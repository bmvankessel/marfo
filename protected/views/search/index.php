<div class="row test">
    <div class="col-md-2">
        <h1>Producten</h1>
    </div>
    <div class="col-md-3">
<?php
    echo CHtml::beginForm();
    echo CHtml::textField('Search[code]', $selectedCode, array('class'=>'searchInput', 'placeholder'=>'op nummer...'));
    echo CHtml::tag('input', array('id'=>'search-code', 'type'=>'submit', 'class'=>'searchButton', 'value'=>$selectedCode));
    echo CHtml::endForm();
?>
    </div>
    <div class="col-md-3">
<?php
    echo CHtml::beginForm();
    echo CHtml::textField('Search[omschrijving]', $selectedDescription, array('class'=>'searchInput', 'placeholder'=>'op naam...'));
    echo CHtml::tag('input', array('id'=>'search-code', 'type'=>'submit', 'class'=>'searchButton'));
    echo CHtml::endForm();
?>
    </div>
    <div class="col-md-6">
    </div>
</div>

<div class="row">
    <div class="col-md-2 search">

        <?php
            echo Render::searchNavigation($model->searchNavigation($selectedMenu));
        ?>

    </div>
    <div class="col-md-10">

<?php

    $description = '"<h1 class=\"meal\" title=\"Aanmaken PDF\">" . $data->omschrijving . "</h1>" .';
    $description .= '"<h2>" . $data->code . "</h2>" .';
    $description .= '"<p class=\"hidden\">" . htmlspecialchars($data->ingredientendeclaratie . " ") . "<a class=\"pdf\" onclick=\"createPdf($data->id)\">Meer informatie <span id=\"$data->id\" class=\"fa fa-file-pdf-o fa-lg\"></span></a></p>"';

    $image = '';

    $image = '"<img class=\"meal\" title=\"Aanmaken PDF\" src=\"" . Yii::app()->createUrl("maaltijd-img") . "/" . ((file_exists(Yii::getPathOfAlias("maaltijdimg") . "/" . $data->code . ".jpg")) ? $data->code .".jpg" : "no-image.png") ."\">"';


    $this->widget('zii.widgets.grid.CGridView', array(
        'id'=>'result-grid',
        'itemsCssClass'=>'dummy',
        'rowCssClass'=>array('row'),
        'ajaxType'=>'post',
        'afterAjaxUpdate'=>"function() {dotdotdot();assignPdfCreation();}",
        'hideHeader'=>true,//	'dataProvider'=>$dataProvider,
        'dataProvider'=>$model->search(),
        'filter'=>$model,
        'emptyText'=>'Er zijn geen maaltijden gevonden die voldoen aan uw zoekopdracht',
        'summaryText'=>'({count} resultaten)',
        'pagerCssClass'=>'result-pagination',
        'summaryCssClass'=>'summary',
        'pager'=>array(
            'header' => 'pag',
            'maxButtonCount'=>5,
            'prevPageLabel'  => '<',
            'nextPageLabel'  => '>',
        ),
        'template'=>'<div class="row"><div class="col-md-3">{summary}</div><div class="col-md-9">{pager}</div></div>{items}',
        'columns'=>array (
            array(
                'type'=>'raw',
                'value'=>$image,
                'htmlOptions'=>array('class'=>'col-md-1'),
            ),
            array(
               'type'=>'raw',
               'value'=>$description,
               'htmlOptions'=>array('class'=>'col-md-11 dotted'),
               ),
        )
    )
);

    Yii::app()->clientScript->registerScriptFile(Yii::app()->createUrl('js/search.js'));

    $script = <<<dot
            function dotdotdot() {

//            alert($(".dotted p").length);
            $(".dotted p.hidden").each(function() {
                $(this).removeClass("hidden");
                $(this).dotdotdot({after: 'a.pdf'});
            });
//            $(".dotted p").dotdotdot();
//            alert('Hello');
            }

            dotdotdot();
dot;

    Yii::app()->clientScript->registerScript('dotted', $script, CClientScript::POS_READY);

    Yii::app()->clientScript->registerScriptFile('//cdn.jsdelivr.net/jquery.dotdotdot/1.6.13/jquery.dotdotdot.min.js',  CClientScript::POS_END );
?>
    </div><!-- second column-->
</div>
