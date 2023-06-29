<?php

class Empresa {
    // Atributos
    private $id;
    private $nombre;
    private $direccion;
    private $mensajeOperacion;

    // Método constructor
    public function __construct() {
        $this->id = 0;
        $this->nombre = "";
        $this->direccion = "";
    }

    public function cargar($nombre, $direccion) {
        $this->setNombre($nombre);
        $this->setDireccion($direccion);
    }

    // Métodos set
    public function setId($id) {
        $this->id = $id;
    }
    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }
    public function setDireccion($direccion) {
        $this->direccion = $direccion;
    }
    public function setMensajeOperacion($mensajeOperacion) {
		$this->mensajeOperacion = $mensajeOperacion;
	}

    // Métodos get
    public function getId() {
        return $this->id;
    }
    public function getNombre() {
        return $this->nombre;
    }
    public function getDireccion() {
        return $this->direccion;
    }
    public function getMensajeOperacion() {
		return $this->mensajeOperacion;
	}

    public function insertar() {
		$base = new BaseDatos();
		$resp = false;
		$consultaInsertar = "INSERT INTO empresa(enombre,edireccion) 
				VALUES ('".$this->getNombre()."','".$this->getDireccion()."')";
		if ($base->Iniciar()) {
			if ($id = $base->devuelveIDInsercion($consultaInsertar)) {
                $this->setId($id);
			    $resp = true;
			} else {
	    		$this->setMensajeOperacion($base->getError());			
			}
		} else {
			$this->setMensajeOperacion($base->getError());
		}
		return $resp;
	}
	
	public function modificar() {
	    $resp = false; 
	    $base = new BaseDatos();
		$consultaModificar = "UPDATE empresa SET enombre='".$this->getNombre()."'
                           ,edireccion='".$this->getDireccion()."' WHERE idempresa=".$this->getId();
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
	
	public function eliminar() {
		$base = new BaseDatos();
		$resp = false;
		if ($base->Iniciar()) {
				$consultaBorrar="DELETE FROM empresa WHERE idempresa=".$this->getId();
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

	function buscar() {
		$base = new BaseDatos();
		$consultaEmpresa = "SELECT * FROM empresa";
		$resp = false;
		if ($base->Iniciar()) {
			if ($base->Ejecutar($consultaEmpresa)) {
				if ($fila = $base->Registro()) {
				    if ($fila['idempresa'] != "") {
						$idEmpresa = $fila['idempresa'];
						$nombre = $fila['enombre'];
						$direccion = $fila['edireccion'];
						$this->cargar($nombre,$direccion);
						$this->setId($idEmpresa);
						$resp = true;
					}
				}
		 	} else {
		 		$this->setmensajeoperacion($base->getError());		 		
			}
		} else {
		 	$this->setmensajeoperacion($base->getError());		 	
		}		
		return $resp;
	}

    // Método toString
    public function __toString() {
        $cadena = "ID: " . $this->getId() . "\n";
        $cadena .= "Nombre: " . $this->getNombre() . "\n";
        $cadena .= "Dirección: " . $this->getDireccion() . "\n";
        return $cadena;
    }
}
?>