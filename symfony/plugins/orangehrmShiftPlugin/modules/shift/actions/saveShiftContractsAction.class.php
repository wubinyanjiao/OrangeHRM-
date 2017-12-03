<?php

class saveShiftContractsAction extends baseShiftAction {
    /**
     * @param sfForm $form
     * @return
     */
    public function setLanguageForm(sfForm $form) {
        if (is_null($this->languageForm)) {
            $this->languageForm = $form;
        }
    }

       public function setContractForm(sfForm $form) {
        if (is_null($this->contractForm)) {
            $this->contractForm = $form;
        }
    }
    
    
    public function execute($request) {
        $form = new DefaultListForm();
        $form->bind($request->getParameter($form->getName()));
        $language = $request->getParameter('language');
       


        $this->setContractForm(new ContractsForm(array(), array('empNumber' => '75', 'languagePermissions' => $this->languagePermissions), true));

        if ($request->isMethod('post')) {
            if ( $request->getParameter('option') == "save") {

                $this->contractForm->bind($request->getParameter($this->contractForm->getName()));

                if ($this->contractForm->isValid()) {
                    $language = $this->getLanguage($this->contractForm);
                    if ($language != NULL) {
                        $this->getEmployeeService()->saveEmployeeLanguage($language);
                        $this->getUser()->setFlash('language.success', __(TopLevelMessages::SAVE_SUCCESS));
                    } 
                } else {
                    $this->getUser()->setFlash('language.warning', __('Form Validation Failed'));
                }
            }

            //this is to delete 
            if ($this->languagePermissions->canDelete()) {
                if ($request->getParameter('option') == "delete") {
                    $deleteIds = $request->getParameter('delLanguage');
                    $languagesToDelete = array();

                    foreach ($deleteIds as $value) {
                        $parts = explode("_", $value, 2);
                        if (count($parts) == 2) {
                            $languagesToDelete[] = array($parts[0] => $parts[1]); 
                        }
                    }

                    if (count($languagesToDelete) > 0) {
                        if ($form->isValid()) {
                            $this->getEmployeeService()->deleteEmployeeLanguages($empNumber, $languagesToDelete);
                            $this->getUser()->setFlash('language.success', __(TopLevelMessages::DELETE_SUCCESS));
                        }
                    }
                }
            }
        }
        $this->getUser()->setFlash('qualificationSection', 'language');
        $this->redirect('pim/viewQualifications?empNumber='. $empNumber . '#language');
    }

    private function getLanguage(sfForm $form) {

        $post = $form->getValues();

        $language = $this->getEmployeeService()->getEmployeeLanguages($post['emp_number'], $post['code'], $post['lang_type']);
        
        $isAllowed = FALSE;
        if (!$language instanceof EmployeeLanguage) {
            if($this->languagePermissions->canCreate()){
                $language = new EmployeeLanguage();
                $isAllowed = TRUE;
            }
        } else {
            if($this->languagePermissions->canUpdate()){
                $isAllowed = TRUE;
            } else {
                $this->getUser()->setFlash('warning', __("You don't have update permission"));
            }
        }
        if ($isAllowed) {
            $language->empNumber = $post['emp_number'];
            $language->langId = $post['code'];
            $language->fluency = $post['lang_type'];
            $language->competency = $post['competency'];
            $language->comments = $post['comments'];

            return $language;
        } else {
            return NULL;
        }
    }

}

?>