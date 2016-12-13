<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<article>
	<header>
		<h1><?php if (LANG == 'ua'): ?>Пошук в каталозі<?php endif; ?><?php if (LANG == 'ru'): ?>Поиск в каталоге<?php endif; ?><?php if (LANG == 'en'): ?>Search in catalog<?php endif; ?></h1>
	</header>
	<p style="padding:0;"><?php if (LANG == 'ua') echo 'по запиту: '; if (LANG == 'ru') echo 'по запросу: '; if (LANG == 'en') echo 'query: '; ?><b><?php echo $query; ?></b></p>
	<p style="padding:0;"><?php if (LANG == 'ua') echo 'знайдено результатів: '; if (LANG == 'ru') echo 'найдено результатов: '; if (LANG == 'en') echo 'found results: '; ?><b><?php echo $count; ?></b></p>
</article><?php var_dump($articles); ?>

<?php if (isset($articles)): ?>
<?php foreach ($articles as $row): ?>
	<p><a href="<?php echo $this->uri->full_url($row['url']); ?>"><?php echo $row['title']; ?></a></p>
<?php endforeach; ?>
<?php endif; ?>

<?php if (isset($catalog)): ?>
<section class="fm catalog">
	<?php foreach ($catalog as $product): ?>
		<div class="fm one_good">
			<a href="<?php echo full_url($product['menu_url'] . '/' . $product['url']); ?>" class="fm on_photo">
				<?php if ($product['file_name'] !== NULL): ?><img src="<?php echo base_url('upload/catalog/' . $product['component_id'] . '/' . $product['product_id'] . '/t_' . $product['file_name']); ?>" alt="<?php echo $product['title']; ?>"><?php endif; ?>
				<?php if ($product['promotion'] == 1): ?><div class="fm promo"></div><?php endif; ?>
				<div class="on_top"></div>
				<div class="on_a"></div>
			</a>
			<a href="<?php echo full_url($product['menu_url'] . '/' . $product['url']); ?>" class="fm on_txt"><?php echo $product['title']; ?></a>
			<div class="fm on_price">
				<span><b><?php echo $product['price']; ?></b> грн.<br><?php echo round($product['price'] / $exchange[PRICE_COLUMN], 0, PHP_ROUND_HALF_UP); ?> $</span>
				<?php if (isset($cart_products[$product['product_id']])): ?>
					<a href="#" class="to_cart in_cart" data-id="<?php echo $product['product_id']; ?>"><? if(LANG=='ua')echo'В кошику';if(LANG=='ru')echo'В корзине';?></a>
				<?php else: ?>
					<a href="#" class="to_cart" data-id="<?php echo $product['product_id']; ?>"><? if(LANG=='ua')echo'Купити';if(LANG=='ru')echo'Купить';?></a>
				<?php endif; ?>
			</div>
		</div>
	<?php endforeach; ?>
</section>
<?php echo $pagination; ?>
<?php endif; ?>