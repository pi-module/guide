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
use Module\Guide\Form\LocationForm;
use Module\Guide\Form\LocationFilter;

class LocationController extends ActionController
{
    protected $locationColumns = array(
        'id', 'level', 'parent', 'title', 'route'
    );

    public function indexAction()
    {
        // Get page
        $module = $this->params('module');
        $level = $this->params('level', 1);
        $page = $this->params('page', 1);
        $locationLevel = Pi::api('location', 'guide')->locationLevel();
        // Get info
        $locationList = array();
        $order = array('id DESC');
        $offset = (int)($page - 1) * $this->config('admin_perpage');
        $limit = intval($this->config('admin_perpage'));
        $where = array('level' => $level);
        $select = $this->getModel('location')->select()->where($where)->order($order)->offset($offset)->limit($limit);
        $rowset = $this->getModel('location')->selectWith($select);
        // Make list
        foreach ($rowset as $row) {
            $locationList[$row->id] = $row->toArray();
            if ($level != 5) {
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
                'level'         => $level,
            )),
        ));
        // Set view
        $this->view()->setTemplate('location_index');
        $this->view()->assign('level', $level);
        $this->view()->assign('locationLevel', $locationLevel);
        $this->view()->assign('locationList', $locationList);
        $this->view()->assign('paginator', $paginator);
    }

    public function addAction()
    {
        // Check post     
        if ($this->request->isPost()) {
        	$data = $this->request->getPost();
        	// Set form
        	$form = new LocationForm('location');
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
            // Get id
            $level = $this->params('level');
            $module = $this->params('module');
            $locationLevel = Pi::api('location', 'guide')->locationLevel();
            // find location
            if ($level && $level == 1) {
                $parentId = 0;
                $title = sprintf(__('Add top location as %s'), $locationLevel[$level]);
            } else {
                $parent = $this->params('parent');
                $location = $this->getModel('location')->find($parent)->toArray();
                if ($location['level'] < 5) {
                    $level = $location['level'] + 1;
                } else {
                    $message = sprintf(__('You can not add child location on %s'), $locationLevel[5]);
                    $this->jump(array('action' => 'index'), $message);
                }
                $parentId = $location['id'];
                $title = sprintf(__('Add child location on %s as %s'), $location['title'], $locationLevel[$level]);
            }
            $formData = array(
                'parent' => $parentId,
                'level'  => $level,
            );
        	// Set form
        	$form = new LocationForm('location');
        	$form->setData($formData);
        }
        // Set view
        $this->view()->setTemplate('location_update');
        $this->view()->assign('form', $form);
        $this->view()->assign('title', $title);
    }

    public function editAction()
    {
        // Get id
        $id = $this->params('id');
        $module = $this->params('module');
        // Set form
        $form = new LocationForm('location');
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

    public function formElementAjaxAction()
    {
        $this->view()->setTemplate(false);
        // Get id
        $level = $this->params('level');
        $parent = $this->params('parent');
        // find
        $element = Pi::api('location', 'guide')->locationFormElement($level, $parent);
        return $element;
    }
}