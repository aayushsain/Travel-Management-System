-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: travel
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `category`
--

DROP TABLE IF EXISTS `category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `category` (
  `Cat_id` int(100) NOT NULL AUTO_INCREMENT,
  `Cat_name` varchar(2000) NOT NULL,
  PRIMARY KEY (`Cat_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `category`
--

LOCK TABLES `category` WRITE;
/*!40000 ALTER TABLE `category` DISABLE KEYS */;
INSERT INTO `category` VALUES (1,'Family Tours'),(2,'Religious Tours');
/*!40000 ALTER TABLE `category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contactus`
--

DROP TABLE IF EXISTS `contactus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contactus` (
  `contactid` int(50) NOT NULL AUTO_INCREMENT,
  `Name` varchar(50) NOT NULL,
  `Phno` varchar(50) NOT NULL,
  `Email` varchar(50) NOT NULL,
  `Message` varchar(5000) NOT NULL,
  PRIMARY KEY (`contactid`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contactus`
--

LOCK TABLES `contactus` WRITE;
/*!40000 ALTER TABLE `contactus` DISABLE KEYS */;
INSERT INTO `contactus` VALUES (1,'Mehar','9501065206','mehar@gmail.com','We have read about the interest your advertisement in the times of India about the vacation trip. We will appreciate, if you kindly send the detailed information about the cost of the trip,the luggage wight, economy class and first class and etc. . The above information is required for our managing director who will like to enjoy the holiday trip along with his family.'),(2,'Japleen','9915079133','japu@gmail.com','We have read about the interest your advertisement in the times of India about the vacation trip. We will appreciate, if you kindly send the detailed information about the cost of the trip,the luggage wight, economy class and first class and etc. . The above information is required for our managing director who will like to enjoy the holiday trip along with his family.'),(3,'Veena','9815724956','veena12@gmail.com','We have read about the interest your advertisement in the times of India about the vacation trip. We will appreciate, if you kindly send the detailed information about the cost of the trip,the luggage wight, economy class and first class and etc. . The above information is required for our managing director who will like to enjoy the holiday trip along with his family.'),(4,'Sahil','9814532456','Sahil@yahoo.com','We have read about the interest your advertisement in the times of India about the vacation trip. We will appreciate, if you kindly send the detailed information about the cost of the trip,the luggage wight, economy class and first class and etc. . The above information is required for our managing director who will like to enjoy the holiday trip along with his family.'),(5,'Varinder','9812345234','vinnysharma@gmail.com','We have read about the interest your advertisement in the times of India about the vacation trip. We will appreciate, if you kindly send the detailed information about the cost of the trip,the luggage wight, economy class and first class and etc. . The above information is required for our managing director who will like to enjoy the holiday trip along with his family.');
/*!40000 ALTER TABLE `contactus` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `enquiry`
--

DROP TABLE IF EXISTS `enquiry`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `enquiry` (
  `Enquiryid` int(50) NOT NULL AUTO_INCREMENT,
  `Packageid` int(50) NOT NULL,
  `Name` varchar(200) NOT NULL,
  `Gender` varchar(20) NOT NULL,
  `Mobileno` varchar(20) NOT NULL,
  `Email` varchar(50) NOT NULL,
  `NoofDays` int(50) NOT NULL,
  `Child` int(50) NOT NULL,
  `Adults` int(50) NOT NULL,
  `Message` varchar(900) NOT NULL,
  `Statusfield` varchar(200) NOT NULL,
  PRIMARY KEY (`Enquiryid`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `enquiry`
--

LOCK TABLES `enquiry` WRITE;
/*!40000 ALTER TABLE `enquiry` DISABLE KEYS */;
INSERT INTO `enquiry` VALUES (5,2,'Nandni','Female','7696303090','nandni@gmail.com',2,2,3,'Brief us about the tour.','Pending'),(8,3,'Rohan','Male','9501065206','rohan@gmail.com',3,1,5,'We have read about the interest your advertisement in the times of India about the vacation trip. We will appreciate, if you kindly send the detailed information about the cost of the trip,the luggage wight, economy class and first class and etc. . The above information is required for our managing director who will like to enjoy the holiday trip along with his family.','Pending'),(13,2,'james','Male','8234567200','james@gmail.com',3,2,2,'provide the required information.','Pending'),(14,43,'Mehul Singh','Male','620378569','mehul.singh@gmail.com',2,0,1,'','Pending'),(15,38,'Aman Raj','Male','6204255822','srijusg2@gmail.com',1,0,1,'','Pending'),(16,38,'Aman Raj','Male','6204255822','srijusg2@gmail.com',5,0,1,'This is a long message of 10+ characters.','Pending');
/*!40000 ALTER TABLE `enquiry` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `package`
--

DROP TABLE IF EXISTS `package`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `package` (
  `Packid` int(200) NOT NULL AUTO_INCREMENT,
  `Packname` varchar(1000) NOT NULL,
  `Category` int(200) NOT NULL,
  `Subcategory` int(200) NOT NULL,
  `Packprice` int(200) NOT NULL,
  `Pic1` varchar(8000) NOT NULL,
  `Pic2` varchar(8000) NOT NULL,
  `Pic3` varchar(8000) NOT NULL,
  `Detail` varchar(8000) NOT NULL,
  PRIMARY KEY (`Packid`)
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `package`
--

LOCK TABLES `package` WRITE;
/*!40000 ALTER TABLE `package` DISABLE KEYS */;
INSERT INTO `package` VALUES (1,'Agra Family Tour ',1,1,10000,'india_family.jpg','classic.jpg','india_family.jpg','A fun-filled holiday with your family is the best time to strengthen bonds and rejuvenate the love between dear ones. And, what could be a better place to have a good time with your family members than Agra? The pleasant weather, beautiful sceneries and delightful food is sure to put you in your best mood to revel the best time with your family. For a stress-free vacation, you can explore your options for Agra family holidays packages with us on Yatra.com. From hotel bookings to travel tickets, we offer various services to help you enjoy your Agra family tour packages. Visit the numerous attractions of this beautiful place in the company of your loved ones with our Agra family vacation and tour packages away from the routine tasks of life. '),(2,'Holidays in Italy  ',1,3,20000,'italy_family.jpg','vatican_rome.jpg','beach3.jpg','Enjoy golden beaches, sparkling seas, beautiful countryside, exciting cities and great cuisine on family holidays in Italy. Whether you are looking for an activity based holiday or one full of cultural pursuits, let our experience and insider knowledge help you tailor the ideal Italy family holiday.'),(3,'Holidays in Vancouver ',1,2,400000,'canada_family.jpg','sl.jpg','himalaya1.jpg',' The lakes and mountains of Jasper, Banff and Yoho National Parks, provide the perfect backdrop for hiking and biking, while walking along the massive Athabasca Glacier feels like you are on top of the world. Horse riding, white water rafting, canoeing and whale watching are readily available for the adventurous traveller on our holidays to Canada. '),(4,'Haridwar',2,4,3000,'religious_india.jpg','varanasi_ganga.jpg','char_dham.jpg','Haridwar means the \'Gateway to the abode of the gods\'. Legend has it, that Prince Bhagirath, through his penance, caused the river Ganges to come down to plains from the Himalayas so that his ancestors who had perished due to a curse of a sage could be revived. '),(28,'Tokyo & Kyoto Family Adventure',1,27,85000,'japan_tokyo.jpg','japan_family.jpg','japan_tokyo.jpg','Explore the neon-lit streets of Tokyo, ancient Kyoto temples, and the iconic Mount Fuji with your family. Includes bullet train pass, robot restaurant dinner, and guided tours of Disneyland Tokyo. A journey your family will cherish forever.'),(29,'Japan Cherry Blossom Family Tour',1,27,92000,'japan_family.jpg','japan_tokyo.jpg','japan_family.jpg','Time your visit to witness Japan\'s spectacular cherry blossom season. Hanami picnics in Ueno Park, visits to Arashiyama bamboo groves, family tea ceremony workshops, and a traditional ryokan stay with hot spring baths.'),(30,'Bali Tropical Family Holiday',1,28,55000,'bali_beach1.jpg','bali_temple.jpg','bali_rice.jpg','Relax on pristine beaches, explore terraced rice paddies, and visit sacred Tanah Lot temple at sunset. This Bali family package includes private villa stay, Balinese cooking classes, and guided jungle trekking to hidden waterfalls.'),(31,'Bali Adventure & Culture Family Tour',1,28,62000,'bali_rice.jpg','bali_beach1.jpg','bali_temple.jpg','Discover Balinese culture through traditional Kecak dance performances, silver jewellery making workshops, and elephant sanctuary visits. Includes thrilling white water rafting on the Ayung River and a Ubud market visit.'),(32,'Swiss Alps Family Ski Holiday',1,29,120000,'switzerland_family.jpg','himalaya1.jpg','switzerland_family.jpg','Hit the world-famous slopes of Zermatt and Interlaken. This all-inclusive ski package includes equipment rental, ski school for children, après-ski fondue evenings, and cosy chalet accommodation with panoramic Matterhorn views.'),(33,'Switzerland Scenic Train & Lakes Tour',1,29,105000,'switzerland_family.jpg','himalaya1.jpg','classic.jpg','Journey aboard the legendary Glacier Express and Bernina Express, crossing breathtaking Alpine passes. Explore Lake Geneva, Lake Lucerne, Château de Chillon, and the charming medieval old town of Bern with your family.'),(34,'Dubai Theme Parks & Luxury Family Tour',1,30,95000,'dubai_family.jpg','Desert.jpg','dubai_family.jpg','Visit Legoland, Motiongate, and IMG Worlds of Adventure. Explore the Burj Khalifa observation deck, the Dubai Mall aquarium, and enjoy a thrilling desert safari with camel rides and BBQ dinner under the stars.'),(35,'Dubai Luxury Beach & Desert Family Holiday',1,30,110000,'dubai_family.jpg','Desert.jpg','dubai_family.jpg','Stay at a 5-star Jumeirah beach resort with private beach access. Experience dolphin watching at Atlantis, visit the traditional Gold and Spice Souk, and take a magical sunset camel ride through the golden Arabian dunes.'),(36,'Complete Char Dham Yatra Package',2,31,35000,'char_dham.jpg','religious_india.jpg','varanasi_ganga.jpg','A sacred journey covering all four dhams — Yamunotri, Gangotri, Kedarnath, and Badrinath. Includes helicopter option for Kedarnath darshan, comfortable guesthouses at each dham, daily puja arrangements, and experienced religious guides.'),(37,'Do Dham - Kedarnath & Badrinath Yatra',2,31,22000,'char_dham.jpg','religious_india.jpg','varanasi_ganga.jpg','Pay sacred respects at the majestic Kedarnath Jyotirlinga and the divine Badrinath Vishnu temple. Enjoy scenic Himalayan landscapes, a sunrise river rafting session in Rishikesh, and the magnificent evening Ganga Aarti at Haridwar.'),(38,'Buddha\'s Sacred Circuit - Full Tour',2,32,28000,'buddhist_pilgrimage.jpg','religious_india.jpg','varanasi_ganga.jpg','Visit the Mahabodhi Temple at Bodh Gaya where Buddha attained enlightenment, Sarnath where he delivered his first sermon, and Kushinagar where he attained Mahaparinirvana. Includes guided meditation retreats and monk interaction sessions.'),(39,'Lumbini & Nepal Buddhist Pilgrimage',2,32,32000,'buddhist_pilgrimage.jpg','religious_india.jpg','varanasi_ganga.jpg','Cross into Nepal to visit Lumbini, the birthplace of Siddhartha Gautama. Explore the sacred Maya Devi Temple, meditate in peaceful monastery gardens, and discover the ancient ruins and stupa complex of Kapilavastu.'),(40,'Vatican & Rome Sacred Pilgrimage Tour',2,33,88000,'vatican_rome.jpg','italy_family.jpg','vatican_rome.jpg','Attend Papal Audience at St. Peter\'s Square, marvel at Michelangelo\'s Sistine Chapel ceiling, and explore the vast Vatican Museums. Includes a special papal blessing ceremony, private guided mass, and expert theological commentary.'),(41,'Rome, Assisi & Florence Pilgrimage',2,33,95000,'vatican_rome.jpg','italy_family.jpg','vatican_rome.jpg','Walk the cobblestone streets of Assisi, birthplace of St. Francis of Assisi. Visit the magnificent Basilica di Santa Croce in Florence, the Uffizi Gallery, and conclude with an unforgettable candlelit mass at St. Peter\'s Basilica in Rome.'),(42,'Amritsar Golden Temple & Wagah Border Tour',2,34,18000,'golden_temple.jpg','religious_india.jpg','golden_temple.jpg','Experience the breathtaking Golden Temple at dawn\'s first light, witness the patriotic Wagah Border flag-lowering ceremony, and explore the moving Jallianwala Bagh memorial. Includes the sacred langar (community meal) experience.'),(43,'Punjab Heritage & Spiritual Grand Tour',2,34,24000,'golden_temple.jpg','religious_india.jpg','golden_temple.jpg','Discover the full spiritual heritage of Punjab — the Golden Temple, Anandpur Sahib (birthplace of the Khalsa), Tarn Taran Sahib, and the historic Fatehgarh Sahib Gurudwara. A deeply moving cultural and spiritual experience.'),(44,'Varanasi Ganga Aarti & Spiritual Experience',2,35,20000,'varanasi_ganga.jpg','religious_india.jpg','char_dham.jpg','Witness the magnificent Ganga Aarti ceremony on the sacred ghats of Varanasi, take a divine dawn boat ride on the Ganges, visit Kashi Vishwanath Jyotirlinga temple, and explore the ancient deer park at Sarnath. A profoundly moving experience.'),(45,'Ayodhya Ram Mandir & Varanasi Grand Tour',2,35,25000,'varanasi_ganga.jpg','religious_india.jpg','char_dham.jpg','Visit the newly consecrated Ram Mandir in Ayodhya, take blessings at Hanuman Garhi and Kanak Bhawan temples, then travel to Varanasi for the world-famous Ganga Aarti spectacle and a serene sunrise boat ride on the sacred river.');
/*!40000 ALTER TABLE `package` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `subcategory`
--

DROP TABLE IF EXISTS `subcategory`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `subcategory` (
  `Subcatid` int(200) NOT NULL AUTO_INCREMENT,
  `Subcatname` varchar(1000) NOT NULL,
  `Catid` int(200) NOT NULL,
  `Pic` varchar(8000) NOT NULL,
  `Detail` varchar(8000) NOT NULL,
  PRIMARY KEY (`Subcatid`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `subcategory`
--

LOCK TABLES `subcategory` WRITE;
/*!40000 ALTER TABLE `subcategory` DISABLE KEYS */;
INSERT INTO `subcategory` VALUES (1,'Family holiday to India   ',1,'india_family.jpg','India is a land of incredible diversity — from the snow-capped peaks of the Himalayas and the golden sands of Rajasthan to the lush backwaters of Kerala and the ancient temples of Tamil Nadu. A family holiday to India is a sensory adventure like no other. Watch your children\'s eyes widen at the majesty of the Taj Mahal at sunrise, explore the magnificent forts and palaces of Jaipur, cruise through the serene backwaters of Alleppey, and experience the colourful chaos of a traditional Indian bazaar. India offers world-class wildlife safaris in Ranthambore and Corbett, thrilling rickshaw rides through Old Delhi, cooking classes, elephant encounters, and so much more. Our carefully curated family packages handle every detail — comfortable hotels, private guides, seamless transportation, and age-appropriate activities — so you can focus entirely on making lifelong memories together. Whether your family seeks history, adventure, wildlife, or spiritual discovery, India delivers it all in breathtaking abundance.'),(2,'Canada family  holiday',1,'canada_family.jpg','Canada is the ultimate playground for adventurous families. Stretching across vast wilderness from the Atlantic to the Pacific, this magnificent country offers some of the world\'s most spectacular natural wonders. Discover the impossibly turquoise waters of Lake Louise and Moraine Lake in the Canadian Rockies, spot grizzly bears and wolves in Banff National Park, and ride through dramatic mountain passes on the legendary Rocky Mountaineer train. From the thundering power of Niagara Falls to the vibrant, multicultural energy of Vancouver and Toronto, Canada captivates at every turn. Our family adventure packages include white water rafting on the Kicking Horse River, guided glacier walks on the Columbia Icefields, kayaking expeditions, stargazing nights under the northern lights, and authentic ranch experiences. Canada\'s wide open spaces, pristine air, and warm local hospitality create the perfect environment for families to reconnect, explore, and discover the extraordinary together. An adventure your children will talk about for decades.'),(3,'Family holiday in Italy',1,'italy_family.jpg','Italy is an open-air museum, a culinary paradise, and a land of extraordinary natural beauty — making it one of the world\'s most captivating destinations for a family holiday. Walk through 2,000 years of history in Rome, tossing coins into the Trevi Fountain and exploring the awe-inspiring Colosseum. Marvel at Michelangelo\'s David in Florence and glide along Venice\'s magical canals in a traditional gondola. Discover the dramatic coastline of the Amalfi Coast with its colourful cliffside villages tumbling into the sparkling Mediterranean sea. Hike the ancient paths of Cinque Terre, visit the preserved ruins of Pompeii at the foot of Mount Vesuvius, and taste freshly made gelato and pizza in the piazzas of Naples. Our Italy family holidays are crafted to balance world-class cultural exploration with relaxed beach time, ensuring children and adults are equally enchanted. Italy\'s warmth, passion, and extraordinary food make every moment of this family holiday a genuine celebration of la dolce vita — the sweet life.'),(4,'Religious Tours in India',2,'religious_india.jpg','India is one of the world\'s most sacred lands — the birthplace of four great religions (Hinduism, Buddhism, Jainism, and Sikhism) and home to thousands of temples, mosques, churches, and shrines of profound spiritual significance. A religious tour of India is a deeply moving and transformative journey for the soul. Stand in awe before the Golden Temple in Amritsar as its radiant reflection shimmers in the sacred Sarovar lake at dawn. Witness the mesmerising Ganga Aarti ceremony on the ancient ghats of Varanasi as hundreds of oil lamps drift down the sacred Ganges river. Embark on the arduous but deeply rewarding Char Dham Yatra through the majestic Himalayas. Trace the footsteps of Lord Buddha from Bodh Gaya to Sarnath to Kushinagar. Bow before the towering gopurams of the magnificent Meenakshi Amman Temple in Madurai and the brilliantly carved Brihadeeswarar Temple in Thanjavur. From the desert temples of Rajasthan to the pilgrimage sites of Vrindavan, Mathura, Tirupati, and Shirdi — India\'s spiritual tapestry is boundless, deeply personal, and absolutely unforgettable. Let us guide you on a journey that nourishes both the spirit and the soul.'),(27,'Japan Family Holiday',1,'japan_family.jpg','Discover the magical blend of ancient temples, futuristic cities, cherry blossoms, and child-friendly theme parks. Japan is an extraordinary family destination that delights all ages with unique culture and unforgettable experiences.'),(28,'Bali Family Escape',1,'bali_family.jpg','Experience lush tropical jungles, sacred temples, pristine beaches, and world-class resorts. Bali offers the perfect mix of adventure and relaxation for families of all sizes seeking sun, sand, and culture.'),(29,'Switzerland Family Adventure',1,'switzerland_family.jpg','Ride the scenic mountain trains, explore fairytale alpine villages, and ski on world-class slopes. Switzerland is a dream destination for families seeking outdoor adventure, breathtaking scenery, and premium comfort.'),(30,'Dubai Family Experience',1,'dubai_family.jpg','From the towering Burj Khalifa to thrilling water parks and desert safari adventures, Dubai offers non-stop excitement, luxury shopping, and unforgettable experiences for the whole family.'),(31,'Char Dham Yatra',2,'char_dham.jpg','Embark on the sacred Char Dham pilgrimage — Yamunotri, Gangotri, Kedarnath, and Badrinath. One of the most revered Hindu pilgrimages set amidst the majestic Himalayas, cleansing the soul and offering divine blessings.'),(32,'Buddhist Pilgrimage Circuit',2,'buddhist_pilgrimage.jpg','Walk in the footsteps of Lord Buddha across the sacred sites of Bodh Gaya, Sarnath, Kushinagar, and Lumbini. A deeply spiritual journey through the birthplace of Buddhism and the lands of enlightenment.'),(33,'Vatican & Rome Pilgrimage',2,'vatican_rome.jpg','Visit the heart of Catholicism — the magnificent St. Peter\'s Basilica, the Sistine Chapel, and the Vatican Museums. A profound spiritual journey through the Eternal City with expert theological guides.'),(34,'Golden Temple & Punjab Tour',2,'golden_temple.jpg','Experience the divine serenity of the Golden Temple in Amritsar, the holiest shrine of Sikhism. Witness the mesmerising palki sahib ceremony, partake in langar, and explore the rich spiritual heritage of Punjab.'),(35,'Varanasi & Ayodhya Spiritual Tour',2,'varanasi_ganga.jpg','Journey to the sacred banks of the Ganges in Varanasi and the holy birthplace of Lord Ram in Ayodhya. Witness the spectacular Ganga Aarti, take a sunrise boat ride, and immerse yourself in ancient Hindu traditions.');
/*!40000 ALTER TABLE `subcategory` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `Username` varchar(100) NOT NULL,
  `Pwd` varchar(100) NOT NULL,
  `Typeofuser` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES ('admin','$2y$10$zAiyyrpj3I3TbtCRysJl5e161VcJcLupnTta3zRQHzSo0c7WFMHRi','Admin'),('neeru','$2y$10$bCoitkqVLrk8D03QXpX2UezXgfs4o6Qd6xnHy/FyDe/CPHNupQCeK','general'),('manu','$2y$10$i.QNYeAKmXaLnobeQtXdwOPUG5cFrQ8WlUFisKFP7oVMFAyQNjZqe','Admin'),('preet','$2y$10$/BF6YP64hOKTac9FE7crtOZ4fEoXPQKEUD22WPohEg3nNfqa2j1ka','general');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-06-16 18:33:37
