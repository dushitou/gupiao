<?php
namespace Home\Controller;
use Think\Controller;
class SysController extends Controller {
    public function index(){
        if(checkLogin()){

            $config_res_data=M('config')->select();
            $change_money=$config_res_data[0]['change_money']*100;

            $this->assign('config',$config_res_data[0]);
            $this->assign('change_money',$change_money);
            
            $this->display();


        }else{
            redirect(__ROOT__ . '/admin.php/Home/Login/login');
        }
    }


    // 修改手续费
    public function changemoney(){

        $config_model=M('config');
        $change_money=$_POST['change_money'];

        if($change_money || $change_money=0){
            
            
            $need_set_change_money=floatval($change_money/100);
            

            $update_data=array(
                'id'=>1,
                'change_money'=>$need_set_change_money
            );


            $config_update_res=$config_model->save($update_data);

            if($config_update_res){
                $this->success('修改成功','',1);
            }else{
                $this->error('修改失败','',1);
            }


        }else{
            $this->error('参数错误','',1);            
        }
    }


    // 修改交易时间
    public function changetime(){
        $open_time=$_POST['open_time'];
        $close_time=$_POST['close_time'];

        $config_model=M('config');
        
        if($open_time && $close_time){
            $update_data=array(
                'id'=>1,
                'open_time'=>$open_time,
                'close_time'=>$close_time
            );

            $config_update_res=$config_model->save($update_data);

            if($config_update_res){
                $this->success('修改成功','',1);
            }else{
                $this->error('修改失败','',1);    
            }
        }else{
            $this->error('参数错误','',1);
        }

    }


}