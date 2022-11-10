<?php
require_once "Models/user.php";
require_once "Models/clients.php";
require_once 'Models/product.php';
require_once "Models/ConfirmMovementException.php";

class Ticket
{
    private $pdo;

    public $id_ticket;
    public Users $user;
    public ?Clients $client;
    public ?Provider $provider;
    public $date;
    public ?array $products;
    public $id_ticket_type;
    public $id_recipient_type;

    //Método de conexión a SGBD.
    public function __CONSTRUCT()
    {
        try {
            $this->pdo = DB::connect();
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    /**
     * Crea un ticket sin productos ni medios de pagos y retorna el id del ticket creado.
     */
    public function Nuevo(): int {
        try
        {
            //Sentencia SQL.
            $sql = "INSERT INTO ticket (id_user, id_customer, id_provider)
		        VALUES (?, ?, ?)";

            $stmt = $this->pdo->prepare($sql);

            try {
                $this->pdo->beginTransaction();
                $stmt->execute(array(
                        $this->user->Id,
                        $this->client->Id,
                        $this->provider->Id,
                ));

                $id = $this->pdo->lastInsertId();
                $this->pdo->commit();
                return $id;
            } catch(PDOException $e) {
                $this->pdo->rollback();
                print "Error!: " . $e->getMessage() . "</br>";
            }
        } catch (Exception $e)
        {
            die($e->getMessage());
        }
        return -1;
    }

    public function TicketTypes() {
        try
        {
            $query = "select * from ticket_type tt where tt.tt_status = 1;";
            $stm = $this->pdo->prepare($query);
            $stm->execute();

            $types = $stm->fetchAll(PDO::FETCH_ASSOC);

            $stm->closeCursor();

            return $types;

        }
        catch(Exception $e)
        {
            //Obtener mensaje de error.
            die($e->getMessage());
        }
    }

    public function RecipientTypes() {
        try
        {
            $query = "select * from recipient_type rt;";
            $stm = $this->pdo->prepare($query);
            $stm->execute();

            $types = $stm->fetchAll(PDO::FETCH_OBJ);

            $stm->closeCursor();

            return $types;

        }
        catch(Exception $e)
        {
            //Obtener mensaje de error.
            die($e->getMessage());
        }
    }

    public function BuscarTicketEnCurso($user): ?Ticket {
        //Se debería retornar un VO y no el model

        try
        {
            $query = "select t.*, c.*, c.cuit_cuil c_cuit_cuil, c.tel_number c_tel_number, c.street c_street, c.address_number c_address_number, u.*, p.*, p.cuit_cuil p_cuit_cuil, p.tel_number p_tel_number, p.street p_street from ticket t " .
                " left join customers c on t.id_customer = c.id_customer " .
                " left join User u on t.id_user = u.Id_user " .
                " left join provider p on t.id_provider = p.id_provider " .
                " where t.id_user = :id order by date desc limit 1;";
            $stm = $this->pdo->prepare($query);

            $id_user = $user->Id_user;

            $stm->bindParam(':id', $id_user);

            $stm->execute();
            $result = $stm->fetch(PDO::FETCH_OBJ);

            $stm->closeCursor();

            return $this->mapTicketFrom($result);
        }
        catch(Exception $e)
        {
            //Obtener mensaje de error.
            die($e->getMessage());
        }
    }

    public function BuscarTicket($id_ticket): ?Ticket {
        //Se debería retornar un VO y no el model
        $ticket = null;

        try
        {
            $query = "select t.*, c.*, c.cuit_cuil c_cuit_cuil, c.tel_number c_tel_number, c.street c_street, c.address_number c_address_number, u.*, p.*, p.cuit_cuil p_cuit_cuil, p.tel_number p_tel_number, p.street p_street  from ticket t " .
                " left join customers c on t.id_customer = c.id_customer " .
                " left join User u on t.id_user = u.Id_user " .
                " left join provider p on t.id_provider = p.id_provider " .
                " where id_ticket = :id order by date desc limit 1;";

            $stm = $this->pdo->prepare($query);

            $stm->bindParam(':id', $id_ticket);

            $stm->execute();
            $result = $stm->fetch(PDO::FETCH_OBJ);

            $ticket = $this->mapTicketFrom($result);

            $stm->closeCursor();
        }
        catch(Exception $e)
        {
            //Obtener mensaje de error.
            die($e->getMessage());
        }

        return $ticket;
    }

    /**
     * Agrega un producto al listado de productos correspondientes al ticket indicado en id_ticket y retorna el ticket actualizado
     * @param $id_ticket
     * @param $id_product
     * @param $cur_price
     * @param $quantity
     * @return Ticket|null El ticket actualizado con el nuevo producto en el listado
     */
    public function agregarProductoATicket($id_ticket, $product, $quantity, $ticketType): ?Ticket
    {
        $addRemoveQuantity = $quantity;
        try
        {
            $ticket = $this->BuscarTicket($id_ticket);

            if(($ticket) != null
                && $ticket->products != null && sizeof($ticket->products) > 0) {

                $existe = false;
                foreach ($ticket->products as $t_product) {
                    if($t_product["id_product"] == $product->id_product) {
                        $quantity += $t_product["quantity"];

                        if($ticketType == 2 && $product->qty < $addRemoveQuantity) {
                            throw new InvalidQuantityException("Stock Insuficiente", 49, new Exception(""));
                        }

                        $existe = true;

                        $sql = "UPDATE ticket_product SET quantity = :quantity WHERE id_ticket_product = :itp and id_product = :id_product";

                        $stmt = $this->pdo->prepare($sql);

                        $stmt->execute([
                            ":quantity" => $quantity,
                            ":id_product" => $t_product["id_product"],
                            "itp" => $t_product["id_ticket_product"]
                        ]);

                        $stmt->closeCursor();

                        $this->ActualizarStock($addRemoveQuantity, $t_product["id_product"], $ticket->id_ticket_type);

                        break;
                    }
                }
                if(!$existe) {
                    $this->insertEnTicketProduct($id_ticket, $product->id_product, $product->price, $quantity);
                    $this->ActualizarStock($addRemoveQuantity, $product->id_product, $ticket->id_ticket_type);
                }
            } else {
                $this->insertEnTicketProduct($ticket->id_ticket, $product->id_product, $product->price, $quantity);
                $this->ActualizarStock($addRemoveQuantity, $product->id_product, $ticket->id_ticket_type);
            }
            $tec = $this->BuscarTicket($id_ticket);
            return $tec;
        } catch (Exception $e)
        {
            die($e->getMessage());
        }
    }

    public function agregarClienteATicket($id_customer, $id_provider, $id_ticket) {
        try
        {
            $sql = "UPDATE ticket SET id_customer = :id_customer, id_provider = :id_provider WHERE id_ticket = :id_ticket";

            $stmt = $this->pdo->prepare($sql);

            $stmt->execute([
                ":id_customer" => $id_customer,
                ":id_provider" => $id_provider,
                ":id_ticket" => $id_ticket
            ]);
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function ActualizarStockDeTicketAnulado($id_ticket) : ?Ticket {
        if(($ticket = $this->BuscarTicket($id_ticket)) != null
            && $ticket->products != null && sizeof($ticket->products) > 0) {

            foreach ($ticket->products as $t_product) {
                $this->ActualizarStock($t_product["quantity"], $t_product["id_product"], $ticket->id_ticket_type==1 ? 2 : 1);
            }
        }

        return $this->BuscarTicket($id_ticket);
    }

    public function EliminarTicketEnCurso($user) {
        try
        {
            $query = "SELECT id_ticket FROM ticket WHERE id_user = :id_user;";

            $stm = $this->pdo->prepare($query);
            $id_user = $user->Id_user;

            $stm->bindParam(':id_user', $id_user);

            $stm->execute();
            $result = $stm->fetch(PDO::FETCH_OBJ);

            if($result && $result->id_ticket != null) {
                $id_ticket = $result->id_ticket;

                $this->ActualizarStockDeTicketAnulado($id_ticket);

                //FIXME: Pasar los DELETEs a métodos

                try {
                    $sql = "DELETE FROM ticket_product where id_ticket = :id_ticket";

                    $stmt = $this->pdo->prepare($sql);

                    $stmt->execute([
                        ":id_ticket" => $id_ticket
                    ]);
                } catch (Exception $e) {

                }

                $sql = "DELETE FROM ticket where id_user = :id_user";

                $stmt = $this->pdo->prepare($sql);

                $stmt->execute([
                    ":id_user" => $id_user
                ]);
            }
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function EliminarProductoDeTicket($id_ticket, $id_product, $quantity) : ?Ticket {
        if(($ticket = $this->BuscarTicket($id_ticket)) != null
            && $ticket->products != null && sizeof($ticket->products) > 0) {

            if($quantity != null) {
                foreach ($ticket->products as $t_product) {
                    if ($t_product["id_product"] == $id_product) {
                        $newQuantity = $t_product["quantity"] - $quantity;

                        $sql = "UPDATE ticket_product SET quantity = :quantity WHERE id_ticket_product = :itp and id_product = :id_product";

                        $stmt = $this->pdo->prepare($sql);

                        $stmt->execute([
                            ":quantity" => $newQuantity,
                            ":id_product" => $t_product["id_product"],
                            "itp" => $t_product["id_ticket_product"]
                        ]);

                        break;
                    }
                }
            }else {
                foreach ($ticket->products as $t_product) {
                    if ($t_product["id_product"] == $id_product) {
                        $this->ActualizarStock($t_product["quantity"], $t_product["id_product"], $ticket->id_ticket_type==1 ? 2 : 1);
                    }
                }

                try {
                    $sql = "DELETE FROM ticket_product where id_product = :id_product " .
                        " and id_ticket = :id_ticket";

                    $stmt = $this->pdo->prepare($sql);

                    $stmt->execute([
                        ":id_product" => $id_product,
                        ":id_ticket" => $id_ticket
                    ]);
                } catch (Exception $e) {

                }
            }
        }

        return $this->BuscarTicket($id_ticket);
     }


    /** Métodos privados */

    /**
     * Crea un nuevo ticket y mapea los datos recuperados desde la base
     * @param $result: El resultado de la query que se va a mapear al ticket
     * @return Ticket|null El ticket generado o null en caso de no tener un resultado
     */
    private function mapTicketFrom($result): ?Ticket
    {
        $ticket = null;

        if($result) {
            $ticket = new Ticket();
            $ticket->pdo = null;
            $ticket->id_ticket = $result->id_ticket;
            $ticket->id_ticket_type = $result->id_ticket_type;

            $ticket->user = new Users();
            $ticket->user->Id = $result->Id_user;
            $ticket->user->Username = $result->user_name;
            $ticket->user->Name = $result->name;
            $ticket->user->Email = $result->email;
            $ticket->user->Rol = $result->id_rol;
            $ticket->user->Estado = $result->estado;
            $ticket->id_recipient_type = $result->id_recipient_type;

            $ticket->client = new Clients();
            if($ticket->id_recipient_type == 1) { // Cliente
                $ticket->client->Id = $result->id_customer;
                $ticket->client->Cuil = $result->c_cuit_cuil;
                $ticket->client->Nombre = $result->customer_name;
                $ticket->client->Email = $result->email;
                $ticket->client->Telefono = $result->c_tel_number;
                $ticket->client->Direccion = $result->c_street;
                $ticket->client->NumeroDireccion = $result->address_number;
            } else if($ticket->id_recipient_type == 2){ //Proveedor
                $ticket->client->Id = $result->id_provider;
                $ticket->client->Cuil = $result->p_cuit_cuil;
                $ticket->client->Nombre = $result->company_name;
                $ticket->client->Email = $result->p_email;
                $ticket->client->Telefono = $result->p_tel_number;
                $ticket->client->Direccion = $result->p_street;
                $ticket->client->NumeroDireccion = $result->number;
            }

            $ticket->date = $result->date;

            $query = "select tp.*, p.*, t.id_tax, t.description as tax_description, t.tax_value from ticket_product tp join products p on tp.id_product = p.id_product " .
                " join taxs t on p.id_tax = t.id_tax " .
                " where tp.id_ticket = :id_ticket;";
            $stm = $this->pdo->prepare($query);

            $stm->bindParam(':id_ticket', $ticket->id_ticket);
            $stm->execute();
            $ticket->products = $stm->fetchAll(PDO::FETCH_ASSOC);
        }
        return $ticket;
    }

    public function AgregarTipoTicketATicket($id_ticket_type, $ticket) {
        try
        {
            $sql = "UPDATE ticket SET id_ticket_type = :id_ticket_type WHERE id_ticket = :id_ticket";

            $stmt = $this->pdo->prepare($sql);

            $stmt->execute([
                ":id_ticket_type" => $id_ticket_type,
                ":id_ticket" => $ticket->id_ticket
            ]);
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function AgregarDestinatarioATicket($id_recipient_type, $id_ticket) {
        try
        {
            $sql = "UPDATE ticket SET id_recipient_type = :id_recipient_type WHERE id_ticket = :id_ticket";

            $stmt = $this->pdo->prepare($sql);

            $stmt->execute([
                ":id_recipient_type" => $id_recipient_type,
                ":id_ticket" => $id_ticket
            ]);
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    /************************* Método privados ************************/

    private function insertEnTicketProduct($id_ticket, $id_product, $cur_price, $quantity) {
        $sql = "INSERT INTO ticket_product (id_ticket, id_product, cur_price, quantity)
                    VALUES (?, ?, ?, ?)";

        $stmt = $this->pdo->prepare($sql);

        try {
            $this->pdo->beginTransaction();
            $stmt->execute(array(
                $id_ticket,
                $id_product,
                $cur_price,
                $quantity
            ));

            $id = $this->pdo->lastInsertId();
            $this->pdo->commit();

        } catch (PDOException $e) {
            $this->pdo->rollback();
            print "Error!: " . $e->getMessage() . "</br>";
        }

    }

    public function ActualizarStock($qty, $id_product, $ticketTypeId) {
        $dbProduct = (new Products())->BuscarPorId($id_product);

        if($ticketTypeId == 1) {
            $qty = $dbProduct->qty + $qty;
        } else if ($ticketTypeId == 2) {
            $qty = $dbProduct->qty - $qty;
            //Validar que la cantidad no haga que el total sea negativo?
        }

        try {
            $sql = "UPDATE products SET qty = :qty WHERE id_product = :id_product";

            $stmt = $this->pdo->prepare($sql);

            $stmt->execute([
                ":qty" => $qty,
                ":id_product" => $id_product
            ]);
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    /**
     * @throws ConfirmMovementException
     */
    public function ProcesarVenta($ticket)
    {
        $sqlInsertMovement = "INSERT INTO movement (ticket_number, id_user, id_customer, id_provider, id_movement_type, id_recipient_type, date) " .
                    " SELECT id_ticket, id_user, id_customer, id_provider, id_ticket_type, id_recipient_type, date from ticket where id_ticket = :id_ticket";

        $sqlInsertMovementProduct = "INSERT INTO movement_product (id_movement, id_product, cur_price, quantity) " .
            " SELECT :lastInsertMovementId, id_product, cur_price, quantity from ticket_product where id_ticket = :id_ticket";

        $stmt = $this->pdo->prepare($sqlInsertMovement);

        try {
            $this->pdo->beginTransaction();
            $stmt->execute([
                ":id_ticket" => $ticket->id_ticket,
            ]);

            $movementId = $this->pdo->lastInsertId();

            $stmt = $this->pdo->prepare($sqlInsertMovementProduct);

            $stmt->execute([
                ":id_ticket" => $ticket->id_ticket,
                ":lastInsertMovementId" => $movementId,
            ]);

            $sql = "DELETE FROM ticket_product where id_ticket = :id_ticket";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ":id_ticket" => $ticket->id_ticket
            ]);

            $sql = "DELETE FROM ticket where id_user = :id_user";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ":id_user" => $ticket->user->Id
            ]);

            $this->pdo->commit();

        } catch (PDOException $e) {
            $this->pdo->rollback();
            $error = "";
            if($e->getCode() == 23000) {
                $error = "Movimiento duplicado";
            }
            throw new ConfirmMovementException("Error al confirmar el movimiento - " . $error, 51, $e);
        }

        return $ticket;
    }
}
