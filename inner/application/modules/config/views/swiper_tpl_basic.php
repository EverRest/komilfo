<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php

//	$this->template_lib->set_js('admin/jquery.form.js');
	$this->template_lib->set_css('js/admin/ui/jquery-ui-1.10.3.custom.min.css', TRUE);
	$this->template_lib->set_js('admin/ui/jquery-ui-1.10.3.custom.min.js');
	$this->template_lib->set_js('admin/ui/jquery.ui.datepicker-uk.js');
	$this->template_lib->set_css('js/admin/fineuploader/fineuploader-3.5.0.css', TRUE);
	$this->template_lib->set_js('admin/fineuploader/jquery.fineuploader-3.5.0.min.js');

	$this->template_lib->set_css('js/admin/jcrop/css/jquery.Jcrop.min.css', TRUE);
	$this->template_lib->set_js('admin/jcrop/js/jquery.Jcrop.min.js');

	$this->template_lib->set_js('admin/ckeditor/ckeditor.js');
	$this->template_lib->set_js('admin/jquery.form.js');
?>
<style type="text/css">
	.evry_title { padding-left: 25px; box-sizing: border-box;}
	.evry_title label {position: relative; float: left; width: 115px;}
	.edit_object { width: 100%; font-weight: bold;}
	.one_input {width: 100%; margin-top: 10px;}
	.one_input input {margin-right: 15px;}
	.input_phone input {width: 20%;}
</style>
<?// echo '<pre>';print_r($swiper);exit; ?>
<div class="fm admin_component">
	<div class="component_loader"></div>
	<div class="fm adcom_panel">
		<div class="fm type_of_component">
			<div class="header_set"></div>
		</div>
		<div class="fm component_edit_links">
			<a href="#" class="fm save"><b></b>Зберегти</a>
		</div>
	</div>
	<div class="centre">
		<form id="timer_form" action="<?=$this->uri->full_url('admin/config/save_swiper');?>" method="post">
			<div class="fm admin_menu">
				<ul id="slides_list">
					<? $slides = array(); ?>
					<?php if (count($slides) > 0): ?>
						<?php foreach ($slides as $slide): ?>
							<li data-id="<?php echo $slide['slide_id']; ?>">
								<div class="holder<?php if ($slide['hidden'] == 1): ?> hidden<?php endif; ?>">
									<div class="cell last_edit <?php if (isset($last_slide) AND ($slide['slide_id'] == $last_slide)) echo 'active'; ?>"></div>
									<div class="cell w_20 number"></div>
									<div class="cell w_20 icon"><a href="#" class="hide-show<?php if ($slide['hidden'] == 0): ?> active<?php endif; ?>"></a></div>
									<div class="cell no_padding w_<?=$thumb[0];?>" style="width: 300px; height: 300px;">
										<?php if ($slide['file_name'] != ''): ?>
											<img src="/upload/swiper/<?php echo $slide['slide_id']; ?>/<?php echo $slide['file_name']; ?>" style="width: 300px; height: 300px;">
										<?php endif; ?>
									</div>
									<div class="cell w_20 icon">
										<a href="<?php echo $this->uri->full_url('admin/swiper/edit?slide_id=' . $slide['slide_id']); ?>" class="edit"></a>
									</div>
									<div class="cell auto"><span class="menu_item"><?php echo ($slide['title'] != '') ? $slide['title'] : 'Новий слайд'; ?></span></div>
									<div class="cell w_20 icon sorter"><a href="#" class="single_arrows"></a></div>
									<div class="cell w_20 icon"><a href="#" class="delete"></a></div>
								</div>
							</li>
						<?php endforeach; ?>
					<?php endif; ?>
				</ul>
			</div>
		</form>
	</div>
	<div class="fm for_sucsess short">
		<div class="fm save_links">
			<a href="#" class="fm save_adm save"><b></b>Зберегти</a>
		</div>
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
//							menu_id: <?//=$menu_id?>//,
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

	$(document).ready(function () {
		$(function () {

			$('#admin_component_<?=$component_id;?>').component();

			row_decor();

			var $slides_list = $('#slides_list');

			$('#add_slide').on('click', function (e) {
				e.preventDefault();

				component_loader_show($('.component_loader'), '');

				$.post(
					full_url('admin/sider/add'),
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
		/**
		 * Збереження змін
		 */
		$('.for_sucsess .save, .component_edit_links .save').on('click', function (e) {
			e.preventDefault();

			$('#timer_form').ajaxSubmit({
				beforeSubmit: function () {
					component_loader_show($('.component_loader'));
				},
				success: function (response) {
					if (response.success) {
						component_loader_hide($('.component_loader'));
					}
				},
				dataType: 'json'
			});

			});

		$('.component_lang').find('a').on('click', function (e) {
			e.preventDefault();

			$(this).closest('div').find('.active').removeClass('active');
			$(this).addClass('active');

			$('.article_tab').hide();
			$('#article_tab_' + $(this).data('language')).show();
		});
	});
</script>