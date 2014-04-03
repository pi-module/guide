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

class CategoryController extends IndexController
{
	public function indexAction()
    {
        // Get info from url
        $module = $this->params('module');
        $slug = $this->params('slug');
        // Get config
        $config = Pi::service('registry')->config->read($module);
        // Get category information from model
        $category = $this->getModel('category')->find($slug, 'slug');
        // Check category
        if (!$category || $category['status'] != 1) {
            $this->jump(array('', 'module' => $module, 'controller' => 'index'), __('The category not found.'), 'error');
        }
        // Set info
        $where = array(
            'status'          => 1,
            'time_start < ?'  => time(),
            'time_end > ?'    => time(),
            'category'        => $category['id'],
        );
        // Get item List
        $itemList = $this->itemList($where);
        // Set paginator info
        $template = array(
            'controller'  => 'category',
            'slug'        => $slug,
        );
        // Get paginator
        $paginator = $this->itemPaginator($template, $where);
        // category list
        $categories = Pi::api('category', 'guide')->categoryList($category['id']);
        // Get special
        if ($config['view_special']) {
            $specialList = Pi::api('special', 'guide')->getAll();
            $this->view()->assign('specialList', $specialList);
            $this->view()->assign('specialTitle', __('Special items'));
        }
        // Set view
        $this->view()->headTitle($category['seo_title']);
        $this->view()->headDescription($category['seo_description'], 'set');
        $this->view()->headKeywords($category['seo_keywords'], 'set');
        $this->view()->setTemplate('item_list');
        $this->view()->assign('itemList', $itemList);
        $this->view()->assign('itemTitle', sprintf(__('New items on %s'), $category['title']));
        $this->view()->assign('category', $category);
        $this->view()->assign('categories', $categories);
        $this->view()->assign('paginator', $paginator);
        $this->view()->assign('config', $config);
    }

    public function listAction()
    {}
}