<?php 
  class AdminExportinginvoicesController extends ModuleAdminController{

    public function __construct(){
      $this->bootstrap = true;   
      parent::__construct();
    }//__construct

    public function initContent(){
      parent::initContent();
    }//initContent

}//AdminExportinginvoicesController
?>