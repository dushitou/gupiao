<?php
namespace Home\Controller;
use Think\Controller;
class LoginRegisterController extends Controller {
    
    // 登录
    public function login(){
        $this->display();
    }
    
    // 登录处理
    public function loginhandle(){
        ob_end_clean();
        header('content-type:text/html;charset=utf-8');
        
        $verfy=$_POST['verfy'];
        $email=$_POST['email'];
        $password=$_POST['password'];

        $user_info_model=M('user_info');
        if(check_verify($verfy)){
            // 检测登录
            $check_user_res_data=$user_info_model->where(array('user_email'=>$email,'password'=>md5($password)))->select();
            if($check_user_res_data[0]){
                session('user_id',$check_user_res_data[0]['id']);
                cookie('user_email',$check_user_res_data[0]['user_email']);
                $this->success('登录成功','/',1);        
            }else{
                $this->error('没有用户','/index.php/Home/LoginRegister/login',1);        
            }

        }else{
            $this->error('验证码错误','/index.php/Home/LoginRegister/login',1);
        }

    }


    // 注册
    public function register(){
        $this->display();
    }

    // 发送邮箱验证码
    public function send_verfy_to_email(){
        $email=$_POST['email'];
        $verfy=$_POST['verfy'];


        if(check_verify($verfy)){
            $rang_num=rand(100000,999999);
            session('email_verfy',$rang_num);
            $send_content='<h3>感谢您的注册! 您的验证码为: <span style="color:#f5886b;font-weight:bold;">'. $rang_num .'</span></h3>';
            if(send_to_email($email,'感谢您的注册',$send_content)){
                $this->ajaxReturn(array('statu'=>1));
            }

        }else{
            $this->ajaxReturn(array('statu'=>2));
        }
        
        
        
    }





    // 注册处理
    public function registerhandle(){
        $email=$_POST['user_email'];
        $email_verfy=$_POST['email_verfy'];
        $password=$_POST['password'];

        if($email_verfy==session('email_verfy')){
            // 注册操作                        
            $user_info_model=M('user_info');
            $user_account_model=M('user_account');
            // 检测用户是否注册
            $user_select_data=$user_info_model->where(array('user_email'=>$email))->select();
            if($user_select_data){
                $this->error('用户已经注册...','/index.php/Home/LoginRegister/register',1);
                return ;  
            }

            $register_info_data=array(
                'user_email'=>$email,
                'user_password'=>md5($password)
            );

            $user_info_add_res_id=$user_info_model->data($register_info_data)->add();

            if($user_info_add_res_id){

                $register_account_data=array(
                  'user_info_id'=>$user_info_add_res_id,
                  'user_account_blance'=>0
                );

                $user_account_add_res_id=$user_account_model->data($register_account_data)->add();

                if($user_account_add_res_id){
                    $this->success('注册成功','/index.php/Home/LoginRegister/login',1);                    
                }

            }

        }else{
            $this->error('验证码错误...','/index.php/Home/LoginRegister/register',1);
        }
        
    }


    // 登出处理
    public function logouthandle(){
        cookie('user_email',null);
        session('user_id',null);
        $this->success('登出成功','/',1);                            
    }


}