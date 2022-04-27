/*
SQLyog Community v12.2.1 (64 bit)
MySQL - 5.6.17 : Database - cobank
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`cobank` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `cobank`;

/*Table structure for table `bank` */

DROP TABLE IF EXISTS `bank`;

CREATE TABLE `bank` (
  `bank_id` int(11) NOT NULL AUTO_INCREMENT,
  `bank_name` varchar(200) DEFAULT NULL,
  `phone_no` varchar(50) DEFAULT NULL,
  `opening_date` datetime DEFAULT NULL,
  `opening_cash_balance` double DEFAULT NULL,
  `approved_by` varchar(200) DEFAULT NULL,
  `retirement_period` int(11) DEFAULT NULL,
  PRIMARY KEY (`bank_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `bank` */

insert  into `bank`(`bank_id`,`bank_name`,`phone_no`,`opening_date`,`opening_cash_balance`,`approved_by`,`retirement_period`) values 
(1,'mes society','9447380808','2016-08-10 00:00:00',54.5,NULL,NULL);

/*Table structure for table `mst_department` */

DROP TABLE IF EXISTS `mst_department`;

CREATE TABLE `mst_department` (
  `department_id` int(11) NOT NULL AUTO_INCREMENT,
  `department_name` varchar(50) NOT NULL,
  PRIMARY KEY (`department_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

/*Data for the table `mst_department` */

insert  into `mst_department`(`department_id`,`department_name`) values 
(1,'Accounts'),
(2,'Loan section'),
(3,'Recovery');

/*Table structure for table `mst_designation` */

DROP TABLE IF EXISTS `mst_designation`;

CREATE TABLE `mst_designation` (
  `designation_id` int(11) NOT NULL AUTO_INCREMENT,
  `designation` varchar(100) NOT NULL,
  PRIMARY KEY (`designation_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

/*Data for the table `mst_designation` */

insert  into `mst_designation`(`designation_id`,`designation`) values 
(1,'Secretary'),
(2,'Accontant'),
(3,'Senior clerk'),
(4,'Junior clerk'),
(5,'Peon');

/*Table structure for table `mst_staff` */

DROP TABLE IF EXISTS `mst_staff`;

CREATE TABLE `mst_staff` (
  `staff_id` int(11) NOT NULL AUTO_INCREMENT,
  `staff_code` int(4) NOT NULL,
  `staff_name` varchar(100) NOT NULL,
  `date_of_join` datetime NOT NULL,
  `designation_code` int(4) DEFAULT NULL,
  `department_code` int(4) DEFAULT NULL,
  `basic_pay` double DEFAULT NULL,
  PRIMARY KEY (`staff_id`),
  KEY `staff_id` (`staff_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `mst_staff` */

/*Table structure for table `user` */

DROP TABLE IF EXISTS `user`;

CREATE TABLE `user` (
  `user_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(50) DEFAULT NULL,
  `firstname` varchar(100) DEFAULT NULL,
  `lastname` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `status` tinyint(4) DEFAULT '1',
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `user` */

insert  into `user`(`user_id`,`username`,`password`,`firstname`,`lastname`,`email`,`status`) values 
(1,'test','test123','test','test',NULL,1);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
