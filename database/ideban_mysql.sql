-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 09, 2026 at 11:46 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `laravel-ideban`
--

-- --------------------------------------------------------

--
-- Table structure for table `articles`
--

CREATE TABLE `articles` (
  `id` bigint(20) UNSIGNED NOT NULL,
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
  `service_id` bigint(20) UNSIGNED DEFAULT NULL,
  `meta_title` varchar(190) DEFAULT NULL,
  `meta_description` varchar(320) DEFAULT NULL,
  `is_published` tinyint(1) NOT NULL DEFAULT 0,
  `published_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `cover_path` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `articles`
--

INSERT INTO `articles` (`id`, `slug`, `title_fa`, `title_en`, `excerpt_fa`, `excerpt_en`, `body_fa`, `body_en`, `category`, `tags`, `cover_url`, `author_name`, `service_id`, `meta_title`, `meta_description`, `is_published`, `published_at`, `created_at`, `updated_at`, `cover_path`) VALUES
(1, 'website-backup-checklist', 'چک‌لیست بکاپ‌گیری از وب‌سایت', 'A practical website backup checklist', 'بکاپ فقط زمانی ارزش دارد که بتوان آن را بازیابی کرد. این فهرست کوتاه را پیش از هر به‌روزرسانی مرور کنید.', 'A backup is only useful if you can restore it. Review this short list before every update.', '## چرا بکاپ کافی نیست؟\r\n\r\nبکاپی که هرگز آزمایش بازیابی نشده، فقط یک فایل است. هدف این است که در بدترین حالت بتوانید سایت را در زمان قابل قبول برگردانید.\r\n\r\n## فهرست پیشنهادی\r\n\r\n- بکاپ از پایگاه داده و فایل‌های آپلودی به‌صورت جداگانه تهیه شود.\r\n- نسخه‌ها در مکانی غیر از سرور اصلی نگهداری شوند.\r\n- حداقل یک بار در ماه بازیابی آزمایشی انجام شود.\r\n- دسترسی به فایل‌های بکاپ محدود به افراد مجاز باشد.\r\n\r\n## نکته امنیتی\r\n\r\nفایل `.env` و کلیدهای دسترسی را هرگز داخل بکاپ عمومی یا مخازن کد قرار ندهید.', '## Why a backup alone is not enough\r\n\r\nA backup that has never been restored is only a file. The goal is to bring the site back within an acceptable time when something goes wrong.\r\n\r\n## Suggested checklist\r\n\r\n- Back up the database and uploaded files separately.\r\n- Store copies away from the production server.\r\n- Run a test restore at least once a month.\r\n- Limit access to backup files to authorised people.\r\n\r\n## Security note\r\n\r\nNever place `.env` files or access keys in public backups or code repositories.', 'امنیت و نگهداری / Security & maintenance', '[\"backup\",\"security\"]', NULL, 'Ideban Almas', 4, NULL, NULL, 1, '2026-10-02 21:02:00', '2026-10-09 21:02:23', '2026-10-09 18:11:18', 'media/articles/e99abe85-cc63-40bb-8aed-4ffd4b8dc5be.jpg'),
(2, 'choosing-a-website-platform', 'انتخاب بستر مناسب برای وب‌سایت کسب‌وکار', 'Choosing the right platform for a business website', 'پیش از انتخاب قالب یا ابزار، نیاز، بودجه، توان نگهداری و مسیر رشد را مشخص کنید.', 'Before choosing a theme or tool, define your needs, budget, maintenance capacity and growth path.', '## سه پرسش کلیدی\r\n\r\n۱. سایت باید چه کاری انجام دهد: معرفی، فروش یا پشتیبانی؟\r\n۲. چه کسی بعداً محتوا و امکانات را به‌روز می‌کند؟\r\n۳. چه حجمی از داده و ترافیک را پیش‌بینی می‌کنید؟\r\n\r\n## مقایسه گزینه‌ها\r\n\r\nمعمولاً وب‌سایت معرفی ساده، فروشگاه آنلاین و نرم‌افزار سفارشی نیازهای متفاوتی دارند. انتخاب ابزار باید بر پایه همین نیازها باشد، نه صرفاً محبوبیت آن.', '## Three key questions\r\n\r\n1. What should the site do: inform, sell or support?\r\n2. Who will update content and features later?\r\n3. What volume of data and traffic do you expect?\r\n\r\n## Comparing options\r\n\r\nA simple brochure site, an online shop and custom software have different requirements. Pick the tool based on those requirements rather than popularity alone.', 'راهنما / Guides', '[\"website\",\"planning\"]', NULL, 'Ideban Almas', NULL, NULL, NULL, 1, '2026-10-06 21:02:00', '2026-10-09 21:02:23', '2026-10-09 18:11:54', 'media/articles/2eac9c28-c6f6-487d-a779-7f6c4a042ce7.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `id` bigint(20) UNSIGNED NOT NULL,
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
  `duration_minutes` int(10) UNSIGNED DEFAULT NULL,
  `prerequisite_fa` varchar(255) DEFAULT NULL,
  `prerequisite_en` varchar(255) DEFAULT NULL,
  `price` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `is_free` tinyint(1) NOT NULL DEFAULT 0,
  `is_published` tinyint(1) NOT NULL DEFAULT 0,
  `cover_url` text DEFAULT NULL,
  `sort_order` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `cover_path` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`id`, `slug`, `title_fa`, `title_en`, `instructor_fa`, `instructor_en`, `summary_fa`, `summary_en`, `description_fa`, `description_en`, `category`, `level`, `duration_minutes`, `prerequisite_fa`, `prerequisite_en`, `price`, `is_free`, `is_published`, `cover_url`, `sort_order`, `created_at`, `updated_at`, `cover_path`) VALUES
(1, 'sample-website-launch-basics', 'نمونه: مبانی راه‌اندازی وب‌سایت کسب‌وکار', 'Sample: Business website launch basics', 'مربی نمونه', 'Sample instructor', 'دوره نمونه برای آشنایی با دامنه، میزبانی و انتشار اولین وب‌سایت.', 'Sample course on domains, hosting and publishing a first website.', NULL, NULL, 'شروع کسب‌وکار اینترنتی', 'beginner', 60, NULL, NULL, 0, 1, 1, NULL, 1, '2026-10-09 21:02:23', '2026-10-09 21:02:23', NULL),
(2, 'sample-linux-server-security', 'نمونه: امنیت پایه سرور لینوکس', 'Sample: Linux server security basics', 'مربی نمونه', 'Sample instructor', 'دوره نمونه درباره سخت‌سازی پایه سرور و به‌روزرسانی امن.', 'Sample course on basic server hardening and safe updates.', NULL, NULL, 'سرور و Linux', 'intermediate', 60, NULL, NULL, 1500000, 0, 1, NULL, 2, '2026-10-09 21:02:23', '2026-10-09 21:02:23', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `discount_codes`
--

CREATE TABLE `discount_codes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(40) NOT NULL,
  `type` varchar(10) NOT NULL DEFAULT 'percent',
  `value` int(10) UNSIGNED NOT NULL,
  `course_id` bigint(20) UNSIGNED DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL,
  `max_uses` int(10) UNSIGNED DEFAULT NULL,
  `used_count` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `discount_codes`
--

INSERT INTO `discount_codes` (`id`, `code`, `type`, `value`, `course_id`, `expires_at`, `max_uses`, `used_count`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'SAMPLE10', 'percent', 10, NULL, NULL, NULL, 0, 1, '2026-10-09 21:02:23', '2026-10-09 21:02:23');

-- --------------------------------------------------------

--
-- Table structure for table `enrollments`
--

CREATE TABLE `enrollments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `course_id` bigint(20) UNSIGNED NOT NULL,
  `source` varchar(20) NOT NULL DEFAULT 'free',
  `payment_id` bigint(20) UNSIGNED DEFAULT NULL,
  `last_lesson_id` bigint(20) UNSIGNED DEFAULT NULL,
  `granted_at` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `number` varchar(30) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `order_id` bigint(20) UNSIGNED DEFAULT NULL,
  `course_id` bigint(20) UNSIGNED DEFAULT NULL,
  `type` varchar(20) NOT NULL DEFAULT 'invoice',
  `status` varchar(20) NOT NULL DEFAULT 'issued',
  `items` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`items`)),
  `subtotal` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `discount` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `extra_costs` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `total` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `valid_until` date DEFAULT NULL,
  `paid_at` datetime DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `leads`
--

CREATE TABLE `leads` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `company` varchar(255) DEFAULT NULL,
  `phone` varchar(30) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `service_id` bigint(20) UNSIGNED DEFAULT NULL,
  `plan_id` bigint(20) UNSIGNED DEFAULT NULL,
  `source` varchar(100) NOT NULL DEFAULT 'website',
  `message` text DEFAULT NULL,
  `stage` varchar(30) NOT NULL DEFAULT 'new',
  `assigned_to` bigint(20) UNSIGNED DEFAULT NULL,
  `follow_up_at` datetime DEFAULT NULL,
  `expected_value` bigint(20) UNSIGNED DEFAULT NULL,
  `sales_note` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `leads`
--

INSERT INTO `leads` (`id`, `name`, `company`, `phone`, `email`, `service_id`, `plan_id`, `source`, `message`, `stage`, `assigned_to`, `follow_up_at`, `expected_value`, `sales_note`, `created_at`, `updated_at`) VALUES
(1, 'نمونه مشتری ۱ (Demo)', 'Demo Co.', '09120000001', NULL, 1, NULL, 'sample', 'داده نمونه برای نمایش پنل فروش.', 'new', NULL, NULL, NULL, NULL, '2026-10-09 21:02:23', '2026-10-09 21:02:23'),
(2, 'نمونه مشتری ۲ (Demo)', 'Demo Retail', '09120000002', NULL, 1, NULL, 'sample', 'داده نمونه برای نمایش پیگیری.', 'proposal', NULL, NULL, 45000000, NULL, '2026-10-09 21:02:23', '2026-10-09 21:02:23');

-- --------------------------------------------------------

--
-- Table structure for table `lessons`
--

CREATE TABLE `lessons` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `course_id` bigint(20) UNSIGNED NOT NULL,
  `title_fa` varchar(255) NOT NULL,
  `title_en` varchar(255) NOT NULL,
  `sort_order` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `source` varchar(20) NOT NULL DEFAULT 'none',
  `file_path` varchar(255) DEFAULT NULL,
  `external_url` text DEFAULT NULL,
  `duration_seconds` int(10) UNSIGNED DEFAULT NULL,
  `is_free_preview` tinyint(1) NOT NULL DEFAULT 0,
  `is_published` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `thumbnail_path` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `lessons`
--

INSERT INTO `lessons` (`id`, `course_id`, `title_fa`, `title_en`, `sort_order`, `source`, `file_path`, `external_url`, `duration_seconds`, `is_free_preview`, `is_published`, `created_at`, `updated_at`, `thumbnail_path`) VALUES
(1, 1, 'معرفی دوره (نمونه)', 'Course introduction (sample)', 1, 'none', NULL, NULL, NULL, 1, 1, '2026-10-09 21:02:23', '2026-10-09 21:02:23', NULL),
(2, 1, 'انتخاب دامنه و میزبانی (نمونه)', 'Choosing a domain and hosting (sample)', 2, 'none', NULL, NULL, NULL, 0, 1, '2026-10-09 21:02:23', '2026-10-09 21:02:23', NULL),
(3, 2, 'مقدمه امنیت سرور (نمونه)', 'Server security introduction (sample)', 1, 'none', NULL, NULL, NULL, 1, 1, '2026-10-09 21:02:23', '2026-10-09 21:02:23', NULL),
(4, 2, 'کلیدهای SSH و فایروال (نمونه)', 'SSH keys and firewall (sample)', 2, 'none', NULL, NULL, NULL, 0, 1, '2026-10-09 21:02:23', '2026-10-09 21:02:23', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `lesson_progress`
--

CREATE TABLE `lesson_progress` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `lesson_id` bigint(20) UNSIGNED NOT NULL,
  `completed_at` datetime DEFAULT NULL,
  `last_position_seconds` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2026_10_09_000001_add_role_to_users_table', 1),
(6, '2026_10_09_000002_create_business_tables', 1),
(7, '2026_10_09_000003_create_content_tables', 1),
(8, '2026_10_09_000004_add_username_to_users_table', 1),
(9, '2026_10_09_000005_create_commerce_and_support_tables', 1),
(10, '2026_10_09_000006_create_academy_tables', 1),
(11, '2026_10_09_000007_create_slides_table', 1),
(12, '2026_10_09_000008_add_media_paths_to_content_tables', 1),
(13, '2026_10_10_000001_add_read_tracking_to_ticket_messages', 2);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `reference` varchar(20) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `lead_id` bigint(20) UNSIGNED DEFAULT NULL,
  `service_id` bigint(20) UNSIGNED DEFAULT NULL,
  `plan_id` bigint(20) UNSIGNED DEFAULT NULL,
  `status` varchar(30) NOT NULL DEFAULT 'requested',
  `progress_percent` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `customer_note` text DEFAULT NULL,
  `staff_note` text DEFAULT NULL,
  `addon_ids` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`addon_ids`)),
  `estimate_setup` bigint(20) UNSIGNED DEFAULT NULL,
  `estimate_recurring` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `invoice_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `amount` bigint(20) UNSIGNED NOT NULL,
  `method` varchar(30) NOT NULL,
  `gateway` varchar(30) DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'pending',
  `authority` varchar(120) DEFAULT NULL,
  `reference_id` varchar(120) DEFAULT NULL,
  `receipt_path` varchar(255) DEFAULT NULL,
  `paid_at` datetime DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `portfolios`
--

CREATE TABLE `portfolios` (
  `id` bigint(20) UNSIGNED NOT NULL,
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
  `media_path` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `portfolios`
--

INSERT INTO `portfolios` (`id`, `slug`, `title_fa`, `title_en`, `client_name`, `challenge_fa`, `challenge_en`, `solution_fa`, `solution_en`, `result_fa`, `result_en`, `technologies`, `image_url`, `project_url`, `completed_at`, `is_published`, `created_at`, `updated_at`, `media_path`) VALUES
(1, 'sample-online-store', 'نمونه: فروشگاه آنلاین با پرداخت امن', 'Sample: online store with secure checkout', 'Demo (نمونه نمایشی)', 'فروشگاه قدیمی بدون مدیریت موجودی و با سرعت پایین بارگذاری.', 'A legacy store with no stock management and slow page loads.', 'بازطراحی رابط کاربری، ساختار محصول و بهینه‌سازی تصاویر و کش.', 'Redesigned UI, product structure, image optimisation and caching.', 'این نتیجه نمایشی است؛ نتایج واقعی را پس از تأیید مشتری وارد کنید.', 'Illustrative outcome only; add real measured results after client approval.', '[\"WordPress\",\"WooCommerce\",\"Redis\"]', '/images/portfolio-1.jpg', NULL, '2026-06-01', 1, '2026-10-09 21:02:23', '2026-10-09 21:02:23', 'images/samples/portfolio-sample-store.jpg'),
(2, 'sample-docker-deployment', 'نمونه: استقرار خودکار با Docker و CI/CD', 'Sample: automated Docker deployment with CI/CD', 'Demo (نمونه نمایشی)', 'استقرار دستی و زمان‌بر نسخه‌های جدید نرم‌افزار.', 'Manual, time-consuming release process.', 'Docker Compose، پایپ‌لاین CI/CD و مانیتورینگ ساده سرور.', 'Docker Compose, a CI/CD pipeline and simple server monitoring.', 'نمونه آموزشی؛ مدت زمان استقرار را پس از اندازه‌گیری واقعی ثبت کنید.', 'Educational sample; record real deployment times after measuring.', '[\"Docker\",\"GitLab CI\",\"Nginx\",\"Linux\"]', '/images/portfolio-2.jpg', NULL, '2026-07-15', 1, '2026-10-09 21:02:23', '2026-10-09 21:02:23', 'images/samples/portfolio-sample-cloud.jpg'),
(3, 'sample-security-hardening', 'نمونه: امن‌سازی سرور و بکاپ خودکار', 'Sample: server hardening and automated backups', 'Demo (نمونه نمایشی)', 'دسترسی‌های باز، نبود بکاپ منظم و نبود بازیابی آزموده‌شده.', 'Open access rules, no regular backups and no tested restore.', 'سخت‌سازی SSH، فایروال، به‌روزرسانی خودکار و بکاپ روزانه با تست بازیابی.', 'SSH hardening, firewall rules, automatic updates and daily backups with restore tests.', 'نمونه نمایشی؛ نتیجه را پس از ممیزی واقعی وارد کنید.', 'Demo sample; enter real audit results here.', '[\"Ubuntu\",\"UFW\",\"Fail2ban\",\"Restic\"]', '/images/portfolio-3.jpg', NULL, '2026-08-10', 1, '2026-10-09 21:02:23', '2026-10-09 21:02:23', 'images/samples/portfolio-sample-security.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `pricing_plans`
--

CREATE TABLE `pricing_plans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `service_id` bigint(20) UNSIGNED DEFAULT NULL,
  `slug` varchar(255) NOT NULL,
  `name_fa` varchar(255) NOT NULL,
  `name_en` varchar(255) NOT NULL,
  `description_fa` text DEFAULT NULL,
  `description_en` text DEFAULT NULL,
  `features_fa` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`features_fa`)),
  `features_en` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`features_en`)),
  `setup_fee` bigint(20) UNSIGNED DEFAULT NULL,
  `recurring_fee` bigint(20) UNSIGNED DEFAULT NULL,
  `recurrence_fa` varchar(80) DEFAULT NULL,
  `recurrence_en` varchar(80) DEFAULT NULL,
  `price_type` varchar(30) NOT NULL DEFAULT 'quote',
  `valid_until` date DEFAULT NULL,
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `media_path` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pricing_plans`
--

INSERT INTO `pricing_plans` (`id`, `service_id`, `slug`, `name_fa`, `name_en`, `description_fa`, `description_en`, `features_fa`, `features_en`, `setup_fee`, `recurring_fee`, `recurrence_fa`, `recurrence_en`, `price_type`, `valid_until`, `is_featured`, `is_active`, `sort_order`, `created_at`, `updated_at`, `media_path`) VALUES
(1, NULL, 'base', 'پایه', 'Base', 'محدوده خدمات و هزینه پس از نیازسنجی و تأیید پیش‌فاکتور مشخص می‌شود.', 'Scope and cost are confirmed after discovery and an approved quotation.', '[]', '[]', NULL, NULL, NULL, NULL, 'quote', NULL, 1, 1, 1, '2026-10-09 21:02:23', '2026-10-09 21:02:23', 'images/samples/plan-basic.jpg'),
(2, NULL, 'professional', 'حرفه‌ای', 'Professional', 'محدوده خدمات و هزینه پس از نیازسنجی و تأیید پیش‌فاکتور مشخص می‌شود.', 'Scope and cost are confirmed after discovery and an approved quotation.', '[]', '[]', NULL, NULL, NULL, NULL, 'quote', NULL, 1, 1, 2, '2026-10-09 21:02:23', '2026-10-09 21:02:23', 'images/samples/plan-professional.jpg'),
(3, NULL, 'enterprise', 'سازمانی', 'Enterprise', 'محدوده خدمات و هزینه پس از نیازسنجی و تأیید پیش‌فاکتور مشخص می‌شود.', 'Scope and cost are confirmed after discovery and an approved quotation.', '[]', '[]', NULL, NULL, NULL, NULL, 'quote', NULL, 1, 1, 3, '2026-10-09 21:02:23', '2026-10-09 21:02:23', 'images/samples/plan-enterprise.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED DEFAULT NULL,
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
  `delivery_days` int(10) UNSIGNED DEFAULT NULL,
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `media_path` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `category_id`, `slug`, `title_fa`, `title_en`, `summary_fa`, `summary_en`, `description_fa`, `description_en`, `included_fa`, `included_en`, `excluded_fa`, `excluded_en`, `delivery_days`, `is_featured`, `is_active`, `created_at`, `updated_at`, `media_path`) VALUES
(1, 1, 'business-website', 'طراحی سایت شرکتی', 'Business website design', 'طراحی و پیاده‌سازی وب‌سایت متناسب با نیاز و هویت کسب‌وکار.', 'A business website designed around your goals and brand.', 'طراحی و پیاده‌سازی وب‌سایت متناسب با نیاز و هویت کسب‌وکار.', 'A business website designed around your goals and brand.', NULL, NULL, NULL, NULL, NULL, 1, 1, '2026-10-09 21:02:23', '2026-10-09 21:02:23', 'images/samples/service-business-website.jpg'),
(2, 1, 'custom-laravel', 'توسعه نرم‌افزار اختصاصی Laravel', 'Custom Laravel development', 'توسعه سامانه‌ها و ماژول‌های اختصاصی با Laravel.', 'Custom applications and modules built with Laravel.', 'توسعه سامانه‌ها و ماژول‌های اختصاصی با Laravel.', 'Custom applications and modules built with Laravel.', NULL, NULL, NULL, NULL, NULL, 1, 1, '2026-10-09 21:02:23', '2026-10-09 21:02:23', 'images/samples/service-custom-laravel.jpg'),
(3, 2, 'deployment-devops', 'استقرار و DevOps', 'Deployment & DevOps', 'راه‌اندازی سرور، Docker، CI/CD، SSL و پشتیبان‌گیری.', 'Server setup, Docker, CI/CD, SSL, and backup workflows.', 'راه‌اندازی سرور، Docker، CI/CD، SSL و پشتیبان‌گیری.', 'Server setup, Docker, CI/CD, SSL, and backup workflows.', NULL, NULL, NULL, NULL, NULL, 1, 1, '2026-10-09 21:02:23', '2026-10-09 21:02:23', 'images/samples/service-deployment-devops.jpg'),
(4, 3, 'it-help-desk', 'پشتیبانی IT و Help Desk', 'IT support & help desk', 'پشتیبانی دوره‌ای کاربران، سیستم‌ها و زیرساخت فناوری.', 'Ongoing support for users, systems, and IT infrastructure.', 'پشتیبانی دوره‌ای کاربران، سیستم‌ها و زیرساخت فناوری.', 'Ongoing support for users, systems, and IT infrastructure.', NULL, NULL, NULL, NULL, NULL, 0, 1, '2026-10-09 21:02:23', '2026-10-09 21:02:23', NULL),
(5, 4, 'network-security', 'شبکه و امنیت فناوری اطلاعات', 'IT network & security', 'ارزیابی و بهبود امنیت سایت، سرور و شبکه سازمان.', 'Assessment and improvement of website, server, and network security.', 'ارزیابی و بهبود امنیت سایت، سرور و شبکه سازمان.', 'Assessment and improvement of website, server, and network security.', NULL, NULL, NULL, NULL, NULL, 0, 1, '2026-10-09 21:02:23', '2026-10-09 21:02:23', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `service_addons`
--

CREATE TABLE `service_addons` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `service_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name_fa` varchar(255) NOT NULL,
  `name_en` varchar(255) NOT NULL,
  `description_fa` text DEFAULT NULL,
  `description_en` text DEFAULT NULL,
  `amount` bigint(20) UNSIGNED DEFAULT NULL,
  `price_type` varchar(30) NOT NULL DEFAULT 'quote',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `media_path` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `service_addons`
--

INSERT INTO `service_addons` (`id`, `service_id`, `name_fa`, `name_en`, `description_fa`, `description_en`, `amount`, `price_type`, `is_active`, `sort_order`, `created_at`, `updated_at`, `media_path`) VALUES
(1, 1, 'نمونه: ساخت فرم و صفحه فرود', 'Sample: landing page and forms', NULL, NULL, NULL, 'quote', 1, 1, '2026-10-09 21:02:23', '2026-10-09 21:02:23', NULL),
(2, 1, 'نمونه: اتصال درگاه پرداخت', 'Sample: payment gateway integration', NULL, NULL, NULL, 'quote', 1, 2, '2026-10-09 21:02:23', '2026-10-09 21:02:23', NULL),
(3, 1, 'نمونه: پشتیبانی ماهانه', 'Sample: monthly support', NULL, NULL, NULL, 'quote', 1, 3, '2026-10-09 21:02:23', '2026-10-09 21:02:23', NULL),
(4, 5, 'نمونه: ممیزی امنیتی', 'Sample: security audit', NULL, NULL, NULL, 'quote', 1, 1, '2026-10-09 21:02:23', '2026-10-09 21:02:23', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `service_categories`
--

CREATE TABLE `service_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name_fa` varchar(255) NOT NULL,
  `name_en` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description_fa` text DEFAULT NULL,
  `description_en` text DEFAULT NULL,
  `sort_order` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `media_path` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `service_categories`
--

INSERT INTO `service_categories` (`id`, `name_fa`, `name_en`, `slug`, `description_fa`, `description_en`, `sort_order`, `is_active`, `created_at`, `updated_at`, `media_path`) VALUES
(1, 'طراحی سایت و نرم‌افزار', 'Web & software', 'web-software', NULL, NULL, 1, 1, '2026-10-09 21:02:23', '2026-10-09 21:02:23', 'images/samples/category-web-software.jpg'),
(2, 'DevOps و زیرساخت', 'DevOps & infrastructure', 'devops-infrastructure', NULL, NULL, 2, 1, '2026-10-09 21:02:23', '2026-10-09 21:02:23', 'images/samples/category-devops-infrastructure.jpg'),
(3, 'پشتیبانی IT', 'IT support', 'it-support', NULL, NULL, 3, 1, '2026-10-09 21:02:23', '2026-10-09 21:02:23', 'images/samples/category-it-support.jpg'),
(4, 'شبکه و امنیت', 'Network & security', 'network-security', NULL, NULL, 4, 1, '2026-10-09 21:02:23', '2026-10-09 21:02:23', 'images/samples/category-network-security.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `service_prices`
--

CREATE TABLE `service_prices` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `service_id` bigint(20) UNSIGNED DEFAULT NULL,
  `title_fa` varchar(255) NOT NULL,
  `title_en` varchar(255) NOT NULL,
  `unit_fa` varchar(100) DEFAULT NULL,
  `unit_en` varchar(100) DEFAULT NULL,
  `amount` bigint(20) UNSIGNED DEFAULT NULL,
  `minimum_amount` bigint(20) UNSIGNED DEFAULT NULL,
  `maximum_amount` bigint(20) UNSIGNED DEFAULT NULL,
  `price_type` varchar(30) NOT NULL DEFAULT 'quote',
  `tariff_year` smallint(5) UNSIGNED DEFAULT NULL,
  `source_name` varchar(255) DEFAULT NULL,
  `source_url` text DEFAULT NULL,
  `is_verified` tinyint(1) NOT NULL DEFAULT 0,
  `show_amount` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `valid_from` date DEFAULT NULL,
  `valid_until` date DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `slides`
--

CREATE TABLE `slides` (
  `id` bigint(20) UNSIGNED NOT NULL,
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
  `sort_order` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `slides`
--

INSERT INTO `slides` (`id`, `title_fa`, `title_en`, `subtitle_fa`, `subtitle_en`, `button_text_fa`, `button_text_en`, `button_url`, `image_path`, `is_sample`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 'نمونه: زیرساخت فناوری کسب‌وکار شما', 'Sample: Technology infrastructure for your business', 'از طراحی و توسعه تا استقرار، امنیت و پشتیبانی', 'From design and development to deployment, security and support', 'مشاهده خدمات', 'View services', 'https://localhost/ideban-laravel/public/services', 'uploads/slides/slide-2b57e7df60962369.jpg', 0, 1, 1, '2026-10-09 21:02:23', '2026-10-09 17:48:32'),
(2, 'نمونه: برآورد هزینه در چند دقیقه', 'Sample: Estimate your costs in minutes', 'بسته و افزودنی‌های موردنیاز را انتخاب کنید؛ مبالغ رسمی و استعلام قیمت به‌روشنی مشخص می‌شوند.', 'Choose a plan and add-ons; official amounts and price inquiries are clearly separated.', 'ماشین‌حساب', 'Calculator', '/calculator', 'images/portfolio-1.jpg', 1, 1, 2, '2026-10-09 21:02:23', '2026-10-09 21:02:23'),
(3, 'نمونه: آکادمی و آموزش عملی', 'Sample: Academy and hands-on training', 'دوره‌های کوتاه درباره وب، سرور و امنیت. دوره‌های نمونه با برچسب مشخص شده‌اند.', 'Short courses on web, servers and security. Sample courses are labelled.', 'ورود به آکادمی', 'Open the academy', '/academy', 'images/portfolio-2.jpg', 1, 1, 3, '2026-10-09 21:02:23', '2026-10-09 21:02:23');

-- --------------------------------------------------------

--
-- Table structure for table `tickets`
--

CREATE TABLE `tickets` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `reference` varchar(20) NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `subject` varchar(190) NOT NULL,
  `category` varchar(50) NOT NULL DEFAULT 'general',
  `priority` varchar(20) NOT NULL DEFAULT 'normal',
  `status` varchar(20) NOT NULL DEFAULT 'open',
  `assigned_to` bigint(20) UNSIGNED DEFAULT NULL,
  `first_response_at` datetime DEFAULT NULL,
  `closed_at` datetime DEFAULT NULL,
  `last_reply_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ticket_messages`
--

CREATE TABLE `ticket_messages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `ticket_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `body` text NOT NULL,
  `attachment_path` varchar(255) DEFAULT NULL,
  `attachment_name` varchar(190) DEFAULT NULL,
  `is_staff` tinyint(1) NOT NULL DEFAULT 0,
  `read_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
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
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `email`, `phone`, `company`, `email_verified_at`, `password`, `role`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Ideban Admin', 'admin', 'admin@ideban.local', NULL, NULL, '2026-10-09 21:02:23', '$2y$10$/v9.8QMPOJUrZG5loWVvd.yGLcGwQIPybTle5MxhqVPcPffmYVPZa', 'admin', NULL, '2026-10-09 21:02:23', '2026-10-09 21:02:23');

-- --------------------------------------------------------

--
-- Table structure for table `videos`
--

CREATE TABLE `videos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `slug` varchar(255) NOT NULL,
  `title_fa` varchar(255) NOT NULL,
  `title_en` varchar(255) NOT NULL,
  `description_fa` text DEFAULT NULL,
  `description_en` text DEFAULT NULL,
  `video_url` text NOT NULL,
  `thumbnail_url` text DEFAULT NULL,
  `duration_seconds` int(10) UNSIGNED DEFAULT NULL,
  `category` varchar(80) DEFAULT NULL,
  `tags` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`tags`)),
  `service_id` bigint(20) UNSIGNED DEFAULT NULL,
  `is_published` tinyint(1) NOT NULL DEFAULT 0,
  `published_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `thumbnail_path` varchar(500) DEFAULT NULL,
  `video_path` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `articles`
--
ALTER TABLE `articles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `articles_slug_unique` (`slug`),
  ADD KEY `articles_service_id_foreign` (`service_id`),
  ADD KEY `articles_is_published_published_at_index` (`is_published`,`published_at`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `courses_slug_unique` (`slug`);

--
-- Indexes for table `discount_codes`
--
ALTER TABLE `discount_codes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `discount_codes_code_unique` (`code`),
  ADD KEY `discount_codes_course_id_foreign` (`course_id`);

--
-- Indexes for table `enrollments`
--
ALTER TABLE `enrollments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `enrollments_user_id_course_id_unique` (`user_id`,`course_id`),
  ADD KEY `enrollments_course_id_foreign` (`course_id`),
  ADD KEY `enrollments_payment_id_foreign` (`payment_id`),
  ADD KEY `enrollments_last_lesson_id_foreign` (`last_lesson_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `invoices_number_unique` (`number`),
  ADD KEY `invoices_user_id_foreign` (`user_id`),
  ADD KEY `invoices_order_id_foreign` (`order_id`),
  ADD KEY `invoices_course_id_index` (`course_id`);

--
-- Indexes for table `leads`
--
ALTER TABLE `leads`
  ADD PRIMARY KEY (`id`),
  ADD KEY `leads_service_id_foreign` (`service_id`),
  ADD KEY `leads_plan_id_foreign` (`plan_id`),
  ADD KEY `leads_assigned_to_foreign` (`assigned_to`),
  ADD KEY `leads_stage_follow_up_at_index` (`stage`,`follow_up_at`);

--
-- Indexes for table `lessons`
--
ALTER TABLE `lessons`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lessons_course_id_foreign` (`course_id`);

--
-- Indexes for table `lesson_progress`
--
ALTER TABLE `lesson_progress`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `lesson_progress_user_id_lesson_id_unique` (`user_id`,`lesson_id`),
  ADD KEY `lesson_progress_lesson_id_foreign` (`lesson_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `orders_reference_unique` (`reference`),
  ADD KEY `orders_user_id_foreign` (`user_id`),
  ADD KEY `orders_lead_id_foreign` (`lead_id`),
  ADD KEY `orders_service_id_foreign` (`service_id`),
  ADD KEY `orders_plan_id_foreign` (`plan_id`),
  ADD KEY `orders_status_created_at_index` (`status`,`created_at`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `payments_authority_unique` (`authority`),
  ADD KEY `payments_invoice_id_foreign` (`invoice_id`),
  ADD KEY `payments_user_id_foreign` (`user_id`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `portfolios`
--
ALTER TABLE `portfolios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `portfolios_slug_unique` (`slug`);

--
-- Indexes for table `pricing_plans`
--
ALTER TABLE `pricing_plans`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pricing_plans_slug_unique` (`slug`),
  ADD KEY `pricing_plans_service_id_foreign` (`service_id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `services_slug_unique` (`slug`),
  ADD KEY `services_category_id_foreign` (`category_id`);

--
-- Indexes for table `service_addons`
--
ALTER TABLE `service_addons`
  ADD PRIMARY KEY (`id`),
  ADD KEY `service_addons_service_id_foreign` (`service_id`);

--
-- Indexes for table `service_categories`
--
ALTER TABLE `service_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `service_categories_slug_unique` (`slug`);

--
-- Indexes for table `service_prices`
--
ALTER TABLE `service_prices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `service_prices_service_id_foreign` (`service_id`);

--
-- Indexes for table `slides`
--
ALTER TABLE `slides`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tickets`
--
ALTER TABLE `tickets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tickets_reference_unique` (`reference`),
  ADD KEY `tickets_user_id_foreign` (`user_id`),
  ADD KEY `tickets_assigned_to_foreign` (`assigned_to`),
  ADD KEY `tickets_status_created_at_index` (`status`,`created_at`);

--
-- Indexes for table `ticket_messages`
--
ALTER TABLE `ticket_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ticket_messages_ticket_id_foreign` (`ticket_id`),
  ADD KEY `ticket_messages_user_id_foreign` (`user_id`),
  ADD KEY `ticket_messages_inbox_idx` (`ticket_id`,`is_staff`,`read_at`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_username_unique` (`username`);

--
-- Indexes for table `videos`
--
ALTER TABLE `videos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `videos_slug_unique` (`slug`),
  ADD KEY `videos_service_id_foreign` (`service_id`),
  ADD KEY `videos_is_published_published_at_index` (`is_published`,`published_at`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `articles`
--
ALTER TABLE `articles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `discount_codes`
--
ALTER TABLE `discount_codes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `enrollments`
--
ALTER TABLE `enrollments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `leads`
--
ALTER TABLE `leads`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `lessons`
--
ALTER TABLE `lessons`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `lesson_progress`
--
ALTER TABLE `lesson_progress`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `portfolios`
--
ALTER TABLE `portfolios`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `pricing_plans`
--
ALTER TABLE `pricing_plans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `service_addons`
--
ALTER TABLE `service_addons`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `service_categories`
--
ALTER TABLE `service_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `service_prices`
--
ALTER TABLE `service_prices`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `slides`
--
ALTER TABLE `slides`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tickets`
--
ALTER TABLE `tickets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ticket_messages`
--
ALTER TABLE `ticket_messages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `videos`
--
ALTER TABLE `videos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `articles`
--
ALTER TABLE `articles`
  ADD CONSTRAINT `articles_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `discount_codes`
--
ALTER TABLE `discount_codes`
  ADD CONSTRAINT `discount_codes_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `enrollments`
--
ALTER TABLE `enrollments`
  ADD CONSTRAINT `enrollments_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `enrollments_last_lesson_id_foreign` FOREIGN KEY (`last_lesson_id`) REFERENCES `lessons` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `enrollments_payment_id_foreign` FOREIGN KEY (`payment_id`) REFERENCES `payments` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `enrollments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `invoices`
--
ALTER TABLE `invoices`
  ADD CONSTRAINT `invoices_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `invoices_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `invoices_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `leads`
--
ALTER TABLE `leads`
  ADD CONSTRAINT `leads_assigned_to_foreign` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `leads_plan_id_foreign` FOREIGN KEY (`plan_id`) REFERENCES `pricing_plans` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `leads_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `lessons`
--
ALTER TABLE `lessons`
  ADD CONSTRAINT `lessons_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `lesson_progress`
--
ALTER TABLE `lesson_progress`
  ADD CONSTRAINT `lesson_progress_lesson_id_foreign` FOREIGN KEY (`lesson_id`) REFERENCES `lessons` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `lesson_progress_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_lead_id_foreign` FOREIGN KEY (`lead_id`) REFERENCES `leads` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `orders_plan_id_foreign` FOREIGN KEY (`plan_id`) REFERENCES `pricing_plans` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `orders_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `payments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `pricing_plans`
--
ALTER TABLE `pricing_plans`
  ADD CONSTRAINT `pricing_plans_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `services`
--
ALTER TABLE `services`
  ADD CONSTRAINT `services_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `service_categories` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `service_addons`
--
ALTER TABLE `service_addons`
  ADD CONSTRAINT `service_addons_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `service_prices`
--
ALTER TABLE `service_prices`
  ADD CONSTRAINT `service_prices_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `tickets`
--
ALTER TABLE `tickets`
  ADD CONSTRAINT `tickets_assigned_to_foreign` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `tickets_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `ticket_messages`
--
ALTER TABLE `ticket_messages`
  ADD CONSTRAINT `ticket_messages_ticket_id_foreign` FOREIGN KEY (`ticket_id`) REFERENCES `tickets` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ticket_messages_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `videos`
--
ALTER TABLE `videos`
  ADD CONSTRAINT `videos_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
