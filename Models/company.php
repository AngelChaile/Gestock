<?php

class Company
{
	private $pdo;

	public $Name;
    public $CompanyName;
    public $Cuit;
	public $TelNumber;
	public $Email;
	public $Address;

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

    //Esta funcion busca los datos de la compania a partir del nombre
    public function Info()
    {
        try
		{
			$stm = $this->pdo->prepare("SELECT * FROM business_info");
			$stm->execute();
			return $stm->fetch(PDO::FETCH_OBJ);
			
		}
		catch(Exception $e)
		{
			//Obtener mensaje de error.
			die($e->getMessage());
		}
    }

	public function Actualizar(Company $data)
	{
		try
		{
			//Sentencia SQL para actualizar los datos.
			$sql = "UPDATE business_info SET
						cuit            = ?,
						name            = ?,
            			company_name    = ?,
						tel_number		= ?,
						email 	        = ?,
						address	        = ?";
			//Ejecución de la sentencia a partir de un arreglo.
			$this->pdo->prepare($sql)
			     ->execute(
				    array(
                        $data->Cuit,
                        $data->Name,
                        $data->CompanyName,
                        $data->TelNumber,
						$data->Email,
						$data->Address,
					)
				);
		} catch (Exception $e)
		{
			die($e->getMessage());
		}
	}

}
?>