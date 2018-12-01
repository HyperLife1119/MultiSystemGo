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
namespace multisystemgo\command\prefixgo\method;

class EditPrefix{
  public static function editPrefix($sender, $title, $prefix, $main){
    if($main->PPS->get($title)["编辑头衔"] == "开"){
      $main->PRE->set($sender->getName(), $prefix);
      $main->PRE->save();
      $sender->sendMessage("§3=====玩家头衔系统=====\n§c处理成功! 已将你的头衔设置为: §f".$prefix);
    }
    else{
      $sender->sendMessage("§3=====玩家头衔系统=====\n§c你还未拥有编辑头衔权限!");
    }
  }
  
}