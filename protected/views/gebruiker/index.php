<?php 
    // add modal template to document
    echo $this->renderPartial('../general/_modal');

    $template = array();
    
    if (!Yii::app()->user->isGuest) {
        $template[] = "{update}";        
        $template[] = "{delete}";        
    }

    $template = implode(' ', $template);
    
    $this->widget('zii.widgets.grid.CGridView', array(
        'ajaxType'=>'post',
	'dataProvider'=>$dataProvider,
        'emptyText'=>'Er zijn geen gebruikers',
        'summaryText'=>'<h1>Gebruikers <span style="font-size:12px">{start} t/m {end} van {count}<span></h1>',
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
//                'headerHtmlOptions'=>array('style'=>'width:75px'),
//                'htmlOptions'=>array('style'=>'width:75px'),
                'buttons'=>array(
                    'view'=>array(
                        'label'=>'',
                        'imageUrl'=>'',
                        'options'=>array('class'=>'glyphicon glyphicon-search'),
                    ),
                    'update'=>array(
                        'label'=>'',
                        'imageUrl'=>'',
                        'options'=>array('class'=>'glyphicon glyphicon-pencil'),
                    ),
                    'delete'=>array(
                        'label'=>'',
                        'imageUrl'=>'',
//                        'url'=>'',
                        'options'=>array('class'=>'glyphicon glyphicon-trash'),
                        'click'=>'js:function(evt){evt.preventDefault();deleteGebruiker($(this));}',
                    ),
                ),
            ),
            array(
                'name'=>'Achternaam',
                'value'=>'$data->achternaam',
            ),
            array(
                'name'=>'Gebruikersnaam',
                'value'=>'$data->gebruikersnaam',
            ),
            array(
                'name'=>'Id',
                'headerHtmlOptions'=>array('class'=>'hidden'),
                'htmlOptions'=>array('name'=>'id', 'class'=>'hidden'),
                'value'=>'$data->id',
                'sortable'=>true,
            ),
        )
        

));
    
    echo CHtml::linkButton('Nieuwe Gebruiker',array('href'=>$this->createUrl('update'), 'type'=>'button','role'=>'gebruiker-add','class'=>'btn btn-success'));

    Yii::app()->clientScript->registerScriptFile($this->createUrl('js/gebruiker.js'));    
?>