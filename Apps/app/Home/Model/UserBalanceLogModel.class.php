<?php
namespace Home\Model;
use Think\Model\RelationModel;
class UserBalanceLogModel extends RelationModel{

    protected $_link = array(
        'user_info'=>array(
            'mapping_type' => self::BELONGS_TO,
            'mapping_name '=> 'user_info',
            'foreign_key'=>'user_info_id',
        )
        );

        
}