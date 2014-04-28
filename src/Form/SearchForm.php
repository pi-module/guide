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

class SearchForm  extends BaseForm
{
	public function __construct($name = null, $option = array())
    {
        $this->field = $option['field'];
        $this->location = $option['location'];
        $this->config = Pi::service('registry')->config->read('guide', 'search');
        parent::__construct($name);
    }

    public function getInputFilter()
    {
        if (!$this->filter) {
            $this->filter = new SearchFilter;
        }
        return $this->filter;
    }

    public function init()
    {
        // type
        if ($this->config['search_type']) {
            $this->add(array(
                'name' => 'type',
                'type' => 'select',
                'options' => array(
                    'label' => __('Title search type'),
                    'value_options' => array(
                        1 => __('Included'),
                        2 => __('Start with'),
                        3 => __('End with'),
                        4 => __('According'),
                    ),
                ),
            ));
        } else {
            $this->add(array(
                'name' => 'type',
                'attributes' => array(
                    'type' => 'hidden',
                    'value' => 1,
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
        // category
        if ($this->config['search_category']) {
            $this->add(array(
                'name' => 'category',
                'type' => 'Module\Guide\Form\Element\Category',
                'options' => array(
                    'label' => __('Category'),
                ),
                'attributes' => array(
                    'size' => 1,
                    'multiple' => 0,
                ),
            ));
        } else {
            $this->add(array(
                'name' => 'category',
                'attributes' => array(
                    'type' => 'hidden',
                ),
            ));
        }
        // Set extra location
        if (!empty($this->location)) {
            foreach ($this->location as $location) {
                $this->add(array(
                    'name' => sprintf('location-%s', $location['id']),
                    'type' => 'select',
                    'options' => array(
                        'label' => $location['title'],
                        'value_options' => $this->makeLocationArray($location['value']),
                    ),
                    'attributes' => array(
                        'id' => sprintf('form-location-%s', $location['id']),
                        'class' => 'form-location',
                        'data-category' => $location['id'],
                    )
                ));
            }
        }
        // service
        $this->add(array(
            'name' => 'service',
            'options' => array(
                'label' => __('Service'),
            ),
            'attributes' => array(
                'type' => 'text',
                'description' => '',
            )
        ));
        // Set extra field
        /* if (!empty($this->field)) {
            foreach ($this->field as $field) {
                if ($field['search']) {
                    if ($field['type'] == 'select') {
                        $this->add(array(
                            'name' => $field['id'],
                            'type' => 'select',
                            'options' => array(
                                'label' => $field['title'],
                                'value_options' => $this->makeArray($field['value']),
                            ),
                        ));
                    } else {
                        $this->add(array(
                            'name' => $field['id'],
                            'options' => array(
                                'label' => $field['title'],
                            ),
                            'attributes' => array(
                                'type' => 'text',
                            )
                        ));
                    }
                }
            }
        } */
        // Save
        $this->add(array(
            'name' => 'submit',
            'type' => 'submit',
            'attributes' => array(
                'value' => __('Submit'),
            )
        ));
    }

    public function makeArray($string)
    {
        $list = array();
        $variable = explode('|', $string);
        foreach ($variable as $value) {
            $list[$value] = $value;
        }
        return $list;
    }

    public function makeLocationArray($array)
    {
        $list = array();
        $list[0] = '';
        foreach ($array as $value) {
            $list[$value['id']] = $value['title'];
        }
        return $list;
    }
}