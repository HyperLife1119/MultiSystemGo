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
use pocketmine\level\Level;
use pocketmine\block\Block;
use pocketmine\plugin\PluginBase;
use pocketmine\event\block\BlockPlaceEvent;

class BlockPlace{
  public static function onBlockPlace($event,PluginBase $main){
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
    
    //创造方块记录
    if($main->SPS->get("功能设置")["创块禁掉"] == "开" AND 
       !$event->isCancelled() AND 
       $mode == 1 AND
       !in_array($xyz, $main->CBD->get("方块数据"))
      ){
      $createBlockList = $main->CBD->get("方块数据");
      $createBlockList[] = $xyz;
      $main->CBD->set("方块数据", $createBlockList);
      $main->CBD->save();
    }
    
    //个人箱子储存
    if($main->SPS->get("功能设置")["个人箱子"] == "开" AND !$event->isCancelled()){
      if($id == 54 OR $id == 130){
        $main->PCD->set($xyz, ["host" => $playerName, "trust" => []]);
        $main->PCD->save();
        $player->sendMessage("§a=====智能保护系统=====\n§e成功创建个人箱子!\n§e小贴士: 大型箱子是比小型箱子更加安全的个人箱子.");
      }
    }
    
    //如果是禁用方块
    if(in_array($blockData, $main->SPS->get("创造设置")["禁止放置方块"]) AND
       !$event->isCancelled() AND
       !in_array($playerName, $main->SPS->get("创造设置")["白名单"]) AND
       $mode == 1
      ){
      $event->setCancelled(true);
      $level->setBlockIdAt($x, $y, $z, 0);
      $player->sendMessage("§a=====智能保护系统=====\n§e为了不影响游戏的平衡性, 服务器已禁止放置此方块.");
    }
    if(in_array($blockData, $main->SPS->get("生存设置")["禁止放置方块"]) AND
       !$event->isCancelled() AND 
       !in_array($playerName, $main->SPS->get("生存设置")["白名单"]) AND
       $mode == 0
      ){
      $event->setCancelled(true);
      $level->setBlockIdAt($x, $y, $z, 0);
      $player->sendMessage("§a=====智能保护系统=====\n§e为了不影响游戏的平衡性, 服务器已禁止放置此方块.");
    }
  }
}