<?php

class ModelExtensionPaymentBitcoinus extends Model
{

    public function getMethod($address,$total)
    {

        $this->load->language('extension/payment/bitcoinus');

        return [
          'code' => 'bitcoinus',
          'title' => $this->language->get('payment_method_title'),
          'terms' => '',
          'sort_order' => 0
        ];

    }
}
