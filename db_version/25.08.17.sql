-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 17, 2025 at 07:44 AM
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
-- Database: `gofit_database`
--

-- --------------------------------------------------------

--
-- Table structure for table `diet_plans`
--

CREATE TABLE `diet_plans` (
  `plan_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `diet_plans`
--

INSERT INTO `diet_plans` (`plan_id`, `name`, `description`, `image`) VALUES
(1, 'Keto Diet', 'A low-carbohydrate, High-fat diet plan for the body to shift its fuel source from carbohydrates to fats.', '/Webapp/assets/images/1754665720_house.png'),
(3, 'Keto Dietin', 'Hello this is a description', '/Webapp/assets/images/1754665674_Pen Tool Practise 1.jpg'),
(5, 'Newest Diet', 'Description full description', '/Webapp/assets/images/1754666224_BaconColors.png');

-- --------------------------------------------------------

--
-- Table structure for table `diet_plan_days`
--

CREATE TABLE `diet_plan_days` (
  `day_id` int(11) NOT NULL,
  `plan_id` int(11) NOT NULL,
  `day_number` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `diet_plan_days`
--

INSERT INTO `diet_plan_days` (`day_id`, `plan_id`, `day_number`) VALUES
(1, 3, 1),
(2, 3, 2),
(3, 3, 3),
(4, 3, 4),
(5, 3, 5),
(6, 3, 6),
(7, 3, 7),
(15, 5, 1),
(16, 5, 2),
(17, 5, 3),
(18, 5, 4),
(19, 5, 5),
(20, 5, 6),
(21, 5, 7);

-- --------------------------------------------------------

--
-- Table structure for table `diet_plan_meals`
--

CREATE TABLE `diet_plan_meals` (
  `meal_id` int(11) NOT NULL,
  `day_id` int(11) NOT NULL,
  `meal_type` varchar(50) NOT NULL,
  `food_id` int(11) NOT NULL,
  `amount` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `diet_plan_meals`
--

INSERT INTO `diet_plan_meals` (`meal_id`, `day_id`, `meal_type`, `food_id`, `amount`) VALUES
(2, 1, 'lunch', 4, 1),
(3, 1, 'breakfast', 5, 1),
(4, 1, 'breakfast', 7, 1),
(6, 2, 'breakfast', 6, 1);

-- --------------------------------------------------------

--
-- Table structure for table `exercises`
--

CREATE TABLE `exercises` (
  `exercise_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `category` varchar(50) NOT NULL,
  `difficulty` varchar(50) NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `exercises`
--

INSERT INTO `exercises` (`exercise_id`, `name`, `category`, `difficulty`, `description`) VALUES
(1, 'Dumbbell Bench Press', 'Strength', 'Intermediate', 'Dumbbell variant of bench press. Keep elbows levelled with shoulders and lift upwards, focusing the muscles on the pectorals.'),
(2, 'Dumbbell Curls', 'Strength', 'Beginner', 'The basic beginner exercise, training the bicep muscles on both arms. Can alternate reps between each arm for variation. Avoid swinging your arms for improper form.');

-- --------------------------------------------------------

--
-- Table structure for table `exercise_log`
--

CREATE TABLE `exercise_log` (
  `log_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `exercise_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `sets` int(11) NOT NULL,
  `reps` int(11) DEFAULT NULL,
  `weight` int(11) DEFAULT NULL,
  `distance` float(10,0) NOT NULL,
  `duration` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `foods`
--

CREATE TABLE `foods` (
  `food_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `brand` varchar(255) NOT NULL,
  `calories` int(11) NOT NULL,
  `portion_unit` varchar(50) NOT NULL,
  `carbs` float(11,1) NOT NULL,
  `protein` float(11,1) NOT NULL,
  `fats` float(11,1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `foods`
--

INSERT INTO `foods` (`food_id`, `name`, `brand`, `calories`, `portion_unit`, `carbs`, `protein`, `fats`) VALUES
(1, 'White Bread', 'Gardenia', 70, '1 slice', 14.0, 3.0, 1.2),
(2, 'Full Cream Milk', 'Marigold HL', 50, '100ml', 5.0, 5.0, 1.0),
(4, 'Nasi Lemak', '-', 500, '1 serving', 55.0, 16.0, 25.0),
(5, 'Hainan Chicken Rice', 'The Chicken Rice Shop', 650, '1 serving', 65.0, 40.0, 25.0),
(6, 'Roti Canai (with Dhal)', 'Mamak', 350, '1 piece', 35.0, 7.0, 17.0),
(7, 'Teh Tarik (Kaw)', 'Teh Tarik Place', 160, '1 cup', 28.0, 3.0, 5.0),
(8, '100 Plus (Can)', '100 Plus', 120, '1 cup', 30.0, 0.0, 0.0),
(9, 'Hamburger', 'McDonald', 500, '1 serving', 65.0, 40.0, 17.0),
(10, 'White Bread', 'Gardenia', 70, '1 slice', 14.0, 3.0, 1.0),
(11, 'White Bread', 'Gardenia', 70, '1 slice', 14.0, 3.0, 1.0),
(12, 'White Bread', 'Gardenia', 70, '1 slice', 14.0, 3.0, 1.0),
(13, 'Brown Rice', '-', 112, '100g', 24.0, 2.3, 0.8);

-- --------------------------------------------------------

--
-- Table structure for table `meal_log`
--

CREATE TABLE `meal_log` (
  `log_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `food_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `meal_type` varchar(50) NOT NULL,
  `calories` int(11) NOT NULL,
  `amount` float NOT NULL,
  `carbs` float(11,1) NOT NULL,
  `protein` float(11,1) NOT NULL,
  `fats` float(11,1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `meal_log`
--

INSERT INTO `meal_log` (`log_id`, `user_id`, `food_id`, `date`, `meal_type`, `calories`, `amount`, `carbs`, `protein`, `fats`) VALUES
(1, 1, 1, '2025-08-03', 'breakfast', 140, 2, 28.0, 6.0, 2.0),
(10, 1, 9, '2025-08-09', 'breakfast', 500, 1, 65.0, 40.0, 17.0),
(13, 2, 1, '2025-08-15', 'breakfast', 140, 2, 28.0, 6.0, 2.4),
(14, 2, 4, '2025-08-15', 'dinner', 1500, 3, 165.0, 48.0, 75.0),
(15, 2, 5, '2025-08-15', 'snack', 1950, 3, 195.0, 120.0, 75.0);

-- --------------------------------------------------------

--
-- Table structure for table `user_data`
--

CREATE TABLE `user_data` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `dob` date DEFAULT NULL,
  `gender` enum('Male','Female') DEFAULT NULL,
  `weight` float(10,1) DEFAULT NULL,
  `height` float(10,1) DEFAULT NULL,
  `bmi` float(10,1) DEFAULT NULL,
  `goal_weight` float(10,1) DEFAULT NULL,
  `calorie_goal` int(11) DEFAULT NULL,
  `activity_level` varchar(255) DEFAULT NULL,
  `register_date` date DEFAULT NULL,
  `weight_change` float(10,2) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `bmr` float(10,1) DEFAULT NULL,
  `tdee` float(10,1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_data`
--

INSERT INTO `user_data` (`user_id`, `username`, `password`, `email`, `dob`, `gender`, `weight`, `height`, `bmi`, `goal_weight`, `calorie_goal`, `activity_level`, `register_date`, `weight_change`, `age`, `bmr`, `tdee`) VALUES
(1, 'admin', '$2y$10$dDFAdyLyrvU3KTUaMdtlFOVEDAMoG0bWexAMzUru/syU.rIT1qcUG', 'gofitadmin@gmail.com', '0000-00-00', '', 0.0, 0.0, 0.0, 0.0, 0, '', '0000-00-00', 0.00, 0, 0.0, 0.0),
(2, 'rice', '$2y$10$yv5bFE0Hkbz6pFIYyQPKUu.auVpUW8O7fIYr0f2I3QSSz4W.Hh6wi', 'abc@gmail.com', '2005-05-05', 'Male', 64.5, 178.0, 20.4, 68.0, 2456, 'sedentary', '2025-08-10', 0.75, 20, 1693.2, 2328.1);

-- --------------------------------------------------------

--
-- Table structure for table `user_diet_plans`
--

CREATE TABLE `user_diet_plans` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `plan_id` int(11) NOT NULL,
  `start_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_diet_plans`
--

INSERT INTO `user_diet_plans` (`id`, `user_id`, `plan_id`, `start_date`) VALUES
(1, 2, 3, '2025-08-14');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `diet_plans`
--
ALTER TABLE `diet_plans`
  ADD PRIMARY KEY (`plan_id`);

--
-- Indexes for table `diet_plan_days`
--
ALTER TABLE `diet_plan_days`
  ADD PRIMARY KEY (`day_id`);

--
-- Indexes for table `diet_plan_meals`
--
ALTER TABLE `diet_plan_meals`
  ADD PRIMARY KEY (`meal_id`);

--
-- Indexes for table `exercises`
--
ALTER TABLE `exercises`
  ADD PRIMARY KEY (`exercise_id`);

--
-- Indexes for table `exercise_log`
--
ALTER TABLE `exercise_log`
  ADD PRIMARY KEY (`log_id`);

--
-- Indexes for table `foods`
--
ALTER TABLE `foods`
  ADD PRIMARY KEY (`food_id`);

--
-- Indexes for table `meal_log`
--
ALTER TABLE `meal_log`
  ADD PRIMARY KEY (`log_id`);

--
-- Indexes for table `user_data`
--
ALTER TABLE `user_data`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `user_diet_plans`
--
ALTER TABLE `user_diet_plans`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `diet_plans`
--
ALTER TABLE `diet_plans`
  MODIFY `plan_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `diet_plan_days`
--
ALTER TABLE `diet_plan_days`
  MODIFY `day_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `diet_plan_meals`
--
ALTER TABLE `diet_plan_meals`
  MODIFY `meal_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `exercises`
--
ALTER TABLE `exercises`
  MODIFY `exercise_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `exercise_log`
--
ALTER TABLE `exercise_log`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `foods`
--
ALTER TABLE `foods`
  MODIFY `food_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `meal_log`
--
ALTER TABLE `meal_log`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `user_data`
--
ALTER TABLE `user_data`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `user_diet_plans`
--
ALTER TABLE `user_diet_plans`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
