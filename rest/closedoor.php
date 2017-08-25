<?php
header('Content-type: application/json');
if(isset($_GET["p"])){
    $door = $_GET["p"];
    $command = escapeshellcmd('sudo python /opt/thebox/closedoor.py '.$door);
    $output = shell_exec($command);
    echo "{message: \"". $output."\", door: \"".$door."\",  status:\"200\"}";
}
else{
    echo "{message: \"Argument error\", status: \"500\" }";
}
?>
