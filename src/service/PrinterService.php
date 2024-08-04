<?php

namespace xjryanse\printer\service;

use xjryanse\system\interfaces\MainModelInterface;

/**
 * 
 */
class PrinterService extends Base implements MainModelInterface {

    use \xjryanse\traits\InstTrait;
    use \xjryanse\traits\MainModelTrait;
    use \xjryanse\traits\MainModelRamTrait;
    use \xjryanse\traits\MainModelCacheTrait;
    use \xjryanse\traits\MainModelCheckTrait;
    use \xjryanse\traits\MainModelGroupTrait;
    use \xjryanse\traits\MainModelQueryTrait;

    use \xjryanse\traits\ObjectAttrTrait;

    protected static $mainModel;
    protected static $mainModelClass = '\\xjryanse\\printer\\model\\Printer';

    protected static $directAfter = true;
    // 20230710：开启方法调用统计
    protected static $callStatics = false;
    
    use \xjryanse\printer\service\index\DoTraits;
    
    /**
     * 额外详情
     * @param type $ids
     * @return type
     */
    public static function extraDetails($ids) {
        return self::commExtraDetails($ids, function ($lists) use ($ids) {
                    foreach($lists as &$v){
                        // 20231219:判断设备是否在线
                        $v['isOnline']  = $v['last_online_time'] && time() - strtotime($v['last_online_time']) < 20 ? 1 : 0;
                    }
                    return $lists;
                }, true);
    }
    
    /**
     * 更新在线状态
     */
    public function onlineUpdate($data=[]){
        $data['last_online_time'] = date('Y-m-d H:i:s');
        return $this->updateRam($data);
    }
    /**
     * 20231219：识别当前的打印机
     */
    public static function getCurrent(){
        $id = '5548856233096011777';
        $info = self::getInstance($id)->info();
        
        return $info;
    }
    
}
