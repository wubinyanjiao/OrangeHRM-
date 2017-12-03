<?php use_javascript(plugin_web_path('orangehrmAdminPlugin', 'js/workShiftSuccess')); ?>
<link href="http://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
<style>
    
 .accordion {
    width: 100%;
    max-width: 360px;
    margin: 30px auto 20px;
    background: #FFF;
    -webkit-border-radius: 4px;
    -moz-border-radius: 4px;
    border-radius: 4px;
 }

.accordion .link {
    cursor: pointer;
    display: block;
    padding: 15px 15px 15px 42px;
    color: #4D4D4D;
    font-size: 14px;
    font-weight: 700;
    border-bottom: 1px solid #CCC;
    position: relative;
    -webkit-transition: all 0.4s ease;
    -o-transition: all 0.4s ease;
    transition: all 0.4s ease;
}

.accordion li:last-child .link {
    border-bottom: 0;
}

.accordion li i {
    position: absolute;
    top: 16px;
    left: 12px;
    font-size: 18px;
    color: #595959;
    -webkit-transition: all 0.4s ease;
    -o-transition: all 0.4s ease;
    transition: all 0.4s ease;
}

.accordion li i.fa-chevron-down {
    right: 12px;
    left: auto;
    font-size: 16px;
}

.accordion li.open .link {
    color: #b63b4d;
}

.accordion li.open i {
    color: #b63b4d;
}
.accordion li.open i.fa-chevron-down {
    -webkit-transform: rotate(180deg);
    -ms-transform: rotate(180deg);
    -o-transform: rotate(180deg);
    transform: rotate(180deg);
}

/**
 * Submenu
 -----------------------------*/
 .submenu {
    display: none;
    /*background: #444359;*/
    font-size: 14px;
 }

 .submenu li {
    /*border-bottom: 1px solid #4b4a5e;*/
 }

 .submenu a {
    display: block;
    text-decoration: none;
    /*color: #d9d9d9;*/
    padding: 12px;
    padding-left: 42px;
    -webkit-transition: all 0.25s ease;
    -o-transition: all 0.25s ease;
    transition: all 0.25s ease;
 }

 .submenu a:hover {
    background: #b63b4d;
    color: #FFF;
 }

 .pimPane .box{
    position:static;
 }

</style>

<div class="box pimPane">

<?php echo include_component('shift', 'shiftLeftMenu', array('empNumber'=>$empNumber, 'schedule'=>$schedule_id,'shift'=>$shift_id,'form' => $form));?>
<div class="box" id="workShift" <?php echo $hideForm ? "style='display:none'" : "";?> >
    <div class="head">
        <h1 id="workShiftHeading"><?php echo __("Work Shift"); ?></h1>
    </div>
    
    <div class="inner">

        <?php include_partial('global/form_errors', array('form' => $form)); ?>
        
        <form name="frmWorkShift" id="frmWorkShift" method="post" action="<?php echo url_for('shift/allocateShift'); ?>" >

            <?php echo $form['_csrf_token']; ?>
            <?php echo $form->renderHiddenFields(); ?>
            
            <fieldset>
                <ol>                    
                    <li>
                            <?php echo $form['name']->renderLabel(__('轮班名') . ' <em>*</em>'); ?>
                            <?php echo $form['name']->render(array("class" => "formInputText", "maxlength" => 50)); ?>
                        </li>
                        <li>
                            <?php echo $form['shiftType']->renderLabel(__('轮班类型') . ' <em>*</em>'); ?>
                            <?php echo $form['shiftType']->render(array("class" => "formInputText", "maxlength" => 50)); ?>
                        </li>
            
                         <li>
                            <?php echo $form['shiftDays']->renderLabel(__('选择日期') . ' <em>*</em>'); ?>
                            <?php echo $form['shiftDays']->render(array("class" => "formInputText", "maxlength" => 50)); ?>
                        </li>
                        <li>
                            <?php echo $form['start_time']->renderLabel(__('开始时间') . ' <em>*</em>'); ?>
                            <?php echo $form['start_time']->render(array("class" => "formInputText", "maxlength" => 50)); ?>
                        </li>
                        <li>
                            <?php echo $form['end_time']->renderLabel(__('结束时间') . ' <em>*</em>'); ?>
                            <?php echo $form['end_time']->render(array("class" => "formInputText", "maxlength" => 50)); ?>
                        </li>
                        <li>
                            <?php echo $form['required_employee']->renderLabel(__('排班所需要人数') . ' <em>*</em>'); ?>
                            <?php echo $form['required_employee']->render(array("class" => "formInputText", "maxlength" => 50)); ?>
                        </li>
                      
                        <li >
                            <?php echo $form['status']->renderLabel(__('状态') . ' <em>*</em>'); ?>
                            <?php echo $form['status']->render(array("class" => "formInputText", "maxlength" => 50)); ?>
                        </li>
                                    
                    <p id="selectManyTable">
                        <table border="0" width="45%" class="">
                            <tbody>
                                <tr>
                                    <td width="35%" style="font-weight:bold; height: 20px">
                                        <?php echo __("Available Employees"); ?>
                                    </td>
                                    <td width="30%"></td>
                                    <td width="35%" style="font-weight:bold;"><?php echo __("Assigned Employees"); ?></td>
                                </tr>
                                <tr>
                                    <td>
                                        <?php echo $form['availableEmp']->render(array("class" => "selectMany", "size" => 10, "style" => "width: 100%")); ?>    
                                    </td>
                                    <td align="center" style="vertical-align: middle">
                                        <a href="#" class="" id="btnAssignEmployee"><?php echo __("Add"). " >>"; ?></a><br /><br />
                                        <a href="#" class="delete" id="btnRemoveEmployee"><?php echo __("Remove") . " <<"; ?></a>
                                    </td>
                                    <td>
                                        <?php echo $form['assignedEmp']->render(array("class" => "selectMany", "size" => 10, "style" => "width: 100%")); ?>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </p>
                    <li class="required">
                        <em>*</em> <?php echo __(CommonMessages::REQUIRED_FIELD); ?>
                    </li>                    
                </ol>
                
                <p>
                    <input type="button" class="" name="btnSave" id="btnSave" value="<?php echo __("Save"); ?>"/>
                    <input type="button" class="reset" name="btnCancel" id="btnCancel" value="<?php echo __("Cancel"); ?>"/>
                </p>
                
            </fieldset>
            
        </form>
        
    </div>
    
</div>

<div id="customerList" class="allocate">
    <?php include_component('core', 'ohrmList', $parmetersForListCompoment); ?>
</div>



<!-- Confirmation box HTML: Begins -->
<div class="modal hide" id="deleteConfModal">
    <div class="modal-header">
        <a class="close" data-dismiss="modal">×</a>
        <h3><?php echo __('OrangeHRM - Confirmation Required'); ?></h3>
    </div>
    <div class="modal-body">
        <p><?php echo __(CommonMessages::DELETE_CONFIRMATION); ?></p>
    </div>
    <div class="modal-footer">
        <input type="button" class="btn" data-dismiss="modal" id="dialogDeleteBtn" value="<?php echo __('Ok'); ?>" />
        <input type="button" class="btn reset" data-dismiss="modal" value="<?php echo __('Cancel'); ?>" />
    </div>
</div>
<!-- Confirmation box HTML: Ends -->
</div>

<script type="text/javascript">
    var employeeList = <?php echo $form->getEmployeeListAsJson();?>;
    var workShiftList = <?php echo $form->getWorkShiftListAsJson();?>;
        
    var defaultStartTime = '<?php echo $default['start_time'];?>';
    var defaultEndTime = '<?php echo $default['end_time'];?>';

    var workShiftInfoUrl = "<?php echo url_for("admin/getWorkShiftInfoJson?id="); ?>";
    var workShiftEmpInfoUrl = "<?php echo url_for("admin/getWorkShiftEmpInfoJson?id="); ?>";

    var lang_Required = '<?php echo __(ValidationMessages::REQUIRED); ?>';
    var lang_exceed50Charactors = '<?php echo __(ValidationMessages::TEXT_LENGTH_EXCEEDS, array('%amount%' => 50)); ?>';
    var lang_addWorkShift = "<?php echo __("Add Work Shift"); ?>";
    var lang_editWorkShift = "<?php echo __("Edit Work Shift"); ?>";
    var lang_nameAlreadyExist = '<?php echo __(ValidationMessages::ALREADY_EXISTS); ?>';
        var lang_FromTimeLessThanToTime = "<?php echo __('From time should be less than To time'); ?>";
$(function() {
    $("#workShift").show();
    //判断是否为空
    var Accordion = function(el, multiple) {
        this.el = el || {};
        this.multiple = multiple || false;

        // Variables privadas
        var links = this.el.find('.link');
        // Evento
        links.on('click', {el: this.el, multiple: this.multiple}, this.dropdown)
    }

    Accordion.prototype.dropdown = function(e) {
        var $el = e.data.el;
            $this = $(this),
            $next = $this.next();

        $next.slideToggle();
        $this.parent().toggleClass('open');

        if (!e.data.multiple) {
            $el.find('.submenu').not($next).slideUp().parent().removeClass('open');
        };
    }   

    var accordion = new Accordion($('#sidenav'), false);

   
    $(".submenu li").click(function(){
        $("#workShift").show();
        //获取shiftID值
        var id =  $(this).find("a").eq(0).attr("value");
        var shiftName=$(this).find("a").eq(0).text();
        var shiftType=$("#shifttype_"+id).val();
        var shiftDate=$("#shiftdate_"+id).val();
        var start_time=$("#shiftstart_"+id).val();
        var end_time=$("#shiftend_"+id).val();
        var schedule_id=$("#schedule_"+id).val();

        $("#workShift_name").val(shiftName);
        $("#workShift_shiftType").val(shiftType);
        $("#dependent_dateOfBirth").val(shiftDate);
        $("#workShift_from_time").val(start_time);
        $("#workShift_to_time").val(end_time);
        $("#workShiftId").val(id);
        $("#scheduleID").val(schedule_id);

        // alert(shiftType);  
    });
});
</script>