<?php

    $template[] = "{up}";        
    $template[] = "{down}";        
    $template[] = "{update}";        
    $template[] = "{del}";        
    $template = implode(' ', $template);

    $this->widget('zii.widgets.grid.CGridView', array(
	'dataProvider'=>$dataProvider,
        'emptyText'=>'Er zijn geen maaltijdzoekfilters',
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
                        'url'=>'',
                        'options'=>array(
                            'class'=>'glyphicon glyphicon-pencil',
                            'title'=>'Wijzigen',
                        ),
                        'click'=>'js:function(evt){evt.preventDefault();openModalMaaltijdfilterUpdate($(this));}',
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
                'htmlOptions'=>array('class'=>'col-md-2'),
                'name'=>'Productgroep',
                'value'=>'$data->productgroep->omschrijving',
                'htmlOptions'=>array('name'=>'productgroep'),
            ),
            array(
                'htmlOptions'=>array('class'=>'col-md-2'),
                'name'=>'Maaltijdtype',
                'value'=>'$data->maaltijdtype->omschrijving',
                'htmlOptions'=>array('name'=>'maaltijdtype'),
            ),
            array(
                'htmlOptions'=>array('class'=>'col-md-2'),
                'name'=>'Maaltijdsubtype',
                'value'=>'substr($data->maaltijdsubtype->omschrijving,0,50)',
                'htmlOptions'=>array('name'=>'maaltijdsubtype'),
            ),
            array(
                'htmlOptions'=>array('class'=>'col-md-5'),
                'name'=>'Tooltip',
                'value'=>'$data->tooltip',
                'htmlOptions'=>array('name'=>'tooltip'),
            ),
            array(
                'name'=>'Id',
                'headerHtmlOptions'=>array('class'=>'hidden'),
                'htmlOptions'=>array('name'=>'id', 'class'=>'hidden'),
                'value'=>'$data->id',
                'visible'=>'false',
            ),
            array(
                'name'=>'Productgroep Id',
                'headerHtmlOptions'=>array('class'=>'hidden'),
                'htmlOptions'=>array('name'=>'productgroep-id', 'class'=>'hidden'),
                'value'=>'$data->productgroep_id',
                'visible'=>'false',
            ),
            array(
                'name'=>'Maaltijdtype Id',
                'headerHtmlOptions'=>array('class'=>'hidden'),
                'htmlOptions'=>array('name'=>'maaltijdtype-id', 'class'=>'hidden'),
                'value'=>'$data->maaltijdtype_id',
                'visible'=>'false',
            ),
            array(
                'name'=>'Maaltijdsubtype Id',
                'headerHtmlOptions'=>array('class'=>'hidden'),
                'htmlOptions'=>array('name'=>'maaltijdsubtype-id', 'class'=>'hidden'),
                'value'=>'$data->maaltijdsubtype_id',
                'visible'=>'false',
            ),
            array(
                'name'=>'sequence',
                'headerHtmlOptions'=>array('class'=>'hidden'),
                'htmlOptions'=>array('name'=>'sequence', 'class'=>'hidden'),
                'value'=>'$data->sequence',
                'visible'=>'false',
            ),
        )
));
    
    echo CHtml::openTag('div', array('class'=>'row'));
    echo CHtml::openTag('div', array('class'=>'col-md-12'));
    
    echo CHtml::button('Nieuw zoekfilter', array(
		'class'=>'btn btn-success',
		'id'=>'btn-maaltijdfilter-add',
    ));
    
    echo CHtml::closeTag('div');    
    echo CHtml::closeTag('div');

	$options = Productgroep::model()->findAll(array('order'=>'omschrijving'));
	$productgroepOptions = array();
	foreach ($options as $option) {
		$productgroepOptions[$option->id] = $option->omschrijving;
	}

	$options = Maaltijdtype::model()->findAll(array('order'=>'omschrijving'));
	$maaltijdtypeOptions = array();
	foreach ($options as $option) {
		$maaltijdtypeOptions[$option->id] = $option->omschrijving;
	}

	$options = Maaltijdsubtype::model()->findAll(array('order'=>'omschrijving'));
	$maaltijdsubtypeOptions = array();
	foreach ($options as $option) {
		$maaltijdsubtypeOptions[$option->id] = $option->omschrijving;
	}

	$this->renderPartial('create_update', array(
		'productgroepOptions'=>$productgroepOptions,
		'maaltijdtypeOptions'=>$maaltijdtypeOptions,
		'maaltijdsubtypeOptions'=>$maaltijdsubtypeOptions,
	));
