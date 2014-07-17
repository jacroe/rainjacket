-- phpMyAdmin SQL Dump
-- version 4.0.6deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 25, 2014 at 12:06 PM
-- Server version: 5.5.37-0ubuntu0.13.10.1
-- PHP Version: 5.5.3-1ubuntu2.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `rainjacket`
--

-- --------------------------------------------------------

--
-- Table structure for table `forecastio`
--

CREATE TABLE IF NOT EXISTS `forecastio` (
  `location` varchar(20) NOT NULL,
  `data` text CHARACTER SET utf8 NOT NULL,
  `expires` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`location`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `forecasts`
--

CREATE TABLE IF NOT EXISTS `forecasts` (
  `user` varchar(60) NOT NULL,
  `raw` text NOT NULL COMMENT 'Data returned from the Python scripts',
  `processed` text NOT NULL COMMENT 'Data used to display information to customer',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`user`),
  UNIQUE KEY `user` (`user`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `pollen`
--

CREATE TABLE IF NOT EXISTS `pollen` (
  `zipcode` varchar(5) NOT NULL,
  `data` text NOT NULL,
  `expires` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`zipcode`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `templates`
--

CREATE TABLE IF NOT EXISTS `templates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `isDay` tinyint(1) NOT NULL COMMENT 'Is it day or is it night?',
  `isPrecip` tinyint(1) NOT NULL COMMENT 'Is it precipitating at all?',
  `isStopping` tinyint(1) NOT NULL COMMENT 'If it''s precipitating, will it stop by the end of the day?',
  `template` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=18 ;

--
-- Dumping data for table `templates`
--

INSERT INTO `templates` (`id`, `isDay`, `isPrecip`, `isStopping`, `template`) VALUES
(1, 1, 1, 1, 'It''s a {{$tempAdj}} day with highs in the {{$temp}} and {{$topPrecipType}} from {{$startPrecipTime}} to {{$endPrecipTime}}. Be sure to bring a RAIN JACKET. Get it? I need friends...'),
(2, 1, 1, 0, 'It''s a {{$tempAdj}} day with highs in the {{$temp}} with {{$topPrecipType}} starting at {{$startPrecipTime}} and continuing throughout the day. Be sure to bring a RAIN JACKET. Get it? I need friends...'),
(3, 1, 0, 0, 'It''s a {{$tempAdj}} day with highs in the {{$temp}}. Something something, sweater weather.'),
(4, 0, 1, 1, 'Lows tonight will be in the {{$temp}}. {{$topPrecipType|ucfirst}} from {{$startPrecipTime}} to {{$endPrecipTime}}. Sounds like perfect cuddling weather.'),
(5, 0, 1, 0, 'Lows tonight will be in the {{$temp}}. {{$topPrecipType|ucfirst}} from {{$startPrecipTime}} and continuing throughout the night. Sounds like perfect cuddling weather.'),
(6, 0, 0, 0, 'Lows tonight will be in the {{$temp}}. Hot chocolate, anyone?'),
(7, 1, 0, 0, 'Give a high five for a high in the {{$temp}}! ....no? Okay then. Sad face...'),
(8, 1, 1, 1, 'Don''t forget the umbrella; a {{$tempAdj}} day lies ahead with highs in the {{$temp}} and {{$topPrecipType}} from {{$startPrecipTime}} to {{$endPrecipTime}}.'),
(9, 1, 1, 1, 'Don''t forget the umbrella; a {{$tempAdj}} day lies ahead with highs in the {{$temp}} with {{$topPrecipType}} starting around {{$startPrecipTime}}.'),
(10, 1, 0, 0, 'What''s that? You wished for no rain today? Well with highs in the {{$temp}}, it''s come true. Enjoy!'),
(11, 0, 0, 0, 'Tonight: {{$tempAdj}}. Rain: nope. Just you, me, and Netflix. Feels like a great night to get caught up on "Psych".'),
(12, 1, 1, 0, 'Looks like the day will be marred with a bit of {{$topPrecipType}} around {{$startPrecipTime}}. Don''t worry; the sun''ll come out tomorrow! (Maybe.)'),
(13, 1, 1, 1, 'Looks like the day will be marred with a bit of {{$topPrecipType}} from {{$startPrecipTime}} to {{$endPrecipTime}}. Don''t worry; the sun''ll come out tomorrow! (Maybe.)'),
(14, 1, 0, 0, 'Nothing headed our way on this fine day (if you define "fine" as being {{$tempAdj}} with temps in the {{$temp}}, that is).'),
(15, 0, 1, 1, 'We''ve got some {{$topPrecipType}} coming in tonight which makes napping pretty much mandatory. Expect it around {{$startPrecipTime}}.'),
(16, 0, 1, 0, 'We''ve got some {{$topPrecipType}} coming in tonight which makes napping pretty much mandatory. Expect it around {{$startPrecipTime}}.'),
(17, 0, 0, 0, 'No precipitation expected tonight, just a low in the {{$temp}}. But be wary; rain is a sneaky joker. Constant vigilance. ');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(60) NOT NULL,
  `password` varchar(60) NOT NULL,
  `email` text NOT NULL,
  `phone` text NOT NULL,
  `zipcode` char(5) NOT NULL DEFAULT '39406',
  `timezone` varchar(20) NOT NULL,
  `dayTime` char(4) NOT NULL,
  `nightTime` char(4) NOT NULL,
  `pollenForecast` tinyint(1) NOT NULL DEFAULT '0',
  `badHairDay` tinyint(1) NOT NULL DEFAULT '0',
  `sendBy` int(11) NOT NULL DEFAULT '1',
  `timeAdded` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Table structure for table `zipcodes`
--

CREATE TABLE IF NOT EXISTS `zipcodes` (
  `zipcode` char(5) NOT NULL,
  `city` text NOT NULL,
  `state` text NOT NULL,
  `latitude` float NOT NULL,
  `longitude` float NOT NULL,
  `timezone` text NOT NULL,
  PRIMARY KEY (`zipcode`),
  UNIQUE KEY `zipcode` (`zipcode`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;