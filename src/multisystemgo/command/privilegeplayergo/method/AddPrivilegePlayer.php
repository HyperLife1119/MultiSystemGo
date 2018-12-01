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

use multisystemgo\data\CapeData;
use pocketmine\entity\Skin;

class AddPrivilegePlayer{
    public static function addPrivilegePlayer($sender, $playerName, $day, $title, $config, $main){
    if(!is_numeric($day) OR $day == "0"){
      $sender->sendMessage("§b=====特权玩家系统=====\n§6处理失败, 原因: §e指令中的 <时间/天> 必须填写数字, 且不能为零");
      return false;
    }
     
    if($main->PPT->exists($playerName)){
      $sender->sendMessage("§b=====特权玩家系统=====\n§6处理失败, 原因: §e{$playerName} §6已经是特权玩家");
      return false;
    }
    else{
      $time = time() + intval($day) * 86400;//总的时间戳 = 添加时的时间戳 + 特权时间的时间戳
      
      $PrivilegeList = $config->get("玩家列表");
      if(!in_array($playerName, $PrivilegeList)){
        $PrivilegeList[] = $playerName;
        $config->set("玩家列表", $PrivilegeList);
        $config->save();
      }
      $main->PPT->set($playerName, $time);
      $main->PPT->save();
      
      if($main->getServer()->getPlayer($playerName) !== null){
        $player = $main->getServer()->getPlayer($playerName);
      
        if($main->PPS->get("功能设置")["附加血量"] == "开"){
          $health = $main->PPS->get($title)["附加血条"];
          $player->setMaxHealth($player->getMaxHealth() + 20 * $health);
          $player->setHealth($player->getMaxHealth() + 20 * $health);
        }
        
        if($main->PPS->get($title)["特权披风"] == "开"){
          $capeData = CapeData::$capeData;
          $randomCape = base64_decode($capeData[mt_rand(0, count($capeData) - 1)]);
          $skin = new Skin($player->getSkin()->getSkinId(), $player->getSkin()->getSkinData(), $randomCape, $player->getSkin()->getGeometryName(), $player->getSkin()->getGeometryData());
          $player->setSkin($skin);
          $player->sendSkin(null);
        }
        
        $main->sendLightning($player);
      }
      
      $sender->sendMessage("§b=====特权玩家系统=====\n§6成功添加{$title}玩家: §e{$playerName}§6, 特权时间为: §e{$day}§6天.");
      $main->getServer()->broadcastMessage("§b=====特权玩家系统=====\n§6恭喜玩家 §e{$playerName} §6成为一名新的{$title}玩家!");
    }
  }
  
}