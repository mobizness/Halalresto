<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Your trial period end</title>
<meta name="viewport" content="width=device-width">
</head>
<body style="min-width: 100%;-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;margin: 0;padding: 0;direction: ltr;background: #f6f8f1;width: 100% !important;">

<table class="body">
<tr>
	<td class="center" align="center" valign="top">		
		<table class="page-header" align="center" style="width: 100%;background: #1f1f1f;">
		<tr>
			<td class="center" align="center">				
				<table class="container" align="center">
				<tr>
					<td>
						<table class="row ">
						<tr>
							<td class="wrapper vertical-middle" style="padding-top: 0;padding-bottom: 0;vertical-align: middle;">
								
								<table class="six columns">
								<tr>
									<td class="vertical-middle" style="padding-top: 0;padding-bottom: 0;vertical-align: middle;">
										<a style="color: #2ba6cb;text-decoration: none;" href="<?php $siteUrl ?>">
											<?php echo $server_name ?>
										</a>
									</td>
								</tr>
								</table>
								
							</td>
							
						</tr>
						</table>
					</td>
				</tr>
				</table>
				
			</td>
		</tr>
		</table>		
		<?php echo $mailContent; ?>		
		<table class="page-footer" align="center" style="width: 100%;background: #2f2f2f;">
		<tr>
			<td class="center" align="center" style="vertical-align: middle;color: #fff;">
				<table class="container" align="center">
				<tr>
					<td style="vertical-align: middle;color: #fff;">						
						<table class="row">
						<tr>
							<td class="wrapper last" style="vertical-align: middle;color: #fff;">
								<span style="font-size:12px;">
								<i>This ia a system generated email and reply is not required.</i>
								</span>
							</td>
						</tr>
						</table>						
						<table class="row">
						<tr>
							<td class="wrapper" style="vertical-align: middle;color: #fff;">
								<table class="four columns">
								<tr>
									<td class="vertical-middle" style="padding-top: 0;padding-bottom: 0;vertical-align: middle;color: #fff;">
										 &copy; <?php echo $server_name; ?> <?php $year ?>.
									</td>
								</tr>
								</table>
							</td>							
						</tr>
						</table>					
					</td>
				</tr>
				</table>
			</td>
		</tr>
		</table>		
	</td>
</tr>
</table>
</body>
</html>