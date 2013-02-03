<?php

require_once("core/utils/ClassAutoLoader.php");
$autoLoader=new ClassAutoLoader();
$autoLoader->addPath("core",true);
TraceConf::$doTrace=true;

echo "<h1>Build indexes</h1>";

//open the database
$db=new GinetteDb("myDatabase1");

$indexer=new GinetteDbIndex($db);
$indexer->indexAllRecords();