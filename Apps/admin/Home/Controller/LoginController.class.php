<?php
    namespace Home\Controller;
    use Think\Controller;
    class LoginController extends Controller {
        // 登录
        public function login(){
            $this->display();
        }
        // 登录操作
        public function loginhandle(){
            $admname=$_POST['admname'];
            $admpassword=$_POST['admpassword'];
            $verfy=$_POST['verfy'];

            $admin_model=M('admin');

            if(check_verify($verfy)){
                $admin_select_res_data=$admin_model->where(array('admin'=>$admname,'password'=>md5($admpassword)))->select();
                if($admin_select_res_data[0]){
                    session('admin_login',true);
                    $this->success('登录成功','/admin.php',1);            
                }else{
                    $this->error('用户名-密码错误','/admin.php/Home/Login/login',1);            
                }
            }else{
                $this->error('验证码错误','/admin.php/Home/Login/login',1);
            }

        }

        // 登出操作
        public function logout(){
            session('admin_login',false);
            $this->success('登出成功','/admin.php',1);                        
        }











    }
?>