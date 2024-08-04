<?php

namespace xjryanse\printer\service;

use xjryanse\system\interfaces\MainModelInterface;
use xjryanse\finance\service\FinanceStatementOrderService;
use xjryanse\finance\service\FinanceStatementService;
use Exception;

/**
 * 
 */
class PrinterServerConfService extends Base implements MainModelInterface {

    use \xjryanse\traits\InstTrait;
    use \xjryanse\traits\MainModelTrait;
    use \xjryanse\traits\MainModelRamTrait;
    use \xjryanse\traits\MainModelCacheTrait;
    use \xjryanse\traits\MainModelCheckTrait;
    use \xjryanse\traits\MainModelGroupTrait;
    use \xjryanse\traits\MainModelQueryTrait;

    use \xjryanse\traits\ObjectAttrTrait;

    protected static $mainModel;
    protected static $mainModelClass = '\\xjryanse\\printer\\model\\PrinterServerConf';

    public static function getByCustomerId(){
        $customerId = '5233210273763827712';
        $con[] = ['server_customer_id','=',$customerId];
        $info = self::where($con)->find();
        return $info;
    }

}
