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

use pocketmine\level\Level;

class SetLevelTime{
  public static function setLevelTime($player, $time, $title, $main){
    if($main->PPS->get($title)["改变时间"] !== "开"){
      $sender->sendMessage("§b=====特权玩家系统=====\n§6你还未拥有该特权权限!");
      return false;
    }
  
    if(!$main->TCD->exists($player->getName())){
      $main->TCD->set($player->getName(), time());
      $main->TCD->save();
    }
    
    if(time() >= $main->TCD->get($player->getName())){
      $player->getLevel()->setTime(intval($time));
      
      $main->TCD->set($player->getName(), time() + $main->PPS->get($title)["时间冷却"]);
      $main->TCD->save();
      
      $player->sendMessage("§b=====特权玩家系统=====\n§6成功将你所在世界的时间设置为: §e".intval($time));
      return true;
    }
    else{
      $player->sendMessage("§b=====特权玩家系统=====\n§6指令冷却中, 冷却时间还剩: §e".$main->TCD->get($player->getName()) - time()."秒");
      return false;
    }
  }
  
}