-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 13, 2026 at 11:04 AM
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
-- Database: `drilltechdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `Admin_ID` int(11) NOT NULL,
  `Admin_Password` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`Admin_ID`, `Admin_Password`) VALUES
(1, 'admin12'),
(2, 'admin123'),
(3, 'admin1234');

-- --------------------------------------------------------

--
-- Table structure for table `assigned_employee`
--

CREATE TABLE `assigned_employee` (
  `Project_ID` int(11) NOT NULL,
  `Employee_ID` int(11) NOT NULL,
  `ProjectEmp_StartD` date DEFAULT NULL,
  `ProjectEmp_EndD` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `assigned_employee`
--

INSERT INTO `assigned_employee` (`Project_ID`, `Employee_ID`, `ProjectEmp_StartD`, `ProjectEmp_EndD`) VALUES
(1, 1, '2026-06-01', '2026-06-30'),
(1, 4, '2026-06-01', '2026-06-30'),
(1, 7, '2026-06-01', '2026-06-30'),
(1, 8, '2026-06-01', '2026-06-30'),
(1, 9, '2026-06-01', '2026-06-30'),
(1, 10, '2026-06-01', '2026-06-30'),
(1, 11, '2026-06-01', '2026-06-30'),
(2, 2, '2026-01-15', '2026-02-15'),
(2, 5, '2026-01-15', '2026-02-15'),
(2, 12, '2026-01-15', '2026-02-15'),
(2, 13, '2026-01-15', '2026-02-15'),
(2, 14, '2026-01-15', '2026-02-15'),
(2, 15, '2026-01-15', '2026-02-15'),
(2, 16, '2026-01-15', '2026-02-15'),
(3, 3, '2026-06-01', '2026-06-30'),
(3, 6, '2026-06-01', '2026-06-30'),
(3, 7, '2026-06-01', '2026-06-30'),
(3, 9, '2026-06-01', '2026-06-30'),
(3, 11, '2026-06-01', '2026-06-30'),
(3, 13, '2026-06-01', '2026-06-30'),
(3, 17, '2026-06-01', '2026-06-30'),
(5, 1, '2026-06-01', '2026-06-30'),
(5, 5, '2026-06-01', '2026-06-30'),
(5, 8, '2026-06-01', '2026-06-30'),
(5, 10, '2026-06-01', '2026-06-30'),
(5, 12, '2026-06-01', '2026-06-30'),
(5, 14, '2026-06-01', '2026-06-30'),
(5, 18, '2026-06-01', '2026-06-30'),
(6, 2, '2026-03-01', '2026-03-31'),
(6, 6, '2026-03-01', '2026-03-31'),
(6, 15, '2026-03-01', '2026-03-31'),
(6, 16, '2026-03-01', '2026-03-31'),
(6, 17, '2026-03-01', '2026-03-31'),
(6, 19, '2026-03-01', '2026-03-31'),
(6, 20, '2026-03-01', '2026-03-31');

-- --------------------------------------------------------

--
-- Table structure for table `client`
--

CREATE TABLE `client` (
  `Client_ID` int(11) NOT NULL,
  `Client_Name` varchar(100) DEFAULT NULL,
  `Client_Contact` varchar(50) DEFAULT NULL,
  `Client_Email` varchar(100) DEFAULT NULL,
  `Client_Address` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `client`
--

INSERT INTO `client` (`Client_ID`, `Client_Name`, `Client_Contact`, `Client_Email`, `Client_Address`) VALUES
(1, 'ABC Corp', '0123456789', 'abc@corp.com', 'KL'),
(2, 'XYZ Holdings', '0139876543', 'xyz@holdings.com', 'Seremban'),
(3, 'MegaBuild', '0142233445', 'mega@build.com', 'Melaka');

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

CREATE TABLE `employee` (
  `Employee_ID` int(11) NOT NULL,
  `Employee_Name` varchar(100) DEFAULT NULL,
  `Employee_Contact` varchar(50) DEFAULT NULL,
  `Employee_Gender` varchar(10) DEFAULT NULL,
  `Employee_Position` varchar(50) DEFAULT NULL,
  `Employee_Address` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employee`
--

INSERT INTO `employee` (`Employee_ID`, `Employee_Name`, `Employee_Contact`, `Employee_Gender`, `Employee_Position`, `Employee_Address`) VALUES
(1, 'Nadhir Nasaar', '0139876543', 'Male', 'Site Engineer', 'Kelantan'),
(2, 'Ahmad Mateen', '0157788990', 'Male', 'Site Engineer', 'KL'),
(3, 'Muhd Amiryul', '01156502124', 'Male', 'Site Engineer', 'Penang'),
(4, 'Nur Aisyah', '0123344556', 'Female', 'Site Supervisor', 'KL'),
(5, 'Daniel Hakim', '0189988776', 'Male', 'Site Supervisor', 'Johor'),
(6, 'Sabrina Kamila', '0172233445', 'Female', 'Site Supervisor', 'Melaka'),
(7, 'Khairul Danish', '0113344556', 'Male', 'General Worker', 'Kelantan'),
(8, 'Muhd Uzair', '0146677889', 'Male', 'General Worker', 'Perak'),
(9, 'Nur Faizah', '0135566778', 'Female', 'General Worker', 'Perak'),
(10, 'Johan Jasli', '0167788990', 'Male', 'General Worker', 'Negeri Sembilan'),
(11, 'Muhd Hamid', '0124455667', 'Male', 'General Worker', 'Negeri Sembilan'),
(12, 'Lim Wei Han', '0172233445', 'Male', 'General Worker', 'Melaka'),
(13, 'Abdullah Abdul', '0123344556', 'Male', 'General Worker', 'KL'),
(14, 'Amira Shafiqah', '0172233445', 'Female', 'General Worker', 'Melaka'),
(15, 'Hakim Fateeh', '0135566778', 'Male', 'General Worker', 'Perak'),
(16, 'Adam Nabeel', '0124455667', 'Male', 'General Worker', 'Negeri Sembilan'),
(17, 'Khairul Hakim', '0146677889', 'Male', 'General Worker', 'Kelantan'),
(18, 'Johari ', '0113344556', 'Male', 'General Worker', 'Selangor'),
(19, 'Muhd Zafry', '0157788990', 'Male', 'General Worker', 'KL'),
(20, 'Amirul Danish', '0192233445', 'Male', 'General Worker', 'Penang');

-- --------------------------------------------------------

--
-- Table structure for table `equipment`
--

CREATE TABLE `equipment` (
  `Equipment_ID` int(11) NOT NULL,
  `Equipment_Quantity` int(11) DEFAULT NULL,
  `Equipment_Name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `equipment`
--

INSERT INTO `equipment` (`Equipment_ID`, `Equipment_Quantity`, `Equipment_Name`) VALUES
(1, 5, 'Goodeng'),
(2, 5, 'Backhoe'),
(3, 4, 'Crane'),
(4, 300, 'Pipeline');

-- --------------------------------------------------------

--
-- Table structure for table `equipment_usage`
--

CREATE TABLE `equipment_usage` (
  `Project_ID` int(11) NOT NULL,
  `Equipment_ID` int(11) NOT NULL,
  `Equipment_Duration` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `equipment_usage`
--

INSERT INTO `equipment_usage` (`Project_ID`, `Equipment_ID`, `Equipment_Duration`) VALUES
(1, 1, '30 days'),
(1, 2, '20 days'),
(1, 3, '15 days'),
(1, 4, '80 unit'),
(2, 1, '25 days'),
(2, 2, '30 days'),
(2, 3, '20 days'),
(2, 4, '100 unit'),
(3, 1, '25 days'),
(3, 2, '30 days'),
(3, 3, '20 days'),
(3, 4, '100 unit'),
(5, 1, '40 days'),
(5, 2, '35 days'),
(5, 3, '25 days'),
(5, 4, '50 unit'),
(6, 1, '30 days'),
(6, 2, '30 days');

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `Payment_ID` int(11) NOT NULL,
  `Payment_Method` varchar(50) DEFAULT NULL,
  `Payment_Date` date DEFAULT NULL,
  `Payment_Status` varchar(50) DEFAULT NULL,
  `Payment_Time` time DEFAULT NULL,
  `Project_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payment`
--

INSERT INTO `payment` (`Payment_ID`, `Payment_Method`, `Payment_Date`, `Payment_Status`, `Payment_Time`, `Project_ID`) VALUES
(7, 'Online Banking', '2026-06-01', 'Completed', '14:30:00', 1),
(8, 'Cheque', '2026-06-02', 'Completed', '10:00:00', 2),
(9, 'Online Banking', '2026-06-03', 'Pending', '09:15:00', 3),
(10, 'Cheque', '2026-06-04', 'Completed', '16:45:00', 4),
(11, 'Online Banking', '2026-06-05', 'Completed', '11:20:00', 5),
(12, 'Cheque', '2026-06-06', 'Completed', '12:00:00', 6);

-- --------------------------------------------------------

--
-- Table structure for table `payroll`
--

CREATE TABLE `payroll` (
  `Payroll_ID` int(11) NOT NULL,
  `Payroll_Status` varchar(50) DEFAULT NULL,
  `Payroll_Amount` decimal(10,2) DEFAULT NULL,
  `Payroll_Type` varchar(50) DEFAULT NULL,
  `Payroll_Date` date DEFAULT NULL,
  `Employee_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payroll`
--

INSERT INTO `payroll` (`Payroll_ID`, `Payroll_Status`, `Payroll_Amount`, `Payroll_Type`, `Payroll_Date`, `Employee_ID`) VALUES
(1, 'Paid', 4500.00, 'Full Time', '2026-06-01', 1),
(2, 'Paid', 4800.00, 'Full Time', '2026-06-02', 2),
(3, 'Paid', 5000.00, 'Full Time', '2026-06-03', 3),
(4, 'Paid', 5200.00, 'Full Time', '2026-06-04', 4),
(5, 'Paid', 4700.00, 'Full Time', '2026-06-05', 5),
(6, 'Paid', 3000.00, 'Part Time', '2026-06-06', 6),
(7, 'Paid', 2500.00, 'Full Time', '2026-06-07', 7),
(8, 'Paid', 2600.00, 'Full Time', '2026-06-08', 8),
(9, 'Paid', 2700.00, 'Full Time', '2026-06-09', 9),
(10, 'Paid', 2800.00, 'Full Time', '2026-06-10', 10),
(11, 'Paid', 2900.00, 'Full Time', '2026-06-11', 11),
(12, 'Paid', 2000.00, 'Part Time', '2026-06-12', 12),
(13, 'Paid', 2100.00, 'Part Time', '2026-06-13', 13),
(14, 'Paid', 2200.00, 'Part Time', '2026-06-14', 14),
(15, 'Paid', 2300.00, 'Part Time', '2026-06-15', 15),
(16, 'Paid', 2400.00, 'Part Time', '2026-06-16', 16),
(17, 'Paid', 2500.00, 'Full Time', '2026-06-17', 17),
(18, 'Paid', 2600.00, 'Full Time', '2026-06-18', 18),
(19, 'Paid', 2700.00, 'Full Time', '2026-06-19', 19),
(20, 'Paid', 2800.00, 'Full Time', '2026-06-20', 20);

-- --------------------------------------------------------

--
-- Table structure for table `project`
--

CREATE TABLE `project` (
  `Project_ID` int(11) NOT NULL,
  `Project_Name` varchar(100) DEFAULT NULL,
  `Project_Location` varchar(100) DEFAULT NULL,
  `Project_Value` decimal(10,2) DEFAULT NULL,
  `Project_Status` varchar(50) DEFAULT NULL,
  `Client_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `project`
--

INSERT INTO `project` (`Project_ID`, `Project_Name`, `Project_Location`, `Project_Value`, `Project_Status`, `Client_ID`) VALUES
(1, 'Pipeline Delta', 'Negeri Sembilan', 80000.00, 'On Going', 1),
(2, 'River Crossing', 'Melaka', 60000.00, 'Completed', 1),
(3, 'Site Alpha', 'KL', 90000.00, 'On Going', 2),
(4, 'Hilltop Install', 'Seremban', 75000.00, 'Pending', 2),
(5, 'Bridge Beta', 'Johor', 50000.00, 'On Going', 3),
(6, 'Tunnel Gamma', 'Penang', 70000.00, 'Completed', 3);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`Admin_ID`);

--
-- Indexes for table `assigned_employee`
--
ALTER TABLE `assigned_employee`
  ADD PRIMARY KEY (`Project_ID`,`Employee_ID`),
  ADD KEY `Employee_ID` (`Employee_ID`);

--
-- Indexes for table `client`
--
ALTER TABLE `client`
  ADD PRIMARY KEY (`Client_ID`);

--
-- Indexes for table `employee`
--
ALTER TABLE `employee`
  ADD PRIMARY KEY (`Employee_ID`);

--
-- Indexes for table `equipment`
--
ALTER TABLE `equipment`
  ADD PRIMARY KEY (`Equipment_ID`);

--
-- Indexes for table `equipment_usage`
--
ALTER TABLE `equipment_usage`
  ADD PRIMARY KEY (`Project_ID`,`Equipment_ID`),
  ADD KEY `Equipment_ID` (`Equipment_ID`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`Payment_ID`),
  ADD KEY `Project_ID` (`Project_ID`);

--
-- Indexes for table `payroll`
--
ALTER TABLE `payroll`
  ADD PRIMARY KEY (`Payroll_ID`),
  ADD KEY `Employee_ID` (`Employee_ID`);

--
-- Indexes for table `project`
--
ALTER TABLE `project`
  ADD PRIMARY KEY (`Project_ID`),
  ADD KEY `Client_ID` (`Client_ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `Admin_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `client`
--
ALTER TABLE `client`
  MODIFY `Client_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `employee`
--
ALTER TABLE `employee`
  MODIFY `Employee_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `equipment`
--
ALTER TABLE `equipment`
  MODIFY `Equipment_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `Payment_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `payroll`
--
ALTER TABLE `payroll`
  MODIFY `Payroll_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `project`
--
ALTER TABLE `project`
  MODIFY `Project_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `assigned_employee`
--
ALTER TABLE `assigned_employee`
  ADD CONSTRAINT `assigned_employee_ibfk_1` FOREIGN KEY (`Project_ID`) REFERENCES `project` (`Project_ID`),
  ADD CONSTRAINT `assigned_employee_ibfk_2` FOREIGN KEY (`Employee_ID`) REFERENCES `employee` (`Employee_ID`);

--
-- Constraints for table `equipment_usage`
--
ALTER TABLE `equipment_usage`
  ADD CONSTRAINT `equipment_usage_ibfk_1` FOREIGN KEY (`Project_ID`) REFERENCES `project` (`Project_ID`),
  ADD CONSTRAINT `equipment_usage_ibfk_2` FOREIGN KEY (`Equipment_ID`) REFERENCES `equipment` (`Equipment_ID`);

--
-- Constraints for table `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `payment_ibfk_1` FOREIGN KEY (`Project_ID`) REFERENCES `project` (`Project_ID`);

--
-- Constraints for table `payroll`
--
ALTER TABLE `payroll`
  ADD CONSTRAINT `payroll_ibfk_1` FOREIGN KEY (`Employee_ID`) REFERENCES `employee` (`Employee_ID`);

--
-- Constraints for table `project`
--
ALTER TABLE `project`
  ADD CONSTRAINT `project_ibfk_1` FOREIGN KEY (`Client_ID`) REFERENCES `client` (`Client_ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
