<?php
function request($url)//получаю массив по запросу
{
	$con = curl_init();
	curl_setopt($con, CURLOPT_URL, $url);
	curl_setopt ($con, CURLOPT_RETURNTRANSFER, 1);
	$content = curl_exec ($con);
	$content = json_decode($content, true);
	curl_close ($con);	
	return $content;
}
function ret_arr($req)//Приводим массив к виду: value=>name
{
	$b = $req[rsp];
	$c=$b[locations];
	$arr = array();
	for($i=0;$i<count($c);$i++)
	{
		 $arr[$c[$i]["value"]]=$c[$i]["name"] ;
	}
	return $arr;
}

$cities = (request("http://emspost.ru/api/rest/?method=ems.get.locations&type=cities&plain=true"));
 
$maxWeight = request("http://emspost.ru/api/rest/?method=ems.get.max.weight");

$regions = request("http://emspost.ru/api/rest/?method=ems.get.locations&type=regions");


if($cities[rsp][stat] == "fail" || $maxWeight[rsp][stat] == "fail"|| $regions[rsp][stat] == "fail")
{
	echo '<div class="er">Возникла ошибка, повторите пожалуйста Ваш запрос</div>';
}
else if($cities == false || $maxWeight == false || $regions == false )
{
	echo '<div class="er">Возникла ошибка, повторите пожалуйста Ваш запрос</div>';
}
else
{
	$maxWeight = $maxWeight[rsp][max_weight];
	$cities = ret_arr($cities);
	$regions = ret_arr($regions);
	$all = array();
	$all = $cities + $regions;
	if($_POST['from'] !="" and $_POST['where']!="" and $_POST['weight']!="")
	{
		$from = trim($_POST['from']);
		$where = trim($_POST['where']);
		$weight = trim($_POST['weight']);
		
		$key1 = array_search($from, $all);
		$key2 = array_search($where, $all);
		
		if($key1 == false )
		{
			echo "<div class='result'>Пожалуйста выберите отправителя из предложенного списка</div>";
		}
		else if($key2 == false )
		{
			echo "<div class='result'>Пожалуйста выберите получателя из предложенного списка</div>";
		}
		else if (is_numeric($weight) == false)
		{
			echo "<div class='result'>
				Вы ошиблись при заполнении поля вес</div>";
		}
		else if($weight<0 or $weight>100)
		{
			echo "<div class='result'>
				Вы ошиблись при заполнении поля вес</div>";
		}
		else if($weight>$maxWeight)
		{
			echo "<div class='result'>
				Вес Вашей посылки превышает домустимую норму - ".$maxWeight." кг</div>";
		}
		else
		{
			$calculation = "http://emspost.ru/api/rest?method=ems.calculate&from=$key1&to=$key2&weight=$weight";
			$calculation = request($calculation);
			if($calculation[rsp][stat] == "fail")
			{
				echo '<div class="er">Возникла ошибка, повторите пожалуйста Ваш запрос</div>';	
			}
			else if($calculation == false)
			{
				echo '<div class="er">Возникла ошибка, повторите пожалуйста Ваш запрос</div>';
			}
			else
			{
				$price = $calculation[rsp][price];//Вывод результатов
				$termMin = $calculation[rsp][term][min];
				$termMax = $calculation[rsp][term][max];
								
				echo "<div class='result'>Стоимость доставки (руб): ".$price."<br/>";
				echo "Срок (дней): ".$termMin." - ".$termMax."</div>";
			}	
		}
	}
	else echo "<div class='result'>Заполните поля формы </div>";	
}
?>