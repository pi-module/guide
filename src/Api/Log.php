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
namespace Module\Guide\Api;

use Pi;
use Pi\Application\Api\AbstractApi;
use Zend\Json\Json;

/*
 * Pi::api('log', 'guide')->addLog($section, $item, $operation);
 */

class Log extends AbstractApi
{
    public function addLog($section, $item, $operation)
    {
    	$row = Pi::model('log', $this->getModule())->createRow();
    	$row->uid = Pi::user()->getId();
    	$row->ip = Pi::user()->getIp();
    	$row->time_create = time();
    	$row->section = $section;
    	$row->item = $item;
    	$row->operation = $operation;
    	$row->save();
    }
}