<?php  
$haveLanguage = count($form->empLanguageList) > 0;
?>                         
<a name="language"></a>
    <div id="setMinEmpSKillLevel">
        <div class="head">
            <h1 id="headChangeSkillLevel"><?php echo __('最低雇员技术水平'); ?></h1>
        </div>
        <div class="inner">
            <form id="frmMinmum" action="<?php echo url_for('shift/saveDeleteLanguag?empNumber=75' 
                     . "&option=save"); ?>" method="post">
                <fieldset>
                    <ol>
                        <?php echo $form->render(); ?>
                        
                        <li class="required">
                            <em>*</em> <?php echo __(CommonMessages::REQUIRED_FIELD); ?>
                        </li>
                    </ol>
                    <p>
                        <input type="button" class="" id="btnMinmum" value="<?php echo __("Save"); ?>" />
                        <input type="button" class="reset" id="btnMinSkillaverageLevelCancel" value="<?php echo __("Cancel"); ?>" />
                    </p>
                </fieldset>
            </form>
        </div>
    </div> <!-- changeLanguage -->


<div class="miniList" id="tblLanguage">
    <div class="head">
        <h1><?php echo __("最低雇员技术水平"); ?></h1>
    </div>

    <div class="inner">
            <?php include_partial('global/flash_messages', array('prefix' => 'language')); ?>

            <form id="frmDelLanguage" action="<?php echo url_for('pim/saveDeleteLanguage?empNumber=' . 
                    $empNumber . "&option=delete"); ?>" method="post">
                <?php echo $listForm ?>
                <p id="actionMinSkillLevel">
            
                    <input type="button" value="<?php echo __("Add"); ?>" class="" id="addMinSkillaverLevel" />&nbsp;
                  
                  
                    <input type="button" value="<?php echo __("Delete"); ?>" class="delete" id="delLanguage" />
                
                </p>
                <table id="lang_data_table" cellpadding="0" cellspacing="0" width="100%" class="table tablesorter">
                    <thead>
                        <tr>
                        
                            <th class="check" width="2%"><input type="checkbox" id="languageCheckAll" /></th>
                  
                            <th><?php echo __('Language'); ?></th>
                            <th><?php echo __('Fluency'); ?></th>
                            <th><?php echo __('Competency'); ?></th>                    
                            <th><?php echo __('status'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!$haveLanguage) { ?>
                            <tr>
                              
                                    <td class="check"></td>
                             
                                <td><?php echo __(TopLevelMessages::NO_RECORDS_FOUND); ?></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        <?php } else { ?>                        
                            <?php
                            $languages = $form->empLanguageList; //var_dump($languages->toArray());die;
                            $row = 0;
                            foreach ($languages as $language) :
                                $cssClass = ($row % 2) ? 'even' : 'odd';
                                $languageName = $language->getLanguage()->getName();
                                ?>
                                <tr class="<?php echo $cssClass; ?>">
                                    <td class="check">
                                        <input type="hidden" class="language_name" 
                                               value="<?php echo htmlspecialchars($languageName); ?>" />
                                        <input type="hidden" class="lang_type" 
                                               value="<?php echo htmlspecialchars($language->fluency); ?>" />
                                        <input type="hidden" class="competency" 
                                               value="<?php echo htmlspecialchars($language->competency); ?>" />
                                        <input type="hidden" class="maximum" 
                                               value="<?php echo htmlspecialchars($language->langId); ?>" />
                                        <?php if ($languagePermissions->canDelete()) { ?>
                                        <input type="checkbox" class="chkbox" 
                                               value="<?php echo $language->langId . "_" . $language->fluency; ?>" 
                                               name="delLanguage[]"/>
                                        <?php } else { ?>
                                        <input type="hidden" class="chkbox" 
                                               value="<?php echo $language->langId . "_" . $language->fluency; ?>" 
                                               name="delLanguage[]"/>
                                        <?php } ?>
                                    </td>
                                    <td class="name">
                                       
                                        <a href="#" class="edit"><?php echo htmlspecialchars($languageName); ?></a>
                                      
                                        ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($form->getLangTypeDesc($language->fluency)); ?></td>
                                    <td><?php echo htmlspecialchars($form->getCompetencyDesc($language->competency)); ?></td>
                                    <td class="status"><?php echo htmlspecialchars($language->status); ?></td>
                                </tr>
                                <?php
                                $row++;
                            endforeach;
                        } ?>
                    </tbody>
                </table>
            </form>

    </div>
</div> <!-- miniList-tblLanguage sectionLanguage -->

<script type="text/javascript">
    //<![CDATA[

    var fileModified = 0;
    var lang_addLanguage = "<?php echo __('最低雇员技术水平');?>";
    var lang_editLanguage = "<?php echo __('Edit Language');?>";
    var lang_languageRequired = '<?php echo __(ValidationMessages::REQUIRED); ?>';
    var lang_languageTypeRequired = '<?php echo __(ValidationMessages::REQUIRED); ?>';
    var lang_competencyRequired = '<?php echo __(ValidationMessages::REQUIRED); ?>';
    var lang_selectLanguageToDelete = "<?php echo __(TopLevelMessages::SELECT_RECORDS);?>";
    var lang_statusMaxLength = "<?php echo __(ValidationMessages::TEXT_LENGTH_EXCEEDS, array('%amount%' => 100));?>";
    var lang_yearShouldBeNumber = "<?php echo __('Should be a number'); ?>";
    //]]>
</script>

<script type="text/javascript">
//<![CDATA[

$(document).ready(function() {
    
    $('#language_maximum').after('<span id="static_language_maximum" style="display:none;"></span>');
    $('#minmum_lang_type').after('<span id="static_lang_type" style="display:none;"></span>');
    
    function addEditLinks() {
        // called here to avoid double adding links - When in edit mode and cancel is pressed.
        removeEditLinks();
        $('form#frmDelLanguage table tbody td.name').wrapInner('<a class="edit" href="#"/>');
    }

    function removeEditLinks() {
        $('form#frmDelLanguage table tbody td.name a').each(function(index) {
            $(this).parent().text($(this).text());
        });
    }
    
    //hide add section
    $("#setMinEmpSKillLevel").hide();
    $("#minmumRequiredNote").hide();

    //hiding the data table if records are not available
    if($("div#tblLanguage .chkbox").length == 0) {
        //$("#tblLanguage").hide();
        $('div#tblLanguage .check').hide();
        $("#editLanguage").hide();
        $("#delLanguage").hide();
    }

    //if check all button clicked
    $("#languageCheckAll").click(function() {
        $("div#tblLanguage .chkbox").removeAttr("checked");
        if($("#languageCheckAll").attr("checked")) {
            $("div#tblLanguage .chkbox").attr("checked", "checked");
        }
    });

    //remove tick from the all button if any checkbox unchecked
    $("div#tblLanguage .chkbox").click(function() {
        $("#languageCheckAll").removeAttr('checked');
        if($("div#tblLanguage .chkbox").length == $("div#tblLanguage .chkbox:checked").length) {
            $("#languageCheckAll").attr('checked', 'checked');
        }
    });
    
    //hide already added languages and fluencys
    $("#language_maximum").change(function() {
        //show all the options to reseting hide options
        $("#minmum_lang_type option").each(function() {
            $(this).show();
        });
        $("#minmum_lang_type").val("");
        var $table_tr = $("#lang_data_table tr");
        var i=0;
        //hide already added optons for selected language
        $table_tr.each(function() {
            i++;
            if (i != 1) {           // skip heading tr
                if ($('#language_maximum').val() == $(this).find('td:eq(0)').find('input[class="maximum"]').val()){
                    $td = $(this).find('td:eq(0)').find('input[class="lang_type"]').val();
                    $("#minmum_lang_type option[value=" + $td + "]").hide();
                }
            }
        });        
    });
    
    $("#addMinSkillaverLevel").click(function() {
       
        removeEditLinks();
        clearMessageBar();
        $('div#setMinEmpSKillLevel label.error').hide();        
        

        //changing the headings
        $("#headChangeSkillLevel").text(lang_addLanguage);
        $("div#tblLanguage .chkbox").hide();
        $("#languageCheckAll").hide();

        //hiding action button section
        $("#actionMinSkillLevel").hide();

        $('#static_language_maximum').hide().val("");        
        $('#static_lang_type').hide().val("");
        $("#language_maximum").show().val("");
        $("#minmum_lang_type").show().val("");
        $("#language_comptency").val("");

        //show add form
        $("#setMinEmpSKillLevel").show();
        $("#minmumRequiredNote").show();
        
        //show all the options to reseting hide options
        $("#minmum_lang_type option").each(function() {
            $(this).show();
        });
    });

    //clicking of delete button
    $("#delLanguage").click(function(){

        clearMessageBar();

        if ($("div#tblLanguage .chkbox:checked").length > 0) {
            $("#frmDelLanguage").submit();
        } else {
            $("#languageMessagebar").attr('class', 'messageBalloon_notice').text(lang_selectLanguageToDelete);
        }

    });

    $("#btnMinmum").click(function() {
        clearMessageBar();

        $("#frmMinmum").submit();
    });

    //form validation
    var languageValidator =
        $("#frmMinmum").validate({
        rules: {
            'language[maximum]': {required: false, digits: true},
            'language[lang_type]': {required: true},
            'language[competency]': {required: true},
            'language[status]' : {required: false, maxlength:100}
        },
        messages: {
            'language[maximum]': {digits: lang_yearShouldBeNumber},
            'language[lang_type]': {required: lang_languageTypeRequired},
            'language[competency]': {required: lang_competencyRequired},
            'language[status]' : {maxlength: lang_statusMaxLength}
        }
    });

    $("#btnMinSkillaverageLevelCancel").click(function() {
        clearMessageBar();
      
            addEditLinks();
    
        languageValidator.resetForm();
        
        $('div#setMinEmpSKillLevel label.error').hide();

        $("div#tblLanguage .chkbox").removeAttr("checked").show();
        
        //hiding action button section
        $("#actionMinSkillLevel").show();
        $("#setMinEmpSKillLevel").hide();
        $("#minmumRequiredNote").hide();        
        $("#languageCheckAll").show();
        $('#static_language_maximum').hide().val("");
        $('#static_lang_type').hide().val("");
        
        //remove if disabled while edit
        $('#language_maximum').removeAttr('disabled');
        $('#minmum_lang_type').removeAttr('disabled');
    });
    
    $('form#frmDelLanguage a.edit').live('click', function(event) {
        event.preventDefault();
        clearMessageBar();

        //changing the headings
        $("#headChangeSkillLevel").text(lang_editLanguage);

        languageValidator.resetForm();

        $('div#setMinEmpSKillLevel label.error').hide();

        //hiding action button section
        $("#actionMinSkillLevel").hide();

        //show add form
        $("#setMinEmpSKillLevel").show();
        var parentRow = $(this).closest("tr");
                                
        var maximum = parentRow.find('input.maximum:first').val();

        var langType = parentRow.find('input.lang_type').val();
        var status = $(this).closest("tr").find('td:last').html();
        
        $('#language_maximum').val(maximum).hide();
        $("#minmum_lang_type").val(langType).hide();
        var langTypeText = $("#minmum_lang_type option:selected").text();
        
        $('#static_language_maximum').text(parentRow.find('input.language_name').val()).show();
        $('#static_lang_type').text(langTypeText).show();
        
        $("#language_competency").val(parentRow.find('input.competency').val());
        $('#language_status').val(status);

        $("#minmumRequiredNote").show();

        $("div#tblLanguage .chkbox").hide();
        $("#languageCheckAll").hide();        
    });
});

//]]>
</script>