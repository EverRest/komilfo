<?php
	defined('ROOT_PATH') OR exit('No direct script access allowed');

	$this->template_lib->set_js('admin/ckeditor/ckeditor.js');
	$this->template_lib->set_js('admin/jquery.form.js');
?>
<div class="admin_component">
	<div class="component_loader"></div>
	<div class="fm adcom_panel">
		<div class="fm type_of_component">
			<div class="benefits"></div>
		</div>
		<div class="fm component_edit_links">
        	<div class="fm only_text"><div>Редагування компоненти</div></div>
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
	<div class="fm admin_view_benefits">
		<form id="component_benefits_form" action="<?=$this->uri->full_url('/admin/benefits/update_benefits');?>" method="post">
			<input type="hidden" name="component_id" value="<?=$component_id;?>">
			<input type="hidden" name="menu_id" value="<?=$menu_id;?>">
			<div id="benefits_tab_ua" class="benefits_tab">
				<div class="evry_title">
					<label for="ca_title_ua" class="block_label">Назва:</label>
					<input type="text" id="ca_title_ua" name="title_ua" value="<?=$benefits['title_ua'];?>">
				</div>
                <div class="evry_title">
                    <label for="ca_author_ua" class="block_label">Автор:</label>
                    <input type="text" id="ca_author_ua" name="author_ua" value="<?=$benefits['author_ua'];?>">
                </div>
                <div class="evry_title">
                    <label for="ca_quote_ua" class="block_label">Цитата:</label>
                    <div class="no_float"><textarea class="component_benefits" id="ca_quote_ua" name="quote_ua" style="height: 400px"><?=stripslashes($benefits['quote_ua']);?></textarea></div>
                </div>
				<div class="evry_title">
					<label for="ca_text_ua>" class="block_label">Текст:</label>
					<div class="no_float"><textarea class="component_benefits" id="ca_text_ua" name="text_ua" style="height: 400px"><?=stripslashes($benefits['text_ua']);?></textarea></div>
				</div>
			</div>
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

	function save_component_benefits(callback) {
		component_loader_show($('.component_loader'), '');
		$('.component_benefits').ckeditor({action: 'update'});
		$('#component_benefits_form').ajaxSubmit({
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

		$('input#background').on('click', function(e){
			if(e.target.checked){
				$("#background_fone").val(1);	
			}else{
				$("#background_fone").val(0);
			}
		});

		$('input#btn_a').on('click', function(e){
			if(e.target.checked){
				$("#btn_active").val(1);	
			}else{
				$("#btn_active").val(0);
			}
		});

		$('.component_benefits').ckeditor({height: 300});

		$('.component_lang').find('a').on('click', function (e) {
			e.preventDefault();

			$(this).closest('div').find('.active').removeClass('active');
			$(this).addClass('active');

			$('.benefits_tab').hide();
			$('#benefits_tab_' + $(this).data('language')).show();
		});

		$('.component_edit_links .save, .for_sucsess .save_adm').on('click', function (e) {
			e.preventDefault();
			save_component_benefits(function () {
				cancel_editing();
			});
		});

		$('.component_edit_links .apply, .for_sucsess .apply_adm').on('click', function (e) {
			e.preventDefault();
			save_component_benefits('');
		});

		$('.component_edit_links .cancel, .for_sucsess .cansel_adm').on('click', function (e) {
			e.preventDefault();
			cancel_editing();
		});
	});
</script>