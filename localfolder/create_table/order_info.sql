CREATE TABLE `order_info` (
  `idx` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '일련번호',
  `order_no` varchar(12) NOT NULL DEFAULT '' COMMENT '주문번호',
  `member_idx` int(10) unsigned NOT NULL DEFAULT 0 COMMENT 'member_info.idx',
  `product_idx` varchar(10) NOT NULL DEFAULT '0' COMMENT 'product_info.idx',
  `product_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '' COMMENT '상품명',
  `order_status` int(1) unsigned NOT NULL DEFAULT 0 COMMENT '상태 0:주문완료 1:발송 2:배송완료',
  `order_datetime` datetime DEFAULT NULL COMMENT '주문시간',
  `ip_address` varchar(15) DEFAULT NULL COMMENT '주문 IP',
  PRIMARY KEY (`idx`),
  UNIQUE KEY `order_no` (`order_no`),
  KEY `member_idx` (`member_idx`),
  KEY `product_idx` (`product_idx`),
  KEY `order_status` (`order_status`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COMMENT='주문 정보'