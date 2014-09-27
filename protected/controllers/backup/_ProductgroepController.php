<?php

class ProductgroepController extends Controller
{
    /* returns the number of maaltijden referencing the productgroep */
    public function actionRelatedParents() {
        $data = json_decode($_POST['data'],true);
        $id = $data['id'];
        $result = array();
        $result['status'] = 'not ok';
        $modelCount = Maaltijd::model()->count("productgroep_id=$id");
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
            
            $model = new Productgroep('insert');
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

        Maaltijd::model()->updateAll(array('productgroep_id'=>null), "productgroep_id=$id");
        $modelsDeleted =  Productgroep::model()->deleteByPk($id);

        switch($modelsDeleted) {
            case 0:
                $result ['message'] = "Record productgroep [id=$id] was not deleted.";
                break;

            case 1:
                $result['status'] = 'ok';        
                break;

            default:
                $result ['message'] = "Multiple records of 'productgroep' [id=$id] are deleted.";
                break;
        }

    echo json_encode($result);
    Yii::app()->end();        
    }

    /* dpslays all productgroep models  */
    public function actionIndex()
    {
        $this->menu = Yii::app()->menuManager->getMain();
        $dataProvider=new CActiveDataProvider('Productgroep');
        $this->render('index',array('dataProvider'=>$dataProvider));
    }

    /* returns the model identified by the id */
    public function loadModel($id)
    {
        $model=Productgroep::model()->findByPk($id);
        if($model===null) {
            throw new CHttpException(404,'The requested page does not exist.');
        }
        return $model;
    }

    /* validates the model attribute values */
    protected function performAjaxValidation($model)
    {
        if(isset($_POST['ajax']) && $_POST['ajax']==='maaltijdtype-form')
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}
