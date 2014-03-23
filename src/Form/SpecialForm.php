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

class SpecialForm extends BaseForm
{
    public function __construct($name = null)
    {
        $this->module = Pi::service('module')->current();
        $this->category = array(
            -1 => __('Home Page'),
            0 => __('All Category'),
        );
        parent::__construct($name);
    }

    public function getInputFilter()
    {
        if (!$this->filter) {
            $this->filter = new SpecialFilter;
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
        // item
        $this->add(array(
            'name' => 'item',
            'type' => 'Module\Guide\Form\Element\Item',
            'options' => array(
                'label' => __('Item'),
                'module' => $this->module,
            ),
            'attributes' => array(
                'description' => __('Select item for add to Special'),
                'size' => 1,
                'multiple' => 0,
            ),
        ));
        // time_publish
        $this->add(array(
            'name' => 'time_publish',
            'options' => array(
                'label' => __('Publish date'),
            ),
            'attributes' => array(
                'type' => 'date',
                'value' => date('Y-m-d'),
                'description' => '',
            )
        ));
        // time_expire
        $this->add(array(
            'name' => 'time_expire',
            'options' => array(
                'label' => __('Expire date'),
            ),
            'attributes' => array(
                'type' => 'date',
                'value' => date('Y-m-d', strtotime('+1 week')),
                'description' => '',
            )
        ));
        // status
        $this->add(array(
            'name' => 'status',
            'type' => 'select',
            'options' => array(
                'label' => __('Status'),
                'value_options' => array(
                    1 => __('Published'),
                    2 => __('Pending review'),
                    3 => __('Draft'),
                    4 => __('Private'),
                ),
            ),
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