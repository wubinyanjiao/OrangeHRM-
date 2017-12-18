
<div class="box ">
<div class="miniList" id="listing">
        <div class="head" align="center">
            <h1><?php echo __("轮班列表"); ?></h1>
        </div>
        
        <div class="inner">
     
            <form name="frmEmpDelDependents" id="frmEmpDelDependents" method="" action="">

                  <p id="listActions">
                
                    <input type="button" id="btnCSV" class="" value="<?php echo __("导出excel表"); ?>"/>
                  
                </p>
       
                <table id="dependent_list" class="table hover" >
                <!-- 标题 -->
                    <thead>
                        <tr>
                        	<th><?php echo __("姓名"); ?></th>
                            <th><?php echo __("所属部门"); ?></th>
		                     <?php foreach($date_list as $key=>$date){
                                ?>
		                     <th><?php echo $date ?></th>
		                     <?php }?>
                        </tr>
                    </thead>
                    <!-- 内容部分 -->
                    <tbody >
                        <?php
                            //循环所有员工
                      		foreach ($date_employ_list as $key=>$employee) :
                      		 $cssClass = ($row % 2) ? 'even' : 'odd';
                           	 echo '<tr class="' . $cssClass . '">';
                            
                         ?>

                           <td><?php echo $employeeList[$key]['firstName']; ?></td>
                           <?php
                                
                            foreach ($employee as $k => $kdepartment) {
                            ?>
                             <td><?php 
                               // echo'<pre>';var_dump($locationList);exit;

                             echo $locationList[$kdepartment['orangeDepartment']];?></td>
                            <?php
                                break;
                             }
                            ?>   
 	
                            


                            <?php
                                
                                foreach ($employee as $k => $kdepartment) {
                            ?>
                             <td><?php echo $locationList[$kdepartment['rotaryDepartment']];?></td>
                                
                                
                                   
                            <?php }
                            ?>      

                         <?php
                            echo '</tr>';
                            $row++;
                           	endforeach;
                        ?>
                       
                    </tbody>
                    <p>
                    
                </p>
                </table>                 
            </form>
        </div>
    </div> 
   </div>
   <script type="text/javascript">
      
    $(document).ready(function(){



         $('#btnCSV').click(function() {

            
            location.href = "<?php echo url_for('shift/createCSV?schedule_id='.$schedule_id) ?>";
        });

          $('#export').click(function(){
                
            })
       

    });
   </script>