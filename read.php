<?php

include "includes.php";

echo "<h1>Read models</h1>";

//open the database
$db=new ModelXmlDb("myDatabase1");



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

trace("----------------other post (manyyyyy!)----------");
foreach($post->otherPosts as $related){
    traceLabeled($related->getId()."( ".$related->getType()." )",$related->description."");
}

trace("----------------see also----------");
foreach($post->seeAlso->children as $related){
    traceLabeled($related->getId()."( ".$related->getType()." )",$related->description."");
}

traceComment("Get the 'queen' model by Post object");
$post=Post::getById("queen");
traceLabeled("description:",$post->description);

$post->otherPost=Post::getById("queen");
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

?>
