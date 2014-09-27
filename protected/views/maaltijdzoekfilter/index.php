<?php

    $template[] = "{up}";        
    $template[] = "{down}";        
    $template[] = "{update}";        
    $template[] = "{del}";        
    $template = implode(' ', $template);

    $this->widget('zii.widgets.grid.CGridView', array(
	'dataProvider'=>$dataProvider,
        'emptyText'=>'Er zijn geem maaltijdzoekfilters',
        'summaryText'=>'<h1>Maaltijdzoekfilters <span style="font-size:12px">{start} t/m {end} van {count}<span></h1>',
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
                'htmlOptions'=>array('class'=>'col-md-1'),
                'template'=>$template,
                'buttons'=>array(
                    'update'=>array(
                        'label'=>'',
                        'imageUrl'=>'',
                        'url'=>'$this->grid->controller->createUrl("update", array("id"=>$data->id))',
                        'options'=>array(
                            'class'=>'glyphicon glyphicon-pencil',
                            'title'=>'Wijzigen',
                        ),
                    ),
                    'del'=>array(
                        'label'=>'',
                        'imageUrl'=>'',
                        'url'=>'$this->grid->controller->createUrl("delete", array("id"=>$data->id))',
                        'options'=>array(
                            'class'=>'glyphicon glyphicon-trash',
                            'title'=>'Verwijderen',
                        ),
                    ),
                    'up'=>array(
                        'label'=>'',
                        'imageUrl'=>'',
                        'url'=>'$this->grid->controller->createUrl("up", array("id"=>$data->id))',
                        'options'=>array(
                            'class'=>'fa fa-arrow-circle-up fa-lg',
                            'title'=>'Verplaats een regel naar boven',
                        ),
                    ),
                    'down'=>array(
                        'label'=>'',
                        'imageUrl'=>'',
                        'url'=>'$this->grid->controller->createUrl("down", array("id"=>$data->id))',
                        'options'=>array(
                            'class'=>'fa fa-arrow-circle-down fa-lg',
                            'title'=>'Verplaats een regel naar beneden'
                        ),
                    ),
                ),
            ),
            array(
                'htmlOptions'=>array('class'=>'col-md-3'),
                'name'=>'Maaltijdtype',
                'value'=>'$data->maaltijdtype->omschrijving',
                'sortable'=>true
            ),
            array(
                'htmlOptions'=>array('class'=>'col-md-3'),
                'name'=>'Maaltijdsubtype',
                'value'=>'substr($data->maaltijdsubtype->omschrijving,0,50)',
                'sortable'=>true
            ),
            array(
                'htmlOptions'=>array('class'=>'col-md-5'),
                'name'=>'Tooltip',
                'value'=>'$data->tooltip',
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
            array(
                'name'=>'sequence',
                'headerHtmlOptions'=>array('class'=>'hidden'),
                'htmlOptions'=>array('name'=>'sequence', 'class'=>'hidden'),
                'value'=>'$data->sequence',
                'visible'=>'false',
                'sortable'=>true
            ),
        )
));
    
    echo CHtml::openTag('div', array('class'=>'row'));
    echo CHtml::openTag('div', array('class'=>'col-md-12'));
    
    echo CHtml::link('Nieuw zoekfilter', $this->createUrl('update'), array('class'=>'btn btn-success'));
    echo CHtml::closeTag('div');    
    echo CHtml::closeTag('div');