<?php
    // add modal template to document
    echo $this->renderPartial('../general/_modal');
    
    $template = array();
    $template[] = "{update}";        
    $template[] = "{delete}";        
    $template = implode(' ', $template);

    $this->widget('zii.widgets.grid.CGridView', array(
	'dataProvider'=>$dataProvider,
        'emptyText'=>'Er zijn geen typen',
        'summaryText'=>'<h1>Maaltijdsubtypen <span style="font-size:12px">{start} t/m {end} van {count}<span></h1>',
        'pagerCssClass'=>'pagination',
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
                'buttons'=>array(
                    'view'=>array(
                        'label'=>'',
                        'imageUrl'=>'',
                        'options'=>array('class'=>'glyphicon glyphicon-search'),
                    ),
                    'update'=>array(
                        'label'=>'',
                        'imageUrl'=>'',
                        'url'=>'',
                        'options'=>array('class'=>'glyphicon glyphicon-pencil'),
                        'click'=>'js:function(evt){evt.preventDefault();updateMaaltijdSubType($(this));}',
                    ),
                    'delete'=>array(
                        'label'=>'',
                        'imageUrl'=>'',
                        'url'=>'',
                        'options'=>array('class'=>'glyphicon glyphicon-trash'),
                        'click'=>'js:function(evt){evt.preventDefault();deleteMaaltijdSubType($(this));}',
                    ),
                ),
            ),
            array(
                'name'=>'Omschrijving',
                'value'=>'$data->omschrijving',
                'sortable'=>true
            ),
            array(
                'name'=>'Id',
                'headerHtmlOptions'=>array('class'=>'hidden'),
                'htmlOptions'=>array('name'=>'id', 'class'=>'hidden'),
                'value'=>'$data->id',
                'visible'=>'false',
                'sortable'=>true
            ),
        )
));

    echo CHtml::tag('button', array( 'type'=>'button','role'=>'maaltijdsubtype-add','class'=>'btn btn-success'), 'Nieuw Subtype');

    $this->renderPartial('_create');
            
    Yii::app()->clientScript->registerScriptFile($this->createUrl('js/maaltijdsubtype.js'));        
?>