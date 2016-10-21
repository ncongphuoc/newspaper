/*
Navicat MySQL Data Transfer

Source Server         : vmw
Source Server Version : 50549
Source Host           : localhost:3306
Source Database       : news

Target Server Type    : MYSQL
Target Server Version : 50549
File Encoding         : 65001

Date: 2016-05-21 12:12:33
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
  `parent_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`cate_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tbl_categories
-- ----------------------------
INSERT INTO `tbl_categories` VALUES ('1', 'News', 'news', '1', '', '1463711490', '1', '1463711521', '1', 'Get the latest Discovery World News: international news, features and analysis from Africa, the Asia-Pacific, Europe, Latin America, the Middle East, South Asia, United States, Canada', 'Get the latest Discovery World News: international news, features and analysis from Africa, the Asia-Pacific, Europe, Latin America, the Middle East, South Asia, United States, Canada', 'Get the latest Discovery World News: international news, features and analysis from Africa, the Asia-Pacific, Europe, Latin America, the Middle East, South Asia, United States, Canada', null, '1', null, '0');
INSERT INTO `tbl_categories` VALUES ('2', 'americas', 'americas', '1', '', '1463829727', '1', '1463829916', '1', 'Find breaking news, world news and multimedia on Canada and South America with news from Mexico, Brazil, Colombia, Chile, Argentina, Peru and Venezuela.', 'El Salvador,Farabundo Marti National Liberation Front,David Munguia Payes,Gangs,United Nations,Susana Malcorra,Ban Ki-moon,Rationing and Allocation of Resources,Guanajuato (Mexico),Mexico,Arsenic,Fluorides,Water,San Antonio de Lourdes (Mexico),Water Pollu', 'Find breaking news, world news and multimedia on Canada and South America with news from Mexico, Brazil, Colombia, Chile, Argentina, Peru and Venezuela.', null, '1', null, '1');
INSERT INTO `tbl_categories` VALUES ('3', 'Africa', 'africa', '2', '', '1463830210', '1', null, null, 'Find breaking news, world news and multimedia on Africa from South Africa, Egypt, Ethiopia, Libya, Rwanda, Kenya, Morocco, Zimbabwe, Sudan and Algeria.', 'Find breaking news, world news and multimedia on Africa from South Africa, Egypt, Ethiopia, Libya, Rwanda, Kenya, Morocco, Zimbabwe, Sudan and Algeria.', 'Find breaking news, world news and multimedia on Africa from South Africa, Egypt, Ethiopia, Libya, Rwanda, Kenya, Morocco, Zimbabwe, Sudan and Algeria.', null, '1', null, '1');
INSERT INTO `tbl_categories` VALUES ('4', 'Asia', 'asia', '3', '', '1463830297', '1', null, null, 'Find breaking news, world news and multimedia on Asia with news on China, Japan, North Korea, South Korea, Vietnam, Indonesia, Malaysia, Taiwan and Cambodia.', 'Find breaking news, world news and multimedia on Asia with news on China, Japan, North Korea, South Korea, Vietnam, Indonesia, Malaysia, Taiwan and Cambodia.', 'Find breaking news, world news and multimedia on Asia with news on China, Japan, North Korea, South Korea, Vietnam, Indonesia, Malaysia, Taiwan and Cambodia.', null, '1', null, '1');
INSERT INTO `tbl_categories` VALUES ('5', 'Europe', 'europe', '4', '', '1463830370', '1', '1463830442', '1', 'Find breaking news, world news and multimedia on Europe.', 'Find breaking news, world news and multimedia on Europe.', 'Find breaking news, world news and multimedia on Europe.', null, '1', null, '1');
INSERT INTO `tbl_categories` VALUES ('6', 'Middle East', 'middle-east', '5', '', '1463830429', '1', null, null, 'Find breaking news, world news and multimedia on the Middle East with news on Iraq, Israel, Lebanon, Iran, Kuwait, Syria, Saudi Arabia and Jordan.', 'Find breaking news, world news and multimedia on the Middle East with news on Iraq, Israel, Lebanon, Iran, Kuwait, Syria, Saudi Arabia and Jordan.', 'Find breaking news, world news and multimedia on the Middle East with news on Iraq, Israel, Lebanon, Iran, Kuwait, Syria, Saudi Arabia and Jordan.', null, '1', null, '1');

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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

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
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tbl_logs
-- ----------------------------
INSERT INTO `tbl_logs` VALUES ('1', 'backend', 'category', 'add', '2', '1', '1463829727', '{\"cate_name\":\"americas\",\"cate_icon\":\"\",\"cate_slug\":\"americas\",\"cate_meta_title\":\"Find breaking news, world news and multimedia on Canada and South America with news from Mexico, Brazil, Colombia, Chile, Argentina, Peru and Venezuela.\",\"cate_meta_description\":\"Find breaking news, world news and multimedia on Canada and South America with news from Mexico, Brazil, Colombia, Chile, Argentina, Peru and Venezuela.\",\"cate_meta_keyword\":\"Boko Haram,Chibok (Nigeria),Nigeria,Terrorism,Kidnapping and Hostages,Nigeria,Chibok (Nigeria),Boko Haram,Ali, Amina,Women and Girls,Luka, Serah,Sex Crimes,Buhari, Muhammadu,Kidnapping and Hostages,Epidemics,Africa,Vaccination and Immunization,Angola,World Health Organization,China,Zika Virus,Yellow Fever,Nigeria,Boko Haram,Pregnancy and Childbirth,Women and Girls,Kidnapping and Hostages,Sex Crimes,Chibok (Nigeria),Boko Haram,Terrorism,Ali, Amina,Defense and Military Forces,Nigeria,Women and Girls,Buhari, Muhammadu,Kidnapping and Hostages,Rwanda,Berinkindi, Claver,Holst, Tora,Sweden,War Crimes, Genocide and Crimes Against Humanity,Tanzania,Terrorism,United States International Relations,United States Defense and Military Forces,Africa,Milley, Mark A,United States Army,Boko Haram,Chad,Military Aircraft,United States International Relations,Leahy, Patrick J,United States Defense and Military Forces,Buhari, Muhammadu,Obama, Barack,Arms Trade,Niger,Nigeria,Human Rights and Human Rights Violations,Cameroon,Johannesburg (South Africa),Pistorius, Oscar,Prostheses,Steenkamp,\",\"cate_sort\":1,\"cate_status\":\"1\",\"created_date\":1463829727,\"user_created\":1,\"parent_id\":1}');
INSERT INTO `tbl_logs` VALUES ('2', 'backend', 'category', 'edit', '2', '1', '1463829916', '{\"cate_name\":\"americas\",\"cate_icon\":\"\",\"cate_slug\":\"americas\",\"cate_meta_title\":\"Find breaking news, world news and multimedia on Canada and South America with news from Mexico, Brazil, Colombia, Chile, Argentina, Peru and Venezuela.\",\"cate_meta_description\":\"Find breaking news, world news and multimedia on Canada and South America with news from Mexico, Brazil, Colombia, Chile, Argentina, Peru and Venezuela.\",\"cate_meta_keyword\":\"El Salvador,Farabundo Marti National Liberation Front,David Munguia Payes,Gangs,United Nations,Susana Malcorra,Ban Ki-moon,Rationing and Allocation of Resources,Guanajuato (Mexico),Mexico,Arsenic,Fluorides,Water,San Antonio de Lourdes (Mexico),Water Pollution,Agriculture and Farming,San Antonio de Lourdes (Mexico),Shortages,Pregnancy and Childbirth,Birth Control and Family Planning,Abortion,Birth Defects,Zika Virus,Brazil,Microcephaly,Ecuador,Correa, Rafael,Earthquakes,Canada,Fort McMurray (Alberta),Evacuations and Evacuees,Wildfires,Canada,Brosseau, Ruth Ellen (1984- ),Trudeau, Justin,Conservative Party (Canada),New Democratic Party (Canada),Green Party (Canada),Legislatures and Parliaments,Fires and Firefighters,Fort McMurray (Alberta),Alberta (Canada),Wildfires,Oil Sands,Trudeau, Justin,Transgender and Transsexuals,Gender,Canada,Hate Crimes,Discrimination,Law and Legislation,Same-Sex Marriage, Civil Unions and Domestic Partnerships,Pena Nieto, Enrique,Mexico,Fires and Firefighters,Fort McMurray (Alberta),Alberta (Canada),Syncrude,Wildfires,Shortages,Antibiotics,Hospitals,Economic Conditions and Trends,Babies and Infants,Venezuela,Deaths (Fatalities),Maduro, Nicolas,Mexico,Fishing, Commercial,Dolphins and Porpoises,Smuggling,Poaching (Wildlife),Endangered and Extinct Species,International Trade and World Market,United Nations Children\'s Fund,Revolutionary Armed Forces of Colombia,Child Soldiers,Colombia,Bribery and Kickbacks,Rousseff, Dilma,Impeachment,Legislatures and Parliaments,Corruption (Institutional),Politics and Government,Brazil,Religion and Belief,Therapy and Rehabilitation,Mexico,Medicine and Health,Pilgrimages,Brazil,Rousseff, Dilma,Venezuela,Hospitals,Medicine and Health,Venezuela,Hospitals,Medicine and Health,Venezuela,Hospitals,Medicine and Health,Rousseff, Dilma,Brasilia (Brazil),Impeachment,Brazil,Impeachment,Rousseff, Dilma\",\"cate_sort\":1,\"cate_status\":\"1\",\"updated_date\":1463829916,\"user_updated\":1,\"parent_id\":1}');
INSERT INTO `tbl_logs` VALUES ('3', 'backend', 'category', 'add', '3', '1', '1463830210', '{\"cate_name\":\"Africa\",\"cate_icon\":\"\",\"cate_slug\":\"africa\",\"cate_meta_title\":\"Find breaking news, world news and multimedia on Africa from South Africa, Egypt, Ethiopia, Libya, Rwanda, Kenya, Morocco, Zimbabwe, Sudan and Algeria.\",\"cate_meta_description\":\"Find breaking news, world news and multimedia on Africa from South Africa, Egypt, Ethiopia, Libya, Rwanda, Kenya, Morocco, Zimbabwe, Sudan and Algeria.\",\"cate_meta_keyword\":\"Find breaking news, world news and multimedia on Africa from South Africa, Egypt, Ethiopia, Libya, Rwanda, Kenya, Morocco, Zimbabwe, Sudan and Algeria.\",\"cate_sort\":2,\"cate_status\":\"1\",\"created_date\":1463830210,\"user_created\":1,\"parent_id\":1}');
INSERT INTO `tbl_logs` VALUES ('4', 'backend', 'category', 'add', '4', '1', '1463830298', '{\"cate_name\":\"Asia\",\"cate_icon\":\"\",\"cate_slug\":\"asia\",\"cate_meta_title\":\"Find breaking news, world news and multimedia on Asia with news on China, Japan, North Korea, South Korea, Vietnam, Indonesia, Malaysia, Taiwan and Cambodia.\",\"cate_meta_description\":\"Find breaking news, world news and multimedia on Asia with news on China, Japan, North Korea, South Korea, Vietnam, Indonesia, Malaysia, Taiwan and Cambodia.\",\"cate_meta_keyword\":\"Find breaking news, world news and multimedia on Asia with news on China, Japan, North Korea, South Korea, Vietnam, Indonesia, Malaysia, Taiwan and Cambodia.\",\"cate_sort\":3,\"cate_status\":\"1\",\"created_date\":1463830297,\"user_created\":1,\"parent_id\":1}');
INSERT INTO `tbl_logs` VALUES ('5', 'backend', 'category', 'add', '5', '1', '1463830370', '{\"cate_name\":\"Europe - International News\",\"cate_icon\":\"\",\"cate_slug\":\"europe-international-news\",\"cate_meta_title\":\"Find breaking news, world news and multimedia on Europe.\",\"cate_meta_description\":\"Find breaking news, world news and multimedia on Europe.\",\"cate_meta_keyword\":\"Find breaking news, world news and multimedia on Europe.\",\"cate_sort\":4,\"cate_status\":\"1\",\"created_date\":1463830370,\"user_created\":1,\"parent_id\":0}');
INSERT INTO `tbl_logs` VALUES ('6', 'backend', 'category', 'add', '6', '1', '1463830429', '{\"cate_name\":\"Middle East\",\"cate_icon\":\"\",\"cate_slug\":\"middle-east\",\"cate_meta_title\":\"Find breaking news, world news and multimedia on the Middle East with news on Iraq, Israel, Lebanon, Iran, Kuwait, Syria, Saudi Arabia and Jordan.\",\"cate_meta_description\":\"Find breaking news, world news and multimedia on the Middle East with news on Iraq, Israel, Lebanon, Iran, Kuwait, Syria, Saudi Arabia and Jordan.\",\"cate_meta_keyword\":\"Find breaking news, world news and multimedia on the Middle East with news on Iraq, Israel, Lebanon, Iran, Kuwait, Syria, Saudi Arabia and Jordan.\",\"cate_sort\":5,\"cate_status\":\"1\",\"created_date\":1463830429,\"user_created\":1,\"parent_id\":1}');
INSERT INTO `tbl_logs` VALUES ('7', 'backend', 'category', 'edit', '5', '1', '1463830442', '{\"cate_name\":\"Europe\",\"cate_icon\":\"\",\"cate_slug\":\"europe\",\"cate_meta_title\":\"Find breaking news, world news and multimedia on Europe.\",\"cate_meta_description\":\"Find breaking news, world news and multimedia on Europe.\",\"cate_meta_keyword\":\"Find breaking news, world news and multimedia on Europe.\",\"cate_sort\":4,\"cate_status\":\"1\",\"updated_date\":1463830442,\"user_updated\":1,\"parent_id\":1}');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tbl_users
-- ----------------------------
INSERT INTO `tbl_users` VALUES ('1', 'admin', 'admin@discoveryworld.com', '0973531618', null, '34567890', null, null, '', '1', '1', '4297f44b13955235245b2497399d7a93', '1463764739', '192.168.134.1', 'Admin', null, null);
