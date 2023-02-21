    <table class="table table-hover">
        <tbody>
        <?php 
            $total = 0;
            $totalitem = 0;
            $discount = 0;
            if(count($list) == 0 ){
                echo '';
            }else {
                foreach($list as $l){
                    $harga = $l['price'];
                    echo '<tr>
                    <td>'.$l['name'].'</td>
                    <td class="text-center">'.$l['qty'].'</td>
                    <td class="text-right">
                    '.curr_format($l['qty'] * $l['price']).
                    '</td>
                    <td><a style="color: red" href="'.base_url().'administrator/selling/delete_item_list/'.$l['selling_id'].'/'.$l['items_id'].'"><i class="fa fa-trash"></i></a></td>
                    </tr>';
                    $total += $harga * $l['qty'];
                    $totalitem += $l['qty'];
                    $discount += $l['discount'];
                }
            }

            $totalpembayaran = ($total - $discount);
        ?>
        </tbody>
    </table>

    <div style="
        bottom: 0;
        left: 0;
        width: 100%
    ">
        <table class="table">
            <tr style="border-top: 2px solid #000; font-size: 10pt">
                <td>Subtotal</td>
                <td class="text-center"><?php echo curr_format($totalitem) ?></td>
                <td class="text-right"><?php echo curr_format($total) ?></td>
            </tr>
            <tr style="font-size: 10pt; color: red">
                <td>Disc</td>
                <td></td>
                <td class="text-right">-<?php echo curr_format($discount) ?></td>
            </tr>
            <tr style="font-size: 12pt; font-weight: bold">
                <td>TOTAL BAYAR</td>
                <td></td>
                <td class="text-right"><?php echo curr_format($total - $discount) ?></td>
            </tr>
        </table>
        <div class="btn-group" role="group" aria-label="Basic example" style="width: 100%;">
            <?php if($total != 0): ?>
                <button type="button" class="btn btn-primary" style="height: 60px;" onclick="savetransaction()">SIMPAN</button>
                <button type="button" class="btn btn-warning" style="height: 60px;" data-toggle="modal" data-target="#modal-transaction">BAYAR</button>
            <?php else: ?>
                <button type="button" class="btn btn-primary" disabled style="height: 60px;" >SIMPAN</button>
                <button type="button" class="btn btn-warning" disabled style="height: 60px;" data-toggle="modal" data-target="#modal-transaction">BAYAR</button>
            <?php endif ?>
        </div>

    </div>


    <!-- Modal -->
    <div class="modal fade" id="modal-transaction" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Pembayaran</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <table width="100%" border="0" class="table">
                            <tr style="font-size: 12pt; font-weight: bold; margin: 10px">
                                <td>JENIS PEMBAYARAN</td>
                                <td colspan="2">
                                    <select name="jenis_pembayaran" id="jenis_pembayaran" class="form-control">
                                        <?php foreach ($type_paid as $val): ?>
                                        <option value="<?php echo $val['inc_id']?>"><?php echo $val['name']?></option>
                                        <?php endforeach ?>
                                    </select>
                                </td>
                            </tr>
                            <tr style="font-size: 12pt; font-weight: bold">
                                <td>TOTAL BAYAR</td>
                                <td></td>
                                <td class="text-right"><?php echo curr_format($totalpembayaran) ?></td>
                                <input type="hidden" class="form-control" name="subtotal" value="<?php echo $total ?>" id="subtotal">
                                <input type="hidden" class="form-control" name="total_pembayaran" value="<?php echo $totalpembayaran ?>" id="total_pembayaran">
                                <input type="hidden" class="form-control" name="discount" value="<?php echo $discount ?>" id="discount">
                            </tr>
                            <tr style="font-size: 12pt; font-weight: bold">
                                <td>BAYAR</td>
                                <td colspan="2">
                                    <input type="number" class="form-control" name="bayar" value="0" id="bayar" onkeyup="kembalian()">
                                </td>
                            </tr>

                            <tr style="font-size: 14pt; font-weight: bold; color: red">
                                <td>KEMBALIAN</td>
                                <td></td>
                                <td class="text-right"><span id="kembalian"></span></td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="save-pembayaran" onclick="savepembayaran(event)">SIMPAN</button>
                </div>
            </div>
        </div>
    </div>