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

class TagController extends IndexController
{
    public function indexAction()
    {
        $test = array(
            'Tag Controller',
        );
        // Set view
        $this->view()->setTemplate('empty');
        $this->view()->assign('test', $test);
    }
}