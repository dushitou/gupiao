<?php
namespace Home\Model;
use Think\Model\RelationModel;
class StockDealRecordeModel extends RelationModel{

    protected $_link = array(
        'stock_poll'=>array(
            'mapping_type' => self::BELONGS_TO,
            'mapping_name '=> 'stock_poll',
            'foreign_key'=>'s_stock_no',
        ),
        'stock_new_price'=>array(
            'mapping_type' => self::BELONGS_TO,
            'mapping_name '=> 'stock_new_price',
            'foreign_key'=>'s_stock_no',
        )
        );

        
}