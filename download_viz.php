<?php
	if (isset($_POST["viz_svg"])) {
		header("Content-Disposition: attachment; filename=\"diagram.png\"");
		header("Content-Type: application/force-download");
		header("Connection: close");
		$image = new Imagick();
		$image->readImageBlob($_POST["viz_svg"]);
		$image->setImageFormat("png24");
		$image->resizeImage(1024, 768, imagick::FILTER_LANCZOS, 1); 

		echo $image;
	}
?>