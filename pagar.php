<?php
    include 'global/config.php';
    include 'global/conexion.php';
    include 'carrito.php';
    include 'templates/cabecera.php';
?>

<?php
if($_POST){
    $total = 0;
    $SID = session_id();
    $correo = $_POST['email'];

    foreach($_SESSION['CARRITO'] as $indice => $producto){
        $total = $total + ($producto['precio'] * $producto['cantidad']);
    }
    $sentencia = $pdo->prepare("INSERT INTO `tblventas` (`id`, `clavetransaccion`, `paypaldatos`, `fecha`, `correo`, `total`, `status`) VALUES (NULL, :clavetransaccion, '', NOW(), :correo, :total, 'pendiente');");

    $sentencia->bindParam(":clavetransaccion",$SID);
    $sentencia->bindParam(":correo",$correo);
    $sentencia->bindParam(":total",$total);
    $sentencia->execute();
    $idVenta = $pdo->lastInsertId();

    foreach($_SESSION['CARRITO'] as $indice => $producto){
        $sentencia = $pdo->prepare("INSERT INTO `tbldetalleventa` (`id`, `idventa`, `idproducto`, `preciounitario`, `cantidad`, `descargado`) VALUES (NULL, :idventa, :idproducto, :preciounitario, :cantidad, '0');");

        $sentencia->bindParam(":idventa",$idVenta);
        $sentencia->bindParam(":idproducto",$producto['id']);
        $sentencia->bindParam(":preciounitario",$producto['precio']);
        $sentencia->bindParam(":cantidad",$producto['cantidad']);
        $sentencia->execute();
    }
    //echo "<h3>" . $total . "</h3>";
}
?>

<!-- Include the PayPal JavaScript SDK -->
<script src="https://www.paypal.com/sdk/js?client-id=AT7mmv4z3C5mzM1GBn03ma5APi4woOPd7YFxhUZZp7VQftFXDXTD9dyISKBP3d326f9tiJG7MWHrCDKN&currency=MXN"></script>

<style> /* validar que este correcto */
        /* Media query for mobile viewport */
        @media screen and (max-width: 400px) {
            #paypal-button-container {
                width: 100%;
            }
        }
        
        /* Media query for desktop viewport */
        @media screen and (min-width: 1920px) {
            #paypal-button-container {
                width: 250px;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh; /* Añadido para ocupar toda la altura de la ventana */            }
        }
</style>

<div class="container">
  <div class="row">
    <div class="col-md-12">
      <div class="p-5 mb-4 bg-dark text-white text-center">
        <h1 class="display-4">!Ya casi es tuyo!</h1>
        <p class="lead">Estás a punto de pagar en PayPal la cantidad de:</p>
            <h4> $<?php echo number_format($total,2); ?> </h4> <br/>
            <div id="paypal-button-container"></div>
        </p>
    
        <p>Los productos podrán ser descargados una vez se haya completado el pago <br/>
            <strong> (Para aclaraciones: robert.mtz.a.08@gmail.com) </strong>
        </p>
      </div>
    </div>
  </div>
</div>

<script>
        // Render the PayPal button into #paypal-button-container
        paypal.Buttons({
            // Call your server to set up the transaction
            createOrder: function(data, actions) {
                return fetch('/demo/checkout/api/paypal/order/create/', {
                    method: 'post'
                }).then(function(res) {
                    return res.json();
                }).then(function(orderData) {
                    return orderData.id;
                });
            },

            // Call your server to finalize the transaction
            // Reemplaza la función onApprove actual
            onApprove: function(data, actions) {
                return fetch('/demo/checkout/api/paypal/order/' + data.orderID + '/capture/', {
                    method: 'post'
                }).then(function(res) {
                    return res.json();
                }).then(function(orderData) {
                    var errorDetail = Array.isArray(orderData.details) && orderData.details[0];

                    if (errorDetail && errorDetail.issue === 'INSTRUMENT_DECLINED') {
                        return actions.restart();
                    }

                    if (errorDetail) {
                        var msg = 'Sorry, your transaction could not be processed.';
                        if (errorDetail.description) msg += '\n\n' + errorDetail.description;
                        if (orderData.debug_id) msg += ' (' + orderData.debug_id + ')';
                        return alert(msg);
                    }

                    // Puedes personalizar el mensaje de agradecimiento
                    var thankYouMessage = '¡Gracias por tu pago! Tu transacción se ha completado con éxito.';
                    var element = document.getElementById('paypal-button-container');
                    element.innerHTML = '<h3>' + thankYouMessage + '</h3>';

                    // Aquí puedes agregar más lógica, como redirigir al usuario a una página de confirmación.
                    // Por ejemplo, puedes usar window.location.href = 'URL-de-confirmacion.html';

                    console.log('Capture result', orderData, JSON.stringify(orderData, null, 2));
                });
            }

        }).render('#paypal-button-container');
    </script>

<?php
    include 'templates/footer.php';
?>