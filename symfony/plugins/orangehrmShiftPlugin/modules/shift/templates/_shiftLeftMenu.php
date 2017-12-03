
<div id="sidebar">
    <ul id="sidenav" class="accordion">
     <?php foreach($menuItems as $key=>$value){    
        ?>
        <li>
            <div class="link"><i class="fa fa-paint-brush"></i><?php echo $value['shiftDate'];?><i class="fa fa-chevron-down"></i></div>
            <ul class="submenu">
                <?php foreach($value['shift_list'] as $k=>$v){ 

                    $start_time=substr($v['shift_type']['start_time'],0,5);
                    $end_time=substr($v['shift_type']['end_time'],0,5);
                    ?>
               
                <input type="hidden" id="shiftdate_<?php echo $v['id'];?>" maxlength="18" value="<?php echo $value['id'];?>"/>

                 <input type="hidden" id="schedule_<?php echo $v['id'];?>" maxlength="18" value="<?php echo $value['scheduleId'];?>"/>

                <input type="hidden" id="shifttype_<?php echo $v['id'];?>" maxlength="18" value="<?php echo $v['shift_type']['id'];?>"/>

                <input type="hidden" id="shiftstart_<?php echo $v['id'];?>" maxlength="18" value="<?php echo $start_time;?>"/>

                <input type="hidden" id="shiftend_<?php echo $v['id'];?>" maxlength="18" value="<?php echo $end_time;?>"/>
                <input type="hidden" id="status_<?php echo $v['id'];?>" maxlength="18" value="<?php echo $v['status'];?>"/>

                <input type="hidden" id="shiftrequireemploy_<?php echo $v['id'];?>" maxlength="18" value="<?php echo $v['required_employee'];?>"/>

                <li><a href="#" id="shiftname_<?php echo $v['shift']['id'];?>" value="<?php echo $v['id'];?>"><?php echo $v['name'];?></a></li>
                <?php } ?>
            </ul>
        </li>
    <?php }?>
      
    </ul>
</div> <!-- sidebar -->