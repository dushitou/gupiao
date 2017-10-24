<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>用户订单</title>
</head>
<body>
    <h1>用户订单</h1>


    <?php if(is_array($list)): foreach($list as $key=>$vo): ?>股票编码 <?php echo ($vo['s_stock_no']); ?> ---
        股票名称 <?php echo ($vo['stock_poll']['s_stock_name']); ?> ---

        股票订单价 <?php echo ($vo['s_stock_price']); ?> 元 --- 
        股票现价 <?php echo ($vo['stock_new_price']['s_new_price']); ?> 元 ---

        剩余的可交易数量 <?php echo ($vo['s_deal_number']); ?> 手 ---

        下单数量 <?php echo ($vo['s_place_number']); ?> 手 ---
        

        交易方式 
        <span style="color:red"> 
                <?php if($vo['s_deal_type'] == 1): ?>买入
                        <?php else: ?> 
                        卖出<?php endif; ?>

        </span>
        

        ---

        下单时间 <?php echo ($vo['s_document_create_time']); ?> ---


        <span style="color:red"> 
                <?php if($vo['s_deal_state'] == 1 AND $vo['deal_flag'] == 1 ): ?>已完成
                        <?php elseif($vo['deal_flag'] == 0): ?>
                        已撤销
                        <?php else: ?> 
                        正在交易<?php endif; ?>

        </span> 

        ---


        <?php if($vo['s_deal_state'] == 1 AND $vo['deal_flag'] == 1): ?><button disabled>已完成</button>
                <?php elseif($vo['deal_flag'] == 0): ?>
                    <button disabled>已撤销</button>
                <?php else: ?> 
                <button class="recall-deal-btn" deal-recorde-id="<?php echo ($vo['id']); ?>"> 
                    <a href="/index.php/Home/UserDeal/recalldeal?deal_no=<?php echo ($vo['s_deal_no']); ?>">撤销订单</a>
                </button><?php endif; ?>
        



        <hr><?php endforeach; endif; ?>




    <div class="result page"><?php echo ($page); ?></div>



</body>
<script src="/Public/libs/jquery.min.js"></script>
<script>
    $(function(){





    })
</script>

</html>