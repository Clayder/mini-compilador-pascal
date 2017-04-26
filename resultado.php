<?php if (isset($codigo)): ?>

    <!-- Button trigger modal -->
    <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#myModal">
        Array de código
    </button>

    <!-- Modal -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">
                        Array de Código
                    </h4>
                </div>
                <div class="modal-body">
                    <pre>
                        <?php print_r($arrayCodigo); ?>
                    </pre>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Button trigger modal -->
    <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#arrayTokens">
        Array de tokens
    </button>

    <!-- Modal -->
    <div class="modal fade" id="arrayTokens" tabindex="-1" role="dialog" aria-labelledby="arrayTokens">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">
                        Array de tokens
                    </h4>
                </div>
                <div class="modal-body">
                    <pre>
                        <?php print_r($lexico->getArrayTokens()); ?>
                    </pre>

                     <pre>
                        <?php print_r($lexico->getArrayTokensLinha()); ?>
                    </pre>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <table class="table table-hover table-bordered" style="margin-top: 40px;">
        <thead>
            <tr style="background-color: #ccc">
                <th class="text-center">Id</th>
                <th class="text-center">Descrição </th>
                <th class="text-center">Lexema </th>
                <th class="text-center">Palavra reservada </th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($lexico->getRelatorioTokens() as $token): ?>
                <?php if ($token['id'] != null): ?>
                    <tr class="success text-center">
                        <td><?php echo $token['id']; ?> </td>
                        <td><?php echo $token['descricao']; ?> </td>
                        <td><?php echo $token['lexema']; ?> </td>
                        <td>
                            <?php if ($token['reservado']): ?>
                                <span class='glyphicon glyphicon-ok text-success' aria-hidden='true'></span>
                            <?php else: ?>
                                <span class='glyphicon glyphicon-remove text-danger' aria-hidden='true'></span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endif; ?>
            <?php endforeach; ?>
            <?php if ($lexico->getExisteCaracterInvalido()): ?>
                <?php
                $erroLexico .= "<span style='color: red'> Erro </span> <span style='color: #23f617'> léxico </span> na linha: <span style='color: red'>";
                $erroLexico .= $lexico->getLinhaAtual() . "</span> | ";
                $erroLexico .= "Token recebido: <span style='color: red'>". $lexico->getTokenInvalido() . "</span> ";
                ?>
                <tr class="danger text-center">
                    <td> <span class='glyphicon glyphicon-remove text-danger' aria-hidden='true'></span> </td>
                    <td> Token inválido linha:  <?php echo $lexico->getLinhaAtual(); ?></td>
                    <td>  <?php echo $lexico->getTokenInvalido(); ?> </td>
                    <td>
                        <span class='glyphicon glyphicon-remove text-danger' aria-hidden='true'></span>
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
<?php endif; ?>
