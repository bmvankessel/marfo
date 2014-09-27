<?php

class BaseLookupController extends Controller
{
    private $modelname;

    private function foreignKey() {
        return strtolower($this->modelname) . '_id';
    }

    /* returns the number of maaltijden referencing the productgroep */
    public function actionRelatedParents() {
        $data = json_decode($_POST['data'],true);
        $id = $data['id'];
        $result = array();
        $result['status'] = 'not ok';
        $foreignKey = $this->foreignKey();
        $modelCount = Maaltijd::model()->count("$foreignKey=$id");
        $result['status'] = 'ok';
        $result['parentCount'] = $modelCount;

        echo json_encode($result);
        Yii::app()->end();        
    }

    /* creates a new productgroep  */
    public function actionAjaxCreate() {
        $result = array();
        $result['status'] = 'not ok';
        $result['action'] = 'update';

        if (isset($_POST['attributes'])) {
            $attributes = json_decode($_POST['attributes'], true);
            
            $model = new $this->modelname('insert');
            $model->setAttributes($attributes, false);

            if ($model->save(false)) {
                $result['id'] = $model->id;
                $result['status'] = 'ok';
            } else {
                $result['message'] = 'Save failed';                
            }
        } else{
            $result['message'] = 'No attributes posted';
        }
        
        echo json_encode($result);
        Yii::app()->end();        
    }

    /* updates an existing productgroep */
    public function actionAjaxUpdate()
    {
        $result = array();
        $result['status'] = 'not ok';
        $result['action'] = 'update';

        if (isset($_POST['attributes'])) {
            $attributes = json_decode($_POST['attributes'], true);

            $model=$this->loadModel($attributes['id']);
            $model->omschrijving = $attributes['omschrijving'];

            if ($model->save()) {
                $result['status'] = 'ok';
            } else {
                $result['message'] = 'Save failed';
            }
        } else {
            $result['message'] = 'No attributes posted';            
        }

        echo json_encode($result);
        Yii::app()->end();        
    }

    /* deletes a productgroup */
    public function actionDelete() {
        $result = array();
        $result['status'] = 'not ok';
        $data = json_decode($_POST['data'],true);
        $id = $data['id'];

        $foreignKey = $this->foreignKey;
        Maaltijd::model()->updateAll(array($foreignKey=>null), "$foreignKey=$id");
        $modelsDeleted =  $this->modelname::model()->deleteByPk($id);
        
        $modelname = strtolower($this->modelname);
        switch($modelsDeleted) {
            case 0:
                $result ['message'] = "Record $modelname [id=$id] was not deleted.";
                break;

            case 1:
                $result['status'] = 'ok';        
                break;

            default:
                $result ['message'] = "Multiple records of '$modelname' [id=$id] are deleted.";
                break;
        }

        echo json_encode($result);
        Yii::app()->end();        
    }

    /* dpslays all productgroep models  */
    public function actionIndex()
    {
        echo 'Index';die;
        $this->menu = Yii::app()->menuManager->getMain();
        $dataProvider=new CActiveDataProvider($this->modelname);

        var_dump('Barry');die;

        $this->render('index',array('dataProvider'=>$dataProvider));
    }

    /* returns the model identified by the id */
    public function loadModel($id)
    {
        $model=$this->modename::model()->findByPk($id);
        if($model===null) {
            throw new CHttpException(404,'The requested page does not exist.');
        }
        return $model;
    }

    /* validates the model attribute values */
    protected function performAjaxValidation($model)
    {
        $formname = strtolower($this->modelname) . '-form';
        if(isset($_POST['ajax']) && $_POST['ajax']===$formname)
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}
