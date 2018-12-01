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

class ClickToJump{
  public static function clickToJump($player, $title, $main){
    if($main->PPS->get($title)["点地弹跳"] !== "开"){
      $sender->sendMessage("§b=====特权玩家系统=====\n§6你还未拥有该特权权限!");
      return false;
    }
            
    if(isset($player->namedtag->ClickToJump)){
      unset($player->namedtag->ClickToJump);
      $player->sendMessage("§b=====特权玩家系统=====\n§6点地弹跳模式已关闭!");
      return true;
    }
    else{
      $player->namedtag->ClickToJump = new StringTag("ClickToJump", "已开启");
      $player->sendMessage("§b=====特权玩家系统=====\n§6点地弹跳模式已开启!");
      return true;
    }
  }
  
}