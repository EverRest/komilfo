<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<article class="fm pd_tp_46 pd_bt_60" >
<div class="centre">
    <div class="fm inner_about_us">
        <div class="fm blue_title"><?=$news['title'];?></div>
        <div class="fm text_box"><?=stripslashes($news['text']);?></div>
    </div>
</div>
</article>
<section class="fm support pd_tp_46 pd_bt_60">
    <div class="centre">
        <div class="fm blue_title mr_bt_48">ЗАЛИШИЛИСЬ ПИТАННЯ? ТЕЛЕФОНУЙТЕ НАМ</div>
        <div class="fm phone_place">
            <div class="fm phone_inner">
                <div class="fm phone_item">
                    <div class="logo_phone kyivstar_op"><img src="images/kyivstar.png" alt=""></div>
                    <div class="phone_number"><?=$header_data['kyivstar_'.LANG]?></div>
                </div>
                <div class="fm phone_item">
                    <div class="logo_phone life_op"><img src="images/life.png" alt=""></div>
                    <div class="phone_number"><?=$header_data['life_'.LANG]?></div>
                </div>
                <div class="fm phone_item">
                    <div class="logo_phone mts_op"><img src="images/mts.png" alt=""></div>
                    <div class="phone_number"><?=$header_data['mts_'.LANG]?></div>
                </div>
            </div>
            <div class="fm phone_btn">
                <div class="fm phone_text">або замовте дзвінок онлайн</div>
                <div class="fm button">
                    <a href="#" class="open_form" data-subject="Форма задати питання" data-title="Введіть контактні дані і ми зв'яжемось з вами" data-type="question"><span>ЗАМОВИТИ ДЗВІНОК</span></a>
                </div>
            </div>
        </div>
    </div>
</section>