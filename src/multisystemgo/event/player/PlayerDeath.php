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
use pocketmine\plugin\PluginBase;
use pocketmine\item\Item;
use pocketmine\event\player\PlayerDeathEvent;
use multisystemgo\event\player\method\RemoveCamouflageEntity;
use multisystemgo\event\player\method\RemoveCamouflageBlock;
use multisystemgo\event\player\method\CancelRide;

class PlayerDeath{
  public static function onPlayerDeath($event, PluginBase $main){
    $player = $event->getPlayer();
    
    if($main->SPS->get("功能设置")["死亡掉落"] == "关"){
      $event->setDrops(array(Item::get(0, 0, 0)));
    }
    
    if($main->SPS->get("功能设置")["死亡背包"] == "开"){
      $event->setKeepInventory(true);
    }
    
    if($main->SPS->get("功能设置")["死亡经验"] == "开"){
      $event->setKeepExperience(true);
    }
    
    //移除发包生物
    RemoveCamouflageEntity::removeCamouflageEntity($player, $main);
    
    //如果玩家正在骑人则取消骑人 或者 如果玩家被骑着就死掉则取消被骑
    CancelRide::cancelRide($player, $main);
  }
  
}