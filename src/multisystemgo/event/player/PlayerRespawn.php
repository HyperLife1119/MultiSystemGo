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
namespace multisystemgo\event\player;

use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\event\player\PlayerRespawnEvent;
use multisystemgo\event\player\method\PrivilegeMode;

class PlayerRespawn{
  public static function onPlayerRespawn($event, PluginBase $main){
    $player = $event->getPlayer();
    $playerName = $player->getName();
    
    $a = $main->A->get("玩家列表");
    $b = $main->B->get("玩家列表");
    $c = $main->C->get("玩家列表");
    
    //如果是顶级特权玩家
    if(in_array($playerName, $a)){
      PrivilegeMode::privilegeMode($player, "顶级特权", $main);
    }
    //如果是高级特权玩家
    elseif(in_array($playerName, $b)){ 
      PrivilegeMode::privilegeMode($player, "高级特权", $main);
    }
    //如果是普通特权玩家
    elseif(in_array($playerName, $c)){
      PrivilegeMode::privilegeMode($player, "普通特权", $main);
    }
    else{
      if($main->PPS->get("功能设置")["附加血量"] == "开"){
        $health = $main->PPS->get("普通玩家")["附加血条"];
        $player->setMaxHealth($player->getMaxHealth() + 20 * $health);
        $player->setHealth($player->getMaxHealth() + 20 * $health);
      }
    }
  }
  
}