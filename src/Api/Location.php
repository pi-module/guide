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
namespace Module\Guide\Api;

use Pi;
use Pi\Application\Api\AbstractApi;
use Zend\Json\Json;

/*
 * Pi::api('location', 'guide')->locationForm();
 * Pi::api('location', 'guide')->locationFormElement($category, $parent);
 */

class Location extends AbstractApi
{
	public function locationForm()
	{
        // Set category
        $category = array();
        // Get category info
        $select = Pi::model('location_category', $this->getModule())->select();
        $rowset = Pi::model('location_category', $this->getModule())->selectWith($select);
        // Make list
        foreach ($rowset as $row) {
            $category[$row->id] = $row->toArray();
            if ($row->parent == 0) {
                $category[$row->id]['value'] = $this->locationFormElement($row->id, $row->parent);
            } else {
                $category[$row->id]['value'] = array();
            }
        }
        return $category;
	}

	public function locationFormElement($category, $parent)
    {
        $location = array();
        $where = array('category' => $category, 'parent' => $parent);
        $select = Pi::model('location', $this->getModule())->select()->where($where);
        $rowset = Pi::model('location', $this->getModule())->selectWith($select);
        foreach ($rowset as $row) {
            $location[$row->id]['id'] = $row->id;
            $location[$row->id]['title'] = $row->title;
        }
        return $location;
    }

}	