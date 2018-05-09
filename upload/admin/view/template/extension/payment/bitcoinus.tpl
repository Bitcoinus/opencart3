<?php echo $header ?>
<?php echo $column_left ?>

<div id="content">
  <div class="page-header">
    <div class="container-fluid">

      <div class="pull-right">
        <button type="submit" title="<?php button_save ?>" class="btn btn-primary" form="form-payment" data-toggle="tooltip">
          <i class="fa fa-save"></i>
        </button>
        <a href="<?php cancel ?>" title="<?php button_cancel ?>" class="btn btn-default" data-toggle="tooltip"><i class="fa fa-reply"></i></a>
      </div>

      <h1><?php echo $nls['heading_title'] ?></h1>

      <ul class="breadcrumb">
        <?php if (isset($breadcrumbs)) foreach ($breadcrumbs as $breadcrumb) { ?>
          <li>
            <a href="<?php echo str_replace('marketplace','extension',$breadcrumb['href']) ?>"><?php echo $breadcrumb['text'] ?></a>
          </li>
        <?php } ?>
      </ul>

    </div>
</div>

<div class="container-fluid">

  <?php if (!empty($error_warning)) { ?>
    <div class="alert alert-danger alert-dismissible">
      <i class="fa fa-exclamation-circle"></i> <?php echo $error_warning ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
  <?php } ?>

  <div class="panel panel-default">

    <div class="panel-heading">
      <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo 'Settings' ?></h3>
    </div>

    <div class="panel-body">
      <form action="<?php echo $action ?>" method="post" enctype="multipart/form-data" id="form-payment" class="form-horizontal">

        <ul class="nav nav-tabs" id="tabs">
          <li class="active"><a href="#tab-account" data-toggle="tab"><?php echo $nls['nls_general'] ?></a></li>
        </ul>

        <div class="tab-content">

          <div class="tab-pane active" id="tab-account">
            <div class="form-group">
              <label class="col-sm-2 control-label" for="input-status"><?php echo $nls['nls_status'] ?></label>
              <div class="col-sm-10">
                <select name="bitcoinus_status" id="input-status" class="form-control">
                  <?php if ($bitcoinus_status) { ?>
                    <option value="1" selected="selected"><?php echo $nls['nls_enabled'] ?></option>
                    <option value="0"><?php echo $nls['nls_disabled'] ?></option>
                  <?php } else { ?>
                    <option value="1"><?php echo $nls['nls_enabled'] ?></option>
                    <option value="0" selected="selected"><?php echo $nls['nls_disabled'] ?></option>
                  <?php } ?>
                </select>
              </div>
            </div>

            <div class="form-group required">
              <label class="col-sm-2 control-label" for="entry-pid"><?php echo $nls['nls_pid'] ?></label>
              <div class="col-sm-10">
                <input type="number" name="bitcoinus_pid" id="entry-pid" class="form-control" value="<?php echo $bitcoinus_pid ?>">
                <?php if (empty($bitcoinus_pid)) { ?>
                  <div class="text-danger"><?php echo $nls['error_pid'] ?></div>
                <?php } ?>
                <br>
                <div>
                  <?php echo $nls['nls_pid_desc'] ?>
                </div>
              </div>
            </div>

            <div class="form-group required">
              <label class="col-sm-2 control-label" for="entry-key"><?php echo $nls['nls_key'] ?></label>
              <div class="col-sm-10">
                <input type="text" name="bitcoinus_key" id="entry-key" class="form-control" value="<?php echo $bitcoinus_key ?>">
                <?php if (empty($bitcoinus_key)) { ?>
                  <div class="text-danger"><?php echo $nls['error_key'] ?></div>
                <?php } ?>
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-2 control-label" for="entry-test"><?php echo $nls['nls_items'] ?></label>
              <div class="col-sm-10">
                <select name="bitcoinus_items" id="entry-items" class="form-control">
                  <?php if ($bitcoinus_items) { ?>
                    <option value="1" selected="selected"><?php echo $nls['nls_send'] ?></option>
                    <option value="0"><?php echo $nls['nls_dontsend'] ?></option>
                  <?php } else { ?>
                    <option value="1"><?php echo $nls['nls_send'] ?></option>
                    <option value="0" selected="selected"><?php echo $nls['nls_dontsend'] ?></option>
                  <?php } ?>
                </select>
                <br>
                <div>
                  <?php echo $nls['nls_items_desc'] ?>
                </div>
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-2 control-label" for="entry-test"><?php echo $nls['nls_test'] ?></label>
              <div class="col-sm-10">
                <select name="bitcoinus_test" id="entry-test" class="form-control">
                  <?php if ($bitcoinus_test) { ?>
                    <option value="1" selected="selected"><?php echo $nls['nls_on'] ?></option>
                    <option value="0"><?php echo $nls['nls_off'] ?></option>
                  <?php } else { ?>
                    <option value="1"><?php echo $nls['nls_on'] ?></option>
                    <option value="0" selected="selected"><?php echo $nls['nls_off'] ?></option>
                  <?php } ?>
                </select>
                <br>
                <div>
                  <?php echo $nls['nls_test_desc'] ?>
                </div>
              </div>
            </div>

          </div>

        </div>

      </form>
    </div>

  </div>

</div>

<?php echo $footer ?>
