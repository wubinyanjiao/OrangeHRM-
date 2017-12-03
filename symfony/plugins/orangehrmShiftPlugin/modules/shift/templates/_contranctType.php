<?php  

$havecontrancttype = count($form->contranctTypeList) > 0;
?>                         
<a name="contrancttype"></a>
    <div id="setMinmum">
        <div class="head">
            <h1 id="headChangecontrancttype"><?php echo __('添加约束类型'); ?></h1>
        </div>
        <div class="inner">
            <form id="frmMinmum" action="<?php echo url_for('shift/saveContranctType?option=save'); ?>" method="post">
                <?php echo $form["scheduleID"]->render(); ?>
                <?php echo $form["id"]->render(); ?>
                <fieldset>
                    <ol>
                        <?php echo $form->render(); ?>
                        
                        <li class="required">
                            <em>*</em> <?php echo __(CommonMessages::REQUIRED_FIELD); ?>
                        </li>
                    </ol>
                    <p>
                        <input type="button" class="" id="btnMinmum" value="<?php echo __("Save"); ?>" />
                        <input type="button" class="reset" id="btnMinmumCancel" value="<?php echo __("Cancel"); ?>" />
                    </p>
                </fieldset>
            </form>
        </div>
    </div> <!-- changecontrancttype -->


<div class="miniList" id="tblcontrancttype">
    <div class="head">
        <h1><?php echo __("添加约束类型"); ?></h1>
    </div>

    <div class="inner">
            <?php include_partial('global/flash_messages', array('prefix' => 'contrancttype')); ?>

            <form id="frmDelcontrancttype" action="<?php echo url_for('pim/saveDeletecontrancttype?empNumber=' . 
                    $empNumber . "&option=delete"); ?>" method="post">
                <?php echo $listForm ?>
                <p id="actionMinmum">
            
                    <input type="button" value="<?php echo __("Add"); ?>" class="" id="addMinmum" />&nbsp;
                  
                  
                    <input type="button" value="<?php echo __("Delete"); ?>" class="delete" id="delcontrancttype" />
                
                </p>
                <table id="lang_data_table" cellpadding="0" cellspacing="0" width="100%" class="table tablesorter">
                    <thead>
                        <tr>
                        
                            <th class="check" width="2%"><input type="checkbox" id="contrancttypeCheckAll" /></th>
                  
                            <th><?php echo __('约束ID'); ?></th>
                            <th><?php echo __('约束名'); ?></th>
                            <th><?php echo __('状态'); ?></th>
                            <th><?php echo __('创建时间'); ?></th>    
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!$havecontrancttype) { ?>
                            <tr>
                              
                                    <td class="check"></td>
                             
                                <td><?php echo __(TopLevelMessages::NO_RECORDS_FOUND); ?></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        <?php } else { ?>                        
                            <?php
                            $contranct_type = $form->contranctTypeList; //var_dump($contranct_type->toArray());die;
                            $row = 0;
                            foreach ($contranct_type as $contrancttype) :
                                $cssClass = ($row % 2) ? 'even' : 'odd';

                                $contrancttypeName = $contrancttype->getName();
                                $contrancttypeId = $contrancttype->getId();

                                $cssClass = ($row % 2) ? 'even' : 'odd';
                                echo '<tr class="' . $cssClass . '">';
                                echo "<td class='check' style='display:block'><input type='checkbox' class='checkbox' name='chkdependentdel[]' value='" . $contrancttypeId . "'/></td>";  
                                ?>
                                
                                   

                               
                                    <td class="name">
                                        <a href="#" class="edit"><?php echo htmlspecialchars($contrancttype->id); ?></a>
                                    </td>
                                    <input type="hidden" id="contranctType_<?php echo $contrancttypeId; ?>" value="<?php echo $contrancttypeId?>" />

                                        <input type="hidden" id="contranctTypeName_<?php echo $contrancttypeId; ?>" value="<?php echo $contrancttypeName;?>" />
                                        <input type="hidden" id="contranctTypeSchedule_<?php echo $contrancttypeId; ?>" value="<?php echo $contrancttype->schedule_id;?>" />
                                        <input type="hidden" id="contranctTypeStatus_<?php echo $contrancttypeId; ?>" value="<?php echo $contrancttype->status;?>" />
                                        <input type="hidden" id="contranctTypecreatetime_<?php echo $contrancttypeId; ?>" value="<?php echo $contrancttype->create_at;?>" />
                                     <td class="name">
                                        <a href="#" class="edit"><?php echo htmlspecialchars($contrancttypeName); ?></a>
                                    </td>
                                 
                                    <td class="status"><?php echo htmlspecialchars($contrancttype->status); ?></td>
                                    <td class="status"><?php echo htmlspecialchars($contrancttype->create_at); ?></td>
                                </tr>
                             
                                <?php
                                $row++;
                            endforeach;
                        } ?>
                    </tbody>
                </table>
            </form>

    </div>
</div> <!-- miniList-tblcontrancttype sectioncontrancttype -->

<script type="text/javascript">
    //<![CDATA[

    var fileModified = 0;
    var lang_addcontrancttype = "<?php echo __('添加约束类型');?>";
    var lang_editcontrancttype = "<?php echo __('修改约束类型');?>";
    var lang_contrancttypeRequired = '<?php echo __(ValidationMessages::REQUIRED); ?>';
    var lang_contrancttypeTypeRequired = '<?php echo __(ValidationMessages::REQUIRED); ?>';
    var lang_competencyRequired = '<?php echo __(ValidationMessages::REQUIRED); ?>';
    var lang_selectcontrancttypeToDelete = "<?php echo __(TopLevelMessages::SELECT_RECORDS);?>";
    var lang_statusMaxLength = "<?php echo __(ValidationMessages::TEXT_LENGTH_EXCEEDS, array('%amount%' => 100));?>";
    var lang_yearShouldBeNumber = "<?php echo __('Should be a number'); ?>";
    //]]>
</script>

<script type="text/javascript">
//<![CDATA[

$(document).ready(function() {
    

    
    function addEditLinks() {
        // called here to avoid double adding links - When in edit mode and cancel is pressed.
        removeEditLinks();
        $('form#frmDelcontrancttype table tbody td.name').wrapInner('<a class="edit" href="#"/>');
    }

    function removeEditLinks() {
        $('form#frmDelcontrancttype table tbody td.name a').each(function(index) {
            $(this).parent().text($(this).text());
        });
    }
    
    //hide add section
    $("#setMinmum").hide();
    $("#minmumRequiredNote").hide();

    //hiding the data table if records are not available
    if($("div#tblcontrancttype .chkbox").length == 0) {
        //$("#tblcontrancttype").hide();
        $('div#tblcontrancttype .check').hide();
        $("#editcontrancttype").hide();
        $("#delcontrancttype").hide();
    }

    //if check all button clicked
    $("#contrancttypeCheckAll").click(function() {
        $("div#tblcontrancttype .chkbox").removeAttr("checked");
        if($("#contrancttypeCheckAll").attr("checked")) {
            $("div#tblcontrancttype .chkbox").attr("checked", "checked");
        }
    });

    //remove tick from the all button if any checkbox unchecked
    $("div#tblcontrancttype .chkbox").click(function() {
        $("#contrancttypeCheckAll").removeAttr('checked');
        if($("div#tblcontrancttype .chkbox").length == $("div#tblcontrancttype .chkbox:checked").length) {
            $("#contrancttypeCheckAll").attr('checked', 'checked');
        }
    });

    $("#addMinmum").click(function() {
       
        removeEditLinks();
        clearMessageBar();
        $('div#setMinmum label.error').hide();        
        

        //changing the headings
        $("#headChangecontrancttype").text(lang_addcontrancttype);
        $("div#tblcontrancttype .chkbox").hide();
        $("#contrancttypeCheckAll").hide();

        //hiding action button section
        $("#actionMinmum").hide();
      

        $("#contrancttype_comptency").val("");

        //show add form
        $("#setMinmum").show();
        $("#minmumRequiredNote").show();
        

    });

    //clicking of delete button
    $("#delcontrancttype").click(function(){

        clearMessageBar();

        if ($("div#tblcontrancttype .chkbox:checked").length > 0) {
            $("#frmDelcontrancttype").submit();
        } else {
            $("#contrancttypeMessagebar").attr('class', 'messageBalloon_notice').text(lang_selectcontrancttypeToDelete);
        }

    });

    $("#btnMinmum").click(function() {
        clearMessageBar();

        $("#frmMinmum").submit();
    });

    //form validation
    var contrancttypeValidator =
        $("#frmMinmum").validate({
        rules: {
            
            'contrancttype[name]': {required: true},
            
        },
        messages: {
        
            'contrancttype[name]': {required: lang_contrancttypeTypeRequired},
      
        }
    });

    $("#btnMinmumCancel").click(function() {
        clearMessageBar();
      
        addEditLinks();
    
        contrancttypeValidator.resetForm();
        
        $('div#setMinmum label.error').hide();

        $("div#tblcontrancttype .chkbox").removeAttr("checked").show();
        
        //hiding action button section
        $("#actionMinmum").show();
        $("#setMinmum").hide();
        $("#minmumRequiredNote").hide();        
        $("#contrancttypeCheckAll").show();

    });
    
    $('form#frmDelcontrancttype a.edit').live('click', function(event) {

        event.preventDefault();
        clearMessageBar();

        //changing the headings
        $("#headChangecontrancttype").text(lang_editcontrancttype);

        contrancttypeValidator.resetForm();

        $('div#setMinmum label.error').hide();

        //hiding action button section
        $("#actionMinmum").hide();

        //show add form
        $("#setMinmum").show();

        var row = $(this).closest("tr");
        var id = row.find('input.checkbox:first').val();
        var status = $("#contranctTypeStatus_" + id).val();
        var schedule_id = $("#contranctTypeSchedule_"+ id).val();
        var contranctTypeName = $("#contranctTypeName_" + id).val();
        var create_at = $("#contranctTypecreatetime_" + id).val();



        $('#contrancttype_id').val(id);
        $('#contrancttype_name').val(contranctTypeName);
    
        $('#contrancttype_scheduleID').val(schedule_id);
        $('#emgcontacts_end_time').val(create_at);
    
        
        $("#contrancttype_competency").val(parentRow.find('input.competency').val());
        $('#contrancttype_status').val(status);

        $("#minmumRequiredNote").show();

        $("div#tblcontrancttype .chkbox").hide();
        $("#contrancttypeCheckAll").hide();        
    });
});

//]]>
</script>