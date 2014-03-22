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