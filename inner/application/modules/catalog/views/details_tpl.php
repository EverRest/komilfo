<?php if (isset($catalog) AND count($catalog) > 0): ?>
    <?
        $this->template_lib->set_js('slick.min.js');
        $url_left = (!empty($catalog['left_link'])) ? $this->uri->full_url( $catalog['left_link']['url'] ) : '#';
        $url_right = (!empty($catalog['right_link'])) ? $this->uri->full_url( $catalog['right_link']['url']) : '#';
    ?>
    <section class="product-details fm">
        <div class="center">
            <div class="product-details_left fm">
                <div class="product-photo fm">
                    <div class="main-proto fm">
                        <?php foreach ($catalog['images'] as $key => $value): ?>
                            <div class="one-photo fm">
                                <img src="<?=base_url('upload/catalog/'.get_dir_code($value['catalog_id']).'/'.$value['catalog_id'].'/'.$value['photo'])?>" alt="<?=$catalog['title_'.LANG]?>">
                            </div>
                        <?php endforeach ?>
                    </div>
                    <div class="small-photo fm">
                        <?php foreach ($catalog['images'] as $key => $value): ?>
                            <div class="one-photo fm">
                                <img src="<?=base_url('upload/catalog/'.get_dir_code($value['catalog_id']).'/'.$value['catalog_id'].'/'.$value['photo'])?>" alt="<?=$catalog['title_'.LANG]?>">
                            </div>
                        <?php endforeach ?>
                    </div>
                </div>
            </div>
            <div class="product-details_right fm">
                <div class="product-details_name fm">
                    <h1><?=lang('tanya_grig')?></h1>
                    <h2><?=$catalog['title_'.LANG]?></h2>
                </div>
                <div class="product-details_descr fm">
                    <article>
                        <?=stripslashes($catalog['text_'.LANG])?>
                    </article>
                </div>
                <?php if ($shop_button): ?>
                    <div class="box-c fm">
                        <a href="<?=$shop_link?>" class="common-btn big-btn"><?=lang('shops')?></a>
                    </div>
                <?php endif ?>
            </div>
        </div>
        <a <?=($url_left == '#'? 'style="display:none;"' : '')?> href="<?=$url_left?>" class="page-nav page-prev"></a>
        <a <?=($url_right == '#'? 'style="display:none;"' : '')?> href="<?=$url_right?>" class="page-nav page-next"></a>
    </section> 
<?php endif; ?>