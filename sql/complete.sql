-- CREATE USER 'newuser'@'localhost' IDENTIFIED BY 'password';
CREATE USER IF NOT EXISTS gatechUser@localhost IDENTIFIED BY 'gatech123';

DROP DATABASE IF EXISTS `cs6400_spr18_team089`; 
SET default_storage_engine=InnoDB;

CREATE DATABASE IF NOT EXISTS cs6400_spr18_team089 
    DEFAULT CHARACTER SET utf8mb4 
    DEFAULT COLLATE utf8mb4_unicode_ci;
USE cs6400_spr18_team089;

GRANT SELECT, INSERT, UPDATE, DELETE, FILE ON *.* TO 'gatechUser'@'localhost';
GRANT ALL PRIVILEGES ON `gatechuser`.* TO 'gatechUser'@'localhost';
GRANT ALL PRIVILEGES ON `cs6400_spr18_team089`.* TO 'gatechUser'@'localhost';
FLUSH PRIVILEGES;

-- Tables

CREATE TABLE `User` (
  `userID` int(16) UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `password` varchar(60) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `first_name` varchar(100) NOT NULL,
   PRIMARY KEY (userID),
   UNIQUE KEY username (username)
);

CREATE TABLE `AdminUser` (
  `adminID` int(16) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(250) NOT NULL,
  `position` varchar(100) NOT NULL,
   PRIMARY KEY (adminID),
   UNIQUE KEY username (username)
);

CREATE TABLE `Bid/GetItNow` (
  `username` varchar(100) NOT NULL,
  `itemID` int(11) UNSIGNED NOT NULL,
  `date_and_time` datetime NOT NULL,
  `bid_amount` decimal(13,2) NOT NULL,
  `is_winner` tinyint(1) DEFAULT NULL,
   PRIMARY KEY (username,itemID,date_and_time),
   KEY itemID (itemID),
   KEY username (username)
);

CREATE TABLE `Category` (
  `categoryID` int(16) UNSIGNED NOT NULL AUTO_INCREMENT,
  `category_name` varchar(250) NOT NULL,
   PRIMARY KEY (categoryID),
   UNIQUE KEY category_name (category_name)
);

CREATE TABLE `ConditionState` (
  `conditionID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `condition_name` varchar(250) NOT NULL,
   PRIMARY KEY (conditionID),
   UNIQUE KEY condition_name (condition_name)
);

CREATE TABLE `Item` (
  `itemID` int(16) UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` varchar(250) NOT NULL,
  `item_name` varchar(250) NOT NULL,
  `description` varchar(250) NOT NULL,
  `get_it_now_price` decimal(13,2) DEFAULT NULL,
  `minimum_sale_price` decimal(13,2) NOT NULL,
  `returnable` tinyint(1) NOT NULL,
  `starting_bid` decimal(13,2) NOT NULL,
  `auction_end_time` datetime NOT NULL,
  `category_name` varchar(250) NOT NULL,
  `condition_name` varchar(250) NOT NULL,
   PRIMARY KEY (itemID),
   KEY username (username),
   KEY condition_name (condition_name),
   KEY category_name (category_name)
); 

CREATE TABLE `Rate` (
  `username` varchar(100) NOT NULL,
  `itemID` int(16) UNSIGNED NOT NULL,
  `star` int(16) NOT NULL,
  `comments` varchar(250) DEFAULT NULL,
  `rating_time` datetime NOT NULL,
   PRIMARY KEY (username,itemID),
   KEY username (username),
   KEY itemID (itemID) 
);

-- Add constant data for tables

INSERT INTO `Category` (`categoryID`, `category_name`) VALUES
(1, 'Art'),
(2, 'Books'),
(3, 'Electronics'),
(4, 'Home&Garden'),
(5, 'Sporting Goods'),
(6, 'Toys'),
(7, 'Other');



INSERT INTO `ConditionState` (`conditionID`, `condition_name`) VALUES
(1, 'New'),
(2, 'Very Good'),
(3, 'Good'),
(4, 'Fair'),
(5, 'Poor');


-- Constraints  Foreign Keys:

ALTER TABLE `AdminUser`
  ADD CONSTRAINT `fk_AdminUser_username_User_username` FOREIGN KEY (`username`) REFERENCES `User` (`username`) ON UPDATE CASCADE;

ALTER TABLE `Bid/GetItNow`
  ADD CONSTRAINT `fk_Bid/GetItNow_itemID_Item_itemID` FOREIGN KEY (`itemID`) REFERENCES `Item` (`itemID`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_Bid/GetItNow_username_User_username` FOREIGN KEY (`username`) REFERENCES `User` (`username`) ON UPDATE CASCADE;

ALTER TABLE `Item`
  ADD CONSTRAINT `fk_Item_category_name_Category_category_name` FOREIGN KEY (`category_name`) REFERENCES `Category` (`category_name`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_Item_condition_name_Condition_condition_name` FOREIGN KEY (`condition_name`) REFERENCES `ConditionState` (`condition_name`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_Item_username_User_username` FOREIGN KEY (`username`) REFERENCES `User` (`username`) ON UPDATE CASCADE;

ALTER TABLE `Rate`
  ADD CONSTRAINT `fk_Rate_itemID_Item_itemID` FOREIGN KEY (`itemID`) REFERENCES `Item` (`itemID`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_Rate_username_User_username` FOREIGN KEY (`username`) REFERENCES `User` (`username`) ON DELETE CASCADE ON UPDATE CASCADE;


-- Insert Test (seed) Data 

-- Insert into User
-- example of using a 60 char length hashed password 'michael123' = $2y$08$kr5P80A7RyA0FDPUa8cB2eaf0EqbUay0nYspuajgHRRXM9SgzNgZO
-- depends on if you are storing the hash $storedHash or plaintext $storedPassword in login.php
INSERT INTO `User` (username, `password`, last_name, first_name) VALUES('admin@gtonline.com', 'admin123', 'Johnny', 'Admin');
INSERT INTO `User` (username, `password`, last_name, first_name) VALUES('admin1@gtonline.com', 'support123', 'Technical', 'Support');
INSERT INTO `User` (username, `password`, last_name, first_name) VALUES('admin2@gtonline.com', 'Tech123', 'Chief', 'Tech');
INSERT INTO `User` (username, `password`, last_name, first_name) VALUES('dschrute@dundermifflin.com', 'dwight123', 'Dwight', 'Schrute');
INSERT INTO `User` (username, `password`, last_name, first_name) VALUES('gbluth@bluthco.com', 'george123', 'George', 'Bluth');
INSERT INTO `User` (username, `password`, last_name, first_name) VALUES('jhalpert@dundermifflin.com', 'jim123', 'Jim', 'Halpert');
INSERT INTO `User` (username, `password`, last_name, first_name) VALUES('lfunke@bluthco.com', 'lindsey123', 'Lindsey', 'Funke');
INSERT INTO `User` (username, `password`, last_name, first_name) VALUES('michael@bluthco.com', 'michael123', 'Michael', 'Bluth');
INSERT INTO `User` (username, `password`, last_name, first_name) VALUES('pam@dundermifflin.com', 'pam123', 'Pam', 'Halpert');
INSERT INTO `User` (username, `password`, last_name, first_name) VALUES('tsmith@gatech.edu', 'tsmith123', 'Tom', 'Smith');
INSERT INTO `User` (username, `password`, last_name, first_name) VALUES('jdoe@gatech.edu', 'jdoe123', 'Jane', 'Doe');
INSERT INTO `User` (username, `password`, last_name, first_name) VALUES('rocky@cc.gatech.edu', 'rocky123', 'Rocky', 'Dunlap');
INSERT INTO `User` (username, `password`, last_name, first_name) VALUES('dkelor@cc.gatech.edu', 'kelor123', 'Kelor', 'Danite');
INSERT INTO `User` (username, `password`, last_name, first_name) VALUES('dkiney@cc.gatech.edu', 'kiney123', 'Kiney', 'Dodra');
INSERT INTO `User` (username, `password`, last_name, first_name) VALUES('pbishop@cc.gatech.edu', 'bishop123', 'Bishop', 'Peran');
INSERT INTO `User` (username, `password`, last_name, first_name) VALUES('rroran@cc.gatech.edu', 'roran123', 'Roran', 'Randy');
INSERT INTO `User` (username, `password`, last_name, first_name) VALUES('aiankel@cc.gatech.edu', 'iankel123', 'Iankel', 'Ashod');
INSERT INTO `User` (username, `password`, last_name, first_name) VALUES('cachant@cc.gatech.edu', 'achant123', 'Achant', 'Cany');
INSERT INTO `User` (username, `password`, last_name, first_name) VALUES('rfuiss@cc.gatech.edu', 'fuiss123', 'Fuiss', 'Riley');
INSERT INTO `User` (username, `password`, last_name, first_name) VALUES('tkinser@cc.gatech.edu', 'kinser123', 'Kinser', 'Tonnis');


-- Insert into AdminUser
INSERT INTO `AdminUser` (username, position) VALUES('admin@gtonline.com', 'admin' );
INSERT INTO `AdminUser` (username, position) VALUES('admin1@gtonline.com', 'TechnicalSupport' );
INSERT INTO `AdminUser` (username, position) VALUES('admin2@gtonline.com', 'ChiefTech' );


-- Insert into Item

INSERT INTO `Item` (username, item_name, description, get_it_now_price, minimum_sale_price, returnable, starting_bid, auction_end_time, category_name, condition_name) 
VALUES('dschrute@dundermifflin.com', 'laptop','laptop is used, but good', 500, 100, 1, 60, "2018-11-11 13:23:44",'Electronics', 'Good');
INSERT INTO `Item` (username, item_name, description, get_it_now_price, minimum_sale_price, returnable, starting_bid, auction_end_time, category_name, condition_name) 
VALUES('gbluth@bluthco.com', 'microphone','microphone is used, in a fair condition', 50, 10, 0, 6, "2018-03-10 13:23:44",'Electronics', 'Fair');
INSERT INTO `Item` (username, item_name, description, get_it_now_price, minimum_sale_price, returnable, starting_bid, auction_end_time, category_name, condition_name) 
VALUES('jhalpert@dundermifflin.com', 'Chemistry','This chemistry book is new', 100, 80, 0, 15, "2018-03-09 13:23:44",'Books', 'New');
INSERT INTO `Item` (username, item_name, description, get_it_now_price, minimum_sale_price, returnable, starting_bid, auction_end_time, category_name, condition_name) 
VALUES('lfunke@bluthco.com', 'flower port','flower pot is not in a good condition, sold in cheap price', 30, 15, 0, 8, "2018-03-08 13:23:44",'Home&Garden', 'poor');
INSERT INTO `Item` (username, item_name, description, get_it_now_price, minimum_sale_price, returnable, starting_bid, auction_end_time, category_name, condition_name) 
VALUES('michael@bluthco.com', 'skate board','skate is very good', 60, 30, 0, 15, "2018-03-08 13:23:44",'Sporting Goods', 'very good');
INSERT INTO `Item` (username, item_name, description, get_it_now_price, minimum_sale_price, returnable, starting_bid, auction_end_time, category_name, condition_name) 
VALUES('pam@dundermifflin.com', 'toy','toy is used but very good', 10, 5, 1, 3, "2018-11-08 13:23:44",'Toys', 'very good');
INSERT INTO `Item` (username, item_name, description, get_it_now_price, minimum_sale_price, returnable, starting_bid, auction_end_time, category_name, condition_name) 
VALUES('pam@dundermifflin.com', 'key','key is good', 5, 2, 1, 1, "2018-11-08 13:23:44",'Other', 'Good');
INSERT INTO `Item` (username, item_name, description, get_it_now_price, minimum_sale_price, returnable, starting_bid, auction_end_time, category_name, condition_name) 
VALUES ('dkelor@cc.gatech.edu', 'Danish Art Book', 'Delicious Danish Art in a fair condition', 15, 10, 1, 10, '2018-04-05 00:00:00', 'Art', 'Fair');
INSERT INTO `Item` (username, item_name, description, get_it_now_price, minimum_sale_price, returnable, starting_bid, auction_end_time, category_name, condition_name)
VALUES ('dschrute@dundermifflin.com', 'Piano', 'This is a new Piano', 1000, 500, 1, 500, '2018-04-01 00:00:00', 'Art', 'New');
INSERT INTO `item` (username, item_name, description, get_it_now_price, minimum_sale_price, returnable, starting_bid, auction_end_time, category_name, condition_name) 
VALUES ('pam@dundermifflin.com', 'Bike', 'This a new Bike', '100', '50', '1', '50', '2018-04-30 00:00:00', 'Sporting Goods', 'New');



-- Insert into bid or get it now table
INSERT INTO `Bid/GetItNow` (username, itemID, date_and_time, bid_amount) 
VALUES('dschrute@dundermifflin.com', 2, '2018-03-01 13:23:44',6);
INSERT INTO `Bid/GetItNow` (username, itemID, date_and_time, bid_amount) 
VALUES('dschrute@dundermifflin.com', 2, '2018-03-02 13:23:44',7);
INSERT INTO `Bid/GetItNow` (username, itemID, date_and_time, bid_amount) 
VALUES('michael@bluthco.com', 2, '2018-03-03 13:23:44',10);
INSERT INTO `Bid/GetItNow` (username, itemID, date_and_time, bid_amount) 
VALUES('gbluth@bluthco.com', 3, '2018-03-02 13:23:44',85);
INSERT INTO `Bid/GetItNow` (username, itemID, date_and_time, bid_amount) 
VALUES('tsmith@gatech.edu', 3, '2018-03-02 15:23:44',86);
INSERT INTO `Bid/GetItNow` (username, itemID, date_and_time, bid_amount) 
VALUES('gbluth@bluthco.com', 3, '2018-03-02 17:23:44',88);
INSERT INTO `Bid/GetItNow` (username, itemID, date_and_time, bid_amount,is_winner) 
VALUES('rocky@cc.gatech.edu', 3, '2018-03-03 17:23:44',100, 2);
INSERT INTO `Bid/GetItNow` (username, itemID, date_and_time, bid_amount) 
VALUES('rocky@cc.gatech.edu', 4, '2018-03-03 17:23:44',8);
INSERT INTO `Bid/Getitnow` (username, itemID, date_and_time, bid_amount, is_winner) 
VALUES ('pam@dundermifflin.com', 1, '2018-04-01 00:00:00', 70, NULL);
INSERT INTO `Bid/Getitnow` (username, itemID, date_and_time, bid_amount, is_winner) 
VALUES ('rroran@cc.gatech.edu', 3, '2018-02-04 00:00:00', 50, NULL);
INSERT INTO `Bid/Getitnow` (username, itemID, date_and_time, bid_amount, is_winner) 
VALUES ('tkinser@cc.gatech.edu', 4, '2018-01-16 00:00:00', 20, NULL);
INSERT INTO `Bid/Getitnow` (username, itemID, date_and_time, bid_amount, is_winner)  
VALUES ('pbishop@cc.gatech.edu', 5, '2018-01-30 00:00:00', 20, NULL);
INSERT INTO `Bid/Getitnow` (username, itemID, date_and_time, bid_amount, is_winner) 
VALUES ('lfunke@bluthco.com', 6, '2018-04-02 00:00:00', 8, NULL);


-- Insert into rate

INSERT INTO `rate` (username, itemID, star, comments, rating_time) 
VALUES ('dschrute@dundermifflin.com', '1', '3', 'This is good', '2018-11-19 00:00:00');
INSERT INTO `rate` (username, itemID, star, comments, rating_time) 
VALUES ('jdoe@gatech.edu', '2', '2', 'This is Bad', '2018-04-09 00:00:00');
INSERT INTO `rate` (username, itemID, star, comments, rating_time) 
VALUES ('cachant@cc.gatech.edu', '1', '4', 'This is very great!', '2018-12-04 00:00:00');
INSERT INTO `rate` (username, itemID, star, comments, rating_time)
VALUES('dschrute@dundermifflin.com', '3', '1', 'No one need it.', '2018-04-09 00:00:00');
INSERT INTO `rate` (username, itemID, star, comments, rating_time)
VALUES ('pbishop@cc.gatech.edu', '5', '5', 'It is perfect', '2018-04-01 00:00:00'); 
INSERT INTO `rate` (username, itemID, star, comments, rating_time)
VALUES ('pam@dundermifflin.com', '6', '1', 'Bad toy.', '2018-03-26 00:00:00');
INSERT INTO `rate` (username, itemID, star, comments, rating_time)
VALUES('cachant@cc.gatech.edu', '10', '3', 'It is just ok', '2018-05-01 00:00:00');
