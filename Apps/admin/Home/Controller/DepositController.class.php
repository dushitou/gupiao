<?php
namespace Home\Controller;
use Think\Controller;
class DepositController extends Controller {
    public function index(){
        if(checkLogin()){
            






            $this->show();    
        }else{
            redirect(__ROOT__ . '/admin.php/Home/Login/login');
        }
    }

    
}