

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

CREATE DATABASE `myclassroom` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;

USE `myclassroom`;

CREATE TABLE `account` (
  `username` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `firstname` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `lastname` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `activated` bit(1) DEFAULT b'0',
  `activate_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `account` (`username`, `firstname`, `lastname`, `email`, `password`, `activated`, `activate_token`) VALUES
('admin', 'Phan', 'Hải Đăng', 'cunkul3s5@gmail.com', '$2y$10$G5xbGzpKJl8AWmOd1NPM9Oea8p4tBGja/81TAGshzocFynX3yPG26', b'1', '04d8561f1f350e681e57f59c1f5143dd'),
('dang', 'Hai Dang 1', 'Phan', 'haidang123456@gmail.com', '$2y$10$G5xbGzpKJl8AWmOd1NPM9Oea8p4tBGja/81TAGshzocFynX3yPG26', b'1', '0'),
('dang2', 'Hai Dang 2', 'Phan', 'haidang1456@gmail.com', '$2y$10$G5xbGzpKJl8AWmOd1NPM9Oea8p4tBGja/81TAGshzocFynX3yPG26', b'1', '0'),
('dang3', 'Hai Dang 3', 'Phan', 'haidang156@gmail.com', '$2y$10$G5xbGzpKJl8AWmOd1NPM9Oea8p4tBGja/81TAGshzocFynX3yPG26', b'1', '0'),
('haidang', 'Dang', 'Clone', 'mrdrom9191@gmail.com', '$2y$10$m/UHfVsNLLx8GvSyv3NbT.oggw4N9Ln88Qg20Y48CuIZHh./KOdai', b'1', ''),
('tep', 'aaa', 'aaa', 'ddd', '$2y$10$G5xbGzpKJl8AWmOd1NPM9Oea8p4tBGja/81TAGshzocFynX3yPG26', b'1', '3333');


GO
CREATE TABLE `account_lophoc` (
  `idaccount_lophoc` int(11) NOT NULL,
  `idLop` int(11) NOT NULL,
  `username` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `role` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

GO

INSERT INTO `account_lophoc` (`idaccount_lophoc`, `idLop`, `username`, `role`) VALUES
(1, 1, 'admin', 1),
(2, 2, 'admin', 1),
(5, 1, 'tep', 2),
(9, 3, 'dang', 2),
(10, 1, 'dang', 2),
(12, 1, 'dang3', 2),
(14, 8, 'admin', 1),
(18, 12, 'admin', 1),
(20, 3, 'admin', 1),
(21, 3, 'dang2', 2),
(30, 14, 'tep', 1),
(31, 14, 'admin', 1),
(32, 3, 'dang3', 2),
(33, 12, 'tep', 2),
(36, 15, 'haidang', 1),
(37, 15, 'admin', 0),
(38, 16, 'haidang', 1),
(39, 16, 'admin', 1),
(41, 16, 'dang3', 1),
(42, 2, 'dang', 2),
(43, 12, 'dang3', 2);


GO

CREATE TABLE `baidang` (
  `idbaidang` int(11) NOT NULL,
  `idlophoc` int(11) NOT NULL,
  `username` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `noidung` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `fileupload` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `thoigiandang` varchar(64) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

GO

INSERT INTO `baidang` (`idbaidang`, `idlophoc`, `username`, `noidung`, `fileupload`, `thoigiandang`) VALUES
(10, 8, 'admin', 'Xin chào mọi người, hôm nay trời đẹp quá', '', '02/12/2020 22:18'),
(11, 8, 'admin', 'My love is like to ice, and I to fire', '', '02/12/2020 22:18'),
(13, 8, 'admin', '1. My love for you is like the raging sea,\r\n\r\nSo powerful and deep it will forever be.\r\n\r\nThrough storm, wind, and heavy rain\r\n\r\nIt will withstand every pain.', '', '02/12/2020 22:17'),
(14, 2, 'admin', 'anh đẹp trai đúng kh em', '', '02/12/2020 15:28'),
(16, 2, 'admin', 'xin chào mọi người', '', '02/12/2020 16:37'),
(17, 3, 'dang', 'hello', '', '02/12/2020 16:11'),
(18, 1, 'admin', 'xin chào mọi người, chúng ta bắt đầu học nhé', 'baidang/VzER67nBEc/work.jpg', '02/12/2020 22:19'),
(19, 2, 'admin', 'Danh sách cấm thi lý thuyết do vắng quá số buổi học theo quy định. Các em xem trong tập tin đính kèm và phản hồi đến hết 27/11/2020.\r\n', '', '02/12/2020 17:25'),
(20, 3, 'admin', 'Các em nghỉ học nhé', '', '02/12/2020 18:13'),
(21, 3, 'admin', 'Đừng chơi bời nhiều nha các em', '', '02/12/2020 18:32'),
(22, 2, 'admin', 'Thầy nhớ các em quá', '', '02/12/2020 18:40'),
(23, 2, 'admin', 'Nhớ gì như nhớ các em', '', '02/12/2020 18:41'),
(24, 2, 'admin', 'ddd', '', '02/12/2020 18:42'),
(25, 2, 'admin', 'Dạo này lạnh quá', '', '02/12/2020 19:25'),
(26, 2, 'admin', 'Đừng nói vs thầy là các em 20 tuổi  hé', '', '02/12/2020 19:25'),
(27, 2, 'admin', 'Hôm nay trời nhiều mây vậy các em', '', '02/12/2020 19:27'),
(28, 2, 'admin', 'dd', '', '02/12/2020 19:28'),
(29, 2, 'admin', 'dddd', '', '02/12/2020 19:34'),
(30, 3, 'admin', 'dddd', '', '02/12/2020 19:36'),
(31, 3, 'admin', 'd', '', '02/12/2020 19:36'),
(32, 3, 'admin', 'dh', '', '02/12/2020 19:37'),
(33, 3, 'admin', 'Các bạn đã được nghỉ tết chưa', '', '02/12/2020 19:46');



GO

CREATE TABLE `baitap` (
  `idbaitap` int(11) NOT NULL,
  `usernamepost` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `idlophoc` int(11) NOT NULL,
  `tieude` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `noidung` varchar(1000) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fileupload` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `thoigiandang` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `thoigianhethan` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

GO

INSERT INTO `baitap` (`idbaitap`, `usernamepost`, `idlophoc`, `tieude`, `noidung`, `fileupload`, `thoigiandang`, `thoigianhethan`) VALUES
(11, 'admin', 3, 'Bài tập môn mạng', 'các em nhớ làm bài cho thầy nè', 'baitap/6ECRAhpkmC/homework.png', '02/12/2020 20:03', ''),
(12, 'admin', 3, 'Bài tập sinh học', 'sinh học 123', '', '02/12/2020 20:21', '12/12/2020 10:20'),
(13, 'admin', 1, 'bài tập d', 'bài tập d', '', '02/12/2020 20:30', ''),
(14, 'admin', 1, 'Bài tập về nhà 2', '', 'baitap/VzER67nBEc/class (1).html', '02/12/2020 22:19', '');


GO

CREATE TABLE `binhluan` (
  `idbl` int(11) NOT NULL,
  `idbaidang` int(11) NOT NULL,
  `noidungbl` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `usernamebl` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `thoigianbl` varchar(50) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

GO

INSERT INTO `binhluan` (`idbl`, `idbaidang`, `noidungbl`, `usernamebl`, `thoigianbl`) VALUES
(4, 14, 'trời ơi', 'admin', '02/12/2020 17:02'),
(5, 18, 'Nay thầy thấy mình trẻ ra 10 tuổi', 'admin', '02/12/2020 17:07'),
(6, 18, 'thầy đẹp trai quá', 'dang', '02/12/2020 17:08'),
(7, 20, 'đúng v', 'admin', '02/12/2020 18:14');


GO

CREATE TABLE `binhluan_baitap` (
  `idbl_bt` int(11) NOT NULL,
  `id_baitap` int(11) NOT NULL,
  `username` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `noidungbl` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `thoigianbl` varchar(64) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

GO

INSERT INTO `binhluan_baitap` (`idbl_bt`, `id_baitap`, `username`, `noidungbl`, `thoigianbl`) VALUES
(1, 13, 'admin', 'đâsđá', '02/12/2020 21:42'),
(2, 13, 'admin', 'đâsdá', '02/12/2020 21:42'),
(3, 13, 'dang3', 'dsađâs', '02/12/2020 21:55'),
(4, 13, 'dang3', 'đâsdá', '02/12/2020 21:55'),
(5, 11, 'dang3', 'đasdá', '02/12/2020 22:01'),
(6, 11, 'dang3', 'dsađâs', '02/12/2020 22:01'),
(7, 11, 'dang3', 'đâsda', '02/12/2020 22:02');



GO

CREATE TABLE `loaibaidang` (
  `maloaibd` int(11) NOT NULL,
  `tenloai` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

GO

INSERT INTO `loaibaidang` (`maloaibd`, `tenloai`) VALUES
(1, 'Thông báo'),
(2, 'Thảo luận'),
(3, 'Bài tập');


GO

CREATE TABLE `lophoc` (
  `idLop` int(11) NOT NULL,
  `tenLop` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `motaLop` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phanHoc` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phongHoc` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `chude` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `codelop` varchar(64) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

GO

INSERT INTO `lophoc` (`idLop`, `tenLop`, `motaLop`, `phanHoc`, `phongHoc`, `chude`, `codelop`) VALUES
(1, 'Cấu trúc rời rạc', 'Discrete Structures	', 'Phần 2', 'C501', 'Triết học', 'VzER67nBEc'),
(2, 'Phương pháp tính', 'Numerical Analysis	', '3', 'A701', 'Tin học', 'ubpcw1OSEm'),
(3, 'Thực tập nghề nghiệp', 'Basic Career Requirement	', '', 'A302', 'tin học', '6ECRAhpkmC'),
(6, 'lớp 1', 'mota nè', 'phan hc nè', 'C123', 'Game', '123121313'),
(8, 'Giáo dục quốc phòng - Học phần 1', 'National Defense Education - 1st Course', '1', 'D401', '', 'tHWNyArBqCSdI0g'),
(12, 'Giáo dục quốc phòng - Học phần 2', 'National Defense Education - 2nd Course	', '2', 'C602', 'Tin học', 'dNbTP9VAFiGlKqW'),
(14, 'Xác suất và thống kê ứng dụng cho Công nghệ thông tin', 'Applied Probability and Statistics for IT	', '1', 'C406', '', 'zTdFiyH5jwo41lD'),
(15, 'Hai dang clone', '', '', '', '', 'gGxlj4rHKkT86Qs'),
(16, 'Cấu trúc dữ liệu và giải thuật 1', 'Data Structures and Algorithms 1	', '', '', '', 're61liZuDmFpLIo');

GO

CREATE TABLE `pending_invited` (
  `idinvite` int(11) NOT NULL,
  `username` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `codelophoc` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `role` int(11) NOT NULL,
  `status` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

GO

INSERT INTO `pending_invited` (`idinvite`, `username`, `codelophoc`, `role`, `status`) VALUES
(1, 'tep', 'dNbTP9VAFiGlKqW', 2, 0),
(2, 'admin', 'VzER67nBEc', 0, 1),
(3, 'dang2', '123121313', 2, 1),
(5, 'dang', 'dddddddddddddd', 2, 0),
(7, 'admin', '3333333', 1231321321, 0),
(9, 'dang2', 're61liZuDmFpLIo', 2, 0);

GO

CREATE TABLE `reset_token` (
  `email` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `expire_on` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

GO

INSERT INTO `reset_token` (`email`, `token`, `expire_on`) VALUES
('cunkul35@gmail.com', '40be3a97af88a6f11750d7b02e71bf40', 1606209384);

GO
ALTER TABLE `account`
  ADD PRIMARY KEY (`username`),
  ADD UNIQUE KEY `email` (`email`);

GO
ALTER TABLE `account_lophoc`
  ADD PRIMARY KEY (`idaccount_lophoc`),
  ADD KEY `account_lophoc_username` (`username`),
  ADD KEY `fk_account_lophoc_idlop` (`idLop`);

GO
ALTER TABLE `baidang`
  ADD PRIMARY KEY (`idbaidang`),
  ADD KEY `baidang_idlophoc` (`idlophoc`),
  ADD KEY `baidang_username` (`username`);

GO
ALTER TABLE `baitap`
  ADD PRIMARY KEY (`idbaitap`),
  ADD KEY `baitap_idlop` (`idlophoc`),
  ADD KEY `baitap_username` (`usernamepost`);

GO
ALTER TABLE `binhluan`
  ADD PRIMARY KEY (`idbl`),
  ADD KEY `binhluan_idbaidang` (`idbaidang`),
  ADD KEY `binhluan_username` (`usernamebl`);

GO
ALTER TABLE `binhluan_baitap`
  ADD PRIMARY KEY (`idbl_bt`),
  ADD KEY `binhluan_baitap_idbt` (`id_baitap`),
  ADD KEY `binhluan_baitap_username` (`username`);

GO
ALTER TABLE `loaibaidang`
  ADD PRIMARY KEY (`maloaibd`);

GO
ALTER TABLE `lophoc`
  ADD PRIMARY KEY (`idLop`);

GO
ALTER TABLE `pending_invited`
  ADD PRIMARY KEY (`idinvite`),
  ADD KEY `pending_invited_username` (`username`);

GO
ALTER TABLE `reset_token`
  ADD PRIMARY KEY (`email`);

GO
ALTER TABLE `account_lophoc`
  MODIFY `idaccount_lophoc` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

GO
ALTER TABLE `baidang`
  MODIFY `idbaidang` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

GO
ALTER TABLE `baitap`
  MODIFY `idbaitap` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

GO
ALTER TABLE `binhluan`
  MODIFY `idbl` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

GO
ALTER TABLE `binhluan_baitap`
  MODIFY `idbl_bt` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

GO
ALTER TABLE `loaibaidang`
  MODIFY `maloaibd` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

GO
ALTER TABLE `lophoc`
  MODIFY `idLop` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

GO
ALTER TABLE `pending_invited`
  MODIFY `idinvite` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

GO
ALTER TABLE `account_lophoc`
  ADD CONSTRAINT `account_lophoc_idlop` FOREIGN KEY (`idLop`) REFERENCES `lophoc` (`idLop`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `account_lophoc_username` FOREIGN KEY (`username`) REFERENCES `account` (`username`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_account_lophoc_idlop` FOREIGN KEY (`idLop`) REFERENCES `lophoc` (`idLop`) ON DELETE CASCADE ON UPDATE CASCADE;

GO
ALTER TABLE `baidang`
  ADD CONSTRAINT `baidang_idlophoc` FOREIGN KEY (`idlophoc`) REFERENCES `lophoc` (`idLop`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `baidang_username` FOREIGN KEY (`username`) REFERENCES `account` (`username`) ON DELETE CASCADE ON UPDATE CASCADE;

GO
ALTER TABLE `baitap`
  ADD CONSTRAINT `baitap_idlop` FOREIGN KEY (`idlophoc`) REFERENCES `lophoc` (`idLop`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `baitap_username` FOREIGN KEY (`usernamepost`) REFERENCES `account` (`username`) ON DELETE CASCADE ON UPDATE CASCADE;

GO
ALTER TABLE `binhluan`
  ADD CONSTRAINT `binhluan_idbaidang` FOREIGN KEY (`idbaidang`) REFERENCES `baidang` (`idbaidang`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `binhluan_username` FOREIGN KEY (`usernamebl`) REFERENCES `account` (`username`) ON DELETE CASCADE ON UPDATE CASCADE;

GO
ALTER TABLE `binhluan_baitap`
  ADD CONSTRAINT `binhluan_baitap_idbt` FOREIGN KEY (`id_baitap`) REFERENCES `baitap` (`idbaitap`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `binhluan_baitap_username` FOREIGN KEY (`username`) REFERENCES `account` (`username`) ON DELETE CASCADE ON UPDATE CASCADE;

GO
ALTER TABLE `pending_invited`
  ADD CONSTRAINT `pending_invited_username` FOREIGN KEY (`username`) REFERENCES `account` (`username`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

