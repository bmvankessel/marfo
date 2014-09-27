<div class="header-with-icon">
    <h1>Maaltijd&nbsp;</h1><span id="<?=$model->id?>" role="create-pdf" class="fa fa-file-pdf-o fa-lg" title="Aanmaken PDF"></span>
</div>

<?php
    $options = array();
    if ($updateAllowed) {
        $options['updateButtons'] = true;
    }

    // set update action
    $options['action'] = $this->createUrl('update', array('id'=>$model->id));
    
    function addField($model, $field) {
        $values = array();
        $values[$field] = $model->$field;
        return  $values;
    }

    function addVoedingswaarde($model, $field, $field1=null, $field2=null) {
        $values = array();
        if ($field) {
            $field1 = "vw_100_$field";
            $field2 = "vw_compleet_$field";
        };

        if ($field1) {
            $values[$field1] = $model->$field1;
        } else {
            $values[''] = null;
        }
        
        if ($field2) {
            $values[$field2] = $model->$field2;
        } else {
            $values[''] = null;            
        }

        return $values;
    }

    $attributes = array();
    $attribute = array();
    $attribute['label'] = 'type';
    $attribute['values'][] = addField($model, 'maaltijdtype_id');
    $attribute['valueOptions'] = $typeDescriptions;
    $attribute['display']['displayAs'] = 'dropdown';
    $attributes[] = $attribute;

    $attribute = array();
    $attribute['label'] = 'Maaltijdsubtype';
    $attribute['values'][] = addField($model, 'maaltijdsubtype_id');
    $attribute['valueOptions'] = $subTypeDescriptions;
    $attribute['display']['displayAs'] = 'dropdown';
    $attributes[] = $attribute;

    foreach($model->productAttributes() as $modelAttribute) {
        $attribute = array();
        $label = "label$modelAttribute";
        $attribute['label'] = $model->$label;
        $attribute['values'] = array();
        $attribute['values'][] = addField($model, $modelAttribute);
        $attributes[] = $attribute;
    }  

    $attribute = array();
    $attribute['label'] = 'Gaarheid';
    $attribute['values'][] = addField($model, 'gaarheid');
    $attribute['valueOptions'] = array(
        0=>'',
        1=>'+',
        2=>'++',
        3=>'+++',
    );
    $attribute['display']['displayAs'] = 'dropdown';
    $attributes[] = $attribute;    
    
    echo Render::product($attributes, $options);

    echo Render::ingredienten($model, 'ingredientendeclaratie', $options);

 
    $attributes = array();
    
    foreach($model->bereidingswijzeAttributes() as $modelAttribute) {
        $attribute = array();
        $label = "label$modelAttribute";
        $attribute['label'] = $model->$label;
        $attribute['values'] = array();
        $attribute['values'][] = addField($model, $modelAttribute);
        $attributes[] = $attribute;
    }
    
//    foreach($model->bereidingAttributes() as $modelAttribute) {
//        $attribute = array();
//        $label = "label$modelAttribute";
//        $attribute['label'] = $model->$label;
//        $attribute['values'][] = addField($model, $modelAttribute);
//        $attribute['valueOptions'] = array(
//            0=>'',
//            1=>'+',
//            2=>'++',
//            3=>'+++',
//        );
//        $attribute['display']['displayAs'] = 'dropdown';
//        $attributes[] = $attribute;
//    }
    echo Render::bereiding($attributes, $options);
        
    $attributes = array();
    foreach($model->claimAttributes() as $modelAttribute) {
        $attribute = array();
        $label = "label$modelAttribute";
        $attribute['label'] = $model->$label;
        $attribute['values'] = array();
        $attribute['values'][] = addField($model, $modelAttribute);
        $attributes[] = $attribute;
    }
    echo Render::claims($attributes, $options);

    $voedingswaarden = array();
    foreach($model->voedingswaardeAttributes() as $modelAttribute) {
        $voedingswaarde = array();
        $label = "label$modelAttribute";
        $voedingswaarde['label'] = $model->$label;
        $field = substr($modelAttribute, strlen('vw_100_'));
        $voedingswaarde['values'] = addVoedingswaarde($model,$field);
        $voedingswaarden[] = $voedingswaarde;
    }    
    echo Render::voedingswaarden($voedingswaarden, $options);   

    $allergenen = array();
    foreach($model->allergenenAttributes() as $attribute) {
        $allergeen = array();
        $label = "label$attribute";
        $allergeen['label'] = $model->$label;
        $allergeen['values'] = array();
        $allergeen['values'][] = addField($model, $attribute);
        $allergenen[] = $allergeen;
    }    
    echo Render::allergenen($allergenen, $options);

    $componenten = array();
    foreach($model->componentAttributes() as $attribute) {
        $component = array();
        $label = "label$attribute";
        $component['label'] = $model->$label;
        $component['values'] = array();
        $component['values'][] = addField($model, $attribute);
        $componenten[] = $component;
    }    
    echo Render::componenten($componenten, $options);

    echo Render::opmerkingen($model, 'opmerking', $options);
    
//    $source = Yii::app()->createUrl('/js/dialog.js');
//    Yii::app()->clientScript->registerScriptFile($source, CClientScript::POS_END);
//    $source = Yii::app()->createUrl('/js/jquery.form.min.js');
//    Yii::app()->clientScript->registerScriptFile($source, CClientScript::POS_END);
    $source = Yii::app()->createUrl('/js/updatable.js');
    Yii::app()->clientScript->registerScriptFile($source, CClientScript::POS_END);
    
//    $script = <<<JS
//        $("#voedingswaarden").unbind("click").find("button[role=save]").click(function(){
////            alert('yes!');
////            var panel = $(this).closest(".mode-edit");
////            var form = panel.find('form');
////            update(JSON.stringify(formData(form)), form.attr('action'), panel);
//});
//JS;
//    
//   Yii::app()->clientScript->registerScript('', $script); 

    Yii::app()->clientScript->registerScriptFile($this->createUrl('js/pdf.js'));    
    Yii::app()->clientScript->registerScriptFile($this->createUrl('js/maaltijd_update.js'));        
?>