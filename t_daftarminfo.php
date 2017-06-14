<?php

// Global variable for table object
$t_daftarm = NULL;

//
// Table class for t_daftarm
//
class ct_daftarm extends cTable {
	var $AuditTrailOnAdd = TRUE;
	var $AuditTrailOnEdit = TRUE;
	var $AuditTrailOnDelete = TRUE;
	var $AuditTrailOnView = FALSE;
	var $AuditTrailOnViewData = FALSE;
	var $AuditTrailOnSearch = FALSE;
	var $DaftarmID;
	var $_UserID;
	var $TglJam;
	var $BuktiBayar;
	var $JumlahBayar;
	var $Acc;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 't_daftarm';
		$this->TableName = 't_daftarm';
		$this->TableType = 'TABLE';

		// Update Table
		$this->UpdateTable = "`t_daftarm`";
		$this->DBID = 'DB';
		$this->ExportAll = TRUE;
		$this->ExportPageBreakCount = 0; // Page break per every n record (PDF only)
		$this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)
		$this->ExportPageSize = "a4"; // Page size (PDF only)
		$this->ExportExcelPageOrientation = ""; // Page orientation (PHPExcel only)
		$this->ExportExcelPageSize = ""; // Page size (PHPExcel only)
		$this->DetailAdd = FALSE; // Allow detail add
		$this->DetailEdit = FALSE; // Allow detail edit
		$this->DetailView = FALSE; // Allow detail view
		$this->ShowMultipleDetails = FALSE; // Show multiple details
		$this->GridAddRowCount = 5;
		$this->AllowAddDeleteRow = ew_AllowAddDeleteRow(); // Allow add/delete row
		$this->UserIDAllowSecurity = 0; // User ID Allow
		$this->BasicSearch = new cBasicSearch($this->TableVar);

		// DaftarmID
		$this->DaftarmID = new cField('t_daftarm', 't_daftarm', 'x_DaftarmID', 'DaftarmID', '`DaftarmID`', '`DaftarmID`', 3, -1, FALSE, '`DaftarmID`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'NO');
		$this->DaftarmID->Sortable = TRUE; // Allow sort
		$this->DaftarmID->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['DaftarmID'] = &$this->DaftarmID;

		// UserID
		$this->_UserID = new cField('t_daftarm', 't_daftarm', 'x__UserID', 'UserID', '`UserID`', '`UserID`', 3, -1, FALSE, '`UserID`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->_UserID->Sortable = TRUE; // Allow sort
		$this->_UserID->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->_UserID->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->_UserID->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['UserID'] = &$this->_UserID;

		// TglJam
		$this->TglJam = new cField('t_daftarm', 't_daftarm', 'x_TglJam', 'TglJam', '`TglJam`', ew_CastDateFieldForLike('`TglJam`', 0, "DB"), 135, 0, FALSE, '`TglJam`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->TglJam->Sortable = TRUE; // Allow sort
		$this->TglJam->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_FORMAT"], $Language->Phrase("IncorrectDate"));
		$this->fields['TglJam'] = &$this->TglJam;

		// BuktiBayar
		$this->BuktiBayar = new cField('t_daftarm', 't_daftarm', 'x_BuktiBayar', 'BuktiBayar', '`BuktiBayar`', '`BuktiBayar`', 200, -1, TRUE, '`BuktiBayar`', FALSE, FALSE, FALSE, 'IMAGE', 'FILE');
		$this->BuktiBayar->Sortable = TRUE; // Allow sort
		$this->BuktiBayar->ImageResize = TRUE;
		$this->fields['BuktiBayar'] = &$this->BuktiBayar;

		// JumlahBayar
		$this->JumlahBayar = new cField('t_daftarm', 't_daftarm', 'x_JumlahBayar', 'JumlahBayar', '`JumlahBayar`', '`JumlahBayar`', 4, -1, FALSE, '`JumlahBayar`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->JumlahBayar->Sortable = TRUE; // Allow sort
		$this->JumlahBayar->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['JumlahBayar'] = &$this->JumlahBayar;

		// Acc
		$this->Acc = new cField('t_daftarm', 't_daftarm', 'x_Acc', 'Acc', '`Acc`', '`Acc`', 16, -1, FALSE, '`Acc`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'RADIO');
		$this->Acc->Sortable = TRUE; // Allow sort
		$this->Acc->OptionCount = 2;
		$this->Acc->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['Acc'] = &$this->Acc;
	}

	// Set Field Visibility
	function SetFieldVisibility($fldparm) {
		global $Security;
		return $this->$fldparm->Visible; // Returns original value
	}

	// Multiple column sort
	function UpdateSort(&$ofld, $ctrl) {
		if ($this->CurrentOrder == $ofld->FldName) {
			$sSortField = $ofld->FldExpression;
			$sLastSort = $ofld->getSort();
			if ($this->CurrentOrderType == "ASC" || $this->CurrentOrderType == "DESC") {
				$sThisSort = $this->CurrentOrderType;
			} else {
				$sThisSort = ($sLastSort == "ASC") ? "DESC" : "ASC";
			}
			$ofld->setSort($sThisSort);
			if ($ctrl) {
				$sOrderBy = $this->getSessionOrderBy();
				if (strpos($sOrderBy, $sSortField . " " . $sLastSort) !== FALSE) {
					$sOrderBy = str_replace($sSortField . " " . $sLastSort, $sSortField . " " . $sThisSort, $sOrderBy);
				} else {
					if ($sOrderBy <> "") $sOrderBy .= ", ";
					$sOrderBy .= $sSortField . " " . $sThisSort;
				}
				$this->setSessionOrderBy($sOrderBy); // Save to Session
			} else {
				$this->setSessionOrderBy($sSortField . " " . $sThisSort); // Save to Session
			}
		} else {
			if (!$ctrl) $ofld->setSort("");
		}
	}

	// Current detail table name
	function getCurrentDetailTable() {
		return @$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_DETAIL_TABLE];
	}

	function setCurrentDetailTable($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_DETAIL_TABLE] = $v;
	}

	// Get detail url
	function GetDetailUrl() {

		// Detail url
		$sDetailUrl = "";
		if ($this->getCurrentDetailTable() == "t_daftard") {
			$sDetailUrl = $GLOBALS["t_daftard"]->GetListUrl() . "?" . EW_TABLE_SHOW_MASTER . "=" . $this->TableVar;
			$sDetailUrl .= "&fk_DaftarmID=" . urlencode($this->DaftarmID->CurrentValue);
		}
		if ($sDetailUrl == "") {
			$sDetailUrl = "t_daftarmlist.php";
		}
		return $sDetailUrl;
	}

	// Table level SQL
	var $_SqlFrom = "";

	function getSqlFrom() { // From
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`t_daftarm`";
	}

	function SqlFrom() { // For backward compatibility
		return $this->getSqlFrom();
	}

	function setSqlFrom($v) {
		$this->_SqlFrom = $v;
	}
	var $_SqlSelect = "";

	function getSqlSelect() { // Select
		return ($this->_SqlSelect <> "") ? $this->_SqlSelect : "SELECT * FROM " . $this->getSqlFrom();
	}

	function SqlSelect() { // For backward compatibility
		return $this->getSqlSelect();
	}

	function setSqlSelect($v) {
		$this->_SqlSelect = $v;
	}
	var $_SqlWhere = "";

	function getSqlWhere() { // Where
		$sWhere = ($this->_SqlWhere <> "") ? $this->_SqlWhere : "";
		$this->TableFilter = (CurrentUserLevel() >= 0 ? "`acc` = '0' and userid = ".CurrentUserID() : "");
		ew_AddFilter($sWhere, $this->TableFilter);
		return $sWhere;
	}

	function SqlWhere() { // For backward compatibility
		return $this->getSqlWhere();
	}

	function setSqlWhere($v) {
		$this->_SqlWhere = $v;
	}
	var $_SqlGroupBy = "";

	function getSqlGroupBy() { // Group By
		return ($this->_SqlGroupBy <> "") ? $this->_SqlGroupBy : "";
	}

	function SqlGroupBy() { // For backward compatibility
		return $this->getSqlGroupBy();
	}

	function setSqlGroupBy($v) {
		$this->_SqlGroupBy = $v;
	}
	var $_SqlHaving = "";

	function getSqlHaving() { // Having
		return ($this->_SqlHaving <> "") ? $this->_SqlHaving : "";
	}

	function SqlHaving() { // For backward compatibility
		return $this->getSqlHaving();
	}

	function setSqlHaving($v) {
		$this->_SqlHaving = $v;
	}
	var $_SqlOrderBy = "";

	function getSqlOrderBy() { // Order By
		return ($this->_SqlOrderBy <> "") ? $this->_SqlOrderBy : "";
	}

	function SqlOrderBy() { // For backward compatibility
		return $this->getSqlOrderBy();
	}

	function setSqlOrderBy($v) {
		$this->_SqlOrderBy = $v;
	}

	// Apply User ID filters
	function ApplyUserIDFilters($sFilter) {
		return $sFilter;
	}

	// Check if User ID security allows view all
	function UserIDAllow($id = "") {
		$allow = EW_USER_ID_ALLOW;
		switch ($id) {
			case "add":
			case "copy":
			case "gridadd":
			case "register":
			case "addopt":
				return (($allow & 1) == 1);
			case "edit":
			case "gridedit":
			case "update":
			case "changepwd":
			case "forgotpwd":
				return (($allow & 4) == 4);
			case "delete":
				return (($allow & 2) == 2);
			case "view":
				return (($allow & 32) == 32);
			case "search":
				return (($allow & 64) == 64);
			default:
				return (($allow & 8) == 8);
		}
	}

	// Get SQL
	function GetSQL($where, $orderby) {
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(),
			$this->getSqlGroupBy(), $this->getSqlHaving(), $this->getSqlOrderBy(),
			$where, $orderby);
	}

	// Table SQL
	function SQL() {
		$sFilter = $this->CurrentFilter;
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(),
			$this->getSqlGroupBy(), $this->getSqlHaving(), $this->getSqlOrderBy(),
			$sFilter, $sSort);
	}

	// Table SQL with List page filter
	function SelectSQL() {
		$sFilter = $this->getSessionWhere();
		ew_AddFilter($sFilter, $this->CurrentFilter);
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$this->Recordset_Selecting($sFilter);
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(), $this->getSqlGroupBy(),
			$this->getSqlHaving(), $this->getSqlOrderBy(), $sFilter, $sSort);
	}

	// Get ORDER BY clause
	function GetOrderBy() {
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql("", "", "", "", $this->getSqlOrderBy(), "", $sSort);
	}

	// Try to get record count
	function TryGetRecordCount($sSql) {
		$cnt = -1;
		if (($this->TableType == 'TABLE' || $this->TableType == 'VIEW' || $this->TableType == 'LINKTABLE') && preg_match("/^SELECT \* FROM/i", $sSql)) {
			$sSql = "SELECT COUNT(*) FROM" . preg_replace('/^SELECT\s([\s\S]+)?\*\sFROM/i', "", $sSql);
			$sOrderBy = $this->GetOrderBy();
			if (substr($sSql, strlen($sOrderBy) * -1) == $sOrderBy)
				$sSql = substr($sSql, 0, strlen($sSql) - strlen($sOrderBy)); // Remove ORDER BY clause
		} else {
			$sSql = "SELECT COUNT(*) FROM (" . $sSql . ") EW_COUNT_TABLE";
		}
		$conn = &$this->Connection();
		if ($rs = $conn->Execute($sSql)) {
			if (!$rs->EOF && $rs->FieldCount() > 0) {
				$cnt = $rs->fields[0];
				$rs->Close();
			}
		}
		return intval($cnt);
	}

	// Get record count based on filter (for detail record count in master table pages)
	function LoadRecordCount($sFilter) {
		$origFilter = $this->CurrentFilter;
		$this->CurrentFilter = $sFilter;
		$this->Recordset_Selecting($this->CurrentFilter);

		//$sSql = $this->SQL();
		$sSql = $this->GetSQL($this->CurrentFilter, "");
		$cnt = $this->TryGetRecordCount($sSql);
		if ($cnt == -1) {
			if ($rs = $this->LoadRs($this->CurrentFilter)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		$this->CurrentFilter = $origFilter;
		return intval($cnt);
	}

	// Get record count (for current List page)
	function SelectRecordCount() {
		$sSql = $this->SelectSQL();
		$cnt = $this->TryGetRecordCount($sSql);
		if ($cnt == -1) {
			$conn = &$this->Connection();
			if ($rs = $conn->Execute($sSql)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		return intval($cnt);
	}

	// INSERT statement
	function InsertSQL(&$rs) {
		$names = "";
		$values = "";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]) || $this->fields[$name]->FldIsCustom)
				continue;
			$names .= $this->fields[$name]->FldExpression . ",";
			$values .= ew_QuotedValue($value, $this->fields[$name]->FldDataType, $this->DBID) . ",";
		}
		while (substr($names, -1) == ",")
			$names = substr($names, 0, -1);
		while (substr($values, -1) == ",")
			$values = substr($values, 0, -1);
		return "INSERT INTO " . $this->UpdateTable . " ($names) VALUES ($values)";
	}

	// Insert
	function Insert(&$rs) {
		$conn = &$this->Connection();
		$bInsert = $conn->Execute($this->InsertSQL($rs));
		if ($bInsert) {

			// Get insert id if necessary
			$this->DaftarmID->setDbValue($conn->Insert_ID());
			$rs['DaftarmID'] = $this->DaftarmID->DbValue;
			if ($this->AuditTrailOnAdd)
				$this->WriteAuditTrailOnAdd($rs);
		}
		return $bInsert;
	}

	// UPDATE statement
	function UpdateSQL(&$rs, $where = "", $curfilter = TRUE) {
		$sql = "UPDATE " . $this->UpdateTable . " SET ";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]) || $this->fields[$name]->FldIsCustom)
				continue;
			$sql .= $this->fields[$name]->FldExpression . "=";
			$sql .= ew_QuotedValue($value, $this->fields[$name]->FldDataType, $this->DBID) . ",";
		}
		while (substr($sql, -1) == ",")
			$sql = substr($sql, 0, -1);
		$filter = ($curfilter) ? $this->CurrentFilter : "";
		if (is_array($where))
			$where = $this->ArrayToFilter($where);
		ew_AddFilter($filter, $where);
		if ($filter <> "")	$sql .= " WHERE " . $filter;
		return $sql;
	}

	// Update
	function Update(&$rs, $where = "", $rsold = NULL, $curfilter = TRUE) {
		$conn = &$this->Connection();
		$bUpdate = $conn->Execute($this->UpdateSQL($rs, $where, $curfilter));
		if ($bUpdate && $this->AuditTrailOnEdit) {
			$rsaudit = $rs;
			$fldname = 'DaftarmID';
			if (!array_key_exists($fldname, $rsaudit)) $rsaudit[$fldname] = $rsold[$fldname];
			$this->WriteAuditTrailOnEdit($rsaudit, $rsold);
		}
		return $bUpdate;
	}

	// DELETE statement
	function DeleteSQL(&$rs, $where = "", $curfilter = TRUE) {
		$sql = "DELETE FROM " . $this->UpdateTable . " WHERE ";
		if (is_array($where))
			$where = $this->ArrayToFilter($where);
		if ($rs) {
			if (array_key_exists('DaftarmID', $rs))
				ew_AddFilter($where, ew_QuotedName('DaftarmID', $this->DBID) . '=' . ew_QuotedValue($rs['DaftarmID'], $this->DaftarmID->FldDataType, $this->DBID));
		}
		$filter = ($curfilter) ? $this->CurrentFilter : "";
		ew_AddFilter($filter, $where);
		if ($filter <> "")
			$sql .= $filter;
		else
			$sql .= "0=1"; // Avoid delete
		return $sql;
	}

	// Delete
	function Delete(&$rs, $where = "", $curfilter = TRUE) {
		$conn = &$this->Connection();
		$bDelete = $conn->Execute($this->DeleteSQL($rs, $where, $curfilter));
		if ($bDelete && $this->AuditTrailOnDelete)
			$this->WriteAuditTrailOnDelete($rs);
		return $bDelete;
	}

	// Key filter WHERE clause
	function SqlKeyFilter() {
		return "`DaftarmID` = @DaftarmID@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->DaftarmID->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@DaftarmID@", ew_AdjustSql($this->DaftarmID->CurrentValue, $this->DBID), $sKeyFilter); // Replace key value
		return $sKeyFilter;
	}

	// Return page URL
	function getReturnUrl() {
		$name = EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL;

		// Get referer URL automatically
		if (ew_ServerVar("HTTP_REFERER") <> "" && ew_ReferPage() <> ew_CurrentPage() && ew_ReferPage() <> "login.php") // Referer not same page or login page
			$_SESSION[$name] = ew_ServerVar("HTTP_REFERER"); // Save to Session
		if (@$_SESSION[$name] <> "") {
			return $_SESSION[$name];
		} else {
			return "t_daftarmlist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "t_daftarmlist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("t_daftarmview.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("t_daftarmview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			$url = "t_daftarmadd.php?" . $this->UrlParm($parm);
		else
			$url = "t_daftarmadd.php";
		return $this->AddMasterUrl($url);
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("t_daftarmedit.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("t_daftarmedit.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
		return $this->AddMasterUrl($url);
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("t_daftarmadd.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("t_daftarmadd.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
		return $this->AddMasterUrl($url);
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("t_daftarmdelete.php", $this->UrlParm());
	}

	// Add master url
	function AddMasterUrl($url) {
		return $url;
	}

	function KeyToJson() {
		$json = "";
		$json .= "DaftarmID:" . ew_VarToJson($this->DaftarmID->CurrentValue, "number", "'");
		return "{" . $json . "}";
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->DaftarmID->CurrentValue)) {
			$sUrl .= "DaftarmID=" . urlencode($this->DaftarmID->CurrentValue);
		} else {
			return "javascript:ew_Alert(ewLanguage.Phrase('InvalidRecord'));";
		}
		return $sUrl;
	}

	// Sort URL
	function SortUrl(&$fld) {
		if ($this->CurrentAction <> "" || $this->Export <> "" ||
			in_array($fld->FldType, array(128, 204, 205))) { // Unsortable data type
				return "";
		} elseif ($fld->Sortable) {
			$sUrlParm = $this->UrlParm("order=" . urlencode($fld->FldName) . "&amp;ordertype=" . $fld->ReverseSort());
			return $this->AddMasterUrl(ew_CurrentPage() . "?" . $sUrlParm);
		} else {
			return "";
		}
	}

	// Get record keys from $_POST/$_GET/$_SESSION
	function GetRecordKeys() {
		global $EW_COMPOSITE_KEY_SEPARATOR;
		$arKeys = array();
		$arKey = array();
		if (isset($_POST["key_m"])) {
			$arKeys = ew_StripSlashes($_POST["key_m"]);
			$cnt = count($arKeys);
		} elseif (isset($_GET["key_m"])) {
			$arKeys = ew_StripSlashes($_GET["key_m"]);
			$cnt = count($arKeys);
		} elseif (!empty($_GET) || !empty($_POST)) {
			$isPost = ew_IsHttpPost();
			if ($isPost && isset($_POST["DaftarmID"]))
				$arKeys[] = ew_StripSlashes($_POST["DaftarmID"]);
			elseif (isset($_GET["DaftarmID"]))
				$arKeys[] = ew_StripSlashes($_GET["DaftarmID"]);
			else
				$arKeys = NULL; // Do not setup

			//return $arKeys; // Do not return yet, so the values will also be checked by the following code
		}

		// Check keys
		$ar = array();
		if (is_array($arKeys)) {
			foreach ($arKeys as $key) {
				if (!is_numeric($key))
					continue;
				$ar[] = $key;
			}
		}
		return $ar;
	}

	// Get key filter
	function GetKeyFilter() {
		$arKeys = $this->GetRecordKeys();
		$sKeyFilter = "";
		foreach ($arKeys as $key) {
			if ($sKeyFilter <> "") $sKeyFilter .= " OR ";
			$this->DaftarmID->CurrentValue = $key;
			$sKeyFilter .= "(" . $this->KeyFilter() . ")";
		}
		return $sKeyFilter;
	}

	// Load rows based on filter
	function &LoadRs($sFilter) {

		// Set up filter (SQL WHERE clause) and get return SQL
		//$this->CurrentFilter = $sFilter;
		//$sSql = $this->SQL();

		$sSql = $this->GetSQL($sFilter, "");
		$conn = &$this->Connection();
		$rs = $conn->Execute($sSql);
		return $rs;
	}

	// Load row values from recordset
	function LoadListRowValues(&$rs) {
		$this->DaftarmID->setDbValue($rs->fields('DaftarmID'));
		$this->_UserID->setDbValue($rs->fields('UserID'));
		$this->TglJam->setDbValue($rs->fields('TglJam'));
		$this->BuktiBayar->Upload->DbValue = $rs->fields('BuktiBayar');
		$this->JumlahBayar->setDbValue($rs->fields('JumlahBayar'));
		$this->Acc->setDbValue($rs->fields('Acc'));
	}

	// Render list row values
	function RenderListRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// DaftarmID
		// UserID
		// TglJam
		// BuktiBayar
		// JumlahBayar
		// Acc
		// DaftarmID

		$this->DaftarmID->ViewValue = $this->DaftarmID->CurrentValue;
		$this->DaftarmID->ViewCustomAttributes = "";

		// UserID
		if (strval($this->_UserID->CurrentValue) <> "") {
			$sFilterWrk = "`UserID`" . ew_SearchString("=", $this->_UserID->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `UserID`, `Nama` AS `DispFld`, `NIM` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `t_user`";
		$sWhereWrk = "";
		$this->_UserID->LookupFilters = array("dx1" => '`Nama`', "dx2" => '`NIM`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->_UserID, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$arwrk[2] = $rswrk->fields('Disp2Fld');
				$this->_UserID->ViewValue = $this->_UserID->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->_UserID->ViewValue = $this->_UserID->CurrentValue;
			}
		} else {
			$this->_UserID->ViewValue = NULL;
		}
		$this->_UserID->ViewCustomAttributes = "";

		// TglJam
		$this->TglJam->ViewValue = $this->TglJam->CurrentValue;
		$this->TglJam->ViewValue = ew_FormatDateTime($this->TglJam->ViewValue, 0);
		$this->TglJam->ViewCustomAttributes = "";

		// BuktiBayar
		if (!ew_Empty($this->BuktiBayar->Upload->DbValue)) {
			$this->BuktiBayar->ImageWidth = EW_THUMBNAIL_DEFAULT_WIDTH;
			$this->BuktiBayar->ImageHeight = EW_THUMBNAIL_DEFAULT_HEIGHT;
			$this->BuktiBayar->ImageAlt = $this->BuktiBayar->FldAlt();
			$this->BuktiBayar->ViewValue = $this->BuktiBayar->Upload->DbValue;
		} else {
			$this->BuktiBayar->ViewValue = "";
		}
		$this->BuktiBayar->ViewCustomAttributes = "";

		// JumlahBayar
		$this->JumlahBayar->ViewValue = $this->JumlahBayar->CurrentValue;
		$this->JumlahBayar->ViewValue = ew_FormatNumber($this->JumlahBayar->ViewValue, 0, -2, -2, -2);
		$this->JumlahBayar->CellCssStyle .= "text-align: right;";
		$this->JumlahBayar->ViewCustomAttributes = "";

		// Acc
		if (strval($this->Acc->CurrentValue) <> "") {
			$this->Acc->ViewValue = $this->Acc->OptionCaption($this->Acc->CurrentValue);
		} else {
			$this->Acc->ViewValue = NULL;
		}
		$this->Acc->ViewCustomAttributes = "";

		// DaftarmID
		$this->DaftarmID->LinkCustomAttributes = "";
		$this->DaftarmID->HrefValue = "";
		$this->DaftarmID->TooltipValue = "";

		// UserID
		$this->_UserID->LinkCustomAttributes = "";
		$this->_UserID->HrefValue = "";
		$this->_UserID->TooltipValue = "";

		// TglJam
		$this->TglJam->LinkCustomAttributes = "";
		$this->TglJam->HrefValue = "";
		$this->TglJam->TooltipValue = "";

		// BuktiBayar
		$this->BuktiBayar->LinkCustomAttributes = "";
		if (!ew_Empty($this->BuktiBayar->Upload->DbValue)) {
			$this->BuktiBayar->HrefValue = ew_GetFileUploadUrl($this->BuktiBayar, $this->BuktiBayar->Upload->DbValue); // Add prefix/suffix
			$this->BuktiBayar->LinkAttrs["target"] = ""; // Add target
			if ($this->Export <> "") $this->BuktiBayar->HrefValue = ew_ConvertFullUrl($this->BuktiBayar->HrefValue);
		} else {
			$this->BuktiBayar->HrefValue = "";
		}
		$this->BuktiBayar->HrefValue2 = $this->BuktiBayar->UploadPath . $this->BuktiBayar->Upload->DbValue;
		$this->BuktiBayar->TooltipValue = "";
		if ($this->BuktiBayar->UseColorbox) {
			if (ew_Empty($this->BuktiBayar->TooltipValue))
				$this->BuktiBayar->LinkAttrs["title"] = $Language->Phrase("ViewImageGallery");
			$this->BuktiBayar->LinkAttrs["data-rel"] = "t_daftarm_x_BuktiBayar";
			ew_AppendClass($this->BuktiBayar->LinkAttrs["class"], "ewLightbox");
		}

		// JumlahBayar
		$this->JumlahBayar->LinkCustomAttributes = "";
		$this->JumlahBayar->HrefValue = "";
		$this->JumlahBayar->TooltipValue = "";

		// Acc
		$this->Acc->LinkCustomAttributes = "";
		$this->Acc->HrefValue = "";
		$this->Acc->TooltipValue = "";

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Render edit row values
	function RenderEditRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

		// DaftarmID
		$this->DaftarmID->EditAttrs["class"] = "form-control";
		$this->DaftarmID->EditCustomAttributes = "";
		$this->DaftarmID->EditValue = $this->DaftarmID->CurrentValue;
		$this->DaftarmID->ViewCustomAttributes = "";

		// UserID
		$this->_UserID->EditAttrs["class"] = "form-control";
		$this->_UserID->EditCustomAttributes = "";

		// TglJam
		$this->TglJam->EditAttrs["class"] = "form-control";
		$this->TglJam->EditCustomAttributes = "";
		$this->TglJam->EditValue = ew_FormatDateTime($this->TglJam->CurrentValue, 8);
		$this->TglJam->PlaceHolder = ew_RemoveHtml($this->TglJam->FldCaption());

		// BuktiBayar
		$this->BuktiBayar->EditAttrs["class"] = "form-control";
		$this->BuktiBayar->EditCustomAttributes = "";
		if (!ew_Empty($this->BuktiBayar->Upload->DbValue)) {
			$this->BuktiBayar->ImageWidth = EW_THUMBNAIL_DEFAULT_WIDTH;
			$this->BuktiBayar->ImageHeight = EW_THUMBNAIL_DEFAULT_HEIGHT;
			$this->BuktiBayar->ImageAlt = $this->BuktiBayar->FldAlt();
			$this->BuktiBayar->EditValue = $this->BuktiBayar->Upload->DbValue;
		} else {
			$this->BuktiBayar->EditValue = "";
		}
		if (!ew_Empty($this->BuktiBayar->CurrentValue))
			$this->BuktiBayar->Upload->FileName = $this->BuktiBayar->CurrentValue;

		// JumlahBayar
		$this->JumlahBayar->EditAttrs["class"] = "form-control";
		$this->JumlahBayar->EditCustomAttributes = "";
		$this->JumlahBayar->EditValue = $this->JumlahBayar->CurrentValue;
		$this->JumlahBayar->PlaceHolder = ew_RemoveHtml($this->JumlahBayar->FldCaption());
		if (strval($this->JumlahBayar->EditValue) <> "" && is_numeric($this->JumlahBayar->EditValue)) $this->JumlahBayar->EditValue = ew_FormatNumber($this->JumlahBayar->EditValue, -2, -2, -2, -2);

		// Acc
		$this->Acc->EditCustomAttributes = "";
		$this->Acc->EditValue = $this->Acc->Options(FALSE);

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Aggregate list row values
	function AggregateListRowValues() {
	}

	// Aggregate list row (for rendering)
	function AggregateListRow() {

		// Call Row Rendered event
		$this->Row_Rendered();
	}
	var $ExportDoc;

	// Export data in HTML/CSV/Word/Excel/Email/PDF format
	function ExportDocument(&$Doc, &$Recordset, $StartRec, $StopRec, $ExportPageType = "") {
		if (!$Recordset || !$Doc)
			return;
		if (!$Doc->ExportCustom) {

			// Write header
			$Doc->ExportTableHeader();
			if ($Doc->Horizontal) { // Horizontal format, write header
				$Doc->BeginExportRow();
				if ($ExportPageType == "view") {
					if ($this->_UserID->Exportable) $Doc->ExportCaption($this->_UserID);
					if ($this->TglJam->Exportable) $Doc->ExportCaption($this->TglJam);
					if ($this->BuktiBayar->Exportable) $Doc->ExportCaption($this->BuktiBayar);
					if ($this->JumlahBayar->Exportable) $Doc->ExportCaption($this->JumlahBayar);
					if ($this->Acc->Exportable) $Doc->ExportCaption($this->Acc);
				} else {
					if ($this->DaftarmID->Exportable) $Doc->ExportCaption($this->DaftarmID);
					if ($this->_UserID->Exportable) $Doc->ExportCaption($this->_UserID);
					if ($this->TglJam->Exportable) $Doc->ExportCaption($this->TglJam);
					if ($this->BuktiBayar->Exportable) $Doc->ExportCaption($this->BuktiBayar);
					if ($this->JumlahBayar->Exportable) $Doc->ExportCaption($this->JumlahBayar);
					if ($this->Acc->Exportable) $Doc->ExportCaption($this->Acc);
				}
				$Doc->EndExportRow();
			}
		}

		// Move to first record
		$RecCnt = $StartRec - 1;
		if (!$Recordset->EOF) {
			$Recordset->MoveFirst();
			if ($StartRec > 1)
				$Recordset->Move($StartRec - 1);
		}
		while (!$Recordset->EOF && $RecCnt < $StopRec) {
			$RecCnt++;
			if (intval($RecCnt) >= intval($StartRec)) {
				$RowCnt = intval($RecCnt) - intval($StartRec) + 1;

				// Page break
				if ($this->ExportPageBreakCount > 0) {
					if ($RowCnt > 1 && ($RowCnt - 1) % $this->ExportPageBreakCount == 0)
						$Doc->ExportPageBreak();
				}
				$this->LoadListRowValues($Recordset);

				// Render row
				$this->RowType = EW_ROWTYPE_VIEW; // Render view
				$this->ResetAttrs();
				$this->RenderListRow();
				if (!$Doc->ExportCustom) {
					$Doc->BeginExportRow($RowCnt); // Allow CSS styles if enabled
					if ($ExportPageType == "view") {
						if ($this->_UserID->Exportable) $Doc->ExportField($this->_UserID);
						if ($this->TglJam->Exportable) $Doc->ExportField($this->TglJam);
						if ($this->BuktiBayar->Exportable) $Doc->ExportField($this->BuktiBayar);
						if ($this->JumlahBayar->Exportable) $Doc->ExportField($this->JumlahBayar);
						if ($this->Acc->Exportable) $Doc->ExportField($this->Acc);
					} else {
						if ($this->DaftarmID->Exportable) $Doc->ExportField($this->DaftarmID);
						if ($this->_UserID->Exportable) $Doc->ExportField($this->_UserID);
						if ($this->TglJam->Exportable) $Doc->ExportField($this->TglJam);
						if ($this->BuktiBayar->Exportable) $Doc->ExportField($this->BuktiBayar);
						if ($this->JumlahBayar->Exportable) $Doc->ExportField($this->JumlahBayar);
						if ($this->Acc->Exportable) $Doc->ExportField($this->Acc);
					}
					$Doc->EndExportRow();
				}
			}

			// Call Row Export server event
			if ($Doc->ExportCustom)
				$this->Row_Export($Recordset->fields);
			$Recordset->MoveNext();
		}
		if (!$Doc->ExportCustom) {
			$Doc->ExportTableFooter();
		}
	}

	// Get auto fill value
	function GetAutoFill($id, $val) {
		$rsarr = array();
		$rowcnt = 0;

		// Output
		if (is_array($rsarr) && $rowcnt > 0) {
			$fldcnt = count($rsarr[0]);
			for ($i = 0; $i < $rowcnt; $i++) {
				for ($j = 0; $j < $fldcnt; $j++) {
					$str = strval($rsarr[$i][$j]);
					$str = ew_ConvertToUtf8($str);
					if (isset($post["keepCRLF"])) {
						$str = str_replace(array("\r", "\n"), array("\\r", "\\n"), $str);
					} else {
						$str = str_replace(array("\r", "\n"), array(" ", " "), $str);
					}
					$rsarr[$i][$j] = $str;
				}
			}
			return ew_ArrayToJson($rsarr);
		} else {
			return FALSE;
		}
	}

	// Write Audit Trail start/end for grid update
	function WriteAuditTrailDummy($typ) {
		$table = 't_daftarm';
		$usr = CurrentUserID();
		ew_WriteAuditTrail("log", ew_StdCurrentDateTime(), ew_ScriptName(), $usr, $typ, $table, "", "", "", "");
	}

	// Write Audit Trail (add page)
	function WriteAuditTrailOnAdd(&$rs) {
		global $Language;
		if (!$this->AuditTrailOnAdd) return;
		$table = 't_daftarm';

		// Get key value
		$key = "";
		if ($key <> "") $key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rs['DaftarmID'];

		// Write Audit Trail
		$dt = ew_StdCurrentDateTime();
		$id = ew_ScriptName();
		$usr = CurrentUserID();
		foreach (array_keys($rs) as $fldname) {
			if (array_key_exists($fldname, $this->fields) && $this->fields[$fldname]->FldDataType <> EW_DATATYPE_BLOB) { // Ignore BLOB fields
				if ($this->fields[$fldname]->FldHtmlTag == "PASSWORD") {
					$newvalue = $Language->Phrase("PasswordMask"); // Password Field
				} elseif ($this->fields[$fldname]->FldDataType == EW_DATATYPE_MEMO) {
					if (EW_AUDIT_TRAIL_TO_DATABASE)
						$newvalue = $rs[$fldname];
					else
						$newvalue = "[MEMO]"; // Memo Field
				} elseif ($this->fields[$fldname]->FldDataType == EW_DATATYPE_XML) {
					$newvalue = "[XML]"; // XML Field
				} else {
					$newvalue = $rs[$fldname];
				}
				ew_WriteAuditTrail("log", $dt, $id, $usr, "A", $table, $fldname, $key, "", $newvalue);
			}
		}
	}

	// Write Audit Trail (edit page)
	function WriteAuditTrailOnEdit(&$rsold, &$rsnew) {
		global $Language;
		if (!$this->AuditTrailOnEdit) return;
		$table = 't_daftarm';

		// Get key value
		$key = "";
		if ($key <> "") $key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rsold['DaftarmID'];

		// Write Audit Trail
		$dt = ew_StdCurrentDateTime();
		$id = ew_ScriptName();
		$usr = CurrentUserID();
		foreach (array_keys($rsnew) as $fldname) {
			if (array_key_exists($fldname, $this->fields) && array_key_exists($fldname, $rsold) && $this->fields[$fldname]->FldDataType <> EW_DATATYPE_BLOB) { // Ignore BLOB fields
				if ($this->fields[$fldname]->FldDataType == EW_DATATYPE_DATE) { // DateTime field
					$modified = (ew_FormatDateTime($rsold[$fldname], 0) <> ew_FormatDateTime($rsnew[$fldname], 0));
				} else {
					$modified = !ew_CompareValue($rsold[$fldname], $rsnew[$fldname]);
				}
				if ($modified) {
					if ($this->fields[$fldname]->FldHtmlTag == "PASSWORD") { // Password Field
						$oldvalue = $Language->Phrase("PasswordMask");
						$newvalue = $Language->Phrase("PasswordMask");
					} elseif ($this->fields[$fldname]->FldDataType == EW_DATATYPE_MEMO) { // Memo field
						if (EW_AUDIT_TRAIL_TO_DATABASE) {
							$oldvalue = $rsold[$fldname];
							$newvalue = $rsnew[$fldname];
						} else {
							$oldvalue = "[MEMO]";
							$newvalue = "[MEMO]";
						}
					} elseif ($this->fields[$fldname]->FldDataType == EW_DATATYPE_XML) { // XML field
						$oldvalue = "[XML]";
						$newvalue = "[XML]";
					} else {
						$oldvalue = $rsold[$fldname];
						$newvalue = $rsnew[$fldname];
					}
					ew_WriteAuditTrail("log", $dt, $id, $usr, "U", $table, $fldname, $key, $oldvalue, $newvalue);
				}
			}
		}
	}

	// Write Audit Trail (delete page)
	function WriteAuditTrailOnDelete(&$rs) {
		global $Language;
		if (!$this->AuditTrailOnDelete) return;
		$table = 't_daftarm';

		// Get key value
		$key = "";
		if ($key <> "")
			$key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rs['DaftarmID'];

		// Write Audit Trail
		$dt = ew_StdCurrentDateTime();
		$id = ew_ScriptName();
		$curUser = CurrentUserID();
		foreach (array_keys($rs) as $fldname) {
			if (array_key_exists($fldname, $this->fields) && $this->fields[$fldname]->FldDataType <> EW_DATATYPE_BLOB) { // Ignore BLOB fields
				if ($this->fields[$fldname]->FldHtmlTag == "PASSWORD") {
					$oldvalue = $Language->Phrase("PasswordMask"); // Password Field
				} elseif ($this->fields[$fldname]->FldDataType == EW_DATATYPE_MEMO) {
					if (EW_AUDIT_TRAIL_TO_DATABASE)
						$oldvalue = $rs[$fldname];
					else
						$oldvalue = "[MEMO]"; // Memo field
				} elseif ($this->fields[$fldname]->FldDataType == EW_DATATYPE_XML) {
					$oldvalue = "[XML]"; // XML field
				} else {
					$oldvalue = $rs[$fldname];
				}
				ew_WriteAuditTrail("log", $dt, $id, $curUser, "D", $table, $fldname, $key, $oldvalue, "");
			}
		}
	}

	// Table level events
	// Recordset Selecting event
	function Recordset_Selecting(&$filter) {

		// Enter your code here	
	}

	// Recordset Selected event
	function Recordset_Selected(&$rs) {

		//echo "Recordset Selected";
	}

	// Recordset Search Validated event
	function Recordset_SearchValidated() {

		// Example:
		//$this->MyField1->AdvancedSearch->SearchValue = "your search criteria"; // Search value

	}

	// Recordset Searching event
	function Recordset_Searching(&$filter) {

		// Enter your code here	
	}

	// Row_Selecting event
	function Row_Selecting(&$filter) {

		// Enter your code here	
	}

	// Row Selected event
	function Row_Selected(&$rs) {

		//echo "Row Selected";
	}

	// Row Inserting event
	function Row_Inserting($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Inserted event
	function Row_Inserted($rsold, &$rsnew) {

		//echo "Row Inserted"
	}

	// Row Updating event
	function Row_Updating($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Updated event
	function Row_Updated($rsold, &$rsnew) {

		//echo "Row Updated";
	}

	// Row Update Conflict event
	function Row_UpdateConflict($rsold, &$rsnew) {

		// Enter your code here
		// To ignore conflict, set return value to FALSE

		return TRUE;
	}

	// Grid Inserting event
	function Grid_Inserting() {

		// Enter your code here
		// To reject grid insert, set return value to FALSE

		return TRUE;
	}

	// Grid Inserted event
	function Grid_Inserted($rsnew) {

		//echo "Grid Inserted";
	}

	// Grid Updating event
	function Grid_Updating($rsold) {

		// Enter your code here
		// To reject grid update, set return value to FALSE

		return TRUE;
	}

	// Grid Updated event
	function Grid_Updated($rsold, $rsnew) {

		//echo "Grid Updated";
	}

	// Row Deleting event
	function Row_Deleting(&$rs) {

		// Enter your code here
		// To cancel, set return value to False

		return TRUE;
	}

	// Row Deleted event
	function Row_Deleted(&$rs) {

		//echo "Row Deleted";
	}

	// Email Sending event
	function Email_Sending(&$Email, &$Args) {

		//var_dump($Email); var_dump($Args); exit();
		return TRUE;
	}

	// Lookup Selecting event
	function Lookup_Selecting($fld, &$filter) {

		//var_dump($fld->FldName, $fld->LookupFilters, $filter); // Uncomment to view the filter
		// Enter your code here

	}

	// Row Rendering event
	function Row_Rendering() {

		// Enter your code here	
	}

	// Row Rendered event
	function Row_Rendered() {

		// To view properties of field class, use:
		//var_dump($this-><FieldName>);

		if (CurrentUserLevel() >= 0) {
			$this->Acc->Disabled = TRUE;
		}
	}

	// User ID Filtering event
	function UserID_Filtering(&$filter) {

		// Enter your code here
	}
}
?>
