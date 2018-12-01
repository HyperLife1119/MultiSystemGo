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

use pocketmine\nbt\tag\StringTag;
use pocketmine\network\mcpe\protocol\types\EntityLink;
use pocketmine\network\mcpe\protocol\SetEntityLinkPacket;

class RidePlayer{
  public static function ridePlayer($player, $target, $main){
    if(!$player->isSneaking()){
      //坐标偏移[左右,上下,前后]
      $player->getDataPropertyManager()->setPropertyValue(57, 8, [0, 1.03, -0.45]);
              
      $pk = new SetEntityLinkPacket();
      $pk->link = new EntityLink($target->getId(), $player->getId(), 2);
      $main->getServer()->broadcastPacket($main->getServer()->getOnlinePlayers(), $pk);
    
      //防止互骑
      $pk = new SetEntityLinkPacket();
      $pk->link = new EntityLink($target->getId(), 0, 2);
      $main->getServer()->broadcastPacket($main->getServer()->getOnlinePlayers(), $pk);
              
      $player->namedtag->URidePlayerS = new StringTag("URidePlayerS", $target->getName());
      $target->namedtag->DRidePlayerS = new StringTag("DRidePlayerS", $player->getName());
              
      $player->sendMessage("§b=====特权玩家系统=====\n§6成功乘骑玩家 §e{$target->getName()}§6, 点击跳跃键/潜行键即可回到地面!");
      $target->sendMessage("§b=====特权玩家系统=====\n§6你已被特权玩家 §e{$player->getName()} §6乘骑, 进入潜行模式即可甩开乘骑玩家!");
    }
    else{
      $player->sendMessage("§b=====特权玩家系统=====\n§6请退出潜行模式再进行乘骑!");
    }
  }
  
}