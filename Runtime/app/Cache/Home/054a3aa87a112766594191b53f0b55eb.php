<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>登录页面</title>
</head>
<body>

    <form action="/index.php/Home/LoginRegister/loginhandle" method="POST">
        用户名:<input type="email" name="email" required><br>
        密码：<input type="password" name="password" required><br>
        验证码: <input type="text" name="verfy" required><br>
        <img src="/index.php/Home/Verfy/verfy"  alt="验证码" title="看不清，点我换一张" id="verfy-img"><br>
        <input type="submit" value="提交"><br>
        <button type="button"><a href="/index.php/Home/LoginRegister/register">注册</a></button>
    </form>
    <br>
   





</body>
<script src="/Public/libs/jquery.min.js"></script>
<script>
    $(function(){
        // 更改验证码
        $('#verfy-img').on('click',function(){
            var timestamp=new Date().getTime();
            $(this).attr({'src':'/index.php/Home/Verfy/verfy?time='+timestamp});
        })
    })
</script>


</html>