<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<?php
		foreach($styleSheets as $styleSheet){
			echo '<link rel="stylesheet" type="text/css" href="'.$styleSheet.'" />'."\n";
		}
	?>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta name="description" content="" />
	<meta name="keywords" content="" />
	<meta name="publisher" content="" />
	<meta name="revisit" content="After 7 Days" />
	<meta name="language" content="English" />
	<title><?php echo $title; ?></title>
	<?php
		foreach($javaScripts as $javaScript){
			echo '<script type="text/javascript" src="'.$javaScript.'"></script>'."\n";
		}
	?>
</head>