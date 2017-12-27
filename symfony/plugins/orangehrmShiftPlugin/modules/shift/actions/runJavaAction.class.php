<?php


class runJavaAction extends baseShiftAction {

    public function execute($request) {
  

        $scheduleID=$request->getParameter('schedule_id');
        $this->getShiftService()->createXml($scheduleID);
        $this->schedule_id=$scheduleID;
     
        $result=$this->getShiftService()->getRosterResult($scheduleID);


 
      
        if(false ==$result){
            $file_path="/Users/wubin/Documents/Github/www/OrangeHRM/symfony/plugins/orangehrmShiftPlugin/lib/service/files/roster_".$scheduleID.'.xml';
            $roaster_path="/Users/wubin/Documents/Github/www/OrangeHRM/symfony/plugins/orangehrmShiftPlugin/lib/service/Linux-NurseRostering/NurseRostering.jar";
            $java_path="/Library/Java/JavaVirtualMachines/jdk-9.0.1.jdk/Contents/Home/bin/java";

            exec("$java_path  -jar $roaster_path  $file_path 2>&1",$output, $return_val);
            // sleep(500);
            $this->getShiftService()->setRosterResult($scheduleID);
        }

        $this->redirect("shift/createXML?schedule_id=$scheduleID");

    }

}

