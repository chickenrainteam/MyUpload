

DROP TABLE IF EXISTS `fileuploads`;


CREATE TABLE `fileuploads` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`path` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
	`file_type` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
	`file_size` int(11) NOT NULL,
	`created` datetime DEFAULT NULL,	PRIMARY KEY  (`id`)) 	DEFAULT CHARSET=utf8,
	COLLATE=utf8_unicode_ci,
	ENGINE=InnoDB;

