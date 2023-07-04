-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mar. 04 juil. 2023 à 17:05
-- Version du serveur : 10.4.27-MariaDB
-- Version de PHP : 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `exenmissions`
--

-- --------------------------------------------------------

--
-- Structure de la table `frais`
--

CREATE TABLE `frais` (
  `IdFrais` int(11) NOT NULL,
  `LibelléFrais` varchar(50) NOT NULL,
  `MontantFrais` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `frais`
--

INSERT INTO `frais` (`IdFrais`, `LibelléFrais`, `MontantFrais`) VALUES
(1, 'Déjeuner', 30),
(2, 'Petit-Déjeuner', 25),
(8, 'Dîner', 30);

-- --------------------------------------------------------

--
-- Structure de la table `groupes`
--

CREATE TABLE `groupes` (
  `IdG` int(11) NOT NULL,
  `Libellé` varchar(100) NOT NULL,
  `TauxG` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `groupes`
--

INSERT INTO `groupes` (`IdG`, `Libellé`, `TauxG`) VALUES
(1, 'Aucun', 0);

-- --------------------------------------------------------

--
-- Structure de la table `historique`
--

CREATE TABLE `historique` (
  `IdAction` int(11) NOT NULL,
  `TypeAction` varchar(255) NOT NULL,
  `DateAction` text NOT NULL,
  `ElementAction` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `historique`
--

INSERT INTO `historique` (`IdAction`, `TypeAction`, `DateAction`, `ElementAction`) VALUES
(1, 'Déconnexion', '04/07/2023 16:01:57', ''),
(2, 'Modification', '04/07/2023 16:04:08', 'Collaborateur 1'),
(3, 'Connexion', '04/07/2023 16:04:17', ''),
(4, 'Déconnexion', '04/07/2023 16:04:35', ''),
(5, 'Connexion', '04/07/2023 16:04:37', ''),
(6, 'Déconnexion', '04/07/2023 16:05:16', '');

-- --------------------------------------------------------

--
-- Structure de la table `membres`
--

CREATE TABLE `membres` (
  `IdMb` int(11) NOT NULL,
  `IdG` int(11) NOT NULL,
  `Statut` tinyint(4) NOT NULL,
  `Nom` varchar(100) NOT NULL,
  `Prénom` varchar(100) NOT NULL,
  `TitreCivilité` varchar(100) NOT NULL,
  `Email` varchar(150) NOT NULL,
  `Mdps` text NOT NULL,
  `CIN` varchar(10) NOT NULL,
  `Profil` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `membres`
--

INSERT INTO `membres` (`IdMb`, `IdG`, `Statut`, `Nom`, `Prénom`, `TitreCivilité`, `Email`, `Mdps`, `CIN`, `Profil`) VALUES
(1, 1, 1, 'admin', 'admin', 'M.', 'admin@gmail.com', '$2y$10$oNrByf6sfExyJz4mhkEad.xKgetHEhO..Jqfhc8Yrkgw78ZfDvQUK', 'F678337', 'admin');

-- --------------------------------------------------------

--
-- Structure de la table `missions`
--

CREATE TABLE `missions` (
  `IdMiss` int(11) NOT NULL,
  `RéfMiss` varchar(100) NOT NULL,
  `IdMb` int(11) NOT NULL,
  `ObjMiss` text NOT NULL,
  `LieuDép` varchar(50) NOT NULL,
  `MoyTrans` varchar(50) NOT NULL,
  `Départ` text NOT NULL,
  `Retour` text NOT NULL,
  `Durée` double DEFAULT NULL,
  `DateMiss` text NOT NULL,
  `TypeMiss` varchar(50) NOT NULL,
  `Montant` double DEFAULT NULL,
  `IdPaiement` int(11) DEFAULT NULL,
  `Accomp` varchar(100) DEFAULT NULL,
  `Note` text NOT NULL,
  `StatutMiss` tinyint(4) NOT NULL,
  `OrdreMiss` varchar(255) DEFAULT NULL,
  `DemandeRemb` varchar(255) DEFAULT NULL,
  `DeletedAt` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `paiement`
--

CREATE TABLE `paiement` (
  `IdPaiement` int(11) NOT NULL,
  `TypePaiement` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `paiement`
--

INSERT INTO `paiement` (`IdPaiement`, `TypePaiement`) VALUES
(1, 'chèque'),
(2, 'espèces'),
(3, 'virement bancaire\n');

-- --------------------------------------------------------

--
-- Structure de la table `piècesjointes`
--

CREATE TABLE `piècesjointes` (
  `IdPJ` int(11) NOT NULL,
  `IdMiss` int(11) NOT NULL,
  `IdFrais` int(11) NOT NULL,
  `DescriptionPJ` varchar(200) NOT NULL,
  `NomPJ` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `frais`
--
ALTER TABLE `frais`
  ADD PRIMARY KEY (`IdFrais`);

--
-- Index pour la table `groupes`
--
ALTER TABLE `groupes`
  ADD PRIMARY KEY (`IdG`);

--
-- Index pour la table `historique`
--
ALTER TABLE `historique`
  ADD PRIMARY KEY (`IdAction`);

--
-- Index pour la table `membres`
--
ALTER TABLE `membres`
  ADD PRIMARY KEY (`IdMb`),
  ADD KEY `IdG` (`IdG`);

--
-- Index pour la table `missions`
--
ALTER TABLE `missions`
  ADD PRIMARY KEY (`IdMiss`),
  ADD KEY `IdCollab` (`IdMb`),
  ADD KEY `IdPaiement` (`IdPaiement`);

--
-- Index pour la table `paiement`
--
ALTER TABLE `paiement`
  ADD PRIMARY KEY (`IdPaiement`);

--
-- Index pour la table `piècesjointes`
--
ALTER TABLE `piècesjointes`
  ADD PRIMARY KEY (`IdPJ`),
  ADD KEY `IdMiss` (`IdMiss`),
  ADD KEY `IdFrais` (`IdFrais`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `frais`
--
ALTER TABLE `frais`
  MODIFY `IdFrais` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `groupes`
--
ALTER TABLE `groupes`
  MODIFY `IdG` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `historique`
--
ALTER TABLE `historique`
  MODIFY `IdAction` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `membres`
--
ALTER TABLE `membres`
  MODIFY `IdMb` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `missions`
--
ALTER TABLE `missions`
  MODIFY `IdMiss` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `paiement`
--
ALTER TABLE `paiement`
  MODIFY `IdPaiement` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `piècesjointes`
--
ALTER TABLE `piècesjointes`
  MODIFY `IdPJ` int(11) NOT NULL AUTO_INCREMENT;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `membres`
--
ALTER TABLE `membres`
  ADD CONSTRAINT `membres_ibfk_1` FOREIGN KEY (`IdG`) REFERENCES `groupes` (`IdG`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `missions`
--
ALTER TABLE `missions`
  ADD CONSTRAINT `missions_ibfk_1` FOREIGN KEY (`IdMb`) REFERENCES `membres` (`IdMb`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `missions_ibfk_2` FOREIGN KEY (`IdPaiement`) REFERENCES `paiement` (`IdPaiement`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `piècesjointes`
--
ALTER TABLE `piècesjointes`
  ADD CONSTRAINT `piècesjointes_ibfk_1` FOREIGN KEY (`IdMiss`) REFERENCES `missions` (`IdMiss`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `piècesjointes_ibfk_2` FOREIGN KEY (`IdFrais`) REFERENCES `frais` (`IdFrais`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
