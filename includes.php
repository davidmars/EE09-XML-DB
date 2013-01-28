<?php
/**
 * Created by JetBrains PhpStorm.
 * User: juliette
 * Date: 26/01/13
 * Time: 14:37
 * To change this template use File | Settings | File Templates.
 */

require_once("core/ModelXmlDb.php");

/*require_once("core/ModelXml.php");
require_once("core/FieldManager.php");
require_once("core/Post.php");*/

function trace($str){
    echo "<div style='background-color: #eee; padding: 10px; margin: 10px;'>$str</div>";
}
function traceLabeled($label,$str){
    echo "<div style='background-color: #eee; padding: 10px; margin: 10px;'><b>$label</b><br>$str</div>";
}
function traceComment($str){
    echo "<div style='color: #bbb; padding: 10px; margin: 10px;'>$str</div>";
}
function traceError($str){
    echo "<div style='background-color: #f00; color:#fff; padding: 10px; margin: 10px;'><b>Error</b><br>$str</div>";
}
function traceCode($str){
    echo "<div style='background-color: #000; color:#0f0; padding: 10px; margin: 10px;'>$str</div>";
}
function traceImg($url){
    echo "<div style='background-color: #eee; color:#666; padding: 10px; margin: 10px;'>
    <img src='".$url."' width='300px'>
    </div>";
}