<?php

class MenuManager extends CComponent {
    public function init() {
    }
    
    public function getMain() {
        $menu = array();
          
        $menu["Maaltijden"] = Yii::app()->createUrl('maaltijd');
        if (!Yii::app()->user->isGuest) {            
            $menu["Stamgegevens"] = array();
            $menu["Stamgegevens"]["Maaltijdtypen"] = Yii::app()->createUrl('/maaltijdtype');
            $menu["Stamgegevens"]["Maaltijdsubtypen"] = Yii::app()->createUrl('xxx');
            $menu["Gebruikers"] = Yii::app()->createUrl('xxx');            
        }        
        
        return $menu;
    }
    
    public function getActions($model = "") {
        $actions = array();
        
        $actions['view'] = array();
        $actions['create'] = array();
        $actions['update'] = array();
        $actions['delete'] = array();
        
        return $actions;
    }
}