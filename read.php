<?php

require_once("core/utils/ClassAutoLoader.php");
$autoLoader=new ClassAutoLoader();
$autoLoader->addPath("core",true);
TraceConf::$doTrace=true;

echo "<h1>Read models</h1>";

Nestor::start();
traceError(Nestor::time()."before boot db");
//open the database
$db=new GinetteDb("myDatabase1");

traceError(Nestor::time()."after boot db");

/**
traceComment("Get the 'beatles' model by database object");

$post=$db->getRecordById("beatles");
var_dump($post);
traceLabeled("description:",$post->description);
traceLabeled("created:",$post->created->format("Y m d h:i:s"));
traceLabeled("updated:",$post->updated->format("Y m d h:i:s"));
traceError(Nestor::time()." ");
traceLabeled("download",$post->download);
$post->download->setUrl($post->download->getUrl());
traceLabeled("download exists",$post->download->exists());
traceLabeled("download filesize",$post->download->getFileSize());
traceError(Nestor::time()." ");
trace("----------------other post (onlyone!)----------");
traceLabeled("otherPost",$post->otherPost->description);
traceError(Nestor::time()." ");
trace("will save thumbnail in ".$post->otherPost->getId());
$post->otherPost->thumbnail=Image::getById("nous-3-image",$db);
$post->otherPost->save();
traceImg($post->otherPost->thumbnail->file);
traceError(Nestor::time()." ");
trace("----------------other post (manyyyyy!)----------");
foreach($post->otherPosts as $related){
    traceLabeled($related->getId()."( ".$related->getType()." )",$related->description."");
}
traceError(Nestor::time()." ");



trace("----------------see also----------");
foreach($post->seeAlso->children as $related){
    traceLabeled($related->getId()."( ".$related->getType()." )",$related->description."");
}

traceComment("Get the 'queen' model by Post object");
$post=Post::getById("queen",$db);
traceLabeled("description:",$post->description);

$post->otherPost=Post::getById("queen",$db);
if(!$db->recordExists("aaaaaaaa")){
    $post->otherPosts[]=new Post("aaaaaaaa");
}
if(!$db->recordExists("bbbbbbb")){
    $post->otherPosts[]=new Post("bbbbbbb");
}
$post->save();
*/

traceCode(Nestor::time()." start");
$all=$db->getRecordList();

foreach($all as $m){
    //trace($m->getId());
    echo $m->getId();
}
traceCode(Nestor::time()." end");
/*
traceComment("Get the 'I-m-a-shit-model' model by Post object");
$post=Post::getById("I-m-a-shit-model");
trace($post->description);
*/


