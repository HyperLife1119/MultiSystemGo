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
namespace multisystemgo\command\privilegeplayergo\method;

use pocketmine\math\Vector3;
use pocketmine\level\Level;
use pocketmine\nbt\tag\StringTag;
use pocketmine\network\mcpe\protocol\types\EntityLink;
use pocketmine\network\mcpe\protocol\SetEntityLinkPacket;

class RidePlayer{
  public static function ridePlayer($player, $target, $title, $a, $b, $c, $main){
    if($main->PPS->get($title)["特权乘骑"] !== "开"){
      $player->sendMessage("§b=====特权玩家系统=====\n§6你还未拥有该特权权限!");
      return false;
    }
    
    if(isset($player->namedtag->URidePlayerS) OR isset($player->namedtag->DRidePlayerS)){
      $player->sendMessage("§b=====特权玩家系统=====\n§6处理失败, 原因: §e当前状态下不允许再次乘骑");
      return false;
    }
    
    if($player->isSneaking()){
      $player->sendMessage("§b=====特权玩家系统=====\n§6请退出潜行模式再进行乘骑!");
      return false;
    }
      
    if($target == null){
      if(count($player->getLevel()->getPlayers()) <= 1){
        $player->sendMessage("§b=====特权玩家系统=====\n§6处理失败, 原因: §e当前世界只有你一位玩家");
        return false;
      }
      
      $player->sendMessage("§b=====特权玩家系统=====\n§6正在匹配离你最近并且允许乘骑的玩家. . .");
      
      $distanceData = [];//存储玩家与我之间的距离数据
      
      foreach($player->getLevel()->getPlayers() as $otherPlayer){
        if($otherPlayer->getName() !== $player->getName()){
          $distanceData[$otherPlayer->getName()] = $player->asVector3()->distance($otherPlayer->asVector3());
        }
      }
      
      foreach($distanceData as $name => $distance){
        if($distance == min($distanceData)){
          $target = $main->getServer()->getPlayer($name);
          if($target !== null AND !isset($target->namedtag->DRidePlayerS)){
            if($title == "顶级特权"){
              $player->sendMessage("§6成功匹配到玩家: {$name}");
              RidePlayer::ride($player, $target, $main);
              return true;
              break;
            }
            
            if($title == "高级特权"){
              if(!in_array($target->getName(), $a)){
                $player->sendMessage("§6成功匹配到玩家: {$name}");
                RidePlayer::ride($player, $target, $main);
                return true;
                break;
              }
            }
            
            if($title == "普通特权"){
              if(!in_array($target->getName(), $a) OR !in_array($target->getName(), $b)){
                $player->sendMessage("§6成功匹配到玩家: {$name}");
                RidePlayer::ride($player, $target, $main);
                return true;
                break;
              }
            }
            
          }
        }
      }
      unset($distanceData);
      return true;
    }
    else{
      if(isset($target->namedtag->DRidePlayerS)){
        $player->sendMessage("§b=====特权玩家系统=====\n§6处理失败, 原因: §e当前玩家 {target->getName()} 的状态不允许乘骑");
        return false;
      }
      
      if($title == "顶级特权"){
        RidePlayer::ride($player, $target, $main);
        return true;
      }
      
      if($title == "高级特权"){
        if(!in_array($target->getName(), $a)){
          RidePlayer::ride($player, $target, $main);
          return true;
        }
        else{
          $player->sendMessage("§b=====特权玩家系统=====\n§6对方的特权等级高于你, 你无法乘骑特权等级高于你的玩家!");
          return false;
        }
      }
      
      if($title == "普通特权"){
        if(!in_array($target->getName(), $a) OR !in_array($target->getName(), $b)){
          RidePlayer::ride($player, $target, $main);
          return true;
        }
        else{
          $player->sendMessage("§b=====特权玩家系统=====\n§6对方的特权等级高于你, 你无法乘骑特权等级高于你的玩家!");
          return false;
        }
      }
    }
  }
  
  public static function ride($player, $target, $main){
    //坐标偏移[左右,上下,前后]
    $player->getDataPropertyManager()->setPropertyValue(57, 8, new Vector3(0, 1.03, -0.45));
            
    $pk = new SetEntityLinkPacket();
    $pk->link = new EntityLink($target->getId(), $player->getId(), 2, true);
    $main->getServer()->broadcastPacket($main->getServer()->getOnlinePlayers(), $pk);
  
    //防止互骑
    $pk = new SetEntityLinkPacket();
    $pk->link = new EntityLink($target->getId(), 0, 2, true);
    $main->getServer()->broadcastPacket($main->getServer()->getOnlinePlayers(), $pk);
            
    $player->namedtag->URidePlayerS = new StringTag("URidePlayerS", $target->getName());
    $target->namedtag->DRidePlayerS = new StringTag("DRidePlayerS", $player->getName());
            
    $player->sendMessage("§b=====特权玩家系统=====\n§6成功乘骑玩家 §e{$target->getName()}§6, 点击跳跃键/潜行键即可回到地面!");
    $target->sendMessage("§b=====特权玩家系统=====\n§6你已被特权玩家 §e{$player->getName()} §6乘骑, 进入潜行模式即可甩开乘骑玩家!");
  }
  
}