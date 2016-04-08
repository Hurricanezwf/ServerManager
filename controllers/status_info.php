<?php
require_once 'status.interface.php';
require_once 'mail.php';

// 前端获取服务器状态信息逻辑
$status_info = get_server_status();
//echo json_encode($status_info);
echo "\n";

// send mail
$bad_servers = get_bad_server($status_info);
$is_all_server_ok = 0;
if (empty($bad_servers)) {
    $is_all_server_ok = 1;
}
//echo "is_all_server_ok=$is_all_server_ok\n";

$is_send = is_send_mail($is_all_server_ok);
//echo "is_send=$is_send\n";
if ($is_send) {
    $ini = parse_ini_file("../conf/cfg.ini", true);
    $email_receivers = $ini['mail']['email_receivers'];
    $receivers_array = explode(',', $email_receivers);

    foreach ($receivers_array as $receiver) {
        trim($receiver);
        echo "send mail to $receiver!\n";
        $res = false;
        if ($is_all_server_ok == 0) {
            $mailsub  = "Server Dump";
            $mailbody = parse_bad_servers_to_html($bad_servers);
            $res = sendmailto($receiver, $mailsub, $mailbody); 
        } else {
            $mailsub  = "Server Recover";       
            $mailbody = "All server recover to normal";
            $res = sendmailto($receiver, $mailsub, $mailbody);
        }
        if ($res == false) {
            echo "send mail to $receiver failed\n";
        }
    }
}




// @param $status_info: all server's status_info object
// @return json format:
// {
//     [{
//          "host_id" : 1,
//          "group_id": 1,
//          "bad_servers": {
//              "XX_server" : 0,
//              "XX_server" : "err"
//          }
//     },
//     {
//          .....
//     }]
// }
function get_bad_server($status_info) {
    $res = array();
    foreach ($status_info as $single_group_info) {
        $bad_servers = array();

        $group_status_info_array = $single_group_info['status_info'];
        if (empty($group_status_info_array)) {
            printf("host[%d] group[%d] monitor is not run. \n", $single_group_info['host_id'], $single_group_info['group_id']);
            $bad_servers["err"] = "monitor_server is not run";
        } else {
            foreach ($group_status_info_array as $group_detail) {
                foreach ($group_detail as $server_name => $detail) {
                    if ($detail->state != 1) {
                        $bad_servers[$server_name] = (string)$detail->state;
                    }
                }
            }
        }

        if (!empty($bad_servers)) {
            $bad_group = array();
            $bad_group['host_id']     = $single_group_info['host_id'];
            $bad_group['group_id']    = $single_group_info['group_id'];
            $bad_group['bad_servers'] = $bad_servers;
            array_push($res, $bad_group);
        }
    }

    return $res;
}




// @param $is_all_server_ok   0 or 1
// @return true or false
function is_send_mail($is_all_server_ok) {
    $file_path = "../cache/status.cache";
    if (!file_exists("$file_path")) {
        shell_exec("touch $file_path");
    }

    $cache = fopen("$file_path", "rb") or die("open file failed!");
    $server_history_status = fread($cache, 10);
    fclose($cache);
    if ($server_history_status == "") {
        $cache = fopen("$file_path", "wb");
        fwrite($cache, 1);
        fclose($cache);
    } else if ($server_history_status != $is_all_server_ok) {
        $cache = fopen("$file_path", "wb");
        fwrite($cache, $is_all_server_ok);
        fclose($cache);
        return true;
    }

    return false;
}

// 将bad_servers的Object转换成可视化的HTML
function parse_bad_servers_to_html($bad_servers) {
    $reply = "<table border='1' cellspacing='0'>";
    $reply .= "<th>host_id</th>";
    $reply .= "<th>group_id</th>";
    $reply .= "<th>bad_servers</th>";
    foreach ($bad_servers as $bad_single) {
        $host_id = $bad_single['host_id'];
        $group_id = $bad_single['group_id'];
        $server_list = $bad_single['bad_servers'];

        $tr = "<tr>";
        $tr .= "<td>$host_id</td>";
        $tr .= "<td>$group_id</td>";
        $tr .= "<td>";
        foreach ($server_list as $server_name => $state) {
           $tr .= "$server_name:$state<br>";
        }
        $tr .= "</td>";
        $tr .= "</tr>"; 

        $reply .= $tr;
    } 
    $reply .= "</table>";
    return $reply;
}

?>
