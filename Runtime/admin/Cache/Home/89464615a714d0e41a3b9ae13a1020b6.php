<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>股票管理</title>
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
    <h1>股票管理</h1>
    <hr>
    <h3>股票的展示与修改</h3>

    <!-- 股票列表 -->
    <div style="padding:9px;border:1px solid #ccc;margin-bottom:6px;">


        <?php if(is_array($list)): foreach($list as $key=>$vo): ?><div>

                <?php echo ($key); ?> 股票编码:<?php echo ($vo['s_stock_no']); ?> --- 股票名称:<?php echo ($vo['s_stock_name']); ?> --- 股票现手数:<?php echo ($vo['s_stock_number']); ?> --- 股票总手数:<?php echo ($vo['s_total_number']); ?> --- 股票每手价格:<?php echo ($vo['s_stock_price']); ?> ---
                创建时间:<?php echo ($vo['update_time']); ?> ---
                <button class="change-stock-btn">修改</button> ---
                <button class="delete-stock-btn" stockid="<?php echo ($vo['id']); ?>" flag="<?php echo ($vo['show_flag']); ?>">
                    <?php if($vo['show_flag'] == 1): ?>删除
                    <?php else: ?>
                        取消删除<?php endif; ?>
                </button>
                <div class="change-form" style="border:1px solid blue;padding-top:9px;padding-bottom:9px;display:none;">
                    <form action="/admin.php/Home/Stock/changestock" method="POST">
                        股票名称:<input type="text" name="stock_name" required value="<?php echo ($vo['s_stock_name']); ?>">

                        <br>

                        <!-- 选择减少或者增加 -->
                        <select name="change_stock_number_type">
                                <option value ="add">增加</option>
                                <option value ="reduce">减少</option>
                        </select>

                        <input type="number" required value="0" name="change_stock_number">手 <br> 每手的价格: <input type="text"
                            onInput="clearNoNum(this)" name="stock_price" value="<?php echo ($vo['s_stock_price']); ?>" required> 元


                        <!-- 隐藏id -->
                        <input type="hidden" name="stock_id" value="<?php echo ($vo['id']); ?>">

                        <br>

                        <button type="submit">确认修改</button>

                    </form>

                </div>
            </div><?php endforeach; endif; ?>






        <div class="result page"><?php echo ($page); ?></div>

    </div>
    <hr>
    <h3>添加股票模块</h3>
    <!-- 添加股票模块 -->
    <div style="padding:9px;border:1px solid #ccc;">
        <form action="/admin.php/Home/Stock/addstock" method="POST">
            股票编码:<input type="text" name="stock_no" required> --- 股票名称: <input type="text" name="stock_name" required> ---
            股票总手数: <input type="number" name="stock_number" required> --- 每手的价格: <input type="text" onInput="clearNoNum(this)"  name="stock_price"
                required> 元
            <input type="submit" value="提交">
        </form>
    </div>




</body>
<script src="/Public/libs/jquery.min.js"></script>
<script>
    $(function () {
        var selectFormDom=null;
        $('.change-stock-btn').on('click', function () {
            $('.change-form').hide();
            $(this).parent().find('.change-form').show();
            if(selectFormDom){
                selectFormDom.parent().find('.change-form').hide();
                selectFormDom=null;    
            }else{
                selectFormDom=$(this);
            }
        })

        $('.delete-stock-btn').on('click', function () {
            var msg = "您真的确定要修改吗？\n\n请确认！";
            if (confirm(msg) == true) {
                var stockId = $(this).attr('stockid');
                var flag = $(this).attr('flag');
                if (flag == 0) {
                    flag = 1;
                } else {
                    flag = 0;
                }
                window.location.href = '/admin.php/Home/Stock/modifystock?id=' + stockId + '&flag=' +
                    flag;
            } else {
                return false;
            }
        })

    })
</script>


</html>