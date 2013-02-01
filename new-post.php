<?php
/**
 * Created by JetBrains PhpStorm.
 * User: juliette
 * Date: 24/01/13
 * Time: 10:44
 * To change this template use File | Settings | File Templates.
 */

include "includes.php";
$db=new GinetteDb("myDatabase1");

echo "<h1>New Image</h1>";

if(!$db->modelExists("nous-3-image")){
    $im=new Image("nous-3-image");
    $im->file->setUrl("myDatabase1/files/nous-3.jpg");
    $im->save();

}


echo "<h1>New Post</h1>";

$post=new Post(uniqid("post"));
//$post->id=uniqid("post");
$post->description="ma description";
$post->name="my post name ".$post->id;
//$post->download->setUrl("myDatabase1/files/nous-2.jpg");
$post->download->setUrl("myDatabase1/files/nous-3.jpg");
$post->save();

$hello=new Post("Hello");
$hello->name="Hello world";
$hello->description="Hellooooooo Helooooooo";
$post->otherPosts[]=$hello;
$post->save();

traceLabeled("name",$post->name);
traceLabeled("description",$post->description);
