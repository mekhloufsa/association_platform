-- Database Schema for Association Platform "AURA"
-- Design: Antigravity IA

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- --------------------------------------------------------
-- Table: wilayas
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `wilayas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `code` varchar(5) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table: users
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL UNIQUE,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('admin','president_assoc','president_siege','user') DEFAULT 'user',
  `phone` varchar(20) DEFAULT NULL,
  `wilaya_id` int(11) DEFAULT NULL,
  `status` enum('active','inactive','suspended') DEFAULT 'active',
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`wilaya_id`) REFERENCES `wilayas`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table: associations
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `associations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL UNIQUE,
  `description` text,
  `president_user_id` int(11) NOT NULL,
  `national_account_status` enum('pending','approved','rejected') DEFAULT 'pending',
  `logo_path` varchar(255) DEFAULT NULL,
  `thank_you_message` text DEFAULT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`president_user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table: sieges (Bureaux locaux)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `sieges` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `association_id` int(11) NOT NULL,
  `wilaya_id` int(11) NOT NULL,
  `address` text NOT NULL,
  `manager_user_id` int(11) DEFAULT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`association_id`) REFERENCES `associations`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`wilaya_id`) REFERENCES `wilayas`(`id`),
  FOREIGN KEY (`manager_user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table: annonces
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `annonces` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `author_user_id` int(11) NOT NULL,
  `association_id` int(11) DEFAULT NULL,
  `status` enum('draft','published','archived') DEFAULT 'draft',
  `published_at` timestamp NULL DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `attachment_path` varchar(255) DEFAULT NULL,
  `visibility` enum('public','users_only') DEFAULT 'public',
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`author_user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`association_id`) REFERENCES `associations`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table: help_requests
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `help_requests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `association_id` int(11) DEFAULT NULL,
  `subject` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `attachments` text, -- JSON array of file paths
  `status` enum('pending','accepted','rejected','completed') DEFAULT 'pending',
  `siege_id` int(11) DEFAULT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`association_id`) REFERENCES `associations`(`id`) ON DELETE SET NULL,
  FOREIGN KEY (`siege_id`) REFERENCES `sieges`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table: association_requests
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `association_requests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `national_id_number` varchar(50) NOT NULL,
  `logo_path` varchar(255) DEFAULT NULL,
  `attachments` text, -- JSON array
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `admin_message` text,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table: siege_requests
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `siege_requests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `siege_id` int(11) NOT NULL,
  `national_id_number` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `contact_info` text NOT NULL,
  `attachments` text, -- JSON array
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `president_message` text,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`siege_id`) REFERENCES `sieges`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table: material_donations
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `material_donations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `association_id` int(11) NOT NULL,
  `siege_id` int(11) DEFAULT NULL,
  `category` varchar(100) NOT NULL, -- e.g., 'Food', 'Clothing', 'Medical'
  `description` text NOT NULL,
  `quantity` varchar(100) DEFAULT NULL,
  `status` enum('pending', 'scheduled', 'collected', 'cancelled') DEFAULT 'pending',
  `pickup_date` timestamp NULL DEFAULT NULL,
  `manager_message` text,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`association_id`) REFERENCES `associations`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`siege_id`) REFERENCES `sieges`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table: donations
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `donations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `association_id` int(11) DEFAULT NULL,
  `siege_id` int(11) DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `type` enum('onetime','monthly') DEFAULT 'onetime',
  `message` text,
  `status` enum('completed','failed','pending') DEFAULT 'completed',
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`association_id`) REFERENCES `associations`(`id`) ON DELETE SET NULL,
  FOREIGN KEY (`siege_id`) REFERENCES `sieges`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table: campaigns (Bénévolat)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `campaigns` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `association_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `location` varchar(255) DEFAULT NULL,
  `max_volunteers` int(11) DEFAULT NULL,
  `status` enum('open','closed','finished') DEFAULT 'open',
  `campaign_type` enum('local','national') DEFAULT 'local',
  `need_type` enum('personnel','argent') DEFAULT 'personnel',
  `financial_goal` decimal(10,2) DEFAULT NULL,
  `current_raised` decimal(10,2) DEFAULT 0.00,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`association_id`) REFERENCES `associations`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table: volunteers
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `volunteers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `campaign_id` int(11) NOT NULL,
  `status` enum('registered','confirmed','attended','cancelled') DEFAULT 'registered',
  `registered_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_campaign` (`user_id`,`campaign_id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`campaign_id`) REFERENCES `campaigns`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table: tasks
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `tasks` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `association_id` INT NOT NULL,
    `title` VARCHAR(255) NOT NULL,
    `description` TEXT,
    `assigned_to` INT NULL,
    `status` ENUM('pending', 'in_progress', 'completed') DEFAULT 'pending',
    `priority` ENUM('low', 'medium', 'high') DEFAULT 'medium',
    `due_date` DATE,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`association_id`) REFERENCES `associations`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`assigned_to`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table: activity_logs
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `activity_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `action` varchar(255) NOT NULL,
  `details` text,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Data for table: wilayas
-- --------------------------------------------------------
INSERT INTO `wilayas` (`id`, `name`, `code`) VALUES
(1, 'Adrar', '01'), (2, 'Chlef', '02'), (3, 'Laghouat', '03'), (4, 'Oum El Bouaghi', '04'), (5, 'Batna', '05'),
(6, 'Béjaïa', '06'), (7, 'Biskra', '07'), (8, 'Béchar', '08'), (9, 'Blida', '09'), (10, 'Bouira', '10'),
(11, 'Tamanrasset', '11'), (12, 'Tébessa', '12'), (13, 'Tlemcen', '13'), (14, 'Tiaret', '14'), (15, 'Tizi Ouzou', '15'),
(16, 'Alger', '16'), (17, 'Djelfa', '17'), (18, 'Jijel', '18'), (19, 'Sétif', '19'), (20, 'Saïda', '20'),
(21, 'Skikda', '21'), (22, 'Sidi Bel Abbès', '22'), (23, 'Annaba', '23'), (24, 'Guelma', '24'), (25, 'Constantine', '25'),
(26, 'Médéa', '26'), (27, 'Mostaganem', '27'), (28, 'M\'Sila', '28'), (29, 'Mascara', '29'), (30, 'Ouargla', '30'),
(31, 'Oran', '31'), (32, 'El Bayadh', '32'), (33, 'Illizi', '33'), (34, 'Bordj Bou Arreridj', '34'), (35, 'Boumerdès', '35'),
(36, 'El Tarf', '36'), (37, 'Tindouf', '37'), (38, 'Tissemsilt', '38'), (39, 'El Oued', '39'), (40, 'Khenchela', '40'),
(41, 'Souk Ahras', '41'), (42, 'Tipaza', '42'), (43, 'Mila', '43'), (44, 'Aïn Defla', '44'), (45, 'Naâma', '45'),
(46, 'Aïn Témouchent', '46'), (47, 'Ghardaïa', '47'), (48, 'Relizane', '48');

-- --------------------------------------------------------
-- Fixtures (Test Data)
-- --------------------------------------------------------

-- Admin (pwd: admin123)
INSERT INTO `users` (`id`, `nom`, `prenom`, `email`, `password_hash`, `role`, `phone`, `wilaya_id`, `status`) VALUES
(1, 'Admin', 'Aura', 'admin@aura.dz', '$2y$10$F0kaJjbR8GDUx3ns3CUIz.4ooUDCd3O9dh00EETYhUssiILOZF6fa', 'admin', '0555555555', 16, 'active');

-- Président d'Association (pwd: president123)
INSERT INTO `users` (`id`, `nom`, `prenom`, `email`, `password_hash`, `role`, `phone`, `wilaya_id`, `status`) VALUES
(2, 'Benali', 'Ahmed', 'president@elghit.dz', '$2y$10$NQpW3rVGesTxkS/kX9fVtea6Ykd9kWSf9ZG.r5HA/inIcwcLWavJG', 'president_assoc', '0661223344', 31, 'active');

-- Association
INSERT INTO `associations` (`id`, `name`, `slug`, `description`, `president_user_id`, `national_account_status`) VALUES
(1, 'Association El Ghaith', 'el-ghaith', 'Une association dédiée à l aide humanitaire et à la distribution de repas.', 2, 'approved');

-- Annonces
INSERT INTO `annonces` (`title`, `content`, `author_user_id`, `published_at`, `status`) VALUES
('Lancement de la campagne Hiver 2026', 'Nous lançons aujourd hui notre grande collecte de vêtements chauds pour les plus démunis.', 1, NOW(), 'published'),
('Appel aux bénévoles pour le don du sang', 'Rejoignez-nous samedi prochain au centre de transfusion d Alger.', 1, NOW(), 'published'),
('Nouvelle antenne à Oran', 'L association El Ghaith ouvre un nouveau siège à Oran pour mieux vous servir.', 2, NOW(), 'published');

COMMIT;
