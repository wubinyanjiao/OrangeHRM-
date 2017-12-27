$(document).ready(function() {

    function getCheckValue(){
        var r=document.getElementsByName("shiftday");
        var shiftday = new Array();
        for(var i=0;i<r.length;i++){
             if(r[i].checked){
                shiftday.push(r[i].nextSibling.nodeValue);
           }
        }
   
        $('#workShift_copydays').val(shiftday);

    }

    $('#btnSave').click(function() {
        var selected = $.map( $('#workShift_assignedEmp option'),
            function(e) {
                return $(e).val();
            } );
        $('#workShift_assignedEmp').val(selected);
        
        getCheckValue();
        $('#frmWorkShift').submit();

    });
    
    $('#btnAdd').click(function() {
     
        
        $('#workShift_name').val('');
        $('#workShift_workHours_from').val('');
        $('#workShift_workHours_to').val('');
        $('#workShift_workShiftId').val('');
        // fillTotalTime();
        $('#workShiftHeading').html(lang_addWorkShift);
        $('#workShift').show();
        $('.top').hide();        
        $(".messageBalloon_success").remove();
    });
    
    $('#btnCancel').click(function() {
        $('#workShift').hide();
        $('.top').show();
        validator.resetForm();
    });
    
    $('a').click(function(){

        var row = $(this).closest("tr");
        var scheudleId = row.find('input').val();
        
        var url = workShiftInfoUrl+scheudleId;

        $("a").attr("href",url); 

    });

    $('#btnCatch').click(function() {
             var row = $(this).closest("tr");
            var scheudleId = row.find('input').val();workShiftResult
            var url = workShiftResult+scheudleId;

        $("a").attr("href",url); 
    });


    
    $('#btnDelete').attr('disabled', 'disabled');

        
    $("#ohrmList_chkSelectAll").click(function() {
        if($(":checkbox").length == 1) {
            $('#btnDelete').attr('disabled','disabled');
        }
        else {
            if($("#ohrmList_chkSelectAll").is(':checked')) {
                $('#btnDelete').removeAttr('disabled');
            } else {
                $('#btnDelete').attr('disabled','disabled');
            }
        }
    });
    
    $(':checkbox[name*="chkSelectRow[]"]').click(function() {
        if($(':checkbox[name*="chkSelectRow[]"]').is(':checked')) {
            $('#btnDelete').removeAttr('disabled');
        } else {
            $('#btnDelete').attr('disabled','disabled');
        }
    });

    $('#frmList_ohrmListComponent').attr('name','frmList_ohrmListComponent');
    $('#dialogDeleteBtn').click(function() {
        document.frmList_ohrmListComponent.submit();
    });
    
    // Bind On change event of From Time
    $('#workShift_workHours_from').change(function() {
        fillTotalTime();
    });

    // Bind On change event of To Time
    $('#workShift_workHours_to').change(function() {
        fillTotalTime();
    });    
        
    $.validator.addMethod("uniqueName", function(value, element, params) {
        
        var temp = true;
        var currentShift;
        var id = $('#workShift_workShiftId').val();
        var vcCount = workShiftList.length;
        for (var j=0; j < vcCount; j++) {
            if(id == workShiftList[j].id){
                currentShift = j;
            }
        }
        var i;
        vcName = $.trim($('#workShift_name').val()).toLowerCase();
        for (i=0; i < vcCount; i++) {

            arrayName = workShiftList[i].name.toLowerCase();
            if (vcName == arrayName) {
                temp = false
                break;
            }
        }
        if(currentShift != null){
            if(vcName == workShiftList[currentShift].name.toLowerCase()){
                temp = true;
            }
        }
		
        return temp;
    });
    
    $.validator.addMethod("validWorkHours", function(value, element) {
        var valid = true;

        var totalTime = getTotalTime();
        if (parseFloat(totalTime) <= 0) {
            valid = false;
        }

        return valid;  
    });
        
        
    var validator = $("#frmWorkShift").validate({

        rules: {
            'workShift[name]' : {
                required:true,
                uniqueName: true,
                maxlength: 50
            },
            
            'workShift[dateOfBirth]': {
                    required: true,
                    valid_date: function() {
                        return {
                            required: true,
                            format:datepickerDateFormat,
                            displayFormat:displayDateFormat
                        }
                    }
                },
            'workShift[copy]' : {
                required:true,
             
              
            },
         
        },
        messages: {
            'workShift[name]' : {
                required: lang_Required,
                uniqueName: lang_nameAlreadyExist,
                maxlength: lang_exceed50Charactors
            },
          
            'workShift[dateOfBirth]':{
               required:lang_invalidDate,
                    valid_date: lang_invalidDate
            },
            'workShift[workHours]':{
                required : lang_Required
            }            
        }

    });
});

function fillTotalTime() {        
    var total = getTotalTime();
    if (isNaN(total)) {
        total = '';
    }

    $('input.time_range_duration').val(total);
    $('#workShift_workHours_from').valid();
    $('#workShift_workHours_to').valid();
}

function getTotalTime() {
    var total = 0;
    var fromTime = ($('#workShift_workHours_from').val()).split(":");
    var fromdate = new Date();
    fromdate.setHours(fromTime[0],fromTime[1]);
        
    var toTime = ($('#workShift_workHours_to').val()).split(":");
    var todate = new Date();
    todate.setHours(toTime[0],toTime[1]);        
        
    var difference = todate - fromdate;
    var floatDeference	=	parseFloat(difference/3600000) ;
    total = Math.round(floatDeference*Math.pow(10,2))/Math.pow(10,2);
        
    return total;        
}

function getWorkShiftInfo(url){
    
    $.getJSON(url, function(data) {
        $('#workShift_workShiftId').val(data.id);
        $('#workShift_name').val(data.name);
        $('#workShift_workHours_from').val(data.start_time);
        $('#workShift_workHours_to').val(data.end_time);
        fillTotalTime();
        $('#workShift').show();
        $(".messageBalloon_success").remove();
        $('.top').hide();
    });
}

