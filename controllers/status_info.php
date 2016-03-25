<?php
require_once './status_interface.php';

$dump_group_for_web = array(); //只返回有崩溃服务器的服务器组名

$status = check_all_groups();
foreach ($status as $server_group => $group_staus) {
    $dump_server = array();
    foreach ($group_staus as $server => $status) {
        if ($status["state"] != 1) {
            array_push($dump_server, $server);
        } 
    } 
    if (count($dump_server) > 0) {
        $dump_group_for_web[$server_group] = $server_group;
    }
}

$reply_data = json_encode($dump_group_for_web);
echo $reply_data;
?>
