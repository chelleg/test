<?php
function request($url)
{
	$con = curl_init();
	curl_setopt($con, CURLOPT_URL, $url);
	curl_setopt ($con, CURLOPT_RETURNTRANSFER, 1);
	$content = curl_exec ($con);
	$content = json_decode($content, true);
	curl_close ($con);
	return $content;
}

function ret_arr($req)
{
	$b = $req[rsp];
	$c=$b[locations];
	$arr = array();
	$j = 0;
	$z = 0;
	for($i=0;$i<count($c)*2;$i+=2)
	{
		$arr[$i] = $c[$z]["value"];
		$j=$i+1;
		$arr[$j] = $c[$z]["name"];
		$z++;
	}
	return $arr;
}

$citi = request("http://emspost.ru/api/rest/?method=ems.get.locations&type=cities&plain=true");
$region = request("http://emspost.ru/api/rest/?method=ems.get.locations&type=regions");

$citi = ret_arr($citi);
$region = ret_arr($region);

$regions = array_merge($citi, $region);
 
echo "var arr = ['".$regions[1]."'";// Формируем массив городов и регионов 
									//для дальнейшей работы с ним в js
for($i=3; $i<count($regions);$i+=2)
{
	echo ", '".$regions[$i]."'";
}

echo "]";

?>