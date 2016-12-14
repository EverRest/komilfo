<?php
	defined('ROOT_PATH') OR exit('No direct script access allowed');

	$this->template_lib->set_js('admin/jquery.form.js');
?>

<style type="text/css">
	.evry_title { padding-left: 25px; box-sizing: border-box;}
	.evry_title label {position: relative; float: left; width: 115px;}
	.edit_object { width: 100%; font-weight: bold;}
	.one_input {width: 100%; margin-top: 10px;}
	.one_input input {margin-right: 15px;}
	.input_phone input {width: 20%;}
</style>

<div class="admin_component">
	<div class="component_loader"><span></span></div>
	<div class="fm adcom_panel">
		<div class="fm type_of_component">
			<div class="footer_set"></div>
		</div>
		<div class="fm component_edit_links">
			<a href="#" class="fm save"><b></b>Save</a>

		</div>
		<?php if (count($languages) > 1): ?>
		<div class="fmr component_lang" style="display: none;">
			<?php foreach ($languages as $key => $val): ?>
			<a href="#" class="flags <?=$key;?><?=(($key == LANG) ? ' active' : '');?>" data-language="<?=$key;?>"><img src="img/flags_<?=$key;?>.png"></a>
			<?php endforeach; ?>
		</div>
		<?php endif; ?>
	</div>
	<div class="fm admin_view_article">
		<form id="component_google_map_form" action="<?=$this->uri->full_url('/admin/config/update_footer');?>" method="post">
			<div class="fm evry_title">
				<!-- <div class="fm edit_object">Телефони:</div> -->
				<div class="fm one_input"><label>Вконтакті: </label><input type="text" name="vk" value="<?= $data['vk']?>"></div>
				<div class="fm one_input"><label>Facebook: </label><input type="text" name="fb" value="<?= $data['fb']?>"></div>
				<div class="fm one_input"><label>Instagram: </label><input type="text" name="gplus" value="<?= $data['ing']?>"></div>
			</div>

			<div class="fm for_sucsess">
				<div class="sucsess" style="display: none">Saved</div>
				<div class="loader" style="display: none"></div>
				<div class="fmr save_links">
					<a href="#" class="fm save_adm"><b></b>Save</a>
				</div>
			</div>
		</form>
	</div>
</div>
<script type="text/javascript">

//	function save_component_google_map(callback) {
//
//		$('#component_google_map_form').ajaxSubmit({
//			beforeSubmit:function () {
//				component_loader_show($('.component_loader'), '');
//			},
//			success:function (response) {
//				component_loader_hide($('.component_loader'));
//
//				if ($.type(callback) == 'function') callback();
//			},
//			dataType: 'json'
//		});
//	}

	function cancel_editing() {
		window.location.href = '<?=$this->init_model->get_link($menu_id, '{URL}');?>';
	}

	$(function () {

		$('.component_lang').find('a').on('click', function (e) {
			e.preventDefault();

			$(this).closest('div').find('.active').removeClass('active');
			$(this).addClass('active');

			$('.google_map_tab').hide();
			$('.google_map_tab_' + $(this).data('language')).show();
		});

		$('.component_edit_links .save, .for_sucsess .save_adm').on('click', function (e) {
			e.preventDefault();
			save_component_google_map(function () {});
		});

	});
</script>