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
            'label'         => __('Category list'),
            'permission'    => array(
                'resource'  => 'public',
            ),
            'route'         => 'guide',
            'module'        => 'guide',
            'controller'    => 'category',
            'action'        => 'list',
        ),

        'tag' => array(
            'label'         => __('Tag list'),
            'permission'    => array(
                'resource'  => 'public',
            ),
            'route'         => 'guide',
            'module'        => 'guide',
            'controller'    => 'tag',
            'action'        => 'list',
        ),

        'package' => array(
            'label'         => __('Package'),
            'permission'    => array(
                'resource'  => 'public',
            ),
            'route'         => 'guide',
            'module'        => 'guide',
            'controller'    => 'package',
            'action'        => 'index',
        ),

        'search' => array(
            'label'         => __('Search'),
            'permission'    => array(
                'resource'  => 'public',
            ),
            'route'         => 'guide',
            'module'        => 'guide',
            'controller'    => 'search',
            'action'        => 'index',
        ),

        'manage' => array(
            'label'         => __('Manage'),
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
            'label'         => __('Item'),
            'permission'    => array(
                'resource'  => 'item',
            ),
            'route'         => 'admin',
            'module'        => 'guide',
            'controller'    => 'item',
            'action'        => 'index',
        ),

        'category' => array(
            'label'         => __('Category'),
            'permission'    => array(
                'resource'  => 'category',
            ),
            'route'         => 'admin',
            'module'        => 'guide',
            'controller'    => 'category',
            'action'        => 'index',
        ),

        'location' => array(
            'label'         => __('Location'),
            'permission'    => array(
                'resource'  => 'location',
            ),
            'route'         => 'admin',
            'module'        => 'guide',
            'controller'    => 'location',
            'action'        => 'index',
        ),

        'service' => array(
            'label'         => __('Service'),
            'permission'    => array(
                'resource'  => 'service',
            ),
            'route'         => 'admin',
            'module'        => 'guide',
            'controller'    => 'service',
            'action'        => 'index',
        ),

        'extra' => array(
            'label'         => __('Extra'),
            'permission'    => array(
                'resource'  => 'extra',
            ),
            'route'         => 'admin',
            'module'        => 'guide',
            'controller'    => 'extra',
            'action'        => 'index',
        ),

        'special' => array(
            'label'         => __('Special'),
            'permission'    => array(
                'resource'  => 'special',
            ),
            'route'         => 'admin',
            'module'        => 'guide',
            'controller'    => 'special',
            'action'        => 'index',
        ),

        'review' => array(
            'label'         => __('Review'),
            'permission'    => array(
                'resource'  => 'review',
            ),
            'route'         => 'admin',
            'module'        => 'guide',
            'controller'    => 'review',
            'action'        => 'index',
        ),

        'attach' => array(
            'label'         => __('Attach'),
            'permission'    => array(
                'resource'  => 'attach',
            ),
            'route'         => 'admin',
            'module'        => 'guide',
            'controller'    => 'attach',
            'action'        => 'index',
        ),

        'score' => array(
            'label'         => __('Score'),
            'permission'    => array(
                'resource'  => 'score',
            ),
            'route'         => 'admin',
            'module'        => 'guide',
            'controller'    => 'score',
            'action'        => 'index',
        ),

        'package' => array(
            'label'         => __('Package'),
            'permission'    => array(
                'resource'  => 'package',
            ),
            'route'         => 'admin',
            'module'        => 'guide',
            'controller'    => 'package',
            'action'        => 'index',
        ),

        'order' => array(
            'label'         => __('Orders'),
            'permission'    => array(
                'resource'  => 'order',
            ),
            'route'         => 'admin',
            'module'        => 'guide',
            'controller'    => 'order',
            'action'        => 'index',
        ),

        'log' => array(
            'label'         => __('Logs'),
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