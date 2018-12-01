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
namespace multisystemgo\command\luvgo;

use pocketmine\Player;
use pocketmine\Server;
use pocketmine\plugin\PluginBase;
use pocketmine\math\Vector3;
use pocketmine\level\Level;
use pocketmine\nbt\NBT;
use pocketmine\nbt\tag\DoubleTag;
use pocketmine\command\Command;
use pocketmine\command\CommandExecutor;
use pocketmine\command\CommandSender;
use pocketmine\level\Position;
use pocketmine\level\particle\Particle;
use pocketmine\level\particle\HeartParticle;
use pocketmine\level\sound\Sound;
use pocketmine\level\sound\EndermanTeleportSound;

class LuvGo{
  public static function onCommand($sender, $cmd, $label, $args, PluginBase $main){
    $senderName = $sender->getName();
    
    if($cmd->getName() == "结婚"){
      if(!isset($args[0])){
        if($sender->isOp()){
          $sender->sendMessage("§c=====玩家结婚系统=====\n§e查看所有夫妇列表: §f/结婚 列表\n§e设置婚礼殿堂坐标: §f/结婚 婚礼地点\n§e强制玩家进行结婚: §f/结婚 强制结婚 <玩家名称> <玩家名称>\n§e强制玩家进行离婚: §f/结婚 强制离婚 <玩家名称>\n§e对某玩家进行求婚: §f/结婚 求婚 <玩家名称>\n§e同意追求者的请求: §f/结婚 同意\n§e拒绝追求者的请求: §f/结婚 拒绝 <拒绝理由>\n§e申请解除夫妻关系: §f/结婚 离婚 <离婚理由>\n§e同意解除夫妻关系: §f/结婚 同意离婚\n§e拒绝解除夫妻关系: §f/结婚 拒绝离婚 <拒绝理由>\n§e传送到配偶的身边: §f/结婚 传送\n§e设置我的婚姻之家: §f/结婚 定居\n§e传送回到婚姻之家: §f/结婚 回家\n§e牺牲血量治疗配偶: §f/结婚 牺牲\n§e查看配偶活跃时间: §f/结婚 配偶活跃");
        }
        else{
          $sender->sendMessage("§c=====玩家结婚系统=====\n§e查看所有夫妇列表: §f/结婚 列表\n§e对某玩家进行求婚: §f/结婚 求婚 <玩家名称>\n§e同意追求者的请求: §f/结婚 同意\n§e拒绝追求者的请求: §f/结婚 拒绝 <拒绝理由>\n§e申请解除夫妻关系: §f/结婚 离婚 <离婚理由>\n§e同意解除夫妻关系: §f/结婚 同意离婚\n§e拒绝解除夫妻关系: §f/结婚 拒绝离婚 <拒绝理由>\n§e传送到配偶的身边: §f/结婚 传送\n§e设置我的婚姻之家: §f/结婚 定居\n§e传送回到婚姻之家: §f/结婚 回家\n§e牺牲血量治疗配偶: §f/结婚 牺牲\n§e查看配偶活跃时间: §f/结婚 配偶活跃");
        }
        return true;
      }
      
      switch($args[0]){
      
        case "列表":
          $sender->sendMessage("§c=====玩家结婚系统=====\n§e玩家名称 §f: §e配偶名称");
          foreach($main->PMD->getAll() as $a => $b){
            $sender->sendMessage("§e{$a} §f: §e{$b}");
          }
          return true;
        break;
        
        case "传送":
          if(!$sender instanceof Player){
            $sender->sendMessage("§c=====玩家结婚系统=====\n§e你只能在游戏中执行该指令");
            return false;
          }
           
          if(!$main->PMD->exists($senderName)){
            $sender->sendMessage("§c=====玩家结婚系统=====\n§e请求失败, 原因: §f你还未结婚");
            return false;
          }
          
          if($main->getServer()->getPlayer($main->PMD->get($senderName)) !== null){
            $player = $main->getServer()->getPlayer($main->PMD->get($senderName));
            $sender->teleport($player->getPosition);
            $sender->sendMessage("§c=====玩家结婚系统=====\n§e成功传送到配偶身边!");
            $player->sendMessage("§c=====玩家结婚系统=====\n§e你的配偶已回到你身边.");
            $sender->getLevel()->addSound(new EndermanTeleportSound($sender));
            return true;
          }
          else{
            $sender->sendMessage("§c=====玩家结婚系统=====\n§e传送失败, 原因: §f你的配偶不在线");
            return false;
          }
        break;
        
        case "婚礼地点":
          if(!$sender instanceof Player){
            $sender->sendMessage("§c=====玩家结婚系统=====\n§e你只能在游戏中执行该指令");
            return false;
          }
          if($sender->isOp()){
            $main->PDD->set("!MarryPos", $sender->getX().":".$sender->getY().":".$sender->getZ().":".$sender->getLevel()->getName());
            $main->PDD->save();
            $sender->sendMessage("§c=====玩家结婚系统=====\n§e婚礼殿堂坐标设置成功!");
            return true;
          }
          else{
            $sender->sendMessage("§c=====玩家结婚系统=====\n§e你没有权限使用此指令!");
            return false;
          }
        break;
        
        case "求婚":
          if(!$sender instanceof Player){
            $sender->sendMessage("§c=====玩家结婚系统=====\n§e你只能在游戏中执行该指令");
            return false;
          }
          
          if(!isset($args[1])){
            $sender->sendMessage("§c=====玩家结婚系统=====\n§e正确用法: §f/结婚 求婚 <玩家名称>");
            return false;
          }
          
          if($args[1] == $senderName){
            $sender->sendMessage("§c=====玩家结婚系统=====\n§e请求失败, 原因: §f你无法向自己求婚");
            return false;
          }
          
          if($main->PMD->exists($senderName)){
            $sender->sendMessage("§c=====玩家结婚系统=====\n§e请求失败, 原因: §f你已经是已婚人士");
            return false;
          }
          
          if($main->PMD->exists($args[1])){
            $sender->sendMessage("§c=====玩家结婚系统=====\n§e请求失败, 原因: §f对方已经是已婚人士");
            return false;
          }
          else{
            if($main->getServer()->getPlayer($args[1]) !== null){
              $player = $main->getServer()->getPlayer($args[1]);
              $player->sendMessage("§c=====玩家结婚系统=====\n§e玩家 §f{$senderName} §e正在向你求婚, 如果你愿意, 请输入指令: §f/结婚 同意§e, 如果不愿意, 请输入指令: §f/结婚 拒绝 <拒绝理由>");
            }
            $sender->sendMessage("§c=====玩家结婚系统=====\n§e请求成功, 请耐心等待玩家 §f{$args[1]} §e的回复.");
            //如果之前求过婚, 则删除上次的求婚数据
            if($main->PTD->exists($senderName) AND $main->PLD->exists($main->PTD->get($senderName))){
              $main->PLD->remove($main->PTD->get($senderName));
              $main->PLD->save();
            }
            //存储求婚临时数据
            $main->PTD->set($senderName, $args[1]);
            $main->PTD->save();
            $main->PLD->set($args[1], $senderName);
            $main->PLD->save();
            
            return true;
          }
        break;
        
        case "同意":
          if($main->PLD->exists($senderName)){
            $main->PMD->set($senderName, $main->PLD->get($senderName));
            $main->PMD->set($main->PLD->get($senderName),$senderName);
            $main->PMD->save();
            $sender->sendMessage("§c=====玩家结婚系统=====\n§e处理成功, 你已和玩家 §f".$main->PLD->get($senderName)." §e结为夫妇.");
            
            if($main->getServer()->getPlayer($main->PLD->get($senderName)) !== null){
              $main->getServer()->getPlayer($main->PLD->get($senderName))->sendMessage("§c=====玩家结婚系统=====\n§e恭喜你, 玩家 §f{$senderName} §e已同意你的求婚, 你已和玩家 §f{$senderName} §e结为夫妇.");
            }
            
            $main->getServer()->broadcastMessage("§c=====玩家结婚系统=====\n§e恭喜玩家 §f{$senderName} §e与玩家 §f".$main->PLD->get($senderName)." §e结为夫妇, 让我们一同祝贺他们吧!");
            
            //传送到婚礼殿堂
            if($main->PDD->exists("!MarryPos") AND $main->getServer()->getPlayer($main->PLD->get($senderName)) !== null){
              $pos = explode(":", $main->PDD->get("!MarryPos"));
              $player = $main->getServer()->getPlayer($main->PLD->get($senderName));
              $player->teleport(new Position($pos[0], $pos[1], $pos[2], $main->getServer()->getLevelByName($pos[3])));
              $sender->teleport(new Position($pos[0], $pos[1], $pos[2], $main->getServer()->getLevelByName($pos[3])));
              
              $sender->sendMessage("§c=====玩家结婚系统=====\n§e已将你们传送到: §f婚礼殿堂");
              $player->sendMessage("§c=====玩家结婚系统=====\n§e已将你们传送到: §f婚礼殿堂");
              
              $level->addParticle(new HeartParticle(new Vector3($sender->getX(), $sender->getY()+2, $sender->getZ()), 5));
              $level->addParticle(new HeartParticle(new Vector3($player->getX(), $player->getY()+2, $player->getZ()), 5));
            }
            
            //清除离婚任务
            if($main->PDD->exists($senderName)){
              $main->PDD->remove($senderName);
            }
            if($main->PDD->exists($main->PLD->get($senderName))){
              $main->PDD->remove($main->PLD->get($senderName));
            }
            
            if($main->PTD->exists($main->PLD->get($senderName))){
              $main->PTD->remove($main->PLD->get($senderName));
            }
            if($main->PLD->exists($main->PTD->get($senderName))){
              $main->PLD->remove($main->PTD->get($senderName));
            }
            
            $main->PLD->remove($senderName);
            
            if($main->PTD->exists($senderName)){
              $main->PTD->remove($senderName);
            }
            //如果之前求过婚, 则删除上次的求婚数据
            if($main->PLD->exists($main->PTD->get($senderName))){
              $main->PLD->remove($main->PTD->get($senderName));
            }
            
            $main->PDD->save();
            $main->PTD->save();
            $main->PLD->save();
            
            return true;
          }
          else{
            $sender->sendMessage("§c=====玩家结婚系统=====\n§e处理失败, 原因: §f你还未拥有追求者");
            return false;
          }
        break;
        
        case "拒绝":
          if(!isset($args[1])){
            $sender->sendMessage("§c=====玩家结婚系统=====\n§e正确用法: §f/结婚 拒绝 <拒绝理由>");
            return false;
          }
          
          if($main->PLD->exists($senderName)){
            if($main->getServer()->getPlayer($main->PLD->get($senderName)) !== null){
              $main->getServer()->getPlayer($main->PLD->get($senderName))->sendMessage("§c=====玩家结婚系统=====\n§e很遗憾, 玩家 §f{$senderName} §e拒绝了你的求婚, 拒绝理由: §f{$args[1]}");;
            }
            
            //清除临时数据与求婚数据
            if($main->PTD->exists($main->PLD->get($senderName))){
              $main->PTD->remove($main->PLD->get($senderName));
              $main->PTD->save();
            }
            
            $main->PLD->remove($senderName);
            $main->PLD->save();
            
            $sender->sendMessage("§c=====玩家结婚系统=====\n§e处理成功, 已拒绝追求者的申请!");
            return true;
          }
          else{
            $sender->sendMessage("§c=====玩家结婚系统=====\n§e处理失败, 原因: §f你还未拥有追求者");
            return false;
          }
        break;
        
        case "离婚":
          if(!isset($args[1])){
            $sender->sendMessage("§c=====玩家结婚系统=====\n§e正确用法: §f/结婚 离婚 <离婚理由>");
            return false;
          }
          
          if(!$main->PMD->exists($senderName)){
            $sender->sendMessage("§c=====玩家结婚系统=====\n§e请求失败, 原因: §f你还没有结婚");
            return false;
          }
          
          if($main->getServer()->getPlayer($main->PMD->get($senderName)) == null){
            $main->PDD->set($main->PMD->get($senderName), $args[1]);
            $main->PDD->save();
            $sender->sendMessage("§c=====玩家结婚系统=====\n§e申请成功, 由于对方不在线, 该任务已转存到系统, 请耐心等待对方的回复.\n§e如果有必要, 可向管理员申请强制离婚.");
            return true;
          }
          else{
            $main->PDD->set($main->PMD->get($senderName), $args[1]);
            $main->PDD->save();
            $sender->sendMessage("§c=====玩家结婚系统=====\n§e申请成功, 请耐心等待对方回应.");
            $main->getServer()->getPlayer($main->PMD->get($senderName))->sendMessage("§c=====玩家结婚系统=====\n§e很遗憾, 你的配偶 §f{$senderName} §e向你提出了离婚申请, 离婚理由: §f{$args[1]}, §e如果你愿意, 请输入指令: §f/结婚 同意离婚§e, 如果不愿意, 请输入指令: §f/结婚 拒绝离婚 <拒绝理由>");
            return true;
          }
        break;
        
        case "同意离婚":
          if($main->PDD->exists($senderName)){
            $sender->sendMessage("§c=====玩家结婚系统=====\n§e处理成功, 你已和玩家 §f".$main->PMD->get($senderName)." §e解除夫妻关系.");
            if(isset($sender->namedtag->SacrificeCD)){
              unset($sender->namedtag->SacrificeCD);
            }
            if($main->getServer()->getPlayer($main->PMD->get($senderName)) !== null){
              $player = $main->getServer()->getPlayer($main->PMD->get($senderName));
              $player->sendMessage("§c=====玩家结婚系统=====\n§e玩家 §f{$senderName} §e已同意和你离婚!");
              if(isset($player->namedtag->SacrificeCD)){
                unset($player->namedtag->SacrificeCD);
              }
            }
            if($main->PDD->exists($main->PMD->get($senderName))){
              $main->PDD->remove($main->PMD->get($senderName));
            }
            $main->PDD->remove($senderName);
            $main->PDD->save();
            $main->PMD->remove($main->PMD->get($senderName));
            $main->PMD->remove($senderName);
            $main->PMD->save();
            
            return true;
          }
          else{
            $sender->sendMessage("§c=====玩家结婚系统=====\n§e处理失败, 原因: §f你的配偶尚未向你提出离婚");
            return false;
          }
        break;
        
        case "拒绝离婚":
          if(!isset($args[1])){
            $sender->sendMessage("§c=====玩家结婚系统=====\n§e正确用法: §f/结婚 拒绝离婚 <拒绝理由>");
            return false;
          }
          
          if($main->PDD->exists($senderName)){
            $sender->sendMessage("§c=====玩家结婚系统=====\n§e处理成功, 你已拒绝玩家 §f".$main->PMD->get($senderName)." §e的离婚申请.");
            if($main->getServer()->getPlayer($main->PMD->get($senderName)) !== null){
              $main->getServer()->getPlayer($main->PMD->get($senderName))->sendMessage("§c=====玩家结婚系统=====\n§e玩家 §f{$senderName} §e拒绝和你离婚, 拒绝理由: §f{$args[1]}§e, 如果有必要, 可向管理员申请强制离婚!");
            }
            //清除离婚任务
            if($main->PDD->exists($main->PMD->get($senderName))){
              $main->PDD->remove($main->PMD->get($senderName));
            }
            $main->PDD->remove($senderName);
            $main->PDD->save();
            
            return true;
          }
          else{
            $sender->sendMessage("§c=====玩家结婚系统=====\n§e处理失败, 原因: §f你的配偶尚未向你提出离婚");
            return false;
          }
        break;
        
        case "强制结婚":
          if(!$sender->isOp()){
            $sender->sendMessage("§c=====玩家结婚系统=====\n§e你没有权限执行此指令!");
            return false;
          }
          
          if(!isset($args[1]) OR !isset($args[2])){
            $sender->sendMessage("§c=====玩家结婚系统=====\n§e正确用法: §f/结婚 强制结婚 <玩家名称> <玩家名称>");
            return true;
          }
          
          if(!$main->PMD->exists($args[1])){
            if(!$main->PMD->exists($args[2])){
              $main->PMD->set($args[1], $args[2]);
              $main->PMD->set($args[2], $args[1]);
              $main->PMD->save();
              $sender->sendMessage("§c=====玩家结婚系统=====\n§e处理成功, 玩家 §f{$args[1]} §e已和玩家 §f{$args[2]} §e结为夫妇!");
              $main->getServer()->broadcastMessage("§c=====玩家结婚系统=====\n§e恭喜玩家 §f{$args[1]} §e与玩家 §f{$args[2]} §e被管理员强制结为夫妇, 让我们一同祝贺他们吧!");
              if($main->getServer()->getPlayer($args[1]) !== null){
                $main->getServer()->getPlayer($args[1])->sendMessage("§c=====玩家结婚系统=====\n§e管理员已强制将你和玩家 §f{$args[2]} 结为夫妇!");
              }
              if($main->getServer()->getPlayer($args[2]) !== null){
                $main->getServer()->getPlayer($args[2])->sendMessage("§c=====玩家结婚系统=====\n§e管理员已强制将你和玩家 §f{$args[1]} 结为夫妇!");
              }
              //传送到婚礼殿堂
              if($main->PDD->exists("!MarryPos") AND $main->getServer()->getPlayer($args[1]) !== null AND $main->getServer()->getPlayer($args[2]) !== null){
                $pos = explode(":", $main->PDD->get("!MarryPos"));
                $player1 = $main->getServer()->getPlayer($args[1]);
                $player2 = $main->getServer()->getPlayer($args[2]);
                $player1->teleport(new Position($pos[0], $pos[1], $pos[2], $main->getServer()->getLevelByName($pos[3])));
                $player2->teleport(new Position($pos[0], $pos[1], $pos[2], $main->getServer()->getLevelByName($pos[3])));
                $player1->sendMessage("§c=====玩家结婚系统=====\n§e已将你们传送到: §f婚礼殿堂");
                $player2->sendMessage("§c=====玩家结婚系统=====\n§e已将你们传送到: §f婚礼殿堂");
              }
              //清除离婚任务
              if($main->PDD->exists($args[1])){
                $main->PDD->remove($args[1]);
              }
              if($main->PDD->exists($args[2])){
                $main->PDD->remove($args[2]);
              }
              $main->PDD->save();
              
              //清除求婚任务
              if($main->PLD->exists($args[1])){
                $main->PLD->remove($args[1]);
              }
              if($main->PLD->exists($args[2])){
                $main->PLD->remove($args[2]);
              }
              $main->PLD->save();
              
              //清除临时数据
              if($main->PTD->exists($args[1])){
                $main->PTD->remove($args[1]);
              }
              if($main->PTD->exists($args[2])){
                $main->PTD->remove($args[2]);
              }
              $main->PTD->save();
              
              return true;
            }
            else{
              $sender->sendMessage("§c=====玩家结婚系统=====\n§e处理失败, 原因: §f{$args[2]} 已经是已婚人士");
              return false;
            }
          }
          else{
            $sender->sendMessage("§c=====玩家结婚系统=====\n§e处理失败, 原因: §f{$args[1]} 已经是已婚人士");
            return false;
          }
        break;
        
        case "强制离婚":
          if(!$sender->isOp()){
            $sender->sendMessage("§c=====玩家结婚系统=====\n§e你没有权限执行此指令!");
            return false;
          }
          
          if(!isset($args[1])){
            $sender->sendMessage("§c=====玩家结婚系统=====\n§e正确用法: §f/结婚 强制离婚 <玩家名称>");
            return false;
          }
          
          if($main->PMD->exists($args[1])){
            $sender->sendMessage("§c=====玩家结婚系统=====\n§e处理成功, 玩家 §f{$args[1]} §e已和玩家 §f".$main->PMD->get($args[1])." §e解除夫妻关系.");
            if($main->getServer()->getPlayer($args[1]) !== null){
              $player = $main->getServer()->getPlayer($args[1]);
              $player->sendMessage("§c=====玩家结婚系统=====\n§e你和你的配偶已被管理员强制离婚!");
              if(isset($player->namedtag->SacrificeCD)){
                unset($player->namedtag->SacrificeCD);
              }
            }
            if($main->getServer()->getPlayer($main->PMD->exists($args[1])) !== null){
              $player = $main->getServer()->getPlayer($main->PMD->exists($args[1]));
              $player->sendMessage("§c=====玩家结婚系统=====\n§e你和你的配偶已被管理员强制离婚!");
              if(isset($player->namedtag->SacrificeCD)){
                unset($player->namedtag->SacrificeCD);
              }
            }
            
            //清除离婚任务
            if($main->PDD->exists($args[1])){
              $main->PDD->remove($args[1]);
            }
            if($main->PDD->exists($main->PMD->get($args[1]))){
              $main->PDD->remove($main->PMD->get($args[1]));
            }
            $main->PDD->save();
            
            //清除求婚任务
            if($main->PLD->exists($args[1])){
              $main->PLD->remove($args[1]);
            }
            if($main->PLD->exists($main->PMD->get($args[1]))){
              $main->PLD->remove($main->PMD->get($args[1]));
            }
            $main->PLD->save();
            
            //清除临时数据
            if($main->PTD->exists($args[1])){
              $main->PTD->remove($args[1]);
            }
            if($main->PTD->exists($main->PMD->get($args[1]))){
              $main->PTD->remove($main->PMD->get($args[1]));
            }
            $main->PTD->save();
            
            //清除结婚数据
            $main->PMD->remove($main->PMD->get($args[1]));
            $main->PMD->remove($args[1]);
            $main->PMD->save();
            
            return true;
          }
          else{
            $sender->sendMessage("§c=====玩家结婚系统=====\n§e处理失败, 原因: §f{$args[1]} 还未结婚");
            return false;
          }
        break;
        
        case "定居":
          if($main->PMD->exists($senderName)){
            $main->LHD->set($senderName, intval($sender->getX()).":".intval($sender->getY()).":".intval($sender->getZ()).":".$sender->getLevel()->getName());
            $main->LHD->set($main->PMD->get($senderName), $sender->getX().":".$sender->getY().":".$sender->getZ().":".$sender->getLevel()->getFolderName());
            $main->LHD->save();
            $sender->sendMessage("§c=====玩家结婚系统=====\n§e成功将当前位置设置为你和你配偶的婚姻之家!");
            return true;
          }
          else{
            $sender->sendMessage("§c=====玩家结婚系统=====\n§e处理失败, 原因: §f你还没有结婚");
            return false;
          }
        break;
        
        case "回家":
          if(!$main->PMD->exists($senderName)){
            $sender->sendMessage("§c=====玩家结婚系统=====\n§e处理失败, 原因: §f你还没有结婚");
            return false;
          }
          
          if($main->LHD->exists($senderName)){
            $pos = explode(":", $main->LHD->get($senderName));
            $sender->teleport(new Position(intval($pos[0]), intval($pos[1]), intval($pos[2]), $main->getServer()->getLevelByName($pos[3])));
            $sender->sendMessage("§c=====玩家结婚系统=====\n§e成功回到婚姻之家!");
            return true;
          }
          else{
            $sender->sendMessage("§c=====玩家结婚系统=====\n§e回家失败, 原因: §f你还没有设置婚姻之家");
            return false;
          }
        break;
        
        case "牺牲":
          if(!$main->PMD->exists($senderName)){
             $sender->sendMessage("§c=====玩家结婚系统=====\n§e处理失败, 原因: §f你还没有结婚");
             return false;
          }
          //如果对方不在线
          if($main->getServer()->getPlayer($main->PMD->get($senderName)) == null){
            $sender->sendMessage("§c=====玩家结婚系统=====\n§e处理失败, 原因: 你的配偶不在线");
            return false;
          }
          else{
            if($sender->getGamemode() == 0){
              $player = $main->getServer()->getPlayer($main->PMD->get($senderName));
              $health = $player->getHealth();
              $maxHealth = $player->getMaxHealth();
              $lackHealth = $maxHealth-$health;
              
              if(!isset($sender->namedtag->SacrificeCD)){
                $sender->namedtag->SacrificeCD = new DoubleTag("SacrificeCD", time());
              }
              
              $allTime = $sender->namedtag->SacrificeCD->getValue();
              $nowTime = time();
              
              if($nowTime >= $allTime){
                if($health == $maxHealth){
                  $sender->sendMessage("§c=====玩家结婚系统=====\n§e处理失败, 原因: §f你的配偶的生命值已满");
                  return false;
                }
                else{
                  if($sender->getHealth() > $lackHealth){
                    //如果自己有足够的血可以补齐配偶的血
                    $sender->setHealth($sender->getHealth() - $lackHealth);
                    $player->setHealth($maxHealth);
                    $sender->sendMessage("§c=====玩家结婚系统=====\n§e成功牺牲了 §f{$lackHealth} §e滴血为配偶恢复生命!");
                    $player->sendMessage("§c=====玩家结婚系统=====\n§e你的配偶 §f{$senderName} §e牺牲了 §f{$lackHealth} §e滴血来为你恢复生命!");
                  }
                  else{
                    $sender->sendMessage("§c=====玩家结婚系统=====\n§e成功牺牲了你的性命来为配偶恢复生命!");
                    $player->sendMessage("§c=====玩家结婚系统=====\n§e你的配偶 §f{$senderName} §e牺牲了性命来为你恢复生命!");
                    $player->setHealth($maxHealth);
                    $sender->kill();
                  }
                  $sender->namedtag->SacrificeCD = new DoubleTag("SacrificeCD", time() + $main->PLS->get("功能设置")["牺牲冷却"]);
                }
              }
              else{
                $lastTime = $allTime - $nowTime;
                $sender->sendMessage("§c=====玩家结婚系统=====\n§e技能冷却中, 冷却时间还剩 §f{$lastTime} §e秒");
              }
              return true;
            }
            else{
              $sender->sendMessage("§c=====玩家结婚系统=====\n§e处理失败, 原因: 你无法在非生存模式下牺牲血量为配偶恢复生命");
              return false;
            }
          }
        break;
        
        case "配偶活跃":
          if(!$main->PMD->exists($senderName)){
            $sender->sendMessage("§c=====玩家结婚系统=====\n§e处理失败, 原因: §f你还没有结婚");
            return false;
          }
          
          $name = $main->PMD->get($senderName);
          if($main->PAT->exists($name)){
            $time = time() - $main->PAT->get($name);
            
            if($time <= 60){//单位/秒
              $sender->sendMessage("§c=====玩家结婚系统=====\n§e配偶 §f{$name} §e的最后活跃时间: §f{$time}秒前");
            }
            elseif(($time <= 3600)){//单位/分钟
              $min = ceil($time/60);
              $sender->sendMessage("§c=====玩家结婚系统=====\n§e配偶 §f{$name} §e的最后活跃时间: §f{$min}分钟前");
            }
            elseif($time <= 86400){//单位/小时
              $hour = ceil($time/3600);
              $sender->sendMessage("§c=====玩家结婚系统=====\n§e配偶 §f{$name} §e的最后活跃时间: §f{$hour}小时前");
            }
            else{//单位/天
              $day = ceil($time/86400);
              $sender->sendMessage("§c=====玩家结婚系统=====\n§e配偶 §f{$name} §e的最后活跃时间: §f{$day}天前");
            }
            return true;
          }
          else{
            $sender->sendMessage("§c=====玩家结婚系统=====\n§e配偶 §f{$name} §e的最后活跃时间: §f无记录");
            return true;
          }
        break;
        
        default:
           $sender->sendMessage("§c=====玩家结婚系统=====\n§e未知指令, 请输入 §f/结婚 §e查看指令帮助!");
           return false;
        break;
      }
    }
  }
  
}