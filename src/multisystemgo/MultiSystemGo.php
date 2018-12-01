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
namespace multisystemgo;

use pocketmine\Server;

use pocketmine\Player;

use pocketmine\plugin\PluginBase;

use pocketmine\utils\Config;

use pocketmine\tile\Sign;

use pocketmine\math\Vector3;

use pocketmine\item\Item;

use pocketmine\level\Level;

use pocketmine\scheduler\PluginTask;
use pocketmine\scheduler\CallbackTask;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\player\PlayerKickEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\player\PlayerGameModeChangeEvent;
use pocketmine\event\player\PlayerDropItemEvent;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerCommandPreprocessEvent;
use pocketmine\event\player\PlayerToggleSneakEvent;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerRespawnEvent;
use pocketmine\event\entity\EntityLevelChangeEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\block\BlockUpdateEvent;
use pocketmine\event\block\ItemFrameDropItemEvent;
use pocketmine\event\block\SignChangeEvent;
use pocketmine\event\server\DataPacketReceiveEvent;

use pocketmine\command\Command;
use pocketmine\command\CommandExecutor;
use pocketmine\command\CommandSender;

use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\FloatTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\DoubleTag;
use pocketmine\nbt\tag\CompoundTag;

use pocketmine\entity\Entity;
use pocketmine\entity\Skin;
use pocketmine\entity\object\Lightning;

use multisystemgo\event\player\PlayerJoin;
use multisystemgo\event\player\PlayerQuit;
use multisystemgo\event\player\PlayerKick;
use multisystemgo\event\player\PlayerMove;
use multisystemgo\event\player\PlayerDeath;
use multisystemgo\event\player\PlayerGameModeChange;
use multisystemgo\event\player\PlayerInteract;
use multisystemgo\event\player\PlayerDropItem;
use multisystemgo\event\player\PlayerCommandPreprocess;
use multisystemgo\event\player\PlayerToggleSneak;
use multisystemgo\event\player\PlayerChat;
use multisystemgo\event\player\PlayerRespawn;
use multisystemgo\event\block\BlockBreak;
use multisystemgo\event\block\BlockPlace;
use multisystemgo\event\block\BlockUpdate;
use multisystemgo\event\block\ItemFrameDropItem;
use multisystemgo\event\block\SignChange;
use multisystemgo\event\server\DataPacketReceive;
use multisystemgo\event\entity\EntityLevelChange;
use multisystemgo\event\entity\EntityDamage;
use multisystemgo\command\privilegeplayergo\PrivilegePlayerGo;
use multisystemgo\command\smartprotectiongo\SmartProtectionGo;
use multisystemgo\command\prefixgo\PrefixGo;
use multisystemgo\command\luvgo\LuvGo;
use multisystemgo\command\rangersgo\RangersGo;
use multisystemgo\data\ConfigData;
use multisystemgo\data\CapeData;

use onebone\economyapi\EconomyAPI;

class MultiSystemGo extends PluginBase implements Listener{
  private static $obj = null;
  public $BlockPos = [];
  
  public function onLoad(){
    $this->time = microtime(true);
    
    $this->getLogger()->notice("\n\n  §b[Go!] MultiSystemGo 正在驱动各系统启动...\n  §b[Go!] PrivilegePlayerGo 特权玩家系统正在启动...\n  §b[Go!] SmartProtectionGo 智能保护系统正在启动...\n  §b[Go!] PrefixGo 玩家头衔系统正在启动...\n  §b[Go!] LuvGo 玩家结婚系统正在启动...\n  §b[Go!] RangersGo 玩家战队系统正在启动...\n  §b[Go!] MultiSystemGo 正在读取相关配置文件...\n");
  }
  
  public function onEnable(){
    
    
    if(!self::$obj instanceof MultiSystemGo){
      self::$obj = $this;
    }
    
    $this->getServer()->getPluginManager()->registerEvents($this, $this);
    
    //检测经济核心
    if($this->getServer()->getPluginManager()->getPlugin("EconomyAPI") == null){
      $this->getLogger()->warning("\n\n§e  [Go!] MultiSystemGo 启动失败!\n  §e原因: 服务器没有安装EconomyAPI经济核心插件\n");
      $this->getServer()->getPluginManager()->disablePlugin($this);
      return;
    }
    
    //将特权玩家系统的文件归整到一个文件夹
    @mkdir($this->getServer()->getPluginPath()."/MultiSystemGo!/InternalData/PrivilegePlayerGo!", 0777, true);
    
    if(file_exists($this->getServer()->getPluginPath()."/MultiSystemGo!/InternalData/PrivilegeTime.yml")){
      $this->getLogger()->notice("\n\n§b  [Go!] PrivilegePlayerGo 正在进行配置文件热更新...\n");
      copy($this->getServer()->getPluginPath()."/MultiSystemGo!/InternalData/PrivilegeTime.yml", $this->getServer()->getPluginPath()."/MultiSystemGo!/InternalData/PrivilegePlayerGo!/PrivilegeTime.yml");
      unlink($this->getServer()->getPluginPath()."/MultiSystemGo!/InternalData/PrivilegeTime.yml");
    }
    if(file_exists($this->getServer()->getPluginPath()."/MultiSystemGo!/InternalData/FireTime.yml")){
      copy($this->getServer()->getPluginPath()."/MultiSystemGo!/InternalData/FireTime.yml", $this->getServer()->getPluginPath()."/MultiSystemGo!/InternalData/PrivilegePlayerGo!/FireTime.yml");
      unlink($this->getServer()->getPluginPath()."/MultiSystemGo!/InternalData/FireTime.yml");
    }
    if(file_exists($this->getServer()->getPluginPath()."/MultiSystemGo!/InternalData/TimeCmdTime.yml")){
      copy($this->getServer()->getPluginPath()."/MultiSystemGo!/InternalData/TimeCmdTime.yml", $this->getServer()->getPluginPath()."/MultiSystemGo!/InternalData/PrivilegePlayerGo!/TimeCmdTime.yml");
      unlink($this->getServer()->getPluginPath()."/MultiSystemGo!/InternalData/TimeCmdTime.yml");
    }
    if(file_exists($this->getServer()->getPluginPath()."/MultiSystemGo!/InternalData/LightningTime.yml")){
      copy($this->getServer()->getPluginPath()."/MultiSystemGo!/InternalData/LightningTime.yml", $this->getServer()->getPluginPath()."/MultiSystemGo!/InternalData/PrivilegePlayerGo!/LightningTime.yml");
      unlink($this->getServer()->getPluginPath()."/MultiSystemGo!/InternalData/LightningTime.yml");
    }
    if(file_exists($this->getServer()->getPluginPath()."/MultiSystemGo!/InternalData/PPWorld.yml")){
      copy($this->getServer()->getPluginPath()."/MultiSystemGo!/InternalData/PPWorld.yml", $this->getServer()->getPluginPath()."/MultiSystemGo!/InternalData/PrivilegePlayerGo!/PPWorld.yml");
      rename($this->getServer()->getPluginPath()."/MultiSystemGo!/InternalData/PrivilegePlayerGo!/PPWorld.yml", $this->getServer()->getPluginPath()."/MultiSystemGo!/InternalData/PrivilegePlayerGo!/PrivilegeWorld.yml");
      unlink($this->getServer()->getPluginPath()."/MultiSystemGo!/InternalData/PPWorld.yml");
    }
    if(file_exists($this->getServer()->getPluginPath()."/MultiSystemGo!/InternalData/PlayerData/PlayerList-1.yml")){
      copy($this->getServer()->getPluginPath()."/MultiSystemGo!/InternalData/PlayerData/PlayerList-1.yml", $this->getServer()->getPluginPath()."/MultiSystemGo!/InternalData/PrivilegePlayerGo!/PlayerList-1.yml");
      unlink($this->getServer()->getPluginPath()."/MultiSystemGo!/InternalData/PlayerData/PlayerList-1.yml");
    }
    if(file_exists($this->getServer()->getPluginPath()."/MultiSystemGo!/InternalData/PlayerData/PlayerList-2.yml")){
      copy($this->getServer()->getPluginPath()."/MultiSystemGo!/InternalData/PlayerData/PlayerList-2.yml", $this->getServer()->getPluginPath()."/MultiSystemGo!/InternalData/PrivilegePlayerGo!/PlayerList-2.yml");
      unlink($this->getServer()->getPluginPath()."/MultiSystemGo!/InternalData/PlayerData/PlayerList-2.yml");
    }
    if(file_exists($this->getServer()->getPluginPath()."/MultiSystemGo!/InternalData/PrefixGo!/PrefixData.yml")){
      unlink($this->getServer()->getPluginPath()."/MultiSystemGo!/InternalData/PrefixGo!/PrefixData.yml");
    }
    if(file_exists($this->getServer()->getPluginPath()."/MultiSystemGo!/InternalData/PlayerData/PlayerList-3.yml")){
      copy($this->getServer()->getPluginPath()."/MultiSystemGo!/InternalData/PlayerData/PlayerList-3.yml", $this->getServer()->getPluginPath()."/MultiSystemGo!/InternalData/PrivilegePlayerGo!/PlayerList-3.yml");
      unlink($this->getServer()->getPluginPath()."/MultiSystemGo!/InternalData/PlayerData/PlayerList-3.yml");
      rmdir($this->getServer()->getPluginPath()."/MultiSystemGo!/InternalData/PlayerData/");
      $this->getLogger()->notice("\n\n§b  [Go!] PrivilegePlayerGo 配置文件热更新已完成!\n");
    }
    
    //创建配置文件
    @mkdir($this->getServer()->getPluginPath()."/MultiSystemGo!/CdkData/", 0777, true);
    @mkdir($this->getServer()->getPluginPath()."/MultiSystemGo!/InternalData/SmartProtectionGo!", 0777, true);
    @mkdir($this->getServer()->getPluginPath()."/MultiSystemGo!/InternalData/LuvGo!", 0777, true);
    @mkdir($this->getServer()->getPluginPath()."/MultiSystemGo!/InternalData/PrefixGo!", 0777, true);
    @mkdir($this->getServer()->getPluginPath()."/MultiSystemGo!/InternalData/RangersGo!", 0777, true);
    
    //特权玩家列表
    $this->A = new Config($this->getServer()->getPluginPath()."/MultiSystemGo!/InternalData/PrivilegePlayerGo!/PlayerList-1.yml", Config::YAML, ["玩家列表" => []]);
    $this->B = new Config($this->getServer()->getPluginPath()."/MultiSystemGo!/InternalData/PrivilegePlayerGo!/PlayerList-2.yml", Config::YAML, ["玩家列表" => []]);
    $this->C = new Config($this->getServer()->getPluginPath()."/MultiSystemGo!/InternalData/PrivilegePlayerGo!/PlayerList-3.yml", Config::YAML, ["玩家列表" => []]);
    //特权专属世界
    $this->PPW = new Config($this->getServer()->getPluginPath()."/MultiSystemGo!/InternalData/PrivilegePlayerGo!/PrivilegeWorld.yml", Config::YAML, ["顶级特权世界" => [], "高级特权世界" => [], "普通特权世界" => []]);
    //特权时间数据
    $this->PPT = new Config($this->getServer()->getPluginPath()."/MultiSystemGo!/InternalData/PrivilegePlayerGo!/PrivilegeTime.yml", Config::YAML, []);
    //特权烟花冷却
    $this->FCD = new Config($this->getServer()->getPluginPath()."/MultiSystemGo!/InternalData/PrivilegePlayerGo!/FireTime.yml", Config::YAML, []);
    //特权时间冷却
    $this->TCD = new Config($this->getServer()->getPluginPath()."/MultiSystemGo!/InternalData/PrivilegePlayerGo!/TimeCmdTime.yml", Config::YAML, []);
    //特权闪电冷却
    $this->LCD = new Config($this->getServer()->getPluginPath()."/MultiSystemGo!/InternalData/PrivilegePlayerGo!/LightningTime.yml", Config::YAML, []);
    //兑换码数据
    $this->CDK = new Config($this->getServer()->getPluginPath()."/MultiSystemGo!/CdkData/CdkData.yml", Config::YAML, ["提示" => "命令中请使用 {名称} 代替玩家名称"]);
    //玩家结婚数据
    $this->PMD = new Config($this->getServer()->getPluginPath()."/MultiSystemGo!/InternalData/LuvGo!/PlayerData.yml", Config::YAML, []);
    //玩家离婚任务
    $this->PDD = new Config($this->getServer()->getPluginPath()."/MultiSystemGo!/InternalData/LuvGo!/DivorceData.yml", Config::YAML, []);
    //玩家结婚任务
    $this->PLD = new Config($this->getServer()->getPluginPath()."/MultiSystemGo!/InternalData/LuvGo!/LuvData.yml", Config::YAML, []);
    //玩家离婚任务
    $this->PTD = new Config($this->getServer()->getPluginPath()."/MultiSystemGo!/InternalData/LuvGo!/TemporaryData.yml", Config::YAML, []);
    //爱情之家数据
    $this->LHD = new Config($this->getServer()->getPluginPath()."/MultiSystemGo!/InternalData/LuvGo!/LuvHomeData.yml", Config::YAML, []);
    //玩家头衔数据
    $this->PRE = new Config($this->getServer()->getPluginPath()."/MultiSystemGo!/InternalData/PrefixGo!/PlayerPrefixData.yml", Config::YAML, ["自定义头衔商店木牌" => [], "固定义头衔商店木牌" => [], "玩家头衔" => []]);
    //玩家战队列表
    $this->PRL = new Config($this->getServer()->getPluginPath()."/MultiSystemGo!/InternalData/RangersGo!/RangersList.yml", Config::YAML, []);
    //玩家战队数据
    $this->PRD = new Config($this->getServer()->getPluginPath()."/MultiSystemGo!/InternalData/RangersGo!/RangersData.yml", Config::YAML, []);
    //玩家加了什么战队的数据
    $this->RPD = new Config($this->getServer()->getPluginPath()."/MultiSystemGo!/InternalData/RangersGo!/PlayerData.yml", Config::YAML, []);
    //战队队长数据
    $this->RCD = new Config($this->getServer()->getPluginPath()."/MultiSystemGo!/InternalData/RangersGo!/CaptainData.yml", Config::YAML, []);
    //战队管理数据
    $this->RMD = new Config($this->getServer()->getPluginPath()."/MultiSystemGo!/InternalData/RangersGo!/ManagerData.yml", Config::YAML, []);
    //加队临时数据
    $this->PJR = new Config($this->getServer()->getPluginPath()."/MultiSystemGo!/InternalData/RangersGo!/JoinData.yml", Config::YAML, []);
    //宣传木牌数据
    $this->RDS = new Config($this->getServer()->getPluginPath()."/MultiSystemGo!/InternalData/RangersGo!/FrumbeatingSignData.yml", Config::YAML, []);
    //人数木牌数据
    $this->RCS = new Config($this->getServer()->getPluginPath()."/MultiSystemGo!/InternalData/RangersGo!/CountListSignData.yml", Config::YAML, []);
    //基金木牌数据
    $this->RMS = new Config($this->getServer()->getPluginPath()."/MultiSystemGo!/InternalData/RangersGo!/MoneyListSignData.yml", Config::YAML, []);
    //玩家活跃时间
    $this->PAT = new Config($this->getServer()->getPluginPath()."/MultiSystemGo!/InternalData/RangersGo!/PlayerActionTime.yml", Config::YAML, []);
    //玩家背包数据
    $this->PBD = new Config($this->getServer()->getPluginPath()."/MultiSystemGo!/InternalData/SmartProtectionGo!/BackpackData.yml", Config::YAML, []);
    //创造方块数据
    $this->CBD = new Config($this->getServer()->getPluginPath()."/MultiSystemGo!/InternalData/SmartProtectionGo!/CreateBlockData.yml", Config::YAML, ["方块数据" => []]);
    //玩家箱子数据
    $this->PCD = new Config($this->getServer()->getPluginPath()."/MultiSystemGo!/InternalData/SmartProtectionGo!/ChestData.yml", Config::YAML, []);
    //玩家头衔系统
    $this->FIX = new Config($this->getServer()->getPluginPath()."/MultiSystemGo!/玩家头衔系统.yml", Config::YAML, ConfigData::$FIX);
    //文本信息配置
    $this->SMS = new Config($this->getServer()->getPluginPath()."/MultiSystemGo!/文本信息配置.yml", Config::YAML, ConfigData::$SMS);
    //智能保护系统
    $this->SPS = new Config($this->getServer()->getPluginPath()."/MultiSystemGo!/智能保护系统.yml", Config::YAML, ConfigData::$SPS);
    //特权玩家系统
    $this->PPS = new Config($this->getServer()->getPluginPath()."/MultiSystemGo!/特权玩家系统.yml", Config::YAML, ConfigData::$PPS);
    //玩家战队系统
    $this->PRS = new Config($this->getServer()->getPluginPath()."/MultiSystemGo!/玩家战队系统.yml", Config::YAML, ConfigData::$PRS);
    //玩家结婚系统
    $this->PLS = new Config($this->getServer()->getPluginPath()."/MultiSystemGo!/玩家结婚系统.yml", Config::YAML, ConfigData::$PLS);
    
    $this->FIX->setAll($this->FIX->getAll());
    $this->FIX->save();
    $this->SMS->setAll($this->SMS->getAll());
    $this->SMS->save();
    $this->SPS->setAll($this->SPS->getAll());
    $this->SPS->save();
    $this->PPS->setAll($this->PPS->getAll());
    $this->PPS->save();
    $this->PRS->setAll($this->PRS->getAll());
    $this->PRS->save();
    $this->PLS->setAll($this->PLS->getAll());
    $this->PLS->save();
    
    //创建计时器
    if($this->SMS->get("功能设置")["底部显示"] == "开"){
      $this->getServer()->getScheduler()->scheduleRepeatingTask(new CallbackTask([$this, "sendPopup"]),10);
    }
    if($this->PPS->get("功能设置")["披风变幻"] == "开"){
      $this->getServer()->getScheduler()->scheduleRepeatingTask(new CallbackTask([$this, "changingCape"]), $this->PPS->get("功能设置")["变幻时间"] * 20);
    }
    if(count($this->RDS->getAll()) !== 0){
      $this->getServer()->getScheduler()->scheduleRepeatingTask(new CallbackTask([$this, "updateSign"]), 20 * $this->PRS->get("战队木牌")["木牌刷新时间"]);
    }
    
    //更新配置文件
    if($this->FIX->get("功能设置") !== null){
      $this->FIX->setAll(ConfigData::$FIX);
      $this->FIX->save();
    }
    
    $configData = $this->PPS->getAll();
    if(isset($configData["顶级特权"]["伪装生物"])){
      $configData["顶级特权"]["伪装实体"] = $configData["顶级特权"]["伪装生物"];
      unset($configData["顶级特权"]["伪装生物"]);
    }
    if(isset($configData["高级特权"]["伪装生物"])){
      $configData["高级特权"]["伪装实体"] = $configData["高级特权"]["伪装生物"];
      unset($configData["高级特权"]["伪装生物"]);
    }
    if(isset($configData["普通特权"]["伪装生物"])){
      $configData["普通特权"]["伪装实体"] = $configData["普通特权"]["伪装生物"];
      unset($configData["普通特权"]["伪装生物"]);
    }
    if(isset($configData["顶级特权"]["编辑头衔"])){
      unset($configData["顶级特权"]["编辑头衔"]);
    }
    if(isset($configData["高级特权"]["编辑头衔"])){
      unset($configData["高级特权"]["编辑头衔"]);
    }
    if(isset($configData["普通特权"]["编辑头衔"])){
      unset($configData["普通特权"]["编辑头衔"]);
    }
    $this->PPS->setAll($configData);
    $this->PPS->save();
    
    if(isset($this->SMS->get("功能设置")["列表信息"])){
      $data = $this->SMS->get("功能设置");
      $data["列表格式"] = $data["列表信息"];
      unset($data["列表信息"]);
      $this->SMS->setAll($data);
      $this->SMS->save();
    }
    
    if(!$this->PCD->exists("update")){
      $this->getLogger()->notice("\n\n  §b[Go!] SmartProtectionGo 正在进行配置文件热更新...\n");
      foreach($this->PCD->getAll() as $xyz => $host){
        $this->PCD->set($xyz,["host" => $host, "trust" => []]);
      }
      $this->PCD->set("update", "enable");
      $this->PCD->save();
      $this->getLogger()->notice("\n\n  §b[Go!] SmartProtectionGo 配置文件热更新已完成!\n");
    }
    if($this->SMS->exists("列表信息")){
      $this->SMS->remove("列表信息");
      $this->SMS->save();
    }
    if(file_exists($this->getDataFolder()."/InternalData/SmartProtectionGo!/BanWorld.yml")){
      unlink($this->getDataFolder()."/InternalData/SmartProtectionGo!/BanWorld.yml");
    }
    if(isset($this->SPS->get("世界设置")["提示"])){
      $this->SPS->set("世界设置",[]);
      $this->SPS->save();
    }
    
    $this->getLogger()->notice("\n\n  §b[Go!] MultiSystemGo 全部系统已成功完成启动! 耗时: ".round(microtime(true) - $this->time, 3)."秒.\n");
    
  }
  
  public function onDisable(){
    if(count($this->BlockPos) == 0){
      return;
    }
    
    $this->getLogger()->notice("\n\n  §b[Go!] PrivilegePlayerGo 正在清除残余的伪装方块...\n");
    
    $main = new MultiSystemGo();
    
    foreach($main->BlockPos as $key => $playerName){
      foreach($main->BlockPos[$playerName] as $key => $pos){
        if(count($main->BlockPos[$playerName]) == 0){
          continue;
        }
        
        $blockPos = explode(":", $pos);
        $level = $this->getServer()->getLevelByName($blockPos[3]);
        if($level->getBlockIdAt($blockPos[0], $blockPos[1], $blockPos[2]) !== 0){
          $level->setBlockIdAt($blockPos[0], $blockPos[1], $blockPos[2], 0);
        }
      }
      unset($main->BlockPos[$playerName]);
    }
    $this->getLogger()->notice("\n\n  §b[Go!] PrivilegePlayerGo 清除完成!\n");
  }
  
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  public static function getInstance(){
    return self::$obj;
  }
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //添加特权玩家
  public function addPrivilege($playerName, $level, $time){
    if(!$this->PPT->exists($playerName)){
      $allTime = time() + $time * 86400;//总的时间戳 = 添加时的时间戳 + 特权时间的时间戳
      
      if($level == 0){
        $PrivilegeList = $this->C->get("玩家列表");
        if(!in_array($playerName, $PrivilegeList)){
          $PrivilegeList[] = $args[1];
          $this->C->set("玩家列表", $PrivilegeList);
          $this->C->save();
        }
        $this->PPT->set($playerName, $allTime);
        $this->PPT->save();
        //如果这个玩家在线
        if($this->getServer()->getPlayer($playerName) !== null AND $this->PPS->get("功能设置")["附加血量"] == "开"){
          $player = $this->getServer()->getPlayer($playerName);
          $player->setMaxHealth($player->getMaxHealth() + 20 * $this->PPS->get("普通特权")["附加血条"]);
          $player->setHealth($player->getMaxHealth() + 20 * $this->PPS->get("普通特权")["附加血条"]);
        }
        $this->getServer()->broadcastMessage("§b=====特权玩家系统=====\n§6恭喜玩家 §e{$playerName} §6成为一名新的普通特权玩家!");
      }
      if($level == 1){
        $PrivilegeList = $this->B->get("玩家列表");
        if(!in_array($playerName, $PrivilegeList)){
          $PrivilegeList[] = $args[1];
          $this->B->set("玩家列表", $PrivilegeList);
          $this->B->save();
        }
        $this->PPT->set($playerName, $allTime);
        $this->PPT->save();
        //如果这个玩家在线
        if($this->getServer()->getPlayer($playerName) !== null AND $this->PPS->get("功能设置")["附加血量"] == "开"){
          $player = $this->getServer()->getPlayer($playerName);
          $player->setMaxHealth($player->getMaxHealth() + 20 * $this->PPS->get("高级特权")["附加血条"]);
          $player->setHealth($player->getMaxHealth() + 20 * $this->PPS->get("高级特权")["附加血条"]);
        }
        $this->getServer()->broadcastMessage("§b=====特权玩家系统=====\n§6恭喜玩家 §e{$playerName} §6成为一名新的高级特权玩家!");
      }
      if($level == 2){
        $PrivilegeList = $this->A->get("玩家列表");
        if(!in_array($playerName, $PrivilegeList)){
          $PrivilegeList[] = $args[1];
          $this->A->set("玩家列表", $PrivilegeList);
          $this->A->save();
        }
        $this->PPT->set($playerName, $allTime);
        $this->PPT->save();
        //如果这个玩家在线
        if($this->getServer()->getPlayer($playerName) !== null AND $this->PPS->get("功能设置")["附加血量"] == "开"){
          $player = $this->getServer()->getPlayer($playerName);
          $player->setMaxHealth($player->getMaxHealth() + 20 * $this->PPS->get("顶级特权")["附加血条"]);
          $player->setHealth($player->getMaxHealth() + 20 * $this->PPS->get("顶级特权")["附加血条"]);
        }
        $this->getServer()->broadcastMessage("§b=====特权玩家系统=====\n§6恭喜玩家 §e{$playerName} §6成为一名新的顶级特权玩家!");
      }
    }
  }
  
  //移除特权玩家
  public function removePrivilege($playerName){
    if($this->PPT->exists($playerName)){
      //移除特权时间数据
      $this->PPT->remove($playerName);
      $this->PPT->save();
      //移除闪电冷却时间数据
      if($this->LCD->exists($playerName)){
        $this->LCD->remove($playerName);
        $this->LCD->save();
      }
      //移除时间冷却时间数据
      if($this->TCD->exists($playerName)){
        $this->TCD->remove($playerName);
        $this->TCD->save();
      }
      //移除烟花冷却时间数据
      if($this->FCD->exists($playerName)){
        $this->FCD->remove($playerName);
        $this->FCD->save();
      }
      $a = $this->A->get("玩家列表");
      $b = $this->B->get("玩家列表");
      $c = $this->C->get("玩家列表");
      if(in_array($playerName, $a)){
        $PrivilegeList = $this->A->get("玩家列表");
        $inv = array_search($playerName, $PrivilegeList);
        $inv = array_splice($PrivilegeList, $inv, 1);
        $this->A->set("玩家列表",$PrivilegeList);
        $this->A->save();
      }
      if(in_array($playerName, $c)){
        $PrivilegeList = $this->B->get("玩家列表");
        $inv = array_search($playerName, $PrivilegeList);
        $inv = array_splice($PrivilegeList, $inv, 1);
        $this->B->set("玩家列表",$PrivilegeList);
        $this->B->save();
      }
      if(in_array($playerName, $c)){
        $PrivilegeList = $this->C->get("玩家列表");
        $inv = array_search($playerName, $PrivilegeList);
        $inv = array_splice($PrivilegeList, $inv, 1);
        $this->C->set("玩家列表",$PrivilegeList);
        $this->C->save();
      }
      //如果这个玩家在线
      if($this->getServer()->getPlayer($playerName) !== null){
        $player = $this->getServer()->getPlayer($playerName);
        if($player->getGamemode() != 0){
          $player->setGamemode(0);
        }
        if($player->getAllowFlight()){
          $player->setAllowFlight(false);
        }
        if($this->PPS->get("功能设置")["附加血量"] =="开"){
          $player->setMaxHealth(20 + 20 * $this->PPS->get("顶级特权")["附加血条"]);
          $player->setHealth(20 + 20 * $this->PPS->get("顶级特权")["附加血条"]);
        }
        $player->setDataFlag(Entity::DATA_FLAGS, Entity::DATA_FLAG_INVISIBLE, false);
        $player->removeEffect(14);
      }
    }
  }
  
  //获得玩家称号
  public function getPlayerTitle($player){
    if($this->PPT->exists($player->getName())){
      $a = $this->A->get("玩家列表");
      $b = $this->B->get("玩家列表");
      $c = $this->C->get("玩家列表");
      $title = "NULL";
      if(in_array($player->getName(), $a)){
        $title = $this->PPS->get("顶级特权")["特权称号"];
      }
      if(array($player->getName(), $b)){
        $title = $this->PPS->get("高级特权")["特权称号"];
      }
      if(in_array($player->getName(),$c)){
        $title = $this->PPS->get("普通特权")["特权称号"];
      }
    }
    else{
      $title = $this->PPS->get("普通玩家")["普通称号"];
    }
    return $title;
  }
  //判断玩家是否为特权玩家
  public function isPrivilege(Player $player){
    if($this->PPT->exists($player->getName())){
      return true;
    }
    else{
      return false;
    }
  }
  
  //判断玩家身份类型
  public function getPlayerType($player){
    if($this->PPT->exists($player->getName())){
      if(in_array($player->getName(), $this->A->get("玩家列表"))){
        return 3;
      }
      if(in_array($player->getName(), $this->B->get("玩家列表"))){
        return 2;
      }
      if(in_array($player->getName(), $this->C->get("玩家列表"))){
        return 1;
      }
    }
    else{
      return 0;
    }
  }
  //获得特权剩余时间天数
  public function getPrivilegeTime($player){
    if($this->PPT->exists($player->getName())){
      $nowTime = time();//获取当前的时间戳
      $allTime = $this->PPT->get($player->getName());//获取存储于配置文件中的总时间戳
      $time = ($allTime - $nowTime)/86400;
      $haveTime = ceil($time);//取整
      return $haveTime;
    }
    else{
      return 0;
    }
  }
  
  //获得世界类型
  public function getWorldType($levelName){
    if(in_array($levelName, $this->PPW->get("顶级特权世界"))){
      return 3;
    }
    elseif(in_array($levelName, $this->PPW->get("高级特权世界"))){
      return 2;
    }
    elseif(in_array($levelName, $this->PPW->get("普通特权世界"))){
      return 1;
    }
    else{
      return 0;
    }
  }
  
  //结婚
  public function setMarry($playerName1, $playerName2){
    if(!$this->PMD->exists($playerName1)){
      if(!$this->PMD->exists($playerName2)){
        $this->PMD->set($playerName1, $playerName2);
        $this->PMD->set($playerName2, $playerName1);
        $this->PMD->save();
        
        $this->getServer()->broadcastMessage("§c=====玩家结婚系统=====\n§e恭喜玩家 §f{$playerName1} §e与玩家 §f{$playerName2} §e被系统强制结为夫妇, 让我们一同祝贺他们吧!");
        if($this->getServer()->getPlayer($playerName1) !== null){
          $this->getServer()->getPlayer($playerName1)->sendMessage("§c=====玩家结婚系统=====\n§e系统已强制将你和玩家 §f{$playerName2} 结为夫妇!");
        }
        if($this->getServer()->getPlayer($playerName2) !== null){
          $this->getServer()->getPlayer($playerName2)->sendMessage("§c=====玩家结婚系统=====\n§e系统已强制将你和玩家 §f{$playerName2} 结为夫妇!");
        }
        //传送到婚礼殿堂
        if($this->PDD->exists("!MarryPos")){
          if($this->getServer()->getPlayer($playerName1) !== null AND $this->getServer()->getPlayer($playerName2) !== null){
            $pos = explode(":", $this->PDD->get("!MarryPos"));
            $player1 = $this->getServer()->getPlayer($playerName1);
            $player2 = $this->getServer()->getPlayer($playerName2);
            $player1->teleport(new Position($pos[0], $pos[1], $pos[2], $this->getServer()->getLevelByName($pos[3])));
            $player2->teleport(new Position($pos[0], $pos[1], $pos[2], $this->getServer()->getLevelByName($pos[3])));
            $player1->sendMessage("§c=====玩家结婚系统=====\n§e已将你们传送到: §f婚礼殿堂");
            $player2->sendMessage("§c=====玩家结婚系统=====\n§e已将你们传送到: §f婚礼殿堂");
          }
        }
        //清除离婚任务
        if($this->PDD->exists($playerName1)){
          $this->PDD->remove($playerName1);
        }
        if($this->PDD->exists($playerName2)){
          $this->PDD->remove($playerName2);
        }
        $this->PDD->save();
        //清除求婚任务
        if($this->PLD->exists($playerName1)){
          $this->PLD->remove($playerName1);
        }
        if($this->PLD->exists($playerName2)){
          $this->PLD->remove($playerName2);
        }
        $this->PLD->save();
        //清除临时数据
        if($this->PTD->exists($playerName1)){
          $this->PTD->remove($playerName1);
        }
        if($this->PTD->exists($playerName1)){
          $this->PTD->remove($playerName2);
        }
        $this->PTD->save();
      }
      else{
        $this->getServer()->getLogger()->warning("§c=====玩家结婚系统=====\n§e处理失败, 原因: §f{$playerName2} 已经是已婚人士");
      }
    }
    else{
      $this->getServer()->getLogger()->warning("§c=====玩家结婚系统=====\n§e处理失败, 原因: §f{$playerName1} 已经是已婚人士");
    }
  }
  
  //离婚
  public function setDivorce($playerName){
    if($this->PMD->exists($playerName)){
      if($this->getServer()->getPlayer($playerName) !== null){
        $player = $this->getServer()->getPlayer($playerName);
        $player->sendMessage("§c=====玩家结婚系统=====\n§e你和你的配偶已被系统强制离婚!");
        if(isset($player->namedtag->SacrificeCD)){
          unset($player->namedtag->SacrificeCD);
        }
      }
      if($this->getServer()->getPlayer($this->PMD->exists($playerName)) !== null){
        $player=$this->getServer()->getPlayer($this->PMD->get($playerName));
        $player->sendMessage("§c=====玩家结婚系统=====\n§e你和你的配偶已被系统强制离婚!");
        if(isset($player->namedtag->SacrificeCD)){
          unset($player->namedtag->SacrificeCD);
        }
      }
      //清除离婚任务
      if($this->PDD->exists($playerName)){
        $this->PDD->remove($playerName);
      }
      if($this->PDD->exists($this->PMD->get($playerName))){
        $this->PDD->remove($this->PMD->get($playerName));
      }
      $this->PDD->save();
      //清除求婚任务
      if($this->PLD->exists($playerName)){
        $this->PLD->remove($playerName);
      }
      if($this->PDD->exists($this->PMD->get($playerName))){
        $this->PLD->remove($this->PMD->get($playerName));
      }
      $this->PLD->save();
      //清除临时数据
      if($this->PDD->exists($playerName)){
        $this->PTD->remove($playerName);
      }
      if($this->PDD->exists($this->PMD->get($playerName))){
        $this->PTD->remove($this->PMD->get($playerName));
      }
      $this->PTD->save();
      //清除结婚数据
      if($this->PDD->exists($this->PMD->get($playerName))){
        $this->PMD->remove($this->PMD->get($playerName));
      }
      if($this->PDD->exists($playerName)){
        $this->PMD->remove($playerName);
      }
      $this->PMD->save();
    }
  }
  
  //判断是否已婚
  public function isMarry($player){
    if($this->PMD->exists($player->getName())){
      return true;
    }
    else{
      return false;
    }
  }
  
  //获得配偶名称
  public function getSpouse($player){
    if($this->PMD->exists($player->getName())){
      return $this->PMD->get($player->getName());
    }
    else{
      return "无";
    }
  }
  
  //获得玩家的头衔
  public function getPrefix($player){
    if(isset($the->PRE->get("玩家头衔")[$player->getName()])){
      return $the->PRE->get("玩家头衔")[$player->getName()]["正在使用"];
    }
    else{
      return "无";
    }
  }
  //判断玩家是否为战队队长
  public function isCaptain($player){
    if($this->RCD->exists($player->getName())){
      return true;
    }
    else{
      return false;
    }
  }
  
  //获得玩家加入了什么战队
  public function getRangers($player){
    if($this->RPD->exists($player->getName())){
      return $this->RPD->get($player->getName());
    }
    else{
      return "无";
    }
  }
  
  //获得战队管理员
  public function getRangersManagers($rangersName){
    if($this->PRL->exists($rangersName)){
      //如果这个战队已经被创建
      return $this->PRD->get($rangersName)["管理"];
    }
  }
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  public function changingCape(){
    $capeData = CapeData::$capeData;
    if($this->PPS->get("顶级特权")["特权披风"] == "开"){
      foreach($this->A->get("玩家列表") as $playerName){
        if($this->getServer()->getPlayer($playerName) !== null){
          $player = $this->getServer()->getPlayer($playerName);
          $skin = new Skin($player->getSkin()->getSkinId(), $player->getSkin()->getSkinData(), base64_decode($capeData[mt_rand(0, count($capeData) - 1)]), $player->getSkin()->getGeometryName(), $player->getSkin()->getGeometryData());
          $player->setSkin($skin);
          $player->sendSkin(null);
        }
      }
    }
    if($this->PPS->get("高级特权")["特权披风"] == "开"){
      foreach($this->B->get("玩家列表") as $playerName){
        if($this->getServer()->getPlayer($playerName) !== null){
          $player=$this->getServer()->getPlayer($playerName);
          $skin = new Skin($player->getSkin()->getSkinId(), $player->getSkin()->getSkinData(), base64_decode($capeData[mt_rand(0, count($capeData) - 1)]), $player->getSkin()->getGeometryName(), $player->getSkin()->getGeometryData());
          $player->setSkin($skin);
          $player->sendSkin(null);
        }
      }
    }
    if($this->PPS->get("普通特权")["特权披风"] == "开"){
      foreach($this->C->get("玩家列表") as $playerName){
        if($this->getServer()->getPlayer($playerName) !== null){
          $skin = new Skin($player->getSkin()->getSkinId(), $player->getSkin()->getSkinData(), base64_decode($capeData[mt_rand(0, count($capeData) - 1)]), $player->getSkin()->getGeometryName(), $player->getSkin()->getGeometryData());
          $player->setSkin($skin);
          $player->sendSkin(null);
        }
      }
    }
  }

  public function nestedTag($player, $the, $content){
    $playerName = $player->getName();
    
    $health = $player->getHealth()."/".$player->getMaxHealth();
    
    $allArmor = $player->getInventory()->getArmorContents();
    $helmet = ($allArmor[0]->isArmor()) ? $allArmor[0]->getArmorTier() : 0;
    $chestplate = ($allArmor[1]->isArmor()) ? $allArmor[1]->getArmorTier() : 0;
    $leggings = ($allArmor[2]->isArmor()) ? $allArmor[2]->getArmorTier() : 0;
    $boots = ($allArmor[3]->isArmor()) ? $allArmor[3]->getArmorTier() : 0;
    $armor = $helmet + $chestplate + $leggings + $boots;
    
    $count = count($the->getServer()->getOnlinePlayers());
    
    $mode = str_replace([0, 1, 2, 3], ["生存模式", "创造模式", "冒险模式", "旁观模式"], $player->getGamemode());
    
    $money = EconomyAPI::getInstance()->myMoney($player);
    
    $item = "{$player->getInventory()->getItemInHand()->getId()}:{$player->getInventory()->getItemInHand()->getDamage()}";
    
    $level = $player->getLevel()->getFolderName();
    
    $xyz = intval($player->getX()).":".intval($player->getY()).":".intval($player->getZ());
    //获取在线时间
    if(isset($player->namedtag->JoinMinute) AND isset($player->namedtag->JoinHour)){
      $hour = $player->namedtag->JoinHour->getValue();
      $JoinMinute = $player->namedtag->JoinMinute->getValue();
      $minute = intval((time() - $JoinMinute) / 60);
      if($minute >= 59){
        $player->namedtag->JoinHour = new IntTag("JoinHour", $hour + 1);
        $player->namedtag->JoinMinute = new IntTag("JoinMinute", time());
      }
    }
    else{
      $player->namedtag->JoinHour = new IntTag("JoinHour", 0);
      $player->namedtag->JoinMinute = new IntTag("JoinMinute", time());
      $hour = $player->namedtag->JoinHour->getValue();
      $JoinMinute = $player->namedtag->JoinMinute->getValue();
      $minute = intval((time() - $JoinMinute) / 60);
      if($minute >= 59){
        $player->namedtag->JoinHour = new IntTag("JoinHour", $hour+1);
        $player->namedtag->JoinMinute = new IntTag("JoinMinute", time());
      }
    }
    $time = "{$hour}时{$minute}分";
    
    $title = "未知";
    if($the->PPT->exists($playerName)){
      $a = $the->A->get("玩家列表");
      $b = $the->B->get("玩家列表");
      $c = $the->C->get("玩家列表");
      if(in_array($playerName, $a)){
        $title = $the->PPS->get("顶级特权")["特权称号"];
      }
      if(in_array($playerName, $b)){
        $title = $the->PPS->get("高级特权")["特权称号"];
      }
      if(in_array($playerName, $c)){
        $title = $the->PPS->get("普通特权")["特权称号"];
      }
    }
    else{
      $title = $the->PPS->get("普通玩家")["普通称号"];
    }
    
    $luv = "无";
    if($the->PMD->exists($playerName)){
      $luv = $the->PMD->get($playerName);
    }
    
    $prefix = "无";
    if(isset($the->PRE->get("玩家头衔")[$playerName])){
      $prefix = $the->PRE->get("玩家头衔")[$playerName]["正在使用"];
    }
    
    $rangers="无";
    if($the->RPD->exists($playerName)){
      $rangers = $the->RPD->get($playerName);
    }
    
    $ping = $player->getPing();
    
    //替换字符串
    $target = [
      "{名称}",
      "{生命}",
      "{护甲}",
      "{延迟}",
      "{称号}",
      "{换行}",
      "{时间}",
      "{金币}",
      "{模式}",
      "{物品}",
      "{世界}",
      "{人数}",
      "{坐标}",
      "{配偶}",
      "{头衔}",
      "{战队}",
    ];
    
    $targets = [
      "{$playerName}",
      "{$health}",
      "{$armor}",
      "{$ping}",
      "{$title}",
      "\n",
      "{$time}",
      "{$money}",
      "{$mode}",
      "{$item}",
      "{$level}",
      "{$count}",
      "{$xyz}",
      "{$luv}",
      "{$prefix}",
      "{$rangers}",
    ];
    return str_replace($target, $targets, $content);
  }
  
  //底部信息显示
  public function sendPopup(){
    foreach($this->getServer()->getOnlinePlayers() as $player){
      //修复由于计时器太快导致get不到namedtag而报错
      if(!isset($player->namedtag->JoinHour)){
        $player->namedtag->JoinHour = new IntTag("JoinHour", 0);
      }
      if(!isset($player->namedtag->JoinMinute)){
        $player->namedtag->JoinMinute = new IntTag("JoinMinute", time());
      }
      if(!in_array($player->getLevel()->getFolderName(), $this->SMS->get("功能设置")["隐底世界"])){
        if($this->SMS->get("功能设置")["底部兼容"] == "开"){
          $player->sendTip($this->nestedTag($player, $this, $this->SMS->get("功能设置")["底部信息"]));
        }
        else{
          $player->sendPopup($this->nestedTag($player, $this, $this->SMS->get("功能设置")["底部信息"]));
        }
      }
    }
  }
  
  //用于替换战队信息显示
  public function rangers($rangers, $content){
    if($this->PRD->exists($rangers)){
      $data = $this->PRD->get($rangers);
      $target = [
        "{队名}",
        "{人数}",
        "{基金}",
        "{队长}",
      ];
      $targets=[
        "{$rangers}",
        count($data["成员"])."/{$data["人数"]}",
        "{$data["基金"]}",
        "{$data["队长"]}",
      ];
      return str_replace($target, $targets, $content);
    }
  }
  
  //刷新木牌
  public function updateSign(){
    foreach($this->RDS->getAll() as $rangers => $info){
      if($this->PRL->exists($rangers)){
        $pos = explode(":", $info);
        if($this->getServer()->getLevelByName($pos[0]) !== null){
          $tile = $this->getServer()->getLevelByName($pos[0])->getTile(new Vector3($pos[1], $pos[2], $pos[3]));
          if($tile instanceof Sign){
            $tile->setText($this->rangers($rangers, $this->PRS->get("战队木牌")["战队宣传木牌"]["第一行"]), $this->rangers($rangers, $this->PRS->get("战队木牌")["战队宣传木牌"]["第二行"]), $this->rangers($rangers, $this->PRS->get("战队木牌")["战队宣传木牌"]["第三行"]), $this->rangers($rangers, $this->PRS->get("战队木牌")["战队宣传木牌"]["第四行"]));
          }
          else{
            //移除战队木牌
            $this->RDS->remove($this->RDS->get($rangers));
            $this->RDS->remove($rangers);
            $this->RDS->save();
          }
        }
        else{
          //移除战队木牌
          $this->RDS->remove($this->RDS->get($rangers));
          $this->RDS->remove($rangers);
          $this->RDS->save();
        }
      }
    }
  }
  
  //发送闪电
  public function sendLightning($player){
    $nbt0 = new CompoundTag("", [
      "Pos" => new ListTag("Pos", [
        new DoubleTag("", $player->getX()),
        new DoubleTag("", $player->getY()),
        new DoubleTag("", $player->getZ())
      ]),
      "Motion" => new ListTag("Motion", [
        new DoubleTag("", 0),
        new DoubleTag("", 0),
        new DoubleTag("", 0)
      ]),
      "Rotation" => new ListTag("Rotation", [
        new FloatTag("", $player->getYaw()),
        new FloatTag("", $player->getPitch())
      ])
    ]);
    $nbt1 = new CompoundTag("", [
      "Pos" => new ListTag("Pos", [
        new DoubleTag("", $player->getX() + 3),
        new DoubleTag("", $player->getY()),
        new DoubleTag("", $player->getZ())
      ]),
      "Motion" => new ListTag("Motion", [
        new DoubleTag("", 0),
        new DoubleTag("", 0),
        new DoubleTag("", 0)
      ]),
      "Rotation" => new ListTag("Rotation", [
        new FloatTag("", $player->getYaw()),
        new FloatTag("", $player->getPitch())
      ])
    ]);
    $nbt2 = new CompoundTag("", [
      "Pos" => new ListTag("Pos", [
        new DoubleTag("", $player->getX() - 3),
        new DoubleTag("", $player->getY()),
        new DoubleTag("", $player->getZ())
      ]),
      "Motion" => new ListTag("Motion", [
        new DoubleTag("", 0),
        new DoubleTag("", 0),
        new DoubleTag("", 0)
      ]),
      "Rotation" => new ListTag("Rotation", [
        new FloatTag("", $player->getYaw()),
        new FloatTag("", $player->getPitch())
      ])
    ]);
    $nbt3 = new CompoundTag("", [
      "Pos" => new ListTag("Pos", [
        new DoubleTag("", $player->getX()),
        new DoubleTag("", $player->getY()),
        new DoubleTag("", $player->getZ() + 3)
      ]),
      "Motion" => new ListTag("Motion", [
        new DoubleTag("", 0),
        new DoubleTag("", 0),
        new DoubleTag("", 0)
      ]),
      "Rotation" => new ListTag("Rotation", [
        new FloatTag("", $player->getYaw()),
        new FloatTag("", $player->getPitch())
      ])
    ]);
    $nbt4 = new CompoundTag("", [
      "Pos" => new ListTag("Pos", [
        new DoubleTag("", $player->getX()),
        new DoubleTag("", $player->getY()),
        new DoubleTag("", $player->getZ() - 3)
      ]),
      "Motion" => new ListTag("Motion", [
        new DoubleTag("", 0),
        new DoubleTag("", 0),
        new DoubleTag("", 0)
      ]),
      "Rotation" => new ListTag("Rotation", [
        new FloatTag("", $player->getYaw()),
        new FloatTag("", $player->getPitch())
      ])
    ]);
    $nbt5 = new CompoundTag("", [
      "Pos" => new ListTag("Pos", [
        new DoubleTag("", $player->getX() + 2),
        new DoubleTag("", $player->getY()),
        new DoubleTag("", $player->getZ() - 2)
      ]),
      "Motion" => new ListTag("Motion", [
        new DoubleTag("", 0),
        new DoubleTag("", 0),
        new DoubleTag("", 0)
      ]),
      "Rotation" => new ListTag("Rotation", [
        new FloatTag("", $player->getYaw()),
        new FloatTag("", $player->getPitch())
      ])
    ]);
    $nbt6 = new CompoundTag("", [
      "Pos" => new ListTag("Pos", [
        new DoubleTag("", $player->getX() + 2),
        new DoubleTag("", $player->getY()),
        new DoubleTag("", $player->getZ() + 2)
      ]),
      "Motion" => new ListTag("Motion", [
        new DoubleTag("", 0),
        new DoubleTag("", 0),
        new DoubleTag("", 0)
      ]),
      "Rotation" => new ListTag("Rotation", [
        new FloatTag("", $player->getYaw()),
        new FloatTag("", $player->getPitch())
      ])
    ]);
    $nbt7 = new CompoundTag("", [
      "Pos" => new ListTag("Pos", [
        new DoubleTag("", $player->getX() - 2),
        new DoubleTag("", $player->getY()),
        new DoubleTag("", $player->getZ() + 2)
      ]),
      "Motion" => new ListTag("Motion", [
        new DoubleTag("", 0),
        new DoubleTag("", 0),
        new DoubleTag("", 0)
      ]),
      "Rotation" => new ListTag("Rotation", [
        new FloatTag("", $player->getYaw()),
        new FloatTag("", $player->getPitch())
      ])
    ]);
    $nbt8 = new CompoundTag("", [
      "Pos" => new ListTag("Pos", [
        new DoubleTag("", $player->getX() - 2),
        new DoubleTag("", $player->getY()),
        new DoubleTag("", $player->getZ() - 2)
      ]),
      "Motion" => new ListTag("Motion", [
        new DoubleTag("", 0),
        new DoubleTag("", 0),
        new DoubleTag("", 0)
      ]),
      "Rotation" => new ListTag("Rotation", [
        new FloatTag("", $player->getYaw()),
        new FloatTag("", $player->getPitch())
      ])
    ]);
    $entity0 = new Lightning($player->getLevel(), $nbt0);
    $entity1 = new Lightning($player->getLevel(), $nbt1);
    $entity2 = new Lightning($player->getLevel(), $nbt2);
    $entity3 = new Lightning($player->getLevel(), $nbt3);
    $entity4 = new Lightning($player->getLevel(), $nbt4);
    $entity5 = new Lightning($player->getLevel(), $nbt5);
    $entity6 = new Lightning($player->getLevel(), $nbt6);
    $entity7 = new Lightning($player->getLevel(), $nbt7);
    $entity8 = new Lightning($player->getLevel(), $nbt8);
    $entity0->spawnToAll();
    $entity1->spawnToAll();
    $entity2->spawnToAll();
    $entity3->spawnToAll();
    $entity4->spawnToAll();
    $entity5->spawnToAll();
    $entity6->spawnToAll();
    $entity7->spawnToAll();
    $entity8->spawnToAll();
  }
  
  //找到地面的v3坐标
  public function findfloor($level, $v3){
    $y = $v3->getY();
    do{
      $y = $y - 1;
      $v3->y = $y;
      $block = $level->getBlock($v3);
      $id = $block->getId();
    }
    while($id == 0 AND $y >= 0);
    if($y < 0){
      return false;
    }
    else{
      $v3->y = $v3->y + 1;
      return $v3;
    }
  }
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  public function onCommand(CommandSender $sender, Command $cmd, string $label, array $args){
    PrivilegePlayerGo::onCommand($sender, $cmd, $label, $args, $this);
    SmartProtectionGo::onCommand($sender, $cmd, $label, $args, $this);
    PrefixGo::onCommand($sender, $cmd, $label, $args, $this);
    LuvGo::onCommand($sender, $cmd, $label, $args, $this);
    RangersGo::onCommand($sender, $cmd, $label, $args, $this);
  }
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //玩家加入检测
  public function onPlayerJoin(PlayerJoinEvent $event){
    PlayerJoin::onPlayerJoin($event, $this);
  }
  //玩家退出检测
  public function onPlayerQuit(PlayerQuitEvent $event){
    PlayerQuit::onPlayerQuit($event, $this);
  }
  //玩家被踢检测
  public function onPlayerKick(PlayerKickEvent $event){
    PlayerKick::onPlayerKick($event, $this);
  }
  //玩家移动检测
  public function onPlayerMove(PlayerMoveEvent $event){
    PlayerMove::onPlayerMove($event, $this);
  }
  //丢弃物品检测
  public function onPlayerDropItem(PlayerDropItemEvent $event){
    PlayerDropItem::onPlayerDropItem($event);
  }
  //触摸方块检测
  public function onPlayerInteract(PlayerInteractEvent $event){
    PlayerInteract::onPlayerInteract($event, $this);
  }
  //切换模式检测
  public function onPlayerModeChange(PlayerGameModeChangeEvent $event){
    PlayerGameModeChange::onPlayerGameModeChange($event, $this);
  }
  //死亡掉落检测
  public function onPlayerDeath(PlayerDeathEvent $event){
    PlayerDeath::onPlayerDeath($event, $this);
  }
  //执行命令检测
  public function onPlayerCommandPreprocess(PlayerCommandPreprocessEvent $event){
    PlayerCommandPreprocess::onPlayerCommandPreprocess($event, $this);
  }
  //玩家潜行检测
  public function onPlayerToggleSneak(PlayerToggleSneakEvent $event){
    PlayerToggleSneak::onPlayerToggleSneak($event, $this);
  }
  //玩家发言检测
  public function onPlayerChat(PlayerChatEvent $event){
    PlayerChat::onPlayerChat($event, $this);
  }
  //玩家重生检测
  public function onPlayerRespawn(PlayerRespawnEvent $event){
    PlayerRespawn::onPlayerRespawn($event, $this);
  }
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //放置方块记录
  public function onBlockPlace(BlockPlaceEvent $event){
    BlockPlace::onBlockPlace($event, $this);
  }
  //破坏方块检测
  public function onBlockBreak(BlockBreakEvent $event){
    BlockBreak::onBlockBreak($event, $this);
  }
  //方块更新检测
  public function onBlockUpdate(BlockUpdateEvent $event){
    BlockUpdate::onBlockUpdate($event, $this);
  }
  //物品展示框
  public function onItemFrameDropItem(ItemFrameDropItemEvent $event){
    ItemFrameDropItem::onItemFrameDropItem($event);
  }
  //木牌状态检测
  public function onSignChange(SignChangeEvent $event){
    SignChange::onSignChange($event, $this);
  }
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //进入禁用命令世界检测
  public function onEntityLevelChange(EntityLevelChangeEvent $event){
    EntityLevelChange::onEntityLevelChange($event, $this);
  }
  //火焰检测
  public function onEntityDamage(EntityDamageEvent $event){
    EntityDamage::onEntityDamage($event, $this);
  }
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //乘骑玩家
  public function onDataPacketReceive(DataPacketReceiveEvent $event){
    DataPacketReceive::onDataPacketReceive($event, $this);
  }
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
}
?>