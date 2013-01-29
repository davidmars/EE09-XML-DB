<?php
/**
 * admin is a View template. It works with a mixed object.
 * layout for the admin pages
 *
 */

/* @var $this View */
/* @var $vv VM_layout */
$vv = $_vars;
$this->inside("mvc/v/layout/html5bp",$vv);

?>
<!-- This code is taken from http://twitter.github.com/bootstrap/examples/hero.html -->

<div class="navbar navbar-inverse navbar-fixed-top">
    <div class="navbar-inner">
        <div class="container">
            <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </a>
            <a class="brand" href="#">Project name</a>
            <div class="nav-collapse collapse">
                <ul class="nav">
                    <li class="active"><a href="#">Home</a></li>
                    <li><a href="#about">About</a></li>
                    <li><a href="#contact">Contact</a></li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Dropdown <b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li><a href="#">Action</a></li>
                            <li><a href="#">Another action</a></li>
                            <li><a href="#">Something else here</a></li>
                            <li class="divider"></li>
                            <li class="nav-header">Nav header</li>
                            <li><a href="#">Separated link</a></li>
                            <li><a href="#">One more separated link</a></li>
                        </ul>
                    </li>
                </ul>
                <form class="navbar-form pull-right">
                    <input class="span2" type="text" placeholder="Email">
                    <input class="span2" type="password" placeholder="Password">
                    <button type="submit" class="btn">Sign in</button>
                </form>
            </div><!--/.nav-collapse -->
        </div>
    </div>
</div>

        <div class="container">
            <div class="row">
                <div class="span4">
                    <ul class="nav nav-list">
                        <?=$this->render("mvc/v/layout/nav/left/models-list",$vv->getLayout()->getModelList())?>
                    </ul>
                </div>
                <div class="span8">
                    <?=$this->insideContent?>
                </div>
            </div>




            <footer>
                <p>&copy; Cocorico cowboy!</p>
            </footer>

        </div> <!-- /container -->