<?php
class Provider
{
	private $pdo;

	public $Id;
	public $Nombre;
	public $Cuil;
	public $Telefono;
    public $Calle;
    public $Numero;
	public $Email;
	public $estado;

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

	public function Listar()
	{
		try
		{
			$result = array();
			
			$stm = $this->pdo->prepare("SELECT `id_provider`, `company_name`, `cuit_cuil`,`tel_number`,`street`, `number`, estado 
										FROM `provider`
										JOIN state on provider.id_state = state.id_state");
			
			$stm->execute();

			return $stm->fetchAll(PDO::FETCH_OBJ);
			
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}

	public function Obtener($Id)
	{
		try
		{
			$stm = $this->pdo->prepare("SELECT * FROM provider WHERE id_provider = ?");

			$stm->execute(array($Id));
			return $stm->fetch(PDO::FETCH_OBJ);
		} catch (Exception $e)
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

	public function ValidateAddProvider(Provider $data)
	{
		try
		{
			$stm = $this->pdo->prepare("SELECT id_provider FROM provider WHERE cuit_cuil = ?");
			$stm->execute(array($data->Cuil));
			return $stm->fetch(PDO::FETCH_OBJ);
			
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}

	public function EmailExist(Provider $data)
	{
		try
		{
			$stm = $this->pdo->prepare("SELECT id_provider FROM provider WHERE email = ?");
			$stm->execute(array($data->Email));
			return $stm->fetch(PDO::FETCH_OBJ);
			
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}

	public function Actualizar(Provider $data)
	{
		try
		{
			$sql = "UPDATE provider SET
						cuit_cuil		=?,
						tel_number		=?,
            			street			=?,
						number			=?,
						company_name	=?,
						email			=?
				    WHERE id_provider	=?";
			
			$this->pdo->prepare($sql)
			     ->execute(
				    array(
                        $data->Cuil,
                        $data->Telefono,
                        $data->Calle,
                        $data->Numero,
						$data->Nombre,
						$data->Email,
						$data->Id
					)
				);
		} catch (Exception $e)
		{
			die($e->getMessage());
		}
	}

	public function Registrar(Provider $data)
	{
		try
		{
			
			$sql = "INSERT INTO provider (cuit_cuil,tel_number,street,number,company_name,email)
		        VALUES (?, ?, ?, ?, ?, ?)";

			$this->pdo->prepare($sql)
		     ->execute(
				array(
                    $data->Cuil,
                    $data->Telefono,
					$data->Calle,
					$data->Numero,
					$data->Nombre,
					$data->Email,
                )	
			);
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
			$sql = "UPDATE provider SET
						id_state			= ?
				    WHERE id_provider 		= ?";
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

    public function BuscarPorCUITCUIL($cuit_cuil)
    {
        try
        {
            $stm = $this->pdo->prepare("SELECT id_provider id_customer, cuit_cuil, company_name customer_name, tel_number, street, number address_number FROM provider WHERE cuit_cuil = ?");

            $stm->execute(array($cuit_cuil));
            return $stm->fetch(PDO::FETCH_OBJ);
        } catch (Exception $e)
        {
            die($e->getMessage());
        }
    }
}
?>