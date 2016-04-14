<?php
require_once 'http.php';

$host_id  = $_POST["hostid"];
$group_id = $_POST["groupid"];
$op_type  = $_POST["optype"];

$xml = simplexml_load_file("../conf/servers.xml");
foreach ($xml->children() as $host) {
    $hostid = $host->host_id;
    if ($host_id == $hostid) {
        $ip = $host->host_ip;
        $monitor_list = $host->monitor_list;
        foreach ($monitor_list->children() as $monitor_single) {
            $groupid = $monitor_single->group_id;
            if ($group_id == $groupid && $monitor_single->switcher == 1) {
                $port = $monitor_single->port;
                $url = "http://$ip:$port";
                $req = array();
                if ($op_type == "start") {
                    $req["cmd"] = "http_start_server_req";
                } else if ($op_type == "stop"){
                    $req["cmd"] = "http_stop_server_req";
                } else {
                    echo "unknown cmd\n";
                    exit;
                }

                $json = json_encode($req);
                $http = new http();
                $res = $http->PostReq($url, $json);
                if ($res->reply_code == 0) {
                    echo "sucess";
                    exit;
                }
            }
        }
    } else {
        continue;
    }
}

echo "failed";

?>
