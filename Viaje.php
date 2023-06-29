<?php

class Viaje {
    // Atributos
    private $idViaje;
    private $destino;
    private $cantMaxPasajeros;
    private $objResponsable;
    private $colPasajeros;
    private $importe;
    private $objEmpresa;
    private $mensajeOperacion;

    // Método constructor
    public function __construct() {
        $this->idViaje = 0;
        $this->destino = "";
        $this->cantMaxPasajeros = 0;
        $this->objResponsable = null;
        $this->colPasajeros = [];
        $this->importe = 0;
        $this->objEmpresa = null;
    }

    public function cargar($destino, $cantMaxPasajeros, $objResponsable, $colPasajeros, $importe, $objEmpresa) {
        $this->setDestino($destino);
        $this->setCantMaxPasajeros($cantMaxPasajeros);
        $this->setObjResponsable($objResponsable);
        $this->setColPasajeros($colPasajeros);
        $this->setImporte($importe);
        $this->setObjEmpresa($objEmpresa);
    }

    // Métodos set
    public function setIdViaje($idViaje) {
        $this->idViaje = $idViaje;
    }
    public function setDestino($destino) {
        $this->destino = $destino;
    }
    public function setCantMaxPasajeros($cantMaxPasajeros) {
        $this->cantMaxPasajeros = $cantMaxPasajeros;
    }
    public function setObjResponsable($objResponsable) {
        $this->objResponsable = $objResponsable;
    }
    public function setColPasajeros($colPasajeros) {
        $this->colPasajeros = $colPasajeros;
    }
    public function setImporte($importe) {
        $this->importe = $importe;
    }
    public function setMensajeOperacion($mensajeOperacion) {
		$this->mensajeOperacion = $mensajeOperacion;
	}
    public function setObjEmpresa($objEmpresa) {
		$this->objEmpresa = $objEmpresa;
    }

    // Métodos get
    public function getIdViaje() {
        return $this->idViaje;
    }
    public function getDestino() {
        return $this->destino;
    }
    public function getCantMaxPasajeros() {
        return $this->cantMaxPasajeros;
    }
    public function getObjResponsable() {
        return $this->objResponsable;
    }
    public function getColPasajeros() {
        return $this->colPasajeros;
    }
    public function getImporte() {
        return $this->importe;
    }
    public function getMensajeOperacion() {
		return $this->mensajeOperacion;
	}
    public function getObjEmpresa() {
		return $this->objEmpresa;
    }

    /** Busca los datos de un viaje
	 * @param INT $id
	 * @return BOOLEAN, true en caso de encontrar los datos, false en caso contrario 
	 */	
    public function buscar($id){
		$base = new BaseDatos();
		$consultaPersona = "SELECT * FROM viaje WHERE idviaje=".$id;
		$resp = false;
		if($base->Iniciar()){
			if($base->Ejecutar($consultaPersona)){
				if ($fila = $base->Registro()) {
                    // No use cargar viaje debido a error de SQL: Fatal error: Uncaught mysqli_sql_exception: Too many connections
                    $this->setIdViaje($id);
					$this->setDestino($fila['vdestino']);
					$this->setCantMaxPasajeros($fila['vcantmaxpasajeros']);
                    $objResponsable = new Responsable;
                    $objResponsable->buscar($fila['rnumeroempleado']);
					$this->setObjResponsable($objResponsable);
					$this->setImporte($fila['vimporte']);
                    $objEmpresa = new Empresa;
                    $objEmpresa->buscar();
                    $this->setObjEmpresa($objEmpresa);
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

    public function insertar() {
        $base = new BaseDatos();
        $resp = false;
        $consultaInsertar = "INSERT INTO viaje (vdestino, vcantmaxpasajeros, idempresa, rnumeroempleado, vimporte) 
                VALUES ('".$this->getDestino()."',".$this->getCantMaxPasajeros().",".$this->getObjEmpresa()->getId().",".$this->getObjResponsable()->getNumEmpleado().",".$this->getImporte().")";
        if ($base->Iniciar()) {
            if ($id = $base->devuelveIDInsercion($consultaInsertar)) {
                $this->setIdViaje($id);
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
        $consultaModificar = "UPDATE viaje SET vdestino='".$this->getDestino()."',vcantmaxpasajeros='".$this->getCantMaxPasajeros()."'
                           ,idempresa=".$this->getObjEmpresa()->getId().",rnumeroempleado=".$this->getObjResponsable()->getNumEmpleado().",vimporte=".$this->getImporte()." WHERE idviaje=".$this->getIdViaje();
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
                $consultaBorrar = "DELETE FROM viaje WHERE idviaje=".$this->getIdViaje();
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

    public function listar($condicion){
	    $arregloViajes = null;
		$base=new BaseDatos();
		$consultaViajes="SELECT * FROM viaje ";
		if ($condicion!=""){
		    $consultaViajes=$consultaViajes.' WHERE '.$condicion;
		}
		$consultaViajes.=" ORDER BY idviaje ";
		if($base->Iniciar()){
			if($base->Ejecutar($consultaViajes)){				
				$arregloViajes= array();
				while($fila = $base->Registro()){
                    // no use buscar viaje debido a error de SQL: Fatal error: Uncaught mysqli_sql_exception: Too many connections
				    $objEmpresa = new Empresa();
                    $objResponsable = new Responsable();
                    $pasajeroAux = new Pasajero();
                    $idViaje = $fila['idviaje'];
                    $destino = $fila['vdestino'];
                    $cantMaxPasajeros = $fila['vcantmaxpasajeros'];
                    $objEmpresa->buscar($fila['idempresa']);
                    $objResponsable->buscar($fila['rnumeroempleado']);
                    $importe = $fila['vimporte'];
                    $viajeAux = new Viaje();
                    $viajeAux->setIdViaje($idViaje);
                    $listaPasajeros = $pasajeroAux->listar("idviaje=".$viajeAux->getIdViaje());
                    $viajeAux->cargar($destino,$cantMaxPasajeros,$objResponsable,$listaPasajeros,$importe,$objEmpresa);
                    array_push($arregloViajes,$viajeAux);
				}							
		 	} else {
		 		$this->setmensajeoperacion($base->getError());		 		
			}
		} else {
		 	$this->setmensajeoperacion($base->getError());		 	
		}	
		return $arregloViajes;
	}

    // Método toString
    public function __toString() {
        $cadena = "Código del viaje: " . $this->getIdViaje() . "\n";
        $cadena .= "Destino: " . $this->getDestino() . "\n";
        $cadena .= "Cantidad máxima de pasajeros: " . $this->getCantMaxPasajeros() . "\n";
        $cadena .= "Datos del responsable:\n" . $this->getObjResponsable();
        $arrayPasajeros = $this->getColPasajeros();
        if (count($arrayPasajeros) != 0) {
            $cadena .= "Colección de pasajeros\n";
            for ($i = 0; $i < count($arrayPasajeros); $i++) {
                $unPasajero = $arrayPasajeros[$i];
                $cadena .= "---- Pasajero N°" . ($i+1) . " ----\n";
                $cadena .= $unPasajero;
            }
        } else {
            $cadena .= "Este viaje aún no tiene pasajeros.\n";
        }
        $cadena .= "Importe: " . $this->getImporte() . "\n";
        return $cadena;
    }
}
?>