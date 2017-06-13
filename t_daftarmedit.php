<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "t_daftarminfo.php" ?>
<?php include_once "t_userinfo.php" ?>
<?php include_once "t_daftardgridcls.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$t_daftarm_edit = NULL; // Initialize page object first

class ct_daftarm_edit extends ct_daftarm {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{BDF410E2-F8A1-44BF-83FA-BA321C2274F7}";

	// Table name
	var $TableName = 't_daftarm';

	// Page object name
	var $PageObjName = 't_daftarm_edit';

	// Page name
	function PageName() {
		return ew_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		$PageUrl = ew_CurrentPage() . "?";
		if ($this->UseTokenInUrl) $PageUrl .= "t=" . $this->TableVar . "&"; // Add page token
		return $PageUrl;
	}

	// Message
	function getMessage() {
		return @$_SESSION[EW_SESSION_MESSAGE];
	}

	function setMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_MESSAGE], $v);
	}

	function getFailureMessage() {
		return @$_SESSION[EW_SESSION_FAILURE_MESSAGE];
	}

	function setFailureMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_FAILURE_MESSAGE], $v);
	}

	function getSuccessMessage() {
		return @$_SESSION[EW_SESSION_SUCCESS_MESSAGE];
	}

	function setSuccessMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_SUCCESS_MESSAGE], $v);
	}

	function getWarningMessage() {
		return @$_SESSION[EW_SESSION_WARNING_MESSAGE];
	}

	function setWarningMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_WARNING_MESSAGE], $v);
	}

	// Methods to clear message
	function ClearMessage() {
		$_SESSION[EW_SESSION_MESSAGE] = "";
	}

	function ClearFailureMessage() {
		$_SESSION[EW_SESSION_FAILURE_MESSAGE] = "";
	}

	function ClearSuccessMessage() {
		$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = "";
	}

	function ClearWarningMessage() {
		$_SESSION[EW_SESSION_WARNING_MESSAGE] = "";
	}

	function ClearMessages() {
		$_SESSION[EW_SESSION_MESSAGE] = "";
		$_SESSION[EW_SESSION_FAILURE_MESSAGE] = "";
		$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = "";
		$_SESSION[EW_SESSION_WARNING_MESSAGE] = "";
	}

	// Show message
	function ShowMessage() {
		$hidden = FALSE;
		$html = "";

		// Message
		$sMessage = $this->getMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sMessage, "");
		if ($sMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sMessage;
			$html .= "<div class=\"alert alert-info ewInfo\">" . $sMessage . "</div>";
			$_SESSION[EW_SESSION_MESSAGE] = ""; // Clear message in Session
		}

		// Warning message
		$sWarningMessage = $this->getWarningMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sWarningMessage, "warning");
		if ($sWarningMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sWarningMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sWarningMessage;
			$html .= "<div class=\"alert alert-warning ewWarning\">" . $sWarningMessage . "</div>";
			$_SESSION[EW_SESSION_WARNING_MESSAGE] = ""; // Clear message in Session
		}

		// Success message
		$sSuccessMessage = $this->getSuccessMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sSuccessMessage, "success");
		if ($sSuccessMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sSuccessMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sSuccessMessage;
			$html .= "<div class=\"alert alert-success ewSuccess\">" . $sSuccessMessage . "</div>";
			$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = ""; // Clear message in Session
		}

		// Failure message
		$sErrorMessage = $this->getFailureMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sErrorMessage, "failure");
		if ($sErrorMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sErrorMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sErrorMessage;
			$html .= "<div class=\"alert alert-danger ewError\">" . $sErrorMessage . "</div>";
			$_SESSION[EW_SESSION_FAILURE_MESSAGE] = ""; // Clear message in Session
		}
		echo "<div class=\"ewMessageDialog\"" . (($hidden) ? " style=\"display: none;\"" : "") . ">" . $html . "</div>";
	}
	var $PageHeader;
	var $PageFooter;

	// Show Page Header
	function ShowPageHeader() {
		$sHeader = $this->PageHeader;
		$this->Page_DataRendering($sHeader);
		if ($sHeader <> "") { // Header exists, display
			echo "<p>" . $sHeader . "</p>";
		}
	}

	// Show Page Footer
	function ShowPageFooter() {
		$sFooter = $this->PageFooter;
		$this->Page_DataRendered($sFooter);
		if ($sFooter <> "") { // Footer exists, display
			echo "<p>" . $sFooter . "</p>";
		}
	}

	// Validate page request
	function IsPageRequest() {
		global $objForm;
		if ($this->UseTokenInUrl) {
			if ($objForm)
				return ($this->TableVar == $objForm->GetValue("t"));
			if (@$_GET["t"] <> "")
				return ($this->TableVar == $_GET["t"]);
		} else {
			return TRUE;
		}
	}
	var $Token = "";
	var $TokenTimeout = 0;
	var $CheckToken = EW_CHECK_TOKEN;
	var $CheckTokenFn = "ew_CheckToken";
	var $CreateTokenFn = "ew_CreateToken";

	// Valid Post
	function ValidPost() {
		if (!$this->CheckToken || !ew_IsHttpPost())
			return TRUE;
		if (!isset($_POST[EW_TOKEN_NAME]))
			return FALSE;
		$fn = $this->CheckTokenFn;
		if (is_callable($fn))
			return $fn($_POST[EW_TOKEN_NAME], $this->TokenTimeout);
		return FALSE;
	}

	// Create Token
	function CreateToken() {
		global $gsToken;
		if ($this->CheckToken) {
			$fn = $this->CreateTokenFn;
			if ($this->Token == "" && is_callable($fn)) // Create token
				$this->Token = $fn();
			$gsToken = $this->Token; // Save to global variable
		}
	}

	//
	// Page class constructor
	//
	function __construct() {
		global $conn, $Language;
		global $UserTable, $UserTableConn;
		$GLOBALS["Page"] = &$this;
		$this->TokenTimeout = ew_SessionTimeoutTime();

		// Language object
		if (!isset($Language)) $Language = new cLanguage();

		// Parent constuctor
		parent::__construct();

		// Table object (t_daftarm)
		if (!isset($GLOBALS["t_daftarm"]) || get_class($GLOBALS["t_daftarm"]) == "ct_daftarm") {
			$GLOBALS["t_daftarm"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["t_daftarm"];
		}

		// Table object (t_user)
		if (!isset($GLOBALS['t_user'])) $GLOBALS['t_user'] = new ct_user();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 't_daftarm', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect($this->DBID);

		// User table object (t_user)
		if (!isset($UserTable)) {
			$UserTable = new ct_user();
			$UserTableConn = Conn($UserTable->DBID);
		}
	}

	//
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsCustomExport, $gsExportFile, $UserProfile, $Language, $Security, $objForm;

		// Security
		$Security = new cAdvancedSecurity();
		if (!$Security->IsLoggedIn()) $Security->AutoLogin();
		if ($Security->IsLoggedIn()) $Security->TablePermission_Loading();
		$Security->LoadCurrentUserLevel($this->ProjectID . $this->TableName);
		if ($Security->IsLoggedIn()) $Security->TablePermission_Loaded();
		if (!$Security->CanEdit()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage(ew_DeniedMsg()); // Set no permission
			if ($Security->CanList())
				$this->Page_Terminate(ew_GetUrl("t_daftarmlist.php"));
			else
				$this->Page_Terminate(ew_GetUrl("login.php"));
		}
		if ($Security->IsLoggedIn()) {
			$Security->UserID_Loading();
			$Security->LoadUserID();
			$Security->UserID_Loaded();
		}

		// Create form object
		$objForm = new cFormObj();
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->_UserID->SetVisibility();
		$this->TglJam->SetVisibility();
		$this->BuktiBayar->SetVisibility();
		$this->JumlahBayar->SetVisibility();
		$this->Acc->SetVisibility();

		// Global Page Loading event (in userfn*.php)
		Page_Loading();

		// Page Load event
		$this->Page_Load();

		// Check token
		if (!$this->ValidPost()) {
			echo $Language->Phrase("InvalidPostRequest");
			$this->Page_Terminate();
			exit();
		}

		// Process auto fill
		if (@$_POST["ajax"] == "autofill") {

			// Process auto fill for detail table 't_daftard'
			if (@$_POST["grid"] == "ft_daftardgrid") {
				if (!isset($GLOBALS["t_daftard_grid"])) $GLOBALS["t_daftard_grid"] = new ct_daftard_grid;
				$GLOBALS["t_daftard_grid"]->Page_Init();
				$this->Page_Terminate();
				exit();
			}
			$results = $this->GetAutoFill(@$_POST["name"], @$_POST["q"]);
			if ($results) {

				// Clean output buffer
				if (!EW_DEBUG_ENABLED && ob_get_length())
					ob_end_clean();
				echo $results;
				$this->Page_Terminate();
				exit();
			}
		}

		// Create Token
		$this->CreateToken();
	}

	//
	// Page_Terminate
	//
	function Page_Terminate($url = "") {
		global $gsExportFile, $gTmpImages;

		// Page Unload event
		$this->Page_Unload();

		// Global Page Unloaded event (in userfn*.php)
		Page_Unloaded();

		// Export
		global $EW_EXPORT, $t_daftarm;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($t_daftarm);
				$doc->Text = $sContent;
				if ($this->Export == "email")
					echo $this->ExportEmail($doc->Text);
				else
					$doc->Export();
				ew_DeleteTmpImages(); // Delete temp images
				exit();
			}
		}
		$this->Page_Redirecting($url);

		 // Close connection
		ew_CloseConn();

		// Go to URL if specified
		if ($url <> "") {
			if (!EW_DEBUG_ENABLED && ob_get_length())
				ob_end_clean();

			// Handle modal response
			if ($this->IsModal) {
				$row = array();
				$row["url"] = $url;
				echo ew_ArrayToJson(array($row));
			} else {
				header("Location: " . $url);
			}
		}
		exit();
	}
	var $FormClassName = "form-horizontal ewForm ewEditForm";
	var $IsModal = FALSE;
	var $DbMasterFilter;
	var $DbDetailFilter;
	var $DisplayRecs = 1;
	var $StartRec;
	var $StopRec;
	var $TotalRecs = 0;
	var $RecRange = 10;
	var $Pager;
	var $RecCnt;
	var $RecKey = array();
	var $Recordset;

	// 
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;
		global $gbSkipHeaderFooter;

		// Check modal
		$this->IsModal = (@$_GET["modal"] == "1" || @$_POST["modal"] == "1");
		if ($this->IsModal)
			$gbSkipHeaderFooter = TRUE;

		// Load current record
		$bLoadCurrentRecord = FALSE;
		$sReturnUrl = "";
		$bMatchRecord = FALSE;

		// Load key from QueryString
		if (@$_GET["DaftarmID"] <> "") {
			$this->DaftarmID->setQueryStringValue($_GET["DaftarmID"]);
			$this->RecKey["DaftarmID"] = $this->DaftarmID->QueryStringValue;
		} else {
			$bLoadCurrentRecord = TRUE;
		}

		// Load recordset
		$this->StartRec = 1; // Initialize start position
		if ($this->Recordset = $this->LoadRecordset()) // Load records
			$this->TotalRecs = $this->Recordset->RecordCount(); // Get record count
		if ($this->TotalRecs <= 0) { // No record found
			if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
				$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
			$this->Page_Terminate("t_daftarmlist.php"); // Return to list page
		} elseif ($bLoadCurrentRecord) { // Load current record position
			$this->SetUpStartRec(); // Set up start record position

			// Point to current record
			if (intval($this->StartRec) <= intval($this->TotalRecs)) {
				$bMatchRecord = TRUE;
				$this->Recordset->Move($this->StartRec-1);
			}
		} else { // Match key values
			while (!$this->Recordset->EOF) {
				if (strval($this->DaftarmID->CurrentValue) == strval($this->Recordset->fields('DaftarmID'))) {
					$this->setStartRecordNumber($this->StartRec); // Save record position
					$bMatchRecord = TRUE;
					break;
				} else {
					$this->StartRec++;
					$this->Recordset->MoveNext();
				}
			}
		}

		// Process form if post back
		if (@$_POST["a_edit"] <> "") {
			$this->CurrentAction = $_POST["a_edit"]; // Get action code
			$this->LoadFormValues(); // Get form values

			// Set up detail parameters
			$this->SetUpDetailParms();
		} else {
			$this->CurrentAction = "I"; // Default action is display
		}

		// Validate form if post back
		if (@$_POST["a_edit"] <> "") {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = ""; // Form error, reset action
				$this->setFailureMessage($gsFormError);
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues();
			}
		}
		switch ($this->CurrentAction) {
			case "I": // Get a record to display
				if (!$bMatchRecord) {
					if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
						$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
					$this->Page_Terminate("t_daftarmlist.php"); // Return to list page
				} else {
					$this->LoadRowValues($this->Recordset); // Load row values
				}

				// Set up detail parameters
				$this->SetUpDetailParms();
				break;
			Case "U": // Update
				if ($this->getCurrentDetailTable() <> "") // Master/detail edit
					$sReturnUrl = $this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=" . $this->getCurrentDetailTable()); // Master/Detail view page
				else
					$sReturnUrl = $this->getReturnUrl();
				if (ew_GetPageName($sReturnUrl) == "t_daftarmlist.php")
					$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to list page with correct master key if necessary
				$this->SendEmail = TRUE; // Send email on update success
				if ($this->EditRow()) { // Update record based on key
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Update success
					$this->Page_Terminate($sReturnUrl); // Return to caller
				} elseif ($this->getFailureMessage() == $Language->Phrase("NoRecord")) {
					$this->Page_Terminate($sReturnUrl); // Return to caller
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Restore form values if update failed

					// Set up detail parameters
					$this->SetUpDetailParms();
				}
		}

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Render the record
		$this->RowType = EW_ROWTYPE_EDIT; // Render as Edit
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Set up starting record parameters
	function SetUpStartRec() {
		if ($this->DisplayRecs == 0)
			return;
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET[EW_TABLE_START_REC] <> "") { // Check for "start" parameter
				$this->StartRec = $_GET[EW_TABLE_START_REC];
				$this->setStartRecordNumber($this->StartRec);
			} elseif (@$_GET[EW_TABLE_PAGE_NO] <> "") {
				$PageNo = $_GET[EW_TABLE_PAGE_NO];
				if (is_numeric($PageNo)) {
					$this->StartRec = ($PageNo-1)*$this->DisplayRecs+1;
					if ($this->StartRec <= 0) {
						$this->StartRec = 1;
					} elseif ($this->StartRec >= intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1) {
						$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1;
					}
					$this->setStartRecordNumber($this->StartRec);
				}
			}
		}
		$this->StartRec = $this->getStartRecordNumber();

		// Check if correct start record counter
		if (!is_numeric($this->StartRec) || $this->StartRec == "") { // Avoid invalid start record counter
			$this->StartRec = 1; // Reset start record counter
			$this->setStartRecordNumber($this->StartRec);
		} elseif (intval($this->StartRec) > intval($this->TotalRecs)) { // Avoid starting record > total records
			$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to last page first record
			$this->setStartRecordNumber($this->StartRec);
		} elseif (($this->StartRec-1) % $this->DisplayRecs <> 0) {
			$this->StartRec = intval(($this->StartRec-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to page boundary
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Get upload files
	function GetUploadFiles() {
		global $objForm, $Language;

		// Get upload data
		$this->BuktiBayar->Upload->Index = $objForm->Index;
		$this->BuktiBayar->Upload->UploadFile();
		$this->BuktiBayar->CurrentValue = $this->BuktiBayar->Upload->FileName;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		$this->GetUploadFiles(); // Get upload files
		if (!$this->_UserID->FldIsDetailKey) {
			$this->_UserID->setFormValue($objForm->GetValue("x__UserID"));
		}
		if (!$this->TglJam->FldIsDetailKey) {
			$this->TglJam->setFormValue($objForm->GetValue("x_TglJam"));
			$this->TglJam->CurrentValue = ew_UnFormatDateTime($this->TglJam->CurrentValue, 0);
		}
		if (!$this->JumlahBayar->FldIsDetailKey) {
			$this->JumlahBayar->setFormValue($objForm->GetValue("x_JumlahBayar"));
		}
		if (!$this->Acc->FldIsDetailKey) {
			$this->Acc->setFormValue($objForm->GetValue("x_Acc"));
		}
		if (!$this->DaftarmID->FldIsDetailKey)
			$this->DaftarmID->setFormValue($objForm->GetValue("x_DaftarmID"));
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->DaftarmID->CurrentValue = $this->DaftarmID->FormValue;
		$this->_UserID->CurrentValue = $this->_UserID->FormValue;
		$this->TglJam->CurrentValue = $this->TglJam->FormValue;
		$this->TglJam->CurrentValue = ew_UnFormatDateTime($this->TglJam->CurrentValue, 0);
		$this->JumlahBayar->CurrentValue = $this->JumlahBayar->FormValue;
		$this->Acc->CurrentValue = $this->Acc->FormValue;
	}

	// Load recordset
	function LoadRecordset($offset = -1, $rowcnt = -1) {

		// Load List page SQL
		$sSql = $this->SelectSQL();
		$conn = &$this->Connection();

		// Load recordset
		$dbtype = ew_GetConnectionType($this->DBID);
		if ($this->UseSelectLimit) {
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			if ($dbtype == "MSSQL") {
				$rs = $conn->SelectLimit($sSql, $rowcnt, $offset, array("_hasOrderBy" => trim($this->getOrderBy()) || trim($this->getSessionOrderBy())));
			} else {
				$rs = $conn->SelectLimit($sSql, $rowcnt, $offset);
			}
			$conn->raiseErrorFn = '';
		} else {
			$rs = ew_LoadRecordset($sSql, $conn);
		}

		// Call Recordset Selected event
		$this->Recordset_Selected($rs);
		return $rs;
	}

	// Load row based on key values
	function LoadRow() {
		global $Security, $Language;
		$sFilter = $this->KeyFilter();

		// Call Row Selecting event
		$this->Row_Selecting($sFilter);

		// Load SQL based on filter
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$conn = &$this->Connection();
		$res = FALSE;
		$rs = ew_LoadRecordset($sSql, $conn);
		if ($rs && !$rs->EOF) {
			$res = TRUE;
			$this->LoadRowValues($rs); // Load row values
			$rs->Close();
		}
		return $res;
	}

	// Load row values from recordset
	function LoadRowValues(&$rs) {
		if (!$rs || $rs->EOF) return;

		// Call Row Selected event
		$row = &$rs->fields;
		$this->Row_Selected($row);
		$this->DaftarmID->setDbValue($rs->fields('DaftarmID'));
		$this->_UserID->setDbValue($rs->fields('UserID'));
		$this->TglJam->setDbValue($rs->fields('TglJam'));
		$this->BuktiBayar->Upload->DbValue = $rs->fields('BuktiBayar');
		$this->BuktiBayar->CurrentValue = $this->BuktiBayar->Upload->DbValue;
		$this->JumlahBayar->setDbValue($rs->fields('JumlahBayar'));
		$this->Acc->setDbValue($rs->fields('Acc'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->DaftarmID->DbValue = $row['DaftarmID'];
		$this->_UserID->DbValue = $row['UserID'];
		$this->TglJam->DbValue = $row['TglJam'];
		$this->BuktiBayar->Upload->DbValue = $row['BuktiBayar'];
		$this->JumlahBayar->DbValue = $row['JumlahBayar'];
		$this->Acc->DbValue = $row['Acc'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Convert decimal values if posted back

		if ($this->JumlahBayar->FormValue == $this->JumlahBayar->CurrentValue && is_numeric(ew_StrToFloat($this->JumlahBayar->CurrentValue)))
			$this->JumlahBayar->CurrentValue = ew_StrToFloat($this->JumlahBayar->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// DaftarmID
		// UserID
		// TglJam
		// BuktiBayar
		// JumlahBayar
		// Acc

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// DaftarmID
		$this->DaftarmID->ViewValue = $this->DaftarmID->CurrentValue;
		$this->DaftarmID->ViewCustomAttributes = "";

		// UserID
		$this->_UserID->ViewValue = $this->_UserID->CurrentValue;
		$this->_UserID->ViewCustomAttributes = "";

		// TglJam
		$this->TglJam->ViewValue = $this->TglJam->CurrentValue;
		$this->TglJam->ViewValue = ew_FormatDateTime($this->TglJam->ViewValue, 0);
		$this->TglJam->ViewCustomAttributes = "";

		// BuktiBayar
		if (!ew_Empty($this->BuktiBayar->Upload->DbValue)) {
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
			$this->Acc->ViewValue = "";
			$arwrk = explode(",", strval($this->Acc->CurrentValue));
			$cnt = count($arwrk);
			for ($ari = 0; $ari < $cnt; $ari++) {
				$this->Acc->ViewValue .= $this->Acc->OptionCaption(trim($arwrk[$ari]));
				if ($ari < $cnt-1) $this->Acc->ViewValue .= ew_ViewOptionSeparator($ari);
			}
		} else {
			$this->Acc->ViewValue = NULL;
		}
		$this->Acc->ViewCustomAttributes = "";

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
			$this->BuktiBayar->HrefValue = "";
			$this->BuktiBayar->HrefValue2 = $this->BuktiBayar->UploadPath . $this->BuktiBayar->Upload->DbValue;
			$this->BuktiBayar->TooltipValue = "";

			// JumlahBayar
			$this->JumlahBayar->LinkCustomAttributes = "";
			$this->JumlahBayar->HrefValue = "";
			$this->JumlahBayar->TooltipValue = "";

			// Acc
			$this->Acc->LinkCustomAttributes = "";
			$this->Acc->HrefValue = "";
			$this->Acc->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// UserID
			$this->_UserID->EditAttrs["class"] = "form-control";
			$this->_UserID->EditCustomAttributes = "";
			$this->_UserID->EditValue = ew_HtmlEncode($this->_UserID->CurrentValue);
			$this->_UserID->PlaceHolder = ew_RemoveHtml($this->_UserID->FldCaption());

			// TglJam
			$this->TglJam->EditAttrs["class"] = "form-control";
			$this->TglJam->EditCustomAttributes = "";
			$this->TglJam->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->TglJam->CurrentValue, 8));
			$this->TglJam->PlaceHolder = ew_RemoveHtml($this->TglJam->FldCaption());

			// BuktiBayar
			$this->BuktiBayar->EditAttrs["class"] = "form-control";
			$this->BuktiBayar->EditCustomAttributes = "";
			if (!ew_Empty($this->BuktiBayar->Upload->DbValue)) {
				$this->BuktiBayar->EditValue = $this->BuktiBayar->Upload->DbValue;
			} else {
				$this->BuktiBayar->EditValue = "";
			}
			if (!ew_Empty($this->BuktiBayar->CurrentValue))
				$this->BuktiBayar->Upload->FileName = $this->BuktiBayar->CurrentValue;
			if ($this->CurrentAction == "I" && !$this->EventCancelled) ew_RenderUploadField($this->BuktiBayar);

			// JumlahBayar
			$this->JumlahBayar->EditAttrs["class"] = "form-control";
			$this->JumlahBayar->EditCustomAttributes = "";
			$this->JumlahBayar->EditValue = ew_HtmlEncode($this->JumlahBayar->CurrentValue);
			$this->JumlahBayar->PlaceHolder = ew_RemoveHtml($this->JumlahBayar->FldCaption());
			if (strval($this->JumlahBayar->EditValue) <> "" && is_numeric($this->JumlahBayar->EditValue)) $this->JumlahBayar->EditValue = ew_FormatNumber($this->JumlahBayar->EditValue, -2, -2, -2, -2);

			// Acc
			$this->Acc->EditCustomAttributes = "";
			$this->Acc->EditValue = $this->Acc->Options(FALSE);

			// Edit refer script
			// UserID

			$this->_UserID->LinkCustomAttributes = "";
			$this->_UserID->HrefValue = "";

			// TglJam
			$this->TglJam->LinkCustomAttributes = "";
			$this->TglJam->HrefValue = "";

			// BuktiBayar
			$this->BuktiBayar->LinkCustomAttributes = "";
			$this->BuktiBayar->HrefValue = "";
			$this->BuktiBayar->HrefValue2 = $this->BuktiBayar->UploadPath . $this->BuktiBayar->Upload->DbValue;

			// JumlahBayar
			$this->JumlahBayar->LinkCustomAttributes = "";
			$this->JumlahBayar->HrefValue = "";

			// Acc
			$this->Acc->LinkCustomAttributes = "";
			$this->Acc->HrefValue = "";
		}
		if ($this->RowType == EW_ROWTYPE_ADD ||
			$this->RowType == EW_ROWTYPE_EDIT ||
			$this->RowType == EW_ROWTYPE_SEARCH) { // Add / Edit / Search row
			$this->SetupFieldTitles();
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Validate form
	function ValidateForm() {
		global $Language, $gsFormError;

		// Initialize form error message
		$gsFormError = "";

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return ($gsFormError == "");
		if (!ew_CheckInteger($this->_UserID->FormValue)) {
			ew_AddMessage($gsFormError, $this->_UserID->FldErrMsg());
		}
		if (!ew_CheckDateDef($this->TglJam->FormValue)) {
			ew_AddMessage($gsFormError, $this->TglJam->FldErrMsg());
		}
		if (!ew_CheckNumber($this->JumlahBayar->FormValue)) {
			ew_AddMessage($gsFormError, $this->JumlahBayar->FldErrMsg());
		}

		// Validate detail grid
		$DetailTblVar = explode(",", $this->getCurrentDetailTable());
		if (in_array("t_daftard", $DetailTblVar) && $GLOBALS["t_daftard"]->DetailEdit) {
			if (!isset($GLOBALS["t_daftard_grid"])) $GLOBALS["t_daftard_grid"] = new ct_daftard_grid(); // get detail page object
			$GLOBALS["t_daftard_grid"]->ValidateGridForm();
		}

		// Return validate result
		$ValidateForm = ($gsFormError == "");

		// Call Form_CustomValidate event
		$sFormCustomError = "";
		$ValidateForm = $ValidateForm && $this->Form_CustomValidate($sFormCustomError);
		if ($sFormCustomError <> "") {
			ew_AddMessage($gsFormError, $sFormCustomError);
		}
		return $ValidateForm;
	}

	// Update record based on key values
	function EditRow() {
		global $Security, $Language;
		$sFilter = $this->KeyFilter();
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$conn = &$this->Connection();
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE)
			return FALSE;
		if ($rs->EOF) {
			$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
			$EditRow = FALSE; // Update Failed
		} else {

			// Begin transaction
			if ($this->getCurrentDetailTable() <> "")
				$conn->BeginTrans();

			// Save old values
			$rsold = &$rs->fields;
			$this->LoadDbValues($rsold);
			$rsnew = array();

			// UserID
			$this->_UserID->SetDbValueDef($rsnew, $this->_UserID->CurrentValue, 0, $this->_UserID->ReadOnly);

			// TglJam
			$this->TglJam->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->TglJam->CurrentValue, 0), ew_CurrentDate(), $this->TglJam->ReadOnly);

			// BuktiBayar
			if ($this->BuktiBayar->Visible && !$this->BuktiBayar->ReadOnly && !$this->BuktiBayar->Upload->KeepFile) {
				$this->BuktiBayar->Upload->DbValue = $rsold['BuktiBayar']; // Get original value
				if ($this->BuktiBayar->Upload->FileName == "") {
					$rsnew['BuktiBayar'] = NULL;
				} else {
					$rsnew['BuktiBayar'] = $this->BuktiBayar->Upload->FileName;
				}
			}

			// JumlahBayar
			$this->JumlahBayar->SetDbValueDef($rsnew, $this->JumlahBayar->CurrentValue, 0, $this->JumlahBayar->ReadOnly);

			// Acc
			$this->Acc->SetDbValueDef($rsnew, $this->Acc->CurrentValue, 0, $this->Acc->ReadOnly);
			if ($this->BuktiBayar->Visible && !$this->BuktiBayar->Upload->KeepFile) {
				if (!ew_Empty($this->BuktiBayar->Upload->Value)) {
					$rsnew['BuktiBayar'] = ew_UploadFileNameEx(ew_UploadPathEx(TRUE, $this->BuktiBayar->UploadPath), $rsnew['BuktiBayar']); // Get new file name
				}
			}

			// Call Row Updating event
			$bUpdateRow = $this->Row_Updating($rsold, $rsnew);
			if ($bUpdateRow) {
				$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
				if (count($rsnew) > 0)
					$EditRow = $this->Update($rsnew, "", $rsold);
				else
					$EditRow = TRUE; // No field to update
				$conn->raiseErrorFn = '';
				if ($EditRow) {
					if ($this->BuktiBayar->Visible && !$this->BuktiBayar->Upload->KeepFile) {
						if (!ew_Empty($this->BuktiBayar->Upload->Value)) {
							if (!$this->BuktiBayar->Upload->SaveToFile($this->BuktiBayar->UploadPath, $rsnew['BuktiBayar'], TRUE)) {
								$this->setFailureMessage($Language->Phrase("UploadErrMsg7"));
								return FALSE;
							}
						}
					}
				}

				// Update detail records
				$DetailTblVar = explode(",", $this->getCurrentDetailTable());
				if ($EditRow) {
					if (in_array("t_daftard", $DetailTblVar) && $GLOBALS["t_daftard"]->DetailEdit) {
						if (!isset($GLOBALS["t_daftard_grid"])) $GLOBALS["t_daftard_grid"] = new ct_daftard_grid(); // Get detail page object
						$Security->LoadCurrentUserLevel($this->ProjectID . "t_daftard"); // Load user level of detail table
						$EditRow = $GLOBALS["t_daftard_grid"]->GridUpdate();
						$Security->LoadCurrentUserLevel($this->ProjectID . $this->TableName); // Restore user level of master table
					}
				}

				// Commit/Rollback transaction
				if ($this->getCurrentDetailTable() <> "") {
					if ($EditRow) {
						$conn->CommitTrans(); // Commit transaction
					} else {
						$conn->RollbackTrans(); // Rollback transaction
					}
				}
			} else {
				if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

					// Use the message, do nothing
				} elseif ($this->CancelMessage <> "") {
					$this->setFailureMessage($this->CancelMessage);
					$this->CancelMessage = "";
				} else {
					$this->setFailureMessage($Language->Phrase("UpdateCancelled"));
				}
				$EditRow = FALSE;
			}
		}

		// Call Row_Updated event
		if ($EditRow)
			$this->Row_Updated($rsold, $rsnew);
		$rs->Close();

		// BuktiBayar
		ew_CleanUploadTempPath($this->BuktiBayar, $this->BuktiBayar->Upload->Index);
		return $EditRow;
	}

	// Set up detail parms based on QueryString
	function SetUpDetailParms() {

		// Get the keys for master table
		if (isset($_GET[EW_TABLE_SHOW_DETAIL])) {
			$sDetailTblVar = $_GET[EW_TABLE_SHOW_DETAIL];
			$this->setCurrentDetailTable($sDetailTblVar);
		} else {
			$sDetailTblVar = $this->getCurrentDetailTable();
		}
		if ($sDetailTblVar <> "") {
			$DetailTblVar = explode(",", $sDetailTblVar);
			if (in_array("t_daftard", $DetailTblVar)) {
				if (!isset($GLOBALS["t_daftard_grid"]))
					$GLOBALS["t_daftard_grid"] = new ct_daftard_grid;
				if ($GLOBALS["t_daftard_grid"]->DetailEdit) {
					$GLOBALS["t_daftard_grid"]->CurrentMode = "edit";
					$GLOBALS["t_daftard_grid"]->CurrentAction = "gridedit";

					// Save current master table to detail table
					$GLOBALS["t_daftard_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["t_daftard_grid"]->setStartRecordNumber(1);
					$GLOBALS["t_daftard_grid"]->DaftarmID->FldIsDetailKey = TRUE;
					$GLOBALS["t_daftard_grid"]->DaftarmID->CurrentValue = $this->DaftarmID->CurrentValue;
					$GLOBALS["t_daftard_grid"]->DaftarmID->setSessionValue($GLOBALS["t_daftard_grid"]->DaftarmID->CurrentValue);
				}
			}
		}
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("t_daftarmlist.php"), "", $this->TableVar, TRUE);
		$PageId = "edit";
		$Breadcrumb->Add("edit", $PageId, $url);
	}

	// Setup lookup filters of a field
	function SetupLookupFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		}
	}

	// Setup AutoSuggest filters of a field
	function SetupAutoSuggestFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		}
	}

	// Page Load event
	function Page_Load() {

		//echo "Page Load";
	}

	// Page Unload event
	function Page_Unload() {

		//echo "Page Unload";
	}

	// Page Redirecting event
	function Page_Redirecting(&$url) {

		// Example:
		//$url = "your URL";

	}

	// Message Showing event
	// $type = ''|'success'|'failure'|'warning'
	function Message_Showing(&$msg, $type) {
		if ($type == 'success') {

			//$msg = "your success message";
		} elseif ($type == 'failure') {

			//$msg = "your failure message";
		} elseif ($type == 'warning') {

			//$msg = "your warning message";
		} else {

			//$msg = "your message";
		}
	}

	// Page Render event
	function Page_Render() {

		//echo "Page Render";
	}

	// Page Data Rendering event
	function Page_DataRendering(&$header) {

		// Example:
		//$header = "your header";

	}

	// Page Data Rendered event
	function Page_DataRendered(&$footer) {

		// Example:
		//$footer = "your footer";

	}

	// Form Custom Validate event
	function Form_CustomValidate(&$CustomError) {

		// Return error message in CustomError
		return TRUE;
	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($t_daftarm_edit)) $t_daftarm_edit = new ct_daftarm_edit();

// Page init
$t_daftarm_edit->Page_Init();

// Page main
$t_daftarm_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$t_daftarm_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = ft_daftarmedit = new ew_Form("ft_daftarmedit", "edit");

// Validate form
ft_daftarmedit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "__UserID");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($t_daftarm->_UserID->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_TglJam");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($t_daftarm->TglJam->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_JumlahBayar");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($t_daftarm->JumlahBayar->FldErrMsg()) ?>");

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
	}

	// Process detail forms
	var dfs = $fobj.find("input[name='detailpage']").get();
	for (var i = 0; i < dfs.length; i++) {
		var df = dfs[i], val = df.value;
		if (val && ewForms[val])
			if (!ewForms[val].Validate())
				return false;
	}
	return true;
}

// Form_CustomValidate event
ft_daftarmedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ft_daftarmedit.ValidateRequired = true;
<?php } else { ?>
ft_daftarmedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
ft_daftarmedit.Lists["x_Acc[]"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
ft_daftarmedit.Lists["x_Acc[]"].Options = <?php echo json_encode($t_daftarm->Acc->Options()) ?>;

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php if (!$t_daftarm_edit->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $t_daftarm_edit->ShowPageHeader(); ?>
<?php
$t_daftarm_edit->ShowMessage();
?>
<?php if (!$t_daftarm_edit->IsModal) { ?>
<form name="ewPagerForm" class="form-horizontal ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($t_daftarm_edit->Pager)) $t_daftarm_edit->Pager = new cPrevNextPager($t_daftarm_edit->StartRec, $t_daftarm_edit->DisplayRecs, $t_daftarm_edit->TotalRecs) ?>
<?php if ($t_daftarm_edit->Pager->RecordCount > 0 && $t_daftarm_edit->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($t_daftarm_edit->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $t_daftarm_edit->PageUrl() ?>start=<?php echo $t_daftarm_edit->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($t_daftarm_edit->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $t_daftarm_edit->PageUrl() ?>start=<?php echo $t_daftarm_edit->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $t_daftarm_edit->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($t_daftarm_edit->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $t_daftarm_edit->PageUrl() ?>start=<?php echo $t_daftarm_edit->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($t_daftarm_edit->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $t_daftarm_edit->PageUrl() ?>start=<?php echo $t_daftarm_edit->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $t_daftarm_edit->Pager->PageCount ?></span>
</div>
<?php } ?>
<div class="clearfix"></div>
</form>
<?php } ?>
<form name="ft_daftarmedit" id="ft_daftarmedit" class="<?php echo $t_daftarm_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($t_daftarm_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $t_daftarm_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="t_daftarm">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<?php if ($t_daftarm_edit->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div>
<?php if ($t_daftarm->_UserID->Visible) { // UserID ?>
	<div id="r__UserID" class="form-group">
		<label id="elh_t_daftarm__UserID" for="x__UserID" class="col-sm-2 control-label ewLabel"><?php echo $t_daftarm->_UserID->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $t_daftarm->_UserID->CellAttributes() ?>>
<span id="el_t_daftarm__UserID">
<input type="text" data-table="t_daftarm" data-field="x__UserID" name="x__UserID" id="x__UserID" size="30" placeholder="<?php echo ew_HtmlEncode($t_daftarm->_UserID->getPlaceHolder()) ?>" value="<?php echo $t_daftarm->_UserID->EditValue ?>"<?php echo $t_daftarm->_UserID->EditAttributes() ?>>
</span>
<?php echo $t_daftarm->_UserID->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t_daftarm->TglJam->Visible) { // TglJam ?>
	<div id="r_TglJam" class="form-group">
		<label id="elh_t_daftarm_TglJam" for="x_TglJam" class="col-sm-2 control-label ewLabel"><?php echo $t_daftarm->TglJam->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $t_daftarm->TglJam->CellAttributes() ?>>
<span id="el_t_daftarm_TglJam">
<input type="text" data-table="t_daftarm" data-field="x_TglJam" name="x_TglJam" id="x_TglJam" placeholder="<?php echo ew_HtmlEncode($t_daftarm->TglJam->getPlaceHolder()) ?>" value="<?php echo $t_daftarm->TglJam->EditValue ?>"<?php echo $t_daftarm->TglJam->EditAttributes() ?>>
<?php if (!$t_daftarm->TglJam->ReadOnly && !$t_daftarm->TglJam->Disabled && !isset($t_daftarm->TglJam->EditAttrs["readonly"]) && !isset($t_daftarm->TglJam->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("ft_daftarmedit", "x_TglJam", 0);
</script>
<?php } ?>
</span>
<?php echo $t_daftarm->TglJam->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t_daftarm->BuktiBayar->Visible) { // BuktiBayar ?>
	<div id="r_BuktiBayar" class="form-group">
		<label id="elh_t_daftarm_BuktiBayar" class="col-sm-2 control-label ewLabel"><?php echo $t_daftarm->BuktiBayar->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $t_daftarm->BuktiBayar->CellAttributes() ?>>
<span id="el_t_daftarm_BuktiBayar">
<div id="fd_x_BuktiBayar">
<span title="<?php echo $t_daftarm->BuktiBayar->FldTitle() ? $t_daftarm->BuktiBayar->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($t_daftarm->BuktiBayar->ReadOnly || $t_daftarm->BuktiBayar->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="t_daftarm" data-field="x_BuktiBayar" name="x_BuktiBayar" id="x_BuktiBayar"<?php echo $t_daftarm->BuktiBayar->EditAttributes() ?>>
</span>
<input type="hidden" name="fn_x_BuktiBayar" id= "fn_x_BuktiBayar" value="<?php echo $t_daftarm->BuktiBayar->Upload->FileName ?>">
<?php if (@$_POST["fa_x_BuktiBayar"] == "0") { ?>
<input type="hidden" name="fa_x_BuktiBayar" id= "fa_x_BuktiBayar" value="0">
<?php } else { ?>
<input type="hidden" name="fa_x_BuktiBayar" id= "fa_x_BuktiBayar" value="1">
<?php } ?>
<input type="hidden" name="fs_x_BuktiBayar" id= "fs_x_BuktiBayar" value="255">
<input type="hidden" name="fx_x_BuktiBayar" id= "fx_x_BuktiBayar" value="<?php echo $t_daftarm->BuktiBayar->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_BuktiBayar" id= "fm_x_BuktiBayar" value="<?php echo $t_daftarm->BuktiBayar->UploadMaxFileSize ?>">
</div>
<table id="ft_x_BuktiBayar" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php echo $t_daftarm->BuktiBayar->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t_daftarm->JumlahBayar->Visible) { // JumlahBayar ?>
	<div id="r_JumlahBayar" class="form-group">
		<label id="elh_t_daftarm_JumlahBayar" for="x_JumlahBayar" class="col-sm-2 control-label ewLabel"><?php echo $t_daftarm->JumlahBayar->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $t_daftarm->JumlahBayar->CellAttributes() ?>>
<span id="el_t_daftarm_JumlahBayar">
<input type="text" data-table="t_daftarm" data-field="x_JumlahBayar" name="x_JumlahBayar" id="x_JumlahBayar" size="30" placeholder="<?php echo ew_HtmlEncode($t_daftarm->JumlahBayar->getPlaceHolder()) ?>" value="<?php echo $t_daftarm->JumlahBayar->EditValue ?>"<?php echo $t_daftarm->JumlahBayar->EditAttributes() ?>>
</span>
<?php echo $t_daftarm->JumlahBayar->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($t_daftarm->Acc->Visible) { // Acc ?>
	<div id="r_Acc" class="form-group">
		<label id="elh_t_daftarm_Acc" class="col-sm-2 control-label ewLabel"><?php echo $t_daftarm->Acc->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $t_daftarm->Acc->CellAttributes() ?>>
<span id="el_t_daftarm_Acc">
<div id="tp_x_Acc" class="ewTemplate"><input type="checkbox" data-table="t_daftarm" data-field="x_Acc" data-value-separator="<?php echo $t_daftarm->Acc->DisplayValueSeparatorAttribute() ?>" name="x_Acc[]" id="x_Acc[]" value="{value}"<?php echo $t_daftarm->Acc->EditAttributes() ?>></div>
<div id="dsl_x_Acc" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $t_daftarm->Acc->CheckBoxListHtml(FALSE, "x_Acc[]") ?>
</div></div>
</span>
<?php echo $t_daftarm->Acc->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<input type="hidden" data-table="t_daftarm" data-field="x_DaftarmID" name="x_DaftarmID" id="x_DaftarmID" value="<?php echo ew_HtmlEncode($t_daftarm->DaftarmID->CurrentValue) ?>">
<?php
	if (in_array("t_daftard", explode(",", $t_daftarm->getCurrentDetailTable())) && $t_daftard->DetailEdit) {
?>
<?php if ($t_daftarm->getCurrentDetailTable() <> "") { ?>
<h4 class="ewDetailCaption"><?php echo $Language->TablePhrase("t_daftard", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "t_daftardgrid.php" ?>
<?php } ?>
<?php if (!$t_daftarm_edit->IsModal) { ?>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $t_daftarm_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
<?php if (!isset($t_daftarm_edit->Pager)) $t_daftarm_edit->Pager = new cPrevNextPager($t_daftarm_edit->StartRec, $t_daftarm_edit->DisplayRecs, $t_daftarm_edit->TotalRecs) ?>
<?php if ($t_daftarm_edit->Pager->RecordCount > 0 && $t_daftarm_edit->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($t_daftarm_edit->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $t_daftarm_edit->PageUrl() ?>start=<?php echo $t_daftarm_edit->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($t_daftarm_edit->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $t_daftarm_edit->PageUrl() ?>start=<?php echo $t_daftarm_edit->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $t_daftarm_edit->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($t_daftarm_edit->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $t_daftarm_edit->PageUrl() ?>start=<?php echo $t_daftarm_edit->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($t_daftarm_edit->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $t_daftarm_edit->PageUrl() ?>start=<?php echo $t_daftarm_edit->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $t_daftarm_edit->Pager->PageCount ?></span>
</div>
<?php } ?>
<div class="clearfix"></div>
<?php } ?>
</form>
<script type="text/javascript">
ft_daftarmedit.Init();
</script>
<?php
$t_daftarm_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$t_daftarm_edit->Page_Terminate();
?>
