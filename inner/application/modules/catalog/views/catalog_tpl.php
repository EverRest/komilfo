<?php defined('ROOT_PATH') or exit('No direct script access allowed');

	/**
	 * @var array $items
	 * @var array $markers
	 * @var array $locations
	 */
	$catalog_menu = Modules::run('menu/get_catalog_menu');
	
?>

<?if(!empty($catalog)):?>
	<section class="sect-catalog fm">
		<div class="center">
			<div class="catalog-title fm">
				<h1><?=lang('tanya_grig')?></h1>
				<h2><?=$catalog['menu_name']?></h2>
			</div>
			<? unset($catalog['menu_name']); ?>
			<div class="catalog-wrapper fm">
				<?foreach ($catalog as $key => $value): ?>
					<?php $url = $this->uri->full_url($value['url']); ?>
					<div class="catalog-item fm">
						<a href="<?=$url?>">
							<?php if (isset($value['images'][0])): ?>
								<img src="<?=base_url('upload/catalog/'.get_dir_code($value['catalog_id']).'/'.$value['catalog_id'].'/'.$value['images'][0]['photo'])?>" alt="alt">
							<?php endif ?>
							<span class="catalog-item_text">
								<span class="catalog-item_title"><?=lang('tanya_grig')?></span>
								<span class="catalog-item_descr"><?=$value['title_'.LANG]?></span>
							</span>
						</a>
					</div>
				<?endforeach; ?>
			</div>
		</div>
	</section>
	<section class="other-collections fm">
		<div class="center">
			<div class="sect-title fm"><?=lang('other_collections')?></div>
			<?=$catalog_menu?>
		</div>
	</section>	
<?endif; ?>