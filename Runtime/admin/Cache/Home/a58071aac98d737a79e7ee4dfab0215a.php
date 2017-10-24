<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>登录</title>
</head>
<body>
    <h3>登录</h3>
    <form action="/admin.php/Home/Login/loginhandle" method="POST">
      用户名:  <input type="text" name="admname"> <br>
      密码:   <input type="password" name="admpassword"> <br>
      验证码:  <input type="text" name="verfy"> <br>
        <img src="/admin.php/Home/Verfy/verfy" alt="验证码" title="看不清，再换一张" id="verfy-img"> <br>
        <button type="submit">登录</button>
    </form>

    
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