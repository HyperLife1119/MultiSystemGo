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
use pocketmine\entity\Entity;
use pocketmine\block\Block;
use pocketmine\level\Level;
use pocketmine\math\Vector3;
use pocketmine\plugin\PluginBase;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\network\mcpe\protocol\DataPacket;
use pocketmine\network\mcpe\protocol\MoveEntityPacket;
use pocketmine\network\mcpe\protocol\SetEntityMotionPacket;

class PlayerMove{
  public static function onPlayerMove($event, PluginBase $main){
    $player = $event->getPlayer();
    $playerName = $player->getName();
    $level = $player->getLevel();
    
    $x = $player->getX();
    $y = $player->getY();
    $z = $player->getZ();
    $blockPos = intval($x).":".intval($y).":".intval($z).":".$level->getFolderName();
    
    //伪装方块
    if(isset($player->namedtag->CamouflageBlockData) AND !$event->getFrom()->equals($event->getTo())){
      if($player->getGamemode() !== 3){
        $player->setGamemode(3);
      }
      $player->setDataFlag(Entity::DATA_FLAGS, Entity::DATA_FLAG_INVISIBLE,true);
      
      $block = explode(":", $player->namedtag->CamouflageBlockData->getValue());
      
      //如果已经存有玩家的方块坐标数据
      if(isset($main->BlockPos[$playerName])){
        foreach($main->BlockPos[$playerName] as $key => $pos){
          $oldBlockPos = explode(":", $pos);
          //清除方块
          if($level->getBlockIdAt($oldBlockPos[0], $oldBlockPos[1], $oldBlockPos[2]) !== 0){
            $level->setBlockIdAt($oldBlockPos[0], $oldBlockPos[1], $oldBlockPos[2], 0);
          }
          unset($main->BlockPos[$playerName][$key]);
        }
      }
      //如果位置有空位
      if($level->getBlockIdAt($x, $y, $z) == 0){
        $level->setBlock(new Vector3($x, $y, $z), new Block($block[0], $block[1]));
        $main->BlockPos[$playerName][] = $blockPos;//记录坐标
      }
    }
    
    //检测传送中断
    if(isset($player->namedtag->TransferFailure)){
      $player->teleport($level->getSafeSpawn());
      unset($player->namedtag->TransferFailure);
      //防止传送拉回隐身出错
      if(isset($player->namedtag->CamouflageBlockData)){
        $player->setDataFlag(Entity::DATA_FLAGS, Entity::DATA_FLAG_INVISIBLE, true);
      }
      $player->sendMessage("§a=====智能保护系统=====\n§e检测到传送被中断, 已将你拉回该世界的出生点!\n§e若出现NPC消失等异常情况, 请重新进入本世界即可刷新!");
    }
    
    //伪装生物移动
    if(isset($player->namedtag->CamouflageEntity)){
      $mov = new MoveEntityPacket();
      $mov->entityRuntimeId = $player->namedtag->CamouflageEntity->getValue();
      $mov->position = $player->asVector3();
      //修复末影龙的身体方向与玩家身体方向相反问题
      ($player->namedtag->CamouflageEntityId->getValue() == 53) ? $mov->yaw = $player->getYaw() - 180 : $mov->yaw = $player->getYaw();
      $mov->headYaw = $player->getYaw();
      $mov->pitch = $player->getPitch();
      
      $mot = new SetEntityMotionPacket();
      $mot->entityRuntimeId = $player->namedtag->CamouflageEntity->getValue();
      $mot->motion = $player->getMotion();
      
      $main->getServer()->broadcastPacket($main->getServer()->getOnlinePlayers(), $mov);
      $main->getServer()->broadcastPacket($main->getServer()->getOnlinePlayers(), $mot);
      
      $player->setDataFlag(Entity::DATA_FLAGS, Entity::DATA_FLAG_INVISIBLE, true);
    }
  }
}