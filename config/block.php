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
        'title'         => _a('New Items'),
        'description'   => _a('New Items list'),
        'render'        => array('block', 'itemNew'),
        'template'      => 'item_new',
        'config'        => array(
            'number' => array(
                'title' => _a('Number'),
                'description' => '',
                'edit' => 'text',
                'filter' => 'number_int',
                'value' => 10,
            ),
            'recommended' => array(
                'title' => _a('Show just recommended'),
                'description' => '',
                'edit' => 'checkbox',
                'filter' => 'number_int',
                'value' => 1,
            ),
            'more-show' => array(
                'title' => _a('Show More link to module page'),
                'description' => '',
                'edit' => 'checkbox',
                'filter' => 'number_int',
                'value' => 0,
            ),
            'more-link' => array(
                'title' => _a('Set More link to module page'),
                'description' => '',
                'edit' => 'text',
                'filter' => 'string',
                'value' => '',
            ),
        ),
    ),
    'item-random'    => array(
        'name'          => 'item-random',
        'title'         => _a('Random Item'),
        'description'   => _a('Random Item list'),
        'render'        => array('block', 'itemRandom'),
        'template'      => 'item_random',
        'config'        => array(
            'number' => array(
                'title' => _a('Number'),
                'description' => '',
                'edit' => 'text',
                'filter' => 'number_int',
                'value' => 10,
            ),
            'recommended' => array(
                'title' => _a('Show just recommended'),
                'description' => '',
                'edit' => 'checkbox',
                'filter' => 'number_int',
                'value' => 1,
            ),
            'more-show' => array(
                'title' => _a('Show More link to module page'),
                'description' => '',
                'edit' => 'checkbox',
                'filter' => 'number_int',
                'value' => 0,
            ),
            'more-link' => array(
                'title' => _a('Set More link to module page'),
                'description' => '',
                'edit' => 'text',
                'filter' => 'string',
                'value' => '',
            ),
        ),
    ),
    'category'    => array(
        'name'          => 'category',
        'title'         => _a('Category'),
        'description'   => _a('Category list'),
        'render'        => array('block', 'category'),
        'template'      => 'category',
        'config'        => array(
            'type' => array(
                'title' => _a('Category show type'),
                'description' => '',
                'edit' => array(
                    'type' => 'select',
                    'options' => array(
                        'options' => array(
                            'simple' => _a('Simple list'),
                            'advanced' => _a('Advanced list'),
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
        'title'         => _a('Search'),
        'description'   => _a('Item search form'),
        'render'        => array('block', 'search'),
        'template'      => 'search',
    ),
);