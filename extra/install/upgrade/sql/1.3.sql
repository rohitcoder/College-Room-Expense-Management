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

DROP TABLE IF EXISTS `analytics`;

CREATE TABLE IF NOT EXISTS `analytics` (
  `code` varchar(1000) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

TRUNCATE TABLE `analytics`;

INSERT INTO `analytics` (`code`, `status`) VALUES
('<script>\r\n  (function(i,s,o,g,r,a,m){i[''GoogleAnalyticsObject'']=r;i[r]=i[r]||function(){\r\n  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),\r\n  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)\r\n  })(window,document,''script'',''//www.google-analytics.com/analytics.js'',''ga'');\r\n\r\n  ga(''create'', ''UA-2327545-16'', ''vidsdownloader.com'');\r\n  ga(''send'', ''pageview'');\r\n\r\n</script>', 1);