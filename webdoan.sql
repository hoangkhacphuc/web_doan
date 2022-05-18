-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th5 18, 2022 lúc 12:49 PM
-- Phiên bản máy phục vụ: 10.4.21-MariaDB
-- Phiên bản PHP: 7.4.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `webdoan`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `account`
--

CREATE TABLE `account` (
  `id` int(11) NOT NULL,
  `user` varchar(255) NOT NULL,
  `pass` varchar(255) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT '',
  `birthday` date DEFAULT NULL,
  `gender` int(11) DEFAULT 1 COMMENT '0 : Nữ, 1 : Nam',
  `address` varchar(255) DEFAULT '',
  `user_type` int(11) DEFAULT 0 COMMENT '0 : Đoàn viên, 1 : Admin',
  `date_created` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Đang đổ dữ liệu cho bảng `account`
--

INSERT INTO `account` (`id`, `user`, `pass`, `full_name`, `email`, `birthday`, `gender`, `address`, `user_type`, `date_created`) VALUES
(1, 'admin', 'f5bb0c8de146c67b44babbf4e6584cc0', 'ADMIN', 'admin@gmail.com', '2022-01-01', 1, 'Hà Nội', 1, '2022-05-18 10:02:15'),
(2, 'user0001', 'f5bb0c8de146c67b44babbf4e6584cc0', 'Nguyễn Văn A', 'a123@gmail.com', '2022-01-01', 1, 'Hà Nội', 0, '2022-05-18 10:49:19'),
(3, 'user0002', 'f5bb0c8de146c67b44babbf4e6584cc0', 'Nguyễn Văn B', 'b123@gmail.com', '2022-01-01', 1, 'Hà Nội', 0, '2022-05-18 10:49:22');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `participate`
--

CREATE TABLE `participate` (
  `id` int(11) NOT NULL,
  `account_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `title` varchar(500) NOT NULL,
  `content` varchar(5000) NOT NULL,
  `be_attended` int(11) DEFAULT 0 COMMENT '0 : Ko được tham gia, 1 : Được tham gia',
  `account_id` int(11) NOT NULL,
  `deadline` date NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Đang đổ dữ liệu cho bảng `posts`
--

INSERT INTO `posts` (`id`, `title`, `content`, `be_attended`, `account_id`, `deadline`, `date_created`) VALUES
(1, 'Vụ biệt thự \"tai tiếng\" ở Hà Nội: Kỷ luật Chủ tịch phường Yên Hòa', 'Trao đổi với PV Dân trí ngày 18/5, bà Trần Thị Phương Hoa - Bí thư Quận ủy Cầu Giấy - xác nhận, ông Đỗ Ngọc Anh - Chủ tịch UBND phường Yên Hòa - vừa bị quận Cầu Giấy ra quyết định kỷ luật công chức với hình thức cảnh cáo.UBND quận Cầu Giấy cũng ra quyết định kỷ luật công chức với hình thức khiển trách đối với ông Nguyễn Xuân Quang - Phó Chủ tịch UBND phường Yên Hòa; ông Trần Anh Tuấn - Đội trưởng Đội quản lý trật tự xây dựng đô thị quận Cầu Giấy.\"Hiện Ủy ban kiểm tra quận ủy đang tiến hành kiểm tra để xử lý về mặt Đảng đối với 3 cá nhân nêu trên theo hình thức tương xứng\" - bà Hoa cho biết thêm.', 1, 1, '2022-05-19', '2022-05-18 10:08:51'),
(2, 'Vụ biệt thự \"tai tiếng\" ở Hà Nội: Kỷ luật Chủ tịch phường Yên Hòa', 'Trao đổi với PV Dân trí ngày 18/5, bà Trần Thị Phương Hoa - Bí thư Quận ủy Cầu Giấy - xác nhận, ông Đỗ Ngọc Anh - Chủ tịch UBND phường Yên Hòa - vừa bị quận Cầu Giấy ra quyết định kỷ luật công chức với hình thức cảnh cáo.UBND quận Cầu Giấy cũng ra quyết định kỷ luật công chức với hình thức khiển trách đối với ông Nguyễn Xuân Quang - Phó Chủ tịch UBND phường Yên Hòa; ông Trần Anh Tuấn - Đội trưởng Đội quản lý trật tự xây dựng đô thị quận Cầu Giấy.\"Hiện Ủy ban kiểm tra quận ủy đang tiến hành kiểm tra để xử lý về mặt Đảng đối với 3 cá nhân nêu trên theo hình thức tương xứng\" - bà Hoa cho biết thêm.', 0, 1, '0000-00-00', '2022-05-18 10:12:43'),
(3, 'Vụ biệt thự \"tai tiếng\" ở Hà Nội: Kỷ luật Chủ tịch phường Yên Hòa', 'Trao đổi với PV Dân trí ngày 18/5, bà Trần Thị Phương Hoa - Bí thư Quận ủy Cầu Giấy - xác nhận, ông Đỗ Ngọc Anh - Chủ tịch UBND phường Yên Hòa - vừa bị quận Cầu Giấy ra quyết định kỷ luật công chức với hình thức cảnh cáo.UBND quận Cầu Giấy cũng ra quyết định kỷ luật công chức với hình thức khiển trách đối với ông Nguyễn Xuân Quang - Phó Chủ tịch UBND phường Yên Hòa; ông Trần Anh Tuấn - Đội trưởng Đội quản lý trật tự xây dựng đô thị quận Cầu Giấy.\"Hiện Ủy ban kiểm tra quận ủy đang tiến hành kiểm tra để xử lý về mặt Đảng đối với 3 cá nhân nêu trên theo hình thức tương xứng\" - bà Hoa cho biết thêm.', 1, 1, '2022-05-17', '2022-05-18 10:12:43'),
(4, 'Vụ biệt thự \"tai tiếng\" ở Hà Nội: Kỷ luật Chủ tịch phường Yên Hòa', 'Trao đổi với PV Dân trí ngày 18/5, bà Trần Thị Phương Hoa - Bí thư Quận ủy Cầu Giấy - xác nhận, ông Đỗ Ngọc Anh - Chủ tịch UBND phường Yên Hòa - vừa bị quận Cầu Giấy ra quyết định kỷ luật công chức với hình thức cảnh cáo.UBND quận Cầu Giấy cũng ra quyết định kỷ luật công chức với hình thức khiển trách đối với ông Nguyễn Xuân Quang - Phó Chủ tịch UBND phường Yên Hòa; ông Trần Anh Tuấn - Đội trưởng Đội quản lý trật tự xây dựng đô thị quận Cầu Giấy.\"Hiện Ủy ban kiểm tra quận ủy đang tiến hành kiểm tra để xử lý về mặt Đảng đối với 3 cá nhân nêu trên theo hình thức tương xứng\" - bà Hoa cho biết thêm.', 0, 1, '0000-00-00', '2022-05-18 10:12:43'),
(5, 'Vụ biệt thự \"tai tiếng\" ở Hà Nội: Kỷ luật Chủ tịch phường Yên Hòa', 'Trao đổi với PV Dân trí ngày 18/5, bà Trần Thị Phương Hoa - Bí thư Quận ủy Cầu Giấy - xác nhận, ông Đỗ Ngọc Anh - Chủ tịch UBND phường Yên Hòa - vừa bị quận Cầu Giấy ra quyết định kỷ luật công chức với hình thức cảnh cáo.UBND quận Cầu Giấy cũng ra quyết định kỷ luật công chức với hình thức khiển trách đối với ông Nguyễn Xuân Quang - Phó Chủ tịch UBND phường Yên Hòa; ông Trần Anh Tuấn - Đội trưởng Đội quản lý trật tự xây dựng đô thị quận Cầu Giấy.\"Hiện Ủy ban kiểm tra quận ủy đang tiến hành kiểm tra để xử lý về mặt Đảng đối với 3 cá nhân nêu trên theo hình thức tương xứng\" - bà Hoa cho biết thêm.', 0, 1, '0000-00-00', '2022-05-18 10:12:43');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `account`
--
ALTER TABLE `account`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `participate`
--
ALTER TABLE `participate`
  ADD PRIMARY KEY (`id`),
  ADD KEY `account_id` (`account_id`),
  ADD KEY `post_id` (`post_id`);

--
-- Chỉ mục cho bảng `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `account_id` (`account_id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `account`
--
ALTER TABLE `account`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `participate`
--
ALTER TABLE `participate`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `participate`
--
ALTER TABLE `participate`
  ADD CONSTRAINT `participate_ibfk_1` FOREIGN KEY (`account_id`) REFERENCES `account` (`id`),
  ADD CONSTRAINT `participate_ibfk_2` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`);

--
-- Các ràng buộc cho bảng `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`account_id`) REFERENCES `account` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
