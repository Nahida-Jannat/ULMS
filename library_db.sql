-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Nov 11, 2025 at 09:07 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `library_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `book_id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `author` varchar(200) NOT NULL,
  `genre` varchar(100) DEFAULT NULL,
  `isbn` varchar(20) DEFAULT NULL,
  `thumbnail` varchar(255) DEFAULT NULL,
  `source` enum('api','manual') NOT NULL,
  `reserved` enum('Yes','No') DEFAULT 'No',
  `reserved_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`book_id`, `title`, `author`, `genre`, `isbn`, `thumbnail`, `source`, `reserved`, `reserved_by`) VALUES
(1, 'Chemistry', 'Theodore L. Brown', 'Science', '9780130845108', NULL, 'api', 'Yes', NULL),
(2, 'Self-knowledge', 'John Mason', 'Science', '79859847538563', NULL, 'api', 'Yes', NULL),
(3, 'Foundation', 'Isaac Asimov', 'Science', '034531798X', NULL, 'api', 'No', NULL),
(4, 'The Last Man', 'Mary Shelley', 'Science', '9798653604294', NULL, 'api', 'No', NULL),
(5, 'Discours de la méthode', 'René Descartes', 'Science', '9782040284053', NULL, 'api', 'No', NULL),
(6, 'The Time Machine', 'H. G. Wells', 'Science', '9788175992955', NULL, 'api', 'No', NULL),
(7, 'Du contrat social', 'Jean-Jacques Rousseau', 'Science', '0192835971', NULL, 'api', 'Yes', NULL),
(8, 'Dune', 'Frank Herbert', 'Science', '9780450000898', NULL, 'api', 'No', NULL),
(9, 'The Invisible Man', 'H. G. Wells', 'Science', '9781699308035', NULL, 'api', 'No', NULL),
(10, 'Flatland', 'Edwin Abbott Abbott', 'Science', '6051710302', NULL, 'api', 'No', NULL),
(11, 'The Martian Chronicles', 'Ray Bradbury', 'Science', '9788445071977', NULL, 'api', 'No', NULL),
(12, 'The First Men in the Moon', 'H. G. Wells', 'Science', '9798604851227', NULL, 'api', 'No', NULL),
(13, 'Fahrenheit 451', 'Ray Bradbury', 'Science', '0007181701', NULL, 'api', 'No', NULL),
(14, 'The Lost World', 'Arthur Conan Doyle', 'Science', '9781726681889', NULL, 'api', 'No', NULL),
(15, 'The War of the Worlds', 'H. G. Wells', 'Science', '9780141441030', NULL, 'api', 'No', NULL),
(16, 'The Awakening', 'Kate Chopin', 'Science', '0341994928', NULL, 'api', 'No', NULL),
(17, 'La Poetica', 'Aristotle', 'Science', '0879756713', NULL, 'api', 'No', NULL),
(18, 'The Island of Dr. Moreau', 'H. G. Wells', 'Science', '9798737831981', NULL, 'api', 'No', NULL),
(19, 'Frankenstein or The Modern Prometheus', 'Mary Shelley', 'Science', '9781926444314', NULL, 'api', 'No', NULL),
(21, 'A Connecticut Yankee in King Arthur\'s Court', 'Mark Twain', 'Science', '9781731703637', NULL, 'api', 'No', NULL),
(22, 'Ender\'s Game', 'Orson Scott Card', 'Science', '9788496581579', NULL, 'api', 'No', NULL),
(23, 'Autobiography', 'Benjamin Franklin', 'Science', '1539170322', NULL, 'api', 'No', NULL),
(24, 'Le Tour du Monde en Quatre-Vingts Jours', 'Jules Verne', 'Science', '9782892425062', NULL, 'api', 'No', NULL),
(26, 'Vingt mille lieues sous les mers', 'Jules Verne', 'Science', '0900948183', NULL, 'api', 'No', NULL),
(27, 'The Scarlet Letter', 'Nathaniel Hawthorne', 'Science', '0344330575', NULL, 'api', 'No', NULL),
(28, 'Nicomachean Ethics', 'Aristotle', 'Science', '0192505467', NULL, 'api', 'No', NULL),
(29, '2001', 'Arthur C. Clarke', 'Science', '9780090015306', NULL, 'api', 'Yes', NULL),
(30, 'Principles of Anatomy and Physiology', 'Gerard J. Tortora', 'Science', '9780673993571', NULL, 'api', 'No', NULL),
(31, 'On The Beach', 'Nevil Shute', 'Science', '9789022509678', NULL, 'api', 'No', NULL),
(32, 'The Hitch Hiker\'s Guide to the Galaxy', 'Douglas Adams', 'Science', '9780575074842', NULL, 'api', 'No', NULL),
(33, 'A Princess of Mars', 'Edgar Rice Burroughs', 'Science', '9781513272078', NULL, 'api', 'No', NULL),
(34, 'De rerum natura', 'Titus Lucretius Carus', 'Science', '9781019162002', NULL, 'api', 'No', NULL),
(35, 'A Brief History of Time', 'Stephen Hawking', 'Science', '8377857677', NULL, 'api', 'Yes', NULL),
(36, 'The Art of War', 'Sun Tzu', 'Science', '9386538210', NULL, 'api', 'No', NULL),
(37, 'Мы', 'Евгений Иванович Замятин', 'Science', '9798551719502', NULL, 'api', 'No', NULL),
(38, 'Brave New World', 'Aldous Huxley', 'Science', '1521308578', NULL, 'api', 'No', NULL),
(39, 'Anthem', 'Ayn Rand', 'Science', '9781503250703', NULL, 'api', 'No', NULL),
(40, 'The Subtle Knife', 'Philip Pullman', 'Science', '0590542435', NULL, 'api', 'No', NULL),
(41, 'The food of the gods and how it came to earth', 'H. G. Wells', 'Science', '198774246X', NULL, 'api', 'No', NULL),
(42, 'The Handmaid\'s Tale', 'Margaret Atwood', 'Science', '8982735526', NULL, 'api', 'No', NULL),
(43, 'Πολιτικά (Politiká)', 'Aristotle', 'Science', '0198145152', NULL, 'api', 'No', NULL),
(44, 'De la terre à la lune', 'Jules Verne', 'Science', '9781798833407', NULL, 'api', 'No', NULL),
(45, 'A Wrinkle in Time', 'Madeleine L\'Engle', 'Science', '9755093656', NULL, 'api', 'No', NULL),
(46, 'Northern Lights', 'Philip Pullman', 'Science', '9789867399946', NULL, 'api', 'No', NULL),
(47, 'Voyage au Centre de la Terre', 'Jules Verne', 'Science', '1853262870', NULL, 'api', 'No', NULL),
(48, 'A Clockwork Orange', 'Anthony Burgess', 'Science', '8576571366', NULL, 'api', 'No', NULL),
(49, 'When the Sleeper Awakes', 'H. G. Wells', 'Science', '9781086008715', NULL, 'api', 'No', NULL),
(50, 'Mein Kampf', 'Adolf Hitler', 'Science', '1593640064', NULL, 'api', 'No', NULL),
(51, 'Vom Kriege', 'Carl von Clausewitz', 'Science', '9781595472649', NULL, 'api', 'No', NULL),
(52, 'Nineteen Eighty-Four', 'George Orwell', 'Science', '9780451516756', NULL, 'api', 'No', NULL),
(53, 'The Giver', 'Lois Lowry', 'Science', '9780358354734', NULL, 'api', 'No', NULL),
(54, 'The Kybalion', 'Three Initiates', 'Science', '1470162083', NULL, 'api', 'No', NULL),
(55, 'It Can\'t Happen Here', 'Sinclair Lewis', 'Science', '0451159365', NULL, 'api', 'No', NULL),
(56, 'Common Sense', 'Thomas Paine', 'Science', '0786596708', NULL, 'api', 'No', NULL),
(57, 'Het Achterhuis', 'Anne Frank', 'Science', '9780606001038', NULL, 'api', 'No', NULL),
(58, 'Das Kapital', 'Karl Marx', 'Science', '9781605200101', NULL, 'api', 'No', NULL),
(59, 'Animal Farm', 'George Orwell', 'Science', '838661188X', NULL, 'api', 'No', NULL),
(60, 'A Doll\'s House', 'Henrik Ibsen', 'Science', '1978220979', NULL, 'api', 'No', NULL),
(61, 'The Prince', 'Niccolò Machiavelli', 'Science', '1542047544', NULL, 'api', 'No', NULL),
(62, 'The Silver Chair', 'C.S. Lewis', 'Science', '0006716687', NULL, 'api', 'No', NULL),
(63, 'The Last Battle', 'C.S. Lewis', 'Science', '9780007325108', NULL, 'api', 'No', NULL),
(64, 'The Hunger Games', 'Suzanne Collins', 'Science', '9781407192239', NULL, 'api', 'No', NULL),
(65, 'A Vindication of Rights of Woman', 'Mary Wollstonecraft', 'Science', '1497684218', NULL, 'api', 'No', NULL),
(66, 'Charlie and the Great Glass Elevator', 'Roald Dahl', 'Science', '9781536406528', NULL, 'api', 'No', NULL),
(67, 'Goodnight Moon', 'Margaret Wise Brown', 'Science', '9780941078290', NULL, 'api', 'No', NULL),
(68, 'Carrie', 'Stephen King', 'Science', '9788497595698', NULL, 'api', 'No', NULL),
(69, 'Twilight', 'Stephenie Meyer', 'Science', '9781907410482', NULL, 'api', 'No', NULL),
(70, 'The Lost Symbol', 'Dan Brown', 'Science', '9789955132431', NULL, 'api', 'No', NULL),
(71, 'Moralia', 'Plutarch', 'Science', '9782251004990', NULL, 'api', 'No', NULL),
(72, 'Manifest der Kommunistischen Partei', 'Karl Marx', 'Science', '9781774761403', NULL, 'api', 'No', NULL),
(73, 'The Magician\'s Nephew', 'C.S. Lewis', 'Science', '0812424336', NULL, 'api', 'No', NULL),
(74, 'πολιτεία', 'Πλάτων', 'Science', '8470821768', NULL, 'api', 'No', NULL),
(75, 'Poirot investigates', 'Agatha Christie', 'Science', '1611732328', NULL, 'api', 'No', NULL),
(76, 'La Peste', 'Albert Camus', 'Science', '9781439513132', NULL, 'api', 'No', NULL),
(77, 'Genji monogatari', 'Murasaki Shikibu', 'Science', '4062521016', NULL, 'api', 'No', NULL),
(78, 'The Lion, the Witch and the Wardrobe', 'C.S. Lewis', 'Science', '0060086610', NULL, 'api', 'No', NULL),
(79, 'Life on the Mississippi', 'Mark Twain', 'Science', '9781406721829', NULL, 'api', 'No', NULL),
(80, 'Der Zauberberg', 'Thomas Mann', 'Science', '9788435045957', NULL, 'api', 'No', NULL),
(81, 'History', 'Herodotus', 'Science', '9788804363903', NULL, 'api', 'No', NULL),
(82, 'On the origin of species by means of natural selection', 'Charles Darwin', 'Science', '9782707111692', NULL, 'api', 'No', NULL),
(83, 'The Murder of Roger Ackroyd', 'Agatha Christie', 'Science', '3442000122', NULL, 'api', 'No', NULL),
(84, 'The Strange Case of Dr. Jekyll and Mr. Hyde', 'Robert Louis Stevenson', 'Science', '8440725655', NULL, 'api', 'No', NULL),
(85, 'Tender is the Night', 'F. Scott Fitzgerald', 'Science', '1795066369', NULL, 'api', 'No', NULL),
(86, 'Бѣсы', 'Фёдор Михайлович Достоевский', 'Science', '3942963264', NULL, 'api', 'No', NULL),
(87, 'The Secret Agent', 'Joseph Conrad', 'Science', '8809025725', NULL, 'api', 'No', NULL),
(88, 'A Room of One\'s Own', 'Virginia Woolf', 'Science', '9781551114286', NULL, 'api', 'No', NULL),
(89, 'Смерть Ивана Ильича', 'Лев Толстой', 'Science', '1086794419', NULL, 'api', 'No', NULL),
(90, 'Narrative of the life of Frederick Douglass', 'Frederick Douglass', 'Science', '1612930433', NULL, 'api', 'No', NULL),
(91, 'Of Human Bondage', 'William Somerset Maugham', 'Science', '9781983676574', NULL, 'api', 'No', NULL),
(92, 'Phantastes', 'George MacDonald', 'Science', '9781499151855', NULL, 'api', 'No', NULL),
(93, 'Faerie queene', 'Edmund Spenser', 'Science', '9780198319191', NULL, 'api', 'No', NULL),
(94, 'The Iron Heel', 'Jack London', 'Science', '197633201X', NULL, 'api', 'No', NULL),
(95, 'Dracula', 'Bram Stoker', 'Science', '0679994351', NULL, 'api', 'No', NULL),
(96, 'The Two Towers', 'J.R.R. Tolkien', 'Science', '0345248287', NULL, 'api', 'No', NULL),
(97, 'Harry Potter and the Prisoner of Azkaban', 'J. K. Rowling', 'Science', '8417879692', NULL, 'api', 'No', NULL),
(98, 'On Liberty', 'John Stuart Mill', 'Science', '9798511911380', NULL, 'api', 'No', NULL),
(99, 'Mrs. Dalloway', 'Virginia Woolf', 'Science', '9798643748434', NULL, 'api', 'No', NULL),
(100, 'Мы', 'Евгений Иванович Замятин', 'Science', '9781986663489', NULL, 'api', 'No', NULL),
(101, 'Das Kapital', 'Karl Marx', 'Science', '9780394607269', NULL, 'api', 'No', NULL),
(102, 'On the origin of species by means of natural selection', 'Charles Darwin', 'Science', '0804461058', NULL, 'api', 'No', NULL),
(103, 'The Murder of Roger Ackroyd', 'Agatha Christie', 'Science', '9781579126278', NULL, 'api', 'No', NULL),
(104, 'The Secret Agent', 'Joseph Conrad', 'Science', '9780141199559', NULL, 'api', 'No', NULL),
(234, 'test', 'test', 'test', 'rfyi74564567', NULL, 'api', 'No', NULL),
(356357, 'The Last Man', 'Mary Shelley', 'Horror', '1478319526', NULL, 'api', 'No', NULL),
(356358, 'The Time Machine', 'H. G. Wells', 'Horror', '9798516328886', NULL, 'api', 'No', NULL),
(356359, 'The Invisible Man', 'H. G. Wells', 'Horror', '0451528522', NULL, 'api', 'No', NULL),
(356360, 'The Awakening', 'Kate Chopin', 'Horror', '9798716719958', NULL, 'api', 'No', NULL),
(356361, 'La Poetica', 'Aristotle', 'Horror', '1585101877', NULL, 'api', 'No', NULL),
(356362, 'Autobiography', 'Benjamin Franklin', 'Horror', '1566193931', NULL, 'api', 'No', NULL),
(356363, 'A Princess of Mars', 'Edgar Rice Burroughs', 'Horror', '1517708710', NULL, 'api', 'No', NULL),
(356364, 'The Art of War', 'Sun Tzu', 'Horror', '9781772266986', NULL, 'api', 'No', NULL),
(356365, 'Мы', 'Евгений Иванович Замятин', 'Horror', '9786257120432', NULL, 'api', 'No', NULL),
(356366, 'The food of the gods and how it came to earth', 'H. G. Wells', 'Horror', '9781717311184', NULL, 'api', 'No', NULL),
(356367, 'Πολιτικά (Politiká)', 'Aristotle', 'Horror', '0872203883', NULL, 'api', 'No', NULL),
(356368, 'When the Sleeper Awakes', 'H. G. Wells', 'Horror', '9783423131285', NULL, 'api', 'No', NULL),
(356369, 'Nineteen Eighty-Four', 'George Orwell', 'Horror', '8418915099', NULL, 'api', 'No', NULL),
(356370, 'The Prince', 'Niccolò Machiavelli', 'Horror', '1976056187', NULL, 'api', 'No', NULL),
(356371, 'A Vindication of Rights of Woman', 'Mary Wollstonecraft', 'Horror', '1603849386', NULL, 'api', 'No', NULL),
(356372, 'Carrie', 'Stephen King', 'Horror', '0385394284', NULL, 'api', 'No', NULL),
(356373, 'A Room of One\'s Own', 'Virginia Woolf', 'Horror', '9781448139040', NULL, 'api', 'No', NULL),
(356374, 'Смерть Ивана Ильича', 'Лев Толстой', 'Horror', '9798762939614', NULL, 'api', 'No', NULL),
(356375, 'Бѣсы', 'Фёдор Михайлович Достоевский', 'Horror', '9780394604411', NULL, 'api', 'No', NULL),
(356376, 'Mrs. Dalloway', 'Virginia Woolf', 'Horror', '9798729946303', NULL, 'api', 'No', NULL),
(356377, 'The Jewel of Seven Stars', 'Bram Stoker', 'Horror', '9781727570458', NULL, 'api', 'No', NULL),
(356378, 'The King in Yellow', 'Robert W. Chambers', 'Horror', '9781708367800', NULL, 'api', 'No', NULL),
(356379, 'Carmilla', 'Joseph Sheridan Le Fanu', 'Horror', '9798534513882', NULL, 'api', 'No', NULL),
(356380, 'The Great God Pan', 'Arthur Machen', 'Horror', '9781406545531', NULL, 'api', 'No', NULL),
(356381, 'Northanger Abbey', 'Jane Austen', 'Horror', '9781520673639', NULL, 'api', 'No', NULL),
(356382, 'The Power of Darkness', 'Edith Nesbit', 'Horror', '9798711938309', NULL, 'api', 'No', NULL),
(356383, 'The Picture of Dorian Gray', 'Oscar Wilde', 'Horror', '151942941X', NULL, 'api', 'No', NULL),
(356384, 'yfuy', 'Stephen King', 'Horror', '9780881037203', NULL, 'api', 'No', NULL),
(356385, 'Le fantôme de l\'opéra', 'Gaston Leroux', 'Horror', '9788489691872', NULL, 'api', 'No', NULL),
(356386, 'Misery', 'Stephen King', 'Horror', '1101138122', NULL, 'api', 'No', NULL),
(356387, 'The Castle of Otranto', 'Horace Walpole', 'Horror', '0342804936', NULL, 'api', 'No', NULL),
(356388, 'A Christmas Carol', 'Charles Dickens', 'Horror', '9781540826879', NULL, 'api', 'No', NULL),
(356389, 'Daisy Miller', 'Henry James', 'Horror', '1366581911', NULL, 'api', 'No', NULL),
(356390, 'The Hobbit', 'J.R.R. Tolkien', 'Horror', '0007497911', NULL, 'api', 'No', NULL),
(356391, 'Mary Shelley\'s Frankenstein; or, the Modern Prometheus (1818 text)', 'Mary Shelley', 'Horror', '9780192833662', NULL, 'api', 'No', NULL),
(356392, 'The Shining', 'Stephen King', 'Horror', '0340797665', NULL, 'api', 'No', NULL),
(356393, 'Brood of the Witch-Queen', 'Sax Rohmer', 'Horror', '9798710992326', NULL, 'api', 'No', NULL),
(356394, 'It', 'Stephen King', 'Horror', '9784167148102', NULL, 'api', 'No', NULL),
(356395, 'The Gunslinger', 'Stephen King', 'Horror', '9788573026030', NULL, 'api', 'No', NULL),
(356396, 'The Green Mile', 'Stephen King', 'Horror', '9780329173937', NULL, 'api', 'No', NULL),
(356397, 'Pet Sematary', 'Stephen King', 'Horror', '9782277222668', NULL, 'api', 'No', NULL),
(356398, 'The Legend of Sleepy Hollow', 'Washington Irving', 'Horror', '9798608730047', NULL, 'api', 'No', NULL),
(356399, 'New Moon', 'Stephenie Meyer', 'Horror', '1904233872', NULL, 'api', 'No', NULL),
(356400, 'The Dead Zone', 'Stephen King', 'Horror', '9788374696197', NULL, 'api', 'No', NULL),
(356401, 'Breaking Dawn', 'Stephenie Meyer', 'Horror', '9788324588978', NULL, 'api', 'No', NULL),
(356402, 'Coraline', 'Neil Gaiman', 'Horror', '3401606468', NULL, 'api', 'No', NULL),
(356403, 'The House of the Seven Gables', 'Nathaniel Hawthorne', 'Horror', '818828095X', NULL, 'api', 'No', NULL),
(356404, 'The Stand', 'Stephen King', 'Horror', '0593314018', NULL, 'api', 'No', NULL),
(356405, 'Cujo', 'Stephen King', 'Horror', '9785859760244', NULL, 'api', 'No', NULL),
(356406, 'The Amber Spyglass', 'Philip Pullman', 'Horror', '1407130242', NULL, 'api', 'No', NULL),
(356407, 'Firestarter', 'Stephen King', 'Horror', '1444780832', NULL, 'api', 'No', NULL),
(356408, 'The Lightning Thief', 'Rick Riordan', 'Horror', '9780141376882', NULL, 'api', 'No', NULL),
(356409, 'The Illustrated Man', 'Ray Bradbury', 'Horror', '9780553132472', NULL, 'api', 'No', NULL),
(356410, 'The Exorcist', 'William Peter Blatty', 'Horror', '0061007226', NULL, 'api', 'No', NULL),
(356411, 'The Drawing of the Three', 'Stephen King', 'Horror', '9024518040', NULL, 'api', 'No', NULL),
(356412, 'Interview With the Vampire', 'Anne Rice', 'Horror', '3442430534', NULL, 'api', 'No', NULL),
(356413, 'Bag of Bones', 'Stephen King', 'Horror', '9780671582340', NULL, 'api', 'No', NULL),
(356414, 'Flowers in the Attic', 'V. C. Andrews', 'Horror', '1416510885', NULL, 'api', 'No', NULL),
(356415, 'Herland', 'Charlotte Perkins Gilman', 'Horror', '9781604243703', NULL, 'api', 'No', NULL),
(356416, 'The Haunting of Hill House', 'Shirley Jackson', 'Horror', '1841190977', NULL, 'api', 'No', NULL),
(356417, 'The Amityville Horror', 'Jay Anson', 'Horror', '0553144006', NULL, 'api', 'No', NULL),
(356418, 'The Waste Lands', 'Stephen King', 'Horror', '5237014852', NULL, 'api', 'No', NULL),
(356419, 'Wizard and Glass', 'Stephen King', 'Horror', '9780452279179', NULL, 'api', 'No', NULL),
(356420, 'On Writing', 'Stephen King', 'Horror', '9780671024253', NULL, 'api', 'No', NULL),
(356421, 'The Girl Who Loved Tom Gordon', 'Stephen King', 'Horror', '9783548268415', NULL, 'api', 'No', NULL),
(356422, 'The Haunted Bookshop', 'Christopher Morley', 'Horror', '9781515437321', NULL, 'api', 'No', NULL),
(356423, 'The Haunted Hotel', 'Wilkie Collins', 'Horror', '0786272953', NULL, 'api', 'No', NULL),
(356424, 'Danse Macabre', 'Stephen King', 'Horror', '0425099350', NULL, 'api', 'No', NULL),
(356425, 'Witch Wood', 'John Buchan', 'Horror', '9781673569100', NULL, 'api', 'No', NULL),
(356426, 'Needful Things', 'Stephen King', 'Horror', '9780140839531', NULL, 'api', 'No', NULL),
(356427, 'The Magician', 'William Somerset Maugham', 'Horror', '9798506134565', NULL, 'api', 'No', NULL),
(356428, 'Night Shift', 'Stephen King', 'Horror', '9780881037197', NULL, 'api', 'No', NULL),
(356429, 'The Eyes of the Dragon', 'Stephen King', 'Horror', '179710750X', NULL, 'api', 'No', NULL),
(356430, 'The Road', 'Cormac McCarthy', 'Horror', '1501260421', NULL, 'api', 'No', NULL),
(356431, 'Four Past Midnight', 'Stephen King', 'Horror', '3453438418', NULL, 'api', 'No', NULL),
(356432, 'Tales of Terror and Mystery', 'Arthur Conan Doyle', 'Horror', '1909676446', NULL, 'api', 'No', NULL),
(356433, 'The Damned', 'Algernon Blackwood', 'Horror', '1790599407', NULL, 'api', 'No', NULL),
(356434, 'Christine', 'Stephen King', 'Horror', '9782277218661', NULL, 'api', 'No', NULL),
(356435, 'The Willows', 'Algernon Blackwood', 'Horror', '9798476325055', NULL, 'api', 'No', NULL),
(356436, 'Cell', 'Stephen King', 'Horror', '9781846177682', NULL, 'api', 'No', NULL),
(356437, 'Stuart Little', 'E. B. White', 'Horror', '9780439662208', NULL, 'api', 'No', NULL),
(356438, 'The Day of the Triffids', 'John Wyndham', 'Horror', '0345328175', NULL, 'api', 'No', NULL),
(356439, 'The Dark Half', 'Stephen King', 'Horror', '9788497936743', NULL, 'api', 'No', NULL),
(356440, 'Thinner', 'Stephen King', 'Horror', '2290307734', NULL, 'api', 'No', NULL),
(356441, 'The Empty House and Other Ghost Stories', 'Algernon Blackwood', 'Horror', '9781839648793', NULL, 'api', 'No', NULL),
(356442, 'Everything\'s Eventual. 14 Dark Tales', 'Stephen King', 'Horror', '9780340874714', NULL, 'api', 'No', NULL),
(356443, 'Petals on the Wind', 'V. C. Andrews', 'Horror', '9788370755225', NULL, 'api', 'No', NULL),
(356444, 'Something Wicked This Way Comes', 'Ray Bradbury', 'Horror', '0965020452', NULL, 'api', 'No', NULL),
(356445, 'Different Seasons', 'Stephen King', 'Horror', '9782277224341', NULL, 'api', 'No', NULL),
(356446, 'Cabin Fever', 'Jeff Kinney', 'Horror', '9787540553654', NULL, 'api', 'No', NULL),
(356447, 'Red Dragon', 'Thomas Harris', 'Horror', '9780307344687', NULL, 'api', 'No', NULL),
(356448, 'Poems', 'Percy Bysshe Shelley', 'Horror', '9781419157264', NULL, 'api', 'No', NULL),
(356449, 'Jaws', 'Peter Benchley', 'Horror', '055308500X', NULL, 'api', 'No', NULL),
(356450, 'The Tommyknockers', 'Stephen King', 'Horror', '9781501144288', NULL, 'api', 'No', NULL),
(356451, 'The Silence of the Lambs', 'Thomas Harris', 'Horror', '2266120158', NULL, 'api', 'No', NULL),
(356452, 'Insomnia', 'Stephen King', 'Horror', '0450608484', NULL, 'api', 'No', NULL),
(356453, 'Gerald\'s Game', 'Stephen King', 'Horror', '3548253881', NULL, 'api', 'No', NULL),
(356454, 'Rose Madder', 'Stephen King', 'Horror', '9782226084637', NULL, 'api', 'No', NULL),
(356455, 'Desperation', 'Stephen King', 'Horror', '1101192747', NULL, 'api', 'No', NULL),
(356456, 'From a Buick 8', 'Stephen King', 'Horror', '9781400003143', NULL, 'api', 'No', NULL),
(356457, 'Dreamcatcher', 'Stephen King', 'Horror', '8497598865', NULL, 'api', 'No', NULL),
(356458, 'Hearts in Atlantis', 'Stephen King', 'Horror', '9785170021369', NULL, 'api', 'No', NULL),
(356459, 'The Mysteries of Udolpho', 'Ann Radcliffe', 'Horror', '9781540664082', NULL, 'api', 'No', NULL),
(356460, 'I am Legend', 'Richard Matheson', 'Horror', '1433203324', NULL, 'api', 'No', NULL),
(356461, 'Intensity', 'Dean Koontz', 'Horror', '9782744126369', NULL, 'api', 'No', NULL),
(356462, 'Lisey\'s Story', 'Stephen King', 'Horror', '5170445296', NULL, 'api', 'No', NULL),
(356463, 'The October Country', 'Ray Bradbury', 'Horror', '0586042296', NULL, 'api', 'No', NULL),
(356464, 'If There Be Thorns', 'V. C. Andrews', 'Horror', '9788484505785', NULL, 'api', 'No', NULL),
(675568, 'Ficciones', 'Jorge Luis Borges', 'Fiction', '8806173677', NULL, 'api', 'No', NULL),
(675569, 'Beloved', 'Toni Morrison', 'Fiction', '9788440656957', NULL, 'api', 'No', NULL),
(675570, 'The Time Machine', 'H. G. Wells', 'Fiction', '9781790966257', NULL, 'api', 'No', NULL),
(675571, 'The Invisible Man', 'H. G. Wells', 'Fiction', '0938819321', NULL, 'api', 'No', NULL),
(675572, 'Dune', 'Frank Herbert', 'Fiction', '0792748662', NULL, 'api', 'No', NULL),
(675573, 'The Riddle of the Sands', 'Erskine Childers', 'Fiction', '0141190450', NULL, 'api', 'No', NULL),
(675574, 'The Picture of Dorian Gray', 'Oscar Wilde', 'Fiction', '9781483949949', NULL, 'api', 'No', NULL),
(675575, 'The Scarlet Pimpernel', 'Emmuska Orczy, Baroness Orczy', 'Fiction', '080851038X', NULL, 'api', 'No', NULL),
(675576, 'The blue castle', 'Lucy Maud Montgomery', 'Fiction', '1098607058', NULL, 'api', 'No', NULL),
(675577, 'The Awakening', 'Kate Chopin', 'Fiction', '1613828012', NULL, 'api', 'No', NULL),
(675578, 'Dubliners', 'James Joyce', 'Fiction', '1584722460', NULL, 'api', 'No', NULL),
(675579, 'The War of the Worlds', 'H. G. Wells', 'Fiction', '9798451317242', NULL, 'api', 'No', NULL),
(675580, 'The Martian Chronicles', 'Ray Bradbury', 'Fiction', '9788435001236', NULL, 'api', 'No', NULL),
(675581, 'Of Human Bondage', 'William Somerset Maugham', 'Fiction', '9781513135724', NULL, 'api', 'No', NULL),
(675582, 'Lilith', 'George MacDonald', 'Fiction', '9781513277516', NULL, 'api', 'No', NULL),
(675583, 'The Sign of Four', 'Arthur Conan Doyle', 'Fiction', '9780008436889', NULL, 'api', 'No', NULL),
(675584, 'Persuasion', 'Jane Austen', 'Fiction', '9781544259345', NULL, 'api', 'No', NULL),
(675585, 'This Side of Paradise', 'F. Scott Fitzgerald', 'Fiction', '0824059549', NULL, 'api', 'No', NULL),
(675586, 'The Adventures of Tom Sawyer', 'Mark Twain', 'Fiction', '0786286407', NULL, 'api', 'No', NULL),
(675587, 'The First Men in the Moon', 'H. G. Wells', 'Fiction', '9781484126905', NULL, 'api', 'No', NULL),
(675588, 'Kim', 'Rudyard Kipling', 'Fiction', '1772267740', NULL, 'api', 'No', NULL),
(675589, 'The Lost World', 'Arthur Conan Doyle', 'Fiction', '9781420960891', NULL, 'api', 'No', NULL),
(675590, 'Wuthering Heights', 'Emily Brontë', 'Fiction', '9798592519611', NULL, 'api', 'No', NULL),
(675591, 'The Adventures of Sherlock Holmes [12 stories]', 'Arthur Conan Doyle', 'Fiction', '9781532925085', NULL, 'api', 'No', NULL),
(675592, 'The Good Earth', 'Pearl S. Buck', 'Fiction', '0835910598', NULL, 'api', 'No', NULL),
(675593, 'The Book of Dragons', 'Edith Nesbit', 'Fiction', '9798490107897', NULL, 'api', 'No', NULL),
(675594, 'Sense and Sensibility', 'Jane Austen', 'Fiction', '9781979393157', NULL, 'api', 'No', NULL),
(675595, 'Poetics', 'Aristotle', 'Fiction', '0807817635', NULL, 'api', 'No', NULL),
(675596, 'The Moonstone', 'Wilkie Collins', 'Fiction', '1015584608', NULL, 'api', 'No', NULL),
(675597, 'White Fang', 'Jack London', 'Fiction', '9798654281944', NULL, 'api', 'No', NULL),
(675598, 'A Room with a View', 'Edward Morgan Forster', 'Fiction', '1434639037', NULL, 'api', 'No', NULL),
(675599, 'Daddy-Long-Legs', 'Jean Webster', 'Fiction', '9798419258693', NULL, 'api', 'No', NULL),
(675600, 'The Alchemist, 1612', 'Ben Jonson', 'Fiction', '9781428041714', NULL, 'api', 'No', NULL),
(675601, 'The Jungle', 'Upton Sinclair', 'Fiction', '9798785471528', NULL, 'api', 'No', NULL),
(675602, 'Emma', 'Jane Austen', 'Fiction', '9781596255944', NULL, 'api', 'No', NULL),
(675603, 'The Mysterious Affair at Styles', 'Agatha Christie', 'Fiction', '1520139209', NULL, 'api', 'No', NULL),
(675604, 'The King in Yellow', 'Robert W. Chambers', 'Fiction', '1304997987', NULL, 'api', 'No', NULL),
(675605, 'Anna Karenina', 'Лев Толстой', 'Fiction', '1596091835', NULL, 'api', 'No', NULL),
(675606, 'Frankenstein or The Modern Prometheus', 'Mary Shelley', 'Fiction', '9780307745163', NULL, 'api', 'No', NULL),
(675607, 'The Mystery of the Blue Train', 'Agatha Christie', 'Fiction', '8371840799', NULL, 'api', 'No', NULL),
(675608, 'Pride and Prejudice', 'Jane Austen', 'Fiction', '9781540751065', NULL, 'api', 'No', NULL),
(675609, 'Great Expectations', 'Charles Dickens', 'Fiction', '1788883756', NULL, 'api', 'No', NULL),
(675610, 'The Red Badge of Courage', 'Stephen Crane', 'Fiction', '9798591177768', NULL, 'api', 'No', NULL),
(675611, 'Nineteen Eighty-Four', 'George Orwell', 'Fiction', '2070463699', NULL, 'api', 'No', NULL),
(675612, 'The Sea Fairies', 'L. Frank Baum', 'Fiction', '9781085904988', NULL, 'api', 'No', NULL),
(675613, 'Eva Luna', 'Isabel Allende', 'Fiction', '0886193494', NULL, 'api', 'No', NULL),
(675614, 'The Call of the Wild', 'Jack London', 'Fiction', '9781522049784', NULL, 'api', 'No', NULL),
(675615, 'A Study in Scarlet', 'Arthur Conan Doyle', 'Fiction', '1556856083', NULL, 'api', 'No', NULL),
(675616, 'The Return of Sherlock Holmes', 'Arthur Conan Doyle', 'Fiction', '9781794685673', NULL, 'api', 'No', NULL),
(675617, 'The Kite Runner', 'Khaled Hosseini', 'Fiction', '9789023418993', NULL, 'api', 'No', NULL),
(675618, 'Les Misérables', 'Victor Hugo', 'Fiction', '8879836234', NULL, 'api', 'No', NULL),
(675619, 'Women in Love', 'David Herbert Lawrence', 'Fiction', '9798514285518', NULL, 'api', 'No', NULL),
(675620, 'The Island of Dr. Moreau', 'H. G. Wells', 'Fiction', '0786401230', NULL, 'api', 'No', NULL),
(675621, 'His Last Bow [8 stories]', 'Arthur Conan Doyle', 'Fiction', '9798527651898', NULL, 'api', 'No', NULL),
(675622, 'The Age of Innocence', 'Edith Wharton', 'Fiction', '9798535732497', NULL, 'api', 'No', NULL),
(675623, 'My Ántonia', 'Willa Cather', 'Fiction', '9781593080242', NULL, 'api', 'No', NULL),
(675624, 'El túnel', 'Ernesto Sabato', 'Fiction', '9939516851', NULL, 'api', 'No', NULL),
(675625, 'Treasure Island', 'Robert Louis Stevenson', 'Fiction', '9781983544842', NULL, 'api', 'No', NULL),
(675626, 'Lolita', 'Vladimir Nabokov', 'Fiction', '0141037431', NULL, 'api', 'No', NULL),
(675627, 'The Witches', 'Roald Dahl', 'Fiction', '9780141349947', NULL, 'api', 'No', NULL),
(675628, 'Adventures of Huckleberry Finn', 'Mark Twain', 'Fiction', '3730691260', NULL, 'api', 'No', NULL),
(675629, 'Pickwick Papers', 'Charles Dickens', 'Fiction', '9780307291752', NULL, 'api', 'No', NULL),
(675630, 'Twilight', 'Stephenie Meyer', 'Fiction', '9781603963541', NULL, 'api', 'No', NULL),
(675631, 'La casa de los espíritus', 'Isabel Allende', 'Fiction', '9788493496531', NULL, 'api', 'No', NULL),
(675632, 'A Princess of Mars', 'Edgar Rice Burroughs', 'Fiction', '9780615872322', NULL, 'api', 'No', NULL),
(675633, 'Ulysses', 'James Joyce', 'Fiction', '9781728786056', NULL, 'api', 'No', NULL),
(675634, 'The Sun Also Rises', 'Ernest Hemingway', 'Fiction', '9781504068147', NULL, 'api', 'No', NULL),
(675635, 'Guess How Much I Love You', 'Sam McBratney', 'Fiction', '0744592895', NULL, 'api', 'No', NULL),
(675636, 'Matilda', 'Roald Dahl', 'Fiction', '9780435123987', NULL, 'api', 'No', NULL),
(675637, 'The Pearl', 'John Steinbeck', 'Fiction', '9780241980361', NULL, 'api', 'No', NULL),
(675638, 'El Libro de la selva / The Jungle Book', 'Rudyard Kipling', 'Fiction', '9780582035874', NULL, 'api', 'No', NULL),
(675639, 'Middlemarch', 'George Eliot', 'Fiction', '1499185529', NULL, 'api', 'No', NULL),
(675640, 'The Bell Jar', 'Sylvia Plath', 'Fiction', '9798365576001', NULL, 'api', 'No', NULL),
(675641, 'Catch-22', 'Joseph Heller', 'Fiction', '9789991258843', NULL, 'api', 'No', NULL),
(675642, 'The Tale of Peter Rabbit', 'Beatrix Potter', 'Fiction', '1626878579', NULL, 'api', 'No', NULL),
(675643, 'The Portrait Of A Lady', 'Henry James', 'Fiction', '0393966461', NULL, 'api', 'No', NULL),
(675644, 'Far From the Madding Crowd', 'Thomas Hardy', 'Fiction', '0141197803', NULL, 'api', 'No', NULL),
(675645, 'Memoirs of Sherlock Holmes [11 stories]', 'Arthur Conan Doyle', 'Fiction', '1774260581', NULL, 'api', 'No', NULL),
(675646, 'One Flew Over the Cuckoo\'s Nest', 'Ken Kesey', 'Fiction', '9780812416374', NULL, 'api', 'No', NULL),
(675647, 'Pollyanna', 'Eleanor Hodgman Porter', 'Fiction', '9781987659948', NULL, 'api', 'No', NULL),
(675648, 'Die Blechtrommel', 'Günter Grass', 'Fiction', '9789588940267', NULL, 'api', 'No', NULL),
(675649, 'Bridge to Terabithia', 'Katherine Paterson', 'Fiction', '9780440841210', NULL, 'api', 'No', NULL),
(675650, 'Harry Potter and the Deathly Hallows', 'J. K. Rowling', 'Fiction', '1408894742', NULL, 'api', 'No', NULL),
(5633757, 'A Study of History', 'Arnold J. Toynbee', 'History', '9780195050813', NULL, 'api', 'No', NULL),
(5633758, 'The Enduring Vision', 'Paul S. Boyer', 'History', '0395945119', NULL, 'api', 'No', NULL),
(5633759, 'History of the Decline and Fall of the Roman Empire Complete and Unabridged', 'Edward Gibbon', 'History', '1421915111', NULL, 'api', 'No', NULL),
(5633760, 'History of the Peloponnesian War', 'Thucydides', 'History', '9781593080914', NULL, 'api', 'No', NULL),
(5633761, 'Naturalis historia', 'Pliny the Elder', 'History', '9783760816036', NULL, 'api', 'No', NULL),
(5633762, 'David Copperfield', 'Charles Dickens', 'History', '9781292184319', NULL, 'api', 'No', NULL),
(5633763, 'The Story of Doctor Dolittle', 'Hugh Lofting', 'History', '9781707826216', NULL, 'api', 'No', NULL),
(5633764, 'Plays (36)', 'William Shakespeare', 'History', '1010581406', NULL, 'api', 'No', NULL),
(5633765, 'Hans Andersen\'s Fairy Tales', 'Hans Christian Andersen', 'History', '9780575021884', NULL, 'api', 'No', NULL),
(5633766, 'Ab urbe condita', 'Titus Livius', 'History', '3519014920', NULL, 'api', 'No', NULL),
(5633767, 'Don Quixote', 'Miguel de Cervantes Saavedra', 'History', '1587263742', NULL, 'api', 'No', NULL),
(5633768, 'King Lear', 'William Shakespeare', 'History', '0521587077', NULL, 'api', 'No', NULL),
(5633769, 'King Henry V', 'William Shakespeare', 'History', '021688215X', NULL, 'api', 'No', NULL),
(5633770, 'Le Morte d\'Arthur', 'Thomas Malory', 'History', '0872209466', NULL, 'api', 'No', NULL),
(5633771, 'Les Contes de ma mère l\'Oye', 'Charles Perrault', 'History', '0722652682', NULL, 'api', 'No', NULL),
(5633772, 'A dictionary of the English language', 'Samuel Johnson', 'History', '1929154100', NULL, 'api', 'No', NULL),
(5633773, 'Fairy Tales and Stories', 'Hans Christian Andersen', 'History', '0698400356', NULL, 'api', 'No', NULL),
(5633774, 'Doctor Faustus', 'Christopher Marlowe', 'History', '0413620506', NULL, 'api', 'No', NULL),
(5633775, 'Hamlet', 'William Shakespeare', 'History', '0908361041', NULL, 'api', 'No', NULL),
(5633776, 'The book of tea', 'Okakura Kakuzo', 'History', '9789867519856', NULL, 'api', 'No', NULL),
(5633777, 'The Second World War', 'Winston S. Churchill', 'History', '1472520874', NULL, 'api', 'No', NULL),
(5633778, 'The Silmarillion', 'J.R.R. Tolkien', 'History', '0261103679', NULL, 'api', 'No', NULL),
(5633779, 'Up from Slavery', 'Booker T. Washington', 'History', '1576460606', NULL, 'api', 'No', NULL),
(5633780, 'Mémoires', 'Giacomo Casanova', 'History', '9781625581693', NULL, 'api', 'No', NULL),
(5633781, 'Utopia', 'Thomas More', 'History', '9780679410768', NULL, 'api', 'No', NULL),
(5633782, 'The Merchant of Venice', 'William Shakespeare', 'History', '9780451502773', NULL, 'api', 'No', NULL),
(5633783, 'Bury My Heart at Wounded Knee', 'Dee Alexander Brown', 'History', '060620511X', NULL, 'api', 'No', NULL),
(5633784, 'Manifest der Kommunistischen Partei', 'Karl Marx', 'History', '9781695105393', NULL, 'api', 'No', NULL),
(5633785, 'The Taming of the Shrew', 'William Shakespeare', 'History', '9780308701625', NULL, 'api', 'No', NULL),
(5633786, 'When the Sleeper Awakes', 'H. G. Wells', 'History', '1985709570', NULL, 'api', 'No', NULL),
(5633787, 'The House of Mirth', 'Edith Wharton', 'History', '1546808264', NULL, 'api', 'No', NULL),
(5633788, 'Works [37 plays, 6 poems, sonnets]', 'William Shakespeare', 'History', '9780755459087', NULL, 'api', 'No', NULL),
(5633789, 'The Souls of Black Folk', 'W. E. B. Du Bois', 'History', '0451516745', NULL, 'api', 'No', NULL),
(5633790, 'Heart of Darkness', 'Joseph Conrad', 'History', '1789559871', NULL, 'api', 'No', NULL),
(5633791, 'Le petit prince', 'Antoine de Saint-Exupéry', 'History', '1857155246', NULL, 'api', 'No', NULL),
(5633792, 'A Portrait of the Artist as a Young Man', 'James Joyce', 'History', '9780824028084', NULL, 'api', 'No', NULL),
(5633793, 'The Turn of the Screw', 'Henry James', 'History', '9780899261591', NULL, 'api', 'No', NULL),
(5633794, 'The life of Olaudah Equiano, or Gustavus Vassa, the African', 'Olaudah Equiano', 'History', '9798540324601', NULL, 'api', 'No', NULL),
(5633795, 'Autobiography', 'Benjamin Franklin', 'History', '1480484059', NULL, 'api', 'No', NULL),
(5633796, 'Candide', 'Voltaire', 'History', '9798510887051', NULL, 'api', 'No', NULL),
(5633797, 'Study Guide', 'Supersummary', 'History', '9781699488218', NULL, 'api', 'No', NULL),
(5633798, 'Dubliners', 'James Joyce', 'History', '9781580491655', NULL, 'api', 'No', NULL),
(5633799, 'Narrative of the life of Frederick Douglass', 'Frederick Douglass', 'History', '9780788721502', NULL, 'api', 'No', NULL),
(5633800, 'Hard Times', 'Charles Dickens', 'History', '6053242241', NULL, 'api', 'No', NULL),
(5633801, 'Συμπόσιον', 'Πλάτων', 'History', '1667151932', NULL, 'api', 'No', NULL),
(5633802, 'Macbeth', 'William Shakespeare', 'History', '0679938753', NULL, 'api', 'No', NULL),
(5633803, 'The Prince', 'Niccolò Machiavelli', 'History', '148009319X', NULL, 'api', 'No', NULL),
(5633804, 'Robinson Crusoe', 'Daniel Defoe', 'History', '0192833421', NULL, 'api', 'No', NULL),
(5633805, 'Romeo and Juliet', 'William Shakespeare', 'History', '8483431807', NULL, 'api', 'No', NULL),
(5633806, 'Julius Caesar', 'William Shakespeare', 'History', '0823801136', NULL, 'api', 'No', NULL),
(5633807, 'Max Ernst', 'Max Ernst', 'History', '9783791304557', NULL, 'api', 'No', NULL),
(5633808, 'Much Ado About Nothing', 'William Shakespeare', 'History', '9798511187594', NULL, 'api', 'No', NULL),
(5633809, 'Antony and Cleopatra', 'William Shakespeare', 'History', '9780674040502', NULL, 'api', 'No', NULL),
(5633810, 'Walden', 'Henry David Thoreau', 'History', '0691169349', NULL, 'api', 'No', NULL),
(5633811, 'Homage to Catalonia', 'George Orwell', 'History', '9788804509844', NULL, 'api', 'No', NULL),
(5633812, 'Study Guide', 'SuperSummary', 'History', '9798626933246', NULL, 'api', 'No', NULL),
(5633813, 'The Book Thief', 'Markus Zusak', 'History', '6066090860', NULL, 'api', 'No', NULL),
(5633814, 'The Art of War', 'Sun Tzu', 'History', '9781877527845', NULL, 'api', 'No', NULL),
(5633815, 'The Legend of Sleepy Hollow', 'Washington Irving', 'History', '9781981938018', NULL, 'api', 'No', NULL),
(5633816, 'πολιτεία', 'Πλάτων', 'History', '9789753853583', NULL, 'api', 'No', NULL),
(5633817, 'Summa Theologica', 'Thomas Aquinas', 'History', '052102921X', NULL, 'api', 'No', NULL),
(5633818, 'As You Like It', 'William Shakespeare', 'History', '9780451526786', NULL, 'api', 'No', NULL),
(5633819, 'Tess of the d\'Urbervilles', 'Thomas Hardy', 'History', '9798420685433', NULL, 'api', 'No', NULL),
(5633820, 'An autobiography', 'Mohandas Karamchand Gandhi', 'History', '1463694873', NULL, 'api', 'No', NULL),
(5633821, 'Twelfth Night', 'William Shakespeare', 'History', '9780521227520', NULL, 'api', 'No', NULL),
(5633822, 'Uncle Tom\'s Cabin', 'Harriet Beecher Stowe', 'History', '1545595828', NULL, 'api', 'No', NULL),
(5633823, 'Εὐθύφρων / Κρίτων / Φαίδων / Ἀπολογία Σωκράτους', 'Πλάτων', 'History', '1481266004', NULL, 'api', 'No', NULL),
(5633824, 'A Midsummer Night\'s Dream', 'William Shakespeare', 'History', '061387286X', NULL, 'api', 'No', NULL),
(5633825, 'Beloved', 'Toni Morrison', 'History', '0783802625', NULL, 'api', 'No', NULL),
(5633826, 'King Solomon\'s Mines', 'H. Rider Haggard', 'History', '9788420734170', NULL, 'api', 'No', NULL),
(5633827, 'Othello', 'William Shakespeare', 'History', '9781403946331', NULL, 'api', 'No', NULL),
(5633828, 'The Travels of Marco Polo', 'Marco Polo', 'History', '8882460649', NULL, 'api', 'No', NULL),
(5633829, 'Gone With the Wind', 'Margaret Mitchell', 'History', '9354622623', NULL, 'api', 'No', NULL),
(5633830, 'A Vindication of Rights of Woman', 'Mary Wollstonecraft', 'History', '0760754942', NULL, 'api', 'No', NULL),
(5633831, 'Bible', 'Bible', 'History', '0340863188', NULL, 'api', 'No', NULL),
(5633832, 'Доктор Живаго', 'Boris Leonidovich Pasternak', 'History', '9781405882422', NULL, 'api', 'No', NULL),
(5633833, 'The Story of Philosophy', 'Will Durant', 'History', '1950330923', NULL, 'api', 'No', NULL),
(5633834, 'Gulliver\'s Travels', 'Jonathan Swift', 'History', '9780371886946', NULL, 'api', 'No', NULL),
(5633835, 'Metamorphoses', 'Ovid', 'History', '9780451527936', NULL, 'api', 'No', NULL),
(5633836, 'A Tale of Two Cities', 'Charles Dickens', 'History', '9798596353310', NULL, 'api', 'No', NULL),
(5633837, 'Il nome della rosa', 'Umberto Eco', 'History', '9783446253803', NULL, 'api', 'No', NULL),
(5633838, 'The Hunchback of Notre Dame', 'Victor Hugo', 'History', '014026020X', NULL, 'api', 'No', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `reservations`
--

CREATE TABLE `reservations` (
  `reservation_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `reservation_date` date NOT NULL,
  `due_date` date NOT NULL,
  `returned` enum('Yes','No') DEFAULT 'No'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reservations`
--

INSERT INTO `reservations` (`reservation_id`, `book_id`, `user_id`, `reservation_date`, `due_date`, `returned`) VALUES
(24, 1, 111, '2025-11-25', '2025-12-1', 'No'),
(25, 2, 111, '2025-12-18', '2025-12-25', 'No'),
(26, 7, 112, '2025-12-18', '2025-12-25', 'No'),
(28, 5, 112, '2025-12-19', '2025-12-26', 'Yes'),
(32, 29, 112, '2025-12-26', '2026-01-02', 'No'),
(33, 35, 111, '2026-01-07', '2026-01-14', 'No');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','staff','student') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `name`, `email`, `password`, `role`) VALUES
(1, 'Nahida Jannat', 'nahida@gmail.com', '123', 'admin'),
(2, 'Mayouree', 'mayouree@gmail.com', '123', 'staff'),
(111, 'Asif', 'Asif@gmail.com', '111', 'student'),
(112, 'Sagor', 'sagor@gmail.com', '112', 'student');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`book_id`),
  ADD UNIQUE KEY `isbn` (`isbn`);

--
-- Indexes for table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`reservation_id`),
  ADD KEY `book_id` (`book_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `book_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5633839;

--
-- AUTO_INCREMENT for table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `reservation_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `reservations`
--
ALTER TABLE `reservations`
  ADD CONSTRAINT `reservations_ibfk_1` FOREIGN KEY (`book_id`) REFERENCES `books` (`book_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
