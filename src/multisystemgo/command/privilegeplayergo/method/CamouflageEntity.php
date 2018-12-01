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
use pocketmine\network\mcpe\protocol\AddEntityPacket;
use pocketmine\nbt\tag\IntTag;
use multisystemgo\event\player\method\RemoveCamouflageEntity;
use multisystemgo\data\EntityIdData;

class CamouflageEntity{
  public static function camouflageEntity($player, $eid, $title, $main){
    if($main->PPS->get($title)["伪装实体"] !== "开"){
      $sender->sendMessage("§b=====特权玩家系统=====\n§6你还未拥有该特权权限!");
      return false;
    }
  
    if(!is_numeric($eid)){
      $player->sendMessage("§b=====特权玩家系统=====\n§6处理失败, 原因: §e指令中的 <ID> 必须填写数字");
      return false;
    }
  
    if(!in_array(intval($eid), EntityIdData::$entityIdList)){
      $player->sendMessage("§b=====特权玩家系统=====\n§6无法找到此实体, 请输入正确的实体ID!\n§6实体ID列表:\n §6实体名称: §e实体ID");
      foreach(EntityIdData::$entityIdInfo as $name => $id){
        $player->sendMessage(" §6{$name}: §e{$id}");
      }
      return false;
    }
                    
    if($eid == "0"){
      RemoveCamouflageEntity::removeCamouflageEntity($player, $main);
      $player->sendMessage("§b=====特权玩家系统=====\n§6伪装解除, 回归本体!");
      return true;
    }
    else{
      //移除发包实体
      RemoveCamouflageEntity::removeCamouflageEntity($player, $main);
      
      $pk = new AddEntityPacket();
      $pk->entityRuntimeId = Entity::$entityCount++;
      $pk->type = intval($eid);
      $pk->position = $player->asVector3();
      $pk->motion = $player->getMotion();
      //修复末影龙的身体方向与玩家身体方向相反问题
      ($eid == "53") ? $pk->yaw = $player->getYaw() - 180 : $pk->yaw = $player->getYaw();
      $pk->pitch = $player->getPitch();
      $main->getServer()->broadcastPacket($main->getServer()->getOnlinePlayers(), $pk);
      
      $player->setDataFlag(Entity::DATA_FLAGS, Entity::DATA_FLAG_INVISIBLE, true);
      $player->namedtag->CamouflageEntity = new IntTag("CamouflageEntity", $pk->entityRuntimeId);
      $player->namedtag->CamouflageEntityId = new IntTag("CamouflageEntityId", intval($eid));
      
      $player->sendMessage("§b=====特权玩家系统=====\n§6成功伪装成ID为 §e".intval($eid)." §6的 §e".array_flip(EntityIdData::$entityIdInfo)[$eid]." §6实体!\n§6你得小心, 手持物品, 疾跑粒子, 飞行都可能会暴露你的伪装.");
      return true;
    }
  }
  
}