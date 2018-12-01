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
use pocketmine\event\player\PlayerDropItemEvent;

class PlayerDropItem{
  public static function onPlayerDropItem($event){
    $player = $event->getPlayer();
    $mode = $player->getGamemode();
    
    if($mode == 1 AND !$player->isOp()){
      $event->setCancelled(true);
      $player->sendMessage("§a=====智能保护系统=====\n§e创造模式下, 非管理员玩家禁止丢弃物品!");
    }
  }
  
}