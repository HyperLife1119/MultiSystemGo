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
namespace multisystemgo\event\server\method;

use pocketmine\network\mcpe\protocol\types\EntityLink;
use pocketmine\network\mcpe\protocol\SetEntityLinkPacket;

class ComeDown{
  public static function comeDown($player, $target, $main){
    $pk = new SetEntityLinkPacket();
    $pk->link = new EntityLink($target->getId(), $player->getId(), 0, true);
    $main->getServer()->broadcastPacket($main->getServer()->getOnlinePlayers(), $pk);
    
    if(isset($player->namedtag->URidePlayerS)){
      unset($player->namedtag->URidePlayerS);
    }
    if(isset($target->namedtag->DRidePlayerS)){
      unset($target->namedtag->DRidePlayerS);
    }
            
    $target->sendMessage("§b=====特权玩家系统=====\n§e{$player->getName()} §6玩家已从你的肩膀上落回地面!");
    $player->sendMessage("§b=====特权玩家系统=====\n§6成功落回地面!");
  }
  
}