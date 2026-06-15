-- ============================================================
-- NEW SUBCATEGORIES & PACKAGES: Family Tours + Religious Tours
-- DB: travel | subcategory cols: Subcatid,Subcatname,Catid,Pic,Detail
-- DB: travel | package cols: Packid,Packname,Category,Subcategory,Packprice,Pic1,Pic2,Pic3,Detail
-- ============================================================

-- -----------------------------------------------
-- FAMILY TOURS new subcategories (Catid = 1)
-- -----------------------------------------------
INSERT INTO subcategory (Subcatname, Catid, Pic, Detail) VALUES
('Japan Family Holiday', 1, '93.jpg',
 'Discover the magical blend of ancient temples, futuristic cities, cherry blossoms, and child-friendly theme parks. Japan is an extraordinary family destination that delights all ages with unique culture and unforgettable experiences.'),

('Bali Family Escape', 1, '94.jpg',
 'Experience lush tropical jungles, sacred temples, pristine beaches, and world-class resorts. Bali offers the perfect mix of adventure and relaxation for families of all sizes seeking sun, sand, and culture.'),

('Switzerland Family Adventure', 1, '95.jpg',
 'Ride the scenic mountain trains, explore fairytale alpine villages, and ski on world-class slopes. Switzerland is a dream destination for families seeking outdoor adventure, breathtaking scenery, and premium comfort.'),

('Dubai Family Experience', 1, '96.jpg',
 'From the towering Burj Khalifa to thrilling water parks and desert safari adventures, Dubai offers non-stop excitement, luxury shopping, and unforgettable experiences for the whole family.');

-- -----------------------------------------------
-- RELIGIOUS TOURS new subcategories (Catid = 2)
-- -----------------------------------------------
INSERT INTO subcategory (Subcatname, Catid, Pic, Detail) VALUES
('Char Dham Yatra', 2, '97.jpg',
 'Embark on the sacred Char Dham pilgrimage — Yamunotri, Gangotri, Kedarnath, and Badrinath. One of the most revered Hindu pilgrimages set amidst the majestic Himalayas, cleansing the soul and offering divine blessings.'),

('Buddhist Pilgrimage Circuit', 2, '98.jpg',
 'Walk in the footsteps of Lord Buddha across the sacred sites of Bodh Gaya, Sarnath, Kushinagar, and Lumbini. A deeply spiritual journey through the birthplace of Buddhism and the lands of enlightenment.'),

('Vatican & Rome Pilgrimage', 2, '99.jpg',
 'Visit the heart of Catholicism — the magnificent St. Peter''s Basilica, the Sistine Chapel, and the Vatican Museums. A profound spiritual journey through the Eternal City with expert theological guides.'),

('Golden Temple & Punjab Tour', 2, '100.jpg',
 'Experience the divine serenity of the Golden Temple in Amritsar, the holiest shrine of Sikhism. Witness the mesmerising palki sahib ceremony, partake in langar, and explore the rich spiritual heritage of Punjab.'),

('Varanasi & Ayodhya Spiritual Tour', 2, '101.jpg',
 'Journey to the sacred banks of the Ganges in Varanasi and the holy birthplace of Lord Ram in Ayodhya. Witness the spectacular Ganga Aarti, take a sunrise boat ride, and immerse yourself in ancient Hindu traditions.');

-- -----------------------------------------------
-- PACKAGES FOR FAMILY TOURS NEW SUBCATEGORIES
-- -----------------------------------------------

-- Japan Family Holiday
INSERT INTO package (Packname, Category, Subcategory, Packprice, Pic1, Pic2, Pic3, Detail) VALUES
('Tokyo & Kyoto Family Adventure', 1,
 (SELECT Subcatid FROM subcategory WHERE Subcatname='Japan Family Holiday' LIMIT 1),
 85000, '55.jpg', '56.jpg', '57.jpg',
 'Explore the neon-lit streets of Tokyo, ancient Kyoto temples, and the iconic Mount Fuji with your family. Includes bullet train pass, robot restaurant dinner, and guided tours of Disneyland Tokyo. A journey your family will cherish forever.'),

('Japan Cherry Blossom Family Tour', 1,
 (SELECT Subcatid FROM subcategory WHERE Subcatname='Japan Family Holiday' LIMIT 1),
 92000, '58.jpg', '59.jpg', '60.jpg',
 'Time your visit to witness Japan''s spectacular cherry blossom season. Hanami picnics in Ueno Park, visits to Arashiyama bamboo groves, family tea ceremony workshops, and a traditional ryokan stay with hot spring baths.');

-- Bali Family Escape
INSERT INTO package (Packname, Category, Subcategory, Packprice, Pic1, Pic2, Pic3, Detail) VALUES
('Bali Tropical Family Holiday', 1,
 (SELECT Subcatid FROM subcategory WHERE Subcatname='Bali Family Escape' LIMIT 1),
 55000, '61.jpg', '62.jpg', '63.jpg',
 'Relax on pristine beaches, explore terraced rice paddies, and visit sacred Tanah Lot temple at sunset. This Bali family package includes private villa stay, Balinese cooking classes, and guided jungle trekking to hidden waterfalls.'),

('Bali Adventure & Culture Family Tour', 1,
 (SELECT Subcatid FROM subcategory WHERE Subcatname='Bali Family Escape' LIMIT 1),
 62000, '64.jpg', '65.jpg', '66.jpg',
 'Discover Balinese culture through traditional Kecak dance performances, silver jewellery making workshops, and elephant sanctuary visits. Includes thrilling white water rafting on the Ayung River and a Ubud market visit.');

-- Switzerland Family Adventure
INSERT INTO package (Packname, Category, Subcategory, Packprice, Pic1, Pic2, Pic3, Detail) VALUES
('Swiss Alps Family Ski Holiday', 1,
 (SELECT Subcatid FROM subcategory WHERE Subcatname='Switzerland Family Adventure' LIMIT 1),
 120000, '67.jpg', '68.jpg', '69.jpg',
 'Hit the world-famous slopes of Zermatt and Interlaken. This all-inclusive ski package includes equipment rental, ski school for children, après-ski fondue evenings, and cosy chalet accommodation with panoramic Matterhorn views.'),

('Switzerland Scenic Train & Lakes Tour', 1,
 (SELECT Subcatid FROM subcategory WHERE Subcatname='Switzerland Family Adventure' LIMIT 1),
 105000, '70.jpg', '72.jpg', '73.jpg',
 'Journey aboard the legendary Glacier Express and Bernina Express, crossing breathtaking Alpine passes. Explore Lake Geneva, Lake Lucerne, Château de Chillon, and the charming medieval old town of Bern with your family.');

-- Dubai Family Experience
INSERT INTO package (Packname, Category, Subcategory, Packprice, Pic1, Pic2, Pic3, Detail) VALUES
('Dubai Theme Parks & Luxury Family Tour', 1,
 (SELECT Subcatid FROM subcategory WHERE Subcatname='Dubai Family Experience' LIMIT 1),
 95000, '75.jpg', '76.jpg', '77.jpg',
 'Visit Legoland, Motiongate, and IMG Worlds of Adventure. Explore the Burj Khalifa observation deck, the Dubai Mall aquarium, and enjoy a thrilling desert safari with camel rides and BBQ dinner under the stars.'),

('Dubai Luxury Beach & Desert Family Holiday', 1,
 (SELECT Subcatid FROM subcategory WHERE Subcatname='Dubai Family Experience' LIMIT 1),
 110000, '81.jpg', '82.jpg', '83.jpg',
 'Stay at a 5-star Jumeirah beach resort with private beach access. Experience dolphin watching at Atlantis, visit the traditional Gold and Spice Souk, and take a magical sunset camel ride through the golden Arabian dunes.');

-- -----------------------------------------------
-- PACKAGES FOR RELIGIOUS TOURS NEW SUBCATEGORIES
-- -----------------------------------------------

-- Char Dham Yatra
INSERT INTO package (Packname, Category, Subcategory, Packprice, Pic1, Pic2, Pic3, Detail) VALUES
('Complete Char Dham Yatra Package', 2,
 (SELECT Subcatid FROM subcategory WHERE Subcatname='Char Dham Yatra' LIMIT 1),
 35000, '84.jpg', '85.jpg', '86.jpg',
 'A sacred journey covering all four dhams — Yamunotri, Gangotri, Kedarnath, and Badrinath. Includes helicopter option for Kedarnath darshan, comfortable guesthouses at each dham, daily puja arrangements, and experienced religious guides.'),

('Do Dham - Kedarnath & Badrinath Yatra', 2,
 (SELECT Subcatid FROM subcategory WHERE Subcatname='Char Dham Yatra' LIMIT 1),
 22000, '87.jpg', '88.jpg', '89.jpg',
 'Pay sacred respects at the majestic Kedarnath Jyotirlinga and the divine Badrinath Vishnu temple. Enjoy scenic Himalayan landscapes, a sunrise river rafting session in Rishikesh, and the magnificent evening Ganga Aarti at Haridwar.');

-- Buddhist Pilgrimage Circuit
INSERT INTO package (Packname, Category, Subcategory, Packprice, Pic1, Pic2, Pic3, Detail) VALUES
('Buddha''s Sacred Circuit - Full Tour', 2,
 (SELECT Subcatid FROM subcategory WHERE Subcatname='Buddhist Pilgrimage Circuit' LIMIT 1),
 28000, '93.jpg', '94.jpg', '95.jpg',
 'Visit the Mahabodhi Temple at Bodh Gaya where Buddha attained enlightenment, Sarnath where he delivered his first sermon, and Kushinagar where he attained Mahaparinirvana. Includes guided meditation retreats and monk interaction sessions.'),

('Lumbini & Nepal Buddhist Pilgrimage', 2,
 (SELECT Subcatid FROM subcategory WHERE Subcatname='Buddhist Pilgrimage Circuit' LIMIT 1),
 32000, '96.jpg', '97.jpg', '98.jpg',
 'Cross into Nepal to visit Lumbini, the birthplace of Siddhartha Gautama. Explore the sacred Maya Devi Temple, meditate in peaceful monastery gardens, and discover the ancient ruins and stupa complex of Kapilavastu.');

-- Vatican & Rome Pilgrimage
INSERT INTO package (Packname, Category, Subcategory, Packprice, Pic1, Pic2, Pic3, Detail) VALUES
('Vatican & Rome Sacred Pilgrimage Tour', 2,
 (SELECT Subcatid FROM subcategory WHERE Subcatname='Vatican & Rome Pilgrimage' LIMIT 1),
 88000, '99.jpg', '100.jpg', '101.jpg',
 'Attend Papal Audience at St. Peter''s Square, marvel at Michelangelo''s Sistine Chapel ceiling, and explore the vast Vatican Museums. Includes a special papal blessing ceremony, private guided mass, and expert theological commentary.'),

('Rome, Assisi & Florence Pilgrimage', 2,
 (SELECT Subcatid FROM subcategory WHERE Subcatname='Vatican & Rome Pilgrimage' LIMIT 1),
 95000, '102.jpg', '106.jpg', '107.jpg',
 'Walk the cobblestone streets of Assisi, birthplace of St. Francis of Assisi. Visit the magnificent Basilica di Santa Croce in Florence, the Uffizi Gallery, and conclude with an unforgettable candlelit mass at St. Peter''s Basilica in Rome.');

-- Golden Temple & Punjab Tour
INSERT INTO package (Packname, Category, Subcategory, Packprice, Pic1, Pic2, Pic3, Detail) VALUES
('Amritsar Golden Temple & Wagah Border Tour', 2,
 (SELECT Subcatid FROM subcategory WHERE Subcatname='Golden Temple & Punjab Tour' LIMIT 1),
 18000, '108.jpg', '109.jpg', '110.jpg',
 'Experience the breathtaking Golden Temple at dawn''s first light, witness the patriotic Wagah Border flag-lowering ceremony, and explore the moving Jallianwala Bagh memorial. Includes the sacred langar (community meal) experience.'),

('Punjab Heritage & Spiritual Grand Tour', 2,
 (SELECT Subcatid FROM subcategory WHERE Subcatname='Golden Temple & Punjab Tour' LIMIT 1),
 24000, '111.jpg', '112.jpg', '113.jpg',
 'Discover the full spiritual heritage of Punjab — the Golden Temple, Anandpur Sahib (birthplace of the Khalsa), Tarn Taran Sahib, and the historic Fatehgarh Sahib Gurudwara. A deeply moving cultural and spiritual experience.');

-- Varanasi & Ayodhya Spiritual Tour
INSERT INTO package (Packname, Category, Subcategory, Packprice, Pic1, Pic2, Pic3, Detail) VALUES
('Varanasi Ganga Aarti & Spiritual Experience', 2,
 (SELECT Subcatid FROM subcategory WHERE Subcatname='Varanasi & Ayodhya Spiritual Tour' LIMIT 1),
 20000, '114.jpg', '12.jpg', '13.jpg',
 'Witness the magnificent Ganga Aarti ceremony on the sacred ghats of Varanasi, take a divine dawn boat ride on the Ganges, visit Kashi Vishwanath Jyotirlinga temple, and explore the ancient deer park at Sarnath. A profoundly moving experience.'),

('Ayodhya Ram Mandir & Varanasi Grand Tour', 2,
 (SELECT Subcatid FROM subcategory WHERE Subcatname='Varanasi & Ayodhya Spiritual Tour' LIMIT 1),
 25000, '14.jpg', '24.jpg', '25.jpg',
 'Visit the newly consecrated Ram Mandir in Ayodhya, take blessings at Hanuman Garhi and Kanak Bhawan temples, then travel to Varanasi for the world-famous Ganga Aarti spectacle and a serene sunrise boat ride on the sacred river.');
