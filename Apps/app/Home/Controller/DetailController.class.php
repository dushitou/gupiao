<?php
namespace Home\Controller;
use Think\Controller;
class DetailController extends Controller {

    // 股票交易明细
    public function stock(){
        if(checkLogin()){
            
            // 获取交易的所有股票日志
            $map=array('user_info_id'=>session('user_id'));
            $Data = D('UserStockDealLog'); // 实例化Data数据对象  date 是你的表名
            $count = $Data->where($map)->count();// 查询满足要求的总记录数 $map表示查询条件
            $Page = new \Think\Page($count,9);// 实例化分页类 传入总记录数
            $show = $Page->show();// 分页显示输出

            // 进行分页数据查询
            $list = $Data->relation(true)->where($map)->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select(); // $Page->firstRow 起始条数 $Page->listRows 获取多少条
            $this->assign('list',$list);// 赋值数据集
            $this->assign('page',$show);// 赋值分页输出

            trace($list);

            $this->assign('login_state',true);
            $this->display();

        }else{
            redirect(__ROOT__ . '/index.php/Home/LoginRegister/login');
        }
    }


    public function balance(){
        if(checkLogin()){
            
            // 获取用户资金日志

            $map=array('user_info_id'=>session('user_id'));
            $Data = D('UserBalanceLog'); // 实例化Data数据对象  date 是你的表名
            $count = $Data->where($map)->count();// 查询满足要求的总记录数 $map表示查询条件
            $Page = new \Think\Page($count,9);// 实例化分页类 传入总记录数
            $show = $Page->show();// 分页显示输出

            // 进行分页数据查询
            $list = $Data->relation(true)->where($map)->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select(); // $Page->firstRow 起始条数 $Page->listRows 获取多少条
            $this->assign('list',$list);// 赋值数据集
            $this->assign('page',$show);// 赋值分页输出

            trace($list);

            $this->assign('login_state',true);
            $this->display();

        }else{
            redirect(__ROOT__ . '/index.php/Home/LoginRegister/login');
        }
    }
    

}