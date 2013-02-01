<?php

require_once("core/utils/ClassAutoLoader.php");
$autoLoader=new ClassAutoLoader();
$autoLoader->addPath("core",true);
TraceConf::$doTrace=true;

echo "<h1>Read models</h1>";

//open the database
$db=new GinetteDb("myDatabase1");



traceComment("Get the 'beatles' model by database object");
/** @var $post Post */
$post=$db->getModelById("beatles");
traceLabeled("description:",$post->description);
traceLabeled("created:",$post->created->format("Y m d h:i:s"));
traceLabeled("updated:",$post->updated->format("Y m d h:i:s"));

traceLabeled("download",$post->download);
$post->download->setUrl($post->download->getUrl());
traceLabeled("download exists",$post->download->exists());
traceLabeled("download filesize",$post->download->getFileSize());

trace("----------------other post (onlyone!)----------");
traceLabeled("otherPost",$post->otherPost->description);

trace("will save thumbnail in ".$post->otherPost->getId());
$post->otherPost->thumbnail=Image::getById("nous-3-image",$db);
$post->otherPost->save();
traceImg($post->otherPost->thumbnail->file);

trace("----------------other post (manyyyyy!)----------");
foreach($post->otherPosts as $related){
    traceLabeled($related->getId()."( ".$related->getType()." )",$related->description."");
}



trace("----------------see also----------");
foreach($post->seeAlso->children as $related){
    traceLabeled($related->getId()."( ".$related->getType()." )",$related->description."");
}

traceComment("Get the 'queen' model by Post object");
$post=Post::getById("queen",$db);
traceLabeled("description:",$post->description);

$post->otherPost=Post::getById("queen",$db);
if(!$db->modelExists("aaaaaaaa")){
    $post->otherPosts[]=new Post("aaaaaaaa");
}
if(!$db->modelExists("bbbbbbb")){
    $post->otherPosts[]=new Post("bbbbbbb");
}
$post->save();

/*
traceComment("Get the 'I-m-a-shit-model' model by Post object");
$post=Post::getById("I-m-a-shit-model");
trace($post->description);
*/


