<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div class="fm admin_component">
	<div class="component_loader"></div>
	<div class="fm adcom_panel">
		<div class="fm type_of_component">
			<div class="access"></div>
		</div>
		<div class="fm component_edit_links">
			<a href="#" class="fm save"><b></b>Зберегти</a>
		</div>
	</div>
	<div class="evry_title">
		<label class="block_label">Логін:</label>
		<input type="text" name="admin_login" value="<?php echo $config['login']; ?>" class="short">
	</div>
	<div class="evry_title">
		<label class="block_label">Поточний пароль:</label>
		<input type="password" name="old_password" value="" class="short">
	</div>
	<div class="evry_title">
		<label class="block_label">Новий пароль:</label>
		<input type="password" name="new_password" value="" class="short">
	</div>
	<div class="evry_title">
		<label class="block_label">Підтвердження паролю:</label>
		<input type="password" name="repeat_password" value="" class="short">
	</div>
	<div class="fm for_sucsess short">
		<div class="fmr save_links">
			<a href="#" class="fm save_adm"><b></b>Зберегти</a>
		</div>
	</div>
</div>
<script type="text/javascript">
	$(document).ready(function () {
		/**
		 * Збереження змін
		 */
		$('.for_sucsess .save_adm, .component_edit_links .save').on('click', function (e) {
			e.preventDefault();

			var $admin_login = $('input[name="admin_login"]'),
				$admin_old_password = $('input[name="old_password"]'),
				$admin_new_password = $('input[name="new_password"]'),
				$admin_repeat_password = $('input[name="repeat_password"]'),
				login = $.trim($admin_login.val()),
				old_password = $.trim($admin_old_password.val()),
				new_password = $.trim($admin_new_password.val()),
				repeat_password = $.trim($admin_repeat_password.val());

			if (login === '') {
				$admin_login.closest('.evry_title').addClass('wrong');
				return false;
			}

			if (old_password === '') {
				$admin_old_password.closest('.evry_title').addClass('wrong');
				return false;
			}

			if (new_password !== '') {
				if (login === '') {
					$admin_login.closest('.evry_title').addClass('wrong');
					return false;
				}

				if (old_password === '') {
					$admin_old_password.closest('.evry_title').addClass('wrong');
					return false;
				}

				if (repeat_password === '') {
					$admin_repeat_password.closest('.evry_title').addClass('wrong');
					return false;
				}

				if (new_password !== repeat_password) {
					$admin_new_password.closest('.evry_title').addClass('wrong');
					$admin_repeat_password.closest('.evry_title').addClass('wrong');
					return false;
				}
			} else {
				$admin_new_password.closest('.evry_title').addClass('wrong');
				return false;
			}

			component_loader_show($('.component_loader'), '');
			$('.evry_title').removeClass('wrong');

			var uri = '<?php echo $this->uri->full_url('admin/config/save_admin'); ?>',
				request = {
					login : login,
					old_password : old_password,
					new_password : new_password
				};

			$.post(
				uri,
				request,
				function (response) {
					if (response.success) {
						component_loader_hide($('.component_loader'), '');
						window.location.reload();
					} else {
						$admin_old_password.closest('.evry_title').addClass('wrong');
					}
				},
				'json'
			);
		});

		$('input[name="admin_login"], input[name="old_password"], input[name="new_password"]').on('keyup blur paste', function () {
			$(this).closest('.evry_title').removeClass('wrong');
		});

		$('input[name="repeat_password"]').on('keyup blur paste', function () {
			$(this).closest('.evry_title').removeClass('wrong');
			$('input[name="new_password"]').closest('.evry_title').removeClass('wrong');
		});
	});
</script>