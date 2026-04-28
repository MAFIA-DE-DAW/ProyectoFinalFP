-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: EcoGotchi
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
-- Current Database: `EcoGotchi`
--

/*!40000 DROP DATABASE IF EXISTS `EcoGotchi`*/;

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `ecogotchi` /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci */;

USE `EcoGotchi`;

--
-- Table structure for table `compras`
--

DROP TABLE IF EXISTS `compras`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `compras` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) NOT NULL,
  `id_accion` int(11) NOT NULL,
  `fecha` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `id_usuario` (`id_usuario`),
  KEY `id_accion` (`id_accion`),
  CONSTRAINT `compras_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`),
  CONSTRAINT `compras_ibfk_2` FOREIGN KEY (`id_accion`) REFERENCES `tienda_acciones` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `compras`
--

LOCK TABLES `compras` WRITE;
/*!40000 ALTER TABLE `compras` DISABLE KEYS */;
INSERT INTO `compras` VALUES (1,5,7,'2026-04-27 16:04:38'),(2,5,1,'2026-04-27 16:57:26'),(3,5,3,'2026-04-27 17:43:29'),(4,5,1,'2026-04-27 17:45:16'),(5,5,1,'2026-04-27 17:53:18');
/*!40000 ALTER TABLE `compras` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `entorno`
--

DROP TABLE IF EXISTS `entorno`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `entorno` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) DEFAULT NULL,
  `nivel_ecologico` int(11) DEFAULT 50,
  `estado_entorno` varchar(30) DEFAULT 'normal',
  `fecha_ultima_actualizacion` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `id_usuario` (`id_usuario`),
  CONSTRAINT `entorno_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `entorno`
--

LOCK TABLES `entorno` WRITE;
/*!40000 ALTER TABLE `entorno` DISABLE KEYS */;
INSERT INTO `entorno` VALUES (1,1,24,'normal','2026-03-18 11:20:23'),(2,3,50,'normal','2026-03-10 10:56:40'),(3,2,0,'normal','2026-03-18 11:21:19'),(4,4,100,'normal','2026-03-10 10:56:40'),(5,5,100,'normal','2026-04-27 16:26:00');
/*!40000 ALTER TABLE `entorno` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mascotas`
--

DROP TABLE IF EXISTS `mascotas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mascotas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) DEFAULT NULL,
  `nombre` varchar(50) DEFAULT NULL,
  `tipo` varchar(50) DEFAULT NULL,
  `color` varchar(30) DEFAULT NULL,
  `hambre` int(11) DEFAULT 80,
  `sueno` int(11) DEFAULT 80,
  `diversion` int(11) DEFAULT 80,
  `higiene` int(11) DEFAULT 80,
  `salud` int(11) DEFAULT 100,
  `fecha_ultima_actualizacion` datetime DEFAULT current_timestamp(),
  `basura` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `id_usuario` (`id_usuario`),
  CONSTRAINT `mascotas_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mascotas`
--

LOCK TABLES `mascotas` WRITE;
/*!40000 ALTER TABLE `mascotas` DISABLE KEYS */;
INSERT INTO `mascotas` VALUES (4,3,'Jorge','fantasia','azul',16,58,16,58,100,'2026-03-09 15:43:52',0),(9,4,'Kevin','fantasia','verde',60,82,60,80,100,'2026-03-10 09:59:38',0),(12,1,'Chema','planta','verde',100,100,100,100,100,'2026-03-18 11:19:59',0),(15,5,'Luisito','planta','verde',100,81,86,48,100,'2026-04-27 17:54:06',0);
/*!40000 ALTER TABLE `mascotas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `misiones`
--

DROP TABLE IF EXISTS `misiones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `misiones` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(100) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `recompensa` int(11) DEFAULT NULL,
  `puntos_monedas` int(11) NOT NULL DEFAULT 5,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `misiones`
--

LOCK TABLES `misiones` WRITE;
/*!40000 ALTER TABLE `misiones` DISABLE KEYS */;
INSERT INTO `misiones` VALUES (1,'Reciclar plástico','Hoy reciclaste una botella de plástico',10,10),(2,'Usar transporte público','Evita el coche hoy',15,20),(3,'Ahorrar agua','Reduce el tiempo en la ducha',10,10),(4,'Apagar luces','Apaga luces innecesarias',5,5),(5,'Reutilizar bolsa','Usa una bolsa reutilizable al hacer compras',10,10),(6,'Separar residuos','Separa papel, plástico y orgánico correctamente',10,15),(7,'Desconectar cargadores','Desconecta cargadores que no estés usando',5,5),(8,'Recoger basura','Recoge al menos un residuo que encuentres en la calle',15,20),(9,'Usar botella reutilizable','Evita comprar botellas de plástico hoy',10,10),(10,'Plantar una planta','Planta una semilla o cuida una planta',20,25),(11,'Comer local','Consume alimentos producidos localmente',10,10),(12,'Reducir carne','Evita comer carne hoy para reducir huella ecológica',15,15),(13,'Apagar dispositivos','Apaga dispositivos electrónicos que no uses',5,5),(14,'Ir caminando','Camina en lugar de usar transporte contaminante',15,15),(15,'Usar luz natural','Aprovecha la luz del sol en lugar de encender lámparas',5,5),(16,'Ducha corta','Dúchate en menos de 5 minutos',10,10),(17,'Reutilizar papel','Usa las dos caras del papel antes de tirarlo',5,5),(18,'Comprar a granel','Compra productos sin envases innecesarios',10,10),(19,'Regar plantas','Cuida las plantas o árboles cercanos',5,5),(20,'Compartir transporte','Comparte coche o transporte con alguien',15,15);
/*!40000 ALTER TABLE `misiones` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `misiones_completadas`
--

DROP TABLE IF EXISTS `misiones_completadas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `misiones_completadas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) DEFAULT NULL,
  `id_mision` int(11) DEFAULT NULL,
  `fecha` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `id_usuario` (`id_usuario`),
  KEY `id_mision` (`id_mision`),
  CONSTRAINT `misiones_completadas_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`),
  CONSTRAINT `misiones_completadas_ibfk_2` FOREIGN KEY (`id_mision`) REFERENCES `misiones` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `misiones_completadas`
--

LOCK TABLES `misiones_completadas` WRITE;
/*!40000 ALTER TABLE `misiones_completadas` DISABLE KEYS */;
INSERT INTO `misiones_completadas` VALUES (1,2,4,'2026-03-09 16:23:00'),(2,2,1,'2026-03-09 16:23:05'),(3,2,3,'2026-03-09 17:23:51'),(4,2,2,'2026-03-09 17:23:52'),(5,1,4,'2026-03-09 17:43:50'),(6,1,1,'2026-03-09 17:43:52'),(7,1,16,'2026-03-09 18:02:11'),(8,1,8,'2026-03-09 18:02:12'),(9,1,14,'2026-03-09 18:02:13'),(10,1,20,'2026-03-09 18:02:16'),(11,4,12,'2026-03-10 08:45:20'),(12,4,19,'2026-03-10 08:45:23'),(13,4,2,'2026-03-10 08:51:47'),(14,4,9,'2026-03-10 09:47:12'),(15,4,16,'2026-03-10 09:47:22'),(16,4,13,'2026-03-10 09:48:36'),(17,4,4,'2026-03-10 09:49:17'),(18,4,7,'2026-03-10 09:50:11'),(19,4,14,'2026-03-10 09:50:37'),(20,4,17,'2026-03-10 09:52:56'),(21,4,8,'2026-03-10 09:53:11'),(22,4,3,'2026-03-10 09:53:18'),(23,4,20,'2026-03-10 09:56:29'),(24,4,18,'2026-03-10 09:57:10'),(25,4,1,'2026-03-10 09:57:18'),(26,4,5,'2026-03-10 09:59:46'),(27,4,11,'2026-03-10 09:59:52'),(28,4,10,'2026-03-10 10:00:00'),(29,4,6,'2026-03-10 10:00:09'),(30,4,15,'2026-03-10 10:00:24'),(31,1,5,'2026-03-10 10:01:10'),(32,1,6,'2026-03-10 10:01:18'),(33,1,4,'2026-03-10 10:01:25'),(34,1,9,'2026-03-18 11:20:06'),(35,1,14,'2026-03-18 11:20:15'),(36,5,13,'2026-04-27 14:48:26'),(37,5,8,'2026-04-27 14:48:40'),(38,5,10,'2026-04-27 14:48:47'),(39,5,18,'2026-04-27 14:48:55'),(40,5,17,'2026-04-27 14:49:01'),(41,5,3,'2026-04-27 16:03:56'),(42,5,7,'2026-04-27 16:04:10'),(43,5,2,'2026-04-27 16:04:16'),(44,5,14,'2026-04-27 16:55:13'),(45,5,5,'2026-04-27 17:43:12'),(46,5,11,'2026-04-27 17:43:19'),(47,5,20,'2026-04-27 17:43:24'),(48,5,4,'2026-04-27 17:45:04'),(49,5,6,'2026-04-27 17:45:12'),(50,5,12,'2026-04-27 17:52:06'),(51,5,16,'2026-04-27 17:52:13');
/*!40000 ALTER TABLE `misiones_completadas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tienda_acciones`
--

DROP TABLE IF EXISTS `tienda_acciones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tienda_acciones` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `icono` varchar(10) DEFAULT '­ƒî▒',
  `coste` int(11) NOT NULL,
  `impacto_real` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tienda_acciones`
--

LOCK TABLES `tienda_acciones` WRITE;
/*!40000 ALTER TABLE `tienda_acciones` DISABLE KEYS */;
INSERT INTO `tienda_acciones` VALUES (1,'Punto de Reciclaje Barrio','Apoyas la instalación de un punto de reciclaje en tu vecindario para gestionar mejor los residuos.','🌳',25,'Reduce hasta 300 kg de basura mezclada al año.'),(2,'Plantar un Roble Centenario','Contribuyes a la reforestación plantando un árbol que absorberá CO2 durante décadas.','🌲',40,'Absorbe hasta 20kg de CO2 al año.'),(3,'Apoyar Bici-Escuela','Fomenta el transporte sostenible financiando clases de ciclismo urbano para jóvenes.','🚴',30,'Ahorra aprox. 150kg de CO2 por alumno al año.'),(7,'Reforestar zona quemada','Ayudas a recuperar bosque perdido por incendios.','🔥',70,'Por cada hectárea reforestada se fijan ~50 toneladas de CO2.'),(8,'Limpieza de Playas','Contribuyes a la recogida manual de pl??sticos y residuos en nuestras costas.','🌊',45,'Retira 15kg de pl??sticos del mar.'),(9,'Energ??a Solar Escolar','Instalaci??n de paneles fotovoltaicos en colegios p??blicos para reducir emisiones.','☀️',80,'Ahorra 1MWh de energ??a contaminante.'),(10,'Santuario de Abejas','Creaci??n de colmenas urbanas y siembra de flores silvestres para polinizadores.','🐝',35,'Ayuda a polinizar 5.000 flores nuevas.'),(11,'Reforestar el Amazonas','Siembro de ??rboles aut??ctonos en zonas protegidas de la selva amaz??nica.','🌴',100,'Captura 1 tonelada de CO2 en 10 a??os.'),(12,'Pozos de Agua Solar','Construcci??n de pozos con bombeo solar en zonas con escasez de agua.','💧',65,'Suministra agua potable a 5 familias.'),(13,'Huerto Comunitario','Creaci??n de espacios de cultivo urbano sostenible para vecinos del barrio.','🍅',40,'Produce 30kg de vegetales frescos locales.');
/*!40000 ALTER TABLE `tienda_acciones` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `fecha_registro` datetime DEFAULT current_timestamp(),
  `monedas_verdes` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuarios`
--

LOCK TABLES `usuarios` WRITE;
/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;
INSERT INTO `usuarios` VALUES (1,'Chema','chema.gesyfar@outlook.es','$2y$10$e64pBL4krR9lXP1w6ypMXO.McQmiGvp.89wNAhOdgJtAcaNdonfla','2026-03-09 08:55:28',0),(2,'Luisito','prueba1@test.com','$2y$10$/94T4Kman3NaiYsa4Kk8P.0usLWRdFVYhVLeJKZkmReeN/47lcHHS','2026-03-09 12:18:57',0),(3,'Kevin','kevin@gesyfar.com','$2y$10$k8ltisECobBTgns50I.fG.QBvWRF9LtDtM89YGCpurfHbSyGGChiu','2026-03-09 14:59:32',0),(4,'Marta','marta@test.com','$2y$10$Dl.45iAPQ69VTTHJNnf97.SI4Z8LPtpP.Xsshmb4M8nkm3.3CztJ6','2026-03-10 08:44:02',0),(5,'Plantica','test23@test.com','$2y$10$WjywbcQUsjHPD7J/NPAmouypi0wYFyuCMaOKo/C1MMDqD4wK1b/ze','2026-04-27 14:38:23',0);
/*!40000 ALTER TABLE `usuarios` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

