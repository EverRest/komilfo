<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="admin_component">
	<div class="component_loader"><span></span></div>
	<div class="fm adcom_panel">
		<div class="fm type_of_component">
			<div class="exchange"></div>
		</div>
		<div class="fm component_edit_links">
			<div class="fm only_text"><div>Курси валют</div></div>
			<a href="#" class="fm save"><b></b>Зберегти</a>
		</div>
	</div>
	<div class="evry_title">
		<label for="usd_exchnage" class="block_label">Курс USD:</label>
		<input type="text" id="usd_exchnage" name="usd" value="<?=$config['usd'];?>" class="short-er">
	</div>
	<div class="evry_title">
		<label for="eur_exchnage" class="block_label">Курс EUR:</label>
		<input type="text" id="eur_exchnage" name="eur" value="<?=$config['eur'];?>" class="short-er">
	</div>
	<div class="fm for_sucsess">
		<div class="fmr save_links">
			<a href="#" class="fm save_adm"><b></b>Зберегти</a>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(function () {
		/**
		 * Збереження змін
		 */
		$('.save_adm').on('click', function (e) {
			e.preventDefault();

			component_loader_show($('.component_loader'), '');

			var uri = '<?=$this->uri->full_url('admin/config/save_exchange');?>',
				request = {
					usd: $('#usd_exchnage').val(),
					eur: $('#eur_exchnage').val()
				};

			$.post(
				uri,
				request,
				function (response) {
					component_loader_hide($('.component_loader'), '');
				},
				'json'
			);
		});
	});
</script>