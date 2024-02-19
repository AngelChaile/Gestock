<?php
class Clients
{
	private $pdo;

	public $Id;
    public $Cuil;
    public $Nombre;
	public $Email;
	public $Telefono;
	public $Direccion;
	public $NumeroDireccion;
	public $estado;

	//Método de conexión a SGBD.
	public function __CONSTRUCT()
	{ 
		try
		{
			$this->pdo = DB::connect();
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}

	//Este método selecciona todas las tuplas de la tabla
	//votante en caso de error se muestra por pantalla.
	public function Listar()
	{
		try
		{
			$result = array();
			//Sentencia SQL para selección de datos.
			$stm = $this->pdo->prepare("SELECT id_customer,cuit_cuil,customer_name,email,tel_number,street,address_number, estado
			FROM customers 
			JOIN state on customers.id_state = state.id_state LIMIT 1000");
			//Ejecución de la sentencia SQL.
			$stm->execute();
			//fetchAll — Devuelve un array que contiene todas las filas del conjunto
			//de resultados
			//var_dump($stm->fetchAll(PDO::FETCH_OBJ));exit;
			return $stm->fetchAll(PDO::FETCH_OBJ);
			
		}
		catch(Exception $e)
		{
			//Obtener mensaje de error.
			die($e->getMessage());
		}
	}

	//Este método obtiene los datos del votante a partir del documento
	//utilizando SQL.
	public function Obtener($Id)
	{
		try
		{
			//Sentencia SQL para selección de datos utilizando
			//la clausula Where para especificar el documento del votante.
			$stm = $this->pdo->prepare("SELECT * FROM customers WHERE id_customer = ?");
			//Ejecución de la sentencia SQL utilizando el parámetro documento.
			$stm->execute(array($Id));
			return $stm->fetch(PDO::FETCH_OBJ);
		} catch (Exception $e)
		{
			die($e->getMessage());
		}
	}

	public function ValidateAddClient(Clients $data)
	{
		try
		{
			$stm = $this->pdo->prepare("SELECT id_customer FROM customers WHERE cuit_cuil = ?");
			$stm->execute(array($data->Cuil));
			return $stm->fetch(PDO::FETCH_OBJ);
			
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}

	public function EmailExist(Clients $data)
	{
		try
		{
			$stm = $this->pdo->prepare("SELECT id_customer FROM customers WHERE email = ?");
			$stm->execute(array($data->Email));
			return $stm->fetch(PDO::FETCH_OBJ);
			
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}

	public function Sanitize($var)
    {
        $regexCaracteresPermitidos = '/^[0-9a-zA-Z áéíóúñÁÍÓÚÑ@_.-]+$/';
        if (preg_match($regexCaracteresPermitidos, $var)) {
            return $var;
        }
		unset($var);
    }

	public function Actualizar(Clients $data)
	{
		try
		{
			//Sentencia SQL para actualizar los datos.
			$sql = "UPDATE customers SET
						customer_name       = ?,
            			cuit_cuil        	= ?,
						tel_number        	= ?,
						street       		= ?,
						address_number      = ?,
						email				= ?
				    WHERE id_customer 		= ?";
			//Ejecución de la sentencia a partir de un arreglo.
			$this->pdo->prepare($sql)
			     ->execute(
				    array(
						$data->Nombre,
						$data->Cuil,
						$data->Telefono,
						$data->Direccion,
						$data->NumeroDireccion,
						$data->Email,
						$data->Id

					)
				);
		} catch (Exception $e)
		{
			die($e->getMessage());
		}
	}

	//Método que registra un nuevo votante a la tabla.
	public function Registrar(Clients $data)
	{
		try
		{
			//Sentencia SQL.
			$sql = "INSERT INTO customers (cuit_cuil,customer_name,email,tel_number,street,address_number)
		        VALUES (?, ?, ?, ?, ?, ?)";

			$this->pdo->prepare($sql)
		     ->execute(
				array(
                    $data->Cuil,
                    $data->Nombre,
					$data->Email,
					$data->Telefono,
					$data->Direccion,
					$data->NumeroDireccion,
                )
			);
		} catch (Exception $e)
		{
			die($e->getMessage());
		}
	}

    //Este método obtiene los datos del votante a partir del documento
    //utilizando SQL.
    public function BuscarPorCUITCUIL($number)
    {
        try
        {
            //Sentencia SQL para selección de datos utilizando
            //la clausula Where para especificar el documento del votante.
            $stm = $this->pdo->prepare("SELECT id_customer, cuit_cuil, customer_name, tel_number, street, address_number FROM customers WHERE cuit_cuil = ?"
                //." UNION " .
                //"SELECT id_provider id_customer, cuit_cuil, company_name customer_name, tel_number, street, number address_number FROM provider WHERE cuit_cuil = ?"
            );
            //Ejecución de la sentencia SQL utilizando el parámetro documento.
            $stm->execute(array($number/*, $number*/));
            return $stm->fetch(PDO::FETCH_OBJ);
        } catch (Exception $e)
        {
            die($e->getMessage());
        }
    }

	public function Eliminar($Id, $newStatus)
	{

		try
		{
			//Sentencia SQL para actualizar los datos.
			$sql = "UPDATE customers SET
						id_state			= ?
				    WHERE id_customer 		= ?";
			//Ejecución de la sentencia a partir de un arreglo.
			$this->pdo->prepare($sql)
			     ->execute(
				    array(
                        $newStatus,
                        $Id
					)
				);
		} catch (Exception $e)
		{
			die($e->getMessage());
		}
	}
}
?>