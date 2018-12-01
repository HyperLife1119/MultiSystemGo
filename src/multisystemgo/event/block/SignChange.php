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
use pocketmine\event\block\SignChangeEvent;

class SignChange{
  public static function onSignChange($event, PluginBase $main){
    $player = $event->getPlayer();
    $playerName = $player->getName();
    
    $lines = $event->getLines();
    
    $level = $player->getLevel();
    $levelName = $level->getFolderName();
    
    $block = $event->getBlock();
    $x = $block->getX();
    $y = $block->getY();
    $z = $block->getZ();
    
    if($lines[0] == "玩家头衔"){
      if(!$player->isOp()){
        $player->sendMessage("§3=====玩家头衔系统=====\n§c你还没没有权限创建玩家头衔商店木牌!");
        return;
      }
      
      if($lines[1] == "自定义"){
        $pirce = $main->FIX->get("自定义头衔")["头衔价格"];
        $event->setLine(0, str_replace(["{价格}", "{头衔}"], [$pirce, "自定义头衔"], $main->FIX->get("自定义头衔")["木牌格式"]["第一行"]));
        $event->setLine(1, str_replace(["{价格}", "{头衔}"], [$pirce, "自定义头衔"], $main->FIX->get("自定义头衔")["木牌格式"]["第二行"]));
        $event->setLine(2, str_replace(["{价格}", "{头衔}"], [$pirce, "自定义头衔"], $main->FIX->get("自定义头衔")["木牌格式"]["第三行"]));
        $event->setLine(3, str_replace(["{价格}", "{头衔}"], [$pirce, "自定义头衔"], $main->FIX->get("自定义头衔")["木牌格式"]["第四行"]));
        
        $data = $main->PRE->get("自定义头衔商店木牌");
        $data["{$levelName}:{$x}:{$y}:{$z}"] = "{$levelName}:{$x}:{$y}:{$z}";
        $main->PRE->set("自定义头衔商店木牌", $data);
        $main->PRE->save();
        
        $player->sendMessage("§3=====玩家头衔系统=====\n§c成功创建自定义头衔商店木牌!");
      }
      elseif($lines[1] !== null AND $lines[1] !==""){
        if(is_numeric($lines[2])){
          $event->setLine(0, str_replace(["{价格}", "{头衔}"], [$lines[2], $lines[1]], $main->FIX->get("固定义头衔")["木牌格式"]["第一行"]));
          $event->setLine(1, str_replace(["{价格}", "{头衔}"], [$lines[2], $lines[1]], $main->FIX->get("固定义头衔")["木牌格式"]["第二行"]));
          $event->setLine(2, str_replace(["{价格}", "{头衔}"], [$lines[2], $lines[1]], $main->FIX->get("固定义头衔")["木牌格式"]["第三行"]));
          $event->setLine(3, str_replace(["{价格}", "{头衔}"], [$lines[2], $lines[1]], $main->FIX->get("固定义头衔")["木牌格式"]["第四行"]));
          
          $data = $main->PRE->get("固定义头衔商店木牌");
          $data["{$levelName}:{$x}:{$y}:{$z}"] = ["头衔价格" => doubleval($lines[2]), "头衔内容" => $lines[1]];
          $main->PRE->set("固定义头衔商店木牌", $data);
          $main->PRE->save();
          
          $player->sendMessage("§3=====玩家头衔系统=====\n§c成功创建固定义头衔商店木牌!");
        }
        else{
          $player->sendMessage("§3=====玩家头衔系统=====\n§c请在木牌的第三行输入头衔价格!");
        }
      }
      else{
        $player->sendMessage("§3=====玩家头衔系统=====\n§c请在木牌的第二行\n§c输入 §f自定义 §c来创建自定义头衔商店木牌 或 输入 §f头衔内容 §c来创建固定义头衔商店木牌!");
      }
    }
    
    if($lines[0] == "战队宣传"){
      if(!$main->RCD->exists($playerName)){
        $player->sendMessage("§d=====玩家战队系统=====\n§c你还不是战队队长, 无法创建战队宣传木牌!");
      }
      $rangers = $main->RCD->get($playerName);
      if($main->RDS->exists($rangers)){
        $player->sendMessage("§d=====玩家战队系统=====\n§c战队宣传木牌创建失败, 原因: §6为了防止战队宣传木牌泛滥, 你只能创建一个战队宣传木牌");
      }
      else{
        $event->setLine(0, $main->rangers($rangers, $main->PRS->get("战队木牌")["战队宣传木牌"]["第一行"]));
        $event->setLine(1, $main->rangers($rangers, $main->PRS->get("战队木牌")["战队宣传木牌"]["第二行"]));
        $event->setLine(2, $main->rangers($rangers, $main->PRS->get("战队木牌")["战队宣传木牌"]["第三行"]));
        $event->setLine(3, $main->rangers($rangers, $main->PRS->get("战队木牌")["战队宣传木牌"]["第四行"]));
        $main->RDS->set($rangers, "{$levelName}:{$x}:{$y}:{$z}");
        $main->RDS->set("{$levelName}:{$x}:{$y}:{$z}", $rangers);
        $main->RDS->save();
        $player->sendMessage("§d=====玩家战队系统=====\n§c成功创建战队宣传木牌!");
      }
    }
    if($lines[0] == "战队排行"){
      if(!$player->isOp()){
        $player->sendMessage("§d=====玩家战队系统=====\n§c你还没没有权限创建战队排行木牌!");
        return;
      }
    
      if($lines[1] == "人数排行"){
        $main->RCS->set("{$levelName}:{$x}:{$y}:{$z}");
        $main->RCS->save();
        $event->setLine(0, $main->PRS->get("战队木牌")["人数排行木牌"]["第一行"]);
        $event->setLine(1, $main->PRS->get("战队木牌")["人数排行木牌"]["第二行"]);
        $event->setLine(2, $main->PRS->get("战队木牌")["人数排行木牌"]["第三行"]);
        $event->setLine(3, $main->PRS->get("战队木牌")["人数排行木牌"]["第四行"]);
        
        $player->sendMessage("§d=====玩家战队系统=====\n§c成功创建战队人数排行木牌!");
      }
      elseif($lines[1] == "基金排行"){
        $main->RMS->set("{$levelName}:{$x}:{$y}:{$z}");
        $main->RMS->save();
        $event->setLine(0, $main->PRS->get("战队木牌")["基金排行木牌"]["第一行"]);
        $event->setLine(1, $main->PRS->get("战队木牌")["基金排行木牌"]["第二行"]);
        $event->setLine(2, $main->PRS->get("战队木牌")["基金排行木牌"]["第三行"]);
        $event->setLine(3, $main->PRS->get("战队木牌")["基金排行木牌"]["第四行"]);
        
        $player->sendMessage("§d=====玩家战队系统=====\n§c成功创建战队基金排行木牌!");
      }
      else{
        $player->sendMessage("§d=====玩家战队系统=====\n§c战队排行木牌创建失败, 原因: §6必须也在第二行填写 人数排行 或者 基金排行 才能够创建战队木牌!");
      }
    }
  }
  
}