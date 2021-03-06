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
 * Pi::api('category', 'guide')->setLink($item, $category, $create, $update, $start, $end, $status, $rating, $hits);
 * Pi::api('category', 'guide')->findFromCategory($category);
 * Pi::api('category', 'guide')->categoryList($parent);
 * Pi::api('category', 'guide')->categoryCount();
 * Pi::api('category', 'guide')->canonizeCategory($category);
 */

class Category extends AbstractApi
{
    /**
     * Set item category to link table
     */
    public function setLink(
        $item, 
        $category, 
        $create, 
        $update, 
        $start, 
        $end, 
        $status,
        $rating,
        $hits
    ) {
        //Remove
        Pi::model('link', $this->getModule())->delete(array('item' => $item));
        // Add
        $allCategory = Json::decode($category);
        foreach ($allCategory as $category) {
            // Set array
            $values['item'] = $item;
            $values['category'] = $category;
            $values['time_create'] = $create;
            $values['time_update'] = $update;
            $values['time_start'] = $start;
            $values['time_end'] = $end;
            $values['status'] = $status;
            $values['rating'] = $rating;
            $values['hits'] = $hits;
            // Save
            $row = Pi::model('link', $this->getModule())->createRow();
            $row->assign($values);
            $row->save();
        }
    }

    public function findFromCategory($category)
    {
        $list = array();
        $where = array('category' => $category);
        $select = Pi::model('link', $this->getModule())->select()->where($where);
        $rowset = Pi::model('link', $this->getModule())->selectWith($select);
        foreach ($rowset as $row) {
            $row = $row->toArray();
            $list[] = $row['item'];
        }
        return array_unique($list);
    }

    public function categoryList($parent = null)
    {
        // Get config
        $config = Pi::service('registry')->config->read($this->getModule());
        $return = array();
        if (is_null($parent)) {
            $where = array('status' => 1);
        } else {
            $where = array('status' => 1, 'parent' => $parent);
        }
        $order = array('time_create DESC', 'id DESC');
        $select = Pi::model('category', $this->getModule())->select()->where($where)->order($order);
        $rowset = Pi::model('category', $this->getModule())->selectWith($select);
        foreach ($rowset as $row) {
            $return[$row->id] = $this->canonizeCategory($row);
        }
        return $return;
    }  

    public function categoryCount()
    {
        $columns = array('count' => new \Zend\Db\Sql\Predicate\Expression('count(*)'));
        $select = Pi::model('category', $this->getModule())->select()->columns($columns);
        $count = Pi::model('category', $this->getModule())->selectWith($select)->current()->count;
        return $count;
    }

    public function canonizeCategory($category)
    {
        // Check
        if (empty($category)) {
            return '';
        }
        // Get config
        $config = Pi::service('registry')->config->read($this->getModule());
        // boject to array
        $category = $category->toArray();
        // Set description text
        $category['description'] = Pi::service('markup')->render($category['description'], 'html', 'html');
        // Set times
        $category['time_create_view'] = _date($category['time_create']);
        $category['time_update_view'] = _date($category['time_update']);
        // Set item url
        $category['categoryUrl'] = Pi::service('url')->assemble('guide', array(
            'module'        => $this->getModule(),
            'controller'    => 'category',
            'slug'          => $category['slug'],
        ));
        // Set image url
        if ($category['image']) {
            // Set image original url
            $category['originalUrl'] = Pi::url(
                sprintf('upload/%s/original/%s/%s', 
                    $config['image_path'], 
                    $category['path'], 
                    $category['image']
                ));
            // Set image large url
            $category['largeUrl'] = Pi::url(
                sprintf('upload/%s/large/%s/%s', 
                    $config['image_path'], 
                    $category['path'], 
                    $category['image']
                ));
            // Set image medium url
            $category['mediumUrl'] = Pi::url(
                sprintf('upload/%s/medium/%s/%s', 
                    $config['image_path'], 
                    $category['path'], 
                    $category['image']
                ));
            // Set image thumb url
            $category['thumbUrl'] = Pi::url(
                sprintf('upload/%s/thumb/%s/%s', 
                    $config['image_path'], 
                    $category['path'], 
                    $category['image']
                ));
        }
        // return item
        return $category; 
    }
}