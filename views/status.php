<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta http-equiv="Expires" content="0" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Cache-control" content="no-cache" />
    <meta http-equiv="Cache" content="no-cache" />

    <title>服务器状态</title>
    <style type="text/css">
       html body {
            margin-left: 12px;
            font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
            font-size: 14px;
            color: #333;
        }

        .ok {
            color: green;
        }
        
        .failed {
            color: red;
        }

    </style>
</head>

<body>
    <?php
        require_once '../controllers/status_interface.php';

        $group_name = $_GET["group"];
        echo "<h3>$group_name servers status</h3><hr><br>";

        $table  = "<table><tr>";
        $table .= "<th>server_name</th>";
        $table .= "<th>state</th>";
        $table .= "<th>memory(MB)</th>";
        $table .= "<th>CPU(%)</th>";

        $res = check_all_servers($group_name);
        foreach ($res as $server => $status) {
            $table .= "<tr>";
            $table .= "<td>$server</td>";

            if ($status["state"] == 1) {
                $table .= "<td class='ok'>RUN</td>";
            } else {
                $table .= "<td class='failed'>STOP</td>";
            }

            $mem = $status['memory'];
            $cpu = $status['cpu'];
            $table .= "<td>$mem</td>";
            $table .= "<td>$cpu</td>";
            $table .= "</tr>";
        }

        $table .= "</tr></table>";
        echo $table;
    ?>
</body>

</html>
