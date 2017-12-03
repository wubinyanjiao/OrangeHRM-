

<?php

// var_dump($result);exit;

?>




<div class="box ">
<div class="miniList" id="listing">
        <div class="head" align="center">
            <h1><?php echo __("排班列表"); ?></h1>
        </div>
        
        <div class="inner">
     
            <form name="frmEmpDelDependents" id="frmEmpDelDependents" method="" action="">
       
                <table id="dependent_list" class="table hover" >
                <!-- 标题 -->
                    <thead>
                        <tr>
                        	<th><?php echo __("姓名"); ?></th>
		                     <?php foreach($date_list as $key=>$date){?>
		                     <th><?php echo $date ?></th>
		                     <?php }?>
                        </tr>
                    </thead>
                    <!-- 内容部分 -->
                    <tbody >
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
                       
                    </tbody>
                    <p>
                    
                </p>
                </table>                 
            </form>
        </div>
    </div> 
   </div>