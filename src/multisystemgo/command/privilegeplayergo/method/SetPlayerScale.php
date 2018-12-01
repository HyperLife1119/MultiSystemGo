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

class SetPlayerScale{
  public static function setPlayerScale($player, $scale, $title, $main){
    if($main->PPS->get($title)["改变尺寸"] !== "开"){
      $player->sendMessage("§b=====特权玩家系统=====\n§6你还未拥有该特权权限!");
      return false;
      return;
    }
    
    if(floatval($scale) >= 0.1 AND floatval($scale) <= 10){
      $player->setScale(floatval($scale));
      $player->sendMessage("§b=====特权玩家系统=====\n§6成功将身体尺寸调整为: §e".floatval($scale)." §6, 正常的身体尺寸为: §e1");
      if(floatval($scale) >= 3){
        $main->getServer()->broadcastMessage("§b=====特权玩家系统=====\n§6{$title}玩家 §e{$player->getName()} §6获得了光的力量并成为了光之巨人!");
      }
      return true;
    }
    else{
      $player->sendMessage("§b=====特权玩家系统=====\n§6处理失败, 原因: §e尺寸超出了标准限制 0.1 - 10");
      return false;
    }
  }
  
}