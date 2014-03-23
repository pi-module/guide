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
 * Pi::api('item', 'guide')->getItem($parameter, $type);
 * Pi::api('item', 'guide')->getItemLight($parameter, $type);
 * Pi::api('item', 'guide')->getListFromId($id);
 * Pi::api('item', 'guide')->getListFromIdLight($id);
 * Pi::api('item', 'guide')->extraCount($id);
 * Pi::api('item', 'guide')->attachCount($id);
 * Pi::api('item', 'guide')->reviewCount($id);
 * Pi::api('item', 'guide')->AttachList($id);
 * Pi::api('item', 'guide')->canonizeItem($item, $categoryList);
 * Pi::api('item', 'guide')->canonizeItemLight($item);
 */

class Item extends AbstractApi
{
    public function getItem($parameter, $type = 'id')
    {
        // Get category list
        $categoryList = Pi::api('category', 'guide')->categoryList();
        // Get item
        $item = Pi::model('item', $this->getModule())->find($parameter, $type);
        $item = $this->canonizeitem($item, $categoryList);
        return $item;
    }

    public function getItemLight($parameter, $type = 'id')
    {
        // Get item
        $item = Pi::model('item', $this->getModule())->find($parameter, $type);
        $item = $this->canonizeitemLight($item);
        return $item;
    }

    public function getListFromId($id)
    {
        $list = array();
        $where = array('id' => $id, 'status' => 1);
        $select = Pi::model('item', $this->getModule())->select()->where($where);
        $rowset = Pi::model('item', $this->getModule())->selectWith($select);
        foreach ($rowset as $row) {
            $list[$row->id] = $this->canonizeitem($row);
        }
        return $list;
    }

    public function getListFromIdLight($id)
    {
        $list = array();
        $where = array('id' => $id, 'status' => 1);
        $select = Pi::model('item', $this->getModule())->select()->where($where);
        $rowset = Pi::model('item', $this->getModule())->selectWith($select);
        foreach ($rowset as $row) {
            $list[$row->id] = $this->canonizeitemLight($row);
        }
        return $list;
    }	

    /**
     * Set number of used extra fields for selected item
     */
    public function extraCount($id)
    {
        // Get attach count
        $columns = array('count' => new \Zend\Db\Sql\Predicate\Expression('count(*)'));
        $select = Pi::model('field_data', $this->getModule())->select()->columns($columns);
        $count = Pi::model('field_data', $this->getModule())->selectWith($select)->current()->count;
        // Set attach count
        Pi::model('item', $this->getModule())->update(array('extra' => $count), array('id' => $id));
    }

    /**
     * Set number of attach files for selected item
     */
    public function attachCount($id)
    {
        // Get attach count
        $where = array('item' => $id);
        $columns = array('count' => new \Zend\Db\Sql\Predicate\Expression('count(*)'));
        $select = Pi::model('attach', $this->getModule())->select()->columns($columns)->where($where);
        $count = Pi::model('attach', $this->getModule())->selectWith($select)->current()->count;
        // Set attach count
        Pi::model('item', $this->getModule())->update(array('attach' => $count), array('id' => $id));
    }

    /**
     * Set number of reviews for selected item
     */
    public function reviewCount($id)
    {
        // Get attach count
        $where = array('item' => $id, 'status' => 1);
        $columns = array('count' => new \Zend\Db\Sql\Predicate\Expression('count(*)'));
        $select = Pi::model('review', $this->getModule())->select()->columns($columns)->where($where);
        $count = Pi::model('review', $this->getModule())->selectWith($select)->current()->count;
        // Set attach count
        Pi::model('item', $this->getModule())->update(array('review' => $count), array('id' => $id));
    }

    /**
     * Get list of attach files
     */
    public function AttachList($id)
    {
        // Get config
        $config = Pi::service('registry')->config->read($this->getModule());
        // Set info
        $file = array();
        $where = array('item' => $id, 'status' => 1);
        $order = array('time_create DESC', 'id DESC');
        // Get all attach files
        $select = Pi::model('attach', $this->getModule())->select()->where($where)->order($order);
        $rowset = Pi::model('attach', $this->getModule())->selectWith($select);
        // Make list
        foreach ($rowset as $row) {
            $file[$row->type][$row->id] = $row->toArray();
            $file[$row->type][$row->id]['time_create_view'] = _date($file[$row->type][$row->id]['time_create']);
            if ($file[$row->type][$row->id]['type'] == 'image') {
                // Set image original url
                $file[$row->type][$row->id]['originalUrl'] = Pi::url(
                    sprintf('upload/%s/original/%s/%s', 
                        $config['image_path'], 
                        $file[$row->type][$row->id]['path'], 
                        $file[$row->type][$row->id]['file']
                    ));
                // Set image large url
                $file[$row->type][$row->id]['largeUrl'] = Pi::url(
                    sprintf('upload/%s/large/%s/%s', 
                        $config['image_path'], 
                        $file[$row->type][$row->id]['path'], 
                        $file[$row->type][$row->id]['file']
                    ));
                // Set image medium url
                $file[$row->type][$row->id]['mediumUrl'] = Pi::url(
                    sprintf('upload/%s/medium/%s/%s', 
                        $config['image_path'], 
                        $file[$row->type][$row->id]['path'], 
                        $file[$row->type][$row->id]['file']
                    ));
                // Set image thumb url
                $file[$row->type][$row->id]['thumbUrl'] = Pi::url(
                    sprintf('upload/%s/thumb/%s/%s', 
                        $config['image_path'], 
                        $file[$row->type][$row->id]['path'], 
                        $file[$row->type][$row->id]['file']
                    ));
            } else {
                $file[$row->type][$row->id]['fileUrl'] = Pi::url(
                    sprintf('upload/%s/%s/%s/%s', 
                        $config['file_path'], 
                        $file[$row->type][$row->id]['type'], 
                        $file[$row->type][$row->id]['path'], 
                        $file[$row->type][$row->id]['file']
                    ));
            }
        }
        // return
        return $file;
    }

    public function canonizeItem($item, $categoryList = array())
    {
        // Check
        if (empty($item)) {
            return '';
        }
        // Get config
        $config = Pi::service('registry')->config->read($this->getModule());
        // Get category list
        $categoryList = (empty($categoryList)) ? Pi::api('category', 'guide')->categoryList() : $categoryList;
        // boject to array
        $item = $item->toArray();
        // Set summary text
        $item['summary'] = Pi::service('markup')->render($item['summary'], 'text', 'html');
        // Set description text
        $item['description'] = Pi::service('markup')->render($item['description'], 'html', 'html');
        // Set times
        $item['time_create_view'] = _date($item['time_create']);
        $item['time_update_view'] = _date($item['time_update']);
        // Set item url
        $item['itemUrl'] = Pi::service('url')->assemble('guide', array(
            'module'        => $this->getModule(),
            'controller'    => 'item',
            'slug'          => $item['slug'],
        ));
        // Set category information
        $item['category'] = Json::decode($item['category']);
        foreach ($item['category'] as $category) {
            $item['categories'][$category]['title'] = $categoryList[$category]['title'];
            $item['categories'][$category]['url'] = Pi::service('url')->assemble('guide', array(
                'module'        => $this->getModule(),
                'controller'    => 'category',
                'slug'          => $categoryList[$category]['slug'],
            ));
        }
        // Set image url
        if ($item['image']) {
            // Set image original url
            $item['originalUrl'] = Pi::url(
                sprintf('upload/%s/original/%s/%s', 
                    $config['image_path'], 
                    $item['path'], 
                    $item['image']
                ));
            // Set image large url
            $item['largeUrl'] = Pi::url(
                sprintf('upload/%s/large/%s/%s', 
                    $config['image_path'], 
                    $item['path'], 
                    $item['image']
                ));
            // Set image medium url
            $item['mediumUrl'] = Pi::url(
                sprintf('upload/%s/medium/%s/%s', 
                    $config['image_path'], 
                    $item['path'], 
                    $item['image']
                ));
            // Set image thumb url
            $item['thumbUrl'] = Pi::url(
                sprintf('upload/%s/thumb/%s/%s', 
                    $config['image_path'], 
                    $item['path'], 
                    $item['image']
                ));
        }
        // return item
        return $item; 
    }

    public function canonizeItemLight($item)
    {
        // Check
        if (empty($item)) {
            return '';
        }
        // Get config
        $config = Pi::service('registry')->config->read($this->getModule());
        // boject to array
        $item = $item->toArray();
        // Set times
        $item['time_create_view'] = _date($item['time_create']);
        $item['time_update_view'] = _date($item['time_update']);
        // Set item url
        $item['itemUrl'] = Pi::service('url')->assemble('guide', array(
            'module'        => $this->getModule(),
            'controller'    => 'item',
            'slug'          => $item['slug'],
        ));
        // Set image url
        if ($item['image']) {
            // Set image thumb url
            $item['thumbUrl'] = Pi::url(
                sprintf('upload/%s/thumb/%s/%s', 
                    $config['image_path'], 
                    $item['path'], 
                    $item['image']
                ));
        }
        // unset
        /* unset($item['category']);
        unset($item['summary']);
        unset($item['description']);
        unset($item['seo_title']);
        unset($item['seo_keywords']);
        unset($item['seo_description']);
        unset($item['comment']);
        unset($item['point']);
        unset($item['count']);
        unset($item['favorite']);
        unset($item['attach']);
        unset($item['extra']);
        unset($item['related']);
        unset($item['review']);
        unset($item['recommended']);
        unset($item['stock']);
        unset($item['stock_alert']);
        unset($item['price_discount']);
        unset($item['price_discount_view']);
        unset($item['uid']);
        unset($item['hits']);
        unset($item['sales']); */
        // return item
        return $item; 
    }
}	