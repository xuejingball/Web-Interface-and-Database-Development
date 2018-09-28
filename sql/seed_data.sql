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
  `description` varchar(250) DEFAULT NULL,
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
(4, 'Home & Garden'),
(7, 'Other'),
(5, 'Sporting Goods'),
(6, 'Toys');


INSERT INTO `ConditionState` (`conditionID`, `condition_name`) VALUES
(4, 'Fair'),
(3, 'Good'),
(1, 'New'),
(5, 'Poor'),
(2, 'Very Good');


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

INSERT INTO `User` (username, `password`, last_name, first_name) VALUES('user1', 'pass1', 'Kelor', 'Danite');
INSERT INTO `User` (username, `password`, last_name, first_name) VALUES('user2', 'pass2', 'Kiney', 'Dodra');
INSERT INTO `User` (username, `password`, last_name, first_name) VALUES('user3', 'pass3', 'Bishop', 'Peran');
INSERT INTO `User` (username, `password`, last_name, first_name) VALUES('user4', 'pass4', 'Roran', 'Randy');
INSERT INTO `User` (username, `password`, last_name, first_name) VALUES('user5', 'pass5', 'Iankel', 'Ashod');
INSERT INTO `User` (username, `password`, last_name, first_name) VALUES('user6', 'pass6', 'Achant', 'Cany');
INSERT INTO `User` (username, `password`, last_name, first_name) VALUES('admin1', 'opensesame', 'Fuiss', 'Riley');
INSERT INTO `User` (username, `password`, last_name, first_name) VALUES('admin2', 'opensesayou', 'Kinser', 'Tonnis');
INSERT INTO `User` (username, `password`, last_name, first_name) VALUES('michael@bluthco.com', 'michael123', 'Michael', 'Bluth');

-- Insert into AdminUser
INSERT INTO AdminUser (username, position) VALUES('admin1', 'Technical Support' );
INSERT INTO AdminUser (username, position) VALUES('admin2', 'Chief Techy' );

-- Insert into Item
INSERT INTO Item (itemID, username, item_name, description, get_it_now_price, minimum_sale_price, returnable, starting_bid, auction_end_time, category_name, condition_name) VALUES ('1', 'user1', 'Garmin GPS', 'This is a great GPS.', '99', '70', '0', '50', '2018-03-31 12:22:00', 'Electronics', 'Very Good');

INSERT INTO Item (itemID, username, item_name, description, get_it_now_price, minimum_sale_price, returnable, starting_bid, auction_end_time, category_name, condition_name) VALUES ('2', 'user1', 'Canon Powershot', 'Point and shoot!', '80', '60', '0', '40', '2018-04-01 14:14:00', 'Electronics', 'Good');

INSERT INTO Item (itemID, username, item_name, description, get_it_now_price, minimum_sale_price, returnable, starting_bid, auction_end_time, category_name, condition_name) VALUES ('3', 'user2', 'Nikon D3', 'New and in box!', '2000', '1800', '0', '1500', '2018-04-05 09:19:00', 'Electronics', 'New');

INSERT INTO Item (itemID, username, item_name, description, get_it_now_price, minimum_sale_price, returnable, starting_bid, auction_end_time, category_name, condition_name) VALUES ('4', 'user3', 'Danish Art Book', 'Delicious Danish Art', '15', '10', '1', '10', '2018-04-05 15:33:00', 'Art', 'Very Good');

INSERT INTO Item (itemID, username, item_name, description, get_it_now_price, minimum_sale_price, returnable, starting_bid, auction_end_time, category_name, condition_name) VALUES ('5', 'admin1', 'SQL in 10 Minutes', 'Learn SQL really fast!', '12', '10', '0', '5', '2018-04-05 15:33:00', 'Books', 'Fair');

INSERT INTO Item (itemID, username, item_name, description, get_it_now_price, minimum_sale_price, returnable, starting_bid, auction_end_time, category_name, condition_name) VALUES ('6', 'admin2', 'SQL in 8 Minutes', 'Learn SQL even faster!', '10', '8', '0', '5', '2018-04-08 10:01:00', 'Books', 'Good');

INSERT INTO Item (itemID, username, item_name, description, get_it_now_price, minimum_sale_price, returnable, starting_bid, auction_end_time, category_name, condition_name) VALUES ('7', 'user6', 'Pull-up Bar', 'Works on any door frame.', '40', '25', '1', '20', '2018-04-09 22:09:00', 'Sporting Goods', 'New');

INSERT INTO Item (itemID, username, item_name, description, get_it_now_price, minimum_sale_price, returnable, starting_bid, auction_end_time, category_name, condition_name) VALUES ('8', 'admin2', 'Garmin GPS', 'A 2-year-old GPS, working perfectly.', '75', '50', '0', '25', '2018-04-23 03:15:00', 'Electronics', 'Good');

INSERT INTO Item (itemID, username, item_name, description, get_it_now_price, minimum_sale_price, returnable, starting_bid, auction_end_time, category_name, condition_name) VALUES ('9', 'user4', 'MacBook Pro', 'Nice MacBook in good condition!', NULL, '1500', '0', '1000', '2018-04-23 01:01:00', 'Electronics', 'Very Good');

INSERT INTO Item (itemID, username, item_name, description, get_it_now_price, minimum_sale_price, returnable, starting_bid, auction_end_time, category_name, condition_name) VALUES ('10', 'user5', 'Microsoft Surface', 'Quite good Microsoft Surface, maintained well.', '899', '750', '0', '500', '2018-04-23 06:00:00', 'Electronics', 'Good');

INSERT INTO Item (itemID, username, item_name, description, get_it_now_price, minimum_sale_price, returnable, starting_bid, auction_end_time, category_name, condition_name) VALUES ('11', 'user5', 'Piano', 'A fair used piano.', '2000', '750', '0', '500', '2018-04-23 06:00:00', 'Art', 'Fair');
-- Insert into Bid/GetItNow
INSERT INTO `Bid/GetItNow` (username, itemID, date_and_time, bid_amount, is_winner) VALUES ('user4', '1', '2018-03-30 14:53:00', '50', NULL);
INSERT INTO `Bid/GetItNow` (username, itemID, date_and_time, bid_amount, is_winner) VALUES ('user5', '1', '2018-03-30 16:45:00', '55', NULL);
INSERT INTO `Bid/GetItNow` (username, itemID, date_and_time, bid_amount, is_winner) VALUES ('user4', '1', '2018-03-30 19:28:00', '75', NULL);
INSERT INTO `Bid/GetItNow` (username, itemID, date_and_time, bid_amount, is_winner) VALUES ('user5', '1', '2018-03-31 10:00:00', '85', NULL);
INSERT INTO `Bid/GetItNow` (username, itemID, date_and_time, bid_amount, is_winner) VALUES ('user6', '2', '2018-04-01 13:55:00', '80', '2');
INSERT INTO `Bid/GetItNow` (username, itemID, date_and_time, bid_amount, is_winner) VALUES ('user1', '3', '2018-04-04 08:37:00', '1500', NULL);
INSERT INTO `Bid/GetItNow` (username, itemID, date_and_time, bid_amount, is_winner) VALUES ('user3', '3', '2018-04-04 09:15:00', '1501', NULL);
INSERT INTO `Bid/GetItNow` (username, itemID, date_and_time, bid_amount, is_winner) VALUES ('user1', '3', '2018-04-04 12:27:00', '1795', NULL);
INSERT INTO `Bid/GetItNow` (username, itemID, date_and_time, bid_amount, is_winner) VALUES ('user4', '7', '2018-04-08 20:20:00', '20', NULL);
INSERT INTO `Bid/GetItNow` (username, itemID, date_and_time, bid_amount, is_winner) VALUES ('user2', '7', '2018-04-09 21:15:00', '25', NULL);
INSERT INTO `Bid/GetItNow` (username, itemID, date_and_time, bid_amount, is_winner) VALUES ('user4', '8', '2018-04-16 04:15:00', '30', NULL);
INSERT INTO `Bid/GetItNow` (username, itemID, date_and_time, bid_amount, is_winner) VALUES ('user5', '8', '2018-04-16 04:30:00', '31', NULL);
INSERT INTO `Bid/GetItNow` (username, itemID, date_and_time, bid_amount, is_winner) VALUES ('user3', '8', '2018-04-18 06:30:00', '33', NULL);
INSERT INTO `Bid/GetItNow` (username, itemID, date_and_time, bid_amount, is_winner) VALUES ('user4', '8', '2018-04-19 06:35:00', '40', NULL);
INSERT INTO `Bid/GetItNow` (username, itemID, date_and_time, bid_amount, is_winner) VALUES ('user6', '8', '2018-04-20 11:35:00', '45', NULL);


-- Insert into Rate
INSERT INTO Rate (username, itemID, star, comments, rating_time) VALUES ('user2', '1', '5', 'Great GPS!', '2018-03-30 17:00:00');
INSERT INTO Rate (username, itemID, star, comments, rating_time) VALUES ('user3', '1', '2', 'Not so great GPS!', '2018-03-30 18:00:00');
INSERT INTO Rate (username, itemID, star, comments, rating_time) VALUES ('user4', '1', '4', 'A favorite of mine.', '2018-03-30 19:00:00');
INSERT INTO Rate (username, itemID, star, comments, rating_time) VALUES ('user1', '4', '1', 'Go for the Italian stuff instead.', '2018-04-01 16:46:00');
INSERT INTO Rate (username, itemID, star, comments, rating_time) VALUES ('admin1', '6', '1', 'Not recommended', '2018-04-06 23:56:00');
INSERT INTO Rate (username, itemID, star, comments, rating_time) VALUES ('user1', '6', '3', 'This book is okay', '2018-04-07 13:32:00');
INSERT INTO Rate (username, itemID, star, comments, rating_time) VALUES ('user2', '6', '5', 'I learned SQL in 8 minutes!', '2018-04-07 14:44:00');
INSERT INTO Rate (username, itemID, star, comments, rating_time) VALUES ('user5', '9', '5', 'Great for getting OMSCS coursework done', '2018-04-018 14:55:00');
INSERT INTO Rate (username, itemID, star, comments, rating_time) VALUES ('user2', '10', '2', 'Looks nice but underpowered', '2018-04-17 19:44:00');
INSERT INTO Rate (username, itemID, star, comments, rating_time) VALUES ('user3', '10', '3', NULL, '2018-04-20 19:17:00');