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
    // Front section
    'front' => array(
        'public'         => array(
            'title'         => _a('Global public resource'),
            'access'        => array(
                'guest',
                'member',
            ),
        ),
        'manage'         => array(
            'title'         => _a('Manage'),
            'access'        => array(
                'member',
            ),
        ),
    ),
    // Admin section
    'admin' => array(
        'item'           => array(
            'title'         => _a('Item'),
            'access'        => array(
                //'admin',
            ),
        ),
        'category'       => array(
            'title'         => _a('Category'),
            'access'        => array(
                //'admin',
            ),
        ),
        'location'       => array(
            'title'         => _a('Location'),
            'access'        => array(
                //'admin',
            ),
        ),
        'service'        => array(
            'title'         => _a('Service'),
            'access'        => array(
                //'admin',
            ),
        ),
        'extra'          => array(
            'title'         => _a('Extra'),
            'access'        => array(
                //'admin',
            ),
        ),
        'special'        => array(
            'title'         => _a('Special'),
            'access'        => array(
                //'admin',
            ),
        ),
        'review'         => array(
            'title'         => _a('Review'),
            'access'        => array(
                //'admin',
            ),
        ),
        'attach'         => array(
            'title'         => _a('Attach'),
            'access'        => array(
                //'admin',
            ),
        ),
        'score'          => array(
            'title'         => _a('Score'),
            'access'        => array(
                //'admin',
            ),
        ),
        'package'        => array(
            'title'         => _a('Package'),
            'access'        => array(
                //'admin',
            ),
        ),
        'order'          => array(
            'title'         => _a('Orders'),
            'access'        => array(
                //'admin',
            ),
        ),
        'log'            => array(
            'title'         => _a('Logs'),
            'access'        => array(
                //'admin',
            ),
        ),
    ),
);