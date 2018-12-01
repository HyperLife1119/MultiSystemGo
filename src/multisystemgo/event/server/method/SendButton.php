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

use pocketmine\Player;
use pocketmine\entity\Entity;

class SendButton{
  public static function sendButton($player, $target, $main){
    if($target instanceof Player AND
       //如果目标不是NPC
       stripos(get_parent_class($target), "NPC") === false AND
       //如果玩家 没有正在乘骑 和 被骑 和 目标没有被骑
       !isset($player->namedtag->URidePlayerS) AND 
       !isset($player->namedtag->DRidePlayerS) AND 
       !isset($target->namedtag->DRidePlayerS) AND
       !$player->isSneaking()
      ){
      $player->getDataPropertyManager()->setPropertyValue(40, 4, $main->SMS->get("功能设置")["乘骑按钮"]);
    }
  }
  
}