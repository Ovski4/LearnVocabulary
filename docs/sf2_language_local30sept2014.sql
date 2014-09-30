-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le: Mar 30 Septembre 2014 à 18:28
-- Version du serveur: 5.5.38-0ubuntu0.14.04.1
-- Version de PHP: 5.5.9-1ubuntu4.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `sf2_language`
--

-- --------------------------------------------------------

--
-- Structure de la table `Article`
--

CREATE TABLE IF NOT EXISTS `Article` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `language_id` int(11) NOT NULL,
  `value` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_CD8737FA82F1BAF4` (`language_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=17 ;

--
-- Contenu de la table `Article`
--

INSERT INTO `Article` (`id`, `language_id`, `value`) VALUES
(9, 7, 'el'),
(10, 7, 'la'),
(11, 5, 'der'),
(12, 5, 'die'),
(13, 5, 'das'),
(14, 8, 'le'),
(15, 8, 'la'),
(16, 8, 'l''');

-- --------------------------------------------------------

--
-- Structure de la table `Language`
--

CREATE TABLE IF NOT EXISTS `Language` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `require_articles` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=9 ;

--
-- Contenu de la table `Language`
--

INSERT INTO `Language` (`id`, `name`, `require_articles`) VALUES
(5, 'german', 1),
(6, 'english', 0),
(7, 'spanish', 1),
(8, 'french', 1);

-- --------------------------------------------------------

--
-- Structure de la table `Learning`
--

CREATE TABLE IF NOT EXISTS `Learning` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `language1_id` int(11) NOT NULL,
  `language2_id` int(11) NOT NULL,
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_3673C6946C3EEA2C` (`language1_id`),
  KEY `IDX_3673C6947E8B45C2` (`language2_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Contenu de la table `Learning`
--

INSERT INTO `Learning` (`id`, `language1_id`, `language2_id`, `slug`) VALUES
(2, 7, 8, 'french-spanish');

-- --------------------------------------------------------

--
-- Structure de la table `ovski_article`
--

CREATE TABLE IF NOT EXISTS `ovski_article` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `language_id` int(11) NOT NULL,
  `value` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_40D7A10582F1BAF4` (`language_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=25 ;

--
-- Contenu de la table `ovski_article`
--

INSERT INTO `ovski_article` (`id`, `language_id`, `value`) VALUES
(17, 11, 'el'),
(18, 11, 'la'),
(19, 9, 'der'),
(20, 9, 'die'),
(21, 9, 'das'),
(22, 12, 'le'),
(23, 12, 'la'),
(24, 12, 'l''');

-- --------------------------------------------------------

--
-- Structure de la table `ovski_language`
--

CREATE TABLE IF NOT EXISTS `ovski_language` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `require_articles` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=13 ;

--
-- Contenu de la table `ovski_language`
--

INSERT INTO `ovski_language` (`id`, `name`, `require_articles`) VALUES
(9, 'german', 1),
(10, 'english', 0),
(11, 'spanish', 1),
(12, 'french', 1);

-- --------------------------------------------------------

--
-- Structure de la table `ovski_learning`
--

CREATE TABLE IF NOT EXISTS `ovski_learning` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `language1_id` int(11) NOT NULL,
  `language2_id` int(11) NOT NULL,
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_1BFC798F6C3EEA2C` (`language1_id`),
  KEY `IDX_1BFC798F7E8B45C2` (`language2_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=9 ;

--
-- Contenu de la table `ovski_learning`
--

INSERT INTO `ovski_learning` (`id`, `language1_id`, `language2_id`, `slug`) VALUES
(5, 11, 12, 'french-spanish'),
(6, 9, 12, 'french-german'),
(7, 9, 11, 'german-spanish'),
(8, 10, 9, 'english-german');

-- --------------------------------------------------------

--
-- Structure de la table `ovski_translation`
--

CREATE TABLE IF NOT EXISTS `ovski_translation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `word1_id` int(11) NOT NULL,
  `word2_id` int(11) NOT NULL,
  `learning_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `createdAt` datetime NOT NULL,
  `is_starred` tinyint(1) NOT NULL,
  `wordType_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_3CDCAAC24586854D` (`word1_id`),
  KEY `IDX_3CDCAAC257332AA3` (`word2_id`),
  KEY `IDX_3CDCAAC2811B8602` (`wordType_id`),
  KEY `IDX_3CDCAAC24E6B0AB3` (`learning_id`),
  KEY `IDX_3CDCAAC2A76ED395` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=8 ;

--
-- Contenu de la table `ovski_translation`
--

INSERT INTO `ovski_translation` (`id`, `word1_id`, `word2_id`, `learning_id`, `user_id`, `createdAt`, `is_starred`, `wordType_id`) VALUES
(5, 24, 28, 5, 5, '2014-09-28 09:00:29', 1, 19),
(6, 22, 21, 5, 5, '2014-09-28 09:00:29', 0, 23),
(7, 31, 32, 7, 7, '2014-09-28 10:29:09', 0, 22);

-- --------------------------------------------------------

--
-- Structure de la table `ovski_user`
--

CREATE TABLE IF NOT EXISTS `ovski_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `username_canonical` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email_canonical` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `enabled` tinyint(1) NOT NULL,
  `salt` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `last_login` datetime DEFAULT NULL,
  `locked` tinyint(1) NOT NULL,
  `expired` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL,
  `confirmation_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password_requested_at` datetime DEFAULT NULL,
  `roles` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:array)',
  `credentials_expired` tinyint(1) NOT NULL,
  `credentials_expire_at` datetime DEFAULT NULL,
  `max_items_per_page` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_D2DE0C6592FC23A8` (`username_canonical`),
  UNIQUE KEY `UNIQ_D2DE0C65A0D96FBF` (`email_canonical`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=9 ;

--
-- Contenu de la table `ovski_user`
--

INSERT INTO `ovski_user` (`id`, `username`, `username_canonical`, `email`, `email_canonical`, `enabled`, `salt`, `password`, `last_login`, `locked`, `expired`, `expires_at`, `confirmation_token`, `password_requested_at`, `roles`, `credentials_expired`, `credentials_expire_at`, `max_items_per_page`) VALUES
(5, 'baptiste', 'baptiste', 'baptiste@example.com', 'baptiste@example.com', 1, 'qpg6xrxiznk0g0ggs0ss4kkskg0scsw', 'G++BLDHxngaoBTRw/tRAJ3/ymxh4IjWHdOmIuW8/aHHPmKsZG8ch97mGmW5jnUb+S9Bp91lsg05vuCU31Md/bQ==', '2014-09-30 17:50:26', 0, 0, NULL, NULL, NULL, 'a:0:{}', 0, NULL, 1),
(6, 'lise', 'lise', 'lise@example.com', 'lise@example.com', 1, 'nfn5aqirmeooc8k0g4800kgo4c000ks', '7lfBXsTlgNGrZTXcOHpvIlUxc3P6bLZFJTHZF+mbVFzssrRyNZ1uquXY2uG3V5JJkGTyRqHSMabwR/QcRj3phA==', NULL, 0, 0, NULL, NULL, NULL, 'a:0:{}', 0, NULL, 1),
(7, 'bebe', 'bebe', 'test@test.fr', 'test@test.fr', 1, 'lqtras6rzhwc8go0wggc44wk4w0os4w', 'TnlzOiRLcArFNOBqS9J8ITZTuqBNe6IY2JYisuKf/blUH4Bun056myahiybkVN1F7i/6+8S5mKSDoYpyeAtplw==', '2014-09-28 14:51:47', 0, 0, NULL, NULL, NULL, 'a:0:{}', 0, NULL, 10),
(8, 'dede', 'dede', 'gerad@dede.de', 'gerad@dede.de', 1, 'pjbt8mmeh2sco8sogs00kwgo4wo4w08', 'lFP/E92Zm7PKLrKDwTPrTqDAoOpAbKwLBtAZokLT1K1hB14tgPE4iyRxUx1ADpKwXbnKV4cd9Md7Ak/RGmr8bg==', '2014-09-28 11:25:10', 0, 0, NULL, NULL, NULL, 'a:0:{}', 0, NULL, 20);

-- --------------------------------------------------------

--
-- Structure de la table `ovski_user_learning`
--

CREATE TABLE IF NOT EXISTS `ovski_user_learning` (
  `learning_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`learning_id`,`user_id`),
  KEY `IDX_494F53A4E6B0AB3` (`learning_id`),
  KEY `IDX_494F53AA76ED395` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `ovski_user_learning`
--

INSERT INTO `ovski_user_learning` (`learning_id`, `user_id`) VALUES
(5, 5),
(6, 5),
(7, 5),
(7, 7),
(8, 7);

-- --------------------------------------------------------

--
-- Structure de la table `ovski_user_word`
--

CREATE TABLE IF NOT EXISTS `ovski_user_word` (
  `word_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`word_id`,`user_id`),
  KEY `IDX_B1C9FD28E357438D` (`word_id`),
  KEY `IDX_B1C9FD28A76ED395` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `ovski_user_word`
--

INSERT INTO `ovski_user_word` (`word_id`, `user_id`) VALUES
(21, 5),
(22, 5),
(23, 5),
(24, 5),
(25, 5),
(26, 5),
(27, 5),
(28, 5),
(29, 5),
(30, 5);

-- --------------------------------------------------------

--
-- Structure de la table `ovski_word`
--

CREATE TABLE IF NOT EXISTS `ovski_word` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `article_id` int(11) DEFAULT NULL,
  `language_id` int(11) NOT NULL,
  `value` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `wordType_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_9CBCAF3D7294869C` (`article_id`),
  KEY `IDX_9CBCAF3D811B8602` (`wordType_id`),
  KEY `IDX_9CBCAF3D82F1BAF4` (`language_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=33 ;

--
-- Contenu de la table `ovski_word`
--

INSERT INTO `ovski_word` (`id`, `article_id`, `language_id`, `value`, `wordType_id`) VALUES
(21, NULL, 12, 'surtout', 23),
(22, NULL, 11, 'principalmente', 23),
(23, 17, 11, 'pan', 19),
(24, 18, 11, 'manzana', 19),
(25, 18, 11, 'mujer', 19),
(26, 17, 11, 'hombre', 19),
(27, 22, 12, 'pain', 19),
(28, 23, 12, 'pomme', 19),
(29, 24, 12, 'homme', 19),
(30, 23, 12, 'mère', 19),
(31, NULL, 9, 'test', 22),
(32, NULL, 11, 'test', 22);

-- --------------------------------------------------------

--
-- Structure de la table `ovski_word_type`
--

CREATE TABLE IF NOT EXISTS `ovski_word_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `value` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=28 ;

--
-- Contenu de la table `ovski_word_type`
--

INSERT INTO `ovski_word_type` (`id`, `value`) VALUES
(19, 'name'),
(20, 'sentence'),
(21, 'preposition'),
(22, 'verb'),
(23, 'adverb'),
(24, 'article'),
(25, 'conjunction'),
(26, 'pronoun'),
(27, 'adjective');

-- --------------------------------------------------------

--
-- Structure de la table `Translation`
--

CREATE TABLE IF NOT EXISTS `Translation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `word1_id` int(11) NOT NULL,
  `word2_id` int(11) NOT NULL,
  `learning_id` int(11) NOT NULL,
  `createdAt` datetime NOT NULL,
  `wordType_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_32F5CAB84586854D` (`word1_id`),
  KEY `IDX_32F5CAB857332AA3` (`word2_id`),
  KEY `IDX_32F5CAB8811B8602` (`wordType_id`),
  KEY `IDX_32F5CAB84E6B0AB3` (`learning_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=8 ;

--
-- Contenu de la table `Translation`
--

INSERT INTO `Translation` (`id`, `word1_id`, `word2_id`, `learning_id`, `createdAt`, `wordType_id`) VALUES
(3, 14, 18, 2, '2014-09-11 19:21:51', 10),
(4, 12, 11, 2, '2014-09-11 19:21:51', 14),
(5, 21, 22, 2, '2014-09-11 20:25:36', 11),
(6, 13, 17, 2, '2014-09-11 20:28:24', 10),
(7, 23, 24, 2, '2014-09-11 20:32:12', 10);

-- --------------------------------------------------------

--
-- Structure de la table `Word`
--

CREATE TABLE IF NOT EXISTS `Word` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `article_id` int(11) DEFAULT NULL,
  `language_id` int(11) NOT NULL,
  `value` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `wordType_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_63C3DA2F7294869C` (`article_id`),
  KEY `IDX_63C3DA2F811B8602` (`wordType_id`),
  KEY `IDX_63C3DA2F82F1BAF4` (`language_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=25 ;

--
-- Contenu de la table `Word`
--

INSERT INTO `Word` (`id`, `article_id`, `language_id`, `value`, `wordType_id`) VALUES
(11, NULL, 8, 'surtout', 14),
(12, NULL, 7, 'principalmente', 14),
(13, 9, 7, 'pan', 10),
(14, 10, 7, 'manzana', 10),
(15, 10, 7, 'mujer', 10),
(16, 9, 7, 'hombre', 10),
(17, 14, 8, 'pain', 10),
(18, 15, 8, 'pomme', 10),
(19, 16, 8, 'homme', 10),
(20, 15, 8, 'mère', 10),
(21, NULL, 7, 'morgane es cool', 11),
(22, NULL, 8, 'Morgane est cool', 11),
(23, 10, 7, 'Banana', 10),
(24, 15, 8, 'Banane', 10);

-- --------------------------------------------------------

--
-- Structure de la table `word_type`
--

CREATE TABLE IF NOT EXISTS `word_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `value` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=19 ;

--
-- Contenu de la table `word_type`
--

INSERT INTO `word_type` (`id`, `value`) VALUES
(10, 'name'),
(11, 'sentence'),
(12, 'preposition'),
(13, 'verb'),
(14, 'adverb'),
(15, 'article'),
(16, 'conjunction'),
(17, 'pronoun'),
(18, 'adjective');

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `Article`
--
ALTER TABLE `Article`
  ADD CONSTRAINT `FK_CD8737FA82F1BAF4` FOREIGN KEY (`language_id`) REFERENCES `Language` (`id`);

--
-- Contraintes pour la table `Learning`
--
ALTER TABLE `Learning`
  ADD CONSTRAINT `FK_3673C6946C3EEA2C` FOREIGN KEY (`language1_id`) REFERENCES `Language` (`id`),
  ADD CONSTRAINT `FK_3673C6947E8B45C2` FOREIGN KEY (`language2_id`) REFERENCES `Language` (`id`);

--
-- Contraintes pour la table `ovski_article`
--
ALTER TABLE `ovski_article`
  ADD CONSTRAINT `FK_40D7A10582F1BAF4` FOREIGN KEY (`language_id`) REFERENCES `ovski_language` (`id`);

--
-- Contraintes pour la table `ovski_learning`
--
ALTER TABLE `ovski_learning`
  ADD CONSTRAINT `FK_1BFC798F6C3EEA2C` FOREIGN KEY (`language1_id`) REFERENCES `ovski_language` (`id`),
  ADD CONSTRAINT `FK_1BFC798F7E8B45C2` FOREIGN KEY (`language2_id`) REFERENCES `ovski_language` (`id`);

--
-- Contraintes pour la table `ovski_translation`
--
ALTER TABLE `ovski_translation`
  ADD CONSTRAINT `FK_3CDCAAC24586854D` FOREIGN KEY (`word1_id`) REFERENCES `ovski_word` (`id`),
  ADD CONSTRAINT `FK_3CDCAAC24E6B0AB3` FOREIGN KEY (`learning_id`) REFERENCES `ovski_learning` (`id`),
  ADD CONSTRAINT `FK_3CDCAAC257332AA3` FOREIGN KEY (`word2_id`) REFERENCES `ovski_word` (`id`),
  ADD CONSTRAINT `FK_3CDCAAC2811B8602` FOREIGN KEY (`wordType_id`) REFERENCES `ovski_word_type` (`id`),
  ADD CONSTRAINT `FK_3CDCAAC2A76ED395` FOREIGN KEY (`user_id`) REFERENCES `ovski_user` (`id`);

--
-- Contraintes pour la table `ovski_user_learning`
--
ALTER TABLE `ovski_user_learning`
  ADD CONSTRAINT `FK_494F53A4E6B0AB3` FOREIGN KEY (`learning_id`) REFERENCES `ovski_learning` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_494F53AA76ED395` FOREIGN KEY (`user_id`) REFERENCES `ovski_user` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `ovski_user_word`
--
ALTER TABLE `ovski_user_word`
  ADD CONSTRAINT `FK_B1C9FD28A76ED395` FOREIGN KEY (`user_id`) REFERENCES `ovski_user` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_B1C9FD28E357438D` FOREIGN KEY (`word_id`) REFERENCES `ovski_word` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `ovski_word`
--
ALTER TABLE `ovski_word`
  ADD CONSTRAINT `FK_9CBCAF3D7294869C` FOREIGN KEY (`article_id`) REFERENCES `ovski_article` (`id`),
  ADD CONSTRAINT `FK_9CBCAF3D811B8602` FOREIGN KEY (`wordType_id`) REFERENCES `ovski_word_type` (`id`),
  ADD CONSTRAINT `FK_9CBCAF3D82F1BAF4` FOREIGN KEY (`language_id`) REFERENCES `ovski_language` (`id`);

--
-- Contraintes pour la table `Translation`
--
ALTER TABLE `Translation`
  ADD CONSTRAINT `FK_32F5CAB84586854D` FOREIGN KEY (`word1_id`) REFERENCES `Word` (`id`),
  ADD CONSTRAINT `FK_32F5CAB84E6B0AB3` FOREIGN KEY (`learning_id`) REFERENCES `Learning` (`id`),
  ADD CONSTRAINT `FK_32F5CAB857332AA3` FOREIGN KEY (`word2_id`) REFERENCES `Word` (`id`),
  ADD CONSTRAINT `FK_32F5CAB8811B8602` FOREIGN KEY (`wordType_id`) REFERENCES `word_type` (`id`);

--
-- Contraintes pour la table `Word`
--
ALTER TABLE `Word`
  ADD CONSTRAINT `FK_63C3DA2F7294869C` FOREIGN KEY (`article_id`) REFERENCES `Article` (`id`),
  ADD CONSTRAINT `FK_63C3DA2F811B8602` FOREIGN KEY (`wordType_id`) REFERENCES `word_type` (`id`),
  ADD CONSTRAINT `FK_63C3DA2F82F1BAF4` FOREIGN KEY (`language_id`) REFERENCES `Language` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
