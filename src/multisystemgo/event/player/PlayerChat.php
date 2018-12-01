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
                      HyperGo!|Camouflageright © 保留所有权利
                           Powered By HyperGo!
                            author HyperLife
*/
namespace multisystemgo\event\player;

use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\nbt\tag\StringTag;
use pocketmine\nbt\tag\IntTag;

class PlayerChat{
  public static function onPlayerChat($event, PluginBase $main){
    $player = $event->getPlayer();
    $playerName = $player->getName();
    $msg = $event->getMessage();
    
    //敏感词屏蔽
    $event->setMessage(str_replace($main->SPS->get("聊天设置")["敏感词汇屏蔽"], "(敏感词汇)", $msg));
    
    //防止刷屏
    if(isset($player->namedtag->ChatTimeS)){
      if(time() < $player->namedtag->ChatTimeS->getValue()){
        $event->setCancelled(true);
        $player->sendMessage("§a=====智能保护系统=====\n§e消息已被取消发送, 原因: §f你说话说得太快了");
      }
      else{
        $player->namedtag->ChatTimeS = new IntTag("ChatTimeS", time() + 2);
      }
    }
    else{
      $player->namedtag->ChatTimeS = new IntTag("ChatTimeS", time());
    }
    
    //刷新头部名称Tag
    if($main->SMS->get("功能设置")["名称美化"] == "开"){
      foreach($main->getServer()->getOnlinePlayers() as $players){
        if(!isset($players->namedtag->CamouflagePlayer)){
          $players->setNameTag($main->nestedTag($players, $main, $main->SMS->get("功能设置")["头部名称"]));
        }
      }
    }
    
    //如果刚刚已经购买头衔
    if(isset($player->namedtag->BuyPrefix) AND !$event->isCancelled()){
      $event->setCancelled(true);
      if(mb_strlen($msg, "utf8") <= $main->FIX->get("自定义头衔")["字数上限"]){
        unset($player->namedtag->BuyPrefix);
        $player->namedtag->WritePrefix = new StringTag("WritePrefix", $msg);
        $player->sendMessage("§3=====玩家头衔系统=====\n§c请到聊天框再一次输入并发送你要的头衔进行确认!");
      }
      else{
        $player->sendMessage("§3=====玩家头衔系统=====\n§c处理失败, 原因: §f头衔文字数量已超过标准限制§c, 标准限制为 §f".$main->FIX->get("自定义头衔")["字数上限"]." §c个字.");
      }
    }
    
    //重复头衔进行确认
    if(isset($player->namedtag->WritePrefix) AND !$event->isCancelled()){
      $event->setCancelled(true);
      if($msg == $player->namedtag->WritePrefix->getValue()){
        $data = $main->PRE->get("玩家头衔");
        $playerData = $data[$playerName];
        if($playerData["正在使用"] == "无"){
          $playerData["正在使用"] = $msg;
        }
        $playerData["全部头衔"][$msg] = $msg;
        $data[$playerName] = $playerData;
        $main->PRE->set("玩家头衔", $data);
        $main->PRE->save();
        
        $player->sendMessage("§3=====玩家头衔系统=====\n§c成功购买头衔: §f{$msg}");
        unset($player->namedtag->WritePrefix);
      }
      else{
        $player->sendMessage("§3=====玩家头衔系统=====\n§c处理失败, 原因: §f两次输入的头衔内容不一致§c, 请到聊天框重新输入并发送你要的头衔");
        unset($player->namedtag->WritePrefix);
        $player->namedtag->BuyPrefix = new StringTag("BuyPrefix", "已购买");
      }
    }
    
    //消息字数检查
    if(mb_strlen($msg,"utf8") > $main->SPS->get("聊天设置")["消息字数上限"]){
      if(!$event->isCancelled()){
        $event->setCancelled(true);
        $player->sendMessage("§a=====智能保护系统=====\n§e消息发送失败, 原因: §f消息字数超出上限§e, 消息字数上限为: §f".$main->SPS->get("聊天设置")["消息字数上限"]);
      }
    }
    else{
      if($main->SMS->get("功能设置")["聊天美化"] == "开" AND !isset($player->namedtag->CamouflagePlayer)){
        $player->setDisplayName($main->nestedTag($player,$main,$main->SMS->get("功能设置")["聊天格式"]));
      }
      if($main->SMS->get("功能设置")["列表美化"] == "开"){
        $player->namedtag->UpdateDisplayName = new StringTag("UpdateDisplayName", "待更新");
      }
    }
    
    //禁言
    if(isset($player->namedtag->BanSendChatS) AND !$event->isCancelled()){
      if($player->namedtag->BanSendChatS->getValue() > time()){
        $time = $player->namedtag->BanSendChatS->getValue() - time();
        $event->setCancelled(true);
        $player->sendMessage("§a=====智能保护系统=====\n§e你已被禁言, 剩余时间: §f {$time} §e秒.");
      }
      else{
        unset($player->namedtag->BanSendChatS);
      }
    }
  }
  
}