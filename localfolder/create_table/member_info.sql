CREATE TABLE `member_info` (
  `idx` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '일련번호',
  `name` varchar(20) NOT NULL DEFAULT '' COMMENT '이름',
  `nickname` varchar(20) NOT NULL DEFAULT '' COMMENT '별명',
  `password` varchar(60) NOT NULL DEFAULT '' COMMENT '비밀번호',
  `hp_no` varchar(20) NOT NULL DEFAULT '' COMMENT '전화번호',
  `email` varchar(100) NOT NULL DEFAULT '' COMMENT '이메일',
  `gender` int(1) unsigned DEFAULT 0 COMMENT '성별 1:여성, 2:남성',
  `create_datetime` datetime DEFAULT NULL COMMENT '생성시간',
  `ip_address` varchar(15) DEFAULT NULL COMMENT '생성 IP',
  PRIMARY KEY (`idx`),
  UNIQUE KEY `name` (`name`),
  KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8 COMMENT='회원정보'