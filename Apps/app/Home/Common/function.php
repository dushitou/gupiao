<?php
    // 检测是否登录
    function checkLogin(){
        return cookie('user_email') && session('user_id');
    }

    // 检测输入的验证码是否正确，$code为用户输入的验证码字符串
    function check_verify($code, $id = ''){
        $verify = new \Think\Verify();
        return $verify->check($code, $id);
    }

    // 邮件通知
    function send_to_email($userEmail,$title,$emailContent){
        $address=$userEmail;
        $title=$title;
        // 发送邮件的内容
        $emailContent=$emailContent;
        import('Org.Net.PHPMailer');
        $mail=new PHPMailer();
        // 设置PHPMailer使用SMTP服务器发送Email
        $mail->IsSMTP();
        $mail->SMTPSecure = 'ssl'; // 使用安全协议
        // 设置邮件的字符编码，若不指定，则为'UTF-8'
        $mail->CharSet='UTF-8';
        $mail->isHTML(true); 
        // 添加收件人地址，可以多次使用来添加多个收件人
        $mail->AddAddress($address);
        // 设置邮件正文
        $mail->Body=$emailContent;
        // 设置邮件头的From字段。
        $mail->From=C('MAIL_CON')['MAIL_ADDRESS'];
        $mail->Port=C('MAIL_CON')['MAIL_PORT'];
        // 设置发件人名字
        $mail->FromName='初阳科技';
        // 设置邮件标题
        $mail->Subject=$title;
        // 设置SMTP服务器。
        $mail->Host=C('MAIL_CON')['MAIL_SMTP'];
        // 设置为“需要验证”
        $mail->SMTPAuth=true;
        // 设置用户名和密码。
        $mail->Username=C('MAIL_CON')['MAIL_LOGINNAME'];
        $mail->Password=C('MAIL_CON')['MAIL_PASSWORD'];
        return $mail->Send();
    }

    // post json方式传递参数获取数据
    function http_post_data($url, $data_string) {  
        $ch = curl_init();  
        curl_setopt($ch, CURLOPT_POST, 1);  
        curl_setopt($ch, CURLOPT_URL, $url);  
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);  
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(  
            'Content-Type: application/json; charset=utf-8',  
            'Content-Length: ' . strlen($data_string))  
        );
        ob_start();  
        curl_exec($ch);  
        $return_content = ob_get_contents();  
        ob_end_clean();  
        $return_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);  
        return array($return_code, $return_content);  
    }

    //向普通http服务器POST数据
    function do_post($url = '', $param = '') {
        if (empty($url) || empty($param)) {
            return false;
        }

        $postUrl = $url;
        $curlPost = http_build_query($param);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$postUrl);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
        $data = curl_exec($ch);
        curl_close($ch);

        return $data;
    }


    // 服务器推送的消息
    function push_message($channel,$content){
        $url='http://rest-hangzhou.goeasy.io/publish';
        $post_arr=array(
            'appkey'=>C('GOEASY_APPKEY'),
            'channel'=>$channel,
            'content'=>$content
        );
        $res=do_post($url,$post_arr);
        return $res;
    }


    // 获取用户id
    function get_user_id(){
        if(session('user_id')){
            return session('user_id');
        }else{     
            redirect(__ROOT__ . '/index.php/Home/LoginRegister/login');
            exit('用户不存在');
        }
    }


    // 生成订单号
    function build_order_no() {    
        return date('Ymd').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
    }



    // 增加用户股票日志
    function add_user_stock_log($add_data){
        $user_stock_deal_log_model=M('user_stock_deal_log');
        if($add_data){
            $user_stock_deal_log_add_res=$user_stock_deal_log_model->data($add_data)->add();

            if($user_stock_deal_log_add_res){
                return array('return_code'=>1,'return_msg'=>'添加成功');                
            }else{
                return array('return_code'=>2,'return_msg'=>'添加失败');
            }

        }else{
            return array('return_code'=>2,'return_msg'=>'参数错误');
        }
    }

    // 增加用户资金日志
    function add_user_balance_log($add_data){
        $user_balance_log_model=M('user_balance_log');
        if($add_data){
            $user_balance_log_add_res=$user_balance_log_model->data($add_data)->add();


            if($user_balance_log_add_res){
                return array('return_code'=>1,'return_msg'=>'添加成功');
            }else{
                return array('return_code'=>2,'return_msg'=>'添加失败');    
            }

        }else{
            return array('return_code'=>2,'return_msg'=>'参数错误');
        }

    }




    
    // 增加用户持股数
    function add_user_stock($user_id,$stock_no,$deal_number,$change_type){
        if($user_id && $stock_no && $deal_number){
            
            $user_stock_model=M('user_stock');

            $user_stock_select_data=$user_stock_model->where(array('user_info_id'=>$user_id,'s_stock_no'=>$stock_no))->select();
            if($user_stock_select_data[0]){
                $user_stock_id=$user_stock_select_data[0]['id'];
                $neetset_stock_number=$user_stock_select_data[0]['stock_number']+$deal_number;
                $update_user_stock_data=array(
                    'id'=>$user_stock_id,
                    'user_info_id'=>$user_id,
                    's_stock_no'=>$stock_no,
                    'stock_number'=>$neetset_stock_number
                );

                $update_user_stock_res=$user_stock_model->save($update_user_stock_data);

                if($update_user_stock_res){


                    // 增加用户股票日志
                    $add_user_stock_log_data=array(
                        'user_info_id'=>$user_id,
                        's_stock_no'=>$stock_no,
                        'stock_number'=>$deal_number,
                        'change_type'=>$change_type,
                        'in_out_flag'=>1
                    );
                    $add_user_stock_log_res=add_user_stock_log($add_user_stock_log_data);

                    if($add_user_stock_log_res['return_code']==1){
                        // 购买成功之后 广播给所有页面让他们更新
                        push_message('stock_poll_number_handle',json_encode($update_stock_poll_data));
                        return array('return_code'=>1,'return_msg'=>'购买成功');
                    }else{
                        return array('return_code'=>2,'return_msg'=>$add_user_stock_log_res['return_msg']);
                    }

                }else{
                    return array('return_code'=>2,'return_msg'=>'购买失败');
                }
            }else{

                $add_user_stock_data=array(
                    's_stock_no'=>$stock_no,
                    'stock_number'=>$deal_number,
                    'user_info_id'=>$user_id
                );

                $add_user_stock_res=$user_stock_model->data($add_user_stock_data)->add();

                
                if($add_user_stock_res){
                    // 购买成功之后 广播给所有页面让他们更新
                    push_message('stock_poll_number_handle',json_encode($update_stock_poll_data));

                    return array('return_code'=>1,'return_msg'=>'购买成功');
                }else{
                    return array('return_code'=>2,'return_msg'=>'购买失败');
                }
                
            }


        }else{
            return array('return_code'=>2,'return_msg'=>'参数错误');
            // exit('参数错误');
        }
    }


    // 减少用户持股数
    function reduce_user_stock($user_id,$stock_no,$deal_number,$change_type){
        if($user_id && $stock_no && $deal_number){
            
            $user_stock_model=M('user_stock');

            $user_stock_select_data=$user_stock_model->where(array('user_info_id'=>$user_id,'s_stock_no'=>$stock_no))->select();
            if($user_stock_select_data[0]){
                $user_stock_id=$user_stock_select_data[0]['id'];
                
                if($user_stock_select_data[0]['stock_number']<$deal_number){
                    return array('return_code'=>2,'return_msg'=>'卖出不能超过持有数');
                }else{
                    $neetset_stock_number=$user_stock_select_data[0]['stock_number']-$deal_number;
                }

                
                $update_user_stock_data=array(
                    'id'=>$user_stock_id,
                    'user_info_id'=>$user_id,
                    's_stock_no'=>$stock_no,
                    'stock_number'=>$neetset_stock_number
                );

                $update_user_stock_res=$user_stock_model->save($update_user_stock_data);

                if($update_user_stock_res){

                    // 增加用户股票日志
                    $add_user_stock_log_data=array(
                        'user_info_id'=>$user_id,
                        's_stock_no'=>$stock_no,
                        'stock_number'=>$deal_number,
                        'change_type'=>$change_type,
                        'in_out_flag'=>0
                    );
                    $add_user_stock_log_res=add_user_stock_log($add_user_stock_log_data);

                    if($add_user_stock_log_res['return_code']==1){
                        // 购买成功之后 广播给所有页面让他们更新
                        push_message('stock_poll_number_handle',json_encode($update_stock_poll_data));
                        return array('return_code'=>1,'return_msg'=>'购买成功');
                    }else{
                        return array('return_code'=>2,'return_msg'=>$add_user_stock_log_res['return_msg']);
                    }

                    // // 购买成功之后 广播给所有页面让他们更新
                    // push_message('stock_poll_number_handle',json_encode($update_stock_poll_data));
                    // return array('return_code'=>1,'return_msg'=>'购买成功');


                }else{
                    return array('return_code'=>2,'return_msg'=>'购买失败');
                }
            }else{
                return array('return_code'=>2,'return_msg'=>'获取用户数据失败');
            }


        }else{
            exit('参数错误');
        }
    }


    // 增加用户资金数
    function add_user_blance($user_id,$change_blance,$change_type){
        if($user_id && $change_blance){
            $user_account_model=M('user_account');
            // 更新用户资金表
            $user_account_select_data=$user_account_model->where(array('user_info_id'=>$user_id))->select();
            $user_account_blance=$user_account_select_data[0]['user_account_blance'];
            $user_account_id=$user_account_select_data[0]['id'];


            // 计算需要更新的资金
            $needset_blance=$user_account_blance+$change_blance;

            // 更新用户资金                           
            $update_user_account_data=array(
                'id'=>$user_account_id,
                'user_account_blance'=>$needset_blance
            );

            $update_user_account_res=$user_account_model->save($update_user_account_data);


            if($update_user_account_res){

                // 添加用户资金日志
                $user_blance_log_add_data=array(
                    'user_info_id'=>$user_id,
                    'change_balance'=>$change_blance,
                    'change_type'=>$change_type, //交易过程
                    'in_out_flag'=>1
                );

                $add_user_balance_log_res=add_user_balance_log($user_blance_log_add_data);


                if($add_user_balance_log_res['return_code']==1){
                    return array('return_code'=>1,'return_msg'=>'修改资金成功');
                }else{
                    return array('return_code'=>2,'return_msg'=>$add_user_balance_log_res['return_msg']);
                }
            }else{
                return array('return_code'=>2,'return_msg'=>'修改资金失败');
            }

        }else{
            return array('return_code'=>2,'return_msg'=>'参数错误');
        }
        
    }


    // 减少用户资金数
    function reduce_user_blance($user_id,$change_blance,$change_type){

        if($user_id && $change_blance){

            $user_account_model=M('user_account');
            // 更新用户资金表
            $user_account_select_data=$user_account_model->where(array('user_info_id'=>$user_id))->select();
            $user_account_blance=$user_account_select_data[0]['user_account_blance'];
            $user_account_id=$user_account_select_data[0]['id'];

            if($user_account_blance>=$change_blance){

                // 计算需要更新的资金
                $needset_blance=$user_account_blance-$change_blance;

                // 更新用户资金                           
                $update_user_account_data=array(
                    'id'=>$user_account_id,
                    'user_account_blance'=>$needset_blance
                );

                $update_user_account_res=$user_account_model->save($update_user_account_data);


                if($update_user_account_res){
                    // 添加用户资金日志
                    $user_blance_log_add_data=array(
                        'user_info_id'=>$user_id,
                        'change_balance'=>$change_blance,
                        'change_type'=>$change_type, //交易过程
                        'in_out_flag'=>0
                    );

                    $add_user_balance_log_res=add_user_balance_log($user_blance_log_add_data);
                    
                    
                    if($add_user_balance_log_res['return_code']==1){
                        return array('return_code'=>1,'return_msg'=>'修改资金成功');
                    }else{
                        return array('return_code'=>2,'return_msg'=>$add_user_balance_log_res['return_msg']);
                    }

                }else{
                    return array('return_code'=>2,'return_msg'=>'修改资金失败');
                }

            }else{
                return array('return_code'=>2,'return_msg'=>'您的资金不足啦');
            }

        }else{
            return array('return_code'=>2,'return_msg'=>'参数错误');
        }
    

    }

    // 更新股票最新价格表
    function update_stock_new_price($stock_no,$stock_new_price){
        if($stock_no && $stock_new_price){

            $stock_new_price_model=M('stock_new_price');

            $select_stock_res_data=$stock_new_price_model->where(array('s_stock_no'=>$stock_no))->select();

            if($select_stock_res_data){
                $update_stock_new_price_data=array(
                    's_stock_no'=>$stock_no,
                    's_new_price'=>$stock_new_price
                );
    
                $update_stock_new_price_res=$stock_new_price_model->where(array('s_stock_no'=>$stock_no))->save($update_stock_new_price_data);
    
                if($update_stock_new_price_res){
                    trace('更新股票最新价成功');
                    // 向浏览器推送消息
                    // push_message('change_new_price',json_encode($update_stock_new_price_data));
                    return array('return_code'=>1,'return_msg'=>'更新成功');
                }else{
                    return array('return_code'=>2,'return_msg'=>'更新失败');
                }
            }else{
                // 不存在 则创建
                $add_stock_new_price_data=array(
                    's_stock_no'=>$stock_no,
                    's_new_price'=>$stock_new_price
                );
                
                $add_stock_new_price_res=$stock_new_price_model->data($add_stock_new_price_data)->add();

                if($add_stock_new_price_res){
                    trace('更新股票最新价成功');
                    // 向浏览器推送消息
                    // push_message('change_new_price',json_encode($add_stock_new_price_data));
                    return array('return_code'=>1,'return_msg'=>'更新成功');
                }else{
                    return array('return_code'=>2,'return_msg'=>'更新失败');
                }


            }


            


        }else{
            return array('return_code'=>2,'return_msg'=>'参数错误');
        }
    
    }

    // 更新股票交易日志
    function update_deal_recorde_log($stock_no,$stock_number,$stock_price){
        $deal_recorde_log_model=M('deal_recorde_log');
        if($stock_no && $stock_number && $stock_price){

            $add_deal_recorde_log_data=array(
                's_stock_no'=>$stock_no,
                's_stock_price'=>$stock_price,
                's_deal_number'=>$stock_number
            );

            $add_deal_recorde_log_model_res=$deal_recorde_log_model->data($add_deal_recorde_log_data)->add();

            if($add_deal_recorde_log_model_res){
                return array('return_code'=>1,'return_msg'=>'添加成功');
            }else{
                return array('return_code'=>2,'return_msg'=>'添加失败');
            }

        }else{
            return array('return_code'=>2,'return_msg'=>'参数错误');
        }

    }








    
    // 交易池结算方程
    function sell_stock($buy_value){
        $deal_recorde_log_model=M('deal_recorde_log');

        // 获取手续费
        $config_res_data=M('config')->select();
        $change_money=$config_res_data[0]['change_money'];



        $stock_deal_recorde_model=M('stock_deal_recorde');

        $sell_map=array(
            'show_flag'=>1,
            'deal_flag'=>1,
            's_stock_no'=>$buy_value['s_stock_no'],
            's_deal_state'=>0,
            's_deal_type'=>0,
            'user_info_id'=>array('neq',$buy_value['user_info_id']),
            's_stock_price'=>$buy_value['s_stock_price']
        );


        $sell_stock_recorde_res_data=$stock_deal_recorde_model->where($sell_map)->order('id desc')->select();
        
            foreach($sell_stock_recorde_res_data as $sell_value){

                if($buy_value['s_deal_number']==$sell_value['s_deal_number']){

                    trace($buy_value['s_deal_number'] .'------'.$sell_value['s_deal_number'] . '---' .$sell_value['user_info_id'] );
                    // 更新买股票的股票表
                    $buy_user_id=$buy_value['user_info_id'];
                    $buy_stock_no=$buy_value['s_stock_no'];
                    $buy_deal_number=$buy_value['s_deal_number'];
                    $buy_deal_price=$buy_value['s_stock_price'];

                    $add_user_stock_res=add_user_stock($buy_user_id,$buy_stock_no,$buy_deal_number,1);
                    
                    // 更新卖股票的资金表
                    if($add_user_stock_res['return_code']==1){
                        
                        $sell_user_id=$sell_value['user_info_id'];

                        $change_blance=$sell_value['s_stock_price']*$sell_value['s_deal_number']*(1-$change_money);

                        $add_user_blance_res=add_user_blance($sell_user_id,$change_blance,1);

                        if($add_user_blance_res['return_code']==1){
                            trace('购买成功');    

                            // 更新股票价格
                            update_stock_new_price($buy_stock_no,$buy_deal_price);
                            // 添加股票日志
                            update_deal_recorde_log($buy_stock_no,$buy_deal_number,$buy_deal_price);


                            // 修改 交易池 标识
                            $buy_value['s_deal_state']=1;
                            $sell_value['s_deal_state']=1;
                            $stock_deal_recorde_model->save($buy_value);
                            $stock_deal_recorde_model->save($sell_value);
                            
                            return array('return_code'=>1,'return_msg'=>'购买成功');
                        }else{
                            return array('return_code'=>2,'return_msg'=>$add_user_blance_res['return_msg']);
                        }

                    }else{
                        return array('return_code'=>2,'return_msg'=>$add_user_blance_res['return_msg']);
                    }

                }


                if($buy_value['s_deal_number']<$sell_value['s_deal_number']){
                    trace($buy_value['s_deal_number'] .'------'.$sell_value['s_deal_number'] . '---' .$sell_value['user_info_id'] );
                    
                    // 更新买股票的股票表
                    $buy_user_id=$buy_value['user_info_id'];
                    $buy_stock_no=$buy_value['s_stock_no'];
                    $buy_deal_number=$buy_value['s_deal_number'];
                    $buy_deal_price=$buy_value['s_stock_price'];

                    $add_user_stock_res=add_user_stock($buy_user_id,$buy_stock_no,$buy_deal_number,1);

                    if($add_user_stock_res['return_code']==1){
                        // 添加用户资金
                        $sell_user_id=$sell_value['user_info_id'];
                        
                        $change_blance=$buy_value['s_stock_price']*$buy_value['s_deal_number']*(1-$change_money);

                        $add_user_blance_res=add_user_blance($sell_user_id,$change_blance,1);

                        if($add_user_blance_res['return_code']==1){

                            // 更新股票价格
                            update_stock_new_price($buy_stock_no,$buy_deal_price);
                            // 添加股票日志
                            update_deal_recorde_log($buy_stock_no,$buy_deal_number,$buy_deal_price);

                            // 修改股票交易池信息
                            $buy_value['s_deal_state']=1;
                            $stock_deal_recorde_model->save($buy_value);

                            $change_sell_recorde_number=$sell_value['s_deal_number']-$buy_value['s_deal_number'];
                            $sell_value['s_deal_number']=$change_sell_recorde_number;

                            $stock_deal_recorde_model->save($sell_value);

                            trace('购买成功');
                            return array('return_code'=>1,'return_msg'=>'购买成功');
                        }else{
                            return array('return_code'=>2,'return_msg'=>$add_user_blance_res['return_msg']);
                        }

                    }else{
                        return array('return_code'=>2,'return_msg'=>$add_user_stock_res['return_msg']);
                    }

                }


                if($buy_value['s_deal_number']>$sell_value['s_deal_number']){
                    trace($buy_value['s_deal_number'] .'------'.$sell_value['s_deal_number'] . '---' .$sell_value['user_info_id'] );

                    // 更新买股票的股票表
                    $buy_user_id=$buy_value['user_info_id'];
                    $buy_stock_no=$sell_value['s_stock_no'];
                    $buy_deal_number=$sell_value['s_deal_number'];
                    $buy_deal_price=$buy_value['s_stock_price'];

                    $add_user_stock_res=add_user_stock($buy_user_id,$buy_stock_no,$buy_deal_number,1);


                    if($add_user_stock_res['return_code']==1){
                        // 添加用户资金
                        $sell_user_id=$sell_value['user_info_id'];
                        
                        $change_blance=$sell_value['s_stock_price']*$sell_value['s_deal_number']*(1-$change_money);

                        $add_user_blance_res=add_user_blance($sell_user_id,$change_blance,1);


                        if($add_user_blance_res['return_code']==1){

                            // 更新股票价格
                            update_stock_new_price($buy_stock_no,$buy_deal_price);
                            // 添加股票日志
                            update_deal_recorde_log($buy_stock_no,$buy_deal_number,$buy_deal_price);

                            // 修改股票交易池信息
                            $change_buy_recorde_number=$buy_value['s_deal_number']-$sell_value['s_deal_number'];
                            $buy_value['s_deal_number']=$change_buy_recorde_number;
                            $stock_deal_recorde_model->save($buy_value);

                            $sell_value['s_deal_state']=1;
                            $stock_deal_recorde_model->save($sell_value);


                            trace('购买成功');

                            return array('return_code'=>1,'return_msg'=>'购买成功');

                        }else{
                            trace(array('return_code'=>2,'return_msg'=>$add_user_blance_res['return_msg']));
                            return array('return_code'=>2,'return_msg'=>$add_user_blance_res['return_msg']);
                        }

                    }else{
                        trace(array('return_code'=>2,'return_msg'=>$add_user_stock_res['return_msg']));
                        
                        return array('return_code'=>2,'return_msg'=>$add_user_stock_res['return_msg']);
                    }

                }



            }

    }



    // 判断是否在这段时间内 用于生成k线图数据
    function get_curr_time_section($start_time,$end_time){  
        $checkDayStr = date('Y-m-d ',time());  
        $timeBegin1 = strtotime($checkDayStr.$start_time.":00");  
        $timeEnd1 = strtotime($checkDayStr.$end_time.":00");  
         
        $curr_time = time();  
         
        if($curr_time >= $timeBegin1 && $curr_time <= $timeEnd1)  
        {  
            return true;  
        }  
          
        return false;  
    }


    // 生成预支付二维码
    // 获取二维码的方程
    function request_qr_code($get_qrcode_post_arr_data,$notify_url){

        $url=C('PAY_URL').'/pay/100/prepay';

        $get_qrcode_post_arr_data=$get_qrcode_post_arr_data;
    
        $str1=get_str_form_array($get_qrcode_post_arr_data);


        // 获取access_token 通过session 或者直接通过 表 获取
        $access_token=C('ACCESS_TOKEN');

        $str2=$str1.'&access_token='.$access_token;


        $get_qrcode_post_arr_data['key_sign']=md5($str2);

        $get_qrcode_post_arr_data['order_body']='账户入金';
        $get_qrcode_post_arr_data['notify_url']=$notify_url;


        $data_string=json_encode($get_qrcode_post_arr_data);


        $result=http_post_data($url,$data_string);

        if($result[0]=='200'){
            $result_arr=json_decode($result[1],true);
            if($result_arr['return_code']=='01'){
                if($result_arr['terminal_trace']==$get_qrcode_post_arr_data['terminal_trace']){
                    return $result_arr;
                }
            }else{
                return false;
            }

        }else{
            return false;
        }
    }
    
    // 将数组拼接成 字符串
    function get_str_form_array($get_access_token_body){
        $fields_string='';
        foreach ($get_access_token_body as $key => $value) {
            $fields_string .= $key . '=' . $value . '&';
        }
        $stringUrl=rtrim($fields_string, '&');

        return $stringUrl;
    }

?>