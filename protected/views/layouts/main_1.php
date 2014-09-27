<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title></title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!--
        <link rel="stylesheet" href="/initializr/css/bootstrap.min.css">
        -->
        <link rel="stylesheet" href="<?=Yii::app()->createUrl('initializr/css/bootstrap.min.css')?>">
        <style>
            body {
                padding-top: 50px;
                padding-bottom: 20px;
            }
        </style>
<!--        
        <link rel="stylesheet" href="/initializr/css/bootstrap-theme.min.css">
        <link rel="stylesheet" href="/initializr/css/main.css">
        <link rel="stylesheet" href="/css/custom.css">
        <script src="/initializr/js/vendor/modernizr-2.6.2-respond-1.1.0.min.js"></script>
-->
        <link rel="stylesheet" href="<?=Yii::app()->createUrl('initializr/css/bootstrap-theme.min.css')?>">
        <link rel="stylesheet" href="<?=Yii::app()->createUrl('initializr/css/main.css')?>">
        <link rel="stylesheet" href="<?=Yii::app()->createUrl('css/custom.css')?>">
        <script src="<?=Yii::app()->createUrl('initializr/js/vendor/modernizr-2.6.2-respond-1.1.0.min.js')?>"></script>

    </head>
    <body>
        <!--[if lt IE 7]>
            <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->

        
    <nav class="navbar navbar-inverse navbar-static-top" role="navigation">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#"><?php echo yii::app()->name?></a>
            </div>
            <div class="navbar-collapse collapse">
                
<?php
    if (Yii::app()->user->isGuest) {
        $action = Yii::app()->createUrl('site/login');
        $label = 'login';
    } else {
        $action = Yii::app()->createUrl('site/logout');
        $label = 'logout';
    }
?>
                
        <form action="<?= $action?>" class="navbar-form navbar-right">
            <button type="submit" value="Hello" class="btn btn-success"><?=$label?></button>
        </form>
<!--
                <form class="navbar-form navbar-right" role="form">
                    <div class="form-group">
                        <input type="text" placeholder="Gebruikersnaam" class="form-control">
                    </div>
                    <div class="form-group">
                        <input type="password" placeholder="Wachtwoord" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-default">Inloggen</button>
                </form>
-->
            </div><!--/.navbar-collapse -->
        </div>

    </nav>

        <!--
    <div class ="container">
        <div class="row">
            <ul class="nav nav-pills">
                <li class="active"><a href="">Maaltijden</a></li>
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        Stamgegevens<span class="caret"</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="</?php echo Yii::app()->createUrl('/maaltijdtype')?>">Maaltijdtypen</a></li>
                        <li><a href="</?php echo Yii::app()->createUrl('/maaltijdsubtype')?>">Maaltijdsubtypen</a></li>
                    </ul>
                </li>
                <li><a href="</?php echo Yii::app()->createUrl('/maaltijdtype')?>">Gebruikers</a></li>
            </ul>
        
            <br>
                </?php echo $content ?>
        </div>
    </div>
        -->
        <div class="container">
            <div class="row">
                <?php echo Render::menu($this->menu, Yii::app()->request->requestUri)?>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <?php echo $content ?>
                </div>
            </div>
        </div>
        
<!--
        <nav class="navbar navbar-fixed-bottom">    
            <div class="container">
              <footer>
                <p>&copy; Brainpower Solutions 2014</p>
                </footer>
    </div> <!-- /container -->        
<!--
        </nav>
-->
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js">
        <script>window.jQuery || document.write('<script src="<?=Yii::app()->createUrl('js/vendor/jquery-1.11.0.min.js')?>"><\/script>')
<!--
        <script>window.jQuery || document.write('<script src="js/vendor/jquery-1.11.0.min.js"><\/script>')</script>
        <script src="/initializr/js/vendor/bootstrap.min.js"></script>
        <script src="/initializr/js/main.js"></script>
-->

        <script src="<?=Yii::app()->createUrl('initializr/js/vendor/bootstrap.min.js')?>"></script>
        <script src="<?=Yii::app()->createUrl('initializr/js/main.js')?>"></script>

        <!-- Google Analytics: change UA-XXXXX-X to be your site's ID. -->
        <script>
            (function(b,o,i,l,e,r){b.GoogleAnalyticsObject=l;b[l]||(b[l]=
            function(){(b[l].q=b[l].q||[]).push(arguments)});b[l].l=+new Date;
            e=o.createElement(i);r=o.getElementsByTagName(i)[0];
            e.src='//www.google-analytics.com/analytics.js';
            r.parentNode.insertBefore(e,r)}(window,document,'script','ga'));
            ga('create','UA-XXXXX-X');ga('send','pageview');
        </script>
    </body>
</html>
