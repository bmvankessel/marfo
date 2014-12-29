<?php
class SearchController extends Controller {
    
    // Blank page for search page
    // Displayed 'outside' yii in iFrame
    public $layout = '//layouts/search';

    // Set accessible to everyone
    public function filters()
    {
        return array(
            'accessControl',
        );
    }    

    // Set accessible to everyone
    public function accessRules()
    {
        return array(
            array(
                'allow',
                'roles'=>array('*'),
            ),
        );
    }
    
    /*
     * Create PDF
     */
    public function actionCreatePdf() {
        $data = json_decode($_POST['data'], true);
        $id = $data['id'];
        $result = array();
        $result['status'] = 'ok';
        Yii::import('ext.mpdf.Pdf');
        
        $maaltijd = Maaltijd::model()->findByPk($id);
        $maaltijdType = Maaltijdtype::model()->findByPk($maaltijd->maaltijdtype_id);
        $maaltijdSubType = Maaltijdsubtype::model()->findByPk($maaltijd->maaltijdsubtype_id);
        $pdf = new Pdf();
        $code = $maaltijd->code;
        $imageSource = Yii::getPathOfAlias('maaltijdimg') . "/$code.jpg";
        
        $pdf->AddPage();

        $html = $pdf->htmlStyle();
        
        $html .= $pdf->htmlHeader($maaltijd->omschrijving, $maaltijd->code);
        $html .= $pdf->htmlTitle('Bedrijfsgegevens');
        $data = array(
            array('Bedrijfsnaam', 'Marfo B.V.'),
            array('Adresgegevens', 'Koperstraat 25-31, 8211 AK, Lelystad'),
            array('E-mailadres','info@marfo.com'),
            array('EEG-nr','212'),
        );
        $html .= $pdf->htmlTable($data ,array(1,1), false, 'bedrijfsgegevens');

        $html .= $pdf->htmlTitle('Productgegevens');
        $html .= $pdf->htmlProductgegevens(
                $maaltijd->productomschrijving_warenwettelijk,
                $maaltijdType->omschrijving, $maaltijdSubType->omschrijving, 
                $maaltijd->gewicht, $maaltijd->stuks_per_verpakking, 
                $maaltijd->gemiddeld_gewicht_per_stuk, $imageSource);
        
        $html .= $pdf->htmlTitle('Houdbaarheid en bewaarcondities');
        $data = array();
        $data[] = array('Houdbaarheid na productie:', '18 maanden mits bewaard bij een temperatuur van max. -18⁰C');
        $data[] = array('Bewaaradvies na ontdooiden:','maximaal 24 uur in de koelkast bij een temperatuur van max. 7⁰C');
        $data[] = array('', 'LET OP: het product mag slechts eenmaal verwarmd worden.' );
        $html .= $pdf->htmlTable($data ,array(100,100), false, 'houdbaarheid');

        $html .= $pdf->htmlTitle('Ingrediënten');
        $html .= $pdf->htmlTable(array(array($maaltijd->ingredientendeclaratie))  ,array(100), false, 'ingredienten', true);
        
        $html .= $pdf->htmlTitle('Voedingswaarden');
        $data = array();
        $data[] = array('Voedingswaarden', 'per 100g', 'per maaltijd');

        foreach($maaltijd->voedingswaardeAttributes() as $modelAttribute) {
            $label = "label$modelAttribute";
            $field100 = $modelAttribute;
            $fieldComplete = str_replace('vw_100_', 'vw_compleet_', $modelAttribute);
            $data[] = array(
                $maaltijd->$label,
                ($maaltijd->$field100 == null) ? 'xxx' :  str_replace('.', ',', $maaltijd->$field100) , 
                ($maaltijd->$fieldComplete == null)  ? 'xxx' : str_replace('.',',',$maaltijd->$fieldComplete));
        }    
        $html .= $pdf->htmlTable($data ,array(1,2,3),true, 'voedingswaarden');

        $html .= '<div class="uitleg10">Deze waarden zijn berekend. Door onder o.a. seizoensinvloeden kunnen hier afwijkingen in ontstaan.</div>';

        $pdf->writeHtml($html);
        $pdf->AddPage();
        
        $html = $pdf->htmlStyle();
        
        $html .= $pdf->htmlHeader($maaltijd->omschrijving, $maaltijd->code);
        
        $html .= $pdf->htmlTitle('Allergenen');

        # build explanation (static and dynamic text)
        $explanation = CHtml::encode('Een "+" geeft aan dat het betreffende bestanddeel aanwezig is in het product.');
        # ignore descriptions containin a zero as string as well (delivery error)
        if ($maaltijd->melding_allergenentabel !== null && $maaltijd->melding_allergenentabel != 0 && strlen($maaltijd->melding_allergenentabel)>0) {
            $explanation .= '<br>' . CHtml::encode($maaltijd->melding_allergenentabel);
        }
        $html .= CHtml::tag('div', array('class'=>'uitleg9'), $explanation);
            
        $data = array();
        foreach($maaltijd->allergenenAttributes() as $modelAttribute) {
            $label = "label$modelAttribute";
            $data[] = array($maaltijd->$label, ($maaltijd->$modelAttribute) ? '+' : '-');
        }    

        $footer = '*Glutencontrole sneltest uitgevoerd: ';
        $footer .= ($maaltijd->vrij_van_gluten) ? 'ja' : 'nee';
        
        $html .= $pdf->htmlTable($data ,array(1,2), true, 'allergenen', false, $footer);
        
        $html .= "<br>";
        $html .= $pdf->htmlTitle('Overige kwalificaties');
        $data = array();
        foreach($maaltijd->claimAttributesPdf() as $modelAttribute) {
            $label = "label$modelAttribute";
            $data[] = array($maaltijd->$label, ($maaltijd->$modelAttribute) ? 'Ja' : 'Nee');
        }    
        $html .= $pdf->htmlTable($data ,array(1,2), false, 'gezondheid');

        $html .= "<br>";
        $html .= $pdf->htmlTitle('Bereidingswijze');
        $data = array();
        foreach($maaltijd->bereidingswijzeAttributes() as $modelAttribute) {
            $label = "label$modelAttribute";
            $data[] = array($maaltijd->$label, $maaltijd->$modelAttribute);
        }    
        $html .= $pdf->htmlTable($data ,array(1,2), false, 'bereidingswijze');        
        
        $html .= "<br>";
        $html .= $pdf->htmlTitle('Verklaring Marfo');
        $html .= $pdf->htmlPictureAndStatement('','',$imageSource);
        
        $pdf->writeHtml($html);
        
        $code = $maaltijd->code;
        
        $file = Yii::getPathOfAlias('maaltijdpdf') . '/maaltijd_' . $code . '.pdf';
  
        $pdf->Output($file, 'F');

        $file = Yii::app()->createAbsoluteUrl('maaltijd-pdf/maaltijd_' . $code . '.pdf');
        $result['file'] = $file;
        
        
        echo json_encode($result);
        Yii::app()->end();        
  }
  
	/**
	 * Returns the value form the post or get array.
	 * First the post and than the get array is checked.
	 * 
	 * @param string $field			Name of the field.
	 * @param mixed $defaultValue	Value if not found.
	 *
	 * Returns either the value from the post or get array, or the default value. 
	 */ 
//	private function getPostGetValue($field, $defaultvalue) {
//		if (isset($_POST[$field])) {
//			return $_POST[$field];
//		} else {
//			if (isset($_GET[$field])) {
//				return $_GET[$field];
//			} else
//				return $defaultValue;
//			}
//		}
//	}
    
    /**
     * Displays the search page for the meals.
     * 
     * The meals to be displayed are filtered by:
     * - values sent by clicking the search tree
     * - values entered in one of the search input for code or description.
     * - no value at all if none of the previous values were received
     */
    function actionIndex() {
        $model = new Maaltijd('search');
        $model->unsetAttributes();
        
        /* Values are sent by post or get. Merge the arrays so that one array will be processed */
        $_P_G = array_merge($_POST, $_GET);

		/* 
		 * Set the model attributes if the search field is set.
		 * The values are send by clicking the search tree.
		 */
        if (isset($_P_G['Search'])) {
            $model->setAttributes($_P_G['Search'], false);
        }

        $selectedGroup = (isset($_P_G['group'])) ? $_P_G['group'] : 0;
        
        /* set the values comming from one of the the search inputs */
        $selectedCode = (isset($_P_G['Search']['code'])) ? $_P_G['Search']['code'] : '';
        $selectedDescription = (isset($_P_G['Search']['omschrijving'])) ? $_P_G['Search']['omschrijving'] : '';
        $selectedDate = (isset($_P_G['Search']['specificatie_datum'])) ? $_P_G['Search']['specificatie_datum'] : '';
        
        /*
         * Store the selected search tree elements.
         * The values will be used to expend the select and show the selected component in the tree
         * when rendering the page.  
         */
        if (isset($_P_G['Selected'])) {
            $selectedMenu = $_P_G['Selected'];
            $selectedMenu['selectFirstGroup'] = false;
        } else {
            $selectedMenu = array(
                'selectFirstGroup'=>true, 
                'mainGroup'=>'',
                'group'=>'', 
                'subgroup'=>'', 
                'component'=>''
            );
        }
        
        $this->render('index', array(
            'model'=>$model, 
            'selectedMenu'=>$selectedMenu, 
            'selectedCode'=>$selectedCode, 
            'selectedDescription'=>$selectedDescription,
            'selectedDate'=>$selectedDate)
        );
        
    }
}
