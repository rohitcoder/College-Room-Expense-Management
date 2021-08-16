DROP TABLE IF EXISTS `articleCategories`;

CREATE TABLE IF NOT EXISTS `articleCategories` (
  `cid` int(11) NOT NULL AUTO_INCREMENT,
  `english` varchar(100) NOT NULL,
  `permalink` varchar(100) NOT NULL,
  `description` varchar(300) NOT NULL,
  `keywords` varchar(200) NOT NULL,
  `status` int(11) NOT NULL,
  `displayOrder` int(11) NOT NULL,
  PRIMARY KEY (`cid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

DROP TABLE IF EXISTS `articleRatings`;

CREATE TABLE IF NOT EXISTS `articleRatings` (
  `i` int(11) NOT NULL AUTO_INCREMENT,
  `id` int(11) NOT NULL,
  `rating` int(11) NOT NULL,
  `ip` varchar(100) NOT NULL,
  PRIMARY KEY (`i`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

DROP TABLE IF EXISTS `articles`;

CREATE TABLE IF NOT EXISTS `articles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `cid` int(11) NOT NULL,
  `english` varchar(100) NOT NULL,
  `permalink` varchar(100) NOT NULL,
  `image` varchar(30) NOT NULL,
  `rating` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `views` int(11) NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=18 ;

DROP TABLE IF EXISTS `articleSettings`;

CREATE TABLE IF NOT EXISTS `articleSettings` (
  `name` varchar(60) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

TRUNCATE TABLE `articleSettings`;

INSERT INTO `articleSettings` (`name`, `status`) VALUES
('reviews', 0);

DROP TABLE IF EXISTS `articlesLanguage`;
CREATE TABLE IF NOT EXISTS `articlesLanguage` (
  `id` int(11) NOT NULL,
  `language` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `summary` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
TRUNCATE TABLE `articlesLanguage`;

ALTER TABLE `cacheSettings` ADD `articleCache` INT NOT NULL , ADD `articleExpTime` INT NOT NULL ;

ALTER TABLE `categories` CHANGE `name` `english` VARCHAR(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;

ALTER TABLE `categories` ADD `description` VARCHAR(500) NOT NULL AFTER `english`, ADD `keywords` VARCHAR(200) NOT NULL AFTER `description`;

DROP TABLE IF EXISTS `commentSettings`;
CREATE TABLE IF NOT EXISTS `commentSettings` (
  `productCommentStatus` tinyint(4) NOT NULL,
  `articleCommentStatus` int(11) NOT NULL,
  `disqusUserName` varchar(50) NOT NULL,
  `commentsActive` int(11) NOT NULL,
  `fbCommentsLimit` int(10) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

TRUNCATE TABLE `commentSettings`;
INSERT INTO `commentSettings` (`productCommentStatus`, `articleCommentStatus`, `disqusUserName`, `commentsActive`, `fbCommentsLimit`) VALUES
(1, 1, 'storenew', 1, 10);

DROP TABLE IF EXISTS `expire`;

CREATE TABLE IF NOT EXISTS `expire` (
  `expireStatus` int(11) NOT NULL,
  `date` date NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

TRUNCATE TABLE `expire`;

INSERT INTO `expire` (`expireStatus`, `date`) VALUES
(1, '2015-06-1');

DROP TABLE IF EXISTS `languages`;

CREATE TABLE IF NOT EXISTS `languages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `languageName` varchar(100) CHARACTER SET latin1 NOT NULL,
  `languageFile` varchar(100) CHARACTER SET latin1 NOT NULL,
  `status` int(11) NOT NULL,
  `displayOrder` int(10) NOT NULL,
  `rtlStatus` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=26 ;

TRUNCATE TABLE `languages`;

INSERT INTO `languages` (`id`, `languageName`, `languageFile`, `status`, `displayOrder`, `rtlStatus`) VALUES
(1, 'english', 'english.php', 1, 1, 0);

DROP TABLE IF EXISTS `mediaSettings`;
CREATE TABLE IF NOT EXISTS `mediaSettings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `featuredProductsLimit` int(11) NOT NULL,
  `featuredImageWidth` int(11) NOT NULL,
  `productImageWidth` int(11) NOT NULL,
  `productImageHeight` int(11) NOT NULL,
  `smallThumbnailWidth` int(11) NOT NULL,
  `smallThumbnailHeight` int(11) NOT NULL,
  `mediumThumbnailWidth` int(11) NOT NULL,
  `mediumThumbnailHeight` int(11) NOT NULL,
  `largeThumbnailWidth` int(11) NOT NULL,
  `largeThumbnailHeight` int(11) NOT NULL,
  `featuredImageHeight` int(11) NOT NULL,
  `articleThumbnailWidth` int(11) NOT NULL,
  `articleThumbnailHeight` int(11) NOT NULL,
  `articleShortDescription` int(11) NOT NULL,
  `articleLongDescription` int(11) NOT NULL,
  `perPageProduct` int(11) NOT NULL,
  `shortDescription` int(11) NOT NULL,
  `longDescription` int(10) NOT NULL,
  `relatedProducts` int(11) NOT NULL,
  `relatedProductsLimit` int(11) NOT NULL,
  `articlesLimit` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

TRUNCATE TABLE `mediaSettings`;

INSERT INTO `mediaSettings` (`id`, `featuredProductsLimit`, `featuredImageWidth`, `productImageWidth`, `productImageHeight`, `smallThumbnailWidth`, `smallThumbnailHeight`, `mediumThumbnailWidth`, `mediumThumbnailHeight`, `largeThumbnailWidth`, `largeThumbnailHeight`, `featuredImageHeight`, `articleThumbnailWidth`, `articleThumbnailHeight`, `articleShortDescription`, `articleLongDescription`, `perPageProduct`, `shortDescription`, `longDescription`, `relatedProducts`, `relatedProductsLimit`, `articlesLimit`) VALUES
(1, 10, 640, 250, 250, 100, 100, 800, 800, 800, 800, 347, 200, 200, 200, 600, 10, 200, 600, 1, 4, 10);

ALTER TABLE `pages` CHANGE `title` `english` VARCHAR(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;

DROP TABLE IF EXISTS `pagesLanguage`;
CREATE TABLE IF NOT EXISTS `pagesLanguage` (
  `id` int(11) NOT NULL,
  `language` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `content` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
TRUNCATE TABLE `pagesLanguage`;

DROP TABLE IF EXISTS `productsLanguage`;
CREATE TABLE IF NOT EXISTS `productsLanguage` (
  `ai` int(11) NOT NULL AUTO_INCREMENT,
  `id` int(11) NOT NULL,
  `language` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `summary` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`ai`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=335 ;
TRUNCATE TABLE `productsLanguage`;

DROP TABLE IF EXISTS `publisherRoles`;

CREATE TABLE IF NOT EXISTS `publisherRoles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `productApproved` tinyint(4) NOT NULL,
  `productEdit` tinyint(4) NOT NULL,
  `productDelete` tinyint(4) NOT NULL,
  `articleApproved` int(11) NOT NULL,
  `articleEdit` int(11) NOT NULL,
  `articleDelete` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

TRUNCATE TABLE `publisherRoles`;

INSERT INTO `publisherRoles` (`id`, `productApproved`, `productEdit`, `productDelete`, `articleApproved`, `articleEdit`, `articleDelete`) VALUES
(1, 1, 1, 1, 1, 1, 1);


ALTER TABLE `ratings` DROP `sessionid`, DROP `dateposted`, DROP `timestamp`, DROP `sum_rate`, DROP `rate_times`, DROP `avg_rate`;