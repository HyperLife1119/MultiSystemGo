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
namespace multisystemgo\event\player;

use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\level\Level;
use pocketmine\event\player\PlayerCommandPreprocessEvent;

class PlayerCommandPreprocess{
  public static function onPlayerCommandPreprocess($event, PluginBase $main){
    $player = $event->getPlayer();
    $playerName = $player->getName();
    
    $level = $player->getLevel();
    $levelName = $level->getFolderName();
    
    $msg = $event->getMessage();
    
    if(substr($msg, 0, 1) !== "/"){
      return;
    }
    
    //禁用指令
    if(array_key_exists($levelName, $main->SPS->get("世界设置")) AND !$player->isOp()){
      $worldInfo = $main->SPS->get("世界设置")[$levelName];
      if(!in_array($playerName, $worldInfo["白名单"])){
        foreach($worldInfo["禁用指令列表"] as $cmds){
          if($cmds == substr($msg, 0, strlen($cmds))){
            $event->setCancelled();
            $player->sendMessage("§a=====智能保护系统=====\n§e指令执行失败, 原因: §f你不是白名单玩家, 该指令无法在该世界执行");
            break;
          }
        }
      }
    }
    
    //后台限制指令
    if(!in_array($playerName, $main->SPS->get("指令设置")["白名单"])){
      foreach($main->SPS->get("指令设置")["后台限制"] as $cmds){
        if($cmds == substr($msg, 0, strlen($cmds))){
          $event->setCancelled();
          $player->sendMessage("§a=====智能保护系统=====\n§e指令执行失败, 原因: §f该指令只允许在控制台执行");
          break;
        }
      }
    }
    
    //刷新列表名称
    if($main->SMS->get("功能设置")["列表美化"] == "开"){
      foreach($main->getServer()->getOnlinePlayers() as $players){
        $players->setDisplayName($main->nestedTag($players, $main, $main->SMS->get("功能设置")["列表格式"]));
      }
    }
    
    //刷新头部名称
    if($main->SMS->get("功能设置")["名称美化"] == "开"){
      foreach($main->getServer()->getOnlinePlayers() as $players){
        $players->setNameTag($main->nestedTag($players, $main, $main->SMS->get("功能设置")["头部名称"]));
      }
    }
  }
  
}