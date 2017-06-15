<!-- Begin Main Menu -->
<?php

// Generate all menu items
$RootMenu->IsRoot = TRUE;
$RootMenu->AddMenuItem(5, "mmi_c_home_php", $Language->MenuPhrase("5", "MenuText"), "c_home.php", -1, "", AllowListMenu('{BDF410E2-F8A1-44BF-83FA-BA321C2274F7}c_home.php'), FALSE, TRUE);
$RootMenu->AddMenuItem(6, "mmi_t_daftarm", $Language->MenuPhrase("6", "MenuText"), "t_daftarmlist.php", -1, "", AllowListMenu('{BDF410E2-F8A1-44BF-83FA-BA321C2274F7}t_daftarm'), FALSE, FALSE);
$RootMenu->AddMenuItem(12, "mmci_Setup", $Language->MenuPhrase("12", "MenuText"), "", -1, "", TRUE, FALSE, TRUE);
$RootMenu->AddMenuItem(7, "mmi_t_praktikum", $Language->MenuPhrase("7", "MenuText"), "t_praktikumlist.php", 12, "", AllowListMenu('{BDF410E2-F8A1-44BF-83FA-BA321C2274F7}t_praktikum'), FALSE, FALSE);
$RootMenu->AddMenuItem(1, "mmi_t_user", $Language->MenuPhrase("1", "MenuText"), "t_userlist.php", 12, "", AllowListMenu('{BDF410E2-F8A1-44BF-83FA-BA321C2274F7}t_user'), FALSE, FALSE);
$RootMenu->AddMenuItem(4, "mmi_t_user_levels", $Language->MenuPhrase("4", "MenuText"), "t_user_levelslist.php", 12, "", (@$_SESSION[EW_SESSION_USER_LEVEL] & EW_ALLOW_ADMIN) == EW_ALLOW_ADMIN, FALSE, FALSE);
$RootMenu->AddMenuItem(13, "mmci_View", $Language->MenuPhrase("13", "MenuText"), "", -1, "", TRUE, FALSE, TRUE);
$RootMenu->AddMenuItem(2, "mmi_t_audit_trail", $Language->MenuPhrase("2", "MenuText"), "t_audit_traillist.php", 13, "", AllowListMenu('{BDF410E2-F8A1-44BF-83FA-BA321C2274F7}t_audit_trail'), FALSE, FALSE);
$RootMenu->AddMenuItem(-2, "mmi_changepwd", $Language->Phrase("ChangePwd"), "changepwd.php", -1, "", IsLoggedIn() && !IsSysAdmin());
$RootMenu->AddMenuItem(-1, "mmi_logout", $Language->Phrase("Logout"), "logout.php", -1, "", IsLoggedIn());
$RootMenu->AddMenuItem(-1, "mmi_login", $Language->Phrase("Login"), "login.php", -1, "", !IsLoggedIn() && substr(@$_SERVER["URL"], -1 * strlen("login.php")) <> "login.php");
$RootMenu->Render();
?>
<!-- End Main Menu -->
