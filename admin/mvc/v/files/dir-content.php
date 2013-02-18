<?php
/**
 * dir-content is a View template. It works with a VM_dir object.
 * display the content a directory
 *
 */

/* @var $this View */
/* @var $vv VM_file_dir */
$vv = $_vars;

?>
<?php //------title?>
<?php if($vv->dir->parent()):?>
    <div style="float: right;">
        <a load-in-jaquy-ajax-receiver="foldercontent"
           href="<?=C_files::urlDirContent($vv->dir->parent()->relativePath)?>">
            <i class="icon-circle-arrow-up"></i>
        </a>
    </div>
<?php endif?>
<h1 class="page-header">
    <?=$vv->dir->fileName()?><small> <?=$vv->dir->relativePath?>/</small>
</h1>

<?php //------list?>
<div class="row">
    <?php foreach($vv->dir->childrenImages() as $im):?>


    <div class="span2" file-manager-item="<?=$im->relativePath?>">
        <img class="thumbnail" src="<?=$im->sizedShowAll(200,200)?>">
        <div><br><?=$im->relativePath?></div>
    </div>


    <?php endforeach?>
</div>