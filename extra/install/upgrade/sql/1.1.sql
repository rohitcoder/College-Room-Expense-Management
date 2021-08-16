ALTER TABLE `products` ADD `summary` TEXT NOT NULL AFTER `description`;
ALTER TABLE `products` ADD `featured` INT(1) NOT NULL AFTER `saleStatus`;

ALTER TABLE `mediaSettings` DROP `imageWidth`;
ALTER TABLE `mediaSettings` DROP `imageHeight`;
ALTER TABLE `mediaSettings` DROP `imageWidthAfterCrop`;
ALTER TABLE `mediaSettings` DROP `imageHeightAfterCrop`;
ALTER TABLE `mediaSettings` DROP `thumbnailWidth`;
ALTER TABLE `mediaSettings` DROP `thumbnailHeight`;

ALTER TABLE `mediaSettings` ADD `featuredProductsLimit` INT NOT NULL AFTER `id`, ADD `featuredImageWidth` INT NOT NULL AFTER `featuredProductsLimit`, ADD `featuredImageHeight` INT NOT NULL AFTER `featuredImageWidth`, ADD `productImageWidth` INT NOT NULL AFTER `featuredImageWidth`, ADD `productImageHeight` INT NOT NULL AFTER `productImageWidth`, ADD `smallThumbnailWidth` INT NOT NULL AFTER `productImageHeight`, ADD `smallThumbnailHeight` INT NOT NULL AFTER `smallThumbnailWidth`, ADD `mediumThumbnailWidth` INT NOT NULL AFTER `smallThumbnailHeight`, ADD `mediumThumbnailHeight` INT NOT NULL AFTER `mediumThumbnailWidth`, ADD `largeThumbnailWidth` INT NOT NULL AFTER `mediumThumbnailHeight`, ADD `largeThumbnailHeight` INT NOT NULL AFTER `largeThumbnailWidth`;

UPDATE `products` SET `summary`='summary...';
UPDATE `mediaSettings` SET `featuredProductsLimit`='10',`featuredImageWidth`='640',`featuredImageHeight`='347',`productImageWidth`='250',`productImageHeight`='250',`smallThumbnailWidth`='100',`smallThumbnailHeight`='100',`mediumThumbnailWidth`='800',`mediumThumbnailHeight`='800',`largeThumbnailWidth`='800',`largeThumbnailHeight`='800';