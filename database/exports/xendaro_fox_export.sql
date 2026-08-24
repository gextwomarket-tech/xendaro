
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
DROP TABLE IF EXISTS `account_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `account_types` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) NOT NULL,
  `depot_min` decimal(12,2) NOT NULL DEFAULT 0.00,
  `spread_min` decimal(8,5) NOT NULL DEFAULT 0.00000,
  `levier_max` int(10) unsigned NOT NULL DEFAULT 100,
  `swap_free` tinyint(1) NOT NULL DEFAULT 0,
  `description` text DEFAULT NULL,
  `ordre` int(10) unsigned NOT NULL DEFAULT 0,
  `est_actif` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `account_types` WRITE;
/*!40000 ALTER TABLE `account_types` DISABLE KEYS */;
INSERT INTO `account_types` VALUES (1,'Standard',100.00,0.00012,200,0,'Le compte idéal pour débuter, spreads compétitifs et exécution fiable sur tous les instruments.',1,1,'2026-08-23 06:05:04','2026-08-23 06:05:04'),(2,'ECN',500.00,0.00002,500,0,'Accès direct au marché interbancaire (ECN), spreads ultra-serrés dès 0.2 pip, commission par lot.',2,1,'2026-08-23 06:05:04','2026-08-23 06:05:04'),(3,'VIP',5000.00,0.00001,500,0,'Conditions premium, gestionnaire de compte dédié, spreads les plus bas de la plateforme.',3,1,'2026-08-23 06:05:04','2026-08-23 06:05:04'),(4,'Islamique (sans swap)',100.00,0.00015,200,1,'Compte conforme à la Charia, sans frais de swap sur les positions conservées la nuit.',4,1,'2026-08-23 06:05:04','2026-08-23 06:05:04');
/*!40000 ALTER TABLE `account_types` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `affiliate_commissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `affiliate_commissions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `parrain_id` bigint(20) unsigned NOT NULL,
  `filleul_id` bigint(20) unsigned NOT NULL,
  `montant` decimal(18,2) NOT NULL DEFAULT 0.00,
  `statut` enum('en_attente','valide','refuse') NOT NULL DEFAULT 'en_attente',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `affiliate_commissions_filleul_id_foreign` (`filleul_id`),
  KEY `affiliate_commissions_parrain_id_statut_index` (`parrain_id`,`statut`),
  CONSTRAINT `affiliate_commissions_filleul_id_foreign` FOREIGN KEY (`filleul_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `affiliate_commissions_parrain_id_foreign` FOREIGN KEY (`parrain_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `affiliate_commissions` WRITE;
/*!40000 ALTER TABLE `affiliate_commissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `affiliate_commissions` ENABLE KEYS */;
UNLOCK TABLES;
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

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
INSERT INTO `cache` VALUES ('xendaro-fox-cache-contact-form:127.0.0.1','i:1;',1787530784),('xendaro-fox-cache-contact-form:127.0.0.1:timer','i:1787530784;',1787530784),('xendaro-fox-cache-site_identifier.current','O:25:\"App\\Models\\SiteIdentifier\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:16:\"site_identifiers\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:23:{s:2:\"id\";i:1;s:14:\"nom_plateforme\";s:11:\"Xendaro Fox\";s:6:\"slogan\";s:70:\"Tradez le Forex, les Cryptos, l\'Or et plus encore, en toute confiance.\";s:17:\"langue_par_defaut\";s:2:\"fr\";s:18:\"couleur_principale\";s:7:\"#F5A623\";s:18:\"couleur_secondaire\";s:7:\"#5B8CFF\";s:8:\"about_us\";s:240:\"Xendaro Fox est une plateforme de trading en ligne offrant un accès aux marchés Forex, Crypto, Or/Métaux, Matières premières, Indices et Actions, avec une expérience utilisateur moderne inspirée des meilleures plateformes du marché.\";s:15:\"path_light_logo\";N;s:14:\"path_dark_logo\";N;s:16:\"path_favicon_png\";N;s:15:\"phone_contact_1\";s:17:\"+33 1 23 45 67 89\";s:15:\"phone_contact_2\";N;s:11:\"email_pro_1\";s:22:\"contact@xendarofox.com\";s:11:\"email_pro_2\";N;s:16:\"location_adresse\";s:19:\"Adresse à définir\";s:3:\"cvg\";s:87:\"Conditions Générales de Vente de Xendaro Fox — contenu à finaliser avec le client.\";s:8:\"policies\";s:85:\"Politique de confidentialité de Xendaro Fox — contenu à finaliser avec le client.\";s:7:\"cookies\";s:76:\"Politique de cookies de Xendaro Fox — contenu à finaliser avec le client.\";s:12:\"nos_services\";s:92:\"Découvrez nos types de comptes, nos plateformes de trading et nos conditions compétitives.\";s:7:\"contact\";s:68:\"Notre équipe est disponible pour répondre à toutes vos questions.\";s:15:\"reseaux_sociaux\";N;s:10:\"created_at\";s:19:\"2026-08-23 06:48:25\";s:10:\"updated_at\";s:19:\"2026-08-23 06:48:25\";}s:11:\"\0*\0original\";a:23:{s:2:\"id\";i:1;s:14:\"nom_plateforme\";s:11:\"Xendaro Fox\";s:6:\"slogan\";s:70:\"Tradez le Forex, les Cryptos, l\'Or et plus encore, en toute confiance.\";s:17:\"langue_par_defaut\";s:2:\"fr\";s:18:\"couleur_principale\";s:7:\"#F5A623\";s:18:\"couleur_secondaire\";s:7:\"#5B8CFF\";s:8:\"about_us\";s:240:\"Xendaro Fox est une plateforme de trading en ligne offrant un accès aux marchés Forex, Crypto, Or/Métaux, Matières premières, Indices et Actions, avec une expérience utilisateur moderne inspirée des meilleures plateformes du marché.\";s:15:\"path_light_logo\";N;s:14:\"path_dark_logo\";N;s:16:\"path_favicon_png\";N;s:15:\"phone_contact_1\";s:17:\"+33 1 23 45 67 89\";s:15:\"phone_contact_2\";N;s:11:\"email_pro_1\";s:22:\"contact@xendarofox.com\";s:11:\"email_pro_2\";N;s:16:\"location_adresse\";s:19:\"Adresse à définir\";s:3:\"cvg\";s:87:\"Conditions Générales de Vente de Xendaro Fox — contenu à finaliser avec le client.\";s:8:\"policies\";s:85:\"Politique de confidentialité de Xendaro Fox — contenu à finaliser avec le client.\";s:7:\"cookies\";s:76:\"Politique de cookies de Xendaro Fox — contenu à finaliser avec le client.\";s:12:\"nos_services\";s:92:\"Découvrez nos types de comptes, nos plateformes de trading et nos conditions compétitives.\";s:7:\"contact\";s:68:\"Notre équipe est disponible pour répondre à toutes vos questions.\";s:15:\"reseaux_sociaux\";N;s:10:\"created_at\";s:19:\"2026-08-23 06:48:25\";s:10:\"updated_at\";s:19:\"2026-08-23 06:48:25\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:1:{s:15:\"reseaux_sociaux\";s:5:\"array\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:20:{i:0;s:14:\"nom_plateforme\";i:1;s:6:\"slogan\";i:2;s:17:\"langue_par_defaut\";i:3;s:18:\"couleur_principale\";i:4;s:18:\"couleur_secondaire\";i:5;s:8:\"about_us\";i:6;s:15:\"path_light_logo\";i:7;s:14:\"path_dark_logo\";i:8;s:16:\"path_favicon_png\";i:9;s:15:\"phone_contact_1\";i:10;s:15:\"phone_contact_2\";i:11;s:11:\"email_pro_1\";i:12;s:11:\"email_pro_2\";i:13;s:16:\"location_adresse\";i:14;s:3:\"cvg\";i:15;s:8:\"policies\";i:16;s:7:\"cookies\";i:17;s:12:\"nos_services\";i:18;s:7:\"contact\";i:19;s:15:\"reseaux_sociaux\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}',2102858062);
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;
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

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(255) NOT NULL,
  `nom_fr` varchar(255) NOT NULL,
  `nom_en` varchar(255) DEFAULT NULL,
  `slug` varchar(255) NOT NULL,
  `ordre` int(10) unsigned NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categories_type_slug_unique` (`type`,`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,'faq','Compte & Inscription','Account & Registration','compte-inscription',1,'2026-08-23 06:05:04','2026-08-23 06:05:04'),(2,'faq','Dépôts & Retraits','Deposits & Withdrawals','depots-retraits',2,'2026-08-23 06:05:04','2026-08-23 06:05:04'),(3,'faq','Trading','Trading','trading',3,'2026-08-23 06:05:04','2026-08-23 06:05:04'),(4,'faq','Sécurité','Security','securite',4,'2026-08-23 06:05:04','2026-08-23 06:05:04'),(5,'education','Débutant','Beginner','debutant',1,'2026-08-23 06:05:04','2026-08-23 06:05:04'),(6,'education','Analyse technique','Technical Analysis','analyse-technique',2,'2026-08-23 06:05:04','2026-08-23 06:05:04'),(7,'education','Gestion du risque','Risk Management','gestion-du-risque',3,'2026-08-23 06:05:04','2026-08-23 06:05:04'),(8,'education','Webinaires','Webinars','webinaires',4,'2026-08-23 06:05:04','2026-08-23 06:05:04'),(9,'news','Forex','Forex','forex',1,'2026-08-23 06:05:04','2026-08-23 06:05:04'),(10,'news','Crypto','Crypto','crypto',2,'2026-08-23 06:05:04','2026-08-23 06:05:04'),(11,'news','Matières premières','Commodities','matieres-premieres',3,'2026-08-23 06:05:04','2026-08-23 06:05:04'),(12,'news','Actions & Indices','Stocks & Indices','actions-indices',4,'2026-08-23 06:05:04','2026-08-23 06:05:04');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `contact_messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contact_messages` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `sujet` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `est_traite` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `contact_messages` WRITE;
/*!40000 ALTER TABLE `contact_messages` DISABLE KEYS */;
INSERT INTO `contact_messages` VALUES (1,'Sophie Lambert-Dupont','autofillion@gmail.com','Question générale sur la plateforme','Bonjour, je découvre Xendaro Fox et j\'aimerais en savoir plus sur les frais. Merci !',0,'2026-08-23 23:14:44','2026-08-23 23:14:44');
/*!40000 ALTER TABLE `contact_messages` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `economic_events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `economic_events` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `titre` varchar(255) NOT NULL,
  `devise` varchar(10) NOT NULL,
  `importance` enum('faible','moyenne','haute') NOT NULL DEFAULT 'moyenne',
  `date_heure` datetime NOT NULL,
  `valeur_precedente` varchar(255) DEFAULT NULL,
  `valeur_prevue` varchar(255) DEFAULT NULL,
  `valeur_reelle` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `economic_events` WRITE;
/*!40000 ALTER TABLE `economic_events` DISABLE KEYS */;
INSERT INTO `economic_events` VALUES (1,'Décision de taux directeur - Fed','USD','haute','2026-08-24 20:00:00','5.50%','5.25%',NULL,'2026-08-23 06:05:04','2026-08-23 06:05:04'),(2,'Indice des prix à la consommation (CPI)','USD','haute','2026-08-25 14:30:00','3.2%','3.0%',NULL,'2026-08-23 06:05:04','2026-08-23 06:05:04'),(3,'Taux de chômage','EUR','moyenne','2026-08-25 11:00:00','6.5%','6.4%',NULL,'2026-08-23 06:05:04','2026-08-23 06:05:04'),(4,'Décision de taux directeur - BCE','EUR','haute','2026-08-26 14:15:00','4.50%','4.50%',NULL,'2026-08-23 06:05:04','2026-08-23 06:05:04'),(5,'PIB trimestriel','GBP','moyenne','2026-08-27 08:00:00','0.2%','0.3%',NULL,'2026-08-23 06:05:04','2026-08-23 06:05:04'),(6,'Indice PMI manufacturier','USD','moyenne','2026-08-28 15:45:00','49.1','49.5',NULL,'2026-08-23 06:05:04','2026-08-23 06:05:04'),(7,'Stocks de pétrole brut (EIA)','USD','faible','2026-08-24 16:30:00','-2.1M','-1.5M',NULL,'2026-08-23 06:05:04','2026-08-23 06:05:04'),(8,'Ventes au détail','USD','moyenne','2026-08-22 14:30:00','0.4%','0.2%','0.3%','2026-08-23 06:05:04','2026-08-23 06:05:04'),(9,'Balance commerciale','JPY','faible','2026-08-21 01:50:00','-¥450B','-¥400B','-¥380B','2026-08-23 06:05:04','2026-08-23 06:05:04'),(10,'Indice de confiance des consommateurs','EUR','faible','2026-08-29 16:00:00','-15.5','-15.0',NULL,'2026-08-23 06:05:04','2026-08-23 06:05:04');
/*!40000 ALTER TABLE `economic_events` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `education_resources`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `education_resources` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `titre_fr` varchar(255) NOT NULL,
  `titre_en` varchar(255) DEFAULT NULL,
  `slug` varchar(255) NOT NULL,
  `contenu_fr` longtext NOT NULL,
  `contenu_en` longtext DEFAULT NULL,
  `category_id` bigint(20) unsigned DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'cours',
  `est_actif` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `education_resources_slug_unique` (`slug`),
  KEY `education_resources_category_id_foreign` (`category_id`),
  CONSTRAINT `education_resources_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `education_resources` WRITE;
/*!40000 ALTER TABLE `education_resources` DISABLE KEYS */;
INSERT INTO `education_resources` VALUES (1,'Les bases du trading Forex','Forex Trading Basics','les-bases-du-trading-forex','Le marché des changes (Forex) est le plus grand marché financier au monde. Dans ce cours, vous apprendrez ce qu\'est une paire de devises, comment se lit une cotation, la différence entre position acheteuse et vendeuse, et les bases pour passer votre premier ordre. Nous verrons également le vocabulaire essentiel : pip, spread, lot et effet de levier.','The foreign exchange market (Forex) is the largest financial market in the world. In this course you\'ll learn what a currency pair is, how to read a quote, the difference between a long and short position, and the basics to place your first order. We\'ll also cover the essential vocabulary: pip, spread, lot and leverage.',5,NULL,'cours',1,'2026-08-23 06:05:04','2026-08-23 06:05:04'),(2,'Comprendre les chandeliers japonais','Understanding Japanese Candlesticks','comprendre-les-chandeliers-japonais','Les chandeliers japonais sont l\'outil de lecture graphique le plus utilisé par les traders. Ce cours détaille l\'anatomie d\'un chandelier (ouverture, clôture, mèches), les figures classiques (marteau, étoile filante, doji) et comment les intégrer dans votre stratégie d\'entrée en position.','Japanese candlesticks are the most widely used charting tool among traders. This course covers candle anatomy (open, close, wicks), classic patterns (hammer, shooting star, doji) and how to integrate them into your entry strategy.',6,NULL,'cours',1,'2026-08-23 06:05:04','2026-08-23 06:05:04'),(3,'Gérer son risque : la règle des 2%','Managing Risk: The 2% Rule','gerer-son-risque-la-regle-des-2','Une gestion du risque rigoureuse est ce qui distingue les traders qui durent des autres. Découvrez la règle des 2% par trade, comment calculer votre taille de position en fonction de votre stop loss, et pourquoi le ratio risque/rendement est central dans toute stratégie.','Rigorous risk management is what separates traders who last from the rest. Discover the 2% per-trade rule, how to size your position based on your stop loss, and why the risk/reward ratio is central to any strategy.',7,NULL,'cours',1,'2026-08-23 06:05:04','2026-08-23 06:05:04'),(4,'Glossaire du trader','Trader\'s Glossary','glossaire-du-trader','Pip : plus petite variation de prix d\'une paire de devises. Spread : différence entre prix d\'achat et de vente. Lot : unité standard de volume de trading (100 000 unités pour un lot standard). Levier : capacité à trader une position supérieure à son capital. Marge : capital immobilisé pour ouvrir une position.','Pip: the smallest price move of a currency pair. Spread: the difference between the bid and ask price. Lot: standard trading volume unit (100,000 units for a standard lot). Leverage: the ability to trade a position larger than your capital. Margin: capital locked to open a position.',5,NULL,'glossaire',1,'2026-08-23 06:05:04','2026-08-23 06:05:04'),(5,'Webinaire : Analyser le marché des cryptomonnaies','Webinar: Analyzing the Crypto Market','webinaire-analyser-le-marche-des-cryptomonnaies','Rejoignez nos experts pour une session dédiée à l\'analyse du marché crypto : cycles de marché, corrélation avec le Bitcoin, et gestion de la volatilité propre aux cryptomonnaies.','Join our experts for a session dedicated to crypto market analysis: market cycles, correlation with Bitcoin, and managing the volatility specific to cryptocurrencies.',8,NULL,'webinaire',1,'2026-08-23 06:05:04','2026-08-23 06:05:04'),(6,'L\'effet de levier : opportunités et risques','Leverage: Opportunities and Risks','leffet-de-levier-opportunites-et-risques','L\'effet de levier permet de démultiplier ses gains potentiels, mais aussi ses pertes. Ce cours explique comment il fonctionne concrètement, avec des exemples chiffrés, et les bonnes pratiques pour l\'utiliser sans mettre en péril votre capital.','Leverage can amplify your potential gains, but also your losses. This course explains how it works concretely, with worked examples, and best practices to use it without jeopardizing your capital.',7,NULL,'cours',1,'2026-08-23 06:05:04','2026-08-23 06:05:04');
/*!40000 ALTER TABLE `education_resources` ENABLE KEYS */;
UNLOCK TABLES;
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

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `faq_contents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `faq_contents` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `categorie_id` bigint(20) unsigned DEFAULT NULL,
  `question_fr` varchar(255) NOT NULL,
  `reponse_fr` text NOT NULL,
  `question_en` varchar(255) DEFAULT NULL,
  `reponse_en` text DEFAULT NULL,
  `ordre` int(10) unsigned NOT NULL DEFAULT 0,
  `est_actif` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `faq_contents_categorie_id_foreign` (`categorie_id`),
  CONSTRAINT `faq_contents_categorie_id_foreign` FOREIGN KEY (`categorie_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `faq_contents` WRITE;
/*!40000 ALTER TABLE `faq_contents` DISABLE KEYS */;
INSERT INTO `faq_contents` VALUES (1,1,'Comment créer un compte Xendaro Fox ?','Cliquez sur \"Créer un compte\", renseignez votre nom, votre email et un mot de passe, acceptez les CGV puis validez votre adresse email via le code reçu.','How do I create a Xendaro Fox account?','Click \"Create Account\", fill in your name, email and password, accept the terms, then verify your email using the code you receive.',1,1,'2026-08-23 06:05:04','2026-08-23 06:05:04'),(2,1,'Dois-je vérifier mon identité (KYC) ?','Oui, une vérification d\'identité est obligatoire avant tout retrait de fonds, conformément à nos obligations réglementaires.','Do I need to verify my identity (KYC)?','Yes, identity verification is required before any withdrawal, in line with our regulatory obligations.',2,1,'2026-08-23 06:05:04','2026-08-23 06:05:04'),(3,2,'Quels sont les moyens de dépôt disponibles ?','Carte bancaire, virement bancaire, e-wallets et cryptomonnaies, selon les moyens de paiement activés par notre équipe.','What deposit methods are available?','Credit/debit card, bank transfer, e-wallets and cryptocurrencies, depending on the payment methods enabled by our team.',1,1,'2026-08-23 06:05:04','2026-08-23 06:05:04'),(4,2,'Combien de temps prend un retrait ?','Le délai de traitement dépend du moyen de paiement choisi, généralement entre quelques minutes et 3 jours ouvrés.','How long does a withdrawal take?','Processing time depends on the chosen payment method, generally between a few minutes and 3 business days.',2,1,'2026-08-23 06:05:04','2026-08-23 06:05:04'),(5,3,'Qu\'est-ce qu\'un compte démo ?','Un compte démo vous permet de trader avec un capital virtuel de 10 000$ pour tester la plateforme sans risque.','What is a demo account?','A demo account lets you trade with virtual capital of $10,000 to test the platform risk-free.',1,1,'2026-08-23 06:05:04','2026-08-23 06:05:04'),(6,3,'Quel effet de levier proposez-vous ?','L\'effet de levier varie selon l\'instrument, jusqu\'à 500:1 sur le Forex. Voir la page Conditions de trading pour le détail.','What leverage do you offer?','Leverage varies by instrument, up to 500:1 on Forex. See the Trading Conditions page for details.',2,1,'2026-08-23 06:05:04','2026-08-23 06:05:04'),(7,4,'Mes fonds sont-ils en sécurité ?','Les fonds clients sont séparés des comptes opérationnels de la société et protégés selon les standards du secteur.','Are my funds safe?','Client funds are held separately from the company operating accounts and protected to industry standards.',1,1,'2026-08-23 06:05:04','2026-08-23 06:05:04');
/*!40000 ALTER TABLE `faq_contents` ENABLE KEYS */;
UNLOCK TABLES;
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

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;
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

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `kyc_documents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `kyc_documents` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `type_document` enum('piece_identite','justificatif_domicile') NOT NULL,
  `fichier_path` varchar(255) NOT NULL,
  `statut` enum('en_attente','valide','refuse') NOT NULL DEFAULT 'en_attente',
  `commentaire_admin` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `kyc_documents_user_id_type_document_index` (`user_id`,`type_document`),
  CONSTRAINT `kyc_documents_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `kyc_documents` WRITE;
/*!40000 ALTER TABLE `kyc_documents` DISABLE KEYS */;
/*!40000 ALTER TABLE `kyc_documents` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `market_instruments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `market_instruments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) NOT NULL,
  `symbole_interne` varchar(255) NOT NULL,
  `categorie` enum('forex','crypto','metal','commodite','indice','action') NOT NULL,
  `symbole_provider_externe` varchar(255) DEFAULT NULL,
  `provider` varchar(255) NOT NULL DEFAULT 'tradingview',
  `spread` decimal(10,5) NOT NULL DEFAULT 0.00000,
  `levier_max` int(10) unsigned NOT NULL DEFAULT 100,
  `prix_reference` decimal(18,5) NOT NULL DEFAULT 0.00000,
  `est_actif` tinyint(1) NOT NULL DEFAULT 1,
  `icone` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `market_instruments_symbole_interne_unique` (`symbole_interne`),
  KEY `market_instruments_categorie_est_actif_index` (`categorie`,`est_actif`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `market_instruments` WRITE;
/*!40000 ALTER TABLE `market_instruments` DISABLE KEYS */;
INSERT INTO `market_instruments` VALUES (1,'Euro / Dollar US','EURUSD','forex','FX:EURUSD','tradingview',0.00010,500,1.08500,1,NULL,'2026-08-23 05:48:25','2026-08-23 05:48:25'),(2,'Livre Sterling / Dollar US','GBPUSD','forex','FX:GBPUSD','tradingview',0.00015,500,1.26500,1,NULL,'2026-08-23 05:48:25','2026-08-23 05:48:25'),(3,'Dollar US / Yen Japonais','USDJPY','forex','FX:USDJPY','tradingview',0.01200,500,149.50000,1,NULL,'2026-08-23 05:48:25','2026-08-23 05:48:25'),(4,'Dollar Australien / Dollar US','AUDUSD','forex','FX:AUDUSD','tradingview',0.00018,500,0.65500,1,NULL,'2026-08-23 05:48:25','2026-08-23 05:48:25'),(5,'Dollar US / Franc Suisse','USDCHF','forex','FX:USDCHF','tradingview',0.00016,500,0.88000,1,NULL,'2026-08-23 05:48:25','2026-08-23 05:48:25'),(6,'Bitcoin / Dollar US','BTCUSD','crypto','BINANCE:BTCUSDT','tradingview',15.00000,20,62000.00000,1,NULL,'2026-08-23 05:48:25','2026-08-23 05:48:25'),(7,'Ethereum / Dollar US','ETHUSD','crypto','BINANCE:ETHUSDT','tradingview',2.50000,20,3400.00000,1,NULL,'2026-08-23 05:48:25','2026-08-23 05:48:25'),(8,'Solana / Dollar US','SOLUSD','crypto','BINANCE:SOLUSDT','tradingview',0.05000,10,145.00000,1,NULL,'2026-08-23 05:48:25','2026-08-23 05:48:25'),(9,'XRP / Dollar US','XRPUSD','crypto','BINANCE:XRPUSDT','tradingview',0.00100,10,0.62000,1,NULL,'2026-08-23 05:48:25','2026-08-23 05:48:25'),(10,'Or','XAUUSD','metal','OANDA:XAUUSD','tradingview',0.25000,200,2350.00000,1,NULL,'2026-08-23 05:48:25','2026-08-23 05:48:25'),(11,'Argent','XAGUSD','metal','OANDA:XAGUSD','tradingview',0.02000,200,27.50000,1,NULL,'2026-08-23 05:48:25','2026-08-23 05:48:25'),(12,'Pétrole Brut WTI','WTIUSD','commodite','TVC:USOIL','tradingview',0.03000,100,78.20000,1,NULL,'2026-08-23 05:48:25','2026-08-23 05:48:25'),(13,'S&P 500','US500','indice','TVC:SPX','tradingview',0.40000,100,5200.00000,1,NULL,'2026-08-23 05:48:25','2026-08-23 05:48:25'),(14,'Nasdaq 100','US100','indice','TVC:NDQ','tradingview',1.00000,100,18200.00000,1,NULL,'2026-08-23 05:48:25','2026-08-23 05:48:25'),(15,'Apple Inc.','AAPL','action','NASDAQ:AAPL','tradingview',0.02000,20,190.00000,1,NULL,'2026-08-23 05:48:25','2026-08-23 05:48:25');
/*!40000 ALTER TABLE `market_instruments` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2026_08_23_064435_create_site_identifiers_table',2),(5,'2026_08_23_064436_create_market_instruments_table',2),(6,'2026_08_23_064436_create_wallets_table',2),(7,'2026_08_23_064437_create_trade_histories_table',2),(8,'2026_08_23_064438_create_categories_table',2),(9,'2026_08_23_064438_create_payment_methods_table',2),(10,'2026_08_23_064439_create_wallet_transactions_table',2),(11,'2026_08_23_064440_create_faq_contents_table',2),(12,'2026_08_23_064616_add_trading_fields_to_users_table',2),(13,'2026_08_23_065914_create_tickets_table',3),(14,'2026_08_23_065915_create_ticket_messages_table',3),(15,'2026_08_23_065916_create_kyc_documents_table',3),(16,'2026_08_23_065917_create_affiliate_commissions_table',3),(17,'2026_08_23_070004_create_notifications_table',4),(18,'2026_08_23_070014_create_account_types_table',5),(19,'2026_08_23_070014_create_promotions_table',5),(20,'2026_08_23_070015_create_education_resources_table',5),(21,'2026_08_23_070016_create_news_articles_table',5),(22,'2026_08_23_070017_create_economic_events_table',5),(23,'2026_08_23_070018_create_contact_messages_table',5),(24,'2026_08_23_202747_add_details_paiement_to_payment_methods_table',6);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `news_articles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `news_articles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `titre_fr` varchar(255) NOT NULL,
  `titre_en` varchar(255) DEFAULT NULL,
  `slug` varchar(255) NOT NULL,
  `contenu_fr` longtext NOT NULL,
  `contenu_en` longtext DEFAULT NULL,
  `category_id` bigint(20) unsigned DEFAULT NULL,
  `instrument_id` bigint(20) unsigned DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `publie_le` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `news_articles_slug_unique` (`slug`),
  KEY `news_articles_category_id_foreign` (`category_id`),
  KEY `news_articles_instrument_id_foreign` (`instrument_id`),
  CONSTRAINT `news_articles_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL,
  CONSTRAINT `news_articles_instrument_id_foreign` FOREIGN KEY (`instrument_id`) REFERENCES `market_instruments` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `news_articles` WRITE;
/*!40000 ALTER TABLE `news_articles` DISABLE KEYS */;
INSERT INTO `news_articles` VALUES (1,'L\'EUR/USD sous pression avant les décisions des banques centrales','EUR/USD Under Pressure Ahead of Central Bank Decisions','leurusd-sous-pression-avant-les-decisions-des-banques-centrales','La paire EUR/USD évolue dans un range serré à l\'approche des prochaines décisions de politique monétaire. Les investisseurs restent prudents, dans l\'attente de signaux clairs sur la trajectoire des taux directeurs des deux côtés de l\'Atlantique.','EUR/USD is trading in a tight range ahead of upcoming monetary policy decisions. Investors remain cautious, awaiting clear signals on the rate path on both sides of the Atlantic.',9,1,NULL,'2026-08-22 07:05:04','2026-08-23 06:05:04','2026-08-23 06:05:04'),(2,'Le Bitcoin retrouve de la vigueur après une phase de consolidation','Bitcoin Regains Strength After a Consolidation Phase','le-bitcoin-retrouve-de-la-vigueur-apres-une-phase-de-consolidation','Après plusieurs semaines de consolidation, le Bitcoin affiche un regain d\'intérêt de la part des investisseurs institutionnels. Les volumes échangés repartent à la hausse, un signal souvent annonciateur de mouvements directionnels.','After several weeks of consolidation, Bitcoin is showing renewed interest from institutional investors. Trading volumes are picking up again, a signal that often precedes directional moves.',10,6,NULL,'2026-08-21 07:05:04','2026-08-23 06:05:04','2026-08-23 06:05:04'),(3,'L\'or continue son ascension, valeur refuge privilégiée','Gold Continues Its Rise as a Preferred Safe Haven','lor-continue-son-ascension-valeur-refuge-privilegiee','Dans un contexte d\'incertitude géopolitique persistante, l\'or continue d\'attirer les capitaux en quête de sécurité. Les analystes surveillent de près le niveau des taux réels, facteur clé de la valorisation du métal jaune.','Amid persistent geopolitical uncertainty, gold continues to attract capital seeking safety. Analysts are closely watching real rate levels, a key driver of the yellow metal\'s valuation.',11,10,NULL,'2026-08-20 07:05:04','2026-08-23 06:05:04','2026-08-23 06:05:04'),(4,'Wall Street : les indices proches de leurs records','Wall Street: Indices Near Record Highs','wall-street-les-indices-proches-de-leurs-records','Les principaux indices américains évoluent proches de leurs plus hauts historiques, portés par des résultats d\'entreprises supérieurs aux attentes et un discours toujours accommodant de la banque centrale.','Major US indices are trading near record highs, supported by better-than-expected corporate earnings and a still-accommodative central bank stance.',12,NULL,NULL,'2026-08-19 07:05:04','2026-08-23 06:05:04','2026-08-23 06:05:04'),(5,'Le pétrole recule sur fond de craintes de ralentissement de la demande','Oil Falls Amid Demand Slowdown Concerns','le-petrole-recule-sur-fond-de-craintes-de-ralentissement-de-la-demande','Les cours du pétrole brut sont repartis à la baisse cette semaine, les investisseurs s\'inquiétant d\'un ralentissement de la demande mondiale malgré les efforts de réduction de l\'offre par certains grands producteurs.','Crude oil prices fell again this week as investors worry about a slowdown in global demand despite supply-reduction efforts from some major producers.',11,NULL,NULL,'2026-08-18 07:05:04','2026-08-23 06:05:04','2026-08-23 06:05:04'),(6,'Ethereum : la mise à jour du réseau attendue par les investisseurs','Ethereum: Investors Await the Network Upgrade','ethereum-la-mise-a-jour-du-reseau-attendue-par-les-investisseurs','La communauté Ethereum attend avec impatience la prochaine mise à jour du réseau, qui devrait améliorer l\'efficacité des transactions et réduire les frais pour les utilisateurs.','The Ethereum community is eagerly awaiting the next network upgrade, expected to improve transaction efficiency and lower fees for users.',10,NULL,NULL,'2026-08-17 07:05:04','2026-08-23 06:05:04','2026-08-23 06:05:04');
/*!40000 ALTER TABLE `news_articles` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `notifications` (
  `id` char(36) NOT NULL,
  `type` varchar(255) NOT NULL,
  `notifiable_type` varchar(255) NOT NULL,
  `notifiable_id` bigint(20) unsigned NOT NULL,
  `data` text NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `notifications` WRITE;
/*!40000 ALTER TABLE `notifications` DISABLE KEYS */;
/*!40000 ALTER TABLE `notifications` ENABLE KEYS */;
UNLOCK TABLES;
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

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `payment_methods`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `payment_methods` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) NOT NULL,
  `type` enum('carte','virement','crypto','e-wallet') NOT NULL,
  `instructions` text DEFAULT NULL,
  `details_paiement` text DEFAULT NULL,
  `frais` decimal(8,2) NOT NULL DEFAULT 0.00,
  `delai_traitement` varchar(255) DEFAULT NULL,
  `est_actif` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `payment_methods` WRITE;
/*!40000 ALTER TABLE `payment_methods` DISABLE KEYS */;
INSERT INTO `payment_methods` VALUES (2,'Virement bancaire','virement','Effectuez un virement SEPA/SWIFT vers le compte bancaire Xendaro Fox ci-dessous en indiquant votre nom complet en référence.','IBAN: FR76 3000 6000 0112 3456 7890 189\nBIC: AGRIFRPP\nBénéficiaire: Xendaro Fox SAS',0.00,'1 à 3 jours ouvrés',1,'2026-08-23 06:02:12','2026-08-23 19:28:51'),(4,'Bitcoin (BTC)','crypto','Envoyez le montant exact en BTC à l\'adresse ci-dessous, puis soumettez votre demande. Le crédit est effectué après confirmation manuelle par notre équipe.','bc1qxy2kgdygjrsqtzq2n0yrf2493p83kkfjhx0wlh',0.00,'30 à 60 minutes après confirmation réseau',1,'2026-08-23 19:28:50','2026-08-23 19:28:50'),(5,'Ethereum (ETH)','crypto','Envoyez le montant exact en ETH (réseau Ethereum ERC20) à l\'adresse ci-dessous, puis soumettez votre demande.','0x71C7656EC7ab88b098defB751B7401B5f6d8976',0.00,'15 à 30 minutes après confirmation réseau',1,'2026-08-23 19:28:51','2026-08-23 19:28:51'),(6,'USDT (TRC20)','crypto','Envoyez le montant exact en USDT sur le réseau TRON (TRC20 uniquement) à l\'adresse ci-dessous.','TXm1p9K3vFq7WnBz2eR8LcH4sYjD6tPqAx',0.00,'10 à 20 minutes après confirmation réseau',1,'2026-08-23 19:28:51','2026-08-23 19:28:51'),(7,'USDT (ERC20)','crypto','Envoyez le montant exact en USDT sur le réseau Ethereum (ERC20 uniquement) à l\'adresse ci-dessous.','0x89D24A6b4CcB1B6fAA2625Fe562bDD9a23260359',0.00,'15 à 30 minutes après confirmation réseau',1,'2026-08-23 19:28:51','2026-08-23 19:28:51'),(8,'Litecoin (LTC)','crypto','Envoyez le montant exact en LTC à l\'adresse ci-dessous, puis soumettez votre demande.','ltc1qh6tf004ty7z7un2v5ntu4mkf630545gvhs45u7',0.00,'10 à 20 minutes après confirmation réseau',1,'2026-08-23 19:28:51','2026-08-23 19:28:51'),(9,'PayPal','e-wallet','Envoyez le montant en tant que \"Paiement entre amis/famille\" (Friends & Family) à l\'adresse ci-dessous. Indiquez votre email de compte Xendaro Fox en référence.','payments@xendarofox.com',0.00,'1 à 3 heures ouvrées',1,'2026-08-23 19:28:51','2026-08-23 19:28:51'),(10,'Perfect Money','e-wallet','Effectuez un transfert du montant exact vers le compte Perfect Money ci-dessous depuis votre propre compte Perfect Money.','U29384756',0.00,'1 à 3 heures ouvrées',1,'2026-08-23 19:28:51','2026-08-23 19:28:51');
/*!40000 ALTER TABLE `payment_methods` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `promotions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `promotions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `titre` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `date_debut` date DEFAULT NULL,
  `date_fin` date DEFAULT NULL,
  `est_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `promotions` WRITE;
/*!40000 ALTER TABLE `promotions` DISABLE KEYS */;
INSERT INTO `promotions` VALUES (1,'Bonus de bienvenue 50%','Bénéficiez d\'un bonus de 50% sur votre premier dépôt, jusqu\'à 500$, pour démarrer votre aventure de trading avec plus de capital.',NULL,'2026-08-13','2026-10-23',1,'2026-08-23 06:05:04','2026-08-23 06:05:04'),(2,'Trading sans commission - 30 jours','Profitez de 30 jours sans commission sur tous vos trades ECN dès l\'ouverture de votre compte VIP.',NULL,'2026-08-18','2026-09-23',1,'2026-08-23 06:05:04','2026-08-23 06:05:04'),(3,'Programme de parrainage renforcé','Parrainez un ami et recevez jusqu\'à 100$ de commission supplémentaire sur son volume de trading du premier mois.',NULL,'2026-08-03','2026-11-23',1,'2026-08-23 06:05:04','2026-08-23 06:05:04');
/*!40000 ALTER TABLE `promotions` ENABLE KEYS */;
UNLOCK TABLES;
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

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('BzC9qZDfAXDJw21IKux6KsPGzVMtJdW70lglB6yt',7,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Claude/1.34493.1 Chrome/148.0.7778.280 Electron/42.9.2 Safari/537.36 MSIX','YTo1OntzOjY6Il90b2tlbiI7czo0MDoicnBRRjl2Zzl6UWdMZFFyTXZyRndhUDNpQmdnVjN1dmJ1bHJpd3QxZyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjk6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9jb250YWN0IjtzOjU6InJvdXRlIjtzOjc6ImNvbnRhY3QiO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aTo3O3M6NToidHJhZGUiO2E6MTp7czoxMzoiaW5zdHJ1bWVudF9pZCI7aTo2O319',1787530484),('eY62oEe9iGmdfIUPmiEO8DIlWaPKBkOWKmGCiHEi',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:154.0) Gecko/20100101 Firefox/154.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiVktCSVJ2NEVjM2dDYmNGRkprc1JhSGFOcXBQMTdXUVRldXFnUDVzbyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9pbnNjcmlwdGlvbiI7czo1OiJyb3V0ZSI7czo4OiJyZWdpc3RlciI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1787531414),('Vg6uvyhPAjKcDO17261DS1QPhdJYkST5bPBjlNy3',NULL,'127.0.0.1','curl/8.18.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoieXI4bUQ2N2ZyT2x4c0RDTnFCZHM2T3FDdG5nYTNSWlY2elJBOTNBRyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7czo0OiJob21lIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1787525751);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `site_identifiers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `site_identifiers` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nom_plateforme` varchar(255) NOT NULL DEFAULT 'Xendaro Fox',
  `slogan` varchar(255) DEFAULT NULL,
  `langue_par_defaut` varchar(5) NOT NULL DEFAULT 'fr',
  `couleur_principale` varchar(7) NOT NULL DEFAULT '#F5A623',
  `couleur_secondaire` varchar(7) NOT NULL DEFAULT '#5B8CFF',
  `about_us` longtext DEFAULT NULL,
  `path_light_logo` varchar(255) DEFAULT NULL,
  `path_dark_logo` varchar(255) DEFAULT NULL,
  `path_favicon_png` varchar(255) DEFAULT NULL,
  `phone_contact_1` varchar(255) DEFAULT NULL,
  `phone_contact_2` varchar(255) DEFAULT NULL,
  `email_pro_1` varchar(255) DEFAULT NULL,
  `email_pro_2` varchar(255) DEFAULT NULL,
  `location_adresse` varchar(255) DEFAULT NULL,
  `cvg` longtext DEFAULT NULL,
  `policies` longtext DEFAULT NULL,
  `cookies` longtext DEFAULT NULL,
  `nos_services` longtext DEFAULT NULL,
  `contact` longtext DEFAULT NULL,
  `reseaux_sociaux` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`reseaux_sociaux`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `site_identifiers` WRITE;
/*!40000 ALTER TABLE `site_identifiers` DISABLE KEYS */;
INSERT INTO `site_identifiers` VALUES (1,'Xendaro Fox','Tradez le Forex, les Cryptos, l\'Or et plus encore, en toute confiance.','fr','#F5A623','#5B8CFF','Xendaro Fox est une plateforme de trading en ligne offrant un accès aux marchés Forex, Crypto, Or/Métaux, Matières premières, Indices et Actions, avec une expérience utilisateur moderne inspirée des meilleures plateformes du marché.',NULL,NULL,NULL,'+33 1 23 45 67 89',NULL,'contact@xendarofox.com',NULL,'Adresse à définir','Conditions Générales de Vente de Xendaro Fox — contenu à finaliser avec le client.','Politique de confidentialité de Xendaro Fox — contenu à finaliser avec le client.','Politique de cookies de Xendaro Fox — contenu à finaliser avec le client.','Découvrez nos types de comptes, nos plateformes de trading et nos conditions compétitives.','Notre équipe est disponible pour répondre à toutes vos questions.',NULL,'2026-08-23 05:48:25','2026-08-23 05:48:25');
/*!40000 ALTER TABLE `site_identifiers` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `ticket_messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ticket_messages` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `ticket_id` bigint(20) unsigned NOT NULL,
  `auteur_id` bigint(20) unsigned NOT NULL,
  `message` text NOT NULL,
  `est_admin` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ticket_messages_auteur_id_foreign` (`auteur_id`),
  KEY `ticket_messages_ticket_id_created_at_index` (`ticket_id`,`created_at`),
  CONSTRAINT `ticket_messages_auteur_id_foreign` FOREIGN KEY (`auteur_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `ticket_messages_ticket_id_foreign` FOREIGN KEY (`ticket_id`) REFERENCES `tickets` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `ticket_messages` WRITE;
/*!40000 ALTER TABLE `ticket_messages` DISABLE KEYS */;
INSERT INTO `ticket_messages` VALUES (1,1,7,'Bonjour, combien de temps prend le traitement d\'un retrait via virement bancaire ? Merci d\'avance.',0,'2026-08-23 22:49:42','2026-08-23 22:49:42'),(2,1,7,'Petite précision : je parle d\'un retrait de 150$ soumis aujourd\'hui.',0,'2026-08-23 22:54:08','2026-08-23 22:54:08');
/*!40000 ALTER TABLE `ticket_messages` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `tickets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tickets` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `sujet` varchar(255) NOT NULL,
  `statut` enum('ouvert','en_cours','ferme') NOT NULL DEFAULT 'ouvert',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tickets_user_id_statut_index` (`user_id`,`statut`),
  CONSTRAINT `tickets_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `tickets` WRITE;
/*!40000 ALTER TABLE `tickets` DISABLE KEYS */;
INSERT INTO `tickets` VALUES (1,7,'Question sur le retrait de fonds','ouvert','2026-08-23 22:49:42','2026-08-23 22:49:42');
/*!40000 ALTER TABLE `tickets` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `trade_histories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `trade_histories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `market_instrument_id` bigint(20) unsigned NOT NULL,
  `mode` enum('demo','reel') NOT NULL DEFAULT 'demo',
  `sens` enum('buy','sell') NOT NULL,
  `type_ordre` enum('marche','buy_limit','sell_limit','buy_stop','sell_stop') NOT NULL DEFAULT 'marche',
  `volume` decimal(10,2) NOT NULL,
  `prix_ouverture` decimal(18,5) NOT NULL,
  `prix_cloture` decimal(18,5) DEFAULT NULL,
  `stop_loss` decimal(18,5) DEFAULT NULL,
  `take_profit` decimal(18,5) DEFAULT NULL,
  `marge_utilisee` decimal(18,2) NOT NULL DEFAULT 0.00,
  `profit_perte` decimal(18,2) DEFAULT NULL,
  `statut` enum('ouvert','cloture') NOT NULL DEFAULT 'ouvert',
  `ouvert_le` timestamp NOT NULL DEFAULT current_timestamp(),
  `cloture_le` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `trade_histories_market_instrument_id_foreign` (`market_instrument_id`),
  KEY `trade_histories_user_id_statut_index` (`user_id`,`statut`),
  KEY `trade_histories_user_id_mode_statut_index` (`user_id`,`mode`,`statut`),
  CONSTRAINT `trade_histories_market_instrument_id_foreign` FOREIGN KEY (`market_instrument_id`) REFERENCES `market_instruments` (`id`) ON DELETE CASCADE,
  CONSTRAINT `trade_histories_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `trade_histories` WRITE;
/*!40000 ALTER TABLE `trade_histories` DISABLE KEYS */;
INSERT INTO `trade_histories` VALUES (1,7,1,'demo','buy','marche',0.10,1.08527,1.08463,1.08000,1.09500,0.02,0.00,'cloture','2026-08-23 23:03:37','2026-08-23 23:08:20','2026-08-23 23:03:37','2026-08-23 23:08:20'),(2,7,6,'demo','sell','marche',0.01,62002.41800,62029.94600,NULL,NULL,3100.12,-0.28,'cloture','2026-08-23 23:05:19','2026-08-23 23:07:46','2026-08-23 23:05:19','2026-08-23 23:07:46');
/*!40000 ALTER TABLE `trade_histories` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `avatar_path` varchar(255) DEFAULT NULL,
  `referral_code` varchar(12) DEFAULT NULL,
  `parrain_id` bigint(20) unsigned DEFAULT NULL,
  `otp_code` varchar(6) DEFAULT NULL,
  `otp_expires_at` timestamp NULL DEFAULT NULL,
  `two_factor_enabled` tinyint(1) NOT NULL DEFAULT 0,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `users_referral_code_unique` (`referral_code`),
  KEY `users_parrain_id_foreign` (`parrain_id`),
  CONSTRAINT `users_parrain_id_foreign` FOREIGN KEY (`parrain_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Xendaro Admin','marcosseko.travail@gmail.com',NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,'$2y$12$md8C7XDgcZnxg97obQp0zuwlIVYZPNwvndqhXPUDSFMmWFRskepDO',NULL,'2026-08-23 05:41:42','2026-08-23 05:41:42'),(2,'Test User','test@example.com',NULL,NULL,'RAR0XWXF',NULL,NULL,NULL,0,'2026-08-23 05:48:26','$2y$12$HAR3bGEN6S6jiETXch4jnutBGojWC0Y5IpFeOejffNRLq0gVrMpWC','htzIticYGO','2026-08-23 05:48:27','2026-08-23 19:36:42'),(7,'Sophie Lambert-Dupont','autofillion@gmail.com','+33698765432',NULL,'NRYNXI6V',NULL,NULL,NULL,1,'2026-08-23 22:05:18','$2y$12$YJBkNnBIRTfX7VTC2dZanODQwmdRRi1VaI520IUJjxlLZ0tycRjfS','TS4UkJHnIyGqT6CFaUaFYqc4r7S3adsuwB9H9K9e0gMbXahl6MEkvu5m0O0k','2026-08-23 22:04:35','2026-08-23 22:32:20');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `wallet_transactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wallet_transactions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `payment_method_id` bigint(20) unsigned DEFAULT NULL,
  `type` enum('depot','retrait') NOT NULL,
  `montant` decimal(18,2) NOT NULL,
  `statut` enum('en_attente','valide','refuse') NOT NULL DEFAULT 'en_attente',
  `reference` varchar(255) NOT NULL,
  `note_admin` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `wallet_transactions_reference_unique` (`reference`),
  KEY `wallet_transactions_payment_method_id_foreign` (`payment_method_id`),
  KEY `wallet_transactions_user_id_statut_index` (`user_id`,`statut`),
  CONSTRAINT `wallet_transactions_payment_method_id_foreign` FOREIGN KEY (`payment_method_id`) REFERENCES `payment_methods` (`id`) ON DELETE SET NULL,
  CONSTRAINT `wallet_transactions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `wallet_transactions` WRITE;
/*!40000 ALTER TABLE `wallet_transactions` DISABLE KEYS */;
INSERT INTO `wallet_transactions` VALUES (2,7,4,'depot',250.00,'valide','XF-VRWQXKEQMS',NULL,'2026-08-23 22:34:24','2026-08-23 22:35:00'),(3,7,2,'retrait',150.00,'valide','XF-IBZ9X0CN8R',NULL,'2026-08-23 22:46:27','2026-08-23 22:46:54');
/*!40000 ALTER TABLE `wallet_transactions` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `wallets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wallets` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `solde_reel` decimal(18,2) NOT NULL DEFAULT 0.00,
  `solde_demo` decimal(18,2) NOT NULL DEFAULT 10000.00,
  `devise` varchar(3) NOT NULL DEFAULT 'USD',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `wallets_user_id_unique` (`user_id`),
  CONSTRAINT `wallets_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `wallets` WRITE;
/*!40000 ALTER TABLE `wallets` DISABLE KEYS */;
INSERT INTO `wallets` VALUES (1,2,0.00,10000.00,'USD','2026-08-23 05:48:27','2026-08-23 19:42:21'),(5,7,200.00,15999.72,'USD','2026-08-23 22:04:35','2026-08-23 23:08:20');
/*!40000 ALTER TABLE `wallets` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

