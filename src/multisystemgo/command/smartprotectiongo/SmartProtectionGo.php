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
namespace multisystemgo\command\smartprotectiongo;

use pocketmine\Player;
use pocketmine\Server;
use pocketmine\plugin\PluginBase;
use pocketmine\item\Item;
use pocketmine\level\Level;
use pocketmine\nbt\tag\StringTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\inventory\PlayerInventory;
use pocketmine\command\Command;
use pocketmine\command\CommandExecutor;
use pocketmine\command\CommandSender;
use pocketmine\level\sound\PopSound;

class SmartProtectionGo{
  public static function onCommand($sender, $cmd, $label, $args, PluginBase $main){
    $senderName = $sender->getName();
    
    if($cmd->getName() == "智保"){
      if(!isset($args[0])){
        if($sender->isOp()){
          $sender->sendMessage("§a=====智能保护系统=====\n§e查看某玩家的信息: §f/智保 查看信息 <玩家名称>\n§e取回我的生存背包: §f/智保 背包\n§e添加箱子信任玩家: §f/智保 添加信任 <玩家名称>\n§e移除箱子信任玩家: §f/智保 移除信任 <玩家名称>\n§e查看箱子信任列表: §f/智保 信任列表\n§e添加世界配置选项: §f/智保 添加世界 <世界名称>\n§e移除世界配置选项: §f/智保 移除世界 <世界名称>\n§e查看世界配置列表: §f/智保 世界列表\n§e查询某玩家的背包: §f/智保 查询背包 <玩家名称>\n§e禁止某个玩家发言: §f/智保 禁言 <玩家名称> <时间/秒>\n§e解除某玩家的禁言: §f/智保 解禁 <玩家名称>\n§e重载智能保护系统: §f/智保 重载");
        }
        else{
          $sender->sendMessage("§a=====智能保护系统=====\n§e取回我的生存背包: §f/智保 背包\n§e添加箱子信任玩家: §f/智保 添加信任 <玩家名称>\n§e移除箱子信任玩家: §f/智保 移除信任 <玩家名称>\n§e查看箱子信任列表: §f/智保 信任列表");
        }
        return true;
      }
      
      switch($args[0]){
        case "查看信息":
          if(!isset($args[1])){
            $sender->sendMessage("§a=====智能保护系统=====\n§e正确用法: §f/智保 查看信息 <玩家名称>");
            return false;
          }
          
          if($main->getServer()->getPlayer($args[1]) !== null){
            $sender->sendMessage("§a=====智能保护系统=====");
            $sender->sendMessage($main->nestedTag($target, $main, $main->SMS->get("功能设置")["信息文本"]));
            return true;
          }
          else{
            $sender->sendMessage("§a=====智能保护系统=====\n§e处理失败, 原因: §f找不到 {$args[1]} 玩家");
            return false;
          }
        break;
      
        case "背包":
          if(!$sender instanceof Player){
            $sender->sendMessage("§a=====智能保护系统=====\n§e请在游戏中执行该指令!");
            return false;
          }
          
          if(!$main->PBD->exists($senderName)){
            $sender->sendMessage("§a=====智能保护系统=====\n§e无法找到你的背包数据!");
            return false;
          }
            
          if($sender->getGamemode() == 0){
            $ids = $main->PBD->get($senderName)["id"];
            $damages = $main->PBD->get($senderName)["damage"];
            $counts = $main->PBD->get($senderName)["count"];
            $nbts = $main->PBD->get($senderName)["nbt"];
            
            foreach($sender->getInventory()->getContents() as $item){
              if($item->getId() !== 0){//判断格子内的物品是不是空气
                $sender->sendMessage("§a=====智能保护系统=====\n§e你必须清空你的背包才能取回背包!");
                return false;
                break;
              }
            }
            
            for ($i = 0; $i < count($ids); $i++){
              $item = Item::get($ids[$i], $damages[$i], $counts[$i], base64_decode($nbts[$i]));
              $sender->getInventory()->addItem($item);
            }
            
            $main->PBD->remove($senderName);
            $main->PBD->save();
            $sender->getLevel()->addSound(new PopSound($sender));
            $sender->sendMessage("§a=====智能保护系统=====\n§e你已成功取回背包!");
            return true;
          }
          else{
            $sender->sendMessage("§a=====智能保护系统=====\n§e你只能在生存模式下取回背包!");
            return false;
          }
        break;
        
        case "添加信任":
          if(!isset($args[1])){
            $sender->sendMessage("§a=====智能保护系统=====\n§e正确用法: §f/智保 添加信任 <玩家名称>");
            return false;
          }
          
          if($args[1] == $senderName){
            $sender->sendMessage("§a=====智能保护系统=====\n§e处理失败, 原因: §f你无法将自己添加到自己的个人箱子信任列表中");
            return false;
          }
          else{
            $sender->namedtag->ClickChestToAdd = new StringTag("ClickChestToAdd", $args[1]);
            $sender->sendMessage("§a=====智能保护系统=====\n§e请点击你的个人箱子进行添加信任者!");
            return true;
          }
        break;
        
        case "移除信任":
          if(!isset($args[1])){
            $sender->sendMessage("§a=====智能保护系统=====\n§e正确用法: §f/智保 移除信任 <玩家名称>");
            return false;
          }
          if($args[1] == $senderName){
            $sender->sendMessage("§a=====智能保护系统=====\n§e处理失败, 原因: §f你无法从你自己的个人箱子信任列表中移除自己");
            return false;
          }
          else{
            $sender->namedtag->ClickChestToRemove = new StringTag("ClickChestToRemove", $args[1]);
            $sender->sendMessage("§a=====智能保护系统=====\n§e请点击你的个人箱子进行移除信任者!");
            return true;
          }
        break;
        
        case "信任列表":
          $sender->namedtag->ClickChestToCheck = new StringTag("ClickChestToCheck", "点击箱子查看信任列表");
          $sender->sendMessage("§a=====智能保护系统=====\n§e点击你的个人箱子即可查看该箱子的所有信任者!");
          return true;
        break;
        
        case "添加世界":
          if(!$sender->isOp()){
            $sender->sendMessage("§a=====智能保护系统=====\n§e你没有权限执行该指令!");
            return false;
          }
        
          if(!isset($args[1])){
            $sender->sendMessage("§a=====智能保护系统=====\n§e正确用法: §f/智保 添加世界 <世界名称>");
            return false;
          }
          
          $worldConfig = $main->SPS->get("世界设置");
          if(!array_key_exists($args[1], $worldConfig)){
            $worldConfig[$args[1]] = [
              "白名单" => [
                "玩家名称",
                "以此类推",
              ],
              "禁用指令列表" => [
                "/指令",
                "以此类推",
              ],
              "世界游戏模式" => 0,
              "关闭玩家飞行" => "开",
              "世界人数上限" => 99,
            ];
            $main->SPS->set("世界设置", $worldConfig);
            $main->SPS->save();
            $sender->sendMessage("§a=====智能保护系统=====\n§e成功添加 §f{$args[1]} §e世界配置选项!\n§e请到 §f智能保护系统.yml §e文件中的 §f世界设置 §e完成相关配置.");
            return true;
          }
          else{
            $sender->sendMessage("§a=====智能保护系统=====\n§f{$args[1]} §e世界配置选项已存在!");
            return false;
          }
        break;
        
        case "移除世界":
          if(!$sender->isOp()){
            $sender->sendMessage("§a=====智能保护系统=====\n§e你没有权限执行该指令!");
            return false;
          }
        
          if(!isset($args[1])){
            $sender->sendMessage("§a=====智能保护系统=====\n§e正确用法: §f/智保 移除世界 <世界名称>");
            return false;
          }
          
          $worldConfig = $main->SPS->get("世界设置");
          if(array_key_exists($args[1], $worldConfig)){
            unset($worldConfig[$args[1]]);
            $main->SPS->set("世界设置", $worldConfig);
            $main->SPS->save();
            $sender->sendMessage("§a=====智能保护系统=====\n§e成功移除 §f{$args[1]} §e世界配置选项!");
            return true;
          }
          else{
            $sender->sendMessage("§a=====智能保护系统=====\n§f{$args[1]} §e世界配置选项不存在!");
            return false;
          }
        break;
        
        case "世界列表":
          if(!$sender->isOp()){
            $sender->sendMessage("§a=====智能保护系统=====\n§e你没有权限执行该指令!");
            return false;
          }
        
          $sender->sendMessage("§a=====智能保护系统=====\n§e世界配置选项列表:");
          foreach($main->SPS->get("世界设置") as $levelName => $info){
            $sender->sendMessage("§e - §f{$levelName} §e世界已添加世界配置选项");
          }
          return true;
        break;
        
        case "重载":
          if(!$sender->isOp()){
            $sender->sendMessage("§a=====智能保护系统=====\n§e你没有权限执行该指令!");
            return false;
          }
          
          $main->SPS->reload();
          $sender->sendMessage("§a=====智能保护系统=====\n§e智能保护系统重载完成!");
          return true;
        break;
        
        case "查询背包":
          if(!$sender->isOp()){
            $sender->sendMessage("§a=====智能保护系统=====\n§e你没有权限执行该指令!");
            return false;
          }
          
          if(!isset($args[1])){
            $sender->sendMessage("§a=====智能保护系统=====\n§e正确用法: §f/智保 查询背包 <玩家名称>");
            return false;
          }
          
          if($main->getServer()->getPlayer($args[1]) == null){
            $sender->sendMessage("§a=====智能保护系统=====\n§e无法找到 §f{$args[1]} §e玩家!");
            return false;
          }
          
          if($sender->getGamemode() == 0){
            foreach($sender->getInventory()->getContents() as $item){
              if($item->getId() !== 0){//判断格子内的物品是不是空气
                $sender->sendMessage("§a=====智能保护系统=====\n§e你必须清空你的背包才能查询背包!");
                return false;
                break;
              }
            }
            
            foreach($main->getServer()->getPlayer($args[1])->getInventory()->getContents() as $item){
              if($item->getId() !== 0){//判断格内的物品是否为空气
                $id = $item->getId();
                $damage = $item->getDamage();
                $count = $item->getCount();
                $nbt = $item->getCompoundTag();
                $sender->getInventory()->addItem(Item::get($id, $damage, $count, $nbt));
              }
              continue;
            }
            
            $sender->sendMessage("§a=====智能保护系统=====\n§e查询完成, 已将玩家 §f{$args[1]} §e的背包复制到你的背包内.");
            return true;
          }
          else{
            $sender->sendMessage("§a=====智能保护系统=====\n§e你只能在生存模式下查询背包!");
            return false;
          }
        break;
        
        case "禁言":
          if(!$sender->isOp()){
            $sender->sendMessage("§a=====智能保护系统=====\n§e你没有权限执行该指令!");
            return false;
          }
          
          if(!isset($args[1]) OR !isset($args[2])){
            $sender->sendMessage("§a=====智能保护系统=====\n§e正确用法: §f/智保 禁言 <玩家名称> <时间/秒>");
            return false;
          }
          
          if(!is_numeric($args[2])){
            $sender->sendMessage("§d=====玩家战队系统=====\n§c请使用数字填写指令中的时间!");
            return false;
          }
          
          if($main->getServer()->getPlayer($args[1]) !== null){
            $player = $main->getServer()->getPlayer($args[1]);
            $player->namedtag->BanSendChatS = new IntTag("BanSendChatS", time() + intval($args[2]));
            $player->sendMessage("§a=====智能保护系统=====\n§e你已被管理员 §f{$senderName} §e禁言 §f{$args[2]} §e秒!");
            $sender->sendMessage("§a=====智能保护系统=====\n§e玩家 §f{$args[1]} §e已被你禁言 §f{$args[2]} §e秒!");
            return true;
          }
          else{
            $sender->sendMessage("§a=====智能保护系统=====\n§e无法找到 §f{$args[1]} §e玩家!");
            return false;
          }
        break;
        
        case "解禁":
          if(!$sender->isOp()){
            $sender->sendMessage("§a=====智能保护系统=====\n§e你没有权限执行该指令!");
            return false;
          }
          
          if(isset($args[1])){
            $sender->sendMessage("§a=====智能保护系统=====\n§e正确用法: §f/智保 解禁 <玩家名称>");
            return false;
          }
          if($main->getServer()->getPlayer($args[1]) !== null){
            $player = $main->getServer()->getPlayer($args[1]);
            unset($player->namedtag->BanSendChatS);
            $player->sendMessage("§a=====智能保护系统=====\n§e你已被管理员 §f{$senderName} §e解除禁言!");
            $sender->sendMessage("§a=====智能保护系统=====\n§e玩家 §f{$args[1]} §e已被你解除禁言!");
            return true;
          }
          else{
            $sender->sendMessage("§a=====智能保护系统=====\n§e无法找到 §f{$args[1]} §e玩家!");
            return false;
          }
        break;
        
        default:
          $sender->sendMessage("§a=====智能保护系统=====\n§e未知指令, 请输入 §f/智保 §e查看指令帮助!");
          return false;
        break;
      }
    }
  }
  
}