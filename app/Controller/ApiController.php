<?php

class ApiController extends AppController {
  public $viewClass = 'Json';
  public $uses = array('Image', 'Tag');
  private $limit = 10;
  private $fields = array('url', 'thumbnail');
  private $conditions = array('nsfw' => false, 'thumbnail NOT' => 'default');

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

  public function search($query) {
    list($conditions, $fields, $limit) = $this->getDefaultQueryParams();
    if (!empty($this->params['named']['limit'])) {
      $limit = $this->params['named']['limit'];
    }

    if ($tags = $this->Tag->find('all', array('conditions' => array('tag' => explode(' ', $query))))) {

    }

    $result = $this->Image->find('all', compact('limit', 'conditions', 'fields'));

    $this->set(compact('result'));
  }

  public function beforeRender() {
    parent::beforeRender();
    $results = Set::extract($this->viewVars['result'], '{n}.Image');
    foreach ($results as &$result) {
      if (strpos('.gif', $result['url']) === false && strpos('.jpg', $result['url'])) {
        $result['url'] .= '.gif';
      }
    }
    $this->set('result', $result);

    $this->set('_serialize', array('result'));
  }
}