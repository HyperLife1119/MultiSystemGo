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
namespace multisystemgo\event\block;

use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\event\block\ItemFrameDropItemEvent;

class ItemFrameDropItem{
  public static function onItemFrameDropItem($event){
    $player = $event->getPlayer();
    
    if(!$player->isOp()){
      $event->setCancelled(true);
      $player->sendMessage("§a=====智能保护系统=====\n§e为了不影响游戏的平衡性, 服务器已禁止玩家与展示框交互.");
    }
  }
  
}