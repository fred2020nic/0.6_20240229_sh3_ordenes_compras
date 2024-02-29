<?php
require_once('../../config.php');
if (isset($_GET['id']) && $_GET['id'] > 0) {
    $qry = $conn->query("SELECT * from `postal_codes` where 1");
    if ($qry->num_rows > 0) {
        foreach ($qry->fetch_assoc() as $k => $v) {
            $$k = stripslashes($v);
        }
    }
}
?>

<form action="" id="postal-form">
    <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
    <div class="container-fluid">
        <div class="form-group">
            <label for="name" class="control-label">Código Postal</label>
            <input type="text" name="postal_code" id="postal_code" class="form-control rounded-0" value="<?php echo isset($postal_code) ? $postal_code : "" ?>" required>
        </div>
    </div>
</form>
<script>
    $(function () {
        $('#postal-form').submit(function (e) {
            e.preventDefault();
            var consulta = new FormData($(this)[0]);
            var _this = $(this);
            $('.err-msg').remove();
            start_loader();
            $.ajax({
                url: _base_url_ + "classes/Master.php?f=save_postal_code",
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