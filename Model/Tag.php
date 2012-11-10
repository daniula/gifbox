<?php

class Tag extends Image {
  public $hasAndBelongsToMany = array('Image' => array('images_count' => true));
}