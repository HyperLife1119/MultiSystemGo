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
use pocketmine\Server;
use pocketmine\level\Level;
use pocketmine\plugin\PluginBase;
use pocketmine\event\player\PlayerQuitEvent;
use multisystemgo\event\player\method\CancelRide;
use multisystemgo\event\player\method\RemoveNamedTag;
use multisystemgo\event\player\method\RemoveCamouflageEntity;
use multisystemgo\event\player\method\RemoveCamouflageBlock;

class PlayerQuit{
  public static function onPlayerQuit($event, PluginBase $main){
    $player = $event->getPlayer();
    $playerName = $player->getName();
    
    $level = $player->getLevel();
    
    //记录活跃时间
    $main->PAT->set($playerName, time());
    $main->PAT->save();
    
    //通过执行gc指令优化服务器
    if($main->SPS->get("功能设置")["智能优化"] == "开"){
  	    $main->getServer()->dispatchCommand(new \pocketmine\command\ConsoleCommandSender, "gc");
    }
    
    if($main->SMS->get("功能设置")["名称美化"] == "开"){
      foreach($main->getServer()->getOnlinePlayers() as $players){
        $players->setNameTag($main->nestedTag($players, $main, $main->SMS->get("功能设置")["头部名称"]));
      }
    }
    
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