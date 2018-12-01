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

class SetGameMode{
  public static function setGameMode($player, $mode, $title, $main){
    if($main->PPS->get($title)["切换模式"] !== "开"){
      $player->sendMessage("§b=====特权玩家系统=====\n§6你还未拥有该特权权限!");
      return false;
    }
  
    if(in_array(intval($mode), $main->PPS->get($title)["模式界限"])){
      $player->setGamemode(intval($mode));
      $player->sendMessage("§b=====特权玩家系统=====\n§6成功将你的游戏模式切换为: §e".str_replace([0, 1, 2, 3], ["生存模式", "创造模式", "冒险模式", "旁观模式"], intval($mode)));
      return true;
    }
    else{
      $player->sendMessage("§b=====特权玩家系统=====\n§6处理失败, 原因: §e你只能在 ".implode(", ", $main->PPS->get($title)["模式界限"])." 模式之间切换");
      return false;
    }
  }
  
}