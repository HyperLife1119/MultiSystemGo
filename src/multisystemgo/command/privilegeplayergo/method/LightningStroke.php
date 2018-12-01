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

class LightningStroke{
  public static function lightningStroke($player, $targetName, $title, $main){
    if($main->PPS->get($title)["特权电击"] !== "开"){
      $sender->sendMessage("§b=====特权玩家系统=====\n§6你还未拥有该特权权限!");
      return false;
    }
  
    if($main->getServer()->getPlayer($targetName) == null){
      $player->sendMessage("§b=====特权玩家系统=====\n§6处理失败, 原因: §e找不到 {$targetName} 玩家");
      return false;
    }
      
    if(!$main->LCD->exists($player->getName())){
      $main->LCD->set($player->getName(), time());
      $main->LCD->save();
     }
    
    if(time() >= $main->LCD->get($player->getName())){
      $target = $main->getServer()->getPlayer($targetName);
      $main->sendLightning($target);
      
      if($target->getGamemode() == 0){
        $target->setHealth($target->getHealth() - $main->PPS->get($title)["电击伤害"] * 2);
      }
      
      $main->LCD->set($player->getName(), time() + $main->PPS->get($title)["电击冷却"]);
      $main->LCD->save();
      
      $main->getServer()->broadcastMessage("§b=====特权玩家系统=====\n§6玩家 §e{$targetName} §6遭受了{$title}玩家 §e{$player->getName()} §6施加的闪电击, 伤害为: §e".$main->PPS->get($title)["电击伤害"]);
      return true;
    }
    else{
      $player->sendMessage("§b=====特权玩家系统=====\n§6技能冷却中, 冷却时间还剩: §e".$allTime-$nowTime."秒");
      return false;
    }
  }
  
}