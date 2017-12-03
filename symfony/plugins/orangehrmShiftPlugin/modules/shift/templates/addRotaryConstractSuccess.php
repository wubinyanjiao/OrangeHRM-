<?php 
 use_stylesheet(plugin_web_path('orangehrmShiftPlugin', 'css/viewShiftContranctSuccess.css'));

?>

<div class="box " id="employee-details">

    <div class="personalDetails" id="pdMainContainer">
        
        <div class="head">
            <h1><?php echo __('轮转规则池'); ?></h1>
        </div> <!-- head -->
    
        <div class="inner">

            <?php include_partial('global/flash_messages', array('prefix' => 'personaldetails')); ?>

            <form id="frmEmpPersonalDetails" method="post" action="<?php echo url_for('shift/addRotaryConstract'); ?>">

                <?php echo $form['_csrf_token']; ?>
                <?php echo $form['txtEmpID']->render(); ?>

                <fieldset>

                    <ol>
                        <li class="line nameContainer">
                            <label for="Full_Name" class="hasTopFieldHelp"><?php echo __('中级职称平均分配'); ?></label>
                            <ol class="fieldsInLine">

                                <li>
                                    <div class="fieldDescription"><em>*</em> <?php echo __('平均值'); ?></div>
                                    <?php echo $form['averge_mid_level_count']->render(array("class" => "block default editable", "maxlength" => 30, "title" => __('Last Name'))); ?>
                                </li>
                                <li style="margin-bottom: 13px">
                                    <div class="fieldDescription"><?php echo __('权重(赋值越大，权重越高)'); ?></div>
                                    <?php echo $form['averge_mid_level_weight']->render(array("class" => "block default editable", "maxlength" => 30, "title" => __('Middle Name'))); ?>
                                </li>
                                
                                <li>
                                    <div class="fieldDescription"><em>*</em> <?php echo __('状态'); ?></div>
                                    <?php echo $form['averge_mid_level_status']->render(array("class" => "block default editable", "maxlength" => 30, "title" => __('Last Name'))); ?>
                                </li>
                            </ol>      
                        </li>
                    </ol>

                    <ol>
                        <li class="line nameContainer">
                            <label for="Full_Name" class="hasTopFieldHelp"><?php echo __('男士平均分配'); ?></label>
                            <ol class="fieldsInLine">

                                <li>
                                    <div class="fieldDescription"><em>*</em> <?php echo __('平均值'); ?></div>
                                    <?php echo $form['averge_man_count']->render(array("class" => "block default editable", "maxlength" => 30, "title" => __('Last Name'))); ?>
                                </li>
                                <li style="margin-bottom: 13px">
                                    <div class="fieldDescription"><?php echo __('权重(赋值越大，权重越高)'); ?></div>
                                    <?php echo $form['averge_man_weight']->render(array("class" => "block default editable", "maxlength" => 30, "title" => __('Middle Name'))); ?>
                                </li>
                                
                                <li>
                                    <div class="fieldDescription"><em>*</em> <?php echo __('状态'); ?></div>
                                    <?php echo $form['averge_man_status']->render(array("class" => "block default editable", "maxlength" => 30, "title" => __('Last Name'))); ?>
                                </li>
                            </ol>      
                        </li>
                    </ol>

                    <ol>
                        <li class="line nameContainer">
                            <label for="Full_Name" class="hasTopFieldHelp"><?php echo __('研究生平均分配'); ?></label>
                            <ol class="fieldsInLine">

                                <li>
                                    <div class="fieldDescription"><em>*</em> <?php echo __('平均值'); ?></div>
                                    <?php echo $form['averge_graduate_count']->render(array("class" => "block default editable", "maxlength" => 30, "title" => __('Last Name'))); ?>
                                </li>
                                <li style="margin-bottom: 13px">
                                    <div class="fieldDescription"><?php echo __('权重(赋值越大，权重越高)'); ?></div>
                                    <?php echo $form['averge_graduate_weight']->render(array("class" => "block default editable", "maxlength" => 30, "title" => __('Middle Name'))); ?>
                                </li>
                                
                                <li>
                                    <div class="fieldDescription"><em>*</em> <?php echo __('状态'); ?></div>
                                    <?php echo $form['averge_graduate_status']->render(array("class" => "block default editable", "maxlength" => 30, "title" => __('Last Name'))); ?>
                                </li>
                            </ol>      
                        </li>
                    </ol>

                    <ol>
                        <li class="line nameContainer">
                            <label for="Full_Name" class="hasTopFieldHelp"><?php echo __('中级职称满多长时间轮转'); ?></label>
                            <ol class="fieldsInLine">

                                 <li>
                                    <div class="fieldDescription"><em>*</em> <?php echo __('选择年数'); ?></div>
                                    <?php echo $form['midlevel_year_count']->render(array("class" => "block default editable", "maxlength" => 30, "title" => __('Last Name'))); ?>
                                </li>
                                <li style="margin-bottom: 13px">
                                    <div class="fieldDescription"><?php echo __('权重(赋值越大，权重越高)'); ?></div>
                                    <?php echo $form['midlevel_year_weight']->render(array("class" => "block default editable", "maxlength" => 30, "title" => __('Middle Name'))); ?>
                                </li>
                               
                                <li>
                                    <div class="fieldDescription"><em>*</em> <?php echo __('状态'); ?></div>
                                    <?php echo $form['midlevel_year_status']->render(array("class" => "block default editable", "maxlength" => 30, "title" => __('Last Name'))); ?>
                                </li>
                            </ol>      
                        </li>
                    </ol>


                    <ol>
                        <li class="line nameContainer">
                            <label for="Full_Name" class="hasTopFieldHelp"><?php echo __('轮转部门顺序'); ?></label>
                            <ol class="fieldsInLine">

                                <li>
                                    <div class="fieldDescription"><em>*</em> <?php echo __('第一个部门'); ?></div>
                                    <?php echo $form['firt_rotary_document']->render(array("class" => "block default editable", "maxlength" => 30, "title" => __('Last Name'))); ?>
                                </li>
                                <li>
                                    <div class="fieldDescription"><em>*</em> <?php echo __('第二个部门'); ?></div>
                                    <?php echo $form['sec_rotary_document']->render(array("class" => "block default editable", "maxlength" => 30, "title" => __('Last Name'))); ?>
                                </li>

                                <li>
                                    <div class="fieldDescription"><em>*</em> <?php echo __('第三个部门'); ?></div>
                                    <?php echo $form['third_rotary_document']->render(array("class" => "block default editable", "maxlength" => 30, "title" => __('Last Name'))); ?>
                                </li>

                                <li style="margin-bottom: 13px">
                                    <div class="fieldDescription"><?php echo __('权重(赋值越大，权重越高)'); ?></div>
                                    <?php echo $form['document_rotary_Weight']->render(array("class" => "block default editable", "maxlength" => 30, "title" => __('Middle Name'))); ?>
                                </li>
                              
                                <li>
                                    <div class="fieldDescription"><em>*</em> <?php echo __('状态'); ?></div>
                                    <?php echo $form['document_rotary_status']->render(array("class" => "block default editable", "maxlength" => 30, "title" => __('Last Name'))); ?>
                                </li>
                            </ol>      
                        </li>
                    </ol>

                    <ol>
                        <li class="line nameContainer">
                            <label for="Full_Name" class="hasTopFieldHelp"><?php echo __('门诊满几年轮转'); ?></label>
                            <ol class="fieldsInLine">

                                <li>
                                    <div class="fieldDescription"><em>*</em> <?php echo __('至少需要几年'); ?></div>
                                    <?php echo $form['rotary_limit_year']->render(array("class" => "block default editable", "maxlength" => 30, "title" => __('Last Name'))); ?>
                                </li>
                                <li style="margin-bottom: 13px">
                                    <div class="fieldDescription"><?php echo __('权重(赋值越大，权重越高)'); ?></div>
                                    <?php echo $form['rotary_limit_time_weight']->render(array("class" => "block default editable", "maxlength" => 30, "title" => __('Middle Name'))); ?>
                                </li>
                              
                                <li>
                                    <div class="fieldDescription"><em>*</em> <?php echo __('状态'); ?></div>
                                    <?php echo $form['rotary_limit_time_status']->render(array("class" => "block default editable", "maxlength" => 30, "title" => __('Last Name'))); ?>
                                </li>
                            </ol>      
                        </li>
                    </ol>

                    <ol>
                        <li class="line nameContainer">
                            <label for="Full_Name" class="hasTopFieldHelp"><?php echo __('年龄满足至少多少不轮转到门诊'); ?></label>
                            <ol class="fieldsInLine">

                                 <li>
                                    <div class="fieldDescription"><em>*</em> <?php echo __('至少需要几年'); ?></div>
                                    <?php echo $form['rotary_limit_year']->render(array("class" => "block default editable", "maxlength" => 30, "title" => __('Last Name'))); ?>
                                </li>
                                <li style="margin-bottom: 13px">
                                    <div class="fieldDescription"><?php echo __('权重(赋值越大，权重越高'); ?></div>
                                    <?php echo $form['min_age_rotary_weight']->render(array("class" => "block default editable", "maxlength" => 30, "title" => __('Middle Name'))); ?>
                                </li>
                              
                                <li>
                                    <div class="fieldDescription"><em>*</em> <?php echo __('状态'); ?></div>
                                    <?php echo $form['min_age_rotary_status']->render(array("class" => "block default editable", "maxlength" => 30, "title" => __('Last Name'))); ?>
                                </li>
                            </ol>      
                        </li>
                    </ol>

                     <ol>
                        <li class="line nameContainer">
                            <label for="Full_Name" class="hasTopFieldHelp"><?php echo __('组长不参与轮转'); ?></label>
                            <ol class="fieldsInLine">

                                 <li>
                                    <div class="fieldDescription"><em>*</em> <?php echo __('至少需要几年'); ?></div>
                                    <?php echo $form['leader_no_rotary_year']->render(array("class" => "block default editable", "maxlength" => 30, "title" => __('Last Name'))); ?>
                                </li>
                                <li style="margin-bottom: 13px">
                                    <div class="fieldDescription"><?php echo __('权重(赋值越大，权重越高'); ?></div>
                                    <?php echo $form['leader_no_rotary_weight']->render(array("class" => "block default editable", "maxlength" => 30, "title" => __('Middle Name'))); ?>
                                </li>
                              
                                <li>
                                    <div class="fieldDescription"><em>*</em> <?php echo __('状态'); ?></div>
                                    <?php echo $form['leader_no_rotary_status']->render(array("class" => "block default editable", "maxlength" => 30, "title" => __('Last Name'))); ?>
                                </li>
                            </ol>      
                        </li>
                    </ol>

   
                    <p><input type="button" id="btnSave" value="<?php echo __("Edit"); ?>" /></p>
               
                </fieldset>
            </form>

          

        </div> <!-- inner -->
        
    </div> <!-- pdMainContainer -->

    
    <?php echo include_component('pim', 'customFields', array('empNumber'=>$empNumber, 'screen' => CustomField::SCREEN_PERSONAL_DETAILS));?>
    <?php echo include_component('pim', 'attachments', array('empNumber'=>$empNumber, 'screen' => EmployeeAttachment::SCREEN_PERSONAL_DETAILS));?>
    
</div> <!-- employee-details -->
 
<?php //echo stylesheet_tag('orangehrm.datepicker.css') ?>
<?php //echo javascript_include_tag('orangehrm.datepicker.js')?>

<script type="text/javascript">

    var edit = "<?php echo __("Edit"); ?>";
    var save = "<?php echo __("Save"); ?>";
    var lang_firstNameRequired = "<?php echo __(ValidationMessages::REQUIRED); ?>";
    var lang_weightShouldBeNumber = "<?php echo __('Should be a number'); ?>";
    var lang_lastNameRequired = "<?php echo __(ValidationMessages::REQUIRED); ?>";
    var lang_selectGender = "<?php echo __(ValidationMessages::REQUIRED); ?>";
    var lang_processing = '<?php echo __(CommonMessages::LABEL_PROCESSING);?>';
    var lang_invalidDate = '<?php echo __(ValidationMessages::DATE_FORMAT_INVALID, array('%format%' => str_replace('yy', 'yyyy', get_datepicker_date_format($sf_user->getDateFormat())))) ?>';
    var datepickerDateFormat = '<?php echo get_datepicker_date_format($sf_user->getDateFormat()); ?>';

    var fileModified = 0;
    
    var readOnlyFields = <?php echo json_encode($form->getReadOnlyWidgetNames());?>
 
    //]]>
</script>

<?php echo javascript_include_tag(plugin_web_path('orangehrmShiftPlugin', 'js/viewShiftContranctSuccess')); ?>
