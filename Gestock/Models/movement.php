<?php

class Movement
{
    private $pdo;

    //Método de conexión a SGBD.
    public function __CONSTRUCT()
    {
        try {
            $this->pdo = DB::connect();
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function Listar()
    {
        try {
            $query = "SELECT *, sum(mp.quantity) product_quantity FROM movement m left join User u on m.id_user = u.id_user " .
                " left join customers cu on m.id_customer = cu.id_customer" .
                " left join provider prv on m.id_provider = prv.id_provider " .
                " left join movement_type mt on m.id_movement_type = mt.id_movement_type " .
                " left join recipient_type rt on m.id_recipient_type = rt.id_recipient_type " .
                " left join movement_product mp on m.id_movement = mp.id_movement " .
                " group by m.id_movement ";
            $stm = $this->pdo->prepare($query);
            $stm->execute();
            $result = $stm->fetchAll(PDO::FETCH_OBJ);

            return $result;

        } catch (Exception $e) {
            //Obtener mensaje de error.
            die($e->getMessage());
        }
    }

    public function FindById($id_movement)
    {
        try {
            $query = "SELECT *, " .
                " cu.tel_number cu_tel_number, cu.street cu_street, cu.address_number cu_address_number, cu.cuit_cuil cu_cuit_cuil, ".
                " prv.tel_number prv_tel_number, prv.street prv_street, prv.number prv_number, prv.cuit_cuil prv_cuit_cuil" .
                " FROM movement m left join User u on m.id_user = u.id_user " .
                " left join customers cu on m.id_customer = cu.id_customer" .
                " left join provider prv on m.id_provider = prv.id_provider " .
                " left join movement_type mt on m.id_movement_type = mt.id_movement_type " .
                " left join recipient_type rt on m.id_recipient_type = rt.id_recipient_type " .
                " where m.id_movement = :id_movement ";
            $stm = $this->pdo->prepare($query);
            $stm->execute([":id_movement" => $id_movement]);
            $result = $stm->fetch(PDO::FETCH_OBJ);

            return $result;

        } catch (Exception $e) {
            //Obtener mensaje de error.
            die($e->getMessage());
        }
    }

    public function FindMovementProductsById($id_movement)
    {
        try {
            $query = "SELECT * FROM movement_product mp join products p on mp.id_product = p.id_product" .
                " where mp.id_movement = :id_movement ";
            $stm = $this->pdo->prepare($query);
            $stm->execute([":id_movement" => $id_movement]);
            $result = $stm->fetchAll(PDO::FETCH_OBJ);

            return $result;

        } catch (Exception $e) {
            //Obtener mensaje de error.
            die($e->getMessage());
        }
    }
}
