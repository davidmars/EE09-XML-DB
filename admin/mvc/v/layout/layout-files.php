<?php
/**
 * layout-edit-record is a View template. It works with a  object.
 *
 *
 */

/* @var $this View */
/* @var VM_files_manager $vv */
$vv = $_vars;
$this->inside("layout/layout-admin-page",$vv);
?>
<?php //---------------------------the left nav ?>
<div class="span4">
    <?php
    /*
    <ul class="nav nav-list">
        <?php //echo $this->render("layout/nav/left/models-list",$vv->getRecordList())?>
    </ul>
    */?>
    <div class="tree-nav">
    <?php echo $this->render("layout/nav/nav-files/files-dir",$vv->rootDir)?>
    </div>
</div>
<?php //---------------------------the page content ?>
<div class="span8" jaquy-ajax-receiver="foldercontent">

    <?=$this->insideContent?>
</div>