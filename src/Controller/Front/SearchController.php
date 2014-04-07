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
use Module\Guide\Form\SearchForm;
use Module\Guide\Form\SearchFilter;
use Zend\Json\Json;

class SearchController extends IndexController
{
	public function indexAction()
    {
    	// Get info from url
        $module = $this->params('module');
    	$option = array();
        // Get config
        $config = Pi::service('registry')->config->read($module);
        // Get extra field
        //$fields = Pi::api('extra', 'guide')->Get();
        //$option['field'] = $fields['extra'];
        $option['field'] = '';
        // Get location
        $option['location'] = Pi::api('location', 'guide')->locationForm();
        // Set form
    	$form = new SearchForm('search', $option);
    	if ($this->request->isPost()) {
    		$data = $this->request->getPost();
    		$form->setInputFilter(new SearchFilter($option));
            $form->setData($data);
            if ($form->isValid()) {
            	$_SESSION['guide']['search'] = $form->getData();
            	$message = __('Your search successfully. Go to result page');
            	$url = array('action' => 'result');
                $this->jump($url, $message, 'success');
            }
    	} else {
    		unset($_SESSION['guide']['search']);
    	}
    	// Set view
        $this->view()->headTitle($config['text_title_search']);
        $this->view()->headDescription($config['text_description_search'], 'set');
        $this->view()->headKeywords($config['text_keywords_search'], 'set');
        $this->view()->setTemplate('search_form');
        $this->view()->assign('form', $form);
        $this->view()->assign('locationCategory', $option['location']);
    }

    public function resultAction()
    {
        // Get search
        $search = $_SESSION['guide']['search'];
        if (empty($search)) {
        	$message = __('Your search session is empty, please search again');
            $url = array('action' => 'index');
            $this->jump($url, $message);
        }
        // Get info from url
        $module = $this->params('module');
        // Get config
        $config = Pi::service('registry')->config->read($module);
        // Set item info from search
        $where = array('status' => 1);
        // Set title
        if (isset($search['title']) 
            && !empty($search['title']))
        {
            switch ($search['type']) {
                case 1:
                    $where['title LIKE ?'] = '%' . $search['title'] . '%';
                    break;

                case 2:
                    $where['title LIKE ?'] = $search['title'] . '%';
                    break;
                
                case 3:
                    $where['title LIKE ?'] = '%' . $search['title'];
                    break;
                
                case 4:
                    $where['title LIKE ?'] = $search['title'];
                    break;          
            }
        }
        // Set category
        if (isset($search['category']) 
            && !empty($search['category']) 
            && is_array($search['category']))
        {
            $categoryId = Pi::api('category', $module)->findFromCategory($search['category']);
        }
        // Set extra
        /*$extraSearch = Pi::api('extra', 'guide')->SearchForm($search);
        if (!empty($extraSearch)) {
            $extraId = Pi::api('extra', 'guide')->findFromExtra($extraSearch);
        }*/
        // Set location
        $locationSearch = Pi::api('location', 'guide')->locationSearch($search);
        if (!empty($locationSearch)) {
        	$where['location'] = $locationSearch;
        }
        // Set where id
        /* if (!empty($categoryId) && !empty($extraId)) {
            $itemId = array_merge($categoryId, $extraId);
            $itemId = array_unique($itemId);
            $where['id'] = $itemId;
        } elseif (!empty($categoryId) && empty($extraId)) {
            $where['id'] = $categoryId;
        } elseif (empty($categoryId) && !empty($extraId)) {
            $where['id'] = $extraId;
        } */
        if (!empty($categoryId)) {
            $where['id'] = $categoryId;
        }
        // Get item List
        $itemList = $this->searchList($where);
        // Set paginator info
        $template = array(
            'controller'  => 'search',
            'action'      => 'result',
            );
        // Get paginator
        $paginator = $this->searchPaginator($template, $where);
        // Set header and title
        if (isset($search['title']) 
            && !empty($search['title']))
        {
            $title = sprintf(__('Search result of %s'), $search['title']);
        } else {
            $title = __('Search result');
        }
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

    public function ajaxAction()
    {
        $this->view()->setTemplate(false);
        // Get id
        $category = $this->params('category');
        $parent = $this->params('parent');
        $module = $this->params('module');
        $element = array();
        // find
        $element = Pi::api('location', 'guide')->locationFormElement($category, $parent);
        return $element;
    }
}