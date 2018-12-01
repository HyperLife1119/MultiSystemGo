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

use pocketmine\Server;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\entity\Entity;
use pocketmine\math\Vector3;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\nbt\NBT;
use pocketmine\nbt\tag\StringTag;
use pocketmine\nbt\tag\IntTag;
use onebone\economyapi\EconomyAPI;

class PlayerInteract{
  public static function onPlayerInteract($event,PluginBase $main){
    $player = $event->getPlayer();
    $playerName = $player->getName();
    $mode = $player->getGamemode();
    $money = EconomyAPI::getInstance()->myMoney($player);
    
    $action = $event->getAction();
    
    $level = $player->getLevel();
    $levelName = $level->getFolderName();
    
    $block = $event->getBlock();
    $item = $event->getItem();
    
    $x = $block->getX();
    $y = $block->getY();
    $z = $block->getZ();
    $xyz = "{$levelName}:{$x}:{$y}:{$z}";
    
    $id = $block->getId();
    $damage = $block->getDamage();
    $ids = $item->getId();
    $damages = $item->getDamage();
    
    $blockData = "{$id}:{$damage}";
    $itemData = "{$ids}:{$damages}";
    
    //战队人数排行木牌
    if($main->RCS->exists($xyz) AND $action == 1){
      $rangersList = [];
      $rankingList = [];
      
      foreach($main->PRD->getAll() as $ranger => $data){
        $rangersList[$ranger] = count($data["成员"]);
      }
      
      $c = 0;
      for($i = 0; $i <= count($rangersList); $i++){
        foreach($rangersList as $rangers => $count){
          if($count == max($rangersList) AND $c < $main->PRS->get("战队木牌")["人数排行木牌"]["排行量"]){
            $c++;
            $rankingList[$c] = $main->rangers($rangers, $main->PRS->get("战队木牌")["人数排行木牌"]["排行榜"]);
            unset($rangersList[$rangers]);
          }
        }
      }
      
      $player->sendMessage("§d=====玩家战队系统=====\n§c玩家战队人数排行榜:");
      foreach($rankingList as $ranking => $content){
        $player->sendMessage(" §c{$ranking}. {$content}");
      }
      unset($rangersList);
      unset($rankingList);
    }
    
    //战队基金排行木牌
    if($main->RMS->exists($xyz) AND $action == 1){
      $rangersList=[];
      $rankingList=[];
      
      foreach($main->PRD->getAll() as $rangers => $data){
        $rangersList[$rangers] = $data["基金"];
      }
      
      $c = 0;
      for ($i = 0; $i <= count($rangersList); $i++){
        foreach($rangersList as $rangers => $count){
          if($count == max($rangersList) AND $c < $main->PRS->get("战队木牌")["基金排行木牌"]["排行量"]){
            $c++;
            $rankingList[$c] = $main->rangers($rangers, $main->PRS->get("战队木牌")["基金排行木牌"]["排行榜"]);
            unset($rangersList[$rangers]);
          }
        }
      }
      
      $player->sendMessage("§d=====玩家战队系统=====\n§c玩家战队基金排行榜:");
      foreach($rankingList as $ranking => $content){
        $player->sendMessage(" §c{$ranking}. {$content}");
      }
      unset($rangersList);
      unset($rankingList);
    }
    
    //玩家头衔商店木牌
    if(in_array($xyz, $main->PRE->get("自定义头衔商店木牌")) AND $action == 1){
      if($money < $main->FIX->get("自定义头衔")["头衔价格"]){
        $player->sendMessage("§3=====玩家头衔系统=====\n§c购买失败, 原因: §f余额不足");
        return;
      }
      
      if(isset($player->namedtag->BuyPrefix)){
        $player->sendMessage("§3=====玩家头衔系统=====\n§c购买失败, 原因: §f你有未定义的头衔, 请到聊天框输入并发送你想要的头衔");
        return;
      }
      
      if(!isset($main->PRE->get("玩家头衔")[$playerName])){
        $data = $main->PRE->get("玩家头衔");
        $data[$playerName] = ["正在使用" => "无", "全部头衔" => []];
        $main->PRE->set("玩家头衔", $data);
        $main->PRE->save();
      }
      $player->namedtag->BuyPrefix = new StringTag("BuyPrefix", "已购买");
      EconomyAPI::getInstance()->reduceMoney($playerName, $main->FIX->get("自定义")["头衔价格"]);
      $player->sendMessage("§3=====玩家头衔系统=====\n§c成功花费 §f".$main->FIX->get("自定义头衔")["头衔价格"]." §c元购买自定义头衔, 接下来, 请到聊天框输入并发送你想要的头衔.");
    }
    if(array_key_exists($xyz, $main->PRE->get("固定义头衔商店木牌")) AND $action == 1){
      $data = $main->PRE->get("固定义头衔商店木牌")[$xyz];
      if($money < $data["头衔价格"]){
        $player->sendMessage("§3=====玩家头衔系统=====\n§c购买失败, 原因: §f余额不足");
        return;
      }
      
      if(!isset($main->PRE->get("玩家头衔")[$playerName])){
        $data = $main->PRE->get("玩家头衔");
        $data[$playerName] = ["正在使用" => "无", "全部头衔" => []];
        $main->PRE->set("玩家头衔", $data);
        $main->PRE->save();
      }
      else{
        $prefixData = $main->PRE->get("玩家头衔");
        $playerData = $prefixData[$playerName];
        $playerData["全部头衔"][$data["头衔内容"]] = $data["头衔内容"];
        if($playerData["正在使用"] == "无"){
          $playerData["正在使用"] = $data["头衔内容"];
        }
        $prefixData[$playerName] = $playerData;
        $main->PRE->set("玩家头衔", $prefixData);
        $main->PRE->save();
      }
      
      EconomyAPI::getInstance()->reduceMoney($playerName, $data["头衔价格"]);
      $player->sendMessage("§3=====玩家头衔系统=====\n§c成功花费 §f".$data["头衔价格"]." §c元购买头衔: §f".$data["头衔内容"]);
    }
    
    //双击战队宣传木牌加入战队
    if($main->RDS->exists($xyz) AND !$main->RPD->exists($playerName) AND $action == 1){
      $rangers = $main->RDS->get($xyz);
      if(!isset($player->namedtag->ClickSignToJoinRangers)){
        $player->namedtag->ClickSignToJoinRangers=new IntTag("ClickSignToJoinRangers", time() + 5);
        $player->sendMessage("§d=====玩家战队系统=====\n§c再点击一次木牌即可申请加入 §6{$rangers} §c战队!");
        return;
      }
      
      if($player->namedtag->ClickSignToJoinRangers->getValue() < time()){
        $player->namedtag->ClickSignToJoinRangers=new IntTag("ClickSignToJoinRangers", time() + 5);
        $player->sendMessage("§d=====玩家战队系统=====\n§c再点击一次木牌即可申请加入 §6{$rangers} §c战队!");
        return;
      }
      unset($player->namedtag->ClickSignToJoinRangers);
      
      if($main->PRD->get($rangers)["人数"] == count($main->PRD->get($rangers)["成员"])){
        $player->sendMessage("§d=====玩家战队系统=====\n§c申请失败, 原因: §6该战队人数已达上限");
      }
      else{
        foreach($main->PRD->get($rangers)["管理"] as $key => $name){
          if($main->getServer()->getPlayer($name) !== null){
            $main->getServer()->getPlayer($name)->sendMessage("§d=====玩家战队系统=====\n§c玩家 §6{$playerName} §c申请加入 §6{$rangers} §c战队! 同意加入请输入指令 §6/战队 同意§c, 不同意加入则输入指令 §6/战队 拒绝 §c.");
          }
        }
        
        if($main->getServer()->getPlayer($main->PRD->get($rangers)["队长"]) !== null){
          $main->getServer()->getPlayer($main->PRD->get($rangers)["队长"])->sendMessage("§d=====玩家战队系统=====\n§c玩家 §6{$playerName} §c申请加入 §6{$rangers} §c战队! 同意加入请输入指令 §6/战队 同意§c, 不同意加入则输入指令 §6/战队 拒绝 §c.");
        }
        
        //存储临时数据
        $main->PJR->set($playerName, $rangers);
        $main->PJR->save();
        $player->sendMessage("§d=====玩家战队系统=====\n§c成功申请加入 §6{$rangers} §c战队, 请耐心等待处理!");
      }
    }
    
    //点击方块选择伪装方块
    if(isset($player->namedtag->ClickToCamouflageBlock) AND $action == 1){
      $player->namedtag->CamouflageBlockData = new StringTag("CamouflageBlockData", $blockData);
      $player->sendMessage("§b=====特权玩家系统=====\n§6成功伪装成ID特殊值为 §e{$blockData} §6的方块!\n§6你得小心, 手持物品, 疾跑粒子都可能会暴露你的伪装.输入指令 §e/特权 伪装方块 0 §6进行解除伪装.");
      unset($player->namedtag->ClickToCamouflageBlock);
    }
    
    //点地跳跃
    if(isset($player->namedtag->ClickToJump) AND $action == 1){
      $player->setMotion(new Vector3($player->motionX, $player->motionY + 0.1, $player->motionZ));
    }
    
    //如果是禁用方块
    if(in_array($blockData, $main->SPS->get("创造设置")["禁止使用方块"]) AND !in_array($playerName, $main->SPS->get("创造设置")["白名单"]) AND $mode == 1){
      $event->setCancelled(true);
      $player->sendMessage("§a=====智能保护系统=====\n§e为了不影响游戏的平衡性, 服务器已禁止使用此方块.");
    }
    if(in_array($blockData, $main->SPS->get("生存设置")["禁止使用方块"]) AND !in_array($playerName, $main->SPS->get("生存设置")["白名单"]) AND $mode == 0){
      $event->setCancelled(true);
      $player->sendMessage("§a=====智能保护系统=====\n§e为了不影响游戏的平衡性, 服务器已禁止使用此方块.");
    }
    
    //如果是禁用物品
    if(in_array($itemData,$main->SPS->get("创造设置")["禁止使用物品"]) AND !in_array($playerName, $main->SPS->get("创造设置")["白名单"]) AND $mode == 1){
      $event->setCancelled(true);
      $player->sendMessage("§a=====智能保护系统=====\n§e为了不影响游戏的平衡性, 服务器已禁止放置此物品.");
    }
    if(in_array($itemData,$main->SPS->get("生存设置")["禁止使用物品"]) AND !in_array($playerName, $main->SPS->get("生存设置")["白名单"]) AND $mode == 0){
      $event->setCancelled(true);
      $player->sendMessage("§a=====智能保护系统=====\n§e为了不影响游戏的平衡性, 服务器已禁止放置此物品.");
    }
    
    //点击箱子进行添加信任者/移除信任者/查看信任列表
    if(isset($player->namedtag->ClickChestToAdd) OR isset($player->namedtag->ClickChestToRemove) OR isset($player->namedtag->ClickChestToCheck)){
      if($id !== 54 AND $id !== 130){
        $event->setCancelled(true);
        $player->sendMessage("§a=====智能保护系统=====\n§e请先点击你的个人箱子进行添加信任者/移除信任者/查看信任列表!");
      }
    }
    
    //监测玩家打开箱子
    if(($id == 54 OR $id == 130) AND $action == 1){
      //添加信任玩家
      if(isset($player->namedtag->ClickChestToAdd)){
        $event->setCancelled(true);
      
        if(!$main->PCD->exists($xyz)){
          $player->sendMessage("§a=====智能保护系统=====\n§e处理失败, 原因: §f该箱子并不属于你的个人箱子");
          return;
        }
        
        $host = $main->PCD->get($xyz)["host"];
        
        //如果是箱子主人
        if($host !== $playerName){
          $player->sendMessage("§a=====智能保护系统=====\n§e处理失败, 原因: §f该箱子并不属于你的个人箱子");
          return;
        }
        
        $name = $player->namedtag->ClickChestToAdd->getValue();
        $trust = $main->PCD->get($xyz)["trust"];
        if(in_array($name, $trust)){
          $player->sendMessage("§a=====智能保护系统=====\n§e处理失败, 原因: §f玩家 {$name} 已是该个人箱子的信任者");
        }
        else{
          $trust[$name] = $name;
          $main->PCD->set($xyz,["host" => $playerName, "trust" => $trust]);
          $main->PCD->save();
          $player->sendMessage("§a=====智能保护系统=====\n§e成功将玩家 §f{$name} §e添加为该个人箱子的信任者!");
        }
        unset($player->namedtag->ClickChestToAdd);
      }
      
      //移除信任玩家
      if(isset($player->namedtag->ClickChestToRemove) AND $action == 1){
        $event->setCancelled(true);
        
        if(!$main->PCD->exists($xyz)){
          $player->sendMessage("§a=====智能保护系统=====\n§e处理失败, 原因: §f该箱子并不属于你的个人箱子");
          return;
        }
          
        $host = $main->PCD->get($xyz)["host"];
        
        //如果是箱子主人
        if($host !== $playerName){
          $player->sendMessage("§a=====智能保护系统=====\n§e处理失败, 原因: §f该箱子并不属于你的个人箱子");
          return;
        }
        
        $name = $player->namedtag->ClickChestToRemove->getValue();
        $trust = $main->PCD->get($xyz)["trust"];
        if(in_array($name, $trust)){
          unset($trust[$name]);
          $main->PCD->set($xyz,["host" => $playerName, "trust" => $trust]);
          $main->PCD->save();
          $player->sendMessage("§a=====智能保护系统=====\n§e成功将玩家 §f{$name} §e从该个人箱子的信任列表中移除!");
        }
        else{
          $player->sendMessage("§a=====智能保护系统=====\n§e移除失败, 原因: §f玩家 {$name} 不是该个人箱子的信任者");
        }
        unset($player->namedtag->ClickChestToRemove);
      }
      
      //查看信任列表
      if(isset($player->namedtag->ClickChestToCheck) AND $action == 1){
        if($main->PCD->exists($xyz)){
          $event->setCancelled(true);
          $player->sendMessage("§a=====智能保护系统=====\n§e箱子主人: §f".$main->PCD->get($xyz)["host"]."\n§e信任列表: §f".implode(", ", $main->PCD->get($xyz)["trust"]));
        }
        else{
          $player->sendMessage("§a=====智能保护系统=====\n§e查看失败, 原因: §f该箱子还未拥有主人");
        }
        unset($player->namedtag->ClickChestToCheck);
      }
      
      if($mode == 1){
        if(!$player->isOp()){
          $event->setCancelled(true);
          $sender->sendMessage("§a=====智能保护系统=====\n§e创造模式下, 非管理员玩家无法开启箱子!");
        }
      }
      else{
        if($main->SPS->get("功能设置")["个人箱子"] == "开" AND
           $main->PCD->exists($xyz) AND
           $playerName !== $main->PCD->get($xyz)["host"] AND 
           !in_array($playerName, $main->PCD->get($xyz)["trust"])
          ){
          if($player->isOp()){
            $player->sendMessage("§a=====智能保护系统=====\n§e你使用了管理员权限强制打开了他人的个人箱子!");
          }
          else{
            $event->setCancelled(true);
            $player->sendMessage("§a=====智能保护系统=====\n§e你不是这个箱子的主人/信任者!");
          }
        }
      }
    }
    
    //刷新列表名称
    if($main->SMS->get("功能设置")["列表美化"] == "开" AND isset($player->namedtag->UpdateDisplayName)){
      foreach($main->getServer()->getOnlinePlayers() as $players){
        $players->setDisplayName($main->nestedTag($players, $main, $main->SMS->get("功能设置")["列表格式"]));
      }
      unset($player->namedtag->UpdateDisplayName);
    }
  }
  
}