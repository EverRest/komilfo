<?php
	defined('ROOT_PATH') OR exit('No direct script access allowed');
	$this->template_lib->set_js('admin/ckeditor/ckeditor.js');
	$this->template_lib->set_js('admin/jquery.form.js');
?>
<div class="admin_component">
	<div class="component_loader"></div>
	<div class="fm adcom_panel">
		<div class="fm type_of_component">
			<div class="article"></div>
		</div>
		<div class="fm component_edit_links">
        	<div class="fm only_text"><div>Редагування компонента</div></div>
			<a href="#" class="fm save"><b></b>Зберегти</a>
			<a href="#" class="fm apply"><b></b>Застосувати</a>
			<a href="#" class="fm cancel"><b></b>Скасувати</a>
		</div>
		<?php if (count($languages) > 1): ?>
		<div class="fmr component_lang">
			<?php foreach ($languages as $key => $val): ?>
				<a href="#" class="flags <?=$key;?><?=(($key == LANG) ? ' active' : '');?>" data-language="<?=$key;?>"><img src="img/flags_<?=$key;?>.png"></a>
			<?php endforeach; ?>
		</div>
		<?php endif; ?>
	</div>
	<div class="fm admin_view_article">
		<form id="component_article_form" action="<?=$this->uri->full_url('/admin/frequent/update_article');?>" method="post">
			<input type="hidden" name="component_id" value="<?=$component_id;?>">
			<input type="hidden" name="menu_id" value="<?=$menu_id;?>">
			<?php foreach ($languages as $key => $val): ?>
			<div id="article_tab_<?=$key;?>" class="article_tab"<?=(($key != LANG) ? ' style="display: none"' : ''); ?>>
				<?php foreach($article as $val):?>
				<div class="evry_title">
					<label class="block_label">№<?=$val['id'];?></label>
					<input type="text" name="ques<?=$val['id']?>" value="<?=$val['answer'];?>">
				</div>
				<div class="evry_title">
					<label class="block_label">Відповідь:</label>
					<div class="no_float"><textarea class="component_article"  name="answ<?=$val['id'];?>" style="width: 98%; height: 150px;"><?=stripslashes($val['questions']);?></textarea></div>
				</div>
				<?php endforeach;?>
			<?php endforeach; ?>
			<div class="fm for_sucsess">
				<div class="fmr save_links">
					<a href="#" class="fm save_adm"><b></b>Зберегти</a>
					<a href="#" class="fm apply_adm"><b></b>Застосувати</a>
					<a href="#" class="fm cansel_adm"><b></b>Скасувати</a>
				</div>
			</div>
		</form>
	</div>
</div>
<script type="text/javascript">

	function save_component_article(callback) {
		component_loader_show($('.component_loader'), '');
		$('.component_article').ckeditor({action: 'update'});
		$('#component_article_form').ajaxSubmit({
			success:function (response) {
				component_loader_hide($('.component_loader'), 'Зміни збережено');
				if ($.type(callback) === 'function') callback();
			},
			dataType: 'json'
		});
	}

	function cancel_editing() {
		window.location.href = '<?=$this->init_model->get_link($menu_id, '{URL}');?>';
	}

	$(function () {

		$('.component_article').ckeditor({height: 300});

		$('.component_lang').find('a').on('click', function (e) {
			e.preventDefault();

			$(this).closest('div').find('.active').removeClass('active');
			$(this).addClass('active');

			$('.article_tab').hide();
			$('#article_tab_' + $(this).data('language')).show();
		});

		$('.component_edit_links .save, .for_sucsess .save_adm').on('click', function (e) {
			e.preventDefault();
			save_component_article(function () {
				cancel_editing();
			});
		});

		$('.component_edit_links .apply, .for_sucsess .apply_adm').on('click', function (e) {
			e.preventDefault();
			save_component_article('');
		});

		$('.component_edit_links .cancel, .for_sucsess .cansel_adm').on('click', function (e) {
			e.preventDefault();
			cancel_editing();
		});
	});
</script>