<?php
namespace Home\Controller;
use Think\Controller;
class StockController extends Controller {

    public function index(){
        if(checkLogin()){

            // $map=array('show_flag'=>1);
            $Data = M('stock_poll'); // 实例化Data数据对象  date 是你的表名
            $count = $Data->count();// 查询满足要求的总记录数 $map表示查询条件
            $Page = new \Think\Page($count,9);// 实例化分页类 传入总记录数
            $show = $Page->show();// 分页显示输出

            // 进行分页数据查询
            $list = $Data->order('id')->limit($Page->firstRow.','.$Page->listRows)->select(); // $Page->firstRow 起始条数 $Page->listRows 获取多少条
            $this->assign('list',$list);// 赋值数据集
            $this->assign('page',$show);// 赋值分页输出
            $this->display(); // 输出模板
 
        }else{
            redirect(__ROOT__ . '/admin.php/Home/User/login');
        }
    }

    // 添加股票操作
    public function addstock(){
        $stock_no=$_POST['stock_no'];
        $stock_name=$_POST['stock_name'];
        $stock_number=$_POST['stock_number'];
        $stock_price=$_POST['stock_price'];

        $stock_poll_model=M('stock_poll');


        if($stock_no && $stock_name && $stock_number){
            $stock_poll_select_res_data=$stock_poll_model->where(array('s_stock_no'=>$stock_no))->select();

            if($stock_poll_select_res_data[0]){
                $this->error('股票编码必须唯一','',1);    
                return ;
            }
            

            $stock_add_data=array(
                's_stock_no'=>$stock_no,
                's_stock_name'=>$stock_name,
                's_stock_number'=>$stock_number,
                's_total_number'=>$stock_number,
                's_stock_price'=>$stock_price
            );




            $stock_add_res=$stock_poll_model->data($stock_add_data)->add();


            if($stock_add_res){
                $this->success('添加成功','',1);
            }else{
                $this->error('添加失败','',1);        
            }

        }else{
            $this->error('数据格式错误','',1);
        }



    }

    // 修改股票池信息
    public function changestock(){
        
        $stock_name=$_POST['stock_name'];
        $change_stock_number_type=$_POST['change_stock_number_type'];
        $change_stock_number=$_POST['change_stock_number'];
        $stock_id=$_POST['stock_id'];
        $stock_price=$_POST['stock_price'];

        
        $stock_poll_model=M('stock_poll');

        if($stock_name && $change_stock_number_type && ($change_stock_number || $change_stock_number==0) && $stock_id){

            $stock_poll_select_res_data=$stock_poll_model->where(array('id'=>$stock_id))->select();

            if($stock_poll_select_res_data){
                $stock_number=$stock_poll_select_res_data[0]['s_stock_number'];
                $s_total_number=$stock_poll_select_res_data[0]['s_total_number'];
            }else{
                $this->error('出错啦，请重试','',1);
                return ;        
            }

            // 添加减少手数操作
            if($change_stock_number_type=='reduce' && $stock_number<$change_stock_number){
                $this->error('减少的手数不能小于现有数','',1);
                return ;
            }

            if($change_stock_number_type=='reduce'){
                $need_change_stock_number=$stock_number-$change_stock_number;
                $need_change_total_number=$s_total_number-$change_stock_number;
            }

            if($change_stock_number_type=='add'){
                $need_change_stock_number=$stock_number+$change_stock_number;  
                $need_change_total_number=$s_total_number+$change_stock_number;              
            }

            
            $update_stock_poll_data=array(
                'id'=>$stock_id,
                's_stock_number'=>$need_change_stock_number,
                's_stock_name'=>$stock_name,
                's_total_number'=>$need_change_total_number,
                's_stock_price'=>$stock_price
            );


            $update_stock_poll_res=$stock_poll_model->save($update_stock_poll_data);


            if($update_stock_poll_res){
                $this->success('更新成功','',1);
            }else{
                $this->error('更新失败,请重试','',1);        
            }

        }else{
            $this->error('数据格式错误','',1);
        }

    }

    // 修改股票
    public function modifystock(){
        $flag=$_GET['flag'];
        $stock_id=$_GET['id'];

        $stock_poll_model=M('stock_poll');

        if($flag==0 || $flag==1){            
            $modify_stock_data=array(
                'id'=>$stock_id,
                'show_flag'=>$flag
            );
            
             $modify_stock_res=$stock_poll_model->save($modify_stock_data);

             if($modify_stock_res){
                $this->success('修改成功','',1);    
            }else{
                $this->error('修改失败','',1);            
            }

        }else{
            $this->error('参数错误','',1);
        }


    }





}