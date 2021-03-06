<?php
	defined('ROOT_PATH') OR exit('No direct script access allowed');

	$this->template_lib->set_js('admin/ui/jquery-ui-1.10.3.custom.min.js');
	$thumb = array(300, 150);

?>
<div class="fm admin_component" id="admin_component_<?=$component_id;?>" data-component-id="<?=$component_id;?>" data-menu-id="<?=$menu_id;?>" data-module="swiper" data-css-class="swiper" data-visibility-url="<?=$this->uri->full_url('admin/components/toggle_visibility');?>" data-delete-url="<?=$this->uri->full_url('admin/swiper/delete_component');?>">
	<div class="component_loader"></div>
	<div class="fm adcom_panel">
		<div class="fm type_of_component">
			<div class="gallery"></div>
		</div>
		<div class="fm component_edit_links">
			<a class="fm add" href="#" id="add_swipe"><b></b>Додати свайп</a>
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
		<ul id="swipes_list">
		<?php if (count($swipes) > 0): ?>
			<?php foreach ($swipes as $slide): ?>
			<li data-id="<?php echo $slide['slide_id']; ?>">
				<div class="holder<?php if ($slide['hidden'] == 1): ?> hidden<?php endif; ?>">
					<div class="cell last_edit <?php if (isset($last_slide) AND ($slide['slide_id'] == $last_slide)) echo 'active'; ?>"></div>
					<div class="cell w_20 number"></div>
					<div class="cell w_20 icon"><a href="#" class="hide-show<?php if ($slide['hidden'] == 0): ?> active<?php endif; ?>"></a></div>
					<div class="cell no_padding w_<?=$thumb[0];?>" style="width: 300px; height: 150px;">
						<?php if ($slide['file_name'] != ''): ?>
						<img src="/upload/swiper/<?php echo $menu_id; ?>/<?php echo $slide['slide_id']; ?>/<?php echo $slide['file_name']; ?>" style="width: 300px; height: 150px;">
						<?php endif; ?>
					</div>
					<div class="cell w_20 icon">
						<a href="<?php echo $this->uri->full_url('admin/swiper/edit?menu_id=' . $menu_id . '&slide_id=' . $slide['slide_id']); ?>" class="edit"></a>
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

	function sw_row_decor() {
        console.log('sw_row_decor');
		$('#swipes_list')
			.find('li').removeClass('grey').each(function (i) { $(this).find('.number').text(i + 1); })
			.end()
			.find('li:even').addClass('grey');

		sw_make_sortable();
	}

	function sw_make_sortable() {
        console.log('sw_make_sortable');
		$("#swipes_list")
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

					var swipes = [];

					$('#swipes_list').find('li').each(function (i) {
						swipes[i] = $(this).data('id');
						$(this).find('.number').text(i + 1);
					});

					$.post(
						'<?=$this->uri->full_url('admin/swiper/swipes_position');?>',
						{
							menu_id: <?=$menu_id?>,
							slide_id: $(object.item).data('id'),
							slides: swipes
						},
						function (response) {
							if (response.success) {
								component_loader_hide($('.component_loader'), '');
								sw_row_decor();
							}
						},
						'json'
					);
				}
			});
	}

	$(function () {

		$('#admin_component_<?=$component_id;?>').component();

		sw_row_decor();
		var $swipes_list = $('#swipes_list');

		$('#add_swipe').on('click', function (e) {
			e.preventDefault();

			component_loader_show($('.component_loader'), '');

			$.post(
				full_url('admin/swiper/add'),
				{
					menu_id: <?=$menu_id?>,
					add_top: slide_add_top
				},
				function (response) {
					if (response.success) {

						$swipes_list.find('.last_edit').removeClass('active');

						var slide = '<li data-id="' + response.slide_id + '"><div class="holder"><div class="cell last_edit active"></div><div class="cell w_20 number"></div><div class="cell w_20 icon"><a href="#" class="hide-show active"></a></div><div class="cell no_padding w_<?=$thumb[0];?>" style="width:300px; height:150px;"></div><div class="cell w_20 icon"><a href="<?=$this->uri->full_url('admin/swiper/edit?menu_id=' . $menu_id . '&slide_id='); ?>' + response.slide_id + '" class="edit"></a></div><div class="cell auto"><span class="menu_item">Новий слайд</span></div><div class="cell w_20 icon sorter"><a href="#" class="single_arrows"></a></div><div class="cell w_20 icon"><a href="#" class="delete"></a></div></div></li>';
						(slide_add_top === 1) ? $swipes_list.prepend(slide) : $swipes_list.append(slide);

						sw_row_decor();
						component_loader_hide($('.component_loader'), '');
					}
				},
				'json'
			);
		});

		$swipes_list
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
				$('#swipes_list').find('.last_edit').removeClass('active');
				$holder.find('.last_edit').addClass('active');

				$.post(
					full_url('admin/swiper/hidden'),
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
						full_url('admin/swiper/delete'),
						{
							menu_id: <?=$menu_id?>,
							slide_id: $link.closest('li').data('id')
						},
						function (response) {
							if (response.success) {
								$link.closest('li').slideUp(function () {
									$(this).remove();
									sw_row_decor();
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