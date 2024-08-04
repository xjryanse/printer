<?php
namespace xjryanse\printer\model;

/**
 *
 */
class PrinterTask extends Base
{
        
    use \xjryanse\traits\ModelUniTrait;
    // 20230516:数据表关联字段
    public static $uniFields = [
        //性能不佳
        [
            'field'     =>'user_id',
            'uni_name'  =>'user',
            'uni_field' =>'id',
            'in_list'   => false,
            'in_statics'=> false,
            'in_exist'  => true,
            'del_check' => true,
        ],[
            'field'     =>'id',
            'uni_name'  =>'finance_statement_order',
            'uni_field' =>'belong_table_id',
            'exist_field'=>'isStatementOrderExist',
            'in_list'   => false,
            'in_statics'=> false,
            'in_exist'  => true,
            'del_check' => false,
            // 20231113:关联映射表
            'reflect_field' => [
                // hasStatement 映射到表finance_statement_order的has_statement
                'hasStatement'  => ['key'=>'has_statement','nullVal'=>0],
                // nullVal,当关联结果是null时的替代值
                'hasSettle'     => ['key'=>'has_settle','nullVal'=>0]
            ],
        ]
    ];
    
    /**
     * 20230807：反置属性
     * @var type
     */
    public static $uniRevFields = [
        [
            'table'     =>'finance_statement_order',
            'field'     =>'belong_table_id',
            'uni_field' =>'id',
            'exist_field'   =>'isStatementOrderExist',
            'condition'     =>[
                // 关联表，即本表
                'belong_table'=>'{$uniTable}'
            ]
        ]
    ];
    
    public static $picFields = ['file_id'];
    
    public static $multiPicFields = ['pic_ids'];

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
    
    /**
     * 2023-10-10多图
     * @param type $value
     * @return type
     */
    public function getPicIdsAttr($value) {
        return self::getImgVal($value, true);
    }
    
    public function setPicIdsAttr($value) {
        return self::setImgVal($value);
    }
    
}