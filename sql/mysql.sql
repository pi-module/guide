CREATE TABLE `{item}` (
    `id` int(10) unsigned NOT NULL auto_increment,
    `title` varchar(255) NOT NULL,
    `slug` varchar(255) NOT NULL,
    `category` varchar(255) NOT NULL,
    `summary` text,
    `description` text,
    `seo_title` varchar(255) NOT NULL,
    `seo_keywords` varchar(255) NOT NULL,
    `seo_description` varchar(255) NOT NULL,
    `status` tinyint(1) unsigned NOT NULL,
    `time_create` int(10) unsigned NOT NULL,
    `time_update` int(10) unsigned NOT NULL,
    `time_start` int(10) unsigned NOT NULL,
    `time_end` int(10) unsigned NOT NULL,
    `uid` int(10) unsigned NOT NULL,
    `hits` int(10) unsigned NOT NULL,
    `image` varchar(255) NOT NULL,
    `path` varchar(16) NOT NULL,
    `point` int(10) NOT NULL,
    `count` int(10) unsigned NOT NULL,
    `favourite` int(10) unsigned NOT NULL,
    `service` tinyint(1) unsigned NOT NULL,
    `attach` tinyint(1) unsigned NOT NULL,
    `extra` tinyint(1) unsigned NOT NULL,
    `review` tinyint(1) unsigned NOT NULL,
    `recommended` tinyint(1) unsigned NOT NULL,
    `map_longitude` varchar(16) NOT NULL,
    `map_latitude` varchar(16) NOT NULL,
    `location` int(10) unsigned NOT NULL,
    `location_category` int(10) unsigned NOT NULL,
    `blog` varchar(255) NOT NULL,
    PRIMARY KEY (`id`)
); 

CREATE TABLE `{category}` (
    `id` int (10) unsigned NOT NULL auto_increment,
    `parent` int(5) unsigned NOT NULL default '0',
    `title` varchar(255) NOT NULL,
    `slug` varchar(255) NOT NULL,
    `image` varchar(255) NOT NULL,
    `path` varchar(16) NOT NULL,
    `description` text,
    `seo_title` varchar(255) NOT NULL,
    `seo_keywords` varchar(255) NOT NULL,
    `seo_description` varchar(255) NOT NULL,
    `time_create` int(10) unsigned NOT NULL,
    `time_update` int(10) unsigned NOT NULL,
    `setting` text,
    `status` tinyint(1) unsigned NOT NULL,
    PRIMARY KEY (`id`)
); 

CREATE TABLE `{link}` (
    `id` int (10) unsigned NOT NULL auto_increment,
    `item` int(10) unsigned NOT NULL,
    `category` int(10) unsigned NOT NULL,
    `time_create` int(10) unsigned NOT NULL,
    `time_update` int(10) unsigned NOT NULL,
    `time_start` int(10) unsigned NOT NULL,
    `time_end` int(10) unsigned NOT NULL,
    `status` tinyint(1) unsigned NOT NULL,
    PRIMARY KEY (`id`)
);

CREATE TABLE `{location}` (
    `id` int(10) unsigned NOT NULL auto_increment,
    `category` int(5) unsigned NOT NULL,
    `parent` int(5) unsigned NOT NULL,
    `title` varchar(255) NOT NULL,
    `route` text,
    PRIMARY KEY (`id`)
);

CREATE TABLE `{location_category}` (
    `id` int (10) unsigned NOT NULL auto_increment,
    `parent` int(5) unsigned NOT NULL,
    `child` int(5) unsigned NOT NULL,
    `title` varchar(255) NOT NULL,
    PRIMARY KEY (`id`)
);

CREATE TABLE `{attach}` (
    `id` int (10) unsigned NOT NULL auto_increment,
    `title` varchar (255) NOT NULL,
    `file` varchar (255) NOT NULL,
    `path` varchar(16) NOT NULL,
    `item` int(10) unsigned NOT NULL,
    `time_create` int(10) unsigned NOT NULL,
    `size` int(10) unsigned NOT NULL,
    `type` enum('archive','image','video','audio','pdf','doc','other') NOT NULL,
    `status` tinyint(1) unsigned NOT NULL,
    `hits` int(10) unsigned NOT NULL,
    PRIMARY KEY (`id`)
);

CREATE TABLE `{field}` (
    `id` int (10) unsigned NOT NULL auto_increment,
    `title` varchar (255) NOT NULL,
    `image` varchar (255) NOT NULL,
    `type` enum('text','link','currency','date','number','select','video','audio','file') NOT NULL,
    `order` int(10) unsigned NOT NULL,
    `status` tinyint(1) unsigned NOT NULL default '1',
    `search` tinyint(1) unsigned NOT NULL default '1',
    `value` text,
    PRIMARY KEY (`id`)
);

CREATE TABLE `{field_data}` (
    `id` int (10) unsigned NOT NULL auto_increment,
    `field` int(10) unsigned NOT NULL,
    `item` int(10) unsigned NOT NULL,
    `data` varchar(255) NOT NULL,
    PRIMARY KEY (`id`)
);

CREATE TABLE `{special}` (
    `id` int(10) unsigned NOT NULL auto_increment,
    `item` int(10) unsigned NOT NULL,
    `time_publish` int(10) unsigned NOT NULL,
    `time_expire` int(10) unsigned NOT NULL,
    `status` tinyint(1) unsigned NOT NULL default '1',
    PRIMARY KEY (`id`)
);

CREATE TABLE `{review}` (
    `id` int(10) unsigned NOT NULL auto_increment,
    `uid` int(10) unsigned NOT NULL,
    `item` int(10) unsigned NOT NULL,
    `title` varchar (255) NOT NULL,
    `description` text,
    `image` varchar(255) NOT NULL,
    `path` varchar(16) NOT NULL,
    `time_create` int(10) unsigned NOT NULL,
    `status` tinyint(1) unsigned NOT NULL,
    PRIMARY KEY (`id`)
);

CREATE TABLE `{service}` (
    `id` int(10) unsigned NOT NULL auto_increment,
    `category` int(5) unsigned NOT NULL,
    `title` varchar(255) NOT NULL,
    `price` decimal(16,2) NOT NULL,
    `description` text,
    `image` varchar(255) NOT NULL,
    `path` varchar(16) NOT NULL,
    `item` int(10) unsigned NOT NULL,
    `time_create` int(10) unsigned NOT NULL,
    `status` tinyint(1) unsigned NOT NULL,
    PRIMARY KEY (`id`)
);

CREATE TABLE `{service_category}` (
    `id` int(10) unsigned NOT NULL auto_increment,
    `title` varchar(255) NOT NULL,
    `time_create` int(10) unsigned NOT NULL,
    `status` tinyint(1) unsigned NOT NULL,
    PRIMARY KEY (`id`)
);

CREATE TABLE `{log}` (
    `id` int(10) unsigned NOT NULL auto_increment,
    `uid` int(10) unsigned NOT NULL,
    `ip` char(15) NOT NULL,
    `time_create` int(10) unsigned NOT NULL,
    `section` varchar (32) NOT NULL,
    `item` int(10) unsigned NOT NULL,
    `operation` varchar (32) NOT NULL,
    `description` text,
    PRIMARY KEY (`id`)
);