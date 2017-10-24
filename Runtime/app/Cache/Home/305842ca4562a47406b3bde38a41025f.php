<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>用户信息</title>
</head>
<body>
    <h1>用户信息</h1>

    
    <a href="/index.php/Home/User/changeinfo">修改信息</a>


    <hr>


    <h3>用户账户资金</h3>
    用户名:  <?php echo ($user_info['user_name']); ?> <br>
    用户邮箱:  <?php echo ($user_info['user_email']); ?> <br>
    账户资金:  <?php echo ($user_info['user_account']['user_account_blance']); ?> 元 <br>
    <hr>


    <h3>入金</h3>
    
    <button><a href="/index.php/Home/Desposit/index">账户入金</a></button>


    <hr>


    <h3>用户持有股票</h3>

    <hr>
    


    <?php if(is_array($user_stock)): foreach($user_stock as $key=>$vo): ?>股票编码: <?php echo ($vo['s_stock_no']); ?> ---
        股票名称: <?php echo ($vo['stock_poll']['s_stock_name']); ?> --- 
        股票持有数: <?php echo ($vo['stock_number']); ?> ---
        
        <button><a href="/index.php/Home/StockDeal/index?stockno=<?php echo ($vo['s_stock_no']); ?>">交易</a></button>

        <hr><?php endforeach; endif; ?>



    <hr>

    <!-- 用户订单 -->
    <h3>用户订单</h3>

    <button>
        <a href="/index.php/Home/UserDeal/index">我的订单</a>
    </button>

    <hr>

    <!-- 股票明细 -->
    <h3>股票明细</h3>
    <button>
        <a href="/index.php/Home/Detail/stock">股票明细</a>
    </button>

    <hr>

    <h3>资金明细</h3>

    <button>
        <a href="/index.php/Home/Detail/balance">资金明细</a>
    </button>










    

</body>
</html>