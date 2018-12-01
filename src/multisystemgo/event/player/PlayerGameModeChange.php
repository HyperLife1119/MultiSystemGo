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
use pocketmine\plugin\PluginBase;
use pocketmine\item\Item;
use pocketmine\event\player\PlayerGameModeChangeEvent;

class PlayerGameModeChange{
  public static function onPlayerGameModeChange($event, PluginBase $main){
    $player = $event->getPlayer();
    $playerName = $player->getName();
    $mode = $player->getGamemode();
    
    $type = "无物品";
    
    if($main->SPS->get("功能设置")["背包保存"] == "开" AND !$event->isCancelled()){
      if($mode == 0){
        $ids = [];
        $damages = [];
        $counts = [];
        $nbts = [];
        
        foreach($player->getInventory()->getContents() as $item){
          //判断格内的物品是否为空气
          if($item->getId() !== 0){
            $type = "有物品";
            
            $id = $item->getId();
            $damage = $item->getDamage();
            $count = $item->getCount();
            $nbt = base64_encode($item->getCompoundTag());//使用base64加密nbt数据, 防止染色物品报错导致yml清空
              
            $ids[] = $id;
            $damages[] = $damage;
            $counts[] = $count;
            $nbts[] = $nbt;
          }
        }
        
        //储存背包数据到配置文件
        if($type == "有物品"){
          if($main->PBD->exists($playerName)){
            $oldIds = $main->PBD->get($playerName)["id"];
            $oldDamages = $main->PBD->get($playerName)["damage"];
            $oldCounts = $main->PBD->get($playerName)["count"];
            $oldNbts = $main->PBD->get($playerName)["nbt"];
              
            //将旧数据和新数据整合到一起
            $newIds = array_merge($oldIds, $ids);
            $newDamages = array_merge($oldDamages, $damages);
            $newCounts = array_merge($oldCounts, $counts);
            $newNbts= array_merge($oldNbts, $nbts);
              
            $main->PBD->set($playerName,["id" => $newIds, "damage" => $newDamages, "count" => $newCounts, "nbt" => $newNbts]);
            $main->PBD->save();
            $player->sendMessage("§a=====智能保护系统=====\n§e你在生存模式下的背包数据已保存, 请在生存模式下输入指令 §f/智保 背包 §e来取回你的背包.");
          }
          else{
            $main->PBD->set($playerName,["id" => $ids, "damage" => $damages, "count" => $counts, "nbt" => $nbts]);
            $main->PBD->save();
            $player->sendMessage("§a=====智能保护系统=====\n§e你在生存模式下的背包数据已保存, 请在生存模式下输入指令 §f/智保 背包 §e来取回你的背包.");
          }
        }
      }
      else{
        $player->sendMessage("§a=====智能保护系统=====\n§e你可以在生存模式下输入指令 §f/智保 背包 §e来取回你的背包.");
      }
      //清空玩家的背包
      $player->getInventory()->ClearAll();
    }
  }
}