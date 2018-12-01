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
namespace multisystemgo\event\entity;

use pocketmine\Player;
use pocketmine\entity\Entity;
use pocketmine\plugin\PluginBase;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;

class EntityDamage{
  public static function onEntityDamage($event,PluginBase $main){
    $entity = $event->getEntity();
    $cause = $event->getCause();
    
    if($entity instanceof Player){
      if($main->SPS->get("功能设置")["火焰保护"] == "开" AND in_array($cause, [5, 6])){
        $event->setCancelled(true);
      }
      //点地弹跳开启免疫摔伤
      if(isset($entity->namedtag->JumpState) AND $cause == 4){
        $event->setCancelled(true);
      }
    }
    if($main->PRS->get("功能设置")["队友攻击队友"] == "关" AND 
       $event instanceof EntityDamageByEntityEvent
      ){
        $damager = $event->getDamager();
      if(
       $entity instanceof Player AND
       $damager instanceof Player AND
       $main->RPD->exists($entity->getName()) AND
       $main->RPD->exists($damager->getName()) AND
       $main->RPD->get($entity->getName()) == $main->RPD->get($damager->getName())
      ){
      $event->setCancelled(true);
      $damager->sendMessage("§d=====玩家战队系统=====\n§c你无法攻击和你在同一战队的成员!");
    }
    }
  }
}