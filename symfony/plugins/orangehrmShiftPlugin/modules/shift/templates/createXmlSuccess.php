
<div class="box ">
<div class="miniList" id="listing">
        <div class="head" align="center">
            <h1><?php echo __("排班列表"); ?></h1>
        </div>
        
        <div class="inner">
     
            <form name="frmEmpDelDependents" id="frmEmpDelDependents" method="" action="">

                <p id="listActions">
                    <input type="button" id="btnCSV" class="" value="<?php echo __("导出excel表"); ?>"/>
                    <input type="hidden" id="schedule_id" class="" value="<?php echo $schedule_id; ?>"/>
                    <input type="button" id="addTr2" value="循环">
                </p>
       
                <table id="dependent_list" class="table hover" >
                <!-- 标题 -->
                 
                        <tr>
                        	<th><?php echo __("姓名"); ?></th>
		                     <?php foreach($date_list as $key=>$date){?>
		                     <th><?php echo $date ?></th>
		                     <?php }?>
                        </tr>
                 
                    <!-- 内容部分 -->
                    
                        <?php
                      		foreach ($emarray as $key=>$employee) :
                      		 $cssClass = ($row % 2) ? 'even' : 'odd';
                           	 echo '<tr class="' . $cssClass . '">';



                            
                         ?>

                           <td><?php echo $employeeList[$key]['firstName']; ?></td>

                            <?php foreach($employee as $k=>$singempl){?>

                             <td> 
                             	<?php
                 
                             		foreach ($singempl as $ktyp => $shiftType) {

                             			$arr[$key][$k][$ktyp]=$shiftType;
                             		}


                                    foreach ($arr[$key][$k] as $ke => $ve) {
                                       
                                    
                             		// $valstr=implode('--', $arr[$key][$k]);

                             	?>					
								<a id="<?php echo $ke; ?>"data-toggle='modal' href='#languageDialog' style='text-decoration: none; list-style: none;color:#5d5d5d;font-size:14px;font-weight:bold;' class='averageAssign_deleteButton'><?php echo $ve; ?></a>
                                <?php }?>

                           
                             	
                             </td>

                            <?php
                        		}
                            ?>      

                         <?php
                            echo '</tr>';
                            $row++;
                           	endforeach;
                        ?>
                       
                
                    <p>
                    
                </p>
                </table>                 
            </form>
        </div>


        <!-- Message for supported languages -->
         <div class="modal hide" id="languageDialog">
            <div class="modal-header">
                <a class="close" data-dismiss="modal">×</a>
                <h3><?php echo __('创建类型')?></h3>
            </div>
            <div class="modal-body">
                <p>
                <form name="frmEmpEmgContact" id="frmEmpEmgContact" method="post" action="<?php echo url_for('shift/updateShiftResult?schedule_id=' . $schedule_id); ?>">
                    <?php echo $updateShiftResultForm['_csrf_token']; ?>
                    <?php echo $updateShiftResultForm["scheduleID"]->render(); ?>
                    <?php echo $updateShiftResultForm["shiftResultID"]->render(); ?>
                    <fieldset>
                        <ol>
                           
                            </li>
                           
                            <li>
                                <?php echo $updateShiftResultForm['employee']->renderLabel(__('选择员工') . ' <em>*</em>'); ?>
                                <?php echo $updateShiftResultForm['employee']->render(array("class" => "formInputText", "maxlength" => 25)); ?>
                            </li>
                          
                            
                            <li class="required">
                                <em>*</em><?php echo __(CommonMessages::REQUIRED_FIELD); ?>
                            </li>
                        </ol>
                        <p>
                            <input type="button" class="" name="btnSaveEContact" id="btnSaveEContact" value="<?php echo __("Save"); ?>"/>
                        </p>
                    </fieldset>
                </form>       
                </p>
              </div>
            
        </div>
        <!-- End-of-msg -->
    </div> 
   </div>
   <script type="text/javascript">

    var activateShiftUrl = '<?php echo url_for('shift/createXml?schedule_id=' . $schedule_id); ?>';
      
    $(document).ready(function(){

        function addTr(row){
            //获取table最后一行 $("#tab tr:last")
             //获取table第一行 $("#tab tr").eq(0)
             //获取table倒数第二行 $("#tab tr").eq(-2)
            trHtml= "<tr>"+$("#dependent_list  td:eq(0) tr:last").html()+"</tr>";
         
             var $tr=$("#dependent_list"+" tr").eq(row);
              
             if($tr.size()==0){
                alert("指定的table id或行数不存在！");
                return;
             }
             $tr.after(trHtml);
        }

        function delTr(){
            //获取选中的复选框，然后循环遍历删除
              
            $("#dependent_list tr:last td:eq(0)").remove();
                  
        }

        $('#btnCSV').click(function() {

            
            location.href = "<?php echo url_for('shift/createCSV?schedule_id='.$schedule_id) ?>";
        });


        $('#addTr2').click(function() {

            location.href = "<?php echo url_for('shift/rollXML?schedule_id='.$schedule_id) ?>";
            

        });


        $('#frmEmpDelDependents a').live('click', function() {
          
            var id = $(this).attr("id");

            $('#emgcontacts_shiftResultID').val(id);
            
        });

        $('#btnSaveEContact').click(function() {
            $('#frmEmpEmgContact').submit();
        });

    

       

    });
   </script>