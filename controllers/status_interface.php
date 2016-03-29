<?php
require_once 'http.php';

// 检测N组服务器的状态
// json结构
// {
//      "test" : {
//          "login_server" : {
//              "state" : 0,
//              "memory": 8, (单位Mb)
//              "cpu"   : 1.1(百分比)
//          },
//          "gate_server" : {
//              "state" : 1,
//              "memory": 2,
//              "cpu"   : 3
//          }
//      }
// }
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
// json结构
// {
//      "login_server" : {
//          "state" : 0,
//          "memory": 1,
//          "cpu"   : 2.2
//      },
//      "gate_server" : {
//          "state" : 0,
//          "memory": 1,
//          "cpu"   : 3
//      },
// }
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
    $single_server = array();

    $res = shell_exec("ps -ef | grep '$user_uid' | grep '$server_name' | grep -v 'grep' | grep -v 'gdb' | wc -l");
    $single_server["state"] = ($res == 1 ? 1 : 0);

    $pid = get_process_pid($server_name, $user_uid);
    if ($pid != null && $pid != "" && $pid > 0) {
        $single_server["memory"] = get_memory_usage($pid);
        $single_server["cpu"]    = get_cpu_usage($pid);
    } else {
        $single_server["memory"] = "--";
        $single_server["cpu"]    = "--";
    }

    return $single_server;
}

function get_process_pid($process_name, $user_uid) {
    if ($process_name == null || $user_uid == null) {
        printf("[E] get process pid failed! param not enough \n");
        return -1;
    }

    $cmd = sprintf("ps -ef -u %s | grep %s | grep -v 'grep' | grep -v 'vim' | grep -v 'gdb' | grep -v 'tail' | awk '{print $2}'", $user_uid, $process_name);
    return shell_exec($cmd);
}

function get_cpu_usage($pid) {
    if ($pid == null) {
        printf("[E] get cpu usage failed! param not enough \n");
        return "";
    }

    $cmd = sprintf("ps -o pcpu -p %d | grep -v 'CPU' | awk '{print $1}'", $pid);
    return shell_exec($cmd);
}

function get_memory_usage($pid) {
    if ($pid == null) {
        printf("[E] get memory usage failed! param not enough \n");
        return "";
    }

    $cmd = sprintf("ps -o vsz -p %d | grep -v VSZ", $pid);
    $mem_kb = shell_exec($cmd);
    $mem_mb = $mem_kb/(1024*8);
    return number_format($mem_mb, 2);
}



// 返回的json格式:
// {
//      [{
//          "host_id" : 1,
//          "group_id": 100,
//          "status_info": {
//              "login_server" : {
//                  "state" : 0,  //"--"表示超时或未找到
//                  "memory": 82,
//                  "cpu"   : 1.1
//              },
//              "gate_server" : {
//                  ...
//              }
//          }
//      },
//      {
//          "host_id" : 1,
//          "group_id": 101,
//          "status_info": {
//              "login_server" : {
//                  "state" : 0,
//                  "memory": 82,
//                  "cpu"   : 1.1
//              },
//              "gate_server" : {
//                  ...
//              }
//          }
//      }]
// }
function get_server_status() {
    $reply = array();
    $http  = new http();
    $xml = simplexml_load_file("../conf/servers.xml");
    foreach ($xml->children() as $host) {
        $ip = $host->host_ip;
        $monitor_list = $host->monitor_list;
        foreach ($monitor_list->children() as $monitor_single) {
            if ($monitor_single->switcher == 1) {
                $port = $monitor_single->port;

                $url = "http://$ip:$port";
                $req = array(
                    "cmd" => "http_get_all_status_req",
                    "data"=> "",    
                );
                $json = json_encode($req);
                $res = $http->PostReq($url, $json);

                $single_group_status["host_id"] = "$host->host_id";
                $single_group_status["group_id"] = "$monitor_single->group_id";
                $single_group_status["status_info"] = $res;

                array_push($reply, $single_group_status);
            }
        }
        break;
    }

    return json_encode($reply);
}

?>
