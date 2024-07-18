CREATE DATABASE  IF NOT EXISTS `mercazon` /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci */;
USE `mercazon`;
-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: 127.0.0.1    Database: mercazon
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

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
-- Table structure for table `lojistas`
--

DROP TABLE IF EXISTS `lojistas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lojistas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(120) NOT NULL,
  `nome_estabelecimento` varchar(120) NOT NULL,
  `endereco` varchar(120) NOT NULL,
  `email` varchar(255) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `telefone` varchar(30) NOT NULL,
  `imagem_empresa` varchar(150) NOT NULL,
  `imagem_lojista` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lojistas`
--

LOCK TABLES `lojistas` WRITE;
/*!40000 ALTER TABLE `lojistas` DISABLE KEYS */;
INSERT INTO `lojistas` VALUES (1,'1','','1','1@gmail.com','$2y$10$rIvrJDJbTaZCFmhn.pGHfOZb78NjdtzSNlwgnYOgilVzV85WO.PHu','','',NULL),(2,'2','','2','2@gmail.com','$2y$10$0PE9QiLQPvqGQevqRtoS5eVEr18oc0CBlmx3ZwLAuYYsROeChgRVe','','',NULL);
/*!40000 ALTER TABLE `lojistas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `produtos`
--

DROP TABLE IF EXISTS `produtos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `produtos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(200) NOT NULL,
  `preco` decimal(9,2) NOT NULL,
  `categoria` varchar(50) NOT NULL,
  `imagem` varchar(150) NOT NULL,
  `marca` varchar(150) DEFAULT NULL,
  `genero` enum('M','F','O') DEFAULT NULL,
  `cor` varchar(50) DEFAULT NULL,
  `descricao` varchar(255) DEFAULT NULL,
  `contador_cliques` int(11) DEFAULT 0,
  `id_lojista` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_lojista` (`id_lojista`),
  CONSTRAINT `produtos_ibfk_1` FOREIGN KEY (`id_lojista`) REFERENCES `lojistas` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `produtos`
--

LOCK TABLES `produtos` WRITE;
/*!40000 ALTER TABLE `produtos` DISABLE KEYS */;
INSERT INTO `produtos` VALUES (1,'teste',22.00,'Roupas','f31944c78b076e32a7c77c8e3263e5ac.png',NULL,NULL,NULL,NULL,0,1),(2,'teste2',55.00,'Eletrodomesticos','8a94dd381428423cef8593b4648874bf.jpeg',NULL,NULL,NULL,NULL,0,1),(3,'teste3',22.00,'Roupas','42f721bcfe0865dd279bb69487c38e2b.png',NULL,NULL,NULL,NULL,0,1),(4,'python',99.00,'Eletronicos','ea6b0324425afc5a04308bba5160c359.jpeg',NULL,NULL,NULL,NULL,0,2),(5,'Headset Bluetooh',199.00,'Eletronicos','b97a34e28d564455eed81aa7a301c772.jpeg',NULL,NULL,NULL,NULL,0,2),(6,'Geladeira deluxe',999.00,'Eletrodomesticos','9b6f891a6b1348bada829e2ca794452e.jpeg',NULL,NULL,NULL,NULL,0,2),(7,'Camiseta Básica',89.00,'Roupas','7137db4e3242404b1b93e1d196d583b1.jpeg',NULL,NULL,NULL,NULL,0,2),(8,'Camiseta com frase',79.00,'Roupas','c05760f1769726a822d5e6dbfc915807.jpeg',NULL,NULL,NULL,NULL,0,2),(9,'Fone de ouvido bluetooh',89.00,'Eletronicos','d9cf4d1f893a0e7b17a83f7ef79c975a.webp',NULL,NULL,NULL,NULL,0,2);
/*!40000 ALTER TABLE `produtos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuario_favorita_produto`
--

DROP TABLE IF EXISTS `usuario_favorita_produto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usuario_favorita_produto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) NOT NULL,
  `id_produto` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_usuario` (`id_usuario`),
  KEY `id_produto` (`id_produto`),
  CONSTRAINT `usuario_favorita_produto_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`),
  CONSTRAINT `usuario_favorita_produto_ibfk_2` FOREIGN KEY (`id_produto`) REFERENCES `produtos` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=58 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario_favorita_produto`
--

LOCK TABLES `usuario_favorita_produto` WRITE;
/*!40000 ALTER TABLE `usuario_favorita_produto` DISABLE KEYS */;
INSERT INTO `usuario_favorita_produto` VALUES (26,1,3),(28,3,4),(31,3,5),(39,3,6),(40,3,8),(41,3,9),(42,3,7),(43,3,1),(44,4,1),(53,4,5),(56,4,3);
/*!40000 ALTER TABLE `usuario_favorita_produto` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(120) NOT NULL,
  `email` varchar(255) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `endereco` varchar(120) NOT NULL,
  `data_nascimento` date NOT NULL,
  `imagem_usuario` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuarios`
--

LOCK TABLES `usuarios` WRITE;
/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;
INSERT INTO `usuarios` VALUES (1,'1','1@gmail.com','$2y$10$D3J2wB32IzrkRmnq53YhieRTcsHWIS22UM68XLxur5rxL9FMe8GiS','1','5555-05-05',NULL),(3,'','','$2y$10$0rJoaiIT4jGh7Y6Tt6euheLYUBYMUS5oraLYrMiYj.M7BTS.wlAVW','','0000-00-00','d554ffd25feb6bb89123d26beb2b7221.png'),(4,'Joaquim','3@gmail.com','$2y$10$O6umH4WnE.1Nhwez1rJ/vuWmW4tfuLgfI.NF/4dB5cKW6z4TkPGDO','Av Exemplo 203','2024-07-23','93ba961ac50c2b58f8e0d39b9cbdb8cb.png');
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

-- Dump completed on 2024-07-15 17:13:24
