<?php
header('Content-type: image/gif');

	include_once ($_SERVER['DOCUMENT_ROOT'].'/config.php');
	include_once ($_SERVER['DOCUMENT_ROOT'].'/cms/cms.php');
	$doit = d();
	
	
foreach($_GET as $key=>$value)
{
$mname=$key;
break;
}  
$c="неизвестно";
if(!is_array(d()->plugins)){
	d()->plugins=array(d()->plugins);
}
if(isset(d()->plugins[$mname])){
$c=d()->plugins[$mname];
}


  $im     = imagecreatefromgif("module.gif");
 // $im = imagecreate(120, 30);
  $white = imagecolorallocate($im, 255, 255, 255);
  $black = imagecolorallocate($im, 0, 0, 0);
  
  // Replace path by your own font path
  imagettftext($im,9, 0,35,23, $black, "ubuntu.ttf",  $c);
  imagegif($im);
  imagedestroy($im);
 