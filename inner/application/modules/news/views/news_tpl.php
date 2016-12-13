<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php if (count($news) > 0): ?>
<!-------------- товари -------------->
<section class="fm services pd_tp_46 pd_bt_33" id="services">
	<div class="centre">
	    <div class="fm blue_title mr_bt_48">НАШІ ПОСЛУГИ</div>
	    <div class="fm services_place">
			<?php foreach ($news as $v): ?>
				<?php $url = $this->uri->full_url((($v['main'] == 0) ? $v['menu_url'] . '/' : '') . $v['url']); ?>
				<div class="fm services_item">
					<?php if($v['image'] != ''):?>
		                <div class="fm image_services">
		                    <a href="<?=$url?>" class="open_form" data-component="services" data-text='<?=$v['news_id'];?>' data-title="<?=$v['title'];?>" data-subject="Форма наші послуги - <?=$v['title'];?>" data-type="services"><div class="image_inner"><div><img src="/upload/news/<?=$this->init_model->dir_by_id($v['news_id']);?>/<?=$v['news_id'];?>/t_<?=$v['image'];?>" alt="<?=$v['title'];?>" alt=""></div></div></a>
		                </div>
		                <a href="<?=$url?>" class="open_form" data-component="services" data-text="<?=$v['news_id'];?>" data-title="<?=$v['title'];?>" data-subject="Форма наші послуги - <?=$v['title'];?>" data-type="services"><div class="fm small_title"><?=$v['title'];?></div></a>
		                <div class="fm descriptio_services"><?=$v['anons_'.LANG];?></div>
		                <div class="fm detail_btn"><a href="<?=$url?>" class="open_form" data-component="services" data-text="<?=$v['news_id'];?>" data-title="<?=$v['title'];?>" data-subject="Форма наші послуги - <?=$v['title'];?>" data-type="services">ДЕТАЛЬНІШЕ</a></div>
		            <?endif;?>
	            </div>
			<?php endforeach;?>
		</div>
    </div>
	<?php if ($pagination != '') echo $pagination; ?>
</section>
<?php endif; ?>
