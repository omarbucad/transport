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

	<?php foreach($y as $key => $x) : ?>

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
					<td style="font-weight:bold;border:1px solid #e0e0e0;padding:5px 0 0 50px;" colspan="2">INVOICE </td>
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
						<?php echo @$x['invoice'][0]->billing_address; ?>
					</td>

					<td style="width:10%;border-right:1px solid #e0e0e0;" rowspan="1"></td>

					<td style="padding:7px;font-weight:bold;background-color:#dbdbdb;border:1px solid #e0e0e0;"> Invoice No. </td>
					<td style="text-align:center;width:20%;border:1px solid #e0e0e0;padding:7px;width:23%;"> <?php echo @$x['invoice'][0]->invoice_number; ?> </td>
				</tr>


				<tr>
					<td style="width:10%;border-right:1px solid #e0e0e0;" rowspan="1"></td>

					<td style="padding:7px;font-weight:bold;background-color:#dbdbdb;border:1px solid #e0e0e0;"> Invoice/Tax Date </td>
					<td style="text-align:center;border: 1px solid #e0e0e0;padding:7px;"> <?php echo @$x['invoice'][0]->invoice_date; ?>  </td>
				</tr>

				<tr>
					<td style="width:10%;border-right:1px solid #e0e0e0;" rowspan="1"></td>

					<td style="padding:7px;font-weight:bold;background-color:#dbdbdb;border:1px solid #e0e0e0;"> Order No. </td>
					<td style="text-align:center;border:1px solid #e0e0e0;padding:7px;"> <?php echo @$x['invoice'][0]->jpo_number; ?>  </td>
				</tr>

				<tr>	
					<td style="width:10%;border-right:1px solid #e0e0e0;" rowspan="1"></td>

					<td style="padding:7px;font-weight:bold;background-color:#dbdbdb;border:1px solid #e0e0e0;"> Account No. </td>
					<td style="text-align:center;border:1px solid #e0e0e0;padding:7px;"> <?php echo @$x['invoice'][0]->account_no; ?> </td>
				</tr>

				<tr>
					<td colspan="4" style="padding:10px;">&nbsp;</td>
				</tr>

			</table>

			<table style="width: 100%;">

				<tr style="text-align:center;background-color:#dbdbdb;">
					<th style="padding:10px;border:1px solid #e0e0e0;width:15%"> Delivery Date </th>
					<th style="padding:10px;border:1px solid #e0e0e0;width:60%"> Description </th>
					<th style="padding:10px;border:1px solid #e0e0e0;width:10%"> VAT </th>
					<th style="padding:10px;border:1px solid #e0e0e0;width:15%"> Value </th>
				</tr>

				<?php foreach($x['result'] as $row) : ?>

				<tr>
					<td style="height:10px;padding:10px;padding-left:15px;border:1px solid #e0e0e0;"> 
						<?php echo @$row->delivery_time; ?> 
					</td>
					<td style="padding:10px;border:1px solid #e0e0e0;"> 
						<?php echo ($row->job_number) ? @$row->job_name .' - '.@$row->job_number : @$row->job_name; ?>  
						<?php if(@$row->inotes) : ?>
								<?php echo @$row->inotes; ?>
						<?php endif ; ?>


						<?php if($row->demurrage > 0) : ?>
							<br>
							Demurrage
						<?php endif; ?>
					</td>
					<td style="padding:10px;text-align:center;border:1px solid #e0e0e0;"> 
						<?php echo formatMoney(@$row->vat - ($row->demurrage * 0.20)); ?>
						<?php if($row->demurrage > 0) : ?>
							<br>
							<?php echo formatMoney($row->demurrage * 0.20); ?>
						<?php endif; ?>
					</td>
					<td style="padding:10px;text-align:center;border:1px solid #e0e0e0;"> 
						<?php echo formatMoney(@$row->price); ?> 
						<?php if($row->demurrage > 0) : ?>
							<br>
							<?php echo formatMoney($row->demurrage); ?>
						<?php endif; ?>
					</td>
				</tr>

				<?php endforeach; ?>

			</table>

			<?php if(($key+1) == count($y)) : ?>
				<table>

				<tr>
					<td rowspan="1" style="padding:10px;border-bottom:1px solid #e0e0e0;width:50%;"></td>
					<td rowspan="1" style="width:10%;border-right:1px solid #e0e0e0;"></td>

					<td style="width:20%;padding:10px;font-weight:bold;border:1px solid #e0e0e0;border-top:hidden;"> Goods </td>
					<td style="text-align:right;width:20%;padding:10px;border:1px solid #e0e0e0;border-top:hidden;"> <?php echo formatMoney(@$x['invoice'][0]->price + @$x['invoice'][0]->demurrage); ?> </td>
				</tr>

				<tr>
					<td rowspan="3" style="padding:20px 40px;font-weight:bold;border:1px solid #e0e0e0;width: 50%; ">
						TERMS AND CONDITIONS <br> 
						STRICTLY 30 DAYS FROM THE DATE <br>
						ON THE INVOICE
					</td>
					<td rowspan="1" style="width:10%;border-right:1px solid #e0e0e0;">&nbsp;</td>

					<td style="width:20%;padding:10px;font-weight: bold;border:1px solid #e0e0e0;border-top:hidden;"> VAT </td>
					<td style="text-align:right;width:20%;padding:10px;border:1px solid #e0e0e0;border-top:hidden;"> <?php echo formatMoney(@$x['invoice'][0]->vat ); ?> </td>
				</tr>

				<tr>
					<td rowspan="1" style="padding:10px;border-right:1px solid #e0e0e0;"></td>

					<td style="padding:10px;font-weight:bold;border:1px solid #e0e0e0;"> Total </td>
					<td style="text-align:right;padding:10px;border:1px solid #e0e0e0;"> <?php echo formatMoney(@$x['invoice'][0]->total_price); ?> </td>
				</tr>

				<tr>
					<td rowspan="1" style="padding:10px;border:hidden;"></td>
				</tr>

			</table>

			<?php endif; ?>

			</div>
			<span style="font-size: 7px;"><br><br><?php echo @$x['invoice'][0]->invoice_id; ?></span>
			
			<div style="width: 100%;text-align: center;">
				page <?php echo ($key+1) .' of '.count($y)?>
			</div>

	<?php endforeach; ?>


</body>
</html>
