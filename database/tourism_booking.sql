-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 12, 2025 at 04:06 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tourism_booking`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `package_id` int(11) NOT NULL,
  `booking_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `payment_method` enum('credit_card','online_banking','e_wallet') NOT NULL,
  `special_requests` text DEFAULT NULL,
  `status` enum('pending','paid','cancelled') NOT NULL,
  `num_travelers` int(11) NOT NULL DEFAULT 1,
  `total_price` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `user_id`, `package_id`, `booking_date`, `payment_method`, `special_requests`, `status`, `num_travelers`, `total_price`) VALUES
(9, 100, 12, '2025-05-11 05:01:02', 'credit_card', 'none', 'cancelled', 1, NULL),
(11, 100, 13, '2025-05-11 06:13:06', 'credit_card', 'none', 'paid', 1, NULL),
(12, 100, 14, '2025-05-11 06:37:03', 'online_banking', 'Chocolate', 'cancelled', 3, NULL),
(14, 100, 24, '2025-05-11 08:42:27', 'online_banking', '', 'paid', 2, 6000.00),
(16, 100, 25, '2025-05-11 08:47:10', 'e_wallet', '', 'cancelled', 1, 1000.00),
(20, 100, 17, '2025-05-12 07:44:14', 'credit_card', 'none', 'pending', 3, 3600.00),
(21, 100002, 11, '2025-05-12 07:57:36', 'online_banking', '', 'paid', 3, 16500.00),
(22, 100002, 26, '2025-05-12 07:58:10', 'e_wallet', '', 'paid', 2, 2400.00),
(23, 100004, 24, '2025-05-12 07:59:14', 'credit_card', 'Grasya', 'cancelled', 3, 9000.00),
(24, 100005, 23, '2025-05-12 08:01:15', 'online_banking', 'miming', 'paid', 3, 5400.00),
(25, 100005, 20, '2025-05-12 08:01:46', 'online_banking', 'maeasy', 'paid', 1, 2900.00),
(26, 100006, 17, '2025-05-12 12:26:33', 'online_banking', 'waws', 'paid', 4, 4800.00),
(27, 100007, 22, '2025-05-12 13:36:41', 'online_banking', 'Free lunch', 'paid', 3, 6900.00),
(28, 100002, 20, '2025-05-12 13:49:40', 'online_banking', 'surfing 5G sana', 'pending', 1, 2900.00);

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `subject` varchar(150) DEFAULT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `destinations`
--

CREATE TABLE `destinations` (
  `id` int(11) NOT NULL,
  `operator_id` int(11) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `itinerary` text DEFAULT NULL,
  `flight_info` varchar(255) DEFAULT NULL,
  `hotel` varchar(255) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `max_slots` int(11) DEFAULT 20,
  `is_featured` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `destinations`
--

INSERT INTO `destinations` (`id`, `operator_id`, `name`, `description`, `image_url`, `itinerary`, `flight_info`, `hotel`, `start_date`, `end_date`, `price`, `created_at`, `max_slots`, `is_featured`) VALUES
(11, 100001, 'Boracay Island (Aklan)', 'Famous for its powdery white sand beaches and vibrant nightlife.', 'https://images.unsplash.com/photo-1542213493895-edf5b94f5a96?q=80&w=1973&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D', '🏝️ Day 1: Arrival & White Beach Bliss\r\n\r\nMorning: Arrive at Caticlan Airport → boat transfer to Boracay\r\n\r\nCheck-in at beachfront hotel (Station 1 for peace, Station 2 for action)\r\n\r\nAfternoon: Relax on White Beach, take a swim, enjoy a fresh mango shake\r\n\r\nEvening: Watch the sunset and have seafood dinner at D’Talipapa\r\n\r\nOptional: Beachside massage or a chill bar with live music\r\n\r\n🌊 Day 2: Adventure & Exploration\r\n\r\nMorning: Island hopping tour (Puka Beach, Magic Island, Crystal Cove)\r\n\r\nLunch: Island picnic or buffet with the tour\r\n\r\nAfternoon: Snorkeling or helmet diving\r\n\r\nEvening: Dinner at D’Mall, sunset drinks at The White House, or party at Epic Bar\r\n\r\n🧘 Day 3: Chill & Depart\r\n\r\nMorning: Early swim or paddleboarding\r\n\r\nBrunch at The Sunny Side Café\r\n\r\nLast-minute souvenir shopping at D’Mall\r\n\r\nCheck-out and transfer back to the airport', 'Waws Airplane (Flight No: W31 A029)', 'Waws Hotel', '2025-05-12', '2025-05-15', 5500.00, '2025-05-11 10:04:32', 20, 1),
(12, 100001, 'Palawan (El Nido & Coron)', 'Offers a breathtaking escape of turquoise lagoons, hidden beaches, and dramatic limestone cliffs.', 'https://images.unsplash.com/photo-1518509562904-e7ef99cdcc86?q=80&w=1974&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D', '🏝️ Day 1: Arrival in Coron\r\n\r\nArrive in Coron Airport (Busuanga)\r\n\r\nCheck in to your hotel or resort\r\n\r\nAfternoon: Visit Maquinit Hot Spring and relax after your trip\r\n\r\nEvening: Dinner in town (try local seafood)\r\n\r\n🌊 Day 2: Coron Island Hopping\r\n\r\nFull-day tour:\r\n\r\nKayangan Lake\r\n\r\nTwin Lagoon\r\n\r\nBarracuda Lake\r\n\r\nSiete Pecados (snorkeling)\r\n\r\nLunch: Island picnic\r\n\r\nReturn to hotel, rest or massage\r\n\r\n✈️ Day 3: Travel to El Nido\r\n\r\nMorning: Ferry or flight to El Nido\r\n\r\nAfternoon: Check in and relax at the beach\r\n\r\nOptional sunset at Las Cabanas Beach\r\n\r\nDinner at Altrove (pizza/pasta) or Gusto\r\n\r\n🛶 Day 4: El Nido Island Hopping (Tour A or C)\r\n\r\nFull-day island tour (Tour A is most popular):\r\n\r\nBig Lagoon\r\n\r\nSecret Lagoon\r\n\r\nShimizu Island\r\n\r\n7 Commandos Beach\r\n\r\nLunch: Beach BBQ included\r\n\r\nReturn by late afternoon, relax or explore town\r\n\r\n🌅 Day 5: Free Time & Departure\r\n\r\nEarly swim or breakfast by the beach\r\n\r\nSouvenir shopping in El Nido town\r\n\r\nTransfer to airport (Lio or Puerto Princesa) for flight home', 'Waws Airplane (Flight No: W03 A629)', 'Waws Hotel', '2025-05-26', '2025-05-30', 8800.00, '2025-05-11 10:08:28', 20, 1),
(13, 100001, 'Banaue Rice Terraces (Ifugao)', '2,000-year-old rice terraces carved into the mountains by indigenous people.', 'https://plus.unsplash.com/premium_photo-1730035378824-930cdb688183?q=80&w=1974&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D', '🌄 Day 1: Travel to Banaue & Scenic Views\r\n\r\nEarly morning: Depart from Manila (by bus or private van – ~9 hours)\r\n\r\nAfternoon: Arrive in Banaue, check in to a local inn or homestay\r\n\r\nLate afternoon: Visit Banaue Viewpoint for panoramic views of the rice terraces\r\n\r\nEvening: Dinner and rest, enjoy the cool mountain air\r\n\r\n🥾 Day 2: Trekking & Cultural Immersion\r\n\r\nMorning: Start guided trek to Batad Rice Terraces (UNESCO World Heritage Site)\r\n\r\nPass through scenic trails and native villages\r\n\r\nVisit Tappiya Falls for a refreshing dip\r\n\r\nLunch: Local meal at a Batad homestay\r\n\r\nAfternoon: Learn about Ifugao culture and craftsmanship\r\n\r\nReturn to Banaue by late afternoon or stay overnight in Batad (optional)\r\n\r\n🛣️ Day 3: Souvenirs & Return\r\n\r\nMorning: Light breakfast, visit local shops for woodcrafts and native textiles\r\n\r\nOptional quick visit to Hapao Rice Terraces if time allows\r\n\r\nMidday: Depart for Manila or next destination\r\n\r\n', 'Waws Airplane (Flight No: W34 A503)', 'Waws Hotel', '2025-05-21', '2025-05-23', 2000.00, '2025-05-11 10:14:11', 20, 1),
(14, 100001, 'Chocolate Hills (Bohol)', 'Over 1,000 cone-shaped hills that turn brown in the dry season.', 'https://images.unsplash.com/photo-1708353234006-5f1a3f92a63a?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D', '🌄 Day 1: Chocolate Hills & Countryside Tour\r\n\r\nMorning:\r\n\r\nArrive in Tagbilaran or Panglao early\r\n\r\nHead straight to Chocolate Hills Viewpoint in Carmen\r\n\r\nOptional: Try the ATV ride or bike zipline at Chocolate Hills Adventure Park (CHAP)\r\n\r\nAfternoon:\r\n\r\nVisit the Tarsier Sanctuary in Corella\r\n\r\nLunch along the Loboc River Cruise with live music\r\n\r\nStop by Man-made Forest in Bilar for photos\r\n\r\nEvening:\r\n\r\nReturn to Tagbilaran or Panglao\r\n\r\nDinner at a local restaurant or beachside spot\r\n\r\n🌅 Day 2: Culture & Departure\r\n\r\nMorning:\r\n\r\nVisit Baclayon Church & Museum\r\n\r\nOptional stop at Blood Compact Shrine\r\n\r\nTry local delicacies or shop for souvenirs\r\n\r\nAfternoon:\r\n\r\nReturn to airport or pier for departure', 'Waws Airplane (Flight No: W13 A431)', 'Waws Hotel', '2025-05-12', '2025-05-13', 1500.00, '2025-05-11 10:17:27', 20, 1),
(15, 100001, 'Mayon Volcano (Albay, Bicol Region)', 'Perfectly cone-shaped volcano known for its symmetry.', 'https://images.unsplash.com/photo-1555590858-be28a58c2688?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D', '🌋 Mayon Volcano Day Trip Itinerary\r\n🚗 Morning:\r\n\r\nArrival in Legazpi City \r\n\r\nHead straight to Cagsawa Ruins for a stunning view of Mayon Volcano and photo ops\r\n\r\nVisit the Mayon Volcano Natural Park or Mayon Skyline View Deck (if weather permits)\r\n\r\n🏍️ Midday:\r\n\r\nGo on a Mayon ATV Adventure — ride through lava trails at the foot of the volcano\r\n\r\nLunch at a local restaurant with views of the volcano (e.g., Balay Cena Una or Small Talk Café)\r\n\r\n🏛️ Afternoon:\r\n\r\nExplore Daraga Church (a historic hilltop church with a panoramic view of Mayon)\r\n\r\nStop by Legazpi Boulevard for a relaxing coastal walk\r\n\r\nVisit Albay Farmers Market or souvenir shops for pili nuts and local crafts\r\n\r\n✈️ Evening:\r\n\r\nReturn to Legazpi Airport or head back to your hotel or next destination', 'Waws Airplane (Flight No: W83 A632)', 'Waws Hotel', '2025-05-29', '2025-05-29', 850.00, '2025-05-11 10:20:07', 20, 0),
(16, 100001, 'Intramuros (Manila)', 'Manila’s historic walled district, featuring Spanish-era landmarks, cobblestone streets, and rich cultural heritage.', 'https://images.unsplash.com/photo-1710001111595-9bf6a0c9095c?q=80&w=2060&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D', 'Morning:\r\n\r\nStart at Fort Santiago: Explore the historical site, learn about its role in Philippine history, and visit the Rizal Shrine Museum.\r\n\r\nWalk around the Walls: Enjoy panoramic views of the city from the historic walls of Intramuros, which once protected the Spanish colonial city.\r\n\r\nVisit Manila Cathedral: Admire the architecture of this grand church, a significant part of Intramuros\' history.\r\n\r\nLunch:\r\n\r\nEnjoy a meal at one of the many local restaurants or cafes, such as Barbara\'s or Ilustrado, which offer traditional Filipino-Spanish dishes.\r\n\r\nAfternoon:\r\n\r\nExplore San Agustin Church: Visit this UNESCO World Heritage Site, known for its stunning Baroque architecture.\r\n\r\nTour Casa Manila Museum: Step inside a colonial-style house that showcases the lifestyle of Filipino elites during the Spanish era.\r\n\r\nOptional: Take a Kalesa Ride: Hop on a traditional horse-drawn carriage to explore the old streets and learn about the history from your guide.\r\n\r\nRelax at Puerta Real Gardens: Enjoy a peaceful stroll through this garden, a serene spot in the heart of Intramuros.\r\n\r\nEvening (Optional):\r\n\r\nCatch the sunset: Head to a café with a view or walk around the walls to enjoy a sunset view of Manila Bay.\r\n\r\nDinner: Choose from local dining spots within or near Intramuros, like Ristorante delle Mitre for Filipino heritage cuisine.', '', '', '2025-05-28', '2025-05-28', 1000.00, '2025-05-11 10:25:19', 20, 0),
(17, 100001, 'Taal Lake (Batangas)', 'A picturesque lake within a volcano, perfect for boat tours and hiking to the summit for stunning views.', 'https://images.unsplash.com/photo-1621420561012-1adfa8e6bc2e?q=80&w=1974&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D', 'Day 1: Arrival and Explore Taal Lake\r\n\r\nMorning: Arrive in Batangas from Manila (2-3 hours by car)\r\n\r\nMidday: Take a boat ride to Taal Volcano and hike to the summit for panoramic views of the lake and crater\r\n\r\nAfternoon: Visit Taal Heritage Town to see Spanish-era architecture, like the Taal Church\r\n\r\nEvening: Stay in a nearby resort and enjoy dinner overlooking the lake\r\n\r\nDay 2: Relax and Adventure\r\n\r\nMorning: Enjoy a peaceful morning by the lake, go kayaking or swimming\r\n\r\nAfternoon: Visit Taal Volcano Island or go on a horseback ride around the volcano rim\r\n\r\nEvening: Return to Manila or continue exploring Batangas’ beaches and natural spots', '', 'Waws Hotel', '2025-05-13', '2025-05-14', 1200.00, '2025-05-11 10:28:37', 20, 1),
(18, 100001, 'Underground River (Palawan)', 'A UNESCO World Heritage site, this river flows through a cave system with impressive limestone formations.', 'https://images.unsplash.com/photo-1660849259228-603fad947b89?q=80&w=2072&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D', 'Underground River (Palawan)\r\nDay 1: Arrival in Puerto Princesa\r\n\r\nMorning: Arrive in Puerto Princesa, Palawan\r\n\r\nAfternoon: Explore Puerto Princesa City — visit Rizal Park, Plaza Cuartel, and local markets\r\n\r\nEvening: Enjoy a local seafood dinner\r\n\r\nDay 2: Underground River\r\n\r\nMorning: Take a day trip to the Puerto Princesa Underground River (UNESCO World Heritage Site)\r\n\r\nBoat Tour: Explore the 8.2 km-long river inside the cave system, passing through limestone formations\r\n\r\nAfternoon: Visit the nearby Sabang Beach or relax in the area\r\n\r\nEvening: Return to Puerto Princesa, enjoy dinner at a local restaurant\r\n\r\nDay 3: Additional Palawan Activities\r\n\r\nMorning: Visit Honda Bay for island hopping and snorkeling\r\n\r\nAfternoon: Explore Nagtabon Beach for a quieter, more secluded experience\r\n\r\nEvening: Return to the hotel, relax', 'Waws Airplane (Flight No: W60 A039)', 'Waws Hotel', '2025-05-30', '2025-06-01', 3000.00, '2025-05-11 10:30:16', 20, 1),
(19, 100001, 'Sohoton Cove (Samar)', 'A hidden paradise with crystal-clear waters, caves, lagoons, and natural rock formations.', 'https://plus.unsplash.com/premium_photo-1664283661416-01d9d7bae478?q=80&w=2073&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D', 'Sohoton Cove (Samar)\r\nDay 1: Arrival in Tacloban & Travel to Sohoton Cove\r\n\r\nMorning: Arrive in Tacloban (via flight from Manila)\r\n\r\nAfternoon: Travel to Sohoton Cove (Basey, Samar) — boat ride to the cove\r\n\r\nEvening: Stay in nearby accommodations or resorts and enjoy local dishes\r\n\r\nDay 2: Explore Sohoton Cove\r\n\r\nMorning: Take a boat tour through Sohoton Cove — explore the cave system, crystal-clear lagoons, and rock formations\r\n\r\nAfternoon: Visit the Sohoton Natural Bridge, a natural limestone bridge, and swim in the lagoons\r\n\r\nEvening: Return to your accommodation or enjoy a peaceful night by the water\r\n\r\nDay 3: More Exploration\r\n\r\nMorning: Visit Calbiga Caves (the largest cave system in the Philippines) for spelunking adventures\r\n\r\nAfternoon: Relax by the beach or do more local activities (kayaking, fishing)\r\n\r\nEvening: Return to Tacloban and relax', 'Waws Airplane (Flight No: W43 A013)', 'Waws Hotel', '2025-05-14', '2025-05-16', 2500.00, '2025-05-11 10:31:59', 20, 1),
(20, 100001, 'Pagudpud Beaches (Ilocos Norte)', 'Pristine white sand beaches like Saud Beach and Blue Lagoon, ideal for swimming and relaxation.', 'https://images.unsplash.com/photo-1720945490863-3b29042ba584?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D', 'Pagudpud Beaches (Ilocos Norte)\r\nDay 1: Arrival in Pagudpud\r\n\r\nMorning: Arrive in Pagudpud, Ilocos Norte (via flight to Laoag or bus from Manila)\r\n\r\nAfternoon: Relax at Saud Beach, swim, or enjoy the beautiful coastal views\r\n\r\nEvening: Watch the sunset and have a seafood dinner at a beachside restaurant\r\n\r\nDay 2: Explore Pagudpud Beaches\r\n\r\nMorning: Visit Blue Lagoon, enjoy water sports like snorkeling, kayaking, or surfing\r\n\r\nAfternoon: Explore Maira-ira Beach and nearby coves or take a boat trip to see the coastal cliffs\r\n\r\nEvening: Have dinner by the beach and relax under the stars\r\n\r\nDay 3: Nearby Attractions\r\n\r\nMorning: Visit Patapat Viaduct for stunning coastal views and photo opportunities\r\n\r\nAfternoon: Explore Kabigan Falls, enjoy a scenic trek to the falls for a refreshing swim\r\n\r\nEvening: Head back to Laoag or continue enjoying Pagudpud\'s beaches\r\n\r\n', 'Waws Airplane (Flight No: W01 A102)', 'Waws Hotel', '2025-05-18', '2025-05-20', 2900.00, '2025-05-11 10:34:08', 20, 0),
(21, 100001, 'Lake Caliraya (Laguna)', 'A man-made lake popular for water sports, camping, and picturesque views, perfect for nature lovers.', 'https://plus.unsplash.com/premium_photo-1677636665512-d210c919fe74?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D', 'Day 1: Arrival at Lake Caliraya\r\n\r\nMorning: Depart from Manila to Lake Caliraya, Laguna (~2-3 hours by car)\r\n\r\nAfternoon: Check into a lakeside resort and enjoy lunch with scenic views of the lake\r\n\r\nEvening: Go on a relaxing boat ride or enjoy some fishing by the lake\r\n\r\nDay 2: Adventure and Relaxation\r\n\r\nMorning: Try water sports such as kayaking, windsurfing, or wakeboarding\r\n\r\nAfternoon: Relax by the lake, go for a nature walk, or enjoy a picnic by the shore\r\n\r\nEvening: Have a dinner barbecue at the resort with lake views\r\n\r\nDay 3: Explore Nearby Nature Spots\r\n\r\nMorning: Visit Pagsanjan Falls or Kawasan Falls (1.5-2 hours from Lake Caliraya)\r\n\r\nAfternoon: Take a boat ride through the scenic Pagsanjan Gorge or swim at Kawasan\r\n\r\nEvening: Return to Lake Caliraya for a relaxing night under the stars', 'Waws Airplane (Flight No: W09 A648)', 'Waws Hotel', '2025-05-23', '2025-05-25', 4000.00, '2025-05-11 10:35:39', 20, 1),
(22, 101, 'Siargao Island (Surigao del Norte)', 'The surfing capital of the Philippines, known for Cloud 9, island hopping, lagoons, and natural rock pools.', 'https://images.unsplash.com/photo-1650823351306-17f53aec06c2?q=80&w=2072&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D', 'Day 1: Arrive in Siargao, surf at Cloud 9, relax at General Luna beach.\r\n\r\nDay 2: Go island hopping — Daku, Guyam, and Naked Island.\r\n\r\nDay 3: Visit Sugba Lagoon, Magpupungko Rock Pools, and enjoy local food.', 'Waws Airplane (Flight No: W03 A629)', 'Waws Hotel', '2025-05-29', '2025-05-31', 2300.00, '2025-05-11 15:20:17', 20, 1),
(23, 101, 'Enchanted River (Hinatuan, Surigao del Sur)', 'A crystal-clear, deep blue river surrounded by lush forest, known for its mysterious depth and beauty.', 'https://images.unsplash.com/photo-1696321791412-e5d5c51c39f7?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D', 'Day 1: Travel to Hinatuan, visit the Enchanted River, and enjoy a swim or boat ride.\r\n\r\nDay 2: Explore nearby Tinuy-an Falls or Britania Islands before heading back.', 'Waws Airplane (Flight No: W31 A029)', 'Waws Hotel', '2025-05-11', '2025-05-12', 1800.00, '2025-05-11 15:21:55', 20, 0),
(24, 101, 'Hundred Islands (Pangasinan)', 'A national park with over 100 islands perfect for swimming, snorkeling, and island hopping.', 'https://plus.unsplash.com/premium_photo-1668883189361-9c754861dbd6?q=80&w=1974&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D', 'Day 1: Arrive in Alaminos, go island hopping — visit Governor\'s Island, Quezon Island, and Children\'s Island.\r\n\r\nDay 2: Kayaking, ziplining, and snorkeling, then enjoy seafood by the beach.', 'Waws Airplane (Flight No: W03 A629)', 'Waws Hotel', '2025-05-14', '2025-05-15', 3000.00, '2025-05-11 16:28:14', 20, 1),
(25, 101, 'Mt. Pulag (Benguet)', 'The third-highest mountain in the Philippines, famous for its sea of clouds and sunrise hikes.', 'https://images.unsplash.com/photo-1670641059897-a1c8dbc615e4?q=80&w=1974&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D', 'Day 1: Travel to Bokod, Benguet, attend orientation, then start the trek.\r\n\r\nDay 2: Summit early for sunrise above the clouds, then descend and return.\r\n\r\n', 'Waws Airplane (Flight No: W34 A503)', 'Waws Hotel', '2025-05-15', '2025-05-16', 1000.00, '2025-05-11 16:29:25', 20, 1),
(26, 101, 'Malapascua Island (Cebu)', 'A small island known for white-sand beaches and diving with thresher sharks.', 'https://images.unsplash.com/photo-1724427555581-521bfb683cf0?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D', 'Day 1: Arrive via Cebu and boat to Malapascua, relax at Bounty Beach.\r\n\r\nDay 2: Go diving/snorkeling, spot thresher sharks or explore coral reefs.\r\n\r\nDay 3: Island walk, visit Lighthouse, and enjoy a peaceful beach day.', 'Waws Airplane (Flight No: W03 A629)', 'Waws Hotel', '2025-05-23', '2025-05-25', 1200.00, '2025-05-11 17:17:27', 20, 0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` enum('traveler','operator','admin') DEFAULT 'traveler',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('active','inactive') DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `created_at`, `status`) VALUES
(100, 'Test Traveler', 'traveler@email.com', '$2y$10$i3Ht8E/iPpqKWo/E7rzXxum1BJuDVVb9bQGvkvpBDK2J5.odmJHhy', 'traveler', '2025-05-10 05:33:21', 'active'),
(101, 'Test Operator', 'operator@email.com', '$2y$10$JJSgTbjVCVG2tkq.SdZA5.8U.xV4EyhMMXzO9vImET6vseb52jbei', 'operator', '2025-05-10 05:33:41', 'active'),
(100000, 'Test Operator 2', 'operator2@email.com', '$2y$10$qRjV456JIoMCyQK9sibWueaJoB.g79CtSow8mwjZD.wExzbIEqLfq', 'operator', '2025-05-10 12:06:14', 'active'),
(100001, NULL, 'admin@email.com', '$2y$10$FoG8YpJat0rzbzTJpE9h8.5.AKIzmJ0SZC3agD2gn75f2gPaSpoVu', 'admin', '2025-05-10 12:52:56', 'active'),
(100002, 'Yana Nawali', 'yananawali@email.com', '$2y$10$obK8VpBZ0SDiQJsF5.bFp.H8/dc89d1w5px7fHop.qklmfcFxeG/e', 'traveler', '2025-05-12 07:53:19', 'active'),
(100004, 'Grasya Nadala', 'grasyanadala@email.com', '$2y$10$.GL5r4bF/FsRbs.MTkWsuu6s6g6tWGGWbuXdO0050IHEjojLkj3kW', 'traveler', '2025-05-12 07:56:31', 'active'),
(100005, 'Gared Lidamag', 'garedlidamag@email.com', '$2y$10$ONq/.f/xqBBZuXLJ5i4cm.m6IkR/Ijv6Tki1rykPYbgUMrSb.m/z6', 'traveler', '2025-05-12 08:00:26', 'active'),
(100006, 'Minjas Deznafern', 'minjasdeznafern@email.com', '$2y$10$r5QCw1oZLzy04Vzi3.N.HeZQLypwwgyhS21IqnGSTYxITs638hY4S', 'traveler', '2025-05-12 12:25:03', 'active'),
(100007, 'Xarone Anit', 'xaroneanit@email.com', '$2y$10$UuPoaaUztJCMkK.rMHjbLOFhUkP33xq5xG1JjnGMghhSA5uisXKJm', 'traveler', '2025-05-12 13:32:23', 'active');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `package_id` (`package_id`);

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `destinations`
--
ALTER TABLE `destinations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_operator` (`operator_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `destinations`
--
ALTER TABLE `destinations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=100008;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`package_id`) REFERENCES `destinations` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `destinations`
--
ALTER TABLE `destinations`
  ADD CONSTRAINT `fk_operator` FOREIGN KEY (`operator_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
