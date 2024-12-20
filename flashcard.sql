-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- ホスト: mysql3104.db.sakura.ne.jp
-- 生成日時: 2024 年 12 月 20 日 10:46
-- サーバのバージョン： 8.0.40
-- PHP のバージョン: 8.2.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- データベース: `atuy-amour_gs_db_class`
--

-- --------------------------------------------------------

--
-- テーブルの構造 `flashcard`
--

CREATE TABLE `flashcard` (
  `id` int NOT NULL,
  `word` varchar(64) NOT NULL,
  `type` varchar(64) NOT NULL,
  `meaning` text NOT NULL,
  `phrase` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- テーブルのデータのダンプ `flashcard`
--

INSERT INTO `flashcard` (`id`, `word`, `type`, `meaning`, `phrase`) VALUES
(1, 'une distillerie', 'noun', '蒸留所', 'wisky'),
(2, 'une ruelle', 'noun', '路地、小路、裏通り', 'Les ruelles des vieux quartiers sont malsaines.');

--
-- ダンプしたテーブルのインデックス
--

--
-- テーブルのインデックス `flashcard`
--
ALTER TABLE `flashcard`
  ADD PRIMARY KEY (`id`);

--
-- ダンプしたテーブルの AUTO_INCREMENT
--

--
-- テーブルの AUTO_INCREMENT `flashcard`
--
ALTER TABLE `flashcard`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
