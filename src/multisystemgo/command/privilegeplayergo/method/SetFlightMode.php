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
namespace multisystemgo\command\privilegeplayergo\method;

use pocketmine\math\Vector3;

class SetFlightMode{
  public static function setFlightMode($player, $title, $main){
    if($main->PPS->get($title)["飞行模式"] !== "开"){
      $sender->sendMessage("§b=====特权玩家系统=====\n§6你还未拥有该特权权限!");
      return false;
    }
    
    if($player->getAllowFlight()){
      $player->setAllowFlight(false);
      $player->teleport($main->findfloor($player->getLevel(), new Vector3($player->getFloorX(), $player->getFloorY(), $player->getFloorZ())));
      $player->setMotion(new Vector3(0, -1.5, 0));
        
      $player->sendMessage("§b=====特权玩家系统=====\n§6特权飞行模式已关闭!");
      return true;
    }
    else{
      $player->setAllowFlight(true);
      
      $player->sendMessage("§b=====特权玩家系统=====\n§6特权飞行模式已开启!");
      return false;
    }
  }
    
}