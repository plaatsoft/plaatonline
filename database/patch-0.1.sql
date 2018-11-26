--
--  ===========
--  PlaatOnline
--  ===========
--
--  Created by wplaat
--
--  For more information visit the following website.
--  Website : www.plaatsoft.nl 
--
--  Or send an email to the following address.
--  Email   : info@plaatsoft.nl
--
--  All copyrights reserved (c) 2008-2016 PlaatSoft
--

-- CONFIG TABLE

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE TABLE `config` (
  `id` int(11) NOT NULL,
  `category` int(11) NOT NULL,
  `token` varchar(32) NOT NULL,
  `value` varchar(128) NOT NULL,
  `options` varchar(255) NOT NULL,
  `last_update` date NOT NULL,
  `readonly` tinyint(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

INSERT INTO `config` (`id`, `category`, `token`, `value`, `options`, `last_update`, `readonly`) VALUES
(1, 0, 'database_version', '0.1', '', '2017-09-13', 1);

INSERT INTO `config` (`id`, `category`, `token`, `value`, `options`, `last_update`, `readonly`) 
VALUES (NULL, '0', 'timezone', 'Europe/Amsterdam', '', '2017-09-13', '0');

ALTER TABLE `config`
  ADD PRIMARY KEY (`id`);
  
ALTER TABLE `config`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
  
-- INVENTORY TABLE

CREATE TABLE `inventory` (
  `iid` int(11) NOT NULL,
  `name` varchar(128) NOT NULL,
  `address` varchar(128) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

INSERT INTO `inventory` (`iid`, `name`, `address`) VALUES
(1, 'test', '127.0.0.1');

ALTER TABLE `inventory`
  ADD PRIMARY KEY (`iid`);
  
ALTER TABLE `inventory`
  MODIFY `iid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
  
-- ONLINE TABLE
  
CREATE TABLE `online` (
  `oid` int(11) NOT NULL,
  `iid` int(11) NOT NULL,
  `created` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

ALTER TABLE `online`
  ADD PRIMARY KEY (`oid`);

ALTER TABLE `online`
  MODIFY `oid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;



