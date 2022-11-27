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

    public function EnviarOne(){
        //capturo los datos
        $email          = $_REQUEST["email"];
        $nombreCompleto = $_REQUEST["nombre"];

        //construyo el cuerpo del mensaje
        $message        = "Nombre: " . $nombreCompleto . "\n";
        $message        = $message . "Email: " . $email . "\n";

        //obtener datos del archivo subido
        $file_tmp_name  =        $_FILES['my_file']['tmp_name'];
        $file_name      =        $_FILES['my_file']['name'];
        $file_size      =        $_FILES['my_file']['size'];
        $file_type      =        $_FILES['my_file']['type'];

        $handle         = fopen($file_tmp_name, "r");
        $content        = fread($handle, $file_size);
        fclose($handle);
        $encoded_content = chunk_split(base64_encode($content));

        $boundary = md5("pera");

        //Encabezados
        $headers        = "MIME-Version: 1.0\r\n";
        $headers       .= "From:".$email."\r\n"; 
        $headers       .= "Reply-To: ".$email."" . "\r\n";
        $headers       .= "Content-Type: multipart/mixed; boundary = $boundary\r\n\r\n";

        //Texto Plano
        $body           = "--$boundary\r\n";
        $body          .= "Content-Type: text/plain; charset=ISO-8859-1\r\n";
        $body          .= "Content-Transfer-Encoding: base 64\r\n\r\n";
        $body          .= chunk_split(base64_encode($message));

        //Adjunto
        $body          .= "--$boundary\r\n";
        $body          .="Content-Type: $file_type; name=".$file_name."\r\n";
        $body          .="Content-Disposition: attachment; filename=".$file_name."\r\n";
        $body          .="Content-Transfer-Encoding: base64\r\n";
        $body          .="X-Attachment-Id: ".rand(1000, 99999)."\r\n\r\n";
        $body          .= $encoded_content;

        $subject        = "PDF-Movimientos Gestock"; 

        //Enviando el email
        $sentMail = mail($email, $subject, $body, $headers);
        if($sentMail){
            echo"<p style='color:green; text-align: center; margin-top: 100px;'
                Formulario enviado, revisar el Email</center></p>";
        }else {
            echo "<h2> Se produjo un herror y su pedido no pudo ser enviado</h2>";
        }

    }
}
?>
