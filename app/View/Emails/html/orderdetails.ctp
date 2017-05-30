<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
      <title>Order details <?php echo $order_id; ?> in <?php echo $storename; ?></title>
      <!--IMPORTANT: 
      Before deploying this email template into your application make sure you convert all the css code in <style> tag using http://beaker.mailchimp.com/inline-css.
      Chrome and other few mail clients do not support <style> tag so the above converter from mailchip will make sure that all the css code will be converted into inline css.
      -->
      <meta name="viewport" content="width=device-width"/>
</head>
<body>
      <table cellpadding="0" cellspacing="0" border="0" height="100%" width="100%" bgcolor="#f4f4f4" id="bodyTable" style="border-collapse: collapse;table-layout: fixed;margin:0 auto;"><tr><td>

        <!-- Outlook and Lotus Notes don't support max-width but are always on desktop, so we can enforce a wide, fixed width view. -->
        <!-- Beginning of Outlook-specific wrapper : BEGIN -->
            <!--[if (gte mso 9)|(IE)]>
        <table width="600" align="center" cellpadding="0" cellspacing="0" border="0">
          <tr>
            <td>
        <![endif]-->
        <!-- Beginning of Outlook-specific wrapper : END -->

        <!-- Email wrapper : BEGIN -->
        <table border="0" width="100%" cellpadding="0" cellspacing="0" align="center" style="max-width: 640px; background:#fff; margin: 5px auto;" class="email-container">
          <tr>
            <td>
              <!-- Logo Left, Nav Right : BEGIN -->
              <table border="0" width="100%" cellpadding="0" cellspacing="0">
                <tr>
                  <td height="10" style="font-size: 0; line-height: 0;">&nbsp;</td>
                </tr>
                <tr>
                  <td valign="middle" style="" align="center">
                        <a href="" target="_blank" style="width:100%; text-align:center; display:inline-block">
                          <img src="images/logo.png" alt="shoppieeshop" style="display: block;">
                        </a>
                  </td>
                </tr>
                <tr>
                  <td height="25" style="font-size: 0; line-height: 0;">&nbsp;</td>
                </tr>
              </table>
              <!-- Logo Left, Nav Right : END -->
        
              <table border="0" width="100%" cellpadding="0" cellspacing="0" >
            
                <!-- Logo Centered + Vertical Padding : BEGIN -->
                <tr>
                  <td style="padding: 10px; font-family: sans-serif; font-size: 16px; line-height: 27px; color: #000;">
                   <b>Dear <?php echo $CUSTOMER; ?>,</b><br>
                   <i style="font-size:13px;">Greeting from </i>

                   </td>
                  </tr>
                <tr>
                  <td style="padding:0 10px; font-family: sans-serif; font-size: 13px; line-height: 20px; color: #666666;">
                         <table cellpadding="5" cellspacing="0" width="100%" style=" text-align:left; color:#fff; table-layout:fixed; margin:auto; color:#666; border-collapse:separate;background:#fff; border:1px solid #b1b1b2; border-radius:3px">
      <thead>
            <tr>
              <th width="35%" bgcolor="#777" align="left" style="color:#fff; padding-left:10px; text-align:left;font-family:Helvetica,Arial,sans-serif;font-size:14px;font-weight:bold;word-wrap:break-word">
                  Product Name       
                   </th>
                    <th width="15%" bgcolor="#777" align="left" style="color:#fff; padding-left:10px; text-align:left;font-family:Helvetica,Arial,sans-serif;font-size:14px;font-weight:bold;word-wrap:break-word">
                  Seller Name       
                   </th>
                      <th width="13%" bgcolor="#777" align="center" style="color:#fff; text-align:center;font-family:Helvetica,Arial,sans-serif;font-size:14px;font-weight:bold;word-wrap:break-word">
                  Qty
                  </th>
                      <th width="18%" bgcolor="#777" align="center" style="color:#fff; text-align:center;font-family:Helvetica,Arial,sans-serif;font-size:14px;font-weight:bold;word-wrap:break-word">
                  Price        </th>
              <th width="18%" bgcolor="#777" align="center" style="color:#fff; text-align:center;font-family:Helvetica,Arial,sans-serif;font-size:14px;font-weight:bold;word-wrap:break-word">
                  Total        </th>
            </tr>
            
      </thead>
      <tbody>
                <?php echo $order_items; ?>
                  </tbody>
      <tfoot>
                  <td  colspan="4" style="text-align:right;font-family:Helvetica,Arial,sans-serif;font-size:12px;word-wrap:break-word; padding-right:10px;"><b>Sub-Total :</b></td>
                  <td  style="text-align:center;font-family:Helvetica,Arial,sans-serif;font-size:12px;border-left:1px solid #e0e0e0"><?php echo $subtotal; ?></td>
            </tr>
                  <tr>
                  <td  colspan="4" style="text-align:right;font-family:Helvetica,Arial,sans-serif;font-size:12px;word-wrap:break-word; padding-right:10px;"><b>Tax (18%) :</b></td>
                  <td  style="text-align:center;font-family:Helvetica,Arial,sans-serif;font-size:12px;border-left:1px solid #e0e0e0"><?php echo $tax; ?></td>
            </tr>
                  <tr>
                  <td  colspan="4" style="text-align:right;font-family:Helvetica,Arial,sans-serif;font-size:12px;word-wrap:break-word; padding-right:10px;"><b>Delivery Charges :</b></td>
                  <td style="text-align:center;font-family:Helvetica,Arial,sans-serif;font-size:12px;border-left:1px solid #e0e0e0"><?php echo $SHIPCHARGE; ?></td>
            </tr>
          <tr>
                  <td  colspan="4" style="text-align:right;font-family:Helvetica,Arial,sans-serif;font-size:12px;word-wrap:break-word; padding-right:10px;"><b>Discount (10.00% off):</b></td>
                  <td style="text-align:center;font-family:Helvetica,Arial,sans-serif;font-size:12px;border-left:1px solid #e0e0e0">00000</td>
            </tr>
          <tr>
                  <td colspan="4" style="text-align:right;font-family:Helvetica,Arial,sans-serif;font-size:12px;word-wrap:break-word; padding-right:10px;"><b>Total :</b></td>
                  <td  style="text-align:center;font-family:Helvetica,Arial,sans-serif;font-size:12px;border-left:1px solid #e0e0e0"><?php echo $GRANDTOTAL; ?></td>
            </tr>
            </tfoot>
      </table>

                  </td>
                </tr>
                <tr>
                  <td>
                        <!-- 2 x 2 grid : BEGIN -->
                    <table border="0" width="100%" cellpadding="0" cellspacing="0" align="center">
                      <tr>
                        <td valign="top" width="50%" style="padding:10px 10px; font-family: sans-serif; font-size: 13px; line-height: 24px; color: #3a3939; text-align: left;">
                         <table width="100%" bordercolor="#fff" bgcolor="#b1b1b2" style="border:1px solid #b1b1b2; background-color:#fff;">
                             <thead>
                              <tr>
                                 <th bgcolor="#bd1104" style="background-color:#777; padding:5px; color:#fff">Here is your order information</th>
                              </tr>
                             </thead>
                             <tbody style="display:block;">
                              <tr>
                                 <td  style="padding:5px 5px 0;" height="10px"><b>Order Number/ID :</b></td>
                                 <td style="padding:5px 5px 0 0;" height="10px">498</td>
                              </tr>
                              <tr>
                                 <td style="padding:0 5px 0;" height="10px"><b>Payment Method :</b></td>
                                 <td style="padding:0 5px 0 0;" height="10px">Cash On Delivery</td>
                              </tr>
                              <tr>
                                 <td style="padding:0 5px 0;" height="10px"><b>Payment type :</b></td>
                                 <td style="padding:0 5px 0 0;" height="10px">One day shipping</td>
                              </tr>
                              <tr>
                                 <td style="padding:0 5px 0;" height="10px" ><b>Date Ordered :</b></td>
                                 <td style="padding:0 5px 0 0;" height="10px">09/01/2015</td>
                              </tr>
                             </tbody>
                             </table>
                        </td>
                        <td valign="top" width="50%" style="padding:10px 10px; font-family: sans-serif; font-size: 14px; line-height: 24px;">
                           <table width="100%" bordercolor="#fff" bgcolor="#b1b1b2" style="border:1px solid #b1b1b2; background-color:#fff;">
                             <thead>
                              <tr>
                                 <th bgcolor="#bd1104" style="background-color:#777; padding:5px; color:#fff">Customer shipping address:</th>
                              </tr>
                             </thead>
                             <tbody style="display:block;">
                              <tr>
                                 <td  style="padding:5px 5px 0;" height="10px"><b>Mohan K   9551555007</b></td>
                              </tr>
                              <tr>
                                 <td style="padding:0 5px 0;" height="10px">
                                       30C, engineer sampath garden<br>
                                       Arumbakkam<br>
                                       Chennai – 600106<br>
                                       Tamilnadu- India<br>
                                 </td>
                              </tr>
                              
                             </tbody>
                             </table>
                        </td>
                      </tr>
                     </table>
                  </td>
                   </tr>
                    <tr>
                  <td height="20" style="font-size: 0; line-height: 0;">&nbsp;</td>
                </tr>
                    </table>
                        <!-- 2 x 2 grid : END -->
                  </td>
                  </tr>
             
                  
                 
              </table>
            </td>
                  </tr>
       
        </table>
      </td></tr></table>
</body>
</html>