<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="admin_component" id="administrators_component">
	<div class="component_loader"><span></span></div>
	<div class="fm adcom_panel">
		<div class="fm type_of_component"><div class="administrators"></div></div>
		<div class="fm component_edit_links"><a class="fm add" href="#" id="administrator_add"><b></b>Додати адміністратора</a></div>
	</div>
	<div class="fm admin_menu">
		<ul>
			<li class="th">
				<div class="holder">
					<div class="cell w_30"></div>
					<div class="cell w_20"></div>
					<div class="cell w_20"></div>
					<div class="cell auto">Ім’я</div>
					<div class="cell w_120">Дата створення</div>
					<div class="cell w_120">Останнє логування</div>
					<div class="cell w_20"></div>
				</div>
			</li>
		</ul>
		<ul class="admin_administrators">
			<?php if (isset($administrators) AND count($administrators) > 0): ?><?php foreach ($administrators as $row): ?>
			<li data-id="<?=$row['admin_id'];?>">
				<div class="holder">
					<div class="cell w_30 number"></div>
					<div class="cell w_20 icon"><a href="#" class="hide-show<?php if ($row['status'] == 0) echo ' active'; ?>"></a></div>
					<div class="cell w_20 icon"><?php if ($row['root'] == 1 OR ($row['admin_id'] == $this->session->userdata('admin_id'))): ?><a href="<?=$this->uri->full_url('admin/administrators/edit?menu_id=' . $menu_id . '&admin_id=' . $row['admin_id']);?>" class="edit"></a><?php endif; ?></div>
					<div class="cell auto"><?=(($row['name'] != '') ? $row['name'] : 'Новий адміністратор');?></div>
					<div class="cell w_120" style="text-align: center"><?=date('d.m.Y H:i', $row['create_date']);?></div>
					<div class="cell w_120" style="text-align: center"><?=($row['login_date'] > 0 ? date('d.m.Y H:i', $row['login_date']) : '-');?></div>
					<div class="cell w_20 icon"><?php if ($row['root'] == 0): ?><a href="#" class="delete"></a><?php endif; ?></div>
				</div>
			</li>
		<?php endforeach; ?><?php endif; ?>
		</ul>
	</div>
</div>
<script type="text/javascript">
//<![CDATA[
	$(document).ready(function () {

		var $component = $('#administrators_component');

		function row_decor() {
			$component.find('.admin_administrators').find('li')
				.removeClass('grey')
				.each(function () {
					var index = $(this).index();
					$(this).find('.number').eq(0).text(index + 1);
					if (index % 2 == 0) $(this).addClass('grey');
				});
		}

		row_decor();

		$component.on('click', '#administrator_add', function (e) {
			e.preventDefault();

			component_loader_show($('.component_loader'), '');

			$.post(
				'<?=$this->uri->full_url('admin/administrators/insert');?>',
				function (response) {
					if (response.success) {
						var row = $('<li data-id="' + response.admin_id + '"><div class="holder"><div class="cell w_30 number"></div><div class="cell w_20 icon"><a href="#" class="hide-show active"></a></div><div class="cell w_20 icon"><a href="<?=$this->uri->full_url('admin/administrators/edit?menu_id=' . $menu_id . '&admin_id=');?>' + response.admin_id + '" class="edit"></a></div><div class="cell auto">Новий адміністратор</div><div class="cell w_120" style="text-align: center">' + response.date + '</div><div class="cell w_120" style="text-align: center">-</div><div class="cell w_20 icon"><a href="#" class="delete"></a></div></div></li>');
						$component.find('.admin_administrators').prepend(row);

						row_decor();
						component_loader_hide($('.component_loader'), '');
					}
				},
				'json'
			);
		});

		$component
			.on('click', '.hide-show', function (e) {
				e.preventDefault();
				var $link = $(this),
					status = 1;

				if ($link.hasClass('active')) {
					$link.removeClass('active');
				} else {
					$link.addClass('active');
					status = 0;
				}

				component_loader_show($('.component_loader'), '');

				$.post(
					'<?=$this->uri->full_url('admin/administrators/status');?>',
					{
						admin_id: $link.closest('li').data('id'),
						status: status
					},
					function (response) {
						if (response.success) {
							component_loader_hide($('.component_loader'), '');
						}
					},
					'json'
				);
			})
			.on('click', '.delete', function (e) {
				e.preventDefault();

				var $link = $(this),
					admin_id = $link.closest('li').data('id');

				confirmation('Видалити адміністратора?', function () {
					component_loader_show($('.component_loader'), '');

					$.post(
						'<?=$this->uri->full_url('admin/administrators/delete');?>',
						{
							admin_id: admin_id
						},
						function (response) {
							if (response.success) {
								$link.closest('li').slideUp().remove();
								row_decor();
								component_loader_hide($('.component_loader'), '');
							}
						},
						'json'
					);
				});
			})
			.on('mouseover', '.admin_administrators .holder', function () {
				$(this).addClass('active');
			})
			.on('mouseout', '.admin_administrators .holder', function () {
				$(this).removeClass('active');
			});
	});
//]]>
</script>