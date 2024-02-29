<?php if ($_settings->chk_flashdata('success')): ?>
    <script>
        alert_toast("<?php echo $_settings->flashdata('success') ?>", 'success')
    </script>
<?php endif; ?>
<?php
if (isset($_GET['id']) && $_GET['id'] > 0) {
    $qry = $conn->query("SELECT p.*, r.pr_no,r.email_client, r.branch_office from `po_list` p LEFT JOIN req_list r ON p.req_id = r.id where p.id = '{$_GET['id']}' ");
    if ($qry->num_rows > 0) {
        foreach ($qry->fetch_assoc() as $k => $v) {
            $$k = $v;
        }
    }
}
?>
<style>
    span.select2-selection.select2-selection--single {
        border-radius: 0;
        padding: 0.25rem 0.5rem;
        padding-top: 0.25rem;
        padding-right: 0.5rem;
        padding-bottom: 0.25rem;
        padding-left: 0.5rem;
        height: auto;
    }
    /* Chrome, Safari, Edge, Opera */
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    /* Firefox */
    input[type=number] {
        -moz-appearance: textfield;
    }
    [name="tax_percentage"],[name="discount_percentage"]{
        width:5vw;
    }
</style>
<div class="card card-outline card-info">
    <div class="card-header">
        <h3 class="card-title"><?php echo isset($id) ? "Actualizar detalles de Orden de Compra" : "New Purchase Order" ?> </h3>
        <div class="card-tools">
            <button class="btn btn-sm btn-flat btn-success" id="print" type="button"><i class="fa fa-print"></i> Imprimir</button>
            <a class="btn btn-sm btn-flat btn-primary" href="?page=purchase_orders/manage_po&id=<?php echo $id ?>">Editar</a>
            <a class="btn btn-sm btn-flat btn-default" href="?page=purchase_orders">Volver</a>
        </div>
    </div>
    <div class="card-body" id="out_print">
        <div class="row">
            <div class="col-6 d-flex align-items-center">
                <div>
                    <p class="m-0"><?php echo $_settings->info('company_name') ?></p>
                    <a>Correo</a>
                    <p class="m-0"><?php echo $_settings->info('company_email') ?></p>
                    <a>Direccion</a>
                    <p class="m-0"><?php echo $_settings->info('company_address') ?></p>
                </div>
            </div>
            <div class="col-6">
                <center><img src="<?php echo validate_image($_settings->info('logo')) ?>" alt="" height="200px"></center>
                <h2 class="text-center"><b></b></h2>
            </div>
        </div>
        <div class="row mb-2">
            <div class="col-6">
                <div class="col-6">
                    <div class="d-flex">
                        <p  class="m-0"><b>FOLIO No.</b></p>
                        <p class="ml-3"><b><?php echo $invoice ?></b></p>
                    </div>
                    <div class="d-flex">
                        <p  class="m-0"><b>FECHA DE SOLICITUD</b></p>
                        <p class="ml-3"><b><?php echo date("Y/m/d", strtotime($date_created)) ?></b></p>
                    </div>
                    
                    <div class="d-flex">
                        <!-- <div><p><b>FIRMA DE AUTORIZACIÓN:&nbsp&nbsp&nbsp&nbsp</b></p></div><div><p>_____________________</p></div> -->
                        <h3>Lic.Laura Alvarez M</h3> 
                    </div>
                    <!--<div>-->
                    <!--    <p style="text-decoration:underline;"><b><?php echo isset($notes) ? $notes : '' ?></b></p>-->
                    <!--</div>-->
                </div>
                <!--<p class="m-0"><b>Proveedores</b></p>-->
                <?php
//                $sup_qry = $conn->query("SELECT * FROM supplier_list where id = '{$supplier_id}'");
//                $supplier = $sup_qry->fetch_array();
                ?>
                <div>
                    <!--<p class="m-0"><?php echo $supplier['name'] ?></p>-->
<!--                    <p class="m-0"><?php //echo $supplier['address']              ?></p>
                    <p class="m-0"><?php //echo $supplier['contact_person']              ?></p>
                    <p class="m-0"><?php //echo $supplier['contact']              ?></p>-->
<!--                    <p class="m-0"><?php echo $supplier['email'] ?></p>-->
                </div>
            </div>
            <div class="col-6 row">
                <!--                <div class="col-6">
                                    <p  class="m-0"><b># Orden:</b></p>
                                    <p><b><?php //echo $po_no              ?></b></p>
                                </div>-->
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <table class="table table-striped table-bordered mb-0" id="item-list">
                    <colgroup>
                        <col width="70%">
                        <col width="30%">
                    </colgroup>
                    <thead>
                    <th class="bg-navy disabled text-light px-1 py-1 text-center"> <div class=" d-flex">
                        <!-- <p  class="m-0"><b>Vendedor:
                        </b> <?php echo $seller_name ?></p> -->
                    </div></th>
                    <th class="bg-navy disabled text-light px-1 py-1 text-center">CONDICIONES DE PAGO</th>
                    </thead>
                    <tbody>
                        <tr class="disabled" style="">
                            <td class="align-middle p-0 text-center"><?php echo $pr_no ?></td>
                            <td class="align-middle p-1 text-center"><?php echo $way_pay ?></td>
                        </tr>
                    </tbody>
                </table>
                <table class="table table-striped table-bordered" id="item-list">
                    <colgroup>
                        <col width="10%">
                        <col width="15%">
                        <col width="25%">
                        <col width="30%">
                        <col width="20%">
                    </colgroup>
                    <thead>
                        <tr class="bg-navy disabled" style="">
                            <th class="bg-navy disabled text-light px-1 py-1 text-center">CANTIDAD</th>
                            <th class="bg-navy disabled text-light px-1 py-1 text-center">UNIDAD</th>
                            <th class="bg-navy disabled text-light px-1 py-1 text-center">Descripción</th>
                            <th class="bg-navy disabled text-light px-1 py-1 text-center">UNITARIO</th>
                            <th class="bg-navy disabled text-light px-1 py-1 text-center">SUBTOTAL</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (isset($id)):
                            $order_items_qry = $conn->query("SELECT o.*,i.product_key, i.description,i.unit, p.po_no, r.pr_no FROM `order_items` o inner join item_list i on o.item_id = i.id LEFT JOIN po_list p ON p.id = o.po_id LEFT JOIN req_list r ON r.id = p.req_id where o.`po_id` = '$id' ");
                            $sub_total = 0;
                            $printed = false;
                            while ($row = $order_items_qry->fetch_assoc()):
                                $sub_total += ($row['quantity'] * $row['unit_price']);
                                ?>
                                <tr class="po-item" data-id="">
                                    <td class="align-middle p-0 text-center"><?php echo $row['quantity'] ?></td>
                                    <td class="align-middle p-1"><?php echo $row['unit'] ?></td>
                                    <td class="align-middle p-1"><?php echo $row['product_key'] ?></td>
                                    <td class="align-middle p-1"><?php echo number_format($row['unit_price']) ?></td>
                                    <td class="align-middle p-1 text-right total-price"><?php echo number_format($row['quantity'] * $row['unit_price']) ?></td>
                                </tr>
                                <?php
                            endwhile;
                        endif;
                        ?>
                    </tbody>
                    <tfoot>
                        <tr class="bg-lightblue">
                        <tr>
                            <th class="p-1 text-right" colspan="4">Sub Total</th>
                            <th class="p-1 text-right" id="sub_total"><?php echo number_format($sub_total) ?></th>
                        </tr>
                        <?php if ($iva_percentage !== 0): ?>
                            <tr>
                                <th class="p-1 text-right" colspan="4">IVA (<?php echo isset($iva_percentage) ? $iva_percentage : 0 ?>%)
                                </th>
                                <th class="p-1 text-right"><?php echo isset($iva_amount) ? number_format($iva_amount) : 0 ?></th>
                            </tr>
                        <?php endif; ?>
                        <?php if ($isr_percentage !== 0): ?>
                            <tr>
                                <th class="p-1 text-right" colspan="4">ISR (<?php echo isset($isr_percentage) ? $isr_percentage : 0 ?>%)</th>
                                <th class="p-1 text-right"><?php echo isset($isr_amount) ? number_format($isr_amount) : 0 ?></th>
                            </tr>
                        <?php endif; ?>
                        <?php if ($total_supplier_discount !== 0): ?>
                            <tr>
                                <th class="p-1 text-right" colspan="4">DESCUENTO (<?php echo isset($total_supplier_discount) ? $total_supplier_discount : 0 ?>%)</th>
                                <th class="p-1 text-right"><?php echo isset($discount_amount) ? number_format($discount_amount) : 0 ?></th>
                            </tr>
                        <?php endif; ?>
                        <?php if ($isr_iva !== '0'): ?>
                            <tr>
                                <th class="p-1 text-right" colspan="4">ISR IVA (<?php echo isset($isr_iva) ? $isr_iva : 0 ?>%)</th>
                                <th class="p-1 text-right"><?php echo  isset($isr_iva_amount) ? number_format($isr_iva_amount) : 0 ?></th>
                            </tr>
                        <?php endif; ?>
                        <tr>
                            <th class="p-1 text-right" colspan="4">Total</th>
                            <th class="p-1 text-right" id="total"><?php echo  isset($iva_amount) ? $currency ." ". number_format($sub_total + $iva_amount + $isr_amount - $discount_amount + $isr_iva_amount) : 0 ?></th>
                        </tr>
                        <tr>
                            <th class="p-1 text-left" colspan="4">COTIZACIÓN <?php echo " " . $po_no . " &nbsp&nbsp&nbsp&nbsp" . date("Y/m/d", strtotime($date_created)) ?> </th>
                        </tr>
                    </tfoot>
                </table>
                <div class="row">
                    <div class=" col-12 d-flex">
                        <p  class="m-0"><b>SOLICITÓ</b></p>
                        <p class="ml-3"><b><?php echo $author_name ?></b></p>
                    </div>
                   
                    <div class="col-6">
                        <div class="d-flex">
                            <b>Dir. Planta:&nbsp&nbsp </b> <p><?php echo isset($adress) ? $adress : '' ?></p>
                        </div>
                        <div class="d-flex">
                            <b>Dir. Oficinas:&nbsp&nbsp </b> <p><?php echo isset($branch_office) ? $branch_office : '' ?></p>
                        </div>
                    </div>
                    <div class="col-12 ">
                        <div class="d-flex border align-items-center p-2">
                            <b>ENVIAR FACTURA AL CORREO A   : 
                        <p class="mb-0">   cuentasporpagar@alvartispharma.com.mx  </p> </b>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<table class="d-none" id="item-clone">
    <tr class="po-item" data-id="">
        <td class="align-middle p-1 text-center">
            <button class="btn btn-sm btn-danger py-0" type="button" onclick="rem_item($(this))"><i class="fa fa-times"></i></button>
        </td>
        <td class="align-middle p-0 text-center">
            <input type="number" class="text-center w-100 border-0" step="any" name="qty[]"/>
        </td>
        <td class="align-middle p-1">
            <input type="text" class="text-center w-100 border-0" name="unit[]"/>
        </td>
        <td class="align-middle p-1">
            <input type="hidden" name="item_id[]">
            <input type="text" class="text-center w-100 border-0 item_id" required/>
        </td>
        <td class="align-middle p-1 item-description"></td>
        <td class="align-middle p-1">
            <input type="number" step="any" class="text-right w-100 border-0" name="unit_price[]" value="0"/>
        </td>
        <td class="align-middle p-1 text-right total-price">0</td>
    </tr>
</table>
<script>
    $(function () {
        $('#print').click(function (e) {
            e.preventDefault();
            start_loader();
            var _h = $('head').clone()
            var _p = $('#out_print').clone()
            var _el = $('<div>')
            _p.find('thead th').attr('style', 'color:black !important')
            _el.append(_h)
            _el.append(_p)

            var nw = window.open("", "", "width=1200,height=950")
            nw.document.write(_el.html())
            nw.document.close()
            setTimeout(() => {
                nw.print()
                setTimeout(() => {
                    end_loader();
                    nw.close()
                }, 300);
            }, 200);
        })
    })
</script>