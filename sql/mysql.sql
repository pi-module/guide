CREATE TABLE `{item}` (
    `id` int(10) unsigned NOT NULL auto_increment,

    PRIMARY KEY (`id`)
); 

CREATE TABLE `{category}` (
    `id` int(10) unsigned NOT NULL auto_increment,

    PRIMARY KEY (`id`)
); 

CREATE TABLE `{link}` (
    `id` int(10) unsigned NOT NULL auto_increment,

    PRIMARY KEY (`id`)
);

CREATE TABLE `{location}` (
    `id` int(10) unsigned NOT NULL auto_increment,

    PRIMARY KEY (`id`)
);

CREATE TABLE `{location_category}` (
    `id` int(10) unsigned NOT NULL auto_increment,

    PRIMARY KEY (`id`)
);

CREATE TABLE `{attach}` (
    `id` int(10) unsigned NOT NULL auto_increment,

    PRIMARY KEY (`id`)
);

CREATE TABLE `{field}` (
    `id` int(10) unsigned NOT NULL auto_increment,

    PRIMARY KEY (`id`)
);

CREATE TABLE `{field_data}` (
    `id` int(10) unsigned NOT NULL auto_increment,

    PRIMARY KEY (`id`)
);

CREATE TABLE `{service}` (
    `id` int(10) unsigned NOT NULL auto_increment,

    PRIMARY KEY (`id`)
);

CREATE TABLE `{review}` (
    `id` int(10) unsigned NOT NULL auto_increment,

    PRIMARY KEY (`id`)
);

CREATE TABLE `{blog}` (
    `id` int(10) unsigned NOT NULL auto_increment,

    PRIMARY KEY (`id`)
);