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
    // route name
    'guide'  => array(
        'name'      => 'guide',
        'type'      => 'Module\Guide\Route\Guide',
        'options'   => array(
            'route'     => '/guide',
            'defaults'  => array(
                'module'        => 'guide',
                'controller'    => 'index',
                'action'        => 'index'
            )
        ),
    )
);