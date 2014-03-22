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
    'guild'  => array(
        'name'      => 'guild',
        'type'      => 'Module\Guild\Route\Guild',
        'options'   => array(
            'route'     => '/guild',
            'defaults'  => array(
                'module'        => 'guild',
                'controller'    => 'index',
                'action'        => 'index'
            )
        ),
    )
);