<?php
/* janakiraman */
App::import('Vendor', 'Mpdf', array('file' => 'mpdf' . DS . 'mpdf.php'));
App::uses('AppController','Controller');
App::uses('CakeEmail', 'Network/Email');

class InvoicesController extends AppController {    
    public $helpers     = array('Html','Form', 'Session', 'Javascript');  
    public $uses        = array('Invoice','Store','Order','State','City','Location');
    public $components  = array('Functions','Mpdf');

    // Admin invoice index
    public function admin_index() {   
        $invoice_list  = $this->Invoice->find('all', array(
                                        'order' => 'Invoice.id Desc'));
        $this->set(compact('invoice_list'));

    }

    // Admin invoice details
    public function admin_invoiceDetail() {

        $ids  = $this->params['pass'];

        $site_detail  = $this->siteSetting;
        $tax          = $site_detail['Sitesetting']['vat_percent'];
        $invoice_detail = $this->Invoice->findById($ids[3]);

        $order_detail = $this->Order->find('all',array(
                        'conditions'=>array(
                                    'Order.store_id'=>$ids[0],
                                    'Order.status'=>'Delivered',
                                    'Order.delivery_date between ? and ?' =>
                                                array($ids[1], $ids[2])),
                        'order' => array('Order.payment_type' => 'Asc')));

        $state_list  = $this->State->findById($invoice_detail['Store']['store_state']);
        $city_list   = $this->City->findById($invoice_detail['Store']['store_city']);
        $area_list   = $this->Location->findById($invoice_detail['Store']['store_zip']);

        $this->set(compact('order_detail','state_list','city_list','area_list','invoice_detail','site_detail','tax'));
    }

    // Store invoice index
    public function store_index() {
      $this->layout  = 'assets';
      $storeId       = $this->Auth->User('Store.id');
      $invoice_list  = $this->Invoice->find('all',array(
                                            'conditions'=>array('Invoice.store_id'=>$storeId),
                                            'order' => 'Invoice.id Desc'));
      $this->set(compact('invoice_list'));
    }

    // Store invoice details
    public function store_invoiceDetail() {
        $site_detail  = $this->siteSetting;
        $this->layout  = 'assets';
        $ids  = $this->params['pass'];
        $invoice_detail = $this->Invoice->findById($ids[3]);
        $order_detail   = $this->Order->find('all',array(
                        'conditions'=>array(
                                    'Order.store_id'=>$ids[0],
                                    'Order.status'=>'Delivered',
                                    'Order.delivery_date between ? and ?' =>
                                                array($ids[1], $ids[2])),
                        'order' => array('Order.payment_type' => 'Asc')));

        $state_list = $this->State->findById($order_detail[0]['Store']['store_state']);
        $city_list  = $this->City->findById($order_detail[0]['Store']['store_city']);
        $area_list  = $this->Location->findById($order_detail[0]['Store']['store_zip']);
        $tax        = $this->siteSetting['Sitesetting']['vat_percent'];

        $this->set(compact('order_detail','state_list','city_list','area_list','invoice_detail','site_detail','tax'));
    }

    // Invoice pdf
    public function invoicePdf($invoiceId) {

        if ($this->Auth->User('role_id') == 1) {
            $invoice_detail = $this->Invoice->findById($invoiceId);
        } elseif ($this->Auth->User('role_id') == 3) {
            $storeId        = $this->Auth->User('Store.id');
            $invoice_detail = $this->Invoice->findByIdAndStoreId($invoiceId, $storeId);
        }

        if (empty($invoice_detail)) {
            echo $this->render('/Errors/error400');
            exit();
        }

        $startDate  = $invoice_detail['Invoice']['start_date'];
        $endDate    = $invoice_detail['Invoice']['end_date'];
        $this->Order->recursive = 0;

        $order_detail = $this->Order->find('all',array(
                            'conditions'=>array(
                                        'Order.store_id' => $invoice_detail['Invoice']['store_id'],
                                        'Order.status'=>'Delivered',
                                        'Order.delivery_date between ? and ?' =>array($startDate, $endDate)),
                            'order' => array(//'Order.id' => 'Asc',
                                             'Order.payment_type' => 'Asc')));

        $state_list = $this->State->findById($order_detail[0]['Store']['store_state']);
        $city_list  = $this->City->findById($order_detail[0]['Store']['store_city']);
        $area_list  = $this->Location->findById($order_detail[0]['Store']['store_zip']);


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
                            <span style="font:12px Verdana;">'.$order_detail[0]['Store']['contact_name'].'<br>
                                '.$order_detail[0]['Store']['store_name'].'<br>';
                                    if ($this->siteSetting['Sitesetting']['address_mode'] != 'Google') {
                                        $output .= $area_list['Location']['area_name'] . ',' .
                                            $area_list['City']['city_name'] . '-' .
                                            $area_list['Location']['zip_code'] . ',<br>' .
                                            $area_list['State']['state_name'] . ', ' .
                                            $state_list['Country']['country_name'];
                                    } else {
                                        $output .= $order_detail[0]['Store']['address'];
                                    }
                            $output .= '</span>
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

            $main  = '';
            $count = 1;

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
                
            $output .= '<h1 style="display:inline-block; font:24px Verdana;">Order Information</h1>';

            foreach($order_detail as $key => $value) {
                $nextValue = $key+1;
                if ($value['Order']['payment_type'] != $main) {
                    $main = $value['Order']['payment_type'];

                    $paymentType =  ($value['Order']['payment_type'] == 'cod') ? 'Cash' : $value['Order']['payment_type'].' Paid';

                    $output .= '<h4>'.$paymentType.'</h4>
                                <table width="100%" style="margin-top:15px;"  align="center" border="1" cellspacing="1" cellpadding="1">
                                    <thead>
                                        <tr>
                                            <th style="padding:10px 0 10px 15px;">S.no</th>
                                            <th style="padding:10px 0 10px 15px;">Order  Id</th>
                                            <th style="padding:10px 0 10px 15px;">Subtotal</th>
                                            <th style="padding:10px 0 10px 15px;">Tax</th>
                                            <th style="padding:10px 0 10px 15px;">Delivery Charge</th>
                                            <th style="padding:10px 0 10px 15px;">Tips</th>
                                            <th style="padding:10px 0 10px 15px;">Commision</th>
                                        </tr>
                                    </thead>
                                    <tbody>';
                }
                    $commision = $value['Order']['order_sub_total'] * ($this->siteSetting['Sitesetting']['card_fee'] / 100);
                    $output   .= '
                                        <tr class="odd gradeX">
                                            <td style="padding:10px 0 10px 15px;">' . $count . '</td>
                                            <td style="padding:10px 0 10px 15px;">' . $value['Order']['ref_number'] . '</td>
                                            <td style="padding:10px 0 10px 15px;">' . $this->siteCurrency . ' ' .$value['Order']['order_sub_total'] . '
                                            </td>
                                            <td style="padding:10px 0 10px 15px;">' . $this->siteCurrency.' '.$value['Order']['tax_amount'] . '</td>
                                            <td style="padding:10px 0 10px 15px;">' . $this->siteCurrency.' '.$value['Order']['delivery_charge'] . '</td>
                                            <td style="padding:10px 0 10px 15px;">' . $this->siteCurrency.' '.$value['Order']['tip_amount'] . '</td>
                                            <td style="padding:10px 0 10px 15px;">' .
                                                $this->siteCurrency . ' ' . number_format($commision, 2) . '
                                            </td>
                                        </tr>';
                                $count++;
                if (!isset($order_detail[$nextValue]['Order']['payment_type']) || 
                        $order_detail[$nextValue]['Order']['payment_type'] != $main) {
                        $count = 1;

                            $output .= '
                                    </tbody>
                                </table>'; 
                }
            }

        $output .= '    </tbody>
                    </table>';

        // initializing mPDF
        $this->Mpdf->init();


        $mpdf=new mPDF();
        //$mpdf->SetJS('this.print();');
        $mpdf->WriteHTML($output);
        $mpdf->Output();
        exit();
    }
}