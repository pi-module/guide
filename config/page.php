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
return array(
    // Admin section
    'admin' => array(
        array(
            'controller'    => 'item',
            'permission'    => 'item',
        ),
        array(
            'controller'    => 'category',
            'permission'    => 'category',
        ),
        array(
            'controller'    => 'location',
            'permission'    => 'location',
        ),
        array(
            'controller'    => 'service',
            'permission'    => 'service',
        ),
        array(
            'controller'    => 'extra',
            'permission'    => 'extra',
        ),
        array(
            'controller'    => 'special',
            'permission'    => 'special',
        ),
        array(
            'controller'    => 'review',
            'permission'    => 'review',
        ),
        array(
            'controller'    => 'attach',
            'permission'    => 'attach',
        ),
        array(
            'controller'    => 'score',
            'permission'    => 'score',
        ),
        array(
            'controller'    => 'log',
            'permission'    => 'log',
        ),
    ),
    // Front section
    'front' => array(),
);