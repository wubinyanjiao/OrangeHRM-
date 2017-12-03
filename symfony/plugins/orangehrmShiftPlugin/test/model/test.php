<?php
/**
 * OrangeHRM is a comprehensive Human Resource Management (HRM) System that captures
 * all the essential functionalities required for any enterprise.
 * Copyright (C) 2006 OrangeHRM Inc., http://www.orangehrm.com
 *
 * OrangeHRM is free software; you can redistribute it and/or modify it under the terms of
 * the GNU General Public License as published by the Free Software Foundation; either
 * version 2 of the License, or (at your option) any later version.
 *
 * OrangeHRM is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
 * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with this program;
 * if not, write to the Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor,
 * Boston, MA  02110-1301, USA
 *
 */
?>

<?php
$numContacts = count($shiftTypes);
// echo'<pre>';var_dump($shiftTypes);exit;
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

<div class="box">
    
    <div id="addPaneEmgContact">
        <div class="head">
            <h1 id="emergencyContactHeading"><?php echo __('轮班类型'); ?></h1>
        </div>
        
        <div class="inner">

            <form name="frmEmpEmgContact" id="frmEmpEmgContact" method="post" action="<?php echo url_for('shift/updateShift?empNumber=' . $empNumber); ?>">
                <?php echo $form['_csrf_token']; ?>
                <?php echo $form["scheduleID"]->render(); ?>
                <?php echo $form["seqNo"]->render(); ?>
                <fieldset>
                    <ol>
                        <li>
                            <?php echo $form['shiftTypeName']->renderLabel(__('Shift Type Name') . ' <em>*</em>'); ?>
                            <?php echo $form['shiftTypeName']->render(array("class" => "formInputText", "maxlength" => 50)); ?>
                        </li>
                        <li>
                            <?php echo $form['abbreviation']->renderLabel(__('Abbreviation') . ' <em>*</em>'); ?>
                            <?php echo $form['abbreviation']->render(array("class" => "formInputText", "maxlength" => 50)); ?>
                        </li>
                        <li>
                            <?php echo $form['start_time']->renderLabel(__('Start Time') . ' <em>*</em>'); ?>
                            <?php echo $form['start_time']->render(array("class" => "formInputText", "maxlength" => 50)); ?>
                        </li>
                        <li>
                            <?php echo $form['end_time']->renderLabel(__('End Time') . ' <em>*</em>'); ?>
                            <?php echo $form['end_time']->render(array("class" => "formInputText", "maxlength" => 50)); ?>
                        </li>
                       
                        <li>
                            <?php echo $form['homePhone']->renderLabel(__('Status') . ' <em>*</em>'); ?>
                            <?php echo $form['homePhone']->render(array("class" => "formInputText", "maxlength" => 25)); ?>
                        </li>
                      
                        
                        <li class="required">
                            <em>*</em><?php echo __(CommonMessages::REQUIRED_FIELD); ?>
                        </li>
                    </ol>
                    <p>
                        <input type="button" class="" name="btnSaveEContact" id="btnSaveEContact" value="<?php echo __("Save"); ?>"/>
                        <input type="button" id="btnCancel" class="reset" value="<?php echo __("Cancel"); ?>"/>
                    </p>
                </fieldset>
            </form>
        </div>
    </div> <!-- addPaneEmgContact -->
 
    <div class="miniList" id="listEmegrencyContact">
        <div class="head">
            <h1><?php echo __("轮班类型"); ?></h1>
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
                            <th class="emgContactName"><?php echo __("轮班类型名"); ?></th>
                            <th><?php echo __("简介"); ?></th>
                            <th><?php echo __("开始时间"); ?></th>
                            <th><?php echo __("结束时间"); ?></th>
                            <th><?php echo __("状态"); ?></th>
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
                        
                        foreach ($shiftTypes as $shiftType) :
                           
                            $cssClass = ($row % 2) ? 'even' : 'odd';
                            echo '<tr class="' . $cssClass . '">';
                       
                                echo "<td class='check'><input type='checkbox' class='checkbox' name='chkecontactdel[]' value='" . $shiftType['shift_type']['shiftTypeName'] . "'/></td>";
                          
                            ?>
                            <td><?php echo $shiftType['id'];?></td>
                            <td class="emgContactName" valign="top">
                                <a href="#"><?php echo $shiftType['shiftTypeName']; ?></a>
                            </td>

                            <?php
                          
                            echo "<td valigh='top'>" . $shiftType['abbreviation'] . '</td>';
                            echo "<td valigh='top'>" . $shiftType['start_time'] . '</td>';
                            echo "<td valigh='top'>" . $shiftType['end_time'] . '</td>';
                            echo "<td valigh='top'>" . $shiftType['status'] . '</td>';
                            echo '</tr>';
                            $row++;
                        endforeach;
                        } ?>
                    </tbody>
                </table>
            </form>
            
           
            <div><?php echo __(CommonMessages::RESTRICTED_SECTION); ?></div>

        </div>
    </div> <!-- miniList -->

    
</div> <!-- Box -->

<script type="text/javascript">
    //<![CDATA[

    // Move to separate js after completing initial work
    $('#delContactsBtn').attr('disabled', 'disabled');
    
    function clearAddForm() {
        $('#emgcontacts_seqNo').val('');
        $('#emgcontacts_name').val('');
        $('#emgcontacts_names').val('');
        $('#emgcontacts_relationship').val('');
        $('#emgcontacts_homePhone').val('');
        $('#emgcontacts_mobilePhone').val('');
        $('#emgcontacts_workPhone').val('');
        $('div#addPaneEmgContact label.error').hide();
        $('div#messagebar').hide();
    }

    function addEditLinks() {
        // called here to avoid double adding links - When in edit mode and cancel is pressed.
        removeEditLinks();
        $('#emgcontact_list tbody td.emgContactName').wrapInner('<a href="#"/>');
    }

    function removeEditLinks() {
        $('#emgcontact_list tbody td.emgContactName a').each(function(index) {
            $(this).parent().text($(this).text());
        });
    }

    $(document).ready(function() {
        $('#addPaneEmgContact').hide();
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
        // Edit a emergency contact in the list
        $('#frmEmpDelEmgContacts a').live('click', function() {

            var row = $(this).closest("tr");
            var seqNo = row.find('input.check:first').val();
            var name = $(this).text();
          
            var relationship = $("#relationship_" + seqNo).val();
            var homePhone = $("#homePhone_" + seqNo).val();
            var mobilePhone = $("#mobilePhone_" + seqNo).val();
            var workPhone = $("#workPhone_" + seqNo).val();
            
            $('#emgcontacts_seqNo').val(seqNo);
            $('#emgcontacts_name').val(name);
        
            $('#emgcontacts_relationship').val(relationship);
            $('#emgcontacts_homePhone').val(homePhone);
            $('#emgcontacts_mobilePhone').val(mobilePhone);
            $('#emgcontacts_workPhone').val(workPhone);

            $(".paddingLeftRequired").show();
            $("#emergencyContactHeading").text("<?php echo __("Edit Emergency Contact");?>");
            $('div#messagebar').hide();
            // hide validation error messages

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
            <?php  if ($emergencyContactPermissions->canUpdate()){?>
            addEditLinks();
            <?php }?>
            $('div#messagebar').hide();
            $(".paddingLeftRequired").hide();
        });

        // Add a emergency contact
        $('#btnAddContact').click(function() {
            $("#emergencyContactHeading").text("<?php echo __("创建轮班类型");?>");
            $(".paddingLeftRequired").show();
            clearAddForm();

            // Hide list action buttons and checkbox
            $('#listActions').hide();
            $('#emgcontact_list .check').hide();
            removeEditLinks();
            $('div#messagebar').hide();

            //
            //            // hide validation error messages
            //            $("label.errortd[generated='true']").css('display', 'none');
            $('#addPaneEmgContact').css('display', 'block');
        });

        /* Valid Contact Phone */
        $.validator.addMethod("validContactPhone", function(value, element) {
            if ( $('#emgcontacts_homePhone').val() == '' && $('#emgcontacts_mobilePhone').val() == '' &&
                    $('#emgcontacts_workPhone').val() == '' )
                return false;
            else
                return true
        });
        
        $("#frmEmpEmgContact").validate({

            rules: {
                'emgcontacts[end_time]' : {required:true, maxlength:100},
                'emgcontacts[shiftTypeName]' : {required:true, maxlength:100},
                'emgcontacts[abbreviation]' : {required:true, maxlength:100},
                'emgcontacts[start_time]' : {required:true, maxlength:100},
                'emgcontacts[relationship]' : {required: true, maxlength:100},
                'emgcontacts[homePhone]' : {required: true, maxlength:100},
    
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
                'emgcontacts[homePhone]' : {
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
});
//]]>
</script>
