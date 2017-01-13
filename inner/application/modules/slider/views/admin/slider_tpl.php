<?php
	defined('ROOT_PATH') OR exit('No direct script access allowed');

	$this->template_lib->set_js('admin/ui/jquery-ui-1.10.3.custom.min.js');
	$thumb = array(300, 300);

?>
<div class="fm admin_component" id="admin_component_<?=$component_id;?>" data-component-id="<?=$component_id;?>" data-menu-id="<?=$menu_id;?>" data-module="slider" data-css-class="slider" data-visibility-url="<?=$this->uri->full_url('admin/components/toggle_visibility');?>" data-delete-url="<?=$this->uri->full_url('admin/slider/delete_component');?>">
	<div class="component_loader"></div>
	<div class="fm adcom_panel">
		<div class="fm type_of_component">
			<div class="gallery"></div>
		</div>
		<div class="fm component_edit_links">
			<a class="fm add" href="#" id="add_slide"><b></b>Додати слайд</a>
			<a class="fm add_bottom" href="#"><b class="active"></b>Додавати внизу</a>
			<a href="#" class="fm show_hide"><b></b><?=(($hidden == 0) ? 'Приховати' : 'Показати');?></a>
		</div>
		<div class="fmr component_del">
			<a href="#" class="fm delete_component"><b></b></a>
		</div>
		<div class="fmr component_pos">
			<a href="#" class="fm up_component"><b></b></a>
			<a href="#" class="fm down_component"><b></b></a>
		</div>
	</div>
	<div class="fm admin_menu">
		<ul id="slides_list">
		<?php if (count($slides) > 0): ?>
			<?php foreach ($slides as $slide): ?>
			<li data-id="<?php echo $slide['slide_id']; ?>">
				<div class="holder<?php if ($slide['hidden'] == 1): ?> hidden<?php endif; ?>">
					<div class="cell last_edit <?php if (isset($last_slide) AND ($slide['slide_id'] == $last_slide)) echo 'active'; ?>"></div>
					<div class="cell w_20 number"></div>
					<div class="cell w_20 icon"><a href="#" class="hide-show<?php if ($slide['hidden'] == 0): ?> active<?php endif; ?>"></a></div>
					<div class="cell no_padding w_<?=$thumb[0];?>" style="width: 300px; height: 300px;">
						<?php if ($slide['file_name'] != ''): ?>
						<img src="/upload/slider/<?php echo $menu_id; ?>/<?php echo $slide['slide_id']; ?>/<?php echo $slide['file_name']; ?>" style="width: 300px; height: 300px;">
						<?php endif; ?>
					</div>
					<div class="cell w_20 icon">
						<a href="<?php echo $this->uri->full_url('admin/slider/edit?menu_id=' . $menu_id . '&slide_id=' . $slide['slide_id']); ?>" class="edit"></a>
					</div>
					<div class="cell auto"><span class="menu_item"><?php echo ($slide['description'] != '') ? $slide['description'] : 'Новий слайд'; ?></span></div>
					<div class="cell w_20 icon sorter"><a href="#" class="single_arrows"></a></div>
					<div class="cell w_20 icon"><a href="#" class="delete"></a></div>
				</div>
			</li>
			<?php endforeach; ?>
		<?php endif; ?>
		</ul>
	</div>
</div>
<script type="text/javascript">

	var slide_add_top = 0;

	function row_decor() {
		$('#slides_list')
			.find('li').removeClass('grey').each(function (i) { $(this).find('.number').text(i + 1); })
			.end()
			.find('li:even').addClass('grey');

		make_sortable();
	}

	function make_sortable() {
		$("#slides_list")
			.sortable({
				axis:'y',
				handle:'.sorter a',
				scroll:true,
				crollSpeed:2000,
				forcePlaceholderSize:true,
				placeholder:"ui-state-highlight",
				update:function (e, object) {

					$(this).find('.last_edit').removeClass('active');
					$(object.item).find('.last_edit').addClass('active');

					component_loader_show($('.component_loader'), '');

					var slides = [];

					$('#slides_list').find('li').each(function (i) {
						slides[i] = $(this).data('id');
						$(this).find('.number').text(i + 1);
					});

					$.post(
						'<?=$this->uri->full_url('admin/slider/slides_position');?>',
						{
							menu_id: <?=$menu_id?>,
							slide_id: $(object.item).data('id'),
							slides: slides
						},
						function (response) {
							if (response.success) {
								component_loader_hide($('.component_loader'), '');
								row_decor();
							}
						},
						'json'
					);
				}
			});
	}

	$(function () {

		$('#admin_component_<?=$component_id;?>').component();

		row_decor();

		var $slides_list = $('#slides_list');

		$('#add_slide').on('click', function (e) {
			e.preventDefault();

			component_loader_show($('.component_loader'), '');

			$.post(
				full_url('admin/slider/add'),
				{
					menu_id: <?=$menu_id?>,
					add_top: slide_add_top
				},
				function (response) {
					if (response.success) {

						$slides_list.find('.last_edit').removeClass('active');

						var slide = '<li data-id="' + response.slide_id + '"><div class="holder"><div class="cell last_edit active"></div><div class="cell w_20 number"></div><div class="cell w_20 icon"><a href="#" class="hide-show active"></a></div><div class="cell no_padding w_<?=$thumb[0];?>" style="width:300px; height:300px;"></div><div class="cell w_20 icon"><a href="<?=$this->uri->full_url('admin/slider/edit?menu_id=' . $menu_id . '&slide_id='); ?>' + response.slide_id + '" class="edit"></a></div><div class="cell auto"><span class="menu_item">Новий слайд</span></div><div class="cell w_20 icon sorter"><a href="#" class="single_arrows"></a></div><div class="cell w_20 icon"><a href="#" class="delete"></a></div></div></li>';
						(slide_add_top === 1) ? $slides_list.prepend(slide) : $slides_list.append(slide);

						row_decor();
						component_loader_hide($('.component_loader'), '');
					}
				},
				'json'
			);
		});

		$slides_list
			/**
			 * Відображення/приховування
			 */
			.on('click', '.hide-show', function (e) {
				e.preventDefault();

				var $holder = $(this).closest('.holder'),
					status = 0;

				if ($holder.hasClass('hidden')) {
					$holder.removeClass('hidden');
					$(this).addClass('active');
				} else {
					$holder.addClass('hidden');
					$(this).removeClass('active');
					status = 1;
				}

				component_loader_show($('.component_loader'), '');
				$('#slides_list').find('.last_edit').removeClass('active');
				$holder.find('.last_edit').addClass('active');

				$.post(
					full_url('admin/slider/hidden'),
					{
						menu_id: <?=$menu_id?>,
						slide_id: $holder.closest('li').data('id'),
						hidden: status
					},
					function (response) {
						if (response.success) component_loader_hide($('.component_loader'), '');
					},
					'json'
				);
			})
			/**
			 * Видалення
			 */
			.on('click', '.delete', function (e) {
				e.preventDefault();

				var $link = $(this);

				confirmation('Видалити слайд?', function () {
					component_loader_show($('.component_loader'), '');
					$.post(
						full_url('admin/slider/delete'),
						{
							menu_id: <?=$menu_id?>,
							slide_id: $link.closest('li').data('id')
						},
						function (response) {
							if (response.success) {
								$link.closest('li').slideUp(function () {
									$(this).remove();
									row_decor();
								});
								component_loader_hide($('.component_loader'), '');
							}
						},
						'json'
					);
				});
			})
			.on('mouseover', '.holder', function () {
				$(this).addClass('active');
			})
			.on('mouseout', '.holder', function () {
				$(this).removeClass('active');
			});

		/**
		 * Додавати внизу/вверху
		 */
		$('.add_bottom').on('click', function (e) {
			e.preventDefault();

			if (!$(this).find('b').hasClass('active')) {
				$(this).find('b').addClass('active');
				slide_add_top = 0;
			} else {
				$(this).find('b').removeClass('active');
				slide_add_top = 1;
			}
		});
	});
</script>