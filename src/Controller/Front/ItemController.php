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

class ItemController extends IndexController
{
    public function indexAction()
    {
        // Get info from url
        $slug = $this->params('slug');
        $module = $this->params('module');
        // Get config
        $config = Pi::service('registry')->config->read($module);
        // Get category list
        $categoryList = Pi::api('category', 'guide')->categoryList();
        // Find item
        $item = $this->getModel('item')->find($slug, 'slug');
        $item = Pi::api('item', 'guide')->canonizeitem($item, $categoryList);
        // Check item
        if (!$item || $item['status'] != 1) {
            $this->jump(array('', 'module' => $module, 'controller' => 'index'), __('The item not found.'), 'error');
        }
        // Update Hits
        $this->getModel('item')->update(array('hits' => $item['hits'] + 1), array('id' => $item['id']));
        // Get extra
        if ($item['extra'] && $config['view_extra']) {
            $extra = Pi::api('extra', 'guide')->item($item['id']);
            $this->view()->assign('extra', $extra);
        }
        // Get attached files
        if ($item['attach'] && $config['view_attach']) {
            $attach = Pi::api('item', 'guide')->AttachList($item['id']);
            $this->view()->assign('attach', $attach);
        }
        // Get new items in category
        if ($config['view_incategory']) {
            $where = array(
                'status'          => 1,
                'time_start < ?'  => time(),
                'time_end > ?'    => time(),
                'category'        => $item['category'],
            );
            $itemList = $this->itemList($where);
            $this->view()->assign('itemList', $itemList);
            $this->view()->assign('itemTitle', __('New items'));
        }
        // Set tag
        if ($config['view_tag']) {
            $tag = Pi::service('tag')->get($module, $item['id'], '');
            $this->view()->assign('tag', $tag);  
        }
        // Set view
        $this->view()->headTitle($item['seo_title']);
        $this->view()->headDescription($item['seo_description'], 'set');
        $this->view()->headKeywords($item['seo_keywords'], 'set');
        $this->view()->setTemplate('item_item');
        $this->view()->assign('itemItem', $item);
        $this->view()->assign('categoryItem', $item['categories']);
        $this->view()->assign('config', $config);
    }

    public function printAction()
    {
        $test = array(
            'Item Controller',
            'Print Action',
        );
        // Set view
        $this->view()->setTemplate('empty');
        $this->view()->assign('test', $test);
    }

    public function reviewAction()
    {
        $test = array(
            'Item Controller',
            'Review Action',
        );
        // Set view
        $this->view()->setTemplate('empty');
        $this->view()->assign('test', $test);
    }

    public function addReviewAction()
    {
        $test = array(
            'Item Controller',
            'Add Review Action',
        );
        // Set view
        $this->view()->setTemplate('empty');
        $this->view()->assign('test', $test);
    }

    public function serviceAction()
    {
        $test = array(
            'Item Controller',
            'Service Action',
        );
        // Set view
        $this->view()->setTemplate('empty');
        $this->view()->assign('test', $test);
    }

    public function blogAction()
    {
        $test = array(
            'Item Controller',
            'Blog Action',
        );
        // Set view
        $this->view()->setTemplate('empty');
        $this->view()->assign('test', $test);
    }

    public function storyAction()
    {
        $test = array(
            'Item Controller',
            'Story Action',
        );
        // Set view
        $this->view()->setTemplate('empty');
        $this->view()->assign('test', $test);
    }
}