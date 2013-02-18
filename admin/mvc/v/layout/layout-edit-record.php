<?php
/**
 * layout-edit-record is a View template. It works with a  object.
 *
 *
 */

/* @var $this View */
/* @var VM_layout $vv */
$vv = $_vars;
$this->inside("layout/layout-admin-page",$vv);
?>
<?php //---------------------------the left nav ?>
<div class="span4">
    <?php
    /*
    <ul class="nav nav-list">
        <?php //echo $this->render("layout/nav/left/models-list",$vv->getModelList())?>

    </ul>
    */?>
    <?php echo $this->render("layout/nav/left/tree",$vv->getTree())?>
</div>
<?php //---------------------------the page content ?>
<div class="span8">
    <?=$this->insideContent?>
</div>