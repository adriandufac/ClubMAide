-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : jeu. 04 août 2022 à 12:56
-- Version du serveur : 5.7.36
-- Version de PHP : 7.4.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `projet-eni`
--

-- --------------------------------------------------------

--
-- Structure de la table `campus`
--

DROP TABLE IF EXISTS `campus`;
CREATE TABLE IF NOT EXISTS `campus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `campus`
--

INSERT INTO `campus` (`id`, `nom`) VALUES
(9, 'Université Rennes 2'),
(10, 'Lille-campus Moulin'),
(11, 'Caen-Campus 4'),
(12, 'Lyon saint-Irénée'),
(13, 'IUT Nantes'),
(14, 'Angers Saumur'),
(15, 'Toulouse 1 Capitole'),
(16, 'Paris Cité'),
(17, 'Bordeaux Campus Talence'),
(18, 'Université Strasbourg'),
(19, 'Sciences Po Nancy'),
(20, 'Montpellier Campus Saint Priest'),
(21, 'Brest Open Campus'),
(22, 'Campus Eductive Grenoble'),
(23, 'Université de Bourgogne Dijon'),
(24, 'Campus Saint Charles Marseille'),
(25, 'Campus Reims'),
(26, 'CentraleSupélec Metz'),
(27, 'Université Clermont Auvergne'),
(28, 'Campus Sport Besançon');

-- --------------------------------------------------------

--
-- Structure de la table `doctrine_migration_versions`
--

DROP TABLE IF EXISTS `doctrine_migration_versions`;
CREATE TABLE IF NOT EXISTS `doctrine_migration_versions` (
  `version` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `doctrine_migration_versions`
--

INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20220802091225', '2022-08-02 09:24:46', 307),
('DoctrineMigrations\\Version20220802094959', '2022-08-02 10:00:38', 891),
('DoctrineMigrations\\Version20220802110909', '2022-08-02 12:38:21', 28),
('DoctrineMigrations\\Version20220802193524', '2022-08-03 07:58:39', 4436),
('DoctrineMigrations\\Version20220802194323', '2022-08-03 08:12:48', 299),
('DoctrineMigrations\\Version20220803131556', '2022-08-03 13:22:15', 256);

-- --------------------------------------------------------

--
-- Structure de la table `etat`
--

DROP TABLE IF EXISTS `etat`;
CREATE TABLE IF NOT EXISTS `etat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `libelle` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `etat`
--

INSERT INTO `etat` (`id`, `libelle`) VALUES
(1, 'Créée'),
(2, 'Ouverte'),
(3, 'Clôturée'),
(4, 'Activité en cours'),
(5, 'Passée'),
(6, 'Annulée');

-- --------------------------------------------------------

--
-- Structure de la table `lieu`
--

DROP TABLE IF EXISTS `lieu`;
CREATE TABLE IF NOT EXISTS `lieu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(55) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rue` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `latitude` double NOT NULL,
  `longitude` double NOT NULL,
  `ville_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_2F577D59A73F0036` (`ville_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `lieu`
--

INSERT INTO `lieu` (`id`, `nom`, `rue`, `latitude`, `longitude`, `ville_id`) VALUES
(3, 'Camping', 'camping du fion', 1, 2, 20);

-- --------------------------------------------------------

--
-- Structure de la table `messenger_messages`
--

DROP TABLE IF EXISTS `messenger_messages`;
CREATE TABLE IF NOT EXISTS `messenger_messages` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `body` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `headers` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue_name` varchar(190) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `available_at` datetime NOT NULL,
  `delivered_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_75EA56E0FB7336F0` (`queue_name`),
  KEY `IDX_75EA56E0E3BD61CE` (`available_at`),
  KEY `IDX_75EA56E016BA31DB` (`delivered_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `participant`
--

DROP TABLE IF EXISTS `participant`;
CREATE TABLE IF NOT EXISTS `participant` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(55) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prenom` varchar(55) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telephone` int(11) NOT NULL,
  `mail` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mot_passe` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `administrateur` tinyint(1) NOT NULL,
  `actif` tinyint(1) NOT NULL,
  `campus_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_D79F6B11AF5D55E1` (`campus_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `sortie`
--

DROP TABLE IF EXISTS `sortie`;
CREATE TABLE IF NOT EXISTS `sortie` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_heure_debut` datetime NOT NULL,
  `duree` int(11) NOT NULL,
  `date_limite_inscription` datetime NOT NULL,
  `nb_inscriptions_max` int(11) NOT NULL,
  `infos_sortie` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `campus_id` int(11) NOT NULL,
  `user_organisateur_id` int(11) NOT NULL,
  `etat_id` int(11) NOT NULL,
  `lieu_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_3C3FD3F2AF5D55E1` (`campus_id`),
  KEY `IDX_3C3FD3F2D5E86FF` (`etat_id`),
  KEY `IDX_3C3FD3F26AB213CC` (`lieu_id`),
  KEY `IDX_3C3FD3F2CBEADE75` (`user_organisateur_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `sortie`
--

INSERT INTO `sortie` (`id`, `nom`, `date_heure_debut`, `duree`, `date_limite_inscription`, `nb_inscriptions_max`, `infos_sortie`, `campus_id`, `user_organisateur_id`, `etat_id`, `lieu_id`) VALUES
(3, 'Test', '2022-08-03 13:29:08', 5, '2022-08-03 13:29:08', 15, 'On zamuz', 20, 6, 5, 3),
(4, 'test2', '2022-08-03 14:00:20', 4, '2022-08-03 14:00:20', 18, 'test2', 14, 6, 5, 3),
(5, 'Piscine ', '2022-08-04 08:46:29', 8, '2022-08-03 10:46:29', 7, 'Piscine', 20, 6, 4, 3),
(6, 'barbecue', '2022-08-04 10:18:34', 1, '2022-08-03 11:18:34', 15, 'barbecue', 17, 6, 5, 3);

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` longtext COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '(DC2Type:json)',
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prenom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pseudo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telephone` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `administrateur` tinyint(1) NOT NULL,
  `actif` tinyint(1) NOT NULL,
  `campus_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`),
  UNIQUE KEY `UNIQ_8D93D64986CC499D` (`pseudo`),
  KEY `IDX_8D93D649AF5D55E1` (`campus_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `email`, `roles`, `password`, `nom`, `prenom`, `pseudo`, `telephone`, `administrateur`, `actif`, `campus_id`) VALUES
(6, 'dede@dede.fr', '[\"ROLE_ADMIN\"]', '$2y$13$QgMiyocR8JF0pzohuX7yZ.ShKU6tE0uZnlBYpo0VLA3aa4u4tE9MS', 'dede', 'dede', 'dede', '0000000000', 1, 0, 20);

-- --------------------------------------------------------

--
-- Structure de la table `user_sortie`
--

DROP TABLE IF EXISTS `user_sortie`;
CREATE TABLE IF NOT EXISTS `user_sortie` (
  `user_id` int(11) NOT NULL,
  `sortie_id` int(11) NOT NULL,
  PRIMARY KEY (`user_id`,`sortie_id`),
  KEY `IDX_596DC8CFA76ED395` (`user_id`),
  KEY `IDX_596DC8CFCC72D953` (`sortie_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `user_sortie`
--

INSERT INTO `user_sortie` (`user_id`, `sortie_id`) VALUES
(6, 3);

-- --------------------------------------------------------

--
-- Structure de la table `ville`
--

DROP TABLE IF EXISTS `ville`;
CREATE TABLE IF NOT EXISTS `ville` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(55) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code_postal` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `ville`
--

INSERT INTO `ville` (`id`, `nom`, `code_postal`) VALUES
(9, 'Rennes', 35000),
(10, 'Lille', 59000),
(11, 'Caen', 14000),
(12, 'Lyon', 69001),
(13, 'Nantes', 44000),
(14, 'Angers', 49000),
(15, 'Toulouse', 31000),
(16, 'Paris', 75001),
(17, 'Bordeaux', 33000),
(18, 'Strasbourg', 67000),
(19, 'Nancy', 54000),
(20, 'Montpellier', 34000),
(21, 'Brest', 29200),
(22, 'Grenoble', 38000),
(23, 'Dijon', 21000),
(24, 'Marseille', 13001),
(25, 'Reims', 51100),
(26, 'Metz', 57000),
(27, 'Clermont-Ferrand', 63000),
(28, 'Besançon', 25000);

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `lieu`
--
ALTER TABLE `lieu`
  ADD CONSTRAINT `FK_2F577D59A73F0036` FOREIGN KEY (`ville_id`) REFERENCES `ville` (`id`);

--
-- Contraintes pour la table `participant`
--
ALTER TABLE `participant`
  ADD CONSTRAINT `FK_D79F6B11AF5D55E1` FOREIGN KEY (`campus_id`) REFERENCES `campus` (`id`);

--
-- Contraintes pour la table `sortie`
--
ALTER TABLE `sortie`
  ADD CONSTRAINT `FK_3C3FD3F26AB213CC` FOREIGN KEY (`lieu_id`) REFERENCES `lieu` (`id`),
  ADD CONSTRAINT `FK_3C3FD3F2AF5D55E1` FOREIGN KEY (`campus_id`) REFERENCES `campus` (`id`),
  ADD CONSTRAINT `FK_3C3FD3F2CBEADE75` FOREIGN KEY (`user_organisateur_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_3C3FD3F2D5E86FF` FOREIGN KEY (`etat_id`) REFERENCES `etat` (`id`);

--
-- Contraintes pour la table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `FK_8D93D649AF5D55E1` FOREIGN KEY (`campus_id`) REFERENCES `campus` (`id`);

--
-- Contraintes pour la table `user_sortie`
--
ALTER TABLE `user_sortie`
  ADD CONSTRAINT `FK_596DC8CFA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_596DC8CFCC72D953` FOREIGN KEY (`sortie_id`) REFERENCES `sortie` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
