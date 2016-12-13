<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php if (isset($reviews) AND count($reviews) > 0): ?>
<div class="fm review">
	<div class="fm rew_title"><? if(LANG=='ua')echo'відгуки наших клієнтів';if(LANG=='ru')echo'Отзывы наших клиентов';if(LANG=='en')echo'Reviews of our customers';?></div>
	<div class="fm rew_box">
	<?php foreach ($reviews as $k => $v): ?>
		<div class="fm one_rew">
			<div class="fm or_photo"><?php if ($v['image'] != ''): ?><img src="/upload/reviews/<?=$this->init_model->dir_by_id($v['review_id']);?>/<?=$v['review_id'];?>/<?=$v['image'];?>" alt="" ><?php endif; ?></div>
			<?php if ($v['logo'] != ''): ?><div class="fm or_logo"><div><img src="/upload/reviews/<?=$this->init_model->dir_by_id($v['review_id']);?>/<?=$v['review_id'];?>/<?=$v['logo'];?>" alt="" ></div></div><?php endif; ?>
			<div class="fm or_txt"><?=$v['text'];?></div>
			<div class="fm or_whu"><?=$v['title'];?></div>
		</div>
	<?php endforeach; ?>
	</div>
	<?php if ($reviews_url != ''): ?><a href="<?=$this->uri->full_url($reviews_url);?>" class="fmr or_all"><? if(LANG=='ua')echo'Всі відгуки';if(LANG=='ru')echo'Все отзывы';if(LANG=='en')echo'All reviews';?></a><?php endif; ?>
</div>
<?php endif; ?>