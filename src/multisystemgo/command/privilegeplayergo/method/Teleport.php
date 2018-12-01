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

use pocketmine\level\sound\EndermanTeleportSound;

class Teleport{
  public static function teleport($player, $targetName, $title, $main){
    if($main->PPS->get($title)["特权传送"] !== "开"){
      $player->sendMessage("§b=====特权玩家系统=====\n§6你还未拥有该特权权限!");
      return false;
    }
    
    if($main->getServer()->getPlayer($targetName) !== null){
      $target = $main->getServer()->getPlayer($targetName);
      
      $player->teleport($target->getPosition());
      $player->getLevel()->addSound(new EndermanTeleportSound($player));
      if($main->PPS->get("功能设置")["传送闪电"] == "开"){
        $main->sendLightning($target);
      }
      
      $player->sendMessage("§b=====特权玩家系统=====\n§6成功传送到玩家 §e{$targetName} §6的身旁.");
      return true;
    }
    else{
      $player->sendMessage("§b=====特权玩家系统=====\n§6无法找到 §e{$targetName} §6玩家!");
      return false;
    }
  }
  
}