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

class TagController extends IndexController
{
	public function termAction()
    {
        // Get info from url
        $module = $this->params('module');
        $slug = $this->params('slug');
        // Get config
        $config = Pi::service('registry')->config->read($module);
        // Check slug
        if (!isset($slug) || empty($slug)) {
            $url = array('', 'module' => $module, 'controller' => 'index', 'action' => 'index');
            $this->jump($url, __('The tag not set.'), 'error');
        }
        // Get id from tag module
        $tagId = array();
        $tags = Pi::service('tag')->getList($slug, $module);
        foreach ($tags as $tag) {
            $tagId[] = $tag['item'];
        }
        // Check slug
        if (empty($tagId)) {
        	$url = array('', 'module' => $module, 'controller' => 'index', 'action' => 'index');
            $this->jump($url, __('The tag not found.'), 'error');
        }
        // Set info
        $where = array('status' => 1, 'item' => $tagId);
        // Get item List
        $itemList = $this->itemList($where);
        // Set paginator info
        $template = array(
            'controller' => 'tag',
            'action' => 'term',
            'slug' => urlencode($slug),
        );
        // Get paginator
        $paginator = $this->itemPaginator($template, $where);
        // Set header and title
        $title = sprintf(__('All items by %s tag'), $slug);
        $seoTitle = Pi::api('text', 'guide')->title($title);
        $seoDescription = Pi::api('text', 'guide')->description($title);
        $seoKeywords = Pi::api('text', 'guide')->keywords($title);
        // Set view
        $this->view()->headTitle($seoTitle);
        $this->view()->headDescription($seoDescription, 'set');
        $this->view()->headKeywords($seoKeywords, 'set');
        $this->view()->setTemplate('item_list');
        $this->view()->assign('itemList', $itemList);
        $this->view()->assign('itemTitle', $title);
        $this->view()->assign('paginator', $paginator);
        $this->view()->assign('config', $config);
    }

    public function listAction()
    {
        // Get info from url
        $module = $this->params('module');
    	$where = array('module' => $module);
        $order = array('count DESC', 'id DESC');
    	$select = Pi::model('stats', 'tag')->select()->where($where)->order($order);
    	$rowset = Pi::model('stats', 'tag')->selectWith($select);
    	foreach ($rowset as $row) {
            $tag = Pi::model('tag', 'tag')->find($row->term, 'term');
            $tagList[$row->id] = $row->toArray();
            $tagList[$row->id]['term'] = $tag['term'];
            $tagList[$row->id]['url'] = $this->url('', array(
            	'controller' => 'tag', 
            	'action' => 'term', 
            	'slug' => urldecode($tag['term'])
            	));
        }
        // Set header and title
        $title = __('List of all used tags on guide');
        $seoTitle = Pi::api('text', 'guide')->title($title);
        $seoDescription = Pi::api('text', 'guide')->description($title);
        $seoKeywords = Pi::api('text', 'guide')->keywords($title);
        // Set view
        $this->view()->headTitle($seoTitle);
        $this->view()->headDescription($seoDescription, 'set');
        $this->view()->headKeywords($seoKeywords, 'set');
        $this->view()->setTemplate('tag_list');
        $this->view()->assign('title', $title);
        $this->view()->assign('tagList', $tagList);
    }
}