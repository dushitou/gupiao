-- phpMyAdmin SQL Dump
-- version 4.7.0-beta1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: 2017-10-24 14:46:28
-- 服务器版本： 5.6.17
-- PHP Version: 5.6.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `stock_table`
--

-- --------------------------------------------------------

--
-- 表的结构 `admin`
--

CREATE TABLE `admin` (
  `id` bigint(12) NOT NULL COMMENT '标识',
  `admin` varchar(30) DEFAULT NULL COMMENT '管理员姓名',
  `password` varchar(90) DEFAULT NULL COMMENT '管理员密码',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '创建时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='管理员表';

--
-- 转存表中的数据 `admin`
--

INSERT INTO `admin` (`id`, `admin`, `password`, `create_time`) VALUES
(1, 'admin', 'f379eaf3c831b04de153469d1bec345e', '2017-10-09 06:16:40');

-- --------------------------------------------------------

--
-- 表的结构 `alipay_withdraw_log`
--

CREATE TABLE `alipay_withdraw_log` (
  `id` bigint(12) NOT NULL COMMENT '标识',
  `user_withdraw_id` bigint(12) NOT NULL COMMENT '出金列表id',
  `transaction_log_id` bigint(12) DEFAULT NULL COMMENT '账户日志记录表id',
  `confirm_state` int(1) NOT NULL DEFAULT '0' COMMENT '审核状态 1 通过审核 0 未通过审核',
  `payee_type` varchar(30) CHARACTER SET utf8 NOT NULL COMMENT '转账的方式',
  `payee_account` varchar(90) CHARACTER SET utf8 NOT NULL COMMENT '转出的账户 与payee_type对应',
  `payee_real_name` varchar(60) CHARACTER SET utf8 NOT NULL COMMENT '转出账户真实姓名',
  `amount` double NOT NULL COMMENT '转出的金额',
  `timestamp` datetime NOT NULL COMMENT '转出的时间',
  `out_biz_no` varchar(60) NOT NULL COMMENT '转账唯一订单号',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='支付宝账户出金日志表';

-- --------------------------------------------------------

--
-- 表的结构 `config`
--

CREATE TABLE `config` (
  `id` bigint(6) NOT NULL COMMENT '标识id',
  `change_money` float NOT NULL DEFAULT '0' COMMENT '手续费',
  `open_time` varchar(18) CHARACTER SET utf8 NOT NULL DEFAULT '09:00:00' COMMENT '开盘时间',
  `close_time` varchar(18) NOT NULL DEFAULT '16:00:00' COMMENT '收盘时间'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- 转存表中的数据 `config`
--

INSERT INTO `config` (`id`, `change_money`, `open_time`, `close_time`) VALUES
(1, 0.06, '09:00', '18:00');

-- --------------------------------------------------------

--
-- 表的结构 `day_k_recorde`
--

CREATE TABLE `day_k_recorde` (
  `id` bigint(12) NOT NULL COMMENT '标识',
  `open` float NOT NULL COMMENT '开盘价',
  `close` float NOT NULL COMMENT '收盘价',
  `lowest` float NOT NULL COMMENT '最低价',
  `highest` int(11) NOT NULL COMMENT '最高价',
  `vloume` int(11) NOT NULL COMMENT '成交量',
  `s_stock_no` int(12) NOT NULL COMMENT '股票编码',
  `create_time` date NOT NULL COMMENT '创建时间'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- 转存表中的数据 `day_k_recorde`
--

INSERT INTO `day_k_recorde` (`id`, `open`, `close`, `lowest`, `highest`, `vloume`, `s_stock_no`, `create_time`) VALUES
(1, 2, 3, 1, 6, 8, 1, '2017-10-16'),
(2, 6, 6, 6, 6, 12, 3, '2017-10-17');

-- --------------------------------------------------------

--
-- 表的结构 `deal_recorde_log`
--

CREATE TABLE `deal_recorde_log` (
  `id` bigint(12) NOT NULL COMMENT '标识',
  `s_stock_no` varchar(12) CHARACTER SET utf8 NOT NULL COMMENT '股票标识',
  `s_stock_price` float NOT NULL COMMENT '股票成交价格',
  `s_deal_number` bigint(12) NOT NULL COMMENT '交易数',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='成交的日志';

--
-- 转存表中的数据 `deal_recorde_log`
--

INSERT INTO `deal_recorde_log` (`id`, `s_stock_no`, `s_stock_price`, `s_deal_number`, `create_time`) VALUES
(1, '1', 2, 1, '2017-10-16 01:19:15'),
(2, '1', 1, 1, '2017-10-16 01:19:47'),
(3, '1', 6, 3, '2017-10-16 01:21:20'),
(4, '1', 3, 3, '2017-10-16 01:21:39'),
(5, '3', 6, 3, '2017-10-17 02:01:19'),
(6, '3', 6, 3, '2017-10-17 02:27:16'),
(7, '3', 6, 3, '2017-10-17 02:27:48'),
(8, '3', 6, 3, '2017-10-17 02:45:45');

-- --------------------------------------------------------

--
-- 表的结构 `desposit_log`
--

CREATE TABLE `desposit_log` (
  `id` bigint(12) NOT NULL COMMENT '标识',
  `user_info_id` bigint(20) NOT NULL COMMENT '用户标识id'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='入金的列表记录';

-- --------------------------------------------------------

--
-- 表的结构 `saobei_desposit_log`
--

CREATE TABLE `saobei_desposit_log` (
  `id` bigint(12) NOT NULL COMMENT '标识',
  `user_info_id` bigint(12) NOT NULL COMMENT '用户标识id',
  `confirm_state` int(1) NOT NULL COMMENT '审核标识: 1审核 0未审核',
  `ammount` double NOT NULL DEFAULT '0' COMMENT '入金金额(元)',
  `pay_type` varchar(3) CHARACTER SET utf8 NOT NULL COMMENT '请求类型，010微信，020 支付宝，060qq钱包，080京东钱包，090口碑',
  `terminal_trace` varchar(60) CHARACTER SET utf8 DEFAULT NULL COMMENT '订单号',
  `terminal_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '交易时间',
  `total_fee` bigint(12) NOT NULL DEFAULT '0' COMMENT '入金数量 (分)',
  `order_body` varchar(30) CHARACTER SET utf8 DEFAULT NULL COMMENT '订单描述',
  `res_result_code` varchar(18) CHARACTER SET utf8 DEFAULT NULL COMMENT '业务结果',
  `res_result_msg` varchar(30) CHARACTER SET utf8 NOT NULL COMMENT '预支付返回提示信息',
  `res_terminal_trace` varchar(60) CHARACTER SET utf8 DEFAULT NULL COMMENT '订单号',
  `res_terminal_time` varchar(30) NOT NULL COMMENT '终端交易时间',
  `res_out_trade_no` varchar(60) CHARACTER SET utf8 DEFAULT NULL COMMENT '利楚唯一订单号',
  `res_qr_code` varchar(90) CHARACTER SET utf8 DEFAULT NULL COMMENT '二维码码串',
  `async_res_result_code` varchar(18) CHARACTER SET utf8 DEFAULT NULL COMMENT '业务结果',
  `async_res_result_msg` varchar(18) CHARACTER SET utf8 DEFAULT NULL COMMENT '交易订单状态',
  `create_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '创建的时间'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='扫呗账户入金日志表';

--
-- 转存表中的数据 `saobei_desposit_log`
--

INSERT INTO `saobei_desposit_log` (`id`, `user_info_id`, `confirm_state`, `ammount`, `pay_type`, `terminal_trace`, `terminal_time`, `total_fee`, `order_body`, `res_result_code`, `res_result_msg`, `res_terminal_trace`, `res_terminal_time`, `res_out_trade_no`, `res_qr_code`, `async_res_result_code`, `async_res_result_msg`, `create_time`) VALUES
(1, 2, 1, 100, '010', '2017101853975510', '2017-10-18 02:48:53', 10000, '微信入金', '01', '预支付请求成功', '2017101853975510', '20171018104853', '300508950021217101810485900008', 'weixin://wxpay/bizpayurl?pr=FePXhYF', '01', '支付成功', '2017-10-18 10:48:59'),
(2, 2, 1, 100, '010', '2017101810257100', '2017-10-18 02:55:11', 10000, '微信入金', '01', '预支付请求成功', '2017101810257100', '20171018105511', '300508950021217101810551600009', 'weixin://wxpay/bizpayurl?pr=VEprap7', '01', '支付成功', '2017-10-18 10:55:15'),
(3, 2, 1, 100, '010', '2017101854501019', '2017-10-18 02:56:06', 10000, '微信入金', '01', '预支付请求成功', '2017101854501019', '20171018105606', '300508950021217101810561100010', 'weixin://wxpay/bizpayurl?pr=0lCk3vJ', '01', '支付成功', '2017-10-18 10:56:09'),
(4, 2, 1, 100, '010', '2017101848100991', '2017-10-18 02:56:32', 10000, '微信入金', '01', '预支付请求成功', '2017101848100991', '20171018105632', '300508950021217101810563700011', 'weixin://wxpay/bizpayurl?pr=VZSxjvM', '01', '支付成功', '2017-10-18 10:56:35'),
(5, 2, 1, 100, '010', '2017101854574899', '2017-10-18 02:58:46', 10000, '微信入金', '01', '预支付请求成功', '2017101854574899', '20171018105846', '300508950021217101810585100012', 'weixin://wxpay/bizpayurl?pr=jDNaRrB', '01', '支付成功', '2017-10-18 10:58:56'),
(6, 2, 0, 100, '010', '2017101954495155', '2017-10-19 07:11:18', 10000, '微信入金', '01', '预支付请求成功', '2017101954495155', '20171019151118', '300508950021217101915112400002', 'weixin://wxpay/bizpayurl?pr=nZzvHru', NULL, NULL, '2017-10-19 15:11:19');

-- --------------------------------------------------------

--
-- 表的结构 `stock_deal_recorde`
--

CREATE TABLE `stock_deal_recorde` (
  `id` bigint(12) NOT NULL COMMENT '标识',
  `s_deal_no` bigint(12) NOT NULL COMMENT '交易流水号',
  `user_info_id` bigint(12) NOT NULL COMMENT '用户编码',
  `s_stock_no` int(12) NOT NULL COMMENT '股票编码',
  `s_stock_price` double NOT NULL COMMENT '交易价格',
  `s_deal_number` int(12) NOT NULL DEFAULT '0' COMMENT '交易数量',
  `s_place_number` bigint(12) NOT NULL DEFAULT '0' COMMENT '总下单数',
  `s_deal_type` int(1) NOT NULL COMMENT '交易类型:0卖出 1 买入',
  `s_deal_state` int(1) NOT NULL COMMENT '交易状态:1交易完成 0 交易未完成',
  `show_flag` int(1) NOT NULL DEFAULT '1' COMMENT '显示标识',
  `deal_flag` int(1) NOT NULL DEFAULT '1' COMMENT '交易标识',
  `s_document_create_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '交易创建时间',
  `s_deal_finish_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '交易结束时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='股票交易记录表';

--
-- 转存表中的数据 `stock_deal_recorde`
--

INSERT INTO `stock_deal_recorde` (`id`, `s_deal_no`, `user_info_id`, `s_stock_no`, `s_stock_price`, `s_deal_number`, `s_place_number`, `s_deal_type`, `s_deal_state`, `show_flag`, `deal_flag`, `s_document_create_time`, `s_deal_finish_time`) VALUES
(1, 2017101752989954, 2, 3, 6, 3, 3, 0, 1, 1, 1, '2017-10-17 02:26:46', '2017-10-17 02:26:46'),
(2, 2017101751515456, 3, 3, 6, 3, 3, 1, 1, 1, 1, '2017-10-17 02:27:15', '2017-10-17 02:27:15'),
(3, 2017101753995310, 2, 3, 6, 3, 3, 1, 1, 1, 1, '2017-10-17 02:27:33', '2017-10-17 02:27:33'),
(4, 2017101750101995, 3, 3, 6, 3, 3, 0, 1, 1, 1, '2017-10-17 02:27:47', '2017-10-17 02:27:47'),
(5, 2017101748561005, 2, 1, 1.888, 1, 1, 1, 1, 1, 1, '2017-10-17 02:34:56', '2017-10-17 02:34:56'),
(6, 2017101710151491, 2, 3, 1.666, 3, 3, 1, 1, 1, 1, '2017-10-17 02:35:42', '2017-10-17 02:35:42'),
(7, 2017101799102519, 2, 3, 6, 3, 3, 0, 1, 1, 1, '2017-10-17 02:45:33', '2017-10-17 02:45:33'),
(8, 2017101750569857, 3, 3, 6, 3, 3, 1, 1, 1, 1, '2017-10-17 02:45:38', '2017-10-17 02:45:38'),
(9, 2017101898974910, 2, 3, 6, 3, 3, 1, 0, 1, 0, '2017-10-18 02:23:39', '2017-10-18 02:24:23');

-- --------------------------------------------------------

--
-- 表的结构 `stock_new_price`
--

CREATE TABLE `stock_new_price` (
  `s_stock_no` int(12) NOT NULL,
  `s_new_price` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='股票最新价格';

--
-- 转存表中的数据 `stock_new_price`
--

INSERT INTO `stock_new_price` (`s_stock_no`, `s_new_price`) VALUES
(1, 5),
(2, 6),
(3, 6);

-- --------------------------------------------------------

--
-- 表的结构 `stock_poll`
--

CREATE TABLE `stock_poll` (
  `id` int(12) NOT NULL COMMENT '标识',
  `s_stock_no` int(12) NOT NULL COMMENT '股票编码',
  `s_stock_number` int(12) NOT NULL COMMENT '股票总手数',
  `s_stock_price` float NOT NULL DEFAULT '0' COMMENT '股票价格',
  `s_stock_name` varchar(30) NOT NULL DEFAULT '股票名' COMMENT '股票名称',
  `s_total_number` bigint(30) NOT NULL COMMENT '股票现有手数',
  `show_flag` int(1) NOT NULL DEFAULT '1' COMMENT '是否显示标识',
  `update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='股票池';

--
-- 转存表中的数据 `stock_poll`
--

INSERT INTO `stock_poll` (`id`, `s_stock_no`, `s_stock_number`, `s_stock_price`, `s_stock_name`, `s_total_number`, `show_flag`, `update_time`) VALUES
(1, 1, 293, 1.888, '股票', 2532, 1, '2017-10-17 02:34:56'),
(2, 2, 300, 1.58, '股票2', 1632, 1, '2017-10-17 02:26:28'),
(3, 3, 299, 1.666, '股票3', 1032, 1, '2017-10-17 02:35:41');

-- --------------------------------------------------------

--
-- 表的结构 `transaction_log`
--

CREATE TABLE `transaction_log` (
  `id` bigint(12) NOT NULL COMMENT '标识',
  `confirm_state` int(1) NOT NULL COMMENT '确认状态',
  `amount` double NOT NULL COMMENT '出入金数量',
  `transaction_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '出入金时间',
  `reason` int(1) NOT NULL COMMENT '出入金标识: 1入金 0 出金'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='账户日志记录表';

-- --------------------------------------------------------

--
-- 表的结构 `user_account`
--

CREATE TABLE `user_account` (
  `id` bigint(12) NOT NULL COMMENT '标识',
  `user_info_id` bigint(12) NOT NULL COMMENT '用户标识id',
  `user_account_blance` double NOT NULL COMMENT '用户资金',
  `update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- 转存表中的数据 `user_account`
--

INSERT INTO `user_account` (`id`, `user_info_id`, `user_account_blance`, `update_time`) VALUES
(2, 2, 574.482, '2017-10-18 02:58:56'),
(3, 3, 3472.6584, '2017-10-17 02:45:38');

-- --------------------------------------------------------

--
-- 表的结构 `user_balance_log`
--

CREATE TABLE `user_balance_log` (
  `id` bigint(12) NOT NULL COMMENT '标识',
  `user_info_id` bigint(12) NOT NULL COMMENT '用户标识',
  `change_balance` float NOT NULL COMMENT '更改的资金',
  `change_type` int(1) NOT NULL COMMENT '改变标识',
  `in_out_flag` int(1) NOT NULL COMMENT '出入金标识',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '创建时间'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='用户资金详情表';

--
-- 转存表中的数据 `user_balance_log`
--

INSERT INTO `user_balance_log` (`id`, `user_info_id`, `change_balance`, `change_type`, `in_out_flag`, `create_time`) VALUES
(1, 3, 18, 3, 0, '2017-10-17 02:27:15'),
(2, 2, 16.92, 1, 1, '2017-10-17 02:27:16'),
(3, 2, 18, 3, 0, '2017-10-17 02:27:33'),
(4, 3, 16.92, 1, 1, '2017-10-17 02:27:48'),
(5, 2, 11.328, 1, 0, '2017-10-17 02:28:25'),
(6, 2, 1.888, 1, 0, '2017-10-17 02:34:56'),
(7, 2, 4.998, 1, 0, '2017-10-17 02:35:41'),
(8, 3, 18, 3, 0, '2017-10-17 02:45:38'),
(9, 2, 16.92, 1, 1, '2017-10-17 02:45:44'),
(10, 2, 18, 3, 0, '2017-10-18 02:23:39'),
(11, 2, 18, 2, 1, '2017-10-18 02:24:23'),
(12, 2, 100, 5, 1, '2017-10-18 02:55:15'),
(13, 2, 100, 5, 1, '2017-10-18 02:56:09'),
(14, 2, 100, 4, 1, '2017-10-18 02:56:35'),
(15, 2, 100, 4, 1, '2017-10-18 02:58:56');

-- --------------------------------------------------------

--
-- 表的结构 `user_info`
--

CREATE TABLE `user_info` (
  `id` bigint(12) NOT NULL COMMENT '标识',
  `user_name` varchar(30) DEFAULT '无名' COMMENT '用户昵称',
  `user_phone` varchar(30) CHARACTER SET utf8 DEFAULT NULL COMMENT '用户手机号',
  `user_email` varchar(60) DEFAULT NULL COMMENT '用户邮箱',
  `user_password` varchar(60) CHARACTER SET utf8 NOT NULL COMMENT '密码',
  `user_cardnum` int(30) NOT NULL DEFAULT '0' COMMENT '身份证号',
  `user_sex` int(1) NOT NULL DEFAULT '0' COMMENT '性别:1男0女 ',
  `show_flag` int(1) NOT NULL DEFAULT '1' COMMENT '显示标识',
  `confirm_flag` int(1) DEFAULT '0' COMMENT '审核状态',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '创建时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='用户信息表';

--
-- 转存表中的数据 `user_info`
--

INSERT INTO `user_info` (`id`, `user_name`, `user_phone`, `user_email`, `user_password`, `user_cardnum`, `user_sex`, `show_flag`, `confirm_flag`, `create_time`) VALUES
(2, '&lt;h1&gt;石头&lt;/h1&gt;', NULL, '1227026350@qq.com', 'd41d8cd98f00b204e9800998ecf8427e', 0, 0, 1, 1, '2017-10-12 03:32:53'),
(3, '无名', NULL, '1660894787@qq.com', 'f379eaf3c831b04de153469d1bec345e', 0, 0, 1, 1, '2017-10-12 03:40:45');

-- --------------------------------------------------------

--
-- 表的结构 `user_stock`
--

CREATE TABLE `user_stock` (
  `id` bigint(12) NOT NULL COMMENT '标识',
  `s_stock_no` int(12) NOT NULL COMMENT '股票编码',
  `stock_number` bigint(30) NOT NULL COMMENT '股票持有数',
  `user_info_id` bigint(12) NOT NULL COMMENT '与用户表对应的标识'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户股票持有表';

--
-- 转存表中的数据 `user_stock`
--

INSERT INTO `user_stock` (`id`, `s_stock_no`, `stock_number`, `user_info_id`) VALUES
(1, 1, 277, 3),
(2, 3, 132, 3),
(3, 2, 269, 3),
(4, 1, 72, 2),
(5, 2, 408, 2),
(6, 3, 376, 2);

-- --------------------------------------------------------

--
-- 表的结构 `user_stock_deal_log`
--

CREATE TABLE `user_stock_deal_log` (
  `id` bigint(12) NOT NULL COMMENT '标识',
  `user_info_id` bigint(12) NOT NULL COMMENT '用户标识id',
  `s_stock_no` int(12) NOT NULL COMMENT '股票编码',
  `stock_number` bigint(12) NOT NULL COMMENT '交易数量',
  `change_type` int(1) NOT NULL COMMENT '改变标识',
  `in_out_flag` int(1) NOT NULL COMMENT '出入标识',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '交易成功的时间'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='用户股票交易表';

--
-- 转存表中的数据 `user_stock_deal_log`
--

INSERT INTO `user_stock_deal_log` (`id`, `user_info_id`, `s_stock_no`, `stock_number`, `change_type`, `in_out_flag`, `create_time`) VALUES
(1, 2, 3, 3, 3, 0, '2017-10-17 02:26:44'),
(2, 3, 3, 3, 1, 1, '2017-10-17 02:27:16'),
(3, 3, 3, 3, 3, 0, '2017-10-17 02:27:47'),
(4, 2, 3, 3, 1, 1, '2017-10-17 02:27:47'),
(5, 2, 1, 6, 1, 1, '2017-10-17 02:28:26'),
(6, 2, 1, 1, 1, 1, '2017-10-17 02:34:56'),
(7, 2, 3, 3, 1, 1, '2017-10-17 02:35:42'),
(8, 2, 3, 3, 3, 0, '2017-10-17 02:45:33'),
(9, 3, 3, 3, 1, 1, '2017-10-17 02:45:44');

-- --------------------------------------------------------

--
-- 表的结构 `user_withdraw`
--

CREATE TABLE `user_withdraw` (
  `id` bigint(12) NOT NULL COMMENT '标识',
  `confirm_state` int(1) NOT NULL DEFAULT '0' COMMENT '审核标识:1 通过审核 0 未通过审核',
  `amount` double NOT NULL COMMENT '出金的金额',
  `withdraw_type` int(1) NOT NULL COMMENT '出金的方式 1 微信 2 支付宝',
  `show_state` int(1) NOT NULL DEFAULT '1' COMMENT '删除标识 1 未删除 0 删除',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='出金的列表';

-- --------------------------------------------------------

--
-- 表的结构 `weixin_withdraw_log`
--

CREATE TABLE `weixin_withdraw_log` (
  `id` bigint(12) NOT NULL COMMENT '标识',
  `user_withdraw_id` bigint(12) NOT NULL COMMENT '出金列表id',
  `transaction_log_id` bigint(12) DEFAULT NULL COMMENT '账户日志记录表id',
  `confirm_state` int(1) NOT NULL DEFAULT '0' COMMENT '审核状态 1 通过审核 0 未通过审核',
  `openid` varchar(60) NOT NULL COMMENT '某用户的openid',
  `re_user_name` varchar(60) NOT NULL COMMENT '收款用户真实姓名',
  `amount` bigint(12) NOT NULL COMMENT '转账数额 单位(分)',
  `timestamp` datetime DEFAULT NULL COMMENT '转出的时间',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='微信转账出金日志表';

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `alipay_withdraw_log`
--
ALTER TABLE `alipay_withdraw_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `config`
--
ALTER TABLE `config`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `day_k_recorde`
--
ALTER TABLE `day_k_recorde`
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `deal_recorde_log`
--
ALTER TABLE `deal_recorde_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `desposit_log`
--
ALTER TABLE `desposit_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `saobei_desposit_log`
--
ALTER TABLE `saobei_desposit_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stock_deal_recorde`
--
ALTER TABLE `stock_deal_recorde`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `s_deal_no` (`s_deal_no`,`s_stock_no`);

--
-- Indexes for table `stock_new_price`
--
ALTER TABLE `stock_new_price`
  ADD UNIQUE KEY `s_stock_no` (`s_stock_no`),
  ADD UNIQUE KEY `s_stock_no_2` (`s_stock_no`);

--
-- Indexes for table `stock_poll`
--
ALTER TABLE `stock_poll`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `s_stock_no` (`s_stock_no`),
  ADD UNIQUE KEY `s_stock_no_2` (`s_stock_no`),
  ADD UNIQUE KEY `s_stock_no_3` (`s_stock_no`);

--
-- Indexes for table `transaction_log`
--
ALTER TABLE `transaction_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_account`
--
ALTER TABLE `user_account`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_balance_log`
--
ALTER TABLE `user_balance_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_info`
--
ALTER TABLE `user_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_stock`
--
ALTER TABLE `user_stock`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_stock_deal_log`
--
ALTER TABLE `user_stock_deal_log`
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `user_withdraw`
--
ALTER TABLE `user_withdraw`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `weixin_withdraw_log`
--
ALTER TABLE `weixin_withdraw_log`
  ADD PRIMARY KEY (`id`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `admin`
--
ALTER TABLE `admin`
  MODIFY `id` bigint(12) NOT NULL AUTO_INCREMENT COMMENT '标识', AUTO_INCREMENT=2;
--
-- 使用表AUTO_INCREMENT `alipay_withdraw_log`
--
ALTER TABLE `alipay_withdraw_log`
  MODIFY `id` bigint(12) NOT NULL AUTO_INCREMENT COMMENT '标识';
--
-- 使用表AUTO_INCREMENT `config`
--
ALTER TABLE `config`
  MODIFY `id` bigint(6) NOT NULL AUTO_INCREMENT COMMENT '标识id', AUTO_INCREMENT=2;
--
-- 使用表AUTO_INCREMENT `day_k_recorde`
--
ALTER TABLE `day_k_recorde`
  MODIFY `id` bigint(12) NOT NULL AUTO_INCREMENT COMMENT '标识', AUTO_INCREMENT=3;
--
-- 使用表AUTO_INCREMENT `deal_recorde_log`
--
ALTER TABLE `deal_recorde_log`
  MODIFY `id` bigint(12) NOT NULL AUTO_INCREMENT COMMENT '标识', AUTO_INCREMENT=9;
--
-- 使用表AUTO_INCREMENT `desposit_log`
--
ALTER TABLE `desposit_log`
  MODIFY `id` bigint(12) NOT NULL AUTO_INCREMENT COMMENT '标识';
--
-- 使用表AUTO_INCREMENT `saobei_desposit_log`
--
ALTER TABLE `saobei_desposit_log`
  MODIFY `id` bigint(12) NOT NULL AUTO_INCREMENT COMMENT '标识', AUTO_INCREMENT=7;
--
-- 使用表AUTO_INCREMENT `stock_deal_recorde`
--
ALTER TABLE `stock_deal_recorde`
  MODIFY `id` bigint(12) NOT NULL AUTO_INCREMENT COMMENT '标识', AUTO_INCREMENT=10;
--
-- 使用表AUTO_INCREMENT `stock_poll`
--
ALTER TABLE `stock_poll`
  MODIFY `id` int(12) NOT NULL AUTO_INCREMENT COMMENT '标识', AUTO_INCREMENT=4;
--
-- 使用表AUTO_INCREMENT `transaction_log`
--
ALTER TABLE `transaction_log`
  MODIFY `id` bigint(12) NOT NULL AUTO_INCREMENT COMMENT '标识';
--
-- 使用表AUTO_INCREMENT `user_account`
--
ALTER TABLE `user_account`
  MODIFY `id` bigint(12) NOT NULL AUTO_INCREMENT COMMENT '标识', AUTO_INCREMENT=4;
--
-- 使用表AUTO_INCREMENT `user_balance_log`
--
ALTER TABLE `user_balance_log`
  MODIFY `id` bigint(12) NOT NULL AUTO_INCREMENT COMMENT '标识', AUTO_INCREMENT=16;
--
-- 使用表AUTO_INCREMENT `user_info`
--
ALTER TABLE `user_info`
  MODIFY `id` bigint(12) NOT NULL AUTO_INCREMENT COMMENT '标识', AUTO_INCREMENT=4;
--
-- 使用表AUTO_INCREMENT `user_stock`
--
ALTER TABLE `user_stock`
  MODIFY `id` bigint(12) NOT NULL AUTO_INCREMENT COMMENT '标识', AUTO_INCREMENT=7;
--
-- 使用表AUTO_INCREMENT `user_stock_deal_log`
--
ALTER TABLE `user_stock_deal_log`
  MODIFY `id` bigint(12) NOT NULL AUTO_INCREMENT COMMENT '标识', AUTO_INCREMENT=10;
--
-- 使用表AUTO_INCREMENT `user_withdraw`
--
ALTER TABLE `user_withdraw`
  MODIFY `id` bigint(12) NOT NULL AUTO_INCREMENT COMMENT '标识';
--
-- 使用表AUTO_INCREMENT `weixin_withdraw_log`
--
ALTER TABLE `weixin_withdraw_log`
  MODIFY `id` bigint(12) NOT NULL AUTO_INCREMENT COMMENT '标识';
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
