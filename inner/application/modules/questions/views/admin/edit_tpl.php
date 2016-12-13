<?php
	defined('ROOT_PATH') OR exit('No direct script access allowed');

	$this->template_lib->set_js('admin/jquery.form.js');
?>
<div class="admin_component">
	<div class="component_loader"></div>
	<div class="fm adcom_panel">
		<div class="fm type_of_component">
			<div class="article"></div>
		</div>
		<div class="fm component_edit_links">
        	<div class="fm only_text"><div>Редагування компонента - наші переваги</div></div>
			<a href="#" class="fm save"><b></b>Зберегти</a>
			<a href="#" class="fm apply"><b></b>Застосувати</a>
			<a href="#" class="fm recreation"><b></b>Відновити</a>
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
		<form id="component_article_form" action="<?=$this->uri->full_url('/admin/benefits/update_article');?>" method="post">
			<input type="hidden" name="component_id" value="<?=$component_id;?>">
			<input type="hidden" name="menu_id" value="<?=$menu_id;?>">
			<?php foreach ($languages as $key => $val): ?>
			<div id="article_tab_<?=$key;?>" class="article_tab"<?=(($key != LANG) ? ' style="display: none"' : ''); ?>>
				<div class="evry_title">
					<textarea wrap="off" id="ca_text_<?=$key;?>" name="text[<?=$key;?>]" style="width: 95%; height: 1000px; margin-left: 15px;">
						<?php
							$file=fopen($_SERVER['DOCUMENT_ROOT'].'/inner/application/modules/benefits/views/benefits_tpl.php', 'r');
							$contents = fread($file, filesize($_SERVER['DOCUMENT_ROOT'].'/inner/application/modules/benefits/views/benefits_tpl.php'));
							fclose($file);
							echo htmlspecialchars($contents);//екрануємо теги html
						?>
					</textarea>
				</div>
			<?php endforeach; ?>
			<div class="fm for_sucsess">
				<div class="fmr save_links">
					<a href="#" class="fm save_adm"><b></b>Зберегти</a>
					<a href="#" class="fm apply_adm"><b></b>Застосувати</a>
					<a href="#" class="fm recreation"><b></b>Відновити</a>
					<a href="#" class="fm cansel_adm"><b></b>Скасувати</a>
				</div>
			</div>
		</form>
	</div>
</div>
<script type="text/javascript">
	function save_component_article(callback) {
		component_loader_show($('.component_loader'), '');
		$('#component_article_form').ajaxSubmit({
			success:function (response) {
				component_loader_hide($('.component_loader'), 'Зміни збережено');
				if ($.type(callback) === 'function') callback();
			},
			dataType: 'json'
		});
	}
	function recreation_component_article()
	{
		component_loader_show($('.component_loader'), '');

		var uri = '<?=$this->uri->full_url('admin/benefits/update_article');?>',

			request = {
				mode: 1,
				menu_id:<?=$menu_id;?>,
				component_id:<?=$component_id;?>
			};
		$.post(
			uri,
			request,
			function (response) {
				if (response.error == 0) {
					component_loader_hide($('.component_loader'), '');
					location.reload();
				}
			},
			'json'
		);
	}

	function cancel_editing() {
		window.location.href = '<?=$this->init_model->get_link($menu_id, '{URL}');?>';
	}

	$(function () {

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

		$('.component_edit_links .recreation,.for_sucsess .recreation').on('click', function (e) {
			e.preventDefault();
			recreation_component_article();
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