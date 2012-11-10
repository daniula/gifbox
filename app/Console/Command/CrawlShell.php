<?php

class CrawlShell extends AppShell {
  public $uses = array('Image');
  public $components = array('Reddit');

  public function getOptionParser() {
    $parser = parent::getOptionParser();
    $parser->addOption('after', array(
      'help' => 'Provide token of page which crawling shoud start from.',
      'short' => 'a',
    ));
    $parser->addOption('debug', array(
     'help' => 'Check amount of images in database before and after request.',
     'short' => 'd',
     'boolean' => true,
     'default' => false,
    ));
    $parser->addOption('repeat', array(
      'help' => 'Repeat query using pagination data x number of times.',
      'short' => 'r',
      'deafult' => 1,
    ));
    return $parser;
  }

  public function go($subreddit = null, $repeat = null) {
    if (count($this->args) == 0 && is_null($subreddit)) {
      return $this->out('You have to give subreddit name to start crawling');
    }
    $repeat = is_null($repeat) ? $this->params['repeat'] : $repeat;
    $subreddit = is_null($subreddit) ? $this->args[0] : $subreddit;
    $cache = sprintf('CrawlShell.%s.after', $subreddit);
    $after = empty($this->params['after']) ? Cache::read($cache) : $this->params['after'];
    $params = compact('after');

    if ($this->params['debug']) {
      $this->out('Number of images before query: '.$this->Image->find('count'));
    }

    if ($result = $this->Reddit->get('/r/'.$subreddit, $params)) {

      $this->out(sprintf('Reddit returned %d results', count($result->children)));
      if ($after) {
        $this->out('www.reddit.com/r/'.$subreddit.'/?'.http_build_query(compact('after')));
      } else {
        $this->out('www.reddit.com/r/'.$subreddit.'/');
      }

      $insert = $this->Image->saveMany($result->children, 'reddit');
      $msg = array_sum($insert).' images saved.';

      if (!empty($this->Image->validationErrors)) {
        $errors = $this->Image->validationErrors;
        foreach ($errors as $index => &$error) {
          $error = $error['url'][0].': '.$result->children[$index]->data->url;
        }
        $this->out(join("\n", $errors));
        $msg .= ' '.count($errors).' errors.';
      }

      $this->out($msg);

      if (empty($this->params['after'])) {
        Cache::write($cache, $result->after);
      }

    } else {
      $this->out('Reddit error: '.json_decode($this->Reddit->response));
    }

    if ($this->params['debug']) {
      $this->out('Number of images after query: '.$this->Image->find('count'));
    }

    if ($repeat > 0) {
      $this->go($subreddit, --$repeat);
    }
  }

  public function reset() {
    if (count($this->args) == 0) {
      return $this->out('You have to give subreddit name.');
    }
    $subreddit = $this->args[0];
    Cache::delete('CrawlShell.'.$subreddit.'.after');
    $this->out('Reddit crawl progress cleared');
  }
}