<?php
namespace Home\Controller;
use Think\Controller;
class UserDealController extends Controller {
    public function index(){
        if(checkLogin()){
            

            $map=array('show_flag'=>1,'user_info_id'=>session('user_id'));

            $Data = D('stockDealRecorde'); // 实例化Data数据对象  date 是你的表名

            $count = $Data->where($map)->count();// 查询满足要求的总记录数 $map表示查询条件
            $Page = new \Think\Page($count,9);// 实例化分页类 传入总记录数
            $show = $Page->show();// 分页显示输出


            // 进行分页数据查询
            $list = $Data->relation(true)->where($map)->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select(); // $Page->firstRow 起始条数 $Page->listRows 获取多少条
            $this->assign('list',$list);// 赋值数据集
            $this->assign('page',$show);// 赋值分页输出
    

            $this->assign('login_state',true);
            $this->display();


        }else{
            redirect(__ROOT__ . '/index.php/Home/LoginRegister/login');
        }
    }
    

    // 撤销订单
    public function recalldeal(){
        $user_id=get_user_id();
        $stock_deal_no=$_GET['deal_no'];

        $stock_deal_recorde_model=M('stock_deal_recorde');

        if($stock_deal_no){

            $update_stock_deal_recorde_data=array(
                'deal_flag'=>0
            );

            $update_stock_dealrecorde_res=$stock_deal_recorde_model->where(array('user_info_id'=>$user_id,'s_deal_no'=>$stock_deal_no))->save($update_stock_deal_recorde_data);


            if($update_stock_dealrecorde_res){
                
                $stock_dealrecorde_select_res_data=$stock_deal_recorde_model->where(array('user_info_id'=>$user_id,'s_deal_no'=>$stock_deal_no))->select();
                
                $s_stock_no=$stock_dealrecorde_select_res_data[0]['s_stock_no'];
                $s_deal_number=$stock_dealrecorde_select_res_data[0]['s_deal_number'];
                $s_deal_type=$stock_dealrecorde_select_res_data[0]['s_deal_type'];
                $s_deal_price=$stock_dealrecorde_select_res_data[0]['s_stock_price'];

                // 更新用户股票数 卖出撤销更新股票数
                if($s_deal_type==0){
                    $recall_user_stock_res=add_user_stock($user_id,$s_stock_no,$s_deal_number,2);
                }

                // 买入更新用户资金表
                if($s_deal_type==1){
                    $change_blance=$s_deal_number*$s_deal_price;
                    $recall_user_stock_res=add_user_blance($user_id,$change_blance,2);
                }

                
                if($s_deal_type==1){
                    $this->success('撤销成功','',1);
                    return ;
                }


                if($recall_user_stock_res['return_code']==1){
                    $this->success('撤销成功','',1);
                }else{
                    $this->error($recall_user_stock_res['return_msg'],'',1);
                }
                
            }else{
                $this->error('撤销失败','',1);
            }
            

        }else{
            $this->error('参数错误','',1);
        }


    }



}