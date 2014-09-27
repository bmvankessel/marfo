<?php

class MaaltijdzoekfilterController extends Controller {
    public function actionIndex() {
        $this->render('index', array('dataProvider'=>  Maaltijdzoekfilter::model()->search()));
    }

    public function actionUp($id) {
        Maaltijdzoekfilter::model()->moveUp($id);
        $this->render('index', array('dataProvider'=>  Maaltijdzoekfilter::model()->search()));
    }

    public function actionDelete($id) {
        Maaltijdzoekfilter::model()->deleteByPk($id);
        $this->render('index', array('dataProvider'=>  Maaltijdzoekfilter::model()->search()));
    }

    public function actionDown($id) {
        Maaltijdzoekfilter::model()->moveDown($id);
        $this->render('index', array('dataProvider'=>  Maaltijdzoekfilter::model()->search()));
    }

    public function actionUpdate($id=0) {
        if (Yii::app()->request->isPostRequest) {
            // update maaltijdzoekfilter
            
            $attributes = $_POST['Maaltijdzoekfilter'];
            
            if ($attributes['id'] == 0) {
                $zoekfilter = new Maaltijdzoekfilter();
                // remove id from attributes
                
                // determine sequence
                // will be placed as last item with the same maaltijdtype_id
                // or as last item if new (maaltijdtype_id does not exist)
                $sequence = 0;
                foreach (Maaltijdzoekfilter::model()->All() as $currentZoekfilter) {
                    if ($currentZoekfilter->sequence != $sequence) {
                        $currentZoekfilter->sequence = $sequence;
                        $currentZoekfilter->save();
                    }                    
                }

                $sequence++;
                $zoekfilter->sequence = $sequence;

            } else {
                $zoekfilter = Maaltijdzoekfilter::model()->findByPk($attributes['id']);
            }
            unset($attributes['id']);
            
            // set other attributes
            $zoekfilter->setAttributes($attributes,false);
            if (!$zoekfilter->save()) {
                throw new CException('Error saving maaltijdzoekfilter, data:' . json_encode($_attributes));
            }
            $this->render('index', array('dataProvider'=>  Maaltijdzoekfilter::model()->search()));
            
        } else {
            if ($id==0) {
                $zoekfilter = new Maaltijdzoekfilter();
                $zoekfilter->id = $id;
            } else {
                $zoekfilter = Maaltijdzoekfilter::model()->findByPk($id);
            }
            $maaltijdtypeDescriptions = Maaltijdtype::model()->omschrijvingen();
            $maaltijdsubtypeDescriptions = Maaltijdsubtype::model()->omschrijvingen();

            $this->render(
                    'update', 
                    array(
                        'zoekfilter'=>$zoekfilter, 
                        'maaltijdtypeDescriptions'=>$maaltijdtypeDescriptions,
                        'maaltijdsubtypeDescriptions'=>$maaltijdsubtypeDescriptions
                    )
            );            
        }
    }
}