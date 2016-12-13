<section class="sect-collections fm">
	<div class="center">
		<div class="collections-wrapper fm">
			<?php foreach ($categories as $key => $category): ?>
				<? $url = $this->uri->full_url($category['url_'.LANG]);?>
				<div class="collection-slider left-slider fm">
					<div class="collection-slider_one fm">
						<a href="<?=$url?>">
							<img src="<?=base_url('upload/menu/'.$category['id'].'/'.$category['image']);?>" alt="<?=$category['name_'.LANG]?>">
							<span class="collection-slider_text">
								<span class="collection-slider_title"><?=lang('tanya_grig')?></span>
								<span class="collection-slider_descr"><?=$category['name_'.LANG]?></span>
							</span>
						</a>
					</div>
				</div>
			<?php endforeach ?>
		</div>
	</div>
</section>