<?php
require_once('../../config.php');
if (isset($_GET['id']) && $_GET['id'] > 0) {
    $qry = $conn->query("SELECT * from `supplier_list` left join `postal_codes` on `postal_code_id` = postal_codes.id  where supplier_list.id = '{$_GET['id']}'  order by (`name`) asc  ");
    if ($qry->num_rows > 0) {
        foreach ($qry->fetch_assoc() as $k => $v) {
            $$k = stripslashes($v);
        }
    }
}
?>
<style>
    #uni_modal .modal-footer{
        display:none
    }
</style>
<div class="container fluid">
    <callout class="callout-primary">
        <dl class="row">
            <dt class="col-md-4">Nombre comercial</dt>
            <dd class="col-md-8">: <?php echo $name ?></dd>
            <dt class="col-md-4">Nombre social RFC</dt>
            <dd class="col-md-8">: <?php echo $business_name ?></dd>
            <dt class="col-md-4">Datos ubicación</dt>
            <dd class="col-md-8">: <p class="m-0">
                    <b>Colonia:</b> <?php echo $suburb ?>,<br>
                    <b>Alcaldía:</b> <?php echo $town_hall ?>,<br>
                    <b>Cod. postal:</b> <?php echo $postal_code ?><br>
                </p>
            </dd>
            <dt class="col-md-4">Contacto venta</dt>
            <dd class="col-md-8">:  <?php
                $text = $contact_sold;
                preg_match("/\d+/", $text, $numbers);
                $numSold = isset($numbers[0]) ? $numbers[0] : '';
                $nameSold = trim(str_replace($numSold, '', $text));
                echo "<b>$nameSold</b> " . $numSold;
                ?>
            </dd>
            <dt class="col-md-4">Contacto facturación</dt>
            <dd class="col-md-8">: <?php
                $text = $contact_invoice;
                preg_match("/\d+/", $text, $numbers);
                $numInv = isset($numbers[0]) ? $numbers[0] : '';
                $nameInv = trim(str_replace($numInv, '', $text));
                echo "<b>$nameInv</b> " . $numInv;
                ?>
            </dd>
            <dt class="col-md-4">Contacto pago</dt>
            <dd class="col-md-8">: <?php
                $text = $contact_pay;
                preg_match("/\d+/", $text, $numbers);
                $numPay = isset($numbers[0]) ? $numbers[0] : '';
                $namePay = trim(str_replace($numPay, '', $text));
                echo "<b>$namePay</b> " . $numPay;
                ?>
            </dd>
            <dt class="col-md-4">Persona</dt>
            <dd class="col-md-8">: <?php echo $type_person ?></dd>
            <dt class="col-md-4">Descuento</dt>
            <dd class="col-md-8">: <?php echo $discount ?></dd>
            <dt class="col-md-4">Credito</dt>
            <dd class="col-md-8">: <?php echo ($credit == '0') ? "No" : "Si" ?></dd>
            <dt class="col-md-4">Correo</dt>
            <dd class="col-md-8">: <?php echo $email ?></dd>
        </dl>
    </callout>
    <div class="row px-2 justify-content-end">
        <div class="col-1">
            <button class="btn btn-dark btn-flat btn-sm" type="button" data-dismiss="modal">Cerrar</button>
        </div>
    </div>
</div>