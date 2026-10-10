-- Ideban Almas: full schema and sample data for MySQL 5.7+ / MariaDB 10.2+.
-- Generated from the Laravel migrations (2014 .. 2026_10_11_000001) and seeders.
-- Import ONLY into an EMPTY database:
--   mysql -u USER -p DATABASE < database/ideban_mysql.sql
-- The migrations ledger is included, so do NOT run `php artisan migrate` afterwards.
--
-- ADMIN LOGIN (change the password after the first sign-in):
--   Login page: /login    Username: admin    Password: IdebanAlmas#Gold2026
--   The password is stored as a bcrypt hash. The email admin@ideban.local also works.
--
-- Sample data is clearly labelled (Persian and English "sample" / "نمونه"):
--   portfolio entries contain "Demo" in client_name; two leads have source "sample";
--   two academy courses, one discount code (SAMPLE10), three homepage slides
--   (images are original generated illustrations in public/images/samples and public/images, flagged is_sample) and
--   three add-ons (quote only, no amounts). Featured services, service categories, pricing plans and
--   sample portfolio items reference images in public/images/samples (labelled SAMPLE).
--   Two SAMPLE staff resumes (fictional people, is_sample=1) with work, education,
--   skills and certificates; they are shown on /team. Replace them before publishing.
--   Lessons have no video files attached.
--   Replace or delete them in the admin panel before going live.
/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19-11.8.6-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: 127.0.0.1    Database: ideban_almas
-- ------------------------------------------------------
-- Server version	11.8.6-MariaDB-0+deb13u1 from Debian

/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19-11.8.6-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: 127.0.0.1    Database: ideban_almas
-- ------------------------------------------------------
-- Server version	11.8.6-MariaDB-0+deb13u1 from Debian

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*M!100616 SET @OLD_NOTE_VERBOSITY=@@NOTE_VERBOSITY, NOTE_VERBOSITY=0 */;

--
-- Table structure for table `articles`
--

DROP TABLE IF EXISTS `articles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `articles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `slug` varchar(255) NOT NULL,
  `title_fa` varchar(255) NOT NULL,
  `title_en` varchar(255) NOT NULL,
  `excerpt_fa` varchar(500) DEFAULT NULL,
  `excerpt_en` varchar(500) DEFAULT NULL,
  `body_fa` longtext DEFAULT NULL,
  `body_en` longtext DEFAULT NULL,
  `category` varchar(80) DEFAULT NULL,
  `tags` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`tags`)),
  `cover_url` text DEFAULT NULL,
  `author_name` varchar(120) DEFAULT NULL,
  `service_id` bigint(20) unsigned DEFAULT NULL,
  `meta_title` varchar(190) DEFAULT NULL,
  `meta_description` varchar(320) DEFAULT NULL,
  `is_published` tinyint(1) NOT NULL DEFAULT 0,
  `published_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `cover_path` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `articles_slug_unique` (`slug`),
  KEY `articles_service_id_foreign` (`service_id`),
  KEY `articles_is_published_published_at_index` (`is_published`,`published_at`),
  CONSTRAINT `articles_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `articles`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `articles` WRITE;
/*!40000 ALTER TABLE `articles` DISABLE KEYS */;
INSERT INTO `articles` VALUES
(1,'website-backup-checklist','چک‌لیست بکاپ‌گیری از وب‌سایت','A practical website backup checklist','بکاپ فقط زمانی ارزش دارد که بتوان آن را بازیابی کرد. این فهرست کوتاه را پیش از هر به‌روزرسانی مرور کنید.','A backup is only useful if you can restore it. Review this short list before every update.','## چرا بکاپ کافی نیست؟\n\nبکاپی که هرگز آزمایش بازیابی نشده، فقط یک فایل است. هدف این است که در بدترین حالت بتوانید سایت را در زمان قابل قبول برگردانید.\n\n## فهرست پیشنهادی\n\n- بکاپ از پایگاه داده و فایل‌های آپلودی به‌صورت جداگانه تهیه شود.\n- نسخه‌ها در مکانی غیر از سرور اصلی نگهداری شوند.\n- حداقل یک بار در ماه بازیابی آزمایشی انجام شود.\n- دسترسی به فایل‌های بکاپ محدود به افراد مجاز باشد.\n\n## نکته امنیتی\n\nفایل `.env` و کلیدهای دسترسی را هرگز داخل بکاپ عمومی یا مخازن کد قرار ندهید.','## Why a backup alone is not enough\n\nA backup that has never been restored is only a file. The goal is to bring the site back within an acceptable time when something goes wrong.\n\n## Suggested checklist\n\n- Back up the database and uploaded files separately.\n- Store copies away from the production server.\n- Run a test restore at least once a month.\n- Limit access to backup files to authorised people.\n\n## Security note\n\nNever place `.env` files or access keys in public backups or code repositories.','امنیت و نگهداری / Security & maintenance','[\"backup\",\"security\"]','/images/article-backup.jpg','Ideban Almas',NULL,NULL,NULL,1,'2026-10-03 18:19:49','2026-10-10 18:19:49','2026-10-10 18:19:49',NULL),
(2,'choosing-a-website-platform','انتخاب بستر مناسب برای وب‌سایت کسب‌وکار','Choosing the right platform for a business website','پیش از انتخاب قالب یا ابزار، نیاز، بودجه، توان نگهداری و مسیر رشد را مشخص کنید.','Before choosing a theme or tool, define your needs, budget, maintenance capacity and growth path.','## سه پرسش کلیدی\n\n۱. سایت باید چه کاری انجام دهد: معرفی، فروش یا پشتیبانی؟\n۲. چه کسی بعداً محتوا و امکانات را به‌روز می‌کند؟\n۳. چه حجمی از داده و ترافیک را پیش‌بینی می‌کنید؟\n\n## مقایسه گزینه‌ها\n\nمعمولاً وب‌سایت معرفی ساده، فروشگاه آنلاین و نرم‌افزار سفارشی نیازهای متفاوتی دارند. انتخاب ابزار باید بر پایه همین نیازها باشد، نه صرفاً محبوبیت آن.','## Three key questions\n\n1. What should the site do: inform, sell or support?\n2. Who will update content and features later?\n3. What volume of data and traffic do you expect?\n\n## Comparing options\n\nA simple brochure site, an online shop and custom software have different requirements. Pick the tool based on those requirements rather than popularity alone.','راهنما / Guides','[\"website\",\"planning\"]','/images/article-platform.jpg','Ideban Almas',NULL,NULL,NULL,1,'2026-10-07 18:19:49','2026-10-10 18:19:49','2026-10-10 18:19:49',NULL);
/*!40000 ALTER TABLE `articles` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `courses`
--

DROP TABLE IF EXISTS `courses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `courses` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `slug` varchar(255) NOT NULL,
  `title_fa` varchar(255) NOT NULL,
  `title_en` varchar(255) NOT NULL,
  `instructor_fa` varchar(190) DEFAULT NULL,
  `instructor_en` varchar(190) DEFAULT NULL,
  `summary_fa` text DEFAULT NULL,
  `summary_en` text DEFAULT NULL,
  `description_fa` longtext DEFAULT NULL,
  `description_en` longtext DEFAULT NULL,
  `category` varchar(80) DEFAULT NULL,
  `level` varchar(30) NOT NULL DEFAULT 'beginner',
  `duration_minutes` int(10) unsigned DEFAULT NULL,
  `prerequisite_fa` varchar(255) DEFAULT NULL,
  `prerequisite_en` varchar(255) DEFAULT NULL,
  `price` bigint(20) unsigned NOT NULL DEFAULT 0,
  `is_free` tinyint(1) NOT NULL DEFAULT 0,
  `is_published` tinyint(1) NOT NULL DEFAULT 0,
  `cover_url` text DEFAULT NULL,
  `sort_order` int(10) unsigned NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `cover_path` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `courses_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `courses`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `courses` WRITE;
/*!40000 ALTER TABLE `courses` DISABLE KEYS */;
INSERT INTO `courses` VALUES
(1,'sample-website-launch-basics','نمونه: مبانی راه‌اندازی وب‌سایت کسب‌وکار','Sample: Business website launch basics','مربی نمونه','Sample instructor','دوره نمونه برای آشنایی با دامنه، میزبانی و انتشار اولین وب‌سایت.','Sample course on domains, hosting and publishing a first website.',NULL,NULL,'شروع کسب‌وکار اینترنتی','beginner',60,NULL,NULL,0,1,1,NULL,1,'2026-10-10 18:19:49','2026-10-10 18:19:49',NULL),
(2,'sample-linux-server-security','نمونه: امنیت پایه سرور لینوکس','Sample: Linux server security basics','مربی نمونه','Sample instructor','دوره نمونه درباره سخت‌سازی پایه سرور و به‌روزرسانی امن.','Sample course on basic server hardening and safe updates.',NULL,NULL,'سرور و Linux','intermediate',60,NULL,NULL,1500000,0,1,NULL,2,'2026-10-10 18:19:49','2026-10-10 18:19:49',NULL);
/*!40000 ALTER TABLE `courses` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `discount_codes`
--

DROP TABLE IF EXISTS `discount_codes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `discount_codes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(40) NOT NULL,
  `type` varchar(10) NOT NULL DEFAULT 'percent',
  `value` int(10) unsigned NOT NULL,
  `course_id` bigint(20) unsigned DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL,
  `max_uses` int(10) unsigned DEFAULT NULL,
  `used_count` int(10) unsigned NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `discount_codes_code_unique` (`code`),
  KEY `discount_codes_course_id_foreign` (`course_id`),
  CONSTRAINT `discount_codes_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `discount_codes`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `discount_codes` WRITE;
/*!40000 ALTER TABLE `discount_codes` DISABLE KEYS */;
INSERT INTO `discount_codes` VALUES
(1,'SAMPLE10','percent',10,NULL,NULL,NULL,0,1,'2026-10-10 18:19:49','2026-10-10 18:19:49');
/*!40000 ALTER TABLE `discount_codes` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `enrollments`
--

DROP TABLE IF EXISTS `enrollments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `enrollments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `course_id` bigint(20) unsigned NOT NULL,
  `source` varchar(20) NOT NULL DEFAULT 'free',
  `payment_id` bigint(20) unsigned DEFAULT NULL,
  `last_lesson_id` bigint(20) unsigned DEFAULT NULL,
  `granted_at` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `enrollments_user_id_course_id_unique` (`user_id`,`course_id`),
  KEY `enrollments_course_id_foreign` (`course_id`),
  KEY `enrollments_payment_id_foreign` (`payment_id`),
  KEY `enrollments_last_lesson_id_foreign` (`last_lesson_id`),
  CONSTRAINT `enrollments_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  CONSTRAINT `enrollments_last_lesson_id_foreign` FOREIGN KEY (`last_lesson_id`) REFERENCES `lessons` (`id`) ON DELETE SET NULL,
  CONSTRAINT `enrollments_payment_id_foreign` FOREIGN KEY (`payment_id`) REFERENCES `payments` (`id`) ON DELETE SET NULL,
  CONSTRAINT `enrollments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `enrollments`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `enrollments` WRITE;
/*!40000 ALTER TABLE `enrollments` DISABLE KEYS */;
/*!40000 ALTER TABLE `enrollments` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
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

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `invoices`
--

DROP TABLE IF EXISTS `invoices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `invoices` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `number` varchar(30) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `order_id` bigint(20) unsigned DEFAULT NULL,
  `course_id` bigint(20) unsigned DEFAULT NULL,
  `type` varchar(20) NOT NULL DEFAULT 'invoice',
  `status` varchar(20) NOT NULL DEFAULT 'issued',
  `items` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`items`)),
  `subtotal` bigint(20) unsigned NOT NULL DEFAULT 0,
  `discount` bigint(20) unsigned NOT NULL DEFAULT 0,
  `extra_costs` bigint(20) unsigned NOT NULL DEFAULT 0,
  `total` bigint(20) unsigned NOT NULL DEFAULT 0,
  `valid_until` date DEFAULT NULL,
  `paid_at` datetime DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `invoices_number_unique` (`number`),
  KEY `invoices_user_id_foreign` (`user_id`),
  KEY `invoices_order_id_foreign` (`order_id`),
  KEY `invoices_course_id_index` (`course_id`),
  CONSTRAINT `invoices_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE SET NULL,
  CONSTRAINT `invoices_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE SET NULL,
  CONSTRAINT `invoices_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `invoices`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `invoices` WRITE;
/*!40000 ALTER TABLE `invoices` DISABLE KEYS */;
/*!40000 ALTER TABLE `invoices` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `leads`
--

DROP TABLE IF EXISTS `leads`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `leads` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `company` varchar(255) DEFAULT NULL,
  `phone` varchar(30) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `service_id` bigint(20) unsigned DEFAULT NULL,
  `plan_id` bigint(20) unsigned DEFAULT NULL,
  `source` varchar(100) NOT NULL DEFAULT 'website',
  `message` text DEFAULT NULL,
  `stage` varchar(30) NOT NULL DEFAULT 'new',
  `assigned_to` bigint(20) unsigned DEFAULT NULL,
  `follow_up_at` datetime DEFAULT NULL,
  `expected_value` bigint(20) unsigned DEFAULT NULL,
  `sales_note` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `leads_service_id_foreign` (`service_id`),
  KEY `leads_plan_id_foreign` (`plan_id`),
  KEY `leads_assigned_to_foreign` (`assigned_to`),
  KEY `leads_stage_follow_up_at_index` (`stage`,`follow_up_at`),
  CONSTRAINT `leads_assigned_to_foreign` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `leads_plan_id_foreign` FOREIGN KEY (`plan_id`) REFERENCES `pricing_plans` (`id`) ON DELETE SET NULL,
  CONSTRAINT `leads_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `leads`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `leads` WRITE;
/*!40000 ALTER TABLE `leads` DISABLE KEYS */;
INSERT INTO `leads` VALUES
(1,'نمونه مشتری ۱ (Demo)','Demo Co.','09120000001',NULL,1,NULL,'sample','داده نمونه برای نمایش پنل فروش.','new',NULL,NULL,NULL,NULL,'2026-10-10 18:19:49','2026-10-10 18:19:49'),
(2,'نمونه مشتری ۲ (Demo)','Demo Retail','09120000002',NULL,1,NULL,'sample','داده نمونه برای نمایش پیگیری.','proposal',NULL,NULL,45000000,NULL,'2026-10-10 18:19:49','2026-10-10 18:19:49');
/*!40000 ALTER TABLE `leads` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `lesson_progress`
--

DROP TABLE IF EXISTS `lesson_progress`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `lesson_progress` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `lesson_id` bigint(20) unsigned NOT NULL,
  `completed_at` datetime DEFAULT NULL,
  `last_position_seconds` int(10) unsigned NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `lesson_progress_user_id_lesson_id_unique` (`user_id`,`lesson_id`),
  KEY `lesson_progress_lesson_id_foreign` (`lesson_id`),
  CONSTRAINT `lesson_progress_lesson_id_foreign` FOREIGN KEY (`lesson_id`) REFERENCES `lessons` (`id`) ON DELETE CASCADE,
  CONSTRAINT `lesson_progress_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lesson_progress`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `lesson_progress` WRITE;
/*!40000 ALTER TABLE `lesson_progress` DISABLE KEYS */;
/*!40000 ALTER TABLE `lesson_progress` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `lessons`
--

DROP TABLE IF EXISTS `lessons`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `lessons` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `course_id` bigint(20) unsigned NOT NULL,
  `title_fa` varchar(255) NOT NULL,
  `title_en` varchar(255) NOT NULL,
  `sort_order` int(10) unsigned NOT NULL DEFAULT 0,
  `source` varchar(20) NOT NULL DEFAULT 'none',
  `file_path` varchar(255) DEFAULT NULL,
  `external_url` text DEFAULT NULL,
  `duration_seconds` int(10) unsigned DEFAULT NULL,
  `is_free_preview` tinyint(1) NOT NULL DEFAULT 0,
  `is_published` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `thumbnail_path` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `lessons_course_id_foreign` (`course_id`),
  CONSTRAINT `lessons_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lessons`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `lessons` WRITE;
/*!40000 ALTER TABLE `lessons` DISABLE KEYS */;
INSERT INTO `lessons` VALUES
(1,1,'معرفی دوره (نمونه)','Course introduction (sample)',1,'none',NULL,NULL,NULL,1,1,'2026-10-10 18:19:49','2026-10-10 18:19:49',NULL),
(2,1,'انتخاب دامنه و میزبانی (نمونه)','Choosing a domain and hosting (sample)',2,'none',NULL,NULL,NULL,0,1,'2026-10-10 18:19:49','2026-10-10 18:19:49',NULL),
(3,2,'مقدمه امنیت سرور (نمونه)','Server security introduction (sample)',1,'none',NULL,NULL,NULL,1,1,'2026-10-10 18:19:49','2026-10-10 18:19:49',NULL),
(4,2,'کلیدهای SSH و فایروال (نمونه)','SSH keys and firewall (sample)',2,'none',NULL,NULL,NULL,0,1,'2026-10-10 18:19:49','2026-10-10 18:19:49',NULL);
/*!40000 ALTER TABLE `lessons` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES
(1,'2014_10_12_000000_create_users_table',1),
(2,'2014_10_12_100000_create_password_resets_table',1),
(3,'2019_08_19_000000_create_failed_jobs_table',1),
(4,'2019_12_14_000001_create_personal_access_tokens_table',1),
(5,'2026_10_09_000001_add_role_to_users_table',1),
(6,'2026_10_09_000002_create_business_tables',1),
(7,'2026_10_09_000003_create_content_tables',1),
(8,'2026_10_09_000004_add_username_to_users_table',1),
(9,'2026_10_09_000005_create_commerce_and_support_tables',1),
(10,'2026_10_09_000006_create_academy_tables',1),
(11,'2026_10_09_000007_create_slides_table',1),
(12,'2026_10_09_000008_add_media_paths_to_content_tables',1),
(13,'2026_10_10_000001_add_read_tracking_to_ticket_messages',1),
(14,'2026_10_11_000001_create_resume_tables',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `orders` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `reference` varchar(20) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `lead_id` bigint(20) unsigned DEFAULT NULL,
  `service_id` bigint(20) unsigned DEFAULT NULL,
  `plan_id` bigint(20) unsigned DEFAULT NULL,
  `status` varchar(30) NOT NULL DEFAULT 'requested',
  `progress_percent` tinyint(3) unsigned NOT NULL DEFAULT 0,
  `customer_note` text DEFAULT NULL,
  `staff_note` text DEFAULT NULL,
  `addon_ids` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`addon_ids`)),
  `estimate_setup` bigint(20) unsigned DEFAULT NULL,
  `estimate_recurring` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `orders_reference_unique` (`reference`),
  KEY `orders_user_id_foreign` (`user_id`),
  KEY `orders_lead_id_foreign` (`lead_id`),
  KEY `orders_service_id_foreign` (`service_id`),
  KEY `orders_plan_id_foreign` (`plan_id`),
  KEY `orders_status_created_at_index` (`status`,`created_at`),
  CONSTRAINT `orders_lead_id_foreign` FOREIGN KEY (`lead_id`) REFERENCES `leads` (`id`) ON DELETE SET NULL,
  CONSTRAINT `orders_plan_id_foreign` FOREIGN KEY (`plan_id`) REFERENCES `pricing_plans` (`id`) ON DELETE SET NULL,
  CONSTRAINT `orders_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE SET NULL,
  CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_resets`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `password_resets` WRITE;
/*!40000 ALTER TABLE `password_resets` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_resets` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `payments`
--

DROP TABLE IF EXISTS `payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `payments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `invoice_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `amount` bigint(20) unsigned NOT NULL,
  `method` varchar(30) NOT NULL,
  `gateway` varchar(30) DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'pending',
  `authority` varchar(120) DEFAULT NULL,
  `reference_id` varchar(120) DEFAULT NULL,
  `receipt_path` varchar(255) DEFAULT NULL,
  `paid_at` datetime DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `payments_authority_unique` (`authority`),
  KEY `payments_invoice_id_foreign` (`invoice_id`),
  KEY `payments_user_id_foreign` (`user_id`),
  CONSTRAINT `payments_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE CASCADE,
  CONSTRAINT `payments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payments`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `payments` WRITE;
/*!40000 ALTER TABLE `payments` DISABLE KEYS */;
/*!40000 ALTER TABLE `payments` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal_access_tokens`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `personal_access_tokens` WRITE;
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `portfolios`
--

DROP TABLE IF EXISTS `portfolios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `portfolios` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `slug` varchar(255) NOT NULL,
  `title_fa` varchar(255) NOT NULL,
  `title_en` varchar(255) NOT NULL,
  `client_name` varchar(255) DEFAULT NULL,
  `challenge_fa` text DEFAULT NULL,
  `challenge_en` text DEFAULT NULL,
  `solution_fa` text DEFAULT NULL,
  `solution_en` text DEFAULT NULL,
  `result_fa` text DEFAULT NULL,
  `result_en` text DEFAULT NULL,
  `technologies` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`technologies`)),
  `image_url` text DEFAULT NULL,
  `project_url` text DEFAULT NULL,
  `completed_at` date DEFAULT NULL,
  `is_published` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `media_path` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `portfolios_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `portfolios`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `portfolios` WRITE;
/*!40000 ALTER TABLE `portfolios` DISABLE KEYS */;
INSERT INTO `portfolios` VALUES
(1,'sample-online-store','نمونه: فروشگاه آنلاین با پرداخت امن','Sample: online store with secure checkout','Demo (نمونه نمایشی)','فروشگاه قدیمی بدون مدیریت موجودی و با سرعت پایین بارگذاری.','A legacy store with no stock management and slow page loads.','بازطراحی رابط کاربری، ساختار محصول و بهینه‌سازی تصاویر و کش.','Redesigned UI, product structure, image optimisation and caching.','این نتیجه نمایشی است؛ نتایج واقعی را پس از تأیید مشتری وارد کنید.','Illustrative outcome only; add real measured results after client approval.','[\"WordPress\",\"WooCommerce\",\"Redis\"]','/images/portfolio-1.jpg',NULL,'2026-06-01',1,'2026-10-10 18:19:49','2026-10-10 18:19:49','images/samples/portfolio-sample-store.jpg'),
(2,'sample-docker-deployment','نمونه: استقرار خودکار با Docker و CI/CD','Sample: automated Docker deployment with CI/CD','Demo (نمونه نمایشی)','استقرار دستی و زمان‌بر نسخه‌های جدید نرم‌افزار.','Manual, time-consuming release process.','Docker Compose، پایپ‌لاین CI/CD و مانیتورینگ ساده سرور.','Docker Compose, a CI/CD pipeline and simple server monitoring.','نمونه آموزشی؛ مدت زمان استقرار را پس از اندازه‌گیری واقعی ثبت کنید.','Educational sample; record real deployment times after measuring.','[\"Docker\",\"GitLab CI\",\"Nginx\",\"Linux\"]','/images/portfolio-2.jpg',NULL,'2026-07-15',1,'2026-10-10 18:19:49','2026-10-10 18:19:49','images/samples/portfolio-sample-cloud.jpg'),
(3,'sample-security-hardening','نمونه: امن‌سازی سرور و بکاپ خودکار','Sample: server hardening and automated backups','Demo (نمونه نمایشی)','دسترسی‌های باز، نبود بکاپ منظم و نبود بازیابی آزموده‌شده.','Open access rules, no regular backups and no tested restore.','سخت‌سازی SSH، فایروال، به‌روزرسانی خودکار و بکاپ روزانه با تست بازیابی.','SSH hardening, firewall rules, automatic updates and daily backups with restore tests.','نمونه نمایشی؛ نتیجه را پس از ممیزی واقعی وارد کنید.','Demo sample; enter real audit results here.','[\"Ubuntu\",\"UFW\",\"Fail2ban\",\"Restic\"]','/images/portfolio-3.jpg',NULL,'2026-08-10',1,'2026-10-10 18:19:49','2026-10-10 18:19:49','images/samples/portfolio-sample-security.jpg');
/*!40000 ALTER TABLE `portfolios` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `pricing_plans`
--

DROP TABLE IF EXISTS `pricing_plans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `pricing_plans` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `service_id` bigint(20) unsigned DEFAULT NULL,
  `slug` varchar(255) NOT NULL,
  `name_fa` varchar(255) NOT NULL,
  `name_en` varchar(255) NOT NULL,
  `description_fa` text DEFAULT NULL,
  `description_en` text DEFAULT NULL,
  `features_fa` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`features_fa`)),
  `features_en` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`features_en`)),
  `setup_fee` bigint(20) unsigned DEFAULT NULL,
  `recurring_fee` bigint(20) unsigned DEFAULT NULL,
  `recurrence_fa` varchar(80) DEFAULT NULL,
  `recurrence_en` varchar(80) DEFAULT NULL,
  `price_type` varchar(30) NOT NULL DEFAULT 'quote',
  `valid_until` date DEFAULT NULL,
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(10) unsigned NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `media_path` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pricing_plans_slug_unique` (`slug`),
  KEY `pricing_plans_service_id_foreign` (`service_id`),
  CONSTRAINT `pricing_plans_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pricing_plans`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `pricing_plans` WRITE;
/*!40000 ALTER TABLE `pricing_plans` DISABLE KEYS */;
INSERT INTO `pricing_plans` VALUES
(1,NULL,'base','پایه','Base','محدوده خدمات و هزینه پس از نیازسنجی و تأیید پیش‌فاکتور مشخص می‌شود.','Scope and cost are confirmed after discovery and an approved quotation.','[]','[]',NULL,NULL,NULL,NULL,'quote',NULL,1,1,1,'2026-10-10 18:19:49','2026-10-10 18:19:49','images/samples/plan-basic.jpg'),
(2,NULL,'professional','حرفه‌ای','Professional','محدوده خدمات و هزینه پس از نیازسنجی و تأیید پیش‌فاکتور مشخص می‌شود.','Scope and cost are confirmed after discovery and an approved quotation.','[]','[]',NULL,NULL,NULL,NULL,'quote',NULL,1,1,2,'2026-10-10 18:19:49','2026-10-10 18:19:49','images/samples/plan-professional.jpg'),
(3,NULL,'enterprise','سازمانی','Enterprise','محدوده خدمات و هزینه پس از نیازسنجی و تأیید پیش‌فاکتور مشخص می‌شود.','Scope and cost are confirmed after discovery and an approved quotation.','[]','[]',NULL,NULL,NULL,NULL,'quote',NULL,1,1,3,'2026-10-10 18:19:49','2026-10-10 18:19:49','images/samples/plan-enterprise.jpg');
/*!40000 ALTER TABLE `pricing_plans` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `resume_items`
--

DROP TABLE IF EXISTS `resume_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `resume_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `resume_id` bigint(20) unsigned NOT NULL,
  `type` varchar(20) NOT NULL,
  `title_fa` varchar(255) NOT NULL,
  `title_en` varchar(255) NOT NULL,
  `organization_fa` varchar(255) DEFAULT NULL,
  `organization_en` varchar(255) DEFAULT NULL,
  `period` varchar(60) DEFAULT NULL,
  `description_fa` text DEFAULT NULL,
  `description_en` text DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `level` tinyint(3) unsigned DEFAULT NULL,
  `sort_order` int(10) unsigned NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `resume_items_resume_id_foreign` (`resume_id`),
  KEY `resume_items_type_index` (`type`),
  CONSTRAINT `resume_items_resume_id_foreign` FOREIGN KEY (`resume_id`) REFERENCES `resumes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `resume_items`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `resume_items` WRITE;
/*!40000 ALTER TABLE `resume_items` DISABLE KEYS */;
INSERT INTO `resume_items` VALUES
(1,1,'experience','مهندس ارشد شبکه (نمونه)','Senior network engineer (sample)','شرکت نمونه A','Sample Company A','2021 – 2026','طراحی شبکه VLAN و مدیریت فایروال.','Designed VLAN networks and managed firewalls.',NULL,NULL,10,'2026-10-10 18:19:49','2026-10-10 18:19:49'),
(2,1,'experience','کارشناس شبکه (نمونه)','Network specialist (sample)','شرکت نمونه B','Sample Company B','2018 – 2021','پشتیبانی و نگهداری تجهیزات شبکه.','Supported and maintained network equipment.',NULL,NULL,20,'2026-10-10 18:19:49','2026-10-10 18:19:49'),
(3,1,'education','کارشناسی مهندسی فناوری اطلاعات (نمونه)','BSc in IT engineering (sample)','دانشگاه نمونه','Sample University','2014 – 2018',NULL,NULL,NULL,NULL,30,'2026-10-10 18:19:49','2026-10-10 18:19:49'),
(4,1,'skill','پیکربندی فایروال','Firewall configuration',NULL,NULL,NULL,NULL,NULL,NULL,90,40,'2026-10-10 18:19:49','2026-10-10 18:19:49'),
(5,1,'skill','مسیریابی و سوئیچینگ','Routing & switching',NULL,NULL,NULL,NULL,NULL,NULL,85,50,'2026-10-10 18:19:49','2026-10-10 18:19:49'),
(6,1,'certificate','گواهی نمونه شبکه','Sample networking certificate','مرکز آموزشی نمونه','Sample Training Center','2023',NULL,NULL,'https://example.com/certificate-sample',NULL,60,'2026-10-10 18:19:49','2026-10-10 18:19:49'),
(7,2,'experience','توسعه‌دهنده ارشد وب (نمونه)','Senior web developer (sample)','استودیو نمونه C','Sample Studio C','2022 – 2026','توسعه سامانه‌های مدیریت محتوا و فروشگاه.','Built content management and e-commerce systems.',NULL,NULL,10,'2026-10-10 18:19:49','2026-10-10 18:19:49'),
(8,2,'education','کارشناسی مهندسی نرم‌افزار (نمونه)','BSc in software engineering (sample)','دانشگاه نمونه','Sample University','2017 – 2021',NULL,NULL,NULL,NULL,20,'2026-10-10 18:19:49','2026-10-10 18:19:49'),
(9,2,'skill','Laravel و PHP','Laravel & PHP',NULL,NULL,NULL,NULL,NULL,NULL,92,30,'2026-10-10 18:19:49','2026-10-10 18:19:49'),
(10,2,'skill','MySQL و طراحی پایگاه داده','MySQL & database design',NULL,NULL,NULL,NULL,NULL,NULL,88,40,'2026-10-10 18:19:49','2026-10-10 18:19:49');
/*!40000 ALTER TABLE `resume_items` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `resumes`
--

DROP TABLE IF EXISTS `resumes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `resumes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `slug` varchar(255) NOT NULL,
  `name_fa` varchar(255) NOT NULL,
  `name_en` varchar(255) NOT NULL,
  `job_title_fa` varchar(255) DEFAULT NULL,
  `job_title_en` varchar(255) DEFAULT NULL,
  `bio_fa` text DEFAULT NULL,
  `bio_en` text DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `location_fa` varchar(255) DEFAULT NULL,
  `location_en` varchar(255) DEFAULT NULL,
  `photo_path` varchar(255) DEFAULT NULL,
  `is_sample` tinyint(1) NOT NULL DEFAULT 0,
  `is_published` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(10) unsigned NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `resumes_slug_unique` (`slug`),
  KEY `resumes_is_published_index` (`is_published`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `resumes`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `resumes` WRITE;
/*!40000 ALTER TABLE `resumes` DISABLE KEYS */;
INSERT INTO `resumes` VALUES
(1,'sample-network-engineer','نمونه: نیلوفر کاویانی','Sample: Niloufar Kaviani','مهندس شبکه و امنیت (نمونه)','Network & security engineer (sample)','این رزومه نمونه است. مهندس شبکه با تجربه طراحی و پیاده‌سازی زیرساخت‌های سازمانی، مدیریت فایروال و پایش امنیت.','This is a sample resume. Network engineer experienced in designing enterprise infrastructure, firewall management and security monitoring.','sample.network@example.com',NULL,'تهران','Tehran',NULL,1,1,1,'2026-10-10 18:19:49','2026-10-10 18:19:49'),
(2,'sample-laravel-developer','نمونه: آرش موسوی','Sample: Arash Mousavi','توسعه‌دهنده ارشد Laravel (نمونه)','Senior Laravel developer (sample)','این رزومه نمونه است. توسعه‌دهنده وب با تمرکز بر Laravel، MySQL و طراحی رابط کاربری فارسی و انگلیسی.','This is a sample resume. Web developer focused on Laravel, MySQL and bilingual (Persian and English) interfaces.','sample.dev@example.com',NULL,'اصفهان','Isfahan',NULL,1,1,2,'2026-10-10 18:19:49','2026-10-10 18:19:49');
/*!40000 ALTER TABLE `resumes` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `service_addons`
--

DROP TABLE IF EXISTS `service_addons`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `service_addons` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `service_id` bigint(20) unsigned DEFAULT NULL,
  `name_fa` varchar(255) NOT NULL,
  `name_en` varchar(255) NOT NULL,
  `description_fa` text DEFAULT NULL,
  `description_en` text DEFAULT NULL,
  `amount` bigint(20) unsigned DEFAULT NULL,
  `price_type` varchar(30) NOT NULL DEFAULT 'quote',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(10) unsigned NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `media_path` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `service_addons_service_id_foreign` (`service_id`),
  CONSTRAINT `service_addons_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `service_addons`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `service_addons` WRITE;
/*!40000 ALTER TABLE `service_addons` DISABLE KEYS */;
INSERT INTO `service_addons` VALUES
(1,1,'نمونه: ساخت فرم و صفحه فرود','Sample: landing page and forms',NULL,NULL,NULL,'quote',1,1,'2026-10-10 18:19:49','2026-10-10 18:19:49',NULL),
(2,1,'نمونه: اتصال درگاه پرداخت','Sample: payment gateway integration',NULL,NULL,NULL,'quote',1,2,'2026-10-10 18:19:49','2026-10-10 18:19:49',NULL),
(3,1,'نمونه: پشتیبانی ماهانه','Sample: monthly support',NULL,NULL,NULL,'quote',1,3,'2026-10-10 18:19:49','2026-10-10 18:19:49',NULL),
(4,5,'نمونه: ممیزی امنیتی','Sample: security audit',NULL,NULL,NULL,'quote',1,1,'2026-10-10 18:19:49','2026-10-10 18:19:49',NULL);
/*!40000 ALTER TABLE `service_addons` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `service_categories`
--

DROP TABLE IF EXISTS `service_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `service_categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name_fa` varchar(255) NOT NULL,
  `name_en` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description_fa` text DEFAULT NULL,
  `description_en` text DEFAULT NULL,
  `sort_order` int(10) unsigned NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `media_path` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `service_categories_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `service_categories`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `service_categories` WRITE;
/*!40000 ALTER TABLE `service_categories` DISABLE KEYS */;
INSERT INTO `service_categories` VALUES
(1,'طراحی سایت و نرم‌افزار','Web & software','web-software',NULL,NULL,1,1,'2026-10-10 18:19:49','2026-10-10 18:19:49','images/samples/category-web-software.jpg'),
(2,'DevOps و زیرساخت','DevOps & infrastructure','devops-infrastructure',NULL,NULL,2,1,'2026-10-10 18:19:49','2026-10-10 18:19:49','images/samples/category-devops-infrastructure.jpg'),
(3,'پشتیبانی IT','IT support','it-support',NULL,NULL,3,1,'2026-10-10 18:19:49','2026-10-10 18:19:49','images/samples/category-it-support.jpg'),
(4,'شبکه و امنیت','Network & security','network-security',NULL,NULL,4,1,'2026-10-10 18:19:49','2026-10-10 18:19:49','images/samples/category-network-security.jpg');
/*!40000 ALTER TABLE `service_categories` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `service_prices`
--

DROP TABLE IF EXISTS `service_prices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `service_prices` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `service_id` bigint(20) unsigned DEFAULT NULL,
  `title_fa` varchar(255) NOT NULL,
  `title_en` varchar(255) NOT NULL,
  `unit_fa` varchar(100) DEFAULT NULL,
  `unit_en` varchar(100) DEFAULT NULL,
  `amount` bigint(20) unsigned DEFAULT NULL,
  `minimum_amount` bigint(20) unsigned DEFAULT NULL,
  `maximum_amount` bigint(20) unsigned DEFAULT NULL,
  `price_type` varchar(30) NOT NULL DEFAULT 'quote',
  `tariff_year` smallint(5) unsigned DEFAULT NULL,
  `source_name` varchar(255) DEFAULT NULL,
  `source_url` text DEFAULT NULL,
  `is_verified` tinyint(1) NOT NULL DEFAULT 0,
  `show_amount` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `valid_from` date DEFAULT NULL,
  `valid_until` date DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `service_prices_service_id_foreign` (`service_id`),
  CONSTRAINT `service_prices_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `service_prices`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `service_prices` WRITE;
/*!40000 ALTER TABLE `service_prices` DISABLE KEYS */;
/*!40000 ALTER TABLE `service_prices` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `services`
--

DROP TABLE IF EXISTS `services`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `services` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `category_id` bigint(20) unsigned DEFAULT NULL,
  `slug` varchar(255) NOT NULL,
  `title_fa` varchar(255) NOT NULL,
  `title_en` varchar(255) NOT NULL,
  `summary_fa` varchar(500) DEFAULT NULL,
  `summary_en` varchar(500) DEFAULT NULL,
  `description_fa` text DEFAULT NULL,
  `description_en` text DEFAULT NULL,
  `included_fa` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`included_fa`)),
  `included_en` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`included_en`)),
  `excluded_fa` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`excluded_fa`)),
  `excluded_en` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`excluded_en`)),
  `delivery_days` int(10) unsigned DEFAULT NULL,
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `media_path` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `services_slug_unique` (`slug`),
  KEY `services_category_id_foreign` (`category_id`),
  CONSTRAINT `services_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `service_categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `services`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `services` WRITE;
/*!40000 ALTER TABLE `services` DISABLE KEYS */;
INSERT INTO `services` VALUES
(1,1,'business-website','طراحی سایت شرکتی','Business website design','طراحی و پیاده‌سازی وب‌سایت متناسب با نیاز و هویت کسب‌وکار.','A business website designed around your goals and brand.','طراحی و پیاده‌سازی وب‌سایت متناسب با نیاز و هویت کسب‌وکار.','A business website designed around your goals and brand.',NULL,NULL,NULL,NULL,NULL,1,1,'2026-10-10 18:19:49','2026-10-10 18:19:49','images/samples/service-business-website.jpg'),
(2,1,'custom-laravel','توسعه نرم‌افزار اختصاصی Laravel','Custom Laravel development','توسعه سامانه‌ها و ماژول‌های اختصاصی با Laravel.','Custom applications and modules built with Laravel.','توسعه سامانه‌ها و ماژول‌های اختصاصی با Laravel.','Custom applications and modules built with Laravel.',NULL,NULL,NULL,NULL,NULL,1,1,'2026-10-10 18:19:49','2026-10-10 18:19:49','images/samples/service-custom-laravel.jpg'),
(3,2,'deployment-devops','استقرار و DevOps','Deployment & DevOps','راه‌اندازی سرور، Docker، CI/CD، SSL و پشتیبان‌گیری.','Server setup, Docker, CI/CD, SSL, and backup workflows.','راه‌اندازی سرور، Docker، CI/CD، SSL و پشتیبان‌گیری.','Server setup, Docker, CI/CD, SSL, and backup workflows.',NULL,NULL,NULL,NULL,NULL,1,1,'2026-10-10 18:19:49','2026-10-10 18:19:49','images/samples/service-deployment-devops.jpg'),
(4,3,'it-help-desk','پشتیبانی IT و Help Desk','IT support & help desk','پشتیبانی دوره‌ای کاربران، سیستم‌ها و زیرساخت فناوری.','Ongoing support for users, systems, and IT infrastructure.','پشتیبانی دوره‌ای کاربران، سیستم‌ها و زیرساخت فناوری.','Ongoing support for users, systems, and IT infrastructure.',NULL,NULL,NULL,NULL,NULL,0,1,'2026-10-10 18:19:49','2026-10-10 18:19:49',NULL),
(5,4,'network-security','شبکه و امنیت فناوری اطلاعات','IT network & security','ارزیابی و بهبود امنیت سایت، سرور و شبکه سازمان.','Assessment and improvement of website, server, and network security.','ارزیابی و بهبود امنیت سایت، سرور و شبکه سازمان.','Assessment and improvement of website, server, and network security.',NULL,NULL,NULL,NULL,NULL,0,1,'2026-10-10 18:19:49','2026-10-10 18:19:49',NULL);
/*!40000 ALTER TABLE `services` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `slides`
--

DROP TABLE IF EXISTS `slides`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `slides` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title_fa` varchar(190) NOT NULL,
  `title_en` varchar(190) NOT NULL,
  `subtitle_fa` varchar(500) DEFAULT NULL,
  `subtitle_en` varchar(500) DEFAULT NULL,
  `button_text_fa` varchar(80) DEFAULT NULL,
  `button_text_en` varchar(80) DEFAULT NULL,
  `button_url` varchar(255) DEFAULT NULL,
  `image_path` varchar(255) NOT NULL,
  `is_sample` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(10) unsigned NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `slides`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `slides` WRITE;
/*!40000 ALTER TABLE `slides` DISABLE KEYS */;
INSERT INTO `slides` VALUES
(1,'نمونه: زیرساخت فناوری کسب‌وکار شما','Sample: Technology infrastructure for your business','از طراحی و توسعه تا استقرار، امنیت و پشتیبانی','From design and development to deployment, security and support','مشاهده خدمات','View services','/services','images/hero22-gold.jpg',1,1,1,'2026-10-10 18:19:49','2026-10-10 18:19:49'),
(2,'نمونه: برآورد هزینه در چند دقیقه','Sample: Estimate your costs in minutes','بسته و افزودنی‌های موردنیاز را انتخاب کنید؛ مبالغ رسمی و استعلام قیمت به‌روشنی مشخص می‌شوند.','Choose a plan and add-ons; official amounts and price inquiries are clearly separated.','ماشین‌حساب','Calculator','/calculator','images/portfolio-1.jpg',1,1,2,'2026-10-10 18:19:49','2026-10-10 18:19:49'),
(3,'نمونه: آکادمی و آموزش عملی','Sample: Academy and hands-on training','دوره‌های کوتاه درباره وب، سرور و امنیت. دوره‌های نمونه با برچسب مشخص شده‌اند.','Short courses on web, servers and security. Sample courses are labelled.','ورود به آکادمی','Open the academy','/academy','images/portfolio-2.jpg',1,1,3,'2026-10-10 18:19:49','2026-10-10 18:19:49');
/*!40000 ALTER TABLE `slides` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `ticket_messages`
--

DROP TABLE IF EXISTS `ticket_messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `ticket_messages` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `ticket_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `body` text NOT NULL,
  `attachment_path` varchar(255) DEFAULT NULL,
  `attachment_name` varchar(190) DEFAULT NULL,
  `is_staff` tinyint(1) NOT NULL DEFAULT 0,
  `read_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ticket_messages_user_id_foreign` (`user_id`),
  KEY `ticket_messages_inbox_idx` (`ticket_id`,`is_staff`,`read_at`),
  CONSTRAINT `ticket_messages_ticket_id_foreign` FOREIGN KEY (`ticket_id`) REFERENCES `tickets` (`id`) ON DELETE CASCADE,
  CONSTRAINT `ticket_messages_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ticket_messages`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `ticket_messages` WRITE;
/*!40000 ALTER TABLE `ticket_messages` DISABLE KEYS */;
/*!40000 ALTER TABLE `ticket_messages` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `tickets`
--

DROP TABLE IF EXISTS `tickets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `tickets` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `reference` varchar(20) NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `subject` varchar(190) NOT NULL,
  `category` varchar(50) NOT NULL DEFAULT 'general',
  `priority` varchar(20) NOT NULL DEFAULT 'normal',
  `status` varchar(20) NOT NULL DEFAULT 'open',
  `assigned_to` bigint(20) unsigned DEFAULT NULL,
  `first_response_at` datetime DEFAULT NULL,
  `closed_at` datetime DEFAULT NULL,
  `last_reply_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tickets_reference_unique` (`reference`),
  KEY `tickets_user_id_foreign` (`user_id`),
  KEY `tickets_assigned_to_foreign` (`assigned_to`),
  KEY `tickets_status_created_at_index` (`status`,`created_at`),
  CONSTRAINT `tickets_assigned_to_foreign` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `tickets_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tickets`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `tickets` WRITE;
/*!40000 ALTER TABLE `tickets` DISABLE KEYS */;
/*!40000 ALTER TABLE `tickets` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `username` varchar(60) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `company` varchar(190) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(30) NOT NULL DEFAULT 'customer',
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `users_username_unique` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES
(1,'Ideban Admin','admin','admin@ideban.local',NULL,NULL,'2026-10-10 18:19:49','$2y$10$wzYCbkZYq5TUW2PUtoTaz./LBuFJc3tXx9YKhquNH33PjPIY9aSlO','admin',NULL,'2026-10-10 18:19:49','2026-10-10 18:19:49');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `videos`
--

DROP TABLE IF EXISTS `videos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `videos` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `slug` varchar(255) NOT NULL,
  `title_fa` varchar(255) NOT NULL,
  `title_en` varchar(255) NOT NULL,
  `description_fa` text DEFAULT NULL,
  `description_en` text DEFAULT NULL,
  `video_url` text NOT NULL,
  `thumbnail_url` text DEFAULT NULL,
  `duration_seconds` int(10) unsigned DEFAULT NULL,
  `category` varchar(80) DEFAULT NULL,
  `tags` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`tags`)),
  `service_id` bigint(20) unsigned DEFAULT NULL,
  `is_published` tinyint(1) NOT NULL DEFAULT 0,
  `published_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `thumbnail_path` varchar(500) DEFAULT NULL,
  `video_path` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `videos_slug_unique` (`slug`),
  KEY `videos_service_id_foreign` (`service_id`),
  KEY `videos_is_published_published_at_index` (`is_published`,`published_at`),
  CONSTRAINT `videos_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `videos`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `videos` WRITE;
/*!40000 ALTER TABLE `videos` DISABLE KEYS */;
/*!40000 ALTER TABLE `videos` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Dumping routines for database 'ideban_almas'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*M!100616 SET NOTE_VERBOSITY=@OLD_NOTE_VERBOSITY */;

-- Dump completed on 2026-10-10 18:21:15
