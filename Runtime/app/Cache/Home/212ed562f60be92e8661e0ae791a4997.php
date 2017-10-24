<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>资金明细</title>
</head>
<body>
        <?php if(is_array($list)): foreach($list as $key=>$vo): ?><!-- 显示是买入还是卖出以及交易的类别 -->
        
                <?php if($vo['in_out_flag'] == 1): ?>入金
                        <?php else: ?> 
                        出金<?php endif; ?> 
                <?php echo ($vo['change_balance']); ?> 元
                ---
        
                原因 : 
        
                <span class="deal-reason" style="color:yellowgreen"><?php echo ($vo['change_type']); ?></span> 
        
                --- 
        
        
                交易时间 : <?php echo ($vo['create_time']); ?> <br><?php endforeach; endif; ?>
            
            <div class="result page"><?php echo ($page); ?></div>




    
</body>
<script src="/Public/libs/jquery.min.js"></script>
<script>
    $(function(){
        var type_to_msg={
            '1':'股票交易',
            '2':'撤销订单',
            '3':'委托下单',
            '4':'入金操作',
            '5':'出金操作'
        };

        $('.deal-reason').each(function(){
            var text=type_to_msg[$(this).text()];
            $(this).text(text);
        });


    })

</script>
</html>