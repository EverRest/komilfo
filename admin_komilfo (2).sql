-- phpMyAdmin SQL Dump
-- version 4.4.15.7
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1:3306
-- Время создания: Янв 27 2017 г., 12:01
-- Версия сервера: 5.5.50
-- Версия PHP: 5.6.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `admin_komilfo`
--

-- --------------------------------------------------------

--
-- Структура таблицы `ko_administrators`
--

CREATE TABLE IF NOT EXISTS `ko_administrators` (
  `admin_id` int(6) NOT NULL,
  `name` varchar(50) NOT NULL,
  `login` varchar(50) NOT NULL,
  `password` char(32) NOT NULL,
  `salt` char(32) NOT NULL,
  `create_date` int(10) NOT NULL,
  `login_date` int(10) NOT NULL DEFAULT '0',
  `site_menu` text NOT NULL,
  `admin_menu` text NOT NULL,
  `root` tinyint(1) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `edited` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `ko_administrators`
--

INSERT INTO `ko_administrators` (`admin_id`, `name`, `login`, `password`, `salt`, `create_date`, `login_date`, `site_menu`, `admin_menu`, `root`, `status`, `edited`) VALUES
(1, 'Адміністратор', 'admin', '3fcc0f1c93aec089e3857f834b9d66e8', '2a81ce665b7a39f7e0c493e5af16ef40', 1410864123, 1485358717, '1,316', 'components,article_index,google_map_index,feedback_index,news_index,comments_index,menu,menu_1,seo,seo_tags,seo_seo_link,seo_xml,seo_site_name,config,config_common,config_languages,config_administrators,config_watermark', 1, 0, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `ko_components`
--

CREATE TABLE IF NOT EXISTS `ko_components` (
  `component_id` int(6) unsigned NOT NULL,
  `menu_id` int(6) unsigned NOT NULL,
  `position` int(3) unsigned NOT NULL DEFAULT '0',
  `hidden` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `module` varchar(30) NOT NULL,
  `method` varchar(30) NOT NULL,
  `config` text NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=101 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `ko_components`
--

INSERT INTO `ko_components` (`component_id`, `menu_id`, `position`, `hidden`, `module`, `method`, `config`) VALUES
(46, 1, 5, 0, 'benefits', 'index', ''),
(47, 1, 6, 0, 'google_map', 'index', ''),
(68, 1, 0, 0, 'swiper', 'index', ''),
(82, 1, 4, 0, 'slider', 'index', ''),
(83, 1, 2, 0, 'article', 'index', ''),
(84, 1, 3, 0, 'guarantee', 'index', ''),
(86, 9, 2, 0, 'benefits', 'index', ''),
(87, 9, 1, 0, 'guarantee', 'index', ''),
(88, 9, 0, 0, 'slider', 'index', ''),
(100, 1, 1, 0, 'services', 'index', '');

-- --------------------------------------------------------

--
-- Структура таблицы `ko_component_article`
--

CREATE TABLE IF NOT EXISTS `ko_component_article` (
  `component_id` int(6) unsigned NOT NULL,
  `menu_id` int(6) NOT NULL,
  `title_ua` varchar(255) NOT NULL,
  `title_ru` varchar(255) NOT NULL,
  `title_en` varchar(255) NOT NULL,
  `title_pl` varchar(255) NOT NULL,
  `text_ua` text NOT NULL,
  `text_ru` text NOT NULL,
  `text_en` text NOT NULL,
  `text_pl` text NOT NULL,
  `wide` tinyint(1) NOT NULL DEFAULT '0',
  `background_fone` int(1) NOT NULL,
  `btn_active` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `ko_component_article`
--

INSERT INTO `ko_component_article` (`component_id`, `menu_id`, `title_ua`, `title_ru`, `title_en`, `title_pl`, `text_ua`, `text_ru`, `text_en`, `text_pl`, `wide`, `background_fone`, `btn_active`) VALUES
(83, 1, 'Pellentesque ut justo ut metus rhoncus ultrices at ut ligula.', '', '', '', '<p><span font-size:=\\"\\" line-height:=\\"\\" open=\\"\\" style=\\"color: rgb(0, 0, 0); font-family: \\" text-align:=\\"\\">Nulla imperdiet facilisis lectus, at accumsan dolor gravida id. Curabitur eget interdum urna. Ut tempor efficitur quam, ac posuere metus ornare sed. Etiam sit amet risus volutpat, facilisis turpis a, iaculis nunc. Vivamus condimentum at purus a tincidunt. Morbi et pellentesque justo. Pellentesque varius magna velit, sed fringilla diam luctus non. Suspendisse a orci nisl. Integer quis lorem vitae est mollis fermentum. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Sed sagittis ligula eros, id molestie leo cursus ac. Maecenas vehicula facilisis dolor, et iaculis lectus.</span></p>', '', '', '', 0, 0, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `ko_component_benefits`
--

CREATE TABLE IF NOT EXISTS `ko_component_benefits` (
  `component_id` int(6) NOT NULL,
  `menu_id` int(6) NOT NULL,
  `wide` varchar(255) NOT NULL,
  `title_ua` varchar(255) NOT NULL,
  `quote_ua` text NOT NULL,
  `text_ua` text NOT NULL,
  `author_ua` varchar(255) NOT NULL,
  `btn_active` int(11) NOT NULL,
  `background_fone` varchar(255) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `ko_component_benefits`
--

INSERT INTO `ko_component_benefits` (`component_id`, `menu_id`, `wide`, `title_ua`, `quote_ua`, `text_ua`, `author_ua`, `btn_active`, `background_fone`) VALUES
(46, 1, '0', 'Про Нас', '<p><span style=\\"color: rgb(103, 103, 103); font-family: &quot;Open Sans&quot;, sans-serif; font-size: 30px;\\">Ніколи не стежте за чоловіком!</span><br style=\\"box-sizing: border-box; color: rgb(103, 103, 103); font-family: &quot;Open Sans&quot;, sans-serif; font-size: 30px;\\" /><span style=\\"color: rgb(103, 103, 103); font-family: &quot;Open Sans&quot;, sans-serif; font-size: 30px;\\">Ми навчимо Вас&nbsp;</span><br style=\\"box-sizing: border-box; color: rgb(103, 103, 103); font-family: &quot;Open Sans&quot;, sans-serif; font-size: 30px;\\" /><span style=\\"color: rgb(103, 103, 103); font-family: &quot;Open Sans&quot;, sans-serif; font-size: 30px;\\">доглядати за собою - і тоді&nbsp;</span><br style=\\"box-sizing: border-box; color: rgb(103, 103, 103); font-family: &quot;Open Sans&quot;, sans-serif; font-size: 30px;\\" /><span style=\\"color: rgb(103, 103, 103); font-family: &quot;Open Sans&quot;, sans-serif; font-size: 30px;\\">чоловіки будуть стежити за Вами!</span></p>', '<p style=\\"box-sizing: border-box; margin: 0px 0px 30px; padding: 0px; border: 0px; font-variant-numeric: inherit; font-stretch: inherit; font-size: 18px; line-height: 30px; font-family: &quot;Open Sans&quot;, sans-serif; vertical-align: baseline; color: rgb(0, 0, 0);\\">Салон краси &quot;Комільфо&quot;у самому центрі м.Львова на вулиці Гнатюка,16 - це один з перших салонів нашого міста, салон Expert Gold легендарної французької марки L&#39;Oreal Professionnel, який був відкритий 29 березня 1996 року.</p><p style=\\"box-sizing: border-box; margin: 0px 0px 30px; padding: 0px; border: 0px; font-variant-numeric: inherit; font-stretch: inherit; font-size: 18px; line-height: 30px; font-family: &quot;Open Sans&quot;, sans-serif; vertical-align: baseline; color: rgb(0, 0, 0);\\">Салон краси &laquo;Комільфо&raquo; у Львові дотримується принципу надання послуг найвищої якості у всіх її проявах. Висококваліфіковані майстри, найкраща кометика, вишуканий сервіс &mdash; все для Вас у найкращих формах краси.</p><p style=\\"box-sizing: border-box; margin: 0px 0px 30px; padding: 0px; border: 0px; font-variant-numeric: inherit; font-stretch: inherit; font-size: 18px; line-height: 30px; font-family: &quot;Open Sans&quot;, sans-serif; vertical-align: baseline; color: rgb(0, 0, 0);\\">В салоні краси &laquo;Комільфо&raquo; надаються пакети послуг для нареченої, по омолодженню і корекції фігури. Тут також можна придбати професійну косметику і подарунковий сертифікат на послуги салону &laquo;Комільфо&raquo;. Для постійних клінтів діють знижки від 5 до 15%.</p>', 'Салон &quot;Комільфо&quot;', 0, '');

-- --------------------------------------------------------

--
-- Структура таблицы `ko_component_contacts`
--

CREATE TABLE IF NOT EXISTS `ko_component_contacts` (
  `component_id` int(6) unsigned NOT NULL,
  `menu_id` int(6) NOT NULL,
  `title_ua` varchar(255) NOT NULL,
  `title_ru` varchar(255) NOT NULL,
  `title_en` varchar(255) NOT NULL,
  `title_pl` varchar(255) NOT NULL,
  `text_ua` text NOT NULL,
  `text_ru` text NOT NULL,
  `text_en` text NOT NULL,
  `text_pl` text NOT NULL,
  `wide` tinyint(1) NOT NULL DEFAULT '0',
  `background_fone` int(1) NOT NULL,
  `btn_active` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `ko_component_footer`
--

CREATE TABLE IF NOT EXISTS `ko_component_footer` (
  `id` int(11) NOT NULL,
  `vk` varchar(255) NOT NULL,
  `fb` varchar(255) NOT NULL,
  `gplus` varchar(255) NOT NULL,
  `ing` varchar(255) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `ko_component_footer`
--

INSERT INTO `ko_component_footer` (`id`, `vk`, `fb`, `gplus`, `ing`) VALUES
(1, 'http://www.unian.ua/', 'https://twitter.com/', 'https://plus.google.com/u/0/110578169760680701903', 'http://www.epravda.com.ua/news/2017/01/2/616599/');

-- --------------------------------------------------------

--
-- Структура таблицы `ko_component_frequent`
--

CREATE TABLE IF NOT EXISTS `ko_component_frequent` (
  `id` int(11) NOT NULL,
  `answer` text NOT NULL,
  `questions` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `ko_component_frequent`
--

INSERT INTO `ko_component_frequent` (`id`, `answer`, `questions`) VALUES
(1, 'Скільки людей чи вантажу може перевезти ліфт за 1 підйом(спуск)? ', '5 людей до 520 кг.'),
(2, ' Чи може ліфт обірватися і впасти?', 'sdf sdf sdf sdf '),
(3, 'Який вид пасажирського ліфта замовити краще: звичайний чи панорамний?', 'sdf sdf sdf sdf '),
(4, 'Скільки коштує замінити в житловому будинку старий ліфт на новий сучасний?', 'sdf sdf sdf sdf '),
(5, 'Чому при зупинці ліфта на поверх, кабіна робить невеличкий стрибок? ', 'sdf sdf sdf sdf '),
(6, 'Чи може ліфт їхати з відкритими дверима кабіни ліфта?', 'sdf sdf sdf sdf '),
(7, 'Якшо я «застряг» у ліфті, що я маю робити ?', 'sdf sdf sdf sdf '),
(9, 'Яку ліфтову шахту збудувати щоб туди можна було змонтувати ліфт?', 'sdf sdf sdf sdf '),
(10, 'Чи можу я у Вас купити ліфт і змонтувати його самостійно ?', 'sdf sdf sdf sdf '),
(11, 'Як я можу зекономити при замовленні ліфта? ', 'sdf sdf sdf sdf '),
(12, 'Для чого встановлювати диспетчеризацію в кабіні ліфта, якщо зараз кожен має при собі мобільний телефон?', 'sdf sdf sdf sdf ');

-- --------------------------------------------------------

--
-- Структура таблицы `ko_component_google_map`
--

CREATE TABLE IF NOT EXISTS `ko_component_google_map` (
  `component_id` int(6) NOT NULL,
  `menu_id` int(6) NOT NULL,
  `information_ua` text NOT NULL,
  `schedule_ua` text NOT NULL,
  `sale_ua` text NOT NULL,
  `marker_lat` varchar(30) NOT NULL,
  `marker_lng` varchar(30) NOT NULL,
  `center_lat` varchar(30) NOT NULL,
  `center_lng` varchar(30) NOT NULL,
  `wide` varchar(10) NOT NULL,
  `zoom` tinyint(2) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `ko_component_google_map`
--

INSERT INTO `ko_component_google_map` (`component_id`, `menu_id`, `information_ua`, `schedule_ua`, `sale_ua`, `marker_lat`, `marker_lng`, `center_lat`, `center_lng`, `wide`, `zoom`) VALUES
(47, 1, 'Пн.-Сб.: 10:00-19:00Нд.: 10:00-16:00', 'м. Львів, вул. Гнатюка, 15(097)640-30-50(097)640-30-50', 'BEAUTY LUNCH в салоні краси Комільфо:-20% у ПОНЕДІЛОК та ВІВТОРОК з 10:00 до 14:00', '13.7886255', '100.51747150000006', '13.7886255', '100.51747150000006', '0', 17);

-- --------------------------------------------------------

--
-- Структура таблицы `ko_component_header`
--

CREATE TABLE IF NOT EXISTS `ko_component_header` (
  `id` int(11) NOT NULL,
  `slogan_ua` text,
  `slogan_ru` text,
  `address_ua` text,
  `address_ru` text,
  `kyivstar_ua` varchar(20) DEFAULT NULL,
  `life_ua` varchar(20) DEFAULT NULL,
  `mts_ua` varchar(20) DEFAULT NULL,
  `phone4_ua` varchar(255) DEFAULT NULL,
  `kyivstar_ru` varchar(20) DEFAULT NULL,
  `life_ru` varchar(20) DEFAULT NULL,
  `mts_ru` varchar(20) DEFAULT NULL,
  `phone4_ru` varchar(255) DEFAULT NULL,
  `title_action_ua` text,
  `title_action_ru` text,
  `main_action_ua` text,
  `main_action_ru` text,
  `time_ua` int(20) DEFAULT NULL,
  `time_ru` int(20) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `ko_component_header`
--

INSERT INTO `ko_component_header` (`id`, `slogan_ua`, `slogan_ru`, `address_ua`, `address_ru`, `kyivstar_ua`, `life_ua`, `mts_ua`, `phone4_ua`, `kyivstar_ru`, `life_ru`, `mts_ru`, `phone4_ru`, `title_action_ua`, `title_action_ru`, `main_action_ua`, `main_action_ru`, `time_ua`, `time_ru`) VALUES
(1, 'Професійне прибирання', '2', 'м. Львів<br>\r\nвул. Смаль-Стоцького 1<br>\r\nОфіс: 312<br>', 'ролро', '+38 (068) 555 80 80', '+38 (073) 805 80 80', '+38 (050) 449 80 80', '032-239-37-69', '', '345345', '34534', '345345', 'АКЦІЯ - 50%', 'dfgdfgdfg', 'На вартість монтажних робіт<br>\r\nпісля підписання договору', 'ролрол', 1425061800, 1424192640);

-- --------------------------------------------------------

--
-- Структура таблицы `ko_component_services`
--

CREATE TABLE IF NOT EXISTS `ko_component_services` (
  `id` int(11) NOT NULL,
  `component_id` int(6) NOT NULL,
  `menu_id` int(6) NOT NULL,
  `header` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `price` int(11) NOT NULL,
  `title_ua` varchar(255) NOT NULL,
  `text_ua` text NOT NULL,
  `wide` int(16) NOT NULL,
  `background_fone` varchar(255) NOT NULL,
  `btn_active` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `ko_component_swiper`
--

CREATE TABLE IF NOT EXISTS `ko_component_swiper` (
  `slide_id` int(6) unsigned NOT NULL,
  `menu_id` int(6) unsigned NOT NULL DEFAULT '0',
  `position` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `hidden` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `file_name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=68 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `ko_config`
--

CREATE TABLE IF NOT EXISTS `ko_config` (
  `key` varchar(20) NOT NULL,
  `val` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `ko_config`
--

INSERT INTO `ko_config` (`key`, `val`) VALUES
('def_lang', 'ua'),
('delete_alert', '1'),
('languages', 'a:2:{i:0;s:2:"ua";i:1;s:2:"ru";}'),
('print_icon', '0'),
('site_email', 'medynskyypavlo@gmail.com'),
('site_name_en', 'Public union ukrainian association of swimming clubs "Masters"'),
('site_name_pl', ''),
('site_name_ru', 'Лікування'),
('site_name_ua', 'Komilfo'),
('watermark', ''),
('watermark_opacity', '0.25'),
('watermark_padding', '20');

-- --------------------------------------------------------

--
-- Структура таблицы `ko_gallery`
--

CREATE TABLE IF NOT EXISTS `ko_gallery` (
  `slide_id` int(6) unsigned NOT NULL,
  `menu_id` int(6) unsigned NOT NULL DEFAULT '0',
  `position` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `hidden` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `title_ua` varchar(255) NOT NULL,
  `url_ua` varchar(255) NOT NULL,
  `file_name_ua` varchar(255) NOT NULL,
  `title_ru` varchar(255) NOT NULL,
  `url_ru` varchar(255) NOT NULL,
  `file_name_ru` varchar(255) NOT NULL,
  `title_en` varchar(255) NOT NULL,
  `url_en` varchar(255) NOT NULL,
  `hidden_ua` int(1) NOT NULL,
  `hidden_ru` int(1) NOT NULL,
  `hidden_en` int(1) NOT NULL,
  `file_name_en` varchar(255) NOT NULL,
  `description_ua` varchar(255) NOT NULL,
  `description_ru` varchar(255) NOT NULL,
  `description_en` varchar(255) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=61 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `ko_google_map`
--

CREATE TABLE IF NOT EXISTS `ko_google_map` (
  `component_id` int(6) NOT NULL,
  `menu_id` int(6) NOT NULL,
  `center_lat` varchar(30) NOT NULL,
  `center_lng` varchar(30) NOT NULL,
  `marker_lat` varchar(30) NOT NULL,
  `marker_lng` varchar(30) NOT NULL,
  `zoom` tinyint(2) NOT NULL,
  `title_en` varchar(255) NOT NULL,
  `description_en` text NOT NULL,
  `contacts_check` int(1) NOT NULL,
  `title_ua` varchar(255) NOT NULL,
  `description_ua` text NOT NULL,
  `url_ua` varchar(255) NOT NULL,
  `url_en` varchar(255) NOT NULL,
  `phone` varchar(30) NOT NULL,
  `photo` varchar(255) NOT NULL,
  `image_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- Дамп данных таблицы `ko_google_map`
--

INSERT INTO `ko_google_map` (`component_id`, `menu_id`, `center_lat`, `center_lng`, `marker_lat`, `marker_lng`, `zoom`, `title_en`, `description_en`, `contacts_check`, `title_ua`, `description_ua`, `url_ua`, `url_en`, `phone`, `photo`, `image_id`) VALUES
(49, 1, '', '', '', '', 0, '', '', 0, '', '', '', '', '', '', 0);

-- --------------------------------------------------------

--
-- Структура таблицы `ko_html_components`
--

CREATE TABLE IF NOT EXISTS `ko_html_components` (
  `id` int(11) NOT NULL,
  `main_html_ua` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `ko_loyalty`
--

CREATE TABLE IF NOT EXISTS `ko_loyalty` (
  `id` int(11) NOT NULL,
  `component_id` int(11) NOT NULL,
  `text` text NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `ko_loyalty`
--

INSERT INTO `ko_loyalty` (`id`, `component_id`, `text`) VALUES
(1, 0, '<p>Система бонусів та лояльності:</p>\n\n<p>Вже після першого прибирання Ви отримаєте картку, на якій будуть накопичуватись бонуси у вигляді знижок на кожне наступне замовлення послуг.</p>\n\n<p>Знижка вираховується від загальної суми Ваших замовлень :</p>\n\n<ul>\n	<li>Від 1000 грн. &ndash; 3% знижка</li>\n	<li>Від 10&nbsp;000 грн. &ndash; 5% знижка</li>\n	<li>Від 20&nbsp;000 грн. &ndash; 10% знижка</li>\n</ul>\n');

-- --------------------------------------------------------

--
-- Структура таблицы `ko_menu`
--

CREATE TABLE IF NOT EXISTS `ko_menu` (
  `id` int(6) unsigned NOT NULL,
  `parent_id` int(6) unsigned NOT NULL DEFAULT '0',
  `level` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `menu_index` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `position` int(4) unsigned NOT NULL DEFAULT '0',
  `hidden` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `main` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `target` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `url_path_id` varchar(100) NOT NULL,
  `name_ua` varchar(255) NOT NULL,
  `title_ua` varchar(50) NOT NULL,
  `url_ua` varchar(255) NOT NULL,
  `url_path_ua` varchar(255) NOT NULL,
  `url_hash_ua` char(32) NOT NULL,
  `static_url_ua` varchar(255) NOT NULL,
  `name_ru` varchar(255) NOT NULL,
  `title_ru` varchar(50) NOT NULL,
  `url_ru` varchar(255) NOT NULL,
  `url_path_ru` varchar(255) NOT NULL,
  `url_hash_ru` char(32) NOT NULL,
  `static_url_ru` varchar(255) NOT NULL,
  `name_en` varchar(255) NOT NULL,
  `title_en` varchar(50) NOT NULL,
  `url_en` varchar(255) NOT NULL,
  `url_path_en` varchar(255) NOT NULL,
  `url_hash_en` char(32) NOT NULL,
  `static_url_en` varchar(255) NOT NULL,
  `name_pl` varchar(255) NOT NULL,
  `title_pl` varchar(50) NOT NULL,
  `url_pl` varchar(255) NOT NULL,
  `url_path_pl` varchar(255) NOT NULL,
  `url_hash_pl` char(32) NOT NULL,
  `static_url_pl` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `icon` varchar(255) NOT NULL,
  `code` varchar(20) NOT NULL,
  `update` int(11) unsigned NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `ko_menu`
--

INSERT INTO `ko_menu` (`id`, `parent_id`, `level`, `menu_index`, `position`, `hidden`, `main`, `target`, `url_path_id`, `name_ua`, `title_ua`, `url_ua`, `url_path_ua`, `url_hash_ua`, `static_url_ua`, `name_ru`, `title_ru`, `url_ru`, `url_path_ru`, `url_hash_ru`, `static_url_ru`, `name_en`, `title_en`, `url_en`, `url_path_en`, `url_hash_en`, `static_url_en`, `name_pl`, `title_pl`, `url_pl`, `url_path_pl`, `url_hash_pl`, `static_url_pl`, `image`, `icon`, `code`, `update`) VALUES
(1, 0, 0, 1, 0, 0, 1, 0, '', 'Головна', '', 'golovna', 'golovna', '00eb099a6cb6877236b7c6b1184e6bc3', '', 'Главная', '', 'glavnaya', '', '', '', 'holovna', '', 'holovna', 'holovna', '78a7a9af2f1ac44fbc028d5d23d3149b', '', '', '', '', '', '', '', '', '', '', 0),
(8, 0, 0, 1, 0, 0, 0, 0, '', 'Головна', '', 'golovna', 'golovna', '00eb099a6cb6877236b7c6b1184e6bc3', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 1484749242),
(9, 0, 0, 1, 1, 0, 0, 0, '', 'Послуга', '', 'posluga', 'posluga', 'b0563fc3054aa259b684b4eb783c08ef', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 1484749244),
(10, 0, 0, 2, 0, 0, 0, 0, '', 'Перукарські послуги', '', 'perukarski-poslugy', 'perukarski-poslugy', '5a0949635ef9be185b081927e624095f', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 1485338180),
(11, 0, 0, 2, 5, 0, 0, 0, '', 'Послуги манікюру і педікюру', '', 'poslugy-manikyuru-i-pedikyuru', 'poslugy-manikyuru-i-pedikyuru', 'ec09d0751bc191d8fe56ef6fd50ce597', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 1485338213),
(12, 10, 1, 2, 1, 0, 0, 0, '.10.', 'Полубокс-200', '', 'poluboks-200', 'perukarski-poslugy/poluboks-200', '86c18a8ad0a516d614aefd39aa3ac3ff', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 1485338248),
(14, 10, 1, 2, 2, 0, 0, 0, '.10.', 'Налисо-100', '', 'nalyso-100', 'perukarski-poslugy/nalyso-100', '0e7b91f22f76f1e5dba6e7d2868f506a', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 1485338277),
(15, 10, 1, 2, 3, 0, 0, 0, '.10.', 'Під шапочку-250', '', 'pid-shapochku-250', 'perukarski-poslugy/pid-shapochku-250', 'ac1e5db4bfbfc60e630a4cf30ab9a3c7', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 1485338286),
(17, 10, 1, 2, 4, 0, 0, 0, '.10.', 'Каре-400', '', 'kare-400', 'perukarski-poslugy/kare-400', '66908f131bda02db5183fe8964685441', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 1485338325),
(18, 11, 1, 2, 6, 0, 0, 0, '.11.', 'Манікюр-150', '', 'manikyur-150', 'poslugy-manikyuru-i-pedikyuru/manikyur-150', '019b6935f1f264dcb4fd9359d4483e57', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 1485338337),
(19, 11, 1, 2, 7, 0, 0, 0, '.11.', 'Педікюр-250', '', 'pedikyur-250', 'poslugy-manikyuru-i-pedikyuru/pedikyur-250', '4eff14c222b8d52b5ae604c065adfa11', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 1485338338),
(20, 11, 1, 2, 8, 0, 0, 0, '.11.', 'Наведення красоти-800', '', 'navedennya-krasoty-800', 'poslugy-manikyuru-i-pedikyuru/navedennya-krasoty-800', 'c584a7db5ea373b804ec464061af27d1', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 1485338338),
(21, 0, 0, 2, 9, 0, 0, 0, '', 'Ще якась біліберда', '', 'sche-yakas-biliberda', 'sche-yakas-biliberda', '83f5e0ba961657c05e712cc73031f473', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 1485338511),
(22, 21, 1, 2, 0, 0, 0, 0, '.21.', 'Масаж-500', '', 'masaj-500', 'sche-yakas-biliberda/masaj-500', 'ea7c7145816ff37d5c96b4adeac74121', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 1485338528),
(23, 21, 1, 2, 1, 0, 0, 0, '.21.', 'Тайський масаж-800', '', 'tayskyy-masaj-800', 'sche-yakas-biliberda/tayskyy-masaj-800', 'c1d6b06025bc74a44fed6f6cbf4fbe1d', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 1485338529);

-- --------------------------------------------------------

--
-- Структура таблицы `ko_news`
--

CREATE TABLE IF NOT EXISTS `ko_news` (
  `news_id` int(6) unsigned NOT NULL,
  `menu_id` int(6) unsigned NOT NULL,
  `component_id` int(6) unsigned NOT NULL,
  `hidden` tinyint(1) NOT NULL DEFAULT '0',
  `position` int(4) NOT NULL DEFAULT '0',
  `date` bigint(20) unsigned NOT NULL,
  `title_ua` varchar(255) NOT NULL,
  `title_ru` varchar(255) NOT NULL,
  `title_en` varchar(255) NOT NULL,
  `title_pl` varchar(255) NOT NULL,
  `url_ua` varchar(255) NOT NULL,
  `url_ru` varchar(255) DEFAULT NULL,
  `url_en` varchar(255) NOT NULL,
  `url_pl` varchar(255) NOT NULL,
  `text_ua` text NOT NULL,
  `text_ru` text NOT NULL,
  `text_en` text NOT NULL,
  `text_pl` text NOT NULL,
  `anons_ua` text NOT NULL,
  `anons_ru` text NOT NULL,
  `anons_en` text NOT NULL,
  `anons_pl` text NOT NULL,
  `price_start_eur` int(11) NOT NULL,
  `price_end_eur` int(11) NOT NULL,
  `price_start_usd` int(11) NOT NULL,
  `price_end_usd` int(11) NOT NULL,
  `update` bigint(20) unsigned NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `ko_news`
--

INSERT INTO `ko_news` (`news_id`, `menu_id`, `component_id`, `hidden`, `position`, `date`, `title_ua`, `title_ru`, `title_en`, `title_pl`, `url_ua`, `url_ru`, `url_en`, `url_pl`, `text_ua`, `text_ru`, `text_en`, `text_pl`, `anons_ua`, `anons_ru`, `anons_en`, `anons_pl`, `price_start_eur`, `price_end_eur`, `price_start_usd`, `price_end_usd`, `update`) VALUES
(3, 1, 4, 0, 1, 1442579208, 'Назва товару:', '', '', '', 'nazva-tovaru', '', '', '', '<table border=\\"1\\" cellpadding=\\"1\\" cellspacing=\\"1\\" style=\\"width:980px;\\"><tbody><tr><td>ТОВАР</td><td>ОПИС</td><td>АРТИКЛЬ</td><td>ЦІНА</td></tr><tr><td><img alt=\\"\\" src=\\"/upload/images/photo_1.jpg\\" style=\\"width: 200px; height: 223px;\\" /></td><td>Cтрічкова пила Wood-Mizer</td><td>546-5456-4556</td><td class=\\"table_price\\">5 500</td></tr></tbody></table><p>&nbsp;</p>', '', '', '', 'Анонс товару:', '', '', '', 100, 150, 0, 0, 1442573384),
(4, 1, 4, 0, 0, 1443178452, 'Wood-Mizer', '', '', '', 'wood-mizer', '', '', '', '<table border=\\"1\\" cellpadding=\\"1\\" cellspacing=\\"1\\" style=\\"width:600px;\\"><thead><tr><th>Назва</th><th>Код</th><th>Профіль</th><th>Товщина, ширина, мм.</th><th>Ціна</th></tr></thead><tbody><tr><td><p style=\\"text-align: center;\\"><img alt=\\"\\" src=\\"/upload/images/Wood-Mizer/silvertip.jpg\\" style=\\"width: 176px; height: 41px;\\" /></p><p style=\\"text-align: center;\\">Пили ждя вторинного розпилу деревини</p></td><td>ST 35*1,00*1030SS<br />ST 35*1,00*1030HSS<br />ST 40*1,00*1030SS<br />ST 40*1,00*1030HSS<br />ST 50*1,00*1030SS<br />ST 50*1,00*1030HSS<br />ST 50*1,07*1030HSS<br />ST 35*1,07*1030HSS</td><td>10/30<br />10/30<br />10/30<br />10/30<br />10/30<br />10/30<br />10/30<br />10/30<br /></td><td class=\\"table_price\\">35*1,00<br />35*1,00<br />40*1,00<br />40*1,00<br />50*1,00<br />50*1,00<br />50*1,07<br />35*1,07<br /></td><td class=\\"table_price\\">2,05 Є<br />2,20 Є<br />2,40 Є<br />2,50 Є<br />2,75 Є<br />2,80 Є<br />3,38 Є<br />2,75 Є<br /></td></tr><tr><td><img alt=\\"\\" src=\\"/upload/images/photo_1.jpg\\" style=\\"width: 47px; height: 52px;\\" /></td><td>Cт1річкова пила Wood-Mizer</td><td>546-5456-4556</td><td class=\\"table_price\\">5 500</td><td class=\\"table_price\\">&nbsp;</td></tr><tr><td><img alt=\\"\\" src=\\"/upload/images/photo_1.jpg\\" style=\\"width: 47px; height: 52px;\\" /></td><td>Cт1річкова пила Wood-Mizer</td><td>546-5456-4556</td><td class=\\"table_price\\">5 500</td><td class=\\"table_price\\">&nbsp;</td></tr><tr><td><img alt=\\"\\" src=\\"/upload/images/photo_1.jpg\\" style=\\"width: 47px; height: 52px;\\" /></td><td>Cт1річкова пила Wood-Mizer</td><td>546-5456-4556</td><td class=\\"table_price\\">5 500</td><td class=\\"table_price\\">&nbsp;</td></tr><tr><td><img alt=\\"\\" src=\\"/upload/images/photo_1.jpg\\" style=\\"width: 47px; height: 52px;\\" /></td><td>Cт1річкова пила Wood-Mizer</td><td>546-5456-4556</td><td class=\\"table_price\\">5 500</td><td class=\\"table_price\\">&nbsp;</td></tr></tbody></table><p>&nbsp;</p>', '', '', '', 'Стрічкові пили', '', '', '', 250, 300, 0, 0, 1442582200),
(5, 1, 4, 0, 2, 1443611932, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 0, 0, 0, 0, 1443611864),
(6, 1, 4, 0, 3, 1443611978, 'кцк', 'кцк', '', '', 'ktsk', 'ktsk', '', '', '', '', '', '', 'цкц', 'цкц', '', '', 0, 0, 0, 0, 1443611945);

-- --------------------------------------------------------

--
-- Структура таблицы `ko_news_images`
--

CREATE TABLE IF NOT EXISTS `ko_news_images` (
  `image_id` int(6) unsigned NOT NULL,
  `menu_id` int(6) unsigned NOT NULL,
  `component_id` int(6) unsigned NOT NULL,
  `news_id` int(6) unsigned NOT NULL,
  `position` int(4) unsigned NOT NULL DEFAULT '0',
  `file_name` varchar(255) NOT NULL,
  `watermark_position` int(2) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=151 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `ko_news_images`
--

INSERT INTO `ko_news_images` (`image_id`, `menu_id`, `component_id`, `news_id`, `position`, `file_name`, `watermark_position`) VALUES
(147, 1, 4, 3, 1, 'photo_1.jpg', 0),
(148, 1, 4, 4, 1, 'photo_1.jpg', 0),
(149, 1, 4, 5, 1, 'bandsaw_blades.jpg', 0),
(150, 1, 4, 6, 1, '1306361038.jpg', 0);

-- --------------------------------------------------------

--
-- Структура таблицы `ko_reviews`
--

CREATE TABLE IF NOT EXISTS `ko_reviews` (
  `review_id` int(11) unsigned NOT NULL,
  `component_id` int(11) unsigned NOT NULL,
  `menu_id` int(11) unsigned NOT NULL,
  `hidden` tinyint(1) NOT NULL DEFAULT '0',
  `position` int(4) NOT NULL DEFAULT '0',
  `title_ua` varchar(255) NOT NULL,
  `title_ru` varchar(255) NOT NULL,
  `title_en` varchar(255) NOT NULL,
  `title_pl` varchar(255) NOT NULL,
  `text_ua` text NOT NULL,
  `text_ru` text NOT NULL,
  `text_en` text NOT NULL,
  `text_pl` text NOT NULL,
  `image` varchar(255) NOT NULL,
  `logo` varchar(255) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `ko_seo_link`
--

CREATE TABLE IF NOT EXISTS `ko_seo_link` (
  `menu_id` int(6) unsigned NOT NULL,
  `hide_items` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `ko_seo_tags`
--

CREATE TABLE IF NOT EXISTS `ko_seo_tags` (
  `tags_id` int(6) unsigned NOT NULL,
  `item_id` int(6) unsigned NOT NULL,
  `component_id` int(6) unsigned NOT NULL DEFAULT '0',
  `menu_id` int(6) unsigned NOT NULL,
  `module` varchar(30) NOT NULL,
  `type_ua` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `title_ua` varchar(255) NOT NULL,
  `description_ua` text NOT NULL,
  `keywords_ua` text NOT NULL,
  `cache_title_ua` varchar(255) NOT NULL,
  `cache_description_ua` text NOT NULL,
  `cache_keywords_ua` text NOT NULL,
  `cache_ua` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `type_ru` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `title_ru` varchar(255) NOT NULL,
  `description_ru` text NOT NULL,
  `keywords_ru` text NOT NULL,
  `cache_title_ru` varchar(255) NOT NULL,
  `cache_description_ru` text NOT NULL,
  `cache_keywords_ru` text NOT NULL,
  `cache_ru` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `type_en` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `title_en` varchar(255) NOT NULL,
  `description_en` text NOT NULL,
  `keywords_en` text NOT NULL,
  `cache_title_en` varchar(255) NOT NULL,
  `cache_description_en` text NOT NULL,
  `cache_keywords_en` text NOT NULL,
  `cache_en` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `type_pl` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `title_pl` varchar(255) NOT NULL,
  `description_pl` text NOT NULL,
  `keywords_pl` text NOT NULL,
  `cache_title_pl` varchar(255) NOT NULL,
  `cache_description_pl` text NOT NULL,
  `cache_keywords_pl` text NOT NULL,
  `cache_pl` tinyint(1) unsigned NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=1378 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `ko_seo_tags`
--

INSERT INTO `ko_seo_tags` (`tags_id`, `item_id`, `component_id`, `menu_id`, `module`, `type_ua`, `title_ua`, `description_ua`, `keywords_ua`, `cache_title_ua`, `cache_description_ua`, `cache_keywords_ua`, `cache_ua`, `type_ru`, `title_ru`, `description_ru`, `keywords_ru`, `cache_title_ru`, `cache_description_ru`, `cache_keywords_ru`, `cache_ru`, `type_en`, `title_en`, `description_en`, `keywords_en`, `cache_title_en`, `cache_description_en`, `cache_keywords_en`, `cache_en`, `type_pl`, `title_pl`, `description_pl`, `keywords_pl`, `cache_title_pl`, `cache_description_pl`, `cache_keywords_pl`, `cache_pl`) VALUES
(1372, 0, 0, 1, '', 1, 'Комільфо Львів | Перукарські послуги | Послуги манікюру і педикюру', 'Салон краси &quot;Комільфо&quot; надає перукарські послуги, а також послуги манікюру і педикюру', 'Комільфо Львів, перукарські послуги Львів, послуги манікюру і педикюру Львів', 'Про нас', 'Про нас Клінінгова компанія &quot;Чиста Оселя&quot; надає послуги професійного прибирання у м. Львів: прибирання після ремонту, генеральне прибирання, щоденне прибирання: квартир, будинків, бізнес центрів, офісів та складів &ndash; миття вікон, хімчистка &amp; аквачистка доріжок, килимів, ковроліну та м&rsquo;яких меблів (диванів, матрасів,', 'прибирання, послуги, часу, області, львова, професійне', 0, 0, '', '', '', '', '  ', '', 1, 0, '', '', '', '', '', '', 0, 0, '', '', '', '', '', '', 0),
(1373, 0, 0, 0, '', 0, '', '', '', '', '', '', 0, 0, '', '', '', '', '', '', 0, 0, '', '', '', '', '', '', 0, 0, '', '', '', '', '', '', 0),
(1376, 0, 0, 9, '', 0, '', '', '', '', '', '', 0, 0, '', '', '', '', '', '', 0, 0, '', '', '', '', '', '', 0, 0, '', '', '', '', '', '', 0),
(1377, 0, 0, 14, '', 0, '', '', '', '', '', '', 0, 0, '', '', '', '', '', '', 0, 0, '', '', '', '', '', '', 0, 0, '', '', '', '', '', '', 0);

-- --------------------------------------------------------

--
-- Структура таблицы `ko_services`
--

CREATE TABLE IF NOT EXISTS `ko_services` (
  `id` int(6) unsigned NOT NULL,
  `parent_id` int(6) unsigned NOT NULL DEFAULT '0',
  `level` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `services_index` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `position` int(4) unsigned NOT NULL DEFAULT '0',
  `hidden` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `main` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `target` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `url_path_id` varchar(100) NOT NULL,
  `name_ua` varchar(255) NOT NULL,
  `title_ua` varchar(50) NOT NULL,
  `url_ua` varchar(255) NOT NULL,
  `url_path_ua` varchar(255) NOT NULL,
  `url_hash_ua` char(32) NOT NULL,
  `static_url_ua` varchar(255) NOT NULL,
  `name_ru` varchar(255) NOT NULL,
  `title_ru` varchar(50) NOT NULL,
  `url_ru` varchar(255) NOT NULL,
  `url_path_ru` varchar(255) NOT NULL,
  `url_hash_ru` char(32) NOT NULL,
  `static_url_ru` varchar(255) NOT NULL,
  `name_en` varchar(255) NOT NULL,
  `title_en` varchar(50) NOT NULL,
  `url_en` varchar(255) NOT NULL,
  `url_path_en` varchar(255) NOT NULL,
  `url_hash_en` char(32) NOT NULL,
  `static_url_en` varchar(255) NOT NULL,
  `name_pl` varchar(255) NOT NULL,
  `title_pl` varchar(50) NOT NULL,
  `url_pl` varchar(255) NOT NULL,
  `url_path_pl` varchar(255) NOT NULL,
  `url_hash_pl` char(32) NOT NULL,
  `static_url_pl` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `icon` varchar(255) NOT NULL,
  `code` varchar(20) NOT NULL,
  `update` int(11) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `ko_site_links`
--

CREATE TABLE IF NOT EXISTS `ko_site_links` (
  `link_id` int(6) unsigned NOT NULL,
  `item_id` int(6) unsigned NOT NULL,
  `component_id` int(6) unsigned NOT NULL,
  `menu_id` int(6) unsigned NOT NULL,
  `module` varchar(20) NOT NULL,
  `method` varchar(30) NOT NULL,
  `hash_ua` char(32) NOT NULL,
  `hash_en` char(32) NOT NULL,
  `hash_pl` char(32) NOT NULL,
  `hash_ru` char(32) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `ko_site_links`
--

INSERT INTO `ko_site_links` (`link_id`, `item_id`, `component_id`, `menu_id`, `module`, `method`, `hash_ua`, `hash_en`, `hash_pl`, `hash_ru`) VALUES
(6, 8, 0, 8, 'components', 'get_components', '00eb099a6cb6877236b7c6b1184e6bc3', '', '', ''),
(7, 9, 0, 9, 'components', 'get_components', 'b0563fc3054aa259b684b4eb783c08ef', '', '', ''),
(8, 5, 5, 0, 'components', 'get_components', '', '', '', ''),
(9, 6, 6, 0, 'components', 'get_components', '', '', '', ''),
(10, 7, 7, 0, 'components', 'get_components', '', '', '', ''),
(11, 8, 8, 0, 'components', 'get_components', '', '', '', ''),
(12, 9, 9, 0, 'components', 'get_components', '', '', '', ''),
(13, 10, 10, 0, 'components', 'get_components', '', '', '', ''),
(14, 11, 11, 0, 'components', 'get_components', '', '', '', ''),
(15, 12, 12, 0, 'components', 'get_components', '', '', '', ''),
(16, 13, 13, 0, 'components', 'get_components', '', '', '', ''),
(17, 14, 14, 0, 'components', 'get_components', '', '', '', ''),
(18, 15, 15, 0, 'components', 'get_components', '', '', '', ''),
(19, 16, 16, 0, 'components', 'get_components', '', '', '', ''),
(20, 17, 17, 0, 'components', 'get_components', '', '', '', ''),
(21, 18, 18, 0, 'components', 'get_components', '', '', '', ''),
(22, 19, 19, 0, 'components', 'get_components', '', '', '', ''),
(23, 20, 20, 0, 'components', 'get_components', '', '', '', ''),
(24, 21, 21, 0, 'components', 'get_components', '', '', '', ''),
(25, 22, 22, 0, 'components', 'get_components', '', '', '', ''),
(26, 10, 0, 10, 'components', 'get_components', '5a0949635ef9be185b081927e624095f', '', '', ''),
(27, 11, 0, 11, 'components', 'get_components', 'ec09d0751bc191d8fe56ef6fd50ce597', '', '', ''),
(28, 12, 0, 12, 'components', 'get_components', '86c18a8ad0a516d614aefd39aa3ac3ff', '', '', ''),
(30, 14, 0, 14, 'components', 'get_components', '0e7b91f22f76f1e5dba6e7d2868f506a', '', '', ''),
(31, 15, 0, 15, 'components', 'get_components', 'ac1e5db4bfbfc60e630a4cf30ab9a3c7', '', '', ''),
(33, 17, 0, 17, 'components', 'get_components', '66908f131bda02db5183fe8964685441', '', '', ''),
(34, 18, 0, 18, 'components', 'get_components', '019b6935f1f264dcb4fd9359d4483e57', '', '', ''),
(35, 19, 0, 19, 'components', 'get_components', '4eff14c222b8d52b5ae604c065adfa11', '', '', ''),
(36, 20, 0, 20, 'components', 'get_components', 'c584a7db5ea373b804ec464061af27d1', '', '', ''),
(37, 21, 0, 21, 'components', 'get_components', '83f5e0ba961657c05e712cc73031f473', '', '', ''),
(38, 22, 0, 22, 'components', 'get_components', 'ea7c7145816ff37d5c96b4adeac74121', '', '', ''),
(39, 23, 0, 23, 'components', 'get_components', 'c1d6b06025bc74a44fed6f6cbf4fbe1d', '', '', '');

-- --------------------------------------------------------

--
-- Структура таблицы `ko_slider`
--

CREATE TABLE IF NOT EXISTS `ko_slider` (
  `slide_id` int(6) unsigned NOT NULL,
  `menu_id` int(6) unsigned NOT NULL DEFAULT '0',
  `position` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `hidden` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `title_ua` varchar(255) NOT NULL,
  `url_ua` varchar(255) NOT NULL,
  `file_name_ua` varchar(255) NOT NULL,
  `title_ru` varchar(255) NOT NULL,
  `url_ru` varchar(255) NOT NULL,
  `file_name_ru` varchar(255) NOT NULL,
  `title_en` varchar(255) NOT NULL,
  `url_en` varchar(255) NOT NULL,
  `hidden_ua` int(1) NOT NULL,
  `hidden_ru` int(1) NOT NULL,
  `hidden_en` int(1) NOT NULL,
  `file_name_en` varchar(255) NOT NULL,
  `description_ua` varchar(255) NOT NULL,
  `description_ru` varchar(255) NOT NULL,
  `description_en` varchar(255) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=60 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `ko_slider`
--

INSERT INTO `ko_slider` (`slide_id`, `menu_id`, `position`, `hidden`, `title_ua`, `url_ua`, `file_name_ua`, `title_ru`, `url_ru`, `file_name_ru`, `title_en`, `url_en`, `hidden_ua`, `hidden_ru`, `hidden_en`, `file_name_en`, `description_ua`, `description_ru`, `description_en`) VALUES
(59, 1, 10, 0, '', '', '159608_litso_kistochki_akvarel_kraski_.jpg', '', '', '', '', '', 0, 0, 0, '', '', '', ''),
(57, 1, 8, 0, '', '', '159341_facebook_logotip_logo_.jpg', '', '', '', '', '', 0, 0, 0, '', '', '', ''),
(58, 1, 9, 0, '', '', '163729_samuray_busido_yaponiya_katana_by_sira_.jpg', '', '', '', '', '', 0, 0, 0, '', '', '', '');

-- --------------------------------------------------------

--
-- Структура таблицы `ko_swiper`
--

CREATE TABLE IF NOT EXISTS `ko_swiper` (
  `slide_id` int(6) unsigned NOT NULL,
  `menu_id` int(6) unsigned NOT NULL DEFAULT '0',
  `position` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `hidden` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `title_ua` varchar(255) NOT NULL,
  `url_ua` varchar(255) NOT NULL,
  `file_name_ua` varchar(255) NOT NULL,
  `title_ru` varchar(255) NOT NULL,
  `url_ru` varchar(255) NOT NULL,
  `file_name_ru` varchar(255) NOT NULL,
  `title_en` varchar(255) NOT NULL,
  `url_en` varchar(255) NOT NULL,
  `hidden_ua` int(1) NOT NULL,
  `hidden_ru` int(1) NOT NULL,
  `hidden_en` int(1) NOT NULL,
  `file_name_en` varchar(255) NOT NULL,
  `description_ua` varchar(255) NOT NULL,
  `description_ru` varchar(255) NOT NULL,
  `description_en` varchar(255) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=29 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `ko_swiper`
--

INSERT INTO `ko_swiper` (`slide_id`, `menu_id`, `position`, `hidden`, `title_ua`, `url_ua`, `file_name_ua`, `title_ru`, `url_ru`, `file_name_ru`, `title_en`, `url_en`, `hidden_ua`, `hidden_ru`, `hidden_en`, `file_name_en`, `description_ua`, `description_ru`, `description_en`) VALUES
(26, 1, 1, 0, '', '', '150565_nike_just_do_it_slogan_.jpg', '', '', '', '', '', 0, 0, 0, '', '', '', ''),
(27, 1, 2, 0, '', '', '160069_android_yabloko_mech_android_apple_.jpg', '', '', '', '', '', 0, 0, 0, '', '', '', ''),
(28, 1, 3, 0, '', '', '160944_shaman_maska_posoh_cherep_krilya_perya_magiya_.jpg', '', '', '', '', '', 0, 0, 0, '', '', '', '');

-- --------------------------------------------------------

--
-- Структура таблицы `ko_timer`
--

CREATE TABLE IF NOT EXISTS `ko_timer` (
  `id` int(6) NOT NULL,
  `time` int(10) NOT NULL DEFAULT '0',
  `title_ua` varchar(255) NOT NULL,
  `title_ru` varchar(255) NOT NULL,
  `title_en` varchar(255) NOT NULL,
  `title_pl` varchar(255) NOT NULL,
  `sign_ua` varchar(255) NOT NULL,
  `sign_ru` varchar(255) NOT NULL,
  `sign_en` varchar(255) NOT NULL,
  `sign_pl` varchar(255) NOT NULL,
  `photo` varchar(255) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `ko_administrators`
--
ALTER TABLE `ko_administrators`
  ADD PRIMARY KEY (`admin_id`);

--
-- Индексы таблицы `ko_components`
--
ALTER TABLE `ko_components`
  ADD PRIMARY KEY (`component_id`),
  ADD KEY `menu_id` (`menu_id`,`hidden`);

--
-- Индексы таблицы `ko_component_article`
--
ALTER TABLE `ko_component_article`
  ADD PRIMARY KEY (`component_id`),
  ADD KEY `menu_id` (`menu_id`);

--
-- Индексы таблицы `ko_component_benefits`
--
ALTER TABLE `ko_component_benefits`
  ADD PRIMARY KEY (`component_id`);

--
-- Индексы таблицы `ko_component_contacts`
--
ALTER TABLE `ko_component_contacts`
  ADD PRIMARY KEY (`component_id`),
  ADD KEY `menu_id` (`menu_id`);

--
-- Индексы таблицы `ko_component_footer`
--
ALTER TABLE `ko_component_footer`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `ko_component_google_map`
--
ALTER TABLE `ko_component_google_map`
  ADD PRIMARY KEY (`component_id`);

--
-- Индексы таблицы `ko_component_header`
--
ALTER TABLE `ko_component_header`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `ko_component_services`
--
ALTER TABLE `ko_component_services`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `ko_component_swiper`
--
ALTER TABLE `ko_component_swiper`
  ADD PRIMARY KEY (`slide_id`),
  ADD KEY `hidden` (`hidden`);

--
-- Индексы таблицы `ko_config`
--
ALTER TABLE `ko_config`
  ADD PRIMARY KEY (`key`);

--
-- Индексы таблицы `ko_gallery`
--
ALTER TABLE `ko_gallery`
  ADD PRIMARY KEY (`slide_id`),
  ADD KEY `hidden` (`hidden`);

--
-- Индексы таблицы `ko_google_map`
--
ALTER TABLE `ko_google_map`
  ADD PRIMARY KEY (`component_id`),
  ADD KEY `menu_id` (`menu_id`);

--
-- Индексы таблицы `ko_html_components`
--
ALTER TABLE `ko_html_components`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `ko_loyalty`
--
ALTER TABLE `ko_loyalty`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `ko_menu`
--
ALTER TABLE `ko_menu`
  ADD PRIMARY KEY (`id`),
  ADD KEY `parent_id` (`parent_id`,`menu_index`,`hidden`);

--
-- Индексы таблицы `ko_news`
--
ALTER TABLE `ko_news`
  ADD PRIMARY KEY (`news_id`),
  ADD KEY `component_id` (`component_id`);

--
-- Индексы таблицы `ko_news_images`
--
ALTER TABLE `ko_news_images`
  ADD PRIMARY KEY (`image_id`),
  ADD KEY `news_id` (`news_id`);

--
-- Индексы таблицы `ko_reviews`
--
ALTER TABLE `ko_reviews`
  ADD PRIMARY KEY (`review_id`),
  ADD KEY `component_id` (`component_id`);

--
-- Индексы таблицы `ko_seo_link`
--
ALTER TABLE `ko_seo_link`
  ADD PRIMARY KEY (`menu_id`);

--
-- Индексы таблицы `ko_seo_tags`
--
ALTER TABLE `ko_seo_tags`
  ADD PRIMARY KEY (`tags_id`);

--
-- Индексы таблицы `ko_services`
--
ALTER TABLE `ko_services`
  ADD PRIMARY KEY (`id`),
  ADD KEY `parent_id` (`parent_id`,`services_index`,`hidden`);

--
-- Индексы таблицы `ko_site_links`
--
ALTER TABLE `ko_site_links`
  ADD PRIMARY KEY (`link_id`),
  ADD KEY `hash_ua` (`hash_ua`),
  ADD KEY `item_id` (`item_id`,`module`);

--
-- Индексы таблицы `ko_slider`
--
ALTER TABLE `ko_slider`
  ADD PRIMARY KEY (`slide_id`),
  ADD KEY `hidden` (`hidden`);

--
-- Индексы таблицы `ko_swiper`
--
ALTER TABLE `ko_swiper`
  ADD PRIMARY KEY (`slide_id`),
  ADD KEY `hidden` (`hidden`);

--
-- Индексы таблицы `ko_timer`
--
ALTER TABLE `ko_timer`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `ko_administrators`
--
ALTER TABLE `ko_administrators`
  MODIFY `admin_id` int(6) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT для таблицы `ko_components`
--
ALTER TABLE `ko_components`
  MODIFY `component_id` int(6) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=101;
--
-- AUTO_INCREMENT для таблицы `ko_component_benefits`
--
ALTER TABLE `ko_component_benefits`
  MODIFY `component_id` int(6) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=47;
--
-- AUTO_INCREMENT для таблицы `ko_component_footer`
--
ALTER TABLE `ko_component_footer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT для таблицы `ko_component_google_map`
--
ALTER TABLE `ko_component_google_map`
  MODIFY `component_id` int(6) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=48;
--
-- AUTO_INCREMENT для таблицы `ko_component_header`
--
ALTER TABLE `ko_component_header`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT для таблицы `ko_component_services`
--
ALTER TABLE `ko_component_services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `ko_component_swiper`
--
ALTER TABLE `ko_component_swiper`
  MODIFY `slide_id` int(6) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=68;
--
-- AUTO_INCREMENT для таблицы `ko_gallery`
--
ALTER TABLE `ko_gallery`
  MODIFY `slide_id` int(6) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=61;
--
-- AUTO_INCREMENT для таблицы `ko_html_components`
--
ALTER TABLE `ko_html_components`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `ko_loyalty`
--
ALTER TABLE `ko_loyalty`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT для таблицы `ko_menu`
--
ALTER TABLE `ko_menu`
  MODIFY `id` int(6) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=24;
--
-- AUTO_INCREMENT для таблицы `ko_news`
--
ALTER TABLE `ko_news`
  MODIFY `news_id` int(6) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT для таблицы `ko_news_images`
--
ALTER TABLE `ko_news_images`
  MODIFY `image_id` int(6) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=151;
--
-- AUTO_INCREMENT для таблицы `ko_reviews`
--
ALTER TABLE `ko_reviews`
  MODIFY `review_id` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT для таблицы `ko_seo_tags`
--
ALTER TABLE `ko_seo_tags`
  MODIFY `tags_id` int(6) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1378;
--
-- AUTO_INCREMENT для таблицы `ko_services`
--
ALTER TABLE `ko_services`
  MODIFY `id` int(6) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `ko_site_links`
--
ALTER TABLE `ko_site_links`
  MODIFY `link_id` int(6) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=40;
--
-- AUTO_INCREMENT для таблицы `ko_slider`
--
ALTER TABLE `ko_slider`
  MODIFY `slide_id` int(6) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=60;
--
-- AUTO_INCREMENT для таблицы `ko_swiper`
--
ALTER TABLE `ko_swiper`
  MODIFY `slide_id` int(6) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=29;
--
-- AUTO_INCREMENT для таблицы `ko_timer`
--
ALTER TABLE `ko_timer`
  MODIFY `id` int(6) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
