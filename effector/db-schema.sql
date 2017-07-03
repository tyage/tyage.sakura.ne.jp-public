-- phpMyAdmin SQL Dump
-- version 3.3.10.5
-- http://www.phpmyadmin.net
--
-- ホスト: mysql202.db.sakura.ne.jp
-- 生成時間: 2014 年 4 月 14 日 13:59
-- サーバのバージョン: 5.1.66
-- PHP のバージョン: 5.4.7

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- データベース: `tyage`
--

-- --------------------------------------------------------

--
-- テーブルの構造 `effecter_categories`
--

CREATE TABLE IF NOT EXISTS `effecter_categories` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=29 ;

-- --------------------------------------------------------

--
-- テーブルの構造 `effecter_posts`
--

CREATE TABLE IF NOT EXISTS `effecter_posts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `category_id` int(10) unsigned NOT NULL,
  `username` varchar(64) NOT NULL,
  `password` varchar(128) NOT NULL,
  `name` varchar(128) NOT NULL,
  `cost` varchar(32) NOT NULL,
  `cause` text NOT NULL,
  `instrument` text NOT NULL,
  `all` int(11) NOT NULL,
  `performance` int(11) NOT NULL,
  `quality` int(11) NOT NULL,
  `operability` int(11) NOT NULL,
  `recommend` int(11) NOT NULL,
  `review` text NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=397241 ;

