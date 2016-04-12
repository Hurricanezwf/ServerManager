<?php
require_once '../controllers/status.interface.php';
$status_info = get_server_status();
foreach ($status_info as $single_group_status) {
    $host_id  = $single_group_status['host_id'];
    $group_id = $single_group_status['group_id'];
    $group_name = get_group_name($host_id, $group_id);
    $display_hostid = "host" . $host_id;
    $table_id = "_" . "$host_id" . "_" . "$group_id";

    echo "<div class='row server_row $display_hostid'>";
    echo      "<span class='server_title'>$group_name</span>";
    echo      "<span class='glyphicon glyphicon-menu-down pull-right'></span>";
    echo      "<div id='$table_id' class='table-responsive'>";
    echo          "<table class='table table-striped'>";
    echo               "<tr>";
    echo                    "<th>process</th>";
    echo                    "<th>STATE</th>";
    echo                    "<th>CPU(%)</th>";
    echo                    "<th>MEMORY(MB)</th>";
    echo               "</tr>";

    $group_detail = $single_group_status['status_info'];
    if (!empty($group_detail)) {
        foreach ($group_detail as $single_process) {
            foreach ($single_process as $server_name => $server_data) {
                $tr = "<tr>";
                $tr .= "<td class='name'>$server_name</td>";
                $tr .= "<td class='state'>$server_data->state</td>";
                $tr .= "<td class='cpu'>$server_data->cpu</td>";
                $tr .= "<td class='memory'>$server_data->memory</td>";
                $tr .= "</tr>";
                echo $tr;
            }
        }
    }
    echo            "</table>";
    echo      "</div>";
    echo      "<hr>";
    echo "</div>";
}
?>
