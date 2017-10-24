<?php
namespace Home\Controller;
use Think\Controller;
class StockPollController extends Controller {
    public function index(){
        if(checkLogin()){

            $map=array('show_flag'=>1);
            $Data = M('stock_poll'); // 实例化Data数据对象  date 是你的表名
            $count = $Data->where($map)->count();// 查询满足要求的总记录数 $map表示查询条件
            $Page = new \Think\Page($count,9);// 实例化分页类 传入总记录数
            $show = $Page->show();// 分页显示输出

            // 进行分页数据查询
            $list = $Data->where($map)->order('id')->limit($Page->firstRow.','.$Page->listRows)->select(); // $Page->firstRow 起始条数 $Page->listRows 获取多少条
            $this->assign('list',$list);// 赋值数据集
            $this->assign('page',$show);// 赋值分页输出

            $this->assign('login_state',true);
            $this->display();
        }else{
            redirect(__ROOT__ . '/index.php/Home/LoginRegister/login');
        }
    }
    

    // 购买股票池中的股票

    public function buystockpoll(){
        $stock_no=$_POST['stock_no'];
        $deal_number=$_POST['deal_number'];
        
        $user_id=get_user_id();



        if($stock_no && $deal_number){

            $user_info_model=M('user_info');
            $stock_poll_model=M('stock_poll');
            $stock_deal_recorde_model=M('stock_deal_recorde');

            $stock_poll_select_data=$stock_poll_model->where(array('s_stock_no'=>$stock_no))->select();


            if($stock_poll_select_data[0]){
                $stock_poll_id=$stock_poll_select_data[0]['id'];
                $s_stock_number=$stock_poll_select_data[0]['s_stock_number'];
                $s_stock_price=$stock_poll_select_data[0]['s_stock_price'];
                

                // 查询用户是否通过审核 不通过审核直接不让交易
                $user_info_select_res_data=$user_info_model->where(array('id'=>$user_id,'confirm_flag'=>1))->select();

                if(!$user_info_select_res_data[0]){
                    $this->error('您未通过审核，请联系管理员!','',1);
                    exit('未通过后台审核');
                }



                // 计算需要减去的资金
                $reduce_blance=$deal_number*$s_stock_price;

                    // 更新股票池
                    if($s_stock_number>=$deal_number){

                        $needset_stock_number=$s_stock_number-$deal_number;
    
                        $update_stock_poll_data=array(
                            'id'=>$stock_poll_id,
                            's_stock_no'=>$stock_no,
                            's_stock_number'=>$needset_stock_number
                        );
                        $update_stock_poll_res=$stock_poll_model->save($update_stock_poll_data);
    
                        if($update_stock_poll_res){

                            // 减少用户资金
                            $reduce_user_blance_res=reduce_user_blance($user_id,$reduce_blance,1);

                            if($reduce_user_blance_res['return_code']==1){
                                // 增加用户持股数
                                $add_user_stock_res=add_user_stock($user_id,$stock_no,$deal_number,1);
                                if($add_user_stock_res['return_code']==1){
                                    // 增加用户订单
                                    // 生成随机订单号
                                    $deal_order_no=build_order_no();
                                    $add_stock_deal_recode_data=array(
                                        's_deal_no'=>$deal_order_no,
                                        'user_info_id'=>$user_id,
                                        's_stock_no'=>$stock_no,
                                        's_stock_price'=>$s_stock_price,
                                        's_deal_number'=>$deal_number,
                                        's_place_number'=>$deal_number,
                                        's_deal_type'=>1,//1 买入
                                        's_deal_state'=>1
                                    );
                                    $stock_deal_recorde_add_res=$stock_deal_recorde_model->data($add_stock_deal_recode_data)->add();
                                    if($stock_deal_recorde_add_res){
                                        $this->success($add_user_stock_res['return_msg'],'',1);
                                    }else{
                                        $this->error($add_user_stock_res['return_msg'],'',1);
                                    }
                                }else{
                                    $this->error($reduce_user_blance_res['return_msg'],'',1);    
                                }

                            }else{
                                $this->error($reduce_user_blance_res['return_msg'],'',1);
                            }

                        }else{
                            $this->error('更新表失败','',1);
                        }
    
                    }else{
                        $this->error('股票池没那么多啦','',1);
                    }
            }else{
                $this->error('没有该股票','',1);
            }

        }else{
            $this->error('参数错误','',1);
        }

    }


}