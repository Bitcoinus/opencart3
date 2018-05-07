<form action="<?php echo $action ?>" method="post" id="wtp-checkout">
  <input type="hidden" name="payment[do]" value="yes">
  <input type="hidden" name="bitcoinus_payment_method" />
  <div class="buttons">
    <div class="pull-right">
      <button id="button-confirm" class="btn btn-primary" data-loading-text="<?php echo $nls['text_loading'] ?>">
        <?php echo $nls['button_pay'] ?>
      </button>
    </div>
  </div>
</form>
