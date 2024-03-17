<?php
require_once('../../config.php');
if (isset($_GET['id']) && $_GET['id'] > 0) {
    $qry = $conn->query("SELECT * from `supplier_list` where id = '{$_GET['id']}' ");
    if ($qry->num_rows > 0) {
        foreach ($qry->fetch_assoc() as $k => $v) {
            $$k = stripslashes($v);
        }
    }
    $postal = $conn->query("SELECT * from `postal_codes` where id = '{$_GET['id']}' ");
    if ($postal->num_rows > 0) {
        foreach ($postal->fetch_assoc() as $p => $v) {
            $$p = stripslashes($v);
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
</style>
<form action="" id="supplier-form">
    <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
    <div class="container-fluid">
        <div class="form-group">
            <label for="name" class="control-label">Nombre Proveedor</label>
            <input type="text" name="name" id="name" class="form-control rounded-0" value="<?php echo isset($name) ? $name : "" ?>" required>
        </div>
        <div class="form-group">
            <label for="business_name" class="control-label">Razón social RFC</label>
            <input type="text" name="business_name" id="business_name" class="form-control rounded-0" value="<?php echo isset($business_name) ? $business_name : "" ?>" required>
        </div>
        <div class="form-group">
            <label for="type_person" class="control-label">Persona</label>
            <select name="type_person" id="type_person" class="form-control rounded-0" required>
                <option value="fisica">Física</option>
                <option value="moral">Moral</option>
            </select>
        </div>
        <div class="col-md-6 form-group">
            <label for="postal_code_id">Código Postal</label>
            <select name="postal_code_id" id="postal_code_id" class="custom-select custom-select-sm rounded-0 select2 select-prov">
                <option value="" disabled <?php echo!isset($postal_code_id) ? "selected" : '' ?>></option>
                <?php
                $postal_qry = $conn->query("SELECT * FROM `postal_codes` order by `postal_code` asc");
                while ($row = $postal_qry->fetch_assoc()):
                    ?>
                    <option value="<?php echo $row['id'] ?>" <?php echo isset($postal_code) && $postal_code == $row['postal_code'] ? 'selected' : '' ?> ><?php echo $row['postal_code'] ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="suburb" class="control-label">Colonia</label>
            <input type="text" name="suburb" id="suburb" class="form-control rounded-0" value="<?php echo isset($suburb) ? $suburb : "" ?>" required>
        </div>
        <div class="form-group">
            <label for="town_hall" class="control-label">Alcaldía</label>
            <input type="text" name="town_hall" id="town_hall" class="form-control rounded-0" value="<?php echo isset($town_hall) ? $town_hall : "" ?>" required>
        </div>
        <div class="form-group">
            <label for="contact_sold" class="control-label">Contacto venta</label>
            <input type="text" name="contact_sold" id="contact_sold" class="form-control rounded-0" value="<?php echo isset($contact_sold) ? $contact_sold : "" ?>" required>
        </div>
        <div class="form-group">
            <label for="contact_invoice" class="control-label">Contacto facturación</label>
            <input type="text" name="contact_invoice" id="contact_invoice" class="form-control rounded-0" value="<?php echo isset($contact_invoice) ? $contact_invoice : "" ?>" required>
        </div>

        <div class="form-group">
            <label for="contact_pay" class="control-label">Contacto pago</label>
            <input type="text" name="contact_pay" id="contact_pay" class="form-control rounded-0" value="<?php echo isset($contact_pay) ? $contact_pay : "" ?>" required>
        </div> 
        
        <div class="form-group">
            <label for="discount" class="control-label">Descuento (%)</label>
            <input type="text" name="discount" id="discount" class="form-control rounded-0" value="<?php echo isset($discount) ? $discount : "" ?>" required>
        </div>
        <div class="form-group">
            <label for="email" class="control-label">Correo Electrónico</label>
            <input type="email" name="email" id="email" class="form-control rounded-0" value="<?php echo isset($email) ? $email : "" ?>" required>
        </div>
    
        <div class="form-group">
            <label for="credit" class="control-label">Credito</label>
            <select name="credit" id="credit" class="form-control rounded-0" required>
                <option value="0" <?php echo (isset($credit) && $credit == '0' && $credit !== '') ? 'selected': '' ?>>No</option>
                <option  value="1" <?php echo (isset($credit) && $credit == '1' && $credit !== '') ? 'selected': '' ?>>Si</option>
            </select>
        </div>
         <div class="form-group">
            <label for="contact_pay" class="control-label">Dias Credito</label>
            <input type="text" name="dias_c" id="dias_c" class="form-control rounded-0" value="<?php echo isset($dias_c) ? $dias_c : "" ?>" required>
        </div>
    </div>
    <div class="form-group">
            <label for="type_mon" class="control-label">Moneda</label>
            <select name="type_mon" id="type_mon" class="form-control rounded-0" required>
                <option value="Euro">Euro</option>
                <option value="Pesos Mexicanos">Peso Mexicano</option>
                <option value="Dolar">Dolar</option>
                
            </select>
        </div>
    <br>
    <hr>
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
                                    <th class="bg-navy disabled">Clave:</th>
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
                               
                            </table>
                        </div>
</form>
<script>
    $(function () {
        $('.select2').select2({placeholder: "Porfavor selecciona aquí", width: "relative"});
        $('#supplier-form').submit(function (e) {
            e.preventDefault();
            var _this = $(this);
            $('.err-msg').remove();
            start_loader();
            $.ajax({
                url: _base_url_ + "classes/Master.php?f=save_supplier",
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
                        location.reload();
                    } else if (resp.status == 'failed' && !!resp.msg) {
                        var el = $('<div>');
                        el.addClass("alert alert-danger err-msg").text(resp.msg);
                        _this.prepend(el);
                        el.show('slow');
                        $("html, body").animate({scrollTop: 0}, "fast");
                    } else {
                        alert_toast("Ocurrió un error", 'error');
                        console.log(resp);
                    }
                    end_loader();
                }
            });
        });
    });
</script>