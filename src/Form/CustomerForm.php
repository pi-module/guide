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

class CustomerForm  extends BaseForm
{
    public function __construct($name = null, $option = array())
    {
        parent::__construct($name);
    }

    public function getInputFilter()
    {
        if (!$this->filter) {
            $this->filter = new CustomerFilter;
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
        // first_name
        $this->add(array(
            'name' => 'first_name',
            'options' => array(
                'label' => __('First name'),
            ),
            'attributes' => array(
                'type' => 'text',
                'description' => '',
            )
        ));
        // last_name
        $this->add(array(
            'name' => 'last_name',
            'options' => array(
                'label' => __('Last name'),
            ),
            'attributes' => array(
                'type' => 'text',
                'description' => '',
            )
        ));
        // phone
        $this->add(array(
            'name' => 'phone',
            'options' => array(
                'label' => __('Phone'),
            ),
            'attributes' => array(
                'type' => 'text',
                'description' => '',
            )
        ));
        // mobile
        $this->add(array(
            'name' => 'mobile',
            'options' => array(
                'label' => __('Mobile'),
            ),
            'attributes' => array(
                'type' => 'text',
                'description' => '',
            )
        ));
        // company
        $this->add(array(
            'name' => 'company',
            'options' => array(
                'label' => __('Company'),
            ),
            'attributes' => array(
                'type' => 'text',
                'description' => '',
            )
        ));
        // address
        $this->add(array(
            'name' => 'address',
            'options' => array(
                'label' => __('Address'),
            ),
            'attributes' => array(
                'type' => 'textarea',
                'rows' => '5',
                'cols' => '40',
                'description' => '',
            )
        ));
        // country
        $this->add(array(
            'name' => 'country',
            'options' => array(
                'label' => __('Country'),
            ),
            'attributes' => array(
                'type' => 'text',
                'description' => '',
            )
        ));
        // city
        $this->add(array(
            'name' => 'city',
            'options' => array(
                'label' => __('City'),
            ),
            'attributes' => array(
                'type' => 'text',
                'description' => '',
            )
        ));
        // zip_code
        $this->add(array(
            'name' => 'zip_code',
            'options' => array(
                'label' => __('Zip code'),
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
                'value' => __('Creating an account'),
            )
        ));
    }
}