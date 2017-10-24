<?php
namespace Home\Model;
use Think\Model\RelationModel;
class UserInfoModel extends RelationModel{

    protected $_link = array(
        'user_account'=>array(
            'mapping_type' => self::HAS_ONE,
            'mapping_name '=> 'user_account',
            'foreign_key'=>'user_info_id',
            )
        );

    

}