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

class RemoveCamouflageBlock{
  public static function removeCamouflageBlock($player, $level, $main){
    if(!isset($main->BlockPos[$player->getName()])){
      return;
    }
    
    if(isset($player->namedtag->CamouflageBlockData)){
      unset($player->namedtag->CamouflageBlockData);
    }
    if(isset($player->namedtag->BlockState)){
      unset($player->namedtag->BlockState);
    }
    
    foreach($main->BlockPos[$player->getName()] as $key => $pos){
      $oldBlockPos = explode(":", $pos);
      //清除方块
      if($level->getBlockIdAt($oldBlockPos[0], $oldBlockPos[1], $oldBlockPos[2]) !== 0){
        $level->setBlockIdAt($oldBlockPos[0], $oldBlockPos[1], $oldBlockPos[2], 0);
      }
    }
    unset($main->BlockPos[$player->getName()]);
    $player->setGamemode(0);
    $player->setDataFlag(Entity::DATA_FLAGS, Entity::DATA_FLAG_INVISIBLE, false);
  }
  
}