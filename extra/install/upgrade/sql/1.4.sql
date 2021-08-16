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

ALTER TABLE `products` CHANGE `permalink` `permalink` VARCHAR(253) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;
ALTER TABLE `products` CHANGE `tags` `tags` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;

ALTER TABLE `products` CHANGE `image` `image` VARCHAR(253) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;
ALTER TABLE `products` CHANGE `image1` `image1` VARCHAR(253) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;
ALTER TABLE `products` CHANGE `image2` `image2` VARCHAR(253) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;
ALTER TABLE `products` CHANGE `image3` `image3` VARCHAR(253) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;
ALTER TABLE `products` CHANGE `image4` `image4` VARCHAR(253) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;
ALTER TABLE `products` CHANGE `image5` `image5` VARCHAR(253) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;

ALTER TABLE `productsLanguage` CHANGE `language` `language` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;

ALTER TABLE `productsLanguage` CHANGE `title` `title` VARCHAR(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;

ALTER TABLE `productsLanguage` CHANGE `description` `description` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;

ALTER TABLE `productsLanguage` CHANGE `summary` `summary` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;
ALTER TABLE `sitemaps` ADD `otherFiles` VARCHAR(500) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL AFTER `outputPath`;
ALTER TABLE `sitemaps` CHANGE `outputPath` `outputPath` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;
ALTER TABLE `sitemaps` ADD `productsLimit` INT NOT NULL AFTER `otherFiles`;
UPDATE `sitemaps` SET `productsLimit`='50'