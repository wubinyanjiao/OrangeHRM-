<?php 
 use_stylesheet(plugin_web_path('orangehrmShiftPlugin', 'css/viewShiftContranctSuccess.css'));

?>

<div class="box pimPane" id="employee-details">

    <?php echo include_component('shift', 'pimLeftMenu', array('empNumber'=>$empNumber, 'schedule'=>$schedule_id,'form' => $form));?>

    <div class="personalDetails" id="pdMainContainer">
        
        <div class="head">
            <h1><?php echo __('排班规则池'); ?></h1>
        </div> <!-- head -->
    
        <div class="inner">

            <?php include_partial('global/flash_messages', array('prefix' => 'personaldetails')); ?>

            <form id="frmEmpPersonalDetails" method="post" action="<?php echo url_for('shift/viewContranctsList'); ?>">

                <?php echo $form['_csrf_token']; ?>
                <?php echo $form['txtEmpID']->render(); ?>

                <fieldset>

                    <ol>
                        <li class="line nameContainer nightLeisure" id="nightLeisure">
                            <label for="Full_Name" class="hasTopFieldHelp"><?php echo __('夜班后夜休'); ?></label>
                            <ol class="fieldsInLine">
                                <li>
                                    <div class="fieldDescription"><em>*</em> <?php echo __('班次选择'); ?></div>
                                    <?php echo $form['nightAfterNightLeisureShiftSelect']->render(array("class" => "block default editable", "maxlength" => 30, "title" => __('班次选择'))); ?>
                                </li>

                                <li style="margin-bottom: 13px">
                                    <div class="fieldDescription"><?php echo __('权重(赋值越大，权重越高)'); ?></div>
                                    <?php echo $form['nightAfterNightLeisureWeight']->render(array("class" => "block default editable", "maxlength" => 30, "title" => __('Middle Name'))); ?>
                                </li>
            
                                <li>
                                    <div class="fieldDescription"><em>*</em> <?php echo __('状态'); ?></div>
                                    <?php echo $form['nightAfterNightLeisureStatus']->render(array("class" => "block default editable", "maxlength" => 30, "title" => __('Last Name'))); ?>
                                </li>

                                <a href="#?" id="addNightLeisure" style="text-decoration: none;color:#33AC3F;font-size:16px;font-weight:bold;" class="addButton"> +</a>
                                <a href='#?' id='treeLink_delete_3' style='text-decoration: none; list-style: none;color:#AA4935;font-size:16px;font-weight:bold;' class='deleteButton'> x</a>
                            </ol>    
                        </li>
                    </ol>

                    <ol>
                        <li class="line nameContainer averageAssignment" id="averageAssignment">
                            <label for="Full_Name" class="hasTopFieldHelp"><?php echo __('班次尽量平均分配'); ?></label>
                            <ol class="fieldsInLine">
                                <li>
                                    <div class="fieldDescription"><em>*</em> <?php echo __('班次选择'); ?></div>
                                    <?php echo $form['averageAssignmentShiftSelect']->render(array("class" => "block default editable", "maxlength" => 30, "title" => __('班次选择'))); ?>
                                </li>

                                <li style="margin-bottom: 13px">
                                    <div class="fieldDescription"><?php echo __('权重(赋值越大，权重越高)'); ?></div>
                                    <?php echo $form['averageAssignmentWeight']->render(array("class" => "block default editable", "maxlength" => 30, "title" => __('Middle Name'))); ?>
                                </li>
                                <li>
                                    <div class="fieldDescription"><em>*</em> <?php echo __('每人分配的次数'); ?></div>
                                    <?php echo $form['averageAssignment']->render(array("class" => "block default editable", "maxlength" => 30, "title" => __('Last Name'))); ?>
                                </li>
                                <li>
                                    <div class="fieldDescription"><em>*</em> <?php echo __('状态'); ?></div>
                                    <?php echo $form['averageAssignmentStatus']->render(array("class" => "block default editable", "maxlength" => 30, "title" => __('Last Name'))); ?>
                                </li>

                                <a href="#?" id="addAverageAssignment" style="text-decoration: none;color:#33AC3F;font-size:16px;font-weight:bold;" class="addButton"> +</a>
                                <a href='#?' style='text-decoration: none; list-style: none;color:#AA4935;font-size:16px;font-weight:bold;' class='averageAssign_deleteButton'> x</a>
                            </ol>      
                        </li>
                    </ol>

                    <ol>
                        <li class="line nameContainer shiftdOnlyforMan" id="shiftdOnlyforMan">
                            <label for="Full_Name" class="hasTopFieldHelp"><?php echo __('班次只分配给男性'); ?></label>
                            <ol class="fieldsInLine">
                                <li>
                                    <div class="fieldDescription"><em>*</em> <?php echo __('班次选择'); ?></div>
                                    <?php echo $form['shiftdOnlyforManShiftSelect']->render(array("class" => "block default editable", "maxlength" => 30, "title" => __('班次选择'))); ?>
                                </li>

                                <li style="margin-bottom: 13px">
                                    <div class="fieldDescription"><?php echo __('权重(赋值越大，权重越高)'); ?></div>
                                    <?php echo $form['shiftdOnlyforManWeight']->render(array("class" => "block default editable", "maxlength" => 30, "title" => __('Middle Name'))); ?>
                                </li>
                     
                                <li>
                                    <div class="fieldDescription"><em>*</em> <?php echo __('状态'); ?></div>
                                    <?php echo $form['shiftdOnlyforManStatus']->render(array("class" => "block default editable", "maxlength" => 30, "title" => __('Last Name'))); ?>
                                </li>

                                <a href="#?" id="addshiftdOnlyforMan" style="text-decoration: none;color:#33AC3F;font-size:16px;font-weight:bold;" class="addButton"> +</a>
                                <a href='#?' style='text-decoration: none; list-style: none;color:#AA4935;font-size:16px;font-weight:bold;' class='shiftdOnlyforMan_deleteButton'> x</a>
                            </ol>      
                        </li>
                    </ol>

                    <ol>
                        <li class="line nameContainer assignmentAfter" id="assignmentAfter">
                            <label for="Full_Name" class="hasTopFieldHelp"><?php echo __('该班次分配后间隔后再分配'); ?></label>
                            <ol class="fieldsInLine">
                                <li>
                                    <div class="fieldDescription"><em>*</em> <?php echo __('班次选择'); ?></div>
                                    <?php echo $form['assignmentAfterIntervalShiftSelect']->render(array("class" => "block default editable", "maxlength" => 30, "title" => __('班次选择'))); ?>
                                </li>

                                <li style="margin-bottom: 13px">
                                    <div class="fieldDescription"><?php echo __('权重(赋值越大，权重越高)'); ?></div>
                                    <?php echo $form['assignmentAfterIntervalWeight']->render(array("class" => "block default editable", "maxlength" => 30, "title" => __('Middle Name'))); ?>
                                </li>
                                <li>
                                    <div class="fieldDescription"><em>*</em> <?php echo __('选择间隔几周'); ?></div>
                                    <?php echo $form['assignmentAfterIntervalEmployee']->render(array("class" => "block default editable", "maxlength" => 30, "title" => __('Last Name'))); ?>
                                </li>
                                <li>
                                    <div class="fieldDescription"><em>*</em> <?php echo __('状态'); ?></div>
                                    <?php echo $form['assignmentAfterIntervalStatus']->render(array("class" => "block default editable", "maxlength" => 30, "title" => __('Last Name'))); ?>
                                </li>

                                <a href="#?" id="addAssignmentAfter" style="text-decoration: none;color:#33AC3F;font-size:16px;font-weight:bold;" class="addButton"> +</a>
                                <a href='#?' style='text-decoration: none; list-style: none;color:#AA4935;font-size:16px;font-weight:bold;' class='assignmentAfter_deleteButton'> x</a>
                              
                            </ol>
                        </li>
                    </ol>


                    <ol>
                        <li class="line nameContainer shiftNotForEmployee" id="shiftNotForEmployee" >
                            <label for="Full_Name" class="hasTopFieldHelp"><?php echo __('该班次不分配给某员工'); ?></label>
                            <ol class="fieldsInLine">
                                <li>
                                    <div class="fieldDescription"><em>*</em> <?php echo __('班次选择'); ?></div>
                                    <?php echo $form['shiftNotForEmployeeShiftSelect']->render(array("class" => "block default editable", "maxlength" => 30, "title" => __('班次选择'))); ?>
                                </li>

                                <li style="margin-bottom: 13px">
                                    <div class="fieldDescription"><?php echo __('权重(赋值越大，权重越高)'); ?></div>
                                    <?php echo $form['shiftNotForEmployeeWeight']->render(array("class" => "block default editable", "maxlength" => 30, "title" => __('Middle Name'))); ?>
                                </li>
                                <li>
                                    <div class="fieldDescription"><em>*</em> <?php echo __('选择员工'); ?></div>
                                    <?php echo $form['shiftNotForEmployee']->render(array("class" => "block default editable", "maxlength" => 30, "title" => __('Last Name'))); ?>
                                </li>
                                <li>
                                    <div class="fieldDescription"><em>*</em> <?php echo __('状态'); ?></div>
                                    <?php echo $form['shiftNotForEmployeeStatus']->render(array("class" => "block default editable", "maxlength" => 30, "title" => __('Last Name'))); ?>
                                </li>
                                 <a href="#?" id="addShiftNotForEmployee" style="text-decoration: none;color:#33AC3F;font-size:16px;font-weight:bold;" class="addButton"> +</a>
                                <a href='#?' style='text-decoration: none; list-style: none;color:#AA4935;font-size:16px;font-weight:bold;' class='shiftNotForEmployee_deleteButton'> x</a>
                            </ol>      
                        </li>
                    </ol>

                    <ol>
                        <li class="line nameContainer shiftForEmployee" id="shiftForEmployee">
                            <label for="Full_Name" class="hasTopFieldHelp"><?php echo __('该班次分配给某员工'); ?></label>
                            <ol class="fieldsInLine">
                                <li>
                                    <div class="fieldDescription"><em>*</em> <?php echo __('班次选择'); ?></div>
                                    <?php echo $form['shiftForEmployeeShiftSelect']->render(array("class" => "block default editable", "maxlength" => 30, "title" => __('班次选择'))); ?>
                                </li>

                                <li style="margin-bottom: 13px">
                                    <div class="fieldDescription"><?php echo __('权重(赋值越大，权重越高)'); ?></div>
                                    <?php echo $form['shiftForEmployeeWeight']->render(array("class" => "block default editable", "maxlength" => 30, "title" => __('Middle Name'))); ?>
                                </li>
                                <li>
                                    <div class="fieldDescription"><em>*</em> <?php echo __('选择员工'); ?></div>
                                    <?php echo $form['shiftForEmployee']->render(array("class" => "block default editable", "maxlength" => 30, "title" => __('Last Name'))); ?>
                                </li>
                                <li>
                                    <div class="fieldDescription"><em>*</em> <?php echo __('状态'); ?></div>
                                    <?php echo $form['shiftForEmployeeStatus']->render(array("class" => "block default editable", "maxlength" => 30, "title" => __('Last Name'))); ?>
                                </li>
                                <a href="#?" id="addShiftForEmployee" style="text-decoration: none;color:#33AC3F;font-size:16px;font-weight:bold;" class="addButton"> +</a>
                                <a href='#?' style='text-decoration: none; list-style: none;color:#AA4935;font-size:16px;font-weight:bold;' class='shiftForEmployee_deleteButton'> x</a>
                            </ol>      
                        </li>
                    </ol>


                    <ol>
                        <li class="line nameContainer assignmentAfterShift" id="assignmentAfterShift" >
                            <label for="Full_Name" class="hasTopFieldHelp"><?php echo __('该班次分配后持续分配'); ?></label>
                            <ol class="fieldsInLine">
                                <li>
                                    <div class="fieldDescription"><em>*</em> <?php echo __('班次选择'); ?></div>
                                    <?php echo $form['assignmentAfterShiftSelect']->render(array("class" => "block default editable", "maxlength" => 30, "title" => __('班次选择'))); ?>
                                </li>

                                <li style="margin-bottom: 13px">
                                    <div class="fieldDescription"><?php echo __('权重(赋值越大，权重越高)'); ?></div>
                                    <?php echo $form['assignmentAfterShiftWeight']->render(array("class" => "block default editable", "maxlength" => 30, "title" => __('Middle Name'))); ?>
                                </li>
                                <li>
                                    <div class="fieldDescription"><em>*</em> <?php echo __('选择天数'); ?></div>
                                    <?php echo $form['assignmentAfterShiftDays']->render(array("class" => "block default editable", "maxlength" => 30, "title" => __('Last Name'))); ?>
                                </li>
                                <li>
                                    <div class="fieldDescription"><em>*</em> <?php echo __('状态'); ?></div>
                                    <?php echo $form['assignmentAfterShiftStatus']->render(array("class" => "block default editable", "maxlength" => 30, "title" => __('Last Name'))); ?>
                                </li>
                                <a href="#?" id="addAssignmentAfterShift" style="text-decoration: none;color:#33AC3F;font-size:16px;font-weight:bold;" class="addButton"> +</a>
                                <a href='#?' style='text-decoration: none; list-style: none;color:#AA4935;font-size:16px;font-weight:bold;' class='assignmentAfterShift_deleteButton'> x</a>
                            </ol>      
                        </li>
                    </ol>


                    <ol>
                        <li class="line nameContainer noBehindShift" id="noBehindShift">
                            <label for="Full_Name" class="hasTopFieldHelp "><?php echo __('不希望此班次后继续班次'); ?></label>
                            <ol class="fieldsInLine">
                                <li>
                                    <div class="fieldDescription"><em>*</em> <?php echo __('选择班次'); ?></div>
                                    <?php echo $form['startShiftSelect']->render(array("class" => "block default editable", "maxlength" => 30, "title" => __('班次选择'))); ?>
                                </li>

                                <li>
                                    <div class="fieldDescription"><em>*</em> <?php echo __('不希望继续的班次'); ?></div>
                                    <?php echo $form['nextShiftSelect']->render(array("class" => "block default editable", "maxlength" => 30, "title" => __('Last Name'))); ?>
                                </li>

                                <li style="margin-bottom: 13px">
                                    <div class="fieldDescription"><?php echo __('权重(赋值越大，权重越高)'); ?></div>
                                    <?php echo $form['restAfterOneShiftWeight']->render(array("class" => "block default editable", "maxlength" => 30, "title" => __('Middle Name'))); ?>
                                </li>

                                <li>
                                    <div class="fieldDescription"><em>*</em> <?php echo __('状态'); ?></div>
                                    <?php echo $form['restAfterOneShiftStatus']->render(array("class" => "block default editable", "maxlength" => 30, "title" => __('Last Name'))); ?>
                                </li>
                                <a href="#?" id="addNoBehindShift" style="text-decoration: none;color:#33AC3F;font-size:16px;font-weight:bold;" class="addButton"> +</a>
                                <a href='#?' style='text-decoration: none; list-style: none;color:#AA4935;font-size:16px;font-weight:bold;' class='noBehindShift_deleteButton'> x</a>
                            </ol>      
                        </li>
                    </ol>

                    <ol>
                        <li class="line nameContainer freeTwoDays" id="freeTwoDays">
                            <label for="Full_Name" class="hasTopFieldHelp"><?php echo __('每周公休分配'); ?></label>
                            <ol class="fieldsInLine">

                                <li style="margin-bottom: 13px">
                                    <div class="fieldDescription"><?php echo __('权重(赋值越大，权重越高)'); ?></div>
                                    <?php echo $form['freeTwoDaysWeight']->render(array("class" => "block default editable", "maxlength" => 30, "title" => __('Middle Name'))); ?>
                                </li>
                                <li>
                                    <div class="fieldDescription"><em>*</em> <?php echo __('选择天数'); ?></div>
                                    <?php echo $form['freeTwoDaysSelect']->render(array("class" => "block default editable", "maxlength" => 30, "title" => __('Last Name'))); ?>
                                </li>
                                <li>
                                    <div class="fieldDescription"><em>*</em> <?php echo __('状态'); ?></div>
                                    <?php echo $form['freeTwoDaysStatus']->render(array("class" => "block default editable", "maxlength" => 30, "title" => __('Last Name'))); ?>
                                </li>
                            </ol>      
                        </li>
                    </ol>

                    <ol>
                        <li class="line nameContainer">
                            <label for="Full_Name" class="hasTopFieldHelp"><?php echo __('最多允许连续工作几个周末'); ?></label>
                            <ol class="fieldsInLine">

                                <li style="margin-bottom: 13px">
                                    <div class="fieldDescription"><?php echo __('权重(赋值越大，权重越高)'); ?></div>
                                    <?php echo $form['maxWeekendShiftWeight']->render(array("class" => "block default editable", "maxlength" => 30, "title" => __('Middle Name'))); ?>
                                </li>
                                <li>
                                    <div class="fieldDescription"><em>*</em> <?php echo __('允许几个周末'); ?></div>
                                    <?php echo $form['allowWeekendShift']->render(array("class" => "block default editable", "maxlength" => 30, "title" => __('Last Name'))); ?>
                                </li>
                                <li>
                                    <div class="fieldDescription"><em>*</em> <?php echo __('状态'); ?></div>
                                    <?php echo $form['maxWeekendShiftStatus']->render(array("class" => "block default editable", "maxlength" => 30, "title" => __('Last Name'))); ?>
                                </li>
                            </ol>      
                        </li>
                    </ol>

                    <ol>
                        <li class="line nameContainer">
                            <label for="Full_Name" class="hasTopFieldHelp"><?php echo __('最少任务天数分配'); ?></label>
                            <ol class="fieldsInLine">

                                <li style="margin-bottom: 13px">
                                    <div class="fieldDescription"><?php echo __('权重(赋值越大，权重越高)'); ?></div>
                                    <?php echo $form['minWorkDayWeight']->render(array("class" => "block default editable", "maxlength" => 30, "title" => __('Middle Name'))); ?>
                                </li>
                                <li>
                                    <div class="fieldDescription"><em>*</em> <?php echo __('最少工作天数'); ?></div>
                                    <?php echo $form['minWorkDayCount']->render(array("class" => "block default editable", "maxlength" => 30, "title" => __('Last Name'))); ?>
                                </li>
                                <li>
                                    <div class="fieldDescription"><em>*</em> <?php echo __('状态'); ?></div>
                                    <?php echo $form['minWorkDayStatus']->render(array("class" => "block default editable", "maxlength" => 30, "title" => __('Last Name'))); ?>
                                </li>
                            </ol>      
                        </li>
                    </ol>

                    <ol>
                        <li class="line nameContainer">
                            <label for="Full_Name" class="hasTopFieldHelp"><?php echo __('最多任务天数分配'); ?></label>
                            <ol class="fieldsInLine">

                                <li style="margin-bottom: 13px">
                                    <div class="fieldDescription"><?php echo __('权重(赋值越大，权重越高)'); ?></div>
                                    <?php echo $form['maxWorkDayWeight']->render(array("class" => "block default editable", "maxlength" => 30, "title" => __('Middle Name'))); ?>
                                </li>
                                <li>
                                    <div class="fieldDescription"><em>*</em> <?php echo __('最少工作天数'); ?></div>
                                    <?php echo $form['maxWorkDayCount']->render(array("class" => "block default editable", "maxlength" => 30, "title" => __('Last Name'))); ?>
                                </li>
                                <li>
                                    <div class="fieldDescription"><em>*</em> <?php echo __('状态'); ?></div>
                                    <?php echo $form['maxWorkDayStatus']->render(array("class" => "block default editable", "maxlength" => 30, "title" => __('Last Name'))); ?>
                                </li>
                            </ol>      
                        </li>
                    </ol>

                    <ol>
                        <li class="line nameContainer">
                            <label for="Full_Name" class="hasTopFieldHelp"><?php echo __('周六日连休'); ?></label>
                            <ol class="fieldsInLine">

                                <li style="margin-bottom: 13px">
                                    <div class="fieldDescription"><?php echo __('权重(赋值越大，权重越高)'); ?></div>
                                    <?php echo $form['restOnStaAndSunWeight']->render(array("class" => "block default editable", "maxlength" => 30, "title" => __('Middle Name'))); ?>
                                </li>
                                <li>
                                    <div class="fieldDescription"><em>*</em> <?php echo __('选择权限'); ?></div>
                                    <?php echo $form['restOnStaAndSunOn']->render(array("class" => "block default editable", "maxlength" => 30, "title" => __('Last Name'))); ?>
                                </li>
                                <li>
                                    <div class="fieldDescription"><em>*</em> <?php echo __('状态'); ?></div>
                                    <?php echo $form['restOnStaAndSunStatus']->render(array("class" => "block default editable", "maxlength" => 30, "title" => __('Last Name'))); ?>
                                </li>
                            </ol>      
                        </li>
                    </ol>


                    <ol>
                        <li class="line nameContainer">
                            <label for="Full_Name" class="hasTopFieldHelp"><?php echo __('周六工作在周二或周四安排调休'); ?></label>
                            <ol class="fieldsInLine">

                                <li style="margin-bottom: 13px">
                                    <div class="fieldDescription"><?php echo __('权重(赋值越大，权重越高)'); ?></div>
                                    <?php echo $form['restOnTuOrTuesWeight']->render(array("class" => "block default editable", "maxlength" => 30, "title" => __('Middle Name'))); ?>
                                </li>
                              
                                <li>
                                    <div class="fieldDescription"><em>*</em> <?php echo __('状态'); ?></div>
                                    <?php echo $form['restOnTuOrTuesStatus']->render(array("class" => "block default editable", "maxlength" => 30, "title" => __('Last Name'))); ?>
                                </li>
                            </ol>      
                        </li>
                    </ol>

                    <ol>
                        <li class="line nameContainer">
                            <label for="Full_Name" class="hasTopFieldHelp"><?php echo __('连续周末分配同一班次'); ?></label>
                            <ol class="fieldsInLine">

                                <li style="margin-bottom: 13px">
                                    <div class="fieldDescription"><?php echo __('权重(赋值越大，权重越高)'); ?></div>
                                    <?php echo $form['continuWeekOneShiftWeight']->render(array("class" => "block default editable", "maxlength" => 30, "title" => __('Middle Name'))); ?>
                                </li>
                              
                                <li>
                                    <div class="fieldDescription"><em>*</em> <?php echo __('状态'); ?></div>
                                    <?php echo $form['continuWeekOneShiftStatus']->render(array("class" => "block default editable", "maxlength" => 30, "title" => __('Last Name'))); ?>
                                </li>
                            </ol>      
                        </li>
                    </ol>

                    <ol>
                        <li class="line nameContainer">
                            <label for="Full_Name" class="hasTopFieldHelp"><?php echo __('最少允许连续工作几个周末'); ?></label>
                            <ol class="fieldsInLine">

                                <li style="margin-bottom: 13px">
                                    <div class="fieldDescription"><?php echo __('允许周末数'); ?></div>
                                    <?php echo $form['minWorkWeekendCount']->render(array("class" => "block default editable", "maxlength" => 30, "title" => __('Middle Name'))); ?>
                                </li>
                              
                                <li>
                                    <div class="fieldDescription"><em>*</em> <?php echo __('状态'); ?></div>
                                    <?php echo $form['minWorkWeekendStatus']->render(array("class" => "block default editable", "maxlength" => 30, "title" => __('Last Name'))); ?>
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

    $(function() { 
        
        $("#addNightLeisure").click(function() { 
            var count=$(".nightLeisure").length;

            var nighttempTr = $("#nightLeisure").clone(true); 
           
            //查找到第一个框
            var temoli=nighttempTr.children("ol").find("li").eq(0);
            var temoli2=nighttempTr.children("ol").find("li").eq(1);
            var temoli3=nighttempTr.children("ol").find("li").eq(2);

            //获取属性名
            var attrName= temoli.children("select").attr("name");
            var attrName2= temoli2.children("input").attr("name");
            // alert(attrName2);
            var name='nightAfterNightLeisureShiftSelect[' + count +']'+'';
            var name2='nightAfterNightLeisureWeight[' + count +']'+'';
            var name3='nightAfterNightLeisureStatus[' + count +']'+'';

            //属性赋值
            temoli.children("select").attr("name",name);
            temoli2.children("input").attr("name",name2);
            temoli3.children("select").attr("name",name3);

            $("#nightLeisure").after(nighttempTr); 

        }); 

        $(".deleteButton").click(function() { 
            if ($(".nightLeisure").length < 2) { 
                alert("至少保留一行!"); 
                } else { 
                if (confirm("确认删除?")) { 
                $(this).parent().parent().remove(); 
                } 
                } 
        });



        //班次尽量平均分配
        $("#addAverageAssignment").click(function() { 
            var count=$(".averageAssignment").length;

            var nighttempTr = $("#averageAssignment").clone(true); 

            var temoli=nighttempTr.children("ol").find("li").eq(0);
            var temoli2=nighttempTr.children("ol").find("li").eq(1);
            var temoli3=nighttempTr.children("ol").find("li").eq(2);
            var temoli4=nighttempTr.children("ol").find("li").eq(3);

            //获取属性名
     
            var name='averageAssignmentShiftSelect[' + count +']'+'';
            var name2='averageAssignmentWeight[' + count +']'+'';
            var name3='averageAssignment[' + count +']'+'';
            var name4='averageAssignmentStatus[' + count +']'+'';

            //属性赋值
            temoli.children("select").attr("name",name);
            temoli2.children("input").attr("name",name2);
            temoli3.children("input").attr("name",name3);
            temoli4.children("select").attr("name",name4);

            $("#averageAssignment").after(nighttempTr); 

        }); 

        $(".averageAssign_deleteButton").click(function() { 
            if ($(".averageAssignment").length < 2) { 
                alert("至少保留一行!"); 
                } else { 
                if (confirm("确认删除?")) { 
                $(this).parent().parent().remove(); 
                } 
                } 
        });

        //班次只给男神

        $("#addshiftdOnlyforMan").click(function() { 
            var count=$(".shiftdOnlyforMan").length;

            var nighttempTr = $("#shiftdOnlyforMan").clone(true); 
           
            //查找到第一个框
            var temoli=nighttempTr.children("ol").find("li").eq(0);
            var temoli2=nighttempTr.children("ol").find("li").eq(1);
            var temoli3=nighttempTr.children("ol").find("li").eq(2);

            //获取属性名
            var attrName= temoli.children("select").attr("name");
            var attrName2= temoli2.children("input").attr("name");
            // alert(attrName2);
            var name='shiftdOnlyforManShiftSelect[' + count +']'+'';
            var name2='shiftdOnlyforManWeight[' + count +']'+'';
            var name3='shiftdOnlyforManStatus[' + count +']'+'';

            //属性赋值
            temoli.children("select").attr("name",name);
            temoli2.children("input").attr("name",name2);
            temoli3.children("select").attr("name",name3);

            $("#shiftdOnlyforMan").after(nighttempTr); 

        }); 

        $(".shiftdOnlyforMan_deleteButton").click(function() { 
            if ($(".shiftdOnlyforMan").length < 2) { 
                alert("至少保留一行!"); 
                } else { 
                if (confirm("确认删除?")) { 
                $(this).parent().parent().remove(); 
                } 
                } 
        });


        //该班次分配后间隔后再分配
        $("#addAssignmentAfter").click(function() { 
            var count=$(".assignmentAfter").length;

            var nighttempTr = $("#assignmentAfter").clone(true); 

            var temoli=nighttempTr.children("ol").find("li").eq(0);
            var temoli2=nighttempTr.children("ol").find("li").eq(1);
            var temoli3=nighttempTr.children("ol").find("li").eq(2);
            var temoli4=nighttempTr.children("ol").find("li").eq(3);

            //获取属性名
     
            var name='assignmentAfterIntervalShiftSelect[' + count +']'+'';
            var name2='assignmentAfterIntervalWeight[' + count +']'+'';
            var name3='assignmentAfterIntervalEmployee[' + count +']'+'';
            var name4='assignmentAfterIntervalStatus[' + count +']'+'';

            //属性赋值
            temoli.children("select").attr("name",name);
            temoli2.children("input").attr("name",name2);
            temoli3.children("input").attr("name",name3);
            temoli4.children("select").attr("name",name4);

            $("#assignmentAfter").after(nighttempTr); 

        }); 

        $(".assignmentAfter_deleteButton").click(function() { 
            if ($(".assignmentAfter").length < 2) { 
                alert("至少保留一行!"); 
                } else { 
                if (confirm("确认删除?")) { 
                $(this).parent().parent().remove(); 
                } 
                } 
        });



        //该班次不分配给某员工

        $("#addShiftNotForEmployee").click(function() { 
            var count=$(".shiftNotForEmployee").length;

            var nighttempTr = $("#shiftNotForEmployee").clone(true); 

            var temoli=nighttempTr.children("ol").find("li").eq(0);
            var temoli2=nighttempTr.children("ol").find("li").eq(1);
            var temoli3=nighttempTr.children("ol").find("li").eq(2);
            var temoli4=nighttempTr.children("ol").find("li").eq(3);

            //获取属性名
     
            var name='shiftNotForEmployeeShiftSelect[' + count +']'+'';
            var name2='shiftNotForEmployeeWeight[' + count +']'+'';
            var name3='shiftNotForEmployee[' + count +']'+'';
            var name4='shiftNotForEmployeeStatus[' + count +']'+'';

            //属性赋值
            temoli.children("select").attr("name",name);
            temoli2.children("input").attr("name",name2);
            temoli3.children("select").attr("name",name3);
            temoli4.children("select").attr("name",name4);

            $("#shiftNotForEmployee").after(nighttempTr); 

        });


        //该班次分配给某员工

        $("#addShiftForEmployee").click(function() { 
            var count=$(".shiftForEmployee").length;

            var nighttempTr = $("#shiftForEmployee").clone(true); 

            var temoli=nighttempTr.children("ol").find("li").eq(0);
            var temoli2=nighttempTr.children("ol").find("li").eq(1);
            var temoli3=nighttempTr.children("ol").find("li").eq(2);
            var temoli4=nighttempTr.children("ol").find("li").eq(3);

            //获取属性名
     
            var name='shiftForEmployeeShiftSelect[' + count +']'+'';
            var name2='shiftForEmployeeWeight[' + count +']'+'';
            var name3='shiftForEmployee[' + count +']'+'';
            var name4='shiftForEmployeeStatus[' + count +']'+'';

            //属性赋值
            temoli.children("select").attr("name",name);
            temoli2.children("input").attr("name",name2);
            temoli3.children("select").attr("name",name3);
            temoli4.children("select").attr("name",name4);

            $("#shiftForEmployee").after(nighttempTr); 

        }); 

        $(".shiftForEmployee_deleteButton").click(function() { 
            if ($(".shiftForEmployee").length < 2) { 
                alert("至少保留一行!"); 
                } else { 
                if (confirm("确认删除?")) { 
                $(this).parent().parent().remove(); 
                } 
                } 
        });



        //该班次分配后持续分配

        $("#addAssignmentAfterShift").click(function() { 
            var count=$(".assignmentAfterShift").length;

            var nighttempTr = $("#assignmentAfterShift").clone(true); 

            var temoli=nighttempTr.children("ol").find("li").eq(0);
            var temoli2=nighttempTr.children("ol").find("li").eq(1);
            var temoli3=nighttempTr.children("ol").find("li").eq(2);
            var temoli4=nighttempTr.children("ol").find("li").eq(3);

            //获取属性名
     
            var name='assignmentAfterShiftSelect[' + count +']'+'';
            var name2='assignmentAfterShiftWeight[' + count +']'+'';
            var name3='assignmentAfterShiftDays[' + count +']'+'';
            var name4='assignmentAfterShiftStatus[' + count +']'+'';

            //属性赋值
            temoli.children("select").attr("name",name);
            temoli2.children("input").attr("name",name2);
            temoli3.children("select").attr("name",name3);
            temoli4.children("select").attr("name",name4);

            $("#assignmentAfterShift").after(nighttempTr); 

        }); 

        $(".assignmentAfterShift_deleteButton").click(function() { 
            if ($(".assignmentAfterShift").length < 2) { 
                alert("至少保留一行!"); 
                } else { 
                if (confirm("确认删除?")) { 
                $(this).parent().parent().remove(); 
                } 
                } 
        });


        //不希望此班次后继续班次

        $("#addNoBehindShift").click(function() { 
            var count=$(".noBehindShift").length;

            var nighttempTr = $("#noBehindShift").clone(true); 

            var temoli=nighttempTr.children("ol").find("li").eq(0);
            var temoli2=nighttempTr.children("ol").find("li").eq(1);
            var temoli3=nighttempTr.children("ol").find("li").eq(2);
            var temoli4=nighttempTr.children("ol").find("li").eq(3);
     
            var name='startShiftSelect[' + count +']'+'';
            var name2='nextShiftSelect[' + count +']'+'';
            var name3='restAfterOneShiftWeight[' + count +']'+'';
            var name4='restAfterOneShiftStatus[' + count +']'+'';

            //属性赋值
            temoli.children("select").attr("name",name);
            temoli2.children("select").attr("name",name2);
            temoli3.children("input").attr("name",name3);
            temoli4.children("select").attr("name",name4);

            $("#noBehindShift").after(nighttempTr); 

        }); 

        $(".noBehindShift_deleteButton").click(function() { 
            if ($(".noBehindShift").length < 2) { 
                alert("至少保留一行!"); 
                } else { 
                if (confirm("确认删除?")) { 
                $(this).parent().parent().remove(); 
                } 
                } 
        });



    }); 
    
 
    //]]>
</script>

<?php echo javascript_include_tag(plugin_web_path('orangehrmShiftPlugin', 'js/viewShiftContranctSuccess')); ?>
