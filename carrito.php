<?php
session_start();
$mensaje = "";

    if(isset($_POST['btnAccion'])){
        switch($_POST['btnAccion']){
            case 'Agregar':
                if(is_numeric( openssl_decrypt( $_POST['id'], COD, KEY,))){
                    $ID = openssl_decrypt( $_POST['id'], COD, KEY,);
                    $mensaje.= "Ok ID Correcto" . $ID . "<br/>";
                } else{
                    $mensaje.= "Upss, ID Incorrecto" . $ID . "<br/>";
                }

                if( is_string( openssl_decrypt( $_POST['nombre'], COD, KEY,))){
                    $NOMBRE = openssl_decrypt( $_POST['nombre'], COD, KEY,);
                    $mensaje = "Nombre Correcto" . $NOMBRE . "<br/>";
                } else{
                    $mensaje.= "Upss, Nombre Incorrecto" . $NOMBRE . "<br/>";
                    break;
                }

                if( is_numeric( openssl_decrypt( $_POST['cantidad'], COD, KEY,))){
                    $CANTIDAD = openssl_decrypt( $_POST['cantidad'], COD, KEY,);
                    $mensaje = "Cantidad Correcto" . $CANTIDAD . "<br/>";
                } else{
                    $mensaje.= "Upss, Cantidad Incorrecta" . $CANTIDAD . "<br/>";
                    break;
                }

                if( is_numeric( openssl_decrypt( $_POST['precio'], COD, KEY,))){
                    $PRECIO = openssl_decrypt( $_POST['precio'], COD, KEY,);
                    $mensaje = "Precio Correcto" . $PRECIO . "<br/>";
                } else{
                    $mensaje.= "Upss, Precio Incorrecto" . $PRECIO . "<br/>";
                    break;
                }

                if( !isset($_SESSION['CARRITO'])){
                    $producto = array(
                        'id' => $ID,
                        'nombre' => $NOMBRE,
                        'cantidad' => $CANTIDAD,
                        'precio' => $PRECIO
                    );
                    $_SESSION['CARRITO'][0] = $producto;
                    $mensaje = "Producto agregado al carrito";

                } else{
                    $idProductos = array_column($_SESSION['CARRITO'], "id");

                    if(in_array($ID, $idProductos)){
                        echo "<script> alert('El Producto ya ha sido seleccionado...'); </script>";
                        $mensaje = "";
                    }else{
                    $numeroProductos = count($_SESSION['CARRITO']);

                    $producto = array(
                        'id' => $ID,
                        'nombre' => $NOMBRE,
                        'cantidad' => $CANTIDAD,
                        'precio' => $PRECIO
                    );
                    $_SESSION['CARRITO'][$numeroProductos] = $producto;
                    $mensaje = "Producto agregado al carrito";
                    }
                }

            break;

            case "Eliminar":
                if(is_numeric( openssl_decrypt( $_POST['id'], COD, KEY,))){
                    $ID = openssl_decrypt( $_POST['id'], COD, KEY,);
                    //$mensaje.= "Ok ID Correcto" . $ID . "<br/>";
                    foreach($_SESSION['CARRITO'] as $indice=>$producto){
                        if( $producto['id'] === $ID){
                            unset($_SESSION['CARRITO'][$indice]);
                            echo "<script> alert('Elemento Borrado...'); </script>";
                        }
                    }

                } else{
                    $mensaje.= "Upss, ID Incorrecto" . $ID . "<br/>";
                }
            break;
        }
    }

?>