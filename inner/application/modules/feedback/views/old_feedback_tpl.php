<?php defined('ROOT_PATH') OR exit('No direct script access allowed'); ?>

<?php if (LANG=='ua') $title = 'Зворотній зв`язок'; if (LANG == 'ru') $title = 'Обратная связь'; if (LANG == 'en') $title = 'Feedback'; ?>

<section class="fm feedback_place">

	<div class="centre">

        <div class="fm feedback" id="feedback_<?php echo $component_id; ?>">

        	<div class="long_div tit"><div class="pf_title"><?=$title;?></div></div>

	        <div class="long_div evry_form hidden">

		        <label for="feedback_code_<?php echo $component_id; ?>"></label>

		        <input type="text" id="feedback_code_<?php echo $component_id; ?>" name="code" value="">

	        </div>

            <div class="long_div evry_form">

                <label for="feedback_name_<?php echo $component_id; ?>">П.І.П</label>

                <input type="text" id="feedback_name_<?php echo $component_id; ?>" name="name" value="">

            </div>

            <div class="long_div evry_form">

                <label for="feedback_email_<?php echo $component_id; ?>">E-mail</label>

                <input type="text" id="feedback_email_<?php echo $component_id; ?>" name="email" value="">

            </div>

            <div class="long_div evry_form no_active">

                <label for="feedback_phone_<?php echo $component_id; ?>">Телефон</label>

                <input type="text" id="feedback_phone_<?php echo $component_id; ?>" name="phone" value="">

            </div>

            <div class="long_div evry_form no_active">

                <label for="feedback_theme_<?php echo $component_id; ?>">Тема</label>

                <input type="text" id="feedback_theme_<?php echo $component_id; ?>" name="theme" value="">

            </div>

            <div class="long_div evry_form textarea">

                <label for="feedback_message_<?php echo $component_id; ?>">Повідомлення</label>

                <textarea id="feedback_message_<?php echo $component_id; ?>" name="message"></textarea>

            </div>

            <div class="long_div evry_form">

            	<a class="fm standard_bottom main_feedback send_adm" id="feedback_send_<?php echo $component_id; ?>" href="#"><span>Надіслати</span><b></b></a>

            </div>

        </div>

        <div id="feedback_alert_<?php echo $component_id; ?>" class="fm mail_sent" style="display:none;"></div>

	</div>

</section>

<script type="text/javascript">

	$(function () {



		$('#feedback_name_<?php echo $component_id; ?>,#feedback_email_<?php echo $component_id; ?>,#feedback_message_<?php echo $component_id; ?>').on('keyup blur paste', function () {

			$(this).removeClass('error');

		});



		$('#feedback_send_<?php echo $component_id; ?>').on('click', function (event) {

			event.preventDefault();



			var $self = $(this),

				request = {

					component_id: <?php echo $component_id; ?>,

					menu_id: <?php echo $menu_id; ?>,

					name:$.trim($('#feedback_name_<?php echo $component_id; ?>').val()),

					email:$.trim($('#feedback_email_<?php echo $component_id; ?>').val()),

					phone:$.trim($('#feedback_phone_<?php echo $component_id; ?>').val()),

					theme:$.trim($('#feedback_theme_<?php echo $component_id; ?>').val()),

					message:$.trim($('#feedback_message_<?php echo $component_id; ?>').val()),

					code:$.trim($('#feedback_code_<?php echo $component_id; ?>').val())

				},

				$error = $('#feedback_alert_<?php echo $component_id; ?>');



			if (request.name === '') {

				$('#feedback_name_<?php echo $component_id; ?>').addClass('error');

				return false;

			}



			var email_test = /^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,6}$/i;

			if (!email_test.test(request.email)) {

				$('#feedback_email_<?php echo $component_id; ?>').addClass('error');

				return false;

			}



			if (request.message === '') {

				$('#feedback_message_<?php echo $component_id; ?>').addClass('error');

				return false;

			}



			$self.find('span').text('Надсилається');



			$.post(

				'<?php echo $this->uri->full_url('feedback/send'); ?>',

				request,

				function (response) {

					if (response.error === 0) {

						$('#feedback_name_<?php echo $component_id; ?>').val('');

						$('#feedback_email_<?php echo $component_id; ?>').val('');

						$('#feedback_phone_<?php echo $component_id; ?>').val('');

						$('#feedback_theme_<?php echo $component_id; ?>').val('');

						$('#feedback_message_<?php echo $component_id; ?>').val('');

						$('#feedback_code_<?php echo $component_id; ?>').val('');



						$self.find('span').text('Надіслано');

						setTimeout(function () {

							$self.find('span').text('Надіслати');

						}, 2000);

					}

				},

				'json'

			);

		});



	});

</script>