CREATE TABLE `finchmeister`.`members` (
    `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `email` VARCHAR(50) NOT NULL,
    `password` CHAR(60) NOT NULL,
    `created_at` TIMESTAMP
) ENGINE = InnoDB;

CREATE TABLE `finchmeister`.`login_attempts` (
    `user_id` INT(11) NOT NULL,
    `time` DATETIME NOT NULL,
    `success` TINYINT(1) NOT NULL,
    `user_agent` VARCHAR(100) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE `finchmeister`.`smokefree` (
    `user_id` INT(11) NOT NULL,
    `time` DATETIME NOT NULL,
    `created_at` TIMESTAMP
) ENGINE=InnoDB;


CREATE TABLE `finchmeister`.`poker_rankings` (
    `date` DATE NOT NULL,
    `position` TINYINT(1) NOT NULL,
    `name` VARCHAR(50) NOT NULL,
    `winnings` INTEGER,
    `buyIn` INTEGER,
    `rebuys` TINYINT(1)
) ENGINE=InnoDB;