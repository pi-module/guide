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

class SearchFilter extends InputFilter
{
    public function __construct($option = array())
    {
        // type
        $this->add(array(
            'name' => 'type',
            'required' => false,
        ));
        // title
        $this->add(array(
            'name' => 'title',
            'required' => false,
            'filters' => array(
                array(
                    'name' => 'StringTrim',
                ),
            ),
        ));
        // category
        $this->add(array(
            'name' => 'category',
            'required' => false,
        ));
        // Set extra location
        if (!empty($option['location'])) {
            foreach ($option['location'] as $location) {
                $this->add(array(
                    'name' => sprintf('location-%s', $location['id']),
                    'required' => false,
                ));
            }
        }
        // Set extra field
        if (!empty($option['field'])) {
            foreach ($option['field'] as $field) {
                if ($field['search']) {
                    $this->add(array(
                        'name' => $field['id'],
                        'required' => false,
                    ));
                }
            }
        }
    }
}    	