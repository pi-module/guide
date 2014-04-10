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
use Pi\File\Transfer\Upload;
use Module\Guide\Form\ServiceCategoryForm;
use Module\Guide\Form\ServiceCategoryFilter;
use Module\Guide\Form\ServiceForm;
use Module\Guide\Form\ServiceFilter;

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
    	'id', 'title', 'image', 'path', 'time_create', 'status'
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
        // Find service
        if ($id) {
            $service_category = $this->getModel('service_category')->find($id)->toArray();
            if ($service_category['image']) {
                $service_category['thumbUrl'] = sprintf('upload/%s/thumb/%s/%s', $this->config('image_path'), $service_category['path'], $service_category['image']);
                $option['thumbUrl'] = Pi::url($service_category['thumbUrl']);
                $option['removeUrl'] = $this->url('', array('action' => 'remove', 'id' => $service_category['id'], 'table' => 'service_category'));
            }
        }
        // Set form
        $form = new ServiceCategoryForm('serviceCategory');
        $form->setAttribute('enctype', 'multipart/form-data');
        if ($this->request->isPost()) {
        	$data = $this->request->getPost();
            $form->setInputFilter(new ServiceCategoryFilter);
            $form->setData($data);
            if ($form->isValid()) {
            	$values = $form->getData();
                // upload image
                if (!empty($file['image']['name'])) {
                    // Set upload path
                    $values['path'] = sprintf('%s/%s', date('Y'), date('m'));
                    $originalPath = Pi::path(sprintf('upload/%s/original/%s', $this->config('image_path'), $values['path']));
                    // Upload
                    $uploader = new Upload;
                    $uploader->setDestination($originalPath);
                    $uploader->setRename($this->ServiceImagePrefix . '%random%');
                    $uploader->setExtension($this->config('image_extension'));
                    $uploader->setSize($this->config('image_size'));
                    if ($uploader->isValid()) {
                        $uploader->receive();
                        // Get image name
                        $values['image'] = $uploader->getUploaded('image');
                        // process image
                        Pi::api('image', 'guide')->process($values['image'], $values['path']);
                    } else {
                        $this->jump(array('action' => 'update'), __('Problem in upload image. please try again'));
                    }
                } elseif (!isset($values['image'])) {
                    $values['image'] = '';  
                }
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
                $form->setData($service_category);
            }
        }
        // Set view
        $this->view()->setTemplate('service_update');
        $this->view()->assign('form', $form);
        $this->view()->assign('title', __('Add a category'));
    }

    public function listAction()
    {
        // Get item
        $item = $this->params('item');
        $module = $this->params('module');
        // Get item
        $item = Pi::api('item', 'guide')->getItem($item);
        $item['service'] = Pi::api('service', 'guide')->getService($item['id']);
        // Set view
        $this->view()->setTemplate('service_item');
        $this->view()->assign('item', $item);
    }

    public function manageAction()
    {
        // Get id
        $id = $this->params('id');
        $item = $this->params('item');
        $module = $this->params('module');
        $option = array();
        // Get item
        $item = Pi::api('item', 'guide')->getItem($item);
        // Find service
        if ($id) {
            $service = $this->getModel('service')->find($id)->toArray();
            if ($service['image']) {
                $service['thumbUrl'] = sprintf('upload/%s/thumb/%s/%s', $this->config('image_path'), $service['path'], $service['image']);
                $option['thumbUrl'] = Pi::url($service['thumbUrl']);
                $option['removeUrl'] = $this->url('', array('action' => 'remove', 'id' => $service['id']));
            }
        }
        // Set form
        $form = new ServiceForm('service', $option);
        $form->setAttribute('enctype', 'multipart/form-data');
        if ($this->request->isPost()) {
            $data = $this->request->getPost();
            $file = $this->request->getFiles();
            $form->setInputFilter(new ServiceFilter);
            $form->setData($data);
            if ($form->isValid()) {
                $values = $form->getData();
                // upload image
                if (!empty($file['image']['name'])) {
                    // Set upload path
                    $values['path'] = sprintf('%s/%s', date('Y'), date('m'));
                    $originalPath = Pi::path(sprintf('upload/%s/original/%s', $this->config('image_path'), $values['path']));
                    // Upload
                    $uploader = new Upload;
                    $uploader->setDestination($originalPath);
                    $uploader->setRename($this->ServiceImagePrefix . '%random%');
                    $uploader->setExtension($this->config('image_extension'));
                    $uploader->setSize($this->config('image_size'));
                    if ($uploader->isValid()) {
                        $uploader->receive();
                        // Get image name
                        $values['image'] = $uploader->getUploaded('image');
                        // process image
                        Pi::api('image', 'guide')->process($values['image'], $values['path']);
                    } else {
                        $this->jump(array('action' => 'update'), __('Problem in upload image. please try again'));
                    }
                } elseif (!isset($values['image'])) {
                    $values['image'] = '';  
                }
                // Set just service_category fields
                foreach (array_keys($values) as $key) {
                    if (!in_array($key, $this->serviceColumns)) {
                        unset($values[$key]);
                    }
                }
                // Set values
                $values['item'] = $item['id'];
                // Set time
                if (empty($values['id'])) {
                    $values['time_create'] = time();
                }
                // Save values
                if (!empty($values['id'])) {
                    $row = $this->getModel('service')->find($values['id']);
                } else {
                    $row = $this->getModel('service')->createRow();
                }
                $row->assign($values);
                $row->save();
                // Add log
                $operation = (empty($values['id'])) ? 'add' : 'edit';
                Pi::api('log', 'guide')->addLog('service', $row->id, $operation);
                $message = __('Service data saved successfully.');
                $this->jump(array('action' => 'list', 'item' => $item['id']), $message);
            } else {
                $message = __('Invalid data, please check and re-submit.');
            }   
        } else {
            if ($id) {
                $form->setData($service);
            }
        }
        // Set view
        $this->view()->setTemplate('service_manage');
        $this->view()->assign('form', $form);
        $this->view()->assign('title', sprintf(__('Add a service to %s'), $item['title']));
    }

    public function removeAction()
    {
        // Get id and status
        $id = $this->params('id');
        $table = $this->params('table', 'service');
        // set category
        $row = $this->getModel($table)->find($id);
        // Check
        if ($row && !empty($id)) {
            // remove file
            /* $files = array(
                Pi::path(sprintf('upload/%s/original/%s/%s', $this->config('image_path'), $category->path, $category->image)),
                Pi::path(sprintf('upload/%s/large/%s/%s', $this->config('image_path'), $category->path, $category->image)),
                Pi::path(sprintf('upload/%s/medium/%s/%s', $this->config('image_path'), $category->path, $category->image)),
                Pi::path(sprintf('upload/%s/thumb/%s/%s', $this->config('image_path'), $category->path, $category->image)),
            );
            Pi::service('file')->remove($files); */
            // clear DB
            $row->image = '';
            $row->path = '';
            // Save
            if ($row->save()) {
                $message = sprintf(__('Image of %s removed'), $row->title);
                $status = 1;
            } else {
                $message = __('Image not remove');
                $status = 0;
            }
        } else {
            $message = __('Please select category');
            $status = 0;
        }
        return array(
            'status' => $status,
            'message' => $message,
        );
    }
}