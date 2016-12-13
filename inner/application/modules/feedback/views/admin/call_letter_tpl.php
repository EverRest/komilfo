<?php defined('ROOT_PATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8"/>
	<title><?=$subject;?></title>
	<style type="text/css">
		body, table tr td, table tr th {font: 12px/18px Verdana, Arial, Tahoma, sans-serif;}
		table tr td, table tr th {padding: 5px 0 5px 0;empty-cells: show;}
	</style>
</head>
<body>
<table align="center" width="900" cellpadding="10" cellspacing="0" border="0" style="border-collapse: collapse;">
	<tr>
		<td align="left" colspan="2">
			<span style="font-size: 14px">Замовлення дзвінка</span>
		</td>
	</tr>
	<tr>
		<td align="left" colspan="2" style="padding-bottom: 30px">
			<table align="left" width="100%" cellpadding="5" cellspacing="5" border="0" style="border-collapse: collapse;">
				<tr>
					<td align="left" width="170">Ім’я:</td>
					<td align="left"><?php echo $name; ?></td>
				</tr>
				<tr>
					<td align="left" width="170" style="border-top: 1px solid #f4f4f4;">Телефон:</td>
					<td align="left" style="border-top: 1px solid #f4f4f4;"><?php echo $phone; ?></td>
				</tr>
			</table>
		</td>
	</tr>
</table>
</body>
</html>