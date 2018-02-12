<!DOCTYPE>
<html>
<head>
	<title></title>
	<style type="text/css">
		.invoice-box{
			max-width:100%;
			margin-top:20px;
		}
		table {
			border-collapse: collapse;
		}
	</style>
</head>
<body>

	<div class="invoice-box">
		<table style="width:100%;" border="0">

			<tr>
				<th colspan="2" style="font-size:25px;font-weight: bold;">
					BURGESS & WALKER <br> TRANSPORT LTD <br>
					<span style="font-style:italic;font-size:14px;font-weight:normal;">General Haulage </span> 
				</th>
				<th colspan="2" style="font-size:25px;font-weight:bold;border-bottom:1px solid #e0e0e0;">
				</th>
			</tr>	

			<tr>
				<td style="width:50%;padding-top: 5px;" rowspan="4"> 
					Five Acre Farm, <br>
					Needingworth Road, <br>
					St. Ives, Huntingdon, <br>
					Cambs PE27 4NB <br>
					Tel: St. Ives (01480) 468959 <br>
					Fax: (01480) 492893
				</td>

				<td style="width:10.35%;border-right:1px solid #e0e0e0;" rowspan="1">&nbsp;</td>
				<td style="font-weight:bold;border:1px solid #e0e0e0;padding:5px 0 0 50px;" colspan="2"> INVOICE  </td>
			</tr>

			<tr>
				<td style="width:10%;" rowspan="1">&nbsp;</td>
				<td  colspan="2">&nbsp;</td>
			</tr>

			<tr>
				<td style="width:10%;" rowspan="1">&nbsp;</td>
				<td colspan="2">&nbsp;</td>
			</tr>

			<tr>	
				<td style="width:10%;" rowspan="1">&nbsp;</td>
				<td colspan="2">&nbsp;</td>
			</tr>

			<tr>
				<td colspan="4">&nbsp;</td>
			</tr>

			<tr>
				<td colspan="1" style="font-size:13px;padding:5px 0 5px;border-bottom:1px solid #e0e0e0;">VAT Reg. No. 214 3379 81 </td>
				<td colspan="1">&nbsp;</td>
				<td colspan="2" style="border-bottom:1px solid #e0e0e0;"></td>
			</tr>

			<tr>
				<td style="width:50%;padding:8px 0 10px 40px;border:1px solid #e0e0e0;" rowspan="4"> 
					<?php echo @$billing_address; ?>
				</td>

				<td style="width:10%;border-right:1px solid #e0e0e0;" rowspan="1"></td>

				<td style="padding:7px;font-weight:bold;background-color:#dbdbdb;border:1px solid #e0e0e0;"> Invoice No. </td>
				<td style="text-align:center;width:20%;border:1px solid #e0e0e0;padding:7px;width:23%;"> <?php echo @$invoice_number; ?></td>
			</tr>


			<tr>
				<td style="width:10%;border-right:1px solid #e0e0e0;" rowspan="1"></td>

				<td style="padding:7px;font-weight:bold;background-color:#dbdbdb;border:1px solid #e0e0e0;"> Invoice/Tax Date </td>
				<td style="text-align:center;border: 1px solid #e0e0e0;padding:7px;"> <?php echo @$invoice_date; ?>  </td>
			</tr>

			<tr>
				<td style="width:10%;border-right:1px solid #e0e0e0;" rowspan="1"></td>

				<td style="padding:7px;font-weight:bold;background-color:#dbdbdb;border:1px solid #e0e0e0;"> Order No. </td>
				<td style="text-align:center;border:1px solid #e0e0e0;padding:7px;"> <?php echo @$jpo_number; ?>  </td>
			</tr>

			<tr>	
				<td style="width:10%;border-right:1px solid #e0e0e0;" rowspan="1"></td>

				<td style="padding:7px;font-weight:bold;background-color:#dbdbdb;border:1px solid #e0e0e0;"> Account No. </td>
				<td style="text-align:center;border:1px solid #e0e0e0;padding:7px;"> <?php echo @$account_no; ?> </td>
			</tr>

			<tr>
				<td colspan="4" style="padding:10px;">&nbsp;</td>
			</tr>

		</table>

		<table style="width: 100%;">

			<tr style="text-align:center;background-color:#dbdbdb;">
				<th style="padding:10px;border:1px solid #e0e0e0;width:15%"> Reference </th>
				<th style="padding:10px;border:1px solid #e0e0e0;width:15%"> Delivery Date </th>
				<th style="padding:10px;border:1px solid #e0e0e0;width:45%"> Description </th>
				<th style="padding:10px;border:1px solid #e0e0e0;width:10%"> VAT </th>
				<th style="padding:10px;border:1px solid #e0e0e0;width:15%"> Value </th>
			</tr>

			<tr>
				<td style="height:40%;padding:10px;padding-left:15px;border:1px solid #e0e0e0;"> <?php echo @$invoice_date; ?>  </td>
				<td style="height:40%;padding:10px;padding-left:15px;border:1px solid #e0e0e0;"> <?php echo @$delivery_time; ?>  </td>
				<td style="padding:10px;border:1px solid #e0e0e0;"> 
					<?php echo ($jn) ? @$job_name.' - '.@$jn : @$job_name ; ?> 
					<?php if(@$row->inotes) : ?>
							<?php echo @$row->inotes; ?>
					<?php endif; ?>
					<?php if($demurrage > 0) : ?>
						<br>
						<span>Demurrage</span>
					<?php endif; ?>
				</td>
				<td style="padding:10px;text-align:center;border:1px solid #e0e0e0;"> 
					<?php echo formatMoney(@$price * 0.20); ?> 
					<?php if($demurrage > 0) : ?>
						<br>
						<?php echo formatMoney($demurrage * 0.20); ?>
					<?php endif; ?>

				</td>
				<td style="padding:10px;text-align:center;border:1px solid #e0e0e0;"> 
					<?php echo formatMoney(@$price); ?> 
					<?php if($demurrage > 0) : ?>
						<br>
						<?php echo formatMoney($demurrage); ?>
					<?php endif; ?>
				</td>
			</tr>

		</table>


		<table>

			<tr>
				<td rowspan="1" style="padding:10px;border-bottom:1px solid #e0e0e0;width:50%;"></td>
				<td rowspan="1" style="width:10%;border-right:1px solid #e0e0e0;"></td>

				<td style="width:20%;padding:10px;font-weight:bold;border:1px solid #e0e0e0;border-top:hidden;"> Goods </td>
				<td style="text-align:right;width:20%;padding:10px;border:1px solid #e0e0e0;border-top:hidden;"> <?php echo formatMoney(@$price + @$demurrage); ?> </td>
			</tr>

			<tr>
				<td rowspan="3" style="padding:20px 40px;font-weight:bold;border:1px solid #e0e0e0;width: 50%; ">
					TERMS AND CONDITIONS <br> 
					STRICTLY 30 DAYS FROM THE DATE <br>
					ON THE INVOICE
				</td>
				<td rowspan="1" style="width:10%;border-right:1px solid #e0e0e0;">&nbsp;</td>

				<td style="width:20%;padding:10px;font-weight: bold;border:1px solid #e0e0e0;border-top:hidden;"> VAT </td>
				<td style="text-align:right;width:20%;padding:10px;border:1px solid #e0e0e0;border-top:hidden;"> <?php echo formatMoney(@$vat); ?> </td>
			</tr>

			<tr>
				<td rowspan="1" style="padding:10px;border-right:1px solid #e0e0e0;"></td>

				<td style="padding:10px;font-weight:bold;border:1px solid #e0e0e0;"> Total </td>
				<td style="text-align:right;padding:10px;border:1px solid #e0e0e0;"> <?php echo formatMoney(@$total_price);; ?> </td>
			</tr>

			<tr>
				<td rowspan="1" style="padding:10px;border:hidden;"></td>
			</tr>

		</table>

	</div>
	<span style="font-size: 7px;"><?php echo @$invoice_id; ?></span>


</body>
</html>
