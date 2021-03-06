<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>股票交易区</title>
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

        // 只允许输入数字 或浮点数
        function clearFloatNum(obj) {
            //先把非数字的都替换掉，除了数字和.
            obj.value = obj.value.replace(/[^\d.]/g, "");
            //必须保证第一个为数字而不是.
            obj.value = obj.value.replace(/^\./g, "");
            //保证只有出现一个.而没有多个.
            obj.value = obj.value.replace(/\.{2,}/g, ".");
            //保证.只出现一次，而不能出现两次以上
            obj.value = obj.value.replace(".", "$#$").replace(/\./g, "").replace("$#$", ".");
            //保证 数字整数位不大于8位
            if (100000000 <= parseFloat(obj.value))
                obj.value = "";
        }
    </script>
</head>

<body>
    <h1>股票交易区</h1>

    <hr>

    <h3>股票价格显示区</h3>
    
    <p style="color:red" class="new-price_<?php echo ($stock_info['s_stock_no']); ?>"><?php echo ($stock_info['stock_new_price']['s_new_price']); ?></p>


    <hr>


    <h3>k线图</h3>
    
        <hr>
        
            <div id="main" style="width: 100%;height:600px;"></div>

        <hr>


    <h3>用户持股信息</h3>
    股票编码: <?php echo ($user_stock['s_stock_no']); ?> --- 股票名称: <?php echo ($user_stock['stock_poll']['s_stock_name']); ?> --- 持有数 : <?php echo ($user_stock['stock_number']); ?>
    手 ---

    <hr>


    <h3>股票交易区</h3>
    <hr>

    <div style="padding:6px;border:1px solid #ccc;">

        <div>
            <button class="buy-stock-btn">买入</button><br> 交易手数: <input type="text" onInput="clearNoNum(this)" class="stock-deal-number"><br>            交易价格: <input type="text" onInput="clearFloatNum(this)" value="<?php echo ($stock_info['stock_new_price']['s_new_price']); ?>"
                class="stock-deal-price"><br>
            <button class="sell-stock-btn">卖出</button>
        </div>


    </div>


    <hr>
  


    

</body>
<script src="/Public/libs/jquery.min.js"></script>
<script>
    $(function () {
        // 买入的操作
        $('.buy-stock-btn').on('click', function () {
            var stock_deal_number = $('.stock-deal-number').val();
            var stock_deal_price = $('.stock-deal-price').val();

            var stock_no = "<?php echo ($user_stock['s_stock_no']); ?>";

            if (!stock_deal_price || !stock_deal_number || !stock_no) {
                alert('不能为空!');
                return;
            }

            $.ajax({
                type: 'POST',
                url: '/index.php/Home/StockDeal/buystockhandle',
                data: {
                    stock_no: stock_no,
                    deal_number: stock_deal_number,
                    deal_price: stock_deal_price
                },
                success: function (data) {
                    console.log(data);
                },
                error: function (data) {
                    console.log(data);
                }
            })



        })


        // 卖出的操作
        $('.sell-stock-btn').on('click', function () {
            var stock_deal_number = $('.stock-deal-number').val();
            var stock_deal_price = $('.stock-deal-price').val();

            var stock_no = "<?php echo ($user_stock['s_stock_no']); ?>";

            if (!stock_deal_price || !stock_deal_number || !stock_no) {
                alert('不能为空!');
                return;
            }


            $.ajax({
                type: 'POST',
                url: '/index.php/Home/StockDeal/sellstockhandle',
                data: {
                    stock_no: stock_no,
                    deal_number: stock_deal_number,
                    deal_price: stock_deal_price
                },
                success: function (data) {
                    console.log(data);
                },
                error: function (data) {
                    console.log(data);
                }
            })

        })




    })
</script>

<!-- goeasy -->
<script type="text/javascript" src="https://cdn-hangzhou.goeasy.io/goeasy.js"></script>
<script type="text/javascript">
    var goEasy = new GoEasy({
        appkey: 'BC-0d3594612a9f46128008ce2723163ddc'
    });
    goEasy.subscribe({
        channel: 'change_new_price',
        onMessage: function (message) {
            var contentObj = jQuery.parseJSON(message.content);
            console.log(contentObj.s_stock_no);
            console.log(contentObj.s_new_price);

            // 修改最新价格
            $('.new-price_' + contentObj.s_stock_no).html(contentObj.s_new_price);

        }
    });
</script>

<!-- 引入 echarts.js -->
<script src="/Public/libs/echarts.js"></script>
<!-- 生成k线图 -->
<script src="/Public/js/make_k_chart.js"></script>

<script>
    var dom=document.getElementById('main');
    var s_stock_no="<?php echo ($user_stock['s_stock_no']); ?>";
    var timestamp = Date.parse(new Date());
    var jsonurl='/Data/daystock/'+s_stock_no+'.json?time='+timestamp;
    $.ajax(jsonurl, {
        type: 'get',
        timeout: 3000,
        success: function(data) {
            make_k_chart(data,dom);
        },
        error: function(err) {
            console.log(err);
        }
    });
    

</script>

</html>