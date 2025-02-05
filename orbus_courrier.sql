-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : lun. 01 avr. 2024 à 14:15
-- Version du serveur : 5.7.24
-- Version de PHP : 8.2.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `orbus_courrier`
--

-- --------------------------------------------------------

--
-- Structure de la table `activity_log`
--

CREATE TABLE `activity_log` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `log_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `event` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `causer_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `causer_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `properties` json DEFAULT NULL,
  `batch_uuid` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `activity_log`
--

INSERT INTO `activity_log` (`id`, `log_name`, `description`, `subject_type`, `event`, `subject_id`, `causer_type`, `causer_id`, `properties`, `batch_uuid`, `created_at`, `updated_at`) VALUES
(1, 'default', 'created', 'App\\Models\\Document', 'created', '1', 'App\\Models\\User', '9b7a22da-8e1c-4acf-b9af-e077bb899b83', '{\"attributes\": {\"doc_path\": \"doc-attachments/1/contract-Amethyst-Oneal-v0.10.docx\"}}', NULL, '2024-03-04 17:34:30', '2024-03-04 17:34:30'),
(2, 'default', 'updated', 'App\\Models\\Document', 'updated', '1', 'App\\Models\\User', '9b7a22da-8e1c-4acf-b9af-e077bb899b83', '{\"old\": {\"doc_path\": \"doc-attachments/1/contract-Amethyst-Oneal-v0.10.docx\"}, \"attributes\": {\"doc_path\": \"doc-attachments/1/contract-Amethyst-Oneal-v0.10.docx\"}}', NULL, '2024-03-04 17:34:55', '2024-03-04 17:34:55'),
(3, 'default', 'created', 'App\\Models\\Document', 'created', '2', 'App\\Models\\User', '9b7a22da-8e1c-4acf-b9af-e077bb899b83', '{\"attributes\": {\"doc_path\": \"doc-attachments/2/memorandum-DOC-N-TEST-v0.10.docx\"}}', NULL, '2024-03-12 16:25:08', '2024-03-12 16:25:08'),
(4, 'default', 'updated', 'App\\Models\\Document', 'updated', '2', 'App\\Models\\User', '9b7a22da-8e1c-4acf-b9af-e077bb899b83', '{\"old\": {\"doc_path\": \"doc-attachments/2/memorandum-DOC-N-TEST-v0.10.docx\"}, \"attributes\": {\"doc_path\": \"doc-attachments/2/memorandum-DOC-N-TEST-v0.10.docx\"}}', NULL, '2024-03-12 16:25:22', '2024-03-12 16:25:22'),
(5, 'default', 'updated', 'App\\Models\\Document', 'updated', '2', 'App\\Models\\User', '9b7a22df-c67f-4bfd-9781-12624beba8ee', '{\"old\": {\"doc_path\": \"doc-attachments/2/memorandum-DOC-N-TEST-v0.10.docx\"}, \"attributes\": {\"doc_path\": \"doc-attachments/2/memorandum-DOC-N-TEST-v0.10.docx\"}}', NULL, '2024-03-12 16:30:00', '2024-03-12 16:30:00'),
(6, 'default', 'updated', 'App\\Models\\Document', 'updated', '2', 'App\\Models\\User', '9b7a22df-c67f-4bfd-9781-12624beba8ee', '{\"old\": {\"doc_path\": \"doc-attachments/2/memorandum-DOC-N-TEST-v0.10.docx\"}, \"attributes\": {\"doc_path\": \"doc-attachments/2/memorandum-DOC-N-TEST-v0.10.docx\"}}', NULL, '2024-03-12 16:30:00', '2024-03-12 16:30:00'),
(7, 'default', 'updated', 'App\\Models\\Document', 'updated', '2', 'App\\Models\\User', '9b7a22df-c67f-4bfd-9781-12624beba8ee', '{\"old\": {\"doc_path\": \"doc-attachments/2/memorandum-DOC-N-TEST-v0.10.docx\"}, \"attributes\": {\"doc_path\": \"doc-attachments/2/memorandum-DOC-N-TEST-v0.10.pdf\"}}', NULL, '2024-03-12 16:33:33', '2024-03-12 16:33:33'),
(8, 'default', 'created', 'App\\Models\\Document', 'created', '3', 'App\\Models\\User', '9b7a22df-c67f-4bfd-9781-12624beba8ee', '{\"attributes\": {\"doc_path\": \"doc-attachments/3/memorandum-DOC-PARAPH-v0.10.docx\"}}', NULL, '2024-03-12 16:39:29', '2024-03-12 16:39:29'),
(9, 'default', 'updated', 'App\\Models\\Document', 'updated', '3', 'App\\Models\\User', '9b7a22df-c67f-4bfd-9781-12624beba8ee', '{\"old\": {\"doc_path\": \"doc-attachments/3/memorandum-DOC-PARAPH-v0.10.docx\"}, \"attributes\": {\"doc_path\": \"doc-attachments/3/memorandum-DOC-PARAPH-v02.docx\"}}', NULL, '2024-03-12 16:45:54', '2024-03-12 16:45:54'),
(10, 'default', 'created', 'App\\Models\\Document', 'created', '4', 'App\\Models\\User', '9b7a22df-c67f-4bfd-9781-12624beba8ee', '{\"attributes\": {\"doc_path\": \"doc-attachments/4/memorandum-Document--Test-A-v0.10.docx\"}}', NULL, '2024-03-12 21:07:44', '2024-03-12 21:07:44'),
(11, 'default', 'updated', 'App\\Models\\Document', 'updated', '4', 'App\\Models\\User', '9b7a22df-c67f-4bfd-9781-12624beba8ee', '{\"old\": {\"doc_path\": \"doc-attachments/4/memorandum-Document--Test-A-v0.10.docx\"}, \"attributes\": {\"doc_path\": \"doc-attachments/4/memorandum-Document--Test-A-v0.10.docx\"}}', NULL, '2024-03-12 21:08:10', '2024-03-12 21:08:10'),
(12, 'default', 'updated', 'App\\Models\\Document', 'updated', '4', 'App\\Models\\User', '9b7a22df-c67f-4bfd-9781-12624beba8ee', '{\"old\": {\"doc_path\": \"doc-attachments/4/memorandum-Document--Test-A-v0.10.docx\"}, \"attributes\": {\"doc_path\": \"doc-attachments/4/memorandum-Document--Test-A-v0.10.docx\"}}', NULL, '2024-03-12 21:08:21', '2024-03-12 21:08:21'),
(13, 'default', 'updated', 'App\\Models\\Document', 'updated', '4', 'App\\Models\\User', '9b7a22df-c67f-4bfd-9781-12624beba8ee', '{\"old\": {\"doc_path\": \"doc-attachments/4/memorandum-Document--Test-A-v0.10.docx\"}, \"attributes\": {\"doc_path\": \"doc-attachments/4/memorandum-Document--Test-A-v0.10.docx\"}}', NULL, '2024-03-12 21:08:21', '2024-03-12 21:08:21'),
(14, 'default', 'updated', 'App\\Models\\Document', 'updated', '4', 'App\\Models\\User', '9b7a22df-c67f-4bfd-9781-12624beba8ee', '{\"old\": {\"doc_path\": \"doc-attachments/4/memorandum-Document--Test-A-v0.10.docx\"}, \"attributes\": {\"doc_path\": \"doc-attachments/4/memorandum-Document--Test-A-v0.10.pdf\"}}', NULL, '2024-03-12 21:11:11', '2024-03-12 21:11:11'),
(15, 'default', 'created', 'App\\Models\\Document', 'created', '5', 'App\\Models\\User', '9b7a22da-8e1c-4acf-b9af-e077bb899b83', '{\"attributes\": {\"doc_path\": \"doc-attachments/5/contract-Document--Test-ABC-v0.10.docx\"}}', NULL, '2024-03-14 17:10:53', '2024-03-14 17:10:53'),
(16, 'default', 'updated', 'App\\Models\\Document', 'updated', '5', 'App\\Models\\User', '9b7a22da-8e1c-4acf-b9af-e077bb899b83', '{\"old\": {\"doc_path\": \"doc-attachments/5/contract-Document--Test-ABC-v0.10.docx\"}, \"attributes\": {\"doc_path\": \"doc-attachments/5/contract-Document--Test-ABC-v02.docx\"}}', NULL, '2024-03-14 17:13:52', '2024-03-14 17:13:52'),
(17, 'default', 'updated', 'App\\Models\\Document', 'updated', '5', 'App\\Models\\User', '9b7a22da-8e1c-4acf-b9af-e077bb899b83', '{\"old\": {\"doc_path\": \"doc-attachments/5/contract-Document--Test-ABC-v02.docx\"}, \"attributes\": {\"doc_path\": \"doc-attachments/5/contract-Document--Test-ABC-v02.docx\"}}', NULL, '2024-03-14 17:14:04', '2024-03-14 17:14:04'),
(18, 'default', 'updated', 'App\\Models\\Document', 'updated', '1', 'App\\Models\\User', '9b7a22df-15c4-4830-aeb2-1dde4c6ed27a', '{\"old\": {\"doc_path\": \"doc-attachments/1/contract-Amethyst-Oneal-v0.10.docx\"}, \"attributes\": {\"doc_path\": \"doc-attachments/1/contract-Amethyst-Oneal-v0.10.docx\"}}', NULL, '2024-03-25 13:59:22', '2024-03-25 13:59:22');

-- --------------------------------------------------------

--
-- Structure de la table `contacts`
--

CREATE TABLE `contacts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `recipient_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `entity` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `contacts`
--

INSERT INTO `contacts` (`id`, `recipient_id`, `name`, `email`, `phone`, `entity`, `created_at`, `updated_at`) VALUES
(1, 1, 'Kaci Lehner', 'elmer.purdy@example.net', '+1-607-648-7187', 'quod', '2024-03-03 17:58:59', '2024-03-03 17:58:59'),
(2, 1, 'Aurelie Anderson', 'gking@example.net', '1-910-928-0439', 'adipisci', '2024-03-03 17:58:59', '2024-03-03 17:59:00'),
(3, 1, 'Mrs. Alda Wisoky', 'ubrekke@example.org', '+1 (872) 534-8233', 'fugiat', '2024-03-03 17:58:59', '2024-03-03 17:59:00'),
(4, 1, 'Demario Brekke', 'loyal.moore@example.org', '+17374972254', 'sit', '2024-03-03 17:58:59', '2024-03-03 17:59:00'),
(5, 1, 'Prof. Isidro Rohan III', 'fabian91@example.com', '+16518068276', 'fuga', '2024-03-03 17:58:59', '2024-03-03 17:59:00'),
(6, 2, 'Bridgette Batz', 'wwintheiser@example.com', '(407) 955-0340', 'aut', '2024-03-03 17:59:00', '2024-03-03 17:59:00'),
(7, 2, 'Gus Kris MD', 'ada30@example.com', '+1-352-337-4514', 'a', '2024-03-03 17:59:00', '2024-03-03 17:59:00'),
(8, 2, 'Prof. Devon Wunsch DVM', 'brionna07@example.org', '1-762-237-0063', 'rerum', '2024-03-03 17:59:00', '2024-03-03 17:59:00'),
(9, 2, 'Ashleigh Rowe', 'shaylee68@example.com', '+1-352-252-4929', 'aut', '2024-03-03 17:59:00', '2024-03-03 17:59:00'),
(10, 2, 'Reina Bosco', 'qrunolfsson@example.org', '(559) 265-0072', 'quia', '2024-03-03 17:59:00', '2024-03-03 17:59:00'),
(11, 3, 'Webster Cummings III', 'blanche75@example.org', '+1-831-537-0339', 'laborum', '2024-03-03 17:59:00', '2024-03-03 17:59:00'),
(12, 3, 'Benny Daniel IV', 'klocko.gudrun@example.net', '+1-551-434-0545', 'quaerat', '2024-03-03 17:59:00', '2024-03-03 17:59:01'),
(13, 3, 'Dr. Kenton Nienow Jr.', 'norma.brown@example.org', '585.827.0428', 'molestias', '2024-03-03 17:59:00', '2024-03-03 17:59:01'),
(14, 3, 'Hilbert Conroy', 'amorar@example.com', '+1-864-896-5959', 'voluptatibus', '2024-03-03 17:59:00', '2024-03-03 17:59:01'),
(15, 3, 'Maribel Towne', 'gabbott@example.org', '1-802-900-5001', 'rem', '2024-03-03 17:59:00', '2024-03-03 17:59:01'),
(16, 4, 'Domenica Schuster', 'chauncey45@example.net', '940.914.8116', 'molestias', '2024-03-03 17:59:01', '2024-03-03 17:59:01'),
(17, 4, 'Eddie Reichert DVM', 'alexie36@example.net', '817-740-3572', 'sint', '2024-03-03 17:59:01', '2024-03-03 17:59:01'),
(18, 4, 'Alivia Cremin', 'wquigley@example.net', '(727) 908-2837', 'est', '2024-03-03 17:59:01', '2024-03-03 17:59:01'),
(19, 4, 'Dr. Clare Nikolaus', 'marquis87@example.org', '1-475-620-8926', 'sed', '2024-03-03 17:59:01', '2024-03-03 17:59:01'),
(20, 4, 'Dr. Eileen Hamill Sr.', 'rebeka32@example.org', '(574) 857-3602', 'in', '2024-03-03 17:59:01', '2024-03-03 17:59:01'),
(21, 5, 'Kailyn O\'Connell', 'glenna48@example.net', '+1-781-390-4821', 'sit', '2024-03-03 17:59:01', '2024-03-03 17:59:02'),
(22, 5, 'Miss Elda Sporer', 'julie87@example.com', '+1-262-310-6759', 'sint', '2024-03-03 17:59:01', '2024-03-03 17:59:02'),
(23, 5, 'Mackenzie Wintheiser', 'addie76@example.net', '+12064959770', 'in', '2024-03-03 17:59:01', '2024-03-03 17:59:02'),
(24, 5, 'Gregg Lynch', 'fbernier@example.net', '+1 (385) 303-9473', 'autem', '2024-03-03 17:59:02', '2024-03-03 17:59:02'),
(25, 5, 'Logan Erdman', 'lemke.cornell@example.com', '(503) 910-9793', 'voluptatem', '2024-03-03 17:59:02', '2024-03-03 17:59:02'),
(26, 6, 'Dariana Franecki', 'rosemarie92@example.net', '(463) 314-5729', 'et', '2024-03-03 17:59:02', '2024-03-03 17:59:02'),
(27, 6, 'Mr. Isom Blick', 'umorissette@example.net', '(628) 482-4770', 'dolores', '2024-03-03 17:59:02', '2024-03-03 17:59:02'),
(28, 6, 'Nicholas Murray', 'america.runolfsdottir@example.net', '+1 (520) 375-6971', 'ad', '2024-03-03 17:59:02', '2024-03-03 17:59:02'),
(29, 6, 'Mrs. Lizzie Hand', 'flavio.schultz@example.org', '+1 (757) 266-0548', 'porro', '2024-03-03 17:59:02', '2024-03-03 17:59:02'),
(30, 6, 'Hoyt Dicki', 'gia.dubuque@example.com', '479.331.7738', 'impedit', '2024-03-03 17:59:02', '2024-03-03 17:59:02'),
(31, 7, 'Cicero Bartell', 'ortiz.gene@example.com', '272.993.6352', 'natus', '2024-03-03 17:59:02', '2024-03-03 17:59:02'),
(32, 7, 'Carol Wisoky IV', 'jovan.cremin@example.org', '(979) 382-0955', 'in', '2024-03-03 17:59:02', '2024-03-03 17:59:02'),
(33, 7, 'Prof. Adella Fritsch V', 'katelynn20@example.com', '+1 (479) 363-2941', 'quis', '2024-03-03 17:59:02', '2024-03-03 17:59:02'),
(34, 7, 'Prof. Luella Tillman IV', 'lottie.schroeder@example.net', '(818) 440-4998', 'atque', '2024-03-03 17:59:02', '2024-03-03 17:59:03'),
(35, 7, 'Gaylord Simonis MD', 'carlotta49@example.com', '+1-304-288-3055', 'asperiores', '2024-03-03 17:59:02', '2024-03-03 17:59:03'),
(36, 8, 'Dr. Morton Gorczany PhD', 'sherwood98@example.net', '+1 (475) 537-9170', 'accusamus', '2024-03-03 17:59:03', '2024-03-03 17:59:03'),
(37, 8, 'Enrico Ratke V', 'mills.adelle@example.org', '707-627-2391', 'assumenda', '2024-03-03 17:59:03', '2024-03-03 17:59:03'),
(38, 8, 'Mr. Gay Ortiz', 'flossie94@example.com', '689-226-6115', 'quam', '2024-03-03 17:59:03', '2024-03-03 17:59:03'),
(39, 8, 'Domenico Hudson', 'simone.bernhard@example.com', '1-754-305-7015', 'iste', '2024-03-03 17:59:03', '2024-03-03 17:59:03'),
(40, 8, 'Prof. Arvilla Rutherford Jr.', 'bkiehn@example.net', '(331) 282-3434', 'quia', '2024-03-03 17:59:03', '2024-03-03 17:59:03'),
(41, 9, 'Libby Towne I', 'clifford.veum@example.com', '(267) 212-5795', 'qui', '2024-03-03 17:59:03', '2024-03-03 17:59:04'),
(42, 9, 'Saul Altenwerth', 'uparisian@example.net', '+15022130331', 'quasi', '2024-03-03 17:59:03', '2024-03-03 17:59:04'),
(43, 9, 'Richie Kiehn PhD', 'annalise52@example.com', '310-362-1441', 'molestiae', '2024-03-03 17:59:03', '2024-03-03 17:59:04'),
(44, 9, 'Florence Mayert Jr.', 'elisabeth75@example.net', '+1 (510) 790-9791', 'provident', '2024-03-03 17:59:04', '2024-03-03 17:59:04'),
(45, 9, 'Ernestine Becker', 'noah27@example.org', '843-593-4072', 'architecto', '2024-03-03 17:59:04', '2024-03-03 17:59:04'),
(46, 10, 'Velma Medhurst', 'bertha.lynch@example.com', '+1-470-351-0890', 'qui', '2024-03-03 17:59:04', '2024-03-03 17:59:05'),
(47, 10, 'Paul Gaylord II', 'michel.streich@example.org', '+1-254-987-9997', 'voluptatem', '2024-03-03 17:59:04', '2024-03-03 17:59:05'),
(48, 10, 'Prof. Tyrell Adams', 'gerry07@example.com', '936.623.3836', 'voluptatem', '2024-03-03 17:59:04', '2024-03-03 17:59:05'),
(49, 10, 'Katelyn Goyette', 'walter.jarod@example.org', '+1-214-255-7950', 'at', '2024-03-03 17:59:04', '2024-03-03 17:59:05'),
(50, 10, 'Dr. Bernita Cole', 'oswald73@example.net', '856.617.8380', 'quos', '2024-03-03 17:59:04', '2024-03-03 17:59:05');

-- --------------------------------------------------------

--
-- Structure de la table `couriers`
--

CREATE TABLE `couriers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `document_id` bigint(20) UNSIGNED NOT NULL,
  `created_by` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `courier_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `object` text COLLATE utf8mb4_unicode_ci,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `comment` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `attachments` text COLLATE utf8mb4_unicode_ci,
  `recipient_signature` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `closure_date` timestamp NULL DEFAULT NULL,
  `lat` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `long` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deposit_location_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `courier_created_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `couriers`
--

INSERT INTO `couriers` (`id`, `document_id`, `created_by`, `courier_number`, `object`, `status`, `comment`, `attachments`, `recipient_signature`, `closure_date`, `lat`, `long`, `deposit_location_name`, `courier_created_at`, `created_at`, `updated_at`) VALUES
(1, 2, '9b7a22df-c67f-4bfd-9781-12624beba8ee', '001/2024', NULL, 'draft', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-03-12 16:33:33', '2024-03-12 16:33:33'),
(2, 4, '9b7a22df-c67f-4bfd-9781-12624beba8ee', '002/2024', 'Courrier For Company X', 'initialized', NULL, '[]', NULL, NULL, NULL, NULL, NULL, '2024-03-20 10:48:01', '2024-03-12 21:11:11', '2024-03-20 10:48:01');

-- --------------------------------------------------------

--
-- Structure de la table `courier_user`
--

CREATE TABLE `courier_user` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `courier_id` bigint(20) UNSIGNED NOT NULL,
  `recipient_id` bigint(20) UNSIGNED NOT NULL,
  `contact_id` bigint(20) UNSIGNED DEFAULT NULL,
  `comment` text COLLATE utf8mb4_unicode_ci,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `order_column` int(11) DEFAULT NULL,
  `assignment_date` timestamp NULL DEFAULT NULL,
  `pickup_date` timestamp NULL DEFAULT NULL,
  `deposit_date` timestamp NULL DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `receipt_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rejection_motive` text COLLATE utf8mb4_unicode_ci,
  `notified` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `courier_user`
--

INSERT INTO `courier_user` (`id`, `user_id`, `courier_id`, `recipient_id`, `contact_id`, `comment`, `type`, `order_column`, `assignment_date`, `pickup_date`, `deposit_date`, `status`, `receipt_path`, `rejection_motive`, `notified`, `created_at`, `updated_at`) VALUES
(1, '9b7a22f3-b69d-4e52-89bd-13dfd8698a1f', 2, 1, NULL, NULL, 'main', NULL, '2024-03-20 10:48:01', NULL, NULL, 'initialized', NULL, NULL, 0, '2024-03-20 10:47:44', '2024-03-20 10:48:01');

-- --------------------------------------------------------

--
-- Structure de la table `documents`
--

CREATE TABLE `documents` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_by` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `external_doc_initiator_id` bigint(20) UNSIGNED DEFAULT NULL,
  `should_be_expedited` tinyint(1) NOT NULL DEFAULT '1',
  `doc_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `doc_urgency` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `object` text COLLATE utf8mb4_unicode_ci,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `doc_content` text COLLATE utf8mb4_unicode_ci,
  `doc_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `doc_created_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `documents`
--

INSERT INTO `documents` (`id`, `created_by`, `external_doc_initiator_id`, `should_be_expedited`, `doc_type`, `doc_urgency`, `name`, `object`, `status`, `doc_content`, `doc_path`, `doc_created_at`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, '9b7a22da-8e1c-4acf-b9af-e077bb899b83', NULL, 1, 'contract', 'urgent', 'Amethyst Oneal', 'Quaerat beatae culpa', 'validating', NULL, 'doc-attachments/1/contract-Amethyst-Oneal-v0.10.docx', '2024-03-04 17:34:55', NULL, '2024-03-04 17:34:29', '2024-03-25 13:59:22'),
(2, '9b7a22da-8e1c-4acf-b9af-e077bb899b83', NULL, 1, 'memorandum', 'normal', 'DOC N TEST', 'nb vvhbvbnjhj', 'signed', NULL, 'doc-attachments/2/memorandum-DOC-N-TEST-v0.10.pdf', '2024-03-12 16:25:22', NULL, '2024-03-12 16:25:08', '2024-03-12 16:33:33'),
(3, '9b7a22df-c67f-4bfd-9781-12624beba8ee', NULL, 1, 'memorandum', 'normal', 'DOC PARAPH', 'NOTE DE SERVICE', 'draft', NULL, 'doc-attachments/3/memorandum-DOC-PARAPH-v02.docx', NULL, NULL, '2024-03-12 16:39:29', '2024-03-12 16:45:54'),
(4, '9b7a22df-c67f-4bfd-9781-12624beba8ee', NULL, 1, 'memorandum', 'normal', 'Document  Test A', 'hvbnbjnj', 'signed', NULL, 'doc-attachments/4/memorandum-Document--Test-A-v0.10.pdf', '2024-03-12 21:08:10', NULL, '2024-03-12 21:07:44', '2024-03-12 21:11:11'),
(5, '9b7a22da-8e1c-4acf-b9af-e077bb899b83', NULL, 1, 'contract', 'normal', 'Document  Test ABC', 'TEST VARIABLES', 'initialized', NULL, 'doc-attachments/5/contract-Document--Test-ABC-v02.docx', '2024-03-14 17:14:04', NULL, '2024-03-14 17:10:53', '2024-03-14 17:14:04');

-- --------------------------------------------------------

--
-- Structure de la table `document_team`
--

CREATE TABLE `document_team` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `document_id` bigint(20) UNSIGNED NOT NULL,
  `team_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `document_user`
--

CREATE TABLE `document_user` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `document_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `action_request_date` timestamp NULL DEFAULT NULL,
  `comment` text COLLATE utf8mb4_unicode_ci,
  `order_column` int(11) DEFAULT NULL,
  `notified` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `document_user`
--

INSERT INTO `document_user` (`id`, `user_id`, `document_id`, `role_id`, `action_request_date`, `comment`, `order_column`, `notified`, `created_at`, `updated_at`) VALUES
(1, '9b7a22df-15c4-4830-aeb2-1dde4c6ed27a', 1, 10, '2024-03-04 17:34:55', NULL, 1, 1, '2024-03-04 17:34:30', '2024-03-05 14:04:51'),
(2, '9b7a22df-c67f-4bfd-9781-12624beba8ee', 1, 10, '2024-03-25 13:59:22', NULL, 2, 1, '2024-03-04 17:34:30', '2024-03-25 13:59:22'),
(3, '9b7a22e0-6d14-4723-9b2a-dcb57dca524a', 1, 10, '2024-03-04 17:34:30', NULL, 3, 1, '2024-03-04 17:34:30', '2024-03-05 14:04:54'),
(4, '9b7a22de-591e-4385-9fdc-f2d9c4c4ad51', 1, 9, NULL, NULL, 4, 0, '2024-03-04 17:34:30', '2024-03-04 17:34:30'),
(5, '9b7a22df-c67f-4bfd-9781-12624beba8ee', 2, 10, '2024-03-12 16:25:22', NULL, 1, 0, '2024-03-12 16:25:08', '2024-03-12 16:25:22'),
(6, '9b7a22df-c67f-4bfd-9781-12624beba8ee', 2, 12, NULL, NULL, 2, 1, '2024-03-12 16:25:08', '2024-03-12 16:34:13'),
(7, '9b7a22df-c67f-4bfd-9781-12624beba8ee', 2, 9, NULL, NULL, 3, 0, '2024-03-12 16:25:08', '2024-03-12 16:25:08'),
(17, '9b7a22df-c67f-4bfd-9781-12624beba8ee', 3, 9, NULL, NULL, NULL, 0, NULL, NULL),
(18, '9b7a22df-c67f-4bfd-9781-12624beba8ee', 4, 10, '2024-03-12 21:08:10', NULL, 1, 0, '2024-03-12 21:07:44', '2024-03-12 21:08:10'),
(19, '9b7a22df-c67f-4bfd-9781-12624beba8ee', 4, 12, NULL, NULL, 2, 0, '2024-03-12 21:07:44', '2024-03-12 21:07:44'),
(20, '9b7a22df-c67f-4bfd-9781-12624beba8ee', 4, 9, NULL, NULL, 3, 0, '2024-03-12 21:07:45', '2024-03-12 21:07:45'),
(21, '9b7a22df-15c4-4830-aeb2-1dde4c6ed27a', 5, 10, '2024-03-14 17:14:04', NULL, 1, 0, '2024-03-14 17:10:53', '2024-03-14 17:14:04'),
(22, '9b7a22df-c67f-4bfd-9781-12624beba8ee', 5, 12, NULL, NULL, 1, 0, '2024-03-14 17:10:53', '2024-03-14 17:13:52'),
(23, '9b7a22df-15c4-4830-aeb2-1dde4c6ed27a', 5, 9, NULL, NULL, 3, 0, '2024-03-14 17:10:53', '2024-03-14 17:10:53');

-- --------------------------------------------------------

--
-- Structure de la table `doc_histories`
--

CREATE TABLE `doc_histories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `document_id` bigint(20) UNSIGNED NOT NULL,
  `version` decimal(8,2) NOT NULL DEFAULT '0.10',
  `action` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci,
  `doc_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `doc_histories`
--

INSERT INTO `doc_histories` (`id`, `user_id`, `document_id`, `version`, `action`, `content`, `doc_path`, `created_at`, `updated_at`) VALUES
(1, '9b7a22de-591e-4385-9fdc-f2d9c4c4ad51', 1, '0.10', 'create', NULL, 'doc-attachments/1/contract-Amethyst-Oneal-v0.10.docx', '2024-03-04 17:34:30', '2024-03-04 17:34:30'),
(2, '9b7a22df-c67f-4bfd-9781-12624beba8ee', 2, '0.10', 'create', NULL, 'doc-attachments/2/memorandum-DOC-N-TEST-v0.10.docx', '2024-03-12 16:25:09', '2024-03-12 16:25:09'),
(3, '9b7a22df-c67f-4bfd-9781-12624beba8ee', 3, '0.10', 'create', NULL, 'doc-attachments/3/memorandum-DOC-PARAPH-v0.10.docx', '2024-03-12 16:39:29', '2024-03-12 16:39:29'),
(4, '9b7a22df-c67f-4bfd-9781-12624beba8ee', 4, '0.10', 'create', NULL, 'doc-attachments/4/memorandum-Document--Test-A-v0.10.docx', '2024-03-12 21:07:45', '2024-03-12 21:07:45'),
(5, '9b7a22df-15c4-4830-aeb2-1dde4c6ed27a', 5, '0.10', 'create', NULL, 'doc-attachments/5/contract-Document--Test-ABC-v0.10.docx', '2024-03-14 17:10:53', '2024-03-14 17:10:53');

-- --------------------------------------------------------

--
-- Structure de la table `doc_templates`
--

CREATE TABLE `doc_templates` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `doc_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `doc_templates`
--

INSERT INTO `doc_templates` (`id`, `file_path`, `doc_type`, `name`, `content`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'memorandum.docx', 'memorandum', 'Note de Service', NULL, NULL, '2024-03-03 17:58:55', '2024-03-03 17:58:55'),
(2, 'letter.docx', 'letter', 'Lettre', NULL, NULL, '2024-03-03 17:58:55', '2024-03-03 17:58:55'),
(3, 'contract.docx', 'contract', 'Contrat', NULL, NULL, '2024-03-03 17:58:55', '2024-03-03 17:58:55');

-- --------------------------------------------------------

--
-- Structure de la table `doc_validation_histories`
--

CREATE TABLE `doc_validation_histories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `document_id` bigint(20) UNSIGNED NOT NULL,
  `validation_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `doc_validation_histories`
--

INSERT INTO `doc_validation_histories` (`id`, `user_id`, `document_id`, `validation_date`, `is_active`, `created_at`, `updated_at`) VALUES
(1, '9b7a22df-c67f-4bfd-9781-12624beba8ee', 2, '2024-03-12 16:30:00', 1, '2024-03-12 16:30:00', '2024-03-12 16:30:00'),
(2, '9b7a22df-c67f-4bfd-9781-12624beba8ee', 4, '2024-03-12 21:08:21', 1, '2024-03-12 21:08:21', '2024-03-12 21:08:21'),
(3, '9b7a22df-15c4-4830-aeb2-1dde4c6ed27a', 1, '2024-03-25 13:59:22', 1, '2024-03-25 13:59:22', '2024-03-25 13:59:22');

-- --------------------------------------------------------

--
-- Structure de la table `external_doc_initiators`
--

CREATE TABLE `external_doc_initiators` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `logo_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `external_doc_initiators`
--

INSERT INTO `external_doc_initiators` (`id`, `name`, `phone`, `email`, `address`, `logo_url`, `created_at`, `updated_at`) VALUES
(1, 'Prof. Ryann Weissnat DDS', '385-866-8760', 'lilly.upton@example.org', '2504 Destany Harbor Apt. 309\nSouth Rebekah, NV 24138-8445', 'https://via.placeholder.com/640x480.png/00eeaa?text=delectus', '2024-03-03 17:59:05', '2024-03-03 17:59:05'),
(2, 'Miss Hattie Becker', '626.362.1938', 'jbogan@example.com', '8942 Antonina Well\nEast Asia, DC 33662', 'https://via.placeholder.com/640x480.png/00ee66?text=distinctio', '2024-03-03 17:59:05', '2024-03-03 17:59:05'),
(3, 'Vincent Walker', '+1.480.604.8051', 'pzemlak@example.com', '7029 Mathilde Village Apt. 589\nWest Catalina, SC 62047-1309', 'https://via.placeholder.com/640x480.png/00ccdd?text=et', '2024-03-03 17:59:05', '2024-03-03 17:59:05'),
(4, 'Jada Casper', '(325) 745-6363', 'stephania.turner@example.net', '882 Keyshawn Mount\nNorth Deontefort, KY 10949-2460', 'https://via.placeholder.com/640x480.png/0077ff?text=id', '2024-03-03 17:59:05', '2024-03-03 17:59:05'),
(5, 'Dr. Kaley King MD', '1-480-319-2681', 'adriel.jacobi@example.com', '563 Monte Corners Apt. 908\nNorth Wilberfort, MD 17276-9203', 'https://via.placeholder.com/640x480.png/00dd66?text=ut', '2024-03-03 17:59:05', '2024-03-03 17:59:05');

-- --------------------------------------------------------

--
-- Structure de la table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `failed_jobs`
--

INSERT INTO `failed_jobs` (`id`, `uuid`, `connection`, `queue`, `payload`, `exception`, `failed_at`) VALUES
(1, '9e447660-1655-47cd-a580-a2ca227f9758', 'database', 'default', '{\"uuid\":\"9e447660-1655-47cd-a580-a2ca227f9758\",\"displayName\":\"App\\\\Notifications\\\\PasswordResetNotification\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\",\"command\":\"O:48:\\\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\\\":3:{s:11:\\\"notifiables\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";a:1:{i:0;s:36:\\\"9b7a22df-c67f-4bfd-9781-12624beba8ee\\\";}s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:43:\\\"App\\\\Notifications\\\\PasswordResetNotification\\\":3:{s:49:\\\"\\u0000App\\\\Notifications\\\\PasswordResetNotification\\u0000code\\\";s:4:\\\"7811\\\";s:55:\\\"\\u0000App\\\\Notifications\\\\PasswordResetNotification\\u0000expiration\\\";O:25:\\\"Illuminate\\\\Support\\\\Carbon\\\":3:{s:4:\\\"date\\\";s:26:\\\"2024-03-05 22:34:32.000000\\\";s:13:\\\"timezone_type\\\";i:3;s:8:\\\"timezone\\\";s:3:\\\"UTC\\\";}s:2:\\\"id\\\";s:36:\\\"5fb4dd3a-f00d-44eb-b475-bf20c6415726\\\";}s:8:\\\"channels\\\";a:1:{i:0;s:4:\\\"mail\\\";}}\"}}', 'Symfony\\Component\\Mailer\\Exception\\TransportException: Connection could not be established with host \"ssl://smtp.googlemail.com:465\": stream_socket_client(): SSL: An existing connection was forcibly closed by the remote host in C:\\laragon\\www\\orbus_courier_server\\vendor\\symfony\\mailer\\Transport\\Smtp\\Stream\\SocketStream.php:154\nStack trace:\n#0 [internal function]: Symfony\\Component\\Mailer\\Transport\\Smtp\\Stream\\SocketStream->Symfony\\Component\\Mailer\\Transport\\Smtp\\Stream\\{closure}(2, \'stream_socket_c...\', \'C:\\\\laragon\\\\www\\\\...\', 157)\n#1 C:\\laragon\\www\\orbus_courier_server\\vendor\\symfony\\mailer\\Transport\\Smtp\\Stream\\SocketStream.php(157): stream_socket_client(\'ssl://smtp.goog...\', 0, \'\', 60.0, 4, Resource id #1852)\n#2 C:\\laragon\\www\\orbus_courier_server\\vendor\\symfony\\mailer\\Transport\\Smtp\\SmtpTransport.php(275): Symfony\\Component\\Mailer\\Transport\\Smtp\\Stream\\SocketStream->initialize()\n#3 C:\\laragon\\www\\orbus_courier_server\\vendor\\symfony\\mailer\\Transport\\Smtp\\SmtpTransport.php(213): Symfony\\Component\\Mailer\\Transport\\Smtp\\SmtpTransport->start()\n#4 C:\\laragon\\www\\orbus_courier_server\\vendor\\symfony\\mailer\\Transport\\AbstractTransport.php(69): Symfony\\Component\\Mailer\\Transport\\Smtp\\SmtpTransport->doSend(Object(Symfony\\Component\\Mailer\\SentMessage))\n#5 C:\\laragon\\www\\orbus_courier_server\\vendor\\symfony\\mailer\\Transport\\Smtp\\SmtpTransport.php(137): Symfony\\Component\\Mailer\\Transport\\AbstractTransport->send(Object(Symfony\\Component\\Mime\\Email), Object(Symfony\\Component\\Mailer\\DelayedEnvelope))\n#6 C:\\laragon\\www\\orbus_courier_server\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(573): Symfony\\Component\\Mailer\\Transport\\Smtp\\SmtpTransport->send(Object(Symfony\\Component\\Mime\\Email), Object(Symfony\\Component\\Mailer\\DelayedEnvelope))\n#7 C:\\laragon\\www\\orbus_courier_server\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(335): Illuminate\\Mail\\Mailer->sendSymfonyMessage(Object(Symfony\\Component\\Mime\\Email))\n#8 C:\\laragon\\www\\orbus_courier_server\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\Channels\\MailChannel.php(66): Illuminate\\Mail\\Mailer->send(Object(Closure), Array, Object(Closure))\n#9 C:\\laragon\\www\\orbus_courier_server\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\NotificationSender.php(148): Illuminate\\Notifications\\Channels\\MailChannel->send(Object(App\\Models\\User), Object(App\\Notifications\\PasswordResetNotification))\n#10 C:\\laragon\\www\\orbus_courier_server\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\NotificationSender.php(106): Illuminate\\Notifications\\NotificationSender->sendToNotifiable(Object(App\\Models\\User), \'e1cef311-72ba-4...\', Object(App\\Notifications\\PasswordResetNotification), \'mail\')\n#11 C:\\laragon\\www\\orbus_courier_server\\vendor\\laravel\\framework\\src\\Illuminate\\Support\\Traits\\Localizable.php(19): Illuminate\\Notifications\\NotificationSender->Illuminate\\Notifications\\{closure}()\n#12 C:\\laragon\\www\\orbus_courier_server\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\NotificationSender.php(101): Illuminate\\Notifications\\NotificationSender->withLocale(NULL, Object(Closure))\n#13 C:\\laragon\\www\\orbus_courier_server\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\ChannelManager.php(54): Illuminate\\Notifications\\NotificationSender->sendNow(Object(Illuminate\\Database\\Eloquent\\Collection), Object(App\\Notifications\\PasswordResetNotification), Array)\n#14 C:\\laragon\\www\\orbus_courier_server\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\SendQueuedNotifications.php(119): Illuminate\\Notifications\\ChannelManager->sendNow(Object(Illuminate\\Database\\Eloquent\\Collection), Object(App\\Notifications\\PasswordResetNotification), Array)\n#15 C:\\laragon\\www\\orbus_courier_server\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): Illuminate\\Notifications\\SendQueuedNotifications->handle(Object(Illuminate\\Notifications\\ChannelManager))\n#16 C:\\laragon\\www\\orbus_courier_server\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(41): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#17 C:\\laragon\\www\\orbus_courier_server\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(93): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#18 C:\\laragon\\www\\orbus_courier_server\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#19 C:\\laragon\\www\\orbus_courier_server\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(662): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#20 C:\\laragon\\www\\orbus_courier_server\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(128): Illuminate\\Container\\Container->call(Array)\n#21 C:\\laragon\\www\\orbus_courier_server\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(144): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(Illuminate\\Notifications\\SendQueuedNotifications))\n#22 C:\\laragon\\www\\orbus_courier_server\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(119): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(Illuminate\\Notifications\\SendQueuedNotifications))\n#23 C:\\laragon\\www\\orbus_courier_server\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(132): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#24 C:\\laragon\\www\\orbus_courier_server\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(123): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(Illuminate\\Notifications\\SendQueuedNotifications), false)\n#25 C:\\laragon\\www\\orbus_courier_server\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(144): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}(Object(Illuminate\\Notifications\\SendQueuedNotifications))\n#26 C:\\laragon\\www\\orbus_courier_server\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(119): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(Illuminate\\Notifications\\SendQueuedNotifications))\n#27 C:\\laragon\\www\\orbus_courier_server\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(122): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#28 C:\\laragon\\www\\orbus_courier_server\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(70): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Notifications\\SendQueuedNotifications))\n#29 C:\\laragon\\www\\orbus_courier_server\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Jobs\\Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)\n#30 C:\\laragon\\www\\orbus_courier_server\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(439): Illuminate\\Queue\\Jobs\\Job->fire()\n#31 C:\\laragon\\www\\orbus_courier_server\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(389): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))\n#32 C:\\laragon\\www\\orbus_courier_server\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(333): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))\n#33 C:\\laragon\\www\\orbus_courier_server\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(137): Illuminate\\Queue\\Worker->runNextJob(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))\n#34 C:\\laragon\\www\\orbus_courier_server\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(120): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')\n#35 C:\\laragon\\www\\orbus_courier_server\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#36 C:\\laragon\\www\\orbus_courier_server\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(41): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#37 C:\\laragon\\www\\orbus_courier_server\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(93): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#38 C:\\laragon\\www\\orbus_courier_server\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#39 C:\\laragon\\www\\orbus_courier_server\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(662): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#40 C:\\laragon\\www\\orbus_courier_server\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(211): Illuminate\\Container\\Container->call(Array)\n#41 C:\\laragon\\www\\orbus_courier_server\\vendor\\symfony\\console\\Command\\Command.php(326): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#42 C:\\laragon\\www\\orbus_courier_server\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(180): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#43 C:\\laragon\\www\\orbus_courier_server\\vendor\\symfony\\console\\Application.php(1096): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#44 C:\\laragon\\www\\orbus_courier_server\\vendor\\symfony\\console\\Application.php(324): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#45 C:\\laragon\\www\\orbus_courier_server\\vendor\\symfony\\console\\Application.php(175): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#46 C:\\laragon\\www\\orbus_courier_server\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Console\\Kernel.php(201): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#47 C:\\laragon\\www\\orbus_courier_server\\artisan(35): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#48 {main}', '2024-03-06 12:36:02'),
(2, 'c5c5e4f8-9875-405f-9f80-bd88484d5fc4', 'database', 'default', '{\"uuid\":\"c5c5e4f8-9875-405f-9f80-bd88484d5fc4\",\"displayName\":\"App\\\\Notifications\\\\PasswordResetNotification\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\",\"command\":\"O:48:\\\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\\\":3:{s:11:\\\"notifiables\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";a:1:{i:0;s:36:\\\"9b7a22df-c67f-4bfd-9781-12624beba8ee\\\";}s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:43:\\\"App\\\\Notifications\\\\PasswordResetNotification\\\":3:{s:49:\\\"\\u0000App\\\\Notifications\\\\PasswordResetNotification\\u0000code\\\";i:2814;s:55:\\\"\\u0000App\\\\Notifications\\\\PasswordResetNotification\\u0000expiration\\\";O:25:\\\"Illuminate\\\\Support\\\\Carbon\\\":3:{s:4:\\\"date\\\";s:26:\\\"2024-03-08 15:58:00.774384\\\";s:13:\\\"timezone_type\\\";i:3;s:8:\\\"timezone\\\";s:3:\\\"UTC\\\";}s:2:\\\"id\\\";s:36:\\\"0edd8d09-6dc3-4578-902f-cbc66708fcf4\\\";}s:8:\\\"channels\\\";a:1:{i:0;s:4:\\\"mail\\\";}}\"}}', 'Symfony\\Component\\Mailer\\Exception\\TransportException: Connection could not be established with host \"ssl://smtp.googlemail.com:465\": stream_socket_client(): Unable to connect to ssl://smtp.googlemail.com:465 (A connection attempt failed because the connected party did not properly respond after a period of time, or established connection failed because connected host has failed to respond) in C:\\laragon\\www\\orbus_courier_server\\vendor\\symfony\\mailer\\Transport\\Smtp\\Stream\\SocketStream.php:154\nStack trace:\n#0 [internal function]: Symfony\\Component\\Mailer\\Transport\\Smtp\\Stream\\SocketStream->Symfony\\Component\\Mailer\\Transport\\Smtp\\Stream\\{closure}(2, \'stream_socket_c...\', \'C:\\\\laragon\\\\www\\\\...\', 157)\n#1 C:\\laragon\\www\\orbus_courier_server\\vendor\\symfony\\mailer\\Transport\\Smtp\\Stream\\SocketStream.php(157): stream_socket_client(\'ssl://smtp.goog...\', 0, \'\', 60.0, 4, Resource id #1855)\n#2 C:\\laragon\\www\\orbus_courier_server\\vendor\\symfony\\mailer\\Transport\\Smtp\\SmtpTransport.php(275): Symfony\\Component\\Mailer\\Transport\\Smtp\\Stream\\SocketStream->initialize()\n#3 C:\\laragon\\www\\orbus_courier_server\\vendor\\symfony\\mailer\\Transport\\Smtp\\SmtpTransport.php(213): Symfony\\Component\\Mailer\\Transport\\Smtp\\SmtpTransport->start()\n#4 C:\\laragon\\www\\orbus_courier_server\\vendor\\symfony\\mailer\\Transport\\AbstractTransport.php(69): Symfony\\Component\\Mailer\\Transport\\Smtp\\SmtpTransport->doSend(Object(Symfony\\Component\\Mailer\\SentMessage))\n#5 C:\\laragon\\www\\orbus_courier_server\\vendor\\symfony\\mailer\\Transport\\Smtp\\SmtpTransport.php(137): Symfony\\Component\\Mailer\\Transport\\AbstractTransport->send(Object(Symfony\\Component\\Mime\\Email), Object(Symfony\\Component\\Mailer\\DelayedEnvelope))\n#6 C:\\laragon\\www\\orbus_courier_server\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(573): Symfony\\Component\\Mailer\\Transport\\Smtp\\SmtpTransport->send(Object(Symfony\\Component\\Mime\\Email), Object(Symfony\\Component\\Mailer\\DelayedEnvelope))\n#7 C:\\laragon\\www\\orbus_courier_server\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(335): Illuminate\\Mail\\Mailer->sendSymfonyMessage(Object(Symfony\\Component\\Mime\\Email))\n#8 C:\\laragon\\www\\orbus_courier_server\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\Channels\\MailChannel.php(66): Illuminate\\Mail\\Mailer->send(Object(Closure), Array, Object(Closure))\n#9 C:\\laragon\\www\\orbus_courier_server\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\NotificationSender.php(148): Illuminate\\Notifications\\Channels\\MailChannel->send(Object(App\\Models\\User), Object(App\\Notifications\\PasswordResetNotification))\n#10 C:\\laragon\\www\\orbus_courier_server\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\NotificationSender.php(106): Illuminate\\Notifications\\NotificationSender->sendToNotifiable(Object(App\\Models\\User), \'16683dc6-5874-4...\', Object(App\\Notifications\\PasswordResetNotification), \'mail\')\n#11 C:\\laragon\\www\\orbus_courier_server\\vendor\\laravel\\framework\\src\\Illuminate\\Support\\Traits\\Localizable.php(19): Illuminate\\Notifications\\NotificationSender->Illuminate\\Notifications\\{closure}()\n#12 C:\\laragon\\www\\orbus_courier_server\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\NotificationSender.php(101): Illuminate\\Notifications\\NotificationSender->withLocale(NULL, Object(Closure))\n#13 C:\\laragon\\www\\orbus_courier_server\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\ChannelManager.php(54): Illuminate\\Notifications\\NotificationSender->sendNow(Object(Illuminate\\Database\\Eloquent\\Collection), Object(App\\Notifications\\PasswordResetNotification), Array)\n#14 C:\\laragon\\www\\orbus_courier_server\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\SendQueuedNotifications.php(119): Illuminate\\Notifications\\ChannelManager->sendNow(Object(Illuminate\\Database\\Eloquent\\Collection), Object(App\\Notifications\\PasswordResetNotification), Array)\n#15 C:\\laragon\\www\\orbus_courier_server\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): Illuminate\\Notifications\\SendQueuedNotifications->handle(Object(Illuminate\\Notifications\\ChannelManager))\n#16 C:\\laragon\\www\\orbus_courier_server\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(41): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#17 C:\\laragon\\www\\orbus_courier_server\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(93): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#18 C:\\laragon\\www\\orbus_courier_server\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#19 C:\\laragon\\www\\orbus_courier_server\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(662): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#20 C:\\laragon\\www\\orbus_courier_server\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(128): Illuminate\\Container\\Container->call(Array)\n#21 C:\\laragon\\www\\orbus_courier_server\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(144): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(Illuminate\\Notifications\\SendQueuedNotifications))\n#22 C:\\laragon\\www\\orbus_courier_server\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(119): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(Illuminate\\Notifications\\SendQueuedNotifications))\n#23 C:\\laragon\\www\\orbus_courier_server\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(132): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#24 C:\\laragon\\www\\orbus_courier_server\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(123): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(Illuminate\\Notifications\\SendQueuedNotifications), false)\n#25 C:\\laragon\\www\\orbus_courier_server\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(144): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}(Object(Illuminate\\Notifications\\SendQueuedNotifications))\n#26 C:\\laragon\\www\\orbus_courier_server\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(119): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(Illuminate\\Notifications\\SendQueuedNotifications))\n#27 C:\\laragon\\www\\orbus_courier_server\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(122): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#28 C:\\laragon\\www\\orbus_courier_server\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(70): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Notifications\\SendQueuedNotifications))\n#29 C:\\laragon\\www\\orbus_courier_server\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Jobs\\Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)\n#30 C:\\laragon\\www\\orbus_courier_server\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(439): Illuminate\\Queue\\Jobs\\Job->fire()\n#31 C:\\laragon\\www\\orbus_courier_server\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(389): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))\n#32 C:\\laragon\\www\\orbus_courier_server\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(333): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))\n#33 C:\\laragon\\www\\orbus_courier_server\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(137): Illuminate\\Queue\\Worker->runNextJob(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))\n#34 C:\\laragon\\www\\orbus_courier_server\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(120): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')\n#35 C:\\laragon\\www\\orbus_courier_server\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#36 C:\\laragon\\www\\orbus_courier_server\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(41): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#37 C:\\laragon\\www\\orbus_courier_server\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(93): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#38 C:\\laragon\\www\\orbus_courier_server\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#39 C:\\laragon\\www\\orbus_courier_server\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(662): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#40 C:\\laragon\\www\\orbus_courier_server\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(211): Illuminate\\Container\\Container->call(Array)\n#41 C:\\laragon\\www\\orbus_courier_server\\vendor\\symfony\\console\\Command\\Command.php(326): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#42 C:\\laragon\\www\\orbus_courier_server\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(180): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#43 C:\\laragon\\www\\orbus_courier_server\\vendor\\symfony\\console\\Application.php(1096): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#44 C:\\laragon\\www\\orbus_courier_server\\vendor\\symfony\\console\\Application.php(324): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#45 C:\\laragon\\www\\orbus_courier_server\\vendor\\symfony\\console\\Application.php(175): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#46 C:\\laragon\\www\\orbus_courier_server\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Console\\Kernel.php(201): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#47 C:\\laragon\\www\\orbus_courier_server\\artisan(35): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#48 {main}', '2024-03-08 15:45:03'),
(3, '718a8ee1-6cf5-48e2-85f1-875fefcfff23', 'database', 'default', '{\"uuid\":\"718a8ee1-6cf5-48e2-85f1-875fefcfff23\",\"displayName\":\"App\\\\Notifications\\\\PasswordResetNotification\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\",\"command\":\"O:48:\\\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\\\":3:{s:11:\\\"notifiables\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";a:1:{i:0;s:36:\\\"9b7a22df-c67f-4bfd-9781-12624beba8ee\\\";}s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:43:\\\"App\\\\Notifications\\\\PasswordResetNotification\\\":3:{s:49:\\\"\\u0000App\\\\Notifications\\\\PasswordResetNotification\\u0000code\\\";i:8417;s:55:\\\"\\u0000App\\\\Notifications\\\\PasswordResetNotification\\u0000expiration\\\";O:25:\\\"Illuminate\\\\Support\\\\Carbon\\\":3:{s:4:\\\"date\\\";s:26:\\\"2024-03-08 16:32:53.159781\\\";s:13:\\\"timezone_type\\\";i:3;s:8:\\\"timezone\\\";s:3:\\\"UTC\\\";}s:2:\\\"id\\\";s:36:\\\"47fffcdf-d4af-4638-9518-e0097ad87a93\\\";}s:8:\\\"channels\\\";a:1:{i:0;s:4:\\\"mail\\\";}}\"}}', 'Symfony\\Component\\Mailer\\Exception\\TransportException: Connection could not be established with host \"ssl://smtp.googlemail.com:465\": stream_socket_client(): php_network_getaddresses: getaddrinfo for smtp.googlemail.com failed: No such host is known.  in C:\\laragon\\www\\orbus_courier_server\\vendor\\symfony\\mailer\\Transport\\Smtp\\Stream\\SocketStream.php:154\nStack trace:\n#0 [internal function]: Symfony\\Component\\Mailer\\Transport\\Smtp\\Stream\\SocketStream->Symfony\\Component\\Mailer\\Transport\\Smtp\\Stream\\{closure}(2, \'stream_socket_c...\', \'C:\\\\laragon\\\\www\\\\...\', 157)\n#1 C:\\laragon\\www\\orbus_courier_server\\vendor\\symfony\\mailer\\Transport\\Smtp\\Stream\\SocketStream.php(157): stream_socket_client(\'ssl://smtp.goog...\', 0, \'\', 60.0, 4, Resource id #1855)\n#2 C:\\laragon\\www\\orbus_courier_server\\vendor\\symfony\\mailer\\Transport\\Smtp\\SmtpTransport.php(275): Symfony\\Component\\Mailer\\Transport\\Smtp\\Stream\\SocketStream->initialize()\n#3 C:\\laragon\\www\\orbus_courier_server\\vendor\\symfony\\mailer\\Transport\\Smtp\\SmtpTransport.php(213): Symfony\\Component\\Mailer\\Transport\\Smtp\\SmtpTransport->start()\n#4 C:\\laragon\\www\\orbus_courier_server\\vendor\\symfony\\mailer\\Transport\\AbstractTransport.php(69): Symfony\\Component\\Mailer\\Transport\\Smtp\\SmtpTransport->doSend(Object(Symfony\\Component\\Mailer\\SentMessage))\n#5 C:\\laragon\\www\\orbus_courier_server\\vendor\\symfony\\mailer\\Transport\\Smtp\\SmtpTransport.php(137): Symfony\\Component\\Mailer\\Transport\\AbstractTransport->send(Object(Symfony\\Component\\Mime\\Email), Object(Symfony\\Component\\Mailer\\DelayedEnvelope))\n#6 C:\\laragon\\www\\orbus_courier_server\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(573): Symfony\\Component\\Mailer\\Transport\\Smtp\\SmtpTransport->send(Object(Symfony\\Component\\Mime\\Email), Object(Symfony\\Component\\Mailer\\DelayedEnvelope))\n#7 C:\\laragon\\www\\orbus_courier_server\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(335): Illuminate\\Mail\\Mailer->sendSymfonyMessage(Object(Symfony\\Component\\Mime\\Email))\n#8 C:\\laragon\\www\\orbus_courier_server\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\Channels\\MailChannel.php(66): Illuminate\\Mail\\Mailer->send(Object(Closure), Array, Object(Closure))\n#9 C:\\laragon\\www\\orbus_courier_server\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\NotificationSender.php(148): Illuminate\\Notifications\\Channels\\MailChannel->send(Object(App\\Models\\User), Object(App\\Notifications\\PasswordResetNotification))\n#10 C:\\laragon\\www\\orbus_courier_server\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\NotificationSender.php(106): Illuminate\\Notifications\\NotificationSender->sendToNotifiable(Object(App\\Models\\User), \'852fe5c3-01d7-4...\', Object(App\\Notifications\\PasswordResetNotification), \'mail\')\n#11 C:\\laragon\\www\\orbus_courier_server\\vendor\\laravel\\framework\\src\\Illuminate\\Support\\Traits\\Localizable.php(19): Illuminate\\Notifications\\NotificationSender->Illuminate\\Notifications\\{closure}()\n#12 C:\\laragon\\www\\orbus_courier_server\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\NotificationSender.php(101): Illuminate\\Notifications\\NotificationSender->withLocale(NULL, Object(Closure))\n#13 C:\\laragon\\www\\orbus_courier_server\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\ChannelManager.php(54): Illuminate\\Notifications\\NotificationSender->sendNow(Object(Illuminate\\Database\\Eloquent\\Collection), Object(App\\Notifications\\PasswordResetNotification), Array)\n#14 C:\\laragon\\www\\orbus_courier_server\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\SendQueuedNotifications.php(119): Illuminate\\Notifications\\ChannelManager->sendNow(Object(Illuminate\\Database\\Eloquent\\Collection), Object(App\\Notifications\\PasswordResetNotification), Array)\n#15 C:\\laragon\\www\\orbus_courier_server\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): Illuminate\\Notifications\\SendQueuedNotifications->handle(Object(Illuminate\\Notifications\\ChannelManager))\n#16 C:\\laragon\\www\\orbus_courier_server\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(41): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#17 C:\\laragon\\www\\orbus_courier_server\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(93): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#18 C:\\laragon\\www\\orbus_courier_server\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#19 C:\\laragon\\www\\orbus_courier_server\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(662): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#20 C:\\laragon\\www\\orbus_courier_server\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(128): Illuminate\\Container\\Container->call(Array)\n#21 C:\\laragon\\www\\orbus_courier_server\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(144): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(Illuminate\\Notifications\\SendQueuedNotifications))\n#22 C:\\laragon\\www\\orbus_courier_server\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(119): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(Illuminate\\Notifications\\SendQueuedNotifications))\n#23 C:\\laragon\\www\\orbus_courier_server\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(132): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#24 C:\\laragon\\www\\orbus_courier_server\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(123): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(Illuminate\\Notifications\\SendQueuedNotifications), false)\n#25 C:\\laragon\\www\\orbus_courier_server\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(144): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}(Object(Illuminate\\Notifications\\SendQueuedNotifications))\n#26 C:\\laragon\\www\\orbus_courier_server\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(119): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(Illuminate\\Notifications\\SendQueuedNotifications))\n#27 C:\\laragon\\www\\orbus_courier_server\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(122): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#28 C:\\laragon\\www\\orbus_courier_server\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(70): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Notifications\\SendQueuedNotifications))\n#29 C:\\laragon\\www\\orbus_courier_server\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Jobs\\Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)\n#30 C:\\laragon\\www\\orbus_courier_server\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(439): Illuminate\\Queue\\Jobs\\Job->fire()\n#31 C:\\laragon\\www\\orbus_courier_server\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(389): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))\n#32 C:\\laragon\\www\\orbus_courier_server\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(333): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))\n#33 C:\\laragon\\www\\orbus_courier_server\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(137): Illuminate\\Queue\\Worker->runNextJob(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))\n#34 C:\\laragon\\www\\orbus_courier_server\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(120): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')\n#35 C:\\laragon\\www\\orbus_courier_server\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#36 C:\\laragon\\www\\orbus_courier_server\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(41): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#37 C:\\laragon\\www\\orbus_courier_server\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(93): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#38 C:\\laragon\\www\\orbus_courier_server\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#39 C:\\laragon\\www\\orbus_courier_server\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(662): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#40 C:\\laragon\\www\\orbus_courier_server\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(211): Illuminate\\Container\\Container->call(Array)\n#41 C:\\laragon\\www\\orbus_courier_server\\vendor\\symfony\\console\\Command\\Command.php(326): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#42 C:\\laragon\\www\\orbus_courier_server\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(180): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#43 C:\\laragon\\www\\orbus_courier_server\\vendor\\symfony\\console\\Application.php(1096): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#44 C:\\laragon\\www\\orbus_courier_server\\vendor\\symfony\\console\\Application.php(324): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#45 C:\\laragon\\www\\orbus_courier_server\\vendor\\symfony\\console\\Application.php(175): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#46 C:\\laragon\\www\\orbus_courier_server\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Console\\Kernel.php(201): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#47 C:\\laragon\\www\\orbus_courier_server\\artisan(35): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#48 {main}', '2024-03-08 16:18:08'),
(4, '0ad4d2b6-b87c-41f2-92cf-7802b9b88cc8', 'database', 'default', '{\"uuid\":\"0ad4d2b6-b87c-41f2-92cf-7802b9b88cc8\",\"displayName\":\"App\\\\Notifications\\\\DocumentValidated\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\",\"command\":\"O:48:\\\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\\\":4:{s:11:\\\"notifiables\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";a:1:{i:0;s:36:\\\"9b7a22df-c67f-4bfd-9781-12624beba8ee\\\";}s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:35:\\\"App\\\\Notifications\\\\DocumentValidated\\\":3:{s:8:\\\"document\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:19:\\\"App\\\\Models\\\\Document\\\";s:2:\\\"id\\\";i:2;s:9:\\\"relations\\\";a:3:{i:0;s:10:\\\"parapheurs\\\";i:1;s:15:\\\"parapheurs.user\\\";i:2;s:9:\\\"createdBy\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"id\\\";s:36:\\\"d0ce311c-7bc7-434a-a5fe-7554516323f5\\\";s:11:\\\"afterCommit\\\";b:1;}s:8:\\\"channels\\\";a:1:{i:0;s:4:\\\"mail\\\";}s:11:\\\"afterCommit\\\";b:1;}\"}}', 'Illuminate\\Queue\\MaxAttemptsExceededException: App\\Notifications\\DocumentValidated has been attempted too many times. in C:\\laragon\\www\\orbus_courier_server\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\MaxAttemptsExceededException.php:24\nStack trace:\n#0 C:\\laragon\\www\\orbus_courier_server\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(785): Illuminate\\Queue\\MaxAttemptsExceededException::forJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob))\n#1 C:\\laragon\\www\\orbus_courier_server\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(519): Illuminate\\Queue\\Worker->maxAttemptsExceededException(Object(Illuminate\\Queue\\Jobs\\DatabaseJob))\n#2 C:\\laragon\\www\\orbus_courier_server\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(428): Illuminate\\Queue\\Worker->markJobAsFailedIfAlreadyExceedsMaxAttempts(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), 1)\n#3 C:\\laragon\\www\\orbus_courier_server\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(389): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))\n#4 C:\\laragon\\www\\orbus_courier_server\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(333): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))\n#5 C:\\laragon\\www\\orbus_courier_server\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(137): Illuminate\\Queue\\Worker->runNextJob(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))\n#6 C:\\laragon\\www\\orbus_courier_server\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(120): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')\n#7 C:\\laragon\\www\\orbus_courier_server\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#8 C:\\laragon\\www\\orbus_courier_server\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(41): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#9 C:\\laragon\\www\\orbus_courier_server\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(93): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#10 C:\\laragon\\www\\orbus_courier_server\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#11 C:\\laragon\\www\\orbus_courier_server\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(662): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#12 C:\\laragon\\www\\orbus_courier_server\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(211): Illuminate\\Container\\Container->call(Array)\n#13 C:\\laragon\\www\\orbus_courier_server\\vendor\\symfony\\console\\Command\\Command.php(326): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#14 C:\\laragon\\www\\orbus_courier_server\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(180): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#15 C:\\laragon\\www\\orbus_courier_server\\vendor\\symfony\\console\\Application.php(1096): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#16 C:\\laragon\\www\\orbus_courier_server\\vendor\\symfony\\console\\Application.php(324): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#17 C:\\laragon\\www\\orbus_courier_server\\vendor\\symfony\\console\\Application.php(175): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#18 C:\\laragon\\www\\orbus_courier_server\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Console\\Kernel.php(201): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#19 C:\\laragon\\www\\orbus_courier_server\\artisan(35): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#20 {main}', '2024-03-12 21:10:10');

-- --------------------------------------------------------

--
-- Structure de la table `filament_comments`
--

CREATE TABLE `filament_comments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject_id` bigint(20) UNSIGNED NOT NULL,
  `comment` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `jobs`
--

INSERT INTO `jobs` (`id`, `queue`, `payload`, `attempts`, `reserved_at`, `available_at`, `created_at`) VALUES
(9, 'default', '{\"uuid\":\"02be2eb2-a4ae-4e4b-b417-5cdb349868ea\",\"displayName\":\"App\\\\Jobs\\\\SendInformativeNotificationJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendInformativeNotificationJob\",\"command\":\"O:39:\\\"App\\\\Jobs\\\\SendInformativeNotificationJob\\\":2:{s:49:\\\"\\u0000App\\\\Jobs\\\\SendInformativeNotificationJob\\u0000document\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:19:\\\"App\\\\Models\\\\Document\\\";s:2:\\\"id\\\";i:4;s:9:\\\"relations\\\";a:2:{i:0;s:10:\\\"parapheurs\\\";i:1;s:15:\\\"parapheurs.user\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:55:\\\"\\u0000App\\\\Jobs\\\\SendInformativeNotificationJob\\u0000validatorAdded\\\";b:1;}\"}}', 1, 1710277831, 1710277690, 1710277690),
(10, 'default', '{\"uuid\":\"8cb36fa5-0371-40f9-b0cd-f39008813a46\",\"displayName\":\"App\\\\Notifications\\\\DocumentValidated\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\",\"command\":\"O:48:\\\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\\\":4:{s:11:\\\"notifiables\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";a:1:{i:0;s:36:\\\"9b7a22df-c67f-4bfd-9781-12624beba8ee\\\";}s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:35:\\\"App\\\\Notifications\\\\DocumentValidated\\\":3:{s:8:\\\"document\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:19:\\\"App\\\\Models\\\\Document\\\";s:2:\\\"id\\\";i:4;s:9:\\\"relations\\\";a:3:{i:0;s:10:\\\"parapheurs\\\";i:1;s:15:\\\"parapheurs.user\\\";i:2;s:9:\\\"createdBy\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"id\\\";s:36:\\\"0a640004-8181-4f36-bd78-b3f8d1fe52ec\\\";s:11:\\\"afterCommit\\\";b:1;}s:8:\\\"channels\\\";a:1:{i:0;s:4:\\\"mail\\\";}s:11:\\\"afterCommit\\\";b:1;}\"}}', 0, NULL, 1710277701, 1710277701),
(11, 'default', '{\"uuid\":\"027840b6-009f-4b51-adbd-31aabe1418f9\",\"displayName\":\"App\\\\Notifications\\\\DocumentValidated\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\",\"command\":\"O:48:\\\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\\\":4:{s:11:\\\"notifiables\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";a:1:{i:0;s:36:\\\"9b7a22df-c67f-4bfd-9781-12624beba8ee\\\";}s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:35:\\\"App\\\\Notifications\\\\DocumentValidated\\\":3:{s:8:\\\"document\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:19:\\\"App\\\\Models\\\\Document\\\";s:2:\\\"id\\\";i:4;s:9:\\\"relations\\\";a:3:{i:0;s:10:\\\"parapheurs\\\";i:1;s:15:\\\"parapheurs.user\\\";i:2;s:9:\\\"createdBy\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"id\\\";s:36:\\\"5ee62049-c2de-47fb-b798-24453e249954\\\";s:11:\\\"afterCommit\\\";b:1;}s:8:\\\"channels\\\";a:1:{i:0;s:4:\\\"mail\\\";}s:11:\\\"afterCommit\\\";b:1;}\"}}', 0, NULL, 1710277701, 1710277701),
(12, 'default', '{\"uuid\":\"a81c35d9-0cbe-4e2f-a0e2-64735b39b229\",\"displayName\":\"App\\\\Jobs\\\\SendInformativeNotificationJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendInformativeNotificationJob\",\"command\":\"O:39:\\\"App\\\\Jobs\\\\SendInformativeNotificationJob\\\":2:{s:49:\\\"\\u0000App\\\\Jobs\\\\SendInformativeNotificationJob\\u0000document\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:19:\\\"App\\\\Models\\\\Document\\\";s:2:\\\"id\\\";i:5;s:9:\\\"relations\\\";a:2:{i:0;s:10:\\\"parapheurs\\\";i:1;s:15:\\\"parapheurs.user\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:55:\\\"\\u0000App\\\\Jobs\\\\SendInformativeNotificationJob\\u0000validatorAdded\\\";b:1;}\"}}', 0, NULL, 1710436444, 1710436444),
(13, 'default', '{\"uuid\":\"2817f1fa-917a-47e4-a067-659d377fde63\",\"displayName\":\"App\\\\Jobs\\\\CourierUserNotificationJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\CourierUserNotificationJob\",\"command\":\"O:35:\\\"App\\\\Jobs\\\\CourierUserNotificationJob\\\":1:{s:44:\\\"\\u0000App\\\\Jobs\\\\CourierUserNotificationJob\\u0000courier\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:18:\\\"App\\\\Models\\\\Courier\\\";s:2:\\\"id\\\";i:2;s:9:\\\"relations\\\";a:1:{i:0;s:8:\\\"coursers\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}}\"}}', 0, NULL, 1710931682, 1710931682),
(14, 'default', '{\"uuid\":\"9cad08be-6ba0-4ce5-9a19-20680ca28f95\",\"displayName\":\"App\\\\Notifications\\\\SendDocActionNotification\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\",\"command\":\"O:48:\\\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\\\":3:{s:11:\\\"notifiables\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";a:1:{i:0;s:36:\\\"9b7a22df-c67f-4bfd-9781-12624beba8ee\\\";}s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:43:\\\"App\\\\Notifications\\\\SendDocActionNotification\\\":3:{s:53:\\\"\\u0000App\\\\Notifications\\\\SendDocActionNotification\\u0000userName\\\";s:9:\\\"Res Suivi\\\";s:53:\\\"\\u0000App\\\\Notifications\\\\SendDocActionNotification\\u0000document\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:19:\\\"App\\\\Models\\\\Document\\\";s:2:\\\"id\\\";i:1;s:9:\\\"relations\\\";a:1:{i:0;s:10:\\\"parapheurs\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"id\\\";s:36:\\\"51a6b384-f4c1-4277-a569-a52c291d6513\\\";}s:8:\\\"channels\\\";a:1:{i:0;s:4:\\\"mail\\\";}}\"}}', 0, NULL, 1711375163, 1711375163);

-- --------------------------------------------------------

--
-- Structure de la table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2023_11_17_165721_create_recipients_table', 1),
(6, '2023_11_19_223611_create_permission_tables', 1),
(7, '2023_11_20_162352_create_documents_table', 1),
(8, '2023_11_20_163632_create_document_user_table', 1),
(9, '2023_11_21_130708_create_doc_templates_table', 1),
(10, '2023_11_22_141432_create_notifications_table', 1),
(11, '2023_11_22_153255_create_jobs_table', 1),
(12, '2023_11_22_193006_create_activity_log_table', 1),
(13, '2023_11_22_193007_add_event_column_to_activity_log_table', 1),
(14, '2023_11_22_193008_add_batch_uuid_column_to_activity_log_table', 1),
(15, '2023_11_24_144447_create_doc_histories_table', 1),
(16, '2023_11_27_162537_create_doc_validation_histories_table', 1),
(17, '2023_11_28_141651_create_couriers_table', 1),
(18, '2023_11_28_163455_create_courier_user_table', 1),
(19, '2024_01_23_130558_create_uploads_table', 1),
(20, '2024_01_23_181529_create_settings_table', 1),
(21, '2024_02_05_133114_create_external_doc_initiators_table', 1),
(22, '2024_02_07_225415_create_filament_comments_table', 1),
(23, '2024_02_08_180807_create_teams_table', 1),
(24, '2024_02_08_191219_create_team_user_table', 1),
(25, '2024_02_09_202824_create_document_team_table', 1),
(26, '2024_02_19_131201_create_contacts_table', 1),
(27, '2024_03_03_142610_create_password_reset_codes_table', 1);

-- --------------------------------------------------------

--
-- Structure de la table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', '9b7a22da-8e1c-4acf-b9af-e077bb899b83'),
(2, 'App\\Models\\User', '9b7a22dc-9312-47af-9714-cbdad4d6946f'),
(2, 'App\\Models\\User', '9b7a22dd-3ae4-4a7a-92ae-6a895dc7224d'),
(2, 'App\\Models\\User', '9b7a22dd-c621-4371-99fd-aff97c8f87a6'),
(6, 'App\\Models\\User', '9b7a22de-591e-4385-9fdc-f2d9c4c4ad51'),
(7, 'App\\Models\\User', '9b7a22df-15c4-4830-aeb2-1dde4c6ed27a'),
(3, 'App\\Models\\User', '9b7a22df-c67f-4bfd-9781-12624beba8ee'),
(4, 'App\\Models\\User', '9b7a22e0-6d14-4723-9b2a-dcb57dca524a'),
(5, 'App\\Models\\User', '9b7a22e1-2d9c-435a-99de-36590a0c91ad'),
(9, 'App\\Models\\User', '9b7a22e1-ccbf-4e40-9489-f25740b335de'),
(2, 'App\\Models\\User', '9b7a22f1-8414-4f5b-8e1a-648fac11217a'),
(2, 'App\\Models\\User', '9b7a22f2-440d-43a2-951f-5c6f610bd6e1'),
(2, 'App\\Models\\User', '9b7a22f2-6819-44df-b4df-a9246f7ae186'),
(2, 'App\\Models\\User', '9b7a22f2-af1f-41ee-b980-86ea8190b4a1'),
(2, 'App\\Models\\User', '9b7a22f3-03c7-4e11-92e6-a345c6a20555'),
(8, 'App\\Models\\User', '9b7a22f3-b69d-4e52-89bd-13dfd8698a1f'),
(8, 'App\\Models\\User', '9b7a22f4-09f2-46c9-80e1-b1b881a50107'),
(8, 'App\\Models\\User', '9b7a22f4-23a7-4ba8-a59e-dd01a98b8451'),
(8, 'App\\Models\\User', '9b7a22f4-3a62-426a-8f84-0cf6775f18dc'),
(8, 'App\\Models\\User', '9b7a22f4-4de0-4d19-9afe-6cf108cb31fb'),
(8, 'App\\Models\\User', '9b7a22f4-617a-4518-bdb5-ec874101bb03'),
(8, 'App\\Models\\User', '9b7a22f4-74f8-4038-b117-f792fb876e32'),
(8, 'App\\Models\\User', '9b7a22f4-8878-42a2-bec5-edbca3783614'),
(8, 'App\\Models\\User', '9b7a22f4-9c16-4a1d-9434-c6a6df735ee3'),
(8, 'App\\Models\\User', '9b7a22f4-af94-43ff-ac19-39b4dee5dca0');

-- --------------------------------------------------------

--
-- Structure de la table `notifications`
--

CREATE TABLE `notifications` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `data` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `notifications`
--

INSERT INTO `notifications` (`id`, `type`, `notifiable_type`, `notifiable_id`, `data`, `read_at`, `created_at`, `updated_at`) VALUES
('23c6c032-3ea7-486f-8c81-e8a8c56e3c16', 'Filament\\Notifications\\DatabaseNotification', 'App\\Models\\User', '9b7a22e0-6d14-4723-9b2a-dcb57dca524a', '{\"actions\":[{\"name\":\"Voir Document\",\"color\":null,\"event\":null,\"eventData\":[],\"dispatchDirection\":false,\"dispatchToComponent\":null,\"extraAttributes\":[],\"icon\":null,\"iconPosition\":\"before\",\"iconSize\":null,\"isOutlined\":false,\"isDisabled\":false,\"label\":\"Voir document\",\"shouldClose\":false,\"shouldMarkAsRead\":false,\"shouldMarkAsUnread\":false,\"shouldOpenUrlInNewTab\":false,\"size\":\"sm\",\"tooltip\":null,\"url\":\"\\/documents\\/1\",\"view\":\"filament-actions::button-action\"},{\"name\":\"Marquer comme non lu\",\"color\":null,\"event\":null,\"eventData\":[],\"dispatchDirection\":false,\"dispatchToComponent\":null,\"extraAttributes\":[],\"icon\":null,\"iconPosition\":\"before\",\"iconSize\":null,\"isOutlined\":false,\"isDisabled\":false,\"label\":\"Marquer comme non lu\",\"shouldClose\":false,\"shouldMarkAsRead\":false,\"shouldMarkAsUnread\":true,\"shouldOpenUrlInNewTab\":false,\"size\":\"sm\",\"tooltip\":null,\"url\":null,\"view\":\"filament-actions::button-action\"},{\"name\":\"Marquer comme lu\",\"color\":null,\"event\":null,\"eventData\":[],\"dispatchDirection\":false,\"dispatchToComponent\":null,\"extraAttributes\":[],\"icon\":null,\"iconPosition\":\"before\",\"iconSize\":null,\"isOutlined\":false,\"isDisabled\":false,\"label\":\"Marquer comme lu\",\"shouldClose\":false,\"shouldMarkAsRead\":true,\"shouldMarkAsUnread\":false,\"shouldOpenUrlInNewTab\":false,\"size\":\"sm\",\"tooltip\":null,\"url\":null,\"view\":\"filament-actions::button-action\"}],\"body\":\"Vous venez d\'\\u00eatre ajout\\u00e9 au document Amethyst Oneal en tant que Parapheur.\",\"color\":null,\"duration\":\"persistent\",\"icon\":\"heroicon-o-check-circle\",\"iconColor\":\"success\",\"status\":\"success\",\"title\":\"Ajout de validateur \\u00e0 document\",\"view\":\"filament-notifications::notification\",\"viewData\":[],\"format\":\"filament\"}', NULL, '2024-03-05 14:05:01', '2024-03-05 14:05:01'),
('66b0fb6e-3d9a-412a-8732-befde93271ba', 'Filament\\Notifications\\DatabaseNotification', 'App\\Models\\User', '9b7a22df-15c4-4830-aeb2-1dde4c6ed27a', '{\"actions\":[{\"name\":\"Voir Document\",\"color\":null,\"event\":null,\"eventData\":[],\"dispatchDirection\":false,\"dispatchToComponent\":null,\"extraAttributes\":[],\"icon\":null,\"iconPosition\":\"before\",\"iconSize\":null,\"isOutlined\":false,\"isDisabled\":false,\"label\":\"Voir document\",\"shouldClose\":false,\"shouldMarkAsRead\":false,\"shouldMarkAsUnread\":false,\"shouldOpenUrlInNewTab\":false,\"size\":\"sm\",\"tooltip\":null,\"url\":\"\\/documents\\/1\",\"view\":\"filament-actions::button-action\"},{\"name\":\"Marquer comme non lu\",\"color\":null,\"event\":null,\"eventData\":[],\"dispatchDirection\":false,\"dispatchToComponent\":null,\"extraAttributes\":[],\"icon\":null,\"iconPosition\":\"before\",\"iconSize\":null,\"isOutlined\":false,\"isDisabled\":false,\"label\":\"Marquer comme non lu\",\"shouldClose\":false,\"shouldMarkAsRead\":false,\"shouldMarkAsUnread\":true,\"shouldOpenUrlInNewTab\":false,\"size\":\"sm\",\"tooltip\":null,\"url\":null,\"view\":\"filament-actions::button-action\"},{\"name\":\"Marquer comme lu\",\"color\":null,\"event\":null,\"eventData\":[],\"dispatchDirection\":false,\"dispatchToComponent\":null,\"extraAttributes\":[],\"icon\":null,\"iconPosition\":\"before\",\"iconSize\":null,\"isOutlined\":false,\"isDisabled\":false,\"label\":\"Marquer comme lu\",\"shouldClose\":false,\"shouldMarkAsRead\":true,\"shouldMarkAsUnread\":false,\"shouldOpenUrlInNewTab\":false,\"size\":\"sm\",\"tooltip\":null,\"url\":null,\"view\":\"filament-actions::button-action\"}],\"body\":\"\\nAttente de votre validation.Vous venez d\'\\u00eatre ajout\\u00e9 au document Amethyst Oneal en tant que Parapheur.\",\"color\":null,\"duration\":\"persistent\",\"icon\":\"heroicon-o-check-circle\",\"iconColor\":\"success\",\"status\":\"success\",\"title\":\"Ajout de validateur \\u00e0 document\",\"view\":\"filament-notifications::notification\",\"viewData\":[],\"format\":\"filament\"}', NULL, '2024-03-05 14:04:59', '2024-03-05 14:04:59'),
('d0c95c49-7d96-4881-86c6-dd38fb911cd4', 'Filament\\Notifications\\DatabaseNotification', 'App\\Models\\User', '9b7a22df-c67f-4bfd-9781-12624beba8ee', '{\"actions\":[{\"name\":\"Voir Document\",\"color\":null,\"event\":null,\"eventData\":[],\"dispatchDirection\":false,\"dispatchToComponent\":null,\"extraAttributes\":[],\"icon\":null,\"iconPosition\":\"before\",\"iconSize\":null,\"isOutlined\":false,\"isDisabled\":false,\"label\":\"Voir document\",\"shouldClose\":false,\"shouldMarkAsRead\":false,\"shouldMarkAsUnread\":false,\"shouldOpenUrlInNewTab\":false,\"size\":\"sm\",\"tooltip\":null,\"url\":\"\\/documents\\/1\",\"view\":\"filament-actions::button-action\"},{\"name\":\"Marquer comme non lu\",\"color\":null,\"event\":null,\"eventData\":[],\"dispatchDirection\":false,\"dispatchToComponent\":null,\"extraAttributes\":[],\"icon\":null,\"iconPosition\":\"before\",\"iconSize\":null,\"isOutlined\":false,\"isDisabled\":false,\"label\":\"Marquer comme non lu\",\"shouldClose\":false,\"shouldMarkAsRead\":false,\"shouldMarkAsUnread\":true,\"shouldOpenUrlInNewTab\":false,\"size\":\"sm\",\"tooltip\":null,\"url\":null,\"view\":\"filament-actions::button-action\"},{\"name\":\"Marquer comme lu\",\"color\":null,\"event\":null,\"eventData\":[],\"dispatchDirection\":false,\"dispatchToComponent\":null,\"extraAttributes\":[],\"icon\":null,\"iconPosition\":\"before\",\"iconSize\":null,\"isOutlined\":false,\"isDisabled\":false,\"label\":\"Marquer comme lu\",\"shouldClose\":false,\"shouldMarkAsRead\":true,\"shouldMarkAsUnread\":false,\"shouldOpenUrlInNewTab\":false,\"size\":\"sm\",\"tooltip\":null,\"url\":null,\"view\":\"filament-actions::button-action\"}],\"body\":\"Vous venez d\'\\u00eatre ajout\\u00e9 au document Amethyst Oneal en tant que Parapheur.\",\"color\":null,\"duration\":\"persistent\",\"icon\":\"heroicon-o-check-circle\",\"iconColor\":\"success\",\"status\":\"success\",\"title\":\"Ajout de validateur \\u00e0 document\",\"view\":\"filament-notifications::notification\",\"viewData\":[],\"format\":\"filament\"}', NULL, '2024-03-05 14:05:00', '2024-03-05 14:05:00'),
('fe594086-019a-488f-b3e6-b1783eae0ea3', 'Filament\\Notifications\\DatabaseNotification', 'App\\Models\\User', '9b7a22df-c67f-4bfd-9781-12624beba8ee', '{\"actions\":[{\"name\":\"Voir Document\",\"color\":null,\"event\":null,\"eventData\":[],\"dispatchDirection\":false,\"dispatchToComponent\":null,\"extraAttributes\":[],\"icon\":null,\"iconPosition\":\"before\",\"iconSize\":null,\"isOutlined\":false,\"isDisabled\":false,\"label\":\"Voir document\",\"shouldClose\":false,\"shouldMarkAsRead\":false,\"shouldMarkAsUnread\":false,\"shouldOpenUrlInNewTab\":false,\"size\":\"sm\",\"tooltip\":null,\"url\":\"\\/documents\\/2\",\"view\":\"filament-actions::button-action\"},{\"name\":\"Marquer comme non lu\",\"color\":null,\"event\":null,\"eventData\":[],\"dispatchDirection\":false,\"dispatchToComponent\":null,\"extraAttributes\":[],\"icon\":null,\"iconPosition\":\"before\",\"iconSize\":null,\"isOutlined\":false,\"isDisabled\":false,\"label\":\"Marquer comme non lu\",\"shouldClose\":false,\"shouldMarkAsRead\":false,\"shouldMarkAsUnread\":true,\"shouldOpenUrlInNewTab\":false,\"size\":\"sm\",\"tooltip\":null,\"url\":null,\"view\":\"filament-actions::button-action\"},{\"name\":\"Marquer comme lu\",\"color\":null,\"event\":null,\"eventData\":[],\"dispatchDirection\":false,\"dispatchToComponent\":null,\"extraAttributes\":[],\"icon\":null,\"iconPosition\":\"before\",\"iconSize\":null,\"isOutlined\":false,\"isDisabled\":false,\"label\":\"Marquer comme lu\",\"shouldClose\":false,\"shouldMarkAsRead\":true,\"shouldMarkAsUnread\":false,\"shouldOpenUrlInNewTab\":false,\"size\":\"sm\",\"tooltip\":null,\"url\":null,\"view\":\"filament-actions::button-action\"}],\"body\":\"Vous venez d\'\\u00eatre ajout\\u00e9 au document DOC N TEST en tant que Signataire par ordre\",\"color\":null,\"duration\":\"persistent\",\"icon\":\"heroicon-o-check-circle\",\"iconColor\":\"success\",\"status\":\"success\",\"title\":\"Ajout de Signataire \\u00e0 document\",\"view\":\"filament-notifications::notification\",\"viewData\":[],\"format\":\"filament\"}', NULL, '2024-03-12 21:10:30', '2024-03-12 21:10:30');

-- --------------------------------------------------------

--
-- Structure de la table `password_reset_codes`
--

CREATE TABLE `password_reset_codes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `expired_at` timestamp NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `password_reset_codes`
--

INSERT INTO `password_reset_codes` (`id`, `user_id`, `code`, `is_active`, `expired_at`, `created_at`, `updated_at`) VALUES
(4, '9b7a22df-c67f-4bfd-9781-12624beba8ee', '4790', 1, '2024-03-11 16:41:20', '2024-03-11 15:26:20', '2024-03-11 15:26:20');

-- --------------------------------------------------------

--
-- Structure de la table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'view_courier::module::courier', 'web', '2024-03-03 17:58:55', '2024-03-03 17:58:55'),
(2, 'view_any_courier::module::courier', 'web', '2024-03-03 17:58:55', '2024-03-03 17:58:55'),
(3, 'create_courier::module::courier', 'web', '2024-03-03 17:58:56', '2024-03-03 17:58:56'),
(4, 'update_courier::module::courier', 'web', '2024-03-03 17:58:56', '2024-03-03 17:58:56'),
(5, 'delete_courier::module::courier', 'web', '2024-03-03 17:58:56', '2024-03-03 17:58:56'),
(6, 'delete_any_courier::module::courier', 'web', '2024-03-03 17:58:56', '2024-03-03 17:58:56'),
(7, 'view_courier::module::recipient', 'web', '2024-03-03 17:58:56', '2024-03-03 17:58:56'),
(8, 'view_any_courier::module::recipient', 'web', '2024-03-03 17:58:56', '2024-03-03 17:58:56'),
(9, 'create_courier::module::recipient', 'web', '2024-03-03 17:58:56', '2024-03-03 17:58:56'),
(10, 'update_courier::module::recipient', 'web', '2024-03-03 17:58:56', '2024-03-03 17:58:56'),
(11, 'delete_courier::module::recipient', 'web', '2024-03-03 17:58:56', '2024-03-03 17:58:56'),
(12, 'delete_any_courier::module::recipient', 'web', '2024-03-03 17:58:56', '2024-03-03 17:58:56'),
(13, 'view_document::module::doc::template', 'web', '2024-03-03 17:58:56', '2024-03-03 17:58:56'),
(14, 'view_any_document::module::doc::template', 'web', '2024-03-03 17:58:56', '2024-03-03 17:58:56'),
(15, 'create_document::module::doc::template', 'web', '2024-03-03 17:58:56', '2024-03-03 17:58:56'),
(16, 'update_document::module::doc::template', 'web', '2024-03-03 17:58:56', '2024-03-03 17:58:56'),
(17, 'delete_document::module::doc::template', 'web', '2024-03-03 17:58:56', '2024-03-03 17:58:56'),
(18, 'delete_any_document::module::doc::template', 'web', '2024-03-03 17:58:56', '2024-03-03 17:58:56'),
(19, 'view_document::module::document', 'web', '2024-03-03 17:58:56', '2024-03-03 17:58:56'),
(20, 'view_any_document::module::document', 'web', '2024-03-03 17:58:56', '2024-03-03 17:58:56'),
(21, 'create_document::module::document', 'web', '2024-03-03 17:58:56', '2024-03-03 17:58:56'),
(22, 'update_document::module::document', 'web', '2024-03-03 17:58:56', '2024-03-03 17:58:56'),
(23, 'delete_document::module::document', 'web', '2024-03-03 17:58:56', '2024-03-03 17:58:56'),
(24, 'delete_any_document::module::document', 'web', '2024-03-03 17:58:56', '2024-03-03 17:58:56'),
(25, 'view_role', 'web', '2024-03-03 17:58:56', '2024-03-03 17:58:56'),
(26, 'view_any_role', 'web', '2024-03-03 17:58:56', '2024-03-03 17:58:56'),
(27, 'create_role', 'web', '2024-03-03 17:58:56', '2024-03-03 17:58:56'),
(28, 'update_role', 'web', '2024-03-03 17:58:56', '2024-03-03 17:58:56'),
(29, 'delete_role', 'web', '2024-03-03 17:58:56', '2024-03-03 17:58:56'),
(30, 'delete_any_role', 'web', '2024-03-03 17:58:56', '2024-03-03 17:58:56'),
(31, 'view_security::module::user', 'web', '2024-03-03 17:58:56', '2024-03-03 17:58:56'),
(32, 'view_any_security::module::user', 'web', '2024-03-03 17:58:56', '2024-03-03 17:58:56'),
(33, 'create_security::module::user', 'web', '2024-03-03 17:58:56', '2024-03-03 17:58:56'),
(34, 'update_security::module::user', 'web', '2024-03-03 17:58:57', '2024-03-03 17:58:57'),
(35, 'delete_security::module::user', 'web', '2024-03-03 17:58:57', '2024-03-03 17:58:57'),
(36, 'delete_any_security::module::user', 'web', '2024-03-03 17:58:57', '2024-03-03 17:58:57'),
(37, 'page_ActivityLog', 'web', '2024-03-03 17:58:57', '2024-03-03 17:58:57'),
(38, 'page_MyProfilePage', 'web', '2024-03-03 17:58:57', '2024-03-03 17:58:57'),
(39, 'view_document::module::external::initiator', 'web', '2024-03-03 17:58:57', '2024-03-03 17:58:57'),
(40, 'view_any_document::module::external::initiator', 'web', '2024-03-03 17:58:57', '2024-03-03 17:58:57'),
(41, 'create_document::module::external::initiator', 'web', '2024-03-03 17:58:57', '2024-03-03 17:58:57'),
(42, 'update_document::module::external::initiator', 'web', '2024-03-03 17:58:57', '2024-03-03 17:58:57'),
(43, 'delete_document::module::external::initiator', 'web', '2024-03-03 17:58:57', '2024-03-03 17:58:57'),
(44, 'delete_any_document::module::external::initiator', 'web', '2024-03-03 17:58:57', '2024-03-03 17:58:57'),
(45, 'pass_validation_turn_security::module::user', 'web', '2024-03-03 17:58:57', '2024-03-03 17:58:57'),
(46, 'view_setting::module::courier::setting', 'web', '2024-03-03 17:58:57', '2024-03-03 17:58:57'),
(47, 'view_any_setting::module::courier::setting', 'web', '2024-03-03 17:58:57', '2024-03-03 17:58:57'),
(48, 'create_setting::module::courier::setting', 'web', '2024-03-03 17:58:57', '2024-03-03 17:58:57'),
(49, 'update_setting::module::courier::setting', 'web', '2024-03-03 17:58:57', '2024-03-03 17:58:57'),
(50, 'delete_setting::module::courier::setting', 'web', '2024-03-03 17:58:57', '2024-03-03 17:58:57'),
(51, 'delete_any_setting::module::courier::setting', 'web', '2024-03-03 17:58:57', '2024-03-03 17:58:57'),
(52, 'view_setting::module::doc::setting', 'web', '2024-03-03 17:58:57', '2024-03-03 17:58:57'),
(53, 'view_any_setting::module::doc::setting', 'web', '2024-03-03 17:58:57', '2024-03-03 17:58:57'),
(54, 'create_setting::module::doc::setting', 'web', '2024-03-03 17:58:57', '2024-03-03 17:58:57'),
(55, 'update_setting::module::doc::setting', 'web', '2024-03-03 17:58:57', '2024-03-03 17:58:57'),
(56, 'delete_setting::module::doc::setting', 'web', '2024-03-03 17:58:57', '2024-03-03 17:58:57'),
(57, 'delete_any_setting::module::doc::setting', 'web', '2024-03-03 17:58:57', '2024-03-03 17:58:57'),
(58, 'view_setting::module::system::setting', 'web', '2024-03-03 17:58:57', '2024-03-03 17:58:57'),
(59, 'view_any_setting::module::system::setting', 'web', '2024-03-03 17:58:57', '2024-03-03 17:58:57'),
(60, 'create_setting::module::system::setting', 'web', '2024-03-03 17:58:57', '2024-03-03 17:58:57'),
(61, 'update_setting::module::system::setting', 'web', '2024-03-03 17:58:57', '2024-03-03 17:58:57'),
(62, 'delete_setting::module::system::setting', 'web', '2024-03-03 17:58:57', '2024-03-03 17:58:57'),
(63, 'delete_any_setting::module::system::setting', 'web', '2024-03-03 17:58:57', '2024-03-03 17:58:57'),
(64, 'add_signataire_security::module::user', 'web', '2024-03-03 17:58:58', '2024-03-03 17:58:58'),
(65, 'add_signataires_security::module::user', 'web', '2024-03-03 17:58:58', '2024-03-03 17:58:58'),
(66, 'view_security::module::team', 'web', '2024-03-03 17:58:58', '2024-03-03 17:58:58'),
(67, 'view_any_security::module::team', 'web', '2024-03-03 17:58:58', '2024-03-03 17:58:58'),
(68, 'create_security::module::team', 'web', '2024-03-03 17:58:58', '2024-03-03 17:58:58'),
(69, 'update_security::module::team', 'web', '2024-03-03 17:58:58', '2024-03-03 17:58:58'),
(70, 'delete_security::module::team', 'web', '2024-03-03 17:58:58', '2024-03-03 17:58:58'),
(71, 'delete_any_security::module::team', 'web', '2024-03-03 17:58:58', '2024-03-03 17:58:58'),
(72, 'widget_DashboardStatsOverview', 'web', '2024-03-03 17:58:58', '2024-03-03 17:58:58');

-- --------------------------------------------------------

--
-- Structure de la table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `personal_access_tokens`
--

INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `expires_at`, `created_at`, `updated_at`) VALUES
(1, 'App\\Models\\User', '9b7a22df-c67f-4bfd-9781-12624beba8ee', 'access_token', 'e0f669d43f65ed8852f11f598e5b9130c830534f782d85fb71b7baf55434dc9e', '[\"couriers:get\",\"documents:get\",\"settings:get\"]', '2024-03-11 15:14:46', NULL, '2024-03-11 15:14:17', '2024-03-11 15:14:46'),
(2, 'App\\Models\\User', '9b7a22df-c67f-4bfd-9781-12624beba8ee', 'access_token', '0346b8fcf2ede4dcaae309507fdbd83350ca7707a574c288f3d5e84440c83be7', '[\"couriers:get\",\"documents:get\",\"settings:get\"]', '2024-03-20 14:03:22', NULL, '2024-03-19 13:46:16', '2024-03-20 14:03:22'),
(3, 'App\\Models\\User', '9b7a22df-c67f-4bfd-9781-12624beba8ee', 'access_token', 'f9e401c47f1dc5713a9d5a7112a0d83ec0a293bafb209e1ebb95167d5c439986', '[\"couriers:get\",\"documents:get\",\"settings:get\"]', '2024-03-20 16:59:36', NULL, '2024-03-20 14:03:46', '2024-03-20 16:59:36'),
(4, 'App\\Models\\User', '9b7a22df-c67f-4bfd-9781-12624beba8ee', 'access_token', '712cd4c5fd6fc3e52927044bdf9ecdb39f6629e63bd8fca7d910cdc152c50f21', '[\"couriers:get\",\"documents:get\",\"settings:get\"]', '2024-03-20 16:23:04', NULL, '2024-03-20 14:10:33', '2024-03-20 16:23:04'),
(5, 'App\\Models\\User', '9b7a22df-c67f-4bfd-9781-12624beba8ee', 'access_token', 'dcc8cd337a9890b0a48a4194f98439b00a4abe31056bcc6624a06a2f0f779579', '[\"couriers:get\",\"documents:get\",\"settings:get\"]', '2024-03-22 14:43:52', NULL, '2024-03-21 13:25:53', '2024-03-22 14:43:52'),
(6, 'App\\Models\\User', '9b7a22df-15c4-4830-aeb2-1dde4c6ed27a', 'access_token', 'a3e45f5e97fb1d4bf792a42d88939d33a8fb66f935fc9efdd5c162de6b08e666', '[\"documents:get\",\"settings:get\"]', '2024-03-25 11:30:22', NULL, '2024-03-22 14:44:02', '2024-03-25 11:30:22'),
(7, 'App\\Models\\User', '9b7a22df-c67f-4bfd-9781-12624beba8ee', 'access_token', '2991776aa8dff581950bfd3bb96731e45b971bbe8f8e896efc8e90cd07b9dccb', '[\"couriers:get\",\"documents:get\",\"settings:get\"]', '2024-03-25 13:57:57', NULL, '2024-03-25 11:51:25', '2024-03-25 13:57:57'),
(8, 'App\\Models\\User', '9b7a22df-c67f-4bfd-9781-12624beba8ee', 'access_token', 'd647bf85f04ec4f53735ce4cad41a1a80b8f5a7cd95672db036e05232e6b74a8', '[\"couriers:get\",\"documents:get\",\"settings:get\"]', '2024-03-25 14:18:09', NULL, '2024-03-25 13:36:43', '2024-03-25 14:18:09'),
(9, 'App\\Models\\User', '9b7a22df-15c4-4830-aeb2-1dde4c6ed27a', 'access_token', '01edf97eefdb9e43106811632145fdfd9927ec733ad5d64bb246e6523f01f0e2', '[\"documents:get\",\"settings:get\"]', '2024-03-29 15:04:17', NULL, '2024-03-25 13:57:58', '2024-03-29 15:04:17'),
(10, 'App\\Models\\User', '9b7a22df-15c4-4830-aeb2-1dde4c6ed27a', 'access_token', 'd264ed59004f85e2bc6d6a0f2f60ecc75d94f999162a826f56886d2ef2fbdb7f', '[\"documents:get\",\"settings:get\"]', '2024-03-26 12:26:26', NULL, '2024-03-25 14:20:28', '2024-03-26 12:26:26');

-- --------------------------------------------------------

--
-- Structure de la table `recipients`
--

CREATE TABLE `recipients` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lat` double DEFAULT NULL,
  `lng` double DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `recipients`
--

INSERT INTO `recipients` (`id`, `name`, `email`, `phone`, `address`, `lat`, `lng`, `created_at`, `updated_at`) VALUES
(1, 'Prof. Eusebio Lehner', 'hobart50@example.org', '+14588538186', '59863 Dickens Orchard Suite 001\nOrnbury, NM 17670-2537', 15.973786, 102.573688, '2024-03-03 17:58:59', '2024-03-03 17:58:59'),
(2, 'Michaela Ledner', 'demetris.collier@example.net', '828-797-7551', '4470 Steuber Mews Suite 598\nPollichland, WA 24563-9027', -71.632959, 53.442838, '2024-03-03 17:58:59', '2024-03-03 17:58:59'),
(3, 'Tia Schneider', 'zprice@example.org', '1-413-822-2299', '71738 Alisa Way\nAhmadberg, HI 20426-2291', 73.997239, -116.784841, '2024-03-03 17:58:59', '2024-03-03 17:58:59'),
(4, 'Bernie Stroman', 'elyssa.stokes@example.com', '636-696-2656', '3138 Marlin Centers Suite 943\nSporerport, DC 59195-6828', 15.181634, 0.819102, '2024-03-03 17:58:59', '2024-03-03 17:58:59'),
(5, 'Rudy Ratke', 'schneider.judge@example.org', '+1 (432) 443-3543', '7707 Jade Glens\nEast Leslyview, ME 67097-0406', -17.255947, 161.326361, '2024-03-03 17:58:59', '2024-03-03 17:58:59'),
(6, 'Domingo Beer', 'emcglynn@example.com', '1-629-409-6240', '8331 Hyatt Landing\nNorth Kali, MD 28143-4976', -79.377739, 151.413946, '2024-03-03 17:58:59', '2024-03-03 17:58:59'),
(7, 'Issac Bauch', 'bartoletti.kirstin@example.org', '(878) 515-1254', '31080 Beahan Inlet\nMayertfort, WY 32206-8912', 62.404916, 11.134956, '2024-03-03 17:58:59', '2024-03-03 17:58:59'),
(8, 'Katherine Koelpin', 'hanna.bode@example.org', '409.997.2362', '275 Coy Court\nNew Brittanyborough, KS 66677-4806', 70.082073, 88.126421, '2024-03-03 17:58:59', '2024-03-03 17:58:59'),
(9, 'Dr. Audrey Braun DVM', 'armani.emard@example.net', '+12245656679', '91302 Jamison Crescent Suite 758\nSimonisside, SD 65781-1733', 27.399922, 67.769263, '2024-03-03 17:58:59', '2024-03-03 17:58:59'),
(10, 'Florencio Glover', 'wcummings@example.org', '430-325-9011', '299 Alisa Junction\nWest Destany, MN 87038-6256', -26.208942, -78.529398, '2024-03-03 17:58:59', '2024-03-03 17:58:59'),
(11, 'Peggie Lueilwitz', 'vrice@example.com', '+1-218-900-2505', '1121 Wunsch Corner\nSouth Joeton, AL 23743', -60.928339, -58.266403, '2024-03-03 17:58:59', '2024-03-03 17:58:59'),
(12, 'Dr. Jacklyn Krajcik', 'jjohnston@example.org', '(628) 509-8334', '6856 Rempel Estate\nNorth Westonland, SC 18641', -22.571723, -138.182403, '2024-03-03 17:58:59', '2024-03-03 17:58:59'),
(13, 'Lelah Nicolas', 'lukas08@example.org', '+1.617.850.0248', '2926 Amir Street\nRutherfordshire, WI 48004', 18.581518, 140.521641, '2024-03-03 17:58:59', '2024-03-03 17:58:59'),
(14, 'Kellen Bauch', 'kitty.beahan@example.net', '+1.772.333.4071', '59285 Betsy Canyon\nSouth Lavadachester, TN 01252', -4.033788, -64.096123, '2024-03-03 17:58:59', '2024-03-03 17:58:59'),
(15, 'Flo Blick', 'yolanda.heathcote@example.net', '(313) 853-3807', '1234 Hagenes Bypass Apt. 263\nTorrancemouth, KS 78228-1092', -77.846818, 34.768465, '2024-03-03 17:58:59', '2024-03-03 17:58:59'),
(16, 'Samanta Predovic Sr.', 'alva17@example.net', '929-227-1358', '1903 Bergstrom Brook\nGunnarville, OH 00934-8801', -49.555093, 96.73347, '2024-03-03 17:59:00', '2024-03-03 17:59:00'),
(17, 'Kiel Lowe', 'funk.lester@example.com', '364-216-4506', '943 Pfeffer Extension\nNorth Ansley, IN 36363-8813', -50.932024, -35.174689, '2024-03-03 17:59:00', '2024-03-03 17:59:00'),
(18, 'Miss Aida Bednar DDS', 'carlotta85@example.com', '+1 (689) 850-6811', '599 Heidi Avenue\nElveraport, NH 94085-9459', -6.800486, -175.185791, '2024-03-03 17:59:00', '2024-03-03 17:59:00'),
(19, 'Dr. Adolfo Haag', 'patrick05@example.net', '+1 (949) 474-2069', '6706 Schumm Extensions Suite 378\nWest Dax, AZ 14016', -13.580689, 26.907478, '2024-03-03 17:59:00', '2024-03-03 17:59:00'),
(20, 'Miss Norene Will DDS', 'heathcote.erwin@example.net', '1-541-286-8914', '29350 Swift Street Suite 040\nAlanachester, TX 15116-9564', 68.820916, -17.324895, '2024-03-03 17:59:00', '2024-03-03 17:59:00'),
(21, 'Mr. Jefferey Gibson', 'alessia.hill@example.net', '(480) 755-1057', '5716 Roma Garden Suite 756\nSouth Triston, MS 83680-3579', 77.441433, -100.307315, '2024-03-03 17:59:00', '2024-03-03 17:59:00'),
(22, 'Prof. Marques Gusikowski I', 'lemke.magdalen@example.net', '+16512427358', '59855 Quitzon Curve Suite 597\nEast Kamrenside, FL 61842', 45.682138, 49.178999, '2024-03-03 17:59:00', '2024-03-03 17:59:00'),
(23, 'Francisco Hayes', 'jenifer.wolff@example.com', '1-352-378-9302', '86673 Nickolas Pine Suite 832\nNorth Olaborough, GA 65909-6024', -0.643674, 140.702881, '2024-03-03 17:59:00', '2024-03-03 17:59:00'),
(24, 'Marilou Klocko', 'kathryne.kuhn@example.net', '314.422.8363', '1676 Zboncak Row\nGerholdberg, MO 48686-3226', 32.061949, 117.187463, '2024-03-03 17:59:00', '2024-03-03 17:59:00'),
(25, 'Cleora Sporer MD', 'tschuster@example.org', '+1-203-285-1875', '843 Minnie Throughway Apt. 443\nSouth Javier, KY 61412-4761', -44.494871, -110.546385, '2024-03-03 17:59:00', '2024-03-03 17:59:00'),
(26, 'Constance Leffler', 'ottis.flatley@example.net', '+1-864-533-6200', '490 Jammie Underpass Apt. 032\nNew Robbieview, RI 60514-3682', -59.92383, 119.86347, '2024-03-03 17:59:01', '2024-03-03 17:59:01'),
(27, 'Dr. Lew Ernser', 'okuneva.edna@example.org', '+1.769.422.1907', '13525 Reichert Keys\nFisherville, IN 23221-9501', 31.547096, 132.404601, '2024-03-03 17:59:01', '2024-03-03 17:59:01'),
(28, 'Mertie Heathcote I', 'parisian.trisha@example.com', '(312) 807-1927', '13473 Stehr Run\nWest Rossieview, WI 70273', 10.356628, -172.765888, '2024-03-03 17:59:01', '2024-03-03 17:59:01'),
(29, 'Gail Bayer', 'zander.monahan@example.com', '1-872-647-7534', '5787 Odessa Circle Apt. 484\nNorth Kathlyn, OK 95863-9298', -42.420618, -14.726043, '2024-03-03 17:59:01', '2024-03-03 17:59:01'),
(30, 'Coralie Dare', 'ozemlak@example.org', '480.847.6925', '5005 Prohaska Flat Apt. 813\nPort Evie, LA 35812', -66.076528, 24.443845, '2024-03-03 17:59:01', '2024-03-03 17:59:01'),
(31, 'Gregoria Howe', 'valerie22@example.com', '(424) 693-6759', '392 Kulas Parks Apt. 160\nLake Maybell, WA 50617-1091', -44.731523, 34.617702, '2024-03-03 17:59:01', '2024-03-03 17:59:01'),
(32, 'Bradly Keebler Jr.', 'rosalia00@example.com', '+1.332.818.6604', '2354 Kacey Pike Suite 813\nFriesenborough, FL 76310-3141', 33.38605, -150.135542, '2024-03-03 17:59:01', '2024-03-03 17:59:01'),
(33, 'Maude Ward', 'senger.sydney@example.org', '1-364-330-9713', '8968 Witting Lock\nWest Marcella, DC 34536', -26.730257, 147.948111, '2024-03-03 17:59:01', '2024-03-03 17:59:01'),
(34, 'Arthur Auer', 'tromp.bertha@example.com', '863-577-1971', '45493 Grace Port\nPort Brent, NC 48382', 39.130632, -41.755177, '2024-03-03 17:59:01', '2024-03-03 17:59:01'),
(35, 'Fae Rice MD', 'hank07@example.com', '1-930-378-6498', '8860 Stiedemann Ville\nRitchieport, NE 14578', -47.276176, 113.210404, '2024-03-03 17:59:01', '2024-03-03 17:59:01'),
(36, 'Zelma Toy', 'funk.brycen@example.net', '360-372-5400', '2164 Schultz Lake Apt. 036\nSouth Foster, FL 38782-4409', 25.017966, -99.732202, '2024-03-03 17:59:02', '2024-03-03 17:59:02'),
(37, 'Collin Terry', 'hamill.rosa@example.net', '+1-540-469-0719', '54554 Schoen Mission Apt. 946\nKoryburgh, GA 93682', -4.712109, -99.874848, '2024-03-03 17:59:02', '2024-03-03 17:59:02'),
(38, 'Prince Cummings', 'prohaska.tanner@example.org', '+1-320-667-8858', '72640 Ritchie Turnpike Suite 475\nGibsonmouth, KY 82073', -21.283575, 138.030804, '2024-03-03 17:59:02', '2024-03-03 17:59:02'),
(39, 'Prof. Juwan Connelly IV', 'sawayn.elbert@example.net', '+1-262-961-3012', '879 Chaz Mountains Suite 209\nShanellestad, NY 11587-0561', 2.790528, 102.909689, '2024-03-03 17:59:02', '2024-03-03 17:59:02'),
(40, 'Shaniya Keeling', 'emonahan@example.net', '848-384-5780', '9966 Lebsack Spur Apt. 617\nNorth Lucystad, AL 07126', -6.107592, -107.790783, '2024-03-03 17:59:02', '2024-03-03 17:59:02'),
(41, 'Elwyn Franecki', 'durgan.madeline@example.com', '(820) 758-2218', '2238 Mervin Tunnel\nTravonport, UT 35085', 35.330954, 123.608271, '2024-03-03 17:59:02', '2024-03-03 17:59:02'),
(42, 'Aglae Schuppe Sr.', 'daisha38@example.org', '312.723.3761', '2500 Alfonzo Courts\nLake Coby, MA 59284-3354', -26.470162, 11.918785, '2024-03-03 17:59:02', '2024-03-03 17:59:02'),
(43, 'Drake Paucek I', 'feffertz@example.net', '430.714.4490', '108 Brian Route Apt. 524\nWest Wademouth, ME 82446-6276', 57.758654, 148.861333, '2024-03-03 17:59:02', '2024-03-03 17:59:02'),
(44, 'Ludwig Kuhn', 'uwaters@example.org', '(989) 766-6204', '318 Gerda Way\nEmanuelberg, KS 99734', 48.158363, -143.542275, '2024-03-03 17:59:02', '2024-03-03 17:59:02'),
(45, 'Dr. Keon Glover MD', 'mccullough.fredy@example.org', '1-661-228-2589', '1723 Reichel Land\nDianaside, NY 71546', 59.877102, -99.157309, '2024-03-03 17:59:02', '2024-03-03 17:59:02'),
(46, 'Mr. Golden Reinger PhD', 'edd24@example.com', '726-728-3191', '44547 Bradtke Plains Apt. 369\nEast Damian, WI 28688', -20.265619, 161.361355, '2024-03-03 17:59:03', '2024-03-03 17:59:03'),
(47, 'Ludwig O\'Reilly', 'jannie.wuckert@example.net', '1-862-748-3820', '6236 Upton Burg\nRatkefurt, LA 72569-8432', 65.142295, -151.552455, '2024-03-03 17:59:03', '2024-03-03 17:59:03'),
(48, 'Maeve Fritsch', 'oreilly.bernardo@example.org', '930.776.2932', '42390 Mayert Mountains Apt. 737\nNew Maci, IN 70163', -79.396356, 127.752284, '2024-03-03 17:59:03', '2024-03-03 17:59:03'),
(49, 'Edythe Sawayn', 'kreinger@example.com', '1-228-438-9078', '2898 Gwen Path\nSouth Vladimirfort, WV 53422-1987', -24.32661, 15.358571, '2024-03-03 17:59:03', '2024-03-03 17:59:03'),
(50, 'Reinhold Mueller', 'krunte@example.org', '1-218-866-8042', '5471 Hyatt Field Apt. 788\nEast Maye, VA 34934', 63.092068, -148.832726, '2024-03-03 17:59:03', '2024-03-03 17:59:03'),
(51, 'Prof. Bernard Haag', 'hturner@example.com', '(909) 408-8568', '1775 Lakin Mission Suite 103\nPort Garrisonborough, NM 45423-5551', -16.425296, -173.625161, '2024-03-03 17:59:03', '2024-03-03 17:59:03'),
(52, 'Megane Gottlieb', 'kaitlyn06@example.com', '726.889.2981', '42657 Jeff Stravenue\nEast Dominiqueside, IA 41268', -71.379019, -119.015275, '2024-03-03 17:59:03', '2024-03-03 17:59:03'),
(53, 'Miss Agustina Muller', 'willis.schroeder@example.org', '+1-347-395-4805', '795 Turcotte Loaf Apt. 324\nChloefurt, TX 66500', -78.141109, 12.962239, '2024-03-03 17:59:03', '2024-03-03 17:59:03'),
(54, 'Miss Andreanne O\'Hara IV', 'holden.durgan@example.com', '+14136484390', '26956 Emilio Square Apt. 423\nLorineville, SC 19861', 66.525218, -48.792017, '2024-03-03 17:59:03', '2024-03-03 17:59:03'),
(55, 'Adell O\'Connell', 'xdoyle@example.org', '+1-424-777-8934', '42556 Gilberto Ways\nNyahshire, DE 27249-5790', -41.364714, 55.56666, '2024-03-03 17:59:03', '2024-03-03 17:59:03'),
(56, 'Ramona Hettinger', 'beer.ariane@example.net', '351-463-4145', '34902 Ardith Parkway Apt. 912\nDaretown, KY 06057', 59.354254, 39.803544, '2024-03-03 17:59:04', '2024-03-03 17:59:04'),
(57, 'Gaetano Cummerata', 'lucio11@example.net', '(475) 272-2346', '7554 Huel Road\nLake Era, WV 45739', 21.876604, -36.218781, '2024-03-03 17:59:04', '2024-03-03 17:59:04'),
(58, 'Kayla Lowe', 'candelario30@example.net', '+18105427217', '5386 McLaughlin Stravenue Suite 047\nNorth Magaliville, ME 26168-6873', -20.658592, 118.689049, '2024-03-03 17:59:04', '2024-03-03 17:59:04'),
(59, 'Ike Rau', 'lawson.kerluke@example.net', '979.414.6269', '5263 Buckridge Hill Suite 193\nLake Kirstenburgh, MN 80433', 46.221857, 52.718795, '2024-03-03 17:59:04', '2024-03-03 17:59:04'),
(60, 'Lura Keebler', 'britchie@example.net', '+1.775.662.1996', '13791 Howard Wells\nKelsiton, GA 60484-3238', -8.466488, 33.093428, '2024-03-03 17:59:04', '2024-03-03 17:59:04');

-- --------------------------------------------------------

--
-- Structure de la table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_role_courier` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `is_role_courier`, `created_at`, `updated_at`) VALUES
(1, 'Super Admin', 'web', 0, '2024-03-03 17:58:46', '2024-03-03 17:58:46'),
(2, 'Admin', 'web', 0, '2024-03-03 17:58:46', '2024-03-03 17:58:46'),
(3, 'Responsable suivi', 'web', 0, '2024-03-03 17:58:47', '2024-03-03 17:58:47'),
(4, 'Responsable juridique', 'web', 0, '2024-03-03 17:58:47', '2024-03-03 17:58:47'),
(5, 'Responsable achat', 'web', 0, '2024-03-03 17:58:47', '2024-03-03 17:58:47'),
(6, 'AG', 'web', 0, '2024-03-03 17:58:47', '2024-03-03 17:58:47'),
(7, 'RH', 'web', 0, '2024-03-03 17:58:47', '2024-03-03 17:58:47'),
(8, 'Coursier', 'web', 0, '2024-03-03 17:58:47', '2024-03-03 17:58:47'),
(9, 'Initiateur', 'web', 0, '2024-03-03 17:58:47', '2024-03-03 17:58:47'),
(10, 'Parapheur', 'web', 1, '2024-03-03 17:58:47', '2024-03-03 17:58:47'),
(11, 'Signataire principal', 'web', 1, '2024-03-03 17:58:47', '2024-03-03 17:58:47'),
(12, 'Signataire par ordre', 'web', 1, '2024-03-03 17:58:47', '2024-03-03 17:58:47'),
(13, 'Signataire par interim', 'web', 1, '2024-03-03 17:58:47', '2024-03-03 17:58:47'),
(14, 'Signataire par délegation', 'web', 1, '2024-03-03 17:58:47', '2024-03-03 17:58:47'),
(15, 'Responsable RH', 'web', 0, '2024-03-03 17:58:58', '2024-03-03 17:58:58'),
(16, 'Demandeur', 'web', 0, '2024-03-03 17:58:58', '2024-03-03 17:58:58');

-- --------------------------------------------------------

--
-- Structure de la table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(1, 2),
(2, 2),
(3, 2),
(4, 2),
(5, 2),
(6, 2),
(7, 2),
(8, 2),
(9, 2),
(10, 2),
(11, 2),
(12, 2),
(13, 2),
(14, 2),
(15, 2),
(16, 2),
(17, 2),
(18, 2),
(19, 2),
(20, 2),
(21, 2),
(22, 2),
(23, 2),
(24, 2),
(25, 2),
(26, 2),
(27, 2),
(28, 2),
(29, 2),
(30, 2),
(31, 2),
(32, 2),
(33, 2),
(34, 2),
(35, 2),
(36, 2),
(37, 2),
(38, 2),
(39, 2),
(40, 2),
(41, 2),
(42, 2),
(43, 2),
(44, 2),
(45, 2),
(46, 2),
(47, 2),
(48, 2),
(49, 2),
(50, 2),
(51, 2),
(52, 2),
(53, 2),
(54, 2),
(55, 2),
(56, 2),
(57, 2),
(58, 2),
(59, 2),
(60, 2),
(61, 2),
(62, 2),
(63, 2),
(64, 2),
(65, 2),
(66, 2),
(67, 2),
(68, 2),
(69, 2),
(70, 2),
(71, 2),
(72, 2),
(1, 3),
(2, 3),
(3, 3),
(4, 3),
(5, 3),
(6, 3),
(7, 3),
(8, 3),
(9, 3),
(10, 3),
(11, 3),
(12, 3),
(13, 3),
(14, 3),
(15, 3),
(16, 3),
(17, 3),
(18, 3),
(19, 3),
(20, 3),
(21, 3),
(22, 3),
(23, 3),
(24, 3),
(37, 3),
(38, 3),
(39, 3),
(40, 3),
(41, 3),
(42, 3),
(43, 3),
(44, 3),
(46, 3),
(47, 3),
(48, 3),
(49, 3),
(50, 3),
(51, 3),
(52, 3),
(53, 3),
(54, 3),
(55, 3),
(56, 3),
(57, 3),
(58, 3),
(59, 3),
(60, 3),
(61, 3),
(62, 3),
(63, 3),
(64, 3),
(66, 3),
(67, 3),
(68, 3),
(69, 3),
(70, 3),
(71, 3),
(72, 3),
(1, 4),
(2, 4),
(3, 4),
(4, 4),
(5, 4),
(6, 4),
(7, 4),
(8, 4),
(9, 4),
(10, 4),
(11, 4),
(12, 4),
(13, 4),
(14, 4),
(15, 4),
(16, 4),
(17, 4),
(18, 4),
(19, 4),
(20, 4),
(21, 4),
(22, 4),
(23, 4),
(24, 4),
(37, 4),
(38, 4),
(39, 4),
(40, 4),
(41, 4),
(42, 4),
(43, 4),
(44, 4),
(46, 4),
(47, 4),
(48, 4),
(49, 4),
(50, 4),
(51, 4),
(52, 4),
(53, 4),
(54, 4),
(55, 4),
(56, 4),
(57, 4),
(58, 4),
(59, 4),
(60, 4),
(61, 4),
(62, 4),
(63, 4),
(64, 4),
(66, 4),
(67, 4),
(68, 4),
(69, 4),
(70, 4),
(71, 4),
(72, 4),
(1, 5),
(2, 5),
(3, 5),
(4, 5),
(5, 5),
(6, 5),
(7, 5),
(8, 5),
(9, 5),
(10, 5),
(11, 5),
(12, 5),
(13, 5),
(14, 5),
(15, 5),
(16, 5),
(17, 5),
(18, 5),
(19, 5),
(20, 5),
(21, 5),
(22, 5),
(23, 5),
(24, 5),
(37, 5),
(38, 5),
(39, 5),
(40, 5),
(41, 5),
(42, 5),
(43, 5),
(44, 5),
(46, 5),
(47, 5),
(48, 5),
(49, 5),
(50, 5),
(51, 5),
(52, 5),
(53, 5),
(54, 5),
(55, 5),
(56, 5),
(57, 5),
(58, 5),
(59, 5),
(60, 5),
(61, 5),
(62, 5),
(63, 5),
(64, 5),
(66, 5),
(67, 5),
(68, 5),
(69, 5),
(70, 5),
(71, 5),
(72, 5),
(1, 6),
(2, 6),
(3, 6),
(4, 6),
(5, 6),
(6, 6),
(7, 6),
(8, 6),
(9, 6),
(10, 6),
(11, 6),
(12, 6),
(13, 6),
(14, 6),
(15, 6),
(16, 6),
(17, 6),
(18, 6),
(19, 6),
(20, 6),
(21, 6),
(22, 6),
(23, 6),
(24, 6),
(25, 6),
(26, 6),
(27, 6),
(28, 6),
(29, 6),
(30, 6),
(31, 6),
(32, 6),
(33, 6),
(34, 6),
(35, 6),
(36, 6),
(37, 6),
(38, 6),
(39, 6),
(40, 6),
(41, 6),
(42, 6),
(43, 6),
(44, 6),
(45, 6),
(46, 6),
(47, 6),
(48, 6),
(49, 6),
(50, 6),
(51, 6),
(52, 6),
(53, 6),
(54, 6),
(55, 6),
(56, 6),
(57, 6),
(58, 6),
(59, 6),
(60, 6),
(61, 6),
(62, 6),
(63, 6),
(64, 6),
(65, 6),
(66, 6),
(67, 6),
(68, 6),
(69, 6),
(70, 6),
(71, 6),
(72, 6),
(1, 7),
(2, 7),
(3, 7),
(4, 7),
(5, 7),
(6, 7),
(7, 7),
(8, 7),
(9, 7),
(10, 7),
(11, 7),
(12, 7),
(13, 7),
(14, 7),
(15, 7),
(16, 7),
(17, 7),
(18, 7),
(19, 7),
(20, 7),
(21, 7),
(22, 7),
(23, 7),
(24, 7),
(37, 7),
(38, 7),
(39, 7),
(40, 7),
(41, 7),
(42, 7),
(43, 7),
(44, 7),
(46, 7),
(47, 7),
(48, 7),
(49, 7),
(50, 7),
(51, 7),
(52, 7),
(53, 7),
(54, 7),
(55, 7),
(56, 7),
(57, 7),
(58, 7),
(59, 7),
(60, 7),
(61, 7),
(62, 7),
(63, 7),
(64, 7),
(66, 7),
(67, 7),
(68, 7),
(69, 7),
(70, 7),
(71, 7),
(72, 7),
(1, 8),
(2, 8),
(13, 9),
(14, 9),
(15, 9),
(16, 9),
(17, 9),
(18, 9),
(19, 9),
(20, 9),
(21, 9),
(22, 9),
(23, 9),
(24, 9),
(1, 15),
(2, 15),
(3, 15),
(4, 15),
(5, 15),
(6, 15),
(7, 15),
(8, 15),
(9, 15),
(10, 15),
(11, 15),
(12, 15),
(13, 15),
(14, 15),
(15, 15),
(16, 15),
(17, 15),
(18, 15),
(19, 15),
(20, 15),
(21, 15),
(22, 15),
(23, 15),
(24, 15),
(37, 15),
(38, 15),
(39, 15),
(40, 15),
(41, 15),
(42, 15),
(43, 15),
(44, 15),
(46, 15),
(47, 15),
(48, 15),
(49, 15),
(50, 15),
(51, 15),
(52, 15),
(53, 15),
(54, 15),
(55, 15),
(56, 15),
(57, 15),
(64, 15),
(66, 15),
(67, 15),
(68, 15),
(69, 15),
(70, 15),
(71, 15),
(72, 15);

-- --------------------------------------------------------

--
-- Structure de la table `settings`
--

CREATE TABLE `settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `unit` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `default_value` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `module` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `settings`
--

INSERT INTO `settings` (`id`, `key`, `display_name`, `value`, `unit`, `default_value`, `description`, `is_active`, `module`, `created_at`, `updated_at`) VALUES
(1, 'password_expiration_delay', 'Délais expiration mot de passe', '60', 'jours', '60', 'Temps de validité d\'un mot de passe utilisateur. PAssé ce délai, l\'utilisateur devra renouveller son mot de passe pour accéder aux resources de l\'application.', 0, 'system', '2024-03-03 17:58:55', '2024-03-03 17:58:55'),
(2, 'courier_recovery_delay', 'Délais récupération courrier', '48', 'heures', '48', 'Délais après lequel un courrier est marqué comme en retard s\'il n\'a pas été récupéré par le coursier affecté', 0, 'courier', '2024-03-03 17:58:55', '2024-03-03 17:58:55'),
(3, 'doc_urgency_normal', 'Doc non-urgent', '168', 'heures', '168', 'Délais de validation d\'un document après lequel le tour de validation passe automatiquement au validateur suivant.', 0, 'document', '2024-03-03 17:58:55', '2024-03-03 17:58:55'),
(4, 'doc_urgency_urgent', 'Doc urgent', '120', 'heures', '120', 'Délais de validation d\'un document après lequel le tour de validation passe automatiquement au validateur suivant.', 0, 'document', '2024-03-03 17:58:55', '2024-03-03 17:58:55'),
(5, 'doc_urgency_critical', 'Doc Très urgent', '48', 'heures', '48', 'Délais de validation d\'un document après lequel le tour de validation passe automatiquement au validateur suivant.', 0, 'document', '2024-03-03 17:58:55', '2024-03-03 17:58:55');

-- --------------------------------------------------------

--
-- Structure de la table `teams`
--

CREATE TABLE `teams` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `teams`
--

INSERT INTO `teams` (`id`, `user_id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, '9b7a22f1-8414-4f5b-8e1a-648fac11217a', 'ipsam', 'Recusandae facilis nihil velit provident consequatur saepe sit.', '2024-03-03 17:59:06', '2024-03-03 17:59:06'),
(2, '9b7a22f2-440d-43a2-951f-5c6f610bd6e1', 'totam', 'Pariatur itaque vero eos placeat.', '2024-03-03 17:59:06', '2024-03-03 17:59:06'),
(3, '9b7a22f2-6819-44df-b4df-a9246f7ae186', 'iste', 'Ducimus eaque vero dolor enim dolore enim.', '2024-03-03 17:59:06', '2024-03-03 17:59:06'),
(4, '9b7a22f2-af1f-41ee-b980-86ea8190b4a1', 'et', 'Error eum velit aut sint deserunt placeat nihil.', '2024-03-03 17:59:06', '2024-03-03 17:59:06'),
(5, '9b7a22f3-03c7-4e11-92e6-a345c6a20555', 'iusto', 'Ut voluptas atque quo nobis quaerat nesciunt eos.', '2024-03-03 17:59:06', '2024-03-03 17:59:06');

-- --------------------------------------------------------

--
-- Structure de la table `team_user`
--

CREATE TABLE `team_user` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `team_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `uploads`
--

CREATE TABLE `uploads` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `uploads`
--

INSERT INTO `uploads` (`id`, `user_id`, `file_path`, `type`, `is_active`, `created_at`, `updated_at`) VALUES
(1, '9b7a22df-c67f-4bfd-9781-12624beba8ee', 'signatures/9b7a22df-c67f-4bfd-9781-12624beba8ee/paraphe_65f0827605671.png', 'paraphe', 1, '2024-03-12 16:27:34', '2024-03-12 16:27:34'),
(2, '9b7a22df-c67f-4bfd-9781-12624beba8ee', 'signatures/9b7a22df-c67f-4bfd-9781-12624beba8ee/order_65f0829292c22.png', 'order', 1, '2024-03-12 16:28:02', '2024-03-12 16:28:02'),
(3, '9b7a22df-15c4-4830-aeb2-1dde4c6ed27a', 'signatures/9b7a22df-15c4-4830-aeb2-1dde4c6ed27a/paraphe_65fd98bb86642.png', 'paraphe', 1, '2024-03-22 14:42:03', '2024-03-22 14:42:03');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `registration_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `signature` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `avatar_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password_changed_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `name`, `registration_number`, `phone`, `email`, `email_verified_at`, `password`, `signature`, `is_active`, `avatar_url`, `password_changed_at`, `remember_token`, `created_at`, `updated_at`) VALUES
('9b7a22da-8e1c-4acf-b9af-e077bb899b83', 'Super Admin', '637696', NULL, 'super@test.mail', '2024-03-03 17:58:48', '$2y$12$9luwUHz7VSvzV6eeuNsowOl.Miv.z56ULEuJblYDtkz75TVU0ItUm', NULL, 1, NULL, NULL, NULL, '2024-03-03 17:58:51', '2024-03-03 17:58:51'),
('9b7a22dc-9312-47af-9714-cbdad4d6946f', 'Admin', '7', NULL, 'admin@test.mail', '2024-03-03 17:58:51', '$2y$12$MIPiqoB/iact0OSpuwREzuLSNfBAmxfPq7ueS5vrCeoSZ0BJ6AWXO', NULL, 1, NULL, NULL, NULL, '2024-03-03 17:58:51', '2024-03-03 17:58:51'),
('9b7a22dd-3ae4-4a7a-92ae-6a895dc7224d', 'Khady DIAKHATE', '119444376', NULL, 'kdiakhate@gainde2000.sn', '2024-03-03 17:58:52', '$2y$12$XmDnc8N7mTch5ummDNTET.ueDc0rKdUOICdu/oApEq2i1FeIVkrbi', NULL, 1, NULL, NULL, NULL, '2024-03-03 17:58:52', '2024-03-03 17:58:52'),
('9b7a22dd-c621-4371-99fd-aff97c8f87a6', 'Aissatou KASSE', '9', NULL, 'akasse@gainde2000.sn', '2024-03-03 17:58:52', '$2y$12$Dn7no2K9VcvjUMn2QQn.geL.WVrah3lG4TymxI4mOBaW02lgfVjjy', NULL, 1, NULL, NULL, NULL, '2024-03-03 17:58:52', '2024-03-03 17:58:52'),
('9b7a22de-591e-4385-9fdc-f2d9c4c4ad51', 'Admin General', '672676891', '+1 (435) 801-8406', 'ag@test.mail', '2024-03-03 17:58:52', '$2y$12$dFTE2ePlvw3TvUoryoMeSen9iyhgjlkergSwSyIjjyknMNgwZu3rm', NULL, 1, NULL, NULL, NULL, '2024-03-03 17:58:53', '2024-03-03 17:58:53'),
('9b7a22df-15c4-4830-aeb2-1dde4c6ed27a', 'Res Humaine', '92135', '(332) 405-4078', 'rh@test.mail', '2024-03-03 17:58:53', '$2y$12$6j5MNkgZrB3LdicKTaR2PunT.r3dDhNgwc2rFrMdBL9/P5bLcZmsG', NULL, 1, NULL, NULL, NULL, '2024-03-03 17:58:53', '2024-03-03 17:58:53'),
('9b7a22df-c67f-4bfd-9781-12624beba8ee', 'Res Suivi', '7848', '(520) 262-4694', 'ngonendiaye.laye@gmail.com', '2024-03-03 17:58:53', '$2y$12$j2O4jiW1FNluHcG1BtfH9eFwYdFzIN3QM1h5uKfcrIYl1Pt8z0S96', NULL, 1, NULL, NULL, NULL, '2024-03-03 17:58:54', '2024-03-11 15:11:22'),
('9b7a22e0-6d14-4723-9b2a-dcb57dca524a', 'Res Juri', '3782', '1-669-429-5514', 'resjuri@test.mail', '2024-03-03 17:58:54', '$2y$12$8jdl2sa7cv19QhIy4Owwyu7NiZms3XlfZhrkotIqkUjVinvdMd5E.', NULL, 1, NULL, NULL, NULL, '2024-03-03 17:58:54', '2024-03-03 17:58:54'),
('9b7a22e1-2d9c-435a-99de-36590a0c91ad', 'Res Achat', '1', '586-963-8498', 'resachat@test.mail', '2024-03-03 17:58:54', '$2y$12$mNEWwIy0YnnMwJTTZPS5V.WJ4u99dJg5OqcQ3Pd7rRnBpXjIOIBQa', NULL, 1, NULL, NULL, NULL, '2024-03-03 17:58:54', '2024-03-03 17:58:54'),
('9b7a22e1-ccbf-4e40-9489-f25740b335de', 'Ini Tiateur', '8097634', '941.912.4423', 'init@test.mail', '2024-03-03 17:58:55', '$2y$12$2LI/h.rU/97RJ6jybSa70.GCAEKkD2jdwPxHYUCPxF6MnL.2CtwhG', NULL, 1, NULL, NULL, NULL, '2024-03-03 17:58:55', '2024-03-03 17:58:55'),
('9b7a22f1-8414-4f5b-8e1a-648fac11217a', 'Ezequiel Daniel', '36094', NULL, 'glarson@example.com', '2024-03-03 17:59:05', '$2y$12$FoZc2kF307ZXVKHOeHtgdO.1vZV/c.M20H74LEXfniWFPoqVTu90S', NULL, 1, NULL, NULL, 't7L0aKPUEi', '2024-03-03 17:59:05', '2024-03-03 17:59:05'),
('9b7a22f2-440d-43a2-951f-5c6f610bd6e1', 'Reva Sanford', '21988', NULL, 'ernest28@example.net', '2024-03-03 17:59:06', '$2y$12$FoZc2kF307ZXVKHOeHtgdO.1vZV/c.M20H74LEXfniWFPoqVTu90S', NULL, 1, NULL, NULL, 'ziPP6kY2RH', '2024-03-03 17:59:06', '2024-03-03 17:59:06'),
('9b7a22f2-6819-44df-b4df-a9246f7ae186', 'Ms. Effie Cremin', '31615', NULL, 'zroberts@example.net', '2024-03-03 17:59:06', '$2y$12$FoZc2kF307ZXVKHOeHtgdO.1vZV/c.M20H74LEXfniWFPoqVTu90S', NULL, 1, NULL, NULL, 'nr5LKZ5FkW', '2024-03-03 17:59:06', '2024-03-03 17:59:06'),
('9b7a22f2-af1f-41ee-b980-86ea8190b4a1', 'Adela Bogisich', '40017', NULL, 'goyette.aditya@example.com', '2024-03-03 17:59:06', '$2y$12$FoZc2kF307ZXVKHOeHtgdO.1vZV/c.M20H74LEXfniWFPoqVTu90S', NULL, 1, NULL, NULL, 'MwLH2CrL7Y', '2024-03-03 17:59:06', '2024-03-03 17:59:06'),
('9b7a22f3-03c7-4e11-92e6-a345c6a20555', 'Ludie Osinski', '33549', NULL, 'jeanette.wilderman@example.net', '2024-03-03 17:59:06', '$2y$12$FoZc2kF307ZXVKHOeHtgdO.1vZV/c.M20H74LEXfniWFPoqVTu90S', NULL, 1, NULL, NULL, 'iqBNNU9jYJ', '2024-03-03 17:59:06', '2024-03-03 17:59:06'),
('9b7a22f3-b69d-4e52-89bd-13dfd8698a1f', 'Mrs. Madelyn Waters', '49981', NULL, 'ekerluke@example.net', '2024-03-03 17:59:07', '$2y$12$FoZc2kF307ZXVKHOeHtgdO.1vZV/c.M20H74LEXfniWFPoqVTu90S', NULL, 1, NULL, NULL, 'pExEmsWs1S', '2024-03-03 17:59:07', '2024-03-03 17:59:07'),
('9b7a22f4-09f2-46c9-80e1-b1b881a50107', 'Florencio Dare', '63770', NULL, 'heaney.elwyn@example.com', '2024-03-03 17:59:07', '$2y$12$FoZc2kF307ZXVKHOeHtgdO.1vZV/c.M20H74LEXfniWFPoqVTu90S', NULL, 1, NULL, NULL, 'T0QebdJsuA', '2024-03-03 17:59:07', '2024-03-03 17:59:07'),
('9b7a22f4-23a7-4ba8-a59e-dd01a98b8451', 'Lonie Schamberger', '63968', NULL, 'lwitting@example.net', '2024-03-03 17:59:07', '$2y$12$FoZc2kF307ZXVKHOeHtgdO.1vZV/c.M20H74LEXfniWFPoqVTu90S', NULL, 1, NULL, NULL, 'pI7EkAghSv', '2024-03-03 17:59:07', '2024-03-03 17:59:07'),
('9b7a22f4-3a62-426a-8f84-0cf6775f18dc', 'Melyssa Turcotte', '47406', NULL, 'shanon.bosco@example.net', '2024-03-03 17:59:07', '$2y$12$FoZc2kF307ZXVKHOeHtgdO.1vZV/c.M20H74LEXfniWFPoqVTu90S', NULL, 1, NULL, NULL, 'JKvdhIK8Pr', '2024-03-03 17:59:07', '2024-03-03 17:59:07'),
('9b7a22f4-4de0-4d19-9afe-6cf108cb31fb', 'Andreane Homenick', '66663', NULL, 'gleason.furman@example.org', '2024-03-03 17:59:07', '$2y$12$FoZc2kF307ZXVKHOeHtgdO.1vZV/c.M20H74LEXfniWFPoqVTu90S', NULL, 1, NULL, NULL, 'I18Up1uFII', '2024-03-03 17:59:07', '2024-03-03 17:59:07'),
('9b7a22f4-617a-4518-bdb5-ec874101bb03', 'Melyna Ortiz', '22769', NULL, 'casandra80@example.org', '2024-03-03 17:59:07', '$2y$12$FoZc2kF307ZXVKHOeHtgdO.1vZV/c.M20H74LEXfniWFPoqVTu90S', NULL, 1, NULL, NULL, 'MKISO3IFIu', '2024-03-03 17:59:07', '2024-03-03 17:59:07'),
('9b7a22f4-74f8-4038-b117-f792fb876e32', 'Amari Fay', '51581', NULL, 'caterina.kessler@example.com', '2024-03-03 17:59:07', '$2y$12$FoZc2kF307ZXVKHOeHtgdO.1vZV/c.M20H74LEXfniWFPoqVTu90S', NULL, 1, NULL, NULL, 'izQlrqp67S', '2024-03-03 17:59:07', '2024-03-03 17:59:07'),
('9b7a22f4-8878-42a2-bec5-edbca3783614', 'Fae O\'Kon', '35585', NULL, 'mann.abby@example.org', '2024-03-03 17:59:07', '$2y$12$FoZc2kF307ZXVKHOeHtgdO.1vZV/c.M20H74LEXfniWFPoqVTu90S', NULL, 1, NULL, NULL, 'sXLYtUeDAn', '2024-03-03 17:59:07', '2024-03-03 17:59:07'),
('9b7a22f4-9c16-4a1d-9434-c6a6df735ee3', 'Luna Huels', '13425', NULL, 'corkery.jamey@example.org', '2024-03-03 17:59:07', '$2y$12$FoZc2kF307ZXVKHOeHtgdO.1vZV/c.M20H74LEXfniWFPoqVTu90S', NULL, 1, NULL, NULL, 'PkOFXOhXKN', '2024-03-03 17:59:07', '2024-03-03 17:59:07'),
('9b7a22f4-af94-43ff-ac19-39b4dee5dca0', 'Evans Thiel', '40208', NULL, 'savanah49@example.com', '2024-03-03 17:59:07', '$2y$12$FoZc2kF307ZXVKHOeHtgdO.1vZV/c.M20H74LEXfniWFPoqVTu90S', NULL, 1, NULL, NULL, 'dqMmPW9xXy', '2024-03-03 17:59:07', '2024-03-03 17:59:07');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `activity_log`
--
ALTER TABLE `activity_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subject` (`subject_type`,`subject_id`),
  ADD KEY `causer` (`causer_type`,`causer_id`),
  ADD KEY `activity_log_log_name_index` (`log_name`);

--
-- Index pour la table `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `contacts_email_unique` (`email`),
  ADD UNIQUE KEY `contacts_phone_unique` (`phone`);

--
-- Index pour la table `couriers`
--
ALTER TABLE `couriers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `couriers_courier_number_unique` (`courier_number`);

--
-- Index pour la table `courier_user`
--
ALTER TABLE `courier_user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `courier_user_courier_id_index` (`courier_id`),
  ADD KEY `courier_user_recipient_id_index` (`recipient_id`);

--
-- Index pour la table `documents`
--
ALTER TABLE `documents`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `documents_name_unique` (`name`),
  ADD KEY `documents_doc_type_index` (`doc_type`);

--
-- Index pour la table `document_team`
--
ALTER TABLE `document_team`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `document_user`
--
ALTER TABLE `document_user`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `doc_histories`
--
ALTER TABLE `doc_histories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `doc_histories_document_id_index` (`document_id`);

--
-- Index pour la table `doc_templates`
--
ALTER TABLE `doc_templates`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `doc_validation_histories`
--
ALTER TABLE `doc_validation_histories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `doc_validation_histories_user_id_index` (`user_id`),
  ADD KEY `doc_validation_histories_document_id_index` (`document_id`);

--
-- Index pour la table `external_doc_initiators`
--
ALTER TABLE `external_doc_initiators`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `external_doc_initiators_email_unique` (`email`),
  ADD UNIQUE KEY `external_doc_initiators_phone_unique` (`phone`);

--
-- Index pour la table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Index pour la table `filament_comments`
--
ALTER TABLE `filament_comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `filament_comments_user_id_index` (`user_id`);

--
-- Index pour la table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Index pour la table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Index pour la table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Index pour la table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`);

--
-- Index pour la table `password_reset_codes`
--
ALTER TABLE `password_reset_codes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `password_reset_codes_code_unique` (`code`);

--
-- Index pour la table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Index pour la table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Index pour la table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Index pour la table `recipients`
--
ALTER TABLE `recipients`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `recipients_name_unique` (`name`),
  ADD UNIQUE KEY `recipients_email_unique` (`email`),
  ADD UNIQUE KEY `recipients_phone_unique` (`phone`);

--
-- Index pour la table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Index pour la table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Index pour la table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `teams`
--
ALTER TABLE `teams`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `team_user`
--
ALTER TABLE `team_user`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `uploads`
--
ALTER TABLE `uploads`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_registration_number_unique` (`registration_number`),
  ADD UNIQUE KEY `users_phone_unique` (`phone`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `activity_log`
--
ALTER TABLE `activity_log`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT pour la table `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT pour la table `couriers`
--
ALTER TABLE `couriers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `courier_user`
--
ALTER TABLE `courier_user`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `documents`
--
ALTER TABLE `documents`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `document_team`
--
ALTER TABLE `document_team`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `document_user`
--
ALTER TABLE `document_user`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT pour la table `doc_histories`
--
ALTER TABLE `doc_histories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `doc_templates`
--
ALTER TABLE `doc_templates`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `doc_validation_histories`
--
ALTER TABLE `doc_validation_histories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `external_doc_initiators`
--
ALTER TABLE `external_doc_initiators`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `filament_comments`
--
ALTER TABLE `filament_comments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT pour la table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT pour la table `password_reset_codes`
--
ALTER TABLE `password_reset_codes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- AUTO_INCREMENT pour la table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pour la table `recipients`
--
ALTER TABLE `recipients`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT pour la table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT pour la table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `teams`
--
ALTER TABLE `teams`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `team_user`
--
ALTER TABLE `team_user`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `uploads`
--
ALTER TABLE `uploads`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
