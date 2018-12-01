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
use pocketmine\network\mcpe\protocol\RemoveEntityPacket;

class RemoveCamouflageEntity{
  public static function removeCamouflageEntity($player, $main){
    if(isset($player->namedtag->CamouflageEntity)){
      $pk = new RemoveEntityPacket();
      $pk->entityUniqueId = $player->namedtag->CamouflageEntity->getValue();
      $main->getServer()->broadcastPacket($main->getServer()->getOnlinePlayers(), $pk);
      unset($player->namedtag->CamouflageEntity);
      unset($player->namedtag->CamouflageEntityId);
      $player->setDataFlag(Entity::DATA_FLAGS, Entity::DATA_FLAG_INVISIBLE, false);
    }
  }
}