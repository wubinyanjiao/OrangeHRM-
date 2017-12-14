<?php 
use_javascript(plugin_web_path('orangehrmShiftPlugin', 'js/addScheduleSuccess')); 

// use_stylesheet(plugin_web_path('orangehrmShiftPlugin', 'css/addScheduleSuccess.css'));

?>


<div id="workShift" class="box" <?php echo $hideForm ? "style='display:none'" : "";?> >
    
    <div class="head">
        <h1 id="workShiftHeading"><?php echo __("Work Shift"); ?></h1>
    </div>
    
    <div class="inner">

        <?php include_partial('global/form_errors', array('form' => $form)); ?>
        
        <form name="frmWorkShift" id="frmWorkShift" method="post" action="<?php echo url_for('shift/addSchedule'); ?>" >

            <?php echo $form['_csrf_token']; ?>
            <?php echo $form->renderHiddenFields(); ?>
            
            <fieldset>
                
                <ol>                    
                    <li>
                        <?php echo $form['name']->renderLabel(); ?>
                        <?php echo $form['name']->render(); ?>
                    </li>
                    
                    <li>
                        <?php echo $form['dateOfBirth']->renderLabel(__('第一个排班开始时间')); ?>
                        <?php echo $form['dateOfBirth']->render(array("class" => "formDateInput")); ?>    
                    </li>
                    <li class="radio">
                        <?php echo $form['copy']->renderLabel(__('选择区间') . ' <em>*</em>'); ?>
                        <?php echo $form['copy']->render(); ?>
                    </li>
                    <li class="radio">
                        <?php echo $form['status']->renderLabel(__('是否需要填报工作量') . ' <em>*</em>'); ?>
                        <?php echo $form['status']->render(); ?>
                    </li>
                    
                    <li class="" id="table2">
                      
                    </li>
                                         
                
                    <li class="required">
                        <em>*</em> <?php echo __(CommonMessages::REQUIRED_FIELD); ?>
                    </li>                    
                </ol>
                
                <p>
                    <input type="button" class="" name="btnSave" id="btnSave" value="<?php echo __("Save"); ?>"/>
                    <input type="button" class="reset" name="btnCancel" id="btnCancel" value="<?php echo __("Cancel"); ?>"/>
                </p>
                
            </fieldset>
            
        </form>
        
    </div>
    
</div>

<div id="customerList">
    <?php include_component('core', 'ohrmList', $parmetersForListCompoment); ?>
</div>

<!-- Confirmation box HTML: Begins -->
<div class="modal hide" id="deleteConfModal">
    <div class="modal-header">
        <a class="close" data-dismiss="modal">×</a>
        <h3><?php echo __('OrangeHRM - Confirmation Required'); ?></h3>
    </div>
    <div class="modal-body">
        <p><?php echo __(CommonMessages::DELETE_CONFIRMATION); ?></p>
    </div>
    <div class="modal-footer">
        <input type="button" class="btn" data-dismiss="modal" id="dialogDeleteBtn" value="<?php echo __('Ok'); ?>" />
        <input type="button" class="btn reset" data-dismiss="modal" value="<?php echo __('Cancel'); ?>" />
    </div>
</div>
<!-- Confirmation box HTML: Ends -->


<?php

    $dateFormat = get_datepicker_date_format($sf_user->getDateFormat());
    $displayDateFormat = str_replace('yy', 'yyyy', $dateFormat);
?>


<script type="text/javascript">

    var workShiftList = <?php echo $form->getScheduleListAsJson();?>;
    var lang_invalidDate = '<?php echo __(ValidationMessages::DATE_FORMAT_INVALID, array('%format%' => $displayDateFormat)) ?>';
        
    var workShiftInfoUrl = "<?php echo url_for("shift/createShift?schedule_id="); ?>";
    var workShiftEmpInfoUrl = "<?php echo url_for("admin/getWorkShiftEmpInfoJson?id="); ?>";

    var lang_Required = '<?php echo __(ValidationMessages::REQUIRED); ?>';
    var lang_companyRequired = "<?php echo __(ValidationMessages::REQUIRED); ?>";
    var lang_exceed50Charactors = '<?php echo __(ValidationMessages::TEXT_LENGTH_EXCEEDS, array('%amount%' => 50)); ?>';
    var lang_addWorkShift = "<?php echo __("Add Work Shift"); ?>";
    var lang_editWorkShift = "<?php echo __("Edit Work Shift"); ?>";
    var lang_nameAlreadyExist = '<?php echo __(ValidationMessages::ALREADY_EXISTS); ?>';
    var lang_FromTimeLessThanToTime = "<?php echo __('From time should be less than To time'); ?>";
    

    function getDate(datestr){  
              var temp = datestr.split("-");  
              var date = new Date(temp[0],temp[1]-1,temp[2]); 
              if(isNaN(date)){
                alert("请先选择年份");

                return false;
            }else{
                return date; 
            }
    } 

    function countDay(endTime,startTime){  
            $('#table2').show();
            $("#table2").empty();

              while((endTime.getTime()-startTime.getTime())>=0){  
                          var k=1;
                          var year = startTime.getFullYear();  
                          var month = (startTime.getMonth()+1).toString().length==1?"0"+(startTime.getMonth()+1).toString():(startTime.getMonth()+1).toString(); 
                          var day = startTime.getDate().toString().length==1?"0"+startTime.getDate():startTime.getDate();
                          var a = new Array("日", "一", "二", "三", "四", "五", "六");  
                          var week = startTime.getDay();  
                          var str = year+"-"+month+"-"+day+":星期"+ a[week];  
                     
                        $("#table2").append("<tr><td><input type='checkbox' 'id'='"+startTime.getTime()+"' 'value'='"+startTime.getTime()+"' name='shiftday' checked='checked'>"+str+"</td></tr>");
                        
                          startTime.setDate(startTime.getDate()+1);  
                    } 
    } 

     $(document).ready(function() {
        
          //单选框联动时间
        $("#workShift_copy_one").click(function(){

            var start_time=$('#dependent_dateOfBirth').val();
        
            var startTime = getDate(start_time); 
            if(startTime==false){
                this.checked=false;
            }

            //获取七天后时间
            var ddd=7;
            ttt=new Date(start_time).getTime()+ddd*24000*3600;
            endTime=new Date();
            endTime.setTime(ttt);

            countDay(endTime,startTime);

        });
         //单选框联动时间
        $("#workShift_copy_two").click(function(){

            var start_time=$('#dependent_dateOfBirth').val();
            var startTime = getDate(start_time);  
            if(startTime==false){
                this.checked=false;
            }
            //获取七天后时间
            var ddd=14;
            ttt=new Date(start_time).getTime()+ddd*24000*3600;
            endTime=new Date();
            endTime.setTime(ttt);

            countDay(endTime,startTime);

        });
        $("#workShift_copy_three").click(function(){

            var start_time=$('#dependent_dateOfBirth').val();
            var startTime = getDate(start_time);  
            if(startTime==false){
                this.checked=false;
            }
            //获取七天后时间
            var ddd=31;
            ttt=new Date(start_time).getTime()+ddd*24000*3600;
            endTime=new Date();
            endTime.setTime(ttt);

            countDay(endTime,startTime);

        });
        $("#workShift_copy_four").click(function(){

            var start_time=$('#dependent_dateOfBirth').val();
            var startTime = getDate(start_time);  
            if(startTime==false){
                this.checked=false;
            }
            //获取七天后时间
            var ddd=28;
            ttt=new Date(start_time).getTime()+ddd*24000*3600;
            endTime=new Date();
            endTime.setTime(ttt);

            countDay(endTime,startTime);

        });

        //如果不复制，默认只显示当天
        $("#workShift_copy_no").click(function(){
        $("#table2").empty();
            var start_time=$('#dependent_dateOfBirth').val();
            var startTime = getDate(start_time); 
            // alert(startTime);
            var year = startTime.getFullYear();  
            var month = (startTime.getMonth()+1).toString().length==1?"0"+(startTime.getMonth()+1).toString():(startTime.getMonth()+1).toString(); 
            var day = startTime.getDate().toString().length==1?"0"+startTime.getDate():startTime.getDate();
            var a = new Array("日", "一", "二", "三", "四", "五", "六");  
            var week = startTime.getDay();  
            var str = ":星期"+ a[week];  
                         
             $("#table2").append("<tr><td><input type='checkbox' 'id'='"+startTime.getTime()+"' 'value'='"+startTime.getTime()+"' name='shiftday' checked='checked'>"+str+"</td></tr>");

        });

     });

</script>