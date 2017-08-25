<?php
header('Content-type: application/json');
//$command = escapeshellcmd('sudo python /opt/thebox/getdoorstate.py');
$command = escapeshellcmd('sudo /opt/thebox/getdoorstate.py');
$output = shell_exec($command);
echo $output;
?>
