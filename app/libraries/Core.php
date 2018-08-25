<?php
/**
 * App core class
 * creates url and loads core controller
 * url format - /controller/method/params
 */

 class Core {
   protected $currentController = 'Pages';
   protected $currentMethod = 'index';
   protected $params = [];

  public function __construct(){
    //print_r($this->getUrl());

    $url = $this->getUrl();
    
    //look in controllers for first value
    if(file_exists('../app/controllers/' . ucwords($url[0]) . '.php')){
      // if exists, set as controller
      $this->currentController = ucwords($url[0]);
      // unset 0 index
      unset($url[0]);
    }
    // require the controller
    require_once '../app/controllers/'. $this->currentController . '.php';

    // Instantiate controller class
    $this->currentController = new $this->currentController;

    //check for second part/the method
    if(isset($url[1])){
      //check to see if method exists
      if(method_exists($this->currentController,$url[1])){
        $this->currentMethod = $url[1];
        // unset 0 index
        unset($url[1]);
      }
    }

    //Tried to understand how this works. Since we unset all the other vals above, only the params are left
    //print_r(array_values($url));
    $this->params = $url ? array_values($url) : [];

    // call a callback with array of params
    call_user_func_array([$this->currentController,$this->currentMethod], $this->params);

  }

   public function getUrl(){
    if(isset($_GET['url'])) {
      $url = rtrim($_GET['url'], '/');
      $url = filter_var($url, FILTER_SANITIZE_URL);
      $url = explode('/', $url);
      return $url;
    }
   }
 }