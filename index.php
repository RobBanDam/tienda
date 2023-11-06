<?php
    include 'global/config.php';
    include 'global/conexion.php';
    include 'carrito.php';
    include 'templates/cabecera.php';
?>

        <br>

        <?php if($mensaje != "") { ?>
        <div class="alert alert-success" role="alert">
            <?php echo $mensaje; ?>
            <a href="/../tienda/mostrarCarrito.php" class="badge text-bg-success">Ver Carrito</a>
        </div>
        <?php } ?>

        <div class="row">

        <?php
            $sentencia = $pdo->prepare("SELECT * FROM `tblproductos`");
            $sentencia -> execute();
            $listaProductos = $sentencia->fetchAll(PDO::FETCH_ASSOC);
            //print_r($listaProductos);
        ?>

        <?php foreach($listaProductos as $producto) { ?>
            <div class="col-3">
                <div class="card">
                  <img 
                    title="<?php echo $producto['nombre'];?>" 
                    class="card-img-top" 
                    src="<?php echo $producto['imagen'];?>" 
                    alt="<?php echo $producto['nombre'];?>"
                    data-bs-toggle="popover"
                    data-bs-trigger="hover"
                    data-bs-content="<?php echo $producto['descripcion'];?>"
                    height="317px"
                  >
                  <div class="card-body">
                    <span><?php echo $producto['nombre'];?></span>
                    <h4 class="card-title">$<?php echo $producto['precio'];?></h4>
                    <p class="card-text">Descripción</p>

                    <form action="" method="post">
                        <input type="hidden" name="id" id="id" value="<?php echo openssl_encrypt($producto['id'], COD, KEY);?>">
                        <input type="hidden" name="nombre" id="nombre" value="<?php echo openssl_encrypt($producto['nombre'], COD, KEY);?>">
                        <input type="hidden" name="precio" id="precio" value="<?php echo openssl_encrypt($producto['precio'], COD, KEY);?>">
                        <input type="hidden" name="cantidad" id="cantidad" value="<?php echo openssl_encrypt(1, COD, KEY);?>">

                        <button 
                            type="submit" 
                            class="btn btn-primary" 
                            name="btnAccion" 
                            value="Agregar">
                            Agregar al Carrito
                        </button>
                    </form>

                  </div>
                </div>
            </div>
        
        <?php } ?>

        </div>

    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"      integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

    <script>
        var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
        var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl)})
    </script>

<?php
    include 'templates/footer.php';
?>