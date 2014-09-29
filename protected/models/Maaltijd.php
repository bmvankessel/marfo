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
            $criteria->compare('maaltijdtype_id', $this->maaltijdtype_id);
            $criteria->compare('maaltijdsubtype_id', $this->maaltijdsubtype_id);
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

	/**
	 * @return array customized attribute labels (name=>label)
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
                'vw_100_totaal_eiwit'=>'Totaal Eiwit (g)',
                'vw_100_koolhydraten'=>'Totaal Koolhydraten (g)',
                'vw_100_totaal_vet'=>'Totaal vet (g)',
                'vw_100_verzadigd_vet'=>'Verzadigd vet (g)',
                'vw_100_natrium'=>'Natrium (mg)',
                'vw_100_kalium'=>'Kalium (mg)',
                'vw_100_eov_g_100_gr'=>'Enkelvoudig onverzadigde vetzuren (g)',
                'vw_100_mov_g_100_gr'=>'Meervoudig onverzadigde vetzuren (g)',
                'vw_100_vezels'=>'Vezels',
                'vw_100_zout'=>'Zout (g)',
                'vw_100_suikers'=>'Suikers',
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
                'vw_100_totaal_eiwit',
                'vw_100_koolhydraten',
                'vw_100_suikers',
                'vw_100_vezels',
                'vw_100_totaal_vet',
                'vw_100_verzadigd_vet',
                'vw_100_transvetzuren',
                'vw_100_eov_g_100_gr',
                'vw_100_mov_g_100_gr',
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
                    'component_gevogelte'=>$this->getAttributeLabel('component_gevogelte'),
                    'component_lam'=>$this->getAttributeLabel('component_lam'),
                    'component_rund'=>$this->getAttributeLabel('component_rund'),
                    'component_rund_varken'=>$this->getAttributeLabel('component_rund_varken'),
                    'component_varken'=>$this->getAttributeLabel('component_varken'),
                    'component_vegetarisch'=>$this->getAttributeLabel('component_vegetarisch'),
                    'component_vis'=>$this->getAttributeLabel('component_vis'),
                    'component_aardappel'=>$this->getAttributeLabel('component_aardappel'),
                    'component_pasta'=>$this->getAttributeLabel('component_pasta'),
                    'component_rijst'=>$this->getAttributeLabel('component_rijst'),
                );
            } else {
                return array(
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

        private function _addSubgroup($name, $description, $subgroupIds, $selectedMenu) {
            $subgroup = array();
            $subgroup['name'] = $name;
            $subgroup['selected'] = ($name == $selectedMenu['subgroup']) ? true : false;
            $subgroup['description'] = $description;
            $name = strtolower($name);
            $subgroup['id'] = (key_exists($name, $subgroupIds)) ? $subgroupIds[$name] : -1;
            $subgroup['components'] = $this->addComponents($subgroup['selected'], $selectedMenu);
            return $subgroup;
        }

        private function _addGroup($name,array $groupIds, $selectedMenu) {
            $group = array();
            $group['name'] = $name;
            $group['selected'] = ($name == $selectedMenu['group']) ? true : false;
            $name = strtolower($name);
            $group['id'] = (key_exists($name, $groupIds)) ? $groupIds[$name] : -1;
            $group['subgroup'] = array();
            return $group;
        }


        private function addSubgroup($name, $description, $id, $selectedMenu) {
            $subgroup = array();
            $subgroup['name'] = $name;
            $subgroup['selected'] = ($name == $selectedMenu['subgroup']) ? true : false;
            $subgroup['description'] = $description;
            $subgroup['id'] = $id;
            $subgroup['components'] = $this->addComponents($subgroup['selected'], $selectedMenu);
            return $subgroup;
        }

          private function addGroup($name, $id, $selectedMenu) {
            $group = array();
            $group['name'] = $name;
            $group['selected'] = ($name == $selectedMenu['group']) ? true : false;
            $group['id'] = $id;
            $group['subgroup'] = array();
            return $group;
        }


        public function searchNavigation($selectedMenu) {
            $navigation['group'] = array();
            $group = null;
            $currentGroup = 0;
            foreach(Maaltijdzoekfilter::model()->All() as $zoekfilter) {
                if ($currentGroup != $zoekfilter->maaltijdtype_id) {
                    if ($group !== null) {
                        $navigation['group'][] = $group;
                    }
                    // new group
                    $group = $this->addGroup($zoekfilter->maaltijdtype->omschrijving, $zoekfilter->maaltijdtype_id, $selectedMenu);
                    $currentGroup = $zoekfilter->maaltijdtype_id;
                }
                $group['subgroup'][] = $this->addSubgroup($zoekfilter->maaltijdsubtype->omschrijving, $zoekfilter->tooltip, $zoekfilter->maaltijdsubtype_id, $selectedMenu);
            }

            if ($group !== null) {
                $navigation['group'][] = $group;
            }

            return $navigation;
        }

        public function _searchNavigation($selectedMenu) {
            $type = new Maaltijdtype();
            $groups = array_change_key_case($type->omschrijvingenNaarId());
            $subtype = new Maaltijdsubtype();
            $subgroups = array_change_key_case($subtype->omschrijvingenNaarId());

            $navigation = array();
            $navigation['group'] = array();

            $group = $this->addGroup('Reguliere Maaltijden', $groups, $selectedMenu);
            $group['subgroup'][] = $this->addSubgroup('Regulier', '', $subgroups, $selectedMenu);
            $group['subgroup'][] = $this->addSubgroup('Extra gaar', 'De componenten in deze maaltijden zijn gaar gekookt en daardoor zacht en makkelijk kauwbaar', $subgroups, $selectedMenu);
            $navigation['group'][] = $group;

            $group = $this->addGroup('Specials',$groups, $selectedMenu);
            $group['subgroup'][] = $this->addSubgroup('Feestmaaltijden', 'Maaltijden geschikt voor zon- en feestdagen', $subgroups, $selectedMenu);
            $group['subgroup'][] = $this->addSubgroup('Kindermaaltijden', 'Maaltijden die qua componenten en hoeveelheid speciaal ontwikkeld zijn voor kinderen', $subgroups, $selectedMenu);
            $group['subgroup'][] = $this->addSubgroup('Bijgerechtjes', 'Gerechtjes die bij een maaltijd kunnen worden geserveerd', $subgroups, $selectedMenu);
            $group['subgroup'][] = $this->addSubgroup('Authentieke wereldgerechten', 'Gerechten bereid volgens authentieke receptuur uit diverse landen', $subgroups, $selectedMenu);
            $group['subgroup'][] = $this->addSubgroup('Kleine gerechten', 'Gerechten met een gemiddeld totaalgewicht van 300 gram, met een relatief hoog aandeel eiwit.', $subgroups, $selectedMenu);
            $group['subgroup'][] = $this->addSubgroup('Minigerechtjes', 'Gerechtjes die voldoen aan de snaq-eisen 150-200 kcal / 5-10 gram eiwit', $subgroups, $selectedMenu);
            $navigation['group'][] = $group;

            $group = $this->addGroup('Dieetmaaltijden',$groups, $selectedMenu);
            $group['subgroup'][] = $this->addSubgroup('Feestmaaltijden', 'Geen zout toegevoegd (max 450 mg natrium), gluten en lactosevrij', $subgroups, $selectedMenu);
            $group['subgroup'][] = $this->addSubgroup('Strikt natriumbeperkt', 'Geen zout toegevoegd (max 450 mg natrium), gluten en lactosevrij', $subgroups, $selectedMenu);
            $group['subgroup'][] = $this->addSubgroup('Allergenenvrij', 'Vrij van allergenen', $subgroups, $selectedMenu);
            $group['subgroup'][] = $this->addSubgroup('Licht natriumbeperkt', 'Max 650 mg natrium', $subgroups, $selectedMenu);
            $navigation['group'][] = $group;

            $group = $this->addGroup('Gepureerde maaltijden', $groups, $selectedMenu);
            $group['subgroup'][] = $this->addSubgroup('Regulier', '', $subgroups, $selectedMenu);
            $navigation['group'][] = $group;

            $group = $this->addGroup('Jus en sauzen', $groups, $selectedMenu);
            $group['subgroup'][] = $this->addSubgroup('Regulier', '', $subgroups, $selectedMenu);
            $group['subgroup'][] = $this->addSubgroup('Dieet', 'Productomschrijving maakt duidelijk voor welk "dieet" de jus geschikt is.', $subgroups, $selectedMenu);
            $navigation['group'][] = $group;

            if ($selectedMenu['selectFirstGroup']) {
                $navigation['group'][0]['selected'] = true;
                $navigation['group'][0]['subgroup'][0]['selected'] = true;
                $navigation['group'][0]['subgroup'][0]['components'][0]['selected'] = false;
            }

            return $navigation;
        }
    }

?>
