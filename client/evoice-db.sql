-- MySQL dump 10.11
--
-- Host: localhost    Database: evoice
-- ------------------------------------------------------
-- Server version	5.0.51b

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
-- Table structure for table `t_addressgroup`
--

DROP TABLE IF EXISTS `t_addressgroup`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `t_addressgroup` (
  `id` int(4) NOT NULL auto_increment,
  `UserId` int(4) default NULL,
  `GroupNo` varchar(100) default NULL,
  `GroupName` varchar(100) default NULL,
  `GroupCount` int(4) default NULL,
  `ProcessState` varchar(100) default NULL,
  `createDate` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `idx` int(4) NOT NULL default '0',
  `GroupType` int(4) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=726 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `t_autograph`
--

DROP TABLE IF EXISTS `t_autograph`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `t_autograph` (
  `id` int(4) NOT NULL auto_increment,
  `UserId` int(4) default NULL,
  `AutographNo` varchar(100) default NULL,
  `AutographName` varchar(100) default NULL,
  `AutographPwd` varchar(100) default NULL,
  `AutographFile` varchar(100) default NULL,
  `Description` varchar(100) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `t_ccalls`
--

DROP TABLE IF EXISTS `t_ccalls`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `t_ccalls` (
  `ccalls` int(11) NOT NULL default '0',
  PRIMARY KEY  (`ccalls`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `t_config`
--

DROP TABLE IF EXISTS `t_config`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `t_config` (
  `id` int(11) NOT NULL auto_increment,
  `webtitle` varchar(200) NOT NULL,
  `weburl` varchar(200) NOT NULL,
  `webname` varchar(500) NOT NULL,
  `webkeyword` varchar(500) NOT NULL,
  `webdes` varchar(500) NOT NULL,
  `webfoot` varchar(2000) NOT NULL,
  `webhomemenu` varchar(100) NOT NULL,
  `UserId` int(10) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `t_dic`
--

DROP TABLE IF EXISTS `t_dic`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `t_dic` (
  `id` int(4) NOT NULL auto_increment,
  `ClassId` varchar(100) NOT NULL,
  `ClassName` varchar(20) NOT NULL,
  `ClassType` int(4) NOT NULL,
  `idx` int(4) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=110 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `t_his_work`
--

DROP TABLE IF EXISTS `t_his_work`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `t_his_work` (
  `id` int(4) NOT NULL,
  `UserId` int(4) default NULL,
  `WorkNo` varchar(100) default NULL,
  `WorkType` int(4) default NULL,
  `SendTime` datetime default NULL,
  `WorkCount` int(4) default NULL,
  `OverCount` int(4) default NULL,
  `SuccessCount` int(4) default NULL,
  `Money` double default NULL,
  `WorkState` int(4) default '-1',
  `AddressSource` int(4) default NULL,
  `AddressGroupId` varchar(100) default NULL,
  `AddressFile` varchar(100) default NULL,
  `Title` varchar(100) default NULL,
  `SendTimeType` int(4) default NULL,
  `FixedTime` datetime default NULL,
  `IfEndTime` int(4) default NULL,
  `EndTime` datetime default NULL,
  `Level` int(4) default NULL,
  `WorkTimeSH1` int(4) default NULL,
  `WorkTimeSM1` int(4) default NULL,
  `WorkTimeEH1` int(4) default NULL,
  `WorkTimeEM1` int(4) default NULL,
  `WorkTimeSH2` int(4) default NULL,
  `WorkTimeSM2` int(4) default NULL,
  `IfVoiceTemplate` int(4) default NULL,
  `VoiceTemplateId` int(4) default NULL,
  `VoiceType` int(4) default NULL,
  `VoiceFile` varchar(100) default NULL,
  `TTS` varchar(100) default NULL,
  `IfClick` int(4) default NULL,
  `RepeatNum` int(4) default NULL,
  `ReturnNum` int(4) default NULL,
  `ComplainNum` int(4) default NULL,
  `ReturnVoiceType` int(4) default NULL,
  `ReturnVoiceFile` varchar(100) default NULL,
  `ReturnTTS` varchar(100) default NULL,
  `IfFax` int(4) default NULL,
  `FaxFile` varchar(100) default NULL,
  `Ifmessage` int(4) default NULL,
  `IfMessageS1` int(4) default NULL,
  `IfMessageS2` int(4) default NULL,
  `IfMessageS3` int(4) default NULL,
  `IfMessageS4` int(4) default NULL,
  `IfMessageS5` int(4) default NULL,
  `IfMessageS6` int(4) default NULL,
  `Message` varchar(100) default NULL,
  `CreateDate` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `WorkTimeEH2` int(4) NOT NULL,
  `WorkTimeEM2` int(4) NOT NULL,
  `ComplainAgents` varchar(100) NOT NULL default ' ',
  `baktime` varchar(20) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `UserId` (`UserId`),
  KEY `baktime` (`baktime`),
  KEY `CreateDate` (`CreateDate`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `t_his_work_detail`
--

DROP TABLE IF EXISTS `t_his_work_detail`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `t_his_work_detail` (
  `id` int(4) NOT NULL,
  `UserId` int(4) default NULL,
  `WorkId` int(4) default NULL,
  `TelNo` varchar(100) default NULL,
  `Receiver` varchar(100) default NULL,
  `SendTime` date default NULL,
  `TimeLength` int(4) default NULL,
  `SendNum` int(4) default NULL,
  `Money` double default NULL,
  `SendResult` int(4) default NULL,
  `CreateTime` datetime default NULL,
  `Linkman` varchar(100) default NULL,
  `Company` varchar(100) default NULL,
  `Dept` varchar(100) default NULL,
  `Position` varchar(100) default NULL,
  `Country` varchar(100) default NULL,
  `Province` varchar(100) default NULL,
  `City` varchar(100) default NULL,
  `Address` varchar(100) default NULL,
  `PostCode` varchar(100) default NULL,
  `Fax` varchar(100) default NULL,
  `Tel` varchar(100) default NULL,
  `HomeTel` varchar(100) default NULL,
  `Mobi` varchar(100) default NULL,
  `Email` varchar(100) default NULL,
  `Url` varchar(100) default NULL,
  `Description` varchar(100) default NULL,
  `KeyPress` varchar(10) NOT NULL,
  `dialstatus` char(15) default NULL,
  `dialtime` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `WorkId` (`WorkId`),
  KEY `UserId` (`UserId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `t_linkman`
--

DROP TABLE IF EXISTS `t_linkman`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `t_linkman` (
  `id` int(4) NOT NULL auto_increment,
  `UserId` int(4) default NULL,
  `GroupId` int(4) default NULL,
  `Linkman` varchar(100) default NULL,
  `Company` varchar(100) default NULL,
  `Dept` varchar(100) default NULL,
  `Position` varchar(100) default NULL,
  `Country` varchar(100) default NULL,
  `Province` varchar(100) default NULL,
  `City` varchar(100) default NULL,
  `Address` varchar(100) default NULL,
  `PostCode` varchar(100) default NULL,
  `Fax` varchar(100) default NULL,
  `Tel` varchar(100) default NULL,
  `HomeTel` varchar(100) default NULL,
  `Mobi` varchar(100) default NULL,
  `Email` varchar(100) default NULL,
  `Url` varchar(100) default NULL,
  `Description` varchar(100) default NULL,
  `CreateDate` timestamp NOT NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1089006 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `t_livelog`
--

DROP TABLE IF EXISTS `t_livelog`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `t_livelog` (
  `id` int(10) NOT NULL auto_increment,
  `host` varchar(20) character set utf8 NOT NULL,
  `num` int(4) NOT NULL,
  `createtime` timestamp NOT NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`),
  KEY `host` (`host`),
  KEY `createtime` (`createtime`)
) ENGINE=MyISAM AUTO_INCREMENT=1861200 DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `t_livetable`
--

DROP TABLE IF EXISTS `t_livetable`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `t_livetable` (
  `host` char(20) NOT NULL,
  `livecalls` int(11) NOT NULL default '0',
  `maxcalls` int(11) NOT NULL,
  PRIMARY KEY  (`host`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `t_log`
--

DROP TABLE IF EXISTS `t_log`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `t_log` (
  `id` bigint(20) NOT NULL auto_increment,
  `tdate` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `type` char(10) NOT NULL,
  `body` varchar(500) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=96714 DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `t_mobile_filter`
--

DROP TABLE IF EXISTS `t_mobile_filter`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `t_mobile_filter` (
  `id` int(4) NOT NULL,
  `MobileNumber` varchar(7) character set utf8 NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `MobileNumber` (`MobileNumber`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `t_recharge`
--

DROP TABLE IF EXISTS `t_recharge`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `t_recharge` (
  `id` int(4) NOT NULL auto_increment,
  `UserId` int(4) NOT NULL,
  `CustomerMoney` double NOT NULL default '0',
  `RechargeMoney` double NOT NULL default '0',
  `Description` varchar(200) character set utf8 NOT NULL,
  `OperatorId` int(4) NOT NULL,
  `CreateDate` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `TargetId` int(4) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=238 DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `t_seal`
--

DROP TABLE IF EXISTS `t_seal`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `t_seal` (
  `id` int(4) NOT NULL auto_increment,
  `UserId` int(4) default NULL,
  `SealNo` varchar(100) default NULL,
  `SealName` varchar(100) default NULL,
  `SealPwd` varchar(100) default NULL,
  `SealType` int(4) default NULL,
  `SealFile` varchar(100) default NULL,
  `Description` varchar(100) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `t_user`
--

DROP TABLE IF EXISTS `t_user`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `t_user` (
  `id` int(4) NOT NULL auto_increment,
  `username` varchar(100) default NULL,
  `password` varchar(100) default NULL,
  `realname` varchar(100) default NULL,
  `byname` varchar(100) default NULL,
  `areaNum` varchar(100) default NULL,
  `linkman` varchar(100) default NULL,
  `address` varchar(100) default NULL,
  `postcode` varchar(100) default NULL,
  `tel` varchar(100) default NULL,
  `fax` varchar(100) default NULL,
  `mobi` varchar(100) default NULL,
  `email` varchar(100) default NULL,
  `mainTel` varchar(100) default NULL,
  `sendLevel` int(4) default NULL,
  `faxMoney` double default '0',
  `messageMoney` double default '0',
  `voiceMoney` double NOT NULL default '0',
  `alertMoney` double default '0',
  `ip` varchar(255) default NULL,
  `loginNum` int(4) default NULL,
  `LastLogin` datetime default NULL,
  `cardType` int(4) default NULL,
  `cardNo` varchar(100) default NULL,
  `cardPic` varchar(100) default NULL,
  `faxIB` varchar(100) default NULL,
  `sendSound` int(4) default NULL,
  `ifDelInfo` int(4) default NULL,
  `state` int(4) default NULL,
  `upId` int(4) default NULL,
  `createTime` timestamp NULL default CURRENT_TIMESTAMP,
  `StatusId` int(4) NOT NULL,
  `FeeRate` varchar(10) default '0.03,6',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=143 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `t_voice_template`
--

DROP TABLE IF EXISTS `t_voice_template`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `t_voice_template` (
  `id` int(4) NOT NULL auto_increment,
  `UserId` int(4) default NULL,
  `TemplateName` varchar(100) default NULL,
  `VoiceType` int(4) default NULL,
  `VoiceFile` varchar(100) default NULL,
  `TTS` varchar(100) default NULL,
  `IfClick` int(4) default NULL,
  `RepeatNum` int(4) default NULL,
  `ReturnNum` int(4) default NULL,
  `ComplainNum` int(4) default NULL,
  `ReturnVoiceType` int(4) default NULL,
  `ReturnVoiceFile` varchar(100) default NULL,
  `ReturnTTS` varchar(100) default NULL,
  `CreateDate` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `idx` int(4) NOT NULL default '1',
  `ComplainAgents` varchar(100) NOT NULL,
  `Auditing` int(4) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=369 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `t_work`
--

DROP TABLE IF EXISTS `t_work`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `t_work` (
  `id` int(4) NOT NULL auto_increment,
  `UserId` int(4) default NULL,
  `WorkNo` varchar(100) default NULL,
  `WorkType` int(4) default NULL,
  `SendTime` datetime default NULL,
  `WorkCount` int(4) default NULL,
  `OverCount` int(4) default NULL,
  `SuccessCount` int(4) default NULL,
  `Money` double default NULL,
  `WorkState` int(11) default '0',
  `AddressSource` int(4) default NULL,
  `AddressGroupId` varchar(100) default NULL,
  `AddressFile` varchar(100) default NULL,
  `Title` varchar(100) default NULL,
  `SendTimeType` int(4) default NULL,
  `FixedTime` datetime default NULL,
  `IfEndTime` int(4) default NULL,
  `EndTime` datetime default NULL,
  `Level` int(4) default NULL,
  `WorkTimeSH1` int(4) default '0',
  `WorkTimeSM1` int(4) default '0',
  `WorkTimeEH1` int(4) default '0',
  `WorkTimeEM1` int(4) default '0',
  `WorkTimeSH2` int(4) default '0',
  `WorkTimeSM2` int(4) default '0',
  `IfVoiceTemplate` int(4) default NULL,
  `VoiceTemplateId` int(4) default NULL,
  `VoiceType` int(4) default NULL,
  `VoiceFile` varchar(100) default NULL,
  `TTS` varchar(100) default NULL,
  `IfClick` int(4) default NULL,
  `RepeatNum` int(4) default NULL,
  `ReturnNum` int(4) default NULL,
  `ComplainNum` int(4) default NULL,
  `ReturnVoiceType` int(4) default NULL,
  `ReturnVoiceFile` varchar(100) default NULL,
  `ReturnTTS` varchar(100) default NULL,
  `IfFax` int(4) default NULL,
  `FaxFile` varchar(100) default NULL,
  `Ifmessage` int(4) default NULL,
  `IfMessageS1` int(4) default NULL,
  `IfMessageS2` int(4) default NULL,
  `IfMessageS3` int(4) default NULL,
  `IfMessageS4` int(4) default NULL,
  `IfMessageS5` int(4) default NULL,
  `IfMessageS6` int(4) default NULL,
  `Message` varchar(100) default NULL,
  `CreateDate` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `WorkTimeEH2` int(4) NOT NULL default '0',
  `WorkTimeEM2` int(4) NOT NULL default '0',
  `ComplainAgents` varchar(100) NOT NULL default '0',
  `assignedbranch` varchar(10) NOT NULL,
  `AddressText` text NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `UserId` (`UserId`),
  KEY `CreateDate` (`CreateDate`)
) ENGINE=MyISAM AUTO_INCREMENT=1954 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `t_work_detail`
--

DROP TABLE IF EXISTS `t_work_detail`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `t_work_detail` (
  `id` int(4) NOT NULL auto_increment,
  `UserId` int(4) default NULL,
  `WorkId` int(4) default NULL,
  `TelNo` varchar(100) default NULL,
  `Receiver` varchar(100) default NULL,
  `SendTime` datetime default NULL,
  `TimeLength` int(4) default '0',
  `SendNum` int(4) default '0',
  `Money` double default '0',
  `SendResult` int(4) default '0',
  `KeyPress` char(1) NOT NULL default 'T',
  `answeredagent` char(20) default NULL,
  `Linkman` varchar(100) default NULL,
  `Company` varchar(100) default NULL,
  `Dept` varchar(100) default NULL,
  `Position` varchar(100) default NULL,
  `Country` varchar(100) default NULL,
  `Province` varchar(100) default NULL,
  `City` varchar(100) default NULL,
  `Address` varchar(100) default NULL,
  `PostCode` varchar(100) default NULL,
  `Fax` varchar(100) default NULL,
  `Tel` varchar(100) default NULL,
  `HomeTel` varchar(100) default NULL,
  `Mobi` varchar(100) default NULL,
  `Email` varchar(100) default NULL,
  `Url` varchar(100) default NULL,
  `Description` varchar(100) default NULL,
  `CreateTime` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `dialstatus` char(15) default NULL,
  `dialtime` int(11) default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3167138 DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `t_work_detail_spool`
--

DROP TABLE IF EXISTS `t_work_detail_spool`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `t_work_detail_spool` (
  `id` int(4) NOT NULL auto_increment,
  `UserId` int(4) default NULL,
  `WorkId` int(4) default NULL,
  `TelNo` varchar(100) default NULL,
  `SendTime` datetime default NULL,
  `TimeLength` int(4) default '0',
  `SendNum` int(4) default NULL,
  `Money` double default '0',
  `SendResult` int(4) default '0',
  `KeyPress` char(1) NOT NULL default 'T',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SET character_set_client = @saved_cs_client;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2013-03-28  2:31:03
