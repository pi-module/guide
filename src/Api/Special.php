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
 * Pi::api('special', 'guide')->getAll();
 */

class Special extends AbstractApi
{
    public function getAll()
    {
        // Get config
        $config = Pi::service('registry')->config->read($this->getModule());
        $special = array();
        // Set options
        $where = array('status' => 1, 'time_publish < ?' => time(), 'time_expire > ?' => time()); 
        $order = array('id DESC', 'time_publish DESC');
        $limit = intval($config['view_special_number']);
        // Get ids
        $model = Pi::model('special', $this->getModule());
        $select = $model->select()->where($where)->order($order)->limit($limit);
        $rowset = $model->selectWith($select);
        foreach ($rowset as $row) {
            $specialList[$row->id] = $row->toArray();
            $specialId[] = $row->item;
        }
        // Get list of items
        if (!empty($specialId)) {
            $specialItem = Pi::api('item', 'guide')->getListFromId($specialId);
            foreach ($specialList as $item) {
                $special[$item['id']] = $item;
                $special[$item['id']]['itemInformation'] = $specialItem[$item['item']];
            }
        }
        return $special;
    }
}	