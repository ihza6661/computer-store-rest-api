mysqldump: Deprecated program name. It will be removed in a future release, use '/usr/bin/mariadb-dump' instead
mysqldump: Error: 'Access denied; you need (at least one of) the PROCESS privilege(s) for this operation' when trying to dump tablespaces
/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19-12.1.2-MariaDB, for Linux (x86_64)
--
-- Host: l7cup2om0gngra77.cbetxkdyhwsb.us-east-1.rds.amazonaws.com    Database: atkzf7mkwsmwcup7
-- ------------------------------------------------------
-- Server version	8.0.42

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
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
set autocommit=0;
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
set autocommit=0;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categories_name_unique` (`name`),
  UNIQUE KEY `categories_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `categories` VALUES
(1,'Laptops','laptops','High-performance laptops for gaming, business, and everyday use. From ultraportable notebooks to powerful gaming machines.',NULL,'2025-12-22 03:07:16','2025-12-22 03:07:16'),
(2,'Desktop Computers','desktop-computers','Complete desktop systems and components for professional workstations, gaming rigs, and home offices.',NULL,'2025-12-22 03:07:16','2025-12-22 03:07:16'),
(3,'Monitors & Displays','monitors-displays','Professional monitors, gaming displays, and ultrawide screens. 4K, curved, and high refresh rate options available.',NULL,'2025-12-22 03:07:16','2025-12-22 03:07:16'),
(4,'Computer Components','computer-components','CPU processors, graphics cards, RAM, motherboards, storage drives, and PC cases for custom builds.',NULL,'2025-12-22 03:07:16','2025-12-22 03:07:16'),
(5,'Keyboards & Mice','keyboards-mice','Mechanical keyboards, wireless mice, gaming peripherals, and ergonomic input devices from top brands.',NULL,'2025-12-22 03:07:16','2025-12-22 03:07:16'),
(6,'Audio & Headsets','audio-headsets','Gaming headsets, studio headphones, microphones, and speakers for immersive audio experiences.',NULL,'2025-12-22 03:07:16','2025-12-22 03:07:16'),
(7,'Networking Equipment','networking-equipment','Routers, switches, WiFi mesh systems, and network accessories for home and office connectivity.',NULL,'2025-12-22 03:07:16','2025-12-22 03:07:16'),
(8,'Storage Solutions','storage-solutions','External hard drives, SSDs, NAS systems, and cloud storage solutions for backup and expansion.',NULL,'2025-12-22 03:07:16','2025-12-22 03:07:16'),
(9,'Software & Licenses','software-licenses','Operating systems, productivity suites, creative software, antivirus, and professional development tools.',NULL,'2025-12-22 03:07:16','2025-12-22 03:07:16'),
(10,'Gaming Accessories','gaming-accessories','Controllers, racing wheels, VR headsets, gaming chairs, and streaming equipment for gamers.',NULL,'2025-12-22 03:07:16','2025-12-22 03:07:16');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `contacts`
--

DROP TABLE IF EXISTS `contacts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `contacts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `category` enum('sales_inquiry','tech_support','general') NOT NULL DEFAULT 'general',
  `message` text NOT NULL,
  `status` enum('new','read','replied') NOT NULL DEFAULT 'new',
  `admin_reply` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contacts`
--

LOCK TABLES `contacts` WRITE;
/*!40000 ALTER TABLE `contacts` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `contacts` VALUES
(1,'Budi Santoso','budi.santoso@techcorp.id','+62-812-3456-7890','sales_inquiry','Selamat siang, saya dari PT TechCorp Indonesia. Kami sedang mencari supplier komputer untuk kantor baru kami di Jakarta. Kami membutuhkan 25 unit laptop Dell XPS 15 dan 10 unit monitor LG UltraFine. Apakah ada diskon untuk pembelian dalam jumlah besar? Mohon info harga dan waktu pengiriman. Terima kasih.','new',NULL,'2025-12-22 03:07:16','2025-12-22 03:07:16'),
(2,'Sarah Chen','sarah.chen@creativestudio.co.id','+62-811-9876-5432','sales_inquiry','Hi! We\'re a video production company looking to upgrade our editing workstations. Interested in the MacBook Pro 16\" M3 Max and the Samsung Odyssey OLED G9 monitors. Do you offer business accounts with NET 30 payment terms? We\'d be ordering 8 MacBooks and 5 monitors. Also, is installation service available?','read',NULL,'2025-12-22 03:07:16','2025-12-22 03:07:16'),
(3,'Ahmad Rahman','ahmad.rahman@univ-jakarta.ac.id','+62-813-2468-1357','sales_inquiry','Assalamualaikum, saya koordinator lab komputer Universitas Jakarta. Kami berencana membeli 50 unit Acer Aspire 5 untuk lab mahasiswa. Apakah ada harga khusus untuk institusi pendidikan? Kami juga memerlukan extended warranty 3 tahun. Mohon kirimkan quotation lengkap beserta term of payment. Jazakallah.','new',NULL,'2025-12-22 03:07:16','2025-12-22 03:07:16'),
(4,'Jennifer Lopez','jlopez@digitalmedia.co',NULL,'sales_inquiry','Hello, I run a digital marketing agency and need to equip our new office. Looking for: 15x Lenovo ThinkPad X1 Carbon, 3x Synology NAS DS923+, 20x Dell P2723DE monitors, and various peripherals (keyboards, mice, headsets). Can you prepare a complete package quote? Budget is flexible for quality products. Need delivery by end of next month.','read','Hi Jennifer, thank you for your interest! We\'ve prepared a comprehensive quote for your office setup. Our sales team will contact you within 24 hours with detailed pricing, volume discounts (approximately 12-15% off), and delivery schedule. We can definitely meet your end-of-month deadline. For a purchase of this size, we also offer free installation and 1-year premium support. Best regards, R-Tech Sales Team','2025-12-22 03:07:16','2025-12-22 03:07:16'),
(5,'Michael Wijaya','michael.w@gamingcafe.id','+62-815-7777-8888','sales_inquiry','Halo, saya mau buka gaming cafe di Bandung dengan 30 PC. Tertarik dengan Custom Gaming PC - RTX 4070 Ti Build. Apakah bisa custom spec? Saya mau upgrade ke RTX 4080 dan 64GB RAM. Berapa total cost untuk 30 unit? Apakah ada garansi dan after sales service? Butuh ready dalam 2 bulan. Tolong info detail ya. Thanks!','new',NULL,'2025-12-22 03:07:16','2025-12-22 03:07:16'),
(6,'David Kurniawan','david.k@email.com','+62-813-5555-1234','tech_support','Saya beli ASUS ROG Strix G16 minggu lalu (invoice #RT-2024-1234). Laptop nya panas banget saat main game, temperature CPU sampai 95°C. Fan juga bunyi keras. Apakah ini normal? Saya khawatir ada masalah dengan cooling system. Mohon bantuan troubleshooting atau perlu dibawa ke service center? Terima kasih.','new',NULL,'2025-12-22 03:07:16','2025-12-22 03:07:16'),
(7,'Emily Rodriguez','emily.rodriguez@freelance.com','+62-815-7777-9999','tech_support','My Dell P2723DE monitor has several dead pixels near the center of the screen. I purchased it 2 months ago. Is this covered under warranty? How do I process a warranty claim? Do I need to ship it back or can someone pick it up? I need this monitor for work urgently, so hoping for a quick resolution or replacement.','new',NULL,'2025-12-22 03:07:16','2025-12-22 03:07:16'),
(8,'Rini Kusuma','rini.kusuma@company.co.id','+62-817-2222-3333','tech_support','MacBook Pro M3 Max saya tiba-tiba slow sejak update macOS kemarin. Applications sering crash dan battery life menurun drastis. Sudah coba restart berkali-kali tapi masih sama. Apakah ini known issue? Ada solusi tanpa harus factory reset? Data saya penting semua. Mohon advise segera karena mengganggu pekerjaan. Thanks.','read','Hi Rini, thank you for reaching out. The issues you\'re experiencing are typically related to macOS indexing processes after an update. Please try: 1) Reset SMC and NVRAM, 2) Check Activity Monitor for high CPU processes, 3) Disable and re-enable Spotlight indexing. If problems persist after 48 hours, please bring your MacBook to our service center for diagnostics (free under warranty). We can backup your data before any repairs. Contact: 021-xxx-xxxx.','2025-12-22 03:07:16','2025-12-22 03:07:16'),
(9,'Alex Thompson','alex.t@startup.io','+62-818-3333-4444','tech_support','I\'m having connectivity issues with my ASUS RT-AX88U Pro router. WiFi keeps dropping every few hours and I have to restart it. Firmware is up to date (latest version). I have about 45 devices connected. Is this a hardware issue or configuration problem? Any troubleshooting steps I should try? Router is only 3 months old.','new',NULL,'2025-12-22 03:07:16','2025-12-22 03:07:16'),
(10,'Siti Nurhaliza','siti.n@designer.id','+62-819-6666-7777','tech_support','Samsung Odyssey OLED G9 monitor saya mengalami burn-in di bagian taskbar Windows. Baru pakai 4 bulan. Ini masuk garansi kan? Katanya OLED ada 3 tahun OLED care warranty. Bagaimana prosedur klaim nya? Apakah perlu foto bukti? Saya sangat kecewa karena harga monitor ini mahal sekali. Mohon solusi cepat.','read',NULL,'2025-12-22 03:07:16','2025-12-22 03:07:16'),
(11,'Kevin Tan','kevin.tan@gamer.net','+62-821-9999-0000','tech_support','Razer DeathAdder V3 Pro mouse not charging. LED indicator doesn\'t light up when connected to USB-C cable. Tried different cables and ports but same issue. Battery was working fine yesterday, suddenly dead today. Is this a battery defect? Still under warranty (bought 5 months ago). Need replacement ASAP as I use this for competitive gaming.','new',NULL,'2025-12-22 03:07:16','2025-12-22 03:07:16'),
(12,'Lisa Anderson','lisa.anderson@techblog.com',NULL,'general','Hi! I\'m a tech blogger writing an article about \"Best Laptops for Content Creators in 2024\". Would love to feature some of your products (MacBook Pro M3, Dell XPS 15, MSI Raider). Can I use product images from your website? Will include links and proper attribution. Also, any chance of getting review units for hands-on testing? My blog gets 50K monthly visitors.','read','Hi Lisa, thank you for your interest in featuring our products! You\'re welcome to use our product images with proper attribution and links to our store. Regarding review units, please send us your media kit and previous work samples to marketing@rtech.com. We\'ll review your request and get back to you within 3-5 business days. Looking forward to potential collaboration!','2025-12-22 03:07:16','2025-12-22 03:07:16'),
(13,'Robert Martinez','robert.martinez@itconsultant.biz','+62-812-6666-9999','general','Good day! I\'m an IT consultant serving 20+ corporate clients in Jakarta and Surabaya. Very interested in your product range. Do you have a reseller or partner program? Looking for competitive pricing, priority stock allocation, and technical support for my clients. Annual revenue potential: $500K+. Please share partnership terms and conditions.','new',NULL,'2025-12-22 03:07:16','2025-12-22 03:07:16'),
(14,'Dewi Lestari','dewi.lestari@foundation.or.id','+62-814-3333-4444','general','Yayasan kami bergerak di bidang pendidikan untuk anak kurang mampu di Indonesia Timur. Kami ingin melengkapi 5 computer labs (total 150 komputer). Apakah R-Tech punya CSR program atau educational discount? Kami juga terbuka untuk partnership jangka panjang. Budget terbatas tapi komitmen jangka panjang tinggi. Mohon info lebih lanjut. Terima kasih banyak.','read','Dear Ibu Dewi, terima kasih atas kepercayaan kepada R-Tech. Kami sangat menghargai misi mulia yayasan Ibu. R-Tech memiliki program CSR untuk institusi pendidikan dengan diskon up to 25% dan payment terms yang fleksibel. Tim kami akan menghubungi Ibu untuk diskusi lebih detail mengenai kebutuhan dan solusi yang kami tawarkan. Mari kita bersama membangun pendidikan Indonesia. Salam, R-Tech CSR Team','2025-12-22 03:07:16','2025-12-22 03:07:16'),
(15,'Marcus Johnson','marcus.j@youtuber.com','+62-816-1111-2222','general','Hey! I\'m a tech YouTuber with 250K subscribers focusing on gaming gear reviews. Want to collaborate on review videos for your gaming products - laptops, monitors, peripherals, etc. My videos average 100K views. Can provide detailed reviews, unboxing, performance testing. Interested in long-term partnership. Check out my channel: TechMarcusID. Let\'s talk!','new',NULL,'2025-12-22 03:07:16','2025-12-22 03:07:16'),
(16,'Putri Wulandari','putri.w@webagency.co.id','+62-822-4444-5555','general','Halo R-Tech! Kami digital agency yang sedang develop website untuk client kami. Bolehkah kami menggunakan beberapa product photos dari website R-Tech sebagai mockup/demo content? Tentu dengan watermark dan credit to R-Tech. Website tersebut untuk portfolio kami saja, non-commercial. Mohon izin dan approval. Thank you!','read','Hi Putri, terima kasih sudah menghubungi kami. Untuk penggunaan product photos sebagai mockup/demo content non-commercial, kami perbolehkan dengan syarat: 1) Include watermark/credit to R-Tech, 2) Include link to website kami, 3) Kirim preview ke marketing@rtech.com untuk approval. Senang bisa membantu project Anda. Good luck!','2025-12-22 03:07:16','2025-12-22 03:07:16'),
(17,'William Chen','william.chen@enterprise.com','+62-823-7777-8888','general','We\'re a multinational company expanding to Indonesia. Need IT equipment procurement for 200+ employees across 3 offices (Jakarta, Surabaya, Bali). Looking for comprehensive solution: laptops, desktops, monitors, networking equipment, peripherals. Do you handle enterprise-level deployment, asset management, and on-site support? Need RFP response by next week.','new',NULL,'2025-12-22 03:07:16','2025-12-22 03:07:16'),
(18,'Andi Firmansyah','andi.f@esports.id','+62-824-9999-0000','general','Kami E-Sports organization dengan 5 professional teams. Butuh sponsor untuk gaming equipment: 30x gaming laptops (ASUS ROG/MSI), 30x gaming monitors (240Hz+), 30x gaming peripherals (keyboard, mouse, headset). Bisa discuss sponsorship package? We have 500K+ social media followers dan participate in national/international tournaments. Contact untuk meeting?','new',NULL,'2025-12-22 03:07:16','2025-12-22 03:07:16'),
(19,'Sophie Wang','sophie.wang@photographer.id',NULL,'general','Hello! I\'m a commercial photographer looking to shoot product photography for tech brands. Saw your product range and would love to collaborate. Can provide high-quality lifestyle shots, studio photography, and creative content for your website/social media. Portfolio: sophiewang.com. Let me know if you\'re interested in upgrading your product visuals. Competitive rates available!','read','Hi Sophie, thank you for reaching out! We\'re actually planning a product photography refresh for Q1 2024. Your portfolio looks impressive. Please send your rate card and available packages to marketing@rtech.com. Our marketing team will review and schedule a meeting to discuss potential collaboration. Looking forward to working together!','2025-12-22 03:07:16','2025-12-22 03:07:16'),
(20,'Hendra Gunawan','hendra.g@distributor.co.id','+62-825-1111-2222','general','Selamat siang, saya distributor elektronik di Kalimantan dengan 15 retail stores. Tertarik menjadi authorized dealer R-Tech untuk wilayah Kalimantan. Punya pengalaman 10 tahun di industri IT retail. Mohon info mengenai dealer program, margin, MOQ, dan support yang diberikan. Bisa schedule meeting untuk discuss partnership? Terima kasih.','new',NULL,'2025-12-22 03:07:16','2025-12-22 03:07:16');
/*!40000 ALTER TABLE `contacts` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
set autocommit=0;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
set autocommit=0;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
set autocommit=0;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `migrations` VALUES
(1,'0001_01_01_000000_create_users_table',1),
(2,'0001_01_01_000001_create_cache_table',1),
(3,'0001_01_01_000002_create_jobs_table',1),
(4,'2025_12_20_184510_add_role_to_users_table',1),
(5,'2025_12_20_184510_create_categories_table',1),
(6,'2025_12_20_184510_create_contacts_table',1),
(7,'2025_12_20_184510_create_products_table',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
set autocommit=0;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `products` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `category_id` bigint unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text,
  `price` decimal(10,2) NOT NULL,
  `sku` varchar(255) NOT NULL,
  `image_url` varchar(255) NOT NULL,
  `image_thumbnail_url` varchar(255) DEFAULT NULL,
  `stock` int NOT NULL DEFAULT '0',
  `specifications` json DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `products_sku_unique` (`sku`),
  KEY `products_category_id_foreign` (`category_id`),
  CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `products` VALUES
(1,1,'Asus TUF Gaming A15 FA507NV','Powerful gaming laptop with Ryzen 7 and RTX 4060. Features 144Hz FHD display for smooth gaming. Includes official warranty until December 2025. Bonus: Bag, Wireless Mouse, and Original Office License.',15100000.00,'ASUS-TUF-A15-FA507NV','https://res.cloudinary.com/drcy0kzdm/image/upload/v1766311990/r-tech-products/zvuoi8p9tulelucbjpi8.jpg','https://res.cloudinary.com/drcy0kzdm/image/upload/v1766311990/r-tech-products/zvuoi8p9tulelucbjpi8.jpg',1,'\"{\\\"processor\\\":\\\"AMD Ryzen 7-7735HS\\\",\\\"gpu\\\":\\\"NVIDIA GeForce RTX 4060 8GB\\\",\\\"ram\\\":\\\"16GB\\\",\\\"storage\\\":\\\"512GB SSD\\\",\\\"display\\\":\\\"15.6\\\\\\\" FHD (1920x1080) 144Hz\\\",\\\"keyboard\\\":\\\"Backlit\\\",\\\"warranty\\\":\\\"Official warranty until December 2025\\\",\\\"condition\\\":\\\"Used\\\",\\\"extras\\\":\\\"Bag, Wireless Mouse, Original Office License\\\",\\\"original_price\\\":\\\"19970000\\\"}\"',NULL,'2025-12-22 03:07:16','2025-12-22 03:07:16'),
(2,1,'Asus ROG Strix G513QM','High-performance gaming laptop with AMD Ryzen 9 and RTX 3060. 144Hz FHD display for excellent gaming experience. Quality controlled with 3-month warranty. Bonus: Bag, Wireless Mouse, and Original Office License.',14300000.00,'ASUS-ROG-G513QM','https://res.cloudinary.com/drcy0kzdm/image/upload/v1766311994/r-tech-products/scb7kobsdwfr0r4es389.jpg','https://res.cloudinary.com/drcy0kzdm/image/upload/v1766311994/r-tech-products/scb7kobsdwfr0r4es389.jpg',0,'\"{\\\"processor\\\":\\\"AMD Ryzen 9-5900HX\\\",\\\"gpu\\\":\\\"NVIDIA GeForce RTX 3060 6GB\\\",\\\"ram\\\":\\\"16GB\\\",\\\"storage\\\":\\\"1TB SSD\\\",\\\"display\\\":\\\"15.6\\\\\\\" FHD (1920x1080) 144Hz\\\",\\\"keyboard\\\":\\\"Backlit\\\",\\\"warranty\\\":\\\"3 months from store\\\",\\\"condition\\\":\\\"Used\\\",\\\"extras\\\":\\\"Bag, Wireless Mouse, Original Office License\\\"}\"',NULL,'2025-12-22 03:07:16','2025-12-22 03:07:16'),
(3,1,'Acer Aspire A314-23M','Reliable laptop with AMD Ryzen 5 processor for everyday tasks. Quality controlled with 3-month store warranty. Bonus: Bag and Wireless Mouse.',5900000.00,'ACER-ASP-A314-23M','https://res.cloudinary.com/drcy0kzdm/image/upload/v1766311997/r-tech-products/ltnhslfg95oeyve5zeot.jpg','https://res.cloudinary.com/drcy0kzdm/image/upload/v1766311997/r-tech-products/ltnhslfg95oeyve5zeot.jpg',0,'\"{\\\"processor\\\":\\\"AMD Ryzen 5-7520U\\\",\\\"gpu\\\":\\\"AMD Radeon Graphics\\\",\\\"ram\\\":\\\"8GB\\\",\\\"storage\\\":\\\"512GB SSD\\\",\\\"display\\\":\\\"14\\\\\\\" FHD (1920x1080)\\\",\\\"warranty\\\":\\\"3 months from store\\\",\\\"condition\\\":\\\"Used\\\",\\\"extras\\\":\\\"Bag, Wireless Mouse\\\"}\"',NULL,'2025-12-22 03:07:16','2025-12-22 03:07:16'),
(4,1,'HP Laptop 14 EM0014','Compact 14-inch laptop with AMD Ryzen 3 processor. Backlit keyboard and ample storage. Quality controlled with 3-month warranty. Bonus: Bag and Wireless Mouse.',5500000.00,'HP-14-EM0014','https://res.cloudinary.com/drcy0kzdm/image/upload/v1766312004/r-tech-products/jfyludkwm0l7wrko58i9.jpg','https://res.cloudinary.com/drcy0kzdm/image/upload/v1766312004/r-tech-products/jfyludkwm0l7wrko58i9.jpg',1,'\"{\\\"processor\\\":\\\"AMD Ryzen 3-7320U\\\",\\\"gpu\\\":\\\"AMD Radeon Graphics\\\",\\\"ram\\\":\\\"8GB\\\",\\\"storage\\\":\\\"512GB SSD\\\",\\\"display\\\":\\\"14\\\\\\\" FHD (1920x1080)\\\",\\\"keyboard\\\":\\\"Backlit\\\",\\\"warranty\\\":\\\"3 months from store\\\",\\\"condition\\\":\\\"Used\\\",\\\"extras\\\":\\\"Bag, Wireless Mouse\\\"}\"',NULL,'2025-12-22 03:07:16','2025-12-22 03:07:16'),
(5,1,'Lenovo Ideapad Slim 3i','Sleek and portable 14-inch laptop with Intel Core i3. Official warranty until March 2027. Perfect for students and office work. Bonus: Bag and Wireless Mouse.',5000000.00,'LENOVO-SLIM3I','https://res.cloudinary.com/drcy0kzdm/image/upload/v1766312013/r-tech-products/vwp1dth0z1ofudnlqgzi.jpg','https://res.cloudinary.com/drcy0kzdm/image/upload/v1766312013/r-tech-products/vwp1dth0z1ofudnlqgzi.jpg',0,'\"{\\\"processor\\\":\\\"Intel Core i3-1215U\\\",\\\"gpu\\\":\\\"Intel UHD Graphics\\\",\\\"ram\\\":\\\"8GB\\\",\\\"storage\\\":\\\"256GB SSD\\\",\\\"display\\\":\\\"14\\\\\\\" FHD (1920x1080)\\\",\\\"keyboard\\\":\\\"Backlit\\\",\\\"warranty\\\":\\\"Official warranty until March 2027\\\",\\\"condition\\\":\\\"Used\\\",\\\"extras\\\":\\\"Bag, Wireless Mouse\\\"}\"',NULL,'2025-12-22 03:07:16','2025-12-22 03:07:16'),
(6,1,'Acer Aspire Lite 14','Ultra-affordable laptop with Intel N150 processor for basic computing needs. Official warranty until April 2026. Ideal for students. Bonus: Bag and Wireless Mouse.',4000000.00,'ACER-LITE14','https://res.cloudinary.com/drcy0kzdm/image/upload/v1766312023/r-tech-products/rsmsztfqreldj9czimmi.jpg','https://res.cloudinary.com/drcy0kzdm/image/upload/v1766312023/r-tech-products/rsmsztfqreldj9czimmi.jpg',1,'\"{\\\"processor\\\":\\\"Intel N150\\\",\\\"gpu\\\":\\\"Intel Graphics\\\",\\\"ram\\\":\\\"8GB\\\",\\\"storage\\\":\\\"256GB SSD\\\",\\\"display\\\":\\\"14\\\\\\\" FHD (1920x1080)\\\",\\\"warranty\\\":\\\"Official warranty until April 2026\\\",\\\"condition\\\":\\\"Used\\\",\\\"extras\\\":\\\"Bag, Wireless Mouse\\\"}\"',NULL,'2025-12-22 03:07:16','2025-12-22 03:07:16'),
(7,1,'Asus Vivobook E410M','Entry-level laptop with Intel Celeron processor. Features numeric keypad for productivity. Quality controlled with 3-month warranty. Bonus: Bag and Wireless Mouse.',3300000.00,'ASUS-E410M','https://res.cloudinary.com/drcy0kzdm/image/upload/v1766312028/r-tech-products/hgkdkh4ekt24kxektyv1.jpg','https://res.cloudinary.com/drcy0kzdm/image/upload/v1766312028/r-tech-products/hgkdkh4ekt24kxektyv1.jpg',1,'\"{\\\"processor\\\":\\\"Intel Celeron N4020\\\",\\\"gpu\\\":\\\"Intel UHD Graphics\\\",\\\"ram\\\":\\\"4GB\\\",\\\"storage\\\":\\\"512GB SSD\\\",\\\"display\\\":\\\"14\\\\\\\" HD (1366x768)\\\",\\\"features\\\":\\\"Numeric Keypad\\\",\\\"warranty\\\":\\\"3 months from store\\\",\\\"condition\\\":\\\"Used\\\",\\\"extras\\\":\\\"Bag, Wireless Mouse\\\"}\"',NULL,'2025-12-22 03:07:16','2025-12-22 03:07:16'),
(8,1,'MacBook Pro M1 2020','Premium Apple laptop with M1 chip. Excellent for creative professionals and developers. 13-inch Retina display with True Tone. Quality controlled with 3-month warranty. Bonus: Bag, USB-C Hub, Original Accessories.',12500000.00,'APPLE-MBP-M1-2020','https://images.unsplash.com/photo-1517336714731-489689fd1ca8?auto=format&fit=crop&w=1000&q=80','https://images.unsplash.com/photo-1517336714731-489689fd1ca8?auto=format&fit=crop&w=500&q=60',1,'\"{\\\"processor\\\":\\\"Apple M1 Chip (8-core CPU)\\\",\\\"gpu\\\":\\\"Apple M1 GPU (7-core)\\\",\\\"ram\\\":\\\"8GB Unified Memory\\\",\\\"storage\\\":\\\"256GB SSD\\\",\\\"display\\\":\\\"13.3\\\\\\\" Retina (2560x1600)\\\",\\\"battery\\\":\\\"Up to 17 hours\\\",\\\"warranty\\\":\\\"3 months from store\\\",\\\"condition\\\":\\\"Used - Excellent\\\",\\\"extras\\\":\\\"Bag, USB-C Hub, Original Charger\\\"}\"',NULL,'2025-12-22 03:07:16','2025-12-22 03:07:16');
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text,
  `payload` longtext NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `sessions` VALUES
('1mdW4TGYijAPNllCz6t35VIQCoiI6AZUUIXyCFpO',NULL,'10.1.7.7','curl/8.17.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoibXRaNDdmNlgwRGV1SVA2YUxHRjVFNmVrZVE2NEFWYmNwdk1HeWZlaiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Nzc6Imh0dHA6Ly9yLXRlY2gtY29tcHV0ZXItYXBpLTZmYzAzNzBiODZkYy5oZXJva3VhcHAuY29tL2FwaS9wcm9kdWN0cz9wZXJfcGFnZT0yIjtzOjU6InJvdXRlIjtOO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766393134),
('6vFwXSVNlZa1xaB9xBDCLfu7enQ2ZKhW1bZvrl9A',NULL,'10.1.92.129','vercel-screenshot/1.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoidzFHWkNTblV6N2hVZXlyQXdLekNma0ZZTDRQNUJyNzFKcklQbWNrNiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6ODU6Imh0dHA6Ly9yLXRlY2gtY29tcHV0ZXItYXBpLTZmYzAzNzBiODZkYy5oZXJva3VhcHAuY29tL2FwaS9wcm9kdWN0cz9wYWdlPTEmcGVyX3BhZ2U9MTIiO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1766392722),
('8drAGhbjwTvBx6XzB0Xj5rGAQzpecbsYdo8RbjJr',NULL,'10.1.19.69','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiSVpmS25VYzZYZ1hJTGxxOHYwcDF4VnlBYVNvOEs4S04xUVFtTkxZWCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6ODU6Imh0dHA6Ly9yLXRlY2gtY29tcHV0ZXItYXBpLTZmYzAzNzBiODZkYy5oZXJva3VhcHAuY29tL2FwaS9wcm9kdWN0cz9wYWdlPTEmcGVyX3BhZ2U9MTIiO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1766392739),
('BxUcg8N2KmPekBqec6h4BuDhuYOjruZIrU9dnn2l',NULL,'10.1.2.180','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiSlpzN3ZGTWdTMGRUTlBqVDV0Zko0ZGs2VEduOHZDMVZRdU1nY0dIbCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Njg6Imh0dHA6Ly9yLXRlY2gtY29tcHV0ZXItYXBpLTZmYzAzNzBiODZkYy5oZXJva3VhcHAuY29tL2FwaS9wcm9kdWN0cy8xIjtzOjU6InJvdXRlIjtOO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766392762),
('DbU9HzpGXa06uK5LLSDp6SQGDpmVMoa1405UC11J',NULL,'10.1.82.20','vercel-screenshot/1.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiV0Q0QmZyQ3Vmb2pscDlRVnVUb0wwdnlTZXYxWkx0UmQ4WE5JbktDbSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6ODU6Imh0dHA6Ly9yLXRlY2gtY29tcHV0ZXItYXBpLTZmYzAzNzBiODZkYy5oZXJva3VhcHAuY29tL2FwaS9wcm9kdWN0cz9wYWdlPTEmcGVyX3BhZ2U9MTIiO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1766392664),
('ezoMm8ftaO5O2QKWxxuS7VwLXrRwaMAPS4hfkwaK',NULL,'10.1.92.129','curl/8.17.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiVVlWdFFJY3BQWmRhNkI1Q2lTa3BvWTRUNENqUWJXM292dzdxNjhQTSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Njg6Imh0dHA6Ly9yLXRlY2gtY29tcHV0ZXItYXBpLTZmYzAzNzBiODZkYy5oZXJva3VhcHAuY29tL2FwaS9wcm9kdWN0cy83IjtzOjU6InJvdXRlIjtOO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766393133),
('qdTBDs9f0zzgEEEDg8Sq1JrTSmWx5x9neBYUrZVz',NULL,'10.1.19.69','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiS3RGOE1zVU9KRU5BekFLdTdmejV0T3Z1Z3Z1MXU5c2QyTGdMT3d1NyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6ODU6Imh0dHA6Ly9yLXRlY2gtY29tcHV0ZXItYXBpLTZmYzAzNzBiODZkYy5oZXJva3VhcHAuY29tL2FwaS9wcm9kdWN0cz9wYWdlPTEmcGVyX3BhZ2U9MTIiO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1766392762),
('TP2B3U4uaWlG9EHekP4Si5mvVEtolm36rIBw7hAd',NULL,'10.1.61.239','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Mobile Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiZm5JTlZjQUxYNFdBN2ZwRFdMVWxQeFBkNnJkYW9ucVAxZmViYmY0bSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6ODU6Imh0dHA6Ly9yLXRlY2gtY29tcHV0ZXItYXBpLTZmYzAzNzBiODZkYy5oZXJva3VhcHAuY29tL2FwaS9wcm9kdWN0cz9wYWdlPTEmcGVyX3BhZ2U9MTIiO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1766386507),
('Uma4szxYg8bTAva65SHyCKQKUmHEecTF2oIDYSoJ',NULL,'10.1.19.69','curl/8.17.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiSkJocjQ2SXpKY0VBdGY1SmlpYWE2czVPaDk5b1dCS1dBbUl2SWpFaiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Nzc6Imh0dHA6Ly9yLXRlY2gtY29tcHV0ZXItYXBpLTZmYzAzNzBiODZkYy5oZXJva3VhcHAuY29tL2FwaS9wcm9kdWN0cz9wZXJfcGFnZT0zIjtzOjU6InJvdXRlIjtOO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766392844),
('vNuhCCevwaFhS8Fxq3HxKIznzXi35905angn9hhR',NULL,'10.1.49.34','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiUEZUVlcxZFp3WFlXZTc2MVlKNjRBdVNleW1EOEdzVG52ZWZWNEN0OCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6ODU6Imh0dHA6Ly9yLXRlY2gtY29tcHV0ZXItYXBpLTZmYzAzNzBiODZkYy5oZXJva3VhcHAuY29tL2FwaS9wcm9kdWN0cz9wYWdlPTEmcGVyX3BhZ2U9MTIiO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1766389221);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','editor') NOT NULL DEFAULT 'editor',
  `last_login_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `users` VALUES
(1,'Ihza Mahendra','ihza@rtech.com','2025-12-22 03:07:15','$2y$12$sRMOPNfJlb6sDbHBYX5Vcuev3GQ8XiurS9oC7JPHWJeY6ExyGOxHW','admin',NULL,NULL,'2025-12-22 03:07:15','2025-12-22 03:07:15'),
(2,'Sarah Chen','sarah.chen@rtech.com','2025-12-22 03:07:15','$2y$12$aNmjadNsEKiea0OyKBOCb.L9tlyDQJZbrr.3deAIqfhIzTGYEk9w.','admin',NULL,NULL,'2025-12-22 03:07:15','2025-12-22 03:07:15'),
(3,'Dewi Lestari','dewi.lestari@rtech.com','2025-12-22 03:07:15','$2y$12$DCPo4K5oGoB1AB5/KZ/qcO./6hUikVnWog4a1ZCA2Y7/k1mnVJbKW','editor',NULL,NULL,'2025-12-22 03:07:15','2025-12-22 03:07:15'),
(4,'Michael Wijaya','michael.wijaya@rtech.com','2025-12-22 03:07:15','$2y$12$0wfaF0Ipcg1Ar/9ev29aLOrJnipmeaa7xEyCxFsrnOEEaZ7afqFMa','editor',NULL,NULL,'2025-12-22 03:07:15','2025-12-22 03:07:15'),
(5,'Test Admin','admin@rtech.test','2025-12-22 03:07:16','$2y$12$vYvr0Tp6sZCQpJcmA1n3oOwKxYlwqBfk3APtWKwlI3DQz7G1FuFZq','admin',NULL,NULL,'2025-12-22 03:07:16','2025-12-22 03:07:16'),
(6,'Test Editor','editor@rtech.test','2025-12-22 03:07:16','$2y$12$XXThX/VgS1VL7N73VTFCA.qvkP5wKui1VzwcWlYdhUPah5M4Ih3zC','editor',NULL,NULL,'2025-12-22 03:07:16','2025-12-22 03:07:16');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
commit;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*M!100616 SET NOTE_VERBOSITY=@OLD_NOTE_VERBOSITY */;

-- Dump completed on 2025-12-22 15:47:06
