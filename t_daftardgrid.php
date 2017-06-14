<?php include_once "t_userinfo.php" ?>
<?php

// Create page object
if (!isset($t_daftard_grid)) $t_daftard_grid = new ct_daftard_grid();

// Page init
$t_daftard_grid->Page_Init();

// Page main
$t_daftard_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$t_daftard_grid->Page_Render();
?>
<?php if ($t_daftard->Export == "") { ?>
<script type="text/javascript">

// Form object
var ft_daftardgrid = new ew_Form("ft_daftardgrid", "grid");
ft_daftardgrid.FormKeyCountName = '<?php echo $t_daftard_grid->FormKeyCountName ?>';

// Validate form
ft_daftardgrid.Validate = function() {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	var $ = jQuery, fobj = this.GetForm(), $fobj = $(fobj);
	if ($fobj.find("#a_confirm").val() == "F")
		return true;
	var elm, felm, uelm, addcnt = 0;
	var $k = $fobj.find("#" + this.FormKeyCountName); // Get key_count
	var rowcnt = ($k[0]) ? parseInt($k.val(), 10) : 1;
	var startcnt = (rowcnt == 0) ? 0 : 1; // Check rowcnt == 0 => Inline-Add
	var gridinsert = $fobj.find("#a_list").val() == "gridinsert";
	for (var i = startcnt; i <= rowcnt; i++) {
		var infix = ($k[0]) ? String(i) : "";
		$fobj.data("rowindex", infix);
		var checkrow = (gridinsert) ? !this.EmptyRow(infix) : true;
		if (checkrow) {
			addcnt++;
			elm = this.GetElements("x" + infix + "_PraktikumID");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $t_daftard->PraktikumID->FldCaption(), $t_daftard->PraktikumID->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_Tgl");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $t_daftard->Tgl->FldCaption(), $t_daftard->Tgl->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_Tgl");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($t_daftard->Tgl->FldErrMsg()) ?>");

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
		} // End Grid Add checking
	}
	return true;
}

// Check empty row
ft_daftardgrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "PraktikumID", false)) return false;
	if (ew_ValueChanged(fobj, infix, "Tgl", false)) return false;
	return true;
}

// Form_CustomValidate event
ft_daftardgrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ft_daftardgrid.ValidateRequired = true;
<?php } else { ?>
ft_daftardgrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
ft_daftardgrid.Lists["x_PraktikumID"] = {"LinkField":"x_PraktikumID","Ajax":true,"AutoFill":false,"DisplayFields":["x_Nama","x_Biaya","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"t_praktikum"};

// Form object for search
</script>
<?php } ?>
<?php
if ($t_daftard->CurrentAction == "gridadd") {
	if ($t_daftard->CurrentMode == "copy") {
		$bSelectLimit = $t_daftard_grid->UseSelectLimit;
		if ($bSelectLimit) {
			$t_daftard_grid->TotalRecs = $t_daftard->SelectRecordCount();
			$t_daftard_grid->Recordset = $t_daftard_grid->LoadRecordset($t_daftard_grid->StartRec-1, $t_daftard_grid->DisplayRecs);
		} else {
			if ($t_daftard_grid->Recordset = $t_daftard_grid->LoadRecordset())
				$t_daftard_grid->TotalRecs = $t_daftard_grid->Recordset->RecordCount();
		}
		$t_daftard_grid->StartRec = 1;
		$t_daftard_grid->DisplayRecs = $t_daftard_grid->TotalRecs;
	} else {
		$t_daftard->CurrentFilter = "0=1";
		$t_daftard_grid->StartRec = 1;
		$t_daftard_grid->DisplayRecs = $t_daftard->GridAddRowCount;
	}
	$t_daftard_grid->TotalRecs = $t_daftard_grid->DisplayRecs;
	$t_daftard_grid->StopRec = $t_daftard_grid->DisplayRecs;
} else {
	$bSelectLimit = $t_daftard_grid->UseSelectLimit;
	if ($bSelectLimit) {
		if ($t_daftard_grid->TotalRecs <= 0)
			$t_daftard_grid->TotalRecs = $t_daftard->SelectRecordCount();
	} else {
		if (!$t_daftard_grid->Recordset && ($t_daftard_grid->Recordset = $t_daftard_grid->LoadRecordset()))
			$t_daftard_grid->TotalRecs = $t_daftard_grid->Recordset->RecordCount();
	}
	$t_daftard_grid->StartRec = 1;
	$t_daftard_grid->DisplayRecs = $t_daftard_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$t_daftard_grid->Recordset = $t_daftard_grid->LoadRecordset($t_daftard_grid->StartRec-1, $t_daftard_grid->DisplayRecs);

	// Set no record found message
	if ($t_daftard->CurrentAction == "" && $t_daftard_grid->TotalRecs == 0) {
		if (!$Security->CanList())
			$t_daftard_grid->setWarningMessage(ew_DeniedMsg());
		if ($t_daftard_grid->SearchWhere == "0=101")
			$t_daftard_grid->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$t_daftard_grid->setWarningMessage($Language->Phrase("NoRecord"));
	}
}
$t_daftard_grid->RenderOtherOptions();
?>
<?php $t_daftard_grid->ShowPageHeader(); ?>
<?php
$t_daftard_grid->ShowMessage();
?>
<?php if ($t_daftard_grid->TotalRecs > 0 || $t_daftard->CurrentAction <> "") { ?>
<div class="panel panel-default ewGrid t_daftard">
<div id="ft_daftardgrid" class="ewForm form-inline">
<?php if ($t_daftard_grid->ShowOtherOptions) { ?>
<div class="panel-heading ewGridUpperPanel">
<?php
	foreach ($t_daftard_grid->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<div id="gmp_t_daftard" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table id="tbl_t_daftardgrid" class="table ewTable">
<?php echo $t_daftard->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$t_daftard_grid->RowType = EW_ROWTYPE_HEADER;

// Render list options
$t_daftard_grid->RenderListOptions();

// Render list options (header, left)
$t_daftard_grid->ListOptions->Render("header", "left");
?>
<?php if ($t_daftard->PraktikumID->Visible) { // PraktikumID ?>
	<?php if ($t_daftard->SortUrl($t_daftard->PraktikumID) == "") { ?>
		<th data-name="PraktikumID"><div id="elh_t_daftard_PraktikumID" class="t_daftard_PraktikumID"><div class="ewTableHeaderCaption"><?php echo $t_daftard->PraktikumID->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="PraktikumID"><div><div id="elh_t_daftard_PraktikumID" class="t_daftard_PraktikumID">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_daftard->PraktikumID->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_daftard->PraktikumID->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_daftard->PraktikumID->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($t_daftard->Tgl->Visible) { // Tgl ?>
	<?php if ($t_daftard->SortUrl($t_daftard->Tgl) == "") { ?>
		<th data-name="Tgl"><div id="elh_t_daftard_Tgl" class="t_daftard_Tgl"><div class="ewTableHeaderCaption"><?php echo $t_daftard->Tgl->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Tgl"><div><div id="elh_t_daftard_Tgl" class="t_daftard_Tgl">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_daftard->Tgl->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_daftard->Tgl->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_daftard->Tgl->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$t_daftard_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$t_daftard_grid->StartRec = 1;
$t_daftard_grid->StopRec = $t_daftard_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($t_daftard_grid->FormKeyCountName) && ($t_daftard->CurrentAction == "gridadd" || $t_daftard->CurrentAction == "gridedit" || $t_daftard->CurrentAction == "F")) {
		$t_daftard_grid->KeyCount = $objForm->GetValue($t_daftard_grid->FormKeyCountName);
		$t_daftard_grid->StopRec = $t_daftard_grid->StartRec + $t_daftard_grid->KeyCount - 1;
	}
}
$t_daftard_grid->RecCnt = $t_daftard_grid->StartRec - 1;
if ($t_daftard_grid->Recordset && !$t_daftard_grid->Recordset->EOF) {
	$t_daftard_grid->Recordset->MoveFirst();
	$bSelectLimit = $t_daftard_grid->UseSelectLimit;
	if (!$bSelectLimit && $t_daftard_grid->StartRec > 1)
		$t_daftard_grid->Recordset->Move($t_daftard_grid->StartRec - 1);
} elseif (!$t_daftard->AllowAddDeleteRow && $t_daftard_grid->StopRec == 0) {
	$t_daftard_grid->StopRec = $t_daftard->GridAddRowCount;
}

// Initialize aggregate
$t_daftard->RowType = EW_ROWTYPE_AGGREGATEINIT;
$t_daftard->ResetAttrs();
$t_daftard_grid->RenderRow();
if ($t_daftard->CurrentAction == "gridadd")
	$t_daftard_grid->RowIndex = 0;
if ($t_daftard->CurrentAction == "gridedit")
	$t_daftard_grid->RowIndex = 0;
while ($t_daftard_grid->RecCnt < $t_daftard_grid->StopRec) {
	$t_daftard_grid->RecCnt++;
	if (intval($t_daftard_grid->RecCnt) >= intval($t_daftard_grid->StartRec)) {
		$t_daftard_grid->RowCnt++;
		if ($t_daftard->CurrentAction == "gridadd" || $t_daftard->CurrentAction == "gridedit" || $t_daftard->CurrentAction == "F") {
			$t_daftard_grid->RowIndex++;
			$objForm->Index = $t_daftard_grid->RowIndex;
			if ($objForm->HasValue($t_daftard_grid->FormActionName))
				$t_daftard_grid->RowAction = strval($objForm->GetValue($t_daftard_grid->FormActionName));
			elseif ($t_daftard->CurrentAction == "gridadd")
				$t_daftard_grid->RowAction = "insert";
			else
				$t_daftard_grid->RowAction = "";
		}

		// Set up key count
		$t_daftard_grid->KeyCount = $t_daftard_grid->RowIndex;

		// Init row class and style
		$t_daftard->ResetAttrs();
		$t_daftard->CssClass = "";
		if ($t_daftard->CurrentAction == "gridadd") {
			if ($t_daftard->CurrentMode == "copy") {
				$t_daftard_grid->LoadRowValues($t_daftard_grid->Recordset); // Load row values
				$t_daftard_grid->SetRecordKey($t_daftard_grid->RowOldKey, $t_daftard_grid->Recordset); // Set old record key
			} else {
				$t_daftard_grid->LoadDefaultValues(); // Load default values
				$t_daftard_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$t_daftard_grid->LoadRowValues($t_daftard_grid->Recordset); // Load row values
		}
		$t_daftard->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($t_daftard->CurrentAction == "gridadd") // Grid add
			$t_daftard->RowType = EW_ROWTYPE_ADD; // Render add
		if ($t_daftard->CurrentAction == "gridadd" && $t_daftard->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$t_daftard_grid->RestoreCurrentRowFormValues($t_daftard_grid->RowIndex); // Restore form values
		if ($t_daftard->CurrentAction == "gridedit") { // Grid edit
			if ($t_daftard->EventCancelled) {
				$t_daftard_grid->RestoreCurrentRowFormValues($t_daftard_grid->RowIndex); // Restore form values
			}
			if ($t_daftard_grid->RowAction == "insert")
				$t_daftard->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$t_daftard->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($t_daftard->CurrentAction == "gridedit" && ($t_daftard->RowType == EW_ROWTYPE_EDIT || $t_daftard->RowType == EW_ROWTYPE_ADD) && $t_daftard->EventCancelled) // Update failed
			$t_daftard_grid->RestoreCurrentRowFormValues($t_daftard_grid->RowIndex); // Restore form values
		if ($t_daftard->RowType == EW_ROWTYPE_EDIT) // Edit row
			$t_daftard_grid->EditRowCnt++;
		if ($t_daftard->CurrentAction == "F") // Confirm row
			$t_daftard_grid->RestoreCurrentRowFormValues($t_daftard_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$t_daftard->RowAttrs = array_merge($t_daftard->RowAttrs, array('data-rowindex'=>$t_daftard_grid->RowCnt, 'id'=>'r' . $t_daftard_grid->RowCnt . '_t_daftard', 'data-rowtype'=>$t_daftard->RowType));

		// Render row
		$t_daftard_grid->RenderRow();

		// Render list options
		$t_daftard_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($t_daftard_grid->RowAction <> "delete" && $t_daftard_grid->RowAction <> "insertdelete" && !($t_daftard_grid->RowAction == "insert" && $t_daftard->CurrentAction == "F" && $t_daftard_grid->EmptyRow())) {
?>
	<tr<?php echo $t_daftard->RowAttributes() ?>>
<?php

// Render list options (body, left)
$t_daftard_grid->ListOptions->Render("body", "left", $t_daftard_grid->RowCnt);
?>
	<?php if ($t_daftard->PraktikumID->Visible) { // PraktikumID ?>
		<td data-name="PraktikumID"<?php echo $t_daftard->PraktikumID->CellAttributes() ?>>
<?php if ($t_daftard->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $t_daftard_grid->RowCnt ?>_t_daftard_PraktikumID" class="form-group t_daftard_PraktikumID">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x<?php echo $t_daftard_grid->RowIndex ?>_PraktikumID"><?php echo (strval($t_daftard->PraktikumID->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $t_daftard->PraktikumID->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($t_daftard->PraktikumID->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $t_daftard_grid->RowIndex ?>_PraktikumID',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="t_daftard" data-field="x_PraktikumID" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $t_daftard->PraktikumID->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $t_daftard_grid->RowIndex ?>_PraktikumID" id="x<?php echo $t_daftard_grid->RowIndex ?>_PraktikumID" value="<?php echo $t_daftard->PraktikumID->CurrentValue ?>"<?php echo $t_daftard->PraktikumID->EditAttributes() ?>>
<input type="hidden" name="s_x<?php echo $t_daftard_grid->RowIndex ?>_PraktikumID" id="s_x<?php echo $t_daftard_grid->RowIndex ?>_PraktikumID" value="<?php echo $t_daftard->PraktikumID->LookupFilterQuery() ?>">
</span>
<input type="hidden" data-table="t_daftard" data-field="x_PraktikumID" name="o<?php echo $t_daftard_grid->RowIndex ?>_PraktikumID" id="o<?php echo $t_daftard_grid->RowIndex ?>_PraktikumID" value="<?php echo ew_HtmlEncode($t_daftard->PraktikumID->OldValue) ?>">
<?php } ?>
<?php if ($t_daftard->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_daftard_grid->RowCnt ?>_t_daftard_PraktikumID" class="form-group t_daftard_PraktikumID">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x<?php echo $t_daftard_grid->RowIndex ?>_PraktikumID"><?php echo (strval($t_daftard->PraktikumID->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $t_daftard->PraktikumID->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($t_daftard->PraktikumID->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $t_daftard_grid->RowIndex ?>_PraktikumID',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="t_daftard" data-field="x_PraktikumID" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $t_daftard->PraktikumID->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $t_daftard_grid->RowIndex ?>_PraktikumID" id="x<?php echo $t_daftard_grid->RowIndex ?>_PraktikumID" value="<?php echo $t_daftard->PraktikumID->CurrentValue ?>"<?php echo $t_daftard->PraktikumID->EditAttributes() ?>>
<input type="hidden" name="s_x<?php echo $t_daftard_grid->RowIndex ?>_PraktikumID" id="s_x<?php echo $t_daftard_grid->RowIndex ?>_PraktikumID" value="<?php echo $t_daftard->PraktikumID->LookupFilterQuery() ?>">
</span>
<?php } ?>
<?php if ($t_daftard->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_daftard_grid->RowCnt ?>_t_daftard_PraktikumID" class="t_daftard_PraktikumID">
<span<?php echo $t_daftard->PraktikumID->ViewAttributes() ?>>
<?php echo $t_daftard->PraktikumID->ListViewValue() ?></span>
</span>
<?php if ($t_daftard->CurrentAction <> "F") { ?>
<input type="hidden" data-table="t_daftard" data-field="x_PraktikumID" name="x<?php echo $t_daftard_grid->RowIndex ?>_PraktikumID" id="x<?php echo $t_daftard_grid->RowIndex ?>_PraktikumID" value="<?php echo ew_HtmlEncode($t_daftard->PraktikumID->FormValue) ?>">
<input type="hidden" data-table="t_daftard" data-field="x_PraktikumID" name="o<?php echo $t_daftard_grid->RowIndex ?>_PraktikumID" id="o<?php echo $t_daftard_grid->RowIndex ?>_PraktikumID" value="<?php echo ew_HtmlEncode($t_daftard->PraktikumID->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="t_daftard" data-field="x_PraktikumID" name="ft_daftardgrid$x<?php echo $t_daftard_grid->RowIndex ?>_PraktikumID" id="ft_daftardgrid$x<?php echo $t_daftard_grid->RowIndex ?>_PraktikumID" value="<?php echo ew_HtmlEncode($t_daftard->PraktikumID->FormValue) ?>">
<input type="hidden" data-table="t_daftard" data-field="x_PraktikumID" name="ft_daftardgrid$o<?php echo $t_daftard_grid->RowIndex ?>_PraktikumID" id="ft_daftardgrid$o<?php echo $t_daftard_grid->RowIndex ?>_PraktikumID" value="<?php echo ew_HtmlEncode($t_daftard->PraktikumID->OldValue) ?>">
<?php } ?>
<?php } ?>
<a id="<?php echo $t_daftard_grid->PageObjName . "_row_" . $t_daftard_grid->RowCnt ?>"></a></td>
	<?php } ?>
<?php if ($t_daftard->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-table="t_daftard" data-field="x_DaftradID" name="x<?php echo $t_daftard_grid->RowIndex ?>_DaftradID" id="x<?php echo $t_daftard_grid->RowIndex ?>_DaftradID" value="<?php echo ew_HtmlEncode($t_daftard->DaftradID->CurrentValue) ?>">
<input type="hidden" data-table="t_daftard" data-field="x_DaftradID" name="o<?php echo $t_daftard_grid->RowIndex ?>_DaftradID" id="o<?php echo $t_daftard_grid->RowIndex ?>_DaftradID" value="<?php echo ew_HtmlEncode($t_daftard->DaftradID->OldValue) ?>">
<?php } ?>
<?php if ($t_daftard->RowType == EW_ROWTYPE_EDIT || $t_daftard->CurrentMode == "edit") { ?>
<input type="hidden" data-table="t_daftard" data-field="x_DaftradID" name="x<?php echo $t_daftard_grid->RowIndex ?>_DaftradID" id="x<?php echo $t_daftard_grid->RowIndex ?>_DaftradID" value="<?php echo ew_HtmlEncode($t_daftard->DaftradID->CurrentValue) ?>">
<?php } ?>
	<?php if ($t_daftard->Tgl->Visible) { // Tgl ?>
		<td data-name="Tgl"<?php echo $t_daftard->Tgl->CellAttributes() ?>>
<?php if ($t_daftard->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $t_daftard_grid->RowCnt ?>_t_daftard_Tgl" class="form-group t_daftard_Tgl">
<input type="text" data-table="t_daftard" data-field="x_Tgl" name="x<?php echo $t_daftard_grid->RowIndex ?>_Tgl" id="x<?php echo $t_daftard_grid->RowIndex ?>_Tgl" placeholder="<?php echo ew_HtmlEncode($t_daftard->Tgl->getPlaceHolder()) ?>" value="<?php echo $t_daftard->Tgl->EditValue ?>"<?php echo $t_daftard->Tgl->EditAttributes() ?>>
<?php if (!$t_daftard->Tgl->ReadOnly && !$t_daftard->Tgl->Disabled && !isset($t_daftard->Tgl->EditAttrs["readonly"]) && !isset($t_daftard->Tgl->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("ft_daftardgrid", "x<?php echo $t_daftard_grid->RowIndex ?>_Tgl", 0);
</script>
<?php } ?>
</span>
<input type="hidden" data-table="t_daftard" data-field="x_Tgl" name="o<?php echo $t_daftard_grid->RowIndex ?>_Tgl" id="o<?php echo $t_daftard_grid->RowIndex ?>_Tgl" value="<?php echo ew_HtmlEncode($t_daftard->Tgl->OldValue) ?>">
<?php } ?>
<?php if ($t_daftard->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_daftard_grid->RowCnt ?>_t_daftard_Tgl" class="form-group t_daftard_Tgl">
<input type="text" data-table="t_daftard" data-field="x_Tgl" name="x<?php echo $t_daftard_grid->RowIndex ?>_Tgl" id="x<?php echo $t_daftard_grid->RowIndex ?>_Tgl" placeholder="<?php echo ew_HtmlEncode($t_daftard->Tgl->getPlaceHolder()) ?>" value="<?php echo $t_daftard->Tgl->EditValue ?>"<?php echo $t_daftard->Tgl->EditAttributes() ?>>
<?php if (!$t_daftard->Tgl->ReadOnly && !$t_daftard->Tgl->Disabled && !isset($t_daftard->Tgl->EditAttrs["readonly"]) && !isset($t_daftard->Tgl->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("ft_daftardgrid", "x<?php echo $t_daftard_grid->RowIndex ?>_Tgl", 0);
</script>
<?php } ?>
</span>
<?php } ?>
<?php if ($t_daftard->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_daftard_grid->RowCnt ?>_t_daftard_Tgl" class="t_daftard_Tgl">
<span<?php echo $t_daftard->Tgl->ViewAttributes() ?>>
<?php echo $t_daftard->Tgl->ListViewValue() ?></span>
</span>
<?php if ($t_daftard->CurrentAction <> "F") { ?>
<input type="hidden" data-table="t_daftard" data-field="x_Tgl" name="x<?php echo $t_daftard_grid->RowIndex ?>_Tgl" id="x<?php echo $t_daftard_grid->RowIndex ?>_Tgl" value="<?php echo ew_HtmlEncode($t_daftard->Tgl->FormValue) ?>">
<input type="hidden" data-table="t_daftard" data-field="x_Tgl" name="o<?php echo $t_daftard_grid->RowIndex ?>_Tgl" id="o<?php echo $t_daftard_grid->RowIndex ?>_Tgl" value="<?php echo ew_HtmlEncode($t_daftard->Tgl->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="t_daftard" data-field="x_Tgl" name="ft_daftardgrid$x<?php echo $t_daftard_grid->RowIndex ?>_Tgl" id="ft_daftardgrid$x<?php echo $t_daftard_grid->RowIndex ?>_Tgl" value="<?php echo ew_HtmlEncode($t_daftard->Tgl->FormValue) ?>">
<input type="hidden" data-table="t_daftard" data-field="x_Tgl" name="ft_daftardgrid$o<?php echo $t_daftard_grid->RowIndex ?>_Tgl" id="ft_daftardgrid$o<?php echo $t_daftard_grid->RowIndex ?>_Tgl" value="<?php echo ew_HtmlEncode($t_daftard->Tgl->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$t_daftard_grid->ListOptions->Render("body", "right", $t_daftard_grid->RowCnt);
?>
	</tr>
<?php if ($t_daftard->RowType == EW_ROWTYPE_ADD || $t_daftard->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
ft_daftardgrid.UpdateOpts(<?php echo $t_daftard_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($t_daftard->CurrentAction <> "gridadd" || $t_daftard->CurrentMode == "copy")
		if (!$t_daftard_grid->Recordset->EOF) $t_daftard_grid->Recordset->MoveNext();
}
?>
<?php
	if ($t_daftard->CurrentMode == "add" || $t_daftard->CurrentMode == "copy" || $t_daftard->CurrentMode == "edit") {
		$t_daftard_grid->RowIndex = '$rowindex$';
		$t_daftard_grid->LoadDefaultValues();

		// Set row properties
		$t_daftard->ResetAttrs();
		$t_daftard->RowAttrs = array_merge($t_daftard->RowAttrs, array('data-rowindex'=>$t_daftard_grid->RowIndex, 'id'=>'r0_t_daftard', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($t_daftard->RowAttrs["class"], "ewTemplate");
		$t_daftard->RowType = EW_ROWTYPE_ADD;

		// Render row
		$t_daftard_grid->RenderRow();

		// Render list options
		$t_daftard_grid->RenderListOptions();
		$t_daftard_grid->StartRowCnt = 0;
?>
	<tr<?php echo $t_daftard->RowAttributes() ?>>
<?php

// Render list options (body, left)
$t_daftard_grid->ListOptions->Render("body", "left", $t_daftard_grid->RowIndex);
?>
	<?php if ($t_daftard->PraktikumID->Visible) { // PraktikumID ?>
		<td data-name="PraktikumID">
<?php if ($t_daftard->CurrentAction <> "F") { ?>
<span id="el$rowindex$_t_daftard_PraktikumID" class="form-group t_daftard_PraktikumID">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x<?php echo $t_daftard_grid->RowIndex ?>_PraktikumID"><?php echo (strval($t_daftard->PraktikumID->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $t_daftard->PraktikumID->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($t_daftard->PraktikumID->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $t_daftard_grid->RowIndex ?>_PraktikumID',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="t_daftard" data-field="x_PraktikumID" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $t_daftard->PraktikumID->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $t_daftard_grid->RowIndex ?>_PraktikumID" id="x<?php echo $t_daftard_grid->RowIndex ?>_PraktikumID" value="<?php echo $t_daftard->PraktikumID->CurrentValue ?>"<?php echo $t_daftard->PraktikumID->EditAttributes() ?>>
<input type="hidden" name="s_x<?php echo $t_daftard_grid->RowIndex ?>_PraktikumID" id="s_x<?php echo $t_daftard_grid->RowIndex ?>_PraktikumID" value="<?php echo $t_daftard->PraktikumID->LookupFilterQuery() ?>">
</span>
<?php } else { ?>
<span id="el$rowindex$_t_daftard_PraktikumID" class="form-group t_daftard_PraktikumID">
<span<?php echo $t_daftard->PraktikumID->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_daftard->PraktikumID->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="t_daftard" data-field="x_PraktikumID" name="x<?php echo $t_daftard_grid->RowIndex ?>_PraktikumID" id="x<?php echo $t_daftard_grid->RowIndex ?>_PraktikumID" value="<?php echo ew_HtmlEncode($t_daftard->PraktikumID->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="t_daftard" data-field="x_PraktikumID" name="o<?php echo $t_daftard_grid->RowIndex ?>_PraktikumID" id="o<?php echo $t_daftard_grid->RowIndex ?>_PraktikumID" value="<?php echo ew_HtmlEncode($t_daftard->PraktikumID->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($t_daftard->Tgl->Visible) { // Tgl ?>
		<td data-name="Tgl">
<?php if ($t_daftard->CurrentAction <> "F") { ?>
<span id="el$rowindex$_t_daftard_Tgl" class="form-group t_daftard_Tgl">
<input type="text" data-table="t_daftard" data-field="x_Tgl" name="x<?php echo $t_daftard_grid->RowIndex ?>_Tgl" id="x<?php echo $t_daftard_grid->RowIndex ?>_Tgl" placeholder="<?php echo ew_HtmlEncode($t_daftard->Tgl->getPlaceHolder()) ?>" value="<?php echo $t_daftard->Tgl->EditValue ?>"<?php echo $t_daftard->Tgl->EditAttributes() ?>>
<?php if (!$t_daftard->Tgl->ReadOnly && !$t_daftard->Tgl->Disabled && !isset($t_daftard->Tgl->EditAttrs["readonly"]) && !isset($t_daftard->Tgl->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("ft_daftardgrid", "x<?php echo $t_daftard_grid->RowIndex ?>_Tgl", 0);
</script>
<?php } ?>
</span>
<?php } else { ?>
<span id="el$rowindex$_t_daftard_Tgl" class="form-group t_daftard_Tgl">
<span<?php echo $t_daftard->Tgl->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $t_daftard->Tgl->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="t_daftard" data-field="x_Tgl" name="x<?php echo $t_daftard_grid->RowIndex ?>_Tgl" id="x<?php echo $t_daftard_grid->RowIndex ?>_Tgl" value="<?php echo ew_HtmlEncode($t_daftard->Tgl->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="t_daftard" data-field="x_Tgl" name="o<?php echo $t_daftard_grid->RowIndex ?>_Tgl" id="o<?php echo $t_daftard_grid->RowIndex ?>_Tgl" value="<?php echo ew_HtmlEncode($t_daftard->Tgl->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$t_daftard_grid->ListOptions->Render("body", "right", $t_daftard_grid->RowCnt);
?>
<script type="text/javascript">
ft_daftardgrid.UpdateOpts(<?php echo $t_daftard_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($t_daftard->CurrentMode == "add" || $t_daftard->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $t_daftard_grid->FormKeyCountName ?>" id="<?php echo $t_daftard_grid->FormKeyCountName ?>" value="<?php echo $t_daftard_grid->KeyCount ?>">
<?php echo $t_daftard_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($t_daftard->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $t_daftard_grid->FormKeyCountName ?>" id="<?php echo $t_daftard_grid->FormKeyCountName ?>" value="<?php echo $t_daftard_grid->KeyCount ?>">
<?php echo $t_daftard_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($t_daftard->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="ft_daftardgrid">
</div>
<?php

// Close recordset
if ($t_daftard_grid->Recordset)
	$t_daftard_grid->Recordset->Close();
?>
<?php if ($t_daftard_grid->ShowOtherOptions) { ?>
<div class="panel-footer ewGridLowerPanel">
<?php
	foreach ($t_daftard_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
</div>
</div>
<?php } ?>
<?php if ($t_daftard_grid->TotalRecs == 0 && $t_daftard->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($t_daftard_grid->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($t_daftard->Export == "") { ?>
<script type="text/javascript">
ft_daftardgrid.Init();
</script>
<?php } ?>
<?php
$t_daftard_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$t_daftard_grid->Page_Terminate();
?>
