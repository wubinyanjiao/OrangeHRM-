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
 */
?>
<?php use_javascripts_for_form($form); ?>
<?php use_stylesheets_for_form($form); ?>
<?php if($holidayPermissions->canRead()){?>
<div id="location" class="box single">

    <div class="head">
        <h1 id="locationHeading"><?php echo ($editMode) ? __('Edit') . " " . __('Holiday') : __('Add') . " " . __('Holiday'); ?></h1>
    </div>

    <div class="inner">

        <?php include_partial('global/flash_messages'); ?>
        <form id="frmHoliday" name="frmHoliday" method="post" action="<?php echo url_for('leave/defineHoliday') ?>">

            <fieldset>

                <ol>
                    <?php echo $form->render(); ?>
                    <input type="hidden" name="hdnEditMode" id="hdnEditMode" value="<?php echo ($editMode) ? 'yes' : 'no'; ?>" />
                    <li class="required">
                        <em>*</em> <?php echo __(CommonMessages::REQUIRED_FIELD); ?>
                    </li>
                </ol>

                <p>

                <div class="formbuttons">    
                    <?php if(($holidayPermissions->canCreate() && empty($id)) || ($holidayPermissions->canUpdate() && $id > 0)){?>
                    <input type="button" class="savebutton" id="saveBtn" value="<?php echo __('Save'); ?>" />
                    <input type="button" class="reset" name="btnCancel" id="btnCancel" value="<?php echo __("Cancel"); ?>"/> 
                    <?php }?>
                </div>

                </p>

            </fieldset>

        </form>
    </div>
</div>
<?php }?>
<script type="text/javascript">
    //<![CDATA[
    var addhtml = '<input type="button" id="defineHoliday_addtime" value="添加时间" style="width:8%;margin-left:15px"><br />';
    var delete_button ='<img class="ui-datepicker-trigger delete_button" src="<?php echo public_path("/webres_598bd8c4489f52.47381308/themes/default/images/close.png")?>" style="margin-left:15px" alt="" title="">'
    $('#holiday_date').parent('').append(addhtml);
    var newdate = $('#holiday_newdate').val();
    if(newdate.length!=0){
        var strs= new Array(); //定义一数组

        strs=newdate.split(","); //字符分割
        for (i=0;i<strs.length ;i++ )
        {
            //document.write(strs[i]+"<br/>"); //分割后的字符输出
            add_newinput(strs[i]);
        } 
    }
    // alert(newdate);
    // var newdate = $('#holiday_newdate').val();
    // alert(newdate);
    //添加时间
    
    $('#defineHoliday_addtime').live('click', function () {
        add_newinput(0);
     });
    //删除时间
    $('.delete_button').live('click', function () {
        $(this).parent('div').remove();
     });

    
    var datepickerDateFormat = '<?php echo get_datepicker_date_format($sf_user->getDateFormat()); ?>';
    var backUrl = '<?php echo url_for('leave/viewHolidayList'); ?>';

    var lang_Required = '<?php echo __(ValidationMessages::REQUIRED); ?>';
    var lang_DateFormatIsWrong = '<?php echo __(ValidationMessages::DATE_FORMAT_INVALID, array('%format%' => str_replace('yy', 'yyyy', get_datepicker_date_format($sf_user->getDateFormat())))) ?>';
    var lang_NameIsOverLimit = "<?php echo __(ValidationMessages::TEXT_LENGTH_EXCEEDS, array('%amount%' => 200)); ?>";
    var disableWidgets = <?php echo ($editMode && !$holidayPermissions->canUpdate()) || (!$editMode && !$holidayPermissions->canCreate()) ? 'true' : 'false';?>; 

    function add_newinput(even){
        $.ajax({
            type: 'POST',
            url: '<?php echo url_for('leave/defineHolidayAjax'); ?>',
            cache: false,
            data : "newdate="+even,
            dataType: 'json',
            async:false, 
            success: function(msg){
                if(msg['stat'] == 200){
                    var newtext = '<div><br /><br />'+msg['message']+delete_button+'</div>';
                     $('#holiday_date').parent('').append(newtext);
                }
            },
            error:function(request){
                
            }
        });
    }
    //]]>
</script>
