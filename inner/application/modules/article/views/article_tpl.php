<?php defined('ROOT_PATH') OR exit('No direct script access allowed'); ?>

<? //$article['text'] = str_replace('<td>&nbsp;</td>', '<td><div class="fm detail_btn" style="text-align: center;"><a href="" class="open_form" data-component="order"  data-title="Замовлення" data-subject="Замовлення" data-type="order">ЗАМОВИТИ</a></div></td>', $article['text']); ?>
<article class="fm <?=($article['background_fone'] == 1)? 'about_us_fone' : 'about_us' ;?> pd_tp_46 pd_bt_60" id="<?=($article['btn_active'] == 1)? 'price' : 'about_ua';?>">
<div class="centre">
    <div class="fm inner_about_us">
        <div class="fm blue_title white_title"><?=$article['title'];?></div>
        <div class="fm text_box"><?=stripslashes($article['text']);?></div>
    </div>
</div>
</article>