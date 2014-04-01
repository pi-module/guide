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
use Module\Guide\Form\ScoreForm;
use Module\Guide\Form\ScoreFilter;

class ScoreController extends ActionController
{
    /**
     * Category Columns
     */
    protected $scoreColumns = array(
    	'id', 'title', 'status'
    );

    /**
     * index Action
     */
	public function indexAction()
    {
        // Get page
        $module = $this->params('module');
        // Get info
        $list = array();
        $order = array('id DESC');
        $select = $this->getModel('score')->select()->order($order);
        $rowset = $this->getModel('score')->selectWith($select);
        // Make list
        foreach ($rowset as $row) {
            $list[$row->id] = $row->toArray();
        }
        // Go to update page if empty
        if (empty($list)) {
            return $this->redirect()->toRoute('', array('action' => 'update'));
        }
        // Set view
        $this->view()->setTemplate('score_index');
        $this->view()->assign('list', $list);
    }

    /**
     * update Action
     */
    public function updateAction()
    {
        // Get id
        $id = $this->params('id');
        $module = $this->params('module');
        // Set form
        $form = new ScoreForm('score');
        if ($this->request->isPost()) {
        	$data = $this->request->getPost();
            $form->setInputFilter(new ScoreFilter);
            $form->setData($data);
            if ($form->isValid()) {
            	$values = $form->getData();
            	// Set just score fields
            	foreach (array_keys($values) as $key) {
                    if (!in_array($key, $this->scoreColumns)) {
                        unset($values[$key]);
                    }
                }
                // Save values
                if (!empty($values['id'])) {
                    $row = $this->getModel('score')->find($values['id']);
                } else {
                    $row = $this->getModel('score')->createRow();
                }
                $row->assign($values);
                $row->save();
                // Add log
                $operation = (empty($values['id'])) ? 'add' : 'edit';
                Pi::api('log', 'guide')->addLog('score', $row->id, $operation);
                $message = __('Score data saved successfully.');
                $this->jump(array('action' => 'index'), $message);
            } else {
                $message = __('Invalid data, please check and re-submit.');
            }	
        } else {
            if ($id) {
            	$score = $this->getModel('score')->find($id)->toArray();
                $form->setData($score);
            }
        }
        // Set view
        $this->view()->setTemplate('score_update');
        $this->view()->assign('form', $form);
        $this->view()->assign('title', __('Add score options'));
    }
}