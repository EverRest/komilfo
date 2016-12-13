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
        <script type="text/javascript" src="<?=base_url('js/jquery.js');?>"></script>
        <?php if($this->init_model->is_admin()):?>
            <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,700&subset=latin,cyrillic' rel='stylesheet' type='text/css'>
            <link href='https://fonts.googleapis.com/css?family=Roboto:400,300&subset=latin,cyrillic' rel='stylesheet' type='text/css'>
            <link href="<?=base_url('css/base.css');?>" rel="stylesheet">
            <script type="text/javascript" src="<?=base_url('js/html5.js');?>"></script>
            <script type="text/javascript" src="<?=base_url('js/jquery.parallax-1.1.3.js')?>"></script>
            <script type="text/javascript" src="<?=base_url('js/jquery.localscroll-1.2.7-min.js')?>"></script>
            <script type="text/javascript" src="<?=base_url('js/jquery.scrollTo-1.4.2-min.js')?>"></script>
            <script type="text/javascript" src="<?=base_url('js/jquery.maskedinput.min.js');?>"></script>
            <script type="text/javascript" src="<?=base_url('js/scripts.js?v=1');?>"></script>
            <script type="text/javascript" async src="<?=base_url('js/SmoothScroll.js');?>"></script>
            <script type="text/javascript" src="<?=base_url('js/components/slider_reviews.js');?>"></script>
            <script type="text/javascript">LANG = '<?=LANG;?>'; DEF_LANG = '<?=$this->config->item('def_lang');?>';</script>
            <script type="text/javascript" src="<?=base_url('js/colorbox/colorbox.js');?>"></script>
            <link href="<?=base_url('js/colorbox/colorbox.css');?>" rel="stylesheet">
            <?php if (isset($page_javascript)) echo $page_javascript; ?>
            <script type="text/javascript" src="<?=base_url('js/jquery.maskedinput.min.js');?>"></script>
            <script type="text/javascript">
            $(document).ready(function(){
                $('.menu').find('a[href*=#]').bind("click", function(e){
                    var anchor = $(this);
                    $('html, body').stop().animate({
                       scrollTop: $(anchor.attr('href')).offset().top
                    }, 1000);
                    e.preventDefault();
                });
                return false;
            });
            (function(e){e.fn.visible=function(t,n,r){var i=e(this).eq(0),s=i.get(0),o=e(window),u=o.scrollTop(),a=u+o.height(),f=o.scrollLeft(),l=f+o.width(),c=i.offset().top,h=c+i.height(),p=i.offset().left,d=p+i.width(),v=t===true?h:c,m=t===true?c:h,g=t===true?d:p,y=t===true?p:d,b=n===true?s.offsetWidth*s.offsetHeight:true,r=r?r:"both";if(r==="both")return!!b&&m<=a&&v>=u&&y<=l&&g>=f;else if(r==="vertical")return!!b&&m<=a&&v>=u;else if(r==="horizontal")return!!b&&y<=l&&g>=f}})(jQuery);
            $(document).ready(function(){
                $('#services').parallax("50%", 0.8);
                $('#elem_1').parallax("50%", 0.7);
                $('#elem_2').parallax("50%", 0.4);
                $('#elem_3').parallax("50%", 0.5);
                $('#how_we_work').parallax("50%", 0.3);
                $('#gallery').parallax("50%", 2);
            })
            </script>
            <!--[if IE]>
                <script src="js/html5.js"></script>
            <![endif]-->
            <!--[if IE 8]>
                <html class="ie8">
            <![endif]-->
            <!-- </noscript> -->
        <?php endif;?>
        <?php if (isset($page_description) AND $page_description != ''): ?><meta name="description" content="<?=$page_description;?>"><?php endif; ?>
        <?php if (isset($page_keywords) AND $page_keywords != ''): ?><meta name="keywords" content="<?=$page_keywords;?>"><?php endif; ?>
        <?php if (isset($page_css)) echo $page_css; ?>
        <link href="<?=base_url('favicon.ico');?>" rel="icon" type="image/x-icon">
        <link href="<?=base_url('favicon.ico');?>" rel="shortcut icon">   
        <script type="text/javascript">
              jQuery(document).ready(function(){
                jQuery("a[rel='photo']").colorbox();
              });
            </script>
            <!---------------------- -->
            <!--[if IE]>
              <script  src="js/html5.js"></script>
            <![endif]-->
            <!--[if IE 8]>
              <html class="ie8">
            <![endif]-->
            </noscript>
                <script type="text/javascript">
                    $('#ask_question').on("click", function(e){
                        e.preventDefault()
                        goog_report_conversion();
                    });
                   // <![CDATA[ 
                   goog_snippet_vars = function() {
                     var w = window;
                     w.google_conversion_id = 932925977;
                     w.google_conversion_label = "RUS9CIvqx2IQmaTtvAM";
                     w.google_conversion_value = 100.00;
                     w.google_conversion_currency = "UAH";
                     w.google_remarketing_only = false;
                   }
                   // DO NOT CHANGE THE CODE BELOW.
                   goog_report_conversion = function(url) {
                     goog_snippet_vars();
                     window.google_conversion_format = "3";
                     window.google_is_call = true;
                     var opt = new Object();
                     opt.onload_callback = function() {
                     if (typeof(url) != 'undefined') {
                       window.location = url;
                     }
                   }
                   var conv_handler = window['google_trackConversion'];
                   if (typeof(conv_handler) == 'function') {
                     conv_handler(opt);
                   }
                }
                // ]]> 
                </script>
                <script type="text/javascript" src="//www.googleadservices.com/pagead/conversion_async.js"></script>
                <script>
                    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                    (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
                    m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
                    })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
                    ga('create', 'UA-71666923-1', 'auto');
                    ga('send', 'pageview');
                </script>
                <script type="text/javascript">
        /* <![CDATA[ */
        var google_conversion_id = 932925977;
        var google_custom_params = window.google_tag_params;
        var google_remarketing_only = true;
        /* ]]> */
        </script>
        <script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js"></script>

        <noscript>
            <div style="display:inline;">
            <img height="1" width="1" style="border-style:none;" alt="" src="//googleads.g.doubleclick.net/pagead/viewthroughconversion/932925977/?value=0&guid=ON&script=0"/>
          </div>
        </noscript>
    </head>
    <body>
        <header class="fm header" id="head">
            <section class="fm top_header">
                <div class="centre">
                    <div class="fm logo_header">
                        <a href=""><img src="images/logo.png" alt="logo"></a>
                        <span><?= $header_data['slogan_'.LANG]; ?></span>
                    </div>
                    <div class="fmr button">
                        <a href="" id="ask_question" class="open_form" data-subject="Форма задати питання" data-title="Введіть контактні дані і ми зв'яжемось з вами" data-type="question"><span>ЗАДАТИ ПИТАННЯ</span></a>
                        <div class="btn_descrp">Працюємо навіть у вихідні</div>
                    </div>
                    <div class="fmr phone">
                        <div>
                            <span class="kvs"><?=$header_data['kyivstar_'.LANG]?></span>
                            <span class="life"><?=$header_data['life_'.LANG]?></span>
                            <span class="mts"><?=$header_data['mts_'.LANG]?></span>
                        </div>
                    </div>
                </div>
            </section>
            <section class="fm top_menu">
                <div class="centre">
                    <nav class="fm menu">
                        <ul>
                            <?if(isset($menu['guarantee']) && $menu_hidden['guarantee'] != 1){?><li><a href="#head" class="active">ГОЛОВНА</a></li><?}?>
                            <?if(isset($menu['news']) && $menu_hidden['news'] != 1){?><li><a href="#services">ПОСЛУГИ</a></li><?}?>
                            <?if(isset($menu['benefits']) && $menu_hidden['benefits'] != 1){?><li><a href="#how_we_work">ЯК МИ ПРАЦЮЄМО</a></li><?}?>
                            <?if(isset($menu['gallery']) && $menu_hidden['gallery'] != 1){?><li><a href="#gallery">ГАЛЕРЕЯ</a></li><?}?>
                            <?if(isset($menu['loyalty_system']) && $menu_hidden['loyalty_system'] != 1){?><li><a href="#loyalty_system">СИСТЕМА ЛОЯЛЬНОСТІ</a></li><?}?>
                            <?if(isset($menu['slider']) && $menu_hidden['slider'] != 1){?><li><a href="#reviews">ВІДГУКИ</a></li><?}?>
                            <?if(isset($menu['article']) && $menu_hidden['article'] != 1){?><li><a href="#about_ua">ПРО НАС</a></li><?}?>
                            <?if(isset($menu['questions']) && $menu_hidden['questions'] != 1){?><li><a href="#questions">КОНТАКТИ</a></li><?}?>
                        </ul>
                    </nav>
                </div>
            </section>
        </header>
        <section class="fm main_col">
            <?= $page_content;?>
        </section>
        <footer class="fm footer">
            <div class="centre">
                <div class="fm top_footer">
                    <div class="fm logo_header logo_footer">
                        <a href="#"><img src="images/logo.png" alt="logo"></a>
                        <span><?= $header_data['slogan_'.LANG]; ?></span>
                    </div>
                    <div class="fmr social">
                        <a href="<?=$footer_data['vk']?>" target="_blank" class="vk"></a>
                        <a href="<?=$footer_data['fb']?>" target="_blank" class="fb"></a>
                        <a href="<?=$footer_data['gplus']?>" target="_blank" class="gpl"></a>
                    </div>
                </div>
                <div class="fm sufix_foot"><?=$site_name?> всі права захищені © <a href="http://websufix.com/stvorennya-saytiv" title="створення сайтів">створення сайтів</a> <a href="http://websufix.com/" title="веб студія">веб студія</a> "SUFIX"</div>
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
		</body>
</html>

<?php if(!$this->init_model->is_admin()):?>
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,700&subset=latin,cyrillic' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Roboto:400,300&subset=latin,cyrillic' rel='stylesheet' type='text/css'>
    <link href="<?=base_url('js/colorbox/colorbox.css');?>" rel="stylesheet">
    <link href="<?=base_url('css/base.css');?>" rel="stylesheet">
    <script type="text/javascript" src="<?=base_url('js/html5.js');?>"></script>
    <!--script type="text/javascript" src="<?=base_url('js/jquery.js');?>"></script-->
    <script type="text/javascript" src="<?=base_url('js/jquery.parallax-1.1.3.js')?>"></script>
    <script type="text/javascript" src="<?=base_url('js/jquery.localscroll-1.2.7-min.js')?>"></script>
    <script type="text/javascript" src="<?=base_url('js/jquery.scrollTo-1.4.2-min.js')?>"></script>
    <script type="text/javascript" src="<?=base_url('js/jquery.maskedinput.min.js');?>"></script>
    <script type="text/javascript" src="<?=base_url('js/scripts.js?v=1');?>"></script>
    <script type="text/javascript" async src="<?=base_url('js/SmoothScroll.js');?>"></script>
    <script type="text/javascript" src="<?=base_url('js/components/slider_reviews.js');?>"></script>
    <script type="text/javascript">LANG = '<?=LANG;?>'; DEF_LANG = '<?=$this->config->item('def_lang');?>';</script>
    <script type="text/javascript" src="<?=base_url('js/colorbox/colorbox.js');?>"></script>
    <?php if (isset($page_javascript)) echo $page_javascript; ?> 
    <script  type="text/javascript">
            $(document).ready(function(){
               $('.menu').find('a[href*=#]').bind("click", function(e){
                  var anchor = $(this);
                  $('html, body').stop().animate({
                     scrollTop: $(anchor.attr('href')).offset().top
                  }, 1000);
                  e.preventDefault();
               });
               return false;
            });
      (function(e){e.fn.visible=function(t,n,r){var i=e(this).eq(0),s=i.get(0),o=e(window),u=o.scrollTop(),a=u+o.height(),f=o.scrollLeft(),l=f+o.width(),c=i.offset().top,h=c+i.height(),p=i.offset().left,d=p+i.width(),v=t===true?h:c,m=t===true?c:h,g=t===true?d:p,y=t===true?p:d,b=n===true?s.offsetWidth*s.offsetHeight:true,r=r?r:"both";if(r==="both")return!!b&&m<=a&&v>=u&&y<=l&&g>=f;else if(r==="vertical")return!!b&&m<=a&&v>=u;else if(r==="horizontal")return!!b&&y<=l&&g>=f}})(jQuery);
      $(document).ready(function(){
        $('#services').parallax("50%", 0.8);
        $('#elem_1').parallax("50%", 0.7);
        $('#elem_2').parallax("50%", 0.4);
        $('#elem_3').parallax("50%", 0.5);
        $('#how_we_work').parallax("50%", 0.3);
        $('#gallery').parallax("50%", 2);
      })
    </script>
    
    <script type="text/javascript">
      jQuery(document).ready(function(){
        jQuery("a[rel='photo']").colorbox();
      });
    </script>
    <!---------------------- -->
    <!--[if IE]>
      <script  src="js/html5.js"></script>
    <![endif]-->
    <!--[if IE 8]>
      <html class="ie8">
    <![endif]-->
    <?php endif;?>

<script type="text/javascript">
  jQuery(document).ready(function(){
    jQuery("a[rel='art_product']").colorbox();
  });
</script>

<script type="text/javascript">
    $(function(){
        $('#reviews').object_reviews({});
    });
</script>
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
