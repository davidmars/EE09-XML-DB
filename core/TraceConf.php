<?php
/**
 *
 */
class TraceConf{
    /**
     * @var bool set it to false if you want to prevent traces
     *
     */
    public static $doTrace=true;
}


function echoTrace($trace){
    if(TraceConf::$doTrace){
        echo $trace;
    }
}
function trace($str){
    echoTrace("<div style='background-color: #eee; padding: 10px; margin: 10px;'>$str</div>");
}
function traceLabeled($label,$str){
    echoTrace("<div style='background-color: #eee; padding: 10px; margin: 10px;'><b>$label</b><br>$str</div>");
}
function traceComment($str){
    echoTrace("<div style='color: #bbb; padding: 10px; margin: 10px;'>$str</div>");
}
function traceError($str){
    echoTrace("<div style='background-color: #f00; color:#fff; padding: 10px; margin: 10px;'><b>Error</b><br>$str</div>");
}
function traceCode($str){
    echoTrace("<div style='background-color: #000; color:#0f0; padding: 10px; margin: 10px;'>$str</div>");
}
function traceImg($url){
    echoTrace("<div style='background-color: #eee; color:#666; padding: 10px; margin: 10px;'>
    <img src='".$url."' width='300px'>
    </div>");
}

