/*
Navicat MySQL Data Transfer

Source Server         : vmw
Source Server Version : 50505
Source Host           : localhost:3306
Source Database       : news

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2016-05-20 23:11:40
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for tbl_categories
-- ----------------------------
DROP TABLE IF EXISTS `tbl_categories`;
CREATE TABLE `tbl_categories` (
  `cate_id` int(11) NOT NULL AUTO_INCREMENT,
  `cate_name` varchar(255) NOT NULL,
  `cate_slug` varchar(255) NOT NULL,
  `cate_sort` int(11) DEFAULT '999',
  `cate_icon` varchar(255) NOT NULL,
  `created_date` int(11) NOT NULL,
  `user_created` int(11) NOT NULL,
  `updated_date` int(11) DEFAULT NULL,
  `user_updated` int(11) DEFAULT NULL,
  `cate_meta_title` varchar(255) DEFAULT NULL,
  `cate_meta_keyword` varchar(255) DEFAULT NULL,
  `cate_meta_description` varchar(255) DEFAULT NULL,
  `cate_description` varchar(255) DEFAULT NULL,
  `cate_status` int(11) DEFAULT '1',
  `total_content` int(11) DEFAULT NULL,
  PRIMARY KEY (`cate_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tbl_categories
-- ----------------------------
INSERT INTO `tbl_categories` VALUES ('1', 'News', 'news', '1', '', '1463711490', '1', '1463711521', '1', 'Get the latest Discovery World News: international news, features and analysis from Africa, the Asia-Pacific, Europe, Latin America, the Middle East, South Asia, United States, Canada', 'Get the latest Discovery World News: international news, features and analysis from Africa, the Asia-Pacific, Europe, Latin America, the Middle East, South Asia, United States, Canada', 'Get the latest Discovery World News: international news, features and analysis from Africa, the Asia-Pacific, Europe, Latin America, the Middle East, South Asia, United States, Canada', null, '1', null);

-- ----------------------------
-- Table structure for tbl_contact
-- ----------------------------
DROP TABLE IF EXISTS `tbl_contact`;
CREATE TABLE `tbl_contact` (
  `contatc_id` int(11) NOT NULL AUTO_INCREMENT,
  `contact_title` varchar(255) NOT NULL,
  `contact_content` text NOT NULL,
  `created_date` int(11) DEFAULT NULL,
  `user_created` int(11) DEFAULT NULL,
  `user_info` text,
  `status` int(11) DEFAULT '0',
  `updated_date` int(11) DEFAULT NULL,
  `user_updated` int(11) DEFAULT NULL,
  PRIMARY KEY (`contatc_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tbl_contact
-- ----------------------------

-- ----------------------------
-- Table structure for tbl_contents
-- ----------------------------
DROP TABLE IF EXISTS `tbl_contents`;
CREATE TABLE `tbl_contents` (
  `cont_id` int(11) NOT NULL AUTO_INCREMENT,
  `cont_title` varchar(255) NOT NULL,
  `cont_slug` varchar(255) NOT NULL,
  `cont_detail` text NOT NULL,
  `created_date` int(11) NOT NULL,
  `user_created` int(11) NOT NULL,
  `updated_date` int(11) DEFAULT NULL,
  `user_updated` int(11) DEFAULT NULL,
  `cate_id` int(11) DEFAULT NULL,
  `meta_title` varchar(255) DEFAULT NULL,
  `cont_desciption` varchar(255) DEFAULT NULL,
  `meta_keyword` varchar(255) DEFAULT NULL,
  `cont_views` int(11) DEFAULT '0',
  `cont_status` int(255) DEFAULT '1',
  `cont_image` text,
  `modified_date` int(11) DEFAULT NULL,
  `cont_detail_text` text,
  `total_comment` int(11) DEFAULT '0',
  `method` varchar(255) DEFAULT NULL,
  `from_soucre` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`cont_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tbl_contents
-- ----------------------------

-- ----------------------------
-- Table structure for tbl_general
-- ----------------------------
DROP TABLE IF EXISTS `tbl_general`;
CREATE TABLE `tbl_general` (
  `gene_id` int(11) NOT NULL AUTO_INCREMENT,
  `gene_name` varchar(255) NOT NULL,
  `gene_slug` varchar(255) NOT NULL,
  `gene_parent` int(11) DEFAULT '0',
  `gene_content` varchar(255) DEFAULT NULL,
  `user_created` int(11) NOT NULL,
  `created_date` int(11) NOT NULL,
  `updated_date` int(11) DEFAULT NULL,
  `user_updated` int(11) DEFAULT NULL,
  `gene_status` int(11) DEFAULT NULL,
  PRIMARY KEY (`gene_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tbl_general
-- ----------------------------

-- ----------------------------
-- Table structure for tbl_groups
-- ----------------------------
DROP TABLE IF EXISTS `tbl_groups`;
CREATE TABLE `tbl_groups` (
  `group_id` int(11) NOT NULL AUTO_INCREMENT,
  `group_name` varchar(255) NOT NULL,
  `created_date` int(11) NOT NULL,
  `user_created` int(11) NOT NULL,
  `user_updated` int(11) DEFAULT NULL,
  `updated_date` int(11) DEFAULT NULL,
  `is_acp` int(11) NOT NULL DEFAULT '0' COMMENT '1: được vào backend',
  `is_full_access` int(11) NOT NULL DEFAULT '0' COMMENT '1: full quyền',
  `group_status` int(11) DEFAULT '1',
  PRIMARY KEY (`group_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tbl_groups
-- ----------------------------
INSERT INTO `tbl_groups` VALUES ('1', 'Adminstrator', '345678', '1', null, null, '1', '1', '1');

-- ----------------------------
-- Table structure for tbl_logs
-- ----------------------------
DROP TABLE IF EXISTS `tbl_logs`;
CREATE TABLE `tbl_logs` (
  `log_id` int(11) NOT NULL AUTO_INCREMENT,
  `module` varchar(255) NOT NULL,
  `controller` varchar(255) NOT NULL,
  `action` varchar(255) NOT NULL,
  `table_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_date` int(11) NOT NULL,
  `log_content` text,
  PRIMARY KEY (`log_id`)
) ENGINE=InnoDB AUTO_INCREMENT=159 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tbl_logs
-- ----------------------------
INSERT INTO `tbl_logs` VALUES ('156', 'backend', 'category', 'add', '102', '1', '1463710856', '{\"cate_name\":\"News\",\"cate_icon\":\"\",\"cate_slug\":\"news\",\"cate_meta_title\":\"Get the latest Discovery World News: international news, features and analysis from Africa, the Asia-Pacific, Europe, Latin America, the Middle East, South Asia, United States, Canada\",\"cate_meta_description\":\"Get the latest Discovery World News: international news, features and analysis from Africa, the Asia-Pacific, Europe, Latin America, the Middle East, South Asia, United States, Canada\",\"cate_meta_keyword\":\"Get the latest Discovery World News: international news, features and analysis from Africa, the Asia-Pacific, Europe, Latin America, the Middle East, South Asia, United States, Canada\",\"cate_sort\":1,\"cate_status\":\"1\",\"created_date\":1463710856,\"user_created\":1}');
INSERT INTO `tbl_logs` VALUES ('157', 'backend', 'category', 'add', '1', '1', '1463711490', '{\"cate_name\":\"News\",\"cate_icon\":\"\",\"cate_slug\":\"news\",\"cate_meta_title\":\"Get the latest Discovery World News: international news, features and analysis from Africa, the Asia-Pacific, Europe, Latin America, the Middle East, South Asia, United States, Canada\",\"cate_meta_description\":\"Get the latest Discovery World News: international news, features and analysis from Africa, the Asia-Pacific, Europe, Latin America, the Middle East, South Asia, United States, Canada\",\"cate_meta_keyword\":\"Get the latest Discovery World News: international news, features and analysis from Africa, the Asia-Pacific, Europe, Latin America, the Middle East, South Asia, United States, Canada\",\"cate_sort\":0,\"cate_status\":\"1\",\"created_date\":1463711490,\"user_created\":1}');
INSERT INTO `tbl_logs` VALUES ('158', 'backend', 'category', 'edit', '1', '1', '1463711521', '{\"cate_name\":\"News\",\"cate_icon\":\"\",\"cate_slug\":\"news\",\"cate_meta_title\":\"Get the latest Discovery World News: international news, features and analysis from Africa, the Asia-Pacific, Europe, Latin America, the Middle East, South Asia, United States, Canada\",\"cate_meta_description\":\"Get the latest Discovery World News: international news, features and analysis from Africa, the Asia-Pacific, Europe, Latin America, the Middle East, South Asia, United States, Canada\",\"cate_meta_keyword\":\"Get the latest Discovery World News: international news, features and analysis from Africa, the Asia-Pacific, Europe, Latin America, the Middle East, South Asia, United States, Canada\",\"cate_sort\":1,\"cate_status\":\"1\",\"updated_date\":1463711521,\"user_updated\":1}');

-- ----------------------------
-- Table structure for tbl_permisions
-- ----------------------------
DROP TABLE IF EXISTS `tbl_permisions`;
CREATE TABLE `tbl_permisions` (
  `perm_id` int(11) NOT NULL AUTO_INCREMENT,
  `module` varchar(255) NOT NULL,
  `controller` varchar(255) NOT NULL,
  `action` varchar(255) NOT NULL,
  `group_id` int(11) NOT NULL,
  `created_date` int(11) NOT NULL,
  `user_created` int(11) NOT NULL,
  `updated_date` int(11) DEFAULT NULL,
  `user_updated` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `perm_status` int(11) DEFAULT '1',
  PRIMARY KEY (`perm_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tbl_permisions
-- ----------------------------

-- ----------------------------
-- Table structure for tbl_subcribes
-- ----------------------------
DROP TABLE IF EXISTS `tbl_subcribes`;
CREATE TABLE `tbl_subcribes` (
  `sub_id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  `created_date` int(11) NOT NULL,
  PRIMARY KEY (`sub_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tbl_subcribes
-- ----------------------------

-- ----------------------------
-- Table structure for tbl_tags
-- ----------------------------
DROP TABLE IF EXISTS `tbl_tags`;
CREATE TABLE `tbl_tags` (
  `tag_id` int(11) NOT NULL AUTO_INCREMENT,
  `tag_name` varchar(255) NOT NULL,
  `tag_slug` varchar(255) NOT NULL,
  `user_created` int(11) NOT NULL,
  `created_date` int(11) NOT NULL,
  `updated_date` int(11) DEFAULT NULL,
  `user_updated` int(11) DEFAULT NULL,
  PRIMARY KEY (`tag_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tbl_tags
-- ----------------------------

-- ----------------------------
-- Table structure for tbl_users
-- ----------------------------
DROP TABLE IF EXISTS `tbl_users`;
CREATE TABLE `tbl_users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(255) NOT NULL,
  `user_email` varchar(255) NOT NULL,
  `user_phone` varchar(255) NOT NULL,
  `user_created` int(11) DEFAULT NULL,
  `created_date` int(11) NOT NULL,
  `user_updated` int(11) DEFAULT NULL,
  `updated_date` int(11) DEFAULT NULL,
  `user_avatar` text,
  `user_status` int(11) DEFAULT '1' COMMENT '1: đang hoạt động || 0 : block || -1: đã xóa',
  `group_id` int(11) NOT NULL,
  `user_password` varchar(255) NOT NULL,
  `user_last_login` int(11) DEFAULT NULL,
  `user_login_ip` varchar(255) DEFAULT NULL,
  `user_fullname` varchar(255) DEFAULT NULL,
  `user_gender` int(11) DEFAULT NULL,
  `user_birthday` int(11) DEFAULT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tbl_users
-- ----------------------------
INSERT INTO `tbl_users` VALUES ('1', 'admin', 'admin@discoveryworld.com', '0973531618', null, '34567890', null, null, '', '1', '1', '4297f44b13955235245b2497399d7a93', '1463700343', '192.168.29.1', 'Admin', null, null);
