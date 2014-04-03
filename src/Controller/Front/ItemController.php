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

class ItemController extends ActionController
{
    public function indexAction()
    {
        $test = array(
            'Item Controller',
            'Index Action',
        );
        // Set view
        $this->view()->setTemplate('empty');
        $this->view()->assign('test', $test);
    }

    public function printAction()
    {
        $test = array(
            'Item Controller',
            'Print Action',
        );
        // Set view
        $this->view()->setTemplate('empty');
        $this->view()->assign('test', $test);
    }

    public function reviewAction()
    {
        $test = array(
            'Item Controller',
            'Review Action',
        );
        // Set view
        $this->view()->setTemplate('empty');
        $this->view()->assign('test', $test);
    }

    public function addReviewAction()
    {
        $test = array(
            'Item Controller',
            'Add Review Action',
        );
        // Set view
        $this->view()->setTemplate('empty');
        $this->view()->assign('test', $test);
    }

    public function serviceAction()
    {
        $test = array(
            'Item Controller',
            'Service Action',
        );
        // Set view
        $this->view()->setTemplate('empty');
        $this->view()->assign('test', $test);
    }

    public function blogAction()
    {
        $test = array(
            'Item Controller',
            'Blog Action',
        );
        // Set view
        $this->view()->setTemplate('empty');
        $this->view()->assign('test', $test);
    }

    public function storyAction()
    {
        $test = array(
            'Item Controller',
            'Story Action',
        );
        // Set view
        $this->view()->setTemplate('empty');
        $this->view()->assign('test', $test);
    }
}