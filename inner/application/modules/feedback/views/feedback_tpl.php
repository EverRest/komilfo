<?php defined('ROOT_PATH') OR exit('No direct script access allowed'); ?>
<?php if (LANG=='ua') $title = 'Зворотній зв`язок'; if (LANG == 'ru') $title = 'Обратная связь'; if (LANG == 'en') $title = 'Feedback'; ?>
<article class="feat">
	<header><?php if (!$h1): ?><h1><?=$title;?></h1><?php else: ?><h2><?=$title;?></h2><?php endif; ?></header>
	<div class="fm feedback" id="feedback_<?php echo $component_id; ?>">
		<div class="evry_title">
			<label for="feedback_name_<?php echo $component_id; ?>"><? if(LANG=='ua')echo'П.І.П.';if(LANG=='ru')echo'Ф.И.О.';if(LANG=='en')echo'Full Name';?>:</label>
			<input type="text" id="feedback_name_<?php echo $component_id; ?>" name="name" value="">
		</div>
		<div class="evry_title">
			<label for="feedback_email_<?php echo $component_id; ?>">E-mail:</label>
			<input type="text" id="feedback_email_<?php echo $component_id; ?>" name="email" value="">
		</div>
		<div class="evry_title">
			<label for="feedback_phone_<?php echo $component_id; ?>" class="no_active"><? if(LANG=='ua')echo'Телефон';if(LANG=='ru')echo'Телефон';if(LANG=='en')echo'Phone';?>:</label>
			<input type="text" id="feedback_phone_<?php echo $component_id; ?>" name="phone" value="">
		</div>
		<div class="evry_title">
			<label for="feedback_theme_<?php echo $component_id; ?>" class="no_active"><? if(LANG=='ua')echo'Тема';if(LANG=='ru')echo'Тема';if(LANG=='en')echo'Subject';?>:</label>
			<input type="text" id="feedback_theme_<?php echo $component_id; ?>" name="theme" value="">
		</div>
		<div class="evry_title">
			<label for="feedback_message_<?php echo $component_id; ?>"><? if(LANG=='ua')echo'Повідомлення';if(LANG=='ru')echo'Сообщение';if(LANG=='en')echo'Message';?>:</label>
			<textarea id="feedback_message_<?php echo $component_id; ?>" name="message"></textarea>
		</div>
		<div class="evry_title">
			<label></label>
			<div class="fm captcha">
				<input type="text" id="feedback_code_<?php echo $component_id; ?>" name="code" value="" maxlength="4">
				<div class="captcha_place" id="feedback_captcha_<?php echo $component_id; ?>">
					<img src="<?php echo $this->uri->full_url('feedback/captcha'); ?>" height="27">
					<a href="#" class="reload" id="feedback_recaptcha_<?php echo $component_id; ?>"></a>
				</div>
			</div>
		</div>
                <div class="fm buttom top_buttom">
                    <div class="fm fon_button2">
                        <a href="#" class="fm send_button" id="feedback_send_<?php echo $component_id; ?>"><? if(LANG=='ua')echo'Надіслати';if(LANG=='ru')echo'Отправить';if(LANG=='en')echo'Send';?></a>
                    </div>
                    </div>
                </div>
	<div id="feedback_alert_<?php echo $component_id; ?>" class="fm mail_sent" style="display:none;"></div>
</article>
