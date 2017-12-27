<?php use_javascripts_for_form($form); ?>
<?php use_stylesheets_for_form($form); ?>

<?php
$numDependents = $shift_list->count();

$haveDependents = $numDependents > 0;
?>

<?php if ($form->hasErrors()): ?>
<span class="error">
<?php
echo $form->renderGlobalErrors();


foreach($form->getWidgetSchema()->getPositions() as $widgetName) {
  echo $form[$widgetName]->renderError();
}
?>
</span>
<?php endif; ?>

<div class="box pimPane">
    
    <?php echo include_component('shift', 'pimLeftMenu', array('empNumber'=>$empNumber, 'schedule'=>$schedule_id,'form' => $form));?>
    
   
    <div id="addPaneDependent">
        <div class="head">
            <h1 id="heading"><?php echo __('创建任务'); ?></h1>
        </div>
        
        <div class="inner">
            <form name="frmEmpDependent" id="frmEmpDependent" method="post" action="<?php echo url_for("shift/updateShifts?schedule_id=$schedule_id"); ?>">
                <?php echo $form['_csrf_token']; ?>
                <?php echo $form["scheduleID"]->render(); ?>
                <?php echo $form["shiftId"]->render(); ?>
                <fieldset>
                    <ol>
                        <li>
                            <?php echo $form['name']->renderLabel(__('排班名') . ' <em>*</em>'); ?>
                            <?php echo $form['name']->render(array("class" => "formInputText", "maxlength" => 50)); ?>
                        </li>
                        <li>
                            <?php echo $form['shiftType']->renderLabel(__('排班类型') . ' <em>*</em>'); ?>
                            <?php echo $form['shiftType']->render(array("class" => "formInputText", "maxlength" => 50)); ?>
                            <a data-toggle='modal' href='#languageDialog' style='text-decoration: none; list-style: none;color:#33ac3f;font-size:14px;font-weight:bold;' class='averageAssign_deleteButton'> ＋</a>

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
                        <!--  -->
                        <li class="required">
                            <em>*</em><?php echo __(CommonMessages::REQUIRED_FIELD); ?>
                        </li>
                    </ol>

        
                    <p>
                        <input type="button" class="" name="btnSaveDependent" id="btnSaveDependent" value="<?php echo __("Save"); ?>"/>
                        <input type="button" id="btnCancel" class="reset" value="<?php echo __("Cancel"); ?>"/>

                    </p>

                </fieldset>
            </form>
        </div>

       <!-- Message for supported languages -->
        <div class="modal hide" id="languageDialog">
            <div class="modal-header">
                <a class="close" data-dismiss="modal">×</a>
                <h3><?php echo __('创建类型')?></h3>
            </div>
            <div class="modal-body">
                <p>
                <form name="frmEmpEmgContact" id="frmEmpEmgContact" method="post" action="<?php echo url_for('shift/updateShift?schedule_id=' . $schedule_id); ?>">
                    <?php echo $leavecommentForm['_csrf_token']; ?>
                    <?php echo $leavecommentForm["scheduleID"]->render(); ?>
                    <?php echo $leavecommentForm["shiftTypeID"]->render(); ?>
                    <fieldset>
                        <ol>
                            <li>
                                <?php echo $leavecommentForm['shiftTypeName']->renderLabel(__('Shift Type Name') . ' <em>*</em>'); ?>
                                <?php echo $leavecommentForm['shiftTypeName']->render(array("class" => "formInputText", "maxlength" => 50)); ?>
                            </li>
                
                            <li>
                                <?php echo $leavecommentForm['start_time']->renderLabel(__('Start Time') . ' <em>*</em>'); ?>
                                <?php echo $leavecommentForm['start_time']->render(array("class" => "formInputText", "maxlength" => 50)); ?>
                            </li>
                            <li>
                                <?php echo $leavecommentForm['end_time']->renderLabel(__('End Time') . ' <em>*</em>'); ?>
                                <?php echo $leavecommentForm['end_time']->render(array("class" => "formInputText", "maxlength" => 50)); ?>
                            </li>

                            <li>
                                <?php echo $leavecommentForm['required_employee']->renderLabel(__('所需人数') . ' <em>*</em>'); ?>
                                <?php echo $leavecommentForm['required_employee']->render(array("class" => "formInputText", "maxlength" => 50)); ?>
                            </li>

                            <li>
                                <?php echo $leavecommentForm['relationshipType']->renderLabel(__('应用类型') . ' <em>*</em>'); ?>
                                <?php echo $leavecommentForm['relationshipType']->render(array("class" => "formInputText", "maxlength" => 50)); ?>
                            </li>
                           
                            <li>
                                <?php echo $leavecommentForm['status']->renderLabel(__('Status') . ' <em>*</em>'); ?>
                                <?php echo $leavecommentForm['status']->render(array("class" => "formInputText", "maxlength" => 25)); ?>
                            </li>
                          
                            
                            <li class="required">
                                <em>*</em><?php echo __(CommonMessages::REQUIRED_FIELD); ?>
                            </li>
                        </ol>
                        <p>
                            <input type="button" class="" name="btnSaveEContact" id="btnSaveEContact" value="<?php echo __("Save"); ?>"/>
                        </p>
                    </fieldset>
                </form>       
                </p>
              </div>
            
        </div>
        <!-- End-of-msg -->
    </div> 

    
    <div class="miniList" id="listing">
        <div class="head">
            <h1><?php echo __("排班列表"); ?></h1>
        </div>
        
        <div class="inner">
     
            
            <?php include_partial('global/flash_messages', array('prefix' => 'viewDependents')); ?>
            
            <form name="frmEmpDelDependents" id="frmEmpDelDependents" method="post" action="<?php echo url_for('shift/deleteDependents?empNumber=' . $empNumber); ?>">
                <?php echo $deleteForm['_csrf_token']->render(); ?>
                <?php echo $deleteForm['empNumber']->render(); ?>
                <p id="listActions">
                  
                    <input type="button" class="" id="btnAddDependent" value="<?php echo __("Add"); ?>"/>
               
                    <input type="button" class="delete" id="delDependentBtn" value="<?php echo __("Delete"); ?>"/>

                    <input type="button" id="btnXml" class="" value="<?php echo __("查看排班结果"); ?>"/>
                  
                </p>
                <table id="dependent_list" class="table hover">
                <!-- 标题 -->
                    <thead>
                        <tr>
                            <th class="check" style="width:2%"><input type='checkbox' id='checkAll' class="checkbox" /></th>
                    
                            
                            <th><?php echo __("排班日期"); ?></th>
                            
                            <th><?php echo __("该天班次"); ?></th>
                    
                           
                        </tr>
                    </thead>
                    <!-- 内容部分 -->
                    <tbody>
                        <?php

                        if (!$haveDependents) { ?>
                        <tr>
                            <td class="check"></td>
                            <td><?php echo __(TopLevelMessages::NO_RECORDS_FOUND); ?></td>
                            <td></td>
                        </tr>
                        <?php } else { ?>                        
                        <?php
                        $row = 0;

                        foreach ($shiftdates_list as $dependent) :


                            $cssClass = ($row % 2) ? 'even' : 'odd';
                            echo '<tr class="' . $cssClass . '">';
                            echo "<td class='check'><input type='checkbox' class='checkbox' name='chkdependentdel[]' value='" . $shift_id . "'/></td>";
                            ?>
                            <td class="dependentName">
                                <?php echo $dependent['shiftDate']; ?>
                            </td>

                            
                        
                       
                            <td> 
                                <?php foreach($dependent['shiftList'] as $shift){

                                        $shift_id=$shift['id'];
                                        $shift_type_id=$shift['shift_type_id'];
                                        $shift_date_id=$shift['shiftdate_id'];
                                        $start_time=substr($shift['start_time'],0,5);
                                        $end_time=substr($shift['end_time'],0,5);

                                ?>

                                    <input type="hidden" id="scheduleID_<?php echo $shift_id; ?>" value="<?php echo $dependent['scheduleId']?>" />
                                    <input type="hidden" id="shiftID_<?php echo $shift_id; ?>" value="<?php echo $shift_id?>" />

                                    <input type="hidden" id="shiftName_<?php echo $shift_id; ?>" value="<?php echo $dependent->name?>" />
                                    <input type="hidden" id="shiftType_<?php echo $shift_id; ?>" value="<?php echo $shift_type_id;?>" />
                                    <input type="hidden" id="shiftDate_<?php echo $shift_id; ?>" value="<?php echo $shift_date_id?>" />
                                    <input type="hidden" id="end_time_<?php echo $shift_id; ?>" value="<?php echo $end_time?>" />
                                    <input type="hidden" id="start_time_<?php echo $shift_id; ?>" value="<?php echo $start_time;?>" />
                                    <input type="hidden" id="required_employee<?php echo $shift_id; ?>" value="<?php echo $shift['required_employee'];?>" />


                                    <a id="<?php echo $shift_id; ?>" value="<?php echo $shift_id?>"><?php echo $shift['name']; ?></a>
                                <?php }?>
                            </td>
                         
                            <?php
                            echo '</tr>';
                            $row++;
                            endforeach;
                        ?>
                        <?php } ?>
                    </tbody>
                    <p>
                    
                </p>
                </table>                 
            </form>
        </div>
    </div> 
    
    <!-- Attachments & Custom Fields -->
    <?php
    echo include_component('pim', 'customFields', array('empNumber'=>$empNumber, 'screen' => CustomField::SCREEN_DEPENDENTS));
    echo include_component('pim', 'attachments', array('empNumber'=>$empNumber, 'screen' => EmployeeAttachment::SCREEN_DEPENDENTS));
    ?>
    
</div> <!-- Box -->

<script type="text/javascript">

    var activateShiftUrl = '<?php echo url_for('shift/createShift?schedule_id=' . $schedule_id); ?>';
    $('#delDependentBtn').attr('disabled', 'disabled');
    function clearAddForm() {
        $('#dependent_shiftId').val('');
        $('#dependent_name').val('');
        $('#dependent_relationshipType').val('');
        $('#dependent_relationship').val('');
        $('div#addPaneDependent label.error').hide();
        $('div#messagebar').hide();
    }

    function addEditLinks() {
        removeEditLinks();
        $('#dependent_list tbody td.dependentName').wrapInner('<a href="#"/>');
    }

    function removeEditLinks() {
        $('#dependent_list tbody td.dependentName a').each(function(index) {
            $(this).parent().text($(this).text());
        });
    }

    function hideShowRelationshipOther() {
        if ($('#dependent_relationshipType').val() == '4') {
            $('#relationshipDesc').show();
        } else {
            $('#relationshipDesc').hide();
        }
    }

    function timedependtype(){


        var shiftTypeID=$("#dependent_shiftType").val();
        var array = new Array();

        $.ajax({
                type: 'POST',
                url: activateShiftUrl,
                data: "shiftTypeID="+shiftTypeID,
                async: false,
                dataType:"json",

                success:function(data){

                    $('#dependent_end_time').val(data.end_time);
                    $('#dependent_start_time').val(data.start_time);
                    $('#dependent_required_employee').val(data.required_employee);
                }
            });

    }

    $(document).ready(function() {

        
        $('#btnXml').click(function() {
            location.href = "<?php echo url_for('shift/createXml?schedule_id='.$schedule_id) ?>";
        });
        // $('#addPaneDependent').hide();
        <?php  if (!$haveDependents){?>
        $(".check").hide();
        <?php } ?>
        
        $("#checkAll").click(function(){
            if($("#checkAll:checked").attr('value') == 'on') {
                $(".checkbox").attr('checked', 'checked');
            } else {
                $(".checkbox").removeAttr('checked');
            }
            
            if($('.checkbox:checkbox:checked').length > 0) {
                $('#delDependentBtn').removeAttr('disabled');
            } else {
                $('#delDependentBtn').attr('disabled', 'disabled');
            }
        });

        $(".checkbox").click(function() {
            $("#checkAll").removeAttr('checked');
            if(($(".checkbox").length - 1) == $(".checkbox:checked").length) {
                $("#checkAll").attr('checked', 'checked');
            }
            
            if($('.checkbox:checkbox:checked').length > 0) {
                $('#delDependentBtn').removeAttr('disabled');
            } else {
                $('#delDependentBtn').attr('disabled', 'disabled');
            }
        });

        hideShowRelationshipOther();
        //展开添加类型按钮
        $('#frmEmpDelDependents a').live('click', function() {
            $("#heading").text("<?php echo __("Edit Dependent");?>");
            var row = $(this).closest("tr");
            var shiftId = $(this).attr("id");

       
            var name = $(this).text();
            var relationshipType = $("#shiftType_" + shiftId).val();
            var shiftDate = $("#shiftDate_" + shiftId).val();
            var end_time = $("#end_time_"+ shiftId).val();

            var start_time = $("#start_time_"+ shiftId).val();
            var schedule_id = $("#scheduleID_"+ shiftId).val();
            var relationType = $("#required_employee"+ shiftId).val();

            $('#dependent_shiftId').val(shiftId);
            $('#dependent_name').val(name);
            $('#dependent_shiftType').val(relationshipType);
            $('#dependent_shiftDays').val(shiftDate);
            $('#dependent_end_time').val(end_time);
            $('#dependent_start_time').val(start_time);
            $('#dependent_scheduleID').val(schedule_id);
            $('#dependent_required_employee').val(relationType);

            $('div#messagebar').hide();
            hideShowRelationshipOther();
           
            $('#listActions').hide();
            $('#dependent_list .check').hide();
            $('#addPaneDependent').css('display', 'block');

            $(".paddingLeftRequired").show();
            $('#btnCancel').show();

        });

        $('#dependent_relationshipType').change(function() {
            hideShowRelationshipOther();
        });
        $('#dependent_shiftType').change(function() {
            timedependtype();
        });

        // 隐藏添加类型窗口
        $('#btnCancel').click(function() {
            clearAddForm();
            $('#addPaneDependent').css('display', 'none');
            $('#listActions').show();
            $('#dependent_list .check').show();
           
            addEditLinks();
          
            $('div#messagebar').hide();
            $(".paddingLeftRequired").hide();
        });

    

        

        // Add a emergency contact
        $('#btnAddDependent').click(function() {
            $("#heading").text("<?php echo __("创建任务");?>");
            clearAddForm();

            // Hide list action buttons and checkbox
            $('#listActions').hide();
            $('#dependent_list .check').hide();
            removeEditLinks();
            $('div#messagebar').hide();
            
            hideShowRelationshipOther();

            $('#addPaneDependent').css('display', 'block');

            $(".paddingLeftRequired").show();

        });

        
        $("#frmEmpDependent").validate({

            rules: {
                'dependent[name]' : {required:true, maxlength:100},
                'dependent[shiftType]' : {required: true},
                'dependent[shiftDays]' : {required: true},

                'dependent[start_time]' : {required: true},
                'dependent[end_time]' : {required: true},
                'dependent[required_employee]' : {required: true},
                'dependent[relationshipType]' : {required: true},
               
                
                maxlength:100
            },
            messages: {
                'dependent[name]': {
                    required:'<?php echo __(ValidationMessages::REQUIRED) ?>',
                    maxlength: '<?php echo __(ValidationMessages::TEXT_LENGTH_EXCEEDS,array('%amount%' => 100)) ?>'
                },
                'dependent[shiftType]': {
                    required:'<?php echo __(ValidationMessages::REQUIRED) ?>'
                },
                'dependent[shiftDays]': {
                    required:'<?php echo __(ValidationMessages::REQUIRED) ?>'
                },
                'dependent[start_time]': {
                    required:'<?php echo __(ValidationMessages::REQUIRED) ?>'
                },
                'dependent[end_time]': {
                    required:'<?php echo __(ValidationMessages::REQUIRED) ?>'
                },
                'dependent[required_employee]': {
                    required:'<?php echo __(ValidationMessages::REQUIRED) ?>'
                },
                'dependent[relationshipType]': {
                    required:'<?php echo __(ValidationMessages::REQUIRED) ?>'
                },
                
            }
        });

        $("#frmEmpEmgContact").validate({

            rules: {
                'emgcontacts[end_time]' : {required:true, maxlength:100},
                'emgcontacts[shiftTypeName]' : {required:true, maxlength:100},
                'emgcontacts[abbreviation]' : {required:true, maxlength:100},
                'emgcontacts[start_time]' : {required:true, maxlength:100},
                'emgcontacts[relationship]' : {required: true, maxlength:100},
                'emgcontacts[status]' : {required: true, maxlength:100},
    
            },
            messages: {
                'emgcontacts[end_time]': {
                    required:'<?php echo __(ValidationMessages::REQUIRED); ?>',
                    maxlength: '<?php echo __(ValidationMessages::TEXT_LENGTH_EXCEEDS,array('%amount%' => 100)); ?>'
                },
     
                'emgcontacts[shiftTypeName]': {
                    required:'<?php echo __(ValidationMessages::REQUIRED); ?>',
                    maxlength: '<?php echo __(ValidationMessages::TEXT_LENGTH_EXCEEDS,array('%amount%' => 100)); ?>'
                },
                'emgcontacts[abbreviation]': {
                    required:'<?php echo __(ValidationMessages::REQUIRED); ?>',
                    maxlength: '<?php echo __(ValidationMessages::TEXT_LENGTH_EXCEEDS,array('%amount%' => 100)); ?>'
                },
                'emgcontacts[start_time]': {
                    required:'<?php echo __(ValidationMessages::REQUIRED); ?>',
                    maxlength: '<?php echo __(ValidationMessages::TEXT_LENGTH_EXCEEDS,array('%amount%' => 100)); ?>'
                },
                'emgcontacts[relationship]': {
                    required:'<?php echo __(ValidationMessages::REQUIRED); ?>',
                    maxlength: '<?php echo __(ValidationMessages::TEXT_LENGTH_EXCEEDS,array('%amount%' => 100)); ?>'
                },
                'emgcontacts[status]' : {
                    phone:'<?php echo __(ValidationMessages::TP_NUMBER_INVALID); ?>',
                    validContactPhone:'<?php echo __('At least one phone number is required'); ?>',
                    maxlength: '<?php echo __(ValidationMessages::TEXT_LENGTH_EXCEEDS,array('%amount%' => 100)); ?>'
                },
    
            }
        });

        $('#delDependentBtn').click(function() {
            var checked = $('#frmEmpDelDependents input:checked').length;

            if (checked == 0) {
                $('div#messagebar').show();
                $("#messagebar").attr('class', "messageBalloon_notice");
                $("#messagebar").text('<?php echo __(TopLevelMessages::SELECT_RECORDS); ?>');
            } else {
                $('#frmEmpDelDependents').submit();
            }
        });

        $('#btnSaveDependent').click(function() {
            $('#frmEmpDependent').submit();
        });
        $('#btnSaveShiftType').click(function() {
            $('#shifTypeDependent').submit();
        });

        $('#btnSaveEContact').click(function() {
            $('#frmEmpEmgContact').submit();
        });


});
//]]>
</script>

