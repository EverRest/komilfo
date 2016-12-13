<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php if (isset($reviews) AND count($reviews) > 0): ?>
<div class="fm reviews">
	<div class="centre">
		<div class="fm title"><b><? if(LANG=='ua')echo'ВІДГУКИ ПРО НАС';if(LANG=='ru')echo'Отзывы наших клиентов';if(LANG=='en')echo'Reviews of our customers';?></b></div>
		<div class="fm main_reviews">
			<div class="fm main_r">
				<?php foreach ($reviews as $k => $v): ?>
					<div class="fm one_reviews">
						<?php if ($v['image'] != ''): ?><img src="/upload/reviews/<?=$this->init_model->dir_by_id($v['review_id']);?>/<?=$v['review_id'];?>/<?=$v['image'];?>" alt="" ><?php endif; ?>
						<?php if ($v['logo'] != ''): ?><div class="fm or_logo"><div><img src="/upload/reviews/<?=$this->init_model->dir_by_id($v['review_id']);?>/<?=$v['review_id'];?>/<?=$v['logo'];?>" alt="" ></div></div><?php endif; ?>
						<div class="fm rev_desc">
							<b><?=$v['title'];?></b>
							<?=$v['text'];?>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</div>
<?php endif; ?>
