


<div class="box ">
<div class="miniList" id="listing">
        <div class="head" align="center">
            <h1><?php echo __("排班列表"); ?></h1>
        </div>
        
        <div class="inner">
     
            <form name="frmEmpDelDependents" id="frmEmpDelDependents" method="" action="">

                <p id="listActions">
                    <input type="button" id="btnCSV" class="" value="<?php echo __("导出excel表"); ?>"/>
                    
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

                             		$valstr=implode('--', $arr[$key][$k]);

                             	?>					
								<span href=""><?php echo $valstr ?></span>
                             	
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
    </div> 
   </div>
   <script type="text/javascript">
      
    $(document).ready(function(){

        function addTr(row){
            //获取table最后一行 $("#tab tr:last")
             //获取table第一行 $("#tab tr").eq(0)
             //获取table倒数第二行 $("#tab tr").eq(-2)
            trHtml= "<tr>"+$("#dependent_list tr:last").html()+"</tr>";
         
             var $tr=$("#dependent_list"+" tr").eq(row);
              
             if($tr.size()==0){
                alert("指定的table id或行数不存在！");
                return;
             }
             $tr.after(trHtml);
        }

        function delTr(){
            //获取选中的复选框，然后循环遍历删除
              
            $("#dependent_list tr:last").remove();
                  
        }

        $('#btnCSV').click(function() {

            
            location.href = "<?php echo url_for('shift/createCSV?schedule_id='.$schedule_id) ?>";
        });


        $('#addTr2').click(function() {



            addTr(0);
            delTr();

        });

       

    });
   </script>