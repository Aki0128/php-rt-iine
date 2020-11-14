-- phpMyAdmin SQL Dump
-- version 4.9.3
-- https://www.phpmyadmin.net/
--
-- ホスト: localhost:8889
-- 生成日時: 2020 年 11 月 14 日 14:21
-- サーバのバージョン： 5.7.26
-- PHP のバージョン: 7.4.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- データベース: `mini_bbs`
--

-- --------------------------------------------------------

--
-- テーブルの構造 `likes`
--

CREATE TABLE `likes` (
  `id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  `like_post_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- テーブルのデータのダンプ `likes`
--

INSERT INTO `likes` (`id`, `member_id`, `like_post_id`) VALUES
(32, 12, 36),
(33, 12, 38),
(34, 11, 38),
(38, 10, 39);

-- --------------------------------------------------------

--
-- テーブルの構造 `members`
--

CREATE TABLE `members` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(100) NOT NULL,
  `picture` varchar(255) NOT NULL,
  `created` datetime NOT NULL,
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- テーブルのデータのダンプ `members`
--

INSERT INTO `members` (`id`, `name`, `email`, `password`, `picture`, `created`, `modified`) VALUES
(10, 'ねこ', 'mail7@gmail.com', '501ab5444eae9ad32b562570b36ff628ec3790ce', '20201012192556f_f_object_111_s512_f_object_111_0bg.jpg', '2020-10-12 19:26:08', '2020-10-12 10:26:08'),
(11, 'くま', 'mail8@gmail.com', '0ddb5877c896f43e8734e10b001e7f1eb92889cd', '20201012192813f_f_object_81_s512_f_object_81_2bg.jpg', '2020-10-12 19:28:19', '2020-10-12 10:28:19'),
(12, 'いぬ', 'mail9@gmail.com', '4170ac2a2782a1516fe9e13d7322ae482c1bd594', '20201012192952f_f_object_112_s512_f_object_112_0bg.jpg', '2020-10-12 19:29:57', '2020-10-12 10:29:57');

-- --------------------------------------------------------

--
-- テーブルの構造 `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `message` text NOT NULL,
  `member_id` int(11) NOT NULL,
  `reply_post_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- テーブルのデータのダンプ `posts`
--

INSERT INTO `posts` (`id`, `message`, `member_id`, `reply_post_id`, `created`, `modified`) VALUES
(19, 'ねこ', 10, 0, '2020-10-12 19:26:52', '2020-10-12 10:26:52'),
(36, 'ねこの１', 10, 0, '2020-10-20 23:53:12', '2020-10-20 14:53:12'),
(38, 'ねこの３', 10, 0, '2020-10-20 23:53:21', '2020-10-20 14:53:21'),
(39, 'くまの１', 11, 0, '2020-10-20 23:54:13', '2020-10-20 14:54:13'),
(40, 'いぬの１', 12, 0, '2020-10-21 04:23:38', '2020-10-20 19:23:38'),
(41, '@ねこ ねこの３＞いぬの返信', 12, 38, '2020-10-21 23:37:12', '2020-10-21 14:37:12');

-- --------------------------------------------------------

--
-- テーブルの構造 `retweet`
--

CREATE TABLE `retweet` (
  `id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  `retweet_post_id` int(11) NOT NULL,
  `created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- テーブルのデータのダンプ `retweet`
--

INSERT INTO `retweet` (`id`, `member_id`, `retweet_post_id`, `created`) VALUES
(19, 12, 36, '2020-10-21 04:24:09'),
(24, 11, 38, '2020-10-21 07:24:40'),
(29, 12, 39, '2020-10-21 23:41:36'),
(40, 12, 38, '2020-10-23 01:42:46'),
(43, 10, 39, '2020-10-24 00:23:46');

--
-- ダンプしたテーブルのインデックス
--

--
-- テーブルのインデックス `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `members`
--
ALTER TABLE `members`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `retweet`
--
ALTER TABLE `retweet`
  ADD PRIMARY KEY (`id`);

--
-- ダンプしたテーブルのAUTO_INCREMENT
--

--
-- テーブルのAUTO_INCREMENT `likes`
--
ALTER TABLE `likes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- テーブルのAUTO_INCREMENT `members`
--
ALTER TABLE `members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- テーブルのAUTO_INCREMENT `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- テーブルのAUTO_INCREMENT `retweet`
--
ALTER TABLE `retweet`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
