<?php

/**
 * Read It Later API library for PHP
 *
 * LICENSE
 * 
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 *
 * @package   ReadItLater
 * @copyright Copyright (c) 2011 aoiaoi
 * @license   New BSD License
 * @version   $Id: ReadItLater.php 2011-11-10 $
 */

/**
 * @package   ReadItLater
 * @copyright Copyright (c) 2011 aoiaoi
 * @license   New BSD License
 * @version   $Id: ReadItLater.php 2011-11-10 $
 */
class ReadItLater {
  /** URI for other than text method */
  const API_URI_BASE = 'https://readitlaterlist.com';
  
  /** URI for text method */
  const API_URI_TEXT = 'https://text.readitlaterlist.com';

  /** Query paths */
  const PATH_ADD    = '/v2/add';
  const PATH_SEND   = '/v2/send';
  const PATH_STATS  = '/v2/stats';
  const PATH_GET    = '/v2/get';
  const PATH_AUTH   = '/v2/auth';
  const PATH_SIGNUP = '/v2/signup';
  const PATH_TEXT   = '/v2/text';
  const PATH_API    = '/v2/api';

  /**
   * Your Read It Later API key
   *
   * @var string
   */
  protected $_apiKey;
  
  /**
   * The username of the account being authenticated
   *
   * @var string
   */
  protected $_username;
  
  /**
   * The password of the account being authenticated
   *
   * @var string
   */
  protected $_password;
  
  /**
   * Zend_Rest_Client instance
   * 
   * @var Zend_Rest_Client
   */
  protected $_restClient = null;
  
  /**
   * Performs object initializations
   *
   * @param  string $apikey
   * @param  string $username
   * @param  string $password
   * @return void
   */
  public function __construct($apikey, $username = null, $password = null) {
    $this->setApiKey($apikey);
    
    if (isset($username)) {
      $this->setUsername($username);
    }
    if (isset($password)) {
      $this->setPassword($password);
    }
    
    /**
     * @see Zend_Rest_Client
     */
    require_once 'Zend/Rest/Client.php';
    $this->_restClient = new Zend_Rest_Client(self::API_URI_BASE);
  }
  
  /**
   * Set the your Read It Later API key
   *
   * @param  string $value
   * @return ReadItLater
   */
  public function setApiKey($value) {
    $this->_apiKey = (string) $value;
    
    return $this;
  }
  
  /**
   * Retrieve your Read It Later API key
   * 
   * @return string
   */
  public function getApiKey() {
    return $this->_apiKey;
  }
  
  /**
   * Set the username of the account being authenticated
   *
   * @param  string $value
   * @return ReadItLater
   */
  public function setUsername($value) {
    $this->_username = (string) $value;
    
    return $this;
  }
  
  /**
   * Retrieve the username of the account being authenticated
   * 
   * @return string
   */
  public function getUsername() {
    return $this->_username;
  }
  
  /**
   * Set the password of the account being authenticated
   *
   * @param  string $value
   * @return ReadItLater
   */
  public function setPassword($value) {
    $this->_password = (string) $value;
    
    return $this;
  }
  
  /**
   * Retrieve the password of the account being authenticated
   *
   * @return string
   */
  public function getPassword() {
    return $this->_password;
  }
  
  /**
   * List Management: Add - Add a page to a user's list
   * 
   * @param  string $url
   * @param  array  $options : (title|ref_id)
   * @return $response
   */
  public function add($url, $options = array()) {
    $parameters = array('apikey'   => $this->getApiKey(),
			'username' => $this->getUsername(),
			'password' => $this->getPassword(),
			'url'      => $url);
    
    $parameters = $this->_prepareParameters($parameters, $options);
    $this->_validateAddMethod($parameters);
    
    $this->_restClient->getHttpClient()->resetParameters();
    $response = $this->_restClient->restGet(self::PATH_ADD, $parameters);
    
    return $response;
  }
  
  /**
   * List Management: Send - Mark items as read, add multiple pages, change titles, or set tags
   *
   * @param  array $options : (new|read|update_title|update_tags)
   * @return $response
   */
  public function send($options = array()) {
    $parameters = array('apikey'   => $this->getApiKey(),
			'username' => $this->getUsername(),
			'password' => $this->getPassword());
    
    $parameters = $this->prepareParameters($parameters, $options);
    $this->_validateSendMethod($parameters);
    
    $this->_restClient->getHttpClient()->resetParameters();
    $response = $this->_restClient->restGet(self::PATH_SEND, $parameters);
    
    return $response;
  }

  /**
   * List Management: Get - Retrieve a user's reading list
   *
   * @param  array $options : (format|state|myAppOnly|since|count|page|tags)
   * @return $response
   */
  public function get($options = array()) { 
    $parameters = array('apikey'   => $this->getApiKey(),
			'username' => $this->getUsername(),
			'password' => $this->getPassword());

    if (!isset($options['count'])) { $options['count'] = 10; }
    
    $parameters = $this->_prepareParameters($parameters, $options);
    $this->_validateGetMethod($parameters);
    
    $this->_restClient->getHttpClient()->resetParameters();
    $response = $this->_restClient->restGet(self::PATH_GET, $parameters);
    
    return $response;
  }
  
  /**
   * List Management: Stats - Retrieve information about a user's list
   *
   * @param  array $options : (format)
   * @return $response
   */
  public function stats($options = array()) {
    $parameters = array('apikey'   => $this->getApiKey(),
			'username' => $this->getUsername(),
			'password' => $this->getPassword());
    
    $parameters = $this->_prepareParameters($parameters, $options);
    $this->_validateStatsMetod($parameters);
    
    $this->_restClient->getHttpClient()->resetParameters();
    $response = $this->_restClient->restGet(self::PATH_STATS, $parameters);
    
    return $response;
  }
  
  /**
   * Account Management: Authenticate - Verify a user's account
   *
   * @return $response
   */
  public function auth() {
    $parameters = array('apikey'   => $this->getApiKey(),
			'username' => $this->getUsername(),
			'password' => $this->getPassword());
    
    $this->_restClient->getHttpClient()->resetParameters();
    $response = $this->_restClient->restGet(self::PATH_AUTH, $parameters);
    
    return $response;
  }
  
  /**
   * Account Management: Signup - Register a new user
   * 
   * @param  string $username
   * @param  string $password
   * @return $response
   */
  public function signup($username, $password) {
    $parameters = array('apikey'   => $this->getApiKey(),
			'username' => $username,
			'password' => $password);
    
    $this->_restClient->getHttpClient()->resetParameters();
    $response = $this->_restClient->restGet(self::PATH_SIGNUP, $parameters);
    
    return $response;
  }
    
  /**
   * Text: Text - Get the text only version of a url
   * 
   * @param  string $url
   * @param  array  $options : (mode|images)
   * @return $response
   */
  public function text($url, $options = array()) {
    $parameters = array('apikey' => $this->getApiKey(),
			'url'    => $url);
    
    $parameters = $this->_prepareParameters($parameters, $options);
    $this->_validateTextMethod($parameters);

    $this->_restClient->getHttpClient()->resetParameters();
    $this->_restClient->setUri(self::API_URI_TEXT);
    $response = $this->_restClient->restGet(self::PATH_TEXT, $parameters);
    
    return $response;
  }
  
  /**
   * API: API - Return stats / current rate limit information about your API key
   *
   * @return $response
   */
  public function api() {
    $parameters = array('apikey' => $this->getApiKey());
    
    $this->_restClient->getHttpClient()->resetParameters();
    $response = $this->_restClient->restGet(self::PATH_API, $parameters);
    
    return $response;
  }
  
  /**
   * Prepare parameters for the request
   *
   * @param  array $parameters
   * @param  array $options
   * @return array Merged array of user and optional/required parameters
   */
  protected function _prepareParameters(array $parameters, array $options) {
    return array_merge($options, $parameters);
  }
  
  /**
   * Validate Add Method Parameters
   *
   * @param  array $parameters
   * @return void
   */
  protected function _validateAddMethod(array $parameters) {
    $validParameters = array('apikey', 'username', 'password', 'url', 'title', 'ref_id');
    $this->_compareParameters($parameters, $validParameters);
  }
  
  /**
   * Validate Send Method Parameters
   *
   * @param  array $parameters
   * @return void
   */
  protected function _validateSendMethod(array $parameters) {
    $validParameters = array('apikey', 'username', 'password', 'new', 'read', 'update_title', 'update_tags');
    $this->_compareParameters($parameters, $validParameters);
  }

  /**
   * Validate Get Method Parameters
   *
   * @param  array $parameters
   * @return void
   */
  protected function _validateGetMethod(array $parameters) {
    $validParameters = array('apikey', 'username', 'password', 'format', 'state',
			     'myAppOnly', 'since', 'count', 'page', 'tags');
    $this->_compareParameters($parameters, $validParameters);
  }
  
  /**
   * Validate Text Method Parameters
   *
   * @param  array $parameters
   * @return void
   */
  protected function _validateTextMethod(array $parameters) {
    $validParameters = array('apikey', 'url', 'mode', 'images');
    $this->_compareParameters($parameters, $validParameters);
  }

  /**
   * Throws an exception if and only if any user parameters are invalid
   *
   * @param  array $parameters
   * @param  array $validParameters
   * @return void
   * @throws Exception
   */
  protected function _compareParameters(array $parameters, array $validParameters) {
    $difference = array_diff(array_keys($parameters), $validParameters);
    if ($difference) {
      throw new Exception('The following parameters are invalid: ' . implode(',', $difference));
    }
  }
}
