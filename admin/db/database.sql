/*
SQLyog Enterprise - MySQL GUI v8.12 
MySQL - 5.1.41-community : Database - framework-php
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

/*Table structure for table `attachment` */

CREATE TABLE `attachment` (
  `code` int(8) NOT NULL AUTO_INCREMENT,
  `member_code` int(8) unsigned NOT NULL COMMENT 'member code or shop code',
  `table_name` varchar(20) NOT NULL COMMENT '연동 table명 : board:커뮤니티, shop:상점, book:현재사용 않음',
  `table_code` int(8) NOT NULL COMMENT 'table code (board_data_code, book_code...)',
  `plupload_id` varchar(20) DEFAULT NULL COMMENT '대용량 upload용 id',
  `file_path` varchar(100) NOT NULL COMMENT '파일 path',
  `file_name` varchar(50) NOT NULL COMMENT '파일명',
  `thumbnail_name` varchar(50) DEFAULT NULL COMMENT '쎔네일 파일명',
  `orig_name` varchar(100) DEFAULT NULL COMMENT '원본 파일명',
  `file_size` float unsigned DEFAULT NULL COMMENT '파일 크기(KB)',
  `file_width` int(4) DEFAULT NULL COMMENT '파일 width',
  `file_height` int(4) DEFAULT NULL COMMENT '파일 height',
  `file_ext` varchar(10) NOT NULL COMMENT '파일 확장자',
  `reg_date` datetime NOT NULL COMMENT '등록일자',
  PRIMARY KEY (`code`),
  KEY `attachment_index1` (`table_name`,`table_code`),
  KEY `attachment_index2` (`code`,`member_code`),
  KEY `attachment_index3` (`plupload_id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8;

/*Data for the table `attachment` */

insert  into `attachment`(`code`,`member_code`,`table_name`,`table_code`,`plupload_id`,`file_path`,`file_name`,`thumbnail_name`,`orig_name`,`file_size`,`file_width`,`file_height`,`file_ext`,`reg_date`) values (1,0,'board_data',8,'161214_141949676','/attachment/201612','1481692823366519.png','1481692823366519_tn.png','2.png',31,136,153,'png','2016-12-14 14:20:23');
insert  into `attachment`(`code`,`member_code`,`table_name`,`table_code`,`plupload_id`,`file_path`,`file_name`,`thumbnail_name`,`orig_name`,`file_size`,`file_width`,`file_height`,`file_ext`,`reg_date`) values (2,0,'board_data',8,'161214_141949676','/attachment/201612','1481692823438775.png','1481692823438775_tn.png','3_뽀로로의대모험.png',1240,1024,1024,'png','2016-12-14 14:20:23');
insert  into `attachment`(`code`,`member_code`,`table_name`,`table_code`,`plupload_id`,`file_path`,`file_name`,`thumbnail_name`,`orig_name`,`file_size`,`file_width`,`file_height`,`file_ext`,`reg_date`) values (3,0,'board_data',8,'161214_141949676','/attachment/201612','1481692823584389.png','1481692823584389_tn.png','2_말해봐디보.png',1040,1024,1024,'png','2016-12-14 14:20:23');
insert  into `attachment`(`code`,`member_code`,`table_name`,`table_code`,`plupload_id`,`file_path`,`file_name`,`thumbnail_name`,`orig_name`,`file_size`,`file_width`,`file_height`,`file_ext`,`reg_date`) values (4,0,'board_data',8,'161214_141949676','/attachment/201612','1481692823705116.jpg','1481692823705116_tn.jpg','003.jpg',548,1200,1147,'jpg','2016-12-14 14:20:23');
insert  into `attachment`(`code`,`member_code`,`table_name`,`table_code`,`plupload_id`,`file_path`,`file_name`,`thumbnail_name`,`orig_name`,`file_size`,`file_width`,`file_height`,`file_ext`,`reg_date`) values (5,0,'board_data',11,'161221_210241492','/attachment/201612','1482321790083580.jpg','1482321790083580_tn.jpg','1.jpg',58,600,320,'jpg','2016-12-21 21:03:10');
insert  into `attachment`(`code`,`member_code`,`table_name`,`table_code`,`plupload_id`,`file_path`,`file_name`,`thumbnail_name`,`orig_name`,`file_size`,`file_width`,`file_height`,`file_ext`,`reg_date`) values (6,0,'board_data',11,'161221_210241492','/attachment/201612','1482321790164465.jpg','1482321790164465_tn.jpg','2.jpg',111,540,359,'jpg','2016-12-21 21:03:10');
insert  into `attachment`(`code`,`member_code`,`table_name`,`table_code`,`plupload_id`,`file_path`,`file_name`,`thumbnail_name`,`orig_name`,`file_size`,`file_width`,`file_height`,`file_ext`,`reg_date`) values (7,1,'board_data',12,NULL,'/attachment/201612','1482996596579338.jpg','1482996596579338_tn.jpg','00mugi_big22.jpg',8,316,299,'jpg','2016-12-29 16:29:56');
insert  into `attachment`(`code`,`member_code`,`table_name`,`table_code`,`plupload_id`,`file_path`,`file_name`,`thumbnail_name`,`orig_name`,`file_size`,`file_width`,`file_height`,`file_ext`,`reg_date`) values (8,0,'board_data',0,'170104_163651426','/attachment/201701','1483515480405621.pptx','','160929_경기버스_수정사항_1차.pptx',488,0,0,'pptx','2017-01-04 16:38:00');
insert  into `attachment`(`code`,`member_code`,`table_name`,`table_code`,`plupload_id`,`file_path`,`file_name`,`thumbnail_name`,`orig_name`,`file_size`,`file_width`,`file_height`,`file_ext`,`reg_date`) values (9,0,'board_data',0,'170104_163651426','/attachment/201701','1483515480510322.pptx','','160905-경기버스-수정사항-ICT사업부-이민지-ver.3.1.pptx',2010,0,0,'pptx','2017-01-04 16:38:00');
insert  into `attachment`(`code`,`member_code`,`table_name`,`table_code`,`plupload_id`,`file_path`,`file_name`,`thumbnail_name`,`orig_name`,`file_size`,`file_width`,`file_height`,`file_ext`,`reg_date`) values (10,0,'board_data',0,'170104_163651426','/attachment/201701','1483515480557578.txt','','경기버스_진행사항20160905.txt',1,0,0,'txt','2017-01-04 16:38:00');
insert  into `attachment`(`code`,`member_code`,`table_name`,`table_code`,`plupload_id`,`file_path`,`file_name`,`thumbnail_name`,`orig_name`,`file_size`,`file_width`,`file_height`,`file_ext`,`reg_date`) values (11,0,'board_data',0,'170104_163651426','/attachment/201701','1483515480666202.pptx','','160707-경기버스-홈페이지 기획서-ICT사업부-이민지-ver.2.2.pptx',2302,0,0,'pptx','2017-01-04 16:38:00');
insert  into `attachment`(`code`,`member_code`,`table_name`,`table_code`,`plupload_id`,`file_path`,`file_name`,`thumbnail_name`,`orig_name`,`file_size`,`file_width`,`file_height`,`file_ext`,`reg_date`) values (12,0,'board_data',0,'170104_163651426','/attachment/201701','1483515480771426.pptx','','160526-경기버스-홈페이지 기획서-ICT사업부-이민지-ver.1.3.pptx',1902,0,0,'pptx','2017-01-04 16:38:00');
insert  into `attachment`(`code`,`member_code`,`table_name`,`table_code`,`plupload_id`,`file_path`,`file_name`,`thumbnail_name`,`orig_name`,`file_size`,`file_width`,`file_height`,`file_ext`,`reg_date`) values (13,0,'board_data',0,'170104_163651426','/attachment/201701','1483515480818061.xlsx','','160518-경기버스 프로젝트 일정-ICT사업부-이상희-R0.xlsx',151,0,0,'xlsx','2017-01-04 16:38:00');
insert  into `attachment`(`code`,`member_code`,`table_name`,`table_code`,`plupload_id`,`file_path`,`file_name`,`thumbnail_name`,`orig_name`,`file_size`,`file_width`,`file_height`,`file_ext`,`reg_date`) values (14,0,'board_data',17,'','/attachment/201701','1484201853314189.png','1484201853314189_tn.png','2.png',31,136,153,'png','2017-01-12 15:17:33');
insert  into `attachment`(`code`,`member_code`,`table_name`,`table_code`,`plupload_id`,`file_path`,`file_name`,`thumbnail_name`,`orig_name`,`file_size`,`file_width`,`file_height`,`file_ext`,`reg_date`) values (15,0,'board_data',17,'','/attachment/201701','1484201853369621.png','1484201853369621_tn.png','4.png',39,136,146,'png','2017-01-12 15:17:33');
insert  into `attachment`(`code`,`member_code`,`table_name`,`table_code`,`plupload_id`,`file_path`,`file_name`,`thumbnail_name`,`orig_name`,`file_size`,`file_width`,`file_height`,`file_ext`,`reg_date`) values (16,0,'board_data',18,'170112_152706783','/attachment/201701','1484202443896260.png','1484202443896260_tn.png','4.png',39,136,146,'png','2017-01-12 15:27:23');
insert  into `attachment`(`code`,`member_code`,`table_name`,`table_code`,`plupload_id`,`file_path`,`file_name`,`thumbnail_name`,`orig_name`,`file_size`,`file_width`,`file_height`,`file_ext`,`reg_date`) values (17,0,'board_data',0,'170113_110711200','/attachment/201701','1484273246591477.png','1484273246591477_tn.png','2.png',31,136,153,'png','2017-01-13 11:07:26');
insert  into `attachment`(`code`,`member_code`,`table_name`,`table_code`,`plupload_id`,`file_path`,`file_name`,`thumbnail_name`,`orig_name`,`file_size`,`file_width`,`file_height`,`file_ext`,`reg_date`) values (18,1,'board_data',22,NULL,'/attachment/201702','1486601044586652.xlsx','','170207_엑셀샘플.xlsx',17,0,0,'xlsx','2017-02-09 09:44:04');
insert  into `attachment`(`code`,`member_code`,`table_name`,`table_code`,`plupload_id`,`file_path`,`file_name`,`thumbnail_name`,`orig_name`,`file_size`,`file_width`,`file_height`,`file_ext`,`reg_date`) values (19,1,'board_data',31,NULL,'/attachment/201702','1488258046846913.png','1488258046846913_tn.png','3.png',32,138,147,'png','2017-02-28 14:00:46');
insert  into `attachment`(`code`,`member_code`,`table_name`,`table_code`,`plupload_id`,`file_path`,`file_name`,`thumbnail_name`,`orig_name`,`file_size`,`file_width`,`file_height`,`file_ext`,`reg_date`) values (22,1,'board_data',35,NULL,'/attachment/201703','1490248649151300.jpg','1490248649151300_tn.jpg','fe4556ea59047b6ecbd0f48427c3478f_1490084422_1565.jpg',17,346,354,'jpg','2017-03-23 14:57:29');

/*Table structure for table `banner` */

CREATE TABLE `banner` (
  `code` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(30) CHARACTER SET utf8 NOT NULL COMMENT '타이틀',
  `url` varchar(200) CHARACTER SET utf8 DEFAULT NULL COMMENT 'url',
  `type` char(5) CHARACTER SET utf8 NOT NULL COMMENT '배너 구분자',
  `reg_date` datetime NOT NULL COMMENT '날짜',
  PRIMARY KEY (`code`),
  KEY `banner_index1` (`type`,`code`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

/*Data for the table `banner` */

insert  into `banner`(`code`,`title`,`url`,`type`,`reg_date`) values (1,'후이즈','http://whois.co.kr','','2017-03-13 16:16:33');

/*Table structure for table `board` */

CREATE TABLE `board` (
  `code` int(6) NOT NULL AUTO_INCREMENT,
  `type` enum('list','gallery','qna','webzine','movie','event') NOT NULL COMMENT 'type(list:일반,gallery:사진,qna,webzine:웹진,movie:동영상,event:이벤트)',
  `title` varchar(100) DEFAULT NULL COMMENT '제목',
  `header` text COMMENT '헤더 소스',
  `category` varchar(100) DEFAULT NULL COMMENT '카테고리(,구분)',
  `show_list` enum('y','n') DEFAULT 'n' COMMENT '상세보기에서 리스트 표출여부',
  `show_memo` enum('y','n') DEFAULT 'n' COMMENT '댓글',
  `limit_title` int(3) DEFAULT NULL COMMENT '리스트에서 표출 글자수',
  `auth_list` int(1) NOT NULL COMMENT '리스트 권한',
  `auth_view` int(1) NOT NULL COMMENT '보기 권한(NULL:누구나 가능)',
  `auth_write` int(1) NOT NULL DEFAULT '2' COMMENT '쓰기 권한',
  `auth_reply` int(1) NOT NULL DEFAULT '2' COMMENT '답변 권한',
  `auth_update` int(1) NOT NULL DEFAULT '2' COMMENT '수정 권한',
  `auth_memo` int(1) NOT NULL DEFAULT '2' COMMENT '매모 권한',
  `auth_delete` int(1) NOT NULL DEFAULT '2' COMMENT '삭제 권한',
  `auth_notice` int(1) NOT NULL DEFAULT '1' COMMENT '공지 권한',
  `is_secret` enum('n','y') DEFAULT 'n' COMMENT '비밀글',
  `is_mass` enum('n','y') DEFAULT 'n' COMMENT '대용량 업로드',
  `is_order` enum('n','y') DEFAULT 'n' COMMENT '순서변경 기능',
  `is_captcha` enum('n','y') DEFAULT 'y' COMMENT '그림문자',
  PRIMARY KEY (`code`),
  KEY `board_list_index1` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `board` */

insert  into `board`(`code`,`type`,`title`,`header`,`category`,`show_list`,`show_memo`,`limit_title`,`auth_list`,`auth_view`,`auth_write`,`auth_reply`,`auth_update`,`auth_memo`,`auth_delete`,`auth_notice`,`is_secret`,`is_mass`,`is_order`,`is_captcha`) values (1,'list','공지사항','<div class=\"img_sub_wrap\">\r\n	<div class=\"img_sub4\">\r\n		<ul>\r\n		<li class=\"on\" onmouseover=\"this.className=\'on\'\" onmouseout=\"this.className=\'\'\" onclick=\"location.href=\'?tpf=board/list&amp;board_code=1\'\">공지사항</li>\r\n		<li onmouseover=\"this.className=\'on\'\" onmouseout=\"this.className=\'on\'\" onclick=\"location.href=\'?tpf=board/write&amp;board_code=2\'\">수강상담</li>\r\n		<li onmouseover=\"this.className=\'on\'\" onmouseout=\"this.className=\'\'\" onclick=\"location.href=\'?tpf=board/list&amp;board_code=3\'\">질문과 답변</li>\r\n		</ul>\r\n	</div>\r\n</div>','','','y',0,0,0,0,0,0,3,0,2,'y','','','y');

/*Table structure for table `board_data` */

CREATE TABLE `board_data` (
  `code` int(8) unsigned NOT NULL AUTO_INCREMENT,
  `board_code` int(6) NOT NULL COMMENT 'board code',
  `num` int(8) NOT NULL COMMENT '순번코드',
  `depth` varchar(10) NOT NULL COMMENT 'depth (A:원본글, AA:답글)',
  `member_code` int(8) DEFAULT NULL COMMENT 'member code',
  `name` varchar(30) DEFAULT NULL COMMENT '글쓴이',
  `tel` varchar(30) DEFAULT NULL COMMENT '연락처',
  `email` varchar(70) DEFAULT NULL COMMENT 'email',
  `title` varchar(200) NOT NULL COMMENT '제목',
  `content` mediumtext NOT NULL COMMENT '내용',
  `link` mediumtext COMMENT '링크URL(http://a.com|http://b.com|...)',
  `category` varchar(30) DEFAULT NULL COMMENT '카테고리',
  `start_date` date DEFAULT NULL COMMENT '이벤트 (시작일자)',
  `end_date` date DEFAULT NULL COMMENT '이벤트 (종료일자)',
  `memo_count` int(2) DEFAULT '0' COMMENT 'memo 개수',
  `hitting` int(3) DEFAULT '0' COMMENT '조회수',
  `is_notice` char(1) DEFAULT NULL COMMENT '공지사항 여부',
  `is_secret` char(1) DEFAULT NULL COMMENT '비밀글 사용여부',
  `password` varchar(50) DEFAULT NULL COMMENT '비밀글 사용시 비번',
  `ip` varchar(16) DEFAULT NULL COMMENT 'ip',
  `reg_date` datetime DEFAULT NULL COMMENT '등록일자',
  PRIMARY KEY (`code`),
  KEY `FK_board1` (`board_code`),
  KEY `board_data_index2` (`board_code`,`num`,`depth`),
  KEY `board_data_index1` (`board_code`,`is_notice`,`category`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8;

/*Data for the table `board_data` */

insert  into `board_data`(`code`,`board_code`,`num`,`depth`,`member_code`,`name`,`tel`,`email`,`title`,`content`,`link`,`category`,`start_date`,`end_date`,`memo_count`,`hitting`,`is_notice`,`is_secret`,`password`,`ip`,`reg_date`) values (1,1,-1,'A',1,'관리자','','','여기는 공지사항 입니다.','<p>안녕하세요?</p>\r\n\r\n<p>사이트 오픈 준비중입니다.</p>\r\n','https://player.vimeo.com/video/193358188','',NULL,NULL,0,17,'','','a93a167ae3c307c26646a0073555d0ad741bee7f','121.140.62.102','2016-12-06 16:38:48');
insert  into `board_data`(`code`,`board_code`,`num`,`depth`,`member_code`,`name`,`tel`,`email`,`title`,`content`,`link`,`category`,`start_date`,`end_date`,`memo_count`,`hitting`,`is_notice`,`is_secret`,`password`,`ip`,`reg_date`) values (9,1,-3,'A',1,'관리자','','','tt','<p><img alt=\"\" src=\"http://api.whoisict.com/user/attachment/201612/1482319720614988.jpg\" style=\"height:359px; width:540px\" /></p>\r\n','','',NULL,NULL,0,2,'','','a93a167ae3c307c26646a0073555d0ad741bee7f','125.187.167.43','2016-12-21 20:28:54');
insert  into `board_data`(`code`,`board_code`,`num`,`depth`,`member_code`,`name`,`tel`,`email`,`title`,`content`,`link`,`category`,`start_date`,`end_date`,`memo_count`,`hitting`,`is_notice`,`is_secret`,`password`,`ip`,`reg_date`) values (10,1,-4,'A',1,'관리자','','','tt3','<p>ttt<img alt=\"\" src=\"http://api.whoisict.com/user/attachment/201612/1482321703434534.jpg\" style=\"height:359px; width:540px\" /></p>\r\n','','',NULL,NULL,0,1,'','','a93a167ae3c307c26646a0073555d0ad741bee7f','125.187.167.43','2016-12-21 21:01:47');
insert  into `board_data`(`code`,`board_code`,`num`,`depth`,`member_code`,`name`,`tel`,`email`,`title`,`content`,`link`,`category`,`start_date`,`end_date`,`memo_count`,`hitting`,`is_notice`,`is_secret`,`password`,`ip`,`reg_date`) values (11,1,-5,'A',1,'관리자','','','tt3333','<p>223223<img alt=\"\" src=\"http://api.whoisict.com/user/attachment/201612/1482321774372416.jpg\" style=\"height:320px; width:600px\" /></p>\r\n','','',NULL,NULL,0,6,'','','a93a167ae3c307c26646a0073555d0ad741bee7f','125.187.167.43','2016-12-21 21:03:11');
insert  into `board_data`(`code`,`board_code`,`num`,`depth`,`member_code`,`name`,`tel`,`email`,`title`,`content`,`link`,`category`,`start_date`,`end_date`,`memo_count`,`hitting`,`is_notice`,`is_secret`,`password`,`ip`,`reg_date`) values (12,1,-6,'A',1,'관리자','','','test','<p>test</p>\r\n','','',NULL,NULL,0,13,'','','a93a167ae3c307c26646a0073555d0ad741bee7f','121.140.62.143','2016-12-22 10:06:53');
insert  into `board_data`(`code`,`board_code`,`num`,`depth`,`member_code`,`name`,`tel`,`email`,`title`,`content`,`link`,`category`,`start_date`,`end_date`,`memo_count`,`hitting`,`is_notice`,`is_secret`,`password`,`ip`,`reg_date`) values (13,1,-7,'A',1,'관리자','','','test2','<p>111</p>\r\n','','',NULL,NULL,0,0,'','','a93a167ae3c307c26646a0073555d0ad741bee7f','121.140.62.102','2017-01-12 15:20:31');
insert  into `board_data`(`code`,`board_code`,`num`,`depth`,`member_code`,`name`,`tel`,`email`,`title`,`content`,`link`,`category`,`start_date`,`end_date`,`memo_count`,`hitting`,`is_notice`,`is_secret`,`password`,`ip`,`reg_date`) values (14,1,-8,'A',1,'관리자','','','test2','<p>111</p>\r\n','','',NULL,NULL,0,0,'','','a93a167ae3c307c26646a0073555d0ad741bee7f','121.140.62.102','2017-01-12 15:21:28');
insert  into `board_data`(`code`,`board_code`,`num`,`depth`,`member_code`,`name`,`tel`,`email`,`title`,`content`,`link`,`category`,`start_date`,`end_date`,`memo_count`,`hitting`,`is_notice`,`is_secret`,`password`,`ip`,`reg_date`) values (15,1,-9,'A',1,'관리자','','','test2','<p>111</p>\r\n','','',NULL,NULL,0,6,'','','a93a167ae3c307c26646a0073555d0ad741bee7f','121.140.62.102','2017-01-12 15:25:37');
insert  into `board_data`(`code`,`board_code`,`num`,`depth`,`member_code`,`name`,`tel`,`email`,`title`,`content`,`link`,`category`,`start_date`,`end_date`,`memo_count`,`hitting`,`is_notice`,`is_secret`,`password`,`ip`,`reg_date`) values (16,1,-10,'A',1,'관리자','','','test2333','<body onload=\"alert(\'kkkk\')\">','','제품',NULL,NULL,0,15,'','','a93a167ae3c307c26646a0073555d0ad741bee7f','121.140.62.102','2017-01-12 15:26:11');
insert  into `board_data`(`code`,`board_code`,`num`,`depth`,`member_code`,`name`,`tel`,`email`,`title`,`content`,`link`,`category`,`start_date`,`end_date`,`memo_count`,`hitting`,`is_notice`,`is_secret`,`password`,`ip`,`reg_date`) values (23,1,-15,'A',1,'관리자','','','22','<p>333</p>\r\n','','',NULL,NULL,0,16,'y','','a93a167ae3c307c26646a0073555d0ad741bee7f','121.140.62.102','2017-02-09 14:42:52');
insert  into `board_data`(`code`,`board_code`,`num`,`depth`,`member_code`,`name`,`tel`,`email`,`title`,`content`,`link`,`category`,`start_date`,`end_date`,`memo_count`,`hitting`,`is_notice`,`is_secret`,`password`,`ip`,`reg_date`) values (24,1,-16,'A',1,'관리자','','','test','<p>KTOA 스마트워크센터 중소벤처기업 지원안내</p>\r\n\r\n<p>본 서비스는 벤처기업의 원격근무, 모바일 근무 등 최신정보통신 기술(ICT)을 통한 유연한 근무형태의 스마트워크 활성화 지원사업으로 미래창조과학부 주관으로 한국통신사업자연합회(KTOA) 스마트워크센터에서 추진되는 공익사업으로, 서비스 신청을 받아 한정적으로 지원하는 시범사업입니다.&nbsp;<br />\r\n&nbsp; * 관련근거 : 한국정보화진흥원 NIA 2016-기술-위30( 2016.11.14, 2016년 스마트워크 활성화 기반조성 사업 협약서)</p>\r\n\r\n<p>1. 모바일-VPN 무상제공 이용신청 접수</p>\r\n\r\n<p>&nbsp;?? 대상 : 전국 각 지역 중소벤처 임직원 100명 선정<br />\r\n&nbsp;&nbsp; - KT 모바일고객 한정 년중 전국범위 모바일 데이터 무제한 이용가<br />\r\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; * 현재, KT만 기가오피스 부가서비스로 선도적 서비스 제공<br />\r\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; * 이용료 12만원 KTOA 대납(1인당 월1만원*12개월)<br />\r\n&nbsp;&nbsp; - 각 기업당 대외활동이 잦은 외부 출장직원 대상<br />\r\n&nbsp;?? 이용절차 : 신청접수 후 통보<br />\r\n&nbsp;&nbsp; - 접수 : KTOA 스마트워크센터 임중원 팀장(<a href=\"mailto:im0389@)ktoa.or.kr\">im0389@)ktoa.or.kr</a>)<br />\r\n&nbsp;&nbsp; - 문의 : 02-2015-9141, KT모바일-VPN 담당 유병현 차장(010- 6806-0053)<br />\r\n&nbsp;&nbsp; - 신청양식 :</p>\r\n\r\n<p>기업체명 :<br />\r\n담당자명 :&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 연락 전화번호:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 이메일 :<br />\r\n신청자 성명<br />\r\n휴대폰 번호<br />\r\n이메일주소</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p><br />\r\n&nbsp;?? 서비스 제공시기 : 2017년 1월 ? 12월(1년간)</p>\r\n\r\n<p>2. KT 비즈메카(그룹웨어) 1년간 무상이용 신청기업 접수</p>\r\n\r\n<p>&nbsp;?? 대상 : 전국 각 지역 100개 중소벤처(총 500명) 선정<br />\r\n&nbsp;?? 제공서비스 : 1년간 무상제공(KTOA 스마트워크센터 비용대납) 지원<br />\r\n&nbsp;&nbsp; - 보안화된 유무선 겸용 그룹웨어(전자결재, 이메일, SNS, 인사?회계?유통관리, 모바일오피스 등) 서비스(통신사 서버이용 설치비 없음)<br />\r\n&nbsp;&nbsp; - 전자우편 용량(기본 1GB + 추가 프로모션 1GB) 제공<br />\r\n&nbsp;&nbsp; - 공용 문서함 용량(기본 500MB + 추가 프로모션 500MB) 제공<br />\r\n&nbsp;&nbsp; - 1년 무상제공 후 1년후 인당 월3,500원(표준요금 6,650원)으로 다량이용 할인<br />\r\n&nbsp;?? 이용절차 : 신청접수 후 선정 및 개별 통보<br />\r\n&nbsp;&nbsp; - 접수 : KTOA 스마트워크센터 임중원 팀장(<a href=\"mailto:im0389@)ktoa.or.kr\">im0389@)ktoa.or.kr</a>)<br />\r\n&nbsp;&nbsp; - 문의 : 02-2015-9141, KT그룹웨어 담당 이승진 대리 (010- 8489-5240)<br />\r\n&nbsp;&nbsp; - 신청양식</p>\r\n\r\n<p>기업체명<br />\r\n담당자<br />\r\n연락번호<br />\r\n이메일주소<br />\r\n이용직원수<br />\r\n(2017충원인원 포함)</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p><br />\r\n&nbsp;?? 서비스 제공시기 : 2017년 1월 ? 12월(1년간)</p>\r\n\r\n<p><br />\r\n그룹웨어와 모바일 VPN은 벤처기업의 모바일오피스화에 따른 경영효율화에기여할 것으로 보이는 서비스이므로 2가지 서비스를 병행 또는 어느 하나를 선택적으로 이용가능하므로 벤처기업 임직원들께서 많이 신청하셔서 활용해주시기 바랍니다.</p>\r\n\r\n<p>2016. 12. 22</p>\r\n\r\n<p>한국통신사업자연합회 산업지원실장</p>\r\n','','제품',NULL,NULL,0,5,'','','a93a167ae3c307c26646a0073555d0ad741bee7f','121.140.62.102','2017-02-21 17:15:53');
insert  into `board_data`(`code`,`board_code`,`num`,`depth`,`member_code`,`name`,`tel`,`email`,`title`,`content`,`link`,`category`,`start_date`,`end_date`,`memo_count`,`hitting`,`is_notice`,`is_secret`,`password`,`ip`,`reg_date`) values (25,1,-17,'A',1,'관리자','','','test55555 ','<p>KTOA 스마트워크센터 중소벤처기업 지원안내</p>\r\n\r\n<p>본 서비스는 벤처기업의 원격근무, 모바일 근무 등 최신정보통신 기술(ICT)을 통한 유연한 근무형태의 스마트워크 활성화 지원사업으로 미래창조과학부 주관으로 한국통신사업자연합회(KTOA) 스마트워크센터에서 추진되는 공익사업으로, 서비스 신청을 받아 한정적으로 지원하는 시범사업입니다.&nbsp;<br />\r\n&nbsp; * 관련근거 : 한국정보화진흥원 NIA 2016-기술-위30( 2016.11.14, 2016년 스마트워크 활성화 기반조성 사업 협약서)</p>\r\n\r\n<p>1. 모바일-VPN 무상제공 이용신청 접수</p>\r\n\r\n<p>&nbsp;?? 대상 : 전국 각 지역 중소벤처 임직원 100명 선정<br />\r\n&nbsp;&nbsp; - KT 모바일고객 한정 년중 전국범위 모바일 데이터 무제한 이용가<br />\r\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; * 현재, KT만 기가오피스 부가서비스로 선도적 서비스 제공<br />\r\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; * 이용료 12만원 KTOA 대납(1인당 월1만원*12개월)<br />\r\n&nbsp;&nbsp; - 각 기업당 대외활동이 잦은 외부 출장직원 대상<br />\r\n&nbsp;?? 이용절차 : 신청접수 후 통보<br />\r\n&nbsp;&nbsp; - 접수 : KTOA 스마트워크센터 임중원 팀장(<a href=\"mailto:im0389@)ktoa.or.kr\">im0389@)ktoa.or.kr</a>)<br />\r\n&nbsp;&nbsp; - 문의 : 02-2015-9141, KT모바일-VPN 담당 유병현 차장(010- 6806-0053)<br />\r\n&nbsp;&nbsp; - 신청양식 :</p>\r\n\r\n<p>기업체명 :<br />\r\n담당자명 :&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 연락 전화번호:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 이메일 :<br />\r\n신청자 성명<br />\r\n휴대폰 번호<br />\r\n이메일주소</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p><br />\r\n&nbsp;?? 서비스 제공시기 : 2017년 1월 ? 12월(1년간)</p>\r\n\r\n<p>2. KT 비즈메카(그룹웨어) 1년간 무상이용 신청기업 접수</p>\r\n\r\n<p>&nbsp;?? 대상 : 전국 각 지역 100개 중소벤처(총 500명) 선정<br />\r\n&nbsp;?? 제공서비스 : 1년간 무상제공(KTOA 스마트워크센터 비용대납) 지원<br />\r\n&nbsp;&nbsp; - 보안화된 유무선 겸용 그룹웨어(전자결재, 이메일, SNS, 인사?회계?유통관리, 모바일오피스 등) 서비스(통신사 서버이용 설치비 없음)<br />\r\n&nbsp;&nbsp; - 전자우편 용량(기본 1GB + 추가 프로모션 1GB) 제공<br />\r\n&nbsp;&nbsp; - 공용 문서함 용량(기본 500MB + 추가 프로모션 500MB) 제공<br />\r\n&nbsp;&nbsp; - 1년 무상제공 후 1년후 인당 월3,500원(표준요금 6,650원)으로 다량이용 할인<br />\r\n&nbsp;?? 이용절차 : 신청접수 후 선정 및 개별 통보<br />\r\n&nbsp;&nbsp; - 접수 : KTOA 스마트워크센터 임중원 팀장(<a href=\"mailto:im0389@)ktoa.or.kr\">im0389@)ktoa.or.kr</a>)<br />\r\n&nbsp;&nbsp; - 문의 : 02-2015-9141, KT그룹웨어 담당 이승진 대리 (010- 8489-5240)<br />\r\n&nbsp;&nbsp; - 신청양식</p>\r\n\r\n<p>기업체명<br />\r\n담당자<br />\r\n연락번호<br />\r\n이메일주소<br />\r\n이용직원수<br />\r\n(2017충원인원 포함)</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p><br />\r\n&nbsp;?? 서비스 제공시기 : 2017년 1월 ? 12월(1년간)</p>\r\n\r\n<p><br />\r\n그룹웨어와 모바일 VPN은 벤처기업의 모바일오피스화에 따른 경영효율화에기여할 것으로 보이는 서비스이므로 2가지 서비스를 병행 또는 어느 하나를 선택적으로 이용가능하므로 벤처기업 임직원들께서 많이 신청하셔서 활용해주시기 바랍니다.</p>\r\n\r\n<p>2016. 12. 22</p>\r\n\r\n<p>한국통신사업자연합회 산업지원실장</p>\r\n','','제품',NULL,NULL,0,6,'','','a93a167ae3c307c26646a0073555d0ad741bee7f','121.140.62.102','2017-02-21 17:16:19');
insert  into `board_data`(`code`,`board_code`,`num`,`depth`,`member_code`,`name`,`tel`,`email`,`title`,`content`,`link`,`category`,`start_date`,`end_date`,`memo_count`,`hitting`,`is_notice`,`is_secret`,`password`,`ip`,`reg_date`) values (26,1,-18,'A',1,'관리자','','','TEST 555556 6666','<table border=\"1\" cellpadding=\"1\" cellspacing=\"1\" style=\"width:500px\">\r\n	<tbody>\r\n		<tr>\r\n			<td>aDsdASD</td>\r\n			<td>FASDFASDF</td>\r\n		</tr>\r\n		<tr>\r\n			<td>ASDFASDF</td>\r\n			<td>SDF</td>\r\n		</tr>\r\n		<tr>\r\n			<td>ASDF</td>\r\n			<td>ASDFASDF</td>\r\n		</tr>\r\n	</tbody>\r\n</table>\r\n\r\n<p>&nbsp;</p>\r\n','','제품',NULL,NULL,0,31,'','','a93a167ae3c307c26646a0073555d0ad741bee7f','121.140.62.102','2017-02-21 17:17:23');
insert  into `board_data`(`code`,`board_code`,`num`,`depth`,`member_code`,`name`,`tel`,`email`,`title`,`content`,`link`,`category`,`start_date`,`end_date`,`memo_count`,`hitting`,`is_notice`,`is_secret`,`password`,`ip`,`reg_date`) values (27,1,-19,'A',1,'관리자','','','태그처리','<p>&lt;st<x>yle type=&quot;text/css&quot;&gt; A:li<x>nk {color:#ffffff; text-decoration:none}A:visited {color:#ffffff; text-decoration:none }A:active {color:#ffffff; text-decoration:none}A:hover {color:#ffffff; text-decoration:none}&lt;/st<x>yle&gt;&lt;div st<x>yle=&quot; width:100%;height:100%;border:0px solid #a7a7a7;margin:auto; &quot;&gt;&lt;/div&gt;<br />\r\n&lt;a href=&quot;http://whoalba.3000alba.com&quot; target=&quot;_blank&quot;&gt;코코알바&lt;/a&gt;<br />\r\n&lt;a href=&quot;http://whoalba.3000alba.com&quot; target=&quot;_blank&quot;&gt;노래방도우미&lt;/a&gt;<br />\r\n&lt;a href=&quot;http://whoalba.3000alba.com&quot; target=&quot;_blank&quot;&gt;유흥알바&lt;/a&gt;<br />\r\n&lt;a href=&quot;http://whoalba.3000alba.com&quot; target=&quot;_blank&quot;&gt;밤알바&lt;/a&gt;<br />\r\n&lt;a href=&quot;http://whoalba.3000alba.com&quot; target=&quot;_blank&quot;&gt;룸알바&lt;/a&gt;<br />\r\n&lt;a href=&quot;http://whoalba.3000alba.com&quot; target=&quot;_blank&quot;&gt;여성알바&lt;/a&gt;<br />\r\n&lt;a href=&quot;http://whoalba.3000alba.com&quot; target=&quot;_blank&quot;&gt;업소알바&lt;/a&gt;<br />\r\n&lt;a href=&quot;http://whoalba.3000alba.com&quot; target=&quot;_blank&quot;&gt;텐프로&lt;/a&gt;<br />\r\n&lt;a href=&quot;http://whoalba.3000alba.com&quot; target=&quot;_blank&quot;&gt;바알바&lt;/a&gt;<br />\r\n&lt;a href=&quot;http://whoalba.3000alba.com&quot; target=&quot;_blank&quot;&gt;고소득알바&lt;/a&gt;<br />\r\n&nbsp;</p>\r\n','','제품',NULL,NULL,0,4,'','','bec223b420dbc149e0cedc66d79e8783cbf6af1d','121.140.62.102','2017-02-27 10:35:20');
insert  into `board_data`(`code`,`board_code`,`num`,`depth`,`member_code`,`name`,`tel`,`email`,`title`,`content`,`link`,`category`,`start_date`,`end_date`,`memo_count`,`hitting`,`is_notice`,`is_secret`,`password`,`ip`,`reg_date`) values (29,1,-20,'A',0,'1--23','','','2-','<p>3-</p>\r\n','','제품',NULL,NULL,0,69,'','','bec223b420dbc149e0cedc66d79e8783cbf6af1d','121.140.62.102','2017-02-27 16:21:53');
insert  into `board_data`(`code`,`board_code`,`num`,`depth`,`member_code`,`name`,`tel`,`email`,`title`,`content`,`link`,`category`,`start_date`,`end_date`,`memo_count`,`hitting`,`is_notice`,`is_secret`,`password`,`ip`,`reg_date`) values (30,1,-21,'A',1,'관리자','','','게시판 테스트 입니다.','<p><strong>[OSEN=부산, 조형래 기자]</strong></p>\r\n\r\n<p><span style=\"font-family:궁서\">SK 와이번스가 트레이 힐만 신임 감독 체제에서</span></p>\r\n\r\n<p><em>KBO리그 공식전 첫 승을 거뒀다.&nbsp;</em></p>\r\n\r\n<p><s>SK는 14일 부산 사직구장에서 열린 </s></p>\r\n\r\n<p style=\"text-align:center\">&lsquo;2017 타이어뱅크 KBO리그&rsquo;</p>\r\n\r\n<p style=\"text-align:right\">롯데 자이언츠와의</p>\r\n\r\n<p><span style=\"color:#e74c3c\">시범경기 개막전에서 3-2로 신승을 거뒀다.</span></p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>gggghhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhh</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p><strong><span style=\"background-color:#c0392b\">SK는 </span><em><span style=\"font-size:10px\"><span style=\"background-color:#f1c40f\">시범경기 첫 승, 롯데는 첫 패를 당했다</span></span></em></strong></p>\r\n','','','2017-03-14','2017-03-15',0,119,'','','bec223b420dbc149e0cedc66d79e8783cbf6af1d','121.140.62.102','2017-02-27 17:35:13');
insert  into `board_data`(`code`,`board_code`,`num`,`depth`,`member_code`,`name`,`tel`,`email`,`title`,`content`,`link`,`category`,`start_date`,`end_date`,`memo_count`,`hitting`,`is_notice`,`is_secret`,`password`,`ip`,`reg_date`) values (31,1,-22,'A',1,'관리자','','','이벤트 게시판입니다','<p>ㅋㅋ</p>\r\n','','','2017-03-07','2017-03-29',2,28,'','','a93a167ae3c307c26646a0073555d0ad741bee7f','121.140.62.102','2017-03-16 18:00:06');
insert  into `board_data`(`code`,`board_code`,`num`,`depth`,`member_code`,`name`,`tel`,`email`,`title`,`content`,`link`,`category`,`start_date`,`end_date`,`memo_count`,`hitting`,`is_notice`,`is_secret`,`password`,`ip`,`reg_date`) values (32,1,-23,'A',1,'관리자2','','','ㅋㅋㅋ2','<p>ㅋㅋ2</p>\r\n','','','2017-03-07','2017-03-08',0,43,'','','a93a167ae3c307c26646a0073555d0ad741bee7f','121.140.62.102','2017-03-16 18:00:14');
insert  into `board_data`(`code`,`board_code`,`num`,`depth`,`member_code`,`name`,`tel`,`email`,`title`,`content`,`link`,`category`,`start_date`,`end_date`,`memo_count`,`hitting`,`is_notice`,`is_secret`,`password`,`ip`,`reg_date`) values (33,3,-1,'A',1,'관리자','휴대전화','이메일','Q&A 게시판 입니다.','<p>ㅋㅋ</p>\r\n','','','0000-00-00','0000-00-00',0,0,'','','a93a167ae3c307c26646a0073555d0ad741bee7f','121.140.62.102','2017-03-16 18:05:25');
insert  into `board_data`(`code`,`board_code`,`num`,`depth`,`member_code`,`name`,`tel`,`email`,`title`,`content`,`link`,`category`,`start_date`,`end_date`,`memo_count`,`hitting`,`is_notice`,`is_secret`,`password`,`ip`,`reg_date`) values (34,1,-24,'A',1,'관리자','','','test','<p>test</p>\r\n','','','0000-00-00','0000-00-00',0,23,'','','a93a167ae3c307c26646a0073555d0ad741bee7f','121.140.62.105','2017-03-23 10:49:12');
insert  into `board_data`(`code`,`board_code`,`num`,`depth`,`member_code`,`name`,`tel`,`email`,`title`,`content`,`link`,`category`,`start_date`,`end_date`,`memo_count`,`hitting`,`is_notice`,`is_secret`,`password`,`ip`,`reg_date`) values (35,1,-25,'A',1,'관리자','','','123','<p>123123</p>\r\n','','','0000-00-00','0000-00-00',0,6,'','','28291cb4d96c63c7d8a2bec32813d956b260f6d1','121.140.62.105','2017-03-23 14:57:29');

/*Table structure for table `board_memo` */

CREATE TABLE `board_memo` (
  `code` int(6) NOT NULL AUTO_INCREMENT,
  `board_data_code` int(8) NOT NULL COMMENT 'board_data code',
  `name` varchar(30) NOT NULL COMMENT '이름',
  `password` varchar(50) NOT NULL COMMENT '비밀번호',
  `content` varchar(200) NOT NULL COMMENT '내용',
  `ip` varchar(16) NOT NULL COMMENT 'ip',
  `reg_date` datetime DEFAULT NULL COMMENT '등록일자',
  PRIMARY KEY (`code`),
  KEY `board_comment_index1` (`board_data_code`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

/*Data for the table `board_memo` */

insert  into `board_memo`(`code`,`board_data_code`,`name`,`password`,`content`,`ip`,`reg_date`) values (1,12,'admin','28291cb4d96c63c7d8a2bec32813d956b260f6d1','tttttt adf asdfa sdfasdf','121.140.62.102','2016-12-22 19:16:12');
insert  into `board_memo`(`code`,`board_data_code`,`name`,`password`,`content`,`ip`,`reg_date`) values (3,29,'admin','bec223b420dbc149e0cedc66d79e8783cbf6af1d','ㅅㄷㄴㅅ','121.140.62.102','2017-02-27 17:08:28');
insert  into `board_memo`(`code`,`board_data_code`,`name`,`password`,`content`,`ip`,`reg_date`) values (4,30,'admin','bec223b420dbc149e0cedc66d79e8783cbf6af1d','asdf','121.140.62.102','2017-02-27 17:57:34');
insert  into `board_memo`(`code`,`board_data_code`,`name`,`password`,`content`,`ip`,`reg_date`) values (7,31,'55','bec223b420dbc149e0cedc66d79e8783cbf6af1d','adf','121.140.62.102','2017-03-23 14:23:31');
insert  into `board_memo`(`code`,`board_data_code`,`name`,`password`,`content`,`ip`,`reg_date`) values (13,31,'111','bec223b420dbc149e0cedc66d79e8783cbf6af1d','zz','121.140.62.102','2017-03-23 15:21:01');

/*Table structure for table `captcha` */

CREATE TABLE `captcha` (
  `ip` varchar(15) NOT NULL COMMENT '접속 ip',
  `auth_key` varchar(10) NOT NULL COMMENT '인증키',
  `reg_date` datetime NOT NULL COMMENT '생성일자',
  PRIMARY KEY (`ip`),
  KEY `captcha_index1` (`ip`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `captcha` */

insert  into `captcha`(`ip`,`auth_key`,`reg_date`) values ('121.140.62.102','0589','2017-03-23 16:47:50');
insert  into `captcha`(`ip`,`auth_key`,`reg_date`) values ('121.140.62.105','1467','2017-03-23 15:10:55');
insert  into `captcha`(`ip`,`auth_key`,`reg_date`) values ('223.62.219.40','6148','2017-02-27 17:28:03');

/*Table structure for table `category` */

CREATE TABLE `category` (
  `code` int(4) NOT NULL AUTO_INCREMENT,
  `category_code` int(8) NOT NULL COMMENT '카테고리 코드(1010..)',
  `order_code` int(5) NOT NULL COMMENT '순서코드',
  `title` varchar(50) DEFAULT NULL COMMENT '제목',
  `status` enum('y','n') DEFAULT 'y' COMMENT '상태',
  PRIMARY KEY (`code`),
  UNIQUE KEY `category_ndex1` (`category_code`),
  KEY `category_ndex2` (`category_code`,`status`,`order_code`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Data for the table `category` */

insert  into `category`(`code`,`category_code`,`order_code`,`title`,`status`) values (2,10,1,'가전','y');

/*Table structure for table `contract` */

CREATE TABLE `contract` (
  `code` int(1) NOT NULL,
  `provision` text COMMENT '이용약관',
  `privacy` text COMMENT '개인정보취급방침',
  `distinguish` text COMMENT '고유식별정보처리',
  `personal` text COMMENT '개인정보3자제공',
  `investment` text COMMENT '투자신청 이용약관',
  `dealing` text COMMENT '여신거래 기본약관',
  `pincipes` text COMMENT '윤리강령',
  PRIMARY KEY (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `contract` */

insert  into `contract`(`code`,`provision`,`privacy`,`distinguish`,`personal`,`investment`,`dealing`,`pincipes`) values (1,'제1조 (목적)\r\n제2조 (용어의 정의)\r\n제3조 (서비스의 종류)\r\n제4조 (서비스 이용계약의 성립)\r\n제5조 (회원가입)\r\n제6조 (회원 정보의 수집과 보호)\r\n제7조 (회원의 의무)\r\n제8조 (서비스 이용 권리의 양도 등)\r\n제9조 (계약해지 및 이용제한)\r\n제10조 (전자메일에 대한 회원의 의무와 책임)\r\n제11조 (회원정보의 변경)\r\n제12조 (쿠키의 운용 및 활용)\r\n제13조 (링크사이트에 대한 책임)\r\n제14조 (면책)\r\n제15조 (저작권의 귀속 및 이용제한)\r\n제16조 (이용약관의 효력 및 변경)\r\n제17조 (약관 위반 시 책임)\r\n제18조 (서비스 중지)\r\n제19조 (제휴사 또는 광고주와의 거래)\r\n제20조 (정보의 제공)\r\n제21조 (이용료 등)\r\n제22조 (채권의 매각)\r\n제23조 (분쟁조정 및 관할법원)\r\n제24조 (투자자 보호)\r\n제25조 (관계법령과의 관계)\r\n\r\n\r\n제 1조 (목적)  이 약관은 (이하 \"회사\" 또는 \"‘골든벨(www.골든벨.net)’ \"라 함)가 운영하는 사이트(이하 \"사이트\"라 함)에서 제공하는 모든 웹 서비스(이하 \"서비스\"라 함)를 이용함에 있어 \"회사\"와 회원 간의 권리, 의무 및 책임 사항을 규정함을 목적으로 합니다. \"회사\"는 시스템에 관한 제반 기술과 운영에 대한 모든 권한을 갖고 있습니다.\r\n\r\n제 2 조 (용어의 정의) 이 약관에서 사용하는 용어의 정의는 다음과 같습니다.\r\n\r\n①. \"사이트\"란 회사가 서비스 또는 용역을 회원에게 제공하기 위하여 컴퓨터 등 정보통신설비를 이용하여 제공할 수 있도록 설정한 가상의 영업장 또는 서비스 공간을 말하며, 회원 계정(ID 및 PASSWORD)을 이용하여 서비스를 제공받을 수 있는 아래의 사이트를 말합니다. 아울러 사이트를 운영하는 사업자의 의미로도 사용합니다.\r\n * ‘(www.골든벨.net)’  \r\n\r\n\r\n②. \"서비스\"는 \"회사\"의 홈페이지 및 \"회사\"가 직접 운영하는 웹사이트 등에서 제공하는 온라인상의 모든 서비스를 말합니다.\r\n\r\n③. \"회원\"은 본 약관에 동의하고 \"회사\"가 운영하는 사이트에서 서비스를 제공받는 자(이하 \"회원\"이라 함)를 말합니다. \"회사\"의 정책에 의하여 회원의 등급을 구분하여 서비스 이용범위나 혜택 등을 다르게 적용할 수 있습니다.\r\n\r\n④. \"ID\"는 회원으로 로그인하기 위하여 \"사이트\"에 등록한 E-MAIL을 말합니다.\r\n \r\n⑤. \"PASSWORD\"는 회원의 비밀 보호 및 회원 본인임을 확인하고 서비스에 제공되는 각종 정보의 보안을 위해 회원이 직접 설정하며 \"회사\"가 승인하는 6자리 이상 20자리 이하의 영문과 숫자, 특수문자의 혼합으로 표기한 암호 문자를 말합니다.\r\n\r\n⑥. “투자자”는 “사이트”를 통해서 “여신회사”의 대출채권 원리금 수취 권을 취득한 회원을 말합니다.\r\n\r\n⑦. “매입보증인”은 “여신회사”가 대출채권의 담보권실행절차에 들어갔을 때 해당대출채권과 담보권을 매입하기로 하고 소정의 보증금을 납부한 회원을 말합니다.\r\n\r\n⑧. \"여신회사\"는 \"회사\"와 특정한 제휴 및 협약을 맺고 채무자 및 채무자 제공 담보에 대한 심사, 대부계약체결, 채권관리, 추심 등에 관한 역할을 수행하는 법인으로서 “㈜에치제이인베스먼트에이엠씨대부”와 “‘골든벨(www.골든벨.net)’ 대부”를 말합니다.\r\n\r\n⑨. “채무자”는 “여신회사”와 대부계약을 체결하고 “여신회사”에 대부계약 상의 대출 원리금을 납부할 의무가 있는 자를 말합니다.\r\n\r\n⑩. “담보”는 “채무자”가 “여신회사”와 대부계약을 체결할 때에 “여신회사”에 제공한 동산 및 부동산 담보를 말합니다.\r\n\r\n제 3 조 (서비스의 종류) \"회사\"가 회원에게 제공하는 서비스 종류는 다음과 같습니다.\r\n\r\n①. 금융 거래장소(Platform) 서비스\r\n\"회사\"의 사이트를 통하여 투자자가 해당 채권에 대한 원리금수취권리의 참가가 성사되도록 하는 모든 활동에 대하여 온라인으로 제공하는 서비스 및 기타 이용 서비스, 관련 부가서비스 일체를 말합니다.\r\n\r\n②. 기타 서비스 : “회사”가 사이트를 통하여 제공하는 광고서비스, 채권매매서비스 등 기타의 서비스를 말합니다.\r\n\r\n제 4 조 (서비스 이용계약의 성립 등)\r\n\r\n①. 서비스 이용계약은 \"회원\"이 되고자 하는 자가 본 약관에 동의하고 정해진 가입양식에 회원정보(성명, ID, PASSWORD, 기타 \"회사\"가 필요하다고 인정하는 사항 등)를 직접 기입하여 회원신청을 하면 \"회사\"가 이에 대해 승낙함으로써 성립됩니다.\r\n\r\n②. \"회원\"에 가입하는 자는 \"회사\"가 요구하는 개인정보를 정확하게 제공하여야 합니다.\r\n\r\n③. \"회사\"는 ID, PASSWORD를 접수 받아 이를 관리합니다.\r\n\r\n④. 이 약관은 \"회사\"의 사이트에 게시하거나 기타의 방법으로 회원에게 공지함으로써 효력이 발생합니다.\r\n\r\n⑤. \"회원\"은 약관의 변경에 대하여 주의 의무를 다하여야 하며, 변경된 약관의 부지로 인한 피해는 회사가 책임지지 않습니다. 이용고객이 서비스 이용을 신청하는 것은 약관에 따라 신청양식에 기재된 회원정보의 수집/이용 및 \"회사\"가 제정한 \"개인정보보호정책\" 등 각종 정책과 서비스 이용과정에서 준수할 필요가 있어 \"회사\"가 수시로 공지하는 사항에 대해 동의한 것으로 간주됩니다.\r\n\r\n⑥. 타인의 개인정보를 도용하여 허위 가입한 회원은 회사가 제공하는 \"서비스\"를 이용할 수 없으며 이에 따른 민사, 형사상의 모든 책임은 가입한 회원에게 있습니다.\r\n\r\n제 5 조 (회원가입)\r\n\r\n①. 사이트가 정한 가입 양식에 따라 회원정보를 기입함으로써 회원가입을 신청합니다.\r\n\r\n②. \"회사\"는 특정 서비스를 제공하기 위해 회원에게 별도 또는 추가적인 가입절차를 요청할 수 있으며, 이러한 특정 서비스를 이용할 경우 해당 서비스에 대한 이용약관, 규정 또는 세칙 등이 본 약관보다 우선 적용됩니다.\r\n\r\n③. \"회사\"는 회원이 사이트 및 서비스를 쉽게 이용할 수 있도록 회원의 아이디를 포함한 회원제를 관리하거나 사이트 또는 서비스를 개선, 변경할 수 있습니다.\r\n\r\n④. 이용고객은 회원가입 시에 \"회사\"가 서비스를 제공함에 있어서 필요한 정보를 제공해야 하며, \"회사\"가 특별히 요청할 경우 서류를 제출해야 합니다.\r\n\r\n⑤. \"회원\"이 사실과 다른 정보 또는 허위 정보를 기입하거나 추후 그러한 정보임이 밝혀질 경우 운영자의 권한으로 서비스 이용을 일시 정지하거나 영구정지 및 이용 계약을 해지할 수 있습니다. 이로 인하여 회사 또는 제3자에게 발생한 손해는 해당 회원이 책임을 집니다. 다만, 회사의 고의나 과실에 의하여 손해가 발생한 경우에는 회사가 손해를 부담합니다.\r\n\r\n⑥. \"회사\"는 회원이 가입 신청한 경우 법령에 따라 이용 가능한 신용정보업자 또는 신용정보집중기관을 통하여 본인여부를 확인할 수 있으며, \"회사\"는 실명확인절차를 취할 수 없는 이용신청에 대해서는 신청자에게 증빙자료를 요청할 수 있습니다.\r\n\r\n⑦. \"회사\"는 다음과 같은 사유가 있는 경우, 가입 신청에 대한 승낙을 유보할 수 있습니다. 이 경우, 회사는 가입 신청자에게 승낙유보의 사유, 승낙가능시기 또는 승낙에 필요한 추가요청정보 내지 자료 등 기타 승낙유보와 관련된 사항을 해당 서비스화면에 게시하거나 기타 방법을 통하여 회원에게 통지합니다.\r\n    1. 가입신청자가 이 약관 제9조에 의하여 이전에 회원자격을 상실한 적이 있는 경우, \r\n    2. 등록내용에 허위, 기재누락, 오기가 있는 경우.\r\n    3. 기타, 회원으로 등록 시 “사이트”의 운영상 현저히 지장이 있다고 판단되는 경우\r\n\r\n제 6 조 (회원정보의 수집과 보호)\r\n\r\n①. \"회사\"는 이용계약을 위하여 \"회원\"이 제공한 정보 외에도 수집목적 또는 이용목적을 밝혀 \"회원\"으로부터 필요한 정보를 수집할 수 있습니다. 이 경우, 회사는 회원으로부터 정보수집에 대한 동의를 받습니다.\r\n\r\n②. \"회사\"가 정보수집을 위하여 \"회원\"의 동의를 받는 경우, \"회사\"는 수집하는 개인정보의 항목 및 수집방법, 수집목적 및 이용목적, 개인정보의 보유 및 이용기간, 제3자에 대한 정보제공 사항(제공받는 자, 제공받는 자의 이용목적, 제공정보의 항목, 보유 및 이용기간)등을 개인정보취급방침으로 미리 명시하거나 고지합니다.\r\n회원은 정보제공에 동의하더라도 언제든지 그 동의를 철회할 수 있습니다.\r\n\r\n③. \"회원\"은 \"회사\"에게 정보를 제공하는 경우 사실대로 제공하여야 합니다.\r\n\r\n④. \"회사\"는 회원의 개인정보보호를 위하여 관리자를 최소한으로 한정하며, 회사의 고의 또는 과실로 인하여 회원정보가 분실, 도난, 유출, 변조된 경우에는 그로 인한 회원의 손해에 대하여 모든 책임을 부담합니다.\r\n\r\n⑤. \"회사\"는 관련 법령이 정하는 바에 따라서 회원정보를 포함한 개인정보를 보호하기 위하여 노력합니다. \"회원\"의 개인정보보호에 관해서는 관련법령 및 \"회사\"가 정하고 별도로 게시하는 \"개인정보취급방침\"에 정한 바에 준합니다.\r\n\r\n⑥. 이용계약이 종료된 경우, 회사는 당해 회원의 정보를 파기하는 것을 원칙으로 합니다.\r\n다만, 아래의 경우에는 회원정보를 보관합니다. 이 경우 회사는 보관하고 있는 회원정보를 그 보관의 목적으로만 이용합니다. \r\n   1. 상법 등 관계법령의 규정에 의하여 보존할 필요가 있는 경우 회사는 관계법령에서 정한 일정한 기간 동안 회원정보를 보관합니다.\r\n·  2. 회원의 탈퇴신청 등으로 이용계약이 종료된 경우, 회사는 투자자 보호 및 본 약관이 정한 제한을 의도적으로 회피하기 위한 임의 해지 방지를 위하여 상당하다고 인정되는 경우에 한하여 이용계약종료 후 1년간 ID, 성명 또는 상호, 연락처, 거래상세내역, 약관위반 행위자료 등 최소한의 필요정보를 보관합니다.\r\n   3. 회사가 이용계약을 해지하거나 회사로부터 회원자격정지조치를 받은 회원에 대해서는 재가입에 대한 승낙거부사유가 존재하는지 여부를 확인하기 위한 목적으로 이용계약종료 후 1년간 ID, 성명 또는 상호, 연락처, 주소, 해지와 회원자격정지와 관련된 정보 등 최소한의 필요정보를 보관합니다.\r\n·  4. 기타 정보수집에 관한 동의를 받을 때 보유기간을 명시한 경우에는 그 보유기간까지 회원정보를 보관합니다.\r\n\r\n⑦ 회사는 회원정보의 보호와 관리에 관한 개인정보취급방침을 회원과 회사의 서비스를 이용하고자 하는 자가 알 수 있도록 웹 사이트에 공지합니다.\r\n\r\n제 7 조 (회원의 의무)\r\n\r\n①. 회원은 서비스를 이용할 때 아래의 행위를 하지 않아야 합니다.\r\n   1. 가입신청 시 개인정보의 허위사실을 기재하는 행위\r\n   2. 다른 회원의 ID 및 PASSWORD를 부정하게 이용하는 행위\r\n   3.서비스를 이용하여 얻은 정보를 회원의 개인적인 이용 외에 복사, 가공, 번역, 2차적 저작 등을 통하여 복제, 공연, 방송, 전시, 배포, 출판 등에 이용하거나 제3자에게 제공하는 행위\r\n   4. 타인의 명예를 손상시키거나 불이익을 주는 행위\r\n   5. \"회사\"의 저작권, 제3자의 저작권 등 기타 권리를 침해하는 행위\r\n   6. 공공질서 및 미풍양속에 위반되는 내용의 정보, 문장, 도형, 음성 등을 타인에게 유포하는 행위\r\n   7. 범죄와 결부된다고 객관적으로 인정되는 행위\r\n   8. \"서비스\"와 관련된 설비의 오동작이나 정보 등의 파괴 및 혼란을 유발시키는 컴퓨터 바이러스 감염자료를 등록 또는 유포하는 행위\r\n   9. \"서비스\"의 안정적 운영을 방해할 수 있는 정보를 전송하거나 수신자의 의사에 반하여 광고성 정보를 전송하는 행위\r\n  10. 기타 관계법령에 위배되는 행위\r\n\r\n②. ID와 PASSWORD 관리에 관한 일체의 책임은 \"회원\" 본인에게 있습니다. \"회원\"에게 부여된 ID와 PASSWORD를 제3자에게 대여 또는 양도하거나 이와 유사한 행위를 하여서는 안 되며, ID와 PASSWORD의 관리소홀, 부정이용에 의하여 발생하는 모든 결과에 대한 책임은 회원 본인에게 있습니다.\r\n\r\n③. \"회원\"은 자신의 ID가 부정하게 이용된 경우, 즉시 자신의 PASSWORD를 변경하고 그 사실을 \"회사\"에 통보하여야 합니다.\r\n\r\n④. \"회원\"은 본인의 신상관련 사항이 변경되었을 때는 \"사이트\"를 통하여 수정하거나 유선으로 지체 없이 \"회사\"에 통보하여야 합니다.\r\n\r\n⑤. \"회원\"은 \"회사\"의 사전승낙 없이 \"서비스\"를 이용하여 영업활동을 할 수 없으며, 그 영업활동으로 인한 결과에 대하여 \"회사\"는 책임을 지지 아니합니다.\r\n\r\n⑥. \"회원\"은 이 약관 및 관계법령에서 규정한 사항과 서비스 이용안내 또는 주의사항을 성실히 준수하여야 합니다.\r\n\r\n⑦. \"회원\"은 내용별로 \"회사\"가 서비스 공지사항에 게시하거나 별도 공지한 이용 제한사항을 준수하여야 합니다.\r\n\r\n⑧. \"회원\"은 \"회사\"의 사전 동의 없이 \"서비스\"의 이용권한, 기타 이용 계약상 지위를 타인에게 양도, 증여할 수 없으며 이를 담보로 제공할 수 없습니다.\r\n\r\n⑨. \"회원\"은 반드시 본인의 실명으로 등록하여야 하며, 타인의 명의를 도용하는 경우 일체의 보호를 받을 수 없고, 관계법령에 의하여 처벌받을 수 있습니다.\r\n\r\n제 8 조 (서비스 이용 권리의 양도 등)\r\n\r\n①. \"회사\"의 사전 동의 없이 \"서비스\"의 이용권한, 기타 이용 계약상 지위를 타인에게 양도, 증여할 수 없으며, 이를 담보로 제공할 수 없습니다.\r\n\r\n②. \"회사\"는 보안 및 ID 정책, \"서비스\"의 원활한 제공 등과 같은 이유로 제 15 조의 방법으로 고지를 통하여 \"회원\"의 ID 변경을 요구하거나 변경할 수 있습니다.\r\n\r\n제 9 조 (계약해지 및 이용제한)\r\n\r\n①. \"회사\"는 \"서비스\" 이용을 신청한 고객에 대하여 접수순서에 따라 \"서비스\" 이용을 승낙합니다. \"회원\"이 서비스 이용계약을 해지하고자 할 경우에는 본인이 \"회사\" 홈페이지 또는 \"회사\"가 정한 별도의 방법을 이용하여 \"회사\"에 해지 신청을 하여야 합니다. 이 경우 \"회사\"는 소정의 해지 절차를 거쳐 해지를 요청한 \"회원\"의 정보를 삭제합니다.\r\n\r\n②. \"회사\"는 서비스 이용신청이 다음 각 호에 해당하는 것으로 판단되거나 서비스 이용신청 이후의 이용 상황이 다음 각 호의 하나로 판단되는 경우에는 이용 승낙을 하지 아니하거나 또는 이용을 제한하고 탈퇴시킬 수 있습니다. \r\n    1. ID와 PASSWORD 등 회원 고유정보를 타인에게 누설하거나 타인의 ID 및 PASSWORD를 도용한 경우\r\n    2. 서비스 운영을 고의로 방해한 경우\r\n    3. 가입한 성명이 실명이 아닌 경우\r\n    4. 동일 이용자가 다른 ID로 이중 등록을 한 경우\r\n    5. 공공질서 및 미풍양속에 저해되는 내용을 고의로 유포시킨 경우\r\n    6. 회원이 국익 또는 사회적 공익을 저해할 목적으로 서비스 이용을 계획 또는 실행하는 경우\r\n    7. 타인의 명예를 손상시키거나 불이익을 주는 행위를 한 경우\r\n    8. 서비스의 안정적 운영을 방해할 목적으로 정보를 전송하거나 광고성 정보를 전송하는 경우\r\n    9. 통신설비의 오동작이나 정보 등의 파괴를 유발시키는 컴퓨터 바이러스 프로그램 등을 유포하는 경우\r\n   10. \"회사\", 타 회원 또는 제3자의 지적재산권을 침해하는 경우\r\n   11. \"회사\"의 서비스 정보를 이용하여 얻은 정보를 \"회사\"의 사전승낙 없이 복제 또는 유통시키거나 상업적으로 이용하는 경우\r\n   12. 회원이 게시판 등에 음란물을 게재하거나 음란사이트를 연결(링크)하는 경우\r\n   13. 서비스 이용약관을 포함하여 기타 \"회사\"가 정한 이용조건 및 관계법령을 위반해 더 이상의 서비스 제공이 어렵다고 판단되는 경우\r\n   14. 스토킹(stalking) 등 다른 이용자를 괴롭히는 행위를 하는 경우\r\n   15. \"회사\"가 신청양식에서 정한 회원정보가 미비된 경우\r\n   16. 범죄행위를 목적으로 서비스 신청을 한 것으로 판단되는 경우\r\n   17. 본 약관에서 예정하고 있는 거래 이외의 영리를 추구할 목적으로 서비스 가입을 한 것으로 판단되는 경우\r\n   18. 기 탈퇴한 고객으로서 관리자의 판단으로 재가입이 웹사이트의 운영에 저해가 된다고 판단되는 경우\r\n   19. 주관적인 글의 게재로 인하여 타 회원에게 부정적 영향을 끼칠 가능성이 있는 경우\r\n   20. 기타 이 약관상의 제반 의무를 위반하는 경우\r\n   21. 바이러스의 유포 가능성이나 이에 대한 의심이 있는 경우\r\n\r\n③. 본 조 제 2 항에 해당하는 행위를 한 \"회원\"은 이로 인해 \"회사\" 또는 다른 회원에게 발생한 손해를 배상할 책임이 있습니다.\r\n\r\n④. 본 조 제 2 항의 \"회사\" 조치에 대하여 \"회원\"은 \"회사\"가 정한 절차에 따라 이의신청을 할 수 있고, 이의가 정당하다고 인정하는 경우 \"회사\"는 해당 서비스의 이용을 재개합니다.\r\n\r\n제 10 조 (전자메일에 대한 회원의 의무와 책임)\r\n\r\n①. \"회사\"는 \"회원\"의 전자메일 내용을 편집하거나 감시하지 않습니다.\r\n\r\n②. 회원은 \"회사\"의 전자메일을 통하여 음란물이나 명예훼손 내용, 정크 메일(junk mail), 스팸메일(spam mail), 행운의 편지(chain letters), 피라미드 조직에 가입할 것을 권유하는 메일, 외설 또는 폭력적인 메시지 / 화상 / 음성 등 타인에게 피해를 주거나 미풍양속을 해치는 메일을 보내서는 안 됩니다.\r\n\r\n③. 본 조 제 2 항을 위반하여 발생되는 모든 책임은 회원에게 있으며, 이 경우 ID와 PASSWORD 등 개인정보를 수사기관에 제공할 수 있습니다.\r\n\r\n④. SMS, 전화 등 기타 연락 수단도 제 10조 전 항들의 내용을 준용합니다.\r\n\r\n제 11 조 회원정보의 변경\r\n\r\n①. 회원은 이용신청 시 기재한 사항이 변경되었을 경우, 즉시 해당 사항의 수정을 \"회사\"에 요청하여야 합니다. \r\n\r\n②. 회원정보가 변경되었음에도 해당 사항의 수정을 요청하지 않음으로써 발생하는 각종 손해는 회원 본인이 부담하여야 합니다. 다만, 회사의 고의나 과실에 의하여 손해가 발생한 경우에는 회사가 손해를 부담합니다.\r\n\r\n제 12 조 (쿠키(Cookie)의 운용 및 활용)\r\n\r\n①. \"회사\"는 회원에게 적합하고 유용한 서비스를 제공하기 위해서 회원의 정보를 저장하고 수시로 불러오는 쿠키(cookie)를 이용합니다.\r\n\r\n②. 본 조 제 1 항과 관련하여 회원은 쿠키 이용에 대한 선택권을 가지며 쿠키의 수신을 거부하거나 쿠키의 수신에 대하여 경고하도록 이용하는 컴퓨터 브라우저의 설정을 변경할 수 있습니다. 단, 쿠키의 저장을 거부할 경우, 로그인이 필요한 모든 서비스를 이용할 수 없게 됨으로써 발생되는 문제에 대한 책임은 회원에게 있습니다.\r\n\r\n제 13 조 (링크 사이트에 대한 책임)\r\n\r\n①. \"회사\"는 회원에게 다른 웹사이트 또는 자료에 대한 링크를 제공할 수 있습니다. 이 경우 \"회사\"는 외부 사이트 및 자료에 대한 아무런 통제권이 없으므로 그로부터 제공받은 서비스나 자료의 정확성, 유용성 등에 대해 책임지지 아니하며 \"회사\"의 개인정보보호정책이 적용되지 않습니다.\r\n\r\n②. 회원은 링크된 외부 사이트의 서비스나 자료를 신뢰함으로써 또는 이와 관련하여 발생하거나 발생되었다고 주장하는 어떠한 손해나 손실에 대해서도 \"회사\"에 책임이 없음을 인정하고 이에 동의합니다.\r\n\r\n제 14 조 (면책)\r\n\r\n\"회사\"는 다음 각 호의 사유로 인하여 회원에게 발생한 손해에 대해서는 책임지지 아니합니다. 다만, \"회사\"가 책임이 있다고 판단되는 상당한 인과관계가 있는 경우에는 그러하지 아니합니다.\r\n\r\n①. 통신기기, 회선 및 컴퓨터의 장애나 거래의 폭주 등 부득이한 사정으로 \"서비스\"가 제공되지 못하거나 지연된 경우\r\n\r\n②. \"회원\"이 ID, PASSWORD 등을 본인의 관리소홀로 인해 제3자에게 누출한 경우\r\n\r\n③. \"회원\"의 전산 조작이나 업무처리의 오류 등으로 인한 경우\r\n\r\n④. 기타 천재지변 등의 불가피한 사유로 인한 경우\r\n\r\n⑤. \"회원\"이 \"회사\"의 서비스 제공으로부터 기대되는 이익을 얻지 못하였거나 서비스 자료에 대한 취사선택 또는 이용으로 손해가 발생한 경우\r\n\r\n⑥.  “회원”의 귀책사유로 인하여 서비스 이용의 장애가 발생한 경우\r\n\r\n⑦. 회원 상호간 또는 회원과 제3자 상호간에 서비스를 매개로 하여 물품거래, 직거래 등을 한 경우\r\n\r\n⑧. \"회사\"의 귀책사유 없이 회원 간 또는 회원과 제3자간에 발생한 일체의 분쟁\r\n\r\n⑨. 서버 등 설비의 관리, 점검, 보수, 교체 과정 또는 소프트웨어의 운용 과정에서 고의 또는 고의에 준하는 중대한 과실 없이 발생할 수 있는 시스템의 장애, 제3자의 공격으로 인한 시스템의 장애, 국내외의 저명한 연구기관이나 보안관련 업체에 의해 대응방법이 개발되지 아니한 컴퓨터 바이러스 등의 유포나 기타 \"회사\"가 통제할 수 없는 불가항력적 사유로 인해 \"회원\"에게 손해가 발생한 경우\r\n\r\n⑩. 제9항의 회원의 손해에는 (1) 회원이 본 서비스를 이용하여 작성하고 있거나 전송 중이거나 저장한 게시물, 그에 첨부된 파일, 기타 데이터의 손상이나 손실(이하 \"데이터의 손상이나 손실\"이라 합니다)로 인한 경제적, 정신적 손해 (2) 이와 같은 데이터의 손상이나 손실로 인하여 회원이 본 서비스를 이용하여 수행하고자 한 사실행위 또는 제3자와의 계약 기타의 법률행위 등이 불가능해지거나 지연됨으로 인하여 회원에게 발생할 수 있는 경제적, 정신적 손해 등이 포함되며 이에 한정되지 않습니다.\r\n\r\n⑪. 제9항 및 제10항에도 불구하고 \"회사\"가 제공하는 무료서비스의 이용과 관련하여 \"회사\"의 고의 또는 중대한 과실 없이 \"회원\"의 손해가 발생한 경우\r\n\r\n제 15 조 (저작권의 귀속 및 이용제한)\r\n\r\n①. \"회사\"가 작성한 웹 화면(문구 및 디자인)에 관한 저작권, 기타 지적재산권은 \"회사\"에 귀속됩니다.\r\n\r\n②. \"회원\"은 \"회사\"가 제공하는 서비스를 이용함으로써 얻은 정보를 \"회사\"의 사전승낙 없이 출판, 복제, 방송 및 기타 방법에 의하여 유포하거나 제3자에게 이용하게 해서는 아니 됩니다.\r\n\r\n제 16 조 (이용약관의 효력 및 변경)\r\n\r\n①. 이용자가 사이트의 서비스를 이용한 경우에는 이 약관에 동의한 것으로 간주합니다.\r\n\r\n②. \"회사\"는 합리적인 사유가 발생될 경우에는 이 약관을 변경할 수 있으며, 이 경우 적용일자 및 개정 내용을 초기화면 또는 연결 화면을 통해 그 적용일자 7일 이전부터 적용일자 전일까지 공지합니다.\r\n\r\n③. 이용약관의 변경 시 \"회사\"가 미리 회원에게 약관 변경 내용을 개별적으로 통지하여 회원이 동의한 경우나 홈페이지 공지사항을 통하여 약관 변경 내용을 7일전부터 공지하여 동의여부를 확인한 후 회원이 의사표시를 하지 않은 경우에는 변경된 약관에 동의한 것으로 간주합니다.\r\n\r\n④. 변경된 약관에 이의가 있는 회원은 본 약관에서 정한 바에 따라 탈퇴(해지)할 수 있으며, 약관의 효력발생일 이후의 서비스 이용은 변경된 약관의 적용을 받습니다.\r\n\r\n제 17 조 (약관 위반 시 책임)\r\n\r\n\"회사\"와 회원은 이 약관 및 동의서를 위반함으로써 발생하는 모든 책임을 각자 부담하며, 이로 인하여 상대방에게 손해를 입힌 경우에는 지체 없이 배상하여야 합니다.\r\n\r\n제 18 조 (서비스 중지)\r\n\r\n①. \"회사\"는 긴급한 시스템 점검, 증설 및 교체 등 부득이한 사유로 인하여 사전공지 없이 일시적으로 서비스를 중단할 수 있으며, 새로운 서비스로의 교체 등 \"회사\"가 적절하다고 판단하는 사유에 의하여 현재 제공되는 서비스를 완전히 중단할 수 있습니다.\r\n\r\n②. \"회사\"는 서비스 설비의 장애 또는 서비스 이용의 폭주 등 \"회사\"의 귀책사유 없이 정상적인 서비스 제공이 불가능할 경우에는 서비스의 전부 또는 일부를 제한하거나 중지할 수 있습니다. 다만, 이 경우 그 사유 및 기간 등을 회원에게 사전 또는 사후에 공지합니다.\r\n\r\n③. \"회사\"는 천재지변, 전쟁 등 불가항력적 사유가 발생한 경우 또는 기간통신사업자 등 전기통신사업자가 전기통신 서비스를 중지하거나 정상적으로 제공하지 않는 경우에는 서비스를 중지할 수 있습니다.\r\n\r\n제 19 조 (제휴사 또는 광고주와의 거래)\r\n\r\n①. \"회원\"은 서비스 이용 시 노출되는 제휴사를 포함한 \"회사\"의 광고 게재에 대해 동의한 것으로 봅니다.\r\n\r\n②. \"회사\"는 서비스 상에 게재되어 있거나 본 서비스를 통한 제휴사 등의 판촉활동에 회원이 참여하거나 교신 또는 거래를 함으로써 발생하는 손실과 손해에 대해 일체의 책임을 지지 않습니다.\r\n\r\n③. 회원은 서비스 내에 포함되어 있는 링크를 통하여 다른 웹사이트로 옮겨갈 경우, \"회사\"는 해당 사이트에서 제공하는 정보내용 및 이로 인한 손해 등에 대한 책임을 지지 않습니다.\r\n\r\n제 20 조 (정보의 제공)\r\n\r\n①. \"회사\"는 회원에게 서비스 이용에 필요가 있다고 인정되는 각종 정보 및 광고를 전자우편 또는 서신우편 등의 방법으로 회원에게 전송(또는 제공)할 수 있으며, 회원은 이를 원하지 않을 경우 \"회원정보관리\"에서 수신을 거부할 수 있습니다.\r\n\r\n②. 전자우편의 수신을 거부한 경우에도 본 약관(개별 서비스 이용약관 포함), 개인정보보호정책, 기타 중대한 영업정책의 변경 등 회원이 반드시 알고 있어야 하는 공지사항이 있는 경우, 수신거부와 관계없이 공지메일을 발송할 수 있습니다.\r\n\r\n③. \"회사\"는 서비스 개선 및 회원 대상의 서비스 소개 등의 목적으로 회원의 동의하에 추가적인 개인정보를 요구할 수 있습니다.\r\n\r\n제 21 조 (이용료 등)\r\n\r\n①. \"회사\"는 서비스 이용과 관련하여 각종 이용료를 \"회원\"에게 징수할 수 있습니다.\r\n\r\n②. 이용료는 회원의 관리와  \"회사\"가 제공하는 시스템 이용의 대가로서 \"회사\"가 부과하는 \"사이트 이용료\", \"투자 성사 수수료\", \"사이트 부가서비스 이용료\"와 \"여신회사\"가 부과하는 \"취급수수료\", 로 구분됩니다.\r\n\r\n③. \"회사\"는 회원의 입금 또는 출금과 관련하여 계좌이체에 의해 발생하는 출입금 이체수수료를 회원에게 부과할 수 있습니다.\r\n단, 회사의 정책에 따라 이체수수료 금액을 할인, 또는 면제할 수 있습니다.\r\n\r\n④. \"회사\"는 재량 내에서 이용료의 추가 및 폐지, 이용료율의 변경을 할 수 있으며, 개정할 경우에는 적용일자 및 개정사유를 명시하여 그 적용일자 7일 이전부터 적용일자 전일까지 사이트 화면에 게시하거나 기타의 방법으로 회원에게 공지합니다.\r\n\r\n제 22 조 (채권의 매각)\r\n\r\n①. 투자자가 원리금수취권을 보유하고 있는 해당 대출건의 채무자가 기한의 이익을 상실한 경우, \"여신업체\"는 해당 채권을 매입보증인에 \"투자자의 동의 없이\" 매각할 수 있습니다.\r\n\r\n②. 이 경우 \"여신업체\"는 해당 채권의 매각금액에서 매각에 소요되는 비용을 제한 잔여금을 \"회사\"에 지급할 수 있으며, 회사는 지급받은 금액을 자금출연 비율에 맞게 투자자에게 분배합니다.\r\n③. 투자자는 채권의 매각으로 인해 발생한 투자금의 손실에 대해 이의를 제기하지 않습니다.\r\n\r\n제 23 조 (분쟁조정 및 관할법원)\r\n\r\n①. \"회사\" 및 \"회원\"은 회원정보에 관한 분쟁이 발생한 경우 신속하고 효과적인 분쟁해결을 위하여 정보통신망이용촉진 및 정보보호 등에 관한 법률의 규정에 의해 설치된 개인정보분쟁조정위원회에 분쟁의 조정을 신청할 수 있습니다.\r\n\r\n②. 서비스 이용과 관련하여 발생한 분쟁에 대해 소송이 제기될 경우, “회사”의 본점 소재지 관할법원을 전속적 관할법원으로 합니다.\r\n\r\n제 24 조 (투자자 보호)\r\n\r\n“회사”는 투자자를 보호하기 위하여 투자대상을 “담보부 대출채권”의 원리금 수취 권으로 한정하고 있으며, 객관적 데이터에 의한 담보의 “세이프티존 시스템”제도와 동 대출채권의 “매입보증인제도”를 운영하고 있습니다.\r\n\r\n제 25 조 (관련법령과의 관계)\r\n\r\n①. 이 약관 또는 개별약관에서 정하지 않은 사항은 전기통신사업법, 전자거래기본법, 정보통신망이용촉진 및 정보보호 등에 관한 법률, 전자상거래 등에서의 소비자보호에 관한 법률 등 관련법령의 규정과 일반적인 상관례에 의합니다.\r\n\r\n②. 회원은 회사가 제공하는 서비스를 이용함에 있어서 전자상거래 등에서의 소비자보호에 관한 법률, 전자거래기본법, 소비자보호법, 표시광고의 공정화에 관한 법률, 정보통신망이용촉진 및 정보보호 등에 관한 법률 등 관련법령을 준수하여야 하며, 이 약관의 규정을 들어 관련법령 위반에 대한 면책을 주장할 수 없습니다.\r\n\r\n<부칙>\r\n본 약관은 2015년  11월 13일부터 적용됩니다.\r\n본 약관은 2016년  6월 7일부터 적용됩니다.\r\n\r\n상호 : ‘골든벨(www.골든벨.net)’ . \r\n사업자등록번호 : 283-88-00146 \r\n주소 : 서울시 강남구 테헤란로25길 34, 4층(역삼동,삼보빌딩)\r\n대표자 : 박준호\r\n업종 : 소셜금융 플랫폼','‘골든벨(www.골든벨.net)’ 는 개인정보보호법에 따라 이용자의 개인정보 보호 및 권익을 보호하고 개인정보와 관련한 이용자의 고충을 원활하게 처리할 수 있도록 다음과 같은 처리방침을 두고 있습니다.\r\n\r\n‘골든벨(www.골든벨.net)’ 는 회사는 개인정보처리방침을 개정하는 경우 웹사이트 공지사항(또는 개별공지)을 통하여 공지할 것입니다.\r\n\r\n○ 본 방침은 2015년 9월 15일부터 시행됩니다.\r\n\r\n‘골든벨(www.골든벨.net)’ 는 개인정보를 다음의 목적을 위해 처리합니다. 처리한 개인정보는 다음의 목적이외의 용도로는 사용되지 않으며 이용 목적이 변경될 시에는 사전 동의를 구할 예정입니다.\r\n\r\n회원 가입의사 확인, 회원제 \r\n가. 홈페이지 회원가입 및 관리\r\n서비스 제공에 따른 본인 식별·인증, 회원자격 유지·관리, 제한적 본인확인제 시행에 따른 본인확인, 서비스 부정이용 방지, 만14세 미만 아동 개인정보 수집 시 법정대리인 동의 여부 확인, 각종 고지·통지, 고충처리, 분쟁 조정을 위한 기록 보존 등을 목적으로 개인정보를 처리합니다.\r\n\r\n나. 민원사무 처리\r\n민원인의 신원 확인, 민원사항 확인, 사실조사를 위한 연락·통지, 처리결과 통보 등을 목적으로 개인정보를 처리합니다.\r\n\r\n다. 재화 또는 서비스 제공\r\n서비스 제공, 청구서 발송, 콘텐츠 제공, 맞춤 서비스 제공, 본인인증, 연령인증, 요금결제·정산, 채권추심 등을 목적으로 개인정보를 처리합니다.\r\n\r\n라. 마케팅 및 광고에의 활용\r\n신규 서비스(제품) 개발 및 맞춤 서비스 제공, 이벤트 및 광고성 정보 제공 및 참여기회 제공 , 인구통계학적 특성에 따른 서비스 제공 및 광고 게재 , 서비스의 유효성 확인, 접속빈도 파악 또는 회원의 서비스 이용에 대한 통계 등을 목적으로 개인정보를 처리합니다.\r\n\r\n \r\n\r\n \r\n\r\n개인정보의 수집방법 및 수집 항목\r\n\r\n \r\n\r\n가. ‘골든벨(www.골든벨.net)’ 는 법령에 따른 개인정보 보유·이용기간 또는 정보주체로부터 개인정보를 수집 시에 동의 받은 개인정보 보유, 이용기간 내에서 개인정보를 처리, 보유합니다.\r\n\r\n나. 개인정보의 수집 방법\r\n홈페이지 회원가입, 서비스 이용, 이벤트 응모, 생성정보 수집 툴을 통한 수집, 회원정보수정, 팩스, 전화를 통한 회원가입, 고객센터 문의하기\r\n\r\n다. 개인정보 수집 항목 \r\n- 필수항목 : 자택주소, 생년월일, 자택전화번호, 휴대전화번호, 이름, 이메일, 회사명, 직장, 직장 주소, 회사전화번호, 직업, 학력, 은행계좌정보, 신용정보(신용등급, 신용평점, 조회 이력, 연체 이력, 기존 대출 내역 등) 접속 IP 정보, 쿠키, 서비스 이용 기록, 접속 로그\r\n- 선택항목 : 자택주소, 생년월일, 자택전화번호, 성별, 휴대전화번호, 이름, 이메일, 회사명, 직장, 직장 주소, 회사전화번호, 직업, 학력, 은행계좌정보, 신용정보(신용등급, 신용평점, 조회 이력, 연체 이력, 기존 대출 내역 등) 접속 IP 정보, 쿠키, 서비스 이용 기록, 접속 로그\r\n\r\n-이용기간 : 5년\r\n\r\n\r\n\r\n개인정보의 제3자 제공에 관한 사항\r\n\r\n\r\n가. ‘골든벨(www.골든벨.net)’ 는 정보주체의 동의, 법률의 특별한 규정 등 개인정보 보호법 제17조 및 제18조에 해당하는 경우에만 개인정보를 제3자에게 제공합니다. \r\n\r\n\r\n정보주체의 권리, 의무 및 그 행사방법 이용자는 개인정보주체로서 다음과 같은 권리를 행사할 수 있습니다.\r\n\r\n\r\n가. 정보주체는 ‘골든벨(www.골든벨.net)’에 대해 언제든지 다음 각 호의 개인정보 보호 관련 권리를 행사할 수 있습니다.\r\n1. 개인정보 열람요구\r\n2. 오류 등이 있을 경우 정정 요구\r\n3. 삭제요구\r\n4. 처리정지 요구\r\n\r\n \r\n\r\n나. 제1항에 따른 권리 행사는 ‘골든벨(www.골든벨.net)’ 에 대해 개인정보 보호법 시행규칙 별지 제8호 서식에 따라 서면, 전자우편, 모사전송(FAX) 등을 통하여 하실 수 있으며 골든벨 은 이에 대해 지체 없이 조치하겠습니다.\r\n\r\n다. 정보주체가 개인정보의 오류 등에 대한 정정 또는 삭제를 요구한 경우에는 골든벨은 정정 또는 삭제를 완료할 때까지 당해 개인정보를 이용하거나 제공하지 않습니다.\r\n\r\n라. 제1항에 따른 권리 행사는 정보주체의 법정대리인이나 위임을 받은 자 등 대리인을 통하여 하실 수 있습니다. 이 경우 개인정보 보호법 시행규칙 별지 제11호 서식에 따른 위임장을 제출하셔야 합니다.\r\n\r\n\r\n개인정보의 파기\r\n\r\n‘골든벨(www.골든벨.net)’ 는 원칙적으로 개인정보 처리목적이 달성된 경우에는 지체 없이 해당 개인정보를 파기합니다. 파기의 절차, 기한 및 방법은 다음과 같습니다.\r\n\r\n\r\n-파기절차\r\n이용자가 입력한 정보는 목적 달성 후 별도의 DB에 옮겨져(종이의 경우 별도의 서류) 내부 방침 및 기타 관련 법령에 따라 일정기간 저장된 후 혹은 즉시 파기됩니다. 이때, DB로 옮겨진 개인정보는 법률에 의한 경우가 아니고서는 다른 목적으로 이용되지 않습니다.\r\n\r\n-파기기한\r\n이용자의 개인정보는 개인정보의 보유기간이 경과된 경우에는 보유기간의 종료일로부터 5일 이내에, 개인정보의 처리 목적 달성, 해당 서비스의 폐지, 사업의 종료 등 그 개인정보가 불필요하게 되었을 때에는 개인정보의 처리가 불필요한 것으로 인정되는 날로부터 5일 이내에 그 개인정보를 파기합니다.\r\n\r\n-파기방법\r\n전자적 파일 형태의 정보는 기록을 재생할 수 없는 기술적 방법을 사용합니다.\r\n종이에 출력된 개인정보는 분쇄기로 분쇄하거나 소각을 통하여 파기합니다.\r\n\r\n개인정보 자동 수집 장치의 설치, 운영 및 그 거부에 관한 사항\r\n\r\n\r\n“회사”는 귀하의 정보를 수시로 저장하고 찾아내는 ‘쿠키(cookie)’ 등을 운용합니다. 쿠키란 ‘골든벨’ 의 웹사이트를 운영하는데 이용되는 서버가 귀하의 브라우저에 보내는 아주 작은 텍스트 파일로서 귀하의 컴퓨터 하드디스크에 저장됩니다. “회사”는 다음과 같은 목적을 위해 쿠키를 사용합니다.\r\n\r\n가. 쿠키 등 사용 목적\r\n- 회원과 비회원의 접속 빈도나 방문 시간 등을 분석, 이용자의 취향과 관심분야를 파악 및 자취 추적, 각종 이벤트 참여 정도 및 방문 회수 파악 등을 통한 타겟 마케팅 및 개인 맞춤 서비스 제공\r\n귀하는 쿠키 설치에 대한 선택권을 가지고 있습니다. 따라서, 귀하는 웹브라우저에서 옵션을 설정함으로써 모든 쿠키를 허용하거나, 쿠키가 저장될 때마다 확인을 거치거나, 아니면 모든 쿠키의 저장을 거부할 수도 있습니다.\r\n\r\n나. 쿠키 설정 거부 방법\r\n쿠키 설정을 거부하는 방법으로는 회원님이 사용하시는 웹 브라우저의 옵션을 선택함으로써 모든 쿠키를 허용하거나 쿠키를 저장할 때마다 확인을 거치거나, 모든 쿠키의 저장을 거부할 수 있습니다.\r\n\r\n설정방법 예(인터넷 익스플로러의 경우) : 웹 브라우저 상단의 도구 > 인터넷 옵션 > 개인정보\r\n\r\n단, 귀하께서 쿠키 설치를 거부하였을 경우 서비스 제공에 어려움이 있을 수 있습니다.\r\n\r\n\r\n개인정보 보호책임자 작성\r\n\r\n \r\n① ‘골든벨(www.골든벨.net)’ 은(는) 개인정보 처리에 관한 업무를 총괄해서 책임지고, 개인정보 처리와 관련한 정보주체의 불만처리 및 피해구제에 대해 책임을 질것입니다.\r\n\r\n\r\n▶ 개인정보 보호책임\r\n상호 : 골든벨\r\n사이트 : www.www.골든벨.net\r\n대표 : 김수봉\r\n연락 : 031)943-2642\r\n \r\n\r\n② 정보주체께서는 골든벨의 서비스(또는 사업)를 이용하시면서 발생한 모든 개인정보 보호 관련 문의, 불만처리, 피해구제 등에 관한 사항을 개인정보 보호책임자 및 담당부서로 문의하실 수 있습니다. 골든벨은 정보주체의 문의에 대해 지체 없이 답변 및 처리해드릴 것입니다.\r\n\r\n\r\n기타 개인정보침해에 대한 신고나 상담이 필요하신 경우에는 아래 기관에 문의하시기 바랍니다.\r\n\r\n1.개인분쟁조정위원회 (www.1336.or.kr/1336)\r\n2.정보보호마크인증위원회 (www.eprivacy.or.kr/02-580-0533~4)\r\n3.대검찰청 인터넷범죄수사센터 (http://icic.sppo.go.kr/02-3480-3600)\r\n4.경찰청 사이버테러대응센터 (www.ctrc.go.kr/02-392-0330)\r\n\r\n\r\n개인정보의 안전성 확보 조치\r\n\r\n\r\n‘골든벨(www.골든벨.net)’  개인정보보호법 제29조에 따라 다음과 같이 안전성 확보에 필요한 기술적/관리적 및 물리적 조치를 하고 있습니다.\r\n\r\n\r\n1. 개인정보 취급 직원의 최소화 및 교육\r\n개인정보를 취급하는 직원을 지정하고 담당자에 한정시켜 최소화 하여 개인정보를 관리하는 대책을 시행하고 있습니다.\r\n\r\n \r\n\r\n2. 정기적인 자체 감사 실시\r\n개인정보 취급 관련 안정성 확보를 위해 정기적(분기 1회)으로 자체 감사를 실시하고 있습니다.\r\n\r\n \r\n\r\n3. 내부관리계획의 수립 및 시행\r\n개인정보의 안전한 처리를 위하여 내부관리계획을 수립하고 시행하고 있습니다.\r\n\r\n \r\n\r\n4. 개인정보의 암호화\r\n이용자의 개인정보는 비밀번호는 암호화 되어 저장 및 관리되고 있어, 본인만이 알 수 있으며 중요한 데이터는 파일 및 전송 데이터를 암호화 하거나 파일 잠금 기능을 사용하는 등의 별도 보안기능을 사용하고 있습니다.\r\n\r\n \r\n\r\n5. 해킹 등에 대비한 기술적 대책\r\n‘골든벨(www.골든벨.net)’ 은 해킹이나 컴퓨터 바이러스 등에 의한 개인정보 유출 및 훼손을 막기 위하여 보안프로그램을 설치하고 주기적인 갱신·점검을 하며 외부로부터 접근이 통제된 구역에 시스템을 설치하고 기술적/물리적으로 감시 및 차단하고 있습니다.\r\n\r\n \r\n\r\n6. 개인정보에 대한 접근 제한\r\n개인정보를 처리하는 데이터베이스시스템에 대한 접근권한의 부여, 변경, 말소를 통하여 개인정보에 대한 접근통제를 위하여 필요한 조치를 하고 있으며 침입차단시스템을 이용하여 외부로부터의 무단 접근을 통제하고 있습니다.\r\n\r\n \r\n\r\n7. 접속기록의 보관 및 위변조 방지\r\n개인정보처리시스템에 접속한 기록을 최소 6개월 이상 보관, 관리하고 있으며, 접속 기록이 위변조 및 도난, 분실되지 않도록 보안기능 사용하고 있습니다.\r\n\r\n \r\n\r\n8. 문서보안을 위한 잠금장치 사용\r\n개인정보가 포함된 서류, 보조저장매체 등을 잠금장치가 있는 안전한 장소에 보관하고 있습니다.\r\n\r\n \r\n\r\n9. 비인가자에 대한 출입 통제\r\n개인정보를 보관하고 있는 물리적 보관 장소를 별도로 두고 이에 대해 출입통제 절차를 수립, 운영하고 있습니다.\r\n\r\n \r\n\r\n\r\n개인정보 처리방침 변경\r\n\r\n \r\n\r\n이 개인정보처리방침은 시행일로부터 적용되며, 법령 및 방침에 따른 변경내용의 추가, 삭제 및 정정이 있는 경우에는 변경사항의 시행 7일 전부터 공지사항을 통하여 고지할 것입니다.','','','','','');

/*Table structure for table `history` */

CREATE TABLE `history` (
  `code` int(3) NOT NULL AUTO_INCREMENT,
  `year` int(4) NOT NULL COMMENT '연도',
  `month` int(2) DEFAULT NULL COMMENT '월',
  `title` tinytext NOT NULL COMMENT '내용',
  PRIMARY KEY (`code`),
  KEY `history_index1` (`year`,`month`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Data for the table `history` */

insert  into `history`(`code`,`year`,`month`,`title`) values (1,2015,5,'ㅅㄷㄴㅅ2');
insert  into `history`(`code`,`year`,`month`,`title`) values (2,2016,4,'- 33333 ㅁㅇ ㄻㄴㅇㄹ ㅁㄴㅇㄻㄴ ㅇㄹ\r\n- ㅁㄴㅇㄻㄴㅇㄻㄴㅇㄹ\r\n- 푸하하');

/*Table structure for table `info` */

CREATE TABLE `info` (
  `code` int(1) DEFAULT NULL,
  `bankruptcy_rate` varchar(30) DEFAULT NULL COMMENT '부도율',
  `total_repayment_amount` varchar(30) DEFAULT NULL COMMENT '누적 상환액'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `info` */

insert  into `info`(`code`,`bankruptcy_rate`,`total_repayment_amount`) values (1,'0.5','25,000,000');

/*Table structure for table `member` */

CREATE TABLE `member` (
  `code` int(8) NOT NULL AUTO_INCREMENT,
  `id` varchar(25) NOT NULL COMMENT 'id',
  `password` varchar(50) NOT NULL COMMENT 'password',
  `name` varchar(50) NOT NULL COMMENT '이름',
  `birthday` date DEFAULT NULL COMMENT '생년월일',
  `sex` enum('m','f') DEFAULT NULL COMMENT '성별 (m:남자, f:여자)',
  `email` varchar(70) DEFAULT NULL COMMENT 'email',
  `mobile` varchar(14) DEFAULT NULL COMMENT '휴대폰',
  `tel` varchar(14) DEFAULT NULL COMMENT '전화번호',
  `zipcode` varchar(7) DEFAULT NULL COMMENT '우편번호',
  `addr` varchar(100) DEFAULT NULL COMMENT '주소',
  `addr_etc` varchar(100) DEFAULT NULL COMMENT '상세주소',
  `memo` varchar(200) DEFAULT NULL COMMENT '메모',
  `is_receive_email` enum('n','y') DEFAULT 'n' COMMENT '이메일 수신 여부',
  `is_receive_sms` enum('n','y') DEFAULT 'n' COMMENT 'SMS 수신 여부',
  `level` int(2) NOT NULL DEFAULT '2' COMMENT '회원레벨 1:관리자 2:정회원',
  `status` enum('y','n') NOT NULL DEFAULT 'y' COMMENT '상태',
  `last_login_date` datetime DEFAULT NULL COMMENT '최근 접속일',
  `update_date` datetime DEFAULT NULL COMMENT '수정일자',
  `reg_date` datetime NOT NULL COMMENT '등록일자',
  PRIMARY KEY (`code`),
  KEY `member_index1` (`id`,`password`,`status`,`addr`),
  KEY `member_index2` (`email`,`status`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

/*Data for the table `member` */

insert  into `member`(`code`,`id`,`password`,`name`,`birthday`,`sex`,`email`,`mobile`,`tel`,`zipcode`,`addr`,`addr_etc`,`memo`,`is_receive_email`,`is_receive_sms`,`level`,`status`,`last_login_date`,`update_date`,`reg_date`) values (1,'admin','28291cb4d96c63c7d8a2bec32813d956b260f6d1','관리자',NULL,NULL,'limsj@whois.co.kr','010-7553-6162',NULL,'152-869','서울 구로구 가마산로 77','33','최고관리자','n','n',1,'y','2017-03-23 14:42:56','2017-01-24 13:55:48','2016-06-15 11:38:50');
insert  into `member`(`code`,`id`,`password`,`name`,`birthday`,`sex`,`email`,`mobile`,`tel`,`zipcode`,`addr`,`addr_etc`,`memo`,`is_receive_email`,`is_receive_sms`,`level`,`status`,`last_login_date`,`update_date`,`reg_date`) values (3,'whois','28291cb4d96c63c7d8a2bec32813d956b260f6d1','홍길동','2017-01-01','','admin@whois.co.kr','010-1111-2222','02--','08525','서울특별시 금천구 두산로 37 (독산동, 독산동 공장)','222','','n','n',3,'y','2017-03-23 14:24:26','2017-03-23 14:23:17','2017-03-23 14:19:42');

/*Table structure for table `member_level` */

CREATE TABLE `member_level` (
  `level` int(2) NOT NULL AUTO_INCREMENT COMMENT '등급 (1,2,3,4,5)',
  `title` varchar(30) DEFAULT NULL COMMENT '등급명',
  PRIMARY KEY (`level`)
) ENGINE=InnoDB AUTO_INCREMENT=18446744073709551615 DEFAULT CHARSET=utf8;

/*Data for the table `member_level` */

insert  into `member_level`(`level`,`title`) values (1,'최고관리자');
insert  into `member_level`(`level`,`title`) values (2,'지부관리자');
insert  into `member_level`(`level`,`title`) values (3,'회원');
insert  into `member_level`(`level`,`title`) values (4,'');
insert  into `member_level`(`level`,`title`) values (5,'');

/*Table structure for table `member_tmp_password` */

CREATE TABLE `member_tmp_password` (
  `code` int(8) NOT NULL AUTO_INCREMENT,
  `id` varchar(100) NOT NULL COMMENT 'id',
  `password` varchar(20) NOT NULL COMMENT '임시 비밀번호',
  `status` enum('ready','done') NOT NULL DEFAULT 'ready' COMMENT '상태 (ready:사용전, done:사용후)',
  `reg_date` datetime NOT NULL COMMENT '등록일자',
  PRIMARY KEY (`code`),
  KEY `member_tmp_password_index1` (`id`,`password`,`status`,`reg_date`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Data for the table `member_tmp_password` */

insert  into `member_tmp_password`(`code`,`id`,`password`,`status`,`reg_date`) values (2,'admin','224777','done','2016-12-07 14:30:12');
insert  into `member_tmp_password`(`code`,`id`,`password`,`status`,`reg_date`) values (1,'admin','343539','ready','2016-12-07 14:22:44');

/*Table structure for table `popup` */

CREATE TABLE `popup` (
  `code` int(8) NOT NULL AUTO_INCREMENT COMMENT '기본키',
  `title` varchar(200) NOT NULL COMMENT '제목',
  `content` text NOT NULL COMMENT '내용',
  `start_date` date NOT NULL COMMENT '팝업 시작일',
  `end_date` date NOT NULL COMMENT '팝업 종료일',
  `top_position` varchar(10) NOT NULL COMMENT '팝업 top 위치',
  `left_position` varchar(10) NOT NULL COMMENT '팝업 left 위치',
  `width` varchar(10) NOT NULL COMMENT '팝업 가로 크기',
  `height` varchar(10) NOT NULL COMMENT '팝업 세로 크기',
  `popup_cookie` enum('y','n') NOT NULL DEFAULT 'y' COMMENT '팝업 하루동안 열지않기 노출',
  `display` enum('y','n') NOT NULL DEFAULT 'y' COMMENT '팝업 노출',
  `reg_date` datetime NOT NULL COMMENT '등록일',
  PRIMARY KEY (`code`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

/*Data for the table `popup` */

insert  into `popup`(`code`,`title`,`content`,`start_date`,`end_date`,`top_position`,`left_position`,`width`,`height`,`popup_cookie`,`display`,`reg_date`) values (1,'사이트 오픈 안내입니다.','<p style=\"text-align:center\"><span style=\"font-size:14px\"><strong><span style=\"color:#c0392b\">곧 사이트 오픈을 하도록 하겠습니다.</span></strong></span></p>\r\n\r\n<p>많은 성원 바랍니다.</p>\r\n\r\n<p><img alt=\"\" src=\"http://api.whoisict.com/user/attachment/201703/1489387250014115.png\" style=\"height:70px; width:125px\" /></p>\r\n','2017-03-13','2017-11-03','1','50','300','250','y','y','2017-03-13 15:36:49');
insert  into `popup`(`code`,`title`,`content`,`start_date`,`end_date`,`top_position`,`left_position`,`width`,`height`,`popup_cookie`,`display`,`reg_date`) values (2,'지난 팝업입니다','<p>ㅋㅋ</p>\r\n','2017-03-01','2017-03-05','','','200','300','y','y','2017-03-13 15:39:16');

/*Table structure for table `product` */

CREATE TABLE `product` (
  `code` int(8) NOT NULL AUTO_INCREMENT,
  `category_code` int(8) NOT NULL COMMENT '카테고리 코드',
  `order_code` int(8) NOT NULL COMMENT '순서코드',
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '상품명',
  `type` varchar(100) DEFAULT NULL COMMENT 'type',
  `title_sub` varchar(100) DEFAULT NULL COMMENT '소제목',
  `mark` varchar(50) DEFAULT NULL COMMENT '마크 (1,2,3..)',
  `certificate_number` varchar(50) DEFAULT NULL COMMENT '인증서번호',
  `content` mediumtext COMMENT '스펙',
  `file_name` varchar(50) DEFAULT NULL COMMENT '제품사양서',
  `status` enum('y','n') NOT NULL DEFAULT 'y' COMMENT '표출상태(y:표출, n:미표출)',
  `reg_date` datetime NOT NULL COMMENT '등록일자',
  PRIMARY KEY (`code`),
  KEY `product_index1` (`category_code`,`status`,`order_code`),
  KEY `product_index2` (`category_code`,`title`,`status`,`order_code`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `product` */

insert  into `product`(`code`,`category_code`,`order_code`,`title`,`type`,`title_sub`,`mark`,`certificate_number`,`content`,`file_name`,`status`,`reg_date`) values (1,10,1,'ㅅㅅ','','','','','',NULL,'y','2017-03-06 20:25:26');

/*Table structure for table `schedule` */

CREATE TABLE `schedule` (
  `code` int(6) NOT NULL AUTO_INCREMENT,
  `type` enum('nomal','wedding') DEFAULT 'nomal' COMMENT 'type (nomal:일반, wedding:웨딩)',
  `title` varchar(200) NOT NULL COMMENT '제목',
  `content` tinytext COMMENT '내용',
  `background_color` char(7) NOT NULL DEFAULT '#0073b7' COMMENT '배경색',
  `start_date` datetime NOT NULL COMMENT '시작일자',
  `end_date` datetime NOT NULL COMMENT '종료일자',
  `reg_date` datetime NOT NULL COMMENT '등록일자',
  PRIMARY KEY (`code`),
  KEY `schedule_index1` (`type`,`start_date`,`end_date`)
) ENGINE=InnoDB AUTO_INCREMENT=90 DEFAULT CHARSET=utf8;

/*Data for the table `schedule` */

insert  into `schedule`(`code`,`type`,`title`,`content`,`background_color`,`start_date`,`end_date`,`reg_date`) values (89,'nomal','주간업무 회의 있습니다.','참석 바랍니다.','#DC143C','2017-03-22 01:30:00','2017-03-24 03:30:00','2017-03-14 16:39:35');

/*Table structure for table `sms_log` */

CREATE TABLE `sms_log` (
  `code` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `mobile` varchar(25) NOT NULL COMMENT '휴대폰 번호',
  `auth_number` int(4) NOT NULL COMMENT '인증번호 4자리',
  `is_use` enum('n','y') NOT NULL DEFAULT 'n' COMMENT '사용여부 (n:사용전, y:사용)',
  `reg_date` datetime NOT NULL COMMENT '등록일자',
  PRIMARY KEY (`code`),
  KEY `sms_log_index1` (`mobile`,`is_use`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `sms_log` */

/*Table structure for table `staff` */

CREATE TABLE `staff` (
  `code` int(6) NOT NULL AUTO_INCREMENT COMMENT '기본값,이미지 관리사용',
  `order_code` int(6) DEFAULT NULL COMMENT '순서변경사용',
  `position` varchar(100) DEFAULT NULL COMMENT '직책',
  `name` varchar(50) DEFAULT NULL COMMENT '이름',
  `career` text COMMENT '경력',
  `profile` text COMMENT '프로필',
  `reg_date` varchar(20) DEFAULT NULL COMMENT '날짜',
  PRIMARY KEY (`code`),
  KEY `staff_index1` (`order_code`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Data for the table `staff` */

insert  into `staff`(`code`,`order_code`,`position`,`name`,`career`,`profile`,`reg_date`) values (1,-2,'asdf2','홍길동','asdf2','asdf2','2017-03-13 18:13:12');
insert  into `staff`(`code`,`order_code`,`position`,`name`,`career`,`profile`,`reg_date`) values (2,-1,'3','ㅋㅋ','23','23','2017-03-13 18:22:03');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;