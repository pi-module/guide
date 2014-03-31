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

class LocationCategoryForm extends BaseForm
{
    public function __construct($name = null, $option = array())
    {
        $this->dd = $option['dd']; 
        $this->module = Pi::service('module')->current();
        parent::__construct($name);
    }

    public function getInputFilter()
    {
        if (!$this->filter) {
            $this->filter = new LocationCategoryFilter;
        }
        return $this->filter;
    }

    public function init()
    {
        // id
        $this->add(array(
            'name' => 'id',
            'attributes' => array(
                'type' => 'hidden',
            ),
        ));
        // parent
        if ($this->dd == 'new') {
            $this->add(array(
                'name' => 'parent',
                'type' => 'Module\Guide\Form\Element\LocationCategory',
                'options' => array(
                    'label' => __('Parent'),
                    'module' => $this->module,
                ),
            ));
        } else {
            $this->add(array(
                'name' => 'parent',
                'attributes' => array(
                    'type' => 'hidden',
                ),
            ));
        }
        // title
        $this->add(array(
            'name' => 'title',
            'options' => array(
                'label' => __('Title'),
            ),
            'attributes' => array(
                'type' => 'text',
                'description' => '',
            )
        ));
        // Save
        $this->add(array(
            'name' => 'submit',
            'type' => 'submit',
            'attributes' => array(
                'value' => __('Submit'),
            )
        ));
    }
}