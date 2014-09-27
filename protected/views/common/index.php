<?php 
    // add modal template to document
    echo $this->renderPartial('../general/_modal');

    $this->widget('zii.widgets.grid.CGridView', array(
		'dataProvider'=>$dataProvider,
			'emptyText'=>$messageNoValues,
			'summaryText'=>'<h1>' . $title . '<span style="font-size:12px">{start} t/m {end} van {count}<span></h1>',
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
					'template'=>'{update}{delete}',
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
							'click'=>'js:function(evt){evt.preventDefault();updateMaaltijdType($(this));}',
						),
						'delete'=>array(
							'label'=>'',
							'imageUrl'=>'',
							'url'=>'',
							'options'=>array('class'=>'glyphicon glyphicon-trash'),
							'click'=>'js:function(evt){evt.preventDefault();deleteMaaltijdType($(this));}',
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
		)
	);

    echo CHtml::tag('button', array( 'type'=>'button','role'=>'lookup-add','class'=>'btn btn-success'), $captionButtonAdd);

    $this->renderPartial('_create');

    Yii::app()->clientScript->registerScriptFile($this->createUrl('js/productgroep.js'));
?>
