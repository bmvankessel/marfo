<?php
    echo Render::dialogForm(
        $this->createUrl('ajaxCreate'),
        'post',
        array(
            'fields' => array(
                array(
                    'label' => 'Code',
                    'name' => 'code',
                    'required' => true,
                ),
                array(
                    'label' => 'Omschrijving',
                    'name' => 'omschrijving',
                    'required' => true,
                ),
            ),
            'id'=>'dialog-template-maaltijd',
            'buttons' => array(
                array(
                    'label'=>'Toevoegen',
                    'role'=>'maaltijd-create'
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