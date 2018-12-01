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
namespace multisystemgo\command\rangersgo;

use pocketmine\Player;
use pocketmine\Server;
use pocketmine\plugin\PluginBase;
use pocketmine\math\Vector3;
use pocketmine\level\Level;
use pocketmine\level\Position;
use pocketmine\nbt\tag\StringTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\command\Command;
use pocketmine\command\CommandExecutor;
use pocketmine\command\CommandSender;
use onebone\economyapi\EconomyAPI;

class RangersGo{
  public static function onCommand($sender, $cmd, $label, $args, PluginBase $main){
    $senderName = $sender->getName();
    if($cmd->getName() == "战队"){
    
      if(!isset($args[0])){
        $sender->sendMessage("§d=====玩家战队系统=====\n§c正确用法: §6/战队 帮助 <页码>");
        return false;
      }
      
      switch($args[0]){
      
        case "重载":
          if($sender->isOp()){
            $main->PRL->reload();
            $main->RCD->reload();
            $main->PRD->reload();
            $main->RPD->reload();
            $main->PJR->reload();
            $sender->sendMessage("§d=====玩家战队系统=====\n§c玩家战队系统重载完成!");
            return true;
          }
          else{
            $sender->sendMessage("§d=====玩家战队系统=====\n§c你没有权限执行该指令!");
            return false;
          }
        break;
        
        case "帮助":
          if(!isset($args[1])){
            $sender->sendMessage("§d=====玩家战队系统=====\n§c正确用法: §6/战队 帮助 <页码>");
            return false;
          }
          
          //规范: 每个页面里最多拥有十条命令帮助
          if($sender->isOp()){
            switch($args[1]){
              case "1":
                $sender->sendMessage("§d=====玩家战队系统=====\n§c战队系统指令帮助: §6/战队 帮助 <页码>\n§c查看玩家战队列表: §6/战队 列表\n§c创建一支玩家战队: §6/战队 创建 <战队名称>\n§c解散我的玩家战队: §6/战队 解散\n§c编辑我的战队名称: §6/战队 编辑 <战队名称>\n§c设置我的战队基地: §6/战队 基地\n§c邀请玩家加入战队: §6/战队 邀请 <玩家名称>\n§c从战队中踢除玩家: §6/战队 踢除 <玩家名称>\n§c同意玩家加入战队: §6/战队 同意\n§c拒绝玩家加入战队: §6/战队 拒绝");
                return true;
              break;
              
              case "2":
                $sender->sendMessage("§d=====玩家战队系统=====\n§c添加战队的管理员: §6/战队 添加管理 <玩家名称>\n§c移除战队的管理员: §6/战队 移除管理 <玩家名称>\n§c升级我的玩家战队: §6/战队 升级\n§c转让我的玩家战队: §6/战队 转让 <玩家名称>\n§c请求战队成员集合: §6/战队 集合\n§c与其他的战队联盟: §6/战队 联盟 <战队名称>\n§c查看我的战队信息: §6/战队 信息\n§c查看队员活跃时间: §6/战队 队员活跃\n§c强制创建一支战队: §6/战队 强制创建 <队长名称> <战队名称>\n§c强制解散一支战队: §6/战队 强制解散 <战队名称>");
                return true;
              break;
              
              case "3":
                $sender->sendMessage("§d=====玩家战队系统=====\n§c强制编辑战队名称: §6/战队 强制编辑 <原队名> <新队名>\n§c强制设置战队基地: §6/战队 强制基地 <战队名称>\n§c强制玩家加入战队: §6/战队 强制加入 <玩家名称> <战队名称>\n§c强制玩家退出战队: §6/战队 强制踢除 <玩家名称>\n§c强制升级玩家战队: §6/战队 强制升级 <战队名称>\n§c强制转让玩家战队: §6/战队 强制转让 <战队名称> <玩家名称>\n§c强制两个战队联盟: §6/战队 强制联盟 <战队名称> <战队名称>\n§c强制捐赠玩家战队: §6/战队 强制捐赠 <战队名称> <数额>\n§c查看玩家战队信息: §6/战队 查看 <战队名称>\n§c申请加入玩家战队: §6/战队 加入 <战队名称>");
                return true;
              break;
              
              case "4":
                $sender->sendMessage("§d=====玩家战队系统=====\n§c退出我当前的战队: §6/战队 退出\n§c同意战队加入邀请: §6/战队 同意加入\n§c拒绝战队加入邀请: §6/战队 拒绝加入\n§c为你所在战队捐赠: §6/战队 捐赠 <数额>\n§c创建战队宣传木牌: §6在木牌第一行输入'战队宣传'文字\n§c重载玩家战队系统: §6/战队 重载");
                return true;
              break;
              
              default:
                $sender->sendMessage("§d=====玩家战队系统=====\n§c没有找到此页码, 请输入正确页码, 正确页码有: §61, 2, 3, 4");
                return false;
              break;
            }
          }
          else{
            if($main->RCD->exists($senderName)){//如果玩家是队长
              switch($args[1]){
                case "1":
                  $sender->sendMessage("§d=====玩家战队系统=====\n§c战队系统指令帮助: §6/战队 帮助 <页码>\n§c查看玩家战队列表: §6/战队 列表\n§c创建一支玩家战队: §6/战队 创建 <战队名称>\n§c解散我的玩家战队: §6/战队 解散\n§c编辑我的战队名称: §6/战队 编辑 <战队名称>\n§c设置我的战队基地: §6/战队 基地\n§c邀请玩家加入战队: §6/战队 邀请 <玩家名称>\n§c从战队中踢除玩家: §6/战队 踢除 <玩家名称>\n§c同意玩家加入战队: §6/战队 同意\n§c拒绝玩家加入战队: §6/战队 拒绝");
                  return true;
                break;
                
                case "2":
                  $sender->sendMessage("§d=====玩家战队系统=====\n§c添加战队的管理员: §6/战队 添加管理 <玩家名称>\n§c移除战队的管理员: §6/战队 移除管理 <玩家名称>\n§c升级我的玩家战队: §6/战队 升级\n§c转让我的玩家战队: §6/战队 转让 <玩家名称>\n§c请求战队成员集合: §6/战队 集合\n§c与其他的战队联盟: §6/战队 联盟 <战队名称>\n§c查看我的战队信息: §6/战队 信息\n§c查看队员活跃时间: §6/战队 队员活跃\n§c为你所在战队捐赠: §6/战队 捐赠 <数额>\n§c创建战队宣传木牌: §6在木牌第一行输入'战队宣传'文字");
                  return true;
                break;
                
                default:
                  $sender->sendMessage("§d=====玩家战队系统=====\n§c没有找到此页码, 请输入正确页码, 正确页码有: §61, 2");
                  return false;
                break;
              }
            }
            else{
              if($main->RMD->exists($senderName)){//如果玩家是战队管理员
                switch($args[1]){
                  case "1":
                    $sender->sendMessage("§d=====玩家战队系统=====\n§c战队系统指令帮助: §6/战队 帮助 <页码>\n§c查看玩家战队列表: §6/战队 列表\n§c查看我的战队信息: §6/战队 信息\n§c查看队员活跃时间: §6/战队 队员活跃\n§c邀请玩家加入战队: §6/战队 邀请 <玩家名称>\n§c从战队中踢除玩家: §6/战队 踢除 <玩家名称>\n§c同意玩家加入战队: §6/战队 同意\n§c拒绝玩家加入战队: §6/战队 拒绝\n§c请求战队成员集合: §6/战队 集合\n§c退出我当前的战队: §6/战队 退出");
                    return true;
                  break;
                  
                  case "2":
                    $sender->sendMessage("§d=====玩家战队系统=====\n§c为你所在战队捐赠: §6/战队 捐赠 <数额>\n§c创建战队宣传木牌: §6在木牌第一行输入'战队宣传'文字");
                    return true;
                  break;
                  
                  default:
                    $sender->sendMessage("§d=====玩家战队系统=====\n§c没有找到此页码, 请输入正确页码, 正确页码有: §61, 2");
                    return false;
                  break;
                }
              }
              else{//如果玩家是普通玩家
                switch($args[1]){
                  case "1":
                    $sender->sendMessage("§d=====玩家战队系统=====\n§c战队系统指令帮助: §6/战队 帮助 <页码>\n§c查看玩家战队列表: §6/战队 列表\n§c查看我的战队信息: §6/战队 信息\n§c申请加入玩家战队: §6/战队 加入 <战队名称>\n§c退出我当前的战队: §6/战队 退出\n§c同意战队加入邀请: §6/战队 同意加入\n§c拒绝战队加入邀请: §6/战队 拒绝加入\n§c邀请玩家加入战队: §6/战队 邀请 <玩家名称>\n§c为你所在战队捐赠: §6/战队 捐赠 <数额>");
                    return true;
                  break;
                  
                  default:
                    $sender->sendMessage("§d=====玩家战队系统=====\n§c没有找到此页码, 请输入正确页码, 正确页码有: §61");
                    return false;
                  break;
                }
              }
            }
          }
        break;
        
        case "列表":
          $sender->sendMessage("§d=====玩家战队系统=====");
          foreach($main->PRD->getAll() as $rangers=>$info){
            $sender->sendMessage("§c{$rangers}:\n §c队长: §6{$info["队长"]}\n §c管理: §6".implode(", ",$info["管理"])."\n §c基金: §6{$info["基金"]}\n §c人数: §6".count($info["成员"])."/{$info["人数"]}\n§6----------------------");
          }
          return true;
        break;
        
        case "信息":
          if(!$sender instanceof Player){
            $sender->sendMessage("§d=====玩家战队系统=====\n§c请在游戏中执行该指令!");
            return false;
          }
        
          if($main->RPD->exists($senderName)){//如果玩家已加入战队
            $rangers = $main->RPD->get($senderName);
            $info = $main->PRD->get($rangers);
            $sender->sendMessage("§d=====玩家战队系统=====\n§c战队名称: §6{$rangers}\n§c队长: §6{$info["队长"]}\n§c管理: §6".implode(", ",$info["管理"])."\n§c基金: §6{$info["基金"]}\n§c人数: §6".count($info["成员"])."/{$info["人数"]}\n§c成员: §6".implode(", ", $info["成员"]));
            return true;
          }
          else{
            $sender->sendMessage("§d=====玩家战队系统=====\n§c你还没有加入任何战队!");
            return false;
          }
        break;
        
        case "查看":
          if(!isset($args[1])){
            $sender->sendMessage("§d=====玩家战队系统=====\n§c正确用法: §6/战队 查看 <战队名称>");
            return false;
          }
          
          if(!$sender->isOp()){
            $sender->sendMessage("§d=====玩家战队系统=====\n§c你没有权限执行该指令!");
            return false;
          }
          
          if($main->PRD->exists($args[1])){
            $info = $main->PRD->get($args[1]);
            $sender->sendMessage("§d=====玩家战队系统=====\n§c战队名称: §6{$args[1]}\n§c队长: §6{$info["队长"]}\n§c管理: §6".implode(", ",$info["管理"])."\n§c基金: §6{$info["基金"]}\n§c人数: §6".count($info["成员"])."/{$info["人数"]}\n§c成员: §6".implode(", ",$info["成员"]));
            return true;
          }
          else{
            $sender->sendMessage("§d=====玩家战队系统=====\n§c没有找到 {$args[1]} 战队!");
            return false;
          }
        break;
        
        case "创建":
          if(!$sender instanceof Player){
            $sender->sendMessage("§d=====玩家战队系统=====\n§c请在游戏中执行该指令!");
            return false;
          }
        
          if(!isset($args[1])){
            $sender->sendMessage("§d=====玩家战队系统=====\n§c正确用法: §6/战队 创建 <战队名称>");
            return false;
          }
          
          if(EconomyAPI::getInstance()->myMoney($sender) < $main->PRS->get("功能设置")["战队最低基金"]){
            $sender->sendMessage("§d=====玩家战队系统=====\n§c创建失败, 原因: §6你的余额不足以支付创建战队的最低基金§c, 战队最低基金为: §6".$main->PRS->get("功能设置")["战队最低基金"]);
            return false;
          }
          
          if($main->PRL->exists($args[1])){//如果这个战队已经被创建
            $sender->sendMessage("§d=====玩家战队系统=====\n§c创建失败, 原因: §6战队名称已被使用");
            return false;
          }
          
          if($main->RCD->exists($senderName)){//如果玩家已创建过战队
            $sender->sendMessage("§d=====玩家战队系统=====\n§c创建失败, 原因: §6你已经创建了战队");
            return false;
          }
          
          if($main->RPD->exists($senderName)){//如果玩家已经加入战队
            $sender->sendMessage("§d=====玩家战队系统=====\n§c创建失败, 原因: §6你已经加入了战队");
            return false;
          }
          else{
            //战队列表
            $main->PRL->set($args[1], $senderName);
            $main->PRL->save();
            //队长数据
            $main->RCD->set($senderName, $args[1]);
            $main->RCD->save();
            //战队数据
            $main->PRD->set($args[1], [
              "队长" => $senderName,
              "管理" => [],
              "基金" => $main->PRS->get("功能设置")["战队最低基金"],
              "基地" => "无",
              "人数" => $main->PRS->get("功能设置")["战队初始人数"],
              "成员" => [$senderName],
            ]);
            $main->PRD->save();
            //玩家加了什么战队
            $main->RPD->set($senderName, $args[1]);
            $main->RPD->save();
            EconomyAPI::getInstance()->reduceMoney($senderName, $main->PRS->get("功能设置")["战队最低基金"]);
            $sender->sendMessage("§d=====玩家战队系统=====\n§c恭喜你, 成功花费§6 ".$main->PRS->get("功能设置")["战队最低基金"]." §c元创建了 §6{$args[1]} §c战队, 赶快邀请玩家加入你的战队吧!");
            $main->getServer()->broadcastMessage("§d=====玩家战队系统=====\n§c玩家 §6{$senderName} §c创建了一支新的 §6{$args[1]} §c战队, 还没加入战队的玩家可以加入该战队哦!");
            return true;
          }
        break;
        
        case "强制创建":
          if(!$sender->isOp()){
            $sender->sendMessage("§d=====玩家战队系统=====\n§c你没有权限执行该指令!");
            return false;
          }
        
          if(!isset($args[1]) OR !isset($args[2])){
            $sender->sendMessage("§d=====玩家战队系统=====\n§c正确用法: §6/战队 强制创建 <队长名称> <战队名称>");
            return false;
          }
          
          if($main->RCD->exists($args[1])){//如果玩家已创建过战队
            $sender->sendMessage("§d=====玩家战队系统=====\n§c创建失败, 原因: §6玩家 {$args[1]} 已经创建了战队");
            return false;
          }
          
          if($main->RPD->exists($args[1])){//如果玩家已经加入战队
            $sender->sendMessage("§d=====玩家战队系统=====\n§c创建失败, 原因: §6玩家 {$args[1]} 已经加入了战队");
            return false;
          }
          
          if($main->PRL->exists($args[2])){//如果这个战队已经被创建
            $sender->sendMessage("§d=====玩家战队系统=====\n§c创建失败, 原因: §6战队名称已被使用");
            return false;
          }
          else{
            //战队列表
            $main->PRL->set($args[2], $args[1]);
            $main->PRL->save();
            
            //队长数据
            $main->RCD->set($args[1], $args[2]);
            $main->RCD->save();
            
            //战队数据
            $main->PRD->set($args[2], [
              "队长" => $args[1],
              "管理" => [],
              "基金" => $main->PRS->get("功能设置")["战队最低基金"],
              "基地" => "无",
              "人数" => $main->PRS->get("功能设置")["战队初始人数"],
              "成员" => [$args[1]],
            ]);
            $main->PRD->save();
            //玩家加了什么战队
            $main->RPD->set($args[1],$args[2]);
            $main->RPD->save();
            $sender->sendMessage("§d=====玩家战队系统=====\n§c成功强制创建战队, 队名: §6{$args[2]}§c, 队长: §6{$args[1]}");
            return true;
          }
        break;
        
        case "解散":
          if(!$sender instanceof Player){
            $sender->sendMessage("§d=====玩家战队系统=====\n§c请在游戏中执行该指令!");
            return false;
          }
        
          if($main->RCD->exists($senderName)){//如果玩家已创建过战队
            $rangers = $main->RCD->get($senderName);
            //移除玩家加了什么战队/更新玩家信息
            foreach($main->PRD->get($rangers)["成员"] as $key => $name){
              if($main->RPD->exists($name)){
                $main->RPD->remove($name);
              }
            }
            $main->RPD->save();
            
            //移除战队管理员数据
            foreach($main->PRD->get($rangers)["管理"] as $key => $name){
              if($main->RMD->exists($name)){
                $main->RMD->remove($name);
              }
              if($name !== $senderName AND $main->getServer()->getPlayer($name) !== null){
                //给战队成员发送战队解散的悲剧消息
                $main->getServer()->getPlayer($name)->sendMessage("§d=====玩家战队系统=====\n§c你所加入的 §6{$rangers} §c战队已被战队队长 §6{$senderName} §c解散!");
              }
            }
            $main->RMD->save();
            
            //移除战队数据
            $main->PRD->remove($rangers);
            $main->PRD->save();
            
            //移除战队列表
            $main->PRL->remove($rangers);
            $main->PRL->save();
            
            //移除战队木牌
            if($main->RDS->exists($rangers)){
              $main->RDS->remove($main->RDS->get($rangers));
              $main->RDS->remove($rangers);
              $main->RDS->save();
            }
            
            //移除队长数据
            $main->RCD->remove($senderName);
            $main->RCD->save();
            
            $sender->sendMessage("§d=====玩家战队系统=====\n§c成功解散你的玩家战队!");
            return true;
          }
          else{
            $sender->sendMessage("§d=====玩家战队系统=====\n§c处理失败, 原因: §6你还未创建战队");
            return false;
          }
        break;
        
        case "强制解散":
          if(!$sender->isOp()){
            $sender->sendMessage("§d=====玩家战队系统=====\n§c你没有权限执行该指令!");
            return false;
          }
        
          if(!isset($args[1])){
            $sender->sendMessage("§d=====玩家战队系统=====\n§c正确用法: §6/战队 强制解散 <战队名称>");
            return false;
          }
          
          if($main->PRL->exists($args[1])){//如果这个战队已经被创建
            //移除队长数据
            $main->RCD->remove($main->PRD->get($args[1])["队长"]);
            $main->RCD->save();
            
            //移除玩家加了什么战队/更新玩家信息
            foreach($main->PRD->get($args[1])["成员"] as $key=>$name){
              if($main->RPD->exists($name)){
                $main->RPD->remove($name);
              }
            }
            $main->RPD->save();
            
            //移除战队管理员数据
            foreach($main->PRD->get($args[1])["管理"] as $key=>$name){
              if($main->RMD->exists($name)){
                $main->RMD->remove($name);
              }
              if($main->getServer()->getPlayer($name) !== null){
                //给战队成员发送战队解散的悲剧消息
                $main->getServer()->getPlayer($name)->sendMessage("§d=====玩家战队系统=====\n§c你所加入的 §6{$args[1]} §c战队已被管理员 §6{$senderName} §c强制解散!");
              }
            }
            $main->RMD->save();
            
            //移除战队数据
            $main->PRD->remove($args[1]);
            $main->PRD->save();
            
            //移除战队木牌
            if($main->RDS->exists($args[1])){
              $main->RDS->remove($main->RDS->get($args[1]));
              $main->RDS->remove($args[1]);
              $main->RDS->save();
            }
            
            //移除战队列表
            $main->PRL->remove($args[1]);
            $main->PRL->save();
            
            $sender->sendMessage("§d=====玩家战队系统=====\n§c成功强制解散 §6{$args[1]} §c战队!");
            return true;
          }
          else{
            $sender->sendMessage("§d=====玩家战队系统=====\n§c处理失败, 原因: §6没有找到 {$args[1]} 战队");
            return false;
          }
        break;
        
        case "编辑":
          if(!$sender instanceof Player){
            $sender->sendMessage("§d=====玩家战队系统=====\n§c请在游戏中执行该指令!");
            return false;
          }
        
          if(!isset($args[1])){
            $sender->sendMessage("§d=====玩家战队系统=====\n§c正确用法: §6/战队 编辑 <战队名称>");
            return false;
          }
          
          if(!$main->RCD->exists($senderName)){
            $sender->sendMessage("§d=====玩家战队系统=====\n§c处理失败, 原因: §6你还未创建战队");
            return false;
          }
            
          if($main->PRL->exists($args[1])){//如果新队名已被使用
            $sender->sendMessage("§d=====玩家战队系统=====\n§c处理失败, 原因: §6新的战队名称已被使用");
            return false;
          }
            
          $oldName = $main->RCD->get($senderName);
          $editMoney = $main->PRS->get("功能设置")["修改队名费用"];
          if($main->PRD->get($oldName)["基金"] >= $editMoney){
            //更新战队数据
            $main->PRD->set($args[1], $main->PRD->get($oldName));
            $main->PRD->remove($oldName);
            $main->PRD->save();
            
            //更新战队列表
            $main->PRL->remove($oldName);
            $main->PRL->set($args[1], $senderName);
            $main->PRL->save();
            
            //更新队长数据
            $main->RCD->set($senderName, $args[1]);
            $main->RCD->save();
            
            //更新玩家加了什么战队/更新玩家信息
            $data = $main->PRD->get($args[1]);
            
            foreach($data["成员"] as $key => $name){
              $main->RPD->set($name, $args[1]);
              if($name !== $senderName AND $main->getServer()->getPlayer($name) !== null){
                //给战队成员发送战队更名的消息
                $main->getServer()->getPlayer($name)->sendMessage("§d=====玩家战队系统=====\n§c你所加入的 §6{$oldName} §c战队已被战队队长 §6{$senderName} §c更名为 §6{$args[1]} §c战队!");
              }
            }
            $main->RPD->save();
            
            //扣除战队基金
            $main->PRD->set($args[1], [
              "队长" => $data["队长"],
              "管理" => $data["管理"],
              "基金" => $data["基金"] - $editMoney,
              "基地" => $data["基地"],
              "人数" => $data["人数"],
              "成员" => $data["成员"],
            ]);
            $main->PRD->save();
            
            $sender->sendMessage("§d=====玩家战队系统=====\n§c成功花费了 §6{$editMoney} §c战队基金来编辑队名, 你的战队新队名为: §6{$args[1]}");
            return true;
          }
          else{
            $sender->sendMessage("§d=====玩家战队系统=====\n§c处理失败, 原因: 战队基金不足以支付编辑队名所需要的费用§c, 编辑队名费用: §6{$editMoney}§c, 请联系管理员或队员捐赠金币到你的战队基金.");
            return false;
          }
        break;
        
        case "强制编辑":
          if(!$sender->isOp()){
            $sender->sendMessage("§d=====玩家战队系统=====\n§c你没有权限执行该指令!");
            return false;
          }
        
          if(!isset($args[1]) OR!isset($args[2])){
            $sender->sendMessage("§d=====玩家战队系统=====\n§c正确用法: §6/战队 强制编辑 <原队名> <新队名>");
            return false;
          }
          
          if(!$main->PRL->exists($args[1])){
            $sender->sendMessage("§d=====玩家战队系统=====\n§c处理失败, 原因: §6没有找到 {$args[1]} 战队");
            return false;
          }
                
          if($main->PRL->exists($args[2])){//如果新队名已被使用
            $sender->sendMessage("§d=====玩家战队系统=====\n§c处理失败, 原因: §6新的战队名称已被使用");
            return false;
          }
          else{
            //更新战队数据
            $main->PRD->set($args[2], $main->PRD->get($args[1]));
            $main->PRD->remove($args[1]);
            $main->PRD->save();
            
            //更新战队列表
            $main->PRL->remove($args[1]);
            $main->PRL->set($args[2], $main->PRD->get($args[2])["队长"]);
            $main->PRL->save();
            
            //更新队长数据
            $main->RCD->set($main->PRD->get($args[2])["队长"], $args[2]);
            $main->RCD->save();
            
            //更新玩家加了什么战队/更新玩家信息
            foreach($main->PRD->get($args[2])["成员"] as $key => $name){
              $main->RPD->set($name, $args[2]);
              if($main->getServer()->getPlayer($name) !== null){
                //给战队成员发送战队更名的消息
                $main->getServer()->getPlayer($name)->sendMessage("§d=====玩家战队系统=====\n§c你所加入的 §6{$args[1]} §c战队已被管理员 §6{$senderName} §c强制更名为 §6{$args[2]} §c战队!");
              }
            }
            $main->RPD->save();
            
            $sender->sendMessage("§d=====玩家战队系统=====\n§c成功将 §6{$args[1]} §c战队队名强制编辑为 §6{$args[2]} §c.");
            return true;
          }
        break;
        
        case "基地":
          if(!$sender instanceof Player){
            $sender->sendMessage("§d=====玩家战队系统=====\n§c请在游戏中执行该指令!");
            return false;
          }
        
          if($main->RCD->exists($senderName)){//如果玩家已创建过战队
            $rangers = $main->RCD->get($senderName);
            $data = $main->PRD->get($rangers);
            $main->PRD->set($rangers, [
              "队长" => $data["队长"],
              "管理" => $data["管理"],
              "基金" => $data["基金"],
              "基地" => intval($sender->getX()).":".intval($sender->getY()).":".intval($sender->getZ()).":".$sender->getLevel()->getFolderName(),
              "人数" => $data["人数"],
              "成员" => $data["成员"],
            ]);
            $main->PRD->save();
            
            $sender->sendMessage("§d=====玩家战队系统=====\n§c成功将当前位置设置为你的战队集合点!");
            return true;
          }
          else{
            $sender->sendMessage("§d=====玩家战队系统=====\n§c设置失败, 原因: §6你还未创建战队");
            return false;
          }
        break;
        
        case "强制基地":
          if(!$sender instanceof Player){
            $sender->sendMessage("§d=====玩家战队系统=====\n§c请在游戏中执行该指令!");
            return false;
          }
        
          if(!$sender->isOp()){
            $sender->sendMessage("§d=====玩家战队系统=====\n§c你没有权限执行该指令!");
            return false;
          }
        
          if(!isset($args[1])){
            $sender->sendMessage("§d=====玩家战队系统=====\n§c正确用法: §6/战队 强制基地 <战队名称>");
            return false;
          }
          
          if($main->PRL->exists($args[1])){//如果这个战队已经被创建
            $data = $main->PRD->get($args[1]);
            $main->PRD->set($args[1], [
              "队长" => $data["队长"],
              "管理" => $data["管理"],
              "基金" => $data["基金"],
              "基地" => $sender->getX().":".$sender->getY().":".$sender->getZ().":".$sender->getLevel()->getName(),
              "人数" => $data["人数"],
              "成员" => $data["成员"],
            ]);
            $main->PRD->save();
            
            $sender->sendMessage("§d=====玩家战队系统=====\n§c成功将当前位置强制设置为 §6{$args[1]} §c战队集合点!");
            return true;
          }
          else{
            $sender->sendMessage("§d=====玩家战队系统=====\n§c处理失败, 原因: §6没有找到 {$args[1]} 战队");
            return false;
          }
        break;
        
        case "邀请":
          if(!$sender instanceof Player){
            $sender->sendMessage("§d=====玩家战队系统=====\n§c请在游戏中执行该指令!");
            return false;
          }
          
          if(!isset($args[1])){
            $sender->sendMessage("§d=====玩家战队系统=====\n§c正确用法: §6/战队 邀请 <玩家名称>");
            return false;
          }
          
          if(!$main->RPD->exists($senderName)){
            $sender->sendMessage("§d=====玩家战队系统=====\n§c设置失败, 原因: §6你还未加入战队");
            return false;
          }
            
          $rangers = $main->RPD->get($senderName);
          
          if(in_array($args[1], $main->PRD->get($rangers)["成员"])){
            $sender->sendMessage("§d=====玩家战队系统=====\n§c邀请失败, 原因: §6玩家 {$args[1]} 已经加入你的战队");
            return false;
          }
          
          if($main->RPD->exists($args[1])){
            $sender->sendMessage("§d=====玩家战队系统=====\n§c邀请失败, 原因: §6玩家 {$args[1]} 已经加入其他战队");
            return false;
          }
          
          if($main->getServer()->getPlayer($args[1]) == null){
            $sender->sendMessage("§d=====玩家战队系统=====\n§c邀请失败, 原因: §6无法找到 {$args[1]} 玩家");
            return false;
          }
          
          if($main->PRD->get($rangers)["人数"] == count($main->PRD->get($rangers)["成员"])){
            $sender->sendMessage("§d=====玩家战队系统=====\n§c邀请失败, 原因: 人数已达上限");
            return false;
          }
          else{
            $player = $main->getServer()->getPlayer($args[1]);
            $player->namedtag->JoinTheNewRangers = new StringTag("JoinTheNewRangers", $rangers);
            $player->sendMessage("§d=====玩家战队系统=====\n§c玩家 §6{$senderName} §c邀请你加入 §6{$rangers} §c战队, 如果你愿意, 请输入指令 §6/战队 同意加入 §c来接受邀请加入战队, 如果不愿意, 请输入指令 §6/ 战队 拒绝加入 §c来拒绝他的战队邀请.");
            $sender->sendMessage("§d=====玩家战队系统=====\n§c成功向玩家 §6{$args[1]} §c发出邀请!");
            return true;
          }
        break;
        
        case "同意加入":
          if(!$sender instanceof Player){
            $sender->sendMessage("§d=====玩家战队系统=====\n§c请在游戏中执行该指令!");
            return false;
          }
          
          if(!isset($sender->namedtag->JoinTheNewRangers)){
            $sender->sendMessage("§d=====玩家战队系统=====\n§c你还没收到任何战队的邀请.");
            return false;
          }
          
          $rangers = $sender->namedtag->JoinTheNewRangers->getValue();
          
          if(!$main->PRL->exists($rangers)){
            unset($sender->namedtag->JoinTheNewRangers);
            $sender->sendMessage("§d=====玩家战队系统=====\n§c加入失败, 原因: §6该战队已经解散或者已经更名");
            return false;
          }
          
          if($main->PRD->get($rangers)["人数"] == count($main->PRD->get($rangers)["成员"])){
            unset($sender->namedtag->JoinTheNewRangers);
            $sender->sendMessage("§d=====玩家战队系统=====\n§c加入失败, 原因: 该战队人数已达上限");
            return false;
          }
          else{
            foreach($main->PRD->get($rangers)["成员"] as $key => $name){
              if($main->getServer()->getPlayer($name) !== null){
                $main->getServer()->getPlayer($name)->sendMessage("§d=====玩家战队系统=====\n§c欢迎玩家 §6{$senderName} §c加入 §6{$rangers} §c战队!");
              }
            }
            
            //更新玩家加了什么战队
            $main->RPD->set($senderName, $rangers);
            $main->RPD->save();
            
            //更新战队数据
            $data = $main->PRD->get($rangers);
            $players = $data["成员"];
            $players[] = $senderName;
            $main->PRD->set($rangers,[
              "队长" => $data["队长"],
              "管理" => $data["管理"],
              "基金" => $data["基金"],
              "基地" => $data["基地"],
              "人数" => $data["人数"],
              "成员" => $players,
            ]);
            $main->PRD->save();
            
            $sender->sendMessage("§d=====玩家战队系统=====\n§c成功加入战队: §6{$rangers}");
            unset($sender->namedtag->JoinTheNewRangers);
            return true;
          }
        break;
        
        case "强制加入":
          if(!$sender->isOp()){
            $sender->sendMessage("§d=====玩家战队系统=====\n§c你没有权限执行该指令!");
            return false;
          }
        
          if(!isset($args[1]) OR !isset($args[2])){
            $sender->sendMessage("§d=====玩家战队系统=====\n§c正确用法: §6/战队 强制加入 <玩家名称> <战队名称>");
            return false;
          }
          
          if(!$main->PRL->exists($args[2])){
            $sender->sendMessage("§d=====玩家战队系统=====\n§c处理失败, 原因: §6没有找到 {$args[2]} 战队");
            return false;
          }
          
          if(in_array($args[1],$main->PRD->get($args[2])["成员"])){
            $sender->sendMessage("§d=====玩家战队系统=====\n§c处理失败, 原因: §6玩家 {$args[1]} 已经加入 {$args[2]} 战队");
            return false;
          }
          
          if($main->RPD->exists($args[1])){
            $sender->sendMessage("§d=====玩家战队系统=====\n§c处理失败, 原因: §6玩家 {$args[1]} 已经加入其他战队");
            return false;
          }
          
          if($main->PRD->get($args[2])["人数"] == count($main->PRD->get($args[2])["成员"])){
            $sender->sendMessage("§d=====玩家战队系统=====\n§c加入失败, 原因: 该战队人数已达上限");
            return false;
          }
          else{
            foreach($main->PRD->get($args[2])["成员"] as $key=>$name){
              if($main->getServer()->getPlayer($name) !== null){
                $main->getServer()->getPlayer($name)->sendMessage("§d=====玩家战队系统=====\n§c欢迎玩家 §6{$args[1]} §c加入 §6{$args[2]} §c战队!");
              }
            }
            //更新玩家加了什么战队
            $main->RPD->set($args[1],$args[2]);
            $main->RPD->save();
            
            //更新战队数据
            $data = $main->PRD->get($args[2]);
            $players = $data["成员"];
            $players[] = $args[1];
            $main->PRD->set($args[2],[
              "队长" => $data["队长"],
              "管理" => $data["管理"],
              "基金" => $data["基金"],
              "基地" => $data["基地"],
              "人数" => $data["人数"],
              "成员" => $players,
            ]);
            $main->PRD->save();
            
            $sender->sendMessage("§d=====玩家战队系统=====\n§c成功强制将玩家 §6{$args[1]} §c加入 §6{$args[2]} §c战队!");
            if($main->getServer()->getPlayer($args[1]) !== null){
              $main->getServer()->getPlayer($args[1])->sendMessage("§d=====玩家战队系统=====\n§c你已被管理员 §6{$senderName} §c强制加入 §6{$args[2]} §c战队!");
            }
            return true;
          }
        break;
        
        case "拒绝加入":
          if(!$sender instanceof Player){
            $sender->sendMessage("§d=====玩家战队系统=====\n§c请在游戏中执行该指令!");
            return false;
          }
        
          if(isset($sender->namedtag->JoinTheNewRangers)){
            $rangers = $sender->namedtag->JoinTheNewRangers->getValue();
            foreach($main->PRD->get($rangers)["管理"] as $key => $name){
              if($main->getServer()->getPlayer($name) !== null){
                $main->getServer()->getPlayer($name)->sendMessage("§d=====玩家战队系统=====\n§c玩家 §6{$senderName} §c拒绝加入 §6{$rangers} §c战队!");
              }
            }
            
            if($main->getServer()->getPlayer($main->PRD->get($rangers)["队长"])!==null){
              $main->getServer()->getPlayer($main->PRD->get($rangers)["队长"])->sendMessage("§d=====玩家战队系统=====\n§c玩家 §6{$senderName} §c拒绝加入 §6{$rangers} §c战队!");
            }
            
            $sender->sendMessage("§d=====玩家战队系统=====\n§c已拒绝 §6 {$rangers} §c战队的邀请.");
            unset($sender->namedtag->JoinTheNewRangers);
            return true;
          }
          else{
            $sender->sendMessage("§d=====玩家战队系统=====\n§c你还没收到任何战队的邀请.");
            return false;
          }
        break;
        
        case "踢除":
          if(!$sender instanceof Player){
            $sender->sendMessage("§d=====玩家战队系统=====\n§c请在游戏中执行该指令!");
            return false;
          }
        
          if(!isset($args[1])){
            $sender->sendMessage("§d=====玩家战队系统=====\n§c正确用法: §6/战队 踢除 <玩家名称>");
            return false;
          }
          
          if($args[1] == $senderName){
            $sender->sendMessage("§d=====玩家战队系统=====\n§c踢除失败, 原因: §6无法将自己踢出自己的战队§c, 你可以选择解散自己的战队.");
            return false;
          }
            
          //如果玩家不是队长/管理
          if(!$main->RCD->exists($senderName) AND !$main->RMD->exists($senderName)){
            $sender->sendMessage("§d=====玩家战队系统=====\n§c设置失败, 原因: §6你还不是战队队长/战队管理员");
            return false;
          }
            
          $rangers = ($main->RCD->exists($senderName)) ? $main->RCD->get($senderName) : $main->RMD->get($senderName);
          
          if(in_array($args[1], $main->PRD->get($rangers)["成员"])){
            //更新玩家加了什么战队
            $main->RPD->remove($args[1]);
            $main->RPD->save();
            
            //更新战队数据
            $data = $main->PRD->get($rangers);
            $players = $data["成员"];
            foreach($players as $key => $name){
              if($name == $args[1]){
                unset($players[$key]);
              }
            }
            
            $managers = $data["管理"];
            foreach($managers as $key => $name){
              if($name == $args[1]){
                unset($managers[$key]);
              }
            }
            
            $main->PRD->set($rangers, [
              "队长" => $data["队长"],
              "管理" => $managers,
              "基金" => $data["基金"],
              "基地" => $data["基地"],
              "人数" => $data["人数"],
              "成员" => $players,
            ]);
            $main->PRD->save();
            
            $main->RMD->remove($args[1]);
            $main->RMD->save();
            
            foreach($main->PRD->get($rangers)["成员"] as $key => $name){
              if($main->getServer()->getPlayer($name) !== null){
                $main->getServer()->getPlayer($name)->sendMessage("§d=====玩家战队系统=====\n§c玩家 §6{$args[1]} §c已被踢出 §6{$rangers} §c战队!");
              }
            }
            
            if($main->getServer()->getPlayer($args[1]) !== null){
              $main->getServer()->getPlayer($args[1])->sendMessage("§d=====玩家战队系统=====\n§c很遗憾, 你已被踢出 §6{$rangers} §c战队!");
            }
            
            $sender->sendMessage("§d=====玩家战队系统=====\n§c成功将玩家 §6{$args[1]} §c踢出战队!");
            return true;
          }
          else{
            $sender->sendMessage("§d=====玩家战队系统=====\n§c踢除失败, 原因: §6玩家 {$args[1]} 还不是你战队的队员");
            return false;
          }
        break;
        
        case "强制踢除":
          if(!$sender->isOp()){
            $sender->sendMessage("§d=====玩家战队系统=====\n§c你没有权限执行该指令!");
            return false;
          }
        
          if(!isset($args[1])){
            $sender->sendMessage("§d=====玩家战队系统=====\n§c正确用法: §6/战队 强制踢除 <玩家名称>");
            return false;
          }
          
          if(!$main->RPD->exists($args[1])){
            $sender->sendMessage("§d=====玩家战队系统=====\n§c处理失败, 原因: §6玩家 {$args[1]} 未加入任何战队");
            return false;
          }
          $rangers = $main->RPD->get($args[1]);
          if($args[1] == $main->PRD->get($rangers)["队长"]){
            $sender->sendMessage("§d=====玩家战队系统=====\n§c处理失败, 原因: §6无法踢除战队队长§c, 你可以选择强制解散战队.");
            return false;
          }
          else{
            //更新战队数据
            $data = $main->PRD->get($rangers);
            $players = $data["成员"];
            foreach($players as $key => $name){
              if($name == $args[1]){
                unset($players[$key]);
              }
            }
            
            $managers = $data["管理"];
            foreach($managers as $key => $name){
              if($name == $args[1]){
                unset($managers[$key]);
              }
            }
            
            $main->PRD->set($rangers, [
              "队长" => $data["队长"],
              "管理" => $managers,
              "基金" => $data["基金"],
              "基地" => $data["基地"],
              "人数" => $data["人数"],
              "成员" => $players,
            ]);
            $main->PRD->save();
            
            $main->RMD->remove($args[1]);
            $main->RMD->save();
            
            foreach($main->PRD->get($rangers)["成员"] as $key => $name){
              if($main->getServer()->getPlayer($name) !== null){
                $main->getServer()->getPlayer($name)->sendMessage("§d=====玩家战队系统=====\n§c玩家 §6{$args[1]} §c已被管理员 §6{$senderName} §c强制踢出 §6{$rangers} §c战队!");
              }
            }
            
            if($main->getServer()->getPlayer($args[1]) !== null){
              $player = $main->getServer()->getPlayer($args[1]);
              $player->sendMessage("§d=====玩家战队系统=====\n§c很遗憾, 你已被管理员 §6{$senderName} §c强制踢出 §6{$rangers} §c战队!");
            }
            
            //更新玩家加了什么战队
            $main->RPD->remove($args[1]);
            $main->RPD->save();
            
            $sender->sendMessage("§d=====玩家战队系统=====\n§c成功将玩家 §6{$args[1]} §c强制踢出 §6{$rangers} §c战队!");
            return true;
          }
        break;
        
        case "队员活跃":
          if(!$sender instanceof Player){
            $sender->sendMessage("§d=====玩家战队系统=====\n§c请在游戏中执行该指令!");
            return false;
          }
        
          //如果玩家不是队长/管理
          if(!$main->RCD->exists($senderName) AND !$main->RMD->exists($senderName)){
            $sender->sendMessage("§d=====玩家战队系统=====\n§c查看失败, 原因: §6你还不是战队队长/战队管理员");
            return false;
          }
          else{
            $rangers = ($main->RCD->exists($senderName)) ? $main->RCD->get($senderName) : $main->RMD->get($senderName);
            $sender->sendMessage("§d=====玩家战队系统=====");
            foreach($main->PRD->get($rangers)["成员"] as $key => $name){
              if($main->PAT->exists($name)){
                $time = time()-$main->PAT->get($name);
                if($time <= 60){//单位/秒
                  $sender->sendMessage("§c队员 §6{$name} §c的最后活跃时间: §6{$time}秒前");
                }
                elseif($time <= 3600){//单位/分钟
                  $min = ceil($time / 60);
                  $sender->sendMessage("§c队员 §6{$name} §c的最后活跃时间: §6{$min}分钟前");
                }
                elseif($time <= 86400){//单位/小时
                  $hour = ceil($time / 3600);
                  $sender->sendMessage("§c队员 §6{$name} §c的最后活跃时间: §6{$hour}小时前");
                }
                else{//单位/天
                  $day = ceil($time / 86400);
                  $sender->sendMessage("§c队员 §6{$name} §c的最后活跃时间: §6{$day}天前");
                }
              }
              else{
                $sender->sendMessage("§c{$name} 的最后活跃时间: §6无记录");
              }
            }
            return true;
          }
        break;
        
        case "加入":
          if(!$sender instanceof Player){
            $sender->sendMessage("§d=====玩家战队系统=====\n§c请在游戏中执行该指令!");
            return false;
          }
        
          if(!isset($args[1])){
            $sender->sendMessage("§d=====玩家战队系统=====\n§c正确用法: §6/战队 加入 <战队名称>");
            return false;
          }
          
          if($main->RPD->exists($senderName)){
            $sender->sendMessage("§d=====玩家战队系统=====\n§c申请失败, 原因: §6你已经加入了战队");
            return false;
          }
          
          if(!$main->PRL->exists($args[1])){
            $sender->sendMessage("§d=====玩家战队系统=====\n§c申请失败, 原因: §6没有找到 {$args[1]} 战队");
            return false;
          }
          
          if($main->PRD->get($args[1])["人数"] == count($main->PRD->get($args[1])["成员"])){
            $sender->sendMessage("§d=====玩家战队系统=====\n§c申请失败, 原因: §6该战队人数已达上限");
            return false;
          }
          else{
            foreach($main->PRD->get($args[1])["管理"] as $key => $name){
              if($main->getServer()->getPlayer($name) !== null){
                $main->getServer()->getPlayer($name)->sendMessage("§d=====玩家战队系统=====\n§c玩家 §6{$senderName} §c申请加入 §6{$args[1]} §c战队! 同意加入请输入指令 §6/战队 同意§c, 不同意加入则输入指令 §6/战队 拒绝 §c.");
              }
            }
            
            if($main->getServer()->getPlayer($main->PRD->get($args[1])["队长"]) !== null){
              $main->getServer()->getPlayer($main->PRD->get($args[1])["队长"])->sendMessage("§d=====玩家战队系统=====\n§c玩家 §6{$senderName} §c申请加入 §6{$args[1]} §c战队! 同意加入请输入指令 §6/战队 同意§c, 不同意加入则输入指令 §6/战队 拒绝 §c.");
            }
            
            //存储临时数据
            $main->PJR->set($senderName, $args[1]);
            $main->PJR->save();
            
            $sender->sendMessage("§d=====玩家战队系统=====\n§c成功申请加入 §6{$args[1]} §c战队, 请耐心等待处理!");
            return true;
          }
        break;
        
        case "退出":
          if(!$main->RPD->exists($senderName)){
            $sender->sendMessage("§d=====玩家战队系统=====\n§c处理失败, 原因: §6你还未加入战队");
            return false;
          }
          
          $rangers = $main->RPD->get($senderName);
          
          if($senderName == $main->PRD->get($rangers)["队长"]){
            $sender->sendMessage("§d=====玩家战队系统=====\n§c处理失败, 原因: §6你无法退出自己的战队§c, 你可以选择解散你的战队.");
            return false;
          }
          else{
            //更新战队数据
            $data = $main->PRD->get($rangers);
            $players = $data["成员"];
            foreach($players as $key => $name){
              if($name == $senderName){
                unset($players[$key]);
              }
            }
            
            $managers = $data["管理"];
            foreach($managers as $key => $name){
              if($name == $senderName){
                unset($manager[$key]);
              }
            }
            
            $main->PRD->set($rangers, [
              "队长" => $data["队长"],
              "管理" => $managers,
              "基金" => $data["基金"],
              "基地" => $data["基地"],
              "人数" => $data["人数"],
              "成员" => $players,
            ]);
            $main->PRD->save();
            
            $main->RMD->remove($senderName);
            $main->RMD->save();
            
            foreach($main->PRD->get($rangers)["成员"] as $key => $name){
              if($main->getServer()->getPlayer($name) !== null){
                $main->getServer()->getPlayer($name)->sendMessage("§d=====玩家战队系统=====\n§c玩家 §6{$senderName} §c已退出 §6{$rangers} §c战队!");
              }
            }
            
            //更新玩家加了什么战队
            $main->RPD->remove($senderName);
            $main->RPD->save();
            
            $sender->sendMessage("§d=====玩家战队系统=====\n§c成功退出 §6{$rangers} §c战队!");
            return true;
          }
        break;
        
        case "同意":
          if(!$sender instanceof Player){
            $sender->sendMessage("§d=====玩家战队系统=====\n§c请在游戏中执行该指令!");
            return false;
          }
        
          //如果玩家不是战队队长/战队管理员
          if(!$main->RCD->exists($senderName) AND !$main->RMD->exists($senderName)){
            $sender->sendMessage("§d=====玩家战队系统=====\n§c处理失败, 原因: §6你还不是战队队长/战队管理员");
            return false;
          }
          
          $myRangers = ($main->RCD->exists($senderName)) ? $main->RCD->get($senderName) : $main->RMD->get($senderName);
          
          if(in_array($myRangers, $main->PJR->getAll())){//如果临时数据中有申请加入自己战队的
            $sender->sendMessage("§d=====玩家战队系统=====");
            foreach($main->PJR->getAll() as $name => $rangers){
              if($rangers == $myRangers){
                if(count($main->PRD->get($myRangers)["成员"]) == $main->PRD->get($myRangers)["人数"]){
                  //移除临时数据
                  $main->PJR->remove($name);
                  $sender->sendMessage("§c玩家 §6{$name} §c无法加入战队, 原因: §6人数已达上限");
                  if($main->getServer()->getPlayer($name) !== null){
                    $sender->sendMessage("§d=====玩家战队系统=====\n§c战队加入失败, 原因: 人数已达上限");
                  }
                  return false;
                }
                else{
                  //更新玩家加了什么战队
                  $main->RPD->set($name,$myRangers);
                  
                  //更新战队数据
                  $data = $main->PRD->get($myRangers);
                  $players = $main->PRD->get($myRangers)["成员"];
                  $players[] = $name;
                  $main->PRD->set($myRangers, [
                    "队长" => $data["队长"],
                    "管理" => $data["管理"],
                    "基金" => $data["基金"],
                    "基地" => $data["基地"],
                    "人数" => $data["人数"],
                    "成员" => $players,
                  ]);
                  $main->RPD->save();
                  
                  //移除临时数据
                  $main->PJR->remove($name);
                  $sender->sendMessage("§c已批准玩家 §6{$name} §c加入 §6{$myRangers} §c战队.");
                }
              }
            }
            $main->PJR->save();
            
            if($main->getServer()->getPlayer($name) !== null){
              $main->getServer()->getPlayer($name)->sendMessage("§d=====玩家战队系统=====\n§c成功加入 §6{$myRangers} §c战队!");
            }
            return true;
          }
          else{
            $sender->sendMessage("§d=====玩家战队系统=====\n§c处理失败, 原因: §6还没有玩家申请加入战队");
            return false;
          }
        break;
        
        case "拒绝":
          if(!$sender instanceof Player){
            $sender->sendMessage("§d=====玩家战队系统=====\n§c请在游戏中执行该指令!");
            return false;
          }
        
          //如果玩家不是战队队长/战队管理员
          if(!$main->RCD->exists($senderName) AND !$main->RMD->exists($senderName)){
            $sender->sendMessage("§d=====玩家战队系统=====\n§c处理失败, 原因: §6你还不是战队队长/战队管理员");
            return false;
          }
          
          //如果临时数据中有申请加入自己战队的
          if(in_array($main->RCD->get($senderName), $main->PJR->getAll())){
            $sender->sendMessage("§d=====玩家战队系统=====");
            $myRangers = ($main->RCD->exists($senderName)) ? $main->RCD->get($senderName) : $main->RMD->get($senderName);
            foreach($main->PJR->getAll() as $name => $rangers){
              if($rangers == $myRangers){
                //移除临时数据
                $main->PJR->remove($name);
                if($main->getServer()->getPlayer($name) !== null){
                  $main->getServer()->getPlayer($name)->sendMessage("§d=====玩家战队系统=====\n§6 {$rangers} §c战队队长/管理 §6{$senderName} §c拒绝了你的入队申请!");
                }
                $sender->sendMessage("§c已拒绝玩家 §6{$name} §c的入队申请.");
              }
            }
            $main->PJR->save();
            return true;
          } 
          else{
            $sender->sendMessage("§d=====玩家战队系统=====\n§c处理失败, 原因: §6还没有玩家申请加入战队");
            return false;
          }
        break;
        
        case "转让":
          if(!$sender instanceof Player){
            $sender->sendMessage("§d=====玩家战队系统=====\n§c请在游戏中执行该指令!");
            return false;
          }
        
          if(!isset($args[1])){
            $sender->sendMessage("§d=====玩家战队系统=====\n§c正确用法: §6/战队 转让 <玩家名称>");
            return false;
          }
          
          if(!$main->RCD->exists($senderName)){//如果玩家没有创建过战队
            $sender->sendMessage("§d=====玩家战队系统=====\n§c处理失败, 原因: §6你还未创建战队");
            return false;
          }
            
          $rangers = $main->RCD->get($senderName);
          
          if($main->RPD->exists($args[1])){
            $sender->sendMessage("§d=====玩家战队系统=====\n§c处理失败, 原因: §6玩家 {$args[1]} 已经加入战队");
            return false;
          }
          else{
            $data = $main->PRD->get($rangers);
            $players = $data["成员"];
            foreach($players as $key => $name){
              if($name == $senderName){
                unset($players[$key]);
              }
            }
            $players[] = $args[1];
            $main->PRD->set($rangers, [
              "队长" => $args[1],
              "管理" => $data["管理"],
              "基金" => $data["基金"],
              "基地" => $data["基地"],
              "人数" => $data["人数"],
              "成员" => $players,
            ]);
            $main->PRD->save();
            
            //更新玩家加了什么战队
            $main->RPD->set($args[1], $rangers);
            $main->RPD->remove($senderName);
            $main->RPD->save();
            
            //更新战队列表
            $main->PRL->set($rangers, $args[1]);
            $main->PRL->save();
            
            //更新队长数据
            $main->RCD->set($args[1], $rangers);
            $main->RCD->remove($senderName);
            $main->RCD->save();
            
            if($main->getServer()->getPlayer($args[1]) !== null){
              $main->getServer()->getPlayer($args[1])->sendMessage("§d=====玩家战队系统=====\n§c玩家 §6{$senderName} §c已将他的 §6{$rangers} §c战队转让给你!");
            }
            
            foreach($main->PRD->get($rangers)["成员"] as $key => $name){
              if($main->getServer()->getPlayer($name) !== null){
                $main->getServer()->getPlayer($name)->sendMessage("§d=====玩家战队系统=====\n§c战队队长 §6{$senderName} §c已将 §6{$rangers} §c战队转让给玩家 §6{$args[1]} §c!");
              }
            }
            
            $sender->sendMessage("§d=====玩家战队系统=====\n§c成功将你的 §6{$rangers} §c战队转让给玩家 §6{$args[1]} §c.");
            return true;
          }
        break;
        
        case "强制转让":
          if(!$sender->isOp()){
            $sender->sendMessage("§d=====玩家战队系统=====\n§c你没有权限执行该指令!");
            return false;
          }
        
          if(!isset($args[1]) OR !isset($args[2])){
            $sender->sendMessage("§d=====玩家战队系统=====\n§c正确用法: §6/战队 强制转让 <战队名称> <玩家名称>");
            return false;
          }
          
          if(!$main->PRL->exists($args[1])){
            $sender->sendMessage("§d=====玩家战队系统=====\n§c处理失败, 原因: §6找不到 {$args[1]} 战队");
            return false;
          }
          
          if($main->RPD->exists($args[2])){
            $sender->sendMessage("§d=====玩家战队系统=====\n§c处理失败, 原因: §6玩家 {$args[2]} 已加入其他战队");
            return false;
          }
          else{
            //更新队长数据
            $main->RCD->set($args[2], $args[1]);
            $main->RCD->remove($main->PRD->get($args[1])["队长"]);
            $main->RCD->save();
            
            //更新玩家加了什么战队
            $main->RPD->set($args[2], $args[1]);
            $main->RPD->remove($main->PRD->get($args[1])["队长"]);
            $main->RPD->save();
            
            $data = $main->PRD->get($args[1]);
            $players = $data["成员"];
            foreach($players as $key => $name){
              if($name == $main->PRD->get($args[1])["队长"]){
                unset($players[$key]);
              }
            }
            $players[] = $args[2];
            $main->PRD->set($args[1], [
              "队长" => $args[2],
              "管理" => $data["管理"],
              "基金" => $data["基金"],
              "基地" => $data["基地"],
              "人数" => $data["人数"],
              "成员" => $players,
            ]);
            $main->PRD->save();
            
            //更新战队列表
            $main->PRL->set($args[1], $args[2]);
            $main->PRL->save();
            
            $sender->sendMessage("§d=====玩家战队系统=====\n§c成功将 §6{$args[1]} §c战队强制转让给玩家 §6{$args[2]} §c.");
            if($main->getServer()->getPlayer($args[2]) !== null){
              $main->getServer()->getPlayer($args[2])->sendMessage("§d=====玩家战队系统=====\n§c管理员 §6{$senderName} §c已将 §6{$rangers} §c战队强制转让给你!");
            }
            foreach($main->PRD->get($args[1])["成员"] as $key => $name){
              if($main->getServer()->getPlayer($name) !== null){
                $main->getServer()->getPlayer($name)->sendMessage("§d=====玩家战队系统=====\n§c管理员 §6{$senderName} §c已将 §6{$rangers} §c战队强制转让给玩家 §6{$args[1]} §c!");
              }
            }
            return true;
          }
        break;
        
        case "集合":
          if(!$sender instanceof Player){
            $sender->sendMessage("§d=====玩家战队系统=====\n§c请在游戏中执行该指令!");
            return false;
          }
        
          //如果玩家不是战队队长/战队管理员
          if(!$main->RCD->exists($senderName) AND !$main->RMD->exists($senderName)){
            $sender->sendMessage("§d=====玩家战队系统=====\n§c处理失败, 原因: §6你还不是战队队长/战队管理员");
            return false;
          }
          
          $rangers = ($main->RCD->exists($senderName)) ? $main->RCD->get($senderName) : $main->RMD->get($senderName);
          if($main->PRD->get($rangers)["基地"] == "无"){
            $sender->sendMessage("§d=====玩家战队系统=====\n§c集合失败, 原因: §6你的战队还未设置集合点(基地)");
            return false;
          }
          else{
            $pos = explode(":", $main->PRD->get($rangers)["基地"]);
            $sender->teleport(new Position(intval($pos[0]), intval($pos[1]), intval($pos[2]), $main->getServer()->getLevelByName($pos[3])));
            foreach($main->PRD->get($rangers)["成员"] as $key => $name){
              if($main->getServer()->getPlayer($name) !== null AND $name !== $senderName){
                $player = $main->getServer()->getPlayer($name);
                $player->namedtag->CallingAllTitans = new IntTag("CallingAllTitans", time());
                ($main->RCD->exists($senderName)) ? 
                $player->sendMessage("§d=====玩家战队系统=====\n§c战队队长 §6{$senderName} §c向你发出了集结令, 请在30秒内输入指令 §6/战队 接受 §c来同意集合 或 输入指令 §6/战队 拒绝 §c来拒绝集合!") :
                $player->sendMessage("§d=====玩家战队系统=====\n§c战队管理员 §6{$senderName} §c向你发出了集结令, 请在30秒内输入指令 §6/战队 接受 §c来同意集合 或 输入指令 §6/战队 推辞 §c来推辞集合!");
              }
            }
            $sender->sendMessage("§d=====玩家战队系统=====\n§c成功向战队所有在线成员发出集结令!");
            return true;
          }
        break;
        
        case "接受":
          if(!$sender instanceof Player){
            $sender->sendMessage("§d=====玩家战队系统=====\n§c请在游戏中执行该指令!");
            return false;
          }
        
          if(!$main->RPD->exists($senderName)){
            $sender->sendMessage("§d=====玩家战队系统=====\n§c处理失败, 原因: §6你还未加入战队");
            return false;
          }
          
          $rangers = $main->RPD->get($senderName);
          
          //战队集合
          if(isset($sender->namedtag->CallingAllTitans)){
            if(time() <= $sender->namedtag->CallingAllTitans->getValue() + 30){
              unset($sender->namedtag->CallingAllTitans);
              $pos = explode(":",$main->PRD->get($rangers)["基地"]);
              $sender->teleport(new Position(intval($pos[0]), intval($pos[1]), intval($pos[2]), $main->getServer()->getLevelByName($pos[3])));
              $sender->sendMessage("§d=====玩家战队系统=====\n§c成功接受战队集结令并传送到战队集合点(基地)!");
              return true;
            }
            else{
              unset($sender->namedtag->CallingAllTitans);
              $sender->sendMessage("§d=====玩家战队系统=====\n§c请求超时!");
              return false;
            }
          }
          //战队联盟
          elseif(isset($sender->namedtag->RangersUnionS)){
            if(!$main->PRD->exists($sender->namedtag->RangersUnionS->getValue())){
              $sender->sendMessage("§d=====玩家战队系统=====\n§c处理失败, 原因: §6".$sender->namedtag->RangersUnionS->getValue()." 战队已经解散!");
              unset($sender->namedtag->RangersUnionS);
              return false;
            }
          
            $data = $main->PRD->get($sender->namedtag->RangersUnionS->getValue());
            $datas = $main->PRD->get($rangers);
            $managers = array_merge($data["管理"], $datas["管理"]);
            $managers[] = $senderName;
            $players = array_merge($data["成员"], $datas["成员"]);
            
            $main->PRD->set($sender->namedtag->RangersUnionS->getValue(), [
              "队长" => $data["队长"],
              "管理" => $managers,
              "基金" => $data["基金"]+$datas["基金"],
              "基地" => $data["基地"],
              "人数" => $data["人数"]+$datas["人数"],
              "成员" => $players,
            ]);
            $main->PRD->remove($rangers);
            $main->PRD->save();
            
            //更新战队列表
            $main->PRL->remove($rangers);
            $main->PRL->save();
            
            //更新玩家加了什么战队
            $main->RPD->set($senderName,$sender->namedtag->RangersUnionS->getValue());
            $main->RPD->save();
            
            //战队管理员
            $main->RMD->set($senderName,$sender->namedtag->RangersUnionS->getValue());
            $main->RMD->save();
            
            //更新队长数据
            $main->RCD->remove($senderName);
            $main->RCD->save();
            
            foreach($main->PRD->get($sender->namedtag->RangersUnionS->getValue())["成员"] as $key => $name){
              if($main->getServer()->getPlayer($name) !== null){
                $main->getServer()->getPlayer($name)->sendMessage("§d=====玩家战队系统=====\n§6{$rangers} §c战队成功与 §6{$sender->namedtag->RangersUnionS->getValue()} §c战队进行联盟!");
              }
            }
            
            unset($sender->namedtag->RangersUnionS);
            return true;
          }
          else{
            $sender->sendMessage("§d=====玩家战队系统=====\n§c你还没收到任何请求!");
            return false;
          }
        break;
        
        case "推辞":
          if(!$sender instanceof Player){
            $sender->sendMessage("§d=====玩家战队系统=====\n§c请在游戏中执行该指令!");
            return false;
          }
          
          if(!$main->RPD->exists($senderName)){
            $sender->sendMessage("§d=====玩家战队系统=====\n§c处理失败, 原因: §6你还未加入战队");
            return false;
          }
        
          if(isset($sender->namedtag->CallingAllTitans)){
          if(time() <= $sender->namedtag->CallingAllTitans->getValue()+30){
            unset($sender->namedtag->CallingAllTitans);
            $sender->sendMessage("§d=====玩家战队系统=====\n§c成功推辞战队集合令!");
          }
          else{
            unset($sender->namedtag->CallingAllTitans);
            $sender->sendMessage("§d=====玩家战队系统=====\n§c请求超时!");
          }
        }
        elseif(isset($sender->namedtag->RangersUnionS)){
          if(!$main->PRD->exists($sender->namedtag->RangersUnionS->getValue())){
            $sender->sendMessage("§d=====玩家战队系统=====\n§c处理失败, 原因: §6".$sender->namedtag->RangersUnionS->getValue()." 战队已经解散!");
            unset($sender->namedtag->RangersUnionS);
            return false;
          }
        
          $data = $main->PRD->get($sender->namedtag->RangersUnionS->getValue());
          if($main->getServer()->getPlayer($data["队长"]) !== null){
            $main->getServer()->getPlayer($data["队长"])->sendMessage("§d=====玩家战队系统=====\n§c玩家 §6{$senderName} §c已拒绝你提出的战队联盟申请!");
          }
          
          $sender->sendMessage("§d=====玩家战队系统=====\n§c成功推辞了 §6{$sender->namedtag->RangersUnionS->getValue()} §c战队队长 §6{$data["队长"]} §c提出的战队联盟申请!");
          unset($sender->namedtag->RangersUnionS);
          return true;
        }
        else{
          $sender->sendMessage("§d=====玩家战队系统=====\n§c你还没收到任何请求!");
          return false;
        }
        break;
        
        case "捐赠":
          if(!$sender instanceof Player){
            $sender->sendMessage("§d=====玩家战队系统=====\n§c请在游戏中执行该指令!");
            return false;
          }
        
          if(!isset($args[1])){
            $sender->sendMessage("§d=====玩家战队系统=====\n§c正确用法: §6/战队 捐赠 <数额>");
            return false;
          }
          
          if(!is_numeric($args[1])){
            $sender->sendMessage("§d=====玩家战队系统=====\n§c请使用数字填写指令中的数额!");
            return false;
          }
            
          if(!$main->RPD->exists($senderName)){
            $sender->sendMessage("§d=====玩家战队系统=====\n§c处理失败, 原因: §6你还未加入战队");
            return false;
          }
            
          if(EconomyAPI::getInstance()->myMoney($sender) >= $args[1]){
            $rangers = $main->RPD->get($senderName);
            EconomyAPI::getInstance()->reduceMoney($senderName, $args[1]);
            $data = $main->PRD->get($rangers);
            $main->PRD->set($rangers, [
              "队长" => $data["队长"],
              "管理" => $data["管理"],
              "基金" => $data["基金"] + $args[1],
              "基地" => $data["基地"],
              "人数" => $data["人数"],
              "成员" => $data["成员"],
            ]);
            $main->PRD->save();
            
            $sender->sendMessage("§d=====玩家战队系统=====\n§c成功捐赠 §6{$args[1]} §c金币到 §6{$rangers} §c战队的战队基金!");
            return true;
          } 
          else{
            $sender->sendMessage("§d=====玩家战队系统=====\n§c处理失败, 原因: §6余额不足以支付捐赠数额");
            return false;
          }
        break;
        
        case "强制捐赠":
          if(!$sender->isOp()){
            $sender->sendMessage("§d=====玩家战队系统=====\n§c你没有权限执行该指令!");
            return false;
          }
          
          if(!isset($args[1]) OR !isset($args[2])){
            $sender->sendMessage("§d=====玩家战队系统=====\n§c正确用法: §6/战队 强制捐赠 <战队名称> <数额>");
            return false;
          }
          
          if(!is_numeric($args[2])){
            $sender->sendMessage("§d=====玩家战队系统=====\n§c请使用数字填写指令中的数额!");
            return false;
          }
          
          if($main->PRL->exists($args[1])){
            $data = $main->PRD->get($args[1]);
            $main->PRD->set($args[1], [
              "队长" => $data["队长"],
              "管理" => $data["管理"],
              "基金" => $data["基金"] + $args[2],
              "基地" => $data["基地"],
              "人数" => $data["人数"],
              "成员" => $data["成员"],
            ]);
            $main->PRD->save();
            
            $sender->sendMessage("§d=====玩家战队系统=====\n§c成功强制捐赠 §6{$args[2]} §c金币到 §6{$args[1]} §c战队的战队基金!");
            return true;
          }
          else{
            $sender->sendMessage("§d=====玩家战队系统=====\n§c处理失败, 原因: §6找不到 {$args[1]} 战队");
            return false;
          }
        break;
        
        case "添加管理":
          if(!$sender instanceof Player){
            $sender->sendMessage("§d=====玩家战队系统=====\n§c请在游戏中执行该指令!");
            return false;
          }
        
          if(!isset($args[1])){
            $sender->sendMessage("§d=====玩家战队系统=====\n§c正确用法: §6/战队 添加管理 <玩家名称>");
            return false;
          }
          
          if(!$main->RCD->exists($senderName)){//如果玩家没有创建过战队
            $sender->sendMessage("§d=====玩家战队系统=====\n§c处理失败, 原因: §6你还未创建战队");
            return false;
          }
            
          if($args[1] == $senderName){
            $sender->sendMessage("§d=====玩家战队系统=====\n§c添加失败, 原因: §6你无法将自己添加为战队管理员");
            return false;
          }
          
          $rangers = $main->RCD->get($senderName);
          
          if(in_array($args[1], $main->PRD->get($rangers)["管理"])){
            $sender->sendMessage("§d=====玩家战队系统=====\n§c添加失败, 原因: 玩家 {$args[1]} 已经是战队管理员");
            return false;
          }
          
          if(in_array($args[1],$main->PRD->get($rangers)["成员"])){
            $data = $main->PRD->get($rangers);
            $managers = $data["管理"];
            $managers[] = $args[1];
            
            $main->PRD->set($rangers, [
              "队长" => $data["队长"],
              "管理" => $managers,
              "基金" => $data["基金"],
              "基地" => $data["基地"],
              "人数" => $data["人数"],
              "成员" => $data["成员"],
            ]);
            $main->PRD->save();
            
            $main->RMD->set($args[1], $rangers);
            $main->RMD->save();
            
            $sender->sendMessage("§d=====玩家战队系统=====\n§c成功将玩家 §6{$args[1]} §c添加为 §6{$rangers} §c战队管理员!");
            return true;
          }
          else{
            $sender->sendMessage("§d=====玩家战队系统=====\n§c添加失败, 原因: §6玩家 {$args[1]} 还不是 {$rangers} 战队的成员");
            return false;
          }
        break;
        
        case "移除管理":
          if(!$sender instanceof Player){
            $sender->sendMessage("§d=====玩家战队系统=====\n§c请在游戏中执行该指令!");
            return false;
          }
        
          if(!isset($args[1])){
            $sender->sendMessage("§d=====玩家战队系统=====\n§c正确用法: §6/战队 移除管理 <玩家名称>");
            return false;
          }
          
          if(!$main->RCD->exists($senderName)){//如果玩家没有创建过战队
            $sender->sendMessage("§d=====玩家战队系统=====\n§c处理失败, 原因: §6你还未创建战队");
            return false;
          }
            
          $rangers = $main->RCD->get($senderName);
          $data = $main->PRD->get($rangers);
          
          if(in_array($args[1], $data["管理"])){
            $managers = $data["管理"];
            foreach($managers as $key=>$name){
              if($name==$args[1]){
                unset($managers[$key]);
              }
            }
            $main->PRD->set($rangers, [
              "队长" => $data["队长"],
              "管理" => $managers,
              "基金" => $data["基金"],
              "基地" => $data["基地"],
              "人数" => $data["人数"],
              "成员" => $data["成员"],
            ]);
            $main->PRD->save();
            
            $main->RMD->remove($args[1]);
            $main->RMD->save();
            
            $sender->sendMessage("§d=====玩家战队系统=====\n§c成功移除玩家 §6{$args[1]} §c的 §6{$rangers} §c战队管理员身份!");
            return true;
          }
          else{
            $sender->sendMessage("§d=====玩家战队系统=====\n§c移除失败, 原因: §6玩家 {$args[1]} 还不是 {$rangers} 战队管理员");
            return false;
          }
        break;
        
        case "升级":
          if(!$sender instanceof Player){
            $sender->sendMessage("§d=====玩家战队系统=====\n§c请在游戏中执行该指令!");
            return false;
          }
        
          if(!$main->RCD->exists($senderName)){
            $sender->sendMessage("§d=====玩家战队系统=====\n§c处理失败, 原因: §6你还未创建战队");
            return false;
          }
          
          $rangers = $main->RCD->get($senderName);
          $data = $main->PRD->get($rangers);
          $money = $main->PRS->get("功能设置")["战队升级费用"];
          $count = $main->PRS->get("功能设置")["战队升级人数"];
          $newCount = $data["人数"] + $count;
          
          if($data["基金"] >= $money){
            $main->PRD->set($rangers, [
              "队长" => $data["队长"],
              "管理" => $data["管理"],
              "基金" => $data["基金"] - $money,
              "基地" => $data["基地"],
              "人数" => $newCount,
              "成员" => $data["成员"],
            ]);
            $main->PRD->save();
            
            $sender->sendMessage("§d=====玩家战队系统=====\n§c成功花费 §6{$money} §c战队基金进行战队升级, 现战队人数上限为 §6{$newCount} §c人!");
            return true;
          }
          else{
            $sender->sendMessage("§d=====玩家战队系统=====\n§c升级失败, 原因: §6战队基金不足以支付战队升级费用§c, 战队升级费用: §6{$moneys}");
            return false;
          }
        break;
        
        case "强制升级":
          if(!$sender->isOp()){
            $sender->sendMessage("§d=====玩家战队系统=====\n§c你没有权限执行该指令!");
            return false;
          }
        
          if(!isset($args[1])){
            $sender->sendMessage("§d=====玩家战队系统=====\n§c正确用法: §6/战队 强制升级 <战队名称>");
            return false;
          }
          
          if($main->PRL->exists($args[1])){
            $data = $main->PRD->get($args[1]);
            $count = $main->PRS->get("功能设置")["战队升级人数"];
            $newCount = $data["人数"] + $count;
            
            $main->PRD->set($args[1],[
              "队长" => $data["队长"],
              "管理" => $data["管理"],
              "基金" => $data["基金"],
              "基地" => $data["基地"],
              "人数" => $newCount,
              "成员" => $data["成员"],
            ]);
            $main->PRD->save();
            
            $sender->sendMessage("§d=====玩家战队系统=====\n§c成功强制升级 §6{$args[1]} §c战队, 现 §6{$args[1]} §c战队人数上限为 §6{$newCount} §c人!");
            return true;
          }
          else{
            $sender->sendMessage("§d=====玩家战队系统=====\n§c处理失败, 原因: §6找不到 {$args[1]} 战队");
            return false;
          }
        break;
        
        case "联盟":
          if(!$sender instanceof Player){
            $sender->sendMessage("§d=====玩家战队系统=====\n§c请在游戏中执行该指令!");
            return false;
          }
        
          if(!$main->RCD->exists($senderName)){
            $sender->sendMessage("§d=====玩家战队系统=====\n§c处理失败, 原因: §6你还未创建战队");
            return false;
          }
          
          if(!isset($args[1])){
            $sender->sendMessage("§d=====玩家战队系统=====\n§c正确用法: §6/战队 联盟 <战队名称>");
            return false;
          }
          
          if(!$main->PRL->exists($args[1])){
            $sender->sendMessage("§d=====玩家战队系统=====\n§c处理失败, 原因: §6找不到 {$args[1]} 战队");
            return false;
          }
            
          $data = $main->PRD->get($args[1]);
          
          if($main->getServer()->getPlayer($data["队长"]) == null){
            $sender->sendMessage("§d=====玩家战队系统=====\n§c申请失败, 原因: §6{$args[1]} 战队队长 {$data["队长"]} 不在线");
            return false;
          }
          else{
            $rangers = $main->RCD->get($senderName);
            $player = $main->getServer()->getPlayer($data["队长"]);
            
            $player->namedtag->RangersUnionS = new StringTag("RangersUnionS", $rangers);
            $player->sendMessage("§d=====玩家战队系统=====\n§6{$rangers} §c战队队长 §6{$senderName} §c正在向你申请战队联盟, 联盟后, 你的战队将会合并到 §6{$rangers} §c战队, 并且 §6{$senderName} §c将会是战队队长, 你将会成为该战队的管理员.\n请输入指令 §6/战队 接受 §c来同意该申请, 或输入指令 §6/战队 推辞 §c来推辞该申请.");
            $sender->sendMessage("§d=====玩家战队系统=====\n§c成功向 §6{$args[1]} §c战队队长 §6{$data["队长"]} §c发出战队联盟申请, 请耐心等待回复!");
            return true;
          }
        break;
        
        default:
           $sender->sendMessage("§d=====玩家战队系统=====\n§c未知指令, 请输入指令 §6/战队 帮助 <页面> §c查看指令帮助.");
           return false;
        break;
      }

    }
  }
}