<?php
class Products
{
	private $pdo;

	public $Id;
    public $ProductName;
    public $Price;
	public $Qty;
	public $Category;
	public $Tax;
	public $Brand;
	public $Barcode;
	public $id_category;
	public $id_state;
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
			$stm = $this->pdo->prepare("SELECT 
											id_product,
											description,
											price,
											qty,
											estado
											FROM products
											JOIN state on products.id_state = state.id_state
										LIMIT 1000");
			$stm->execute();
			return $stm->fetchAll(PDO::FETCH_OBJ);
			
		}
		catch(Exception $e)
		{
			//Obtener mensaje de error.
			die($e->getMessage());
		}
	}

	public function Obtener($Id)
	{
		try
		{
			//Sentencia SQL para selección de datos utilizando
			//la clausula Where para especificar el id del producto.
			$stm = $this->pdo->prepare("SELECT * FROM products WHERE id_product = ?");
			//Ejecución de la sentencia SQL utilizando el parámetro id_product.
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


	public function Actualizar(Products $data)
	{
		try
		{
			//Sentencia SQL para actualizar los datos.
			$sql = "UPDATE products SET
						description     = ?,
						price        	= ?,
            			qty       		= ?,
						id_category		= ?,
						id_tax			= ?,
						brand		    = ?,
						barcode			= ?
				    WHERE id_product 	= ?";
			//Ejecución de la sentencia a partir de un arreglo.
			$this->pdo->prepare($sql)
			     ->execute(
				    array(
                        $data->Nombre,
                        $data->Price,
                        $data->Qty,
                        $data->Category,
						$data->Tax,
						$data->Brand,
						$data->Barcode,
						$data->Id
					)
				);
		} catch (Exception $e)
		{
			die($e->getMessage());
		}
	}


	public function mdlViewCategory()
	{
		try
		{
			$stm = DB::connect()->prepare("SELECT * FROM categories");		
			$stm->execute();
			return $stm->fetchAll(PDO::FETCH_ASSOC);
		} catch (Exception $e)
		{
			die($e->getMessage());
		}
	}


	public function mdlViewTax()
	{
		try
		{
			$stm = DB::connect()->prepare("SELECT * FROM taxs");		
			$stm->execute();
			return $stm->fetchAll(PDO::FETCH_ASSOC);
		} catch (Exception $e)
		{
			die($e->getMessage());
		}
	}


	public function ValidateAddProduct(Products $data)
	{
		try
		{
			$stm = $this->pdo->prepare("SELECT id_product FROM products WHERE barcode = ?");
			$stm->execute(array($data->Barcode));
			return $stm->fetch(PDO::FETCH_OBJ);

		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}
	public function Buscar($criterio)
	{
		$result = array();
		try
		{
			$query = "select * from products where concat_ws('|||', upper(description), upper(brand), upper(barcode)) like :id and status = 1 " . (strlen($criterio)<=0 ? "limit 10": "") .
				" union " .
				" select * from products where id_product=:id_product or price=:price or qty=:qty and status = 1 " . (strlen($criterio)<=0 ? "limit 10;": ";");
			$stm = $this->pdo->prepare($query);

			$str = '%' . strtoupper($criterio) . '%';
			$stm->bindParam(':id', $str);
			$stm->bindParam(':id_product', $criterio);
			$stm->bindParam(':price', $criterio);
			$stm->bindParam(':qty', $criterio);
			$stm->execute();
			$result = $stm->fetchAll(PDO::FETCH_OBJ);

		}
		catch(Exception $e)
		{
			//Obtener mensaje de error.
			die($e->getMessage());
		}
//var_dump($result);
		return $result;
	}

	public function Registrar(Products $data)
	{
		try
		{
			//Sentencia SQL.
			$sql = "INSERT INTO products (description, price, qty, id_category, id_tax, brand, barcode)
		        VALUES (?, ?, ?, ?, ?, ?, ?)";

			$this->pdo->prepare($sql)
		     ->execute(
				array(
                    $data->ProductName,
                    $data->Price,
					$data->Qty,
					$data->Category,
					$data->Tax,
					$data->Brand,
					$data->Barcode
                )
			);
		} catch (Exception $e)
		{
			die($e->getMessage());
		}
	}

	public function BuscarPorId($id)
	{
		try
		{
			//Sentencia SQL.
			$sql = "SELECT * FROM products where id_product = ? limit 1";

			$stmt = $this->pdo->prepare($sql);
			$stmt->execute(array($id));
			return $stmt->fetch(PDO::FETCH_OBJ);
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
			$sql = "UPDATE products SET
						id_state			= ?
				    WHERE id_product 		= ?";
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
