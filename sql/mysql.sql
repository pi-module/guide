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
    `customer` int(10) unsigned NOT NULL,
    `package` int(10) unsigned NOT NULL,
    `hits` int(10) unsigned NOT NULL,
    `image` varchar(255) NOT NULL,
    `path` varchar(16) NOT NULL,
    `vote` varchar(255) NOT NULL,
    `rating` int(10) unsigned NOT NULL,
    `favourite` int(10) unsigned NOT NULL,
    `service` tinyint(1) unsigned NOT NULL,
    `attach` tinyint(1) unsigned NOT NULL,
    `extra` tinyint(1) unsigned NOT NULL,
    `review` tinyint(1) unsigned NOT NULL,
    `recommended` tinyint(1) unsigned NOT NULL,
    `map_longitude` varchar(16) NOT NULL,
    `map_latitude` varchar(16) NOT NULL,
    `location` int(10) unsigned NOT NULL,
    `location_level` tinyint(1) unsigned NOT NULL,
    `address1` varchar(255) NOT NULL,
    `address2` varchar(255) NOT NULL,
    `city` varchar(32) NOT NULL,
    `area` varchar(32) NOT NULL,
    `zipcode` varchar(16) NOT NULL,
    `phone1` varchar(16) NOT NULL,
    `phone2` varchar(16) NOT NULL,
    `mobile` varchar(16) NOT NULL,
    `website` varchar(64) NOT NULL,
    `email` varchar(64) NOT NULL,
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
    `level` tinyint(1) unsigned NOT NULL,
    `parent` int(5) unsigned NOT NULL,
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
    `image` varchar(255) NOT NULL,
    `path` varchar(16) NOT NULL,
    `time_create` int(10) unsigned NOT NULL,
    `status` tinyint(1) unsigned NOT NULL,
    PRIMARY KEY (`id`)
);

CREATE TABLE `{score}` (
    `id` int(10) unsigned NOT NULL auto_increment,
    `title` varchar(255) NOT NULL,
    `status` tinyint(1) unsigned NOT NULL,
    PRIMARY KEY (`id`)
);

CREATE TABLE `{package}` (
    `id` int(10) unsigned NOT NULL auto_increment,
    `title` varchar(255) NOT NULL,
    `description` text,
    `features` text,
    `status` tinyint(1) unsigned NOT NULL,
    `time_create` int(10) unsigned NOT NULL,
    `time_update` int(10) unsigned NOT NULL,
    `time_period` int(10) unsigned NOT NULL,
    `image` varchar(255) NOT NULL,
    `path` varchar(16) NOT NULL,
    `price` double(16,2) NOT NULL,
    `stock_all` int(10) unsigned NOT NULL,
    `stock_sold` int(10) unsigned NOT NULL,
    `stock_remained` int(10) unsigned NOT NULL,
    PRIMARY KEY (`id`)
);

CREATE TABLE `{customer}` (
    `id` int(10) unsigned NOT NULL auto_increment,
    `uid` int(10) unsigned NOT NULL,
    `first_name` varchar (255) NOT NULL,
    `last_name` varchar (255) NOT NULL,
    `email` varchar (64) NOT NULL,
    `phone` varchar (16) NOT NULL,
    `mobile` varchar (16) NOT NULL,
    `company` varchar (255) NOT NULL,
    `address` text,
    `country` varchar (64) NOT NULL,
    `city` varchar (64) NOT NULL,
    `zip_code` varchar (16) NOT NULL,
    `ip` char(15) NOT NULL,
    PRIMARY KEY (`id`)
);

CREATE TABLE `{order}` (
    `id` int(10) unsigned NOT NULL auto_increment,
    `uid` int(10) unsigned NOT NULL,
    `customer` int(10) unsigned NOT NULL,
    `ip` char(15) NOT NULL,
    `item` int(10) unsigned NOT NULL,
    `package` int(10) unsigned NOT NULL,
    `status_order` tinyint(1) unsigned NOT NULL,
    `status_payment` tinyint(1) unsigned NOT NULL,
    `time_create` int(10) unsigned NOT NULL,
    `time_payment` int(10) unsigned NOT NULL,
    `time_start` int(10) unsigned NOT NULL,
    `time_end` int(10) unsigned NOT NULL,
    `user_note` text,
    `admin_note` text,
    `package_price` double(16,2) NOT NULL,
    `paid_price` double(16,2) NOT NULL,
    `payment_method` enum('online','offline') NOT NULL,
    `payment_adapter` varchar(64) NOT NULL,
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