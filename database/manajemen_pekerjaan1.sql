-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 14, 2025 at 09:02 PM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `manajemen_pekerjaan`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`bramahimm`@`localhost` PROCEDURE `HapusProjek` (IN `p_project_id` INT, IN `p_user_id` INT)   BEGIN
    DECLARE v_exists INT;

    -- Cek apakah proyek ada dan dimiliki oleh user_id yang sesuai
    SELECT COUNT(*) INTO v_exists
    FROM projects
    WHERE id = p_project_id AND user_id = p_user_id;

    IF v_exists = 0 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Proyek tidak ditemukan atau bukan milik Anda';
    ELSE
        -- Simpan log penghapusan proyek hanya dengan kolom yang tersedia
        INSERT INTO activity_logs (user_id, aksi, waktu)
        VALUES (p_user_id, CONCAT('Menghapus proyek ID ', p_project_id), NOW());

        -- Hapus proyek setelah pencatatan log
        DELETE FROM projects WHERE id = p_project_id AND user_id = p_user_id;
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `TambahProjek` (IN `p_nama_projek` VARCHAR(150), IN `p_deskripsi` TEXT, IN `p_user_id` INT, IN `p_deadline` DATE)   BEGIN
    DECLARE last_id INT;

    -- Tambahkan proyek
    INSERT INTO projects(nama_projek, deskripsi, user_id, deadline, status)
    VALUES (p_nama_projek, p_deskripsi, p_user_id, p_deadline, 'planning');

    -- Ambil ID proyek terakhir
    SET last_id = LAST_INSERT_ID();

    -- Tambahkan log aktivitas
    INSERT INTO activity_logs(user_id, aksi)
    VALUES (p_user_id, CONCAT('Membuat proyek "', p_nama_projek, '"'));

END$$

--
-- Functions
--
CREATE DEFINER=`root`@`localhost` FUNCTION `TotalTaskUser` (`p_user_id` INT) RETURNS INT DETERMINISTIC BEGIN
    DECLARE total INT;
    SELECT COUNT(*) INTO total FROM tasks WHERE assigned_to = p_user_id;
    RETURN total;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `aksi` varchar(255) DEFAULT NULL,
  `waktu` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `activity_logs`
--

INSERT INTO `activity_logs` (`id`, `user_id`, `aksi`, `waktu`) VALUES
(1, 1, 'Membuat proyek Website Portofolio', '2025-06-01 21:06:15'),
(2, 2, 'Membuat proyek Sistem Inventaris Sekolah', '2025-06-01 21:06:15'),
(3, 3, 'Ditugaskan membuat desain tampilan utama', '2025-06-01 21:06:15'),
(4, 1, 'Membuat proyek Aplikasi Manajemen Tugas', '2025-06-01 21:06:15'),
(5, 2, 'Menambahkan task \"Testing Backend\"', '2025-06-12 15:16:46'),
(6, 2, 'Membuat proyek \"coba\"', '2025-06-12 15:21:21'),
(7, 2, 'Menambahkan task \"coba\"', '2025-06-12 15:28:24'),
(8, 2, 'Menyelesaikan task \"Desain Tampilan Utama\"', '2025-06-12 15:37:37'),
(9, 2, 'Membuat proyek \"a\"', '2025-06-14 13:03:22'),
(10, 2, 'Menambahkan task \"frontend\" untuk proyek ID 6', '2025-06-14 13:06:06'),
(11, 2, 'Menambahkan task \"backend\" untuk proyek ID 6', '2025-06-14 13:06:27'),
(12, 2, 'Menyelesaikan task \"backend\"', '2025-06-14 13:06:30'),
(13, 1, 'Menyelesaikan task \"frontend\"', '2025-06-14 13:06:31'),
(14, 2, 'Menambahkan task \"a\" untuk proyek ID 6', '2025-06-14 13:06:47'),
(15, 1, 'Menyelesaikan task \"a\"', '2025-06-14 13:06:58'),
(16, 2, 'Membuat proyek \"b\"', '2025-06-14 14:31:41'),
(17, 2, 'Menambahkan task \"b\" untuk proyek ID 7', '2025-06-14 14:32:01'),
(18, 1, 'Menyelesaikan task \"b\"', '2025-06-14 14:32:05'),
(19, 2, 'Menambahkan task \"c\" untuk proyek ID 7', '2025-06-14 14:32:56'),
(20, 2, 'Menambahkan task \"frontend\" untuk proyek ID 7', '2025-06-14 14:33:11'),
(21, 2, 'Menambahkan task \"backend\" untuk proyek ID 7', '2025-06-14 14:33:37'),
(22, 2, 'Menambahkan task \"backend\" untuk proyek ID 7', '2025-06-14 14:33:57'),
(23, 3, 'Menyelesaikan task \"Riset Kebutuhan Sekolah\"', '2025-06-14 15:12:13'),
(24, 1, 'Menyelesaikan task \"c\"', '2025-06-14 15:12:38'),
(25, 2, 'Menyelesaikan task \"backend\"', '2025-06-14 15:12:42'),
(26, 2, 'Membuat proyek \"bram\"', '2025-06-14 16:54:50'),
(27, 2, 'Menghapus proyek \"bram\" (ID: 8)', '2025-06-14 17:12:59'),
(28, 2, 'Membuat proyek \"bram trigger\"', '2025-06-14 17:23:25'),
(29, 2, 'Menghapus proyek \"bram trigger\" (ID: 9)', '2025-06-14 17:23:35'),
(30, 2, 'Membuat proyek \"bram\"', '2025-06-14 17:29:18'),
(31, 2, 'Membuat proyek \"bram2\"', '2025-06-14 17:31:45'),
(32, 2, 'Menghapus proyek \"bram2\" (ID: 11)', '2025-06-14 17:31:51'),
(33, 2, 'Menghapus proyek \"bram\" (ID: 10)', '2025-06-14 17:33:20'),
(34, 2, 'Membuat proyek \"bramahimsa\"', '2025-06-14 18:01:32'),
(35, 2, 'Menghapus proyek \"bramahimsa\" (ID: 12)', '2025-06-14 18:01:40'),
(36, 2, 'Menambahkan task \"ngoding prontend\" untuk proyek ID 4', '2025-06-15 02:28:07'),
(37, 2, 'Menyelesaikan task \"ngoding prontend\"', '2025-06-15 02:28:15'),
(38, 2, 'Menambahkan task \"lorem iosufhsfkjsnfs\" untuk proyek ID 4', '2025-06-15 02:28:43'),
(39, 2, 'Menyelesaikan task \"lorem iosufhsfkjsnfs\"', '2025-06-15 02:28:50'),
(40, 2, 'Menambahkan task \"asdasdasdas\" untuk proyek ID 4', '2025-06-15 02:29:03'),
(41, 2, 'Membuat proyek \"bram\"', '2025-06-15 02:44:19'),
(42, 2, 'Menghapus proyek ID 13', '2025-06-15 03:04:39'),
(43, 2, 'Menghapus proyek \"bram\" (ID: 13)', '2025-06-15 03:04:39'),
(44, 2, 'Membuat proyek \"raaefaef\"', '2025-06-15 03:07:58'),
(45, 2, 'Menghapus proyek ID 14', '2025-06-15 03:08:03'),
(46, 2, 'Menghapus proyek \"raaefaef\" (ID: 14)', '2025-06-15 03:08:03'),
(47, 2, 'Menyelesaikan task \"asdasdasdas\"', '2025-06-15 04:01:40'),
(48, 2, 'Menambahkan task \"bramahimsa\" untuk proyek ID 4', '2025-06-15 04:02:02'),
(49, 2, 'Menambahkan task \"bram\" untuk proyek ID 4', '2025-06-15 04:02:22');

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE `projects` (
  `id` int NOT NULL,
  `nama_projek` varchar(150) DEFAULT NULL,
  `deskripsi` text,
  `user_id` int DEFAULT NULL,
  `deadline` date DEFAULT NULL,
  `status` enum('planning','ongoing','done','canceled') DEFAULT NULL,
  `dibuat_pada` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`id`, `nama_projek`, `deskripsi`, `user_id`, `deadline`, `status`, `dibuat_pada`) VALUES
(1, 'Website Portofolio', 'Pembuatan website pribadi untuk showcase pekerjaan', 1, '2025-06-15', 'ongoing', '2025-06-01 21:05:06'),
(2, 'Sistem Inventaris Sekolah', 'Aplikasi pencatatan aset dan stok barang sekolah', 2, '2025-07-01', 'planning', '2025-06-01 21:05:06'),
(3, 'Aplikasi Manajemen Tugas', 'Task tracker berbasis web untuk kerja tim', 1, '2025-06-20', 'planning', '2025-06-01 21:05:06'),
(4, 'coba', 'coba', 2, '2025-06-12', 'planning', '2025-06-12 15:21:21'),
(5, 'buat pdt', 'pdt uas\r\n', 1, NULL, NULL, '2025-06-12 23:40:50'),
(6, 'a', 'a', 2, '2025-06-14', 'planning', '2025-06-14 13:03:21'),
(7, 'b', 'b', 2, '2025-06-14', 'planning', '2025-06-14 14:31:41');

--
-- Triggers `projects`
--
DELIMITER $$
CREATE TRIGGER `log_project_delete` AFTER DELETE ON `projects` FOR EACH ROW BEGIN
  INSERT INTO activity_logs (user_id, aksi, waktu)
  VALUES (
    IFNULL(OLD.user_id, 0),
    CONCAT('Menghapus proyek "', IFNULL(OLD.nama_projek, 'Tidak diketahui'), '" (ID: ', OLD.id, ')'),
    NOW()
  );
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `id` int NOT NULL,
  `project_id` int DEFAULT NULL,
  `judul` varchar(150) DEFAULT NULL,
  `deskripsi` text,
  `assigned_to` int DEFAULT NULL,
  `status` enum('todo','in_progress','done') DEFAULT NULL,
  `deadline` date DEFAULT NULL,
  `dibuat_pada` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`id`, `project_id`, `judul`, `deskripsi`, `assigned_to`, `status`, `deadline`, `dibuat_pada`) VALUES
(1, 1, 'Desain Tampilan Utama', 'Buat mockup tampilan menggunakan Figma', 2, 'done', '2025-06-10', '2025-06-01 21:05:52'),
(2, 1, 'Koding Frontend', 'Implementasi tampilan HTML/CSS/JS', 3, 'todo', '2025-06-13', '2025-06-01 21:05:52'),
(3, 2, 'Riset Kebutuhan Sekolah', 'Tanya ke pihak sekolah soal kebutuhan barang', 3, 'done', '2025-06-05', '2025-06-14 15:12:13'),
(4, 3, 'Setup Database Awal', 'Buat struktur tabel dan relasi', 1, 'todo', '2025-06-03', '2025-06-01 21:05:52'),
(5, 1, 'Testing Backend', 'Unit test dan integrasi', 2, 'todo', '2025-06-13', '2025-06-12 15:16:46'),
(6, 1, 'coba', 'coba', 2, 'todo', '2025-06-12', '2025-06-12 15:28:24'),
(7, 6, 'frontend', 'tolong dikerjakan', 1, 'done', '2025-06-14', '2025-06-14 13:06:31'),
(8, 6, 'backend', 'kerjakan', 2, 'done', '2025-06-14', '2025-06-14 13:06:30'),
(9, 6, 'a', 'a', 1, 'done', '2025-06-14', '2025-06-14 13:06:58'),
(10, 7, 'b', 'b', 1, 'done', '2025-06-14', '2025-06-14 14:32:05'),
(11, 7, 'c', 'c', 1, 'done', '2025-06-14', '2025-06-14 15:12:38'),
(12, 7, 'frontend', 'f', 2, 'todo', '2025-07-04', '2025-06-14 14:33:11'),
(13, 7, 'backend', 'r', 3, 'todo', '2025-06-14', '2025-06-14 14:33:37'),
(14, 7, 'backend', 'd', 2, 'done', '2025-06-16', '2025-06-14 15:12:42'),
(15, 4, 'ngoding prontend', 'lorem ipsum sahur tung tungtung', 2, 'done', '2025-06-16', '2025-06-15 02:28:15'),
(16, 4, 'lorem iosufhsfkjsnfs', 'dfasfsaddfsadf', 2, 'done', '2025-06-13', '2025-06-15 02:28:50'),
(17, 4, 'asdasdasdas', 'dasdasdsadad', 2, 'done', '2025-06-16', '2025-06-15 04:01:40'),
(18, 4, 'bramahimsa', 'bram', 2, 'todo', '2025-06-19', '2025-06-15 04:02:02'),
(19, 4, 'bram', 'bram', 1, 'todo', '2025-07-01', '2025-06-15 04:02:22');

--
-- Triggers `tasks`
--
DELIMITER $$
CREATE TRIGGER `after_task_update` AFTER UPDATE ON `tasks` FOR EACH ROW BEGIN
    IF NEW.status = 'done' AND OLD.status <> 'done' THEN
        INSERT INTO activity_logs(user_id, aksi)
        VALUES (NEW.assigned_to, CONCAT('Menyelesaikan task "', NEW.judul, '"'));
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` enum('admin','user') DEFAULT NULL,
  `dibuat_pada` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `nama`, `email`, `password`, `role`, `dibuat_pada`) VALUES
(1, 'Wibi', 'wibi@example.com', 'hashed_password_1', 'admin', '2025-06-01 21:03:34'),
(2, 'Ady', 'ady@example.com', '123', 'user', '2025-06-01 21:03:34'),
(3, 'Ilham', 'ilham@example.com', 'hashed_password_3', 'user', '2025-06-01 21:03:34');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `project_id` (`project_id`),
  ADD KEY `assigned_to` (`assigned_to`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD CONSTRAINT `activity_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `projects`
--
ALTER TABLE `projects`
  ADD CONSTRAINT `projects_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `tasks`
--
ALTER TABLE `tasks`
  ADD CONSTRAINT `tasks_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`),
  ADD CONSTRAINT `tasks_ibfk_2` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
