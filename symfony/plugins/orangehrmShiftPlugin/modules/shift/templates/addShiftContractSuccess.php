<?php use_javascripts_for_form($shiftContractsForm) ?>
<?php use_stylesheets_for_form($shiftContractsForm) ?>
<?php use_javascripts_for_form($allocateForm) ?>
<?php use_stylesheets_for_form($allocateForm) ?>

<!-- 约束列表 -->
<?php use_javascripts_for_form($contranctListForm) ?>
<?php use_stylesheets_for_form($contranctListForm) ?>

<?php use_javascripts_for_form($contranctTypeForm) ?>
<?php use_stylesheets_for_form($contranctTypeForm) ?>

<?php //use_javascripts_for_form($minEmpSkillLevelForm) ?>
<?php //use_stylesheets_for_form($minEmpSkillLevelForm) ?>

<?php //use_javascripts_for_form($minumumSupervisorForm) ?>
<?php //use_stylesheets_for_form($minumumSupervisorForm) ?>

<?php //use_javascripts_for_form($maximumSupervisorForm) ?>
<?php //use_stylesheets_for_form($maximumSupervisorForm) ?>

<?php echo javascript_include_tag(plugin_web_path('orangehrmShiftPlugin', 'js/workShiftSuccess')); ?>
<?php
$haveWorkExperience = count($shiftContractsForm->shiftsByDate)>0;
?>
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

 #sidebar{
    margin-top: -18px;
 }

</style>

<div class="box pimPane">

    <?php 
        $form = $shiftConstractsForm;
        echo include_component('shift', 'shiftLeftMenu', array('schedule'=>$schedule_id,'shift'=>$shift_id,'form' => $form));
    ?>
   
        <div id="changeWorkExperience">
            <div class="head">
                <h1 id="headChangeWorkExperience"><?php echo __('排班信息'); ?></h1>
            </div>
            <div class="inner">
                <form id="frmWorkExperience" action="<?php echo url_for('shift/saveShiftContract?option=save'); ?>" method="post">
                    <?php echo $shiftContractsForm['_csrf_token']; ?>
                    <?php //echo $shiftContractsForm['emp_number']->render(); ?>
                    <?php echo $shiftContractsForm["shiftId"]->render(); ?>
                    <?php echo $shiftContractsForm["scheduleID"]->render(); ?>

                    <fieldset>
                        <ol>
                            <li>
                                <?php echo $shiftContractsForm['name']->renderLabel(__('排班名') . ' <em>*</em>'); ?>
                                <?php echo $shiftContractsForm['name']->render(array("class" => "formInputText", "maxlength" => 100)); ?>
                            </li>
                            <li>
                                <?php echo $shiftContractsForm['shiftDays']->renderLabel(__('选择时间') . ' <em>*</em>'); ?>
                                <?php echo $shiftContractsForm['shiftDays']->render(array("class" => "formInputText", "maxlength" => 100)); ?>
                            </li>
                            <li>
                                <?php echo $shiftContractsForm['shiftType']->renderLabel(__('排班类型') . ' <em>*</em>'); ?>
                                <?php echo $shiftContractsForm['shiftType']->render(array("class" => "formInputText", "maxlength" => 100)); ?>
                            </li>
                            <li>
                                <?php echo $shiftContractsForm['start_time']->renderLabel(__('开始时间') . ' <em>*</em>'); ?>
                                <?php echo $shiftContractsForm['start_time']->render(array("class" => "formInputText", "maxlength" => 100)); ?>
                            </li>
                            <li>
                                <?php echo $shiftContractsForm['end_time']->renderLabel(__('开始时间') . ' <em>*</em>'); ?>
                                <?php echo $shiftContractsForm['end_time']->render(array("class" => "formInputText", "maxlength" => 100)); ?>
                            </li>
                            <li>
                                <?php echo $shiftContractsForm['required_employee']->renderLabel(__('所需人数') . ' <em>*</em>'); ?>
                                <?php echo $shiftContractsForm['required_employee']->render(array("class" => "formInputText", "maxlength" => 100)); ?>
                            </li>
                            <li>
                                <?php echo $shiftContractsForm['status']->renderLabel(__('状态') . ' <em>*</em>'); ?>
                                <?php echo $shiftContractsForm['status']->render(array("class" => "formInputText", "maxlength" => 100)); ?>
                            </li>
                            <li class="largeTextBox">
                                <?php echo $shiftContractsForm['comments']->renderLabel(__('Comment')); ?>
                                <?php echo $shiftContractsForm['comments']->render(array("class" => "formInputText")); ?>
                            </li>
                            <li class="required">
                                <em>*</em> <?php echo __(CommonMessages::REQUIRED_FIELD); ?>
                            </li>
                        </ol>
                        <p>
                            <input type="button" class="" id="btnWorkExpSave" value="<?php echo __("Save"); ?>" />
                            <?php if (($haveWorkExperience)){ ?>
                            <input type="button" class="reset" id="btnWorkExpCancel" value="<?php echo __("Cancel"); ?>" />
                            <?php } ?>
                        </p>
                    </fieldset>
                </form>
            </div>
        </div> <!-- changeWorkExperience  -->

        
  <!-- miniList-sectionWorkExperience -->
    
    <!-- this is education section -->

     <!-- 分配雇员 -->
    <?php
    include_partial('allocate', array( 'form' => $allocateForm, 
        'section' => $section, 'listForm'=>$listForm));
    ?>
   
    <?php
    //include_partial('contranctType', array( 'form' => $contranctTypeForm, 
        //'section' => $section, 'listForm'=>$listForm));
    ?>

     <?php
    //include_partial('contranctList', array( 'form' => $contranctListForm, 
        //'section' => $section, 'listForm'=>$listForm));
    ?>


    <?php
    // include_partial('minEmpSkillLevel', array( 'form' => $minEmpSkillLevelForm, 
    //     'section' => $section, 'listForm'=>$listForm));
    ?>
    

    <?php
    // include_partial('minumumSupervisor', array( 'form' => $minumumSupervisorForm, 
    //     'section' => $section, 'listForm'=>$listForm));
    ?>

    <?php
    // include_partial('maximumSupervisor', array( 'form' => $maximumSupervisorForm, 
    //     'section' => $section, 'listForm'=>$listForm));
    ?>

</div> <!-- Box -->

<script type="text/javascript">
    //<![CDATA[
    var fileModified = 0;
    var lang_addWorkExperience = "<?php echo __('修改排班信息'); ?>";
    var lang_editWorkExperience = "<?php echo __('修改排班信息'); ?>";
    var lang_companyRequired = "<?php echo __(ValidationMessages::REQUIRED); ?>";
    var lang_jobTitleRequired = "<?php echo __(ValidationMessages::REQUIRED); ?>";
    var lang_invalidDate = '<?php echo __(ValidationMessages::DATE_FORMAT_INVALID, 
            array('%format%' => str_replace('yy', 'yyyy', get_datepicker_date_format($sf_user->getDateFormat())))); ?>';
    var lang_commentLength = "<?php echo __(ValidationMessages::TEXT_LENGTH_EXCEEDS, array('%amount%' => 200)); ?>";
    var lang_fromDateLessToDate = "<?php echo __('To date should be after From date'); ?>";
    var lang_selectWrkExprToDelete = "<?php echo __(TopLevelMessages::SELECT_RECORDS); ?>";
    var lang_jobTitleMaxLength = "<?php echo __(ValidationMessages::TEXT_LENGTH_EXCEEDS, array('%amount%' => 100)); ?>";
    var lang_companyMaxLength = "<?php echo __(ValidationMessages::TEXT_LENGTH_EXCEEDS, array('%amount%' => 100)); ?>";
    var datepickerDateFormat = '<?php echo get_datepicker_date_format($sf_user->getDateFormat()); ?>';
   
    //]]>
</script>

<script type="text/javascript">
    // var employeeList = <?php //echo $form->getEmployeeListAsJson();?>;
    // var workShiftList = <?php //echo $form->getWorkShiftListAsJson();?>;


    var activateShiftUrl = '<?php echo url_for('shift/addShiftContract?shift_id=' . $shift_id); ?>'
        
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
    // $("#frmWorkExperience").show();

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
        $("#changeWorkExperience").show();
        $("#workExpRequiredNote").show();
        //获取shiftID值
        var id =  $(this).find("a").eq(0).attr("value");
        var shiftName=$(this).find("a").eq(0).text();
        var shiftType=$("#shifttype_"+id).val();
        var shiftDate=$("#shiftdate_"+id).val();
        var start_time=$("#shiftstart_"+id).val();
        var end_time=$("#shiftend_"+id).val();
        var schedule_id=$("#schedule_"+id).val();
        var employe_size=$("#shiftrequireemploy_"+id).val();
        var status=$("#status_"+id).val();

        $("#workShift_name").val(shiftName);
        $("#workShift_shiftType").val(shiftType);
        $("#workShift_shiftDays").val(shiftDate);
        $("#workShift_start_time").val(start_time);
        $("#workShift_end_time").val(end_time);
        $("#workShift_shiftId").val(id);
        $("#workShift_scheduleID").val(schedule_id);
        $("#workShift_status").val(status);
        $("#workShift_required_employee").val(employe_size);



        var shift_id=id;

        var array = new Array();

        $.ajax({
                type: 'POST',
                url: activateShiftUrl,
                data: "shift_id="+shift_id,
                async: false,
                dataType:"json",

                success:function(data){

                    // $('#dependent_end_time').val(data.end_time);
                    // $('#dependent_start_time').val(data.start_time);
                }
        });


    });
});
</script>