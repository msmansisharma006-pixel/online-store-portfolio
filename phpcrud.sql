-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 30, 2026 at 04:01 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `phpcrud`
--

-- --------------------------------------------------------

--
-- Table structure for table `tblproduct`
--

CREATE TABLE `tblproduct` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  `image` text NOT NULL,
  `price` double(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblproduct`
--

INSERT INTO `tblproduct` (`id`, `name`, `code`, `image`, `price`) VALUES
(1, 'Beauty of Joseon Relief Sun Aqua-fresh Rice + B5 (50 ml)', 'Beauty of Joseon', 'bojspf.avif', 1570.00),
(2, 'Laneige Lip Glowy Balm - Berry', 'Laneige', 'laneige.avif', 750.00),
(3, 'Neutrogena Hydro Boost Water Gel Face Moisturizer (50g)', 'Neutrogena', 'neutrogena.avif', 1190.00),
(4, 'Huda Beauty Easy Bake Loose Powder Mini - Banana Bread (6 g)', 'Huda Beauty', 'huda.avif', 1900.00),
(5, 'Inde Wild Champi Hair Oil ', 'Inde Wild', 'inde wild.avif', 844.00),
(6, 'COSRX Advanced Snail 96 Mucin Power Essence (100ml)', 'COSRX', 'snail mucin.jpeg', 1450.00),
(7, 'ClayCo. Matcha Enzyme Scrub For Dead Skin Removal (70 g)', 'ClayCo.', 'clay.avif', 799.00),
(8, 'L\'Oreal Professionnel Absolut Repair Combo For Damaged Hair', 'L\'Oreal', 'loreal pro.avif', 1749.00),
(9, 'The Ordinary Glycolic Acid 7% Toning Solution (240 ml)', 'The Ordinary', 'the ordinary toner.avif', 1275.00),
(10, 'PAC Micro Finish Makeup Fixer (120ml)', 'PAC', 'pac.avif', 1175.00),
(11, 'FENTY BEAUTY Gloss Bomb Stix High-Shine Gloss Stick - Riri (3.6 g)', 'FENTY BEAUTY', 'fenty beauty.avif', 2700.00),
(12, 'SKIN1004 Madagascar Centella Tone Brightening Capsule Ampoule (100ml)', 'SKIN1004', 'skin1004.avif', 2249.00);

-- --------------------------------------------------------

--
-- Table structure for table `tblproduct2`
--

CREATE TABLE `tblproduct2` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  `image` text NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblproduct2`
--

INSERT INTO `tblproduct2` (`id`, `name`, `code`, `image`, `price`) VALUES
(1, 'Nails Our Way Nail Enamel Remover - Squeaky Clean (30 ml)', 'Nails Our Way', 'nail.avif', 99.00),
(2, 'KIKO Milano Volume Lip Plumper - Tutu Rose (6.5 ml)', 'KIKO Milano', 'kikolip.avif', 1250.00),
(3, 'Mixsoon Bean Essence (50 ml)', 'Mixsoon', 'mixsoon.avif', 3799.00),
(4, 'Profusion Cosmetics 21 Shade Eye Shadow Pallete & Brush (33.6 g)', 'Profusion Cosmetics', 'eyeshadow.avif', 1300.00),
(5, 'KIKO Milano 3D Hydra Lipgloss - 35 Pearly Warm Mauve (6.5 ml)', 'KIKO Milano', 'kikogloss.avif', 1290.00),
(6, 'Mixsoon Soybean Milk Pad (16 ml x 3 Sheets)', 'Mixsoon', 'mixsoonmask.avif', 219.00),
(7, 'Akind On Cloud Nine Lightweight Moisturiser (50g)', 'Akind', 'akind.jpeg', 945.00),
(8, 'Akind One For All Multi-Tint - Barrier-Loving Tint for Lips(5.5 ml)', 'Akind', 'akindlip.jpeg', 695.00),
(9, 'TIRTIR Mask Fit Red Mini Cushion - 23N Sand (4.5 g)', 'TIRTIR', 'tirtirfoundation.avif', 1200.00),
(10, 'Innisfree Super Volcanic Pore Clay Mask (20 ml)', 'Innisfree', 'innisfreemask.avif', 1100.00),
(11, 'Round Lab 1025 Dokdo Cleanser (150 ml)', 'Round Lab', 'roundlab.avif', 1299.00),
(12, 'Laneige Bouncy And Firm Sleeping Mask (25 ml)', 'Laneige', 'sleepingmask.avif', 1300.00);

-- --------------------------------------------------------

--
-- Table structure for table `tblproduct3`
--

CREATE TABLE `tblproduct3` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  `image` text NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblproduct3`
--

INSERT INTO `tblproduct3` (`id`, `name`, `code`, `image`, `price`) VALUES
(1, 'Mid Waist Pocket Wide Leg Trouser Curve & Plus', 'BROADSTAR', '1.avif', 1799.00),
(2, 'Linen-Blend High Rise Solid Pocket Tapered Trousers', 'H&M', '2.avif', 2549.00),
(3, 'Woven Straight Leg High Waist Trousers', 'Cider', '3.avif', 1789.00),
(4, 'Woven Straight Leg High Waist Trousers', 'Cider', '4.avif', 900.00),
(5, 'High Waist Solid Pleated Wide Leg Trousers', 'BROADSTAR', '5.avif', 1500.00),
(6, 'Mid Rise Solid Pleated Pocket Tapered Trousers With Belt', 'Cider', '6.avif', 1199.00),
(7, 'Mid Rise Solid Pleated Pocket Wide Leg Trousers With Belt', 'H&M', '7.avif', 3399.00),
(8, 'Khaki High Waist Pleated Wide Leg Pant', 'BROADSTAR', '8.avif', 1655.00),
(9, 'Tan Linen Blend Solid Wide Leg Pant with Belt', 'Van Heusen', '9.avif', 3599.00),
(10, 'Beige Solid Pleated High Waist Wide Leg Trouser', 'Cider', '10.avif', 1800.00),
(11, 'Green Elastic Waist Solid Pleated Wide Leg Trouser', 'Van Heusen', '11.avif', 1999.00),
(12, 'Green French Riviera Vacation High Rise Solid Pocket Wide Leg Trouser', 'H&M', '12.avif', 999.00);

-- --------------------------------------------------------

--
-- Table structure for table `tblproduct4`
--

CREATE TABLE `tblproduct4` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  `image` text NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblproduct4`
--

INSERT INTO `tblproduct4` (`id`, `name`, `code`, `image`, `price`) VALUES
(1, '9 to 5 Eyeconic Liquid Eyeliner- Intense Black (4.5ml)', 'Lakme', '2.avif', 325.00),
(2, 'Mask Fit Mini Cushion Foundation- 27C Cool Beige (4.5g)', 'TIRTIR', '3.avif', 1200.00),
(3, 'Cheek Out Freestyle Cream Blush- Petal Poppin (3 g)', 'FENTY BEAUTY', '4.avif', 3125.00),
(4, 'Lip Tins- Rosy Lips (17 g)', 'Vaseline', '5.avif', 295.00),
(5, 'Pro Artist Ultra Definition Liquid Foundation(60 ml)', 'Daily Life Foreever52', '6.avif', 1799.00),
(6, 'essence Birthday Bomb Shiny Lipgloss- 01 Cke My Day! (10 ml)', 'essence', '7.avif', 350.00),
(7, '9 to 5 Powerplay Priming Foundation', 'LAKME', '8.avif', 899.00),
(8, 'Rom&nd The Juciy Lasting Tint- 03 Bare Grape (3.5 g', 'Rom&nd', '9.avif', 940.00),
(9, 'Matte Foundation- 02- 24hr, SPF25, Waterproof, Full Coverage (30 ml)', 'Charmacy Milano', '10.avif', 1099.00),
(10, 'Sheglam Color Bloom Liquid Blush - Love Cake (5.2 ml)', 'Sheglam', '11.avif', 549.00),
(11, 'KIKO Milano Smart Fusion Lipstick - 430 Amaranth (3 g)', 'KIKO Milano', '12.avif', 490.00),
(12, 'FENTY BEAUTY Eaze Drop Blurring Skin Tint - 09 (32 ml)', 'FENTY BEAUTY', '13.avif', 4150.00);

-- --------------------------------------------------------

--
-- Table structure for table `tblproduct5`
--

CREATE TABLE `tblproduct5` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  `image` text NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblproduct5`
--

INSERT INTO `tblproduct5` (`id`, `name`, `code`, `image`, `price`) VALUES
(1, 'Brown Casual Collar Short Sleeve Polyester Plain Embellished High Stretch Women Clothing', 'Cute Top', 'top.jpeg', 699.00),
(2, 'Plus Size Women Shiny Draped Neck Halter Top Black Party Knitted Fabric Plain.', 'Black V-Neckline Top', 'top2.jpeg', 749.00),
(3, 'Khaki Casual Collar Knitted Fabric Plain Halter Embellished Medium Stretch Women Clothing', 'Khaki Top', 'top3.jpeg', 499.00),
(4, 'Teen Girls Square Neck Red And White Floral Print Slim Fit Pleated Elegant Tank Top', 'Floral Top', 'top4.jpeg', 549.00),
(5, 'Plus Size Turndown Collar Contrast Color Chest Ruched Burgundy & White Contrast Color Shirt', 'Collar Top', 'top5.jpeg', 699.00),
(6, 'Army Green Casual Collar Short Sleeve Knitted Fabric Plain Embellished Stretch Women Clothing', 'Army Green Casual', 'top7.jpeg', 750.00),
(7, 'Bowknot Gather Green Vest Army Green Casual Knitted Fabric Plain High Stretch Women Clothing', 'Stand Collar', 'top8.jpeg', 999.00),
(8, 'ROMWE Women\'s Floral Print Sheer Mesh Square Neck Top With Flared Sleeves online Australia', 'Sheer Mesh Top', 'top9.jpeg', 899.00),
(9, 'Women\'s Color Block 2 In 1 Halter Neck Tee Multicolor Casual Short Sleeve Knitted Fabric Colorblock', 'Halter Neck Tee', 'top10.jpeg', 749.00),
(10, 'Navy Blue Collar Sleeveless Woven Fabric Colorblock vest Embellished Non-Stretch Women Clothing', 'Collar Sleeveless', 'top11.jpeg', 1300.00),
(11, 'SHEIN MOD Contrast Lace Ruched Crop TeeI discovered amazing products on SHEIN.', 'Contrast Lace Ruched', 'top12.jpeg', 399.00),
(12, 'Plus SizeWomen Vintage Apricot Sleeveless Knot Back Crisscross Tank Top Burgundy Casual Woven Fabric', 'Sleeveless Knot Back', 'top6.jpeg', 899.00);

-- --------------------------------------------------------

--
-- Table structure for table `tblproduct6`
--

CREATE TABLE `tblproduct6` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  `image` text NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblproduct6`
--

INSERT INTO `tblproduct6` (`id`, `name`, `code`, `image`, `price`) VALUES
(1, 'Blue Solid Cowl Neck Slit Satin Midi Dress', 'H&M', '1.avif', 1599.00),
(2, 'Green Elasticated Cinched Waist Maxi Dress', 'Outzidr', '2.avif', 2299.00),
(3, 'Cowl Neck Solid Glitter Ruffle Hem Maxi Dress', 'Cider', '9.avif', 2399.00),
(4, 'Paisley Knotted Criss Cross Printed Maxi Dress', 'H&M', '4.avif', 1799.00),
(5, 'One Shoulder Neck Drawstring Split Maxi Dress', 'Styli', '5.avif', 1599.00),
(6, 'Velvet Boat Neck Bowknot Backless Midi Dress', 'AAREIN', '6.avif', 2999.00),
(7, 'Asymmetrical Neck Buckle Black Maxi Dress', 'Styli', '7.avif', 2245.00),
(8, 'Woven Strapless Solid Ruched Maxi Dress', 'Cider', '8.avif', 3499.00),
(9, 'Pink Elasticated Cinched Waist Maxi Dress', 'Outzidr', '3.avif', 1679.00),
(10, 'Green Solid Spaghetti Strap Mermaid Maxi Dress', 'AAREIN', '10.avif', 1900.00),
(11, 'Black Spaghetti Strap Fitted Dress With Crop Shrug', 'H&M', '11.avif', 2499.00),
(12, 'Satin Cowl Neck 3D Floral Ruched Mermaid Dress', 'Outzidr', '12.avif', 3099.00);

-- --------------------------------------------------------

--
-- Table structure for table `tblproduct7`
--

CREATE TABLE `tblproduct7` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  `image` text NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblproduct7`
--

INSERT INTO `tblproduct7` (`id`, `name`, `code`, `image`, `price`) VALUES
(1, 'V-neck Ruched Top & Mid Rise Elastic Waist Knotted Straight Leg Trousers', 'BROADSTAR', '1.avif', 1499.00),
(2, 'Cotton-blend Solid Ruched Hoodie Straight Leg Trousers Set', 'H&M', '2.avif', 1449.00),
(3, 'Mason Co-Ord Set Black', 'Cider', '3.avif', 1999.00),
(4, 'V-neck Twist Split Crop Cami Top & Mid Rise Wide Leg Trousers', 'Cider', '4.avif', 2299.00),
(5, 'Brodie Co-Ord Set Red', 'BROADSTAR', '5.avif', 1799.00),
(6, 'Boat Neck Long Sleeve Top & Mid Rise Straight Leg Trousers With Belt', 'Cider', '6.avif', 2199.00),
(7, 'Abstract Graphic Collar Bell Sleeve Shirt & Mid Rise Wide Leg Trousers Set', 'H&M', '7.avif', 4599.00),
(8, 'V-neck Stripe Button Tank Top & Mid Rise Pocket Straight Leg Trousers', 'BROADSTAR', '8.avif', 1999.00),
(9, 'Linen-blend Pleated Cami Top & Mid Rise Elastic Waist Straight Leg Trousers ', 'Miakee', '9.avif', 2349.00),
(10, 'Shirred Knotted Crop Tube & Wide Leg Trousers', 'Cider', '10.avif', 1699.00),
(11, 'Linen-blend Lace Up Corset Bandeau Top & Mid Rise Barrel-leg Trousers Set', 'Miakee', '11.avif', 1499.00),
(12, 'Cotton-blend V-neck Solid Button Vest & Mid Rise Knotted Pocket Straight Leg Trousers', 'H&M', '12.avif', 2899.00);

-- --------------------------------------------------------

--
-- Table structure for table `tblusers`
--

CREATE TABLE `tblusers` (
  `id` int(11) NOT NULL,
  `firstname` varchar(255) DEFAULT NULL,
  `lastname` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `address` varchar(500) DEFAULT NULL,
  `reg_date` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblusers`
--

INSERT INTO `tblusers` (`id`, `firstname`, `lastname`, `email`, `address`, `reg_date`) VALUES
(1, 'Mansi', 'Sharma', 'mansi7204sharma@gmail.com', 'Delhi-36', '2026-04-08');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `address` varchar(100) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `address`, `phone`, `created_at`) VALUES


--
-- Indexes for dumped tables
--

--
-- Indexes for table `tblproduct`
--
ALTER TABLE `tblproduct`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblproduct2`
--
ALTER TABLE `tblproduct2`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblproduct3`
--
ALTER TABLE `tblproduct3`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblproduct4`
--
ALTER TABLE `tblproduct4`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblproduct5`
--
ALTER TABLE `tblproduct5`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblproduct6`
--
ALTER TABLE `tblproduct6`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblproduct7`
--
ALTER TABLE `tblproduct7`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblusers`
--
ALTER TABLE `tblusers`
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
-- AUTO_INCREMENT for table `tblproduct`
--
ALTER TABLE `tblproduct`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `tblproduct2`
--
ALTER TABLE `tblproduct2`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `tblproduct3`
--
ALTER TABLE `tblproduct3`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `tblproduct4`
--
ALTER TABLE `tblproduct4`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `tblproduct5`
--
ALTER TABLE `tblproduct5`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `tblproduct6`
--
ALTER TABLE `tblproduct6`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `tblproduct7`
--
ALTER TABLE `tblproduct7`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `tblusers`
--
ALTER TABLE `tblusers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
