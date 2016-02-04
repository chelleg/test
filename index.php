<!DOCTYPE html>
	<head>
		<meta charset="UTF-8"/>
		<link rel="stylesheet" href="/style.css">
		
		<link rel="stylesheet" href="js/jquery-ui.css">
		<link rel="stylesheet" href="js/jquery-ui.min.css">
		<link rel="stylesheet" href="js/jquery-ui.structure.css">
		<link rel="stylesheet" href="js/jquery-ui.structure.min.css">
		<link rel="stylesheet" href="js/jquery-ui.theme.css">
		<link rel="stylesheet" href="js/jquery-ui.theme.min.css">
		
		
		<script type="text/javascript" src="js/external/jquery/jquery.js"></script>
		<script type="text/javascript" src="js/jquery-ui.min.js"></script>
		<script type="text/javascript" src="js/jquery-ui.js"></script>
		
		
		<script type="text/javascript">
		
$(document).ready(function() 
{	
	<?include 'json.php';?>;//Подключим массив городов и регионов
	$( "#from" ).autocomplete({
			source: arr,
			dalay:100,
			minLength: 1
		});
	
	$( "#where" ).autocomplete({
			source: arr,
			dalay:100,
			minLength: 1
		});
	
	$("#submit").click(function()
				{
					var from = $("#from").val();
					var where = $("#where").val();
					var weight = $("#weight").val();
					
					if(from == "")               //Посветим пустые ячейки, если такие есть
					{
						$("#from").css("background","#FDF5E6");
					}
					$("#from").click(function()
					{
						$("#from").css("background","none");
					})
					if(where == "")
					{
						$("#where").css("background","#FDF5E6");
					}
					$("#where").click(function()
					{
						$("#where").css("background","none");
					})
					if(weight == "")
					{
						$("#weight").css("background","#FDF5E6");
					}
					$("#weight").click(function()
					{
						$("#weight").css("background","none");
					})
					
					$.post("action.php", 
					{
						from:from,
						where:where,
						weight:weight
					}, 
		
					function(data)
					{
						$("#test").html(data);
					});	
				});
});

	
			

		
		</script>
	</head>
	
	<body>
		  
			<span>Откуда</span><input id="from" type="text" name="from" placeholder="Откуда" /><br/>
					
			<span>Куда</span><input id="where" type="text" name="where" placeholder="Куда" /><br/>
			
			<span>Вес</span><input id="weight" type="text" name="weight" placeholder="Вес посылки, кг"/><br/>
			<div id="submit" >Рассчитать</div>
			
			<!--
			
			<input type="submit" id="submit" value="Рассчитать"/>
			-->
			<div class="test" id="test"></div>
			
	</body>
</html>