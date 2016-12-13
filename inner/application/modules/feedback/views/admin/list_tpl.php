<?php defined('ROOT_PATH') OR exit('No direct script access allowed'); ?>
<ul>
	<li class="th">
		<div class="holder">
			<div class="cell last_edit"></div>
			<div class="cell w_20"><label class="check_label"><i><input type="checkbox" id="check_all" value=""></i></label></div>
			<div class="cell auto"><a href="#" class="delete_selected no_cell" style="display:none;">Видалити вибрані</a></div>
		</div>
	</li>
	<?php foreach ($messages as $key => $message): ?>
		<li data-id="<?php echo $message['id']; ?>">
			<div class="holder">
				<div class="cell last_edit<?php if ($message['status'] == 0): ?> active<?php endif; ?>"></div>
				<div class="cell w_20 icon">
					<div class="controls">
						<label class="check_label"><i><input type="checkbox" name="message[]" value="<?=$message['id'];?>"></i></label>
					</div>
				</div>
				<div class="cell w_200">
					<strong>П.І.Б.:</strong> <?php echo stripslashes($message['name']); ?><br>
					<strong>E-mail:</strong> <?php echo stripslashes($message['email']); ?><br>
					<?php if ($message['phone'] != ''): ?><strong>Телефон:</strong> <?php echo stripslashes($message['phone']); ?><br><?php endif; ?>
					<strong>Дата:</strong> <?php echo date('d.m.Y H:i', $message['time']); ?><br>
				</div>
				<div class="cell auto">
					<?php if ($message['theme'] != ''): ?><strong>Тема:</strong><br><?php echo stripslashes($message['theme']); ?><br><br><?php endif; ?>
					<strong>Текст повідомлення:</strong><br><?php echo stripslashes($message['message']); ?>
				</div>
				<div class="cell w_20 icon"><a href="#" class="delete"></a></div>
			</div>
		</li>
	<?php endforeach; ?>
</ul>