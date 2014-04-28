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
 * Pi::api('service', 'guide')->getService($item);
 * Pi::api('service', 'guide')->getServiceCategory();
 * Pi::api('service', 'guide')->searchService($title);
 */

class Service extends AbstractApi
{
	public function getService($item)
	{
        $list = array();
		$categories = $this->getServiceCategory();
		// select
		$where = array('item' => $item, 'status' => 1);
        $order = array('id DESC', 'time_create DESC');
        $select = Pi::model('service', $this->getModule())->select()->where($where)->order($order);
        $rowset = Pi::model('service', $this->getModule())->selectWith($select)->toArray();
        // Make list
        foreach ($categories as $category) {
        	$service = array();
        	$service['title'] = $category['title'];
        	$service['parameter'] = array();
        	foreach ($rowset as $row) {
        		if ($row['category'] == $category['id']) {
        			$service['parameter'][$row['id']] = $row;
                    $service['parameter'][$row['id']]['price_view'] = $this->viewPrice($row['price']);
                    $service['parameter'][$row['id']]['categoryTitle'] = $category['title'];
        		}
            }
        	$list[$category['id']] = $service;
        }
		return $list;
	}

	public function getServiceCategory()
	{
		$list = array();
		$where = array('status' => 1);
        $order = array('id DESC', 'time_create DESC');
        $select = Pi::model('service_category', $this->getModule())->select()->where($where)->order($order);
        $rowset = Pi::model('service_category', $this->getModule())->selectWith($select);
        foreach ($rowset as $row) {
            $list[$row->id] = $row->toArray();
        }
        return $list;
	}

    public function viewPrice($price)
    {
        if ($price > 0) {
            $viewPrice = _currency($price);
        } else {
            $viewPrice = '';
        }
        return $viewPrice;
    }

    public function searchService($title)
    {
        $list = array();
        $where = array('title' => $title, 'status' => 1);
        $select = Pi::model('service', $this->getModule())->select()->where($where);
        $rowset = Pi::model('service', $this->getModule())->selectWith($select);
        foreach ($rowset as $row) {
            $list[] = $row->item;
        }
        $list = array_unique($list);
        return $list;
    }
}