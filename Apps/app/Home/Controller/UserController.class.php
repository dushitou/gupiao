<?php
namespace Home\Controller;
use Think\Controller;
class UserController extends Controller {
    
    public function index(){
        if(checkLogin()){
            $user_info_model=D('userInfo');
            $user_stock_model=D('userStock');
            


            $user_info_data=$user_info_model->relation(true)->where(array('id'=>session('user_id')))->select();

            $user_stock_data=$user_stock_model->relation(true)->where(array('user_info_id'=>session('user_id')))->select();
            

            // trace($user_stock_data);
            

            $this->assign('user_info',$user_info_data[0]);
                        
            $this->assign('user_stock',$user_stock_data);




            $this->assign('login_state',true);
            $this->display();    
        }else{
            redirect(__ROOT__ . '/index.php/Home/LoginRegister/login');
        }
    }

    // 展示修改用户信息的页面
    public function changeinfo(){
        if(checkLogin()){
            $user_info_model=M('userInfo');
            $user_info_data=$user_info_model->where(array('id'=>session('user_id')))->select();

            $this->assign('user_info',$user_info_data[0]);
            $this->assign('login_state',true);
            trace($user_info_data);
            $this->display();    
        }else{
            redirect(__ROOT__ . '/index.php/Home/LoginRegister/login');
        }

    }

    // 修改用户昵称的页面
    public function changenamehandle(){
        $user_name=htmlspecialchars($_POST['user_name']);

        $user_info_model=M('user_info');

        $user_id=get_user_id();


        if($user_name){
            $update_data=array(
                'id'=>$user_id,
                'user_name'=>$user_name
            );

            $user_info_update_res=$user_info_model->save($update_data);

            if($user_info_update_res){
                $this->success('修改成功','',1);
            }else{
                $this->error('修改失败','',1);
            }

        }else{
            $this->error('用户名不能为空','',1);
        }


    }











    



}