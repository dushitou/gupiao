<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>系统设置</title>
</head>
<script>
    // 只允许输入数字 或浮点数
    function clearNoNum(obj) {
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

<body>
    <h1>系统设置</h1>

    <hr>


    <form action="/admin.php/Home/Sys/changemoney" method="POST">
        手续费设置: <input type="text" onInput="clearNoNum(this)" required name="change_money" value="<?php echo ($change_money); ?>">% <br>
        <button type="submit">提交</button>        
    </form>
    <hr>
    <form action="/admin.php/Home/Sys/changetime" method="POST">
        开盘时间: <input type="time" value="<?php echo ($config['open_time']); ?>" name="open_time"/> <br>
        收盘时间: <input type="time" value="<?php echo ($config['close_time']); ?>" name="close_time"/> <br>

        <button type="submit">提交</button>
    </form>


    <hr>



</body>

</html>