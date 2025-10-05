
CREATE SCHEMA `brgy_appointment` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ;

USE `brgy_appointment`;

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+08:00";

CREATE TABLE `admin` (
  `admin_id` int(11) NOT NULL,
  `email_address` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `brgy_name` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `brgy_address` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `brgy_contact_no` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `brgy_logo` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `admin` (`admin_id`, `email_address`, `password`, `username`, `brgy_name`, `brgy_address`, `brgy_contact_no`, `brgy_logo`) VALUES
(1, 'admin@admin.com', 'password', 'admin', 'Rosario', 'Rosario Gumaca Quezon IV-A CALABARZON', '0912345678', '../images/brgy-rosario-logo.png');

CREATE TABLE `appointment` (
  `appointment_id` int(11) NOT NULL,
  `brgy_official_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `brgy_official_schedule_id` int(11) NOT NULL,
  `appointment_number` int(11) NOT NULL,
  `reason_for_appointment` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `appointment_time` time NOT NULL,
  `app_status` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `client_come_into_office` enum('No','Yes') COLLATE utf8mb4_unicode_ci NOT NULL,
  `brgy_official_comment` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `appointment` (`appointment_id`, `brgy_official_id`, `client_id`, `brgy_official_schedule_id`, `appointment_number`, `reason_for_appointment`, `appointment_time`, `app_status`, `client_come_into_office`, `brgy_official_comment`) VALUES
(3, 1, 3, 2, 10000, 'Cedula', '09:00:00', 'Cancel', 'No', ''),
(4, 1, 3, 2, 10001, 'Brgy. Clearance', '09:00:00', 'In Process', 'Yes', ''),
(5, 1, 4, 2, 10002, 'Business Permit', '09:30:00', 'Completed', 'Yes', 'Please prepare necessary files.'),
(6, 5, 3, 7, 10003, 'Certificate of Indigency', '18:00:00', 'In Process', 'Yes', 'Please prepare necessary files.'),
(7, 6, 5, 13, 10004, 'Submission of Educational Assistant', '15:30:00', 'Completed', 'Yes', '');

CREATE TABLE `brgy_official_schedule` (
  `brgy_official_schedule_id` int(11) NOT NULL,
  `brgy_official_id` int(11) NOT NULL,
  `schedule_date` date NOT NULL,
  `schedule_day` enum('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday') COLLATE utf8mb4_unicode_ci NOT NULL,
  `schedule_start_time` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `schedule_end_time` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `average_consulting_time` int(5) NOT NULL,
  `schedule_status` enum('Active','Inactive') COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `brgy_official_schedule` (`brgy_official_schedule_id`, `brgy_official_id`, `schedule_date`, `schedule_day`, `schedule_start_time`, `schedule_end_time`, `average_consulting_time`, `schedule_status`) VALUES
(2, 1, '2022-06-01', 'Friday', '10:00', '12:00', 15, 'Active'),
(3, 2, '2022-06-05', 'Friday', '09:00', '12:00', 15, 'Active'),
(4, 5, '2022-06-07', 'Friday', '10:00', '14:00', 10, 'Active'),
(5, 3, '2022-06-08', 'Friday', '13:00', '17:00', 20, 'Active'),
(6, 4, '2022-06-02', 'Friday', '15:00', '18:00', 5, 'Active'),
(7, 5, '2022-06-03', 'Monday', '18:00', '20:00', 10, 'Active'),
(8, 2, '2021-02-04', 'Wednesday', '09:30', '12:30', 10, 'Active'),
(9, 5, '2021-02-11', 'Wednesday', '11:00', '15:00', 10, 'Active'),
(10, 1, '2022-06-12', 'Wednesday', '12:00', '15:00', 10, 'Active'),
(11, 3, '2022-06-09', 'Wednesday', '14:00', '17:00', 15, 'Active'),
(12, 4, '2022-06-08', 'Wednesday', '16:00', '20:00', 10, 'Active'),
(13, 6, '2022-06-08', 'Wednesday', '15:30', '18:30', 10, 'Active'),
(14, 6, '2022-06-11', 'Thursday', '10:00', '13:30', 10, 'Active'),
(15, 3, '2022-06-13', 'Thursday', '13:49', '17:50', 25, 'Active');

CREATE TABLE `brgy_official` (
  `brgy_official_id` int(11) NOT NULL,
  `email_address` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fullname` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `profile_image` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone_no` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `position` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `off_status` enum('Active','Inactive') COLLATE utf8mb4_unicode_ci NOT NULL,
  `added_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `brgy_official` (`brgy_official_id`, `email_address`, `password`, `fullname`, `profile_image`, `phone_no`, `address`, `position`, `off_status`, `added_on`) VALUES
(1, 'abner@gmail.com', 'password', 'Hon. Abner Abequibel', '../images/default-profile.png', '09123456771', '10, Rosario Gumaca, Quezon', 'Barrangay Chairperson', 'Active', '2022-06-15 17:04:59'),
(2, 'ace@gmail.com', 'password', 'Hon. Ace Garcia', '../images/default-profile.png', '09123434331', '32, Rosario Gumaca, Quezon', 'Finance and Appropriation', 'Active', '2022-06-18 15:00:32'),
(3, 'jing@gmail.com', 'password', 'Hon. Jing Marcander', '../images/default-profile.png', '09321233212', '101, Rosario Gumaca, Quezon', 'Public Works and Infastructure', 'Active', '2022-06-18 15:05:02'),
(4, 'julieta@gmail.com', 'password', 'Hon. Julieta Parco', '../images/default-profile.png', '09312312123', '33, Rosario Gumaca, Quezon', 'Education, Sports, Culture and Tourism', 'Active', '2022-06-18 15:08:24'),
(5, 'julius@gmail.com', 'password', 'Hon. Julius Damasco', '../images/default-profile.png', '09832123121', '132, Rosario Gumaca, Quezon', 'Commerce and Trade', 'Active', '2022-06-18 15:15:23'),
(6, 'luningning@gmail.com', 'password', 'Hon. Luningning Magallanes', '../images/default-profile.png', '09412312331', '140, Rosario Gumaca, Quezon', 'Health, Sanitation, Women & Family Affair', 'Active', '2022-06-23 17:26:16'),
(7, 'leo@gmail.com', 'password', 'Hon. Leo Angelo Añouevo', '../images/default-profile.png', '09412312322', '40, Rosario Gumaca, Quezon', 'Agriculture & Food Production', 'Active', '2022-06-23 17:26:16'),
(8, 'cesar@gmail.com', 'password', 'Hon. Cesar Francisco Septimo', '../images/default-profile.png', '09412312234', '13, Rosario Gumaca, Quezon', 'Peace and Order', 'Active', '2022-06-23 17:26:16'),
(9, 'doris@gmail.com', 'password', 'Hon. Doris Nuńezca', '../images/default-profile.png', '09412314323', '24, Rosario Gumaca, Quezon', 'Barrangay Secretary', 'Active', '2022-06-23 17:26:16'),
(10, 'vincent@gmail.com', 'password', 'Hon. Vincent Larcena', '../images/default-profile.png', '09418761233', '52, Rosario Gumaca, Quezon', 'Barrangay Secretary', 'Active', '2022-06-23 17:26:16');

CREATE TABLE `client` (
  `client_id` int(11) NOT NULL,
  `email_address` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `first_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `gender` enum('Male','Female','Other') COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone_no` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `added_on` datetime NOT NULL,
  `verification_code` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verify` enum('No','Yes') COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `client` (`client_id`, `email_address`, `password`, `first_name`, `last_name`, `gender`, `address`, `phone_no`, `added_on`, `verification_code`, `email_verify`) VALUES
(3, 'liam@gmail.com', 'password', 'Liam', 'Reyes', 'Male', '31, Rosario Gumaca, Quezon', '09418543233', '2022-06-18 16:34:55', 'b1f3f8409f7687072adb1f1b7c22d4b0', 'Yes'),
(4, 'emma@gmail.com', 'password', 'Emma', 'Gonzales', 'Female', '65, Rosario Gumaca, Quezon', '09418762344', '2022-06-19 18:28:23', '8902e16ef62a556a8e271c9930068fea', 'Yes'),
(5, 'anna@programmer.net', 'password', 'Anna', 'Cruz', 'Female', '80, Rosario Gumaca, Quezon', '09412342432', '2022-06-23 17:50:06', '1909d59e254ab7e433d92f014d82ba3d', 'Yes');

-- ===================== KEYS & AUTO INCREMENT =====================

ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`);

ALTER TABLE `appointment`
  ADD PRIMARY KEY (`appointment_id`);

ALTER TABLE `brgy_official_schedule`
  ADD PRIMARY KEY (`brgy_official_schedule_id`);

ALTER TABLE `brgy_official`
  ADD PRIMARY KEY (`brgy_official_id`);

ALTER TABLE `client`
  ADD PRIMARY KEY (`client_id`);

ALTER TABLE `admin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

ALTER TABLE `appointment`
  MODIFY `appointment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

ALTER TABLE `brgy_official_schedule`
  MODIFY `brgy_official_schedule_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

ALTER TABLE `brgy_official`
  MODIFY `brgy_official_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

ALTER TABLE `client`
  MODIFY `client_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

COMMIT;
