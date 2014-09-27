<?php
    class GebruikerController extends Controller
    {

    public function filters()
    {
        return array(
            'accessControl',
        );
    }    

        public function accessRules()
        {
            return array(
                array(
                    'allow',
                    'actions'=>array('index'),
                    'roles'=>array('admin'),
                ),
                array(
                    'deny',
                    'actions'=>array('index'),
                    'users'=>array('*'),
                ),
            );
        }
                
        public function actionIndex()
        {
            $dataProvider=new CActiveDataProvider('Gebruiker');
            $this->render('index',array('dataProvider'=>$dataProvider));
        }
        
        public function actionDelete() {
            $result = array();
            $result['status'] = 'not ok';
            $data = json_decode($_POST['data'],true);
            $id = $data['id'];

            $gebruikersDeleted =  Gebruiker::model()->deleteByPk($id);

            if ($gebruikersDeleted == 0) {
                $result ['message'] = "Record gebruiker [id=$id] was not deleted.";
            } else { 
                $result['status'] = 'ok';
            }

            echo json_encode($result);
            Yii::app()->end();        
        }
        
        public function actionUpdate($id = -1) {
            if ($id == -1) {
                $action = 'create';
                $model = new Gebruiker('new');
            } else {
                $action = 'update';
                $model = Gebruiker::model()->findByPk($id);                
            }

            $this->performAjaxValidation($model);
            
            if (isset($_POST['Gebruiker'])) {                
                $model->setAttributes($_POST['Gebruiker'], true);
                $model->attributes = $_POST['Gebruiker'];
                
                if (strlen($model->wachtwoord)>0) {
                    $model->salt = Utils::createSalt();
                    $model->hash = Utils::createHash($_POST['Gebruiker']['wachtwoord'], $model->salt);
                }
                
                if ($model->save()) {
                    $this->redirect($this->createUrl('index'));
                } else {
                    var_dump($model);die;
                }
            } 
            $this->render('update',array('model'=>$model, 'action'=>$action));            
        }
        
        public function performAjaxValidation($model) {
            if (isset($_POST['ajax']) && $_POST['ajax']==='gebruiker-form')
            {
                echo CActiveForm::validate($model);
                Yii::app()->end();
            }
        }
    }
?>