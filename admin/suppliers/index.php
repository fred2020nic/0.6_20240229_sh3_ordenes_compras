<?php if ($_settings->chk_flashdata('success')): ?>
    <script>
        alert_toast("<?php echo $_settings->flashdata('success') ?>", 'success')
    </script>
<?php endif; ?>
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">Lista de Proveedores</h3>
        <div class="card-tools">
            <a href="javascript:void(0)" id="create_new" class="btn btn-flat btn-primary"><span class="fas fa-plus"></span>  Crear Nuevo</a>
        </div>
    </div>
    <div class="card-body">
        <div class="container-fluid">
            <div class="container-fluid">
                <table class="table table-hover table-striped">
                    <colgroup>
                        <col width="5%">
                        <col width="10%">
                        <col width="20%">
                        <col width="10%">
                        <col width="10%">
                        <col width="10%">
                        <col width="15%">
                    </colgroup>
                    <thead>
                        <tr class="bg-navy disabled">
                            <th>Nombre comercial</th>
                            <th>Razón social RFC</th>
                            <th>Datos ubicación</th>
                            <th>Contacto venta</th>
                            <th>Contacto facturación</th>
                            <th>Contacto pago</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 1;
                        $qry = $conn->query("SELECT supplier_list.*,postal_codes.postal_code from `supplier_list` left join `postal_codes` on `postal_code_id` = postal_codes.id order by (`name`) asc ");
                        while ($row = $qry->fetch_assoc()):
                            ?>
                            <tr>
                                <td><?php echo $row['name'] ?></td>
                                <td><?php echo $row['business_name'] ?></td>
                                <td>
                                    <p class="m-0">
                                        <b>Colonia:</b> <?php echo $row['suburb'] ?>,<br>
                                        <b>Alcaldía:</b> <?php echo $row['town_hall'] ?>,<br>
                                        <b>Cod. postal:</b> <?php echo $row['postal_code'] ?><br>
                                    </p>
                                </td>
                                <td>
                                    <?php
                                        $text = $row['contact_sold'];
                                        preg_match("/\d+/", $text, $numbers);
                                        $numSold = isset($numbers[0]) ? $numbers[0] : '';
                                        $nameSold = trim(str_replace($numSold, '', $text));
                                        echo "<b>$nameSold</b>" . '<br>' . $numSold;
                                    ?>
                                </td>
                                <td>
                                    <?php
                                        $text =  $row['contact_invoice'];
                                        preg_match("/\d+/", $text, $numbers);
                                        $numInv = isset($numbers[0]) ? $numbers[0] : '';
                                        $nameInv = trim(str_replace($numInv, '', $text));
                                        echo "<b>$nameInv</b>" . '<br>' . $numInv;
                                    ?>
                                </td>
                                <td>
                                    <?php
                                         $text =  $row['contact_pay'];
                                        preg_match("/\d+/", $text, $numbers);
                                        $numPay = isset($numbers[0]) ? $numbers[0] : '';
                                        $namePay = trim(str_replace($numPay, '', $text));
                                        echo "<b>$namePay</b>" . '<br>' . $numPay;
                                    ?>
                                </td>
    <!--							<td>
                                        <p class="m-0">
                                <?php //echo $row['contact_person'] ?><br>
                                <?php //echo $row['contact'] ?>
                                        </p>
                                </td>-->
                                <td align="center">
                                    <button type="button" class="btn btn-flat btn-default btn-sm dropdown-toggle dropdown-icon py-0" data-toggle="dropdown">
                                        Acción
                                        <span class="sr-only">Toggle Dropdown</span>
                                    </button>
                                    <div class="dropdown-menu" role="menu">
                                        <a class="dropdown-item view_data" href="javascript:void(0)" data-id = "<?php echo $row['id'] ?>"><span class="fa fa-info text-primary"></span> Ver</a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item edit_data" href="javascript:void(0)" data-id = "<?php echo $row['id'] ?>"><span class="fa fa-edit text-primary"></span> Editar</a>
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
        <div class="card-tools">
            <a href="javascript:void(0)" id="create_postal_code" class="btn btn-flat btn-primary"><span class="fas fa-plus"></span>  Crear Nuevo Código Postal</a>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $('.delete_data').click(function () {
            _conf("¿Estás segur@ de eliminar este proveedor de forma permanente?", "delete_supplier", [$(this).attr('data-id')]);
        });
        $('#create_new').click(function () {
            uni_modal("<i class='fa fa-plus'></i> Registrar Nuevo Proveedor", "suppliers/manage_supplier.php");
        });
        $('#create_postal_code').click(function () {
            uni_modal("<i class='fa fa-plus'></i> Registrar Nuevo Código Postal", "suppliers/postal_code.php");
        });
        $('.view_data').click(function () {
            uni_modal("<i class='fa fa-info-circle'></i> Datos del proveedor", "suppliers/view_details.php?id=" + $(this).attr('data-id'), "");
        });
        $('.edit_data').click(function () {
            uni_modal("<i class='fa fa-edit'></i> Editar los detalles del proveedor", "suppliers/manage_supplier.php?id=" + $(this).attr('data-id'));
        });
        $('.table th,.table td').addClass('px-1 py-0 align-middle')
        $('.table').dataTable({
            "lengthChange": false
        });
    })
    function delete_supplier($id) {
        start_loader();
        $.ajax({
            url: _base_url_ + "classes/Master.php?f=delete_supplier",
            method: "POST",
            data: {id: $id},
            dataType: "json",
            error: err => {
                console.log(err)
                alert_toast("Ocurrió un error.", 'error');
                end_loader();
            },
            success: function (resp) {
                if (typeof resp == 'object' && resp.status == 'success') {
                    location.reload();
                } else {
                    alert_toast("Ocurrió un error.", 'error');
                    end_loader();
                }
            }
        })
    }
</script>