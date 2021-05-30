<?php

if (!defined('_PS_VERSION_'))
  exit;

class Exportinginvoices extends Module {

  //construct method
  public function __construct() {
    $this->name = 'exportinginvoices';
    $this->tab = 'front_office_features';
    $this->version = '1.0.0';
    $this->author = 'Burxi';
    $this->need_instance = 0;
    $this->booststrap = true;
    $this->ps_versions_compliancy = array('min'=>'1.7','max'=>_PS_VERSION_);

    parent::__construct();

    $this->displayName = $this->l('Invoice Exporting');
    $this->description = $this->l('Free Prestashop module to download invoices from your store easily');
  }//constructor

  //module install function
  public function install(){
    if(!parent::install()){
      return false;
    }//if
    else if( !$this->registerHook('displayBackOfficeHeader')){
      return false;
    }//else if
    else if(!$this->installTab("AdminExportinginvoices","Invoice Exporting","AdminCatalog")){
      return false;
    }//else if
    else if(!$this->installTab("AdminExportinginvoicesbyid","Invoice Exporting By ID","AdminExportinginvoices")){
      return false;
    }//else if
    else if(!$this->installTab("AdminExportinginvoicesbydate","Invoice Exporting By Date","AdminExportinginvoices")){
      return false;
    }//else if
    else{
      return true;
    }//else
  }//install
  
  //module uninstall function
  public function uninstall(){
    $this->uninstallTab("AdminExportinginvoices");
    $this->uninstallTab("AdminExportinginvoicesbyid");
    $this->uninstallTab("AdminExportinginvoicesbydate");
    return parent::uninstall();
  }//uninstall

  //module getContent function, (config page)
  public function getContent(){
    Tools::redirectAdmin('?tab=AdminExportinginvoicesbyid' . '&token=' . Tools::getAdminTokenLite('AdminExportinginvoicesbyid'));
  }//getContent

  //install Tabs
  public function installTab($class_name,$name,$parent){
    $tab = new Tab();
    $tab->active = 1;
    $tab->id_parent = (int)Tab::getIdFromClassName($parent);
    $tab->name= array();
    foreach (Language::getLanguages(true) as $lang){
        $tab->name[$lang['id_lang']] = $name;
    }//foreach
    $tab->class_name = $class_name;
    $tab->module = $this->name;
    return $tab->add();
  }//installTab

  //uninistall tabs
  public function uninstallTab($class_name){
    $id_tab = (int)Tab::getIDFromClassName($class_name);
    $tab = new Tab((int)$id_tab);
    return $tab->delete();
  }//uninstallTab

}//ExportingInvoices