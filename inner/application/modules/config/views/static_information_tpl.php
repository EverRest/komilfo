<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
	$this->template_lib
		->set_js('http://maps.googleapis.com/maps/api/js?v=3.exp&sensor=true&libraries=places', FALSE)
		->set_js('admin/jquery.form.js')
		->set_js('plugins/mustache.min.js');
?>
<div class="fm admin_component">
	<div class="component_loader"></div>
	<div class="fm adcom_panel">
		<div class="fm type_of_component">
			<div class="static_information"></div>
		</div>
		<div class="fm component_edit_links">
			<a href="#" class="fm save"><b></b>Зберегти</a>
		</div>
		
	</div>
	<div class="fm admin_static" style="width: 100%;">
		<form id="header_form" action="<?=$this->uri->full_url('admin/config/save_static_information');?>" method="post">
			<!-- <div class="fm evry_title">
				<label class="block_label short">Email:</label>
				<input type="text" name="email" value="<?= $data['email'];?>">
			</div>			
			<div class="fm evry_title">
				<label class="block_label">Вконтакті:</label>
				<input type="text" name="vk" value="<?= $data['vk'];?>">
			</div> -->
			<div class="fm evry_title">
				<label class="block_label">Facebook:</label>
				<input type="text" name="fb" value="<?= $data['fb'];?>">
			</div>
		<!-- 	<div class="fm evry_title">
				<label class="block_label">Tweeter:</label>
				<input type="text" name="tw" value="<?= $data['tw'];?>">
			</div> -->
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
		$('.component_lang').on('click', 'a', function (e) {
			e.preventDefault();
			$('.static_tab').hide();
			$('#static_tab_' + $(this).data('language')).show();
			$(this).addClass('active').siblings().removeClass('active');
		});
		/**
		 * Збереження змін
		 */
		$('.for_sucsess .save, .component_edit_links .save').on('click', function (e) {
			e.preventDefault();
			var request = {};
			$this = $(".admin_static").find('input#form_view');
			if ($this.attr('type') !== undefined && $this.attr('type').toLowerCase() === 'checkbox') {
				request[$this.attr('name')] = $this.prop('checked') ? $this.val() : null;
			}
					global_helper.loader($('.admin_component'));
			$('#header_form').ajaxSubmit({
				data : request,
				beforeSubmit: function () {
					console.log("asdas");
				},
				success: function (response) {
					if (response) {
						global_helper.loader($('.admin_component'));
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