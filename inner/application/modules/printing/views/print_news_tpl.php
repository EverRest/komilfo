<?php defined('ROOT_PATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="uk">
<head>
	<base href="<?php echo base_url(); ?>">
	<title><?=$news['title'];?> - <?php if( LANG == 'ua') echo 'версія для друку'; if (LANG == 'ru') echo 'версия для печати'; if (LANG == 'en') echo 'print version';?></title>
	<style type="text/css" media="screen">
		BODY,HTML {background:#fff; font:12px/18px Tahoma; margin:10px 15px 30px 25px; padding:0;}
		U {clear:both; display:block;}
		H1,H2,H3 {line-height:26px;}
		.for_H1 {width:700px; position:relative;}
		.for_H1 H1 {font:18px Tahoma; margin:0 0 20px 0; width:683px;}
		A.print {width:18px; height:17px; top:7px; right: -20px; color:#5d5d5d; font:11px Tahoma; background:url(/img/printer.png) no-repeat; position:absolute; text-decoration:none;}
	</style>
	<style type="text/css" media="print">
		BODY,HTML {background:#fff; font:12px/18px Tahoma; margin:10px 15px 30px 25px; padding:0;}
		U {clear:both; display:block;}
		H1,H2,H3 {line-height:26px;}
		H1 {font:18px Tahoma;}
		.print {display:none;}
	</style>
</head>
<body>
	<div class="for_H1">
		<h1><?=$news['title'];?></h1><a href="#" class="print" onclick="window.print();return false;">&nbsp;</a>
	</div>
	<div>
		<?=stripslashes($news['text']);?>
	</div>
</body>
</html>