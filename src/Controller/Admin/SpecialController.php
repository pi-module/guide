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
use Module\Guide\Form\SpecialForm;
use Module\Guide\Form\SpecialFilter;

class SpecialController extends ActionController
{
    /**
     * Special Columns
     */
    protected $specialColumns = array(
        'id', 'item', 'time_publish', 'time_expire', 'status'
    );

    public function indexAction()
    {
        // Get item and category
        $where = array('time_expire > ?' => time());
        $columns = array('item');
        $select = $this->getModel('special')->select()->where($where)->columns($columns);
        $idSet = $this->getModel('special')->selectWith($select)->toArray();
        if (empty($idSet)) {
            return $this->redirect()->toRoute('', array('action' => 'update'));
        }
        // Set topics and stores
        foreach ($idSet as $special) {
            $itemArr[] = $special['item'];
        }
        // Get items
        $where = array('id' => array_unique($itemArr));
        $columns = array('id', 'title', 'slug');
        $select = $this->getModel('item')->select()->where($where)->columns($columns);
        $itemSet = $this->getModel('item')->selectWith($select);
        // Make item list
        foreach ($itemSet as $row) {
            $itemList[$row->id] = $row->toArray();
        }
        // Get special
        $where = array('time_expire > ?' => time());
        $order = array('id DESC', 'time_publish DESC');
        $select = $this->getModel('special')->select()->where($where)->order($order);
        $specialSet = $this->getModel('special')->selectWith($select);
        // Make special list
        foreach ($specialSet as $row) {
            $specialList[$row->id] = $row->toArray();
            $specialList[$row->id]['itemTitle'] = $itemList[$row->item]['title'];
            $specialList[$row->id]['itemSlug'] = $itemList[$row->item]['slug'];
            $specialList[$row->id]['time_publish'] = _date($specialList[$row->id]['time_publish']);
            $specialList[$row->id]['time_expire'] = _date($specialList[$row->id]['time_expire']);
        }
        // Set view
        $this->view()->setTemplate('special_index');
        $this->view()->assign('specials', $specialList);
    }

    public function updateAction()
    {
        // Get id
        $id = $this->params('id');
        $form = new SpecialForm('special');
        if ($this->request->isPost()) {
            $data = $this->request->getPost();
            $form->setInputFilter(new SpecialFilter);
            $form->setData($data);
            if ($form->isValid()) {
                $values = $form->getData();
                foreach (array_keys($values) as $key) {
                    if (!in_array($key, $this->specialColumns)) {
                        unset($values[$key]);
                    }
                }
                // Set time
                $values['time_publish'] = strtotime($values['time_publish']);
                $values['time_expire'] = strtotime($values['time_expire']);
                // Save values
                if (!empty($values['id'])) {
                    $row = $this->getModel('special')->find($values['id']);
                } else {
                    $row = $this->getModel('special')->createRow();
                }
                $row->assign($values);
                $row->save();
                // Add log
                $operation = (empty($values['id'])) ? 'add' : 'edit';
                Pi::api('log', 'guide')->addLog('special', $row->id, $operation);
                // Check it save or not
                if ($row->id) {
                    $message = __('Special data saved successfully.');
                    $this->jump(array('action' => 'index'), $message);
                }
            }
        } else {
            if ($id) {
                $values = $this->getModel('special')->find($id)->toArray();
                $form->setData($values);
            }
        }
        $this->view()->setTemplate('special_update');
        $this->view()->assign('form', $form);
        $this->view()->assign('title', __('Add Special'));
    }

    public function deleteAction()
    {
        // Get information
        $this->view()->setTemplate(false);
        $id = $this->params('id');
        $row = $this->getModel('special')->find($id);
        if ($row) {
            $row->delete();
            $this->jump(array('action' => 'special'), __('Selected special delete'));
        }
        $this->jump(array('action' => 'special'), __('Please select special'));
    }
}