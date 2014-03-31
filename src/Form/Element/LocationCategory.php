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
namespace Module\Guide\Form\Element;

use Pi;
use Zend\Form\Element\Select;

class LocationCategory extends Select
{
    /**
     * @return array
     */
    public function getValueOptions()
    {
        if (empty($this->valueOptions)) {
            $columns = array('id', 'title');
            $order = array('id DESC');
            $select = Pi::model('location_category', $this->options['module'])->select()->columns($columns)->order($order)->limit(1);
            $rowset = Pi::model('location_category', $this->options['module'])->selectWith($select);
            $options = array();
        	// Make list
            foreach ($rowset as $row) {
                $list[$row->id] = $row->toArray();
                $options[$row->id] = $list[$row->id]['title'];
            }
            // Check if empty options
            if (empty($options)) {
                $options[0] = __('Top category');
            }
            $this->valueOptions = $options;
        }
        return $this->valueOptions;
    }
}