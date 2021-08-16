SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

DROP TABLE IF EXISTS `ads`;
CREATE TABLE IF NOT EXISTS `ads` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `largeRect1` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `largeRect1Status` tinyint(1) NOT NULL,
  `largeRect2` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `largeRect2Status` tinyint(1) NOT NULL,
  `largeRect3` varchar(2000) NOT NULL,
  `largeRect3Status` int(11) NOT NULL,
  `largeRect1StatusResponsive` int(11) NOT NULL,
  `largeRect2StatusResponsive` int(11) NOT NULL,
  `largeRect3StatusResponsive` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;
TRUNCATE TABLE `ads`;

INSERT INTO `ads` (`id`, `largeRect1`, `largeRect1Status`, `largeRect2`, `largeRect2Status`, `largeRect3`, `largeRect3Status`, `largeRect1StatusResponsive`, `largeRect2StatusResponsive`, `largeRect3StatusResponsive`) VALUES
(1, '<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>\r\n<!-- responsive_ad_unit -->\r\n<ins class="adsbygoogle"\r\n     style="display:block"\r\n     data-ad-client="ca-pub-1706865407555688"\r\n     data-ad-slot="2216561353"\r\n     data-ad-format="auto"></ins>\r\n<script>\r\n(adsbygoogle = window.adsbygoogle || []).push({});\r\n</script>', 1, ' <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>\r\n<!-- responsive_ad_unit -->\r\n<ins class="adsbygoogle"\r\n     style="display:block"\r\n     data-ad-client="ca-pub-1706865407555688"\r\n     data-ad-slot="2216561353"\r\n     data-ad-format="auto"></ins>\r\n<script>\r\n(adsbygoogle = window.adsbygoogle || []).push({});\r\n</script>', 1, ' <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>\r\n<!-- responsive_ad_unit -->\r\n<ins class="adsbygoogle"\r\n     style="display:block"\r\n     data-ad-client="ca-pub-1706865407555688"\r\n     data-ad-slot="2216561353"\r\n     data-ad-format="auto"></ins>\r\n<script>\r\n(adsbygoogle = window.adsbygoogle || []).push({});\r\n</script>', 1, 1, 1, 1);

DROP TABLE IF EXISTS `links`;
CREATE TABLE IF NOT EXISTS `links` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `english` varchar(100) NOT NULL,
  `url` text NOT NULL,
  `showIn` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `newTab` int(11) NOT NULL,
  `displayOrder` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;
TRUNCATE TABLE `links`;

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

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
('reviews', 1);

DROP TABLE IF EXISTS `articlesLanguage`;

CREATE TABLE IF NOT EXISTS `articlesLanguage` (
  `id` int(11) NOT NULL,
  `language` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `summary` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `cacheSettings`;

CREATE TABLE IF NOT EXISTS `cacheSettings` (
  `recentCache` int(1) NOT NULL,
  `recentExpTime` int(10) NOT NULL,
  `categoryCache` int(1) NOT NULL,
  `categoryExpTime` int(10) NOT NULL,
  `tagsCache` int(1) NOT NULL,
  `tagsExpTime` int(10) NOT NULL,
  `relatedCache` int(1) NOT NULL,
  `relatedExpTime` int(10) NOT NULL,
  `productCache` int(1) NOT NULL,
  `productExpTime` int(10) NOT NULL,
  `articleCache` int(11) NOT NULL,
  `articleExpTime` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

TRUNCATE TABLE `cacheSettings`;

INSERT INTO `cacheSettings` (`recentCache`, `recentExpTime`, `categoryCache`, `categoryExpTime`, `tagsCache`, `tagsExpTime`, `relatedCache`, `relatedExpTime`, `productCache`, `productExpTime`, `articleCache`, `articleExpTime`) VALUES
(0, 3600, 0, 3600, 0, 3600, 0, 3600, 0, 3600, 0, 3600);

DROP TABLE IF EXISTS `captchaSettings`;

CREATE TABLE IF NOT EXISTS `captchaSettings` (
  `adminCaptcha` tinyint(1) NOT NULL,
  `contactCaptcha` tinyint(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

TRUNCATE TABLE `captchaSettings`;

INSERT INTO `captchaSettings` (`adminCaptcha`, `contactCaptcha`) VALUES
(1, 1);

DROP TABLE `categories`;

CREATE TABLE IF NOT EXISTS `categories` (
  `parentId` int(11) NOT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `permalink` varchar(65) NOT NULL,
  `english` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(500) NOT NULL,
  `keywords` varchar(200) NOT NULL,
  `displayOrder` int(11) NOT NULL,
  `status` int(1) NOT NULL,
  `limit` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=15 ;

TRUNCATE TABLE `categories`;

INSERT INTO `categories` (`parentId`, `id`, `permalink`, `english`, `description`, `keywords`, `displayOrder`, `status`, `limit`) VALUES
(0, 2, 'electronics-1', 'Electronics', 'Electronics', 'Electronics', 6, 0, 4),
(2, 3, 'notebook', 'Notebook', 'Notebook', 'Notebook', 0, 1, 0),
(0, 4, 'mobile-phones', 'Mobile Phones', 'mobile phones', 'mobile phones', 2, 1, 4),
(0, 5, 'tablet-pc', 'Tablet PC', 'tablet pc', 'tablet pc', 3, 1, 4),
(0, 6, 'digital-cameras', 'Digital Cameras', 'digital cameras', 'digital cameras', 0, 0, 4),
(0, 7, 'laptops-1', 'Laptops', 'Laptops', 'Laptops', 1, 1, 4),
(0, 8, 'desktop-computers-1', 'Desktop Computers', 'desktop computers', 'desktop computers', 4, 1, 4),
(0, 9, 'video-games-1-1', 'Video Games', 'Video Games', 'Video Games', 5, 1, 4),
(0, 10, 'movies', 'Movies', 'Movies', 'Movies', 7, 1, 4),
(0, 11, 'music-1', 'Music', 'Music', 'Music', 8, 1, 4);

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
(1, 1, '', 1, 10);

DROP TABLE IF EXISTS `currencySettings`;

CREATE TABLE IF NOT EXISTS `currencySettings` (
  `crName` varchar(50) NOT NULL,
  `priceDollor` float NOT NULL,
  `showPlace` tinyint(4) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

TRUNCATE TABLE `currencySettings`;

INSERT INTO `currencySettings` (`crName`, `priceDollor`, `showPlace`) VALUES
('$', 1, 0);

DROP TABLE IF EXISTS `expire`;

CREATE TABLE IF NOT EXISTS `expire` (
  `expireStatus` int(11) NOT NULL,
  `date` date NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

TRUNCATE TABLE `expire`;

INSERT INTO `expire` (`expireStatus`, `date`) VALUES
(1, '2015-05-29');

DROP TABLE IF EXISTS `hotProducts`;

CREATE TABLE IF NOT EXISTS `hotProducts` (
  `productId` varchar(100) NOT NULL,
  `todayClicks` int(11) NOT NULL,
  `weeklyClicks` int(11) NOT NULL,
  `monthlyClicks` int(11) NOT NULL,
  `alltimeClicks` int(11) NOT NULL,
  `date` varchar(30) NOT NULL,
  `weekUpdateDate` date NOT NULL,
  `monthUpdateDate` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
(18, 'english', 'english.php', 1, 1, 0);

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

DROP TABLE IF EXISTS `pages`;

CREATE TABLE IF NOT EXISTS `pages` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `permalink` varchar(80) NOT NULL,
  `english` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `keywords` text NOT NULL,
  `status` tinyint(1) NOT NULL,
  `showIn` int(1) NOT NULL,
  `displayOrder` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

DROP TABLE IF EXISTS `pagesLanguage`;

CREATE TABLE IF NOT EXISTS `pagesLanguage` (
  `id` int(11) NOT NULL,
  `language` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `content` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `procat`;

CREATE TABLE IF NOT EXISTS `procat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL,
  `cid` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`),
  KEY `cid` (`cid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=129 ;

DROP TABLE IF EXISTS `products`;

CREATE TABLE IF NOT EXISTS `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userType` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `permalink` varchar(253) CHARACTER SET latin1 NOT NULL,
  `image` varchar(253) CHARACTER SET latin1 NOT NULL,
  `url` text CHARACTER SET latin1 NOT NULL,
  `originalPrice` float NOT NULL,
  `salePrice` float NOT NULL,
  `saleStatus` int(1) NOT NULL,
  `featured` int(1) NOT NULL,
  `tags` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(4) NOT NULL,
  `clicks` int(11) NOT NULL,
  `views` int(11) NOT NULL,
  `submitDate` date NOT NULL,
  `publishedDate` date NOT NULL,
  `updatedDate` date NOT NULL,
  `approvedBy` int(10) NOT NULL,
  `expiryDate` date NOT NULL,
  `image1` varchar(253) CHARACTER SET latin1 NOT NULL,
  `image2` varchar(253) CHARACTER SET latin1 NOT NULL,
  `image3` varchar(253) CHARACTER SET latin1 NOT NULL,
  `image4` varchar(253) CHARACTER SET latin1 NOT NULL,
  `image5` varchar(253) CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=49 ;

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

TRUNCATE TABLE IF EXISTS `publisherRoles`;

INSERT INTO `publisherRoles` (`id`, `productApproved`, `productEdit`, `productDelete`, `articleApproved`, `articleEdit`, `articleDelete`) VALUES
(1, 1, 1, 1, 1, 1, 1);

DROP TABLE IF EXISTS `ratings`;

CREATE TABLE IF NOT EXISTS `ratings` (
  `i` int(11) NOT NULL AUTO_INCREMENT,
  `id` int(11) NOT NULL,
  `rating` int(11) NOT NULL,
  `ip` varchar(50) NOT NULL,
  PRIMARY KEY (`i`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=103 ;

DROP TABLE IF EXISTS `rssSettings`;

CREATE TABLE IF NOT EXISTS `rssSettings` (
  `enable` tinyint(1) NOT NULL,
  `limitRss` int(11) NOT NULL,
  `descLength` int(11) NOT NULL,
  `recentRssEnable` int(1) NOT NULL,
  `catRssEnable` tinyint(1) NOT NULL,
  `tagRssEnable` tinyint(4) NOT NULL,
  `topRssEnable` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

TRUNCATE TABLE `rssSettings`;

INSERT INTO `rssSettings` (`enable`, `limitRss`, `descLength`, `recentRssEnable`, `catRssEnable`, `tagRssEnable`, `topRssEnable`) VALUES
(1, 15, 500, 1, 1, 1, 1);

DROP TABLE IF EXISTS `settings`;

CREATE TABLE IF NOT EXISTS `settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rootpath` varchar(254) NOT NULL,
  `websiteName` varchar(100) NOT NULL,
  `metaTags` varchar(160) NOT NULL,
  `title` varchar(70) NOT NULL,
  `description` varchar(160) NOT NULL,
  `frontPageLogo` varchar(200) NOT NULL,
  `favicon` varchar(200) NOT NULL,
  `urlStructure` int(1) NOT NULL,
  `httpsStatus` int(1) NOT NULL,
  `version` float NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

TRUNCATE TABLE `settings`;

INSERT INTO `settings` (`id`, `rootpath`, `websiteName`, `metaTags`, `title`, `description`, `frontPageLogo`, `favicon`, `urlStructure`, `httpsStatus`, `version`) VALUES
(1, '', '', 'affiliate,portal,products', '', 'Affiliate Store Script is a powerful PHP based script helping you earn affiliate commission through Affiliate links', 'logo.png', 'favicon.png', 1, 0, 1.4);

DROP TABLE IF EXISTS `sitemaps`;

CREATE TABLE IF NOT EXISTS `sitemaps` (
  `categoriesStatus` tinyint(4) NOT NULL,
  `pagesStatus` tinyint(4) NOT NULL,
  `contactFormStatus` tinyint(4) NOT NULL,
  `postsStatus` tinyint(4) NOT NULL,
  `outputPath` varchar(50) NOT NULL,
  `lastModified` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
ALTER TABLE `sitemaps` ADD `productsLimit` INT NOT NULL AFTER `otherFiles`;
TRUNCATE TABLE `sitemaps`;
ALTER TABLE `sitemaps` ADD `otherFiles` VARCHAR(500) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL AFTER `outputPath`;
ALTER TABLE `sitemaps` CHANGE `outputPath` `outputPath` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;

INSERT INTO `sitemaps` (`categoriesStatus`, `pagesStatus`, `contactFormStatus`, `postsStatus`, `outputPath`, `otherFiles`,`productsLimit`,`lastModified`) VALUES
(1, 1, 1, 1, '','',50, '2015-05-27 01:23:37');

DROP TABLE IF EXISTS `socialProfiles`;

CREATE TABLE IF NOT EXISTS `socialProfiles` (
  `facebook` varchar(50) NOT NULL,
  `twitter` varchar(50) NOT NULL,
  `google` varchar(50) NOT NULL,
  `linkedin` varchar(50) NOT NULL,
  `pinterest` varchar(50) NOT NULL,
  `status` int(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

TRUNCATE TABLE `socialProfiles`;

INSERT INTO `socialProfiles` (`facebook`, `twitter`, `google`, `linkedin`, `pinterest`, `status`) VALUES
('nexthon', 'nexthon', 'nexthon', 'nexthon', 'nexthon', 1);

DROP TABLE IF EXISTS `stats`;

CREATE TABLE IF NOT EXISTS `stats` (
  `pageviews` int(11) NOT NULL,
  `uniqueHits` int(11) NOT NULL,
  `clicks` int(11) NOT NULL,
  `datetime` date NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `user`;

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `type` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

TRUNCATE TABLE `user`;

INSERT INTO `user` (`id`, `username`, `password`, `email`, `type`) VALUES
(1, '', '', '', 1);

DROP TABLE IF EXISTS `analytics`;

CREATE TABLE IF NOT EXISTS `analytics` (
  `code` varchar(1000) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

TRUNCATE TABLE `analytics`;

INSERT INTO `analytics` (`code`, `status`) VALUES
('<script>\r\n  (function(i,s,o,g,r,a,m){i[''GoogleAnalyticsObject'']=r;i[r]=i[r]||function(){\r\n  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),\r\n  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)\r\n  })(window,document,''script'',''//www.google-analytics.com/analytics.js'',''ga'');\r\n\r\n  ga(''create'', ''UA-2327545-16'', ''vidsdownloader.com'');\r\n  ga(''send'', ''pageview'');\r\n\r\n</script>', 1);

DROP TABLE IF EXISTS `socialLogin`;

CREATE TABLE IF NOT EXISTS `socialLogin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `loginType` varchar(10) NOT NULL,
  `status` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

DROP TABLE IF EXISTS `favourite`;

CREATE TABLE IF NOT EXISTS `favourite` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `pid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=65 ;

DROP TABLE IF EXISTS `apiSettings`;

CREATE TABLE IF NOT EXISTS `apiSettings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `appId` varchar(100) NOT NULL,
  `appSecret` varchar(100) NOT NULL,
  `consumerKey` varchar(100) NOT NULL,
  `consumerSecret` varchar(100) NOT NULL,
  `allow` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

TRUNCATE TABLE `apiSettings`;

INSERT INTO `apiSettings` (`id`, `appId`, `appSecret`, `consumerKey`, `consumerSecret`, `allow`) VALUES
(1, '410043625867223', '4c5a4f83f0d2adf2a481040ea3fe8845', 'Q6cvXwaV9MubpOEB0Zpc1VW2U', 'p1wQ6SdTcQHwnbNiNOp75k21OIRkp53KCP4jd2eHO7rryLQKwv', 1);

ALTER TABLE `articleCategories` CHANGE `english` `english` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;
ALTER TABLE `articleCategories` CHANGE `permalink` `permalink` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;
ALTER TABLE `articleCategories` CHANGE `description` `description` VARCHAR(300) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;
ALTER TABLE `articleCategories` CHANGE `keywords` `keywords` VARCHAR(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;
ALTER TABLE `articles` CHANGE `english` `english` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;
ALTER TABLE `articles` CHANGE `permalink` `permalink` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;
ALTER TABLE `articles` CHANGE `image` `image` VARCHAR(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;

ALTER TABLE `categories` CHANGE `english` `english` VARCHAR(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;

ALTER TABLE `categories` CHANGE `permalink` `permalink` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;
ALTER TABLE `categories` CHANGE `description` `description` VARCHAR(500) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;
ALTER TABLE `categories` CHANGE `keywords` `keywords` VARCHAR(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;
ALTER TABLE `currencySettings` CHANGE `crName` `crName` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;
ALTER TABLE `languages` CHANGE `languageName` `languageName` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;
ALTER TABLE `languages` CHANGE `languageFile` `languageFile` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;

ALTER TABLE `pages` CHANGE `english` `english` VARCHAR(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;
ALTER TABLE `pages` CHANGE `permalink` `permalink` VARCHAR(80) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;
ALTER TABLE `pages` CHANGE `keywords` `keywords` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;

ALTER TABLE `pagesLanguage` CHANGE `language` `language` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;

ALTER TABLE `pagesLanguage` CHANGE `content` `content` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;

ALTER TABLE `pagesLanguage` CHANGE `description` `description` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;

ALTER TABLE `productsLanguage` CHANGE `language` `language` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;

ALTER TABLE `productsLanguage` CHANGE `title` `title` VARCHAR(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;

ALTER TABLE `productsLanguage` CHANGE `description` `description` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;

ALTER TABLE `productsLanguage` CHANGE `summary` `summary` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;

ALTER TABLE `products` CHANGE `permalink` `permalink` VARCHAR(253) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;

ALTER TABLE `products` CHANGE `tags` `tags` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;

ALTER TABLE `products` CHANGE `image` `image` VARCHAR(253) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;
ALTER TABLE `products` CHANGE `image1` `image1` VARCHAR(253) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;
ALTER TABLE `products` CHANGE `image2` `image2` VARCHAR(253) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;
ALTER TABLE `products` CHANGE `image3` `image3` VARCHAR(253) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;
ALTER TABLE `products` CHANGE `image4` `image4` VARCHAR(253) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;
ALTER TABLE `products` CHANGE `image5` `image5` VARCHAR(253) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;