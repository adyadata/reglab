<?php

// UserID
// TglJam
// BuktiBayar
// JumlahBayar
// Acc

?>
<?php if ($t_daftarm->Visible) { ?>
<!-- <h4 class="ewMasterCaption"><?php echo $t_daftarm->TableCaption() ?></h4> -->
<table id="tbl_t_daftarmmaster" class="table table-bordered table-striped ewViewTable">
<?php echo $t_daftarm->TableCustomInnerHtml ?>
	<tbody>
<?php if ($t_daftarm->_UserID->Visible) { // UserID ?>
		<tr id="r__UserID">
			<td><?php echo $t_daftarm->_UserID->FldCaption() ?></td>
			<td<?php echo $t_daftarm->_UserID->CellAttributes() ?>>
<span id="el_t_daftarm__UserID">
<span<?php echo $t_daftarm->_UserID->ViewAttributes() ?>>
<?php echo $t_daftarm->_UserID->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($t_daftarm->TglJam->Visible) { // TglJam ?>
		<tr id="r_TglJam">
			<td><?php echo $t_daftarm->TglJam->FldCaption() ?></td>
			<td<?php echo $t_daftarm->TglJam->CellAttributes() ?>>
<span id="el_t_daftarm_TglJam">
<span<?php echo $t_daftarm->TglJam->ViewAttributes() ?>>
<?php echo $t_daftarm->TglJam->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($t_daftarm->BuktiBayar->Visible) { // BuktiBayar ?>
		<tr id="r_BuktiBayar">
			<td><?php echo $t_daftarm->BuktiBayar->FldCaption() ?></td>
			<td<?php echo $t_daftarm->BuktiBayar->CellAttributes() ?>>
<span id="el_t_daftarm_BuktiBayar">
<span>
<?php echo ew_GetFileViewTag($t_daftarm->BuktiBayar, $t_daftarm->BuktiBayar->ListViewValue()) ?>
</span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($t_daftarm->JumlahBayar->Visible) { // JumlahBayar ?>
		<tr id="r_JumlahBayar">
			<td><?php echo $t_daftarm->JumlahBayar->FldCaption() ?></td>
			<td<?php echo $t_daftarm->JumlahBayar->CellAttributes() ?>>
<span id="el_t_daftarm_JumlahBayar">
<span<?php echo $t_daftarm->JumlahBayar->ViewAttributes() ?>>
<?php echo $t_daftarm->JumlahBayar->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($t_daftarm->Acc->Visible) { // Acc ?>
		<tr id="r_Acc">
			<td><?php echo $t_daftarm->Acc->FldCaption() ?></td>
			<td<?php echo $t_daftarm->Acc->CellAttributes() ?>>
<span id="el_t_daftarm_Acc">
<span<?php echo $t_daftarm->Acc->ViewAttributes() ?>>
<?php echo $t_daftarm->Acc->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
	</tbody>
</table>
<?php } ?>
