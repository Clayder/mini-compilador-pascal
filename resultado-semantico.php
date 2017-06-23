<?php if(isset($semantico)): ?>
    <table class="table table-hover table-bordered" style="margin-top: 40px;">
        <thead>
        <tr style="background-color: #ccc">
            <th class="text-center">Id</th>
            <th class="text-center">Variável </th>
            <th class="text-center">Tipo </th>
            <th class="text-center">Nível </th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($semantico->getTabelaSimbolo() as $simbolo): ?>
                <tr class="success text-center">
                    <td><?php echo $simbolo['idToken']; ?> </td>
                    <td><?php echo $simbolo['variavel']; ?> </td>
                    <td><?php echo $simbolo['tipo']; ?> </td>
                    <td><?php echo $simbolo['nivel']; ?></td>
                </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <table class="table table-hover table-bordered" style="margin-top: 40px;">
        <thead>
        <tr style="background-color: #ccc">
            <th class="text-center">Id</th>
            <th class="text-center">Variável </th>
            <th class="text-center">Tipo </th>
            <th class="text-center">Nível </th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($semantico->getAuxTabelaSimbolo() as $simbolo): ?>
            <tr class="success text-center">
                <td><?php echo $simbolo['idToken']; ?> </td>
                <td><?php echo $simbolo['variavel']; ?> </td>
                <td><?php echo $simbolo['tipo']; ?> </td>
                <td><?php echo $simbolo['nivel']; ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
