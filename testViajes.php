<?php
// Incluye las clases necesarias
include_once 'BaseDatos.php';
include_once 'Empresa.php';
include_once 'Pasajero.php';
include_once 'Responsable.php';
include_once 'Viaje.php';
// Crea los objetos
$objEmpresa = new Empresa();
$objResponsable = new Responsable();
$objViaje = new Viaje();
$objPasajero = new Pasajero();
// Carga los datos de la empresa en caso de que haya datos en la base de datos
$objEmpresa->buscar();
do {
    // Se muestra el menú de opciones
    echo "\n---------- Menú de opciones ----------\n";
    echo "-------------- EMPRESA ---------------\n";
    echo "1-  Ingresar los datos\n";
    echo "2-  Modificar los datos\n";
    echo "3-  Eliminar los datos\n";
    echo "4-  Mostrar los datos\n";
    echo "------------ RESPONSABLES ------------\n";
    echo "5-  Ingresar los datos\n";
    echo "6-  Modificar los datos\n";
    echo "7-  Eliminar los datos\n";
    echo "8-  Mostrar los datos\n";
    echo "--------------- VIAJES ---------------\n";
    echo "9-  Ingresar los datos\n";
    echo "10- Modificar los datos\n";
    echo "11- Eliminar los datos\n";
    echo "12- Mostrar los datos\n";
    echo "-------------- PASAJEROS -------------\n";
    echo "13- Ingresar los datos\n";
    echo "14- Modificar los datos\n";
    echo "15- Eliminar los datos\n";
    echo "16- Mostrar los datos\n";
    echo "--------------------------------------\n";
    echo "Otro- Salir\n";
    echo "Elección: ";
    $opcionElegida = trim(fgets(STDIN));
    
    // Guarda todos los responsables que estan en la base de datos
    $arrayResponsables = $objResponsable->listar("");
    // Guarda todos los viajes que estan en la base de datos
    $arrayViajes = $objViaje->listar("");
    // Guarda todos los pasajeros que estan en la base de datos
    $arrayPasajeros = $objPasajero->listar("");
    switch ($opcionElegida) {
        case 1:
            // Verifica si ya hay datos de empresa en la base de datos
            if ($objEmpresa->buscar()) {
                echo "No se pudo crear una empresa porque ya existe una, si la desea modificar use '2'.";
            } else {
                echo "Ingrese el nombre de la empresa: ";
                $nombreEmpresa = trim(fgets(STDIN));
                echo "Ingrese la dirección de la empresa: ";
                $direccionEmpresa = trim(fgets(STDIN));
                $objEmpresa->cargar($nombreEmpresa, $direccionEmpresa);
                if ($objEmpresa->insertar()) {
                    echo "La empresa ha sido correctamente cargada.";
                } else {
                    echo "No se pudo agregar la empresa debido al siguiente error: " . $objEmpresa->getMensajeOperacion();
                }
            }
            sleep(3);
            break;
        case 2:
            // Verifica que haya datos de empresa en la base de datos
            if ($objEmpresa->buscar()) {
                echo "Ingrese el nuevo nombre de la empresa: ";
                $nombreEmpresa = trim(fgets(STDIN));
                echo "Ingrese la nueva dirección de la empresa: ";
                $direccionEmpresa = trim(fgets(STDIN));
                $objEmpresa->setNombre($nombreEmpresa);
                $objEmpresa->setDireccion($direccionEmpresa);
                if ($objEmpresa->modificar()) {
                    echo "La empresa ha sido correctamente modificada.";
                } else {
                    echo "No se pudo realizar la modificación debido al siguiente error: " . $objEmpresa->getMensajeOperacion();
                }
            } else {
                echo "No hay ninguna empresa que modificar, use '1' para añadir una.";
            }
            sleep(3);
            break;
        case 3:
            // Verifica que haya datos de empresa en la base de datos
            if ($objEmpresa->buscar()) {
                // Verifica que haya no haya ningún viaje
                if (count($arrayViajes) == 0) {
                    if ($objEmpresa->eliminar()) {
                        echo "La empresa ha sido correctamente eliminada.";
                    } else {
                        echo "No se pudo realizar la eliminación de la empresa debido al siguiente error: " . $objEmpresa->getMensajeOperacion();
                    }
                } else {
                    echo "No se pueden eliminar los datos de la empresa porque contiene al menos un viaje, elimine los viajes usando '11'";
                }
            } else {
                echo "No hay datos de empresa para eliminar, use '1' para agregar datos.";
            }
            sleep(3);
            break;
        case 4:
            // Verifica que haya datos de empresa en la base de datos
            if ($objEmpresa->buscar()) {
                echo "-------- INFORMACIÓN DE LA EMPRESA --------\n";
                echo $objEmpresa;
            } else {
                echo "No hay datos de empresa para mostrar, use '1' para agregar datos.";
            }
            sleep(3);
            break;
        case 5:
            echo "Ingrese el número de licencia: ";
            $numLicencia = trim(fgets(STDIN));
            echo "Ingrese el nombre del responsable: ";
            $rNombre = trim(fgets(STDIN));
            echo "Ingrese el apellido del responsable: ";
            $rApellido = trim(fgets(STDIN));
            $objResponsable->cargar($numLicencia, $rNombre, $rApellido);
            if ($objResponsable->insertar()) {
                echo "El responsable ha sido correctamente cargado.";
            } else {
                echo "No se pudo agregar al responsable debido al siguiente error : " . $objResponsable->getMensajeOperacion();
            }
            sleep(3);
            break;
        case 6:
            // Verifica que haya al menos un responsable en la base de datos para poder modificarlo
            if (count($arrayResponsables) != 0) {
                // Muestra todos los responsables para que el usuario elija a cual modificar
                echo "----------- Lista de responsables -----------\n";
                for ($i = 0; $i < count($arrayResponsables); $i++) {
                    $unResponsable = $arrayResponsables[$i];
                    echo "Responsable ID N°" . $unResponsable->getNumEmpleado() . ", nombre y apellido: " . $unResponsable->getNombre() . " " . $unResponsable->getApellido() . "\n";
                }
                echo "Ingrese el número de empleado del responsable que desea modificar: ";
                $numEmpleado = trim(fgets(STDIN));
                // Verifica que se haya ingresado un número de empleado válido
                if ($objResponsable->buscar($numEmpleado)) {
                    echo "Ingrese el nuevo número de licencia: ";
                    $numLicencia = trim(fgets(STDIN));
                    echo "Ingrese el nuevo nombre del responsable: ";
                    $nuevoNombre = trim(fgets(STDIN));
                    echo "Ingrese el nuevo apellido del responsable: ";
                    $nuevoApellido= trim(fgets(STDIN));
                    $objResponsable->setNumLicencia($numLicencia);
                    $objResponsable->setNombre($nuevoNombre);
                    $objResponsable->setApellido($nuevoApellido);
                    if ($objResponsable->modificar()) {
                        echo "El responsable ha sido correctamente modificadado.";
                    } else {
                        echo "No se pudo realizar la modificación del responsable debido al siguiente error: " . $objResponsable->getMensajeOperacion();
                    }
                } else {
                    echo "No existe ningún responsable con el número de empleado N°" . $numEmpleado;
                }
            } else {
                echo "No hay ningún responsable para modificar, use '5' para agregar uno.";
            }
            sleep(3);
            break;
        case 7:
            // Verifica que haya al menos un responsable para mostrar
            if (count($arrayResponsables) != 0) {
                // Muestra todos los responsables para que el usuario elija a cual eliminar
                echo "----------- Lista de responsables -----------\n";
                for ($i = 0; $i < count($arrayResponsables); $i++) {
                    $unResponsable = $arrayResponsables[$i];
                    echo "Responsable ID N°" . $unResponsable->getNumEmpleado() . ", nombre y apellido: " . $unResponsable->getNombre() . " " . $unResponsable->getApellido() . "\n";
                }
                echo "Ingrese el número de empleado del responsable que desea eliminar: ";
                $numEmpleado = trim(fgets(STDIN));
                // Verifica que ese empleado no esté en algún viaje
                $viajesDeResponsable = $objViaje->listar("rnumeroempleado=".$numEmpleado);
                if (count($viajesDeResponsable) > 0) {
                    echo "No se pudo eliminar. Antes de eliminar a un responsable, elimine los viajes a los que esta vinculado."; 
                } else {
                    // Verifica que el número de empleado ingresado sea válido
                    if ($objResponsable->buscar($numEmpleado)) {
                        if ($objResponsable->eliminar()) {
                            echo "El responsable ha sido correctamente eliminado.";
                        } else {
                            echo "No se pudo realizar la eliminación del responsable debido al siguiente error: " . $objEmpresa->getMensajeOperacion();
                        }
                    } else {
                        echo "No existe ningún responsable con el número de empleado N°" . $numEmpleado;
                    }
                }
            } else {
                echo "No existen responsables para eliminar, agregue datos usando la opción '5'";
            }
            sleep(3);
            break;
        case 8:
            // Verifica que haya al menos un responsable para eliminar
            if (count($arrayResponsables) != 0) {
                // Muestra todos los responsables
                echo "----------- INFORMACIÓN DE LOS RESPONSABLES -----------\n";
                for ($i = 0; $i < count($arrayResponsables); $i++) {
                    $unResponsable = $arrayResponsables[$i];
                    echo "---- Responsable N°" . ($i+1) . " ----\n";
                    echo $unResponsable;
                }
            } else {
                echo "No existen responsables para mostrar, agregue datos usando la opción '5'";
            }
            sleep(3);
            break;
        case 9:
            // Verifica que haya datos de empresa en la base de datos
            if ($objEmpresa->buscar()) {
                // Verifica que haya al menos un responsable en la base de datos
                if (count($arrayResponsables) != 0) {
                    echo "Ingrese el destino: ";
                    $destinoViaje = trim(fgets(STDIN));
                    echo "Ingrese la cantidad máxima de pasajeros: ";
                    $cantMaxPasajerosViaje = trim(fgets(STDIN));
                    echo "----------- Lista de responsables -----------\n";
                    for ($i = 0; $i < count($arrayResponsables); $i++) {
                        $unResponsable = $arrayResponsables[$i];
                        echo "Responsable ID N°" . $unResponsable->getNumEmpleado() . ", nombre y apellido: " . $unResponsable->getNombre() . " " . $unResponsable->getApellido() . "\n";
                    }
                    echo "Ingrese el número de empleado del responsable: ";
                    $numEmpleadoResponsableViaje = trim(fgets(STDIN));
                    if ($objResponsable->buscar($numEmpleadoResponsableViaje)) {
                        echo "Ingrese el importe: ";
                        $importeViaje = trim(fgets(STDIN));
                        $objViaje->cargar($destinoViaje, $cantMaxPasajerosViaje, $objResponsable, [],$importeViaje, $objEmpresa);
                        if ($objViaje->insertar()) {
                            echo "El viaje ha sido correctamente cargado.";
                        } else {
                            echo "No se pudo agregar el viaje debido al siguiente error: " . $objEmpresa->getMensajeOperacion();
                        }
                    } else {
                        echo "No existe ningún viaje con el ID N°" . $numEmpleadoResponsableViaje;
                    }
                } else {
                    echo "No hay responsables, antes de crear el viaje, cree al menos un responsable usando '5'.";
                }
            } else {
                echo "No hay datos de empresa, antes de crear un viaje, use '1' para crear una empresa.";
            }
            sleep(3);
            break;
        case 10:
            // Verifica que haya datos al menos un viaje para modificar en la base de datos
            if (count($arrayViajes) != 0) {
                // Muestra todos los viajes para que el usuario elija cual desea modificar
                echo "-------------- Lista de viajes --------------\n";
                for ($i = 0; $i < count($arrayViajes); $i++) {
                    $unViaje = $arrayViajes[$i];
                    echo "Viaje ID N°" . $unViaje->getIdViaje() . ", destino: " . $unViaje->getDestino() . ", máximo de pasajeros: " . $unViaje->getCantMaxPasajeros() . ", importe: $" . $unViaje->getImporte() . "\n";
                }
                echo "Ingrese el ID del viaje que desea modificar: ";
                $idViaje = trim(fgets(STDIN));
                // Verifica que el ID ingresado sea válido
                if ($objViaje->buscar($idViaje)) {
                    echo "Ingrese el nuevo destino del viaje: ";
                    $destinoViaje = trim(fgets(STDIN));
                    echo "Ingrese la nueva cantidad máxima de pasajeros: ";
                    $cantMaxPasajerosViaje = trim(fgets(STDIN));
                    // Muestra todos los responsables para que el usuario elija cual va a estar vinculado a este viaje
                    echo "----------- Lista de responsables -----------\n";
                    for ($i = 0; $i < count($arrayResponsables); $i++) {
                        $unResponsable = $arrayResponsables[$i];
                        echo "Responsable ID N°". $unResponsable->getNumEmpleado(). ", nombre y apellido: ".$unResponsable->getNombre(). " ". $unResponsable->getApellido()."\n";
                    }
                    echo "Ingrese el número de empleado del nuevo responsable: ";
                    $numEmpleadoResponsableViaje = trim(fgets(STDIN));
                    // Verifica que el número de empleado sea válido
                    if ($objResponsable->buscar($numEmpleadoResponsableViaje)) {
                        echo "Ingrese el nuevo importe: ";
                        $importeViaje = trim(fgets(STDIN));
                        $objViaje->setDestino($destinoViaje);
                        $objViaje->setCantMaxPasajeros($cantMaxPasajerosViaje);
                        $objViaje->setObjResponsable($objResponsable);
                        $objViaje->setObjEmpresa($objEmpresa);
                        $objViaje->setImporte($importeViaje);
                        if ($objViaje->modificar()) {
                            echo "El viaje ha sido correctamente modificado.";
                        } else {
                            echo "No se pudo realizar la modificación del viaje debido al siguiente error: " . $objResponsable->getMensajeOperacion();
                        }
                    } else {
                        echo "No existe ningún responsable con el número de empleado N°" . $numEmpleadoResponsableViaje;
                    }
                } else {
                    echo "No existe ningun viaje con el ID N°" . $idViaje;
                }
            } else {
                echo "No hay ningún viaje para modificar, use '9' para añadir uno.";
            }
            sleep(3);
            break;
        case 11:
            // Verifica que haya datos al menos un viaje para eliminar en la base de datos
            if (count($arrayViajes) != 0) {
                // Muestra todos los viajes para que el usuario elija cual desea eliminar
                echo "-------------- Lista de viajes --------------\n";
                for ($i = 0; $i < count($arrayViajes); $i++) {
                    $unViaje = $arrayViajes[$i];
                    echo "Viaje ID N°" . $unViaje->getIdViaje() . ", destino: " . $unViaje->getDestino() . ", máximo de pasajeros: " . $unViaje->getCantMaxPasajeros() . ", importe: $" . $unViaje->getImporte() . "\n";
                }
                echo "Ingrese el ID del viaje que desea borrar: ";
                $idViaje = trim(fgets(STDIN));
                // Verifica que el ID ingresado sea válido
                if ($objViaje->buscar($idViaje)) {
                    // Verifica que el viaje no contenga algún pasajero
                    $pasajerosDelViaje = $objPasajero->listar("idviaje =".$idViaje);
                    if (count($pasajerosDelViaje) == 0){
                        if ($objViaje->eliminar()) {
                            echo "El viaje se eliminó correctamente";
                        } else {
                            echo "No se pudo realizar la eliminación debido al siguiente error: " . $objEmpresa->getMensajeOperacion();
                        }
                    } else {
                        echo "No se puede eliminar un viaje que contenga pasajeros. Elimine los pasajeros del viaje antes de eliminar el viaje.";
                    }
                } else {
                    echo "No existe ningun viaje con el ID N°" . $idViaje;
                }
            } else {
                echo "No hay ningún viaje para eliminar, use '9' para agregar uno.";
            }
            sleep(3);
            break;
        case 12:
            // Verifica que haya datos al menos un viaje para mostrar en la base de datos
            if (count($arrayViajes) != 0) {
                // Muestra todos los viajes
                echo "-------------- DATOS DE LOS VIAJES --------------\n";
                for ($i = 0; $i < count($arrayViajes); $i++) {
                    $unViaje = $arrayViajes[$i];
                    echo "\n---- Viaje N°" . ($i+1) . " ----\n";
                    echo $unViaje;
                }
            } else {
                echo "No hay ningún viaje para mostrar, use '9' para agregar uno.";
            }
            sleep(3);
            break;
        case 13:
            // Verifica que haya datos al menos un viaje en la base de datos
            if (count($arrayViajes) != 0) {
                // Muestra todos los viajes para que el usuario elija en que viaje va a estar el pasajero
                echo "-------------- Lista de viajes --------------\n";
                for ($i = 0; $i < count($arrayViajes); $i++) {
                    echo "Viaje ID N°" . $arrayViajes[$i]->getIdViaje() . ", destino: " . $arrayViajes[$i]->getDestino() . ", importe: $" . $arrayViajes[$i]->getImporte() . "\n";
                }
                echo "Ingrese la ID del viaje en el que va a estar el pasajero: ";
                $idElegida = trim(fgets(STDIN));
                // Verifica que la ID ingresada sea válida
                if ($objViaje->buscar($idElegida)) {
                    // Verifica si el viaje al que esta intentando añadir un pasajero tiene cupo
                    $arrayPasajeros = $objPasajero->listar("idviaje=".$idElegida);
                    if (count($arrayPasajeros) < $objViaje->getCantMaxPasajeros()){
                        echo "Ingrese el número de documento: ";
                        $nDoc = trim(fgets(STDIN));
                        // Verifica si el pasajero ya existe
                        if (!$objPasajero->buscar($nDoc)) {
                            echo "Ingrese el nombre: ";
                            $nombre = trim(fgets(STDIN));
                            echo "Ingrese el apellido: ";
                            $apellido = trim(fgets(STDIN));
                            echo "Ingrese el número de teléfono: ";
                            $telefono = trim(fgets(STDIN));
                            $objPasajero->cargar($nombre, $apellido, $nDoc, $telefono, $objViaje);
                            if ($objPasajero->insertar()) {
                                echo "El pasajero ha sido correctamente cargado.";
                            } else {
                                echo "No se pudo agregar el pasajero debido al siguiente error: " . $objEmpresa->getMensajeOperacion();
                            }
                        } else {
                            echo "No se pudo añadir al pasajero porque ya está en un viaje.";
                        }
                    } else {
                        echo "El viaje al que esta intentando agregar un pasajero no tiene cupo.";
                    }
                } else {
                    echo "No hay ningún viaje con el ID N°" . $idElegida;
                }
            } else {
                echo "No hay ningún viaje, añada un viaje usando '9' antes de añadir un pasajero.";
            }
            sleep(3);
            break;
        case 14:
            // Verifica que haya al menos un pasajero en la base de datos
            if (count($arrayPasajeros) != 0) {
                echo "Ingrese el número de documento del pasajero a modificar: ";
                $nDoc = trim(fgets(STDIN));
                // Verifica que el número de documento ingresado sea válido
                if ($objPasajero->buscar($nDoc)) {
                    echo "Ingrese el nuevo nombre: ";
                    $nombre = trim(fgets(STDIN));
                    echo "Ingrese el nuevo apellido: ";
                    $apellido = trim(fgets(STDIN));
                    echo "Ingrese el nuevo número de teléfono: ";
                    $telefono = trim(fgets(STDIN));
                    $objPasajero->setNombre($nombre);
                    $objPasajero->setApellido($apellido);
                    $objPasajero->setTelefono($telefono);
                    if ($objPasajero->modificar()) {
                        echo "El pasajero ha sido correctamente modificado.";
                    } else {
                        echo "No se pudo modificar el pasajero debido al siguiente error: " . $objEmpresa->getMensajeOperacion();
                    }
                } else {
                    echo "No hay ningún pasajero con el documento N°" . $nDoc;
                }
            } else {
                echo "No hay ningun pasajero, añada uno usando '13' antes de intentar modificar un pasajero.";
            }
            sleep(3);
            break;
        case 15:
            // Verifica que haya al menos un pasajero en la base de datos
            if (count($arrayPasajeros) != 0) {
                echo "Ingrese el número de documento del pasajero que desea eliminar: ";
                $nDoc = trim(fgets(STDIN));
                // Verifica que el número de documento ingresado sea válido
                if ($objPasajero->buscar($nDoc)) {
                    if ($objPasajero->eliminar()) {
                        echo "El pasajero ha sido eliminado correctamente.";
                    } else {
                        echo "No se pudo eliminar el pasajero debido al siguiente error: " . $objEmpresa->getMensajeOperacion();
                    }
                } else {
                    echo "No existe ningun pasajero con el documento N°" . $nDoc;
                }
            } else {
                echo "No existen pasajeros para eliminar, agregue uno usando la opción '13'.";
            }
            sleep(3);
            break;
        case 16:
            // Verifica que haya al menos un pasajero en la base de datos
            if (count($arrayPasajeros) != 0) {
                // Muestra todos los pasajeros
                echo "-------------- DATOS DE LOS PASAJEROS --------------\n";
                for ($i = 0; $i < count($arrayPasajeros); $i++) {
                    $unPasajero = $arrayPasajeros[$i];
                    echo "---- Pasajero N°" . ($i+1) . " ----\n";
                    echo $unPasajero;
                }
            } else {
                echo "No existen pasajeros para mostrar, agregue uno usando la opción '13'.";
            }
            sleep(3);
            break;
    }
} while ($opcionElegida >= 1 && $opcionElegida <= 16);
?>