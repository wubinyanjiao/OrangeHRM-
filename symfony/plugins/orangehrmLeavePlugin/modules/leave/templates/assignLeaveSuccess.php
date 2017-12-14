<?php
use_javascripts_for_form($form);
use_stylesheets_for_form($form);
use_stylesheet(plugin_web_path('orangehrmLeavePlugin', 'css/assignLeaveSuccess.css'));
?>

<?php include_partial('overlapping_leave', array('overlapLeave' => $overlapLeave, 'workshiftLengthExceeded' => $workshiftLengthExceeded));?>

<div class="box" id="assign-leave">
    <div class="head">
        <h1><?php echo __('Assign Leave') ?></h1>
    </div>
    <div class="inner">
        <?php include_partial('global/flash_messages'); ?>
        <?php if ($form->hasErrors()): ?>
                <?php include_partial('global/form_errors', array('form' => $form)); ?>
        <?php endif; ?>        
<?php if (count($leaveTypes) > 0) : ?>        
        <form id="frmLeaveApply" name="frmLeaveApply" method="post" action="">
            <fieldset>                
                <ol>
                    <?php echo $form->render(); ?>
                    <li class="required new">
                        <em>*</em> <?php echo __(CommonMessages::REQUIRED_FIELD); ?>
                    </li>                      
                </ol>                            
                <p>
                    <input type="button" id="assignBtn" value="<?php echo __("Assign") ?>"/>
                </p>                
            </fieldset>            
        </form>
<?php endif ?>        
    </div> <!-- inner -->
    
</div> <!-- assign leave -->

<!-- leave balance details HTML: Begins -->
<div class="modal hide" id="balance_details">
  <div class="modal-header">
    <a class="close" data-dismiss="modal">×</a>
    <h3><?php echo ' ' . __('休假详情'); ?></h3>
  </div>
  <div class="modal-body">
      <dl class="search-params">
        <dt><?php echo __('员工姓名');?></dt>
        <dd id="popup_emp_name"></dd>
        <dt><?php echo __('休假类型');?></dt>
        <dd id="popup_leave_type"></dd>
        <dt><?php echo __('截止日期');?></dt>
        <dd id="balance_as_of"></dd>       
     </dl>
    <table border="0" cellspacing="0" cellpadding="0" class="table">
        <tbody>
                <tr class="odd">
                    <td><?php echo __('所有天数'); ?></td>
                    <td id="balance_entitled">0.00</td>
                </tr>
                <tr class="odd" id="container-adjustment">
                    <td><?php echo __('Adjustment'); ?></td>
                    <td id="balance_adjustment">0.00</td>
                </tr>
                <tr class="even">
                    <td><?php echo __('Taken'); ?></td>
                    <td id="balance_taken">0.00</td>
                </tr>
                <tr class="odd">
                    <td><?php echo __('Scheduled'); ?></td>
                    <td id="balance_scheduled">0.00</td>
                </tr>
                <tr class="even">
                    <td><?php echo __('Pending Approval'); ?></td>
                    <td id="balance_pending">0.00</td>
                </tr>                    
        </tbody>
        <tfoot>
            <tr class="total">
                <td><?php echo __('剩余天数');?></td>
                <td id="balance_total">0.00</td>
            </tr>
        </tfoot>          
    </table>
  </div>
  <div class="modal-footer">
    <input type="button" class="btn" data-dismiss="modal" id="closeButton" value="<?php echo __('Ok'); ?>" />
  </div>
</div>

<!-- leave balance details HTML: Begins -->
<div class="modal hide" id="multiperiod_balance">
  <div class="modal-header">
    <a class="close" data-dismiss="modal">×</a>
    <h3><?php echo ' - ' . __('休假详情'); ?></h3>
  </div>
  <div class="modal-body">
      <dl class="search-params">
        <dt><?php echo __('Employee Name');?></dt>
        <dd id="multiperiod_emp_name"></dd>
        <dt><?php echo __('Leave Type');?></dt>
        <dd id="multiperiod_leave_type"></dd>        
     </dl>
    <table border="0" cellspacing="0" cellpadding="0" class="table">
        <thead>
            <tr>
                <th><?php echo __('Leave Period');?></th>
                <th><?php echo __('Initial Balance');?></th>
                <th><?php echo __('Leave Date');?></th>
                <th><?php echo __('Available Balance');?></th>
            </tr>
        </thead>
        <tbody>
                <tr class="odd">
                    <td></td>
                </tr>                    
        </tbody>       
    </table>
  </div>
  <div class="modal-footer">
    <input type="button" class="btn" data-dismiss="modal" id="closeButton" value="<?php echo __('Ok'); ?>" />
  </div>
</div>

<!-- Confirmation box for leave balance update -->
<div class="modal hide" id="leaveBalanceConfirm" style="width:500px">
  <div class="modal-header">
    <h3><?php echo ' - ' . __('确认休假分配'); ?></h3>
  </div>
  <div class="modal-body">
      <p><?php echo __('员工对请假要求没有足够的多余天数.');?></p>
      <p><?php echo __('单击确认离开.');?></p>
  </div>
  <div class="modal-footer">
    <input type="button" class="btn" data-dismiss="modal" id="confirmOkButton" value="<?php echo __('Ok'); ?>" />
    <input type="button" class="reset" data-dismiss="modal" id="confirmCancelButton" value="<?php echo __('Cancel'); ?>" />    
  </div>    
</div>

<?php include_component('core', 'ohrmPluginPannel', array('location' => 'assign-leave-javascript'))?>
<!-- leave balance details HTML: Ends -->

<?php

    $dateFormat = get_datepicker_date_format($sf_user->getDateFormat());
    $displayDateFormat = str_replace('yy', 'yyyy', $dateFormat);
?>

<script type="text/javascript">
//<![CDATA[    
  
    var datepickerDateFormat = '<?php echo $dateFormat; ?>';
    var displayDateFormat = '<?php echo $displayDateFormat; ?>';
   
    var lang_invalidDate = '<?php echo __(ValidationMessages::DATE_FORMAT_INVALID, array('%format%' => $displayDateFormat)) ?>';
    
    var lang_Required = '<?php echo __(ValidationMessages::REQUIRED); ?>';
    
//]]>    
</script>    
    