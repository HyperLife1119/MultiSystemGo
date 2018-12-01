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
namespace multisystemgo\event\entity;

use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\level\Level;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\RemoveEntityPacket;
use pocketmine\nbt\tag\StringTag;
use pocketmine\entity\Entity;
use pocketmine\event\entity\EntityLevelChangeEvent;
use multisystemgo\event\entity\method\JoinPrivilegeWorld;
use multisystemgo\event\player\method\CancelRide;
use multisystemgo\event\player\method\RemoveCamouflageEntity;

class EntityLevelChange{
  public static function onEntityLevelChange($event, PluginBase $main){
    $entity = $event->getEntity();
    
    $levelName = $event->getTarget()->getFolderName();
    
    $A = $main->PPW->get("顶级特权世界");
    $B = $main->PPW->get("高级特权世界");
    $C = $main->PPW->get("普通特权世界");
    
    $a = $main->A->get("玩家列表");
    $b = $main->B->get("玩家列表");
    $c = $main->C->get("玩家列表");
    
    if($entity instanceof Player){
      $playerName=$entity->getName();
      
      //如果世界有配置选项
      if(array_key_exists($levelName, $main->SPS->get("世界设置"))){
        $worldInfo = $main->SPS->get("世界设置")[$levelName];
        if(count($event->getTarget()->getPlayers()) == $worldInfo["世界人数上限"]){
          $event->setCancelled(true);
          $entity->namedtag->TransferFailure = new StringTag("TransferFailure","未出生");
          $entity->sendMessage("§a=====智能保护系统=====\n§e暂时无法进入 §f{$levelName} §e世界, 原因: §f该世界人数已满");
          return;
        }
        if(!in_array($playerName, $worldInfo["白名单"])){
          if(in_array($worldInfo["世界游戏模式"], [0,1,2,3])){
            $entity->setGamemode($worldInfo["世界游戏模式"]);
          }
          if($worldInfo["关闭玩家飞行"] == "开"){
            if($entity->isSneaking() AND !$entity->isFlying() AND $entity->isOnGround()){
              $floorV3 = $main->findfloor($entity->getLevel(), new Vector3($entity->getFloorX(), $entity->getY(), $entity->getFloorZ()));
              $entity->setAllowFlight(false);
              $entity->teleport($floorV3);
              $entity->setMotion(new Vector3(0,-1.5,0));
            }
            else{
              $event->setCancelled(true);
              $entity->namedtag->TransferFailure = new StringTag("TransferFailure", "未出生");
              $entity->sendMessage("§a=====智能保护系统=====\n§e为了防止飞行作弊, 你只能在潜行模式下进入 §f{$levelName} §e世界!");
            }
          }
        }
      }
      
      //特权专属世界
      JoinPrivilegeWorld::joinPrivilegeWorld($entity, $A, $a, $levelName, "顶级");
      JoinPrivilegeWorld::joinPrivilegeWorld($entity, $B, $b, $levelName, "高级");
      JoinPrivilegeWorld::joinPrivilegeWorld($entity, $C, $c, $levelName, "普通");
      
      //如果玩家处于伪装生物状态
      RemoveCamouflageEntity::removeCamouflageEntity($entity, $main);
      
      //伪装方块禁止传送
      if(isset($entity->namedtag->BlockIdS)){
        $event->setCancelled(true);
        $entity->namedtag->TransferFailure = new StringTag("TransferFailure","未出生");
        $entity->sendMessage("§a=====智能保护系统=====\n§e伪装方块下无法进行世界传送!");
      }
      
      //如果玩家正在骑人则取消骑人 或者 如果玩家被骑着则取消被骑
      CancelRide::cancelRide($entity, $main);
    }
  }
  
}