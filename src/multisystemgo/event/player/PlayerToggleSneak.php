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
use pocketmine\math\Vector3;
use pocketmine\plugin\PluginBase;
use pocketmine\event\player\PlayerToggleSneakEvent;
use pocketmine\network\mcpe\protocol\SetEntityLinkPacket;
use pocketmine\level\Level;
use pocketmine\level\Position;
use pocketmine\level\particle\EntityFlameParticle;
use pocketmine\level\particle\LavaDripParticle;
use pocketmine\level\sound\BlazeShootSound;
use pocketmine\level\sound\ExplodeSound;
use pocketmine\level\sound\PopSound;
use multisystemgo\event\player\method\CancelRide;
use multisystemgo\event\player\method\Stealth;

class PlayerToggleSneak{
  public static function onPlayerToggleSneak($event, PluginBase $main){
    $player = $event->getPlayer();
    $playerName = $player->getName();
    
    $level = $player->getLevel();
    
    $a = $main->A->get("玩家列表");
    $b = $main->B->get("玩家列表");
    $c = $main->C->get("玩家列表");
    
    //潜行隐身
    if(in_array($playerName, $a)){
      Stealth::stealth($player, $level, "顶级特权", $main);
    }
    if(in_array($playerName, $b)){
      Stealth::stealth($player, $level, "高级特权", $main);
    }
    if(in_array($playerName, $c)){
      Stealth::stealth($player, $level, "普通特权", $main);
    }
    
    //如果玩家被骑着
    CancelRide::cancelRide($player, $main);
    
    //点燃烟花确认
    if(isset($player->namedtag->PrivilegeFire)){
      unset($player->namedtag->PrivilegeFire);
      
      if(!$main->PPT->exists($playerName)){
        return;
      }
        
      $x = intval($player->getX());
      $y = intval($player->getY());
      $z = intval($player->getZ());
      
      $level->addSound(new BlazeShootSound(new Vector3($x, $y, $z)));
      //y轴提升动画效果
      for ($i = 0; $i < 20; $i++){
        $level->addParticle(new LavaDripParticle(new Vector3($x, $y + $i, $z)));
        for ($i1 = 0; $i1 <= 180; $i1 += 20){
          for ($i2 = 0;$i2 < 360;$i2 += 20){
            $level->addParticle(new EntityFlameParticle(new Vector3($x + 7 * sin(deg2rad($i1)) * cos(deg2rad($i2)), $y + 25 + 7 * sin(deg2rad($i1)) * sin(deg2rad($i2)), $z + (7 * cos(deg2rad($i1))), 245, 110, 0)));
          }
        }
      }
      $level->addSound(new ExplodeSound(new Vector3($x, $y, $z)));
      
      if(in_array($playerName, $a)){
        $main->FCD->set($playerName,time() + $main->PPS->get("顶级特权")["烟花冷却"]);
        $main->FCD->save();
      }
      if(in_array($playerName, $b)){
        $main->FCD->set($playerName,time() + $main->PPS->get("高级特权")["烟花冷却"]);
        $main->FCD->save();
      }
      if(in_array($playerName, $c)){
        $main->FCD->set($playerName,time() + $main->PPS->get("普通特权")["烟花冷却"]);
        $main->FCD->save();
      }
      
      $player->sendMessage("§b=====特权玩家系统=====\n§6你成功燃放了一枚特权烟花!");
    }
  }
  
}