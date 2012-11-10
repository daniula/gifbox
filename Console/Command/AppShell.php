<?php
/**
 * AppShell file
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         CakePHP(tm) v 2.0
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('Shell', 'Console');
App::uses('Component', 'Controller');
App::uses('ComponentCollection', 'Controller');

/**
 * Application Shell
 *
 * Add your application-wide methods in the class below, your shells
 * will inherit them.
 *
 * @package       app.Console.Command
 */
class AppShell extends Shell {

  private function _loadComponents() {
    $this->Components = new ComponentCollection();
    if (empty($this->components)) {
      return;
    }

    if (array_search(null, $this->components)) {
      App::uses('AppController', 'Controller');
      $controller = new AppController();
      foreach ($this->components as $name => &$settings) {
        if (!empty($controller->components[$name]) && is_null($settings)) {
          $settings = $controller->components[$name];
        }
      }
    }

    $components = ComponentCollection::normalizeObjectArray($this->components);
    foreach ($components as $name => $properties) {
      $this->{$name} = $this->Components->load($properties['class'], $properties['settings']);
    }
  }

  public function initialize() {
    parent::initialize();

    $this->_loadComponents();
  }
}
