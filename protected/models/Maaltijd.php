<?php
/**
 * @copyright Copyright &copy; Brainpower Solutions.nl, 2014
 */

/**
 * Maaltijd Model.
 *
 * @author Barry M. van Kessel <bmvankessel@brainpowersolutions.nl>
 */
class Maaltijd extends CCustomActiveRecord {

	public function search() {
		$criteria = new CDbCriteria;
		$criteria->compare('code', $this->code, true);
		$criteria->compare('omschrijving', $this->omschrijving, true);
        if ($this->specificatie_datum !== null) {
            $dateParts = explode('-', $this->specificatie_datum);
            if (count($dateParts) == 3) {
                $format = '';
                # determine format for day
                if (strlen($dateParts[0]) == 1) {
                    $format .= 'j';
                } else {
                    $format .= 'd';
                }
                $format .= '-';
                # determine format for month
                if (strlen($dateParts[1]) == 1) {
                    $format .='n';
                } else {
                    $format .= 'm';
                }
                $format .= '-';
                if (strlen($dateParts[2]) == 2) {
                    $format .= 'y';
                } else {
                    $format .= 'Y';
                }

                $date = DateTime::createFromFormat($format,$this->specificatie_datum);
                $date = $date->format('Y-m-d');
                $criteria->addCondition("specificatie_datum >='$date'");
            }
        //$criteria->compare('specificatie_datum', $this->specificatie_datum);
        }
		$criteria->compare('productgroep_id', $this->productgroep_id);
		$criteria->compare('maaltijdtype_id', $this->maaltijdtype_id);
		$criteria->compare('maaltijdsubtype_id', $this->maaltijdsubtype_id);
		$criteria->compare('component_groente', $this->component_groente);
		$criteria->compare('component_rund', $this->component_rund);
		$criteria->compare('component_varken', $this->component_varken);
		$criteria->compare('component_vis', $this->component_vis);
		$criteria->compare('component_gevogelte', $this->component_gevogelte);
		$criteria->compare('component_lam', $this->component_lam);
		$criteria->compare('component_vegetarisch', $this->component_vegetarisch);
		$criteria->compare('component_aardappel', $this->component_aardappel);
		$criteria->compare('component_rijst', $this->component_rijst);
		$criteria->compare('component_pasta', $this->component_pasta);
		$criteria->compare('component_omelet', $this->component_omelet);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
			'pagination' => array(
				'params' =>array('Search'=>$this->searchAttributes()),
				'pageSize' => 20,
			),
		));
	}

	public function searchInternal() {
		$criteria = new CDbCriteria;
		$criteria->compare('code', $this->code, true);
		$criteria->compare('maaltijdtype_id', $this->maaltijdtype_id);
		$criteria->compare('maaltijdsubtype_id', $this->maaltijdsubtype_id);
		$criteria->order = 'IF(LOCATE("nieuw", LOWER(IFNULL(`code`, ""))) > 0, `copy_of_code`, `code`), `code`';
		
		
		
		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
			'pagination' => array(
				'params' =>array('Search'=>$this->searchInternalAttributes()),
				'pageSize' => 20,
			),
		));
	}

	private function searchInternalAttributes() {
		$attributes = array();
		$attributes['code']= $this->code;
		$attributes['maaltijdtype_id']= $this->maaltijdtype_id;
		$attributes['maaltijdsubtype_id']= $this->maaltijdsubtype_id;

		return $attributes;
	}

	private function searchAttributes() {
		$attributes = array();
		$attributes['code']= $this->code;
		$attributes['omschrijving']= $this->omschrijving;
		$attributes['maaltijdtype_id']= $this->maaltijdtype_id;
		$attributes['maaltijdsubtype_id']= $this->maaltijdsubtype_id;

		return $attributes;
	}

public function tableName()
{
	return 'maaltijd';
}

	public function attributeTypes() {
		return array(
			'id' => 'int',
			'code' => 'code',
			'omschrijving' => 'string'
		);
	}

public function rules() {
	return array(
		array('specificatie_datum, specificatie_gecontroleerd_op', 'default','setOnEmpty'=>true, 'value'=>null,),
	);
}

/**
 * @return array Customized attribute labels (name=>label)
 */
public function attributeLabels()
{
		return array(
			'id' => 'ID',
			'code' => 'Code',
			'omschrijving'=>'Omschrijving',
			'mizo_geschikt'=>'MiZo geschikt',
			'rundvleesvrij'=>'Rundvleesvrij',
			'varkensvleesvrij'=>'Varkensvleesvrij',
//                'rund_en_varkensvleesvrij'=>'Rund- en varkensvleesvrij',
			'vegetarisch'=>'Vegetarisch',
			'halal_gecertificeerd'=>'Halal',
			'alcoholvrij'=>'Alcoholvrij',
			'specificatie_datum'=>'Specificatiedatum (jjjj-mm-dd)',
			'gewicht'=>'Gewicht (gram)',
			'ikb_geschikt'=>'Ik kies bewust',
			'ingredientendeclaratie'=>'Ingredienten',
			'vw_100_energie_kj'=>'Energie (kJ)',
			'vw_100_energie_kcal'=>'Energie (kcal)',
			'vw_100_totaal_eiwit'=>'Eiwit (g)',
			'vw_100_koolhydraten'=>'Totaal Koolhydraten (g)',
			'vw_100_totaal_vet'=>'Totaal vet (g)',
			'vw_100_verzadigd_vet'=>'Verzadigd vet (g)',
			'vw_100_natrium'=>'Natrium (mg)',
			'vw_100_kalium'=>'Kalium (mg)',
			'vw_100_eov_g_100_gr'=>'Enkelvoudige onverzadigde vetzuren (g)',
			'vw_100_mov_g_100_gr'=>'Meervoudig onverzadigde vetzuren (g)',
			'vw_100_vezels'=>'Vezels (g)',
			'vw_100_zout'=>'Zout (g)',
			'vw_100_suikers'=>'Suikers (g)',
			'vw_100_transvetzuren'=>'Transvetzuren',
			'gluten_bevattende_granen'=>'Glutenbevattende granen *',
			'schaaldieren'=>'Schaal- en schelpdieren',
			'eieren'=>'Eieren',
			'vis'=>'Vis',
			'aardnoten_pindas'=>'Aardnoten',
			'mosterd'=>'Mosterd',
			'soja'=>'Soja',
			'lupine'=>'Lupine',
			'melk'=>'Melk',
			'noten'=>'Noten',
			'selderij'=>'Selderij',
			'sesamzaad'=>'Sesamzaad',
			'zwavel_en_sulfiet'=>'Zwaveldioxide en sulfieten',
			'weekdieren'=>'Weekdieren',
			'omschrijving' => 'Omschrijving',
			'component_rund' => 'Rund',
			'component_groente' => 'Groente',
			'component_rund_varken' => 'Rund & varken',
			'component_varken' => 'Varken',
			'component_vis' => 'Vis',
			'component_gevogelte' => 'Gevogelte',
			'component_lam' => 'Lam',
			'component_vegetarisch' => 'Vegetarisch',
			'component_aardappel' => 'Aardappel',
			'component_rijst' => 'Rijst',
			'component_pasta' => 'Pasta',
			'component_omelet' => 'Omelet',
			'vrij_van_gluten'=>'Gluten',
			'vrij_van_gluten_controle_sneltest'=>'Gluten controle sneltest',
			'vrij_van_melkeiwit'=>'Melkeiwit',
			'vrij_van_lactose'=>'Lactose',
			'vrij_van_kippenei'=>'Kippenei',
			'magnetrongeschikt'=>'Magnetron geschikt',
			'maaltijdtype_id'=>'Maaltijdtype',
			'maaltijdsubtype_id'=>'Maaltijdsubtype',
			'gaarheid'=>'Gaarheid',
			'opmerking'=>'Opmerking',
			'subtitel'=>'Subtitel',
			'stuks_per_verpakking'=>'Aantal stuks / porties per verpakking',
			'gemiddeld_gewicht_per_stuk'=>'Gemiddeld gewicht per stuk / portie (gram)',
			'bereidingswijze_contactwarmte' => 'Contactwarmte',
			'bereidingswijze_hete_lucht' => 'Hete lucht',
			'bereidingswijze_combisteamer' => 'Combisteamer',
			'bereidingswijze_magnetron' => 'Magnetron',
			'productomschrijving_warenwettelijk' => 'Productomschrijving warenwettelijk',
			'specificatie_gecontroleerd_op' => 'Gecontroleerd op (jjjj-mm-dd)',
		);
}

	public function relations() {
		return array(
			'productgroep'=>array(self::BELONGS_TO, 'Productgroep', 'productgroep_id'),
			'maaltijdtype'=>array(self::BELONGS_TO, 'Maaltijdtype', 'maaltijdtype_id'),
			'maaltijdsubtype'=>array(self::BELONGS_TO, 'Maaltijdsubtype', 'maaltijdsubtype_id'),
			'productgroep'=>array(self::BELONGS_TO, 'Maaltijdsubtype', 'maaltijdsubtype_id'),
		);
	}

	public function bereidingAttributes() {
		return array(
			'gaarheid',
		);
	}

	public function voedingswaardeAttributes() {
		return array(
			'vw_100_energie_kj',
			'vw_100_energie_kcal',
            'vw_100_totaal_vet',
            'vw_100_verzadigd_vet',
            'vw_100_eov_g_100_gr',
            'vw_100_mov_g_100_gr',
            'vw_100_koolhydraten',
            'vw_100_suikers',
            'vw_100_vezels',
			'vw_100_totaal_eiwit',
			'vw_100_zout',
			'vw_100_natrium',
			'vw_100_kalium',
		);
	}

	public function bereidingswijzeAttributes() {
		return array(
			'bereidingswijze_contactwarmte',
			'bereidingswijze_hete_lucht',
			'bereidingswijze_combisteamer',
			'bereidingswijze_magnetron',
		);
	}

	public function productAttributes() {
		return array(
			'code',
//                'subtitel',
			'omschrijving',
			'productomschrijving_warenwettelijk',
			'specificatie_datum',
			'gewicht',
			'stuks_per_verpakking',
			'gemiddeld_gewicht_per_stuk',
			'specificatie_gecontroleerd_op',
		);
	}

	public function componentAttributes() {
		if ($this->scenario == 'search') {
			return array(
				'component_groente'=>$this->getAttributeLabel('component_groente'),
				'component_gevogelte'=>$this->getAttributeLabel('component_gevogelte'),
				'component_lam'=>$this->getAttributeLabel('component_lam'),
				'component_rund'=>$this->getAttributeLabel('component_rund'),
//				'component_rund_varken'=>$this->getAttributeLabel('component_rund_varken'),
				'component_varken'=>$this->getAttributeLabel('component_varken'),
				'component_vegetarisch'=>$this->getAttributeLabel('component_vegetarisch'),
				'component_vis'=>$this->getAttributeLabel('component_vis'),
				'component_aardappel'=>$this->getAttributeLabel('component_aardappel'),
				'component_pasta'=>$this->getAttributeLabel('component_pasta'),
				'component_rijst'=>$this->getAttributeLabel('component_rijst'),
			);
		} else {
			return array(
				'component_groente',
				'component_rund',
				'component_varken',
				'component_vis',
				'component_gevogelte',
				'component_lam',
				'component_vegetarisch',
				'component_aardappel',
				'component_rijst',
				'component_pasta',
				'component_omelet',
			);
		}
	}

	public function allergenenAttributes() {
		return array(
			'gluten_bevattende_granen',
			'schaaldieren',
			'eieren',
			'vis',
			'aardnoten_pindas',
			'soja',
			'lupine',
			'melk',
			'noten',
			'selderij',
			'mosterd',
			'sesamzaad',
			'zwavel_en_sulfiet',
			'weekdieren',
		);
	}

	public function claimAttributes() {
		return array(
			'vegetarisch',
			'halal_gecertificeerd',
			'alcoholvrij',
			'ikb_geschikt',
			'mizo_geschikt',
			'rundvleesvrij',
			'varkensvleesvrij',
//                'rund_en_varkensvleesvrij',
//                'magnetrongeschikt',
		);
	}

	public function claimAttributesPdf() {
		return array(
			'vegetarisch',
			'halal_gecertificeerd',
			'alcoholvrij',
			'ikb_geschikt',
		);
	}

	public function vrijVanAttributes() {
		return array(
			'vrij_van_gluten',
			'vrij_van_gluten_controle_sneltest',
			'vrij_van_melkeiwit',
			'vrij_van_lactose',
			'vrij_van_kippenei',
		);
	}

	public function attributesToDisplay() {
		return array(
			'code',
			'omschrijving'
		);
	}

public static function model($className=__CLASS__)
{
	return parent::model($className);
}

	/**
	 * Creates the component entries.
	 * 
	 * @param string $subgroupSelected			Name of the selected subgroup.
	 * @param array $selectedMenu				Item selected for each of the menu levels mainGroup, 
	 * 											group and subgroup.
	 * 
	 * @return array							Components.
	 */
	private function addComponents($subgroupSelected, $selectedMenu) {
		$components = array();
		$components[] = array(
			'name'=>'Alles',
			'field'=>'',
			'selected'=> ($subgroupSelected && 'Alles' == $selectedMenu['component']) ? true : false,
		);

		foreach ($this->componentAttributes() as $field=>$name) {
			$components[] = array(
				'name'=>$name,
				'field'=>$field,
				'selected'=> ($subgroupSelected && $name == $selectedMenu['component']) ? true : false,
			);
		}
		return $components;
	}

	/**
	 * Creates a subgroup entry.
	 * 
	 * @param string $name			Subgroup name.
	 * @param string $description	Subgroup description.
	 * @param integer $id			Subgroup id.
	 * @param array $selectedMenu	Item selected for each of the menu levels mainGroup, group and 
	 * 								subgroup.
	 * 
	 * @return array				Subgroup data.
	 */
	private function addSubgroup($name, $description, $id, $selectedMenu) {
		$subgroup = array();
		$subgroup['name'] = $name;
		$subgroup['selected'] = ($name == $selectedMenu['subgroup']) ? true : false;
		$subgroup['description'] = $description;
		$subgroup['id'] = $id;
		$subgroup['components'] = $this->addComponents($subgroup['selected'], $selectedMenu);
		return $subgroup;
	}

	/**
	 * Creates a group entry.
	 * 
	 * @param string $name			Group name.
	 * @param integer $id			Group id.
	 * @param array $selectedMenu	Item selected for each of the menu levels mainGroup, group and 
	 * 								subgroup.
	 * 
	 * @return array				Group data.
	 */
	private function addGroup($name, $id, $selectedMenu) {
	$group = array();
	$group['name'] = $name;
	$group['selected'] = ($name == $selectedMenu['group']) ? true : false;
	$group['id'] = $id;
	$group['subgroup'] = array();
	return $group;
	}

	/**
	 * Creates a main group entry.
	 * 
	 * @param string $name			Main group name.
	 * @param integer $id			Main group id.
	 * @param array $selectedMenu	Item selected for each of the menu levels mainGroup, group and 
	 * 								subgroup.
	 * 
	 * @return array				Main group data.
	 */
	private function addMainGroup($name, $id, $selectedMenu) {
		$mainGroup = array();
		$mainGroup['name'] = $name;
		$mainGroup['selected'] = ($name === $selectedMenu['mainGroup']) ? true: false;
		$mainGroup['id'] = $id;
		$mainGroup['group'] = array();
		return $mainGroup;
	}

	/**
	 * Builds the search tree: productgroep -> maaltijdtype -> maaltijdsubtype as defined by
	 * maaltijdzoekfilter.
	 *
	 * @param array $selectedMenu	Item selected for each of the menu levels mainGroup, group and 
	 * 								subgroup.
	 *
	 * @return array				Search tree.
	 *
	 */
	public function searchNavigation($selectedMenu) {
		$navigation['mainGroup'] = array();
		$currentMainGroup = 0;
		$currentGroup = 0;
		$currentSubgroup = 0;
		
		$idxMainGroup = -1;
		$idxGroup = -1;
		
		foreach(Maaltijdzoekfilter::model()->all() as $zoekfilter) {
			if ($currentMainGroup != $zoekfilter->productgroep_id) {
				// new main group
				$navigation['mainGroup'][] = $this->addMainGroup($zoekfilter->productgroep->omschrijving, $zoekfilter->productgroep_id, $selectedMenu);
				$currentMainGroup = $zoekfilter->productgroep_id;
				$currentGroup = 0;
				$idxMainGroup++;
				$idxGroup = -1;
			}
			
			if ($currentGroup !== $zoekfilter->maaltijdtype_id) {
				// new group
				$navigation['mainGroup'][$idxMainGroup]['group'][] =
					$this->addGroup($zoekfilter->maaltijdtype->omschrijving, $zoekfilter->maaltijdtype_id, $selectedMenu);
				$currentGroup = $zoekfilter->maaltijdtype_id;
				$idxGroup++;
			}
			
			$navigation['mainGroup'][$idxMainGroup]['group'][$idxGroup]['subgroup'][] =
				$this->addSubgroup($zoekfilter->maaltijdsubtype->omschrijving, $zoekfilter->tooltip , $zoekfilter->maaltijdsubtype_id, $selectedMenu);				
		}

		return $navigation;
	}
}

?>
