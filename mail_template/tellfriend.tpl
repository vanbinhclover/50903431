<?php
function email_temp($data){
return<<<EOF

<table width="100%" border="0" cellspacing="2" cellpadding="2">
  <tr>
    <td><p align="justify">Xin chao {$data['r_name']},</p>
      <p align="justify">{$data['message']}</p>
	  <p align="justify">~~~~~~~~~~~~~~~~~~~~~~~~~~</p>
<p align="justify">De xem bai viet nay xin vui long click vao link duoi day:</p>
<p align="justify">{$data['linkpro']}</p>
<p align="justify">~~~~~~~~~~~~~~~~~~~~~~~~~~</p>
      <p align="justify">&nbsp;</p>
	  <p align="justify">Email nay duoc goi tu {$data['s_name']} ({$data['s_email']})</p>
	  <p align="justify">&nbsp;</p>
    </td>
  </tr>
  <tr>
    <td align="center">
	<p align="left"><font color="#99CC33">&nbsp;</font>
	<div align="left">
	  <p>TRE TODAY - www.tretoday.com</p>
	  </div></td>
  </tr>
</table>
EOF;
}