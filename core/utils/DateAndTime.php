<?php
/**
 * Created by JetBrains PhpStorm.
 * User: heeek
 * Date: 01/02/13
 * Time: 08:16
 * To change this template use File | Settings | File Templates.
 */
class DateAndTime
{
    /**
     * @param string $str
     * @return DateTime
     */
    public static function fromString($str){
        $val="now";
        if(is_numeric($str)){
            $val="@".$str;
        }
        traceCode($val);
        return new DateTime($val);
    }
}
