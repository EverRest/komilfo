<?php
	defined('ROOT_PATH') OR exit('No direct script access allowed');

?>
<?php if ($count_results['catalog'] == 0): ?>
	<section class="sect-catalog fm">
		<div class="center">
			<div class="catalog-title fm">
				<h1><?=lang('search_result')?></h1>
				<h2><?=lang('on_request')." \"".$query."\" ".lang('nothing_found')?></h2>
			</div>
		</div>
	</section>
<?php else: ?>
	<?php if (isset($catalog)): ?>
		<section class="sect-catalog fm">
			<div class="center">
				<?php foreach ($catalog as $key => $result): ?>
					
				<?php endforeach ?>
				<div class="catalog-title fm">
					<h1><?=lang('tanya_grig')?></h1>
					<h2><?=$key?></h2>
				</div>
				<div class="catalog-wrapper fm">
					<?foreach ($result as $key => $value): ?>
						<?php $url = $this->uri->full_url($value['url']); ?>
						<div class="catalog-item fm">
							<a href="<?=$url?>">
								<img src="<?=base_url('upload/catalog/'.get_dir_code($value['catalog_id']).'/'.$value['catalog_id'].'/'.$value['image'])?>" alt="alt">
								<span class="catalog-item_text">
									<span class="catalog-item_title"><?=lang('tanya_grig')?></span>
									<span class="catalog-item_descr"><?=$value['title']?></span>
								</span>
							</a>
						</div>
					<? endforeach; ?>
				</div>
			</div>
		</section>
	<?php endif ?>
<?php endif ?>
