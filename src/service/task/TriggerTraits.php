<?php

namespace xjryanse\printer\service\task;

use xjryanse\logic\Arrays;
use xjryanse\logic\DataCheck;
use xjryanse\printer\service\PrinterTaskFilesService;
use Exception;
/**
 * 
 */
trait TriggerTraits{
    /**
     * 钩子-保存前
     */
    public static function extraPreSave(&$data, $uuid) {
        self::stopUse(__METHOD__);
    }

    public static function extraPreUpdate(&$data, $uuid) {
        self::stopUse(__METHOD__);
    }
    
    public function extraPreDelete() {
        self::stopUse(__METHOD__);
    }
    
    /**
     * 钩子-保存前
     */
    public static function ramPreSave(&$data, $uuid) {
        // 临时
        if(!Arrays::value($data, 'print_type')){
            $data['print_type'] = 1;
        }
        
        // 1图片；2文件
        $effects['print_type'] = [1,2];
        DataCheck::effect($data, $effects);
        if(!Arrays::value($data, 'user_id')){
            $data['user_id'] = session(SESSION_USER_ID);
        }
        // print_type：类型1:图片；类型2:文件
        $printType = Arrays::value($data, 'print_type');
        if($printType == 1 && !Arrays::value($data, 'pic_ids')){
            throw new Exception('未上传图片');
        }
        if($printType == 2 && !Arrays::value($data, 'file_id')){
            throw new Exception('未上传文件');
        }
        // 页数
        $data['page_count'] = self::getInstance($uuid)->calPageCount();
        // 单价
        $data['unit_prize'] = self::getInstance($uuid)->calUnitPrize();
        $data['order_prize'] = $data['page_count'] * $data['unit_prize'];
        
        return $data;
    }

    /**
     * 钩子-保存后
     */
    public static function ramAfterSave(&$data, $uuid) {
        // 20231217
        if($data['order_prize']){
            self::getInstance($uuid)->addStatementOrder();
        }
        // 写入任务列表
        self::getInstance($uuid)->toTaskFiles();
    }

    /**
     * 钩子-更新前
     */
    public static function ramPreUpdate(&$data, $uuid) {
        
    }

    /**
     * 钩子-更新后
     */
    public static function ramAfterUpdate(&$data, $uuid) {
        
    }

    /**
     * 钩子-删除前
     */
    public function ramPreDelete() {
        $info = $this->get();
        if($info['is_get']){
            throw new Exception('已领取不可删');
        }
        if($info['is_print']){
            throw new Exception('已打印不可删');
        }
        if($info['is_pay']){
            throw new Exception('已支付不可删');
        }
        // 先删明细
        $fileList = $this->objAttrsList('printerTaskFiles');
        foreach($fileList as $v){
            // 20231217
            PrinterTaskFilesService::getInstance($v['id'])->deleteRam();
        }
    }

    /**
     * 钩子-删除后
     */
    public function ramAfterDelete() {
        
    }
}
