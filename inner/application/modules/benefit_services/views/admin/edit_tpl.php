<?php
	defined('ROOT_PATH') OR exit('No direct script access allowed');

	$this->template_lib->set_js('admin/ckeditor/ckeditor.js');
	$this->template_lib->set_js('admin/jquery.form.js');
?>
<div class="admin_component">
	<div class="component_loader"></div>
	<div class="fm adcom_panel">
		<div class="fm type_of_component">
			<div class="article"></div>
		</div>
		<div class="fm component_edit_links">
        	<div class="fm only_text"><div>Редагування компонента</div></div>
			<a href="#" class="fm save"><b></b>Зберегти</a>
			<a href="#" class="fm apply"><b></b>Застосувати</a>
			<a href="#" class="fm cancel"><b></b>Скасувати</a>
		</div>
		<?php if (count($languages) > 1): ?>
		<div class="fmr component_lang">
			<?php foreach ($languages as $key => $val): ?>
				<a href="#" class="flags <?=$key;?><?=(($key == LANG) ? ' active' : '');?>" data-language="<?=$key;?>"><img src="img/flags_<?=$key;?>.png"></a>
			<?php endforeach; ?>
		</div>
		<?php endif; ?>
	</div>
	<div class="fm admin_view_article">
		<form id="component_article_form" action="<?=$this->uri->full_url('/admin/benefit_services/update_article');?>" method="post">
			<input type="hidden" name="component_id" value="<?=$component_id;?>">
<!--			<input type="hidden" name="menu_id" value="--><?//=$menu_id;?><!--">-->
<!--			<input type="hidden" name="id" value="--><?//=$item_id;?><!--">-->
<!--			<div class="evry_title">-->
<!--				<label class="block_label">Назва компонента:</label>-->
<!--				<input type="text" id="ca_title" name="title" value="" placeholder="Послуги та Ціни">-->
<!--			</div>-->
            <div class="evry_title">
                <input type="button" id="add_type" class="btn-add" value="Додати послугу">
            </div>
<!--            <ul class="service_item">-->
<!--                <li class="service_description">-->
<!--                    <div class="evry_title">-->
<!--                        <label for="ca_service" class="block_label">Послуга:</label>-->
<!--                        <input type="text" id="ca_service" name="ca_service" value="">-->
<!--                    </div>-->
<!--                    <div class="evry_title">-->
<!--                        <label for="ca_price" class="block_label">Ціна:</label>-->
<!--                        <input type="text" id="ca_price" name="ca_price" value="0">-->
<!--                    </div>-->
<!--                </li>-->
<!--            </ul>-->
			<div class="fm for_sucsess">
				<div class="fmr save_links">
					<a href="#" class="fm save_adm"><b></b>Зберегти</a>
					<a href="#" class="fm apply_adm"><b></b>Застосувати</a>
					<a href="#" class="fm cansel_adm"><b></b>Скасувати</a>
				</div>
			</div>
		</form>
	</div>
</div>
<script type="text/javascript">

    function add_service_type() {
        var form = '#component_article_form';
        var i = 0;
        $(form).find('#add_type').on('click', function () {
            i++;
            var title = '<div class="evry_title service_container"><label for="ca_type" class="block_label">Послугa ' + i + ':</label><input type="text" class="input-title" id="ca_type-' + i + ' name="ca_type" value=""></div><ul id="service-' + i + '"></ul>';
            $('#add_type').hide();
            $(form).prepend(title);
            $(form).keypress(function (e){
                if (e.which == 13) {
                    var data = $('.evry_title').find('.input-title').val();
                    console.log(data);
                    var service_header = '<h1>'+ data +'</h1>';
                    $(form).find('.service_container').remove();
                    $(form).prepend(service_header);
                    $('#add_type').show();
                    return false;
                }
            });
        });
    }

    function add_service_item(id) {

    }

	function save_component_article(callback) {
		component_loader_show($('.component_loader'), '');
		$('.component_article').ckeditor({action: 'update'});
		$('#component_article_form').ajaxSubmit({
			success:function (response) {
				component_loader_hide($('.component_loader'), 'Зміни збережено');
				if ($.type(callback) === 'function') callback();
			},
			dataType: 'json'
		});
	}

	function cancel_editing() {
		window.location.href = '<?=$this->init_model->get_link($menu_id, '{URL}');?>';
	}

	$(function () {
		$('.component_article').ckeditor({height: 200});

        add_service_type();

		$('.component_lang').find('a').on('click', function (e) {
			e.preventDefault();

			$(this).closest('div').find('.active').removeClass('active');
			$(this).addClass('active');

			$('.article_tab').hide();
			$('#article_tab_' + $(this).data('language')).show();
		});

		$('.component_edit_links .save, .for_sucsess .save_adm').on('click', function (e) {
			e.preventDefault();
			save_component_article(function () {
				cancel_editing();
			});
		});

		$('.component_edit_links .apply, .for_sucsess .apply_adm').on('click', function (e) {
			e.preventDefault();
			save_component_article('');
		});

		$('.component_edit_links .cancel, .for_sucsess .cansel_adm').on('click', function (e) {
			e.preventDefault();
			cancel_editing();
		});
	});
</script>