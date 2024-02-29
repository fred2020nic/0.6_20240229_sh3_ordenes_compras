<?php
if (isset($_GET['id']) && $_GET['id'] > 0) {
    $qry = $conn->query("SELECT * from `po_list` where id = '{$_GET['id']}' ");
    if ($qry->num_rows > 0) {
        foreach ($qry->fetch_assoc() as $k => $v) {
            $$k = $v;
        }
    }
}
$pr_no = '';

do {
    $pr_no = "PO-" . (sprintf("%'.011d", mt_rand(1, 999999999999)));

    $qry = $conn->query("SELECT COUNT(*) as count FROM po_list WHERE po_no = '$pr_no'");
    $result = $qry->fetch_assoc();
    $exists = $result['count'];
} while ($exists > 0);
$supplierNames = array(); // Crear un array vacío para almacenar los nombres

if (isset($_GET['id']) && $_GET['id'] > 0) {
    $qry = $conn->query("SELECT p.*, s.name, oi.po_id,  oi.supplier_id FROM `po_list` p LEFT JOIN order_items oi ON p.id = oi.po_id LEFT JOIN supplier_list s ON oi.supplier_id = s.id WHERE p.id = '{$_GET['id']}'");

    while ($row = $qry->fetch_assoc()) {
        if (!in_array($row['name'], $supplierNames) && $row['name'] != null) { // Esto evita nombres duplicados y nulos
            $supplierNames[] = $row['name'];
        }
    }
}
$allNames = implode(', ', $supplierNames);
?>
<style>
    .item-supplier, .item_req_no {
        width: 100%;
        height: 37px;
        background: #e9ecef;
        border-radius: 4px;
        border: 1px solid #ced4da;
        display: flex;
        align-items: center;
        padding-left: 10px;
    }
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
        <h3 class="card-title"><?php echo isset($id) ? "Actualizar los detalles de la orden de compra" : "Nueva orden de compra" ?> </h3>
    </div>
    <div class="card-body">
        <form action="" id="po-form">
            <input type="hidden" name ="id" value="<?php echo isset($id) ? $id : '' ?>">
            <div class="row">
                <div class="col-md-4 form-group">
                    <label for="supplier_id">Fecha creación</label><br>
                    <?php $currentDate = date("Y-m-d"); ?>
                    <input type="date" id="date_created" class="w-100" name="date_created" value="<?php echo isset($date_created) ? $date_created : $currentDate ?>">
                </div>
                <div class="col-md-4 form-group">
                    <label for="order_status">Orden de compra</label>
                    <select name="order_status" id="order_id" class="custom-select custom-select-sm rounded-0">
                        <option value="1" <?php echo (isset($order_status) && ($order_status == 1)) ? 'selected' : ''; ?>>Orden autorizada</option>
                        <option value="2" <?php echo (isset($order_status) && ($order_status == 2)) ? 'selected' : ''; ?>>Orden no autorizada</option>
                        <option value="0" <?php echo (isset($order_status) && ($order_status == 0)) ? 'selected' : ''; ?>>Orden cancelada</option>
                    </select>
                </div>
                <div class="col-md-4 form-group">
                    <label for="po_no">Requisición asignada:<span class="po_err_msg text-danger"></span></label>
                    <select name="req_id" id="req_id" class="custom-select custom-select-sm rounded-0 select2">
                        <option value="" disabled <?php echo!isset($current_pr_no) ? "selected" : '' ?>></option>
                        <?php
                        $supplier_qry = $conn->query("SELECT * FROM `req_list` order by `pr_no` asc");
                        while ($row = $supplier_qry->fetch_assoc()):
                            ?>
                            <option value="<?php echo $row['id'] ?>" <?php echo (isset($row['pr_no']) && isset($req_id) && $row['id'] == $req_id) ? 'selected' : '' ?>><?php echo isset($row['pr_no']) ? $row['pr_no'] : '' ?></option>
                        <?php endwhile; ?>
                    </select>

                </div>

            </div>
            <div class="row">
                <div class="col-md-4 form-group">
                    <label for="po_no">N° Cotización<span class="po_err_msg text-danger"></span></label>
                    <div class="item_req_no">
                        <input type="hidden" class="form-control form-control-sm rounded-0" id="po_no" name="po_no" value="<?php echo isset($po_no) ? $po_no : $pr_no ?>">
                        <?php echo isset($po_no) ? $po_no : $pr_no ?>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="row" id="supplier_content">
                        <div class="col-md-12 form-group">
                            <!--                            <label for="supplier_id">Proveedor(es)</label>
                                                        <div class="item_id item-supplier"><?php //echo $allNames   ?></div>-->
                            <div class="col-md-12 form-group">
                                <label for="supplier_id">Proveedor(es)</label>
                                <select name="supplier_id" id="supplier_id" class="custom-select custom-select-sm rounded-0 select2 item-supplier" data-tags="true">
                                    <option value="" disabled <?php echo!isset($supplier) ? "selected" : '' ?>></option>
                                    <?php
                                    $supplier_qry = $conn->query("SELECT * FROM `supplier_list` order by `name` asc");
                                    while ($row = $supplier_qry->fetch_assoc()):
                                        ?>
                                        <option value="<?php echo $row['id'] ?>" <?php echo (isset($row['supplier_list']) && isset($supplier_id) && $row['id'] == $supplier_id) ? 'selected' : '' ?>><?php echo isset($row['name']) ? $row['name'] : '' ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>


                        </div>
                    </div>
                </div>
                <div class="col-md-4 form-group">
                    <label for="way_pay">Dirección <span class="po_err_msg text-danger"></span></label>
                    <input type="text" class="form-control form-control-sm rounded-0" id="adress" name="adress" value="<?php echo isset($adress) ? $adress : '' ?>">
                </div>

            </div>
            <div class="row">
                <div class="col-md-4 form-group">
                    <label for="po_no">Departamento que solicita <span class="po_err_msg text-danger"></span></label>
                    <input type="text" class="form-control form-control-sm rounded-0" id="department" name="department" value="<?php echo isset($department) ? $department : '' ?>">
                </div>
                <div class="col-md-4 form-group">
                    <label for="po_no">Condiciones de pago <span class="po_err_msg text-danger"></span></label>
                    <input type="text" step="any" name="way_pay" class="text-left w-100" value="<?php echo isset($way_pay) ? $way_pay : '' ?>">
                </div>
                <div class="col-md-4 form-group">
                    <label for="po_no">Folio <span class="po_err_msg text-danger"></span></label>
                    <input type="text" class="form-control form-control-sm rounded-0" id="invoice" name="invoice" value="<?php echo isset($invoice) ? $invoice : '' ?>">
                </div>
                <div class="col-md-4 form-group">
                            <!--<label for="seller_name">Vendedor <span class="po_err_msg text-danger"></span></label>-->
                            <input type="text" class="form-control form-control-sm rounded-0" id="seller_name" name="seller_name" value="<?php echo isset($seller_name) ? $seller_name : '' ?>">
                        </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-striped table-bordered" id="item-list">
                        <colgroup>
                            <col width="5%">
                            <col width="5%">
                            <col width="10%">
                            <col width="20%">
                            <col width="30%">
                            <col width="15%">
                            <col width="15%">
                        </colgroup>
                        <thead>
                            <tr class="bg-navy disabled">
                                <th class="px-1 py-1 text-center"></th>
                                <th class="px-1 py-1 text-center">N°</th>
                                <th class="px-1 py-1 text-center">Cantidad</th>
                                <th class="px-1 py-1 text-center">Unidad</th>
                                <th class="px-1 py-1 text-center">Descripción</th>
                                <th class="px-1 py-1 text-center">Precio unitario</th>
                                <th class="px-1 py-1 text-center">subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 1;
                            if (isset($id)):
                                $order_items_qry = $conn->query("SELECT o.*,i.product_key,i.unit, i.description, s.name,s.discount, i.supplier_id FROM `order_items` o inner join item_list i on o.item_id = i.id LEFT JOIN supplier_list s ON s.id = o.supplier_id  where o.`po_id` = '$id' ");
                                echo $conn->error;
                                while ($row = $order_items_qry->fetch_assoc()):
                                    ?>
                                    <tr class="po-item" data-id="" data-supplier='<?= $row['name'] ?>' data-discount='<?= $row['discount'] ?>'>
                                <input type="hidden" name="supplier_id[]" id="supplier_id" value="<?php echo $row['supplier_id'] ?>">
                                <td class="align-middle p-1 text-center">
                                    <button class="btn btn-sm btn-danger py-0" type="button" onclick="rem_item($(this))"><i class="fa fa-times"></i></button>
                                </td>
                                <td class="sequential-number align-middle p-1 text-center"> <?php echo $i++ ?></td>
                                <td class="align-middle p-0 text-center">
                                    <input type="number" class="text-center w-100 border-0" step="any" name="qty[]" value="<?php echo $row['quantity'] ?>"/>
                                </td>
                                <td class="align-middle p-1">
                                    <input type="text" class="text-center w-100 border-0" name="item-unit[]" value="<?php echo $row['unit'] ?>"/>
                                </td>
                                <td class="align-middle p-1">
                                    <input type="hidden" name="item_id[]" value="<?php echo $row['item_id'] ?>">
                                    <input type="text" class="text-center w-100 border-0 item_id" value="<?php echo $row['product_key'] ?>" required/>
                                </td>
                                <td class="align-middle p-1">
                                    <input type="number" step="any" class="text-right w-100 border-0" name="unit_price[]"  value="<?php echo ($row['unit_price']) ?>"/>
                                </td>
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
                                <th class="p-1 text-right" colspan="6"><span><button class="btn btn btn-sm btn-flat btn-primary py-0 mx-1" type="button" id="add_row">Agregar Fila</button></span> Sub Total</th>
                                <th class="p-1 text-right" id="sub_total">0</th>
                            </tr>
                            <tr>
                                <th class="p-1 text-right" colspan="6">Iva(%)
                                    <input type="number" step="any" name="iva_percentage" class="border-light text-right" value="<?php echo isset($iva_percentage) ? $iva_percentage : 16 ?>">
                                </th>
                                <th class="p-1"><input type="text" class="w-100 border-0 text-right" readonly value="<?php echo isset($iva_amount) ? $iva_amount : 0 ?>" name="iva_amount"></th>
                            </tr>
                            <tr>
                                <th class="p-1 text-right" colspan="6">Descuento (%)
                                    <input type="number" step="any" name="total_supplier_discount" class="border-light text-right" value="<?php echo isset($total_supplier_discount) ? $total_supplier_discount : 0 ?>">
                                </th>
                                <th class="p-1"><input type="text" class="w-100 border-0 text-right" readonly value="<?php echo isset($discount_amount) ? $discount_amount : 0 ?>" name="discount_amount"></th>
                            </tr>
                            <tr>
                                <th class="p-1 text-right" colspan="6">ISR (%)
                                    <input type="number" step="any" name="isr_percentage" class="border-light text-right" value="<?php echo isset($isr_percentage) ? $isr_percentage : 0 ?>">
                                </th>
                                <th class="p-1"><input type="text" class="w-100 border-0 text-right" readonly value="<?php echo isset($isr_amount) ? $isr_amount : 0 ?>" name="isr_amount"></th>
                            </tr>
                            <tr>
                                <th class="p-1 text-right" colspan="6">Iva ISR (%)
                                    <input type="number" step="any" name="isr_iva" class="border-light text-right" value="<?php echo isset($isr_iva) ? $isr_iva : 0 ?>">
                                </th>
                                <th class="p-1"><input type="text" class="w-100 border-0 text-right" name="isr_iva_amount" readonly value="<?php echo isset($isr_iva_amount) ? $isr_iva_amount : 0 ?>" ></th>
                            </tr>
                            <tr>
                                <th class="p-1 text-right" colspan="6">Total</th>
                                <th class="p-1 text-right" id="total">0</th>
                            </tr>
                            <tr>
                                <th class="p-1 text-right" colspan="6">Moneda de cambio</th>
                                <th class="p-1 text-right">
                                    <select name="currency" id="currency">
                                        <option value="" disabled selected>Seleccione su moneda de cambio</option>
                                        <option value="USD" <?php echo (isset($currency) && ($currency == 'USD')) ? 'selected' : '' ?>>USD</option>
                                        <option value="MX" <?php echo (isset($currency) && ($currency == 'MX')) ? 'selected' : '' ?>>MX</option>
                                        <option value="EU" <?php echo (isset($currency) && ($currency == 'EU')) ? 'selected' : '' ?>>EU</option>
                                    </select>
                                </th>


                            </tr>

                        </tfoot>
                    </table>
                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label for="po_no">Solicitó <span class="po_err_msg text-danger"></span></label>
                            <input type="text" class="form-control form-control-sm rounded-0" id="author_name" name="author_name" value="<?php echo isset($author_name) ? $author_name : '' ?>">
                        </div>
                        
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="notes" class="control-label">Notas</label>
                            <textarea name="notes" id="notes" cols="10" rows="4" class="form-control rounded-0"><?php echo isset($notes) ? $notes : '' ?></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="card-footer">
        <button class="btn btn-flat btn-primary" form="po-form">Guardar</button>
        <a class="btn btn-flat btn-default" href="?page=purchase_orders">Cancelar</a>
    </div>
</div>
<table class="d-none" id="item-clone">
    <tr class="po-item" data-id="">
        <td class="align-middle p-1 text-center">
            <input type="hidden" name="supplier_id[]" value="<?php echo isset($row['supplier_id']) ? $row['supplier_id'] : '' ?>">
            <button class="btn btn-sm btn-danger py-0" type="button" onclick="rem_item($(this))"><i class="fa fa-times"></i></button>
        </td>
        <td class="sequential-number align-middle p-1 text-center"></td>
        <td class="align-middle p-0 text-center">
            <input type="number" class="text-center w-100 border-0" step="any" name="qty[]"/>
        </td>
        <td class="align-middle p-1">
            <input type="text" class="text-center w-100 border-0" name="item-unit[]"/>
        </td>
        <td class="align-middle p-1">
            <input type="hidden" name="item_id[]">
            <input type="text" class="text-center w-100 border-0 item_id" required/>
        </td>
        <td class="align-middle p-1">
            <input type="number" step="any" class="text-right w-100 border-0" name="unit_price[]" value="0"/>
        </td>
        <td class="align-middle p-1 text-right total-price">0</td>
    </tr>
</table>
<script>
    function rem_item(_this) {
        var supplierToRemove = _this.closest('tr').data('supplier');
        var discountValueToRemove = _this.closest('tr').data('discount'); // Recupera el descuento para este producto específico
        console.log("Proveedor a eliminar:", supplierToRemove);
        _this.closest('tr').remove();

        var hasOtherRowsWithSameSupplier = false;
        $('#item-list tr').each(function () {
            if ($(this).data('supplier') === supplierToRemove) {
                hasOtherRowsWithSameSupplier = true;
                return false;
            }
        });

//        if (!hasOtherRowsWithSameSupplier) {
//            subtractValueFromInput('input[name="total_supplier_discount"]', discountValueToRemove);
//
//            var suppliers = $('#supplier_content .item-supplier').text().split(', ');
//            var indexToRemove = suppliers.indexOf(supplierToRemove);
//            if (indexToRemove !== -1) {
//                suppliers.splice(indexToRemove, 1);
//                $('#supplier_content .item-supplier').text(suppliers.join(', '));
//            }
//        }

        // Actualizar la numeración de las filas
        var table = $('#item-list');
        updateSequentialNumbers(table);
    }

    function subtractValueFromInput(selector, valueToSubtract) {
        // 1. Obtén el valor actual del input.
        let currentValue = parseFloat($(selector).val()) || 0;
        console.log('Valor actual:', currentValue); // Debug

        // 2. Resta el valor a sustraer del valor actual.
        let newValue = currentValue - parseFloat(valueToSubtract);
        console.log('Valor a restar:', valueToSubtract); // Debug
        console.log('Nuevo valor:', newValue); // Debug

        // 3. Establece el nuevo valor en el input.
        $(selector).val(newValue);
    }



    function updateSequentialNumbers(table) {
        // Renumerar los elementos con clase 'sequential-number' después de eliminar una fila
        $(table).find('.sequential-number').each(function (index, element) {
            $(element).text(index + 1);
        });
        calculate();
    }
    function calculate() {
        var _total = 0
        $('.po-item').each(function () {
            var qty = $(this).find("[name='qty[]']").val()
            var unit_price = $(this).find("[name='unit_price[]']").val();
            var row_total = 0;
            if (qty > 0 && unit_price > 0) {
                row_total = parseFloat(qty) * parseFloat(unit_price);
            }
            $(this).find('.total-price').text(parseFloat(row_total).toLocaleString('en-US'));
        })
        $('.total-price').each(function () {
            var _price = $(this).text();
            _price = _price.replace(/\,/gi, '');
            _total += parseFloat(_price)
        })
        var total_supplier_discount_perc = 0;
        var iva_perc = 0;

        if ($('[name="iva_percentage"]').val() > 0) {
            iva_perc = $('[name="iva_percentage"]').val();
        }
        if ($('[name="total_supplier_discount"]').val() > 0) {
            total_supplier_discount_perc = $('[name="total_supplier_discount"]').val();
        }
        var isr_percentage = 0;
        if ($('[name="isr_percentage"]').val() > 0) {
            isr_percentage = $('[name="isr_percentage"]').val();
        }
        var isr_iva = 0;
        if ($('[name="isr_iva"]').val() > 0) {
            isr_iva = $('[name="isr_iva"]').val();
        }
        var iva_amount = _total * (iva_perc / 100);
        var isr_amount = _total * (isr_percentage / 100);
        var isr_iva_amount = _total * (isr_iva / 100);
        var total_supplier_amount = _total * (total_supplier_discount_perc / 100);
        $('[name="iva_amount"]').val(parseFloat(iva_amount).toLocaleString("en-US"))
        $('[name="isr_amount"]').val(parseFloat(isr_amount).toLocaleString("en-US"))
        $('[name="isr_iva_amount"]').val(parseFloat(isr_iva_amount).toLocaleString("en-US"));
        $('[name="discount_amount"]').val(parseFloat(total_supplier_amount).toLocaleString("en-US"))
        $('#sub_total').text(parseFloat(_total).toLocaleString("en-US"))
        $('#total').text(parseFloat(_total + iva_amount - total_supplier_amount + isr_amount + isr_iva_amount).toLocaleString("en-US"))
    }

    function _autocomplete(_item) {
        _item.find('.item_id').autocomplete({
            source: function (request, response) {
                $.ajax({
                    url: _base_url_ + "classes/Master.php?f=search_items",
                    method: 'POST',
                    data: {q: request.term},
                    dataType: 'json',
                    error: err => {
                        console.log(err)
                    },
                    success: function (resp) {
                        response(resp)
                    }
                })
            },
            select: function (event, ui) {
                console.log(ui)
                _item.find('input[name="item_id[]"]').val(ui.item.id)
                _item.find('.item-description').text(ui.item.description),
                        _item.find('input[name="item_id[]"]').val(ui.item.item_id);
                _item.find('input[name="item-unit[]"]').val(ui.item.unit);
                _item.find('input[name="qty[]"]').val(ui.item.quantity);
                _item.find('input[name="supplier_id[]"]').val(ui.item.supplier_id);
                _item.data('supplier', ui.item.supplier);
                _item.data('discount', ui.item.discount);
                var currentSuppliers = $('#supplier_content .item-supplier').text().split(', ').filter(Boolean); // filter(Boolean) para eliminar valores vacíos

                // Verifica si el proveedor seleccionado ya está en la lista
//                if (!currentSuppliers.includes(ui.item.supplier)) {
//                    addValueToInput('input[name="total_supplier_discount"]', ui.item.discount);
//                    // Si no está en la lista, añádelo
//                    currentSuppliers.push(ui.item.supplier);
//                    $('#supplier_content .item-supplier').text(currentSuppliers.join(', '));
//                }
            }
        })
    }
    function addValueToInput(selector, valueToAdd) {
        let currentValue = parseFloat($(selector).val()) || 0; // Si el valor no es un número, se usará 0
        let newValue = currentValue + parseFloat(valueToAdd);

        // 3. Establece el nuevo valor en el input.
        $(selector).val(newValue);
    }
    function _get_req_info() {
        $('#req_id').change(function () {
            const selectedValue = $(this).val();

            if (selectedValue) {
                $.ajax({
                    url: _base_url_ + 'classes/Master.php?f=get_req_info',
                    type: 'POST',
                    data: {q: selectedValue},
                    dataType: 'json',
                    success: function (data) {
                        // Limpiamos las filas existentes (opcional)
                        $('#item-list tbody .po-item').remove();
                        let suppliersList = [];

                        data.forEach((item, index) => {
                            // Clonamos la fila de referencia
                            let clone = $('#item-clone .po-item').clone();

                            // Asigna total_supplier_discount e isr_percentage solo si es la primera fila de datos
                            if (index === 0) {
//                                $('input[name="total_supplier_discount"]').val(item.total_supplier_discount);
                                $('input[name="isr_percentage"]').val(item.isr_percentage);
                            }

                            // Llenamos los datos en la fila clonada
                            clone.attr('data-id', index + 1);
                            clone.find('.number').text(index + 1);
                            clone.find('input[name="qty[]"]').val(item.quantity);
                            clone.find('input[name="item-unit[]"]').val(item.unit);
                            clone.find('input[name="item_id[]"]').val(item.item_id);
                            clone.find('.item_id').val(item.description);
                            clone.find('input[name="unit_price[]"]').val(item.unit_price);
                            clone.find('input[name="supplier_id[]"]').val(item.supplier_id);
                            // Calcula el subtotal y actualiza en la fila
                            let subtotal = item.quantity * item.unit_price;
                            clone.find('.total-price').text(subtotal.toFixed(2));

                            clone.find('input[name="supplier_id[]"]').val(item.supplier_id);
                            clone.data('supplier', item.supplier);
                            clone.data('discount', item.discount); // Almacena el descuento en el atributo data

                            if (!suppliersList.includes(item.supplier)) {
                                suppliersList.push(item.supplier);
                            }

                            $('#item-list tbody').append(clone);
                        });
                        if (data.length > 0 && data[0].hasOwnProperty('way_pay')) {
                            $('input[name="way_pay"]').val(data[0].way_pay);
                        }
                        if (data.length > 0 && data[0].hasOwnProperty('department')) {
                            $('input[name="department"]').val(data[0].department);
                        }
                        if (data.length > 0 && data[0].hasOwnProperty('invoice')) {
                            $('input[name="invoice"]').val(data[0].invoice);
                        }
//                        if (data.length > 0 && data[0].hasOwnProperty('author_name')) {
//                            $('input[name="author_name"]').val(data[0].author_name);
//                        }
                        // Actualiza el contenido del proveedor
//                        $('#supplier_content .item-supplier').text(suppliersList.join(', '));

                        calculate();

                    },
                    error: function (err) {
                        console.error("Hubo un error:", err);
                    }
                });
            }
        });
    }

   $('#supplier_id').on('change', function() {
        var supplierId = $(this).val();

        if (supplierId) {
            $.ajax({
                url: _base_url_ + 'classes/Master.php?f=get_supplier_discount',
                type: 'POST',
                data: {supplier_id: supplierId},
                dataType: 'json',
                success: function(response) {
                    if (response.success && response.discount) {
                        $('input[name="total_supplier_discount"]').val(response.discount);
                        calculate()
                    } else {
                        console.error("Error al obtener el descuento:", response.message);
                    }
                },
                error: function(err) {
                    console.error("Hubo un error:", err);
                }
            });
        }
    });
    $(document).ready(function () {
        $('#supplier_id').select2({
    tags: true, // Esto permite agregar valores que no están en las opciones originales
    tokenSeparators: [','] // Esto permite crear nuevas opciones (etiquetas) al escribir una coma
});
        _get_req_info();
        $('#add_row').click(function () {
            var tr = $('#item-clone tr').clone()
            $('#item-list tbody').append(tr)
            _autocomplete(tr)
            tr.find('[name="qty[]"],[name="unit_price[]"]').on('input keypress', function (e) {
                calculate()
            });
            var table = $('#item-list');
            updateSequentialNumbers(table);
            $('#item-list tfoot').find('[name="total_supplier_discount"],[name="iva_percentage"],[name="isr_percentage"],[name="isr_iva"]').on('input keypress', function (e) {
                calculate()
            })
        })
        if ($('#item-list .po-item').length > 0) {
            $('#item-list .po-item').each(function () {
                var tr = $(this)
                _autocomplete(tr)
                tr.find('[name="qty[]"],[name="unit_price[]"]').on('input keypress', function (e) {
                    calculate()
                })
                $('#item-list tfoot').find('[name="total_supplier_discount"],[name="iva_percentage"],[name="isr_percentage"],[name="isr_iva"]').on('input keypress', function (e) {
                    calculate()
                })
                tr.find('[name="qty[]"],[name="unit_price[]"]').trigger('keypress')
            })
        } else {
            $('#add_row').trigger('click')
        }
        $('.select2').select2({placeholder: "Porfavor selecciona aquí", width: "relative"})
        $('#po-form').submit(function (e) {

            e.preventDefault();
            console.log(new FormData($(this)[0]));
//            return;
            var _this = $(this)
            $('.err-msg').remove();
            $('[name="po_no"]').removeClass('border-danger')
            if ($('#item-list .po-item').length <= 0) {
                alert_toast(" Agregue al menos 1 elemento en la lista.", 'warning')
                return false;
            }
            start_loader();

            $.ajax({
                url: _base_url_ + "classes/Master.php?f=save_po",
                data: new FormData($(this)[0]),
                cache: false,
                contentType: false,
                processData: false,
                method: 'POST',
                type: 'POST',
                dataType: 'json',
                error: err => {
                    console.log(err)
                    alert_toast("Ocurrió un error", 'error');
                    end_loader();
                },
                success: function (resp) {
                    if (typeof resp == 'object' && resp.status == 'success') {
                        location.href = "./?page=purchase_orders/view_po&id=" + resp.id;
                    } else if ((resp.status == 'failed' || resp.status == 'po_failed') && !!resp.msg) {
                        var el = $('<div>')
                        el.addClass("alert alert-danger err-msg").text(resp.msg)
                        _this.prepend(el)
                        el.show('slow')
                        $("html, body").animate({scrollTop: 0}, "fast");
                        end_loader()
                        if (resp.status == 'po_failed') {
                            $('[name="po_no"]').addClass('border-danger').focus()
                        }
                    } else {
                        alert_toast("Ocurrió un error", 'error');
                        end_loader();
                        console.log(resp)
                    }
                }
            })
        })


    })


</script>