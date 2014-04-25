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
            'title'         => _a('Item'),
            'controller'    => 'item',
            'permission'    => 'item',
        ),
        array(
            'title'         => _a('Category'),
            'controller'    => 'category',
            'permission'    => 'category',
        ),
        array(
            'title'         => _a('Location'),
            'controller'    => 'location',
            'permission'    => 'location',
        ),
        array(
            'title'         => _a('Service'),
            'controller'    => 'service',
            'permission'    => 'service',
        ),
        array(
            'title'         => _a('Extra'),
            'controller'    => 'extra',
            'permission'    => 'extra',
        ),
        array(
            'title'         => _a('Special'),
            'controller'    => 'special',
            'permission'    => 'special',
        ),
        array(
            'title'         => _a('Review'),
            'controller'    => 'review',
            'permission'    => 'review',
        ),
        array(
            'title'         => _a('Attach'),
            'controller'    => 'attach',
            'permission'    => 'attach',
        ),
        array(
            'title'         => _a('Score'),
            'controller'    => 'score',
            'permission'    => 'score',
        ),
        array(
            'title'         => _a('Package'),
            'controller'    => 'package',
            'permission'    => 'package',
        ),
        array(
            'title'         => _a('Orders'),
            'controller'    => 'order',
            'permission'    => 'order',
        ),
        array(
            'title'         => _a('Logs'),
            'controller'    => 'log',
            'permission'    => 'log',
        ),
    ),
    // Front section
    'front' => array(
        array(
            'title'         => _a('Index page'),
            'controller'    => 'index',
            'permission'    => 'public',
            'block'         => 1,
        ),
        array(
            'title'         => _a('Category'),
            'controller'    => 'category',
            'permission'    => 'public',
            'block'         => 1,
        ),
        array(
            'title'         => _a('Category list'),
            'controller'    => 'category',
            'action'        => 'list',
            'permission'    => 'public',
            'block'         => 1,
        ),
        array(
            'title'         => _a('Tag'),
            'controller'    => 'tag',
            'permission'    => 'public',
            'block'         => 1,
        ),
        array(
            'title'         => _a('Tag list'),
            'controller'    => 'tag',
            'action'        => 'list',
            'permission'    => 'public',
            'block'         => 1,
        ),
        array(
            'title'         => _a('Item'),
            'controller'    => 'item',
            'permission'    => 'public',
            'block'         => 1,
        ),
        array(
            'title'         => _a('Manage'),
            'controller'    => 'manage',
            'permission'    => 'manage',
            'block'         => 1,
        ),
        array(
            'title'         => _a('Package'),
            'controller'    => 'package',
            'permission'    => 'public',
            'block'         => 1,
        ),
        array(
            'title'         => _a('Search'),
            'controller'    => 'search',
            'permission'    => 'public',
            'block'         => 1,
        ),
    ),
);