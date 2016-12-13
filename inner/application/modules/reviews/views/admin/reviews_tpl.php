<?php
	defined('ROOT_PATH') OR exit('No direct script access allowed');

	$this->template_lib->set_js('admin/ui/jquery-ui-1.10.3.custom.min.js');

?>
<div class="admin_component" id="admin_component_<?=$component_id;?>" data-component-id="<?=$component_id;?>" data-menu-id="<?=$menu_id;?>" data-module="reviews" data-css-class="reviews" data-visibility-url="<?=$this->uri->full_url('admin/components/toggle_visibility');?>" data-delete-url="<?=$this->uri->full_url('admin/reviews/delete_component');?>">
	<div class="component_loader"><span></span></div>
	<div class="fm adcom_panel">
		<div class="fm type_of_component">
			<div class="<?=(($hidden == 0) ? 'reviews' : 'hidden');?>"></div>
		</div>
		<div class="fm component_edit_links">
			<div class="fm only_text"><div>Відгуки наших клієнтів</div></div>
			<a class="fm add add_review" href="#"><b></b>Додати відгук</a>
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
		<?php if (isset($reviews)): ?>
		<ul id="reviews_list">
			<?php if (count($reviews) > 0): ?><?php foreach ($reviews as $v): ?>
			<li data-review-id="<?=$v['review_id'];?>">
				<div class="holder<?php if ($v['hidden'] == 1): ?> hidden<?php endif; ?>">
					<div class="cell last_edit <?php if (isset($last_review) AND ($v['review_id'] == $last_review)) echo ' active'; ?>"></div>
					<div class="cell w_30 number"></div>
					<div class="cell w_20 icon"><a href="#" class="hide-show<?php if ($v['hidden'] == 0): ?> active<?php endif; ?>"></a></div>
					<div class="cell no_padding" style="width:108px"><?php if ($v['image'] != ''): ?><img src="<?=base_url('upload/reviews/' . $this->init_model->dir_by_id($v['review_id']) . '/' . $v['review_id'] . '/' . $v['image'] . '?t=' . time() . rand(10000, 1000000));?>" alt=""><?php endif; ?></div>
					<div class="cell w_20 icon"><a href="<?=$this->uri->full_url('admin/reviews/edit?menu_id=' . $menu_id . '&review_id=' . $v['review_id']);?>" class="edit"></a></div>
					<div class="cell auto"><?=(($v['title'] != '') ? $v['title'] : 'Новий відгук');?></div>
					<div class="cell w_20 icon sorter"><a href="#" class="single_arrows"></a></div>
					<div class="cell w_20 icon"><a href="#" class="delete"></a></div>
				</div>
			</li>
			<?php endforeach; ?><?php endif; ?>
		</ul>
		<div class="fm admin_massage <?php if (count($reviews) > 0): ?> adm_hidden<?php endif; ?>">Список відгуків пустий.</div>
		<?php endif; ?>
	</div>
</div>

<script type="text/template" id="review_template">
	<li data-review-id="%review_id%">
		<div class="holder">
			<div class="cell last_edit active"></div>
			<div class="cell w_30 number"></div>
			<div class="cell w_20 icon"><a href="#" class="hide-show active"></a></div>
			<div class="cell no_padding" style="width:108px"></div>
			<div class="cell w_20 icon"><a href="<?=$this->uri->full_url('admin/reviews/edit?menu_id=' . $menu_id . '&review_id=%review_id%');?>" class="edit"></a></div>
			<div class="cell auto">Новий відгук</div>
			<div class="cell w_20 icon sorter"><a href="#" class="single_arrows"></a></div>
			<div class="cell w_20 icon"><a href="#" class="delete"></a></div>
		</div>
	</li>
</script>

<script type="text/javascript">

	$(document).ready(function () {

		var $component = $('#admin_component_<?=$component_id;?>'),
			$reviews = $('#reviews_list');

		function rebuild() {
			$reviews.find('li').map(function (i) {
				$(this).find('.number').text(i + 1);

				if (i % 2 == 0) {
					$(this).addClass('grey');
				} else {
					$(this).removeClass('grey');
				}
			});

			make_sortable();
		}

		rebuild();

		$component
			.component()
			.on('click', '.add_review', function (e) {
				e.preventDefault();

				component_loader_show($component.find('.component_loader'), '');

				$.post(
					'<?=$this->uri->full_url('admin/reviews/insert');?>',
					{
						component_id: <?=$component_id;?>,
						menu_id: <?=$menu_id;?>
					},
					function (response) {
						if (response.success) {
							$component.find('.admin_massage').addClass('adm_hidden');

							var row = $('#review_template').html();
							$reviews.prepend(row.split('%review_id%').join(response.review_id));

							rebuild();
							component_loader_hide($component.find('.component_loader'), '');
						}
					},
					'json'
				);
			});


		function make_sortable() {
			$("#reviews_list")
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
						var position = [];

						$reviews.find('li').each(function () {
							position[$(this).index()] = $(this).data('review-id');
						});

						$.post(
							'<?=$this->uri->full_url('admin/reviews/save_position');?>',
							{
								position: position,
								menu_id: <?=$menu_id;?>
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

		$reviews
			.on('click', '.hide-show', function (e) {
				e.preventDefault();

				var $link = $(this),
					status = 1,
					review_id = $link.closest('li').data('review-id');

				if ($link.hasClass('active')) {
					$link.removeClass('active');
				} else {
					$link.addClass('active');
					status = 0;
				}

				component_loader_show($component.find('.component_loader'), '');

				$.post(
					'<?=$this->uri->full_url('admin/reviews/visible');?>',
					{
						review_id: review_id,
						status: status,
						menu_id: <?=$menu_id;?>
					},
					function (response) {
						if (response.success) component_loader_hide($component.find('.component_loader'), '');
					},
					'json'
				);
			})
			.on('click', '.delete', function (e) {
				e.preventDefault();

				var $link = $(this),
					review_id = $link.closest('li').data('review-id');

				confirmation('Видалити відгук?', function () {

					component_loader_show($component.find('.component_loader'), '');

					$.post(
						'<?=$this->uri->full_url('admin/reviews/delete');?>',
						{
							review_id: review_id,
							menu_id: <?=$menu_id;?>
						},
						function (response) {
							if (response.success) {
								$link.closest('li').slideUp(function () {
									$(this).remove();
									if ($reviews.find('li').length == 0) $component.find('.admin_massage').removeClass('adm_hidden');
								});

								rebuild();
								component_loader_hide($component.find('.component_loader'), '');
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
	});
</script>