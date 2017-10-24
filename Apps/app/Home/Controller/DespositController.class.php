<?php
namespace Home\Controller;
use Think\Controller;
class DespositController extends Controller {
    public function index(){
        if(checkLogin()){
            $this->assign('login_state',true);
            $this->display();    
        }else{
            redirect(__ROOT__ . '/index.php/Home/LoginRegister/login');
        }
    }

    // 入金操作
    public function prepay(){
        $desposit_type=$_POST['desposit_type'];
        $desposit_ammount=$_POST['desposit_ammount'];
        $user_id=get_user_id();
        if($desposit_type && $desposit_ammount){
            
            $saobei_desposit_log_model=M('saobei_desposit_log');

            // 判断是支付宝还是微信
            if($desposit_type==1){
                $desposit_type='010';
            }else{
                $desposit_type='020';
            }

            $type_to_body=array(
                '010'=>'微信入金',
                '020'=>'支付宝入金'
            );



            $amount_fee=$desposit_ammount*100;            

            $desposit_order_no=build_order_no();
           
            // 预支付订单日志记录
            $saobei_desposit_log_add_data=array(
                'user_info_id'=>$user_id,
                'confirm_state'=>0,
                'ammount'=>$desposit_ammount,
                'pay_type'=>$desposit_type,
                'terminal_trace'=>$desposit_order_no,
                'total_fee'=>$amount_fee,
                'order_body'=>$type_to_body[$desposit_type]
            );

            $saobei_desposit_log_add_res=$saobei_desposit_log_model->data($saobei_desposit_log_add_data)->add();

            if($saobei_desposit_log_add_res){

                $get_qrcode_post_arr_data=array(
                    'pay_ver'=>'100',
                    'pay_type'=>$desposit_type,
                    'service_id'=>'011',
                    'merchant_no'=>C('MERCHANT_NO'),
                    'terminal_id'=>C('TERMINAL_ID'),
                    'terminal_trace'=>$desposit_order_no,
                    'terminal_time'=>date("YmdHis"),
                    'total_fee'=>$amount_fee,
                );

                    $notify_url='http://localhost?order_no=' . $desposit_order_no . '&user_id' . $user_id;
                
                    $request_qr_code_res=request_qr_code($get_qrcode_post_arr_data,$notify_url);
                    

                    session('desposit_order_no',$desposit_order_no);
                    session('user_id',$user_id);
                    

                    if($request_qr_code_res){
                    // 更新入金记录表
                    $saobei_desposit_log_update_data=array(
                        'res_result_code'=>$request_qr_code_res['return_code'],
                        'res_result_msg'=>$request_qr_code_res['return_msg'],
                        'res_terminal_trace'=>$request_qr_code_res['terminal_trace'],
                        'res_terminal_time'=>$request_qr_code_res['terminal_time'],
                        'res_out_trade_no'=>$request_qr_code_res['out_trade_no'],
                        'res_qr_code'=>$request_qr_code_res['qr_code']
                    );


                    $saobei_deposit_log_update_res=$saobei_desposit_log_model->where(array('terminal_trace'=>$desposit_order_no))->save($saobei_desposit_log_update_data);


                    if($saobei_deposit_log_update_res){

                        // trace($request_qr_code_res['pay_type']);
                        // trace($request_qr_code_res['qr_code']);
                        // trace($request_qr_code_res['out_trade_no']);

                        $return_array=array(
                            'pay_type'=>$request_qr_code_res['pay_type'],
                            'qr_code'=>$request_qr_code_res['qr_code'],
                            'out_trade_no'=>$request_qr_code_res['out_trade_no']
                        );


                        $this->assign('result',$return_array);
                        $this->display();


                    }
                    
                }



            }


        }else{
            $this->error('参数错误','',1);
        }
    }

    // 入金成功之后的处理
    public function notifyhandle(){
        $desposit_order_no=$_GET['order_no'];
        $user_id=$_GET['user_id'];


        $desposit_order_no=session('desposit_order_no');
        $user_id=session('user_id');


        $saobei_desposit_log_model=M('saobei_desposit_log');


        $saobei_desposit_log_select_data=$saobei_desposit_log_model->where(array('terminal_trace'=>$desposit_order_no,'user_info_id'=>$user_id,'confirm_state'=>0))->select();



        if(!$saobei_desposit_log_select_data){
            $this->ajaxReturn(array("return_code"=>"01","return_msg"=>"success"),'JSON');
            exit('没有订单');
        }

        

        $desposit_order_no_update_data=array(
            'confirm_state'=>1,
            'async_res_result_code'=>'01',
            'async_res_result_msg'=>'支付成功'
        );



        $saobei_desposit_log_update_res=$saobei_desposit_log_model->where(array('terminal_trace'=>$desposit_order_no,'user_info_id'=>$user_id))->save($desposit_order_no_update_data);

        $ammount=$saobei_desposit_log_select_data[0]['ammount'];

        // 更新支付通道
        if($saobei_desposit_log_update_res){
            // 增加用户资金
            $add_user_blance_res=add_user_blance($user_id,$ammount,4);

            if($add_user_blance_res['return_code']==1){
                trace('入金成功');        
            }else{
                trace('入金失败');
            }
            $this->ajaxReturn(array("return_code"=>"01","return_msg"=>"success"),'JSON');
        }else{
            $this->ajaxReturn(array("return_code"=>"01","return_msg"=>"success"),'JSON');
        }

    }

}