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
namespace Module\Guide\Route;

use Pi\Mvc\Router\Http\Standard;

class Guide extends Standard
{
    /**
     * Default values.
     * @var array
     */
    protected $defaults = array(
        'module'        => 'guide',
        'controller'    => 'index',
        'action'        => 'index'
    );

    protected $controllerList = array(
        'category', 'index', 'item', 'json', 'manage', 'package', 'search', 'tag'
    );

    /**
     * {@inheritDoc}
     */
    protected $structureDelimiter = '/';

    /**
     * {@inheritDoc}
     */
    protected function parse($path)
    {
        $matches = array();
        $parts = array_filter(explode($this->structureDelimiter, $path));

        // Set controller
        $matches = array_merge($this->defaults, $matches);
        if (isset($parts[0]) && in_array($parts[0], $this->controllerList)) {
            $matches['controller'] = $this->decode($parts[0]);
        }

        // Make Match
        if (isset($matches['controller']) && !empty($parts[1])) {
            switch ($matches['controller']) {
                // category controller
                case 'category':
                    if ($parts[1] == 'list') {
                        $matches['action'] = 'list';
                    } else {
                        $matches['slug'] = $this->decode($parts[1]);
                        // Set sort
                        if (isset($parts[2]) && $parts[2] == 'sort') {
                            $matches['sort'] = $this->decode($parts[3]);
                        }
                    }
                    break;

                // index controller
                case 'index':
                    if ($parts[1] == 'sort') {
                        $matches['sort'] = $this->decode($parts[2]);
                    }
                    break;

                // item controller
                case 'item':
                    if ($parts[1] == 'print') {
                        $matches['action'] = 'print';
                        $matches['slug'] = $this->decode($parts[2]);
                    } elseif($parts[1] == 'review') {
                        $matches['action'] = 'review';
                        $matches['slug'] = $this->decode($parts[2]);
                    } elseif($parts[1] == 'addReview') {
                        $matches['action'] = 'addReview';
                        $matches['slug'] = $this->decode($parts[2]);
                    } elseif($parts[1] == 'service') {
                        $matches['action'] = 'service';
                        $matches['slug'] = $this->decode($parts[2]);
                    } elseif($parts[1] == 'blog') {
                        $matches['action'] = 'blog';
                        $matches['slug'] = $this->decode($parts[2]);
                    } elseif($parts[1] == 'story') {
                        $matches['action'] = 'story';
                        $matches['slug'] = $this->decode($parts[2]);
                    } else {
                        $matches['slug'] = $this->decode($parts[1]);
                    }
                    break;

                // json controller
                case 'json':

                    break;

                // manage controller
                case 'manage':
                    $matches['action'] = $this->decode($parts[1]);
                    if (isset($parts[2]) && $parts[2] == 'level') {
                        $matches['level'] = intval($parts[3]);
                    }
                    if (isset($parts[4]) && $parts[4] == 'parent') {
                        $matches['parent'] = intval($parts[5]);
                    }
                    break;

                // package controller
                case 'package':

                    break;

                // search controller
                case 'search':
                    if ($parts[1] == 'result') {
                        $matches['action'] = 'result';
                        if (isset($parts[2]) && $parts[2] == 'sort') {
                            $matches['sort'] = $this->decode($parts[3]);
                        }
                    } elseif ($parts[1] == 'ajax') {
                        $matches['action'] = 'ajax';
                        $matches['level'] = $parts[3];
                        $matches['parent'] = $parts[5];
                    } elseif ($parts[1] == 'block') {
                        $matches['action'] = 'block';
                    }    
                    break;

                // tag controller
                case 'tag':
                    if ($parts[1] == 'term') {
                        $matches['action'] = 'term';
                        $matches['slug'] = urldecode($parts[2]);
                        if (isset($parts[3]) && $parts[3] == 'sort') {
                            $matches['sort'] = $this->decode($parts[4]);
                        }
                    } elseif ($parts[1] == 'list') {
                        $matches['action'] = 'list';
                    }
                    break;
            }    
        } 
        return $matches;
    }

    /**
     * assemble(): Defined by Route interface.
     *
     * @see    Route::assemble()
     * @param  array $params
     * @param  array $options
     * @return string
     */
    public function assemble(
        array $params = array(),
        array $options = array()
    ) {
        $mergedParams = array_merge($this->defaults, $params);
        if (!$mergedParams) {
            return $this->prefix;
        }
        
        // Set module
        if (!empty($mergedParams['module'])) {
            $url['module'] = $mergedParams['module'];
        }

        // Set controller
        if (!empty($mergedParams['controller']) 
                && $mergedParams['controller'] != 'index'
                && in_array($mergedParams['controller'], $this->controllerList)) 
        {
            $url['controller'] = $mergedParams['controller'];
        }

        // Set action
        if (!empty($mergedParams['action']) 
                && $mergedParams['action'] != 'index') 
        {
            $url['action'] = $mergedParams['action'];
        }
        
        // Set slug
        if (!empty($mergedParams['slug'])) {
            $url['slug'] = $mergedParams['slug'];
        }

        // Set level
        if (!empty($mergedParams['level'])) {
            $url['level'] = $mergedParams['level'];
        }

        // Set parent
        if (!empty($mergedParams['parent'])) {
            $url['parent'] = $mergedParams['parent'];
        }

        // Set id
        if (!empty($mergedParams['id'])) {
            $url['id'] = $mergedParams['id'];
        }

        // Make url
        $url = implode($this->paramDelimiter, $url);

        if (empty($url)) {
            return $this->prefix;
        }
        return $this->paramDelimiter . $url;
    }
}
