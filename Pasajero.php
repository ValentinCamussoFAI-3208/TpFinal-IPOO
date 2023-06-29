<?php

class Pasajero {
    // Atributos
    private $nombre;
    private $apellido;
    private $nDocumento;
    private $telefono;
	private $mensajeOperacion;
    private $objViaje;

    // Método constructor
    public function __construct() {
        $this->nombre = "";
        $this->apellido = "";
        $this->nDocumento = 0;
        $this->telefono = 0;
        $this->objViaje = null;
    }

	public function cargar($nombre, $apellido, $nDocumento, $telefono, $objViaje) {
        $this->setNombre($nombre);
        $this->setApellido($apellido);
        $this->setNDocumento($nDocumento);
        $this->setTelefono($telefono);
        $this->setObjViaje($objViaje);
    }

    // Métodos set
    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }
    public function setApellido($apellido) {
        $this->apellido = $apellido;
    }
    public function setNDocumento($nDocumento) {
        $this->nDocumento = $nDocumento;
    }
    public function setTelefono($telefono) {
        $this->telefono = $telefono;
    }
    public function setMensajeOperacion($mensajeOperacion){
		$this->mensajeOperacion = $mensajeOperacion;
	}
    public function setObjViaje($objViaje) {
        $this->objViaje = $objViaje;
    }

    // Métodos set
    public function getNombre() {
        return $this->nombre;
    }
    public function getApellido() {
        return $this->apellido;
    }
    public function getNDocumento() {
        return $this->nDocumento;
    }
    public function getTelefono() {
        return $this->telefono;
    }
    public function getMensajeOperacion(){
		return $this->mensajeOperacion;
	}
    public function getObjViaje() {
        return $this->objViaje;
    }

    // Método toString
    public function __toString() {
        $cadena = "Nombre: " . $this->getNombre() . "\n";
        $cadena .= "Apellido: " . $this->getApellido() . "\n";
        $cadena .= "Documento N°" . $this->getNDocumento() . "\n";
        $cadena .= "Número de teléfono: " . $this->getTelefono() . "\n";
		$cadena .= "Pertenece al viaje ID N°" . $this->getObjViaje()->getIdViaje() . "\n";
        return $cadena;
    }

    /** Busca los datos de una persona por dni
	 * @param INT $dni
	 * @return BOOLEAN, true en caso de encontrar los datos, false en caso contrario 
	 */		
    public function buscar($dni){
		$base = new BaseDatos();
		$consultaPersona = "SELECT * FROM pasajero WHERE pdocumento=".$dni;
		$resp = false;
		if($base->Iniciar()){
			if($base->Ejecutar($consultaPersona)){
				if($fila = $base->Registro()){
					// No use cargar pasajero debido a error de SQL: Fatal error: Uncaught mysqli_sql_exception: Too many connections
					$unViaje = new Viaje();
                    $this->setNDocumento($dni);
					$this->setNombre($fila['pnombre']);
					$this->setApellido($fila['papellido']);
					$this->setTelefono($fila['ptelefono']);
					$unViaje->buscar($fila['idviaje']);
					$this->setObjViaje($unViaje);
					$resp = true;
				}
		 	} else {
		 		$this->setMensajeOperacion($base->getError());
			}
		} else {
		 	$this->setMensajeOperacion($base->getError());
	    }		
		return $resp;
	}

    public function insertar(){
        $base=new BaseDatos();
        $resp= false;
        $consultaInsertar="INSERT INTO pasajero(pdocumento, pnombre, papellido, ptelefono, idviaje) 
                VALUES (".$this->getNDocumento().",'".$this->getNombre()."','".$this->getApellido()."',".$this->getTelefono().",".$this->getObjViaje()->getIdViaje().")";
        if($base->Iniciar()){
            if($base->Ejecutar($consultaInsertar)){
                $resp = true;
            } else {
                $this->setMensajeOperacion($base->getError());
            }
        } else {
            $this->setMensajeOperacion($base->getError());
        }
        return $resp;
    }

    public function modificar(){
	    $resp = false; 
	    $base = new BaseDatos();
		$consultaModificar = "UPDATE pasajero SET pdocumento =".$this->getNDocumento().",pnombre='".$this->getNombre()."',papellido='".$this->getApellido()."'
                           ,ptelefono=".$this->getTelefono().",idviaje=".$this->getObjViaje()->getIdViaje()." WHERE pdocumento=".$this->getNDocumento();
		if ($base->Iniciar()) {
			if ($base->Ejecutar($consultaModificar)) {
			    $resp = true;
			} else {
				$this->setMensajeOperacion($base->getError());
			}
		} else {
				$this->setMensajeOperacion($base->getError());	
		}
		return $resp;
	}
	
	public function eliminar(){
		$base = new BaseDatos();
		$resp = false;
		if ($base->Iniciar()) {
				$consultaBorrar = "DELETE FROM pasajero WHERE pdocumento=".$this->getNDocumento();
				if ($base->Ejecutar($consultaBorrar)) {
				    $resp = true;
				} else {
					$this->setMensajeOperacion($base->getError());
				}
		} else {
			$this->setMensajeOperacion($base->getError());
		}
		return $resp; 
	}

    public function listar($condicion=""){
	    $arregloPersona = null;
		$base = new BaseDatos();
		$consultaPersonas="SELECT * FROM pasajero ";
		if ($condicion!=""){
		    $consultaPersonas = $consultaPersonas.' WHERE '.$condicion;
		}
		$consultaPersonas.=" ORDER BY papellido ";
		if ($base->Iniciar()) {
			if ($base->Ejecutar($consultaPersonas)) {				
				$arregloPersona = array();
				while ($fila=$base->Registro()) {
					// no use buscar viaje  debido a error de SQL: Fatal error: Uncaught mysqli_sql_exception: Too many connections
					$objViaje = new Viaje();
				    $nroDoc = $fila['pdocumento'];
					$nombre = $fila['pnombre'];
					$apellido = $fila['papellido'];
					$telefono = $fila['ptelefono'];
					$objViaje->buscar($fila['idviaje']);
					$pasajero = new Pasajero();
					$pasajero->cargar($nombre,$apellido,$nroDoc,$telefono,$objViaje);
					array_push($arregloPersona,$pasajero);
				}
		 	} else {
		 		$this->setMensajeOperacion($base->getError());
			}
		} else {
		 	$this->setMensajeOperacion($base->getError()); 	
		}	
		return $arregloPersona;
	}	
}
?>