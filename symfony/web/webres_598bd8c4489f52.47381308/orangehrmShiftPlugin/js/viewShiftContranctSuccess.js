$(document).ready(function() {

    //form validation
    $("#frmEmpPersonalDetails").validate({
        rules: {
            'personal[nightAfterNightLeisureWeight]': {required: true,digits: true },
            

            'personal[averageAssignmentWeight]': {required: true,digits: true },
            'personal[averageAssignment]': {required: true,digits: true },

            'personal[shiftdOnlyforManWeight]': {required: true,digits: true },


            'personal[freeTwoDaysWeight]': {required: true,digits: true },
            'personal[freeTwoDaysSelect]': {required: true},

            'personal[assignmentAfterIntervalWeight]': {required: true,digits: true },
            'personal[assignmentAfterIntervalShiftSelect]': {required: true},
            'personal[assignmentAfterIntervalEmployee]': {required: true,digits: true},

            'personal[assignmentAfterShiftWeight]': {required: true,digits: true },
            'personal[assignmentAfterShiftDays]': {required: true},

            'personal[maxWeekendShiftWeight]': {required: true,digits: true },
            'personal[allowWeekendShift]': {required: true,digits: true },

            'personal[restOnStaAndSunWeight]': {required: true,digits: true },
            'personal[restOnStaAndSunOn]': {required: true},


            'personal[shiftNotForEmployeeWeight]': {required: true,digits: true },
            'personal[shiftNotForEmployeeShiftSelect]': {required: true},
            'personal[shiftNotForEmployee]': {required: true},

            'personal[shiftForEmployeeWeight]': {required: true,digits: true },
            'personal[shiftForEmployeeShiftSelect]': {required: true},
            'personal[shiftForEmployee]': {required: true},


            'personal[restAfterOneShiftWeight]': {required: true,digits: true },
            'personal[startShiftSelect]': {required: true},
            'personal[nextShiftSelect]': {required: true},


             'personal[restOnTuOrTuesWeight]': {required: true,digits: true },

             'personal[continuWeekOneShiftWeight]': {required: true,digits: true },



            'personal[minWorkDayWeight]': {required: true,digits: true },
            'personal[minWorkDayCount]': {required: true,digits: true },

            'personal[maxWorkDayWeight]': {required: true,digits: true },
            'personal[maxWorkDayCount]': {required: true,digits: true },

            

            'personal[minWorkWeekendCount]': {required: true,digits: true },

            

          
        },
        messages: {
            'personal[nightAfterNightLeisureWeight]': { required: lang_firstNameRequired,digits: lang_weightShouldBeNumber },

            'personal[averageAssignmentWeight]': { required: lang_firstNameRequired,digits: lang_weightShouldBeNumber },
            'personal[averageAssignment]': { required: lang_firstNameRequired,digits: lang_weightShouldBeNumber },

            'personal[shiftdOnlyforManWeight]': { required: lang_firstNameRequired,digits: lang_weightShouldBeNumber },
            

            'personal[freeTwoDaysWeight]': { required: lang_firstNameRequired,digits: lang_weightShouldBeNumber },
            'personal[freeTwoDaysSelect]': { required: lang_firstNameRequired},

            'personal[assignmentAfterIntervalWeight]': { required: lang_firstNameRequired,digits: lang_weightShouldBeNumber },
            'personal[assignmentAfterIntervalShiftSelect]': { required: lang_firstNameRequired},
            'personal[assignmentAfterIntervalEmployee]': { required: lang_firstNameRequired,digits: lang_weightShouldBeNumber},

            'personal[assignmentAfterShiftWeight]': { required: lang_firstNameRequired,digits: lang_weightShouldBeNumber },
            'personal[assignmentAfterShiftDays]': { required: lang_firstNameRequired},
           
            'personal[maxWeekendShiftWeight]': { required: lang_firstNameRequired,digits: lang_weightShouldBeNumber },
            'personal[allowWeekendShift]': { required: lang_firstNameRequired,digits: lang_weightShouldBeNumber },


            'personal[restOnStaAndSunWeight]': { required: lang_firstNameRequired,digits: lang_weightShouldBeNumber },
            'personal[restOnStaAndSunOn]': { required: lang_firstNameRequired},

            'personal[shiftNotForEmployeeWeight]': { required: lang_firstNameRequired,digits: lang_weightShouldBeNumber },
            'personal[shiftNotForEmployeeShiftSelect]': { required: lang_firstNameRequired},
            'personal[shiftNotForEmployee]': { required: lang_firstNameRequired},

            'personal[shiftForEmployeeWeight]': { required: lang_firstNameRequired,digits: lang_weightShouldBeNumber },
            'personal[shiftForEmployeeShiftSelect]': { required: lang_firstNameRequired},
            'personal[shiftForEmployee]': { required: lang_firstNameRequired},


            'personal[restAfterOneShiftWeight]': { required: lang_firstNameRequired,digits: lang_weightShouldBeNumber },
            'personal[startShiftSelect]': { required: lang_firstNameRequired},
            'personal[nextShiftSelect]': { required: lang_firstNameRequired},

              
             'personal[restOnTuOrTuesWeight]': { required: lang_firstNameRequired,digits: lang_weightShouldBeNumber },

             'personal[continuWeekOneShiftWeight]': { required: lang_firstNameRequired,digits: lang_weightShouldBeNumber },


            'personal[minWorkDayWeight]': { required: lang_firstNameRequired,digits: lang_weightShouldBeNumber },
            'personal[minWorkDayCount]': { required: lang_firstNameRequired,digits: lang_weightShouldBeNumber },

            'personal[maxWorkDayWeight]': { required: lang_firstNameRequired,digits: lang_weightShouldBeNumber },
            'personal[maxWorkDayCount]': { required: lang_firstNameRequired,digits: lang_weightShouldBeNumber },


            'personal[minWorkWeekendCount]': { required: lang_firstNameRequired,digits: lang_weightShouldBeNumber },
        }
    });

    $(".editable").each(function(){
        $(this).attr("disabled", "disabled");
    });
    
    // Disable calendar elements
    $(".editable.calendar").datepicker('disable');
    
    $("#btnSave").click(function() {
        //if user clicks on Edit make all fields editable
        if($("#btnSave").attr('value') == edit) {
            
            $("#pdMainContainer .editable").each(function(){
                $(this).removeAttr("disabled");
            });            
            
            // Enable calendar elements that are not in readOnlyFields array
            $(".editable.calendar").each(function() {
                var fieldId = $(this).attr('id');
                
                if (fieldId.indexOf('personal_') == 0) {
                    var idWithoutPrefix = fieldId.slice(9);
                    if (-1 == jQuery.inArray(idWithoutPrefix, readOnlyFields)) {
                        $(this).datepicker('enable');
                    }
                }
            });
            
            
            // handle read only fields                
            for (var j = 0; j < readOnlyFields.length; j++) {
                var fieldId = '#personal_' + readOnlyFields[j];
                var field = $(fieldId);
                var fieldName = 'personal['+ readOnlyFields[j]+']';
                
                $('input[name="' + fieldName + '"]').attr('disabled', 'disabled');
                field.attr('disabled', 'disabled');
            }

            $("#btnSave").attr('value', save);
            return;
        }

        if($("#btnSave").attr('value') == save) {
            if ($("#frmEmpPersonalDetails").valid()) {
                $("#btnSave").val(lang_processing);
            }
            $("#frmEmpPersonalDetails").submit();
        }
    });
    });
