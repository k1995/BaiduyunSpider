-- phpMyAdmin SQL Dump
-- version 4.4.7
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: 2016-09-28 16:56:27
-- 服务器版本： 5.5.42-log
-- PHP Version: 7.0.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `pan`
--

-- --------------------------------------------------------

--
-- 表的结构 `share_file`
--

CREATE TABLE IF NOT EXISTS `share_file` (
  `fid` bigint(20) unsigned NOT NULL,
  `title` varchar(100) NOT NULL,
  `uk` bigint(20) unsigned NOT NULL,
  `shorturl` varchar(15) NOT NULL,
  `isdir` tinyint(1) NOT NULL,
  `size` bigint(20) unsigned NOT NULL,
  `md5` varchar(32) NOT NULL,
  `shareid` varchar(20) NOT NULL,
  `deleted` tinyint(4) NOT NULL DEFAULT '0',
  `d_cnt` int(11) unsigned NOT NULL DEFAULT '0',
  `ext` varchar(10) NOT NULL,
  `create_time` int(11) NOT NULL,
  `file_type` tinyint(4) NOT NULL COMMENT '0:video;1:image;2:document;3:music;4:package;5:software',
  `uid` int(20) NOT NULL,
  `feed_type` varchar(10) NOT NULL DEFAULT 'share',
  `feed_time` int(11) NOT NULL,
  `indexed` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `share_users`
--

CREATE TABLE IF NOT EXISTS `share_users` (
  `uid` int(20) NOT NULL,
  `user_name` varchar(50) NOT NULL,
  `uk` bigint(20) unsigned NOT NULL,
  `avatar_url` varchar(200) NOT NULL,
  `intro` text NOT NULL,
  `follow_count` int(11) NOT NULL,
  `fens_count` int(11) NOT NULL,
  `pubshare_count` int(11) NOT NULL,
  `album_count` int(11) NOT NULL,
  `last_visited` int(11) NOT NULL,
  `weight` tinyint(4) NOT NULL,
  `create_time` int(11) NOT NULL,
  `visited_count` int(11) NOT NULL DEFAULT '0',
  `fetched` int(11) NOT NULL DEFAULT '0' COMMENT '爬取到的文件数'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `spider_list`
--

CREATE TABLE IF NOT EXISTS `spider_list` (
  `sid` bigint(20) NOT NULL,
  `uk` bigint(20) unsigned NOT NULL,
  `file_fetched` int(11) NOT NULL DEFAULT '0',
  `follow_fetched` int(11) NOT NULL DEFAULT '0',
  `follow_done` tinyint(1) NOT NULL DEFAULT '0',
  `file_done` tinyint(1) NOT NULL DEFAULT '0',
  `weight` tinyint(4) NOT NULL DEFAULT '5',
  `uid` bigint(20) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `share_file`
--
ALTER TABLE `share_file`
  ADD PRIMARY KEY (`fid`),
  ADD KEY `fk_uid` (`uid`),
  ADD KEY `feed_type` (`feed_type`),
  ADD KEY `file_type` (`file_type`),
  ADD KEY `isdir` (`isdir`),
  ADD KEY `indexed` (`indexed`);

--
-- Indexes for table `share_users`
--
ALTER TABLE `share_users`
  ADD PRIMARY KEY (`uid`),
  ADD UNIQUE KEY `uk` (`uk`);

--
-- Indexes for table `spider_list`
--
ALTER TABLE `spider_list`
  ADD PRIMARY KEY (`sid`),
  ADD UNIQUE KEY `uk` (`uk`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `share_file`
--
ALTER TABLE `share_file`
  MODIFY `fid` bigint(20) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `share_users`
--
ALTER TABLE `share_users`
  MODIFY `uid` int(20) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `spider_list`
--
ALTER TABLE `spider_list`
  MODIFY `sid` bigint(20) NOT NULL AUTO_INCREMENT;
--
-- 限制导出的表
--

--
-- 限制表 `share_file`
--
ALTER TABLE `share_file`
  ADD CONSTRAINT `fk_uid` FOREIGN KEY (`uid`) REFERENCES `share_users` (`uid`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
