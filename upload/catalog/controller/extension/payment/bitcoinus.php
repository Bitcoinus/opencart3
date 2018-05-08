<?php

class ControllerExtensionPaymentBitcoinus extends Controller
{

  public function index(){
    $this->load->language('extension/payment/bitcoinus');
    $data['action'] = $this->url->link('extension/payment/bitcoinus/confirm','','SSL');
    $data['nls'] = $this->language->all();
    if ($this->request->get['route'] != 'checkout/guest/confirm') {
      $data['back'] = HTTPS_SERVER.'index.php?route=checkout/payment';
    } else {
      $data['back'] = HTTPS_SERVER.'index.php?route=checkout/guest';
    }
    $this->load->model('checkout/order');
    $order = $this->model_checkout_order->getOrder($this->session->data['order_id']);
    $pid = $this->config->get('payment_bitcoinus_pid');
    if (file_exists(DIR_TEMPLATE.$this->config->get('config_template').'config_template')) {
      return $this->load->view($this->config->get('config_template').'config_template',$data);
    } else {
      return $this->load->view('extension/payment/bitcoinus',$data);
    }
  }

  public function confirm(){
    // load order
    $this->load->model('checkout/order');
    $order_id = $this->session->data['order_id'];
    $order = $this->model_checkout_order->getOrder($order_id);
    $this->model_checkout_order->addOrderHistory($order_id,1);
    // load payment address
    $address = '';
    if (!empty($order['payment_address_1'])) $address = $order['payment_address_1'].', '.trim($order['payment_postcode'].' '.$order['payment_city']);
    if (!empty($order['payment_zone'])) $address .= ', '.$order['payment_zone'];
    if (!empty($order['payment_country'])) $address .= ', '.$order['payment_country'];
    // load payment name
    if (!empty($order['payment_firstname'])) {
      $firstname = $order['payment_firstname'];
      $email = '';
      $street = $address;
    } elseif (!empty($order['firstname'])) {
      $firstname = $order['firstname'];
      $email = $order['email'];
      $street = '';
    } else {
      $firstname = '';
    }
    if (!empty($order['payment_lastname'])) {
      $lastname = $order['payment_lastname'];
      $email = '';
      $street = $address;
    } elseif (!empty($order['lastname'])) {
      $lastname = $order['lastname'];
      $email = $order['email'];
      $street = '';
    } else {
      $lastname = '';
    }
    $fullname = trim(trim($firstname).' '.trim($lastname));
    // get project id
    $pid = $this->config->get('payment_bitcoinus_pid');
    // get project secret key
    $key = $this->config->get('payment_bitcoinus_key');
    // check test mode
    $test = $this->config->get('payment_bitcoinus_test');
    // create payment array
    $data = json_encode((object)[
      'pid' => $pid,
      'orderid' => "$order_id",
      'currency' => $order['currency_code'],
      'amount' => $order['total'],
      'name' => $fullname,
      'email' => $email,
      'street' => $street,
      'redirect' => 'https://'.$_SERVER['HTTP_HOST'].'/index.php?route=extension/payment/bitcoinus/accept',
      'back' => 'https://'.$_SERVER['HTTP_HOST'].'/index.php?route=extension/payment/bitcoinus/cancel',
      'test' => $test
    ]);

    // perform redirect
    $args = [ 'data' => base64_encode($data), 'signature' => hash_hmac('sha256',$data,$key) ];
    header('Location: https://pay.bitcoinus.io/init?'.http_build_query($args));
		exit();
  }

  public function cancel(){
    $this->response->redirect($this->url->link('checkout/cart', '', true));
  }

  public function accept(){
    $this->response->redirect($this->url->link('checkout/success', '', true));
  }

  public function callback(){
    // init variables
    $data = stripslashes(filter_var($_REQUEST['data'],FILTER_SANITIZE_STRING,FILTER_FLAG_NO_ENCODE_QUOTES));
    $signature = filter_var($_REQUEST['signature'],FILTER_SANITIZE_STRING);
    $pid = $this->config->get('payment_bitcoinus_pid');
    $key = $this->config->get('payment_bitcoinus_key');
    // check signature
    $signature_local = hash_hmac('sha256',$data,$key);
    if ($signature_local != $signature) exit('Invalid signature');
    // decode object
    if (is_object($data=json_decode($data))) {
      $orderid = filter_var($data->orderid,FILTER_SANITIZE_STRING);
      $status = intval($data->status);
      // success
      if ($status == 1) {
        // load order
        $this->load->model('checkout/order');
        $order = $this->model_checkout_order->getOrder($orderid);
        if (empty($order)) {
          exit('Order not found');
        } elseif (is_array($order)) {
          // update order status
          $this->model_checkout_order->addOrderHistory($orderid,15);
        } else {
          error_log('Order referenced in Bitcoinus callback cannot be found');
        }
      } else {
        echo 'Payment failed';
      }
    }
  }

}
