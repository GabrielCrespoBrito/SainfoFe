-- MySQL dump 10.13  Distrib 5.7.17, for Win64 (x86_64)
--
-- Host: localhost    Database: plantilla
-- ------------------------------------------------------
-- Server version	5.7.20-log

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
-- Table structure for table `actividad_clientes`
--

DROP TABLE IF EXISTS `actividad_clientes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `actividad_clientes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `PCCodi` varchar(11) NOT NULL,
  `Nombre` varchar(50) DEFAULT NULL,
  `Descripcion` varchar(255) DEFAULT NULL,
  `Fecha` datetime DEFAULT NULL,
  `Model_id` varchar(11) DEFAULT NULL,
  `Model_name` varchar(40) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=78 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;



--
-- Dumping data for table `actividad_clientes`
--

LOCK TABLES `actividad_clientes` WRITE;
/*!40000 ALTER TABLE `actividad_clientes` DISABLE KEYS */;
/*!40000 ALTER TABLE `actividad_clientes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `almacen`
--

DROP TABLE IF EXISTS `almacen`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `almacen` (
  `Almcodi` varchar(1) NOT NULL DEFAULT '',
  `AlmNomb` varchar(20) DEFAULT NULL,
  `AlmDire` varchar(50) DEFAULT NULL,
  `AlmDist` varchar(30) DEFAULT NULL,
  `SerGuiaSal` char(3) DEFAULT NULL,
  `NumGuiaSal` char(10) DEFAULT NULL,
  `SerGuiaSal2` char(3) DEFAULT NULL,
  `NumGuiaSal2` char(10) DEFAULT NULL,
  PRIMARY KEY (`Almcodi`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `almacen`
--

LOCK TABLES `almacen` WRITE;
/*!40000 ALTER TABLE `almacen` DISABLE KEYS */;
INSERT INTO `almacen` VALUES ('0','ALMACÉN PRINCIPAL','','','001','000000','100','8'),('1','ALM.VMT','','','002','000000','0','0');
/*!40000 ALTER TABLE `almacen` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `anio`
--

DROP TABLE IF EXISTS `anio`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `anio` (
  `empcodi` char(3) NOT NULL,
  `Pan_cAnio` char(4) NOT NULL,
  `Pan_cEstado` varchar(1) DEFAULT NULL,
  `Pan_cUserCrea` varchar(45) DEFAULT NULL,
  `Pan_dFechaCrea` datetime DEFAULT NULL,
  `Pan_cUserModifica` varchar(45) DEFAULT NULL,
  `Pan_dFechaModifica` datetime DEFAULT NULL,
  `Pan_cEquipoUser` varchar(45) DEFAULT NULL,
  `Per_cPeriodo` varchar(2) DEFAULT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`empcodi`,`Pan_cAnio`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `anio`
--

LOCK TABLES `anio` WRITE;
/*!40000 ALTER TABLE `anio` DISABLE KEYS */;
/*!40000 ALTER TABLE `anio` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bancos`
--

DROP TABLE IF EXISTS `bancos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bancos` (
  `bancodi` varchar(2) NOT NULL,
  `bannomb` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`bancodi`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bancos`
--

LOCK TABLES `bancos` WRITE;
/*!40000 ALTER TABLE `bancos` DISABLE KEYS */;
/*!40000 ALTER TABLE `bancos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bancos_cuenta_cte`
--

DROP TABLE IF EXISTS `bancos_cuenta_cte`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bancos_cuenta_cte` (
  `BanCodi` char(2) NOT NULL,
  `CueCodi` char(4) NOT NULL,
  `CueNume` varchar(145) DEFAULT NULL,
  `CueSald` float DEFAULT NULL,
  `CueImSd` float DEFAULT NULL,
  `CueImSC` float DEFAULT NULL,
  `MonCodi` char(2) DEFAULT NULL,
  `CueSect` varchar(45) DEFAULT NULL,
  `CueObse` varchar(95) DEFAULT NULL,
  `User_Crea` varchar(45) DEFAULT NULL,
  `User_FCrea` datetime DEFAULT NULL,
  `User_ECrea` varchar(45) DEFAULT NULL,
  `User_Modi` varchar(45) DEFAULT NULL,
  `User_FModi` datetime DEFAULT NULL,
  `User_EModi` varchar(45) DEFAULT NULL,
  `UDelete` char(1) DEFAULT NULL,
  `CueCorrE` char(10) DEFAULT NULL,
  `CueCorrD` char(10) DEFAULT NULL,
  `test` int(11) DEFAULT '1',
  `EmpCodi` char(4) NOT NULL DEFAULT '001',
  `Detract` varchar(255) NOT NULL DEFAULT '0',
  PRIMARY KEY (`CueCodi`,`BanCodi`,`EmpCodi`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bancos_cuenta_cte`
--

LOCK TABLES `bancos_cuenta_cte` WRITE;
/*!40000 ALTER TABLE `bancos_cuenta_cte` DISABLE KEYS */;
/*!40000 ALTER TABLE `bancos_cuenta_cte` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bancos_movimientos`
--

DROP TABLE IF EXISTS `bancos_movimientos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bancos_movimientos` (
  `MovCodi` char(6) NOT NULL,
  `CueCodi` char(4) DEFAULT NULL,
  `MovNume` varchar(45) DEFAULT NULL,
  `MovFOper` date DEFAULT NULL,
  `MovFech` date DEFAULT NULL,
  `MovFecV` date DEFAULT NULL,
  `TimCodi` char(2) DEFAULT NULL,
  `MovDebe` float DEFAULT NULL,
  `MovHaber` float DEFAULT NULL,
  `MovSald` float DEFAULT NULL,
  `MovConc` varchar(105) DEFAULT NULL,
  `MovEsta` char(1) DEFAULT NULL,
  `User_Crea` varchar(45) DEFAULT NULL,
  `User_FCrea` datetime DEFAULT NULL,
  `User_ECrea` varchar(45) DEFAULT NULL,
  `User_Modi` varchar(45) DEFAULT NULL,
  `User_FModi` datetime DEFAULT NULL,
  `User_EModi` varchar(45) DEFAULT NULL,
  `UDelete` char(1) DEFAULT NULL,
  `CheOper` char(10) DEFAULT NULL,
  PRIMARY KEY (`MovCodi`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bancos_movimientos`
--

LOCK TABLES `bancos_movimientos` WRITE;
/*!40000 ALTER TABLE `bancos_movimientos` DISABLE KEYS */;
/*!40000 ALTER TABLE `bancos_movimientos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Temporary view structure for view `c_prodvendidofecha`
--

DROP TABLE IF EXISTS `c_prodvendidofecha`;
/*!50001 DROP VIEW IF EXISTS `c_prodvendidofecha`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `c_prodvendidofecha` AS SELECT 
 1 AS `id`,
 1 AS `UnpCodi`,
 1 AS `ProCodi`,
 1 AS `ProNomb`,
 1 AS `SumaDeDetCant`,
 1 AS `VtaEsta`,
 1 AS `MesCodi`,
 1 AS `mesnomb`,
 1 AS `VtaFVta`,
 1 AS `LocCodi`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `c_proven`
--

DROP TABLE IF EXISTS `c_proven`;
/*!50001 DROP VIEW IF EXISTS `c_proven`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `c_proven` AS SELECT 
 1 AS `DetCodi`,
 1 AS `unpcodi`,
 1 AS `DetNomb`,
 1 AS `MarNomb`,
 1 AS `CANT`,
 1 AS `VtaFvta`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `c_salidas_prod`
--

DROP TABLE IF EXISTS `c_salidas_prod`;
/*!50001 DROP VIEW IF EXISTS `c_salidas_prod`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `c_salidas_prod` AS SELECT 
 1 AS `LocCodi`,
 1 AS `TmoCodi`,
 1 AS `PCCodi`,
 1 AS `id`,
 1 AS `ProCodi`,
 1 AS `UnpCodi`,
 1 AS `ProNomb`,
 1 AS `detcant`,
 1 AS `Cant`,
 1 AS `UniEnte`,
 1 AS `UniMedi`,
 1 AS `guifemi`,
 1 AS `EntSal`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `c_utilidad_detalle`
--

DROP TABLE IF EXISTS `c_utilidad_detalle`;
/*!50001 DROP VIEW IF EXISTS `c_utilidad_detalle`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `c_utilidad_detalle` AS SELECT 
 1 AS `vtafvta`,
 1 AS `vtanume`,
 1 AS `venta`,
 1 AS `costo`,
 1 AS `Utilidad`,
 1 AS `Pccodi`,
 1 AS `pcrucc`,
 1 AS `pcnomb`,
 1 AS `VtaEsta`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `c_utilidad_resumen`
--

DROP TABLE IF EXISTS `c_utilidad_resumen`;
/*!50001 DROP VIEW IF EXISTS `c_utilidad_resumen`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `c_utilidad_resumen` AS SELECT 
 1 AS `vtafvta`,
 1 AS `venta`,
 1 AS `costo`,
 1 AS `Utilidad`,
 1 AS `VtaEsta`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `c_ventas_estadistica`
--

DROP TABLE IF EXISTS `c_ventas_estadistica`;
/*!50001 DROP VIEW IF EXISTS `c_ventas_estadistica`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `c_ventas_estadistica` AS SELECT 
 1 AS `Importe`,
 1 AS `mescodi`,
 1 AS `MesNomb`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `c_ventas_mejor_cliente`
--

DROP TABLE IF EXISTS `c_ventas_mejor_cliente`;
/*!50001 DROP VIEW IF EXISTS `c_ventas_mejor_cliente`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `c_ventas_mejor_cliente` AS SELECT 
 1 AS `pccodi`,
 1 AS `pcrucc`,
 1 AS `pcnomb`,
 1 AS `Vta`,
 1 AS `vtafvta`,
 1 AS `MesCodi`,
 1 AS `EmpCodi`,
 1 AS `TipCodi`*/;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `caja`
--

DROP TABLE IF EXISTS `caja`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `caja` (
  `CajNume` varchar(10) NOT NULL DEFAULT '',
  `CueCodi` char(4) DEFAULT NULL,
  `CajFech` date DEFAULT NULL,
  `CajSalS` float DEFAULT NULL,
  `CajSalD` float DEFAULT NULL,
  `CajEsta` varchar(2) DEFAULT NULL,
  `UsuCodi` varchar(3) NOT NULL,
  `CajFecC` date DEFAULT NULL,
  `CajHora` time DEFAULT NULL,
  `LocCodi` char(3) NOT NULL,
  `EmpCodi` char(3) NOT NULL,
  `PanAno` char(4) DEFAULT NULL,
  `PanPeri` char(2) DEFAULT NULL,
  `MesCodi` char(6) DEFAULT NULL,
  `User_Crea` varchar(45) DEFAULT NULL,
  `User_FCrea` datetime DEFAULT NULL,
  `User_ECrea` varchar(45) DEFAULT NULL,
  `User_Modi` varchar(45) DEFAULT NULL,
  `User_FModi` datetime DEFAULT NULL,
  `User_EModi` varchar(45) DEFAULT NULL,
  `UDelete` char(1) DEFAULT NULL,
  PRIMARY KEY (`CajNume`,`LocCodi`,`EmpCodi`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `caja`
--

LOCK TABLES `caja` WRITE;
/*!40000 ALTER TABLE `caja` DISABLE KEYS */;
/*!40000 ALTER TABLE `caja` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `caja_anticipos`
--

DROP TABLE IF EXISTS `caja_anticipos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `caja_anticipos` (
  `AntNume` char(10) NOT NULL,
  `AntFech` date DEFAULT NULL,
  `AntRefe` varchar(45) DEFAULT NULL,
  `MonCodi` char(2) DEFAULT NULL,
  `AntImpo` float DEFAULT NULL,
  `AntDcto` float DEFAULT NULL,
  `AntSald` float DEFAULT NULL,
  `TpgCodi` char(2) DEFAULT NULL,
  `AntObse` varchar(155) DEFAULT NULL,
  `MesCodi` char(7) DEFAULT NULL,
  `PcCodi` char(5) DEFAULT NULL,
  `UsuCodi` char(2) DEFAULT NULL,
  PRIMARY KEY (`AntNume`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `caja_anticipos`
--

LOCK TABLES `caja_anticipos` WRITE;
/*!40000 ALTER TABLE `caja_anticipos` DISABLE KEYS */;
/*!40000 ALTER TABLE `caja_anticipos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `caja_anticipos_detalle`
--

DROP TABLE IF EXISTS `caja_anticipos_detalle`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `caja_anticipos_detalle` (
  `DetOper` char(10) NOT NULL,
  `AntNume` char(10) DEFAULT NULL,
  `DetIngr` float DEFAULT NULL,
  `DetEgre` float DEFAULT NULL,
  `DetSald` float DEFAULT NULL,
  PRIMARY KEY (`DetOper`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `caja_anticipos_detalle`
--

LOCK TABLES `caja_anticipos_detalle` WRITE;
/*!40000 ALTER TABLE `caja_anticipos_detalle` DISABLE KEYS */;
/*!40000 ALTER TABLE `caja_anticipos_detalle` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `caja_depositos`
--

DROP TABLE IF EXISTS `caja_depositos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `caja_depositos` (
  `DepNume` char(10) NOT NULL,
  `DepFech` date DEFAULT NULL,
  `DepRefe` varchar(45) DEFAULT NULL,
  `MonCodi` char(2) DEFAULT NULL,
  `DepImpo` float DEFAULT NULL,
  `DepDcto` float DEFAULT NULL,
  `DepSald` float DEFAULT NULL,
  `TpgCodi` char(2) DEFAULT NULL,
  `DepObse` varchar(155) DEFAULT NULL,
  `MesCodi` char(7) DEFAULT NULL,
  `PcCodi` char(5) DEFAULT NULL,
  `UsuCodi` char(2) DEFAULT NULL,
  `BanCodi` char(2) DEFAULT NULL,
  `DepFoto` longblob,
  PRIMARY KEY (`DepNume`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `caja_depositos`
--

LOCK TABLES `caja_depositos` WRITE;
/*!40000 ALTER TABLE `caja_depositos` DISABLE KEYS */;
/*!40000 ALTER TABLE `caja_depositos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `caja_detalle`
--

DROP TABLE IF EXISTS `caja_detalle`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `caja_detalle` (
  `Id` varchar(8) NOT NULL DEFAULT '',
  `CueCodi` char(4) NOT NULL,
  `MocNume` varchar(13) DEFAULT NULL,
  `DocNume` varchar(12) DEFAULT NULL,
  `CajNume` varchar(10) DEFAULT NULL,
  `MocFech` date DEFAULT NULL,
  `MocFecV` date DEFAULT NULL,
  `TIPMOV` varchar(10) DEFAULT NULL,
  `MocNomb` varchar(100) DEFAULT NULL,
  `CtoCodi` varchar(3) DEFAULT NULL,
  `MonCodi` varchar(2) DEFAULT NULL,
  `CtaImpo` float DEFAULT NULL,
  `CtaDias` float DEFAULT NULL,
  `CANINGS` float DEFAULT NULL,
  `CANEGRS` float DEFAULT NULL,
  `SALSOLE` float DEFAULT NULL,
  `CtaSald` float DEFAULT NULL,
  `CANINGD` float DEFAULT NULL,
  `CANEGRD` float DEFAULT NULL,
  `SALDOLA` float DEFAULT NULL,
  `TIPCAMB` float DEFAULT NULL,
  `FECANUl` date DEFAULT NULL,
  `ANULADO` varchar(1) DEFAULT NULL,
  `CANMOV` double DEFAULT NULL,
  `MOTIVO` varchar(100) DEFAULT NULL,
  `AUTORIZA` varchar(30) DEFAULT NULL,
  `OTRODOC` varchar(15) DEFAULT NULL,
  `LocCodi` varchar(3) DEFAULT NULL,
  `UsuCodi` char(2) DEFAULT NULL,
  `EgrIng` char(3) DEFAULT NULL,
  `PCCodi` char(5) DEFAULT NULL,
  `TDocCodi` char(1) DEFAULT NULL,
  `User_Crea` varchar(45) DEFAULT NULL,
  `User_FCrea` datetime DEFAULT NULL,
  `User_ECrea` varchar(45) DEFAULT NULL,
  `User_Modi` varchar(45) DEFAULT NULL,
  `User_FModi` datetime DEFAULT NULL,
  `User_EModi` varchar(45) DEFAULT NULL,
  `UDelete` char(1) DEFAULT NULL,
  `CheOper` char(6) DEFAULT NULL,
  `empcodi` varchar(3) NOT NULL DEFAULT '001',
  PRIMARY KEY (`Id`,`CueCodi`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `caja_detalle`
--

LOCK TABLES `caja_detalle` WRITE;
/*!40000 ALTER TABLE `caja_detalle` DISABLE KEYS */;
/*!40000 ALTER TABLE `caja_detalle` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `caja_distribucion`
--

DROP TABLE IF EXISTS `caja_distribucion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `caja_distribucion` (
  `CajNume` char(10) NOT NULL,
  `CajFech` date DEFAULT NULL,
  `CajDateC` varchar(30) DEFAULT NULL,
  `CajDateM` varchar(30) DEFAULT NULL,
  `CajSalS` float DEFAULT NULL,
  `CajSalD` float DEFAULT NULL,
  `CajTC` float DEFAULT NULL,
  `CajDCen` float DEFAULT NULL,
  `CajVCen` float DEFAULT NULL,
  `CajCcen` float DEFAULT NULL,
  `CajUSol` float DEFAULT NULL,
  `CajDSol` float DEFAULT NULL,
  `CajCSol` float DEFAULT NULL,
  `CajDzSol` float DEFAULT NULL,
  `CajVeSol` float DEFAULT NULL,
  `CajCiSol` float DEFAULT NULL,
  `CajCnSol` float DEFAULT NULL,
  `CajDSSol` float DEFAULT NULL,
  `CajSumaS` float DEFAULT NULL,
  `CajDifeS` float DEFAULT NULL,
  `CajUDol` float DEFAULT NULL,
  `CajCDol` float DEFAULT NULL,
  `CajDDol` float DEFAULT NULL,
  `CajVDol` float DEFAULT NULL,
  `CajCiDol` float DEFAULT NULL,
  `CajCnDol` float DEFAULT NULL,
  `CajSumaD` float DEFAULT NULL,
  `CajDifeD` float DEFAULT NULL,
  PRIMARY KEY (`CajNume`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `caja_distribucion`
--

LOCK TABLES `caja_distribucion` WRITE;
/*!40000 ALTER TABLE `caja_distribucion` DISABLE KEYS */;
/*!40000 ALTER TABLE `caja_distribucion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `caja_historial`
--

DROP TABLE IF EXISTS `caja_historial`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `caja_historial` (
  `CajNume` char(10) NOT NULL,
  `VtaFactS` decimal(10,2) DEFAULT NULL,
  `VtaFactD` decimal(10,2) DEFAULT NULL,
  `VtaBoleS` decimal(10,2) DEFAULT NULL,
  `VtaBoleD` decimal(10,2) DEFAULT NULL,
  `VtaNotaS` decimal(10,2) DEFAULT NULL,
  `VtaNotaD` decimal(10,2) DEFAULT NULL,
  `VtaNCreS` decimal(10,2) DEFAULT NULL,
  `VtaNCreD` decimal(10,2) DEFAULT NULL,
  `CpaFactS` decimal(10,2) DEFAULT NULL,
  `CpaFactD` decimal(10,2) DEFAULT NULL,
  `CpaBoleS` decimal(10,2) DEFAULT NULL,
  `CpaBoleD` decimal(10,2) DEFAULT NULL,
  `CpaNotaS` decimal(10,2) DEFAULT NULL,
  `CpaNotaD` decimal(10,2) DEFAULT NULL,
  `CpaNcreS` decimal(10,2) DEFAULT NULL,
  `CpaNCreD` decimal(10,2) DEFAULT NULL,
  `VpagCreS` decimal(10,2) DEFAULT NULL,
  `VpagCreD` decimal(10,2) DEFAULT NULL,
  `VpagCheS` decimal(10,2) DEFAULT NULL,
  `VpagCheD` decimal(10,2) DEFAULT NULL,
  `VpagLetS` decimal(10,2) DEFAULT NULL,
  `VpagLetD` decimal(10,2) DEFAULT NULL,
  `VpagDepS` decimal(10,2) DEFAULT NULL,
  `VpagDepD` decimal(10,2) DEFAULT NULL,
  `VpagTarS` decimal(10,2) DEFAULT NULL,
  `VpagTarD` decimal(10,2) DEFAULT NULL,
  `VpagNCrS` decimal(10,2) DEFAULT NULL,
  `VpagNCrD` decimal(10,2) DEFAULT NULL,
  `VpagAntS` decimal(10,2) DEFAULT NULL,
  `VpagAntD` decimal(10,2) DEFAULT NULL,
  `CpagCreS` decimal(10,2) DEFAULT NULL,
  `CpagCreD` decimal(10,2) DEFAULT NULL,
  `CpagCheS` decimal(10,2) DEFAULT NULL,
  `CpagCheD` decimal(10,2) DEFAULT NULL,
  `CpagLetS` decimal(10,2) DEFAULT NULL,
  `CpagletD` decimal(10,2) DEFAULT NULL,
  `CpagDepS` decimal(10,2) DEFAULT NULL,
  `CpagDepD` decimal(10,2) DEFAULT NULL,
  `CpagTarS` decimal(10,2) DEFAULT NULL,
  `CpagTarD` decimal(10,2) DEFAULT NULL,
  `CpagNCrS` decimal(10,2) DEFAULT NULL,
  `CpagNCrD` decimal(10,2) DEFAULT NULL,
  `CajIniS` decimal(10,2) DEFAULT NULL,
  `CajIniD` decimal(10,2) DEFAULT NULL,
  `VtaEfeS` decimal(10,2) DEFAULT NULL,
  `VtaEfeD` decimal(10,2) DEFAULT NULL,
  `CobOtrS` decimal(10,2) DEFAULT NULL,
  `CobotrD` decimal(10,2) DEFAULT NULL,
  `OtroIngS` decimal(10,2) DEFAULT NULL,
  `OtroIngd` decimal(10,2) DEFAULT NULL,
  `TCIngS` decimal(10,2) DEFAULT NULL,
  `TCIngD` decimal(10,2) DEFAULT NULL,
  `CpaEfeS` decimal(10,2) DEFAULT NULL,
  `CpaEfeD` decimal(10,2) DEFAULT NULL,
  `PagotrS` decimal(10,2) DEFAULT NULL,
  `PagotrD` decimal(10,2) DEFAULT NULL,
  `TraEgrS` decimal(10,2) DEFAULT NULL,
  `TraEgrD` decimal(10,2) DEFAULT NULL,
  `OtroEgrS` decimal(10,2) DEFAULT NULL,
  `OtroEgrD` decimal(10,2) DEFAULT NULL,
  `TCEgrS` decimal(10,2) DEFAULT NULL,
  `TCEgrD` decimal(10,2) DEFAULT NULL,
  `FacNume` varchar(45) DEFAULT NULL,
  `BolNume` varchar(45) DEFAULT NULL,
  `NotNume` varchar(45) DEFAULT NULL,
  `NCRNume` varchar(45) DEFAULT NULL,
  `FCanNume` char(3) DEFAULT NULL,
  `BCanNume` char(3) DEFAULT NULL,
  `NCanNume` char(3) DEFAULT NULL,
  `NCCanNume` char(3) DEFAULT NULL,
  PRIMARY KEY (`CajNume`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `caja_historial`
--

LOCK TABLES `caja_historial` WRITE;
/*!40000 ALTER TABLE `caja_historial` DISABLE KEYS */;
/*!40000 ALTER TABLE `caja_historial` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `centro_costo`
--

DROP TABLE IF EXISTS `centro_costo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `centro_costo` (
  `CCoCodi` char(3) NOT NULL,
  `CCoNomb` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`CCoCodi`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `centro_costo`
--

LOCK TABLES `centro_costo` WRITE;
/*!40000 ALTER TABLE `centro_costo` DISABLE KEYS */;
INSERT INTO `centro_costo` VALUES ('000','SIN/DEFINIR');
/*!40000 ALTER TABLE `centro_costo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cheques_estado`
--

DROP TABLE IF EXISTS `cheques_estado`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cheques_estado` (
  `ChECodi` char(2) NOT NULL,
  `ChENomb` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`ChECodi`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cheques_estado`
--

LOCK TABLES `cheques_estado` WRITE;
/*!40000 ALTER TABLE `cheques_estado` DISABLE KEYS */;
INSERT INTO `cheques_estado` VALUES ('00','Todos los Estados'),('01','CARTERA'),('03','DEPOSITADO'),('04','ACREDITADO EN CAJA'),('05','RECHAZADO'),('06','ENDOZADOS'),('07','ACREDITADO EN BANCO'),('08','ENVIADO EN SUCURSAL');
/*!40000 ALTER TABLE `cheques_estado` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cheques_propio`
--

DROP TABLE IF EXISTS `cheques_propio`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cheques_propio` (
  `CheOper` varchar(10) NOT NULL DEFAULT '',
  `VtaOper` char(10) DEFAULT NULL,
  `TpgCodi` char(2) DEFAULT NULL,
  `CheFech` date DEFAULT NULL,
  `CheTCam` float DEFAULT NULL,
  `MonCodi` char(2) DEFAULT NULL,
  `CheImpo` float DEFAULT NULL,
  `ChePago` float DEFAULT NULL,
  `BanCodi` char(2) DEFAULT NULL,
  `CueCodi` char(4) DEFAULT NULL,
  `CheNume` varchar(12) DEFAULT NULL,
  `CheFDoc` date DEFAULT NULL,
  `CheFVen` date DEFAULT NULL,
  `PagBoch` char(30) DEFAULT NULL,
  `usufech` date DEFAULT NULL,
  `usuhora` char(20) DEFAULT NULL,
  `usucodi` char(2) DEFAULT NULL,
  `CajNume` varchar(10) DEFAULT NULL,
  `CheEsta` char(10) DEFAULT NULL,
  `ChECodi` char(2) DEFAULT NULL,
  `Tippcodi` char(1) DEFAULT NULL,
  `PCCodi` char(5) DEFAULT NULL,
  `PCNomb` varchar(145) DEFAULT NULL,
  `CheSald` float DEFAULT NULL,
  `CheTipo` int(1) DEFAULT NULL,
  `User_Crea` varchar(45) DEFAULT NULL,
  `User_FCrea` datetime DEFAULT NULL,
  `User_ECrea` varchar(45) DEFAULT NULL,
  `User_Modi` varchar(45) DEFAULT NULL,
  `User_FModi` datetime DEFAULT NULL,
  `User_EModi` varchar(45) DEFAULT NULL,
  `UDelete` char(1) DEFAULT NULL,
  `CheObse` varchar(245) DEFAULT NULL,
  `CheENume` varchar(15) DEFAULT NULL,
  `CheEFecha` date DEFAULT NULL,
  `CheENomb` varchar(145) DEFAULT NULL,
  `CheEDoc` varchar(45) DEFAULT NULL,
  `CheTPE` char(1) DEFAULT NULL,
  PRIMARY KEY (`CheOper`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cheques_propio`
--

LOCK TABLES `cheques_propio` WRITE;
/*!40000 ALTER TABLE `cheques_propio` DISABLE KEYS */;
/*!40000 ALTER TABLE `cheques_propio` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `compra_nc`
--

DROP TABLE IF EXISTS `compra_nc`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `compra_nc` (
  `NCCodi` char(6) NOT NULL,
  `NCFech` date DEFAULT NULL,
  `CpaRefe` char(15) DEFAULT NULL,
  `CpaOper` char(10) DEFAULT NULL,
  `CpaNume` char(15) DEFAULT NULL,
  `MonCodi` char(2) DEFAULT NULL,
  `CpaImpo` float DEFAULT NULL,
  PRIMARY KEY (`NCCodi`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `compra_nc`
--

LOCK TABLES `compra_nc` WRITE;
/*!40000 ALTER TABLE `compra_nc` DISABLE KEYS */;
/*!40000 ALTER TABLE `compra_nc` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `compras_cab`
--

DROP TABLE IF EXISTS `compras_cab`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `compras_cab` (
  `CpaOper` char(10) NOT NULL,
  `EmpCodi` char(3) DEFAULT NULL,
  `PanAno` char(4) DEFAULT NULL,
  `PanPeri` char(2) DEFAULT NULL,
  `CpaSerie` char(4) DEFAULT NULL,
  `CpaNumee` varchar(10) DEFAULT NULL,
  `CpaNume` varchar(12) DEFAULT NULL,
  `CpaFCpa` date DEFAULT NULL,
  `CpaFCon` date DEFAULT NULL,
  `CpaFPag` date DEFAULT NULL,
  `CpaFven` date DEFAULT NULL,
  `PCcodi` char(5) DEFAULT NULL,
  `TidCodi` char(2) DEFAULT NULL,
  `concodi` char(2) DEFAULT NULL,
  `zoncodi` char(4) DEFAULT NULL,
  `moncodi` char(2) DEFAULT NULL,
  `vencodi` char(4) DEFAULT NULL,
  `Docrefe` char(20) DEFAULT NULL,
  `GuiOper` char(10) DEFAULT NULL,
  `Cpaobse` varchar(145) DEFAULT NULL,
  `CpaTCam` float DEFAULT NULL,
  `CpaSdCa` float DEFAULT NULL,
  `CpaTPes` float DEFAULT NULL,
  `Cpabase` float DEFAULT NULL,
  `CpaIGVV` float DEFAULT NULL,
  `CpaImpo` float DEFAULT NULL,
  `CpaEsta` char(1) DEFAULT NULL,
  `usuCodi` char(2) DEFAULT NULL,
  `MesCodi` char(6) DEFAULT NULL,
  `LocCodi` char(3) DEFAULT NULL,
  `CpaPago` float DEFAULT NULL,
  `CpaSald` float DEFAULT NULL,
  `CpaEsPe` char(2) DEFAULT NULL,
  `CpaPPer` float DEFAULT NULL,
  `CpaAPer` float DEFAULT NULL,
  `CpaPerc` float DEFAULT NULL,
  `Cpatota` float DEFAULT NULL,
  `TipCodi` char(6) DEFAULT NULL,
  `AlmEsta` char(2) DEFAULT NULL,
  `CajNume` varchar(10) DEFAULT NULL,
  `AjuNeto` float DEFAULT NULL,
  `AjuIGVV` float DEFAULT NULL,
  `IGVEsta` int(1) DEFAULT NULL,
  `IGVImpo` float DEFAULT NULL,
  `CpaEstado` varchar(45) DEFAULT NULL,
  `CpaEOpe` char(1) DEFAULT NULL,
  `User_Crea` varchar(45) DEFAULT NULL,
  `User_FCrea` datetime DEFAULT NULL,
  `User_ECrea` varchar(45) DEFAULT NULL,
  `User_Modi` varchar(45) DEFAULT NULL,
  `User_FModi` datetime DEFAULT NULL,
  `User_EModi` varchar(45) DEFAULT NULL,
  `UDelete` char(1) DEFAULT NULL,
  PRIMARY KEY (`CpaOper`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `compras_cab`
--

LOCK TABLES `compras_cab` WRITE;
/*!40000 ALTER TABLE `compras_cab` DISABLE KEYS */;
/*!40000 ALTER TABLE `compras_cab` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `compras_detalle`
--

DROP TABLE IF EXISTS `compras_detalle`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `compras_detalle` (
  `Linea` char(8) NOT NULL DEFAULT '',
  `DetItem` char(2) DEFAULT NULL,
  `CpaOper` char(10) DEFAULT NULL,
  `UniCodi` char(10) DEFAULT NULL,
  `DetUnid` char(4) DEFAULT NULL,
  `Detcodi` char(20) DEFAULT NULL,
  `Detnomb` varchar(120) DEFAULT NULL,
  `MarNomb` varchar(20) DEFAULT NULL,
  `DetCant` float DEFAULT NULL,
  `DetPrec` float DEFAULT NULL,
  `DetDct1` float DEFAULT NULL,
  `DetDct2` float DEFAULT NULL,
  `DetImpo` float DEFAULT NULL,
  `DetPeso` float DEFAULT NULL,
  `DetEsta` char(1) DEFAULT NULL,
  `DetEsPe` char(2) DEFAULT NULL,
  `DetPerc` float DEFAULT NULL,
  `DetCSol` float DEFAULT NULL,
  `DetCDol` float DEFAULT NULL,
  `DetIgvv` float DEFAULT NULL,
  `DetCGia` float DEFAULT NULL,
  `OrdOper` char(10) DEFAULT NULL,
  `CccCodi` char(3) DEFAULT NULL,
  `GuiOper` char(10) DEFAULT NULL,
  `Guiline` char(8) DEFAULT NULL,
  `Detfact` float DEFAULT NULL,
  `DetSdCa` float DEFAULT NULL,
  `lote` char(15) DEFAULT NULL,
  `detfven` date DEFAULT NULL,
  PRIMARY KEY (`Linea`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `compras_detalle`
--

LOCK TABLES `compras_detalle` WRITE;
/*!40000 ALTER TABLE `compras_detalle` DISABLE KEYS */;
/*!40000 ALTER TABLE `compras_detalle` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `compras_pago`
--

DROP TABLE IF EXISTS `compras_pago`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `compras_pago` (
  `PagOper` varchar(10) NOT NULL DEFAULT '',
  `CpaOper` char(10) DEFAULT NULL,
  `TpgCodi` char(2) DEFAULT NULL,
  `PagFech` date DEFAULT NULL,
  `PagTCam` float DEFAULT NULL,
  `MonCodi` char(2) DEFAULT NULL,
  `PagImpo` float DEFAULT NULL,
  `BanCodi` char(22) DEFAULT NULL,
  `Bannomb` varchar(60) DEFAULT NULL,
  `CpaNume` varchar(12) DEFAULT NULL,
  `CpaFcpa` date DEFAULT NULL,
  `CpaFVen` date DEFAULT NULL,
  `PagBoch` char(30) DEFAULT NULL,
  `usufech` date DEFAULT NULL,
  `usuhora` char(20) DEFAULT NULL,
  `usucodi` char(2) DEFAULT NULL,
  `cajnume` varchar(10) DEFAULT NULL,
  `cpafoto` mediumblob,
  `CpaNCre` char(10) DEFAULT NULL,
  `ChePT` char(20) DEFAULT NULL,
  `EmpCodi` char(3) DEFAULT NULL,
  `PanAno` char(4) DEFAULT NULL,
  `PanPeri` char(2) DEFAULT NULL,
  `User_Crea` varchar(45) DEFAULT NULL,
  `User_FCrea` datetime DEFAULT NULL,
  `User_ECrea` varchar(45) DEFAULT NULL,
  `User_Modi` varchar(45) DEFAULT NULL,
  `User_FModi` datetime DEFAULT NULL,
  `User_EModi` varchar(45) DEFAULT NULL,
  `UDelete` char(1) DEFAULT NULL,
  PRIMARY KEY (`PagOper`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `compras_pago`
--

LOCK TABLES `compras_pago` WRITE;
/*!40000 ALTER TABLE `compras_pago` DISABLE KEYS */;
/*!40000 ALTER TABLE `compras_pago` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `condicion`
--

DROP TABLE IF EXISTS `condicion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `condicion` (
  `conCodi` char(2) NOT NULL,
  `connomb` varchar(45) DEFAULT NULL,
  `condias` float DEFAULT NULL,
  `contipo` char(1) DEFAULT NULL,
  `empcodi` varchar(3) NOT NULL DEFAULT '001',
  PRIMARY KEY (`conCodi`,`empcodi`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `condicion`
--

LOCK TABLES `condicion` WRITE;
/*!40000 ALTER TABLE `condicion` DISABLE KEYS */;
INSERT INTO `condicion` VALUES ('01','CONTADO',0,'C','001'),('02','CREDITO 7 DIAS',7,'D','001'),('03','CREDITO 15 DIAS',15,'D','001'),('04','CREDITO 30 DIAS',30,'D','001'),('05','CREDITO 45 DIAS',45,'D','001'),('06','CREDITO 60 DIAS',60,'D','001'),('07','LETRA 15-30-60',60,'D','001'),('09','CHEQUE',0,'C','001'),('10','TARJETA',0,'C','001'),('11','LETRA 30 - 45',45,'D','001');
/*!40000 ALTER TABLE `condicion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `condicion_cpra_vta`
--

DROP TABLE IF EXISTS `condicion_cpra_vta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `condicion_cpra_vta` (
  `CcvCodi` char(2) NOT NULL,
  `CcvDesc` longtext,
  `empcodi` varchar(3) NOT NULL DEFAULT '001',
  PRIMARY KEY (`CcvCodi`,`empcodi`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `condicion_cpra_vta`
--

LOCK TABLES `condicion_cpra_vta` WRITE;
/*!40000 ALTER TABLE `condicion_cpra_vta` DISABLE KEYS */;
INSERT INTO `condicion_cpra_vta` VALUES ('01','-TODO SERVICIO ES MAS EL  I.G.V.\r- 50 % DE ADELANTO PARA EMPEZAR EL TRABAJO \r\n-LOS PRECIOS ESTAN SUJETO A MODIFICACIONES SIN PREVIO AVISO.','001'),('02','*PRECIOS INCLUYEN EL I.G.V.\r\n*CHEQUE DIFERIDO A 90 DIAS\r\n*FIERROSOL RECOJE EL MATERIAL EN SUS INSTALACIONES','001'),('03','- Es responsabilidad del cliente revisar los datos del documento una vez emitido.\n - No se aceptan cambios ni devoluciones de materiales.\n - La mercaderia viaja por cuenta y riesgo del comprador.\n- Los materiales no recogidos pasado el 5to día pagaran el 1% diario por almacenaje.','001');
/*!40000 ALTER TABLE `condicion_cpra_vta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contingencias_cab`
--

DROP TABLE IF EXISTS `contingencias_cab`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contingencias_cab` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `empcodi` varchar(4) COLLATE utf8mb4_unicode_ci NOT NULL,
  `panano` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `panperi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mescodi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `docnume` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ticket` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fecha_documento` date NOT NULL,
  `fecha_emision` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contingencias_cab`
--

LOCK TABLES `contingencias_cab` WRITE;
/*!40000 ALTER TABLE `contingencias_cab` DISABLE KEYS */;
/*!40000 ALTER TABLE `contingencias_cab` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contingencias_detalles`
--

DROP TABLE IF EXISTS `contingencias_detalles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contingencias_detalles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `empcodi` varchar(4) COLLATE utf8mb4_unicode_ci NOT NULL,
  `con_id` int(11) NOT NULL,
  `vtaoper` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tidcodi` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `serie` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `numero` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `motivo_id` int(11) DEFAULT NULL,
  `gravada` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `exonerada` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `inafecta` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `igv` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `isc` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tidcodi_ref` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `serie_ref` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `numero_ref` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contingencias_detalles`
--

LOCK TABLES `contingencias_detalles` WRITE;
/*!40000 ALTER TABLE `contingencias_detalles` DISABLE KEYS */;
/*!40000 ALTER TABLE `contingencias_detalles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contingencias_motivos`
--

DROP TABLE IF EXISTS `contingencias_motivos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contingencias_motivos` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cod_sunat` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contingencias_motivos`
--

LOCK TABLES `contingencias_motivos` WRITE;
/*!40000 ALTER TABLE `contingencias_motivos` DISABLE KEYS */;
/*!40000 ALTER TABLE `contingencias_motivos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `control_adelanto_cab`
--

DROP TABLE IF EXISTS `control_adelanto_cab`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `control_adelanto_cab` (
  `CtoOper` char(10) NOT NULL,
  `CtoFech` date DEFAULT NULL,
  `CtoFecC` date DEFAULT NULL,
  `monCodi` char(2) DEFAULT NULL,
  `Tipcamb` float DEFAULT NULL,
  `ctoImpo` float DEFAULT NULL,
  `CtoDesc` varchar(120) DEFAULT NULL,
  `ctocant` float DEFAULT NULL,
  `CtoEsta` char(2) DEFAULT NULL,
  `ConCodi` char(3) DEFAULT NULL,
  PRIMARY KEY (`CtoOper`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `control_adelanto_cab`
--

LOCK TABLES `control_adelanto_cab` WRITE;
/*!40000 ALTER TABLE `control_adelanto_cab` DISABLE KEYS */;
/*!40000 ALTER TABLE `control_adelanto_cab` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `control_adelanto_detalle`
--

DROP TABLE IF EXISTS `control_adelanto_detalle`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `control_adelanto_detalle` (
  `CDeOper` char(6) NOT NULL,
  `CdeFech` date DEFAULT NULL,
  `moncodi` char(2) DEFAULT NULL,
  `TipCamb` float DEFAULT NULL,
  `CDeImpo` float DEFAULT NULL,
  `CdeRefe` varchar(50) DEFAULT NULL,
  `CDeBanc` varchar(45) DEFAULT NULL,
  `CdeDesc` varchar(100) DEFAULT NULL,
  `CtoOper` char(10) DEFAULT NULL,
  `usuCodi` char(3) DEFAULT NULL,
  `TpgCodi` char(2) DEFAULT NULL,
  PRIMARY KEY (`CDeOper`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `control_adelanto_detalle`
--

LOCK TABLES `control_adelanto_detalle` WRITE;
/*!40000 ALTER TABLE `control_adelanto_detalle` DISABLE KEYS */;
/*!40000 ALTER TABLE `control_adelanto_detalle` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `control_almacen`
--

DROP TABLE IF EXISTS `control_almacen`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `control_almacen` (
  `CtoOper` char(12) NOT NULL,
  `CtofecA` date DEFAULT NULL,
  `CtoFecC` char(15) DEFAULT NULL,
  `CtoHora` char(15) DEFAULT NULL,
  `CtoEsta` char(2) DEFAULT NULL,
  `UsuCodi` char(2) DEFAULT NULL,
  `LocCodi` char(3) DEFAULT NULL,
  PRIMARY KEY (`CtoOper`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `control_almacen`
--

LOCK TABLES `control_almacen` WRITE;
/*!40000 ALTER TABLE `control_almacen` DISABLE KEYS */;
/*!40000 ALTER TABLE `control_almacen` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `control_almacen_detalle`
--

DROP TABLE IF EXISTS `control_almacen_detalle`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `control_almacen_detalle` (
  `DetOper` char(8) NOT NULL,
  `CtoOper` char(12) DEFAULT NULL,
  `DetInic` float DEFAULT NULL,
  `DetIngr` float DEFAULT NULL,
  `DetSali` float DEFAULT NULL,
  `DetSald` float DEFAULT NULL,
  `Id` char(11) DEFAULT NULL,
  PRIMARY KEY (`DetOper`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `control_almacen_detalle`
--

LOCK TABLES `control_almacen_detalle` WRITE;
/*!40000 ALTER TABLE `control_almacen_detalle` DISABLE KEYS */;
/*!40000 ALTER TABLE `control_almacen_detalle` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cotizaciones`
--

DROP TABLE IF EXISTS `cotizaciones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cotizaciones` (
  `EmpCodi` char(3) NOT NULL,
  `LocCodi` char(3) NOT NULL,
  `CotNume` char(12) NOT NULL,
  `CotFVta` date DEFAULT NULL,
  `CotFVen` date DEFAULT NULL,
  `PcCodi` char(5) DEFAULT NULL,
  `ConCodi` char(2) DEFAULT NULL,
  `zoncodi` char(4) DEFAULT NULL,
  `moncodi` char(2) DEFAULT NULL,
  `vencodi` char(4) DEFAULT NULL,
  `Docrefe` varchar(20) DEFAULT NULL,
  `VtaOper` char(10) DEFAULT NULL,
  `cotobse` longtext,
  `CotTCam` float DEFAULT NULL,
  `cotcant` float DEFAULT NULL,
  `cotbase` float DEFAULT NULL,
  `cotigvv` float DEFAULT NULL,
  `cotimpo` float DEFAULT NULL,
  `cotesta` char(2) DEFAULT NULL,
  `usucodi` char(2) DEFAULT NULL,
  `mescodi` char(6) DEFAULT NULL,
  `CotCond` longtext,
  `Cotcont` varchar(45) DEFAULT NULL,
  `CotEsPe` char(2) DEFAULT NULL,
  `CotPPer` float DEFAULT NULL,
  `CotAPer` float DEFAULT NULL,
  `CotPerc` float DEFAULT NULL,
  `CotTota` float DEFAULT NULL,
  `TipCodi` char(6) DEFAULT NULL,
  `TidCodi` char(2) DEFAULT NULL,
  `TidCodi1` varchar(255) NOT NULL DEFAULT '50',
  PRIMARY KEY (`EmpCodi`,`LocCodi`,`CotNume`,`TidCodi1`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cotizaciones`
--

LOCK TABLES `cotizaciones` WRITE;
/*!40000 ALTER TABLE `cotizaciones` DISABLE KEYS */;
/*!40000 ALTER TABLE `cotizaciones` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cotizaciones_detalle`
--

DROP TABLE IF EXISTS `cotizaciones_detalle`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cotizaciones_detalle` (
  `DetItem` char(2) NOT NULL DEFAULT '',
  `CotNume` varchar(12) NOT NULL,
  `UniCodi` char(10) DEFAULT NULL,
  `DetUnid` char(4) DEFAULT NULL,
  `DetCodi` varchar(20) DEFAULT NULL,
  `DetNomb` varchar(255) DEFAULT NULL,
  `MarNomb` varchar(45) DEFAULT NULL,
  `DetCant` float DEFAULT NULL,
  `DetFact` float DEFAULT NULL,
  `DetPrec` float DEFAULT NULL,
  `DetImpo` float DEFAULT NULL,
  `DetPeso` float DEFAULT NULL,
  `DetEsta` char(2) DEFAULT NULL,
  `DetEsPe` char(2) DEFAULT NULL,
  `VtaOper` char(10) DEFAULT NULL,
  `Vtacant` float DEFAULT NULL,
  `DetIGVV` float DEFAULT NULL,
  `DetCSol` float DEFAULT NULL,
  `DetCDol` float DEFAULT NULL,
  `DetDcto` float DEFAULT NULL,
  `DetDeta` varchar(545) DEFAULT NULL,
  `DetBase` varchar(45) DEFAULT NULL,
  `DetISC` float DEFAULT NULL,
  `DetISCP` float DEFAULT NULL,
  `DetIGVP` float DEFAULT NULL,
  `DetPercP` float DEFAULT NULL,
  `EmpCodi` varchar(3) NOT NULL DEFAULT '001',
  PRIMARY KEY (`DetItem`,`CotNume`,`EmpCodi`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cotizaciones_detalle`
--

LOCK TABLES `cotizaciones_detalle` WRITE;
/*!40000 ALTER TABLE `cotizaciones_detalle` DISABLE KEYS */;
/*!40000 ALTER TABLE `cotizaciones_detalle` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cuenta_conta`
--

DROP TABLE IF EXISTS `cuenta_conta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cuenta_conta` (
  `CueCodi` varchar(5) NOT NULL DEFAULT '',
  `Cuenomb` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`CueCodi`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cuenta_conta`
--

LOCK TABLES `cuenta_conta` WRITE;
/*!40000 ALTER TABLE `cuenta_conta` DISABLE KEYS */;
INSERT INTO `cuenta_conta` VALUES ('10','CAJA Y BANCOS'),('12','CLIENTES'),('16','CUENTAS POR COBRAR DIVERSAS'),('20','MERCADERÍAS'),('21','PRODUCTOS TERMINADOS'),('33','INMUEBLES, MAQUINARIAS Y EQUIPO'),('34','INTANGIBLES'),('38','CARGAS DIFERIDAS'),('39','DEPRECIACIÓN Y AMORTIZACIÓN ACUMULADA'),('4011C','TRIBUTOS POR PAGAR - IGV - CRÉDITOS'),('4011D','TRIBUTOS POR PAGAR - IGV - DÉBITOS'),('4017C','TRIBUTOS POR PAGAR - IMPUESTO A LA RENTA - CRÉDITOS'),('4017D','TRIBUTOS POR PAGAR - IMPUESTO A LA RENTA - DÉBITOS'),('402','TRIBUTOS POR PAGAR - OTROS IMPUESTOS'),('42','PROVEEDORES'),('46','CUENTAS POR PAGAR DIVERSAS'),('50','CAPITAL'),('58','RESERVAS'),('59','RESULTADOS ACUMULADOS'),('60','COMPRAS'),('61','VARIACIÓN DE EXISTENCIAS'),('62','CARGAS DE PERSONAL'),('63','SERVICIOS PRESTADOS POR TERCEROS'),('65','CARGAS DIVERSAS DE GESTIÓN'),('66','CARGAS EXCEPCIONALES'),('67','CARGAS FINANCIERAS'),('68','PROVISIONES DEL EJERCICIO'),('69','COSTO DE VENTAS'),('70','VENTAS'),('75','INGRESOS DIVERSOS'),('76','INGRESOS EXCEPCIONALES'),('77','INGRESOS FINANCIEROS'),('79','CARGAS IMPUTABLES A LA CUENTA DE COSTOS'),('96','GASTOS ADMINISTRATIVOS'),('97','GASTOS DE VENTAS');
/*!40000 ALTER TABLE `cuenta_conta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `documentos_pendientes`
--

DROP TABLE IF EXISTS `documentos_pendientes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `documentos_pendientes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `EmpCodi` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipo_documento` enum('factura','boleta') COLLATE utf8mb4_unicode_ci NOT NULL,
  `cantidad` int(11) NOT NULL,
  `lapso` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'diario',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `documentos_pendientes`
--

LOCK TABLES `documentos_pendientes` WRITE;
/*!40000 ALTER TABLE `documentos_pendientes` DISABLE KEYS */;
/*!40000 ALTER TABLE `documentos_pendientes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `documentos_pendientes_detalle`
--

DROP TABLE IF EXISTS `documentos_pendientes_detalle`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `documentos_pendientes_detalle` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_documento_pendiente` int(10) unsigned NOT NULL,
  `VtaOper` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `EmpCodi` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `VtaNume` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `documentos_pendientes_detalle_id_documento_pendiente_foreign` (`id_documento_pendiente`),
  CONSTRAINT `documentos_pendientes_detalle_id_documento_pendiente_foreign` FOREIGN KEY (`id_documento_pendiente`) REFERENCES `documentos_pendientes_detalle` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `documentos_pendientes_detalle`
--

LOCK TABLES `documentos_pendientes_detalle` WRITE;
/*!40000 ALTER TABLE `documentos_pendientes_detalle` DISABLE KEYS */;
/*!40000 ALTER TABLE `documentos_pendientes_detalle` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `egresos`
--

DROP TABLE IF EXISTS `egresos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `egresos` (
  `EgrCodi` char(3) NOT NULL,
  `Egrnomb` varchar(105) DEFAULT NULL,
  `EmpCodi` varchar(3) NOT NULL DEFAULT '001',
  PRIMARY KEY (`EgrCodi`,`EmpCodi`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `egresos`
--

LOCK TABLES `egresos` WRITE;
/*!40000 ALTER TABLE `egresos` DISABLE KEYS */;
INSERT INTO `egresos` VALUES ('000','RETIRO EFECTIVO','001'),('001','COMPRAS OFICINA','001'),('002','DDDD','001');
/*!40000 ALTER TABLE `egresos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `empresa_transporte`
--

DROP TABLE IF EXISTS `empresa_transporte`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `empresa_transporte` (
  `EmpCodi` int(3) NOT NULL,
  `EmpNomb` varchar(90) DEFAULT NULL,
  `EmpRucc` varchar(12) DEFAULT NULL,
  `empresa_id` varchar(255) DEFAULT '001',
  PRIMARY KEY (`EmpCodi`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `empresa_transporte`
--

LOCK TABLES `empresa_transporte` WRITE;
/*!40000 ALTER TABLE `empresa_transporte` DISABLE KEYS */;
INSERT INTO `empresa_transporte` VALUES (100,'SIN DEFINIR','','001'),(101,'FIERRO COMERCIAL DEL SUR .S.A','20499599235','001');
/*!40000 ALTER TABLE `empresa_transporte` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `familias`
--

DROP TABLE IF EXISTS `familias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `familias` (
  `famCodi` varchar(25) NOT NULL,
  `famNomb` varchar(255) DEFAULT NULL,
  `gruCodi` varchar(25) NOT NULL,
  `famesta` varchar(2) DEFAULT NULL,
  `empcodi` varchar(3) NOT NULL DEFAULT '001',
  PRIMARY KEY (`famCodi`,`gruCodi`,`empcodi`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `familias`
--

LOCK TABLES `familias` WRITE;
/*!40000 ALTER TABLE `familias` DISABLE KEYS */;
INSERT INTO `familias` VALUES ('00','SIN DEFINIR','00',NULL,'001');
/*!40000 ALTER TABLE `familias` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `grupos`
--

DROP TABLE IF EXISTS `grupos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `grupos` (
  `GruCodi` varchar(3) NOT NULL,
  `GruNomb` varchar(50) DEFAULT NULL,
  `GruEsta` int(1) DEFAULT NULL,
  `empcodi` varchar(3) NOT NULL DEFAULT '001',
  PRIMARY KEY (`GruCodi`,`empcodi`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `grupos`
--

LOCK TABLES `grupos` WRITE;
/*!40000 ALTER TABLE `grupos` DISABLE KEYS */;
INSERT INTO `grupos` VALUES ('00','SIN DEFINIR',1,'001');
/*!40000 ALTER TABLE `grupos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `guia_detalle`
--

DROP TABLE IF EXISTS `guia_detalle`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `guia_detalle` (
  `DetItem` char(5) DEFAULT NULL,
  `GuiOper` char(10) DEFAULT NULL,
  `Linea` char(8) NOT NULL DEFAULT '',
  `UniCodi` char(10) DEFAULT NULL,
  `DetNomb` varchar(105) DEFAULT NULL,
  `MarNomb` varchar(45) DEFAULT NULL,
  `Detcant` float DEFAULT NULL,
  `DetPrec` double DEFAULT NULL,
  `DetDct1` float DEFAULT NULL,
  `DetDct2` float DEFAULT NULL,
  `DetImpo` double DEFAULT NULL,
  `DetPeso` float DEFAULT NULL,
  `DetUnid` char(10) DEFAULT NULL,
  `DetCodi` char(20) DEFAULT NULL,
  `DetCSol` float DEFAULT NULL,
  `DetCDol` float DEFAULT NULL,
  `CCoCodi` char(3) DEFAULT NULL,
  `CpaVtaCant` float DEFAULT NULL,
  `CpaVtaOpe` varchar(6) DEFAULT NULL,
  `CpaVtaLine` varchar(8) DEFAULT NULL,
  `DetTipo` char(1) DEFAULT NULL,
  `DetEsPe` float DEFAULT NULL,
  `DetFact` float DEFAULT NULL,
  `TidCodi` char(2) DEFAULT NULL,
  `DetDcto` float DEFAULT NULL,
  `DetIng` float DEFAULT NULL,
  `DetSal` float DEFAULT NULL,
  `DetDeta` varchar(245) DEFAULT NULL,
  `lote` char(15) DEFAULT NULL,
  `detfven` date DEFAULT NULL,
  `empcodi` varchar(3) NOT NULL DEFAULT '001',
  PRIMARY KEY (`Linea`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `guia_detalle`
--

LOCK TABLES `guia_detalle` WRITE;
/*!40000 ALTER TABLE `guia_detalle` DISABLE KEYS */;
/*!40000 ALTER TABLE `guia_detalle` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `guia_detalle_pre`
--

DROP TABLE IF EXISTS `guia_detalle_pre`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `guia_detalle_pre` (
  `DetItem` char(2) DEFAULT NULL,
  `GuiOper` char(10) DEFAULT NULL,
  `Linea` char(8) NOT NULL DEFAULT '',
  `UniCodi` char(20) DEFAULT NULL,
  `DetNomb` varchar(105) DEFAULT NULL,
  `Detcant` float DEFAULT NULL,
  `DetPrec` double DEFAULT NULL,
  `DetDct1` float DEFAULT NULL,
  `DetDct2` float DEFAULT NULL,
  `DetImpo` double DEFAULT NULL,
  `DetPeso` float DEFAULT NULL,
  `DetUnid` char(10) DEFAULT NULL,
  `DetCodi` char(20) DEFAULT NULL,
  `DetCSol` float DEFAULT NULL,
  `DetCDol` float DEFAULT NULL,
  `CCoCodi` char(3) DEFAULT NULL,
  `CpaVtaCant` float DEFAULT NULL,
  `CpaVtaOpe` varchar(6) DEFAULT NULL,
  `CpaVtaLine` varchar(8) DEFAULT NULL,
  `DetTipo` char(1) DEFAULT NULL,
  `DetEsPe` char(2) DEFAULT NULL,
  `DetFact` float DEFAULT NULL,
  `TidCodi` char(2) DEFAULT NULL,
  PRIMARY KEY (`Linea`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `guia_detalle_pre`
--

LOCK TABLES `guia_detalle_pre` WRITE;
/*!40000 ALTER TABLE `guia_detalle_pre` DISABLE KEYS */;
/*!40000 ALTER TABLE `guia_detalle_pre` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `guias_cab`
--

DROP TABLE IF EXISTS `guias_cab`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `guias_cab` (
  `GuiOper` char(10) NOT NULL,
  `EmpCodi` char(3) NOT NULL,
  `PanAno` char(4) NOT NULL,
  `PanPeri` char(2) NOT NULL,
  `EntSal` char(1) DEFAULT NULL,
  `GuiSeri` char(4) DEFAULT NULL,
  `GuiNumee` char(8) DEFAULT NULL,
  `GuiNume` varchar(15) DEFAULT NULL,
  `GuiFemi` date DEFAULT NULL,
  `GuiFDes` date DEFAULT NULL,
  `TmoCodi` char(4) DEFAULT NULL,
  `GuiEsta` varchar(100) DEFAULT NULL,
  `PCCodi` char(5) DEFAULT NULL,
  `zoncodi` char(4) DEFAULT NULL,
  `vencodi` char(4) DEFAULT NULL,
  `Loccodi` char(3) DEFAULT NULL,
  `moncodi` char(2) DEFAULT NULL,
  `guiTcam` float DEFAULT NULL,
  `tracodi` char(4) DEFAULT NULL,
  `guiobse` varchar(105) DEFAULT NULL,
  `guipedi` varchar(25) DEFAULT NULL,
  `guicant` float DEFAULT NULL,
  `guitbas` float DEFAULT NULL,
  `guiporp` float DEFAULT NULL,
  `GuiEsPe` char(2) DEFAULT NULL,
  `docrefe` char(15) DEFAULT NULL,
  `guidirp` varchar(105) DEFAULT NULL,
  `guidisp` varchar(45) DEFAULT NULL,
  `guidill` varchar(105) DEFAULT NULL,
  `guidisll` varchar(45) DEFAULT NULL,
  `motcodi` char(2) DEFAULT NULL,
  `VehCodi` char(4) DEFAULT NULL,
  `concodi` char(2) DEFAULT NULL,
  `mescodi` char(6) DEFAULT NULL,
  `usucodi` char(2) DEFAULT NULL,
  `TipCodi` char(6) DEFAULT NULL,
  `cpaOper` char(10) DEFAULT NULL,
  `vtaoper` char(10) DEFAULT NULL,
  `TidCodi` varchar(2) DEFAULT NULL,
  `IGVEsta` int(1) DEFAULT NULL,
  `GuiNOpe` varchar(15) DEFAULT NULL,
  `CtoOper` char(12) DEFAULT NULL,
  `TraOper` char(12) DEFAULT NULL,
  `GuiEFor` int(1) DEFAULT NULL,
  `GuiEOpe` char(1) DEFAULT NULL,
  `TippCodi` char(1) DEFAULT NULL,
  `User_Crea` varchar(45) DEFAULT NULL,
  `User_FCrea` datetime DEFAULT NULL,
  `User_ECrea` varchar(45) DEFAULT NULL,
  `User_Modi` varchar(45) DEFAULT NULL,
  `User_FModi` datetime DEFAULT NULL,
  `User_EModi` varchar(45) DEFAULT NULL,
  `UDelete` char(1) DEFAULT NULL,
  `GuiXML` int(11) DEFAULT '0',
  `GuiPDF` int(11) DEFAULT '0',
  `GuiCDR` int(11) DEFAULT '0',
  `GuiMail` int(11) DEFAULT '0',
  `fe_rpta` varchar(45) DEFAULT '9',
  `fe_obse` varchar(255) DEFAULT NULL,
  `fe_firma` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`GuiOper`,`EmpCodi`,`PanAno`,`PanPeri`),
  KEY `LocCodi_idx` (`Loccodi`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `guias_cab`
--

LOCK TABLES `guias_cab` WRITE;
/*!40000 ALTER TABLE `guias_cab` DISABLE KEYS */;
/*!40000 ALTER TABLE `guias_cab` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `guias_cab_pre`
--

DROP TABLE IF EXISTS `guias_cab_pre`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `guias_cab_pre` (
  `GuiOper` char(10) NOT NULL,
  `GuiNume` varchar(15) DEFAULT NULL,
  `GuiFemi` date DEFAULT NULL,
  `GuiFDes` date DEFAULT NULL,
  `TmoCodi` char(4) DEFAULT NULL,
  `EntSal` char(1) DEFAULT NULL,
  `GuiEsta` char(1) DEFAULT NULL,
  `PCCodi` char(5) DEFAULT NULL,
  `zoncodi` char(4) DEFAULT NULL,
  `vencodi` char(4) DEFAULT NULL,
  `Loccodi` char(3) DEFAULT NULL,
  `moncodi` char(2) DEFAULT NULL,
  `guiTcam` float DEFAULT NULL,
  `tracodi` char(4) DEFAULT NULL,
  `guiobse` varchar(105) DEFAULT NULL,
  `guipedi` varchar(25) DEFAULT NULL,
  `guicant` float DEFAULT NULL,
  `guitbas` float DEFAULT NULL,
  `guiporp` float DEFAULT NULL,
  `GuiEsPe` char(2) DEFAULT NULL,
  `docrefe` char(15) DEFAULT NULL,
  `guidirp` varchar(105) DEFAULT NULL,
  `guidisp` varchar(45) DEFAULT NULL,
  `guidill` varchar(105) DEFAULT NULL,
  `guidisll` varchar(45) DEFAULT NULL,
  `motcodi` char(2) DEFAULT NULL,
  `VehCodi` char(4) DEFAULT NULL,
  `concodi` char(2) DEFAULT NULL,
  `empcodi` char(3) DEFAULT NULL,
  `mescodi` char(6) DEFAULT NULL,
  `usucodi` char(2) DEFAULT NULL,
  `TipCodi` char(6) DEFAULT NULL,
  `Traoper` char(10) DEFAULT NULL,
  `cpaOper` char(10) DEFAULT NULL,
  `vtaoper` char(10) DEFAULT NULL,
  `IGVEsta` int(1) DEFAULT NULL,
  `GuiNOpe` varchar(15) DEFAULT NULL,
  `CtoOper` char(12) DEFAULT NULL,
  PRIMARY KEY (`GuiOper`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `guias_cab_pre`
--

LOCK TABLES `guias_cab_pre` WRITE;
/*!40000 ALTER TABLE `guias_cab_pre` DISABLE KEYS */;
/*!40000 ALTER TABLE `guias_cab_pre` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `imagenes`
--

DROP TABLE IF EXISTS `imagenes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `imagenes` (
  `imacodi` int(11) NOT NULL AUTO_INCREMENT,
  `imanomb` longblob,
  PRIMARY KEY (`imacodi`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `imagenes`
--

LOCK TABLES `imagenes` WRITE;
/*!40000 ALTER TABLE `imagenes` DISABLE KEYS */;
INSERT INTO `imagenes` VALUES (1,NULL);
/*!40000 ALTER TABLE `imagenes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ingresos`
--

DROP TABLE IF EXISTS `ingresos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ingresos` (
  `IngCodi` int(3) NOT NULL,
  `IngNomb` varchar(105) DEFAULT NULL,
  `EmpCodi` varchar(3) NOT NULL DEFAULT '001',
  PRIMARY KEY (`IngCodi`,`EmpCodi`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ingresos`
--

LOCK TABLES `ingresos` WRITE;
/*!40000 ALTER TABLE `ingresos` DISABLE KEYS */;
/*!40000 ALTER TABLE `ingresos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lista_precio`
--

DROP TABLE IF EXISTS `lista_precio`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lista_precio` (
  `LisCodi` int(2) NOT NULL,
  `LisNomb` varchar(45) DEFAULT NULL,
  `empcodi` varchar(3) NOT NULL DEFAULT '001',
  `LocCodi` varchar(255) DEFAULT '001',
  PRIMARY KEY (`LisCodi`,`empcodi`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lista_precio`
--

LOCK TABLES `lista_precio` WRITE;
/*!40000 ALTER TABLE `lista_precio` DISABLE KEYS */;
INSERT INTO `lista_precio` VALUES (10,'LIST.PUBLICO','001','001');
/*!40000 ALTER TABLE `lista_precio` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `local`
--

DROP TABLE IF EXISTS `local`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `local` (
  `LocCodi` char(3) NOT NULL,
  `LocNomb` varchar(45) DEFAULT NULL,
  `LocDire` varchar(105) DEFAULT NULL,
  `LocDist` varchar(45) DEFAULT NULL,
  `LocTele` varchar(100) DEFAULT NULL,
  `SerGuiaSal` char(4) DEFAULT NULL,
  `NumGuiaSal` char(12) DEFAULT NULL,
  `Numlibre` char(10) DEFAULT NULL,
  `Numletra` char(10) DEFAULT NULL,
  `SerLetra` char(3) DEFAULT NULL,
  `Fecha` date DEFAULT NULL,
  `EmpCodi` char(3) NOT NULL,
  PRIMARY KEY (`LocCodi`,`EmpCodi`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `local`
--

LOCK TABLES `local` WRITE;
/*!40000 ALTER TABLE `local` DISABLE KEYS */;
INSERT INTO `local` VALUES ('000','TODOS...',NULL,NULL,NULL,NULL,'10',NULL,NULL,NULL,NULL,'001'),('001','PRINCIPAL','AV. AVIACION NRO 2334 URB SAN BORJA SAN BORJA LIMA - LIMA','150130','2540620','000','10','10','000000','000','2014-10-08','001'),('001','PRINCIPAL','CAL. CALLE 9 MZ. Y LT. 31 A.H. - 1RO DE JUNIO / SAN JUAN DE MIRAFLORES 99VA #522','150133','2540620','000','10','10','000000','000','2014-10-08','002'),('001','PRINCIPAL','-','150130','-','-','10','10','-','-','2014-10-08','003'),('001','PRINCIPAL','AV. TUPAC AMARU NRO. 3303 URB. CHACRA CERRO LIMA - LIMA - COMAS','150130','Telf. (01) 537 2329  Cel. 960 841 352','000','10','10','000000','000','2014-10-08','004'),('001','PRINCIPAL','MZA. G LOTE. 19 A.V. RICARDO PALMA PAMPAS DE SAN JUAN LIMA - LIMA - SAN JUAN DE MIRAFLORES','150130','2540620','000','10','10','000000','000','2014-10-08','005'),('002','SUCURSAL','MZA. REF LOTE. 42A CASA HUERTA EL CARMEN LIMA - LIMA - CARABAYLLO ','150130','Telf 7233654 Cel. 952394228','000','20','20','000000','000','2014-10-08','004');
/*!40000 ALTER TABLE `local` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `marca`
--

DROP TABLE IF EXISTS `marca`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `marca` (
  `MarCodi` char(4) NOT NULL,
  `MarNomb` varchar(50) DEFAULT NULL,
  `empcodi` varchar(3) NOT NULL DEFAULT '001',
  PRIMARY KEY (`MarCodi`,`empcodi`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `marca`
--

LOCK TABLES `marca` WRITE;
/*!40000 ALTER TABLE `marca` DISABLE KEYS */;
INSERT INTO `marca` VALUES ('01','SIN DEFINIR','001');
/*!40000 ALTER TABLE `marca` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `menu`
--

DROP TABLE IF EXISTS `menu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `menu` (
  `menCodi` char(3) NOT NULL,
  `menNomb` varchar(225) NOT NULL,
  PRIMARY KEY (`menCodi`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `menu`
--

LOCK TABLES `menu` WRITE;
/*!40000 ALTER TABLE `menu` DISABLE KEYS */;
/*!40000 ALTER TABLE `menu` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mes`
--

DROP TABLE IF EXISTS `mes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mes` (
  `mescodi` varchar(7) NOT NULL DEFAULT '',
  `mesnomb` varchar(30) DEFAULT NULL,
  `mesdesd` date DEFAULT NULL,
  `meshast` date DEFAULT NULL,
  PRIMARY KEY (`mescodi`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mes`
--

LOCK TABLES `mes` WRITE;
/*!40000 ALTER TABLE `mes` DISABLE KEYS */;
INSERT INTO `mes` VALUES ('202001','ENERO DEL 2020','2020-01-09','2020-01-09');
/*!40000 ALTER TABLE `mes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `model_has_permissions`
--

DROP TABLE IF EXISTS `model_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `model_has_permissions` (
  `permission_id` int(10) unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  KEY `model_has_permissions_permission_id_foreign` (`permission_id`),
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `model_has_permissions`
--

LOCK TABLES `model_has_permissions` WRITE;
/*!40000 ALTER TABLE `model_has_permissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `model_has_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `model_has_roles`
--

DROP TABLE IF EXISTS `model_has_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `model_has_roles` (
  `role_id` int(10) unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  KEY `model_has_roles_role_id_foreign` (`role_id`),
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `model_has_roles`
--

LOCK TABLES `model_has_roles` WRITE;
/*!40000 ALTER TABLE `model_has_roles` DISABLE KEYS */;
INSERT INTO `model_has_roles` VALUES (16,'App\\User','01'),(18,'App\\User','99'),(17,'App\\User','12'),(17,'App\\User','100'),(19,'App\\User','17'),(17,'App\\User','14'),(19,'App\\User','16'),(19,'App\\User','13'),(17,'App\\User','18');
/*!40000 ALTER TABLE `model_has_roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orden_cab`
--

DROP TABLE IF EXISTS `orden_cab`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `orden_cab` (
  `OrdOper` char(10) NOT NULL,
  `EmpCodi` char(3) DEFAULT NULL,
  `PanAno` char(4) DEFAULT NULL,
  `PanPeri` char(2) DEFAULT NULL,
  `OrdFOrd` date DEFAULT NULL,
  `OrdFCon` date DEFAULT NULL,
  `OrdFPag` date DEFAULT NULL,
  `OrdFven` date DEFAULT NULL,
  `PCcodi` char(5) DEFAULT NULL,
  `TidCodi` char(2) DEFAULT NULL,
  `concodi` char(2) DEFAULT NULL,
  `zoncodi` char(4) DEFAULT NULL,
  `moncodi` char(2) DEFAULT NULL,
  `vencodi` char(4) DEFAULT NULL,
  `Docrefe` char(20) DEFAULT NULL,
  `CpaOper` char(10) DEFAULT NULL,
  `Ordobse` varchar(145) DEFAULT NULL,
  `OrdTCam` float DEFAULT NULL,
  `Ordcant` float DEFAULT NULL,
  `OrdTPes` float DEFAULT NULL,
  `Ordbase` float DEFAULT NULL,
  `OrdIGVV` float DEFAULT NULL,
  `OrdImpo` float DEFAULT NULL,
  `OrdEsta` char(1) DEFAULT NULL,
  `usuCodi` char(2) DEFAULT NULL,
  `MesCodi` char(6) DEFAULT NULL,
  `LocCodi` char(3) DEFAULT NULL,
  `OrdPago` float DEFAULT NULL,
  `OrdSald` float DEFAULT NULL,
  `OrdEsPe` char(2) DEFAULT NULL,
  `OrdPPer` float DEFAULT NULL,
  `OrdAPer` float DEFAULT NULL,
  `OrdPerc` float DEFAULT NULL,
  `Ordtota` float DEFAULT NULL,
  `TipCodi` char(6) DEFAULT NULL,
  `AlmEsta` char(2) DEFAULT NULL,
  `IGVEsta` int(1) DEFAULT NULL,
  `OrdCond` longtext,
  `User_Crea` varchar(45) DEFAULT NULL,
  `User_FCrea` datetime DEFAULT NULL,
  `User_ECrea` varchar(45) DEFAULT NULL,
  `User_Modi` varchar(45) DEFAULT NULL,
  `User_FModi` datetime DEFAULT NULL,
  `User_EModi` varchar(45) DEFAULT NULL,
  `UDelete` char(1) DEFAULT NULL,
  PRIMARY KEY (`OrdOper`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orden_cab`
--

LOCK TABLES `orden_cab` WRITE;
/*!40000 ALTER TABLE `orden_cab` DISABLE KEYS */;
/*!40000 ALTER TABLE `orden_cab` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orden_detalle`
--

DROP TABLE IF EXISTS `orden_detalle`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `orden_detalle` (
  `Linea` char(8) NOT NULL DEFAULT '',
  `DetItem` char(2) DEFAULT NULL,
  `OrdOper` char(10) DEFAULT NULL,
  `UniCodi` char(10) DEFAULT NULL,
  `DetUnid` char(4) DEFAULT NULL,
  `Detcodi` char(20) DEFAULT NULL,
  `Detnomb` varchar(120) DEFAULT NULL,
  `DetCant` float DEFAULT NULL,
  `DetPrec` float DEFAULT NULL,
  `DetDct1` float DEFAULT NULL,
  `DetDct2` float DEFAULT NULL,
  `DetImpo` float DEFAULT NULL,
  `DetPeso` float DEFAULT NULL,
  `DetEsta` char(1) DEFAULT NULL,
  `DetEsPe` char(2) DEFAULT NULL,
  `DetCSol` float DEFAULT NULL,
  `DetCDol` float DEFAULT NULL,
  `DetIgvv` float DEFAULT NULL,
  `DetCGia` float DEFAULT NULL,
  `CpaOper` char(10) DEFAULT NULL,
  `CccCodi` char(3) DEFAULT NULL,
  `GuiOper` char(10) DEFAULT NULL,
  `Cpaline` char(8) DEFAULT NULL,
  `Detfact` float DEFAULT NULL,
  PRIMARY KEY (`Linea`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orden_detalle`
--

LOCK TABLES `orden_detalle` WRITE;
/*!40000 ALTER TABLE `orden_detalle` DISABLE KEYS */;
/*!40000 ALTER TABLE `orden_detalle` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `permissions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=227 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permissions`
--

LOCK TABLES `permissions` WRITE;
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
INSERT INTO `permissions` VALUES (177,'show clientes','web','2019-08-16 10:59:07','2019-08-16 10:59:07'),(178,'delete clientes','web','2019-08-16 10:59:07','2019-08-16 10:59:07'),(179,'edit clientes','web','2019-08-16 10:59:07','2019-08-16 10:59:07'),(180,'actividad clientes','web','2019-08-16 10:59:07','2019-08-16 10:59:07'),(181,'create clientes','web','2019-08-16 10:59:07','2019-08-16 10:59:07'),(182,'create productos','web','2019-08-16 10:59:07','2019-08-16 10:59:07'),(183,'show productos','web','2019-08-16 10:59:07','2019-08-16 10:59:07'),(184,'delete productos','web','2019-08-16 10:59:07','2019-08-16 10:59:07'),(185,'edit productos','web','2019-08-16 10:59:07','2019-08-16 10:59:07'),(186,'show parametros','web','2019-08-16 10:59:07','2019-08-16 10:59:07'),(187,'store parametros','web','2019-08-16 10:59:07','2019-08-16 10:59:07'),(188,'show ventas','web','2019-08-16 10:59:07','2019-08-16 10:59:07'),(189,'show_recurso ventas','web','2019-08-16 10:59:07','2019-08-16 10:59:07'),(190,'create ventas','web','2019-08-16 10:59:07','2019-08-16 10:59:07'),(191,'delete ventas','web','2019-08-16 10:59:07','2019-08-16 10:59:07'),(192,'edit ventas','web','2019-08-16 10:59:07','2019-08-16 10:59:07'),(193,'email ventas','web','2019-08-16 10:59:07','2019-08-16 10:59:07'),(194,'reporte compra-venta','web','2019-08-16 10:59:07','2019-08-16 10:59:07'),(195,'reporte kardex-fisico','web','2019-08-16 10:59:07','2019-08-16 10:59:07'),(196,'reporte kardex-valorizado','web','2019-08-16 10:59:07','2019-08-16 10:59:07'),(197,'reporte contable-ventas','web','2019-08-16 10:59:07','2019-08-16 10:59:07'),(198,'reporte ventas','web','2019-08-16 10:59:07','2019-08-16 10:59:07'),(199,'reporte guia','web','2019-08-16 10:59:07','2019-08-16 10:59:07'),(200,'importar-ventas','web','2019-08-16 10:59:07','2019-08-16 10:59:07'),(201,'verificar-documentos','web','2019-08-16 10:59:07','2019-08-16 10:59:07'),(202,'show marcas','web','2019-08-16 10:59:07','2019-08-16 10:59:07'),(203,'show pendientes','web','2019-08-16 10:59:07','2019-08-16 10:59:07'),(204,'send pendientes','web','2019-08-16 10:59:07','2019-08-16 10:59:07'),(205,'show resumen','web','2019-08-16 10:59:07','2019-08-16 10:59:07'),(206,'show guia','web','2019-08-16 10:59:07','2019-08-16 10:59:07'),(207,'index cajas','web','2019-08-16 10:59:07','2019-08-16 10:59:07'),(208,'show caja','web','2019-08-16 10:59:07','2019-08-16 10:59:07'),(209,'create caja','web','2019-08-16 10:59:07','2019-08-16 10:59:07'),(210,'delete caja','web','2019-08-16 10:59:07','2019-08-16 10:59:07'),(211,'pendientes caja','web','2019-08-16 10:59:07','2019-08-16 10:59:07'),(212,'index cajas_movimientos','web','2019-08-16 10:59:07','2019-08-16 10:59:07'),(213,'create cajas_movimientos','web','2019-08-16 10:59:07','2019-08-16 10:59:07'),(214,'edit cajas_movimientos','web','2019-08-16 10:59:07','2019-08-16 10:59:07'),(215,'delete cajas_movimientos','web','2019-08-16 10:59:07','2019-08-16 10:59:07'),(216,'index compras','web','2019-08-16 10:59:07','2019-08-16 10:59:07'),(217,'show compras','web','2019-08-16 10:59:07','2019-08-16 10:59:07'),(218,'create compras','web','2019-08-16 10:59:07','2019-08-16 10:59:07'),(219,'edit compras','web','2019-08-16 10:59:07','2019-08-16 10:59:07'),(220,'delete compras','web','2019-08-16 10:59:07','2019-08-16 10:59:07'),(221,'cotizaciones all','web','2019-08-16 10:59:07','2019-08-16 10:59:07'),(222,'productos all','web','2019-08-16 10:59:07','2019-08-16 10:59:07'),(223,'reportes','web','2019-08-16 10:59:07','2019-08-16 10:59:07'),(224,'utilitarios','web','2019-08-16 10:59:07','2019-08-16 10:59:07'),(225,'administracion','web','2019-08-16 10:59:07','2019-08-16 10:59:07'),(226,'contador','web','2019-08-16 10:59:07','2019-08-16 10:59:07');
/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `plantilla_bole`
--

DROP TABLE IF EXISTS `plantilla_bole`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `plantilla_bole` (
  `CodClie` varchar(6) NOT NULL DEFAULT '',
  `Cliente` varchar(6) DEFAULT NULL,
  `TDocClie` varchar(6) DEFAULT NULL,
  `RUC` varchar(6) DEFAULT NULL,
  `DirClie` varchar(6) DEFAULT NULL,
  `DistClie` varchar(6) DEFAULT NULL,
  `TelClie` varchar(6) DEFAULT NULL,
  `Contacto` varchar(6) DEFAULT NULL,
  `Fecha` varchar(6) DEFAULT NULL,
  `FechaVen` varchar(6) DEFAULT NULL,
  `NumBole` varchar(6) DEFAULT NULL,
  `NumRefe` varchar(6) DEFAULT NULL,
  `NumPedi` varchar(6) DEFAULT NULL,
  `FPago` varchar(6) DEFAULT NULL,
  `CodVend` varchar(6) DEFAULT NULL,
  `Zona` varchar(6) DEFAULT NULL,
  `Moneda` varchar(6) DEFAULT NULL,
  `Observ` varchar(6) DEFAULT NULL,
  `Transp` varchar(6) DEFAULT NULL,
  `RucTransp` varchar(6) DEFAULT NULL,
  `DirTransp` varchar(6) DEFAULT NULL,
  `PlacaTransp` varchar(6) DEFAULT NULL,
  `DirPartida` varchar(6) DEFAULT NULL,
  `DirLlegada` varchar(6) DEFAULT NULL,
  `Peso` varchar(6) DEFAULT NULL,
  `NumItem` varchar(6) DEFAULT NULL,
  `CodArti` varchar(6) DEFAULT NULL,
  `DesArti` varchar(6) DEFAULT NULL,
  `Unidad` varchar(6) DEFAULT NULL,
  `Cantidad` varchar(6) DEFAULT NULL,
  `Valor` varchar(6) DEFAULT NULL,
  `Dcto1` varchar(6) DEFAULT NULL,
  `Dcto2` varchar(6) DEFAULT NULL,
  `IGVDet` varchar(6) DEFAULT NULL,
  `ISC` varchar(6) DEFAULT NULL,
  `SubTot` varchar(6) DEFAULT NULL,
  `ImpBruto` varchar(6) DEFAULT NULL,
  `ImpTDcto` varchar(6) DEFAULT NULL,
  `NumLote` varchar(6) DEFAULT NULL,
  `FechaVenLote` varchar(6) DEFAULT NULL,
  `NumSerie` varchar(6) DEFAULT NULL,
  `ValVta` varchar(6) DEFAULT NULL,
  `ValExcento` varchar(6) DEFAULT NULL,
  `ValImp` varchar(6) DEFAULT NULL,
  `ValImpISC` varchar(6) DEFAULT NULL,
  `PorIGV` varchar(6) DEFAULT NULL,
  `IGV` varchar(6) DEFAULT NULL,
  `Total` varchar(6) DEFAULT NULL,
  `Letras` varchar(6) DEFAULT NULL,
  `Espaciado` int(11) DEFAULT NULL,
  `MaxItem` int(11) DEFAULT NULL,
  `TamLetra` int(11) DEFAULT NULL,
  `LargoPagina` int(11) DEFAULT NULL,
  `LargoMemo` int(11) DEFAULT NULL,
  `Impresora` varchar(80) DEFAULT NULL,
  `AfePerc` varchar(6) DEFAULT NULL,
  `PorPerc` varchar(6) DEFAULT NULL,
  `ValPerc` varchar(6) DEFAULT NULL,
  `Totpago` varchar(6) DEFAULT NULL,
  `UsuCodi` varchar(6) DEFAULT NULL,
  `VtaHora` varchar(15) DEFAULT NULL,
  `TipLetra` varchar(105) DEFAULT NULL,
  `pagina` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`CodClie`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `plantilla_bole`
--

LOCK TABLES `plantilla_bole` WRITE;
/*!40000 ALTER TABLE `plantilla_bole` DISABLE KEYS */;
/*!40000 ALTER TABLE `plantilla_bole` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `plantilla_cheque`
--

DROP TABLE IF EXISTS `plantilla_cheque`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `plantilla_cheque` (
  `PchOper` char(4) NOT NULL,
  `PChFecha` char(6) DEFAULT NULL,
  `PChOpci` int(1) DEFAULT NULL,
  `PChFven` char(6) DEFAULT NULL,
  `PChMone` char(6) DEFAULT NULL,
  `PChImpo` char(6) DEFAULT NULL,
  `PchLetra` char(6) DEFAULT NULL,
  `PChGiro` char(6) DEFAULT NULL,
  `PChTama` char(5) DEFAULT NULL,
  `BanCodi` char(2) DEFAULT NULL,
  `PChImpre` char(30) DEFAULT NULL,
  PRIMARY KEY (`PchOper`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `plantilla_cheque`
--

LOCK TABLES `plantilla_cheque` WRITE;
/*!40000 ALTER TABLE `plantilla_cheque` DISABLE KEYS */;
/*!40000 ALTER TABLE `plantilla_cheque` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `plantilla_fact`
--

DROP TABLE IF EXISTS `plantilla_fact`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `plantilla_fact` (
  `CodClie` varchar(6) NOT NULL DEFAULT '',
  `Cliente` varchar(6) DEFAULT NULL,
  `TDocClie` varchar(6) DEFAULT NULL,
  `RUC` varchar(6) DEFAULT NULL,
  `DirClie` varchar(6) DEFAULT NULL,
  `DistClie` varchar(6) DEFAULT NULL,
  `TelClie` varchar(6) DEFAULT NULL,
  `Contacto` varchar(6) DEFAULT NULL,
  `Fecha` varchar(6) DEFAULT NULL,
  `FechaVen` varchar(6) DEFAULT NULL,
  `NumFact` varchar(6) DEFAULT NULL,
  `NumRefe` varchar(6) DEFAULT NULL,
  `NumPedi` varchar(6) DEFAULT NULL,
  `FPago` varchar(6) DEFAULT NULL,
  `CodVend` varchar(6) DEFAULT NULL,
  `Zona` varchar(6) DEFAULT NULL,
  `Moneda` varchar(6) DEFAULT NULL,
  `Observ` varchar(6) DEFAULT NULL,
  `Transp` varchar(6) DEFAULT NULL,
  `RucTransp` varchar(6) DEFAULT NULL,
  `DirTransp` varchar(6) DEFAULT NULL,
  `PlacaTransp` varchar(6) DEFAULT NULL,
  `DirPartida` varchar(6) DEFAULT NULL,
  `DirLlegada` varchar(6) DEFAULT NULL,
  `Peso` varchar(6) DEFAULT NULL,
  `NumItem` varchar(6) DEFAULT NULL,
  `CodArti` varchar(6) DEFAULT NULL,
  `DesArti` varchar(6) DEFAULT NULL,
  `Unidad` varchar(6) DEFAULT NULL,
  `Cantidad` varchar(6) DEFAULT NULL,
  `Valor` varchar(6) DEFAULT NULL,
  `Dcto1` varchar(6) DEFAULT NULL,
  `Dcto2` varchar(6) DEFAULT NULL,
  `IGVDet` varchar(6) DEFAULT NULL,
  `ISC` varchar(6) DEFAULT NULL,
  `SubTot` varchar(6) DEFAULT NULL,
  `ImpBruto` varchar(6) DEFAULT NULL,
  `ImpTDcto` varchar(6) DEFAULT NULL,
  `NumLote` varchar(6) DEFAULT NULL,
  `FechaVenLote` varchar(6) DEFAULT NULL,
  `NumSerie` varchar(6) DEFAULT NULL,
  `ValVta` varchar(6) DEFAULT NULL,
  `ValExcento` varchar(6) DEFAULT NULL,
  `ValImp` varchar(6) DEFAULT NULL,
  `ValImpISC` varchar(6) DEFAULT NULL,
  `PorIGV` varchar(6) DEFAULT NULL,
  `IGV` varchar(6) DEFAULT NULL,
  `Total` varchar(6) DEFAULT NULL,
  `Letras` varchar(6) DEFAULT NULL,
  `Espaciado` int(11) DEFAULT NULL,
  `MaxItem` int(11) DEFAULT NULL,
  `TamLetra` int(11) DEFAULT NULL,
  `LargoPagina` int(11) DEFAULT NULL,
  `LargoMemo` int(11) DEFAULT NULL,
  `Impresora` varchar(80) DEFAULT NULL,
  `AfePerc` varchar(6) DEFAULT NULL,
  `PorPerc` varchar(6) DEFAULT NULL,
  `ValPerc` varchar(6) DEFAULT NULL,
  `Totpago` varchar(6) DEFAULT NULL,
  `UsuCodi` varchar(6) DEFAULT NULL,
  `VtaHora` varchar(12) DEFAULT NULL,
  `TipLetra` varchar(105) DEFAULT NULL,
  `NumLetra` varchar(6) DEFAULT NULL,
  `ImpLetra` varchar(6) DEFAULT NULL,
  `FVenLetra` varchar(6) DEFAULT NULL,
  `facDesc` varchar(6) DEFAULT NULL,
  `LetDesc` varchar(45) DEFAULT NULL,
  `VtaMens` varchar(6) DEFAULT NULL,
  `pagina` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`CodClie`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `plantilla_fact`
--

LOCK TABLES `plantilla_fact` WRITE;
/*!40000 ALTER TABLE `plantilla_fact` DISABLE KEYS */;
INSERT INTO `plantilla_fact` VALUES ('100170','120175','000000','120215','120195','070215','036020','040020','700215','700240','640110','630290','056020','370535','050560','000000','540535','000000','076020','080020','084020','088020','092020','096020','220560','060260','090260','260260','220260','200260','700260','000000','000000','000000','000000','790260','000000','000000','000000','000000','000000','630535','000000','206155','212155','650500','690535','790535','120480',17,10,10,100,55,'ServidorEPSON LX-300+ /II','000000','000000','000000','000000','100535','500160','National First Font Dotted','000000','000000','000000','000000','000000','000000','FACTURA');
/*!40000 ALTER TABLE `plantilla_fact` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `plantilla_guia`
--

DROP TABLE IF EXISTS `plantilla_guia`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `plantilla_guia` (
  `CodClie` varchar(6) NOT NULL DEFAULT '',
  `Cliente` varchar(6) DEFAULT '',
  `TDocClie` varchar(6) DEFAULT NULL,
  `RUC` varchar(6) DEFAULT NULL,
  `DirClie` varchar(6) DEFAULT NULL,
  `DistClie` varchar(6) DEFAULT NULL,
  `TelClie` varchar(6) DEFAULT NULL,
  `Contacto` varchar(6) DEFAULT NULL,
  `Fecha` varchar(6) DEFAULT NULL,
  `NumGuia` varchar(6) DEFAULT NULL,
  `NumPedi` varchar(6) DEFAULT NULL,
  `CodVend` varchar(6) DEFAULT NULL,
  `Zona` varchar(6) DEFAULT NULL,
  `Moneda` varchar(6) DEFAULT NULL,
  `FechaDesp` varchar(6) DEFAULT NULL,
  `DirPartida` varchar(6) DEFAULT NULL,
  `DistPartida` varchar(6) DEFAULT NULL,
  `DirLlegada` varchar(6) DEFAULT NULL,
  `DistLlegada` varchar(6) DEFAULT NULL,
  `Observ` varchar(6) DEFAULT NULL,
  `Transp` varchar(6) DEFAULT NULL,
  `RUCTransp` varchar(6) DEFAULT NULL,
  `NumConstTransp` varchar(6) DEFAULT NULL,
  `PlacaTransp` varchar(6) DEFAULT NULL,
  `MarcaTransp` varchar(6) DEFAULT NULL,
  `NumRefe` varchar(6) DEFAULT NULL,
  `LicenciaTransp` varchar(6) DEFAULT NULL,
  `Motivo` varchar(6) DEFAULT NULL,
  `CostoTras` varchar(6) DEFAULT NULL,
  `Peso` varchar(6) DEFAULT NULL,
  `FPago` varchar(6) DEFAULT NULL,
  `EmpTranspRS` varchar(6) DEFAULT NULL,
  `EmpTranspRUC` varchar(6) DEFAULT NULL,
  `EmpTranspDir` varchar(6) DEFAULT NULL,
  `NumItem` varchar(6) DEFAULT NULL,
  `CodArti` varchar(6) DEFAULT NULL,
  `DesArti` varchar(6) DEFAULT NULL,
  `Unidad` varchar(6) DEFAULT NULL,
  `Cantidad` varchar(6) DEFAULT NULL,
  `Valor` varchar(6) DEFAULT NULL,
  `Dcto1` varchar(6) DEFAULT NULL,
  `Dcto2` varchar(6) DEFAULT NULL,
  `SubTot` varchar(6) DEFAULT NULL,
  `Auxiliar1` varchar(6) DEFAULT NULL,
  `Auxiliar2` varchar(6) DEFAULT NULL,
  `NumLote` varchar(6) DEFAULT NULL,
  `FechaVenLote` varchar(6) DEFAULT NULL,
  `NumSerie` varchar(6) DEFAULT NULL,
  `TotArti` varchar(6) DEFAULT NULL,
  `TotPeso` varchar(6) DEFAULT NULL,
  `Espaciado` int(11) DEFAULT NULL,
  `MaxItem` int(11) DEFAULT NULL,
  `TamLetra` int(11) DEFAULT NULL,
  `LargoPagina` int(11) DEFAULT NULL,
  `LargoMemo` int(11) DEFAULT NULL,
  `Impresora` varchar(80) DEFAULT NULL,
  `UsuCodi` varchar(6) DEFAULT NULL,
  `Vtahora` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`CodClie`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `plantilla_guia`
--

LOCK TABLES `plantilla_guia` WRITE;
/*!40000 ALTER TABLE `plantilla_guia` DISABLE KEYS */;
INSERT INTO `plantilla_guia` VALUES ('110240','100240','100120','100270','000000','000000','000000','000000','000000','600140','000000','350640','000000','072100','730315','060165','000000','060195','000000','000000','550370','450300','600260','720340','500370','650220','540340','350320','130020','760440','400140','520270','510315','000000','010440','040440','270440','220440','160440','670440','000000','000000','760440','000000','000000','000000','000000','000000','000000','760640',20,20,9,0,50,'EPSON LX-300+ /II on USB002','450640','000000');
/*!40000 ALTER TABLE `plantilla_guia` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `plantilla_letra`
--

DROP TABLE IF EXISTS `plantilla_letra`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `plantilla_letra` (
  `Girador` varchar(6) NOT NULL DEFAULT '',
  `Numero` varchar(6) DEFAULT NULL,
  `Lugar` varchar(6) DEFAULT NULL,
  `Fecha` varchar(6) DEFAULT NULL,
  `Vencimiento` varchar(6) DEFAULT NULL,
  `Moneda` varchar(6) DEFAULT NULL,
  `Importe` varchar(6) DEFAULT NULL,
  `ImporteLetra` varchar(6) DEFAULT NULL,
  `Giradoa` varchar(6) DEFAULT NULL,
  `GiradoaDoc` varchar(6) DEFAULT NULL,
  `GiradoaFono` varchar(6) DEFAULT NULL,
  `GiradoaDir` varchar(6) DEFAULT NULL,
  `Avalista` varchar(6) DEFAULT NULL,
  `AvalistaDoc` varchar(6) DEFAULT NULL,
  `AvalistaFono` varchar(6) DEFAULT NULL,
  `AvalistaDir` varchar(6) DEFAULT NULL,
  `LugarGiro` varchar(20) DEFAULT NULL,
  `TamLetra` int(11) DEFAULT NULL,
  `LargoPagina` int(11) DEFAULT NULL,
  `Impresora` varchar(80) DEFAULT NULL,
  `FechaAval` datetime DEFAULT NULL,
  `Valor` varchar(20) DEFAULT NULL,
  `numdoc` varchar(6) DEFAULT NULL,
  PRIMARY KEY (`Girador`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `plantilla_letra`
--

LOCK TABLES `plantilla_letra` WRITE;
/*!40000 ALTER TABLE `plantilla_letra` DISABLE KEYS */;
INSERT INTO `plantilla_letra` VALUES ('000010','004010','008010','012010','016010','020010','024010','028010','036010','040010','044010','048010','056010','060010','064010','068010','Lugar Giro',10,0,'EPSON Stylus C62 Series',NULL,NULL,'000000');
/*!40000 ALTER TABLE `plantilla_letra` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `plantilla_ncre`
--

DROP TABLE IF EXISTS `plantilla_ncre`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `plantilla_ncre` (
  `CodClie` varchar(6) NOT NULL DEFAULT '',
  `Cliente` varchar(6) DEFAULT NULL,
  `TDocClie` varchar(6) DEFAULT NULL,
  `RUC` varchar(6) DEFAULT NULL,
  `DirClie` varchar(6) DEFAULT NULL,
  `DistClie` varchar(6) DEFAULT NULL,
  `TelClie` varchar(6) DEFAULT NULL,
  `Contacto` varchar(6) DEFAULT NULL,
  `Fecha` varchar(6) DEFAULT NULL,
  `FechaVen` varchar(6) DEFAULT NULL,
  `NumNCre` varchar(6) DEFAULT NULL,
  `NumRefe` varchar(6) DEFAULT NULL,
  `NumPedi` varchar(6) DEFAULT NULL,
  `FPago` varchar(6) DEFAULT NULL,
  `CodVend` varchar(6) DEFAULT NULL,
  `Zona` varchar(6) DEFAULT NULL,
  `Moneda` varchar(6) DEFAULT NULL,
  `Observ` varchar(6) DEFAULT NULL,
  `Transp` varchar(6) DEFAULT NULL,
  `RucTransp` varchar(6) DEFAULT NULL,
  `DirTransp` varchar(6) DEFAULT NULL,
  `PlacaTransp` varchar(6) DEFAULT NULL,
  `DirPartida` varchar(6) DEFAULT NULL,
  `DirLlegada` varchar(6) DEFAULT NULL,
  `Peso` varchar(6) DEFAULT NULL,
  `NumItem` varchar(6) DEFAULT NULL,
  `CodArti` varchar(6) DEFAULT NULL,
  `DesArti` varchar(6) DEFAULT NULL,
  `Unidad` varchar(6) DEFAULT NULL,
  `Cantidad` varchar(6) DEFAULT NULL,
  `Valor` varchar(6) DEFAULT NULL,
  `Dcto1` varchar(6) DEFAULT NULL,
  `Dcto2` varchar(6) DEFAULT NULL,
  `IGVDet` varchar(6) DEFAULT NULL,
  `ISC` varchar(6) DEFAULT NULL,
  `SubTot` varchar(6) DEFAULT NULL,
  `Auxiliar1` varchar(6) DEFAULT NULL,
  `Auxiliar2` varchar(6) DEFAULT NULL,
  `NumLote` varchar(6) DEFAULT NULL,
  `FechaVenLote` varchar(6) DEFAULT NULL,
  `NumSerie` varchar(6) DEFAULT NULL,
  `ValVta` varchar(6) DEFAULT NULL,
  `ValExcento` varchar(6) DEFAULT NULL,
  `ValImp` varchar(6) DEFAULT NULL,
  `ValImpISC` varchar(6) DEFAULT NULL,
  `PorIGV` varchar(6) DEFAULT NULL,
  `IGV` varchar(6) DEFAULT NULL,
  `Total` varchar(6) DEFAULT NULL,
  `Letras` varchar(6) DEFAULT NULL,
  `Espaciado` int(11) DEFAULT NULL,
  `MaxItem` int(11) DEFAULT NULL,
  `TamLetra` int(11) DEFAULT NULL,
  `LargoPagina` int(11) DEFAULT NULL,
  `LargoMemo` int(11) DEFAULT NULL,
  `Impresora` varchar(80) DEFAULT NULL,
  `AfePerc` varchar(6) DEFAULT NULL,
  `PorPerc` varchar(6) DEFAULT NULL,
  `ValPerc` varchar(6) DEFAULT NULL,
  `Totpago` varchar(6) DEFAULT NULL,
  `UsuCodi` varchar(6) DEFAULT NULL,
  `VtaHora` varchar(45) DEFAULT NULL,
  `pagina` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`CodClie`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `plantilla_ncre`
--

LOCK TABLES `plantilla_ncre` WRITE;
/*!40000 ALTER TABLE `plantilla_ncre` DISABLE KEYS */;
/*!40000 ALTER TABLE `plantilla_ncre` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `plantilla_ndeb`
--

DROP TABLE IF EXISTS `plantilla_ndeb`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `plantilla_ndeb` (
  `CodClie` varchar(6) NOT NULL DEFAULT '',
  `Cliente` varchar(6) DEFAULT NULL,
  `TDocClie` varchar(6) DEFAULT NULL,
  `RUC` varchar(6) DEFAULT NULL,
  `DirClie` varchar(6) DEFAULT NULL,
  `DistClie` varchar(6) DEFAULT NULL,
  `TelClie` varchar(6) DEFAULT NULL,
  `Contacto` varchar(6) DEFAULT NULL,
  `Fecha` varchar(6) DEFAULT NULL,
  `FechaVen` varchar(6) DEFAULT NULL,
  `NumNDeb` varchar(6) DEFAULT NULL,
  `NumRefe` varchar(6) DEFAULT NULL,
  `NumPedi` varchar(6) DEFAULT NULL,
  `FPago` varchar(6) DEFAULT NULL,
  `CodVend` varchar(6) DEFAULT NULL,
  `Zona` varchar(6) DEFAULT NULL,
  `Moneda` varchar(6) DEFAULT NULL,
  `Observ` varchar(6) DEFAULT NULL,
  `Transp` varchar(6) DEFAULT NULL,
  `RucTransp` varchar(6) DEFAULT NULL,
  `DirTransp` varchar(6) DEFAULT NULL,
  `PlacaTransp` varchar(6) DEFAULT NULL,
  `DirPartida` varchar(6) DEFAULT NULL,
  `DirLlegada` varchar(6) DEFAULT NULL,
  `Peso` varchar(6) DEFAULT NULL,
  `NumItem` varchar(6) DEFAULT NULL,
  `CodArti` varchar(6) DEFAULT NULL,
  `DesArti` varchar(6) DEFAULT NULL,
  `Unidad` varchar(6) DEFAULT NULL,
  `Cantidad` varchar(6) DEFAULT NULL,
  `Valor` varchar(6) DEFAULT NULL,
  `Dcto1` varchar(6) DEFAULT NULL,
  `Dcto2` varchar(6) DEFAULT NULL,
  `IGVDet` varchar(6) DEFAULT NULL,
  `ISC` varchar(6) DEFAULT NULL,
  `SubTot` varchar(6) DEFAULT NULL,
  `Auxiliar1` varchar(6) DEFAULT NULL,
  `Auxiliar2` varchar(6) DEFAULT NULL,
  `NumLote` varchar(6) DEFAULT NULL,
  `FechaVenLote` varchar(6) DEFAULT NULL,
  `NumSerie` varchar(6) DEFAULT NULL,
  `ValVta` varchar(6) DEFAULT NULL,
  `ValExcento` varchar(6) DEFAULT NULL,
  `ValImp` varchar(6) DEFAULT NULL,
  `ValImpISC` varchar(6) DEFAULT NULL,
  `PorIGV` varchar(6) DEFAULT NULL,
  `IGV` varchar(6) DEFAULT NULL,
  `Total` varchar(6) DEFAULT NULL,
  `Letras` varchar(6) DEFAULT NULL,
  `Espaciado` int(11) DEFAULT NULL,
  `MaxItem` int(11) DEFAULT NULL,
  `TamLetra` int(11) DEFAULT NULL,
  `LargoPagina` int(11) DEFAULT NULL,
  `LargoMemo` int(11) DEFAULT NULL,
  `Impresora` varchar(80) DEFAULT NULL,
  `AfePerc` varchar(6) DEFAULT NULL,
  `PorPerc` varchar(6) DEFAULT NULL,
  `ValPerc` varchar(6) DEFAULT NULL,
  `Totpago` varchar(6) DEFAULT NULL,
  `VtaHora` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`CodClie`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `plantilla_ndeb`
--

LOCK TABLES `plantilla_ndeb` WRITE;
/*!40000 ALTER TABLE `plantilla_ndeb` DISABLE KEYS */;
/*!40000 ALTER TABLE `plantilla_ndeb` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `plantilla_nota`
--

DROP TABLE IF EXISTS `plantilla_nota`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `plantilla_nota` (
  `CodClie` varchar(6) NOT NULL DEFAULT '',
  `Cliente` varchar(6) DEFAULT NULL,
  `TDocClie` varchar(6) DEFAULT NULL,
  `RUC` varchar(6) DEFAULT NULL,
  `DirClie` varchar(6) DEFAULT NULL,
  `DistClie` varchar(6) DEFAULT NULL,
  `TelClie` varchar(6) DEFAULT NULL,
  `Contacto` varchar(6) DEFAULT NULL,
  `Fecha` varchar(6) DEFAULT NULL,
  `FechaVen` varchar(6) DEFAULT NULL,
  `NumNota` varchar(6) DEFAULT NULL,
  `NumRefe` varchar(6) DEFAULT NULL,
  `NumPedi` varchar(6) DEFAULT NULL,
  `FPago` varchar(6) DEFAULT NULL,
  `CodVend` varchar(6) DEFAULT NULL,
  `Zona` varchar(6) DEFAULT NULL,
  `Moneda` varchar(6) DEFAULT NULL,
  `Observ` varchar(6) DEFAULT NULL,
  `Transp` varchar(6) DEFAULT NULL,
  `RucTransp` varchar(6) DEFAULT NULL,
  `DirTransp` varchar(6) DEFAULT NULL,
  `PlacaTransp` varchar(6) DEFAULT NULL,
  `DirPartida` varchar(6) DEFAULT NULL,
  `DirLlegada` varchar(6) DEFAULT NULL,
  `Peso` varchar(6) DEFAULT NULL,
  `NumItem` varchar(6) DEFAULT NULL,
  `CodArti` varchar(6) DEFAULT NULL,
  `DesArti` varchar(6) DEFAULT NULL,
  `Unidad` varchar(6) DEFAULT NULL,
  `Cantidad` varchar(6) DEFAULT NULL,
  `Valor` varchar(6) DEFAULT NULL,
  `Dcto1` varchar(6) DEFAULT NULL,
  `Dcto2` varchar(6) DEFAULT NULL,
  `IGVDet` varchar(6) DEFAULT NULL,
  `ISC` varchar(6) DEFAULT NULL,
  `SubTot` varchar(6) DEFAULT NULL,
  `Auxiliar1` varchar(6) DEFAULT NULL,
  `Auxiliar2` varchar(6) DEFAULT NULL,
  `NumLote` varchar(6) DEFAULT NULL,
  `FechaVenLote` varchar(6) DEFAULT NULL,
  `NumSerie` varchar(6) DEFAULT NULL,
  `ValVta` varchar(6) DEFAULT NULL,
  `ValExcento` varchar(6) DEFAULT NULL,
  `ValImp` varchar(6) DEFAULT NULL,
  `ValImpISC` varchar(6) DEFAULT NULL,
  `PorIGV` varchar(6) DEFAULT NULL,
  `IGV` varchar(6) DEFAULT NULL,
  `Total` varchar(6) DEFAULT NULL,
  `Letras` varchar(6) DEFAULT NULL,
  `Espaciado` int(11) DEFAULT NULL,
  `MaxItem` int(11) DEFAULT NULL,
  `TamLetra` int(11) DEFAULT NULL,
  `LargoPagina` int(11) DEFAULT NULL,
  `LargoMemo` int(11) DEFAULT NULL,
  `Impresora` varchar(80) DEFAULT NULL,
  `AfePerc` varchar(6) DEFAULT NULL,
  `PorPerc` varchar(6) DEFAULT NULL,
  `ValPerc` varchar(6) DEFAULT NULL,
  `Totpago` varchar(6) DEFAULT NULL,
  `UsuCodi` varchar(6) DEFAULT NULL,
  `VtaHora` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`CodClie`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `plantilla_nota`
--

LOCK TABLES `plantilla_nota` WRITE;
/*!40000 ALTER TABLE `plantilla_nota` DISABLE KEYS */;
/*!40000 ALTER TABLE `plantilla_nota` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `plantilla_oserv`
--

DROP TABLE IF EXISTS `plantilla_oserv`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `plantilla_oserv` (
  `CodClie` varchar(6) NOT NULL DEFAULT '',
  `Cliente` varchar(6) DEFAULT NULL,
  `TDocClie` varchar(6) DEFAULT NULL,
  `RUC` varchar(6) DEFAULT NULL,
  `DirClie` varchar(6) DEFAULT NULL,
  `DistClie` varchar(6) DEFAULT NULL,
  `TelClie` varchar(6) DEFAULT NULL,
  `Contacto` varchar(6) DEFAULT NULL,
  `Fecha` varchar(6) DEFAULT NULL,
  `FechaVen` varchar(6) DEFAULT NULL,
  `NumNota` varchar(6) DEFAULT NULL,
  `NumRefe` varchar(6) DEFAULT NULL,
  `NumPedi` varchar(6) DEFAULT NULL,
  `FPago` varchar(6) DEFAULT NULL,
  `CodVend` varchar(6) DEFAULT NULL,
  `Zona` varchar(6) DEFAULT NULL,
  `Moneda` varchar(6) DEFAULT NULL,
  `Observ` varchar(6) DEFAULT NULL,
  `Transp` varchar(6) DEFAULT NULL,
  `RucTransp` varchar(6) DEFAULT NULL,
  `DirTransp` varchar(6) DEFAULT NULL,
  `PlacaTransp` varchar(6) DEFAULT NULL,
  `DirPartida` varchar(6) DEFAULT NULL,
  `DirLlegada` varchar(6) DEFAULT NULL,
  `Peso` varchar(6) DEFAULT NULL,
  `NumItem` varchar(6) DEFAULT NULL,
  `CodArti` varchar(6) DEFAULT NULL,
  `DesArti` varchar(6) DEFAULT NULL,
  `Unidad` varchar(6) DEFAULT NULL,
  `Cantidad` varchar(6) DEFAULT NULL,
  `Valor` varchar(6) DEFAULT NULL,
  `Dcto1` varchar(6) DEFAULT NULL,
  `Dcto2` varchar(6) DEFAULT NULL,
  `IGVDet` varchar(6) DEFAULT NULL,
  `ISC` varchar(6) DEFAULT NULL,
  `SubTot` varchar(6) DEFAULT NULL,
  `Auxiliar1` varchar(6) DEFAULT NULL,
  `Auxiliar2` varchar(6) DEFAULT NULL,
  `NumLote` varchar(6) DEFAULT NULL,
  `FechaVenLote` varchar(6) DEFAULT NULL,
  `NumSerie` varchar(6) DEFAULT NULL,
  `ValVta` varchar(6) DEFAULT NULL,
  `ValExcento` varchar(6) DEFAULT NULL,
  `ValImp` varchar(6) DEFAULT NULL,
  `ValImpISC` varchar(6) DEFAULT NULL,
  `PorIGV` varchar(6) DEFAULT NULL,
  `IGV` varchar(6) DEFAULT NULL,
  `Total` varchar(6) DEFAULT NULL,
  `Letras` varchar(6) DEFAULT NULL,
  `Espaciado` int(11) DEFAULT NULL,
  `MaxItem` int(11) DEFAULT NULL,
  `TamLetra` int(11) DEFAULT NULL,
  `LargoPagina` int(11) DEFAULT NULL,
  `LargoMemo` int(11) DEFAULT NULL,
  `Impresora` varchar(80) DEFAULT NULL,
  `AfePerc` varchar(6) DEFAULT NULL,
  `PorPerc` varchar(6) DEFAULT NULL,
  `ValPerc` varchar(6) DEFAULT NULL,
  `Totpago` varchar(6) DEFAULT NULL,
  `UsuCodi` varchar(6) DEFAULT NULL,
  `VtaHora` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`CodClie`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `plantilla_oserv`
--

LOCK TABLES `plantilla_oserv` WRITE;
/*!40000 ALTER TABLE `plantilla_oserv` DISABLE KEYS */;
/*!40000 ALTER TABLE `plantilla_oserv` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `plantilla_perc`
--

DROP TABLE IF EXISTS `plantilla_perc`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `plantilla_perc` (
  `CodClie` varchar(6) NOT NULL DEFAULT '',
  `Cliente` varchar(6) DEFAULT NULL,
  `TDocClie` varchar(6) DEFAULT NULL,
  `RUC` varchar(6) DEFAULT NULL,
  `DirClie` varchar(6) DEFAULT NULL,
  `DistClie` varchar(6) DEFAULT NULL,
  `TelClie` varchar(6) DEFAULT NULL,
  `Contacto` varchar(6) DEFAULT NULL,
  `Fecha` varchar(6) DEFAULT NULL,
  `FechaVen` varchar(6) DEFAULT NULL,
  `NumPerc` varchar(6) DEFAULT NULL,
  `NumRefe` varchar(6) DEFAULT NULL,
  `NumPedi` varchar(6) DEFAULT NULL,
  `FPago` varchar(6) DEFAULT NULL,
  `CodVend` varchar(6) DEFAULT NULL,
  `Zona` varchar(6) DEFAULT NULL,
  `Moneda` varchar(6) DEFAULT NULL,
  `Observ` varchar(6) DEFAULT NULL,
  `Peso` varchar(6) DEFAULT NULL,
  `Cantidad` varchar(6) DEFAULT NULL,
  `Valor` varchar(6) DEFAULT NULL,
  `Dcto1` varchar(6) DEFAULT NULL,
  `Dcto2` varchar(6) DEFAULT NULL,
  `IGVDet` varchar(6) DEFAULT NULL,
  `ISC` varchar(6) DEFAULT NULL,
  `SubTot` varchar(6) DEFAULT NULL,
  `Auxiliar1` varchar(6) DEFAULT NULL,
  `Auxiliar2` varchar(6) DEFAULT NULL,
  `NumLote` varchar(6) DEFAULT NULL,
  `FechaVenLote` varchar(6) DEFAULT NULL,
  `NumSerie` varchar(6) DEFAULT NULL,
  `ValVta` varchar(6) DEFAULT NULL,
  `ValExcento` varchar(6) DEFAULT NULL,
  `ValImp` varchar(6) DEFAULT NULL,
  `ValImpISC` varchar(6) DEFAULT NULL,
  `PorIGV` varchar(6) DEFAULT NULL,
  `IGV` varchar(6) DEFAULT NULL,
  `Total` varchar(6) DEFAULT NULL,
  `Letras` varchar(6) DEFAULT NULL,
  `Espaciado` int(11) DEFAULT NULL,
  `MaxItem` int(11) DEFAULT NULL,
  `TamLetra` int(11) DEFAULT NULL,
  `LargoPagina` int(11) DEFAULT NULL,
  `LargoMemo` int(11) DEFAULT NULL,
  `Impresora` varchar(80) DEFAULT NULL,
  `PerItem` varchar(6) DEFAULT NULL,
  `Tipo_Doc` varchar(6) DEFAULT NULL,
  `NumDocu` varchar(6) DEFAULT NULL,
  `Docfecha` varchar(6) DEFAULT NULL,
  `AfePerc` varchar(6) DEFAULT NULL,
  `PorPerc` varchar(6) DEFAULT NULL,
  `ValPerc` varchar(6) DEFAULT NULL,
  `Totpago` varchar(6) DEFAULT NULL,
  `TotImPe` varchar(6) DEFAULT NULL,
  `UsuCodi` varchar(6) DEFAULT NULL,
  `TotMonto` varchar(6) DEFAULT NULL,
  PRIMARY KEY (`CodClie`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `plantilla_perc`
--

LOCK TABLES `plantilla_perc` WRITE;
/*!40000 ALTER TABLE `plantilla_perc` DISABLE KEYS */;
INSERT INTO `plantilla_perc` VALUES ('010040','060040','010000','010060','010080','000000','000000','000000','600080','000000','600020','000000','000000','000000','000000','000000','000000','000000','000000','000000','000000','000000','000000','000000','000000','000000','000000','000000','000000','000000','000000','000000','000000','000000','000000','000000','000000','000000','000000',15,10,10,0,50,'Epson FX-880 on LPT1:','010120','050120','100120','200120','300120','400120','500120','600120','500400','000000','600400');
/*!40000 ALTER TABLE `plantilla_perc` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `procedencia`
--

DROP TABLE IF EXISTS `procedencia`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `procedencia` (
  `ProcCodi` char(2) NOT NULL,
  `ProcNomb` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`ProcCodi`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `procedencia`
--

LOCK TABLES `procedencia` WRITE;
/*!40000 ALTER TABLE `procedencia` DISABLE KEYS */;
INSERT INTO `procedencia` VALUES ('00','VARIOS');
/*!40000 ALTER TABLE `procedencia` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `procesos`
--

DROP TABLE IF EXISTS `procesos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `procesos` (
  `ProCodi` char(3) NOT NULL,
  `Pronomb` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`ProCodi`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `procesos`
--

LOCK TABLES `procesos` WRITE;
/*!40000 ALTER TABLE `procesos` DISABLE KEYS */;
INSERT INTO `procesos` VALUES ('001','GRUPOS'),('002','FAMILIAS'),('003','ARTICULOS'),('004','UNIDADES'),('005','CLIENTES'),('006','PROVEEDORES'),('007','ZONAS');
/*!40000 ALTER TABLE `procesos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `productos`
--

DROP TABLE IF EXISTS `productos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `productos` (
  `ID` int(11) NOT NULL,
  `ProCodi` char(20) NOT NULL,
  `famcodi` char(4) DEFAULT NULL,
  `grucodi` char(2) DEFAULT NULL,
  `marcodi` char(2) DEFAULT NULL,
  `ProCodi1` char(20) DEFAULT NULL,
  `ProNomb` varchar(255) DEFAULT NULL,
  `proesta` int(1) DEFAULT NULL,
  `prosto1` float DEFAULT NULL,
  `prosto2` float DEFAULT NULL,
  `prosto3` float DEFAULT NULL,
  `prosto4` float DEFAULT NULL,
  `prosto5` float DEFAULT NULL,
  `prosto6` float DEFAULT NULL,
  `prosto7` float DEFAULT NULL,
  `prosto8` float DEFAULT NULL,
  `prosto9` float DEFAULT NULL,
  `prosto10` float DEFAULT NULL,
  `unpcodi` char(3) DEFAULT NULL,
  `tiecodi` char(2) DEFAULT NULL,
  `moncodi` char(2) DEFAULT NULL,
  `provaco` float DEFAULT NULL,
  `proigco` float DEFAULT NULL,
  `ProPUCD` float DEFAULT NULL,
  `ProPUCS` float DEFAULT NULL,
  `ProMarg` float DEFAULT NULL,
  `ProPUVD` float DEFAULT NULL,
  `ProPUVS` float DEFAULT NULL,
  `ProMarg1` float DEFAULT NULL,
  `ProPUVS1` float DEFAULT NULL,
  `ProPUVD1` float DEFAULT NULL,
  `ProPeso` float DEFAULT NULL,
  `ProPerc` char(2) DEFAULT NULL,
  `Promini` float DEFAULT NULL,
  `proubic` varchar(35) DEFAULT NULL,
  `ProUltC` float DEFAULT NULL,
  `ProUltF` varchar(45) DEFAULT NULL,
  `proproms` float DEFAULT NULL,
  `propromd` float DEFAULT NULL,
  `proigvv` float DEFAULT NULL,
  `ProPUCL` float DEFAULT NULL,
  `ProDcto1` float DEFAULT NULL,
  `Prodcto2` float DEFAULT NULL,
  `prodcto3` float DEFAULT NULL,
  `ProIgv1` float DEFAULT NULL,
  `ProPerc1` float DEFAULT NULL,
  `Proobse` varchar(255) DEFAULT NULL,
  `proingre` varchar(255) DEFAULT NULL,
  `prouso` varchar(255) DEFAULT NULL,
  `ctacpra` char(20) DEFAULT NULL,
  `ctavta` char(20) DEFAULT NULL,
  `profoto` longblob,
  `ProSTem` float DEFAULT NULL,
  `ProcCodi` char(2) DEFAULT NULL,
  `User_Crea` varchar(45) DEFAULT NULL,
  `User_FCrea` datetime DEFAULT NULL,
  `User_ECrea` varchar(45) DEFAULT NULL,
  `User_Modi` varchar(45) DEFAULT NULL,
  `User_FModi` datetime DEFAULT NULL,
  `User_EModi` varchar(45) DEFAULT NULL,
  `UDelete` char(1) DEFAULT NULL,
  `BaseIGV` varchar(45) DEFAULT NULL,
  `ISC` float DEFAULT NULL,
  `empcodi` varchar(3) NOT NULL DEFAULT '001',
  `profoto2` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`ID`,`ProCodi`,`empcodi`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `productos`
--

LOCK TABLES `productos` WRITE;
/*!40000 ALTER TABLE `productos` DISABLE KEYS */;
/*!40000 ALTER TABLE `productos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `productos_prov`
--

DROP TABLE IF EXISTS `productos_prov`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `productos_prov` (
  `Id` char(8) NOT NULL,
  `PCCodi` char(5) NOT NULL,
  `ProPUCL` float DEFAULT NULL,
  `ProDcto1` float DEFAULT NULL,
  `ProDcto2` float DEFAULT NULL,
  `ProDcto3` float DEFAULT NULL,
  `ProIgv` float DEFAULT NULL,
  `ProPerc` float DEFAULT NULL,
  `ProPUCD` float DEFAULT NULL,
  `ProPUCS` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `productos_prov`
--

LOCK TABLES `productos_prov` WRITE;
/*!40000 ALTER TABLE `productos_prov` DISABLE KEYS */;
INSERT INTO `productos_prov` VALUES ('100078','00002',10,1,0,0,18,0,11.918,40.748),('100077','00002',15,1,0,0,18,0,17.877,61.121);
/*!40000 ALTER TABLE `productos_prov` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `prov_clientes`
--

DROP TABLE IF EXISTS `prov_clientes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `prov_clientes` (
  `EmpCodi` char(3) NOT NULL,
  `PCCodi` char(6) NOT NULL DEFAULT '',
  `TipCodi` char(1) NOT NULL,
  `PCNomb` varchar(100) DEFAULT NULL,
  `PCRucc` varchar(12) DEFAULT NULL,
  `PCDire` varchar(255) DEFAULT NULL,
  `PCDist` varchar(65) DEFAULT NULL,
  `PCTel1` varchar(55) DEFAULT NULL,
  `PCTel2` varchar(15) DEFAULT NULL,
  `PCMail` varchar(65) DEFAULT NULL,
  `PCCont` varchar(100) DEFAULT NULL,
  `PCCMail` varchar(45) DEFAULT NULL,
  `VenCodi` varchar(4) DEFAULT NULL,
  `ZonCodi` varchar(4) DEFAULT NULL,
  `TdoCodi` varchar(4) DEFAULT NULL,
  `PCDocu` varchar(15) DEFAULT NULL,
  `MonCodi` char(2) DEFAULT NULL,
  `PCAfPe` float DEFAULT NULL,
  `LisCodi` int(2) DEFAULT NULL,
  `PCLine` float DEFAULT NULL,
  `PCAfli` tinyint(4) DEFAULT NULL,
  `PCDeud` float DEFAULT NULL,
  `PCANom` varchar(80) DEFAULT NULL,
  `PCARuc` varchar(45) DEFAULT NULL,
  `PCADir` varchar(80) DEFAULT NULL,
  `PCATel` varchar(45) DEFAULT NULL,
  `PCAEma` varchar(45) DEFAULT NULL,
  `TDocCodi` varchar(1) DEFAULT NULL,
  `PConCodi` char(5) DEFAULT NULL,
  `PCFoto` longblob,
  `User_Crea` varchar(45) DEFAULT NULL,
  `User_FCrea` datetime DEFAULT NULL,
  `User_ECrea` varchar(45) DEFAULT NULL,
  `User_Modi` varchar(45) DEFAULT NULL,
  `User_FModi` datetime DEFAULT NULL,
  `User_EModi` varchar(45) DEFAULT NULL,
  `UDelete` char(1) DEFAULT NULL,
  PRIMARY KEY (`EmpCodi`,`PCCodi`,`TipCodi`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `prov_clientes`
--

LOCK TABLES `prov_clientes` WRITE;
/*!40000 ALTER TABLE `prov_clientes` DISABLE KEYS */;
INSERT INTO `prov_clientes` VALUES ('001','00000','C','\"ASERRADERO EL ORIENTE GFJ\" S.A.C.','20541363662','JR. CIRO LANDA NRO. 208 - JUNIN JAUJA YAUYOS','200101',NULL,NULL,NULL,NULL,NULL,'1OFI','0100','6','20541363662','01',NULL,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,NULL,'2018-12-11 19:11:56',NULL,'FONSECA','2018-12-26 19:30:59','MATRIX',NULL),('001','00000','P','QUITA DEL ALMACEN',NULL,NULL,'200101',NULL,NULL,NULL,NULL,NULL,'1OFI','0100',NULL,NULL,'01',NULL,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'0',NULL,NULL,NULL,'2018-12-11 19:11:56',NULL,NULL,'2018-12-11 19:11:56',NULL,NULL),('001','00001','P','PROVEEDORES VARIOS','.',NULL,'200101',NULL,NULL,NULL,NULL,NULL,'1OFI','0100',NULL,NULL,'01',NULL,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'0',NULL,NULL,NULL,'2018-12-11 19:11:56',NULL,NULL,'2018-12-11 19:11:56',NULL,NULL),('001','00002','C','JUAN CARLOS ALCARRAZ CONTRERAS','42532753',NULL,'',NULL,'',NULL,NULL,NULL,'1OFI','0100','1',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1',NULL,NULL,'DEMO','2019-05-06 20:58:33','ip-172-31-81-196',NULL,'2019-05-06 20:58:33',NULL,''),('001','00002','P','GRIFO\'S SAMIRA SOCIEDAD ANONIMA CERRADA','20487741486','CAR.JAEN - SAN IGNACIO KM. 40 CAS. DE LA FLORESTA (CENTRO POBLADO SHUMBA ALTO) CAJAMARCA - JAEN - BELLAVISTA','',NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'FONSECA','2019-08-20 11:07:35','ip-172-31-81-196',NULL,'2019-08-20 11:07:35',NULL,''),('001','00003','C','LUIS DAVID ALEJANDRO LAURENTE','43278119',NULL,'',NULL,'',NULL,NULL,NULL,'1OFI','0100','1',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1',NULL,NULL,'DEMO','2019-05-06 20:58:40','ip-172-31-81-196',NULL,'2019-05-06 20:58:40',NULL,''),('001','00003','P','HOTEL SUEÑO DE ANGELES S.A.C.','20525971709','AV. PROY. SAN MARTIN DE TOURS NRO. 0 CENT. SECHURA PIURA - SECHURA - SECHURA','',NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'FONSECA','2019-09-25 18:15:46','ip-172-31-81-196',NULL,'2019-09-25 18:15:46',NULL,''),('001','00004','C','LUISA MADOLYN ALVAREZ BENAUTE','42264899',NULL,'',NULL,'',NULL,NULL,NULL,'1OFI','0100','1',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1',NULL,NULL,'DEMO','2019-05-06 20:58:58','ip-172-31-81-196',NULL,'2019-05-06 20:58:58',NULL,''),('001','00005','C','GRUPO HUARCAYA Y PEREZ S.A.C.','20601937531',NULL,'',NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'DEMO','2019-05-06 21:04:36','ip-172-31-81-196',NULL,'2019-05-06 21:04:36',NULL,''),('001','00006','C','RODSEBRU  EMPRESA INDIVIDUAL DE RESPONSABILIDAD LIMITADA','20526026455','AV. MARCELINO CHAMPAGNAT NRO. 510 URB. SANTA ROSA PIURA - SULLANA - SULLANA','',NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'FONSECA','2019-05-06 21:24:10','ip-172-31-81-196',NULL,'2019-05-06 21:24:10',NULL,''),('001','00007','C','ASESORES Y CONSULTORES CALIDAD & JONAS S.R.L.','20570687795','CAL.MARIETA NRO. 243 SEC. MORRO SOLAR (2DO.PISO) CAJAMARCA - JAEN - JAEN','',NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'FONSECA','2019-05-06 21:24:30','ip-172-31-81-196',NULL,'2019-05-06 21:24:30',NULL,''),('001','00008','C','CENTROS AUDITIVOS UYARI S.A.C.','20602221831','JR. ASAMBLEA NRO. 298 CERCADO AYACUCHO - HUAMANGA - AYACUCHO','',NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'FONSECA','2019-05-06 21:24:44','ip-172-31-81-196',NULL,'2019-05-06 21:24:44',NULL,''),('001','00009','C','ARTURO VELASCO ALVAREZ','45982456',NULL,'',NULL,'',NULL,NULL,NULL,'1OFI','0100','1',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1',NULL,NULL,'DEMO','2019-05-06 22:11:38','ip-172-31-81-196',NULL,'2019-05-06 22:11:38',NULL,''),('001','00010','C','MARIO JOSE VELASQUEZ HERRERA','41166949',NULL,'',NULL,'',NULL,NULL,NULL,'1OFI','0100','1',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1',NULL,NULL,'DEMO','2019-05-06 22:11:48','ip-172-31-81-196',NULL,'2019-05-06 22:11:48',NULL,''),('001','00011','C','RAUL ALEXANDER VELITA ZORRILLA','44394261',NULL,'',NULL,'',NULL,NULL,NULL,'1OFI','0100','1',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1',NULL,NULL,'DEMO','2019-05-06 22:11:56','ip-172-31-81-196',NULL,'2019-05-06 22:11:56',NULL,''),('001','00012','C','FEDERICO OMAR VILCA CORDOVA','42776351','mza. A Lt. 1 El Bosque III carabayllo  lima',NULL,NULL,'',NULL,NULL,NULL,'1OFI','0100','1',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1',NULL,NULL,'DEMO','2019-05-06 22:12:03','ip-172-31-81-196','VENTAS','2019-10-04 14:37:19','ip-172-31-81-196',''),('001','00013','C','WAGNER ZAVALETA VILCHEZ','43504814',NULL,'',NULL,'',NULL,NULL,NULL,'1OFI','0100','1',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1',NULL,NULL,'DEMO','2019-05-06 22:12:09','ip-172-31-81-196',NULL,'2019-05-06 22:12:09',NULL,''),('001','00014','C','LUIS ANGEL ZEGARRA RENGIFO','45841096',NULL,'',NULL,'',NULL,NULL,NULL,'1OFI','0100','1',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1',NULL,NULL,'DEMO','2019-05-06 22:17:45','ip-172-31-81-196',NULL,'2019-05-06 22:17:45',NULL,''),('001','00015','C','COMPLEJO EDUCATIVO EMANUEL S.R.L.','20600198140','AV. PERU NRO. S/N (3CDRA COL LA ALDEA-CDRA13 PERU-S72722172) JUNIN - SATIPO - MAZAMARI','120501',NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'DEMO','2019-05-06 22:20:06','ip-172-31-81-196',NULL,'2019-05-06 22:20:06',NULL,''),('001','00016','C','CONSTRUCTORA LA SANTA CRUZ S.A.C.','20601322090','JR. RICARDO PALMA NRO. 478 (ESPALDA DE MINISTERIO DE TRANSPORTES) AYACUCHO - HUAMANGA - JESUS NAZARENO','',NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'DEMO','2019-05-06 22:20:31','ip-172-31-81-196',NULL,'2019-05-06 22:20:31',NULL,''),('001','00017','C','GRUPO OPEN SOLUTIONS S.R.L.','20491656841','JR. CONTAMANA NRO. 390 BR SAN JOSE CAJAMARCA - CAJAMARCA - CAJAMARCA','',NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'DEMO','2019-05-09 15:12:56','ip-172-31-81-196',NULL,'2019-05-09 15:12:56',NULL,''),('001','00018','C','CHERO DIOSES ANGEL ALEXANDER','10400956435','Paita','',NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'DEMO','2019-05-09 16:55:50','ip-172-31-81-196',NULL,'2019-05-09 16:55:50',NULL,''),('001','00019','C','JUAN MIGUEL KININ TIWI','46262086',NULL,'',NULL,'',NULL,NULL,NULL,'1OFI','0100','1',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1',NULL,NULL,'FONSECA','2019-05-30 22:45:59','ip-172-31-81-196',NULL,'2019-05-30 22:45:59',NULL,''),('001','00020','C','NEGOCIO Y ALOJAMIENTO QUINTA GUZMAN E.I.R.L.','20542259117','JR. DAMIAN NAJAR NRO. 295 SAN MARTIN - MOYOBAMBA - MOYOBAMBA','',NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'DEMO','2019-08-13 12:31:57','ip-172-31-81-196',NULL,'2019-08-13 12:31:57',NULL,''),('001','00021','C','SERVICORT PLEGADOS S.A.C.','20562778463','MZA. 4 LOTE. 3 SEC. NUEVO HORIZONTE (PAMPLONA ALTA) LIMA - LIMA - SAN JUAN DE MIRAFLORES','',NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'FONSECA','2019-09-20 12:26:36','ip-172-31-81-196',NULL,'2019-09-20 12:26:36',NULL,''),('002','00001','P','RED CIENTIFICA PERUANA','20111451592','JR. GONZALES PRADA NRO. 585 LIMA - LIMA - SURQUILLO','',NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'FONSECA','2019-11-22 08:41:21','ip-172-31-81-196',NULL,'2019-11-22 08:41:21',NULL,''),('002','00013','C','.VARIOS','.','AV. LAS TORRES MZA. K LOTE. 6-C URB. LA CAPITANA DE HUACHIPA (A 3 CDRAS DEL PARQUE HUACHIPA) LIMA - LIMA - LURIGANCHO','150118',NULL,'',NULL,NULL,NULL,'1OFI','0100','0',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'0',NULL,NULL,'FONSECA','2019-02-18 13:26:11','ip-172-31-81-196','FONSECA','2019-04-23 21:54:35','ip-172-31-81-196',''),('002','00014','C','GAMESCO PERU S.A.C.','20601901600','MZA. B-2 LOTE. 7 P.J. TUPAC AMARU DE VILLA LIMA - LIMA - CHORRILLOS','150108',NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'FONSECA','2019-02-18 13:26:31','ip-172-31-81-196',NULL,'2019-02-18 13:26:31',NULL,''),('002','00015','C','GEFRANKA S.R.L.','20475986645','AV. LOS FAISANES NRO. 109 URB. LA CAMPINA (TAMBIEN 111.ALT.MCDO.SAN MARTIN DE PORRE) LIMA - LIMA - CHORRILLOS','',NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'FONSECA','2019-02-18 13:26:51','ip-172-31-81-196',NULL,'2019-02-18 13:26:51',NULL,''),('002','00016','C','ACEROS VILERO S.A.C.','20566058601','MZA. A LOTE. 8 SEC. 2 GRUPO 19 LIMA - LIMA - VILLA EL SALVADOR','150142',NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'FONSECA','2019-02-18 13:27:12','ip-172-31-81-196',NULL,'2019-02-18 13:27:12',NULL,''),('002','00017','C','4G PAPER WHITE S.A.C','20603419287','AV. ALFREDO BENAVIDES NRO. 5384 INT. D-10 URB. LAS GARDENIAS ET. UNO (COSTADO DE UNIVERSIDAD RICARDO PALMA) LIMA - LIMA - SANTIAGO DE SURCO','150140',NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'FONSECA','2019-02-18 13:27:52','ip-172-31-81-196',NULL,'2019-02-18 13:27:52',NULL,''),('002','00018','C','AFERROTUB S.A.C.','20556932931','AV. SAN JUAN MZA. H-1 LOTE. 21 URB. TUPAC AMARU DE VILLA (A 3 CRDAS DE LA POSTA MEDICA TUPAC AMARU) LIMA - LIMA - CHORRILLOS','150108',NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'FONSECA','2019-02-18 13:28:11','ip-172-31-81-196',NULL,'2019-02-18 13:28:11',NULL,''),('002','00019','C','HUAMAN LAPA MILAGROS JASMIN','10427682132','-','150133',NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'FONSECA','2019-02-18 13:28:36','ip-172-31-81-196',NULL,'2019-02-18 13:28:36',NULL,''),('002','00020','C','ACERO LA ECONOMICA EMPRESA INDIVIDUAL DE RESPONSABILIDAD LIMITADA','20603335687','----CAMANA NRO. 171 CHOSICA LIMA - LIMA - LURIGANCHO','150118',NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'FONSECA','2019-02-18 13:29:03','ip-172-31-81-196',NULL,'2019-02-18 13:29:03',NULL,''),('002','00021','C','LIDERCORT H & L S.A.C.','20553173991','AV. DEFENSORES DE LIMA NRO. 619 P.J. EL BRILLANTE LIMA - LIMA - SAN JUAN DE MIRAFLORES','150133',NULL,'',NULL,NULL,NULL,'1OFI','0100','0',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'0',NULL,NULL,'FONSECA','2019-02-18 13:29:26','ip-172-31-81-196','CAMBUR','2019-04-24 20:14:05','ip-172-31-81-196',''),('002','00022','C','INVERSIONES PRADA S.A.C.','20520758829','AV. MIGUEL IGLESIAS MZA. C LOTE. 11 COO. URANMARCA (AV. MIG IG. PASANDO TORRES O PAMPAS SJ) LIMA - LIMA - SAN JUAN DE MIRAFLORES','',NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'FONSECA','2019-02-18 13:29:41','ip-172-31-81-196',NULL,'2019-02-18 13:29:41',NULL,''),('002','00023','C','EMPRESA PERUANA DE SEGURIDAD S.A.C.','20531663421','JR. CESAR VALLEJO NRO. 792 URB. MERCURIO (MZ Z LOTE 23 - I ETAPA) LIMA - LIMA - LOS OLIVOS','150117',NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'FONSECA','2019-02-18 13:30:02','ip-172-31-81-196',NULL,'2019-02-18 13:30:02',NULL,''),('002','00024','C','RODRIGUEZ VELASQUEZ SARA','10178845573','-','130101',NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'FONSECA','2019-02-18 13:30:27','ip-172-31-81-196',NULL,'2019-02-18 13:30:27',NULL,''),('002','00025','C','REPRESENTACIONES HIDALGO SAC','20481678880','AV. AMERICA NORTE NRO. 293 LA INTENDENCIA LA LIBERTAD - TRUJILLO - TRUJILLO','',NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'FONSECA','2019-02-18 13:30:41','ip-172-31-81-196',NULL,'2019-02-18 13:30:41',NULL,''),('002','00026','C','HIDALGO BUSTAMANTE KATHERINE','10412942987','-','',NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'FONSECA','2019-02-18 13:30:53','ip-172-31-81-196',NULL,'2019-02-18 13:30:53',NULL,''),('002','00027','C','DISTRIBUIDORA CARMELITA S.R.L.','20492644880','AV. RICARDO BENTIN NRO. 699 (CDRA 9 AV ALCAZAR Y TARAPACA) LIMA - LIMA - RIMAC','RIMAC',NULL,'','alvaradojeri@gmail.com',NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'FONSECA','2019-02-18 13:31:08','ip-172-31-81-196','FONSECA','2019-11-08 07:50:12','ip-172-31-81-196',''),('002','00028','C','ZUÑIGA LAYME PRUDENCIO JOHN','10086267581','-','150137',NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'FONSECA','2019-02-18 13:31:22','ip-172-31-81-196','FONSECA','2019-02-18 13:31:45','ip-172-31-81-196',''),('002','00029','C','ZUÑIGA RIVERA ANDREW NATHAN','10459491894','AV GUILLERMO DE LA FUENTE  # 207 COMAS',NULL,NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'FONSECA','2019-02-18 13:31:58','ip-172-31-81-196','VENTAS','2019-11-11 16:39:59','ip-172-31-81-196',''),('002','00030','C','INVERSIONES PAPELERA GRAFHURT S.A.C','20546422373','AV. LOS HEROES 585-B NRO. 585B URB. ZONA K (COCOSTADO MCDO COOP CIUDAD DE DIOS) LIMA - LIMA - SAN JUAN DE MIRAFLORES','150133',NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'FONSECA','2019-02-18 13:32:20','ip-172-31-81-196',NULL,'2019-02-18 13:32:20',NULL,''),('002','00031','C','NEGOCIACIONES PAPELERA HURTADO S.A.C','20451482972','AV. LOS HEROES NRO. 609A (ALT. MCDO CIUDAD DE DIOS Y ANTIGUA MUNIC) LIMA - LIMA - SAN JUAN DE MIRAFLORES','',NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'FONSECA','2019-02-18 13:32:44','ip-172-31-81-196',NULL,'2019-02-18 13:32:44',NULL,''),('002','00032','C','NEUMATICAR S.A.C.','20555831294','AV. ALFREDO MENDIOLA NRO. 1395 URB. PALAO 1RA ETAPA (FTE AL BRITANICO GRIFO SERVITOR) LIMA - LIMA - SAN MARTIN DE PORRES','150135',NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'FONSECA','2019-02-18 13:33:11','ip-172-31-81-196',NULL,'2019-02-18 13:33:11',NULL,''),('002','00033','C','CARRION ZEVALLOS OMAR','10422663857','-','110113',NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'FONSECA','2019-02-18 13:33:32','ip-172-31-81-196',NULL,'2019-02-18 13:33:32',NULL,''),('002','00034','C','PRINTEC CAD E.I.R.L.','20553982759','AV. BENAVIDES NRO. 5388 INT. 128 URB. LAS GARDENIAS (GALERIA RICHI) LIMA - LIMA - SANTIAGO DE SURCO','150140',NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'FONSECA','2019-02-18 13:34:03','ip-172-31-81-196',NULL,'2019-02-18 13:34:03',NULL,''),('002','00035','C','CORPORACION METALURGICA ALEMANA S.A.C.','20603616929','AV. LOS HEROES NRO. 1931 LIMA - LIMA - VILLA MARIA DEL TRIUNFO','150143',NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'FONSECA','2019-02-18 13:34:26','ip-172-31-81-196',NULL,'2019-02-18 13:34:26',NULL,''),('002','00036','C','GRIFALFA FERNANDEZ LUIS FELIX','10076812191','-','110113',NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'FONSECA','2019-02-18 13:34:44','ip-172-31-81-196',NULL,'2019-02-18 13:34:44',NULL,''),('002','00037','C','INVERSIONES MCV SOCIEDAD ANONIMA CERRADA','20505711069','MZA. A LOTE. 21 URB. PRADERAS DE PARIACHI 3ETP (ALT KM 15.3 DE CARRETERA CENTRAL) LIMA - LIMA - ATE','',NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'FONSECA','2019-02-18 13:35:03','ip-172-31-81-196',NULL,'2019-02-18 13:35:03',NULL,''),('002','00038','C','MERECO INVERSIONES S.A.C.','20600309341','----GRUPO 22A MZA. A LOTE. 13 SEC. 3 (CRUCE DE JC MARIATEGUI Y MICAELA BASTIDA) LIMA - LIMA - VILLA EL SALVADOR','150142',NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'FONSECA','2019-02-18 13:35:24','ip-172-31-81-196',NULL,'2019-02-18 13:35:24',NULL,''),('002','00039','C','MAXI BIGBAG S.A.C.','20557825127','JR. FELIPE SANTIAGO CRESPO NRO. 348 URB. EL TREBOL LIMA - LIMA - SAN LUIS','150134',NULL,'','maxibigbag_sac@hotmail.com',NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'FONSECA','2019-02-18 13:35:51','ip-172-31-81-196','FONSECA','2019-08-10 12:34:38','ip-172-31-81-196',''),('002','00040','C','AGRIFRUT SANTA ROSA SOCIEDAD ANONIMA CERRADA','20601708371','CAL.DERECHA NRO. 850 (COSTADO DE OTASS) LIMA - HUARAL - HUARAL','150601',NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'FONSECA','2019-02-18 13:36:10','ip-172-31-81-196',NULL,'2019-02-18 13:36:10',NULL,''),('002','00041','C','EQUIPOS & SERVICIOS HIDRAULICOS UMIÑA EMPRESA INDIVIDUAL DE RESPONSABILIDAD LIMITADA','20492120627','MZA. D LOTE. 9 A.H. BUENOS AIRES (SAN GABRIEL- ESP. COMPLEJO A.A. CACERES) LIMA - LIMA - VILLA MARIA DEL TRIUNFO','150143',NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'FONSECA','2019-02-18 13:36:37','ip-172-31-81-196',NULL,'2019-02-18 13:36:37',NULL,''),('002','00042','C','WILDER ANIBAL FONSECA BLAS','32301376',NULL,'',NULL,'',NULL,NULL,NULL,'1OFI','0100','1',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1',NULL,NULL,'FONSECA','2019-02-23 22:15:04','ip-172-31-81-196',NULL,'2019-02-23 22:15:04',NULL,''),('002','00043','C','METALES G & R PERFILES S.A.C.','20553997357','MZA. K LOTE. 1 A.H. BRISAS DE PACHAC ST2 PAR3 LIMA - LIMA - VILLA EL SALVADOR','VILLA EL SALVADOR',NULL,'','gyrperfilessac@hotmail.com',NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'FONSECA','2019-03-07 23:11:00','ip-172-31-81-196','FONSECA','2019-04-05 19:49:24','ip-172-31-81-196',''),('002','00044','C','TORIBIO ANYARIN INJANTE EIRL','20101576176','JR. PUNO NRO. 406 INT. 203- URB. LIMA CERCADO LIMA - LIMA - LIMA','',NULL,'','normita.collantes@gmail.com',NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'FONSECA','2019-03-19 20:26:32','ip-172-31-81-196',NULL,'2019-03-19 20:26:32',NULL,''),('002','00045','C','TUBOS Y PERFILES ZARATE S.A.C.','20603953798','AV. 3 MZA. E LOTE. 30 COO. VIRGEN DE COCHARCAS (ESPALDA DE HOSP. DE LA SOLIDARIDAD-VES) LIMA - LIMA - VILLA EL SALVADOR','',NULL,'','tuperzasac@gmail.com',NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'FONSECA','2019-04-06 17:26:55','ip-172-31-81-196',NULL,'2019-04-06 17:26:55',NULL,''),('002','00046','C','REPUESTOS CARLITOS S.A.C.','20602986242','AV. LAS TORRES MZA. K LOTE. 1 LIMA - LIMA - LURIGANCHO','',NULL,'','juancarlos271091@gmail.com',NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'FONSECA','2019-05-11 22:32:29','ip-172-31-81-196',NULL,'2019-05-11 22:32:29',NULL,''),('002','00047','C','FIERROS METALICOS EL ARCO S.A.C.','20545613345','AV. GRAL MIGUEL IGLESIAS MZA. B LOTE. 15 COO. URANMARCA LIMA - LIMA - SAN JUAN DE MIRAFLORES','',NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'FONSECA','2019-07-10 08:40:30','ip-172-31-81-196',NULL,'2019-07-10 08:40:30',NULL,''),('002','00048','C','GRUPO ANTRIAL PERU S.A.C.','20603868391','CAL.D MZA. 3 LOTE. 32 COO. VIV AMERICA LIMA - LIMA - SAN JUAN DE MIRAFLORES','',NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'FONSECA','2019-08-01 16:33:48','ip-172-31-81-196',NULL,'2019-08-01 16:33:48',NULL,''),('002','00049','C','CORPORACION EL TORO S.A.C.','20605142185','AV. TUPAC AMARU NRO. 3303 URB. CHACRA CERRO LIMA - LIMA - COMAS',NULL,NULL,'','eltoroasto01@gmail.com',NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'FONSECA','2019-08-30 12:19:01','ip-172-31-81-196','FONSECA','2019-08-30 12:24:16','ip-172-31-81-196',''),('002','00050','C','FASSEI SERVICIO DE DOBLES S.R.L .','20602268072','MZA. U LOTE. 2 URB. HUERTOS DE LURIN LIMA - LIMA - LURIN','',NULL,'','fassei.servicios@gmail.com',NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'FONSECA','2019-09-26 12:23:00','ip-172-31-81-196',NULL,'2019-09-26 12:23:00',NULL,''),('002','00051','C','CORPORACION FIVAL S.A.C.','20392458922','AV. DE LOS PATRIOTAS NRO. 443 DPTO. 201 LIMA - LIMA - SAN MIGUEL','',NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'FONSECA','2019-09-28 08:59:51','ip-172-31-81-196',NULL,'2019-09-28 08:59:51',NULL,''),('002','00052','C','ACEROS Y SERVICIOS J & R E.I.R.L.','20565355946','CAL.SIRIO NRO. 149 URB. SOL DE VITARTE SECTOR G (ALTURA SOLDADURA) LIMA - LIMA - ATE','',NULL,'','acerosyserviciosjr@hotmail.com',NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'FONSECA','2019-10-13 11:46:27','ip-172-31-81-196',NULL,'2019-10-13 11:46:27',NULL,''),('002','00053','C','ACEROS Y PERFILES RC SAC','20556340171','AV. LAS TORRES MZA. K LOTE. 6-C URB. LA CAPITANA DE HUACHIPA (A 3 CDRAS DEL PARQUE HUACHIPA) LIMA - LIMA - LURIGANCHO','',NULL,'','aceros.perfiles.rc@gmail.com',NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'FONSECA','2019-10-18 14:18:43','ip-172-31-81-196',NULL,'2019-10-18 14:18:43',NULL,''),('002','00054','C','ACERMET E.I.R.L.','20536377876','MZA. D LOTE. 18 A.H. LOS ROSALES DE PRO (ALT. OVALO DE INFANTAS) LIMA - LIMA - LOS OLIVOS','',NULL,'','acermet_1@hotmail.com',NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'FONSECA','2019-11-04 18:56:43','ip-172-31-81-196',NULL,'2019-11-04 18:56:43',NULL,''),('002','00055','C','COMERCIAL J & G SOCIEDAD ANONIMA CERRADA','20538531619','CAL.33 NRO. 3268 URB. EL PACIFICO 2DA ET (AV UNIVERSITARIA Y ANTUNEZ DE MAYOLO) LIMA - LIMA - SAN MARTIN DE PORRES','150135',NULL,'','jaimemontoya.251@gmail.com',NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'FONSECA','2019-11-08 07:53:22','ip-172-31-81-196',NULL,'2019-11-08 07:53:22',NULL,''),('002','00056','C','GIROS INTERNACIONALES EL FORTIN S.A.C.','20605379801','MZA. G LOTE. 19 A.V. RICARDO PALMA PAMPAS DE SAN JUAN LIMA - LIMA - SAN JUAN DE MIRAFLORES','150133',NULL,'','Giroselfortin@gmail.com',NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'FONSECA','2019-11-08 08:04:10','ip-172-31-81-196',NULL,'2019-11-08 08:04:10',NULL,''),('002','00057','C','ZUÑIGA RIVERA MATTHEW ALEXANDER','10778065377','PJ. C NRO. S/N INT. 5 ---- MCDO. DE PRODUCTORES SANTA ANITA - LIMA','150137',NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'FONSECA','2019-11-19 10:34:56','ip-172-31-81-196',NULL,'2019-11-19 10:34:56',NULL,''),('002','00058','C','FEDEMACONSTRUCCION EL SOL E.I.R.L.','20538671976','AV. GUARDIA CIVIL NRO. 524 URB. LA CAMPIÑA ZONA CUATRO LIMA - LIMA - CHORRILLOS','',NULL,'','fedemaconstruccionelsol@hotmail.com',NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'FONSECA','2019-12-11 10:42:27','ip-172-31-81-196',NULL,'2019-12-11 10:42:27',NULL,''),('002','00059','C','CORPORACION SAINFO E.I.R.L. - SAINFO E.I.R.L.','20604067899','CAL.9 MZA. Y LOTE. 31 A.H. 1 DE JUNIO (COMERCIAL ALEMANA) LIMA - LIMA - SAN JUAN DE MIRAFLORES','120425',NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'FONSECA','2019-12-11 12:35:18','ip-172-31-81-196',NULL,'2019-12-11 12:35:18',NULL,''),('002','00060','C','NEGOCIOS SERVICIOS Y LOGISTICA INTEGRAL S.A.C','20509663484','MZA. A LOTE. 3 URB. HUERTOS DE VILLENA (PASANDO RIO LURIN) LIMA - LIMA - PACHACAMAC','',NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'FONSECA','2019-12-16 12:33:17','ip-172-31-81-196',NULL,'2019-12-16 12:33:17',NULL,''),('002','00061','C','SAMUEL AMADOR ALANIA SABINO','23150497',NULL,'',NULL,'',NULL,NULL,NULL,'1OFI','0100','1',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1',NULL,NULL,'FONSECA','2020-01-14 16:56:23','MATRIX',NULL,'2020-01-14 16:56:23',NULL,''),('002','00062','C','MARCELO ARIAS LEIVA','21104701',NULL,'',NULL,'',NULL,NULL,NULL,'1OFI','0100','1',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1',NULL,NULL,'FONSECA','2020-01-15 16:08:27','MATRIX',NULL,'2020-01-15 16:08:27',NULL,''),('002','00063','C','DNI ADAN SANTOS ARISMENDI BUSTAMANTE','25529875',NULL,'',NULL,'',NULL,NULL,NULL,'1OFI','0100','1',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1',NULL,NULL,'FONSECA','2020-01-15 16:17:23','MATRIX',NULL,'2020-01-15 16:17:23',NULL,''),('002','00064','C','JULIO SINCHE ROMERO','19955936',NULL,'',NULL,'',NULL,NULL,NULL,'1OFI','0100','1',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1',NULL,NULL,'FONSECA','2020-01-20 11:13:56','MATRIX',NULL,'2020-01-20 11:13:56',NULL,''),('003','00013','C','.VARIOS','.',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1OFI',NULL,'0',NULL,'01',NULL,10,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'0',NULL,NULL,NULL,NULL,NULL,'FONSECA','2019-04-23 21:54:35','ip-172-31-81-196',NULL),('003','00014','C','WILDER ANIBAL FONSECA BLAS','32301376',NULL,'',NULL,'',NULL,NULL,NULL,'1OFI','0100','1',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1',NULL,NULL,'CAMBUR','2019-04-23 18:57:09','ip-172-31-81-196',NULL,'2019-04-23 18:57:09',NULL,''),('003','00015','C','JOSEFINA HERNANDEZ','19724653',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-23 20:54:05',NULL,NULL,'2019-04-23 20:54:05',NULL,NULL),('003','00016','C','YENRY SILVA','27139857',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-23 20:54:05',NULL,NULL,'2019-04-23 20:54:05',NULL,NULL),('003','00017','C','JESSIKA SANCHEZ','20098113',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-23 20:54:06',NULL,NULL,'2019-04-23 20:54:06',NULL,NULL),('003','00018','C','YOEL CRUCES','15629129',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-24 15:23:40',NULL,NULL,'2019-04-24 15:23:40',NULL,NULL),('003','00019','C','MARGARITA BURGOS','28435732',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-24 15:23:41',NULL,NULL,'2019-04-24 15:23:41',NULL,NULL),('003','00020','C','ELVIS YAJURE','16415192',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-24 15:23:41',NULL,NULL,'2019-04-24 15:23:41',NULL,NULL),('003','00021','C','RENZO CASTILLO',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1OFI',NULL,'0',NULL,'01',NULL,10,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'0',NULL,NULL,NULL,'2019-04-24 15:23:41',NULL,'CAMBUR','2019-04-24 20:14:05','ip-172-31-81-196',NULL),('003','00022','C','INGRID TORREALBA','21295366',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-24 15:23:41',NULL,NULL,'2019-04-24 15:23:41',NULL,NULL),('003','00023','C','DEIVIS BOLIVAR','16099199',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-24 15:23:42',NULL,NULL,'2019-04-24 15:23:42',NULL,NULL),('003','00024','C','JHON POLO','18662442',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-24 15:23:42',NULL,NULL,'2019-04-24 15:23:42',NULL,NULL),('003','00025','C','GIOVANNI AGUILERA','8963228',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-24 15:23:42',NULL,NULL,'2019-04-24 15:23:42',NULL,NULL),('003','00026','C','RICARDO ROA','23412440',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-24 15:23:42',NULL,NULL,'2019-04-24 15:23:42',NULL,NULL),('003','00027','C','NESTOR UMBRIA','11324234',NULL,'RIMAC',NULL,NULL,'alvaradojeri@gmail.com',NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-24 15:23:43',NULL,'FONSECA','2019-11-08 07:50:12','ip-172-31-81-196',NULL),('003','00028','C','GABRIELA GONZALEZ','25146449',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-24 15:23:43',NULL,NULL,'2019-04-24 15:23:43',NULL,NULL),('003','00029','C','REYNALDO GAMARRA','22337096','AV GUILLERMO DE LA FUENTE  # 207 COMAS',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-24 15:23:43',NULL,'VENTAS','2019-11-11 16:39:59','ip-172-31-81-196',NULL),('003','00030','C','DOHYRALITH URBANEJA','21389104',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-24 15:23:43',NULL,NULL,'2019-04-24 15:23:43',NULL,NULL),('003','00031','C','YELITZA JIMENEZ','12666067',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-24 15:23:43',NULL,NULL,'2019-04-24 15:23:43',NULL,NULL),('003','00032','C','CARLOS LOPEZ','23815246',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-24 15:23:44',NULL,NULL,'2019-04-24 15:23:44',NULL,NULL),('003','00033','C','YASBETH TORRES','16382573',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-24 15:23:44',NULL,NULL,'2019-04-24 15:23:44',NULL,NULL),('003','00034','C','YORDIS HERRERA','19004343',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-24 15:23:44',NULL,NULL,'2019-04-24 15:23:44',NULL,NULL),('003','00035','C','MARIA PEREZ','8276605',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-24 15:23:44',NULL,NULL,'2019-04-24 15:23:44',NULL,NULL),('003','00036','C','EGLA RODRIGUEZ','18032497',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-24 15:23:44',NULL,NULL,'2019-04-24 15:23:44',NULL,NULL),('003','00037','C','REGINO BURGOS','21162106',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-24 15:23:45',NULL,NULL,'2019-04-24 15:23:45',NULL,NULL),('003','00038','C','JOSE MORON','21201084',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-24 15:23:45',NULL,NULL,'2019-04-24 15:23:45',NULL,NULL),('003','00039','C','KELLY BATISTA','26094567',NULL,'150134',NULL,NULL,'maxibigbag_sac@hotmail.com',NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-24 15:23:45',NULL,'FONSECA','2019-08-10 12:34:38','ip-172-31-81-196',NULL),('003','00040','C','JORGE CUENTAS','32807392',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-24 15:23:45',NULL,NULL,'2019-04-24 15:23:45',NULL,NULL),('003','00041','C','HERNAN ZAMBRANO','17391635',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-24 15:23:45',NULL,NULL,'2019-04-24 15:23:45',NULL,NULL),('003','00042','C','FERNANDO MARCANO','16485983',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-24 15:23:46',NULL,NULL,'2019-04-24 15:23:46',NULL,NULL),('003','00043','C','MARIA RIVAS','19233488',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-24 15:23:46',NULL,NULL,'2019-04-24 15:23:46',NULL,NULL),('003','00044','C','LEORANA CARABALLO','21176027',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-24 15:23:46',NULL,NULL,'2019-04-24 15:23:46',NULL,NULL),('003','00045','C','VANESSA GOMEZ','139822216',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-24 15:23:46',NULL,NULL,'2019-04-24 15:23:46',NULL,NULL),('003','00046','C','BRYAN SERRANO','23622801',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-24 15:23:46',NULL,NULL,'2019-04-24 15:23:46',NULL,NULL),('003','00047','C','DANIELA CAPELLA','26225401',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-24 15:23:47',NULL,NULL,'2019-04-24 15:23:47',NULL,NULL),('003','00048','C','WILLIAM LOPEZ','13488318',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-24 15:23:47',NULL,NULL,'2019-04-24 15:23:47',NULL,NULL),('003','00049','C','ERICK GIMENEZ','16964593',NULL,NULL,NULL,NULL,'eltoroasto01@gmail.com',NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-24 15:23:47',NULL,'FONSECA','2019-08-30 12:24:16','ip-172-31-81-196',NULL),('003','00050','C','VIVIAN FERNANDEZ','9746863',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-24 15:23:47',NULL,NULL,'2019-04-24 15:23:47',NULL,NULL),('003','00051','C','ELBANO GUERRERO','8103257',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-24 15:23:47',NULL,NULL,'2019-04-24 15:23:47',NULL,NULL),('003','00052','C','LUIS MENDOZA','15480146',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-24 15:23:48',NULL,NULL,'2019-04-24 15:23:48',NULL,NULL),('003','00053','C','LUIS GONZALEZ','17050174',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-24 15:23:48',NULL,NULL,'2019-04-24 15:23:48',NULL,NULL),('003','00054','C','JESUS LIRA','17788839',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-24 15:23:48',NULL,NULL,'2019-04-24 15:23:48',NULL,NULL),('003','00055','C','MOISES BELYS','20741057',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-24 19:13:46',NULL,NULL,'2019-04-24 19:13:46',NULL,NULL),('003','00056','C','MARLY RODRIGUEZ','24679845',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-24 19:13:46',NULL,NULL,'2019-04-24 19:13:46',NULL,NULL),('003','00057','C','JULIANY CONTRERAS','14646941',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-24 19:13:46',NULL,NULL,'2019-04-24 19:13:46',NULL,NULL),('003','00058','C','JESUS NAVARRO','24208924',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-24 19:13:46',NULL,NULL,'2019-04-24 19:13:46',NULL,NULL),('003','00059','C','KEVIN ONCOY','71380423',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-24 19:13:47',NULL,NULL,'2019-04-24 19:13:47',NULL,NULL),('003','00060','C','DIEGO MONTIEL','20281398',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-24 19:13:47',NULL,NULL,'2019-04-24 19:13:47',NULL,NULL),('003','00061','C','MARIA ZAMORA','22872486',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-24 19:13:47',NULL,NULL,'2019-04-24 19:13:47',NULL,NULL),('003','00062','C','MAYERLINE GUERRA','118719012',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-24 19:13:47',NULL,NULL,'2019-04-24 19:13:47',NULL,NULL),('003','00063','C','NORELIS GUILLEN','10240147',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-24 19:13:48',NULL,NULL,'2019-04-24 19:13:48',NULL,NULL),('003','00064','C','SERVICIOS GENERALES SEHU SCRL','22872699',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-24 19:13:48',NULL,'VENTAS','2019-10-09 13:11:30','ip-172-31-81-196',NULL),('003','00065','C','HAILEY WILLIAMS','19703565',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-24 19:13:48',NULL,NULL,'2019-04-24 19:13:48',NULL,NULL),('003','00066','C','DOUGLAS PEREZ','18809043',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-24 19:13:48',NULL,NULL,'2019-04-24 19:13:48',NULL,NULL),('003','00067','C','XANDERLI VELASQUEZ','19195189',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-24 19:13:49',NULL,NULL,'2019-04-24 19:13:49',NULL,NULL),('003','00068','C','YELBIS BLANCO','17513730',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-24 19:13:49',NULL,NULL,'2019-04-24 19:13:49',NULL,NULL),('003','00069','C','CECILIA PEREZ','14610188',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-24 19:13:49',NULL,NULL,'2019-04-24 19:13:49',NULL,NULL),('003','00070','C','MARYORI CAMACHO',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1OFI',NULL,'0',NULL,'01',NULL,10,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'0',NULL,NULL,NULL,'2019-04-24 19:13:49',NULL,'FONSECA','2019-04-26 03:37:01','ip-172-31-81-196',NULL),('003','00071','C','MARIA MATA','163825',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-24 19:13:50',NULL,NULL,'2019-04-24 19:13:50',NULL,NULL),('003','00072','C','OSCAR ARANGUIBEL','18772154',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-24 19:13:50',NULL,NULL,'2019-04-24 19:13:50',NULL,NULL),('003','00073','C','MARIA ALEJANDRA OSORIO','20138325',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-24 19:13:50',NULL,NULL,'2019-04-24 19:13:50',NULL,NULL),('003','00074','C','GIBSON CUENCA','27315026',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-24 19:13:51',NULL,NULL,'2019-04-24 19:13:51',NULL,NULL),('003','00075','C','YUSMELY LANDAETA','16435505',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-24 19:13:51',NULL,NULL,'2019-04-24 19:13:51',NULL,NULL),('003','00076','C','ARELIS FERNANDEZ','13950949',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-24 19:13:51',NULL,NULL,'2019-04-24 19:13:51',NULL,NULL),('003','00077','C','GLENDA ZAMBRANO','15419283',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-24 19:13:52',NULL,NULL,'2019-04-24 19:13:52',NULL,NULL),('003','00078','C','CARLOS ESCOBAR','17286095',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-24 19:13:52',NULL,NULL,'2019-04-24 19:13:52',NULL,NULL),('003','00079','C','LUISERGGI HURTADO','18590072',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-24 19:13:52',NULL,NULL,'2019-04-24 19:13:52',NULL,NULL),('003','00080','C','MARIA GABRIELA DAVILA','20960604',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-24 19:13:52',NULL,NULL,'2019-04-24 19:13:52',NULL,NULL),('003','00081','C','EDILIA VELASQUEZ','15005632',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-24 19:13:53',NULL,NULL,'2019-04-24 19:13:53',NULL,NULL),('003','00082','C','JOHANNA GUTIERREZ','14825289',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-24 19:13:53',NULL,NULL,'2019-04-24 19:13:53',NULL,NULL),('003','00083','C','MIGUEL ATILIO','13528767',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-24 19:13:53',NULL,NULL,'2019-04-24 19:13:53',NULL,NULL),('003','00084','C','DAIMAR RAMOS','19482398',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-24 19:13:54',NULL,NULL,'2019-04-24 19:13:54',NULL,NULL),('003','00085','C','GILSEN MORA','15777228',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-24 19:13:54',NULL,NULL,'2019-04-24 19:13:54',NULL,NULL),('003','00086','C','LOLYMAR QUINTERO','6653652',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-24 19:13:54',NULL,NULL,'2019-04-24 19:13:54',NULL,NULL),('003','00087','C','ALFREDO PILCO','41519805','jiron brescia 413 comas lima lima',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-24 19:13:54',NULL,'VENTAS','2019-10-28 11:55:36','ip-172-31-81-196',NULL),('003','00088','C','BERNARDO CASTILLO','21269079',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-24 19:13:55',NULL,NULL,'2019-04-24 19:13:55',NULL,NULL),('003','00089','C','IRYCES TERAN','25625297',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-24 19:13:55',NULL,NULL,'2019-04-24 19:13:55',NULL,NULL),('003','00090','C','CRISTIAN RICAPA','60495387',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-24 19:13:55',NULL,NULL,'2019-04-24 19:13:55',NULL,NULL),('003','00091','C','JOSE MANUEL DE LA RIVA AGUERO','10224948',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-24 19:13:55',NULL,NULL,'2019-04-24 19:13:55',NULL,NULL),('003','00092','C','EDGYMAR GONZALEZ','13956786',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-24 19:13:56',NULL,NULL,'2019-04-24 19:13:56',NULL,NULL),('003','00093','C','MARIANNY MORENO','19853020',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-24 19:13:56',NULL,NULL,'2019-04-24 19:13:56',NULL,NULL),('003','00094','C','LAURA BRACAMONTE','21301697',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-24 19:13:56',NULL,NULL,'2019-04-24 19:13:56',NULL,NULL),('003','00095','C','LUISANA FUCIL','16559749',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-24 19:13:57',NULL,NULL,'2019-04-24 19:13:57',NULL,NULL),('003','00096','C','RICHARD GARCIA','25618001',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-24 19:13:57',NULL,NULL,'2019-04-24 19:13:57',NULL,NULL),('003','00097','C','ELIANI ALVIZU','21404882',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-24 19:13:57',NULL,NULL,'2019-04-24 19:13:57',NULL,NULL),('003','00098','C','JESUS SANCHEZ','20786178',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-24 19:13:57',NULL,NULL,'2019-04-24 19:13:57',NULL,NULL),('003','00099','C','AMERICO BRICEÑO','13000312',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-24 19:13:58',NULL,NULL,'2019-04-24 19:13:58',NULL,NULL),('003','00100','C','NATHALY GUERRERO GARCIA','19335013',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-24 19:13:58',NULL,NULL,'2019-04-24 19:13:58',NULL,NULL),('003','00101','C','EVELIN VALERO','26094992',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-24 19:13:58',NULL,NULL,'2019-04-24 19:13:58',NULL,NULL),('003','00102','C','KELLY PIÑANGO','20872628',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-24 19:13:58',NULL,NULL,'2019-04-24 19:13:58',NULL,NULL),('003','00103','C','SANDRA FERNANDEZ','11083337',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-24 19:13:59',NULL,NULL,'2019-04-24 19:13:59',NULL,NULL),('003','00104','C','LEONEL URDANETA','21402280',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-24 21:22:34',NULL,NULL,'2019-04-24 21:22:34',NULL,NULL),('003','00105','C','ENMILYS GARCIA','25879235',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-24 21:22:35',NULL,NULL,'2019-04-24 21:22:35',NULL,NULL),('003','00106','C','FELIX OJEDA','21640153',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-24 21:22:35',NULL,NULL,'2019-04-24 21:22:35',NULL,NULL),('003','00107','C','VICTOR RODRIGUEZ','21195701',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-24 21:22:35',NULL,NULL,'2019-04-24 21:22:35',NULL,NULL),('003','00108','C','EDWING SANTAMARIA','21060668',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-24 21:22:36',NULL,NULL,'2019-04-24 21:22:36',NULL,NULL),('003','00109','C','LUIS JOSE RUIZ','108197051',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-24 21:22:36',NULL,NULL,'2019-04-24 21:22:36',NULL,NULL),('003','00110','C','MAIBELIN RODRIGUEZ','21116973',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-24 21:22:36',NULL,NULL,'2019-04-24 21:22:36',NULL,NULL),('003','00111','C','FREDDY MEDINA','173683',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-24 21:22:36',NULL,NULL,'2019-04-24 21:22:36',NULL,NULL),('003','00112','C','JUAN ESCALONA','19495230',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-24 21:22:37',NULL,NULL,'2019-04-24 21:22:37',NULL,NULL),('003','00113','C','ISBELIA ZERPA','19433546',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-24 21:22:37',NULL,NULL,'2019-04-24 21:22:37',NULL,NULL),('003','00114','C','JOSE JESUS ROJAS PONCE','16274435',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-24 21:22:38',NULL,NULL,'2019-04-24 21:22:38',NULL,NULL),('003','00115','C','ANTHONY GABRIEL GUEVARA FERNÁNDEZ','26137109',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-24 21:22:38',NULL,NULL,'2019-04-24 21:22:38',NULL,NULL),('003','00116','C','AGUSTIN ROBLES','46303972',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-24 21:22:38',NULL,NULL,'2019-04-24 21:22:38',NULL,NULL),('003','00117','C','GUSTAVO URBINA','27421313',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-24 21:22:39',NULL,NULL,'2019-04-24 21:22:39',NULL,NULL),('003','00118','C','JESUS CASTELLANOS','22942659',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-24 21:22:39',NULL,NULL,'2019-04-24 21:22:39',NULL,NULL),('003','00119','C','MARIA NEUDECK','23712079',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-25 14:34:32',NULL,NULL,'2019-04-25 14:34:32',NULL,NULL),('003','00120','C','GABRIELA JAIMES','23535682',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-25 14:34:33',NULL,NULL,'2019-04-25 14:34:33',NULL,NULL),('003','00121','C','GENESIS PEROZO','24917407',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-25 14:34:33',NULL,NULL,'2019-04-25 14:34:33',NULL,NULL),('003','00122','C','ALEJANDRO LUQUE','101824172',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-25 14:34:33',NULL,NULL,'2019-04-25 14:34:33',NULL,NULL),('003','00123','C','EUDALYS ADRIANZA','19824685',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-25 14:34:34',NULL,NULL,'2019-04-25 14:34:34',NULL,NULL),('003','00124','C','ISSAC MANZANILLA','73649387',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-25 14:34:34',NULL,NULL,'2019-04-25 14:34:34',NULL,NULL),('003','00125','C','KARLA PABON','20011102',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-25 14:34:35',NULL,NULL,'2019-04-25 14:34:35',NULL,NULL),('003','00126','C','JUAN CARLOS MONTENEGRO','18846127',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-25 14:34:35',NULL,NULL,'2019-04-25 14:34:35',NULL,NULL),('003','00127','C','VICTOR ARTEAGA','19818711',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-25 14:34:35',NULL,NULL,'2019-04-25 14:34:35',NULL,NULL),('003','00128','C','JHOANNY OCHOA','19723390',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-25 14:34:35',NULL,NULL,'2019-04-25 14:34:35',NULL,NULL),('003','00129','C','ALVIN SOSA','20267755',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-25 14:34:35',NULL,NULL,'2019-04-25 14:34:35',NULL,NULL),('003','00130','C','ERNESTO CORZO','18228925',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-25 14:34:36',NULL,NULL,'2019-04-25 14:34:36',NULL,NULL),('003','00131','C','JUAN LEAL','20296962',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-25 15:38:24',NULL,NULL,'2019-04-25 15:38:24',NULL,NULL),('003','00132','C','ALI GARCIA','10854040',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-25 15:38:24',NULL,NULL,'2019-04-25 15:38:24',NULL,NULL),('003','00133','C','JOHAN ORDOÑEZ','16873290',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-25 15:38:24',NULL,NULL,'2019-04-25 15:38:24',NULL,NULL),('003','00134','C','ELEANA BRITO','8373749',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-25 15:38:25',NULL,NULL,'2019-04-25 15:38:25',NULL,NULL),('003','00135','C','ERIMAR DIAZ','20639100',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-25 15:38:25',NULL,NULL,'2019-04-25 15:38:25',NULL,NULL),('003','00136','C','JOSSELIA VEGAS','22380123',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-25 15:38:26',NULL,NULL,'2019-04-25 15:38:26',NULL,NULL),('003','00137','C','JESUS RONDON','93292087',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-25 15:38:26',NULL,NULL,'2019-04-25 15:38:26',NULL,NULL),('003','00138','C','LIBANESA DOS REIS','119188448',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-25 15:38:26',NULL,NULL,'2019-04-25 15:38:26',NULL,NULL),('003','00139','C','FELIPE RODRIGUEZ','24847102',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-25 15:38:26',NULL,NULL,'2019-04-25 15:38:26',NULL,NULL),('003','00140','C','MAIRELYS JIMENEZ','24354346',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-25 15:38:27',NULL,NULL,'2019-04-25 15:38:27',NULL,NULL),('003','00141','C','ROMEL ZAMBRANO','18339355',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-25 15:38:27',NULL,NULL,'2019-04-25 15:38:27',NULL,NULL),('003','00142','C','ELEYDI TORRES','20110300',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-25 15:38:27',NULL,NULL,'2019-04-25 15:38:27',NULL,NULL),('003','00143','C','YANEIBIS BLANCO','19304191',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-25 15:38:27',NULL,NULL,'2019-04-25 15:38:27',NULL,NULL),('003','00144','C','MIGUELIS LUCES','11514551',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-25 15:38:28',NULL,NULL,'2019-04-25 15:38:28',NULL,NULL),('003','00145','C','YETZABETH GIL',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1OFI',NULL,'0',NULL,'01',NULL,10,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'0',NULL,NULL,NULL,'2019-04-25 15:38:28',NULL,'FONSECA','2019-04-26 03:38:20','ip-172-31-81-196',NULL),('003','00146','C','MARIO DORIVAL','43552382',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-25 15:38:28',NULL,NULL,'2019-04-25 15:38:28',NULL,NULL),('003','00147','C','MARIA CELESTE MEJIAS','9383830',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-25 15:38:28',NULL,NULL,'2019-04-25 15:38:28',NULL,NULL),('003','00148','C','ANABEL RAMIREZ','14015214',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-25 15:38:29',NULL,NULL,'2019-04-25 15:38:29',NULL,NULL),('003','00149','C','NARDI MENDOZA','11184144',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-25 15:38:29',NULL,NULL,'2019-04-25 15:38:29',NULL,NULL),('003','00150','C','YANUTH CARDOZO','20844822',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-25 15:38:29',NULL,NULL,'2019-04-25 15:38:29',NULL,NULL),('003','00151','C','ERIX MELO','13095241',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-25 15:38:29',NULL,NULL,'2019-04-25 15:38:29',NULL,NULL),('003','00152','C','JESUS DELGADO','16578423',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-25 15:38:30',NULL,NULL,'2019-04-25 15:38:30',NULL,NULL),('003','00153','C','ABIGAIL RAMON MOSQUERA PALENCIA','19823517',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-25 15:38:30',NULL,NULL,'2019-04-25 15:38:30',NULL,NULL),('003','00154','C','DORIS MANZANEDA','138303121',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-25 15:38:31',NULL,NULL,'2019-04-25 15:38:31',NULL,NULL),('003','00155','C','WILMER TREJO','56887979',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-25 15:38:31',NULL,NULL,'2019-04-25 15:38:31',NULL,NULL),('003','00156','C','MAOLY HERESI','18836324',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-25 15:38:31',NULL,NULL,'2019-04-25 15:38:31',NULL,NULL),('003','00157','C','ABRIL YDROGO','88399957',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-25 15:38:31',NULL,NULL,'2019-04-25 15:38:31',NULL,NULL),('003','00158','C','MILETXY TOVAR','14701804',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-25 15:38:31',NULL,NULL,'2019-04-25 15:38:31',NULL,NULL),('003','00159','C','MARIA SEGOVIA','765433324',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-25 15:38:32',NULL,NULL,'2019-04-25 15:38:32',NULL,NULL),('003','00160','C','DELIANA GARCIA','25624229',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-25 15:38:32',NULL,NULL,'2019-04-25 15:38:32',NULL,NULL),('003','00161','C','CIRILO CARDENAS','9644130',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-25 15:38:32',NULL,NULL,'2019-04-25 15:38:32',NULL,NULL),('003','00162','C','GABRIEL ALCALA','18362997',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-25 15:38:33',NULL,NULL,'2019-04-25 15:38:33',NULL,NULL),('003','00163','C','PABLO JOSÉ JIMÉNEZ LINARES','18689903',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-25 15:38:33',NULL,NULL,'2019-04-25 15:38:33',NULL,NULL),('003','00164','C','GLORIMAR YANEZ','76543556',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-25 15:38:33',NULL,NULL,'2019-04-25 15:38:33',NULL,NULL),('003','00165','C','WALESKA BELLO','23202741',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-25 15:38:33',NULL,NULL,'2019-04-25 15:38:33',NULL,NULL),('003','00166','C','ELADIO FUENTES','15578180',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-25 15:38:34',NULL,NULL,'2019-04-25 15:38:34',NULL,NULL),('003','00167','C','ERIKA','38753',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-25 15:38:34',NULL,NULL,'2019-04-25 15:38:34',NULL,NULL),('003','00168','C','DIGNA ARROYO','16685094',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-25 15:38:35',NULL,NULL,'2019-04-25 15:38:35',NULL,NULL),('003','00169','C','DARIANNY BRICEÑO','26629822',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-25 15:38:36',NULL,NULL,'2019-04-25 15:38:36',NULL,NULL),('003','00170','C','GISMAR PALOMO','136754822',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-25 15:38:36',NULL,NULL,'2019-04-25 15:38:36',NULL,NULL),('003','00171','C','MOISES SANCHEZ','7324999',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-25 15:38:36',NULL,NULL,'2019-04-25 15:38:36',NULL,NULL),('003','00172','C','ARGENIS MORENO','25919055',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-25 15:38:37',NULL,NULL,'2019-04-25 15:38:37',NULL,NULL),('003','00173','C','WLADIMIR PEREZ','22983260',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-25 15:38:38',NULL,NULL,'2019-04-25 15:38:38',NULL,NULL),('003','00174','C','GENESIS SILVA','27139859',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-25 15:38:38',NULL,NULL,'2019-04-25 15:38:38',NULL,NULL),('003','00175','C','OSCAR GOMEZ OJEDA','9950182',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-25 15:38:38',NULL,NULL,'2019-04-25 15:38:38',NULL,NULL),('003','00176','C','NAYRA PEREZ','17976104',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-25 15:38:39',NULL,NULL,'2019-04-25 15:38:39',NULL,NULL),('003','00177','C','GUSTAVO HUMBERTO','2498',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-25 15:38:39',NULL,NULL,'2019-04-25 15:38:39',NULL,NULL),('003','00178','C','MIGUEL CASTILLO','19112012',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-25 15:38:39',NULL,NULL,'2019-04-25 15:38:39',NULL,NULL),('003','00179','C','YONATHAN MENDOZA','20780648',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-25 15:38:40',NULL,NULL,'2019-04-25 15:38:40',NULL,NULL),('003','00180','C','PILAR ROCCA','48941214',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-25 15:38:40',NULL,NULL,'2019-04-25 15:38:40',NULL,NULL),('003','00181','C','VANESSA CHARANY','16573373',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-25 15:38:40',NULL,NULL,'2019-04-25 15:38:40',NULL,NULL),('003','00182','C','JULIO VASQUEZ','24311475',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-25 15:38:40',NULL,NULL,'2019-04-25 15:38:40',NULL,NULL),('003','00183','C','TOMAS TORRES','16382572',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-25 15:38:41',NULL,NULL,'2019-04-25 15:38:41',NULL,NULL),('003','00184','C','JUAN FRANCO','19989224',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-25 15:38:41',NULL,NULL,'2019-04-25 15:38:41',NULL,NULL),('003','00185','C','DAYMARI LINARES','14929023',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-25 15:38:41',NULL,NULL,'2019-04-25 15:38:41',NULL,NULL),('003','00186','C','LUIS LANDAETA','26480624',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-25 15:38:42',NULL,NULL,'2019-04-25 15:38:42',NULL,NULL),('003','00187','C','JUNIOR RODRIGUEZ','20099411',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-25 15:38:42',NULL,NULL,'2019-04-25 15:38:42',NULL,NULL),('003','00188','C','MARIA JACQUELINE','104278224',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-25 15:38:42',NULL,NULL,'2019-04-25 15:38:42',NULL,NULL),('003','00189','C','GERMAN MILLAN','17010430',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-25 15:38:42',NULL,NULL,'2019-04-25 15:38:42',NULL,NULL),('003','00190','C','WILIA DIAZ','8824800',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-25 15:38:43',NULL,NULL,'2019-04-25 15:38:43',NULL,NULL),('003','00191','C','YONATHAN GALINDO','23028210',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-25 19:48:13',NULL,NULL,'2019-04-25 19:48:13',NULL,NULL),('003','00192','C','RICHARD CALZADILLA','17337796',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-25 19:48:13',NULL,NULL,'2019-04-25 19:48:13',NULL,NULL),('003','00193','C','RICARDO CORRALES','16443259',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-25 19:48:14',NULL,NULL,'2019-04-25 19:48:14',NULL,NULL),('003','00194','C','GENESIS MANZINI','45657657',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-25 19:48:14',NULL,NULL,'2019-04-25 19:48:14',NULL,NULL),('003','00195','C','GEOVALDY GIMENEZ','17172473',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-25 19:48:14',NULL,NULL,'2019-04-25 19:48:14',NULL,NULL),('003','00196','C','DIOSANYELIS POLANCO','20443122',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-25 19:48:15',NULL,NULL,'2019-04-25 19:48:15',NULL,NULL),('003','00197','C','STEFI PEREZ GIL','19967482',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-25 19:48:15',NULL,NULL,'2019-04-25 19:48:15',NULL,NULL),('003','00198','C','ALEXABET GUERRERO','19332801',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-25 19:48:15',NULL,NULL,'2019-04-25 19:48:15',NULL,NULL),('003','00199','C','OMAR ESCALANTE','16907842',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-25 19:48:15',NULL,NULL,'2019-04-25 19:48:15',NULL,NULL),('003','00200','C','ANFERNNI TINEO','25504847',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-25 19:48:16',NULL,NULL,'2019-04-25 19:48:16',NULL,NULL),('003','00201','C','ELSY BELEN','4278',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-25 19:48:16',NULL,NULL,'2019-04-25 19:48:16',NULL,NULL),('003','00202','C','MAURICETT JIMENEZ','10782704',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-25 19:48:17',NULL,NULL,'2019-04-25 19:48:17',NULL,NULL),('003','00203','C','RHONNY GUILLEN','13563454',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-25 19:48:18',NULL,NULL,'2019-04-25 19:48:18',NULL,NULL),('003','00204','C','KLEYVER LANDAETA','22344333',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-25 19:48:18',NULL,NULL,'2019-04-25 19:48:18',NULL,NULL),('003','00205','C','DAYANA GUILLEN','112272643',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-25 19:48:18',NULL,NULL,'2019-04-25 19:48:18',NULL,NULL),('003','00206','C','ALEX FARIAS','18512258',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-25 19:48:19',NULL,NULL,'2019-04-25 19:48:19',NULL,NULL),('003','00207','C','JOSE BARRIOS','19658706',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-25 19:48:19',NULL,NULL,'2019-04-25 19:48:19',NULL,NULL),('003','00208','C','ANYERLIN GONZALEZ','21115788',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-25 19:48:19',NULL,NULL,'2019-04-25 19:48:19',NULL,NULL),('003','00209','C','CARMEN MORENO','12152621',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-25 19:48:20',NULL,NULL,'2019-04-25 19:48:20',NULL,NULL),('003','00210','C','CLAVEL MENDOZA','17164300',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-25 19:48:20',NULL,NULL,'2019-04-25 19:48:20',NULL,NULL),('003','00211','C','JORGE VILCHEZ','22838158',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-25 19:48:21',NULL,NULL,'2019-04-25 19:48:21',NULL,NULL),('003','00212','C','JOSIMAR QUISPE','4146479',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-25 19:48:21',NULL,NULL,'2019-04-25 19:48:21',NULL,NULL),('003','00213','C','CARMELO TORRES','14581221',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-25 19:48:21',NULL,NULL,'2019-04-25 19:48:21',NULL,NULL),('003','00214','C','HORACIO NOVOA','10281538',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-25 19:48:22',NULL,NULL,'2019-04-25 19:48:22',NULL,NULL),('003','00215','C','LIGMARY AGUILAR','14432581',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-25 19:48:22',NULL,NULL,'2019-04-25 19:48:22',NULL,NULL),('003','00216','C','HILDA CALLADUI','47930404',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-25 19:48:22',NULL,NULL,'2019-04-25 19:48:22',NULL,NULL),('003','00217','C','GENESIS TOYO','24788685',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-25 19:48:23',NULL,NULL,'2019-04-25 19:48:23',NULL,NULL),('003','00218','C','ANDREINA FARFAN','20958594',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-25 19:48:23',NULL,NULL,'2019-04-25 19:48:23',NULL,NULL),('003','00219','C','NELSON DELGADO',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1OFI',NULL,'0',NULL,'01',NULL,10,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'0',NULL,NULL,NULL,'2019-04-25 19:48:23',NULL,'FONSECA','2019-04-26 03:37:54','ip-172-31-81-196',NULL),('003','00220','C','GEORGINA PAHUARA','23521001',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-26 18:43:19',NULL,NULL,'2019-04-26 18:43:19',NULL,NULL),('003','00221','C','HECTOR HUAMASH','41253828',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-26 18:43:19',NULL,NULL,'2019-04-26 18:43:19',NULL,NULL),('003','00222','C','JOSE REYES','20503973',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-26 18:43:19',NULL,NULL,'2019-04-26 18:43:19',NULL,NULL),('003','00223','C','YETZABETH GIL','3453896',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-26 18:43:20',NULL,NULL,'2019-04-26 18:43:20',NULL,NULL),('003','00224','C','ARON ALMENAR','20413193',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-26 18:43:20',NULL,NULL,'2019-04-26 18:43:20',NULL,NULL),('003','00225','C','JOEL ANGEL BASTIDAS','16428381',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-26 18:43:21',NULL,NULL,'2019-04-26 18:43:21',NULL,NULL),('003','00226','C','ODAXI OCHOA','16483730',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-26 18:43:21',NULL,NULL,'2019-04-26 18:43:21',NULL,NULL),('003','00227','C','WILLIAN ROMERO','25829507',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-26 18:43:21',NULL,NULL,'2019-04-26 18:43:21',NULL,NULL),('003','00228','C','MANUEL ROA','20443716',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-26 18:43:22',NULL,NULL,'2019-04-26 18:43:22',NULL,NULL),('003','00229','C','JESUS HERNANDEZ','21018745',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-26 18:43:22',NULL,NULL,'2019-04-26 18:43:22',NULL,NULL),('003','00230','C','EGILVED VALENZUELA','14229000',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-26 18:43:23',NULL,NULL,'2019-04-26 18:43:23',NULL,NULL),('003','00231','C','OMAR OROPEZA','16473238',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-26 18:43:23',NULL,NULL,'2019-04-26 18:43:23',NULL,NULL),('003','00232','C','JOSE GUILLERMO TORRES RIOS','80397743',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-26 18:43:23',NULL,NULL,'2019-04-26 18:43:23',NULL,NULL),('003','00233','C','JOHAN PARRA','20872629',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-26 18:43:24',NULL,NULL,'2019-04-26 18:43:24',NULL,NULL),('003','00234','C','KARINA SALCEDO','17537868',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-26 18:43:24',NULL,NULL,'2019-04-26 18:43:24',NULL,NULL),('003','00235','C','ANDREINA RIVAS','9970168',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-26 18:43:24',NULL,NULL,'2019-04-26 18:43:24',NULL,NULL),('003','00236','C','CAROLINA ITANARE','19248772',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-26 18:43:25',NULL,NULL,'2019-04-26 18:43:25',NULL,NULL),('003','00237','C','ASTRID BRACAMONTE','19794297',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-26 18:43:25',NULL,NULL,'2019-04-26 18:43:25',NULL,NULL),('003','00238','C','JEFFERSON NOGUERA','25338044',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-26 18:43:25',NULL,NULL,'2019-04-26 18:43:25',NULL,NULL),('003','00239','C','NORWIST MACHADO','15885872',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-26 18:43:26',NULL,NULL,'2019-04-26 18:43:26',NULL,NULL),('003','00240','C','NARCELIS CONTRERAS','20940956',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-26 18:43:26',NULL,NULL,'2019-04-26 18:43:26',NULL,NULL),('003','00241','C','NELSON DELGADO','16091424',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-26 18:43:27',NULL,NULL,'2019-04-26 18:43:27',NULL,NULL),('003','00242','C','ROBERTH PEREZ','10895194',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-26 18:43:27',NULL,NULL,'2019-04-26 18:43:27',NULL,NULL),('003','00243','C','LUISANA FUCIL','76298732',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-26 19:25:55',NULL,NULL,'2019-04-26 19:25:55',NULL,NULL),('003','00244','C','LUIS MARCANO','20739233',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-26 19:25:55',NULL,NULL,'2019-04-26 19:25:55',NULL,NULL),('003','00245','C','RONNA LARREAL','20100559',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-26 19:25:56',NULL,NULL,'2019-04-26 19:25:56',NULL,NULL),('003','00246','C','JEAN CARLOS LARROQUE MOGOLLON','67543688',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-26 19:25:57',NULL,NULL,'2019-04-26 19:25:57',NULL,NULL),('003','00247','C','MARISELA CARDENAS','28248866',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-26 19:25:57',NULL,NULL,'2019-04-26 19:25:57',NULL,NULL),('003','00248','C','LUISMELIS BELTRAN','19124251',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-26 19:25:58',NULL,NULL,'2019-04-26 19:25:58',NULL,NULL),('003','00249','C','ASTRID CURO','16899553',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-26 19:25:58',NULL,NULL,'2019-04-26 19:25:58',NULL,NULL),('003','00250','C','EDUIN GUILLES','18645159',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-26 19:25:58',NULL,NULL,'2019-04-26 19:25:58',NULL,NULL),('003','00251','C','JHOVANNA GUTIERREZ','19767388',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-26 19:25:58',NULL,NULL,'2019-04-26 19:25:58',NULL,NULL),('003','00252','C','MARIA MATA','16382556',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-26 19:25:58',NULL,NULL,'2019-04-26 19:25:58',NULL,NULL),('003','00253','C','WILMER SUAREZ','17323598',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-26 19:25:59',NULL,NULL,'2019-04-26 19:25:59',NULL,NULL),('003','00254','C','ALEJANDRA MARIN','26668398',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-26 19:25:59',NULL,NULL,'2019-04-26 19:25:59',NULL,NULL),('003','00255','C','JOSE MARCOS TORRES','19205691',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-26 19:25:59',NULL,NULL,'2019-04-26 19:25:59',NULL,NULL),('003','00256','C','JORGE ARIAS','19601529',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-26 19:26:00',NULL,NULL,'2019-04-26 19:26:00',NULL,NULL),('003','00257','C','GARY TUESTA GARCIA','45836737',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-26 19:26:00',NULL,NULL,'2019-04-26 19:26:00',NULL,NULL),('003','00258','C','JOSE MARCOS TORRES','19202027',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-26 19:26:01',NULL,NULL,'2019-04-26 19:26:01',NULL,NULL),('003','00259','C','DANIELA DUQUE','17869456',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-30 17:44:18',NULL,NULL,'2019-04-30 17:44:18',NULL,NULL),('003','00260','C','YETZABETH GIL','25466369',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-30 17:44:19',NULL,NULL,'2019-04-30 17:44:19',NULL,NULL),('003','00261','C','DAYANA OLIVEROS','19358625',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-30 17:44:20',NULL,NULL,'2019-04-30 17:44:20',NULL,NULL),('003','00262','C','XIOMARA GONZALEZ','45689757',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-30 17:44:20',NULL,NULL,'2019-04-30 17:44:20',NULL,NULL),('003','00263','C','ELSY BELEN','16524278',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-30 17:44:21',NULL,NULL,'2019-04-30 17:44:21',NULL,NULL),('003','00264','C','ALVARO ROA','26259556',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-30 17:44:21',NULL,NULL,'2019-04-30 17:44:21',NULL,NULL),('003','00265','C','ENDRIX AGUILAR','23679810',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-30 17:44:21',NULL,NULL,'2019-04-30 17:44:21',NULL,NULL),('003','00266','C','OSCAR GOMEZ OJEDA','99507182',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-30 17:44:22',NULL,NULL,'2019-04-30 17:44:22',NULL,NULL),('003','00267','C','KARLA SANCHEZ','49007633',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-30 17:44:22',NULL,NULL,'2019-04-30 17:44:22',NULL,NULL),('003','00268','C','MARIA SEGOVIA','26349379',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-30 17:44:22',NULL,NULL,'2019-04-30 17:44:22',NULL,NULL),('003','00269','C','NOLMARIS DIAZ','13461315',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-30 17:44:22',NULL,NULL,'2019-04-30 17:44:22',NULL,NULL),('003','00270','C','FRAN SALAS','21073353',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-30 17:44:23',NULL,NULL,'2019-04-30 17:44:23',NULL,NULL),('003','00271','C','KIRLA HURTADO','16614802',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-30 17:44:23',NULL,NULL,'2019-04-30 17:44:23',NULL,NULL),('003','00272','C','KEVERLIN HERNANDEZ','24272948',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-30 17:44:23',NULL,NULL,'2019-04-30 17:44:23',NULL,NULL),('003','00273','C','JOSE ALBERTO BUSTAMANTE ROSALES','6309910',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-30 17:44:23',NULL,NULL,'2019-04-30 17:44:23',NULL,NULL),('003','00274','C','FREDDY MEDINA','17364683',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-30 17:44:24',NULL,NULL,'2019-04-30 17:44:24',NULL,NULL),('003','00275','C','ALICIA ARROYO','26186874',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-30 17:44:24',NULL,NULL,'2019-04-30 17:44:24',NULL,NULL),('003','00276','C','VANESSA GOMEZ','13982221',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-30 17:44:24',NULL,NULL,'2019-04-30 17:44:24',NULL,NULL),('003','00277','C','ANTONIO GOMEZ','77080358',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-30 17:44:25',NULL,NULL,'2019-04-30 17:44:25',NULL,NULL),('003','00278','C','AURISMAR PEREZ','18839461',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-30 17:44:26',NULL,NULL,'2019-04-30 17:44:26',NULL,NULL),('003','00279','C','JEAN MENDOZA','44544807',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-30 17:44:26',NULL,NULL,'2019-04-30 17:44:26',NULL,NULL),('003','00280','C','MARISA CÁCERES','23502068',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-30 18:07:06',NULL,NULL,'2019-04-30 18:07:06',NULL,NULL),('003','00281','C','STEPHANIE PINO','19032507',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-30 18:07:06',NULL,NULL,'2019-04-30 18:07:06',NULL,NULL),('003','00282','C','NEMESIS CRIOLLO','17657221',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-30 18:07:07',NULL,NULL,'2019-04-30 18:07:07',NULL,NULL),('003','00283','C','EDUARDO ASCANIO','20097964',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-30 18:07:07',NULL,NULL,'2019-04-30 18:07:07',NULL,NULL),('003','00284','C','DANIEL BARRIOS','21442446',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-30 18:07:07',NULL,NULL,'2019-04-30 18:07:07',NULL,NULL),('003','00285','C','ENMANUEL KHOURI','26307521',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-30 18:07:07',NULL,NULL,'2019-04-30 18:07:07',NULL,NULL),('003','00286','C','KEYLA MALAVE','17960929',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-30 18:07:08',NULL,NULL,'2019-04-30 18:07:08',NULL,NULL),('003','00287','C','OLIVER CARRION','14174345',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-30 18:07:08',NULL,NULL,'2019-04-30 18:07:08',NULL,NULL),('003','00288','C','ISAAC MANZANILLA','25406935',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-30 18:07:09',NULL,NULL,'2019-04-30 18:07:09',NULL,NULL),('003','00289','C','JENI MEDINA','20660970',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-30 18:07:09',NULL,NULL,'2019-04-30 18:07:09',NULL,NULL),('003','00290','C','LUIS TORRES','48958166',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-30 18:07:09',NULL,NULL,'2019-04-30 18:07:09',NULL,NULL),('003','00291','C','MARYELIS PEREZ','20055263',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-30 18:07:11',NULL,NULL,'2019-04-30 18:07:11',NULL,NULL),('003','00292','C','JOEL ANDARCIA','64987341',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-30 18:07:11',NULL,NULL,'2019-04-30 18:07:11',NULL,NULL),('003','00293','C','MIGUEL ESTRADA','10396786',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-30 18:07:12',NULL,NULL,'2019-04-30 18:07:12',NULL,NULL),('003','00294','C','DUBRASKA ACSTA','18963137',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-30 19:40:48',NULL,NULL,'2019-04-30 19:40:48',NULL,NULL),('003','00295','C','INESKA OJEDA','20498266',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-30 19:40:49',NULL,NULL,'2019-04-30 19:40:49',NULL,NULL),('003','00296','C','VALLENILLA CARREÑO JUNIOR','20201816',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-30 19:40:49',NULL,NULL,'2019-04-30 19:40:49',NULL,NULL),('003','00297','C','JAVIER DIAZ','25512932',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-30 19:40:51',NULL,NULL,'2019-04-30 19:40:51',NULL,NULL),('003','00298','C','JAVIER OSCAR VIDAL TARAZONA','64598773',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-30 19:40:51',NULL,NULL,'2019-04-30 19:40:51',NULL,NULL),('003','00299','C','LUCIBEL OJEDA','27349458',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-30 19:40:52',NULL,NULL,'2019-04-30 19:40:52',NULL,NULL),('003','00300','C','ERIKA JIMENEZ','56498722',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-30 19:40:52',NULL,NULL,'2019-04-30 19:40:52',NULL,NULL),('003','00301','C','LEONEL PERALTA','70203524',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-30 19:40:52',NULL,NULL,'2019-04-30 19:40:52',NULL,NULL),('003','00302','C','JONATHAN BRITO','27013386',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-30 19:40:53',NULL,NULL,'2019-04-30 19:40:53',NULL,NULL),('003','00303','C','MAYERLINE GUERRA','18719012',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-30 19:40:53',NULL,NULL,'2019-04-30 19:40:53',NULL,NULL),('003','00304','C','LOURDES DE FUENTES','14977094',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-30 19:40:53',NULL,NULL,'2019-04-30 19:40:53',NULL,NULL),('003','00305','C','RANDY NUEVO','43780155',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-30 19:40:54',NULL,NULL,'2019-04-30 19:40:54',NULL,NULL),('003','00306','C','DEEINIS BUSTAMANTE','20886169',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-30 19:40:54',NULL,NULL,'2019-04-30 19:40:54',NULL,NULL),('003','00307','C','MARLON ESCALONA','14754814',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-30 19:40:54',NULL,NULL,'2019-04-30 19:40:54',NULL,NULL),('003','00308','C','NAZARETH DIAZ','26373338',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-30 19:40:54',NULL,NULL,'2019-04-30 19:40:54',NULL,NULL),('003','00309','C','ADRIANA CHIRIVI','14241955',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-30 19:40:55',NULL,NULL,'2019-04-30 19:40:55',NULL,NULL),('003','00310','C','JHOSWART RUBIO','19594497',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-30 19:40:55',NULL,NULL,'2019-04-30 19:40:55',NULL,NULL),('003','00311','C','JESUS CONTRERAS','24314817',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-30 19:40:55',NULL,NULL,'2019-04-30 19:40:55',NULL,NULL),('003','00312','C','MARYURI ABREU','89120093',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-30 19:40:55',NULL,NULL,'2019-04-30 19:40:55',NULL,NULL),('003','00313','C','WILMER DEL RISCO','18469979',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-30 19:40:56',NULL,NULL,'2019-04-30 19:40:56',NULL,NULL),('003','00314','C','JEAN CARLOS LARROQUE MOGOLLON','18977396',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-30 19:40:57',NULL,NULL,'2019-04-30 19:40:57',NULL,NULL),('003','00315','C','JEFFERSON GAULE','14216819',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-30 19:40:59',NULL,NULL,'2019-04-30 19:40:59',NULL,NULL),('003','00316','C','FRANKING BUSTAMANTE','22701175',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-30 19:41:02',NULL,NULL,'2019-04-30 19:41:02',NULL,NULL),('003','00317','C','YOSMAN DIAZ','18310471',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-30 19:41:03',NULL,NULL,'2019-04-30 19:41:03',NULL,NULL),('003','00318','C','JAISI PEREZ','17456875',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-30 19:41:04',NULL,NULL,'2019-04-30 19:41:04',NULL,NULL),('003','00319','C','WILLIAM YANEZ','18553445',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-04-30 19:41:04',NULL,NULL,'2019-04-30 19:41:04',NULL,NULL),('003','00320','C','YANETSSY NIETO','26214139',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-02 16:28:31',NULL,NULL,'2019-05-02 16:28:31',NULL,NULL),('003','00321','C','HAYDEE ABURTO','99026185',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-02 16:28:31',NULL,NULL,'2019-05-02 16:28:31',NULL,NULL),('003','00322','C','EDGAR MOSQUERA','19453871',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-02 16:28:32',NULL,NULL,'2019-05-02 16:28:32',NULL,NULL),('003','00323','C','ANNY COLMENARES','18565308',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-02 16:28:32',NULL,NULL,'2019-05-02 16:28:32',NULL,NULL),('003','00324','C','SHERLY SANCHEZ','19397179',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-02 16:28:32',NULL,NULL,'2019-05-02 16:28:32',NULL,NULL),('003','00325','C','ALISSON PERNALETTE','14900587',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-02 16:28:33',NULL,NULL,'2019-05-02 16:28:33',NULL,NULL),('003','00326','C','KATHERINE GONZALEZ','13073696',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-02 16:28:34',NULL,NULL,'2019-05-02 16:28:34',NULL,NULL),('003','00327','C','EDUARDO CHOURIO','26051243',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-02 16:28:34',NULL,NULL,'2019-05-02 16:28:34',NULL,NULL),('003','00328','C','GRISBELL AGUILAR','27885231',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-02 16:28:34',NULL,NULL,'2019-05-02 16:28:34',NULL,NULL),('003','00329','C','KISBEL ESPINOZA','22546165',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-02 16:28:35',NULL,NULL,'2019-05-02 16:28:35',NULL,NULL),('003','00330','C','GRUPO MONTEPER S.A.C.','20570804775','AV. LA COLINA NRO. S/N SEC. EL HUITO (PASANDO MOLINO PROVIDENCIA-HOTEL URQU) CAJAMARCA - JAEN - JAEN','',NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'FONSECA','2019-05-06 23:22:45','ip-172-31-81-196',NULL,'2019-05-06 23:22:45',NULL,''),('003','00331','C','MIGUEL CORDERO','21412669',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-07 14:23:01',NULL,NULL,'2019-05-07 14:23:01',NULL,NULL),('003','00332','C','RANDOL SERRANO','11260393',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-07 14:23:02',NULL,NULL,'2019-05-07 14:23:02',NULL,NULL),('003','00333','C','MARIOLYS CARDOZO','16149477',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-07 14:23:03',NULL,NULL,'2019-05-07 14:23:03',NULL,NULL),('003','00334','C','EDIXON GARRIDO','15258196',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-07 14:23:03',NULL,NULL,'2019-05-07 14:23:03',NULL,NULL),('003','00335','C','CARLOS CASTILLO','16018777',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-07 14:23:03',NULL,NULL,'2019-05-07 14:23:03',NULL,NULL),('003','00336','C','FRANKLIN GARCIA','23585228',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-07 14:23:03',NULL,NULL,'2019-05-07 14:23:03',NULL,NULL),('003','00337','C','YURAIMA JIMENEZ','13994634',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-07 14:23:04',NULL,NULL,'2019-05-07 14:23:04',NULL,NULL),('003','00338','C','HERNAN SUCLUPE','32943438',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-07 14:23:04',NULL,NULL,'2019-05-07 14:23:04',NULL,NULL),('003','00339','C','JORMAN TUA LOYO','26945715',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-07 14:23:04',NULL,NULL,'2019-05-07 14:23:04',NULL,NULL),('003','00340','C','JESSICA SUAREZ','20245416',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-07 14:23:05',NULL,NULL,'2019-05-07 14:23:05',NULL,NULL),('003','00341','C','JOSE LUCENA','20671769',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-07 14:23:05',NULL,NULL,'2019-05-07 14:23:05',NULL,NULL),('003','00342','C','OSCAR MAITA','18206354',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-07 14:23:05',NULL,NULL,'2019-05-07 14:23:05',NULL,NULL),('003','00343','C','ARIADNA GONZALEZ','20484133',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-07 14:23:05',NULL,NULL,'2019-05-07 14:23:05',NULL,NULL),('003','00344','C','ANDREA UZCATEGUI','24158692',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-07 14:23:06',NULL,NULL,'2019-05-07 14:23:06',NULL,NULL),('003','00345','C','VALERIA RONDON','26766849',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-07 14:23:06',NULL,NULL,'2019-05-07 14:23:06',NULL,NULL),('003','00346','C','JOSE SANCHEZ','14042411',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-07 14:23:06',NULL,NULL,'2019-05-07 14:23:06',NULL,NULL),('003','00347','C','YORGEL BASTIDAS','25735097',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-07 14:23:07',NULL,NULL,'2019-05-07 14:23:07',NULL,NULL),('003','00348','C','KARLA RODRIGUEZ','13828919',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-07 14:23:07',NULL,NULL,'2019-05-07 14:23:07',NULL,NULL),('003','00349','C','ANTONY NARANJA','19946525',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-07 14:23:07',NULL,NULL,'2019-05-07 14:23:07',NULL,NULL),('003','00350','C','ISMAEL PEREIRA','63544345',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-07 14:23:08',NULL,NULL,'2019-05-07 14:23:08',NULL,NULL),('003','00351','C','JOVANNI PEÑA','98046872',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-07 14:23:08',NULL,NULL,'2019-05-07 14:23:08',NULL,NULL),('003','00352','C','BERNARDO GONZALEZ','20313737',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-07 14:23:08',NULL,NULL,'2019-05-07 14:23:08',NULL,NULL),('003','00353','C','ZENAIDA TERRONES','46201958',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-07 14:23:09',NULL,NULL,'2019-05-07 14:23:09',NULL,NULL),('003','00354','C','LISSET RIVAS','42971218',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-07 14:23:10',NULL,NULL,'2019-05-07 14:23:10',NULL,NULL),('003','00355','C','PAULO DOS REIS','16395457',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-07 14:23:11',NULL,NULL,'2019-05-07 14:23:11',NULL,NULL),('003','00356','C','MICHAEL BUENDIA','21456594',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-07 14:23:11',NULL,NULL,'2019-05-07 14:23:11',NULL,NULL),('003','00357','C','MARIA PEREZ','82766052',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-07 14:23:11',NULL,NULL,'2019-05-07 14:23:11',NULL,NULL),('003','00358','C','LUCY YAMILE PEÑA PEÑA','23583894',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-07 14:23:12',NULL,NULL,'2019-05-07 14:23:12',NULL,NULL),('003','00359','C','GENESIS NIEVES','99805162',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-07 14:23:13',NULL,NULL,'2019-05-07 14:23:13',NULL,NULL),('003','00360','C','JOSE ARMANDO CAICEDO ZEÑA','80432494',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-07 14:23:13',NULL,NULL,'2019-05-07 14:23:13',NULL,NULL),('003','00361','C','JOEL MARTINEZ','19379113',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-07 14:23:14',NULL,NULL,'2019-05-07 14:23:14',NULL,NULL),('003','00362','C','ANTONIO JULIO','14275591',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-07 14:23:14',NULL,NULL,'2019-05-07 14:23:14',NULL,NULL),('003','00363','C','DOUGLAS CHACON','18544171',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-07 14:23:14',NULL,NULL,'2019-05-07 14:23:14',NULL,NULL),('003','00364','C','ROIMER LOPEZ','24931301',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-07 14:23:15',NULL,NULL,'2019-05-07 14:23:15',NULL,NULL),('003','00365','C','MARIA VELASQUEZ','45332446',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-07 14:23:15',NULL,NULL,'2019-05-07 14:23:15',NULL,NULL),('003','00366','C','LUIS ALFREDO ARAQUE','17028699',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-07 14:23:15',NULL,NULL,'2019-05-07 14:23:15',NULL,NULL),('003','00367','C','GUSTAVO HUMBERTO','19477682',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-07 14:23:16',NULL,NULL,'2019-05-07 14:23:16',NULL,NULL),('003','00368','C','VIVIAN FERNANDEZ','97463863',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-07 14:23:16',NULL,NULL,'2019-05-07 14:23:16',NULL,NULL),('003','00369','C','DANIEL MALDONADO','17564283',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-07 14:23:16',NULL,NULL,'2019-05-07 14:23:16',NULL,NULL),('003','00370','C','VANESSA GOMEZ','13982216',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-07 14:23:16',NULL,NULL,'2019-05-07 14:23:16',NULL,NULL),('003','00371','C','RAMON CONTRERAS','27187279',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-07 14:23:16',NULL,NULL,'2019-05-07 14:23:16',NULL,NULL),('003','00372','C','DEIKER BOLIVAR','19273033',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-07 23:07:39',NULL,NULL,'2019-05-07 23:07:39',NULL,NULL),('003','00373','C','NEYLER CORDOBA','24901578',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-07 23:07:39',NULL,NULL,'2019-05-07 23:07:39',NULL,NULL),('003','00374','C','FANNY GALINDO','16544892',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-07 23:07:39',NULL,NULL,'2019-05-07 23:07:39',NULL,NULL),('003','00375','C','CELESTE SERRANO','23734094',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-07 23:07:39',NULL,NULL,'2019-05-07 23:07:39',NULL,NULL),('003','00376','C','CARMEN MARTINEZ','86862018',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-07 23:07:39',NULL,NULL,'2019-05-07 23:07:39',NULL,NULL),('003','00377','C','NICOLAS PEREZ','26497329',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-07 23:07:39',NULL,NULL,'2019-05-07 23:07:39',NULL,NULL),('003','00378','C','JOSE MANUEL NOGUERA','15448690',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-07 23:10:00',NULL,NULL,'2019-05-07 23:10:00',NULL,NULL),('003','00379','C','GLENDER JOSE SAAVEDRA GARCIA','18225350',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-07 23:10:00',NULL,NULL,'2019-05-07 23:10:00',NULL,NULL),('003','00380','C','DANIEL LIMA','17714103',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-07 23:10:00',NULL,NULL,'2019-05-07 23:10:00',NULL,NULL),('003','00381','C','CAROLINA CALDERON','16656588',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-07 23:10:00',NULL,NULL,'2019-05-07 23:10:00',NULL,NULL),('003','00382','C','RAFAEL MORENO','20790939',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-08 20:28:33',NULL,NULL,'2019-05-08 20:28:33',NULL,NULL),('003','00383','C','KARLA RODRIGUEZ','13828191',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-08 20:28:33',NULL,NULL,'2019-05-08 20:28:33',NULL,NULL),('003','00384','C','MAITHE SULBARAN','19593211',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-08 20:28:33',NULL,NULL,'2019-05-08 20:28:33',NULL,NULL),('003','00385','C','KAROLINE ROJAS','20490844',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-08 20:28:33',NULL,NULL,'2019-05-08 20:28:33',NULL,NULL),('003','00386','C','YOEL CASTELLANO','15447796',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-08 20:28:34',NULL,NULL,'2019-05-08 20:28:34',NULL,NULL),('003','00387','C','MILEIDY VALLADARES','14690393',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-08 20:28:35',NULL,NULL,'2019-05-08 20:28:35',NULL,NULL),('003','00388','C','SOLANGEL DURAN','19965721',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-08 20:28:36',NULL,NULL,'2019-05-08 20:28:36',NULL,NULL),('003','00389','C','JONATHAN BOLEL','19998056',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-08 20:28:36',NULL,NULL,'2019-05-08 20:28:36',NULL,NULL),('003','00390','C','FLOR ABURTO','80091897',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-08 20:28:37',NULL,NULL,'2019-05-08 20:28:37',NULL,NULL),('003','00391','C','JASMIN PEREZ','55667625',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-08 20:28:37',NULL,NULL,'2019-05-08 20:28:37',NULL,NULL),('003','00392','C','MOISES SANCHEZ','73249399',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-08 20:28:37',NULL,NULL,'2019-05-08 20:28:37',NULL,NULL),('003','00393','C','RAFAEL VASQUEZ','24597736',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-08 20:28:37',NULL,NULL,'2019-05-08 20:28:37',NULL,NULL),('003','00394','C','ESTEFANY RAMOS','22762070',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-08 20:28:38',NULL,NULL,'2019-05-08 20:28:38',NULL,NULL),('003','00395','C','LILIA GARCIA','84514323',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-08 20:28:38',NULL,NULL,'2019-05-08 20:28:38',NULL,NULL),('003','00396','C','JOSE MARCOS TORRES','19202024',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-08 20:28:38',NULL,NULL,'2019-05-08 20:28:38',NULL,NULL),('003','00397','C','MOISES PONCE','26435036',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-08 20:28:39',NULL,NULL,'2019-05-08 20:28:39',NULL,NULL),('003','00398','C','VICENTE MORALES','56334788',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-08 20:28:39',NULL,NULL,'2019-05-08 20:28:39',NULL,NULL),('003','00399','C','PEDRO SANCHEZ','15901938',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-08 20:28:39',NULL,NULL,'2019-05-08 20:28:39',NULL,NULL),('003','00400','C','GREGORIO VELASQUEZ','18400598',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-08 20:28:40',NULL,NULL,'2019-05-08 20:28:40',NULL,NULL),('003','00401','C','ROSSANA PEREIRA','15112285',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-08 20:28:40',NULL,NULL,'2019-05-08 20:28:40',NULL,NULL),('003','00402','C','FREDDY ONOFRE','40112382',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-08 20:28:40',NULL,NULL,'2019-05-08 20:28:40',NULL,NULL),('003','00403','C','GENIGER VELASQUEZ','17429192',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-08 20:28:40',NULL,NULL,'2019-05-08 20:28:40',NULL,NULL),('003','00404','C','LILIANA CAIRES','16342452',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-08 21:03:04',NULL,NULL,'2019-05-08 21:03:04',NULL,NULL),('003','00405','C','FRANK CHUKY','17985341',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-08 21:03:04',NULL,NULL,'2019-05-08 21:03:04',NULL,NULL),('003','00406','C','DELIA CARRILLO','40761194',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-08 21:03:04',NULL,NULL,'2019-05-08 21:03:04',NULL,NULL),('003','00407','C','YAILETH MORA','19591237',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-08 21:03:04',NULL,NULL,'2019-05-08 21:03:04',NULL,NULL),('003','00408','C','HILDAMAR BRETO','14182460',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-08 21:03:04',NULL,NULL,'2019-05-08 21:03:04',NULL,NULL),('003','00409','C','ANDREINA RIVAS','99701682',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-08 21:03:05',NULL,NULL,'2019-05-08 21:03:05',NULL,NULL),('003','00410','C','BRYAN RODRIGUEZ','22701172',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-08 21:03:05',NULL,NULL,'2019-05-08 21:03:05',NULL,NULL),('003','00411','C','LUIS JOSE RUIZ','10819051',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-08 21:03:05',NULL,NULL,'2019-05-08 21:03:05',NULL,NULL),('003','00412','C','MARIBELYS ARMAS','26527548',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-08 21:03:05',NULL,NULL,'2019-05-08 21:03:05',NULL,NULL),('003','00413','C','MIRIAN MEJIAS','10758827',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-08 21:03:06',NULL,NULL,'2019-05-08 21:03:06',NULL,NULL),('003','00414','C','DUVERLIS NAVA','15985787',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-08 21:03:06',NULL,NULL,'2019-05-08 21:03:06',NULL,NULL),('003','00415','C','LUZ SUCLUPE','27602997',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-08 21:03:06',NULL,NULL,'2019-05-08 21:03:06',NULL,NULL),('003','00416','C','WILLIAM SANCHEZ','11952901',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-08 21:03:07',NULL,NULL,'2019-05-08 21:03:07',NULL,NULL),('003','00417','C','VICSY VIDAL','19885293',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-08 21:03:07',NULL,NULL,'2019-05-08 21:03:07',NULL,NULL),('003','00418','C','FREDDY MEDINA','17368434',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-08 21:03:07',NULL,NULL,'2019-05-08 21:03:07',NULL,NULL),('003','00419','C','LEONEL ZAMBRANO','44313269',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-08 21:03:08',NULL,NULL,'2019-05-08 21:03:08',NULL,NULL),('003','00420','C','CARLOS ORTIZ','12071094',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-08 21:03:09',NULL,NULL,'2019-05-08 21:03:09',NULL,NULL),('003','00421','C','MILERKIS CHACON','16013752',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-08 21:03:09',NULL,NULL,'2019-05-08 21:03:09',NULL,NULL),('003','00422','C','JIOVANY VEGA','13281547',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-08 21:03:10',NULL,NULL,'2019-05-08 21:03:10',NULL,NULL),('003','00423','C','FREDDY HERNANDEZ','10469138',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-08 21:03:10',NULL,NULL,'2019-05-08 21:03:10',NULL,NULL),('003','00424','C','ISRAEL GONZALEZ','15343333',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-08 21:09:39',NULL,NULL,'2019-05-08 21:09:39',NULL,NULL),('003','00425','C','KEVIN PADRON','14566738',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-08 21:09:39',NULL,NULL,'2019-05-08 21:09:39',NULL,NULL),('003','00426','C','JOSIMAR QUISPE','46251866',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-08 21:09:39',NULL,NULL,'2019-05-08 21:09:39',NULL,NULL),('003','00427','C','MARIANA CAICEDO','22746525',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-08 21:09:40',NULL,NULL,'2019-05-08 21:09:40',NULL,NULL),('003','00428','C','ALEX CARLOS','48321828',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-08 21:09:40',NULL,NULL,'2019-05-08 21:09:40',NULL,NULL),('003','00429','C','MARIAN RENGIFO','19633425',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-08 21:09:40',NULL,NULL,'2019-05-08 21:09:40',NULL,NULL),('003','00430','C','JUAN TORRES','68016633',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-08 21:09:41',NULL,NULL,'2019-05-08 21:09:41',NULL,NULL),('003','00431','C','GREGORIO MARCANO','25706356',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-08 21:09:42',NULL,NULL,'2019-05-08 21:09:42',NULL,NULL),('003','00432','C','SIUL GALINDEZ','13986717',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-08 21:09:43',NULL,NULL,'2019-05-08 21:09:43',NULL,NULL),('003','00433','C','JOVANNI PEÑA','98068721',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-08 21:09:44',NULL,NULL,'2019-05-08 21:09:44',NULL,NULL),('003','00434','C','ADELIS PIEDRA','50187728',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-08 21:09:45',NULL,NULL,'2019-05-08 21:09:45',NULL,NULL),('003','00435','C','WILSON RINCON','10422484',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-08 21:09:45',NULL,NULL,'2019-05-08 21:09:45',NULL,NULL),('003','00436','C','WILIA DIAZ','88248004',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-08 21:09:46',NULL,NULL,'2019-05-08 21:09:46',NULL,NULL),('003','00437','C','LUISANA FUCIL','14763267',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-08 21:09:47',NULL,NULL,'2019-05-08 21:09:47',NULL,NULL),('003','00438','C','MARIA PEREZ','82766051',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-08 21:09:47',NULL,NULL,'2019-05-08 21:09:47',NULL,NULL),('003','00439','C','STIFANY MEJIA','22915147',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-08 21:09:47',NULL,NULL,'2019-05-08 21:09:47',NULL,NULL),('003','00440','C','LOLYMAR QUINTERO','66536524',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-08 21:09:48',NULL,NULL,'2019-05-08 21:09:48',NULL,NULL),('003','00441','C','LUIS ANGEL JAIME','27095638',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-08 21:09:48',NULL,NULL,'2019-05-08 21:09:48',NULL,NULL),('003','00442','C','GISMAR PALOMO','13675482',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-08 21:09:48',NULL,NULL,'2019-05-08 21:09:48',NULL,NULL),('003','00443','C','LEONARDO CHIRINOS','23792255',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-08 21:09:50',NULL,NULL,'2019-05-08 21:09:50',NULL,NULL),('003','00444','C','JOHANA ALFONZO','18609457',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-08 21:09:50',NULL,NULL,'2019-05-08 21:09:50',NULL,NULL),('003','00445','C','RAIDY PEÑA','17267778',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-08 21:09:50',NULL,NULL,'2019-05-08 21:09:50',NULL,NULL),('003','00446','C','OSWALD TORRES','18910026',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-08 21:09:51',NULL,NULL,'2019-05-08 21:09:51',NULL,NULL),('003','00447','C','GENESIS SUAREZ','19962035',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-08 21:09:53',NULL,NULL,'2019-05-08 21:09:53',NULL,NULL),('003','00448','C','YOMAIRY JIMÉNEZ MONTILLA','14377613',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-08 21:09:54',NULL,NULL,'2019-05-08 21:09:54',NULL,NULL),('003','00449','C','CESAR IZARRA','17532473',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-08 21:09:55',NULL,NULL,'2019-05-08 21:09:55',NULL,NULL),('003','00450','C','FRANCISCO BUSTAMANTE','14230673',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-08 21:09:56',NULL,NULL,'2019-05-08 21:09:56',NULL,NULL),('003','00451','C','OSCAR GOMEZ OJEDA','99501825',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-08 21:09:56',NULL,NULL,'2019-05-08 21:09:56',NULL,NULL),('003','00452','C','MARYORI TROCONIS','17938592',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-08 21:09:56',NULL,NULL,'2019-05-08 21:09:56',NULL,NULL),('003','00453','C','GLADYS CASTILLO TARAZONA','32917172',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-08 21:09:57',NULL,NULL,'2019-05-08 21:09:57',NULL,NULL),('003','00454','C','LUISSANA GONZALEZ','20065725',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-08 21:09:57',NULL,NULL,'2019-05-08 21:09:57',NULL,NULL),('003','00455','C','WILMER PEREZ','15753357',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-08 21:09:57',NULL,NULL,'2019-05-08 21:09:57',NULL,NULL),('003','00456','C','OSCAR GOMEZ OJEDA','99501824',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-08 21:09:59',NULL,NULL,'2019-05-08 21:09:59',NULL,NULL),('003','00457','C','MARTHA HERRERA','22954493',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-08 21:25:45',NULL,NULL,'2019-05-08 21:25:45',NULL,NULL),('003','00458','C','TATIANA GAUDIOSO','15122682',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-08 21:25:45',NULL,NULL,'2019-05-08 21:25:45',NULL,NULL),('003','00459','C','REINER PANTIN','19589468',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-08 21:25:46',NULL,NULL,'2019-05-08 21:25:46',NULL,NULL),('003','00460','C','FABIOLA UZCATEGUI','12653924',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-08 21:25:46',NULL,NULL,'2019-05-08 21:25:46',NULL,NULL),('003','00461','C','JOSE GREGORIO ZERPA','23346027',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-08 21:25:47',NULL,NULL,'2019-05-08 21:25:47',NULL,NULL),('003','00462','C','ALEXIS RODRIGUEZ','25894230',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-08 21:25:47',NULL,NULL,'2019-05-08 21:25:47',NULL,NULL),('003','00463','C','OSCAR LUCENA','23487497',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-08 21:25:48',NULL,NULL,'2019-05-08 21:25:48',NULL,NULL),('003','00464','C','JESUS FARIÑA','22744312',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-08 21:25:48',NULL,NULL,'2019-05-08 21:25:48',NULL,NULL),('003','00465','C','CRISTOPHER COLMENARES','20267380',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-08 21:25:48',NULL,NULL,'2019-05-08 21:25:48',NULL,NULL),('003','00466','C','MARIA BOSCAN','16687229',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-08 21:25:49',NULL,NULL,'2019-05-08 21:25:49',NULL,NULL),('003','00467','C','NERLLI MORILLO','20918152',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-08 21:25:50',NULL,NULL,'2019-05-08 21:25:50',NULL,NULL),('003','00468','C','MARIA CASTILLO','24786359',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-08 21:25:51',NULL,NULL,'2019-05-08 21:25:51',NULL,NULL),('003','00469','C','MARTIN CAPOTE','14367656',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-08 21:25:51',NULL,NULL,'2019-05-08 21:25:51',NULL,NULL),('003','00470','C','ANDERSON OCANDO','13967203',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-08 21:25:51',NULL,NULL,'2019-05-08 21:25:51',NULL,NULL),('003','00471','C','VICENTE PEREZ','15443674',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-08 21:25:52',NULL,NULL,'2019-05-08 21:25:52',NULL,NULL),('003','00472','C','FELIX GIMENEZ','53652708',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-08 21:25:53',NULL,NULL,'2019-05-08 21:25:53',NULL,NULL),('003','00473','C','FRANKLIN MONSALVE','12855491',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-08 21:25:53',NULL,NULL,'2019-05-08 21:25:53',NULL,NULL),('003','00474','C','GILBERTO UZCATEGUI','17392779',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-08 21:25:53',NULL,NULL,'2019-05-08 21:25:53',NULL,NULL),('003','00475','C','YORMAN ALFONZO','14457643',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-08 21:25:54',NULL,NULL,'2019-05-08 21:25:54',NULL,NULL),('003','00476','C','JOSE MARCOS TORRES','19202025',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-08 21:25:55',NULL,NULL,'2019-05-08 21:25:55',NULL,NULL),('003','00477','C','DIANA CHAVARRY','17475406',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-08 21:25:56',NULL,NULL,'2019-05-08 21:25:56',NULL,NULL),('003','00478','C','BELINDA CURIEL','14108295',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-09 21:16:09',NULL,NULL,'2019-05-09 21:16:09',NULL,NULL),('003','00479','C','MARIA JAQUELINE GUERRERO PAREDES','10427822',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-09 21:16:09',NULL,NULL,'2019-05-09 21:16:09',NULL,NULL),('003','00480','C','DAYANA GUILLEN','11227264',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-09 21:16:09',NULL,NULL,'2019-05-09 21:16:09',NULL,NULL),('003','00481','C','KATHERINE DIAZ','18711138',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-09 21:16:10',NULL,NULL,'2019-05-09 21:16:10',NULL,NULL),('003','00482','C','LUIS PEREZ','13657822',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-09 21:16:11',NULL,NULL,'2019-05-09 21:16:11',NULL,NULL),('003','00483','C','CARLOS ESPINOZA','14170401',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-09 21:16:12',NULL,NULL,'2019-05-09 21:16:12',NULL,NULL),('003','00484','C','CESAR LUCAR','40302021',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-09 21:16:12',NULL,NULL,'2019-05-09 21:16:12',NULL,NULL),('003','00485','C','EDUARDO FERNANDEZ','18286698',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-09 21:16:13',NULL,NULL,'2019-05-09 21:16:13',NULL,NULL),('003','00486','C','LUIS JOSE ROJAS BRICEÑO','13445922',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-09 21:16:14',NULL,NULL,'2019-05-09 21:16:14',NULL,NULL),('003','00487','C','ADOLFO RUIZ RUFFNER','12543438',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-09 21:16:14',NULL,NULL,'2019-05-09 21:16:14',NULL,NULL),('003','00488','C','KERVIS SANCHEZ','14553363',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-09 21:16:14',NULL,NULL,'2019-05-09 21:16:14',NULL,NULL),('003','00489','C','ALAM ZAVALA','19823028',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-09 21:16:15',NULL,NULL,'2019-05-09 21:16:15',NULL,NULL),('003','00490','C','ELSY BELEN','42784342',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-09 21:16:15',NULL,NULL,'2019-05-09 21:16:15',NULL,NULL),('003','00491','C','SEEGRITH MUJICA','15624116',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-09 21:16:15',NULL,NULL,'2019-05-09 21:16:15',NULL,NULL),('003','00492','C','JEAN PIERO ROMAN PARRA','14553672',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-09 21:16:15',NULL,NULL,'2019-05-09 21:16:15',NULL,NULL),('003','00493','C','ROSMARY RAMON','92566001',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-09 21:16:16',NULL,NULL,'2019-05-09 21:16:16',NULL,NULL),('003','00494','C','JOSEPH VERENZUELA','10785942',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-09 21:16:16',NULL,NULL,'2019-05-09 21:16:16',NULL,NULL),('003','00495','C','FELIX GIMENEZ','53652709',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-09 21:16:16',NULL,NULL,'2019-05-09 21:16:16',NULL,NULL),('003','00496','C','ALVARO JAVIER CARRILLO','14665791',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-09 21:16:17',NULL,NULL,'2019-05-09 21:16:17',NULL,NULL),('003','00497','C','FREDDY AGUIRRE','41118487',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-09 21:16:18',NULL,NULL,'2019-05-09 21:16:18',NULL,NULL),('003','00498','C','CARLOS JAUREGUI','22511864',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-09 21:16:19',NULL,NULL,'2019-05-09 21:16:19',NULL,NULL),('003','00499','C','FREDDY MEDINA','17368367',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-09 21:16:19',NULL,NULL,'2019-05-09 21:16:19',NULL,NULL),('003','00500','C','LEONEL ZAMBRANO','44313285',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-09 21:16:19',NULL,NULL,'2019-05-09 21:16:19',NULL,NULL),('003','00501','C','NEOMAR  CONTRERAS','16435986',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-09 21:16:20',NULL,NULL,'2019-05-09 21:16:20',NULL,NULL),('003','00502','C','ERICKK PADILLA','14767855',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-09 21:16:20',NULL,NULL,'2019-05-09 21:16:20',NULL,NULL),('003','00503','C','AMAURYS PIÑA','16027925',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-09 21:16:21',NULL,NULL,'2019-05-09 21:16:21',NULL,NULL),('003','00504','C','WILLIAM PINEDA','11764416',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-09 21:16:21',NULL,NULL,'2019-05-09 21:16:21',NULL,NULL),('003','00505','C','GIOVANNI AGUILERA','89632288',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-09 21:16:21',NULL,NULL,'2019-05-09 21:16:21',NULL,NULL),('003','00506','C','EMMA GUERRERO','99866475',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-16 15:47:00',NULL,NULL,'2019-05-16 15:47:00',NULL,NULL),('003','00507','C','MAYERLINE GUERRA','11871901',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-16 15:47:01',NULL,NULL,'2019-05-16 15:47:01',NULL,NULL),('003','00508','C','HECTOR SALAZAR','14892007',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-16 15:47:01',NULL,NULL,'2019-05-16 15:47:01',NULL,NULL),('003','00509','C','YELIMBERT MORENO','25513986',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-16 15:47:01',NULL,NULL,'2019-05-16 15:47:01',NULL,NULL),('003','00510','C','KARENT ALVARADO','20082329',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-16 15:47:01',NULL,NULL,'2019-05-16 15:47:01',NULL,NULL),('003','00511','C','ROSA DELLEPIANE','95309264',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-16 15:47:02',NULL,NULL,'2019-05-16 15:47:02',NULL,NULL),('003','00512','C','GREGORY GIL','18905982',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-16 15:47:02',NULL,NULL,'2019-05-16 15:47:02',NULL,NULL),('003','00513','C','REBECA CALDERA','18830019',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-16 15:47:03',NULL,NULL,'2019-05-16 15:47:03',NULL,NULL),('003','00514','C','MAIRE ALVAREZ','15720752',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-16 15:47:04',NULL,NULL,'2019-05-16 15:47:04',NULL,NULL),('003','00515','C','YENY POCHO','13665492',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-16 15:47:05',NULL,NULL,'2019-05-16 15:47:05',NULL,NULL),('003','00516','C','VANESSA PIÑA','14632602',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-16 15:47:06',NULL,NULL,'2019-05-16 15:47:06',NULL,NULL),('003','00517','C','ARNOLDO LOPEZ PEREZ','10751419',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-16 15:47:06',NULL,NULL,'2019-05-16 15:47:06',NULL,NULL),('003','00518','C','OLIVER CEDEÑO','96944282',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-16 15:47:06',NULL,NULL,'2019-05-16 15:47:06',NULL,NULL),('003','00519','C','ESTHER GONZALEZ','99290205',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-16 15:47:07',NULL,NULL,'2019-05-16 15:47:07',NULL,NULL),('003','00520','C','LEYDIS RODRIGUEZ','20563393',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-16 15:47:07',NULL,NULL,'2019-05-16 15:47:07',NULL,NULL),('003','00521','C','LAURI GUTIERREZ','13521919',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-16 15:47:07',NULL,NULL,'2019-05-16 15:47:07',NULL,NULL),('003','00522','C','ALVARO JAVIER CARRILLO','18851363',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-16 15:47:07',NULL,NULL,'2019-05-16 15:47:07',NULL,NULL),('003','00523','C','MARIA VELASQUEZ','20904033',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-16 15:47:08',NULL,NULL,'2019-05-16 15:47:08',NULL,NULL),('003','00524','C','CESAR HUAMANI','42095828',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-16 15:47:09',NULL,NULL,'2019-05-16 15:47:09',NULL,NULL),('003','00525','C','DULCELINA HERNANDEZ','18090857',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-16 15:47:10',NULL,NULL,'2019-05-16 15:47:10',NULL,NULL),('003','00526','C','YINIELI CAMARGO','19829630',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-16 15:47:10',NULL,NULL,'2019-05-16 15:47:10',NULL,NULL),('003','00527','C','CARLOS VIERA','11616431',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-16 15:47:10',NULL,NULL,'2019-05-16 15:47:10',NULL,NULL),('003','00528','C','JOSE MARCOS TORRES','19202023',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-16 15:47:11',NULL,NULL,'2019-05-16 15:47:11',NULL,NULL),('003','00529','C','THOMAS CENTENO','17883461',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-16 15:47:11',NULL,NULL,'2019-05-16 15:47:11',NULL,NULL),('003','00530','C','JOSE GREGORIO MATHEUS','93142592',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-20 03:30:19',NULL,NULL,'2019-05-20 03:30:19',NULL,NULL),('003','00531','C','LISBETH SERRANO','12055749',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-20 03:30:19',NULL,NULL,'2019-05-20 03:30:19',NULL,NULL),('003','00532','C','JUAN DELGADO','18728828',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-20 03:30:19',NULL,NULL,'2019-05-20 03:30:19',NULL,NULL),('003','00533','C','ESTHER GONZALEZ','99290204',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-20 03:30:21',NULL,NULL,'2019-05-20 03:30:21',NULL,NULL),('003','00534','C','GLORIMAR YANEZ','10353708',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-20 03:30:21',NULL,NULL,'2019-05-20 03:30:21',NULL,NULL),('003','00535','C','ENRIQUETA PANASA','19547135',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-20 03:30:21',NULL,NULL,'2019-05-20 03:30:21',NULL,NULL),('003','00536','C','DANIEL GOMEZ','23429935',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-20 03:30:24',NULL,NULL,'2019-05-20 03:30:24',NULL,NULL),('003','00537','C','NICOLAS RODRIGUEZ','16102643',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-20 03:30:24',NULL,NULL,'2019-05-20 03:30:24',NULL,NULL),('003','00538','C','ELSY BELEN','42785664',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-20 03:30:24',NULL,NULL,'2019-05-20 03:30:24',NULL,NULL),('003','00539','C','JESUS VALERRY','19763934',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-20 03:30:24',NULL,NULL,'2019-05-20 03:30:24',NULL,NULL),('003','00540','C','ELIOMAR PARRA','11899430',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-20 03:30:25',NULL,NULL,'2019-05-20 03:30:25',NULL,NULL),('003','00541','C','JORGE ROCA','16882934',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-20 03:30:25',NULL,NULL,'2019-05-20 03:30:25',NULL,NULL),('003','00542','C','ANTHONY RONDON','24884613',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-20 03:30:26',NULL,NULL,'2019-05-20 03:30:26',NULL,NULL),('003','00543','C','LOLYMAR QUINTERO','66536521',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-20 03:30:27',NULL,NULL,'2019-05-20 03:30:27',NULL,NULL),('003','00544','C','ALEXIS PRADO','16974601',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-20 03:30:27',NULL,NULL,'2019-05-20 03:30:27',NULL,NULL),('003','00545','C','CESAR LUCAR','40302024',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-20 03:30:28',NULL,NULL,'2019-05-20 03:30:28',NULL,NULL),('003','00546','C','KARLA RODRIGUEZ','13828195',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-20 03:30:28',NULL,NULL,'2019-05-20 03:30:28',NULL,NULL),('003','00547','C','SOL ARTIGAS','24298215',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-20 03:30:31',NULL,NULL,'2019-05-20 03:30:31',NULL,NULL),('003','00548','C','MISAEL PAEZ','78652213',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-20 03:30:31',NULL,NULL,'2019-05-20 03:30:31',NULL,NULL),('003','00549','C','FELIX GIMENEZ','53652705',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-20 03:30:31',NULL,NULL,'2019-05-20 03:30:31',NULL,NULL),('003','00550','C','JAISI PEREZ','12748593',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-20 03:30:32',NULL,NULL,'2019-05-20 03:30:32',NULL,NULL),('003','00551','C','RICARDO BAEZ','14971480',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-20 03:30:32',NULL,NULL,'2019-05-20 03:30:32',NULL,NULL),('003','00552','C','ELIAS GUTIERREZ','15632178',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-20 15:03:46',NULL,NULL,'2019-05-20 15:03:46',NULL,NULL),('003','00553','C','GREG BARRIOS','26765744',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-20 15:03:47',NULL,NULL,'2019-05-20 15:03:47',NULL,NULL),('003','00554','C','YFRAIN SAMBRANO','69874472',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-20 15:03:47',NULL,NULL,'2019-05-20 15:03:47',NULL,NULL),('003','00555','C','ANA CARRILLO','12665320',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-20 15:03:47',NULL,NULL,'2019-05-20 15:03:47',NULL,NULL),('003','00556','C','ERIKA PERÉZ','17655309',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-20 15:03:48',NULL,NULL,'2019-05-20 15:03:48',NULL,NULL),('003','00557','C','REGULO FERNANDEZ','19644703',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-20 15:03:48',NULL,NULL,'2019-05-20 15:03:48',NULL,NULL),('003','00558','C','ROBERTH VAN DER LOYO','19448957',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-20 15:03:48',NULL,NULL,'2019-05-20 15:03:48',NULL,NULL),('003','00559','C','ANDREINA VELOZ','22338268',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-20 15:03:50',NULL,NULL,'2019-05-20 15:03:50',NULL,NULL),('003','00560','C','JESUS TOVAR','23626405',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-20 15:03:50',NULL,NULL,'2019-05-20 15:03:50',NULL,NULL),('003','00561','C','FREDDY MEDINA','17368352',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-20 15:03:50',NULL,NULL,'2019-05-20 15:03:50',NULL,NULL),('003','00562','C','WILENGER SOTO','18600559',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-20 15:03:51',NULL,NULL,'2019-05-20 15:03:51',NULL,NULL),('003','00563','C','BLADIMIR RUIZ','10378698',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-20 15:03:51',NULL,NULL,'2019-05-20 15:03:51',NULL,NULL),('003','00564','C','JOHANA MIRANDA','15496834',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-20 15:03:52',NULL,NULL,'2019-05-20 15:03:52',NULL,NULL),('003','00565','C','CARLOS GONZALEZ','19335757',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-20 15:03:52',NULL,NULL,'2019-05-20 15:03:52',NULL,NULL),('003','00566','C','STEPHANY AZUAJE','34666999',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-20 15:41:17',NULL,NULL,'2019-05-20 15:41:17',NULL,NULL),('003','00567','C','MIGUEL MORAN','17041268',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-20 15:41:18',NULL,NULL,'2019-05-20 15:41:18',NULL,NULL),('003','00568','C','JULIO CESAR','25535888',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-20 15:41:19',NULL,NULL,'2019-05-20 15:41:19',NULL,NULL),('003','00569','C','ANGELO GONZALEZ','23618361',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-20 15:41:19',NULL,NULL,'2019-05-20 15:41:19',NULL,NULL),('003','00570','C','ERIKA','16882234',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-20 15:41:19',NULL,NULL,'2019-05-20 15:41:19',NULL,NULL),('003','00571','C','CARLOS FERNANDEZ','18459692',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-20 15:41:20',NULL,NULL,'2019-05-20 15:41:20',NULL,NULL),('003','00572','C','RADAMES QUIJADA','11415773',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-20 15:41:20',NULL,NULL,'2019-05-20 15:41:20',NULL,NULL),('003','00573','C','MERLY ROMERO MALLMA','40349096',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-20 15:41:21',NULL,NULL,'2019-05-20 15:41:21',NULL,NULL),('003','00574','C','GENESIS MANZINI','22320419',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-20 15:41:21',NULL,NULL,'2019-05-20 15:41:21',NULL,NULL),('003','00575','C','CARMEN RAMIREZ','12561340',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-20 15:41:23',NULL,NULL,'2019-05-20 15:41:23',NULL,NULL),('003','00576','C','JOSE SALAZAR','12537184',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-20 15:41:24',NULL,NULL,'2019-05-20 15:41:24',NULL,NULL),('003','00577','C','THOMAS CENTENO','21547896',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-20 15:41:25',NULL,NULL,'2019-05-20 15:41:25',NULL,NULL),('003','00578','C','RONALD RODRIGUEZ','10821808',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-20 15:41:25',NULL,NULL,'2019-05-20 15:41:25',NULL,NULL),('003','00579','C','JOHANNA ZACARIAS','14687769',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-20 15:41:26',NULL,NULL,'2019-05-20 15:41:26',NULL,NULL),('003','00580','C','CARLOS MORALES','26967101',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-20 15:41:27',NULL,NULL,'2019-05-20 15:41:27',NULL,NULL),('003','00581','C','ALEX BLANCO','14394564',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-20 15:41:27',NULL,NULL,'2019-05-20 15:41:27',NULL,NULL),('003','00582','C','ERICK PADILLA','15884937',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-20 15:41:28',NULL,NULL,'2019-05-20 15:41:28',NULL,NULL),('003','00583','C','PEDRO PABLO','20266754',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-20 15:41:32',NULL,NULL,'2019-05-20 15:41:32',NULL,NULL),('003','00584','C','GABRIELA FEBRES','19154823',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-20 16:32:34',NULL,NULL,'2019-05-20 16:32:34',NULL,NULL),('003','00585','C','LIBANESA DOS REIS','11918844',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-20 16:32:34',NULL,NULL,'2019-05-20 16:32:34',NULL,NULL),('003','00586','C','JOSE MANUEL NOGUERA','14556723',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-20 16:32:34',NULL,NULL,'2019-05-20 16:32:34',NULL,NULL),('003','00587','C','JHOAN RUIX','20266771',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-20 16:32:35',NULL,NULL,'2019-05-20 16:32:35',NULL,NULL),('003','00588','C','CARLOS COLO','16547684',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-20 16:32:35',NULL,NULL,'2019-05-20 16:32:35',NULL,NULL),('003','00589','C','ANYELY LANDAETA','19003467',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-20 16:32:36',NULL,NULL,'2019-05-20 16:32:36',NULL,NULL),('003','00590','C','JHONATHAN RUIZ','16130573',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-20 16:32:37',NULL,NULL,'2019-05-20 16:32:37',NULL,NULL),('003','00591','C','MISAEL PAEZ','78652241',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-20 16:32:39',NULL,NULL,'2019-05-20 16:32:39',NULL,NULL),('003','00592','C','LUIS COX','21262461',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-20 16:32:39',NULL,NULL,'2019-05-20 16:32:39',NULL,NULL),('003','00593','C','JERLY COLINA','18170788',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-20 16:32:40',NULL,NULL,'2019-05-20 16:32:40',NULL,NULL),('003','00594','C','LUISANA FUCIL','13665782',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-20 16:32:42',NULL,NULL,'2019-05-20 16:32:42',NULL,NULL),('003','00595','C','YORDANY ZAMBRANO','24899074',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-20 18:52:25',NULL,NULL,'2019-05-20 18:52:25',NULL,NULL),('003','00596','C','JOAN FLORES','13502229',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-20 18:52:25',NULL,NULL,'2019-05-20 18:52:25',NULL,NULL),('003','00597','C','WILMER TREJO','14325673',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-20 18:52:25',NULL,NULL,'2019-05-20 18:52:25',NULL,NULL),('003','00598','C','YAMILETH GARCIA','25060676',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-20 18:52:27',NULL,NULL,'2019-05-20 18:52:27',NULL,NULL),('003','00599','C','DARWIN MONCADA','15876287',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-20 18:52:27',NULL,NULL,'2019-05-20 18:52:27',NULL,NULL),('003','00600','C','ERICK PADILLA','16382949',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-20 18:52:28',NULL,NULL,'2019-05-20 18:52:28',NULL,NULL),('003','00601','C','OSCAR GOMEZ OJEDA','99501821',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-20 18:52:29',NULL,NULL,'2019-05-20 18:52:29',NULL,NULL),('003','00602','C','IRI LUZ BLANCA','13066923',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-20 18:52:29',NULL,NULL,'2019-05-20 18:52:29',NULL,NULL),('003','00603','C','KARLA LICON','25583512',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-20 18:52:30',NULL,NULL,'2019-05-20 18:52:30',NULL,NULL),('003','00604','C','DANIEL CERVANTES','16283722',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-20 18:52:31',NULL,NULL,'2019-05-20 18:52:31',NULL,NULL),('003','00605','C','FELIX GIMENEZ','53652702',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-20 18:52:32',NULL,NULL,'2019-05-20 18:52:32',NULL,NULL),('003','00606','C','JOSE ANCIETA','40365833',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-20 18:52:34',NULL,NULL,'2019-05-20 18:52:34',NULL,NULL),('003','00607','C','RAMON JORDAN','19617712',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-20 19:31:05',NULL,NULL,'2019-05-20 19:31:05',NULL,NULL),('003','00608','C','GERALDO SUAREZ','18735052',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-20 19:31:07',NULL,NULL,'2019-05-20 19:31:07',NULL,NULL),('003','00609','C','LISSETH SANCHEZ','15963866',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-20 19:31:07',NULL,NULL,'2019-05-20 19:31:07',NULL,NULL),('003','00610','C','YESIKA GUTIERREZ','19286615',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-20 19:31:07',NULL,NULL,'2019-05-20 19:31:07',NULL,NULL),('003','00611','C','JHON GALINDO','19786537',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-20 19:31:08',NULL,NULL,'2019-05-20 19:31:08',NULL,NULL),('003','00612','C','CARLOS COLO','16386383',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-20 19:31:08',NULL,NULL,'2019-05-20 19:31:08',NULL,NULL),('003','00613','C','JOVANNI PEÑA','98068723',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-20 19:31:08',NULL,NULL,'2019-05-20 19:31:08',NULL,NULL),('003','00614','C','FRADORAMA MONAGAS','15814086',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-20 19:31:09',NULL,NULL,'2019-05-20 19:31:09',NULL,NULL),('003','00615','C','ELISAUL FERNANDEZ','26193538',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-20 19:31:11',NULL,NULL,'2019-05-20 19:31:11',NULL,NULL),('003','00616','C','DAYERSI CIBOLI','26055320',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-20 19:31:11',NULL,NULL,'2019-05-20 19:31:11',NULL,NULL),('003','00617','C','MARIA MATA','16382522',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-20 19:31:13',NULL,NULL,'2019-05-20 19:31:13',NULL,NULL),('003','00618','C','SUHAYL MARINA','12539408',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-20 19:31:14',NULL,NULL,'2019-05-20 19:31:14',NULL,NULL),('003','00619','C','DELIA PEREZ','19957026',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-20 19:31:14',NULL,NULL,'2019-05-20 19:31:14',NULL,NULL),('003','00620','C','JOSE ROMERO','15404425',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-20 19:31:15',NULL,NULL,'2019-05-20 19:31:15',NULL,NULL),('003','00621','C','ODALYS GOUDET','16008797',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-20 19:31:15',NULL,NULL,'2019-05-20 19:31:15',NULL,NULL),('003','00622','C','FREDDY MEDINA','17368341',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-20 19:31:16',NULL,NULL,'2019-05-20 19:31:16',NULL,NULL),('003','00623','C','SUSANA RICON','19729669',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-20 19:31:16',NULL,NULL,'2019-05-20 19:31:16',NULL,NULL),('003','00624','C','LUIS CASTILLA','10784252',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-20 19:31:16',NULL,NULL,'2019-05-20 19:31:16',NULL,NULL),('003','00625','C','ASTRID CEGARRA','12992842',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-20 19:31:17',NULL,NULL,'2019-05-20 19:31:17',NULL,NULL),('003','00626','C','ELSY BELEN','42785427',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-20 19:31:17',NULL,NULL,'2019-05-20 19:31:17',NULL,NULL),('003','00627','C','VIVIAN FERNANDEZ','97468634',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-20 19:31:19',NULL,NULL,'2019-05-20 19:31:19',NULL,NULL),('003','00628','C','LEONEL RUZA','13260076',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-20 20:54:29',NULL,NULL,'2019-05-20 20:54:29',NULL,NULL),('003','00629','C','ORLYMAR TRIAS','20515271',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-20 20:54:29',NULL,NULL,'2019-05-20 20:54:29',NULL,NULL),('003','00630','C','ANDRES LINARES','96687417',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-20 20:54:29',NULL,NULL,'2019-05-20 20:54:29',NULL,NULL),('003','00631','C','WILMAR TORTOZA','24087239',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-20 20:54:30',NULL,NULL,'2019-05-20 20:54:30',NULL,NULL),('003','00632','C','DIANA MENA','16543558',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-20 20:54:30',NULL,NULL,'2019-05-20 20:54:30',NULL,NULL),('003','00633','C','ROSMARY RAMON','92566005',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-20 20:54:32',NULL,NULL,'2019-05-20 20:54:32',NULL,NULL),('003','00634','C','RICHARD VILORIA','20656911',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-20 20:54:33',NULL,NULL,'2019-05-20 20:54:33',NULL,NULL),('003','00635','C','JENNIFER PIRELA','19913136',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-20 20:54:33',NULL,NULL,'2019-05-20 20:54:33',NULL,NULL),('003','00636','C','JOSE MARCOS TORRES','19202026',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-20 20:54:34',NULL,NULL,'2019-05-20 20:54:34',NULL,NULL),('003','00637','C','RAHILMA MEZA','15824293',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-20 20:54:34',NULL,NULL,'2019-05-20 20:54:34',NULL,NULL),('003','00638','C','ERICK PADILLA','16443789',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-20 20:54:34',NULL,NULL,'2019-05-20 20:54:34',NULL,NULL),('003','00639','C','ALEJANDRO SANABRIA','25917867',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-20 20:54:35',NULL,NULL,'2019-05-20 20:54:35',NULL,NULL),('003','00640','C','JHONATHAN RUIZ','16130576',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-20 20:54:36',NULL,NULL,'2019-05-20 20:54:36',NULL,NULL),('003','00641','C','JANETH BEATRIZ','48943845',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-20 20:54:36',NULL,NULL,'2019-05-20 20:54:36',NULL,NULL),('003','00642','C','OLIVER CEDEÑO','96944286',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-20 20:54:38',NULL,NULL,'2019-05-20 20:54:38',NULL,NULL),('003','00643','C','EREIDA RODRIGUEZ','86934205',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-20 20:54:40',NULL,NULL,'2019-05-20 20:54:40',NULL,NULL),('003','00644','C','FRANK CHUKY','13246557',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-20 21:26:26',NULL,NULL,'2019-05-20 21:26:26',NULL,NULL),('003','00645','C','CINTHIA CRIOLLO','27101775',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-20 21:26:27',NULL,NULL,'2019-05-20 21:26:27',NULL,NULL),('003','00646','C','JOSE HERNANDEZ','11100719',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-20 21:26:28',NULL,NULL,'2019-05-20 21:26:28',NULL,NULL),('003','00647','C','IRINED OJEDA','21405568',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-20 21:26:28',NULL,NULL,'2019-05-20 21:26:28',NULL,NULL),('003','00648','C','DANIEL ARANGUREN','15732033',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-20 21:26:28',NULL,NULL,'2019-05-20 21:26:28',NULL,NULL),('003','00649','C','BERTHILA GONZALEZ','57271253',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-20 21:26:29',NULL,NULL,'2019-05-20 21:26:29',NULL,NULL),('003','00650','C','FABIANI BECERRA','25302101',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-20 21:26:31',NULL,NULL,'2019-05-20 21:26:31',NULL,NULL),('003','00651','C','KATHERINE ESCALANTE','19792118',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-20 21:26:31',NULL,NULL,'2019-05-20 21:26:31',NULL,NULL),('003','00652','C','DANIELA GUERRERO','20788844',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-20 21:26:32',NULL,NULL,'2019-05-20 21:26:32',NULL,NULL),('003','00653','C','NUBIA GONZALEZ','96153402',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-20 21:26:32',NULL,NULL,'2019-05-20 21:26:32',NULL,NULL),('003','00654','C','LUIS PEREZ','16052954',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-20 21:26:33',NULL,NULL,'2019-05-20 21:26:33',NULL,NULL),('003','00655','C','JOSE JAIR PEÑA TORRES','17664131',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-20 21:26:33',NULL,NULL,'2019-05-20 21:26:33',NULL,NULL),('003','00656','C','YOSYMAR MOLLEDA','19006417',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-20 21:26:34',NULL,NULL,'2019-05-20 21:26:34',NULL,NULL),('003','00657','C','MARLY SEGOVIA','17037820',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-20 21:26:34',NULL,NULL,'2019-05-20 21:26:34',NULL,NULL),('003','00658','C','GIOVANNI AGUILERA','89632284',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-20 21:26:36',NULL,NULL,'2019-05-20 21:26:36',NULL,NULL),('003','00659','C','OSMAR OBREGON','14557645',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-21 20:19:14',NULL,NULL,'2019-05-21 20:19:14',NULL,NULL),('003','00660','C','ANDRES LINARES','96687415',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-21 20:19:15',NULL,NULL,'2019-05-21 20:19:15',NULL,NULL),('003','00661','C','ANDRES BRACAMONTE','28076229',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-21 20:19:16',NULL,NULL,'2019-05-21 20:19:16',NULL,NULL),('003','00662','C','ELIMAR MAURERA','21339249',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-21 20:19:16',NULL,NULL,'2019-05-21 20:19:16',NULL,NULL),('003','00663','C','SHARON MARCHENA','26425837',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-21 20:19:18',NULL,NULL,'2019-05-21 20:19:18',NULL,NULL),('003','00664','C','ALBERTO DELGADO','29647662',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-21 20:19:18',NULL,NULL,'2019-05-21 20:19:18',NULL,NULL),('003','00665','C','JEANETTE GONZALEZ','15886196',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-21 20:19:19',NULL,NULL,'2019-05-21 20:19:19',NULL,NULL),('003','00666','C','JAVIER OROPEZA','18184378',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-21 20:19:20',NULL,NULL,'2019-05-21 20:19:20',NULL,NULL),('003','00667','C','CIRILO CARDENAS','96441306',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-21 20:19:20',NULL,NULL,'2019-05-21 20:19:20',NULL,NULL),('003','00668','C','MOISES SANCHEZ','73249996',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-21 20:19:22',NULL,NULL,'2019-05-21 20:19:22',NULL,NULL),('003','00669','C','ROSMER ALVAREZ','19854787',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-21 20:19:23',NULL,NULL,'2019-05-21 20:19:23',NULL,NULL),('003','00670','C','DORIS MANZANEDA','13830312',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-21 20:19:26',NULL,NULL,'2019-05-21 20:19:26',NULL,NULL),('003','00671','C','SANDY GAMARRA','82290796',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-21 20:19:27',NULL,NULL,'2019-05-21 20:19:27',NULL,NULL),('003','00672','C','LUIS JOSE RUIZ','10819705',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-21 20:19:29',NULL,NULL,'2019-05-21 20:19:29',NULL,NULL),('003','00673','C','VICENTE MORALES','23628896',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-21 20:19:30',NULL,NULL,'2019-05-21 20:19:30',NULL,NULL),('003','00674','C','ESTER MEDINA','20085973',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-21 20:19:30',NULL,NULL,'2019-05-21 20:19:30',NULL,NULL),('003','00675','C','JESSIKA CARABALLO','25368831',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-21 20:19:30',NULL,NULL,'2019-05-21 20:19:30',NULL,NULL),('003','00676','C','OSWALDO VEGAS','19112069',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-21 20:19:32',NULL,NULL,'2019-05-21 20:19:32',NULL,NULL),('003','00677','C','ANGEL PIMENTEL','24377850',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-21 20:19:32',NULL,NULL,'2019-05-21 20:19:32',NULL,NULL),('003','00678','C','VIVIAN FERNANDEZ','97468636',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-27 20:36:15',NULL,NULL,'2019-05-27 20:36:15',NULL,NULL),('003','00679','C','JESSICA NOGUERA','26528796',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-27 20:36:15',NULL,NULL,'2019-05-27 20:36:15',NULL,NULL),('003','00680','C','KATERINE BENCOMO','27574576',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-27 20:36:17',NULL,NULL,'2019-05-27 20:36:17',NULL,NULL),('003','00681','C','CAMILO VIELOT','27807922',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-27 20:36:17',NULL,NULL,'2019-05-27 20:36:17',NULL,NULL),('003','00682','C','BERTHILA GONZALEZ','57271255',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-27 20:36:17',NULL,NULL,'2019-05-27 20:36:17',NULL,NULL),('003','00683','C','WILDER ROMERO','15789524',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-27 20:36:17',NULL,NULL,'2019-05-27 20:36:17',NULL,NULL),('003','00684','C','RUBEN COLMENARES','17499331',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-27 20:36:18',NULL,NULL,'2019-05-27 20:36:18',NULL,NULL),('003','00685','C','JUAN TORRES','68016635',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-27 20:36:19',NULL,NULL,'2019-05-27 20:36:19',NULL,NULL),('003','00686','C','TEOFILO PALACIOS','76857525',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-27 20:36:20',NULL,NULL,'2019-05-27 20:36:20',NULL,NULL),('003','00687','C','CARLOS COLMENARES','23439285',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-27 20:36:22',NULL,NULL,'2019-05-27 20:36:22',NULL,NULL),('003','00688','C','FANY MARTINEZ','99477005',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-27 20:36:23',NULL,NULL,'2019-05-27 20:36:23',NULL,NULL),('003','00689','C','MARIA GUARIQUE','14357796',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-27 20:36:24',NULL,NULL,'2019-05-27 20:36:24',NULL,NULL),('003','00690','C','JOSE ACOSTA','16052803',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-27 20:36:25',NULL,NULL,'2019-05-27 20:36:25',NULL,NULL),('003','00691','C','ELSY BELEN','42785433',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-27 20:36:26',NULL,NULL,'2019-05-27 20:36:26',NULL,NULL),('003','00692','C','LUISANA FUCIL','14554332',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-28 19:22:11',NULL,NULL,'2019-05-28 19:22:11',NULL,NULL),('003','00693','C','YULENDRI PINEDA','20303862',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-28 19:22:12',NULL,NULL,'2019-05-28 19:22:12',NULL,NULL),('003','00694','C','FANY MARTINEZ','99477004',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-28 19:22:13',NULL,NULL,'2019-05-28 19:22:13',NULL,NULL),('003','00695','C','ZOILYMAR AROCHA','27028126',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-28 19:22:14',NULL,NULL,'2019-05-28 19:22:14',NULL,NULL),('003','00696','C','RHONNY ESTRADA','24357587',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-28 19:22:14',NULL,NULL,'2019-05-28 19:22:14',NULL,NULL),('003','00697','C','FREDDY MEDINA','17368345',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-28 19:22:15',NULL,NULL,'2019-05-28 19:22:15',NULL,NULL),('003','00698','C','ELSY BELEN','12324278',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-28 19:22:16',NULL,NULL,'2019-05-28 19:22:16',NULL,NULL),('003','00699','C','GABRIEL KINGSTON','20636312',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-28 19:22:17',NULL,NULL,'2019-05-28 19:22:17',NULL,NULL),('003','00700','C','ANTONIO RODRIGUEZ','10216525',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-28 19:22:17',NULL,NULL,'2019-05-28 19:22:17',NULL,NULL),('003','00701','C','DAVID NAPAN','19500423',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-28 19:22:17',NULL,NULL,'2019-05-28 19:22:17',NULL,NULL),('003','00702','C','FRANCISBEL ZAMBRANO','27259962',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-28 21:15:06',NULL,NULL,'2019-05-28 21:15:06',NULL,NULL),('003','00703','C','ROBERTH MARTINEZ','11890364',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-28 21:15:06',NULL,NULL,'2019-05-28 21:15:06',NULL,NULL),('003','00704','C','DARWIN APONTE','22427272',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-28 21:15:07',NULL,NULL,'2019-05-28 21:15:07',NULL,NULL),('003','00705','C','RENZO CASTILLO','15277839',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-28 21:15:08',NULL,NULL,'2019-05-28 21:15:08',NULL,NULL),('003','00706','C','GRECIA MERCADO','18183769',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-28 21:15:08',NULL,NULL,'2019-05-28 21:15:08',NULL,NULL),('003','00707','C','EDUARDO PEÑA','26690088',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-28 21:15:08',NULL,NULL,'2019-05-28 21:15:08',NULL,NULL),('003','00708','C','ELSY BELEN','42784533',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-28 21:15:09',NULL,NULL,'2019-05-28 21:15:09',NULL,NULL),('003','00709','C','HAYDEE ABURTO','99026188',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-28 21:15:10',NULL,NULL,'2019-05-28 21:15:10',NULL,NULL),('003','00710','C','MARIA MATA','16382546',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-28 21:15:10',NULL,NULL,'2019-05-28 21:15:10',NULL,NULL),('003','00711','C','ELENA PEREZ','12366865',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-28 21:15:11',NULL,NULL,'2019-05-28 21:15:11',NULL,NULL),('003','00712','C','YOGENDRIS DORANTE','16868796',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-28 21:15:12',NULL,NULL,'2019-05-28 21:15:12',NULL,NULL),('003','00713','C','ALFREDO CATAÑO','10818527',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-28 21:15:12',NULL,NULL,'2019-05-28 21:15:12',NULL,NULL),('003','00714','C','WHILTHER MADRIZ','17716607',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-28 21:15:13',NULL,NULL,'2019-05-28 21:15:13',NULL,NULL),('003','00715','C','JUAN RODRIGUEZ','10312776',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-28 21:15:14',NULL,NULL,'2019-05-28 21:15:14',NULL,NULL),('003','00716','C','THOMA CENTENO','19729855',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-28 21:15:15',NULL,NULL,'2019-05-28 21:15:15',NULL,NULL),('003','00717','C','KEENBERLIN ROMERO','16997402',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-28 21:42:19',NULL,NULL,'2019-05-28 21:42:19',NULL,NULL),('003','00718','C','ELSY BELEN','42783443',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-28 21:42:20',NULL,NULL,'2019-05-28 21:42:20',NULL,NULL),('003','00719','C','MILAGROS GRILLO','20534007',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-28 21:42:23',NULL,NULL,'2019-05-28 21:42:23',NULL,NULL),('003','00720','C','CESAR SANCHEZ','98436372',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-28 21:42:23',NULL,NULL,'2019-05-28 21:42:23',NULL,NULL),('003','00721','C','MAYRELIS GUANCHEZ','16399257',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-28 21:42:23',NULL,NULL,'2019-05-28 21:42:23',NULL,NULL),('003','00722','C','RONALD MEDINA','19848529',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-28 21:42:25',NULL,NULL,'2019-05-28 21:42:25',NULL,NULL),('003','00723','C','FRANK ANDRADE ','16753765',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-28 21:42:25',NULL,NULL,'2019-05-28 21:42:25',NULL,NULL),('003','00724','C','MARIA SUAREZ','13465533',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-29 15:40:54',NULL,NULL,'2019-05-29 15:40:54',NULL,NULL),('003','00725','C','JUAN MANUEL SUAREZ','13321553',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-29 15:40:54',NULL,NULL,'2019-05-29 15:40:54',NULL,NULL),('003','00726','C','JULIO SANCHEZ','22331180',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-29 15:40:55',NULL,NULL,'2019-05-29 15:40:55',NULL,NULL),('003','00727','C','ARGENIS OLIVEROS','15224785',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-29 15:40:55',NULL,NULL,'2019-05-29 15:40:55',NULL,NULL),('003','00728','C','SORELY RAMÍREZ','15532803',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-29 15:40:56',NULL,NULL,'2019-05-29 15:40:56',NULL,NULL),('003','00729','C','JOSE MANUEL NOGUERA','20651998',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-29 15:40:56',NULL,NULL,'2019-05-29 15:40:56',NULL,NULL),('003','00730','C','JESUS HUAMAN','23276547',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-29 15:40:58',NULL,NULL,'2019-05-29 15:40:58',NULL,NULL),('003','00731','C','CRISTINA FAGUNDEZ','27985412',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-29 15:40:58',NULL,NULL,'2019-05-29 15:40:58',NULL,NULL),('003','00732','C','MARIANA RODRIGUEZ','15665083',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-29 15:40:59',NULL,NULL,'2019-05-29 15:40:59',NULL,NULL),('003','00733','C','MAGDELYS AVENDAÑO','14768893',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-29 15:41:01',NULL,NULL,'2019-05-29 15:41:01',NULL,NULL),('003','00734','C','JAVIER ZELADA','41823039',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-29 15:41:01',NULL,NULL,'2019-05-29 15:41:01',NULL,NULL),('003','00735','C','JOSE GREGORIO MATHEUS','93142594',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-29 16:18:16',NULL,NULL,'2019-05-29 16:18:16',NULL,NULL),('003','00736','C','JOSE BENCOMO','12499423',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-29 16:18:18',NULL,NULL,'2019-05-29 16:18:18',NULL,NULL),('003','00737','C','RICARDO HERNANDEZ','20920538',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-29 16:18:18',NULL,NULL,'2019-05-29 16:18:18',NULL,NULL),('003','00738','C','MARYORIE ECHEVERRIA','20263212',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-29 16:18:19',NULL,NULL,'2019-05-29 16:18:19',NULL,NULL),('003','00739','C','JUNEISSY QUINTERO','20127435',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-29 16:18:20',NULL,NULL,'2019-05-29 16:18:20',NULL,NULL),('003','00740','C','ELBANO GUERRERO','81032573',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-29 16:18:21',NULL,NULL,'2019-05-29 16:18:21',NULL,NULL),('003','00741','C','MARIA PEREZ','82766053',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-29 16:18:23',NULL,NULL,'2019-05-29 16:18:23',NULL,NULL),('003','00742','C','JEAN PIERO ROMAN PARRA','13007603',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-29 16:18:24',NULL,NULL,'2019-05-29 16:18:24',NULL,NULL),('003','00743','C','EMMA GUERRERO','99866474',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-29 16:18:25',NULL,NULL,'2019-05-29 16:18:25',NULL,NULL),('003','00744','C','EMMA GUERRERO','99866473',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-29 16:18:26',NULL,NULL,'2019-05-29 16:18:26',NULL,NULL),('003','00745','C','GUILLERMO CABANILLAS','16671363',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-29 16:18:30',NULL,NULL,'2019-05-29 16:18:30',NULL,NULL),('003','00746','C','MARIA PEREZ','82766054',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-29 16:18:31',NULL,NULL,'2019-05-29 16:18:31',NULL,NULL),('003','00747','C','YOALVIC CONTRERAS','17970061',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-29 16:51:38',NULL,NULL,'2019-05-29 16:51:38',NULL,NULL),('003','00748','C','JUAN MADRIZ','25464423',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-29 16:51:40',NULL,NULL,'2019-05-29 16:51:40',NULL,NULL),('003','00749','C','FELIX GIMENEZ','53652704',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-29 16:51:41',NULL,NULL,'2019-05-29 16:51:41',NULL,NULL),('003','00750','C','FREDDY MEDINA','17368372',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-29 16:51:43',NULL,NULL,'2019-05-29 16:51:43',NULL,NULL),('003','00751','C','DANIEL MEJIAS','20626232',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-29 16:51:43',NULL,NULL,'2019-05-29 16:51:43',NULL,NULL),('003','00752','C','CARLOS ACOSTA','18596947',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-29 16:51:43',NULL,NULL,'2019-05-29 16:51:43',NULL,NULL),('003','00753','C','BERTHILA GONZALEZ','57271254',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-29 16:51:44',NULL,NULL,'2019-05-29 16:51:44',NULL,NULL),('003','00754','C','JOVANNI PEÑA','98068724',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-29 16:51:44',NULL,NULL,'2019-05-29 16:51:44',NULL,NULL),('003','00755','C','ASNEIDY BORGES','21200423',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-29 16:51:45',NULL,NULL,'2019-05-29 16:51:45',NULL,NULL),('003','00756','C','ERASMO VIVAS','14436685',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-29 16:51:46',NULL,NULL,'2019-05-29 16:51:46',NULL,NULL),('003','00757','C','RAMON SANGUINO','16594084',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-29 16:51:47',NULL,NULL,'2019-05-29 16:51:47',NULL,NULL),('003','00758','C','ELEANA BRITO','25684635',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-31 15:41:13',NULL,NULL,'2019-05-31 15:41:13',NULL,NULL),('003','00759','C','JOEL GRIMALDI','21456570',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-31 15:41:13',NULL,NULL,'2019-05-31 15:41:13',NULL,NULL),('003','00760','C','ELSY BELEN','42784568',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-31 15:41:14',NULL,NULL,'2019-05-31 15:41:14',NULL,NULL),('003','00761','C','YOANNY YARI','17103185',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-31 15:41:15',NULL,NULL,'2019-05-31 15:41:15',NULL,NULL),('003','00762','C','DARWIN GONZALEZ','16856050',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-31 15:41:16',NULL,NULL,'2019-05-31 15:41:16',NULL,NULL),('003','00763','C','WILLIAM RIVERO PENA','23835009',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-31 15:41:16',NULL,NULL,'2019-05-31 15:41:16',NULL,NULL),('003','00764','C','LUISANA SUCRE','20059620',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-31 15:41:16',NULL,NULL,'2019-05-31 15:41:16',NULL,NULL),('003','00765','C','KATERINE BENCOMO','27573553',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-31 15:41:17',NULL,NULL,'2019-05-31 15:41:17',NULL,NULL),('003','00766','C','MISAEL PAEZ','78652232',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-31 15:41:17',NULL,NULL,'2019-05-31 15:41:17',NULL,NULL),('003','00767','C','LUIS JOSE ROJAS BRICEÑO','14487632',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-31 15:41:18',NULL,NULL,'2019-05-31 15:41:18',NULL,NULL),('003','00768','C','JESUS CASTILLO','79065332',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-31 15:41:19',NULL,NULL,'2019-05-31 15:41:19',NULL,NULL),('003','00769','C','KELVIN CEDEÑO','21225718',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-31 20:02:29',NULL,NULL,'2019-05-31 20:02:29',NULL,NULL),('003','00770','C','KELIP LINARES','18056944',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-31 20:02:29',NULL,NULL,'2019-05-31 20:02:29',NULL,NULL),('003','00771','C','ELSY BELEN','42783434',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-31 20:02:31',NULL,NULL,'2019-05-31 20:02:31',NULL,NULL),('003','00772','C','LUIS NAVA','29360792',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-31 20:02:31',NULL,NULL,'2019-05-31 20:02:31',NULL,NULL),('003','00773','C','LUCIMAR MARTINEZ','14557642',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-31 20:02:32',NULL,NULL,'2019-05-31 20:02:32',NULL,NULL),('003','00774','C','ANDREINA RIVAS','99701684',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-31 20:02:32',NULL,NULL,'2019-05-31 20:02:32',NULL,NULL),('003','00775','C','BRIGGITTE MEDINA','24572157',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-31 20:02:33',NULL,NULL,'2019-05-31 20:02:33',NULL,NULL),('003','00776','C','JORGE FERREIRA','15478518',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-31 20:02:33',NULL,NULL,'2019-05-31 20:02:33',NULL,NULL),('003','00777','C','ORLANDO LORBES','16543456',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-31 20:02:35',NULL,NULL,'2019-05-31 20:02:35',NULL,NULL),('003','00778','C','GIOVANNI AGUILERA','89632285',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-31 20:02:36',NULL,NULL,'2019-05-31 20:02:36',NULL,NULL),('003','00779','C','JHONATHAN RUIZ','16130574',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-31 20:02:37',NULL,NULL,'2019-05-31 20:02:37',NULL,NULL),('003','00780','C','ORLANDO LORBES','15478963',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-05-31 20:02:37',NULL,NULL,'2019-05-31 20:02:37',NULL,NULL),('003','00781','C','JESUS MORENO','17309803',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-03 22:03:37',NULL,NULL,'2019-06-03 22:03:37',NULL,NULL),('003','00782','C','ROSA DELLEPIANE','95309263',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-03 22:03:38',NULL,NULL,'2019-06-03 22:03:38',NULL,NULL),('003','00783','C','LUIS LOPEZ','19540094',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-03 22:03:38',NULL,NULL,'2019-06-03 22:03:38',NULL,NULL),('003','00784','C','VIVIAN FERNANDEZ','97468633',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-03 22:03:38',NULL,NULL,'2019-06-03 22:03:38',NULL,NULL),('003','00785','C','ALBERTO REYES','11678199',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-03 22:03:39',NULL,NULL,'2019-06-03 22:03:39',NULL,NULL),('003','00786','C','DANIEL SOTO','25862437',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-03 22:03:40',NULL,NULL,'2019-06-03 22:03:40',NULL,NULL),('003','00787','C','ROSSMERY YEPES','27621680',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-03 22:03:41',NULL,NULL,'2019-06-03 22:03:41',NULL,NULL),('003','00788','C','HAYDEE ABURTO','99026183',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-03 22:03:42',NULL,NULL,'2019-06-03 22:03:42',NULL,NULL),('003','00789','C','JOSE GREGORIO GONZALEZ','20267177',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-03 22:03:42',NULL,NULL,'2019-06-03 22:03:42',NULL,NULL),('003','00790','C','NORELKYS LEAL','14449072',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-03 22:03:43',NULL,NULL,'2019-06-03 22:03:43',NULL,NULL),('003','00791','C','WILIA DIAZ','88248001',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-03 22:03:43',NULL,NULL,'2019-06-03 22:03:43',NULL,NULL),('003','00792','C','FREDDY MEDINA','17368312',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-03 22:03:44',NULL,NULL,'2019-06-03 22:03:44',NULL,NULL),('003','00793','C','CARLOS CANDELA','20615430',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-03 22:03:45',NULL,NULL,'2019-06-03 22:03:45',NULL,NULL),('003','00794','C','DANIEL ARAUJO','64376882',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-03 22:03:46',NULL,NULL,'2019-06-03 22:03:46',NULL,NULL),('003','00795','C','RICARDO ROSELL','25848987',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-05 18:34:51',NULL,NULL,'2019-06-05 18:34:51',NULL,NULL),('003','00796','C','EDIXON WILLANDER LEAL AGUERO','12543357',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-05 18:34:52',NULL,NULL,'2019-06-05 18:34:52',NULL,NULL),('003','00797','C','ALEXA GUERRERO','25374550',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-05 18:34:56',NULL,NULL,'2019-06-05 18:34:56',NULL,NULL),('003','00798','C','NATHALY DA SILVA','14457554',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-05 18:34:57',NULL,NULL,'2019-06-05 18:34:57',NULL,NULL),('003','00799','C','MARIA MATA','16382545',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-05 18:34:58',NULL,NULL,'2019-06-05 18:34:58',NULL,NULL),('003','00800','C','ADNER GODOY','20198565',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-05 19:23:05',NULL,NULL,'2019-06-05 19:23:05',NULL,NULL),('003','00801','C','JOSETH RODRIGUEZ','13713615',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-05 19:23:05',NULL,NULL,'2019-06-05 19:23:05',NULL,NULL),('003','00802','C','MISAEL PAEZ','78652267',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-05 19:23:07',NULL,NULL,'2019-06-05 19:23:07',NULL,NULL),('003','00803','C','ELIAS GUTIERREZ','28021744',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-05 19:23:07',NULL,NULL,'2019-06-05 19:23:07',NULL,NULL),('003','00804','C','MILKA CHIRINOS','17067117',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-05 19:23:08',NULL,NULL,'2019-06-05 19:23:08',NULL,NULL),('003','00805','C','JESUS GUTIERREZ','24357671',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-05 19:23:08',NULL,NULL,'2019-06-05 19:23:08',NULL,NULL),('003','00806','C','JACKILN ARISMENDI','16653324',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-05 19:23:09',NULL,NULL,'2019-06-05 19:23:09',NULL,NULL),('003','00807','C','FELIX GIMENEZ','53652706',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-05 19:23:10',NULL,NULL,'2019-06-05 19:23:10',NULL,NULL),('003','00808','C','ENRIQUE RODRIGUEZ','16553576',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-05 19:23:11',NULL,NULL,'2019-06-05 19:23:11',NULL,NULL),('003','00809','C','KERVIS SANCHEZ','16653789',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-05 19:23:11',NULL,NULL,'2019-06-05 19:23:11',NULL,NULL),('003','00810','C','JOSE GREGORIO VIELMA','17528617',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-05 19:23:12',NULL,NULL,'2019-06-05 19:23:12',NULL,NULL),('003','00811','C','NERIO CEPEDA','14975429',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-05 19:23:13',NULL,NULL,'2019-06-05 19:23:13',NULL,NULL),('003','00812','C','ANTHONY MEDINA','16534579',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-05 19:23:13',NULL,NULL,'2019-06-05 19:23:13',NULL,NULL),('003','00813','C','ALVARO RAMIREZ','13872981',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-05 19:23:15',NULL,NULL,'2019-06-05 19:23:15',NULL,NULL),('003','00814','C','CARMEN RODRIGUEZ','13948189',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-05 19:23:17',NULL,NULL,'2019-06-05 19:23:17',NULL,NULL),('003','00815','C','LEONEL ZAMBRANO','44313276',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-05 19:23:20',NULL,NULL,'2019-06-05 19:23:20',NULL,NULL),('003','00816','C','ANDREA CAMPOS','15542576',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-05 19:23:21',NULL,NULL,'2019-06-05 19:23:21',NULL,NULL),('003','00817','C','BLANCA GONZALEZ','14186502',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-05 20:17:33',NULL,NULL,'2019-06-05 20:17:33',NULL,NULL),('003','00818','C','ELIAS RIOS','19322534',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-05 20:17:34',NULL,NULL,'2019-06-05 20:17:34',NULL,NULL),('003','00819','C','YOMAIRY JIMÉNEZ MONTILLA','18728692',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-05 20:17:35',NULL,NULL,'2019-06-05 20:17:35',NULL,NULL),('003','00820','C','MAOLY RIERA','24914858',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-05 20:17:35',NULL,NULL,'2019-06-05 20:17:35',NULL,NULL),('003','00821','C','TULIO VALERO','15221953',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-05 20:17:37',NULL,NULL,'2019-06-05 20:17:37',NULL,NULL),('003','00822','C','ELSY BELEN','42784356',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-05 20:17:38',NULL,NULL,'2019-06-05 20:17:38',NULL,NULL),('003','00823','C','LUIS CASTRO','18931638',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-05 20:17:39',NULL,NULL,'2019-06-05 20:17:39',NULL,NULL),('003','00824','C','DANIEL LIMA','17711031',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-05 20:17:39',NULL,NULL,'2019-06-05 20:17:39',NULL,NULL),('003','00825','C','ERWIN AROCHA','14357532',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-05 20:17:39',NULL,NULL,'2019-06-05 20:17:39',NULL,NULL),('003','00826','C','ROSA DELLEPIANE','95309261',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-05 20:17:40',NULL,NULL,'2019-06-05 20:17:40',NULL,NULL),('003','00827','C','ROBERTH SAUCEDO','24042081',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-05 20:17:42',NULL,NULL,'2019-06-05 20:17:42',NULL,NULL),('003','00828','C','WILDER ROMERO','42139827',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-05 20:17:43',NULL,NULL,'2019-06-05 20:17:43',NULL,NULL),('003','00829','C','DUKLENNYS URBINA','30215378',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-05 20:17:43',NULL,NULL,'2019-06-05 20:17:43',NULL,NULL),('003','00830','C','MANUEL JOSE ROJAS MEJIA','11668282',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-05 20:17:44',NULL,NULL,'2019-06-05 20:17:44',NULL,NULL),('003','00831','C','ERIKA','14355753',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-05 20:17:44',NULL,NULL,'2019-06-05 20:17:44',NULL,NULL),('003','00832','C','MOISES SANCHEZ','73249995',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-05 20:17:45',NULL,NULL,'2019-06-05 20:17:45',NULL,NULL),('003','00833','C','STEPHANIE CISNEROS','21444552',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-05 20:17:46',NULL,NULL,'2019-06-05 20:17:46',NULL,NULL),('003','00834','C','LUIS ENRIQUE','26371247',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-07 20:40:48',NULL,NULL,'2019-06-07 20:40:48',NULL,NULL),('003','00835','C','JESSICA VERENZUELA','23658947',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-07 20:40:48',NULL,NULL,'2019-06-07 20:40:48',NULL,NULL),('003','00836','C','DANIEL MEJIAS','20626230',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-07 20:40:49',NULL,NULL,'2019-06-07 20:40:49',NULL,NULL),('003','00837','C','JESSICA VILCHEZ','16727770',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-07 20:40:49',NULL,NULL,'2019-06-07 20:40:49',NULL,NULL),('003','00838','C','FRANCISCO ALVAREZ','98890910',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-07 20:40:50',NULL,NULL,'2019-06-07 20:40:50',NULL,NULL),('003','00839','C','FELIX GIMENEZ','53652700',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-07 20:40:50',NULL,NULL,'2019-06-07 20:40:50',NULL,NULL),('003','00840','C','LOLYMAR QUINTERO','66536520',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-07 20:40:53',NULL,NULL,'2019-06-07 20:40:53',NULL,NULL),('003','00841','C','WILMER SANCHEZ','19052199',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-07 20:40:54',NULL,NULL,'2019-06-07 20:40:54',NULL,NULL),('003','00842','C','LESLIE ROMERO','25751413',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-07 20:40:54',NULL,NULL,'2019-06-07 20:40:54',NULL,NULL),('003','00843','C','CARLOS ANTONIO LUJAN NAVARRO','68462794',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-07 20:40:55',NULL,NULL,'2019-06-07 20:40:55',NULL,NULL),('003','00844','C','NOHELY MARQUEZ','12440915',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-07 20:40:56',NULL,NULL,'2019-06-07 20:40:56',NULL,NULL),('003','00845','C','ERASMO VIVAS','17876994',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-07 20:40:57',NULL,NULL,'2019-06-07 20:40:57',NULL,NULL),('003','00846','C','CARLOS PARRAS','22654608',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-07 20:40:58',NULL,NULL,'2019-06-07 20:40:58',NULL,NULL),('003','00847','C','ALEJANDRO JOSE ROLLYS','18110591',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-07 21:18:18',NULL,NULL,'2019-06-07 21:18:18',NULL,NULL),('003','00848','C','FANNY GALINDO','16689959',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-07 21:18:19',NULL,NULL,'2019-06-07 21:18:19',NULL,NULL),('003','00849','C','XIOMARA GONZALEZ','45689753',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-07 21:18:20',NULL,NULL,'2019-06-07 21:18:20',NULL,NULL),('003','00850','C','MARIA PEREZ','82766050',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-07 21:18:23',NULL,NULL,'2019-06-07 21:18:23',NULL,NULL),('003','00851','C','ZULI OMAR','11139523',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-07 21:18:24',NULL,NULL,'2019-06-07 21:18:24',NULL,NULL),('003','00852','C','KAREN MALARIAGA','13161683',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-07 21:18:24',NULL,NULL,'2019-06-07 21:18:24',NULL,NULL),('003','00853','C','TOMAS VISBAL','20141540',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-07 21:18:24',NULL,NULL,'2019-06-07 21:18:24',NULL,NULL),('003','00854','C','MARIA MALDONADO PEREZ','96765623',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-07 21:18:25',NULL,NULL,'2019-06-07 21:18:25',NULL,NULL),('003','00855','C','ERNESTO SUAREZ','21692183',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-07 21:18:26',NULL,NULL,'2019-06-07 21:18:26',NULL,NULL),('003','00856','C','ELSY BELEN','42780000',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-07 21:18:26',NULL,NULL,'2019-06-07 21:18:26',NULL,NULL),('003','00857','C','LUZ MARIA JAVIER PEVE','94734333',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-07 21:18:26',NULL,NULL,'2019-06-07 21:18:26',NULL,NULL),('003','00858','C','LILIANA CAIRES','16342450',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-10 19:38:25',NULL,NULL,'2019-06-10 19:38:25',NULL,NULL),('003','00859','C','JOSE MARCOS TORRES','19202020',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-10 19:38:26',NULL,NULL,'2019-06-10 19:38:26',NULL,NULL),('003','00860','C','JORGE ROMERO','14435667',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-11 16:34:50',NULL,NULL,'2019-06-11 16:34:50',NULL,NULL),('003','00861','C','JOSE GREGORIO VIELMA','17528613',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-11 16:34:51',NULL,NULL,'2019-06-11 16:34:51',NULL,NULL),('003','00862','C','OSCAR MIQUILENA','12131338',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-11 16:34:52',NULL,NULL,'2019-06-11 16:34:52',NULL,NULL),('003','00863','C','FREDDY MEDINA','17368300',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-11 16:34:53',NULL,NULL,'2019-06-11 16:34:53',NULL,NULL),('003','00864','C','ERNESTO LENIN GONZALEZ MECHAN','16627513',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-11 16:34:53',NULL,NULL,'2019-06-11 16:34:53',NULL,NULL),('003','00865','C','GUSMARLI MATOS','21344294',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-11 16:34:54',NULL,NULL,'2019-06-11 16:34:54',NULL,NULL),('003','00866','C','YOEL SALCEDO','40038575',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-11 16:34:54',NULL,NULL,'2019-06-11 16:34:54',NULL,NULL),('003','00867','C','MARIO ARAUJO','26482973',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-11 16:34:55',NULL,NULL,'2019-06-11 16:34:55',NULL,NULL),('003','00868','C','JEANNETH SEGOBIA','22420003',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-11 16:34:55',NULL,NULL,'2019-06-11 16:34:55',NULL,NULL),('003','00869','C','ANA CARDENAS','10175223',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-11 16:34:56',NULL,NULL,'2019-06-11 16:34:56',NULL,NULL),('003','00870','C','JOSE LUIS CASTELLANO','17369576',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-11 21:47:10',NULL,NULL,'2019-06-11 21:47:10',NULL,NULL),('003','00871','C','JUAN SUAREZ','13322553',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-11 21:47:12',NULL,NULL,'2019-06-11 21:47:12',NULL,NULL),('003','00872','C','HECTOR VALERIO','20054333',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-11 21:47:15',NULL,NULL,'2019-06-11 21:47:15',NULL,NULL),('003','00873','C','YEINIDEL ROMERO','30243575',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-11 21:47:16',NULL,NULL,'2019-06-11 21:47:16',NULL,NULL),('003','00874','C','JIMMY NUÑEZ','19844796',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-11 21:47:17',NULL,NULL,'2019-06-11 21:47:17',NULL,NULL),('003','00875','C','ROGERT POLANCO','10567497',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-11 21:47:17',NULL,NULL,'2019-06-11 21:47:17',NULL,NULL),('003','00876','C','MIGUEL PIMENTEL','15435643',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-11 21:47:18',NULL,NULL,'2019-06-11 21:47:18',NULL,NULL),('003','00877','C','ANDREINA RIVAS','99701680',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-11 21:47:21',NULL,NULL,'2019-06-11 21:47:21',NULL,NULL),('003','00878','C','CESAR GARCIA','48975469',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-11 21:47:22',NULL,NULL,'2019-06-11 21:47:22',NULL,NULL),('003','00879','C','YUSMERY RANGEL','15791028',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-11 21:47:25',NULL,NULL,'2019-06-11 21:47:25',NULL,NULL),('003','00880','C','DANIEL ARAUJO','64376880',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-11 21:47:28',NULL,NULL,'2019-06-11 21:47:28',NULL,NULL),('003','00881','C','CARMEN MARTINEZ','86860184',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-11 21:47:30',NULL,NULL,'2019-06-11 21:47:30',NULL,NULL),('003','00882','C','MARIANGELICA LINAREZ','16382824',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-12 21:03:51',NULL,NULL,'2019-06-12 21:03:51',NULL,NULL),('003','00883','C','MARISELYS CONTRERAS','17581906',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-12 21:03:52',NULL,NULL,'2019-06-12 21:03:52',NULL,NULL),('003','00884','C','ALEJANDRO RIVERO','24892969',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-12 21:03:55',NULL,NULL,'2019-06-12 21:03:55',NULL,NULL),('003','00885','C','CARLOS PUGA','16373547',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-12 21:03:56',NULL,NULL,'2019-06-12 21:03:56',NULL,NULL),('003','00886','C','JOSEPH VERENZUELA','14238738',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-12 21:03:58',NULL,NULL,'2019-06-12 21:03:58',NULL,NULL),('003','00887','C','MISAEL PAEZ','78652231',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-13 21:25:09',NULL,NULL,'2019-06-13 21:25:09',NULL,NULL),('003','00888','C','YORDAN SENESI','20959934',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-13 21:25:10',NULL,NULL,'2019-06-13 21:25:10',NULL,NULL),('003','00889','C','JEFFERLINE SILVERA','19620204',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-13 21:25:11',NULL,NULL,'2019-06-13 21:25:11',NULL,NULL),('003','00890','C','MOISES SANCHEZ','73249990',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-13 21:25:11',NULL,NULL,'2019-06-13 21:25:11',NULL,NULL),('003','00891','C','LEONARDO LOPEZ','25893798',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-13 21:25:12',NULL,NULL,'2019-06-13 21:25:12',NULL,NULL),('003','00892','C','ARGELIS MILLAN','15433667',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-13 21:25:13',NULL,NULL,'2019-06-13 21:25:13',NULL,NULL),('003','00893','C','MISAEL PAEZ','78652254',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-13 21:25:14',NULL,NULL,'2019-06-13 21:25:14',NULL,NULL),('003','00894','C','EDUARDO GIMENEZ','14744925',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-13 21:25:15',NULL,NULL,'2019-06-13 21:25:15',NULL,NULL),('003','00895','C','NOREIDA CORDOVA TRENARD','17197902',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-13 21:25:17',NULL,NULL,'2019-06-13 21:25:17',NULL,NULL),('003','00896','C','DARELIS HERNANDEZ','19794752',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-17 15:38:28',NULL,NULL,'2019-06-17 15:38:28',NULL,NULL),('003','00897','C','LUCIMAR MARTINEZ','17572175',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-17 15:38:30',NULL,NULL,'2019-06-17 15:38:30',NULL,NULL),('003','00898','C','CARLOS PUGA','17422245',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-17 15:38:30',NULL,NULL,'2019-06-17 15:38:30',NULL,NULL),('003','00899','C','YESIKA ACOSTA','17610911',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-17 15:38:31',NULL,NULL,'2019-06-17 15:38:31',NULL,NULL),('003','00900','C','CARLOS COLO','15678983',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-17 15:38:31',NULL,NULL,'2019-06-17 15:38:31',NULL,NULL),('003','00901','C','ANDREA CAMPOS','15321214',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-17 15:38:33',NULL,NULL,'2019-06-17 15:38:33',NULL,NULL),('003','00902','C','ERIKA','14543244',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-17 15:38:33',NULL,NULL,'2019-06-17 15:38:33',NULL,NULL),('003','00903','C','CIRILO CARDENAS','96441304',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-17 15:38:34',NULL,NULL,'2019-06-17 15:38:34',NULL,NULL),('003','00904','C','JHON ROMERO','14543211',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-17 15:38:36',NULL,NULL,'2019-06-17 15:38:36',NULL,NULL),('003','00905','C','GERALDINE','16578993',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-17 15:38:37',NULL,NULL,'2019-06-17 15:38:37',NULL,NULL),('003','00906','C','ERICK PADILLA','16432632',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-17 15:38:38',NULL,NULL,'2019-06-17 15:38:38',NULL,NULL),('003','00907','C','WILIA DIAZ','88248000',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-17 15:38:39',NULL,NULL,'2019-06-17 15:38:39',NULL,NULL),('003','00908','C','LILIA GARCIA','84514324',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-17 15:38:40',NULL,NULL,'2019-06-17 15:38:40',NULL,NULL),('003','00909','C','GERMAN GARCIA','13558653',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-17 15:38:42',NULL,NULL,'2019-06-17 15:38:42',NULL,NULL),('003','00910','C','KARIAANGEL MORENO','24155185',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-18 11:19:15',NULL,NULL,'2019-06-18 11:19:15',NULL,NULL),('003','00911','C','JOSELIN MORENO','21479002',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-18 11:19:19',NULL,NULL,'2019-06-18 11:19:19',NULL,NULL),('003','00912','C','YULINA ALCALA','15789365',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-18 11:19:20',NULL,NULL,'2019-06-18 11:19:20',NULL,NULL),('003','00913','C','JOSETH RODRIGUEZ','13713613',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-18 11:19:20',NULL,NULL,'2019-06-18 11:19:20',NULL,NULL),('003','00914','C','LUIS ROJAS','19654031',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-18 11:19:22',NULL,NULL,'2019-06-18 11:19:22',NULL,NULL),('003','00915','C','JORGE LEON','16228615',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-18 11:51:45',NULL,NULL,'2019-06-18 11:51:45',NULL,NULL),('003','00916','C','SERGIO FUENMAYOR','16213630',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-18 11:51:45',NULL,NULL,'2019-06-18 11:51:45',NULL,NULL),('003','00917','C','VIVIAN FERNANDEZ','97468630',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-18 11:51:46',NULL,NULL,'2019-06-18 11:51:46',NULL,NULL),('003','00918','C','PEDRO PEÑA BALSA','10321741',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-18 11:51:47',NULL,NULL,'2019-06-18 11:51:47',NULL,NULL),('003','00919','C','ERIKA MENDOZA','14345575',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-18 11:51:47',NULL,NULL,'2019-06-18 11:51:47',NULL,NULL),('003','00920','C','JORGE ALCOCER','24846037',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-18 11:51:52',NULL,NULL,'2019-06-18 11:51:52',NULL,NULL),('003','00921','C','SHERLY SANCHEZ','20364850',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-18 11:51:52',NULL,NULL,'2019-06-18 11:51:52',NULL,NULL),('003','00922','C','GUSTAVO LLAUCA','15772637',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-18 16:07:56',NULL,NULL,'2019-06-18 16:07:56',NULL,NULL),('003','00923','C','EMILIS ROJAS','14620823',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-18 16:07:57',NULL,NULL,'2019-06-18 16:07:57',NULL,NULL),('003','00924','C','SELENE CHACIN','12692165',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-18 16:07:57',NULL,NULL,'2019-06-18 16:07:57',NULL,NULL),('003','00925','C','CARLOS COLO','15445357',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-18 16:08:01',NULL,NULL,'2019-06-18 16:08:01',NULL,NULL),('003','00926','C','MADELINE SANCHEZ','16244679',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-18 16:08:02',NULL,NULL,'2019-06-18 16:08:02',NULL,NULL),('003','00927','C','ALEJANDRA NAVA','16772563',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-18 16:08:04',NULL,NULL,'2019-06-18 16:08:04',NULL,NULL),('003','00928','C','GERMAN GARCIA','17652325',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-18 16:08:11',NULL,NULL,'2019-06-18 16:08:11',NULL,NULL),('003','00929','C','EMILY BARRETO','20585224',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-20 15:30:24',NULL,NULL,'2019-06-20 15:30:24',NULL,NULL),('003','00930','C','JUAN TORRES','68016630',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-20 15:30:24',NULL,NULL,'2019-06-20 15:30:24',NULL,NULL),('003','00931','C','DANIEL LOPEZ','16727647',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-20 15:30:26',NULL,NULL,'2019-06-20 15:30:26',NULL,NULL),('003','00932','C','LUIS ESPINOZA','20598279',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-20 15:30:26',NULL,NULL,'2019-06-20 15:30:26',NULL,NULL),('003','00933','C','GABRIEL SANCHEZ','19964951',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-20 15:30:27',NULL,NULL,'2019-06-20 15:30:27',NULL,NULL),('003','00934','C','JOSE PEREZ','14055190',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-20 15:30:29',NULL,NULL,'2019-06-20 15:30:29',NULL,NULL),('003','00935','C','JOVANNI PEÑA','98068720',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-20 15:30:29',NULL,NULL,'2019-06-20 15:30:29',NULL,NULL),('003','00936','C','JESUS RUIZ','17627879',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-20 15:30:30',NULL,NULL,'2019-06-20 15:30:30',NULL,NULL),('003','00937','C','WILSON TORRES','42698363',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-20 15:30:32',NULL,NULL,'2019-06-20 15:30:32',NULL,NULL),('003','00938','C','HUGO CAMPOS GIRON','94163703',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-20 15:30:37',NULL,NULL,'2019-06-20 15:30:37',NULL,NULL),('003','00939','C','VALESKA DARIAS','26954155',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-20 15:43:13',NULL,NULL,'2019-06-20 15:43:13',NULL,NULL),('003','00940','C','CIRILO CARDENAS','96441303',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-20 15:43:15',NULL,NULL,'2019-06-20 15:43:15',NULL,NULL),('003','00941','C','JAISI PEREZ','16638882',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-20 15:43:15',NULL,NULL,'2019-06-20 15:43:15',NULL,NULL),('003','00942','C','HUGO CAMPOS GIRON','94163704',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-20 15:43:18',NULL,NULL,'2019-06-20 15:43:18',NULL,NULL),('003','00943','C','FERNANDO PARRA','12494384',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-20 15:43:21',NULL,NULL,'2019-06-20 15:43:21',NULL,NULL),('003','00944','C','GIOVANNI AGUILERA','89632283',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-20 15:43:21',NULL,NULL,'2019-06-20 15:43:21',NULL,NULL),('003','00945','C','JEISSON RODRIGUEZ','20986493',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-20 15:43:22',NULL,NULL,'2019-06-20 15:43:22',NULL,NULL),('003','00946','C','YORMAN ALFONZO','20591315',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-21 16:57:29',NULL,NULL,'2019-06-21 16:57:29',NULL,NULL),('003','00947','C','YULINA ALCALA','18126212',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-21 16:57:30',NULL,NULL,'2019-06-21 16:57:30',NULL,NULL),('003','00948','C','ELBANO GUERRERO','81032570',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-21 16:57:30',NULL,NULL,'2019-06-21 16:57:30',NULL,NULL),('003','00949','C','MARIANGELICA LINAREZ','30125127',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-21 16:57:31',NULL,NULL,'2019-06-21 16:57:31',NULL,NULL),('003','00950','C','FREDDY ANTONIO ESCOBAR RODRIGUEZ','12370438',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-21 16:57:32',NULL,NULL,'2019-06-21 16:57:32',NULL,NULL),('003','00951','C','JUNIOR LINARES','21404687',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-21 16:57:32',NULL,NULL,'2019-06-21 16:57:32',NULL,NULL),('003','00952','C','YENY POCHO','15467833',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-21 16:57:33',NULL,NULL,'2019-06-21 16:57:33',NULL,NULL),('003','00953','C','JOEL RAMOS','18544734',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-21 16:57:33',NULL,NULL,'2019-06-21 16:57:33',NULL,NULL),('003','00954','C','MARIA SERRADA','19314596',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-21 16:57:33',NULL,NULL,'2019-06-21 16:57:33',NULL,NULL),('003','00955','C','ALEJANDRO ZARATE','15020547',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-21 16:57:34',NULL,NULL,'2019-06-21 16:57:34',NULL,NULL),('003','00956','C','MISAEL PAEZ','78652200',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-21 16:57:35',NULL,NULL,'2019-06-21 16:57:35',NULL,NULL),('003','00957','C','ALEXANDRA MOLINA','25067055',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-25 23:38:56',NULL,NULL,'2019-06-25 23:38:56',NULL,NULL),('003','00958','C','GERMAN GARCIA','16458478',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-25 23:38:57',NULL,NULL,'2019-06-25 23:38:57',NULL,NULL),('003','00959','C','JESUS MANUEL ROMAN','19286648',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-25 23:39:00',NULL,NULL,'2019-06-25 23:39:00',NULL,NULL),('003','00960','C','FANY MARTINEZ','99477000',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-25 23:44:51',NULL,NULL,'2019-06-25 23:44:51',NULL,NULL),('003','00961','C','VICTOR MONTERO','44589667',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-25 23:44:52',NULL,NULL,'2019-06-25 23:44:52',NULL,NULL),('003','00962','C','ANDRES LINARES','96687410',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-25 23:44:52',NULL,NULL,'2019-06-25 23:44:52',NULL,NULL),('003','00963','C','DAYANA OROPEZA','33578129',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-06-25 23:44:55',NULL,NULL,'2019-06-25 23:44:55',NULL,NULL),('003','00964','C','CESAR ANTEQUERA','86094123',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-01 10:37:31',NULL,NULL,'2019-07-01 10:37:31',NULL,NULL),('003','00965','C','JUAN LIZARZUBURU','46176133',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-01 10:37:33',NULL,NULL,'2019-07-01 10:37:33',NULL,NULL),('003','00966','C','ELAINE REQUENA','16022611',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-01 10:37:34',NULL,NULL,'2019-07-01 10:37:34',NULL,NULL),('003','00967','C','ROSMARY RAMON','92566000',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-01 10:37:35',NULL,NULL,'2019-07-01 10:37:35',NULL,NULL),('003','00968','C','JOSE ANGEL ZEBALLOS','10729659',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-01 10:37:35',NULL,NULL,'2019-07-01 10:37:35',NULL,NULL),('003','00969','C','CARLOS ANTONIO LUJAN NAVARRO','68462793',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-01 10:37:39',NULL,NULL,'2019-07-01 10:37:39',NULL,NULL),('003','00970','C','DANIELA DUQUE','15478922',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-01 12:08:03',NULL,NULL,'2019-07-01 12:08:03',NULL,NULL),('003','00971','C','HUGO CAMPOS GIRON','94163708',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-01 12:08:03',NULL,NULL,'2019-07-01 12:08:03',NULL,NULL),('003','00972','C','SERGIO CAMACHO','15336674',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-01 12:08:03',NULL,NULL,'2019-07-01 12:08:03',NULL,NULL),('003','00973','C','GIOVANI MOGOLLON','10108287',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-01 12:08:06',NULL,NULL,'2019-07-01 12:08:06',NULL,NULL),('003','00974','C','ANDREA CAMPOS','20382551',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-01 12:08:09',NULL,NULL,'2019-07-01 12:08:09',NULL,NULL),('003','00975','C','PARAMACONI MARAPACUTO','18931767',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-01 12:08:11',NULL,NULL,'2019-07-01 12:08:11',NULL,NULL),('003','00976','C','JOSELIN NUÑEZ','15325744',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-01 12:08:14',NULL,NULL,'2019-07-01 12:08:14',NULL,NULL),('003','00977','C','ERIKA','13257654',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-01 12:24:17',NULL,NULL,'2019-07-01 12:24:17',NULL,NULL),('003','00978','C','DIEGO SANDOVAL','20475896',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-01 12:24:19',NULL,NULL,'2019-07-01 12:24:19',NULL,NULL),('003','00979','C','YENY POCHO','15467885',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-01 15:31:18',NULL,NULL,'2019-07-01 15:31:18',NULL,NULL),('003','00980','C','HUGO CAMPOS GIRON','94163702',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-01 15:31:19',NULL,NULL,'2019-07-01 15:31:19',NULL,NULL),('003','00981','C','EDUARDO DANIEL MINAYA DIAZ','46920671',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-01 15:31:24',NULL,NULL,'2019-07-01 15:31:24',NULL,NULL),('003','00982','C','JAKELINE GARCIA','17654897',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-01 15:50:36',NULL,NULL,'2019-07-01 15:50:36',NULL,NULL),('003','00983','C','KELLY SALAS','19998611',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-01 15:50:38',NULL,NULL,'2019-07-01 15:50:38',NULL,NULL),('003','00984','C','LUZ MARIA JAVIER PEVE','94734335',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-01 15:50:38',NULL,NULL,'2019-07-01 15:50:38',NULL,NULL),('003','00985','C','LUZ MARIA JAVIER PEVE','94734337',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-01 15:50:38',NULL,NULL,'2019-07-01 15:50:38',NULL,NULL),('003','00986','C','GENESIS PEREZ','20410072',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-01 15:50:39',NULL,NULL,'2019-07-01 15:50:39',NULL,NULL),('003','00987','C','LUIS VILLASMIL','20397480',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-01 15:50:41',NULL,NULL,'2019-07-01 15:50:41',NULL,NULL),('003','00988','C','GENESIS BALSA','25225900',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-01 16:17:48',NULL,NULL,'2019-07-01 16:17:48',NULL,NULL),('003','00989','C','ALEJANDRO MALAGUERA','99675509',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-01 16:17:49',NULL,NULL,'2019-07-01 16:17:49',NULL,NULL),('003','00990','C','RAMON PIÑANGO','14390369',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-01 16:17:50',NULL,NULL,'2019-07-01 16:17:50',NULL,NULL),('003','00991','C','MARLON JOSE TERAN','16738090',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-01 16:17:50',NULL,NULL,'2019-07-01 16:17:50',NULL,NULL),('003','00992','C','JOSE LUIS LACRUZ','10989836',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-01 16:17:52',NULL,NULL,'2019-07-01 16:17:52',NULL,NULL),('003','00993','C','LEONEL ZAMBRANO','44313200',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-01 16:17:55',NULL,NULL,'2019-07-01 16:17:55',NULL,NULL),('003','00994','C','GABRIEL GUTIÉRREZ','15477163',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-05 10:23:09',NULL,NULL,'2019-07-05 10:23:09',NULL,NULL),('003','00995','C','JESUS DELGADO','16578424',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-05 10:23:09',NULL,NULL,'2019-07-05 10:23:09',NULL,NULL),('003','00996','C','ANA KARINA BALZA','28135876',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-05 10:23:10',NULL,NULL,'2019-07-05 10:23:10',NULL,NULL),('003','00997','C','DARWIN PEÑA','49018936',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-05 10:23:12',NULL,NULL,'2019-07-05 10:23:12',NULL,NULL),('003','00998','C','ALVARO TROMPETERO','17260655',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-05 10:23:13',NULL,NULL,'2019-07-05 10:23:13',NULL,NULL),('003','00999','C','LEIDY GARCIA','20606148',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-05 10:23:14',NULL,NULL,'2019-07-05 10:23:14',NULL,NULL),('003','01000','C','EMILDE MARTINEZ','13331310',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-05 10:23:15',NULL,NULL,'2019-07-05 10:23:15',NULL,NULL),('003','01001','C','CESAR LUCAR','40302020',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-05 10:23:15',NULL,NULL,'2019-07-05 10:23:15',NULL,NULL),('003','01002','C','ASHLEY BARRIOS','27376738',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-05 10:23:16',NULL,NULL,'2019-07-05 10:23:16',NULL,NULL),('003','01003','C','WILLIANS BERAUN SERNA','48394751',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-05 10:23:16',NULL,NULL,'2019-07-05 10:23:16',NULL,NULL),('003','01004','C','JAKELINE GARCIA','18101162',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-05 10:23:18',NULL,NULL,'2019-07-05 10:23:18',NULL,NULL),('003','01005','C','CARLOS SANCHEZ','23415869',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-05 10:27:02',NULL,NULL,'2019-07-05 10:27:02',NULL,NULL),('003','01006','C','WLADIMIR LANDAETA','25237581',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-05 10:27:03',NULL,NULL,'2019-07-05 10:27:03',NULL,NULL),('003','01007','C','ERWIN AROCHA','14543245',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-05 10:27:04',NULL,NULL,'2019-07-05 10:27:04',NULL,NULL),('003','01008','C','RENZO CASTILLO','16534234',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-05 10:27:07',NULL,NULL,'2019-07-05 10:27:07',NULL,NULL),('003','01009','C','MARIO GUERRA','18745159',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-05 10:27:07',NULL,NULL,'2019-07-05 10:27:07',NULL,NULL),('003','01010','C','SERGIO CAMACHO','19814415',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-05 10:27:09',NULL,NULL,'2019-07-05 10:27:09',NULL,NULL),('003','01011','C','INGRID ALFONZO','17760140',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-05 10:27:11',NULL,NULL,'2019-07-05 10:27:11',NULL,NULL),('003','01012','C','WILLIAM CANDOTTY','20234060',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-05 10:27:12',NULL,NULL,'2019-07-05 10:27:12',NULL,NULL),('003','01013','C','GEOZELYN MILLAN','20537529',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-05 10:27:13',NULL,NULL,'2019-07-05 10:27:13',NULL,NULL),('003','01014','C','GENESIS TORRES','23778167',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-05 10:27:15',NULL,NULL,'2019-07-05 10:27:15',NULL,NULL),('003','01015','C','YUDITH MEJIAS','19069181',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-05 10:27:16',NULL,NULL,'2019-07-05 10:27:16',NULL,NULL),('003','01016','C','EDGARDO RODRIGUEZ','25401769',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-05 10:27:17',NULL,NULL,'2019-07-05 10:27:17',NULL,NULL),('003','01017','C','SERGIO CAMACHO','19814418',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-05 10:27:18',NULL,NULL,'2019-07-05 10:27:18',NULL,NULL),('003','01018','C','MARBELYS MARTINEZ','24155176',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-05 10:27:19',NULL,NULL,'2019-07-05 10:27:19',NULL,NULL),('003','01019','C','LUZ MARIA JAVIER PEVE','94734338',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-05 10:27:20',NULL,NULL,'2019-07-05 10:27:20',NULL,NULL),('003','01020','C','HUGO CAMPOS GIRON','94163706',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-05 10:27:20',NULL,NULL,'2019-07-05 10:27:20',NULL,NULL),('003','01021','C','SOLANG BARRETO','20470840',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-05 10:32:46',NULL,NULL,'2019-07-05 10:32:46',NULL,NULL),('003','01022','C','MARYORI CAMACHO','12148100',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-05 10:32:47',NULL,NULL,'2019-07-05 10:32:47',NULL,NULL),('003','01023','C','JUAN CARLOS SALAZAR','22381505',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-05 10:32:48',NULL,NULL,'2019-07-05 10:32:48',NULL,NULL),('003','01024','C','AIDASBEL VALLEJOS','15334531',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-05 10:32:49',NULL,NULL,'2019-07-05 10:32:49',NULL,NULL),('003','01025','C','JESSICA SOTO','24586319',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-05 10:32:50',NULL,NULL,'2019-07-05 10:32:50',NULL,NULL),('003','01026','C','MARIA VILLASMIL','75864312',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-05 10:32:54',NULL,NULL,'2019-07-05 10:32:54',NULL,NULL),('003','01027','C','FREIMAR FRANYELI ESCOBAR RODRIGUEZ','69140675',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-05 10:32:54',NULL,NULL,'2019-07-05 10:32:54',NULL,NULL),('003','01028','C','CARLOS COLO','17343221',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-05 10:32:55',NULL,NULL,'2019-07-05 10:32:55',NULL,NULL),('003','01029','C','CARLOS ANTONIO LUJAN NAVARRO','68462792',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-05 10:32:55',NULL,NULL,'2019-07-05 10:32:55',NULL,NULL),('003','01030','C','INDIRA RODRIGUEZ','12385418',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-11 10:08:13',NULL,NULL,'2019-07-11 10:08:13',NULL,NULL),('003','01031','C','CLAUDIO MEDINA','15617730',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-11 10:08:13',NULL,NULL,'2019-07-11 10:08:13',NULL,NULL),('003','01032','C','ROSA GUTIERREZ','22010638',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-11 10:08:14',NULL,NULL,'2019-07-11 10:08:14',NULL,NULL),('003','01033','C','MONICA LORBES','23687766',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-11 10:08:16',NULL,NULL,'2019-07-11 10:08:16',NULL,NULL),('003','01034','C','CIRILO CARDENAS','96441305',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-11 10:08:16',NULL,NULL,'2019-07-11 10:08:16',NULL,NULL),('003','01035','C','DARWUIN SACHEZ','17013480',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-11 10:08:16',NULL,NULL,'2019-07-11 10:08:16',NULL,NULL),('003','01036','C','ROSA RAMIREZ','21056116',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-11 10:08:16',NULL,NULL,'2019-07-11 10:08:16',NULL,NULL),('003','01037','C','ANA RODRIGUES','28669860',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-11 10:08:16',NULL,NULL,'2019-07-11 10:08:16',NULL,NULL),('003','01038','C','JENERY MORON','22009299',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-11 10:08:18',NULL,NULL,'2019-07-11 10:08:18',NULL,NULL),('003','01039','C','JOSE GREGORIO CRESPO RONDON','16164135',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-11 10:08:22',NULL,NULL,'2019-07-11 10:08:22',NULL,NULL),('003','01040','C','WILLIAM JOSE LARA','14232172',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-11 10:08:22',NULL,NULL,'2019-07-11 10:08:22',NULL,NULL),('003','01041','C','YOLIMAR MATOS','75413822',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-11 10:21:04',NULL,NULL,'2019-07-11 10:21:04',NULL,NULL),('003','01042','C','MARIA MALDONADO PEREZ','96765625',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-11 10:21:05',NULL,NULL,'2019-07-11 10:21:05',NULL,NULL),('003','01043','C','SERGIO CAMACHO','19814412',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-11 10:21:07',NULL,NULL,'2019-07-11 10:21:07',NULL,NULL),('003','01044','C','ALIX ARAQUE','11383965',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-11 10:21:08',NULL,NULL,'2019-07-11 10:21:08',NULL,NULL),('003','01045','C','JOSE DAVID MENDOZA','25352972',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-11 10:21:11',NULL,NULL,'2019-07-11 10:21:11',NULL,NULL),('003','01046','C','CAMILO LISTA','15813727',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-11 15:08:59',NULL,NULL,'2019-07-11 15:08:59',NULL,NULL),('003','01047','C','HEUMIR ANIBAL URBAY','16379421',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-11 15:08:59',NULL,NULL,'2019-07-11 15:08:59',NULL,NULL),('003','01048','C','IRIS HIDALGO','24587613',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-11 15:09:05',NULL,NULL,'2019-07-11 15:09:05',NULL,NULL),('003','01049','C','YENY POCHO','15489962',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-11 15:09:08',NULL,NULL,'2019-07-11 15:09:08',NULL,NULL),('003','01050','C','KELLY BORREGALES','24832241',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-11 15:46:34',NULL,NULL,'2019-07-11 15:46:34',NULL,NULL),('003','01051','C','FRANKLIN MOLLEDA','11412353',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-11 15:46:34',NULL,NULL,'2019-07-11 15:46:34',NULL,NULL),('003','01052','C','JOSETH RODRIGUEZ','13713616',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-11 15:46:35',NULL,NULL,'2019-07-11 15:46:35',NULL,NULL),('003','01053','C','MARIANA FIGUEROA','17729764',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-11 15:46:35',NULL,NULL,'2019-07-11 15:46:35',NULL,NULL),('003','01054','C','JIMMY NUÑEZ','91635866',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-11 15:46:37',NULL,NULL,'2019-07-11 15:46:37',NULL,NULL),('003','01055','C','DAYANA LACRUZ','24330536',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-11 15:46:39',NULL,NULL,'2019-07-11 15:46:39',NULL,NULL),('003','01056','C','RAUL LUZARDO','16353895',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-11 15:46:40',NULL,NULL,'2019-07-11 15:46:40',NULL,NULL),('003','01057','C','MARIANNY FIGUERA','25062195',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-11 15:46:44',NULL,NULL,'2019-07-11 15:46:44',NULL,NULL),('003','01058','C','MADELINE SANCHEZ','15739333',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-11 15:46:44',NULL,NULL,'2019-07-11 15:46:44',NULL,NULL),('003','01059','C','AUGUSTO FERNANDEZ','15468293',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-11 15:46:44',NULL,NULL,'2019-07-11 15:46:44',NULL,NULL),('003','01060','C','JAISI PEREZ','16438897',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-11 15:46:47',NULL,NULL,'2019-07-11 15:46:47',NULL,NULL),('003','01061','C','LEYDI ZAMBRANO','13524861',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-12 10:55:12',NULL,NULL,'2019-07-12 10:55:12',NULL,NULL),('003','01062','C','CARLOS COLO','14565467',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-12 10:55:14',NULL,NULL,'2019-07-12 10:55:14',NULL,NULL),('003','01063','C','DANIEL MELENDEZ','14040388',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-12 10:55:14',NULL,NULL,'2019-07-12 10:55:14',NULL,NULL),('003','01064','C','WENDYS RUBIO','23595364',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-12 10:55:15',NULL,NULL,'2019-07-12 10:55:15',NULL,NULL),('003','01065','C','SHEYLA GILARTE','16871179',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-12 13:20:36',NULL,NULL,'2019-07-12 13:20:36',NULL,NULL),('003','01066','C','MARIA HENRIQUEZ','24493641',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-12 13:20:36',NULL,NULL,'2019-07-12 13:20:36',NULL,NULL),('003','01067','C','ANGEL URRACA','23745461',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-12 13:20:36',NULL,NULL,'2019-07-12 13:20:36',NULL,NULL),('003','01068','C','ROBERTO ROJAS','13704828',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-12 13:20:39',NULL,NULL,'2019-07-12 13:20:39',NULL,NULL),('003','01069','C','HUGO CAMPOS GIRON','94163707',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-12 13:20:42',NULL,NULL,'2019-07-12 13:20:42',NULL,NULL),('003','01070','C','RAUL MONTERO','40067650',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-12 13:20:42',NULL,NULL,'2019-07-12 13:20:42',NULL,NULL),('003','01071','C','AIDASBEL VALLEJOS','27333422',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-15 15:52:54',NULL,NULL,'2019-07-15 15:52:54',NULL,NULL),('003','01072','C','ANGELIKA CEDEÑO','17431709',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-15 15:52:54',NULL,NULL,'2019-07-15 15:52:54',NULL,NULL),('003','01073','C','YENIFER GUAICARA','18766508',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-15 15:52:56',NULL,NULL,'2019-07-15 15:52:56',NULL,NULL),('003','01074','C','MARIA LOPEZ','24815637',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-15 15:52:57',NULL,NULL,'2019-07-15 15:52:57',NULL,NULL),('003','01075','C','ORLANDO LORBES','28020354',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-15 15:52:57',NULL,NULL,'2019-07-15 15:52:57',NULL,NULL),('003','01076','C','MIGDELYS BORDONES','17283183',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-15 15:52:58',NULL,NULL,'2019-07-15 15:52:58',NULL,NULL),('003','01077','C','CARLOS BALZA','19292112',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-15 15:52:59',NULL,NULL,'2019-07-15 15:52:59',NULL,NULL),('003','01078','C','CHARLES RANGEL','80386217',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-15 15:53:00',NULL,NULL,'2019-07-15 15:53:00',NULL,NULL),('003','01079','C','GIOVANNI AGUILERA','89632286',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-15 15:53:01',NULL,NULL,'2019-07-15 15:53:01',NULL,NULL),('003','01080','C','ELIANY TELLO','45561548',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-15 15:59:57',NULL,NULL,'2019-07-15 15:59:57',NULL,NULL),('003','01081','C','DANIEL CHACÓN','22665394',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-15 15:59:57',NULL,NULL,'2019-07-15 15:59:57',NULL,NULL),('003','01082','C','YENY POCHO','78956412',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-15 15:59:58',NULL,NULL,'2019-07-15 15:59:58',NULL,NULL),('003','01083','C','JORGE ROCA','12587785',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-15 15:59:58',NULL,NULL,'2019-07-15 15:59:58',NULL,NULL),('003','01084','C','GIANCARLO AGUILAR ABURTO','46989765',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-15 16:00:02',NULL,NULL,'2019-07-15 16:00:02',NULL,NULL),('003','01085','C','ADELYS SANCHEZ','16978356',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-15 16:00:02',NULL,NULL,'2019-07-15 16:00:02',NULL,NULL),('003','01086','C','JOSE ALTUVE','24191591',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-15 16:00:04',NULL,NULL,'2019-07-15 16:00:04',NULL,NULL),('003','01087','C','RAUL MORALES','45599770',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-15 16:00:05',NULL,NULL,'2019-07-15 16:00:05',NULL,NULL),('003','01088','C','ILIANA RIOS','45782163',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-15 16:00:07',NULL,NULL,'2019-07-15 16:00:07',NULL,NULL),('003','01089','C','MANUEL RODRIGUEZ','40241718',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-15 16:00:07',NULL,NULL,'2019-07-15 16:00:07',NULL,NULL),('003','01090','C','OSLANDO FIGUEROA','20147689',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-15 16:06:57',NULL,NULL,'2019-07-15 16:06:57',NULL,NULL),('003','01091','C','NERIBIS MORENO','15478952',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-15 16:06:59',NULL,NULL,'2019-07-15 16:06:59',NULL,NULL),('003','01092','C','JHON ROMERO','66589223',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-15 16:07:00',NULL,NULL,'2019-07-15 16:07:00',NULL,NULL),('003','01093','C','ELVIS MENDEZ','14596325',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-15 16:07:02',NULL,NULL,'2019-07-15 16:07:02',NULL,NULL),('003','01094','C','JOSE ALVARADO','41257863',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-15 16:07:05',NULL,NULL,'2019-07-15 16:07:05',NULL,NULL),('003','01095','C','LUIS RODRIGUEZ','21060068',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-19 16:54:46',NULL,NULL,'2019-07-19 16:54:46',NULL,NULL),('003','01096','C','ALBENIS ESPINA','18605180',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-19 16:54:52',NULL,NULL,'2019-07-19 16:54:52',NULL,NULL),('003','01097','C','LILIA GARCIA','84514320',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-19 16:54:53',NULL,NULL,'2019-07-19 16:54:53',NULL,NULL),('003','01098','C','GIOVANNI AGUILERA','89632280',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-19 16:54:54',NULL,NULL,'2019-07-19 16:54:54',NULL,NULL),('003','01099','C','ALEXANDER ACOSTO','96558290',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-19 16:54:55',NULL,NULL,'2019-07-19 16:54:55',NULL,NULL),('003','01100','C','EMMA GUERRERO','99866470',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-19 16:54:56',NULL,NULL,'2019-07-19 16:54:56',NULL,NULL),('003','01101','C','ERWIN AROCHA','22727622',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-19 16:54:57',NULL,NULL,'2019-07-19 16:54:57',NULL,NULL),('003','01102','C','RICARDO SAUCEDO','25827495',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-19 16:55:00',NULL,NULL,'2019-07-19 16:55:00',NULL,NULL),('003','01103','C','HUGO CAMPOS GIRON','94163705',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-19 16:55:02',NULL,NULL,'2019-07-19 16:55:02',NULL,NULL),('003','01104','C','JAISI PEREZ','26691662',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-19 16:55:03',NULL,NULL,'2019-07-19 16:55:03',NULL,NULL),('003','01105','C','NAYLLEN ROJAS','16374258',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-23 19:51:23',NULL,NULL,'2019-07-23 19:51:23',NULL,NULL),('003','01106','C','LESLIE VALLEJOS','23458976',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-23 19:51:24',NULL,NULL,'2019-07-23 19:51:24',NULL,NULL),('003','01107','C','JOSE DOMINGO','22479653',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-23 19:51:26',NULL,NULL,'2019-07-23 19:51:26',NULL,NULL),('003','01108','C','MANUEL GHIORZO','66879127',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-23 19:51:29',NULL,NULL,'2019-07-23 19:51:29',NULL,NULL),('003','01109','C','JOSE VERAMENDI','44789632',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-23 19:51:29',NULL,NULL,'2019-07-23 19:51:29',NULL,NULL),('003','01110','C','JOSE GONZALEZ','26346427',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-23 19:51:31',NULL,NULL,'2019-07-23 19:51:31',NULL,NULL),('003','01111','C','JESUS SAAVEDRA','26763209',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-23 19:51:32',NULL,NULL,'2019-07-23 19:51:32',NULL,NULL),('003','01112','C','JOSE ROSAL','10010854',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-23 19:51:35',NULL,NULL,'2019-07-23 19:51:35',NULL,NULL),('003','01113','C','SERGIO GONZALEZ','20924588',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-23 19:51:37',NULL,NULL,'2019-07-23 19:51:37',NULL,NULL),('003','01114','C','HUGO CAMPOS GIRON','94163701',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-23 19:51:38',NULL,NULL,'2019-07-23 19:51:38',NULL,NULL),('003','01115','C','CARLOS ANTONIO LUJAN NAVARRO','68462795',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-23 19:51:39',NULL,NULL,'2019-07-23 19:51:39',NULL,NULL),('003','01116','C','HUGO CAMPOS GIRON','94163709',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-23 19:51:39',NULL,NULL,'2019-07-23 19:51:39',NULL,NULL),('003','01117','C','DARWIN MONCADA','15648792',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-23 19:51:39',NULL,NULL,'2019-07-23 19:51:39',NULL,NULL),('003','01118','C','CARLOS JAVIER GIMENEZ LINARES','23489001',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-24 09:46:39',NULL,NULL,'2019-07-24 09:46:39',NULL,NULL),('003','01119','C','GUSTAVO CASTILLO','11068086',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-24 09:46:45',NULL,NULL,'2019-07-24 09:46:45',NULL,NULL),('003','01120','C','YORIANY BASTIDAS','20976797',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-24 09:46:45',NULL,NULL,'2019-07-24 09:46:45',NULL,NULL),('003','01121','C','LEIDY JIMENEZ','24190520',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-24 09:46:46',NULL,NULL,'2019-07-24 09:46:46',NULL,NULL),('003','01122','C','MARLYNG LOPEZ','15474549',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-24 09:46:46',NULL,NULL,'2019-07-24 09:46:46',NULL,NULL),('003','01123','C','CARLOS PUGA','26269112',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-24 09:46:47',NULL,NULL,'2019-07-24 09:46:47',NULL,NULL),('003','01124','C','RONALD MECHATO LAMADRID','47556892',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-24 11:56:20',NULL,NULL,'2019-07-24 11:56:20',NULL,NULL),('003','01125','C','VIVIAM PINEDA','16487593',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-24 11:56:20',NULL,NULL,'2019-07-24 11:56:20',NULL,NULL),('003','01126','C','NERIBIS MORENO','16543998',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-24 11:56:20',NULL,NULL,'2019-07-24 11:56:20',NULL,NULL),('003','01127','C','JONATHAN ZARRAGA','25631746',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-24 11:56:22',NULL,NULL,'2019-07-24 11:56:22',NULL,NULL),('003','01128','C','JOSE ALBERTO BUSTAMANTE ROSALES','63099105',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-24 11:56:25',NULL,NULL,'2019-07-24 11:56:25',NULL,NULL),('003','01129','C','GENESIS PEÑA','22600647',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-24 11:56:25',NULL,NULL,'2019-07-24 11:56:25',NULL,NULL),('003','01130','C','JHONATAN MARIN','20410762',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-24 11:56:26',NULL,NULL,'2019-07-24 11:56:26',NULL,NULL),('003','01131','C','JAVIER MARIÑAS VITE','43530548',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-24 11:56:31',NULL,NULL,'2019-07-24 11:56:31',NULL,NULL),('003','01132','C','ALEXA MARTINEZ','26461456',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-24 12:05:58',NULL,NULL,'2019-07-24 12:05:58',NULL,NULL),('003','01133','C','FELIX CUEVAS','17784932',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-24 12:05:59',NULL,NULL,'2019-07-24 12:05:59',NULL,NULL),('003','01134','C','JOSE ALBERTO BUSTAMANTE ROSALES','63099100',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-24 12:06:01',NULL,NULL,'2019-07-24 12:06:01',NULL,NULL),('003','01135','C','JAIRO MARTINEZ','24303727',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-24 12:06:03',NULL,NULL,'2019-07-24 12:06:03',NULL,NULL),('003','01136','C','SANTIAGO LOPEZ','15966450',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-24 12:06:04',NULL,NULL,'2019-07-24 12:06:04',NULL,NULL),('003','01137','C','GABRIEL SEQUERA','24717900',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-24 12:06:06',NULL,NULL,'2019-07-24 12:06:06',NULL,NULL),('003','01138','C','DARWIN MONCADA','12548976',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-24 12:06:08',NULL,NULL,'2019-07-24 12:06:08',NULL,NULL),('003','01139','C','EDIXON WILLANDER LEAL AGUERO','26359481',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-24 12:27:07',NULL,NULL,'2019-07-24 12:27:07',NULL,NULL),('003','01140','C','ROSMELY CHACIN','25812341',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-24 12:27:08',NULL,NULL,'2019-07-24 12:27:08',NULL,NULL),('003','01141','C','MARITZA MONTAÑA','14923213',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-24 12:27:08',NULL,NULL,'2019-07-24 12:27:08',NULL,NULL),('003','01142','C','JHOISY PACEDO','22447798',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-24 12:27:09',NULL,NULL,'2019-07-24 12:27:09',NULL,NULL),('003','01143','C','NERIBIS MORENO','12547965',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-24 12:27:11',NULL,NULL,'2019-07-24 12:27:11',NULL,NULL),('003','01144','C','YULIANA SOSA','18163767',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-24 12:27:12',NULL,NULL,'2019-07-24 12:27:12',NULL,NULL),('003','01145','C','KATHERINE GONZALEZ','67694928',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-24 12:27:14',NULL,NULL,'2019-07-24 12:27:14',NULL,NULL),('003','01146','C','JORGE ROCA','14578622',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-24 12:27:14',NULL,NULL,'2019-07-24 12:27:14',NULL,NULL),('003','01147','C','NAIYULY CANDOTTI','19799913',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-24 13:19:29',NULL,NULL,'2019-07-24 13:19:29',NULL,NULL),('003','01148','C','DEISSMER PORTILLO','25754393',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-24 13:19:30',NULL,NULL,'2019-07-24 13:19:30',NULL,NULL),('003','01149','C','GLORIA GALINDEZ','14938393',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-24 13:19:30',NULL,NULL,'2019-07-24 13:19:30',NULL,NULL),('003','01150','C','JACKSON ESTEVEZ','17109077',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-24 13:19:34',NULL,NULL,'2019-07-24 13:19:34',NULL,NULL),('003','01151','C','GILVER VITRIAGO','21437174',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-24 13:19:35',NULL,NULL,'2019-07-24 13:19:35',NULL,NULL),('003','01152','C','JEANNELIS CALDERA','28442118',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-24 13:19:36',NULL,NULL,'2019-07-24 13:19:36',NULL,NULL),('003','01153','C','KARELIS CRESPO','20015303',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-24 13:19:38',NULL,NULL,'2019-07-24 13:19:38',NULL,NULL),('003','01154','C','KLEYLIS PEREZ','24671215',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-30 22:59:41',NULL,NULL,'2019-07-30 22:59:41',NULL,NULL),('003','01155','C','WILLIAM DEL VALLE','74507028',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-30 22:59:42',NULL,NULL,'2019-07-30 22:59:42',NULL,NULL),('003','01156','C','GERALDINE ZANABRIA','19266518',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-30 22:59:42',NULL,NULL,'2019-07-30 22:59:42',NULL,NULL),('003','01157','C','ANGELY ALVAREZ','25611483',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-30 22:59:43',NULL,NULL,'2019-07-30 22:59:43',NULL,NULL),('003','01158','C','LEIDYS SILVA','14826735',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-30 23:04:11',NULL,NULL,'2019-07-30 23:04:11',NULL,NULL),('003','01159','C','JHON PAUL SACO SAAVEDRA','21047856',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-30 23:04:18',NULL,NULL,'2019-07-30 23:04:18',NULL,NULL),('003','01160','C','KATHERINE GONZALEZ','67694925',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-30 23:04:18',NULL,NULL,'2019-07-30 23:04:18',NULL,NULL),('003','01161','C','EDIXON GIL','17458892',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-30 23:04:18',NULL,NULL,'2019-07-30 23:04:18',NULL,NULL),('003','01162','C','JOHANA MACHACON','14754980',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-30 23:04:19',NULL,NULL,'2019-07-30 23:04:19',NULL,NULL),('003','01163','C','OLIVER VIAMONTE','14928767',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-30 23:10:54',NULL,NULL,'2019-07-30 23:10:54',NULL,NULL),('003','01164','C','YENNI RODRIGUEZ','13295277',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-30 23:10:56',NULL,NULL,'2019-07-30 23:10:56',NULL,NULL),('003','01165','C','ROBERTH ROJAS','12038233',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-30 23:10:56',NULL,NULL,'2019-07-30 23:10:56',NULL,NULL),('003','01166','C','JOSE GREGORIO VIELMA','17528618',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-30 23:11:00',NULL,NULL,'2019-07-30 23:11:00',NULL,NULL),('003','01167','C','LUALDI LOPEZ','22662192',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-30 23:26:30',NULL,NULL,'2019-07-30 23:26:30',NULL,NULL),('003','01168','C','WUILDER RODRIGUEZ','14895762',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-30 23:26:30',NULL,NULL,'2019-07-30 23:26:30',NULL,NULL),('003','01169','C','VERONICA PEREZ','26697860',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-30 23:26:30',NULL,NULL,'2019-07-30 23:26:30',NULL,NULL),('003','01170','C','ROSA DELLEPIANE','95309265',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-30 23:26:35',NULL,NULL,'2019-07-30 23:26:35',NULL,NULL),('003','01171','C','JOSE GREGORIO VIELMA','17528615',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-30 23:26:36',NULL,NULL,'2019-07-30 23:26:36',NULL,NULL),('003','01172','C','GLORIA JARA PEREZ','15762880',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-30 23:26:37',NULL,NULL,'2019-07-30 23:26:37',NULL,NULL),('003','01173','C','YALEXIS GUZMAN','26072640',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-30 23:26:38',NULL,NULL,'2019-07-30 23:26:38',NULL,NULL),('003','01174','C','JOSE GUAIPO','77485496',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-30 23:30:14',NULL,NULL,'2019-07-30 23:30:14',NULL,NULL),('003','01175','C','PIERANYELA PERAZA TIMAURE','20670970',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-30 23:30:15',NULL,NULL,'2019-07-30 23:30:15',NULL,NULL),('003','01176','C','LUIS HERRERA','33425168',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-30 23:30:17',NULL,NULL,'2019-07-30 23:30:17',NULL,NULL),('003','01177','C','OMAR CASTILLO','21454001',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-07-30 23:30:17',NULL,NULL,'2019-07-30 23:30:17',NULL,NULL),('003','01178','C','LUIS CORRALES','15921488',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-08-02 22:42:37',NULL,NULL,'2019-08-02 22:42:37',NULL,NULL),('003','01179','C','YOLFRED CEDEÑO','24642914',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-08-02 22:42:38',NULL,NULL,'2019-08-02 22:42:38',NULL,NULL),('003','01180','C','MIGUEL VEGA','73887406',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-08-02 22:42:39',NULL,NULL,'2019-08-02 22:42:39',NULL,NULL),('003','01181','C','CARLOS TORO','16542622',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-08-02 22:42:40',NULL,NULL,'2019-08-02 22:42:40',NULL,NULL),('003','01182','C','DARWIN MONCADA','15478965',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-08-02 22:42:54',NULL,NULL,'2019-08-02 22:42:54',NULL,NULL),('003','01183','C','YORBI PELLICER','17458471',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-08-02 22:42:54',NULL,NULL,'2019-08-02 22:42:54',NULL,NULL),('003','01184','C','RAFAEL MARTINEZ','59565428',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-08-02 22:42:55',NULL,NULL,'2019-08-02 22:42:55',NULL,NULL),('003','01185','C','JULIO MOSTACERO','22206610',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-08-02 22:47:05',NULL,NULL,'2019-08-02 22:47:05',NULL,NULL),('003','01186','C','ANABEL LUNA','20803828',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-08-02 22:47:05',NULL,NULL,'2019-08-02 22:47:05',NULL,NULL),('003','01187','C','RONALD GARCIA','14855267',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-08-02 22:47:09',NULL,NULL,'2019-08-02 22:47:09',NULL,NULL),('003','01188','C','MARIANNA MENDOZA','28025298',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-08-02 22:47:12',NULL,NULL,'2019-08-02 22:47:12',NULL,NULL),('003','01189','C','OLGA COLMENARES','26049679',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-08-02 22:47:14',NULL,NULL,'2019-08-02 22:47:14',NULL,NULL),('003','01190','C','ELIANY TELLO','45568563',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-08-02 22:47:16',NULL,NULL,'2019-08-02 22:47:16',NULL,NULL),('003','01191','C','MILEXIX AGUILERA PEREZ','25397886',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-08-02 22:47:17',NULL,NULL,'2019-08-02 22:47:17',NULL,NULL),('003','01192','C','LUISA ACHAHUI','44734252',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-08-08 15:34:19',NULL,NULL,'2019-08-08 15:34:19',NULL,NULL),('003','01193','C','ANGELBERTH CARRILLO','28469109',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-08-08 15:34:21',NULL,NULL,'2019-08-08 15:34:21',NULL,NULL),('003','01194','C','YORSMERY RIVAS','24899858',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-08-08 15:34:21',NULL,NULL,'2019-08-08 15:34:21',NULL,NULL),('003','01195','C','SEBASTIAN SAEZ','12174653',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-08-08 15:34:28',NULL,NULL,'2019-08-08 15:34:28',NULL,NULL),('003','01196','C','ERICK PADILLA','18965472',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-08-08 15:34:29',NULL,NULL,'2019-08-08 15:34:29',NULL,NULL),('003','01197','C','LUISANA FUCIL','18795462',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-08-08 19:03:40',NULL,NULL,'2019-08-08 19:03:40',NULL,NULL),('003','01198','C','JESSICA PEREZ','18436234',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-08-08 19:03:40',NULL,NULL,'2019-08-08 19:03:40',NULL,NULL),('003','01199','C','MARIANA FEREIRA','20244959',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-08-08 19:03:44',NULL,NULL,'2019-08-08 19:03:44',NULL,NULL),('003','01200','C','HELEN BARRIOS','19242939',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-08-08 19:03:46',NULL,NULL,'2019-08-08 19:03:46',NULL,NULL),('003','01201','C','CESAR VALERO','14606147',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-08-08 19:03:46',NULL,NULL,'2019-08-08 19:03:46',NULL,NULL),('003','01202','C','NOEL OSUNA','20201795',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-08-08 19:14:51',NULL,NULL,'2019-08-08 19:14:51',NULL,NULL),('003','01203','C','KERVIS SANCHEZ','24785961',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-08-08 19:14:54',NULL,NULL,'2019-08-08 19:14:54',NULL,NULL),('003','01204','C','CARLOS ANTONIO LUJAN NAVARRO','68462791',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-08-08 19:14:56',NULL,NULL,'2019-08-08 19:14:56',NULL,NULL),('003','01205','C','JOSE MADRID','41578896',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-08-08 19:14:58',NULL,NULL,'2019-08-08 19:14:58',NULL,NULL),('003','01206','C','SUSANA CAMPOS','14758692',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-08-08 19:24:08',NULL,NULL,'2019-08-08 19:24:08',NULL,NULL),('003','01207','C','JORGE ROCA','14896551',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-08-08 19:24:11',NULL,NULL,'2019-08-08 19:24:11',NULL,NULL),('003','01208','C','JENNY CHAVEZ','19888355',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-08-08 19:24:12',NULL,NULL,'2019-08-08 19:24:12',NULL,NULL),('003','01209','C','CRHISTIAN RAMOS','20870362',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-08-08 19:24:13',NULL,NULL,'2019-08-08 19:24:13',NULL,NULL),('003','01210','C','RENATTA RUESTA GUERRERO','48051791',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-08-08 19:24:16',NULL,NULL,'2019-08-08 19:24:16',NULL,NULL),('003','01211','C','ERICK PADILLA','18457996',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-08-08 19:24:17',NULL,NULL,'2019-08-08 19:24:17',NULL,NULL),('003','01212','C','KATHERINE GONZALEZ','16769492',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-08-08 19:24:17',NULL,NULL,'2019-08-08 19:24:17',NULL,NULL),('003','01213','C','GERMAN WILMER DEL CASTILLO MORILLO','41342782',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-08-08 19:30:49',NULL,NULL,'2019-08-08 19:30:49',NULL,NULL),('003','01214','C','MARIO ALBERTO DORIVAL','49013082',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-08-08 19:30:54',NULL,NULL,'2019-08-08 19:30:54',NULL,NULL),('003','01215','C','MARIABE CAITO','16161414',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-08-13 11:43:28',NULL,NULL,'2019-08-13 11:43:28',NULL,NULL),('003','01216','C','JAVIER CORRALES','44785133',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-08-13 11:43:32',NULL,NULL,'2019-08-13 11:43:32',NULL,NULL),('003','01217','C','YECENIA VARGAS','20894564',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-08-13 11:48:30',NULL,NULL,'2019-08-13 11:48:30',NULL,NULL),('003','01218','C','CARLOS RAMIREZ','18457963',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-08-13 11:48:32',NULL,NULL,'2019-08-13 11:48:32',NULL,NULL),('003','01219','C','NAYLLEN ROJAS','16374200',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-08-13 11:48:33',NULL,NULL,'2019-08-13 11:48:33',NULL,NULL),('003','01220','C','CARLOS COLO','16876543',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-08-13 11:48:37',NULL,NULL,'2019-08-13 11:48:37',NULL,NULL),('003','01221','C','DORALIS MERCHAN','12209287',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-08-13 11:56:46',NULL,NULL,'2019-08-13 11:56:46',NULL,NULL),('003','01222','C','ALEJANDRO JOSE ROSAS ROJAS','17456889',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-08-13 11:56:47',NULL,NULL,'2019-08-13 11:56:47',NULL,NULL),('003','01223','C','CARLOS ANTONIO LUJAN NAVARRO','68462798',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-08-13 11:56:50',NULL,NULL,'2019-08-13 11:56:50',NULL,NULL),('003','01224','C','WILLIAM GUARAN','10981097',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-08-13 11:56:51',NULL,NULL,'2019-08-13 11:56:51',NULL,NULL),('003','01225','C','ALEX PALOMINO','19777930',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-08-13 12:02:26',NULL,NULL,'2019-08-13 12:02:26',NULL,NULL),('003','01226','C','GERALDINE GARCIA','27455664',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-08-13 12:02:29',NULL,NULL,'2019-08-13 12:02:29',NULL,NULL),('003','01227','C','YOSHET BRAVO','26527547',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-08-13 12:02:30',NULL,NULL,'2019-08-13 12:02:30',NULL,NULL),('003','01228','C','MANUEL SAJI','41782396',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-08-13 12:02:31',NULL,NULL,'2019-08-13 12:02:31',NULL,NULL),('003','01229','C','CESIBEL SANDOVAL PINCAY','25037485',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-08-13 12:02:33',NULL,NULL,'2019-08-13 12:02:33',NULL,NULL),('003','01230','C','ORIAILYS PIÑA','14237586',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-08-13 12:02:35',NULL,NULL,'2019-08-13 12:02:35',NULL,NULL),('003','01231','C','RONALD FREITES ORTEGA','18461357',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-08-13 12:02:36',NULL,NULL,'2019-08-13 12:02:36',NULL,NULL),('003','01232','C','RAFAEL LOPEZ','20786624',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-08-16 10:49:24',NULL,NULL,'2019-08-16 10:49:24',NULL,NULL),('003','01233','C','NERIBIS MORENO','14896572',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-08-16 10:49:26',NULL,NULL,'2019-08-16 10:49:26',NULL,NULL),('003','01234','C','ELIZABETH BECERRA','16241137',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-08-16 10:49:28',NULL,NULL,'2019-08-16 10:49:28',NULL,NULL),('003','01235','C','ZARETH MATA','14728569',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-08-16 10:49:30',NULL,NULL,'2019-08-16 10:49:30',NULL,NULL),('003','01236','C','MONICA PAREJO','25696263',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-08-16 10:49:32',NULL,NULL,'2019-08-16 10:49:32',NULL,NULL),('003','01237','C','FRANYERLIT MARQUEZ','22447589',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-08-16 10:49:33',NULL,NULL,'2019-08-16 10:49:33',NULL,NULL),('003','01238','C','ZABDIEL VILORIA','26242293',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-08-16 10:49:38',NULL,NULL,'2019-08-16 10:49:38',NULL,NULL),('003','01239','C','KATHERINE GONZALEZ','67694921',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-08-16 10:49:39',NULL,NULL,'2019-08-16 10:49:39',NULL,NULL),('003','01240','C','YUSMAR BLANCO','11053774',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-08-16 12:01:44',NULL,NULL,'2019-08-16 12:01:44',NULL,NULL),('003','01241','C','HUGO RIVAS','81787720',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-08-16 12:01:47',NULL,NULL,'2019-08-16 12:01:47',NULL,NULL),('003','01242','C','JENNY CHAVEZ','98883555',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-08-16 12:06:22',NULL,NULL,'2019-08-16 12:06:22',NULL,NULL),('003','01243','C','JOSE PARRA','15424671',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-08-16 12:06:22',NULL,NULL,'2019-08-16 12:06:22',NULL,NULL),('003','01244','C','MARIA ANDRADE','16487992',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-08-16 12:06:23',NULL,NULL,'2019-08-16 12:06:23',NULL,NULL),('003','01245','C','ROSA VAZQUEZ','24221124',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-08-16 12:06:24',NULL,NULL,'2019-08-16 12:06:24',NULL,NULL),('003','01246','C','JOSE LUCENA FUENTE','19108219',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-08-16 12:06:26',NULL,NULL,'2019-08-16 12:06:26',NULL,NULL),('003','01247','C','ANAIS ADAME','12554795',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-08-16 12:06:27',NULL,NULL,'2019-08-16 12:06:27',NULL,NULL),('003','01248','C','JUAN ZAMBRANO ROMERO','10927027',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-08-16 12:06:29',NULL,NULL,'2019-08-16 12:06:29',NULL,NULL),('003','01249','C','BARBARA DIAZ HERNANDEZ','23635767',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-08-16 12:06:30',NULL,NULL,'2019-08-16 12:06:30',NULL,NULL),('003','01250','C','SOLANYE ANTUNEZ','11789610',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-08-20 09:52:06',NULL,NULL,'2019-08-20 09:52:06',NULL,NULL),('003','01251','C','JESSY RODRIGUEZ','20825384',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-08-20 09:52:10',NULL,NULL,'2019-08-20 09:52:10',NULL,NULL),('003','01252','C','CRUZ GONZALEZ','36273551',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-08-20 09:52:11',NULL,NULL,'2019-08-20 09:52:11',NULL,NULL),('003','01253','C','ROLANDO PAREDES','17539763',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-08-20 09:52:12',NULL,NULL,'2019-08-20 09:52:12',NULL,NULL),('003','01254','C','VICENTE MATOS MATOS','68741524',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-08-20 09:52:12',NULL,NULL,'2019-08-20 09:52:12',NULL,NULL),('003','01255','C','LUZ MARIA JAVIER PEVE','94734331',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-08-20 09:52:17',NULL,NULL,'2019-08-20 09:52:17',NULL,NULL),('003','01256','C','FLAVIO ITURRIZAGA','10433063',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-08-20 10:10:19',NULL,NULL,'2019-08-20 10:10:19',NULL,NULL),('003','01257','C','DOMINIC MENA LAGURA','60446794',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-08-20 10:13:44',NULL,NULL,'2019-08-20 10:13:44',NULL,NULL),('003','01258','C','SERGIO ALTAMIRANO','42155621',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-08-20 10:13:45',NULL,NULL,'2019-08-20 10:13:45',NULL,NULL),('003','01259','C','NERIBIS MORENO','13547896',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-08-20 10:13:48',NULL,NULL,'2019-08-20 10:13:48',NULL,NULL),('003','01260','C','DANIEL GARCEZ','19186460',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-08-20 10:13:50',NULL,NULL,'2019-08-20 10:13:50',NULL,NULL),('003','01261','C','FRANCIS PACHECHO','17482867',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-08-20 10:13:50',NULL,NULL,'2019-08-20 10:13:50',NULL,NULL),('003','01262','C','LISBETH AGUILAR','15478933',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-08-20 10:13:52',NULL,NULL,'2019-08-20 10:13:52',NULL,NULL),('003','01263','C','JOSEPH VERENZUELA','12457895',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-08-27 14:13:35',NULL,NULL,'2019-08-27 14:13:35',NULL,NULL),('003','01264','C','WUILDER RODRIGUEZ','17024035',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-08-27 14:13:36',NULL,NULL,'2019-08-27 14:13:36',NULL,NULL),('003','01265','C','ALEXANDER SEMPRUN','22481283',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-08-27 14:13:37',NULL,NULL,'2019-08-27 14:13:37',NULL,NULL),('003','01266','C','RONNY RODRIGUEZ','72394575',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-08-27 14:13:44',NULL,NULL,'2019-08-27 14:13:44',NULL,NULL),('003','01267','C','ERIKA','14879526',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-08-27 14:13:48',NULL,NULL,'2019-08-27 14:13:48',NULL,NULL),('003','01268','C','LILIANA ASTULLIDO','18278200',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-08-27 14:21:20',NULL,NULL,'2019-08-27 14:21:20',NULL,NULL),('003','01269','C','ANGIE ZAMBRANO','25164953',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-08-27 14:21:20',NULL,NULL,'2019-08-27 14:21:20',NULL,NULL),('003','01270','C','JOSELYN FUENTES','19773650',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-08-27 14:21:27',NULL,NULL,'2019-08-27 14:21:27',NULL,NULL),('003','01271','C','JUAN CARLOS VELARDE','70387344',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-08-27 14:21:27',NULL,NULL,'2019-08-27 14:21:27',NULL,NULL),('003','01272','C','ERICK MADURO','18593539',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-08-27 14:21:28',NULL,NULL,'2019-08-27 14:21:28',NULL,NULL),('003','01273','C','DARWIN MONCADA','15787765',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-08-27 14:24:44',NULL,NULL,'2019-08-27 14:24:44',NULL,NULL),('003','01274','C','JENNY CHAVEZ','98883558',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-08-27 14:24:44',NULL,NULL,'2019-08-27 14:24:44',NULL,NULL),('003','01275','C','ANDREINA RIVAS','14587954',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-08-27 14:27:25',NULL,NULL,'2019-08-27 14:27:25',NULL,NULL),('003','01276','C','LINO RAMON HURTADO','11598930',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-08-27 14:27:32',NULL,NULL,'2019-08-27 14:27:32',NULL,NULL),('003','01277','C','RICARDO DUGARTE','12458769',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-08-27 14:31:15',NULL,NULL,'2019-08-27 14:31:15',NULL,NULL),('003','01278','C','JUNIOR GUILLEN','41726430',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-08-27 14:31:17',NULL,NULL,'2019-08-27 14:31:17',NULL,NULL),('003','01279','C','KATHERINE BOHORQUEZ','99007344',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-08-27 14:31:19',NULL,NULL,'2019-08-27 14:31:19',NULL,NULL),('003','01280','C','WILLER ARIAS','15473257',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-08-27 14:33:25',NULL,NULL,'2019-08-27 14:33:25',NULL,NULL),('003','01281','C','DARWIN MONCADA','15423459',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-08-27 14:33:25',NULL,NULL,'2019-08-27 14:33:25',NULL,NULL),('003','01282','C','KAREN DIAFERIA','14863429',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-02 11:36:45',NULL,NULL,'2019-09-02 11:36:45',NULL,NULL),('003','01283','C','JOSEFINA CENTENO','82599770',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-02 11:36:48',NULL,NULL,'2019-09-02 11:36:48',NULL,NULL),('003','01284','C','CRISTIAN ARANCIBIA','40832502',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-02 12:01:52',NULL,NULL,'2019-09-02 12:01:52',NULL,NULL),('003','01285','C','MARIANA LANDAETA','15478654',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-02 12:01:52',NULL,NULL,'2019-09-02 12:01:52',NULL,NULL),('003','01286','C','RAFAEL PACHECO GONZAGA','26457188',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-02 12:01:54',NULL,NULL,'2019-09-02 12:01:54',NULL,NULL),('003','01287','C','PENELOPPE FINOL','13175668',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-02 12:01:55',NULL,NULL,'2019-09-02 12:01:55',NULL,NULL),('003','01288','C','ODALINA GIL','19807885',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-02 12:01:55',NULL,NULL,'2019-09-02 12:01:55',NULL,NULL),('003','01289','C','LEIDA BENITEZ','93620420',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-02 12:01:56',NULL,NULL,'2019-09-02 12:01:56',NULL,NULL),('003','01290','C','BRENDA RIJO','27943802',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-02 12:28:25',NULL,NULL,'2019-09-02 12:28:25',NULL,NULL),('003','01291','C','NELLEDEYS JOSEFINA BORRERO','14542658',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-02 12:28:27',NULL,NULL,'2019-09-02 12:28:27',NULL,NULL),('003','01292','C','DANNY LEON','18190397',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-02 12:28:28',NULL,NULL,'2019-09-02 12:28:28',NULL,NULL),('003','01293','C','CARMEN PERDOMO','19979957',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-02 12:28:28',NULL,NULL,'2019-09-02 12:28:28',NULL,NULL),('003','01294','C','ARGELIS MILLAN','18551408',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-02 12:28:29',NULL,NULL,'2019-09-02 12:28:29',NULL,NULL),('003','01295','C','JOSE OLIVIER','16389737',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-02 12:28:31',NULL,NULL,'2019-09-02 12:28:31',NULL,NULL),('003','01296','C','GABRIELA BERMUDEZ','16374889',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-02 12:28:32',NULL,NULL,'2019-09-02 12:28:32',NULL,NULL),('003','01297','C','DARWIN MONCADA','14879232',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-02 12:28:33',NULL,NULL,'2019-09-02 12:28:33',NULL,NULL),('003','01298','C','CARLOS COLO','16584266',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-02 12:28:33',NULL,NULL,'2019-09-02 12:28:33',NULL,NULL),('003','01299','C','ROBERTO MALAGUERA','24542540',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-02 14:27:28',NULL,NULL,'2019-09-02 14:27:28',NULL,NULL),('003','01300','C','JOSE PEÑA','16578465',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-02 14:27:31',NULL,NULL,'2019-09-02 14:27:31',NULL,NULL),('003','01301','C','DARWIN VALERA','20802290',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-02 14:27:35',NULL,NULL,'2019-09-02 14:27:35',NULL,NULL),('003','01302','C','ZURIMA DEL CARMEN BASTARDO','47152288',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-02 14:27:37',NULL,NULL,'2019-09-02 14:27:37',NULL,NULL),('003','01303','C','MALOHA MENDOZA','65478921',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-02 14:27:38',NULL,NULL,'2019-09-02 14:27:38',NULL,NULL),('003','01304','C','NELSON MILLAN','24501045',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-02 14:27:38',NULL,NULL,'2019-09-02 14:27:38',NULL,NULL),('003','01305','C','ANAIS ADAME','89880191',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-02 14:27:39',NULL,NULL,'2019-09-02 14:27:39',NULL,NULL),('003','01306','C','WILLIAN MARQUEZ','69239765',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-02 14:27:40',NULL,NULL,'2019-09-02 14:27:40',NULL,NULL),('003','01307','C','RONNY RODRIGUEZ','72394571',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-02 15:00:22',NULL,NULL,'2019-09-02 15:00:22',NULL,NULL),('003','01308','C','ISAIC FERNANDEZ','20026245',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-02 15:00:23',NULL,NULL,'2019-09-02 15:00:23',NULL,NULL),('003','01309','C','JOSE PEÑA','44811223',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-02 15:31:00',NULL,NULL,'2019-09-02 15:31:00',NULL,NULL),('003','01310','C','ANNABEL ROJAS','20323748',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-02 15:31:00',NULL,NULL,'2019-09-02 15:31:00',NULL,NULL),('003','01311','C','JESUS CASTILLO','79065331',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-02 15:31:08',NULL,NULL,'2019-09-02 15:31:08',NULL,NULL),('003','01312','C','JENNY NUÑEZ','24887875',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-06 11:35:17',NULL,NULL,'2019-09-06 11:35:17',NULL,NULL),('003','01313','C','LUISANA FUCIL','15462354',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-06 11:35:19',NULL,NULL,'2019-09-06 11:35:19',NULL,NULL),('003','01314','C','OLLANTAY GUEDEZ ACOSTA','23555194',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-06 11:35:19',NULL,NULL,'2019-09-06 11:35:19',NULL,NULL),('003','01315','C','JUAN JOSE VARGAS VARGAS','47589622',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-06 11:35:25',NULL,NULL,'2019-09-06 11:35:25',NULL,NULL),('003','01316','C','ELDER LA TORRE','24972116',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-06 11:35:28',NULL,NULL,'2019-09-06 11:35:28',NULL,NULL),('003','01317','C','RENZO CASTILLO','16543322',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-06 11:35:34',NULL,NULL,'2019-09-06 11:35:34',NULL,NULL),('003','01318','C','HAYDEE ABURTO','99026181',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-06 11:35:36',NULL,NULL,'2019-09-06 11:35:36',NULL,NULL),('003','01319','C','GABRIEL PIÑA','22144495',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-06 11:40:27',NULL,NULL,'2019-09-06 11:40:27',NULL,NULL),('003','01320','C','MARIA GUERRA','21145649',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-06 11:40:28',NULL,NULL,'2019-09-06 11:40:28',NULL,NULL),('003','01321','C','CARLOS ALBERTO SOTO','76360171',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-06 11:40:28',NULL,NULL,'2019-09-06 11:40:28',NULL,NULL),('003','01322','C','ENDER JOSE RIOS','12009375',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-06 11:40:30',NULL,NULL,'2019-09-06 11:40:30',NULL,NULL),('003','01323','C','CARMEN MARTINEZ','86860180',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-06 11:40:31',NULL,NULL,'2019-09-06 11:40:31',NULL,NULL),('003','01324','C','KARLA ALVAREZ','17093810',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-06 11:40:31',NULL,NULL,'2019-09-06 11:40:31',NULL,NULL),('003','01325','C','EMILY SUAREZ','26182751',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-06 11:40:35',NULL,NULL,'2019-09-06 11:40:35',NULL,NULL),('003','01326','C','DIONELYS DUQUE','24982963',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-06 11:40:36',NULL,NULL,'2019-09-06 11:40:36',NULL,NULL),('003','01327','C','RONALD HERNANDEZ','47581112',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-06 11:40:36',NULL,NULL,'2019-09-06 11:40:36',NULL,NULL),('003','01328','C','DAYSI BRIZUELA','19003450',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-06 11:40:37',NULL,NULL,'2019-09-06 11:40:37',NULL,NULL),('003','01329','C','WILMER RONDON','20759246',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-06 11:40:37',NULL,NULL,'2019-09-06 11:40:37',NULL,NULL),('003','01330','C','RENZO CASTILLO','15789651',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-06 11:40:42',NULL,NULL,'2019-09-06 11:40:42',NULL,NULL),('003','01331','C','GERARDO PEREZ LUZA','21759345',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-06 11:44:44',NULL,NULL,'2019-09-06 11:44:44',NULL,NULL),('003','01332','C','JOHAN VIELMA','16526113',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-06 11:44:52',NULL,NULL,'2019-09-06 11:44:52',NULL,NULL),('003','01333','C','ALBERTO LUGO','17628282',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-06 11:44:54',NULL,NULL,'2019-09-06 11:44:54',NULL,NULL),('003','01334','C','SEBASTIAN ESCALONA','26049985',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-06 11:44:55',NULL,NULL,'2019-09-06 11:44:55',NULL,NULL),('003','01335','C','JAFET ZAVALA','17842855',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-06 11:44:55',NULL,NULL,'2019-09-06 11:44:55',NULL,NULL),('003','01336','C','YORDAN HERMOSO','18323220',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-06 11:44:57',NULL,NULL,'2019-09-06 11:44:57',NULL,NULL),('003','01337','C','YOSELIN RODRIGUEZ','23635742',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-06 11:44:59',NULL,NULL,'2019-09-06 11:44:59',NULL,NULL),('003','01338','C','ADDELIS RODRIGUEZ','19587430',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-06 11:44:59',NULL,NULL,'2019-09-06 11:44:59',NULL,NULL),('003','01339','C','MANUEL SALAZAR','38061511',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-06 11:45:02',NULL,NULL,'2019-09-06 11:45:02',NULL,NULL),('003','01340','C','GLEDYS PEREZ','23575663',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-10 12:07:36',NULL,NULL,'2019-09-10 12:07:36',NULL,NULL),('003','01341','C','MELI RENDON','27197669',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-10 12:07:36',NULL,NULL,'2019-09-10 12:07:36',NULL,NULL),('003','01342','C','EMILY YARELIS AGAMES CALDERON','20406900',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-10 12:07:38',NULL,NULL,'2019-09-10 12:07:38',NULL,NULL),('003','01343','C','STEFANNY HENRIQUEZ','21029476',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-10 12:07:40',NULL,NULL,'2019-09-10 12:07:40',NULL,NULL),('003','01344','C','LUIS MARQUEZ','20217410',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-10 12:07:40',NULL,NULL,'2019-09-10 12:07:40',NULL,NULL),('003','01345','C','MAURICIO APAZA CARDENAS','40269006',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-10 12:07:41',NULL,NULL,'2019-09-10 12:07:41',NULL,NULL),('003','01346','C','JOSE RIVAS','51345899',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-10 12:07:41',NULL,NULL,'2019-09-10 12:07:41',NULL,NULL),('003','01347','C','YXZHEL POLANCO','17925161',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-10 12:07:41',NULL,NULL,'2019-09-10 12:07:41',NULL,NULL),('003','01348','C','MARCO ANTONIO RODAS HERNANDEZ','42225302',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-10 12:07:42',NULL,NULL,'2019-09-10 12:07:42',NULL,NULL),('003','01349','C','LEONARDO FERNANDEZ','20817566',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-10 12:07:44',NULL,NULL,'2019-09-10 12:07:44',NULL,NULL),('003','01350','C','DANIELA MUJICA','25579151',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-10 12:07:45',NULL,NULL,'2019-09-10 12:07:45',NULL,NULL),('003','01351','C','MAIKER MARTIN','16764162',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-10 12:07:45',NULL,NULL,'2019-09-10 12:07:45',NULL,NULL),('003','01352','C','DANIEL CEPEDA','26914664',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-10 12:07:46',NULL,NULL,'2019-09-10 12:07:46',NULL,NULL),('003','01353','C','GREGORY DUARTE','21366709',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-10 12:07:47',NULL,NULL,'2019-09-10 12:07:47',NULL,NULL),('003','01354','C','MEILY ARANA','20688157',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-10 12:07:47',NULL,NULL,'2019-09-10 12:07:47',NULL,NULL),('003','01355','C','JOSE GARCIA NAVARRO','24901362',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-10 12:12:27',NULL,NULL,'2019-09-10 12:12:27',NULL,NULL),('003','01356','C','DEIVINSON DURAN MELENDEZ','25456923',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-10 12:12:29',NULL,NULL,'2019-09-10 12:12:29',NULL,NULL),('003','01357','C','HECTOR MOLLEJA','24155298',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-10 12:12:31',NULL,NULL,'2019-09-10 12:12:31',NULL,NULL),('003','01358','C','JESSICA VERENZUELA','19268418',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-11 11:53:11',NULL,NULL,'2019-09-11 11:53:11',NULL,NULL),('003','01359','C','ERICK ORTIZ','15547862',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-11 11:53:12',NULL,NULL,'2019-09-11 11:53:12',NULL,NULL),('003','01360','C','ABIUD GONZÁLEZ','25359214',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-11 11:53:14',NULL,NULL,'2019-09-11 11:53:14',NULL,NULL),('003','01361','C','YADANIRA PAYARES','48876082',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-11 11:53:15',NULL,NULL,'2019-09-11 11:53:15',NULL,NULL),('003','01362','C','ELIANIS BOTTERO','15478956',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-11 11:53:17',NULL,NULL,'2019-09-11 11:53:17',NULL,NULL),('003','01363','C','NICOL ALBITES','72922573',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-11 11:53:18',NULL,NULL,'2019-09-11 11:53:18',NULL,NULL),('003','01364','C','ALAN ALVA','48953651',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-11 11:53:18',NULL,NULL,'2019-09-11 11:53:18',NULL,NULL),('003','01365','C','MARIA ANTONIETA CANDIA CHACON','24002545',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-11 11:53:18',NULL,NULL,'2019-09-11 11:53:18',NULL,NULL),('003','01366','C','ZULERKI NOGUERA','25132846',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-11 11:53:20',NULL,NULL,'2019-09-11 11:53:20',NULL,NULL),('003','01367','C','ADRIANA GUERRERO','23429808',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-11 11:53:22',NULL,NULL,'2019-09-11 11:53:22',NULL,NULL),('003','01368','C','JORGE ROCA','16489875',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-11 11:53:26',NULL,NULL,'2019-09-11 11:53:26',NULL,NULL),('003','01369','C','ROBERTO LUJAN','42316476',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-11 11:53:27',NULL,NULL,'2019-09-11 11:53:27',NULL,NULL),('003','01370','C','SUSANA VELASQUEZ MAGALLANES','42311408',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-12 15:14:51',NULL,NULL,'2019-09-12 15:14:51',NULL,NULL),('003','01371','C','ORIANY BENITEZ','26479328',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-12 15:14:51',NULL,NULL,'2019-09-12 15:14:51',NULL,NULL),('003','01372','C','YADANIRA PAYARES','48876081',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-12 15:14:54',NULL,NULL,'2019-09-12 15:14:54',NULL,NULL),('003','01373','C','LUISANA FUCIL','13225478',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-12 15:14:57',NULL,NULL,'2019-09-12 15:14:57',NULL,NULL),('003','01374','C','JESÚS DELGADO','24123579',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-12 15:15:00',NULL,NULL,'2019-09-12 15:15:00',NULL,NULL),('003','01375','C','NATALY AGUILAR','14335175',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-13 16:53:19',NULL,NULL,'2019-09-13 16:53:19',NULL,NULL),('003','01376','C','MANUEL MANRIQUE','10155173',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-18 14:58:08',NULL,NULL,'2019-09-18 14:58:08',NULL,NULL),('003','01377','C','EDUARD SOLANO','81377561',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-18 14:58:11',NULL,NULL,'2019-09-18 14:58:11',NULL,NULL),('003','01378','C','JHORMAN RAMOS','16955808',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-18 15:05:25',NULL,NULL,'2019-09-18 15:05:25',NULL,NULL),('003','01379','C','JORGE ROCA','41610836',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-18 15:05:27',NULL,NULL,'2019-09-18 15:05:27',NULL,NULL),('003','01380','C','GENESIS MARIEL ANGULO MATHEUS','23783111',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-18 15:09:26',NULL,NULL,'2019-09-18 15:09:26',NULL,NULL),('003','01381','C','LEONARDO HENRIQUEZ OCHOA','15849671',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-18 15:09:29',NULL,NULL,'2019-09-18 15:09:29',NULL,NULL),('003','01382','C','ELEAZAR SANTAELLA','16356807',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-18 15:12:03',NULL,NULL,'2019-09-18 15:12:03',NULL,NULL),('003','01383','C','LUIS ALFREDO','18883995',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-18 15:12:04',NULL,NULL,'2019-09-18 15:12:04',NULL,NULL),('003','01384','C','JESUS MARIN','10270544',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-18 15:12:09',NULL,NULL,'2019-09-18 15:12:09',NULL,NULL),('003','01385','C','MARIA ATENCIO','23735064',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-18 15:12:18',NULL,NULL,'2019-09-18 15:12:18',NULL,NULL),('003','01386','C','WILLIAN MARQUEZ','69239761',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-18 15:12:18',NULL,NULL,'2019-09-18 15:12:18',NULL,NULL),('003','01387','C','LEOPOLDO CATRO','87106841',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-18 15:12:19',NULL,NULL,'2019-09-18 15:12:19',NULL,NULL),('003','01388','C',NULL,'14005368',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-18 15:17:37',NULL,NULL,'2019-09-18 15:17:37',NULL,NULL),('003','01389','C',NULL,'15466587',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-18 15:17:40',NULL,NULL,'2019-09-18 15:17:40',NULL,NULL),('003','01390','C',NULL,'16544879',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-18 15:17:43',NULL,NULL,'2019-09-18 15:17:43',NULL,NULL),('003','01391','C',NULL,'54895615',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-18 15:17:44',NULL,NULL,'2019-09-18 15:17:44',NULL,NULL),('003','01392','C','FABIOLA DELGADO','15783654',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-24 16:09:56',NULL,NULL,'2019-09-24 16:09:56',NULL,NULL),('003','01393','C','MARIANA GRATEROL','25173073',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-24 16:09:56',NULL,NULL,'2019-09-24 16:09:56',NULL,NULL),('003','01394','C','ALEJANDRO RODRÍGUEZ','24498065',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-24 16:09:57',NULL,NULL,'2019-09-24 16:09:57',NULL,NULL),('003','01395','C','MARIANA GAMEZ','27231901',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-24 16:09:59',NULL,NULL,'2019-09-24 16:09:59',NULL,NULL),('003','01396','C','JESUS ARMAS','25569523',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-24 16:10:00',NULL,NULL,'2019-09-24 16:10:00',NULL,NULL),('003','01397','C','HENRY GUDIÑO','18230782',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-24 16:10:01',NULL,NULL,'2019-09-24 16:10:01',NULL,NULL),('003','01398','C','MARIA FERNANDA ALVAREZ','17616699',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-24 16:10:03',NULL,NULL,'2019-09-24 16:10:03',NULL,NULL),('003','01399','C','JERSON ANGULO GONZÁLES','44848210',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-24 16:10:04',NULL,NULL,'2019-09-24 16:10:04',NULL,NULL),('003','01400','C','DARWIN MONCADA','12547865',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-24 16:10:05',NULL,NULL,'2019-09-24 16:10:05',NULL,NULL),('003','01401','C','WUILIANS ARMANDO','16040370',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-24 16:13:23',NULL,NULL,'2019-09-24 16:13:23',NULL,NULL),('003','01402','C','MILEIDIS GUTIÉRREZ','15463598',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-24 16:13:24',NULL,NULL,'2019-09-24 16:13:24',NULL,NULL),('003','01403','C','KLEIDIMAR GOUVEIA','27554760',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-24 16:13:24',NULL,NULL,'2019-09-24 16:13:24',NULL,NULL),('003','01404','C','DARWIN MONCADA','14789652',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-24 16:13:30',NULL,NULL,'2019-09-24 16:13:30',NULL,NULL),('003','01405','C','YIVELYCE MORILLO','19307404',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-24 16:13:31',NULL,NULL,'2019-09-24 16:13:31',NULL,NULL),('003','01406','C','FERNANDO CASTILLO','82124501',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-24 16:17:42',NULL,NULL,'2019-09-24 16:17:42',NULL,NULL),('003','01407','C','DARWIN MONCADA','45968896',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-24 16:17:44',NULL,NULL,'2019-09-24 16:17:44',NULL,NULL),('003','01408','C','FLOR MOLINA','12365475',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-24 16:17:44',NULL,NULL,'2019-09-24 16:17:44',NULL,NULL),('003','01409','C','JAVIER CARDEN','17812172',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-24 16:17:47',NULL,NULL,'2019-09-24 16:17:47',NULL,NULL),('003','01410','C','MARYBELIS','12565479',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-24 16:17:47',NULL,NULL,'2019-09-24 16:17:47',NULL,NULL),('003','01411','C','ALAM ZAVALA','14587989',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-24 16:21:49',NULL,NULL,'2019-09-24 16:21:49',NULL,NULL),('003','01412','C','YUSMELYS CARRION','10935722',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-24 16:21:49',NULL,NULL,'2019-09-24 16:21:49',NULL,NULL),('003','01413','C','RISSOS REBECA','12547899',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-24 16:21:50',NULL,NULL,'2019-09-24 16:21:50',NULL,NULL),('003','01414','C','DARWIN MONCADA','12547896',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-24 16:21:51',NULL,NULL,'2019-09-24 16:21:51',NULL,NULL),('003','01415','C','JOSE RAMIREZ','25535135',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-24 16:21:54',NULL,NULL,'2019-09-24 16:21:54',NULL,NULL),('003','01416','C','LEONARDO HENRIQUEZ OCHOA','84967114',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-24 16:21:54',NULL,NULL,'2019-09-24 16:21:54',NULL,NULL),('003','01417','C','ERIKA OBERTO','32083901',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-24 16:24:40',NULL,NULL,'2019-09-24 16:24:40',NULL,NULL),('003','01418','C','ABIUD GONZÁLEZ','12546358',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-24 16:24:45',NULL,NULL,'2019-09-24 16:24:45',NULL,NULL),('003','01419','C','MILEIDIS GUTIÉRREZ','15489658',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-24 16:24:47',NULL,NULL,'2019-09-24 16:24:47',NULL,NULL),('003','01420','C','LISMEIDA RANGEL','32564798',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-24 16:24:47',NULL,NULL,'2019-09-24 16:24:47',NULL,NULL),('003','01421','C','MARIANYELI PAOLA BARROETA DE ARMAS','28204853',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-24 16:24:54',NULL,NULL,'2019-09-24 16:24:54',NULL,NULL),('003','01422','C','JENNY ZAMBRANO','12547893',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-24 16:24:55',NULL,NULL,'2019-09-24 16:24:55',NULL,NULL),('003','01423','C','ERIKA MENDOZA','15478954',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-30 15:52:27',NULL,NULL,'2019-09-30 15:52:27',NULL,NULL),('003','01424','C','JOSELYN LUGO','20795710',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-30 15:52:27',NULL,NULL,'2019-09-30 15:52:27',NULL,NULL),('003','01425','C','YENNY DAZA','12547898',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-30 15:52:31',NULL,NULL,'2019-09-30 15:52:31',NULL,NULL),('003','01426','C','RENZO CASTILLO','13654795',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-30 15:52:31',NULL,NULL,'2019-09-30 15:52:31',NULL,NULL),('003','01427','C','DESIRE SOTURNO','20691964',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-30 15:52:33',NULL,NULL,'2019-09-30 15:52:33',NULL,NULL),('003','01428','C','CARLOS COLO','13547895',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-30 15:52:35',NULL,NULL,'2019-09-30 15:52:35',NULL,NULL),('003','01429','C','DARWIN MONCADA','13587925',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-30 15:52:36',NULL,NULL,'2019-09-30 15:52:36',NULL,NULL),('003','01430','C','SIBELL RICO','17953418',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-30 15:52:38',NULL,NULL,'2019-09-30 15:52:38',NULL,NULL),('003','01431','C','DANNA BAUTISTA','25837342',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-30 15:52:39',NULL,NULL,'2019-09-30 15:52:39',NULL,NULL),('003','01432','C','ANAIS ADAME','18988019',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-30 15:52:39',NULL,NULL,'2019-09-30 15:52:39',NULL,NULL),('003','01433','C','MILEIDIS GUTIÉRREZ','16852166',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-30 15:57:19',NULL,NULL,'2019-09-30 15:57:19',NULL,NULL),('003','01434','C','NICOLAS ROSALES','18405810',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-30 15:57:25',NULL,NULL,'2019-09-30 15:57:25',NULL,NULL),('003','01435','C','WILLIAM DEL VALLE','17450702',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-30 15:57:26',NULL,NULL,'2019-09-30 15:57:26',NULL,NULL),('003','01436','C','INES MARTINEZ','13564785',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-30 15:57:29',NULL,NULL,'2019-09-30 15:57:29',NULL,NULL),('003','01437','C','LEOPOLDO CATRO','87106848',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-30 15:57:32',NULL,NULL,'2019-09-30 15:57:32',NULL,NULL),('003','01438','C','MARY MORENO','12554422',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-30 16:05:23',NULL,NULL,'2019-09-30 16:05:23',NULL,NULL),('003','01439','C','LISMEIDA RANGEL','15489654',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-30 16:05:23',NULL,NULL,'2019-09-30 16:05:23',NULL,NULL),('003','01440','C','ENDER CRESPO','22319742',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-30 16:05:26',NULL,NULL,'2019-09-30 16:05:26',NULL,NULL),('003','01441','C','JENNY CHAVEZ','98883551',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-30 16:05:28',NULL,NULL,'2019-09-30 16:05:28',NULL,NULL),('003','01442','C','JUAN CARLOS','19595976',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-30 16:05:29',NULL,NULL,'2019-09-30 16:05:29',NULL,NULL),('003','01443','C','LUIS ALFREDO LAGARES','12547365',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-30 16:05:29',NULL,NULL,'2019-09-30 16:05:29',NULL,NULL),('003','01444','C','FABIAN','20938912',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-30 16:10:59',NULL,NULL,'2019-09-30 16:10:59',NULL,NULL),('003','01445','C','FERNANDO ULLOA','21164867',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-30 16:11:00',NULL,NULL,'2019-09-30 16:11:00',NULL,NULL),('003','01446','C','MILEIDIS GUTIÉRREZ','13546879',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-30 16:11:07',NULL,NULL,'2019-09-30 16:11:07',NULL,NULL),('003','01447','C','NERIBIS MORENO','12498756',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-30 16:22:47',NULL,NULL,'2019-09-30 16:22:47',NULL,NULL),('003','01448','C','LUIS ALEXANDER CONTRERAS','25519830',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-30 16:22:47',NULL,NULL,'2019-09-30 16:22:47',NULL,NULL),('003','01449','C','GUILLERMO CAIGUA','17902142',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-30 16:22:50',NULL,NULL,'2019-09-30 16:22:50',NULL,NULL),('003','01450','C','FRANCIS RODRIGUEZ','25591603',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-09-30 16:22:51',NULL,NULL,'2019-09-30 16:22:51',NULL,NULL),('003','01451','C','LEOPOLDO CATRO','87106844',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-10-02 16:42:53',NULL,NULL,'2019-10-02 16:42:53',NULL,NULL),('003','01452','C','ROBERT ALI SUAREZ HERNANDEZ','21692185',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-10-02 16:42:56',NULL,NULL,'2019-10-02 16:42:56',NULL,NULL),('003','01453','C','ESTEFANY CLIENTE','22336655',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-10-02 16:42:58',NULL,NULL,'2019-10-02 16:42:58',NULL,NULL),('003','01454','C','SUGEY JIMENEZ','15458851',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-10-02 16:42:58',NULL,NULL,'2019-10-02 16:42:58',NULL,NULL),('003','01455','C','GABRIEL TORRES','24272608',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-10-02 16:42:59',NULL,NULL,'2019-10-02 16:42:59',NULL,NULL),('003','01456','C','JOSE FONTALVE','22369871',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-10-02 16:43:02',NULL,NULL,'2019-10-02 16:43:02',NULL,NULL),('003','01457','C','ZEUS ALEJANDRO','12345671',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-10-02 16:43:05',NULL,NULL,'2019-10-02 16:43:05',NULL,NULL),('003','01458','C','MARLYN','24465268',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-10-02 16:43:07',NULL,NULL,'2019-10-02 16:43:07',NULL,NULL),('003','01459','C','WILLIAM DEL VALLE','74507021',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-10-02 16:43:09',NULL,NULL,'2019-10-02 16:43:09',NULL,NULL),('003','01460','C','RONNY RODRIGUEZ','72394578',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-10-04 11:19:15',NULL,NULL,'2019-10-04 11:19:15',NULL,NULL),('003','01461','C','CRUZ GONZALEZ','36273555',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-10-04 11:19:17',NULL,NULL,'2019-10-04 11:19:17',NULL,NULL),('003','01462','C','JOSE BUSTAMANTE','25242613',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-10-04 11:19:18',NULL,NULL,'2019-10-04 11:19:18',NULL,NULL),('003','01463','C','GLEDYS PEREZ','12325678',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-10-04 11:19:20',NULL,NULL,'2019-10-04 11:19:20',NULL,NULL),('003','01464','C','GARY TUESTA GARCIA','15412365',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-10-04 11:19:22',NULL,NULL,'2019-10-04 11:19:22',NULL,NULL),('003','01465','C','CARLOS ANTONIO LUJAN NAVARRO','16846279',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-10-04 11:19:24',NULL,NULL,'2019-10-04 11:19:24',NULL,NULL),('003','01466','C','XIOMARA GONZALEZ','45689751',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-10-04 11:25:48',NULL,NULL,'2019-10-04 11:25:48',NULL,NULL),('003','01467','C','ORLANDO LINARES','21445692',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-10-04 11:25:51',NULL,NULL,'2019-10-04 11:25:51',NULL,NULL),('003','01468','C','RISSOS REBECA','13547894',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-10-04 11:25:58',NULL,NULL,'2019-10-04 11:25:58',NULL,NULL),('003','01469','C','DARWIN MONCADA','12547957',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-10-04 11:30:05',NULL,NULL,'2019-10-04 11:30:05',NULL,NULL),('003','01470','C','JENNY ZAMBRANO','13254655',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-10-04 11:30:07',NULL,NULL,'2019-10-04 11:30:07',NULL,NULL),('003','01471','C','ROSIEL LUCAMBIO','14701284',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-10-11 17:07:31',NULL,NULL,'2019-10-11 17:07:31',NULL,NULL),('003','01472','C','DAYSI MARTINEZ','15490144',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-10-11 17:07:33',NULL,NULL,'2019-10-11 17:07:33',NULL,NULL),('003','01473','C','ISRAEL','34000000',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-10-11 17:07:34',NULL,NULL,'2019-10-11 17:07:34',NULL,NULL),('003','01474','C','JOAN MACHADO','15652417',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-10-11 17:07:35',NULL,NULL,'2019-10-11 17:07:35',NULL,NULL),('003','01475','C','YOANA ALVAREZ','90605984',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-10-11 17:07:41',NULL,NULL,'2019-10-11 17:07:41',NULL,NULL),('003','01476','C','ANDRES VALENZUELA','42249920',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-10-11 17:07:42',NULL,NULL,'2019-10-11 17:07:42',NULL,NULL),('003','01477','C','FABIAN UZCATEGUI','27868557',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-10-11 17:07:43',NULL,NULL,'2019-10-11 17:07:43',NULL,NULL),('003','01478','C','JORGE LUIS CRESPO PEÑA','18303346',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-10-11 17:12:02',NULL,NULL,'2019-10-11 17:12:02',NULL,NULL),('003','01479','C','LEONARDO HENRIQUEZ OCHOA','84967145',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-10-11 17:12:03',NULL,NULL,'2019-10-11 17:12:03',NULL,NULL),('003','01480','C','MILEIDIS GUTIÉRREZ','12546985',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-10-11 17:12:07',NULL,NULL,'2019-10-11 17:12:07',NULL,NULL),('003','01481','C','ERICK ORTIZ','12547895',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-10-11 17:15:03',NULL,NULL,'2019-10-11 17:15:03',NULL,NULL),('003','01482','C','VICTOR CAMACHO','16587465',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-10-11 17:15:09',NULL,NULL,'2019-10-11 17:15:09',NULL,NULL),('003','01483','C','YULIANA JOSEFINA ROJAS PARADES','18456969',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-10-11 17:15:14',NULL,NULL,'2019-10-11 17:15:14',NULL,NULL),('003','01484','C','LISSETH RUIZ','45879548',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-10-11 17:15:14',NULL,NULL,'2019-10-11 17:15:14',NULL,NULL),('003','01485','C','DOMINGO RODRÍGUEZ SÁNCHEZ','10451266',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-10-11 17:15:16',NULL,NULL,'2019-10-11 17:15:16',NULL,NULL),('003','01486','C','RONALD RUIZ','16587495',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-10-11 17:15:19',NULL,NULL,'2019-10-11 17:15:19',NULL,NULL),('003','01487','C','ERICK PÁEZ','19531718',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-10-11 17:19:38',NULL,NULL,'2019-10-11 17:19:38',NULL,NULL),('003','01488','C','BEATRIZ YAJAYRA WILCA ZEGARRA','48068432',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-10-11 17:19:39',NULL,NULL,'2019-10-11 17:19:39',NULL,NULL),('003','01489','C','GREGORY JOSÉ BONILLO GUZMAN','27606884',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-10-11 17:25:39',NULL,NULL,'2019-10-11 17:25:39',NULL,NULL),('003','01490','C','GUILLERMO TABORDA','19351638',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-10-11 17:25:42',NULL,NULL,'2019-10-11 17:25:42',NULL,NULL),('003','01491','C','ALFREDO AYMERICH','24801903',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-10-11 17:29:32',NULL,NULL,'2019-10-11 17:29:32',NULL,NULL),('003','01492','C','ERIKA MENDOZA','16548798',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-10-11 17:29:35',NULL,NULL,'2019-10-11 17:29:35',NULL,NULL),('003','01493','C','CARLOS ENRIQUE VICTORIA ALVAREZ','76373457',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-10-11 17:29:36',NULL,NULL,'2019-10-11 17:29:36',NULL,NULL),('003','01494','C','MARIA SUAREZ','16547985',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-10-16 12:38:20',NULL,NULL,'2019-10-16 12:38:20',NULL,NULL),('003','01495','C','MARIA SUAREZ','13565495',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-10-16 12:38:21',NULL,NULL,'2019-10-16 12:38:21',NULL,NULL),('003','01496','C','ANDRES LEON','19663759',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-10-16 12:38:24',NULL,NULL,'2019-10-16 12:38:24',NULL,NULL),('003','01497','C','GREGORIA MAVARE','45623598',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-10-16 12:38:24',NULL,NULL,'2019-10-16 12:38:24',NULL,NULL),('003','01498','C','SANDY GAMARRA','12549875',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-10-16 12:38:27',NULL,NULL,'2019-10-16 12:38:27',NULL,NULL),('003','01499','C','OSLEIDY SALCEDO','28182433',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-10-16 12:42:22',NULL,NULL,'2019-10-16 12:42:22',NULL,NULL),('003','01500','C','ALBERTO LANDAETA','84117841',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-10-16 12:42:24',NULL,NULL,'2019-10-16 12:42:24',NULL,NULL),('003','01501','C','YAISA REYES','12365478',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-10-16 12:45:44',NULL,NULL,'2019-10-16 12:45:44',NULL,NULL),('003','01502','C','ROBERT ALI SUAREZ HERNANDEZ','21692181',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-10-16 12:45:44',NULL,NULL,'2019-10-16 12:45:44',NULL,NULL),('003','01503','C','HEBRAIN ARTEAGA','22276489',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-10-16 12:45:52',NULL,NULL,'2019-10-16 12:45:52',NULL,NULL),('003','01504','C','LOREN ROJAS','16548794',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-10-16 12:45:54',NULL,NULL,'2019-10-16 12:45:54',NULL,NULL),('003','01505','C','JOSE ZERPA','55449966',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-10-18 16:41:39',NULL,NULL,'2019-10-18 16:41:39',NULL,NULL),('003','01506','C','JENNY CHAVEZ','98883557',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-10-18 16:45:16',NULL,NULL,'2019-10-18 16:45:16',NULL,NULL),('003','01507','C','YULEISI HERNAND','24224841',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-10-18 16:45:20',NULL,NULL,'2019-10-18 16:45:20',NULL,NULL),('003','01508','C','JOSE ALEJANDRO CHARAGUA GONZALEZ','20181011',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-10-18 16:45:21',NULL,NULL,'2019-10-18 16:45:21',NULL,NULL),('003','01509','C','CARLOS DAVID VIVAS GUILLEN','21346703',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-10-18 16:45:21',NULL,NULL,'2019-10-18 16:45:21',NULL,NULL),('003','01510','C','MANUEL SALAZAR','38061512',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-10-18 16:45:23',NULL,NULL,'2019-10-18 16:45:23',NULL,NULL),('003','01511','C','ARMANDO CORDOVA','20604667',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-10-18 16:45:23',NULL,NULL,'2019-10-18 16:45:23',NULL,NULL),('003','01512','C','CARLOS COLO','15457876',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-10-18 16:45:25',NULL,NULL,'2019-10-18 16:45:25',NULL,NULL),('003','01513','C','JENNY ZAMBRANO','13569845',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-10-18 16:45:26',NULL,NULL,'2019-10-18 16:45:26',NULL,NULL),('003','01514','C','MANUEL SALAZAR','38061558',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-10-18 16:45:26',NULL,NULL,'2019-10-18 16:45:26',NULL,NULL),('003','01515','C','LUIS ALFREDO LAGARES','13587944',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-10-18 16:48:36',NULL,NULL,'2019-10-18 16:48:36',NULL,NULL),('003','01516','C','MARIA AMADOR','13412104',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-10-18 16:48:39',NULL,NULL,'2019-10-18 16:48:39',NULL,NULL),('003','01517','C','YENNY DAZA','15364774',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-10-18 16:48:41',NULL,NULL,'2019-10-18 16:48:41',NULL,NULL),('003','01518','C','MANUEL SALAZAR','38061545',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-10-18 16:48:43',NULL,NULL,'2019-10-18 16:48:43',NULL,NULL),('003','01519','C','JOSDELYS MILANES','17449916',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-10-18 16:48:44',NULL,NULL,'2019-10-18 16:48:44',NULL,NULL),('003','01520','C','RONALD RUIZ','21658791',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-10-22 16:31:53',NULL,NULL,'2019-10-22 16:31:53',NULL,NULL),('003','01521','C','NOELIS','13245678',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-10-22 16:31:54',NULL,NULL,'2019-10-22 16:31:54',NULL,NULL),('003','01522','C','HOWARD CORONADO','21150229',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-10-22 16:31:57',NULL,NULL,'2019-10-22 16:31:57',NULL,NULL),('003','01523','C','ALEJANDRO ELIAS','11122233',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-10-22 16:31:57',NULL,NULL,'2019-10-22 16:31:57',NULL,NULL),('003','01524','C','RENZO CASTILLO','12578954',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-10-22 16:31:59',NULL,NULL,'2019-10-22 16:31:59',NULL,NULL),('003','01525','C','ANDREINA RODRÍGUEZ','18310619',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-10-22 16:31:59',NULL,NULL,'2019-10-22 16:31:59',NULL,NULL),('003','01526','C','JENNY ZAMBRANO','13558799',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-10-22 16:32:04',NULL,NULL,'2019-10-22 16:32:04',NULL,NULL),('003','01527','C','SEBASTIAN GUTIERREZ','21370448',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-10-22 16:35:07',NULL,NULL,'2019-10-22 16:35:07',NULL,NULL),('003','01528','C','DEIVID BLANCO','14699047',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-10-22 16:35:11',NULL,NULL,'2019-10-22 16:35:11',NULL,NULL),('003','01529','C','LEONARDO HENRIQUEZ OCHOA','84967154',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-10-22 16:35:12',NULL,NULL,'2019-10-22 16:35:12',NULL,NULL),('003','01530','C','LUIS MIGUEL VEGA','78007741',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-10-22 16:35:13',NULL,NULL,'2019-10-22 16:35:13',NULL,NULL),('003','01531','C','JENNY ZAMBRANO','12358952',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-10-22 16:35:14',NULL,NULL,'2019-10-22 16:35:14',NULL,NULL),('003','01532','C','LUZDARIS MARTINEZ','18851865',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-10-22 16:35:15',NULL,NULL,'2019-10-22 16:35:15',NULL,NULL),('003','01533','C','NILSY CHIRINOS','16633928',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-10-22 16:35:17',NULL,NULL,'2019-10-22 16:35:17',NULL,NULL),('003','01534','C','MILEIDIS GUTIÉRREZ','16587956',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-10-22 16:35:19',NULL,NULL,'2019-10-22 16:35:19',NULL,NULL),('003','01535','C','MANUEL COVA','12254708',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-10-22 16:38:57',NULL,NULL,'2019-10-22 16:38:57',NULL,NULL),('003','01536','C','MARÍA ALBORNOZ','26066906',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-10-22 16:39:00',NULL,NULL,'2019-10-22 16:39:00',NULL,NULL),('003','01537','C','DAIMELYN MORENO','12610023',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-10-22 16:39:00',NULL,NULL,'2019-10-22 16:39:00',NULL,NULL),('003','01538','C','LENIN JOSE CALCINA GOMEZ','46414762',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-10-22 16:39:00',NULL,NULL,'2019-10-22 16:39:00',NULL,NULL),('003','01539','C','DANIEL PUERTA','33355598',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-10-22 16:39:03',NULL,NULL,'2019-10-22 16:39:03',NULL,NULL),('003','01540','C','MIGUEL PIMENTEL','15789913',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-10-22 16:39:12',NULL,NULL,'2019-10-22 16:39:12',NULL,NULL),('003','01541','C','JONATHAN PÉREZ','19798665',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-10-22 16:39:14',NULL,NULL,'2019-10-22 16:39:14',NULL,NULL),('003','01542','C','CARLOS COLO','16547995',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-10-28 15:17:29',NULL,NULL,'2019-10-28 15:17:29',NULL,NULL),('003','01543','C','RONALD RUIZ','13254782',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-10-28 15:17:29',NULL,NULL,'2019-10-28 15:17:29',NULL,NULL),('003','01544','C','JULIO ESPINOZA','23692598',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-10-28 15:17:33',NULL,NULL,'2019-10-28 15:17:33',NULL,NULL),('003','01545','C','CRISTIAM BUSTAMANTE','22752099',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-10-28 15:17:33',NULL,NULL,'2019-10-28 15:17:33',NULL,NULL),('003','01546','C','FABIAN','20938945',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-10-28 15:17:38',NULL,NULL,'2019-10-28 15:17:38',NULL,NULL),('003','01547','C','SUGEY JIMENEZ','13254789',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-10-28 15:17:38',NULL,NULL,'2019-10-28 15:17:38',NULL,NULL),('003','01548','C','CAROLINA QUINTERO','15356144',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-10-28 15:17:39',NULL,NULL,'2019-10-28 15:17:39',NULL,NULL),('003','01549','C','ELADIO PAMPAS VIVAS','75167802',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-10-28 15:17:42',NULL,NULL,'2019-10-28 15:17:42',NULL,NULL),('003','01550','C','LUIS TORI','17153754',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-10-28 15:26:59',NULL,NULL,'2019-10-28 15:26:59',NULL,NULL),('003','01551','C','USBEIMAR RODRIGUEZ','24340321',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-10-28 15:26:59',NULL,NULL,'2019-10-28 15:26:59',NULL,NULL),('003','01552','C','NEHIYIBEL LEÓN BELLO','19054372',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-10-28 15:27:04',NULL,NULL,'2019-10-28 15:27:04',NULL,NULL),('003','01553','C','ALBERTO LANDAETA','84117842',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-10-28 15:30:10',NULL,NULL,'2019-10-28 15:30:10',NULL,NULL),('003','01554','C','JOSÉ SARMIENTO','13563724',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-10-28 15:30:12',NULL,NULL,'2019-10-28 15:30:12',NULL,NULL),('003','01555','C','MIGUEL GONZALEZ','23649370',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-10-28 15:30:16',NULL,NULL,'2019-10-28 15:30:16',NULL,NULL),('003','01556','C','RENZO CASTILLO','16547853',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-10-28 15:30:19',NULL,NULL,'2019-10-28 15:30:19',NULL,NULL),('003','01557','C','ERIKA OBERTO','32083902',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-10-28 15:30:19',NULL,NULL,'2019-10-28 15:30:19',NULL,NULL),('003','01558','C','DAVID PEÑA','17483636',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-10-28 15:30:21',NULL,NULL,'2019-10-28 15:30:21',NULL,NULL),('003','01559','C','RONNY RODRIGUEZ','72394572',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-10-28 15:30:21',NULL,NULL,'2019-10-28 15:30:21',NULL,NULL),('003','01560','C','ELIAMAR LEVEL SANCHEZ','15021344',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-10-28 15:30:22',NULL,NULL,'2019-10-28 15:30:22',NULL,NULL),('003','01561','C','JIMMY JULIO ALVAREZ ELLIS','42951383',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-10-28 15:30:22',NULL,NULL,'2019-10-28 15:30:22',NULL,NULL),('003','01562','C','LOREN ROJAS','13254785',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-10-28 15:33:44',NULL,NULL,'2019-10-28 15:33:44',NULL,NULL),('003','01563','C','CESAR CASANOTAN','41290286',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-10-28 15:33:51',NULL,NULL,'2019-10-28 15:33:51',NULL,NULL),('003','01564','C','JESUS SANCHEZ','59600791',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-10-28 15:37:40',NULL,NULL,'2019-10-28 15:37:40',NULL,NULL),('003','01565','C','CAROLA FUENMAYOR','11287234',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-10-28 15:37:41',NULL,NULL,'2019-10-28 15:37:41',NULL,NULL),('003','01566','C','DARWIN MONCADA','13547855',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-10-28 15:37:46',NULL,NULL,'2019-10-28 15:37:46',NULL,NULL),('003','01567','C','YARMENI MENDOZA','15181376',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-10-31 10:58:42',NULL,NULL,'2019-10-31 10:58:42',NULL,NULL),('003','01568','C','KEVIN RAMÍREZ','70942226',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-10-31 10:58:44',NULL,NULL,'2019-10-31 10:58:44',NULL,NULL),('003','01569','C','FABIAN OSUNA','55544499',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-10-31 10:58:46',NULL,NULL,'2019-10-31 10:58:46',NULL,NULL),('003','01570','C','ARACELI ROJAS','12365498',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-10-31 10:58:59',NULL,NULL,'2019-10-31 10:58:59',NULL,NULL),('003','01571','C','KITTA MOUCHATI','17869032',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-10-31 11:02:17',NULL,NULL,'2019-10-31 11:02:17',NULL,NULL),('003','01572','C','RENZO CASTILLO','15465554',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-10-31 11:02:25',NULL,NULL,'2019-10-31 11:02:25',NULL,NULL),('003','01573','C','MACKERRLLY OMAIRA','14867948',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-10-31 11:02:27',NULL,NULL,'2019-10-31 11:02:27',NULL,NULL),('003','01574','C','KELY CALDERON','25806501',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-10-31 11:02:28',NULL,NULL,'2019-10-31 11:02:28',NULL,NULL),('003','01575','C','NEIDY CASTRO','18980068',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-10-31 11:06:13',NULL,NULL,'2019-10-31 11:06:13',NULL,NULL),('003','01576','C','RAMÓN JOSÉ UGAS','13325478',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-10-31 11:06:14',NULL,NULL,'2019-10-31 11:06:14',NULL,NULL),('003','01577','C','JOSE BLANCO','19944856',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-10-31 11:06:15',NULL,NULL,'2019-10-31 11:06:15',NULL,NULL),('003','01578','C','SUGEY JIMENEZ','15458855',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-10-31 11:06:18',NULL,NULL,'2019-10-31 11:06:18',NULL,NULL),('003','01579','C','MILEIDIS GUTIÉRREZ','13547994',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-07 12:42:33',NULL,NULL,'2019-11-07 12:42:33',NULL,NULL),('003','01580','C','ELVIANNIS GONZÁLEZ','22255215',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-07 12:42:35',NULL,NULL,'2019-11-07 12:42:35',NULL,NULL),('003','01581','C','FERNANDO APARICIO','54988157',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-07 12:42:36',NULL,NULL,'2019-11-07 12:42:36',NULL,NULL),('003','01582','C','WILMER AGRAZ','16590933',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-07 12:42:41',NULL,NULL,'2019-11-07 12:42:41',NULL,NULL),('003','01583','C','ANDRIU COLMENARES','11122299',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-07 12:46:48',NULL,NULL,'2019-11-07 12:46:48',NULL,NULL),('003','01584','C','JOSELYN ALVAREZ','12344455',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-07 12:46:52',NULL,NULL,'2019-11-07 12:46:52',NULL,NULL),('003','01585','C','CRUZ ARANDA','10161771',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-07 12:46:53',NULL,NULL,'2019-11-07 12:46:53',NULL,NULL),('003','01586','C','OCTAVIO LINDO','68277580',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-07 12:46:58',NULL,NULL,'2019-11-07 12:46:58',NULL,NULL),('003','01587','C','JONNATHAN RODRIGUEZ','10000870',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-07 12:47:00',NULL,NULL,'2019-11-07 12:47:00',NULL,NULL),('003','01588','C','XIOMARA GONZALEZ','45689755',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-07 12:47:02',NULL,NULL,'2019-11-07 12:47:02',NULL,NULL),('003','01589','C','MARIANA LANDAETA','12458755',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-07 12:47:02',NULL,NULL,'2019-11-07 12:47:02',NULL,NULL),('003','01590','C','KATERINE BENCOMO','27571245',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-07 12:47:03',NULL,NULL,'2019-11-07 12:47:03',NULL,NULL),('003','01591','C','LUZ RODRIGUEZ','26750374',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-07 12:51:18',NULL,NULL,'2019-11-07 12:51:18',NULL,NULL),('003','01592','C','RONALD RUIZ','12547854',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-07 12:51:19',NULL,NULL,'2019-11-07 12:51:19',NULL,NULL),('003','01593','C','MELIDA MILLAN','56949550',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-07 12:51:20',NULL,NULL,'2019-11-07 12:51:20',NULL,NULL),('003','01594','C','RICHARD ALZUALDE','14574277',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-07 12:51:22',NULL,NULL,'2019-11-07 12:51:22',NULL,NULL),('003','01595','C','LEONAR LUJANO','10000000',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-07 12:51:24',NULL,NULL,'2019-11-07 12:51:24',NULL,NULL),('003','01596','C','ROSMERY RAMON','14567824',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-07 12:51:27',NULL,NULL,'2019-11-07 12:51:27',NULL,NULL),('003','01597','C','CRUZ GONZALEZ','36273554',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-07 12:51:29',NULL,NULL,'2019-11-07 12:51:29',NULL,NULL),('003','01598','C','RAFAEL FERNÁNDEZ','15525996',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-07 12:51:30',NULL,NULL,'2019-11-07 12:51:30',NULL,NULL),('003','01599','C','LUIS BLONDEL','11201774',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-07 12:51:32',NULL,NULL,'2019-11-07 12:51:32',NULL,NULL),('003','01600','C','RONNY RODRIGUEZ','72394574',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-07 12:51:33',NULL,NULL,'2019-11-07 12:51:33',NULL,NULL),('003','01601','C','KELY CALDERON','25806505',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-07 12:51:35',NULL,NULL,'2019-11-07 12:51:35',NULL,NULL),('003','01602','C','LUIS ALFREDO LAGARES','12567741',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-07 12:51:35',NULL,NULL,'2019-11-07 12:51:35',NULL,NULL),('003','01603','C','DELIANA SALAZAR','20419007',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-07 12:51:36',NULL,NULL,'2019-11-07 12:51:36',NULL,NULL),('003','01604','C','ZENAIDA  TERRONES  CORTEZ','30000000',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-07 12:54:18',NULL,NULL,'2019-11-07 12:54:18',NULL,NULL),('003','01605','C','ALEJANDRA ORTUÑO','20000000',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-07 12:54:20',NULL,NULL,'2019-11-07 12:54:20',NULL,NULL),('003','01606','C','LISAURA RODRÍGUEZ','20503545',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-07 12:54:27',NULL,NULL,'2019-11-07 12:54:27',NULL,NULL),('003','01607','C','FERNANDO APARICIO','95937312',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-07 12:54:28',NULL,NULL,'2019-11-07 12:54:28',NULL,NULL),('003','01608','C','ANDREINA','50000000',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-07 12:59:57',NULL,NULL,'2019-11-07 12:59:57',NULL,NULL),('003','01609','C','FABIAN','20938955',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-07 12:59:59',NULL,NULL,'2019-11-07 12:59:59',NULL,NULL),('003','01610','C','EULIMAR GONZALEZ','20763970',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-07 13:00:03',NULL,NULL,'2019-11-07 13:00:03',NULL,NULL),('003','01611','C','CORZO PULGAR DWJISIBETH CHIQUINQUIRA','29921436',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-07 13:00:06',NULL,NULL,'2019-11-07 13:00:06',NULL,NULL),('003','01612','C','KITTA MOUCHATI','17869052',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-08 16:39:49',NULL,NULL,'2019-11-08 16:39:49',NULL,NULL),('003','01613','C','MARYURIS TROCONI','13254756',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-08 16:39:51',NULL,NULL,'2019-11-08 16:39:51',NULL,NULL),('003','01614','C','LEONARDO HENRIQUEZ OCHOA','84967152',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-08 16:39:54',NULL,NULL,'2019-11-08 16:39:54',NULL,NULL),('003','01615','C','DARWIN MONCADA','13257894',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-08 16:39:56',NULL,NULL,'2019-11-08 16:39:56',NULL,NULL),('003','01616','C','MACHADO YORJELYS','25841532',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-08 16:39:57',NULL,NULL,'2019-11-08 16:39:57',NULL,NULL),('003','01617','C','ADEURY CASTILLO','19266678',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-08 16:39:57',NULL,NULL,'2019-11-08 16:39:57',NULL,NULL),('003','01618','C','ÁNGEL BENAVÍDEZ','14788931',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-12 11:40:05',NULL,NULL,'2019-11-12 11:40:05',NULL,NULL),('003','01619','C','LEOPOLDO CATRO','87106845',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-12 11:40:09',NULL,NULL,'2019-11-12 11:40:09',NULL,NULL),('003','01620','C','LUISANA FUCIL','16547892',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-12 11:46:04',NULL,NULL,'2019-11-12 11:46:04',NULL,NULL),('003','01621','C','ALEX PALOMINO','97779301',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-12 11:46:08',NULL,NULL,'2019-11-12 11:46:08',NULL,NULL),('003','01622','C','KARINA SALAZAR','33100662',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-12 11:46:10',NULL,NULL,'2019-11-12 11:46:10',NULL,NULL),('003','01623','C','NEREYDA NEGRIN TORREALBA','19181102',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-12 11:46:11',NULL,NULL,'2019-11-12 11:46:11',NULL,NULL),('003','01624','C','RODOLFO ANTONIO PARICAS','22510482',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-12 11:46:12',NULL,NULL,'2019-11-12 11:46:12',NULL,NULL),('003','01625','C','JENNY ZAMBRANO','16547954',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-12 11:46:12',NULL,NULL,'2019-11-12 11:46:12',NULL,NULL),('003','01626','C','MARYBELIS','13257895',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-12 11:46:13',NULL,NULL,'2019-11-12 11:46:13',NULL,NULL),('003','01627','C','KATHERINE MONTES','21038165',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-12 11:54:16',NULL,NULL,'2019-11-12 11:54:16',NULL,NULL),('003','01628','C','JEIVER JOSE','80000000',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-12 11:54:19',NULL,NULL,'2019-11-12 11:54:19',NULL,NULL),('003','01629','C','ORIANA LOPEZ','26936710',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-12 11:54:23',NULL,NULL,'2019-11-12 11:54:23',NULL,NULL),('003','01630','C','INES MARTINEZ','13562478',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-12 11:54:23',NULL,NULL,'2019-11-12 11:54:23',NULL,NULL),('003','01631','C','ANDREA PATIÑO','15856785',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-12 11:54:24',NULL,NULL,'2019-11-12 11:54:24',NULL,NULL),('003','01632','C','DARWIN VALLES','19448381',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-12 11:54:24',NULL,NULL,'2019-11-12 11:54:24',NULL,NULL),('003','01633','C','RAFAEL ELCHI','70000000',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-12 11:54:28',NULL,NULL,'2019-11-12 11:54:28',NULL,NULL),('003','01634','C','RENZO CASTILLO','13254679',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-14 16:02:49',NULL,NULL,'2019-11-14 16:02:49',NULL,NULL),('003','01635','C','DARWIN MONCADA','13256488',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-14 16:02:56',NULL,NULL,'2019-11-14 16:02:56',NULL,NULL),('003','01636','C','JUAN VALERIANO','41135844',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-14 16:11:13',NULL,NULL,'2019-11-14 16:11:13',NULL,NULL),('003','01637','C','MARINA RODRIGUEZ','20248004',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-14 16:11:14',NULL,NULL,'2019-11-14 16:11:14',NULL,NULL),('003','01638','C','ANA HERNANDEZ','90000000',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-14 16:11:16',NULL,NULL,'2019-11-14 16:11:16',NULL,NULL),('003','01639','C','WINSTON TAMBO','17572299',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-14 16:11:16',NULL,NULL,'2019-11-14 16:11:16',NULL,NULL),('003','01640','C','MILEIDIS GUTIÉRREZ','12654254',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-18 15:08:05',NULL,NULL,'2019-11-18 15:08:05',NULL,NULL),('003','01641','C','RENZO CASTILLO','16578492',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-18 15:08:10',NULL,NULL,'2019-11-18 15:08:10',NULL,NULL),('003','01642','C','RUBÉN ALBERTO CRISCI CEDEÑO','18886467',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-18 15:11:49',NULL,NULL,'2019-11-18 15:11:49',NULL,NULL),('003','01643','C','ELADIO CENTENO','14077576',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-18 15:11:50',NULL,NULL,'2019-11-18 15:11:50',NULL,NULL),('003','01644','C','JUAN BURGOS','16525122',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-18 15:11:51',NULL,NULL,'2019-11-18 15:11:51',NULL,NULL),('003','01645','C','LEONARDO HENRIQUEZ OCHOA','84967126',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-18 15:11:53',NULL,NULL,'2019-11-18 15:11:53',NULL,NULL),('003','01646','C','JOSE GREGORIO VIELMA','17528611',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-18 15:11:54',NULL,NULL,'2019-11-18 15:11:54',NULL,NULL),('003','01647','C','RONALD RUIZ','16254788',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-18 15:15:23',NULL,NULL,'2019-11-18 15:15:23',NULL,NULL),('003','01648','C','OLIVER CEDEÑO','96944281',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-18 15:15:27',NULL,NULL,'2019-11-18 15:15:27',NULL,NULL),('003','01649','C','LOREN ROJAS','16254789',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-18 15:15:29',NULL,NULL,'2019-11-18 15:15:29',NULL,NULL),('003','01650','C','CRUZ GONZALEZ','36273552',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-18 15:15:33',NULL,NULL,'2019-11-18 15:15:33',NULL,NULL),('003','01651','C','VICTOR ALFONSO MOSQUERA GUTIERREZ','28371964',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-20 11:23:19',NULL,NULL,'2019-11-20 11:23:19',NULL,NULL),('003','01652','C','ERIKA OBERTO','32083905',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-20 11:23:21',NULL,NULL,'2019-11-20 11:23:21',NULL,NULL),('003','01653','C','FERNANDO APARICIO','95937315',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-20 11:23:25',NULL,NULL,'2019-11-20 11:23:25',NULL,NULL),('003','01654','C','LEYNNIYER XAVIER SANDREA URDANETA','31756584',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-20 11:23:25',NULL,NULL,'2019-11-20 11:23:25',NULL,NULL),('003','01655','C','RENZO CASTILLO','25846077',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-20 11:23:26',NULL,NULL,'2019-11-20 11:23:26',NULL,NULL),('003','01656','C','YENNIFER  HERNÁNDEZ','18678502',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-20 11:23:26',NULL,NULL,'2019-11-20 11:23:26',NULL,NULL),('003','01657','C','GUTIERREZ MARIN','17451659',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-20 11:23:26',NULL,NULL,'2019-11-20 11:23:26',NULL,NULL),('003','01658','C','NERIBIS MORENO','18475254',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-20 11:23:37',NULL,NULL,'2019-11-20 11:23:37',NULL,NULL),('003','01659','C','YENNY CHAPARRO','13100336',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-20 11:28:00',NULL,NULL,'2019-11-20 11:28:00',NULL,NULL),('003','01660','C','JOSE VENGO','12000000',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-20 11:28:02',NULL,NULL,'2019-11-20 11:28:02',NULL,NULL),('003','01661','C','ADEURY OMAR CASTILLO RIOS','10961428',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-22 12:58:30',NULL,NULL,'2019-11-22 12:58:30',NULL,NULL),('003','01662','C','YEXIBETH ALEJANDRA MONTIEL CARIDAD','28512825',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-22 12:58:34',NULL,NULL,'2019-11-22 12:58:34',NULL,NULL),('003','01663','C','LUIS ALFREDO LAGARES','13535874',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-22 12:58:37',NULL,NULL,'2019-11-22 12:58:37',NULL,NULL),('003','01664','C','GUILLERMO AZOCAR','16254892',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-22 12:58:38',NULL,NULL,'2019-11-22 12:58:38',NULL,NULL),('003','01665','C','ALEXANDRA SALAZAR','21093170',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-22 13:03:53',NULL,NULL,'2019-11-22 13:03:53',NULL,NULL),('003','01666','C','JESSICA BARRERA','13542258',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-22 13:03:54',NULL,NULL,'2019-11-22 13:03:54',NULL,NULL),('003','01667','C','SEGUNDO BASILIO PEÑA','76763917',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-22 13:03:55',NULL,NULL,'2019-11-22 13:03:55',NULL,NULL),('003','01668','C','CARLOS COLO','13254784',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-22 13:03:56',NULL,NULL,'2019-11-22 13:03:56',NULL,NULL),('003','01669','C','CORZO PULGAR DWJISIBETH CHIQUINQUIRA','24921436',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-22 13:03:59',NULL,NULL,'2019-11-22 13:03:59',NULL,NULL),('003','01670','C','FRANCISCO JAVIER GONZALEZ VICUNA','26618324',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-22 13:03:59',NULL,NULL,'2019-11-22 13:03:59',NULL,NULL),('003','01671','C','OROMAICA DE GUILLEN','10687391',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-22 13:03:59',NULL,NULL,'2019-11-22 13:03:59',NULL,NULL),('003','01672','C','YEIVER JOSE','25476933',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-25 14:51:16',NULL,NULL,'2019-11-25 14:51:16',NULL,NULL),('003','01673','C','ALEXIS PALENCIA','17654479',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-25 14:51:16',NULL,NULL,'2019-11-25 14:51:16',NULL,NULL),('003','01674','C','LEONARDO HENRIQUEZ OCHOA','84967165',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-25 14:51:16',NULL,NULL,'2019-11-25 14:51:16',NULL,NULL),('003','01675','C','JENNY ZAMBRANO','16547891',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-25 14:51:21',NULL,NULL,'2019-11-25 14:51:21',NULL,NULL),('003','01676','C','JESUS CARABALLO','13982897',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-25 14:51:22',NULL,NULL,'2019-11-25 14:51:22',NULL,NULL),('003','01677','C','VICTOR ALFONSO MOSQUERA GUTIERREZ','28371958',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-25 15:00:31',NULL,NULL,'2019-11-25 15:00:31',NULL,NULL),('003','01678','C','HAYDEE ABURTO','99026184',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-25 15:00:31',NULL,NULL,'2019-11-25 15:00:31',NULL,NULL),('003','01679','C','LUZDARIS MARTINEZ','18851828',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-25 15:00:35',NULL,NULL,'2019-11-25 15:00:35',NULL,NULL),('003','01680','C','WILLIAN MARQUEZ','69239762',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-25 15:00:36',NULL,NULL,'2019-11-25 15:00:36',NULL,NULL),('003','01681','C','PABLO EMILIO GUDIÑO PEREZ','19991165',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-25 15:00:36',NULL,NULL,'2019-11-25 15:00:36',NULL,NULL),('003','01682','C','DALLANY QUINTERO','20850958',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-26 16:24:59',NULL,NULL,'2019-11-26 16:24:59',NULL,NULL),('003','01683','C','INES MARTINEZ','13547955',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-26 16:25:03',NULL,NULL,'2019-11-26 16:25:03',NULL,NULL),('003','01684','C','NATALY AGUILAR','14335172',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-26 16:25:07',NULL,NULL,'2019-11-26 16:25:07',NULL,NULL),('003','01685','C','ELIANIS BOTTERO','32657941',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-26 16:25:15',NULL,NULL,'2019-11-26 16:25:15',NULL,NULL),('003','01686','C','EUDYS LEONALDO OBANDO QUINTERO','30598225',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-27 11:35:31',NULL,NULL,'2019-11-27 11:35:31',NULL,NULL),('003','01687','C','MISLEIDY CORINA BAUTE MOSQUEDA','10819261',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-28 11:39:56',NULL,NULL,'2019-11-28 11:39:56',NULL,NULL),('003','01688','C','LEONARDO JOSE ESPINOSA PIÑA','93355145',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-28 11:39:56',NULL,NULL,'2019-11-28 11:39:56',NULL,NULL),('003','01689','C','SUSAN YANIRE BRICEÑO SANTIAGO','25797860',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-28 11:39:56',NULL,NULL,'2019-11-28 11:39:56',NULL,NULL),('003','01690','C','YVELYS DAMELIS SANCHEZ BONILLA','23641714',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-28 11:40:02',NULL,NULL,'2019-11-28 11:40:02',NULL,NULL),('003','01691','C','YOHANNA ALEJANDRA','16874636',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-12-03 12:43:12',NULL,NULL,'2019-12-03 12:43:12',NULL,NULL),('003','01692','C','ALIANI Velasco','21202884',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-12-03 12:43:12',NULL,NULL,'2019-12-03 12:43:12',NULL,NULL),('003','01693','C','NANCY MENDOZA','10776472',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-12-03 12:43:15',NULL,NULL,'2019-12-03 12:43:15',NULL,NULL),('003','01694','C','LUIS ALFREDO LAGARES','16547894',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-12-03 12:43:16',NULL,NULL,'2019-12-03 12:43:16',NULL,NULL),('003','01695','C','JUAN CARLOS SALAZAR SALAS','11724576',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-12-03 12:46:47',NULL,NULL,'2019-12-03 12:46:47',NULL,NULL),('003','01696','C','JESUS PARRA','18904635',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-12-03 12:46:52',NULL,NULL,'2019-12-03 12:46:52',NULL,NULL),('003','01697','C','XIOMARA GONZALEZ','45689752',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-12-03 12:46:54',NULL,NULL,'2019-12-03 12:46:54',NULL,NULL),('003','01698','C','RONALD RUIZ','13265499',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-12-03 12:46:55',NULL,NULL,'2019-12-03 12:46:55',NULL,NULL),('003','01699','C','MILEIDIS DANIELA QUIROZ QUIROZ','26006861',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-12-03 13:06:12',NULL,NULL,'2019-12-03 13:06:12',NULL,NULL),('003','01700','C','ERIKA MENDOZA','16547989',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-12-03 13:06:15',NULL,NULL,'2019-12-03 13:06:15',NULL,NULL),('003','01701','C','JULIO EMIRO BENTI OCANTO','80711652',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-12-03 13:06:17',NULL,NULL,'2019-12-03 13:06:17',NULL,NULL),('003','01702','C','RONALD RUIZ','16487952',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-12-05 11:58:33',NULL,NULL,'2019-12-05 11:58:33',NULL,NULL),('003','01703','C','ROSA DELLEPIANE','95309262',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-12-05 11:58:33',NULL,NULL,'2019-12-05 11:58:33',NULL,NULL),('003','01704','C','ENRIQUE JOSIMAR PACHECO CANDOTTI','18431243',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-12-05 11:58:33',NULL,NULL,'2019-12-05 11:58:33',NULL,NULL),('003','01705','C','GUTIERREZ MARIN','17451658',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-12-05 11:58:34',NULL,NULL,'2019-12-05 11:58:34',NULL,NULL),('003','01706','C','JESUS ARIAS','13254778',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-12-05 11:58:38',NULL,NULL,'2019-12-05 11:58:38',NULL,NULL),('003','01707','C','EDWARD MANUEL LOPEZ PALACIOS','30712523',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-12-05 11:58:43',NULL,NULL,'2019-12-05 11:58:43',NULL,NULL),('003','01708','C','MANUEL ÁLVAREZ','15616154',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-12-05 11:58:47',NULL,NULL,'2019-12-05 11:58:47',NULL,NULL),('003','01709','C','ALBA VIRGINIA LADERA FANEITE','19379823',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-12-05 11:58:50',NULL,NULL,'2019-12-05 11:58:50',NULL,NULL),('003','01710','C','NERIBIS MORENO','16578546',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-12-05 11:58:50',NULL,NULL,'2019-12-05 11:58:50',NULL,NULL),('003','01711','C','AMILCAR SANCHEZ','16519351',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-12-05 12:02:25',NULL,NULL,'2019-12-05 12:02:25',NULL,NULL),('003','01712','C','VICTOR LUIS GAMARRA CRUZ','42186713',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-12-05 12:07:50',NULL,NULL,'2019-12-05 12:07:50',NULL,NULL),('003','01713','C','MARVELYS GARCIA','27958865',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-12-05 12:07:52',NULL,NULL,'2019-12-05 12:07:52',NULL,NULL),('003','01714','C','ANA CRISTINA GONZALEZ ROJAS','12822125',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-12-05 12:07:53',NULL,NULL,'2019-12-05 12:07:53',NULL,NULL),('003','01715','C','YECEBELTH ISLEYER VALERA LEONARDES','26338256',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-12-05 12:07:56',NULL,NULL,'2019-12-05 12:07:56',NULL,NULL),('003','01716','C','LUIS ALFREDO LAGARES','16584999',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-12-05 12:07:58',NULL,NULL,'2019-12-05 12:07:58',NULL,NULL),('003','01717','C','RONALD RUIZ','16587995',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-12-09 12:12:56',NULL,NULL,'2019-12-09 12:12:56',NULL,NULL),('003','01718','C','ELIANIS BOTTERO','16547895',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-12-09 12:18:26',NULL,NULL,'2019-12-09 12:18:26',NULL,NULL),('003','01719','C','DANIELA','14854225',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-12-09 12:18:26',NULL,NULL,'2019-12-09 12:18:26',NULL,NULL),('003','01720','C','EDWARD MANUEL LOPEZ PALACIOS','30712554',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-12-09 12:18:28',NULL,NULL,'2019-12-09 12:18:28',NULL,NULL),('003','01721','C','JESUS SANCHEZ','59600792',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-12-09 12:18:30',NULL,NULL,'2019-12-09 12:18:30',NULL,NULL),('003','01722','C','GIANFRANCO DELLEPIANE','82262325',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-12-09 12:18:31',NULL,NULL,'2019-12-09 12:18:31',NULL,NULL),('003','01723','C','PABLO EMILIO GUDIÑO PEREZ','19991146',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-12-09 12:18:35',NULL,NULL,'2019-12-09 12:18:35',NULL,NULL),('003','01724','C','ERIKA MENDOZA','16547998',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-12-09 12:21:29',NULL,NULL,'2019-12-09 12:21:29',NULL,NULL),('003','01725','C','DIANA CABEZA','20446672',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-12-10 11:32:42',NULL,NULL,'2019-12-10 11:32:42',NULL,NULL),('003','01726','C','ANDERSON CALANCHE','22289845',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-12-10 11:32:45',NULL,NULL,'2019-12-10 11:32:45',NULL,NULL),('003','01727','C','LUIS HERNANDEZ','16503077',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-12-10 11:32:46',NULL,NULL,'2019-12-10 11:32:46',NULL,NULL),('003','01728','C','SANDY VICTORIA GAMARRA RODRIGUEZ','45915092',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-12-10 11:32:54',NULL,NULL,'2019-12-10 11:32:54',NULL,NULL),('003','01729','C','ANDREINA  Quiñones','18344969',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-12-10 11:32:59',NULL,NULL,'2019-12-10 11:32:59',NULL,NULL),('003','01730','C','ANGEL ENRIQUE MOGOLLON ARIAS','91287362',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-12-11 12:54:22',NULL,NULL,'2019-12-11 12:54:22',NULL,NULL),('003','01731','C','MARYURIS TROCONI','16254952',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-12-11 12:54:26',NULL,NULL,'2019-12-11 12:54:26',NULL,NULL),('003','01732','C','FABIAN','20938946',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-12-11 12:54:26',NULL,NULL,'2019-12-11 12:54:26',NULL,NULL),('003','01733','C','SULEISY DEL VALLE VILLALBA COLON','25566319',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-12-11 12:54:28',NULL,NULL,'2019-12-11 12:54:28',NULL,NULL),('003','01734','C','GIANFRANCO DELLEPIANE','82262358',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-12-13 12:24:33',NULL,NULL,'2019-12-13 12:24:33',NULL,NULL),('003','01735','C','KATHERINE MONTES','21038152',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-12-13 12:24:34',NULL,NULL,'2019-12-13 12:24:34',NULL,NULL),('003','01736','C','ALEXANDRA PEREZ','22103633',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-12-13 12:24:37',NULL,NULL,'2019-12-13 12:24:37',NULL,NULL),('003','01737','C','EDWARD MANUEL LOPEZ PALACIOS','30712524',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-12-13 12:24:40',NULL,NULL,'2019-12-13 12:24:40',NULL,NULL),('003','01738','C','ALEXANDER VALERO','26825520',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-12-13 14:53:38',NULL,NULL,'2019-12-13 14:53:38',NULL,NULL),('003','01739','C','ALBERTO LANDAETA','84117845',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-12-13 14:53:42',NULL,NULL,'2019-12-13 14:53:42',NULL,NULL),('003','01740','C','YAILIN ANDREA MEJIA COLMENARES','31002757',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-12-13 14:53:45',NULL,NULL,'2019-12-13 14:53:45',NULL,NULL),('004','00002','C','MARCELINO CUEVAS QUISPE','20543408',NULL,'',NULL,'',NULL,NULL,NULL,'1OFI','0100','1',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1',NULL,NULL,'DAVID','2019-08-21 17:39:31','ip-172-31-81-196',NULL,'2019-08-21 17:39:31',NULL,''),('004','00003','C','MUÑOZ LUCERO SONIA LEONIDAS','10157656312','-','',NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'MAXIMO','2019-08-29 12:08:09','ip-172-31-81-196',NULL,'2019-08-29 12:08:09',NULL,''),('004','00004','C','CLIENTE','.','.','',NULL,'',NULL,NULL,NULL,'1OFI','0100','0',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'0',NULL,NULL,'FONSECA','2019-08-30 11:09:54','ip-172-31-81-196',NULL,'2019-08-30 11:09:54',NULL,''),('004','00005','C','FIERROS EL TORO EIRL','20514702145','AV. TUPAC AMARU NRO. 3303 URB. EL CHILCAL LIMA - LIMA - COMAS','',NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'VENTAS','2019-08-30 11:31:21','ip-172-31-81-196',NULL,'2019-08-30 11:31:21',NULL,''),('004','00006','C','INDUALTA SOCIEDAD ANONIMA CERRADA','20546007392','AV. TUPAC AMARU NRO. 1719 URB. HUAQUILLAY (ALT. PARADERO EX SEGURO) LIMA - LIMA - COMAS','',NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'VENTAS','2019-08-30 11:36:05','ip-172-31-81-196',NULL,'2019-08-30 11:36:05',NULL,''),('004','00007','C','WILDER ANIBAL FONSECA BLAS','32301376',NULL,'',NULL,'',NULL,NULL,NULL,'1OFI','0100','1',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1',NULL,NULL,'VENTAS','2019-08-30 11:53:13','ip-172-31-81-196',NULL,'2019-08-30 11:53:13',NULL,''),('004','00008','C','FABRICACION DE MALLAS PERU S.A.C.','20604522022','JR. ASCOPE NRO. 0 CUADRA 4, TIENDA Y-11 (C.C. BELLOTA) LIMA - LIMA - LIMA','',NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'VENTAS1','2019-09-02 10:54:19','ip-172-31-81-196',NULL,'2019-09-02 10:54:19',NULL,''),('004','00009','C','MAXIMO TORRES ROMANI','60146942',NULL,'',NULL,'',NULL,NULL,NULL,'1OFI','0100','1',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1',NULL,NULL,'VENTAS1','2019-09-02 11:11:04','ip-172-31-81-196',NULL,'2019-09-02 11:11:04',NULL,''),('004','00010','C','CORPORACION SAINFO E.I.R.L. - SAINFO E.I.R.L.','20604067899','CAL.9 MZA. Y LOTE. 31 A.H. 1 DE JUNIO (COMERCIAL ALEMANA) LIMA - LIMA - SAN JUAN DE MIRAFLORES','',NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'VENTAS1','2019-09-02 12:07:22','ip-172-31-81-196',NULL,'2019-09-02 12:07:22',NULL,''),('004','00011','C','MEDINA SANCHEZ KEILLY JULISSA','10416185285','mnz j  lote 2 asociacion tungasuca 3ra etapa','',NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'VENTAS','2019-09-03 09:36:18','ip-172-31-81-196',NULL,'2019-09-03 09:36:18',NULL,''),('004','00012','C','DE LOS SANTOS AYALA WILLIAN ADRIANO','10434184253','mza. A Lt. 1 El Bosque III carabayllo  lima',NULL,NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'VENTAS1','2019-09-03 13:26:21','ip-172-31-81-196','VENTAS','2019-10-04 14:37:19','ip-172-31-81-196',''),('004','00013','C','MATOS DELGADO ANTONIO JOSE','10097446364','-','',NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'VENTAS','2019-09-06 15:18:07','ip-172-31-81-196',NULL,'2019-09-06 15:18:07',NULL,''),('004','00014','C','CERTIFICADORES PROFESIONALES S.A.C.','20518620747','CAL.07 MZA. A1 LOTE. 29 ASOC VILLA CORPAC (ESPALDA DE HOSTAL PUNTO AZUL) LIMA - LIMA - CARABAYLLO','',NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'VENTAS1','2019-09-06 16:37:51','ip-172-31-81-196',NULL,'2019-09-06 16:37:51',NULL,''),('004','00015','C','LOTIZADORA ALTOS DE PUNCHAUCA S.A.C.','20460584290','AV. SAN FELIPE NRO. 483 URB. URB. SAN FELIPE SECT A LIMA - LIMA - COMAS','',NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'VENTAS1','2019-09-06 16:58:03','ip-172-31-81-196',NULL,'2019-09-06 16:58:03',NULL,''),('004','00016','C','LUIS ALBERTO FERNANDEZ RAMIREZ','06092957',NULL,'',NULL,'',NULL,NULL,NULL,'1OFI','0100','1',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1',NULL,NULL,'VENTAS1','2019-09-07 15:23:40','ip-172-31-81-196',NULL,'2019-09-07 15:23:40',NULL,''),('004','00017','C','JPC SOLUCIONES S.A.C.','20604318506','JR. PIURA NRO. 312 P.J. PAMPA DE COMAS LIMA - LIMA - COMAS','',NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'VENTAS','2019-09-09 13:10:40','ip-172-31-81-196',NULL,'2019-09-09 13:10:40',NULL,''),('004','00018','C','CREACIONES JC E.I.R.L.','20516016290','JR. BARTOLOME HERRERA NRO. 672 URB. HUAQUILLAY (ALT KM 12 TUPAC AMARU-ESPALDA CINE TUPAC) LIMA - LIMA - COMAS','',NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'VENTAS','2019-09-10 16:26:26','ip-172-31-81-196',NULL,'2019-09-10 16:26:26',NULL,''),('004','00019','C','CALDERON VALENTIN JORGE LUIS','10080385221','-','',NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'VENTAS1','2019-09-11 16:01:53','ip-172-31-81-196',NULL,'2019-09-11 16:01:53',NULL,''),('004','00020','C','NEGOCIACIONES ELY & MAR S.A.C.','20501876241','AV. CHACRA CERRO LOTE. A-4 URB. LOTIZACION LIMA - LIMA - COMAS','',NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'VENTAS','2019-09-13 09:37:11','ip-172-31-81-196',NULL,'2019-09-13 09:37:11',NULL,''),('004','00021','C','J&M SAC OPERADOR LOGISTICO','20507891447','AV. ALFREDO MENDIOLA NRO. 6821 DPTO. 802C (ALT. PARADERO SAN MARTIN) LIMA - LIMA - LOS OLIVOS','',NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'VENTAS','2019-09-13 14:11:22','ip-172-31-81-196',NULL,'2019-09-13 14:11:22',NULL,''),('004','00022','C','SOLAR TEAM S.A.C','20523477774','JR. HUSARES DE JUNIN NRO. 449 INT. PI 2 URB. EL RETABLO - ETAPA 1 (AV UNIVERSITARIA ALT. BOULEVAR EL RETABL) LIMA - LIMA - COMAS','',NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'VENTAS','2019-09-14 12:13:30','ip-172-31-81-196',NULL,'2019-09-14 12:13:30',NULL,''),('004','00023','C','JAKUTEC S.A.','20552933339','JR. GENERAL SUCRE NRO. 375 URB. HUAQUILLAY II ETAPA (ESP.BELAUNDE OESTE CDRA7 KM.13) LIMA - LIMA - COMAS','',NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'VENTAS','2019-09-16 11:02:05','ip-172-31-81-196',NULL,'2019-09-16 11:02:05',NULL,''),('004','00024','C','ARIZA HIDALGO LUIS ALBERTO','10103980564','-','',NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'VENTAS1','2019-09-16 15:18:13','ip-172-31-81-196',NULL,'2019-09-16 15:18:13',NULL,''),('004','00025','C','GRUPO BARATIE S.A.C. - BARATIE S.A.C.','20604100152','AV. UNIVERSITARIA NORTE MZA. D LOTE. 17 A.H. SAN JUAN BAUTISTA LIMA - LIMA - COMAS','',NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'VENTAS','2019-09-17 10:05:33','ip-172-31-81-196',NULL,'2019-09-17 10:05:33',NULL,''),('004','00026','C','CARLOS ANTONIO ENCARNACION BRAVO','06886271',NULL,'',NULL,'',NULL,NULL,NULL,'1OFI','0100','1',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1',NULL,NULL,'VENTAS','2019-09-17 10:11:01','ip-172-31-81-196',NULL,'2019-09-17 10:11:01',NULL,''),('004','00027','C','OSCAR SEGUNDO SULLON SOLANO','40286612',NULL,'RIMAC',NULL,'','alvaradojeri@gmail.com',NULL,NULL,'1OFI','0100','1',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1',NULL,NULL,'VENTAS','2019-09-17 15:25:33','ip-172-31-81-196','FONSECA','2019-11-08 07:50:12','ip-172-31-81-196',''),('004','00028','C','TRANSFMAT S.A.C.','20557925181','JR. JOSE DE LA TORRE UGARTE NRO. 199 URB. EL RETABLO LIMA - LIMA - COMAS','',NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'VENTAS','2019-09-18 08:26:38','ip-172-31-81-196',NULL,'2019-09-18 08:26:38',NULL,''),('004','00029','C','ALVA SOLIS RUBEN ISAAC','10097448219','AV GUILLERMO DE LA FUENTE  # 207 COMAS',NULL,NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'VENTAS','2019-09-18 10:44:59','ip-172-31-81-196','VENTAS','2019-11-11 16:39:59','ip-172-31-81-196',''),('004','00030','C','WILLIAN ADRIANO DE LOS SANTOS AYALA','43418425',NULL,'',NULL,'',NULL,NULL,NULL,'1OFI','0100','1',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1',NULL,NULL,'VENTAS1','2019-09-19 11:14:20','ip-172-31-81-196',NULL,'2019-09-19 11:14:20',NULL,''),('004','00031','C','LUIS EDUARDO SARRIA DE LA CRUZ','09963719','asociación de comerciantes del mercado chacracerro','',NULL,'',NULL,NULL,NULL,'1OFI','0100','1',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1',NULL,NULL,'VENTAS','2019-09-19 14:43:13','ip-172-31-81-196',NULL,'2019-09-19 14:43:13',NULL,''),('004','00032','C','ENMA SULEMA CAMACHO LUCIO','71053785',NULL,'',NULL,'',NULL,NULL,NULL,'1OFI','0100','1',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1',NULL,NULL,'VENTAS1','2019-09-19 15:53:38','ip-172-31-81-196',NULL,'2019-09-19 15:53:38',NULL,''),('004','00033','C','FAMENAC E.I.R.L.','20557826875','CAL.PALLCAMARCA MZA. L-10 LOTE. 15 DPTO. PS3 URB. TUPAC AMARU (FTE AL CEP RICARDO PALMA) LIMA - LIMA - INDEPENDENCIA','',NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'VENTAS1','2019-09-20 11:15:53','ip-172-31-81-196',NULL,'2019-09-20 11:15:53',NULL,''),('004','00034','C','MERY LOZANO ESCOBAR','45476527',NULL,'',NULL,'',NULL,NULL,NULL,'1OFI','0100','1',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1',NULL,NULL,'VENTAS','2019-09-21 11:36:10','ip-172-31-81-196',NULL,'2019-09-21 11:36:10',NULL,''),('004','00035','C','CARLOS ALBERTO TITO TIPULA','80406387',NULL,'',NULL,'',NULL,NULL,NULL,'1OFI','0100','1',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1',NULL,NULL,'VENTAS','2019-09-21 14:30:29','ip-172-31-81-196',NULL,'2019-09-21 14:30:29',NULL,''),('004','00036','C','METAL MECANICA Y CONSTRUCCIONES DEL PERU S.A.C. - METCOP S.A.C.','20601198500','JR. LIBERTAD NRO. 1350 P.J. SANTA ROSA LIMA - LIMA - COMAS','',NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'VENTAS','2019-09-23 10:04:00','ip-172-31-81-196',NULL,'2019-09-23 10:04:00',NULL,''),('004','00037','C','MULTISERVICIOS MONTENEGRO E.I.R.L','20555674091','JR. BEATITA DE HUMAY NRO. 188 (ENTRE AV TUPAC AMARU Y AV BELAUNDE) LIMA - LIMA - COMAS','',NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'VENTAS','2019-09-23 16:45:09','ip-172-31-81-196',NULL,'2019-09-23 16:45:09',NULL,''),('004','00038','C','JERUSAP EIRL','20600254830','JR. ARGENTINA NRO. 743 INT. PIS1 URB. EL PARRAL LIMA - LIMA - COMAS','',NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'VENTAS','2019-09-24 09:27:36','ip-172-31-81-196',NULL,'2019-09-24 09:27:36',NULL,''),('004','00039','C','ALVA SOLIS RUBEN ISAAC','10097448216','-','',NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'VENTAS','2019-09-24 09:42:44','ip-172-31-81-196',NULL,'2019-09-24 09:42:44',NULL,''),('004','00040','C','GUILLEN FERNANDEZ WUILLIAMS','10068691511','-','',NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'VENTAS','2019-09-24 10:02:38','ip-172-31-81-196',NULL,'2019-09-24 10:02:38',NULL,''),('004','00041','C','CLIMACO VASQUEZ CESAR ANTONIO','10212621353','-','',NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'VENTAS1','2019-09-24 14:04:34','ip-172-31-81-196',NULL,'2019-09-24 14:04:34',NULL,''),('004','00042','C','BENAVIDES GUEVARA ABRAHAM JAIME','10099756484','-','',NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'VENTAS','2019-09-24 15:37:00','ip-172-31-81-196',NULL,'2019-09-24 15:37:00',NULL,''),('004','00043','C','RAUL SILVESTRE MEJIA RONDAN','09468297',NULL,'',NULL,'',NULL,NULL,NULL,'1OFI','0100','1',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1',NULL,NULL,'VENTAS','2019-09-25 16:40:45','ip-172-31-81-196',NULL,'2019-09-25 16:40:45',NULL,''),('004','00044','C','QUINTANA LUCANO DELICIA','10419816421','-','',NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'VENTAS1','2019-09-26 15:22:35','ip-172-31-81-196',NULL,'2019-09-26 15:22:35',NULL,''),('004','00045','C','RABBIT GLASS SOCIEDAD ANONIMA','20509192694','JR. JOSE CARLOS MARIATEGUI NRO. 328 URB. SAN AGUSTIN (KM. 13.5 AV. TUPAC AMARU) LIMA - LIMA - COMAS','',NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'VENTAS','2019-09-27 09:56:44','ip-172-31-81-196',NULL,'2019-09-27 09:56:44',NULL,''),('004','00046','C','RENTERIA PUQUIO ANGEL ENRIQUE','10061438969','-','',NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'VENTAS','2019-09-27 10:11:43','ip-172-31-81-196',NULL,'2019-09-27 10:11:43',NULL,''),('004','00047','C','SERVICIOS GENERALES K.V.V. S.A.C.','20552434685','JR. GRAU NRO. 476 P.J. RPB (FTE A LA MUNICIPALIDAD DE CARABAYLLO) LIMA - LIMA - CARABAYLLO','',NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'VENTAS1','2019-09-27 13:50:49','ip-172-31-81-196',NULL,'2019-09-27 13:50:49',NULL,''),('004','00048','C','V & T INDUSTRIAL MECAN S.A.C. - VITIM S.A.C.','20600671392','MZA. C LOTE. 6 APV. SAN JUAN SR.DE LA SOLEDAD LIMA - LIMA - PUENTE PIEDRA','',NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'VENTAS','2019-09-27 14:55:27','ip-172-31-81-196',NULL,'2019-09-27 14:55:27',NULL,''),('004','00049','C','AGUIRRE SANCHEZ MANUEL','10103932853','-','',NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'VENTAS','2019-09-27 16:48:26','ip-172-31-81-196',NULL,'2019-09-27 16:48:26',NULL,''),('004','00050','C','INSTITUTO ESPECIALIZADO EN SERVICIOS DE SALUD S.A.C.','20600385411','AV. LA MAR NRO. 1112 LIMA - LIMA - PUEBLO LIBRE (MAGDALENA VIEJA)','',NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'VENTAS','2019-09-27 18:05:01','ip-172-31-81-196',NULL,'2019-09-27 18:05:01',NULL,''),('004','00051','C','AMED MANTENIMIENTO Y REPRESENTACIONES S.A.C.','20600483570','JR. MANUEL A. ODRIA NRO. 950 EL PROGRESO LIMA - LIMA - CARABAYLLO','',NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'VENTAS1','2019-09-28 11:10:21','ip-172-31-81-196',NULL,'2019-09-28 11:10:21',NULL,''),('004','00052','C','ESTUDIO AGÜERO DEL CARPIO & ASOCIADOS SOCIEDAD CIVIL DE RESPONSABILIDAD LIMITADA - AGÜERO DEL CARPIO','20602544321','CAL.LOS ALGARROBOS NRO. 1670 LIMA - LIMA - LIMA','',NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'VENTAS','2019-09-28 16:01:40','ip-172-31-81-196',NULL,'2019-09-28 16:01:40',NULL,''),('004','00053','C','IMPEX INDUSTRIAL S.A.C','20603949120','----DANIEL ALCIDES CARRION MZA. H LOTE. 11 LAS MARGARITAS LIMA - LIMA - SAN MARTIN DE PORRES','',NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'VENTAS','2019-09-30 10:40:21','ip-172-31-81-196',NULL,'2019-09-30 10:40:21',NULL,''),('004','00054','C','RILOP CONSULTORIA & CONSTRUCTORA S.A.C.','20602026303','AV. ESTRELLA NRO. 142 INT. A (TERCER PISO) LIMA - LIMA - ATE','',NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'VENTAS','2019-09-30 10:56:55','ip-172-31-81-196',NULL,'2019-09-30 10:56:55',NULL,''),('004','00055','C','ALAYZA ARROYO WILLIAMS ALFRED','10404616493','-','',NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'VENTAS','2019-10-02 10:39:09','ip-172-31-81-196',NULL,'2019-10-02 10:39:09',NULL,''),('004','00056','C','LAKSHMI & LAKSHMI S.A.C.','20603519583','----HUASCAR NRO. 462 STA ROSA LIMA - LIMA - COMAS','',NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'VENTAS','2019-10-04 13:59:01','ip-172-31-81-196',NULL,'2019-10-04 13:59:01',NULL,''),('004','00057','C','INFACO S.A.','20517546543','JR. BOSQUE DE HUALLAY NRO. 450 URB. LOS VIÑEDOS (EX. MZ. B LOTE 3) LIMA - LIMA - COMAS','',NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'VENTAS1','2019-10-04 15:04:33','ip-172-31-81-196',NULL,'2019-10-04 15:04:33',NULL,''),('004','00058','C','CARBONERO CARDENAS LUIS ANTONIO','10068750925','-','',NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'VENTAS','2019-10-05 09:11:14','ip-172-31-81-196',NULL,'2019-10-05 09:11:14',NULL,''),('004','00059','C','SANDOVAL JUAREZ JOSE WALTER','10069181827','-','',NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'VENTAS','2019-10-07 10:33:16','ip-172-31-81-196',NULL,'2019-10-07 10:33:16',NULL,''),('004','00060','C','CENTRO DE EDUCACION TEMPRANA CHIQUILIDERES E.I.R.L.','20600854578','AV. LOS INCAS NRO. 1054 URB. EL PINAR (CRUCE AV. LOS INCAS Y CALLE 48) LIMA - LIMA - COMAS','',NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'VENTAS','2019-10-07 16:48:11','ip-172-31-81-196',NULL,'2019-10-07 16:48:11',NULL,''),('004','00061','C','GALVEZ DELGADO FRANCISCO MARTIN','10094834355','-','',NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'VENTAS','2019-10-08 10:27:45','ip-172-31-81-196',NULL,'2019-10-08 10:27:45',NULL,''),('004','00062','C','BEDREGAL CUBA MIGUEL ANGEL','10076145500','-','',NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'VENTAS','2019-10-08 11:38:20','ip-172-31-81-196',NULL,'2019-10-08 11:38:20',NULL,''),('004','00063','C','G & C ESTRUCTURAS SAC','20604147442',NULL,'',NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'VENTAS','2019-10-09 11:36:44','ip-172-31-81-196',NULL,'2019-10-09 11:36:44',NULL,''),('004','00064','C','SERVICIOS GENERALES SEHU SCRL','20538667863',NULL,NULL,NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'VENTAS','2019-10-09 12:48:25','ip-172-31-81-196','VENTAS','2019-10-09 13:11:30','ip-172-31-81-196',''),('004','00065','C','LUIS ALBERTO PALACIOS ZAVALETA','41264399',NULL,'',NULL,'',NULL,NULL,NULL,'1OFI','0100','1',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1',NULL,NULL,'VENTAS1','2019-10-09 13:09:15','ip-172-31-81-196',NULL,'2019-10-09 13:09:15',NULL,''),('004','00066','C','CUBA LOPEZ SAUL','10200850284','-','',NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'VENTAS1','2019-10-09 14:58:33','ip-172-31-81-196',NULL,'2019-10-09 14:58:33',NULL,''),('004','00067','C','D & D\'E ACCESORIOS BRAND IDENTY S.A.C.','20604203733','MZA. 80 LOTE. 33 URB. TORREBLANCA (AV TUPAC AMARU KM 23.5 GRIFO PRIMAX) LIMA - LIMA - CARABAYLLO','',NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'VENTAS1','2019-10-09 15:03:27','ip-172-31-81-196',NULL,'2019-10-09 15:03:27',NULL,''),('004','00068','C','JC PRODUCTOS ALIMENTICIOS S.A.C.','20600907761','AV. LOS CEDROS MZA. C LOTE. 03 URB. SHANGRILLA (PARADERO SHANGRILLA) LIMA - LIMA - PUENTE PIEDRA','',NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'VENTAS','2019-10-11 12:57:01','ip-172-31-81-196',NULL,'2019-10-11 12:57:01',NULL,''),('004','00069','C','LADRILLOS ANDINO S.A.C.','20552876351','CAL.2 MZA. E LOTE. 12 URB. LOS ROBLES DE ATE (3RA ETAPA) LIMA - LIMA - ATE','',NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'VENTAS1','2019-10-11 13:11:51','ip-172-31-81-196',NULL,'2019-10-11 13:11:51',NULL,''),('004','00070','C','KAP KONECTA PERU S.A.C.','20601441781','MZA. S7 LOTE. 5 A.H. AÑO NUEVO (ALTURA DE PARADERO BELASCO) LIMA - LIMA - COMAS','',NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'VENTAS1','2019-10-11 13:31:54','ip-172-31-81-196',NULL,'2019-10-11 13:31:54',NULL,''),('004','00071','C','CONSTRUCCIONES MORENO SOCIEDAD COMERCIAL DE RESPONSABILIDAD LIMITADA - CONSTRUCCIONES MORENO S.R.L.','20600773365','AV. UNIVERSITARIA MZA. A LOTE. 7 A.V. LAS MERCEDES DE CARABAYLLO LIMA - LIMA - CARABAYLLO','',NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'VENTAS1','2019-10-14 15:59:11','ip-172-31-81-196',NULL,'2019-10-14 15:59:11',NULL,''),('004','00072','C','J MULTICAMPS S.A.C.','20552246927','AV. ARGENTINA NRO. 215 INT. AX 7 (CENTRO COMERCIAL NICOLINI) LIMA - LIMA - LIMA','',NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'VENTAS','2019-10-15 17:15:21','ip-172-31-81-196',NULL,'2019-10-15 17:15:21',NULL,''),('004','00073','C','CONSTRUCTEC RYD S.A.C.','20600490924','JR. YAUYOS NRO. 219 URB. EL CARMEN (ALT.KM.12 DE AV.TUPAC AMARU) LIMA - LIMA - COMAS','',NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'VENTAS','2019-10-16 10:53:41','ip-172-31-81-196',NULL,'2019-10-16 10:53:41',NULL,''),('004','00074','C','W & R EFICIENCIA DE SERVICIOS SAC','20502980603','CAL.LAS HIEDRAS NRO. 170 (URB.LAS VIOLETAS(PARAD.TRIANGULO-T.AMARU) LIMA - LIMA - INDEPENDENCIA','',NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'VENTAS','2019-10-16 15:17:44','ip-172-31-81-196',NULL,'2019-10-16 15:17:44',NULL,''),('004','00075','C','INMOBILIARIA CONSTRUCTORA TAIPE S.A.C.','20502902637','AV. TUPAC AMARU NRO. 1636 DPTO. 201 URB. RAUL PORRAS BARRENECHEA (FRENTE AL CONCEJO DE CARABAYLLO) LIMA - LIMA - CARABAYLLO','',NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'VENTAS1','2019-10-16 15:47:00','ip-172-31-81-196',NULL,'2019-10-16 15:47:00',NULL,''),('004','00076','C','SILVA SANTOS RAUL JUSTO','10152861643','-','',NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'VENTAS1','2019-10-16 16:00:09','ip-172-31-81-196',NULL,'2019-10-16 16:00:09',NULL,''),('004','00077','C','NEGOCIACIONES & SERVICIOS SANTIAGO S.R.L.','20602857086','AV. UNIVERSITARIA NORTE MZA. P1 LOTE. 27 URB. PRIMAVERA (FTE A PUERTA DE PARQUE ZONAL SINCHI ROCA) LIMA - LIMA - COMAS','',NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'VENTAS','2019-10-17 10:37:27','ip-172-31-81-196',NULL,'2019-10-17 10:37:27',NULL,''),('004','00078','C','ACEROS Y MULTISERVICIOS J & M S.A.C.','20551132898','AV. TUPAC AMARU NRO. 6258 URB. REPARTICION LIMA - LIMA - COMAS','',NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'VENTAS','2019-10-18 11:26:07','ip-172-31-81-196',NULL,'2019-10-18 11:26:07',NULL,''),('004','00079','C','RODAMETALES SOCIEDAD ANONIMA CERRADA - RODAMETALES S.A.C.','20565269276','JR. CAHUIDE NRO. 1047 P.J. EL CARMEN (ALT CRUCE AV BELAUNDE CON AV TUPAC AMARU) LIMA - LIMA - COMAS','',NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'VENTAS','2019-10-18 17:16:48','ip-172-31-81-196',NULL,'2019-10-18 17:16:48',NULL,''),('004','00080','C','S & F LUVASS AIR E.I.R.L.','20603865741','AV. PERIURBANA MZA. J LOTE. 5 VILLA CLUB 4 LIMA - LIMA - CARABAYLLO','',NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'VENTAS','2019-10-19 12:10:52','ip-172-31-81-196',NULL,'2019-10-19 12:10:52',NULL,''),('004','00081','C','PRODUCTOS DE SEGURIDAD INDUSTRIAL TAPIA SOCIEDAD ANONIMA CERRADA - PROSIT S.A.C.','20601306621','JR. CORONEL MIGUEL ZAMORA NRO. 180 URB. LIMA INDUSTRIAL LIMA - LIMA - LIMA','',NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'VENTAS1','2019-10-21 17:54:53','ip-172-31-81-196',NULL,'2019-10-21 17:54:53',NULL,''),('004','00082','C','TIERRO ARQUITECTURA DEL DESARROLLO S.A.C.','20492846598','JR. LAS ORQUIDEAS NRO. 101 COO. SANTA ROSA DE QUIVES (ALTURA DE FABRICA BASA) LIMA - LIMA - SANTA ANITA','',NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'VENTAS','2019-10-22 08:19:48','ip-172-31-81-196',NULL,'2019-10-22 08:19:48',NULL,''),('004','00083','C','M & G INGENIERIA ELECTRICA S.A.C.','20602473563','JR. HUAROCHIRI NRO. 574 DPTO. E29 CERCADO DE LIMA (C.C PLAZA FERRETERO -1CDRA PLAZA 2 MAYO) LIMA - LIMA - LIMA','',NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'VENTAS1','2019-10-22 09:18:50','ip-172-31-81-196',NULL,'2019-10-22 09:18:50',NULL,''),('004','00084','C','JUAN & RUCOBA MINING S.A.C.-J & R MINING S.A.C.','20601350981','MZA. G LOTE. 12 URB. LAS TERRAZAS DE COMAS LIMA - LIMA - COMAS','',NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'VENTAS1','2019-10-22 15:26:34','ip-172-31-81-196',NULL,'2019-10-22 15:26:34',NULL,''),('004','00085','C','AGUIRRE ANCHANTE CARMEN VICTORIA','10766284090','-','',NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'VENTAS','2019-10-24 11:54:44','ip-172-31-81-196',NULL,'2019-10-24 11:54:44',NULL,''),('004','00086','C','CUSTODIO CUSTODIO VICTOR MANUEL','10097304496','-','',NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'VENTAS','2019-10-28 11:49:12','ip-172-31-81-196',NULL,'2019-10-28 11:49:12',NULL,''),('004','00087','C','CUSTODIO CUSTODIO VICTOR MANUEL','10097304501','jiron brescia 413 comas lima lima',NULL,NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'VENTAS','2019-10-28 11:49:52','ip-172-31-81-196','VENTAS','2019-10-28 11:55:36','ip-172-31-81-196',''),('004','00088','C','FUENTES GODOY JOSE ANTONIO','10069078490','jr 12 de octubre 148 el carmen comas','',NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'VENTAS','2019-10-29 10:07:40','ip-172-31-81-196',NULL,'2019-10-29 10:07:40',NULL,''),('004','00089','C','SJ INGENIEROS S.R.L.','20454839243','CAL.ANDIA NRO. 216 URB. CERCADO DE MARIANO MELGAR AREQUIPA - AREQUIPA - MARIANO MELGAR','',NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'VENTAS1','2019-10-30 08:29:01','ip-172-31-81-196',NULL,'2019-10-30 08:29:01',NULL,''),('004','00090','C','INSTALS DRYWALL SERVICE S.A.C. - SERVDRYINSTAL S.A.C.','20550974453','MZA. A LOTE. 18 ASOC. DE VIVI. CARIBE II (2DO PISO) LIMA - LIMA - SAN MARTIN DE PORRES','',NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'VENTAS1','2019-10-30 09:55:59','ip-172-31-81-196',NULL,'2019-10-30 09:55:59',NULL,''),('004','00091','C','CENTRO SAN FRANCISCO DE LACHAQUI','20160705214','CAL.VESTA NRO. 383 LIMA - LIMA - RIMAC','',NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'VENTAS','2019-11-02 08:28:12','ip-172-31-81-196',NULL,'2019-11-02 08:28:12',NULL,''),('004','00092','C','INVERSIONES CAMBRI SAC','20524617740','MZA. L LOTE. 2 A.H. CAHUIDE INDEPENDENCIA (ALT COMISARIA DE PAYET) LIMA - LIMA - INDEPENDENCIA','',NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'VENTAS','2019-11-06 15:42:37','ip-172-31-81-196',NULL,'2019-11-06 15:42:37',NULL,''),('004','00093','C','AYALA PAUCAR LUIS ANGEL','10476464001','jr. santa rosa lt. 02 dpto p2 mzj lima lima comas','',NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'VENTAS','2019-11-07 08:48:26','ip-172-31-81-196',NULL,'2019-11-07 08:48:26',NULL,''),('004','00094','C','LLAJARUNA VALDEZ JUAN DOMINGO','10090334234','prolongación pezet  # 318 retablo , comas lima','',NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'VENTAS','2019-11-07 09:51:34','ip-172-31-81-196',NULL,'2019-11-07 09:51:34',NULL,''),('004','00095','C','ORTOIMPLANT DENTAL S.A.C.','20604558973','AV. UNIVERSITARIA NRO. 7510 URB. EL RETABLO LIMA - LIMA - COMAS','',NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'VENTAS','2019-11-07 12:12:11','ip-172-31-81-196',NULL,'2019-11-07 12:12:11',NULL,''),('004','00096','C','PAREDES CASTAÑEDA CESAR JACINTO','10717657841','-','',NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'VENTAS1','2019-11-11 10:38:08','ip-172-31-81-196',NULL,'2019-11-11 10:38:08',NULL,''),('004','00097','C','IEP CRAMEX  E.I.R.L.','20518794907','----PARCELA NRO. 285 CASA HUERTA EL CARMEN (KM 23 AV T.AMARU-PARAD TORRE BLANCA) LIMA - LIMA - CARABAYLLO','',NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'VENTAS1','2019-11-11 16:48:57','ip-172-31-81-196',NULL,'2019-11-11 16:48:57',NULL,''),('004','00098','C','ORTIZ VILLANUEVA JOEL','10424609167','-','',NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'VENTAS1','2019-11-13 11:46:21','ip-172-31-81-196',NULL,'2019-11-13 11:46:21',NULL,''),('004','00099','C','CORDOVA VASQUEZ JUAN JULIO','10068223496','-','',NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'VENTAS1','2019-11-14 10:00:26','ip-172-31-81-196',NULL,'2019-11-14 10:00:26',NULL,''),('004','00100','C','EXPOSISTEMAS SERVICIOS SAC','20510264542','AV. JAVIER PRADO ESTE INTERSECCION CON CARRETERA PANAMERICANA S/N NRO. S/N PUERTA 1 HIPODROMO DE MONTERRICO PARCELA I (INTE,CARRETERA PANM SUR,P I HIPODROMO M.) LIMA - LIMA - SANTIAGO DE SURCO','',NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'VENTAS','2019-11-14 15:18:13','ip-172-31-81-196',NULL,'2019-11-14 15:18:13',NULL,''),('004','00101','C','INGENIERIA MECANICA SERVICE INDUSTRIAL S.R.L','20536245376','CAL.CAYRUCACHI NRO. 369 URB. MARANGA ET. CINCO LIMA - LIMA - SAN MIGUEL','',NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'VENTAS','2019-11-15 13:36:59','ip-172-31-81-196',NULL,'2019-11-15 13:36:59',NULL,''),('004','00102','C','HERNANDEZ CHUNGA RENZO SAMUEL','10108755381','-','',NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'VENTAS','2019-11-20 10:21:57','ip-172-31-81-196',NULL,'2019-11-20 10:21:57',NULL,''),('004','00103','C','SPEED LIFT S.A.C.','20523122212','CAL.GONZALO PIZARRO NRO. 528 A.H. EL CARMEN LIMA - LIMA - COMAS','',NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'VENTAS','2019-11-20 11:51:41','ip-172-31-81-196',NULL,'2019-11-20 11:51:41',NULL,''),('004','00104','C','SPEED LIFT S.A.C.','20523122216','CAL.GONZALO PIZARRO NRO. 528 A.H. EL CARMEN LIMA - LIMA - COMAS','',NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'VENTAS','2019-11-20 11:52:13','ip-172-31-81-196',NULL,'2019-11-20 11:52:13',NULL,''),('004','00105','C','NAVARRO AQUINO & CIA S.A.C.','20601115957','MZA. A LOTE. 20 C.P. PUNCHAUCA LIMA - LIMA - CARABAYLLO','',NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'VENTAS1','2019-11-21 13:25:02','ip-172-31-81-196',NULL,'2019-11-21 13:25:02',NULL,''),('004','00106','C','ROSALES RAMIREZ KATIA LISET','10463529758','AA,HH CERRO CALVARIO MZ.L LOTE 6 COMAS','',NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'VENTAS','2019-11-27 16:30:49','ip-172-31-81-196',NULL,'2019-11-27 16:30:49',NULL,''),('004','00107','C','M & D CONTRATISTAS  GENERALES S.A.C','20523874340','CAL.32 MZA. P1 LOTE. 29 URB. VIPOL- 2DA ETAPA LIMA - LIMA - COMAS','',NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'VENTAS1','2019-11-28 10:11:12','ip-172-31-81-196',NULL,'2019-11-28 10:11:12',NULL,''),('004','00108','C','GPRADA S.A.C.','20600005554','CAL.51 MZA. HH5 LOTE. 14 URB. PRO ETP 5 (2 CDRAS DE LA 1RA ENTRADA DE PRO) LIMA - LIMA - LOS OLIVOS','',NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'VENTAS','2019-11-30 08:49:04','ip-172-31-81-196',NULL,'2019-11-30 08:49:04',NULL,''),('004','00109','C','juan carlos iunbato hidalgo','44156288',NULL,'',NULL,'',NULL,NULL,NULL,'1OFI','0100','1',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1',NULL,NULL,'VENTAS','2019-12-02 12:09:43','ip-172-31-81-196',NULL,'2019-12-02 12:09:43',NULL,''),('004','00110','C','pedro cribillero zaes','43428425','as.prq.las brisas carabayllo-lima-lima','',NULL,'',NULL,NULL,NULL,'1OFI','0100','1',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1',NULL,NULL,'VENTAS1','2019-12-03 10:26:33','ip-172-31-81-196',NULL,'2019-12-03 10:26:33',NULL,''),('004','00111','C','GOMEZ CONDORCHOA AVELINO','10444411452','-','',NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'VENTAS1','2019-12-06 11:33:25','ip-172-31-81-196',NULL,'2019-12-06 11:33:25',NULL,''),('004','00112','C','QUIJANO TAMARIZ FERNANDO ALEJANDRO','10316166429','-','',NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'VENTAS1','2019-12-09 15:40:43','ip-172-31-81-196',NULL,'2019-12-09 15:40:43',NULL,''),('004','00113','C','PAREDES CASTAÑEDA CRISTHIAN PEDRO','10717657867','-','',NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'VENTAS1','2019-12-09 15:43:04','ip-172-31-81-196',NULL,'2019-12-09 15:43:04',NULL,''),('004','00114','C','SOCIEDAD MINERA DE RESPONSABILIDAD LIMITADA COAL MINE','20512306714','CAL.PEDRO DE LA GASCA NRO. 114 P.J. EL CARMEN (ALT KM DE LA AV TUPAC AMARU.) LIMA - LIMA - COMAS','',NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'VENTAS','2019-12-10 09:01:50','ip-172-31-81-196',NULL,'2019-12-10 09:01:50',NULL,''),('004','00115','C','TORRES GRANADOS HIBERNON','10069350165','-','',NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'VENTAS','2019-12-11 09:19:01','ip-172-31-81-196',NULL,'2019-12-11 09:19:01',NULL,''),('004','00116','C','ASOC TRAB IND DEL CAR KM 13-14 ATICCA','20251955825','AV. PROLONGACION VISTA ALEGRE NRO. J-2 INT. 03 P.J. EL CARMEN (ENTRADA CEMENTERIO CARMEN ALTO COMAS) LIMA - LIMA - COMAS','',NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'VENTAS','2019-12-11 11:06:48','ip-172-31-81-196',NULL,'2019-12-11 11:06:48',NULL,''),('004','00117','C','INMOBILIARIA INVERSIONES CONTRATISTAS SAN SEBASTIAN SOCIEDAD ANONIMA CERRADA','20512354280','AV. ANTUNEZ DE MAYOLO NRO. 1161 URB. COVIDA (4TO PISO FRENTE AL MERCADO DE COVIDA) LIMA - LIMA - LOS OLIVOS','',NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'VENTAS1','2019-12-11 14:35:04','ip-172-31-81-196',NULL,'2019-12-11 14:35:04',NULL,''),('004','00118','C','CASTAÑEDA POMA JAVIER ELMER','10093272591','-','',NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'VENTAS1','2019-12-11 14:55:49','ip-172-31-81-196',NULL,'2019-12-11 14:55:49',NULL,''),('004','00119','C','sumus servi s.a.c','20603042965','av tupac amaru #3887 comas','',NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'VENTAS','2019-12-12 15:32:25','ip-172-31-81-196',NULL,'2019-12-12 15:32:25',NULL,''),('004','00120','C','SANTOS PEÑA JOEL ROMALDO','10478627055','apv naranjal manzana m lote 16 san martin de porras','',NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'VENTAS','2019-12-13 09:39:58','ip-172-31-81-196',NULL,'2019-12-13 09:39:58',NULL,''),('004','00121','C','CORPORACION DEXCON PERU SAC','20604777993','MZA. O2 LOTE. 15 SAN DIEGO VIPOL (PARQUE LAS CANTUARIAS) LIMA - LIMA - SAN MARTIN DE PORRES','',NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'VENTAS1','2019-12-14 08:18:25','ip-172-31-81-196',NULL,'2019-12-14 08:18:25',NULL,''),('004','00122','C','RGA INVERSIONES Y PROYECTOS PERU SOCIEDAD ANONIMA CERRADA','20555284030','AV. JOSE DE LA TORRE UGARTE NRO. 185 URB. EL RETABLO (ALT BOULEVARD DE RETABLO) LIMA - LIMA - COMAS','',NULL,'',NULL,NULL,NULL,'1OFI','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'VENTAS','2019-12-14 11:54:16','ip-172-31-81-196',NULL,'2019-12-14 11:54:16',NULL,''),('004','00123','C','MURO GUERRERO CONRADO','06946290',NULL,'',NULL,'',NULL,NULL,NULL,'1OFI','0100','1',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1',NULL,NULL,'VENTAS','2019-12-14 12:43:50','ip-172-31-81-196',NULL,'2019-12-14 12:43:50',NULL,''),('005','00002','C','CORPORACION SAINFO E.I.R.L. - SAINFO E.I.R.L.','20604067899','CAL.9 MZA. Y LOTE. 31 A.H. 1 DE JUNIO (COMERCIAL ALEMANA) LIMA - LIMA - SAN JUAN DE MIRAFLORES','',NULL,'',NULL,NULL,NULL,'OFIC','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'GIROSELF','2019-11-02 16:14:53','ip-172-31-81-196',NULL,'2019-11-02 16:14:53',NULL,''),('005','00003','C','CLIENTE','.',NULL,'',NULL,'',NULL,NULL,NULL,'1OFI','0100','0',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'0',NULL,NULL,'FONSECA','2019-11-02 16:16:53','ip-172-31-81-196',NULL,'2019-11-02 16:16:53',NULL,''),('005','00004','C','SALAZAR GONZALEZ MANUEL ANTONIO','15604294123','-','',NULL,'',NULL,NULL,NULL,'OFIC','0100','6',NULL,'01',0,10,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'6',NULL,NULL,'GIROSELF','2019-11-04 10:37:05','ip-172-31-81-196',NULL,'2019-11-04 10:37:05',NULL,''),('005','00005','C','NUBIA TOVAR','70093060',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-05 19:55:27',NULL,NULL,'2019-11-05 19:55:27',NULL,NULL),('005','00006','C','VANNESSA LQ','18926638',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-05 19:55:27',NULL,NULL,'2019-11-05 19:55:27',NULL,NULL),('005','00007','C','REINALDO ANDY','24107461',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-05 19:55:27',NULL,NULL,'2019-11-05 19:55:27',NULL,NULL),('005','00008','C','JHONATTAN MILLAN','15556917',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-05 19:55:27',NULL,NULL,'2019-11-05 19:55:27',NULL,NULL),('005','00009','C','ALEJANDRO JAIME','19501045',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-05 19:55:28',NULL,NULL,'2019-11-05 19:55:28',NULL,NULL),('005','00010','C','GHIN ','17691261',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-05 19:55:28',NULL,NULL,'2019-11-05 19:55:28',NULL,NULL),('005','00011','C','CHRISTIAN MARTINEZ','41200375',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-05 19:55:28',NULL,NULL,'2019-11-05 19:55:28',NULL,NULL),('005','00012','C','JESUS VILLALOBOS','19408331',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-05 19:55:29',NULL,NULL,'2019-11-05 19:55:29',NULL,NULL),('005','00013','C','MARIA GUARAMATA','13694345',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-05 19:55:29',NULL,NULL,'2019-11-05 19:55:29',NULL,NULL),('005','00014','C','RUNNY TERAN','16558965',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-05 19:55:29',NULL,NULL,'2019-11-05 19:55:29',NULL,NULL),('005','00015','C','REINALDO ANDY','20537088',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-05 19:55:29',NULL,NULL,'2019-11-05 19:55:29',NULL,NULL),('005','00016','C','GUSTAVO ESCOBAR','15703469',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-05 19:55:30',NULL,NULL,'2019-11-05 19:55:30',NULL,NULL),('005','00017','C','NUBIA TOVAR','58028930',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-08 09:49:12',NULL,NULL,'2019-11-08 09:49:12',NULL,NULL),('005','00018','C','VANNESSA LQ','11088174',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-08 09:49:12',NULL,NULL,'2019-11-08 09:49:12',NULL,NULL),('005','00019','C','REINALDO ANDY','40345780',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-08 09:49:12',NULL,NULL,'2019-11-08 09:49:12',NULL,NULL),('005','00020','C','JHONATTAN MILLAN','10970180',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-08 09:49:13',NULL,NULL,'2019-11-08 09:49:13',NULL,NULL),('005','00021','C','ALEJANDRO JAIME','18199595',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-08 09:49:13',NULL,NULL,'2019-11-08 09:49:13',NULL,NULL),('005','00022','C','OSWALDO HERNANDEZ','19083238',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-08 09:49:13',NULL,NULL,'2019-11-08 09:49:13',NULL,NULL),('005','00023','C','RAMSES IBARRA','17239460',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-08 09:49:13',NULL,NULL,'2019-11-08 09:49:13',NULL,NULL),('005','00024','C','KAREN BRAYAIN','21063771',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-08 09:49:14',NULL,NULL,'2019-11-08 09:49:14',NULL,NULL),('005','00025','C','KELLY ESTRADA','17925682',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-08 09:49:14',NULL,NULL,'2019-11-08 09:49:14',NULL,NULL),('005','00026','C','YORDELYS CARABALLO','82169480',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-08 17:42:19',NULL,NULL,'2019-11-08 17:42:19',NULL,NULL),('005','00027','C','MAXIMILIANO FERNANDEZ','54741140',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-08 17:42:19',NULL,NULL,'2019-11-08 17:42:19',NULL,NULL),('005','00028','C','NERY','17255500',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-08 17:42:19',NULL,NULL,'2019-11-08 17:42:19',NULL,NULL),('005','00029','C','MARIA DE LOS ANGELES','17363821','AV GUILLERMO DE LA FUENTE  # 207 COMAS',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-08 17:42:19',NULL,'VENTAS','2019-11-11 16:39:59','ip-172-31-81-196',NULL),('005','00030','C','VANESSA CUBERO','26906355',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-08 17:42:20',NULL,NULL,'2019-11-08 17:42:20',NULL,NULL),('005','00031','C','ROSIBEL LAYA','58714030',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-08 17:42:20',NULL,NULL,'2019-11-08 17:42:20',NULL,NULL),('005','00032','C','JOSE TAMARA','12814169',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-08 17:42:20',NULL,NULL,'2019-11-08 17:42:20',NULL,NULL),('005','00033','C','MARIBEL MARBERA','26537630',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-08 17:42:21',NULL,NULL,'2019-11-08 17:42:21',NULL,NULL),('005','00034','C','JOSE TAMARA','91843990',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-08 17:42:21',NULL,NULL,'2019-11-08 17:42:21',NULL,NULL),('005','00035','C','ROSMARTIN MORALES','14263964',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-08 17:42:21',NULL,NULL,'2019-11-08 17:42:21',NULL,NULL),('005','00036','C','CARLOS ARIAS','98044850',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-09 19:57:50',NULL,NULL,'2019-11-09 19:57:50',NULL,NULL),('005','00037','C','CAROLINA HUMANIDAD','16872453',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-09 19:57:51',NULL,NULL,'2019-11-09 19:57:51',NULL,NULL),('005','00038','C','RONNY RODOLFO','16106989',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-09 19:57:51',NULL,NULL,'2019-11-09 19:57:51',NULL,NULL),('005','00039','C','YAMILETH BRACHO','19251626',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-09 19:57:51',NULL,NULL,'2019-11-09 19:57:51',NULL,NULL),('005','00040','C','MARIBEL MARBERA','18889084',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-09 19:57:51',NULL,NULL,'2019-11-09 19:57:51',NULL,NULL),('005','00041','C','HECTOR COCO','16701334',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-09 19:57:52',NULL,NULL,'2019-11-09 19:57:52',NULL,NULL),('005','00042','C','NERY','22890374',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-13 16:18:10',NULL,NULL,'2019-11-13 16:18:10',NULL,NULL),('005','00043','C','VANESSA CUBEROS','12376562',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-13 16:18:10',NULL,NULL,'2019-11-13 16:18:10',NULL,NULL),('005','00044','C','YVAN','81309880',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-13 16:18:11',NULL,NULL,'2019-11-13 16:18:11',NULL,NULL),('005','00045','C','RUTH MELEAN','19230944',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-13 16:18:11',NULL,NULL,'2019-11-13 16:18:11',NULL,NULL),('005','00046','C','DIANA CARVAJAL','16074013',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-13 16:18:11',NULL,NULL,'2019-11-13 16:18:11',NULL,NULL),('005','00047','C','SOFIA','68889980',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-13 16:18:12',NULL,NULL,'2019-11-13 16:18:12',NULL,NULL),('005','00048','C','PEDRO ARAUJO','19324047',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-13 16:18:12',NULL,NULL,'2019-11-13 16:18:12',NULL,NULL),('005','00049','C','ANGEL CUARE','26706009',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-13 16:18:12',NULL,NULL,'2019-11-13 16:18:12',NULL,NULL),('005','00050','C','FRANK MILLAN','14221327',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-13 16:18:12',NULL,NULL,'2019-11-13 16:18:12',NULL,NULL),('005','00051','C','DAINELYS','14107101',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-13 16:18:13',NULL,NULL,'2019-11-13 16:18:13',NULL,NULL),('005','00052','C','MAXIMILIANO FERNANDEZ','15005204',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-13 16:18:13',NULL,NULL,'2019-11-13 16:18:13',NULL,NULL),('005','00053','C','VANESSA CUBEROS','61929070',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-13 16:18:14',NULL,NULL,'2019-11-13 16:18:14',NULL,NULL),('005','00054','C','EDGAR PEÑALVER','26163387',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-13 16:18:14',NULL,NULL,'2019-11-13 16:18:14',NULL,NULL),('005','00055','C','MINERVA ORIANA','24571639',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-14 20:05:33',NULL,NULL,'2019-11-14 20:05:33',NULL,NULL),('005','00056','C','BESALIA SEQUERA','12059660',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-14 20:05:33',NULL,NULL,'2019-11-14 20:05:33',NULL,NULL),('005','00057','C','WLADIMIR LQ','10880528',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-14 20:05:33',NULL,NULL,'2019-11-14 20:05:33',NULL,NULL),('005','00058','C','YESENIA UDO','16496771',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-14 20:05:33',NULL,NULL,'2019-11-14 20:05:33',NULL,NULL),('005','00059','C','CARLOS LUJO','15917993',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-14 20:05:34',NULL,NULL,'2019-11-14 20:05:34',NULL,NULL),('005','00060','C','ANNY AQUINO','29650740',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-14 20:05:34',NULL,NULL,'2019-11-14 20:05:34',NULL,NULL),('005','00061','C','MARIANNYS RODRIGUEZ','42117890',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-16 20:18:00',NULL,NULL,'2019-11-16 20:18:00',NULL,NULL),('005','00062','C','ANGELA LUIS','61169320',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-16 20:18:00',NULL,NULL,'2019-11-16 20:18:00',NULL,NULL),('005','00063','C','GABRIELA CHINA','15423346',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-16 20:18:01',NULL,NULL,'2019-11-16 20:18:01',NULL,NULL),('005','00064','C','KAREN BRAYAN','10912599',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-16 20:18:01',NULL,NULL,'2019-11-16 20:18:01',NULL,NULL),('005','00065','C','SIMON PEREZ','16685915',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-16 20:18:02',NULL,NULL,'2019-11-16 20:18:02',NULL,NULL),('005','00066','C','CARLOS ARIAS','25333623',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-19 20:23:28',NULL,NULL,'2019-11-19 20:23:28',NULL,NULL),('005','00067','C','EDGAR PEÑALVER','18939574',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-19 20:23:28',NULL,NULL,'2019-11-19 20:23:28',NULL,NULL),('005','00068','C','ELIAS ARIAS','48405810',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-19 20:23:29',NULL,NULL,'2019-11-19 20:23:29',NULL,NULL),('005','00069','C','FRANCISCO RODRIGUEZ','18510198',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-19 20:23:29',NULL,NULL,'2019-11-19 20:23:29',NULL,NULL),('005','00070','C','ROBERT RIVAS','12033070',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-19 20:23:30',NULL,NULL,'2019-11-19 20:23:30',NULL,NULL),('005','00071','C','EDGAR PEÑALVER','26005929',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-19 20:23:30',NULL,NULL,'2019-11-19 20:23:30',NULL,NULL),('005','00072','C','SOFIA ','12716134',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-19 20:23:31',NULL,NULL,'2019-11-19 20:23:31',NULL,NULL),('005','00073','C','RONNY RODOLFO','20491212',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-19 20:23:31',NULL,NULL,'2019-11-19 20:23:31',NULL,NULL),('005','00074','C','PEDRO ARAUJO','21060966',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-21 19:51:41',NULL,NULL,'2019-11-21 19:51:41',NULL,NULL),('005','00075','C','DARIAGNA CARRILLO','11545161',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-21 19:51:42',NULL,NULL,'2019-11-21 19:51:42',NULL,NULL),('005','00076','C','ANICLEIDYS WLADIMIR','19190049',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-21 19:51:42',NULL,NULL,'2019-11-21 19:51:42',NULL,NULL),('005','00077','C','VIVIAN','20035199',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-21 19:51:42',NULL,NULL,'2019-11-21 19:51:42',NULL,NULL),('005','00078','C','GUSTAVO ESCOBAR','74826670',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-21 19:51:43',NULL,NULL,'2019-11-21 19:51:43',NULL,NULL),('005','00079','C','EDGAR GARCIA','72538700',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-23 20:35:11',NULL,NULL,'2019-11-23 20:35:11',NULL,NULL),('005','00080','C','WLADIMIR LQ','83826530',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-23 20:35:11',NULL,NULL,'2019-11-23 20:35:11',NULL,NULL),('005','00081','C','YINMAR ACOSTA','13748330',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-26 20:28:36',NULL,NULL,'2019-11-26 20:28:36',NULL,NULL),('005','00082','C','PEDRO ARAUJO','80576570',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-26 20:28:36',NULL,NULL,'2019-11-26 20:28:36',NULL,NULL),('005','00083','C','MIGDALIA','99480410',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-26 20:28:37',NULL,NULL,'2019-11-26 20:28:37',NULL,NULL),('005','00084','C','ALEXIA','19897598',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-26 20:28:37',NULL,NULL,'2019-11-26 20:28:37',NULL,NULL),('005','00085','C','ALFREDO DORIA','17203268',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-26 20:28:37',NULL,NULL,'2019-11-26 20:28:37',NULL,NULL),('005','00086','C','GABRIEL PEREZ','12145894',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-26 20:28:37',NULL,NULL,'2019-11-26 20:28:37',NULL,NULL),('005','00087','C','ANDREI A','22556002',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-26 20:28:38',NULL,NULL,'2019-11-26 20:28:38',NULL,NULL),('005','00088','C','LUIS NORIEGA','16545049',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-26 20:28:38',NULL,NULL,'2019-11-26 20:28:38',NULL,NULL),('005','00089','C','GUSTAVO ESCOBAR','25551506',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-28 20:11:24',NULL,NULL,'2019-11-28 20:11:24',NULL,NULL),('005','00090','C','DANIELA PARRA','97676350',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-28 20:11:25',NULL,NULL,'2019-11-28 20:11:25',NULL,NULL),('005','00091','C','JHONATHAN MILLAN','14971620',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-28 20:11:25',NULL,NULL,'2019-11-28 20:11:25',NULL,NULL),('005','00092','C','YVAN','10287777',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-28 20:11:25',NULL,NULL,'2019-11-28 20:11:25',NULL,NULL),('005','00093','C','YVAN','93053290',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-11-28 20:11:26',NULL,NULL,'2019-11-28 20:11:26',NULL,NULL),('005','00094','C','FRANCISCO RODRIGUEZ','21080773',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-12-03 09:30:29',NULL,NULL,'2019-12-03 09:30:29',NULL,NULL),('005','00095','C','EDWARD VILLEGAS','11336094',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-12-03 09:30:30',NULL,NULL,'2019-12-03 09:30:30',NULL,NULL),('005','00096','C','GABRIELA CHINA','80453518',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-12-03 09:30:30',NULL,NULL,'2019-12-03 09:30:30',NULL,NULL),('005','00097','C','GABRIELA CHINA','12224677',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-12-03 09:30:30',NULL,NULL,'2019-12-03 09:30:30',NULL,NULL),('005','00098','C','DARIAGNA CARRILLO','19433906',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-12-03 09:30:30',NULL,NULL,'2019-12-03 09:30:30',NULL,NULL),('005','00099','C','VANNESA CUBERO','14141161',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-12-03 09:30:31',NULL,NULL,'2019-12-03 09:30:31',NULL,NULL),('005','00100','C','GUSTAVO ESCOBAR','12182490',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-12-03 19:17:36',NULL,NULL,'2019-12-03 19:17:36',NULL,NULL),('005','00101','C','CAROLINA HUMANIDAD','10694434',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-12-03 19:17:37',NULL,NULL,'2019-12-03 19:17:37',NULL,NULL),('005','00102','C','JHONATHAN MILLAN','21379165',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-12-03 19:17:38',NULL,NULL,'2019-12-03 19:17:38',NULL,NULL),('005','00103','C','YOROIMA MARIN','20110953',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-12-03 19:17:38',NULL,NULL,'2019-12-03 19:17:38',NULL,NULL),('005','00104','C','HECTOR GUIPE','17213783',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-12-03 19:17:38',NULL,NULL,'2019-12-03 19:17:38',NULL,NULL),('005','00105','C','HECTOR GUIPE','24876241',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-12-03 19:17:39',NULL,NULL,'2019-12-03 19:17:39',NULL,NULL),('005','00106','C','ANDREINA CANACHE','44361870',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-12-05 20:11:33',NULL,NULL,'2019-12-05 20:11:33',NULL,NULL),('005','00107','C','GABRIELA MANTURANO','23551883',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-12-05 20:11:34',NULL,NULL,'2019-12-05 20:11:34',NULL,NULL),('005','00108','C','MARIA OSE BARRIO','10672950',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-12-05 20:11:35',NULL,NULL,'2019-12-05 20:11:35',NULL,NULL),('005','00109','C','CELYS','10884498',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-12-05 20:11:35',NULL,NULL,'2019-12-05 20:11:35',NULL,NULL),('005','00110','C','VIRMA GONZALEZ','14347527',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-12-05 20:11:35',NULL,NULL,'2019-12-05 20:11:35',NULL,NULL),('005','00111','C','MARBELYS LOPEZ','26565955',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-12-09 18:13:45',NULL,NULL,'2019-12-09 18:13:45',NULL,NULL),('005','00112','C','LUCAS PETER','15504416',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-12-09 18:13:46',NULL,NULL,'2019-12-09 18:13:46',NULL,NULL),('005','00113','C','NEISKY GUSMAN','10343634',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-12-09 18:13:46',NULL,NULL,'2019-12-09 18:13:46',NULL,NULL),('005','00114','C','ANYI','71820790',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-12-09 18:13:46',NULL,NULL,'2019-12-09 18:13:46',NULL,NULL),('005','00115','C','RONNY RODOLFO','41183230',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-12-09 18:13:47',NULL,NULL,'2019-12-09 18:13:47',NULL,NULL),('005','00116','C','VANESSA CUBEROS','12831465',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-12-09 18:13:47',NULL,NULL,'2019-12-09 18:13:47',NULL,NULL),('005','00117','C','ALFREDO DORIA','17004465',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-12-09 18:13:48',NULL,NULL,'2019-12-09 18:13:48',NULL,NULL),('005','00118','C','MARIANA CAMPOS','24106517',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-12-09 18:13:48',NULL,NULL,'2019-12-09 18:13:48',NULL,NULL),('005','00119','C','VIVIAN','20918833',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-12-10 20:14:41',NULL,NULL,'2019-12-10 20:14:41',NULL,NULL),('005','00120','C','MARBELYS LOPEZ','24581789',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-12-10 20:14:42',NULL,NULL,'2019-12-10 20:14:42',NULL,NULL),('005','00121','C','VICENTE NAVA','16335609',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-12-10 20:14:43',NULL,NULL,'2019-12-10 20:14:43',NULL,NULL),('005','00122','C','VANESSA CUBERO','17719934',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-12-10 20:14:43',NULL,NULL,'2019-12-10 20:14:43',NULL,NULL),('005','00123','C','EMMANUEL RAMIREZ','27005109',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-12-10 20:14:44',NULL,NULL,'2019-12-10 20:14:44',NULL,NULL),('005','00124','C','DARIAGNA CARRILLO','11345879',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-12-12 20:51:00',NULL,NULL,'2019-12-12 20:51:00',NULL,NULL),('005','00125','C','ELVIS RIVERO','17485917',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-12-12 20:51:01',NULL,NULL,'2019-12-12 20:51:01',NULL,NULL),('005','00126','C','ELVIS RIVERO','13821929',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-12-12 20:51:01',NULL,NULL,'2019-12-12 20:51:01',NULL,NULL),('005','00127','C','KELLY ESTRADA','33319100',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-12-12 20:51:02',NULL,NULL,'2019-12-12 20:51:02',NULL,NULL),('005','00128','C','LUISANA','20644361',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-12-12 20:51:03',NULL,NULL,'2019-12-12 20:51:03',NULL,NULL),('005','00129','C','YAMILETH BRACHO','16102188',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-12-16 16:30:31',NULL,NULL,'2019-12-16 16:30:31',NULL,NULL),('005','00130','C','ANDRES MARIANA','14686913',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-12-16 16:30:32',NULL,NULL,'2019-12-16 16:30:32',NULL,NULL),('005','00131','C','NEISKY GUZMAN','88247150',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-12-16 16:30:32',NULL,NULL,'2019-12-16 16:30:32',NULL,NULL),('005','00132','C','LISBETH REQUEZ','27175976',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-12-16 16:30:33',NULL,NULL,'2019-12-16 16:30:33',NULL,NULL),('005','00133','C','YOHEMAR QUINTERO','18949247',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-12-16 16:30:33',NULL,NULL,'2019-12-16 16:30:33',NULL,NULL),('005','00134','C','RONNY RODOLFO','68668440',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-12-16 16:30:33',NULL,NULL,'2019-12-16 16:30:33',NULL,NULL),('005','00135','C','HILLARY BETANCOURT','13201991',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-12-16 16:30:34',NULL,NULL,'2019-12-16 16:30:34',NULL,NULL),('005','00136','C','HILLARY BETANCOURT','48359480',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-12-16 16:30:34',NULL,NULL,'2019-12-16 16:30:34',NULL,NULL),('005','00137','C','ANDRES MARIANA','83962580',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-12-17 20:10:22',NULL,NULL,'2019-12-17 20:10:22',NULL,NULL),('005','00138','C','WLADIMIR LQ','39530190',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-12-17 20:10:22',NULL,NULL,'2019-12-17 20:10:22',NULL,NULL),('005','00139','C','ANGEL','13738029',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-12-17 20:10:23',NULL,NULL,'2019-12-17 20:10:23',NULL,NULL),('005','00140','C','FRANCY PEREZ','14867671',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-12-17 20:10:24',NULL,NULL,'2019-12-17 20:10:24',NULL,NULL),('005','00141','C','LUISANA PULIDO','17569258',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-12-17 20:10:24',NULL,NULL,'2019-12-17 20:10:24',NULL,NULL),('005','00142','C','LUISANA PULIDO','10455983',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-12-17 20:10:24',NULL,NULL,'2019-12-17 20:10:24',NULL,NULL),('005','00143','C','VICTOR','90107090',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-12-17 20:10:24',NULL,NULL,'2019-12-17 20:10:24',NULL,NULL),('005','00144','C','HARIANNA FARIAS','25612108',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,'01',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'B',NULL,NULL,NULL,'2019-12-17 20:10:25',NULL,NULL,'2019-12-17 20:10:25',NULL,NULL);
/*!40000 ALTER TABLE `prov_clientes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `prov_clientes_cont`
--

DROP TABLE IF EXISTS `prov_clientes_cont`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `prov_clientes_cont` (
  `PConCodi` char(4) NOT NULL,
  `PConNomb` varchar(80) DEFAULT NULL,
  PRIMARY KEY (`PConCodi`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `prov_clientes_cont`
--

LOCK TABLES `prov_clientes_cont` WRITE;
/*!40000 ALTER TABLE `prov_clientes_cont` DISABLE KEYS */;
INSERT INTO `prov_clientes_cont` VALUES ('0000','Sin Definir');
/*!40000 ALTER TABLE `prov_clientes_cont` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `prov_clientes_tipo`
--

DROP TABLE IF EXISTS `prov_clientes_tipo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `prov_clientes_tipo` (
  `TippCodi` char(2) NOT NULL,
  `TippNomb` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`TippCodi`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `prov_clientes_tipo`
--

LOCK TABLES `prov_clientes_tipo` WRITE;
/*!40000 ALTER TABLE `prov_clientes_tipo` DISABLE KEYS */;
INSERT INTO `prov_clientes_tipo` VALUES ('C','CLIENTES'),('P','PROVEEDORES'),('T','TRABAJADORES'),('V','VENDEDORES');
/*!40000 ALTER TABLE `prov_clientes_tipo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `proveedor`
--

DROP TABLE IF EXISTS `proveedor`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `proveedor` (
  `prvcodi` int(11) NOT NULL,
  `prvnomb` varchar(145) DEFAULT NULL,
  `prvdire` varchar(145) DEFAULT NULL,
  `prvrucc` varchar(15) DEFAULT NULL,
  `prvtel1` varchar(15) DEFAULT NULL,
  `prvtel2` varchar(15) DEFAULT NULL,
  `prvmail` varchar(45) DEFAULT NULL,
  `prvcont` varchar(105) DEFAULT NULL,
  `discodi` varchar(4) DEFAULT NULL,
  PRIMARY KEY (`prvcodi`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `proveedor`
--

LOCK TABLES `proveedor` WRITE;
/*!40000 ALTER TABLE `proveedor` DISABLE KEYS */;
/*!40000 ALTER TABLE `proveedor` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rh_apersonal`
--

DROP TABLE IF EXISTS `rh_apersonal`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rh_apersonal` (
  `RHPMOper` char(6) NOT NULL,
  `RHPCodi` char(5) NOT NULL,
  `Lunes` float DEFAULT NULL,
  `Martes` float DEFAULT NULL,
  `Miercoles` float DEFAULT NULL,
  `Jueves` float DEFAULT NULL,
  `Viernes` float DEFAULT NULL,
  `Sabado` float DEFAULT NULL,
  `Dia` float DEFAULT NULL,
  `PrecioDia` float DEFAULT NULL,
  `Importe` float DEFAULT NULL,
  `HExtra` float DEFAULT NULL,
  `User_Crea` varchar(45) DEFAULT NULL,
  `User_FCrea` datetime DEFAULT NULL,
  `User_ECrea` varchar(45) DEFAULT NULL,
  `User_Modi` varchar(45) DEFAULT NULL,
  `User_FModi` datetime DEFAULT NULL,
  `User_EModi` varchar(45) DEFAULT NULL,
  `UDelete` char(1) DEFAULT NULL,
  PRIMARY KEY (`RHPCodi`,`RHPMOper`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rh_apersonal`
--

LOCK TABLES `rh_apersonal` WRITE;
/*!40000 ALTER TABLE `rh_apersonal` DISABLE KEYS */;
/*!40000 ALTER TABLE `rh_apersonal` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rh_dppersonal`
--

DROP TABLE IF EXISTS `rh_dppersonal`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rh_dppersonal` (
  `RHDPOper` char(6) NOT NULL,
  `RHDPFech` date DEFAULT NULL,
  `RHPCodi` char(4) DEFAULT NULL,
  `RHDPBSue` float DEFAULT NULL,
  `RHDPFDia` int(11) DEFAULT NULL,
  `RHDPIDia` float DEFAULT NULL,
  `RHDPTAdel` float DEFAULT NULL,
  `RHDPAdel` float DEFAULT NULL,
  `RHDPHExt` float DEFAULT NULL,
  `RHDPIHext` float DEFAULT NULL,
  `RHDPNSue` float DEFAULT NULL,
  `RHPMOper` char(6) DEFAULT NULL,
  `RHDPOIng` float DEFAULT NULL,
  `RHDPOEgr` float DEFAULT NULL,
  `RHDPDia` float DEFAULT NULL,
  `RHDPHora` float DEFAULT NULL,
  `User_Crea` varchar(45) DEFAULT NULL,
  `User_FCrea` datetime DEFAULT NULL,
  `User_ECrea` varchar(45) DEFAULT NULL,
  `User_Modi` varchar(45) DEFAULT NULL,
  `User_FModi` datetime DEFAULT NULL,
  `User_EModi` varchar(45) DEFAULT NULL,
  `UDelete` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`RHDPOper`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rh_dppersonal`
--

LOCK TABLES `rh_dppersonal` WRITE;
/*!40000 ALTER TABLE `rh_dppersonal` DISABLE KEYS */;
/*!40000 ALTER TABLE `rh_dppersonal` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rh_mppersonal`
--

DROP TABLE IF EXISTS `rh_mppersonal`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rh_mppersonal` (
  `RHPMOper` char(6) NOT NULL,
  `RHPMFech` datetime DEFAULT NULL,
  `RHPMFIni` datetime DEFAULT NULL,
  `RHPMFUlt` datetime DEFAULT NULL,
  `RHPMImpo` float DEFAULT NULL,
  `RHPMEsta` char(2) DEFAULT NULL,
  PRIMARY KEY (`RHPMOper`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rh_mppersonal`
--

LOCK TABLES `rh_mppersonal` WRITE;
/*!40000 ALTER TABLE `rh_mppersonal` DISABLE KEYS */;
INSERT INTO `rh_mppersonal` VALUES ('000001','2018-12-26 00:00:00','2018-12-26 00:00:00','2018-12-26 00:00:00',0,'Ap');
/*!40000 ALTER TABLE `rh_mppersonal` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rh_personal`
--

DROP TABLE IF EXISTS `rh_personal`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rh_personal` (
  `RHPCodi` char(4) NOT NULL,
  `RHPNDoc` varchar(45) DEFAULT NULL,
  `RHPNomb` varchar(60) DEFAULT NULL,
  `RHPDire` varchar(100) DEFAULT NULL,
  `RHPTele` varchar(45) DEFAULT NULL,
  `RHPFNac` varchar(45) DEFAULT NULL,
  `RHPGen` int(11) DEFAULT NULL,
  `RHPECiv` int(11) DEFAULT NULL,
  `RHPGrad` int(11) DEFAULT NULL,
  `RHPEsta` int(11) DEFAULT NULL,
  `RHPFoto` longblob,
  `RHDCodi` char(2) DEFAULT NULL,
  `RHPSueld` float DEFAULT NULL,
  `RHPHE` float DEFAULT NULL,
  `RHPFing` date DEFAULT NULL,
  `RHPOIng` float DEFAULT NULL,
  `RHPOEgr` float DEFAULT NULL,
  `RHDebe` float DEFAULT NULL,
  `RHHaber` float DEFAULT NULL,
  `CarCodi` int(11) DEFAULT NULL,
  `User_Crea` varchar(45) DEFAULT NULL,
  `User_FCrea` datetime DEFAULT NULL,
  `User_ECrea` varchar(45) DEFAULT NULL,
  `User_Modi` varchar(45) DEFAULT NULL,
  `User_FModi` datetime DEFAULT NULL,
  `User_EModi` varchar(45) DEFAULT NULL,
  `UDelete` char(1) DEFAULT NULL,
  `empcodi` varchar(3) NOT NULL DEFAULT '001',
  PRIMARY KEY (`RHPCodi`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rh_personal`
--

LOCK TABLES `rh_personal` WRITE;
/*!40000 ALTER TABLE `rh_personal` DISABLE KEYS */;
/*!40000 ALTER TABLE `rh_personal` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rh_personal_cargo`
--

DROP TABLE IF EXISTS `rh_personal_cargo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rh_personal_cargo` (
  `carcodi` int(11) NOT NULL AUTO_INCREMENT,
  `carnomb` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`carcodi`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rh_personal_cargo`
--

LOCK TABLES `rh_personal_cargo` WRITE;
/*!40000 ALTER TABLE `rh_personal_cargo` DISABLE KEYS */;
INSERT INTO `rh_personal_cargo` VALUES (1,'TECNICO MECANICO'),(2,'OBRERO'),(20,'PROGRAMADOR'),(21,'VENTAS'),(22,'MAESTRO OPERADOR'),(23,'TORNERO'),(24,'MAESTRO MUELLERO'),(25,'AYUDANTE'),(26,'ADMINISTRACIO'),(27,'JEFA DE ALMACEN'),(28,'MUELLERO');
/*!40000 ALTER TABLE `rh_personal_cargo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rhdepartamento`
--

DROP TABLE IF EXISTS `rhdepartamento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rhdepartamento` (
  `RHDCodi` char(2) NOT NULL,
  `RHDNomb` varchar(45) DEFAULT NULL,
  `RHDTele` varchar(45) DEFAULT NULL,
  `RHDDire` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`RHDCodi`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rhdepartamento`
--

LOCK TABLES `rhdepartamento` WRITE;
/*!40000 ALTER TABLE `rhdepartamento` DISABLE KEYS */;
INSERT INTO `rhdepartamento` VALUES ('01','TALLER','',''),('02','OFICINA','','');
/*!40000 ALTER TABLE `rhdepartamento` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role_has_permissions`
--

DROP TABLE IF EXISTS `role_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `role_has_permissions` (
  `permission_id` int(10) unsigned NOT NULL,
  `role_id` int(10) unsigned NOT NULL,
  KEY `role_has_permissions_permission_id_foreign` (`permission_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`),
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role_has_permissions`
--

LOCK TABLES `role_has_permissions` WRITE;
/*!40000 ALTER TABLE `role_has_permissions` DISABLE KEYS */;
INSERT INTO `role_has_permissions` VALUES (177,16),(178,16),(179,16),(180,16),(181,16),(182,16),(183,16),(184,16),(185,16),(186,16),(187,16),(188,16),(189,16),(190,16),(191,16),(192,16),(193,16),(194,16),(195,16),(196,16),(197,16),(198,16),(199,16),(200,16),(201,16),(202,16),(203,16),(204,16),(205,16),(206,16),(207,16),(208,16),(209,16),(210,16),(211,16),(212,16),(213,16),(214,16),(215,16),(216,16),(217,16),(218,16),(219,16),(220,16),(221,16),(222,16),(223,16),(224,16),(225,16),(226,16),(226,18),(177,19),(178,19),(179,19),(181,19),(182,19),(183,19),(184,19),(185,19),(188,19),(189,19),(190,19),(191,19),(192,19),(193,19),(197,19),(198,19),(200,19),(203,19),(204,19),(205,19),(207,19),(212,19),(213,19),(214,19),(215,19),(221,19),(222,19),(223,19),(177,17),(179,17),(180,17),(181,17),(182,17),(183,17),(185,17),(188,17),(189,17),(190,17),(193,17),(194,17),(195,17),(196,17),(197,17),(198,17),(199,17),(200,17),(201,17),(202,17),(203,17),(204,17),(205,17),(206,17),(207,17),(208,17),(209,17),(210,17),(211,17),(212,17),(213,17),(214,17),(215,17),(216,17),(217,17),(218,17),(219,17),(220,17),(221,17),(222,17),(223,17),(224,17);
/*!40000 ALTER TABLE `role_has_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `roles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (16,'admin','web','2019-08-16 10:59:07','2019-08-16 10:59:07'),(17,'visitante','web','2019-08-16 10:59:08','2019-08-16 10:59:08'),(18,'contador','web','2019-08-16 10:59:08','2019-08-16 10:59:08'),(19,'VENTAS','web','2019-08-21 16:43:25','2019-08-21 16:43:25');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tcmoneda`
--

DROP TABLE IF EXISTS `tcmoneda`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tcmoneda` (
  `TipCodi` char(6) NOT NULL DEFAULT '',
  `TipFech` date NOT NULL,
  `TipComp` float DEFAULT NULL,
  `TipVent` float DEFAULT NULL,
  `empcodi` varchar(3) NOT NULL DEFAULT '001',
  PRIMARY KEY (`TipCodi`,`TipFech`,`empcodi`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tcmoneda`
--

LOCK TABLES `tcmoneda` WRITE;
/*!40000 ALTER TABLE `tcmoneda` DISABLE KEYS */;
INSERT INTO `tcmoneda` VALUES ('111201','2011-12-01',1,1,'001');
/*!40000 ALTER TABLE `tcmoneda` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transportista`
--

DROP TABLE IF EXISTS `transportista`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `transportista` (
  `TraCodi` int(11) NOT NULL,
  `TraNomb` varchar(65) DEFAULT NULL,
  `TraDire` varchar(85) DEFAULT NULL,
  `TraRucc` varchar(15) DEFAULT NULL,
  `TraTele` varchar(15) DEFAULT NULL,
  `TraLice` varchar(25) DEFAULT NULL,
  `EmpCodi` int(3) DEFAULT NULL,
  PRIMARY KEY (`TraCodi`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transportista`
--

LOCK TABLES `transportista` WRITE;
/*!40000 ALTER TABLE `transportista` DISABLE KEYS */;
INSERT INTO `transportista` VALUES (1001,'SIN DEFINIR','','103230137','','10002255',100);
/*!40000 ALTER TABLE `transportista` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `unidad`
--

DROP TABLE IF EXISTS `unidad`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `unidad` (
  `Unicodi` varchar(10) NOT NULL,
  `Id` char(11) DEFAULT NULL,
  `UniEnte` int(11) DEFAULT NULL,
  `UniMedi` int(11) DEFAULT NULL,
  `UniAbre` char(4) DEFAULT NULL,
  `UniPUCD` float DEFAULT NULL,
  `UniPUCS` float DEFAULT NULL,
  `UniMarg` float DEFAULT NULL,
  `UNIPUVD` float DEFAULT NULL,
  `UNIPUVS` float DEFAULT NULL,
  `UniPeso` float DEFAULT NULL,
  `UniPAdi` float DEFAULT NULL,
  `UniMarg1` float DEFAULT NULL,
  `UniPUVS1` float DEFAULT NULL,
  `UniPUVD1` float DEFAULT NULL,
  `empcodi` varchar(3) NOT NULL DEFAULT '001',
  `LisCodi` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`Unicodi`,`empcodi`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `unidad`
--

LOCK TABLES `unidad` WRITE;
/*!40000 ALTER TABLE `unidad` DISABLE KEYS */;
/*!40000 ALTER TABLE `unidad` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuario_documento`
--

DROP TABLE IF EXISTS `usuario_documento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usuario_documento` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `empcodi` char(3) NOT NULL,
  `usucodi` char(2) NOT NULL,
  `tidcodi` char(2) NOT NULL,
  `sercodi` char(4) NOT NULL,
  `numcodi` char(8) NOT NULL,
  `defecto` int(11) DEFAULT NULL,
  `loccodi` char(3) DEFAULT NULL,
  `estado` int(11) DEFAULT NULL,
  `contingencia` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`empcodi`,`usucodi`,`tidcodi`,`sercodi`,`numcodi`,`ID`),
  UNIQUE KEY `ID` (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=89 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario_documento`
--

LOCK TABLES `usuario_documento` WRITE;
/*!40000 ALTER TABLE `usuario_documento` DISABLE KEYS */;
/*!40000 ALTER TABLE `usuario_documento` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuario_empr`
--

DROP TABLE IF EXISTS `usuario_empr`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usuario_empr` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `usucodi` varchar(3) NOT NULL COMMENT 'id de usuario',
  `empcodi` varchar(3) NOT NULL COMMENT 'id de empresa',
  `estado` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'estado de la empresa',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario_empr`
--

LOCK TABLES `usuario_empr` WRITE;
/*!40000 ALTER TABLE `usuario_empr` DISABLE KEYS */;
INSERT INTO `usuario_empr` VALUES (1,'01','001',1);
/*!40000 ALTER TABLE `usuario_empr` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuario_local`
--

DROP TABLE IF EXISTS `usuario_local`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usuario_local` (
  `usucodi` char(2) NOT NULL,
  `loccodi` char(3) NOT NULL,
  `sercodi` char(4) NOT NULL,
  `numcodi` char(8) DEFAULT NULL,
  `defecto` int(11) DEFAULT NULL,
  `empcodi` varchar(3) NOT NULL DEFAULT '001',
  PRIMARY KEY (`usucodi`,`loccodi`,`sercodi`,`empcodi`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario_local`
--

LOCK TABLES `usuario_local` WRITE;
/*!40000 ALTER TABLE `usuario_local` DISABLE KEYS */;
INSERT INTO `usuario_local` VALUES ('01','001','0001','000000',1,'001'),('01','001','0002','000000',1,'001'),('13','001','001','00000',1,'001');
/*!40000 ALTER TABLE `usuario_local` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuario_proceso`
--

DROP TABLE IF EXISTS `usuario_proceso`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usuario_proceso` (
  `UsuCodi` char(2) NOT NULL DEFAULT '',
  `Procodi` int(11) DEFAULT '0',
  `Unicodi` int(11) DEFAULT NULL,
  `CliCodi` int(11) DEFAULT NULL,
  `loccodi` int(11) DEFAULT NULL,
  `usocodi` int(11) DEFAULT NULL,
  `mescodi` int(11) DEFAULT NULL,
  `tipCodi` int(11) DEFAULT NULL,
  `zoncodi` int(11) DEFAULT NULL,
  `vencodi` int(11) DEFAULT NULL,
  `fpgcodi` int(11) DEFAULT NULL,
  `tigcodi` int(11) DEFAULT NULL,
  `motcodi` int(11) DEFAULT NULL,
  `emtcodi` int(11) DEFAULT NULL,
  `tracodi` int(11) DEFAULT NULL,
  `vehcodi` int(11) DEFAULT NULL,
  `bancodi` int(11) DEFAULT NULL,
  `guientr` int(11) DEFAULT NULL,
  `guisali` int(11) DEFAULT NULL,
  `ctostoc` int(11) DEFAULT NULL,
  `actstoc` int(11) DEFAULT NULL,
  `stomini` int(11) DEFAULT NULL,
  `ordoper` int(11) DEFAULT NULL,
  `cpaoper` int(11) DEFAULT NULL,
  `gasoper` int(11) DEFAULT NULL,
  `cotoper` int(11) DEFAULT NULL,
  `vtaoper` int(11) DEFAULT NULL,
  `peroper` int(11) DEFAULT NULL,
  `camnume` int(11) DEFAULT NULL,
  `Cajoper` int(11) DEFAULT NULL,
  `CpgOper` int(11) DEFAULT NULL,
  `CpgROper` int(11) DEFAULT NULL,
  `CcbOper` int(11) DEFAULT NULL,
  `CcbROper` int(11) DEFAULT NULL,
  `RepLista` int(11) DEFAULT NULL,
  `RepSald` int(11) DEFAULT NULL,
  `RepKinte` int(11) DEFAULT NULL,
  `RepKSuna` int(11) DEFAULT NULL,
  `RepMovi` int(11) DEFAULT NULL,
  `RepRCpa` int(11) DEFAULT NULL,
  `RepRvta` int(11) DEFAULT NULL,
  `RepGast` int(11) DEFAULT NULL,
  `RepIngr` int(11) DEFAULT NULL,
  `EmpCodi` int(11) DEFAULT NULL,
  `OtrCodi` int(11) DEFAULT NULL,
  `BorrCodi` int(11) DEFAULT NULL,
  `PlanCodi` int(11) DEFAULT NULL,
  `CopCodi` int(11) DEFAULT NULL,
  `UbiCodi` int(11) DEFAULT NULL,
  `RepGSal` int(1) DEFAULT NULL,
  `RepGIng` int(1) DEFAULT NULL,
  PRIMARY KEY (`UsuCodi`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario_proceso`
--

LOCK TABLES `usuario_proceso` WRITE;
/*!40000 ALTER TABLE `usuario_proceso` DISABLE KEYS */;
/*!40000 ALTER TABLE `usuario_proceso` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vehiculo`
--

DROP TABLE IF EXISTS `vehiculo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vehiculo` (
  `VehCodi` int(4) NOT NULL,
  `VehPlac` varchar(45) DEFAULT NULL,
  `VehMarc` varchar(45) DEFAULT NULL,
  `VehInsc` varchar(25) DEFAULT NULL,
  `empcodi` varchar(3) NOT NULL DEFAULT '001',
  PRIMARY KEY (`VehCodi`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vehiculo`
--

LOCK TABLES `vehiculo` WRITE;
/*!40000 ALTER TABLE `vehiculo` DISABLE KEYS */;
INSERT INTO `vehiculo` VALUES (1001,'SIN DEFINIR','SIN DEFINIR','','001'),(1002,'555-DDD','TOYOTA','','001');
/*!40000 ALTER TABLE `vehiculo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vendedores`
--

DROP TABLE IF EXISTS `vendedores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vendedores` (
  `Vencodi` char(4) NOT NULL,
  `vennomb` varchar(65) DEFAULT NULL,
  `vendire` varchar(100) DEFAULT NULL,
  `ventel1` varchar(45) DEFAULT NULL,
  `ventel2` char(20) DEFAULT NULL,
  `vensuel` float DEFAULT NULL,
  `vencomi` float DEFAULT NULL,
  `venneto` float DEFAULT NULL,
  `venmail` varchar(45) DEFAULT NULL,
  `empcodi` varchar(3) NOT NULL DEFAULT '001',
  `defecto` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`Vencodi`,`empcodi`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vendedores`
--

LOCK TABLES `vendedores` WRITE;
/*!40000 ALTER TABLE `vendedores` DISABLE KEYS */;
INSERT INTO `vendedores` VALUES ('1OFI','OFICINA','OFICINA','4500841','985153240',1000,2,220,'fonsecabwa@hotmail.com','001',1);
/*!40000 ALTER TABLE `vendedores` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ventas_amazon`
--

DROP TABLE IF EXISTS `ventas_amazon`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ventas_amazon` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `VtaOper` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `XML` int(11) NOT NULL DEFAULT '0',
  `PDF` int(11) NOT NULL DEFAULT '0',
  `CDR` int(11) NOT NULL DEFAULT '0',
  `Estatus` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `EmpCodi` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '001',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=276 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ventas_amazon`
--

LOCK TABLES `ventas_amazon` WRITE;
/*!40000 ALTER TABLE `ventas_amazon` DISABLE KEYS */;
/*!40000 ALTER TABLE `ventas_amazon` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ventas_cab`
--

DROP TABLE IF EXISTS `ventas_cab`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ventas_cab` (
  `VtaOper` char(10) NOT NULL,
  `EmpCodi` char(3) NOT NULL,
  `PanAno` char(4) NOT NULL,
  `PanPeri` char(2) NOT NULL,
  `TidCodi` char(2) NOT NULL,
  `VtaSeri` char(4) NOT NULL,
  `VtaNumee` char(8) NOT NULL,
  `VtaNume` varchar(12) DEFAULT NULL,
  `VtaFvta` date DEFAULT NULL,
  `vtaFpag` date DEFAULT NULL,
  `VtaFVen` date DEFAULT NULL,
  `PCCodi` char(5) DEFAULT NULL,
  `ConCodi` char(2) DEFAULT NULL,
  `ZonCodi` char(4) DEFAULT NULL,
  `MonCodi` char(2) DEFAULT NULL,
  `Vencodi` char(4) DEFAULT NULL,
  `DocRefe` varchar(20) DEFAULT NULL,
  `GuiOper` char(10) DEFAULT NULL,
  `VtaObse` varchar(100) DEFAULT NULL,
  `VtaTcam` float DEFAULT NULL,
  `Vtacant` float DEFAULT NULL,
  `Vtabase` float DEFAULT NULL,
  `VtaIGVV` float DEFAULT NULL,
  `VtaDcto` float DEFAULT NULL,
  `VtaInaf` float DEFAULT NULL,
  `VtaExon` float DEFAULT NULL,
  `VtaGrat` float DEFAULT NULL,
  `VtaISC` float DEFAULT NULL,
  `VtaImpo` float DEFAULT NULL,
  `VtaEsta` char(1) DEFAULT NULL,
  `UsuCodi` char(2) DEFAULT NULL,
  `MesCodi` char(6) DEFAULT NULL,
  `LocCodi` char(3) DEFAULT NULL,
  `VtaPago` float DEFAULT NULL,
  `VtaSald` float DEFAULT NULL,
  `VtaEsPe` char(2) DEFAULT NULL,
  `VtaPPer` float DEFAULT NULL,
  `VtaAPer` float DEFAULT NULL,
  `VtaPerc` float DEFAULT NULL,
  `VtaTota` float DEFAULT NULL,
  `VtaSPer` float DEFAULT NULL,
  `TipCodi` char(6) DEFAULT NULL,
  `AlmEsta` char(2) DEFAULT NULL,
  `CajNume` varchar(12) DEFAULT NULL,
  `VtaSdCa` float DEFAULT NULL,
  `VtaHora` varchar(45) DEFAULT NULL,
  `vtafact` char(1) DEFAULT NULL,
  `vtaanu` char(40) DEFAULT NULL,
  `vtaadoc` char(40) DEFAULT NULL,
  `VtaPedi` char(30) DEFAULT NULL,
  `VtaEOpe` char(3) DEFAULT NULL,
  `User_Crea` varchar(45) DEFAULT NULL,
  `User_FCrea` datetime DEFAULT NULL,
  `User_ECrea` varchar(45) DEFAULT NULL,
  `User_Modi` char(30) DEFAULT NULL,
  `User_FModi` datetime DEFAULT NULL,
  `User_EModi` varchar(45) DEFAULT NULL,
  `UDelete` char(1) DEFAULT NULL,
  `fe_fxml` datetime DEFAULT NULL,
  `fe_fenvio` datetime DEFAULT NULL,
  `fe_estado` varchar(105) DEFAULT NULL,
  `fe_obse` varchar(105) DEFAULT NULL,
  `fe_rpta` int(11) DEFAULT NULL,
  `fe_rptaa` int(11) DEFAULT NULL,
  `fe_firma` varchar(105) DEFAULT NULL,
  `VtaTDR` char(2) DEFAULT NULL,
  `VtaSeriR` char(4) DEFAULT NULL,
  `VtaNumeR` char(8) DEFAULT NULL,
  `VtaFVtaR` date DEFAULT NULL,
  `VtaXML` int(11) DEFAULT NULL,
  `VtaPDF` int(11) DEFAULT NULL,
  `VtaCDR` int(11) DEFAULT NULL,
  `VtaMail` int(11) DEFAULT NULL,
  `VtaFMail` varchar(255) DEFAULT NULL,
  `Numoper` char(6) DEFAULT NULL,
  `TipoOper` char(1) DEFAULT NULL,
  `fe_version` char(5) DEFAULT NULL,
  `VtaDetrPorc` varchar(2) DEFAULT NULL,
  `VtaDetrTota` varchar(255) DEFAULT NULL,
  `VtaTotalDetr` varchar(255) DEFAULT NULL,
  `CuenCodi` varchar(255) DEFAULT NULL,
  `VtaDetrCode` varchar(255) DEFAULT '0',
  `VtaAnticipo` varchar(255) DEFAULT '0',
  `VtaNumeAnticipo` varchar(255) DEFAULT NULL,
  `VtaTidCodiAnticipo` varchar(255) DEFAULT NULL,
  `VtaTotalAnticipo` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`VtaOper`,`EmpCodi`,`PanAno`,`PanPeri`,`TidCodi`,`VtaSeri`,`VtaNumee`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ventas_cab`
--

LOCK TABLES `ventas_cab` WRITE;
/*!40000 ALTER TABLE `ventas_cab` DISABLE KEYS */;
/*!40000 ALTER TABLE `ventas_cab` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ventas_consultas_sunat`
--

DROP TABLE IF EXISTS `ventas_consultas_sunat`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ventas_consultas_sunat` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `VtaOper` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `CodiSunat` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Descripcion` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `User_Crea` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `User_FCrea` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `User_Modi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `User_FModi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `EmpCodi` varchar(4) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ventas_consultas_sunat`
--

LOCK TABLES `ventas_consultas_sunat` WRITE;
/*!40000 ALTER TABLE `ventas_consultas_sunat` DISABLE KEYS */;
/*!40000 ALTER TABLE `ventas_consultas_sunat` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ventas_detalle`
--

DROP TABLE IF EXISTS `ventas_detalle`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ventas_detalle` (
  `Linea` char(8) NOT NULL DEFAULT '',
  `DetItem` char(2) DEFAULT NULL,
  `VtaOper` char(10) DEFAULT NULL,
  `UniCodi` varchar(10) DEFAULT NULL,
  `DetUnid` char(4) DEFAULT NULL,
  `DetCodi` char(20) DEFAULT NULL,
  `DetNomb` varchar(255) DEFAULT NULL,
  `MarNomb` varchar(20) DEFAULT NULL,
  `DetCant` float DEFAULT NULL,
  `DetPrec` float DEFAULT NULL,
  `DetImpo` float DEFAULT NULL,
  `DetPeso` float DEFAULT NULL,
  `DetEsta` char(1) DEFAULT NULL,
  `DetEsPe` float DEFAULT NULL,
  `DetCSol` float DEFAULT NULL,
  `DetCDol` float DEFAULT NULL,
  `CCCCodi` char(3) DEFAULT NULL,
  `CotNume` char(12) DEFAULT NULL,
  `GuiOper` char(10) DEFAULT NULL,
  `GuiLine` char(10) DEFAULT NULL,
  `DetIGVV` float DEFAULT '18',
  `DetSdCa` float DEFAULT '0',
  `Detfact` float DEFAULT '1',
  `DetDcto` float DEFAULT '0',
  `DetDeta` varchar(505) DEFAULT NULL,
  `Estado` char(2) DEFAULT NULL,
  `lote` char(15) DEFAULT NULL,
  `detfven` date DEFAULT NULL,
  `DetBase` varchar(45) DEFAULT NULL,
  `DetISC` float DEFAULT NULL,
  `DetISCP` float DEFAULT NULL,
  `DetIGVP` float DEFAULT NULL,
  `DetPercP` float DEFAULT NULL,
  `EmpCodi` varchar(3) NOT NULL DEFAULT '001',
  `TipoIGV` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`Linea`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ventas_detalle`
--

LOCK TABLES `ventas_detalle` WRITE;
/*!40000 ALTER TABLE `ventas_detalle` DISABLE KEYS */;
/*!40000 ALTER TABLE `ventas_detalle` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ventas_nc`
--

DROP TABLE IF EXISTS `ventas_nc`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ventas_nc` (
  `NCCodi` char(6) NOT NULL,
  `NCFech` date DEFAULT NULL,
  `VtaRefe` char(20) DEFAULT NULL,
  `VtaOper` char(8) DEFAULT NULL,
  `VtaNume` char(12) DEFAULT NULL,
  `Moncodi` char(2) DEFAULT NULL,
  `VtaImpo` float DEFAULT NULL,
  `TidCodi` char(2) DEFAULT NULL,
  PRIMARY KEY (`NCCodi`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ventas_nc`
--

LOCK TABLES `ventas_nc` WRITE;
/*!40000 ALTER TABLE `ventas_nc` DISABLE KEYS */;
/*!40000 ALTER TABLE `ventas_nc` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ventas_nube`
--

DROP TABLE IF EXISTS `ventas_nube`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ventas_nube` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `VtaOper` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `XML` int(11) NOT NULL DEFAULT '0',
  `PDF` int(11) NOT NULL DEFAULT '0',
  `CDR` int(11) NOT NULL DEFAULT '0',
  `Estatus` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ventas_nube`
--

LOCK TABLES `ventas_nube` WRITE;
/*!40000 ALTER TABLE `ventas_nube` DISABLE KEYS */;
/*!40000 ALTER TABLE `ventas_nube` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ventas_pago`
--

DROP TABLE IF EXISTS `ventas_pago`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ventas_pago` (
  `PagOper` varchar(10) NOT NULL DEFAULT '',
  `VtaOper` char(10) DEFAULT NULL,
  `TpgCodi` char(2) DEFAULT NULL,
  `PagFech` date DEFAULT NULL,
  `PagTCam` float DEFAULT NULL,
  `MonCodi` char(2) DEFAULT NULL,
  `PagImpo` float DEFAULT NULL,
  `BanCodi` char(22) DEFAULT NULL,
  `Bannomb` varchar(60) DEFAULT NULL,
  `VtaNume` varchar(12) DEFAULT NULL,
  `VtaFVta` date DEFAULT NULL,
  `VtaFVen` date DEFAULT NULL,
  `PagBoch` char(30) DEFAULT NULL,
  `usufech` date DEFAULT NULL,
  `usuhora` char(20) DEFAULT NULL,
  `usucodi` char(2) DEFAULT NULL,
  `CajNume` varchar(10) DEFAULT NULL,
  `antnume` char(10) DEFAULT NULL,
  `CtoOper` char(15) DEFAULT NULL,
  `CheP` varchar(45) DEFAULT NULL,
  `ChECodi` char(2) DEFAULT NULL,
  `PCCodi` char(5) DEFAULT NULL,
  `VtaFact` int(1) DEFAULT NULL,
  `EmpCodi` char(3) NOT NULL,
  `PanAno` char(4) DEFAULT NULL,
  `PanPeri` char(2) DEFAULT NULL,
  `User_Crea` varchar(45) DEFAULT NULL,
  `User_FCrea` datetime DEFAULT NULL,
  `User_ECrea` varchar(45) DEFAULT NULL,
  `User_Modi` varchar(45) DEFAULT NULL,
  `User_FModi` datetime DEFAULT NULL,
  `User_EModi` varchar(45) DEFAULT NULL,
  `UDelete` char(1) DEFAULT NULL,
  PRIMARY KEY (`PagOper`,`EmpCodi`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ventas_pago`
--

LOCK TABLES `ventas_pago` WRITE;
/*!40000 ALTER TABLE `ventas_pago` DISABLE KEYS */;
/*!40000 ALTER TABLE `ventas_pago` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ventas_perc`
--

DROP TABLE IF EXISTS `ventas_perc`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ventas_perc` (
  `PerNume` varchar(12) NOT NULL DEFAULT '',
  `PerDocu` varchar(15) DEFAULT NULL,
  `Perfech` date DEFAULT NULL,
  `PCcodi` varchar(11) DEFAULT NULL,
  `PerImpo` float DEFAULT NULL,
  `MesCodi` varchar(6) DEFAULT NULL,
  `DocRefe` char(25) DEFAULT NULL,
  `PerEsta` varchar(1) DEFAULT NULL,
  `Permonto` float DEFAULT NULL,
  `UsuCodi` char(2) DEFAULT NULL,
  `LocCodi` char(3) DEFAULT NULL,
  PRIMARY KEY (`PerNume`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ventas_perc`
--

LOCK TABLES `ventas_perc` WRITE;
/*!40000 ALTER TABLE `ventas_perc` DISABLE KEYS */;
/*!40000 ALTER TABLE `ventas_perc` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ventas_perc_detalle`
--

DROP TABLE IF EXISTS `ventas_perc_detalle`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ventas_perc_detalle` (
  `DetItem` int(5) NOT NULL DEFAULT '0',
  `PerNume` varchar(12) DEFAULT '',
  `TidAbre` varchar(20) DEFAULT NULL,
  `DocNume` varchar(20) DEFAULT NULL,
  `DocFecV` date DEFAULT NULL,
  `DetTotaD` float DEFAULT NULL,
  `DetTC` float DEFAULT NULL,
  `DetTotalS` float DEFAULT NULL,
  `DetsaldS` float DEFAULT NULL,
  `DetPrec` float DEFAULT NULL,
  `DetPorc` float DEFAULT NULL,
  `DetImpP` float DEFAULT NULL,
  `DetMonto` float DEFAULT NULL,
  `DetEsta` varchar(1) DEFAULT NULL,
  PRIMARY KEY (`DetItem`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ventas_perc_detalle`
--

LOCK TABLES `ventas_perc_detalle` WRITE;
/*!40000 ALTER TABLE `ventas_perc_detalle` DISABLE KEYS */;
/*!40000 ALTER TABLE `ventas_perc_detalle` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ventas_ra_cab`
--

DROP TABLE IF EXISTS `ventas_ra_cab`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ventas_ra_cab` (
  `EmpCodi` char(3) NOT NULL,
  `PanAno` char(4) NOT NULL,
  `PanPeri` char(2) NOT NULL,
  `MesCodi` char(6) NOT NULL,
  `TipoOper` char(1) NOT NULL,
  `NumOper` char(6) NOT NULL,
  `DocNume` char(25) NOT NULL,
  `DocFechaE` date DEFAULT NULL,
  `DocFechaD` date DEFAULT NULL,
  `DocFechaEv` datetime DEFAULT NULL,
  `DocDesc` varchar(255) NOT NULL,
  `DocMotivo` varchar(45) DEFAULT NULL,
  `DocTicket` varchar(45) DEFAULT NULL,
  `DocXML` int(11) DEFAULT NULL,
  `DocPDF` int(11) DEFAULT NULL,
  `DocCDR` int(11) DEFAULT NULL,
  `DocCHash` varchar(45) DEFAULT NULL,
  `DocCEsta` int(11) DEFAULT NULL,
  `DocEstado` varchar(45) DEFAULT NULL,
  `User_Crea` varchar(45) DEFAULT NULL,
  `User_FCrea` datetime DEFAULT NULL,
  `User_ECrea` varchar(45) DEFAULT NULL,
  `User_Modi` varchar(45) DEFAULT NULL,
  `User_FModi` datetime DEFAULT NULL,
  `User_EModi` varchar(45) DEFAULT NULL,
  `UDelete` char(1) DEFAULT NULL,
  `MonCodi` char(2) DEFAULT NULL,
  `LocCodi` varchar(255) NOT NULL DEFAULT '001',
  PRIMARY KEY (`EmpCodi`,`PanAno`,`PanPeri`,`TipoOper`,`NumOper`,`DocNume`,`MesCodi`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ventas_ra_cab`
--

LOCK TABLES `ventas_ra_cab` WRITE;
/*!40000 ALTER TABLE `ventas_ra_cab` DISABLE KEYS */;
/*!40000 ALTER TABLE `ventas_ra_cab` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ventas_ra_detalle`
--

DROP TABLE IF EXISTS `ventas_ra_detalle`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ventas_ra_detalle` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `EmpCodi` char(3) NOT NULL,
  `PanAno` char(4) NOT NULL,
  `PanPeri` char(2) NOT NULL,
  `numoper` char(6) NOT NULL,
  `docNume` char(25) NOT NULL,
  `DetItem` char(5) NOT NULL,
  `detfecha` date DEFAULT NULL,
  `tidcodi` char(2) DEFAULT NULL,
  `detseri` char(4) DEFAULT NULL,
  `detNume` char(8) DEFAULT NULL,
  `DetNume1` char(8) DEFAULT NULL,
  `DetMotivo` varchar(145) DEFAULT NULL,
  `DetGrav` float DEFAULT NULL,
  `DetExon` float DEFAULT NULL,
  `DetInaf` float DEFAULT NULL,
  `DetIGV` float DEFAULT NULL,
  `DetISC` float DEFAULT NULL,
  `DetTota` float DEFAULT NULL,
  `PCCodi` char(5) DEFAULT NULL,
  `PCRucc` varchar(15) DEFAULT NULL,
  `TDocCodi` varchar(1) DEFAULT NULL,
  `VtaEsta` char(1) DEFAULT NULL,
  `vtatdr` varchar(255) DEFAULT NULL,
  `vtaserir` varchar(255) DEFAULT NULL,
  `vtanumer` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`EmpCodi`,`PanAno`,`PanPeri`,`numoper`,`docNume`,`DetItem`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ventas_ra_detalle`
--

LOCK TABLES `ventas_ra_detalle` WRITE;
/*!40000 ALTER TABLE `ventas_ra_detalle` DISABLE KEYS */;
/*!40000 ALTER TABLE `ventas_ra_detalle` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ventas_transportista`
--

DROP TABLE IF EXISTS `ventas_transportista`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ventas_transportista` (
  `TraCodi` char(2) NOT NULL,
  `TraNomb` varchar(70) DEFAULT NULL,
  `TraRucc` varchar(12) DEFAULT NULL,
  `TraDire` varchar(100) DEFAULT NULL,
  `TraPlac` char(15) DEFAULT NULL,
  PRIMARY KEY (`TraCodi`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ventas_transportista`
--

LOCK TABLES `ventas_transportista` WRITE;
/*!40000 ALTER TABLE `ventas_transportista` DISABLE KEYS */;
/*!40000 ALTER TABLE `ventas_transportista` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Final view structure for view `c_prodvendidofecha`
--

/*!50001 DROP VIEW IF EXISTS `c_prodvendidofecha`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `c_prodvendidofecha` AS select `productos`.`ID` AS `id`,`productos`.`unpcodi` AS `UnpCodi`,`productos`.`ProCodi` AS `ProCodi`,`productos`.`ProNomb` AS `ProNomb`,sum(((`ventas_detalle`.`DetCant` / `unidad`.`UniMedi`) * `unidad`.`UniEnte`)) AS `SumaDeDetCant`,`ventas_cab`.`VtaEsta` AS `VtaEsta`,`ventas_cab`.`MesCodi` AS `MesCodi`,`mes`.`mesnomb` AS `mesnomb`,`ventas_cab`.`VtaFvta` AS `VtaFVta`,`ventas_cab`.`LocCodi` AS `LocCodi` from ((((`productos` join `unidad`) join `mes`) join `ventas_cab`) join `ventas_detalle`) where ((`mes`.`mescodi` = `ventas_cab`.`MesCodi`) and (`unidad`.`Unicodi` = `ventas_detalle`.`UniCodi`) and (`ventas_cab`.`VtaOper` = `ventas_detalle`.`VtaOper`) and (convert(`productos`.`ID` using utf8) = convert(`unidad`.`Id` using utf8))) group by `productos`.`ID`,`productos`.`unpcodi`,`productos`.`ProCodi`,`productos`.`ProNomb`,`ventas_cab`.`VtaEsta`,`ventas_cab`.`MesCodi`,`mes`.`mesnomb`,`ventas_cab`.`VtaFvta`,`ventas_cab`.`LocCodi` having (`ventas_cab`.`VtaEsta` = 'V') */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `c_proven`
--

/*!50001 DROP VIEW IF EXISTS `c_proven`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `c_proven` AS select `ventas_detalle`.`DetCodi` AS `DetCodi`,`productos`.`unpcodi` AS `unpcodi`,`ventas_detalle`.`DetNomb` AS `DetNomb`,`ventas_detalle`.`MarNomb` AS `MarNomb`,(`ventas_detalle`.`DetCant` * `ventas_detalle`.`Detfact`) AS `CANT`,`ventas_cab`.`VtaFvta` AS `VtaFvta` from ((`productos` join `ventas_detalle` on((`productos`.`ProCodi` = `ventas_detalle`.`DetCodi`))) join `ventas_cab` on((`ventas_detalle`.`VtaOper` = `ventas_cab`.`VtaOper`))) where (`ventas_cab`.`VtaFvta` between '20150730' and '20150731') */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `c_salidas_prod`
--

/*!50001 DROP VIEW IF EXISTS `c_salidas_prod`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `c_salidas_prod` AS select `guias_cab`.`Loccodi` AS `LocCodi`,`guias_cab`.`TmoCodi` AS `TmoCodi`,`guias_cab`.`PCCodi` AS `PCCodi`,`productos`.`ID` AS `id`,`productos`.`ProCodi` AS `ProCodi`,`productos`.`unpcodi` AS `UnpCodi`,`productos`.`ProNomb` AS `ProNomb`,`guia_detalle`.`Detcant` AS `detcant`,((`guia_detalle`.`Detcant` * `unidad`.`UniEnte`) / `unidad`.`UniMedi`) AS `Cant`,`unidad`.`UniEnte` AS `UniEnte`,`unidad`.`UniMedi` AS `UniMedi`,`guias_cab`.`GuiFemi` AS `guifemi`,`guias_cab`.`EntSal` AS `EntSal` from (((`guia_detalle` join `guias_cab`) join `unidad`) join `productos`) where ((`guias_cab`.`GuiOper` = `guia_detalle`.`GuiOper`) and (`guia_detalle`.`UniCodi` = `unidad`.`Unicodi`) and (convert(`unidad`.`Id` using utf8) = convert(`productos`.`ID` using utf8))) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `c_utilidad_detalle`
--

/*!50001 DROP VIEW IF EXISTS `c_utilidad_detalle`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `c_utilidad_detalle` AS select `ventas_cab`.`VtaFvta` AS `vtafvta`,`ventas_cab`.`VtaNume` AS `vtanume`,sum((`ventas_detalle`.`DetCant` * `ventas_detalle`.`DetPrec`)) AS `venta`,sum((`ventas_detalle`.`DetCant` * `ventas_detalle`.`DetCSol`)) AS `costo`,sum(((`ventas_detalle`.`DetCant` * `ventas_detalle`.`DetPrec`) - (`ventas_detalle`.`DetCant` * `ventas_detalle`.`DetCSol`))) AS `Utilidad`,`ventas_cab`.`PCCodi` AS `Pccodi`,`prov_clientes`.`PCRucc` AS `pcrucc`,`prov_clientes`.`PCNomb` AS `pcnomb`,`ventas_cab`.`VtaEsta` AS `VtaEsta` from ((`ventas_cab` join `ventas_detalle`) join `prov_clientes`) where ((`ventas_cab`.`VtaOper` = `ventas_detalle`.`VtaOper`) and (`ventas_cab`.`PCCodi` = `prov_clientes`.`PCCodi`)) group by `ventas_cab`.`VtaOper`,`ventas_cab`.`VtaFvta`,`ventas_cab`.`VtaNume`,`ventas_cab`.`PCCodi`,`prov_clientes`.`PCRucc`,`prov_clientes`.`PCNomb`,`ventas_cab`.`VtaEsta` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `c_utilidad_resumen`
--

/*!50001 DROP VIEW IF EXISTS `c_utilidad_resumen`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `c_utilidad_resumen` AS select `ventas_cab`.`VtaFvta` AS `vtafvta`,sum((`ventas_detalle`.`DetCant` * `ventas_detalle`.`DetPrec`)) AS `venta`,sum((`ventas_detalle`.`DetCant` * `ventas_detalle`.`DetCSol`)) AS `costo`,sum(((`ventas_detalle`.`DetCant` * `ventas_detalle`.`DetPrec`) - (`ventas_detalle`.`DetCant` * `ventas_detalle`.`DetCSol`))) AS `Utilidad`,`ventas_cab`.`VtaEsta` AS `VtaEsta` from (`ventas_cab` join `ventas_detalle`) where (`ventas_cab`.`VtaOper` = `ventas_detalle`.`VtaOper`) group by `ventas_cab`.`VtaFvta`,`ventas_cab`.`VtaEsta` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `c_ventas_estadistica`
--

/*!50001 DROP VIEW IF EXISTS `c_ventas_estadistica`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `c_ventas_estadistica` AS select sum(`ventas_cab`.`VtaImpo`) AS `Importe`,`mes`.`mescodi` AS `mescodi`,`mes`.`mesnomb` AS `MesNomb` from (`ventas_cab` join `mes`) where (`ventas_cab`.`MesCodi` = `mes`.`mescodi`) group by `mes`.`mescodi`,`mes`.`mesnomb` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `c_ventas_mejor_cliente`
--

/*!50001 DROP VIEW IF EXISTS `c_ventas_mejor_cliente`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `c_ventas_mejor_cliente` AS select `ventas_cab`.`PCCodi` AS `pccodi`,`prov_clientes`.`PCRucc` AS `pcrucc`,`prov_clientes`.`PCNomb` AS `pcnomb`,sum(`ventas_cab`.`VtaImpo`) AS `Vta`,`ventas_cab`.`VtaFvta` AS `vtafvta`,`ventas_cab`.`MesCodi` AS `MesCodi`,`ventas_cab`.`EmpCodi` AS `EmpCodi`,`prov_clientes`.`TipCodi` AS `TipCodi` from (`ventas_cab` join `prov_clientes`) where (`ventas_cab`.`PCCodi` = `prov_clientes`.`PCCodi`) group by `ventas_cab`.`PCCodi`,`prov_clientes`.`PCRucc`,`prov_clientes`.`PCNomb`,`ventas_cab`.`VtaEsta`,`ventas_cab`.`EmpCodi`,`prov_clientes`.`TipCodi` having (`ventas_cab`.`VtaEsta` = 'V') order by sum(`ventas_cab`.`VtaImpo`) desc */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2020-01-27 17:12:26
