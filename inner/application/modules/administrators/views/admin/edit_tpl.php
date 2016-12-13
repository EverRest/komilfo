<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	$this->template_lib->set_js('admin/jquery.form.js');

	$_site_menu = explode(',', $admin['site_menu']);
	$_admin_menu = explode(',', $admin['admin_menu']);
?>
<div class="admin_component" id="administrators_component">
	<div class="component_loader"><span></span></div>
	<div class="fm adcom_panel">
		<div class="fm type_of_component">
			<div class="administrators"></div>
		</div>
		<div class="fm component_edit_links">
			<a href="#" class="fm save"><b></b>Зберегти</a>
			<a href="#" class="fm apply"><b></b>Застосувати</a>
			<a href="<?=$this->uri->full_url('admin/administrators/index?menu_id=' . $menu_id);?>" class="fm cancel"><b></b>До списку адміністраторів</a>
		</div>
	</div>
	<form id="admin_form" action="<?=$this->uri->full_url('admin/administrators/save');?>" method="post">
		<input type="hidden" name="admin_id" value="<?=$admin_id;?>">
		<div class="evry_title padding">
			<div class="no_float bold">Основні дані</div>
		</div>
		<div class="evry_title">
			<label for="admin_name" class="block_label">Ім’я:</label>
			<input type="text" id="admin_name" name="name" value="<?=$admin['name'];?>" class="short">
		</div>
		<div class="evry_title">
			<label for="admin_login" class="block_label">Логін:</label>
			<input type="text" id="admin_login" name="login" value="<?=$admin['login'];?>" class="short">
		</div>
		<div class="evry_title">
			<label for="admin_old_password" class="block_label">Старий пароль:</label>
			<input type="password" id="admin_old_password" name="old_password" value="" autocomplete="off" class="short">
		</div>
		<div class="evry_title">
			<label for="admin_password" class="block_label">Новий пароль:</label>
			<input type="password" id="admin_password" name="password" value="" autocomplete="off" class="short">
		</div>
		<div class="evry_title">
			<label for="admin_repeat_password" class="block_label">Повтор нового паролю:</label>
			<input type="password" id="admin_repeat_password" name="repeat_password" value="" autocomplete="off" class="short">
		</div>
		<div class="evry_title padding">
			<div class="no_float bold">Права доступу</div>
		</div>
		<div class="evry_title">
			<label class="block_label">Меню сайту:</label>
			<div class="no_float admin_menu">
				<a href="#" id="check_user_menu">вибрати все</a>
				|
				<a href="#" id="uncheck_user_menu">зняти все</a>
				<br>
				<br>
				<?php foreach ($site_menu as $k_menu => $menu): ?>
					<?php build_menu($menu, 0, $_site_menu); ?>
				<?php endforeach; ?>
			</div>
		</div>
		<div class="evry_title">
			<label class="block_label">Меню адміністратора:</label>
			<div class="no_float admin_menu">
				<a href="#" id="check_admin_menu">вибрати все</a>
				|
				<a href="#" id="uncheck_admin_menu">зняти все</a>
				<br>
				<br>
				<ul>
					<?php foreach ($admin_menu['root'] as $v): ?>
						<li>
							<div class="holder">
								<div class="cell w_20"><div class="controls"><label for="admin_menu_<?=$v['code'];?>" class="check_label"><i></i><input type="checkbox" id="admin_menu_<?=$v['code'];?>" name="admin_menu[]" value="<?=$v['code'];?>"<?php if (in_array($v['code'], $_admin_menu)) echo ' checked="checked"'; ?>></label></div></div>
								<div class="cell auto"><?=$v['name'];?></div>
							</div>
							<?php if (isset($admin_menu[$v['code']])): ?>
							<ul>
								<?php foreach ($admin_menu[$v['code']] as $_v): ?>
									<li>
										<div class="holder">
											<div class="cell w_20"><div class="controls"><label for="admin_menu_<?=$_v['module'];?>_<?=$_v['index'];?>" class="check_label"><i></i><input type="checkbox" id="admin_menu_<?=$_v['module'];?>_<?=$_v['index'];?>" name="admin_menu[]" value="<?=$_v['module'];?>_<?=$_v['index'];?>"<?php if (in_array($_v['module'] . '_' . $_v['index'], $_admin_menu)) echo ' checked="checked"'; ?>></label></div></div>
											<div class="cell auto"><b></b><?=$_v['name'];?></div>
										</div>
									</li>
								<?php endforeach; ?>
							</ul>
							<?php endif; ?>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>
		</div>
		<div class="fm for_sucsess">
			<div class="fmr save_links">
				<a href="#" class="fm save_adm"><b></b>Зберегти</a>
				<a href="#" class="fm apply_adm"><b></b>Застосувати</a>
				<a href="<?=$this->uri->full_url('admin/administrators/index?menu_id=' . $menu_id);?>" class="fm cansel_adm"><b></b>До списку адміністраторів</a>
			</div>
		</div>
	</form>
</div>
<script type="text/javascript">
//<![CDATA[
	$(function () {
		$('.admin_menu')
			.on('mouseover', '.holder', function () {
				$(this).addClass('active');
			})
			.on('mouseout', '.holder', function () {
				$(this).removeClass('active');
			})
			.find('li').removeClass('grey').each(function (i) {
				if (i % 2 == 0) $(this).addClass('grey');
			});

		$('#check_user_menu').add('#check_admin_menu').on('click', function (e) {
			e.preventDefault();

			$(this).closest('.admin_menu').find('.check_label').addClass('active');
			$(this).closest('.admin_menu').find('[type="checkbox"]').prop('checked', true);
		});

		$('#uncheck_user_menu').add('#uncheck_admin_menu').on('click', function (e) {
			e.preventDefault();

			$(this).closest('.admin_menu').find('.check_label').removeClass('active');
			$(this).closest('.admin_menu').find('[type="checkbox"]').prop('checked', false);
		});

		$('.save').add('.save_adm').add('.apply').add('.apply_adm').on('click', function (e) {
			e.preventDefault();

			var redirect = ($(this).hasClass('apply_adm') || $(this).hasClass('apply')) ? false : true;

			$('#admin_form').ajaxSubmit({
				beforeSubmit: function () {
					component_loader_show($('.component_loader'));
				},
				success: function (response) {
					if (response.success) {
						component_loader_hide($('.component_loader'));
						if (redirect) window.location.href = $('.cansel_adm').attr('href');
					}
				},
				dataType: 'json'
			});
		});
	});
//]]>
</script>