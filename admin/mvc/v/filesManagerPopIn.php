<?php
/**
 * On the left display the directories browser, on the right, display the content
 *
 */

/* @var $this View */
/* @var $vv VM_files_manager */
$vv = $_vars;
?>
<div class=" full" file-manager >
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h3>File manager</h3>
    </div>
    <div class="modal-body">

        <?php //---------------------------the left nav ?>
        <div class="left">
            <div class="tree-nav" jacky-activable-item-list>
                <?php echo $this->render("layout/nav/nav-files/files-dir",$vv->rootDir)?>
            </div>
        </div>

        <?php //---------------------------the folder content ?>
        <div class="right" jaquy-ajax-receiver="foldercontent">
            <?=$this->insideContent?>
        </div>



    </div>

</div>

