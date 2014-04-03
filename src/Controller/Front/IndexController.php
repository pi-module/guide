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
namespace Module\Guide\Controller\Front;

use Pi;
use Pi\Mvc\Controller\ActionController;
use Pi\Paginator\Paginator;
use Zend\Db\Sql\Predicate\Expression;
use Zend\Json\Json;

class IndexController extends ActionController
{
    public function indexAction()
    {
        // Get info from url
        $module = $this->params('module');
        // Get config
        $config = Pi::service('registry')->config->read($module);
        // Set info
        $where = array(
            'status'          => 1,
            'time_start < ?'  => time(),
            'time_end > ?'    => time(),
        );
        // Get item List
        $itemList = $this->itemList($where);
        // Set paginator info
        $template = array(
            'controller'  => 'index',
            'action'      => 'index',
        );
        // Get paginator
        $paginator = $this->itemPaginator($template, $where);
        // category list
        $category = Pi::api('category', 'guide')->categoryList();
        // Get special
        if ($config['view_special']) {
            $specialList = Pi::api('special', 'guide')->getAll();
            $this->view()->assign('specialList', $specialList);
            $this->view()->assign('specialTitle', __('Special items'));
        }
        // Set view
        $this->view()->headTitle($config['text_title_homepage']);
        $this->view()->headDescription($config['text_description_homepage'], 'set');
        $this->view()->headKeywords($config['text_keywords_homepage'], 'set');
        $this->view()->setTemplate('item_list');
        $this->view()->assign('itemList', $itemList);
        $this->view()->assign('itemTitle', __('New items'));
        $this->view()->assign('categories', $category);
        $this->view()->assign('paginator', $paginator);
        $this->view()->assign('config', $config);
    }

    public function itemList($where)
    {
        // Set info
        $id = array();
        $page = $this->params('page', 1);
        $module = $this->params('module');
        $sort = $this->params('sort', 'start');
        $offset = (int)($page - 1) * $this->config('view_perpage');
        $limit = intval($this->config('view_perpage'));
        $order = $this->setOrder($sort);
        // Set info
        $columns = array('item' => new Expression('DISTINCT item'));
        // Get info from link table
        $select = $this->getModel('link')->select()->where($where)->columns($columns)
        ->order($order)->offset($offset)->limit($limit);
        $rowset = $this->getModel('link')->selectWith($select)->toArray();
        // Make list
        foreach ($rowset as $id) {
            $itemId[] = $id['item'];
        }
        // Set info
        $where = array('status' => 1, 'id' => $itemId);
        // Get category list
        $categoryList = Pi::api('category', 'guide')->categoryList();
        // Get list of item
        $select = $this->getModel('item')->select()->where($where)->order($order);
        $rowset = $this->getModel('item')->selectWith($select);
        foreach ($rowset as $row) {
            $item[$row->id] = Pi::api('item', 'guide')->canonizeItem($row, $categoryList);
        }
        // return item
        return $item;
    }

    public function searchList($where)
    {
        // Set info
        $id = array();
        $page = $this->params('page', 1);
        $module = $this->params('module');
        $sort = $this->params('sort', 'start');
        $offset = (int)($page - 1) * $this->config('view_perpage');
        $limit = intval($this->config('view_perpage'));
        $order = $this->setOrder($sort);
        // Set show just have stock
        if (isset($stock) && $stock == 1) {
            $where['stock'] > 0;
        }
        // Get category list
        $categoryList = Pi::api('category', 'guide')->categoryList();
        // Get list of item
        $select = $this->getModel('item')->select()->where($where)
        ->order($order)->offset($offset)->limit($limit);
        $rowset = $this->getModel('item')->selectWith($select);
        foreach ($rowset as $row) {
            $item[$row->id] = Pi::api('item', 'guide')->canonizeItem($row, $categoryList);
        }
        // return item
        return $item;   
    }

    public function itemPaginator($template, $where)
    {
        $template['module'] = $this->params('module');
        $template['sort'] = $this->params('sort');
        $template['page'] = $this->params('page', 1);
        // get count     
        $columns = array('count' => new Expression('count(DISTINCT `item`)'));
        $select = $this->getModel('link')->select()->where($where)->columns($columns);
        $template['count'] = $this->getModel('link')->selectWith($select)->current()->count;
        // paginator
        $paginator = $this->canonizePaginator($template);
        return $paginator;
    }

    public function searchPaginator($template, $where)
    {
        $template['module'] = $this->params('module');
        $template['sort'] = $this->params('sort');
        $template['page'] = $this->params('page', 1);
        // get count     
        $columns = array('count' => new Expression('count(*)'));
        $select = $this->getModel('item')->select()->where($where)->columns($columns);
        $template['count'] = $this->getModel('item')->selectWith($select)->current()->count;
        // paginator
        $paginator = $this->canonizePaginator($template);
        return $paginator;
    }

    public function canonizePaginator($template)
    {
        $template['slug'] = (isset($template['slug'])) ? $template['slug'] : '';
        $template['action'] = (isset($template['action'])) ? $template['action'] : 'index';
        // paginator
        $paginator = Paginator::factory(intval($template['count']));
        $paginator->setItemCountPerPage(intval($this->config('view_perpage')));
        $paginator->setCurrentPageNumber(intval($template['page']));
        $paginator->setUrlOptions(array(
            'router'    => $this->getEvent()->getRouter(),
            'route'     => $this->getEvent()->getRouteMatch()->getMatchedRouteName(),
            'params'    => array_filter(array(
                'module'        => $this->getModule(),
                'controller'    => $template['controller'],
                'action'        => $template['action'],
                'slug'          => $template['slug'],
                'sort'          => $template['sort'],
            )),
        ));
        return $paginator;
    }

    public function setOrder($sort = 'start')
    {
        // Set order
        switch ($sort) {
            case 'start':
            default:
                $order = array('time_start DESC', 'id DESC');
                break;
        } 
        return $order;
    }
}