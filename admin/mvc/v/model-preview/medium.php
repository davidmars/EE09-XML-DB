<?php
/**
 * medium is a View template. It works with a GinetteRecord object.
 * A medium preview
 *
 */

/* @var $this View */
/* @var $vv GinetteRecord */
$vv = $_vars;
?>

<div class=" record-preview">
    <?php /*
    <div class="btns">
    <a class="close pull-right" href="#">&times;</a>
    </div>
    */?>
    <a class="thumb" href="#">
       <?php /* <img style="width:100px;" src="<?=$vv->getThumbnail()->sizedShowAll(100,100,"#eeeeee")?>" alt=""> */ ?>
    </a>
    <div class="text">
        <h4 class="media-heading"><?=$vv->getId()?></h4>
        <p><?=$vv->getType()?></p>
    </div>

</div>
