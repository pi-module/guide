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
            'controller'    => 'package',
            'permission'    => 'package',
        ),
        array(
            'controller'    => 'order',
            'permission'    => 'order',
        ),
        array(
            'controller'    => 'log',
            'permission'    => 'log',
        ),
    ),
    // Front section
    'front' => array(
        array(
            'controller'    => 'index',
            'permission'    => 'public',
            'block'         => 1,
        ),
        array(
            'controller'    => 'category',
            'permission'    => 'public',
            'block'         => 1,
        ),
        array(
            'controller'    => 'category',
            'action'        => 'list',
            'permission'    => 'public',
            'block'         => 1,
        ),
        array(
            'controller'    => 'tag',
            'permission'    => 'public',
            'block'         => 1,
        ),
        array(
            'controller'    => 'tag',
            'action'        => 'list',
            'permission'    => 'public',
            'block'         => 1,
        ),
        array(
            'controller'    => 'item',
            'permission'    => 'public',
            'block'         => 1,
        ),
        array(
            'controller'    => 'manage',
            'permission'    => 'manage',
            'block'         => 1,
        ),
        array(
            'controller'    => 'package',
            'permission'    => 'public',
            'block'         => 1,
        ),
        array(
            'controller'    => 'search',
            'permission'    => 'public',
            'block'         => 1,
        ),
    ),
);