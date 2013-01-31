<?php
/**
 *
 * A Post is an article.
 *
 * @property String $name Name of the post
 * @property String $description Here is the post quick description
 * @property Image $thumbnail The image that represents this post
 * @property File $download An attached file to the post
 * @property Association $seeAlso Related posts to this post
 * @property Post $otherPost 
 * @property Post[] $otherPosts A list of Posts, nothing else!
 *
 */
class Post extends ModelXml
{


    /**
     * Search a Post by id. If the id is found or if the record is not a Post, will return an Exception error message
     * @param string $id A model unique identifier.
     * @param ModelXmlDb $database The database where to search.
     * @return Post The Post model.
     */
    public static function getById($id,$database)
    {
        $item = $database->getModelById($id);
        if (!$item) {
            die("You tried to get the Post '$id' but there is no '$id' record !");
        }
        if ($item->type != "Post") {
            die("You tried to get the Post '$id' ...but '$id' is not a Post, it's a " . $item->getType());
        }
        return $item;
    }
}