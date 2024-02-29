<?php if ($_settings->chk_flashdata('success')): ?>
    <script>
        alert_toast("<?php echo $_settings->flashdata('success') ?>", 'success')
    </script>
<?php endif; ?>
<?php
if (isset($_GET['id']) && $_GET['id'] > 0) {
    $qry = $conn->query("SELECT r.*, ri.supplier_id, sup.name from `req_list` r LEFT JOIN req_items ri ON ri.req_id = r.id LEFT JOIN supplier_list sup ON ri.supplier_id = sup.id where r.id = '{$_GET['id']}' ");

    if ($qry->num_rows > 0) {
        $results = [];
        while ($row = $qry->fetch_assoc()) {
            $results[] = $row;
        }
        foreach ($results as $result) {
            foreach ($result as $k => $v) {
                $$k = $v;
            }
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
        <h3 class="card-title"><?php echo isset($id) ? "Actualizar detalles de Requisiciones de compra" : "New Purchase Order" ?> </h3>
        <div class="card-tools">
            <button class="btn btn-sm btn-flat btn-success" id="print" type="button"><i class="fa fa-print"></i> Imprimir</button>
            <a class="btn btn-sm btn-flat btn-primary" href="?page=requisitions/manage_req&id=<?php echo $id ?>">Editar</a>
            <a class="btn btn-sm btn-flat btn-default" href="?page=purchase_orders">Volver</a>
        </div>
    </div>
    <div class="card-body" id="out_print">
        <div class="row">
            <div class="col-3">
                <center><img src="<?php echo validate_image($_settings->info('logo')) ?>" alt="" height="200px"></center>
            </div>
            <div class="col-9 d-flex align-items-center">
                <div class="text-center">
                    <h2 class="m-0"><b><?php echo $_settings->info('company_name') ?></b></h2>
                    <h2 class="m-0"><b>REQUISICIÓN DE COMPRA</b></h2>
              
                </div>
            </div>
        </div>
        <div class="row mb-2">
            <table class="table table-striped table-bordered border" id="item-list2">
                <!-- Primera fila con 4 columnas -->
                <tr class="border border-secondary">
                    <th class="disabled" style="width: 25% !important;" colspan="1">Fecha:</th>
                    <td class="bg-white text-center" colspan="1">
                        <p><?php echo isset($date_created) ? $date_created : '' ?></p>
                    </td>
                    <th class="disabled" colspan="1">Elabora:</th>
                    <td class="bg-white text-center" colspan="3">
                        <p><?php echo isset($author_name) ? $author_name : '' ?></p>
                    </td>
                </tr>
                <!-- Segunda fila con 6 columnas -->
                <tr>
                    <th class="disabled" colspan="1">Departamento que solicita:</th>
                    <td class="bg-white text-center" colspan="5">
                        <p><?php echo isset($department) ? $department : '' ?></p>
                    </td>
                </tr>
                <!-- Tercera fila con 6 columnas -->
                <tr>
                    <th class="disabled" colspan="1">Proveedor(es):</th>
                    <td class="bg-white text-center" colspan="1">
                        <?php
                        $sup_qry = $conn->query("SELECT * FROM supplier_list where id = '{$supplier_id}'");
                        $supplier = $sup_qry->fetch_array();
                        ?>
                        <?php
                        foreach ($results as $result) {
                            echo $result['name'] . ', ';
                        }
                        ?>
                    </td>
                    <th class="disabled" colspan="1">Credito:</th>
                    <td class="bg-white text-center" colspan="1">
                        <p><?php echo (isset($credit_status) && $credit_status == '1') ? 'x' : '' ?></p>
                    </td>
                    <th class="disabled" colspan="1">Tipo de pedido:</th>
                    <td class="bg-white text-center" colspan="1">
                        <p><?php echo isset($type_order) ? $type_order : '' ?></p>
                    </td>
                    <th class="disabled" colspan="1">Dias Credito:</th>
                    <td class="bg-white text-center" colspan="1">
                        <p><?php echo isset($dias_c) ? $dias_c : '' ?></p>
                    </td>
                </tr>
                <tr>
                    <th class="disabled" style="width: 25% !important;" colspan="1">N° Cotización:</th>
                    <td class="bg-white text-center" colspan="1">
                        <p><?php echo isset($pr_no) ? $pr_no : '' ?></p>
                    </td>
                    <th class="disabled" colspan="1">Folio:</th>
                    <td class="bg-white text-center" colspan="3">
                        <p><?php echo isset($invoice) ? $invoice : '' ?></p>
                    </td>
                </tr>
            </table>
        </div>
        <div class="row">
            <div class="col-md-12">
                <table class="table table-striped table-bordered mb-0" id="item-list">
                    <colgroup>
                        <col width="10%">
                        <col width="10%">
                        <col width="20%">
                        <col width="30%">
                        <col width="15%">
                        <col width="15%">
                    </colgroup>
                    <thead>
                        <tr class="bg-navy disabled" style="">
                            <th class="bg-navy disabled text-light px-1 py-1 text-center">N°</th>
                            <th class="bg-navy disabled text-light px-1 py-1 text-center">Cantidad</th>
                            <th class="bg-navy disabled text-light px-1 py-1 text-center">Unidad</th>
                            <th class="bg-navy disabled text-light px-1 py-1 text-center">Descripción</th>
                            <th class="bg-navy disabled text-light px-1 py-1 text-center">Precio Unitario</th>
                            <th class="bg-navy disabled text-light px-1 py-1 text-center">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i=1;
                        if (isset($id)):
                            $order_items_qry = $conn->query("SELECT ri.*,i.product_key, i.description , i.unit FROM `req_items` ri inner join item_list i on ri.item_id = i.id where ri.`req_id` = '$id' ");
                            $sub_total = 0;
                            while ($row = $order_items_qry->fetch_assoc()):
                                $sub_total += ($row['quantity'] * $row['unit_price']);
                                ?>
                                <tr class="po-item" data-id="">
                                    <td class="align-middle p-0 text-center"><?php echo $i++ ?></td>
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
                            <th class="p-1 text-right" colspan="5">Sub Total</th>
                            <th class="p-1 text-right" id="sub_total"><?php echo number_format($sub_total) ?></th>
                        </tr>
                        <tr>
                            <th class="p-1 text-right" colspan="5">Descuento (<?php echo isset($iva_percentage) ? $iva_percentage : 0 ?>%)
                            </th>
                            <th class="p-1 text-right"><?php echo isset($iva_amount) ? number_format($iva_amount) : 0 ?></th>
                        </tr>
                        <tr>
                            <th class="p-1 text-right" colspan="5">Impuestos Incluidos (<?php echo isset($isr_amount) ? $isr_amount : 0 ?>%)</th>
                            <th class="p-1 text-right"><?php echo isset($isr_amount) ? number_format($isr_amount) : 0 ?></th>
                        </tr>
                        <tr>
                            <th class="p-1 text-right" colspan="5">Total</th>
                            <th class="p-1 text-right" id="total"><?php echo isset($iva_amount) ? number_format($sub_total + $iva_amount + $isr_amount) : 0 ?></th>
                        </tr>
                        </tr>
                    </tfoot>
                </table>
                <div class="row">
                    <div class="col-6 ">
                        <table class="table table-striped table-bordered border">
                            <!-- Primera fila con 4 columnas -->
                            <tr class="border border-secondary">
                                <th class="disabled" style="width: 25% !important;" colspan="1">Observaciones:</th>
                                <td class="bg-white text-center" colspan="1">
                                    <p><?php echo isset($observation) ? $observation : '' ?></p>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped table-bordered" id="item-list2">
                            <tr >
                                <th class="disabled">Forma de pago:</th>
                                <td>
                                    <?php echo isset($way_pay) ? $way_pay : '' ?>
                                </td>
                            </tr>
                            <tr>
                                <th class="disabled">Factura:</th>
                                <td>
                                    <?php echo isset($client_invoice) ? $client_invoice : '' ?>
                                </td>
                            </tr>
                            <tr>
                                <th class="disabled">Nombre:</th>
                                <td>
                                    <?php echo isset($client_name) ? $client_name : '' ?>
                                </td>
                            </tr>
                            <tr>
                                <th class="disabled">Banco:</th>
                                <td>
                                    <?php echo isset($bank) ? $bank : '' ?>
                                </td>
                            </tr>
                            <tr>
                                <th class="disabled">Clabe:</th>
                                <td>
                                    <?php echo isset($client_key) ? $client_key : '' ?>
                                </td>
                            </tr>
                            <tr>
                                <th class="disabled">N° cuenta:</th>
                                <td>
                                    <?php echo isset($client_account_num) ? $client_account_num : '' ?>
                                </td>
                            </tr>
                            <tr>
                                <th class="disabled">N° Tarjeta:</th>
                                <td>
                                    <?php echo isset($client_card_num) ? $client_card_num : '' ?>
                                </td>
                            </tr>
                            <tr>
                                <th class="disabled">Sucursal:</th>
                                <td>
                                    <?php echo isset($branch_office) ? $branch_office : '' ?>
                                </td>
                            </tr>
                            <tr>
                                <th class="disabled">Numero de contácto:</th>
                                <td>
                                    <?php echo isset($contact_client) ? $contact_client : '' ?>
                                </td>
                            </tr>
                            <tr>
                                <th class="disabled">Correo electrónico:</th>
                                <td>
                                    <?php echo isset($email_client) ? $email_client : '' ?>
                                </td>
                            </tr>

                        </table>
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
            }, 500);
        })
    })
</script>