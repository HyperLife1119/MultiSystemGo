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
use pocketmine\block\BlockFactory;
use multisystemgo\event\player\method\RemoveCamouflageBlock;

class CamouflageBlock{
  public static function camouflageBlock($player, $blockData, $main){
    if($blockData == "0"){
      RemoveCamouflageBlock::removeCamouflageBlock($player, $player->getLevel(), $main);
      $player->sendMessage("§b=====特权玩家系统=====\n§6伪装解除, 回归本体!");
      return true;
    }
    else{
      if(!strpos($blockData, ":")){
        $player->sendMessage("§b=====特权玩家系统=====\n§6正确用法:\n§6用法一: §e/特权 伪装方块 <方块ID:特殊值>\n§6用法二: §e输入 /特权 伪装方块 后点击方块并伪装成该方块\n§6用法三: §e输入 /特权 伪装方块 0 解除伪装, 回归本体");
        return false;
      }
      
      $block = explode(":", $blockData);
      
      if(!is_numeric($block[0]) OR !is_numeric($block[1])){
        $player->sendMessage("§b=====特权玩家系统=====\n§6处理失败, 原因: §e指令中的 <方块ID> 与 <特殊值> 必须填写数字");
        return false;
      }
      
      if(BlockFactory::isRegistered($block[0]) AND $block[1] >= 0 AND $block[1] < 0xf){
        $player->namedtag->CamouflageBlockData = new StringTag("CamouflageBlockData", $blockData);
        $player->sendMessage("§b=====特权玩家系统=====\n§6成功伪装成ID特殊值为 §e{$block[0]}:{$block[1]} §6的方块!\n§6你得小心, 手持物品, 疾跑粒子都可能会暴露你的伪装.\n§6可输入指令 §e/特权 伪装方块 0 §6进行解除伪装.");
        return true;
      }
      else{
        $player->sendMessage("§b=====特权玩家系统=====\n§6处理失败, 原因: §e方块ID 或 特殊值 无效");
        return false;
      }
    }
  }
}