<?php
$mysqli = new mysqli("localhost", "root", "root3", "news");
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Drop existing tables, weak entities first
$dropTables = [
    "DROP TABLE IF EXISTS EventTag",
    "DROP TABLE IF EXISTS Comment",
    "DROP TABLE IF EXISTS Event",
    "DROP TABLE IF EXISTS AdvertisementStory",
    "DROP TABLE IF EXISTS StoryAuthor",
    "DROP TABLE IF EXISTS StoryTag",
    "DROP TABLE IF EXISTS Story",
    "DROP TABLE IF EXISTS User",
    "DROP TABLE IF EXISTS Author",
    "DROP TABLE IF EXISTS Location",
    "DROP TABLE IF EXISTS Tag",
    "DROP TABLE IF EXISTS Advertisement"
];

//Recreate the tables
$createTables = [

    "CREATE TABLE IF NOT EXISTS `news`.`Story` (
      `StoryID` INT NOT NULL AUTO_INCREMENT,
      `Title` VARCHAR(50) NOT NULL,
      `Body` VARCHAR(12000) NOT NULL,
      `PublishedTimestamp` DATETIME NOT NULL,
      `ComicURL` VARCHAR(150) NULL,
      PRIMARY KEY (`StoryID`),
      UNIQUE INDEX `Title_UNIQUE` (`Title` ASC) VISIBLE)
    ENGINE = InnoDB;",

    "CREATE TABLE IF NOT EXISTS `news`.`User` (
      `UserID` INT NOT NULL AUTO_INCREMENT,
      `SubscriptionStatus` ENUM('active', 'inactive') NOT NULL,
      `DisplayName` VARCHAR(30) NOT NULL,
      `Email` VARCHAR(40) NOT NULL,
      `Password` VARCHAR(20) NOT NULL,
      `DateJoined` DATETIME NOT NULL,
      `Role` ENUM('editor', 'reader', 'admin') NOT NULL,
      PRIMARY KEY (`UserID`),
      UNIQUE INDEX `DisplayName_UNIQUE` (`DisplayName` ASC) VISIBLE)
    ENGINE = InnoDB;",

    "CREATE TABLE IF NOT EXISTS `news`.`Author` (
      `AuthorID` INT NOT NULL AUTO_INCREMENT,
      `PenName` VARCHAR(30) NOT NULL,
      `Bio` VARCHAR(100) NULL,
      `Degree` VARCHAR(30) NULL,
      `Birthday` DATE NULL,
      PRIMARY KEY (`AuthorID`),
      UNIQUE INDEX `PenName_UNIQUE` (`PenName` ASC) VISIBLE)
    ENGINE = InnoDB;",

    "CREATE TABLE IF NOT EXISTS `news`.`Location` (
      `LocationID` INT NOT NULL AUTO_INCREMENT,
      `LocationName` VARCHAR(30) NULL,
      `LocationAddress` VARCHAR(50) NOT NULL,
      `DateAdded` DATE NOT NULL,
      PRIMARY KEY (`LocationID`),
      UNIQUE INDEX `LocationName_UNIQUE` (`LocationName` ASC) VISIBLE)
    ENGINE = InnoDB;",

    "CREATE TABLE IF NOT EXISTS `news`.`Tag` (
      `TagID` INT NOT NULL AUTO_INCREMENT,
      `DisplayName` VARCHAR(20) NOT NULL,
      `DateAdded` DATE NOT NULL,
      PRIMARY KEY (`TagID`),
      UNIQUE INDEX `DisplayName_UNIQUE` (`DisplayName` ASC) VISIBLE)
    ENGINE = InnoDB;",

    "CREATE TABLE IF NOT EXISTS `news`.`Advertisement` (
      `AdID` INT NOT NULL AUTO_INCREMENT,
      `StartDate` DATETIME NOT NULL,
      `EndDate` DATETIME NOT NULL,
      `AdType` ENUM('banner', 'video', 'image') NOT NULL,
      `ContentURL` VARCHAR(150) NOT NULL,
      `AdName` VARCHAR(30) NOT NULL,
      PRIMARY KEY (`AdID`),
      UNIQUE INDEX `AdName_UNIQUE` (`AdName` ASC) VISIBLE)
    ENGINE = InnoDB;",

    "CREATE TABLE IF NOT EXISTS `news`.`Comment` (
      `UserID` INT NOT NULL,
      `StoryID` INT NOT NULL,
      `CommentText` VARCHAR(100) NOT NULL,
      `Timestamp` DATETIME NOT NULL,
      PRIMARY KEY (`StoryID`, `UserID`, `Timestamp`),
      INDEX `UserID_idx` (`UserID` ASC) VISIBLE,
      INDEX `StoryID_idx` (`StoryID` ASC) VISIBLE,
      CONSTRAINT `UserID`
        FOREIGN KEY (`UserID`)
        REFERENCES `news`.`User` (`UserID`)
        ON DELETE NO ACTION
        ON UPDATE NO ACTION,
      CONSTRAINT `StoryID`
        FOREIGN KEY (`StoryID`)
        REFERENCES `news`.`Story` (`StoryID`)
        ON DELETE NO ACTION
        ON UPDATE NO ACTION)
    ENGINE = InnoDB;",

    "CREATE TABLE IF NOT EXISTS `news`.`Event` (
      `EventID` INT NOT NULL AUTO_INCREMENT,
      `Sponsor` VARCHAR(30) NOT NULL,
      `LocationID` INT NOT NULL,
      `EventStart` DATETIME NOT NULL,
      `EventEnd` DATETIME NOT NULL,
      `Description` VARCHAR(600) NOT NULL,
      `EventName` VARCHAR(30) NOT NULL,
      PRIMARY KEY (`EventID`),
      UNIQUE INDEX `EventName_UNIQUE` (`EventName` ASC) VISIBLE,
      INDEX `LocationID_idx` (`LocationID` ASC) VISIBLE,
      CONSTRAINT `LocationID`
        FOREIGN KEY (`LocationID`)
        REFERENCES `news`.`Location` (`LocationID`)
        ON DELETE NO ACTION
        ON UPDATE NO ACTION
    )
    ENGINE = InnoDB;",

    "CREATE TABLE IF NOT EXISTS `news`.`AdvertisementStory` (
      `StoryID` INT NOT NULL,
      `AdvertisementID` INT NOT NULL,
      PRIMARY KEY (`StoryID`, `AdvertisementID`),
      INDEX `AdvertisementID_idx` (`AdvertisementID` ASC) VISIBLE,
      INDEX `StoryID_idx` (`StoryID` ASC) VISIBLE,
      CONSTRAINT `AdvertisementID`
        FOREIGN KEY (`AdvertisementID`)
        REFERENCES `news`.`Advertisement` (`AdID`)
        ON DELETE NO ACTION
        ON UPDATE NO ACTION,
      CONSTRAINT `StoryID2`
        FOREIGN KEY (`StoryID`)
        REFERENCES `news`.`Story` (`StoryID`)
        ON DELETE NO ACTION
        ON UPDATE NO ACTION)
    ENGINE = InnoDB;",

    "CREATE TABLE IF NOT EXISTS `news`.`StoryAuthor` (
      `StoryID` INT NOT NULL,
      `AuthorID` INT NOT NULL,
      PRIMARY KEY (`StoryID`, `AuthorID`),
      INDEX `Story_idx` (`StoryID` ASC, `AuthorID` ASC) VISIBLE,
      INDEX `AuthorID_idx` (`AuthorID` ASC) VISIBLE,
      CONSTRAINT `StoryID3`
        FOREIGN KEY (`StoryID`)
        REFERENCES `news`.`Story` (`StoryID`)
        ON DELETE NO ACTION
        ON UPDATE NO ACTION,
      CONSTRAINT `AuthorID`
        FOREIGN KEY (`AuthorID`)
        REFERENCES `news`.`Author` (`AuthorID`)
        ON DELETE NO ACTION
        ON UPDATE NO ACTION)
    ENGINE = InnoDB;",

    "CREATE TABLE IF NOT EXISTS `news`.`StoryTag` (
      `StoryID` INT NOT NULL,
      `TagID` INT NOT NULL,
      PRIMARY KEY (`StoryID`, `TagID`),
      INDEX `TagID_idx` (`TagID` ASC, `StoryID` ASC) VISIBLE,
      CONSTRAINT `StoryID4`
        FOREIGN KEY (`StoryID`)
        REFERENCES `news`.`Story` (`StoryID`)
        ON DELETE NO ACTION
        ON UPDATE NO ACTION,
      CONSTRAINT `TagID2`
        FOREIGN KEY (`TagID`)
        REFERENCES `news`.`Tag` (`TagID`)
        ON DELETE NO ACTION
        ON UPDATE NO ACTION)
    ENGINE = InnoDB;",

        "CREATE TABLE IF NOT EXISTS `news`.`EventTag` (
      `EventID` INT NOT NULL,
      `TagID` INT NOT NULL,
      PRIMARY KEY (`EventID`, `TagID`),
      INDEX `TagID_idx` (`TagID` ASC, `EventID` ASC) VISIBLE,
      CONSTRAINT `EventID`
        FOREIGN KEY (`EventID`)
        REFERENCES `news`.`Event` (`EventID`)
        ON DELETE NO ACTION
        ON UPDATE NO ACTION,
      CONSTRAINT `TagID3`
        FOREIGN KEY (`TagID`)
        REFERENCES `news`.`Tag` (`TagID`)
        ON DELETE NO ACTION
        ON UPDATE NO ACTION)
    ENGINE = InnoDB;"
];

try {
    foreach ($dropTables as $sql) {
        $mysqli->query($sql);
    }

    foreach ($createTables as $sql) {
        $mysqli->query($sql);
    }

    echo "Database reset successfully.";

} catch (Exception $e) {
    echo "Error resetting database: " . $e->getMessage();
}

$mysqli->close();
?>