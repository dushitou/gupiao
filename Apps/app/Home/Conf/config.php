<?php
return array(
	//'配置项'=>'配置值'
	SHOW_PAGE_TRACE=>true,
	SESSION_AUTO_START => true,

	// mysql 连接配置
	'db_type'  => 'mysql',
	'db_user'  => 'root',
	'db_pwd'   => '',
	'db_host'  => 'localhost',
	'db_port'  => '3306',
	'db_name'  => 'stock_table',
	'db_charset'=>    'utf8',


	// 发送邮箱配置	
	MAIL_CON=>array(
		'MAIL_ADDRESS'=>'1227026350@qq.com', // 邮箱地址  
		'MAIL_LOGINNAME'=>'1227026350@qq.com', // 邮箱登录帐号
		'MAIL_SMTP'=>'smtp.qq.com', // 邮箱SMTP服务器
		'MAIL_PORT'=>465,
		'MAIL_PASSWORD'=>'vrguuaoexltuheed', // 邮箱密码
	),


	HOME_JS=>'/Public/libs/thinkphp/js',
	HOME_CSS=>'/Public/libs/thinkphp/css',

	// goeasy 实时推送
	GOEASY_APPKEY=>'PR-49c63b35eae04a0d9b5326f72759d429',


	// 扫呗支付
	// 支付接口
	PAY_URL=>'http://test.lcsw.cn:8045/lcsw/',
	// 商户号
	MERCHANT_NO=>'852100210000005',
	// 终端号
	TERMINAL_ID=>'30050895',
	// 口令
	ACCESS_TOKEN=>'a94d34f12d474ba48d51b71e2f0e5be7',

);