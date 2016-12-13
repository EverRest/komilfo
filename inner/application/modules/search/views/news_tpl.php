<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<article>
	<header>
		<h1><?php if (LANG == 'ua'): ?>Пошук в новинах<?php endif; ?><?php if (LANG == 'ru'): ?>Поиск в новостях<?php endif; ?><?php if (LANG == 'en'): ?>Search in news<?php endif; ?></h1>
	</header>
	<p><?php if (LANG == 'ua') echo 'по запиту: '; if (LANG == 'ru') echo 'по запросу: '; if (LANG == 'en') echo 'query: '; ?><b><?php echo $query; ?></b></p>
	<p><?php if (LANG == 'ua') echo 'знайдено результатів: '; if (LANG == 'ru') echo 'найдено результатов: '; if (LANG == 'en') echo 'found results: '; ?><b><?php echo $count; ?></b></p>
</article>
<?php if (isset($news) AND is_array($news)): ?>
<div class="fm ser_tit"><?php if (LANG == 'ua') echo 'В новинах'; if (LANG == 'ru') echo 'В новостях'; if (LANG == 'en') echo 'In news'; ?></div>
<div class="fm news">
	<ul>
		<?php foreach ($news as $row): ?>
			<li>
				<div class="fm news_photo">
					<a href="<?php echo full_url($row['menu_url'] . '/' . translit($row['title']) . '-n' . $row['id']); ?>">
						<?php if ($row['image'] != ''): ?><img src="/upload/news/<?php echo $row['component_id']; ?>/<?php echo $row['id']; ?>/t_<?php echo $row['image']; ?>" alt=""><?php endif; ?>
					</a>
				</div>
				<div class="fm date_and_text">
					<b><?php echo date('d.m.Y', $row['date']); ?></b>
					<a href="<?php echo full_url($row['menu_url'] . '/' . translit($row['title']) . '-n' . $row['id']); ?>"><?php echo $row['title']; ?></a>
				</div>
			</li>
		<?php endforeach; ?>
	</ul>
</div>
<?php echo $pagination; ?>
<?php endif; ?>