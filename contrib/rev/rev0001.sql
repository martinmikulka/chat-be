CREATE DATABASE `chatbe`CHARACTER SET utf8 COLLATE utf8_czech_ci;
CREATE TABLE `chatbe`.`user`( `id` INT UNSIGNED NOT NULL AUTO_INCREMENT, `username` VARCHAR(64), `password` VARCHAR(128), PRIMARY KEY (`id`) ) ENGINE=INNODB;
ALTER TABLE `chatbe`.`user` ADD UNIQUE INDEX (`username`);
