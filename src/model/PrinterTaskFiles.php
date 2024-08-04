<?php
namespace xjryanse\printer\model;

/**
 *
 */
class PrinterTaskFiles extends Base
{
        
    use \xjryanse\traits\ModelUniTrait;
    // 20230516:数据表关联字段
    public static $uniFields = [
        //性能不佳
        [
            'field'     =>'task_id',
            'uni_name'  =>'printer_task',
            'uni_field' =>'id',
        ]
    ];
    
    public static $picFields = ['file_id'];
    
    /**
     * 附件
     * @param type $value
     * @return type
     */
    public function getFileIdAttr($value) {
        return self::getImgVal($value);
    }

    /**
     * 图片修改器，图片带id只取id
     * @param type $value
     * @throws \Exception
     */
    public function setFileIdAttr($value) {
        return self::setImgVal($value);
    }
}