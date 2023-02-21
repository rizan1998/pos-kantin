<?php 
    $no = 1;
    foreach ($item as $i): ?>
        <tr>
            <td><?php echo $no++ ?></td>
            <td><?php echo $i['name']?></td>
            <td class="text-center"><?php echo $i['stock_physic']?></td>

        </tr>
<?php endforeach ?>
