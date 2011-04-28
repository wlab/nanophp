SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `nanophp`
--
CREATE DATABASE IF NOT EXISTS `nanophp` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `nanophp`;

-- --------------------------------------------------------

--
-- Table structure for table `Auths`
--

CREATE TABLE IF NOT EXISTS `Auths` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `uid` int(11) NOT NULL COMMENT 'User ID (our side)',
  `fuid` char(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Foreign User ID (their side)',
  `perams` char(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'JSON Encoded Perams (any extra perams)',
  PRIMARY KEY (`id`),
  KEY `fuid` (`fuid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='This is to be used with assoc. a user with External Accounts' AUTO_INCREMENT=1 ;

--
-- Dumping data for table `Auths`
--


-- --------------------------------------------------------

--
-- Table structure for table `Languages`
--

CREATE TABLE IF NOT EXISTS `Languages` (
  `code` char(7) COLLATE utf8_unicode_ci NOT NULL,
  `key` char(255) COLLATE utf8_unicode_ci NOT NULL,
  `value` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`code`,`key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `Languages`
--


-- --------------------------------------------------------

--
-- Table structure for table `Sessions`
--

CREATE TABLE IF NOT EXISTS `Sessions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `User` int(11) NOT NULL,
  `token` char(255) COLLATE utf8_unicode_ci NOT NULL,
  `ip` int(11) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `token` (`token`,`ip`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `Sessions`
--


-- --------------------------------------------------------

--
-- Table structure for table `Users`
--

CREATE TABLE IF NOT EXISTS `Users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `language` char(7) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  `is_admin` tinyint(1) NOT NULL DEFAULT '0',
  `is_super_admin` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `email` (`email`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `Users`
--

INSERT INTO `Users` (`id`, `language`, `email`, `password`, `is_active`, `is_admin`, `is_super_admin`, `created_at`, `updated_at`) VALUES
(1, 'en_GB', 'chris@nanophp.org', 'testing', 1, 1, 1, '2010-12-07 11:57:43', '2010-12-07 11:57:43');
