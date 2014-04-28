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
        // Get config
        $config = Pi::service('registry')->config->read($this->getModule());
		// select
		$where = array('item' => $item, 'status' => 1);
        $order = array('id DESC', 'time_create DESC');
        $select = Pi::model('service', $this->getModule())->select()->where($where)->order($order);
        $rowset = Pi::model('service', $this->getModule())->selectWith($select)->toArray();
        // Make list
        foreach ($categories as $category) {
        	$service = array();
            $service = array_merge($service, $category);
        	$service['parameter'] = array();
        	foreach ($rowset as $row) {
        		if ($row['category'] == $category['id']) {
        			$service['parameter'][$row['id']] = $row;
                    $service['parameter'][$row['id']]['price_view'] = $this->viewPrice($row['price']);
                    $service['parameter'][$row['id']]['categoryTitle'] = $category['title'];
                    // Set image url
                    if ($row['image']) {
                        // Set image original url
                        $service['parameter'][$row['id']]['originalUrl'] = Pi::url(
                            sprintf('upload/%s/original/%s/%s', 
                                $config['image_path'], 
                                $row['path'], 
                                $row['image']
                            ));
                        // Set image large url
                        $service['parameter'][$row['id']]['largeUrl'] = Pi::url(
                            sprintf('upload/%s/large/%s/%s', 
                                $config['image_path'], 
                                $row['path'], 
                                $row['image']
                            ));
                        // Set image medium url
                        $service['parameter'][$row['id']]['mediumUrl'] = Pi::url(
                            sprintf('upload/%s/medium/%s/%s', 
                                $config['image_path'], 
                                $row['path'], 
                                $row['image']
                            ));
                        // Set image thumb url
                        $service['parameter'][$row['id']]['thumbUrl'] = Pi::url(
                            sprintf('upload/%s/thumb/%s/%s', 
                                $config['image_path'], 
                                $row['path'], 
                                $row['image']
                            ));
                    }
        		}
            }
        	$list[$category['id']] = $service;
        }
		return $list;
	}

	public function getServiceCategory()
	{
		// Get config
        $config = Pi::service('registry')->config->read($this->getModule());
        // Make list
        $list = array();
		$where = array('status' => 1);
        $order = array('id DESC', 'time_create DESC');
        $select = Pi::model('service_category', $this->getModule())->select()->where($where)->order($order);
        $rowset = Pi::model('service_category', $this->getModule())->selectWith($select);
        foreach ($rowset as $row) {
            $list[$row->id] = $row->toArray();
            // Set image url
            if ($list[$row->id]['image']) {
                // Set image original url
                $list[$row->id]['originalUrl'] = Pi::url(
                    sprintf('upload/%s/original/%s/%s', 
                        $config['image_path'], 
                        $list[$row->id]['path'], 
                        $list[$row->id]['image']
                    ));
                // Set image large url
                $list[$row->id]['largeUrl'] = Pi::url(
                    sprintf('upload/%s/large/%s/%s', 
                        $config['image_path'], 
                        $list[$row->id]['path'], 
                        $list[$row->id]['image']
                    ));
                // Set image medium url
                $list[$row->id]['mediumUrl'] = Pi::url(
                    sprintf('upload/%s/medium/%s/%s', 
                        $config['image_path'], 
                        $list[$row->id]['path'], 
                        $list[$row->id]['image']
                    ));
                // Set image thumb url
                $list[$row->id]['thumbUrl'] = Pi::url(
                    sprintf('upload/%s/thumb/%s/%s', 
                        $config['image_path'], 
                        $list[$row->id]['path'], 
                        $list[$row->id]['image']
                    ));
            }
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