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
use Pi\File\Transfer\Upload;
use Module\Guide\Form\CustomerForm;
use Module\Guide\Form\CustomerFilter;
use Module\Guide\Form\ItemSimpleForm;
use Module\Guide\Form\ItemSimpleFilter;
use Zend\Json\Json;

class ManageController extends ActionController
{
    /**
     * Item Image Prefix
     */
    protected $ImageItemPrefix = 'item_';

    /**
     * Item Columns
     */
    protected $itemColumns = array(
        'id', 'title', 'slug', 'type', 'category', 'summary', 'description', 'seo_title', 'seo_keywords', 'seo_description', 
        'status', 'time_create', 'time_update', 'time_start', 'time_end', 'uid', 'customer', 'package','hits', 'image', 
        'path', 'vote', 'rating', 'favourite', 'service', 'attach', 'extra', 'review', 'recommended', 'map_longitude', 
        'map_latitude', 'location', 'location_level', 'address1', 'address2', 'city', 'area', 'zipcode', 'phone1', 
        'phone2', 'mobile', 'website', 'email'
    );

    /**
     * customer Columns
     */
    protected $customerColumns = array(
    	'id', 'uid', 'first_name', 'last_name', 'phone', 'mobile', 'company', 
    	'address', 'country', 'city', 'zip_code', 'ip', 'time_create', 'time_update', 'status'
    );

    public function indexAction()
    {
        // Check user is login or not
        Pi::service('authentication')->requireLogin();
        // Get uid
        $uid = Pi::user()->getId();
        // Check is customer or not
        $customer = $this->getModel('customer')->find($uid, 'uid');
        if (empty($customer)) {
        	return $this->redirect()->toRoute('', array(
            	'controller' => 'manage',
            	'action'     => 'register',
        	));
        } else {
        	return $this->redirect()->toRoute('', array(
            	'controller' => 'manage',
            	'action'     => 'dashboard',
        	));
        }
    }

    public function registerAction()
    {
        // Check user is login or not
        Pi::service('authentication')->requireLogin();
        // Get uid
        $uid = Pi::user()->getId();
        // Check is customer or not
        $customer = $this->getModel('customer')->find($uid, 'uid');
        if (!empty($customer)) {
        	return $this->redirect()->toRoute('', array(
            	'controller' => 'manage',
            	'action'     => 'dashboard',
        	));
        }
        // Set form
        $form = new CustomerForm('customer');
        if ($this->request->isPost()) {
        	$data = $this->request->getPost();
            $form->setInputFilter(new CustomerFilter);
            $form->setData($data);
            if ($form->isValid()) {
            	$values = $form->getData();
            	// Set just customer fields
            	foreach (array_keys($values) as $key) {
                    if (!in_array($key, $this->customerColumns)) {
                        unset($values[$key]);
                    }
                }
                // Set values
                $values['time_create'] = time();
                $values['time_update'] = time();
                $values['status'] = 2;
                $values['uid'] = $uid;
                $values['ip'] = Pi::user()->getIp();
                // Save
                $row = $this->getModel('customer')->createRow();
                $row->assign($values);
                $row->save();
                // Jump
                $message = __('You registered on system successfully.');
                $this->jump(array('action' => 'dashboard'), $message);
            }
        }
        // Set view
        $this->view()->setTemplate('manage_register');
        $this->view()->assign('form', $form);
    }

    public function editAction()
    {
        // Canonize customer
        $customer = $this->canonizeCustomer();
        // Set form
        $form = new CustomerForm('customer');
        if ($this->request->isPost()) {
        	$data = $this->request->getPost();
            $form->setInputFilter(new CustomerFilter);
            $form->setData($data);
            if ($form->isValid()) {
            	$values = $form->getData();
            	// Set just customer fields
            	foreach (array_keys($values) as $key) {
                    if (!in_array($key, $this->customerColumns)) {
                        unset($values[$key]);
                    }
                }
                // Set values
                $values['time_update'] = time();
                $values['status'] = 3;
                $values['ip'] = Pi::user()->getIp();
                // Save
                $row = $this->getModel('customer')->find($values['id']);
                $row->assign($values);
                $row->save();
                // Jump
                $message = __('You customery informations update successfully.');
                $this->jump(array('action' => 'dashboard'), $message);
            }
        } else {
        	$form->setData($customer);
        }
        // Set view
        $this->view()->setTemplate('manage_edit');
        $this->view()->assign('customer', $customer);
        $this->view()->assign('form', $form);
    }

    public function dashboardAction()
    {
        // Canonize customer
        $customer = $this->canonizeCustomer();
        // Set info
        $where = array('customer' => $customer['id']);
        $order = array('time_start DESC', 'id DESC');
        // Get list of item
        $select = $this->getModel('item')->select()->where($where)->order($order);
        $rowset = $this->getModel('item')->selectWith($select);
        foreach ($rowset as $row) {
            $item[$row->id] = Pi::api('item', 'guide')->canonizeItemLight($row);
        }
        // Set view
        $this->view()->setTemplate('manage_dashboard');
        $this->view()->assign('customer', $customer);
        $this->view()->assign('list', $item);
    }

    public function updateAction()
    {
        // Canonize customer
        $customer = $this->canonizeCustomer();
        // Get id
        $id = $this->params('id');
        $module = $this->params('module');
        $option = array();
        $location = '';
        // Find item
        if ($id) {
            $item = $this->getModel('item')->find($id)->toArray();
            $item['category'] = Json::decode($item['category']);
            // Set image
            if ($item['image']) {
                $thumbUrl = sprintf('upload/%s/thumb/%s/%s', $this->config('image_path'), $item['path'], $item['image']);
                $option['thumbUrl'] = Pi::url($thumbUrl);
                $option['removeUrl'] = $this->url('', array('action' => 'remove', 'id' => $item['id']));
            }
            // Set location
            $location = $item['location'];
        }
        // Get extra field
        $fields = Pi::api('extra', 'guide')->Get();
        $option['field'] = $fields['extra'];
        // Get location
        $option['location'] = Pi::api('location', 'guide')->locationForm($location);
        // Set form
        $form = new ItemSimpleForm('item', $option);
        $form->setAttribute('enctype', 'multipart/form-data');
        if ($this->request->isPost()) {
            $data = $this->request->getPost();
            $file = $this->request->getFiles();
            // Form filter
            $form->setInputFilter(new ItemSimpleFilter($option));
            $form->setData($data);
            if ($form->isValid()) {
                $values = $form->getData();
                // Set extra data array
                if (!empty($fields['field'])) {
                    foreach ($fields['field'] as $field) {
                        $extra[$field]['field'] = $field;
                        $extra[$field]['data'] = $values[$field];
                    }
                }
                // Set location
                if (!empty($option['location'])) {
                    foreach ($option['location'] as $location) {
                        $element = sprintf('location-%s', $location['id']);
                        $element = $values[$element];
                        if (isset($element) && !empty($element)) {
                            $values['location'] = $element;
                            $values['location_level'] = $location['id'];
                        }
                    }
                }
                // upload image
                if (!empty($file['image']['name'])) {
                    // Set upload path
                    $values['path'] = sprintf('%s/%s', date('Y'), date('m'));
                    $originalPath = Pi::path(sprintf('upload/%s/original/%s', $this->config('image_path'), $values['path']));
                    // Upload
                    $uploader = new Upload;
                    $uploader->setDestination($originalPath);
                    $uploader->setRename($this->ImageItemPrefix . '%random%');
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
                // Set just item fields
                foreach (array_keys($values) as $key) {
                    if (!in_array($key, $this->itemColumns)) {
                        unset($values[$key]);
                    }
                }
                // Category
                $values['category'] = Json::encode(array_unique($values['category']));
                // Set seo_title
                $values['seo_title'] = Pi::api('text', 'guide')->title($values['title']);
                // Set seo_keywords
                $values['seo_keywords'] = Pi::api('text', 'guide')->keywords($values['title']);
                // Set seo_description
                $values['seo_description'] = Pi::api('text', 'guide')->description($values['title']);
                // Set slug
                $values['slug'] = Pi::api('text', 'guide')->slug($values['title'] . ' ' . rand('100', '999'));
                // Set time
                if (empty($values['id'])) {
                    $values['time_create'] = time();
                    $values['uid'] = Pi::user()->getId();
                    $values['customer'] = $customer['id'];
                }
                $values['time_update'] = time();
                // Save values
                if (!empty($values['id'])) {
                    $row = $this->getModel('item')->find($values['id']);
                } else {
                    $row = $this->getModel('item')->createRow();
                }
                $row->assign($values);
                $row->save();
                // Category
                Pi::api('category', 'guide')->setLink(
                    $row->id, 
                    $row->category, 
                    $row->time_create, 
                    $row->time_update, 
                    $row->time_start, 
                    $row->time_end, 
                    $row->status,
                    $row->rating,
                    $row->hits
                );
                // Extra
                if (!empty($extra)) {
                    Pi::api('extra', 'guide')->Set($extra, $row->id);
                }
                // Check it save or not
                if ($row->id) {
                    $message = __('Item data saved successfully.');
                    $this->jump(array('action' => 'attach'), $message);
                }
            }   
        } else {
            if ($id) {
                // Get Extra
                $item = Pi::api('extra', 'guide')->Form($item);
                // Set time
                $item['time_start'] = date('Y-m-d', $item['time_start']);
                $item['time_end'] = date('Y-m-d', $item['time_end']);
                // Set location
                $name = sprintf('location-%s', $item['location_level']);
                $item[$name] = $item['location'];
                // Set data 
                $form->setData($item);
            }
        }
        // Set view
        $this->view()->setTemplate('manage_update');
        $this->view()->assign('customer', $customer);
        $this->view()->assign('form', $form);
        $this->view()->assign('locationLevel', $option['location']);
    }

    public function attachAction()
    {
        // Canonize customer
        $customer = $this->canonizeCustomer();
        // Set view
        $this->view()->setTemplate('manage_attach');
        $this->view()->assign('customer', $customer);
    }

    public function serviceAction()
    {
        // Canonize customer
        $customer = $this->canonizeCustomer();
        // Set view
        $this->view()->setTemplate('manage_service');
        $this->view()->assign('customer', $customer);
    }

    public function paymentAction()
    {
        // Canonize customer
        $customer = $this->canonizeCustomer();
        // Set view
        $this->view()->setTemplate('manage_payment');
        $this->view()->assign('customer', $customer);
    }

    public function finishAction()
    {
        // Canonize customer
        $customer = $this->canonizeCustomer();
        // Set view
        $this->view()->setTemplate('manage_finish');
        $this->view()->assign('customer', $customer);
    }

    public function logAction()
    {
        // Canonize customer
        $customer = $this->canonizeCustomer();
        // Set view
        $this->view()->setTemplate('manage_log');
        $this->view()->assign('customer', $customer);
    }

    protected function canonizeCustomer()
    {
        // Check user is login or not
        Pi::service('authentication')->requireLogin();
        // Get uid
        $uid = Pi::user()->getId();
        // Check is customer or not
        $customer = $this->getModel('customer')->find($uid, 'uid');
        if (empty($customer)) {
            return $this->redirect()->toRoute('', array(
                'controller' => 'manage',
                'action'     => 'register',
            ));
        }
        $customer = $customer->toArray();
        $customer['avatar'] = Pi::service('user')->avatar($uid, 'medium');
        return $customer;
    }
}