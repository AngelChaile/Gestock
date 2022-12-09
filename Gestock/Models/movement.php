<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

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

    public static function EnviarOne($nombre, $email, $comentario, $pdf)
    {
        require 'Assets/PHPMailer/Exception.php';
        require 'Assets/PHPMailer/PHPMailer.php';
        require 'Assets/PHPMailer/SMTP.php';
        //Se instancia un objeto de la clase PHPMailer
	    $mail = new PHPMailer(true);
    
        //Declaración de variables para almacenar los datos ingresados por el usuario en cada input del formulario. Recordar que se accede por el "name" del input.
        
        /*$nombreCompleto = $_POST['nombre'];
        $email          = $_POST['email'];
        $comentario     = $_POST['comentario'];
        $pdf            = $_FILES['my_file'];*/

        $nombreCompleto = $nombre;

        try {
            //Configuración del servidor
            $mail->SMTPDebug = 0; //Se habilita la depuración, si se utiliza un servidor local colocar $mail->SMTPDebug = 0;
            $mail->isSMTP();       //Se utiliza el protocolo SMTP
            $mail->Host       = 'smtp.gmail.com';  //Colocar aquí el servidor de correo a utilizar, en el ejemplo smtp de gmail
            $mail->SMTPAuth   = true;     //Se habilita la autenticación smtp
            $mail->Username   = 'angelchaile90@gmail.com'; //Colocar aquí una dirección de correo valida, debe pertenecer al servidor indicado arriba
            $mail->Password   = 'hlywotyvwogdkqpw'; //Colocar aquí la contraseña
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         //Habilita el cifrado TLS; se recomienda `PHPMailer::ENCRYPTION_SMTPS` 
            $mail->Port       = 587;                                    //Número del puerto utilizado

        
            $mail->setFrom('angelchaile90@gmail.com', 'Gestock'); //Desde donde se envía el mail, el nombre es opcional
            $mail->addAddress($email, $nombreCompleto);     //A quién se le envía el mail, el nombre es opcional
            //$mail->addAddress('ellen@example.com');  //información opcional
            //$mail->addReplyTo('info@example.com', 'Information');
            //$mail->addCC('cc@example.com');
            //$mail->addBCC('bcc@example.com');

            //Las siguiente líneas se utilizan si se desea enviar archivos
            //$mail->addAttachment($pdf['tmp_name'], $pdf['name']/*'/var/tmp/file.tar.gz'*/);         //Agrega archivos adjuntos
            $mail->addStringAttachment($pdf, "Tiquet$nombreCompleto.pdf", PHPMailer::ENCODING_BASE64,'application/pdf');
            // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    

            //Contenido
            $mail->isHTML(true);                     //Si se envía con formato HTML
            $mail->Subject = 'Hola '.$nombreCompleto.' aqui tiene su PDF con los movimientos';  //Asunto del mensaje
            $mail->Body    = "<p style='color:blue; text-align: center; margin-top: 100px;'>
                                Muchas Gracias por todo!<br> Equipo Gestock.</center></p>"; //Mensaje a enviar
        

            $mail->send(); //Se envía el mail
        } catch (Exception $e) {
            throw new InvalidQuantityException("Ocurrió un error al enviar el email", 100, new Exception(""));
        }
    }
    
}
?>
