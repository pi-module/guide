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

class ItemFilter extends InputFilter
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
        // slug
        $this->add(array(
            'name'          => 'slug',
            'required'      => false,
            'filters'       => array(
                array(
                    'name'  => 'StringTrim',
                ),
            ),
            'validators'    => array(
                new \Module\Guide\Validator\SlugDuplicate(array(
                    'module'            => Pi::service('module')->current(),
                    'table'             => 'item',
                )),
            ),
        ));
        // summary
        $this->add(array(
            'name' => 'summary',
            'required' => false,
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
        // category
        $this->add(array(
            'name' => 'category',
            'required' => true,
        ));
        // image
        $this->add(array(
            'name' => 'image',
            'required' => false,
        ));
        // time_start
        $this->add(array(
            'name' => 'time_start',
            'required' => true,
            'filters' => array(
                array(
                    'name' => 'StringTrim',
                ),
            ),
        ));
        // time_end
        $this->add(array(
            'name' => 'time_end',
            'required' => true,
            'filters' => array(
                array(
                    'name' => 'StringTrim',
                ),
            ),
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
        // map_longitude
        $this->add(array(
            'name' => 'map_longitude',
            'required' => false,
            'filters' => array(
                array(
                    'name' => 'StringTrim',
                ),
            ),
        ));
        // map_latitude
        $this->add(array(
            'name' => 'map_latitude',
            'required' => false,
            'filters' => array(
                array(
                    'name' => 'StringTrim',
                ),
            ),
        ));
        // address1
        $this->add(array(
            'name' => 'address1',
            'required' => false,
            'filters' => array(
                array(
                    'name' => 'StringTrim',
                ),
            ),
        ));
        // address2
        $this->add(array(
            'name' => 'address2',
            'required' => false,
            'filters' => array(
                array(
                    'name' => 'StringTrim',
                ),
            ),
        ));
        // area
        $this->add(array(
            'name' => 'area',
            'required' => false,
            'filters' => array(
                array(
                    'name' => 'StringTrim',
                ),
            ),
        ));
        // city
        $this->add(array(
            'name' => 'city',
            'required' => false,
            'filters' => array(
                array(
                    'name' => 'StringTrim',
                ),
            ),
        ));
        // zipcode
        $this->add(array(
            'name' => 'zipcode',
            'required' => false,
            'filters' => array(
                array(
                    'name' => 'StringTrim',
                ),
            ),
        ));
        // phone1
        $this->add(array(
            'name' => 'phone1',
            'required' => false,
            'filters' => array(
                array(
                    'name' => 'StringTrim',
                ),
            ),
        ));
        // phone2
        $this->add(array(
            'name' => 'phone2',
            'required' => false,
            'filters' => array(
                array(
                    'name' => 'StringTrim',
                ),
            ),
        ));
        // mobile
        $this->add(array(
            'name' => 'mobile',
            'required' => false,
            'filters' => array(
                array(
                    'name' => 'StringTrim',
                ),
            ),
        ));
        // website
        $this->add(array(
            'name' => 'website',
            'required' => false,
            'filters' => array(
                array(
                    'name' => 'StringTrim',
                ),
            ),
        ));
        // email
        $this->add(array(
            'name' => 'email',
            'required' => false,
            'filters' => array(
                array(
                    'name' => 'StringTrim',
                ),
            ),
        ));
        // seo_title
        $this->add(array(
            'name' => 'seo_title',
            'required' => false,
            'filters' => array(
                array(
                    'name' => 'StringTrim',
                ),
            ),
        ));
        // seo_keywords
        $this->add(array(
            'name' => 'seo_keywords',
            'required' => false,
            'filters' => array(
                array(
                    'name' => 'StringTrim',
                ),
            ),
        ));
        // seo_description
        $this->add(array(
            'name' => 'seo_description',
            'required' => false,
            'filters' => array(
                array(
                    'name' => 'StringTrim',
                ),
            ),
        ));
        // tag
        if (Pi::service('module')->isActive('tag')) {
            $this->add(array(
                'name' => 'tag',
                'required' => false,
            ));
        }
        // Set extra field
        if (!empty($option['field'])) {
            foreach ($option['field'] as $field) {
                $this->add(array(
                    'name' => $field['id'],
                    'required' => false,
                ));
            }
        }
    }
}    	