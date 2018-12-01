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

class SendFirework{
  public static function sendFirework($player, $title, $main){
    if($main->PPS->get($title)["燃放烟花"] !== "开"){
      $player->sendMessage("§b=====特权玩家系统=====\n§6你还未拥有该特权权限!");
      return false;
    }
                
    if(!$main->FCD->exists($player->getName())){
      $main->FCD->set($player->getName(), time());
      $main->FCD->save();
    }
    
    if(time() >= $main->FCD->get($player->getName())){
      $player->namedtag->PrivilegeFire = new StringTag("PrivilegeFire", "特权烟花");
      
      $main->getServer()->dispatchCommand(new \pocketmine\command\ConsoleCommandSender, "gc");
      
      $main->getServer()->broadcastMessage("§b=====特权玩家系统=====\n§6{$title}玩家§e {$player->getName()} §6即将燃放烟花, 请注意观赏!");
      $player->sendMessage("§b=====特权玩家系统=====\n§6请进入潜行模式来点燃你的特权烟花!");
      return true;
    }
    else{
      $player->sendMessage("§b=====特权玩家系统=====\n§6技能冷却中, 冷却时间还剩: §e".$main->FCD->get($$player->getName()) -time()."秒");
      return false;
    }
  }
  
}