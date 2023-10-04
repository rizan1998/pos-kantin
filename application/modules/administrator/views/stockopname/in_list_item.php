<?php
$no = 1;
foreach ($item as $i) : ?>
    <tr>
        <td><?php echo $no++ ?></td>
        <td><?php echo $i['name'] ?></td>
        <td>Rp. <?php echo $i['price_sell'] != null ? $i['price_sell'] : 0; ?></td>
        <td class="text-center"><?php echo $i['stock_physic'] ?></td>
        <td class="text-center"><?php echo $i['differential'] ?></td>

    </tr>
<?php endforeach ?>