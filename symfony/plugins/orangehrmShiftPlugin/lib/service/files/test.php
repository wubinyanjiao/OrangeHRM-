<?php

       //该部门员工中中级职称个数＝新加入的中级生个数＋剩余的中级生个数
                                                    $job_orang_thir=$this->getRotaryManWomen($thirDocEmp,'job_title',3);
                                                    $job_temp_thir=$this->getRotaryManWomen($tmp_thir,'job_title',3);
                                                    $total_job_thir=count($job_orang_thir)+count($job_temp_thir)-3;
                                                    $compare_job_thir=$total_job_thir-$data['averge_mid_level']['count'];

                                                    // var_dump($compare_gradaute_thir);exit;
                                                    //第一个部门，如果这个员工是男性，并且是研究生和中级。执行下面判断
                                                    if($thirDocEmp[$i]['gender']==1 && $thirDocEmp[$i]['graduate']==3 && $thirDocEmp[$i]['job_title']==3){//男，研究生，中层

                                                        //轮班后男士数量和研究生小于平均值，则选择其中经验最多的女性且其中非研究生参与轮转
                                                        if($compare_man_thir<0 && $compare_gradaute_thir<0 && $compare_job_thir<0){//只能选择经验最多的非研究生，并且是女员工,并且是中级员工

                                                            //获取部门中经验最多的女员工,
                                                            $women_orang_thir=$this->getRotaryManWomen($thirDocEmp,'gender',2);
                                                         
                                                            //经验最的的女性中的非研究生列表
                                                            $women_nogradute_orang_thir=$this->getRotaryNoGraduate($women_orang_thir,'graduate',3);
                                                            //获取非中中层
                                                            $women_nogradute_nolevel_orang_thir=$this->getRotaryNoGraduate($women_nogradute_orang_thir,'job_title',3);



                                                            if(null==$women_nogradute_nolevel_orang_thir){
                                                                break;
                                                            }else{
                                                                $women_orang_key_thir=array_column($women_nogradute_nolevel_orang_thir,'working_years','empNumber');
                                                           
                                                                $em_key_thir=$this->getMaxEmpNum($women_orang_key_thir,$thirDocEmp);
                                                        
                                                                $tmp_fir[$i]=$thirDocEmp[$em_key_thir];
                                                                $tmp_fir[$i]['date_from']=$monarr[$i];
                                                                $tmp_fir[$i]['rotary_department']=$firDocEmp[$i]['orange_department'];
                                                                $rotary_department_three=$thirDocEmp[$em_key_thir]['orange_department'];

                                                                unset($thirDocEmp[$em_key_thir]);
                                                            }        
                                                            
                                                        }else if($compare_man_thir<0 && $compare_gradaute_thir>-1 && $compare_job_thir<0){//只女非中层员工都可以轮转
                                                            //获取部门中经验最多的女员工,
                                                            $women_orang_thir=$this->getRotaryManWomen($thirDocEmp,'gender',2);
                                                            $nojobtitle_orang_thir=$this->getRotaryNoGraduate($women_orang_thir,'job_title',3);
                                                            if(null==$nojobtitle_orang_thir){
                                                                break;
                                                            }else{
                                                                $women_orang_key_thir=array_column($nojobtitle_orang_thir,'working_years','empNumber');
                                                                 //获取经验最多的女员工emnumber
                                                                $em_key_thir=$this->getMaxEmpNum($women_orang_key_thir,$thirDocEmp);
                                                        
                                                                $tmp_fir[$i]=$thirDocEmp[$em_key_thir];
                                                                $tmp_fir[$i]['date_from']=$monarr[$i];
                                                                $tmp_fir[$i]['rotary_department']=$firDocEmp[$i]['orange_department'];
                                                        
                                                                $rotary_department_three=$thirDocEmp[$em_key_thir]['orange_department'];

                                                                unset($thirDocEmp[$em_key_thir]);
                                                            }

                                                        }else if($compare_man_thir<0 && $compare_gradaute_thir>-1 && $compare_job_thir>-1){//只有女参与轮转
                                                            //获取部门中经验最多的女员工,
                                                            $women_orang_thir=$this->getRotaryManWomen($thirDocEmp,'gender',2);
                                                            
                                                            if(null==$women_orang_thir){
                                                                break;
                                                            }else{
                                                                $women_orang_key_thir=array_column($women_orang_thir,'working_years','empNumber');
                                                                 //获取经验最多的女员工emnumber
                                                                $em_key_thir=$this->getMaxEmpNum($women_orang_key_thir,$thirDocEmp);
                                                        
                                                                $tmp_fir[$i]=$thirDocEmp[$em_key_thir];
                                                                $tmp_fir[$i]['date_from']=$monarr[$i];
                                                                $tmp_fir[$i]['rotary_department']=$firDocEmp[$i]['orange_department'];
                                                        
                                                                $rotary_department_three=$thirDocEmp[$em_key_thir]['orange_department'];

                                                                unset($thirDocEmp[$em_key_thir]);
                                                            }

                                                        }else if($compare_man_thir<0 && $compare_gradaute_thir<0 && $compare_job_thir>-1){//只有女非研究生参与轮转
                                                            //获取部门中经验最多的女员工,
                                                            $women_orang_thir=$this->getRotaryManWomen($thirDocEmp,'gender',2);
                                                            //再获取其中不适研究生的
                                                            $nojobtitle_orang_thir=$this->getRotaryNoGraduate($women_orang_thir,'graduate',3);
                                                            
                                                            if(null==$nojobtitle_orang_thir){
                                                                break;
                                                            }else{
                                                                $nogradute_orang_key_thir=array_column($nojobtitle_orang_thir,'working_years','empNumber');
                                                                 //获取经验最多的女员工emnumber
                                                                $em_key_thir=$this->getMaxEmpNum($nogradute_orang_key_thir,$thirDocEmp);
                                                        
                                                                $tmp_fir[$i]=$thirDocEmp[$em_key_thir];
                                                                $tmp_fir[$i]['date_from']=$monarr[$i];
                                                                $tmp_fir[$i]['rotary_department']=$firDocEmp[$i]['orange_department'];
                                                                $rotary_department_three=$thirDocEmp[$em_key_thir]['orange_department'];

                                                                unset($thirDocEmp[$em_key_thir]);
                                                            }

                                                        }else if($compare_man_thir>-1 && $compare_gradaute_thir<0 && $compare_job_thir<0){//只要不是研究生，且不是中层，都可参与轮转

                                                            //获取非研究生的的列表
                                                            $nogradute_orang_thir=$this->getRotaryNoGraduate($thirDocEmp,'graduate',3);
                                                            $nojobtitle_orang_thir=$this->getRotaryNoGraduate($nogradute_orang_thir,'job_title',3);
                                                            
                                                            if(null==$nojobtitle_orang_thir){
                                                                break;
                                                            }else{
                                                                $nogradute_orang_key_thir=array_column($nojobtitle_orang_thir,'working_years','empNumber');
                                                                 //获取经验最多的女员工emnumber
                                                                $em_key_thir=$this->getMaxEmpNum($nogradute_orang_key_thir,$thirDocEmp);
                                                        
                                                                $tmp_fir[$i]=$thirDocEmp[$em_key_thir];
                                                                $tmp_fir[$i]['date_from']=$monarr[$i];
                                                                $tmp_fir[$i]['rotary_department']=$firDocEmp[$i]['orange_department'];
                                                                $rotary_department_three=$thirDocEmp[$em_key_thir]['orange_department'];

                                                                unset($thirDocEmp[$em_key_thir]);
                                                            }

                                                        }else if($compare_man_thir>-1 && $compare_gradaute_thir<0 && $compare_job_thir>-1){//只要不是中层，都可参与轮转

                                                            //获取非中层的的列表
                                                          
                                                            $nojobtitle_orang_thir=$this->getRotaryNoGraduate($thirDocEmp,'job_title',3);
                                                            
                                                            if(null==$nojobtitle_orang_thir){
                                                                break;
                                                            }else{
                                                                $nogradute_orang_key_thir=array_column($nojobtitle_orang_thir,'working_years','empNumber');
                                                                 //获取经验最多的女员工emnumber
                                                                $em_key_thir=$this->getMaxEmpNum($nogradute_orang_key_thir,$thirDocEmp);
                                                        
                                                                $tmp_fir[$i]=$thirDocEmp[$em_key_thir];
                                                                $tmp_fir[$i]['date_from']=$monarr[$i];
                                                                $tmp_fir[$i]['rotary_department']=$firDocEmp[$i]['orange_department'];
                                                                $rotary_department_three=$thirDocEmp[$em_key_thir]['orange_department'];

                                                                unset($thirDocEmp[$em_key_thir]);
                                                            }

                                                        }else if($compare_man_thir>-1 && $compare_gradaute_thir>-1 && $compare_job_thir<0){//只有非中层参与轮转
                                                            //获取部门中经验最多的女员工,
                                                           $nojobtitle_orang_thir=$this->getRotaryNoGraduate($thirDocEmp,'job_title',3);
                                                            if(null==$nojobtitle_orang_thir){
                                                                break;
                                                            }else{
                                                                $women_orang_key_thir=array_column($nojobtitle_orang_thir,'working_years','empNumber');
                                                                 //获取经验最多的女员工emnumber
                                                                $em_key_thir=$this->getMaxEmpNum($women_orang_key_thir,$thirDocEmp);
                                                        
                                                                $tmp_fir[$i]=$thirDocEmp[$em_key_thir];
                                                                $tmp_fir[$i]['date_from']=$monarr[$i];
                                                                $tmp_fir[$i]['rotary_department']=$firDocEmp[$i]['orange_department'];
                                                        
                                                                $rotary_department_three=$thirDocEmp[$em_key_thir]['orange_department'];

                                                                unset($thirDocEmp[$em_key_thir]);
                                                            }

                                                        }else {//如果轮班后男士数和研究生数大于或者等于平均值，将该员工轮转到第二个部门
                                                            $tmp_fir[$i]=$thirDocEmp[$i];
                                                            $tmp_fir[$i]['date_from']=$monarr[$i];
                                                            $tmp_fir[$i]['rotary_department']=$firDocEmp[$i]['orange_department'];
                                                            $rotary_department_three=$thirDocEmp[$i]['orange_department'];
                                                            unset($thirDocEmp[$i]);
                                                        }
                                                       
                                                    }else if($thirDocEmp[$i]['gender']==1 && $thirDocEmp[$i]['graduate']==3 && $thirDocEmp[$i]['job_title']!=3){//男，研究生,非中层

                                                        if($compare_man_thir<0 && $compare_gradaute_thir<0 && $compare_job_thir<0){//只能选择经验最多的非研究生，并且是女员工

                                                     
                                                            $women_orang_thir=$this->getRotaryManWomen($thirDocEmp,'gender',2);
                                                         
                                                            //经验最的的女性中的非研究生列表
                                                            $women_nogradute_orang_thir=$this->getRotaryNoGraduate($women_orang_thir,'graduate',3);
                                                            //获取非中中层
                                                            $women_nogradute_nolevel_orang_thir=$this->getRotaryNoGraduate($women_nogradute_orang_thir,'job_title',3);

                                                            if(null==$women_nogradute_nolevel_orang_thir){
                                                                break;
                                                            }else{
                                                                $women_orang_key_thir=array_column($women_nogradute_nolevel_orang_thir,'working_years','empNumber');
                                                           
                                                                $em_key_thir=$this->getMaxEmpNum($women_orang_key_thir,$thirDocEmp);
                                                        
                                                                $tmp_fir[$i]=$thirDocEmp[$em_key_thir];
                                                                $tmp_fir[$i]['date_from']=$monarr[$i];
                                                                $tmp_fir[$i]['rotary_department']=$firDocEmp[$i]['orange_department'];
                                                                $rotary_department_three=$thirDocEmp[$em_key_thir]['orange_department'];

                                                                unset($thirDocEmp[$em_key_thir]);
                                                            }              
                                                            
                                                        }else if($compare_man_thir>-1 && $compare_gradaute_thir<0 && $compare_job_thir<0){//只要不是研究生，且不是中层，都可参与轮转

                                                            //获取非研究生的的列表
                                                            $nogradute_orang_thir=$this->getRotaryNoGraduate($thirDocEmp,'graduate',3);
                                                            $nojobtitle_orang_thir=$this->getRotaryNoGraduate($nogradute_orang_thir,'job_title',3);
                                                            
                                                            if(null==$nojobtitle_orang_thir){
                                                                break;
                                                            }else{
                                                                $nogradute_orang_key_thir=array_column($nojobtitle_orang_thir,'working_years','empNumber');
                                                                 //获取经验最多的女员工emnumber
                                                                $em_key_thir=$this->getMaxEmpNum($nogradute_orang_key_thir,$thirDocEmp);
                                                        
                                                                $tmp_fir[$i]=$thirDocEmp[$em_key_thir];
                                                                $tmp_fir[$i]['date_from']=$monarr[$i];
                                                                $tmp_fir[$i]['rotary_department']=$firDocEmp[$i]['orange_department'];
                                                                $rotary_department_three=$thirDocEmp[$em_key_thir]['orange_department'];

                                                                unset($thirDocEmp[$em_key_thir]);
                                                            }

                                                        }else if($compare_man_thir<0 && $compare_gradaute_thir>-1 && $compare_job_thir<0){//只女非中层员工都可以轮转
                                                            //获取部门中经验最多的女员工,
                                                            $women_orang_thir=$this->getRotaryManWomen($thirDocEmp,'gender',2);
                                                            $nojobtitle_orang_thir=$this->getRotaryNoGraduate($women_orang_thir,'job_title',3);
                                                            if(null==$nojobtitle_orang_thir){
                                                                break;
                                                            }else{
                                                                $women_orang_key_thir=array_column($nojobtitle_orang_thir,'working_years','empNumber');
                                                                 //获取经验最多的女员工emnumber
                                                                $em_key_thir=$this->getMaxEmpNum($women_orang_key_thir,$thirDocEmp);
                                                        
                                                                $tmp_fir[$i]=$thirDocEmp[$em_key_thir];
                                                                $tmp_fir[$i]['date_from']=$monarr[$i];
                                                                $tmp_fir[$i]['rotary_department']=$firDocEmp[$i]['orange_department'];
                                                        
                                                                $rotary_department_three=$thirDocEmp[$em_key_thir]['orange_department'];

                                                                unset($thirDocEmp[$em_key_thir]);
                                                            }

                                                        }else if($compare_man_thir>-1 && $compare_gradaute_thir<0 && $compare_job_thir>-1){//只要不是中层，都可参与轮转

                                                            //获取非中层的的列表
                                                          
                                                            $nojobtitle_orang_thir=$this->getRotaryNoGraduate($thirDocEmp,'job_title',3);
                                                            
                                                            if(null==$nojobtitle_orang_thir){
                                                                break;
                                                            }else{
                                                                $nogradute_orang_key_thir=array_column($nojobtitle_orang_thir,'working_years','empNumber');
                                                                 //获取经验最多的女员工emnumber
                                                                $em_key_thir=$this->getMaxEmpNum($nogradute_orang_key_thir,$thirDocEmp);
                                                        
                                                                $tmp_fir[$i]=$thirDocEmp[$em_key_thir];
                                                                $tmp_fir[$i]['date_from']=$monarr[$i];
                                                                $tmp_fir[$i]['rotary_department']=$firDocEmp[$i]['orange_department'];
                                                                $rotary_department_three=$thirDocEmp[$em_key_thir]['orange_department'];

                                                                unset($thirDocEmp[$em_key_thir]);
                                                            }

                                                        }else if($compare_man_thir<0 && $compare_gradaute_thir<0 && $compare_job_thir>-1){//只有女非研究生参与轮转
                                                            //获取部门中经验最多的女员工,
                                                            $women_orang_thir=$this->getRotaryManWomen($thirDocEmp,'gender',2);
                                                            //再获取其中不适研究生的
                                                            $nojobtitle_orang_thir=$this->getRotaryNoGraduate($women_orang_thir,'graduate',3);
                                                            
                                                            if(null==$nojobtitle_orang_thir){
                                                                break;
                                                            }else{
                                                                $nogradute_orang_key_thir=array_column($nojobtitle_orang_thir,'working_years','empNumber');
                                                                 //获取经验最多的女员工emnumber
                                                                $em_key_thir=$this->getMaxEmpNum($nogradute_orang_key_thir,$thirDocEmp);
                                                        
                                                                $tmp_fir[$i]=$thirDocEmp[$em_key_thir];
                                                                $tmp_fir[$i]['date_from']=$monarr[$i];
                                                                $tmp_fir[$i]['rotary_department']=$firDocEmp[$i]['orange_department'];
                                                                $rotary_department_three=$thirDocEmp[$em_key_thir]['orange_department'];

                                                                unset($thirDocEmp[$em_key_thir]);
                                                            }

                                                        }else if($compare_man_thir<0 && $compare_gradaute_thir>-1 && $compare_job_thir>-1){//只有女参与轮转
                                                            //获取部门中经验最多的女员工,
                                                            $women_orang_thir=$this->getRotaryManWomen($thirDocEmp,'gender',2);
                                                            
                                                            if(null==$women_orang_thir){
                                                                break;
                                                            }else{
                                                                $women_orang_key_thir=array_column($women_orang_thir,'working_years','empNumber');
                                                                 //获取经验最多的女员工emnumber
                                                                $em_key_thir=$this->getMaxEmpNum($women_orang_key_thir,$thirDocEmp);
                                                        
                                                                $tmp_fir[$i]=$thirDocEmp[$em_key_thir];
                                                                $tmp_fir[$i]['date_from']=$monarr[$i];
                                                                $tmp_fir[$i]['rotary_department']=$firDocEmp[$i]['orange_department'];
                                                        
                                                                $rotary_department_three=$thirDocEmp[$em_key_thir]['orange_department'];

                                                                unset($thirDocEmp[$em_key_thir]);
                                                            }

                                                        }else{//如果该元满足轮转条件，直接参与轮转
                                                            $tmp_fir[$i]=$thirDocEmp[$i];
                                                            $tmp_fir[$i]['date_from']=$monarr[$i];
                                                            $tmp_fir[$i]['rotary_department']=$firDocEmp[$i]['orange_department'];
                                                            $rotary_department_three=$thirDocEmp[$i]['orange_department'];
                                                            unset($thirDocEmp[$i]);
                                                        }

                                                    }else if($thirDocEmp[$i]['gender']==1 && $thirDocEmp[$i]['graduate']!=3 && $thirDocEmp[$i]['job_title']!=3){//男，非研究生,非中层

                                                        if($compare_man_thir<0 && $compare_gradaute_thir<0 && $compare_job_thir<0){//只能选择经验最多的非研究生，并且是女员工

                                                     
                                                            $women_orang_thir=$this->getRotaryManWomen($thirDocEmp,'gender',2);
                                                         
                                                            //经验最的的女性中的非研究生列表
                                                            $women_nogradute_orang_thir=$this->getRotaryNoGraduate($women_orang_thir,'graduate',3);
                                                            //获取非中中层
                                                            $women_nogradute_nolevel_orang_thir=$this->getRotaryNoGraduate($women_nogradute_orang_thir,'job_title',3);

                                                            if(null==$women_nogradute_nolevel_orang_thir){
                                                                break;
                                                            }else{
                                                                $women_orang_key_thir=array_column($women_nogradute_nolevel_orang_thir,'working_years','empNumber');
                                                           
                                                                $em_key_thir=$this->getMaxEmpNum($women_orang_key_thir,$thirDocEmp);
                                                        
                                                                $tmp_fir[$i]=$thirDocEmp[$em_key_thir];
                                                                $tmp_fir[$i]['date_from']=$monarr[$i];
                                                                $tmp_fir[$i]['rotary_department']=$firDocEmp[$i]['orange_department'];
                                                                $rotary_department_three=$thirDocEmp[$em_key_thir]['orange_department'];

                                                                unset($thirDocEmp[$em_key_thir]);
                                                            }              
                                                            
                                                        }else if($compare_man_thir<0 && $compare_gradaute_thir>-1 && $compare_job_thir<0){//只女非中层员工都可以轮转
                                                            //获取部门中经验最多的女员工,
                                                            $women_orang_thir=$this->getRotaryManWomen($thirDocEmp,'gender',2);
                                                            $nojobtitle_orang_thir=$this->getRotaryNoGraduate($women_orang_thir,'job_title',3);
                                                            if(null==$nojobtitle_orang_thir){
                                                                break;
                                                            }else{
                                                                $women_orang_key_thir=array_column($nojobtitle_orang_thir,'working_years','empNumber');
                                                                 //获取经验最多的女员工emnumber
                                                                $em_key_thir=$this->getMaxEmpNum($women_orang_key_thir,$thirDocEmp);
                                                        
                                                                $tmp_fir[$i]=$thirDocEmp[$em_key_thir];
                                                                $tmp_fir[$i]['date_from']=$monarr[$i];
                                                                $tmp_fir[$i]['rotary_department']=$firDocEmp[$i]['orange_department'];
                                                        
                                                                $rotary_department_three=$thirDocEmp[$em_key_thir]['orange_department'];

                                                                unset($thirDocEmp[$em_key_thir]);
                                                            }

                                                        }else if($compare_man_thir<0 && $compare_gradaute_thir>-1 && $compare_job_thir>-1){//只有女参与轮转
                                                            //获取部门中经验最多的女员工,
                                                            $women_orang_thir=$this->getRotaryManWomen($thirDocEmp,'gender',2);
                                                            
                                                            if(null==$women_orang_thir){
                                                                break;
                                                            }else{
                                                                $women_orang_key_thir=array_column($women_orang_thir,'working_years','empNumber');
                                                                 //获取经验最多的女员工emnumber
                                                                $em_key_thir=$this->getMaxEmpNum($women_orang_key_thir,$thirDocEmp);
                                                        
                                                                $tmp_fir[$i]=$thirDocEmp[$em_key_thir];
                                                                $tmp_fir[$i]['date_from']=$monarr[$i];
                                                                $tmp_fir[$i]['rotary_department']=$firDocEmp[$i]['orange_department'];
                                                        
                                                                $rotary_department_three=$thirDocEmp[$em_key_thir]['orange_department'];

                                                                unset($thirDocEmp[$em_key_thir]);
                                                            }

                                                        }else if($compare_man_thir<0 && $compare_gradaute_thir<0 && $compare_job_thir>-1){//只有女非研究生参与轮转
                                                            //获取部门中经验最多的女员工,
                                                            $women_orang_thir=$this->getRotaryManWomen($thirDocEmp,'gender',2);
                                                            //再获取其中不适研究生的
                                                            $nojobtitle_orang_thir=$this->getRotaryNoGraduate($women_orang_thir,'graduate',3);
                                                            
                                                            if(null==$nojobtitle_orang_thir){
                                                                break;
                                                            }else{
                                                                $nogradute_orang_key_thir=array_column($nojobtitle_orang_thir,'working_years','empNumber');
                                                                 //获取经验最多的女员工emnumber
                                                                $em_key_thir=$this->getMaxEmpNum($nogradute_orang_key_thir,$thirDocEmp);
                                                        
                                                                $tmp_fir[$i]=$thirDocEmp[$em_key_thir];
                                                                $tmp_fir[$i]['date_from']=$monarr[$i];
                                                                $tmp_fir[$i]['rotary_department']=$firDocEmp[$i]['orange_department'];
                                                                $rotary_department_three=$thirDocEmp[$em_key_thir]['orange_department'];

                                                                unset($thirDocEmp[$em_key_thir]);
                                                            }

                                                        }else{//如果该元满足轮转条件，直接参与轮转
                                                            $tmp_fir[$i]=$thirDocEmp[$i];
                                                            $tmp_fir[$i]['date_from']=$monarr[$i];
                                                            $tmp_fir[$i]['rotary_department']=$firDocEmp[$i]['orange_department'];
                                                            $rotary_department_three=$thirDocEmp[$i]['orange_department'];
                                                            unset($thirDocEmp[$i]);
                                                        }

                                                    }else if($thirDocEmp[$i]['gender']==1 && $thirDocEmp[$i]['graduate']!=3 && $thirDocEmp[$i]['job_title']==3){//男，非研究生,中层

                                                        if($compare_man_thir<0 && $compare_gradaute_thir<0 && $compare_job_thir<0){//只能选择经验最多的非研究生，并且是女员工

                                                     
                                                            $women_orang_thir=$this->getRotaryManWomen($thirDocEmp,'gender',2);
                                                         
                                                            //经验最的的女性中的非研究生列表
                                                            $women_nogradute_orang_thir=$this->getRotaryNoGraduate($women_orang_thir,'graduate',3);
                                                            //获取非中中层
                                                            $women_nogradute_nolevel_orang_thir=$this->getRotaryNoGraduate($women_nogradute_orang_thir,'job_title',3);

                                                            if(null==$women_nogradute_nolevel_orang_thir){
                                                                break;
                                                            }else{
                                                                $women_orang_key_thir=array_column($women_nogradute_nolevel_orang_thir,'working_years','empNumber');
                                                           
                                                                $em_key_thir=$this->getMaxEmpNum($women_orang_key_thir,$thirDocEmp);
                                                        
                                                                $tmp_fir[$i]=$thirDocEmp[$em_key_thir];
                                                                $tmp_fir[$i]['date_from']=$monarr[$i];
                                                                $tmp_fir[$i]['rotary_department']=$firDocEmp[$i]['orange_department'];
                                                                $rotary_department_three=$thirDocEmp[$em_key_thir]['orange_department'];

                                                                unset($thirDocEmp[$em_key_thir]);
                                                            }              
                                                            
                                                        }else if($compare_man_thir>-1 && $compare_gradaute_thir<0 && $compare_job_thir<0){//只要不是研究生，且不是中层，都可参与轮转

                                                            //获取非研究生的的列表
                                                            $nogradute_orang_thir=$this->getRotaryNoGraduate($thirDocEmp,'graduate',3);
                                                            $nojobtitle_orang_thir=$this->getRotaryNoGraduate($nogradute_orang_thir,'job_title',3);
                                                            
                                                            if(null==$nojobtitle_orang_thir){
                                                                break;
                                                            }else{
                                                                $nogradute_orang_key_thir=array_column($nojobtitle_orang_thir,'working_years','empNumber');
                                                                 //获取经验最多的女员工emnumber
                                                                $em_key_thir=$this->getMaxEmpNum($nogradute_orang_key_thir,$thirDocEmp);
                                                        
                                                                $tmp_fir[$i]=$thirDocEmp[$em_key_thir];
                                                                $tmp_fir[$i]['date_from']=$monarr[$i];
                                                                $tmp_fir[$i]['rotary_department']=$firDocEmp[$i]['orange_department'];
                                                                $rotary_department_three=$thirDocEmp[$em_key_thir]['orange_department'];

                                                                unset($thirDocEmp[$em_key_thir]);
                                                            }

                                                        }else if($compare_man_thir<0 && $compare_gradaute_thir<0 && $compare_job_thir>-1){//只有女非研究生参与轮转
                                                            //获取部门中经验最多的女员工,
                                                            $women_orang_thir=$this->getRotaryManWomen($thirDocEmp,'gender',2);
                                                            //再获取其中不适研究生的
                                                            $nojobtitle_orang_thir=$this->getRotaryNoGraduate($women_orang_thir,'graduate',3);
                                                            
                                                            if(null==$nojobtitle_orang_thir){
                                                                break;
                                                            }else{
                                                                $nogradute_orang_key_thir=array_column($nojobtitle_orang_thir,'working_years','empNumber');
                                                                 //获取经验最多的女员工emnumber
                                                                $em_key_thir=$this->getMaxEmpNum($nogradute_orang_key_thir,$thirDocEmp);
                                                        
                                                                $tmp_fir[$i]=$thirDocEmp[$em_key_thir];
                                                                $tmp_fir[$i]['date_from']=$monarr[$i];
                                                                $tmp_fir[$i]['rotary_department']=$firDocEmp[$i]['orange_department'];
                                                                $rotary_department_three=$thirDocEmp[$em_key_thir]['orange_department'];

                                                                unset($thirDocEmp[$em_key_thir]);
                                                            }

                                                        }else if($compare_man_thir<0 && $compare_gradaute_thir>-1 && $compare_job_thir<0){//只女非中层员工都可以轮转
                                                            //获取部门中经验最多的女员工,
                                                            $women_orang_thir=$this->getRotaryManWomen($thirDocEmp,'gender',2);
                                                            $nojobtitle_orang_thir=$this->getRotaryNoGraduate($women_orang_thir,'job_title',3);
                                                            if(null==$nojobtitle_orang_thir){
                                                                break;
                                                            }else{
                                                                $women_orang_key_thir=array_column($nojobtitle_orang_thir,'working_years','empNumber');
                                                                 //获取经验最多的女员工emnumber
                                                                $em_key_thir=$this->getMaxEmpNum($women_orang_key_thir,$thirDocEmp);
                                                        
                                                                $tmp_fir[$i]=$thirDocEmp[$em_key_thir];
                                                                $tmp_fir[$i]['date_from']=$monarr[$i];
                                                                $tmp_fir[$i]['rotary_department']=$firDocEmp[$i]['orange_department'];
                                                        
                                                                $rotary_department_three=$thirDocEmp[$em_key_thir]['orange_department'];

                                                                unset($thirDocEmp[$em_key_thir]);
                                                            }

                                                        }else if($compare_man_thir>-1 && $compare_gradaute_thir>-1 && $compare_job_thir<0){//只有非中层参与轮转
                                                            //获取部门中经验最多的女员工,
                                                           $nojobtitle_orang_thir=$this->getRotaryNoGraduate($thirDocEmp,'job_title',3);
                                                            if(null==$nojobtitle_orang_thir){
                                                                break;
                                                            }else{
                                                                $women_orang_key_thir=array_column($nojobtitle_orang_thir,'working_years','empNumber');
                                                                 //获取经验最多的女员工emnumber
                                                                $em_key_thir=$this->getMaxEmpNum($women_orang_key_thir,$thirDocEmp);
                                                        
                                                                $tmp_fir[$i]=$thirDocEmp[$em_key_thir];
                                                                $tmp_fir[$i]['date_from']=$monarr[$i];
                                                                $tmp_fir[$i]['rotary_department']=$firDocEmp[$i]['orange_department'];
                                                        
                                                                $rotary_department_three=$thirDocEmp[$em_key_thir]['orange_department'];

                                                                unset($thirDocEmp[$em_key_thir]);
                                                            }

                                                        }else if($compare_man_thir<0 && $compare_gradaute_thir>-1 && $compare_job_thir>-1){//只有女参与轮转
                                                            //获取部门中经验最多的女员工,
                                                            $women_orang_thir=$this->getRotaryManWomen($thirDocEmp,'gender',2);
                                                            
                                                            if(null==$women_orang_thir){
                                                                break;
                                                            }else{
                                                                $women_orang_key_thir=array_column($women_orang_thir,'working_years','empNumber');
                                                                 //获取经验最多的女员工emnumber
                                                                $em_key_thir=$this->getMaxEmpNum($women_orang_key_thir,$thirDocEmp);
                                                        
                                                                $tmp_fir[$i]=$thirDocEmp[$em_key_thir];
                                                                $tmp_fir[$i]['date_from']=$monarr[$i];
                                                                $tmp_fir[$i]['rotary_department']=$firDocEmp[$i]['orange_department'];
                                                        
                                                                $rotary_department_three=$thirDocEmp[$em_key_thir]['orange_department'];

                                                                unset($thirDocEmp[$em_key_thir]);
                                                            }

                                                        }else{//如果该元满足轮转条件，直接参与轮转
                                                            $tmp_fir[$i]=$thirDocEmp[$i];
                                                            $tmp_fir[$i]['date_from']=$monarr[$i];
                                                            $tmp_fir[$i]['rotary_department']=$firDocEmp[$i]['orange_department'];
                                                            $rotary_department_three=$thirDocEmp[$i]['orange_department'];
                                                            unset($thirDocEmp[$i]);
                                                        }

                                                    }else if($thirDocEmp[$i]['gender']!=1 && $thirDocEmp[$i]['graduate']==3 && $thirDocEmp[$i]['job_title']==3){//非男,研究生,中层

                                                        if($compare_man_thir<0 && $compare_gradaute_thir<0 && $compare_job_thir<0){//只能选择经验最多的非研究生，并且是女员工

                                                     
                                                            $women_orang_thir=$this->getRotaryManWomen($thirDocEmp,'gender',2);
                                                         
                                                            //经验最的的女性中的非研究生列表
                                                            $women_nogradute_orang_thir=$this->getRotaryNoGraduate($women_orang_thir,'graduate',3);
                                                            //获取非中中层
                                                            $women_nogradute_nolevel_orang_thir=$this->getRotaryNoGraduate($women_nogradute_orang_thir,'job_title',3);

                                                            if(null==$women_nogradute_nolevel_orang_thir){
                                                                break;
                                                            }else{
                                                                $women_orang_key_thir=array_column($women_nogradute_nolevel_orang_thir,'working_years','empNumber');
                                                           
                                                                $em_key_thir=$this->getMaxEmpNum($women_orang_key_thir,$thirDocEmp);
                                                        
                                                                $tmp_fir[$i]=$thirDocEmp[$em_key_thir];
                                                                $tmp_fir[$i]['date_from']=$monarr[$i];
                                                                $tmp_fir[$i]['rotary_department']=$firDocEmp[$i]['orange_department'];
                                                                $rotary_department_three=$thirDocEmp[$em_key_thir]['orange_department'];

                                                                unset($thirDocEmp[$em_key_thir]);
                                                            }              
                                                            
                                                        }else if($compare_man_thir>-1 && $compare_gradaute_thir<0 && $compare_job_thir<0){//只要不是研究生，且不是中层，都可参与轮转

                                                            //获取非研究生的的列表
                                                            $nogradute_orang_thir=$this->getRotaryNoGraduate($thirDocEmp,'graduate',3);
                                                            $nojobtitle_orang_thir=$this->getRotaryNoGraduate($nogradute_orang_thir,'job_title',3);
                                                            
                                                            if(null==$nojobtitle_orang_thir){
                                                                break;
                                                            }else{
                                                                $nogradute_orang_key_thir=array_column($nojobtitle_orang_thir,'working_years','empNumber');
                                                                 //获取经验最多的女员工emnumber
                                                                $em_key_thir=$this->getMaxEmpNum($nogradute_orang_key_thir,$thirDocEmp);
                                                        
                                                                $tmp_fir[$i]=$thirDocEmp[$em_key_thir];
                                                                $tmp_fir[$i]['date_from']=$monarr[$i];
                                                                $tmp_fir[$i]['rotary_department']=$firDocEmp[$i]['orange_department'];
                                                                $rotary_department_three=$thirDocEmp[$em_key_thir]['orange_department'];

                                                                unset($thirDocEmp[$em_key_thir]);
                                                            }

                                                        }else if($compare_man_thir<0 && $compare_gradaute_thir>-1 && $compare_job_thir<0){//只女非中层员工都可以轮转
                                                            //获取部门中经验最多的女员工,
                                                            $women_orang_thir=$this->getRotaryManWomen($thirDocEmp,'gender',2);
                                                            $nojobtitle_orang_thir=$this->getRotaryNoGraduate($women_orang_thir,'job_title',3);
                                                            if(null==$nojobtitle_orang_thir){
                                                                break;
                                                            }else{
                                                                $women_orang_key_thir=array_column($nojobtitle_orang_thir,'working_years','empNumber');
                                                                 //获取经验最多的女员工emnumber
                                                                $em_key_thir=$this->getMaxEmpNum($women_orang_key_thir,$thirDocEmp);
                                                        
                                                                $tmp_fir[$i]=$thirDocEmp[$em_key_thir];
                                                                $tmp_fir[$i]['date_from']=$monarr[$i];
                                                                $tmp_fir[$i]['rotary_department']=$firDocEmp[$i]['orange_department'];
                                                        
                                                                $rotary_department_three=$thirDocEmp[$em_key_thir]['orange_department'];

                                                                unset($thirDocEmp[$em_key_thir]);
                                                            }

                                                        }else if($compare_man_thir<0 && $compare_gradaute_thir<0 && $compare_job_thir>-1){//只有女非研究生参与轮转
                                                            //获取部门中经验最多的女员工,
                                                            $women_orang_thir=$this->getRotaryManWomen($thirDocEmp,'gender',2);
                                                            //再获取其中不适研究生的
                                                            $nojobtitle_orang_thir=$this->getRotaryNoGraduate($women_orang_thir,'graduate',3);
                                                            
                                                            if(null==$nojobtitle_orang_thir){
                                                                break;
                                                            }else{
                                                                $nogradute_orang_key_thir=array_column($nojobtitle_orang_thir,'working_years','empNumber');
                                                                 //获取经验最多的女员工emnumber
                                                                $em_key_thir=$this->getMaxEmpNum($nogradute_orang_key_thir,$thirDocEmp);
                                                        
                                                                $tmp_fir[$i]=$thirDocEmp[$em_key_thir];
                                                                $tmp_fir[$i]['date_from']=$monarr[$i];
                                                                $tmp_fir[$i]['rotary_department']=$firDocEmp[$i]['orange_department'];
                                                                $rotary_department_three=$thirDocEmp[$em_key_thir]['orange_department'];

                                                                unset($thirDocEmp[$em_key_thir]);
                                                            }

                                                        }else if($compare_man_thir>-1 && $compare_gradaute_thir>-1 && $compare_job_thir<0){//只有非中层参与轮转
                                                            //获取部门中经验最多的女员工,
                                                           $nojobtitle_orang_thir=$this->getRotaryNoGraduate($thirDocEmp,'job_title',3);
                                                            if(null==$nojobtitle_orang_thir){
                                                                break;
                                                            }else{
                                                                $women_orang_key_thir=array_column($nojobtitle_orang_thir,'working_years','empNumber');
                                                                 //获取经验最多的女员工emnumber
                                                                $em_key_thir=$this->getMaxEmpNum($women_orang_key_thir,$thirDocEmp);
                                                        
                                                                $tmp_fir[$i]=$thirDocEmp[$em_key_thir];
                                                                $tmp_fir[$i]['date_from']=$monarr[$i];
                                                                $tmp_fir[$i]['rotary_department']=$firDocEmp[$i]['orange_department'];
                                                        
                                                                $rotary_department_three=$thirDocEmp[$em_key_thir]['orange_department'];

                                                                unset($thirDocEmp[$em_key_thir]);
                                                            }

                                                        }else if($compare_man_thir>-1 && $compare_gradaute_thir<0 && $compare_job_thir>-1){//只要不是中层，都可参与轮转

                                                            //获取非中层的的列表
                                                          
                                                            $nojobtitle_orang_thir=$this->getRotaryNoGraduate($thirDocEmp,'job_title',3);
                                                            
                                                            if(null==$nojobtitle_orang_thir){
                                                                break;
                                                            }else{
                                                                $nogradute_orang_key_thir=array_column($nojobtitle_orang_thir,'working_years','empNumber');
                                                                 //获取经验最多的女员工emnumber
                                                                $em_key_thir=$this->getMaxEmpNum($nogradute_orang_key_thir,$thirDocEmp);
                                                        
                                                                $tmp_fir[$i]=$thirDocEmp[$em_key_thir];
                                                                $tmp_fir[$i]['date_from']=$monarr[$i];
                                                                $tmp_fir[$i]['rotary_department']=$firDocEmp[$i]['orange_department'];
                                                                $rotary_department_three=$thirDocEmp[$em_key_thir]['orange_department'];

                                                                unset($thirDocEmp[$em_key_thir]);
                                                            }

                                                        }else{//如果该元满足轮转条件，直接参与轮转
                                                            $tmp_fir[$i]=$thirDocEmp[$i];
                                                            $tmp_fir[$i]['date_from']=$monarr[$i];
                                                            $tmp_fir[$i]['rotary_department']=$firDocEmp[$i]['orange_department'];
                                                            $rotary_department_three=$thirDocEmp[$i]['orange_department'];
                                                            unset($thirDocEmp[$i]);
                                                        }

                                                    }else if($thirDocEmp[$i]['gender']!=1 && $thirDocEmp[$i]['graduate']==3 && $thirDocEmp[$i]['job_title']!==3){//非男,研究生,非中层

                                                        if($compare_man_thir<0 && $compare_gradaute_thir<0 && $compare_job_thir<0){//只能选择经验最多的非研究生，并且是女员工

                                                     
                                                            $women_orang_thir=$this->getRotaryManWomen($thirDocEmp,'gender',2);
                                                         
                                                            //经验最的的女性中的非研究生列表
                                                            $women_nogradute_orang_thir=$this->getRotaryNoGraduate($women_orang_thir,'graduate',3);
                                                            //获取非中中层
                                                            $women_nogradute_nolevel_orang_thir=$this->getRotaryNoGraduate($women_nogradute_orang_thir,'job_title',3);

                                                            if(null==$women_nogradute_nolevel_orang_thir){
                                                                break;
                                                            }else{
                                                                $women_orang_key_thir=array_column($women_nogradute_nolevel_orang_thir,'working_years','empNumber');
                                                           
                                                                $em_key_thir=$this->getMaxEmpNum($women_orang_key_thir,$thirDocEmp);
                                                        
                                                                $tmp_fir[$i]=$thirDocEmp[$em_key_thir];
                                                                $tmp_fir[$i]['date_from']=$monarr[$i];
                                                                $tmp_fir[$i]['rotary_department']=$firDocEmp[$i]['orange_department'];
                                                                $rotary_department_three=$thirDocEmp[$em_key_thir]['orange_department'];

                                                                unset($thirDocEmp[$em_key_thir]);
                                                            }              
                                                            
                                                        }else if($compare_man_thir<0 && $compare_gradaute_thir<0 && $compare_job_thir>-1){//只女非中层员工都可以轮转
                                                            //获取部门中经验最多的女员工,
                                                            $women_orang_thir=$this->getRotaryManWomen($thirDocEmp,'gender',2);
                                                            $nojobtitle_orang_thir=$this->getRotaryNoGraduate($women_orang_thir,'job_title',3);
                                                            if(null==$nojobtitle_orang_thir){
                                                                break;
                                                            }else{
                                                                $women_orang_key_thir=array_column($nojobtitle_orang_thir,'working_years','empNumber');
                                                                 //获取经验最多的女员工emnumber
                                                                $em_key_thir=$this->getMaxEmpNum($women_orang_key_thir,$thirDocEmp);
                                                        
                                                                $tmp_fir[$i]=$thirDocEmp[$em_key_thir];
                                                                $tmp_fir[$i]['date_from']=$monarr[$i];
                                                                $tmp_fir[$i]['rotary_department']=$firDocEmp[$i]['orange_department'];
                                                        
                                                                $rotary_department_three=$thirDocEmp[$em_key_thir]['orange_department'];

                                                                unset($thirDocEmp[$em_key_thir]);
                                                            }

                                                        }else if($compare_man_thir>-1 && $compare_gradaute_thir<0 && $compare_job_thir>-1){//只有女参与轮转
                                                            //获取部门中经验最多的女员工,
                                                            $women_orang_thir=$this->getRotaryManWomen($thirDocEmp,'gender',2);
                                                            
                                                            if(null==$women_orang_thir){
                                                                break;
                                                            }else{
                                                                $women_orang_key_thir=array_column($women_orang_thir,'working_years','empNumber');
                                                                 //获取经验最多的女员工emnumber
                                                                $em_key_thir=$this->getMaxEmpNum($women_orang_key_thir,$thirDocEmp);
                                                        
                                                                $tmp_fir[$i]=$thirDocEmp[$em_key_thir];
                                                                $tmp_fir[$i]['date_from']=$monarr[$i];
                                                                $tmp_fir[$i]['rotary_department']=$firDocEmp[$i]['orange_department'];
                                                        
                                                                $rotary_department_three=$thirDocEmp[$em_key_thir]['orange_department'];

                                                                unset($thirDocEmp[$em_key_thir]);
                                                            }

                                                        }else if($compare_man_thir>-1 && $compare_gradaute_thir<0 && $compare_job_thir<0){//只要不是研究生，且不是中层，都可参与轮转

                                                            //获取非研究生的的列表
                                                            $nogradute_orang_thir=$this->getRotaryNoGraduate($thirDocEmp,'graduate',3);
                                                            $nojobtitle_orang_thir=$this->getRotaryNoGraduate($nogradute_orang_thir,'job_title',3);
                                                            
                                                            if(null==$nojobtitle_orang_thir){
                                                                break;
                                                            }else{
                                                                $nogradute_orang_key_thir=array_column($nojobtitle_orang_thir,'working_years','empNumber');
                                                                 //获取经验最多的女员工emnumber
                                                                $em_key_thir=$this->getMaxEmpNum($nogradute_orang_key_thir,$thirDocEmp);
                                                        
                                                                $tmp_fir[$i]=$thirDocEmp[$em_key_thir];
                                                                $tmp_fir[$i]['date_from']=$monarr[$i];
                                                                $tmp_fir[$i]['rotary_department']=$firDocEmp[$i]['orange_department'];
                                                                $rotary_department_three=$thirDocEmp[$em_key_thir]['orange_department'];

                                                                unset($thirDocEmp[$em_key_thir]);
                                                            }

                                                        }else{//如果该元满足轮转条件，直接参与轮转
                                                            $tmp_fir[$i]=$thirDocEmp[$i];
                                                            $tmp_fir[$i]['date_from']=$monarr[$i];
                                                            $tmp_fir[$i]['rotary_department']=$firDocEmp[$i]['orange_department'];
                                                            $rotary_department_three=$thirDocEmp[$i]['orange_department'];
                                                            unset($thirDocEmp[$i]);
                                                        }

                                                    }else if($thirDocEmp[$i]['gender']!=1 && $thirDocEmp[$i]['graduate']!=3 && $thirDocEmp[$i]['job_title']==3){//非男，非研生,中层

                                                        if($compare_man_thir<0 && $compare_gradaute_thir<0 && $compare_job_thir<0){//只能选择经验最多的非研究生，并且是女员工,并且是中级员工

                                                            //获取部门中经验最多的女员工,
                                                            $women_orang_thir=$this->getRotaryManWomen($thirDocEmp,'gender',2);
                                                         
                                                            //经验最的的女性中的非研究生列表
                                                            $women_nogradute_orang_thir=$this->getRotaryNoGraduate($women_orang_thir,'graduate',3);
                                                            //获取非中中层
                                                            $women_nogradute_nolevel_orang_thir=$this->getRotaryNoGraduate($women_nogradute_orang_thir,'job_title',3);



                                                            if(null==$women_nogradute_nolevel_orang_thir){
                                                                break;
                                                            }else{
                                                                $women_orang_key_thir=array_column($women_nogradute_nolevel_orang_thir,'working_years','empNumber');
                                                           
                                                                $em_key_thir=$this->getMaxEmpNum($women_orang_key_thir,$thirDocEmp);
                                                        
                                                                $tmp_fir[$i]=$thirDocEmp[$em_key_thir];
                                                                $tmp_fir[$i]['date_from']=$monarr[$i];
                                                                $tmp_fir[$i]['rotary_department']=$firDocEmp[$i]['orange_department'];
                                                                $rotary_department_three=$thirDocEmp[$em_key_thir]['orange_department'];

                                                                unset($thirDocEmp[$em_key_thir]);
                                                            }        
                                                            
                                                        }else if($compare_man_thir<0 && $compare_gradaute_thir>-1 && $compare_job_thir<0){//只女非中层员工都可以轮转
                                                            //获取部门中经验最多的女员工,
                                                            $women_orang_thir=$this->getRotaryManWomen($thirDocEmp,'gender',2);
                                                            $nojobtitle_orang_thir=$this->getRotaryNoGraduate($women_orang_thir,'job_title',3);
                                                            if(null==$nojobtitle_orang_thir){
                                                                break;
                                                            }else{
                                                                $women_orang_key_thir=array_column($nojobtitle_orang_thir,'working_years','empNumber');
                                                                 //获取经验最多的女员工emnumber
                                                                $em_key_thir=$this->getMaxEmpNum($women_orang_key_thir,$thirDocEmp);
                                                        
                                                                $tmp_fir[$i]=$thirDocEmp[$em_key_thir];
                                                                $tmp_fir[$i]['date_from']=$monarr[$i];
                                                                $tmp_fir[$i]['rotary_department']=$firDocEmp[$i]['orange_department'];
                                                        
                                                                $rotary_department_three=$thirDocEmp[$em_key_thir]['orange_department'];

                                                                unset($thirDocEmp[$em_key_thir]);
                                                            }

                                                        }else if($compare_man_thir>-1 && $compare_gradaute_thir<0 && $compare_job_thir<0){//只要不是研究生，且不是中层，都可参与轮转

                                                            //获取非研究生的的列表
                                                            $nogradute_orang_thir=$this->getRotaryNoGraduate($thirDocEmp,'graduate',3);
                                                            $nojobtitle_orang_thir=$this->getRotaryNoGraduate($nogradute_orang_thir,'job_title',3);
                                                            
                                                            if(null==$nojobtitle_orang_thir){
                                                                break;
                                                            }else{
                                                                $nogradute_orang_key_thir=array_column($nojobtitle_orang_thir,'working_years','empNumber');
                                                                 //获取经验最多的女员工emnumber
                                                                $em_key_thir=$this->getMaxEmpNum($nogradute_orang_key_thir,$thirDocEmp);
                                                        
                                                                $tmp_fir[$i]=$thirDocEmp[$em_key_thir];
                                                                $tmp_fir[$i]['date_from']=$monarr[$i];
                                                                $tmp_fir[$i]['rotary_department']=$firDocEmp[$i]['orange_department'];
                                                                $rotary_department_three=$thirDocEmp[$em_key_thir]['orange_department'];

                                                                unset($thirDocEmp[$em_key_thir]);
                                                            }

                                                        }else if($compare_man_thir>-1 && $compare_gradaute_thir>-1 && $compare_job_thir<0){//只有非中层参与轮转
                                                            //获取部门中经验最多的女员工,
                                                           $nojobtitle_orang_thir=$this->getRotaryNoGraduate($thirDocEmp,'job_title',3);
                                                            if(null==$nojobtitle_orang_thir){
                                                                break;
                                                            }else{
                                                                $women_orang_key_thir=array_column($nojobtitle_orang_thir,'working_years','empNumber');
                                                                 //获取经验最多的女员工emnumber
                                                                $em_key_thir=$this->getMaxEmpNum($women_orang_key_thir,$thirDocEmp);
                                                        
                                                                $tmp_fir[$i]=$thirDocEmp[$em_key_thir];
                                                                $tmp_fir[$i]['date_from']=$monarr[$i];
                                                                $tmp_fir[$i]['rotary_department']=$firDocEmp[$i]['orange_department'];
                                                        
                                                                $rotary_department_three=$thirDocEmp[$em_key_thir]['orange_department'];

                                                                unset($thirDocEmp[$em_key_thir]);
                                                            }

                                                        }else{//如果该元满足轮转条件，直接参与轮转
                                                            $tmp_fir[$i]=$thirDocEmp[$i];
                                                            $tmp_fir[$i]['date_from']=$monarr[$i];
                                                            $tmp_fir[$i]['rotary_department']=$firDocEmp[$i]['orange_department'];
                                                            $rotary_department_three=$thirDocEmp[$i]['orange_department'];
                                                            unset($thirDocEmp[$i]);
                                                        }

                                                    }else{
                                                        $tmp_fir[$i]=$thirDocEmp[$i];
                                                        $tmp_fir[$i]['date_from']=$monarr[$i];
                                                        $tmp_fir[$i]['rotary_department']=$firDocEmp[$i]['orange_department'];
                                                        $rotary_department_three=$thirDocEmp[$i]['orange_department'];
                                                        unset($thirDocEmp[$i]);
                                                    }

?>