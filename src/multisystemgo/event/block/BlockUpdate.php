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

use pocketmine\plugin\PluginBase;
use pocketmine\event\block\BlockUpdateEvent;

class BlockUpdate{
  public static function onBlockUpdate($event, PluginBase $main){
    $id = $event->getBlock()->getId();
    
    if($main->SPS->get("功能设置")["禁框更新"] == "开" AND $id == 199){
      $event->setCancelled(true);
    }
  }
  
}