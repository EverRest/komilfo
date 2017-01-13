<?php
	defined('ROOT_PATH') OR exit('No direct script access allowed');

	$this->template_lib->set_js('admin/ckeditor/ckeditor.js');
	$this->template_lib->set_js('admin/jquery.form.js');
?>
<?php //print_r($services);exit;?>
<div class="admin_component">
	<div class="component_loader"></div>
	<div class="fm adcom_panel">
		<div class="fm type_of_component">
			<div class="article"></div>
		</div>
		<div class="fm component_edit_links">
        	<div class="fm only_text"><div>Редагування компонента</div></div>
<!--			<a href="#" class="fm save"><b></b>Зберегти</a>-->
<!--			<a href="#" class="fm apply"><b></b>Застосувати</a>-->
<!--			<a href="#" class="fm cancel"><b></b>Скасувати</a>-->
            <a id="add_title_service" href="#" class="fm save_adm"><i class="fa fa-plus" aria-hidden="true"></i>  Додати заголовок</a>
            <a id="add_service" href="#" class="fm save_adm"><i class="fa fa-plus" aria-hidden="true"></i>  Додати послугу</a>
		</div>
	</div>
	<div class="fm admin_view_article">
		<form id="component_article_form" class="service_form" action="<?=$this->uri->full_url('/admin/benefit_services/update_article');?>" method="post">
			<input type="hidden" name="component_id" value="<?=$component_id;?>">
            <input type="hidden" name="menu_id" value="<?=$menu_id;?>">
            <div id="service_container" class="centre service_container">
                <?php if(!isset($services[0]['header']) || empty($services[0]['header'])): ?>
                <div class="evry_title">
                    <label for="ca_price" class="block_label">Заголовок для секції послуг:</label>
                    <input type="text" class="service_input" id="ca_header"name="ca_header" placeholder="Назва..." required="required">
                    <span class="success-btn"><i class="fa fa-check" aria-hidden="true"></i></span>
                </div>
                <ul id="service_details">
                    <li data-id="1">
                        <div class="evry_title">
                            <label for="ca_item_title" class="block_label">Послуга:</label>
                            <input type="text" class="service_input" id="ca_desc_1"name="ca_desc_1" placeholder="Опис..." required="required">
                            <span class="success-btn"><i class="fa fa-check" aria-hidden="true"></i></span>
                        </div>
                        <div class="evry_title">
                            <label for="ca_price" class="block_label">Ціна:</label>
                            <input type="text" class="service_input" id="ca_price_1"name="ca_price_1" placeholder="0" required="required">
                            <span class="success-btn"><i class="fa fa-check" aria-hidden="true"></i></span>
                        </div>
                    </li>
                </ul>
                <? else: ?>
                <h1><?=$services[0]['header'];?></h1>
                <input type="hidden" name="ca_header" value="<?=$services[0]['header'];?>">
                <ul id="service_details">
                    <?php foreach( $services as $service): ?>
                    <li data-id="<?php echo $service['id']; ?>">
                        <p>
                            <strong class="text-uppercase">Послуга: </strong><?php echo $service['description'].'.<br>'; ?>
                            <strong class="text-uppercase">Ціна: </strong><?php echo $service['price'].' грн.' ?>
                        </p>
                    </li>
                    <?php endforeach;?>
                <?php endif; ?>
            </div>
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

    function service() {
        var i=2;
        checkout_input();

        $('#add_title_service').on('click', function (e) {
            e.preventDefault();
            console.log('added title');
        })

        $('#add_service').on('click', function (e) {
            e.preventDefault();
            var li = '<li data-id="'+i+'">'+
                            '<div class="evry_title">'+
                                '<label for="ca_item_title" class="block_label">Послуга:</label>'+
                                '<input type="text" class="service_input" id="ca_header_' + i + '" name="ca_header_' + i + '" placeholder="Опис..." required="required">'+
                                '<span class="success-btn"><i class="fa fa-check" aria-hidden="true"></i></span>'+
                            '</div>'+
                            '<div class="evry_title">'+
                                '<label for="ca_price" class="block_label">Ціна:</label>'+
                                '<input type="text" class="service_input" id="ca_price_' + i + '" name="ca_price_' + i + '" placeholder="0" required="required">'+
                                '<span class="success-btn"><i class="fa fa-check" aria-hidden="true"></i></span>'+
                            '</div>'+
                        '<li>';
            $('#service_details').append(li);
            checkout_input();
            i++;
        });

    }

    function checkout_input() {

        $('#component_article_form').find('input').focus(function () {
            $(this).next('span').css({'color':'green'});
        });

        $('#component_article_form').find('input').focusout(function () {
            var value = $(this).val();
            if (value == '') {
                $(this).next('span').css({'color': '#d1d1d1'});
            }
        });
    }

	function save_component_article(callback) {
		component_loader_show($('.component_loader'), '');
//		$('.component_article').ckeditor({action: 'update'});
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
	    service();

		$('.component_article').ckeditor({height: 200});

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