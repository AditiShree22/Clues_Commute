-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 28, 2017 at 10:33 AM
-- Server version: 10.1.22-MariaDB
-- PHP Version: 7.1.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `clues_commute`
--

-- --------------------------------------------------------

--
-- Table structure for table `main`
--

CREATE TABLE `main` (
  `employee_id` varchar(50) NOT NULL,
  `employee_name` varchar(50) NOT NULL,
  `gender` varchar(10) NOT NULL,
  `designation` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `mobile_number` varchar(15) NOT NULL,
  `address` varchar(50) NOT NULL,
  `number_of_seats` int(5) DEFAULT NULL,
  `vehicle_number` varchar(50) DEFAULT NULL,
  `latitude` float NOT NULL,
  `longitude` float NOT NULL,
  `zone_id` int(5) NOT NULL,
  `owns_vehicle` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `main`
--

INSERT INTO `main` (`employee_id`, `employee_name`, `gender`, `designation`, `email`, `mobile_number`, `address`, `number_of_seats`, `vehicle_number`, `latitude`, `longitude`, `zone_id`, `owns_vehicle`) VALUES
('C1358', 'Sandeep Kumar', 'male', 'Manager', 'sandeep.k@shopclues.com', '9911495149', 'Uttam Nagar, Delhi', 4, 'DL3c 5568', 28.6213, 77.0613, 1, 1),
('C1572', 'Rachit Malik', 'male', 'senior Analyst', 'rachit.malik@shopclues.com', '8130910451', 'saket, Delhi', 4, 'DL3C 5569', 28.5246, 77.2066, 1, 1),
('C1606', 'Shrey Srivastava', 'male', 'Manager', 'shrey@shopclues.com', '8510993989', 'Sector 7, Gurgaon', 4, 'DL3C 5569', 28.466, 77.0073, 1, 1),
('C1658', 'Ekta Sharma', 'female', 'senior Analyst', 'ekta@shopclues.com', '9971117761', 'Sector 30, Gurgaon', NULL, NULL, 28.4604, 77.0579, 1, 0),
('C1747', 'Nisha Sharma', 'female', 'senior Analyst', 'nisha@shopclues.com', '9582818646', 'Paschim Vihar, Delhi', NULL, NULL, 28.6687, 77.1019, 1, 0),
('C1784', 'Charu Kapoor', 'male', 'Assistant Manager', 'charu@shopclues.com', '9810886072', 'Dwarka, Delhi', 4, 'DL3C 5569', 28.5921, 77.0461, 1, 1),
('C1859', 'Lohit Kumar', 'male', 'Senior Analyst', 'lohit@shopclues.com', '9654234109', 'Shastri Nagar, Delhi', 4, 'DL3C 5569', 28.67, 77.1819, 1, 1),
('C225', 'Ranya Nigam', 'Female', 'Assistant Manager', 'ranya.nigam@shopclues.com', '9711038099', ' Near Sarai Khawaja, Faridabad 121003', 4, 'HR 12 3562', 28.4999, 77.3021, 1, 1),
('C361', ' Gunjan Srivastava', 'Female', 'Assistant Manager', 'gunjan.srivastava@shopclues.com', '9999689642', ' Cybercity, Gurgaon', NULL, NULL, 28.4936, 77.0883, 1, 0),
('shopclues', 'Shopclues', 'female', 'NA', 'shopclues@shopclues.com', ' ', 'plot number- 112, sector- 44, gurgaon ', NULL, NULL, 28.4485, 77.0759, 1, 0),
('T5339', 'Shubhi Lohani', 'female', 'Tech Intern', 'IN-shubhi.lohani@shopclues.com', '8826628784', 'Mayur Vihar Phase 1, Delhi- 110091', 4, 'DL 3c 4547', 28.6146, 77.3122, 1, 1),
('T5340', 'Yumi', 'male', 'Intern', 'yumi@shopclues.com', '8828828282', 'sector- 15, noida', 4, 'DL 4545', 28.5849, 77.3118, 1, 1),
('T5353', 'Aditi Shree', 'female', 'Tech Intern', 'IN-aditi.shree@shopclues.com', '8826628333', 'PWO, Sector 44, Gurgaon', NULL, NULL, 28.453, 77.0747, 1, 0),
('T5354', 'Sheru', 'male', 'Intern', 'IN-sheru@shopclues.com', '8826628000', 'Sector 18, Gurgaon', NULL, NULL, 28.4945, 77.0693, 1, 0),
('T6001', 'Naruto uzumaki', 'male', 'Trainee', 'IN-naru@shopclues.com', '9826628000', 'Sector 10, Gurgaon', 4, 'DL 4001', 28.4551, 77.0032, 1, 1),
('T6002', 'Uchiha sasuke', 'male', 'Intern', 'IN-sasuke@shopclues.com', '9826628001', 'Sector 11, Gurgaon', NULL, NULL, 28.4529, 77.0248, 1, 0),
('T6003', 'Kakashi Hatake', 'male', 'Trainee', 'IN-kakashi@shopclues.com', '9826628002', 'Sector 12, Gurgaon', 4, 'DL 4002', 28.4749, 77.0326, 1, 1),
('T6004', 'Sunade sama', 'female', 'Intern', 'IN-sunade@shopclues.com', '9826628003', 'Sector 13, Gurgaon', NULL, NULL, 28.4759, 77.0406, 1, 0),
('T6005', 'Misaki', 'male', 'Trainee', 'IN-misaki@shopclues.com', '9826628004', 'Sector 14, Gurgaon', 4, 'DL 4003', 28.4707, 77.0464, 1, 1),
('T6006', 'Victor Nikiforov', 'male', 'Intern', 'IN-vitya@shopclues.com', '9826628005', 'Sector 15, Gurgaon', NULL, NULL, 28.4621, 77.0464, 1, 0),
('T6007', 'Yuri Katsuki', 'male', 'Intern', 'IN-yuri@shopclues.com', '9826628006', 'Sector 16, Gurgaon', 4, 'DL 4003', 28.4684, 77.0521, 1, 1),
('T6008', 'Kou Mabuchi', 'male', 'Trainee', 'IN-kou@shopclues.com', '9826628007', 'Sector 17, Gurgaon', NULL, NULL, 28.4792, 77.0593, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `request`
--

CREATE TABLE `request` (
  `request_id` int(11) NOT NULL,
  `driver_id` varchar(11) NOT NULL,
  `passenger_id` varchar(11) NOT NULL,
  `sent_by` varchar(11) NOT NULL,
  `status` int(1) NOT NULL DEFAULT '0',
  `machine_state` int(5) NOT NULL DEFAULT '0',
  `notify` int(1) NOT NULL DEFAULT '0',
  `message` varchar(50) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `reason` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `request`
--

INSERT INTO `request` (`request_id`, `driver_id`, `passenger_id`, `sent_by`, `status`, `machine_state`, `notify`, `message`, `created_at`, `reason`) VALUES
(32, 'C1358', 'C1747', 'C1358', 1, 0, 0, '', '2017-07-27 18:03:17', NULL),
(33, 'C1358', 'C1658', 'C1358', 1, 0, 0, '', '2017-07-27 18:03:20', NULL),
(34, 'C1358', 'C361', 'C1358', 1, 0, 0, '', '2017-07-27 18:03:24', NULL),
(35, 'C1572', 'C1358', 'C1572', 1, 0, 0, '', '2017-07-27 18:24:38', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `route`
--

CREATE TABLE `route` (
  `driver_id` varchar(11) NOT NULL,
  `route` varchar(100) NOT NULL,
  `current_latitude` double(8,6) NOT NULL,
  `current_longitude` double(8,6) NOT NULL,
  `d_latitude` double(8,6) NOT NULL,
  `d_longitude` double(8,6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `route`
--

INSERT INTO `route` (`driver_id`, `route`, `current_latitude`, `current_longitude`, `d_latitude`, `d_longitude`) VALUES
('C1358', 'C1358,C1747,C361,C1658,shopclues', 28.451404, 77.071905, 28.621300, 77.061300),
('C1572', 'C1358,shopclues', 28.451282, 77.071991, 28.448500, 77.075900);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `unique_id` varchar(23) NOT NULL,
  `employee_id` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `encrypted_password` varchar(80) NOT NULL,
  `salt` varchar(10) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `employee_name` varchar(50) NOT NULL,
  `address` varchar(50) NOT NULL,
  `gender` varchar(10) NOT NULL,
  `designation` varchar(50) NOT NULL,
  `zone_id` int(5) NOT NULL,
  `mobile_number` varchar(15) NOT NULL,
  `owns_vehicle` tinyint(1) NOT NULL,
  `number_of_seats` int(5) DEFAULT NULL,
  `visibility_as_driver` tinyint(1) NOT NULL DEFAULT '1',
  `visibility_as_passenger` tinyint(1) NOT NULL DEFAULT '1',
  `vehicle_number` varchar(50) DEFAULT NULL,
  `latitude` float NOT NULL,
  `longitude` float NOT NULL,
  `access_as` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `unique_id`, `employee_id`, `email`, `encrypted_password`, `salt`, `created_at`, `updated_at`, `employee_name`, `address`, `gender`, `designation`, `zone_id`, `mobile_number`, `owns_vehicle`, `number_of_seats`, `visibility_as_driver`, `visibility_as_passenger`, `vehicle_number`, `latitude`, `longitude`, `access_as`) VALUES
(19, '5979dcb2e6c216.46105089', 'C1358', 'sandeep.k@shopclues.com', '6oQWbO3SUkkjM/ZXsVJ41RsJZJRlMGZkYTc4NzQ3', 'e0fda78747', '2017-07-27 17:59:38', NULL, 'Sandeep Kumar', 'Uttam Nagar, Delhi', 'male', 'Manager', 1, '9911495149', 1, 1, 1, 0, 'DL3c 5568', 28.6213, 77.0613, 1),
(20, '5979dcd01fb6a6.04955566', 'C1747', 'nisha@shopclues.com', '4zEsF6S8rNCJi+b1VezROfmzvKY4MzkzYWQ3OGEw', '8393ad78a0', '2017-07-27 18:00:08', NULL, 'Nisha Sharma', 'Paschim Vihar, Delhi', 'female', 'senior Analyst', 1, '9582818646', 0, NULL, 1, 0, NULL, 28.6687, 77.1019, 0),
(21, '5979dd09853cc3.92512177', 'C1658', 'ekta@shopclues.com', 'ZML8t3U54AJFsVPsc/hU18oy9xA5M2Q3MzY1NThm', '93d736558f', '2017-07-27 18:01:05', NULL, 'Ekta Sharma', 'Sector 30, Gurgaon', 'female', 'senior Analyst', 1, '9971117761', 0, NULL, 1, 1, NULL, 28.4604, 77.0579, 0),
(22, '5979dd2d8ec684.68192303', 'C361', 'gunjan.srivastava@shopclues.com', 'kLBOHs7yq40WL6aaK9N1bJaABIpkNWExYzBiY2Uz', 'd5a1c0bce3', '2017-07-27 18:01:41', NULL, ' Gunjan Srivastava', ' Cybercity, Gurgaon', 'Female', 'Assistant Manager', 1, '9999689642', 0, NULL, 1, 0, NULL, 28.4936, 77.0883, 0),
(23, '5979e22bbc7d14.56381153', 'C1572', 'rachit.malik@shopclues.com', 'Ph1cmOVrk2ltLM6wTKaD5VcojNVhMjRlOTNiODVj', 'a24e93b85c', '2017-07-27 18:22:59', NULL, 'Rachit Malik', 'saket, Delhi', 'male', 'senior Analyst', 1, '8130910451', 1, 3, 1, 1, 'DL3C 5569', 28.5246, 77.2066, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `main`
--
ALTER TABLE `main`
  ADD PRIMARY KEY (`employee_id`);

--
-- Indexes for table `request`
--
ALTER TABLE `request`
  ADD PRIMARY KEY (`request_id`);

--
-- Indexes for table `route`
--
ALTER TABLE `route`
  ADD PRIMARY KEY (`driver_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_id` (`unique_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `request`
--
ALTER TABLE `request`
  MODIFY `request_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;COMMIT;

CREATE TABLE `rides` (
  `id` int(11) NOT NULL,
  `employee_id` varchar(50) NOT NULL,
  `source` varchar(250) DEFAULT NULL,
  `destination` varchar(250) DEFAULT NULL,
  `ride_name` varchar(250) NOT NULL,
  `intermediate_points` text DEFAULT NULL,
  `vehicle_type` tinyint(1) NOT NULL DEFAULT '0',
  `vehicle_number` varchar(50) DEFAULT NULL,
  `number_of_seats` int(5) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


ALTER TABLE `rides`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `rides`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;COMMIT;

  ALTER TABLE `rides`
  ADD `active_status` tinyint(1) NOT NULL DEFAULT '1';
  


/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
