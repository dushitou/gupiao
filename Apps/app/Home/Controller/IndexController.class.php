<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index(){
        if(checkLogin()){
            // 获取交易的所有股票列表
            $stock_poll_model=D('stockPoll');

            $stock_poll_select_res_data=$stock_poll_model->relation(true)->select();

            trace($stock_poll_select_res_data);

            $this->assign('stock_poll',$stock_poll_select_res_data);
            $this->assign('login_state',true);
            $this->display();
        }else{
            redirect(__ROOT__ . '/index.php/Home/LoginRegister/login');
        }
    }
    

}