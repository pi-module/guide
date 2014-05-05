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
        'title'         => _a('New Items'),
        'description'   => _a('New Items list'),
        'render'        => 'block::itemNew',
        'template'      => 'item_new',
        'config'        => array(
            'number'    => array(
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
                'value' => 0,
            ),
            'showtitle' => array(
                'title' => _a('Show title'),
                'description' => '',
                'edit' => 'checkbox',
                'filter' => 'number_int',
                'value' => 1,
            ),
            'showlocation' => array(
                'title' => _a('Show location'),
                'description' => '',
                'edit' => 'checkbox',
                'filter' => 'number_int',
                'value' => 1,
            ),
            'showphone' => array(
                'title' => _a('Show phone'),
                'description' => '',
                'edit' => 'checkbox',
                'filter' => 'number_int',
                'value' => 1,
            ),
            'showtype' => array(
                'title' => _a('Show type activity'),
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
        'title'         => _a('Random Item'),
        'description'   => _a('Random Item list'),
        'render'        => 'block::itemRandom',
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
                'value' => 0,
            ),
            'showtitle' => array(
                'title' => _a('Show title'),
                'description' => '',
                'edit' => 'checkbox',
                'filter' => 'number_int',
                'value' => 1,
            ),
            'showlocation' => array(
                'title' => _a('Show location'),
                'description' => '',
                'edit' => 'checkbox',
                'filter' => 'number_int',
                'value' => 1,
            ),
            'showphone' => array(
                'title' => _a('Show phone'),
                'description' => '',
                'edit' => 'checkbox',
                'filter' => 'number_int',
                'value' => 1,
            ),
            'showtype' => array(
                'title' => _a('Show type activity'),
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
        'title'         => _a('Category'),
        'description'   => _a('Category list'),
        'render'        => 'block::category',
        'template'      => 'category',
        'config'        => array(
            'type' => array(
                'title' => _a('Category list type'),
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
        'title'         => _a('Search'),
        'description'   => _a('Item search form'),
        'render'        => 'block::search',
        'template'      => 'search',
        'config'        => array(
            'type' => array(
                'title' => _a('Search form type'),
                'description' => '',
                'edit' => array(
                    'type' => 'select',
                    'options' => array(
                        'options' => array(
                            'form-horizontal' => _a('Horizontal'),
                            'form-inline' => _a('Inline'),
                        ),
                    ),
                ),
                'filter' => 'text',
                'value' => 'form-inline',
            ),
            'descriptionform' => array(
                'title' => _a('Form description'),
                'description' => '',
                'edit' => 'text',
                'filter' => 'string',
                'value' => '',
            ),
            'descriptiontitle' => array(
                'title' => _a('Title feild description'),
                'description' => '',
                'edit' => 'text',
                'filter' => 'string',
                'value' => _a('Title'),
            ),
            'descriptionlocation' => array(
                'title' => _a('Location feild description'),
                'description' => '',
                'edit' => 'text',
                'filter' => 'string',
                'value' => _a('Location'),
            ),
            'descriptionservice' => array(
                'title' => _a('Service feild description'),
                'description' => '',
                'edit' => 'text',
                'filter' => 'string',
                'value' => _a('Service'),
            ),
        ),
    ),
);