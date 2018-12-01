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

use pocketmine\nbt\tag\StringTag;

class CamouflagePlayer{
  public static function camouflagePlayer($player, $targetName, $title, $main){
    if($main->PPS->get($title)["伪装玩家"] !== "开"){
      $sender->sendMessage("§b=====特权玩家系统=====\n§6你还未拥有该特权权限!");
      return false;
    }
  
    if($main->getServer()->getPlayer($targetName) !== null){
      $player->setSkin($main->getServer()->getPlayer($targetName)->getSkin());
      $player->sendSkin(null);
      
      $player->namedtag->CamouflagePlayer = new StringTag("CamouflagePlayer", "已开启");
      
      if($player->getName() == $targetName){
        unset($player->namedtag->CamouflagePlayer);
        $player->sendMessage("§b=====特权玩家系统=====\n§6成功伪装本体!");
      }
      else{
        $player->sendMessage("§b=====特权玩家系统=====\n§6成功伪装成 §e{$targetName} §6玩家!\n使用指令伪装自己即可解除伪装!");
      }
      return true;
    }
    else{
      $player->sendMessage("§b=====特权玩家系统=====\n§6处理失败, 原因: §e找不到 {$targetName} 玩家");
      return false;
    }
  }
  
}