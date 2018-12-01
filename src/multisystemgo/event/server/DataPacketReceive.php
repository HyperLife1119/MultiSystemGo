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
namespace multisystemgo\event\server;

use pocketmine\Player;
use pocketmine\Server;
use pocketmine\level\Level;
use pocketmine\entity\Entity;
use pocketmine\plugin\PluginBase;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\network\mcpe\protocol\InteractPacket;
use pocketmine\network\mcpe\protocol\SetEntityLinkPacket;
use pocketmine\nbt\tag\StringTag;
use multisystemgo\event\server\method\ComeDown;
use multisystemgo\event\server\method\RidePlayer;
use multisystemgo\event\server\method\SendButton;

class DataPacketReceive{
  public static function onDataPacketReceive($event, PluginBase $main){
    $packet = $event->getPacket();
    
    if(!$packet instanceof InteractPacket){
      return;
    }
    
    $player = $event->getPlayer();
    $playerName = $player->getName();
    
    $target = $player->getLevel()->getEntity($packet->target);
    
    $item = $player->getItemInHand();
    $id = $item->getId();
    $damage = $item->getDamage();
    $itemData = "{$id}:{$damage}";
    
    $a = $main->A->get("玩家列表");
    $b = $main->B->get("玩家列表");
    $c = $main->C->get("玩家列表");
    /*
    //点击玩家查看信息
    if($itemData == $main->SMS->get("功能设置")["信息物品"] AND 
       $packet->action == 2 AND 
       $target instanceof Player AND 
       //如果目标不是NPC
       stripos(get_parent_class($target), "NPC") === false
      ){
      $player->sendMessage($main->nestedTag($target, $main, $main->SMS->get("功能设置")["信息文本"]));
    }
    */
    
    //乘骑玩家
    if(in_array($playerName, $a) AND $main->PPS->get("顶级特权")["特权乘骑"] == "开"){
      /*
      SendButton::sendButton($player, $target, $main);
      
      //如果玩家按下按钮
      if($packet->action == 1 AND 
         !isset($player->namedtag->URidePlayerS) AND 
         !isset($player->namedtag->DRidePlayerS) AND 
         !isset($target->namedtag->DRidePlayerS)
        ){
        RidePlayer::ridePlayer($player, $target, $main);
      }
      */
      
      //如果玩家落地
      if($packet->action == 3){
        ComeDown::comeDown($player, $target, $main);
      }
    }
    if(in_array($playerName, $b) AND $main->PPS->get("高级特权")["特权乘骑"] == "开"){
      /*
      SendButton::sendButton($player, $target, $main);
      
      //如果玩家按下按钮
      if($packet->action == 1 AND 
         !isset($player->namedtag->URidePlayerS) AND 
         !isset($player->namedtag->DRidePlayerS) AND 
         !isset($target->namedtag->DRidePlayerS)
        ){
        if(!in_array($target->getName(), $a)){
          RidePlayer::ridePlayer($player, $target, $main);
        }
        else{
          $player->sendMessage("§b=====特权玩家系统=====\n§6对方的特权等级高于你, 你没有资格乘骑特权等级高于你的玩家!");
        }
      }
      */
      
      //如果玩家落地
      if($packet->action == 3){
        ComeDown::comeDown($player, $target, $main);
      }
    }
    if(in_array($playerName, $c) AND $main->PPS->get("普通特权")["特权乘骑"] == "开"){
      /*
      SendButton::sendButton($player, $target, $main);
      
      //如果玩家按下按钮
      if($packet->action == 1 AND 
         !isset($player->namedtag->URidePlayerS) AND 
         !isset($player->namedtag->DRidePlayerS) AND 
         !isset($target->namedtag->DRidePlayerS)
        ){
        if(!in_array($target->getName(), $a) OR !in_array($target->getName(), $b)){
          RidePlayer::ridePlayer($player, $target, $main);
        }
        else{
          $player->sendMessage("§b=====特权玩家系统=====\n§6对方的特权等级高于你, 你没有资格乘骑特权等级高于你的玩家!");
        }
      }
      */
      
      //如果玩家落地
      if($packet->action == 3){
        ComeDown::comeDown($player, $target, $main);
      }
    }
  }
  
}