<?php defined('ROOT_PATH') OR exit('No direct script access allowed'); ?>
<!--Компонент часті запитаття-->
<div class="fm frequent ex3">
	<div class="centre">
		<div class="fm title_frequent">
			<b>ЧАСТІ ЗАПИТАННЯ</b>
		</div>
		<div class="fm main_frequent">
			<?php foreach ($article as $val):?>
				<?php if($val['answer']!='' && $val['questions']!=''):?>
					<div class="fm one_fq">
						<div class="fm article_open"><?=$val['id']?>. <?=$val['answer'];?> <a href="#" class="reply">Відповідь</a></div>
						<div class="fm article_close"><?=stripslashes($val['questions']);?></div>
					</div>
				<?php endif;?>
			<?php endforeach;?>
		</div>
	</div>
</div>
