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

use pocketmine\nbt\tag\StringTag;
use pocketmine\entity\Skin;

class InvertedBody{
  public static function invertedBody($player, $title, $main){
    if($main->PPS->get($title)["倒置身体"] !== "开"){
      $player->sendMessage("§b=====特权玩家系统=====\n§6你还未拥有该特权权限!");
      return false;
    }
    
    if(isset($player->namedtag->InvertedBody)){
      $skin = new Skin($player->getSkin()->getSkinId(), $player->getSkin()->getSkinData(), $player->getSkin()->getCapeData(), $player->namedtag->InvertedBody->getValue(), $player->getSkin()->getGeometryData());
      $player->setSkin($skin);
      $player->sendSkin(null);
      unset($player->namedtag->InvertedBody);
      
      $player->sendMessage("§b=====特权玩家系统=====\n§6成功将你的身体恢复原状!");
      return true;
    }
    else{
      $player->namedtag->InvertedBody = new StringTag("InvertedBody", $player->getSkin()->getGeometryName());
      $skin = new Skin($player->getSkin()->getSkinId(), $player->getSkin()->getSkinData(), $player->getSkin()->getCapeData(), "geometry.3rdBirthday.Nathan", $player->getSkin()->getGeometryData());
      $player->setSkin($skin);
      $player->sendSkin(null);
      
      $player->sendMessage("§b=====特权玩家系统=====\n§6成功将你的身体反转倒置!(§e使用3D皮肤可能会出现皮肤显示错误§6) 再次输入该指令即可恢复原状.");
      return true;
    }
  }
  
}