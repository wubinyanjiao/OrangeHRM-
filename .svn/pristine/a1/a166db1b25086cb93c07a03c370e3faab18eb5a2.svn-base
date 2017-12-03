<?php 
use_stylesheet(plugin_web_path('orangehrmPimPlugin', 'css/viewPersonalDetailsSuccess.css'));
?>

<div class="box pimPane" id="employee-details">
    
    <?php echo include_component('pim', 'pimLeftMenu', array('empNumber'=>$empNumber, 'form' => $form));?>
    
    <div class="personalDetails" id="pdMainContainer">
        
        <div class="head">
            <h1><?php echo __('学历详细信息'); ?></h1>
        </div> <!-- head -->
    
        <div class="inner">

            <?php if ($personalInformationPermission->canRead()) : ?>

            <?php include_partial('global/flash_messages', array('prefix' => 'personaldetails')); ?>

            <form id="frmEmpPersonalDetails" method="post" action="<?php echo url_for('pim/viewEducationDetails'); ?>">

                <?php echo $form['_csrf_token']; ?>
                <?php echo $form['txtEmpID']->render(); ?>

                <fieldset>
                    <!--
                    <div class="helpLabelContainer">
                        <div><label>First Name</label></div>
                        <div><label>Middle Name</label></div>
                        <div><label>Last Name</label></div>
                    </div>
                    -->
                   
                    <ol>
                        <li>
                            <label for="personal_txtEmployeeId"><?php echo __('进院时学历'); ?></label>
                            <?php echo $form['txtEmployeeId']->render(array("maxlength" => 10, "class" => "editable")); ?>
                        </li>
                        <li>
                            <label for="personal_txtLicExpDate"><?php echo __('进院时间'); ?></label>
                            <?php echo $form['txtLicExpDate']->render(array("class"=>"calendar editable")); ?>
                        </li>
                        <li>
                            <label for="personal_txtOtherID"><?php echo __('毕业院校'); ?></label>
                            <?php echo $form['txtOtherID']->render(array("maxlength" => 30, "class" => "editable")); ?>
                        </li>
                        <li>
                            <label for="personal_graduationDate"><?php echo __('毕业时间'); ?></label>
                            <?php echo $form['graduationDate']->render(array("class"=>" editable")); ?>
                        </li>

                        <li class="long">
                            <label for="personal_txtLicenNo"><?php echo __("所学专业"); ?></label>
                            <?php echo $form['txtLicenNo']->render(array("maxlength" => 30, "class" => "editable")); ?>
                        </li>
                        
                        <?php if ($showSSN) : ?>
                        <li class="new">
                            <label for="personal_txtNICNo"><?php echo __('SSN Number'); ?></label>
                            <?php echo $form['txtNICNo']->render(array("class" => "editable", "maxlength" => 30)); ?>
                        </li>                    
                        <?php endif; ?>
                        <?php if ($showSIN) : ?>
                        <li class="<?php echo !($showSSN)?'new':''; ?>">
                            <label for="personal_txtSINNo"><?php echo __('SIN Number'); ?></label>
                            <?php echo $form['txtSINNo']->render(array("class" => "editable", "maxlength" => 30)); ?>
                        </li>                    
                        <?php endif; ?>  

                    </ol>
                    <ol>
                        <li>
                            <label for="personal_txtOtherID"><?php echo __('当前学历'); ?></label>
                            <?php echo $form['txtOtherID']->render(array("maxlength" => 30, "class" => "editable")); ?>
                        </li>
                        
                        <li>
                            <label for="personal_E_DOB"><?php echo __("当前学历毕业时间"); ?></label>
                            <?php echo $form['E_DOB']->render(array("class"=>"editable")); ?>
                        </li>
                        <li>
                            <label for="personal_txtOtherID"><?php echo __('当前学位'); ?></label>
                            <?php echo $form['txtOtherID']->render(array("maxlength" => 30, "class" => "editable")); ?>
                        </li>
                        

                        <li>
                            <label for="personal_D_DOB"><?php echo __("当前学位毕业时间"); ?></label>
                            <?php echo $form['D_DOB']->render(array("class"=>"editable")); ?>
                        </li>
                        <li>
                            <label for="personal_txtOtherID"><?php echo __('当前毕业院校'); ?></label>
                            <?php echo $form['txtOtherID']->render(array("maxlength" => 30, "class" => "editable")); ?>
                        </li>
                        <?php if(!$showDeprecatedFields) : ?>
                        <li class="required new">
                            <em>*</em> <?php echo __(CommonMessages::REQUIRED_FIELD); ?>
                        </li>
                        <?php endif; ?>
                    </ol>   
                       

                    <?php  if ($personalInformationPermission->canUpdate()) : ?>
                    <p><input type="button" id="btnSave" value="<?php echo __("Edit"); ?>" /></p>
                    <?php endif; ?>

                </fieldset>
            </form>

            <?php else : ?>
            <div><?php echo __(CommonMessages::RESTRICTED_SECTION); ?></div>
            <?php endif; ?>

        </div> <!-- inner -->
        
    </div> <!-- pdMainContainer -->

    
    <?php echo include_component('pim', 'customFields', array('empNumber'=>$empNumber, 'screen' => CustomField::SCREEN_PERSONAL_DETAILS));?>
    <?php echo include_component('pim', 'attachments', array('empNumber'=>$empNumber, 'screen' => EmployeeAttachment::SCREEN_PERSONAL_DETAILS));?>
    
</div> <!-- employee-details -->
 
<?php //echo stylesheet_tag('orangehrm.datepicker.css') ?>
<?php //echo javascript_include_tag('orangehrm.datepicker.js')?>

<script type="text/javascript">
    //<![CDATA[
    //we write javascript related stuff here, but if the logic gets lengthy should use a seperate js file
    var edit = "<?php echo __("Edit"); ?>";
    var save = "<?php echo __("Save"); ?>";
    var lang_firstNameRequired = "<?php echo __(ValidationMessages::REQUIRED); ?>";
    var lang_lastNameRequired = "<?php echo __(ValidationMessages::REQUIRED); ?>";
    var lang_selectGender = "<?php echo __(ValidationMessages::REQUIRED); ?>";
    var lang_processing = '<?php echo __(CommonMessages::LABEL_PROCESSING);?>';
    var lang_invalidDate = '<?php echo __(ValidationMessages::DATE_FORMAT_INVALID, array('%format%' => str_replace('yy', 'yyyy', get_datepicker_date_format($sf_user->getDateFormat())))) ?>';
    var datepickerDateFormat = '<?php echo get_datepicker_date_format($sf_user->getDateFormat()); ?>';

    var fileModified = 0;
    
    var readOnlyFields = <?php echo json_encode($form->getReadOnlyWidgetNames());?>
    
 
    //]]>
</script>

<?php echo javascript_include_tag(plugin_web_path('orangehrmPimPlugin', 'js/viewPersonalDetailsSuccess')); ?>
