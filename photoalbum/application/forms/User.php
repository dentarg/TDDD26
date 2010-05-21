<?php

class Application_Form_User extends Zend_Form
{
    public function init()
    {
        $this->setName('user');

        $email = new Zend_Form_Element_Text('email');
        $email->setLabel('Username')
            ->setRequired(true)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->addValidator('NotEmpty');

        $nickname = new Zend_Form_Element_Text('nickname');
        $nickname->setLabel('Real name')
            ->setRequired(true)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->addValidator('NotEmpty');

        $password = new Zend_Form_Element_Password('password');
        $password->setLabel('Password')
            ->setRequired(true)
            ->addFilter('StripTags')
            ->addFilter('StringTrim')
            ->addValidator('NotEmpty');

         $password_confirmation = new Zend_Form_Element_Password('password_confirmation');
         $password_confirmation->setLabel('Password confirmation')
             ->setRequired(true)
             ->addFilter('StripTags')
             ->addFilter('StringTrim')
             ->addValidator('NotEmpty')
             ->addPrefixPath('App_Validate', 'App/Validate/', 'validate')
             ->addValidator('PasswordConfirmation');
             
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setAttrib('id', 'submitbutton');

        $this->addElements(array($email, $nickname, $password, 
           $password_confirmation, $submit));
    }    
}

?>