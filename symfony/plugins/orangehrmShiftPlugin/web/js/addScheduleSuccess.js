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

    // 获取复选框值
    function getCheckValue(){
        var r=document.getElementsByName("shiftday");
        var shiftday = new Array();
        for(var i=0;i<r.length;i++){
             if(r[i].checked){
                shiftday.push(r[i].nextSibling.nodeValue);
           }
        }
        $('#dependent_copydays').val(shiftday);

    }

     $(document).ready(function() {
        
        $('#addPaneDependent').hide();
        $('#table2').hide();
        <?php  if (!$haveDependents){?>
        $(".check").hide();
        <?php } ?>
        
        $("#checkAll").click(function(){
            if($("#checkAll:checked").attr('value') == 'on') {
                $(".checkbox").attr('checked', 'checked');
            } else {
                $(".checkbox").removeAttr('checked');
            }
            
            if($('.checkbox:checkbox:checked').length > 0) {
                $('#delDependentBtn').removeAttr('disabled');
            } else {
                $('#delDependentBtn').attr('disabled', 'disabled');
            }
        });

         //单选框联动时间
        $("#dependent_copy_one").click(function(){

            
            var start_time=$('#dependent_dateOfBirth').val();
        
            var startTime = getDate(start_time); 
            //获取七天后时间
            var ddd=7;
            ttt=new Date(start_time).getTime()+ddd*24000*3600;
            endTime=new Date();
            endTime.setTime(ttt);

            countDay(endTime,startTime);

        });
         //单选框联动时间
        $("#dependent_copy_two").click(function(){

            var start_time=$('#dependent_dateOfBirth').val();
            var startTime = getDate(start_time);  
            //获取七天后时间
            var ddd=14;
            ttt=new Date(start_time).getTime()+ddd*24000*3600;
            endTime=new Date();
            endTime.setTime(ttt);

            countDay(endTime,startTime);

        });
        $("#dependent_copy_three").click(function(){

            var start_time=$('#dependent_dateOfBirth').val();
            var startTime = getDate(start_time);  
            //获取七天后时间
            var ddd=31;
            ttt=new Date(start_time).getTime()+ddd*24000*3600;
            endTime=new Date();
            endTime.setTime(ttt);

            countDay(endTime,startTime);

        });
        $("#dependent_copy_four").click(function(){

            var start_time=$('#dependent_dateOfBirth').val();
            var startTime = getDate(start_time);  
            //获取七天后时间
            var ddd=28;
            ttt=new Date(start_time).getTime()+ddd*24000*3600;
            endTime=new Date();
            endTime.setTime(ttt);

            countDay(endTime,startTime);

        });

        //如果不复制，默认只显示当天
        $("#dependent_copy_no").click(function(){
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

        $(".checkbox").click(function() {
            $("#checkAll").removeAttr('checked');
            if(($(".checkbox").length - 1) == $(".checkbox:checked").length) {
                $("#checkAll").attr('checked', 'checked');
            }
            
            if($('.checkbox:checkbox:checked').length > 0) {
                $('#delDependentBtn').removeAttr('disabled');
            } else {
                $('#delDependentBtn').attr('disabled', 'disabled');
            }
        });

        hideShowRelationshipOther();
        
       // Edit a emergency contact in the list
        $('#frmEmpDelDependents a').live('click', function() {
            $("#heading").text("<?php echo __("修改计划");?>");
            var row = $(this).closest("tr");
            var seqNo = row.find('input.checkbox:first').val();
            var name = $(this).text();

            var relationshipType = $("#relationType_" + seqNo).val();
            var relationship = $("#relationship_" + seqNo).val();
            var dateOfBirth = $("#dateOfBirth_" + seqNo).val();

            $('#dependent_seqNo').val(seqNo);
            $('#dependent_name').val(name);
            $('#dependent_relationshipType').val(relationshipType);
            $('#dependent_dateOfBirth').val(relationship);

            if ($.trim(dateOfBirth) == '') {
                dateOfBirth = displayDateFormat;
            }
            $('#dependent_dateOfBirth').val(dateOfBirth);

            $('div#messagebar').hide();
            hideShowRelationshipOther();
            // hide validation error messages

            $('#listActions').hide();
            $('#dependent_list .check').hide();
            $('#addPaneDependent').css('display', 'block');

            $(".paddingLeftRequired").show();
            $('#btnCancel').show();

        });

        $('#dependent_relationshipType').change(function() {
            hideShowRelationshipOther();
        });

        // Cancel in add pane
        $('#btnCancel').click(function() {
            clearAddForm();
            $('#addPaneDependent').css('display', 'none');
            $('#listActions').show();
            $('#dependent_list .check').show();
            <?php if ($dependentPermissions->canUpdate()){?>
            addEditLinks();
            <?php }?>
            $('div#messagebar').hide();
            $(".paddingLeftRequired").hide();
        });

        // Add a emergency contact
        $('#btnAddDependent').click(function() {
            $("#heading").text("<?php echo __("创建计划");?>");
            clearAddForm();

            // Hide list action buttons and checkbox
            $('#listActions').hide();
            $('#dependent_list .check').hide();
            removeEditLinks();
            $('div#messagebar').hide();
            
            hideShowRelationshipOther();

            $('#addPaneDependent').css('display', 'block');

            $(".paddingLeftRequired").show();

        });

        /* Valid Contact Phone */
        $.validator.addMethod("validContactPhone", function(value, element) {

            if ( $('#dependent_homePhone').val() == '' && $('#dependent_mobilePhone').val() == '' &&
                    $('#dependent_workPhone').val() == '' )
                return false;
            else
                return true
        });
        
        $("#frmEmpDependent").validate({

            rules: {
                'dependent[name]' : {required:true, maxlength:100},
                'dependent[relationshipType]' : {required: true},
                'dependent[relationship]' : {required: function(element) {
                    return $('#dependent_relationshipType').val() == 'other';
                }},
                'dependent[dateOfBirth]' : {valid_date: function() {
                        return {format:datepickerDateFormat, required:false, displayFormat:displayDateFormat}
                    }
                },
                maxlength:100
            },
            messages: {
                'dependent[name]': {
                    required:'<?php echo __(ValidationMessages::REQUIRED) ?>',
                    maxlength: '<?php echo __(ValidationMessages::TEXT_LENGTH_EXCEEDS,array('%amount%' => 100)) ?>'
                },
                
                
                'dependent[dateOfBirth]' : {
                    valid_date: lang_validDateMsg
                }
            }
        });

        
        $('#delDependentBtn').click(function() {
            var checked = $('#frmEmpDelDependents input:checked').length;

            if (checked == 0) {
                $('div#messagebar').show();
                $("#messagebar").attr('class', "messageBalloon_notice");
                $("#messagebar").text('<?php echo __(TopLevelMessages::SELECT_RECORDS); ?>');
            } else {
                $('#frmEmpDelDependents').submit();
            }
        });

        $('#btnSaveDependent').click(function() {
       
            getCheckValue();
            $('#frmEmpDependent').submit();
        });
});