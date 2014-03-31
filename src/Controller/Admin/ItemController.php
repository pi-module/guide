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
namespace Module\Guide\Controller\Admin;

use Pi;
use Pi\Mvc\Controller\ActionController;
use Pi\Paginator\Paginator;
use Pi\File\Transfer\Upload;
use Module\Guide\Form\ItemForm;
use Module\Guide\Form\ItemFilter;
use Zend\Json\Json;

class ItemController extends ActionController
{
    /**
     * Item Image Prefix
     */
    protected $ImageItemPrefix = 'item_';

    /**
     * Item Columns
     */
    protected $itemColumns = array(
    	'id', 'title', 'slug', 'category', 'summary', 'description', 'seo_title', 'seo_keywords', 'seo_description', 
    	'status', 'time_create', 'time_update', 'time_start', 'time_end', 'uid', 'hits', 'image', 'path', 'point', 
    	'count', 'favourite', 'service', 'attach', 'extra', 'review', 'recommended', 'map_longitude', 'map_latitude',
    	'location', 'location_category', 'blog'
    );

    public function indexAction()
    {
        // Get page
        $page = $this->params('page', 1);
        $module = $this->params('module');
        $status = $this->params('status');
        $category = $this->params('category');
        // Set info
        $offset = (int)($page - 1) * $this->config('admin_perpage');
        $order = array('time_create DESC', 'id DESC');
        $limit = intval($this->config('admin_perpage'));
        $list = array();
        // Set where
        $whereLink = array();
        if (!empty($status)) {
            $whereLink['status'] = $status;
        }
        if (!empty($category)) {
            $whereLink['category'] = $category;
        }
        $columnsLink = array('item' => new \Zend\Db\Sql\Predicate\Expression('DISTINCT item'));
        // Get info from link table
        $select = $this->getModel('link')->select()->where($whereLink)->columns($columnsLink)->order($order)->offset($offset)->limit($limit);
        $rowset = $this->getModel('link')->selectWith($select)->toArray();
        // Make list
        foreach ($rowset as $id) {
            $itemId[] = $id['item'];
        }
        // Set info
        $columnItem = array('id', 'title', 'slug', 'status', 'time_create', 'recommended');
        $whereItem = array('id' => $itemId);
        // Get list of item
        $select = $this->getModel('item')->select()->columns($columnItem)->where($whereItem)->order($order);
        $rowset = $this->getModel('item')->selectWith($select);
        // Make list
        foreach ($rowset as $row) {
            $item[$row->id] = $row->toArray();
            $item[$row->id]['time_create_view'] = _date($item[$row->id]['time_create']);
            $item[$row->id]['time_update_view'] = _date($item[$row->id]['time_update']);
            $item[$row->id]['time_start_view'] = _date($item[$row->id]['time_start']);
            $item[$row->id]['time_end_view'] = _date($item[$row->id]['time_end']);
            $item[$row->id]['itemUrl'] = $this->url('guide', array(
                'module'        => $module,
                'controller'    => 'item',
                'slug'          => $item[$row->id]['slug'],
            ));
        }
        // Go to update page if empty
        if (empty($item)) {
            return $this->redirect()->toRoute('', array('action' => 'update'));
        }
        // Set paginator
        $countLink = array('count' => new \Zend\Db\Sql\Predicate\Expression('count(DISTINCT `item`)'));
        $select = $this->getModel('link')->select()->where($whereLink)->columns($countLink);
        $count = $this->getModel('link')->selectWith($select)->current()->count;
        $paginator = Paginator::factory(intval($count));
        $paginator->setItemCountPerPage($this->config('admin_perpage'));
        $paginator->setCurrentPageNumber($page);
        $paginator->setUrlOptions(array(
            'router'    => $this->getEvent()->getRouter(),
            'route'     => $this->getEvent()->getRouteMatch()->getMatchedRouteName(),
            'params'    => array_filter(array(
                'module'        => $this->getModule(),
                'controller'    => 'item',
                'action'        => 'index',
                'category'      => $category,
                'status'        => $status,
            )),
        ));
        // Set view
        $this->view()->setTemplate('item_index');
        $this->view()->assign('list', $item);
        $this->view()->assign('paginator', $paginator);
    }

    /**
     * update Action
     */
    public function updateAction()
    {
        // check category
        $categoryCount = Pi::api('category', 'guide')->categoryCount();
        if (!$categoryCount) {
            return $this->redirect()->toRoute('', array(
                'controller' => 'category',
                'action' => 'update'
            ));
        }
        // Get id
        $id = $this->params('id');
        $module = $this->params('module');
        $option = array();
        // Find item
        if ($id) {
            $item = $this->getModel('item')->find($id)->toArray();
            $item['category'] = Json::decode($item['category']);
            if ($item['image']) {
                $thumbUrl = sprintf('upload/%s/thumb/%s/%s', $this->config('image_path'), $item['path'], $item['image']);
                $option['thumbUrl'] = Pi::url($thumbUrl);
                $option['removeUrl'] = $this->url('', array('action' => 'remove', 'id' => $item['id']));
            }
        }
        // Get extra field
        $fields = Pi::api('extra', 'guide')->Get();
        $option['field'] = $fields['extra'];
        // Get location
        $option['location'] = Pi::api('location', 'guide')->locationForm();
        // Set form
        $form = new ItemForm('item', $option);
        $form->setAttribute('enctype', 'multipart/form-data');
        if ($this->request->isPost()) {
        	$data = $this->request->getPost();
            $file = $this->request->getFiles();
            // Set slug
            $slug = ($data['slug']) ? $data['slug'] : $data['title'];
            $data['slug'] = Pi::api('text', 'guide')->slug($slug);
            // Form filter
            $form->setInputFilter(new ItemFilter($option));
            $form->setData($data);
            if ($form->isValid()) {
            	$values = $form->getData();
                // Set extra data array
                if (!empty($fields['field'])) {
                    foreach ($fields['field'] as $field) {
                        $extra[$field]['field'] = $field;
                        $extra[$field]['data'] = $values[$field];
                    }
                }
                // Set location
                if (!empty($option['location'])) {
                    foreach ($option['location'] as $location) {
                        $element = sprintf('location-%s', $location['id']);
                        $element = $values[$element];
                        if (isset($element) && !empty($element)) {
                            $values['location'] = $element;
                            $values['location_category'] = $location['id'];
                        }
                    }
                }
                // Tag
                if (!empty($values['tag'])) {
                    $tag = explode('|', $values['tag']);
                }
                // upload image
                if (!empty($file['image']['name'])) {
                    // Set upload path
                    $values['path'] = sprintf('%s/%s', date('Y'), date('m'));
                    $originalPath = Pi::path(sprintf('upload/%s/original/%s', $this->config('image_path'), $values['path']));
                    // Upload
                    $uploader = new Upload;
                    $uploader->setDestination($originalPath);
                    $uploader->setRename($this->ImageItemPrefix . '%random%');
                    $uploader->setExtension($this->config('image_extension'));
                    $uploader->setSize($this->config('image_size'));
                    if ($uploader->isValid()) {
                        $uploader->receive();
                        // Get image name
                        $values['image'] = $uploader->getUploaded('image');
                        // process image
                        Pi::api('image', 'guide')->process($values['image'], $values['path']);
                    } else {
                        $this->jump(array('action' => 'update'), __('Problem in upload image. please try again'));
                    }
                } elseif (!isset($values['image'])) {
                    $values['image'] = '';  
                }
            	// Set just item fields
            	foreach (array_keys($values) as $key) {
                    if (!in_array($key, $this->itemColumns)) {
                        unset($values[$key]);
                    }
                }
                // Category
                $values['category'] = Json::encode(array_unique($values['category']));
                // Set seo_title
                $title = ($values['seo_title']) ? $values['seo_title'] : $values['title'];
                $values['seo_title'] = Pi::api('text', 'guide')->title($title);
                // Set seo_keywords
                $keywords = ($values['seo_keywords']) ? $values['seo_keywords'] : $values['title'];
                $values['seo_keywords'] = Pi::api('text', 'guide')->keywords($keywords);
                // Set seo_description
                $description = ($values['seo_description']) ? $values['seo_description'] : $values['title'];
                $values['seo_description'] = Pi::api('text', 'guide')->description($description);
                // Set time
                if (empty($values['id'])) {
                    $values['time_create'] = time();
                    $values['uid'] = Pi::user()->getId();
                }
                $values['time_update'] = time();
                $values['time_start'] = strtotime($values['time_start']);
                $values['time_end'] = strtotime($values['time_end']);
                // Save values
                if (!empty($values['id'])) {
                    $row = $this->getModel('item')->find($values['id']);
                } else {
                    $row = $this->getModel('item')->createRow();
                }
                $row->assign($values);
                $row->save();
                // Category
                Pi::api('category', 'guide')->setLink($row->id, $row->category, $row->time_create, $row->time_update, $row->time_start, $row->time_end, $row->status);
                // Tag
                if (isset($tag) && is_array($tag) && Pi::service('module')->isActive('tag')) {
                    if (empty($values['id'])) {
                        Pi::service('tag')->add($module, $row->id, '', $tag);
                    } else {
                        Pi::service('tag')->update($module, $row->id, '', $tag);
                    }
                }
                // Extra
                if (!empty($extra)) {
                    Pi::api('extra', 'guide')->Set($extra, $row->id);
                }
                // Add / Edit sitemap
                if (Pi::service('module')->isActive('sitemap')) {
                    $loc = Pi::url($this->url('guide', array(
                        'module' => $module, 
                        'controller' => 'item', 
                        'slug' => $values['slug']
                    )));
                    if (empty($values['id'])) {
                        Pi::api('sitemap', 'sitemap')->add('guide', 'item', $row->id, $loc);
                    } else {
                        Pi::api('sitemap', 'sitemap')->update('guide', 'item', $row->id, $loc);
                    }              
                }
                // Add log
                $operation = (empty($values['id'])) ? 'add' : 'edit';
                Pi::api('log', 'guide')->addLog('item', $row->id, $operation);
                // Check it save or not
                if ($row->id) {
                    $message = __('Item data saved successfully.');
                    $this->jump(array('action' => 'index'), $message);
                }
            }	
        } else {
            if ($id) {
                // Get Extra
                $item = Pi::api('extra', 'guide')->Form($item);
                // Get tag list
                if (Pi::service('module')->isActive('tag')) {
                    $tag = Pi::service('tag')->get($module, $item['id'], '');
                    if (is_array($tag)) {
                        $item['tag'] = implode('|', $tag);
                    }
                }
                // Set time
                $item['time_start'] = date('Y-m-d', $item['time_start']);
                $item['time_end'] = date('Y-m-d', $item['time_end']);
                // Set data 
                $form->setData($item);
            }
        }
        // Set view
        $this->view()->setTemplate('item_update');
        $this->view()->assign('form', $form);
        $this->view()->assign('locationCategory', $option['location']);
        $this->view()->assign('title', __('Add item'));
    }

    public function deleteAction()
    {
        $this->view()->setTemplate(false);
        $id = $this->params('id');
        $module = $this->params('module');
        $row = $this->getModel('item')->find($id);
        if ($row) {
            // Link
            $this->getModel('link')->delete(array('item' => $row->id));
            // Attach
            $this->getModel('attach')->delete(array('item' => $row->id));
            // Extra Field Data
            $this->getModel('field_data')->delete(array('item' => $row->id));
            // Special
            $this->getModel('special')->delete(array('item' => $row->id));
            // Review
            $this->getModel('review')->delete(array('item' => $row->id));
            // Remove sitemap
            if (Pi::service('module')->isActive('sitemap')) {
                $loc = Pi::url($this->url('guide', array(
                        'module'      => $module, 
                        'controller'  => 'item', 
                        'slug'        => $row->slug
                    )));
                Pi::api('sitemap', 'sitemap')->remove($loc);
            }
            // Add log
            Pi::api('log', 'guide')->addLog('item', $row->id, 'delete');
            // Remove page
            $row->delete();
            $this->jump(array('action' => 'index'), __('This item deleted'));
        } else {
            $this->jump(array('action' => 'index'), __('Please select item'));
        }
    }

    public function recommendAction()
    {
        // Get id and recommended
        $id = $this->params('id');
        $recommended = $this->params('recommended');
        $return = array();
        // set item
        $item = $this->getModel('item')->find($id);
        // Check
        if ($item && in_array($recommended, array(0, 1))) {
            // Accept
            $item->recommended = $recommended;
            // Save
            if ($item->save()) {
                $return['message'] = sprintf(__('%s set recommended successfully'), $item->title);
                $return['ajaxstatus'] = 1;
                $return['id'] = $item->id;
                $return['recommended'] = $item->recommended;
                // Add log
                Pi::api('log', 'guide')->addLog('item', $item->id, 'recommend');
            } else {
                $return['message'] = sprintf(__('Error in set recommended for %s item'), $item->title);
                $return['ajaxstatus'] = 0;
                $return['id'] = 0;
                $return['recommended'] = $item->recommended;
            }
        } else {
            $return['message'] = __('Please select item');
            $return['ajaxstatus'] = 0;
            $return['id'] = 0;
            $return['recommended'] = 0;
        }
        return $return;
    }

    public function removeAction()
    {
        // Get id and status
        $id = $this->params('id');
        // set item
        $item = $this->getModel('item')->find($id);
        // Check
        if ($item && !empty($id)) {
            // remove file
            /* $files = array(
                Pi::path(sprintf('upload/%s/original/%s/%s', $this->config('image_path'), $item->path, $item->image)),
                Pi::path(sprintf('upload/%s/large/%s/%s', $this->config('image_path'), $item->path, $item->image)),
                Pi::path(sprintf('upload/%s/medium/%s/%s', $this->config('image_path'), $item->path, $item->image)),
                Pi::path(sprintf('upload/%s/thumb/%s/%s', $this->config('image_path'), $item->path, $item->image)),
            );
            Pi::service('file')->remove($files); */
            // clear DB
            $item->image = '';
            $item->path = '';
            // Save
            if ($item->save()) {
                $message = sprintf(__('Image of %s removed'), $item->title);
                $status = 1;
            } else {
                $message = __('Image not remove');
                $status = 0;
            }
        } else {
            $message = __('Please select item');
            $status = 0;
        }
        return array(
            'status' => $status,
            'message' => $message,
        );
    }
}