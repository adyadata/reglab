<!-- Begin Main Menu -->
<?php $RootMenu = new cMenu(EW_MENUBAR_ID) ?>
<?php

// Generate all menu items
$RootMenu->IsRoot = TRUE;
$RootMenu->AddMenuItem(5, "mi_c_home_php", $Language->MenuPhrase("5", "MenuText"), "c_home.php", -1, "", AllowListMenu('{BDF410E2-F8A1-44BF-83FA-BA321C2274F7}c_home.php'), FALSE, TRUE);
$RootMenu->AddMenuItem(12, "mci_Setup", $Language->MenuPhrase("12", "MenuText"), "", -1, "", TRUE, FALSE, TRUE);
$RootMenu->AddMenuItem(7, "mi_t_praktikum", $Language->MenuPhrase("7", "MenuText"), "t_praktikumlist.php", 12, "", AllowListMenu('{BDF410E2-F8A1-44BF-83FA-BA321C2274F7}t_praktikum'), FALSE, FALSE);
$RootMenu->AddMenuItem(1, "mi_t_user", $Language->MenuPhrase("1", "MenuText"), "t_userlist.php", 12, "", AllowListMenu('{BDF410E2-F8A1-44BF-83FA-BA321C2274F7}t_user'), FALSE, FALSE);
$RootMenu->AddMenuItem(4, "mi_t_user_levels", $Language->MenuPhrase("4", "MenuText"), "t_user_levelslist.php", 12, "", (@$_SESSION[EW_SESSION_USER_LEVEL] & EW_ALLOW_ADMIN) == EW_ALLOW_ADMIN, FALSE, FALSE);
$RootMenu->AddMenuItem(14, "mci_Input", $Language->MenuPhrase("14", "MenuText"), "", -1, "", TRUE, FALSE, TRUE);
$RootMenu->AddMenuItem(6, "mi_t_daftarm", $Language->MenuPhrase("6", "MenuText"), "t_daftarmlist.php", 14, "", AllowListMenu('{BDF410E2-F8A1-44BF-83FA-BA321C2274F7}t_daftarm'), FALSE, FALSE);
$RootMenu->AddMenuItem(13, "mci_View", $Language->MenuPhrase("13", "MenuText"), "", -1, "", TRUE, FALSE, TRUE);
$RootMenu->AddMenuItem(2, "mi_t_audit_trail", $Language->MenuPhrase("2", "MenuText"), "t_audit_traillist.php", 13, "", AllowListMenu('{BDF410E2-F8A1-44BF-83FA-BA321C2274F7}t_audit_trail'), FALSE, FALSE);
$RootMenu->AddMenuItem(-2, "mi_changepwd", $Language->Phrase("ChangePwd"), "changepwd.php", -1, "", IsLoggedIn() && !IsSysAdmin());
$RootMenu->AddMenuItem(-1, "mi_logout", $Language->Phrase("Logout"), "logout.php", -1, "", IsLoggedIn());
$RootMenu->AddMenuItem(-1, "mi_login", $Language->Phrase("Login"), "login.php", -1, "", !IsLoggedIn() && substr(@$_SERVER["URL"], -1 * strlen("login.php")) <> "login.php");
$RootMenu->Render();
?>
<!-- End Main Menu -->
