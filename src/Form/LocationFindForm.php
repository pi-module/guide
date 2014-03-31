<?php
/**
 * Pi Engine (http://pialog.org)
 *
 * @link            http://code.pialog.org for the Pi Engine source repository
 * @copyright       Copyright (c) Pi Engine http://pialog.org
 * @license         http://pialog.org/license.txt New BSD License
 */

/**
 * @author Hossein Azizabadi <azizabadi@faragostaresh.com>
 */
namespace Module\Guide\Form;

use Pi;
use Pi\Form\Form as BaseForm;

class LocationFindForm  extends BaseForm
{
    public function __construct($name = null, $option = array())
    {
        $this->field = $option['field'];
        parent::__construct($name);
    }

    public function init()
    {
        // Set extra field
        if (!empty($this->field)) {
            foreach ($this->field as $field) {
                $this->add(array(
                    'name' => $field['id'],
                    'type' => 'select',
                    'options' => array(
                        'label' => $field['title'],
                        'value_options' => $this->makeArray($field['value']),
                    ),
                    'attributes' => array(
                        'id' => 'form-location-' . $field['id'],
                        'class' => 'form-location', 
                    )
                ));
            }
        }
        // Save
        $this->add(array(
            'name' => 'submit',
            'type' => 'submit',
            'attributes' => array(
                'value' => __('Submit'),
            )
        ));
    }

    public function makeArray($array)
    {
        $list = array();
        $list[0] = '';
        foreach ($array as $value) {
            $list[$value['id']] = $value['title'];
        }
        return $list;
    }
}