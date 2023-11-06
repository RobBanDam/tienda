<?php
    include 'global/config.php';
    include 'carrito.php';
    include 'templates/cabecera.php';
?>

    
    <h3>Lista del Carrito</h3>
        <?php if( !empty($_SESSION['CARRITO'])) { ?>
        <table class="table table-ligth table-bordered">
            <tbody>
                <tr>
                    <th width="40%">Descripción</th>
                    <th width="15%" class="text-center">Cantidad</th>
                    <th width="20%" class="text-center">Precio</th>
                    <th width="20%" class="text-center">Total</th>
                    <th width="5%">--</th>
                </tr>

                <?php $total = 0; ?>

                <?php foreach($_SESSION['CARRITO'] as $indice => $producto) { ?>
                    <tr>
                        <td width="40%"> <?php echo $producto['nombre'];?> </td>
                        <td width="15%" class="text-center"> <?php echo $producto['cantidad'];?> </td>
                        <td width="20%" class="text-center"> $ <?php echo $producto['precio'];?> </td>
                        <td width="20%" class="text-center"> $ <?php echo number_format( $producto['precio'] * $producto['cantidad'],2); ?> </td>
                        <td width="5%"> 
                            <form action="" method="post">
                                <input type="hidden" 
                                name="id" 
                                value=" <?php echo openssl_encrypt($producto['id'], COD, KEY);?> ">

                                <button 
                                    type="submit" 
                                    class="btn btn-danger"
                                    name="btnAccion"
                                    value="Eliminar"
                                >Eliminar</button>
                            </form>
                        </td>
                    </tr>
                <?php $total = $total + ( $producto['precio'] * $producto['cantidad']); ?>
                <?php } ?>

                <tr>
                    <td colspan="3" align="right"> <h3>Total</h3> </td>
                    <td align="right"> <h3>$ <?php echo number_format($total,2);?></h3> </td>
                    <td></td>
                </tr>

                <tr>
                    <td colspan="5">
                        <form action="/../tienda/pagar.php" method="post">
                            <div class="alert alert-success" role="alert">
                                <div class="form-group">
                                    <label for="my-input">Correo del Contacto: </label>
                                    <input id="email" 
                                        name="email" 
                                        class="form-control" 
                                        type="email" 
                                        placeholder="Por favor escribe tu correo" 
                                        required
                                    >
                                </div>
                                <small id="emailHelp" class="form-text text-muted">
                                    Los Productos se enviarán a este correo
                                </small>
                            </div>
                            <div class="d-grid gap-2">
                                <button type="submit" value="proceder" name="btnAccion" class="btn btn-primary btn-lg btn-block"> Proceder a pagar >> </button>
                            </div>
                            
                        </form>
                    </td>
                </tr>

            </tbody>
        </table>

        <?php }else{ ?>
            <div class="alert alert-success" role="alert">
                No hay productos en el Carrito...
            </div>
        <?php } ?>

<?php
    include 'templates/footer.php';
?>