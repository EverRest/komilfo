<?php defined('ROOT_PATH') OR exit('No direct script access allowed'); ?>
<div class="fm admin_base admin_component">
	<div class="component_loader"></div>
	<div class="fm adcom_panel">
		<div class="fm type_of_component">
			<div class="xml_map"></div>
		</div>
		<div class="fm component_edit_links">
			<a href="#" class="fm save re-load"><b></b>Оновити</a>
		</div>
	</div>
    <div class="fm admin_massage" style="display:none;">Xml карта сайту оновлена</div>
	<div class="fm for_sucsess">
		<div class="fmr save_links">
			<a href="#" class="fm save_adm re-load"><b></b>Оновити</a>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(function () {
		$('.save_adm, .component_edit_links .save').on('click', function (event) {
			event.preventDefault();

			component_loader_show($('.component_loader'), '');

			$.post(
				'<?=$this->uri->full_url('admin/seo/update_xml');?>',
				function (response) {
					if (response.success) {
						component_loader_hide($('.component_loader'), '');
						$('.admin_massage').fadeTo(200, 1).delay(2000).fadeTo(200, 0);
					}
				},
				'json'
			);
		});
	});
</script>