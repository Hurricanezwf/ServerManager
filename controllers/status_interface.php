<?php

function check_all_servers() {
    $ini = parse_ini_file('../conf/cfg.ini');

    $server_info = array();
    $server_list = $ini['server_list'];
    $server_array= explode(',', $server_list, -1);
    foreach ($server_array as $server_name) {
        if ($server_name != null && $server_name != "") {
            $server_name = ($server_name == "monitor") ? $server_name : $server_name."_server";
            $res = check_single_server($server_name);
            $server_info[$server_name] = $res;
            //echo $server_name . "=" . $res . "\n";
        }
    }

    return $server_info;
}


function check_single_server($server_name) {
    $user_uid = shell_exec('id -u');
    $res = shell_exec("ps -ef | grep '$user_uid' | grep '$server_name' | grep -v 'grep' | wc -l");
    if ($res == 1) {
        return 1;
    }
    return 0;
}


?>
