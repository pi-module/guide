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
namespace Module\Guide\Controller\Admin;

use Pi;
use Pi\Mvc\Controller\ActionController;
use Pi\Paginator\Paginator;
use Module\Guide\Form\LocationCategoryForm;
use Module\Guide\Form\LocationCategoryFilter;
use Module\Guide\Form\LocationForm;
use Module\Guide\Form\LocationFilter;

class LocationController extends ActionController
{
    protected $locationColumns = array(
    	'id', 'category', 'parent', 'title', 'route'
    );

    protected $locationCategoryColumns = array(
    	'id', 'parent', 'title'
    );

    public function indexAction()
    {
        // Get page
        $module = $this->params('module');
        $category = $this->params('category');
        $page = $this->params('page', 1);
        // Get category info
        $categoryList = array();
        $select = $this->getModel('location_category')->select();
        $rowset = $this->getModel('location_category')->selectWith($select);
        // Make list
        foreach ($rowset as $row) {
            $categoryList[$row->id] = $row->toArray();
            $categoryList[$row->id]['url'] = $this->url('', array('action' => 'index', 'category' => $row->id));
        }
        // Check categtory have child or is end of tree
        $where = array('parent' => $category);
        $columns = array('count' => new \Zend\Db\Sql\Predicate\Expression('count(*)'));
        $select = $this->getModel('location_category')->select()->columns($columns)->where($where);
        $count = $this->getModel('location_category')->selectWith($select)->current()->count;
        // Get info
        $locationList = array();
        $order = array('id DESC');
        $offset = (int)($page - 1) * $this->config('admin_perpage');
        $limit = intval($this->config('admin_perpage'));
        $where = array('category' => $category);
        $select = $this->getModel('location')->select()->where($where)->order($order)->offset($offset)->limit($limit);
        $rowset = $this->getModel('location')->selectWith($select);
        // Make list
        foreach ($rowset as $row) {
            $locationList[$row->id] = $row->toArray();
            if ($count > 0) {
            	$locationList[$row->id]['add'] = $this->url('', array('action' => 'add', 'parent' => $row->id));
            }
            $locationList[$row->id]['edit'] = $this->url('', array('action' => 'edit', 'id' => $row->id));
        }
        // Set paginator
        $columns = array('count' => new \Zend\Db\Sql\Predicate\Expression('count(*)'));
        $select = $this->getModel('location')->select()->columns($columns)->where($where);
        $count = $this->getModel('location')->selectWith($select)->current()->count;
        $paginator = Paginator::factory(intval($count));
        $paginator->setItemCountPerPage($this->config('admin_perpage'));
        $paginator->setCurrentPageNumber($page);
        $paginator->setUrlOptions(array(
            'router'    => $this->getEvent()->getRouter(),
            'route'     => $this->getEvent()->getRouteMatch()->getMatchedRouteName(),
            'params'    => array_filter(array(
                'module'        => $this->getModule(),
                'controller'    => 'location',
                'action'        => 'index',
                'category'      => $category,
            )),
        ));
        // Set view
        $this->view()->setTemplate('location_index');
        $this->view()->assign('categoryId', $category);
        $this->view()->assign('categoryList', $categoryList);
        $this->view()->assign('locationList', $locationList);
        $this->view()->assign('paginator', $paginator);
    }

    public function updateAction()
    {
        // Get id
        $id = $this->params('id');
        $module = $this->params('module');
        $dd = 'new';
        if ($id) {
            $location_category = $this->getModel('location_category')->find($id)->toArray();
            $dd = 'edit';
        }
        // Set form
        $form = new LocationCategoryForm('locationCategory', array('dd' => $dd));
        if ($this->request->isPost()) {
        	$data = $this->request->getPost();
            $form->setInputFilter(new LocationCategoryFilter);
            $form->setData($data);
            if ($form->isValid()) {
            	$values = $form->getData();
            	// Set just service_category fields
            	foreach (array_keys($values) as $key) {
                    if (!in_array($key, $this->locationCategoryColumns)) {
                        unset($values[$key]);
                    }
                }
                // Save values
                if (!empty($values['id'])) {
                    $row = $this->getModel('location_category')->find($values['id']);
                } else {
                    $row = $this->getModel('location_category')->createRow();
                }
                $row->assign($values);
                $row->save();
                // Add log
                $operation = (empty($values['id'])) ? 'add' : 'edit';
                Pi::api('log', 'guide')->addLog('location_category', $row->id, $operation);
                $message = __('Category data saved successfully.');
                $url = array('action' => 'index');
                $this->jump($url, $message);
            } else {
                $message = __('Invalid data, please check and re-submit.');
            }	
        } else {
            if ($id) {
                $form->setData($location_category);
            }
        }
        // Set view
        $this->view()->setTemplate('location_category_update');
        $this->view()->assign('form', $form);
        $this->view()->assign('title', __('Add location category'));
    }

    public function addAction()
    {
        // Get id
        $parent = $this->params('parent');
        $module = $this->params('module');
        // Check post     
        if ($this->request->isPost()) {
        	$data = $this->request->getPost();
        	// Set form
        	$form = new LocationForm('location', array('type' => 'save'));
            $form->setInputFilter(new LocationFilter);
            $form->setData($data);
            if ($form->isValid()) {
            	$values = $form->getData();
            	// Set just location fields
            	foreach (array_keys($values) as $key) {
                    if (!in_array($key, $this->locationColumns)) {
                        unset($values[$key]);
                    }
                }
                // Save values
                $row = $this->getModel('location')->createRow();
                $row->assign($values);
                $row->save();
                // Add log
                Pi::api('log', 'guide')->addLog('location', $row->id, 'add');
                $message = __('Location data saved successfully.');
                $this->jump(array('action' => 'index'), $message);
            } else {
                $message = __('Invalid data, please check and re-submit.');
            }	
        } else {
        	// find location
        	$location = $location = $this->getModel('location')->find($parent)->toArray();
        	// find child category
        	$categoryList = array();
        	$where = array('parent' => $location['category']);
        	$select = $this->getModel('location_category')->select()->where($where);
            $rowset = $this->getModel('location_category')->selectWith($select);
            foreach ($rowset as $row) {
                $list[$row->id] = $row->toArray();
                $categoryList[$row->id] = $list[$row->id]['title'];
            }
            $formData = array('parent' => $location['id']);
        	// Set form
        	$form = new LocationForm('location', array('type' => 'add', 'category' => $categoryList));
        	$form->setData($formData);
        }
        // Set view
        $this->view()->setTemplate('location_update');
        $this->view()->assign('form', $form);
        $this->view()->assign('title', sprintf(__('Add child location on %s'), $location['title']));
        $this->view()->assign('message', $message);
    }

    public function editAction()
    {
        // Get id
        $id = $this->params('id');
        $module = $this->params('module');
        // Set form
        $form = new LocationForm('location', array('type' => 'edit'));
        if ($this->request->isPost()) {
            $data = $this->request->getPost();
            $form->setInputFilter(new LocationFilter);
            $form->setData($data);
            if ($form->isValid()) {
                $values = $form->getData();
                // Set just location fields
                foreach (array_keys($values) as $key) {
                    if (!in_array($key, $this->locationColumns)) {
                        unset($values[$key]);
                    }
                }
                $row = $this->getModel('location')->find($values['id']);
                $row->assign($values);
                $row->save();
                // Add log
                Pi::api('log', 'guide')->addLog('location', $row->id, 'edit');
                $message = __('Location data saved successfully.');
                $this->jump(array('action' => 'index'), $message);
            } else {
                $message = __('Invalid data, please check and re-submit.');
            }   
        } else {
            $location = $this->getModel('location')->find($id)->toArray();
            $form->setData($location);
        }
        // Set view
        $this->view()->setTemplate('location_update');
        $this->view()->assign('form', $form);
        $this->view()->assign('title', __('Edit location'));
    }

    public function manageAction()
    {
        // Get category info
        $categoryList = array();
        $select = $this->getModel('location_category')->select();
        $rowset = $this->getModel('location_category')->selectWith($select);
        // Make list
        foreach ($rowset as $row) {
            $categoryList[$row->id] = $row->toArray();
            $categoryList[$row->id]['url'] = $this->url('', array('action' => 'index', 'category' => $row->id));
            $categoryList[$row->id]['edit'] = $this->url('', array('action' => 'update', 'id' => $row->id));
        }
        // Set view
        $this->view()->setTemplate('location_manage');
        $this->view()->assign('categoryList', $categoryList);
    }
}