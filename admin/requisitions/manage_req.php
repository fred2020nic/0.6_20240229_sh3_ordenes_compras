<?php
if (isset($_GET['id']) && $_GET['id'] > 0) {
    $qry = $conn->query("SELECT * from `req_list` where id = '{$_GET['id']}' ");
    if ($qry->num_rows > 0) {
        foreach ($qry->fetch_assoc() as $k => $v) {
            $$k = $v;
        }
    }
}
$req_no = '';

do {
    $req_no = "REQ-" . (sprintf("%'.011d", mt_rand(1, 999999999999)));

    $qry = $conn->query("SELECT COUNT(*) as count FROM req_list WHERE pr_no = '$req_no'");
    $result = $qry->fetch_assoc();
    $exists = $result['count'];
} while ($exists > 0);
$supplierNames = array(); // Crear un array vacío para almacenar los nombres

if (isset($_GET['id']) && $_GET['id'] > 0) {
    $qry = $conn->query("SELECT r.*, s.name, ri.req_id,  ri.supplier_id FROM `req_list` r LEFT JOIN req_items ri ON r.id = ri.req_id LEFT JOIN supplier_list s ON ri.supplier_id = s.id WHERE r.id = '{$_GET['id']}'");

    while ($row = $qry->fetch_assoc()) {
        if (!in_array($row['name'], $supplierNames) && $row['name'] != null) { // Esto evita nombres duplicados y nulos
            $supplierNames[] = $row['name'];
        }

        // Si necesitas extraer otros detalles del registro, hazlo aquí, pero cuidado con sobrescribir variables
    }
}

// Convertir el array a una cadena, separada por comas
$allNames = implode(', ', $supplierNames);
?>
<style>
    .add_pdf {
  background: #007bff;
  border: none;
  color: #fff;
  padding: 5px 23px;
}
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
    [name="isr_percentage"],[name="iva_percentage"]{
        width:5vw;
    }
</style>
<div class="card card-outline card-info">
    <div class="card-header">
        <h3 class="card-title"><?php echo isset($id) ? "Actualizar los detalles de la requisición de compra" : "Nueva requisición de compra" ?> </h3>
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
                    <label for="po_no">Elabora <span class="po_err_msg text-danger"></span></label>
                    <input type="text" class="form-control form-control-sm rounded-0" id="author_name" name="author_name" value="<?php echo isset($author_name) ? $author_name : '' ?>">
                </div>
                <div class="col-md-4 form-group">
                    <label for="po_no">Departamento que solicita <span class="po_err_msg text-danger"></span></label>
                    <input type="text" class="form-control form-control-sm rounded-0" id="department" name="department" value="<?php echo isset($department) ? $department : '' ?>">
                </div>
            </div>
            <div class="row" id="supplier_content">
                <div class="col-md-8 form-group">
                    <label for="supplier_id">Proveedor</label>
                    <div class="item_id item-supplier"><?php echo $allNames ?></div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 form-group">
                    <label for="credit_status" class="control-label">Credito</label>
                    <select name="credit_status" id="credit_status" class="form-control rounded-0" required>
                        <option value="0" <?php echo (isset($credit_status) && $credit_status == '0' && $credit_status !== '') ? 'selected' : '' ?>>No</option>
                        <option  value="1" <?php echo (isset($credit_status) && $credit_status == '1' && $credit_status !== '') ? 'selected' : '' ?>>Si</option>
                    </select>
                   
                    
                </div>
                 <label for="credit_status" class="control-label">Dias Credito</label>
                <input type="text" class="col-2 form-control form-control-sm rounded-0" id="dias_c" name="dias_c" value="<?php echo isset($dias_c) ? $dias_c : '' ?>">
                </div>
                
                <div class="col-md-4 form-group">
                    <label for="counted">Contado <span class="po_err_msg text-danger"></span></label>
                    <input type="text" class="form-control form-control-sm rounded-0" id="counted" name="counted" value="<?php echo isset($counted) ? $counted : '' ?>">
                </div>
                <div class="col-md-4 form-group">
                    <label for="way_pay">Tipo de pedido <span class="po_err_msg text-danger"></span></label>
                    <input type="text" class="form-control form-control-sm rounded-0" id="type_order" name="type_order" value="<?php echo isset($type_order) ? $type_order : '' ?>">
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 form-group">
                    <label for="po_no">N° Cotización<span class="po_err_msg text-danger"></span></label>
                    <div class="item_req_no">
                        <input type="hidden" class="form-control form-control-sm rounded-0" id="pr_no" name="pr_no" value="<?php echo isset($pr_no) ? $pr_no : $req_no ?>">
                        <?php echo isset($pr_no) ? $pr_no : $req_no ?>
                    </div>
                </div>
                <div class="col-md-6 form-group">
                    <label for="po_no"> Folio<span class="po_err_msg text-danger"></span></label>
                    <input type="text" class="form-control form-control-sm rounded-0" id="invoice" name="invoice" value="<?php echo isset($invoice) ? $invoice : '' ?>">
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
                                <th class="px-1 py-1 text-center">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 1;
                            if (isset($id)):
                                $order_items_qry = $conn->query("SELECT r.*,i.product_key, i.description, i.unit, s.name, i.supplier_id FROM `req_items` r inner join item_list i on r.item_id = i.id LEFT JOIN supplier_list s ON s.id = r.supplier_id where r.`req_id` = '$id' ");
                                echo $conn->error;
                                while ($row = $order_items_qry->fetch_assoc()):
                                    ?>
                                    <tr class="po-item" data-id="" data-supplier='<?= $row['name'] ?>'>
                                <input type="hidden" name="supplier_id[]" value="<?php echo $row['supplier_id'] ?>">
                                <td class="align-middle p-1 text-center">
                                    <button class="btn btn-sm btn-danger py-0" type="button"><i class="fa fa-times"></i></button>
                                </td>
                                <td class=" sequential-number align-middle p-1 text-center"> <?php echo $i++ ?></td>
                                <td class="align-middle p-0 text-center">
                                    <input type="number" class="text-center w-100 border-0" step="any" name="qty[]" value="<?php echo $row['quantity'] ?>"/>
                                </td>
                                <td class="align-middle p-1 ">
                                    <input type="text" class="text-center  w-100 border-0" step="any" name="item-unit[]" value="<?php echo $row['unit'] ?>"/></td>
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
                                <th class="p-1 text-right" colspan="6">Iva (%)
                                    <input type="number" step="any" name="iva_percentage" class="border-light text-right" value="<?php echo isset($iva_percentage) ? $iva_percentage : 16 ?>">
                                </th>
                                <th class="p-1"><input type="text" class="w-100 border-0 text-right" readonly value="<?php echo isset($iva_amount) ? $iva_amount : 0 ?>" name="iva_amount"></th>
                            </tr>
                            <tr>
                                <th class="p-1 text-right" colspan="6">ISR (%)
                                    <input type="number" step="any" name="isr_percentage" class="border-light text-right" value="<?php echo isset($isr_percentage) ? $isr_percentage : 0 ?>">
                                </th>
                                <th class="p-1"><input type="text" class="w-100 border-0 text-right" readonly value="<?php echo isset($isr_amount) ? $isr_amount : 0 ?>" name="isr_amount"></th>
                            </tr>
                            <tr>
                                <th class="p-1 text-right" colspan="6">Total</th>
                                <th class="p-1 text-right" id="total">0</th>
                            </tr>
                            </tr>
                        </tfoot>
                    </table>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="observation" class="control-label">Observaciones</label>
                            <textarea name="observation" id="observation" cols="10" rows="4" class="form-control rounded-0"><?php echo isset($observation) ? $observation : '' ?></textarea>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <h5 class="pl-2">Datos Bancarios: </h5>
                        <div class="col-md-12">
                            <table class="table table-striped table-bordered" id="item-list2">
                                <tr >
                                    <th class="bg-navy disabled">Forma de pago:</th>
                                    <td>
                                        <input type="text" step="any" name="way_pay" class="text-left w-100" value="<?php echo isset($way_pay) ? $way_pay : '' ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <th class="bg-navy disabled">Factura:</th>
                                    <td>
                                        <input type="text" step="any" name="client_invoice" class="text-left w-100" value="<?php echo isset($client_invoice) ? $client_invoice : '' ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <th class="bg-navy disabled">Nombre:</th>
                                    <td>
                                        <input type="text" step="any" name="client_name" class="text-left w-100" value="<?php echo isset($client_name) ? $client_name : '' ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <th class="bg-navy disabled">Banco:</th>
                                    <td>
                                        <input type="text" step="any" name="bank" class="text-left w-100" value="<?php echo isset($bank) ? $bank : '' ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <th class="bg-navy disabled">Clabe:</th>
                                    <td>
                                        <input type="text" step="any" name="client_key" class="text-left w-100" value="<?php echo isset($client_key) ? $client_key : '' ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <th class="bg-navy disabled">N° cuenta:</th>
                                    <td>
                                        <input type="text" step="any" name="client_account_num" class="text-left w-100" value="<?php echo isset($client_account_num) ? $client_account_num : '' ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <th class="bg-navy disabled">N° Tarjeta:</th>
                                    <td>
                                        <input type="text" step="any" name="client_card_num" class="text-left w-100" value="<?php echo isset($client_card_num) ? $client_card_num : '' ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <th class="bg-navy disabled">Sucursal:</th>
                                    <td>
                                        <input type="text" step="any" name="branch_office" class="text-left w-100" value="<?php echo isset($branch_office) ? $branch_office : '' ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <th class="bg-navy disabled">Numero de contácto:</th>
                                    <td>
                                        <input type="text" step="any" name="contact_client" class="text-left w-100" value="<?php echo isset($contact_client) ? $contact_client : '' ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <th class="bg-navy disabled">Correo electrónico:</th>
                                    <td>
                                        <input type="text" step="any" name="email_client" class="text-left w-100" value="<?php echo isset($email_client) ? $email_client : '' ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <?php
                                    $pdfs = [];
                                    if (isset($_GET['id'])) {
                                        $sql = "SELECT name_file FROM tax_records WHERE req_id = '{$_GET['id']}'";
                                        $result = $conn->query($sql);

                                        if ($result->num_rows > 0) {
                                            while ($row = $result->fetch_assoc()) {
                                                $pdfs[] = $row["name_file"];
                                            }
                                        }
                                    }
                                    ?>
                                <input type="file" id="pdfInput" accept=".pdf" multiple>
                                <button type="button" class="add_pdf" onclick="addPDFs()">Añadir PDFs</button>
                                <ul id="pdfList">
                                    <?php if (!empty($pdfs)): ?>
                                        <?php foreach ($pdfs as $pdf): ?>
                                            <li>
                                                <a href="<?php echo base_url . "uploads/pdfs/" . $pdf; ?>" target="_blank"><?php echo $pdf; ?></a>
                                                <button class="btn-danger" onclick="deletePDF('<?php echo $pdf; ?>')">Eliminar</button>
                                            </li>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </ul>
                                </tr>
                            </table>
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
    <input type="hidden" name="supplier_id[]" value="<?php echo isset($row['supplier_id']) ? $row['supplier_id'] : '' ?>">
    <td class="align-middle p-1 text-center">
        <button class="btn btn-sm btn-danger py-0" type="button"><i class="fa fa-times"></i></button>
    </td>
    <td class=" sequential-number align-middle p-1 text-center">

    </td>
    <td class="align-middle p-0 text-center">
        <input type="number" class="text-center w-100 border-0" step="any" name="qty[]"/>
    </td>
    <td class="align-middle item-unit p-1">
        <input type="text" class="text-center w-100 border-0" step="any" name="item-unit[]" value="<?php echo isset($row['unit']) ? $row['unit'] : '' ?>"/></td>
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
    let accumulatedFiles = [];

    function addPDFs() {
        const pdfInput = document.getElementById('pdfInput');
        const pdfList = document.getElementById('pdfList');
        const newFiles = Array.from(pdfInput.files);

        newFiles.forEach(file => {
            if (!accumulatedFiles.some(f => f.name === file.name)) {
                accumulatedFiles.push(file);

                // Crear un nuevo elemento li y configurarlo
                const li = document.createElement('li');
                li.textContent = file.name;

                const deleteBtn = document.createElement('span');
                deleteBtn.textContent = ' X';
                deleteBtn.style.color = 'red';
                deleteBtn.style.cursor = 'pointer';
                deleteBtn.onclick = function () {
                    pdfList.removeChild(li);
                    accumulatedFiles = accumulatedFiles.filter(f => f.name !== file.name);
                    logFiles();
                };
                li.appendChild(deleteBtn);

                pdfList.appendChild(li);
            }
        });
        pdfInput.value = '';
    }

    function rem_item(_this) {
        var supplierToRemove = _this.closest('tr').data('supplier');
        console.log("Proveedor a eliminar:", supplierToRemove);
        _this.closest('tr').remove();

        var hasOtherRowsWithSameSupplier = false;
        $('#item-list tr').each(function () {
            if ($(this).data('supplier') === supplierToRemove) {
                hasOtherRowsWithSameSupplier = true;
                return false;
            }
        });
        if (!hasOtherRowsWithSameSupplier) {
            var suppliers = $('#supplier_content .item-supplier').text().split(', ');
            var indexToRemove = suppliers.indexOf(supplierToRemove);
            if (indexToRemove !== -1) {
                suppliers.splice(indexToRemove, 1);
                $('#supplier_content .item-supplier').text(suppliers.join(', '));
            }
        }

        // Actualizar la numeración de las filas
        var table = $('#item-list');
        updateSequentialNumbers(table);
    }
    $(document).on('click', '.btn-danger', function () {
        rem_item($(this));
    });
    function updateSequentialNumbers(table) {
        // Renumerar los elementos con clase 'sequential-number' después de eliminar una fila
        $(table).find('.sequential-number').each(function (index, element) {
            $(element).text(index + 1);
        });
    }
    function calculate() {
        var _total = 0
        $('.po-item').each(function () {
            var qty = $(this).find("[name='qty[]']").val()
            var unit_price = $(this).find("[name='unit_price[]']").val()
            var row_total = 0;
            if (qty > 0 && unit_price > 0) {
                row_total = parseFloat(qty) * parseFloat(unit_price)
            }
            $(this).find('.total-price').text(parseFloat(row_total).toLocaleString('en-US'))
        })
        $('.total-price').each(function () {
            var _price = $(this).text()
            _price = _price.replace(/\,/gi, '')
            _total += parseFloat(_price)
        })
        var discount_perc = 0
        if ($('[name="iva_percentage"]').val() > 0) {
            discount_perc = $('[name="iva_percentage"]').val()
        }
        var iva_amount = _total * (discount_perc / 100);
        $('[name="iva_amount"]').val(parseFloat(iva_amount).toLocaleString("en-US"))
        var isr_perc = 0
        if ($('[name="isr_percentage"]').val() > 0) {
            isr_perc = $('[name="isr_percentage"]').val()
        }
        var isr_amount = _total * (isr_perc / 100);
        $('[name="isr_amount"]').val(parseFloat(isr_amount).toLocaleString("en-US"))
        $('#sub_total').text(parseFloat(_total).toLocaleString("en-US"))
        $('#total').text(parseFloat(_total + iva_amount + isr_amount).toLocaleString("en-US"))
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

                _item.find('input[name="item_id[]"]').val(ui.item.item_id);
                _item.find('input[name="item-unit[]"]').val(ui.item.unit);
                _item.find('input[name="qty[]"]').val(ui.item.quantity);
                _item.find('input[name="supplier_id[]"]').val(ui.item.supplier_id);
                _item.data('supplier', ui.item.supplier);
                var currentSuppliers = $('#supplier_content .item-supplier').text().split(', ').filter(Boolean); // filter(Boolean) para eliminar valores vacíos
                // Verifica si el proveedor seleccionado ya está en la lista
                if (!currentSuppliers.includes(ui.item.supplier)) {
                    
                    // Si no está en la lista, añádelo
                    currentSuppliers.push(ui.item.supplier);
                    $('#supplier_content .item-supplier').text(currentSuppliers.join(', '));
                }

            }
        })
    }
    function addValueToInput(selector, valueToAdd) {
    // 1. Obtén el valor actual del input.
    let currentValue = parseFloat($(selector).val()) || 0; // Si el valor no es un número, se usará 0

    // 2. Suma el nuevo valor al valor actual.
    let newValue = currentValue + valueToAdd;

    // 3. Establece el nuevo valor en el input.
    $(selector).val(newValue);
}
    $(document).ready(function () {

        var counter = 1;
        $('#add_row').click(function () {
            var tr = $('#item-clone tr').clone()
            var sup = $('#supplier_content div')
            $('#item-list tbody').append(tr)
            _autocomplete(tr);
            _autocomplete(sup);
            tr.find('[name="qty[]"],[name="unit_price[]"]').on('input keypress', function (e) {
                calculate()
            })
            var table = $('#item-list');
            updateSequentialNumbers(table);
            $('#item-list tfoot').find('[name="iva_percentage"],[name="isr_percentage"]').on('input keypress', function (e) {
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
                $('#item-list tfoot').find('[name="iva_percentage"],[name="isr_percentage"]').on('input keypress', function (e) {
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
            var _this = $(this)
            $('.err-msg').remove();
            $('[name="po_no"]').removeClass('border-danger')
            if ($('#item-list .po-item').length <= 0) {
                alert_toast(" Agregue al menos 1 elemento en la lista.", 'warning')
                return false;
            }
            start_loader();
            $.ajax({
                url: _base_url_ + "classes/Master.php?f=save_req",
                data: new FormData($(this)[0]),
                cache: false,
                contentType: false,
                processData: false,
                method: 'POST',
                type: 'POST',
                dataType: 'json',
                error: err => {
                    console.log(err);
                    alert_toast("Ocurrió un error", 'error');
                    end_loader();
                },
                success: function (resp) {
                    if (typeof resp == 'object' && resp.status == 'success') {
                        if (accumulatedFiles.length > 0) {
                            sendPDFs(resp.id, function (pdfResp) {
                                if (pdfResp && pdfResp.status == 'success') {
                                    location.href = "./?page=requisitions/view_req&id=" + resp.id;
                                }
                            });
                        } else {
                            location.href = "./?page=requisitions/view_req&id=" + resp.id;
                        }
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
            });

        })


    })



function sendPDFs(reqId, callback) {
    if (accumulatedFiles.length === 0) {
        alert_toast("No hay archivos PDF para enviar.", 'warning');
        if (callback) callback({ status: 'no_files' });
        return;
    }

    const formData = new FormData();
    accumulatedFiles.forEach((file, index) => {
        formData.append(`file${index + 1}`, file);
        formData.append('req_id', reqId);
    });

    $.ajax({
        url: _base_url_ + "classes/Master.php?f=save_pdf",
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        method: 'POST',
        type: 'POST',
        dataType: 'json',
        error: err => {
            console.log(err);
            alert_toast("Ocurrió un error al enviar los PDFs", 'error');
            end_loader();
            if (callback) callback({ status: 'error' });
        },
        success: function (resp) {
            if (typeof resp == 'object' && resp.status == 'success') {
                console.log('lo encontro');
            } else if (resp.status == 'failed' && !!resp.msg) {
                alert_toast(resp.msg, 'error');
            } else {
                alert_toast("Ocurrió un error inesperado.", 'error');
                console.log(resp);
            }
            end_loader();
            if (callback) callback(resp);
        }
    });
}

    function deletePDF(pdfName) {
        $.ajax({
            url: _base_url_ + "classes/Master.php?f=delete_pdf",
            data: {pdf: pdfName, req_id: '<?php echo isset($_GET['id']) ? $_GET['id'] : ""; ?>'},
            method: 'POST',
            dataType: 'json',
            error: err => {
                console.log(err);
                alert_toast("Ocurrió un error", 'error');
            },
            success: function (resp) {
                if (typeof resp == 'object' && resp.status == 'success') {
                    alert_toast("PDF eliminado con éxito", 'success');
                    location.reload();  // Recargar la página para reflejar la eliminación
                } else if (resp.status == 'failed' && !!resp.msg) {
                    alert_toast(resp.msg, 'error');
                } else {
                    alert_toast("Ocurrió un error inesperado.", 'error');
                    console.log(resp);
                }
            }
        });
    }


</script>