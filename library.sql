-- phpMyAdmin SQL Dump
-- version 4.9.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Nov 17, 2020 at 03:32 PM
-- Server version: 5.7.26
-- PHP Version: 7.4.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `library`
--

-- --------------------------------------------------------

--
-- Table structure for table `author`
--

CREATE TABLE `author` (
  `id` int(11) NOT NULL,
  `name` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `author`
--

INSERT INTO `author` (`id`, `name`) VALUES
(1, 'J. K. Rowling'),
(2, 'George R. R. Martin'),
(3, 'Jim Collins'),
(4, 'John le Carre'),
(5, 'Suzanne Collins'),
(6, 'Wei Meng Lee'),
(7, 'Tom Asacker'),
(8, 'Bruce Springsteen'),
(9, 'Jorge Vega'),
(10, 'Julian Paez'),
(11, 'Natalia Perez'),
(12, 'Daniel Camargo'),
(13, 'Roberto Garcia'),
(14, 'Juan Castro'),
(15, 'Andres Mendoza'),
(16, 'Juan Gómez-Jurado'),
(17, 'Sandra Barneda'),
(18, 'Maria Lazaro Avila'),
(19, 'Francisco Garcia Vena');

-- --------------------------------------------------------

--
-- Table structure for table `book`
--

CREATE TABLE `book` (
  `id` int(11) NOT NULL,
  `isbn` char(13) NOT NULL,
  `title` varchar(80) NOT NULL,
  `author` int(11) NOT NULL,
  `category` int(11) NOT NULL,
  `availability` tinyint(1) UNSIGNED NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `book`
--

INSERT INTO `book` (`id`, `isbn`, `title`, `author`, `category`, `availability`) VALUES
(1, '0000545010225', 'Harry Potter y las Reliquias de la Muerte', 1, 1, 0),
(2, '0000553103547', 'Juego de Tronos', 2, 1, 0),
(3, '0000553106635', 'Una tormenta de espadas', 2, 1, 1),
(4, '0000553108034', 'Choque de Reyes', 2, 1, 1),
(5, '0000553801503', 'Un festín para los cuervos', 2, 1, 1),
(6, '0000747532699', 'Harry Potter y la Piedra Filosofal', 1, 1, 1),
(7, '0000747538492', 'Harry Potter y la cámara de los secretos', 1, 1, 1),
(8, '0000747542155', 'Harry Potter y el prisionero de Azkaban', 1, 1, 1),
(9, '0000747546240', 'Harry Potter y el cáliz de fuego', 1, 1, 1),
(10, '0000747551006', 'Harry Potter y la Orden del Fénix', 1, 1, 1),
(11, '0000747581088', 'Harry Potter y el Príncipe Mestizo', 1, 1, 1),
(12, '9780066620992', 'Bueno a genial', 3, 2, 1),
(13, '9780241257555', 'El túnel de las palomas', 4, 2, 1),
(14, '9780439023511', 'Mockingjay', 5, 1, 1),
(15, '9780439023528', 'Los juegos del hambre', 5, 1, 1),
(16, '9780545227247', 'En llamas', 5, 1, 1),
(17, '9780553801477', 'Una danza con Dragones', 2, 1, 1),
(18, '9780590353427', 'Comienzo del desarrollo de aplicaciones de Android', 6, 3, 1),
(19, '9780967752808', 'Sandbox Wisdom', 7, 2, 1),
(20, '9781501141515', 'Nacido para correr', 8, 2, 1),
(21, '9788183331630', 'Empezemos con C', 9, 3, 1),
(22, '9789350776667', 'Gráficos por computadora y realidad virtual', 10, 3, 1),
(23, '9789350776773', 'Microcontrolador y Sistemas Embebidos', 11, 3, 1),
(24, '9789350777077', 'Sistemas avanzados de gestión de bases de datos', 12, 3, 1),
(25, '9789350777121', 'Sistemas operativos', 13, 3, 1),
(26, '9789351194545', 'Tecnologías de código abierto', 14, 3, 1),
(27, '9789381626719', 'Quédense hambrientos quédense tontos', 15, 2, 1),
(28, '9788466668545', 'Rey Blanco', 16, 1, 1),
(29, '9788408235521', 'Un Oceano Para Llegar a Ti', 17, 1, 1),
(30, '9788441542303', 'Redes Sociales y Menores', 18, 3, 1),
(31, '9788413440385', 'Irrompible', 19, 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `book_issue_log`
--

CREATE TABLE `book_issue_log` (
  `issue_id` int(11) NOT NULL,
  `member` varchar(20) NOT NULL,
  `book_id` int(11) NOT NULL,
  `due_date` date NOT NULL,
  `last_reminded` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `book_issue_log`
--

INSERT INTO `book_issue_log` (`issue_id`, `member`, `book_id`, `due_date`, `last_reminded`) VALUES
(2, 'Usuario', 1, '2020-11-30', NULL),
(3, 'Usuario', 2, '2020-11-12', '2020-11-12');

--
-- Triggers `book_issue_log`
--
DELIMITER $$
CREATE TRIGGER `issue_book` BEFORE INSERT ON `book_issue_log` FOR EACH ROW BEGIN
	SET NEW.due_date = DATE_ADD(CURRENT_DATE, INTERVAL 7 DAY);
    UPDATE book SET availability = 0 WHERE id = NEW.book_id;
    DELETE FROM pending_book_requests WHERE member = NEW.member AND book_id = NEW.book_id;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `return_book` BEFORE DELETE ON `book_issue_log` FOR EACH ROW BEGIN
    UPDATE book SET availability = 1 WHERE id = OLD.book_id;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `name` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `name`) VALUES
(1, 'Ficción'),
(2, 'No Ficción'),
(3, 'Educación');

-- --------------------------------------------------------

--
-- Table structure for table `librarian`
--

CREATE TABLE `librarian` (
  `id` int(11) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` char(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `librarian`
--

INSERT INTO `librarian` (`id`, `username`, `password`) VALUES
(1, 'admin', 'd033e22ae348aeb5660fc2140aec35850c4da997');

-- --------------------------------------------------------

--
-- Table structure for table `member`
--

CREATE TABLE `member` (
  `id` int(11) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` char(40) NOT NULL,
  `name` varchar(80) NOT NULL,
  `email` varchar(80) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `member`
--

INSERT INTO `member` (`id`, `username`, `password`, `name`, `email`) VALUES
(1, 'Usuario', 'b665e217b51994789b02b1838e730d6b93baa30f', 'Usuario', 'usuario@example.com');

--
-- Triggers `member`
--
DELIMITER $$
CREATE TRIGGER `add_member` AFTER INSERT ON `member` FOR EACH ROW DELETE FROM pending_registrations WHERE username = NEW.username
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `remove_member` AFTER DELETE ON `member` FOR EACH ROW DELETE FROM pending_book_requests WHERE member = OLD.username
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `pending_book_requests`
--

CREATE TABLE `pending_book_requests` (
  `request_id` int(11) NOT NULL,
  `member` varchar(20) NOT NULL,
  `book_id` int(11) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pending_book_requests`
--

INSERT INTO `pending_book_requests` (`request_id`, `member`, `book_id`, `time`) VALUES
(2, 'Usuario', 4, '2020-11-10 17:02:36');

-- --------------------------------------------------------

--
-- Table structure for table `pending_registrations`
--

CREATE TABLE `pending_registrations` (
  `username` varchar(20) NOT NULL,
  `password` char(40) NOT NULL,
  `name` varchar(80) NOT NULL,
  `email` varchar(80) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pending_registrations`
--

INSERT INTO `pending_registrations` (`username`, `password`, `name`, `email`, `time`) VALUES
('member', '6467baa3b187373e3931422e2a8ef22f3e447d77', 'Member M', 'member@member.com', '2020-11-12 17:16:10');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `author`
--
ALTER TABLE `author`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `book`
--
ALTER TABLE `book`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `isbn` (`isbn`),
  ADD KEY `author` (`author`),
  ADD KEY `category` (`category`);

--
-- Indexes for table `book_issue_log`
--
ALTER TABLE `book_issue_log`
  ADD PRIMARY KEY (`issue_id`),
  ADD KEY `member` (`member`),
  ADD KEY `book_id` (`book_id`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `librarian`
--
ALTER TABLE `librarian`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `member`
--
ALTER TABLE `member`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `pending_book_requests`
--
ALTER TABLE `pending_book_requests`
  ADD PRIMARY KEY (`request_id`),
  ADD KEY `member` (`member`),
  ADD KEY `book_id` (`book_id`);

--
-- Indexes for table `pending_registrations`
--
ALTER TABLE `pending_registrations`
  ADD PRIMARY KEY (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `author`
--
ALTER TABLE `author`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `book`
--
ALTER TABLE `book`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `book_issue_log`
--
ALTER TABLE `book_issue_log`
  MODIFY `issue_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `librarian`
--
ALTER TABLE `librarian`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `member`
--
ALTER TABLE `member`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `pending_book_requests`
--
ALTER TABLE `pending_book_requests`
  MODIFY `request_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `book`
--
ALTER TABLE `book`
  ADD CONSTRAINT `book_ibfk_1` FOREIGN KEY (`author`) REFERENCES `author` (`id`),
  ADD CONSTRAINT `book_ibfk_2` FOREIGN KEY (`category`) REFERENCES `category` (`id`);

--
-- Constraints for table `book_issue_log`
--
ALTER TABLE `book_issue_log`
  ADD CONSTRAINT `book_issue_log_ibfk_1` FOREIGN KEY (`member`) REFERENCES `member` (`username`),
  ADD CONSTRAINT `book_issue_log_ibfk_2` FOREIGN KEY (`book_id`) REFERENCES `book` (`id`);

--
-- Constraints for table `pending_book_requests`
--
ALTER TABLE `pending_book_requests`
  ADD CONSTRAINT `pending_book_requests_ibfk_1` FOREIGN KEY (`member`) REFERENCES `member` (`username`),
  ADD CONSTRAINT `pending_book_requests_ibfk_2` FOREIGN KEY (`book_id`) REFERENCES `book` (`id`);
