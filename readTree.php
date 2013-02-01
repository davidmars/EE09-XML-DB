<?php

require_once("core/utils/ClassAutoLoader.php");
$autoLoader=new ClassAutoLoader();
$autoLoader->addPath("core",true);
TraceConf::$doTrace=true;

echo "<h1>Read tree</h1>";

//open the database
$db=new GinetteDb("myDatabase1");

traceComment("Get the 'main' tree");
$tree=$db->getTreeById("main");

var_dump($tree);
traceLabeled("tree id",$tree->getId());
traceLabeled("tree id",$tree->getType());
traceLabeled("tree created",$tree->getCreated()->format("Y/m/d h:i:s"));
traceLabeled("tree updated",$tree->getUpdated()->format("Y/m/d h:i:s"));

traceLabeled(
    "first branch model is",
    $tree->branches[0]
);
traceLabeled(
    "first branch->first model is",
    $tree->branches[0]->branches[0]
);
traceComment("Now a tree....");
/**
 * @param GinetteBranch[] $branches
 */
function tree($branches){
    echo "<ul>";

    foreach($branches as $b){
        echo "<li>".$b;
            tree($b->branches);
        echo "</li>";
    }
    echo "</ul>";
}

tree($tree->branches);
