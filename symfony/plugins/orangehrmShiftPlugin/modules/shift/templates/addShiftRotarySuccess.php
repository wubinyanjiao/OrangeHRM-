
<?php
$numContacts = count($shiftRosters);
$haveContacts = $numContacts > 0;

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
<style type="text/css">
 form ol li select{
    width:201px;
 }
</style>

<div class="box">
    
    <div id="addPaneEmgContact">
        <div class="head">
            <h1 id="emergencyContactHeading"><?php echo __('轮转'); ?></h1>
        </div>
        
        <div class="inner">

            <form name="frmEmpEmgContact" id="frmEmpEmgContact" method="post" action="<?php echo url_for('shift/updateRotary'); ?>">
                <?php echo $form['_csrf_token']; ?>
        
                <?php echo $form["RotaryID"]->render(); ?>
                <fieldset >
                    <ol style="border-bottom:none">
                        <li>
                            <?php echo $form['name']->renderLabel(__('轮转名称') . ' <em>*</em>'); ?>
                            <?php echo $form['name']->render(array("class" => "formInputText", "maxlength" => 50)); ?>
                        </li>

                        <li>
                            <?php echo $form['calFromDate']->renderLabel(__('轮转开始时间') . ' <em>*</em>'); ?>
                            <?php echo $form['calFromDate']->render(array("class" => "formInputText", "maxlength" => 50)); ?>
                        </li>
                    
                        <li>
                            <?php echo $form['calToDate']->renderLabel(__('轮转结束时间') . ' <em>*</em>'); ?>
                            <?php echo $form['calToDate']->render(array("class" => "formInputText", "maxlength" => 50)); ?>
                        </li>
           
                    </ol>
                     <ol>
                        <li class="line nameContainer">
                            <label for="Full_Name" class="hasTopFieldHelp"><?php echo __('轮转部门'); ?></label>
                            <ol class="fieldsInLine">

                                <li>
                                    <div class="fieldDescription"><em>*</em> <?php echo __('第一个部门'); ?></div>
                                    <?php echo $form['firDocument']->render(array("class" => "block default editable", "maxlength" => 30, "title" => __('Last Name'))); ?>
                                </li>
                                <li style="margin-bottom: 13px">
                                    <div class="fieldDescription"><?php echo __('第二个部门)'); ?></div>
                                    <?php echo $form['secDocument']->render(array("class" => "block default editable", "maxlength" => 30, "title" => __('Middle Name'))); ?>
                                </li>
                                
                                <li>
                                    <div class="fieldDescription"><em>*</em> <?php echo __('第三个部门'); ?></div>
                                    <?php echo $form['thirDocument']->render(array("class" => "block default editable", "maxlength" => 30, "title" => __('Last Name'))); ?>
                                </li>
                            </ol>      
                        </li>
                    </ol>
                    <p>
                        <input type="button" class="" name="btnSaveEContact" id="btnSaveEContact" value="<?php echo __("Save"); ?>"/>
                        <input type="button" id="btnCancel" class="reset" value="<?php echo __("Cancel"); ?>"/>
                        <input type="button" class="" name="btnAddConstract" id="btnAddConstract" value="<?php echo __("选取规则"); ?>"/>
                    </p>
                </fieldset>
            </form>
        </div>
    </div> <!-- addPaneEmgContact -->
 
    <div class="miniList" id="listEmegrencyContact">
        <div class="head">
            <h1><?php echo __("轮转"); ?></h1>
        </div>
        
        <div class="inner">
            
            <?php include_partial('global/flash_messages', array('prefix' => 'viewEmergencyContacts')); ?>
            
            <form name="frmEmpDelEmgContacts" id="frmEmpDelEmgContacts" method="post" action="<?php echo url_for('pim/deleteEmergencyContacts?empNumber=' . $empNumber); ?>">
                <?php echo $deleteForm['_csrf_token']->render(); ?>
                <?php echo $deleteForm['empNumber']->render(); ?>
                
                <p id="listActions">
                    
                    <input type="button" class="" id="btnAddContact" value="<?php echo __("Add"); ?>"/>
                    
                   
                    <input type="button" class="delete" id="delContactsBtn" value="<?php echo __("Delete"); ?>"/>
         
                </p>
                
                <table id="emgcontact_list" class="table hover">
                    <thead>
                        <tr>
                            
                            <th class="check" style="width:2%"><input type='checkbox' id='checkAll'/></th>
                           
                            <th><?php echo __("ID"); ?></th>
                            <th class="emgContactName"><?php echo __("轮转名"); ?></th>
                  
                            <th><?php echo __("开始时间"); ?></th>
                            <th><?php echo __("结束时间"); ?></th>
                            <th><?php echo __("第一个部门"); ?></th>
                            <th><?php echo __("第二个部门"); ?></th>
                            <th><?php echo __("第三个部门"); ?></th>
                            <th><?php echo __("查看轮转结果"); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (!$haveContacts) { ?>
                        <tr>
                            
                            <td class="check"></td>
                            
                            <td><?php echo __(TopLevelMessages::NO_RECORDS_FOUND); ?></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <?php } else {
                        $row = 0;
                        
                        foreach ($shiftRosters as $shiftRoster) :
                         
                            
                            $cssClass = ($row % 2) ? 'even' : 'odd';
                            echo '<tr class="' . $cssClass . '">';
                            echo "<td class='check'><input type='checkbox' class='checkbox' name='chkecontactdel[]' value='" . $shiftRoster->id . "'/></td>";

                          
                            ?>
                            <td id="<?php echo $shiftRoster['id']?>" value="<?php echo $shiftType['id']?>"><?php echo $shiftRoster->id;?></td>
                            <td class="emgContactName" valign="top">
                                <a href="#"><?php echo $shiftRoster->name; ?></a>
                            </td>
                            
                            <?php
                            // echo'<pre>';
                            // var_dump($jopdocument_list[$shiftRoster->secondDepartment]);
                            // exit;
                          
                            // echo "<td valigh='top'>" . $shiftRoster->name . '</td>';
                            echo "<td valigh='top'>" . $shiftRoster->dateFrom . '</td>';
                            echo "<td valigh='top'>" . $shiftRoster->dateTo . '</td>';
                            echo "<td valigh='top'>" . $jopdocument_list[$shiftRoster->firstDepartment]. '</td>';
                            echo "<td valigh='top'>" .  $jopdocument_list[$shiftRoster->secondDepartment] . '</td>';
                            echo "<td valigh='top'>" . $jopdocument_list[$shiftRoster->thirdDepartment] . '</td>';

                            ?>
                         
                 
                            <td><a class='btn1' href='<?php echo url_for("shift/getRotary?rotaryId=$shiftRoster->id"); ?>'>点击查看</a>
                            </td>
                        </tr>
                            <?php 
                            $row++;
                        endforeach;
                        } ?>
                    </tbody>
                </table>
            </form>
        </div>
    </div> <!-- miniList -->

    
</div> <!-- Box -->

<script type="text/javascript">

    $('#delContactsBtn').attr('disabled', 'disabled');
    
    function clearAddForm() {
        $('#emgcontacts_shiftTypeID').val('');
        $('#emgcontacts_name').val('');
    
        $('#emgcontacts_abbreviation').val('');
        $('#emgcontacts_end_time').val('');
        $('#emgcontacts_start_time').val('');
        $('#emgcontacts_status').val('');
       
        $('div#addPaneEmgContact label.error').hide();
        $('div#messagebar').hide();
    }

    function addEditLinks() {
        removeEditLinks();
        $('#emgcontact_list tbody td.emgContactName').wrapInner('<a href="#"/>');
    }

    function removeEditLinks() {
        $('#emgcontact_list tbody td.emgContactName a').each(function(index) {
            $(this).parent().text($(this).text());
        });
    }

    $(document).ready(function() {
        // $('#addPaneEmgContact').hide();
        <?php  if (!$haveContacts){?>
        $(".check").hide();
        <?php } ?>

        $("#checkAll").click(function(){
            if($("#checkAll:checked").attr('value') == 'on') {
                $(".checkbox").attr('checked', 'checked');
            } else {
                $(".checkbox").removeAttr('checked');
            }
            
            if($('.checkbox:checkbox:checked').length > 0) {
                $('#delContactsBtn').removeAttr('disabled');
            } else {
                $('#delContactsBtn').attr('disabled', 'disabled');
            }
        });

        $(".checkbox").click(function() {
            $("#checkAll").removeAttr('checked');
            if(($(".checkbox").length) == $(".checkbox:checked").length) {
                $("#checkAll").attr('checked', 'checked');
            }
            
            if($('.checkbox:checkbox:checked').length > 0) {
                $('#delContactsBtn').removeAttr('disabled');
            } else {
                $('#delContactsBtn').attr('disabled', 'disabled');
            }
        });
        // 展开添加类型框
        $('#frmEmpDelEmgContacts a').live('click', function() {
            var row = $(this).closest("tr");
            var id = row.find('input.checkbox:first').val();
            var name = $(this).text();
            var abbreviation = $("#abbreviation_"+ id).val();
            var end_time = $("#end_time_"+ id).val();
            var start_time = $("#start_time_"+ id).val();
            var schedule_id = $("#scheduleID_"+ id).val();
            var status = $("#status" + id).val();
       
            $('#emgcontacts_shiftTypeID').val(id);
            $('#emgcontacts_name').val(name);
        
            $('#emgcontacts_abbreviation').val(abbreviation);
            $('#emgcontacts_end_time').val(end_time);
            $('#emgcontacts_start_time').val(start_time);
            $('#emgcontacts_scheduleID').val(schedule_id);
            $('#emgcontacts_status').val(status);

            $(".paddingLeftRequired").show();
            $("#emergencyContactHeading").text("<?php echo __("Edit Emergency Contact");?>");
            $('div#messagebar').hide();

            $('#listActions').hide();
            $('#emgcontact_list .check').hide();
            $('#addPaneEmgContact').css('display', 'block');

        });

        // Cancel in add pane
        $('#btnCancel').click(function() {
            clearAddForm();
            $('#addPaneEmgContact').css('display', 'none');
            $('#listActions').show();
            $('#emgcontact_list .check').show();
            addEditLinks();
         
            $('div#messagebar').hide();
            $(".paddingLeftRequired").hide();
        });

        // Add a emergency contact
        $('#btnAddContact').click(function() {
            $("#emergencyContactHeading").text("<?php echo __("创建轮转");?>");
            $(".paddingLeftRequired").show();
            clearAddForm();

            // Hide list action buttons and checkbox
            $('#listActions').hide();
            $('#emgcontact_list .check').hide();
            removeEditLinks();
            $('div#messagebar').hide();

            $('#addPaneEmgContact').css('display', 'block');
        });

        /* Valid Contact Phone */
        $.validator.addMethod("validContactPhone", function(value, element) {
            if ( $('#emgcontacts_status').val() == '' && $('#emgcontacts_mobilePhone').val() == '' &&
                    $('#emgcontacts_workPhone').val() == '' )
                return false;
            else
                return true
        });
        
        $("#frmEmpEmgContact").validate({

            rules: {
                'emgcontacts[end_time]' : {required:true, maxlength:100},
                'emgcontacts[name]' : {required:true, maxlength:100},
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
     
                'emgcontacts[name]': {
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

        
        $('#delContactsBtn').click(function() {
            var checked = $('#frmEmpDelEmgContacts input:checked').length;

            if (checked == 0) {
                $("#messagebar").attr("class", "messageBalloon_notice");
                $("#messagebar").text("<?php echo __(TopLevelMessages::SELECT_RECORDS); ?>");
            } else {
                $('#frmEmpDelEmgContacts').submit();
            }
        });

        $('#btnSaveEContact').click(function() {
            $('#frmEmpEmgContact').submit();
        });

        $('#btnAddConstract').click(function() {
            location.href = "<?php echo url_for('shift/addRotaryConstract') ?>";
        });
});
//]]>
</script>
