<?php
	if (isset($_POST["viz_file"])) {
		header("Content-Disposition: attachment; filename=\"diagram.viz\"");
		header("Content-Type: application/force-download");
		header("Connection: close");
		echo $_POST["viz_file"];
	}
?>