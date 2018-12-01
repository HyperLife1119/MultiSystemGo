<?php

/*
  __   __                                        ______              __
  \ \  \ \                                      / _____\            / /
   \ \__\ \  __    __  _____   _____    ____   / / ____    _____   / /         
    \  ___ \ \ \  / / / ___ \ / ___ \  / ___\ / / /___ \  / ___ \ /_/
     \ \  \ \ \ \/ / / /__/ // _____/ / /     \ \____/ / / /__/ / __
      \_\  \_\ \  / / _____/ \______//_/       \______/  \_____/ /_/
              _/ / / /
             /__/ /_/

                      HyperGo!|Copyright © 保留所有权利
                           Powered By HyperGo!
                            author HyperLife
*/

namespace multisystemgo\event\player;

use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\event\player\PlayerKickEvent;
use multisystemgo\event\player\method\CancelRide;
use multisystemgo\event\player\method\RemoveNamedTag;
use multisystemgo\event\player\method\RemoveCamouflageEntity;
use multisystemgo\event\player\method\RemoveCamouflageBlock;

class PlayerKick{
  public static function onPlayerKick($event, PluginBase $main){
    $player = $event->getPlayer();
    $playerName = $player->getName();
    $level = $player->getLevel();
   
    //记录活跃时间
    $main->PAT->set($playerName, time());
    $main->PAT->save();
   
    //如果玩家正在骑人则取消骑人 或者 如果玩家被骑着就死掉则取消被骑
    CancelRide::cancelRide($player, $main);
   
    //移除发包生物
    RemoveCamouflageEntity::removeCamouflageEntity($player, $main);
    
    //移除伪装方块
    RemoveCamouflageBlock::removeCamouflageBlock($player, $level, $main);
   
    //清理namedtag
    RemoveNamedTag::removeNamedTag($player);
  }
  
}