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

<div class="media thumbnail">
    <a class="close pull-right" href="#">&times;</a>
    <a class="pull-left" href="#">
        <?php /*<img style="width:100px;" src="<?=$vv->getThumbnail()->getUrl()?>" alt="">*/ ?>
    </a>
    <div class="media-body">
        <h4 class="media-heading"><?=$vv->getId()?></h4>
        <p><?=$vv->getType()?></p>
    </div>
</div>
