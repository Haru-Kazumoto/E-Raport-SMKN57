<table   border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="105">TANGGAL</td>
    <td width="549">KETERANGAN</td>
    <td width="322">FILE UPDATE</td>
    <td width="172">ALREADY</td>
  </tr>
  <tr>
    <td>2013-08-07</td>
    <td>Pemindahan style2.css ke folder js dan diganti nama menjadi um412-cssreg2.js</td>
    <td>um412-cssreg2.css, foot-adm.pp, <br>
config v2.0.php</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>2013-08-07</td>
    <td>- Penambahan Field tgl Exp Abstract<br>
      - Penambahan Field info high light</td>
    <td><p>config-inp.php, config-opr.php</p>
      <p>ALTER  TABLE  `tbconfig`  ADD  `batas_tglabstract` DATE NOT  NULL  AFTER  `batastgl_lat` ;<br>
        ALTER TABLE `tbconfig` ADD `infohl` TEXT NOT NULL ;        <br>
    </p></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>Penyempurnaan page-det.opr</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>update disc</td>
    <td><div> ALTER  TABLE  `konfirmasi_transfer`  CHANGE  `disc`  `disc` FLOAT( 10, 2  )  NOT  NULL DEFAULT  '0.00' </div></td>
    <td>&nbsp;</td>
  </tr>
</table>
