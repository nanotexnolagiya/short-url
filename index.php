<?php
require('redirect.php');
$uri = str_replace(array('/'), '', $_SERVER['REQUEST_URI']);
if (strlen($uri) > 0) {
	if ($url = has_redirect_url($uri)) {
		header('Location: '.$url);
		exit();
	}else{
		echo 'Ssilka ne dostupna';
		exit();
	}
} ?>
<!DOCTYPE html>
<html>
<head>
	<title>Home</title>
	<meta charset="utf-8">
  	<meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>



</body>
</html>
