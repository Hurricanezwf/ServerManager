<?php
require_once 'http.php';

// 正确的返回的json格式:
// 出错时, "status_info" : ""
// {
//      [{
//          "host_id" : 1,
//          "group_id": 100,
//          "status_info": {
//              "login_server" : {
//                  "state" : 0, 
//                  "memory": 82,
//                  "cpu"   : 1.1
//              },
//              "gate1_server" : {
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
//              "gate1_server" : {
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
                );
                $json = json_encode($req);
                $res = $http->PostReq($url, $json);

                $single_group_status["host_id"] = (string)$host->host_id;
                $single_group_status["group_id"] = (string)$monitor_single->group_id;
                if ($res != FALSE && $res->reply_code == 0) {
                    $single_group_status["status_info"] = $res->data;
                } else {
                    $single_group_status["status_info"] = "";
                }

                array_push($reply, $single_group_status);
            }
        }
    }

    return $reply;
}


// $host_id and $group_id is a number
function get_group_name($host_id, $group_id) {
    $xml = simplexml_load_file("../conf/servers.xml");
    foreach ($xml->children() as $host) {
        if ($host->host_id == $host_id) {
            $monitor_list = $host->monitor_list;
            foreach ($monitor_list->children() as $monitor_single) {
                if ($monitor_single->group_id == $group_id) {
                    $name = $monitor_single->group_name;
                    return $name;
                }
            }
        }
    }

    return "";
}

// @param $is_stop_when_failed: 服务器组开启失败时, 是否关闭单个server_list
//        0表示不关闭, 1表示关闭
//
// @return json格式如下:
// [
//      {
//          "host_id": 1,
//          "group_id": 4,
//          "start_res": 0      //0表成功  非0表失败
//      }
// ]
function start_server($is_stop_when_failed)
{
    $reply = array();
    $http = new http();
    $xml  = simplexml_load_file("../conf/servers.xml");
    foreach ($xml->children() as $host) {
        $ip = $host->host_ip;
        $monitor_list = $host->monitor_list;
        foreach ($monitor_list->children() as $monitor_single) {
            if ($monitor_single->switcher == 1) {
                $port = $monitor_single->port;

                $url = "http://$ip:$port";
                $req = array(
                    "cmd" => "http_start_server_req",
                );
                $json = json_encode($req);
                $res = $http->PostReq($url, $json);
                
                sleep(1);
                $single_group_start_res["host_id"]  = (string)$host->host_id;
                $single_group_start_res["group_id"] = (string)$monitor_single->group_id;
                if ($res != FALSE && $res->reply_code == 0) {
                    $req = array(
                        "cmd" => "http_check_server_active_req",    
                        "is_stop_when_failed" => $is_stop_when_failed,
                    );
                    $json = json_encode($req);
                    $res = $http->PostReq($url, $json);
                    $single_group_start_res["start_res"] = $res->reply_code;
                } else {
                    $single_group_start_res["start_res"] = "err";
                }

                array_push($reply, $single_group_start_res);
            }
        }
        break;
    }

    return json_encode($reply);
}


function stop_server()
{
    $reply = array();
    $http = new http();
    $xml  = simplexml_load_file("../conf/servers.xml");
    foreach ($xml->children() as $host) {
        $ip = $host->host_ip;
        $monitor_list = $host->monitor_list;
        foreach ($monitor_list->children() as $monitor_single) {
            if ($monitor_single->switcher == 1) {
                $port = $monitor_single->port;

                $url = "http://$ip:$port";
                $req = array(
                    "cmd" => "http_stop_server_req",
                );
                $json = json_encode($req);
                $res = $http->PostReq($url, $json);
                
                sleep(1);
                $single_group_stop_res["host_id"]  = (string)$host->host_id;
                $single_group_stop_res["group_id"] = (string)$monitor_single->group_id;
                if ($res != FALSE && $res->reply_code == 0) {
                    $req = array(
                        "cmd" => "http_check_server_inactive_req",    
                    );
                    $json = json_encode($req);
                    $res = $http->PostReq($url, $json);
                    $single_group_stop_res["stop_res"] = $res->reply_code;
                } else {
                    $single_group_stop_res["stop_res"] = "err";
                }

                array_push($reply, $single_group_stop_res);
            }
        }
        break;
    }

    return json_encode($reply);
}

//echo get_server_status() . "\n";
//echo start_server(1) . "\n";
//echo stop_server() . "\n";

?>
