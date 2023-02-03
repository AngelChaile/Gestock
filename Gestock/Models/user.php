<?php
class Users
{
	private $pdo;

	public $Id;
    public $Username;
    public $Userpass;
	public $Name;
	public $Email;
	public $description;
	public $Estado;

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
			
			$stm = $this->pdo->prepare("SELECT u.*, s.id_state, s.estado status, description 
			FROM User u
				join state s on u.estado = s.id_state
				JOIN roles on u.id_rol = roles.id_rol");
			
			$stm->execute();
			
			return $stm->fetchAll(PDO::FETCH_OBJ);
			
		}
		catch(Exception $e)
		{
			//Obtener mensaje de error.
			die($e->getMessage());
		}
	}

	public function ValidateAddUser(Users $data)
	{
		try
		{
			$stm = $this->pdo->prepare("SELECT Id_user FROM User WHERE email = ?");
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

	public function Validar(Users $data)
	{
		try
		{
			
			$stm = $this->pdo->prepare("SELECT * FROM User WHERE user_name = ? AND user_pass = ?");
			
			$stm->execute(array(
                $data->Username,
                $data->Userpass,
            ));
			return $stm->fetch(PDO::FETCH_OBJ);
		} catch (Exception $e)
		{
			die($e->getMessage());
		}
	}

	public function Obtener($Id)
	{
		try
		{
			$stm = $this->pdo->prepare("SELECT * FROM User WHERE Id_user = ?");
			
			$stm->execute(array($Id));
			return $stm->fetch(PDO::FETCH_OBJ);
		} catch (Exception $e)
		{
			die($e->getMessage());
		}
	}

	public function Actualizar(Users $data)
	{
		try
		{
			//Sentencia SQL para actualizar los datos.
			$sql = "UPDATE User SET
						user_name   = ?,
						name        = ?,
            			email       = ?,
						id_rol		= ?
				    WHERE Id_user 	= ?";
			//Ejecución de la sentencia a partir de un arreglo.
			$this->pdo->prepare($sql)
			     ->execute(
				    array(
                        $data->Username,
                        $data->Name,
                        $data->Email,
                        $data->Rol,
						$data->Id
					)
				);
		} catch (Exception $e)
		{
			die($e->getMessage());
		}
	}
	
	public function Registrar(Users $data)
	{
		try
		{
			
			$sql = "INSERT INTO User (user_name,user_pass,name,email,id_rol)
		        VALUES (?, ?, ?, ?, ?)";

			$this->pdo->prepare($sql)
		     ->execute(
				array(
                    $data->Username,
                    $data->Userpass,
					$data->Name,
					$data->Email,
					$data->Rol,
                )
			);
		} catch (Exception $e)
		{
			die($e->getMessage());
		}
	}

	public function ExisteEmail(Users $user)
	{
		try
		{
			$stm = $this->pdo->prepare("SELECT count(*) AS cantidad FROM User WHERE email = ?");
			
			$stm->execute(array(
                $user->Email
            ));
			$userexist = $stm->fetch(PDO::FETCH_OBJ);

			return $userexist->cantidad > 0 ? true : false;
		} catch (Exception $e)
		{
			die($e->getMessage());
		}
	}

	public function UserExist(Users $data)
	{
		try
		{
			$stm = $this->pdo->prepare("SELECT Id_user FROM User WHERE user_name = ?");
			
			$stm->execute(array($data->Username));
			return $stm->fetch(PDO::FETCH_OBJ);
		} catch (Exception $e)
		{
			die($e->getMessage());
		}
	}

	public function mdlViewRol()
	{
		try
		{
			$stm = DB::connect()->prepare("SELECT * FROM roles");		
			$stm->execute();
			return $stm->fetchAll(PDO::FETCH_ASSOC);
		} catch (Exception $e)
		{
			die($e->getMessage());
		}
	}

	public function EditPass($data)
	{
		try
		{
			$sql = "UPDATE User SET user_pass = ? WHERE Id_user = ?";
			
			$this->pdo->prepare($sql)
			     ->execute(
				    array(
                        $data->Nueva,
						$data->Id
					)
				);
		} catch (Exception $e)
		{
			die($e->getMessage());
		}
	}

	public function PassReset($Id)
	{
		try
		{
			$stm = $this->pdo
			            ->prepare("UPDATE User SET user_pass = ? WHERE Id_user = ?");

			$stm->execute(array(sha1("1234") ,$Id));
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
			$sql = "UPDATE User SET
						estado			= ?
				    WHERE id_user 		= ?";
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
		
	public function quantity($var)
	{
		try
		{
			$stm = $this->pdo->prepare("SELECT count(*) AS cantidad FROM $var");

			$stm->execute();
			$userexist = $stm->fetch(PDO::FETCH_OBJ);
			if ($userexist)
			{
				return $userexist->cantidad;
			}else
			{
				return 0;
			}

		} catch (Exception $e)
		{
			die($e->getMessage());
		}
	}

	public function quantityDay($var,$move)
	{
		try
		{
			//FIXME debería ser un between con las fechas del día
			$date = date('Y-m-d');

			$stm = $this->pdo->prepare("SELECT count(*) AS cantidad FROM $var WHERE date >= '$date' AND id_movement_type = $move");

			$stm->execute();
			$userexist = $stm->fetch(PDO::FETCH_OBJ);
			if ($userexist)
			{
				return $userexist->cantidad;
			}else
			{
				return 0;
			}
			
		} catch (Exception $e)
		{
			die($e->getMessage());
		}
	}
}
?>
