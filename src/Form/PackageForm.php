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

class PackageForm  extends BaseForm
{
    public function __construct($name = null, $option = array())
    {
        $this->thumbUrl = (isset($option['thumbUrl'])) ? $option['thumbUrl'] : '';
        $this->removeUrl = (isset($option['removeUrl'])) ? $option['removeUrl'] : '';
        $this->features = $option['features'];
        parent::__construct($name);
    }

    public function getInputFilter()
    {
        if (!$this->filter) {
            $this->filter = new PackageFilter;
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
        // description
        $this->add(array(
            'name' => 'description',
            'options' => array(
                'label' => __('Description'),
            ),
            'attributes' => array(
                'type' => 'textarea',
                'rows' => '5',
                'cols' => '40',
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
        // extra
        $this->add(array(
            'name' => 'extra_price',
            'type' => 'fieldset',
            'options' => array(
                'label' => __('Price options'),
            ),
        ));
        // price
        $this->add(array(
            'name' => 'price',
            'options' => array(
                'label' => __('Price'),
            ),
            'attributes' => array(
                'type' => 'text',
                'description' => '',  
            )
        ));
        // stock_all
        $this->add(array(
            'name' => 'stock_all',
            'options' => array(
                'label' => __('Stock'),
            ),
            'attributes' => array(
                'type' => 'text',
                'description' => '',
            )
        ));
        // time_period
        $this->add(array(
            'name' => 'time_period',
            'type' => 'select',
            'options' => array(
                'label' => __('Period'),
                'value_options' => array(
                    1 => __('One Month'),
                    3 => __('Three months'),
                    6 => __('Six Months'),
                    12 => __('Annual'),
                ),
            ),
            'attributes' => array(
                'value' => 12,
            )
        ));
        // extra
        $this->add(array(
            'name' => 'extra_features',
            'type' => 'fieldset',
            'options' => array(
                'label' => __('Features options'),
            ),
            
        ));
        // Make features list
        if (!empty($this->features)) {
            foreach ($this->features as $feature) {
                $this->add(array(
                    'name' => $feature['name'],
                    'options' => array(
                        'label' => $feature['name_title'],
                    ),
                    'attributes' => array(
                        'type' => 'text',
                        'description' => __('This name used as feature title'),
                    )
                ));
                $this->add(array(
                    'name' => $feature['value'],
                    'options' => array(
                        'label' => $feature['value_title'],
                    ),
                    'attributes' => array(
                        'type' => 'text',
                        'description' => __('Use 0 or 1 for yes/no view or type value'),
                    )
                ));
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
}