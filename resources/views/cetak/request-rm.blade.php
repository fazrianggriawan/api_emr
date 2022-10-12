<style>
    .container {
        position: fixed;
        width: 270px;
        height: 100%;
        top: 0;
        left: 0;
        font-family: Arial, Helvetica, sans-serif;
        line-height: 16px;
        font-size: 14px;
    }

    table tr td {
        vertical-align: top;
        font-size: 12px;
    }
</style>
<div class="container">
    <div style="font-weight: bold; margin-bottom: 5px; border-bottom: 1px solid black; text-align: center; padding-bottom: 10px;">
        <div>RUMAH SAKIT SALAK - BOGOR</div>
        <div style="margin-top: 0.25rem;">PERMINTAAN STATUS REKAM MEDIS</div>
    </div>
    <table>
        <tr>
            <td>No.Antrian</td>
            <td>:</td>
            <td>
                <?php if($data->r_registrasi->id_jns_perawatan == 'rj'){  ?>
                <?php echo $data->r_registrasi_antrian->r_antrian->prefix; ?>-<?php echo $data->r_registrasi_antrian->r_antrian->nomor; ?>
                <?php } ?>
            </td>
        </tr>
        <tr>
            <td width="60">No.RM</td>
            <td width="10">:</td>
            <td><?php echo $data->r_pasien->norm ?></td>
        </tr>
        <tr>
            <td>Nama</td>
            <td>:</td>
            <td><?php echo strtoupper($data->r_pasien->nama) ?></td>
        </tr>
        <tr>
            <td>Ruangan</td>
            <td>:</td>
            <td>
                <?php echo '('.strtoupper($data->r_ruangan->jns_perawatan).') '.strtoupper($data->r_ruangan->name) ?> <br>
            </td>
        </tr>
        <tr>
            <td>No.Registrasi</td>
            <td>:</td>
            <td>
                <?php echo $data->noreg; ?>
            </td>
        </tr>
        <tr>
            <td>Tgl - Jam</td>
            <td>:</td>
            <td><?php echo strtoupper($data->dateCreated) ?></td>
        </tr>
    </table>
    <div style="margin-top: 10px; margin-left: 5px;">
        <?php echo $qrcode; ?>
    </div>
</div>

<script>
    // window.print();
</script>