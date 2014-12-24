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

UPDATE `#__wpl_dbst` SET `editable`='1' WHERE `id`='8';
UPDATE `#__wpl_dbst` SET `editable`='1' WHERE `id`='13';
UPDATE `#__wpl_dbst` SET `editable`='1' WHERE `id`='14';

INSERT INTO `#__wpl_extensions` (`id`, `type`, `title`, `parent`, `description`, `enabled`, `param1`, `param2`, `param3`, `param4`, `param5`, `params`, `editable`, `index`, `client`) VALUES
(21, 'sidebar', 'Property Show Bottom', 0, 'Appears on bottom of single property/property show page', 1, 'wpl-pshow-bottom', '', '', '', '', '', 0, 99.99, 2),
(22, 'sidebar', 'Profile Show Top', 0, 'Appears on top of agent show/profile show page', 1, 'wpl-profileshow-top', '', '', '', '', '', 0, 99.99, 2);

UPDATE `#__wpl_dbst` SET `deletable`='0' WHERE `id`='2';
UPDATE `#__wpl_dbst` SET `deletable`='0' WHERE `id`='3';

ALTER TABLE `#__wpl_activities` ADD `association_type` TINYINT( 4 ) NOT NULL DEFAULT '1', ADD `associations` TEXT NULL;

INSERT INTO `#__wpl_settings` (`id`, `setting_name`, `setting_value`, `showable`, `category`, `type`, `title`, `params`, `options`, `index`) VALUES
(50, 'backend_listing_target_page', NULL, 1, 4, 'wppages', 'Backend Listing Target', '{"tooltip":"Used for backend views"}', '{"show_empty":1} ', 99.00);

ALTER TABLE `#__wpl_users` ADD `access_change_user` TINYINT( 4 ) NOT NULL DEFAULT '0' AFTER `access_public_profile`;

INSERT INTO `#__wpl_settings` (`id`, `setting_name`, `setting_value`, `showable`, `category`, `type`, `title`, `params`, `options`, `index`) VALUES
(36, 'realtyna_username', NULL, 0, 1, 'text', '', '', '', 99.00),
(37, 'realtyna_password', NULL, 0, 1, 'text', '', '', '', 99.00),
(38, 'realtyna_verified', '0', 0, 1, 'text', '', '', '', 99.00);

INSERT INTO `#__wpl_extensions` (`id`, `type`, `title`, `parent`, `description`, `enabled`, `param1`, `param2`, `param3`, `param4`, `param5`, `params`, `editable`, `index`, `client`) VALUES
(105, 'javascript', 'AjaxFileUpload', 0, '', 1, 'ajaxFileUpload', 'js/libs/bower_components/ajaxfileupload.min.js', '', '', '', '', 0, 100.00, 2),
(104, 'javascript', 'HoverIntent', 0, '', 1, 'hoverIntent', 'js/libs/bower_components/hoverintent/jquery.hoverIntent.js', '', '', '', '', 0, 100.00, 1),
(103, 'javascript', 'Transit', 0, '', 1, 'transit', 'js/libs/bower_components/transit/jquery.transit.min.js', '', '', '', '', 0, 100.00, 1),
(102, 'javascript', 'customScrollBarJS', 0, '', 1, 'customScrollBarJS', 'js/libs/bower_components/malihu-custom-scrollbar-plugin-bower/jquery.mCustomScrollbar.concat.min.js', '', '', '', '', 0, 100.00, 1),
(101, 'javascript', 'Chosen', 0, '', 1, 'ChosenJS', 'js/libs/bower_components/chosen/public/chosen.jquery.min.js', '', '', '', '', 0, 100.00, 1);

DELETE FROM `#__wpl_extensions` WHERE `id`='93';
DELETE FROM `#__wpl_extensions` WHERE `id`='95';

UPDATE `#__wpl_activities` SET `activity`='agent_info:profileshow' WHERE `id`='12';

UPDATE `#__wpl_activities` SET `association_type`='1';
UPDATE `#__wpl_extensions` SET `client`='2' WHERE `id`='98';
UPDATE `#__wpl_extensions` SET `client`='2' WHERE `id`='102';
UPDATE `#__wpl_extensions` SET `client`='2' WHERE `id`='89';
UPDATE `#__wpl_extensions` SET `client`='2' WHERE `id`='101';

INSERT INTO `#__wpl_units` (`id`, `name`, `type`, `enabled`, `tosi`, `index`, `extra`, `extra2`, `extra3`, `extra4`, `seperator`, `d_seperator`, `after_before`) VALUES
(7, 'Hectare', 2, 0, 10000, 7, '', '', '', '', '', '', 0);

ALTER TABLE `#__wpl_settings` CHANGE `setting_value` `setting_value` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;

INSERT INTO `#__wpl_dbst` (`id`, `kind`, `mandatory`, `name`, `type`, `options`, `enabled`, `pshow`, `plisting`, `searchmod`, `editable`, `deletable`, `index`, `css`, `style`, `specificable`, `listing_specific`, `property_type_specific`, `table_name`, `table_column`, `category`, `rankable`, `rank_point`, `comments`, `pwizard`, `text_search`, `params`) VALUES
(313, 0, 3, 'Property Title', 'text', 'null', 1, '0', 1, 0, 1, 0, 1.00, '', '', 1, '', '', 'wpl_properties', 'field_313', 1, 0, 0, '', '1', 0, '[]');

ALTER TABLE `#__wpl_properties` ADD `field_313` VARCHAR( 50 ) NULL AFTER `field_312`;
UPDATE `#__wpl_dbcat` SET `listing_specific`='' WHERE `id`='7';

INSERT INTO `#__wpl_dbst_types` (`id`, `kind`, `type`, `enabled`, `index`, `queries_add`, `queries_delete`) VALUES
(14, '[0][1]', 'url', 1, 1.00, 'ALTER TABLE `#__[TABLE_NAME]` ADD `field_[FIELD_ID]` varchar(50) NULL; UPDATE #__wpl_dbst SET `table_name`=''[TABLE_NAME]'', `table_column`=''field_[FIELD_ID]'' WHERE id=[FIELD_ID];', 'ALTER TABLE `#__[TABLE_NAME]`\r\nDROP `field_[FIELD_ID]`;');

INSERT INTO `#__wpl_extensions` (`id`, `type`, `title`, `parent`, `description`, `enabled`, `param1`, `param2`, `param3`, `param4`, `param5`, `params`, `editable`, `index`, `client`) VALUES
(24, 'sidebar', 'Property Listing Top', 0, 'Appears below of Google map in property listing page', 1, 'wpl-plisting-top', '', '', '', '', '', 0, 99.99, 2);

UPDATE `#__wpl_dbst_types` SET `kind`='[0][1][2]' WHERE `id`='1';
UPDATE `#__wpl_dbst_types` SET `kind`='[0][1][2]' WHERE `id`='2';
UPDATE `#__wpl_dbst_types` SET `kind`='[0][1][2]' WHERE `id`='3';
UPDATE `#__wpl_dbst_types` SET `kind`='[0][1][2]' WHERE `id`='5';
UPDATE `#__wpl_dbst_types` SET `kind`='[0][1][2]' WHERE `id`='6';
UPDATE `#__wpl_dbst_types` SET `kind`='[0][1][2]' WHERE `id`='11';
UPDATE `#__wpl_dbst_types` SET `kind`='[0][1][2]' WHERE `id`='12';
UPDATE `#__wpl_dbst_types` SET `kind`='[0][1][2]' WHERE `id`='13';
UPDATE `#__wpl_dbst_types` SET `kind`='[0][1][2]' WHERE `id`='14';

UPDATE `#__wpl_dbst` SET `editable`='1' WHERE `id`='903';
UPDATE `#__wpl_dbst` SET `editable`='1' WHERE `id`='900';
UPDATE `#__wpl_dbst` SET `editable`='1' WHERE `id`='901';
UPDATE `#__wpl_dbst` SET `editable`='1' WHERE `id`='902';
UPDATE `#__wpl_dbst` SET `editable`='1', `deletable`='1' WHERE `id`='904';
UPDATE `#__wpl_dbst` SET `editable`='1' WHERE `id`='905';
UPDATE `#__wpl_dbst` SET `editable`='1', `deletable`='1' WHERE `id`='907';
UPDATE `#__wpl_dbst` SET `editable`='1', `deletable`='1' WHERE `id`='908';
UPDATE `#__wpl_dbst` SET `editable`='1', `deletable`='1' WHERE `id`='909';
UPDATE `#__wpl_dbst` SET `editable`='1' WHERE `id`='914';

UPDATE `#__wpl_dbst` SET `editable`='1' WHERE `id`='400';
UPDATE `#__wpl_dbst` SET `editable`='1' WHERE `id`='401';
UPDATE `#__wpl_dbst` SET `editable`='1' WHERE `id`='402';
UPDATE `#__wpl_dbst` SET `editable`='1' WHERE `id`='403';

UPDATE `#__wpl_dbst` SET `deletable`='0' WHERE `id`='4';
UPDATE `#__wpl_dbst` SET `deletable`='0' WHERE `id`='5';

ALTER TABLE `#__wpl_extensions` CHANGE `param2` `param2` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;
UPDATE `#__wpl_extensions` SET `param2`='http://fonts.googleapis.com/css?family=Droid+Serif:400,700,400italic,700italic|Open+Sans:300italic,400italic,600italic,700italic,400,300,600,700|Scada:400italic,700italic,400,700|Archivo+Narrow:400,40|Lato:400,700,900,400italic|BenchNine' WHERE `id`='99';

UPDATE `#__wpl_dbst` SET `index`='3.50' WHERE `id`='171';
UPDATE `#__wpl_dbst` SET `text_search`='1' WHERE `id`='308';

INSERT INTO `#__wpl_settings` (`id`, `setting_name`, `setting_value`, `showable`, `category`, `type`, `title`, `params`, `options`, `index`) VALUES
(51, 'log', '0', 1, 1, 'select', 'WPL log', NULL, '{"values":[{"key":0,"value":"Disabled" },{"key":1,"value":"Enabled"}]}', 120.00);

UPDATE `#__wpl_dbst` SET `deletable`='0' WHERE `id`='51';
UPDATE `#__wpl_dbst` SET `deletable`='0' WHERE `id`='52';

INSERT INTO `#__wpl_extensions` (`id`,`type`, `title`, `parent`, `description`, `enabled`, `param1`, `param2`, `param3`, `param4`, `param5`, `params`, `editable`, `index`, `client`) VALUES
(108, 'javascript', 'Modernizr', 0, '', '', 'modernizer', 'js/modernizr.custom.js', '', '', '1', '', 0, 99.99, 0);

UPDATE `#__wpl_extensions` SET `param5`='' WHERE `id`='108';
UPDATE `#__wpl_extensions` SET `enabled`='1' WHERE `id`='108';

UPDATE `#__wpl_dbst_types` SET `queries_add`='ALTER TABLE `#__[TABLE_NAME]` ADD `field_[FIELD_ID]` double NULL; ALTER TABLE `#__[TABLE_NAME]` ADD `field_[FIELD_ID]_si` double NULL; ALTER TABLE `#__[TABLE_NAME]` ADD `field_[FIELD_ID]_unit` int NULL; UPDATE `#__wpl_dbst` SET `table_name`=''[TABLE_NAME]'', `table_column`=''field_[FIELD_ID]'' WHERE id=[FIELD_ID];' WHERE `id`='10';
UPDATE `#__wpl_dbst_types` SET `queries_add`='ALTER TABLE `#__[TABLE_NAME]` ADD `field_[FIELD_ID]` double NULL; ALTER TABLE `#__[TABLE_NAME]` ADD `field_[FIELD_ID]_si` double NULL; ALTER TABLE `#__[TABLE_NAME]` ADD `field_[FIELD_ID]_unit` int NULL; UPDATE `#__wpl_dbst` SET `table_name`=''[TABLE_NAME]'', `table_column`=''field_[FIELD_ID]'' WHERE id=[FIELD_ID];' WHERE `id`='11';
UPDATE `#__wpl_dbst_types` SET `queries_add`='ALTER TABLE `#__[TABLE_NAME]` ADD `field_[FIELD_ID]` double NULL; ALTER TABLE `#__[TABLE_NAME]` ADD `field_[FIELD_ID]_si` double NULL; ALTER TABLE `#__[TABLE_NAME]` ADD `field_[FIELD_ID]_unit` int NULL; UPDATE `#__wpl_dbst` SET `table_name`=''[TABLE_NAME]'', `table_column`=''field_[FIELD_ID]'' WHERE id=[FIELD_ID];' WHERE `id`='9';
UPDATE `#__wpl_dbst_types` SET `queries_add`='ALTER TABLE `#__[TABLE_NAME]` ADD `field_[FIELD_ID]` double NULL; ALTER TABLE `#__[TABLE_NAME]` ADD `field_[FIELD_ID]_si` double NULL; ALTER TABLE `#__[TABLE_NAME]` ADD `field_[FIELD_ID]_unit` int NULL; UPDATE `#__wpl_dbst` SET `table_name`=''[TABLE_NAME]'', `table_column`=''field_[FIELD_ID]'' WHERE id=[FIELD_ID];' WHERE `id`='8';
UPDATE `#__wpl_dbst_types` SET `queries_add`='ALTER TABLE `#__[TABLE_NAME]` ADD `n_[FIELD_ID]` tinyint(4) NULL; ALTER TABLE `#__[TABLE_NAME]` ADD `n_[FIELD_ID]_distance` int NULL; ALTER TABLE `#__[TABLE_NAME]` ADD `n_[FIELD_ID]_distance_by` tinyint(4) NULL; UPDATE `#__wpl_dbst` SET `table_name`=''[TABLE_NAME]'', `table_column`=''n_[FIELD_ID]'' WHERE id=[FIELD_ID];' WHERE `id`='7';
UPDATE `#__wpl_dbst_types` SET `queries_add`='ALTER TABLE `#__[TABLE_NAME]` ADD `field_[FIELD_ID]` text NULL; UPDATE `#__wpl_dbst` SET `table_name`=''[TABLE_NAME]'', `table_column`=''field_[FIELD_ID]'' WHERE id=[FIELD_ID];' WHERE `id`='5';
UPDATE `#__wpl_dbst_types` SET `queries_add`='ALTER TABLE `#__[TABLE_NAME]` ADD `field_[FIELD_ID]` int(11) NULL; UPDATE `#__wpl_dbst` SET `table_name`=''[TABLE_NAME]'', `table_column`=''field_[FIELD_ID]'' WHERE id=[FIELD_ID];' WHERE `id`='3';
UPDATE `#__wpl_dbst_types` SET `queries_add`='ALTER TABLE `#__[TABLE_NAME]` ADD `f_[FIELD_ID]_options` text NULL; ALTER TABLE `#__[TABLE_NAME]` ADD `f_[FIELD_ID]` tinyint(4) NULL; UPDATE `#__wpl_dbst` SET `table_name`=''[TABLE_NAME]'', `table_column`=''f_[FIELD_ID]'' WHERE id=[FIELD_ID];' WHERE `id`='4';
UPDATE `#__wpl_dbst_types` SET `queries_add`='ALTER TABLE `#__[TABLE_NAME]` ADD `field_[FIELD_ID]` varchar(50) NULL; UPDATE `#__wpl_dbst` SET `table_name`=''[TABLE_NAME]'', `table_column`=''field_[FIELD_ID]'' WHERE id=[FIELD_ID];' WHERE `id`='1';
UPDATE `#__wpl_dbst_types` SET `queries_add`='ALTER TABLE `#__[TABLE_NAME]` ADD `field_[FIELD_ID]` float NULL; UPDATE `#__wpl_dbst` SET `table_name`=''[TABLE_NAME]'', `table_column`=''field_[FIELD_ID]'' WHERE id=[FIELD_ID];' WHERE `id`='2';
UPDATE `#__wpl_dbst_types` SET `queries_add`='ALTER TABLE `#__[TABLE_NAME]` ADD `field_[FIELD_ID]` date NULL; UPDATE `#__wpl_dbst` SET `table_name`=''[TABLE_NAME]'', `table_column`=''field_[FIELD_ID]'' WHERE id=[FIELD_ID];' WHERE `id`='12';
UPDATE `#__wpl_dbst_types` SET `queries_add`='ALTER TABLE `#__[TABLE_NAME]` ADD `field_[FIELD_ID]` datetime NULL; UPDATE `#__wpl_dbst` SET `table_name`=''[TABLE_NAME]'', `table_column`=''field_[FIELD_ID]'' WHERE id=[FIELD_ID];' WHERE `id`='13';
UPDATE `#__wpl_dbst_types` SET `queries_add`='ALTER TABLE `#__[TABLE_NAME]` ADD `field_[FIELD_ID]` varchar(50) NULL; UPDATE `#__wpl_dbst` SET `table_name`=''[TABLE_NAME]'', `table_column`=''field_[FIELD_ID]'' WHERE id=[FIELD_ID];' WHERE `id`='14';
UPDATE `#__wpl_dbst` SET `deletable`='0' WHERE `id`='18';

CREATE TABLE IF NOT EXISTS `#__wpl_notifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `template` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `subject` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `additional_memberships` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `additional_users` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `additional_emails` text COLLATE utf8_unicode_ci,
  `options` text COLLATE utf8_unicode_ci,
  `params` text COLLATE utf8_unicode_ci,
  `enabled` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;

INSERT INTO `#__wpl_menus` (`id`, `client`, `type`, `parent`, `page_title`, `menu_title`, `capability`, `menu_slug`, `function`, `separator`, `enabled`, `index`, `position`, `dashboard`) VALUES
(13, 'backend', 'submenu', 'WPL_main_menu', 'Notifications', 'Notifications', 'admin', 'wpl_admin_notifications', 'b:notifications:home', 0, 1, 2.05, 0, 0);

INSERT INTO `#__wpl_extensions` (`id`, `type`, `title`, `parent`, `description`, `enabled`, `param1`, `param2`, `param3`, `param4`, `param5`, `params`, `editable`, `index`, `client`) VALUES
(109, 'javascript', 'Handlebars', 0, '', 1, 'handlebars', 'js/handlebars.js', '', '', '', '', 0, 109.99, 0);

INSERT INTO `#__wpl_setting_categories` (`id`, `name`, `showable`, `index`) VALUES (5, 'Notifications', 1, 99.00);

INSERT INTO `#__wpl_settings` (`id`, `setting_name`, `setting_value`, `showable`, `category`, `type`, `title`, `params`, `options`, `index`) VALUES
(53, 'wpl_sender_email', '', 1, 5, 'text', 'Sender email', NULL, '', 121.00),
(54, 'wpl_sender_name', '', 1, 5, 'text', 'Sender name', NULL, '', 122.00);

INSERT INTO `#__wpl_settings` (`id`, `setting_name`, `setting_value`, `showable`, `category`, `type`, `title`, `params`, `options`, `index`) VALUES
(55, 'property_location_pattern', '[street_no] [street][glue] [location4_name][glue] [location3_name][glue] [location2_name][glue] [location1_name] [zip_name]', 1, 3, 'text', 'Property Location Pattern', NULL, '', 123.00),
(56, 'user_location_pattern', '[location5_name][glue][location4_name][glue][location3_name][glue][location2_name][glue][location1_name] [zip_name]', 1, 3, 'text', 'User Location Pattern', NULL, '', 124.00);

UPDATE `#__wpl_extensions` SET `param2`='https://maps.google.com/maps/api/js?libraries=places&sensor=true' WHERE `id`='94';
UPDATE `#__wpl_settings` SET `type`='wppages' WHERE `id`='25';

UPDATE `#__wpl_extensions` SET `param2`='https://fonts.googleapis.com/css?family=Droid+Serif:400,700,400italic,700italic|Open+Sans:300italic,400italic,600italic,700italic,400,300,600,700|Scada:400italic,700italic,400,700|Archivo+Narrow:400,40|Lato:400,700,900,400italic|BenchNine' WHERE `id`='99';

INSERT INTO `#__wpl_events` (`id`, `type`, `trigger`, `class_location`, `class_name`, `function_name`, `params`, `enabled`) VALUES
(4, 'notification', 'contact_agent', 'libraries.event_handlers.notifications', 'wpl_events_notifications', 'contact_agent', '', 1);

INSERT INTO `#__wpl_notifications` (`id`, `description`, `template`, `subject`, `additional_memberships`, `additional_users`, `additional_emails`, `options`, `params`, `enabled`) VALUES
(2, 'Contact to listing agent from listing page', 'contact_agent', 'New Contact', '', '', '', NULL, '', 1);

INSERT INTO `#__wpl_activities` (`id`, `activity`, `position`, `enabled`, `index`, `params`, `show_title`, `title`, `association_type`, `associations`) VALUES
(23, 'listing_contact', 'pshow_position2', 1, 99.00, '', 1, 'Contact Agent', 1, '');

UPDATE `#__wpl_dbst` SET `table_column`='locations' WHERE `id`='41';
UPDATE `#__wpl_dbst` SET `table_column`='locations' WHERE `id`='911';
UPDATE `#__wpl_dbst` SET `options`='' WHERE `id`='6';

ALTER TABLE `#__wpl_item_categories` DROP `parent_kind`;
DROP TABLE `#__wpl_notices`;

UPDATE `#__wpl_dbst` SET `text_search`='1' WHERE `id`='312';
UPDATE `#__wpl_dbst` SET `text_search`='1' WHERE `id`='313';

CREATE TABLE IF NOT EXISTS `#__wpl_kinds` (
  `id` int(10) unsigned NOT NULL,
  `name` varchar(100) NOT NULL,
  `table` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `#__wpl_kinds` (`id`, `name`, `table`) VALUES
(0, 'Property', 'wpl_properties'),
(2, 'User', 'wpl_users');

UPDATE `#__wpl_settings` SET `index`='50.00' WHERE `id`='50';
UPDATE `#__wpl_settings` SET `title`='Property Pattern' WHERE `id`='55';
UPDATE `#__wpl_settings` SET `title`='User Pattern' WHERE `id`='56';

INSERT INTO `#__wpl_settings` (`id`, `setting_name`, `setting_value`, `showable`, `category`, `type`, `title`, `params`, `options`, `index`) VALUES
(65, 'location_separator3', '', 1, 3, 'separator', 'Location Method', '', '', 4.50),
(63, 'location_separator1', '', 1, 3, 'separator', 'Location Keywords', '', '', 98.00),
(64, 'location_separator2', '', 1, 3, 'separator', 'Location Patterns', '', '', 122.00);

INSERT INTO `#__wpl_settings` (`id`, `setting_name`, `setting_value`, `showable`, `category`, `type`, `title`, `params`, `options`, `index`) VALUES
(66, 'permalink_separator', '', 1, 4, 'separator', 'WPL Permalink', '', '', 0.90);

INSERT INTO `#__wpl_settings` (`id`, `setting_name`, `setting_value`, `showable`, `category`, `type`, `title`, `params`, `options`, `index`) VALUES
(67, 'sender_separator', NULL, 1, 5, 'separator', 'Notification Sender', NULL, NULL, 120.50);

DELETE FROM `#__wpl_settings` WHERE `id`='3';

INSERT INTO `#__wpl_settings` (`id`, `setting_name`, `setting_value`, `showable`, `category`, `type`, `title`, `params`, `options`, `index`) VALUES
(68, 'resize_separator', NULL, 1, 2, 'separator', 'Resize', NULL, NULL, 1.50),
(69, 'watermark_separator', NULL, 1, 2, 'separator', 'Watermark', NULL, NULL, 4.50);

UPDATE `#__wpl_settings` SET `index`='109.00' WHERE `id`='31';
UPDATE `#__wpl_settings` SET `index`='51.00' WHERE `id`='22';
UPDATE `#__wpl_settings` SET `index`='52.00' WHERE `id`='27';
UPDATE `#__wpl_settings` SET `index`='53.00' WHERE `id`='51';

INSERT INTO `#__wpl_settings` (`id`, `setting_name`, `setting_value`, `showable`, `category`, `type`, `title`, `params`, `options`, `index`) VALUES
(70, 'global_separator', NULL, 1, 1, 'separator', 'Global', NULL, NULL, 0.05),
(71, 'listing_pages_separator', NULL, 1, 1, 'separator', 'Listings', NULL, NULL, 98.00),
(72, 'users_separator', NULL, 1, 1, 'separator', 'Users', NULL, NULL, 107.00),
(73, 'io_separator', NULL, 1, 1, 'separator', 'I/O Application', NULL, NULL, 116.00);

INSERT INTO `#__wpl_activities` (`id`, `activity`, `position`, `enabled`, `index`, `params`, `show_title`, `title`, `association_type`, `associations`) VALUES
(24, 'user_contact', 'profile_show_position1', 0, 99.00, '{"top_comment":""}', 1, 'Contact', 1, '');

INSERT INTO `#__wpl_notifications` (`id`, `description`, `template`, `subject`, `additional_memberships`, `additional_users`, `additional_emails`, `options`, `params`, `enabled`) VALUES
(3, 'Contact to agent from profile page', 'contact_profile', 'New Profile Contact', '', '', '', NULL, '', 1);

INSERT INTO `#__wpl_events` (`id`, `type`, `trigger`, `class_location`, `class_name`, `function_name`, `params`, `enabled`) VALUES
(5, 'notification', 'contact_profile', 'libraries.event_handlers.notifications', 'wpl_events_notifications', 'contact_profile', '', 1);

UPDATE `#__wpl_dbst` SET `searchmod`='1' WHERE `id`='313';

ALTER TABLE `#__wpl_properties` CHANGE `last_modified_time_stamp` `last_modified_time_stamp` TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP;
ALTER TABLE `#__wpl_users` ADD `last_modified_time_stamp` TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `rendered`;

UPDATE `#__wpl_dbst` SET `index`='1.00' WHERE `id`='308';
UPDATE `#__wpl_dbst` SET `index`='0.50' WHERE `id`='313';
UPDATE `#__wpl_dbst` SET `index`='0.60' WHERE `id`='312';

INSERT INTO `#__wpl_extensions` (`id`, `type`, `title`, `parent`, `description`, `enabled`, `param1`, `param2`, `param3`, `param4`, `param5`, `params`, `editable`, `index`, `client`) VALUES
(36, 'service', 'Tips Service', 0, 'For running WPL Tips', 1, 'init', 'tips->run', '9999', '', '', '', 0, 99.99, 1);

ALTER TABLE `#__wpl_dbcat` ADD `params` TEXT NULL;
ALTER TABLE `#__wpl_dbcat` DROP `icon`, DROP `rankable`;

UPDATE `#__wpl_dbst` SET `deletable`='0' WHERE `id`='41';
UPDATE `#__wpl_dbst` SET `deletable`='0' WHERE `id`='911';
UPDATE `#__wpl_dbst` SET `deletable`='0' WHERE `id`='53';

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
ALTER TABLE `#__wpl_users` CHANGE `id` `id` INT(10) NOT NULL AUTO_INCREMENT;

ALTER TABLE `#__wpl_location1` ADD `abbr` VARCHAR(100) NULL AFTER `name`;
ALTER TABLE `#__wpl_location2` ADD `abbr` VARCHAR(100) NULL AFTER `name`;
ALTER TABLE `#__wpl_location3` ADD `abbr` VARCHAR(100) NULL AFTER `name`;
ALTER TABLE `#__wpl_location4` ADD `abbr` VARCHAR(100) NULL AFTER `name`;
ALTER TABLE `#__wpl_location5` ADD `abbr` VARCHAR(100) NULL AFTER `name`;
ALTER TABLE `#__wpl_location6` ADD `abbr` VARCHAR(100) NULL AFTER `name`;
ALTER TABLE `#__wpl_location7` ADD `abbr` VARCHAR(100) NULL AFTER `name`;

UPDATE `#__wpl_location1` SET `abbr`='US' WHERE `id`='254';
UPDATE `#__wpl_location2` SET `abbr`='AL' WHERE `id`='6001';
UPDATE `#__wpl_location2` SET `abbr`='AK' WHERE `id`='6002';
UPDATE `#__wpl_location2` SET `abbr`='AS' WHERE `id`='6003';
UPDATE `#__wpl_location2` SET `abbr`='AZ' WHERE `id`='6004';
UPDATE `#__wpl_location2` SET `abbr`='AR' WHERE `id`='6005';
UPDATE `#__wpl_location2` SET `abbr`='CA' WHERE `id`='6006';
UPDATE `#__wpl_location2` SET `abbr`='CO' WHERE `id`='6007';
UPDATE `#__wpl_location2` SET `abbr`='CT' WHERE `id`='6008';
UPDATE `#__wpl_location2` SET `abbr`='DE' WHERE `id`='6009';
UPDATE `#__wpl_location2` SET `abbr`='DC' WHERE `id`='6010';
UPDATE `#__wpl_location2` SET `abbr`='FM' WHERE `id`='6011';
UPDATE `#__wpl_location2` SET `abbr`='FL' WHERE `id`='6012';
UPDATE `#__wpl_location2` SET `abbr`='GA' WHERE `id`='6013';
UPDATE `#__wpl_location2` SET `abbr`='GU' WHERE `id`='6014';
UPDATE `#__wpl_location2` SET `abbr`='HI' WHERE `id`='6015';
UPDATE `#__wpl_location2` SET `abbr`='ID' WHERE `id`='6016';
UPDATE `#__wpl_location2` SET `abbr`='IL' WHERE `id`='6017';
UPDATE `#__wpl_location2` SET `abbr`='IN' WHERE `id`='6018';
UPDATE `#__wpl_location2` SET `abbr`='IA' WHERE `id`='6019';
UPDATE `#__wpl_location2` SET `abbr`='KS' WHERE `id`='6020';
UPDATE `#__wpl_location2` SET `abbr`='KY' WHERE `id`='6021';
UPDATE `#__wpl_location2` SET `abbr`='LA' WHERE `id`='6022';
UPDATE `#__wpl_location2` SET `abbr`='ME' WHERE `id`='6023';
UPDATE `#__wpl_location2` SET `abbr`='MH' WHERE `id`='6024';
UPDATE `#__wpl_location2` SET `abbr`='MD' WHERE `id`='6025';
UPDATE `#__wpl_location2` SET `abbr`='MA' WHERE `id`='6026';
UPDATE `#__wpl_location2` SET `abbr`='MI' WHERE `id`='6027';
UPDATE `#__wpl_location2` SET `abbr`='MN' WHERE `id`='6028';
UPDATE `#__wpl_location2` SET `abbr`='UM' WHERE `id`='6029';
UPDATE `#__wpl_location2` SET `abbr`='MS' WHERE `id`='6030';
UPDATE `#__wpl_location2` SET `abbr`='MO' WHERE `id`='6031';
UPDATE `#__wpl_location2` SET `abbr`='MT' WHERE `id`='6032';
UPDATE `#__wpl_location2` SET `abbr`='NE' WHERE `id`='6033';
UPDATE `#__wpl_location2` SET `abbr`='NV' WHERE `id`='6034';
UPDATE `#__wpl_location2` SET `abbr`='NH' WHERE `id`='6035';
UPDATE `#__wpl_location2` SET `abbr`='NJ' WHERE `id`='6036';
UPDATE `#__wpl_location2` SET `abbr`='NM' WHERE `id`='6037';
UPDATE `#__wpl_location2` SET `abbr`='NY' WHERE `id`='6038';
UPDATE `#__wpl_location2` SET `abbr`='NC' WHERE `id`='6039';
UPDATE `#__wpl_location2` SET `abbr`='ND' WHERE `id`='6040';
UPDATE `#__wpl_location2` SET `abbr`='MP' WHERE `id`='6041';
UPDATE `#__wpl_location2` SET `abbr`='OH' WHERE `id`='6042';
UPDATE `#__wpl_location2` SET `abbr`='OK' WHERE `id`='6043';
UPDATE `#__wpl_location2` SET `abbr`='OR' WHERE `id`='6044';
UPDATE `#__wpl_location2` SET `abbr`='PW' WHERE `id`='6045';
UPDATE `#__wpl_location2` SET `abbr`='PA' WHERE `id`='6046';
UPDATE `#__wpl_location2` SET `abbr`='PR' WHERE `id`='6047';
UPDATE `#__wpl_location2` SET `abbr`='RI' WHERE `id`='6048';
UPDATE `#__wpl_location2` SET `abbr`='SC' WHERE `id`='6049';
UPDATE `#__wpl_location2` SET `abbr`='SD' WHERE `id`='6050';
UPDATE `#__wpl_location2` SET `abbr`='TN' WHERE `id`='6051';
UPDATE `#__wpl_location2` SET `abbr`='TX' WHERE `id`='6052';
UPDATE `#__wpl_location2` SET `abbr`='UT' WHERE `id`='6053';
UPDATE `#__wpl_location2` SET `abbr`='VT' WHERE `id`='6054';
UPDATE `#__wpl_location2` SET `abbr`='VI' WHERE `id`='6055';
UPDATE `#__wpl_location2` SET `abbr`='VA' WHERE `id`='6056';
UPDATE `#__wpl_location2` SET `abbr`='WA' WHERE `id`='6057';
UPDATE `#__wpl_location2` SET `abbr`='WV' WHERE `id`='6058';
UPDATE `#__wpl_location2` SET `abbr`='WI' WHERE `id`='6059';
UPDATE `#__wpl_location2` SET `abbr`='WY' WHERE `id`='6060';

ALTER TABLE `#__wpl_user_group_types` ADD `editable` TINYINT(4) UNSIGNED NOT NULL DEFAULT '1', ADD `deletable` TINYINT(4) UNSIGNED NOT NULL DEFAULT '1', ADD `index` FLOAT(5, 2) NOT NULL DEFAULT '99.00';
ALTER TABLE `#__wpl_user_group_types` ADD `params` TEXT NULL, ADD `enabled` TINYINT(4) NOT NULL DEFAULT '1';

UPDATE `#__wpl_user_group_types` SET `editable`='0', `deletable`='0', `index`='1.00' WHERE `id`='1';
UPDATE `#__wpl_user_group_types` SET `editable`='0', `deletable`='0', `index`='2.00' WHERE `id`='2';

UPDATE `#__wpl_dbst` SET `editable`='1' WHERE `id`='906';
UPDATE `#__wpl_dbst_types` SET `kind`='[0][1][2]' WHERE `id`='6';

UPDATE `#__wpl_dbst` SET `specificable`='1' WHERE `id`='912';
UPDATE `#__wpl_dbst` SET `specificable`='1' WHERE `id`='913';
ALTER TABLE `#__wpl_dbst` ADD `user_specific` VARCHAR(200) NULL AFTER `property_type_specific`;

UPDATE `#__wpl_dbst` SET `category`='10' WHERE `id`='912';
UPDATE `#__wpl_dbst` SET `category`='10' WHERE `id`='911';
UPDATE `#__wpl_dbst` SET `category`='10' WHERE `id`='913';

INSERT INTO `#__wpl_user_group_types` (`id`, `name`, `editable`, `deletable`, `index`, `params`, `enabled`) VALUES (3, 'Guests', 0, 0, 0.50, NULL, 1);
UPDATE `#__wpl_users` SET `membership_type`='3' WHERE `id`='-2';
UPDATE `#__wpl_users` SET `membership_type`='3' WHERE `id`='0';

ALTER TABLE `#__wpl_users` DROP `maccess_rank_start`, DROP `maccess_attach`;
ALTER TABLE `#__wpl_users` DROP `maccess_renewal_period`;

ALTER TABLE `#__wpl_properties` CHANGE `field_312` `field_312` VARCHAR(70) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
CHANGE `field_313` `field_313` VARCHAR(70) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;

INSERT INTO `#__wpl_dbst_types` (`id`, `kind`, `type`, `enabled`, `index`, `queries_add`, `queries_delete`) VALUES
(19, '[0][1][2]', 'boolean', 1, 19.00, 'ALTER TABLE `#__[TABLE_NAME]` ADD `field_[FIELD_ID]` TINYINT( 4 ) NOT NULL DEFAULT ''[DEFAULT_VALUE]''; UPDATE `#__wpl_dbst` SET `table_name`=''[TABLE_NAME]'', `table_column`=''field_[FIELD_ID]'' WHERE id=[FIELD_ID];', 'ALTER TABLE `#__[TABLE_NAME]`\r\nDROP `field_[FIELD_ID]`;');

ALTER TABLE `#__wpl_properties` ADD `show_address` TINYINT(4) NOT NULL DEFAULT '1' AFTER `location7_name`;

UPDATE `#__wpl_settings` SET `title`='Watermark Logo' WHERE `id`='11';
DELETE FROM `#__wpl_activities` WHERE `id`='1';

ALTER TABLE `#__wpl_dbst_types` ADD `options` TEXT NULL;

ALTER TABLE `#__wpl_users` ADD `index` FLOAT(5, 2) NOT NULL DEFAULT '99.00' AFTER `membership_type`;

UPDATE `#__wpl_dbst` SET `editable`='1', `specificable`='0' WHERE `id`='51';
UPDATE `#__wpl_dbst` SET `editable`='1', `specificable`='0' WHERE `id`='52';

ALTER TABLE `#__wpl_users` ADD `access_receive_notifications` TINYINT(4) NOT NULL DEFAULT '1' AFTER `access_change_user`;
ALTER TABLE `#__wpl_items` ADD `item_extra4` TEXT NULL AFTER `item_extra3`, ADD `item_extra5` TEXT NULL AFTER `item_extra4`;
ALTER TABLE `#__wpl_properties` ADD `source` VARCHAR(100) NOT NULL DEFAULT 'wpl' AFTER `alias`, ADD `last_sync_date` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00' AFTER `source`;

UPDATE `#__wpl_dbst` SET `enabled`='2' WHERE `id`='313';
UPDATE `#__wpl_dbst` SET `enabled`='2', `deletable`='0' WHERE `id`='312';
UPDATE `#__wpl_dbst` SET `enabled`='2', `deletable`='0' WHERE `id`='308';

UPDATE `#__wpl_settings` SET `index`='3.00' WHERE `id`='50';
INSERT INTO `#__wpl_settings` (`id`, `setting_name`, `setting_value`, `showable`, `category`, `type`, `title`, `params`, `options`, `index`) VALUES
(90, 'property_alias_pattern', '[property_type][glue][listing_type][glue][location][glue][rooms][glue][bedrooms][glue][bathrooms][glue][price]', 1, 4, 'pattern', 'Property Link Pattern', '{"tooltip":"You can remove the parameters or change the positions. Don''t add new parameters!"}', '', 4.00);

UPDATE `#__wpl_settings` SET `type`='pattern' WHERE `id`='55';
UPDATE `#__wpl_settings` SET `type`='pattern' WHERE `id`='56';

ALTER TABLE `#__wpl_dbst` CHANGE `index` `index` FLOAT(9, 3) NOT NULL DEFAULT '99.00';
UPDATE `#__wpl_properties` SET `alias`='';

ALTER TABLE `#__wpl_property_types` DROP `keyword`;
ALTER TABLE `#__wpl_menus` CHANGE `index` `index` FLOAT(6, 3) NOT NULL DEFAULT '1.00';

INSERT INTO `#__wpl_extensions` (`id`, `type`, `title`, `parent`, `description`, `enabled`, `param1`, `param2`, `param3`, `param4`, `param5`, `params`, `editable`, `index`, `client`)
VALUES (110, 'javascript', 'qTips', '0', '', '1', 'qtips', 'js/qtips/jquery.qtip.min.js', '', '', '', '', '0', '110.00', '1');

INSERT INTO `#__wpl_extensions` (`id`, `type`, `title`, `parent`, `description`, `enabled`, `param1`, `param2`, `param3`, `param4`, `param5`, `params`, `editable`, `index`, `client`)
VALUES (111, 'javascript', 'ImageLoaded', '0', '', '1', 'imageloaded', 'js/qtips/imagesloaded.pkg.min.js', '', '', '', '', '0', '110.01', '1');

ALTER TABLE `#__wpl_user_group_types` ADD `default_membership_id` INT(10) NOT NULL DEFAULT '-1' AFTER `name`;

UPDATE `#__wpl_user_group_types` SET `editable`='1' WHERE `id`='1';
UPDATE `#__wpl_user_group_types` SET `editable`='1' WHERE `id`='2';
UPDATE `#__wpl_user_group_types` SET `editable`='1' WHERE `id`='3';

ALTER TABLE `#__wpl_user_group_types` ADD `description` TEXT NULL AFTER `default_membership_id`;
ALTER TABLE `#__wpl_users` ADD `maccess_short_description` TEXT NULL AFTER `maccess_upgradable_to`, ADD `maccess_long_description` TEXT NULL AFTER `maccess_short_description`;

ALTER TABLE `#__wpl_properties` ADD `expired` TINYINT(4) NOT NULL DEFAULT '0' AFTER `confirmed`;
ALTER TABLE `#__wpl_users` ADD `expired` TINYINT(4) NOT NULL DEFAULT '0' AFTER `maccess_long_description`, ADD `expiry_date` DATETIME NULL AFTER `expired`;

UPDATE `#__wpl_dbst` SET `specificable`='0' WHERE `id`='310';
UPDATE `#__wpl_dbst` SET `specificable`='0' WHERE `id`='311';