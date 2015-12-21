<?php
/**
 * @copyright Copyright &copy; Brainpower Solutions.nl, 2014
 */

/**
 * Additional static rendering methods.
 *
 * @author Barry M. van Kessel <bmvankessel@brainpowersolutions.nl>
 */
class Render extends CComponent {

	public static function activeFormFields($form, $model, $options = array()) {
		$startTab = self::arrayValue ($options,'startTab',0);
		$colDef = array();
		$colDef[0] = 2;
		$colDef[1] = 4;
		$colDef[2] = 12 - $colDef[0] - $colDef[1];

		$html = "";
		foreach($model->activeFormFields() as $field) {
			$html .= self::tabs($startTab+0) . CHtml::openTag('div', array('class'=>'row')) . PHP_EOL;

			$html .= self::tabs($startTab+1) . CHtml::openTag('div', array('class'=>"col-md-$colDef[0]")) . PHP_EOL;
			$html .= $form->labelEx($model,$field);
			$html .= self::tabs($startTab+1) . CHtml::closeTag('div') . PHP_EOL;

			$html .= self::tabs($startTab+1) . CHtml::openTag('div', array('class'=>"col-md-$colDef[1]")) . PHP_EOL;
			$html .= $form->textField($model,$field, array('class'=>'form-control'));
			$html .= self::tabs($startTab+1) . CHtml::closeTag('div') . PHP_EOL;

			$html .= self::tabs($startTab+1) . CHtml::openTag('div', array('class'=>"col-md-$colDef[2]")) . PHP_EOL;
			$html .= $form->error($model,$field);
			$html .= self::tabs($startTab+1) . CHtml::closeTag('div') . PHP_EOL;

			$html .= self::tabs($startTab+0) . CHtml::closeTag('div') . PHP_EOL;
		}

		return $html;
	}

	public static function arrayCopy(&$target,$source, $keys) {
		foreach($keys as $key) {
			if (isset($source[$key])) {
				$target[$key] = $source[$key];
			}
		}
	}

	public static function arrayCopyExcluded(&$target,$source, $excludedKeys) {
		foreach(array_keys($source) as $key) {
			if (isset($source[$key]) && !in_array($key, $excludedKeys)) {
				$target[$key] = $source[$key];
			}
		}
	}

	public static function arrayValue($options, $key, $default) {
		if (isset($options[$key])) {
			return $options[$key];
		} else {
			return $default;
		}
	}

	/**
	 * Returns a search tree with collapsable levels main group, group, subgroup and components.
	 * 
	 * @param array $navigation		Navigation true data.
	 */
	public static function searchNavigation($navigation) {
		$mainGroupNr = 1;
		$groupNr = 1;
		$subgroupNr = 1;
		
		/* hidden form to post the tree search request */
		echo CHtml::beginForm('search', 'post', array('class'=>'hidden', 'id'=>'post-search'));
		echo Chtml::hiddenField('Search[productgroep_id]');
		echo Chtml::hiddenField('Search[maaltijdtype_id]');
		echo Chtml::hiddenField('Search[maaltijdsubtype_id]');
		echo Chtml::hiddenField('component', 1, array('data-role'=>'component'));
		echo Chtml::hiddenField('Selected[mainGroup]');
		echo Chtml::hiddenField('Selected[group]');
		echo Chtml::hiddenField('Selected[subgroup]');
		echo Chtml::hiddenField('Selected[component]');
		echo CHtml::endForm();
		
		echo CHtml::openTag('ul', array('id'=>'maingroup', 'class'=>'search'));
		foreach($navigation['mainGroup'] as $mainGroup) {
			echo CHtml::openTag('li', array('class'=>'panel'));
			echo CHtml::openTag('a', array('data-toggle'=>'collapse', 'data-parent'=>'#maingroup', 'href'=>"#main-group-$mainGroupNr", 'class'=>'panel-title'));
			echo CHtml::encode($mainGroup['name'] . ' ');
			echo CHtml::tag('span', array('class'=>'icon fa fa-lg fa-caret-right'));
			echo CHtml::closeTag('a');
			$options = array(
				'id'=>"main-group-$mainGroupNr", 
				'class'=>'collapse'
			);
			/* expand the product group if selected */
			if ($mainGroup['selected'] === true) {
				$options['class'] .= ' in';
			}

			echo CHtml::openTag('ul', $options);

			foreach($mainGroup['group'] as $group) {
				echo CHtml::openTag('li', array('class'=>'panel'));
				echo CHtml::openTag('a', array('data-toggle'=>'collapse', 'data-parent'=>"#main-group-$mainGroupNr", 'href'=>"#group-$groupNr"));
				echo CHtml::encode($group['name'] . ' ');
				echo CHtml::tag('span', array('class'=>'icon fa fa-lg fa-caret-right'));
				echo CHtml::closeTag('a');
				$options = array(
					'id'=>"group-$groupNr", 
					'class'=>'collapse'
				);
				/* expand the group if selected */
				if ($group['selected'] === true) {
					$options['class'] .= ' in';
				}
				echo CHtml::openTag('ul', $options);
				
				foreach($group['subgroup'] as $subgroup) {
					$hasDescription = (strlen($subgroup['description']) > 0);
					echo CHtml::openTag('li', array('class'=>'panel subgroup'));
					$options = array(
						'data-toggle'=>'collapse',
						'data-parent'=>"#group-$groupNr",
						'href'=>"#subgroup-$subgroupNr",
					);
					$description = $subgroup['name'] . ' ';
					if ($hasDescription) {
						$options['title'] = $subgroup['description'];
						$description = ' ' . $description;
					}
					echo CHtml::openTag('a', $options);
					if ($hasDescription) {
						echo CHtml::tag('span', array('class'=>'fa fa-question-circle'));
					}
					echo CHtml::encode($description);
					echo CHtml::tag('span', array('class'=>'icon fa fa-lg fa-caret-right'));
					echo CHtml::closeTag('a');
					$options = array(
						'id'=>"subgroup-$subgroupNr", 
						'class'=>'collapse'
					);
					/* expand the group if selected */
					if ($subgroup['selected'] === true) {
						$options['class'] .= ' in';
					}
					echo CHtml::openTag('ul', $options);
					
					foreach($subgroup['components'] as $component) {
						$options = array('class'=>'component');
						if ($component['selected'] === true) {
							$options['class'] .= ' component-selected';
						}
						$componentField = $component['field'];
						$search = array(
                                    'Search[productgroep_id]'=>$mainGroup['id'],
                                    'Search[maaltijdtype_id]'=>$group['id'],
                                    'Search[maaltijdsubtype_id]'=>$subgroup['id'],
                                    "component"=>$componentField,
                                    'Selected[mainGroup]'=>$mainGroup['name'],
                                    'Selected[group]'=>$group['name'],
                                    'Selected[subgroup]'=>$subgroup['name'],
                                    'Selected[component]'=>$component['name'],
                        );
                        $options['data'] = json_encode($search);
						echo CHtml::tag('li', $options, $component['name']);
						
//                        echo CHtml::link(
//                            htmlspecialchars($component['name']),
//                            '#',
//                            array(
//								'class'=>'component',
//                                'submit'=>array('/search'),
//                                'params'=>array(
//                                    'Search[productgroep_id]'=>$mainGroup['id'],
//                                    'Search[maaltijdtype_id]'=>$group['id'],
//                                    'Search[maaltijdsubtype_id]'=>$subgroup['id'],
//                                    "Search[$componentField]"=>1,
//                                    'Selected[mainGroup]'=>$mainGroup['name'],
//                                    'Selected[group]'=>$group['name'],
//                                    'Selected[subgroup]'=>$subgroup['name'],
//                                    'Selected[component]'=>$component['name'],
//                                ),
//                            )
//                        );						
//						echo CHtml::closeTag('li');
					}
					echo CHtml::closeTag('ul');
					$subgroupNr++;
				}
				echo CHtml::closeTag('ul');
				echo CHtml::closeTag('li');
				$groupNr++;
			}
			echo CHtml::closeTag('ul');
			echo CHtml::closeTag('li');
			$mainGroupNr++;
		}
		echo CHtml::closeTag('ul');
	}

	public static function _searchNavigation($navigation, $options = array()) {
		$startTab = self::arrayValue ($options,'startTab',0);
		$url = self::arrayValue ($options,'url','');
		$id = self::arrayValue ($options,'id', 'search');

		$mainGroupNr = 0;
		$groupNr = 0;
		$subgroupNr = 0;

		$html = "";
		$html .= self::tabs($startTab+0) . CHtml::openTag('ul', array('id'=>'maingroup', 'class'=>'search')) .PHP_EOL;

		foreach($navigation['mainGroup'] as $mainGroup) {
			$mainGroupNr++;
			$mainGroupId = $mainGroup['id'];
			$html .= self::tabs($startTab+1) . CHtml::openTag('li', array('class'=>'panel')) .PHP_EOL;
			$html .= self::tabs($startTab+2) . CHtml::openTag('a', array('data-toggle'=>'collapse', 'data-parent'=>"#maingroup", 'class'=>'panel-title', 'href'=>"#maingroup$mainGroupNr")) .PHP_EOL;
			$html .= htmlspecialchars($mainGroup['name'] . " ") .'<span class="icon fa fa-caret-right fa-lg"></span>'  .PHP_EOL;
			$html .= self::tabs($startTab+2) . CHtml::closeTag('a') .PHP_EOL;
			$mainGroupClass = array('collapse');
			if ($mainGroup['selected']) {
				$mainGroupClass[] = 'in';
			}
			$mainGroupClass = implode(' ', $mainGroupClass);
			$html .= self::tabs($startTab+2) . CHtml::openTag('ul', array('id'=>"maingroup$mainGroupNr", 'class'=>$mainGroupClass)) .PHP_EOL;

			foreach($mainGroup['group'] as $group) {
				$groupNr++;
				$groupId = $group['id'];
				$class = array('panel-title');
//                    if (strlen($subgroup['description']) > 0) {
//                        $class[] = 'fa';
//                        $class[] = 'fa-question-circle';
//                    }
				$html .= self::tabs($startTab+3) . CHtml::openTag('li', array('class'=>'panel')) .PHP_EOL;
				$html .= self::tabs($startTab+4) . CHtml::openTag('a', array('data-toggle'=>'collapse', 'data-parent'=>"#maingroup$mainGroupNr", 'class'=>implode(' ', $class) , 'href'=>"#group$groupNr", 'title'=>$group['name'])) .PHP_EOL;
				$html .= htmlspecialchars($group['name'] . " ") .'<span class="icon fa fa-caret-right fa-lg"></span>'  .PHP_EOL;
				$html .= self::tabs($startTab+4) . CHtml::closeTag('a') .PHP_EOL;
				$groupClass = array('collapse');
				if ($group['selected']) {
					$groupClass[] = 'in';
				}
				$groupClass = implode(' ', $groupClass);
				$html .= self::tabs($startTab+4) . CHtml::openTag('ul', array('id'=>"group$groupNr", 'class'=>$groupClass)) .PHP_EOL;

//                    foreach($subgroup['components'] as $component) {
//                        $componentClass = array();
//                        if ($component['selected']) {
//                            $componentClass[] = 'selected';
//                        }
//                        $componentClass = implode(' ', $componentClass);
//                        $componentField = $component['field'];
//                        $html .= self::tabs($startTab+5) . CHtml::openTag('li', array('class'=>'panel')) .PHP_EOL;
//
//                        $html .= CHtml::link(
//                            htmlspecialchars($component['name']),
//                            '#',
//                            array(
//                                'class'=>$componentClass,
//                                'submit'=>array('search/index'),
//                                'params'=>array(
//                                    'Search[maaltijdtype_id]'=>$groupId,
//                                    'Search[maaltijdsubtype_id]'=>$subgroupId,
//                                    "Search[$componentField]"=>1,
//                                    'Selected[group]'=>$group['name'],
//                                    'Selected[subgroup]'=>$subgroup['name'],
//                                    'Selected[component]'=>$component['name'],
//                                ),
//                            )
//                        );
//
//                        $html .= self::tabs($startTab+5) . CHtml::closeTag('li') .PHP_EOL;
//                    }

				$html .= self::tabs($startTab+4) . CHtml::closeTag('ul') .PHP_EOL;
				$html .= self::tabs($startTab+3) . CHtml::closeTag('li') .PHP_EOL;
			}
			$html .= self::tabs($startTab+2) . CHtml::closeTag('ul') .PHP_EOL;
			$html .= self::tabs($startTab+1) . CHtml::closeTag('li') .PHP_EOL;
		}

		$html .= self::tabs($startTab+0) . CHtml::closeTag('ul') .PHP_EOL;

		return $html;
	}


	public static function __searchNavigation($navigation, $options = array()) {
		$startTab = self::arrayValue ($options,'startTab',0);
		$url = self::arrayValue ($options,'url','');
		$id = self::arrayValue ($options,'id', 'search');

		$groupNr = 0;
		$subgroupNr = 0;

		$html = "";
		$html .= self::tabs($startTab+0) . CHtml::openTag('ul', array('id'=>'group', 'class'=>'search')) .PHP_EOL;

		foreach($navigation['group'] as $group) {
			$groupNr++;
			$groupId = $group['id'];
			$html .= self::tabs($startTab+1) . CHtml::openTag('li', array('class'=>'panel')) .PHP_EOL;
			$html .= self::tabs($startTab+2) . CHtml::openTag('a', array('data-toggle'=>'collapse', 'data-parent'=>"#group", 'class'=>'panel-title', 'href'=>"#group$groupNr")) .PHP_EOL;
			$html .= htmlspecialchars($group['name'] . " ") .'<span class="icon fa fa-caret-right fa-lg"></span>'  .PHP_EOL;
			$html .= self::tabs($startTab+2) . CHtml::closeTag('a') .PHP_EOL;
			$groupClass = array('collapse');
			if ($group['selected']) {
				$groupClass[] = 'in';
			}
			$groupClass = implode(' ', $groupClass);
			$html .= self::tabs($startTab+2) . CHtml::openTag('ul', array('id'=>"group$groupNr", 'class'=>$groupClass)) .PHP_EOL;

			foreach($group['subgroup'] as $subgroup) {
				$subgroupNr++;
				$subgroupId = $subgroup['id'];
				$class = array('panel-title');
				if (strlen($subgroup['description']) > 0) {
					$class[] = 'fa';
					$class[] = 'fa-question-circle';
				}
				$html .= self::tabs($startTab+3) . CHtml::openTag('li', array('class'=>'panel')) .PHP_EOL;
				$html .= self::tabs($startTab+4) . CHtml::openTag('a', array('data-toggle'=>'collapse', 'data-parent'=>"#group$groupNr", 'class'=>implode(' ', $class) , 'href'=>"#subgroup$subgroupNr", 'title'=>$subgroup['description'])) .PHP_EOL;
				$html .= htmlspecialchars($subgroup['name'] . " ") .'<span class="icon fa fa-caret-right fa-lg"></span>'  .PHP_EOL;
				$html .= self::tabs($startTab+4) . CHtml::closeTag('a') .PHP_EOL;
				$subgroupClass = array('collapse');
				if ($subgroup['selected']) {
					$subgroupClass[] = 'in';
				}
				$subgroupClass = implode(' ', $subgroupClass);
				$html .= self::tabs($startTab+4) . CHtml::openTag('ul', array('id'=>"subgroup$subgroupNr", 'class'=>$subgroupClass)) .PHP_EOL;

				foreach($subgroup['components'] as $component) {
					$componentClass = array();
					if ($component['selected']) {
						$componentClass[] = 'selected';
					}
					$componentClass = implode(' ', $componentClass);
					$componentField = $component['field'];
					$html .= self::tabs($startTab+5) . CHtml::openTag('li', array('class'=>'panel')) .PHP_EOL;

					$html .= CHtml::link(
						htmlspecialchars($component['name']),
						'#',
						array(
							'class'=>$componentClass,
							'submit'=>array('search/index'),
							'params'=>array(
								'Search[maaltijdtype_id]'=>$groupId,
								'Search[maaltijdsubtype_id]'=>$subgroupId,
								"Search[$componentField]"=>1,
								'Selected[group]'=>$group['name'],
								'Selected[subgroup]'=>$subgroup['name'],
								'Selected[component]'=>$component['name'],
							),
						)
					);

					$html .= self::tabs($startTab+5) . CHtml::closeTag('li') .PHP_EOL;
				}

				$html .= self::tabs($startTab+4) . CHtml::closeTag('ul') .PHP_EOL;
				$html .= self::tabs($startTab+3) . CHtml::closeTag('li') .PHP_EOL;
			}
			$html .= self::tabs($startTab+2) . CHtml::closeTag('ul') .PHP_EOL;
			$html .= self::tabs($startTab+1) . CHtml::closeTag('li') .PHP_EOL;
		}

		$html .= self::tabs($startTab+0) . CHtml::closeTag('ul') .PHP_EOL;

		return $html;
	}


	public static function dialogForm($action = '', $method ='post', array $options = array()) {
		$startTab = (isset($options['startTab'])) ? $options['startTab'] : 0;
		$optionsForm = array();
		$fields = self::arrayValue($options, 'fields', array());
		$optionsDiv = array('class'=>'hidden');

		self::arrayCopy($optionsDiv, $options, array('role', 'id'));
		self::arrayCopyExcluded ($optionsForm, $options, array('id', 'fields', 'role', 'buttons'));

		$html = "";
		$html .= self::tabs($startTab+0) . CHtml::openTag('div', $optionsDiv) . PHP_EOL;
		$html .= self::tabs($startTab+1) . CHtml::beginForm($action, $method, $optionsForm) . PHP_EOL;

		foreach($fields as $field) {
			$label = self::arrayValue($field, 'label', '<no label>');
			$required = self::arrayValue($field, 'required', false);
			$name = self::arrayValue($field, 'name', '');
			$value = self::arrayValue($field, 'value', '');
			$type = self::arrayValue($field, 'type', 'text');
			$display = self::arrayValue($field, 'display', 'textbox');

			$html .= self::tabs($startTab+2) . CHtml::openTag('div', array('class'=>'form-group has-feedback')) . PHP_EOL;
			$html .= self::tabs($startTab+3) . CHtml::label($label, $name) . PHP_EOL;

			switch(strtolower($display)) {
				case 'textbox':
						$optionsTextField = array();
						$class = array();
						$class[] = 'form-control';
						$class[] = $type;
						if ($required) {$class[] = 'required';}
						$optionsTextField['class'] = implode(' ', $class);
						$html .= self::tabs($startTab+3) . CHtml::textField($name, $value, $optionsTextField) . PHP_EOL;
					break;
				default:
					throw new CException(get_class(self) . __METHOD__ . ": type '$type' not supported");
					break;
			}

			$html .= self::tabs($startTab+3) . CHtml::openTag('span', array('class'=>'glyphicon glyphicon-ok form-control-feedback hidden'));
			$html .= CHtml::closeTag('span');

			$html .= self::tabs($startTab+2) . CHtml::closeTag('div') . PHP_EOL;
		}

		$html .= self::tabs($startTab+1) . CHtml::endForm() . PHP_EOL;

		if (isset($options['buttons'])) {
			foreach($options['buttons'] as $button) {
				$label = $button['label'];
				$role = $button['role'];
				$html .= self::tabs($startTab+1) . CHtml::tag('button', array('type'=>'button', 'role'=>"$role", 'class'=>'btn btndefault'), $label) . PHP_EOL;
			}
		}

		$html .= self::tabs($startTab+0) . CHtml::closeTag('div') . PHP_EOL;

		return $html;
	}

public static function updatableField($name, $value, array $options = array()) {
	$displayAs = (isset($options['display']['displayAs'])) ? strtolower($options['display']['displayAs']) : 'textbox';
	$type = self::arrayValue($options, 'type', 'number');
	$startTab = (isset($options['startTab'])) ? $options['startTab'] : 0;

	if ($displayAs == 'textarea') {
		$optionsRoleInput['type'] = 'textarea';
	}

	$html = "";
	$html .= self::tabs($startTab + 0) . CHtml::openTag('div', array('data-role'=>'input-group')) . PHP_EOL;
	$html .= self::tabs($startTab + 1) . CHtml::openTag('div', array('role'=>'label')) . PHP_EOL;
	$html .= self::tabs($startTab + 2) . CHtml::openTag('span');

	switch($displayAs) {
		case 'dropdown':
			$valueOptions = (isset($options['valueOptions'])) ? $options['valueOptions'] : array();
			if (key_exists($value, $valueOptions)) {
				$html .= $valueOptions[$value];
			} else {
				$html .= '';
			}

			break;
		case 'radio':
			$labelTrue = (isset($options['display']['labelTrue'])) ? $options['display']['labelTrue'] : 'Ja';
			$labelFalse = (isset($options['display']['labelFalse'])) ? $options['display']['labelFalse'] : 'Nee';

			if($value) {
				$html .= $labelTrue;
			} else {
				$html .= $labelFalse;
			}
			break;

		case 'checkbox':
			if (isset($options['display']['labelTrue']) && isset($options['display']['labelTrue'])) {
				if($value) {
					$html .= $options['display']['labelTrue'];
				} else {
					$html .= $options['display']['labelFalse'];
				}
			} else {
				$html .= self::tabs($startTab + 2) .CHtml::openTag('div', array('class'=>'checkbox')) . PHP_EOL;
				$html .= self::tabs($startTab + 3) .CHtml::checkBox('', $value) .PHP_EOL;
				$html .= self::tabs($startTab + 2) .CHtml::closeTag('div') .PHP_EOL;
			}
			break;
		default:
			$html .= $value;
			break;
	}

	$html .= CHtml::closeTag('span') . PHP_EOL;
	$html .= self::tabs($startTab + 1) . CHtml::closeTag('div') . PHP_EOL;

	$optionsRoleInput = array('role'=>'input');

	$html .= self::tabs($startTab + 1) . CHtml::openTag('div',$optionsRoleInput) . PHP_EOL;

	switch($displayAs) {
		case 'textbox':
			$html .= self::tabs($startTab + 2) . CHtml::tag('input', array('name'=>$name, 'type'=>'text', 'class'=>"form-control $type", 'value'=>$value)) . PHP_EOL;
			break;

		case 'checkbox':
			$divOptions = array('class'=>'checkbox');
			if (isset($options['display']['labelTrue']) && isset($options['display']['labelTrue'])) {
				$displayValues = array();
				$displayValues['true'] = $options['display']['labelTrue'];
				$displayValues['false'] = $options['display']['labelFalse'];
				$divOptions['display-values'] = json_encode($displayValues);
			}

			$html .= self::tabs($startTab + 2) .CHtml::openTag('div', $divOptions) . PHP_EOL;
			$html .= self::tabs($startTab + 3) .CHtml::checkBox($name, $value) .PHP_EOL;
			$html .= self::tabs($startTab + 2) .CHtml::closeTag('div') .PHP_EOL;
			break;

		case 'textarea':
			$html .= self::tabs($startTab + 2) . CHtml::openTag('textarea', array('rows'=>5, 'name'=>$name, 'type'=>'text', 'class'=>"form-control $type"));
			$html .= $value;
			$html .= CHtml::closeTag('textarea') . PHP_EOL;
			break;

//                $html .= self::tabs($startTab + 2) .CHtml::openTag('div', array('class'=>'checkbox')) . PHP_EOL;
//                $html .= self::tabs($startTab + 3) .CHtml::checkBox($name, $value) .PHP_EOL;
//                $html .= self::tabs($startTab + 2) .CHtml::closeTag('div') .PHP_EOL;
//                break;

		case 'dropdown':
			$html .= self::tabs($startTab + 2) .CHtml::openTag('select', array('class'=>'form-control', 'name'=>$name)) . PHP_EOL;
			foreach($valueOptions as $optionValue=>$optionDescription) {
				$optionOptions = array('value'=>$optionValue);
				if ($value == $optionValue) {
					$optionOptions['selected']='selected';
				}
				$html .= self::tabs($startTab + 3) .CHtml::tag('option', $optionOptions, $optionDescription) .PHP_EOL;
			}
			$html .= self::tabs($startTab + 2) .CHtml::closeTag('select') .PHP_EOL;
			break;

		case 'radio':
			$labelTrue = (isset($options['display']['labelTrue'])) ? $options['display']['labelTrue'] : 'Ja';
			$labelFalse = (isset($options['display']['labelFalse'])) ? $options['display']['labelFalse'] : 'Nee';
			$valueTrue = (isset($options['display']['valueTrue'])) ? $options['display']['valueTrue'] : 1;
			$valueFalse = (isset($options['display']['valueFalse'])) ? $options['display']['valueFalse'] : 0;

			// option true
			$html .= self::tabs($startTab + 2) .CHtml::openTag('div', array('class'=>'radio')) . PHP_EOL;
			$html .= self::tabs($startTab + 3) .CHtml::openTag('label', array()) . PHP_EOL;
//                $html .= self::tabs($startTab + 3) .CHtml::openTag('label', array('class'=>'radio-inline')) . PHP_EOL;
			$html .= self::tabs($startTab + 4) .CHtml::radioButton($name, ($value == $valueTrue), array('value'=>$valueTrue));
			$html .= $labelTrue;
			$html .= CHtml::closeTag('label') . PHP_EOL;
			$html .= self::tabs($startTab + 2) .CHtml::closeTag('div') .PHP_EOL;

			// option false
			$html .= self::tabs($startTab + 2) .CHtml::openTag('div', array('class'=>'radio')) . PHP_EOL;
			$html .= self::tabs($startTab + 3) .CHtml::openTag('label', array()) . PHP_EOL;
//                $html .= self::tabs($startTab + 3) .CHtml::openTag('label', array('class'=>'radio-inline')) . PHP_EOL;
			$html .= self::tabs($startTab + 4) .CHtml::radioButton($name, ($value == $valueFalse), array('value'=>$valueFalse));
			$html .= $labelFalse;
			$html .= CHtml::closeTag('label') . PHP_EOL;
			$html .= self::tabs($startTab + 2) .CHtml::closeTag('div') .PHP_EOL;
			break;
		default:
			throw new CException("Render::groupTrueFalse: option displayAs '$displayAs' not supported.");
			break;
	}

	$html .= self::tabs($startTab + 1) . CHtml::closeTag('div') . PHP_EOL;
	$html .= self::tabs($startTab + 0) . CHtml::closeTag('div') . PHP_EOL;

	return $html;
}

public static function actionButton($icon, $role=null) {
	$options = array('class'=>'pull-right btn-success');
	if ($role) {
		$options['role']=$role;
	}

	$html = CHtml::openTag('button', $options);
	$html .= CHtml::openTag('span', array('class'=>"glyphicon $icon"));
	$html .= CHtml::closeTag('span');
	$html .= CHtml::closeTag('button');

	return $html;
}

public static function actionButtonEdit() {
	return self::actionButton('glyphicon-pencil', 'edit');
}

public static function actionButtonSave() {
	return self::actionButton('glyphicon-floppy-disk', 'save');
}

public static function actionButtonCancel() {
	return self::actionButton('glyphicon-repeat', 'cancel');
}

public static function panel($title, $body, array $options = array()) {
	$startTab = (isset($options['startTab'])) ? $options['startTab'] : 0;

	$optionsPanel = array();
	if (isset($options['id'])) {
		$optionsPanel['id'] = $options['id'];
	}

	$mode = (isset($options['mode'])) ? 'mode-'.$options['mode']  : '';

	$optionsPanel['class'] = "panel panel-success $mode";

	if (self::optionUpdateButtons($options)) {
		$updateButtons = true;
	} else {
		$updateButtons = false;
	}

	$html = "";
	$html .= self::tabs($startTab + 0) . CHtml::openTag('div', $optionsPanel) . PHP_EOL;
	$html .= self::tabs($startTab + 1) . CHtml::openTag('div', array('class'=>'panel-heading'));
	$html .= $title;
	if ($updateButtons) {
			$html .= self::actionButtonCancel() . self::actionButtonEdit(). self::actionButtonSave();
	}
	$html .= CHtml::closeTag('div') . PHP_EOL;
	$html .= self::tabs($startTab + 1) . CHtml::openTag('div', array('class'=>'panel-body')) . PHP_EOL;
	$html .= $body;
	$html .= self::tabs($startTab + 1) . CHtml::closeTag('div') . PHP_EOL;
	$html .= self::tabs($startTab + 0) . CHtml::closeTag('div') . PHP_EOL;

	return $html;
}

private static function groupRow($column1, $column2=null, $column3=null, $options = array()) {
	$availableColumns = 12;
	$startTabs = self::arrayValue($options, 'startTabs', 0);
	$groupRowOptions = array ('class'=>'group-row');
	$firstColumnLabel = self::arrayValue($options, 'firstColumnLabel', true);

	if (isset($options['class'])) {
		$groupRowOptions['class'] .= ' ' . $options['class'];
	}

	$groupColumnOptions = array('class'=>'group-column');

	$html = self::tabs($startTabs + 0) .CHtml::openTag('div', array('class'=>'row')) . PHP_EOL;
	// first column
	if ($column2 === null) {
		$colOptions = array('class'=>'col-md-12');
		$availableColumns -= 12;
	} else {
		if (isset($options['columns']['col1']['span'])) {
			$columnSpan = (int)$options['columns']['col1']['span'];
			$colOptions = array('class'=>"col-md-$columnSpan");
			$availableColumns -= $columnSpan;
		} else {
			$colOptions = array('class'=>'col-md-3');
			$availableColumns -= 3;
		}
	}
	if ($firstColumnLabel) {
		$colOptions['class'] .= ' col-label';
	}
	$html .= self::tabs($startTabs + 1) .CHtml::tag('div', $colOptions, $column1) . PHP_EOL;
	// second column
	if ($column2 !== null) {
		// put plain text within div tags
		if (strpos($column2, '<div>') === false) {$column2 = CHtml::tag('div', array(), $column2);};
		if ($column3 === null) {
			$colOptions = array('class'=>"col-md-$availableColumns");
		} else {
			if (isset($options['columns']['col2']['span'])) {
				$columnSpan = (int)$options['columns']['col2']['span'];
				$colOptions = array('class'=>"col-md-$columnSpan");
				$availableColumns -= $columnSpan;
			} else {
				$columnSpan = (int)($availableColumns/2);
				$colOptions = array('class'=>"col-md-$columnSpan");
				$availableColumns -= $columnSpan;
			}
		}
		if (isset($options['columns']['col2']['align']) && $options['columns']['col2']['align'] = 'right') {
			$colOptions['class'] .= ' align-right';
		}
		$html .= self::tabs($startTabs + 1) .CHtml::tag('div', $colOptions, $column2) . PHP_EOL;
	}

	// third column
	if ($column3 !== null) {
		if (strpos($column3, '<div>') === false) {$column3 = CHtml::tag('div', array(), $column3);};
		if (isset($options['columns']['col3']['span'])) {
			$columnSpan = (int)$options['columns']['col3']['span'];
			$colOptions = array('class'=>"col-md-$columnSpan");
		} else {
			if (($availableColumns % 2) == 1) {
				$columnSpan = $availableColumns - 1;
				$colOptions = array('class'=>"col-md-$columnSpan col-md-offset-1");
			} else {
				$columnWidth = $availableColumns;
				$colOptions = array('class'=>"col-md-$columnSpan");
			}
		}

		if (isset($options['columns']['col3']['align']) && $options['columns']['col3']['align'] = 'right') {
			$colOptions['class'] .= ' align-right';
		}
		$html .= self::tabs($startTabs + 1) .CHtml::tag('div', $colOptions, $column3) . PHP_EOL;
	}

	$html .= self::tabs($startTabs + 0) .CHtml::closeTag('div');
	return $html;
}

public static function groupTrueFalse($attributes, array $options = array()) {
	$optionsUpdatableField = array();
	$startTab = (isset($options['startTab'])) ? $options['startTab'] : 0;
	$action = (isset($options['action'])) ? $options['action'] : '';

	if (isset($options['display'])) {
		$optionsUpdatableField['display'] = $options['display'];
	}

	$formOptions = array();
	if (key_exists('id', $options)) {$formOptions['id'] = $options['id'];}
	$html = "";

	$html .= self::tabs($startTab + 2) . CHtml::beginForm($action, 'post', $formOptions) . PHP_EOL;

	foreach($attributes as $attribute) {
		$label = $attribute['label'];
		$fieldNameFieldValuePair = $attribute['values'][0];
		$fieldName = array_keys($fieldNameFieldValuePair);
		$fieldName = $fieldName[0];
		$fieldValue = $fieldNameFieldValuePair[$fieldName];
		$value = self::updatableField($fieldName, $fieldValue,$optionsUpdatableField);
		$html .= self::groupRow($label, $value, null,
				array (
					'columns' => array(
						'col2' => array(
							'align'=>'right'
						)
					)
				)
		);
	}

	$html .= self::tabs($startTab + 2) . CHtml::endForm() . PHP_EOL;

	return $html;
}



public static function bereiding($attributes, array $options = array()) {
	$defaultDisplay = array('displayAs' =>'textbox');

	$optionsPanel = array('mode'=>'normal', 'id'=>'bereidingswijze');
	if (self::optionUpdateButtons($options)) {
		$optionsPanel['updateButtons'] = true;
	}

	$action = (isset($options['action'])) ? $options['action'] : '';
	$html = CHtml::beginForm($action);

	foreach($attributes as $attribute) {
		$label = $attribute['label'];
		$fieldValue = $attribute['values'][0];
		$field = array_keys($fieldValue);
		$field = $field[0];
		$value = $fieldValue[$field];
		$options['display'] = (isset($attribute['display'])) ? $attribute['display'] : $defaultDisplay;
		$options['type'] = 'text';
		if (isset($attribute['valueOptions'])) {
			$options['valueOptions'] = $attribute['valueOptions'];
		}
		$rowOptions = array(
			'columns'=>array(
				'col1'=>array('span' => 3),
				'col2'=>array('span' => 9),
			 )
		);

		$html .= self::groupRow($label, self::updatableField($field, $value, $options), '', $rowOptions);
	}

	$html .= CHtml::endForm();

	$html = self::panel("Bereidingswijze", $html, $optionsPanel);
//        $html .= self::legend("Gaarheid: <b>+</b> beetgaar, <b>++</b> gaar, <b>+++</b> extra gaar");
	
	return $html;
}

/*    
public static function bereiding($attributes, array $options = array()) {
	$startTab = (isset($options['startTab'])) ? $options['startTab'] : 0;

	$optionsGroup['id']='bereiding';
	$optionsGroup['display'] = array('displayAs' =>'text');

	$optionsPanel = array(
		'mode'=>'normal',
		'id'=>'bereiding',
	);
	$optionsPanel['updateButtons'] = true;

	$action = (isset($options['action'])) ? $options['action'] : '';

	$html = "";

	$html .= self::tabs($startTab + 2) . CHtml::beginForm($action, 'post') . PHP_EOL;

	foreach($attributes as $attribute) {
		$label = $attribute['label'];
		$fieldValue = $attribute['values'][0];
		$field = array_keys($fieldValue);
		$field = $field[0];
		$value = $fieldValue[$field];
		$options['display'] = (isset($attribute['display'])) ? $attribute['display'] : $defaultDisplay;
		if (isset($attribute['valueOptions'])) {
			$options['valueOptions'] = $attribute['valueOptions'];
		}

		$html .= self::groupRow($label, self::updatableField($field, $value, $options), null,
			array (
				'columns' => array(
					'col2' => array(
						'align'=>'right'
					)
				)
			));
	}

	$html .= self::tabs($startTab + 2) . CHtml::endForm() . PHP_EOL;

	return self::panel("Bereidingswijze", $html, $optionsPanel);
}
*/
public static function allergenen($attributes, array $options = array()) {
	$options['id']='allergenen';
	$options['display'] = array(
		'displayAs' =>'radio',
		'labelTrue' => '+',
		'labelFalse' => '-',
	);


	$optionsPanel = array(
		'mode'=>'normal',
		'id'=>'allergenen',
	);

	if (self::optionUpdateButtons($options)) {
		$optionsPanel['updateButtons'] = true;
	}
	$html = self::panel("Allergenen", self::groupTrueFalse($attributes, $options), $optionsPanel);
	
	$html .= self::legend("Legenda: <b>+</b> aanwezig in receptuur, <b>-</b> afwezig in receptuur");
	
	return $html;
}

public static function claims($attributes, array $options = array()) {
	$optionsGroup['id']='claims';
	$optionsGroup['display'] = array('displayAs' =>'radio');

	$optionsPanel = array(
		'mode'=>'normal',
		'id'=>'claims',
	);

	if (key_exists('action', $options)) {
		$optionsGroup['action'] = $options['action'];
	}

	if (self::optionUpdateButtons($options)) {
		$optionsPanel['updateButtons'] = true;
	}

	$html = self::groupTrueFalse($attributes, $optionsGroup);

	return self::panel("Claims", $html , $optionsPanel);
}

public static function componenten($attributes, array $options = array()) {
	$options['id']='componenten';
	$options['display'] = array(
		'displayAs' =>'checkbox',
	);

	$optionsPanel = array('mode'=>'normal');

	if (self::optionUpdateButtons($options)) {
		$optionsPanel['updateButtons'] = true;
	}
	return self::panel("Maaltijdcomponenten", self::groupTrueFalse($attributes, $options), $optionsPanel);
}

public static function vrijVan($attributes, array $options = array()) {
	$options['id']='vrijvan';
//        $options['display'] = array(
//            'displayAs' =>'checkbox',
//        );
	$options['display'] = array(
		'displayAs' =>'checkbox',
		'labelTrue' => '<span class="glyphicon glyphicon-ok"></span>',
//            'labelTrue' => 'Ja',
		'labelFalse' => '',
	);

	$optionsPanel = array('mode'=>'normal');

	if (self::optionUpdateButtons($options)) {
		$optionsPanel['updateButtons'] = true;
	}
	return self::panel("Vrij van", self::groupTrueFalse($attributes, $options), $optionsPanel);
}

public static function legend($description) {
	$html = CHtml::openTag('div', array('class'=>'row legend'));
	$html .= CHtml::openTag('div', array('class'=>'col-md-12'));
	$html .= $description;
	$html .= CHtml::closeTag('div');
	$html .= CHtml::closeTag('div');
	
	return $html;
}

public static function product($attributes, array $options = array()) {
	$defaultDisplay = array('displayAs' =>'textbox');

	$optionsPanel = array('mode'=>'normal', 'id'=>'product');
	if (self::optionUpdateButtons($options)) {
		$optionsPanel['updateButtons'] = true;
	}

	$action = (isset($options['action'])) ? $options['action'] : '';
	$html = CHtml::beginForm($action);

	foreach($attributes as $attribute) {
		$label = $attribute['label'];
		$fieldValue = $attribute['values'][0];
		$field = array_keys($fieldValue);
		$field = $field[0];
		$value = $fieldValue[$field];
		$options['display'] = (isset($attribute['display'])) ? $attribute['display'] : $defaultDisplay;
		$options['type'] = 'text';
		if (isset($attribute['valueOptions'])) {
			$options['valueOptions'] = $attribute['valueOptions'];
		}
		$rowOptions = array(
			'columns'=>array(
				'col1'=>array('span' => 6),
			 )
		);

		$html .= self::groupRow($label, self::updatableField($field, $value, $options), '', $rowOptions);
	}

	$html .= CHtml::endForm();

	$html = self::panel("Productgegevens", $html, $optionsPanel);
	$html .= self::legend("Gaarheid: <b>+</b> beetgaar, <b>++</b> gaar, <b>+++</b> extra gaar");
	
	return $html;
}

private static function optionUpdateButtons($options) {
	if (isset($options['updateButtons']) && $options['updateButtons'] == true) {
		return true;
	} else {
		return false;
	}
}

private static function freeText($title, $model, $name, array $options = array()) {
	$startTab = (isset($options['startTab'])) ? $options['startTab'] : 0;
	$optionsPanel = array();
	$optionsPanel['mode'] = 'normal';

	if (self::optionUpdateButtons($options)) {
		$optionsPanel['updateButtons'] = true;
	}

	$optionsUpdatableField = array(
		'display' => array(
			'displayAs' => 'textarea'
		)
	);

	$action = self::arrayValue($options, 'action', '');

	$html = self::tabs($startTab + 0) . CHtml::beginForm($action) . PHP_EOL;
	$html .= self::tabs($startTab + 1) . Chtml::openTag('div', array('class'=>'row')) . PHP_EOL;
	$html .= self::tabs($startTab + 2) . Chtml::openTag('div', array('class'=>'col-md-12')) . PHP_EOL;
	$html .= self::tabs($startTab + 3) . self::updatableField($name, $model->$name, $optionsUpdatableField);
	$html .= self::tabs($startTab + 2) . Chtml::closeTag('div') . PHP_EOL;
	$html .= self::tabs($startTab + 1) . Chtml::closeTag('div') . PHP_EOL;
	$html .= self::tabs($startTab + 0) . CHtml::endForm() . PHP_EOL;
	return self::panel($title, $html, $optionsPanel);
}

public static function ingredienten($model, $name, array $options = array()) {
	return self::freeText("Ingredienten", $model, $name, $options);
}

public static function opmerkingen($model, $name, array $options = array()) {
	return self::freeText("Opmerkingen", $model, $name, $options);
}

public static function voedingswaarden($attributes, array $options = array()) {
	$startTab = (isset($options['startTab'])) ? $options['startTab'] : 0;

	$optionsPanel = array('mode'=>'normal');
	$optionsPanel['id'] = 'voedingswaarden';

	if (self::optionUpdateButtons($options)) {
		$optionsPanel['updateButtons'] = true;
	}
	$action = (isset($options['action'])) ? $options['action'] : '';

	$html = "";

	$html .= self::tabs($startTab + 2) . CHtml::beginForm($action) . PHP_EOL;

	$optionsRow = array(
		'class'=>'header',
		'columns'=> array(
				'col1' => array('span'=>4),
				'col2' => array('align'=>'right'),
				'col3' => array('align'=>'right')
		),
	);

	// header
	$html .= self::groupRow('<i>Voedingswaarde</i>', '<b><i>per 100g</i></b>', '<b><i>per portie</i></b>', $optionsRow);

	// remove the header class
	unset($optionsRow['class']);
	foreach($attributes as $attribute) {
		$label = $attribute['label'];
		$value1 = null;
		$value2 = null;

		foreach($attribute['values'] as $name=>$value) {
			if ($value1 == null) {
				$value1 = self::updatableField($name, $value, array('startTab'=>4));
			} else {
				$value2 = self::updatableField($name, $value, array('startTab'=>4));
			}
		}
		if ($value1 == null) {$value1 = '';}
		if ($value2 == null) {$value2 = '';}
		$html .= self::groupRow($label, $value1, $value2, $optionsRow);
		}

	$html .= self::tabs($startTab + 2) . CHtml::endForm() . PHP_EOL;

	return self::panel("Voedingswaarden", $html, $optionsPanel);
}

	public static function menu($menuDef, $page = "") {
		$html = "";
		$html .= CHtml::openTag('ul', array('class'=>'nav nav-pills'));

		$currentUrl = strtolower(Yii::app()->request->url);

		foreach($menuDef as $name=>$def) {
			if (is_array($def)) {
				$html .= CHtml::openTag('li', array('class'=>'dropdown'));
				// dropdown header
				$html .= CHtml::openTag('a', array('class'=>'dropdown-togle', 'data-toggle'=>'dropdown', 'href'=>'#'));
				$html .= $name . CHtml::tag('span', array('class'=>'caret'));
				$html .= CHtml::closeTag('a');
				// dropdown items
				$html .= CHtml::openTag('ul', array('class'=>'dropdown-menu'));

				$html .= Render::menu($def);
				/*
				foreach($def as $name=>$href) {
					$html .= Chtml::openTag('li');
					$html .= CHtml::link($name, $href);
					$html .= Chtml::closeTag('li');
				}
				 */
				$html .= CHtml::closeTag('ul');

				$html .= CHtml::closeTag('li');
			} else {
//                    $class = (strtolower($page) == strtolower($def)) ? "active" : "";

				if ($currentUrl == strtolower($def)) {
					$class = 'active';
				} else {
					$class = '';
				}

				$html .= Chtml::openTag('li', array('class'=>$class));
				$html .= CHtml::link($name, $def);
				$html .= Chtml::closeTag('li');
			}
		}

		$html .= CHtml::closeTag('ul');

		return $html;
	}

	public static function renderAside($asideDef) {
		$html = PHP_EOL;
		$html .= "\t" . CHtml::openTag('div', array('class'=>'panel panel-primary')) .PHP_EOL;
		$html .= "\t\t" . CHtml::tag('div', array('class'=>'panel-heading'), $asideDef['title']) .PHP_EOL;
		$html .= "\t\t" . CHtml::openTag('div', array('class'=>'panel-body')) .PHP_EOL;
		foreach ($asideDef['items'] as $name=>$def) {
			$html .= "\t\t\t" . CHtml::tag('div', array(), $name) .PHP_EOL;
		}
		$html .= "\t\t" . CHtml::closeTag('div') .PHP_EOL;
		$html .= "\t" . CHtml::closeTag('div') .PHP_EOL;

		return $html;
	}

	public static function renderInput($model, $attribute, $hidden = false, $showLabel = true) {
		$html = CHtml::openTag('div', array('class'=>'row')) . PHP_EOL;

		if ($showLabel) {
			// display label
			$html .= "\t" . CHtml::openTag('div', array('class'=>'col-md-2')) . PHP_EOL;
			if (!$hidden) {
				$attributeLabels = $model->attributeLabels();
				$html .= "\t\t" .CHtml::label($attributeLabels[$attribute], $attribute) . PHP_EOL;
//                    $html .= "\t\t" .CHtml::label($model->attributeLabels()[$attribute], $attribute) . PHP_EOL;
			}
			$html .= "\t" . CHtml::closeTag('div') . PHP_EOL;

			// display value in second column
			$html .= "\t" . CHtml::openTag('div', array('class'=>'col-md-10')) . PHP_EOL;
		} else {
			// display value full width
			$html .= "\t" . CHtml::openTag('div', array('class'=>'col-md-12')) . PHP_EOL;
		}

		if ($hidden) {
			$type = 'hidden';
		} else {
			$attributeTypes = $model->attributeTypes();
			switch(strtolower($attributeTypes[$attribute])) {
//                switch(strtolower($model->attributeTypes()[$attribute])) {
				case 'string':
				case 'int':
				default:
					$type = 'text';
					break;
			}

		}

		$html .= "\t\t" . CHtml::tag('input', array('name'=>$attribute, 'value'=>$model->$attribute, 'type'=>$type, 'class'=>'form-control')) . PHP_EOL;
		$html .= "\t" . CHtml::closeTag('div') . PHP_EOL;

		$html .= CHtml::closeTag('div') . PHP_EOL;
		return $html;
	}

	public static function tabs($nrOfTabs) {
		return str_repeat("\t", $nrOfTabs);
	}

	public static function renderForm($model) {
		$controller = strtolower($model->modelName());
		if ($model->isNewRecord) {
			$action = Yii::app()->createUrl("/$controller/create");
			$buttonLabel = "Aanmaken";
		} else {
			$action = Yii::app()->createUrl("/$controller/update", array('id'=>$model->id));
			$buttonLabel = "Opslaan";
		}

		$html = "";
		$html .= self::tabs(0) . CHtml::beginForm($action,'post', array('id'=>'update')) . PHP_EOL;

		foreach($model->attributesToDisplay() as $attribute) {
			$label = "label$attribute";
			$name = $model->modelName() . "[$attribute]";

			// row
			$html .= self::tabs(1) . CHtml::openTag('div', array('class'=>'row')) . PHP_EOL;

			// first column
			$html .= self::tabs(2) . CHtml::openTag('div', array('class'=>'col-md-2')) . PHP_EOL;
			// label
			$html .= self::tabs(3) . CHtml::openTag('span', array('role'=>'label'));
			$html .= self::tabs(0) . CHtml::encode($model->$label);
			$html .= self::tabs(0) . CHtml::closeTag('span') . PHP_EOL;
			// end of first column
			$html .= self::tabs(2) . CHtml::closeTag('div') . PHP_EOL;

			//second column
			$html .= self::tabs(2) . CHtml::openTag('div', array('class'=>'col-md-10')) . PHP_EOL;
			//input
			$html .= self::tabs(3) . CHtml::tag('input',
				array(
					'name'=>$name,
					'type'=>'text',
					'class'=>'form-control',
					'value'=>$model->$attribute
				)) . PHP_EOL;
			// end of second column
			$html .= self::tabs(2) . CHtml::closeTag('div') . PHP_EOL;

			// end of row
			$html .= self::tabs(1) . CHtml::closeTag('div') . PHP_EOL;
		}

		$html .= self::tabs(0) . CHtml::endForm() . PHP_EOL;

		$html .= self::tabs(0) . CHtml::openTag('div', array('class'=>'row')) . PHP_EOL;
		$html .= self::tabs(1) . CHtml::openTag('div', array('class'=>'col-md-12')) . PHP_EOL;
		$html .= self::tabs(2) . CHtml::tag('br') . PHP_EOL;
		$html .= self::tabs(2) . CHtml::button($buttonLabel,
			array(
				'id'=>'submit',
				'type'=>'button',
				'class'=>'btn btn-primary pull-right',
			)) . PHP_EOL;
		$html .= self::tabs(1) . CHtml::closeTag('div') . PHP_EOL;
		$html .= self::tabs(0) . CHtml::closeTag('div') . PHP_EOL;

		return $html;
	}
}

$script = <<<JS
		$("div[role='label'] .checkbox").click(function (e) {
			e.preventDefault();
			return false;
		});
JS;

Yii::app()->clientScript->registerScript('disableCheckbox',$script);
?>
