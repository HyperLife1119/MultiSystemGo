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

use pocketmine\entity\Entity;
use pocketmine\level\sound\EndermanTeleportSound;

class Stealth{
  public static function stealth($player, $level, $title, $main){
    if($main->PPS->get($title)["潜行隐身"] == "关" OR $player->isFlying()){
      return;
    }
    
    if($player->isSneaking()){
      $player->setDataFlag(Entity::DATA_FLAGS, Entity::DATA_FLAG_INVISIBLE, false);
      $player->sendMessage("§b=====特权玩家系统=====\n§6你已退出潜行隐身模式");
      $level->addSound(new EndermanTeleportSound($player));
    }
    else{
      $player->setDataFlag(Entity::DATA_FLAGS, Entity::DATA_FLAG_INVISIBLE, true);
      $player->sendMessage("§b=====特权玩家系统=====\n§6你已进入潜行隐身模式");
      $level->addSound(new EndermanTeleportSound($player));
    }
  }
}