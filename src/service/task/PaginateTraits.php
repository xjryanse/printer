<?php

namespace xjryanse\printer\service\task;

/**
 * 触发复用
 */
trait PaginateTraits{
    /*
     * 用户查询自己的数据
     */
    public static function paginateForUser($con = [], $order = '', $perPage = 10, $having = '', $field = "*", $withSum = false) {
        $con[]  = ['user_id','=',session(SESSION_USER_ID)];
        $order = 'create_time desc';
        $res    = self::paginateX($con, $order, $perPage, $having, $field, $withSum);
        return $res;
    }

}
