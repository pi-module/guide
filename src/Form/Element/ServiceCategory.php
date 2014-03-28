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

class ServiceCategory extends Select
{
    /**
     * @return array
     */
    public function getValueOptions()
    {
        if (empty($this->valueOptions)) {
            $columns = array('id', 'title');
            $order = array('time_create DESC', 'id DESC');
            $select = Pi::model('service_category', $this->options['module'])->select()->columns($columns)->order($order);
            $rowset = Pi::model('service_category', $this->options['module'])->selectWith($select);
            //$options = array();
        	//$options[] = '';
            foreach ($rowset as $row) {
                $list[$row->id] = $row->toArray();
                $options[$row->id] = $list[$row->id]['title'];
            }
            $this->valueOptions = $options;
        }
        return $this->valueOptions;
    }
}