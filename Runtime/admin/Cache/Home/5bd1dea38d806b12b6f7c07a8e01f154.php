<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>用户列表控制</title>
</head>

<body>
    <h1>用户控制</h1>

    <hr>

    <h3>用户管理列表</h3>

    <div style="padding:6px;border:1px solid #ccc;">
        <?php if(is_array($list)): foreach($list as $key=>$vo): ?><div>

                用户昵称: <?php echo ($vo['user_name']); ?> --- 用户邮箱: <?php echo ($vo['user_email']); ?> --- 用户性别: <?php echo ($vo['user_sex']); ?> --- 创建时间:<?php echo ($vo['create_time']); ?> --- 用户资金:
                <?php echo ($vo['user_account']['user_account_blance']); ?> 元 ---

                <!-- 审核控制模块 -->
                <button>
                    <?php if($vo['confirm_flag'] == 0 ): ?><a href="/admin.php/Home/User/confirmhandle?flag=1&userinfoid=<?php echo ($vo['id']); ?>">通过审核</a>
                            
                            <?php else: ?>
                            
                            <a href="/admin.php/Home/User/confirmhandle?flag=0&userinfoid=<?php echo ($vo['id']); ?>">取消通过审核</a><?php endif; ?>
                </button>



                <button class="delete-user-btn" userinfoid="<?php echo ($vo['id']); ?>" flag="<?php echo ($vo['show_flag']); ?>">
                    <?php if($vo['show_flag'] == 1): ?>删除
                    <?php else: ?>
                    取消删除<?php endif; ?>
                </button>



            </div><?php endforeach; endif; ?>


    </div>


</body>
<script src="/Public/libs/jquery.min.js"></script>
<script>
    $(function(){
        // 点击删除按钮时执行的函数
        $('.delete-user-btn').on('click', function () {
            var msg = "您真的确定要删除吗？\n\n请确认！";
            if (confirm(msg) == true) {
                var userId = $(this).attr('userinfoid');
                var flag = $(this).attr('flag');
                if(flag==0){
                    flag=1;
                }else{
                    flag=0;
                }
                window.location.href='/admin.php/Home/User/modifyuser?id='+userId + '&flag='+flag;
            } else {
                return false;
            }
        })
    })

</script>



</html>