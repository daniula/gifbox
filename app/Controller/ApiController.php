<?php

class ApiController extends AppController {
  public $viewClass = 'Json';
  public $uses = array('Image', 'Tag');
  private $limit = 10;
  private $fields = array('url', 'thumbnail');
  private $conditions = array('nsfw' => false);

  private function getDefaultQueryParams() {
    return array($this->conditions, $this->fields, $this->limit);
  }

  public function top() {
    list($conditions, $fields, $limit) = $this->getDefaultQueryParams();

    $order = array('used' => 'DESC');

    $result = $this->Image->find('all', compact('conditions', 'fields', 'limit', 'order'));

    $this->set(compact('result'));
  }

  public function featured() {
    list($conditions, $fields, $limit) = $this->getDefaultQueryParams();

    $conditions['featured'] = true;

    $result = $this->Image->find('all', compact('conditions', 'fields', 'limit'));

    $this->set(compact('result'));
  }

  public function newest() {
    list($conditions, $fields, $limit) = $this->getDefaultQueryParams();

    $order = array('created' => 'DESC');

    $result = $this->Image->find('all', compact('conditions', 'fields', 'limit', 'order'));

    $this->set(compact('result'));
  }

  public function trending() {
    list($conditions, $fields, $limit) = $this->getDefaultQueryParams();

    $this->set(compact('result'));
  }

  public function search() {
    list($conditions, $fields, $limit) = $this->getDefaultQueryParams();

    $tags = func_get_args();

    $result = $this->Image->find('all', compact('limit', 'conditions', 'fields'));

    $this->set(compact('result'));
  }

  public function beforeRender() {
    parent::beforeRender();
    $this->set('result', Set::extract($this->viewVars['result'], '{n}.Image'));

    $this->set('_serialize', array('result'));
  }
}