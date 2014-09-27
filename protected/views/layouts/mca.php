<?php $this->beginContent('//layouts/main');?>

<div class="container">
    <div class="row">
        <?php echo Render::renderMenu($this->menu, Yii::app()->request->requestUri)?>
    </div>
    <div class="row">
        <div class="col-md-9">
            <?php echo $content ?>
        </div>
        <div class="col-md-3">
            <?php echo Render::renderAside($this->aside) ?>
        </div>
    </div>
</div>    
    
<?php $this->endContent();?>