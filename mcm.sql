-- phpMyAdmin SQL Dump
-- version 3.5.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 02, 2014 at 03:02 AM
-- Server version: 5.5.28
-- PHP Version: 5.4.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `mcm`
--

-- --------------------------------------------------------

--
-- Table structure for table `mcm_apps_choice`
--

CREATE TABLE IF NOT EXISTS `mcm_apps_choice` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `text` text NOT NULL,
  `category` varchar(255) NOT NULL,
  `image` int(11) DEFAULT '0',
  `soundtext` int(11) DEFAULT '0',
  `var1` varchar(255) DEFAULT NULL,
  `var2` varchar(255) DEFAULT NULL,
  `var3` varchar(255) DEFAULT NULL,
  `var4` varchar(255) DEFAULT NULL,
  `var5` varchar(255) DEFAULT NULL,
  `var6` varchar(255) DEFAULT NULL,
  `var7` varchar(255) DEFAULT NULL,
  `var8` varchar(255) DEFAULT NULL,
  `var9` varchar(255) DEFAULT NULL,
  `var10` varchar(255) DEFAULT NULL,
  `var11` varchar(255) DEFAULT NULL,
  `var12` varchar(255) DEFAULT NULL,
  `cor1` int(2) DEFAULT '0',
  `cor2` int(2) DEFAULT '0',
  `cor3` int(2) DEFAULT '0',
  `cor4` int(2) DEFAULT '0',
  `cor5` int(2) DEFAULT '0',
  `cor6` int(2) DEFAULT '0',
  `cor7` int(2) DEFAULT '0',
  `cor8` int(2) DEFAULT '0',
  `cor9` int(2) DEFAULT '0',
  `cor10` int(2) DEFAULT '0',
  `cor11` int(2) DEFAULT '0',
  `cor12` int(2) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `soundtext` (`soundtext`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=271 ;

-- --------------------------------------------------------

--
-- Table structure for table `mcm_apps_clozeactivity`
--

CREATE TABLE IF NOT EXISTS `mcm_apps_clozeactivity` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `text` text NOT NULL,
  `category` varchar(255) NOT NULL,
  `image` int(11) DEFAULT '0',
  `soundtext` int(11) DEFAULT '0',
  `var1` varchar(255) DEFAULT NULL,
  `var2` varchar(255) DEFAULT NULL,
  `var3` varchar(255) DEFAULT NULL,
  `var4` varchar(255) DEFAULT NULL,
  `var5` varchar(255) DEFAULT NULL,
  `var6` varchar(255) DEFAULT NULL,
  `var7` varchar(255) DEFAULT NULL,
  `var8` varchar(255) DEFAULT NULL,
  `var9` varchar(255) DEFAULT NULL,
  `var10` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `soundtext` (`soundtext`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=276 ;

-- --------------------------------------------------------

--
-- Table structure for table `mcm_apps_dragdrope`
--

CREATE TABLE IF NOT EXISTS `mcm_apps_dragdrope` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `text` text,
  `category` varchar(255) NOT NULL,
  `imgvar1` int(11) DEFAULT '0',
  `imgvar2` int(11) DEFAULT '0',
  `imgvar3` int(11) DEFAULT '0',
  `imgvar4` int(11) DEFAULT '0',
  `imgvar5` int(11) DEFAULT '0',
  `imga1` int(11) DEFAULT '0',
  `imga2` int(11) DEFAULT '0',
  `imga3` int(11) DEFAULT '0',
  `imga4` int(11) DEFAULT '0',
  `imga5` int(11) DEFAULT '0',
  `soundtextvar1` int(11) DEFAULT '0',
  `soundtextvar2` int(11) DEFAULT '0',
  `soundtextvar3` int(11) DEFAULT '0',
  `soundtextvar4` int(11) DEFAULT '0',
  `soundtextvar5` int(11) DEFAULT '0',
  `soundtexta1` int(11) DEFAULT '0',
  `soundtexta2` int(11) DEFAULT '0',
  `soundtexta3` int(11) DEFAULT '0',
  `soundtexta4` int(11) DEFAULT '0',
  `soundtexta5` int(11) DEFAULT '0',
  `var1` varchar(255) DEFAULT NULL,
  `var2` varchar(255) DEFAULT NULL,
  `var3` varchar(255) DEFAULT NULL,
  `var4` varchar(255) DEFAULT NULL,
  `var5` varchar(255) DEFAULT NULL,
  `a1` varchar(255) DEFAULT NULL,
  `a2` varchar(255) DEFAULT NULL,
  `a3` varchar(255) DEFAULT NULL,
  `a4` varchar(255) DEFAULT NULL,
  `a5` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `soundtext` (`soundtextvar1`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Table structure for table `mcm_apps_groupquiz`
--

CREATE TABLE IF NOT EXISTS `mcm_apps_groupquiz` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL,
  `quizcategory` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Table structure for table `mcm_apps_groupquiz_answers`
--

CREATE TABLE IF NOT EXISTS `mcm_apps_groupquiz_answers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `qid` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `aid` int(11) NOT NULL,
  `cid` int(11) NOT NULL,
  `answer` int(11) DEFAULT NULL,
  `correct` int(1) NOT NULL DEFAULT '0',
  `time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=445 ;

-- --------------------------------------------------------

--
-- Table structure for table `mcm_apps_groupquiz_list`
--

CREATE TABLE IF NOT EXISTS `mcm_apps_groupquiz_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `questions` text NOT NULL,
  `uid1` int(11) NOT NULL DEFAULT '0',
  `uid2` int(11) NOT NULL DEFAULT '0',
  `uid3` int(11) NOT NULL DEFAULT '0',
  `uid4` int(11) NOT NULL DEFAULT '0',
  `uid5` int(11) NOT NULL DEFAULT '0',
  `activequestion` int(11) NOT NULL DEFAULT '0',
  `compleate` int(1) NOT NULL DEFAULT '0',
  `time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=39 ;

-- --------------------------------------------------------

--
-- Table structure for table `mcm_apps_groupquiz_online`
--

CREATE TABLE IF NOT EXISTS `mcm_apps_groupquiz_online` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=103 ;

-- --------------------------------------------------------

--
-- Table structure for table `mcm_apps_listening`
--

CREATE TABLE IF NOT EXISTS `mcm_apps_listening` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `category` varchar(255) NOT NULL,
  `feedback` text NOT NULL,
  `soundtext` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `mcm_apps_listenspeak`
--

CREATE TABLE IF NOT EXISTS `mcm_apps_listenspeak` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `text` text,
  `category` varchar(255) NOT NULL,
  `soundtext` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `soundtext` (`soundtext`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `mcm_apps_ordering`
--

CREATE TABLE IF NOT EXISTS `mcm_apps_ordering` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `question` varchar(255) NOT NULL DEFAULT '',
  `text` text,
  `category` varchar(255) NOT NULL,
  `soundtext` int(11) DEFAULT '0',
  `var1` varchar(255) DEFAULT NULL,
  `var2` varchar(255) DEFAULT NULL,
  `var3` varchar(255) DEFAULT NULL,
  `var4` varchar(255) DEFAULT NULL,
  `var5` varchar(255) DEFAULT NULL,
  `var6` varchar(255) DEFAULT NULL,
  `var7` varchar(255) DEFAULT NULL,
  `var8` varchar(255) DEFAULT NULL,
  `var9` varchar(255) DEFAULT NULL,
  `var10` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `soundtext` (`soundtext`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=277 ;

-- --------------------------------------------------------

--
-- Table structure for table `mcm_apps_quizzes`
--

CREATE TABLE IF NOT EXISTS `mcm_apps_quizzes` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `word` varchar(255) NOT NULL,
  `translate` varchar(255) NOT NULL,
  `var1` varchar(255) DEFAULT NULL,
  `var2` varchar(255) DEFAULT NULL,
  `var3` varchar(255) DEFAULT NULL,
  `var4` varchar(255) DEFAULT NULL,
  `var5` varchar(255) DEFAULT NULL,
  `var6` varchar(255) DEFAULT NULL,
  `var7` varchar(255) DEFAULT NULL,
  `var8` varchar(255) DEFAULT NULL,
  `soundword` int(11) DEFAULT '0',
  `category` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `soundword` (`soundword`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=441 ;

-- --------------------------------------------------------

--
-- Table structure for table `mcm_apps_reading`
--

CREATE TABLE IF NOT EXISTS `mcm_apps_reading` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `soundtext` int(11) DEFAULT '0',
  `video` int(11) DEFAULT '0',
  `category` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `soundtext` (`soundtext`),
  KEY `video` (`video`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=144 ;

-- --------------------------------------------------------

--
-- Table structure for table `mcm_apps_reading_texts`
--

CREATE TABLE IF NOT EXISTS `mcm_apps_reading_texts` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `readerid` int(11) NOT NULL,
  `text` text NOT NULL,
  `timing` varchar(10) DEFAULT NULL,
  `image` int(11) DEFAULT '0',
  `vocabulary` varchar(255) DEFAULT NULL,
  `translation` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `image` (`image`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=689 ;

-- --------------------------------------------------------

--
-- Table structure for table `mcm_apps_resource`
--

CREATE TABLE IF NOT EXISTS `mcm_apps_resource` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `text` longtext NOT NULL,
  `image` int(11) DEFAULT '0',
  `soundtext` int(11) DEFAULT '0',
  `category` varchar(20) NOT NULL,
  `url` varchar(255) DEFAULT NULL,
  `urlname` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `image` (`image`),
  KEY `soundtext` (`soundtext`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

-- --------------------------------------------------------

--
-- Table structure for table `mcm_apps_vocabulary`
--

CREATE TABLE IF NOT EXISTS `mcm_apps_vocabulary` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `word` varchar(255) NOT NULL,
  `translate` varchar(255) NOT NULL,
  `text` text NOT NULL,
  `image` int(11) DEFAULT '0',
  `soundword` int(11) DEFAULT '0',
  `soundtext` int(11) DEFAULT '0',
  `video` int(11) DEFAULT '0',
  `category` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `word` (`word`),
  KEY `image` (`image`),
  KEY `soundword` (`soundword`),
  KEY `soundtext` (`soundtext`),
  KEY `video` (`video`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=445 ;

-- --------------------------------------------------------

--
-- Table structure for table `mcm_course`
--

CREATE TABLE IF NOT EXISTS `mcm_course` (
  `id` bigint(10) unsigned NOT NULL AUTO_INCREMENT,
  `category` bigint(10) unsigned NOT NULL DEFAULT '0',
  `password` varchar(50) NOT NULL DEFAULT '',
  `fullname` varchar(254) NOT NULL DEFAULT '',
  `shortname` varchar(100) NOT NULL DEFAULT '',
  `summary` text,
  `format` varchar(10) NOT NULL DEFAULT 'topics',
  `showgrades` tinyint(2) unsigned NOT NULL DEFAULT '1',
  `modinfo` longtext,
  `guest` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `startdate` bigint(10) unsigned NOT NULL DEFAULT '0',
  `enrolperiod` bigint(10) unsigned NOT NULL DEFAULT '0',
  `numsections` mediumint(5) unsigned NOT NULL DEFAULT '1',
  `visible` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `lang` varchar(30) NOT NULL DEFAULT '',
  `timecreated` bigint(10) unsigned NOT NULL DEFAULT '0',
  `timemodified` bigint(10) unsigned NOT NULL DEFAULT '0',
  `enrollable` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `enrolstartdate` bigint(10) unsigned NOT NULL DEFAULT '0',
  `enrolenddate` bigint(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `mdl_cour_cat_ix` (`category`),
  KEY `mdl_cour_sho_ix` (`shortname`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Table structure for table `mcm_course_student`
--

CREATE TABLE IF NOT EXISTS `mcm_course_student` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(11) DEFAULT '0',
  `courseid` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `courseid` (`courseid`),
  KEY `userid` (`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `mcm_course_topic`
--

CREATE TABLE IF NOT EXISTS `mcm_course_topic` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `courseid` int(11) DEFAULT '0',
  `data` text NOT NULL,
  `order` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `courseid` (`courseid`),
  KEY `order` (`order`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=136 ;

-- --------------------------------------------------------

--
-- Table structure for table `mcm_log`
--

CREATE TABLE IF NOT EXISTS `mcm_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(11) DEFAULT '0',
  `apps` int(11) DEFAULT '0',
  `courseid` int(11) DEFAULT '0',
  `time` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`),
  KEY `apps` (`apps`),
  KEY `courseid` (`courseid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=68165 ;

-- --------------------------------------------------------

--
-- Table structure for table `mcm_options`
--

CREATE TABLE IF NOT EXISTS `mcm_options` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=18 ;

-- --------------------------------------------------------

--
-- Table structure for table `mcm_roles`
--

CREATE TABLE IF NOT EXISTS `mcm_roles` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(11) DEFAULT '0',
  `courseid` int(11) DEFAULT '0',
  `roleid` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`),
  KEY `courseid` (`courseid`),
  KEY `roleid` (`roleid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `mcm_score`
--

CREATE TABLE IF NOT EXISTS `mcm_score` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(11) DEFAULT '0',
  `apps` int(11) DEFAULT '0',
  `courseid` int(11) DEFAULT '0',
  `appsid` int(11) NOT NULL,
  `answer` varchar(255) DEFAULT '0',
  `score` int(11) DEFAULT '0',
  `correct` int(11) DEFAULT '0',
  `time` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`),
  KEY `apps` (`apps`),
  KEY `appsid` (`appsid`),
  KEY `score` (`score`),
  KEY `correct` (`correct`),
  KEY `time` (`time`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=48072 ;

-- --------------------------------------------------------

--
-- Table structure for table `mcm_user`
--

CREATE TABLE IF NOT EXISTS `mcm_user` (
  `id` bigint(10) unsigned NOT NULL AUTO_INCREMENT,
  `auth` varchar(20) NOT NULL DEFAULT 'manual',
  `confirmed` tinyint(1) NOT NULL DEFAULT '1',
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `username` varchar(100) NOT NULL DEFAULT '',
  `password` varchar(32) NOT NULL DEFAULT '',
  `firstname` varchar(100) NOT NULL DEFAULT '',
  `lastname` varchar(100) NOT NULL DEFAULT '',
  `email` varchar(100) NOT NULL DEFAULT '',
  `emailstop` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `icq` varchar(15) NOT NULL DEFAULT '',
  `skype` varchar(50) NOT NULL DEFAULT '',
  `yahoo` varchar(50) NOT NULL DEFAULT '',
  `aim` varchar(50) NOT NULL DEFAULT '',
  `msn` varchar(50) NOT NULL DEFAULT '',
  `phone1` varchar(20) NOT NULL DEFAULT '',
  `phone2` varchar(20) NOT NULL DEFAULT '',
  `institution` varchar(40) NOT NULL DEFAULT '',
  `department` varchar(30) NOT NULL DEFAULT '',
  `address` varchar(70) NOT NULL DEFAULT '',
  `city` varchar(20) NOT NULL DEFAULT '',
  `country` varchar(2) NOT NULL DEFAULT '',
  `lang` varchar(30) NOT NULL DEFAULT 'en',
  `timezone` varchar(100) NOT NULL DEFAULT '99',
  `firstaccess` bigint(10) unsigned NOT NULL DEFAULT '0',
  `lastaccess` bigint(10) unsigned NOT NULL DEFAULT '0',
  `lastlogin` bigint(10) unsigned NOT NULL DEFAULT '0',
  `currentlogin` bigint(10) unsigned NOT NULL DEFAULT '0',
  `lastip` varchar(15) NOT NULL DEFAULT '',
  `secret` varchar(15) NOT NULL DEFAULT '',
  `picture` tinyint(1) NOT NULL DEFAULT '0',
  `url` varchar(255) NOT NULL DEFAULT '',
  `description` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `mdl_user_mneuse_uix` (`username`),
  KEY `mdl_user_del_ix` (`deleted`),
  KEY `mdl_user_con_ix` (`confirmed`),
  KEY `mdl_user_fir_ix` (`firstname`),
  KEY `mdl_user_las_ix` (`lastname`),
  KEY `mdl_user_cit_ix` (`city`),
  KEY `mdl_user_cou_ix` (`country`),
  KEY `mdl_user_las2_ix` (`lastaccess`),
  KEY `mdl_user_ema_ix` (`email`),
  KEY `mdl_user_aut_ix` (`auth`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=190 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
