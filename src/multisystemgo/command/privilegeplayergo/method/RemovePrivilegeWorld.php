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

class RemovePrivilegeWorld{
  public static function removePrivilegeWorld($PrivilegeWorldList, $worldName, $title, $main){
    $inv = array_search($worldName, $PrivilegeWorldList);
    $inv = array_splice($PrivilegeWorldList, $inv, 1);
    $main->PPW->set($title."世界", $PrivilegeWorldList);
    $main->PPW->save();
  }
}