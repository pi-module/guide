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
use Module\Guide\Form\ServiceCategoryForm;
use Module\Guide\Form\ServiceCategoryFilter;

class ServiceController extends ActionController
{
    /**
     * Image Prefix
     */
    protected $ServiceImagePrefix = 'service_';

    /**
     * Category Columns
     */
    protected $serviceColumns = array(
    	'id', 'category', 'title', 'price', 'description', 'image', 'path', 'item', 'time_create', 'status'
    );

    /**
     * Category Columns
     */
    protected $serviceCategoryColumns = array(
    	'id', 'title', 'time_create', 'status'
    );

    public function indexAction()
    {
        // Get page
        $page = $this->params('page', 1);
        $module = $this->params('module');
        // Get info
        $list = array();
        $order = array('id DESC', 'time_create DESC');
        $offset = (int)($page - 1) * $this->config('admin_perpage');
        $limit = intval($this->config('admin_perpage'));
        $select = $this->getModel('service_category')->select()->order($order)->offset($offset)->limit($limit);
        $rowset = $this->getModel('service_category')->selectWith($select);
        // Make list
        foreach ($rowset as $row) {
            $list[$row->id] = $row->toArray();
        }
        // Go to update page if empty
        if (empty($list)) {
            return $this->redirect()->toRoute('', array('action' => 'update'));
        }
        // Set paginator
        $count = array('count' => new \Zend\Db\Sql\Predicate\Expression('count(*)'));
        $select = $this->getModel('service_category')->select()->columns($count);
        $count = $this->getModel('service_category')->selectWith($select)->current()->count;
        $paginator = Paginator::factory(intval($count));
        $paginator->setItemCountPerPage($this->config('admin_perpage'));
        $paginator->setCurrentPageNumber($page);
        $paginator->setUrlOptions(array(
            'router'    => $this->getEvent()->getRouter(),
            'route'     => $this->getEvent()->getRouteMatch()->getMatchedRouteName(),
            'params'    => array_filter(array(
                'module'        => $this->getModule(),
                'controller'    => 'service',
                'action'        => 'index',
            )),
        ));
        // Set view
        $this->view()->setTemplate('service_index');
        $this->view()->assign('list', $list);
        $this->view()->assign('paginator', $paginator);
    }

    public function updateAction()
    {
        // Get id
        $id = $this->params('id');
        $module = $this->params('module');
        // Set form
        $form = new ServiceCategoryForm('serviceCategory');
        if ($this->request->isPost()) {
        	$data = $this->request->getPost();
            $form->setInputFilter(new ServiceCategoryFilter);
            $form->setData($data);
            if ($form->isValid()) {
            	$values = $form->getData();
            	// Set just service_category fields
            	foreach (array_keys($values) as $key) {
                    if (!in_array($key, $this->serviceCategoryColumns)) {
                        unset($values[$key]);
                    }
                }
                // Set time
                if (empty($values['id'])) {
                    $values['time_create'] = time();
                }
                // Save values
                if (!empty($values['id'])) {
                    $row = $this->getModel('service_category')->find($values['id']);
                } else {
                    $row = $this->getModel('service_category')->createRow();
                }
                $row->assign($values);
                $row->save();
                // Add log
                $operation = (empty($values['id'])) ? 'add' : 'edit';
                Pi::api('log', 'guide')->addLog('service_category', $row->id, $operation);
                $message = __('Category data saved successfully.');
                $this->jump(array('action' => 'index'), $message);
            } else {
                $message = __('Invalid data, please check and re-submit.');
            }	
        } else {
            if ($id) {
            	$service_category = $this->getModel('service_category')->find($id)->toArray();
                $form->setData($service_category);
            }
        }
        // Set view
        $this->view()->setTemplate('service_update');
        $this->view()->assign('form', $form);
        $this->view()->assign('title', __('Add a category'));
    }
}