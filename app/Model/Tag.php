<?php

class Tag extends AppModel {
  public $hasAndBelongsToMany = array('Image' => array('images_count' => true));
}