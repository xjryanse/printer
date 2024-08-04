<?php

namespace xjryanse\printer\service;

use xjryanse\system\interfaces\MainModelInterface;
use xjryanse\finance\service\FinanceStatementOrderService;
use xjryanse\finance\service\FinanceStatementService;
use xjryanse\printer\service\PrinterTaskFilesService;
use Exception;

/**
 * 
 */
class PrinterTaskService extends Base implements MainModelInterface {

    use \xjryanse\traits\InstTrait;
    use \xjryanse\traits\MainModelTrait;
    use \xjryanse\traits\MainModelRamTrait;
    use \xjryanse\traits\MainModelCacheTrait;
    use \xjryanse\traits\MainModelCheckTrait;
    use \xjryanse\traits\MainModelGroupTrait;
    use \xjryanse\traits\MainModelQueryTrait;

    use \xjryanse\traits\ObjectAttrTrait;
    use \xjryanse\traits\FinanceSourceModelTrait;

    protected static $mainModel;
    protected static $mainModelClass = '\\xjryanse\\printer\\model\\PrinterTask';

    protected static $directAfter = true;
    // 20230710：开启方法调用统计
    
    use \xjryanse\printer\service\task\DoTraits;
    use \xjryanse\printer\service\task\TriggerTraits;
    use \xjryanse\printer\service\task\ListTraits;
    use \xjryanse\printer\service\task\CalTraits;
    use \xjryanse\printer\service\task\PaginateTraits;
    
    /**
     * 额外详情
     * @param type $ids
     * @return type
     */
    public static function extraDetails($ids) {
        return self::commExtraDetails($ids, function ($lists) use ($ids) {

                    foreach($lists as &$v){
                        // needPay:待付款；toDownload:待打印；printing:打印中；toGet:待取件；finish:已取件
                        $v['taskState'] = self::calTaskState($v);
                    }
            
                    return $lists;
                }, true);
    }
    
    /**
     * 提取打印数据详情，并更新is_downlowd字段
     */
    public function getForPrintDownload(){
        $info = $this->get();
        if($info['is_print']){
            throw new Exception('当前任务已打印');
        }
        // 更新is_downlowd
        $data = ['is_download'=>1];
        
        $this->updateRam($data);

        // 20231217:待印文件
        $info['taskFiles'] = $this->objAttrsList('printerTaskFiles');

        return $info;
    }
    
    /**
     * 提取待打印的数据id
     */
    public static function todoIds(){
        // 提取已付；未打印的明细
        $con    = [];
        $con[]  = ['is_pay','=',1];
        $con[]  = ['is_print','=',0];
        // 20231217：增加未下载的
        $con[]  = ['is_download','=',0];

        $ids = self::where($con)->limit(3)->column('id');

        return $ids;
    }
    
    /**
     * 20231216:订单账单添加
     */
    public function addStatementOrder() {
        $prizeKey   = 'printPrize';
        $prizeField = 'order_prize';

        $reflectKeys = ['user_id'=>'user_id'];
        return $this->financeCommAddStatementOrder($prizeKey, $prizeField, $reflectKeys);
    }
    
    /*
     * 获取批量账单id，用于合并支付
     */
    public static function statementGenerate($ids){
        $con[]              = ['belong_table_id','in',$ids];
        $statementOrderIds  = FinanceStatementOrderService::mainModel()->where($con)->column('id');
        $statementId        = FinanceStatementOrderService::getStatementIdWithGenerate($statementOrderIds, true);
        $financeStatement   = FinanceStatementService::getInstance( $statementId )->info();
        return $financeStatement;
    }
    
    
    /*******处理财务回调******************/
    public function dealFinanceCallBack($info){
        $data['is_pay']             = $this->calIsPay();
        return $this->doUpdateRam($data);
    }
    /**
     * 20231217：写入任务列表
     */
    public function toTaskFiles(){
        $info = $this->get();
        if($info['print_type'] == 1){
            $idStr = self::mainModel()->setPicIdsAttr($info['pic_ids']);
            $fileIds = explode(',',$idStr);
        } else {
            $fileId = self::mainModel()->setFileIdAttr($info['file_id']);
            $fileIds = [$fileId];
        }
        return PrinterTaskFilesService::taskAddBatchRam($this->uuid, $fileIds);
    }

    
}
