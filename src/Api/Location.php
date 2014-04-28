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
 * Pi::api('location', 'guide')->locationLevel();
 * Pi::api('location', 'guide')->locationForm($location);
 * Pi::api('location', 'guide')->locationFormElement($level, $parent);
 * Pi::api('location', 'guide')->locationSearch($form);
 * Pi::api('location', 'guide')->locationSearchTitle($title);
 */

class Location extends AbstractApi
{
	public function locationLevel()
    {
        // Get config
        $config = Pi::service('registry')->config->read($this->getModule());
        // Set level array
        $level = array(
            1 => $config['location_level_1'],
            2 => $config['location_level_2'],
            3 => $config['location_level_3'],
            4 => $config['location_level_4'],
            5 => $config['location_level_5']
        );
        return $level;
    }

    public function locationForm($location = '')
	{
        $return = array();
        $locationLevel = $this->locationLevel();
        // Make list
        foreach ($locationLevel as $key => $value) {
            $return[$key]['id'] = $key;
            $return[$key]['title'] = $value;
            $return[$key]['child'] = $key + 1;
            if ($key == 1) {
                $return[$key]['value'] = $this->locationFormElement($key, 0);
            } else {
                $return[$key]['value'] = array();
            }
        }
        // set tree
        if (!empty($location) && !empty($return)) {
            $location = Pi::model('location', $this->getModule())->find($location);
            $return[$location->level]['value'] = $this->locationFormElement($location->level, $location->parent);
        }
        return $return;
	}

	public function locationFormElement($level, $parent)
    {
        $location = array();
        $where = array('level' => $level, 'parent' => $parent);
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
        $location_level = '';
        $locationForm = $this->locationForm();
        foreach ($locationForm as $location) {
            $element = sprintf('location-%s', $location['id']);
            $element = $form[$element];
            if (isset($element) && !empty($element)) {
                $location_id = $element;
                $location_level = $location['id'];
            }
        }
        if (empty($location_id) || empty($location_level)) {
            return '';
        }
        $where = array('level > ?' => $location_level);
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

    public function locationSearchTitle($title)
    {
        $allChild = array();
        // Check title
        if (empty($title)) {
            return $allChild;
        }
        // find and check location id
        $locationSearch = array();
        $where = array('title' => $title);
        $select = Pi::model('location', $this->getModule())->select()->where($where);
        $rowset = Pi::model('location', $this->getModule())->selectWith($select);
        foreach ($rowset as $row) {
            $locationSearch[$row->id]['id'] = $row->id;
            $locationSearch[$row->id]['level'] = $row->level;
        }
        if (empty($locationSearch)) {
            return $allChild;
        }
        // Set location child array
        foreach ($locationSearch as $location) {
            $where = array('level > ?' => $location['level']);
            $select = Pi::model('location', $this->getModule())->select()->where($where);
            $rowset = Pi::model('location', $this->getModule())->selectWith($select);
            foreach ($rowset as $row) {
                $locationList[$row->id]['id'] = $row->id;
                $locationList[$row->id]['parent'] = $row->parent;
            }
            $childs = $this->getTree($locationList, $location['id']);
            $childs[] = $location['id'];
            $allChild = array_merge($childs, $allChild);
        }
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