<?php

require_once('../config.php');

Class Master extends DBConnection {

    private $lastReqId;
    private $settings;

    public function __construct() {
        global $_settings;
        $this->settings = $_settings;
        parent::__construct();
    }

    public function __destruct() {
        parent::__destruct();
    }

    function capture_err() {
        if (!$this->conn->error)
            return false;
        else {
            $resp['status'] = 'failed';
            $resp['error'] = $this->conn->error;
            return json_encode($resp);
            exit;
        }
    }

    function save_supplier() {
        extract($_POST);
        $data = "";
        foreach ($_POST as $k => $v) {
            if (!in_array($k, array('id'))) {
                $v = addslashes(trim($v));
                if (!empty($data))
                    $data .= ",";
                $data .= " `{$k}`='{$v}' ";
            }
        }
        $check = $this->conn->query("SELECT * FROM `supplier_list` where `name` = '{$name}' " . (!empty($id) ? " and id != {$id} " : "") . " ")->num_rows;
        if ($this->capture_err())
            return $this->capture_err();
        if ($check > 0) {
            $resp['status'] = 'failed';
            $resp['msg'] = "Proveedor existe actualmente";
            return json_encode($resp);
            exit;
        }
        if (empty($id)) {
            $sql = "INSERT INTO `supplier_list` set {$data} ";
            $save = $this->conn->query($sql);
        } else {
            $sql = "UPDATE `supplier_list` set {$data} where id = '{$id}' ";
            $save = $this->conn->query($sql);
        }
        if ($save) {
            $resp['status'] = 'success';
            if (empty($id))
                $this->settings->set_flashdata('success', "Nuevo proveedor guardado correctamente");
            else
                $this->settings->set_flashdata('success', "Proveedor actualizado con éxito.");
        } else {
            $resp['status'] = 'failed';
            $resp['err'] = $this->conn->error . "[{$sql}]";
        }
        return json_encode($resp);
    }

    function delete_supplier() {
        extract($_POST);
        $del = $this->conn->query("DELETE FROM `supplier_list` where id = '{$id}'");
        if ($del) {
            $resp['status'] = 'success';
            $this->settings->set_flashdata('success', "Proveedor eliminado correctamente.");
        } else {
            $resp['status'] = 'failed';
            $resp['error'] = $this->conn->error;
        }
        return json_encode($resp);
    }

    function save_postal_code() {
        extract($_POST);
        $data = "";
        foreach ($_POST as $k => $v) {
            if (!in_array($k, array('id'))) {
                $v = addslashes(trim($v));
                if (!empty($data))
                    $data .= ",";
                $data .= " `{$k}`='{$v}' ";
            }
        }
        $check = $this->conn->query("SELECT * FROM `postal_codes` where `postal_code` = '{$postal_code}' ")->num_rows;
        if ($this->capture_err())
            return $this->capture_err();
        if ($check > 0) {
            $resp['status'] = 'failed';
            $resp['msg'] = "Proveedor existe actualmente";
            return json_encode($resp);
            exit;
        }
        if (empty($id)) {
            $sql = "INSERT INTO `postal_codes` set {$data} ";
            $save = $this->conn->query($sql);
        } else {
            $sql = "UPDATE `postal_codes` set {$data} where id = '{$id}' ";
            $save = $this->conn->query($sql);
        }
        if ($save) {
            $resp['status'] = 'success';
            if (empty($id))
                $this->settings->set_flashdata('success', "Nuevo código postal creado correctamente");
            else
                $this->settings->set_flashdata('success', "Código postal actualizado con éxito.");
        } else {
            $resp['status'] = 'failed';
            $resp['err'] = $this->conn->error . "[{$sql}]";
        }
        return json_encode($resp);
    }

    function save_item() {
        extract($_POST);
        $data = "";
        foreach ($_POST as $k => $v) {
            if (!in_array($k, array('id', 'description'))) {
                if (!empty($data))
                    $data .= ",";
                $data .= " `{$k}`='{$v}' ";
            }
        }
        if (isset($_POST['description'])) {
            if (!empty($data))
                $data .= ",";
            $data .= " `description`='" . addslashes(htmlentities($description)) . "' ";
        }
        $check = $this->conn->query("SELECT * FROM `item_list` where `product_key` = '{$product_key}' " . (!empty($id) ? " and id != {$id} " : "") . " ")->num_rows;
        if ($this->capture_err())
            return $this->capture_err();
        if ($check > 0) {
            $resp['status'] = 'failed';
            $resp['msg'] = "El nombre del producto ya existe.";
            return json_encode($resp);
            exit;
        }
        if (empty($id)) {
            $sql = "INSERT INTO `item_list` set {$data} ";
        } else {
            $sql = "UPDATE `item_list` set {$data} where id = '{$id}' ";
        }
        $save = $this->conn->query($sql);
        if ($save) {
            $resp['status'] = 'success';
            if (empty($id))
                $this->settings->set_flashdata('success', "Nuevo elemento guardado con éxito.");
            else
                $this->settings->set_flashdata('success', "Producto actualizado correctamente.");
        } else {
            $resp['status'] = 'failed';
            $resp['err'] = $this->conn->error . "[{$sql}]";
        }
        return json_encode($resp);
    }

    function delete_item() {
        extract($_POST);
        $del = $this->conn->query("DELETE FROM `item_list` where id = '{$id}'");
        if ($del) {
            $resp['status'] = 'success';
            $this->settings->set_flashdata('success', "Producto eliminado correctamente.");
        } else {
            $resp['status'] = 'failed';
            $resp['error'] = $this->conn->error;
        }
        return json_encode($resp);
    }

    function search_items() {
        extract($_POST);
        $qry = $this->conn->query("SELECT i.*, s.id, s.name, s.id as supplier_id, s.discount as discount, i.id as item_id FROM item_list i LEFT JOIN supplier_list s ON i.supplier_id = s.id WHERE i.product_key LIKE '%{$q}%'");
        $data = array();
        while ($row = $qry->fetch_assoc()) {
            $data[] = array("label" => $row['product_key'], "id" => $row['id'], "item_id" => $row['item_id'], "description" => $row['description'], "unit" => $row['unit'], "quantity" => $row['quantity'], "supplier" => $row['name'], "supplier_id" => $row['supplier_id'], "discount" => $row['discount']);
        }
        return json_encode($data);
    }

    function get_req_info() {
        extract($_POST);
        $qry = $this->conn->query("SELECT r.way_pay, r.department, r.invoice, r.author_name, r.pr_no, r.iva_percentage, r.iva_amount, r.isr_percentage, r.isr_amount, r.observation, ri.req_id, ri.item_id, il.product_key, il.description, il.unit as unit, ri.quantity as quantity, ri.unit_price as unit_price, s.id as supplier_id, s.name as supplier, s.discount as supplier_discount FROM `req_list` r LEFT JOIN req_items ri ON ri.req_id = r.id LEFT JOIN item_list il ON ri.item_id = il.id LEFT JOIN supplier_list s ON s.id = ri.supplier_id WHERE r.id = {$q}; ");
        $data = array();
        while ($row = $qry->fetch_assoc()) {
            $data[] = array("quantity" => $row['quantity'], "unit" => $row['unit'], "description" => $row['product_key'], "unit_price" => $row['unit_price'],  "isr_percentage" => $row['isr_percentage'], "supplier_id" => $row['supplier_id'], "supplier" => $row['supplier'], "discount" => $row['supplier_discount'], "way_pay" => $row['way_pay'], "department" => $row['department'], "invoice" => $row['invoice'], "author_name" => $row['author_name'], "item_id" => $row['item_id']);
        }
        return json_encode($data);
    }
    function get_supplier_discount() {
    extract($_POST);
    
    if (!isset($supplier_id)) {
        return json_encode(['success' => false, 'message' => 'ID del proveedor no proporcionado.']);
    }
    
    $qry = $this->conn->query("SELECT discount FROM `supplier_list` WHERE id = {$supplier_id}");
    if ($qry && $qry->num_rows > 0) {
        $row = $qry->fetch_assoc();
        return json_encode(['success' => true, 'discount' => $row['discount']]);
    } else {
        return json_encode(['success' => false, 'message' => 'Proveedor no encontrado o no tiene descuento.']);
    }
}


    function save_po() {
        extract($_POST);
        $data = "";
        foreach ($_POST as $k => $v) {
            if (in_array($k, array('discount_amount', 'tax_amount')))
                $v = str_replace(',', '', $v);
            if (!in_array($k, array('id', 'po_no')) && !is_array($_POST[$k])) {
                $v = addslashes(trim($v));
                if (!empty($data))
                    $data .= ",";
                $data .= " `{$k}`='{$v}' ";
            }
        }
        if (!empty($po_no)) {
            $check = $this->conn->query("SELECT * FROM `po_list` where `po_no` = '{$po_no}' " . ($id > 0 ? " and id != '{$id}' " : ""))->num_rows;
            if ($this->capture_err())
                return $this->capture_err();
            if ($check > 0) {
                $resp['status'] = 'po_failed';
                $resp['msg'] = "El número de orden existe actualmente";
                return json_encode($resp);
                exit;
            }
        } else {
            $po_no = "";
            while (true) {
                $po_no = "PO-" . (sprintf("%'.011d", mt_rand(1, 99999999999)));
                $check = $this->conn->query("SELECT * FROM `po_list` where `po_no` = '{$po_no}'")->num_rows;
                if ($check <= 0)
                    break;
            }
        }
        $data .= ", po_no = '{$po_no}' ";

        if (empty($id)) {
            $sql = "INSERT INTO `po_list` set {$data} ";
        } else {
            $sql = "UPDATE `po_list` set {$data} where id = '{$id}' ";
        }
        $save = $this->conn->query($sql);
        if ($save) {
            $resp['status'] = 'success';
            $po_id = empty($id) ? $this->conn->insert_id : $id;
            $resp['id'] = $po_id;
            $data = "";
            foreach ($item_id as $k => $v) {
                if (!empty($data))
                    $data .= ",";
                $data .= "('{$po_id}','{$v}','{$unit_price[$k]}','{$qty[$k]}','{$supplier_id[$k]}')";
            }
            if (!empty($data)) {
                $this->conn->query("DELETE FROM `order_items` where po_id = '{$po_id}'");
                $save = $this->conn->query("INSERT INTO `order_items` (`po_id`,`item_id`,`unit_price`,`quantity`,`supplier_id`) VALUES {$data} ");
            }
            if (empty($id))
                $this->settings->set_flashdata('success', "Orden de compra guardada correctamente.");
            else
                $this->settings->set_flashdata('success', "Orden de compra actualizada correctamente.");
        } else {
            $resp['status'] = 'failed';
            $resp['err'] = $this->conn->error . "[{$sql}]";
        }
        return json_encode($resp);
    }

    function delete_po() {
        extract($_POST);
//        $del = $this->conn->query("DELETE FROM `po_list` where id = '{$id}'");
        $del2 = $this->conn->query("DELETE FROM `order_items` where po_id = '{$id}'");
        $del = $this->conn->query("DELETE FROM `po_list` where id = '{$id}'");
        if ($del && $del2) {
            $resp['status'] = 'success';
            $this->settings->set_flashdata('success', "Requisición eliminada exitósamente");
        } else {
            $resp['status'] = 'failed';
            $resp['error'] = $this->conn->error;
        }
        return json_encode($resp);
    }

    //logica módulo reqeuisiciones <requisitions>
    function save_req() {
        extract($_POST);
        $data = "";
        foreach ($_POST as $k => $v) {
            if (in_array($k, array('iva_amount', 'isr_amount')))
                $v = str_replace(',', '', $v);
            if (!in_array($k, array('id', 'req_no')) && !is_array($_POST[$k])) {
                $v = addslashes(trim($v));
                if (!empty($data))
                    $data .= ",";
                $data .= " `{$k}`='{$v}' ";
            }
        }
        if (!empty($pr_no)) {
            $check = $this->conn->query("SELECT * FROM `req_list` where `pr_no` = '{$pr_no}' " . ($id > 0 ? " and id != '{$id}' " : ""))->num_rows;
            if ($this->capture_err())
                return $this->capture_err();
            if ($check > 0) {
                $resp['status'] = 'pr_failed';
                $resp['msg'] = "El número de orden existe actualmente";
                return json_encode($resp);
                exit;
            }
        } else {
            $po_no = "";
            while (true) {
                $check = $this->conn->query("SELECT * FROM `req_list` where `pr_no` = '{$pr_no}'")->num_rows;
                if ($check <= 0)
                    break;
            }
        }
        $data;

        if (empty($id)) {
            $sql = "INSERT INTO `req_list` set {$data} ";
        } else {
            $sql = "UPDATE `req_list` set {$data} where id = '{$id}' ";
        }
        $save = $this->conn->query($sql);
        if ($save) {
            $resp['status'] = 'success';
            $req_id = empty($id) ? $this->conn->insert_id : $id;
            $_SESSION['last_req_id'] = $req_id;  // Guardar en la sesión
            $resp['id'] = $req_id;
            $this->lastReqId = empty($id) ? $this->conn->insert_id : $id;
            $data = "";
            foreach ($item_id as $k => $v) {
                if (!empty($data))
                    $data .= ",";
                $data .= "('{$req_id}','{$v}','{$unit_price[$k]}','{$qty[$k]}','{$supplier_id[$k]}')";
            }
            if (!empty($data)) {
                $this->conn->query("DELETE FROM `req_items` where req_id = '{$req_id}'");
                $save = $this->conn->query("INSERT INTO `req_items` (`req_id`,`item_id`,`unit_price`,`quantity`, `supplier_id`) VALUES {$data} ");
            }
            if (empty($id))
                $this->settings->set_flashdata('success', "Orden de compra guardada correctamente.");
            else
                $this->settings->set_flashdata('success', "Orden de compra actualizada correctamente.");
        } else {
            $resp['status'] = 'failed';
            $resp['err'] = $this->conn->error . "[{$sql}]";
        }
        return json_encode($resp);
    }

    //guardar constancias fiscales
    function save_pdf() {
        $req_id = isset($_POST['req_id']) ? $_POST['req_id'] : null;
        // Directorio donde se guardarán los PDFs
        $uploadDirectory = "../uploads/pdfs/";

        // Verificar si el directorio existe, si no, crearlo
        if (!file_exists($uploadDirectory)) {
            mkdir($uploadDirectory, 0777, true);
        }

        $response = ["status" => "failed"];

        try {
            foreach ($_FILES as $file) {
                // Obtener extensión del archivo
                $fileExtension = pathinfo($file["name"], PATHINFO_EXTENSION);

                // Crear un nombre único para el archivo para evitar sobreescribir archivos existentes
                $fileName = uniqid() . '.' . $fileExtension;

                // Ruta completa donde se guardará el archivo
                $targetPath = $uploadDirectory . $fileName;

                // Mover el archivo subido al directorio
                if (move_uploaded_file($file["tmp_name"], $targetPath)) {
                    // Insertar información del archivo en la base de dato
                    $sql = "INSERT INTO tax_records (req_id, name_file) VALUES (?, ?)";
                    $stmt = $this->conn->prepare($sql);
                    $stmt->bind_param("is", $req_id, $fileName);

                    if ($stmt->execute()) {
                        $response["status"] = "success";
                    } else {
                        $response["status"] = 'failed';
                        $response["error"] = $this->conn->error;
                    }
                    unset($_SESSION['last_req_id']);
                } else {
                    $response["msg"] = "Error al guardar el archivo.";
                }
            }
        } catch (Exception $e) {
            $response["msg"] = "Error: " . $e->getMessage();
        }

        echo json_encode($response);
    }

    function delete_pdf() {
        $pdfName = $_POST['pdf'];
        $req_id = isset($_POST['req_id']) ? $_POST['req_id'] : null;

        if ($req_id === null) {
            $response["status"] = "failed";
            $response["msg"] = "req_id no proporcionado";
            return json_encode($response);
            exit;
        }

        $response = ["status" => "failed"];

        // Eliminar de la base de datos
        $sql = "DELETE FROM tax_records WHERE name_file = '{$pdfName}' AND req_id = '{$req_id}'";
        if ($this->conn->query($sql)) {
            // Intentar eliminar el archivo físico
            $filePath = '../uploads/pdfs/' . $pdfName;
            if (file_exists($filePath)) {
                unlink($filePath);
            }

            $response["status"] = "success";
        } else {
            $response["error"] = "Error al eliminar el registro en la base de datos.";
        }

        return json_encode($response);
    }

    //fin logica módulo reqeuisiciones <requisitions>
    function get_price() {
        extract($_POST);
        $qry = $this->conn->query("SELECT * FROM price_list where unit_id = '{$unit_id}'");
        $this->capture_err();
        if ($qry->num_rows > 0) {
            $res = $qry->fetch_array();
            switch ($rent_type) {
                case '1':
                    $resp['price'] = $res['monthly'];
                    break;
                case '2':
                    $resp['price'] = $res['quarterly'];
                    break;
                case '3':
                    $resp['price'] = $res['annually'];
                    break;
            }
        } else {
            $resp['price'] = "0";
        }
        return json_encode($resp);
    }

    function save_rent() {
        extract($_POST);
        $data = "";
        foreach ($_POST as $k => $v) {
            if (!in_array($k, array('id')) && !is_array($_POST[$k])) {
                if (!empty($data))
                    $data .= ",";
                $v = addslashes($v);
                $data .= " `{$k}`='{$v}' ";
            }
        }
        switch ($rent_type) {
            case 1:
                $data .= ", `date_end`='" . date("Y-m-d", strtotime($date_rented . ' +1 month')) . "' ";
                break;

            case 2:
                $data .= ", `date_end`='" . date("Y-m-d", strtotime($date_rented . ' +3 month')) . "' ";
                break;
            case 3:
                $data .= ", `date_end`='" . date("Y-m-d", strtotime($date_rented . ' +1 year')) . "' ";
                break;
            default:
                # code...
                break;
        }
        if (empty($id)) {
            $sql = "INSERT INTO `rent_list` set {$data} ";
        } else {
            $sql = "UPDATE `rent_list` set {$data} where id = '{$id}' ";
        }
        $save = $this->conn->query($sql);
        if ($save) {
            $resp['status'] = 'success';
            if (empty($id))
                $this->settings->set_flashdata('success', "Nueva orden guardado correctamente.");
            else
                $this->settings->set_flashdata('success', "Orden exitósamente actualizada");
            $this->settings->conn->query("UPDATE `unit_list` set `status` = '{$status}' where id = '{$unit_id}'");
        } else {
            $resp['status'] = 'failed';
            $resp['err'] = $this->conn->error . "[{$sql}]";
        }
        return json_encode($resp);
    }

    function delete_rent() {
        extract($_POST);
        $del = $this->conn->query("DELETE FROM `rent_list` where id = '{$id}'");
        if ($del) {
            $resp['status'] = 'success';
            $this->settings->set_flashdata('success', "Requisición eliminada exitósamente");
        } else {
            $resp['status'] = 'failed';
            $resp['error'] = $this->conn->error;
        }
        return json_encode($resp);
    }

    function delete_req() {
        extract($_POST);
        $del2 = $this->conn->query("DELETE FROM `req_items` where req_id = '{$id}'");
        $del = $this->conn->query("DELETE FROM `req_list` where id = '{$id}'");
        if ($del && $del2) {
            $resp['status'] = 'success';
            $this->settings->set_flashdata('success', "Requisición eliminada exitósamente");
        } else {
            $resp['status'] = 'failed';
            $resp['error'] = $this->conn->error;
        }
        return json_encode($resp);
    }

    function delete_img() {
        extract($_POST);
        if (is_file($path)) {
            if (unlink($path)) {
                $resp['status'] = 'success';
            } else {
                $resp['status'] = 'failed';
                $resp['error'] = 'failed to delete ' . $path;
            }
        } else {
            $resp['status'] = 'failed';
            $resp['error'] = 'Unkown ' . $path . ' path';
        }
        return json_encode($resp);
    }

    function renew_rent() {
        extract($_POST);
        $qry = $this->conn->query("SELECT * FROM `rent_list` where id ='{$id}'");
        $res = $qry->fetch_array();
        switch ($res['rent_type']) {
            case 1:
                $date_end = " `date_end`='" . date("Y-m-d", strtotime($res['date_end'] . ' +1 month')) . "' ";
                break;
            case 2:
                $date_end = " `date_end`='" . date("Y-m-d", strtotime($res['date_end'] . ' +3 month')) . "' ";
                break;
            case 3:
                $date_end = " `date_end`='" . date("Y-m-d", strtotime($res['date_end'] . ' +1 year')) . "' ";
                break;
            default:
                # code...
                break;
        }
        $update = $this->conn->query("UPDATE `rent_list` set {$date_end}, date_rented = date_end where id = '{$id}' ");
        if ($update) {
            $resp['status'] = 'success';
            $this->settings->set_flashdata('success', " Orden actualizada exitósamente");
        } else {
            $resp['status'] = 'failed';
            $resp['error'] = $this->conn->error;
        }
        return json_encode($resp);
    }

}

$Master = new Master();
$action = !isset($_GET['f']) ? 'none' : strtolower($_GET['f']);
$sysset = new SystemSettings();
switch ($action) {
    case 'save_supplier':
        echo $Master->save_supplier();
        break;
    case 'get_supplier_discount':
        echo $Master->get_supplier_discount();
        break;
    
    case 'delete_pdf':
        echo $Master->delete_pdf();
        break;
    case 'delete_req':
        echo $Master->delete_req();
        break;
    case 'delete_supplier':
        echo $Master->delete_supplier();
        break;
    case 'save_postal_code':
        echo $Master->save_postal_code();
        break;
    case 'save_item':
        echo $Master->save_item();
        break;
    case 'delete_item':
        echo $Master->delete_item();
        break;
    case 'search_items':
        echo $Master->search_items();
        break;
    case 'get_req_info':
        echo $Master->get_req_info();
        break;
    case 'save_po':
        echo $Master->save_po();
        break;
    case 'save_req':
        echo $Master->save_req();
        break;
    case 'save_pdf':
        echo $Master->save_pdf();
        break;
    case 'delete_po':
        echo $Master->delete_po();
        break;
    case 'get_price':
        echo $Master->get_price();
        break;
    case 'save_rent':
        echo $Master->save_rent();
        break;
    case 'delete_rent':
        echo $Master->delete_rent();
        break;
    case 'renew_rent':
        echo $Master->renew_rent();
        break;

    default:
        // echo $sysset->index();
        break;
}