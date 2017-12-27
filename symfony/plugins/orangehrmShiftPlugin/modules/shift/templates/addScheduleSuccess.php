<?php 
use_javascript(plugin_web_path('orangehrmShiftPlugin', 'js/addScheduleSuccess')); 

$haveContacts=$workScheduleList -> count();
// use_stylesheet(plugin_web_path('orangehrmShiftPlugin', 'css/addScheduleSuccess.css'));

?>


<div id="workShift" class="box" <?php echo $hideForm ? "style='display:none'" : "";?> >
    
    <div class="head">
        <h1 id="workShiftHeading"><?php echo __("排班计划"); ?></h1>
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
    <div id="customerList">
    
        <div class="head">
            <h1><?php echo __("计划列表"); ?></h1>
        </div>
        
        <div class="inner">
            
            <?php include_partial('global/flash_messages', array('prefix' => 'viewEmergencyContacts')); ?>
            
            <form name="frmEmpDelEmgContacts" id="frmEmpDelEmgContacts" method="post" action="">
       
             
                
                <table id="emgcontact_list" class="table hover">
                    <thead>
                        <tr>
                            
                            <th class="check" style="width:2%"><input type='checkbox' id='checkAll'/></th>
                           
                            <th><?php echo __("ID"); ?></th>
                            <th class="emgContactName"><?php echo __("轮班类型名"); ?></th>
                            <th><?php echo __("开始时间"); ?></th>
                       
                            <th><?php echo __("查看排班"); ?></th>
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
                        
                        foreach ($workScheduleList as $schedule) :
                            
                            $cssClass = ($row % 2) ? 'even' : 'odd';
                            echo '<tr class="' . $cssClass . '">';
                            echo "<td class='check'><input type='checkbox' class='checkbox' name='chkecontactdel[]' value='" . $schedule['id'] . "'/></td>";

                          
                            ?>
                            <td id="<?php echo $schedule['id']?>" value="<?php echo $schedule['id']?>"><?php echo $schedule['id'];?></td>
                            <td class="emgContactName" valign="top">
                                <a href="#" id="schedule_shift"><?php echo $schedule['name']; ?></a>
                            </td>
                             <input type="hidden" id="scheduleID_<?php echo $schedule->id; ?>" value="<?php echo $schedule->schedule_id?>" />
                            <input type="hidden" id="shiftTypeID_<?php echo $schedule->id; ?>" value="<?php echo $schedule->id?>" />

                            <input type="hidden" id="shiftTypeName_<?php echo $schedule->id; ?>" value="<?php echo $schedule->name?>" />
                            <input type="hidden" id="abbreviation_<?php echo $schedule->id; ?>" value="<?php echo $schedule->name?>" />
                            <input type="hidden" id="end_time_<?php echo $schedule->id; ?>" value="<?php echo $end_time;?>" />
                            <input type="hidden" id="start_time_<?php echo $schedule->id; ?>" value="<?php echo $start_time;?>" />
                            <input type="hidden" id="status_<?php echo $schedule->id; ?>" value="<?php echo $schedule->status;?>" />
                            <?php
                          
                            echo "<td valigh='top'>" . $schedule['shiftDate'] . '</td>';
                    
                            echo "<td valigh='top'><a id='btnCatch'>" .'点击查看'. '</a></td>';
                            echo '</tr>';
                            $row++;
                        endforeach;
                        } ?>
                    </tbody>
                </table>
            </form>
        </div>
</div>
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

       $('#btnCatch').click(function() {
            location.href = "<?php echo url_for('shift/runJava?schedule_id='.$schedule_id) ?>";
        });
        

    var workShiftList = <?php echo $form->getScheduleListAsJson();?>;
    var lang_invalidDate = '<?php echo __(ValidationMessages::DATE_FORMAT_INVALID, array('%format%' => $displayDateFormat)) ?>';
        
    var workShiftInfoUrl = "<?php echo url_for("shift/createShift?schedule_id="); ?>";
    var workShiftResult = "<?php echo url_for("shift/createXml?schedule_id="); ?>";
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
            var ddd=6;
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
            var ddd=12;
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
            var ddd=18;
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
            var ddd=24;
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