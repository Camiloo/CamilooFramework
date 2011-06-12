<?php

$installer = $this;

$installer->startSetup();

$installer->run("

-- DROP TABLE IF EXISTS {$this->getTable('navadmin')};
CREATE TABLE {$this->getTable('navadmin')} (
  `navadmin_id` int(11) unsigned NOT NULL auto_increment,
  `pid` int(11),
  `store_id` int(11),
  `title` varchar(255) NOT NULL default '',
  `link` varchar(255) NOT NULL default '',
  `target` varchar(255) NOT NULL default '',
  `position` int(11),
  `status` smallint(6) NOT NULL default '0',
  `created_time` datetime NULL,
  `update_time` datetime NULL,
  PRIMARY KEY (`navadmin_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

    ");

$installer->endSetup();