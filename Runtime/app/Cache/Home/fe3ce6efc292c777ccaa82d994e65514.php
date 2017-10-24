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
        if (obj.value<1){
            obj.value = "";            
        }
    }


</script>


<body>
    <h1>股票池</h1>

    <hr>

    <h3>股票池</h3>

    <?php if(is_array($list)): foreach($list as $key=>$vo): ?>股票编码:<?php echo ($vo['s_stock_no']); ?> --- 股票名称:<?php echo ($vo['s_stock_name']); ?> --- 
        
        <span>
            股票剩余数: 
            <span class="s_stock_number_<?php echo ($vo['id']); ?>" >
                <?php echo ($vo['s_stock_number']); ?>
            </span>
            
        </span>

        --- 股票每手价格:<?php echo ($vo['s_stock_price']); ?>  <br>


        <!-- 交易表单 -->
        <div style="padding:6px;border:1px solid red;">

            <form action="/index.php/Home/StockPoll/buystockpoll" method="POST">
                
                买入手数: <input type="text" min="1" onInput="clearNoNum(this)" name="deal_number"> <br>

                <!-- 股票编码:  --><input type="hidden" value="<?php echo ($vo['s_stock_no']); ?>" name="stock_no">


                <button type="submit">购买</button>


            </form>






        </div>


        <hr><?php endforeach; endif; ?>





</body>
<script src="/Public/libs/jquery.min.js"></script>

<!-- goeasy -->
    <script type="text/javascript" src="https://cdn-hangzhou.goeasy.io/goeasy.js"></script>
    <script type="text/javascript">
            var goEasy = new GoEasy({
                appkey: 'BC-0d3594612a9f46128008ce2723163ddc'
            });
            goEasy.subscribe({
                channel: 'stock_poll_number_handle',
                onMessage: function(message){
                    var contentObj=jQuery.parseJSON(message.content);
                    console.log(contentObj);
                    $('.s_stock_number_'+contentObj.id).html(contentObj.s_stock_number);
                    
                }
            });

    </script>




</html>