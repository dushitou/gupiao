<?php
namespace Home\Model;
use Think\Model\RelationModel;
class StockPollModel extends RelationModel{

    protected $_link = array(
        'stock_new_price'=>array(
            'mapping_type' => self::HAS_ONE,
            'mapping_name '=> 'stock_new_price',
            'foreign_key'=>'s_stock_no',
        )
        
        );

        
}