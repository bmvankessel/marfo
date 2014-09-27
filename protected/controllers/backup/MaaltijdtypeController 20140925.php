<?php

class MaaltijdtypeController extends Controller
{

    public function actionRelatedParents() {
        $data = json_decode($_POST['data'],true);
        $id = $data['id'];
        $result = array();
        $result['status'] = 'not ok';
        $modelCount = Maaltijd::model()->count("maaltijdtype_id=$id");
        $result['status'] = 'ok';
        $result['parentCount'] = $modelCount;

        echo json_encode($result);
        Yii::app()->end();        
    }
        
    public function actionAjaxCreate() {
        $result = array();
        $result['status'] = 'not ok';
        $result['action'] = 'update';

        if (isset($_POST['attributes'])) {
            $attributes = json_decode($_POST['attributes'], true);
            
            $model = new Maaltijdtype('insert');
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

    public function actionDelete() {
        $result = array();
        $result['status'] = 'not ok';
        $data = json_decode($_POST['data'],true);
        $id = $data['id'];

        Maaltijd::model()->updateAll(array('maaltijdtype_id'=>null), "maaltijdtype_id=$id");
        $modelsDeleted =  Maaltijdtype::model()->deleteByPk($id);

        switch($modelsDeleted) {
            case 0:
                $result ['message'] = "Record maaltijdtype [id=$id] was not deleted.";
                break;

            case 1:
                $result['status'] = 'ok';        
                break;

            default:
                $result ['message'] = "Multiple records of 'maaltijdtype' [id=$id] are deleted.";
                break;
        }

    echo json_encode($result);
    Yii::app()->end();        
    }

    public function actionIndex()
    {
            $this->menu = Yii::app()->menuManager->getMain();
            $dataProvider=new CActiveDataProvider('Maaltijdtype');
            $this->render('index',array(
                    'dataProvider'=>$dataProvider,
            ));
    }

    public function loadModel($id)
    {
            $model=Maaltijdtype::model()->findByPk($id);
            if($model===null)
                    throw new CHttpException(404,'The requested page does not exist.');
            return $model;
    }

    protected function performAjaxValidation($model)
    {
            if(isset($_POST['ajax']) && $_POST['ajax']==='maaltijdtype-form')
            {
                    echo CActiveForm::validate($model);
                    Yii::app()->end();
            }
    }
}
