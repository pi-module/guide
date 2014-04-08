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

class LogForm  extends BaseForm
{
    public function __construct($name = null, $option = array())
    {
        parent::__construct($name);
    }

    public function getInputFilter()
    {
        if (!$this->filter) {
            $this->filter = new LogFilter;
        }
        return $this->filter;
    }

    public function init()
    {
        // section
        $this->add(array(
            'name' => 'section',
            'type' => 'select',
            'options' => array(
                'label' => __('Section'),
                'value_options' => array(
                    ''                  => __('All'),
                    'category'          => __('Category'),
                    'extra'             => __('Extra'),
                    'item'              => __('Item'),
                    'location'          => __('Location'),
                    'review'            => __('Review'),
                    'special'           => __('Special'),
                    'attach'            => __('Attach'),
                    'service'           => __('Service'),
                    'service_category'  => __('Service category'),
                    'package'           => __('Package'),
                    'score'             => __('Score'),
                    'order'             => __('Orders'),
                ),
            ),
        ));
        // uid
        $this->add(array(
            'name' => 'uid',
            'options' => array(
                'label' => __('User'),
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
                'value' => __('Filter'),
                'class' => 'btn btn-primary',
            )
        ));
    }
}