<?php defined('ROOT_PATH') or exit('No direct script access allowed');

	/**
	 * @var string $page_title
	 * @var string $page_description
	 * @var string $page_keywords
	 */
	$is_admin = $this->init_model->is_admin();
	$is_main = $this->init_model->is_main();
	$menu_id = $this->init_model->get_menu_id();
	$languages = $this->config->item('database_languages');

	$url = (string) $this->uri->segment(LANG == DEF_LANG ? 1 : 2 );
 	
?>
<!DOCTYPE html>
<html lang="<?=(LANG === 'ua') ? 'uk' : LANG;?>">
<head>
	<base href="<?=base_url();?>">
	<meta charset="utf-8">

	<?php if (isset($page_title)): ?><title><?=$page_title;?></title><?="\n";?><?php endif; ?>
	<?php if (isset($page_description) AND $page_description !== ''): ?><meta name="description" content="<?=$page_description;?>"><?="\n";?><?php endif; ?>
	<?php if (isset($page_keywords) AND $page_keywords !== ''): ?><meta name="keywords" content="<?=$page_keywords;?>"><?="\n";?><?php endif; ?>

	<?php if (isset($page_title)): ?><meta property="og:title" content="<?=$page_title;?>"><?="\n";?><?php endif; ?>
	<?php if (isset($page_description)): ?><meta property="og:description" content="<?=$page_description;?>"><?="\n";?><?php endif; ?>
	<?php if (isset($og_image)): ?><meta property="og:image" content="<?=$og_image;?>"><?="\n";?><?php endif; ?>
	<meta property="og:url" content="<?=$this->config->site_url($this->uri->uri_string());?>"><?="\n";?>
	<meta charset="utf-8">

	<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600&subset=cyrillic" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Poiret+One&subset=cyrillic" rel="stylesheet">

	<link href="css/tanyagrig.css" rel="stylesheet">
	<link href="css/slick.css" rel="stylesheet">
	<!-- CSS base style -->
	<link href="css/base.css" rel="stylesheet">
	<link href="css/adaptive.css" rel="stylesheet">
	<link href="css/adaptive_menu.css" rel="stylesheet">
	<link rel="shortcut icon" href="favicon.ico">
	<meta name="viewport" content="initial-scale=1.0, width=device-width, user-scalable=yes, minimum-scale=1.0, maximum-scale=2.0">	
	<?=isset($page_css) ? $page_css : '';?>
	<?php if (isset($photos_block_style)): ?><style><?=implode(' ', $photos_block_style);?></style><?php endif; ?>
	
	<!--[if IE]><script src="<?=base_url('js/html5.js');?>"></script><![endif]-->
	<script type="text/javascript" src="<?=base_url('js/jquery.js');?>"></script>
	<?=isset($page_javascript) ? $page_javascript : '';?>
	<script type="text/javascript" src="<?=base_url('js/lang/' . LANG . '.js');?>"></script>
	<script type="text/javascript" src="<?=base_url('js/scripts.js?v=1');?>"></script>

	<script type="text/javascript" src="<?=base_url('js/mega-dropdown/modernizr.js');?>"></script>
    <script type="text/javascript" src="<?=base_url('js/mega-dropdown/jquery.menu-aim.js');?>"></script>
    <script type="text/javascript" src="<?=base_url('js/mega-dropdown/main.js');?>"></script>

	<script type="text/javascript">
		LANG = '<?=LANG;?>';
		DEF_LANG = '<?=$this->config->item('def_lang');?>';
		C_PREFIX = '<?=$this->config->item('cookie_prefix');?>';
		$.ajaxSetup({data: {'<?=$this->security->get_csrf_token_name();?>': '<?=$this->security->get_csrf_hash();?>'}});
	</script>
	<?if(!$this->init_model->is_admin()): ?>
		<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?v=3&sensor=true"></script>
	<?endif;?>
    <script>
	  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
	  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
	  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
	
	  ga('create', 'UA-76972411-1', 'auto');
	  ga('send', 'pageview');
	
	</script>
</head>
<body>
	<?$static['menu_id'] = $menu_id;?>
	<div class="for-footer-bottom fm">
		<?=$this->load->view('header_tpl', $static, true);?>
		<section class="main-col fm">
			<?php if (isset($page_content)): ?><?=$page_content;?><?php endif; ?>
		</section>
	</div>
	<?=$this->load->view('footer_tpl', $static, true);?>
	<div class="black"></div>
	<div class="popup">
		<a href="#" class="close-popup"></a>
		<div class="popup-head fm"><?=lang('consultation')?></div>
		<div class="popup-main fm">
			<div class="input-place fm">
				<div class="input-item fm">
					<label for="popup-name"><?=lang('name')?></label>
					<input id="popup-name" name="name" type="text">
				</div>
				<div class="input-item fm">
					<label for="popup-name">Email</label>
					<input id="popup-name" name="email" type="text">
				</div>
				<div class="input-item fm">
					<label for="popup-tel"><?=lang('phone')?></label>
					<input id="popup-tel" name="phone" type="text">
				</div>
				<div class="input-item fm">
					<label for="popup-text"><?=lang('message')?></label>
					<textarea id="popup-text" name="message"></textarea>
				</div>
			</div>
			<div class="box-c fm">
				<a href="#" id="send_message" class="common-btn"><?=lang('submit')?></a>
			</div>
		</div>
	</div>
</body>
</html>