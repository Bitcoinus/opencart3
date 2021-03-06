<?php

class ControllerExtensionPaymentBitcoinus extends Controller
{

  private $pid;
  private $error = [];
  private $errorField = [ 'warning', 'pid', 'key' ];
  private $breadcrumbs = [
    'text_home' => 'common/dashboard',
    'nls_extensions' => 'marketplace/extension',
    'heading_title' => 'extension/payment/bitcoinus'
  ];
  private $configFields = [
    'bitcoinus_status',
    'payment_bitcoinus_status',
    'bitcoinus_pid',
    'payment_bitcoinus_pid',
    'bitcoinus_key',
    'payment_bitcoinus_key',
    'bitcoinus_items',
    'payment_bitcoinus_items',
    'bitcoinus_test',
    'payment_bitcoinus_test'
  ];

  public function index(){
    if (isset($this->session->data['user_token'])) {
      $extensions_path = 'marketplace';
      $setting_prefix = 'payment_bitcoinus';
    } else {
      $extensions_path = 'extension';
      $setting_prefix = 'bitcoinus';
    }
    $this->load->language('extension/payment/bitcoinus');
    $this->document->setTitle($this->language->get('heading_title'));
    $this->load->model('setting/setting');
    if ($this->request->server['REQUEST_METHOD']=='POST' && $this->validate()) {
      $this->model_setting_setting->editSetting($setting_prefix,$this->request->post);
      $this->session->data['success'] = $this->generateData('nls_success','');
      $this->response->redirect($this->generateData('',$extensions_path.'/extension'));
    }
    foreach ($this->getErrorField() as $fieldName) {
      $dataName = 'error_'.$fieldName;
      $data[$dataName] = $this->errorValue($fieldName);
    }
    foreach ($this->getBreadcrumbs() as $key => $value) $data['breadcrumbs'][] = $this->generateData($key,$value);
    $data['action'] = $this->generateData('','extension/payment/bitcoinus');
    $data['cancel'] = $this->generateData('','marketplace/extension');
    $data['callback'] = HTTP_CATALOG.'index.php?route=extension/payment/bitcoinus/callback';
    foreach ($this->getConfigFields() as $field) $data[$field] = $this->generateConfigField($field);
    $this->validateProject($this->config->get($setting_prefix.'_pid'));
    $data['header'] = $this->load->controller('common/header');
    $data['column_left'] = $this->load->controller('common/column_left');
    $data['footer'] = $this->load->controller('common/footer');
    $data['nls'] = $this->language->all();
    $this->response->setOutput($this->load->view('extension/payment/bitcoinus',$data));
  }

  protected function validate(){
    if (!$this->user->hasPermission('modify','extension/payment/bitcoinus')) $this->error['warning'] = $this->language->get('error_warning');
    if ((!$this->request->post['payment_bitcoinus_pid'])&&(!$this->request->post['bitcoinus_pid'])) $this->error['pid'] = $this->language->get('error_pid');
    if ((!$this->request->post['payment_bitcoinus_key'])&&(!$this->request->post['bitcoinus_key'])) $this->error['key'] = $this->language->get('error_key');
    return !$this->error;
  }

  private function errorValue($fieldName){
    if (isset($this->error[$fieldName])) {
      $data = $this->error[$fieldName];
    } else {
      $data = '';
    }
    return $data;
  }

  private function generateData($text,$path){
    if (isset($this->session->data['user_token'])) {
      $user_token_arg = 'user_token';
      $extensions_path = 'marketplace';
      $user_token = $this->session->data['user_token'];
    } else {
      $user_token_arg = 'token';
      $extensions_path = 'extension';
      $user_token = $this->session->data['token'];
    }
    if ($path == $extensions_path.'/extension') {
      $tokenParam = '';
    } else {
      $tokenParam = '&type=payment';
    }
    $token = $user_token_arg.'='.$user_token.$tokenParam;
    if (empty($text)) {
      $data = $this->url->link($path,$token,TRUE);
    } elseif (empty($path)) {
      $data = $this->language->get($text);
    } else {
      $data = array(
        'text' => $this->language->get($text),
        'href' => $this->url->link($path,$token,TRUE)
      );
    }
    return $data;
  }

  private function getPID(){
    return $this->pid;
  }

  private function setPID($pid){
    $this->pid = $pid;
  }

  private function generateConfigField($fieldName){
    if (isset($this->request->post[$fieldName])) {
      $data = $this->request->post[$fieldName];
    } else {
      $data = $this->config->get($fieldName);
    }
    return $data;
  }

  private function getErrorField(){
    return $this->errorField;
  }

  private function getConfigFields(){
    return $this->configFields;
  }

  private function validateProject($pid){
    $this->setPID(empty($pid) ? 0 : $pid);
  }

  public function getBreadcrumbs(){
    return $this->breadcrumbs;
  }

}
