-- phpMyAdmin SQL Dump
-- version 4.0.10.14
-- http://www.phpmyadmin.net
--
-- Хост: localhost:3306
-- Час створення: Гру 13 2016 р., 13:44
-- Версія сервера: 5.5.52-cll
-- Версія PHP: 5.6.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База даних: `chistaos_db`
--

-- --------------------------------------------------------

--
-- Структура таблиці `ko_administrators`
--

CREATE TABLE IF NOT EXISTS `ko_administrators` (
  `admin_id` int(6) NOT NULL AUTO_INCREMENT,
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
  `edited` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`admin_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Дамп даних таблиці `ko_administrators`
--

INSERT INTO `ko_administrators` (`admin_id`, `name`, `login`, `password`, `salt`, `create_date`, `login_date`, `site_menu`, `admin_menu`, `root`, `status`, `edited`) VALUES
(1, 'Адміністратор', 'admin', '3fcc0f1c93aec089e3857f834b9d66e8', '2a81ce665b7a39f7e0c493e5af16ef40', 1410864123, 1480775108, '1,316', 'components,article_index,google_map_index,feedback_index,news_index,comments_index,menu,menu_1,seo,seo_tags,seo_seo_link,seo_xml,seo_site_name,config,config_common,config_languages,config_administrators,config_watermark', 1, 0, 1);

-- --------------------------------------------------------

--
-- Структура таблиці `ko_components`
--

CREATE TABLE IF NOT EXISTS `ko_components` (
  `component_id` int(6) unsigned NOT NULL AUTO_INCREMENT,
  `menu_id` int(6) unsigned NOT NULL,
  `position` int(3) unsigned NOT NULL DEFAULT '0',
  `hidden` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `module` varchar(30) NOT NULL,
  `method` varchar(30) NOT NULL,
  `config` text NOT NULL,
  PRIMARY KEY (`component_id`),
  KEY `menu_id` (`menu_id`,`hidden`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=49 ;

--
-- Дамп даних таблиці `ko_components`
--

INSERT INTO `ko_components` (`component_id`, `menu_id`, `position`, `hidden`, `module`, `method`, `config`) VALUES
(31, 1, 1, 0, 'news', 'index', ''),
(34, 1, 8, 0, 'slider', 'index', ''),
(35, 1, 7, 0, 'article', 'index', ''),
(38, 1, 5, 0, 'loyalty_system', 'index', ''),
(40, 1, 4, 0, 'gallery', 'index', ''),
(42, 1, 9, 0, 'questions', 'index', ''),
(43, 1, 0, 0, 'guarantee', 'index', ''),
(46, 1, 2, 0, 'benefits', 'index', ''),
(47, 1, 6, 0, 'article', 'index', ''),
(48, 1, 3, 0, 'article', 'index', '');

-- --------------------------------------------------------

--
-- Структура таблиці `ko_component_article`
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
  `btn_active` int(1) NOT NULL,
  PRIMARY KEY (`component_id`),
  KEY `menu_id` (`menu_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп даних таблиці `ko_component_article`
--

INSERT INTO `ko_component_article` (`component_id`, `menu_id`, `title_ua`, `title_ru`, `title_en`, `title_pl`, `text_ua`, `text_ru`, `text_en`, `text_pl`, `wide`, `background_fone`, `btn_active`) VALUES
(35, 1, 'Про нас | Прибирання квартир', '', '', '', '<div><p><strong>Клінінгова компанія</strong> &quot;Чиста Оселя&quot; пропонує послуги професійного прибирання у м. Львів: <strong>прибирання після ремонту</strong>; щоденне прибирання;&nbsp;<span style=\\"font-size: 13px; line-height: 20.8px;\\">генеральне прибирання&nbsp;</span>квартир, будинків, офісів, складів;&nbsp;миття вікон, хімчистка &amp; аквачистка&nbsp;<span style=\\"font-size: 13px; line-height: 20.8px;\\">м&rsquo;яких меблів (диванів, матрасів, крісел),</span>&nbsp;<span style=\\"font-size: 13px; line-height: 20.8px;\\">килимів,</span> доріжок, ковроліну.&nbsp;Чиста оселя - запорука затишку та злагоди в сім&rsquo;ї!</p></div><div><p>Сучасний світ приносить нам багато можливостей. Тож щоб не упустити те, що пропонує Вам життя рекомендуємо звільнити трішки свого дорогоцінного часу та доручити обов&rsquo;язок прибирання квартир Львів професіоналам!<br />А звільнений час рекомендуємо провести з рідними, прогулятись, відвідати виставу чи просто почитати книгу, тобто використати на те, на що завжди бракує часу. Або ж спробувати щось нове: велопрогулянка, лекція чи семінар, творчість. Клініг у Львові віддайте нам.</p><p>В результаті можна отримати 2 позитивних результати:</p><ul><li>професійне та чисте прибирання квартири чи будинку;</li><li>час на нові можливості.</li></ul><p><strong>Клінінгова компанія Львів</strong> &ldquo;Чиста Оселя&rdquo; надає наступні послуги:</p><ul><li>щотижневе прибирання квартири;</li><li>прибирання котеджів та заміських будинків у Львові;</li><li>прибирання після ремонту та будівництва у квартирі / особняку;</li><li>генеральне прибирання квартир Львів;</li><li>прибирання комерційної нерухомості Львів;</li><li>миття <span style=\\"font-size: 13px; line-height: 20.8px;\\">фасадів,&nbsp;</span>вікон і вітрин,&nbsp;та інше.</li></ul><p>Послугами нашої клінінгової компанії&nbsp;можуть скористатись жителі Львова та області! Будемо раді знайомитись з новими клієнтами та збільшувати кількість чистих осель та щасливих власників!</p><p>Запевняємо, що ми використовуємо професійне обладнання та миючі засоби провідних виробників під час проведення клінінгових робіт, зокрема під час виконання послуги&nbsp;<span style=\\"font-size: 13px; line-height: 20.8px;\\">&ldquo;<a href=\\"http://chystaoselya.com\\" title=\\"Прибирання квартир ціна\\">Прибирання квартир Львів</a></span><span style=\\"font-size: 13px; line-height: 20.8px;\\">&rdquo;</span>.<br />Також ми гарантуємо &nbsp;індивідуальний підхід до кожного клієнта: з розумінням поставимось до особливих прохань та побажань! Можемо виконувати роботу якісно та непомітно.</p><p>Детальніше можна довілатись на тематичних сторінках сайту! Ми коротко описали наші послуги, якщо Вам потрібні клінінгові послуги, яких не описано на нашому сайті, <span style=\\"font-size: 13px; line-height: 20.8px;\\">обов&rsquo;язково&nbsp;</span>телефонуйте,&nbsp;ми надаємо ще дуже багато додаткових менш популярних пропозицій, пов&rsquo;язаних з клінінгом.</p><p>Чиста Оселя працює на ринку <strong>прибирання квартир</strong>&nbsp;у Львові та області з 2004 року, у своїй роботі використовуємо&nbsp;професійне обладнання &ldquo;Karcher&rdquo; та якісні спеціалізовані хімічні засоби.</p></div>', '', '', '', 0, 0, 0),
(47, 1, 'Ціни на клінінгові послуги', '', '', '', '<table align=\\"center\\" border=\\"1\\" cellpadding=\\"5\\" cellspacing=\\"0\\" dir=\\"ltr\\" style=\\"width:900px;\\"><colgroup><col /><col /><col /></colgroup><tbody><tr><td data-sheets-value=\\"[null,2,\\" style=\\"text-align: center;\\" u0434=\\"\\"><strong>Вид послуги</strong></td><td data-sheets-value=\\"[null,2,\\" style=\\"text-align: center;\\" u0430=\\"\\"><strong>Ціна за м2</strong></td><td colspan=\\"2\\" data-sheets-value=\\"[null,2,\\" rowspan=\\"1\\" style=\\"text-align: center;\\" u044c=\\"\\"><strong>Вартість, грн</strong></td></tr><tr><td colspan=\\"4\\" data-sheets-value=\\"[null,2,\\" u043e=\\"\\" u044c=\\"\\" u044f=\\"\\"><strong>Вартість генерального прибирання квартири</strong></td></tr><tr><td 30=\\"\\" data-sheets-value=\\"[null,2,\\" u0438=\\"\\" u043e=\\"\\" u0456=\\"\\">Однокімнатні квартири до 30 м2</td><td data-sheets-value=\\"[null,3,null,18]\\" style=\\"text-align: center;\\">18</td><td data-sheets-value=\\"[null,2,\\">500-600</td><td data-sheets-value=\\"[null,2,\\">&nbsp;</td></tr><tr><td 30-50=\\"\\" data-sheets-value=\\"[null,2,\\" u0438=\\"\\" u043e=\\"\\" u0456=\\"\\">Одно/двокімнатні квартири 30-50 м2</td><td data-sheets-value=\\"[null,3,null,17]\\" style=\\"text-align: center;\\">17</td><td data-sheets-value=\\"[null,2,\\">600-800</td><td data-sheets-value=\\"[null,2,\\">&nbsp;</td></tr><tr><td 50-70=\\"\\" data-sheets-value=\\"[null,2,\\" u0438=\\"\\" u0456=\\"\\">Трикімнатні квартири 50-70 м2</td><td data-sheets-value=\\"[null,3,null,15]\\" style=\\"text-align: center;\\">15</td><td data-sheets-value=\\"[null,2,\\">800-1000</td><td data-sheets-value=\\"[null,2,\\">&nbsp;</td></tr><tr><td 70-100=\\"\\" data-sheets-value=\\"[null,2,\\" u0438=\\"\\" u0456=\\"\\">Чотирикімнатні квартири 70-100 м2</td><td data-sheets-value=\\"[null,3,null,14]\\" style=\\"text-align: center;\\">14</td><td data-sheets-value=\\"[null,2,\\">1000-1400</td><td data-sheets-value=\\"[null,2,\\">&nbsp;</td></tr><tr><td 100=\\"\\" data-sheets-value=\\"[null,2,\\" u0434=\\"\\" u0438=\\"\\" u0456=\\"\\">Багатокімнатні квартири понад 100 м2</td><td data-sheets-value=\\"[null,3,null,13]\\" style=\\"text-align: center;\\">13</td><td data-sheets-value=\\"[null,2,\\">договірна</td><td data-sheets-value=\\"[null,2,\\">&nbsp;</td></tr><tr><td colspan=\\"4\\" data-sheets-value=\\"[null,2,\\" u043e=\\"\\" u044c=\\"\\" u044f=\\"\\"><strong>Вартість щотижневого прибирання</strong></td></tr><tr><td 30=\\"\\" data-sheets-value=\\"[null,2,\\" u0438=\\"\\" u043e=\\"\\" u0456=\\"\\">Однокімнатні квартири до 30 м2</td><td data-sheets-value=\\"[null,3,null,18]\\" style=\\"text-align: center;\\">18</td><td data-sheets-value=\\"[null,2,\\">500-600</td><td data-sheets-value=\\"[null,2,\\">&nbsp;</td></tr><tr><td 30-50=\\"\\" data-sheets-value=\\"[null,2,\\" u0438=\\"\\" u043e=\\"\\" u0456=\\"\\">Одно/двокімнатні квартири 30-50 м2</td><td data-sheets-value=\\"[null,3,null,17]\\" style=\\"text-align: center;\\">17</td><td data-sheets-value=\\"[null,2,\\">600-800</td><td data-sheets-value=\\"[null,2,\\">&nbsp;</td></tr><tr><td 50-70=\\"\\" data-sheets-value=\\"[null,2,\\" u0438=\\"\\" u0456=\\"\\">Трикімнатні квартири 50-70 м2</td><td data-sheets-value=\\"[null,3,null,15]\\" style=\\"text-align: center;\\">15</td><td data-sheets-value=\\"[null,2,\\">800-1000</td><td data-sheets-value=\\"[null,2,\\">&nbsp;</td></tr><tr><td 70-100=\\"\\" data-sheets-value=\\"[null,2,\\" u0438=\\"\\" u0456=\\"\\">Чотирикімнатні квартири 70-100 м2</td><td data-sheets-value=\\"[null,3,null,14]\\" style=\\"text-align: center;\\">14</td><td data-sheets-value=\\"[null,2,\\">1000-1400</td><td data-sheets-value=\\"[null,2,\\">&nbsp;</td></tr><tr><td 100=\\"\\" data-sheets-value=\\"[null,2,\\" u0434=\\"\\" u0438=\\"\\" u0456=\\"\\">Багатокімнатні квартири понад 100 м2</td><td data-sheets-value=\\"[null,3,null,13]\\" style=\\"text-align: center;\\">13</td><td data-sheets-value=\\"[null,2,\\">договірна</td><td data-sheets-value=\\"[null,2,\\">&nbsp;</td></tr><tr><td colspan=\\"4\\" data-sheets-value=\\"[null,2,\\" u0430=\\"\\" u0443=\\"\\" u044f=\\"\\"><strong>Прибирання після ремонту та будівництва</strong></td></tr><tr><td 100=\\"\\" data-sheets-value=\\"[null,2,\\" u043e=\\"\\">до 100 м2</td><td data-sheets-value=\\"[null,2,\\" style=\\"text-align: center;\\" u0434=\\"\\">від 18</td><td>&nbsp;</td><td>&nbsp;</td></tr><tr><td 200=\\"\\" data-sheets-value=\\"[null,2,\\" u043e=\\"\\">до 200 м2</td><td data-sheets-value=\\"[null,3,null,11]\\" style=\\"text-align: center;\\">15</td><td>&nbsp;</td><td>&nbsp;</td></tr><tr><td 500=\\"\\" data-sheets-value=\\"[null,2,\\" u043e=\\"\\">до 500 м2</td><td data-sheets-value=\\"[null,3,null,10]\\" style=\\"text-align: center;\\">10</td><td>&nbsp;</td><td>&nbsp;</td></tr><tr><td 2000=\\"\\" data-sheets-value=\\"[null,2,\\" u043e=\\"\\">до 2000 м2</td><td data-sheets-value=\\"[null,3,null,9]\\" style=\\"text-align: center;\\">договірна</td><td>&nbsp;</td><td>&nbsp;</td></tr><tr><td 2000=\\"\\" data-sheets-value=\\"[null,2,\\" u0434=\\"\\">понад 2000 м2</td><td data-sheets-value=\\"[null,2,\\" style=\\"text-align: center;\\">договірна</td><td>&nbsp;</td><td>&nbsp;</td></tr><tr><td colspan=\\"4\\" data-sheets-value=\\"[null,2,\\" u044c=\\"\\" u044f=\\"\\"><strong>Вартість миття вікон</strong></td></tr><tr><td data-sheets-value=\\"[null,2,\\" u0435=\\"\\" u043d=\\"\\" u0443=\\"\\" u044f=\\"\\">Звичайне миття вікон у квартирі</td><td data-sheets-value=\\"[null,2,\\" style=\\"text-align: center;\\" u0434=\\"\\">від 10</td><td 50=\\"\\" data-sheets-value=\\"[null,2,\\" u043e=\\"\\">до 50 м2</td><td 50=\\"\\" data-sheets-value=\\"[null,2,\\" u043e=\\"\\">&nbsp;</td></tr><tr><td data-sheets-value=\\"[null,2,\\" u0445=\\"\\" u044f=\\"\\">Миття великогабаритних вікон</td><td data-sheets-value=\\"[null,2,\\" style=\\"text-align: center;\\" u0434=\\"\\">від 8</td><td 100=\\"\\" data-sheets-value=\\"[null,2,\\" u043e=\\"\\">до 100 м2</td><td 100=\\"\\" data-sheets-value=\\"[null,2,\\" u043e=\\"\\">&nbsp;</td></tr><tr><td data-sheets-value=\\"[null,2,\\" u0434=\\"\\" u043d=\\"\\" u044e=\\"\\" u044f=\\"\\">Миття вікон висотою понад 3м</td><td data-sheets-value=\\"[null,2,\\" style=\\"text-align: center;\\" u0434=\\"\\">від 12</td><td 100=\\"\\" data-sheets-value=\\"[null,2,\\" u043e=\\"\\">до 100 м2</td><td 100=\\"\\" data-sheets-value=\\"[null,2,\\" u043e=\\"\\">&nbsp;</td></tr><tr><td data-sheets-value=\\"[null,2,\\" u043d=\\"\\" u044f=\\"\\">Миття вікон після ремонту</td><td 12=\\"\\" data-sheets-numberformat=\\"[null,2,\\" data-sheets-value=\\"[null,2,\\" style=\\"text-align: center;\\" u0434=\\"\\" u043e=\\"\\">від 12 до 30</td><td 50=\\"\\" data-sheets-value=\\"[null,2,\\" u043e=\\"\\">до 50 м2</td><td 50=\\"\\" data-sheets-value=\\"[null,2,\\" u043e=\\"\\">&nbsp;</td></tr><tr><td data-sheets-value=\\"[null,2,\\" u0437=\\"\\" u043d=\\"\\" u0445=\\"\\" u044e=\\"\\" u044f=\\"\\">Миття фасадних вікон з допомогою автовишки</td><td colspan=\\"2\\" data-sheets-value=\\"[null,2,\\" rowspan=\\"1\\" style=\\"text-align: center;\\">договірна</td><td>&nbsp;</td></tr><tr><td data-sheets-value=\\"[null,2,\\" u0437=\\"\\" u043d=\\"\\" u044e=\\"\\" u044f=\\"\\">Миття вікон з допомогою альпіністів</td><td colspan=\\"2\\" data-sheets-value=\\"[null,2,\\" rowspan=\\"1\\" style=\\"text-align: center;\\">договірна</td><td>&nbsp;</td></tr><tr><td colspan=\\"3\\" data-sheets-value=\\"[null,2,\\" rowspan=\\"1\\" u0430=\\"\\" u0432=\\"\\" u0445=\\"\\" u044f=\\"\\"><strong>Прибирання котеджів та заміських будинків ціна договірна</strong></td><td data-sheets-value=\\"[null,2,\\" u0430=\\"\\" u0432=\\"\\" u0445=\\"\\" u044f=\\"\\">&nbsp;</td></tr><tr><td colspan=\\"3\\" data-sheets-value=\\"[null,2,\\" rowspan=\\"1\\" u0430=\\"\\" u044f=\\"\\" u0456=\\"\\" u0457=\\"\\"><strong>Прибирання комерційної нерухомості ціна договірна</strong></td><td data-sheets-value=\\"[null,2,\\" rowspan=\\"1\\" u0430=\\"\\" u044f=\\"\\" u0456=\\"\\" u0457=\\"\\">&nbsp;</td></tr><tr><td colspan=\\"4\\" data-sheets-value=\\"[null,2,\\" u0430=\\"\\" u044f=\\"\\" u0456=\\"\\" u0457=\\"\\"><strong style=\\"font-size: 13px; line-height: 20.8px; text-align: center;\\">Хімчистка килимових покриттів</strong></td></tr><tr><td colspan=\\"2\\" data-sheets-value=\\"[null,2,\\" u0430=\\"\\" u044f=\\"\\" u0456=\\"\\" u0457=\\"\\"><span style=\\"font-size: 13px; line-height: 20.8px;\\">Хімчистка ковроліну</span></td><td data-sheets-value=\\"[null,2,\\" u0430=\\"\\" u044f=\\"\\" u0456=\\"\\" u0457=\\"\\">14 грн/м<sup>2</sup></td><td data-sheets-value=\\"[null,2,\\" u0430=\\"\\" u044f=\\"\\" u0456=\\"\\" u0457=\\"\\">&nbsp;</td></tr><tr><td colspan=\\"4\\" data-sheets-value=\\"[null,2,\\" u0430=\\"\\" u044f=\\"\\" u0456=\\"\\" u0457=\\"\\"><strong style=\\"font-size: 13px; line-height: 20.8px; text-align: center;\\">Хімчистка м&rsquo;яких меблів</strong></td></tr><tr><td colspan=\\"2\\" data-sheets-value=\\"[null,2,\\" u0430=\\"\\" u044f=\\"\\" u0456=\\"\\" u0457=\\"\\"><span style=\\"font-size: 13px; line-height: 20.8px;\\">Диван-книжка</span></td><td data-sheets-value=\\"[null,2,\\" u0430=\\"\\" u044f=\\"\\" u0456=\\"\\" u0457=\\"\\"><span style=\\"font-size: 13px; line-height: 20.8px;\\">150-200 грн</span></td><td data-sheets-value=\\"[null,2,\\" u0430=\\"\\" u044f=\\"\\" u0456=\\"\\" u0457=\\"\\">&nbsp;</td></tr><tr><td colspan=\\"2\\" data-sheets-value=\\"[null,2,\\" u0430=\\"\\" u044f=\\"\\" u0456=\\"\\" u0457=\\"\\"><span style=\\"font-size: 13px; line-height: 20.8px;\\">Диван кутовий</span></td><td data-sheets-value=\\"[null,2,\\" u0430=\\"\\" u044f=\\"\\" u0456=\\"\\" u0457=\\"\\"><span style=\\"font-size: 13px; line-height: 20.8px;\\">250-300 грн</span></td><td data-sheets-value=\\"[null,2,\\" u0430=\\"\\" u044f=\\"\\" u0456=\\"\\" u0457=\\"\\">&nbsp;</td></tr><tr><td colspan=\\"2\\" data-sheets-value=\\"[null,2,\\" u0430=\\"\\" u044f=\\"\\" u0456=\\"\\" u0457=\\"\\"><span style=\\"font-size: 13px; line-height: 20.8px;\\">Крісло (диванний комплект)</span></td><td data-sheets-value=\\"[null,2,\\" u0430=\\"\\" u044f=\\"\\" u0456=\\"\\" u0457=\\"\\"><span style=\\"font-size: 13px; line-height: 20.8px;\\">100 грн</span></td><td data-sheets-value=\\"[null,2,\\" u0430=\\"\\" u044f=\\"\\" u0456=\\"\\" u0457=\\"\\">&nbsp;</td></tr><tr><td colspan=\\"2\\" data-sheets-value=\\"[null,2,\\" u0430=\\"\\" u044f=\\"\\" u0456=\\"\\" u0457=\\"\\"><span style=\\"font-size: 13px; line-height: 20.8px;\\">Стілець</span></td><td data-sheets-value=\\"[null,2,\\" u0430=\\"\\" u044f=\\"\\" u0456=\\"\\" u0457=\\"\\"><span style=\\"font-size: 13px; line-height: 20.8px;\\">20 грн</span></td><td data-sheets-value=\\"[null,2,\\" u0430=\\"\\" u044f=\\"\\" u0456=\\"\\" u0457=\\"\\">&nbsp;</td></tr><tr><td colspan=\\"2\\" data-sheets-value=\\"[null,2,\\" u0430=\\"\\" u044f=\\"\\" u0456=\\"\\" u0457=\\"\\"><span style=\\"font-size: 13px; line-height: 20.8px;\\">Матрац</span></td><td data-sheets-value=\\"[null,2,\\" u0430=\\"\\" u044f=\\"\\" u0456=\\"\\" u0457=\\"\\"><span style=\\"font-size: 13px; line-height: 20.8px;\\">150-200 грн</span></td><td data-sheets-value=\\"[null,2,\\" u0430=\\"\\" u044f=\\"\\" u0456=\\"\\" u0457=\\"\\">&nbsp;</td></tr></tbody></table><p>*Ціни на послуги коливаються в залежності від рівня забруднення, площі об&#39;єкту<br />**Виїзд менеджера до клієнта та оцінка об&rsquo;єкта в межах міста &ndash; БЕЗКОШТОВНО!!!</p>', '', '', '', 0, 1, 1),
(48, 1, 'Відео про прибирання квартир та офісів', '', '', '', '<p>​</p><table align=\\"center\\" border=\\"0\\" cellpadding=\\"5\\" cellspacing=\\"0\\" style=\\"width:100%;\\"><tbody><tr><td><iframe allowfullscreen=\\"\\" frameborder=\\"0\\" height=\\"300\\" src=\\"https://www.youtube.com/embed/pR1crZxYT9A\\" width=\\"480\\"></iframe></td><td><iframe allowfullscreen=\\"\\" frameborder=\\"0\\" height=\\"300\\" src=\\"https://www.youtube.com/embed/9oW-STLbvIg\\" width=\\"480\\"></iframe></td></tr><tr><td><iframe allowfullscreen=\\"\\" frameborder=\\"0\\" height=\\"300\\" src=\\"https://www.youtube.com/embed/ZuwwYU_DCvw\\" width=\\"480\\"></iframe></td><td><iframe allowfullscreen=\\"\\" frameborder=\\"0\\" height=\\"300\\" src=\\"https://www.youtube.com/embed/q5CnMbyWM64\\" width=\\"480\\"></iframe></td></tr><tr><td><iframe allowfullscreen=\\"\\" frameborder=\\"0\\" height=\\"300\\" src=\\"https://www.youtube.com/embed/WO2swxLqovw\\" width=\\"480\\"></iframe></td><td><iframe width=\\"480\\" height=\\"300\\" src=\\"https://www.youtube.com/embed/ARkvQoPda-Q\\" frameborder=\\"0\\" allowfullscreen></iframe></td></tr></tbody></table><p>&nbsp;</p>', '', '', '', 0, 1, 0);

-- --------------------------------------------------------

--
-- Структура таблиці `ko_component_footer`
--

CREATE TABLE IF NOT EXISTS `ko_component_footer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `vk` varchar(255) NOT NULL,
  `fb` varchar(255) NOT NULL,
  `gplus` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Дамп даних таблиці `ko_component_footer`
--

INSERT INTO `ko_component_footer` (`id`, `vk`, `fb`, `gplus`) VALUES
(1, 'http://vk.com/public109204160', 'https://www.facebook.com/Чиста-оселя-961896823881280/?fref=ts', 'https://plus.google.com/u/0/110578169760680701903');

-- --------------------------------------------------------

--
-- Структура таблиці `ko_component_frequent`
--

CREATE TABLE IF NOT EXISTS `ko_component_frequent` (
  `id` int(11) NOT NULL,
  `answer` text NOT NULL,
  `questions` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп даних таблиці `ko_component_frequent`
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
-- Структура таблиці `ko_component_header`
--

CREATE TABLE IF NOT EXISTS `ko_component_header` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
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
  `time_ru` int(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Дамп даних таблиці `ko_component_header`
--

INSERT INTO `ko_component_header` (`id`, `slogan_ua`, `slogan_ru`, `address_ua`, `address_ru`, `kyivstar_ua`, `life_ua`, `mts_ua`, `phone4_ua`, `kyivstar_ru`, `life_ru`, `mts_ru`, `phone4_ru`, `title_action_ua`, `title_action_ru`, `main_action_ua`, `main_action_ru`, `time_ua`, `time_ru`) VALUES
(1, 'Професійне прибирання', '2', 'м. Львів<br>\r\nвул. Смаль-Стоцького 1<br>\r\nОфіс: 312<br>', 'ролро', '+38 (068) 555 80 80', '+38 (073) 805 80 80', '+38 (050) 449 80 80', '032-239-37-69', '', '345345', '34534', '345345', 'АКЦІЯ - 50%', 'dfgdfgdfg', 'На вартість монтажних робіт<br>\r\nпісля підписання договору', 'ролрол', 1425061800, 1424192640);

-- --------------------------------------------------------

--
-- Структура таблиці `ko_component_services`
--

CREATE TABLE IF NOT EXISTS `ko_component_services` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `t1` varchar(250) NOT NULL DEFAULT '0',
  `t2` varchar(250) NOT NULL DEFAULT '0',
  `t3` varchar(250) NOT NULL DEFAULT '0',
  `t4` varchar(250) NOT NULL DEFAULT '0',
  `m1` text NOT NULL,
  `m2` text NOT NULL,
  `m3` text NOT NULL,
  `m4` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Дамп даних таблиці `ko_component_services`
--

INSERT INTO `ko_component_services` (`id`, `t1`, `t2`, `t3`, `t4`, `m1`, `m2`, `m3`, `m4`) VALUES
(1, 'ЛІФТИ', 'ЕСКАЛАТОРИ', 'ПІДЙОМНИКИ', 'ТРАВОЛАТОРИ', '<table class=\\"ep-dp-dt\\" style=\\"width: 630px; color: rgb(0, 0, 0); font-family: Arial, sans-serif; font-size: small; line-height: normal;\\"><tbody><tr id=\\":2o.calendarcontainer-row\\"><th class=\\"ep-dp-dt-th\\" style=\\"padding-bottom: 10px; vertical-align: middle; color: rgb(51, 51, 51); padding-right: 10px; text-align: right; white-space: nowrap;\\"><span style=\\"background-color:Lime;\\"><span style=\\"font-family:trebuchet ms,helvetica,sans-serif;\\"><label id=\\":2o.calendarcontainer-label\\">Календарь</label></span></span></th><td class=\\"ep-dp-dt-td\\" style=\\"padding-bottom: 10px; vertical-align: top; width: 459px;\\"><div class=\\"ep-dp-calendar\\" id=\\":2o.calendarcontainer\\"><span style=\\"background-color:Lime;\\"><span style=\\"font-family:trebuchet ms,helvetica,sans-serif;\\">&quot;SUFIX&quot; web studio - Андрій Сагатий</span></span></div></td></tr><tr id=\\":2o.createdby-row\\"><th class=\\"ep-dp-dt-th\\" style=\\"padding-bottom: 10px; vertical-align: top; color: rgb(51, 51, 51); padding-right: 10px; text-align: right; white-space: nowrap;\\"><span style=\\"background-color:Lime;\\"><span style=\\"font-family:trebuchet ms,helvetica,sans-serif;\\"><label id=\\":2o.createdby-label\\">Автор:</label></span></span></th><td class=\\"ep-dp-dt-td\\" style=\\"padding-bottom: 10px; vertical-align: top; width: 459px;\\"><div class=\\"ep-dp-createdby\\" id=\\":2o.createdby\\"><span style=\\"background-color:Lime;\\"><span style=\\"font-family:trebuchet ms,helvetica,sans-serif;\\">kirsland@gmail.com</span></span></div></td></tr><tr id=\\":2o.descript-row\\"><th class=\\"ep-dp-dt-th\\" style=\\"padding-bottom: 10px; vertical-align: top; color: rgb(51, 51, 51); padding-right: 10px; text-align: right; white-space: nowrap;\\"><span style=\\"background-color:Lime;\\"><span style=\\"font-family:trebuchet ms,helvetica,sans-serif;\\"><label id=\\":2o.descript-label\\">Описание</label></span></span></th><td class=\\"ep-dp-dt-td\\" style=\\"padding-bottom: 10px; vertical-align: top; width: 459px;\\"><div class=\\"ep-dp-descript\\" id=\\":2o.descript\\" style=\\"line-height: 1.4em; white-space: pre-wrap;\\"><div class=\\"ui-sch\\" style=\\"padding-right: 6px;\\"><div class=\\"ui-sch-view\\" style=\\"border: 0px; margin: 0px; padding: 0px; max-width: 550px; overflow: auto;\\"><span style=\\"background-color:Lime;\\"><span style=\\"font-family:trebuchet ms,helvetica,sans-serif;\\">Верстка - 12 год*30=360 грн Програмування. Сайт натягнути на адмінку. Зроби ефект плавного появлення інформації при попаданні її на видиму область монітору. Обговоримо ще це. Всі блоки повинні бути як компоненти, які редагуються тільки у випадку наявності досить великої к-сті тексту. Потрібно мати можливість змінювати в редакторі всі тексти по компонентах та тексти всіх поп-ап вікон. Всі контакти повинні редагуватись в налаштуваннях, це стосується і позначки на Мапі. Це в нас буде кістяк для насупних лендінгів. Програмування: 15 год*40=600 грн Разом: 960 грн</span></span></div></div></div></td></tr><tr><td colspan=\\"2\\">&nbsp;</td></tr></tbody></table>', '<table class=\\"ep-dp-dt\\" style=\\"width: 630px; color: rgb(0, 0, 0); font-family: Arial, sans-serif; font-size: small; line-height: normal;\\"><tbody><tr id=\\":2o.calendarcontainer-row\\"><th class=\\"ep-dp-dt-th\\" style=\\"padding-bottom: 10px; vertical-align: middle; color: rgb(51, 51, 51); padding-right: 10px; text-align: right; white-space: nowrap;\\"><label id=\\":2o.calendarcontainer-label\\">Календарь</label></th><td class=\\"ep-dp-dt-td\\" style=\\"padding-bottom: 10px; vertical-align: top; width: 459px;\\"><div class=\\"ep-dp-calendar\\" id=\\":2o.calendarcontainer\\">&quot;SUFIX&quot; web studio - Андрій Сагатий</div></td></tr><tr id=\\":2o.createdby-row\\"><th class=\\"ep-dp-dt-th\\" style=\\"padding-bottom: 10px; vertical-align: top; color: rgb(51, 51, 51); padding-right: 10px; text-align: right; white-space: nowrap;\\"><label id=\\":2o.createdby-label\\">Автор:</label></th><td class=\\"ep-dp-dt-td\\" style=\\"padding-bottom: 10px; vertical-align: top; width: 459px;\\"><div class=\\"ep-dp-createdby\\" id=\\":2o.createdby\\">kirsland@gmail.com</div></td></tr><tr id=\\":2o.descript-row\\"><th class=\\"ep-dp-dt-th\\" style=\\"padding-bottom: 10px; vertical-align: top; color: rgb(51, 51, 51); padding-right: 10px; text-align: right; white-space: nowrap;\\"><label id=\\":2o.descript-label\\">Описание</label></th><td class=\\"ep-dp-dt-td\\" style=\\"padding-bottom: 10px; vertical-align: top; width: 459px;\\"><div class=\\"ep-dp-descript\\" id=\\":2o.descript\\" style=\\"line-height: 1.4em; white-space: pre-wrap;\\"><div class=\\"ui-sch\\" style=\\"padding-right: 6px;\\"><div class=\\"ui-sch-view\\" style=\\"border: 0px; margin: 0px; padding: 0px; max-width: 550px; overflow: auto;\\">Верстка - 12 год*30=360 грн Програмування. Сайт натягнути на адмінку. Зроби ефект плавного появлення інформації при попаданні її на видиму область монітору. Обговоримо ще це. Всі блоки повинні бути як компоненти, які редагуються тільки у випадку наявності досить великої к-сті тексту. Потрібно мати можливість змінювати в редакторі всі тексти по компонентах та тексти всіх поп-ап вікон. Всі контакти повинні редагуватись в налаштуваннях, це стосується і позначки на Мапі. Це в нас буде кістяк для насупних лендінгів. Програмування: 15 год*40=600 грн Разом: 960 грн</div></div></div></td></tr><tr><td colspan=\\"2\\">&nbsp;</td></tr></tbody></table>', '<table class=\\"ep-dp-dt\\" style=\\"width: 630px; color: rgb(0, 0, 0); font-family: Arial, sans-serif; font-size: small; line-height: normal;\\"><tbody><tr id=\\":2o.calendarcontainer-row\\"><th class=\\"ep-dp-dt-th\\" style=\\"padding-bottom: 10px; vertical-align: middle; color: rgb(51, 51, 51); padding-right: 10px; text-align: right; white-space: nowrap;\\"><label id=\\":2o.calendarcontainer-label\\">Календарь</label></th><td class=\\"ep-dp-dt-td\\" style=\\"padding-bottom: 10px; vertical-align: top; width: 459px;\\"><div class=\\"ep-dp-calendar\\" id=\\":2o.calendarcontainer\\">&quot;SUFIX&quot; web studio - Андрій Сагатий</div></td></tr><tr id=\\":2o.createdby-row\\"><th class=\\"ep-dp-dt-th\\" style=\\"padding-bottom: 10px; vertical-align: top; color: rgb(51, 51, 51); padding-right: 10px; text-align: right; white-space: nowrap;\\"><label id=\\":2o.createdby-label\\">Автор:</label></th><td class=\\"ep-dp-dt-td\\" style=\\"padding-bottom: 10px; vertical-align: top; width: 459px;\\"><div class=\\"ep-dp-createdby\\" id=\\":2o.createdby\\">kirsland@gmail.com</div></td></tr><tr id=\\":2o.descript-row\\"><th class=\\"ep-dp-dt-th\\" style=\\"padding-bottom: 10px; vertical-align: top; color: rgb(51, 51, 51); padding-right: 10px; text-align: right; white-space: nowrap;\\"><label id=\\":2o.descript-label\\">Описание</label></th><td class=\\"ep-dp-dt-td\\" style=\\"padding-bottom: 10px; vertical-align: top; width: 459px;\\"><div class=\\"ep-dp-descript\\" id=\\":2o.descript\\" style=\\"line-height: 1.4em; white-space: pre-wrap;\\"><div class=\\"ui-sch\\" style=\\"padding-right: 6px;\\"><div class=\\"ui-sch-view\\" style=\\"border: 0px; margin: 0px; padding: 0px; max-width: 550px; overflow: auto;\\">Верстка - 12 год*30=360 грн Програмування. Сайт натягнути на адмінку. Зроби ефект плавного появлення інформації при попаданні її на видиму область монітору. Обговоримо ще це. Всі блоки повинні бути як компоненти, які редагуються тільки у випадку наявності досить великої к-сті тексту. Потрібно мати можливість змінювати в редакторі всі тексти по компонентах та тексти всіх поп-ап вікон. Всі контакти повинні редагуватись в налаштуваннях, це стосується і позначки на Мапі. Це в нас буде кістяк для насупних лендінгів. Програмування: 15 год*40=600 грн Разом: 960 грн</div></div></div></td></tr><tr><td colspan=\\"2\\">&nbsp;</td></tr></tbody></table>', '<table class=\\"ep-dp-dt\\" style=\\"width: 630px; color: rgb(0, 0, 0); font-family: Arial, sans-serif; font-size: small; line-height: normal;\\"><tbody><tr id=\\":2o.calendarcontainer-row\\"><th class=\\"ep-dp-dt-th\\" style=\\"padding-bottom: 10px; vertical-align: middle; color: rgb(51, 51, 51); padding-right: 10px; text-align: right; white-space: nowrap;\\"><label id=\\":2o.calendarcontainer-label\\">Календарь</label></th><td class=\\"ep-dp-dt-td\\" style=\\"padding-bottom: 10px; vertical-align: top; width: 459px;\\"><div class=\\"ep-dp-calendar\\" id=\\":2o.calendarcontainer\\">&quot;SUFIX&quot; web studio - Андрій Сагатий</div></td></tr><tr id=\\":2o.createdby-row\\"><th class=\\"ep-dp-dt-th\\" style=\\"padding-bottom: 10px; vertical-align: top; color: rgb(51, 51, 51); padding-right: 10px; text-align: right; white-space: nowrap;\\"><label id=\\":2o.createdby-label\\">Автор:</label></th><td class=\\"ep-dp-dt-td\\" style=\\"padding-bottom: 10px; vertical-align: top; width: 459px;\\"><div class=\\"ep-dp-createdby\\" id=\\":2o.createdby\\">kirsland@gmail.com</div></td></tr><tr id=\\":2o.descript-row\\"><th class=\\"ep-dp-dt-th\\" style=\\"padding-bottom: 10px; vertical-align: top; color: rgb(51, 51, 51); padding-right: 10px; text-align: right; white-space: nowrap;\\"><label id=\\":2o.descript-label\\">Описание</label></th><td class=\\"ep-dp-dt-td\\" style=\\"padding-bottom: 10px; vertical-align: top; width: 459px;\\"><div class=\\"ep-dp-descript\\" id=\\":2o.descript\\" style=\\"line-height: 1.4em; white-space: pre-wrap;\\"><div class=\\"ui-sch\\" style=\\"padding-right: 6px;\\"><div class=\\"ui-sch-view\\" style=\\"border: 0px; margin: 0px; padding: 0px; max-width: 550px; overflow: auto;\\">Верстка - 12 год*30=360 грн Програмування. Сайт натягнути на адмінку. Зроби ефект плавного появлення інформації при попаданні її на видиму область монітору. Обговоримо ще це. Всі блоки повинні бути як компоненти, які редагуються тільки у випадку наявності досить великої к-сті тексту. Потрібно мати можливість змінювати в редакторі всі тексти по компонентах та тексти всіх поп-ап вікон. Всі контакти повинні редагуватись в налаштуваннях, це стосується і позначки на Мапі. Це в нас буде кістяк для насупних лендінгів. Програмування: 15 год*40=600 грн Разом: 960 грн</div></div></div></td></tr><tr><td colspan=\\"2\\">&nbsp;</td></tr></tbody></table>');

-- --------------------------------------------------------

--
-- Структура таблиці `ko_config`
--

CREATE TABLE IF NOT EXISTS `ko_config` (
  `key` varchar(20) NOT NULL,
  `val` varchar(255) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп даних таблиці `ko_config`
--

INSERT INTO `ko_config` (`key`, `val`) VALUES
('def_lang', 'ua'),
('delete_alert', '1'),
('languages', 'a:2:{i:0;s:2:"ua";i:1;s:2:"ru";}'),
('print_icon', '0'),
('site_email', 'chysta.oselya@gmail.com'),
('site_name_en', 'Public union ukrainian association of swimming clubs "Masters"'),
('site_name_pl', ''),
('site_name_ru', 'Лікування'),
('site_name_ua', 'Cleen House'),
('watermark', ''),
('watermark_opacity', '0.25'),
('watermark_padding', '20');

-- --------------------------------------------------------

--
-- Структура таблиці `ko_gallery`
--

CREATE TABLE IF NOT EXISTS `ko_gallery` (
  `slide_id` int(6) unsigned NOT NULL AUTO_INCREMENT,
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
  `description_en` varchar(255) NOT NULL,
  PRIMARY KEY (`slide_id`),
  KEY `hidden` (`hidden`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=61 ;

--
-- Дамп даних таблиці `ko_gallery`
--

INSERT INTO `ko_gallery` (`slide_id`, `menu_id`, `position`, `hidden`, `title_ua`, `url_ua`, `file_name_ua`, `title_ru`, `url_ru`, `file_name_ru`, `title_en`, `url_en`, `hidden_ua`, `hidden_ru`, `hidden_en`, `file_name_en`, `description_ua`, `description_ru`, `description_en`) VALUES
(55, 1, 3, 0, '', '', '3.jpg', '', '', '', '', '', 0, 0, 0, '', '', '', ''),
(56, 1, 4, 0, '', '', '4.jpg', '', '', '', '', '', 0, 0, 0, '', '', '', ''),
(53, 1, 1, 0, '', '', '1.jpg', '', '', '', '', '', 0, 0, 0, '', '', '', ''),
(54, 1, 2, 0, '', '', '2.jpg', '', '', '', '', '', 0, 0, 0, '', '', '', ''),
(57, 1, 5, 0, '', '', '6.jpg', '', '', '', '', '', 0, 0, 0, '', '', '', ''),
(58, 1, 6, 0, '', '', '7.jpg', '', '', '', '', '', 0, 0, 0, '', '', '', ''),
(59, 1, 7, 0, '', '', '8.jpg', '', '', '', '', '', 0, 0, 0, '', '', '', ''),
(60, 1, 8, 0, '', '', '5.jpg', '', '', '', '', '', 0, 0, 0, '', '', '', '');

-- --------------------------------------------------------

--
-- Структура таблиці `ko_html_components`
--

CREATE TABLE IF NOT EXISTS `ko_html_components` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `main_html_ua` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблиці `ko_loyalty`
--

CREATE TABLE IF NOT EXISTS `ko_loyalty` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `component_id` int(11) NOT NULL,
  `text` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Дамп даних таблиці `ko_loyalty`
--

INSERT INTO `ko_loyalty` (`id`, `component_id`, `text`) VALUES
(1, 0, '<p>Система бонусів та лояльності:</p>\n\n<p>Вже після першого прибирання Ви отримаєте картку, на якій будуть накопичуватись бонуси у вигляді знижок на кожне наступне замовлення послуг.</p>\n\n<p>Знижка вираховується від загальної суми Ваших замовлень :</p>\n\n<ul>\n	<li>Від 1000 грн. &ndash; 3% знижка</li>\n	<li>Від 10&nbsp;000 грн. &ndash; 5% знижка</li>\n	<li>Від 20&nbsp;000 грн. &ndash; 10% знижка</li>\n</ul>\n');

-- --------------------------------------------------------

--
-- Структура таблиці `ko_menu`
--

CREATE TABLE IF NOT EXISTS `ko_menu` (
  `id` int(6) unsigned NOT NULL AUTO_INCREMENT,
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
  `update` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `parent_id` (`parent_id`,`menu_index`,`hidden`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Дамп даних таблиці `ko_menu`
--

INSERT INTO `ko_menu` (`id`, `parent_id`, `level`, `menu_index`, `position`, `hidden`, `main`, `target`, `url_path_id`, `name_ua`, `title_ua`, `url_ua`, `url_path_ua`, `url_hash_ua`, `static_url_ua`, `name_ru`, `title_ru`, `url_ru`, `url_path_ru`, `url_hash_ru`, `static_url_ru`, `name_en`, `title_en`, `url_en`, `url_path_en`, `url_hash_en`, `static_url_en`, `name_pl`, `title_pl`, `url_pl`, `url_path_pl`, `url_hash_pl`, `static_url_pl`, `image`, `icon`, `code`, `update`) VALUES
(1, 0, 0, 1, 0, 0, 1, 0, '', 'Головна', '', 'golovna', 'golovna', '00eb099a6cb6877236b7c6b1184e6bc3', '', 'Главная', '', 'glavnaya', '', '', '', 'holovna', '', 'holovna', 'holovna', '78a7a9af2f1ac44fbc028d5d23d3149b', '', '', '', '', '', '', '', '', '', '', 0),
(2, 0, 0, 1, 0, 0, 0, 0, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 1423813381);

-- --------------------------------------------------------

--
-- Структура таблиці `ko_news`
--

CREATE TABLE IF NOT EXISTS `ko_news` (
  `news_id` int(6) unsigned NOT NULL AUTO_INCREMENT,
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
  `update` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`news_id`),
  KEY `component_id` (`component_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15 ;

--
-- Дамп даних таблиці `ko_news`
--

INSERT INTO `ko_news` (`news_id`, `menu_id`, `component_id`, `hidden`, `position`, `date`, `title_ua`, `title_ru`, `title_en`, `title_pl`, `url_ua`, `url_ru`, `url_en`, `url_pl`, `text_ua`, `text_ru`, `text_en`, `text_pl`, `anons_ua`, `anons_ru`, `anons_en`, `anons_pl`, `price_start_eur`, `price_end_eur`, `price_start_usd`, `price_end_usd`, `update`) VALUES
(3, 1, 4, 0, 1, 1442579208, 'Назва товару:', '', '', '', 'nazva-tovaru', '', '', '', '<table border=\\"1\\" cellpadding=\\"1\\" cellspacing=\\"1\\" style=\\"width:980px;\\"><tbody><tr><td>ТОВАР</td><td>ОПИС</td><td>АРТИКЛЬ</td><td>ЦІНА</td></tr><tr><td><img alt=\\"\\" src=\\"/upload/images/photo_1.jpg\\" style=\\"width: 200px; height: 223px;\\" /></td><td>Cтрічкова пила Wood-Mizer</td><td>546-5456-4556</td><td class=\\"table_price\\">5 500</td></tr></tbody></table><p>&nbsp;</p>', '', '', '', 'Анонс товару:', '', '', '', 100, 150, 0, 0, 1442573384),
(4, 1, 4, 0, 0, 1443178452, 'Wood-Mizer', '', '', '', 'wood-mizer', '', '', '', '<table border=\\"1\\" cellpadding=\\"1\\" cellspacing=\\"1\\" style=\\"width:600px;\\"><thead><tr><th>Назва</th><th>Код</th><th>Профіль</th><th>Товщина, ширина, мм.</th><th>Ціна</th></tr></thead><tbody><tr><td><p style=\\"text-align: center;\\"><img alt=\\"\\" src=\\"/upload/images/Wood-Mizer/silvertip.jpg\\" style=\\"width: 176px; height: 41px;\\" /></p><p style=\\"text-align: center;\\">Пили ждя вторинного розпилу деревини</p></td><td>ST 35*1,00*1030SS<br />ST 35*1,00*1030HSS<br />ST 40*1,00*1030SS<br />ST 40*1,00*1030HSS<br />ST 50*1,00*1030SS<br />ST 50*1,00*1030HSS<br />ST 50*1,07*1030HSS<br />ST 35*1,07*1030HSS</td><td>10/30<br />10/30<br />10/30<br />10/30<br />10/30<br />10/30<br />10/30<br />10/30<br /></td><td class=\\"table_price\\">35*1,00<br />35*1,00<br />40*1,00<br />40*1,00<br />50*1,00<br />50*1,00<br />50*1,07<br />35*1,07<br /></td><td class=\\"table_price\\">2,05 Є<br />2,20 Є<br />2,40 Є<br />2,50 Є<br />2,75 Є<br />2,80 Є<br />3,38 Є<br />2,75 Є<br /></td></tr><tr><td><img alt=\\"\\" src=\\"/upload/images/photo_1.jpg\\" style=\\"width: 47px; height: 52px;\\" /></td><td>Cт1річкова пила Wood-Mizer</td><td>546-5456-4556</td><td class=\\"table_price\\">5 500</td><td class=\\"table_price\\">&nbsp;</td></tr><tr><td><img alt=\\"\\" src=\\"/upload/images/photo_1.jpg\\" style=\\"width: 47px; height: 52px;\\" /></td><td>Cт1річкова пила Wood-Mizer</td><td>546-5456-4556</td><td class=\\"table_price\\">5 500</td><td class=\\"table_price\\">&nbsp;</td></tr><tr><td><img alt=\\"\\" src=\\"/upload/images/photo_1.jpg\\" style=\\"width: 47px; height: 52px;\\" /></td><td>Cт1річкова пила Wood-Mizer</td><td>546-5456-4556</td><td class=\\"table_price\\">5 500</td><td class=\\"table_price\\">&nbsp;</td></tr><tr><td><img alt=\\"\\" src=\\"/upload/images/photo_1.jpg\\" style=\\"width: 47px; height: 52px;\\" /></td><td>Cт1річкова пила Wood-Mizer</td><td>546-5456-4556</td><td class=\\"table_price\\">5 500</td><td class=\\"table_price\\">&nbsp;</td></tr></tbody></table><p>&nbsp;</p>', '', '', '', 'Стрічкові пили', '', '', '', 250, 300, 0, 0, 1442582200),
(5, 1, 4, 0, 2, 1443611932, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 0, 0, 0, 0, 1443611864),
(6, 1, 4, 0, 3, 1443611978, 'кцк', 'кцк', '', '', 'ktsk', 'ktsk', '', '', '', '', '', '', 'цкц', 'цкц', '', '', 0, 0, 0, 0, 1443611945),
(7, 1, 31, 0, 0, 1454794495, 'Генеральне прибирання', '', '', '', 'generalne-prybyrannya', NULL, '', '', '<p>Провести <strong>генеральне <a href=\\"http://chystaoselya.com\\" title=\\"Прибирання квартир Львів\\">прибирання&nbsp;квартири</a></strong>&nbsp;чи будинку у Львові &ndash; заняття непросте. Одразу потрібно братися за кілька завдань &ndash; чистити меблі, підлоги, стіни, вікна, сантехніку, кухню, килими, люстри. За один день з цим впоратися неможливо. Та ще й треба достатньо сил, щоб стовідсотково вивести пляму від пасти, наліт на крані й вимити кожне вікно. Можемо впевнено стверджувати, що Вам не вистачить навіть вихідних, щоб повністю прибрати звичайну квартиру, що вже й казати про власний будинок. &laquo;<a href=\\"http://chystaoselya.com\\" title=\\"Клінінгова компанія &quot;Чиста Оселя&quot;\\">Чиста Оселя</a>&raquo; готова допомогти Вам у цій справі. Команда професіоналів за кілька годин або за один день повноцінно проведе генеральне прибирання квартири. Отримаєте ідеально чисте приміщення та збережете дорогоцінний час. Перед початком прибирання ми уточнимо з Вами всі деталі, щоб жоден куточок або предмет не залишився неочищеним.</p><p>Ваше житло стане ідеалом чистоти та порядку, а настрій миттєво поліпшиться! Чекаємо Ваших дзвінків уже сьогодні!</p><p style=\\"text-align: center;\\"><strong>Генеральне прибирання у виконанні нашої компанії</strong></p><table align=\\"center\\" border=\\"0\\" cellpadding=\\"5\\" cellspacing=\\"0\\"><tbody><tr><td><ol style=\\"font-size: 13px; line-height: 20.8px;\\"><li>Очищення стін, меблів, розеток та освітлювальних приладів від пилюки.</li><li>Чищення й полірування фурнітури.</li><li>Миття люстр, бра та інших освітлювальних приладів.</li><li>Миття підвіконників, відкосів та відливів.</li><li>Чищення підлоги та килимового покриття порохотягом.</li><li>Вологе прибирання твердої та водостійкої підлоги.</li><li>Миття дверей, наличників, плинтусів.</li><li>Миття скляних і дзеркальних поверхонь.</li><li>Прибирання кухні та кімнат, призначених для харчування.</li><li><span style=\\"line-height: 1.6em;\\">Миття та дезінфекція ванних кімнат, душових кабін, туалетів.</span></li></ol></td><td style=\\"text-align: center; border-color: rgb(0, 0, 0);\\"><p>&nbsp;</p><table align=\\"center\\" border=\\"1\\" cellpadding=\\"5\\" cellspacing=\\"0\\" dir=\\"ltr\\" style=\\"width:450px;\\"><tbody><tr><td style=\\"text-align: center; border-color: rgb(0, 0, 0);\\"><span style=\\"font-size: 13px; line-height: 20.8px; text-align: center;\\">Вид послуги</span></td><td style=\\"text-align: center; border-color: rgb(0, 0, 0);\\"><span style=\\"font-size: 13px; line-height: 20.8px; text-align: center;\\">Ціна за м</span><sup style=\\"line-height: 20.8px; text-align: center;\\">2</sup></td><td style=\\"text-align: center; border-color: rgb(0, 0, 0);\\"><span style=\\"font-size: 13px; line-height: 20.8px; text-align: center;\\">Вартість, грн</span></td></tr><tr><td style=\\"border-color: rgb(0, 0, 0); text-align: left;\\"><span style=\\"font-size: 13px; line-height: 20.8px;\\">Однокімнатні квартири <strong>до 30 м2</strong></span></td><td style=\\"text-align: center; border-color: rgb(0, 0, 0);\\"><span style=\\"font-size: 13px; line-height: 20.8px;\\">18</span></td><td style=\\"text-align: center; border-color: rgb(0, 0, 0);\\"><span style=\\"font-size: 13px; line-height: 20.8px;\\">500-600</span></td></tr><tr><td style=\\"border-color: rgb(0, 0, 0); text-align: left;\\"><span style=\\"font-size: 13px; line-height: 20.8px;\\">Одно/двокімнатні квартири <strong>30-50 м2</strong></span></td><td style=\\"text-align: center; border-color: rgb(0, 0, 0);\\">17</td><td style=\\"text-align: center; border-color: rgb(0, 0, 0);\\">600-800</td></tr><tr><td style=\\"border-color: rgb(0, 0, 0); text-align: left;\\"><span style=\\"font-size: 13px; line-height: 20.8px;\\">Трикімнатні квартири <strong>50-70 м2</strong></span></td><td style=\\"text-align: center; border-color: rgb(0, 0, 0);\\">15</td><td style=\\"text-align: center; border-color: rgb(0, 0, 0);\\">800-1000</td></tr><tr><td style=\\"border-color: rgb(0, 0, 0); text-align: left;\\"><span style=\\"font-size: 13px; line-height: 20.8px;\\">Чотирикімнатні квартири <strong>70-100 м2</strong></span></td><td style=\\"text-align: center; border-color: rgb(0, 0, 0);\\">14</td><td style=\\"text-align: center; border-color: rgb(0, 0, 0);\\">1000-1400</td></tr><tr><td style=\\"border-color: rgb(0, 0, 0); text-align: left;\\"><span style=\\"font-size: 13px; line-height: 20.8px;\\">Багатокімнатні квартири понад <strong>100 м2</strong></span></td><td style=\\"text-align: center; border-color: rgb(0, 0, 0);\\">13</td><td style=\\"text-align: center; border-color: rgb(0, 0, 0);\\"><span style=\\"font-size: 13px; line-height: 20.8px;\\">договірна</span></td></tr></tbody></table>​</td></tr></tbody></table><p>&nbsp;</p>', '', '', '', '<p>Команда професіоналів за кілька годин або за один день повноцінно проведе генеральне прибирання квартири.</p>\n', '', '', '', 0, 0, 0, 0, 1447240326),
(8, 1, 31, 0, 1, 1454794492, 'Щотижневе прибирання', '', '', '', 'schotyjneve-prybyrannya', NULL, '', '', '<p>Від якісного прибирання залежить атмосфера у домі та здоров&#39;я ваших близьких. Робота, яку виконує наша пан &quot;<a href=\\"http://chystaoselya.com\\" title=\\"Прибиральниця Львів\\">Прибиральниця Львів</a>&quot;, приємно здивує Вас оперативністю, старанністю й охайністю.</p><p>Чистота&nbsp;&ndash; запорука здоров&#39;я та гарного настрою усіх, хто мешкає у вашому домі. Чиста оселя &ndash; здорова родина!</p><p style=\\"text-align: center;\\"><strong>Постійне прибирання для підтримання чистоти</strong></p><table align=\\"center\\" border=\\"0\\" cellpadding=\\"2\\" cellspacing=\\"0\\"><tbody><tr><td><ol style=\\"font-size: 13px; line-height: 20.8px;\\"><li>Очищення підлоги, килимового покриття та миття м&#39;яких меблів порохотягом.</li><li>Вологе прибирання твердої та водостійкої підлоги.</li><li>Знежирення робочої поверхні на кухні.</li><li>Дезінфекція санвузла.</li><li>Миття дверей, наличників, плинтусів.</li><li>Видалення пилу з фасадів корпусних меблів.</li></ol></td><td><table border=\\"1\\" cellpadding=\\"5\\" cellspacing=\\"0\\" dir=\\"ltr\\" style=\\"width:500px;\\"><tbody><tr><td 30=\\"\\" data-sheets-value=\\"[null,2,\\" u0438=\\"\\" u043e=\\"\\" u0456=\\"\\">Вид послуги</td><td data-sheets-value=\\"[null,3,null,18]\\">Ціна за м2</td><td data-sheets-value=\\"[null,2,\\">Вартість, грн</td></tr><tr><td 30=\\"\\" data-sheets-value=\\"[null,2,\\" u0438=\\"\\" u043e=\\"\\" u0456=\\"\\">Однокімнатні квартири <strong>до 30 м2</strong></td><td data-sheets-value=\\"[null,3,null,18]\\" style=\\"text-align: center;\\">18</td><td data-sheets-value=\\"[null,2,\\" style=\\"text-align: center;\\">500-600</td></tr><tr><td 30-50=\\"\\" data-sheets-value=\\"[null,2,\\" u0438=\\"\\" u043e=\\"\\" u0456=\\"\\">Одно/двокімнатні квартири <strong>30-50 м2</strong></td><td data-sheets-value=\\"[null,3,null,17]\\" style=\\"text-align: center;\\">17</td><td data-sheets-value=\\"[null,2,\\" style=\\"text-align: center;\\">600-800</td></tr><tr><td 50-70=\\"\\" data-sheets-value=\\"[null,2,\\" u0438=\\"\\" u0456=\\"\\">Трикімнатні квартири <strong>50-70 м2</strong></td><td data-sheets-value=\\"[null,3,null,15]\\" style=\\"text-align: center;\\">15</td><td data-sheets-value=\\"[null,2,\\" style=\\"text-align: center;\\">800-1000</td></tr><tr><td 70-100=\\"\\" data-sheets-value=\\"[null,2,\\" u0438=\\"\\" u0456=\\"\\">Чотирикімнатні квартири <strong>70-100 м2</strong></td><td data-sheets-value=\\"[null,3,null,14]\\" style=\\"text-align: center;\\">14</td><td data-sheets-value=\\"[null,2,\\" style=\\"text-align: center;\\">1000-1400</td></tr><tr><td 100=\\"\\" data-sheets-value=\\"[null,2,\\" u0434=\\"\\" u0438=\\"\\" u0456=\\"\\">Багатокімнатні квартири понад <strong>100 м2</strong></td><td data-sheets-value=\\"[null,3,null,13]\\" style=\\"text-align: center;\\">13</td><td data-sheets-value=\\"[null,2,\\" style=\\"text-align: center;\\">договірна</td></tr></tbody></table></td></tr></tbody></table><p>&nbsp;</p>', '', '', '', '<p>Від якісного прибирання залежить атмосфера у домі та здоров&#39;я ваших близьких. Робота, яку виконують наші співробітники, приємно здивує Вас оперативністю, старанністю й охайністю.</p>\n', '', '', '', 0, 0, 0, 0, 1447749204),
(9, 1, 31, 0, 3, 1454794489, 'Прибирання котеджів та заміських будинків', '', '', '', 'prybyrannya-kotedjiv-ta-zamiskyh-budynkiv', NULL, '', '', '<p>Від якісного прибирання залежить атмосфера у домі й здоров&#39;я Ваших близьких. Робота, яку виконують наші співробітники, приємно здивує Вас оперативністю, старанністю й охайністю.</p><p><a href=\\"http://CHYSTAOSELYA.COM\\" title=\\"Клінінгова компанія Львів\\">Чиста оселя</a> &ndash; здорова родина!</p><p style=\\"text-align: center;\\"><strong>Прибирання котеджів та заміських будинків</strong></p><table align=\\"center\\" border=\\"0\\" cellpadding=\\"2\\" cellspacing=\\"0\\"><tbody><tr><td><ol style=\\"font-size: 13px; line-height: 20.8px;\\"><li>Очищення стін, меблів, розеток та освітлювальних приладів від пилюки.</li><li>Чищення й полірування фурнітури.</li><li>Миття люстр, бра та інших освітлювальних приладів.</li><li>Миття підвіконників, відкосів та відливів.</li><li>Чищення підлоги та килимового покриття порохотягом.</li><li>Вологе прибирання твердої та водостійкої підлоги.</li><li>Миття дверей, наличників, плинтусів.</li><li>Миття скляних і дзеркальних поверхонь.</li><li>Прибирання кухні та кімнат, призначених для харчування.</li><li>Миття та дезінфекція ванних кімнат, душових кабін, туалетів.</li></ol></td><td><p><strong>Ціна договірна</strong></p><p>Виїзд менеджера до клієнта та оцінка об&rsquo;єкта в межах міста &ndash; БЕЗКОШТОВНО!!!</p></td></tr></tbody></table>', '', '', '', '<p>Робота, яку виконують наші співробітники, приємно здивує Вас оперативністю, старанністю й охайністю.</p>\n', '', '', '', 0, 0, 0, 0, 1447949900),
(10, 1, 31, 0, 4, 1454794487, 'Прибирання комерційної нерухомості', '', '', '', 'prybyrannya-komertsiyno-neruhomosti', NULL, '', '', '<p>Ви власник великої компанії, що успішно розвивається? Ваш офіс &ndash; найбільш населене робоче місце в місті? Ваш готель, клуб, банк, магазин&nbsp; повинен бути раєм чистоти і затишку? Ми маємо, що Вам запропонувати. Щоденна чистота, своєчасне та якісне прибирання, приємний запах і блиск підлоги &ndash; все це забезпечимо ми<span style=\\"font-size: 13px; line-height: 20.8px;\\">&nbsp;&ndash; <a href=\\"http://chystaoselya.com\\" title=\\"Клінінгова компанія у Львові\\">клінінгова компанія</a> Чиста Оселя та&nbsp;</span>наші працівники. Позитивний настрій персоналу та клієнтів за таких умов гарантований.</p><p>&nbsp;Клінінгова компанія &laquo;Чиста Оселя&raquo; вирішить питання чистоти у вашому офісі.</p><p>&nbsp;</p><p style=\\"text-align: center;\\"><strong>Прибирання комерційної нерухомості</strong></p><table align=\\"center\\" border=\\"0\\" cellpadding=\\"2\\" cellspacing=\\"0\\"><tbody><tr><td><ol style=\\"font-size: 13px; line-height: 20.8px;\\"><li>Очищення підлоги, килимового покриття та миття м&#39;яких меблів порохотягом.</li><li>Вологе прибирання твердої та водостійкої підлоги.</li><li>Дезінфекція санвузла.</li><li>Миття дверей, наличників, плинтусів.</li><li>Видалення пилу з фасадів корпусних меблів.</li></ol></td><td><p style=\\"font-size: 13px; line-height: 20.8px;\\"><strong>Ціна договірна</strong></p><p style=\\"font-size: 13px; line-height: 20.8px;\\">Виїзд менеджера до клієнта та оцінка об&rsquo;єкта в межах міста &ndash; БЕЗКОШТОВНО!!!</p></td></tr></tbody></table>', '', '', '', '<p>Ваш офіс, готель, клуб, банк, магазин&nbsp; повинен бути раєм чистоти і затишку? Ми маємо, що Вам запропонувати.</p>\n', '', '', '', 0, 0, 0, 0, 1447949926),
(11, 1, 31, 0, 2, 1454794491, 'Прибирання після ремонту та будівництва', '', '', '', 'prybyrannya-pislya-remontu-ta-budivnytstva', NULL, '', '', '<p>Більшість людей хоча би раз в житті прибирали після ремонту або допомагали в цьому. Ми впевнені, що вони запам&#39;ятали той час, коли важка фізична праця, пов&rsquo;язана зі зібранням і винесенням сміття, миттям і витиранням, розставлянням усіх меблів і речей на місця, просто валила з ніг. Ба більше, таке <a href=\\"http://chystaoselya.com\\" title=\\"Прибирання Львів\\">прибирання Львів</a> викликало безліч незручностей, зокрема несвоєчасний прийом їжі, неможливість скористатися душем, зайнятися чимось іншим, необхідність оперативної роботи через те, що насувалися робочі будні. Ми можемо істотно полегшити цей процес, ставши відповідальними за чистоту та новий вигляд Вашого приміщення, і довести, що ремонт може бути приємним процесом!</p><p style=\\"text-align: center;\\"><strong style=\\"font-size: 13px; line-height: 20.8px;\\">Прибирання після ремонту та будівництва</strong></p><table align=\\"center\\" border=\\"0\\" cellpadding=\\"2\\" cellspacing=\\"0\\"><tbody><tr><td><ol><li>Прибирання дрібного будівельного сміття (уламків цегли, будматеріалів).</li><li>Видалення стійких забруднень зі скляних поверхонь та дзеркал.</li><li>Видалення будівельного пилу з поверхні підлоги, стін, комунікацій.</li><li>Вологе прибирання водостійких поверхонь.</li><li>Миття люстр, бра та інших освітлювальних приладів.</li><li>Миття дверей, наличників, плинтусів.<br />Прибирання кухні та кімнат, призначених для харчування.</li><li>Миття та дезінфекція ванних кімнат, душових кабін, туалетів.</li><li>Вологе прибирання водостійких поверхонь.</li></ol></td><td><table border=\\"1\\" cellpadding=\\"5\\" cellspacing=\\"0\\" dir=\\"ltr\\" style=\\"width:300px;\\"><tbody><tr><td 100=\\"\\" data-sheets-value=\\"[null,2,\\" style=\\"text-align: center;\\" u043e=\\"\\">Вид послуг</td><td 100=\\"\\" data-sheets-value=\\"[null,2,\\" style=\\"text-align: center;\\" u043e=\\"\\">Ціна за м2</td></tr><tr><td 100=\\"\\" data-sheets-value=\\"[null,2,\\" style=\\"text-align: center;\\" u043e=\\"\\"><strong>до 100 м2</strong></td><td 100=\\"\\" data-sheets-value=\\"[null,2,\\" style=\\"text-align: center;\\" u043e=\\"\\">&nbsp;18</td></tr><tr><td 200=\\"\\" data-sheets-value=\\"[null,2,\\" style=\\"text-align: center;\\" u043e=\\"\\"><strong>до 200 м2</strong></td><td data-sheets-value=\\"[null,3,null,11]\\" style=\\"text-align: center;\\">15</td></tr><tr><td 500=\\"\\" data-sheets-value=\\"[null,2,\\" style=\\"text-align: center;\\" u043e=\\"\\"><strong>до 500 м2</strong></td><td data-sheets-value=\\"[null,3,null,10]\\" style=\\"text-align: center;\\">10</td></tr><tr><td 2000=\\"\\" data-sheets-value=\\"[null,2,\\" style=\\"text-align: center;\\" u043e=\\"\\"><strong>до 2000 м2</strong></td><td data-sheets-value=\\"[null,3,null,9]\\" style=\\"text-align: center;\\">договірна</td></tr><tr><td 2000=\\"\\" data-sheets-value=\\"[null,2,\\" style=\\"text-align: center;\\" u0434=\\"\\"><strong>понад 2000 м2</strong></td><td data-sheets-value=\\"[null,2,\\" style=\\"text-align: center;\\">договірна</td></tr></tbody></table></td></tr></tbody></table>', '', '', '', '<p>Станемо відповідальними за чистоту та новий вигляд Вашого приміщення, і довести, що ремонт може бути приємним процесом!</p>\n', '', '', '', 0, 0, 0, 0, 1447949947),
(12, 1, 31, 0, 5, 1454795764, 'Миття вікон, фасадів, вітрин', '', '', '', 'myttya-vikon-fasadiv-vitryn', NULL, '', '', '<p>При прибирання будь-якого приміщення (<span style=\\"font-size: 13px; line-height: 20.8px;\\">квартира,&nbsp;</span><span style=\\"font-size: 13px; line-height: 20.8px;\\">офіс,&nbsp;</span><span style=\\"font-size: 13px; line-height: 20.8px;\\">магазин тощо</span>) обов&rsquo;язковою складовою є миття фасадів, віон та вітрин. У квартирі чистота вікон відіграє дуже важливу роль: Ви дивитеся через Ваші вікна на світ, а тому вони просто мусять бути чистими! Побачити блискуче майбутнє можна тільки крізь чисту вітрину. Насолодитися небом можна тільки через скло без патьоків. Захопитися виглядом будівлі можна тільки тоді, коли її фасад чистий. Ті, хто надає перевагу скляним конструкціям, повинні пам&#39;ятати ці правила. Ті, хто їх пам&#39;ятають, користуються нашими <a href=\\"http://chystaoselya.com\\" title=\\"клінінгові послуги Львів\\">клінінговими послугами</a>.</p><p>&nbsp;</p><p style=\\"text-align: center;\\"><strong>Миття вікон, фасадів, вітрин</strong></p><table align=\\"center\\" border=\\"0\\" cellpadding=\\"2\\" cellspacing=\\"0\\"><tbody><tr><td><ol style=\\"font-size: 13px; line-height: 20.8px;\\"><li>Сухе чищення, очищення від пилюки, вологе миття окремих частин із застосуванням спеціальної хімії.</li><li>Полірування поверхні для надання блиску.</li></ol></td><td><table border=\\"1\\" cellpadding=\\"5\\" cellspacing=\\"0\\" dir=\\"ltr\\" style=\\"width:550px;\\"><tbody><tr><td data-sheets-value=\\"[null,2,\\" style=\\"text-align: center;\\" u0435=\\"\\" u043d=\\"\\" u0443=\\"\\" u044f=\\"\\">Вид послуг</td><td data-sheets-value=\\"[null,2,\\" style=\\"text-align: center;\\" u0434=\\"\\">Ціна за м2</td><td 50=\\"\\" data-sheets-value=\\"[null,2,\\" style=\\"text-align: center;\\" u043e=\\"\\">Площа, м2</td></tr><tr><td data-sheets-value=\\"[null,2,\\" style=\\"text-align: center;\\" u0435=\\"\\" u043d=\\"\\" u0443=\\"\\" u044f=\\"\\">Звичайне миття вікон у квартирі</td><td data-sheets-value=\\"[null,2,\\" style=\\"text-align: center;\\" u0434=\\"\\">від 10</td><td 50=\\"\\" data-sheets-value=\\"[null,2,\\" style=\\"text-align: center;\\" u043e=\\"\\">до 50 м2</td></tr><tr><td data-sheets-value=\\"[null,2,\\" style=\\"text-align: center;\\" u0445=\\"\\" u044f=\\"\\">Миття великогабаритних вікон</td><td data-sheets-value=\\"[null,2,\\" style=\\"text-align: center;\\" u0434=\\"\\">від 8</td><td 100=\\"\\" data-sheets-value=\\"[null,2,\\" style=\\"text-align: center;\\" u043e=\\"\\">до 100 м2</td></tr><tr><td data-sheets-value=\\"[null,2,\\" style=\\"text-align: center;\\" u0434=\\"\\" u043d=\\"\\" u044e=\\"\\" u044f=\\"\\">Миття вікон висотою понад 3м</td><td data-sheets-value=\\"[null,2,\\" style=\\"text-align: center;\\" u0434=\\"\\">від 12</td><td 100=\\"\\" data-sheets-value=\\"[null,2,\\" style=\\"text-align: center;\\" u043e=\\"\\">до 100 м2</td></tr><tr><td data-sheets-value=\\"[null,2,\\" style=\\"text-align: center;\\" u043d=\\"\\" u044f=\\"\\">Миття вікон після ремонту</td><td 12=\\"\\" data-sheets-numberformat=\\"[null,2,\\" data-sheets-value=\\"[null,2,\\" u0434=\\"\\" u043e=\\"\\">від 12 до 30</td><td 50=\\"\\" data-sheets-value=\\"[null,2,\\" style=\\"text-align: center;\\" u043e=\\"\\">до 50 м2</td></tr><tr><td data-sheets-value=\\"[null,2,\\" style=\\"text-align: center;\\" u0437=\\"\\" u043d=\\"\\" u0445=\\"\\" u044e=\\"\\" u044f=\\"\\">Миття фасадних вікон з допомогою автовишки</td><td colspan=\\"2\\" data-sheets-value=\\"[null,2,\\" rowspan=\\"1\\" style=\\"text-align: center;\\">договірна</td></tr><tr><td data-sheets-value=\\"[null,2,\\" style=\\"text-align: center;\\" u0437=\\"\\" u043d=\\"\\" u044e=\\"\\" u044f=\\"\\">Миття вікон з допомогою альпіністів</td><td colspan=\\"2\\" data-sheets-value=\\"[null,2,\\" rowspan=\\"1\\" style=\\"text-align: center;\\">договірна</td></tr></tbody></table></td></tr></tbody></table><p>&nbsp;</p>', '', '', '', '<p><span style=&quot;font-size: 13px; line-height: 20.8px;&quot;>При прибирання будь-якого приміщення&nbsp;</span><span style=&quot;font-size: 13px; line-height: 20.8px;&quot;>незамінною складовою є миття фасадів, віон та вітрин</span><span style=&quot;font-size: 13px; line-height: 20.8px;&quot;>.</span></p>\n', '', '', '', 0, 0, 0, 0, 1447949972),
(13, 1, 31, 0, 7, 1469270922, 'Хімчистка', '', '', '', 'himchystka', NULL, '', '', '<p>Меблі та килими в будинку, які давно ніхто не чистив, вбирають в себе пилюку та бруд, погіршуючи якість повітря, що часто стає причиною загострення хронічних захворювань, знижує імунітет і провокує алергічні, ба навіть астматичні, реакції. Особливо це небезпечно, якщо в будинку є діти. Регулярне повноцінне чищення килимів і килимових покриттів, а також меблів &ndash; основа чистоти та затишку у Вашому будинку.</p><p>Під час <a href=\\"http://chystaoselya.com\\" title=\\"Прибирання квартир Львів\\">прибирання квартир&nbsp;у Львові</a> м&#39;які меблі не витримують вологи, пилюки, частого прибирання жорсткими щітками, постійної трансформації. Згодом вони втрачають той розкішний вигляд і структуру, в які Ви закохалися, здійснюючи покупку. Повернути колишній стан частково або повністю завжди можна, скориставшись такою послугою як хімчистка килимів та м&#39;яких меблів вдома, в квартирі, в офісі.</p><p>Брудний ковролін в офісному приміщені справляє гнітюче враження в очах потенційного клієнта або партнера. Будучи джерелом бруду та бактерій, килимове покриття стає причиною зниження працездатності колективу й ефективності роботи всієї компанії. Чистий килим або диван в офісі &ndash; деталь інтер&#39;єру, яка набагато важливіша, ніж домашня атмосфера.</p><table align=\\"center\\" border=\\"1\\" cellpadding=\\"5\\" cellspacing=\\"0\\" style=\\"width:500px;\\"><tbody><tr><td style=\\"text-align: center;\\"><strong>Вид послуги</strong></td><td style=\\"text-align: center;\\"><strong>Обсяг</strong></td><td style=\\"text-align: center;\\"><strong>Вартість</strong></td></tr><tr><td colspan=\\"3\\" style=\\"text-align: center;\\"><strong>Хімчистка килимових покриттів</strong></td></tr><tr><td><span style=\\"line-height: 20.8px;\\">Хімчистка ковроліну</span></td><td><span style=\\"line-height: 20.8px;\\">1 м2</span></td><td><span style=\\"line-height: 20.8px;\\">14 грн</span></td></tr><tr><td colspan=\\"3\\" style=\\"text-align: center;\\"><strong>Хімчистка м&rsquo;яких меблів</strong></td></tr><tr><td><span style=\\"line-height: 20.8px;\\">Диван-книжка</span></td><td><span style=\\"line-height: 20.8px;\\">3 місця</span></td><td><span style=\\"line-height: 20.8px;\\">150-200 грн</span></td></tr><tr><td><span style=\\"line-height: 20.8px;\\">Диван кутовий</span></td><td><span style=\\"line-height: 20.8px;\\">5 місць</span></td><td><span style=\\"line-height: 20.8px;\\">250-300 грн</span></td></tr><tr><td><span style=\\"line-height: 20.8px;\\">Крісло (диванний комплект)</span></td><td><span style=\\"line-height: 20.8px;\\">1 шт.</span></td><td><span style=\\"line-height: 20.8px;\\">100 грн</span></td></tr><tr><td><span style=\\"line-height: 20.8px;\\">Стілець</span></td><td><span style=\\"line-height: 20.8px;\\">1 шт.</span></td><td><span style=\\"line-height: 20.8px;\\">20 грн</span></td></tr><tr><td><span style=\\"line-height: 20.8px;\\">Матрац</span></td><td><span style=\\"line-height: 20.8px;\\">1 шт.</span></td><td><span style=\\"line-height: 20.8px;\\">150-200 грн</span></td></tr></tbody></table>', '', '', '', '<p>Регулярне повноцінне чищення килимів і килимових покриттів, а також меблів &ndash; основа чистоти та затишку у Вашому будинку.</p>\n', '', '', '', 0, 0, 0, 0, 1454574640),
(14, 1, 31, 0, 6, 1454583615, '', '', '', '', '', NULL, '', '', '', '', '', '', '', '', '', '', 0, 0, 0, 0, 1454583615);

-- --------------------------------------------------------

--
-- Структура таблиці `ko_news_images`
--

CREATE TABLE IF NOT EXISTS `ko_news_images` (
  `image_id` int(6) unsigned NOT NULL AUTO_INCREMENT,
  `menu_id` int(6) unsigned NOT NULL,
  `component_id` int(6) unsigned NOT NULL,
  `news_id` int(6) unsigned NOT NULL,
  `position` int(4) unsigned NOT NULL DEFAULT '0',
  `file_name` varchar(255) NOT NULL,
  `watermark_position` int(2) NOT NULL,
  PRIMARY KEY (`image_id`),
  KEY `news_id` (`news_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=160 ;

--
-- Дамп даних таблиці `ko_news_images`
--

INSERT INTO `ko_news_images` (`image_id`, `menu_id`, `component_id`, `news_id`, `position`, `file_name`, `watermark_position`) VALUES
(147, 1, 4, 3, 1, 'photo_1.jpg', 0),
(148, 1, 4, 4, 1, 'photo_1.jpg', 0),
(149, 1, 4, 5, 1, 'bandsaw_blades.jpg', 0),
(150, 1, 4, 6, 1, '1306361038.jpg', 0),
(152, 1, 31, 7, 1, 'img_1.png', 0),
(153, 1, 31, 8, 1, 'img_2.png', 0),
(154, 1, 31, 9, 1, 'img_3.png', 0),
(155, 1, 31, 10, 1, 'img_4.png', 0),
(156, 1, 31, 11, 1, 'img_5.png', 0),
(157, 1, 31, 12, 1, 'img_6.png', 0),
(158, 1, 31, 13, 1, 'chystaoselya_ko_1.png', 0),
(159, 1, 31, 13, 0, 'chystaoselya_ko_2.png', 0);

-- --------------------------------------------------------

--
-- Структура таблиці `ko_reviews`
--

CREATE TABLE IF NOT EXISTS `ko_reviews` (
  `review_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
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
  `logo` varchar(255) NOT NULL,
  PRIMARY KEY (`review_id`),
  KEY `component_id` (`component_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Структура таблиці `ko_seo_link`
--

CREATE TABLE IF NOT EXISTS `ko_seo_link` (
  `menu_id` int(6) unsigned NOT NULL,
  `hide_items` text NOT NULL,
  PRIMARY KEY (`menu_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблиці `ko_seo_tags`
--

CREATE TABLE IF NOT EXISTS `ko_seo_tags` (
  `tags_id` int(6) unsigned NOT NULL AUTO_INCREMENT,
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
  `cache_pl` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`tags_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1382 ;

--
-- Дамп даних таблиці `ko_seo_tags`
--

INSERT INTO `ko_seo_tags` (`tags_id`, `item_id`, `component_id`, `menu_id`, `module`, `type_ua`, `title_ua`, `description_ua`, `keywords_ua`, `cache_title_ua`, `cache_description_ua`, `cache_keywords_ua`, `cache_ua`, `type_ru`, `title_ru`, `description_ru`, `keywords_ru`, `cache_title_ru`, `cache_description_ru`, `cache_keywords_ru`, `cache_ru`, `type_en`, `title_en`, `description_en`, `keywords_en`, `cache_title_en`, `cache_description_en`, `cache_keywords_en`, `cache_en`, `type_pl`, `title_pl`, `description_pl`, `keywords_pl`, `cache_title_pl`, `cache_description_pl`, `cache_keywords_pl`, `cache_pl`) VALUES
(1372, 0, 0, 1, '', 1, 'Прибирання Львів | Прибирання квартир та офісів | Чиста оселя', 'Клінінгова компанія &quot;Чиста Оселя&quot; пропонує: прибирання квартир, офісів, котеджів, прибиральниця Львів, генеральне прибирання, прибирання після ремонту, хімчистка, миття вікон та фасадів.', 'Прибирання Львів,Прибирання квартир Львів,прибиральниця Львів,Клінінгова компанія Львів', 'Про нас', 'Про нас Клінінгова компанія &quot;Чиста Оселя&quot; надає послуги професійного прибирання у м. Львів: прибирання після ремонту, генеральне прибирання, щоденне прибирання: квартир, будинків, бізнес центрів, офісів та складів &ndash; миття вікон, хімчистка &amp; аквачистка доріжок, килимів, ковроліну та м&rsquo;яких меблів (диванів, матрасів,', 'прибирання, послуги, часу, області, львова, професійне', 0, 0, '', '', '', '', '  ', '', 1, 0, '', '', '', '', '', '', 0, 0, '', '', '', '', '', '', 0),
(1373, 7, 31, 1, 'news', 0, '', '', '', '', '', '', 0, 0, '', '', '', '', '', '', 0, 0, '', '', '', '', '', '', 0, 0, '', '', '', '', '', '', 0),
(1374, 8, 31, 1, 'news', 0, '', '', '', '', '', '', 0, 0, '', '', '', '', '', '', 0, 0, '', '', '', '', '', '', 0, 0, '', '', '', '', '', '', 0),
(1375, 9, 31, 1, 'news', 0, '', '', '', '', '', '', 0, 0, '', '', '', '', '', '', 0, 0, '', '', '', '', '', '', 0, 0, '', '', '', '', '', '', 0),
(1376, 10, 31, 1, 'news', 0, '', '', '', '', '', '', 0, 0, '', '', '', '', '', '', 0, 0, '', '', '', '', '', '', 0, 0, '', '', '', '', '', '', 0),
(1377, 11, 31, 1, 'news', 0, '', '', '', '', '', '', 0, 0, '', '', '', '', '', '', 0, 0, '', '', '', '', '', '', 0, 0, '', '', '', '', '', '', 0),
(1378, 12, 31, 1, 'news', 0, '', '', '', '', '', '', 0, 0, '', '', '', '', '', '', 0, 0, '', '', '', '', '', '', 0, 0, '', '', '', '', '', '', 0),
(1379, 13, 31, 1, 'news', 0, '', '', '', '', '', '', 0, 0, '', '', '', '', '', '', 0, 0, '', '', '', '', '', '', 0, 0, '', '', '', '', '', '', 0),
(1380, 14, 31, 1, 'news', 0, '', '', '', '', '', '', 0, 0, '', '', '', '', '', '', 0, 0, '', '', '', '', '', '', 0, 0, '', '', '', '', '', '', 0),
(1381, 0, 0, 0, '', 0, '', '', '', '', '', '', 0, 0, '', '', '', '', '', '', 0, 0, '', '', '', '', '', '', 0, 0, '', '', '', '', '', '', 0);

-- --------------------------------------------------------

--
-- Структура таблиці `ko_site_links`
--

CREATE TABLE IF NOT EXISTS `ko_site_links` (
  `link_id` int(6) unsigned NOT NULL AUTO_INCREMENT,
  `item_id` int(6) unsigned NOT NULL,
  `component_id` int(6) unsigned NOT NULL,
  `menu_id` int(6) unsigned NOT NULL,
  `module` varchar(20) NOT NULL,
  `method` varchar(30) NOT NULL,
  `hash_ua` char(32) NOT NULL,
  `hash_en` char(32) NOT NULL,
  `hash_pl` char(32) NOT NULL,
  `hash_ru` char(32) NOT NULL,
  PRIMARY KEY (`link_id`),
  KEY `hash_ua` (`hash_ua`),
  KEY `item_id` (`item_id`,`module`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- Дамп даних таблиці `ko_site_links`
--

INSERT INTO `ko_site_links` (`link_id`, `item_id`, `component_id`, `menu_id`, `module`, `method`, `hash_ua`, `hash_en`, `hash_pl`, `hash_ru`) VALUES
(1, 7, 31, 1, 'news', 'details', 'e87dc0753d5a6e2fca895850ec712da8', '', '', ''),
(2, 8, 31, 1, 'news', 'details', '941ded570725fe68c793bc86b4737077', '', '', ''),
(3, 9, 31, 1, 'news', 'details', 'c5b37826289c4b9af2ae4071474c791d', '', '', ''),
(4, 10, 31, 1, 'news', 'details', '503fefd95fb63801cf97f006ed01c8ab', '', '', ''),
(5, 11, 31, 1, 'news', 'details', '72b2278f7a2efdd33c0e14135bfada7b', '', '', ''),
(6, 12, 31, 1, 'news', 'details', '4518d09381aa9b29508baa341e48ccd3', '', '', ''),
(7, 13, 31, 1, 'news', 'details', '3a3c15bab8ff2df5ea967210360c0989', '', '', ''),
(8, 14, 31, 1, 'news', 'details', '', '', '', '');

-- --------------------------------------------------------

--
-- Структура таблиці `ko_slider`
--

CREATE TABLE IF NOT EXISTS `ko_slider` (
  `slide_id` int(6) unsigned NOT NULL AUTO_INCREMENT,
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
  `description_en` varchar(255) NOT NULL,
  PRIMARY KEY (`slide_id`),
  KEY `hidden` (`hidden`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=61 ;

--
-- Дамп даних таблиці `ko_slider`
--

INSERT INTO `ko_slider` (`slide_id`, `menu_id`, `position`, `hidden`, `title_ua`, `url_ua`, `file_name_ua`, `title_ru`, `url_ru`, `file_name_ru`, `title_en`, `url_en`, `hidden_ua`, `hidden_ru`, `hidden_en`, `file_name_en`, `description_ua`, `description_ru`, `description_en`) VALUES
(40, 1, 4, 0, 'Орест Євгенович', '', '32142314.jpg', '', '', '', '', '', 0, 0, 0, '', '<p>Прибирають хлопці мені щотижня, бо маю мало вільного часу. Чесно кажучи першого разу думав, що не буду регулярно прибирати квартиру через компанію по прибиранню квартир, але.. Вже понад 3 місяці мою квартиру &quot;доглядає&quot; Чиста Оселя!</p>\n', '', ''),
(39, 1, 3, 0, 'Ірина Миронівна', '', '34654526.jpg', '', '', '', '', '', 0, 0, 0, '', '<p>Працюю адміністратором в ресторані-клубі. Прибирають мені з даної компанії і в ресторані і вдома:)</p>\n', '', ''),
(38, 1, 2, 0, 'Тимофій Петрович', '', '24523432.jpg', '', '', '', '', '', 0, 0, 0, '', '<p>Працюю у сфері подобової оренди квартир. Раніше сам займався питанням прибирання, сам шукав прибиральниць, сам контролював. Вирішив спробувати передати ці функції на сторонню компанію, впринципі все йде нормально, а основне, що я зекономив понад 2 годи', '', ''),
(37, 1, 1, 0, 'Світлана Петрівна', 'йцуйцу', 'czuzztivubc.jpg', '', '', '', '', '', 0, 0, 0, '', '<p>Робили ремонт і як виявилось, вивести сміття це ДУЖЕ непроста задача. Нащастя знайшла в інтернеті Чисту Оселю і вирішила цю проблему да 1 день! Все почистили і кудись повезли. А потім ще й прибрали нам в загальному коридорі, рекомендую цю компанію.</p>', '', '');

-- --------------------------------------------------------

--
-- Структура таблиці `ko_timer`
--

CREATE TABLE IF NOT EXISTS `ko_timer` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `time` int(10) NOT NULL DEFAULT '0',
  `title_ua` varchar(255) NOT NULL,
  `title_ru` varchar(255) NOT NULL,
  `title_en` varchar(255) NOT NULL,
  `title_pl` varchar(255) NOT NULL,
  `sign_ua` varchar(255) NOT NULL,
  `sign_ru` varchar(255) NOT NULL,
  `sign_en` varchar(255) NOT NULL,
  `sign_pl` varchar(255) NOT NULL,
  `photo` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
