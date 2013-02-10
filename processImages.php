<?php
require_once("core/utils/ClassAutoLoader.php");
$autoLoader=new ClassAutoLoader();
$autoLoader->addPath("core",true);


//open the database

$db=new GinetteDb($_GET["db"]);
//activates the image processing
GinetteFileImage::$processImage=true;
//what is the function and arguments to use...
$subject = $_GET["img"];
if(preg_match('#^(.*)/sizedNoBorder-(.*)-(.*)-(.*)-(.*)\.(.*)#',$subject,$m)){
    $img=GinetteFileImage::getByUrl($m[1],$db);
    if($img){
        $im=$img->sizedNoBorder($m[2],$m[3],$m[4],$m[5],$m[6]);
        GinetteFileImage::output($im);
    }
}else if(preg_match('#^(.*)/sizedShowAll-(.*)-(.*)-(.*)-(.*)\.(.*)#',$subject,$m)){
    $img=GinetteFileImage::getByUrl($m[1],$db);
    if($img){
        $im=$img->sizedShowAll($m[2],$m[3],$m[4],$m[5],$m[6]);
        GinetteFileImage::output($im);
    }
}else if(preg_match('#^(.*)/sizedHeight-(.*)-(.*)-(.*)\.(.*)#',$subject,$m)){
    $img=GinetteFileImage::getByUrl($m[1],$db);
    if($img){
        $im=$img->sizedHeight($m[2],$m[3],$m[4],$m[5]);
        GinetteFileImage::output($im);
    }
}else if(preg_match('#^(.*)/sizedWidth-(.*)-(.*)-(.*)\.(.*)#',$subject,$m)){
    $img=GinetteFileImage::getByUrl($m[1],$db);
    if($img){
        $im=$img->sizedWidth($m[2],$m[3],$m[4],$m[5]);
        GinetteFileImage::output($im);
    }
}else{
   die("image function doesn't match : ".$subject);
}





//2011/2011-01-05/IMG_1684.JPG/sizedNoBorder-60-70.jpg