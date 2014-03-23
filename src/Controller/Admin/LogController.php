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
use Module\Guide\Form\LogForm;
use Module\Guide\Form\LogFilter;

class LogController extends ActionController
{
	public function indexAction()
    {
        // Get page
        $page = $this->params('page', 1);
        $module = $this->params('module');
        $section = $this->params('section');
        $uid = $this->params('uid');
        // Get info
        $list = array();
        $order = array('id DESC', 'time_create DESC');
        $offset = (int)($page - 1) * $this->config('admin_perpage');
        $limit = intval($this->config('admin_perpage'));
        
        $where = array();
        if ($section) {
            $where['section'] = $section;
        }
        if ($uid) {
            $where['uid'] = $uid;
        }

        $select = $this->getModel('log')->select()->where($where)->order($order)->offset($offset)->limit($limit);
        $rowset = $this->getModel('log')->selectWith($select);
        // Make list
        foreach ($rowset as $row) {
            $list[$row->id] = $row->toArray();
            $list[$row->id]['time_create'] = _date($list[$row->id]['time_create']);
            switch ($list[$row->id]['section']) {
                case 'category':
                    $list[$row->id]['section_view'] = __('Category');
                    break;

                case 'extra':
                    $list[$row->id]['section_view'] = __('Extra');
                    break;

                case 'item':
                    $list[$row->id]['section_view'] =  __('Item');
                    break;

                case 'location':
                    $list[$row->id]['section_view'] =  __('Location');
                    break;

                case 'review':
                    $list[$row->id]['section_view'] =  __('Review');
                    break;

                case 'special':
                    $list[$row->id]['section_view'] =  __('Special');
                    break;

                case 'attach':
                    $list[$row->id]['section_view'] =  __('Attach');
                    break;

                case 'service':
                    $list[$row->id]['section_view'] =  __('Service');
                    break;
            }
        }
        // Set paginator
        $count = array('count' => new \Zend\Db\Sql\Predicate\Expression('count(*)'));
        $select = $this->getModel('log')->select()->columns($count)->where($where);
        $count = $this->getModel('log')->selectWith($select)->current()->count;
        $paginator = Paginator::factory(intval($count));
        $paginator->setItemCountPerPage($this->config('admin_perpage'));
        $paginator->setCurrentPageNumber($page);
        $paginator->setUrlOptions(array(
            'router'    => $this->getEvent()->getRouter(),
            'route'     => $this->getEvent()->getRouteMatch()->getMatchedRouteName(),
            'params'    => array_filter(array(
                'module'        => $this->getModule(),
                'controller'    => 'log',
                'action'        => 'index',
                'section'       => $section,
                'uid'           => $uid,
            )),
        ));
        // Set form
        $values = array(
            'section'         => $section,
            'uid'             => $uid,
        );
        $form = new LogForm('log');
        $form->setAttribute('action', $this->url('', array('action' => 'process')));
        $form->setData($values);
        // Set view
        $this->view()->setTemplate('log_index');
        $this->view()->assign('list', $list);
        $this->view()->assign('paginator', $paginator);
        $this->view()->assign('form', $form);
    }

    public function processAction()
    {
        if ($this->request->isPost()) {
            $data = $this->request->getPost();
            $form = new LogForm('lod');
            $form->setInputFilter(new LogFilter());
            $form->setData($data);
            if ($form->isValid()) {
                $values = $form->getData();
                $message = __('View filtered logs');
                $url = array(
                    'action' => 'index',
                    'section' => $values['section'],
                    'uid' => $values['uid'],
                );
            } else {
                $message = __('Not valid');
                $url = array(
                    'action' => 'index',
                );
            }
        } else {
            $message = __('Not set');
            $url = array(
                'action' => 'index',
            );
        } 
        return $this->jump($url, $message);  
    }
}