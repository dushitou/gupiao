<?php
namespace Home\Controller;
use Think\Controller;
class StockDealController extends Controller {
    public function index(){
        if(checkLogin()){
            $stock_no=$_GET['stockno'];


            $stock_poll_model=D('StockPoll');
            $user_stock_model=D('UserStock');
            


            // 获取股票的信息
            if($stock_no){
                
                $user_stock_data=$user_stock_model->relation(true)->where(array('user_info_id'=>session('user_id'),'s_stock_no'=>$stock_no))->select();

                $stock_poll_data=$stock_poll_model->relation(true)->where(array('s_stock_no'=>$stock_no))->select();

                trace($user_stock_data);

                $this->assign('stock_info',$user_stock_data[0]);
                $this->assign('user_stock',$user_stock_data[0]);

                $this->assign('login_state',true);
                $this->display();



            }else{
                $this->error('参数错误','',1);
            }
            
        }else{
            redirect(__ROOT__ . '/index.php/Home/LoginRegister/login');
        }
    }
    

    // 买股票处理
    public function buystockhandle(){
        $deal_number=$_POST['deal_number'];
        $stock_no=$_POST['stock_no'];
        $deal_price=$_POST['deal_price'];

        $user_id=get_user_id();

        $stock_deal_recorde_model=M('stock_deal_recorde');

        if($deal_number && $stock_no && $deal_price){
            
            $change_blance=$deal_price*$deal_number;


            $reduce_user_blance_res=reduce_user_blance($user_id,$change_blance,3);
            

            if($reduce_user_blance_res['return_code']==1){
                // 生成随机订单号
                $deal_order_no=build_order_no();
                $add_stock_deal_recode_data=array(
                    's_deal_no'=>$deal_order_no,
                    'user_info_id'=>$user_id,
                    's_stock_no'=>$stock_no,
                    's_stock_price'=>$deal_price,
                    's_deal_number'=>$deal_number,
                    's_place_number'=>$deal_number,
                    's_deal_type'=>1,//1 买入
                );
                $stock_deal_recorde_add_res=$stock_deal_recorde_model->data($add_stock_deal_recode_data)->add();
                if($stock_deal_recorde_add_res){
                    $this->ajaxReturn(array('return_code'=>1,'return_msg'=>'买入成功'),'JSON');
                }else{
                    $this->ajaxReturn(array('return_code'=>2,'return_msg'=>'买入失败'),'JSON');
                }


            }else{
                $this->ajaxReturn(array('return_code'=>2,'return_msg'=>$reduce_user_blance_res['return_msg']),'JSON');
            }


        }else{
            $this->ajaxReturn(array('return_code'=>2,'return_msg'=>'参数错误'),'JSON');
        }
    
    }

    // 卖股票处理
    public function sellstockhandle(){
        $deal_number=$_POST['deal_number'];
        $stock_no=$_POST['stock_no'];
        $deal_price=$_POST['deal_price'];

        $user_id=get_user_id();

        $stock_deal_recorde_model=M('stock_deal_recorde');
        


        if($deal_number && $stock_no && $deal_price){
            // 生成随机订单号
            $deal_order_no=build_order_no();

            $add_stock_deal_recode_data=array(
                's_deal_no'=>$deal_order_no,
                'user_info_id'=>$user_id,
                's_stock_no'=>$stock_no,
                's_stock_price'=>$deal_price,
                's_deal_number'=>$deal_number,
                's_place_number'=>$deal_number,
                's_deal_type'=>0,//0 卖出
            );

            // 减少用户持有数
            $reduce_user_stock_res=reduce_user_stock($user_id,$stock_no,$deal_number,3);

            if($reduce_user_stock_res['return_code']==1){
                $stock_deal_recorde_add_res=$stock_deal_recorde_model->data($add_stock_deal_recode_data)->add();
                
                if($stock_deal_recorde_add_res){
                    $this->ajaxReturn(array('return_code'=>1,'return_msg'=>'卖出成功'),'JSON');
                }else{
                    $this->ajaxReturn(array('return_code'=>1,'return_msg'=>'卖出失败'),'JSON');
                }

            }else{
                $this->ajaxReturn(array('return_code'=>2,'return_msg'=>$reduce_user_stock_res['return_msg']),'JSON');
            }

            
        }else{
            $this->ajaxReturn(array('return_code'=>2,'return_msg'=>'参数错误'),'JSON');
        }

    }


}