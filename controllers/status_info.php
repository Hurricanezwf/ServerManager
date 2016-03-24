<?php
require_once './status_interface.php';

$status = check_all_groups();
$json = json_encode($status);
echo $json;
?>
