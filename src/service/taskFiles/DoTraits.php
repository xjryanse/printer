<?php

namespace xjryanse\printer\service\taskFiles;

use xjryanse\logic\Arrays;
use Exception;
use think\facade\Request;
/**
 * 操作说明
 */
trait DoTraits{
    /**
     * 20231215:上报已打印状态
     */
    public function doPrintUpl($param = []){
        $printerId = Arrays::value($param, 'printer_id');
        if(!$printerId){
            throw new Exception('打印机编号必须');
        }
        // 标记为已打印
        $data['is_print']   = 1;
        $data['printer_id'] = $printerId;
        $data['printer_ip'] = Request::ip();
        $data['print_time'] = date('Y-m-d H:i:s');
        
        return $this->updateRam($data);
    }
    
}
