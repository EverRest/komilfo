<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?
// echo "<pre>";
// print_r($news);
// echo "</pre>";exit();
?>
<?php if (count($news) > 0): ?>
<div class="news pbs">
    <div class="container">
        <div class="title-main row"><?=lang('news')?></div>
        <div class="list-news row">
            <?php foreach ($news as $key => $val): ?>
                <?php $url = $this->uri->full_url($val['url']); ?>
                <div class="item-news col-sm-12 col-xs-24">
                    <div class="inner-news row">
                        <figure class="photo-it-nw bg-box col-sm-12 col-xs-24">
                            <a href="<?=$url?>">
                                <img src="<?=base_url('upload/news/'.get_dir_code($val['news_id']).'/'.$val['news_id'].'/t_'.$val['image'])?>" alt="">
                                <figcaption><span><?=lang("deatils")?></span></figcaption>
                            </a>
                        </figure>
                        <div class="description-it-nw col-sm-12 col-xs-24"><div><a href="<?=$url?>"><?=$val['title'];?></a></div></div>
                    </div>
                </div>
            <?endforeach;?>
        </div>
        <?=(!empty($pagination))? $pagination : '';?>
    </div>
</div>
<?php endif; ?>
