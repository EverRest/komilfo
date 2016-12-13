<?php if (isset($news) AND count($news) > 0): ?>
    <div class="detail-news">
        <div class="container">
        	<article>
	            <div class="title-main"><?=$news['title'];?><?php if ((int)$this->config->item('print_icon') === 1): ?><span class="print_icon" data-module="article" data-id="<?=$component_id;?>"></span><?php endif; ?></div>
	            <div class="detail-text row">
	                <div class="fm photo-detail-news bg-box"><img src="<?=base_url('upload/news/'.get_dir_code($news['news_id']).'/'.$news['news_id'].'/b_'.$news['image'])?>" alt="<?=$news['title']?>"></div>
	                <?=stripslashes($news['text']);?>
	            </div>
            </article>
        </div>
    </div>
<?php endif; ?>