<?php
/**
 * admin is a View template. It works with a mixed object.
 * layout for the admin pages
 *
 */

/* @var $this View */
/* @var $vv VM_layout */
$vv = $_vars;
$this->inside("layout/html5bp",$vv);

?>

<?php// the main top nav ?>
<?=$this->render("layout/nav/main-nav",$vv)?>

<div class="container">
    <div class="row">

    <?=$this->insideContent?>

    </div>




    <footer>
        <p>&copy; Cocorico cowboy!</p>
    </footer>

</div> <!-- /container -->