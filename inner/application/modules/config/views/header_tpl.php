<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php

	$this->template_lib->set_js('admin/jquery.form.js');
	$this->template_lib->set_css('js/admin/ui/jquery-ui-1.10.3.custom.min.css', TRUE);
	$this->template_lib->set_js('admin/ui/jquery-ui-1.10.3.custom.min.js');
	$this->template_lib->set_js('admin/ui/jquery.ui.datepicker-uk.js');
?>
<style type="text/css">
	.evry_title { padding-left: 25px; box-sizing: border-box;}
	.evry_title label {position: relative; float: left; width: 115px;}
	.edit_object { width: 100%; font-weight: bold;}
	.one_input {width: 100%; margin-top: 10px;}
	.one_input input {margin-right: 15px;}
	.input_phone input {width: 20%;}
</style>
<div class="fm admin_component">
	<div class="component_loader"></div>
	<div class="fm adcom_panel">
		<div class="fm type_of_component">
			<div class="header_set"></div>
		</div>
		<div class="fm component_edit_links">
			<a href="#" class="fm save"><b></b>Зберегти</a>
		</div>
	</div>
	<div class="centre">
		<?php if (count($languages) > 1): ?>
			<div class="fmr component_lang">
				<?php foreach ($languages as $key => $val): ?>
					<a href="#" class="flags <?=$key;?><?=(($key == LANG) ? ' active' : '');?>" data-language="<?=$key;?>"><img src="img/flags_<?=$key;?>.png"></a>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
		<form id="timer_form" action="<?=$this->uri->full_url('admin/config/save_header');?>" method="post">
		<?php foreach ($languages as $key => $val): ?>
			<div id="article_tab_<?=$key;?>" class="article_tab"<?=(($key != LANG) ? ' style="display: none"' : ''); ?>>
<!--				<div class="fm evry_title">-->
<!--					<div class="fm edit_object">Слоган:</div>-->
<!--					<div class="fm one_input"><input type="text" name="slogan[--><?//=$key?><!--]" value="--><?//= $data['slogan_'.$key]?><!--"></div>-->
<!--				</div>-->
				
				<div class="fm evry_title">
					<div class="fm edit_object">Телефони:</div>
					<div class="fm one_input input_phone"><label></label><input type="text" name="kyivstar[<?=$key?>]" value="<?= $data['kyivstar_'.$key]?>"></div>
					<div class="fm one_input input_phone"><label></label><input type="text" name="life[<?=$key?>]" value="<?= $data['life_'.$key]?>"></div>
<!--					<div class="fm one_input input_phone"><label></label><input type="text" name="mts[--><?//=$key?><!--]" value="--><?//= $data['mts_'.$key]?><!--"></div>-->
				</div>
			</div>
		<?php endforeach; ?>
		</form>
	</div>
	<div class="fm for_sucsess short">
		<div class="fm save_links">
			<a href="#" class="fm save_adm save"><b></b>Зберегти</a>
		</div>
	</div>
</div>
<script type="text/javascript">
	$(document).ready(function () {

		$.datepicker.setDefaults($.datepicker.regional['']);

		<?php foreach($languages as $key => $val):?>

		$('#timer_date_<?=$key?>')
			.datepicker()
			.datepicker("option", $.datepicker.regional['uk'])
			.datepicker("option", "dateFormat", "dd.mm.yy")<?php if ($data['time_'.$key] > 0): ?>.datepicker("setDate", "<?=date('d.m.Y', $data['time_'.$key]);?>")<?php endif;?>;
		<?php endforeach;?>

		/**
		 * Збереження змін
		 */
		$('.for_sucsess .save, .component_edit_links .save').on('click', function (e) {
			e.preventDefault();

			$('#timer_form').ajaxSubmit({
				beforeSubmit: function () {
					component_loader_show($('.component_loader'));
				},
				success: function (response) {
					if (response.success) {
						component_loader_hide($('.component_loader'));
					}
				},
				dataType: 'json'
			});

			});

		$('.component_lang').find('a').on('click', function (e) {
			e.preventDefault();

			$(this).closest('div').find('.active').removeClass('active');
			$(this).addClass('active');

			$('.article_tab').hide();
			$('#article_tab_' + $(this).data('language')).show();
		});
	});
</script>