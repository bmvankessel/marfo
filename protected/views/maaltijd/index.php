<?php

    // add modal template to document
    echo $this->renderPartial('../general/_modal');

    echo Render::tabs(0) . CHtml::beginForm('','post', array('class'=>'form-horizontal', 'role'=>'form')) . PHP_EOL;
    echo Render::tabs(1) . CHtml::openTag('div', array('class'=>'form-group')) . PHP_EOL;
    echo Render::tabs(2) . CHtml::label('Nummer', 'nummer', array('class'=>'col-md-2 control-label')) .PHP_EOL;
    echo Render::tabs(2) . CHtml::openTag('div', array('class'=>'col-md-2')) .PHP_EOL;
    echo Render::tabs(3) . CHtml::textField('Search[code]',$code, array('placeholder'=>'<leeg>', 'type'=>'text', 'class'=>'form-control')) .PHP_EOL;
    echo Render::tabs(2) . CHtml::closeTag('div') .PHP_EOL;

    // submit button
    echo Render::tabs(2) . CHtml::openTag('div', array('class'=>'col-md-1')) . PHP_EOL;
    echo Render::tabs(2) . Chtml::submitButton('Zoek', array('class'=>'btn btn-success'));
    echo Render::tabs(2) . CHtml::closeTag('div') . PHP_EOL;
    echo Render::tabs(1) . CHtml::closeTag('div') . PHP_EOL;
    echo Render::tabs(0) . CHtml::endForm() . PHP_EOL ;


    echo Render::tabs(0) . CHtml::beginForm('','post', array('class'=>'form-horizontal', 'role'=>'form')) . PHP_EOL;
    echo Render::tabs(1) . CHtml::openTag('div', array('class'=>'form-group')) . PHP_EOL;

    // select maaltijdtype
    echo Render::tabs(2) . CHtml::label('Maaltijdtype', 'maaltijdtype', array('class'=>'col-md-2 control-label')) .PHP_EOL;
    echo Render::tabs(2) . CHtml::openTag('div', array('class'=>'col-md-3')) . PHP_EOL;
    echo Render::tabs(3) . CHtml::openTag('select', array('name'=>'Search[maaltijdtype_id]', 'id'=>'maaltijdtype', 'class'=>'form-control')) . PHP_EOL;
    $options = array('value'=>'');
    if ($selectedType == '') {
        $options['selected'] = true;
    }
    echo Render::tabs(4) . CHtml::tag('option', $options, htmlspecialchars('<leeg>'));
    foreach($maaltijdtypes as $id=>$description) {
        $options = array('value'=>$id);
        if ($selectedType == $id) {
            $options['selected'] = true;
        }
        echo Render::tabs(4) . CHtml::tag('option', $options, htmlspecialchars($description));
    }
    echo Render::tabs(3) . CHtml::closeTag('select') . PHP_EOL;
    echo Render::tabs(2) . CHtml::closeTag('div') . PHP_EOL;

    // select maaltijdsubtype
    echo Render::tabs(2) . CHtml::label('Maaltijdsubtype', 'maaltijdsubtype', array('class'=>'col-md-2 control-label')) .PHP_EOL;
    echo Render::tabs(2) . CHtml::openTag('div', array('class'=>'col-md-3')) . PHP_EOL;
    echo Render::tabs(3) . CHtml::openTag('select', array('name'=>'Search[maaltijdsubtype_id]', 'id'=>'maaltijdsubtype', 'class'=>'form-control')) . PHP_EOL;
    $options = array('value'=>'');
    if ($selectedSubType == '') {
        $options['selected'] = true;
    }
    echo Render::tabs(4) . CHtml::tag('option', $options, htmlspecialchars('<leeg>'));
    foreach($maaltijdsubtypes as $id=>$description) {
        $options = array('value'=>$id);
        if ($selectedSubType == $id) {
            $options['selected'] = true;
        }
        echo Render::tabs(4) . CHtml::tag('option', $options, htmlspecialchars($description));
    }
    echo Render::tabs(3) . CHtml::closeTag('select') . PHP_EOL;
    echo Render::tabs(2) . CHtml::closeTag('div') . PHP_EOL;

    // submit button
    echo Render::tabs(2) . CHtml::openTag('div', array('class'=>'col-md-1')) . PHP_EOL;
    echo Render::tabs(2) . Chtml::submitButton('Filter', array('class'=>'btn btn-success'));
    echo Render::tabs(2) . CHtml::closeTag('div') . PHP_EOL;

    echo Render::tabs(1) . CHtml::closeTag('div') . PHP_EOL;
    echo Render::tabs(0) . CHtml::endForm() . PHP_EOL ;

    $template = array();
    $template[] = "{view}";
    $template[] = "{pdf}";
    $template[] = "{update}";
    $template[] = "{copy}";
    $template[] = "{delete}";

    $template = implode(' ', $template);
$test = 'barry';
    $this->widget('zii.widgets.grid.CGridView', array(
        'ajaxType'=>'post',
//	'dataProvider'=>$dataProvider,
        'dataProvider'=>$maaltijd->searchInternal(),
        'emptyText'=>'Er zijn geen maaltijden',
        'summaryText'=>'<h1>Maaltijden <span style="font-size:12px">{start} t/m {end} van {count}<span></h1>',
        'pagerCssClass'=>'pagination',
        'summaryCssClass'=>'summary',
        'pager'=>array(
            'header' => 'Ga naar pagina',
            'firstPageLabel'  => '&lt;&lt;',
            'prevPageLabel'  => '< Vorige',
            'nextPageLabel'  => 'Volgende >',
            'lastPageLabel'  => '&gt;&gt;',
        ),
        'template'=>'{summary}{items}{pager}',
        'columns'=>array (
            array(
                'header'=>'Actie',
                'class'=>'CButtonColumn',
                'template'=>$template,
                'headerHtmlOptions'=>array('style'=>'width:75px'),
                'headerHtmlOptions'=>[
					'class'=>'action',
                ],
                'htmlOptions'=>array('style'=>'width:75px'),
                'htmlOptions' => [
					'class' => 'action',
				],
                'buttons'=>array(
                    'view'=>array(
                        'label'=>'',
                        'imageUrl'=>'',
                        'options'=>array('class'=>'glyphicon glyphicon-search'),
                    ),
                    'pdf'=>array(
                        'label'=>'',
                        'imageUrl'=>'',
                        'url'=>'',
                        'options'=>array('class'=>'fa fa-file-pdf-o'),
                        'click'=>'js:function(evt){evt.preventDefault();createPdf($(this));}',
                    ),
                    'update'=>array(
                        'label'=>'',
                        'imageUrl'=>'',
                        'options'=>array('class'=>'glyphicon glyphicon-pencil'),
                    ),
					'copy' => [
						'label' => '',
						'imageUrl' => '',
						'url'=> 'Yii::app()->controller->createUrl("copy", ["id"=>$data->id])',
						'options'=>array('class'=>'glyphicon glyphicon-duplicate'),
					],
                    'delete'=>array(
                        'label'=>'',
                        'imageUrl'=>'',
                        'url'=>'',
                        'options'=>array('class'=>'glyphicon glyphicon-trash'),
                        'click'=>'js:function(evt){evt.preventDefault();deleteMaaltijd($(this));}',
                    ),
                ),
            ),
            array(
                'name'=>'Code',
                'value'=>'$data->code',
                'sortable'=>false
            ),
            array(
                'name'=>'Omschrijving',
                'value'=>'$data->omschrijving',
                'sortable'=>false
            ),
            array(
                'name'=>'Id',
                'headerHtmlOptions'=>array('class'=>'hidden'),
                'htmlOptions'=>array('name'=>'id', 'class'=>'hidden'),
                'value'=>'$data->id',
                'visible'=>'false',
                'sortable'=>false,
            ),
        )


));

    echo CHtml::tag('button', array( 'type'=>'button','role'=>'maaltijd-add','class'=>'btn btn-success'), 'Nieuwe Maaltijd');

    $this->renderPartial('_create');

    Yii::app()->clientScript->registerScriptFile($this->createUrl('js/pdf.js'));
    Yii::app()->clientScript->registerScriptFile($this->createUrl('js/maaltijd.js'));
?>
