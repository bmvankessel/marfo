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

        <link rel="stylesheet" href="<?=Yii::app()->createUrl('jquery-ui/css/ui-lightness/jquery-ui-1.10.4.min.css')?>">
        <link rel="stylesheet" href="<?=Yii::app()->createUrl('bootstrap/css/bootstrap.min.css')?>">
        <link rel="stylesheet" href="<?=Yii::app()->createUrl('bootstrap/css/bootstrap-theme.min.css')?>">
        <link rel="stylesheet" href="<?=Yii::app()->createUrl('initializr/css/main.css')?>">
        <link rel="stylesheet" href="<?=Yii::app()->createUrl('css/custom.css')?>">
        <link href="//netdna.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">

<!--        <script src="<?=Yii::app()->createUrl('jquery/jquery-1.11.1.min.js')?>"></script>-->
        <script src="<?=Yii::app()->createUrl('jquery-ui/js/jquery-ui-1.10.4.min.js')?>"></script>
        <script src="<?=Yii::app()->createUrl('bootstrap/js/bootstrap.min.js')?>"></script>
        <script src="<?=Yii::app()->createUrl('initializr/js/vendor/modernizr-2.6.2-respond-1.1.0.min.js')?>"></script>
        <script src="<?=Yii::app()->createUrl('js/utils.js')?>"></script>
    <!--[if lte IE 9]>
            <script src="<?=Yii::app()->createUrl('js/placeholder.js')?>"></script>
    <![endif]-->        

            <?php echo CHtml::hiddenField('url', Yii::app()->getBaseUrl(true)) ?>

        <style>
            body {
                padding-top: 50px;
                padding-bottom: 20px;
            }
        </style>


    </head>

<body>
    <!--[if lt IE 7]>
        <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
    <![endif]-->

<?php
    function isOnPage($page) {
        $page = strtolower($page);
        $page = explode('/', $page);

        $lastPart = count($page)-1;
        $page = $page[$lastPart];

        $currentPage = strtolower(Yii::app()->request->requestUri);
        $currentPage = explode('/', $currentPage);
        $currentPage = $currentPage[$lastPart];

        return ($currentPage === $page);
    }
?>

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

                <div>
                    <ul class="nav navbar-nav">
                        <?php
                            $menuItems = array();

//                            if (!Yii::app()->user->isGuest) {
//                                $menuItems['Maaltijden'] = Yii::app()->createUrl('maaltijd');
//                                $menuItems['Maaltijdtypen'] = Yii::app()->createUrl('maaltijdtype');
//                                $menuItems['Maaltijdsubtypen'] = Yii::app()->createUrl('maaltijdsubtype');
//                                $menuItems['Zoekpagina'] = Yii::app()->createUrl('search');
//                                $menuItems['Instelling Zoekpagina'] = Yii::app()->createUrl('maaltijdzoekfilter');
//                            }
//
//                            foreach($menuItems as $title=>$url) {
//                                if (isOnPage($url)) {
//                                    $active = 'active';
//                                } else {
//                                    $active = '';
//                                }
//                                echo "<li class=\"$active\"><a href=\"$url\">$title</a></li>";
//                            }
                            if (!Yii::app()->user->isGuest) {
                                $menuItems[] = array(
                                    'title'=>'Maaltijden',
                                    'type'=>'item',
                                    'url'=>Yii::app()->createUrl('maaltijd'),
                                );
                                $menuItems[] = array(
                                    'title'=>'Maaltijdtypen',
                                    'type'=>'item',
                                    'url'=>Yii::app()->createUrl('maaltijdtype'),
                                );
                                $menuItems[] = array(
                                    'title'=>'Maaltijdsubtypen',
                                    'type'=>'item',
                                    'url'=>Yii::app()->createUrl('maaltijdsubtype'),
                                );
                                $menuItems[] = array(
                                    'title'=>'Zoekpagina',
                                    'type'=>'list',
                                    'items'=>array(
                                        array(
                                            'title'=>'Ga naar zoekpagina',
                                            'url'=>Yii::app()->createUrl('search'),
                                        ),
                                        array(
                                            'title'=>'Maaltijdzoekfilters',
                                            'url'=>Yii::app()->createUrl('maaltijdzoekfilter'),
                                        ),
                                    ),
                                );
                                
                                if (key_exists('admin', Yii::app()->authManager->getRoles(Yii::app()->user->id))) {                                
                                    $menuItems[] = array(
                                        'title'=>'Gebruikersbeheer',
                                        'type'=>'item',
                                        'url'=>Yii::app()->createUrl('gebruiker'),
                                    );
                                }
                            }

                            foreach($menuItems as $menuItem) {
                                $title = $menuItem['title'];
                                if ($menuItem['type'] == 'item') {
                                    $url = $menuItem['url'];
                                    if (isOnPage($url)) {
                                        $active = 'active';
                                    } else {
                                        $active = '';
                                    }
                                    echo "<li class=\"$active\"><a href=\"$url\">$title</a></li>";
                                } else {
                                    $class = array('dropdown');
                                    // check if one of the items refers to this page
                                    foreach($menuItem['items'] as $item) {
                                        $url = $item['url'];
                                        if (isOnPage($url)) {
                                            $class[] = 'active';
                                            break;
                                        }
                                    }
                                    echo '<li class="' . implode(' ', $class) . '">';
                                    echo "<a href=\"#\" class=\"dropdown-toggle\" data-toggle=\"dropdown\">$title <b class=\"caret\"></b></a>";
                                    echo '<ul class="dropdown-menu">';
                                    foreach($menuItem['items'] as $item) {
                                        $title = $item['title'];
                                        $url = $item['url'];
                                        if (isOnPage($url)) {
                                            $class='active';
                                        } else {
                                            $class='';
                                        }
                                        echo "<li class=\"$class\"><a href=\"$url\">$title</a></li>";
                                    }
                                    echo '</ul>';
                                    echo '</li>';
                                }
                            }
                        ?>
                    </ul>
                </div>

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

            </div><!--/.navbar-collapse -->
        </div>
    </nav>

    <div class="container">
<!--
        <div class="row">
            <?php echo Render::menu($this->menu, Yii::app()->request->requestUri)?>
        </div>
-->
        <div class="row">
            <div class="col-md-12">
                <?php echo $content ?>
            </div>
        </div>
    </div>

    </body>
</html>
