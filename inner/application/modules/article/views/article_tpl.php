<?php defined('ROOT_PATH') OR exit('No direct script access allowed'); ?>

<? //$article['text'] = str_replace('<td>&nbsp;</td>', '<td><div class="fm detail_btn" style="text-align: center;"><a href="" class="open_form" data-component="order"  data-title="Замовлення" data-subject="Замовлення" data-type="order">ЗАМОВИТИ</a></div></td>', $article['text']); ?>
<article id="article" class="fm centre">
    <div class="container">
        <div class="col-sm-12">
            <h2 class='section-title aos-init aos-animate' data-aos="fade-up"><?=$article['title'];?></h2>
            <div class='aos-init aos-animate' data-aos="fade-up"><?=stripslashes($article['text']);?></div>
        </div>
    </div>
</article>