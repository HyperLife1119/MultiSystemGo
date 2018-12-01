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
namespace multisystemgo\event\player\method;

use pocketmine\entity\Skin;
use multisystemgo\data\CapeData;

class PrivilegeMode{
  public static function privilegeMode($player, $title, $main){
    if($main->PPS->get($title)["特权披风"] == "开"){
      $capeData = CapeData::$capeData;
      $randomCape = base64_decode($capeData[mt_rand(0, count($capeData) - 1)]);
      $skin = new Skin($player->getSkin()->getSkinId(), $player->getSkin()->getSkinData(), $randomCape, $player->getSkin()->getGeometryName(), $player->getSkin()->getGeometryData());
      $player->setSkin($skin);
      $player->sendSkin(null);
    }
    if($main->PPS->get("功能设置")["附加血量"] == "开"){
      $player->setMaxHealth($player->getMaxHealth() + 20 * $main->PPS->get($title)["附加血条"]);
      $player->setHealth($player->getMaxHealth() + 20 * $main->PPS->get($title)["附加血条"]);
    }
  }
}