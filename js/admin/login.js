function login_error() {
	$('#admin_login_form').find('[type="text"], [type="password"]').closest('.evry_title').addClass('wrong');
}

function hide_login_error() {
	$('#admin_login_form').find('[type="text"], [type="password"]').closest('.evry_title').removeClass('wrong');
}

$(function () {
	$('#admin_login_form').on('submit', function (event) {
		event.preventDefault();

		var login = $.trim($('#admin_login').val()),
			password = $.trim( $('#admin_password').val());

		if (login === '' || password === '') {
			login_error();
			return false;
		}

		hide_login_error();

		$.post(
			full_url('admin/login/login'),
			{
				login : login,
				password : password
			},
			function (response) {
				if (response.error === 0) {
					window.location.href = full_url('');
				} else {
					login_error();
				}
			},
			'json'
		);
	});

	$('#admin_login, #admin_password').on('keypress paste change', function () {
		$(this).closest('.evry_title').removeClass('wrong').find('.oops').hide();
	});
});