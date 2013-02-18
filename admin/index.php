<?php
/**
 * Well...this admin is not a good practice demonstration.
 * We need it simple, without dependencies and standalone.
 * - NO URL REWRITING!
 * - NO EXOTIC PHP LIB
 */

require_once("../core/utils/ClassAutoLoader.php");
$autoLoader=new ClassAutoLoader();
$autoLoader->addPath("../core",true);
$autoLoader->addPath("mvc/vm",true);
$autoLoader->addPath("mvc/c");

//add admin template views
View::$rootPaths[]=__DIR__."/mvc/v";

//require_once("../core/GinetteDb.php);
TraceConf::$doTrace=false;

//search and run controller...

if(isset($_GET["p"])){
    $route=$_GET["p"];
}else{
    $route="home";
}
$controller="C_".$route;
if(class_exists($controller)){
    $c=new $controller();
}else{
    new C_home();
}
