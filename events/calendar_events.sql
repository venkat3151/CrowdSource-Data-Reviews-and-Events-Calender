
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

SET FOREIGN_KEY_CHECKS=0;
/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

DROP TABLE IF exists `tbl_admin`;
CREATE TABLE `tbl_admin` (
  `ADMIN_ID` int(8) AUTO_INCREMENT, 
  `EMAIL` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `PASSWORD` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `RANK` int(8) NOT NULL,
  `FULL_NAME` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `TEMP_TOKEN` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY(`ADMIN_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF exists `tbl_categories`;
CREATE TABLE `tbl_categories`(
	`CATEGORY_ID` int(8) auto_increment,
    `NAME` varchar(64) not null,
    `ICON` varchar(64) not null,
    `DESCRIPTION` varchar(1000) COLLATE utf8_unicode_ci,
    PRIMARY KEY(`CATEGORY_ID`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
INSERT INTO tbl_categories(NAME, ICON, DESCRIPTION)
VALUES ('Brainy', 'fas fa-brain', 'Brainy events make you smart'),
('Arty', 'fas fa-palette', 'Get your creativity flowing with Arty events' );

DROP TABLE IF exists `tbl_events`;
CREATE TABLE `tbl_events` (
  `ID` int(8) NOT NULL AUTO_INCREMENT,
  `NAME` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `VENUE` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `START_TIME` datetime NOT NULL,
  `END_TIME` datetime NOT NULL,
  `CATEGORY` varchar(64) COLLATE utf8_unicode_ci,
  `DESCRIPTION` varchar(1024) COLLATE utf8_unicode_ci NOT NULL,
  `LINK` varchar(1024) COLLATE utf8_unicode_ci NOT NULL,
  `COST` decimal NOT NULL,
  `PHONE` varchar(15),
  `EMAIL` varchar(128) COLLATE utf8_unicode_ci,
  `UB_CAMPUS_LOCATION` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
   `ADDITIONAL_FILE` mediumblob NOT NULL,
  `ADDITIONAL_FILE_SIZE` int(8),
  `ADDITIONAL_FILE_TYPE` varchar(128) COLLATE utf8_unicode_ci,
  `APPROVAL_STATUS` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `ADDED_BY` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `APPROVED_BY` int(8),
  PRIMARY KEY(`ID`),
  CONSTRAINT FK_tbl_event_approved_by FOREIGN KEY (`APPROVED_BY`) REFERENCES `tbl_admin` (`ADMIN_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF exists `tbl_event_categories`;
CREATE TABLE `tbl_event_categories` (
  `EVENT_ID` int(8) NOT NULL,
  `CATEGORY_ID` int(8) NOT NULL,
  PRIMARY KEY(`EVENT_ID`, `CATEGORY_ID`),
  CONSTRAINT FK_tbl_event_categories_eventID FOREIGN KEY (`EVENT_ID`) REFERENCES `tbl_events` (`ID`),
  CONSTRAINT FK_tbl_event_categories_categoryID FOREIGN KEY (`CATEGORY_ID`) REFERENCES `tbl_categories` (`CATEGORY_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF exists `tbl_event_contacts`;
CREATE TABLE `tbl_event_contacts` (
  `CONTACT_ID` int(8) not null AUTO_INCREMENT,
  `EVENT_ID` int(8) NOT NULL,
  `CONTACT_TYPE` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `PERSON_NAME` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `ADDITIONAL_INFO` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY(`CONTACT_ID`),
  CONSTRAINT `tbl_event_contacts_ibfk_1` FOREIGN KEY (`EVENT_ID`) REFERENCES `tbl_events` (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF exists `tbl_in_progress_events`;
CREATE TABLE `tbl_in_progress_events` (
  `ID` int(8) NOT NULL AUTO_INCREMENT,
  `NAME` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `VENUE` varchar(64) COLLATE utf8_unicode_ci,
  `START_TIME` datetime ,
  `END_TIME` datetime ,
  `CATEGORY` varchar(64) COLLATE utf8_unicode_ci,
  `DESCRIPTION` varchar(1024) COLLATE utf8_unicode_ci,
  `LINK` varchar(1024) COLLATE utf8_unicode_ci ,
  `COST` decimal,
  `PHONE` varchar(15),
  `EMAIL` varchar(128) COLLATE utf8_unicode_ci,
  `UB_CAMPUS_LOCATION` varchar(255) COLLATE utf8_unicode_ci,
    `ADDITIONAL_FILE` mediumblob NOT NULL,
  `ADDITIONAL_FILE_SIZE` int(8),
  `ADDITIONAL_FILE_TYPE` varchar(128) COLLATE utf8_unicode_ci,
  `APPROVAL_STATUS` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `ADDED_BY` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `ADMIN_ID` int(8),
  PRIMARY KEY(`ID`),
  CONSTRAINT FK_tbl_in_progress_event FOREIGN KEY (`ADMIN_ID`) REFERENCES `tbl_admin` (`ADMIN_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF exists `tbl_in_progress_event_categories`;
CREATE TABLE `tbl_in_progress_event_categories` (
  `EVENT_ID` int(8) NOT NULL,
  `CATEGORY_ID` int(8) NOT NULL,
  PRIMARY KEY(`EVENT_ID`, `CATEGORY_ID`),
  CONSTRAINT FK_tbl_in_progress_event_categories_eventID FOREIGN KEY (`EVENT_ID`) REFERENCES `tbl_in_progress_events` (`ID`),
  CONSTRAINT FK_tbl_in_progress_event_categories_categoryID FOREIGN KEY (`CATEGORY_ID`) REFERENCES `tbl_categories` (`CATEGORY_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF exists `tbl_in_progress_event_contacts`;
CREATE TABLE `tbl_in_progress_event_contacts` (
  `CONTACT_ID` int(8) not null AUTO_INCREMENT,
  `EVENT_ID` int(8) NOT NULL,
  `CONTACT_TYPE` varchar(64) COLLATE utf8_unicode_ci,
  `PERSON_NAME` varchar(64) COLLATE utf8_unicode_ci ,
  `ADDITIONAL_INFO` varchar(255) COLLATE utf8_unicode_ci,
  PRIMARY KEY(`CONTACT_ID`),
  CONSTRAINT `tbl_in_progress_event_contacts_ibfk_1` FOREIGN KEY (`EVENT_ID`) REFERENCES `tbl_in_progress_events` (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF exists `tbl_event_changes`;
CREATE TABLE `tbl_event_changes` (
  `ID` int(8) NOT NULL ,
  `NAME` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `VENUE` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `START_TIME` datetime NOT NULL,
  `END_TIME` datetime NOT NULL,
  `CATEGORY` varchar(64) COLLATE utf8_unicode_ci,
  `DESCRIPTION` varchar(1024) COLLATE utf8_unicode_ci NOT NULL,
  `LINK` varchar(1024) COLLATE utf8_unicode_ci NOT NULL,
  `COST` decimal NOT NULL,
  `PHONE` varchar(15),
  `EMAIL` varchar(128) COLLATE utf8_unicode_ci,
  `UB_CAMPUS_LOCATION` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `ADDITIONAL_FILE` mediumblob NOT NULL,
  `ADDITIONAL_FILE_SIZE` int(8),
  `ADDITIONAL_FILE_TYPE` varchar(128) COLLATE utf8_unicode_ci,
  `APPROVAL_STATUS` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `ADDED_BY` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `APPROVED_BY` int(8),
  PRIMARY KEY(`ID`),
  CONSTRAINT FK_tbl_event_changes_eventID  FOREIGN KEY (`ID`) REFERENCES `tbl_events`(`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF exists `tbl_event_category_changes`;
CREATE TABLE `tbl_event_category_changes` (
  `EVENT_ID` int(8) NOT NULL,
  `CATEGORY_ID` int(8) NOT NULL,
  PRIMARY KEY(`EVENT_ID`, `CATEGORY_ID`),
  CONSTRAINT FK_tbl_event_category_changes_eventID FOREIGN KEY (`EVENT_ID`) REFERENCES `tbl_event_changes` (`ID`),
  CONSTRAINT FK_tbl_event_category_changes_categoryID FOREIGN KEY (`CATEGORY_ID`) REFERENCES `tbl_categories` (`CATEGORY_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF exists `tbl_event_contact_changes`;
CREATE TABLE `tbl_event_contact_changes` (
  `CONTACT_ID` int(8) not null AUTO_INCREMENT,
  `EVENT_ID` int(8) NOT NULL,
  `CONTACT_TYPE` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `PERSON_NAME` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `ADDITIONAL_INFO` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY(`CONTACT_ID`),
  CONSTRAINT `tbl_event_contact_changes_ibfk_1` FOREIGN KEY (`EVENT_ID`) REFERENCES `tbl_event_changes` (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF exists  `tbl_roles`;
CREATE TABLE `tbl_roles` (
	`ROLE_ID` int(8) not null auto_increment,
    `ROLE_NAME` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
    `ROLE_DESCRIPTION` varchar(1000) COLLATE utf8_unicode_ci NOT NULL,
    PRIMARY KEY(`ROLE_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF exists `tbl_user_roles`;
CREATE TABLE `tbl_user_roles` (
    `USER_ID` int(8) not null, 
    `ROLE_ID` int(8) not null,
    PRIMARY KEY( `USER_ID`,`ROLE_ID`),
    CONSTRAINT `tbl_user_roles_ibfk_1` FOREIGN KEY (`ROLE_ID`) REFERENCES `tbl_roles` (`ROLE_ID`),
    CONSTRAINT `tbl_user_roles_ibfk_2` FOREIGN KEY (`USER_ID`) REFERENCES `tbl_admin` (`ADMIN_ID`)
    
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
SET FOREIGN_KEY_CHECKS=1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
