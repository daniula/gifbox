<?php

App::uses('HttpSocket', 'Network/Http');

class RedditComponent extends Component {
  private $http;
  public $response;

  public function __construct(ComponentCollection $collection, $settings = array()) {
    parent::__construct($collection, $settings);

    $header = array('User-Agent' => 'Gifbox made by /u/daniula');
    $this->http = new HttpSocket(compact('header'));
  }

  public function get($uri = null, $query = array(), $request = array()) {
    if (is_string($uri) && strpos('http:', $uri) === false) {
      if (strpos('.json', $uri) === false) {
        $uri .= '.json';
      }
      $uri = array('host' => 'www.reddit.com', 'path' => $uri);
    }

    $this->response = $this->http->get($uri, $query, $request);

    if ($this->response->code == 200) {
      $result = json_decode($this->response->body);
      $result = $result->data;
    } else {
      $result = false;
    }

    return $result;
  }
}