<?php defined('ROOT_PATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8"/>
	<title><?php echo $subject; ?></title>
	<style type="text/css">
		body, table tr td, table tr th {font: 12px/18px Verdana, Arial, Tahoma, sans-serif;}
		table tr td, table tr th {padding: 5px 0 5px 0;empty-cells: show;}
	</style>
</head>
<body>
	<table align="center" width="900" cellpadding="10" cellspacing="0" border="0" style="border-collapse: collapse;" border="1px">
		<tr border="1px">
			<td align="left" style="padding-bottom: 30px" border="1px">
				<span style="font-size: 14px">Замовлення: <a href="<?php echo base_url(); ?>" style="color: #693319 !important; text-decoration: underline;"><span style="color: #693319"><?php echo $this->config->item('site_name_' . LANG); ?></span></a>.</span>
			</td>
		</tr>
		<tr>
			<td align="left" colspan="2" style="padding-bottom: 30px; border-bottom: 5px solid #f4f4f4;">
				<table align="left" width="100%" cellpadding="5" cellspacing="0" border="0" style="border-collapse: collapse;">
					<tr>
						<td align="left" width="170" >Date:</td>
						<td align="left"><?=date('d.m.Y H:i');?></td>
					</tr>
					<tr>
						<td align="left" width="170" style="border-top: 1px solid #f4f4f4;">Ім'я:</td>
						<td align="left" style="border-top: 1px solid #f4f4f4;"><?=$name;?></td>
					</tr>
					<?php if(isset($phone) && $phone !=''):?>
					<tr>
						<td align="left" width="170" style="border-top: 1px solid #f4f4f4;">Телефон:</td>
						<td align="left" style="border-top: 1px solid #f4f4f4;"><?=$phone;?></td>
					</tr>
					<?php endif;?>
					<?php if(isset($message) && $message !=''):?>
						<tr>
							<td align="left" width="170" style="border-top: 1px solid #f4f4f4;">Текст повідомлення:</td>
							<td align="left" style="border-top: 1px solid #f4f4f4;"><?=$message;?></td>
						</tr>
					<?php endif;?>
					<?php if(isset($email) && $email !=''):?>
						<tr>
							<td align="left" width="170" style="border-top: 1px solid #f4f4f4;">Email:</td>
							<td align="left" style="border-top: 1px solid #f4f4f4;"><?=$email;?></td>
						</tr>
					<?php endif;?>
				</table>
			</td>
		</tr>
		<tr>
			<td style="left">
				&copy; <?php echo date('Y'); ?> <?php echo $this->config->item('site_name_' . LANG); ?><br/>
				<a href="http://www.websufix.com" style="color:#0061b3; font:10px Tahoma; text-decoration:none; margin:0 0 0 17px;"><span style="color:#0061b3;">SUFIX web design</span></a>
			</td>
		</tr>
	</table>
</body>
</html>