<?php
    echo Render::dialogForm(
        $this->createUrl('ajaxCreate'),
        'post',
        array(
            'fields' => array(
                array(
                    'label' => 'Omschrijving',
                    'name' => 'omschrijving',
                    'required' => true,
                ),
            ),
            'id'=>'dialog-template-maaltijdsubtype',
            'buttons' => array(
                array(
                    'label'=>'Toevoegen',
                    'role'=>'maaltijdsubtype-create'
                ),
                array(
                    'label'=>'Sluiten',
                    'role'=>'cancel'
                ),
            )
        )
    );
    
    
    Yii::app()->clientScript->registerScriptFile(Yii::app()->createUrl('js/dialog.js'));
?>