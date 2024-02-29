<?php
require_once('../../config.php');
$options = "";
if (isset($_GET['id']) && $_GET['id'] > 0) {
    $qry = $conn->query("SELECT * from `item_list` where id = '{$_GET['id']}' ");
    if ($qry->num_rows > 0) {
        foreach ($qry->fetch_assoc() as $k => $v) {
            $$k = stripslashes($v);
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

<form action="" id="item-form">
    <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
    <div class="container-fluid">
        <div class="form-group">
            <label for="name" class="control-label">Clave Producto</label>
            <input type="text" name="product_key" id="product_key" class="form-control rounded-0" value="<?php echo isset($product_key) ? $product_key : "" ?>" required>
        </div>
        <div class="form-group">
            <label for="quantity" class="control-label">Cantidad</label>
            <input type="text" name="quantity" id="quantity" class="form-control rounded-0" value="<?php echo isset($quantity) ? $quantity : "" ?>" required>
        </div>
        <div class="form-group">
            <label for="unit" class="control-label">Unidad</label>
            <input type="text" name="unit" id="unit" class="form-control rounded-0" value="<?php echo isset($unit) ? $unit : "" ?>" required>
        </div>
        <div class="form-group">
            <label for="description" class="control-label">Descripción (kg, gr, L, etc.)</label>
            <textarea rows="3" name="description" id="description" class="form-control rounded-0" required><?php echo isset($description) ? $description : "" ?></textarea>
        </div>
        <div class="col-md-6 form-group">
            <label for="supplier_id">Proveedor</label>
            <select name="supplier_id" id="supplier_id" class="custom-select custom-select-sm rounded-0 select2 select-prov">
                <option value="" disabled <?php echo!isset($supplier_id) ? "selected" : '' ?>></option>
                <?php
                $supplier_qry = $conn->query("SELECT * FROM `supplier_list` order by `name` asc");
                while ($row = $supplier_qry->fetch_assoc()):
                    ?>
                    <option value="<?php echo $row['id'] ?>" <?php echo isset($supplier_id) && $supplier_id == $row['id'] ? 'selected' : '' ?>><?php echo $row['name'] ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="delivery_time" class="control-label">Tiempo de entrega (dias)</label>
            <input type="text" name="delivery_time" id="delivery_time" class="form-control rounded-0" value="<?php echo isset($delivery_time) ? $delivery_time : "" ?>" required>
        </div>
        <div class="form-group">
            <label for="department" class="control-label">Departamento</label>
            <input type="text" name="department" id="department" class="form-control rounded-0" value="<?php echo isset($department) ? $department : "" ?>" required>
        </div>
        <div class="form-group">
            <label for="delivery_time" class="control-label">Art.</label>
            <input type="text" name="art" id="art" class="form-control rounded-0" value="<?php echo isset($art) ? $art : "" ?>" required>
        </div>
    </div>
</form>
<script>
    $('.select2').select2({placeholder:"Porfavor selecciona aquí",width:"relative"});
    $(function () {
        $('#item-form').submit(function (e) {
            e.preventDefault();
            var _this = $(this)
            $('.err-msg').remove();
            start_loader();
            $.ajax({
                url: _base_url_ + "classes/Master.php?f=save_item",
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
                        var el = $('<div>')
                        el.addClass("alert alert-danger err-msg").text(resp.msg)
                        _this.prepend(el)
                        el.show('slow')
                        $("html, body").animate({scrollTop: 0}, "fast");
                    } else {
                        alert_toast("Ocurrió un error", 'error');
                        console.log(resp)
                    }
                    end_loader()
                }
            })
        })
    })
</script>