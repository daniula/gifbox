<?php

class Image extends AppModel {
  public $hasAndBelongsToMany = array('Tag');
  public $validate = array(
    'url' => array('rule' => 'isUnique', 'message' => 'This image already exists in database'),
  );

  public function saveMany($data = null, $options = array()) {
    $options = is_string($options) ? array('source' => $options) : $options;
    $options = array_merge(array('atomic' => false, 'validate' => true), $options);

    if (empty($options['source'])) {
      $result = parent::saveMany($data, $options);
    } else {
      $source = $options['source'];
      unset($options['source']);
      $result = parent::saveMany($this->parseData($data, $source), $options);
    }

    return $result;
  }

  public function parseData($data, $source) {
    return call_user_method_array('parse'.ucfirst($source), $this, array($data));
  }

  private function parseReddit($data) {
    $data = is_array($data) ? $data : array($data);
    $result = array();

    foreach ($data as $record) {
      if ($record->data->is_self == false) {

        $result[] = array(
          'url' => $record->data->url,
          'thumbnail' => empty($record->data->thumbnail) ? null : $record->data->thumbnail,
          'title' => $record->data->title,
          'nsfw' => $record->data->over_18,
          'source' => 'reddit/'.$record->data->subreddit,
          'raw' => json_encode($record),
        );
      }
    }

    return $result;
  }
}