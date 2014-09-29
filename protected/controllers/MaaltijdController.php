<?php

class MaaltijdController extends Controller {

    public function actionAjaxCreate() {
        $result = array();
        $result['status'] = 'not ok';

        if (isset($_POST['attributes'])) {
            $attributes = json_decode($_POST['attributes'], true);

            $model = new Maaltijd('insert');
            $model->setAttributes($attributes, false);

            if ($model->save(false)) {
                $result['id'] = $model->id;
                $result['status'] = 'ok';
                $result['navigate'] = $this->createUrl('view', array('id'=>$model->id));
            } else {
                $result['status'] = 'Maaltijd not created';
            }
        } else{
            $result['message'] = 'No attributes posted';
        }

        echo json_encode($result);
        Yii::app()->end();
    }

    public function actionIndex() {
        $maaltijd = new Maaltijd('search');
        $maaltijd->unsetAttributes();

        $_P_G = array_merge($_POST, $_GET);

        if (isset($_P_G['Search'])) {
            $search = array();
            foreach($_P_G['Search'] as $key=>$value) {
                if (strlen($value) > 0) {
                    $search[$key] = $value;
                }
            }
            
            $maaltijd->setAttributes($search, false);
        }
        
        $maaltijdtype = new Maaltijdtype();
        $maaltijdsubtype = new Maaltijdsubtype();
        $this->render('index',
                array(
                    'selectedType'=>$maaltijd->maaltijdtype_id,
                    'selectedSubType'=>$maaltijd->maaltijdsubtype_id,
                    'maaltijd'=>$maaltijd,
                    'maaltijdtypes'=>$maaltijdtype->omschrijvingen(),
                    'maaltijdsubtypes'=>$maaltijdsubtype->omschrijvingen(),
                    'code'=>$maaltijd->code,
                ));
    }

    public function actionUpdate($id) {
            if (Yii::app()->request->isPostRequest) {
                $result =array();
                $result['status'] = 'not ok';

                $maaltijd = Maaltijd::model()->findByPk($id);

                $attributes = json_decode($_POST['data'], true);

                $maaltijd->setAttributes($attributes, false);

                if (count($attributes) > 0) {
                    if ($maaltijd->save()) {
                        $result['status'] = 'ok';
                    } else {
                        $result['message'] = 'Attributes not saved';
                    }
                } else {
                    $result['message'] = 'No attributes to save';
                }
                echo json_encode($result);
            } else {
                $this->redirect($this->createUrl('view', array('id'=>$id)));
            }
            Yii::app()->end();
    }

    public function actionDelete() {
        $result = array();
        $result['status'] = 'not ok';
        $data = json_decode($_POST['data'],true);
        $id = $data['id'];

        $modelsDeleted =  Maaltijd::model()->deleteByPk($id);

        switch($modelsDeleted) {
            case 0:
                $result ['message'] = "Record maaltijd [id=$id] was not deleted.";
                break;

            case 1:
                $result['status'] = 'ok';
                break;

            default:
                $result ['message'] = "Multiple records of 'maaltijd' [id=$id] are deleted.";
                break;
        }

        echo json_encode($result);
        Yii::app()->end();
    }

    public function actionView($id) {
        $model = $this->loadModel($id);
        if (Yii::app()->request->isPostRequest) {
            $model->attributes = $_POST;
            $fp = fopen('post.txt', 'w');
            fwrite($fp, json_encode($_POST));
            fclose($fp);
            die;
        } else {
            $this->menu = Yii::app()->menuManager->getMain();

            $maaltijdtype = new Maaltijdtype();
            $maaltijdsubtype = new Maaltijdsubtype();
            $productgroep = new Productgroep();
            $this->render(
                    "update",
                    array(
                        'model'=>$model,
                        'updateAllowed'=>(Yii::app()->user->isGuest == false),
                        'productgroepDescriptions'=>$productgroep->omschrijvingen(),
                        'typeDescriptions'=>$maaltijdtype->omschrijvingen(),
                        'subTypeDescriptions'=>$maaltijdsubtype->omschrijvingen(),
                    )
           );
        }
    }

    public function loadModel($id)
    {
            $model=Maaltijd::model()->findByPk($id);
            if($model===null)
                    throw new CHttpException(404,'The requested page does not exist.');
            return $model;
    }
}
