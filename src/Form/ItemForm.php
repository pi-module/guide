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

class ItemForm  extends BaseForm
{
    public function __construct($name = null, $option = array())
    {
        $this->field = $option['field'];
        $this->location = $option['location'];
        $this->thumbUrl = (isset($option['thumbUrl'])) ? $option['thumbUrl'] : '';
        $this->removeUrl = (isset($option['removeUrl'])) ? $option['removeUrl'] : '';
        parent::__construct($name);
    }

    public function getInputFilter()
    {
        if (!$this->filter) {
            $this->filter = new ItemFilter;
        }
        return $this->filter;
    }

    public function init()
    {
        // extra_general
        $this->add(array(
            'name' => 'extra_general',
            'type' => 'fieldset',
            'options' => array(
                'label' => __('General options'),
            ),
        ));
        // id
        $this->add(array(
            'name' => 'id',
            'attributes' => array(
                'type' => 'hidden',
            ),
        ));
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
        // slug
        $this->add(array(
            'name' => 'slug',
            'options' => array(
                'label' => __('slug'),
            ),
            'attributes' => array(
                'type' => 'text',
                'description' => '',
                
            )
        ));
        // type
        $this->add(array(
            'name' => 'type',
            'options' => array(
                'label' => __('Type activity'),
            ),
            'attributes' => array(
                'type' => 'text',
                'description' => '',
            )
        ));
        // summary
        $this->add(array(
            'name' => 'summary',
            'options' => array(
                'label' => __('Summary'),
            ),
            'attributes' => array(
                'type' => 'textarea',
                'rows' => '5',
                'cols' => '40',
                
                'description' => '',
            )
        ));
        // description
        $this->add(array(
            'name' => 'description',
            'options' => array(
                'label' => __('Description'),
                'editor' => 'html',
            ),
            'attributes' => array(
                'type' => 'editor',
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
        // category
        $this->add(array(
            'name' => 'category',
            'type' => 'Module\Guide\Form\Element\Category',
            'options' => array(
                'label' => __('Category'),
                'category' => '',
            ),
        ));
        // Image
        if ($this->thumbUrl) {
            $this->add(array(
                'name' => 'imageview',
                'type' => 'Module\Guide\Form\Element\Image',
                'options' => array(
                    //'label' => __('Image'),
                ),
                'attributes' => array(
                    'src' => $this->thumbUrl,
                ),
            ));
            $this->add(array(
                'name' => 'remove',
                'type' => 'Module\Guide\Form\Element\Remove',
                'options' => array(
                    'label' => __('Remove image'),
                ),
                'attributes' => array(
                    'link' => $this->removeUrl,
                ),
            ));
            $this->add(array(
                'name' => 'image',
                'attributes' => array(
                    'type' => 'hidden',
                ),
            ));
        } else {
            $this->add(array(
                'name' => 'image',
                'options' => array(
                    'label' => __('Image'),
                ),
                'attributes' => array(
                    'type' => 'file',
                    'description' => '',
                )
            ));
        }
        // extra_time
        $this->add(array(
            'name' => 'extra_time',
            'type' => 'fieldset',
            'options' => array(
                'label' => __('Time options'),
            ),
        ));
        // time_start
        $this->add(array(
            'name' => 'time_start',
            'options' => array(
                'label' => __('Time start'),
            ),
            'attributes' => array(
                'type' => 'text',
                'value' => date('Y-m-d'),
                'description' => sprintf(__('Set like %s'), date('Y-m-d')),
            )
        ));
        // time_end
        $this->add(array(
            'name' => 'time_end',
            'options' => array(
                'label' => __('Time end'),
            ),
            'attributes' => array(
                'type' => 'text',
                'value' => date('Y-m-d', strtotime('+1 year')),
                'description' => sprintf(__('Set like %s'), date('Y-m-d', strtotime('+1 year'))),
            )
        ));
        // extra_location
        $this->add(array(
            'name' => 'extra_location',
            'type' => 'fieldset',
            'options' => array(
                'label' => __('Location options'),
            ),
        ));
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
        // map_longitude
        $this->add(array(
            'name' => 'map_longitude',
            'options' => array(
                'label' => __('Longitude'),
            ),
            'attributes' => array(
                'type' => 'text',
                'description' => '',
            )
        ));
        // map_latitude
        $this->add(array(
            'name' => 'map_latitude',
            'options' => array(
                'label' => __('Latitude'),
            ),
            'attributes' => array(
                'type' => 'text',
                'description' => '',
            )
        ));
        // address1
        $this->add(array(
            'name' => 'address1',
            'options' => array(
                'label' => __('Address 1'),
            ),
            'attributes' => array(
                'type' => 'text',
                'description' => '',
            )
        ));
        // address2
        $this->add(array(
            'name' => 'address2',
            'options' => array(
                'label' => __('Address 2'),
            ),
            'attributes' => array(
                'type' => 'text',
                'description' => '',
            )
        ));
        // area
        $this->add(array(
            'name' => 'area',
            'options' => array(
                'label' => __('Area'),
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
        // zipcode
        $this->add(array(
            'name' => 'zipcode',
            'options' => array(
                'label' => __('Zip code'),
            ),
            'attributes' => array(
                'type' => 'text',
                'description' => '',
            )
        ));
        // phone1
        $this->add(array(
            'name' => 'phone1',
            'options' => array(
                'label' => __('Phone 1'),
            ),
            'attributes' => array(
                'type' => 'text',
                'description' => '',
            )
        ));
        // phone2
        $this->add(array(
            'name' => 'phone2',
            'options' => array(
                'label' => __('Phone 2'),
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
        // website
        $this->add(array(
            'name' => 'website',
            'options' => array(
                'label' => __('Website'),
            ),
            'attributes' => array(
                'type' => 'text',
                'description' => '',
            )
        ));
        // email
        $this->add(array(
            'name' => 'email',
            'options' => array(
                'label' => __('Email'),
            ),
            'attributes' => array(
                'type' => 'text',
                'description' => '',
            )
        ));
        // extra_seo
        $this->add(array(
            'name' => 'extra_seo',
            'type' => 'fieldset',
            'options' => array(
                'label' => __('SEO options'),
            ),
        ));
        // seo_title
        $this->add(array(
            'name' => 'seo_title',
            'options' => array(
                'label' => __('SEO Title'),
            ),
            'attributes' => array(
                'type' => 'text',
                'description' => '',
            )
        ));
        // seo_keywords
        $this->add(array(
            'name' => 'seo_keywords',
            'options' => array(
                'label' => __('SEO Keywords'),
            ),
            'attributes' => array(
                'type' => 'text',
                'description' => '',     
            )
        ));
        // seo_description
        $this->add(array(
            'name' => 'seo_description',
            'options' => array(
                'label' => __('SEO Description'),
            ),
            'attributes' => array(
                'type' => 'text',
                'description' => '',     
            )
        ));
        // tag
        if (Pi::service('module')->isActive('tag')) {
            $this->add(array(
                'name' => 'tag',
                'type' => 'tag',
                'options' => array(
                    'label' => __('Tags'),
                ),
                'attributes' => array(
                    'id'          => 'tag',
                    'description' => __('Use `|` as delimiter to separate tag terms'),
                )
            ));
        }
        // Set extra field
        if (!empty($this->field)) {
            // extra_field
            $this->add(array(
                'name' => 'extra_field',
                'type' => 'fieldset',
                'options' => array(
                    'label' => __('Extra fields'),
                ),
            ));
            foreach ($this->field as $field) {
                if ($field['type'] == 'select') {
                    $this->add(array(
                        'name' => $field['id'],
                        'type' => 'select',
                        'options' => array(
                            'label' => sprintf('%s (%s)', $field['title'], $field['type']),
                            'value_options' => $this->makeArray($field['value']),
                        ),
                    ));
                } else {
                    $this->add(array(
                        'name' => $field['id'],
                        'options' => array(
                            'label' => sprintf('%s (%s)', $field['title'], $field['type']),
                        ),
                        'attributes' => array(
                            'type' => 'text',
                        )
                    ));
                }
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