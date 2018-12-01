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
use pocketmine\Server;
use pocketmine\plugin\PluginBase;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\nbt\tag\IntTag;
use pocketmine\entity\Entity;
use pocketmine\level\Level;
use pocketmine\level\sound\EndermanTeleportSound;
use multisystemgo\command\privilegeplayergo\method\RemovePrivilegePlayer;
use multisystemgo\event\player\method\PrivilegeMode;
use multisystemgo\event\player\method\RemoveNamedTag;
use multisystemgo\event\player\method\RemoveCamouflageEntity;
use multisystemgo\event\player\method\RemoveCamouflageBlock;
use onebone\economyapi\EconomyAPI;

class PlayerJoin{
  public static function onPlayerJoin($event, PluginBase $main){
    $player = $event->getPlayer();
    $playerName = $player->getName();
    $mode = $player->getGamemode();
    
    $level = $player->getLevel();
    
    $a = $main->A->get("玩家列表");
    $b = $main->B->get("玩家列表");
    $c = $main->C->get("玩家列表");

    //移除发包生物
    RemoveCamouflageEntity::removeCamouflageEntity($player, $main);
    
    //移除伪装方块
    RemoveCamouflageBlock::removeCamouflageBlock($player, $level, $main);
    
    //播放传送音效
    $level->addSound(new EndermanTeleportSound($player));
    
    //取消隐身
    $player->setDataFlag(Entity::DATA_FLAGS, Entity::DATA_FLAG_INVISIBLE, false);
    
    if($main->SMS->get("功能设置")["名称美化"] == "开"){
      foreach($main->getServer()->getOnlinePlayers() as $players){
        $players->setNameTag($main->nestedTag($players, $main, $main->SMS->get("功能设置")["头部名称"]));
      }
    }
    
    //刷新列表名称
    if($main->SMS->get("功能设置")["列表美化"] == "开"){
      foreach($main->getServer()->getOnlinePlayers() as $players){
        $players->setDisplayName($main->nestedTag($players, $main, $main->SMS->get("功能设置")["列表格式"]));
      }
    }
    
    //登录时间记录
    $player->namedtag->JoinHour = new IntTag("JoinHour", 0);
    $player->namedtag->JoinMinute = new IntTag("JoinMinute", time());
    
    //活跃时间记录
    $main->PAT->set($playerName, time());
    $main->PAT->save();
    
    //设置预定的游戏模式
    if(in_array($main->SPS->get("功能设置")["进服模式"], [0, 1, 2, 3])){
      $player->setGamemode($main->SPS->get("功能设置")["进服模式"]);
    }
    
    //玩家战队消息提醒
    if($main->RCD->exists($playerName)){//如果玩家已创建过战队
      //玩家申请加入战队提示
      if(in_array($main->RCD->get($playerName), $main->PJR->getAll())){//如果临时数据中有申请加入自己战队的
        $player->sendMessage("§d=====玩家战队系统=====\n§c玩家战队消息(1): §6有玩家申请加入战队!");
        $players = [];
        foreach($main->PJR->getAll() as $name=>$rangers){
          if($rangers == $main->RCD->get($playerName)){
            $players[] = $name;
          }
        }
        $player->sendMessage("§c玩家申请列表(".count($players)."):\n§6".implode(", ",$players)."\n§c请输入指令§6 /战队 同意 §c来批准加入, 或输入指令 §6/战队 拒绝 §c来拒绝加入.");
        unset($players);
      }
      
      //提示队长有人申请战队联盟
      if(isset($player->namedtag->RangersUnionS)){
        $player->sendMessage("§d=====玩家战队系统=====\n§c玩家战队消息(1): §6{$player->namedtag->RangersUnionS->getValue()} 战队申请和你的战队进行战队联盟!\n§c请输入指令§6 /战队 接受 §c来同意战队联盟, 或输入指令 §6/战队 推辞 §c来推辞战队联盟.");
      }
    }
    
    //提示求婚
    if($main->PLD->exists($playerName)){
      $player->sendMessage("§c=====玩家结婚系统=====\n§e玩家 §f".$main->PLD->get($playerName)." §e正在向你求婚, 如果你愿意, 请输入指令: §f/结婚 同意§e, 如果不愿意, 请输入指令: §f/结婚 拒绝 <拒绝理由>");
    }
    
    //提示离婚
    if($main->PDD->exists($playerName)){
      $player->sendMessage("§c=====玩家结婚系统=====\n§e很遗憾, 你的配偶 §f".$main->PMD->get($playerName)." §e向你提出了离婚申请, 离婚理由: §f".$main->PDD->get($playerName)."§e, 如果你愿意, 请输入指令: §f/结婚 同意离婚§e, 如果不愿意, 请输入指令: §f/结婚 拒绝离婚 <拒绝理由>");
    }
    
    //玩家加入服务器执行/gc
    if($main->SPS->get("功能设置")["智能优化"] == "开"){
      $main->getServer()->dispatchCommand(new \pocketmine\command\ConsoleCommandSender,"gc");
    }
    
    //处理特权玩家
    if($main->PPT->exists($playerName)){
      if($main->PPT->get($playerName) >= time()){
        if($main->PPS->get("功能设置")["进服闪电"] == "开"){
          $main->sendLightning($player);
        }
        
        if($main->PPS->get("功能设置")["进服提示"] == "开"){
          $main->getServer()->broadcastMessage("§b=====特权玩家系统=====\n".$main->nestedTag($player,$main,$main->PPS->get("功能设置")["进服信息"])."§f");
        }
        $player->getPlayer()->sendMessage("§6亲爱的特权玩家 §e{$playerName} §6, 欢迎回到服务器!");
      }
      else{//特权玩家过期
        $player->getPlayer()->sendMessage("§b=====特权玩家系统=====\n§6你的特权玩家身份已过期!");
        
        //如果是顶级特权玩家
        if(in_array($playerName, $a)){
          RemovePrivilegePlayer::removePrivilegePlayer($playerName, $main->A);
        }
        //如果是高级特权玩家
        if(in_array($playerName,$b)){
          RemovePrivilegePlayer::removePrivilegePlayer($playerName, $main->B);
        }
        //如果是普通特权玩家
        if(in_array($playerName,$c)){
          RemovePrivilegePlayer::removePrivilegePlayer($playerName, $main->C);
        }
      }
      
      //如果是顶级特权玩家
      if(in_array($playerName, $a)){
        PrivilegeMode::privilegeMode($player, "顶级特权", $main);
      }
      //如果是高级特权玩家
      if(in_array($playerName, $b)){ 
        PrivilegeMode::privilegeMode($player, "高级特权", $main);
      }
      //如果是普通特权玩家
      if(in_array($playerName, $c)){
        PrivilegeMode::privilegeMode($player, "普通特权", $main);
      }
    }
    //如果不是特权玩家
    else{
      if($main->PPS->get("功能设置")["附加血量"] == "开"){
        $health = $main->PPS->get("普通玩家")["附加血条"];
        $player->setMaxHealth($player->getMaxHealth() + 20 * $health);
        $player->setHealth($player->getMaxHealth() + 20 * $health);
      }
    }
    
    //清理namedtag
    RemoveNamedTag::removeNamedTag($player);
  }
  
}