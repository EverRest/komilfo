<?php defined('ROOT_PATH') or exit('No direct script access allowed');

	/**
	 * @var bool $is_main
	 * @var string $top_menu
	 */
    $languages_1 = $this->config->item('languages');
    $languages = $this->config->item('database_languages');
    $length = count($languages);
    $menu_lang = $this->config->item('menu_languages');
    $site_name = $this->config->item('site_name_'.LANG);
    $site_email = $this->config->item('site_email');

    
?>
<!-------------- Header -------------->
<header class="header-wrapper fm">
    <div class="top-line fm">
        <div class="center">
            <div class="header-search">
                <input type="text" class="search" placeholder="<?=lang('search')?>">
                <a href="#" class="search-btn"><i class="icon-search"></i></a>
            </div>
            <div class="header-social">
                <? if(isset($static['fb']))?><a href="<?=$static['fb']?>" class="fb" target="_blank"><i class="icon-facebook"></i></a>
            </div>
            <div class="header-lang">
                <?php foreach ($languages as $key => $language): ?>
                    <a href="<?=(isset($language_links[$language['code']]) ? $language_links[$language['code']] : $this->init_model->get_link($menu_id, '{URL}', FALSE, FALSE, $language['code']));?>" class="<?=(LANG == $language['code'] ? ' active' : '');?>"><?=$language['code']?></a>
                <? endforeach; ?>
            </div>
        </div>
    </div>
    <div class="header-main fm">
        <div class="center">
            <a href="#" class="logo"><img src="<?=base_url("images/logo.png")?>" alt="tanya grig"></a>
        </div>
    </div>
    <div class="menu-line fm">
        <div class="center">
            <nav class="main-nav fm">
                <?=$top_menu?>
            </nav>
            <!------ mobile btn ------>
            <?=$adaptive_main?>
        </div>
    </div>
</header>
<?php if (!$this->init_model->is_main()): ?>
    <section class="breadcrumbs fm">
        <div class="center">
            <div class="fm clickpath">
                <?php foreach ($bread_crumbs['navigation'] as $key => $bc): ?>
                    <?php if ($key >= 1): ?>
                        <div <?=(count($bread_crumbs['navigation']) > $key+1? 'itemref="breadcrumb-'.($key+1).'?>"' : 'class="current"');?> id="breadcrumb-<?=$key?>" itemtype="http://data-vocabulary.org/Breadcrumb" itemscope="" itemprop="child">
                            <b>&frasl;</b><a itemprop="url" href="<?=$bc[0]?>"><span itemprop="title"><?=$bc[1]?></span></a>
                        </div>
                    <?php else: ?>
                         <div itemref="breadcrumb-1" itemtype="http://data-vocabulary.org/Breadcrumb" itemscope="">
                            <a itemprop="url" href="<?=$bc[0]?>"><span itemprop="title"><?=lang('home')?></span></a>
                        </div>
                    <?php endif; ?>
                <?php endforeach ?>
            </div>
        </div>
    </section>
<?php endif ?>
