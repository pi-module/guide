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
use Zend\InputFilter\InputFilter;

class PackageFilter extends InputFilter
{
    public function __construct($option = array())
    {
        // id
        $this->add(array(
            'name' => 'id',
            'required' => false,
        ));
        // title
        $this->add(array(
            'name' => 'title',
            'required' => true,
            'filters' => array(
                array(
                    'name' => 'StringTrim',
                ),
            ),
        ));
        // description
        $this->add(array(
            'name' => 'description',
            'required' => false,
            'filters' => array(
                array(
                    'name' => 'StringTrim',
                ),
            ),
        ));
        // status
        $this->add(array(
            'name' => 'status',
            'required' => true,
        ));
        // image
        $this->add(array(
            'name' => 'image',
            'required' => false,
        ));
        // price
        $this->add(array(
            'name' => 'price',
            'required' => false,
            'filters' => array(
                array(
                    'name' => 'StringTrim',
                ),
            ),
        ));
        // stock_all
        $this->add(array(
            'name' => 'stock_all',
            'required' => false,
            'filters' => array(
                array(
                    'name' => 'StringTrim',
                ),
            ),
        ));
        // time_period
        $this->add(array(
            'name' => 'time_period',
            'required' => true,
        ));
        // Features list
        if (!empty($option['features'])) {
            foreach ($option['features'] as $feature) {
        	    $this->add(array(
            	    'name' => $feature['name'],
            	    'required' => false,
            	    'filters' => array(
                	    array(
                    	    'name' => 'StringTrim',
                	    ),
            	    ),
        	    ));
        	    $this->add(array(
            	    'name' => $feature['value'],
            	    'required' => false,
            	    'filters' => array(
                	    array(
                    	    'name' => 'StringTrim',
                	    ),
            	    ),
        	    ));
            }
        }
    }
}    	