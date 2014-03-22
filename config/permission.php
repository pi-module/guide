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
        'public'    => array(
            'title'         => _t('Global public resource'),
            'access'        => array(
                'guest',
                'member',
            ),
        ),
    ),
    // Admin section
    'admin' => array(
        'item'        => array(
            'title'         => __('Item'),
            'access'        => array(
                //'admin',
            ),
        ),
        'category'      => array(
            'title'         => __('Category'),
            'access'        => array(
                //'admin',
            ),
        ),
        'location'         => array(
            'title'         => __('Location'),
            'access'        => array(
                //'admin',
            ),
        ),
        'log'  => array(
            'title'         => __('Logs'),
            'access'        => array(
                //'admin',
            ),
        ),
    ),
);