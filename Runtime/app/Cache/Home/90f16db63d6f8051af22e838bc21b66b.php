<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>入金操作</title>
</head>
<body>
    <h1>入金操作</h1>

    <hr>

    <form action="/index.php/Home/Desposit/prepay" method="POST" target= "_blank">
        入金金额: <input type="number" min="100" name="desposit_ammount" required> 元 <br>

        入金方式 : <label><input name="desposit_type" type="radio" value="1" checked/>微信 </label> 
        <label><input name="desposit_type" type="radio" value="2" />支付宝 </label> 

        
        <button>确认入金</button>

    </form>



    
</body>
</html>