<?php
	// PhilViz
	// @author: Daisy Luo and Tom Jin
	
	$width = $_POST['width'];
	$height = $_POST['height'];
	$svg = $_POST['data']; // TODO
	
	//$x_charts_required = 1;
	$x_charts_required = 4;
	if (isset($width)) {
		$x_charts_required = ceil($width / 8);
	}
	
	//$y_charts_required = 1;
	$y_charts_required = 1;
	if (isset($height)) {
		$y_charts_required = ceil($height / 20);
	}
	
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
    	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    	<meta name="viewport" content="width=device-width, initial-scale=1">
    
		<title>PhilViz - Create Great Argument Maps</title>
		
		<!-- Required CSS -->
		<link href="css/raphael.pan-zoom.css" rel="stylesheet" type="text/css" />
		<link href="css/philviz.css" rel="stylesheet" type="text/css" />
		<link href='http://fonts.googleapis.com/css?family=Rokkitt' rel='stylesheet' type='text/css'>

		<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
		  <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
		  <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
		<![endif]-->
	</head>
	<body>

		<div id="chart-content" class="content-container" style="">
			<?php
				for ($i = 0; $i < $x_charts_required; $i++) {
					for ($j = 0; $j < $y_charts_required; $j++) {
						echo '<div id="chart' . $i . '-' . $j . '" style="margin: 0px;overflow:hidden;position:relative;"></div>';
					}
				}
			?>
		</div><!-- /.container -->
		
		<!-- Required JS Scripts -->
		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.23/jquery-ui.min.js"></script>
		<script src="http://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
		<script src="js/raphael.orgbox.js"></script>
		<script src="js/jquery.widgets.orgchart.js"></script>
		<script src="js/raphael.pan-zoom.js"></script>
		
		<script type="text/javascript">
			$(function(exports) {
				var _colors = {
						blue : "#4169E1",
						green : "#008000",
						brown : "#A52A2A",
						lightgreen : "#90EE90",
						chocolate : "#D2691E",
						sienna : "#A0522D",
						peru : "#CD853F",
						orange : "#FFA500"
				};
				var _datas = [{
					id : "100001",
					name : "P4",
					value : "Infringing person's right to life may be done only competing right: <br />is someone else's right to control own body",
					color : _colors["blue"],
					pid : null
				}, {
					id : "100002",
					name : "总耗水量(10%)",
					value : "5400.00t",
					color : _colors["green"],
					pid : "100001"
				}, {
					id : "100003",
					name : "总耗电量(60%)",
					value : "4200.00kWh",
					color : _colors["brown"],
					pid : "100001"
				}, {
					id : "100004",
					name : "总耗气量(30%)",
					value : "1230.00m3",
					color : _colors["lightgreen"],
					pid : "100001"
				}, {
					id : "100005",
					name : "照明插座用电(15%)",
					value : "1050.00kWh",
					color : _colors["chocolate"],
					pid : "100003"
				}, {
					id : "100006",
					name : "空调用电(15%)",
					value : "1050.00kWh",
					color : _colors["sienna"],
					pid : "100003"
				}, {
					id : "100007",
					name : "动力用电(15%)",
					value : "1050.00kWh",
					color : _colors["peru"],
					pid : "100003"
				}, {
					id : "100008",
					name : "特殊用电(15%)",
					value : "1050.00kWh",
					color : _colors["orange"],
					pid : "100003"
				}, {
					id : "100009",
					name : "照明与插座(5%)",
					value : "1800.00tce",
					pid : "100005"
				}, {
					id : "100010",
					name : "走廊与应急(100%)",
					value : "1800.00tce",
					pid : "100005"
				}, {
					id : "100011",
					name : "室外景观照明(100%)",
					value : "1800.00tce",
					pid : "100005"
				}, {
					id : "100012",
					name : "冷热站(10%)",
					value : "1800.00tce",
					pid : "100006"
				}, {
					id : "100013",
					name : "空调末端(5%)",
					value : "1800.00tce",
					pid : "100006"
				}, {
					id : "100014",
					name : "电梯(100%)",
					value : "1800.00tce",
					pid : "100007"
				}, {
					id : "100015",
					name : "水泵(100%)",
					value : "1800.00tce",
					pid : "100007"
				}, {
					id : "100016",
					name : "通风机(100%)",
					value : "1800.00tce",
					pid : "100007"
				}, {
					id : "100017",
					name : "信息中心(100%)",
					value : "1800.00tce",
					pid : "100008"
				}, {
					id : "100018",
					name : "洗衣房(100%)",
					value : "1800.00tce",
					pid : "100008"
				}, {
					id : "100023",
					name : "冷冻泵(100%)",
					value : "1800.00tce",
					pid : "100012"
				}, {
					id : "100024",
					name : "冷却泵(100%)",
					value : "1800.00tce",
					pid : "100012"
				}, {
					id : "100024",
					name : "冷却泵1(100%)",
					value : "1800.00tce",
					pid : "100013"
				}, {
					id : "100025",
					name : "冷风机组1(100%)",
					value : "1800.00tce",
					pid : "100013"
				}];
	
				<?php
				for ($i = 0; $i < $x_charts_required; $i++) {
					for ($j = 0; $j < $y_charts_required; $j++) {
					?>
					var $chart<?php echo $i . $j; ?> = $("#chart<?php echo $i . '-' . $j; ?>");
					$chart<?php echo $i . $j; ?>.orgchart({
						width : 0,
						height : 0,
						print : true,
						printX : <?php echo $i; ?>,
						printXTot : <?php echo $x_charts_required; ?>,
						printY : <?php echo $j; ?>,
						printYTot : <?php echo $y_charts_required; ?>,
						rawDatas : _datas
					});
				
					$chart<?php echo $i . $j; ?>.orgchart("draw", _datas);
					$chart<?php echo $i . $j; ?>.width(Math.ceil($chart<?php echo $i . $j; ?>.width() / <?php echo $x_charts_required; ?>));
					$chart<?php echo $i . $j; ?>.height($chart<?php echo $i . $j; ?>.height() / <?php echo $y_charts_required; ?>);
					//$('#chart' + ops.printX + "" + ops.printY).width(ops.width / ops.printXTot).height(ops.height / ops.printYTot);
			
			
					<?php
					}
				}
				?>
			});
		</script>
	</body>
</html>