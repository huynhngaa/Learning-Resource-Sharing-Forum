-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 19, 2024 at 03:32 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.1.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `luanvan`
--

-- --------------------------------------------------------

--
-- Table structure for table `bai_viet`
--

CREATE TABLE `bai_viet` (
  `bv_ma` int(11) NOT NULL,
  `dm_ma` int(11) NOT NULL,
  `nd_username` varchar(50) NOT NULL,
  `bv_tieude` varchar(100) NOT NULL,
  `bv_noidung` text NOT NULL,
  `bv_ngaydang` datetime NOT NULL DEFAULT current_timestamp(),
  `bv_luotxem` int(11) DEFAULT 0,
  `bv_diemtrungbinh` float DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;

--
-- Dumping data for table `bai_viet`
--

INSERT INTO `bai_viet` (`bv_ma`, `dm_ma`, `nd_username`, `bv_tieude`, `bv_noidung`, `bv_ngaydang`, `bv_luotxem`, `bv_diemtrungbinh`) VALUES
(194, 68, 'nga', 'Tại sao phải tách từ cho Tiếng Việt?', '<p>Mục tiêu của việc tách từ văn bản đầu vào là để khử tính nhập nhằng về ngữ nghĩa của văn bản. Tùy vào từng loại ngôn ngữ có những đặc điểm khác nhau mà việc tách từ văn bản cũng có độ khó khăn khác nhau. Đựa theo đặc điểm của ngôn ngữ tự nhiên mà ngôn ngữ được phân thành các loại:</p><ol><li><strong>Ngôn ngữ hòa kết (flexional)</strong>, ví dụ: Đức, Latin, Hi Lạp, Anh, Nga.</li><li><strong>Ngôn ngữ chắp dính (agglutinate)</strong>, ví dụ: Thổ Nhĩ Kỳ, Mông Cổ, Nhật Bản, Triều Tiên.</li><li><strong>Ngôn ngữ đơn lập (isolate)</strong>, là ngôn ngữ phi hình thái, không biến hình, đơn âm tiết, ví dụ: Việt Nam, Hán.</li></ol><p>Với ngôn ngữ hòa kết như Tiếng Anh, thì việc tách từ khá đơn giản vì ranh giới từ được nhận diện bằng khoảng trắng và dấu câu.</p>', '2023-12-13 03:05:07', 3, 2),
(195, 68, 'nga', 'MongoDB là gì? Cơ sở dữ liệu phi quan hệ ', '<ul><li>MongoDB là một hệ quản trị cơ sở dữ liệu mã nguồn mở, là CSDL thuộc NoSql và được hàng triệu người sử dụng.</li><li>MongoDB là một database hướng tài liệu (document), các dữ liệu được lưu trữ trong document kiểu JSON thay vì dạng bảng như CSDL quan hệ nên truy vấn sẽ rất nhanh.</li><li>Với CSDL quan hệ chúng ta có khái niệm bảng, các cơ sở dữ liệu quan hệ (như MySQL hay SQL Server...) sử dụng các bảng để lưu dữ liệu thì với MongoDB chúng ta sẽ dùng khái niệm là <strong>collection</strong> thay vì bảng</li></ul>', '2023-12-13 03:19:38', 2, 0),
(196, 50, 'quyen', 'Đặc điểm của hoạt động xét xử của Tòa án ', '<p><span style=\"background-color:rgb(255,255,255);color:rgb(51,51,51);\">Xét xử là hoạt động phán quyết của cơ quan thay mặt Nhà nước nhằm khôi phục trật tự nếu nó bị xâm phạm, hoặc nhằm bảo vệ lợi ích hợp pháp và chính đáng của công dân, của tập thể, của quốc gia và xã hội.&nbsp;</span></p><p><span style=\"background-color:rgb(255,255,255);color:rgb(51,51,51);\">Vì vậy, đây là </span><i>một hoạt động quyền lực nhà nước</i><span style=\"background-color:rgb(255,255,255);color:rgb(51,51,51);\"> đặc thù, nó không đơn thuần chỉ là dàn xếp, hòa giải, mặc dù về thực chất, dàn xếp và hòa giải cũng có mục đích như vậy và do đó, có mối liên quan khăng khít với hoạt động xét xử.</span></p>', '2023-12-13 16:42:05', 2, 3),
(197, 48, 'nga', 'TRANH CHẤP MÔI TRƯỜNG ĐƯỢC GIẢI QUYẾT NHƯ THẾ NÀO? ', '<p><span style=\"background-color:rgb(255,255,255);color:rgb(0,0,0);\">Tranh chấp môi trường là những xung đột giữa các cơ quan, tổ chức, cá nhân, các cộng đồng dân cư về quyền và lợi ích liên quan đến việc phòng ngừa, khắc phục ô nhiễm, suy thoái, sự cố môi trường; về việc khai thác, sử dụng hợp lý các nguồn tài nguyên và môi trường; về quyền được sống trong môi trường trong lành và quyền được bảo vệ tính mạng, sức khỏe , tài sản do làm ô nhiễm môi trường gây nên.</span></p>', '2023-12-13 17:33:33', 1, 0),
(198, 47, 'quyen', 'TRANH CHẤP MÔI TRƯỜNG ', '<p><span style=\"color:rgb(41,128,185);font-family:Arial, Helvetica, sans-serif;\"><strong>1. Khái niệm tranh chấp môi trường</strong></span></p><p style=\"margin-left:0px;text-align:justify;\"><span style=\"font-family:Arial, Helvetica, sans-serif;\">Tranh chấp môi trường là những xung đột giữa các cơ quan, tổ chức, cá nhân, các cộng đồng dân cư về quyền và lợi ích liên quan đến việc phòng ngừa, khắc phục ô nhiễm, suy thoái, sự cố môi trường; về việc khai thác, sử dụng hợp lý các nguồn tài nguyên và môi trường; về quyền được sống trong môi trường trong lành và quyền được bảo vệ tính mạng, sức khỏe , tài sản do làm ô nhiễm môi trường gây nên.</span></p><p><span style=\"color:rgb(41,128,185);font-family:Arial, Helvetica, sans-serif;\"><strong>2. Dấu hiệu đặc trưng tranh chấp môi trường</strong></span></p><p style=\"margin-left:0px;text-align:justify;\"><span style=\"font-family:Arial, Helvetica, sans-serif;\">So với các tranh chấp khác, tranh chấp môi trường có một số đặc trưng như sau:</span></p><p style=\"margin-left:0px;text-align:justify;\"><span style=\"font-family:Arial, Helvetica, sans-serif;\">- Tranh chấp môi trường là xung đột mà trong đó lợi ích công và lợi ích tư thường gắn chặt với nhau.</span></p><p style=\"margin-left:0px;text-align:justify;\"><span style=\"font-family:Arial, Helvetica, sans-serif;\">- Tranh chấp môi trường thường xảy ra với quy mô lớn, liên quan đến nhiều tổ chức, cá nhân, các cộng đồng dân cư, thậm chí liên quan đến nhiều quốc gia.</span></p>', '2023-12-13 17:39:17', 0, 0),
(199, 48, 'quyen', 'Tranh chấp về môi trường được quy định ra sao? ', '<p><i>1. Nội dung tranh chấp về môi trường bao gồm:</i></p><p><i>a) Tranh chấp về quyền, trách nhiệm bảo vệ môi trường trong khai thác, sử dụng thành phần môi trường.</i></p><p><i>b) Tranh chấp về xác định nguyên nhân gây ô nhiễm, suy thoái, sự cố môi trường.</i></p><p><i>c) Tranh chấp về trách nhiệm xử lý, khắc phục hậu quả, bồi thường thiệt hại về môi trường.</i></p>', '2023-12-13 18:01:25', 0, 0),
(200, 45, 'quyen', 'Định nghĩa đầy đủ và chi tiết nhất về MongoDB ', '<p><strong>MongoDB</strong><span style=\"background-color:rgb(255,255,255);color:rgb(34,34,34);\"> là một database hướng tài liệu (document), một dạng NoSQL database. Vì thế, MongoDB sẽ tránh cấu trúc table-based của relational database để thích ứng với các tài liệu như JSON có một schema rất linh hoạt gọi là BSON. </span><strong>MongoDB</strong><span style=\"background-color:rgb(255,255,255);color:rgb(34,34,34);\"> sử dụng lưu trữ dữ liệu dưới dạng Document JSON nên mỗi một collection sẽ các các kích cỡ và các document khác nhau. Các dữ liệu được lưu trữ trong document&nbsp;kiểu JSON nên truy vấn sẽ rất nhanh.</span></p>', '2023-12-13 19:09:29', 0, 0),
(201, 45, 'quyen', 'Định nghĩa thêm về MongoDB', '<p><strong>MongoDB</strong><span style=\"background-color:rgb(255,255,255);color:rgb(34,34,34);\"> là một database hướng tài liệu (document), một dạng NoSQL database. Vì thế, MongoDB sẽ tránh cấu trúc table-based của relational database để thích ứng với các tài liệu như JSON có một schema rất linh hoạt gọi là BSON. </span><strong>MongoDB</strong><span style=\"background-color:rgb(255,255,255);color:rgb(34,34,34);\"> sử dụng lưu trữ dữ liệu dưới dạng Document JSON nên mỗi một collection sẽ các các kích cỡ và các document khác nhau. Các dữ liệu được lưu trữ trong document&nbsp;kiểu JSON nên truy vấn sẽ rất nhanh.</span></p>', '2023-12-13 19:10:08', 0, 0),
(202, 68, 'quyen', 'Tách từ Tiếng Việt', '<ul><li>Đồ thị hoá: Xây dựng một đồ thị biểu diễn câu và giải bài toán tìm đường đi ngắn nhất trên đồ thị.</li><li>Gán nhãn: Coi như bài toán gán nhãn chuỗi. Cách này được sử dụng trong JVNSegmenter<sup>[1]</sup>, Đông du<sup>[2]</sup>.</li><li>Dùng mô hình ngôn ngữ: Cho trước một số cách tách từ của toàn bộ câu, một mô hình ngôn ngữ có thể đánh giá được cách nào có khả năng cao hơn. Đây là cách tiếp cận của vnTokenizer<sup>[3]</sup>.</li></ul>', '2023-12-13 19:11:49', 0, 0),
(203, 45, 'quyen', 'Khi nào sử dụng MongoDB?', '<ul><li><strong>Quản lý và truyền tải content</strong>&nbsp;– Quản lý đa dạng nhiều product của content chỉ trong một kho lưu trữ data cho phép thay đổi và phản hồi nhanh chóng mà không chịu thêm phức tạp thêm từ hệ thống content.</li></ul>', '2023-12-13 19:13:53', 0, 0),
(204, 68, 'quyen', 'Ưu điểm của MongoDB', '<ul><li>Dữ liệu lưu trữ phi cấu trúc, không có tính ràng buộc, toàn vẹn nên tính sẵn sàng cao, hiệu suất lớn và dễ dàng mở rộng lưu trữ.</li><li>Dữ liệu được caching (ghi đệm) lên RAM, hạn chế truy cập vào ổ cứng nên tốc độ đọc và ghi cao.</li></ul>', '2023-12-13 19:14:33', 0, 0),
(205, 68, 'quyen', 'Phương pháp mô hình ngôn ngữ', '<p style=\"margin-left:0px;\">Một mô hình ngôn ngữ cố gắng nắm bắt trực giác của con người về một câu \"tự nhiên\" hoặc \"không tự nhiên\" do đó mô hình ngôn ngữ có thể coi là giải pháp tối hậu cho bài toán tách từ.</p><p style=\"margin-left:0px;\">Số cách tách từ cho một câu có thể rất lớn do sự bùng nổ tổ hợp nên cần có một bước xử lý để lọc ra một số lượng vừa đủ các cách tách từ làm đầu vào cho mô hình ngôn ngữ. Chẳng hạn vnTokenizer sử dụng phương pháp đồ thị hoá trước khi áp dụng mô hình ngôn ngữ.</p>', '2023-12-13 19:27:18', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `binh_luan`
--

CREATE TABLE `binh_luan` (
  `bl_ma` int(11) NOT NULL,
  `bv_ma` int(11) NOT NULL,
  `nd_username` varchar(50) NOT NULL,
  `bl_noidung` text NOT NULL,
  `trangthai` int(11) NOT NULL DEFAULT 1,
  `bl_thoigian` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;

--
-- Dumping data for table `binh_luan`
--

INSERT INTO `binh_luan` (`bl_ma`, `bv_ma`, `nd_username`, `bl_noidung`, `trangthai`, `bl_thoigian`) VALUES
(124, 194, 'nga', 'hahahhahah ', 1, '2023-12-27 00:32:30'),
(125, 194, 'nga', 'hiihihiihihh', 1, '2023-12-27 00:32:35'),
(126, 196, 'nga', 'g', 1, '2023-12-27 00:52:01'),
(127, 196, 'nga', 'g', 1, '2023-12-27 00:52:03'),
(128, 196, 'nga', 'g', 1, '2023-12-27 00:52:06');

-- --------------------------------------------------------

--
-- Table structure for table `co_quyen`
--

CREATE TABLE `co_quyen` (
  `vt_ma` int(11) NOT NULL,
  `q_ma` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;

-- --------------------------------------------------------

--
-- Table structure for table `danhmuc_phancap`
--

CREATE TABLE `danhmuc_phancap` (
  `dm_cha` int(11) NOT NULL,
  `dm_con` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;

-- --------------------------------------------------------

--
-- Table structure for table `danh_gia`
--

CREATE TABLE `danh_gia` (
  `dg_ma` int(11) NOT NULL,
  `bv_ma` int(11) NOT NULL,
  `nd_username` varchar(50) NOT NULL,
  `dg_diem` int(11) NOT NULL,
  `dg_thoigian` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;

--
-- Dumping data for table `danh_gia`
--

INSERT INTO `danh_gia` (`dg_ma`, `bv_ma`, `nd_username`, `dg_diem`, `dg_thoigian`) VALUES
(18, 194, 'nga', 2, '2023-12-27 00:32:55'),
(19, 196, 'nga', 3, '2023-12-27 00:32:43');

-- --------------------------------------------------------

--
-- Table structure for table `danh_muc`
--

CREATE TABLE `danh_muc` (
  `dm_ma` int(11) NOT NULL,
  `mh_ma` int(11) NOT NULL,
  `dm_ten` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;

--
-- Dumping data for table `danh_muc`
--

INSERT INTO `danh_muc` (`dm_ma`, `mh_ma`, `dm_ten`) VALUES
(45, 4, 'CSDL tài liệu MongoDB'),
(46, 4, 'CSDL đồ thị Neo4j'),
(47, 30, 'Giải quyết tranh chấp về môi trường'),
(48, 30, 'Xử lý vi phạm pháp luật về môi trường'),
(49, 29, 'Truy cứu trách nhiệm hành chính'),
(50, 29, 'Hoạt động xét xử của tòa án'),
(51, 33, 'Sinh lý bệnh quá trình viêm'),
(52, 31, 'Các loại thức ăn gia súc'),
(53, 31, 'Dự trữ và chế biến thức ăn gia súc'),
(54, 35, 'Chiến lược quản trị quan hệ khách hàng'),
(55, 35, 'Các cấp độ quản trị quan hệ khách hàng'),
(56, 34, 'Giá trị thời gian của tiền tệ'),
(57, 34, 'Định giá chứng khoán '),
(58, 20, 'Quy trình quản lý sản xuất'),
(59, 20, 'Quy trình giám sát nguồn gốc sản phẩm'),
(62, 23, 'Kích thích sinh sản cá bố mẹ '),
(63, 23, 'Quản lý phát triển phôi'),
(64, 24, 'Tập tính lãnh thổ'),
(65, 24, 'Tập tính di cư'),
(66, 27, 'Sự vận chuyển xa ở mạch gỗ và mạch libe'),
(67, 27, 'Dưỡng chất khoáng vi lượng'),
(68, 3, 'Các phương pháp phân tích từ');

-- --------------------------------------------------------

--
-- Table structure for table `khoi_lop`
--

CREATE TABLE `khoi_lop` (
  `kl_ma` int(11) NOT NULL,
  `kl_ten` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;

--
-- Dumping data for table `khoi_lop`
--

INSERT INTO `khoi_lop` (`kl_ma`, `kl_ten`) VALUES
(1, 'Công nghệ thông tin'),
(2, 'Hệ thống thông tin'),
(5, 'Quản trị kinh doanh'),
(6, 'Luật kinh tế'),
(7, 'Thú y'),
(8, 'Bảo vệ thực vật'),
(10, 'Nuôi trồng thủy sản');

-- --------------------------------------------------------

--
-- Table structure for table `kiem_duyet`
--

CREATE TABLE `kiem_duyet` (
  `bv_ma` int(11) NOT NULL,
  `nd_username` varchar(50) NOT NULL,
  `tt_ma` int(11) NOT NULL,
  `thoigian` datetime NOT NULL DEFAULT current_timestamp(),
  `ghi_chu` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;

--
-- Dumping data for table `kiem_duyet`
--

INSERT INTO `kiem_duyet` (`bv_ma`, `nd_username`, `tt_ma`, `thoigian`, `ghi_chu`) VALUES
(194, 'admin', 1, '2023-12-13 03:20:34', NULL),
(195, 'admin', 1, '2023-12-13 03:20:34', NULL),
(196, 'admin', 1, '2023-12-13 16:44:04', NULL),
(197, 'admin', 1, '2023-12-13 17:36:45', NULL),
(198, 'admin', 4, '2023-12-13 17:39:41', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `lich_su_xem`
--

CREATE TABLE `lich_su_xem` (
  `nd_username` varchar(50) NOT NULL,
  `bv_ma` int(11) DEFAULT NULL,
  `bl_ma` int(11) DEFAULT NULL,
  `ls_thoigian` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;

--
-- Dumping data for table `lich_su_xem`
--

INSERT INTO `lich_su_xem` (`nd_username`, `bv_ma`, `bl_ma`, `ls_thoigian`) VALUES
('nga', 195, NULL, '2023-12-13 03:20:57'),
('nga', 194, NULL, '2023-12-13 03:21:16'),
('nga', 196, NULL, '2023-12-13 16:44:54'),
('quyen', 197, NULL, '2023-12-13 17:38:20'),
('quyen', 196, NULL, '2023-12-13 17:41:06'),
('quyen', 194, NULL, '2023-12-13 18:02:42'),
('quyen', 195, NULL, '2023-12-13 18:58:59'),
('admin', 194, NULL, '2023-12-27 00:39:56');

-- --------------------------------------------------------

--
-- Table structure for table `mon_hoc`
--

CREATE TABLE `mon_hoc` (
  `mh_ma` int(11) NOT NULL,
  `mh_ten` varchar(50) NOT NULL,
  `kl_ma` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;

--
-- Dumping data for table `mon_hoc`
--

INSERT INTO `mon_hoc` (`mh_ma`, `mh_ten`, `kl_ma`) VALUES
(3, 'Xử lý ngôn ngữ tự nhiên', 1),
(4, 'Cơ sở dữ liệu NoSQL', 2),
(20, 'Hệ thống quản lý sản xuất', 2),
(23, 'Kỹ thuật sản xuất giống cá nước ngọt', 10),
(24, 'Tập tính động vật thủy sản', 10),
(27, 'Dinh dưỡng cây trồng', 8),
(29, 'Luật hành chính', 6),
(30, 'Luật môi trường', 6),
(31, 'Thức ăn gia súc', 7),
(33, 'Sinh lý bệnh Thú y', 7),
(34, 'Quản trị tài chính', 5),
(35, 'Quản trị quan hệ khách hàng', 5);

-- --------------------------------------------------------

--
-- Table structure for table `nguoi_dung`
--

CREATE TABLE `nguoi_dung` (
  `nd_username` varchar(50) NOT NULL,
  `vt_ma` int(11) NOT NULL,
  `nd_hoten` varchar(50) NOT NULL,
  `nd_gioitinh` tinyint(1) NOT NULL,
  `nd_email` varchar(100) NOT NULL,
  `nd_sdt` varchar(10) NOT NULL,
  `nd_matkhau` varchar(200) NOT NULL,
  `nd_diachi` varchar(100) NOT NULL,
  `nd_ngaysinh` date NOT NULL,
  `nd_hinh` text NOT NULL,
  `nd_ngaytao` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;

--
-- Dumping data for table `nguoi_dung`
--

INSERT INTO `nguoi_dung` (`nd_username`, `vt_ma`, `nd_hoten`, `nd_gioitinh`, `nd_email`, `nd_sdt`, `nd_matkhau`, `nd_diachi`, `nd_ngaysinh`, `nd_hinh`, `nd_ngaytao`) VALUES
('AD01', 2, 'Lê Xuân', 0, '', '', '$2y$10$96U2ohw38iz1FdtBgTNgSOiHNKHvLaD7.UKO5cwUGVT2Y4fUiS6CG', '', '0000-00-00', 'unnamed.png', '2023-09-20 18:01:41'),
('AD02', 2, 'Trần Thị Trúc Quyên', 0, '', '', '$2y$10$07i2kBTwKCFtUC9xnxRsrefx937Ww3f/cve9U8f.wB2y6rBWdq7Ze', '', '0000-00-00', 'unnamed.png', '2023-11-08 01:37:35'),
('AD03', 2, 'Hoàng', 0, '', '', '$2y$10$v15ap2IS0cXht5YMjbHiVOT9Rvsi6JyNi8OwUCys0EPmvPxVvXbZG', '', '0000-00-00', 'unnamed.png', '2023-11-10 13:54:58'),
('AD04', 2, 'Tú', 0, '', '', '$2y$10$8eQNfBF4EYLBESGODJeBru9AA5rCZ5sr7U9f5XOyUi7y.b/YK1Bk.', '', '0000-00-00', 'unnamed.png', '2023-11-10 13:55:14'),
('AD05', 2, 'Liên', 0, '', '', '$2y$10$PQpmDi77SZ16BkdEd9p1meqc0O8wFovVGNgjpmp3.84jcLJ94LWqm', '', '0000-00-00', 'unnamed.png', '2023-11-10 13:55:32'),
('AD06', 2, 'Dung', 0, '', '', '$2y$10$qFTBf0kfVoRPoREtnUxBKucIudPrrHHlrjFoMry5cs93wfPAYaPx6', '', '0000-00-00', 'unnamed.png', '2023-11-10 13:55:49'),
('admin', 1, 'Quyên Trần', 0, '', '', '$2y$10$Al9koUhyGEIJFPguCHkB8.CZ7aiOnkzyzJGjCiaONAYBYWOpGAFoO', '', '0000-00-00', 'unnamed.png', '2023-09-20 19:25:55'),
('GV01', 3, 'Trần Văn Lành', 0, '', '', '$2y$10$dDNMpLBltb20e/pterv0R./vezIIDnz7Xli5lR1hZbJKVqzIWgE8O', '', '0000-00-00', 'unnamed.png', '2023-09-20 18:00:52'),
('gv010', 3, 'Khối lớp  3', 0, '', '', '$2y$10$3Gc2kIcP6Sa7u4oFNndXsezYjnqY0T9TDZpzqP8CWTZ3lFxDQ.CeW', '', '0000-00-00', 'unnamed.png', '2023-10-17 17:56:41'),
('HS01', 4, 'Nguyễn Văn Tùng', 0, '', '', '$2y$10$l6IMWGnovMlr4H4VRZOwM.HyBtwV.9tsNgfYxGYGFRymG5TXcAVVC', '', '0000-00-00', 'unnamed.png', '2023-09-20 18:01:20'),
('HS0100', 4, 'Ba Phi', 0, '', '', '$2y$10$atc/yWqGuKOb7NguBhg9DeHhqPkjZeujOZJ53GaQXkBbVXm9sCPhq', '', '0000-00-00', 'unnamed.png', '2023-10-13 17:02:55'),
('nga', 3, 'Nguyễn Thị Huỳnh Nga', 1, 'nga@gmail.com', '0123456789', '$2y$10$JihqG98MFby.KGVYajZKN.FPIAEtBajKJb10k32BMF1l.daSS9Vke', 'cần thơ ', '2023-08-10', '560165.png', '2022-12-19 23:52:06'),
('quyen', 4, 'Trần Thị Trúc Quyên', 1, 'quyen@gmail.com', '0123456987', '$2y$10$G.hPL8hFxjPudPgfz54AwOxCuD8Vd.Xf7ONEkrb/gHGZ6AlxLS2ZW', 'cần thơ', '2023-08-11', 'avt2.jpg', '2022-10-18 23:51:44');

-- --------------------------------------------------------

--
-- Table structure for table `quan_ly`
--

CREATE TABLE `quan_ly` (
  `dm_ma` int(11) NOT NULL,
  `nd_username` varchar(50) NOT NULL,
  `tg_phancong` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;

--
-- Dumping data for table `quan_ly`
--

INSERT INTO `quan_ly` (`dm_ma`, `nd_username`, `tg_phancong`) VALUES
(1, 'AD01', '2023-11-10 14:22:54'),
(1, 'AD02', '2023-11-10 14:22:54'),
(14, 'AD01', '2023-11-10 14:22:54'),
(19, 'AD01', '2023-11-10 14:22:54'),
(19, 'AD02', '2023-11-10 14:22:54'),
(20, 'AD01', '2023-11-10 14:22:54'),
(20, 'AD02', '2023-11-10 14:22:54'),
(21, 'AD02', '2023-11-10 14:22:54');

-- --------------------------------------------------------

--
-- Table structure for table `quyen`
--

CREATE TABLE `quyen` (
  `q_ma` int(11) NOT NULL,
  `q_ten` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rep_bl`
--

CREATE TABLE `rep_bl` (
  `bl_cha` int(11) NOT NULL,
  `bl_con` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tai_lieu`
--

CREATE TABLE `tai_lieu` (
  `tl_ma` int(11) NOT NULL,
  `bv_ma` int(11) NOT NULL,
  `tl_tentaptin` varchar(100) NOT NULL,
  `tl_kichthuoc` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;

--
-- Dumping data for table `tai_lieu`
--

INSERT INTO `tai_lieu` (`tl_ma`, `bv_ma`, `tl_tentaptin`, `tl_kichthuoc`) VALUES
(97, 194, 'taisaotachtu.pdf', 30),
(98, 195, 'mongodb.pdf', 30),
(99, 196, 'xetxuphapluat.pdf', 30),
(102, 199, 'noidungtranhchap.pdf', 30),
(103, 200, 'mongodb.pdf', 30),
(106, 204, 'mongodb.pdf', 30);

-- --------------------------------------------------------

--
-- Table structure for table `trang_thai`
--

CREATE TABLE `trang_thai` (
  `tt_ma` int(11) NOT NULL,
  `tt_ten` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;

--
-- Dumping data for table `trang_thai`
--

INSERT INTO `trang_thai` (`tt_ma`, `tt_ten`) VALUES
(1, 'Đã duyệt'),
(2, 'Đã bị hủy'),
(3, 'Chờ duyệt'),
(4, 'Đã bị xóa');

-- --------------------------------------------------------

--
-- Table structure for table `vai_tro`
--

CREATE TABLE `vai_tro` (
  `vt_ma` int(11) NOT NULL,
  `vt_ten` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;

--
-- Dumping data for table `vai_tro`
--

INSERT INTO `vai_tro` (`vt_ma`, `vt_ten`) VALUES
(1, 'Super Admin'),
(2, 'Admin'),
(3, 'Giáo Viên'),
(4, 'Học Sinh');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bai_viet`
--
ALTER TABLE `bai_viet`
  ADD PRIMARY KEY (`bv_ma`),
  ADD KEY `dm_ma` (`dm_ma`),
  ADD KEY `nd_username` (`nd_username`);

--
-- Indexes for table `binh_luan`
--
ALTER TABLE `binh_luan`
  ADD PRIMARY KEY (`bl_ma`),
  ADD KEY `nd_username` (`nd_username`),
  ADD KEY `bv_ma` (`bv_ma`),
  ADD KEY `trangthai` (`trangthai`);

--
-- Indexes for table `co_quyen`
--
ALTER TABLE `co_quyen`
  ADD PRIMARY KEY (`vt_ma`,`q_ma`),
  ADD KEY `q_ma` (`q_ma`);

--
-- Indexes for table `danhmuc_phancap`
--
ALTER TABLE `danhmuc_phancap`
  ADD PRIMARY KEY (`dm_cha`,`dm_con`),
  ADD KEY `dm_con` (`dm_con`);

--
-- Indexes for table `danh_gia`
--
ALTER TABLE `danh_gia`
  ADD PRIMARY KEY (`dg_ma`),
  ADD KEY `bv_ma` (`bv_ma`),
  ADD KEY `nd_username` (`nd_username`);

--
-- Indexes for table `danh_muc`
--
ALTER TABLE `danh_muc`
  ADD PRIMARY KEY (`dm_ma`),
  ADD KEY `mh_ma` (`mh_ma`);

--
-- Indexes for table `khoi_lop`
--
ALTER TABLE `khoi_lop`
  ADD PRIMARY KEY (`kl_ma`);

--
-- Indexes for table `kiem_duyet`
--
ALTER TABLE `kiem_duyet`
  ADD PRIMARY KEY (`bv_ma`,`nd_username`,`tt_ma`),
  ADD KEY `nd_username` (`nd_username`),
  ADD KEY `tt_ma` (`tt_ma`);

--
-- Indexes for table `lich_su_xem`
--
ALTER TABLE `lich_su_xem`
  ADD KEY `bl_ma` (`bl_ma`),
  ADD KEY `bv_ma` (`bv_ma`),
  ADD KEY `nd_username` (`nd_username`);

--
-- Indexes for table `mon_hoc`
--
ALTER TABLE `mon_hoc`
  ADD PRIMARY KEY (`mh_ma`),
  ADD KEY `kl_ma` (`kl_ma`);

--
-- Indexes for table `nguoi_dung`
--
ALTER TABLE `nguoi_dung`
  ADD PRIMARY KEY (`nd_username`),
  ADD KEY `vt_ma` (`vt_ma`);

--
-- Indexes for table `quan_ly`
--
ALTER TABLE `quan_ly`
  ADD PRIMARY KEY (`dm_ma`,`nd_username`),
  ADD KEY `nd_username` (`nd_username`);

--
-- Indexes for table `quyen`
--
ALTER TABLE `quyen`
  ADD PRIMARY KEY (`q_ma`);

--
-- Indexes for table `rep_bl`
--
ALTER TABLE `rep_bl`
  ADD PRIMARY KEY (`bl_cha`,`bl_con`),
  ADD KEY `bl_con` (`bl_con`);

--
-- Indexes for table `tai_lieu`
--
ALTER TABLE `tai_lieu`
  ADD PRIMARY KEY (`tl_ma`),
  ADD KEY `bv_ma` (`bv_ma`);

--
-- Indexes for table `trang_thai`
--
ALTER TABLE `trang_thai`
  ADD PRIMARY KEY (`tt_ma`);

--
-- Indexes for table `vai_tro`
--
ALTER TABLE `vai_tro`
  ADD PRIMARY KEY (`vt_ma`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bai_viet`
--
ALTER TABLE `bai_viet`
  MODIFY `bv_ma` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=206;

--
-- AUTO_INCREMENT for table `binh_luan`
--
ALTER TABLE `binh_luan`
  MODIFY `bl_ma` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=129;

--
-- AUTO_INCREMENT for table `danh_gia`
--
ALTER TABLE `danh_gia`
  MODIFY `dg_ma` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `danh_muc`
--
ALTER TABLE `danh_muc`
  MODIFY `dm_ma` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT for table `khoi_lop`
--
ALTER TABLE `khoi_lop`
  MODIFY `kl_ma` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `mon_hoc`
--
ALTER TABLE `mon_hoc`
  MODIFY `mh_ma` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `quyen`
--
ALTER TABLE `quyen`
  MODIFY `q_ma` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tai_lieu`
--
ALTER TABLE `tai_lieu`
  MODIFY `tl_ma` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=107;

--
-- AUTO_INCREMENT for table `trang_thai`
--
ALTER TABLE `trang_thai`
  MODIFY `tt_ma` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `vai_tro`
--
ALTER TABLE `vai_tro`
  MODIFY `vt_ma` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bai_viet`
--
ALTER TABLE `bai_viet`
  ADD CONSTRAINT `bai_viet_ibfk_1` FOREIGN KEY (`dm_ma`) REFERENCES `danh_muc` (`dm_ma`),
  ADD CONSTRAINT `bai_viet_ibfk_2` FOREIGN KEY (`nd_username`) REFERENCES `nguoi_dung` (`nd_username`);

--
-- Constraints for table `binh_luan`
--
ALTER TABLE `binh_luan`
  ADD CONSTRAINT `binh_luan_ibfk_1` FOREIGN KEY (`bv_ma`) REFERENCES `bai_viet` (`bv_ma`),
  ADD CONSTRAINT `binh_luan_ibfk_2` FOREIGN KEY (`nd_username`) REFERENCES `nguoi_dung` (`nd_username`),
  ADD CONSTRAINT `binh_luan_ibfk_3` FOREIGN KEY (`trangthai`) REFERENCES `trang_thai` (`tt_ma`);

--
-- Constraints for table `co_quyen`
--
ALTER TABLE `co_quyen`
  ADD CONSTRAINT `co_quyen_ibfk_1` FOREIGN KEY (`q_ma`) REFERENCES `quyen` (`q_ma`),
  ADD CONSTRAINT `co_quyen_ibfk_2` FOREIGN KEY (`vt_ma`) REFERENCES `vai_tro` (`vt_ma`);

--
-- Constraints for table `danhmuc_phancap`
--
ALTER TABLE `danhmuc_phancap`
  ADD CONSTRAINT `danhmuc_phancap_ibfk_1` FOREIGN KEY (`dm_cha`) REFERENCES `danh_muc` (`dm_ma`),
  ADD CONSTRAINT `danhmuc_phancap_ibfk_2` FOREIGN KEY (`dm_con`) REFERENCES `danh_muc` (`dm_ma`),
  ADD CONSTRAINT `danhmuc_phancap_ibfk_3` FOREIGN KEY (`dm_cha`) REFERENCES `danh_muc` (`dm_ma`);

--
-- Constraints for table `danh_gia`
--
ALTER TABLE `danh_gia`
  ADD CONSTRAINT `danh_gia_ibfk_1` FOREIGN KEY (`bv_ma`) REFERENCES `bai_viet` (`bv_ma`),
  ADD CONSTRAINT `danh_gia_ibfk_2` FOREIGN KEY (`nd_username`) REFERENCES `nguoi_dung` (`nd_username`);

--
-- Constraints for table `danh_muc`
--
ALTER TABLE `danh_muc`
  ADD CONSTRAINT `danh_muc_ibfk_1` FOREIGN KEY (`mh_ma`) REFERENCES `mon_hoc` (`mh_ma`);

--
-- Constraints for table `kiem_duyet`
--
ALTER TABLE `kiem_duyet`
  ADD CONSTRAINT `kiem_duyet_ibfk_1` FOREIGN KEY (`nd_username`) REFERENCES `nguoi_dung` (`nd_username`),
  ADD CONSTRAINT `kiem_duyet_ibfk_2` FOREIGN KEY (`bv_ma`) REFERENCES `bai_viet` (`bv_ma`),
  ADD CONSTRAINT `kiem_duyet_ibfk_3` FOREIGN KEY (`tt_ma`) REFERENCES `trang_thai` (`tt_ma`);

--
-- Constraints for table `lich_su_xem`
--
ALTER TABLE `lich_su_xem`
  ADD CONSTRAINT `lich_su_xem_ibfk_1` FOREIGN KEY (`bl_ma`) REFERENCES `binh_luan` (`bl_ma`),
  ADD CONSTRAINT `lich_su_xem_ibfk_2` FOREIGN KEY (`bv_ma`) REFERENCES `bai_viet` (`bv_ma`),
  ADD CONSTRAINT `lich_su_xem_ibfk_3` FOREIGN KEY (`nd_username`) REFERENCES `nguoi_dung` (`nd_username`);

--
-- Constraints for table `mon_hoc`
--
ALTER TABLE `mon_hoc`
  ADD CONSTRAINT `mon_hoc_ibfk_1` FOREIGN KEY (`kl_ma`) REFERENCES `khoi_lop` (`kl_ma`);

--
-- Constraints for table `rep_bl`
--
ALTER TABLE `rep_bl`
  ADD CONSTRAINT `rep_bl_ibfk_1` FOREIGN KEY (`bl_cha`) REFERENCES `binh_luan` (`bl_ma`),
  ADD CONSTRAINT `rep_bl_ibfk_2` FOREIGN KEY (`bl_con`) REFERENCES `binh_luan` (`bl_ma`);

--
-- Constraints for table `tai_lieu`
--
ALTER TABLE `tai_lieu`
  ADD CONSTRAINT `tai_lieu_ibfk_1` FOREIGN KEY (`bv_ma`) REFERENCES `bai_viet` (`bv_ma`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
