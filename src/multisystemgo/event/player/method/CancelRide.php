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
namespace multisystemgo\event\player\method;

use pocketmine\network\mcpe\protocol\types\EntityLink;
use pocketmine\network\mcpe\protocol\SetEntityLinkPacket;

class CancelRide{
  public static function cancelRide($player, $main){
    //如果正在骑人
    if(isset($player->namedtag->URidePlayerS) AND $main->getServer()->getPlayer($player->namedtag->URidePlayerS->getValue()) !== null){
      $target = $main->getServer()->getPlayer($player->namedtag->URidePlayerS->getValue());
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
    
    //如果被骑着
    if(isset($player->namedtag->DRidePlayerS) AND $main->getServer()->getPlayer($player->namedtag->DRidePlayerS->getValue()) !== null){
      $rider = $main->getServer()->getPlayer($player->namedtag->DRidePlayerS->getValue());
      $pk = new SetEntityLinkPacket();
      $pk->link = new EntityLink($player->getId(), $rider->getId(), 0, true);
      $main->getServer()->broadcastPacket($main->getServer()->getOnlinePlayers(), $pk);
     
      if(isset($player->namedtag->DRidePlayerS)){
        unset($player->namedtag->DRidePlayerS);
      }
      if(isset($rider->namedtag->URidePlayerS)){
        unset($rider->namedtag->URidePlayerS);
      }
    
      $player->sendMessage("§b=====特权玩家系统=====\n§6成功甩开乘骑玩家!");
      $rider->sendMessage("§b=====特权玩家系统=====\n§6你已被 §e{$player->getName()} §6玩家甩开!");
    }
  }
  
}