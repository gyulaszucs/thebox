<?php 
if (isset($_GET['c'])) {
    $command_buzzer = escapeshellcmd('sudo /opt/thebox/buzzer.py');
    if ($_GET['c']=="on") {
        $output = shell_exec($command_buzzer." 0");
    }
    elseif ($_GET['c']=="off") {
        $output = shell_exec($command_buzzer." 1");
    }
    else {
        echo "Invalid parameter";
    }
    if (isset($output)) {
	echo $output;
    }
}
else {
    echo "No GET parameter \"c\"";
}

?>