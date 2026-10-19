-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: betulinaja
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `addresses`
--

DROP TABLE IF EXISTS `addresses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `addresses` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `label` varchar(255) NOT NULL DEFAULT 'Rumah',
  `address` text NOT NULL,
  `landmark` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `district` varchar(255) DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `is_primary` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `addresses_user_id_foreign` (`user_id`),
  CONSTRAINT `addresses_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `addresses`
--

LOCK TABLES `addresses` WRITE;
/*!40000 ALTER TABLE `addresses` DISABLE KEYS */;
INSERT INTO `addresses` VALUES (1,2,'Rumah','Jl. Menteng Raya No. 45, RT 02/RW 03','Pagar hitam samping minimarket','Jakarta Pusat','Menteng',-6.18800000,106.83400000,1,'2026-10-19 06:40:59','2026-10-19 06:40:59'),(2,2,'Kantor','Gedung Sentra Mulia Lt. 5, Jl. Rasuna Said Kav. X-6','Dekat halte Transjakarta','Jakarta Selatan','Setiabudi',-6.21800000,106.83000000,0,'2026-10-19 06:40:59','2026-10-19 06:40:59');
/*!40000 ALTER TABLE `addresses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `app_settings`
--

DROP TABLE IF EXISTS `app_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `app_settings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(255) NOT NULL,
  `value` text DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `app_settings_key_unique` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `app_settings`
--

LOCK TABLES `app_settings` WRITE;
/*!40000 ALTER TABLE `app_settings` DISABLE KEYS */;
/*!40000 ALTER TABLE `app_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2024_01_01_000010_create_partner_profiles_table',1),(5,'2024_01_01_000020_create_service_categories_table',1),(6,'2024_01_01_000030_create_services_table',1),(7,'2024_01_01_000040_create_partner_skills_table',1),(8,'2024_01_01_000050_create_pricing_rules_table',1),(9,'2024_01_01_000060_create_addresses_table',1),(10,'2024_01_01_000070_create_orders_table',1),(11,'2024_01_01_000080_create_order_photos_table',1),(12,'2024_01_01_000090_create_order_status_histories_table',1),(13,'2024_01_01_000100_create_reviews_table',1),(14,'2024_01_01_000110_create_payments_table',1),(15,'2024_01_01_000120_create_notifications_table',1),(16,'2024_01_01_000130_create_partner_earnings_table',1),(17,'2024_01_01_000140_create_app_settings_table',1),(18,'2026_10_19_062519_create_personal_access_tokens_table',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `notifications` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `type` varchar(50) NOT NULL DEFAULT 'info',
  `data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`data`)),
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifications_user_id_read_at_index` (`user_id`,`read_at`),
  CONSTRAINT `notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notifications`
--

LOCK TABLES `notifications` WRITE;
/*!40000 ALTER TABLE `notifications` DISABLE KEYS */;
/*!40000 ALTER TABLE `notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_photos`
--

DROP TABLE IF EXISTS `order_photos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `order_photos` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint(20) unsigned NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `original_name` varchar(255) DEFAULT NULL,
  `photo_type` enum('damage','before_work','after_work','evidence') NOT NULL DEFAULT 'damage',
  `file_size` bigint(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_photos_order_id_foreign` (`order_id`),
  CONSTRAINT `order_photos_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_photos`
--

LOCK TABLES `order_photos` WRITE;
/*!40000 ALTER TABLE `order_photos` DISABLE KEYS */;
/*!40000 ALTER TABLE `order_photos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_status_histories`
--

DROP TABLE IF EXISTS `order_status_histories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `order_status_histories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint(20) unsigned NOT NULL,
  `changed_by` bigint(20) unsigned NOT NULL,
  `old_status` varchar(50) DEFAULT NULL,
  `new_status` varchar(50) NOT NULL,
  `note` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_status_histories_changed_by_foreign` (`changed_by`),
  KEY `order_status_histories_order_id_index` (`order_id`),
  CONSTRAINT `order_status_histories_changed_by_foreign` FOREIGN KEY (`changed_by`) REFERENCES `users` (`id`),
  CONSTRAINT `order_status_histories_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_status_histories`
--

LOCK TABLES `order_status_histories` WRITE;
/*!40000 ALTER TABLE `order_status_histories` DISABLE KEYS */;
INSERT INTO `order_status_histories` VALUES (1,2,3,'arrived','in_progress','Sedang memeriksa jalur kabel pada instalasi pompa.','2026-10-19 06:41:00','2026-10-19 06:41:00');
/*!40000 ALTER TABLE `order_status_histories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `orders` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `order_number` varchar(30) NOT NULL,
  `customer_id` bigint(20) unsigned NOT NULL,
  `partner_id` bigint(20) unsigned DEFAULT NULL,
  `service_id` bigint(20) unsigned NOT NULL,
  `address_id` bigint(20) unsigned DEFAULT NULL,
  `problem_description` text NOT NULL,
  `address_text` text DEFAULT NULL,
  `landmark` varchar(255) DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `scheduled_at` timestamp NULL DEFAULT NULL,
  `assignment_method` enum('auto','manual') NOT NULL DEFAULT 'auto',
  `status` enum('pending','searching_technician','assigned','accepted','on_the_way','arrived','in_progress','awaiting_customer_confirmation','completed','cancelled') NOT NULL DEFAULT 'pending',
  `base_price` decimal(12,2) NOT NULL DEFAULT 0.00,
  `distance_km` decimal(8,2) NOT NULL DEFAULT 0.00,
  `travel_fee` decimal(10,2) NOT NULL DEFAULT 0.00,
  `difficulty_level` enum('light','medium','heavy') NOT NULL DEFAULT 'medium',
  `difficulty_fee` decimal(10,2) NOT NULL DEFAULT 0.00,
  `additional_fee` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total_estimate` decimal(12,2) NOT NULL DEFAULT 0.00,
  `final_price` decimal(12,2) DEFAULT NULL,
  `platform_commission` decimal(10,2) DEFAULT NULL,
  `partner_earning` decimal(10,2) DEFAULT NULL,
  `cancellation_reason` varchar(255) DEFAULT NULL,
  `cancelled_by` enum('customer','partner','admin') DEFAULT NULL,
  `accepted_at` timestamp NULL DEFAULT NULL,
  `started_at` timestamp NULL DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `orders_order_number_unique` (`order_number`),
  KEY `orders_service_id_foreign` (`service_id`),
  KEY `orders_address_id_foreign` (`address_id`),
  KEY `orders_customer_id_status_index` (`customer_id`,`status`),
  KEY `orders_partner_id_status_index` (`partner_id`,`status`),
  KEY `orders_status_index` (`status`),
  KEY `orders_order_number_index` (`order_number`),
  CONSTRAINT `orders_address_id_foreign` FOREIGN KEY (`address_id`) REFERENCES `addresses` (`id`) ON DELETE SET NULL,
  CONSTRAINT `orders_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `users` (`id`),
  CONSTRAINT `orders_partner_id_foreign` FOREIGN KEY (`partner_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `orders_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` VALUES (1,'BA202610180001',2,3,1,NULL,'AC kamar utama hembusan anginnya kecil dan agak bau apek, butuh dicuci.','Jl. Menteng Raya No. 45, RT 02/RW 03, Jakarta Pusat','Pagar hitam samping minimarket',-6.18800000,106.83400000,'2026-10-18 06:41:00','auto','completed',75000.00,2.30,11500.00,'light',0.00,0.00,86500.00,86500.00,12975.00,73525.00,NULL,NULL,'2026-10-18 06:51:00','2026-10-18 07:26:00','2026-10-18 08:41:00','2026-10-19 06:41:00','2026-10-19 06:41:00',NULL),(2,'BA202610190001',2,3,7,NULL,'MCB listrik lantai 2 turun tiap kali menyalakan pompa air atau kulkas.','Jl. Menteng Raya No. 45, Jakarta Pusat',NULL,-6.18800000,106.83400000,'2026-10-19 06:41:00','manual','in_progress',100000.00,2.30,11500.00,'medium',25000.00,0.00,136500.00,136500.00,20475.00,116025.00,NULL,NULL,'2026-10-19 05:51:00','2026-10-19 06:26:00',NULL,'2026-10-19 06:41:00','2026-10-19 06:41:00',NULL),(3,'BA202610190002',2,NULL,9,NULL,'Pipa sambungan di bawah bak cuci piring bocor rembes ke lemari dapur.','Jl. Menteng Raya No. 45, Jakarta Pusat',NULL,-6.18800000,106.83400000,'2026-10-19 09:41:00','auto','searching_technician',90000.00,3.50,17500.00,'medium',25000.00,0.00,132500.00,132500.00,19875.00,112625.00,NULL,NULL,NULL,NULL,NULL,'2026-10-19 06:41:00','2026-10-19 06:41:00',NULL);
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `partner_earnings`
--

DROP TABLE IF EXISTS `partner_earnings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `partner_earnings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `partner_id` bigint(20) unsigned NOT NULL,
  `order_id` bigint(20) unsigned DEFAULT NULL,
  `type` enum('earning','withdrawal','adjustment') NOT NULL DEFAULT 'earning',
  `amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `platform_commission` decimal(10,2) NOT NULL DEFAULT 0.00,
  `net_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `description` varchar(255) DEFAULT NULL,
  `status` enum('pending','settled','withdrawn') NOT NULL DEFAULT 'settled',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `partner_earnings_order_id_foreign` (`order_id`),
  KEY `partner_earnings_partner_id_index` (`partner_id`),
  CONSTRAINT `partner_earnings_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE SET NULL,
  CONSTRAINT `partner_earnings_partner_id_foreign` FOREIGN KEY (`partner_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `partner_earnings`
--

LOCK TABLES `partner_earnings` WRITE;
/*!40000 ALTER TABLE `partner_earnings` DISABLE KEYS */;
INSERT INTO `partner_earnings` VALUES (1,3,1,'earning',86500.00,12975.00,73525.00,'Penghasilan dari pesanan #BA202610180001','settled','2026-10-19 06:41:00','2026-10-19 06:41:00');
/*!40000 ALTER TABLE `partner_earnings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `partner_profiles`
--

DROP TABLE IF EXISTS `partner_profiles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `partner_profiles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `id_card_number` varchar(30) DEFAULT NULL,
  `id_card_photo` varchar(255) DEFAULT NULL,
  `certificate_photo` varchar(255) DEFAULT NULL,
  `verification_status` enum('pending','verified','rejected') NOT NULL DEFAULT 'pending',
  `verification_note` varchar(255) DEFAULT NULL,
  `availability_status` enum('online','offline','busy') NOT NULL DEFAULT 'offline',
  `current_latitude` decimal(10,8) DEFAULT NULL,
  `current_longitude` decimal(11,8) DEFAULT NULL,
  `average_rating` decimal(3,2) NOT NULL DEFAULT 0.00,
  `total_reviews` int(11) NOT NULL DEFAULT 0,
  `completed_jobs` int(11) NOT NULL DEFAULT 0,
  `years_experience` int(11) NOT NULL DEFAULT 0,
  `balance` decimal(15,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `partner_profiles_user_id_foreign` (`user_id`),
  CONSTRAINT `partner_profiles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `partner_profiles`
--

LOCK TABLES `partner_profiles` WRITE;
/*!40000 ALTER TABLE `partner_profiles` DISABLE KEYS */;
INSERT INTO `partner_profiles` VALUES (1,3,NULL,'Teknisi spesialis AC dan instalasi listrik rumah tangga berpengalaman lebih dari 8 tahun.','3171011508850001',NULL,NULL,'verified','Dokumen KTP dan sertifikat kompetensi BNSP terverifikasi valid.','online',-6.18200000,106.82300000,4.90,28,42,8,1500000.00,'2026-10-19 06:40:59','2026-10-19 06:40:59'),(2,4,NULL,'Ahli perpipaan, pompa air, dan saluran mampet. Melayani area Jakarta Pusat dan Selatan.','3171021003880002',NULL,NULL,'verified','KTP dan SKCK terverifikasi.','online',-6.21000000,106.81500000,4.85,19,31,5,950000.00,'2026-10-19 06:41:00','2026-10-19 06:41:00'),(3,5,NULL,'Teknisi perbaikan elektronik rumah tangga dan kulkas.','3171032512920003',NULL,NULL,'pending',NULL,'offline',NULL,NULL,0.00,0,0,3,0.00,'2026-10-19 06:41:00','2026-10-19 06:41:00');
/*!40000 ALTER TABLE `partner_profiles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `partner_skills`
--

DROP TABLE IF EXISTS `partner_skills`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `partner_skills` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `partner_id` bigint(20) unsigned NOT NULL,
  `category_id` bigint(20) unsigned NOT NULL,
  `experience_years` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `partner_skills_partner_id_category_id_unique` (`partner_id`,`category_id`),
  KEY `partner_skills_category_id_foreign` (`category_id`),
  CONSTRAINT `partner_skills_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `service_categories` (`id`) ON DELETE CASCADE,
  CONSTRAINT `partner_skills_partner_id_foreign` FOREIGN KEY (`partner_id`) REFERENCES `partner_profiles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `partner_skills`
--

LOCK TABLES `partner_skills` WRITE;
/*!40000 ALTER TABLE `partner_skills` DISABLE KEYS */;
INSERT INTO `partner_skills` VALUES (1,1,1,8,'2026-10-19 06:40:59','2026-10-19 06:40:59'),(2,1,3,6,'2026-10-19 06:40:59','2026-10-19 06:40:59'),(3,2,4,5,'2026-10-19 06:41:00','2026-10-19 06:41:00');
/*!40000 ALTER TABLE `partner_skills` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payments`
--

DROP TABLE IF EXISTS `payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `payments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint(20) unsigned NOT NULL,
  `payment_method` enum('cash','qris','transfer','gopay','ovo','dana') NOT NULL DEFAULT 'cash',
  `amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `status` enum('pending','paid','failed','refunded') NOT NULL DEFAULT 'pending',
  `provider_reference` varchar(255) DEFAULT NULL,
  `provider_response` text DEFAULT NULL,
  `paid_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `payments_order_id_unique` (`order_id`),
  CONSTRAINT `payments_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payments`
--

LOCK TABLES `payments` WRITE;
/*!40000 ALTER TABLE `payments` DISABLE KEYS */;
INSERT INTO `payments` VALUES (1,1,'cash',86500.00,'paid',NULL,NULL,'2026-10-18 08:41:00','2026-10-19 06:41:00','2026-10-19 06:41:00');
/*!40000 ALTER TABLE `payments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) unsigned NOT NULL,
  `name` text NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  KEY `personal_access_tokens_expires_at_index` (`expires_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal_access_tokens`
--

LOCK TABLES `personal_access_tokens` WRITE;
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pricing_rules`
--

DROP TABLE IF EXISTS `pricing_rules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pricing_rules` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `service_id` bigint(20) unsigned DEFAULT NULL,
  `category_id` bigint(20) unsigned DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `base_price` decimal(12,2) NOT NULL DEFAULT 0.00,
  `travel_fee_per_km` decimal(10,2) NOT NULL DEFAULT 5000.00,
  `minimum_travel_fee` decimal(10,2) NOT NULL DEFAULT 0.00,
  `difficulty_fee_light` decimal(10,2) NOT NULL DEFAULT 0.00,
  `difficulty_fee_medium` decimal(10,2) NOT NULL DEFAULT 25000.00,
  `difficulty_fee_heavy` decimal(10,2) NOT NULL DEFAULT 60000.00,
  `platform_commission_rate` decimal(5,2) NOT NULL DEFAULT 15.00,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pricing_rules_service_id_foreign` (`service_id`),
  KEY `pricing_rules_category_id_foreign` (`category_id`),
  CONSTRAINT `pricing_rules_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `service_categories` (`id`) ON DELETE SET NULL,
  CONSTRAINT `pricing_rules_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pricing_rules`
--

LOCK TABLES `pricing_rules` WRITE;
/*!40000 ALTER TABLE `pricing_rules` DISABLE KEYS */;
INSERT INTO `pricing_rules` VALUES (1,NULL,1,'Tarif Standar Service AC',75000.00,5000.00,10000.00,0.00,25000.00,60000.00,15.00,1,'2026-10-19 06:40:58','2026-10-19 06:40:58'),(2,NULL,2,'Tarif Standar Elektronik & TV',75000.00,5000.00,10000.00,0.00,25000.00,60000.00,15.00,1,'2026-10-19 06:40:58','2026-10-19 06:40:58'),(3,NULL,3,'Tarif Standar Kelistrikan Rumah',75000.00,5000.00,10000.00,0.00,25000.00,60000.00,15.00,1,'2026-10-19 06:40:58','2026-10-19 06:40:58'),(4,NULL,4,'Tarif Standar Plumbing & Pipa Air',75000.00,5000.00,10000.00,0.00,25000.00,60000.00,15.00,1,'2026-10-19 06:40:58','2026-10-19 06:40:58'),(5,NULL,5,'Tarif Standar Kulkas & Freezer',75000.00,5000.00,10000.00,0.00,25000.00,60000.00,15.00,1,'2026-10-19 06:40:58','2026-10-19 06:40:58'),(6,NULL,6,'Tarif Standar Komputer & Laptop',75000.00,5000.00,10000.00,0.00,25000.00,60000.00,15.00,1,'2026-10-19 06:40:58','2026-10-19 06:40:58'),(7,NULL,7,'Tarif Standar Perbaikan Rumah',75000.00,5000.00,10000.00,0.00,25000.00,60000.00,15.00,1,'2026-10-19 06:40:58','2026-10-19 06:40:58');
/*!40000 ALTER TABLE `pricing_rules` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reviews`
--

DROP TABLE IF EXISTS `reviews`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `reviews` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint(20) unsigned NOT NULL,
  `customer_id` bigint(20) unsigned NOT NULL,
  `partner_id` bigint(20) unsigned NOT NULL,
  `rating` tinyint(3) unsigned NOT NULL,
  `comment` text DEFAULT NULL,
  `is_visible` tinyint(1) NOT NULL DEFAULT 1,
  `moderated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `reviews_order_id_unique` (`order_id`),
  KEY `reviews_customer_id_foreign` (`customer_id`),
  KEY `reviews_partner_id_index` (`partner_id`),
  CONSTRAINT `reviews_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `users` (`id`),
  CONSTRAINT `reviews_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `reviews_partner_id_foreign` FOREIGN KEY (`partner_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reviews`
--

LOCK TABLES `reviews` WRITE;
/*!40000 ALTER TABLE `reviews` DISABLE KEYS */;
INSERT INTO `reviews` VALUES (1,1,2,3,5,'Pak Agus sangat teliti, hasil cuci AC bersih dan tidak ada air menetes di lantai.',1,NULL,'2026-10-19 06:41:00','2026-10-19 06:41:00');
/*!40000 ALTER TABLE `reviews` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `service_categories`
--

DROP TABLE IF EXISTS `service_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `service_categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `service_categories_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `service_categories`
--

LOCK TABLES `service_categories` WRITE;
/*!40000 ALTER TABLE `service_categories` DISABLE KEYS */;
INSERT INTO `service_categories` VALUES (1,'Service AC','service-ac','Cuci AC, perbaikan bocor, isi freon, bongkar pasang oleh teknisi spesialis tata udara berlisensi.','bi-snow',NULL,1,1,'2026-10-19 06:40:58','2026-10-19 06:40:58'),(2,'Elektronik & TV','elektronik-tv','Perbaikan TV LED/LCD, mesin cuci, microwave, dispenser, dan peralatan elektronik rumah tangga lainnya.','bi-tv',NULL,1,2,'2026-10-19 06:40:58','2026-10-19 06:40:58'),(3,'Kelistrikan Rumah','kelistrikan','Solusi korsleting listrik, perbaikan MCB, pasang instalasi stop kontak, panel listrik & lampu.','bi-lightning-charge',NULL,1,3,'2026-10-19 06:40:58','2026-10-19 06:40:58'),(4,'Plumbing & Pipa Air','plumbing','Perbaikan pipa bocor, instalasi toren, keran air patah, pompa air mati, hingga saluran mampet.','bi-droplet',NULL,1,4,'2026-10-19 06:40:58','2026-10-19 06:40:58'),(5,'Kulkas & Freezer','kulkas-freezer','Service kulkas 1 & 2 pintu, freezer box, showcase tidak dingin, kompresor mati, dan ganti karet pintu.','bi-box-seam',NULL,1,5,'2026-10-19 06:40:58','2026-10-19 06:40:58'),(6,'Komputer & Laptop','komputer-laptop','Install ulang, pembersihan pasta thermal, ganti SSD/RAM, perbaikan laptop mati total atau lemot.','bi-laptop',NULL,1,6,'2026-10-19 06:40:58','2026-10-19 06:40:58'),(7,'Perbaikan Rumah','perbaikan-rumah','Pengecatan dinding, perbaikan atap bocor, engsel pintu rusak, perakitan perabot furnitur knock-down.','bi-house-gear',NULL,1,7,'2026-10-19 06:40:58','2026-10-19 06:40:58');
/*!40000 ALTER TABLE `service_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `services`
--

DROP TABLE IF EXISTS `services`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `services` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `category_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `base_price` decimal(12,2) NOT NULL DEFAULT 0.00,
  `estimated_duration_minutes` int(11) NOT NULL DEFAULT 60,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `services_slug_unique` (`slug`),
  KEY `services_category_id_foreign` (`category_id`),
  CONSTRAINT `services_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `service_categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `services`
--

LOCK TABLES `services` WRITE;
/*!40000 ALTER TABLE `services` DISABLE KEYS */;
INSERT INTO `services` VALUES (1,1,'Cuci AC Split (0.5 - 2 PK)','cuci-ac-split','Pembersihan filter, evaporator, dan outdoor unit menggunakan jet cleaner bertekanan tinggi.',75000.00,45,1,1,'2026-10-19 06:40:58','2026-10-19 06:40:58'),(2,1,'Bongkar Pasang AC','bongkar-pasang-ac','Pelepasan unit lama dan instalasi unit baru lengkap dengan perapihan pipa dan vakum.',250000.00,120,1,2,'2026-10-19 06:40:58','2026-10-19 06:40:58'),(3,1,'Tambah Freon R32 / R410A','tambah-freon-r32-r410a','Pengisian freon ramah lingkungan tipe R32 atau R410A untuk AC yang kurang dingin.',150000.00,45,1,3,'2026-10-19 06:40:58','2026-10-19 06:40:58'),(4,1,'Perbaikan AC Bocor Air','perbaikan-ac-bocor-air','Perbaikan saluran pembuangan air kondensasi yang tersumbat lendir atau kotoran.',120000.00,60,1,4,'2026-10-19 06:40:58','2026-10-19 06:40:58'),(5,2,'Service TV LED Mati Total / Layar Gelap','service-tv-led','Pengecekan power supply, penggantian backlight LED, atau modul T-Con.',150000.00,90,1,1,'2026-10-19 06:40:58','2026-10-19 06:40:58'),(6,2,'Service Mesin Cuci 1 & 2 Tabung','service-mesin-cuci','Perbaikan tabung tidak berputar, air tidak keluar, getaran kencang, atau dinamo macet.',125000.00,90,1,2,'2026-10-19 06:40:58','2026-10-19 06:40:58'),(7,3,'Pencarian & Perbaikan Korsleting Listrik','perbaikan-korsleting-listrik','Deteksi titik kebocoran arus atau kabel hangus yang menyebabkan MCB turun terus-menerus.',100000.00,60,1,1,'2026-10-19 06:40:58','2026-10-19 06:40:58'),(8,3,'Pasang Titik Stop Kontak & Saklar Lampu','pasang-stop-kontak','Pemasangan stop kontak baru atau penambahan jalur saklar lampu rumah.',50000.00,40,1,2,'2026-10-19 06:40:58','2026-10-19 06:40:58'),(9,4,'Perbaikan Pipa Air Bocor','perbaikan-pipa-bocor','Penyambungan dan penggantian pipa PVC / PPR bocor pada dinding atau plafon.',90000.00,60,1,1,'2026-10-19 06:40:58','2026-10-19 06:40:58'),(10,4,'Pelancaran Saluran Air Mampet','saluran-air-mampet','Pembersihan pipa pembuangan wastafel, floor drain, atau toilet yang tersumbat.',150000.00,60,1,2,'2026-10-19 06:40:58','2026-10-19 06:40:58'),(11,5,'Service Kulkas Tidak Dingin','service-kulkas-tidak-dingin','Pengecekan relay, overload, defros termostat, fan evaporator, dan kompresor.',130000.00,75,1,1,'2026-10-19 06:40:58','2026-10-19 06:40:58'),(12,6,'Install Ulang & Optimalisasi Laptop','install-ulang-laptop','Instalasi sistem operasi bersih, driver resmi, aplikasi standar, dan pembersihan malware.',100000.00,90,1,1,'2026-10-19 06:40:58','2026-10-19 06:40:58'),(13,7,'Perakitan Perabot & Furnitur Knock-Down','perakitan-furnitur','Perakitan lemari, meja kerja, rak buku, atau tempat tidur knock-down IKEA / Informa.',120000.00,90,1,1,'2026-10-19 06:40:58','2026-10-19 06:40:58');
/*!40000 ALTER TABLE `services` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('TKn7JCXogrfxTCEG8sXUuIpO73grDH19wYZV7YkU',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiR29rYjFyRGx2bFNoT29pWTRrN1V5bm1QSzR2RTNzQjlONEN2ZHZvcyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzk6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9jdXN0b21lci9lc3RpbWF0ZSI7czo1OiJyb3V0ZSI7czoxNzoiY3VzdG9tZXIuZXN0aW1hdGUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1792418116);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('customer','partner','admin') NOT NULL DEFAULT 'customer',
  `status` enum('active','inactive','suspended') NOT NULL DEFAULT 'active',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `users_phone_unique` (`phone`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Administrator BetulinAja','admin@betulinaja.id','081200000001','$2y$12$j4XUFuwDQYDYgnhAZN9YaeZ5bQLSLIhUottGJKRUInqDIrayzxFcy','admin','active','2026-10-19 06:40:58',NULL,'2026-10-19 06:40:58','2026-10-19 06:40:58',NULL),(2,'Budi Pratama','customer@demo.id','081234567890','$2y$12$yVlYmk50P8sniNCwx1G5dePPz1hlCobIY2ifq/8oAt.XDibuTdPGy','customer','active','2026-10-19 06:40:59',NULL,'2026-10-19 06:40:59','2026-10-19 06:40:59',NULL),(3,'Agus Santoso','partner@demo.id','081298765432','$2y$12$R/6uaJaDFBUK1kfcjw5hHO.IdvmNEuNh3y84WLixvk5H90YtFaene','partner','active','2026-10-19 06:40:59',NULL,'2026-10-19 06:40:59','2026-10-19 06:40:59',NULL),(4,'Bambang Wijaya','bambang.plumbing@demo.id','081298765433','$2y$12$k6W5v8sFW9yhkUPmbgqj4.rjSmKJA4Qlg1KUCG.1BX/O6GBczvKVe','partner','active','2026-10-19 06:40:59',NULL,'2026-10-19 06:40:59','2026-10-19 06:40:59',NULL),(5,'Eko Prasetyo','eko.pending@demo.id','081298765434','$2y$12$qJDBViWqwUrJdoxdTFEuFe9guL8pZi75Wrh8pXLJwjNLBdL0FejUq','partner','active','2026-10-19 06:41:00',NULL,'2026-10-19 06:41:00','2026-10-19 06:41:00',NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-10-19 20:55:39
