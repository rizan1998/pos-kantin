<?php 
    $no = 1;
    foreach ($item as $i): ?>
        <tr>
            <td><?php echo $no++ ?></td>
            <td><?php echo $i['name']?></td>
            <td class="text-center"><?php echo $i['qty']?></td>
            <td class="text-right"><?php echo curr_format($i['price'])?></td>
            <td class="text-right"><?php echo curr_format($i['discount'])?></td>

        </tr>
<?php endforeach ?>
