<?php
/**
 *
 * An image model is related to a file which is an Image...great :\ . It has extra information
 *
 * @property String $id The unique identifier
 * @property String $type The model type
 * @property DateTime $created When was created this record
 * @property DateTime $updated When was last updated this record
 * @property String $title Title of the image, will be useful for alt
 * @property String $legend Here is the post quick description
 * @property FileImage $file Well, the image file itself
 *
 */
class Image extends ModelXml
{


    /**
     * Search a Image by id. If the id is found or if the record is not a Image, will return an Exception error message
     * @param string $id A model unique identifier.
     * @param GinetteDb $database The database where to search.
     * @return Image The Image model.
     */
    public static function getById($id,$database)
    {
        $item = $database->getModelById($id);
        if (!$item) {
            die("You tried to get the Image '$id' but there is no '$id' record !");
        }
        if ($item->type != "Image") {
            die("You tried to get the Image '$id' ...but '$id' is not a Image, it's a " . $item->getType());
        }
        return $item;
    }
}