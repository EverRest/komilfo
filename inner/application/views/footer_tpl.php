<?php 
	defined('ROOT_PATH') OR exit('No direct script access allowed');
	$languages_1 = $this->config->item('languages');
    $languages = $this->config->item('database_languages');
?>
<!-------------- Footer -------------->
<footer class="footer-wrapper fm">
    <div class="center">
        <div class="footer-logo fm"><a href="#"><?=($this->config->item('site_name_'.LANG) == '')? $this->config->item('site_name_'.DEF_LANG) : $this->config->item('site_name_'.LANG) ;?></a></div>
        <div class="footer-btn fm"><a href="#" class="common-btn open-popup"><?=lang('consultation')?></a></div>
        <div class="footer-social fm">
            <? if ($static['fb'] != '')?><a href="<?=$static['fb']?>" class="fb" target="_blank"><i class="icon-facebook"></i></a>
        </div>
        <div class="development fmr">
             <a href="<?=lang('websufix_create')?>"><?=lang('sufix_label')?></a> <a href="<?=lang('websufix')?>"><?=lang('sufix_label_2')?></a> "SUFIX"
        </div>
    </div>
</footer>