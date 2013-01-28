<?php
/* @var $this View */
/* @var $vv M_fieldManager */
$vv = $_vars;

?>
<?php echo "<?php\n"?>
/**
 *
 * <?=$vv->description."\n"?>
 *
<?php foreach($vv->fields as $f):?>
<?php echo $this->render("gen/property",$f)."\n"?>
<?php endforeach?>
 *
 */
class <?php echo $vv->type?> extends ModelXml
{


    /**
     * Search a <?=$vv->type?> by id. If the id is found or if the record is not a <?=$vv->type?>, will return an Exception error message
     * @param string $id A model unique identifier.
     * @return <?=$vv->type?> The <?=$vv->type?> model.
     */
    public static function getById($id)
    {
        $item = self::$db->getModelById($id);
        if (!$item) {
            die("You tried to get the <?=$vv->type?> '$id' but there is no '$id' record !");
        }
        if ($item->type != "Post") {
            die("You tried to get the <?=$vv->type?> '$id' ...but '$id' is not a <?=$vv->type?>, it's a " . $item->getType());
        }
        return $item;
    }
}