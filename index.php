
<html>
<head>
<style>
	#printTable{
		width:900px;
		margin:0 auto;
		border:1px solid black;
		border-collapse:collapse;
	}
	#printTable th{
		border:1px solid black;
	}
	#printTable td{
		border:1px solid black;
		text-align:center;
	}
	
</style>
<script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
  crossorigin="anonymous"></script>
<script>
	$(function(){ //calling the api using jquery get method
		$.get("https://api.spacexdata.com/v2/launches",function(obj){
				//here we sending the data to our file and displaying the content of the file inside our investment div
				$.post("investment.php",{obj:JSON.stringify(obj)},function(data){
					$("#investment").html(data);
				});
		});
	});
</script>
</head>
<body>
	<div id="investment"></div>
</body>
</html>
