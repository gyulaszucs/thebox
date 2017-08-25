<?php
    $p = null; 
    if(isset($_GET["p"])){
        $p = $_GET["p"];
        //get state
echo "get s0";
        $state0=GetState();
        var_dump($state0);
	//wait 
        sleep(2);
        //get state
echo "get s1";
        $state1=GetState();
	var_dump($state1);
        //compare
        $sv0 = (int)$state0[$p];        
        $sv1 = (int)$state1[$p];
        if($sv0<$sv1){
            //becsukodott
            //open the door
            Open($p);
            sleep(2);
            //close the door
            Close($p);
        }
        else if($sv0>$sv1){
            //nyitodott
            sleep(2);
            //close the door
            Close($p);
        }
        else{
            //semmi nem történt
        }
    }
    function Open($p){
        $cmd = escapeshellcmd('sudo python /opt/thebox/opendoor.py '.$p);
        $result = shell_exec($cmd);
        return $result;
    }
    function Close($p){
        $cmd = escapeshellcmd('sudo python /opt/thebox/closedoor.py '.$p);
        $result = shell_exec($cmd);
        return $result;
    }
    function  GetState(){
        $cmd = escapeshellcmd('sudo /opt/thebox/getdoorstate.py');
        $result = shell_exec($cmd);
        return $result;
    }
?>
