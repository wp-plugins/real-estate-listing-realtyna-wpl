ALTER TABLE `#__wpl_properties` CHANGE `mls_id` `mls_id` VARCHAR( 30 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;
INSERT INTO `#__wpl_extensions` (`id`, `type`, `title`, `parent`, `description`, `enabled`, `param1`, `param2`, `param3`, `param4`, `param5`, `params`, `editable`, `index`, `client`) VALUES
(99, 'style', 'Googlefont', 0, '', 1, 'wpl-google-font', 'http://fonts.googleapis.com/css?family=Droid+Serif:400,700,400italic,700italic|Open+Sans:300italic,400italic,600italic,700italic,400,300,600,700|Scada:400italic,700italic,400,700|Archivo+Narrow:400,40', '', '', '1', '', 0, 35.00, 2);

INSERT INTO `#__wpl_extensions` (`id`, `type`, `title`, `parent`, `description`, `enabled`, `param1`, `param2`, `param3`, `param4`, `param5`, `params`, `editable`, `index`, `client`) VALUES
(31, 'shortcode', 'my profile shortcode', 0, 'it used for showing my profile', 1, 'wpl_my_profile', 'wpl_html->load_profile_wizard', '', '', '', '', 0, 99.99, 2);

INSERT INTO `#__wpl_extensions` (`id`, `type`, `title`, `parent`, `description`, `enabled`, `param1`, `param2`, `param3`, `param4`, `param5`, `params`, `editable`, `index`, `client`) VALUES
(34, 'shortcode', 'Profile show shortcode', 0, 'it used for showing a profile', 1, 'wpl_profile_show', 'wpl_controller->f:profile_show:display', '', '', '', '', 0, 99.99, 2);

INSERT INTO `#__wpl_extensions` (`id`, `type`, `title`, `parent`, `description`, `enabled`, `param1`, `param2`, `param3`, `param4`, `param5`, `params`, `editable`, `index`, `client`) VALUES
(32, 'shortcode', 'Add/Edit listing shortcode', 0, 'it used for showing Add/Edit listing view', 1, 'wpl_add_edit_listing', 'wpl_html->load_add_edit_listing', '', '', '', '', 0, 99.99, 2),
(33, 'shortcode', 'Listing Manager shortcode', 0, 'it used for showing Listing Manager', 1, 'wpl_listing_manager', 'wpl_html->load_listing_manager', '', '', '', '', 0, 99.99, 2);

INSERT INTO `#__wpl_extensions` (`id`, `type`, `title`, `parent`, `description`, `enabled`, `param1`, `param2`, `param3`, `param4`, `param5`, `params`, `editable`, `index`, `client`) VALUES
(20, 'widget', 'WPL Agents Widget', 0, '', 1, 'widgets.agents.main', 'widgets_init', 'WPL_agents_widget', '', '', '', 0, 99.99, 2);

INSERT INTO `#__wpl_cronjobs` (`id`, `cronjob_name`, `period`, `class_location`, `class_name`, `function_name`, `params`, `enabled`, `latest_run`) VALUES
(3, 'Check All Updates', 24, 'global', 'wpl_global', 'check_all_update', '', 1, '2014-04-05 13:19:29');

UPDATE `#__wpl_cronjobs` SET `cronjob_name`='Remove Expired tmp Directories' WHERE `id`='2';

ALTER TABLE `#__wpl_properties` CHANGE `deleted` `deleted` TINYINT( 1 ) NOT NULL DEFAULT '0';
ALTER TABLE `#__wpl_properties` CHANGE `att_numb` `att_numb` MEDIUMINT( 9 ) NOT NULL DEFAULT '0',
CHANGE `sent_numb` `sent_numb` MEDIUMINT( 9 ) NOT NULL DEFAULT '0',
CHANGE `contact_numb` `contact_numb` MEDIUMINT( 9 ) NOT NULL DEFAULT '0',
CHANGE `location5_id` `location5_id` INT( 11 ) NOT NULL DEFAULT '0',
CHANGE `location6_id` `location6_id` INT( 11 ) NOT NULL DEFAULT '0',
CHANGE `location7_id` `location7_id` INT( 11 ) NOT NULL DEFAULT '0',
CHANGE `price_unit` `price_unit` INT( 11 ) NOT NULL DEFAULT '0',
CHANGE `price_si` `price_si` DOUBLE NOT NULL DEFAULT '0',
CHANGE `price_period` `price_period` SMALLINT( 6 ) NOT NULL DEFAULT '0',
CHANGE `rooms` `rooms` FLOAT NOT NULL DEFAULT '0',
CHANGE `living_area_unit` `living_area_unit` INT( 11 ) NOT NULL DEFAULT '0',
CHANGE `living_area_si` `living_area_si` FLOAT NOT NULL DEFAULT '0',
CHANGE `lot_area_unit` `lot_area_unit` INT( 11 ) NOT NULL DEFAULT '0';

ALTER TABLE `#__wpl_properties` CHANGE `expire_days` `expire_days` INT( 11 ) NOT NULL DEFAULT '0',
CHANGE `visit_time` `visit_time` MEDIUMINT( 9 ) NOT NULL DEFAULT '0',
CHANGE `build_year` `build_year` INT( 11 ) NOT NULL DEFAULT '0',
CHANGE `zip_id` `zip_id` INT( 11 ) NOT NULL DEFAULT '0',
CHANGE `sp_featured` `sp_featured` TINYINT( 4 ) NOT NULL DEFAULT '0',
CHANGE `sp_hot` `sp_hot` TINYINT( 4 ) NOT NULL DEFAULT '0',
CHANGE `sp_openhouse` `sp_openhouse` TINYINT( 4 ) NOT NULL DEFAULT '0',
CHANGE `sp_forclosure` `sp_forclosure` TINYINT( 4 ) NOT NULL DEFAULT '0',
CHANGE `field_7` `field_7` INT( 11 ) NOT NULL DEFAULT '0',
CHANGE `half_bathrooms` `half_bathrooms` FLOAT NOT NULL DEFAULT '0',
CHANGE `field_55` `field_55` FLOAT NOT NULL DEFAULT '0';

ALTER TABLE `#__wpl_properties` DROP `mls_server_id`, DROP `mls_class_id`, DROP `mls_query_id`;
ALTER TABLE `#__wpl_properties` CHANGE `add_date` `add_date` DATETIME NULL, CHANGE `visit_date` `visit_date` DATETIME NULL;
ALTER TABLE `#__wpl_properties` CHANGE `textsearch` `textsearch` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL, CHANGE `rendered` `rendered` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL, CHANGE `alias` `alias` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL;

UPDATE `#__wpl_dbst_types` SET `queries_add`='ALTER TABLE `#__[TABLE_NAME]` ADD `field_[FIELD_ID]` double NULL; ALTER TABLE `#__[TABLE_NAME]` ADD `field_[FIELD_ID]_si` double NULL; ALTER TABLE `#__[TABLE_NAME]` ADD `field_[FIELD_ID]_unit` int NULL; UPDATE #__wpl_dbst SET `table_name`=''[TABLE_NAME]'', `table_column`=''field_[FIELD_ID]'' WHERE id=[FIELD_ID];' WHERE `id`='10';
UPDATE `#__wpl_dbst_types` SET `queries_add`='ALTER TABLE `#__[TABLE_NAME]` ADD `field_[FIELD_ID]` double NULL; ALTER TABLE `#__[TABLE_NAME]` ADD `field_[FIELD_ID]_si` double NULL; ALTER TABLE `#__[TABLE_NAME]` ADD `field_[FIELD_ID]_unit` int NULL; UPDATE #__wpl_dbst SET `table_name`=''[TABLE_NAME]'', `table_column`=''field_[FIELD_ID]'' WHERE id=[FIELD_ID];' WHERE `id`='11';
UPDATE `#__wpl_dbst_types` SET `queries_add`='ALTER TABLE `#__[TABLE_NAME]` ADD `field_[FIELD_ID]` double NULL; ALTER TABLE `#__[TABLE_NAME]` ADD `field_[FIELD_ID]_si` double NULL; ALTER TABLE `#__[TABLE_NAME]` ADD `field_[FIELD_ID]_unit` int NULL; UPDATE #__wpl_dbst SET `table_name`=''[TABLE_NAME]'', `table_column`=''field_[FIELD_ID]'' WHERE id=[FIELD_ID];' WHERE `id`='9';
UPDATE `#__wpl_dbst_types` SET `queries_add`='ALTER TABLE `#__[TABLE_NAME]` ADD `field_[FIELD_ID]` double NULL; ALTER TABLE `#__[TABLE_NAME]` ADD `field_[FIELD_ID]_si` double NULL; ALTER TABLE `#__[TABLE_NAME]` ADD `field_[FIELD_ID]_unit` int NULL; UPDATE #__wpl_dbst SET `table_name`=''[TABLE_NAME]'', `table_column`=''field_[FIELD_ID]'' WHERE id=[FIELD_ID];' WHERE `id`='8';
UPDATE `#__wpl_dbst_types` SET `queries_add`='ALTER TABLE `#__[TABLE_NAME]` ADD `n_[FIELD_ID]` tinyint(4) NULL; ALTER TABLE `#__[TABLE_NAME]` ADD `n_[FIELD_ID]_distance` int NULL; ALTER TABLE `#__[TABLE_NAME]` ADD `n_[FIELD_ID]_distance_by` tinyint(4) NULL; UPDATE #__wpl_dbst SET `table_name`=''[TABLE_NAME]'', `table_column`=''n_[FIELD_ID]'' WHERE id=[FIELD_ID];' WHERE `id`='7';
UPDATE `#__wpl_dbst_types` SET `queries_add`='ALTER TABLE `#__[TABLE_NAME]` ADD `field_[FIELD_ID]` text NULL; UPDATE #__wpl_dbst SET `table_name`=''[TABLE_NAME]'', `table_column`=''field_[FIELD_ID]'' WHERE id=[FIELD_ID];' WHERE `id`='5';
UPDATE `#__wpl_dbst_types` SET `queries_add`='ALTER TABLE `#__[TABLE_NAME]` ADD `field_[FIELD_ID]` int(11) NULL; UPDATE #__wpl_dbst SET `table_name`=''[TABLE_NAME]'', `table_column`=''field_[FIELD_ID]'' WHERE id=[FIELD_ID];' WHERE `id`='3';
UPDATE `#__wpl_dbst_types` SET `queries_add`='ALTER TABLE `#__[TABLE_NAME]` ADD `f_[FIELD_ID]_options` text NULL; ALTER TABLE `#__[TABLE_NAME]` ADD `f_[FIELD_ID]` tinyint(4) NULL; UPDATE #__wpl_dbst SET `table_name`=''[TABLE_NAME]'', `table_column`=''f_[FIELD_ID]'' WHERE id=[FIELD_ID];' WHERE `id`='4';
UPDATE `#__wpl_dbst_types` SET `queries_add`='ALTER TABLE `#__[TABLE_NAME]` ADD `field_[FIELD_ID]` varchar(50) NULL; UPDATE #__wpl_dbst SET `table_name`=''[TABLE_NAME]'', `table_column`=''field_[FIELD_ID]'' WHERE id=[FIELD_ID];' WHERE `id`='1';
UPDATE `#__wpl_dbst_types` SET `queries_add`='ALTER TABLE `#__[TABLE_NAME]` ADD `field_[FIELD_ID]` float NULL; UPDATE #__wpl_dbst SET `table_name`=''[TABLE_NAME]'', `table_column`=''field_[FIELD_ID]'' WHERE id=[FIELD_ID];' WHERE `id`='2';
UPDATE `#__wpl_dbst_types` SET `queries_add`='ALTER TABLE `#__[TABLE_NAME]` ADD `field_[FIELD_ID]` date NULL; UPDATE #__wpl_dbst SET `table_name`=''[TABLE_NAME]'', `table_column`=''field_[FIELD_ID]'' WHERE id=[FIELD_ID];' WHERE `id`='12';
UPDATE `#__wpl_dbst_types` SET `queries_add`='ALTER TABLE `#__[TABLE_NAME]` ADD `field_[FIELD_ID]` datetime NULL; UPDATE #__wpl_dbst SET `table_name`=''[TABLE_NAME]'', `table_column`=''field_[FIELD_ID]'' WHERE id=[FIELD_ID];' WHERE `id`='13';

ALTER TABLE `#__wpl_properties` CHANGE `location1_name` `location1_name` VARCHAR( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `location2_name` `location2_name` VARCHAR( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `location3_name` `location3_name` VARCHAR( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `location4_name` `location4_name` VARCHAR( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `location5_name` `location5_name` VARCHAR( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `location6_name` `location6_name` VARCHAR( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `location7_name` `location7_name` VARCHAR( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL;

ALTER TABLE `#__wpl_properties` CHANGE `googlemap_title` `googlemap_title` VARCHAR( 30 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `zip_name` `zip_name` VARCHAR( 20 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL;

ALTER TABLE `#__wpl_properties` CHANGE `post_code` `post_code` VARCHAR( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `description` `description` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `meta_description` `meta_description` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `meta_keywords` `meta_keywords` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `street` `street` VARCHAR( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `street_no` `street_no` VARCHAR( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL;

ALTER TABLE `#__wpl_properties` CHANGE `property_title` `property_title` VARCHAR( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `field_42` `field_42` VARCHAR( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `field_312` `field_312` VARCHAR( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `ref_id` `ref_id` VARCHAR( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `field_54` `field_54` VARCHAR( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL;

ALTER TABLE `#__wpl_properties` CHANGE `n_100` `n_100` TINYINT( 4 ) NULL ,
CHANGE `n_100_distance` `n_100_distance` INT( 11 ) NULL ,
CHANGE `n_100_distance_by` `n_100_distance_by` TINYINT( 4 ) NULL ,
CHANGE `n_101` `n_101` TINYINT( 4 ) NULL ,
CHANGE `n_101_distance` `n_101_distance` INT( 11 ) NULL ,
CHANGE `n_101_distance_by` `n_101_distance_by` TINYINT( 4 ) NULL;

ALTER TABLE `#__wpl_properties` CHANGE `n_102` `n_102` TINYINT( 4 ) NULL ,
CHANGE `n_102_distance` `n_102_distance` INT( 11 ) NULL ,
CHANGE `n_102_distance_by` `n_102_distance_by` TINYINT( 4 ) NULL;

ALTER TABLE `#__wpl_properties` CHANGE `n_103` `n_103` TINYINT( 4 ) NULL ,
CHANGE `n_103_distance` `n_103_distance` INT( 11 ) NULL ,
CHANGE `n_103_distance_by` `n_103_distance_by` TINYINT( 4 ) NULL ,
CHANGE `n_105` `n_105` TINYINT( 4 ) NULL ,
CHANGE `n_105_distance` `n_105_distance` INT( 11 ) NULL ,
CHANGE `n_105_distance_by` `n_105_distance_by` TINYINT( 4 ) NULL;

ALTER TABLE `#__wpl_properties` CHANGE `n_106` `n_106` TINYINT( 4 ) NULL ,
CHANGE `n_106_distance` `n_106_distance` INT( 11 ) NULL ,
CHANGE `n_106_distance_by` `n_106_distance_by` TINYINT( 4 ) NULL ,
CHANGE `n_107` `n_107` TINYINT( 4 ) NULL ,
CHANGE `n_107_distance` `n_107_distance` INT( 11 ) NULL ,
CHANGE `n_107_distance_by` `n_107_distance_by` TINYINT( 4 ) NULL;

ALTER TABLE `#__wpl_properties` CHANGE `n_108` `n_108` TINYINT( 4 ) NULL ,
CHANGE `n_108_distance` `n_108_distance` INT( 11 ) NULL ,
CHANGE `n_108_distance_by` `n_108_distance_by` TINYINT( 4 ) NULL ,
CHANGE `n_109` `n_109` TINYINT( 4 ) NULL ,
CHANGE `n_109_distance` `n_109_distance` INT( 11 ) NULL ,
CHANGE `n_109_distance_by` `n_109_distance_by` TINYINT( 4 ) NULL;

ALTER TABLE `#__wpl_properties` CHANGE `n_110` `n_110` TINYINT( 4 ) NULL ,
CHANGE `n_110_distance` `n_110_distance` INT( 11 ) NULL ,
CHANGE `n_110_distance_by` `n_110_distance_by` TINYINT( 4 ) NULL ,
CHANGE `n_111` `n_111` TINYINT( 4 ) NULL ,
CHANGE `n_111_distance` `n_111_distance` INT( 11 ) NULL ,
CHANGE `n_111_distance_by` `n_111_distance_by` TINYINT( 4 ) NULL;

ALTER TABLE `#__wpl_properties` CHANGE `n_112` `n_112` TINYINT( 4 ) NULL ,
CHANGE `n_112_distance` `n_112_distance` INT( 11 ) NULL ,
CHANGE `n_112_distance_by` `n_112_distance_by` TINYINT( 4 ) NULL ,
CHANGE `n_113` `n_113` TINYINT( 4 ) NULL ,
CHANGE `n_113_distance` `n_113_distance` INT( 11 ) NULL ,
CHANGE `n_113_distance_by` `n_113_distance_by` TINYINT( 4 ) NULL;

ALTER TABLE `#__wpl_properties` CHANGE `n_114` `n_114` TINYINT( 4 ) NULL ,
CHANGE `n_114_distance` `n_114_distance` INT( 11 ) NULL ,
CHANGE `n_114_distance_by` `n_114_distance_by` TINYINT( 4 ) NULL ,
CHANGE `n_115` `n_115` TINYINT( 4 ) NULL ,
CHANGE `n_115_distance` `n_115_distance` INT( 11 ) NULL ,
CHANGE `n_115_distance_by` `n_115_distance_by` TINYINT( 4 ) NULL;

ALTER TABLE `#__wpl_properties` CHANGE `f_130_options` `f_130_options` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `f_130` `f_130` TINYINT( 4 ) NULL ,
CHANGE `f_131_options` `f_131_options` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `f_131` `f_131` TINYINT( 4 ) NULL ,
CHANGE `f_132_options` `f_132_options` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `f_132` `f_132` TINYINT( 4 ) NULL ,
CHANGE `f_133_options` `f_133_options` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `f_133` `f_133` TINYINT( 4 ) NULL ,
CHANGE `f_134_options` `f_134_options` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `f_134` `f_134` TINYINT( 4 ) NULL;

ALTER TABLE `#__wpl_properties` CHANGE `f_135_options` `f_135_options` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `f_135` `f_135` TINYINT( 4 ) NULL ,
CHANGE `f_136_options` `f_136_options` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `f_136` `f_136` TINYINT( 4 ) NULL ,
CHANGE `f_137_options` `f_137_options` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `f_137` `f_137` TINYINT( 4 ) NULL ,
CHANGE `f_138_options` `f_138_options` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `f_138` `f_138` TINYINT( 4 ) NULL ,
CHANGE `f_139_options` `f_139_options` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `f_139` `f_139` TINYINT( 4 ) NULL;

ALTER TABLE `#__wpl_properties` CHANGE `f_140_options` `f_140_options` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `f_140` `f_140` TINYINT( 4 ) NULL ,
CHANGE `f_141_options` `f_141_options` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `f_141` `f_141` TINYINT( 4 ) NULL ,
CHANGE `f_142_options` `f_142_options` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `f_142` `f_142` TINYINT( 4 ) NULL ,
CHANGE `f_143_options` `f_143_options` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `f_143` `f_143` TINYINT( 4 ) NULL ,
CHANGE `f_144_options` `f_144_options` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `f_144` `f_144` TINYINT( 4 ) NULL;

ALTER TABLE `#__wpl_properties` CHANGE `f_146_options` `f_146_options` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `f_146` `f_146` TINYINT( 4 ) NULL ,
CHANGE `f_147_options` `f_147_options` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `f_147` `f_147` TINYINT( 4 ) NULL ,
CHANGE `f_148_options` `f_148_options` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `f_148` `f_148` TINYINT( 4 ) NULL ,
CHANGE `f_149_options` `f_149_options` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `f_149` `f_149` TINYINT( 4 ) NULL ,
CHANGE `f_150_options` `f_150_options` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `f_150` `f_150` TINYINT( 4 ) NULL;

ALTER TABLE `#__wpl_properties` CHANGE `f_151_options` `f_151_options` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `f_151` `f_151` TINYINT( 4 ) NULL ,
CHANGE `f_152_options` `f_152_options` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `f_152` `f_152` TINYINT( 4 ) NULL ,
CHANGE `f_153_options` `f_153_options` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `f_153` `f_153` TINYINT( 4 ) NULL ,
CHANGE `f_154_options` `f_154_options` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `f_154` `f_154` TINYINT( 4 ) NULL ,
CHANGE `f_155_options` `f_155_options` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `f_155` `f_155` TINYINT( 4 ) NULL;

ALTER TABLE `#__wpl_properties` CHANGE `f_156_options` `f_156_options` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `f_156` `f_156` TINYINT( 4 ) NULL ,
CHANGE `f_157_options` `f_157_options` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `f_157` `f_157` TINYINT( 4 ) NULL ,
CHANGE `f_158_options` `f_158_options` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `f_158` `f_158` TINYINT( 4 ) NULL ,
CHANGE `f_159_options` `f_159_options` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `f_159` `f_159` TINYINT( 4 ) NULL ,
CHANGE `f_160_options` `f_160_options` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `f_160` `f_160` TINYINT( 4 ) NULL;

ALTER TABLE `#__wpl_properties` CHANGE `f_161_options` `f_161_options` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `f_161` `f_161` TINYINT( 4 ) NULL ,
CHANGE `f_162_options` `f_162_options` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `f_162` `f_162` TINYINT( 4 ) NULL ,
CHANGE `f_163_options` `f_163_options` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `f_163` `f_163` TINYINT( 4 ) NULL ,
CHANGE `f_164_options` `f_164_options` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `f_164` `f_164` TINYINT( 4 ) NULL ,
CHANGE `f_165_options` `f_165_options` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `f_165` `f_165` TINYINT( 4 ) NULL;

ALTER TABLE `#__wpl_properties` CHANGE `f_166_options` `f_166_options` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `f_166` `f_166` TINYINT( 4 ) NULL ,
CHANGE `f_167_options` `f_167_options` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `f_167` `f_167` TINYINT( 4 ) NULL ,
CHANGE `f_168_options` `f_168_options` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `f_168` `f_168` TINYINT( 4 ) NULL ,
CHANGE `f_169_options` `f_169_options` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `f_169` `f_169` TINYINT( 4 ) NULL;

ALTER TABLE `#__wpl_dbst` CHANGE `name` `name` VARCHAR( 150 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `type` `type` VARCHAR( 20 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `options` `options` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `enabled` `enabled` TINYINT( 4 ) NOT NULL DEFAULT '1' COMMENT '0=no,1=yes,2=always',
CHANGE `plisting` `plisting` TINYINT( 4 ) NOT NULL DEFAULT '0',
CHANGE `index` `index` FLOAT( 5, 2 ) NOT NULL DEFAULT '99.00',
CHANGE `css` `css` VARCHAR( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `style` `style` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL;

ALTER TABLE `#__wpl_dbst` CHANGE `listing_specific` `listing_specific` VARCHAR( 200 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `property_type_specific` `property_type_specific` VARCHAR( 200 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `table_name` `table_name` VARCHAR( 30 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT 'table which the data is stored to',
CHANGE `table_column` `table_column` VARCHAR( 30 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT 'column of table which datat is stored to',
CHANGE `category` `category` INT( 11 ) NOT NULL DEFAULT '1' COMMENT 'in propertywizard category',
CHANGE `rank_point` `rank_point` INT( 11 ) NOT NULL DEFAULT '0',
CHANGE `text_search` `text_search` TINYINT( 4 ) NULL ,
CHANGE `params` `params` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL;

ALTER TABLE `#__wpl_dbcat` CHANGE `name` `name` VARCHAR( 30 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `icon` `icon` VARCHAR( 30 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `index` `index` FLOAT NOT NULL DEFAULT '99.00',
CHANGE `enabled` `enabled` TINYINT( 4 ) NOT NULL DEFAULT '1',
CHANGE `pshow` `pshow` TINYINT( 4 ) NOT NULL DEFAULT '1';

ALTER TABLE `#__wpl_dbcat` CHANGE `searchmod` `searchmod` TINYINT( 4 ) NOT NULL DEFAULT '1',
CHANGE `rankable` `rankable` TINYINT( 4 ) NOT NULL DEFAULT '1',
CHANGE `prefix` `prefix` VARCHAR( 5 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `listing_specific` `listing_specific` VARCHAR( 200 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `property_type_specific` `property_type_specific` VARCHAR( 200 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL;

ALTER TABLE `#__wpl_activities` CHANGE `activity` `activity` VARCHAR( 255 ) CHARACTER SET latin1 COLLATE latin1_general_ci NULL ,
CHANGE `position` `position` VARCHAR( 255 ) CHARACTER SET latin1 COLLATE latin1_general_ci NULL ,
CHANGE `params` `params` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL ,
CHANGE `title` `title` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL;

ALTER TABLE `#__wpl_listing_types` CHANGE `parent` `parent` INT( 10 ) UNSIGNED NOT NULL DEFAULT '0',
CHANGE `enabled` `enabled` TINYINT( 4 ) NOT NULL DEFAULT '1',
CHANGE `index` `index` FLOAT( 5, 2 ) NOT NULL DEFAULT '99.00',
CHANGE `gicon` `gicon` VARCHAR( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `caption_img` `caption_img` VARCHAR( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `name` `name` VARCHAR( 250 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL;

ALTER TABLE `#__wpl_property_types` CHANGE `parent` `parent` INT( 11 ) NOT NULL DEFAULT '0',
CHANGE `enabled` `enabled` TINYINT( 4 ) NOT NULL DEFAULT '1',
CHANGE `editable` `editable` TINYINT( 4 ) NOT NULL DEFAULT '1',
CHANGE `index` `index` FLOAT( 5, 2 ) NOT NULL DEFAULT '99.00',
CHANGE `listing` `listing` INT( 11 ) NOT NULL DEFAULT '0',
CHANGE `name` `name` VARCHAR( 200 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `keyword` `keyword` VARCHAR( 200 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL;

ALTER TABLE `#__wpl_room_types` CHANGE `name` `name` VARCHAR( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `icon` `icon` VARCHAR( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL;

ALTER TABLE `#__wpl_users` CHANGE `membership_name` `membership_name` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `membership_id` `membership_id` INT( 10 ) NULL ,
CHANGE `membership_type` `membership_type` VARCHAR( 10 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL ,
CHANGE `first_name` `first_name` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `last_name` `last_name` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `company_name` `company_name` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL;

ALTER TABLE `#__wpl_users` CHANGE `company_address` `company_address` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `website` `website` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `main_email` `main_email` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL ,
CHANGE `secondary_email` `secondary_email` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `sex` `sex` TINYINT( 4 ) NULL ,
CHANGE `tel` `tel` VARCHAR( 20 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `fax` `fax` VARCHAR( 20 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `mobile` `mobile` VARCHAR( 20 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `languages` `languages` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL;

ALTER TABLE `#__wpl_users` CHANGE `location5_id` `location5_id` INT( 11 ) NOT NULL DEFAULT '0',
CHANGE `location6_id` `location6_id` INT( 11 ) NOT NULL DEFAULT '0',
CHANGE `location7_id` `location7_id` INT( 11 ) NOT NULL DEFAULT '0',
CHANGE `zip_id` `zip_id` INT( 11 ) NOT NULL DEFAULT '0',
CHANGE `textsearch` `textsearch` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL ,
CHANGE `rendered` `rendered` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL ,
CHANGE `profile_picture` `profile_picture` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `company_logo` `company_logo` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL;

ALTER TABLE `#__wpl_users` CHANGE `location1_name` `location1_name` VARCHAR( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `location2_name` `location2_name` VARCHAR( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `location3_name` `location3_name` VARCHAR( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `location4_name` `location4_name` VARCHAR( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `location5_name` `location5_name` VARCHAR( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `location6_name` `location6_name` VARCHAR( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `location7_name` `location7_name` VARCHAR( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `zip_name` `zip_name` VARCHAR( 20 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL;

ALTER TABLE `#__wpl_users` CHANGE `maccess_num_prop` `maccess_num_prop` INT( 10 ) NULL ,
CHANGE `maccess_num_feat` `maccess_num_feat` INT( 10 ) NULL ,
CHANGE `maccess_num_hot` `maccess_num_hot` INT( 10 ) NULL ,
CHANGE `maccess_num_pic` `maccess_num_pic` INT( 10 ) NULL ,
CHANGE `maccess_rank_start` `maccess_rank_start` INT( 10 ) NULL ,
CHANGE `maccess_period` `maccess_period` INT( 10 ) NULL;

ALTER TABLE `#__wpl_users` CHANGE `maccess_attach` `maccess_attach` INT( 10 ) NULL ,
CHANGE `maccess_price` `maccess_price` DOUBLE NULL ,
CHANGE `maccess_price_unit` `maccess_price_unit` INT( 10 ) NULL ,
CHANGE `maccess_lrestrict` `maccess_lrestrict` INT( 4 ) NULL ,
CHANGE `maccess_listings` `maccess_listings` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `maccess_ptrestrict` `maccess_ptrestrict` INT( 4 ) NULL;

ALTER TABLE `#__wpl_users` CHANGE `maccess_property_types` `maccess_property_types` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL ,
CHANGE `maccess_renewable` `maccess_renewable` INT( 4 ) NULL ,
CHANGE `maccess_renewal_price` `maccess_renewal_price` DOUBLE NULL ,
CHANGE `maccess_renewal_price_unit` `maccess_renewal_price_unit` INT( 11 ) NULL ,
CHANGE `maccess_renewal_period` `maccess_renewal_period` INT( 11 ) NULL ,
CHANGE `maccess_upgradable` `maccess_upgradable` INT( 4 ) NULL ,
CHANGE `maccess_upgradable_to` `maccess_upgradable_to` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL;

ALTER TABLE `#__wpl_dbst` CHANGE `index` `index` FLOAT( 8, 2 ) NOT NULL DEFAULT '99.00';

ALTER TABLE `#__wpl_addons` CHANGE `name` `name` VARCHAR( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `version` `version` VARCHAR( 15 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `addon_name` `addon_name` VARCHAR( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `params` `params` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `update` `update` VARCHAR( 200 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `update_key` `update_key` VARCHAR( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `support_key` `support_key` VARCHAR( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `message` `message` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL;

ALTER TABLE `#__wpl_items` CHANGE `parent_kind` `parent_kind` VARCHAR( 20 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0',
CHANGE `parent_id` `parent_id` INT( 10 ) NULL ,
CHANGE `item_type` `item_type` VARCHAR( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `item_cat` `item_cat` VARCHAR( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `item_name` `item_name` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `item_extra1` `item_extra1` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `item_extra2` `item_extra2` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `item_extra3` `item_extra3` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `params` `params` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL;

ALTER TABLE `#__wpl_item_categories` CHANGE `item_type` `item_type` VARCHAR( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `parent_kind` `parent_kind` VARCHAR( 20 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `category_name` `category_name` VARCHAR( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL;

ALTER TABLE `#__wpl_location1` CHANGE `enabled` `enabled` TINYINT( 4 ) NULL ,
CHANGE `name` `name` VARCHAR( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `tax_percent` `tax_percent` DOUBLE NULL ,
CHANGE `latitude` `latitude` VARCHAR( 25 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `longitude` `longitude` VARCHAR( 25 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `params` `params` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL;

ALTER TABLE `#__wpl_location2` CHANGE `parent` `parent` BIGINT( 20 ) UNSIGNED NOT NULL DEFAULT '0',
CHANGE `name` `name` VARCHAR( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `tax_percent` `tax_percent` DOUBLE NULL ,
CHANGE `latitude` `latitude` VARCHAR( 25 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `longitude` `longitude` VARCHAR( 25 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `params` `params` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL;

ALTER TABLE `#__wpl_location3` CHANGE `parent` `parent` BIGINT( 20 ) UNSIGNED NOT NULL DEFAULT '0',
CHANGE `name` `name` VARCHAR( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `tax_percent` `tax_percent` DOUBLE NULL ,
CHANGE `latitude` `latitude` VARCHAR( 25 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `longitude` `longitude` VARCHAR( 25 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `params` `params` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL;

ALTER TABLE `#__wpl_location4` CHANGE `parent` `parent` BIGINT( 20 ) UNSIGNED NOT NULL DEFAULT '0',
CHANGE `name` `name` VARCHAR( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `tax_percent` `tax_percent` DOUBLE NULL ,
CHANGE `latitude` `latitude` VARCHAR( 25 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `longitude` `longitude` VARCHAR( 25 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `params` `params` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL;

ALTER TABLE `#__wpl_location5` CHANGE `parent` `parent` BIGINT( 20 ) UNSIGNED NOT NULL DEFAULT '0',
CHANGE `name` `name` VARCHAR( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `tax_percent` `tax_percent` DOUBLE NULL ,
CHANGE `latitude` `latitude` VARCHAR( 25 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `longitude` `longitude` VARCHAR( 25 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `params` `params` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL;

ALTER TABLE `#__wpl_location6` CHANGE `parent` `parent` BIGINT( 20 ) UNSIGNED NOT NULL DEFAULT '0',
CHANGE `name` `name` VARCHAR( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `tax_percent` `tax_percent` DOUBLE NULL ,
CHANGE `latitude` `latitude` VARCHAR( 25 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `longitude` `longitude` VARCHAR( 25 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `params` `params` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL;

ALTER TABLE `#__wpl_location7` CHANGE `parent` `parent` BIGINT( 20 ) UNSIGNED NOT NULL DEFAULT '1',
CHANGE `name` `name` VARCHAR( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `tax_percent` `tax_percent` DOUBLE NULL ,
CHANGE `latitude` `latitude` VARCHAR( 25 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `longitude` `longitude` VARCHAR( 25 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `params` `params` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL;

ALTER TABLE `#__wpl_locationtextsearch` CHANGE `location_text` `location_text` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `count` `count` INT( 10 ) NULL ,
CHANGE `counts` `counts` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL;

ALTER TABLE `#__wpl_locationzips` CHANGE `name` `name` VARCHAR( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `parent` `parent` INT( 11 ) NULL ,
CHANGE `country_id` `country_id` INT( 11 ) NULL ,
CHANGE `median_income` `median_income` INT( 11 ) NULL ,
CHANGE `average_hvalue` `average_hvalue` INT( 11 ) NULL ,
CHANGE `distance_to_downtown` `distance_to_downtown` INT( 11 ) NULL ,
CHANGE `school_rating` `school_rating` TINYINT( 4 ) NULL ,
CHANGE `tax_rate` `tax_rate` INT( 11 ) NULL;

ALTER TABLE `#__wpl_locationzips` CHANGE `population` `population` INT( 11 ) NULL ,
CHANGE `boundary` `boundary` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `color` `color` VARCHAR( 6 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '',
CHANGE `hcolor` `hcolor` VARCHAR( 6 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '',
CHANGE `longitude` `longitude` VARCHAR( 25 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `latitude` `latitude` VARCHAR( 25 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `params` `params` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL;

ALTER TABLE `#__wpl_settings` CHANGE `setting_name` `setting_name` VARCHAR( 250 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL ,
CHANGE `setting_value` `setting_value` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL ,
CHANGE `title` `title` VARCHAR( 200 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `params` `params` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `options` `options` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL;

ALTER TABLE `#__wpl_setting_categories` CHANGE `name` `name` VARCHAR( 200 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL;
ALTER TABLE `#__wpl_unit_types` CHANGE `name` `name` VARCHAR( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL;

ALTER TABLE `#__wpl_units` CHANGE `name` `name` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `enabled` `enabled` TINYINT( 4 ) NOT NULL DEFAULT '0',
CHANGE `tosi` `tosi` DOUBLE NOT NULL DEFAULT '0',
CHANGE `index` `index` INT( 11 ) NOT NULL DEFAULT '999',
CHANGE `extra` `extra` VARCHAR( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT 'iso code';

ALTER TABLE `#__wpl_units` CHANGE `extra2` `extra2` VARCHAR( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT 'currency name',
CHANGE `extra3` `extra3` VARCHAR( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT 'icon',
CHANGE `extra4` `extra4` VARCHAR( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `seperator` `seperator` VARCHAR( 2 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `d_seperator` `d_seperator` VARCHAR( 2 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `after_before` `after_before` TINYINT( 4 ) NOT NULL DEFAULT '0' COMMENT '0=before,1=after';

ALTER TABLE `#__wpl_sort_options` CHANGE `name` `name` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
CHANGE `field_name` `field_name` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL;

UPDATE `#__wpl_dbst` SET `editable`='1' WHERE `id`='8';
UPDATE `#__wpl_dbst` SET `editable`='1' WHERE `id`='13';
UPDATE `#__wpl_dbst` SET `editable`='1' WHERE `id`='14';

INSERT INTO `#__wpl_extensions` (`id`, `type`, `title`, `parent`, `description`, `enabled`, `param1`, `param2`, `param3`, `param4`, `param5`, `params`, `editable`, `index`, `client`) VALUES
(21, 'sidebar', 'Property Show Bottom', 0, 'Appears on bottom of single property/property show page', 1, 'wpl-pshow-bottom', '', '', '', '', '', 0, 99.99, 2),
(22, 'sidebar', 'Profile Show Top', 0, 'Appears on top of agent show/profile show page', 1, 'wpl-profileshow-top', '', '', '', '', '', 0, 99.99, 2);

UPDATE `#__wpl_dbst` SET `deletable`='0' WHERE `id`='2';
UPDATE `#__wpl_dbst` SET `deletable`='0' WHERE `id`='3';

UPDATE `#__wpl_settings` SET `showable`='0' WHERE `id`='41';
UPDATE `#__wpl_settings` SET `showable`='0' WHERE `id`='39';
UPDATE `#__wpl_settings` SET `showable`='0' WHERE `id`='40';

ALTER TABLE `#__wpl_activities` ADD `association_type` TINYINT( 4 ) NOT NULL DEFAULT '1', ADD `associations` TEXT NULL;