<?php
	defined('ROOT_PATH') OR exit('No direct script access allowed');
	$is_main = $this->init_model->is_main();
	$menu_id = $this->init_model->get_menu_id();
	$languages = $this->config->item('database_languages');
	$site_name = $this->config->item('site_name_'.LANG);
?>
<?php error_reporting( E_ERROR ); ?>
<!DOCTYPE html>
<html lang="uk">
    <head>
        <meta charset="utf-8">
        <base href="<?=base_url();?>">
        <title><?php if (isset($page_title)) echo $page_title; ?></title>
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400|Roboto+Condensed|Roboto:400,700&amp;subset=cyrillic" rel="stylesheet">
        <link rel="stylesheet" href="<?=base_url('css/style.css')?>">
        <link rel="stylesheet" href="<?=base_url('css/unique.css');?>">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        <script type="text/javascript" src="<?=base_url('js/jquery/jquery.min.js');?>"></script>
        <script type="text/javascript" src="<?=base_url('js/map.js');?>"></script>
        <?php if($this->init_model->is_admin()):?>
            <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,700&subset=latin,cyrillic' rel='stylesheet' type='text/css'>
            <link href='https://fonts.googleapis.com/css?family=Roboto:400,300&subset=latin,cyrillic' rel='stylesheet' type='text/css'>
            <script type="text/javascript" src="<?=base_url('js/html5.js');?>"></script>
            <script type="text/javascript" src="<?=base_url('js/jquery.maskedinput.min.js');?>"></script>
            <script type="text/javascript" src="<?=base_url('js/scripts.js?v=1');?>"></script>
            <script type="text/javascript" async src="<?=base_url('js/SmoothScroll.js');?>"></script>
            <script type="text/javascript" src="<?=base_url('js/components/slider_reviews.js');?>"></script>
            <?php if (isset($page_javascript)) echo $page_javascript; ?>
            <script type="text/javascript" src="<?=base_url('js/jquery.maskedinput.min.js');?>"></script>
<!--            <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js"></script>-->
            <!--[if IE]>
                <script src="js/html5.js"></script>
            <![endif]-->
            <!--[if IE 8]>
                <html class="ie8">
            <![endif]-->
             </noscript>
        <?php endif;?>
        <?php if (isset($page_description) AND $page_description != ''): ?><meta name="description" content="<?=$page_description;?>"><?php endif; ?>
        <?php if (isset($page_keywords) AND $page_keywords != ''): ?><meta name="keywords" content="<?=$page_keywords;?>"><?php endif; ?>
        <?php if (isset($page_css)) echo $page_css; ?>
        <link href="<?=base_url('favicon.ico');?>" rel="icon" type="image/x-icon">
        <link href="<?=base_url('favicon.ico');?>" rel="shortcut icon">
            <!--[if IE]>
              <script  src="js/html5.js"></script>
            <![endif]-->
            <!--[if IE 8]>
              <html class="ie8">
            <![endif]-->
<!--            </noscript>-->

<!--        <noscript>-->
<!--            <div style="display:inline;">-->
<!--            <img height="1" width="1" style="border-style:none;" alt="" src="//googleads.g.doubleclick.net/pagead/viewthroughconversion/932925977/?value=0&guid=ON&script=0"/>-->
<!--          </div>-->
<!--        </noscript>-->
    </head>
    <body>

    <!-- HEADER -->
    <header class="header">
        <div class="header-top">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 col-md-5 col-sm-12">
                        <div class="social-icons">
                            <ul class="social-list">
                                <li class="social-list-item"><a href="<?=$footer_data['fb'];?>" class="fb"><i class="icon icon-facebook"></i>Facebook</a></li>
                                <li class="social-list-item"><a href="<?=$footer_data['vk'];?>" class="vk"><i class="icon icon-vkontakte"></i>VK</a></li>
                                <li class="social-list-item"><a href="<?=$footer_data['ing'];?>" class="in"><i class="icon icon-instagram"></i>Instagram</a>
                                </li>
                            </ul>
                        </div>
                        <address class="address">
                            <span class="kvs"><?=$header_data['kyivstar_'.LANG]?></span>
                            <span class="life"><?=$header_data['life_'.LANG]?></span>
                        </address>
                    </div>

                    <div class="col-lg-6 col-md-7 col-sm-12">
                        <nav class="menu">
                            <div class="mobile-menu-icon icon-menu"></div>
                            <ul class="menu-list">
                                <?if(isset($menu['benefits']) && $menu_hidden['benefits'] != 1){?><li class="menu-list-item"><a href="#benefits">Про Нас</a></li><?}?>
                                <?if(isset($menu['guarantee']) && $menu_hidden['guarantee'] != 1){?><li class="menu-list-item"><a href="#guarantee">Переваги</a></li><?}?>
                                <?if(isset($menu['slider']) && $menu_hidden['slider'] != 1){?><li class="menu-list-item"><a href="#slider">Наші роботи</a></li><?}?>
                                <?if(isset($menu['benefit_services']) && $menu_hidden['benefit_services'] != 1){?><li class="menu-list-item"><a href="#benefit_services">Послуги та ціни</a></li><?}?>
                                <?if(isset($menu['google_map']) && $menu_hidden['google_map'] != 1){?><li class="menu-list-item"><a href="#google_map">Контакти</a></li><?}?>
                            </ul>
                        </nav>

                    </div>
                </div>
            </div>
        </div>
        <div class="logo">
            <a href="/">
                <img src="<?=base_url('images/logo.png');?>" alt="">
            </a>
        </div>
        <!-- Swiper -->
        <div class="main-slider swiper-container">
            <div class="swiper-wrapper">
                <?php if ( !isset($swipes[0]['file_name']) || empty($swipes[0]['file_name']) ): ?>
                    <?php $url = 'images/main-slider/01.jpg'; ?>
                    <div class="swiper-slide slide-bg" style="background-image: url('<?= base_url($url); ?>')">
                        <div class="slide-content">
                            <div class="slide-text text-uppercase" data-caption-animate="fadeInUp">
                                Комільфо - тільки так і не інакше
                            </div>
                            <a href="#" class="btn btn-default btn-sub open-popup" data-caption-delay="450" data-caption-animate="fadeIn"><span>ЗАПИС НА ПРИЙОМ</span></a>
                        </div>
                    </div>
                <?php endif; ?>
                <?php foreach($swipes as $swipe): ?>
                    <?php if($swipe['hidden'] != 1): ?>
                    <?php $url = 'upload/swiper/1/'.$swipe['slide_id'].'/'.$swipe['file_name']; ?>
                    <div class="swiper-slide slide-bg" style="background-image: url('<?= base_url($url);?>')">
                        <div class="slide-content">
                            <? $description = str_replace(array('<p>','<span style="color: rgb(0, 0, 0); font-family: "Open Sans";, Arial, sans-serif; font-size: 14px; line-height: 20px; text-align: justify;">','</span>','</p>'),'',$swipe['description']);?>
                            <div class="slide-text" data-caption-animate="fadeInUp">
                                <?php echo $description; ?>
                            </div>
                            <a href="#" class="btn btn-default btn-sub open-popup" data-caption-delay="450" data-caption-animate="fadeIn"><span>ЗАПИС НА ПРИЙОМ</span></a>
                        </div>
                    </div>
                    <? endif; ?>
                <?php endforeach; ?>
            </div>
        </div>

    </header>
    <!-- MAIN CONTENT -->
    <section class="content">
        <?= $page_content;?>
    </section>

    <!-- FOOTER -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-6 col-xs-6">
                    <div class="copyright">
                    <span class="copyright">
                       <a href="#">© Komilfo</a>
                   </span>
                    </div>
                </div>

                <div class="col-md-6 col-xs-6">
                    <div class="author"><a href="<?=base_url('/');?>">Лендінг Пейдж Львів - Sufix agency</a></div>
                </div>
            </div>
        </div>
    </footer>
        <div class="fm black" style="display: none;"></div>
        <form method="POST">
            <div class="popup" style="display: none;">
                <a href="#" class="btn-close close"><i class="fa fa-times" aria-hidden="true"></i></a>
                <div class="fm title_popup" id="popup_title">Для запису телефонуйте</div>
                <div class="fm text_place"></div>
                <div class="fm title_popup" id="form_title"></div>
                <div class="fm input-place_popup">
                    <div class="fm input-item"><input type="text" id="name" name="name" placeholder="ВАШЕ ІМ’Я"></div>
                    <div class="fm input-item phone_inp"><input type="text" id="phone" name="phone" placeholder="ВАШ ТЕЛЕФОН"></div>
        <!--                <div class="fm input-item mail_inp"><input type="text" name="email" placeholder="ВАШ EMAIL"></div>-->
                    <div class="fm input-item mail_inp"><textarea name="message" id="message" placeholder="ВАШЕ ПОВІДОМЛЕННЯ"></textarea></div>
                </div>
                <div class="fm btn btn-default button button_big"><a href="#" id="btn_form"><span>ВІДПРАВИТИ</span></a></div>
            </div>
        </form>
    <script type="text/javascript" src="<?=base_url('js/libs.min.js');?>"></script>
    <script type="text/javascript" src="<?=base_url('js/app.js');?>"></script>
    <script type="text/javascript" src="<?=base_url('js/popup.js');?>"></script>
</body>
</html>

<?php if(!$this->init_model->is_admin()):?>
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,700&subset=latin,cyrillic' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Roboto:400,300&subset=latin,cyrillic' rel='stylesheet' type='text/css'>
    <script type="text/javascript" src="<?=base_url('js/html5.js');?>"></script>
    <script type="text/javascript" src="<?=base_url('js/jquery.maskedinput.min.js');?>"></script>
    <script type="text/javascript" src="<?=base_url('js/scripts.js?v=1');?>"></script>
    <?php if (isset($page_javascript)) echo $page_javascript; ?>
    <!---------------------- -->
    <!--[if IE]>
      <script  src="js/html5.js"></script>
    <![endif]-->
    <!--[if IE 8]>
      <html class="ie8">
    <![endif]-->
    <?php endif;?>
