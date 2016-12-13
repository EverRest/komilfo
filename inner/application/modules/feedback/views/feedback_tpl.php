<?php defined('ROOT_PATH') OR exit('No direct script access allowed'); ?>
<?php $title = lang('feedback');?>

<?php
	/*
	 * Input style
	 * $style = (placeholder or label)
	 */
	$style = 'placeholder';
?>
<section class="sect-feedback">
    <div class="center" id="feedback_<?php echo $component_id; ?>">
        <!--   з лейблами
   		-------------------------------------------------------------------------------------------------->
    	<div class="sect-title"><?=$title;?></div>
        <?php if($style == 'label'):?>
	        <div class="long_div hidden">
		        <label for="feedback_code_<?php echo $component_id; ?>"></label>
		        <input type="text" id="feedback_code_<?php echo $component_id; ?>" name="code" value="">
	        </div>
            <div class="row">
	            <div class="input-feedback input-item col-sm-12 col-xs-24">
	            	<label for="feedback_name_<?php echo $component_id; ?>"><?=lang('name');?></label>
	                <input type="text" name="name" id="feedback_name_<?php echo $component_id; ?>">
	            </div>
	            <div class="input-feedback input-item col-sm-12 col-xs-24">
	            	<label for="feedback_email_<?php echo $component_id; ?>">E-mail</label>
	                <input type="text" name="email" id="feedback_email_<?php echo $component_id; ?>" >
	            </div>
	        </div>
            <div class="row">
	        	<label for="feedback_message_<?php echo $component_id; ?>"><?=lang('message');?></label>
	            <textarea name="message" class="input-item" id="feedback_message_<?php echo $component_id; ?>" ></textarea>
	        </div>
        <?php else:?>
        <!--   з плейсхолдерами
   		-------------------------------------------------------------------------------------------------->
   		<div class="feedback-inputs">
	        <div class="input-item hidden">
		        <input type="text" id="feedback_code_<?php echo $component_id; ?>" name="code" value="" placeholder="CODE">
	        </div>
            <div class="input-item fm">
                <input type="text" name="name" id="feedback_name_<?php echo $component_id; ?>" placeholder="<?=lang('name');?>">
            </div>
            <div class="input-item fm">
                <input type="text" name="email" id="feedback_email_<?php echo $component_id; ?>" placeholder="Email">
            </div>
            <div class="input-item fm">
           	 <textarea name="message" class="input-item" id="feedback_message_<?php echo $component_id; ?>" placeholder="<?=lang('message');?>"></textarea>
            </div>
        </div>
        <?php endif;?>
        <div class="box-c">
            <a href="" class="common-btn big-btn" id="feedback_send_<?php echo $component_id; ?>"><?=lang('submit')?></a>
        </div>
        <!-- <div class="long_div">
        	<a href="#" class="fmr common_but send_adm btn btn-2 btn-2d" id="feedback_send_<?php echo $component_id; ?>">
        		<b></b><span><?=lang('submit');?></span>
        	</a>
        </div> -->
    </div>
    <div id="feedback_alert_<?php echo $component_id; ?>" class="fm common_tit" style="opacity:0;"></div>
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
			if (request.name == '') {
				$('#feedback_name_<?php echo $component_id; ?>').addClass('error');
				return false;
			}
			var email_test = /^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,6}$/i;
			if (request.email == ''|| !request.email != '' || !email_test.test(request.email)) {
				$('#feedback_email_<?php echo $component_id; ?>').addClass('error');
				return false;
			}
			
			if (request.message == '') {
				$('#feedback_message_<?php echo $component_id; ?>').addClass('error');
				return false;
			}
			
			$self.text('<?=lang('sending');?>');
			
			
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
						setTimeout(function () {
							$self.text('<?=lang('sent');?>');
							$self.css('background-color', '#538e5d');
						}, 1000);
						setTimeout(function () {
							$self.text('<?=lang('submit');?>');
							$self.css("background-color", '#96928d')
						}, 3000);
					}
				},
				'json'
			);
		});
		$('#feedback_recaptcha_<?php echo $component_id; ?>').on('click', function (event) {
			event.preventDefault();
			$('#feedback_captcha_<?php echo $component_id; ?>').find('img').fadeTo(200, 0, function () {
				$(this).attr('src', '<?php echo $this->uri->full_url('feedback/captcha'); ?>?data=' + Math.random()).ready(function () {
					$('#feedback_captcha_<?php echo $component_id; ?>').find('img').fadeTo(1000, 1);
				});
			});
		});
	});
</script>