<?php
	defined('ROOT_PATH') OR exit('No direct script access allowed');
	$is_main = $this->init_model->is_main();
	$menu_id = $this->init_model->get_menu_id();
	$languages = $this->config->item('database_languages');
	$site_name = $this->config->item('site_name_'.LANG);
?>
<!DOCTYPE html>
<html lang="uk">
    <head>
        <meta charset="utf-8">
        <base href="<?=base_url();?>">
        <title><?php if (isset($page_title)) echo $page_title; ?></title>
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400|Roboto+Condensed|Roboto:400,700&amp;subset=cyrillic" rel="stylesheet">
        <link rel="stylesheet" href="<?=base_url('css/style.css')?>">
        <script type="text/javascript" src="<?=base_url('js/jquery/jquery.min.js');?>"></script>
        <?php if($this->init_model->is_admin()):?>
            <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,700&subset=latin,cyrillic' rel='stylesheet' type='text/css'>
            <link href='https://fonts.googleapis.com/css?family=Roboto:400,300&subset=latin,cyrillic' rel='stylesheet' type='text/css'>
            <link href="<?=base_url('css/base.css');?>" rel="stylesheet">
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
            <!---------------------- -->
            <!--[if IE]>
              <script  src="js/html5.js"></script>
            <![endif]-->
            <!--[if IE 8]>
              <html class="ie8">
            <![endif]-->
            </noscript>
<!--                <script type="text/javascript">-->
<!--                    $('#ask_question').on("click", function(e){-->
<!--                        e.preventDefault()-->
<!--                        goog_report_conversion();-->
<!--                    });-->
<!--                   // <![CDATA[ -->
<!--                   goog_snippet_vars = function() {-->
<!--                     var w = window;-->
<!--                     w.google_conversion_id = 932925977;-->
<!--                     w.google_conversion_label = "RUS9CIvqx2IQmaTtvAM";-->
<!--                     w.google_conversion_value = 100.00;-->
<!--                     w.google_conversion_currency = "UAH";-->
<!--                     w.google_remarketing_only = false;-->
<!--                   }-->
<!--                   // DO NOT CHANGE THE CODE BELOW.-->
<!--                   goog_report_conversion = function(url) {-->
<!--                     goog_snippet_vars();-->
<!--                     window.google_conversion_format = "3";-->
<!--                     window.google_is_call = true;-->
<!--                     var opt = new Object();-->
<!--                     opt.onload_callback = function() {-->
<!--                     if (typeof(url) != 'undefined') {-->
<!--                       window.location = url;-->
<!--                     }-->
<!--                   }-->
<!--                   var conv_handler = window['google_trackConversion'];-->
<!--                   if (typeof(conv_handler) == 'function') {-->
<!--                     conv_handler(opt);-->
<!--                   }-->
<!--                }-->
<!--                // ]]>-->
<!--                </script>-->
<!--                <script type="text/javascript" src="//www.googleadservices.com/pagead/conversion_async.js"></script>-->
<!--                <script>-->
<!--                    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){-->
<!--                    (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),-->
<!--                    m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)-->
<!--                    })(window,document,'script','//www.google-analytics.com/analytics.js','ga');-->
<!--                    ga('create', 'UA-71666923-1', 'auto');-->
<!--                    ga('send', 'pageview');-->
<!--                </script>-->
<!--                <script type="text/javascript">-->
<!--        /* <![CDATA[ */-->
<!--        var google_conversion_id = 932925977;-->
<!--        var google_custom_params = window.google_tag_params;-->
<!--        var google_remarketing_only = true;-->
<!--        /* ]]> */-->
<!--        </script>-->
<!--        <script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js"></script>-->

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
                                <li class="menu-list-item"><a href="#benefits">Переваги</a></li>
                                <li class="menu-list-item"><a href="#works">Наші роботи</a></li>
                                <li class="menu-list-item"><a href="#price">Послуги та ціни</a></li>
                                <li class="menu-list-item"><a href="#about">Про нас</a></li>
                                <li class="menu-list-item"><a href="#contacts">Контакти</a></li>
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
                <div class="swiper-slide slide-bg" style="background-image: url('images/main-slider/01.jpg')">
                    <div class="slide-content">

                        <div class="slide-text" data-caption-animate="fadeInUp">
                            Комільфо - тільки так
                            і не інакше!
                        </div>
                        <a href="#" class="btn btn-default" data-caption-delay="450" data-caption-animate="fadeIn"><span>ЗАПИС НА ПРИЙОМ</span></a>
                    </div>
                </div>

                <div class="swiper-slide slide-bg" style="background-image: url('images/main-slider/01.jpg')">
                    <div class="slide-content">
                        <div class="slide-text" data-caption-animate="fadeInUp">
                            Комільфо - тільки так
                            і не інакше!
                        </div>
                        <a href="#" class="btn btn-default" data-caption-delay="450" data-caption-animate="fadeIn"><span>ЗАПИС НА ПРИЙОМ</span></a>
                    </div>
                </div>

                <div class="swiper-slide slide-bg" style="background-image: url('images/main-slider/01.jpg')">
                    <div class="slide-content">
                        <div class="slide-text" data-caption-animate="fadeInUp">
                            Комільфо - тільки так
                            і не інакше!
                        </div>
                        <a href="#" class="btn btn-default" data-caption-delay="450" data-caption-animate="fadeIn"><span>ЗАПИС НА ПРИЙОМ</span></a>
                    </div>
                </div>
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
                    <div class="author">Розробка<a href="#"> SUFIX web studio</a></div>
                </div>
            </div>
        </div>
    </footer>
        <div class="fm black" style="display: none;"></div>
        <div class="popup" style="display: none;">
            <a href="" class="close"></a>
            <div class="fm title_popup" id="popup_title"></div>
            <div class="fm text_place"></div>
            <div class="fm title_popup" id="form_title"></div>
            <div class="fm input_place_popup">
                <div class="fm input_item"><input type="text" name="name" placeholder="ВАШЕ ІМ’Я"></div>
                <div class="fm input_item phone_inp"><input type="text" name="phone" placeholder="ВАШ ТЕЛЕФОН"></div>
                <div class="fm input_item mail_inp"><input type="text" name="email" placeholder="ВАШ EMAIL"></div>
                <div class="fm input_item mail_inp"><textarea name="message" placeholder="ВАШЕ ЗАПИТАННЯ"></textarea></div>
            </div>
            <div class="fm button button_big"><a href="#" id="send_form"><span>ВІДПРАВИТИ</span></a></div>
        </div>
    <script type="text/javascript" src="<?=base_url('js/libs.min.js');?>"></script>
    <script type="text/javascript" src="<?=base_url('js/app.js');?>"></script>
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

<script type="text/javascript">
  $(function () {
    $('#feedback_name_<?php echo $component_id; ?>,#feedback_email_<?php echo $component_id; ?>,#feedback_message_<?php echo $component_id; ?>').on('keyup blur paste', function () {
      var $field = $(this).parents('.evry_title:eq(0)');
      $field.removeClass('wrong');
    });
    $('#feedback_code_<?php echo $component_id; ?>').on('keyup blur paste', function () {
      $(this).closest('.evry_title').removeClass('wrong');
    });
    $('#feedback_send_<?php echo $component_id; ?>').on('click', function (event) {
      event.preventDefault();
      var request = {
          component_id: <?php echo $component_id; ?>,
          menu_id: <?php echo $menu_id; ?>,
          name:$.trim($('#feedback_name_<?php echo $component_id; ?>').val()),
          email:$.trim($('#feedback_email_<?php echo $component_id; ?>').val()),
          phone:$.trim($('#feedback_phone_<?php echo $component_id; ?>').val()),
          theme:$.trim($('#feedback_theme_<?php echo $component_id; ?>').val()),
          message:$.trim($('#feedback_message_<?php echo $component_id; ?>').val()),
          code:$.trim($('#feedback_code_<?php echo $component_id; ?>').val())
        },
        $error = $('#feedback_alert_<?php echo $component_id; ?>');
      if (request.name === '') {
        var $field = $('#feedback_name_<?php echo $component_id; ?>').closest('.evry_title').addClass('wrong');
        return false;
      }
      var email_test = /^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,6}$/i;
      if (!email_test.test(request.email)) {
        var $field = $('#feedback_email_<?php echo $component_id; ?>').closest('.evry_title').addClass('wrong');
        return false;
      }
      if (request.message === '') {
        var $field = $('#feedback_message_<?php echo $component_id; ?>').closest('.evry_title').addClass('wrong');
        return false;
      }
      if (request.code === '') {
        $('#feedback_code_<?php echo $component_id; ?>').closest('.evry_title').addClass('wrong');
        return false;
      }
      $.post(
        '<?php echo $this->uri->full_url('feedback/send'); ?>',
        request,
        function (response) {
          if (response.error === 0) {
            $('#feedback_<?php echo $component_id; ?>').fadeTo(500, 0).slideUp(function () {
              $(this).remove();
              $error.stop().fadeTo(50, 0).html('<? if(LANG=='ua')echo'Повідомлення відправлено';if(LANG=='ru')echo'Cообщение отправлено';if(LANG=='en')echo'Message sent';?>!').fadeTo(500, 1);
            });
          }
          if (response.error === 1) {
            $('#feedback_code_<?php echo $component_id; ?>').closest('.evry_title').addClass('wrong');
            $('#feedback_recaptcha_<?php echo $component_id; ?>').click();
          }
        },
        'json'
      );
    });
    $('#feedback_recaptcha_<?php echo $component_id; ?>').on('click', function (event) {
      event.preventDefault();
      $('#feedback_captcha_<?php echo $component_id; ?>').find('img').fadeTo(200, 0, function () {
        $(this).attr('src', '<?php echo $this->uri->full_url('feedback/captcha'); ?>#' + Math.random()).ready(function () {
          $('#feedback_captcha_<?php echo $component_id; ?>').find('img').fadeTo(1000, 1);
        });
      });
    });
  });
</script>
