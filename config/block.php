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
    'item-new'    => array(
        'name'          => 'item-new',
        'title'         => _b('New Items'),
        'description'   => _b('New Items list'),
        'render'        => array('block', 'itemNew'),
        'template'      => 'item_new',
        'config'        => array(
            'number' => array(
                'title' => _b('Number'),
                'description' => '',
                'edit' => 'text',
                'filter' => 'number_int',
                'value' => 10,
            ),
            'recommended' => array(
                'title' => _b('Show just recommended'),
                'description' => '',
                'edit' => 'checkbox',
                'filter' => 'number_int',
                'value' => 1,
            ),
            'more-show' => array(
                'title' => _b('Show More link to module page'),
                'description' => '',
                'edit' => 'checkbox',
                'filter' => 'number_int',
                'value' => 0,
            ),
            'more-link' => array(
                'title' => _b('Set More link to module page'),
                'description' => '',
                'edit' => 'text',
                'filter' => 'string',
                'value' => '',
            ),
        ),
    ),
    'item-random'    => array(
        'name'          => 'item-random',
        'title'         => _b('Random Item'),
        'description'   => _b('Random Item list'),
        'render'        => array('block', 'itemRandom'),
        'template'      => 'item_random',
        'config'        => array(
            'number' => array(
                'title' => _b('Number'),
                'description' => '',
                'edit' => 'text',
                'filter' => 'number_int',
                'value' => 10,
            ),
            'recommended' => array(
                'title' => _b('Show just recommended'),
                'description' => '',
                'edit' => 'checkbox',
                'filter' => 'number_int',
                'value' => 1,
            ),
            'more-show' => array(
                'title' => _b('Show More link to module page'),
                'description' => '',
                'edit' => 'checkbox',
                'filter' => 'number_int',
                'value' => 0,
            ),
            'more-link' => array(
                'title' => _b('Set More link to module page'),
                'description' => '',
                'edit' => 'text',
                'filter' => 'string',
                'value' => '',
            ),
        ),
    ),
    'category'    => array(
        'name'          => 'category',
        'title'         => _b('Category'),
        'description'   => _b('Category list'),
        'render'        => array('block', 'category'),
        'template'      => 'category',
        'config'        => array(
            'type' => array(
                'title' => _b('Category show type'),
                'description' => '',
                'edit' => array(
                    'type' => 'select',
                    'options' => array(
                        'options' => array(
                            'simple' => _b('Simple list'),
                            'advanced' => _b('Advanced list'),
                        ),
                    ),
                ),
                'filter' => 'text',
                'value' => 'simple',
            ),
        ),
    ),
    'search'    => array(
        'name'          => 'search',
        'title'         => _b('Search'),
        'description'   => _b('Item search form'),
        'render'        => array('block', 'search'),
        'template'      => 'search',
    ),
);