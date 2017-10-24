<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>用户信息修改</title>
</head>
<body>
    <h1>用户信息修改</h1>

    <hr>

    <h3>修改用户名</h3>
    <form action="/index.php/Home/User/changenamehandle" method="POST">


        用户名: <input type="text" name="user_name" value="<?php echo ($user_info['user_name']); ?>"> <br>
        <button type="submit">提交</button>


    </form>







</body>
</html>