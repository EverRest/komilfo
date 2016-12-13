<?php defined('ROOT_PATH') or exit('No direct script access allowed'); ?>
<?php if (isset($article) AND count($article) > 0): ?>
	<section class="artile-place mbm">
	    <div class="center">
	            <article>
	               	<?php if (!$h1): ?><h1><?=$article['title'];?></h1><?php else: ?><h2><?=$article['title'];?></h2><?php endif; ?>
					<?php if ((int)$this->config->item('print_icon') === 1): ?><span class="print_icon" data-module="article" data-id="<?=$component_id;?>"></span><?php endif; ?>
	                <?=stripslashes($article['text']);?>
	            </article>
	    </div>
	</section>
<?php endif; ?>