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
namespace multisystemgo\command\prefixgo;

use pocketmine\Player;
use pocketmine\Server;
use pocketmine\plugin\PluginBase;
use pocketmine\command\Command;
use pocketmine\command\CommandExecutor;
use pocketmine\command\CommandSender;
use multisystemgo\command\prefixgo\method\EditPrefix;
use onebone\economyapi\EconomyAPI;

class PrefixGo{
  public static function onCommand($sender, $cmd, $label, $args, PluginBase $main){
    $senderName = $sender->getName();
    $data = $main->PRE->get("玩家头衔");
    
    if($cmd->getName() == "头衔"){
      if(!isset($args[0])){
        if($sender->isOp()){
          $sender->sendMessage("§3=====玩家头衔系统=====\n§c查看我的所有头衔: §f/头衔 我的头衔\n§c佩戴某个玩家头衔: §f/头衔 佩戴 <头衔>\n§c丢弃你的某个头衔: §f/头衔 丢弃 <头衔>\n§c强制给予他人头衔: §f/头衔 给予 <玩家名称> <头衔>\n§c强制移除他人头衔: §f/头衔 移除 <玩家名称> <头衔>\n§c重载玩家头衔系统: §f/头衔 重载");
        }
        else{
          $sender->sendMessage("§3=====玩家头衔系统=====\n§c查看我的所有头衔: §f/头衔 我的头衔\n§c佩戴某个玩家头衔: §f/头衔 佩戴 <头衔>\n§c丢弃你的某个头衔: §f/头衔 丢弃 <头衔>");
        }
        return true;
      }
      
      switch($args[0]){
        case "我的头衔":
          if(isset($data[$senderName]) AND count($data[$senderName]["全部头衔"]) !== 0){
            $sender->sendMessage("§3=====玩家头衔系统=====\n§c当前佩戴: §f{$data[$senderName]["正在使用"]}\n§c全部头衔:");
            foreach($data[$senderName]["全部头衔"] as $key => $value){
              $sender->sendMessage(" §f- ".$value);
            }
            return true;
          }
          else{
            $sender->sendMessage("§3=====玩家头衔系统=====\n§c处理失败, 原因: §f你还未拥有头衔");
            return false;
          }
        break;
        
        case "佩戴":
          if(!isset($args[1])){
            $sender->sendMessage("§3=====玩家头衔系统=====\n§c正确用法: §f/头衔 佩戴 <头衔>");
            return false;
          }
        
          if(!isset($data[$senderName]) OR count($data[$senderName]["全部头衔"]) == 0){
            $sender->sendMessage("§3=====玩家头衔系统=====\n§c处理失败, 原因: §f你还未拥有头衔");
            return false;
          }
          
          $playerData = $data[$senderName];
          
          if(in_array($args[1], $playerData["全部头衔"])){
            $playerData["正在使用"] = $args[1];
            $data[$senderName] = $playerData;
            $main->PRE->set("玩家头衔", $data);
            $main->PRE->save();
            
            $sender->sendMessage("§3=====玩家头衔系统=====\n§c成功佩戴头衔: §f{$args[1]}");
            return true;
          }
          else{
            $sender->sendMessage("§3=====玩家头衔系统=====\n§c处理失败, 原因: §f你还未拥有该头衔");
            return false;
          }
        break;
        
        case "丢弃":
          if(!isset($args[1])){
            $sender->sendMessage("§3=====玩家头衔系统=====\n§c正确用法: §f/头衔 丢弃 <头衔>");
            return false;
          }
          
          if(!isset($data[$senderName])){
            $sender->sendMessage("§3=====玩家头衔系统=====\n§c处理失败, 原因: §f你还未拥有头衔");
            return false;
          }
          
          $playerData = $data[$senderName];
          
          if(in_array($args[1], $playerData["全部头衔"])){
            unset($playerData["全部头衔"][$args[1]]);
            $data[$senderName] = $playerData;
            $main->PRE->set("玩家头衔", $data);
            $main->PRE->save();
            
            if($playerData["正在使用"] == $args[1]){
              $playerData["正在使用"] = "无";
            }
            
            $sender->sendMessage("§3=====玩家头衔系统=====\n§c成功将 §f{$args[1]} §c头衔丢弃!");
            return true;
          }
          else{
            $sender->sendMessage("§3=====玩家头衔系统=====\n§c处理失败, 原因: §f该头衔不存在");
            return false;
          }
        break;
        
        case "给予":
          if(!$sender->isOp()){
            $sender->sendMessage("§3=====玩家头衔系统=====\n§c你没有权限使用该指令!");
            return false;
          }
          
          if(!isset($args[1]) OR !isset($args[2])){
            $sender->sendMessage("§3=====玩家头衔系统=====\n§c正确用法: §f/头衔 给予 <玩家名称> <头衔内容>");
            return false;
          }
          
          if(!isset($data[$args[1]])){
            $data[$args[1]] = ["正在使用" => "无", "全部头衔" => []];
            $main->PRE->set("玩家头衔", $data);
            $main->PRE->save();
          }
          
          if(isset($data[$args[1]]["全部头衔"][$args[2]])){
            $sender->sendMessage("§3=====玩家头衔系统=====\n§c处理失败, 原因: §f该玩家已经拥有该头衔");
            return false;
          }
          else{
            $playerData = $data[$args[1]];
            $playerData["全部头衔"][$args[2]] = $args[2];
            if($playerData["正在使用"] == "无"){
              $playerData["正在使用"] = $args[2];
            }
            $data[$args[1]] = $playerData;
            $main->PRE->set("玩家头衔", $data);
            $main->PRE->save();
            $sender->sendMessage("§3=====玩家头衔系统=====\n§c成功给予玩家 §f{$args[1]} §c头衔: §f{$args[2]}");
            return true;
          }
        break;
        
        case "移除":
          if(!$sender->isOp()){
            $sender->sendMessage("§3=====玩家头衔系统=====\n§c你没有权限使用该指令!");
            return false;
          }
        
          if(!isset($args[1]) OR !isset($args[1])){
            $sender->sendMessage("§3=====玩家头衔系统=====\n§c正确用法: §f/头衔 移除 <玩家名称> <头衔>");
            return false;
          }
          
          if(!isset($data[$args[1]])){
            $sender->sendMessage("§3=====玩家头衔系统=====\n§c处理失败, 原因: §f该玩家并未拥有头衔");
            return false;
          }
          
          if(isset($data[$args[1]]["全部头衔"][$args[2]])){
            $playerData = $data[$args[1]];
            unset($playerData["全部头衔"][$args[2]]);
            $data[$args[1]] = $playerData;
            $main->PRE->set("玩家头衔", $data);
            
            if($playerData["正在使用"] == $args[2]){
              $playerData["正在使用"] = "无";
            }
            
            $main->PRE->save();
            $sender->sendMessage("§3=====玩家头衔系统=====\n§c成功移除玩家 §f{$args[1]} §c的头衔: §f{$args[2]}");
            return true;
          }
          else{
            $sender->sendMessage("§3=====玩家头衔系统=====\n§c处理失败, 原因: §f该玩家并未拥有该头衔");
            return false;
          }
        break;
        
        case "重载":
          if($sender->isOp()){
            $main->PRE->reload();
            $main->FIX->reload();
            $sender->sendMessage("§3=====玩家头衔系统=====\n§c玩家头衔系统重载完成!");
            return true;
          }
          else{
            $sender->sendMessage("§3=====玩家头衔系统=====\n§c你没有权限使用该指令!");
            return false;
          }
        break;
        
        default:
          $sender->sendMessage("§3=====玩家头衔系统=====\n§c未知指令, 请输入 §f/头衔 §c查看指令帮助!");
          return false;
        break;
      }
    }
  }
  
}