<?php

namespace xjryanse\printer\service;

use xjryanse\system\interfaces\MainModelInterface;

/**
 * 
 */
class PrinterTaskFilesService extends Base implements MainModelInterface {

    use \xjryanse\traits\InstTrait;
    use \xjryanse\traits\MainModelTrait;
    use \xjryanse\traits\MainModelRamTrait;
    use \xjryanse\traits\MainModelCacheTrait;
    use \xjryanse\traits\MainModelCheckTrait;
    use \xjryanse\traits\MainModelGroupTrait;
    use \xjryanse\traits\MainModelQueryTrait;

    use \xjryanse\traits\ObjectAttrTrait;

    protected static $mainModel;
    protected static $mainModelClass = '\\xjryanse\\printer\\model\\PrinterTaskFiles';

    protected static $directAfter = true;
    // 20230710：开启方法调用统计
    protected static $callStatics = true;
    
    use \xjryanse\printer\service\taskFiles\TriggerTraits;
    use \xjryanse\printer\service\taskFiles\DoTraits;
    
    /**
     * 20231217:待打印文件
     * @param type $taskId
     * @param type $fileIds
     */
    public static function taskAddBatchRam($taskId, $fileIds){
        $dataArr = [];
        foreach($fileIds as $fileId){
            $dataArr[] = ['task_id'=>$taskId,'file_id'=>$fileId];
        }
        //先删再加
        self::saveAllRam($dataArr);
    }

}
