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
 * Pi::api('location', 'guide')->locationForm($tree);
 * Pi::api('location', 'guide')->locationFormElement($category, $parent);
 * Pi::api('location', 'guide')->locationSearch($form);
 * Pi::api('location', 'guide')->locationAllChild($id, $category);
 */

class Location extends AbstractApi
{
	public function locationForm($tree = array())
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
        // set tree
        if (!empty($tree) && !empty($category)) {
            $location = Pi::model('location', $this->getModule())->find($tree['location']);
            $category[$tree['id']]['value'] = $this->locationFormElement($tree['id'], $location->parent);
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

    public function locationSearch($form)
    {
        $location_id = '';
        $location_category = '';
        $locationForm = $this->locationForm();
        foreach ($locationForm as $location) {
            $element = sprintf('location-%s', $location['id']);
            $element = $form[$element];
            if (isset($element) && !empty($element)) {
                $location_id = $element;
                $location_category = $location['id'];
            }
        }
        if (empty($location_id) || empty($location_category)) {
            return '';
        }
        $where = array('category > ?' => $location_category);
        $select = Pi::model('location', $this->getModule())->select()->where($where);
        $rowset = Pi::model('location', $this->getModule())->selectWith($select);
        foreach ($rowset as $row) {
            $locationList[$row->id]['id'] = $row->id;
            $locationList[$row->id]['parent'] = $row->parent;
        }
        $allChild = $this->getTree($locationList, $location_id);
        $allChild[] = $location_id;
        return $allChild;
    }

    public function getTree($elements, $parentId = 0)
    {
        $list = array();
        // Set list as tree
        foreach ($elements as $element) {
            if ($element['parent'] == $parentId) {
                $depth = 0;
                $list[] = $element['id'];
                $children = $this->getTree($elements, $element['id']);
                if ($children) {
                    $depth++;
                    foreach ($children as $key => $value) {
                        $list[] = $value;
                    }
                }
                unset($elements[$element['id']]);
                unset($depth);
            }
        }
        return $list;
    }
}	