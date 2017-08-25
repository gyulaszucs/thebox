<?php

class returndata
{
    public $pin;
    public $counter;
    public $stockcounter;
    public $renteditmname;
    public $renteditmpath;
    public $itempinstatus;

}

function patchRequest($url,$data){
 //print_r($url);
 //print_r($data);
  $headers = array('Content-Type: application/json');
  $curl = curl_init();
  curl_setopt($curl, CURLOPT_URL, $url);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_NTLM);
  curl_setopt($curl, CURLOPT_USERPWD, 'koroshegyib@sn.hu:M4nch3st3r');
  curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PATCH');
  curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
  curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
  $response = curl_exec($curl);

  return $response;
}


function deleteRequest($url,$data){
  $headers = array('Content-Type: application/json');
  $curl = curl_init();
  curl_setopt($curl, CURLOPT_URL, $url);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_NTLM);
  curl_setopt($curl, CURLOPT_USERPWD, 'koroshegyib@sn.hu:M4nch3st3r');
  curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PATCH');
  curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
  curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
  $response = curl_exec($curl);

}

function checkpin($pin) {
    $url = "http://thebox.sn.hu/OData.svc/Root/Sites/Store/PIN%20Collector%28'".$pin."'%29?\$expand=VMRequestedItems&\$select=Name,VMItemPINCounter,VMPINStatus,VMRequestedItems/Index,VMRequestedItems/VMItemCount,VMRequestedItems/Path,VMRequestedItems/Name&metadata=no";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_NTLM);
    curl_setopt($ch, CURLOPT_USERPWD, 'koroshegyib@sn.hu:M4nch3st3r');
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $data = curl_exec($ch);
   // echo CURLOPT_USERPWD;
  //  echo $url;
   //   echo curl_errno($ch);
 //     echo curl_getinfo($ch, CURLINFO_HTTP_CODE);
    if (!curl_errno($ch)) {
        switch ($http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE)) {
            case 200:  # OK
            $myjson = json_decode($data);
            $shelfnumber=$myjson->d->VMRequestedItems->Index;
            $stockcounter=$myjson->d->VMRequestedItems->VMItemCount;

            $renteditmname=$myjson->d->VMRequestedItems->Name;
            $renteditmpath=$myjson->d->VMRequestedItems->Path;


            $itempincounter=$myjson->d->VMItemPINCounter;
            $itempinstatus=$myjson->d->VMPINStatus;
         //   $array = [$shelfnumber,$itempincounter];

            $rdata = new returndata;
            $rdata->shelfnumber = $shelfnumber;
            $rdata->counter = $itempincounter;
            $rdata->status = $itempinstatus;
            $rdata->stockcounter = $stockcounter;
            $rdata->renteditmname = $renteditmname;
            $rdata->renteditmpath = $renteditmpath;

            return $rdata;
        default:
            return 666;
        }
    }
}

      $info_message = "";
      $message="";
      $taki= "style='display:none;'";
      $taki2= "style='display:none;'";
       $taki3= "style='display:none;'";



if(isset($_POST["pincode"])){
   
     
    $mypin=$_POST["pincode"];
  //  echo "Open box with PIN: ".$mypin."</br>";
    if($mypin==425939){

             $info_message = "Admin Horror!";
             $shelfnumber= 0;
              $taki2= "";
               $taki3= "";

    }else{
        $serverresult = checkpin($mypin);
        switch ($shelfnumber = $serverresult->shelfnumber) {

            case 666:
                $message = "Not existing PIN! :( </br>";
                 $shelfnumber= 666;
                 $taki= "";

               //  $info_message = "Please open shelf number: ".$shelfnumber."</br>";

                break;

            default:

    if ($serverresult->status[0] == "Active" || $serverresult->status[0] == "Rented") {

                if ($serverresult ->counter === 0 ) {
                    //Első kivétel
              
                          $url =  "http://thebox.sn.hu/OData.svc/Root/Sites/Store/PIN%20Collector('".$mypin."')";
                          $data = json_encode(array(
                              "VMItemPINCounter"  => intval($serverresult->counter)+1,
                              "VMPINStatus" => "Rented"
                             ));

                              $info_message = "Please open shelf number: ".$shelfnumber."</br>";
                            $taki3= "";
                            $vmi = "";

                           patchRequest($url,$data);

                      }else{

                          $info_message = "Please open and return item to shelf number: ".$shelfnumber."</br> ";
                            $taki3= "";
                            $vmi = "";
                        //   $url = "http://thebox.sn.hu/OData.svc/Root/Sites/Store/Vending%20Store/Rental%20Items/('XBox')".
                            $tet = "/".$serverresult->renteditmname;
                            $path = explode($tet, $serverresult->renteditmpath);
                             $pinurl =  "http://thebox.sn.hu/OData.svc/Root/Sites/Store/PIN%20Collector('".$mypin."')";
                              $url = "http://thebox.sn.hu/OData.svc/Root/Sites/Store/Vending%20Store/Rental%20Items/('".$serverresult->renteditmname."')";
                        //  echo $url;

                           $data = json_encode(array(
                              "VMItemCount"  => 1
                             ));

                            $pindata = json_encode(array(
                              "VMItemPINCounter"   => 0,
                              "VMPINStatus" => "Dissolved"
                             ));

                              patchRequest($url,$data);
                              deleteRequest($pinurl,$pindata);
                      }
                                                                                     
                 }else{

                 $message = "Not existing PIN! :( </br>";
                 $shelfnumber= 666;
                 $taki= "";


                 }

        }    
    }
    
  //  echo $shelfnumber;
}


?>
<html>
    <head>
    <link rel="stylesheet" href="style.css">    
    <script src="jquery.js" type="text/javascript"></script>

    

    </head>
    <body>
        
        <div id="wrapper">

        <div <?php echo $taki; ?> id="message">
            <p><?php echo $message; ?></p>
        </div>

         <div id="info_message">

            <p><?php echo $info_message; ?></p>
            <p class="countdown"></p>
             <div <?php echo $taki2; ?> class="admin_button" id="all_open"><p>Open All</p></div>
                <div <?php echo $taki2; ?> class="admin_button" id="all_close"><p>Close All</p></div>
                <div <?php echo $taki2; ?> class="admin_button" id="quit"><p>Return</p></div>
         </div>

        <div <?php echo $taki3; ?> id="thebox">
             <div class="row2">
                <div class="shelf" id="shelf_1"><p>1</p></div>
                <div class="shelf" id="shelf_3"><p>3</p></div>
                <div class="shelf" id="shelf_5"><p>5</p></div>
                <div class="shelf" id="shelf_8"><p>8</p></div>
                <div class="shelf" id="shelf_11"><p>11</p></div>
                <div class="shelf" id="shelf_14"><p>14</p></div>
                <div class="shelf" id="shelf_17"><p>17</p></div>
             </div> 
             <div class="row2">

                <div id="rpi3"></div>
                <div class="shelf" id="shelf_6"><p>6</p></div>
                <div class="shelf" id="shelf_9"><p>9</p></div>
                <div class="shelf" id="shelf_12"><p>12</p></div>
                <div class="shelf" id="shelf_15"><p>15</p></div>
                <div class="shelf" id="shelf_18"><p>18</p></div>
                
                 
             </div>
             <div class="row2">

              
                <div class="shelf" id="shelf_2"><p>2</p></div>
                <div class="shelf" id="shelf_4"><p>4</p></div>
                <div class="shelf" id="shelf_7"><p>7</p></div>
                <div class="shelf" id="shelf_10"><p>10</p></div>
                <div class="shelf" id="shelf_13"><p>13</p></div>
                <div class="shelf" id="shelf_16"><p>16</p></div>
                <div class="shelf" id="shelf_19"><p>19</p></div>
                 
                 
             </div>  
                         
                
        </div>
           
        </div>
        
    </body>
    
</html>
<script>

if(<?php echo $shelfnumber; ?> != 0 && <?php echo $shelfnumber; ?> != 666 ){

$('#shelf_'+<?php echo $shelfnumber; ?> ).addClass('open');

var shelfnumber = <?php echo $shelfnumber; ?>;

  $.get("/rest/opendoor.php?p="+shelfnumber);

    // Our countdown plugin takes a callback, a duration, and an optional message
    $.fn.countdown = function (callback, duration, message) {
        // If no message is provided, we use an empty string
        message = message || "";
        // Get reference to container, and set initial content
        var container = $(this[0]).html(message + duration + ' second(s)');
        // Get reference to the interval doing the countdown
        var countdown = setInterval(function () {
            // If seconds remain
            if (--duration) {
                // Update our container's message
                
                container.html(message +'<div style="display:inline-block;" class="counternumber">'+ duration +'</div>'+ ' second(s)');
                if(duration<10){

                    $('.counternumber').css({'background':'red','width':'55px', 'height':'55px', 'border-radius':'50px', 'margin':'0', 'padding':'8px 5px 0 5px'});
                   
                }
            // Otherwise
            } else {
                // Clear the countdown interval
                clearInterval(countdown);
                // And fire the callback passing our container as `this`
                callback.call(container);   
            }
        // Run interval every 1000ms (1 second)
        }, 1000000);

    };

    // Use p.countdown as container, pass redirect, duration, and optional message
    $(".countdown").countdown(redirect, 10, "Shelf will close in ");

    // Function to be called after 5 seconds
    function redirect () {
        this.html("Shelf is closed. Good Bye!");
           $.get("/rest/closedoor.php?p="+shelfnumber);

        setTimeout(function(){
            window.location = "index.php";
        }, 5000);
       
      
    }

}



else{
    if(<?php echo $shelfnumber; ?> == 666){

        setTimeout(function(){
            window.location = "index.php";
        }, 5000);

    }else{

        $('.shelf').on('click', function (){

              $(this).toggleClass("open");
			  var index = $(this).find("p").html();
			  if($(this).hasClass("open")){
				  $.get("/rest/opendoor.php?p="+index);
			  }
			  else{
				  $.get("/rest/closedoor.php?p="+index);
			  }
			 
        });

        $('#all_close').on('click', function (){

              $('.shelf').removeClass("open");

        });

         $('#all_open').on('click', function (){

             $('.shelf').addClass("open");

        });

         $('#quit').on('click', function (){

              window.location = "index.php";

        });

         

    }
}

</script>

