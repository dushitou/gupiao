<?php
namespace Home\Controller;
use Think\Controller;
class VerfyController extends Controller {

    public function verfy(){
        $Verify = new \Think\Verify();
        $Verify->entry();
    }

}