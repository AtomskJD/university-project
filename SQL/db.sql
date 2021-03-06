CREATE DATABASE  IF NOT EXISTS `mydb` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `mydb`;
-- MySQL dump 10.13  Distrib 5.5.16, for Win32 (x86)
--
-- Host: 127.0.0.1    Database: mydb
-- ------------------------------------------------------
-- Server version	5.5.23

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `reports_list`
--

DROP TABLE IF EXISTS `reports_list`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `reports_list` (
  `report_id` int(10) unsigned NOT NULL,
  `workshop_id` int(10) unsigned NOT NULL,
  `report_date` date DEFAULT NULL,
  PRIMARY KEY (`report_id`,`workshop_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reports_list`
--

LOCK TABLES `reports_list` WRITE;
/*!40000 ALTER TABLE `reports_list` DISABLE KEYS */;
INSERT INTO `reports_list` VALUES (1,1,'2012-06-20'),(1,2,'2012-06-17'),(1,3,'2012-06-19');
/*!40000 ALTER TABLE `reports_list` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `items`
--

DROP TABLE IF EXISTS `items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `items` (
  `item_id` int(10) unsigned NOT NULL,
  `item_name` varchar(45) CHARACTER SET utf8 DEFAULT NULL,
  `storage_id` int(10) unsigned NOT NULL,
  `unit_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`item_id`),
  UNIQUE KEY `item_id_UNIQUE` (`item_id`),
  KEY `fk_items_storages` (`storage_id`),
  KEY `fk_items_units` (`unit_id`),
  CONSTRAINT `fk_items_storages` FOREIGN KEY (`storage_id`) REFERENCES `storages` (`storage_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_items_units` FOREIGN KEY (`unit_id`) REFERENCES `units` (`unit_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `items`
--

LOCK TABLES `items` WRITE;
/*!40000 ALTER TABLE `items` DISABLE KEYS */;
INSERT INTO `items` VALUES (1,'Колбаса 69',1,1),(2,'Молоко 25',2,3),(3,'Тунец консерва',1,2),(4,'Колбаса добрая',1,1);
/*!40000 ALTER TABLE `items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `workshops`
--

DROP TABLE IF EXISTS `workshops`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `workshops` (
  `workshop_id` int(10) unsigned NOT NULL,
  `workshop_name` varchar(45) CHARACTER SET utf8 DEFAULT NULL,
  PRIMARY KEY (`workshop_id`),
  UNIQUE KEY `workshop_id_UNIQUE` (`workshop_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `workshops`
--

LOCK TABLES `workshops` WRITE;
/*!40000 ALTER TABLE `workshops` DISABLE KEYS */;
INSERT INTO `workshops` VALUES (1,'Колбасный цех'),(2,'Рыбный цех'),(3,'Молочный цех');
/*!40000 ALTER TABLE `workshops` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reports`
--

DROP TABLE IF EXISTS `reports`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `reports` (
  `report_id` int(10) unsigned NOT NULL,
  `workshop_id` int(10) unsigned NOT NULL,
  `item_id` int(10) unsigned NOT NULL,
  `report_quantity` int(10) NOT NULL,
  PRIMARY KEY (`report_id`,`workshop_id`,`item_id`),
  KEY `fk_reports_items_has_workshops` (`workshop_id`,`item_id`),
  KEY `fk_reports_reports_list` (`report_id`,`workshop_id`),
  CONSTRAINT `fk_reports_items_has_workshops` FOREIGN KEY (`workshop_id`, `item_id`) REFERENCES `items_has_workshops` (`workshops_workshop_id`, `items_item_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_reports_reports_list` FOREIGN KEY (`report_id`, `workshop_id`) REFERENCES `reports_list` (`report_id`, `workshop_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reports`
--

LOCK TABLES `reports` WRITE;
/*!40000 ALTER TABLE `reports` DISABLE KEYS */;
INSERT INTO `reports` VALUES (1,1,1,10),(1,1,4,10),(1,2,3,10),(1,3,2,30);
/*!40000 ALTER TABLE `reports` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `units`
--

DROP TABLE IF EXISTS `units`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `units` (
  `unit_id` int(10) unsigned NOT NULL,
  `unit_name` varchar(10) CHARACTER SET utf8 DEFAULT NULL,
  PRIMARY KEY (`unit_id`),
  UNIQUE KEY `unit_id_UNIQUE` (`unit_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `units`
--

LOCK TABLES `units` WRITE;
/*!40000 ALTER TABLE `units` DISABLE KEYS */;
INSERT INTO `units` VALUES (1,'шт'),(2,'кор'),(3,'литр');
/*!40000 ALTER TABLE `units` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `items_has_workshops`
--

DROP TABLE IF EXISTS `items_has_workshops`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `items_has_workshops` (
  `items_item_id` int(10) unsigned NOT NULL,
  `workshops_workshop_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`items_item_id`,`workshops_workshop_id`),
  KEY `fk_items_has_workshops_workshops1` (`workshops_workshop_id`),
  KEY `fk_items_has_workshops_items1` (`items_item_id`),
  CONSTRAINT `fk_items_has_workshops_items1` FOREIGN KEY (`items_item_id`) REFERENCES `items` (`item_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_items_has_workshops_workshops1` FOREIGN KEY (`workshops_workshop_id`) REFERENCES `workshops` (`workshop_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `items_has_workshops`
--

LOCK TABLES `items_has_workshops` WRITE;
/*!40000 ALTER TABLE `items_has_workshops` DISABLE KEYS */;
INSERT INTO `items_has_workshops` VALUES (1,1),(4,1),(3,2),(2,3);
/*!40000 ALTER TABLE `items_has_workshops` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `storages`
--

DROP TABLE IF EXISTS `storages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `storages` (
  `storage_id` int(10) unsigned NOT NULL,
  `storage_name` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`storage_id`),
  UNIQUE KEY `storage_id_UNIQUE` (`storage_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `storages`
--

LOCK TABLES `storages` WRITE;
/*!40000 ALTER TABLE `storages` DISABLE KEYS */;
INSERT INTO `storages` VALUES (1,'Склад 1'),(2,'Склад 2'),(3,'Склад 3');
/*!40000 ALTER TABLE `storages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `orders` (
  `workshop_id` int(10) unsigned NOT NULL,
  `item_id` int(10) unsigned NOT NULL,
  `order_quantity` int(11) NOT NULL,
  `output_date` date NOT NULL,
  PRIMARY KEY (`workshop_id`,`item_id`,`output_date`),
  KEY `fk_orders_items_hasworkshop` (`workshop_id`,`item_id`),
  CONSTRAINT `fk_orders_items_hasworkshop` FOREIGN KEY (`workshop_id`, `item_id`) REFERENCES `items_has_workshops` (`workshops_workshop_id`, `items_item_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` VALUES (1,1,20,'2012-06-20'),(3,2,10,'2012-06-20');
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2012-06-18  1:20:01
