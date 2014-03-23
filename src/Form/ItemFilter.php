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
    public function __construct($extra = null)
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
        if (!empty($extra)) {
            foreach ($extra as $field) {
                $this->add(array(
                    'name' => $field['id'],
                    'required' => false,
                ));
            }
        }
    }
}    	