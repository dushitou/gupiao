<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>支付页面</title>
</head>
<body>

    <div>
        <h3>

        
        <?php if($result['pay_type'] == '010'): ?>微信
                    <?php else: ?> 
                    支付宝<?php endif; ?> 
            支付

            <br>

            扫一扫完成支付

        </h3>

        <hr />


        <!-- 支付二维码 -->
        <div class="qrcode"></div>


        <hr>

        <span>
                订单号 : <?php echo ($result['out_trade_no']); ?>
        </span>

    </div>




    


</body>
<script src="/Public/libs/jquery.min.js"></script>
<script src="/Public/libs/qrcode.min.js"></script>

<script>
    $(function(){

        // 生成支付二维码
        var pay_type="$result['pay_type']";
        var qr_code="$result['qr_code']";
        var out_trade_no="$result['out_trade_no']";
        var qrcode=$('.qrcode');
        // 设置参数方式
        var qrcode = new QRCode(qrcode[0], {
        text: qr_code,
        width: 256,
        height: 256,
        colorDark : '#000000',
        colorLight : '#ffffff',
        correctLevel : QRCode.CorrectLevel.H
        });
        


    })
</script>

</html>