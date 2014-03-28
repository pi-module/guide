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
    'front'   => array(),
    'admin' => array(
        'item' => array(
            'label'         => _t('Item'),
            'permission'    => array(
                'resource'  => 'item',
            ),
            'route'         => 'admin',
            'module'        => 'guide',
            'controller'    => 'item',
            'action'        => 'index',
        ),

        'category' => array(
            'label'         => _t('Category'),
            'permission'    => array(
                'resource'  => 'category',
            ),
            'route'         => 'admin',
            'module'        => 'guide',
            'controller'    => 'category',
            'action'        => 'index',
        ),

        'location' => array(
            'label'         => _t('Location'),
            'permission'    => array(
                'resource'  => 'location',
            ),
            'route'         => 'admin',
            'module'        => 'guide',
            'controller'    => 'location',
            'action'        => 'index',
        ),

        'service' => array(
            'label'         => _t('Service category'),
            'permission'    => array(
                'resource'  => 'service',
            ),
            'route'         => 'admin',
            'module'        => 'guide',
            'controller'    => 'service',
            'action'        => 'index',
        ),

        'extra' => array(
            'label'         => _t('Extra'),
            'permission'    => array(
                'resource'  => 'extra',
            ),
            'route'         => 'admin',
            'module'        => 'guide',
            'controller'    => 'extra',
            'action'        => 'index',
        ),

        'special' => array(
            'label'         => _t('Special'),
            'permission'    => array(
                'resource'  => 'special',
            ),
            'route'         => 'admin',
            'module'        => 'guide',
            'controller'    => 'special',
            'action'        => 'index',
        ),

        'review' => array(
            'label'         => _t('Review'),
            'permission'    => array(
                'resource'  => 'review',
            ),
            'route'         => 'admin',
            'module'        => 'guide',
            'controller'    => 'review',
            'action'        => 'index',
        ),

        'attach' => array(
            'label'         => _t('Attach'),
            'permission'    => array(
                'resource'  => 'attach',
            ),
            'route'         => 'admin',
            'module'        => 'guide',
            'controller'    => 'attach',
            'action'        => 'index',
        ),

        'log' => array(
            'label'         => _t('Logs'),
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