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
namespace multisystemgo\event\block;

use pocketmine\Player;
use pocketmine\block\Block;
use pocketmine\level\Level;
use pocketmine\plugin\PluginBase;
use pocketmine\event\block\BlockBreakEvent;
use multisystemgo\event\block\method\DropMineral;

class BlockBreak{
  public static function onBlockBreak($event, PluginBase $main){
    $player = $event->getPlayer();
    $playerName = $player->getName();
    $mode = $player->getGamemode();
    
    $level = $player->getLevel();
    $levelName = $level->getFolderName();
    
    $block = $event->getBlock();
    $id = $block->getId();
    $damage = $block->getDamage();
    $blockData = "{$id}:{$damage}";
    
    $x = $block->getX();
    $y = $block->getY();
    $z = $block->getZ();
    $xyz = "{$levelName}:{$x}:{$y}:{$z}";
    
    //如果被破坏的是伪装方块
    if($main->BlockPos !== null){
      //用于储存所有的伪装方块坐标
      $blockPos = [];
      foreach($main->BlockPos as $name => $xyz){
        foreach($main->BlockPos[$name] as $key => $pos){
          $blockPos[] = $pos;
        }
      }
      //如果是伪装方块
      if(in_array("{$x}:{$y}:{$z}", $blockPos) AND !$event->isCancelled()){
        $event->setCancelled(true);
        $level->setBlockIdAt($x, $y, $z, 0);
      }
    }
    
    $a = $main->A->get("玩家列表");
    $b = $main->B->get("玩家列表");
    $c = $main->C->get("玩家列表");
    
    //如果破坏事件没有被取消
    if(!$event->isCancelled()){
      if(in_array($playerName, $a) AND $main->PPS->get("顶级特权")["多倍矿物"] == "开"){
        $multiple = $main->PPS->get("顶级特权")["矿物倍数"];
        DropMineral::dropMineral($event, $blockData, $multiple);
      }
      if(in_array($playerName, $b) AND $main->PPS->get("高级特权")["多倍矿物"] == "开"){
        $multiple = $main->PPS->get("高级特权")["矿物倍数"];
        DropMineral::dropMineral($event, $blockData, $multiple);
      }
      if(in_array($playerName, $c) AND $main->PPS->get("普通特权")["多倍矿物"] == "开"){
        $multiple = $main->PPS->get("普通特权")["矿物倍数"];
        DropMineral::dropMineral($event, $blockData, $multiple);
      }
    }
    
    $createBlockList = $main->CBD->get("方块数据");
    
    if($id == 54 AND 
       $main->SPS->get("功能设置")["个人箱子"] == "开" AND
       !$event->isCancelled() AND
       $main->PCD->exists($xyz)
      ){
      //判断是不是箱子的主人
      if($playerName == $main->PCD->get($xyz)["host"]){
        $main->PCD->remove($xyz);
        $main->PCD->save();
        $player->sendMessage("§a=====智能保护系统=====\n§e成功销毁了你的个人箱子.");
      }
      else{
        if($player->isOp()){
          $main->PCD->remove($xyz);
          $main->PCD->save();
          $player->sendMessage("§a=====智能保护系统=====\n§e你销毁了他人的个人箱子.");
        }
        else{
          $event->setCancelled(true);
          $player->sendMessage("§a=====智能保护系统=====\n§e你无法破坏他人的个人箱子.");
        }
      }
    }
    
    if(in_array($blockData, $main->SPS->get("创造设置")["禁止破坏方块"]) AND 
       !in_array($playerName, $main->SPS->get("创造设置")["白名单"]) AND 
       $mode == 1
      ){
      $event->setCancelled(true);
      $player->sendMessage("§a=====智能保护系统=====\n§e为了不影响游戏的平衡性, 服务器已禁止破坏此方块.");
    }
    if(in_array($blockData, $main->SPS->get("生存设置")["禁止破坏方块"]) AND
       !in_array($playerName, $main->SPS->get("生存设置")["白名单"]) AND 
       $mode == 0
      ){
      $event->setCancelled(true);
      $player->sendMessage("§a=====智能保护系统=====\n§e为了不影响游戏的平衡性, 服务器已禁止破坏此方块.");
    }
    
    
    //销毁玩家头衔木牌
    if(in_array($xyz, $main->PRE->get("自定义头衔商店木牌")) AND !$event->isCancelled()){
      if($player->isOp()){
        $data = $main->PRE->get("自定义头衔商店木牌");
        unset($data[$xyz]);
        $main->PRE->set("自定义头衔商店木牌", $data);
        $main->PRE->save();
        $player->sendMessage("§3=====玩家头衔系统=====\n§c成功销毁玩家头衔商店木牌!");
      }
      else{
        $event->setCancelled(true);
        $player->sendMessage("§3=====玩家头衔系统=====\n§c你没有权限销毁玩家头衔商店木牌!");
      }
    }
    if(array_key_exists($xyz, $main->PRE->get("固定义头衔商店木牌")) AND !$event->isCancelled()){
      if($player->isOp()){
        $data = $main->PRE->get("固定义头衔商店木牌");
        unset($data[$xyz]);
        $main->PRE->set("固定义头衔商店木牌", $data);
        $main->PRE->save();
        $player->sendMessage("§3=====玩家头衔系统=====\n§c成功销毁玩家头衔商店木牌!");
      }
      else{
        $event->setCancelled(true);
        $player->sendMessage("§3=====玩家头衔系统=====\n§c你没有权限销毁玩家头衔商店木牌!");
      }
    }
    
    if($main->RDS->exists($xyz) AND !$event->isCancelled()){
      //如果玩家是队长而且他有设置战队宣传木牌
      if($main->RCD->exists($playerName) AND $main->RDS->exists($main->RCD->get($playerName))){
        $rangers = $main->RCD->get($playerName);
        $main->RDS->remove($main->RDS->get($rangers));
        $main->RDS->remove($rangers);
        $main->RDS->save();
        $player->sendMessage("§d=====玩家战队系统=====\n§c成功销毁你的战队宣传木牌!");
      }
      else{
        $event->setCancelled(true);
        $player->sendMessage("§d=====玩家战队系统=====\n§c你无法破坏他人战队的宣传木牌!");
      }
    }
    if($main->RCS->exists($xyz) AND !$event->isCancelled()){
      if($player->isOp()){
        $main->RCS->remove($xyz);
        $main->RCS->save();
        $player->sendMessage("§d=====玩家战队系统=====\n§c成功销毁战队人数排行榜木牌!");
      }
      else{
        $event->setCancelled(true);
        $player->sendMessage("§d=====玩家战队系统=====\n§c你没有权限销毁战队人数排行榜木牌!");
      }
    }
    if($main->RMS->exists($xyz) AND !$event->isCancelled()){
      if($player->isOp()){
        $main->RMS->remove($xyz);
        $main->RMS->save();
        $player->sendMessage("§d=====玩家战队系统=====\n§c成功销毁战队基金排行榜木牌!");
      }
      else{
        $event->setCancelled(true);
        $player->sendMessage("§d=====玩家战队系统=====\n§c你没有权限销毁战队基金排行榜木牌!");
      }
    }
    if($mode == 0 AND $main->SPS->get("功能设置")["创块禁掉"] == "开"){
      $blockDataList = implode(",", $createBlockList);
      if(strpos($blockDataList, $xyz) !== false AND !$event->isCancelled()){
        $event->setCancelled(true);
        $level->setBlockIdAt($x, $y, $z, 0);
        $inv = array_search($xyz, $createBlockList);
        $inv = array_splice($createBlockList, $inv, 1);
        $main->CBD->set("方块数据", $createBlockList);
        $main->CBD->save();
        $player->sendMessage("§a=====智能保护系统=====\n§e为了不影响游戏的平衡性, 生存模式下破坏创造模式下放置的方块不会掉落物品!");
      }
    }
  }
  
}