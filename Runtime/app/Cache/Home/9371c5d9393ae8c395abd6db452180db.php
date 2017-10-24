<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>用户首页</title>
</head>
<script>
    // 只允许输入数字 或浮点数
    function clearNoNum(obj) {
        //先把非数字的都替换掉，除了数字和
        obj.value = obj.value.replace(/[^\d]/g, "");
        //保证 大于等于1
        if (obj.value < 1) {
            obj.value = "";
        }
    }
</script>


<body>
    <?php if($login_state == true): ?>已登录
        <a href="/index.php/Home/LoginRegister/logouthandle">登出</a> |

        <!-- 用户中心 -->
        <a href="/index.php/Home/User/index">用户信息</a>


        <?php else: ?>
        <a href="/index.php/Home/LoginRegister/login">登录</a> |
        <a href="/index.php/Home/LoginRegister/register">注册</a><?php endif; ?>


    <hr>

    <h1>首页</h1>

    <hr>


    <h3><a href="/index.php/Home/StockPoll/index">股票池</a></h3>


    <hr>

    <h1>交易区</h1>

    <hr>

    <?php if(is_array($stock_poll)): foreach($stock_poll as $key=>$vo): ?>股票编码 <?php echo ($vo['s_stock_no']); ?> --- 股票名称 <?php echo ($vo['s_stock_name']); ?> --- 
        
        股票现价 
            
            <?php if($vo['stock_new_price']['s_new_price']): echo ($vo['stock_new_price']['s_new_price']); ?>
                <?php else: ?> 
                还未交易<?php endif; ?>

        ---

        <button><a href="/index.php/Home/StockDeal/index?stockno=<?php echo ($vo['s_stock_no']); ?>">交易</a></button>

        <hr><?php endforeach; endif; ?>


</html>