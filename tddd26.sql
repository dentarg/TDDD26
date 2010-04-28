-- phpMyAdmin SQL Dump
-- version 3.2.0.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 26, 2010 at 01:27 PM
-- Server version: 5.1.36
-- PHP Version: 5.3.0

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `tddd26`
--

-- --------------------------------------------------------

--
-- Table structure for table `album`
--

CREATE TABLE IF NOT EXISTS `album` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(150) CHARACTER SET utf8 NOT NULL DEFAULT 'Unnamed' COMMENT 'album title',
  `date` int(11) NOT NULL COMMENT 'date of creation / update (use time() function which returns 11 int digits)',
  `cover` int(20) NOT NULL COMMENT 'id of cover picture',
  `author` int(8) NOT NULL COMMENT 'id of creator',
  `access` int(1) NOT NULL DEFAULT '0' COMMENT 'level of access (0 - public, 1 - shared (only friends), 2 - private (only creator)',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `album`
--


-- --------------------------------------------------------

--
-- Table structure for table `photo`
--

CREATE TABLE IF NOT EXISTS `photo` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) CHARACTER SET utf8 NOT NULL DEFAULT 'Unnamed' COMMENT 'picture title',
  `picture` varchar(100) CHARACTER SET utf8 NOT NULL COMMENT 'path to image',
  `date` int(11) NOT NULL COMMENT 'date of creation / update',
  `album` int(10) NOT NULL COMMENT 'id of album it belongs to',
  PRIMARY KEY (`id`),
  UNIQUE KEY `picture` (`picture`,`album`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `photo`
--


-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(8) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(100) CHARACTER SET utf8 NOT NULL COMMENT 'email / used for loging in',
  `password` varchar(33) CHARACTER SET utf8 NOT NULL COMMENT 'md5 hash of pass',
  `nickname` varchar(50) CHARACTER SET utf8 NOT NULL COMMENT 'name which friends will see',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 - user, 1 - admin',
  `friends` varchar(10000) CHARACTER SET utf8 NOT NULL COMMENT 'friend users'' ids separated by comas',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `user`
--

