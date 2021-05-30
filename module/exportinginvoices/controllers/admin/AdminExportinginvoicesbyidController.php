<?php
 class AdminExportinginvoicesbyidController extends ModuleAdminController{

    public function __construct(){
      $this->bootstrap = true;   
      parent::__construct();
    }//construct

    public function initContent(){
      if(Tools::isSubmit("submitInvoiceExportingPDFbyID")){
        $this->invoiceExportingPDFbyID( Tools::getValue("id_type"),Tools::getValue("from"),Tools::getValue("to"));
      }//if
      $this->renderForm();
      parent::initContent();
    }//initContent

    public function renderForm(){
      $this->fields_form[0]['form'] = [
        "legend" => array(
          "title" => $this->l("PDF INVOICE EXPORTING")
        ),
        'input' => array(
          array(
            'type' => 'select',
            'label' => $this->l('Action:'),
            'desc' => $this->l('Choose exportation ID type (Order or Invoice)'),
            'name' => 'id_type',
            'required' => true,
            'options' => array(
            'query' => $idevents = 
              array(
                array(
                  'idevents' => 'order',
                  'name' =>  $this->l('By Order ID')
                ),
                array(
                  'idevents' => 'invoice',
                  'name' => $this->l('By Invoice ID')
                ),                                      
              ),
            'id' => 'idevents',
            'name' => 'name',
            )
          ),         
          array(
            'type' => 'text',
            'label' => $this->l('From: '),
            'name' => 'from',
            'size' => 20,
            'required' => true,
            'desc' => $this->l('Final ID (inclusive)')
          ),
          array(
            'type' => 'text',
            'label' => $this->l('To : '),
            'name' => 'to',
            'size' => 20,
            'required' => true,
            'desc' => $this->l('Initial ID (inclusive)')
          ),
        ),
        "submit" => array(
          "title" => $this->l("Excute"),
          "class" => "btn btn-default pull-right"
        )
      ];
      $helper = new HelperForm();
      $helper->submit_action = "submitInvoiceExportingPDFbyID";
      $helper->fields_value = $valor;
      $this->content .= $helper->generateForm($this->fields_form);
    }//displayForm
    
    //PDF Export handling
    function invoiceExportingPDFbyID($id_type,$from, $to){
      $sql = new DbQuery();
      $sql->select('oi.*');
      $sql->from('order_invoice', 'oi');
      $sql->leftJoin('orders', 'o', 'o.id_order = oi.id_order');
      if($id_type == 'order')
        $sql->where('oi.id_order BETWEEN '.(int)$from.' AND '.(int)$to);
      if($id_type == 'invoice')
        $sql->where('oi.number BETWEEN '.(int)$from.' AND '.(int)$to);
      $sql->orderBy('oi.date_add ASC');
  
      $order_invoice_list = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
    
      if ($order_invoice_list) {
        $order_invoices = ObjectModel::hydrateCollection('OrderInvoice', $order_invoice_list);
        $pdf = new PDF($order_invoices, PDF::TEMPLATE_INVOICE, Context::getContext()->smarty);
        return $pdf->render(true);
      }//if
    }//invoiceExportingPDFbyID

}//AdminExportinginvoicesbyidController
?>