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
use Module\Guide\Form\ReviewForm;
use Module\Guide\Form\ReviewFilter;

class ReviewController extends ActionController
{
    /**
     * review Columns
     */
    protected $reviewColumns = array(
        'id', 'uid', 'item', 'title', 'description', 'time_create', 'status'
    );

    public function indexAction()
    {
        // Get page
        $page = $this->params('page', 1);
        $module = $this->params('module');
        $status = $this->params('status');
        $item = $this->params('item');
        // Get info
        $list = array();
        $order = array('id DESC', 'time_create DESC');
        $offset = (int)($page - 1) * $this->config('admin_perpage');
        $limit = intval($this->config('admin_perpage'));
        // Set where
        $where = array();
        if (!empty($status)) {
            $where['status'] = $status;
        }
        if (!empty($item)) {
            $where['item'] = $item;
        }
        // Select
        $select = $this->getModel('review')->select()->where($where)->order($order)->offset($offset)->limit($limit);
        $rowset = $this->getModel('review')->selectWith($select);
        // Make list
        foreach ($rowset as $row) {
            $list[$row->id] = $row->toArray();
            $list[$row->id]['description'] = Pi::service('markup')->render($row['description'], 'text', 'html');
            $list[$row->id]['time_create_view'] = _date($row->time_create);
            $list[$row->id]['userinfo'] = Pi::user()->get($row->uid, array('id', 'identity', 'name', 'email'));
        }
        // Set paginator
        $count = array('count' => new \Zend\Db\Sql\Predicate\Expression('count(*)'));
        $select = $this->getModel('review')->select()->columns($count)->where($where);
        $count = $this->getModel('review')->selectWith($select)->current()->count;
        $paginator = Paginator::factory(intval($count));
        $paginator->setItemCountPerPage($this->config('admin_perpage'));
        $paginator->setCurrentPageNumber($page);
        $paginator->setUrlOptions(array(
            'router'    => $this->getEvent()->getRouter(),
            'route'     => $this->getEvent()->getRouteMatch()->getMatchedRouteName(),
            'params'    => array_filter(array(
                'module'        => $this->getModule(),
                'controller'    => 'review',
                'action'        => 'index',
                'item'          => $item,
                'status'        => $status,
            )),
        ));
        // find item
        if (!empty($item)) {
            $item = Pi::api('item', 'guide')->getItemLight($item);
            $title = sprintf(__('List of all reviews from %s'), $item['title']);
            $this->view()->assign('item', $item);
        } else {
            $title = __('List of all reviews');
        }
        // Set view
        $this->view()->setTemplate('review_index');
        $this->view()->assign('list', $list);
        $this->view()->assign('paginator', $paginator);
        $this->view()->assign('title', $title);
    }

    public function updateAction()
    {
        // Get id and item
        $id = $this->params('id');
        $module = $this->params('module');
        // Check review
        if (!$id) {
            $message = __('Review not find');
            $url = array('action' => 'review');
            $this->jump($url, $message, 'error'); 
        }
        // Form
        $form = new ReviewForm('review');
        if ($this->request->isPost()) {
            $data = $this->request->getPost();
            $form->setInputFilter(new ReviewFilter);
            $form->setData($data);
            if ($form->isValid()) {
                $values = $form->getData();
                // Set just category fields
                foreach (array_keys($values) as $key) {
                    if (!in_array($key, $this->reviewColumns)) {
                        unset($values[$key]);
                    }
                }
                // Save values
                $row = $this->getModel('review')->find($values['id']);
                $row->assign($values);
                $row->save();
                // Update review count
                Pi::api('item', 'guide')->reviewCount($row->item);
                // Check it save or not
                if ($row->id) {
                    $message = __('Review data saved successfully.');
                    $url = array('action' => 'index', 'item' => $row->item);
                    $this->jump($url, $message);
                }
            }
        } else {
            $review = $this->getModel('review')->find($id)->toArray();
            $form->setData($review);
        }
        // Set view
        $this->view()->setTemplate('review_update');
        $this->view()->assign('form', $form);
        $this->view()->assign('title', __('Review'));
    }
}