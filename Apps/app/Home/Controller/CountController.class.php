<?php
namespace Home\Controller;
use Think\Controller;
class CountController extends Controller {
    // 统计所有订单 符合交易条件完成交易
    public function rocordehandle(){
        $stock_deal_recorde_model=M('stock_deal_recorde');

        $config_model=M('config');

        // 查看是否交易时间内
        $config_model_select_data=$config_model->where('id=1')->select();
        $open_time=$config_model_select_data[0]['open_time'];
        $close_time=$config_model_select_data[0]['close_time'];
        $is_time=get_curr_time_section($open_time,$close_time);

        if($is_time){
            // 查询购买订单
            $buy_map=array(
                'show_flag'=>1,
                'deal_flag'=>1,
                's_deal_state'=>0,
                's_deal_type'=>1
            );

            $buy_stock_recorde_res_data=$stock_deal_recorde_model->where($buy_map)->order('id desc')->select();
            
            foreach($buy_stock_recorde_res_data as $buy_value){
                sell_stock($buy_value);
            }
        }else{            
            exit('已经休市');
        }


        

    }


    // 计算k线图数据
    function setkdata(){
        
        $need_set_k_data=get_curr_time_section('01:00','12:30');
        
        if($need_set_k_data){
            $deal_recorde_log_model=M('deal_recorde_log');
            $stock_poll_model=M('stock_poll');
            $day_k_record_model=M('day_k_recorde');


            $start_time =  date("Y-m-d",strtotime("-1 day"));
            $end_time =date("Y-m-d",time());
            

            // 查询有几个股票

            $stock_poll_select_data=$stock_poll_model->select();

            foreach($stock_poll_select_data as $value){


                $map['s_stock_no']=$value['s_stock_no'];
                $map['create_time'] = array('between',array($start_time,$end_time));

                $day_k_recorde_select_res=$day_k_record_model->where($map)->select();

                if($day_k_recorde_select_res){
                    exit('已经生成过啦');
                }

                // 查出当天最低价格
                $lowest_data=$deal_recorde_log_model->where($map)->order('s_stock_price')->limit(1)->select();
                $lowest_price=$lowest_data[0]['s_stock_price'];
    
                // 查出当天最高价
                $hight_data=$deal_recorde_log_model->where($map)->order('s_stock_price desc')->limit(1)->select();
                $highest_price=$hight_data[0]['s_stock_price'];


                // 查出开盘价
                $open_data=$deal_recorde_log_model->where($map)->order('create_time')->limit(1)->select();
                $open_price=$open_data[0]['s_stock_price'];


                // 查出收盘价
                $close_data=$deal_recorde_log_model->where($map)->order('create_time desc')->limit(1)->select();
                $close_price=$close_data[0]['s_stock_price'];

                // 查出交易量
                $vloume=$deal_recorde_log_model->where($map)->sum('s_deal_number');


                if($lowest_data){
                    
                    $add_day_k_recorde_data=array(
                        'open'=>$open_price,
                        'close'=>$close_price,
                        'lowest'=>$lowest_price,
                        'highest'=>$highest_price,
                        'vloume'=>$vloume,
                        's_stock_no'=>$value['s_stock_no'],
                        'create_time'=>$start_time
                    );

                    $day_k_record_model->data($add_day_k_recorde_data)->add();

                    // 生成k线图json文件
                    $day_k_recorde_select_data=$day_k_record_model->where(array('s_stock_no'=>1))->select();
                    
                    $json_string = json_encode($day_k_recorde_select_data);
                    
                    
                    file_put_contents('./Data/daystock/'. $value['s_stock_no'] .'.json',$json_string);
                    


                }
                

            }

        }else{
            return ;
        }



    }


    

}