<?php

	defined('ROOT_PATH') OR exit('No direct script access allowed');

	$this->template_lib->set_js('admin/login.js');
?>
<div class="centre">
	<div class="fm admin_component login">
		<div class="fm adcom_panel">
			<div class="fm type_of_component">
				<div class="login"></div>
			</div>
		</div>
		<div id="admin_login_box">
			<form id="admin_login_form" action="#" method="post">
				<div class="evry_title">
					<label class="block_label">Логін:</label>
					<input type="text" id="admin_login" name="admin_login" value="" class="short">
				</div>
				<div class="evry_title">
					<label class="block_label">Пароль:</label>
					<input type="password" id="admin_password" name="admin_password" value="" class="short">
				</div>
				<div class="fm for_sucsess short">
					<div class="fmr save_links">
						<input type="submit" value="Увійти" class="fm login">
					</div>
				</div>
			</form>
		</div>
	</div>
</div>