<?php
    class CCustomActiveRecord extends CActiveRecord {

        public function modelName() {
            return get_class($this);
        }

        public function __get($prop) {
            if (strtolower(substr($prop,0,5)) == "label") {
                $attribute = strtolower(substr($prop, 5));
                if (key_exists($attribute, $this->attributeLabels())) {
                    $labels = $this->attributeLabels();
                    return $labels[$attribute];
//                    return $this->attributeLabels()[$attribute];
                }
                else {
                    throw new Exception("No label defined for '$attribute'");
                }
            }

            if (strtolower(substr($prop,0,4)) == "type") {
                $attribute = strtolower(substr($prop, 4));
                if (key_exists($attribute, $this->attributeTypes())) {
                    $attributeTypes = $this->attributeTypes();
                    return $attributeTypes[$attribute];
//                    return $this->attributeTypes()[$attribute];
                }
                else {
                    throw new Exception("No type defined for '$attribute'");
                }
            }

            return parent::__get($prop);
        }
        
    }
?>