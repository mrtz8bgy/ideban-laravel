-- Ideban Almas phase-two schema and seed data for MySQL 5.7+ / MariaDB 10.2+.
-- Generated from the Laravel migrations (2014 .. 2026_10_09_000003) and the
-- IdebanCatalogSeeder + ContentSeeder data. Import ONLY into an EMPTY database:
--   mysql -u USER -p DATABASE < database/ideban_mysql.sql
-- The migrations ledger is included, so do NOT run `php artisan migrate` afterwards.
-- No staff accounts are included: create the first administrator with
--   php artisan ideban:make-admin
-- Sample articles are general guidance only; there are no client projects,
-- official tariffs or customer records in this dump.

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
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
  `tags` json DEFAULT NULL,
  `cover_url` text DEFAULT NULL,
  `author_name` varchar(120) DEFAULT NULL,
  `service_id` bigint(20) unsigned DEFAULT NULL,
  `meta_title` varchar(190) DEFAULT NULL,
  `meta_description` varchar(320) DEFAULT NULL,
  `is_published` tinyint(1) NOT NULL DEFAULT 0,
  `published_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `articles_slug_unique` (`slug`),
  KEY `articles_service_id_foreign` (`service_id`),
  KEY `articles_is_published_published_at_index` (`is_published`,`published_at`),
  CONSTRAINT `articles_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `articles` WRITE;
/*!40000 ALTER TABLE `articles` DISABLE KEYS */;
INSERT INTO `articles` VALUES
(1,'website-backup-checklist','چک‌لیست بکاپ‌گیری از وب‌سایت','A practical website backup checklist','بکاپ فقط زمانی ارزش دارد که بتوان آن را بازیابی کرد. این فهرست کوتاه را پیش از هر به‌روزرسانی مرور کنید.','A backup is only useful if you can restore it. Review this short list before every update.','## چرا بکاپ کافی نیست؟\n\nبکاپی که هرگز آزمایش بازیابی نشده، فقط یک فایل است. هدف این است که در بدترین حالت بتوانید سایت را در زمان قابل قبول برگردانید.\n\n## فهرست پیشنهادی\n\n- بکاپ از پایگاه داده و فایل‌های آپلودی به‌صورت جداگانه تهیه شود.\n- نسخه‌ها در مکانی غیر از سرور اصلی نگهداری شوند.\n- حداقل یک بار در ماه بازیابی آزمایشی انجام شود.\n- دسترسی به فایل‌های بکاپ محدود به افراد مجاز باشد.\n\n## نکته امنیتی\n\nفایل `.env` و کلیدهای دسترسی را هرگز داخل بکاپ عمومی یا مخازن کد قرار ندهید.','## Why a backup alone is not enough\n\nA backup that has never been restored is only a file. The goal is to bring the site back within an acceptable time when something goes wrong.\n\n## Suggested checklist\n\n- Back up the database and uploaded files separately.\n- Store copies away from the production server.\n- Run a test restore at least once a month.\n- Limit access to backup files to authorised people.\n\n## Security note\n\nNever place `.env` files or access keys in public backups or code repositories.','امنیت و نگهداری / Security & maintenance','[\"backup\",\"security\"]',NULL,'Ideban Almas',NULL,NULL,NULL,1,'2026-10-02 17:21:57','2026-10-09 17:21:57','2026-10-09 17:21:57'),
(2,'choosing-a-website-platform','انتخاب بستر مناسب برای وب‌سایت کسب‌وکار','Choosing the right platform for a business website','پیش از انتخاب قالب یا ابزار، نیاز، بودجه، توان نگهداری و مسیر رشد را مشخص کنید.','Before choosing a theme or tool, define your needs, budget, maintenance capacity and growth path.','## سه پرسش کلیدی\n\n۱. سایت باید چه کاری انجام دهد: معرفی، فروش یا پشتیبانی؟\n۲. چه کسی بعداً محتوا و امکانات را به‌روز می‌کند؟\n۳. چه حجمی از داده و ترافیک را پیش‌بینی می‌کنید؟\n\n## مقایسه گزینه‌ها\n\nمعمولاً وب‌سایت معرفی ساده، فروشگاه آنلاین و نرم‌افزار سفارشی نیازهای متفاوتی دارند. انتخاب ابزار باید بر پایه همین نیازها باشد، نه صرفاً محبوبیت آن.','## Three key questions\n\n1. What should the site do: inform, sell or support?\n2. Who will update content and features later?\n3. What volume of data and traffic do you expect?\n\n## Comparing options\n\nA simple brochure site, an online shop and custom software have different requirements. Pick the tool based on those requirements rather than popularity alone.','راهنما / Guides','[\"website\",\"planning\"]',NULL,'Ideban Almas',NULL,NULL,NULL,1,'2026-10-06 17:21:57','2026-10-09 17:21:57','2026-10-09 17:21:57');
/*!40000 ALTER TABLE `articles` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;
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

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `leads` WRITE;
/*!40000 ALTER TABLE `leads` DISABLE KEYS */;
/*!40000 ALTER TABLE `leads` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;
DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

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
(7,'2026_10_09_000003_create_content_tables',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;
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

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `password_resets` WRITE;
/*!40000 ALTER TABLE `password_resets` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_resets` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;
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

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `personal_access_tokens` WRITE;
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;
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
  `technologies` json DEFAULT NULL,
  `image_url` text DEFAULT NULL,
  `project_url` text DEFAULT NULL,
  `completed_at` date DEFAULT NULL,
  `is_published` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `portfolios_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `portfolios` WRITE;
/*!40000 ALTER TABLE `portfolios` DISABLE KEYS */;
/*!40000 ALTER TABLE `portfolios` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;
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
  `features_fa` json DEFAULT NULL,
  `features_en` json DEFAULT NULL,
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
  PRIMARY KEY (`id`),
  UNIQUE KEY `pricing_plans_slug_unique` (`slug`),
  KEY `pricing_plans_service_id_foreign` (`service_id`),
  CONSTRAINT `pricing_plans_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `pricing_plans` WRITE;
/*!40000 ALTER TABLE `pricing_plans` DISABLE KEYS */;
INSERT INTO `pricing_plans` VALUES
(1,NULL,'base','پایه','Base','محدوده خدمات و هزینه پس از نیازسنجی و تأیید پیش‌فاکتور مشخص می‌شود.','Scope and cost are confirmed after discovery and an approved quotation.','[]','[]',NULL,NULL,NULL,NULL,'quote',NULL,1,1,1,'2026-10-09 17:21:57','2026-10-09 17:21:57'),
(2,NULL,'professional','حرفه‌ای','Professional','محدوده خدمات و هزینه پس از نیازسنجی و تأیید پیش‌فاکتور مشخص می‌شود.','Scope and cost are confirmed after discovery and an approved quotation.','[]','[]',NULL,NULL,NULL,NULL,'quote',NULL,1,1,2,'2026-10-09 17:21:57','2026-10-09 17:21:57'),
(3,NULL,'enterprise','سازمانی','Enterprise','محدوده خدمات و هزینه پس از نیازسنجی و تأیید پیش‌فاکتور مشخص می‌شود.','Scope and cost are confirmed after discovery and an approved quotation.','[]','[]',NULL,NULL,NULL,NULL,'quote',NULL,1,1,3,'2026-10-09 17:21:57','2026-10-09 17:21:57');
/*!40000 ALTER TABLE `pricing_plans` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;
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
  PRIMARY KEY (`id`),
  UNIQUE KEY `service_categories_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `service_categories` WRITE;
/*!40000 ALTER TABLE `service_categories` DISABLE KEYS */;
INSERT INTO `service_categories` VALUES
(1,'طراحی سایت و نرم‌افزار','Web & software','web-software',NULL,NULL,1,1,'2026-10-09 17:21:57','2026-10-09 17:21:57'),
(2,'DevOps و زیرساخت','DevOps & infrastructure','devops-infrastructure',NULL,NULL,2,1,'2026-10-09 17:21:57','2026-10-09 17:21:57'),
(3,'پشتیبانی IT','IT support','it-support',NULL,NULL,3,1,'2026-10-09 17:21:57','2026-10-09 17:21:57'),
(4,'شبکه و امنیت','Network & security','network-security',NULL,NULL,4,1,'2026-10-09 17:21:57','2026-10-09 17:21:57');
/*!40000 ALTER TABLE `service_categories` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;
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

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `service_prices` WRITE;
/*!40000 ALTER TABLE `service_prices` DISABLE KEYS */;
/*!40000 ALTER TABLE `service_prices` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;
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
  `included_fa` json DEFAULT NULL,
  `included_en` json DEFAULT NULL,
  `excluded_fa` json DEFAULT NULL,
  `excluded_en` json DEFAULT NULL,
  `delivery_days` int(10) unsigned DEFAULT NULL,
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `services_slug_unique` (`slug`),
  KEY `services_category_id_foreign` (`category_id`),
  CONSTRAINT `services_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `service_categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `services` WRITE;
/*!40000 ALTER TABLE `services` DISABLE KEYS */;
INSERT INTO `services` VALUES
(1,1,'business-website','طراحی سایت شرکتی','Business website design','طراحی و پیاده‌سازی وب‌سایت متناسب با نیاز و هویت کسب‌وکار.','A business website designed around your goals and brand.','طراحی و پیاده‌سازی وب‌سایت متناسب با نیاز و هویت کسب‌وکار.','A business website designed around your goals and brand.',NULL,NULL,NULL,NULL,NULL,1,1,'2026-10-09 17:21:57','2026-10-09 17:21:57'),
(2,1,'custom-laravel','توسعه نرم‌افزار اختصاصی Laravel','Custom Laravel development','توسعه سامانه‌ها و ماژول‌های اختصاصی با Laravel.','Custom applications and modules built with Laravel.','توسعه سامانه‌ها و ماژول‌های اختصاصی با Laravel.','Custom applications and modules built with Laravel.',NULL,NULL,NULL,NULL,NULL,1,1,'2026-10-09 17:21:57','2026-10-09 17:21:57'),
(3,2,'deployment-devops','استقرار و DevOps','Deployment & DevOps','راه‌اندازی سرور، Docker، CI/CD، SSL و پشتیبان‌گیری.','Server setup, Docker, CI/CD, SSL, and backup workflows.','راه‌اندازی سرور، Docker، CI/CD، SSL و پشتیبان‌گیری.','Server setup, Docker, CI/CD, SSL, and backup workflows.',NULL,NULL,NULL,NULL,NULL,1,1,'2026-10-09 17:21:57','2026-10-09 17:21:57'),
(4,3,'it-help-desk','پشتیبانی IT و Help Desk','IT support & help desk','پشتیبانی دوره‌ای کاربران، سیستم‌ها و زیرساخت فناوری.','Ongoing support for users, systems, and IT infrastructure.','پشتیبانی دوره‌ای کاربران، سیستم‌ها و زیرساخت فناوری.','Ongoing support for users, systems, and IT infrastructure.',NULL,NULL,NULL,NULL,NULL,0,1,'2026-10-09 17:21:57','2026-10-09 17:21:57'),
(5,4,'network-security','شبکه و امنیت فناوری اطلاعات','IT network & security','ارزیابی و بهبود امنیت سایت، سرور و شبکه سازمان.','Assessment and improvement of website, server, and network security.','ارزیابی و بهبود امنیت سایت، سرور و شبکه سازمان.','Assessment and improvement of website, server, and network security.',NULL,NULL,NULL,NULL,NULL,0,1,'2026-10-09 17:21:57','2026-10-09 17:21:57');
/*!40000 ALTER TABLE `services` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;
DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(30) NOT NULL DEFAULT 'customer',
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;
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
  `tags` json DEFAULT NULL,
  `service_id` bigint(20) unsigned DEFAULT NULL,
  `is_published` tinyint(1) NOT NULL DEFAULT 0,
  `published_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `videos_slug_unique` (`slug`),
  KEY `videos_service_id_foreign` (`service_id`),
  KEY `videos_is_published_published_at_index` (`is_published`,`published_at`),
  CONSTRAINT `videos_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `videos` WRITE;
/*!40000 ALTER TABLE `videos` DISABLE KEYS */;
/*!40000 ALTER TABLE `videos` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
