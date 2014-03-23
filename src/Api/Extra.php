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

/*
 * Pi::api('extra', 'guide')->Get();
 * Pi::api('extra', 'guide')->Set($extra, $item);
 * Pi::api('extra', 'guide')->Form($values);
 * Pi::api('extra', 'guide')->Item($id);
 * Pi::api('extra', 'guide')->SearchForm($form);
 * Pi::api('extra', 'guide')->findFromExtra($search);
 */

class Extra extends AbstractApi
{
    /*
      * Get list of extra fields for show in forms
      */
    public function Get()
    {
        $return = array(
            'extra' => '',
            'field' => '',
        );
        $whereField = array('status' => 1);
        $orderField = array('order DESC', 'id DESC');
        $select = Pi::model('field', $this->getModule())->select()->where($whereField)->order($orderField);
        $rowset = Pi::model('field', $this->getModule())->selectWith($select);
        foreach ($rowset as $row) {
            $return['extra'][$row->id] = $row->toArray();
            $return['field'][$row->id] = $return['extra'][$row->id]['id'];
        }
        return $return;
    }

    /*
      * Save extra field datas to DB
      */
    public function Set($extra, $item)
    {
        foreach ($extra as $field) {
            // Find row
            $where = array('field' => $field['field'], 'item' => $item);
            $select = Pi::model('field_data', $this->getModule())->select()->where($where)->limit(1);
            $row = Pi::model('field_data', $this->getModule())->selectWith($select)->current();
            // create new row
            if (empty($row)) {
                $row = Pi::model('field_data', $this->getModule())->createRow();
                $row->field = $field['field'];
                $row->item = $item;
            }
            // Save or delete row
            if (empty($field['data'])) {
                $row->delete();
            } else {
                $row->data = $field['data'];
                $row->save();
            }
        }
        // Set item Extra Count
        Pi::api('item', 'guide')->ExtraCount($item);
    }

    /*
      * Get and Set extra field data valuse to form
      */
    public function Form($values)
    {
        $where = array('item' => $values['id']);
        $select = Pi::model('field_data', $this->getModule())->select()->where($where);
        $rowset = Pi::model('field_data', $this->getModule())->selectWith($select);
        foreach ($rowset as $row) {
            $field[$row->field] = $row->toArray();
            $values[$field[$row->field]['field']] = $field[$row->field]['data'];
        }
        return $values;
    }

    /*
      * Get all extra field data for selected item
      */
    public function Item($id)
    {
        // Get data list
        $whereData = array('item' => $id);
        $columnData = array('field', 'data');
        $select = Pi::model('field_data', $this->getModule())->select()->where($whereData)->columns($columnData);
        $rowset = Pi::model('field_data', $this->getModule())->selectWith($select);
        foreach ($rowset as $row) {
            $data[$row->field] = $row->toArray();
        }
        // Get field list
        $field = array();
        if (!empty($data)) {
            $whereField = array('status' => 1);
            $columnField = array('id', 'title', 'image', 'type');
            $orderField = array('order ASC', 'id ASC');
            $select = Pi::model('field', $this->getModule())->select()->where($whereField)->columns($columnField)->order($orderField);
            $rowset = Pi::model('field', $this->getModule())->selectWith($select);
            foreach ($rowset as $row) {
                switch ($row->type) {
                    case 'audio':
                        $field['audio'][$row->id] = $row->toArray();
                        $field['audio'][$row->id]['data'] = isset($data[$row->id]['data']) ? $data[$row->id]['data'] : '';
                        if ($field['audio'][$row->id]['image']) {
                            $field['audio'][$row->id]['imageUrl'] = Pi::url('upload/' . $this->getModule() . '/extra/' . $field['audio'][$row->id]['image']);
                        }
                        break;

                    case 'video':
                        $field['video'][$row->id] = $row->toArray();
                        $field['video'][$row->id]['data'] = isset($data[$row->id]['data']) ? $data[$row->id]['data'] : '';
                        if ($field['video'][$row->id]['image']) {
                            $field['video'][$row->id]['imageUrl'] = Pi::url('upload/' . $this->getModule() . '/extra/' . $field['video'][$row->id]['image']);
                        }
                        break;
                    
                    default:
                        $field['all'][$row->id] = $row->toArray();
                        $field['all'][$row->id]['data'] = isset($data[$row->id]['data']) ? $data[$row->id]['data'] : '';
                        if ($field['all'][$row->id]['image']) {
                            $field['all'][$row->id]['imageUrl'] = Pi::url('upload/' . $this->getModule() . '/extra/' . $field['all'][$row->id]['image']);
                        }
                        break;
                }             
            }
        }
        // return
        return $field;
    }

    /*
      * Set extra filds from search form
      */
    public function SearchForm($form)
    {
        $extra = array();
        // unset other field
        unset($form['type']);
        unset($form['title']);
        unset($form['category']);
        // Make list
        foreach ($form as $key => $value) {
            if (is_numeric($key) && !empty($value)) {
                $item = array();
                $item['field'] = $key;
                $item['data'] = $value;
                $extra[$key] = $item;
            }
        }
        return $extra;
    }

    /*
      * Set extra filds from search form
      */
    public function findFromExtra($search)
    {
        $id = array();
        $column = array('item');
        foreach ($search as $extra) {
            $where = array(
                'field' => $extra['field'], 
                'data' => $extra['data'],
            );
            $select = Pi::model('field_data', $this->getModule())->select()->where($where)->columns($column);
            $rowset = Pi::model('field_data', $this->getModule())->selectWith($select);
            foreach ($rowset as $row) {
                if (isset($row->item) && !empty($row->item)) {
                    $id[] = $row->item;
                }
            }
        }
        $id = array_unique($id);
        return $id;
    }
}