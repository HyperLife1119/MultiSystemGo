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
namespace multisystemgo\command\privilegeplayergo;

use pocketmine\Player;
use pocketmine\Server;
use pocketmine\plugin\PluginBase;
use pocketmine\nbt\tag\StringTag;
use pocketmine\command\Command;
use pocketmine\command\CommandExecutor;
use pocketmine\command\CommandSender;
use pocketmine\level\sound\PopSound;
use multisystemgo\command\privilegeplayergo\method\AddPrivilegePlayer;
use multisystemgo\command\privilegeplayergo\method\AddPrivilegeWorld;
use multisystemgo\command\privilegeplayergo\method\RemovePrivilegePlayer;
use multisystemgo\command\privilegeplayergo\method\RemovePrivilegeWorld;
use multisystemgo\command\privilegeplayergo\method\CamouflageBlock;
use multisystemgo\command\privilegeplayergo\method\CamouflagePlayer;
use multisystemgo\command\privilegeplayergo\method\CamouflageEntity;
use multisystemgo\command\privilegeplayergo\method\ClickToJump;
use multisystemgo\command\privilegeplayergo\method\LightningStroke;
use multisystemgo\command\privilegeplayergo\method\SetGameMode;
use multisystemgo\command\privilegeplayergo\method\SetFlightMode;
use multisystemgo\command\privilegeplayergo\method\Teleport;
use multisystemgo\command\privilegeplayergo\method\SetLevelTime;
use multisystemgo\command\privilegeplayergo\method\SetPlayerScale;
use multisystemgo\command\privilegeplayergo\method\SendFirework;
use multisystemgo\command\privilegeplayergo\method\RidePlayer;
use multisystemgo\command\privilegeplayergo\method\InvertedBody;

class PrivilegePlayerGo{
  public static function onCommand($sender, $cmd, $label, $args, PluginBase $main){
    $senderName = $sender->getName();
    
    if($cmd->getName() == "特权"){
      if(!isset($args[0])){
        $sender->sendMessage("§b=====特权玩家系统=====\n§6特权系统指令帮助: §e/特权 帮助 <页码>");
        return false;
      }
      
      $a = $main->A->get("玩家列表");
      $b = $main->B->get("玩家列表");
      $c = $main->C->get("玩家列表");
      
      switch($args[0]){
        case "顶级特权":
          if(!$sender->isOp()){
            $sender->sendMessage("§b=====特权玩家系统=====\n§6你没有权限执行该指令!");
            return false;
          }
          
          if(!isset($args[1]) OR !isset($args[2])){
            $sender->sendMessage("§b=====特权玩家系统=====\n§6正确用法: §e/特权 顶级特权 <玩家名称> <时间/天>");
            return false;
          }
          AddPrivilegePlayer::addPrivilegePlayer($sender, $args[1], $args[2], "顶级特权", $main->A, $main);
        break;
        
        case "高级特权":
          if(!$sender->isOp()){
            $sender->sendMessage("§b=====特权玩家系统=====\n§6你没有权限执行该指令!");
            return false;
          }
        
          if(!isset($args[1]) OR !isset($args[2])){
            $sender->sendMessage("§b=====特权玩家系统=====\n§6正确用法: §e/特权 高级特权 <玩家名称> <时间/天>");
            return false;
          }
          AddPrivilegePlayer::addPrivilegePlayer($sender, $args[1], $args[2], "高级特权", $main->B, $main);
        break;
        
        case "普通特权":
          if(!$sender->isOp()){
            $sender->sendMessage("§b=====特权玩家系统=====\n§6你没有权限执行该指令!");
            return false;
          }
        
          if(!isset($args[1]) OR !isset($args[2])){
            $sender->sendMessage("§b=====特权玩家系统=====\n§6正确用法: §e/特权 普通特权 <玩家名称> <时间/天>");
            return false;
          }
          AddPrivilegePlayer::addPrivilegePlayer($sender, $args[1], $args[2], "普通特权", $main->C, $main);
        break;
        
        case "移除特权":
          if(!$sender->isOp()){
            $sender->sendMessage("§b=====特权玩家系统=====\n§6你没有权限执行该指令!");
            return false;
          }
        
          if(!isset($args[1])){
            $sender->sendMessage("§b=====特权玩家系统=====\n§6正确用法: §e/特权 移除特权 <玩家名称>");
            return false;
          }
          
          if($main->PPT->exists($args[1])){
            
            if(in_array($args[1], $a)){
              RemovePrivilegePlayer::removePrivilegePlayer($args[1], $main->A, $main);
            }
            if(in_array($args[1], $b)){
              RemovePrivilegePlayer::removePrivilegePlayer($args[1], $main->B, $main);
            }
            if(in_array($args[1], $c)){
              RemovePrivilegePlayer::removePrivilegePlayer($args[1], $main->C, $main);
            }
            
            $sender->sendMessage("§b=====特权玩家系统=====\n§6成功移除特权玩家: §e{$args[1]}");
            return true;
          }
          else{
            $sender->sendMessage("§b=====特权玩家系统=====\n§6处理失败, 原因: §e玩家 {$args[1]} 还不是特权玩家");
            return false;
          }
        break;
        
        case "增加时间":
          if(!$sender->isOp()){
            $sender->sendMessage("§b=====特权玩家系统=====\n§6你没有权限执行该指令!");
            return false;
          }
        
          if(!isset($args[1]) OR !isset($args[2])){
            $sender->sendMessage("§b=====特权玩家系统=====\n§6正确用法: §e/特权 增加时间 <特权玩家名称> <天数>");
            return false;
          }
                
          if(!$main->PPT->exists($args[1])){
            $sender->sendMessage("§b=====特权玩家系统=====\n§6处理失败, 原因: §e玩家 {$args[1]} 不是特权玩家");
            return false;
          }
          
          if(is_numeric($args[2])){
            $time = $main->PPT->get($args[1]) + intval($args[2]) * 86400;
            
            $main->PPT->set($args[1], $time);
            $main->PPT->save();
            
            $sender->sendMessage("§b=====特权玩家系统=====\n§6处理成功, 特权玩家 §e{$args[1]} §6的特权时间还剩 §e".ceil(($time - time()) / 86400)." §6天.");
            return true;
          }
          else{
            $sender->sendMessage("§b=====特权玩家系统=====\n§6处理失败, 原因: §e指令中的 <天数> 必须填写数字");
            return false;
          }
        break;
        
        case "减少时间":
          if(!$sender->isOp()){
            $sender->sendMessage("§b=====特权玩家系统=====\n§6你没有权限执行该指令!");
            return false;
          }
        
          if(!isset($args[1]) OR !isset($args[2])){
            $sender->sendMessage("§b=====特权玩家系统=====\n§6正确用法: §e/特权 减少时间 <特权玩家名称> <天数>");
            return false;
          }
        
          if(!$main->PPT->exists($args[1])){
            $sender->sendMessage("§b=====特权玩家系统=====\n§6处理失败, 原因: §e玩家 {$args[1]} 不是特权玩家");
            return false;
          }
          
          if(!is_numeric($args[2])){
            $sender->sendMessage("§b=====特权玩家系统=====\n§6处理失败, 原因: §e指令中的 <天数> 必须填写数字");
            return false;
          }
          
          $delTime = intval($args[2]) * 86400;
          $time = $main->PPT->get($args[1]) - $delTime;
          
          if($main->PPT->get($args[1]) - time() > $delTime){
            $main->PPT->set($args[1], $time);
            $main->PPT->save();
            
            $sender->sendMessage("§b=====特权玩家系统=====\n§6处理成功, 特权玩家 §e{$args[1]} §6的特权时间还剩 §e".ceil(($time - time()) / 86400)." §6天.");
            return true;
          }
          else{
            $sender->sendMessage("§b=====特权玩家系统=====\n§6处理失败, 原因: §e减少的时间小于原有的时间");
            return false;
          }
        break;
        
        case "添加世界":
          if(!$sender->isOp()){
            $sender->sendMessage("§b=====特权玩家系统=====\n§6你没有权限执行该指令!");
            return false;
          }
        
          if(!isset($args[1]) OR !isset($args[2])){
            $sender->sendMessage("§b=====特权玩家系统=====\n§6添加顶级特权世界: §e/特权 添加世界 顶级 <世界名称>\n§6添加高级特权世界: §e/特权 添加世界 高级 <世界名称>\n§6添加普通特权世界: §e/特权 添加世界 普通 <世界名称>");
            return false;
          }
          
          if($args[1] == "顶级"){
            $PrivilegeWorldList = $main->PPW->get("顶级特权世界");
            if(!in_array($args[2], $PrivilegeWorldList) AND !in_array($args[2], $main->PPW->get("高级特权世界")) AND !in_array($args[2], $main->PPW->get("普通特权世界"))){
              AddPrivilegeWorld::addPrivilegeWorld($PrivilegeWorldList, $args[2], "顶级特权", $main);
              $sender->sendMessage("§b=====特权玩家系统=====\n§6成功将 §e{$args[2]} §6世界添加到顶级特权世界中.");
              return true;
            }
            else{
              $sender->sendMessage("§b=====特权玩家系统=====\n§6处理失败, 原因: §e {$args[2]} 世界已存在于特权世界列表中\n§e(注意: 每个世界只允许成为§c一种§e特权世界)");
              return false;
            }
          }
          elseif($args[1] == "高级"){
            $PrivilegeWorldList = $main->PPW->get("高级特权世界");
            if(!in_array($args[2], $PrivilegeWorldList) AND !in_array($args[2], $main->PPW->get("顶级特权世界")) AND !in_array($args[2], $main->PPW->get("普通特权世界"))){
              AddPrivilegeWorld::addPrivilegeWorld($PrivilegeWorldList, $args[2], "高级特权", $main);
              $sender->sendMessage("§b=====特权玩家系统=====\n§6成功将 §e{$args[2]} §6世界添加到高级特权世界中.");
              return true;
            }
            else{
              $sender->sendMessage("§b=====特权玩家系统=====\n§6处理失败, 原因: §e {$args[2]} 世界已存在于特权世界列表中\n§e(注意: 每个世界只允许成为§c一种§e特权世界)");
              return false;
            }
          }
          elseif($args[1] == "普通"){
            $PrivilegeWorldList = $main->PPW->get("普通特权世界");
            if(!in_array($args[2], $PrivilegeWorldList) AND !in_array($args[2], $main->PPW->get("顶级特权世界")) AND !in_array($args[2], $main->PPW->get("高级特权世界"))){
              AddPrivilegeWorld::addPrivilegeWorld($PrivilegeWorldList, $args[2], "普通特权", $main);
              $sender->sendMessage("§b=====特权玩家系统=====\n§6成功将 §e{$args[2]} §6世界添加到普通特权世界中.");
              return true;
            }
            else{
              $sender->sendMessage("§b=====特权玩家系统=====\n§6处理失败, 原因: §e {$args[2]} 世界已存在于特权世界列表中\n§e(注意: 每个世界只允许成为§c一种§e特权世界)");
              return false;
            }
          }
          else{
            $sender->sendMessage("§b=====特权玩家系统=====\n§6添加顶级特权世界: §e/特权 添加世界 顶级 <世界名称>\n§6添加高级特权世界: §e/特权 添加世界 高级 <世界名称>\n§6添加普通特权世界: §e/特权 添加世界 普通 <世界名称>");
            return false;
          }
        break;
        
        case "移除世界":
          if(!$sender->isOp()){
            $sender->sendMessage("§b=====特权玩家系统=====\n§6你没有权限执行该指令!");
            return false;
          }
        
          if(!isset($args[1])){
            $sender->sendMessage("§b=====特权玩家系统=====\n§6正确用法: §e/特权 移除世界 <世界名称>");
            return false;
          }
          
          $A = $main->PPW->get("顶级特权世界");
          $B = $main->PPW->get("高级特权世界");
          $C = $main->PPW->get("普通特权世界");
          
          if(in_array($args[1], $A)){
            RemovePrivilegeWorld::removePrivilegeWorld($A, $args[1], "顶级特权", $main);
            $sender->sendMessage("§b=====特权玩家系统=====\n§6成功移除顶级特权世界 §e{$args[1]} §6!");
            return true;
          }
          elseif(in_array($args[1], $B)){
            RemovePrivilegeWorld::removePrivilegeWorld($B, $args[1], "高级特权", $main);
            $sender->sendMessage("§b=====特权玩家系统=====\n§6成功移除高级特权世界 §e{$args[1]} §6!");
            return true;
          }
          elseif(in_array($args[1], $C)){
            RemovePrivilegeWorld::removePrivilegeWorld($C, $args[1], "普通特权", $main);
            $sender->sendMessage("§b=====特权玩家系统=====\n§6成功移除普通特权世界 §e{$args[1]} §6!");
            return true;
          }
          else{
            $sender->sendMessage("§b=====特权玩家系统=====\n§6移除失败, 原因: §e{$args[1]} 世界不存在于任何特权世界中!");
            return false;
          }
        break;
          
        case "世界列表":
          if(!$sender->isOp() OR !$main->PPT->exists($senderName)){
            $sender->sendMessage("§b=====特权玩家系统=====\n§6你没有权限执行该指令!");
            return false;
          }
          $sender->sendMessage("§b=====特权玩家系统=====\n§6顶级特权世界列表: §e".implode(", ", $main->PPW->get("顶级特权世界"))."\n§6高级特权世界列表: §e".implode(", ", $main->PPW->get("高级特权世界"))."\n§6普通特权世界列表: §e".implode(", ", $main->PPW->get("普通特权世界")));
          return true;
        break;
        
        case "列表":
          if(!$sender->isOp() OR !$main->PPT->exists($senderName)){
            $sender->sendMessage("§b=====特权玩家系统=====\n§6你没有权限执行该指令!");
            return false;
          }
          $sender->sendMessage("§b=====特权玩家系统=====\n§6顶级特权玩家列表: §e".implode(", ", $main->A->get("玩家列表"))."\n§6高级特权玩家列表: §e".implode(", ", $main->B->get("玩家列表"))."\n§6普通特权玩家列表: §e".implode(", ", $main->C->get("玩家列表")));
          return true;
        break;
        
        case "重载":
          if(!$sender->isOp()){
            $sender->sendMessage("§b=====特权玩家系统=====\n§6你没有权限执行该指令!");
            return false;
          }
        
          $main->PPS->reload();
          $main->CDK->reload();
          $main->SMS->reload();
          
          $sender->sendMessage("§b=====特权玩家系统=====\n§6特权玩家系统重载完成!");
          return true;
        break;
        
        case "生成":
          if(!$sender->isOp()){
            $sender->sendMessage("§b=====特权玩家系统=====\n§6你没有权限执行该指令!");
            return false;
          }
        
          if(!isset($args[1]) OR !isset($args[2])){
            $sender->sendMessage("§b=====特权玩家系统=====\n§6正确用法: §e/特权 生成 <兑换码条数> <兑换码长度>");
            return false;
          }
          
          if(!is_numeric($args[1]) OR !is_numeric($args[2])){
            $sender->sendMessage("§b=====特权玩家系统=====\n§6处理失败, 原因: §e指令中的 <兑换码条数> 与 <兑换码长度> 必须填写数字");
            return false;
          }
          else{
            $sender->sendMessage("§b=====特权玩家系统=====\n§6正在生成 §e".intval($args[1])." §6条长度为 §e".intval($args[2])." §6的兑换码...");
            $startTime = microtime(true);
            $codeList = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
            $codeArray = str_split($codeList);//将字符串转为数组
            $randomCode = "";
            $count = 0;
            
            for($c = 0; $c < intval($args[1]); $c++){
              while(true){
                for($l = 0; $l < intval($args[2]); $l++){
                  $randomCode = $randomCode.$codeArray[mt_rand(0, count($codeArray) - 1)];
                }
                //如果不存在该随机码
                if(!$main->CDK->exists($randomCode)){
                  $count++;
                  //如果随机码条数少于设定的条数, 大于则停止循环
                  if($count <= intval($args[1])){
                    $main->CDK->set($randomCode, ["将此处改写为指令(无需斜杠)", "say 我的名字是{名称}(这是个示例)"]);
                  }
                  else{
                    break;
                  }
                }
                $randomCode = "";
              }
            }
            $main->CDK->save();
            $sender->sendMessage("§b=====特权玩家系统=====\n§6共生成 §e".intval($args[1])." §6条CDK兑换码, 耗时 §e".round(microtime(true) - $startTime, 3)." §6秒, 请前往目录为 §e/plugins/MultiSystemGo!/CdkData/ §6的 §eCdkData.yml §6配置文件中完成相关配置.");
            return true;
          }
        break;
      
        case "兑换":
          if(!$sender instanceof Player){
            $sender->sendMessage("§b=====特权玩家系统=====\n§6请在游戏中执行该指令!");
            return false;
          }
        
          if(!isset($args[1])){
            $sender->sendMessage("§b=====特权玩家系统=====\n§6正确用法: §e/特权 兑换 <兑换码>");
            return false;
          }
          
          if(!$main->CDK->exists($args[1])){
            $sender->sendMessage("§b=====特权玩家系统=====\n§6兑换失败, 请输入正确的CDK兑换码!");
            return false;
          }
          
          if($sender->getGamemode() == 0){
            if($sender->isOp()){
              foreach($main->CDK->get($args[1]) as $key => $cdk){
                $main->getServer()->dispatchCommand($sender, str_replace("{名称}", "{$senderName}", $cdk));
              }
            }
            else{
              $main->getServer()->addOp($senderName);
              foreach($main->CDK->get($args[1]) as $key => $cdk){
                $main->getServer()->dispatchCommand($sender, str_replace("{名称}", "{$senderName}", $cdk));
              }
              $main->getServer()->removeOp($senderName);
            }
            $main->CDK->remove($args[1]);
            $main->CDK->save();
            
            $level->addSound(new PopSound($sender));
            $main->getServer()->broadcastMessage("§b=====特权玩家系统=====\n§6恭喜玩家 §e{$senderName}§6 成功兑换了CDK!");
            return true;
          }
          else{
            $sender->sendMessage("§b=====特权玩家系统=====\n§6请在生存模式下兑换CDK!");
            return false;
          }
        break;
        
        case "帮助":
          if(!isset($args[1])){
            $sender->sendMessage("§b=====特权玩家系统=====\n§6正确用法: §e/特权 帮助 <页码>");
            return false;
          }
          
          if($sender->isOp()){
            if($args[1] == "1"){
              $sender->sendMessage("§b=====特权玩家系统=====\n§6特权系统指令帮助: §e/特权 帮助 <页码>\n§6添加一名顶级特权: §e/特权 顶级特权 <玩家名称> <时间/天>\n§6添加一名高级特权: §e/特权 高级特权 <玩家名称> <天数>\n§6添加一名普通特权: §e/特权 普通特权 <玩家名称> <天数>\n§6移除一名特权玩家: §e/特权 移除特权 <玩家名称>\n§6增加特权玩家时限: §e/特权 添加时间 <玩家名称> <天数>\n§6减少特权玩家时限: §e/特权 减少时间 <玩家名称> <天数>\n§6重载特权玩家系统: §e/特权 重载\n§6查看特权玩家列表: §e/特权 列表\n§6切换我的游戏模式: §e/特权 修改模式 <模式>");
              return true;
            }
            elseif($args[1] == "2"){
              $sender->sendMessage("§b=====特权玩家系统=====\n§6开启关闭飞行模式: §e/特权 飞行\n§6查看我的个人信息: §e/特权 我的信息\n§6释放特权玩家烟花: §e/特权 烟花\n§6调整我的身体尺寸: §e/特权 尺寸 <数值>\n§6传送到某玩家身边: §e/特权 传送 <玩家名称>\n§6改变当前世界时间: §e/特权 时间 <数值>\n§6远程电击某个玩家: §e/特权 电击 <玩家名称>\n§6伪装成为某个玩家: §e/特权 伪装玩家 <玩家名称>\n§6伪装成为某种实体: §e/特权 伪装实体 <ID>\n§6伪装成为某种方块: §e/特权 伪装方块 <方块ID:特殊值>");
              return true;
            }
            elseif($args[1] == "3"){//8个
              $sender->sendMessage("§b=====特权玩家系统=====\n§6开启关闭点地弹跳: §e/特权 弹跳\n§6骑上某玩家的肩膀: §e/特权 乘骑 <玩家名称>\n§6反转倒置我的身体: §e/特权 倒立\n§6批量创建 CDK 码: §e/特权 生成 <兑换码条数> <兑换码长度>\n§6兑换 CDK 兑换码: §e/特权 兑换 <兑换码>\n§6添加一个特权世界: §e/特权 添加世界\n§6移除一个特权世界: §e/特权 移除世界 <世界名称>\n§6查看所有特权世界: §e/特权 世界列表");
              return true;
            }
            else{
              $sender->sendMessage("§b=====特权玩家系统=====\n§6没有找到此页码, 请输入正确页码, 正确页码有: §e1, 2, 3");
              return false;
            }
          }
          else{
            if($args[1] == "1"){
              $sender->sendMessage("§b=====特权玩家系统=====\n§6特权系统指令帮助: §e/特权 帮助 <页码>\n§6查看特权玩家列表: §e/特权 列表\n§6切换我的游戏模式: §e/特权 修改模式 <模式>\n§6开启关闭飞行模式: §e/特权 飞行\n§6查看我的个人信息: §e/特权 我的信息\n§6释放特权玩家烟花: §e/特权 烟花\n§6调整我的身体尺寸: §e/特权 尺寸 <数值>\n§6传送到某玩家身边: §e/特权 传送 <玩家名称>\n§6改变当前世界时间: §e/特权 时间 <数值>\n§6远程电击某个玩家: §e/特权 电击 <玩家名称>");
              return true;
            }
            elseif($args[1] == "2"){//8个
              $sender->sendMessage("§b=====特权玩家系统=====\n§6伪装成为某个玩家: §e/特权 伪装玩家 <玩家名称>\n§6伪装成为某种实体: §e/特权 伪装实体 <ID>\n§6伪装成为某种方块: §e/特权 伪装方块 <方块ID:特殊值>\n§6开启关闭点地弹跳: §e/特权 弹跳\n§6骑上某玩家的肩膀: §e/特权 乘骑 <玩家名称>\n§6反转倒置我的身体: §e/特权 倒立\n§6兑换 CDK 兑换码: §e/特权 兑换 <兑换码>\n§6查看所有特权世界: §e/特权 世界列表");
              return true;
            }
            else{
              $sender->sendMessage("§b=====特权玩家系统=====\n§6没有找到此页码, 请输入正确页码, 正确页码有: §e".implode(", ",$helpList));
              return false;
            }
          }
        break;
        
        case "我的信息":
          if(!$sender instanceof Player){
            $sender->sendMessage("§b=====特权玩家系统=====\n§6请在游戏中执行该指令!");
            return false;
          }
        
          if(in_array($senderName, $a)){
            $sender->sendMessage("§b=====特权玩家系统=====\n§6特权玩家身份: §e顶级特权\n§6特权时间剩余: §e".ceil(($main->PPT->get($senderName) - time()) / 86400)."天\n§6我的游戏模式: §e".str_replace([0, 1, 2, 3], ["生存模式", "创造模式", "冒险模式", "旁观模式"], $sender->getGamemode())."\n§6我所在的世界: §e{$sender->getLevel()->getFolderName()}\n§6我的位置坐标: §e".intval($sender->getX())." : ".intval($sender->getY())." : ".intval($sender->getZ())."\n§6我的设备型号: §e{$sender->getDeviceModel()}");
            return true;
          }
          elseif(in_array($senderName, $b)){
            $sender->sendMessage("§b=====特权玩家系统=====\n§6特权玩家身份: §e高级特权\n§6特权时间剩余: §e".ceil(($main->PPT->get($senderName) - time()) / 86400)."天\n§6我的游戏模式: §e".str_replace([0, 1, 2, 3], ["生存模式", "创造模式", "冒险模式", "旁观模式"], $sender->getGamemode())."\n§6我所在的世界: §e{$sender->getLevel()->getFolderName()}\n§6我的位置坐标: §e".intval($sender->getX())." : ".intval($sender->getY())." : ".intval($sender->getZ())."\n§6我的设备型号: §e{$sender->getDeviceModel()}");
            return true;
          }
          elseif(in_array($senderName, $c)){
            $sender->sendMessage("§b=====特权玩家系统=====\n§6特权玩家身份: §e高级特权\n§6特权时间剩余: §e".ceil(($main->PPT->get($senderName) - time()) / 86400)."天\n§6我的游戏模式: §e".str_replace([0, 1, 2, 3], ["生存模式", "创造模式", "冒险模式", "旁观模式"], $sender->getGamemode())."\n§6我所在的世界: §e{$sender->getLevel()->getFolderName()}\n§6我的位置坐标: §e".intval($sender->getX())." : ".intval($sender->getY())." : ".intval($sender->getZ())."\n§6我的设备型号: §e{$sender->getDeviceModel()}");
            return true;
          }
          else{
            $sender->sendMessage("§b=====特权玩家系统=====\n§6你还不是特权玩家, 无法执行该指令!");
            return false;
          }
        break;
        
        case "弹跳":
          if(!$sender instanceof Player){
            $sender->sendMessage("§b=====特权玩家系统=====\n§6请在游戏中执行该指令!");
            return false;
          }
        
          if(in_array($senderName, $a)){
            ClickToJump::clickToJump($sender, "顶级特权", $main);
          }
          elseif(in_array($senderName, $b)){
            ClickToJump::clickToJump($sender, "高级特权", $main);
          }
          elseif(in_array($senderName, $c)){
            ClickToJump::clickToJump($sender, "普通特权", $main);
          }
          else{
            $sender->sendMessage("§b=====特权玩家系统=====\n§6你还不是特权玩家, 无法执行该指令!");
            return false;
          }
        break;
        
        case "伪装玩家":
          if(!$sender instanceof Player){
            $sender->sendMessage("§b=====特权玩家系统=====\n§6请在游戏中执行该指令!");
            return false;
          }
          
          if(!isset($args[1])){
            $sender->sendMessage("§b=====特权玩家系统=====\n§6正确用法: §e/特权 伪装玩家 <玩家名称>");
            return false;
          }
          
          if(in_array($senderName, $a)){
            CamouflagePlayer::camouflagePlayer($sender, $args[1], "顶级特权", $main);
          }
          elseif(in_array($senderName, $b)){
            CamouflagePlayer::camouflagePlayer($sender, $args[1], "高级特权", $main);
          }
          elseif(in_array($senderName, $c)){
            CamouflagePlayer::camouflagePlayer($sender, $args[1], "普通特权", $main);
          }
          else{
            $sender->sendMessage("§b=====特权玩家系统=====\n§6你还不是特权玩家, 无法执行该指令!");
            return false;
          }
        break;
        
        case "伪装实体":
          if(!$sender instanceof Player){
            $sender->sendMessage("§b=====特权玩家系统=====\n§6请在游戏中执行该指令!");
            return false;
          }
          
          if(!isset($args[1])){
            $sender->sendMessage("§b=====特权玩家系统=====\n§6正确用法: §e/特权 伪装实体 <ID>");
            return false;
          }
        
          if(in_array($senderName, $a)){
            CamouflageEntity::camouflageEntity($sender, $args[1], "顶级特权", $main);
          }
          elseif(in_array($senderName, $b)){
            CamouflageEntity::camouflageEntity($sender, $args[1], "高级特权", $main);
          }
          elseif(in_array($senderName, $c)){
            CamouflageEntity::camouflageEntity($sender, $args[1], "普通特权", $main);
          }
          else{
            $sender->sendMessage("§b=====特权玩家系统=====\n§6你还不是特权玩家, 无法执行该指令!");
            return false;
          }
        break;
        
        case "伪装方块":
          if(!$sender instanceof Player){
            $sender->sendMessage("§b=====特权玩家系统=====\n§6请在游戏中执行该指令!");
            return false;
          }
        
          if(in_array($senderName, $a)){
            if($main->PPS->get("顶级特权")["伪装方块"] !== "开"){
              $sender->sendMessage("§b=====特权玩家系统=====\n§6你还未拥有该特权权限!");
              return false;
            }
            
            if(isset($args[1])){
              CamouflageBlock::camouflageBlock($sender, $args[1], $main);
            }
            else{
              $sender->sendMessage("§b=====特权玩家系统=====\n§6伪装方块已开启, 点击任意方块即可伪装成该方块!");
              $sender->namedtag->ClickToCamouflageBlock = new StringTag("ClickToCamouflageBlock", "未伪装");
              return true;
            }
          }
          elseif(in_array($senderName, $b)){
            if($main->PPS->get("高级特权")["伪装方块"] !== "开"){
              $sender->sendMessage("§b=====特权玩家系统=====\n§6你还未拥有该特权权限!");
              return false;
            }
            
            if(isset($args[1])){
              CamouflageBlock::camouflageBlock($sender, $args[1], $main);
            }
            else{
              $sender->sendMessage("§b=====特权玩家系统=====\n§6伪装方块已开启, 点击任意方块即可伪装成该方块!");
              $sender->namedtag->ClickToCamouflageBlock = new StringTag("ClickToCamouflageBlock", "未伪装");
              return true;
            }
          }
          elseif(in_array($senderName, $c)){
            if($main->PPS->get("普通特权")["伪装方块"] !== "开"){
              $sender->sendMessage("§b=====特权玩家系统=====\n§6你还未拥有该特权权限!");
              return false;
            }
            
            if(isset($args[1])){
              CamouflageBlock::camouflageBlock($sender, $args[1], $main);
            }
            else{
              $sender->sendMessage("§b=====特权玩家系统=====\n§6伪装方块已开启, 点击任意方块即可伪装成该方块!");
              $sender->namedtag->ClickToCamouflageBlock = new StringTag("ClickToCamouflageBlock", "未伪装");
              return true;
            }
          }
          else{
            $sender->sendMessage("§b=====特权玩家系统=====\n§6你还不是特权玩家, 无法执行该指令!");
            return false;
          }
        break;
        
        case "电击":
          if(!$sender instanceof Player){
            $sender->sendMessage("§b=====特权玩家系统=====\n§6请在游戏中执行该指令!");
            return false;
          }
        
          if(!isset($args[1])){
            $sender->sendMessage("§b=====特权玩家系统=====\n§6正确用法: §e/特权 电击 <玩家名称>");
            return false;
          }
        
          if(in_array($senderName, $a)){
            LightningStroke::lightningStroke($sender, $args[1], "顶级特权", $main);
          }
          elseif(in_array($senderName, $b)){
            LightningStroke::lightningStroke($sender, $args[1], "高级特权", $main);
          }
          elseif(in_array($senderName, $c)){
            LightningStroke::lightningStroke($sender, $args[1], "普通特权", $main);
          }
          else{
            $sender->sendMessage("§b=====特权玩家系统=====\n§6你还不是特权玩家, 无法执行该指令!");
            return false;
          }
        break;
        
        case "修改模式":
          if(!$sender instanceof Player){
            $sender->sendMessage("§b=====特权玩家系统=====\n§6请在游戏中执行该指令!");
            return false;
          }
          
          if(!isset($args[1])){
            $sender->sendMessage("§b=====特权玩家系统=====\n§6正确用法: §e/特权 修改模式 <模式>");
            return false;
          }
          
          if(!is_numeric($args[1])){
            $sender->sendMessage("§b=====特权玩家系统=====\n§6处理失败, 原因: §e指令中的 <模式> 必须填写数字");
            return false;
          }
          
          if(in_array($senderName, $a)){
            SetGameMode::setGameMode($sender, $args[1], "顶级特权", $main);
          }
          elseif(in_array($senderName, $b)){
            SetGameMode::setGameMode($sender, $args[1], "高级特权", $main);
          }
          elseif(in_array($senderName, $c)){
            SetGameMode::setGameMode($sender, $args[1], "普通特权", $main);
          }
          else{
            $sender->sendMessage("§b=====特权玩家系统=====\n§6你还不是特权玩家, 无法执行该指令!");
            return false;
          }
        break;
        
        case "飞行":
          if(!$sender instanceof Player){
            $sender->sendMessage("§b=====特权玩家系统=====\n§6请在游戏中执行该指令!");
            return false;
          }
          
          if(in_array($senderName, $a)){
            SetFlightMode::setFlightMode($sender, "顶级特权", $main);
          }
          elseif(in_array($senderName, $b)){
            SetFlightMode::setFlightMode($sender, "高级特权", $main);
          }
          elseif(in_array($senderName, $c)){
            SetFlightMode::setFlightMode($sender, "普通特权", $main);
          }
          else{
            $sender->sendMessage("§b=====特权玩家系统=====\n§6你还不是特权玩家, 无法执行该指令!");
            return false;
          }
        break;
        
        case "传送":
          if(!$sender instanceof Player){
            $sender->sendMessage("§b=====特权玩家系统=====\n§6请在游戏中执行该指令!");
            return false;
          }
          
          if(!isset($args[1])){
            $sender->sendMessage("§b=====特权玩家系统=====\n§6正确用法: §e/特权 传送 <玩家名称>");
            return false;
          }
          
          if(in_array($senderName, $a)){
            Teleport::teleport($sender, $args[1], "顶级特权", $main);
          }
          elseif(in_array($senderName, $b)){
            Teleport::teleport($sender, $args[1], "顶级特权", $main);
          }
          elseif(in_array($senderName, $c)){
            Teleport::teleport($sender, $args[1], "顶级特权", $main);
          }
          else{
            $sender->sendMessage("§b=====特权玩家系统=====\n§6你还不是特权玩家, 无法执行该指令!");
            return false;
          }
        break;
        
        case "时间":
          if(!$sender instanceof Player){
            $sender->sendMessage("§b=====特权玩家系统=====\n§6请在游戏中执行该指令!");
            return false;
          }
          
          if(!isset($args[1])){
            $sender->sendMessage("§b=====特权玩家系统=====\n§6正确用法: §e/特权 时间 <数值>");
            return false;
          }
          
          if(!is_numeric($args[1])){
            $sender->sendMessage("§b=====特权玩家系统=====\n§6处理失败, 原因: §e指令中的 <数值> 必须填写数字");
            return false;
          }
          
          if(in_array($senderName, $a)){
            SetLevelTime::setLevelTime($sender, $args[1], "顶级特权", $main);
          }
          elseif(in_array($senderName, $b)){
            SetLevelTime::setLevelTime($sender, $args[1], "高级特权", $main);
          }
          elseif(in_array($senderName, $c)){
            SetLevelTime::setLevelTime($sender, $args[1], "普通特权", $main);
          }
          else{
            $sender->sendMessage("§b=====特权玩家系统=====\n§6你还不是特权玩家, 无法执行该指令!");
            return false;
          }
        break;
        
        case "尺寸":
          if(!$sender instanceof Player){
            $sender->sendMessage("§b=====特权玩家系统=====\n§6请在游戏中执行该指令!");
            return false;
          }
          
          if(!isset($args[1])){
            $sender->sendMessage("§b=====特权玩家系统=====\n§6正确用法: §e/特权 尺寸 <数值>");
            return false;
          }
          
          if(!is_numeric($args[1])){
            $sender->sendMessage("§b=====特权玩家系统=====\n§6处理失败, 原因: §e指令中的 <数值> 必须填写数字");
            return false;
          }
          
          if(in_array($senderName, $a)){
            SetPlayerScale::setPlayerScale($sender, $args[1], "顶级特权", $main);
          }
          elseif(in_array($senderName, $b)){
            SetPlayerScale::setPlayerScale($sender, $args[1], "顶级特权", $main);
          }
          elseif(in_array($senderName, $c)){
            SetPlayerScale::setPlayerScale($sender, $args[1], "顶级特权", $main);
          }
          else{
            $sender->sendMessage("§b=====特权玩家系统=====\n§6你还不是特权玩家, 无法执行该指令!");
            return false;
          }
        break;
        
        case "烟花":
          if(!$sender instanceof Player){
            $sender->sendMessage("§b=====特权玩家系统=====\n§6请在游戏中执行该指令!");
            return false;
          }
          
          if(in_array($senderName, $a)){
            SendFirework::sendFirework($sender, "顶级特权", $main);
          }
          elseif(in_array($senderName, $b)){
            SendFirework::sendFirework($sender, "高级特权", $main);
          }
          elseif(in_array($senderName, $c)){
            SendFirework::sendFirework($sender, "普通特权", $main);
          }
          else{
            $sender->sendMessage("§b=====特权玩家系统=====\n§6你还不是特权玩家, 无法执行该指令!");
            return false;
          }
        break;
        
        case "乘骑":
          if(!$sender instanceof Player){
            $sender->sendMessage("§b=====特权玩家系统=====\n§6请在游戏中执行该指令!");
            return false;
          }
          
          if(in_array($senderName, $a)){
            if(isset($args[1])){
              if($main->getServer()->getPlayer($args[1]) == null){
                $sender->sendMessage("§b=====特权玩家系统=====\n§6处理失败, 原因: §e找不到 {$args[1]} 玩家");
                return false;
              }
              
              RidePlayer::ridePlayer($sender, $main->getServer()->getPlayer($args[1]), "顶级特权", $a, $b, $c, $main);
            }
            else{
              RidePlayer::ridePlayer($sender, null, "顶级特权", $a, $b, $c, $main);
            }
          }
          elseif(in_array($senderName, $b)){
            if(isset($args[1])){
              if($main->getServer()->getPlayer($args[1]) == null){
                $sender->sendMessage("§b=====特权玩家系统=====\n§6处理失败, 原因: §e找不到 {$args[1]} 玩家");
                return false;
              }
              
              RidePlayer::ridePlayer($sender, $main->getServer()->getPlayer($args[1]), "顶级特权", $a, $b, $c, $main);
            }
            else{
              RidePlayer::ridePlayer($sender, null, "顶级特权", $a, $b, $c, $main);
            }
          }
          elseif(in_array($senderName, $c)){
            if(isset($args[1])){
              if($main->getServer()->getPlayer($args[1]) == null){
                $sender->sendMessage("§b=====特权玩家系统=====\n§6处理失败, 原因: §e找不到 {$args[1]} 玩家");
                return false;
              }
              
              RidePlayer::ridePlayer($sender, $main->getServer()->getPlayer($args[1]), "顶级特权", $a, $b, $c, $main);
            }
            else{
              RidePlayer::ridePlayer($sender, null, "顶级特权", $a, $b, $c, $main);
            }
          }
          else{
            $sender->sendMessage("§b=====特权玩家系统=====\n§6你还不是特权玩家, 无法执行该指令!");
            return false;
          }
        break;
        
        case "倒立":
          if(!$sender instanceof Player){
            $sender->sendMessage("§b=====特权玩家系统=====\n§6请在游戏中执行该指令!");
            return false;
          }
        
          if(in_array($senderName, $a)){
            InvertedBody::invertedBody($sender, "顶级特权", $main);
          }
          elseif(in_array($senderName, $b)){
            InvertedBody::invertedBody($sender, "高级特权", $main);
          }
          elseif(in_array($senderName, $c)){
            InvertedBody::invertedBody($sender, "普通特权", $main);
          }
          else{
            $sender->sendMessage("§b=====特权玩家系统=====\n§6你还不是特权玩家, 无法执行该指令!");
            return false;
          }
        break;
      
        default:
          $sender->sendMessage("§b=====特权玩家系统=====\n§6未知指令, 请输入 §e/特权 帮助 <页面> §6查看指令帮助!");
          return false;
        break;
      }
    }
  }
  
}