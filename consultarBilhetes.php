<?php
include_once './header.php';
?>

<main id="main">
    <section id="venda-ingresso">
        <div class="container">
            <div class="py-5 text-center">
                <form class="form-signin" method="POST" action="imprimirConsulta" target="_blank">
                    <img class="mb-4" src="imgs/bilhete-buscar.svg" alt="" width="200" height="150">
                    <h1 class="h3 mb-3 font-weight-normal">Consultar bilhetes</h1>
                    <p>A consulta é feita pelo número de telefone informado na compra.</p>
                    <label for="telefone" class="sr-only">Telefone</label>
                    <input type="text" id="telefone" class="form-control phone text-center" name="telefone" placeholder="Telefone" data-mask="(00)00000-0000" maxlength="14" required autofocus>
                    
                    <button class="btn btn-lg btn-primary btn-block mt-3" type="submit">Buscar</button>
                </form>
            </div>
        </div>
    </section>
</main>

<script type="text/javascript">
    var maskBehavior = function (val) {
        return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
    },
            options = {onKeyPress: function (val, e, field, options) {
                    field.mask(maskBehavior.apply({}, arguments), options);
                }
            };

    $('.phone').mask(maskBehavior, options);
</script>


<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery-slim.min.js"><\/script>')</script>
<script src="../../assets/js/vendor/popper.min.js"></script>
<script src="../../dist/js/bootstrap.min.js"></script>
<script src="../../assets/js/vendor/holder.min.js"></script>
<script>
    // Example starter JavaScript for disabling form submissions if there are invalid fields
    (function () {
        'use strict';

        window.addEventListener('load', function () {
            // Fetch all the forms we want to apply custom Bootstrap validation styles to
            var forms = document.getElementsByClassName('needs-validation');

            // Loop over them and prevent submission
            var validation = Array.prototype.filter.call(forms, function (form) {
                form.addEventListener('submit', function (event) {
                    if (form.checkValidity() === false) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        }, false);
    })();
</script>

<style>
    .centered{
        display: flex;
        flex-direction: row;
        justify-content: center;
        align-items: center
    }

    .espacoBilhete{
        margin-right: 15px;
        margin-bottom: 15px;
    }

    .bilhete{
        background-image: url(assets/bilhete.png);
        background-size: cover;
        min-height: 400px;
        min-width: 700px;
    }
</style>

<?php
include_once './footer.php';
?>