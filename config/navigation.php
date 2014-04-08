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
    'front'   => array(
        'category' => array(
            'label'         => _a('Category list'),
            'permission'    => array(
                'resource'  => 'public',
            ),
            'route'         => 'guide',
            'module'        => 'guide',
            'controller'    => 'category',
            'action'        => 'list',
        ),

        'tag' => array(
            'label'         => _a('Tag list'),
            'permission'    => array(
                'resource'  => 'public',
            ),
            'route'         => 'guide',
            'module'        => 'guide',
            'controller'    => 'tag',
            'action'        => 'list',
        ),

        'package' => array(
            'label'         => _a('Package'),
            'permission'    => array(
                'resource'  => 'public',
            ),
            'route'         => 'guide',
            'module'        => 'guide',
            'controller'    => 'package',
            'action'        => 'index',
        ),

        'search' => array(
            'label'         => _a('Search'),
            'permission'    => array(
                'resource'  => 'public',
            ),
            'route'         => 'guide',
            'module'        => 'guide',
            'controller'    => 'search',
            'action'        => 'index',
        ),

        'manage' => array(
            'label'         => _a('Manage'),
            'permission'    => array(
                'resource'  => 'manage',
            ),
            'route'         => 'guide',
            'module'        => 'guide',
            'controller'    => 'manage',
            'action'        => 'index',
        ),
    ),
    'admin' => array(
        'item' => array(
            'label'         => _a('Item'),
            'permission'    => array(
                'resource'  => 'item',
            ),
            'route'         => 'admin',
            'module'        => 'guide',
            'controller'    => 'item',
            'action'        => 'index',
        ),

        'category' => array(
            'label'         => _a('Category'),
            'permission'    => array(
                'resource'  => 'category',
            ),
            'route'         => 'admin',
            'module'        => 'guide',
            'controller'    => 'category',
            'action'        => 'index',
        ),

        'location' => array(
            'label'         => _a('Location'),
            'permission'    => array(
                'resource'  => 'location',
            ),
            'route'         => 'admin',
            'module'        => 'guide',
            'controller'    => 'location',
            'action'        => 'index',
        ),

        'service' => array(
            'label'         => _a('Service'),
            'permission'    => array(
                'resource'  => 'service',
            ),
            'route'         => 'admin',
            'module'        => 'guide',
            'controller'    => 'service',
            'action'        => 'index',
        ),

        'extra' => array(
            'label'         => _a('Extra'),
            'permission'    => array(
                'resource'  => 'extra',
            ),
            'route'         => 'admin',
            'module'        => 'guide',
            'controller'    => 'extra',
            'action'        => 'index',
        ),

        'special' => array(
            'label'         => _a('Special'),
            'permission'    => array(
                'resource'  => 'special',
            ),
            'route'         => 'admin',
            'module'        => 'guide',
            'controller'    => 'special',
            'action'        => 'index',
        ),

        'review' => array(
            'label'         => _a('Review'),
            'permission'    => array(
                'resource'  => 'review',
            ),
            'route'         => 'admin',
            'module'        => 'guide',
            'controller'    => 'review',
            'action'        => 'index',
        ),

        'attach' => array(
            'label'         => _a('Attach'),
            'permission'    => array(
                'resource'  => 'attach',
            ),
            'route'         => 'admin',
            'module'        => 'guide',
            'controller'    => 'attach',
            'action'        => 'index',
        ),

        'score' => array(
            'label'         => _a('Score'),
            'permission'    => array(
                'resource'  => 'score',
            ),
            'route'         => 'admin',
            'module'        => 'guide',
            'controller'    => 'score',
            'action'        => 'index',
        ),

        'package' => array(
            'label'         => _a('Package'),
            'permission'    => array(
                'resource'  => 'package',
            ),
            'route'         => 'admin',
            'module'        => 'guide',
            'controller'    => 'package',
            'action'        => 'index',
        ),

        'order' => array(
            'label'         => _a('Orders'),
            'permission'    => array(
                'resource'  => 'order',
            ),
            'route'         => 'admin',
            'module'        => 'guide',
            'controller'    => 'order',
            'action'        => 'index',
        ),

        'log' => array(
            'label'         => _a('Logs'),
            'permission'    => array(
                'resource'  => 'log',
            ),
            'route'         => 'admin',
            'module'        => 'guide',
            'controller'    => 'log',
            'action'        => 'index',
        ),
    ),
);