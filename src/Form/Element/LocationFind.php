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
namespace Module\Guide\Form\Element;

use Pi;
use Zend\Form\Element\Button as ZendButton;

class LocationFind extends ZendButton
{
    /**
     * @return array
     */
    public function getAttributes()
    {
        $this->Attributes = array(
            'class' => 'btn btn-success btn-sm',
            'id' => 'item-find-location',
            'data-toggle' => 'button',
            'data-find' => $this->attributes['link'],
        );
        return $this->Attributes;
    }
}