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
use Pi\File\Transfer\Upload;
use Module\Guide\Form\PackageForm;
use Module\Guide\Form\PackageFilter;
use Zend\Json\Json;

class PackageController extends ActionController
{
    /**
     * Package Image Prefix
     */
    protected $ImagePackagePrefix = 'package_';

    /**
     * Package Columns
     */
    protected $packageColumns = array(
    	'id', 'title', 'description', 'features', 'status', 'time_create', 'time_update', 'time_period', 
    	'image', 'path', 'price', 'stock_all', 'stock_sold', 'stock_remained'
    );

    public function indexAction()
    {
        // Get page
        $module = $this->params('module');
        // Get info
        $list = array();
        $order = array('id DESC', 'time_create DESC');
        $select = $this->getModel('package')->select()->order($order);
        $rowset = $this->getModel('package')->selectWith($select);
        // Make list
        foreach ($rowset as $row) {
            $list[$row->id] = $row->toArray();
        }
        // Go to update page if empty
        if (empty($list)) {
            return $this->redirect()->toRoute('', array('action' => 'update'));
        }
        // Set view
        $this->view()->setTemplate('package_index');
        $this->view()->assign('list', $list);
    }

    public function updateAction()
    {
        // Get id
        $id = $this->params('id');
        $module = $this->params('module');
        $option = array();
        // Find package
        if ($id) {
            $package = $this->getModel('package')->find($id)->toArray();
            // Set image
            if ($package['image']) {
                $thumbUrl = sprintf('upload/%s/thumb/%s/%s', $this->config('image_path'), $package['path'], $package['image']);
                $option['thumbUrl'] = Pi::url($thumbUrl);
                $option['removeUrl'] = $this->url('', array('action' => 'remove', 'id' => $package['id']));
            }
        }
        // Set features array
        $option['features'] = array();
        for ($i=1; $i < 11; $i++) { 
        	$option['features'][$i]['name'] = 'name' . $i;
        	$option['features'][$i]['name_title'] = __('Name ') . $i;
        	$option['features'][$i]['value'] = 'value' . $i;
        	$option['features'][$i]['value_title'] = __('Value ') . $i;
        }
        // Set form
        $form = new PackageForm('package', $option);
        $form->setAttribute('enctype', 'multipart/form-data');
        if ($this->request->isPost()) {
        	$data = $this->request->getPost();
            $file = $this->request->getFiles();
            $form->setInputFilter(new PackageFilter($option));
            $form->setData($data);
            if ($form->isValid()) {
            	$values = $form->getData();
            	// Set features
            	$values['features'] = array();
                if (!empty($option['features'])) {
                    foreach ($option['features'] as $key => $feature) {
                    	$values['features'][$key]['name'] = $values[$feature['name']];
                    	$values['features'][$key]['value'] = $values[$feature['value']];
                    }
                }
            	$values['features'] = json::encode($values['features']);
            	// Set just service_category fields
            	foreach (array_keys($values) as $key) {
                    if (!in_array($key, $this->packageColumns)) {
                        unset($values[$key]);
                    }
                }
                // upload image
                if (!empty($file['image']['name'])) {
                    // Set upload path
                    $values['path'] = sprintf('%s/%s', date('Y'), date('m'));
                    $originalPath = Pi::path(sprintf('upload/%s/original/%s', $this->config('image_path'), $values['path']));
                    // Upload
                    $uploader = new Upload;
                    $uploader->setDestination($originalPath);
                    $uploader->setRename($this->ImagePackagePrefix . '%random%');
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
                // Set time
                if (empty($values['id'])) {
                    $values['time_create'] = time();
                }
                $values['time_update'] = time();
                // Save values
                if (!empty($values['id'])) {
                    $row = $this->getModel('package')->find($values['id']);
                } else {
                    $row = $this->getModel('package')->createRow();
                }
                $row->assign($values);
                $row->save();
                // Add log
                $operation = (empty($values['id'])) ? 'add' : 'edit';
                Pi::api('log', 'guide')->addLog('package', $row->id, $operation);
                $message = __('Package data saved successfully.');
                $url = array('action' => 'index');
                $this->jump($url, $message);
            } else {
                $message = __('Invalid data, please check and re-submit.');
            }	
        } else {
            if ($id) {
            	$features = json::decode($package['features'], true);
            	$features = (array)$features;
            	if (!empty($option['features']) && !empty($features)) {
                    foreach ($option['features'] as $key => $option) {
                    	$package[$option['name']] = $features[$key]['name'];
                    	$package[$option['value']] = $features[$key]['value'];
                    }
                }
                $form->setData($package);
            }
        }
        // Set view
        $this->view()->setTemplate('package_update');
        $this->view()->assign('form', $form);
        $this->view()->assign('title', __('Add package'));
    }

    public function removeAction()
    {
        // Get id and status
        $id = $this->params('id');
        // set package
        $package = $this->getModel('package')->find($id);
        // Check
        if ($package && !empty($id)) {
            // remove file
            /* $files = array(
                Pi::path(sprintf('upload/%s/original/%s/%s', $this->config('image_path'), $package->path, $package->image)),
                Pi::path(sprintf('upload/%s/large/%s/%s', $this->config('image_path'), $package->path, $package->image)),
                Pi::path(sprintf('upload/%s/medium/%s/%s', $this->config('image_path'), $package->path, $package->image)),
                Pi::path(sprintf('upload/%s/thumb/%s/%s', $this->config('image_path'), $package->path, $package->image)),
            );
            Pi::service('file')->remove($files); */
            // clear DB
            $package->image = '';
            $package->path = '';
            // Save
            if ($package->save()) {
                $message = sprintf(__('Image of %s removed'), $package->title);
                $status = 1;
            } else {
                $message = __('Image not remove');
                $status = 0;
            }
        } else {
            $message = __('Please select package');
            $status = 0;
        }
        return array(
            'status' => $status,
            'message' => $message,
        );
    }
}