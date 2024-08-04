<?php

namespace xjryanse\printer\service\task;

use xjryanse\logic\Arrays2d;
use xjryanse\logic\Arrays;
use xjryanse\printer\service\PrinterServerConfService;
use xjryanse\logic\Pdf;
use xjryanse\logic\Word;
use xjryanse\logic\File;
use Exception;

/**
 * 分页复用列表
 */
trait CalTraits {
    
    protected function calIsPay() {
        $info = $this->get();
        if ($info && $info['order_prize'] == 0) {
            // 20230806:免费的默认已开通
            return 1;
        }
        $lists = $this->objAttrsList('financeStatementOrder');
        $con[] = ['has_settle', '=', 1];
        $listsSettle = Arrays2d::listFilter($lists, $con);
        $money = Arrays2d::sum($listsSettle, 'need_pay_prize');
        //已付金额大于等于设置金额
        return $money >= $info['order_prize'] ? 1 : 0;
    }

    protected function calPageCount(){
        $info = $this->get();
        $printType = Arrays::value($info, 'print_type');
        // 20231218??
        if(!$printType || $printType == 1){
            return count($info['pic_ids']);
        }
        if($printType == 2){
            $file = $info['file_id'];
            $fileUrl = './'.$file['url'];            
            $ext = File::getExt($fileUrl);
            if(!in_array($ext,['doc','docx','pdf'])){
                throw new Exception('不支持该文件类型，请上传doc,docx,pdf类型文件');
            }
            if($ext == 'pdf'){
                return Pdf::pageCount($fileUrl);
            }
            return Word::pageCount($file['file_path']);
        }
        return 99;
    }
    /**
     * 20231217:算单价
     * @return type
     */
    protected function calUnitPrize(){
        $info = PrinterServerConfService::getByCustomerId();
        
        return $info['unit_prize'];
    }
    
    
    /**
     * 计算任务状态
     */
    public static function calTaskState($info){
        // needPay:待付款；toDownload:待打印；printing:打印中；toGet:待取件；finish:已取件
        if($info['is_get']){
            return 'finish';
        }
        if($info['is_print']){
            return 'toGet';
        }
        if($info['is_download']){
            return 'printing';
        }
        if($info['is_pay']){
            return 'toDownload';
        }
        return 'needPay';
    }
}
