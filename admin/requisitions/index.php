<?php if($_settings->chk_flashdata('success')): ?>
<script>
	alert_toast("<?php echo $_settings->flashdata('success') ?>",'success')
</script>
<?php endif;?>
<div class="card card-outline card-primary">
	<div class="card-header">
		<h3 class="card-title">Lista de Requisiciones</h3>
		<div class="card-tools">
			<a href="?page=requisitions/manage_req" class="btn btn-flat btn-primary"><span class="fas fa-plus"></span>  Crear Nuevo</a>
		</div>
	</div>
	<div class="card-body">
		<div class="container-fluid">
        <div class="container-fluid">
			<table class="table table-hover table-striped">
				<colgroup>
					<col width="5%">
					<col width="15%">
					<col width="15%">
					<col width="20%">
					<col width="10%">
					<col width="15%">
					<col width="20%">
					<col width="10%">
				</colgroup>
				<thead>
					<tr class="bg-navy disabled">
						<th>#</th>
						<th>Fecha Creación</th>
						<th># Orden de Requisición</th>
						<th>Proveedores</th>
						<th>Descripción</th>
						<th>Monto Total</th>
						<th>Acción</th>
					</tr>
				</thead>
<tbody>
<?php 
$sub_total = 0;
    $i = 1;
    $qry = $conn->query("SELECT 
    r.pr_no, 
    MAX(r.id) as id,
    MAX(r.iva_amount) as iva_amount, 
    MAX(r.isr_amount) as isr_amount,
    MAX(r.date_created) as recent_date,
    GROUP_CONCAT(DISTINCT s.name) as sname, 
    GROUP_CONCAT(DISTINCT it.product_key) as product_keys, 
    ri.req_id, 
    SUM(ri.unit_price * ri.quantity) as total_for_item
FROM `req_list` r 
LEFT JOIN req_items ri ON r.id = ri.req_id 
LEFT JOIN `supplier_list` s ON ri.supplier_id = s.id 
LEFT JOIN item_list it ON it.id = ri.item_id 
GROUP BY r.pr_no, ri.req_id
ORDER BY recent_date DESC
");
    
    while($row = $qry->fetch_assoc()):
?>
    <tr data-id="<?php echo $row['id'] ?>">
        <td class="text-center"><?php echo $i++; ?></td>
        <td class=""><?php echo date("M d,Y H:i",strtotime($row['recent_date'])) ; ?></td>
        <td class=""><?php echo $row['pr_no'] ?></td>
        <td class=""><?php echo implode(", ", array_unique(explode(",", $row['sname']))); ?></td>
        <td class="text-right"><?php echo implode(", ", array_unique(explode(",", $row['product_keys']))); ?></td>
        <td class="text-right"><?php echo number_format($row['iva_amount'] + $row['isr_amount'] + $row['total_for_item']) ?></td>
        <td align="center">
            <button type="button" class="btn btn-flat btn-default btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown">
                Acción
                <span class="sr-only">Toggle Dropdown</span>
            </button>
            <div class="dropdown-menu" role="menu">
                <a class="dropdown-item" href="?page=requisitions/view_req&id=<?php echo $row['id'] ?>"><span class="fa fa-eye text-primary"></span> Ver</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="?page=requisitions/manage_req&id=<?php echo $row['id'] ?>"><span class="fa fa-edit text-primary"></span> Editar</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item delete_data" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"><span class="fa fa-trash text-danger"></span> Eliminar</a>
            </div>
        </td>
    </tr>
<?php endwhile; ?>
</tbody>
			</table>
		</div>
		</div>
	</div>
</div>
<script>
	$(document).ready(function(){
		$('.delete_data').click(function(){
			_conf("¿Estás segur@ de eliminar esta orden de forma permanente?","delete_req",[$(this).attr('data-id')])
		})
		$('.view_details').click(function(){
			uni_modal("Reservaton Details","purchase_orders/view_details.php?id="+$(this).attr('data-id'),'mid-large')
		})
		$('.renew_data').click(function(){
			_conf("Are you sure to renew this rent data?","renew_rent",[$(this).attr('data-id')]);
		})
		$('.table th,.table td').addClass('px-1 py-0 align-middle')
		$('.table').dataTable();
	})
	function delete_req($id){
		start_loader();
		$.ajax({
			url:_base_url_+"classes/Master.php?f=delete_req",
			method:"POST",
			data:{id: $id},
			dataType:"json",
			error:err=>{
				console.log(err)
				alert_toast("An error occured.",'error');
				end_loader();
			},
			success:function(resp){
				if(typeof resp== 'object' && resp.status == 'success'){
					location.reload();
				}else{
					alert_toast("An error occured.",'error');
					end_loader();
				}
			}
		})
	}
	function renew_rent($id){
		start_loader();
		$.ajax({
			url:_base_url_+"classes/Master.php?f=renew_rent",
			method:"POST",
			data:{id: $id},
			dataType:"json",
			error:err=>{
				console.log(err)
				alert_toast("An error occured.",'error');
				end_loader();
			},
			success:function(resp){
				if(typeof resp== 'object' && resp.status == 'success'){
					location.reload();
				}else{
					alert_toast("An error occured.",'error');
					end_loader();
				}
			}
		})
	}
</script>