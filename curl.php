<?php
function checkpin($pin) {
    $url = "http://thebox.sn.hu/OData.svc/Root/Sites/Store/PIN%20Collector%28'".$pin."'%29?\$expand=VMRequestedItems&\$select=Name,VMRequestedItems/Index&metadata=no";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_NTLM);
    curl_setopt($ch, CURLOPT_USERPWD, '_pibox@sn.hu:It2015Services!');
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $data = curl_exec($ch);
    if (!curl_errno($ch)) {
	switch ($http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE)) {
	    case 200:  # OK
	    $myjson = json_decode($data);
	    $shelfnumber=$myjson->d->VMRequestedItems->Index;
	    return $shelfnumber;
	default:
	    return 666;
	}
    }
}

echo "Open box with PIN: 585827</br>";
switch ($shelfnumber = checkpin(585827)) {
    case 666:
	echo "Not existing PIN!</br>";
	break;
    default:
	echo "Please open shelf number: ".$shelfnumber."</br>";
}
echo "</br>";
echo "Open box with PIN: 585828</br>";
switch ($shelfnumber = checkpin(585828)) {
    case 666:
	echo "Not existing PIN!</br>";
	break;
    default:
	echo "Please open shelf number: ".$shelfnumber."</br>";
}

?>
