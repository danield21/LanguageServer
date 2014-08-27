-- phpMyAdmin SQL Dump
-- version 3.4.11.1deb2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 18, 2014 at 06:12 PM
-- Server version: 5.5.31
-- PHP Version: 5.4.4-14+deb7u5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `language_server`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE IF NOT EXISTS `accounts` (
  `account_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(30) NOT NULL,
  `password` varchar(50) NOT NULL DEFAULT '755d8bc6cc7263c132ff22eb420dc810',
  `first_name` varchar(45) DEFAULT NULL,
  `last_name` varchar(45) DEFAULT NULL,
  `admin` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`account_id`),
  UNIQUE KEY `username_UNIQUE` (`username`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=19 ;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`account_id`, `username`, `password`, `first_name`, `last_name`, `admin`) VALUES
(1, 'administrator', '899498ed77a23bc32dd1eeacc9663f53', 'Test', 'Admin', 2),
(17, 'danield', '8ad326d2f3d853f93a585da3f3eb8d66', 'Daniel', 'Dominguez', 1),
(18, 'ana-m', '1a19bc0868578487abaac5c18f748a70', 'Ana', 'Moreno', 1);

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE IF NOT EXISTS `category` (
  `category_id` int(11) NOT NULL AUTO_INCREMENT,
  `category` varchar(50) NOT NULL,
  `description` varchar(150) NOT NULL DEFAULT '',
  PRIMARY KEY (`category_id`),
  UNIQUE KEY `category_UNIQUE` (`category`),
  UNIQUE KEY `category` (`category`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=26 ;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`category_id`, `category`, `description`) VALUES
(1, 'Alphabet', 'This is the basic building blocks of any language'),
(15, 'La Fruta', ''),
(16, 'Las Verduras', ''),
(17, 'La Comida', ''),
(21, 'Animales', 'Los animales mas comunes'),
(22, 'La escuela', 'Objetos comunes de las escuelas'),
(23, 'Recreación ', 'Actividades Divertidas'),
(24, 'Ropa ', ''),
(25, 'Vivienda', '');

-- --------------------------------------------------------

--
-- Table structure for table `is_in`
--

CREATE TABLE IF NOT EXISTS `is_in` (
  `word_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  KEY `fk_in_category_idx` (`category_id`),
  KEY `fk_is_word_idx` (`word_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `is_in`
--

INSERT INTO `is_in` (`word_id`, `category_id`) VALUES
(1, 1),
(2, 1),
(3, 1),
(4, 1),
(5, 1),
(6, 1),
(7, 1),
(8, 1),
(9, 1),
(10, 1),
(11, 1),
(12, 1),
(13, 1),
(14, 1),
(15, 1),
(16, 1),
(17, 1),
(18, 1),
(19, 1),
(20, 1),
(21, 1),
(22, 1),
(23, 1),
(24, 1),
(25, 1),
(26, 1),
(73, 15),
(75, 16),
(74, 15),
(77, 15),
(79, 16),
(80, 16),
(81, 15),
(82, 16),
(84, 21),
(86, 21),
(87, 21),
(88, 21),
(89, 21),
(90, 21),
(91, 17),
(92, 21),
(93, 21),
(94, 21),
(95, 21),
(96, 21),
(97, 21),
(98, 21),
(99, 21),
(100, 21),
(101, 21),
(102, 21),
(103, 21),
(104, 21),
(105, 21),
(106, 21),
(152, 22),
(153, 22),
(154, 22),
(155, 22),
(156, 22),
(157, 22),
(158, 22),
(159, 22),
(161, 22),
(162, 22),
(163, 22),
(164, 22),
(165, 22),
(166, 22),
(167, 22),
(168, 22),
(169, 23),
(170, 23),
(171, 23),
(172, 23),
(173, 23),
(174, 23),
(175, 23),
(175, 23),
(176, 23),
(177, 23),
(178, 23),
(179, 23),
(180, 23),
(181, 23),
(182, 23),
(183, 24),
(184, 24),
(185, 24),
(186, 24),
(187, 24),
(188, 24),
(189, 24),
(190, 24),
(191, 24),
(191, 24),
(192, 24),
(193, 24),
(194, 24),
(195, 24),
(196, 24),
(197, 24),
(198, 24),
(199, 24),
(199, 24),
(200, 24),
(201, 25),
(202, 25),
(203, 25),
(204, 25),
(205, 25),
(206, 25),
(207, 25),
(208, 25),
(209, 25),
(210, 25),
(211, 25),
(211, 25),
(212, 25),
(213, 25),
(214, 25),
(215, 25),
(216, 25),
(217, 25),
(217, 25),
(217, 25),
(218, 25),
(218, 25),
(219, 25),
(220, 15),
(221, 15),
(222, 15),
(223, 15),
(225, 15),
(226, 15),
(227, 15),
(229, 15),
(238, 16),
(239, 16),
(240, 16),
(241, 16),
(241, 16),
(242, 16),
(243, 16),
(244, 16),
(244, 16),
(245, 16),
(246, 16),
(247, 16),
(248, 16),
(249, 16),
(250, 16),
(251, 16),
(224, 16);

-- --------------------------------------------------------

--
-- Table structure for table `is_subcategory_of`
--

CREATE TABLE IF NOT EXISTS `is_subcategory_of` (
  `subcategory_id` int(11) DEFAULT NULL,
  `parentcategory_id` int(11) DEFAULT NULL,
  KEY `fk_child_cat` (`subcategory_id`),
  KEY `fk_parent_cat` (`parentcategory_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `is_subcategory_of`
--

INSERT INTO `is_subcategory_of` (`subcategory_id`, `parentcategory_id`) VALUES
(15, 17),
(16, 17);

-- --------------------------------------------------------

--
-- Table structure for table `language`
--

CREATE TABLE IF NOT EXISTS `language` (
  `language_id` int(11) NOT NULL AUTO_INCREMENT,
  `language` varchar(45) NOT NULL,
  PRIMARY KEY (`language_id`),
  UNIQUE KEY `language_UNIQUE` (`language`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

--
-- Dumping data for table `language`
--

INSERT INTO `language` (`language_id`, `language`) VALUES
(1, 'English'),
(2, 'Español');

-- --------------------------------------------------------

--
-- Table structure for table `word`
--

CREATE TABLE IF NOT EXISTS `word` (
  `word_id` int(11) NOT NULL AUTO_INCREMENT,
  `word` varchar(50) NOT NULL,
  `primary_sound` char(4) NOT NULL,
  `secondary_sound` char(4) NOT NULL,
  `picture_type` char(4) NOT NULL,
  `language_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`word_id`),
  KEY `fk_word_language_idx` (`language_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=253 ;

--
-- Dumping data for table `word`
--

INSERT INTO `word` (`word_id`, `word`, `primary_sound`, `secondary_sound`, `picture_type`, `language_id`) VALUES
(1, 'a', 'wav', '', 'jpg', 1),
(2, 'b', 'wav', '', 'jpg', 1),
(3, 'c', 'wav', '', 'jpg', 1),
(4, 'd', 'wav', '', 'jpg', 1),
(5, 'e', 'wav', '', 'jpg', 1),
(6, 'f', 'wav', '', 'jpg', 1),
(7, 'g', 'wav', '', 'jpg', 1),
(8, 'h', 'wav', '', 'jpg', 1),
(9, 'i', 'wav', '', 'jpg', 1),
(10, 'j', 'wav', '', 'jpg', 1),
(11, 'k', 'wav', '', 'jpg', 1),
(12, 'l', 'wav', '', 'jpg', 1),
(13, 'm', 'wav', '', 'jpg', 1),
(14, 'n', 'wav', '', 'jpg', 1),
(15, 'o', 'wav', '', 'jpg', 1),
(16, 'p', 'wav', '', 'jpg', 1),
(17, 'q', 'wav', '', 'jpg', 1),
(18, 'r', 'wav', '', 'jpg', 1),
(19, 's', 'wav', '', 'jpg', 1),
(20, 't', 'wav', '', 'jpg', 1),
(21, 'u', 'wav', '', 'jpg', 1),
(22, 'v', 'wav', '', 'jpg', 1),
(23, 'w', 'wav', '', 'jpg', 1),
(24, 'x', 'wav', '', 'jpg', 1),
(25, 'y', 'wav', '', 'jpg', 1),
(26, 'z', 'wav', '', 'jpg', 1),
(73, 'la sandia', 'mp3', 'wav', 'jpg', 2),
(74, 'las naranjas', 'mp3', 'wav', 'jpg', 2),
(75, 'el maíz', 'mp3', 'wav', 'jpg', 2),
(77, 'el tomate', 'mp3', 'wav', 'jpg', 2),
(79, 'la papa', 'mp3', 'wav', 'jpg', 2),
(80, 'la zanahoria', 'mp3', 'wav', 'jpeg', 2),
(81, 'los plátanos', 'mp3', 'wav', 'jpg', 2),
(82, 'la calabaza', 'mp3', 'wav', 'jpeg', 2),
(84, 'Estrella de Mar', 'mp3', '', 'jpg', 2),
(86, 'La Lagartija', 'mp3', '', 'jpg', 2),
(87, 'La ardilla', 'mp3', '', 'jpg', 2),
(88, 'El perro', 'mp3', '', 'jpg', 2),
(89, 'La ballena', 'mp3', '', 'jpg', 2),
(90, 'La araña', 'mp3', '', 'jpg', 2),
(91, 'El rinoceronte ', '', '', 'jpg', 2),
(92, 'La cebra', 'mp3', '', 'jpg', 2),
(93, 'La avispa', 'mp3', '', 'jpg', 2),
(94, 'La foca', 'mp3', '', 'jpg', 2),
(95, 'El cerdo', 'mp3', '', 'jpg', 2),
(96, 'La anguila', 'mp3', '', 'jpg', 2),
(97, 'La oveja', 'mp3', '', 'jpg', 2),
(98, 'El colibri', 'mp3', '', 'jpg', 2),
(99, 'El zorro', 'mp3', '', 'jpg', 2),
(100, 'La mosca', 'mp3', '', 'jpg', 2),
(101, 'El caracol', 'mp3', '', 'jpg', 2),
(102, 'El gato', 'mp3', '', 'jpg', 2),
(103, 'La mantarraya', 'mp3', '', 'jpg', 2),
(104, 'El pájaro carpintero', 'mp3', '', 'jpg', 2),
(105, 'El venado', 'mp3', '', 'jpg', 2),
(106, 'El castor', 'mp3', '', 'jpg', 2),
(107, 'La morsa', '', '', 'jpg', 2),
(108, 'El buho', '', '', 'jpg', 2),
(109, 'La pantera', '', '', 'jpg', 2),
(110, 'El oso', '', '', 'jpg', 2),
(111, 'El caballo de mar', '', '', 'jpg', 2),
(112, 'El cocodrilo', '', '', 'jpg', 2),
(113, 'El mono', '', '', 'jpg', 2),
(114, 'El grillo  ', '', '', 'jpg', 2),
(115, 'El pingüino  ', '', '', 'jpg', 2),
(116, 'La lombriz ', '', '', 'jpg', 2),
(117, 'Los patos', '', '', 'jpg', 2),
(118, 'El murciélago ', '', '', 'jpg', 2),
(119, 'La águila ', '', '', 'jpg', 2),
(120, 'El tiburón ', '', '', 'jpg', 2),
(121, 'El caballo', '', '', 'jpg', 2),
(122, 'El conejo ', '', '', 'jpg', 2),
(123, 'Los camellos', '', '', 'jpg', 2),
(124, 'La hiena ', '', '', 'jpg', 2),
(125, 'La jirafa ', '', '', 'jpg', 2),
(126, 'Los gansos', '', '', 'jpg', 2),
(127, 'El delfín  ', '', '', 'jpg', 2),
(128, 'El zorrillo    ', '', '', 'jpg', 2),
(130, 'El lobo ', '', '', 'jpg', 2),
(131, 'El puerco espín ', '', '', 'jpg', 2),
(132, 'Las gallinas', '', '', 'jpg', 2),
(133, 'La mariposa', '', '', 'jpg', 2),
(134, 'La oruga ', '', '', 'jpg', 2),
(135, 'El ratón ', '', '', 'jpg', 2),
(136, 'El cangrejo ', '', '', 'jpg', 2),
(137, 'La rana ', '', '', 'jpg', 2),
(138, 'El Puma', '', '', 'jpg', 2),
(139, 'El burro ', '', '', 'jpg', 2),
(140, 'El alacrán ', '', '', 'jpg', 2),
(141, 'El chivo', '', '', 'jpg', 2),
(142, 'El sapo', '', '', 'jpg', 2),
(143, 'El gallo ', '', '', 'jpg', 2),
(144, 'La tortuga', '', '', 'jpg', 2),
(145, 'Los calamares ', '', '', 'jpg', 2),
(146, 'El camarón ', '', '', 'jpg', 2),
(147, 'El pulpo', '', '', 'jpg', 2),
(148, 'Los alces ', '', '', 'jpg', 2),
(149, 'La vaca ', '', '', 'jpg', 2),
(150, 'La medusa', '', '', 'jpg', 2),
(151, 'El león ', '', '', 'jpg', 2),
(152, 'El escritorio ', 'mp3', '', 'jpg', 2),
(153, 'Los baños', 'mp3', '', 'jpg', 2),
(154, 'El mapa', 'mp3', '', 'jpg', 2),
(155, 'El gis ', 'mp3', '', 'jpg', 2),
(156, 'La computadora ', 'mp3', '', 'jpg', 2),
(157, 'La cafetería  ', 'mp3', '', 'jpg', 2),
(158, 'La oficina ', 'mp3', '', 'jpg', 2),
(159, 'El maestro ', 'mp3', '', 'jpg', 2),
(160, 'El diccionario ', 'mp3', '', 'jpg', 2),
(161, 'El abecedario   ', 'mp3', '', 'jpg', 2),
(162, 'La pluma', 'mp3', '', 'jpg', 2),
(163, 'La pizarra  ', 'mp3', '', 'jpg', 2),
(164, 'Los audífonos ', 'mp3', '', 'jpg', 2),
(165, 'La biblioteca', 'mp3', '', 'jpg', 2),
(166, 'El libro ', 'mp3', '', 'jpg', 2),
(167, 'Los marca textos ', 'mp3', '', 'jpg', 2),
(168, 'El proyector ', 'mp3', '', 'jpg', 2),
(169, 'El baloncesto', '', '', 'jpg', 2),
(170, 'El béisbol ', '', '', 'jpg', 2),
(171, 'El boxeo ', '', '', 'jpg', 2),
(172, 'El esquí ', '', '', 'jpg', 2),
(173, 'El fútbol americano ', '', '', 'jpg', 2),
(174, 'La natación ', '', '', 'jpg', 2),
(175, 'El patinaje', '', '', 'jpg', 2),
(176, 'El tenis', '', '', 'jpg', 2),
(177, 'Acampar', '', '', 'jpg', 2),
(178, 'Correr', '', '', 'jpg', 2),
(179, 'Viajar', '', '', 'jpg', 2),
(180, 'Levantar pesas', '', '', 'jpg', 2),
(181, 'Bailar', '', '', 'jpg', 2),
(182, 'Alquilar peliculas', '', '', 'jpg', 2),
(183, 'Los guantes ', '', '', 'jpg', 2),
(184, 'La corbata de moño ', '', '', 'jpg', 2),
(185, 'Las gafas de sol ', '', '', 'jpg', 2),
(186, 'Los calcetines ', '', '', 'jpg', 2),
(187, 'Los tacones ', '', '', 'jpg', 2),
(188, 'La camisa', '', '', 'jpg', 2),
(189, 'El gorro ', '', '', 'jpg', 2),
(190, 'La corbata ', '', '', 'jpg', 2),
(191, 'El chaleco ', '', '', 'jpg', 2),
(192, 'El traje de baño ', '', '', 'jpg', 2),
(193, 'El vestido de noche', '', '', 'jpg', 2),
(194, 'El contra viento ', '', '', 'jpg', 2),
(195, 'Los pantalones de ejercicio ', '', '', 'jpg', 2),
(196, 'El vestido de cóctel ', '', '', 'jpg', 2),
(197, 'El vestido de maternidad ', '', '', 'jpg', 2),
(198, 'La falda ', '', '', 'jpg', 2),
(199, 'El abrigo ', '', '', 'jpg', 2),
(200, 'La diadema  ', '', '', 'jpg', 2),
(201, 'El baño ', 'mp3', '', 'jpg', 2),
(202, 'El patio ', 'mp3', '', 'jpg', 2),
(203, 'El detector de humo ', 'mp3', '', 'jpg', 2),
(204, 'El asilo de ancianos ', 'mp3', '', 'jpg', 2),
(205, 'La alberca', 'mp3', '', 'jpg', 2),
(206, 'La granja ', 'mp3', '', 'jpg', 2),
(207, 'La cochera ', 'mp3', '', 'jpg', 2),
(208, 'El callejón ', 'mp3', '', 'jpg', 2),
(209, 'El techo ', 'mp3', '', 'jpg', 2),
(210, 'El condominio ', 'mp3', '', 'jpg', 2),
(211, 'El piso ', 'mp3', '', 'jpg', 2),
(212, 'El cuarto ', 'mp3', '', 'jpg', 2),
(213, 'La sala ', 'mp3', '', 'jpg', 2),
(214, 'El sótano ', 'mp3', '', 'jpg', 2),
(215, 'La ventana', '', '', 'jpg', 2),
(216, 'El basurero ', '', '', 'jpg', 2),
(217, 'El comedor ', '', '', 'jpg', 2),
(218, 'La puerta ', '', '', 'jpg', 2),
(219, 'Los escalones ', '', '', 'jpg', 2),
(220, 'Las mandarinas', 'mp3', '', 'jpg', 2),
(221, 'El melón ', 'mp3', '', 'jpg', 2),
(222, 'Las tunas ', 'mp3', '', 'jpg', 2),
(223, 'Las aceitunas ', 'mp3', '', 'jpg', 2),
(224, 'Los aguacates', '', '', 'jpg', 2),
(225, 'Las cerezas ', 'mp3', '', 'jpg', 2),
(226, 'las ciruelas', 'mp3', '', 'jpg', 2),
(227, 'El coco ', 'mp3', '', 'jpg', 2),
(228, 'Los higos ', 'mp3', '', 'jpg', 2),
(229, 'Las frambuesas ', 'mp3', '', 'jpg', 2),
(230, 'Las fresas ', 'mp3', '', 'jpg', 2),
(231, 'La granada', '', '', 'jpg', 2),
(232, 'Las guayabas', '', '', 'jpg', 2),
(233, 'El kiwi  ', '', '', 'jpg', 2),
(234, 'El limón amarillo ', '', '', 'jpg', 2),
(235, 'El limón verde ', '', '', 'jpg', 2),
(236, 'El mango ', '', '', 'jpg', 2),
(237, 'Las manzanas ', '', '', 'jpg', 2),
(238, 'Las acelgas', '', '', 'jpg', 2),
(239, 'El ajo ', '', '', 'jpg', 2),
(240, 'Las alcachofas ', '', '', 'jpg', 2),
(241, 'Los apios ', '', '', 'jpg', 2),
(242, 'Las berenjenas ', '', '', 'jpg', 2),
(243, 'El brócoli  ', '', '', 'jpg', 2),
(244, 'Los cacahuates ', '', '', 'jpg', 2),
(245, 'las calabazas ', '', '', 'jpg', 2),
(246, 'Las cebollas ', '', '', 'jpg', 2),
(247, 'Los champiñones ', '', '', 'jpg', 2),
(248, 'Los chícharos ', '', '', 'jpg', 1),
(249, 'Los chiles ', '', '', 'jpg', 2),
(250, 'Los espárragos   ', '', '', 'jpg', 2),
(251, 'Las lechugas ', '', '', 'jpg', 2);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `is_in`
--
ALTER TABLE `is_in`
  ADD CONSTRAINT `fk_in_category` FOREIGN KEY (`category_id`) REFERENCES `category` (`category_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_is_word` FOREIGN KEY (`word_id`) REFERENCES `word` (`word_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `is_subcategory_of`
--
ALTER TABLE `is_subcategory_of`
  ADD CONSTRAINT `fk_parent_cat` FOREIGN KEY (`parentcategory_id`) REFERENCES `category` (`category_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_child_cat` FOREIGN KEY (`subcategory_id`) REFERENCES `category` (`category_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `word`
--
ALTER TABLE `word`
  ADD CONSTRAINT `fk_word_language` FOREIGN KEY (`language_id`) REFERENCES `language` (`language_id`) ON DELETE SET NULL ON UPDATE SET NULL;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
