-- phpMyAdmin SQL Dump
-- version 4.1.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: 2014 年 2 月 06 日 21:07
-- サーバのバージョン： 5.5.29
-- PHP Version: 5.3.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `video_contents`
--

-- --------------------------------------------------------

--
-- テーブルの構造 `contents_table`
--

CREATE TABLE IF NOT EXISTS `contents_table` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `page_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` varchar(255) NOT NULL,
  `image_name` varchar(255) NOT NULL,
  `mp4_file_name` varchar(255) NOT NULL,
  `ogv_file_name` varchar(255) NOT NULL,
  `publish_status` int(11) NOT NULL,
  `order_no` int(11) NOT NULL,
  `created` datetime DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='動画コンテンツのデータを格納テーブル' AUTO_INCREMENT=0 ;


-- --------------------------------------------------------

--
-- テーブルの構造 `menus_table`
--

CREATE TABLE IF NOT EXISTS `menus_table` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL COMMENT '表示メニュー名',
  `page_id` int(11) NOT NULL COMMENT 'メニューが指定されたときに表示するページID',
  `publish_status` int(11) NOT NULL,
  `order_no` int(11) NOT NULL,
  `created` datetime DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='メニューのデータを格納するテーブル' AUTO_INCREMENT=0 ;


-- --------------------------------------------------------

--
-- テーブルの構造 `pages_table`
--

CREATE TABLE IF NOT EXISTS `pages_table` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `menu_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL COMMENT 'ページの名前',
  `remark` text NOT NULL COMMENT 'ページの説明',
  `template_name` varchar(255) NOT NULL COMMENT 'ページの表示に使用するテンプレートファイル名',
  `publish_status` int(11) NOT NULL,
  `order_no` int(11) NOT NULL,
  `created` datetime DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='ページのデータを格納するテーブル' AUTO_INCREMENT=0 ;


-- --------------------------------------------------------

--
-- テーブルの構造 `play_logs_table`
--

CREATE TABLE IF NOT EXISTS `play_logs_table` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content_id` int(11) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `created` datetime DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='再生回数を格納するテーブル' AUTO_INCREMENT=0 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
