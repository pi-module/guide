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
namespace Module\Guide\Block;

use Pi;
use Module\Guide\Form\SearchForm;
use Module\Guide\Form\SearchFilter;

class Block
{
    public static function itemNew($options = array(), $module = null)
    {
        // Set options
        $block = array();
        $block = array_merge($block, $options);
        // Set info
        $where = array('status' => 1);
        if ($block['recommended']) {
            $where['recommended'] = 1;
        }
        $order = array('time_create DESC', 'id DESC');
        $limit = intval($block['number']);
        // Get category list
        $categoryList = Pi::api('category', 'guide')->categoryList();
        // Get list of item
        $select = Pi::model('item', $module)->select()->where($where)->order($order)->limit($limit);
        $rowset = Pi::model('item', $module)->selectWith($select);
        // Make list
        foreach ($rowset as $row) {
            $item[$row->id] = Pi::api('item', 'guide')->canonizeItem($row, $categoryList);
        }
        // Set block array
        $block['resources'] = $item;
        return $block;
    }

    public static function itemRandom($options = array(), $module = null)
    {
        // Set options
        $block = array();
        $block = array_merge($block, $options);
        // Set info
        $where = array('status' => 1);
        if ($block['recommended']) {
            $where['recommended'] = 1;
        }
        $order = array(new \Zend\Db\Sql\Predicate\Expression('RAND()'));
        $limit = intval($block['number']);
        // Get category list
        $categoryList = Pi::api('category', 'guide')->categoryList();
        // Get list of item
        $select = Pi::model('item', $module)->select()->where($where)->order($order)->limit($limit);
        $rowset = Pi::model('item', $module)->selectWith($select);
        // Make list
        foreach ($rowset as $row) {
            $item[$row->id] = Pi::api('item', 'guide')->canonizeItem($row, $categoryList);
        }
        // Set block array
        $block['resources'] = $item;
        return $block;
    }

    public static function category($options = array(), $module = null)
    {
        // Set options
        $block = array();
        $block = array_merge($block, $options);
        // Set info
        $columns = array('id', 'parent', 'title', 'slug');
        $where = array('status' => 1);
        $select = Pi::model('category', $module)->select()->columns($columns)->where($where);
        $rowset = Pi::model('category', $module)->selectWith($select);
        foreach ($rowset as $row) {
            $category[$row->id] = $row->toArray();
            $category[$row->id]['url'] = Pi::url(Pi::service('url')->assemble('guide', array(
                'module'        => $module,
                'controller'    => 'category',
                'slug'          => $category[$row->id]['slug'],
            )));
        }
        // Set block array
        $block['resources'] = $category;
        if ($block['type'] == 'advanced') {
            $block['tree'] = self::getTree($category);
            $block['treeHtml'] = self::getTreeHtml($block['tree']);
        }
        return $block;
    }

    public static function search($options = array(), $module = null)
    {
        // Set options
        $block = array();
        $block = array_merge($block, $options);
        // Set info
        $formOption = array();
        $formOption['field'] = '';
        $formOption['location'] = Pi::api('location', 'guide')->locationForm();
        // Set form
        $action = Pi::url(Pi::service('url')->assemble('guide', array(
            'module'        => $module,
            'controller'    => 'search',
        )));
        $form = new SearchForm('search', $formOption);
        $form->setAttribute('action', $action);
        $block['form'] = $form;
        $block['locationLevel'] = $formOption['location'];
        return $block;
    }

    public static function getTree($elements, $parentId = 0)
    {
        $category = array();
        foreach ($elements as $element) {
            if ($element['parent'] == $parentId) {
                $depth = 0;
                $category[$element['id']]['id'] = $element['id'];
                $category[$element['id']]['title'] = $element['title'];
                $category[$element['id']]['url'] = $element['url'];
                $children = self::getTree($elements, $element['id']);
                if ($children) {
                    $depth++;
                    foreach ($children as $key => $value) {
                        $category[$element['id']]['children'][$key] = array(
                            'id' => $value['id'],
                            'title' => $value['title'],
                            'url' => $value['url'],
                            'children' => empty($value['children']) ? '' : $value['children'],
                        );
                    }
                }       
                unset($elements[$element['id']]);
                unset($depth);            
            }
        }
        return $category;
    }

    public static function getTreeHtml($list)
    {
        $html = '<ul class="nav nav-pills">' . PHP_EOL;
        foreach ($list as $sub) {
            $html .= '<li>' . PHP_EOL;
            $html .= '<a title="' . $sub['title'] . '" href="' . $sub['url'] . '">' . $sub['title'] . '</a>' . PHP_EOL;
            if (!empty($sub['children'])) {
                $html .= self::getTreeHtml($sub['children']);
            }
            $html .= '</li>' . PHP_EOL;
        }
        $html .= '</ul>' . PHP_EOL;
        return $html;
    }
}