<p style="color: #FFF; font-size: 20px">
    <?php echo isset($erroLexico) ? $erroLexico . "<br />" : ""; ?>

    <?php echo isset($erroSintatico) ? $erroSintatico . "<br />" : ""; ?>

    <?php echo isset($erroSemantico) ? $erroSemantico . "<br />" : ""; ?>
    <?php if (isset($existeErroSemantico)): ?>
        <?php if (!$existeErroSemantico): ?>
            <p style="color: #4cae4c"> Código compilado com sucesso !!! <p>
            <p style="color: #4cae4c"> Executando ... <p>
            <script>
                window.open("http://localhost/www/uff/materia-compiladores/mini-compilador-pascal/codigoGerado/exibir-codigo-gerado.php", "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,top=100,left=500,width=450,height=400");
            </script>
        <?php endif; ?>
    <?php endif; ?>
</p>
