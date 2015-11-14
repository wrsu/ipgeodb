--
-- База данных: `geoip`
--

-- --------------------------------------------------------

--
-- Структура таблицы `city`
--

CREATE TABLE IF NOT EXISTS `city` (
  `id` smallint(5) unsigned NOT NULL,
  `city` varchar(100) NOT NULL,
  `region` varchar(100) NOT NULL,
  `district` varchar(100) NOT NULL,
  `lat` char(10) NOT NULL,
  `lng` char(10) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `ips`
--

CREATE TABLE IF NOT EXISTS `ips` (
  `ip` int(10) unsigned NOT NULL,
  `last` int(10) unsigned NOT NULL,
  `country` char(2) NOT NULL,
  `city` smallint(5) unsigned NOT NULL,
  PRIMARY KEY  (`ip`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `ipwork`
--

CREATE TABLE IF NOT EXISTS `ipwork` (
  `ip` int(10) unsigned NOT NULL,
  `last` int(10) unsigned NOT NULL,
  `country` char(2) NOT NULL,
  `city` smallint(5) unsigned NOT NULL,
  PRIMARY KEY  (`ip`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;