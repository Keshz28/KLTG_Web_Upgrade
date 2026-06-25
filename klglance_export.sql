-- klglance table export for InfinityFree (if0_42146057_kltg)
-- Import via InfinityFree Control Panel -> phpMyAdmin -> select if0_42146057_kltg -> Import

DROP TABLE IF EXISTS `klglance`;
CREATE TABLE `klglance` (
  `klglance_id` int(11) NOT NULL AUTO_INCREMENT,
  `klglance_order` int(11) NOT NULL DEFAULT 0,
  `klglance_title` varchar(255) NOT NULL,
  `klglance_content` text DEFAULT NULL,
  `klglance_image` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`klglance_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `klglance` (`klglance_id`,`klglance_order`,`klglance_title`,`klglance_content`,`klglance_image`) VALUES ("1","1","Twin Tower","Soaring 452 metres into the KL skyline, the Petronas Twin Towers were the world\'s tallest buildings from 1998 to 2004. Connected by a sky bridge on the 41st and 42nd floors, they remain the defining symbol of modern Malaysia.","klcc.jpg");
INSERT INTO `klglance` (`klglance_id`,`klglance_order`,`klglance_title`,`klglance_content`,`klglance_image`) VALUES ("2","2","KL Tower","Standing 421 metres tall, Menara Kuala Lumpur is a telecommunications tower and observation deck offering 360° panoramic views of the city. Its distinctive pod-shaped observation deck has become a beloved part of the KL skyline.","kltower.jpg");
INSERT INTO `klglance` (`klglance_id`,`klglance_order`,`klglance_title`,`klglance_content`,`klglance_image`) VALUES ("3","3","Dataran Merdeka","The birthplace of Malaysian independence. On 31 August 1957, the Union Jack was lowered here and the Malaysian flag raised for the first time. Today, the square\'s 100-metre flagpole — one of the world\'s tallest — stands as a proud symbol of nationhood, framed by the colonial-era Royal Selangor Club and the Sultan Abdul Samad Building.","DataranMerdeka.jpg");
INSERT INTO `klglance` (`klglance_id`,`klglance_order`,`klglance_title`,`klglance_content`,`klglance_image`) VALUES ("4","4","Merdeka 118","Soaring 678.9 metres across 118 floors, Merdeka 118 is the world\'s second-tallest building and Malaysia\'s boldest architectural statement. Its faceted glass façade echoes the geometry of a traditional kite, while the observation deck on the 116th floor delivers unrivalled panoramic views of the entire Klang Valley.","Merdeka118.jpg");
INSERT INTO `klglance` (`klglance_id`,`klglance_order`,`klglance_title`,`klglance_content`,`klglance_image`) VALUES ("5","5","Batu Caves","A network of sacred Hindu shrines set inside ancient limestone caves just north of the city. The towering 42.7-metre golden statue of Lord Murugan — the tallest in the world — guards the entrance, while 272 rainbow-coloured steps lead to the Cathedral Cave above, where temples and shrines have been carved into the rock for over a century.","BatuCaves.jpg");
INSERT INTO `klglance` (`klglance_id`,`klglance_order`,`klglance_title`,`klglance_content`,`klglance_image`) VALUES ("6","6","Thean Hou Temple","Perched on a hilltop in Seputeh, this six-tiered Chinese temple dedicated to the sea goddess Mazu is one of Southeast Asia\'s largest and most ornate. Built by the Hainanese community and inaugurated in 1989, it blends Buddhist, Taoist and Confucian elements — and offers sweeping panoramic views over the KL skyline.","TheanHouTemple.jpg");
INSERT INTO `klglance` (`klglance_id`,`klglance_order`,`klglance_title`,`klglance_content`,`klglance_image`) VALUES ("7","7","Masjid Wilayah Persekutuan","One of Kuala Lumpur\'s grandest mosques, the Federal Territory Mosque is crowned by a distinctive blue-tiled dome and set within beautifully landscaped grounds. Completed in 2000, it accommodates up to 17,000 worshippers across multiple levels and stands as an eloquent expression of contemporary Islamic architecture in Malaysia.","MosqueKL.jpg");
