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

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <?php echo $content ?>
            </div>
        </div>
    </div>

    </body>
</html>
