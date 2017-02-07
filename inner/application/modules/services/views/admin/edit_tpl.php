<?php
	if (!defined('BASEPATH')) exit('No direct script access allowed');

	$this->template_lib->set_js('admin/ckeditor/ckeditor.js');
	$this->template_lib->set_js('admin/jquery.form.js');
	$this->template_lib->set_js('admin/ui/jquery-ui-1.10.3.custom.min.js');
	$this->template_lib->set_js('admin/fineuploader/jquery.fineuploader-3.5.0.min.js');
	$this->template_lib->set_js('admin/jcrop/js/jquery.Jcrop.min.js');

	$this->template_lib->set_css('js/admin/fineuploader/fineuploader-3.5.0.css', TRUE);
	$this->template_lib->set_css('js/admin/jcrop/css/jquery.Jcrop.min.css', TRUE);
?>
<div class="admin_component" id="services_component">
	<div class="component_loader"><span></span></div>
	<div class="fm adcom_panel">
		<div class="fm type_of_component">
			<div class="services"></div>
		</div>
		<div class="fm component_edit_links">
			<a href="#" class="fm save"><b></b>Зберегти</a>
			<a href="#" class="fm apply"><b></b>Застосувати</a>
			<a href="<?=$this->init_model->get_link($menu_id, '{URL}');?>" class="fm cancel"><b></b>До списку Послуг</a>
		</div>
		<?php if (count($languages) > 1): ?>
			<div class="fmr component_lang">
				<?php foreach ($languages as $key => $val): ?>
					<a href="#" class="flags <?=$key;?><?=(($key == LANG) ? ' active' : '');?>" data-language="<?=$key;?>"><img src="img/flags_<?=$key;?>.png"></a>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>
	<form id="services_form" action="#" method="post">
		<input type="hidden" id="menu_id" name="menu_id" value="<?=$menu_id;?>">
		<input type="hidden" id="services_id" name="services_id" value="<?=$services_id;?>">
        <div class="fm admin_menu">
            <ul id="admin_sortable_menu" data-menu-id="<?=$menu_id;?>">
                <?php if(empty($services['title'])  AND empty($services['text'])  AND empty($services['price']) ): ?>
                <li id="header_section">
                    <div class="holder">
                        <div class="cell last_edit">
                        </div>
                        <div class="cell w_20 icon">
<!--                            <a href="#" class="hide-show  active"></a>-->
                        </div>
                        <div class="cell auto"><input type="text" name="title" id="title" class="title" placeholder="Заголовок секції послуг" required="required">
                        </div>
                        <div class="cell w_20 icon">
<!--                            <a href="#" class="delete"></a>-->
                        </div>
                    </div>
                </li>
                <li class="item_wrapper" data-id="1">
                    <div class="holder">
                        <div class="cell last_edit">
                        </div>
                        <div class="cell w_20 icon">
                        </div>
                        <div class="cell w_20 icon" >
                            <span class="add_subservice glyphicon glyphicon-plus" aria-hidden="true">

                            </span>
                        </div>
                        <div class="cell auto">
                            <input name="title_item" id="title_item" class="title-item" type="text" placeholder="Назва послуги" required="required" />
                        </div>
                        <div class="cell w_100 icon"><input name="price" id="price_item" class="title-price" type="text" placeholder="000.000" required="required" />
                        </div>
                        <div class="cell w_20 icon">
                        </div>
                    </div>
                </li>
                <?php else: ?>
                    <?php $items = array(); $prices = array();
                    $items = explode('~',$services['text']);
                    $prices = explode('~',$services['price'])?>
                    <li id="header_section">
                        <div class="holder">
                            <div class="cell last_edit">
                            </div>
                            <div class="cell w_20 icon">
<!--                                <a href="#" class="hide-show  active"></a>-->
                            </div>
                            <div class="cell auto">
                                <input type="text" name="title" id="title" class="title" placeholder="Заголовок секції послуг" required="required" value="<?=$services['title'];?>">
                            </div>
                            <div class="cell w_20 icon">
<!--                                <a href="#" class="delete"></a>-->
                            </div>
                        </div>
                    </li>
                    <?php for($i = 0;$i < count($items) - 1; $i++):?>
                    <li class="item_wrapper" data-id="1">
                        <div class="holder">
                            <div class="cell last_edit">
                            </div>
                            <div class="cell w_20 icon">
                            </div>
                            <div class="cell w_20 icon" >
                            <?php if($i == 0): ?>
                            <span class="add_subservice glyphicon glyphicon-plus" aria-hidden="true">
                            </span>
                            <?endif;?>
                            </div>
                            <div class="cell auto">
                                <input name="title_item" id="title_item" class="title-item" type="text" placeholder="Назва послуги" required="required" value="<?=$items[$i];?>" />
                            </div>
                            <div class="cell w_100 icon"><input name="price" id="price_item" class="title-price" type="text" placeholder="000.000" required="required" value="<?=$prices[$i];?>" />
                            </div>
                            <div class="cell w_20 icon">
                            </div>
                        </div>
                    </li>
                    <?php endfor; ?>
                <?php endif; ?>
            </ul>
        </div>
        <div class="fm for_sucsess">
            <div class="fmr save_links">
                <a href="#" class="fm save_adm"><b></b>Зберегти</a>
                <a href="#" class="fm apply_adm"><b></b>Застосувати</a>
                <a href="<?=$this->init_model->get_link($menu_id, '{URL}');?>" class="fm cansel_adm"><b></b>До списку послуги</a>
            </div>
        </div>
	</form>
</div>
<script type="text/javascript">
    $(document).ready(function () {

        $('.add_subservice').on('click', function (e) {
            var cur_id =  + $(this).parent().parent().parent('.item_wrapper').data('id');
                cur_id++;
            e.preventDefault();

            $("#admin_sortable_menu").append('<li class="item_wrapper" data-id='+ cur_id +'>' +
                '<div class="holder">' +
                '<div class="cell last_edit">' +
                '</div>' +
                '<div class="cell w_20 icon">' +
                '</div>' +
                '<div class="cell w_20 icon" >' +
                '</div>' +
                '<div class="cell auto">' +
                '<input name="title_item" id="title_item" class="title-item" type="text" placeholder="Назва послуги" />' +
                '</div>' +
                '<div class="cell w_100 icon"><input name="price" id="price_item" type="text" placeholder="000.000" />' +
                '</div>' +
                '<div class="cell w_20 icon">' +
                '</div>' +
                '</div>' +
                '</li>');
        });
        $('.save_adm, .apply_adm').on('click', function (e) {
            var data = {};

            e.preventDefault();
            data.title = $('#title').val();
            data.menu_id = $('#menu_id').val();
            data.services_id = $('#services_id').val();
            data.service = '';
            data.price = '';
            data.error = true;

            $('.item_wrapper').each(function (i, elem) {
                var s_title = $(elem).find('#title_item').val(),
                    s_price = $(elem).find('#price_item').val();
                if( (s_title != undefined && s_title != '') && (s_price != undefined && s_price != '')) {
                    data.service = data.service + s_title + '~';
                    data.price = data.price + s_price + '~';
                } else {
                    e.preventDefault();
                    data.error = false;
                }
            });
            if(data.error) {
                ajax_send(data);
            }
        });
    });

    function ajax_send (data) {
        $.ajax({
            type: 'POST',
            url: '<?=$this->uri->full_url('admin/services/save');?>',
            data: data,
            success: function (response) {
                if (response.success) {
                    window.location.replace("<?=$this->init_model->get_link($menu_id, '{URL}');?>");
                }
            },
            dataType: 'json'
        });
    }
</script>