<?php
	
	session_start();
	
	$new_upload = false;
	$viz_file = "";
	if (isset($_SESSION["new_upload"])) {
		if ($_SESSION["new_upload"] === true) {
			$new_upload = true;
			$viz_file = $_SESSION["viz_file"];
			$_SESSION["new_upload"] = false;
		}
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

		<!-- Bootstrap -->
		<link href="css/bootstrap.min.css" rel="stylesheet">

		<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
		  <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
		  <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
		<![endif]-->
	</head>
	<body>
	
		<div id="blackout" class="blackout"></div>
		<div id="editbox" class="editbox box-shadow">
			<a href="#" id="edit-close" class="box-close-btn">Close</a>
			<div class="editbox-head cfont">
				Edit Node
			</div>
			<div class="editbox-body">
				<textarea placeholder="Write the contents of the node..." id="edit-text" class="form-control" rows="5"></textarea>
				<p>
					<button id="edit-save" type="button" class="btn btn-success">Save</button>
					<button id="edit-cancel" type="button" class="btn btn-primary">Cancel</button>
				</p>
			</div>
		</div>
		<div id="addbox" class="addbox box-shadow">
			<a href="#" id="add-close" class="box-close-btn">Close</a>
			<div class="addbox-head cfont">
				Add Node
			</div>
			<div class="addbox-body">
				<textarea placeholder="Write the contents of the node..." id="add-text" class="form-control" rows="5"></textarea>
				<p>
					<button id="add-save" type="button" class="btn btn-success">Save</button>
					<button id="add-cancel" type="button" class="btn btn-primary">Cancel</button>
				</p>
			</div>
		</div>
		<div id="parent-addbox" class="addbox box-shadow">
			<a href="#" id="parent-add-close" class="box-close-btn">Close</a>
			<div class="addbox-head cfont">
				Add Parent Node
			</div>
			<div class="addbox-body">
				<textarea placeholder="Write the contents of the node..." id="parent-add-text" class="form-control" rows="5"></textarea>
				<p>
					<button id="parent-add-save" type="button" class="btn btn-success">Save</button>
					<button id="parent-add-cancel" type="button" class="btn btn-primary">Cancel</button>
				</p>
			</div>
		</div>
		<div id="aboutbox" class="addbox box-shadow">
			<a href="#" id="about-close" class="box-close-btn">Close</a>
			<div class="addbox-head cfont">
				About
			</div>
			<div class="addbox-body">
				<p>
					<strong>WHATELEY Chart Application</strong><br />
					<br />
					<br />
					Developed by Daisy Luo and Tom Jin for Phil 220.
					<br />
					Version 1.0
					<br />
					<a href="http://www.github.com/tbj128/PhilViz" target="_blank">Source Code on Github</a>
				</p>
			</div>
		</div>
	
		<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
			<div class="container">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="navbar-brand cfont clight" href="index.php">PhilViz<super>*</super></a>
				</div>
				<nav class="collapse navbar-collapse bs-navbar-collapse" role="navigation">
					<ul class="nav navbar-nav">
						<li>
							<a id="nav-new" href="javascript:void(0);">New</a>
						</li>
						<li>
							<a id="nav-open" href="javascript:void(0);">Open</a>
						</li>
						<li>
							<a id="nav-save" href="javascript:void(0);">Save</a>
						</li>
						<li>
							<a id="nav-print" href="javascript:void(0);">Download SVG</a>
						</li>
					</ul>
					<ul class="nav navbar-nav navbar-right">
						<!-- <li><a id="nav-help" href="help.php" target="_blank">Help</a></li>-->
						<li><a id="nav-about" href="javascript:void(0);">About</a></li>
					</ul>
				</nav>
			</div>
		</div>
		<div id="editbar" class="navbar navbar-inverse navbar-fixed-top navbar-secondary" role="navigation">
			<div class="container">
				<nav class="collapse navbar-collapse bs-navbar-collapse" role="navigation">
					<ul class="nav navbar-nav">
						<li>
							<a id="nav-edit-edit" href="#"><i class="glyphicon glyphicon-edit"></i> Edit Node</a>
						</li>
						<li>
							<a id="nav-edit-addparent" href="#" style="display:none;"><i class="glyphicon glyphicon-plus"></i> Add Parent Node</a>
						</li>
						<li>
							<a id="nav-edit-add" href="#"><i class="glyphicon glyphicon-plus"></i> Add Child Node</a>
						</li>
						<li>
							<a id="nav-edit-remove" href="#"><i class="glyphicon glyphicon-remove"></i> Delete Node</a>
						</li>
					</ul>
				</nav>
			</div>
		</div>
		
		<div id="welcome" class="welcome">
			<a href="#" id="welcome-close" class="box-close-btn">Close</a>
			<div class="welcome-head cfont">
				<h1>Hi there. <small>Ready to make some diagrams?</small></h1>
			</div>
			<div class="welcome-body">
				<div style="float:left;">
					<br />
					<p>
						<button id="welcome-new" type="button" class="btn btn-default"><i class="glyphicon glyphicon-file"></i>&nbsp;&nbsp;New Diagram</button>
					</p>
					<button id="welcome-open" type="button" class="btn btn-default"><i class="glyphicon glyphicon-open"></i>&nbsp;&nbsp;Open Existing Diagram</button>
					
				</div>
				<div style="float:right;">
					<img src="img/sample.png" style="float:right;">
				</div>
				<div style="clear:both;padding-top:18px;">
					<div style="float:left;width:92%">
						<div class="alert alert-info"><strong>Tip&nbsp;&nbsp;</strong> Deleting a node will also delete any child nodes.</div>
					</div>
					<div style="float:right;width:6%">
						<button type="button" class="btn btn-default" onclick="window.open('help.php')"><i class="glyphicon glyphicon-question-sign"></i></button>
					</div>
				</div>
			</div>
		</div>
		
		<div id="chart-content" class="content-container" style="height:100%;position:relative;overflow:hidden;">
			<div id="chart" style="width: 100%; margin: 0px;">
			</div>
		</div><!-- /.container -->
		
		<!--<div id="chart-canvas-container" style="display:none;">
			<canvas id="canvas" width="1000px" height="600px"></canvas> 
		</div>-->
		<div id="chart-png-container" style="display:none;">
		</div>
		
		<!-- Hidden form upload -->
		<form id="open-file-form" action="open_viz.php" method="post" enctype="multipart/form-data">
			<input type="file" accept=".viz" id="open-file" name="viz_file" style="display: none;" />
		</form>
		<form id="save-file-form" action="save_viz.php" method="post">
			<input type="text" id="save-file" name="viz_file" style="display: none;" />
		</form>
		<form id="download-file-form" action="download_viz.php" method="post">
			<input type="text" id="download-file" name="viz_svg" style="display: none;" />
		</form>
		
		<!-- Required JS Scripts -->
		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.23/jquery-ui.min.js"></script>
		<script src="http://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
		<script src="js/raphael.orgbox.js"></script>
		<script src="js/jquery.widgets.orgchart.js"></script>
		<script src="js/raphael.pan-zoom.js"></script>
		<script src="js/philviz.js"></script>
		<script type="text/javascript" src="http://canvg.googlecode.com/svn/trunk/rgbcolor.js"></script> 
		<script type="text/javascript" src="http://canvg.googlecode.com/svn/trunk/StackBlur.js"></script>
		<script type="text/javascript" src="http://canvg.googlecode.com/svn/trunk/canvg.js"></script> 
		<script src="js/canvas2image.js"></script>
		<script src="js/base64.js"></script>
		
		<script type="text/javascript">
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
				
				<?php
					if ($new_upload) {
						echo 'var _datas =' . $viz_file . ';';
					} else {
				?>
					var _datas2 = [{
						id : "100001",
						name : "",
						value : "",
						pid : null
					}];
				<?php
					}
				?>
				
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
				
			$( document ).ready( function() {
	            <?php 
	                if ($new_upload) {
	            ?>
				PhilViz.init(false);
	            <?php
	                } else {
	            ?>
				PhilViz.init(true);
	            <?php
	                }
	            ?>
			});
		</script>
	</body>
</html>