-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 05, 2025 at 04:52 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `chatbot`
--

-- --------------------------------------------------------

--
-- Table structure for table `professors`
--

CREATE TABLE `professors` (
  `professor_id` int(11) NOT NULL,
  `professor_name` varchar(255) NOT NULL,
  `bio` text DEFAULT NULL,
  `expertise` text DEFAULT NULL,
  `academic_distinctions` text DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `professors`
--

INSERT INTO `professors` (`professor_id`, `professor_name`, `bio`, `expertise`, `academic_distinctions`, `photo`) VALUES
(1, 'Dr. Joel A. Perez', 'Dr. Joel A. Perez is a seasoned professor with over 20 years of experience in Computer Science and Information Technology. He has published several research papers on algorithms and data structures.', 'Algorithms, Data Structures, Artificial Intelligence', 'Awarded Best IT Professor in 2019 by CPSU Faculty Association', 'images/1.webp'),
(2, 'Dr. Maria Santos', 'Dr. Maria Santos specializes in Software Engineering and Systems Development. She has taught at various universities and has industry experience in project management.', 'Software Engineering, Project Management, Cloud Computing', 'Received the National Excellence in Teaching Award in 2020', 'images/1.webp'),
(3, 'Prof. Mark Reyes', 'Prof. Mark Reyes is a passionate educator with a focus on Programming Languages and Web Development. He enjoys teaching students about the latest trends in tech.', 'Web Development, Programming Languages, Cloud Architecture', 'Recognized as Outstanding Teacher in 2021', 'images/1.webp'),
(4, 'Dr. Christina Tan', 'Dr. Christina Tan is a noted expert in Database Management and Information Security. She has a strong background in research and teaching at the graduate level.', 'Database Management, Information Security, Cloud Computing', 'Awarded the CPSU Faculty Award for Excellence in Research in 2021', 'images/1.webp'),
(5, 'Prof. Albert De Vera', 'Prof. Albert De Vera has extensive industry experience in Mobile App Development and is an advocate of practical learning. His interests include machine learning and mobile technologies.', 'Mobile App Development, Machine Learning, Data Science', 'Received the Tech Innovator Award in 2020', 'images/1.webp'),
(7, 'Jose Rizal', 'Sample Bio', 'Sample Expertise', 'Sample Academics', 'images/1.webp'),
(8, 'Jony Bravo', 'bio', 'exp', 'acad', 'Images/1746340680_images (1).png'),
(9, 'Eric Matti', 'Bio', 'Ex', 'Acad', 'Images/1746345099_images.png'),
(10, 'Jhax Simpson', 'bio', 'exp', 'acad', 'Images/1746347125_images.png'),
(11, 'Joshua Mirano', 'sample bio ni sir joshua mirano', 'sample expertise ni sir joshua mirano', 'sample acad distin ni sir joshua mirano', 'Images/1746412812_1.png');

-- --------------------------------------------------------

--
-- Table structure for table `schedules`
--

CREATE TABLE `schedules` (
  `id` int(11) NOT NULL,
  `professor_name` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `day` varchar(50) NOT NULL,
  `time` varchar(50) NOT NULL,
  `room` varchar(50) NOT NULL,
  `professor_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `schedules`
--

INSERT INTO `schedules` (`id`, `professor_name`, `subject`, `day`, `time`, `room`, `professor_id`) VALUES
(1, 'Dr. Joel A. Perez', 'Data Structures', 'Monday', '8:00 AM - 10:00 AM', 'Room 101', 1),
(2, 'Dr. Joel A. Perez', 'Algorithms', 'Wednesday', '10:00 AM - 12:00 PM', 'Room 102', 2),
(3, 'Prof. Maria Dela Cruz', 'Operating Systems', 'Tuesday', '1:00 PM - 3:00 PM', 'Room 103', 3),
(4, 'Prof. John Smith', 'Networking', 'Thursday', '2:00 PM - 4:00 PM', 'Room 104', 4),
(5, '', 'Data Structures', 'Monday', '9:00 AM - 11:00 AM', 'Room 201', 1),
(6, '', 'Algorithms', 'Wednesday', '2:00 PM - 4:00 PM', 'Room 202', 1),
(7, '', 'Software Engineering', 'Tuesday', '10:00 AM - 12:00 PM', 'Room 103', 2),
(8, '', 'Cloud Computing', 'Thursday', '1:00 PM - 3:00 PM', 'Room 104', 2),
(9, '', 'Web Development', 'Monday', '1:00 PM - 3:00 PM', 'Room 105', 3),
(10, '', 'Programming Languages', 'Friday', '9:00 AM - 11:00 AM', 'Room 106', 3),
(11, '', 'Database Management', 'Tuesday', '8:00 AM - 10:00 AM', 'Room 107', 4),
(12, '', 'Information Security', 'Thursday', '3:00 PM - 5:00 PM', 'Room 108', 4),
(13, '', 'Mobile App Development', 'Monday', '10:00 AM - 12:00 PM', 'Room 109', 5),
(14, '', 'Machine Learning', 'Wednesday', '1:00 PM - 3:00 PM', 'Room 110', 5),
(15, '', 'Data Structures', 'Monday', '10:00 AM-12:00PM', 'Room 101', 10),
(16, '', 'Database', 'Tuesday', '9:00 AM-12:00 PM', 'Room 303', 10),
(17, '', 'Data Structures', 'Monday', '10:00 AM - 12:00PM', 'MPLC 1', 11),
(18, '', 'Database', 'Tuesday', '9:00AM - 12:00PM', 'CSL2', 11);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','student') DEFAULT 'student',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `first_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `created_at`, `first_name`, `last_name`) VALUES
(2, 'admin_username', '$2y$10$eGxU3yA0R6g7G1vzHRoB5OQw4wB6A7zY5OxnzQGjqH0Pb3XuKFG1e', 'admin', '2025-05-04 06:57:25', NULL, NULL),
(3, 'admin', '$2y$10$tQqtvAcn1dpa.rgGvW9iE.Igb7nxLIXTQ0HlLDPig6Z5t.UfvLM3i', 'admin', '2025-05-04 06:59:57', NULL, NULL),
(5, 'stud1', '$2y$10$iG9esNLzan1DlXHyY76L2O4nPKa.ScKqORSTM4zogA.VwASKYi0NO', 'student', '2025-05-04 07:27:21', 'Jessie', 'Lacman'),
(6, 'stud2', '$2y$10$3AZKWjm8xSCfLEBjCxe6Q.wW8lVX4zhtQUhVJkWPQem/Yl5TdA5Sm', 'student', '2025-05-04 07:52:07', 'Janice', 'Ledesma'),
(7, 'stud3', '$2y$10$M1d/jaJbvprL2S4vqNE4nefzQ4GhpQwnCuWvU8Kr24Yp1hw9Y09MC', 'student', '2025-05-04 08:28:55', 'Jonalyn', 'Senyas'),
(8, 'stud5', '$2y$10$hP4LN84iOfrQXBJ.GMz22.i65LmKPezM6SeA.4DsmnH6yN00o0wMG', 'student', '2025-05-05 02:36:09', 'Maria', 'Lacsao');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `professors`
--
ALTER TABLE `professors`
  ADD PRIMARY KEY (`professor_id`);

--
-- Indexes for table `schedules`
--
ALTER TABLE `schedules`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `professors`
--
ALTER TABLE `professors`
  MODIFY `professor_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `schedules`
--
ALTER TABLE `schedules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
