<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	if (!defined('LANG')) define('LANG', 'ua');
	if (!defined('DEF_LANG')) define('DEF_LANG', 'ua');
?><!DOCTYPE html>
<html lang="uk">
<head>
<meta charset="utf-8">
<title>404 Page Not Found</title>
<link href="/css/base.css" rel="stylesheet">
</head>
<body>
	<div class="page_404">
		<div class="container">
			<div class="er_logo">
				<a href="/<?=(LANG != DEF_LANG ? LANG . '/' : '');?>" class="<?=LANG;?>"><img src="/images/logo.png" alt=""></a>
			</div>
			<div class="text_404">
				<? if(LANG=='ua')echo'<b>404</b> помилка<br>Такої сторінки не існує. Перейти на ';if(LANG=='ru')echo'<b>404</b> ошибка<br>Такой страницы не существует. Перейти на ';if(LANG=='en')echo'<b>404</b> error<br>The requested page does not exist. Back to ';?>
				<a href="/<?=(LANG != DEF_LANG ? LANG . '/' : '');?>"><? if(LANG=='ua')echo'головну';if(LANG=='ru')echo'главную';if(LANG=='en')echo'home';?></a>
			</div>
		</div>
	</div>
</body>
</html>