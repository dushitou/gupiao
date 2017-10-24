<?php
namespace Home\Controller;
use Think\Controller;
class UserController extends Controller {
    public function index(){
        if(checkLogin()){

            // $map=array('show_flag'=>1);
            $Data = D('UserInfo'); // 实例化Data数据对象  date 是你的表名
            $count = $Data->count();// 查询满足要求的总记录数 $map表示查询条件
            $Page = new \Think\Page($count,9);// 实例化分页类 传入总记录数
            $show = $Page->show();// 分页显示输出

            // 进行分页数据查询
            $list = $Data->relation('user_account')->order('id')->limit($Page->firstRow.','.$Page->listRows)->select(); // $Page->firstRow 起始条数 $Page->listRows 获取多少条
            
            $this->assign('list',$list);// 赋值数据集
            $this->assign('page',$show);// 赋值分页输出
            $this->display(); // 输出模板
           
            
        }else{
            redirect(__ROOT__ . '/admin.php/Home/Login/login');
        }

    }




    public function confirmhandle(){
        $confirm_flag=$_GET['flag'];
        $user_info_id=$_GET['userinfoid'];

        $user_info_model=M('user_info');

        // 通过审核
        if($confirm_flag==1 || $confirm_flag==0){
            $update_data=array(
                'id'=>$user_info_id,
                'confirm_flag'=>$confirm_flag
            );
            $update_user_info_res=$user_info_model->save($update_data);

            if($update_user_info_res){
                $this->success('修改成功','',1);
            }else{
                $this->error('修改失败，请再试一次','',1);                
            }

        // 错误的参数
        }else{
            $this->error('参数错误','',1);
        }

    }

    // 修改用户
    public function modifyuser(){
        $flag=$_GET['flag'];
        $user_id=$_GET['id'];
        $user_info_model=M('user_info');

        if($flag==0 || $flag==1){
            
            $modify_user_data=array(
                'id'=>$user_id,
                'show_flag'=>$flag
            );
            
            $modify_user_res=$user_info_model->save($modify_user_data);

            if($modify_user_res){
                $this->success('修改成功','',1);    
            }else{
                $this->error('修改失败','',1);            
            }

        }else{
            $this->error('参数错误','',1);
        }






    }







}