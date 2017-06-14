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

$t_daftarm_list = NULL; // Initialize page object first

class ct_daftarm_list extends ct_daftarm {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{BDF410E2-F8A1-44BF-83FA-BA321C2274F7}";

	// Table name
	var $TableName = 't_daftarm';

	// Page object name
	var $PageObjName = 't_daftarm_list';

	// Grid form hidden field names
	var $FormName = 'ft_daftarmlist';
	var $FormActionName = 'k_action';
	var $FormKeyName = 'k_key';
	var $FormOldKeyName = 'k_oldkey';
	var $FormBlankRowName = 'k_blankrow';
	var $FormKeyCountName = 'key_count';

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

	// Page URLs
	var $AddUrl;
	var $EditUrl;
	var $CopyUrl;
	var $DeleteUrl;
	var $ViewUrl;
	var $ListUrl;

	// Export URLs
	var $ExportPrintUrl;
	var $ExportHtmlUrl;
	var $ExportExcelUrl;
	var $ExportWordUrl;
	var $ExportXmlUrl;
	var $ExportCsvUrl;
	var $ExportPdfUrl;

	// Custom export
	var $ExportExcelCustom = FALSE;
	var $ExportWordCustom = FALSE;
	var $ExportPdfCustom = FALSE;
	var $ExportEmailCustom = FALSE;

	// Update URLs
	var $InlineAddUrl;
	var $InlineCopyUrl;
	var $InlineEditUrl;
	var $GridAddUrl;
	var $GridEditUrl;
	var $MultiDeleteUrl;
	var $MultiUpdateUrl;

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

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "t_daftarmadd.php?" . EW_TABLE_SHOW_DETAIL . "=";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "t_daftarmdelete.php";
		$this->MultiUpdateUrl = "t_daftarmupdate.php";

		// Table object (t_user)
		if (!isset($GLOBALS['t_user'])) $GLOBALS['t_user'] = new ct_user();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

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

		// List options
		$this->ListOptions = new cListOptions();
		$this->ListOptions->TableVar = $this->TableVar;

		// Export options
		$this->ExportOptions = new cListOptions();
		$this->ExportOptions->Tag = "div";
		$this->ExportOptions->TagClassName = "ewExportOption";

		// Other options
		$this->OtherOptions['addedit'] = new cListOptions();
		$this->OtherOptions['addedit']->Tag = "div";
		$this->OtherOptions['addedit']->TagClassName = "ewAddEditOption";
		$this->OtherOptions['detail'] = new cListOptions();
		$this->OtherOptions['detail']->Tag = "div";
		$this->OtherOptions['detail']->TagClassName = "ewDetailOption";
		$this->OtherOptions['action'] = new cListOptions();
		$this->OtherOptions['action']->Tag = "div";
		$this->OtherOptions['action']->TagClassName = "ewActionOption";

		// Filter options
		$this->FilterOptions = new cListOptions();
		$this->FilterOptions->Tag = "div";
		$this->FilterOptions->TagClassName = "ewFilterOption ft_daftarmlistsrch";

		// List actions
		$this->ListActions = new cListActions();
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
		if (!$Security->CanList()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage(ew_DeniedMsg()); // Set no permission
			$this->Page_Terminate(ew_GetUrl("index.php"));
		}
		if ($Security->IsLoggedIn()) {
			$Security->UserID_Loading();
			$Security->LoadUserID();
			$Security->UserID_Loaded();
		}

		// Create form object
		$objForm = new cFormObj();

		// Get export parameters
		$custom = "";
		if (@$_GET["export"] <> "") {
			$this->Export = $_GET["export"];
			$custom = @$_GET["custom"];
		} elseif (@$_POST["export"] <> "") {
			$this->Export = $_POST["export"];
			$custom = @$_POST["custom"];
		} elseif (ew_IsHttpPost()) {
			if (@$_POST["exporttype"] <> "")
				$this->Export = $_POST["exporttype"];
			$custom = @$_POST["custom"];
		} else {
			$this->setExportReturnUrl(ew_CurrentUrl());
		}
		$gsExportFile = $this->TableVar; // Get export file, used in header

		// Get custom export parameters
		if ($this->Export <> "" && $custom <> "") {
			$this->CustomExport = $this->Export;
			$this->Export = "print";
		}
		$gsCustomExport = $this->CustomExport;
		$gsExport = $this->Export; // Get export parameter, used in header

		// Update Export URLs
		if (defined("EW_USE_PHPEXCEL"))
			$this->ExportExcelCustom = FALSE;
		if ($this->ExportExcelCustom)
			$this->ExportExcelUrl .= "&amp;custom=1";
		if (defined("EW_USE_PHPWORD"))
			$this->ExportWordCustom = FALSE;
		if ($this->ExportWordCustom)
			$this->ExportWordUrl .= "&amp;custom=1";
		if ($this->ExportPdfCustom)
			$this->ExportPdfUrl .= "&amp;custom=1";
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action

		// Get grid add count
		$gridaddcnt = @$_GET[EW_TABLE_GRID_ADD_ROW_COUNT];
		if (is_numeric($gridaddcnt) && $gridaddcnt > 0)
			$this->GridAddRowCount = $gridaddcnt;

		// Set up list options
		$this->SetupListOptions();

		// Setup export options
		$this->SetupExportOptions();
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

		// Setup other options
		$this->SetupOtherOptions();

		// Set up custom action (compatible with old version)
		foreach ($this->CustomActions as $name => $action)
			$this->ListActions->Add($name, $action);

		// Show checkbox column if multiple action
		foreach ($this->ListActions->Items as $listaction) {
			if ($listaction->Select == EW_ACTION_MULTIPLE && $listaction->Allow) {
				$this->ListOptions->Items["checkbox"]->Visible = TRUE;
				break;
			}
		}
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
			header("Location: " . $url);
		}
		exit();
	}

	// Class variables
	var $ListOptions; // List options
	var $ExportOptions; // Export options
	var $SearchOptions; // Search options
	var $OtherOptions = array(); // Other options
	var $FilterOptions; // Filter options
	var $ListActions; // List actions
	var $SelectedCount = 0;
	var $SelectedIndex = 0;
	var $DisplayRecs = 20;
	var $StartRec;
	var $StopRec;
	var $TotalRecs = 0;
	var $RecRange = 10;
	var $Pager;
	var $DefaultSearchWhere = ""; // Default search WHERE clause
	var $SearchWhere = ""; // Search WHERE clause
	var $RecCnt = 0; // Record count
	var $EditRowCnt;
	var $StartRowCnt = 1;
	var $RowCnt = 0;
	var $Attrs = array(); // Row attributes and cell attributes
	var $RowIndex = 0; // Row index
	var $KeyCount = 0; // Key count
	var $RowAction = ""; // Row action
	var $RowOldKey = ""; // Row old key (for copy)
	var $RecPerRow = 0;
	var $MultiColumnClass;
	var $MultiColumnEditClass = "col-sm-12";
	var $MultiColumnCnt = 12;
	var $MultiColumnEditCnt = 12;
	var $GridCnt = 0;
	var $ColCnt = 0;
	var $DbMasterFilter = ""; // Master filter
	var $DbDetailFilter = ""; // Detail filter
	var $MasterRecordExists;	
	var $MultiSelectKey;
	var $Command;
	var $RestoreSearch = FALSE;
	var $DetailPages;
	var $Recordset;
	var $OldRecordset;

	//
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError, $gsSearchError, $Security;

		// Search filters
		$sSrchAdvanced = ""; // Advanced search filter
		$sSrchBasic = ""; // Basic search filter
		$sFilter = "";

		// Get command
		$this->Command = strtolower(@$_GET["cmd"]);
		if ($this->IsPageRequest()) { // Validate request

			// Process list action first
			if ($this->ProcessListAction()) // Ajax request
				$this->Page_Terminate();

			// Set up records per page
			$this->SetUpDisplayRecs();

			// Handle reset command
			$this->ResetCmd();

			// Set up Breadcrumb
			if ($this->Export == "")
				$this->SetupBreadcrumb();

			// Check QueryString parameters
			if (@$_GET["a"] <> "") {
				$this->CurrentAction = $_GET["a"];

				// Clear inline mode
				if ($this->CurrentAction == "cancel")
					$this->ClearInlineMode();

				// Switch to grid edit mode
				if ($this->CurrentAction == "gridedit")
					$this->GridEditMode();

				// Switch to inline edit mode
				if ($this->CurrentAction == "edit")
					$this->InlineEditMode();

				// Switch to inline add mode
				if ($this->CurrentAction == "add" || $this->CurrentAction == "copy")
					$this->InlineAddMode();

				// Switch to grid add mode
				if ($this->CurrentAction == "gridadd")
					$this->GridAddMode();
			} else {
				if (@$_POST["a_list"] <> "") {
					$this->CurrentAction = $_POST["a_list"]; // Get action

					// Grid Update
					if (($this->CurrentAction == "gridupdate" || $this->CurrentAction == "gridoverwrite") && @$_SESSION[EW_SESSION_INLINE_MODE] == "gridedit") {
						if ($this->ValidateGridForm()) {
							$bGridUpdate = $this->GridUpdate();
						} else {
							$bGridUpdate = FALSE;
							$this->setFailureMessage($gsFormError);
						}
						if (!$bGridUpdate) {
							$this->EventCancelled = TRUE;
							$this->CurrentAction = "gridedit"; // Stay in Grid Edit mode
						}
					}

					// Inline Update
					if (($this->CurrentAction == "update" || $this->CurrentAction == "overwrite") && @$_SESSION[EW_SESSION_INLINE_MODE] == "edit")
						$this->InlineUpdate();

					// Insert Inline
					if ($this->CurrentAction == "insert" && @$_SESSION[EW_SESSION_INLINE_MODE] == "add")
						$this->InlineInsert();

					// Grid Insert
					if ($this->CurrentAction == "gridinsert" && @$_SESSION[EW_SESSION_INLINE_MODE] == "gridadd") {
						if ($this->ValidateGridForm()) {
							$bGridInsert = $this->GridInsert();
						} else {
							$bGridInsert = FALSE;
							$this->setFailureMessage($gsFormError);
						}
						if (!$bGridInsert) {
							$this->EventCancelled = TRUE;
							$this->CurrentAction = "gridadd"; // Stay in Grid Add mode
						}
					}
				}
			}

			// Hide list options
			if ($this->Export <> "") {
				$this->ListOptions->HideAllOptions(array("sequence"));
				$this->ListOptions->UseDropDownButton = FALSE; // Disable drop down button
				$this->ListOptions->UseButtonGroup = FALSE; // Disable button group
			} elseif ($this->CurrentAction == "gridadd" || $this->CurrentAction == "gridedit") {
				$this->ListOptions->HideAllOptions();
				$this->ListOptions->UseDropDownButton = FALSE; // Disable drop down button
				$this->ListOptions->UseButtonGroup = FALSE; // Disable button group
			}

			// Hide options
			if ($this->Export <> "" || $this->CurrentAction <> "") {
				$this->ExportOptions->HideAllOptions();
				$this->FilterOptions->HideAllOptions();
			}

			// Hide other options
			if ($this->Export <> "") {
				foreach ($this->OtherOptions as &$option)
					$option->HideAllOptions();
			}

			// Show grid delete link for grid add / grid edit
			if ($this->AllowAddDeleteRow) {
				if ($this->CurrentAction == "gridadd" || $this->CurrentAction == "gridedit") {
					$item = $this->ListOptions->GetItem("griddelete");
					if ($item) $item->Visible = TRUE;
				}
			}

			// Get default search criteria
			ew_AddFilter($this->DefaultSearchWhere, $this->BasicSearchWhere(TRUE));
			ew_AddFilter($this->DefaultSearchWhere, $this->AdvancedSearchWhere(TRUE));

			// Get basic search values
			$this->LoadBasicSearchValues();

			// Get and validate search values for advanced search
			$this->LoadSearchValues(); // Get search values

			// Process filter list
			$this->ProcessFilterList();
			if (!$this->ValidateSearch())
				$this->setFailureMessage($gsSearchError);

			// Restore search parms from Session if not searching / reset / export
			if (($this->Export <> "" || $this->Command <> "search" && $this->Command <> "reset" && $this->Command <> "resetall") && $this->CheckSearchParms())
				$this->RestoreSearchParms();

			// Call Recordset SearchValidated event
			$this->Recordset_SearchValidated();

			// Set up sorting order
			$this->SetUpSortOrder();

			// Get basic search criteria
			if ($gsSearchError == "")
				$sSrchBasic = $this->BasicSearchWhere();

			// Get search criteria for advanced search
			if ($gsSearchError == "")
				$sSrchAdvanced = $this->AdvancedSearchWhere();
		}

		// Restore display records
		if ($this->getRecordsPerPage() <> "") {
			$this->DisplayRecs = $this->getRecordsPerPage(); // Restore from Session
		} else {
			$this->DisplayRecs = 20; // Load default
		}

		// Load Sorting Order
		$this->LoadSortOrder();

		// Load search default if no existing search criteria
		if (!$this->CheckSearchParms()) {

			// Load basic search from default
			$this->BasicSearch->LoadDefault();
			if ($this->BasicSearch->Keyword != "")
				$sSrchBasic = $this->BasicSearchWhere();

			// Load advanced search from default
			if ($this->LoadAdvancedSearchDefault()) {
				$sSrchAdvanced = $this->AdvancedSearchWhere();
			}
		}

		// Build search criteria
		ew_AddFilter($this->SearchWhere, $sSrchAdvanced);
		ew_AddFilter($this->SearchWhere, $sSrchBasic);

		// Call Recordset_Searching event
		$this->Recordset_Searching($this->SearchWhere);

		// Save search criteria
		if ($this->Command == "search" && !$this->RestoreSearch) {
			$this->setSearchWhere($this->SearchWhere); // Save to Session
			$this->StartRec = 1; // Reset start record counter
			$this->setStartRecordNumber($this->StartRec);
		} else {
			$this->SearchWhere = $this->getSearchWhere();
		}

		// Build filter
		$sFilter = "";
		if (!$Security->CanList())
			$sFilter = "(0=1)"; // Filter all records
		ew_AddFilter($sFilter, $this->DbDetailFilter);
		ew_AddFilter($sFilter, $this->SearchWhere);

		// Set up filter in session
		$this->setSessionWhere($sFilter);
		$this->CurrentFilter = "";

		// Export data only
		if ($this->CustomExport == "" && in_array($this->Export, array("html","word","excel","xml","csv","email","pdf"))) {
			$this->ExportData();
			$this->Page_Terminate(); // Terminate response
			exit();
		}

		// Load record count first
		if (!$this->IsAddOrEdit()) {
			$bSelectLimit = $this->UseSelectLimit;
			if ($bSelectLimit) {
				$this->TotalRecs = $this->SelectRecordCount();
			} else {
				if ($this->Recordset = $this->LoadRecordset())
					$this->TotalRecs = $this->Recordset->RecordCount();
			}
		}

		// Search options
		$this->SetupSearchOptions();
	}

	// Set up number of records displayed per page
	function SetUpDisplayRecs() {
		$sWrk = @$_GET[EW_TABLE_REC_PER_PAGE];
		if ($sWrk <> "") {
			if (is_numeric($sWrk)) {
				$this->DisplayRecs = intval($sWrk);
			} else {
				if (strtolower($sWrk) == "all") { // Display all records
					$this->DisplayRecs = -1;
				} else {
					$this->DisplayRecs = 20; // Non-numeric, load default
				}
			}
			$this->setRecordsPerPage($this->DisplayRecs); // Save to Session

			// Reset start position
			$this->StartRec = 1;
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	//  Exit inline mode
	function ClearInlineMode() {
		$this->setKey("DaftarmID", ""); // Clear inline edit key
		$this->JumlahBayar->FormValue = ""; // Clear form value
		$this->LastAction = $this->CurrentAction; // Save last action
		$this->CurrentAction = ""; // Clear action
		$_SESSION[EW_SESSION_INLINE_MODE] = ""; // Clear inline mode
	}

	// Switch to Grid Add mode
	function GridAddMode() {
		$_SESSION[EW_SESSION_INLINE_MODE] = "gridadd"; // Enabled grid add
	}

	// Switch to Grid Edit mode
	function GridEditMode() {
		$_SESSION[EW_SESSION_INLINE_MODE] = "gridedit"; // Enable grid edit
	}

	// Switch to Inline Edit mode
	function InlineEditMode() {
		global $Security, $Language;
		if (!$Security->CanEdit())
			$this->Page_Terminate("login.php"); // Go to login page
		$bInlineEdit = TRUE;
		if (@$_GET["DaftarmID"] <> "") {
			$this->DaftarmID->setQueryStringValue($_GET["DaftarmID"]);
		} else {
			$bInlineEdit = FALSE;
		}
		if ($bInlineEdit) {
			if ($this->LoadRow()) {
				$this->setKey("DaftarmID", $this->DaftarmID->CurrentValue); // Set up inline edit key
				$_SESSION[EW_SESSION_INLINE_MODE] = "edit"; // Enable inline edit
			}
		}
	}

	// Perform update to Inline Edit record
	function InlineUpdate() {
		global $Language, $objForm, $gsFormError;
		$objForm->Index = 1; 
		$this->LoadFormValues(); // Get form values

		// Validate form
		$bInlineUpdate = TRUE;
		if (!$this->ValidateForm()) {	
			$bInlineUpdate = FALSE; // Form error, reset action
			$this->setFailureMessage($gsFormError);
		} else {
			$bInlineUpdate = FALSE;
			$rowkey = strval($objForm->GetValue($this->FormKeyName));
			if ($this->SetupKeyValues($rowkey)) { // Set up key values
				if ($this->CheckInlineEditKey()) { // Check key
					$this->SendEmail = TRUE; // Send email on update success
					$bInlineUpdate = $this->EditRow(); // Update record
				} else {
					$bInlineUpdate = FALSE;
				}
			}
		}
		if ($bInlineUpdate) { // Update success
			if ($this->getSuccessMessage() == "")
				$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Set up success message
			$this->ClearInlineMode(); // Clear inline edit mode
		} else {
			if ($this->getFailureMessage() == "")
				$this->setFailureMessage($Language->Phrase("UpdateFailed")); // Set update failed message
			$this->EventCancelled = TRUE; // Cancel event
			$this->CurrentAction = "edit"; // Stay in edit mode
		}
	}

	// Check Inline Edit key
	function CheckInlineEditKey() {

		//CheckInlineEditKey = True
		if (strval($this->getKey("DaftarmID")) <> strval($this->DaftarmID->CurrentValue))
			return FALSE;
		return TRUE;
	}

	// Switch to Inline Add mode
	function InlineAddMode() {
		global $Security, $Language;
		if (!$Security->CanAdd())
			$this->Page_Terminate("login.php"); // Return to login page
		if ($this->CurrentAction == "copy") {
			if (@$_GET["DaftarmID"] <> "") {
				$this->DaftarmID->setQueryStringValue($_GET["DaftarmID"]);
				$this->setKey("DaftarmID", $this->DaftarmID->CurrentValue); // Set up key
			} else {
				$this->setKey("DaftarmID", ""); // Clear key
				$this->CurrentAction = "add";
			}
		}
		$_SESSION[EW_SESSION_INLINE_MODE] = "add"; // Enable inline add
	}

	// Perform update to Inline Add/Copy record
	function InlineInsert() {
		global $Language, $objForm, $gsFormError;
		$this->LoadOldRecord(); // Load old recordset
		$objForm->Index = 0;
		$this->LoadFormValues(); // Get form values

		// Validate form
		if (!$this->ValidateForm()) {
			$this->setFailureMessage($gsFormError); // Set validation error message
			$this->EventCancelled = TRUE; // Set event cancelled
			$this->CurrentAction = "add"; // Stay in add mode
			return;
		}
		$this->SendEmail = TRUE; // Send email on add success
		if ($this->AddRow($this->OldRecordset)) { // Add record
			if ($this->getSuccessMessage() == "")
				$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up add success message
			$this->ClearInlineMode(); // Clear inline add mode
		} else { // Add failed
			$this->EventCancelled = TRUE; // Set event cancelled
			$this->CurrentAction = "add"; // Stay in add mode
		}
	}

	// Perform update to grid
	function GridUpdate() {
		global $Language, $objForm, $gsFormError;
		$bGridUpdate = TRUE;

		// Get old recordset
		$this->CurrentFilter = $this->BuildKeyFilter();
		if ($this->CurrentFilter == "")
			$this->CurrentFilter = "0=1";
		$sSql = $this->SQL();
		$conn = &$this->Connection();
		if ($rs = $conn->Execute($sSql)) {
			$rsold = $rs->GetRows();
			$rs->Close();
		}

		// Call Grid Updating event
		if (!$this->Grid_Updating($rsold)) {
			if ($this->getFailureMessage() == "")
				$this->setFailureMessage($Language->Phrase("GridEditCancelled")); // Set grid edit cancelled message
			return FALSE;
		}

		// Begin transaction
		$conn->BeginTrans();
		if ($this->AuditTrailOnEdit) $this->WriteAuditTrailDummy($Language->Phrase("BatchUpdateBegin")); // Batch update begin
		$sKey = "";

		// Update row index and get row key
		$objForm->Index = -1;
		$rowcnt = strval($objForm->GetValue($this->FormKeyCountName));
		if ($rowcnt == "" || !is_numeric($rowcnt))
			$rowcnt = 0;

		// Update all rows based on key
		for ($rowindex = 1; $rowindex <= $rowcnt; $rowindex++) {
			$objForm->Index = $rowindex;
			$rowkey = strval($objForm->GetValue($this->FormKeyName));
			$rowaction = strval($objForm->GetValue($this->FormActionName));

			// Load all values and keys
			if ($rowaction <> "insertdelete") { // Skip insert then deleted rows
				$this->LoadFormValues(); // Get form values
				if ($rowaction == "" || $rowaction == "edit" || $rowaction == "delete") {
					$bGridUpdate = $this->SetupKeyValues($rowkey); // Set up key values
				} else {
					$bGridUpdate = TRUE;
				}

				// Skip empty row
				if ($rowaction == "insert" && $this->EmptyRow()) {

					// No action required
				// Validate form and insert/update/delete record

				} elseif ($bGridUpdate) {
					if ($rowaction == "delete") {
						$this->CurrentFilter = $this->KeyFilter();
						$bGridUpdate = $this->DeleteRows(); // Delete this row
					} else if (!$this->ValidateForm()) {
						$bGridUpdate = FALSE; // Form error, reset action
						$this->setFailureMessage($gsFormError);
					} else {
						if ($rowaction == "insert") {
							$bGridUpdate = $this->AddRow(); // Insert this row
						} else {
							if ($rowkey <> "") {
								$this->SendEmail = FALSE; // Do not send email on update success
								$bGridUpdate = $this->EditRow(); // Update this row
							}
						} // End update
					}
				}
				if ($bGridUpdate) {
					if ($sKey <> "") $sKey .= ", ";
					$sKey .= $rowkey;
				} else {
					break;
				}
			}
		}
		if ($bGridUpdate) {
			$conn->CommitTrans(); // Commit transaction

			// Get new recordset
			if ($rs = $conn->Execute($sSql)) {
				$rsnew = $rs->GetRows();
				$rs->Close();
			}

			// Call Grid_Updated event
			$this->Grid_Updated($rsold, $rsnew);
			if ($this->AuditTrailOnEdit) $this->WriteAuditTrailDummy($Language->Phrase("BatchUpdateSuccess")); // Batch update success
			if ($this->getSuccessMessage() == "")
				$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Set up update success message
			$this->ClearInlineMode(); // Clear inline edit mode
		} else {
			$conn->RollbackTrans(); // Rollback transaction
			if ($this->AuditTrailOnEdit) $this->WriteAuditTrailDummy($Language->Phrase("BatchUpdateRollback")); // Batch update rollback
			if ($this->getFailureMessage() == "")
				$this->setFailureMessage($Language->Phrase("UpdateFailed")); // Set update failed message
		}
		return $bGridUpdate;
	}

	// Build filter for all keys
	function BuildKeyFilter() {
		global $objForm;
		$sWrkFilter = "";

		// Update row index and get row key
		$rowindex = 1;
		$objForm->Index = $rowindex;
		$sThisKey = strval($objForm->GetValue($this->FormKeyName));
		while ($sThisKey <> "") {
			if ($this->SetupKeyValues($sThisKey)) {
				$sFilter = $this->KeyFilter();
				if ($sWrkFilter <> "") $sWrkFilter .= " OR ";
				$sWrkFilter .= $sFilter;
			} else {
				$sWrkFilter = "0=1";
				break;
			}

			// Update row index and get row key
			$rowindex++; // Next row
			$objForm->Index = $rowindex;
			$sThisKey = strval($objForm->GetValue($this->FormKeyName));
		}
		return $sWrkFilter;
	}

	// Set up key values
	function SetupKeyValues($key) {
		$arrKeyFlds = explode($GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"], $key);
		if (count($arrKeyFlds) >= 1) {
			$this->DaftarmID->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->DaftarmID->FormValue))
				return FALSE;
		}
		return TRUE;
	}

	// Perform Grid Add
	function GridInsert() {
		global $Language, $objForm, $gsFormError;
		$rowindex = 1;
		$bGridInsert = FALSE;
		$conn = &$this->Connection();

		// Call Grid Inserting event
		if (!$this->Grid_Inserting()) {
			if ($this->getFailureMessage() == "") {
				$this->setFailureMessage($Language->Phrase("GridAddCancelled")); // Set grid add cancelled message
			}
			return FALSE;
		}

		// Begin transaction
		$conn->BeginTrans();

		// Init key filter
		$sWrkFilter = "";
		$addcnt = 0;
		if ($this->AuditTrailOnAdd) $this->WriteAuditTrailDummy($Language->Phrase("BatchInsertBegin")); // Batch insert begin
		$sKey = "";

		// Get row count
		$objForm->Index = -1;
		$rowcnt = strval($objForm->GetValue($this->FormKeyCountName));
		if ($rowcnt == "" || !is_numeric($rowcnt))
			$rowcnt = 0;

		// Insert all rows
		for ($rowindex = 1; $rowindex <= $rowcnt; $rowindex++) {

			// Load current row values
			$objForm->Index = $rowindex;
			$rowaction = strval($objForm->GetValue($this->FormActionName));
			if ($rowaction <> "" && $rowaction <> "insert")
				continue; // Skip
			$this->LoadFormValues(); // Get form values
			if (!$this->EmptyRow()) {
				$addcnt++;
				$this->SendEmail = FALSE; // Do not send email on insert success

				// Validate form
				if (!$this->ValidateForm()) {
					$bGridInsert = FALSE; // Form error, reset action
					$this->setFailureMessage($gsFormError);
				} else {
					$bGridInsert = $this->AddRow($this->OldRecordset); // Insert this row
				}
				if ($bGridInsert) {
					if ($sKey <> "") $sKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
					$sKey .= $this->DaftarmID->CurrentValue;

					// Add filter for this record
					$sFilter = $this->KeyFilter();
					if ($sWrkFilter <> "") $sWrkFilter .= " OR ";
					$sWrkFilter .= $sFilter;
				} else {
					break;
				}
			}
		}
		if ($addcnt == 0) { // No record inserted
			$this->setFailureMessage($Language->Phrase("NoAddRecord"));
			$bGridInsert = FALSE;
		}
		if ($bGridInsert) {
			$conn->CommitTrans(); // Commit transaction

			// Get new recordset
			$this->CurrentFilter = $sWrkFilter;
			$sSql = $this->SQL();
			if ($rs = $conn->Execute($sSql)) {
				$rsnew = $rs->GetRows();
				$rs->Close();
			}

			// Call Grid_Inserted event
			$this->Grid_Inserted($rsnew);
			if ($this->AuditTrailOnAdd) $this->WriteAuditTrailDummy($Language->Phrase("BatchInsertSuccess")); // Batch insert success
			if ($this->getSuccessMessage() == "")
				$this->setSuccessMessage($Language->Phrase("InsertSuccess")); // Set up insert success message
			$this->ClearInlineMode(); // Clear grid add mode
		} else {
			$conn->RollbackTrans(); // Rollback transaction
			if ($this->AuditTrailOnAdd) $this->WriteAuditTrailDummy($Language->Phrase("BatchInsertRollback")); // Batch insert rollback
			if ($this->getFailureMessage() == "") {
				$this->setFailureMessage($Language->Phrase("InsertFailed")); // Set insert failed message
			}
		}
		return $bGridInsert;
	}

	// Check if empty row
	function EmptyRow() {
		global $objForm;
		if ($objForm->HasValue("x__UserID") && $objForm->HasValue("o__UserID") && $this->_UserID->CurrentValue <> $this->_UserID->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_TglJam") && $objForm->HasValue("o_TglJam") && $this->TglJam->CurrentValue <> $this->TglJam->OldValue)
			return FALSE;
		if (!ew_Empty($this->BuktiBayar->Upload->Value))
			return FALSE;
		if ($objForm->HasValue("x_JumlahBayar") && $objForm->HasValue("o_JumlahBayar") && $this->JumlahBayar->CurrentValue <> $this->JumlahBayar->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_Acc") && $objForm->HasValue("o_Acc") && $this->Acc->CurrentValue <> $this->Acc->OldValue)
			return FALSE;
		return TRUE;
	}

	// Validate grid form
	function ValidateGridForm() {
		global $objForm;

		// Get row count
		$objForm->Index = -1;
		$rowcnt = strval($objForm->GetValue($this->FormKeyCountName));
		if ($rowcnt == "" || !is_numeric($rowcnt))
			$rowcnt = 0;

		// Validate all records
		for ($rowindex = 1; $rowindex <= $rowcnt; $rowindex++) {

			// Load current row values
			$objForm->Index = $rowindex;
			$rowaction = strval($objForm->GetValue($this->FormActionName));
			if ($rowaction <> "delete" && $rowaction <> "insertdelete") {
				$this->LoadFormValues(); // Get form values
				if ($rowaction == "insert" && $this->EmptyRow()) {

					// Ignore
				} else if (!$this->ValidateForm()) {
					return FALSE;
				}
			}
		}
		return TRUE;
	}

	// Get all form values of the grid
	function GetGridFormValues() {
		global $objForm;

		// Get row count
		$objForm->Index = -1;
		$rowcnt = strval($objForm->GetValue($this->FormKeyCountName));
		if ($rowcnt == "" || !is_numeric($rowcnt))
			$rowcnt = 0;
		$rows = array();

		// Loop through all records
		for ($rowindex = 1; $rowindex <= $rowcnt; $rowindex++) {

			// Load current row values
			$objForm->Index = $rowindex;
			$rowaction = strval($objForm->GetValue($this->FormActionName));
			if ($rowaction <> "delete" && $rowaction <> "insertdelete") {
				$this->LoadFormValues(); // Get form values
				if ($rowaction == "insert" && $this->EmptyRow()) {

					// Ignore
				} else {
					$rows[] = $this->GetFieldValues("FormValue"); // Return row as array
				}
			}
		}
		return $rows; // Return as array of array
	}

	// Restore form values for current row
	function RestoreCurrentRowFormValues($idx) {
		global $objForm;

		// Get row based on current index
		$objForm->Index = $idx;
		$this->LoadFormValues(); // Load form values
	}

	// Get list of filters
	function GetFilterList() {
		global $UserProfile;

		// Load server side filters
		if (EW_SEARCH_FILTER_OPTION == "Server") {
			$sSavedFilterList = $UserProfile->GetSearchFilters(CurrentUserName(), "ft_daftarmlistsrch");
		} else {
			$sSavedFilterList = "";
		}

		// Initialize
		$sFilterList = "";
		$sFilterList = ew_Concat($sFilterList, $this->DaftarmID->AdvancedSearch->ToJSON(), ","); // Field DaftarmID
		$sFilterList = ew_Concat($sFilterList, $this->_UserID->AdvancedSearch->ToJSON(), ","); // Field UserID
		$sFilterList = ew_Concat($sFilterList, $this->TglJam->AdvancedSearch->ToJSON(), ","); // Field TglJam
		$sFilterList = ew_Concat($sFilterList, $this->BuktiBayar->AdvancedSearch->ToJSON(), ","); // Field BuktiBayar
		$sFilterList = ew_Concat($sFilterList, $this->JumlahBayar->AdvancedSearch->ToJSON(), ","); // Field JumlahBayar
		$sFilterList = ew_Concat($sFilterList, $this->Acc->AdvancedSearch->ToJSON(), ","); // Field Acc
		if ($this->BasicSearch->Keyword <> "") {
			$sWrk = "\"" . EW_TABLE_BASIC_SEARCH . "\":\"" . ew_JsEncode2($this->BasicSearch->Keyword) . "\",\"" . EW_TABLE_BASIC_SEARCH_TYPE . "\":\"" . ew_JsEncode2($this->BasicSearch->Type) . "\"";
			$sFilterList = ew_Concat($sFilterList, $sWrk, ",");
		}
		$sFilterList = preg_replace('/,$/', "", $sFilterList);

		// Return filter list in json
		if ($sFilterList <> "")
			$sFilterList = "\"data\":{" . $sFilterList . "}";
		if ($sSavedFilterList <> "") {
			if ($sFilterList <> "")
				$sFilterList .= ",";
			$sFilterList .= "\"filters\":" . $sSavedFilterList;
		}
		return ($sFilterList <> "") ? "{" . $sFilterList . "}" : "null";
	}

	// Process filter list
	function ProcessFilterList() {
		global $UserProfile;
		if (@$_POST["ajax"] == "savefilters") { // Save filter request (Ajax)
			$filters = ew_StripSlashes(@$_POST["filters"]);
			$UserProfile->SetSearchFilters(CurrentUserName(), "ft_daftarmlistsrch", $filters);

			// Clean output buffer
			if (!EW_DEBUG_ENABLED && ob_get_length())
				ob_end_clean();
			echo ew_ArrayToJson(array(array("success" => TRUE))); // Success
			$this->Page_Terminate();
			exit();
		} elseif (@$_POST["cmd"] == "resetfilter") {
			$this->RestoreFilterList();
		}
	}

	// Restore list of filters
	function RestoreFilterList() {

		// Return if not reset filter
		if (@$_POST["cmd"] <> "resetfilter")
			return FALSE;
		$filter = json_decode(ew_StripSlashes(@$_POST["filter"]), TRUE);
		$this->Command = "search";

		// Field DaftarmID
		$this->DaftarmID->AdvancedSearch->SearchValue = @$filter["x_DaftarmID"];
		$this->DaftarmID->AdvancedSearch->SearchOperator = @$filter["z_DaftarmID"];
		$this->DaftarmID->AdvancedSearch->SearchCondition = @$filter["v_DaftarmID"];
		$this->DaftarmID->AdvancedSearch->SearchValue2 = @$filter["y_DaftarmID"];
		$this->DaftarmID->AdvancedSearch->SearchOperator2 = @$filter["w_DaftarmID"];
		$this->DaftarmID->AdvancedSearch->Save();

		// Field UserID
		$this->_UserID->AdvancedSearch->SearchValue = @$filter["x__UserID"];
		$this->_UserID->AdvancedSearch->SearchOperator = @$filter["z__UserID"];
		$this->_UserID->AdvancedSearch->SearchCondition = @$filter["v__UserID"];
		$this->_UserID->AdvancedSearch->SearchValue2 = @$filter["y__UserID"];
		$this->_UserID->AdvancedSearch->SearchOperator2 = @$filter["w__UserID"];
		$this->_UserID->AdvancedSearch->Save();

		// Field TglJam
		$this->TglJam->AdvancedSearch->SearchValue = @$filter["x_TglJam"];
		$this->TglJam->AdvancedSearch->SearchOperator = @$filter["z_TglJam"];
		$this->TglJam->AdvancedSearch->SearchCondition = @$filter["v_TglJam"];
		$this->TglJam->AdvancedSearch->SearchValue2 = @$filter["y_TglJam"];
		$this->TglJam->AdvancedSearch->SearchOperator2 = @$filter["w_TglJam"];
		$this->TglJam->AdvancedSearch->Save();

		// Field BuktiBayar
		$this->BuktiBayar->AdvancedSearch->SearchValue = @$filter["x_BuktiBayar"];
		$this->BuktiBayar->AdvancedSearch->SearchOperator = @$filter["z_BuktiBayar"];
		$this->BuktiBayar->AdvancedSearch->SearchCondition = @$filter["v_BuktiBayar"];
		$this->BuktiBayar->AdvancedSearch->SearchValue2 = @$filter["y_BuktiBayar"];
		$this->BuktiBayar->AdvancedSearch->SearchOperator2 = @$filter["w_BuktiBayar"];
		$this->BuktiBayar->AdvancedSearch->Save();

		// Field JumlahBayar
		$this->JumlahBayar->AdvancedSearch->SearchValue = @$filter["x_JumlahBayar"];
		$this->JumlahBayar->AdvancedSearch->SearchOperator = @$filter["z_JumlahBayar"];
		$this->JumlahBayar->AdvancedSearch->SearchCondition = @$filter["v_JumlahBayar"];
		$this->JumlahBayar->AdvancedSearch->SearchValue2 = @$filter["y_JumlahBayar"];
		$this->JumlahBayar->AdvancedSearch->SearchOperator2 = @$filter["w_JumlahBayar"];
		$this->JumlahBayar->AdvancedSearch->Save();

		// Field Acc
		$this->Acc->AdvancedSearch->SearchValue = @$filter["x_Acc"];
		$this->Acc->AdvancedSearch->SearchOperator = @$filter["z_Acc"];
		$this->Acc->AdvancedSearch->SearchCondition = @$filter["v_Acc"];
		$this->Acc->AdvancedSearch->SearchValue2 = @$filter["y_Acc"];
		$this->Acc->AdvancedSearch->SearchOperator2 = @$filter["w_Acc"];
		$this->Acc->AdvancedSearch->Save();
		$this->BasicSearch->setKeyword(@$filter[EW_TABLE_BASIC_SEARCH]);
		$this->BasicSearch->setType(@$filter[EW_TABLE_BASIC_SEARCH_TYPE]);
	}

	// Advanced search WHERE clause based on QueryString
	function AdvancedSearchWhere($Default = FALSE) {
		global $Security;
		$sWhere = "";
		if (!$Security->CanSearch()) return "";
		$this->BuildSearchSql($sWhere, $this->DaftarmID, $Default, FALSE); // DaftarmID
		$this->BuildSearchSql($sWhere, $this->_UserID, $Default, FALSE); // UserID
		$this->BuildSearchSql($sWhere, $this->TglJam, $Default, FALSE); // TglJam
		$this->BuildSearchSql($sWhere, $this->BuktiBayar, $Default, FALSE); // BuktiBayar
		$this->BuildSearchSql($sWhere, $this->JumlahBayar, $Default, FALSE); // JumlahBayar
		$this->BuildSearchSql($sWhere, $this->Acc, $Default, FALSE); // Acc

		// Set up search parm
		if (!$Default && $sWhere <> "") {
			$this->Command = "search";
		}
		if (!$Default && $this->Command == "search") {
			$this->DaftarmID->AdvancedSearch->Save(); // DaftarmID
			$this->_UserID->AdvancedSearch->Save(); // UserID
			$this->TglJam->AdvancedSearch->Save(); // TglJam
			$this->BuktiBayar->AdvancedSearch->Save(); // BuktiBayar
			$this->JumlahBayar->AdvancedSearch->Save(); // JumlahBayar
			$this->Acc->AdvancedSearch->Save(); // Acc
		}
		return $sWhere;
	}

	// Build search SQL
	function BuildSearchSql(&$Where, &$Fld, $Default, $MultiValue) {
		$FldParm = substr($Fld->FldVar, 2);
		$FldVal = ($Default) ? $Fld->AdvancedSearch->SearchValueDefault : $Fld->AdvancedSearch->SearchValue; // @$_GET["x_$FldParm"]
		$FldOpr = ($Default) ? $Fld->AdvancedSearch->SearchOperatorDefault : $Fld->AdvancedSearch->SearchOperator; // @$_GET["z_$FldParm"]
		$FldCond = ($Default) ? $Fld->AdvancedSearch->SearchConditionDefault : $Fld->AdvancedSearch->SearchCondition; // @$_GET["v_$FldParm"]
		$FldVal2 = ($Default) ? $Fld->AdvancedSearch->SearchValue2Default : $Fld->AdvancedSearch->SearchValue2; // @$_GET["y_$FldParm"]
		$FldOpr2 = ($Default) ? $Fld->AdvancedSearch->SearchOperator2Default : $Fld->AdvancedSearch->SearchOperator2; // @$_GET["w_$FldParm"]
		$sWrk = "";

		//$FldVal = ew_StripSlashes($FldVal);
		if (is_array($FldVal)) $FldVal = implode(",", $FldVal);

		//$FldVal2 = ew_StripSlashes($FldVal2);
		if (is_array($FldVal2)) $FldVal2 = implode(",", $FldVal2);
		$FldOpr = strtoupper(trim($FldOpr));
		if ($FldOpr == "") $FldOpr = "=";
		$FldOpr2 = strtoupper(trim($FldOpr2));
		if ($FldOpr2 == "") $FldOpr2 = "=";
		if (EW_SEARCH_MULTI_VALUE_OPTION == 1)
			$MultiValue = FALSE;
		if ($MultiValue) {
			$sWrk1 = ($FldVal <> "") ? ew_GetMultiSearchSql($Fld, $FldOpr, $FldVal, $this->DBID) : ""; // Field value 1
			$sWrk2 = ($FldVal2 <> "") ? ew_GetMultiSearchSql($Fld, $FldOpr2, $FldVal2, $this->DBID) : ""; // Field value 2
			$sWrk = $sWrk1; // Build final SQL
			if ($sWrk2 <> "")
				$sWrk = ($sWrk <> "") ? "($sWrk) $FldCond ($sWrk2)" : $sWrk2;
		} else {
			$FldVal = $this->ConvertSearchValue($Fld, $FldVal);
			$FldVal2 = $this->ConvertSearchValue($Fld, $FldVal2);
			$sWrk = ew_GetSearchSql($Fld, $FldVal, $FldOpr, $FldCond, $FldVal2, $FldOpr2, $this->DBID);
		}
		ew_AddFilter($Where, $sWrk);
	}

	// Convert search value
	function ConvertSearchValue(&$Fld, $FldVal) {
		if ($FldVal == EW_NULL_VALUE || $FldVal == EW_NOT_NULL_VALUE)
			return $FldVal;
		$Value = $FldVal;
		if ($Fld->FldDataType == EW_DATATYPE_BOOLEAN) {
			if ($FldVal <> "") $Value = ($FldVal == "1" || strtolower(strval($FldVal)) == "y" || strtolower(strval($FldVal)) == "t") ? $Fld->TrueValue : $Fld->FalseValue;
		} elseif ($Fld->FldDataType == EW_DATATYPE_DATE || $Fld->FldDataType == EW_DATATYPE_TIME) {
			if ($FldVal <> "") $Value = ew_UnFormatDateTime($FldVal, $Fld->FldDateTimeFormat);
		}
		return $Value;
	}

	// Return basic search SQL
	function BasicSearchSQL($arKeywords, $type) {
		$sWhere = "";
		$this->BuildBasicSearchSQL($sWhere, $this->BuktiBayar, $arKeywords, $type);
		return $sWhere;
	}

	// Build basic search SQL
	function BuildBasicSearchSQL(&$Where, &$Fld, $arKeywords, $type) {
		global $EW_BASIC_SEARCH_IGNORE_PATTERN;
		$sDefCond = ($type == "OR") ? "OR" : "AND";
		$arSQL = array(); // Array for SQL parts
		$arCond = array(); // Array for search conditions
		$cnt = count($arKeywords);
		$j = 0; // Number of SQL parts
		for ($i = 0; $i < $cnt; $i++) {
			$Keyword = $arKeywords[$i];
			$Keyword = trim($Keyword);
			if ($EW_BASIC_SEARCH_IGNORE_PATTERN <> "") {
				$Keyword = preg_replace($EW_BASIC_SEARCH_IGNORE_PATTERN, "\\", $Keyword);
				$ar = explode("\\", $Keyword);
			} else {
				$ar = array($Keyword);
			}
			foreach ($ar as $Keyword) {
				if ($Keyword <> "") {
					$sWrk = "";
					if ($Keyword == "OR" && $type == "") {
						if ($j > 0)
							$arCond[$j-1] = "OR";
					} elseif ($Keyword == EW_NULL_VALUE) {
						$sWrk = $Fld->FldExpression . " IS NULL";
					} elseif ($Keyword == EW_NOT_NULL_VALUE) {
						$sWrk = $Fld->FldExpression . " IS NOT NULL";
					} elseif ($Fld->FldIsVirtual) {
						$sWrk = $Fld->FldVirtualExpression . ew_Like(ew_QuotedValue("%" . $Keyword . "%", EW_DATATYPE_STRING, $this->DBID), $this->DBID);
					} elseif ($Fld->FldDataType != EW_DATATYPE_NUMBER || is_numeric($Keyword)) {
						$sWrk = $Fld->FldBasicSearchExpression . ew_Like(ew_QuotedValue("%" . $Keyword . "%", EW_DATATYPE_STRING, $this->DBID), $this->DBID);
					}
					if ($sWrk <> "") {
						$arSQL[$j] = $sWrk;
						$arCond[$j] = $sDefCond;
						$j += 1;
					}
				}
			}
		}
		$cnt = count($arSQL);
		$bQuoted = FALSE;
		$sSql = "";
		if ($cnt > 0) {
			for ($i = 0; $i < $cnt-1; $i++) {
				if ($arCond[$i] == "OR") {
					if (!$bQuoted) $sSql .= "(";
					$bQuoted = TRUE;
				}
				$sSql .= $arSQL[$i];
				if ($bQuoted && $arCond[$i] <> "OR") {
					$sSql .= ")";
					$bQuoted = FALSE;
				}
				$sSql .= " " . $arCond[$i] . " ";
			}
			$sSql .= $arSQL[$cnt-1];
			if ($bQuoted)
				$sSql .= ")";
		}
		if ($sSql <> "") {
			if ($Where <> "") $Where .= " OR ";
			$Where .=  "(" . $sSql . ")";
		}
	}

	// Return basic search WHERE clause based on search keyword and type
	function BasicSearchWhere($Default = FALSE) {
		global $Security;
		$sSearchStr = "";
		if (!$Security->CanSearch()) return "";
		$sSearchKeyword = ($Default) ? $this->BasicSearch->KeywordDefault : $this->BasicSearch->Keyword;
		$sSearchType = ($Default) ? $this->BasicSearch->TypeDefault : $this->BasicSearch->Type;
		if ($sSearchKeyword <> "") {
			$sSearch = trim($sSearchKeyword);
			if ($sSearchType <> "=") {
				$ar = array();

				// Match quoted keywords (i.e.: "...")
				if (preg_match_all('/"([^"]*)"/i', $sSearch, $matches, PREG_SET_ORDER)) {
					foreach ($matches as $match) {
						$p = strpos($sSearch, $match[0]);
						$str = substr($sSearch, 0, $p);
						$sSearch = substr($sSearch, $p + strlen($match[0]));
						if (strlen(trim($str)) > 0)
							$ar = array_merge($ar, explode(" ", trim($str)));
						$ar[] = $match[1]; // Save quoted keyword
					}
				}

				// Match individual keywords
				if (strlen(trim($sSearch)) > 0)
					$ar = array_merge($ar, explode(" ", trim($sSearch)));

				// Search keyword in any fields
				if (($sSearchType == "OR" || $sSearchType == "AND") && $this->BasicSearch->BasicSearchAnyFields) {
					foreach ($ar as $sKeyword) {
						if ($sKeyword <> "") {
							if ($sSearchStr <> "") $sSearchStr .= " " . $sSearchType . " ";
							$sSearchStr .= "(" . $this->BasicSearchSQL(array($sKeyword), $sSearchType) . ")";
						}
					}
				} else {
					$sSearchStr = $this->BasicSearchSQL($ar, $sSearchType);
				}
			} else {
				$sSearchStr = $this->BasicSearchSQL(array($sSearch), $sSearchType);
			}
			if (!$Default) $this->Command = "search";
		}
		if (!$Default && $this->Command == "search") {
			$this->BasicSearch->setKeyword($sSearchKeyword);
			$this->BasicSearch->setType($sSearchType);
		}
		return $sSearchStr;
	}

	// Check if search parm exists
	function CheckSearchParms() {

		// Check basic search
		if ($this->BasicSearch->IssetSession())
			return TRUE;
		if ($this->DaftarmID->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->_UserID->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->TglJam->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->BuktiBayar->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->JumlahBayar->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->Acc->AdvancedSearch->IssetSession())
			return TRUE;
		return FALSE;
	}

	// Clear all search parameters
	function ResetSearchParms() {

		// Clear search WHERE clause
		$this->SearchWhere = "";
		$this->setSearchWhere($this->SearchWhere);

		// Clear basic search parameters
		$this->ResetBasicSearchParms();

		// Clear advanced search parameters
		$this->ResetAdvancedSearchParms();
	}

	// Load advanced search default values
	function LoadAdvancedSearchDefault() {
		return FALSE;
	}

	// Clear all basic search parameters
	function ResetBasicSearchParms() {
		$this->BasicSearch->UnsetSession();
	}

	// Clear all advanced search parameters
	function ResetAdvancedSearchParms() {
		$this->DaftarmID->AdvancedSearch->UnsetSession();
		$this->_UserID->AdvancedSearch->UnsetSession();
		$this->TglJam->AdvancedSearch->UnsetSession();
		$this->BuktiBayar->AdvancedSearch->UnsetSession();
		$this->JumlahBayar->AdvancedSearch->UnsetSession();
		$this->Acc->AdvancedSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore basic search values
		$this->BasicSearch->Load();

		// Restore advanced search values
		$this->DaftarmID->AdvancedSearch->Load();
		$this->_UserID->AdvancedSearch->Load();
		$this->TglJam->AdvancedSearch->Load();
		$this->BuktiBayar->AdvancedSearch->Load();
		$this->JumlahBayar->AdvancedSearch->Load();
		$this->Acc->AdvancedSearch->Load();
	}

	// Set up sort parameters
	function SetUpSortOrder() {

		// Check for Ctrl pressed
		$bCtrl = (@$_GET["ctrl"] <> "");

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->_UserID, $bCtrl); // UserID
			$this->UpdateSort($this->TglJam, $bCtrl); // TglJam
			$this->UpdateSort($this->BuktiBayar, $bCtrl); // BuktiBayar
			$this->UpdateSort($this->JumlahBayar, $bCtrl); // JumlahBayar
			$this->UpdateSort($this->Acc, $bCtrl); // Acc
			$this->setStartRecordNumber(1); // Reset start position
		}
	}

	// Load sort order parameters
	function LoadSortOrder() {
		$sOrderBy = $this->getSessionOrderBy(); // Get ORDER BY from Session
		if ($sOrderBy == "") {
			if ($this->getSqlOrderBy() <> "") {
				$sOrderBy = $this->getSqlOrderBy();
				$this->setSessionOrderBy($sOrderBy);
			}
		}
	}

	// Reset command
	// - cmd=reset (Reset search parameters)
	// - cmd=resetall (Reset search and master/detail parameters)
	// - cmd=resetsort (Reset sort parameters)
	function ResetCmd() {

		// Check if reset command
		if (substr($this->Command,0,5) == "reset") {

			// Reset search criteria
			if ($this->Command == "reset" || $this->Command == "resetall")
				$this->ResetSearchParms();

			// Reset sorting order
			if ($this->Command == "resetsort") {
				$sOrderBy = "";
				$this->setSessionOrderBy($sOrderBy);
				$this->_UserID->setSort("");
				$this->TglJam->setSort("");
				$this->BuktiBayar->setSort("");
				$this->JumlahBayar->setSort("");
				$this->Acc->setSort("");
			}

			// Reset start position
			$this->StartRec = 1;
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Set up list options
	function SetupListOptions() {
		global $Security, $Language;

		// "griddelete"
		if ($this->AllowAddDeleteRow) {
			$item = &$this->ListOptions->Add("griddelete");
			$item->CssStyle = "white-space: nowrap;";
			$item->OnLeft = TRUE;
			$item->Visible = FALSE; // Default hidden
		}

		// Add group option item
		$item = &$this->ListOptions->Add($this->ListOptions->GroupOptionName);
		$item->Body = "";
		$item->OnLeft = TRUE;
		$item->Visible = FALSE;

		// "view"
		$item = &$this->ListOptions->Add("view");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->CanView();
		$item->OnLeft = TRUE;

		// "edit"
		$item = &$this->ListOptions->Add("edit");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->CanEdit();
		$item->OnLeft = TRUE;

		// "copy"
		$item = &$this->ListOptions->Add("copy");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->CanAdd();
		$item->OnLeft = TRUE;

		// "detail_t_daftard"
		$item = &$this->ListOptions->Add("detail_t_daftard");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->AllowList(CurrentProjectID() . 't_daftard') && !$this->ShowMultipleDetails;
		$item->OnLeft = TRUE;
		$item->ShowInButtonGroup = FALSE;
		if (!isset($GLOBALS["t_daftard_grid"])) $GLOBALS["t_daftard_grid"] = new ct_daftard_grid;

		// Multiple details
		if ($this->ShowMultipleDetails) {
			$item = &$this->ListOptions->Add("details");
			$item->CssStyle = "white-space: nowrap;";
			$item->Visible = $this->ShowMultipleDetails;
			$item->OnLeft = TRUE;
			$item->ShowInButtonGroup = FALSE;
		}

		// Set up detail pages
		$pages = new cSubPages();
		$pages->Add("t_daftard");
		$this->DetailPages = $pages;

		// List actions
		$item = &$this->ListOptions->Add("listactions");
		$item->CssStyle = "white-space: nowrap;";
		$item->OnLeft = TRUE;
		$item->Visible = FALSE;
		$item->ShowInButtonGroup = FALSE;
		$item->ShowInDropDown = FALSE;

		// "checkbox"
		$item = &$this->ListOptions->Add("checkbox");
		$item->Visible = $Security->CanDelete();
		$item->OnLeft = TRUE;
		$item->Header = "<input type=\"checkbox\" name=\"key\" id=\"key\" onclick=\"ew_SelectAllKey(this);\">";
		$item->MoveTo(0);
		$item->ShowInDropDown = FALSE;
		$item->ShowInButtonGroup = FALSE;

		// "sequence"
		$item = &$this->ListOptions->Add("sequence");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = TRUE;
		$item->OnLeft = TRUE; // Always on left
		$item->ShowInDropDown = FALSE;
		$item->ShowInButtonGroup = FALSE;

		// Drop down button for ListOptions
		$this->ListOptions->UseImageAndText = TRUE;
		$this->ListOptions->UseDropDownButton = TRUE;
		$this->ListOptions->DropDownButtonPhrase = $Language->Phrase("ButtonListOptions");
		$this->ListOptions->UseButtonGroup = FALSE;
		if ($this->ListOptions->UseButtonGroup && ew_IsMobile())
			$this->ListOptions->UseDropDownButton = TRUE;
		$this->ListOptions->ButtonClass = "btn-sm"; // Class for button group

		// Call ListOptions_Load event
		$this->ListOptions_Load();
		$this->SetupListOptionsExt();
		$item = &$this->ListOptions->GetItem($this->ListOptions->GroupOptionName);
		$item->Visible = $this->ListOptions->GroupOptionVisible();
	}

	// Render list options
	function RenderListOptions() {
		global $Security, $Language, $objForm;
		$this->ListOptions->LoadDefault();

		// Set up row action and key
		if (is_numeric($this->RowIndex) && $this->CurrentMode <> "view") {
			$objForm->Index = $this->RowIndex;
			$ActionName = str_replace("k_", "k" . $this->RowIndex . "_", $this->FormActionName);
			$OldKeyName = str_replace("k_", "k" . $this->RowIndex . "_", $this->FormOldKeyName);
			$KeyName = str_replace("k_", "k" . $this->RowIndex . "_", $this->FormKeyName);
			$BlankRowName = str_replace("k_", "k" . $this->RowIndex . "_", $this->FormBlankRowName);
			if ($this->RowAction <> "")
				$this->MultiSelectKey .= "<input type=\"hidden\" name=\"" . $ActionName . "\" id=\"" . $ActionName . "\" value=\"" . $this->RowAction . "\">";
			if ($this->RowAction == "delete") {
				$rowkey = $objForm->GetValue($this->FormKeyName);
				$this->SetupKeyValues($rowkey);
			}
			if ($this->RowAction == "insert" && $this->CurrentAction == "F" && $this->EmptyRow())
				$this->MultiSelectKey .= "<input type=\"hidden\" name=\"" . $BlankRowName . "\" id=\"" . $BlankRowName . "\" value=\"1\">";
		}

		// "delete"
		if ($this->AllowAddDeleteRow) {
			if ($this->CurrentAction == "gridadd" || $this->CurrentAction == "gridedit") {
				$option = &$this->ListOptions;
				$option->UseButtonGroup = TRUE; // Use button group for grid delete button
				$option->UseImageAndText = TRUE; // Use image and text for grid delete button
				$oListOpt = &$option->Items["griddelete"];
				if (!$Security->CanDelete() && is_numeric($this->RowIndex) && ($this->RowAction == "" || $this->RowAction == "edit")) { // Do not allow delete existing record
					$oListOpt->Body = "&nbsp;";
				} else {
					$oListOpt->Body = "<a class=\"ewGridLink ewGridDelete\" title=\"" . ew_HtmlTitle($Language->Phrase("DeleteLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("DeleteLink")) . "\" onclick=\"return ew_DeleteGridRow(this, " . $this->RowIndex . ");\">" . $Language->Phrase("DeleteLink") . "</a>";
				}
			}
		}

		// "sequence"
		$oListOpt = &$this->ListOptions->Items["sequence"];
		$oListOpt->Body = ew_FormatSeqNo($this->RecCnt);

		// "copy"
		$oListOpt = &$this->ListOptions->Items["copy"];
		if (($this->CurrentAction == "add" || $this->CurrentAction == "copy") && $this->RowType == EW_ROWTYPE_ADD) { // Inline Add/Copy
			$this->ListOptions->CustomItem = "copy"; // Show copy column only
			$cancelurl = $this->AddMasterUrl($this->PageUrl() . "a=cancel");
			$oListOpt->Body = "<div" . (($oListOpt->OnLeft) ? " style=\"text-align: right\"" : "") . ">" .
				"<a class=\"ewGridLink ewInlineInsert\" title=\"" . ew_HtmlTitle($Language->Phrase("InsertLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("InsertLink")) . "\" href=\"\" onclick=\"return ewForms(this).Submit('" . $this->PageName() . "');\">" . $Language->Phrase("InsertLink") . "</a>&nbsp;" .
				"<a class=\"ewGridLink ewInlineCancel\" title=\"" . ew_HtmlTitle($Language->Phrase("CancelLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("CancelLink")) . "\" href=\"" . $cancelurl . "\">" . $Language->Phrase("CancelLink") . "</a>" .
				"<input type=\"hidden\" name=\"a_list\" id=\"a_list\" value=\"insert\"></div>";
			return;
		}

		// "edit"
		$oListOpt = &$this->ListOptions->Items["edit"];
		if ($this->CurrentAction == "edit" && $this->RowType == EW_ROWTYPE_EDIT) { // Inline-Edit
			$this->ListOptions->CustomItem = "edit"; // Show edit column only
			$cancelurl = $this->AddMasterUrl($this->PageUrl() . "a=cancel");
				$oListOpt->Body = "<div" . (($oListOpt->OnLeft) ? " style=\"text-align: right\"" : "") . ">" .
					"<a class=\"ewGridLink ewInlineUpdate\" title=\"" . ew_HtmlTitle($Language->Phrase("UpdateLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("UpdateLink")) . "\" href=\"\" onclick=\"return ewForms(this).Submit('" . ew_GetHashUrl($this->PageName(), $this->PageObjName . "_row_" . $this->RowCnt) . "');\">" . $Language->Phrase("UpdateLink") . "</a>&nbsp;" .
					"<a class=\"ewGridLink ewInlineCancel\" title=\"" . ew_HtmlTitle($Language->Phrase("CancelLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("CancelLink")) . "\" href=\"" . $cancelurl . "\">" . $Language->Phrase("CancelLink") . "</a>" .
					"<input type=\"hidden\" name=\"a_list\" id=\"a_list\" value=\"update\"></div>";
			$oListOpt->Body .= "<input type=\"hidden\" name=\"k" . $this->RowIndex . "_key\" id=\"k" . $this->RowIndex . "_key\" value=\"" . ew_HtmlEncode($this->DaftarmID->CurrentValue) . "\">";
			return;
		}

		// "view"
		$oListOpt = &$this->ListOptions->Items["view"];
		$viewcaption = ew_HtmlTitle($Language->Phrase("ViewLink"));
		if ($Security->CanView()) {
			$oListOpt->Body = "<a class=\"ewRowLink ewView\" title=\"" . $viewcaption . "\" data-caption=\"" . $viewcaption . "\" href=\"" . ew_HtmlEncode($this->ViewUrl) . "\">" . $Language->Phrase("ViewLink") . "</a>";
		} else {
			$oListOpt->Body = "";
		}

		// "edit"
		$oListOpt = &$this->ListOptions->Items["edit"];
		$editcaption = ew_HtmlTitle($Language->Phrase("EditLink"));
		if ($Security->CanEdit()) {
			$oListOpt->Body = "<a class=\"ewRowLink ewEdit\" title=\"" . ew_HtmlTitle($Language->Phrase("EditLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("EditLink")) . "\" href=\"" . ew_HtmlEncode($this->EditUrl) . "\">" . $Language->Phrase("EditLink") . "</a>";
			$oListOpt->Body .= "<a class=\"ewRowLink ewInlineEdit\" title=\"" . ew_HtmlTitle($Language->Phrase("InlineEditLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("InlineEditLink")) . "\" href=\"" . ew_HtmlEncode(ew_GetHashUrl($this->InlineEditUrl, $this->PageObjName . "_row_" . $this->RowCnt)) . "\">" . $Language->Phrase("InlineEditLink") . "</a>";
		} else {
			$oListOpt->Body = "";
		}

		// "copy"
		$oListOpt = &$this->ListOptions->Items["copy"];
		$copycaption = ew_HtmlTitle($Language->Phrase("CopyLink"));
		if ($Security->CanAdd()) {
			$oListOpt->Body = "<a class=\"ewRowLink ewCopy\" title=\"" . $copycaption . "\" data-caption=\"" . $copycaption . "\" href=\"" . ew_HtmlEncode($this->CopyUrl) . "\">" . $Language->Phrase("CopyLink") . "</a>";
			$oListOpt->Body .= "<a class=\"ewRowLink ewInlineCopy\" title=\"" . ew_HtmlTitle($Language->Phrase("InlineCopyLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("InlineCopyLink")) . "\" href=\"" . ew_HtmlEncode($this->InlineCopyUrl) . "\">" . $Language->Phrase("InlineCopyLink") . "</a>";
		} else {
			$oListOpt->Body = "";
		}

		// Set up list action buttons
		$oListOpt = &$this->ListOptions->GetItem("listactions");
		if ($oListOpt && $this->Export == "" && $this->CurrentAction == "") {
			$body = "";
			$links = array();
			foreach ($this->ListActions->Items as $listaction) {
				if ($listaction->Select == EW_ACTION_SINGLE && $listaction->Allow) {
					$action = $listaction->Action;
					$caption = $listaction->Caption;
					$icon = ($listaction->Icon <> "") ? "<span class=\"" . ew_HtmlEncode(str_replace(" ewIcon", "", $listaction->Icon)) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\"></span> " : "";
					$links[] = "<li><a class=\"ewAction ewListAction\" data-action=\"" . ew_HtmlEncode($action) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({key:" . $this->KeyToJson() . "}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . $listaction->Caption . "</a></li>";
					if (count($links) == 1) // Single button
						$body = "<a class=\"ewAction ewListAction\" data-action=\"" . ew_HtmlEncode($action) . "\" title=\"" . ew_HtmlTitle($caption) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({key:" . $this->KeyToJson() . "}," . $listaction->ToJson(TRUE) . "));return false;\">" . $Language->Phrase("ListActionButton") . "</a>";
				}
			}
			if (count($links) > 1) { // More than one buttons, use dropdown
				$body = "<button class=\"dropdown-toggle btn btn-default btn-sm ewActions\" title=\"" . ew_HtmlTitle($Language->Phrase("ListActionButton")) . "\" data-toggle=\"dropdown\">" . $Language->Phrase("ListActionButton") . "<b class=\"caret\"></b></button>";
				$content = "";
				foreach ($links as $link)
					$content .= "<li>" . $link . "</li>";
				$body .= "<ul class=\"dropdown-menu" . ($oListOpt->OnLeft ? "" : " dropdown-menu-right") . "\">". $content . "</ul>";
				$body = "<div class=\"btn-group\">" . $body . "</div>";
			}
			if (count($links) > 0) {
				$oListOpt->Body = $body;
				$oListOpt->Visible = TRUE;
			}
		}
		$DetailViewTblVar = "";
		$DetailCopyTblVar = "";
		$DetailEditTblVar = "";

		// "detail_t_daftard"
		$oListOpt = &$this->ListOptions->Items["detail_t_daftard"];
		if ($Security->AllowList(CurrentProjectID() . 't_daftard')) {
			$body = $Language->Phrase("DetailLink") . $Language->TablePhrase("t_daftard", "TblCaption");
			$body = "<a class=\"btn btn-default btn-sm ewRowLink ewDetail\" data-action=\"list\" href=\"" . ew_HtmlEncode("t_daftardlist.php?" . EW_TABLE_SHOW_MASTER . "=t_daftarm&fk_DaftarmID=" . urlencode(strval($this->DaftarmID->CurrentValue)) . "") . "\">" . $body . "</a>";
			$links = "";
			if ($GLOBALS["t_daftard_grid"]->DetailView && $Security->CanView() && $Security->AllowView(CurrentProjectID() . 't_daftard')) {
				$links .= "<li><a class=\"ewRowLink ewDetailView\" data-action=\"view\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailViewLink")) . "\" href=\"" . ew_HtmlEncode($this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=t_daftard")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailViewLink")) . "</a></li>";
				if ($DetailViewTblVar <> "") $DetailViewTblVar .= ",";
				$DetailViewTblVar .= "t_daftard";
			}
			if ($GLOBALS["t_daftard_grid"]->DetailEdit && $Security->CanEdit() && $Security->AllowEdit(CurrentProjectID() . 't_daftard')) {
				$links .= "<li><a class=\"ewRowLink ewDetailEdit\" data-action=\"edit\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailEditLink")) . "\" href=\"" . ew_HtmlEncode($this->GetEditUrl(EW_TABLE_SHOW_DETAIL . "=t_daftard")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailEditLink")) . "</a></li>";
				if ($DetailEditTblVar <> "") $DetailEditTblVar .= ",";
				$DetailEditTblVar .= "t_daftard";
			}
			if ($GLOBALS["t_daftard_grid"]->DetailAdd && $Security->CanAdd() && $Security->AllowAdd(CurrentProjectID() . 't_daftard')) {
				$links .= "<li><a class=\"ewRowLink ewDetailCopy\" data-action=\"add\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailCopyLink")) . "\" href=\"" . ew_HtmlEncode($this->GetCopyUrl(EW_TABLE_SHOW_DETAIL . "=t_daftard")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailCopyLink")) . "</a></li>";
				if ($DetailCopyTblVar <> "") $DetailCopyTblVar .= ",";
				$DetailCopyTblVar .= "t_daftard";
			}
			if ($links <> "") {
				$body .= "<button class=\"dropdown-toggle btn btn-default btn-sm ewDetail\" data-toggle=\"dropdown\"><b class=\"caret\"></b></button>";
				$body .= "<ul class=\"dropdown-menu\">". $links . "</ul>";
			}
			$body = "<div class=\"btn-group\">" . $body . "</div>";
			$oListOpt->Body = $body;
			if ($this->ShowMultipleDetails) $oListOpt->Visible = FALSE;
		}
		if ($this->ShowMultipleDetails) {
			$body = $Language->Phrase("MultipleMasterDetails");
			$body = "<div class=\"btn-group\">";
			$links = "";
			if ($DetailViewTblVar <> "") {
				$links .= "<li><a class=\"ewRowLink ewDetailView\" data-action=\"view\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailViewLink")) . "\" href=\"" . ew_HtmlEncode($this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=" . $DetailViewTblVar)) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailViewLink")) . "</a></li>";
			}
			if ($DetailEditTblVar <> "") {
				$links .= "<li><a class=\"ewRowLink ewDetailEdit\" data-action=\"edit\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailEditLink")) . "\" href=\"" . ew_HtmlEncode($this->GetEditUrl(EW_TABLE_SHOW_DETAIL . "=" . $DetailEditTblVar)) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailEditLink")) . "</a></li>";
			}
			if ($DetailCopyTblVar <> "") {
				$links .= "<li><a class=\"ewRowLink ewDetailCopy\" data-action=\"add\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailCopyLink")) . "\" href=\"" . ew_HtmlEncode($this->GetCopyUrl(EW_TABLE_SHOW_DETAIL . "=" . $DetailCopyTblVar)) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailCopyLink")) . "</a></li>";
			}
			if ($links <> "") {
				$body .= "<button class=\"dropdown-toggle btn btn-default btn-sm ewMasterDetail\" title=\"" . ew_HtmlTitle($Language->Phrase("MultipleMasterDetails")) . "\" data-toggle=\"dropdown\">" . $Language->Phrase("MultipleMasterDetails") . "<b class=\"caret\"></b></button>";
				$body .= "<ul class=\"dropdown-menu ewMenu\">". $links . "</ul>";
			}
			$body .= "</div>";

			// Multiple details
			$oListOpt = &$this->ListOptions->Items["details"];
			$oListOpt->Body = $body;
		}

		// "checkbox"
		$oListOpt = &$this->ListOptions->Items["checkbox"];
		$oListOpt->Body = "<input type=\"checkbox\" name=\"key_m[]\" value=\"" . ew_HtmlEncode($this->DaftarmID->CurrentValue) . "\" onclick='ew_ClickMultiCheckbox(event);'>";
		if ($this->CurrentAction == "gridedit" && is_numeric($this->RowIndex)) {
			$this->MultiSelectKey .= "<input type=\"hidden\" name=\"" . $KeyName . "\" id=\"" . $KeyName . "\" value=\"" . $this->DaftarmID->CurrentValue . "\">";
		}
		$this->RenderListOptionsExt();

		// Call ListOptions_Rendered event
		$this->ListOptions_Rendered();
	}

	// Set up other options
	function SetupOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
		$option = $options["addedit"];

		// Add
		$item = &$option->Add("add");
		$addcaption = ew_HtmlTitle($Language->Phrase("AddLink"));
		$item->Body = "<a class=\"ewAddEdit ewAdd\" title=\"" . $addcaption . "\" data-caption=\"" . $addcaption . "\" href=\"" . ew_HtmlEncode($this->AddUrl) . "\">" . $Language->Phrase("AddLink") . "</a>";
		$item->Visible = ($this->AddUrl <> "" && $Security->CanAdd());

		// Inline Add
		$item = &$option->Add("inlineadd");
		$item->Body = "<a class=\"ewAddEdit ewInlineAdd\" title=\"" . ew_HtmlTitle($Language->Phrase("InlineAddLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("InlineAddLink")) . "\" href=\"" . ew_HtmlEncode($this->InlineAddUrl) . "\">" .$Language->Phrase("InlineAddLink") . "</a>";
		$item->Visible = ($this->InlineAddUrl <> "" && $Security->CanAdd());
		$item = &$option->Add("gridadd");
		$item->Body = "<a class=\"ewAddEdit ewGridAdd\" title=\"" . ew_HtmlTitle($Language->Phrase("GridAddLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("GridAddLink")) . "\" href=\"" . ew_HtmlEncode($this->GridAddUrl) . "\">" . $Language->Phrase("GridAddLink") . "</a>";
		$item->Visible = ($this->GridAddUrl <> "" && $Security->CanAdd());
		$option = $options["detail"];
		$DetailTableLink = "";
		$item = &$option->Add("detailadd_t_daftard");
		$url = $this->GetAddUrl(EW_TABLE_SHOW_DETAIL . "=t_daftard");
		$caption = $Language->Phrase("Add") . "&nbsp;" . $this->TableCaption() . "/" . $GLOBALS["t_daftard"]->TableCaption();
		$item->Body = "<a class=\"ewDetailAddGroup ewDetailAdd\" title=\"" . ew_HtmlTitle($caption) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\" href=\"" . ew_HtmlEncode($url) . "\">" . $caption . "</a>";
		$item->Visible = ($GLOBALS["t_daftard"]->DetailAdd && $Security->AllowAdd(CurrentProjectID() . 't_daftard') && $Security->CanAdd());
		if ($item->Visible) {
			if ($DetailTableLink <> "") $DetailTableLink .= ",";
			$DetailTableLink .= "t_daftard";
		}

		// Add multiple details
		if ($this->ShowMultipleDetails) {
			$item = &$option->Add("detailsadd");
			$url = $this->GetAddUrl(EW_TABLE_SHOW_DETAIL . "=" . $DetailTableLink);
			$item->Body = "<a class=\"ewDetailAddGroup ewDetailAdd\" title=\"" . ew_HtmlTitle($Language->Phrase("AddMasterDetailLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("AddMasterDetailLink")) . "\" href=\"" . ew_HtmlEncode($url) . "\">" . $Language->Phrase("AddMasterDetailLink") . "</a>";
			$item->Visible = ($DetailTableLink <> "" && $Security->CanAdd());

			// Hide single master/detail items
			$ar = explode(",", $DetailTableLink);
			$cnt = count($ar);
			for ($i = 0; $i < $cnt; $i++) {
				if ($item = &$option->GetItem("detailadd_" . $ar[$i]))
					$item->Visible = FALSE;
			}
		}

		// Add grid edit
		$option = $options["addedit"];
		$item = &$option->Add("gridedit");
		$item->Body = "<a class=\"ewAddEdit ewGridEdit\" title=\"" . ew_HtmlTitle($Language->Phrase("GridEditLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("GridEditLink")) . "\" href=\"" . ew_HtmlEncode($this->GridEditUrl) . "\">" . $Language->Phrase("GridEditLink") . "</a>";
		$item->Visible = ($this->GridEditUrl <> "" && $Security->CanEdit());
		$option = $options["action"];

		// Add multi delete
		$item = &$option->Add("multidelete");
		$item->Body = "<a class=\"ewAction ewMultiDelete\" title=\"" . ew_HtmlTitle($Language->Phrase("DeleteSelectedLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("DeleteSelectedLink")) . "\" href=\"\" onclick=\"ew_SubmitAction(event,{f:document.ft_daftarmlist,url:'" . $this->MultiDeleteUrl . "'});return false;\">" . $Language->Phrase("DeleteSelectedLink") . "</a>";
		$item->Visible = ($Security->CanDelete());

		// Set up options default
		foreach ($options as &$option) {
			$option->UseImageAndText = TRUE;
			$option->UseDropDownButton = TRUE;
			$option->UseButtonGroup = TRUE;
			$option->ButtonClass = "btn-sm"; // Class for button group
			$item = &$option->Add($option->GroupOptionName);
			$item->Body = "";
			$item->Visible = FALSE;
		}
		$options["addedit"]->DropDownButtonPhrase = $Language->Phrase("ButtonAddEdit");
		$options["detail"]->DropDownButtonPhrase = $Language->Phrase("ButtonDetails");
		$options["action"]->DropDownButtonPhrase = $Language->Phrase("ButtonActions");

		// Filter button
		$item = &$this->FilterOptions->Add("savecurrentfilter");
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"ft_daftarmlistsrch\" href=\"#\">" . $Language->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"ft_daftarmlistsrch\" href=\"#\">" . $Language->Phrase("DeleteFilter") . "</a>";
		$item->Visible = TRUE;
		$this->FilterOptions->UseDropDownButton = TRUE;
		$this->FilterOptions->UseButtonGroup = !$this->FilterOptions->UseDropDownButton;
		$this->FilterOptions->DropDownButtonPhrase = $Language->Phrase("Filters");

		// Add group option item
		$item = &$this->FilterOptions->Add($this->FilterOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;
	}

	// Render other options
	function RenderOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
		if ($this->CurrentAction <> "gridadd" && $this->CurrentAction <> "gridedit") { // Not grid add/edit mode
			$option = &$options["action"];

			// Set up list action buttons
			foreach ($this->ListActions->Items as $listaction) {
				if ($listaction->Select == EW_ACTION_MULTIPLE) {
					$item = &$option->Add("custom_" . $listaction->Action);
					$caption = $listaction->Caption;
					$icon = ($listaction->Icon <> "") ? "<span class=\"" . ew_HtmlEncode($listaction->Icon) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\"></span> " : $caption;
					$item->Body = "<a class=\"ewAction ewListAction\" title=\"" . ew_HtmlEncode($caption) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({f:document.ft_daftarmlist}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . "</a>";
					$item->Visible = $listaction->Allow;
				}
			}

			// Hide grid edit and other options
			if ($this->TotalRecs <= 0) {
				$option = &$options["addedit"];
				$item = &$option->GetItem("gridedit");
				if ($item) $item->Visible = FALSE;
				$option = &$options["action"];
				$option->HideAllOptions();
			}
		} else { // Grid add/edit mode

			// Hide all options first
			foreach ($options as &$option)
				$option->HideAllOptions();
			if ($this->CurrentAction == "gridadd") {
				if ($this->AllowAddDeleteRow) {

					// Add add blank row
					$option = &$options["addedit"];
					$option->UseDropDownButton = FALSE;
					$option->UseImageAndText = TRUE;
					$item = &$option->Add("addblankrow");
					$item->Body = "<a class=\"ewAddEdit ewAddBlankRow\" title=\"" . ew_HtmlTitle($Language->Phrase("AddBlankRow")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("AddBlankRow")) . "\" href=\"javascript:void(0);\" onclick=\"ew_AddGridRow(this);\">" . $Language->Phrase("AddBlankRow") . "</a>";
					$item->Visible = $Security->CanAdd();
				}
				$option = &$options["action"];
				$option->UseDropDownButton = FALSE;
				$option->UseImageAndText = TRUE;

				// Add grid insert
				$item = &$option->Add("gridinsert");
				$item->Body = "<a class=\"ewAction ewGridInsert\" title=\"" . ew_HtmlTitle($Language->Phrase("GridInsertLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("GridInsertLink")) . "\" href=\"\" onclick=\"return ewForms(this).Submit('" . $this->PageName() . "');\">" . $Language->Phrase("GridInsertLink") . "</a>";

				// Add grid cancel
				$item = &$option->Add("gridcancel");
				$cancelurl = $this->AddMasterUrl($this->PageUrl() . "a=cancel");
				$item->Body = "<a class=\"ewAction ewGridCancel\" title=\"" . ew_HtmlTitle($Language->Phrase("GridCancelLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("GridCancelLink")) . "\" href=\"" . $cancelurl . "\">" . $Language->Phrase("GridCancelLink") . "</a>";
			}
			if ($this->CurrentAction == "gridedit") {
				if ($this->AllowAddDeleteRow) {

					// Add add blank row
					$option = &$options["addedit"];
					$option->UseDropDownButton = FALSE;
					$option->UseImageAndText = TRUE;
					$item = &$option->Add("addblankrow");
					$item->Body = "<a class=\"ewAddEdit ewAddBlankRow\" title=\"" . ew_HtmlTitle($Language->Phrase("AddBlankRow")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("AddBlankRow")) . "\" href=\"javascript:void(0);\" onclick=\"ew_AddGridRow(this);\">" . $Language->Phrase("AddBlankRow") . "</a>";
					$item->Visible = $Security->CanAdd();
				}
				$option = &$options["action"];
				$option->UseDropDownButton = FALSE;
				$option->UseImageAndText = TRUE;
					$item = &$option->Add("gridsave");
					$item->Body = "<a class=\"ewAction ewGridSave\" title=\"" . ew_HtmlTitle($Language->Phrase("GridSaveLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("GridSaveLink")) . "\" href=\"\" onclick=\"return ewForms(this).Submit('" . $this->PageName() . "');\">" . $Language->Phrase("GridSaveLink") . "</a>";
					$item = &$option->Add("gridcancel");
					$cancelurl = $this->AddMasterUrl($this->PageUrl() . "a=cancel");
					$item->Body = "<a class=\"ewAction ewGridCancel\" title=\"" . ew_HtmlTitle($Language->Phrase("GridCancelLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("GridCancelLink")) . "\" href=\"" . $cancelurl . "\">" . $Language->Phrase("GridCancelLink") . "</a>";
			}
		}
	}

	// Process list action
	function ProcessListAction() {
		global $Language, $Security;
		$userlist = "";
		$user = "";
		$sFilter = $this->GetKeyFilter();
		$UserAction = @$_POST["useraction"];
		if ($sFilter <> "" && $UserAction <> "") {

			// Check permission first
			$ActionCaption = $UserAction;
			if (array_key_exists($UserAction, $this->ListActions->Items)) {
				$ActionCaption = $this->ListActions->Items[$UserAction]->Caption;
				if (!$this->ListActions->Items[$UserAction]->Allow) {
					$errmsg = str_replace('%s', $ActionCaption, $Language->Phrase("CustomActionNotAllowed"));
					if (@$_POST["ajax"] == $UserAction) // Ajax
						echo "<p class=\"text-danger\">" . $errmsg . "</p>";
					else
						$this->setFailureMessage($errmsg);
					return FALSE;
				}
			}
			$this->CurrentFilter = $sFilter;
			$sSql = $this->SQL();
			$conn = &$this->Connection();
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			$rs = $conn->Execute($sSql);
			$conn->raiseErrorFn = '';
			$this->CurrentAction = $UserAction;

			// Call row action event
			if ($rs && !$rs->EOF) {
				$conn->BeginTrans();
				$this->SelectedCount = $rs->RecordCount();
				$this->SelectedIndex = 0;
				while (!$rs->EOF) {
					$this->SelectedIndex++;
					$row = $rs->fields;
					$Processed = $this->Row_CustomAction($UserAction, $row);
					if (!$Processed) break;
					$rs->MoveNext();
				}
				if ($Processed) {
					$conn->CommitTrans(); // Commit the changes
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage(str_replace('%s', $ActionCaption, $Language->Phrase("CustomActionCompleted"))); // Set up success message
				} else {
					$conn->RollbackTrans(); // Rollback changes

					// Set up error message
					if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

						// Use the message, do nothing
					} elseif ($this->CancelMessage <> "") {
						$this->setFailureMessage($this->CancelMessage);
						$this->CancelMessage = "";
					} else {
						$this->setFailureMessage(str_replace('%s', $ActionCaption, $Language->Phrase("CustomActionFailed")));
					}
				}
			}
			if ($rs)
				$rs->Close();
			$this->CurrentAction = ""; // Clear action
			if (@$_POST["ajax"] == $UserAction) { // Ajax
				if ($this->getSuccessMessage() <> "") {
					echo "<p class=\"text-success\">" . $this->getSuccessMessage() . "</p>";
					$this->ClearSuccessMessage(); // Clear message
				}
				if ($this->getFailureMessage() <> "") {
					echo "<p class=\"text-danger\">" . $this->getFailureMessage() . "</p>";
					$this->ClearFailureMessage(); // Clear message
				}
				return TRUE;
			}
		}
		return FALSE; // Not ajax request
	}

	// Set up search options
	function SetupSearchOptions() {
		global $Language;
		$this->SearchOptions = new cListOptions();
		$this->SearchOptions->Tag = "div";
		$this->SearchOptions->TagClassName = "ewSearchOption";

		// Search button
		$item = &$this->SearchOptions->Add("searchtoggle");
		$SearchToggleClass = ($this->SearchWhere <> "") ? " active" : " active";
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"ft_daftarmlistsrch\">" . $Language->Phrase("SearchBtn") . "</button>";
		$item->Visible = TRUE;

		// Show all button
		$item = &$this->SearchOptions->Add("showall");
		$item->Body = "<a class=\"btn btn-default ewShowAll\" title=\"" . $Language->Phrase("ShowAll") . "\" data-caption=\"" . $Language->Phrase("ShowAll") . "\" href=\"" . $this->PageUrl() . "cmd=reset\">" . $Language->Phrase("ShowAllBtn") . "</a>";
		$item->Visible = ($this->SearchWhere <> $this->DefaultSearchWhere && $this->SearchWhere <> "0=101");

		// Button group for search
		$this->SearchOptions->UseDropDownButton = FALSE;
		$this->SearchOptions->UseImageAndText = TRUE;
		$this->SearchOptions->UseButtonGroup = TRUE;
		$this->SearchOptions->DropDownButtonPhrase = $Language->Phrase("ButtonSearch");

		// Add group option item
		$item = &$this->SearchOptions->Add($this->SearchOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;

		// Hide search options
		if ($this->Export <> "" || $this->CurrentAction <> "")
			$this->SearchOptions->HideAllOptions();
		global $Security;
		if (!$Security->CanSearch()) {
			$this->SearchOptions->HideAllOptions();
			$this->FilterOptions->HideAllOptions();
		}
	}

	function SetupListOptionsExt() {
		global $Security, $Language;
	}

	function RenderListOptionsExt() {
		global $Security, $Language;
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

	// Load default values
	function LoadDefaultValues() {
		$this->_UserID->CurrentValue = 0;
		$this->_UserID->OldValue = $this->_UserID->CurrentValue;
		$this->TglJam->CurrentValue = "1970-01-01 00:00:00";
		$this->TglJam->OldValue = $this->TglJam->CurrentValue;
		$this->BuktiBayar->Upload->DbValue = NULL;
		$this->BuktiBayar->OldValue = $this->BuktiBayar->Upload->DbValue;
		$this->JumlahBayar->CurrentValue = 0.00;
		$this->JumlahBayar->OldValue = $this->JumlahBayar->CurrentValue;
		$this->Acc->CurrentValue = 0;
		$this->Acc->OldValue = $this->Acc->CurrentValue;
	}

	// Load basic search values
	function LoadBasicSearchValues() {
		$this->BasicSearch->Keyword = @$_GET[EW_TABLE_BASIC_SEARCH];
		if ($this->BasicSearch->Keyword <> "") $this->Command = "search";
		$this->BasicSearch->Type = @$_GET[EW_TABLE_BASIC_SEARCH_TYPE];
	}

	// Load search values for validation
	function LoadSearchValues() {
		global $objForm;

		// Load search values
		// DaftarmID

		$this->DaftarmID->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_DaftarmID"]);
		if ($this->DaftarmID->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->DaftarmID->AdvancedSearch->SearchOperator = @$_GET["z_DaftarmID"];

		// UserID
		$this->_UserID->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x__UserID"]);
		if ($this->_UserID->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->_UserID->AdvancedSearch->SearchOperator = @$_GET["z__UserID"];

		// TglJam
		$this->TglJam->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_TglJam"]);
		if ($this->TglJam->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->TglJam->AdvancedSearch->SearchOperator = @$_GET["z_TglJam"];

		// BuktiBayar
		$this->BuktiBayar->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_BuktiBayar"]);
		if ($this->BuktiBayar->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->BuktiBayar->AdvancedSearch->SearchOperator = @$_GET["z_BuktiBayar"];

		// JumlahBayar
		$this->JumlahBayar->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_JumlahBayar"]);
		if ($this->JumlahBayar->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->JumlahBayar->AdvancedSearch->SearchOperator = @$_GET["z_JumlahBayar"];

		// Acc
		$this->Acc->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_Acc"]);
		if ($this->Acc->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->Acc->AdvancedSearch->SearchOperator = @$_GET["z_Acc"];
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		$this->GetUploadFiles(); // Get upload files
		if (!$this->_UserID->FldIsDetailKey) {
			$this->_UserID->setFormValue($objForm->GetValue("x__UserID"));
		}
		$this->_UserID->setOldValue($objForm->GetValue("o__UserID"));
		if (!$this->TglJam->FldIsDetailKey) {
			$this->TglJam->setFormValue($objForm->GetValue("x_TglJam"));
			$this->TglJam->CurrentValue = ew_UnFormatDateTime($this->TglJam->CurrentValue, 0);
		}
		$this->TglJam->setOldValue($objForm->GetValue("o_TglJam"));
		if (!$this->JumlahBayar->FldIsDetailKey) {
			$this->JumlahBayar->setFormValue($objForm->GetValue("x_JumlahBayar"));
		}
		$this->JumlahBayar->setOldValue($objForm->GetValue("o_JumlahBayar"));
		if (!$this->Acc->FldIsDetailKey) {
			$this->Acc->setFormValue($objForm->GetValue("x_Acc"));
		}
		$this->Acc->setOldValue($objForm->GetValue("o_Acc"));
		if (!$this->DaftarmID->FldIsDetailKey && $this->CurrentAction <> "gridadd" && $this->CurrentAction <> "add")
			$this->DaftarmID->setFormValue($objForm->GetValue("x_DaftarmID"));
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		if ($this->CurrentAction <> "gridadd" && $this->CurrentAction <> "add")
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
		$this->ViewUrl = $this->GetViewUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->InlineEditUrl = $this->GetInlineEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->InlineCopyUrl = $this->GetInlineCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();

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
				$this->BuktiBayar->LinkAttrs["data-rel"] = "t_daftarm_x" . $this->RowCnt . "_BuktiBayar";
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
			if (!$GLOBALS["t_daftarm"]->UserIDAllow($GLOBALS["t_daftarm"]->CurrentAction)) $sWhereWrk = $GLOBALS["t_user"]->AddUserIDFilter($sWhereWrk);
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
			if (is_numeric($this->RowIndex) && !$this->EventCancelled) ew_RenderUploadField($this->BuktiBayar, $this->RowIndex);

			// JumlahBayar
			$this->JumlahBayar->EditAttrs["class"] = "form-control";
			$this->JumlahBayar->EditCustomAttributes = "";
			$this->JumlahBayar->EditValue = ew_HtmlEncode($this->JumlahBayar->CurrentValue);
			$this->JumlahBayar->PlaceHolder = ew_RemoveHtml($this->JumlahBayar->FldCaption());
			if (strval($this->JumlahBayar->EditValue) <> "" && is_numeric($this->JumlahBayar->EditValue)) {
			$this->JumlahBayar->EditValue = ew_FormatNumber($this->JumlahBayar->EditValue, -2, -2, -2, -2);
			$this->JumlahBayar->OldValue = $this->JumlahBayar->EditValue;
			}

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
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

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
			if (!$GLOBALS["t_daftarm"]->UserIDAllow($GLOBALS["t_daftarm"]->CurrentAction)) $sWhereWrk = $GLOBALS["t_user"]->AddUserIDFilter($sWhereWrk);
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
			if (is_numeric($this->RowIndex) && !$this->EventCancelled) ew_RenderUploadField($this->BuktiBayar, $this->RowIndex);

			// JumlahBayar
			$this->JumlahBayar->EditAttrs["class"] = "form-control";
			$this->JumlahBayar->EditCustomAttributes = "";
			$this->JumlahBayar->EditValue = ew_HtmlEncode($this->JumlahBayar->CurrentValue);
			$this->JumlahBayar->PlaceHolder = ew_RemoveHtml($this->JumlahBayar->FldCaption());
			if (strval($this->JumlahBayar->EditValue) <> "" && is_numeric($this->JumlahBayar->EditValue)) {
			$this->JumlahBayar->EditValue = ew_FormatNumber($this->JumlahBayar->EditValue, -2, -2, -2, -2);
			$this->JumlahBayar->OldValue = $this->JumlahBayar->EditValue;
			}

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
		} elseif ($this->RowType == EW_ROWTYPE_SEARCH) { // Search row

			// UserID
			$this->_UserID->EditAttrs["class"] = "form-control";
			$this->_UserID->EditCustomAttributes = "";

			// TglJam
			$this->TglJam->EditAttrs["class"] = "form-control";
			$this->TglJam->EditCustomAttributes = "";
			$this->TglJam->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->TglJam->AdvancedSearch->SearchValue, 0), 8));
			$this->TglJam->PlaceHolder = ew_RemoveHtml($this->TglJam->FldCaption());

			// BuktiBayar
			$this->BuktiBayar->EditAttrs["class"] = "form-control";
			$this->BuktiBayar->EditCustomAttributes = "";
			$this->BuktiBayar->EditValue = ew_HtmlEncode($this->BuktiBayar->AdvancedSearch->SearchValue);
			$this->BuktiBayar->PlaceHolder = ew_RemoveHtml($this->BuktiBayar->FldCaption());

			// JumlahBayar
			$this->JumlahBayar->EditAttrs["class"] = "form-control";
			$this->JumlahBayar->EditCustomAttributes = "";
			$this->JumlahBayar->EditValue = ew_HtmlEncode($this->JumlahBayar->AdvancedSearch->SearchValue);
			$this->JumlahBayar->PlaceHolder = ew_RemoveHtml($this->JumlahBayar->FldCaption());

			// Acc
			$this->Acc->EditCustomAttributes = "";
			$this->Acc->EditValue = $this->Acc->Options(FALSE);
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

	// Validate search
	function ValidateSearch() {
		global $gsSearchError;

		// Initialize
		$gsSearchError = "";

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return TRUE;

		// Return validate result
		$ValidateSearch = ($gsSearchError == "");

		// Call Form_CustomValidate event
		$sFormCustomError = "";
		$ValidateSearch = $ValidateSearch && $this->Form_CustomValidate($sFormCustomError);
		if ($sFormCustomError <> "") {
			ew_AddMessage($gsSearchError, $sFormCustomError);
		}
		return $ValidateSearch;
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

	//
	// Delete records based on current filter
	//
	function DeleteRows() {
		global $Language, $Security;
		if (!$Security->CanDelete()) {
			$this->setFailureMessage($Language->Phrase("NoDeletePermission")); // No delete permission
			return FALSE;
		}
		$DeleteRows = TRUE;
		$sSql = $this->SQL();
		$conn = &$this->Connection();
		$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE) {
			return FALSE;
		} elseif ($rs->EOF) {
			$this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
			$rs->Close();
			return FALSE;

		//} else {
		//	$this->LoadRowValues($rs); // Load row values

		}
		$rows = ($rs) ? $rs->GetRows() : array();
		if ($this->AuditTrailOnDelete) $this->WriteAuditTrailDummy($Language->Phrase("BatchDeleteBegin")); // Batch delete begin

		// Clone old rows
		$rsold = $rows;
		if ($rs)
			$rs->Close();

		// Call row deleting event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$DeleteRows = $this->Row_Deleting($row);
				if (!$DeleteRows) break;
			}
		}
		if ($DeleteRows) {
			$sKey = "";
			foreach ($rsold as $row) {
				$sThisKey = "";
				if ($sThisKey <> "") $sThisKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
				$sThisKey .= $row['DaftarmID'];
				$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
				$DeleteRows = $this->Delete($row); // Delete
				$conn->raiseErrorFn = '';
				if ($DeleteRows === FALSE)
					break;
				if ($sKey <> "") $sKey .= ", ";
				$sKey .= $sThisKey;
			}
		} else {

			// Set up error message
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("DeleteCancelled"));
			}
		}
		if ($DeleteRows) {
			if ($this->AuditTrailOnDelete) $this->WriteAuditTrailDummy($Language->Phrase("BatchDeleteSuccess")); // Batch delete success
		} else {
		}

		// Call Row Deleted event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$this->Row_Deleted($row);
			}
		}
		return $DeleteRows;
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

	// Add record
	function AddRow($rsold = NULL) {
		global $Language, $Security;
		$conn = &$this->Connection();

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
		if ($AddRow) {

			// Call Row Inserted event
			$rs = ($rsold == NULL) ? NULL : $rsold->fields;
			$this->Row_Inserted($rs, $rsnew);
		}

		// BuktiBayar
		ew_CleanUploadTempPath($this->BuktiBayar, $this->BuktiBayar->Upload->Index);
		return $AddRow;
	}

	// Load advanced search
	function LoadAdvancedSearch() {
		$this->DaftarmID->AdvancedSearch->Load();
		$this->_UserID->AdvancedSearch->Load();
		$this->TglJam->AdvancedSearch->Load();
		$this->BuktiBayar->AdvancedSearch->Load();
		$this->JumlahBayar->AdvancedSearch->Load();
		$this->Acc->AdvancedSearch->Load();
	}

	// Set up export options
	function SetupExportOptions() {
		global $Language;

		// Printer friendly
		$item = &$this->ExportOptions->Add("print");
		$item->Body = "<a href=\"" . $this->ExportPrintUrl . "\" class=\"ewExportLink ewPrint\" title=\"" . ew_HtmlEncode($Language->Phrase("PrinterFriendlyText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("PrinterFriendlyText")) . "\">" . $Language->Phrase("PrinterFriendly") . "</a>";
		$item->Visible = TRUE;

		// Export to Excel
		$item = &$this->ExportOptions->Add("excel");
		$item->Body = "<a href=\"" . $this->ExportExcelUrl . "\" class=\"ewExportLink ewExcel\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToExcelText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToExcelText")) . "\">" . $Language->Phrase("ExportToExcel") . "</a>";
		$item->Visible = TRUE;

		// Export to Word
		$item = &$this->ExportOptions->Add("word");
		$item->Body = "<a href=\"" . $this->ExportWordUrl . "\" class=\"ewExportLink ewWord\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToWordText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToWordText")) . "\">" . $Language->Phrase("ExportToWord") . "</a>";
		$item->Visible = TRUE;

		// Export to Html
		$item = &$this->ExportOptions->Add("html");
		$item->Body = "<a href=\"" . $this->ExportHtmlUrl . "\" class=\"ewExportLink ewHtml\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToHtmlText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToHtmlText")) . "\">" . $Language->Phrase("ExportToHtml") . "</a>";
		$item->Visible = TRUE;

		// Export to Xml
		$item = &$this->ExportOptions->Add("xml");
		$item->Body = "<a href=\"" . $this->ExportXmlUrl . "\" class=\"ewExportLink ewXml\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToXmlText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToXmlText")) . "\">" . $Language->Phrase("ExportToXml") . "</a>";
		$item->Visible = TRUE;

		// Export to Csv
		$item = &$this->ExportOptions->Add("csv");
		$item->Body = "<a href=\"" . $this->ExportCsvUrl . "\" class=\"ewExportLink ewCsv\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToCsvText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToCsvText")) . "\">" . $Language->Phrase("ExportToCsv") . "</a>";
		$item->Visible = TRUE;

		// Export to Pdf
		$item = &$this->ExportOptions->Add("pdf");
		$item->Body = "<a href=\"" . $this->ExportPdfUrl . "\" class=\"ewExportLink ewPdf\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToPDFText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToPDFText")) . "\">" . $Language->Phrase("ExportToPDF") . "</a>";
		$item->Visible = TRUE;

		// Export to Email
		$item = &$this->ExportOptions->Add("email");
		$url = "";
		$item->Body = "<button id=\"emf_t_daftarm\" class=\"ewExportLink ewEmail\" title=\"" . $Language->Phrase("ExportToEmailText") . "\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_t_daftarm',hdr:ewLanguage.Phrase('ExportToEmailText'),f:document.ft_daftarmlist,sel:false" . $url . "});\">" . $Language->Phrase("ExportToEmail") . "</button>";
		$item->Visible = TRUE;

		// Drop down button for export
		$this->ExportOptions->UseButtonGroup = TRUE;
		$this->ExportOptions->UseImageAndText = TRUE;
		$this->ExportOptions->UseDropDownButton = TRUE;
		if ($this->ExportOptions->UseButtonGroup && ew_IsMobile())
			$this->ExportOptions->UseDropDownButton = TRUE;
		$this->ExportOptions->DropDownButtonPhrase = $Language->Phrase("ButtonExport");

		// Add group option item
		$item = &$this->ExportOptions->Add($this->ExportOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;
	}

	// Export data in HTML/CSV/Word/Excel/XML/Email/PDF format
	function ExportData() {
		$utf8 = (strtolower(EW_CHARSET) == "utf-8");
		$bSelectLimit = $this->UseSelectLimit;

		// Load recordset
		if ($bSelectLimit) {
			$this->TotalRecs = $this->SelectRecordCount();
		} else {
			if (!$this->Recordset)
				$this->Recordset = $this->LoadRecordset();
			$rs = &$this->Recordset;
			if ($rs)
				$this->TotalRecs = $rs->RecordCount();
		}
		$this->StartRec = 1;

		// Export all
		if ($this->ExportAll) {
			set_time_limit(EW_EXPORT_ALL_TIME_LIMIT);
			$this->DisplayRecs = $this->TotalRecs;
			$this->StopRec = $this->TotalRecs;
		} else { // Export one page only
			$this->SetUpStartRec(); // Set up start record position

			// Set the last record to display
			if ($this->DisplayRecs <= 0) {
				$this->StopRec = $this->TotalRecs;
			} else {
				$this->StopRec = $this->StartRec + $this->DisplayRecs - 1;
			}
		}
		if ($bSelectLimit)
			$rs = $this->LoadRecordset($this->StartRec-1, $this->DisplayRecs <= 0 ? $this->TotalRecs : $this->DisplayRecs);
		if (!$rs) {
			header("Content-Type:"); // Remove header
			header("Content-Disposition:");
			$this->ShowMessage();
			return;
		}
		$this->ExportDoc = ew_ExportDocument($this, "h");
		$Doc = &$this->ExportDoc;
		if ($bSelectLimit) {
			$this->StartRec = 1;
			$this->StopRec = $this->DisplayRecs <= 0 ? $this->TotalRecs : $this->DisplayRecs;
		} else {

			//$this->StartRec = $this->StartRec;
			//$this->StopRec = $this->StopRec;

		}

		// Call Page Exporting server event
		$this->ExportDoc->ExportCustom = !$this->Page_Exporting();
		$ParentTable = "";
		$sHeader = $this->PageHeader;
		$this->Page_DataRendering($sHeader);
		$Doc->Text .= $sHeader;
		$this->ExportDocument($Doc, $rs, $this->StartRec, $this->StopRec, "");
		$sFooter = $this->PageFooter;
		$this->Page_DataRendered($sFooter);
		$Doc->Text .= $sFooter;

		// Close recordset
		$rs->Close();

		// Call Page Exported server event
		$this->Page_Exported();

		// Export header and footer
		$Doc->ExportHeaderAndFooter();

		// Clean output buffer
		if (!EW_DEBUG_ENABLED && ob_get_length())
			ob_end_clean();

		// Write debug message if enabled
		if (EW_DEBUG_ENABLED && $this->Export <> "pdf")
			echo ew_DebugMsg();

		// Output data
		if ($this->Export == "email") {
			echo $this->ExportEmail($Doc->Text);
		} else {
			$Doc->Export();
		}
	}

	// Export email
	function ExportEmail($EmailContent) {
		global $gTmpImages, $Language;
		$sSender = @$_POST["sender"];
		$sRecipient = @$_POST["recipient"];
		$sCc = @$_POST["cc"];
		$sBcc = @$_POST["bcc"];
		$sContentType = @$_POST["contenttype"];

		// Subject
		$sSubject = ew_StripSlashes(@$_POST["subject"]);
		$sEmailSubject = $sSubject;

		// Message
		$sContent = ew_StripSlashes(@$_POST["message"]);
		$sEmailMessage = $sContent;

		// Check sender
		if ($sSender == "") {
			return "<p class=\"text-danger\">" . $Language->Phrase("EnterSenderEmail") . "</p>";
		}
		if (!ew_CheckEmail($sSender)) {
			return "<p class=\"text-danger\">" . $Language->Phrase("EnterProperSenderEmail") . "</p>";
		}

		// Check recipient
		if (!ew_CheckEmailList($sRecipient, EW_MAX_EMAIL_RECIPIENT)) {
			return "<p class=\"text-danger\">" . $Language->Phrase("EnterProperRecipientEmail") . "</p>";
		}

		// Check cc
		if (!ew_CheckEmailList($sCc, EW_MAX_EMAIL_RECIPIENT)) {
			return "<p class=\"text-danger\">" . $Language->Phrase("EnterProperCcEmail") . "</p>";
		}

		// Check bcc
		if (!ew_CheckEmailList($sBcc, EW_MAX_EMAIL_RECIPIENT)) {
			return "<p class=\"text-danger\">" . $Language->Phrase("EnterProperBccEmail") . "</p>";
		}

		// Check email sent count
		if (!isset($_SESSION[EW_EXPORT_EMAIL_COUNTER]))
			$_SESSION[EW_EXPORT_EMAIL_COUNTER] = 0;
		if (intval($_SESSION[EW_EXPORT_EMAIL_COUNTER]) > EW_MAX_EMAIL_SENT_COUNT) {
			return "<p class=\"text-danger\">" . $Language->Phrase("ExceedMaxEmailExport") . "</p>";
		}

		// Send email
		$Email = new cEmail();
		$Email->Sender = $sSender; // Sender
		$Email->Recipient = $sRecipient; // Recipient
		$Email->Cc = $sCc; // Cc
		$Email->Bcc = $sBcc; // Bcc
		$Email->Subject = $sEmailSubject; // Subject
		$Email->Format = ($sContentType == "url") ? "text" : "html";
		if ($sEmailMessage <> "") {
			$sEmailMessage = ew_RemoveXSS($sEmailMessage);
			$sEmailMessage .= ($sContentType == "url") ? "\r\n\r\n" : "<br><br>";
		}
		if ($sContentType == "url") {
			$sUrl = ew_ConvertFullUrl(ew_CurrentPage() . "?" . $this->ExportQueryString());
			$sEmailMessage .= $sUrl; // Send URL only
		} else {
			foreach ($gTmpImages as $tmpimage)
				$Email->AddEmbeddedImage($tmpimage);
			$sEmailMessage .= ew_CleanEmailContent($EmailContent); // Send HTML
		}
		$Email->Content = $sEmailMessage; // Content
		$EventArgs = array();
		if ($this->Recordset) {
			$this->RecCnt = $this->StartRec - 1;
			$this->Recordset->MoveFirst();
			if ($this->StartRec > 1)
				$this->Recordset->Move($this->StartRec - 1);
			$EventArgs["rs"] = &$this->Recordset;
		}
		$bEmailSent = FALSE;
		if ($this->Email_Sending($Email, $EventArgs))
			$bEmailSent = $Email->Send();

		// Check email sent status
		if ($bEmailSent) {

			// Update email sent count
			$_SESSION[EW_EXPORT_EMAIL_COUNTER]++;

			// Sent email success
			return "<p class=\"text-success\">" . $Language->Phrase("SendEmailSuccess") . "</p>"; // Set up success message
		} else {

			// Sent email failure
			return "<p class=\"text-danger\">" . $Email->SendErrDescription . "</p>";
		}
	}

	// Export QueryString
	function ExportQueryString() {

		// Initialize
		$sQry = "export=html";

		// Build QueryString for search
		if ($this->BasicSearch->getKeyword() <> "") {
			$sQry .= "&" . EW_TABLE_BASIC_SEARCH . "=" . urlencode($this->BasicSearch->getKeyword()) . "&" . EW_TABLE_BASIC_SEARCH_TYPE . "=" . urlencode($this->BasicSearch->getType());
		}
		$this->AddSearchQueryString($sQry, $this->DaftarmID); // DaftarmID
		$this->AddSearchQueryString($sQry, $this->_UserID); // UserID
		$this->AddSearchQueryString($sQry, $this->TglJam); // TglJam
		$this->AddSearchQueryString($sQry, $this->JumlahBayar); // JumlahBayar
		$this->AddSearchQueryString($sQry, $this->Acc); // Acc

		// Build QueryString for pager
		$sQry .= "&" . EW_TABLE_REC_PER_PAGE . "=" . urlencode($this->getRecordsPerPage()) . "&" . EW_TABLE_START_REC . "=" . urlencode($this->getStartRecordNumber());
		return $sQry;
	}

	// Add search QueryString
	function AddSearchQueryString(&$Qry, &$Fld) {
		$FldSearchValue = $Fld->AdvancedSearch->getValue("x");
		$FldParm = substr($Fld->FldVar,2);
		if (strval($FldSearchValue) <> "") {
			$Qry .= "&x_" . $FldParm . "=" . urlencode($FldSearchValue) .
				"&z_" . $FldParm . "=" . urlencode($Fld->AdvancedSearch->getValue("z"));
		}
		$FldSearchValue2 = $Fld->AdvancedSearch->getValue("y");
		if (strval($FldSearchValue2) <> "") {
			$Qry .= "&v_" . $FldParm . "=" . urlencode($Fld->AdvancedSearch->getValue("v")) .
				"&y_" . $FldParm . "=" . urlencode($FldSearchValue2) .
				"&w_" . $FldParm . "=" . urlencode($Fld->AdvancedSearch->getValue("w"));
		}
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$url = preg_replace('/\?cmd=reset(all){0,1}$/i', '', $url); // Remove cmd=reset / cmd=resetall
		$Breadcrumb->Add("list", $this->TableVar, $url, "", $this->TableVar, TRUE);
	}

	// Setup lookup filters of a field
	function SetupLookupFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		if ($pageId == "list") {
			switch ($fld->FldVar) {
		case "x__UserID":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `UserID` AS `LinkFld`, `Nama` AS `DispFld`, `NIM` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `t_user`";
			$sWhereWrk = "{filter}";
			$this->_UserID->LookupFilters = array("dx1" => '`Nama`', "dx2" => '`NIM`');
			if (!$GLOBALS["t_daftarm"]->UserIDAllow($GLOBALS["t_daftarm"]->CurrentAction)) $sWhereWrk = $GLOBALS["t_user"]->AddUserIDFilter($sWhereWrk);
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`UserID` = {filter_value}', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->_UserID, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
			}
		} elseif ($pageId == "extbs") {
			switch ($fld->FldVar) {
			}
		} 
	}

	// Setup AutoSuggest filters of a field
	function SetupAutoSuggestFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		if ($pageId == "list") {
			switch ($fld->FldVar) {
			}
		} elseif ($pageId == "extbs") {
			switch ($fld->FldVar) {
			}
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

	// ListOptions Load event
	function ListOptions_Load() {

		// Example:
		//$opt = &$this->ListOptions->Add("new");
		//$opt->Header = "xxx";
		//$opt->OnLeft = TRUE; // Link on left
		//$opt->MoveTo(0); // Move to first column

	}

	// ListOptions Rendered event
	function ListOptions_Rendered() {

		// Example: 
		//$this->ListOptions->Items["new"]->Body = "xxx";

	}

	// Row Custom Action event
	function Row_CustomAction($action, $row) {

		// Return FALSE to abort
		return TRUE;
	}

	// Page Exporting event
	// $this->ExportDoc = export document object
	function Page_Exporting() {

		//$this->ExportDoc->Text = "my header"; // Export header
		//return FALSE; // Return FALSE to skip default export and use Row_Export event

		return TRUE; // Return TRUE to use default export and skip Row_Export event
	}

	// Row Export event
	// $this->ExportDoc = export document object
	function Row_Export($rs) {

		//$this->ExportDoc->Text .= "my content"; // Build HTML with field value: $rs["MyField"] or $this->MyField->ViewValue
	}

	// Page Exported event
	// $this->ExportDoc = export document object
	function Page_Exported() {

		//$this->ExportDoc->Text .= "my footer"; // Export footer
		//echo $this->ExportDoc->Text;

	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($t_daftarm_list)) $t_daftarm_list = new ct_daftarm_list();

// Page init
$t_daftarm_list->Page_Init();

// Page main
$t_daftarm_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$t_daftarm_list->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($t_daftarm->Export == "") { ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "list";
var CurrentForm = ft_daftarmlist = new ew_Form("ft_daftarmlist", "list");
ft_daftarmlist.FormKeyCountName = '<?php echo $t_daftarm_list->FormKeyCountName ?>';

// Validate form
ft_daftarmlist.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_TglJam");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($t_daftarm->TglJam->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_JumlahBayar");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($t_daftarm->JumlahBayar->FldErrMsg()) ?>");

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
		} // End Grid Add checking
	}
	if (gridinsert && addcnt == 0) { // No row added
		ew_Alert(ewLanguage.Phrase("NoAddRecord"));
		return false;
	}
	return true;
}

// Check empty row
ft_daftarmlist.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "_UserID", false)) return false;
	if (ew_ValueChanged(fobj, infix, "TglJam", false)) return false;
	if (ew_ValueChanged(fobj, infix, "BuktiBayar", false)) return false;
	if (ew_ValueChanged(fobj, infix, "JumlahBayar", false)) return false;
	if (ew_ValueChanged(fobj, infix, "Acc", false)) return false;
	return true;
}

// Form_CustomValidate event
ft_daftarmlist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ft_daftarmlist.ValidateRequired = true;
<?php } else { ?>
ft_daftarmlist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
ft_daftarmlist.Lists["x__UserID"] = {"LinkField":"x__UserID","Ajax":true,"AutoFill":false,"DisplayFields":["x_Nama","x_NIM","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"t_user"};
ft_daftarmlist.Lists["x_Acc"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
ft_daftarmlist.Lists["x_Acc"].Options = <?php echo json_encode($t_daftarm->Acc->Options()) ?>;

// Form object for search
var CurrentSearchForm = ft_daftarmlistsrch = new ew_Form("ft_daftarmlistsrch");

// Validate function for search
ft_daftarmlistsrch.Validate = function(fobj) {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	fobj = fobj || this.Form;
	var infix = "";

	// Fire Form_CustomValidate event
	if (!this.Form_CustomValidate(fobj))
		return false;
	return true;
}

// Form_CustomValidate event
ft_daftarmlistsrch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ft_daftarmlistsrch.ValidateRequired = true; // Use JavaScript validation
<?php } else { ?>
ft_daftarmlistsrch.ValidateRequired = false; // No JavaScript validation
<?php } ?>

// Dynamic selection lists
ft_daftarmlistsrch.Lists["x_Acc"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
ft_daftarmlistsrch.Lists["x_Acc"].Options = <?php echo json_encode($t_daftarm->Acc->Options()) ?>;
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($t_daftarm->Export == "") { ?>
<div class="ewToolbar">
<?php if ($t_daftarm->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($t_daftarm_list->TotalRecs > 0 && $t_daftarm_list->ExportOptions->Visible()) { ?>
<?php $t_daftarm_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($t_daftarm_list->SearchOptions->Visible()) { ?>
<?php $t_daftarm_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php if ($t_daftarm_list->FilterOptions->Visible()) { ?>
<?php $t_daftarm_list->FilterOptions->Render("body") ?>
<?php } ?>
<?php if ($t_daftarm->Export == "") { ?>
<?php echo $Language->SelectionForm(); ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php
if ($t_daftarm->CurrentAction == "gridadd") {
	$t_daftarm->CurrentFilter = "0=1";
	$t_daftarm_list->StartRec = 1;
	$t_daftarm_list->DisplayRecs = $t_daftarm->GridAddRowCount;
	$t_daftarm_list->TotalRecs = $t_daftarm_list->DisplayRecs;
	$t_daftarm_list->StopRec = $t_daftarm_list->DisplayRecs;
} else {
	$bSelectLimit = $t_daftarm_list->UseSelectLimit;
	if ($bSelectLimit) {
		if ($t_daftarm_list->TotalRecs <= 0)
			$t_daftarm_list->TotalRecs = $t_daftarm->SelectRecordCount();
	} else {
		if (!$t_daftarm_list->Recordset && ($t_daftarm_list->Recordset = $t_daftarm_list->LoadRecordset()))
			$t_daftarm_list->TotalRecs = $t_daftarm_list->Recordset->RecordCount();
	}
	$t_daftarm_list->StartRec = 1;
	if ($t_daftarm_list->DisplayRecs <= 0 || ($t_daftarm->Export <> "" && $t_daftarm->ExportAll)) // Display all records
		$t_daftarm_list->DisplayRecs = $t_daftarm_list->TotalRecs;
	if (!($t_daftarm->Export <> "" && $t_daftarm->ExportAll))
		$t_daftarm_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$t_daftarm_list->Recordset = $t_daftarm_list->LoadRecordset($t_daftarm_list->StartRec-1, $t_daftarm_list->DisplayRecs);

	// Set no record found message
	if ($t_daftarm->CurrentAction == "" && $t_daftarm_list->TotalRecs == 0) {
		if (!$Security->CanList())
			$t_daftarm_list->setWarningMessage(ew_DeniedMsg());
		if ($t_daftarm_list->SearchWhere == "0=101")
			$t_daftarm_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$t_daftarm_list->setWarningMessage($Language->Phrase("NoRecord"));
	}

	// Audit trail on search
	if ($t_daftarm_list->AuditTrailOnSearch && $t_daftarm_list->Command == "search" && !$t_daftarm_list->RestoreSearch) {
		$searchparm = ew_ServerVar("QUERY_STRING");
		$searchsql = $t_daftarm_list->getSessionWhere();
		$t_daftarm_list->WriteAuditTrailOnSearch($searchparm, $searchsql);
	}
}
$t_daftarm_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($t_daftarm->Export == "" && $t_daftarm->CurrentAction == "") { ?>
<form name="ft_daftarmlistsrch" id="ft_daftarmlistsrch" class="form-inline ewForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($t_daftarm_list->SearchWhere <> "") ? " in" : " in"; ?>
<div id="ft_daftarmlistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="t_daftarm">
	<div class="ewBasicSearch">
<?php
if ($gsSearchError == "")
	$t_daftarm_list->LoadAdvancedSearch(); // Load advanced search

// Render for search
$t_daftarm->RowType = EW_ROWTYPE_SEARCH;

// Render row
$t_daftarm->ResetAttrs();
$t_daftarm_list->RenderRow();
?>
<div id="xsr_1" class="ewRow">
<?php if ($t_daftarm->Acc->Visible) { // Acc ?>
	<div id="xsc_Acc" class="ewCell form-group">
		<label class="ewSearchCaption ewLabel"><?php echo $t_daftarm->Acc->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_Acc" id="z_Acc" value="="></span>
		<span class="ewSearchField">
<div id="tp_x_Acc" class="ewTemplate"><input type="radio" data-table="t_daftarm" data-field="x_Acc" data-value-separator="<?php echo $t_daftarm->Acc->DisplayValueSeparatorAttribute() ?>" name="x_Acc" id="x_Acc" value="{value}"<?php echo $t_daftarm->Acc->EditAttributes() ?>></div>
<div id="dsl_x_Acc" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $t_daftarm->Acc->RadioButtonListHtml(FALSE, "x_Acc") ?>
</div></div>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_2" class="ewRow">
	<div class="ewQuickSearch input-group">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="form-control" value="<?php echo ew_HtmlEncode($t_daftarm_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo ew_HtmlEncode($Language->Phrase("Search")) ?>">
	<input type="hidden" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" id="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="<?php echo ew_HtmlEncode($t_daftarm_list->BasicSearch->getType()) ?>">
	<div class="input-group-btn">
		<button type="button" data-toggle="dropdown" class="btn btn-default"><span id="searchtype"><?php echo $t_daftarm_list->BasicSearch->getTypeNameShort() ?></span><span class="caret"></span></button>
		<ul class="dropdown-menu pull-right" role="menu">
			<li<?php if ($t_daftarm_list->BasicSearch->getType() == "") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this)"><?php echo $Language->Phrase("QuickSearchAuto") ?></a></li>
			<li<?php if ($t_daftarm_list->BasicSearch->getType() == "=") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'=')"><?php echo $Language->Phrase("QuickSearchExact") ?></a></li>
			<li<?php if ($t_daftarm_list->BasicSearch->getType() == "AND") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'AND')"><?php echo $Language->Phrase("QuickSearchAll") ?></a></li>
			<li<?php if ($t_daftarm_list->BasicSearch->getType() == "OR") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'OR')"><?php echo $Language->Phrase("QuickSearchAny") ?></a></li>
		</ul>
	<button class="btn btn-primary ewButton" name="btnsubmit" id="btnsubmit" type="submit"><?php echo $Language->Phrase("QuickSearchBtn") ?></button>
	</div>
	</div>
</div>
	</div>
</div>
</form>
<?php } ?>
<?php } ?>
<?php $t_daftarm_list->ShowPageHeader(); ?>
<?php
$t_daftarm_list->ShowMessage();
?>
<?php if ($t_daftarm_list->TotalRecs > 0 || $t_daftarm->CurrentAction <> "") { ?>
<div class="panel panel-default ewGrid t_daftarm">
<?php if ($t_daftarm->Export == "") { ?>
<div class="panel-heading ewGridUpperPanel">
<?php if ($t_daftarm->CurrentAction <> "gridadd" && $t_daftarm->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="form-inline ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($t_daftarm_list->Pager)) $t_daftarm_list->Pager = new cPrevNextPager($t_daftarm_list->StartRec, $t_daftarm_list->DisplayRecs, $t_daftarm_list->TotalRecs) ?>
<?php if ($t_daftarm_list->Pager->RecordCount > 0 && $t_daftarm_list->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($t_daftarm_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $t_daftarm_list->PageUrl() ?>start=<?php echo $t_daftarm_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($t_daftarm_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $t_daftarm_list->PageUrl() ?>start=<?php echo $t_daftarm_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $t_daftarm_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($t_daftarm_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $t_daftarm_list->PageUrl() ?>start=<?php echo $t_daftarm_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($t_daftarm_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $t_daftarm_list->PageUrl() ?>start=<?php echo $t_daftarm_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $t_daftarm_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $t_daftarm_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $t_daftarm_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $t_daftarm_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
<?php if ($t_daftarm_list->TotalRecs > 0 && (!EW_AUTO_HIDE_PAGE_SIZE_SELECTOR || $t_daftarm_list->Pager->Visible)) { ?>
<div class="ewPager">
<input type="hidden" name="t" value="t_daftarm">
<select name="<?php echo EW_TABLE_REC_PER_PAGE ?>" class="form-control input-sm ewTooltip" title="<?php echo $Language->Phrase("RecordsPerPage") ?>" onchange="this.form.submit();">
<option value="10"<?php if ($t_daftarm_list->DisplayRecs == 10) { ?> selected<?php } ?>>10</option>
<option value="20"<?php if ($t_daftarm_list->DisplayRecs == 20) { ?> selected<?php } ?>>20</option>
<option value="50"<?php if ($t_daftarm_list->DisplayRecs == 50) { ?> selected<?php } ?>>50</option>
<option value="100"<?php if ($t_daftarm_list->DisplayRecs == 100) { ?> selected<?php } ?>>100</option>
<option value="200"<?php if ($t_daftarm_list->DisplayRecs == 200) { ?> selected<?php } ?>>200</option>
<option value="ALL"<?php if ($t_daftarm->getRecordsPerPage() == -1) { ?> selected<?php } ?>><?php echo $Language->Phrase("AllRecords") ?></option>
</select>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($t_daftarm_list->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
<form name="ft_daftarmlist" id="ft_daftarmlist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($t_daftarm_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $t_daftarm_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="t_daftarm">
<div id="gmp_t_daftarm" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<?php if ($t_daftarm_list->TotalRecs > 0 || $t_daftarm->CurrentAction == "add" || $t_daftarm->CurrentAction == "copy" || $t_daftarm->CurrentAction == "gridedit") { ?>
<table id="tbl_t_daftarmlist" class="table ewTable">
<?php echo $t_daftarm->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$t_daftarm_list->RowType = EW_ROWTYPE_HEADER;

// Render list options
$t_daftarm_list->RenderListOptions();

// Render list options (header, left)
$t_daftarm_list->ListOptions->Render("header", "left");
?>
<?php if ($t_daftarm->_UserID->Visible) { // UserID ?>
	<?php if ($t_daftarm->SortUrl($t_daftarm->_UserID) == "") { ?>
		<th data-name="_UserID"><div id="elh_t_daftarm__UserID" class="t_daftarm__UserID"><div class="ewTableHeaderCaption"><?php echo $t_daftarm->_UserID->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_UserID"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $t_daftarm->SortUrl($t_daftarm->_UserID) ?>',2);"><div id="elh_t_daftarm__UserID" class="t_daftarm__UserID">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_daftarm->_UserID->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_daftarm->_UserID->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_daftarm->_UserID->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($t_daftarm->TglJam->Visible) { // TglJam ?>
	<?php if ($t_daftarm->SortUrl($t_daftarm->TglJam) == "") { ?>
		<th data-name="TglJam"><div id="elh_t_daftarm_TglJam" class="t_daftarm_TglJam"><div class="ewTableHeaderCaption"><?php echo $t_daftarm->TglJam->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="TglJam"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $t_daftarm->SortUrl($t_daftarm->TglJam) ?>',2);"><div id="elh_t_daftarm_TglJam" class="t_daftarm_TglJam">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_daftarm->TglJam->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_daftarm->TglJam->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_daftarm->TglJam->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($t_daftarm->BuktiBayar->Visible) { // BuktiBayar ?>
	<?php if ($t_daftarm->SortUrl($t_daftarm->BuktiBayar) == "") { ?>
		<th data-name="BuktiBayar"><div id="elh_t_daftarm_BuktiBayar" class="t_daftarm_BuktiBayar"><div class="ewTableHeaderCaption"><?php echo $t_daftarm->BuktiBayar->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="BuktiBayar"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $t_daftarm->SortUrl($t_daftarm->BuktiBayar) ?>',2);"><div id="elh_t_daftarm_BuktiBayar" class="t_daftarm_BuktiBayar">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_daftarm->BuktiBayar->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($t_daftarm->BuktiBayar->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_daftarm->BuktiBayar->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($t_daftarm->JumlahBayar->Visible) { // JumlahBayar ?>
	<?php if ($t_daftarm->SortUrl($t_daftarm->JumlahBayar) == "") { ?>
		<th data-name="JumlahBayar"><div id="elh_t_daftarm_JumlahBayar" class="t_daftarm_JumlahBayar"><div class="ewTableHeaderCaption"><?php echo $t_daftarm->JumlahBayar->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="JumlahBayar"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $t_daftarm->SortUrl($t_daftarm->JumlahBayar) ?>',2);"><div id="elh_t_daftarm_JumlahBayar" class="t_daftarm_JumlahBayar">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_daftarm->JumlahBayar->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_daftarm->JumlahBayar->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_daftarm->JumlahBayar->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($t_daftarm->Acc->Visible) { // Acc ?>
	<?php if ($t_daftarm->SortUrl($t_daftarm->Acc) == "") { ?>
		<th data-name="Acc"><div id="elh_t_daftarm_Acc" class="t_daftarm_Acc"><div class="ewTableHeaderCaption"><?php echo $t_daftarm->Acc->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Acc"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $t_daftarm->SortUrl($t_daftarm->Acc) ?>',2);"><div id="elh_t_daftarm_Acc" class="t_daftarm_Acc">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $t_daftarm->Acc->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($t_daftarm->Acc->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($t_daftarm->Acc->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$t_daftarm_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
	if ($t_daftarm->CurrentAction == "add" || $t_daftarm->CurrentAction == "copy") {
		$t_daftarm_list->RowIndex = 0;
		$t_daftarm_list->KeyCount = $t_daftarm_list->RowIndex;
		if ($t_daftarm->CurrentAction == "copy" && !$t_daftarm_list->LoadRow())
				$t_daftarm->CurrentAction = "add";
		if ($t_daftarm->CurrentAction == "add")
			$t_daftarm_list->LoadDefaultValues();
		if ($t_daftarm->EventCancelled) // Insert failed
			$t_daftarm_list->RestoreFormValues(); // Restore form values

		// Set row properties
		$t_daftarm->ResetAttrs();
		$t_daftarm->RowAttrs = array_merge($t_daftarm->RowAttrs, array('data-rowindex'=>0, 'id'=>'r0_t_daftarm', 'data-rowtype'=>EW_ROWTYPE_ADD));
		$t_daftarm->RowType = EW_ROWTYPE_ADD;

		// Render row
		$t_daftarm_list->RenderRow();

		// Render list options
		$t_daftarm_list->RenderListOptions();
		$t_daftarm_list->StartRowCnt = 0;
?>
	<tr<?php echo $t_daftarm->RowAttributes() ?>>
<?php

// Render list options (body, left)
$t_daftarm_list->ListOptions->Render("body", "left", $t_daftarm_list->RowCnt);
?>
	<?php if ($t_daftarm->_UserID->Visible) { // UserID ?>
		<td data-name="_UserID">
<span id="el<?php echo $t_daftarm_list->RowCnt ?>_t_daftarm__UserID" class="form-group t_daftarm__UserID">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x<?php echo $t_daftarm_list->RowIndex ?>__UserID"><?php echo (strval($t_daftarm->_UserID->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $t_daftarm->_UserID->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($t_daftarm->_UserID->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $t_daftarm_list->RowIndex ?>__UserID',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="t_daftarm" data-field="x__UserID" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $t_daftarm->_UserID->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $t_daftarm_list->RowIndex ?>__UserID" id="x<?php echo $t_daftarm_list->RowIndex ?>__UserID" value="<?php echo $t_daftarm->_UserID->CurrentValue ?>"<?php echo $t_daftarm->_UserID->EditAttributes() ?>>
<input type="hidden" name="s_x<?php echo $t_daftarm_list->RowIndex ?>__UserID" id="s_x<?php echo $t_daftarm_list->RowIndex ?>__UserID" value="<?php echo $t_daftarm->_UserID->LookupFilterQuery() ?>">
</span>
<input type="hidden" data-table="t_daftarm" data-field="x__UserID" name="o<?php echo $t_daftarm_list->RowIndex ?>__UserID" id="o<?php echo $t_daftarm_list->RowIndex ?>__UserID" value="<?php echo ew_HtmlEncode($t_daftarm->_UserID->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($t_daftarm->TglJam->Visible) { // TglJam ?>
		<td data-name="TglJam">
<span id="el<?php echo $t_daftarm_list->RowCnt ?>_t_daftarm_TglJam" class="form-group t_daftarm_TglJam">
<input type="text" data-table="t_daftarm" data-field="x_TglJam" name="x<?php echo $t_daftarm_list->RowIndex ?>_TglJam" id="x<?php echo $t_daftarm_list->RowIndex ?>_TglJam" placeholder="<?php echo ew_HtmlEncode($t_daftarm->TglJam->getPlaceHolder()) ?>" value="<?php echo $t_daftarm->TglJam->EditValue ?>"<?php echo $t_daftarm->TglJam->EditAttributes() ?>>
<?php if (!$t_daftarm->TglJam->ReadOnly && !$t_daftarm->TglJam->Disabled && !isset($t_daftarm->TglJam->EditAttrs["readonly"]) && !isset($t_daftarm->TglJam->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("ft_daftarmlist", "x<?php echo $t_daftarm_list->RowIndex ?>_TglJam", 0);
</script>
<?php } ?>
</span>
<input type="hidden" data-table="t_daftarm" data-field="x_TglJam" name="o<?php echo $t_daftarm_list->RowIndex ?>_TglJam" id="o<?php echo $t_daftarm_list->RowIndex ?>_TglJam" value="<?php echo ew_HtmlEncode($t_daftarm->TglJam->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($t_daftarm->BuktiBayar->Visible) { // BuktiBayar ?>
		<td data-name="BuktiBayar">
<span id="el<?php echo $t_daftarm_list->RowCnt ?>_t_daftarm_BuktiBayar" class="form-group t_daftarm_BuktiBayar">
<div id="fd_x<?php echo $t_daftarm_list->RowIndex ?>_BuktiBayar">
<span title="<?php echo $t_daftarm->BuktiBayar->FldTitle() ? $t_daftarm->BuktiBayar->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($t_daftarm->BuktiBayar->ReadOnly || $t_daftarm->BuktiBayar->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="t_daftarm" data-field="x_BuktiBayar" name="x<?php echo $t_daftarm_list->RowIndex ?>_BuktiBayar" id="x<?php echo $t_daftarm_list->RowIndex ?>_BuktiBayar"<?php echo $t_daftarm->BuktiBayar->EditAttributes() ?>>
</span>
<input type="hidden" name="fn_x<?php echo $t_daftarm_list->RowIndex ?>_BuktiBayar" id= "fn_x<?php echo $t_daftarm_list->RowIndex ?>_BuktiBayar" value="<?php echo $t_daftarm->BuktiBayar->Upload->FileName ?>">
<input type="hidden" name="fa_x<?php echo $t_daftarm_list->RowIndex ?>_BuktiBayar" id= "fa_x<?php echo $t_daftarm_list->RowIndex ?>_BuktiBayar" value="0">
<input type="hidden" name="fs_x<?php echo $t_daftarm_list->RowIndex ?>_BuktiBayar" id= "fs_x<?php echo $t_daftarm_list->RowIndex ?>_BuktiBayar" value="255">
<input type="hidden" name="fx_x<?php echo $t_daftarm_list->RowIndex ?>_BuktiBayar" id= "fx_x<?php echo $t_daftarm_list->RowIndex ?>_BuktiBayar" value="<?php echo $t_daftarm->BuktiBayar->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x<?php echo $t_daftarm_list->RowIndex ?>_BuktiBayar" id= "fm_x<?php echo $t_daftarm_list->RowIndex ?>_BuktiBayar" value="<?php echo $t_daftarm->BuktiBayar->UploadMaxFileSize ?>">
</div>
<table id="ft_x<?php echo $t_daftarm_list->RowIndex ?>_BuktiBayar" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<input type="hidden" data-table="t_daftarm" data-field="x_BuktiBayar" name="o<?php echo $t_daftarm_list->RowIndex ?>_BuktiBayar" id="o<?php echo $t_daftarm_list->RowIndex ?>_BuktiBayar" value="<?php echo ew_HtmlEncode($t_daftarm->BuktiBayar->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($t_daftarm->JumlahBayar->Visible) { // JumlahBayar ?>
		<td data-name="JumlahBayar">
<span id="el<?php echo $t_daftarm_list->RowCnt ?>_t_daftarm_JumlahBayar" class="form-group t_daftarm_JumlahBayar">
<input type="text" data-table="t_daftarm" data-field="x_JumlahBayar" name="x<?php echo $t_daftarm_list->RowIndex ?>_JumlahBayar" id="x<?php echo $t_daftarm_list->RowIndex ?>_JumlahBayar" size="30" placeholder="<?php echo ew_HtmlEncode($t_daftarm->JumlahBayar->getPlaceHolder()) ?>" value="<?php echo $t_daftarm->JumlahBayar->EditValue ?>"<?php echo $t_daftarm->JumlahBayar->EditAttributes() ?>>
</span>
<input type="hidden" data-table="t_daftarm" data-field="x_JumlahBayar" name="o<?php echo $t_daftarm_list->RowIndex ?>_JumlahBayar" id="o<?php echo $t_daftarm_list->RowIndex ?>_JumlahBayar" value="<?php echo ew_HtmlEncode($t_daftarm->JumlahBayar->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($t_daftarm->Acc->Visible) { // Acc ?>
		<td data-name="Acc">
<span id="el<?php echo $t_daftarm_list->RowCnt ?>_t_daftarm_Acc" class="form-group t_daftarm_Acc">
<div id="tp_x<?php echo $t_daftarm_list->RowIndex ?>_Acc" class="ewTemplate"><input type="radio" data-table="t_daftarm" data-field="x_Acc" data-value-separator="<?php echo $t_daftarm->Acc->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $t_daftarm_list->RowIndex ?>_Acc" id="x<?php echo $t_daftarm_list->RowIndex ?>_Acc" value="{value}"<?php echo $t_daftarm->Acc->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $t_daftarm_list->RowIndex ?>_Acc" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $t_daftarm->Acc->RadioButtonListHtml(FALSE, "x{$t_daftarm_list->RowIndex}_Acc") ?>
</div></div>
</span>
<input type="hidden" data-table="t_daftarm" data-field="x_Acc" name="o<?php echo $t_daftarm_list->RowIndex ?>_Acc" id="o<?php echo $t_daftarm_list->RowIndex ?>_Acc" value="<?php echo ew_HtmlEncode($t_daftarm->Acc->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$t_daftarm_list->ListOptions->Render("body", "right", $t_daftarm_list->RowCnt);
?>
<script type="text/javascript">
ft_daftarmlist.UpdateOpts(<?php echo $t_daftarm_list->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
<?php
if ($t_daftarm->ExportAll && $t_daftarm->Export <> "") {
	$t_daftarm_list->StopRec = $t_daftarm_list->TotalRecs;
} else {

	// Set the last record to display
	if ($t_daftarm_list->TotalRecs > $t_daftarm_list->StartRec + $t_daftarm_list->DisplayRecs - 1)
		$t_daftarm_list->StopRec = $t_daftarm_list->StartRec + $t_daftarm_list->DisplayRecs - 1;
	else
		$t_daftarm_list->StopRec = $t_daftarm_list->TotalRecs;
}

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($t_daftarm_list->FormKeyCountName) && ($t_daftarm->CurrentAction == "gridadd" || $t_daftarm->CurrentAction == "gridedit" || $t_daftarm->CurrentAction == "F")) {
		$t_daftarm_list->KeyCount = $objForm->GetValue($t_daftarm_list->FormKeyCountName);
		$t_daftarm_list->StopRec = $t_daftarm_list->StartRec + $t_daftarm_list->KeyCount - 1;
	}
}
$t_daftarm_list->RecCnt = $t_daftarm_list->StartRec - 1;
if ($t_daftarm_list->Recordset && !$t_daftarm_list->Recordset->EOF) {
	$t_daftarm_list->Recordset->MoveFirst();
	$bSelectLimit = $t_daftarm_list->UseSelectLimit;
	if (!$bSelectLimit && $t_daftarm_list->StartRec > 1)
		$t_daftarm_list->Recordset->Move($t_daftarm_list->StartRec - 1);
} elseif (!$t_daftarm->AllowAddDeleteRow && $t_daftarm_list->StopRec == 0) {
	$t_daftarm_list->StopRec = $t_daftarm->GridAddRowCount;
}

// Initialize aggregate
$t_daftarm->RowType = EW_ROWTYPE_AGGREGATEINIT;
$t_daftarm->ResetAttrs();
$t_daftarm_list->RenderRow();
$t_daftarm_list->EditRowCnt = 0;
if ($t_daftarm->CurrentAction == "edit")
	$t_daftarm_list->RowIndex = 1;
if ($t_daftarm->CurrentAction == "gridadd")
	$t_daftarm_list->RowIndex = 0;
if ($t_daftarm->CurrentAction == "gridedit")
	$t_daftarm_list->RowIndex = 0;
while ($t_daftarm_list->RecCnt < $t_daftarm_list->StopRec) {
	$t_daftarm_list->RecCnt++;
	if (intval($t_daftarm_list->RecCnt) >= intval($t_daftarm_list->StartRec)) {
		$t_daftarm_list->RowCnt++;
		if ($t_daftarm->CurrentAction == "gridadd" || $t_daftarm->CurrentAction == "gridedit" || $t_daftarm->CurrentAction == "F") {
			$t_daftarm_list->RowIndex++;
			$objForm->Index = $t_daftarm_list->RowIndex;
			if ($objForm->HasValue($t_daftarm_list->FormActionName))
				$t_daftarm_list->RowAction = strval($objForm->GetValue($t_daftarm_list->FormActionName));
			elseif ($t_daftarm->CurrentAction == "gridadd")
				$t_daftarm_list->RowAction = "insert";
			else
				$t_daftarm_list->RowAction = "";
		}

		// Set up key count
		$t_daftarm_list->KeyCount = $t_daftarm_list->RowIndex;

		// Init row class and style
		$t_daftarm->ResetAttrs();
		$t_daftarm->CssClass = "";
		if ($t_daftarm->CurrentAction == "gridadd") {
			$t_daftarm_list->LoadDefaultValues(); // Load default values
		} else {
			$t_daftarm_list->LoadRowValues($t_daftarm_list->Recordset); // Load row values
		}
		$t_daftarm->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($t_daftarm->CurrentAction == "gridadd") // Grid add
			$t_daftarm->RowType = EW_ROWTYPE_ADD; // Render add
		if ($t_daftarm->CurrentAction == "gridadd" && $t_daftarm->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$t_daftarm_list->RestoreCurrentRowFormValues($t_daftarm_list->RowIndex); // Restore form values
		if ($t_daftarm->CurrentAction == "edit") {
			if ($t_daftarm_list->CheckInlineEditKey() && $t_daftarm_list->EditRowCnt == 0) { // Inline edit
				$t_daftarm->RowType = EW_ROWTYPE_EDIT; // Render edit
			}
		}
		if ($t_daftarm->CurrentAction == "gridedit") { // Grid edit
			if ($t_daftarm->EventCancelled) {
				$t_daftarm_list->RestoreCurrentRowFormValues($t_daftarm_list->RowIndex); // Restore form values
			}
			if ($t_daftarm_list->RowAction == "insert")
				$t_daftarm->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$t_daftarm->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($t_daftarm->CurrentAction == "edit" && $t_daftarm->RowType == EW_ROWTYPE_EDIT && $t_daftarm->EventCancelled) { // Update failed
			$objForm->Index = 1;
			$t_daftarm_list->RestoreFormValues(); // Restore form values
		}
		if ($t_daftarm->CurrentAction == "gridedit" && ($t_daftarm->RowType == EW_ROWTYPE_EDIT || $t_daftarm->RowType == EW_ROWTYPE_ADD) && $t_daftarm->EventCancelled) // Update failed
			$t_daftarm_list->RestoreCurrentRowFormValues($t_daftarm_list->RowIndex); // Restore form values
		if ($t_daftarm->RowType == EW_ROWTYPE_EDIT) // Edit row
			$t_daftarm_list->EditRowCnt++;

		// Set up row id / data-rowindex
		$t_daftarm->RowAttrs = array_merge($t_daftarm->RowAttrs, array('data-rowindex'=>$t_daftarm_list->RowCnt, 'id'=>'r' . $t_daftarm_list->RowCnt . '_t_daftarm', 'data-rowtype'=>$t_daftarm->RowType));

		// Render row
		$t_daftarm_list->RenderRow();

		// Render list options
		$t_daftarm_list->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($t_daftarm_list->RowAction <> "delete" && $t_daftarm_list->RowAction <> "insertdelete" && !($t_daftarm_list->RowAction == "insert" && $t_daftarm->CurrentAction == "F" && $t_daftarm_list->EmptyRow())) {
?>
	<tr<?php echo $t_daftarm->RowAttributes() ?>>
<?php

// Render list options (body, left)
$t_daftarm_list->ListOptions->Render("body", "left", $t_daftarm_list->RowCnt);
?>
	<?php if ($t_daftarm->_UserID->Visible) { // UserID ?>
		<td data-name="_UserID"<?php echo $t_daftarm->_UserID->CellAttributes() ?>>
<?php if ($t_daftarm->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $t_daftarm_list->RowCnt ?>_t_daftarm__UserID" class="form-group t_daftarm__UserID">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x<?php echo $t_daftarm_list->RowIndex ?>__UserID"><?php echo (strval($t_daftarm->_UserID->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $t_daftarm->_UserID->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($t_daftarm->_UserID->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $t_daftarm_list->RowIndex ?>__UserID',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="t_daftarm" data-field="x__UserID" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $t_daftarm->_UserID->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $t_daftarm_list->RowIndex ?>__UserID" id="x<?php echo $t_daftarm_list->RowIndex ?>__UserID" value="<?php echo $t_daftarm->_UserID->CurrentValue ?>"<?php echo $t_daftarm->_UserID->EditAttributes() ?>>
<input type="hidden" name="s_x<?php echo $t_daftarm_list->RowIndex ?>__UserID" id="s_x<?php echo $t_daftarm_list->RowIndex ?>__UserID" value="<?php echo $t_daftarm->_UserID->LookupFilterQuery() ?>">
</span>
<input type="hidden" data-table="t_daftarm" data-field="x__UserID" name="o<?php echo $t_daftarm_list->RowIndex ?>__UserID" id="o<?php echo $t_daftarm_list->RowIndex ?>__UserID" value="<?php echo ew_HtmlEncode($t_daftarm->_UserID->OldValue) ?>">
<?php } ?>
<?php if ($t_daftarm->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_daftarm_list->RowCnt ?>_t_daftarm__UserID" class="form-group t_daftarm__UserID">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x<?php echo $t_daftarm_list->RowIndex ?>__UserID"><?php echo (strval($t_daftarm->_UserID->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $t_daftarm->_UserID->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($t_daftarm->_UserID->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $t_daftarm_list->RowIndex ?>__UserID',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="t_daftarm" data-field="x__UserID" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $t_daftarm->_UserID->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $t_daftarm_list->RowIndex ?>__UserID" id="x<?php echo $t_daftarm_list->RowIndex ?>__UserID" value="<?php echo $t_daftarm->_UserID->CurrentValue ?>"<?php echo $t_daftarm->_UserID->EditAttributes() ?>>
<input type="hidden" name="s_x<?php echo $t_daftarm_list->RowIndex ?>__UserID" id="s_x<?php echo $t_daftarm_list->RowIndex ?>__UserID" value="<?php echo $t_daftarm->_UserID->LookupFilterQuery() ?>">
</span>
<?php } ?>
<?php if ($t_daftarm->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_daftarm_list->RowCnt ?>_t_daftarm__UserID" class="t_daftarm__UserID">
<span<?php echo $t_daftarm->_UserID->ViewAttributes() ?>>
<?php echo $t_daftarm->_UserID->ListViewValue() ?></span>
</span>
<?php } ?>
<a id="<?php echo $t_daftarm_list->PageObjName . "_row_" . $t_daftarm_list->RowCnt ?>"></a></td>
	<?php } ?>
<?php if ($t_daftarm->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-table="t_daftarm" data-field="x_DaftarmID" name="x<?php echo $t_daftarm_list->RowIndex ?>_DaftarmID" id="x<?php echo $t_daftarm_list->RowIndex ?>_DaftarmID" value="<?php echo ew_HtmlEncode($t_daftarm->DaftarmID->CurrentValue) ?>">
<input type="hidden" data-table="t_daftarm" data-field="x_DaftarmID" name="o<?php echo $t_daftarm_list->RowIndex ?>_DaftarmID" id="o<?php echo $t_daftarm_list->RowIndex ?>_DaftarmID" value="<?php echo ew_HtmlEncode($t_daftarm->DaftarmID->OldValue) ?>">
<?php } ?>
<?php if ($t_daftarm->RowType == EW_ROWTYPE_EDIT || $t_daftarm->CurrentMode == "edit") { ?>
<input type="hidden" data-table="t_daftarm" data-field="x_DaftarmID" name="x<?php echo $t_daftarm_list->RowIndex ?>_DaftarmID" id="x<?php echo $t_daftarm_list->RowIndex ?>_DaftarmID" value="<?php echo ew_HtmlEncode($t_daftarm->DaftarmID->CurrentValue) ?>">
<?php } ?>
	<?php if ($t_daftarm->TglJam->Visible) { // TglJam ?>
		<td data-name="TglJam"<?php echo $t_daftarm->TglJam->CellAttributes() ?>>
<?php if ($t_daftarm->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $t_daftarm_list->RowCnt ?>_t_daftarm_TglJam" class="form-group t_daftarm_TglJam">
<input type="text" data-table="t_daftarm" data-field="x_TglJam" name="x<?php echo $t_daftarm_list->RowIndex ?>_TglJam" id="x<?php echo $t_daftarm_list->RowIndex ?>_TglJam" placeholder="<?php echo ew_HtmlEncode($t_daftarm->TglJam->getPlaceHolder()) ?>" value="<?php echo $t_daftarm->TglJam->EditValue ?>"<?php echo $t_daftarm->TglJam->EditAttributes() ?>>
<?php if (!$t_daftarm->TglJam->ReadOnly && !$t_daftarm->TglJam->Disabled && !isset($t_daftarm->TglJam->EditAttrs["readonly"]) && !isset($t_daftarm->TglJam->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("ft_daftarmlist", "x<?php echo $t_daftarm_list->RowIndex ?>_TglJam", 0);
</script>
<?php } ?>
</span>
<input type="hidden" data-table="t_daftarm" data-field="x_TglJam" name="o<?php echo $t_daftarm_list->RowIndex ?>_TglJam" id="o<?php echo $t_daftarm_list->RowIndex ?>_TglJam" value="<?php echo ew_HtmlEncode($t_daftarm->TglJam->OldValue) ?>">
<?php } ?>
<?php if ($t_daftarm->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_daftarm_list->RowCnt ?>_t_daftarm_TglJam" class="form-group t_daftarm_TglJam">
<input type="text" data-table="t_daftarm" data-field="x_TglJam" name="x<?php echo $t_daftarm_list->RowIndex ?>_TglJam" id="x<?php echo $t_daftarm_list->RowIndex ?>_TglJam" placeholder="<?php echo ew_HtmlEncode($t_daftarm->TglJam->getPlaceHolder()) ?>" value="<?php echo $t_daftarm->TglJam->EditValue ?>"<?php echo $t_daftarm->TglJam->EditAttributes() ?>>
<?php if (!$t_daftarm->TglJam->ReadOnly && !$t_daftarm->TglJam->Disabled && !isset($t_daftarm->TglJam->EditAttrs["readonly"]) && !isset($t_daftarm->TglJam->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("ft_daftarmlist", "x<?php echo $t_daftarm_list->RowIndex ?>_TglJam", 0);
</script>
<?php } ?>
</span>
<?php } ?>
<?php if ($t_daftarm->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_daftarm_list->RowCnt ?>_t_daftarm_TglJam" class="t_daftarm_TglJam">
<span<?php echo $t_daftarm->TglJam->ViewAttributes() ?>>
<?php echo $t_daftarm->TglJam->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($t_daftarm->BuktiBayar->Visible) { // BuktiBayar ?>
		<td data-name="BuktiBayar"<?php echo $t_daftarm->BuktiBayar->CellAttributes() ?>>
<?php if ($t_daftarm->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $t_daftarm_list->RowCnt ?>_t_daftarm_BuktiBayar" class="form-group t_daftarm_BuktiBayar">
<div id="fd_x<?php echo $t_daftarm_list->RowIndex ?>_BuktiBayar">
<span title="<?php echo $t_daftarm->BuktiBayar->FldTitle() ? $t_daftarm->BuktiBayar->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($t_daftarm->BuktiBayar->ReadOnly || $t_daftarm->BuktiBayar->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="t_daftarm" data-field="x_BuktiBayar" name="x<?php echo $t_daftarm_list->RowIndex ?>_BuktiBayar" id="x<?php echo $t_daftarm_list->RowIndex ?>_BuktiBayar"<?php echo $t_daftarm->BuktiBayar->EditAttributes() ?>>
</span>
<input type="hidden" name="fn_x<?php echo $t_daftarm_list->RowIndex ?>_BuktiBayar" id= "fn_x<?php echo $t_daftarm_list->RowIndex ?>_BuktiBayar" value="<?php echo $t_daftarm->BuktiBayar->Upload->FileName ?>">
<input type="hidden" name="fa_x<?php echo $t_daftarm_list->RowIndex ?>_BuktiBayar" id= "fa_x<?php echo $t_daftarm_list->RowIndex ?>_BuktiBayar" value="0">
<input type="hidden" name="fs_x<?php echo $t_daftarm_list->RowIndex ?>_BuktiBayar" id= "fs_x<?php echo $t_daftarm_list->RowIndex ?>_BuktiBayar" value="255">
<input type="hidden" name="fx_x<?php echo $t_daftarm_list->RowIndex ?>_BuktiBayar" id= "fx_x<?php echo $t_daftarm_list->RowIndex ?>_BuktiBayar" value="<?php echo $t_daftarm->BuktiBayar->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x<?php echo $t_daftarm_list->RowIndex ?>_BuktiBayar" id= "fm_x<?php echo $t_daftarm_list->RowIndex ?>_BuktiBayar" value="<?php echo $t_daftarm->BuktiBayar->UploadMaxFileSize ?>">
</div>
<table id="ft_x<?php echo $t_daftarm_list->RowIndex ?>_BuktiBayar" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<input type="hidden" data-table="t_daftarm" data-field="x_BuktiBayar" name="o<?php echo $t_daftarm_list->RowIndex ?>_BuktiBayar" id="o<?php echo $t_daftarm_list->RowIndex ?>_BuktiBayar" value="<?php echo ew_HtmlEncode($t_daftarm->BuktiBayar->OldValue) ?>">
<?php } ?>
<?php if ($t_daftarm->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_daftarm_list->RowCnt ?>_t_daftarm_BuktiBayar" class="form-group t_daftarm_BuktiBayar">
<div id="fd_x<?php echo $t_daftarm_list->RowIndex ?>_BuktiBayar">
<span title="<?php echo $t_daftarm->BuktiBayar->FldTitle() ? $t_daftarm->BuktiBayar->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($t_daftarm->BuktiBayar->ReadOnly || $t_daftarm->BuktiBayar->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="t_daftarm" data-field="x_BuktiBayar" name="x<?php echo $t_daftarm_list->RowIndex ?>_BuktiBayar" id="x<?php echo $t_daftarm_list->RowIndex ?>_BuktiBayar"<?php echo $t_daftarm->BuktiBayar->EditAttributes() ?>>
</span>
<input type="hidden" name="fn_x<?php echo $t_daftarm_list->RowIndex ?>_BuktiBayar" id= "fn_x<?php echo $t_daftarm_list->RowIndex ?>_BuktiBayar" value="<?php echo $t_daftarm->BuktiBayar->Upload->FileName ?>">
<?php if (@$_POST["fa_x<?php echo $t_daftarm_list->RowIndex ?>_BuktiBayar"] == "0") { ?>
<input type="hidden" name="fa_x<?php echo $t_daftarm_list->RowIndex ?>_BuktiBayar" id= "fa_x<?php echo $t_daftarm_list->RowIndex ?>_BuktiBayar" value="0">
<?php } else { ?>
<input type="hidden" name="fa_x<?php echo $t_daftarm_list->RowIndex ?>_BuktiBayar" id= "fa_x<?php echo $t_daftarm_list->RowIndex ?>_BuktiBayar" value="1">
<?php } ?>
<input type="hidden" name="fs_x<?php echo $t_daftarm_list->RowIndex ?>_BuktiBayar" id= "fs_x<?php echo $t_daftarm_list->RowIndex ?>_BuktiBayar" value="255">
<input type="hidden" name="fx_x<?php echo $t_daftarm_list->RowIndex ?>_BuktiBayar" id= "fx_x<?php echo $t_daftarm_list->RowIndex ?>_BuktiBayar" value="<?php echo $t_daftarm->BuktiBayar->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x<?php echo $t_daftarm_list->RowIndex ?>_BuktiBayar" id= "fm_x<?php echo $t_daftarm_list->RowIndex ?>_BuktiBayar" value="<?php echo $t_daftarm->BuktiBayar->UploadMaxFileSize ?>">
</div>
<table id="ft_x<?php echo $t_daftarm_list->RowIndex ?>_BuktiBayar" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php } ?>
<?php if ($t_daftarm->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_daftarm_list->RowCnt ?>_t_daftarm_BuktiBayar" class="t_daftarm_BuktiBayar">
<span>
<?php echo ew_GetFileViewTag($t_daftarm->BuktiBayar, $t_daftarm->BuktiBayar->ListViewValue()) ?>
</span>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($t_daftarm->JumlahBayar->Visible) { // JumlahBayar ?>
		<td data-name="JumlahBayar"<?php echo $t_daftarm->JumlahBayar->CellAttributes() ?>>
<?php if ($t_daftarm->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $t_daftarm_list->RowCnt ?>_t_daftarm_JumlahBayar" class="form-group t_daftarm_JumlahBayar">
<input type="text" data-table="t_daftarm" data-field="x_JumlahBayar" name="x<?php echo $t_daftarm_list->RowIndex ?>_JumlahBayar" id="x<?php echo $t_daftarm_list->RowIndex ?>_JumlahBayar" size="30" placeholder="<?php echo ew_HtmlEncode($t_daftarm->JumlahBayar->getPlaceHolder()) ?>" value="<?php echo $t_daftarm->JumlahBayar->EditValue ?>"<?php echo $t_daftarm->JumlahBayar->EditAttributes() ?>>
</span>
<input type="hidden" data-table="t_daftarm" data-field="x_JumlahBayar" name="o<?php echo $t_daftarm_list->RowIndex ?>_JumlahBayar" id="o<?php echo $t_daftarm_list->RowIndex ?>_JumlahBayar" value="<?php echo ew_HtmlEncode($t_daftarm->JumlahBayar->OldValue) ?>">
<?php } ?>
<?php if ($t_daftarm->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_daftarm_list->RowCnt ?>_t_daftarm_JumlahBayar" class="form-group t_daftarm_JumlahBayar">
<input type="text" data-table="t_daftarm" data-field="x_JumlahBayar" name="x<?php echo $t_daftarm_list->RowIndex ?>_JumlahBayar" id="x<?php echo $t_daftarm_list->RowIndex ?>_JumlahBayar" size="30" placeholder="<?php echo ew_HtmlEncode($t_daftarm->JumlahBayar->getPlaceHolder()) ?>" value="<?php echo $t_daftarm->JumlahBayar->EditValue ?>"<?php echo $t_daftarm->JumlahBayar->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($t_daftarm->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_daftarm_list->RowCnt ?>_t_daftarm_JumlahBayar" class="t_daftarm_JumlahBayar">
<span<?php echo $t_daftarm->JumlahBayar->ViewAttributes() ?>>
<?php echo $t_daftarm->JumlahBayar->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($t_daftarm->Acc->Visible) { // Acc ?>
		<td data-name="Acc"<?php echo $t_daftarm->Acc->CellAttributes() ?>>
<?php if ($t_daftarm->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $t_daftarm_list->RowCnt ?>_t_daftarm_Acc" class="form-group t_daftarm_Acc">
<div id="tp_x<?php echo $t_daftarm_list->RowIndex ?>_Acc" class="ewTemplate"><input type="radio" data-table="t_daftarm" data-field="x_Acc" data-value-separator="<?php echo $t_daftarm->Acc->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $t_daftarm_list->RowIndex ?>_Acc" id="x<?php echo $t_daftarm_list->RowIndex ?>_Acc" value="{value}"<?php echo $t_daftarm->Acc->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $t_daftarm_list->RowIndex ?>_Acc" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $t_daftarm->Acc->RadioButtonListHtml(FALSE, "x{$t_daftarm_list->RowIndex}_Acc") ?>
</div></div>
</span>
<input type="hidden" data-table="t_daftarm" data-field="x_Acc" name="o<?php echo $t_daftarm_list->RowIndex ?>_Acc" id="o<?php echo $t_daftarm_list->RowIndex ?>_Acc" value="<?php echo ew_HtmlEncode($t_daftarm->Acc->OldValue) ?>">
<?php } ?>
<?php if ($t_daftarm->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $t_daftarm_list->RowCnt ?>_t_daftarm_Acc" class="form-group t_daftarm_Acc">
<div id="tp_x<?php echo $t_daftarm_list->RowIndex ?>_Acc" class="ewTemplate"><input type="radio" data-table="t_daftarm" data-field="x_Acc" data-value-separator="<?php echo $t_daftarm->Acc->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $t_daftarm_list->RowIndex ?>_Acc" id="x<?php echo $t_daftarm_list->RowIndex ?>_Acc" value="{value}"<?php echo $t_daftarm->Acc->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $t_daftarm_list->RowIndex ?>_Acc" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $t_daftarm->Acc->RadioButtonListHtml(FALSE, "x{$t_daftarm_list->RowIndex}_Acc") ?>
</div></div>
</span>
<?php } ?>
<?php if ($t_daftarm->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $t_daftarm_list->RowCnt ?>_t_daftarm_Acc" class="t_daftarm_Acc">
<span<?php echo $t_daftarm->Acc->ViewAttributes() ?>>
<?php echo $t_daftarm->Acc->ListViewValue() ?></span>
</span>
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$t_daftarm_list->ListOptions->Render("body", "right", $t_daftarm_list->RowCnt);
?>
	</tr>
<?php if ($t_daftarm->RowType == EW_ROWTYPE_ADD || $t_daftarm->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
ft_daftarmlist.UpdateOpts(<?php echo $t_daftarm_list->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($t_daftarm->CurrentAction <> "gridadd")
		if (!$t_daftarm_list->Recordset->EOF) $t_daftarm_list->Recordset->MoveNext();
}
?>
<?php
	if ($t_daftarm->CurrentAction == "gridadd" || $t_daftarm->CurrentAction == "gridedit") {
		$t_daftarm_list->RowIndex = '$rowindex$';
		$t_daftarm_list->LoadDefaultValues();

		// Set row properties
		$t_daftarm->ResetAttrs();
		$t_daftarm->RowAttrs = array_merge($t_daftarm->RowAttrs, array('data-rowindex'=>$t_daftarm_list->RowIndex, 'id'=>'r0_t_daftarm', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($t_daftarm->RowAttrs["class"], "ewTemplate");
		$t_daftarm->RowType = EW_ROWTYPE_ADD;

		// Render row
		$t_daftarm_list->RenderRow();

		// Render list options
		$t_daftarm_list->RenderListOptions();
		$t_daftarm_list->StartRowCnt = 0;
?>
	<tr<?php echo $t_daftarm->RowAttributes() ?>>
<?php

// Render list options (body, left)
$t_daftarm_list->ListOptions->Render("body", "left", $t_daftarm_list->RowIndex);
?>
	<?php if ($t_daftarm->_UserID->Visible) { // UserID ?>
		<td data-name="_UserID">
<span id="el$rowindex$_t_daftarm__UserID" class="form-group t_daftarm__UserID">
<span class="ewLookupList">
	<span onclick="jQuery(this).parent().next().click();" tabindex="-1" class="form-control ewLookupText" id="lu_x<?php echo $t_daftarm_list->RowIndex ?>__UserID"><?php echo (strval($t_daftarm->_UserID->ViewValue) == "" ? $Language->Phrase("PleaseSelect") : $t_daftarm->_UserID->ViewValue); ?></span>
</span>
<button type="button" title="<?php echo ew_HtmlEncode(str_replace("%s", ew_RemoveHtml($t_daftarm->_UserID->FldCaption()), $Language->Phrase("LookupLink", TRUE))) ?>" onclick="ew_ModalLookupShow({lnk:this,el:'x<?php echo $t_daftarm_list->RowIndex ?>__UserID',m:0,n:10});" class="ewLookupBtn btn btn-default btn-sm"><span class="glyphicon glyphicon-search ewIcon"></span></button>
<input type="hidden" data-table="t_daftarm" data-field="x__UserID" data-multiple="0" data-lookup="1" data-value-separator="<?php echo $t_daftarm->_UserID->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $t_daftarm_list->RowIndex ?>__UserID" id="x<?php echo $t_daftarm_list->RowIndex ?>__UserID" value="<?php echo $t_daftarm->_UserID->CurrentValue ?>"<?php echo $t_daftarm->_UserID->EditAttributes() ?>>
<input type="hidden" name="s_x<?php echo $t_daftarm_list->RowIndex ?>__UserID" id="s_x<?php echo $t_daftarm_list->RowIndex ?>__UserID" value="<?php echo $t_daftarm->_UserID->LookupFilterQuery() ?>">
</span>
<input type="hidden" data-table="t_daftarm" data-field="x__UserID" name="o<?php echo $t_daftarm_list->RowIndex ?>__UserID" id="o<?php echo $t_daftarm_list->RowIndex ?>__UserID" value="<?php echo ew_HtmlEncode($t_daftarm->_UserID->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($t_daftarm->TglJam->Visible) { // TglJam ?>
		<td data-name="TglJam">
<span id="el$rowindex$_t_daftarm_TglJam" class="form-group t_daftarm_TglJam">
<input type="text" data-table="t_daftarm" data-field="x_TglJam" name="x<?php echo $t_daftarm_list->RowIndex ?>_TglJam" id="x<?php echo $t_daftarm_list->RowIndex ?>_TglJam" placeholder="<?php echo ew_HtmlEncode($t_daftarm->TglJam->getPlaceHolder()) ?>" value="<?php echo $t_daftarm->TglJam->EditValue ?>"<?php echo $t_daftarm->TglJam->EditAttributes() ?>>
<?php if (!$t_daftarm->TglJam->ReadOnly && !$t_daftarm->TglJam->Disabled && !isset($t_daftarm->TglJam->EditAttrs["readonly"]) && !isset($t_daftarm->TglJam->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateCalendar("ft_daftarmlist", "x<?php echo $t_daftarm_list->RowIndex ?>_TglJam", 0);
</script>
<?php } ?>
</span>
<input type="hidden" data-table="t_daftarm" data-field="x_TglJam" name="o<?php echo $t_daftarm_list->RowIndex ?>_TglJam" id="o<?php echo $t_daftarm_list->RowIndex ?>_TglJam" value="<?php echo ew_HtmlEncode($t_daftarm->TglJam->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($t_daftarm->BuktiBayar->Visible) { // BuktiBayar ?>
		<td data-name="BuktiBayar">
<span id="el$rowindex$_t_daftarm_BuktiBayar" class="form-group t_daftarm_BuktiBayar">
<div id="fd_x<?php echo $t_daftarm_list->RowIndex ?>_BuktiBayar">
<span title="<?php echo $t_daftarm->BuktiBayar->FldTitle() ? $t_daftarm->BuktiBayar->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($t_daftarm->BuktiBayar->ReadOnly || $t_daftarm->BuktiBayar->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="t_daftarm" data-field="x_BuktiBayar" name="x<?php echo $t_daftarm_list->RowIndex ?>_BuktiBayar" id="x<?php echo $t_daftarm_list->RowIndex ?>_BuktiBayar"<?php echo $t_daftarm->BuktiBayar->EditAttributes() ?>>
</span>
<input type="hidden" name="fn_x<?php echo $t_daftarm_list->RowIndex ?>_BuktiBayar" id= "fn_x<?php echo $t_daftarm_list->RowIndex ?>_BuktiBayar" value="<?php echo $t_daftarm->BuktiBayar->Upload->FileName ?>">
<input type="hidden" name="fa_x<?php echo $t_daftarm_list->RowIndex ?>_BuktiBayar" id= "fa_x<?php echo $t_daftarm_list->RowIndex ?>_BuktiBayar" value="0">
<input type="hidden" name="fs_x<?php echo $t_daftarm_list->RowIndex ?>_BuktiBayar" id= "fs_x<?php echo $t_daftarm_list->RowIndex ?>_BuktiBayar" value="255">
<input type="hidden" name="fx_x<?php echo $t_daftarm_list->RowIndex ?>_BuktiBayar" id= "fx_x<?php echo $t_daftarm_list->RowIndex ?>_BuktiBayar" value="<?php echo $t_daftarm->BuktiBayar->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x<?php echo $t_daftarm_list->RowIndex ?>_BuktiBayar" id= "fm_x<?php echo $t_daftarm_list->RowIndex ?>_BuktiBayar" value="<?php echo $t_daftarm->BuktiBayar->UploadMaxFileSize ?>">
</div>
<table id="ft_x<?php echo $t_daftarm_list->RowIndex ?>_BuktiBayar" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<input type="hidden" data-table="t_daftarm" data-field="x_BuktiBayar" name="o<?php echo $t_daftarm_list->RowIndex ?>_BuktiBayar" id="o<?php echo $t_daftarm_list->RowIndex ?>_BuktiBayar" value="<?php echo ew_HtmlEncode($t_daftarm->BuktiBayar->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($t_daftarm->JumlahBayar->Visible) { // JumlahBayar ?>
		<td data-name="JumlahBayar">
<span id="el$rowindex$_t_daftarm_JumlahBayar" class="form-group t_daftarm_JumlahBayar">
<input type="text" data-table="t_daftarm" data-field="x_JumlahBayar" name="x<?php echo $t_daftarm_list->RowIndex ?>_JumlahBayar" id="x<?php echo $t_daftarm_list->RowIndex ?>_JumlahBayar" size="30" placeholder="<?php echo ew_HtmlEncode($t_daftarm->JumlahBayar->getPlaceHolder()) ?>" value="<?php echo $t_daftarm->JumlahBayar->EditValue ?>"<?php echo $t_daftarm->JumlahBayar->EditAttributes() ?>>
</span>
<input type="hidden" data-table="t_daftarm" data-field="x_JumlahBayar" name="o<?php echo $t_daftarm_list->RowIndex ?>_JumlahBayar" id="o<?php echo $t_daftarm_list->RowIndex ?>_JumlahBayar" value="<?php echo ew_HtmlEncode($t_daftarm->JumlahBayar->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($t_daftarm->Acc->Visible) { // Acc ?>
		<td data-name="Acc">
<span id="el$rowindex$_t_daftarm_Acc" class="form-group t_daftarm_Acc">
<div id="tp_x<?php echo $t_daftarm_list->RowIndex ?>_Acc" class="ewTemplate"><input type="radio" data-table="t_daftarm" data-field="x_Acc" data-value-separator="<?php echo $t_daftarm->Acc->DisplayValueSeparatorAttribute() ?>" name="x<?php echo $t_daftarm_list->RowIndex ?>_Acc" id="x<?php echo $t_daftarm_list->RowIndex ?>_Acc" value="{value}"<?php echo $t_daftarm->Acc->EditAttributes() ?>></div>
<div id="dsl_x<?php echo $t_daftarm_list->RowIndex ?>_Acc" data-repeatcolumn="5" class="ewItemList" style="display: none;"><div>
<?php echo $t_daftarm->Acc->RadioButtonListHtml(FALSE, "x{$t_daftarm_list->RowIndex}_Acc") ?>
</div></div>
</span>
<input type="hidden" data-table="t_daftarm" data-field="x_Acc" name="o<?php echo $t_daftarm_list->RowIndex ?>_Acc" id="o<?php echo $t_daftarm_list->RowIndex ?>_Acc" value="<?php echo ew_HtmlEncode($t_daftarm->Acc->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$t_daftarm_list->ListOptions->Render("body", "right", $t_daftarm_list->RowCnt);
?>
<script type="text/javascript">
ft_daftarmlist.UpdateOpts(<?php echo $t_daftarm_list->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($t_daftarm->CurrentAction == "add" || $t_daftarm->CurrentAction == "copy") { ?>
<input type="hidden" name="<?php echo $t_daftarm_list->FormKeyCountName ?>" id="<?php echo $t_daftarm_list->FormKeyCountName ?>" value="<?php echo $t_daftarm_list->KeyCount ?>">
<?php } ?>
<?php if ($t_daftarm->CurrentAction == "gridadd") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $t_daftarm_list->FormKeyCountName ?>" id="<?php echo $t_daftarm_list->FormKeyCountName ?>" value="<?php echo $t_daftarm_list->KeyCount ?>">
<?php echo $t_daftarm_list->MultiSelectKey ?>
<?php } ?>
<?php if ($t_daftarm->CurrentAction == "edit") { ?>
<input type="hidden" name="<?php echo $t_daftarm_list->FormKeyCountName ?>" id="<?php echo $t_daftarm_list->FormKeyCountName ?>" value="<?php echo $t_daftarm_list->KeyCount ?>">
<?php } ?>
<?php if ($t_daftarm->CurrentAction == "gridedit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $t_daftarm_list->FormKeyCountName ?>" id="<?php echo $t_daftarm_list->FormKeyCountName ?>" value="<?php echo $t_daftarm_list->KeyCount ?>">
<?php echo $t_daftarm_list->MultiSelectKey ?>
<?php } ?>
<?php if ($t_daftarm->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($t_daftarm_list->Recordset)
	$t_daftarm_list->Recordset->Close();
?>
<?php if ($t_daftarm->Export == "") { ?>
<div class="panel-footer ewGridLowerPanel">
<?php if ($t_daftarm->CurrentAction <> "gridadd" && $t_daftarm->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($t_daftarm_list->Pager)) $t_daftarm_list->Pager = new cPrevNextPager($t_daftarm_list->StartRec, $t_daftarm_list->DisplayRecs, $t_daftarm_list->TotalRecs) ?>
<?php if ($t_daftarm_list->Pager->RecordCount > 0 && $t_daftarm_list->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($t_daftarm_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $t_daftarm_list->PageUrl() ?>start=<?php echo $t_daftarm_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($t_daftarm_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $t_daftarm_list->PageUrl() ?>start=<?php echo $t_daftarm_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $t_daftarm_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($t_daftarm_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $t_daftarm_list->PageUrl() ?>start=<?php echo $t_daftarm_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($t_daftarm_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $t_daftarm_list->PageUrl() ?>start=<?php echo $t_daftarm_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $t_daftarm_list->Pager->PageCount ?></span>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $t_daftarm_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $t_daftarm_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $t_daftarm_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
<?php if ($t_daftarm_list->TotalRecs > 0 && (!EW_AUTO_HIDE_PAGE_SIZE_SELECTOR || $t_daftarm_list->Pager->Visible)) { ?>
<div class="ewPager">
<input type="hidden" name="t" value="t_daftarm">
<select name="<?php echo EW_TABLE_REC_PER_PAGE ?>" class="form-control input-sm ewTooltip" title="<?php echo $Language->Phrase("RecordsPerPage") ?>" onchange="this.form.submit();">
<option value="10"<?php if ($t_daftarm_list->DisplayRecs == 10) { ?> selected<?php } ?>>10</option>
<option value="20"<?php if ($t_daftarm_list->DisplayRecs == 20) { ?> selected<?php } ?>>20</option>
<option value="50"<?php if ($t_daftarm_list->DisplayRecs == 50) { ?> selected<?php } ?>>50</option>
<option value="100"<?php if ($t_daftarm_list->DisplayRecs == 100) { ?> selected<?php } ?>>100</option>
<option value="200"<?php if ($t_daftarm_list->DisplayRecs == 200) { ?> selected<?php } ?>>200</option>
<option value="ALL"<?php if ($t_daftarm->getRecordsPerPage() == -1) { ?> selected<?php } ?>><?php echo $Language->Phrase("AllRecords") ?></option>
</select>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($t_daftarm_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
</div>
<?php } ?>
<?php if ($t_daftarm_list->TotalRecs == 0 && $t_daftarm->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($t_daftarm_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($t_daftarm->Export == "") { ?>
<script type="text/javascript">
ft_daftarmlistsrch.FilterList = <?php echo $t_daftarm_list->GetFilterList() ?>;
ft_daftarmlistsrch.Init();
ft_daftarmlist.Init();
</script>
<?php } ?>
<?php
$t_daftarm_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($t_daftarm->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$t_daftarm_list->Page_Terminate();
?>
