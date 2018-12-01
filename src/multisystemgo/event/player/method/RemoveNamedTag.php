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

class RemoveNamedTag{
  //推荐在末尾执行
  public static function removeNamedTag($player){
    if(isset($player->namedtag->PrivilegeFire)){
      unset($player->namedtag->PrivilegeFire);
    }
    if(isset($player->namedtag->CamouflagePlayer)){
      unset($player->namedtag->CamouflagePlayer);
    }
    if(isset($player->namedtag->ClickToJump)){
      unset($player->namedtag->ClickToJump);
    }
    if(isset($player->namedtag->TransferFailure)){
      unset($player->namedtag->TransferFailure);
    }
    if(isset($player->namedtag->URidePlayerS)){
      unset($player->namedtag->URidePlayerS);
    }
    if(isset($player->namedtag->DRidePlayerS)){
      unset($player->namedtag->DRidePlayerS);
    }
    if(isset($player->namedtag->CamouflageBlockData)){
      unset($player->namedtag->CamouflageBlockData);
    }
    if(isset($player->namedtag->ClickToCamouflageBlock)){
      unset($player->namedtag->ClickToCamouflageBlock);
    }
    if(isset($player->namedtag->JoinTheNewRangers)){
      unset($player->namedtag->JoinTheNewRangers);
    }
    if(isset($player->namedtag->CallingAllTitans)){
      unset($player->namedtag->CallingAllTitans);
    }
    if(isset($player->namedtag->ClickChestToAdd)){
      unset($player->namedtag->ClickChestToAdd);
    }
    if(isset($player->namedtag->ClickChestToRemove)){
      unset($player->namedtag->ClickChestToRemove);
    }
    if(isset($player->namedtag->ClickChestToCheck)){
      unset($player->namedtag->ClickChestToCheck);
    }
  }
}