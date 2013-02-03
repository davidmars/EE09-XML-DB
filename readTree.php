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
traceLabeled("tree created",$tree->getCreated()->format("Y/m/d h:i:s"));
traceLabeled("tree updated",$tree->getUpdated()->format("Y/m/d h:i:s"));
traceComment("Now a tree....");
/**
 * @param GinetteBranch[] $branches
 */
function traceTree($branches){
    echo "<ul>";

    foreach($branches as $b){
        echo "<li>".$b;
            traceTree($b->branches);
        echo "</li>";
    }
    echo "</ul>";
}

traceTree($tree->branches);
/*
traceLabeled("get the parent of :",$tree->branches[0]->branches[0]->model->getId());
traceLabeled("get the parent of :",$tree->branches[0]->branches[0]->parent->model->getId());
*/

//-----------------
trace("write....");
/**
 * @param GinetteDb $db
 */
function getModelName($db){
    $i=1;
    $name="test-".$i;
    while($db->getModelById($name)){
        $i++;
        $name="test-".$i;
    }
    return $name;
}

$model=new Post(getModelName($db),$db);
traceLabeled("new model",$model);
$tree->branches->push($model);
traceLabeled("first node xml after prepend",$tree->xml->firstChild->firstChild->nodeName."/".$tree->xml->firstChild->firstChild->getAttribute("id"));
traceLabeled("first node model after prepend",
    $tree->branches[0]->model->getType()." /
    ".$tree->branches[0]->model->getId());


$model=new Post(getModelName($db),$db);
traceLabeled("new model to put into first",$model);
$tree->branches[0]->branches->push($model);
$tree->branches[0]->branches[0]->branches->push($model);
$tree->branches[0]->branches[0]->branches[0]->branches->push($model);

for($i=0;$i<10;$i++){
    $model=new Post(getModelName($db),$db);
    $tree->branches[rand(0,$tree->branches->length()-1)]->branches->push($model);
}




/*
$tree->branches->shift();
//$tree->branches->offsetUnset(0);
traceLabeled("first node xml after shift",$tree->xml->firstChild->firstChild->nodeName."/".$tree->xml->firstChild->firstChild->getAttribute("id"));
traceLabeled("first node model after shift",$tree->branches[0]->model->getType()." / ".$tree->branches[0]->model->getId());
trace("------------------------");
*/
traceTree($tree->branches);

$tree->save();

$xpath = new DOMXPath($tree->xml);
// We starts from the root element
$query = "//Post[@id='beatles']";
/** @var DOMNodeList $entries */
$entries = $xpath->query($query);
/** @var DOMNode $entry */
foreach ($entries as $entry) {
    echo "Found ".$entry->nodeName."<br>";
}
