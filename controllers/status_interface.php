<?php

// 检测N组服务器的状态
function check_all_groups() {
   $ini = parse_ini_file('../conf/cfg.ini'); 

   $status = array();
   $group_list  = $ini['group_list'];
   $group_array = explode(',', $group_list);
   foreach ($group_array as $group_name) {
       $group_name = trim($group_name, ' ');
       if ($group_name != null && $group_name != "") {
           $res = check_all_servers($group_name);
           $status[$group_name] = $res;
       }
   }

   return $status;
}

// 检测一组服务器的状态
function check_all_servers($group_name) {
    $ini = parse_ini_file('../conf/cfg.ini', true);
    $group_ini = $ini[$group_name];
    $user_uid  = $group_ini['user_uid'];

    $server_info = array();
    $server_list = $ini['server_list'];
    $server_array= explode(',', $server_list);
    foreach ($server_array as $server_name) {
        $server_name = trim($server_name, ' ');
        if ($server_name != null && $server_name != "") {
            $server_name = ($server_name == "monitor") ? $server_name : $server_name."_server";
            $res = check_single_server($server_name, $user_uid);
            $server_info[$server_name] = $res;
            //echo $server_name . "=" . $res . "\n";
        }
    }

    return $server_info;
}


// @param $server_name    服务器名称,如login_server
// @param $user_uid       linux账户uid, 如501
function check_single_server($server_name, $user_uid) {
    $res = shell_exec("ps -ef | grep '$user_uid' | grep '$server_name' | grep -v 'grep' | wc -l");
    if ($res == 1) {
        return 1;
    }
    return 0;
}


?>
