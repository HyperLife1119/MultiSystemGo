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

use pocketmine\entity\Entity;
use pocketmine\entity\Skin;

class RemovePrivilegePlayer{
  public static function removePrivilegePlayer($playerName, $config, $main){
    $PrivilegeList = $config->get("玩家列表");
    $inv = array_search($playerName, $PrivilegeList);
    $inv = array_splice($PrivilegeList, $inv, 1);
    
    $config->set("玩家列表", $PrivilegeList);
    $config->save();
    
    //移除特权时间数据
    $main->PPT->remove($playerName);
    $main->PPT->save();
    
    //移除闪电冷却时间数据
    if($main->LCD->exists($playerName)){
      $main->LCD->remove($playerName);
      $main->LCD->save();
    }
    
    //移除时间冷却时间数据
    if($main->TCD->exists($playerName)){
      $main->TCD->remove($playerName);
      $main->TCD->save();
    }
    
    //移除烟花冷却时间数据
    if($main->FCD->exists($playerName)){
      $main->FCD->remove($playerName);
      $main->FCD->save();
    }
    
    //如果这个玩家在线
    if($main->getServer()->getPlayer($playerName) !== null){
      $player = $main->getServer()->getPlayer($playerName);
      if($player->getGamemode() !== 0){
        $player->setGamemode(0);
      }
      
      if($player->getAllowFlight()){
        $player->setAllowFlight(false);
      }
      
      if($main->PPS->get("功能设置")["附加血量"] == "开"){
        $health = $main->PPS->get("普通玩家")["附加血条"];
        $player->setMaxHealth($player->getMaxHealth() + 20 * $health);
        $player->setHealth($player->getMaxHealth() + 20 * $health);
      }
      
      if($main->PPS->get($title)["特权披风"] == "开"){
        $skin = new Skin($player->getSkin()->getSkinId(), $player->getSkin()->getSkinData(), "", $player->getSkin()->getGeometryName(), $player->getSkin()->getGeometryData());
        $player->setSkin($skin);
        $player->sendSkin(null);
      }
      
      $player->setDataFlag(Entity::DATA_FLAGS, Entity::DATA_FLAG_INVISIBLE, false);
      $player->removeEffect(14);
    }
  }
  
}