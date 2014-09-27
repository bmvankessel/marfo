<?php 
    $this->beginContent('//layouts/main');
?>

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
    
<?php $this->endContent();?>