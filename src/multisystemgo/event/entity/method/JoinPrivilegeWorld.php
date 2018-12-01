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
namespace multisystemgo\event\entity\method;

use pocketmine\nbt\tag\StringTag;

class JoinPrivilegeWorld{
  public static function joinPrivilegeWorld($player, $PrivilegeWorldList, $PrivilegePlayerList, $levelName, $title){
    if(in_array($levelName, $PrivilegeWorldList)){
      if($player->isOp()){
        $player->sendMessage("§b=====特权玩家系统=====\n§6欢迎进入特权世界: §e{$levelName}");
      }
      else{
        if(in_array($playerName, $PrivilegePlayerList)){
          $player->sendMessage("§b=====特权玩家系统=====\n§6欢迎进入特权世界: §e{$levelName}");
        }
        else{
          $event->setCancelled(true);
          $player->namedtag->TransferFailure = new StringTag("TransferFailure", "未出生");
          $player->sendMessage("§b=====特权玩家系统=====\n§6你还不是{$title}特权玩家, 无法进入顶级特权世界!");
        }
      }
    }
  }
  
}