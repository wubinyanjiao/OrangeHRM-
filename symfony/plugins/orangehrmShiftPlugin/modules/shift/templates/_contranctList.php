<?php  
$havecontract = count($form->empContractList) > 0;
?>                         
<a name="maxstaff"></a>
    <div id="changeShiftContranct">
        <div class="head">
            <h1 id="headChangeShiftContranct"><?php echo __('设置约束'); ?></h1>
        </div>
        <div class="inner">
            <form id="frmShiftContranct" action="<?php echo url_for('shift/saveMaxStaff?option=save'); ?>" method="post">

                <fieldset>
                    <ol>
                        <?php echo $form->render(); ?>
                        
                        <li class="required">
                            <em>*</em> <?php echo __(CommonMessages::REQUIRED_FIELD); ?>
                        </li>
                    </ol>
                    <p>
                        <input type="button" class="" id="btnContranctSave" value="<?php echo __("Save"); ?>" />
                        <input type="button" class="reset" id="btnContranctCancel" value="<?php echo __("Cancel"); ?>" />
                    </p>
                </fieldset>
            </form>
        </div>
    </div> <!-- changeShiftContranct -->


<div class="miniList" id="tblContranct">
    <div class="head">
        <h1><?php echo __("设置约束"); ?></h1>
    </div>

    <div class="inner">

            <?php include_partial('global/flash_messages', array('prefix' => 'contract')); ?>
            <form id="frmDelContranct" action="<?php echo url_for('pim/saveDeletecontract?empNumber=' . 
                    $empNumber . "&option=delete"); ?>" method="post">
                <?php echo $listForm ?>
                <p id="actioncontract">
            
                    <input type="button" value="<?php echo __("Add"); ?>" class="" id="addcontract" />&nbsp;
                  
                  
                    <input type="button" value="<?php echo __("Delete"); ?>" class="delete" id="delcontract" />
                
                </p>
                <table id="lang_data_table" cellpadding="0" cellspacing="0" width="100%" class="table tablesorter">
                    <thead>
                        <tr>
                        
                            <th class="check" width="2%"><input type="checkbox" id="contractCheckAll" /></th>
                  
                           <th><?php echo __('id'); ?></th>
                            <th><?php echo __('约束类型'); ?></th>
                            <th><?php echo __('约束值'); ?></th>
                             <th><?php echo __('所属排班'); ?></th>
                            <th><?php echo __('应用类型'); ?></th>                    
                            <th><?php echo __('status'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!$havecontract) { ?>
                            <tr>
                              
                                <td class="check"></td>
                             
                                <td><?php echo __(TopLevelMessages::NO_RECORDS_FOUND); ?></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        <?php } else { ?>                        
                            <?php
                            $contracts = $form->empContractList;
                            $row = 0;
                            foreach ($contracts as $contract) :

                                $cssClass = ($row % 2) ? 'even' : 'odd';

                                $contractName = $contract->getWorkContranctType()->getName();
                                 if($contract->getWorkShiftNew()){
                                    $shiftName=$contract->getWorkShiftNew()->getName();
                                 }
                                

                          
                                ?>
                                <tr class="<?php echo $cssClass; ?>">
                                    <td class="check">
                                        <input type="hidden" class="contract_name" 
                                               value="<?php echo $contractName; ?>" />
                                        <input type="hidden" class="contract_value" 
                                               value="<?php echo $contract->value; ?>" />
                                     
                                     
                                    </td>
                                    <td class="value"><?php echo $contract->value; ?></td>
                                    <td class="name">
                                        <a href="#" class="edit"><?php echo $contractName; ?></a>
                                    </td>
                                    <td class="value"><?php echo $contract->value; ?></td>
                                    <td class="shift"><?php echo $shiftName; ?></td>
                                    <td class="applay"><?php echo $contract->apply_to; ?></td>
                                    <td class="status"><?php echo $contract->status; ?></td>
                                </tr>
                                <?php
                                $row++;
                            endforeach;
                        } ?>
                    </tbody>
                </table>
            </form>

    </div>
</div> <!-- miniList-tblContranct sectioncontract -->

<script type="text/javascript">
    //<![CDATA[

    var fileModified = 0;
    var lang_addmaximum = "<?php echo __('设置约束');?>";
    var lang_editcontract = "<?php echo __('修改约束');?>";
    var lang_contractRequired = '<?php echo __(ValidationMessages::REQUIRED); ?>';
    var lang_contractTypeRequired = '<?php echo __(ValidationMessages::REQUIRED); ?>';
    var lang_competencyRequired = '<?php echo __(ValidationMessages::REQUIRED); ?>';
    var lang_selectcontractToDelete = "<?php echo __(TopLevelMessages::SELECT_RECORDS);?>";
    var lang_statusMaxLength = "<?php echo __(ValidationMessages::TEXT_LENGTH_EXCEEDS, array('%amount%' => 100));?>";
    var lang_yearShouldBeNumber = "<?php echo __('Should be a number'); ?>";
    //]]>
</script>

<script type="text/javascript">
//<![CDATA[

$(document).ready(function() {
    
    //当修改时，将input标签改为span
    $('#maxstaff_contranct_list').after('<span id="static_contract_maximum" style="display:none;"></span>');
    $('#contract_lang_type').after('<span id="static_lang_type" style="display:none;"></span>');
    
    function addEditLinks() {
        // called here to avoid double adding links - When in edit mode and cancel is pressed.
        removeEditLinks();
        $('form#frmDelContranct table tbody td.name').wrapInner('<a class="edit" href="#"/>');
    }

    function removeEditLinks() {
        $('form#frmDelContranct table tbody td.name a').each(function(index) {
            $(this).parent().text($(this).text());
        });
    }
    
    //hide add section
    $("#changeShiftContranct").hide();
    $("#contractRequiredNote").hide();

    //hiding the data table if records are not available
    if($("div#tblContranct .chkbox").length == 0) {
        //$("#tblContranct").hide();
        $('div#tblContranct .check').hide();
        $("#editcontract").hide();
        $("#delcontract").hide();
    }

    //if check all button clicked
    $("#contractCheckAll").click(function() {
        $("div#tblContranct .chkbox").removeAttr("checked");
        if($("#contractCheckAll").attr("checked")) {
            $("div#tblContranct .chkbox").attr("checked", "checked");
        }
    });

    //remove tick from the all button if any checkbox unchecked
    $("div#tblContranct .chkbox").click(function() {
        $("#contractCheckAll").removeAttr('checked');
        if($("div#tblContranct .chkbox").length == $("div#tblContranct .chkbox:checked").length) {
            $("#contractCheckAll").attr('checked', 'checked');
        }
    });
    
    //hide already added contracts and fluencys
    $("#contract_maximum").change(function() {
        //show all the options to reseting hide options
        $("#contract_lang_type option").each(function() {
            $(this).show();
        });
        $("#contract_lang_type").val("");
        var $table_tr = $("#lang_data_table tr");
        var i=0;
        //hide already added optons for selected contract
        $table_tr.each(function() {
            i++;
            if (i != 1) {           // skip heading tr
                if ($('#contract_maximum').val() == $(this).find('td:eq(0)').find('input[class="maximum"]').val()){
                    $td = $(this).find('td:eq(0)').find('input[class="lang_type"]').val();
                    $("#contract_lang_type option[value=" + $td + "]").hide();
                }
            }
        });        
    });
    
    $("#addcontract").click(function() {
        removeEditLinks();
        clearMessageBar();
        $('div#changeShiftContranct label.error').hide();        
        

        //changing the headings
        $("#headChangeShiftContranct").text(lang_addmaximum);
        $("div#tblContranct .chkbox").hide();
        $("#contractCheckAll").hide();

        //hiding action button section
        $("#actioncontract").hide();

        $('#static_contract_maximum').hide().val("");        
        $('#static_lang_type').hide().val("");
        $("#maxstaff_contranct_list").show().val("");
      

        //show add form
        $("#changeShiftContranct").show();
        $("#contractRequiredNote").show();
        
        //show all the options to reseting hide options
        $("#contract_lang_type option").each(function() {
            $(this).show();
        });
    });

    //clicking of delete button
    $("#delcontract").click(function(){

        clearMessageBar();

        if ($("div#tblContranct .chkbox:checked").length > 0) {
            $("#frmDelContranct").submit();
        } else {
            $("#contractMessagebar").attr('class', 'messageBalloon_notice').text(lang_selectcontractToDelete);
        }

    });

    $("#btnContranctSave").click(function() {
        clearMessageBar();

        $("#frmShiftContranct").submit();
    });

    //form validation
    var contractValidator =
        $("#frmShiftContranct").validate({
        rules: {
            'maxstaff[maximum]': {required: true, digits: true},
            'maxstaff[applyTo]': {required: true},
            'maxstaff[contranct_list]': {required: true},
            'maxstaff[competency]': {required: true},
            'maxstaff[status]' : {required: false, maxlength:100}
        },
        messages: {
            'maxstaff[maximum]': {required: lang_contractTypeRequired,digits: lang_yearShouldBeNumber},
            'maxstaff[applyTo]': {required: lang_contractTypeRequired},
            'maxstaff[contranct_list]': {required: lang_contractTypeRequired},
            'maxstaff[competency]': {required: lang_competencyRequired},
            'maxstaff[status]' : {maxlength: lang_statusMaxLength}
        }
    });

    $("#btnContranctCancel").click(function() {
        clearMessageBar();
      
            addEditLinks();
    
        contractValidator.resetForm();
        
        
        $('div#changeShiftContranct label.error').hide();

        $("div#tblContranct .chkbox").removeAttr("checked").show();
        
        //hiding action button section
        $("#actioncontract").show();
        $("#changeShiftContranct").hide();
        $("#contractRequiredNote").hide();        
        $("#contractCheckAll").show();
        $('#static_contract_maximum').hide().val("");
        $('#static_lang_type').hide().val("");
        
        //remove if disabled while edit
        $('#contract_maximum').removeAttr('disabled');
        
    });
    
    $('form#frmDelContranct a.edit').live('click', function(event) {
        event.preventDefault();
        clearMessageBar();

        //标题改变
        $("#headChangeShiftContranct").text(lang_editcontract);

        //验证规则
        contractValidator.resetForm();

        $('div#changeShiftContranct label.error').hide();

        //添加与删除的button按钮隐藏
        $("#actioncontract").hide();

        //显示输入框
        $("#changeShiftContranct").show();
        var parentRow = $(this).closest("tr");
        
        //获取input标签中的值                        
        var contranct_name = parentRow.find('input.contract_name:first').val();
        var contranct_value = parentRow.find('input.contract_value').val();

        var status = $(this).closest("tr").find('td:last').html();
     
        $('#maxstaff_contranct_list').val(contranct_name).hide();
        $("#contract_lang_type").val(contranct_value).hide();


        //选择选中的值
        var langTypeText = $("#contract_lang_type option:selected").text();

        
        $('#static_contract_maximum').text(parentRow.find('input.contract_name').val()).show();
        $('#static_lang_type').text(langTypeText).show();
        
        $("#maxstaff_maximum").val(contranct_value);
        $('#maxstaff_status').val(status);

        $("#contractRequiredNote").show();

        $("div#tblContranct .chkbox").hide();
        $("#contractCheckAll").hide();        
    });
});

//]]>
</script>