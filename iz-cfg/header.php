<?php
	
	if (!isset($title)) {
		$title = 'iz-jQuery-Bootstrap-Skeleton';
	}
		
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta name="apple-mobile-web-app-capable" content="yes" />
<meta name="apple-mobile-web-app-status-bar-style" content="black" />
<meta name="viewport" content="width=device-width">

<title><?php echo $title ?></title>

<?php if (isset($csslink)) { echo $csslink; } ?>
<?php if (JQUERY === 1) { ?>
  
        <!--[if lte IE 8]>
            <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
            <script>window.jQuery || document.write('<script src="/js/jQuery-1.10.1/jQuery.min.js"><\/script>');</script>
        <![endif]-->
        <!--[if gt IE 8]><!-->
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="/js/jQuery-2.0.2/jQuery.min.js"><\/script>');</script>
        <!--<![endif]-->
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">

<!-- Latest compiled JavaScript -->
<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
<?php } ?>
<?php if (isset($jslink)) { echo $jslink; } ?>
        
</head>

<body>
