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
namespace multisystemgo\event\block\method;

use pocketmine\item\Item;

class DropMineral{
  public static function dropMineral($event, $blockData, $multiple){
    switch($blockData){
      case "16:0"://煤矿
        $event->setDrops(array(Item::get(263, 0, $multiple)));
      break;
            
      case "15:0"://铁矿
        $event->setDrops(array(Item::get(265, 0, $multiple)));
      break;
      
      case "14:0"://金矿
        $event->setDrops(array(Item::get(266, 0, $multiple)));
      break;
            
      case "56:0"://钻石矿
        $event->setDrops(array(Item::get(264, 0, $multiple)));
      break;
            
      case "21:0"://青晶石矿
        $event->setDrops(array(Item::get(351, 4, 5 * $multiple)));
      break;
            
      case "74:0"://红石矿
        $event->setDrops(array(Item::get(331, 0, 5 * $multiple)));
      break;
            
      case "129:0"://绿宝石矿
        $event->setDrops(array(Item::get(388, 0, $multiple)));
      break;
            
      case "153:0"://石英矿
        $event->setDrops(array(Item::get(406, 0, $multiple)));
      break;
    }
  }
  
}