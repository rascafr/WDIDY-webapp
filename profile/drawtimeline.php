<?php
	$img = imagecreate(200, 200);
	$bg = imagecolorallocate($img, 249, 249, 249);
	$col = imagecolorallocate($img, 91, 30, 24); 
	imagearc($img, 100, 100, 50, 50, 0, 350, $col); 
	imagesetthickness ($img ,5);
	header("Content-type: image/png");
	imagejpeg($img,NULL,100000);
	imagedestroy($img);
?>
