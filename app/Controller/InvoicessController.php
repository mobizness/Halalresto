<?php
/* Manikandan MN */

App::import('Vendor', 'Mpdf', array('file' => 'mpdf' . DS . 'mpdf.php'));
App::uses('AppController','Controller');
App::uses('CakeEmail', 'Network/Email');

class InvoicessController extends AppController {

	public $helpers      = array('Html','Form', 'Session', 'Javascript');  
  	public $uses         = array('Invoice','Store','Order','State','City','Location');
  	public $components   = array('Functions','Mpdf');

  	public function beforeFilter() {
		$this->Auth->allow(array('index', 'invoiceMail'));
		parent::beforeFilter();
	}

    // Cron invoice calculation 
  	public function index() {

        $site_detail  = $this->siteSetting;
        $tax          = $site_detail['Sitesetting']['vat_percent'];
        $cardfess     = $site_detail['Sitesetting']['card_fee'];

        $this->Store->recursive = 0;
        $store_detail = $this->Store->find('all', array(
                                'conditions'=>array('Store.status' => 1),
                                'group'=>array('Store.id')));


        if(!empty($store_detail)) {

            foreach($store_detail as $key => $value) {

                $start_dat = date('Y-m-d', strtotime('first day of last month'));
                $end_date  = date('Y-m-d', strtotime('last day of last month'));
                $storeId   = $value['Store']['id'];

                $storeCommission = $value['Store']['commission'];

                if ($value['Store']['invoice_period'] != '30day') {

                    if(date('j') == 1) {

                        //Next Month 1 -> Day 16 To Day 30 OR 31

                        $last_date = date("Y-m-d", strtotime('-1 day', strtotime(date('Y-m-d'))) );

                        list($ll_yr, $ll_mon, $ll_date) = explode("-", $last_date);
                        $start_dat              = $ll_yr.'-'.$ll_mon.'-16';
                        $end_date              = $last_date;

                        $invoice_monthly_2      = $ll_yr.'-'.$ll_mon;
                        $inv_month_period       = 16;
                        $inv_month_period_limit = '16-'.$ll_date;
                        $inv_month              = $ll_yr.'-'.$ll_mon;
                    } else {
                        //Day 16 -> Day 01 to Day 15
                        $start_dat              = date('Y').'-'.date('m').'-01';
                        $end_date              = date('Y').'-'.date('m').'-15';

                        $invoice_monthly_2      = date('Y').'-'.date('m');
                        $inv_month_period       = 1;
                        $inv_month_period_limit = '01-15';
                        $inv_month              = date('Y').'-'.date('m');
                    }
                }
                
                $this->Order->recursive = 0;
                $order_detail   = $this->Order->find('all',array(
                                            'conditions'=>array('Order.store_id' => $storeId,
                                                    'Order.delivery_date between ? and ?' =>
                                                            array($start_dat, $end_date),
                                                    'Order.status'=>'Delivered'),
                                            'order' => array('Order.payment_type' => 'Asc')));

                if(!empty($order_detail)) {
                    
                    $checking   = $this->Invoice->find('first',array(
                                            'conditions'=>array(
                                                                'Invoice.store_id'   => $storeId,
                                                                'Invoice.start_date' => $start_dat,
                                                                'Invoice.end_date'   => $end_date)));
                    if (empty($checking)) {

                        $results = $this->Functions->sumOfDetail($order_detail,$tax,$cardfess);

                        $grossPay  = $results['card_tax'] + $results['card_price'] + 
                                     $results['wallet_tax'] + $results['wallet_price'] +
                                     $results['deliveryCharge'] + $results['cardTips'];


                        $sub_total                  = $results['subGrandTotal'];
                        $grand_total                = $sub_total - ($results['card_tax'] + $results['wallet_tax']);
                        $commision                  = $sub_total*($storeCommission/100);
                        $commisionTotal             = $commision + $results['card_cardfee'] + 
                                                        $results['wallet_cardfee'];

                        $invoices['tax']            = $tax;
                        $invoices['store_id']       = $storeId;
                        $invoices['subtotal']       = $sub_total;
                        $invoices['end_date']       = $end_date;
                        $invoices['start_date']     = $start_dat;
                        $invoices['grand_total']    = $grand_total;
                        $invoices['total_order']    = $results['totalOrder'];


                        // Wallet
                        $invoices['wallet_tax']     = $results['wallet_tax'];
                        $invoices['wallet_count']   = $results['wallet_count'];
                        $invoices['wallet_price']   = $results['wallet_price'];
                        $invoices['wallet_cardfee'] = $results['wallet_cardfee'];

                        // Card
                        $invoices['card_tax']       = $results['card_tax'];
                        $invoices['card_count']     = $results['card_count'];
                        $invoices['card_price']     = $results['card_price'];
                        $invoices['card_cardfee']   = $results['card_cardfee'];

                        $invoices['cardfee_total']  = $results['wallet_cardfee'] + $results['card_cardfee'];

                        
                        // Cod
                        $invoices['cod_count']      = $results['cod_count'];
                        $invoices['cod_price']      = $results['cod_price'];
                        $invoices['commision']      = $commision;
                        $invoices['commision_tax']  = $commision_tax = $commisionTotal * ($tax/100);
                        $invoices['commisionGrand'] = $commision_tax + $commisionTotal;
                        $invoices['commissionTotal']    = $commisionTotal;
                        $invoices['store_commission']   = $storeCommission;
                        $invoices['gross_sale_amount']  = $grossPay;
                        $invoices['store_owned_total']  = $grossPay - ($invoices['commisionGrand']);

                        $invoices['id'] = '';
                        
                        $this->Invoice->save($invoices);

                        $invoices['id']     = $this->Invoice->id;
                        $invoices['ref_id'] = '#GNC000'. $this->Invoice->id. 'INV';

                        $this->Invoice->save($invoices);
                        //Invoice Mail
                        //$this->invoiceMail($this->Invoice->id);
                    }
                }



            }
        }
        exit();
  	}

    // Cron invoice calculation mail
  	public function invoiceMail($invoiceId = null) {

        $invoice_detail = $this->Invoice->find('first',array('conditions'=>array('Invoice.id'=>$invoiceId)));
        $startDate      = $invoice_detail['Invoice']['start_date'];
        $endDate        = $invoice_detail['Invoice']['end_date'];

        $this->Order->recursive = 0;
        $order_detail = $this->Order->find('all',array(
                                'conditions'=>array('Order.store_id'=>$invoice_detail['Invoice']['store_id'],
                                                    'Order.status'=>'Delivered',
                                                    'Order.delivery_date between ? and ?' =>array($startDate, $endDate)),
                                'order' => array('Order.payment_type' => 'Asc')));


        $state_list  = $this->State->findById($invoice_detail['Store']['store_state']);
        $city_list   = $this->City->findById($invoice_detail['Store']['store_city']);
        $area_list   = $this->Location->findById($invoice_detail['Store']['store_zip']);

        $storeAddress = $invoice_detail['Store']['contact_name'].'<br>'.
                        $invoice_detail['Store']['store_name'].'<br>';

        if ($this->siteSetting['Sitesetting']['address_mode'] != 'Google') {

            $storeAddress .= $area_list['Location']['area_name'].','.
                            $area_list['City']['city_name'].'-'.
                            $area_list['Location']['zip_code'].',<br>'.
                            $area_list['State']['state_name'].', '.
                            $state_list['Country']['country_name'];

        } else {
            $storeAddress .= $invoice_detail['Store']['address'];
        }


        //Invoice PDF template file
        $output = '
            <div style="width:960px;margin:0 auto;">
                <h1 align="center">'.__('Invoice').' ['.$invoice_detail['Invoice']['ref_id'].']</h1>
                <table width="100%"  align="center">
                    <tr style="display:block; width:100%;">
                        <td style="display:inline-block;font:16px/20px Verdana; padding:10px 0px 5px; text-align:left;">
                           Created: '.$invoice_detail['Invoice']['created'].'
                        </td>
                        <td style="display:inline-block;font:16px/20px Verdana; padding:10px 0px 5px; text-align:right;">
                           Period: '.$invoice_detail['Invoice']['start_date'].' to '.
                                     $invoice_detail['Invoice']['end_date'].'
                        </td>
                    </tr>
                </table>
                <hr>
                <table width="100%"  align="center">
                     <tr style="display:block; width:100%;">
                         <td style="display:inline-block;font:14px/20px Verdana; padding:10px 0px 5px; text-align:left;">
                           <h3 style="font:bold 14px/20px Verdana; padding-bottom:15px;">Client:</h3>
                        </td>
                        <td style="display:inline-block;font:16px/20px Verdana; padding:10px 0px 5px; text-align:left;">
                            <h3 style="font:bold 14px/20px Verdana; padding-bottom:15px;">About:</h3>
                        </td>
                        <td style="display:inline-block; padding:10px 0px 5px; text-align:left;">
                            <h3 style="font:bold 14px/20px Verdana; padding-bottom:15px;">Payment Details:</h3>
                        </td>
                     </tr>
                     <tr style="display:block; width:100%;">
                         <td style="display:inline-block;font:14px/20px Verdana; padding:10px 0px 5px; text-align:left;">
                               <span style="font:12px Verdana;">'.$storeAddress.'</span>
                        </td>
                        <td style="display:inline-block;font:16px/20px Verdana; padding:10px 0px 5px; text-align:left; vertical-align:top;">
                             <span style="font:12px Verdana;">'. $this->siteSetting['Sitesetting']['site_name'].'</span>
                        </td>
                        <td style="display:inline-block;font:16px/20px Verdana; padding:10px 0px 5px; text-align:left; vertical-align:top;">
                           <span style="font:12px Verdana;"> <strong>V.A.T Reg #:</strong>'. $this->siteSetting['Sitesetting']['vat_no'].'</span>
                        </td>
                     </tr>
                </table>
            </div>';
        $output .= '
                <div style="display:block; width:100%; vertical-align:top; margin-top:15px;margin-bottom:10px;">
                    <div style="clear:both;"></div>
                    <table width="100%" align="center"  style="font:13px Arial;">
                        <tr style="font-size:18px;">
                            <td style="border-bottom:1px solid #ddd9c3;padding-bottom:5px; padding-left:0px; padding-right:5px; padding-top:5px; text-align:left; font-weight:bold;" colspan="2">Invoice breakdown</td>
                            <td style="border-bottom:1px solid #ddd9c3;padding-bottom:5px; padding-left:5px; padding-right:5px; padding-top:5px; text-align:right; font-weight:bold;">Amount</td>
                        </tr>
                        <tr>
                            <td>Customers paid cash for</td>
                            <td style="text-align:right;">'.$invoice_detail['Invoice']['cod_count'].' orders</td>
                            <td style="padding-bottom:5px; padding-left:5px; padding-right:5px; padding-top:5px; text-align:right;">'.$this->siteCurrency . ' ' . $invoice_detail['Invoice']['cod_price'].'</td>
                        </tr>
                        <tr>
                            <td>Customers prepaid online with card for </td>
                            <td style="text-align:right;">'.$invoice_detail['Invoice']['card_count'].' orders</td>
                            <td style="padding-bottom:5px; padding-left:5px; padding-right:5px; padding-top:5px; text-align:right;">'.$this->siteCurrency . ' ' . $invoice_detail['Invoice']['card_price'].'  </td>
                        </tr>
                        <tr>
                            <td>Customers prepaid online with wallet for </td>
                            <td style="text-align:right;">'.$invoice_detail['Invoice']['wallet_count'].' orders</td>
                            <td style="padding-bottom:5px; padding-left:5px; padding-right:5px; padding-top:5px; text-align:right;">'.$this->siteCurrency . ' ' . $invoice_detail['Invoice']['wallet_price'].'</td>
                        </tr>
                        <tr>
                            <td>Total value for</td>
                            <td style="text-align:right;">'.$invoice_detail['Invoice']['total_order'].' orders</td>
                            <td style="padding-bottom:5px; padding-left:5px; padding-right:5px; padding-top:5px; text-align:right;">'.$this->siteCurrency . ' ' . $invoice_detail['Invoice']['subtotal'].'</td>
                        </tr>
                    </table>
                    <table width="100%" align="center" style="font:13px Arial;">
                        <tr>
                            <td style="padding-bottom:5px; padding-left:5px; padding-right:0px; padding-top:5px; text-align:right; border-top:1px solid #ddd9c3;font:13px Arial;">
                                Paid Gross Sale (Including Tax, Delivery charge, Tips) :</td>
                            <td style="padding-bottom:5px; padding-left:0px; padding-right:5px; padding-top:5px; text-align:right; border-top:1px solid #ddd9c3;font:13px Arial;">'.$this->siteCurrency . ' ' . $invoice_detail['Invoice']['gross_sale_amount'].'</td>
                        </tr>
                        <tr>
                            <td style="padding-bottom:5px; padding-left:5px; padding-right:0px; padding-top:5px; text-align:right; border-top:1px solid #ddd9c3;font:13px Arial;">
                                Commission('.$invoice_detail['Invoice']['tax'].'%) :</td>
                            <td style="padding-bottom:5px; padding-left:0px; padding-right:5px; padding-top:5px; text-align:right; border-top:1px solid #ddd9c3;font:13px Arial;">'.$this->siteCurrency . ' ' . $invoice_detail['Invoice']['commision'].'</td>
                        </tr>
                        <tr>
                            <td style="padding-bottom:5px; padding-left:5px; padding-right:0px; padding-top:5px; text-align:right;font:13px Arial;" > Total Card Fee :</td>
                            <td style="padding-bottom:5px; padding-left:0px; padding-right:5px; padding-top:5px; text-align:right;font:13px Arial; ">'.$this->siteCurrency . ' ' . $invoice_detail['Invoice']['cardfee_total'].' </td>
                        </tr>
                        <tr>
                            <td style="padding-bottom:5px; padding-left:5px; padding-right:0px; padding-top:5px; text-align:right;font:13px Arial;" >Total Commission with Card fee :</td>
                            <td style="padding-bottom:5px; padding-left:0px; padding-right:5px; padding-top:5px; text-align:right; border-bottom:1px solid #ddd9c3;font:13px Arial;">'.$this->siteCurrency . ' ' . $invoice_detail['Invoice']['commissionTotal'].' </td>
                        </tr>
                        <tr>
                            <td style="padding-bottom:5px; padding-left:5px; padding-right:0px; padding-top:5px; text-align:right; border-top:1px solid #ddd9c3;font:13px Arial;">
                                VAT ('.$invoice_detail['Invoice']['tax'].'%) :</td>
                            <td style="padding-bottom:5px; padding-left:0px; padding-right:5px; padding-top:5px; text-align:right;font:13px Arial;">'.$this->siteCurrency . ' ' . $invoice_detail['Invoice']['commision_tax'].' </td>
                        </tr>
                        <tr>
                            <td style="padding-bottom:5px; padding-left:5px; padding-right:0px; padding-top:5px; text-align:right;font:13px Arial;">Total Commission from Restaurant :</td>
                            <td style="padding-bottom:5px; padding-left:0px; padding-right:5px; padding-top:5px; text-align:right;font:13px Arial;">'.$this->siteCurrency . ' ' . $invoice_detail['Invoice']['commisionGrand'].'</td>
                        </tr>
                        <tr>
                            <td style="padding-bottom:5px; padding-left:5px; padding-right:0px; padding-top:5px; text-align:right; border-top:1px solid #ddd9c3;font:13px Arial;">
                                Restaurant Owned ('.$this->siteCurrency . ' ' . $invoice_detail['Invoice']['gross_sale_amount'].' - '.$this->siteCurrency . ' ' . $invoice_detail['Invoice']['commisionGrand'].') : </td>
                            <td  style="padding-bottom:5px; padding-left:0px; padding-right:5px; padding-top:5px; text-align:right; border-top:1px solid #ddd9c3;font:13px Arial;">';
                                if ($invoice_detail['Invoice']['store_owned_total'] < 0)
                                    $output .= ' - ';
                                $output .= $this->siteCurrency . ' ' . $invoice_detail['Invoice']['store_owned_total'].' </td>
                        </tr>
                        <tr height="1"><td>&nbsp;</td><td>&nbsp;</td></tr>
                        <tr>
                            <td style="padding-bottom:5px; padding-left:5px; padding-right:0px; padding-top:5px; text-align:right; border-top:1px solid #ddd9c3; font-weight:bold;font:13px Arial;"></td>
                            <td style="padding-bottom:5px; padding-left:0px; padding-right:5px; padding-top:5px; text-align:right; border-top:1px solid #ddd9c3; font-weight:bold;font:13px Arial;"> </td>
                        </tr>
                    </table>
                </div>';


        $main  = '';
        $count = 1;

        $output .= '<h1 style="display:inline-block; font:24px Verdana;">Order Information</h1>';

        foreach($order_detail as $key => $value) {
            $nextValue = $key+1;
            if ($value['Order']['payment_type'] != $main) {
                $main = $value['Order']['payment_type'];

                $paymentType =  ($value['Order']['payment_type'] == 'cod') ? 'Cash' : $value['Order']['payment_type'].' Paid';

                $output .='<h1>'.$paymentType.'</h1>
                    <table width="100%" style="margin-top:15px;"  align="center" border="1" cellspacing="1" sellpadding="1">
                        <thead>
                            <tr>
                                <th style="padding:10px 0 10px 15px;">S.no</th>
                                <th style="padding:10px 0 10px 15px;">Order Id</th>
                                
                                <th style="padding:10px 0 10px 15px;">Subtotal</th>
                                <th style="padding:10px 0 10px 15px;">Commision</th>
                            </tr>
                        </thead>
                        <tbody>';

            }
                $commision = $value['Order']['order_sub_total'] * ($this->siteSetting['Sitesetting']['card_fee']/100);
                $output .= '<tr class="odd gradeX">
                                <td style="padding:10px 0 10px 15px;">'.$count.'</td>
                                <td style="padding:10px 0 10px 15px;">'.$value['Order']['ref_number'].'</td>
                               
                                <td style="padding:10px 0 10px 15px;">'. $this->siteCurrency.' '.$value['Order']['order_sub_total'].'
                                </td>
                                <td style="padding:10px 0 10px 15px;">'.
                                $this->siteCurrency.' '.$commision.'
                                </td>
                            </tr>';
                            $count ++;
            if (!isset($order_detail[$nextValue]['Order']['payment_type']) || 
                    $order_detail[$nextValue]['Order']['payment_type'] != $main) {
                    $count = 1;
                $output .= '
                        </tbody>
                    </table>';
            }
        }

        $output .= '    </tbody>
                    </table>
                    <table width="100%" style="margin-top:15px;"  align="center" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td style="padding:10px 15px 10px 0;" colspan="2" align="right">Paid Gross Sale :</td>
                            <td style="padding:10px 15px 10px 0;" align="right">'.$this->siteCurrency.' '.$invoice_detail['Invoice']['gross_sale_amount'].'</td>
                        </tr>
                        <tr>
                            <td style="padding:10px 15px 10px;" colspan="2" align="right">Commission('. $this->siteSetting['Sitesetting']['vat_percent'].'%) :</td>
                            <td style="padding:10px 15px 10px 0" align="right">'.$this->siteCurrency.''.$invoice_detail['Invoice']['commision'].'</td>
                        </tr>
                         <tr>
                            <td style="padding:10px 15px 10px 0;" colspan="2" align="right"><strong> Card Fee('.$this->siteSetting['Sitesetting']['card_fee'].'%) :</strong></td>
                            <td style="padding:10px 15px 10px 0;" align="right">'.$this->siteCurrency.' '.$invoice_detail['Invoice']['commision_tax'].'</td>
                        </tr>
                        <tr>
                            <td style="padding:10px 15px 10px 0;" colspan="2" align="right"><strong>Restaurant Owned :</strong></td>
                            <td style="padding:10px 15px 10px 0;" align="right">'.$this->siteCurrency.' '.$invoice_detail['Invoice']['store_owned_total'].'</td>
                        </tr>
                    </table>';

        $source       = $this->siteUrl.'/siteicons/logo.png';
        $storeEmail   = $this->siteSetting['Sitesetting']['admin_email'];
        $toemail      = $invoice_detail['Store']['contact_email'];
        $storename    = $this->siteSetting['Sitesetting']['site_name'];

        /*$email = new CakeEmail();
        $email->from($storeEmail);
        $email->to($toemail);
        $email->subject('Invoice Mail');
        $email->template('register');
        $email->emailFormat('html');
        $email->viewVars(array('mailContent' => $output,'source'=>$source,'storename'=>$storename));*/


        $email = new CakeEmail();
        $email->from($storeEmail);
        $email->to('magesh.s@roamsoft.in');
        $email->subject('Invoice Mail');
        $email->template('register');
        $email->emailFormat('html');
        $email->viewVars(array('mailContent' => $output,'source'=>$source,'storename'=>$storename));


        $responseMessage = 'Invoice ---->'. $invoice_detail['Invoice']['ref_id'];
        $filePath      = ROOT.DS.'app'.DS."tmp".DS.'cronSetup.txt';
        $file = fopen($filePath,"a+");
        fwrite($file, PHP_EOL.'Message---->'.$responseMessage.PHP_EOL);
        fclose($file);

        echo "<pre>"; print_r($email);
        exit();



        if($email->send()){
            return true;
        }
        exit();
    }

}