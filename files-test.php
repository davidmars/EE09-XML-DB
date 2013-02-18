<?php

require_once("core/utils/ClassAutoLoader.php");
$autoLoader=new ClassAutoLoader();
$autoLoader->addPath("core",true);
TraceConf::$doTrace=true;
Nestor::start();
echo "<h1>Read tree</h1>";

//open the database

$db=new GinetteDb("myDatabase1");

traceError(Nestor::time());

traceComment("Get the 'file tree'");
$tree=$db->fileRoot;
traceError(Nestor::time());

traceTree($tree);
traceError(Nestor::time());
/**
 * @param GinetteDir $dir
 */
function traceTree($dir){
    echo "<ul>";

    foreach($dir->childrenDir() as $f){
        echo "<li><span style='color:#ff0000'>".$f->fileName()."</span>";
            traceTree($f);
        echo "</li>";
    }
    foreach($dir->childrenImages() as $f){
        echo "<li>".$f->fileName()." ".$f->extension(). " (".$f->relativePath.")";
        echo "</li>";
    }
    foreach($dir->childrenFilesExceptImages() as $f){
        echo "<li><span style='color:#00ff00'>".$f->fileName()." ".$f->extension()."</span>";
        echo "</li>";
    }
    echo "</ul>";
}

traceError(Nestor::time());
traceLabeled("Number of images",count($tree->childrenImages(true)));
traceError(Nestor::time());

//get a random image
$randImage=$tree->childrenImages(true)[2];
traceLabeled("random image is ",$randImage->fileName());

traceError(Nestor::time());
traceLabeled("random image width ",$randImage->width());

traceError(Nestor::time());
traceLabeled("random image height ",$randImage->height());

traceError(Nestor::time());
traceLabeled("random image relative path ",$randImage->relativePath);

traceError(Nestor::time());
traceImg($randImage->sizedWidth(100,"#00ff00",50,"jpg"));

traceError(Nestor::time());
traceImg($randImage->sizedWidth(100,"transparent",50,"png"));

traceError(Nestor::time());


traceLabeled("get the image","nous-3.jpg");
$im=GinetteFileImage::getByUrl("nous-3.jpg",$db);
traceError(Nestor::time());
traceImg($im->sizedWidth(400));
traceError(Nestor::time());
traceImg($im->sizedWidth(800));
traceError(Nestor::time());
traceImg($im->sizedHeight(200));
traceError(Nestor::time());
traceImg($im->sizedShowAll(200,200,"#ff00ff"));
traceError(Nestor::time());
traceImg($im->sizedShowAll(200,200,"transparent",10,"png"));
traceError(Nestor::time());
traceImg($im->sizedNoBorder(200,400,"#ff0000",95,"jpg"));
traceError(Nestor::time());
traceImg($im->sizedNoBorder(400,200,"#ff0000",95,"jpg"));
traceError(Nestor::time());


