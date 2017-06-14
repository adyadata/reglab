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

$t_daftarm_add = NULL; // Initialize page object first

class ct_daftarm_add extends ct_daftarm {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{BDF410E2-F8A1-44BF-83FA-BA321C2274F7}";

	// Table name
	var $TableName = 't_daftarm';

	// Page object name
	var $PageObjName = 't_daftarm_add';

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
			define("EW_PAGE_ID", 'add', TRUE);

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
		if (!$Security->CanAdd()) {
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
	var $FormClassName = "form-horizontal ewForm ewAddForm";
	var $IsModal = FALSE;
	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
	var $StartRec;
	var $Priv = 0;
	var $OldRecordset;
	var $CopyRecord;

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

		// Process form if post back
		if (@$_POST["a_add"] <> "") {
			$this->CurrentAction = $_POST["a_add"]; // Get form action
			$this->CopyRecord = $this->LoadOldRecord(); // Load old recordset
			$this->LoadFormValues(); // Load form values
		} else { // Not post back

			// Load key values from QueryString
			$this->CopyRecord = TRUE;
			if (@$_GET["DaftarmID"] != "") {
				$this->DaftarmID->setQueryStringValue($_GET["DaftarmID"]);
				$this->setKey("DaftarmID", $this->DaftarmID->CurrentValue); // Set up key
			} else {
				$this->setKey("DaftarmID", ""); // Clear key
				$this->CopyRecord = FALSE;
			}
			if ($this->CopyRecord) {
				$this->CurrentAction = "C"; // Copy record
			} else {
				$this->CurrentAction = "I"; // Display blank record
			}
		}

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Set up detail parameters
		$this->SetUpDetailParms();

		// Validate form if post back
		if (@$_POST["a_add"] <> "") {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = "I"; // Form error, reset action
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues(); // Restore form values
				$this->setFailureMessage($gsFormError);
			}
		} else {
			if ($this->CurrentAction == "I") // Load default values for blank record
				$this->LoadDefaultValues();
		}

		// Perform action based on action code
		switch ($this->CurrentAction) {
			case "I": // Blank record, no action required
				break;
			case "C": // Copy an existing record
				if (!$this->LoadRow()) { // Load record based on key
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("t_daftarmlist.php"); // No matching record, return to list
				}

				// Set up detail parameters
				$this->SetUpDetailParms();
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					if ($this->getCurrentDetailTable() <> "") // Master/detail add
						$sReturnUrl = $this->GetDetailUrl();
					else
						$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "t_daftarmlist.php")
						$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to list page with correct master key if necessary
					elseif (ew_GetPageName($sReturnUrl) == "t_daftarmview.php")
						$sReturnUrl = $this->GetViewUrl(); // View page, return to view page with keyurl directly
					$this->Page_Terminate($sReturnUrl); // Clean up and return
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Add failed, restore form values

					// Set up detail parameters
					$this->SetUpDetailParms();
				}
		}

		// Render row based on row type
		$this->RowType = EW_ROWTYPE_ADD; // Render add type

		// Render row
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Get upload files
	function GetUploadFiles() {
		global $objForm, $Language;

		// Get upload data
		$this->BuktiBayar->Upload->Index = $objForm->Index;
		$this->BuktiBayar->Upload->UploadFile();
		$this->BuktiBayar->CurrentValue = $this->BuktiBayar->Upload->FileName;
	}

	// Load default values
	function LoadDefaultValues() {
		$this->_UserID->CurrentValue = 0;
		$this->TglJam->CurrentValue = "1970-01-01 00:00:00";
		$this->BuktiBayar->Upload->DbValue = NULL;
		$this->BuktiBayar->OldValue = $this->BuktiBayar->Upload->DbValue;
		$this->BuktiBayar->CurrentValue = NULL; // Clear file related field
		$this->JumlahBayar->CurrentValue = 0.00;
		$this->Acc->CurrentValue = 0;
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
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->_UserID->CurrentValue = $this->_UserID->FormValue;
		$this->TglJam->CurrentValue = $this->TglJam->FormValue;
		$this->TglJam->CurrentValue = ew_UnFormatDateTime($this->TglJam->CurrentValue, 0);
		$this->JumlahBayar->CurrentValue = $this->JumlahBayar->FormValue;
		$this->Acc->CurrentValue = $this->Acc->FormValue;
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

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("DaftarmID")) <> "")
			$this->DaftarmID->CurrentValue = $this->getKey("DaftarmID"); // DaftarmID
		else
			$bValidKey = FALSE;

		// Load old recordset
		if ($bValidKey) {
			$this->CurrentFilter = $this->KeyFilter();
			$sSql = $this->SQL();
			$conn = &$this->Connection();
			$this->OldRecordset = ew_LoadRecordset($sSql, $conn);
			$this->LoadRowValues($this->OldRecordset); // Load row values
		} else {
			$this->OldRecordset = NULL;
		}
		return $bValidKey;
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
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// UserID
			$this->_UserID->EditCustomAttributes = "";
			if (trim(strval($this->_UserID->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`UserID`" . ew_SearchString("=", $this->_UserID->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `UserID`, `Nama` AS `DispFld`, `NIM` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `t_user`";
			$sWhereWrk = "";
			$this->_UserID->LookupFilters = array("dx1" => '`Nama`', "dx2" => '`NIM`');
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			if (!$GLOBALS["t_daftarm"]->UserIDAllow("add")) $sWhereWrk = $GLOBALS["t_user"]->AddUserIDFilter($sWhereWrk);
			$this->Lookup_Selecting($this->_UserID, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = ew_HtmlEncode($rswrk->fields('DispFld'));
				$arwrk[2] = ew_HtmlEncode($rswrk->fields('Disp2Fld'));
				$this->_UserID->ViewValue = $this->_UserID->DisplayValue($arwrk);
			} else {
				$this->_UserID->ViewValue = $Language->Phrase("PleaseSelect");
			}
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->_UserID->EditValue = $arwrk;

			// TglJam
			$this->TglJam->EditAttrs["class"] = "form-control";
			$this->TglJam->EditCustomAttributes = "";
			$this->TglJam->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->TglJam->CurrentValue, 8));
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
			if (($this->CurrentAction == "I" || $this->CurrentAction == "C") && !$this->EventCancelled) ew_RenderUploadField($this->BuktiBayar);

			// JumlahBayar
			$this->JumlahBayar->EditAttrs["class"] = "form-control";
			$this->JumlahBayar->EditCustomAttributes = "";
			$this->JumlahBayar->EditValue = ew_HtmlEncode($this->JumlahBayar->CurrentValue);
			$this->JumlahBayar->PlaceHolder = ew_RemoveHtml($this->JumlahBayar->FldCaption());
			if (strval($this->JumlahBayar->EditValue) <> "" && is_numeric($this->JumlahBayar->EditValue)) $this->JumlahBayar->EditValue = ew_FormatNumber($this->JumlahBayar->EditValue, -2, -2, -2, -2);

			// Acc
			$this->Acc->EditCustomAttributes = "";
			$this->Acc->EditValue = $this->Acc->Options(FALSE);

			// Add refer script
			// UserID

			$this->_UserID->LinkCustomAttributes = "";
			$this->_UserID->HrefValue = "";

			// TglJam
			$this->TglJam->LinkCustomAttributes = "";
			$this->TglJam->HrefValue = "";

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
		if (!ew_CheckDateDef($this->TglJam->FormValue)) {
			ew_AddMessage($gsFormError, $this->TglJam->FldErrMsg());
		}
		if (!ew_CheckNumber($this->JumlahBayar->FormValue)) {
			ew_AddMessage($gsFormError, $this->JumlahBayar->FldErrMsg());
		}

		// Validate detail grid
		$DetailTblVar = explode(",", $this->getCurrentDetailTable());
		if (in_array("t_daftard", $DetailTblVar) && $GLOBALS["t_daftard"]->DetailAdd) {
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

	// Add record
	function AddRow($rsold = NULL) {
		global $Language, $Security;
		$conn = &$this->Connection();

		// Begin transaction
		if ($this->getCurrentDetailTable() <> "")
			$conn->BeginTrans();

		// Load db values from rsold
		if ($rsold) {
			$this->LoadDbValues($rsold);
		}
		$rsnew = array();

		// UserID
		$this->_UserID->SetDbValueDef($rsnew, $this->_UserID->CurrentValue, 0, strval($this->_UserID->CurrentValue) == "");

		// TglJam
		$this->TglJam->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->TglJam->CurrentValue, 0), ew_CurrentDate(), strval($this->TglJam->CurrentValue) == "");

		// BuktiBayar
		if ($this->BuktiBayar->Visible && !$this->BuktiBayar->Upload->KeepFile) {
			$this->BuktiBayar->Upload->DbValue = ""; // No need to delete old file
			if ($this->BuktiBayar->Upload->FileName == "") {
				$rsnew['BuktiBayar'] = NULL;
			} else {
				$rsnew['BuktiBayar'] = $this->BuktiBayar->Upload->FileName;
			}
		}

		// JumlahBayar
		$this->JumlahBayar->SetDbValueDef($rsnew, $this->JumlahBayar->CurrentValue, 0, strval($this->JumlahBayar->CurrentValue) == "");

		// Acc
		$this->Acc->SetDbValueDef($rsnew, $this->Acc->CurrentValue, 0, strval($this->Acc->CurrentValue) == "");
		if ($this->BuktiBayar->Visible && !$this->BuktiBayar->Upload->KeepFile) {
			if (!ew_Empty($this->BuktiBayar->Upload->Value)) {
				$rsnew['BuktiBayar'] = ew_UploadFileNameEx(ew_UploadPathEx(TRUE, $this->BuktiBayar->UploadPath), $rsnew['BuktiBayar']); // Get new file name
			}
		}

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);
		if ($bInsertRow) {
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			$AddRow = $this->Insert($rsnew);
			$conn->raiseErrorFn = '';
			if ($AddRow) {
				if ($this->BuktiBayar->Visible && !$this->BuktiBayar->Upload->KeepFile) {
					if (!ew_Empty($this->BuktiBayar->Upload->Value)) {
						if (!$this->BuktiBayar->Upload->SaveToFile($this->BuktiBayar->UploadPath, $rsnew['BuktiBayar'], TRUE)) {
							$this->setFailureMessage($Language->Phrase("UploadErrMsg7"));
							return FALSE;
						}
					}
				}
			}
		} else {
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("InsertCancelled"));
			}
			$AddRow = FALSE;
		}

		// Add detail records
		if ($AddRow) {
			$DetailTblVar = explode(",", $this->getCurrentDetailTable());
			if (in_array("t_daftard", $DetailTblVar) && $GLOBALS["t_daftard"]->DetailAdd) {
				$GLOBALS["t_daftard"]->DaftarmID->setSessionValue($this->DaftarmID->CurrentValue); // Set master key
				if (!isset($GLOBALS["t_daftard_grid"])) $GLOBALS["t_daftard_grid"] = new ct_daftard_grid(); // Get detail page object
				$Security->LoadCurrentUserLevel($this->ProjectID . "t_daftard"); // Load user level of detail table
				$AddRow = $GLOBALS["t_daftard_grid"]->GridInsert();
				$Security->LoadCurrentUserLevel($this->ProjectID . $this->TableName); // Restore user level of master table
				if (!$AddRow)
					$GLOBALS["t_daftard"]->DaftarmID->setSessionValue(""); // Clear master key if insert failed
			}
		}

		// Commit/Rollback transaction
		if ($this->getCurrentDetailTable() <> "") {
			if ($AddRow) {
				$conn->CommitTrans(); // Commit transaction
			} else {
				$conn->RollbackTrans(); // Rollback transaction
			}
		}
		if ($AddRow) {

			// Call Row Inserted event
			$rs = ($rsold == NULL) ? NULL : $rsold->fields;
			$this->Row_Inserted($rs, $rsnew);
		}

		// BuktiBayar
		ew_CleanUploadTempPath($this->BuktiBayar, $this->BuktiBayar->Upload->Index);
		return $AddRow;
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
				if ($GLOBALS["t_daftard_grid"]->DetailAdd) {
					if ($this->CopyRecord)
						$GLOBALS["t_daftard_grid"]->CurrentMode = "copy";
					else
						$GLOBALS["t_daftard_grid"]->CurrentMode = "add";
					$GLOBALS["t_daftard_grid"]->CurrentAction = "gridadd";

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
		$PageId = ($this->CurrentAction == "C") ? "Copy" : "Add";
		$Breadcrumb->Add("add", $PageId, $url);
	}

	// Setup lookup filters of a field
	function SetupLookupFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		case "x__UserID":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `UserID` AS `LinkFld`, `Nama` AS `DispFld`, `NIM` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `t_user`";
			$sWhereWrk = "{filter}";
			$this->_UserID->LookupFilters = array("dx1" => '`Nama`', "dx2" => '`NIM`');
			if (!$GLOBALS["t_daftarm"]->UserIDAllow("add")) $sWhereWrk = $GLOBALS["t_user"]->AddUserIDFilter($sWhereWrk);
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`UserID` = {filter_value}', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->_UserID, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
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
if (!isset($t_daftarm_add)) $t_daftarm_add = new ct_daftarm_add();

// Page init
$t_daftarm_add->Page_Init();

// Page main
$t_daftarm_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$t_daftarm_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "add";
var CurrentForm = ft_daftarmadd = new ew_Form("ft_daftarmadd", "add");

// Validate form
ft_daftarmadd.Validate = function() {
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
ft_daftarmadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ft_daftarmadd.ValidateRequired = true;
<?php } else { ?>
ft_daftarmadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
ft_daftarmadd.Lists["x__UserID"] = {"LinkField":"x__UserID","Ajax":true,"AutoFill":false,"DisplayFields":["x_Nama","x_NIM","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"t_user"};
ft_daftarmadd.Lists["x_Acc"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
ft_daftarmadd.Lists["x_Acc"].Options = <?php echo json_encode($t_daftarm->Acc->Options()) ?>;

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php if (!$t_daftarm_add->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $t_daftarm_add->ShowPageHeader(); ?>
<?php
$t_daftarm_add->ShowMessage();
?>
<form name="ft_daftarmadd" id="ft_daftarmadd" class="<?php echo $t_daftarm_add->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($t_daftarm_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $t_daftarm_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="t_daftarm">
<input type="hidden" name="a_add" id="a_add" value="A">
<?php if ($t_daftarm_add->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div>
<?php if ($t_daftarm->_UserID->Visible) { // UserID ?>
	<div id="r__UserID" class="form-group">
		<label id="elh_t_daftarm__UserID" for="x__UserID" class="col-sm-2 control-label ewLabel"><?php echo $t_daftarm->_UserID->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $t_daftarm->_UserID->CellAttributes() ?>>
<span id="el_t_daftarm__UserID">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x__UserID"><?php echo (strval($t_daftarm->_UserID->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $t_daftarm->_UserID->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($t_daftarm->_UserID->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x__UserID',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="t_daftarm" data-field="x__UserID" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $t_daftarm->_UserID->DisplayValueSeparatorAttribute() ?>" name="x__UserID" id="x__UserID" value="<?php echo $t_daftarm->_UserID->CurrentValue ?>"<?php echo $t_daftarm->_UserID->EditAttributes() ?>>
<input type="hidden" name="s_x__UserID" id="s_x__UserID" value="<?php echo $t_daftarm->_UserID->LookupFilterQuery() ?>">
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
ew_CreateCalendar("ft_daftarmadd", "x_TglJam", 0);
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
<input type="hidden" name="fa_x_BuktiBayar" id= "fa_x_BuktiBayar" value="0">
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
<div id="tp_x_Acc" class="ewTemplate"><input type="radio" data-table="t_daftarm" data-field="x_Acc" data-value-separator="<?php echo $t_daftarm->Acc->DisplayValueSeparatorAttribute() ?>" name="x_Acc" id="x_Acc" value="{value}"<?php echo $t_daftarm->Acc->EditAttributes() ?>></div>
<div id="dsl_x_Acc" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $t_daftarm->Acc->RadioButtonListHtml(FALSE, "x_Acc") ?>
</div></div>
</span>
<?php echo $t_daftarm->Acc->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<?php
	if (in_array("t_daftard", explode(",", $t_daftarm->getCurrentDetailTable())) && $t_daftard->DetailAdd) {
?>
<?php if ($t_daftarm->getCurrentDetailTable() <> "") { ?>
<h4 class="ewDetailCaption"><?php echo $Language->TablePhrase("t_daftard", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "t_daftardgrid.php" ?>
<?php } ?>
<?php if (!$t_daftarm_add->IsModal) { ?>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $t_daftarm_add->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
<?php } ?>
</form>
<script type="text/javascript">
ft_daftarmadd.Init();
</script>
<?php
$t_daftarm_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$t_daftarm_add->Page_Terminate();
?>
