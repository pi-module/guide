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
use Module\Guide\Form\CustomerForm;
use Module\Guide\Form\CustomerFilter;

class ManageController extends ActionController
{
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
        $this->view()->assign('form', $form);
    }

    public function dashboardAction()
    {
        // Check user is login or not
        Pi::service('authentication')->requireLogin();
        // Get uid
        $uid = Pi::user()->getId();
        // Get is customer or not
        $customer = $this->getModel('customer')->find($uid, 'uid');
        if (empty($customer)) {
        	return $this->redirect()->toRoute('', array(
            	'controller' => 'manage',
            	'action'     => 'register',
        	));
        }
        $customer = $customer->toArray();





        // Set view
        $this->view()->setTemplate('manage_dashboard');
        $this->view()->assign('customer', $customer);
    }

    public function updateAction()
    {
        // Set view
        $this->view()->setTemplate('manage_update');
    }

    public function attachAction()
    {
        // Set view
        $this->view()->setTemplate('manage_attach');
    }

    public function serviceAction()
    {
        // Set view
        $this->view()->setTemplate('manage_service');
    }

    public function paymentAction()
    {
        // Set view
        $this->view()->setTemplate('manage_payment');
    }

    public function logAction()
    {
        // Set view
        $this->view()->setTemplate('manage_log');
    }
}