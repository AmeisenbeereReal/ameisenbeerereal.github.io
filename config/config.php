<?php
////////////////////////////////////////////////
//             MySQL-Data                     //
//                                     	      //
// Hier trägst du deine MySQL daten ein.
// Sie müssen die selben sein, die du auch bei den MinecraftPlugins angegeben hast.
const DB_HOST = "mysql.gameserver.gamed.de";
const DB_DATABASE = "s1429438";
const DB_USERNAME = "s1429438";
const DB_PASSWORD = "ciowribaem";


//                                            //
////////////////////////////////////////////////

// Die ServerIP ist die IP mit der man normalerweise auf deinen Server joint
const SERVERIP = "mc.ameisenserver.de";
// Der Servername wird bspw. in der Navbar angezeigt
const SERVERNAME = "ameisenserver.de";

const PAGETITLE = "Webinterface - Ameisenserver";

// Hier muss deine Zahlen-IP rein
const SOCKET_IP = "144.91.99.52";
// Wenn du diesen Port änderst, musst du ihn auch in der Config deiner BungeeAPI-Cores ändern!
const SOCKET_PORT = "8081";


////////////////////////////////////////////////////
//                  MODULES                       //
//                                        	      //

//Aktiviere oder deaktiviere bestimmte Bereiche deines Controlpanels!

// Benötigt das BungeeSystem
const CLAN = true;
const FRIENDS = true;
const PUNISHMENT = true;
const SETTINGS = true;

// Zeige die Stats von verschiedenen Plugins an
const STATS = true; //Allgemeines de/aktivieren der Stats-Seite
const MLGRUSH = true; //Benötigt MLGRush
const BUILDFFA = true; //Benötigt BuildFFA
const BEDWARS = false; //Benötigt Bedwars von bote100 (MySQL Verbindun weiter unten angeben)
const SKYWARS = false; //Benötigt Skywars von bote100 (MySQL Verbindun weiter unten angeben)

// Benötigt das PermissionsSystem
const PERMISSIONS = true;

// Benätigt keins der Systeme
const UNBAN = true;
//
//                                               //
//////////////////////////////////////////////////
///
///
//Hier stellst du die Namen der Ränge innerhalb des Controlpnnels ein.
/// 100 wird immer beim erstellen eines Accounts vergeben, wenn man die Berechtigung 'bungee.webadmin' hat
///
const RANKS = array(
    -1=>"Gesperrt",
    0=>"Spieler",
    1=>"Team",
    10=>"Mod",
    99=>"Admin",
    100=>"Website-Administrator"
);


//Hier kannst du alle permissions einstellen. Du gibst hier den mindestens notwendigen Rang ein (siehe weiter oben)
const PERMS = array(
    "navbar_team" => 1, //In der Navbar den Bereich von Team sehen
    "usermanagement_view" => 1, //Nach Spielern suchen können und Informationen über diese einsehen
    "usermanagement_permissions_view" => 10, //Permissions von einem Spieler/einer Gruppe einsehen
    "usermanagement_permissions_manage" => 10, //Permissions von einem Spieler/einer Gruppe einstellen
    "usermanagement_punishment_view" => 1, //Vergangene und aktuelle Mutes und Bans eines Spielers einsehen
    "usermanagement_punishment_manage" => 10, //Spieler Bannen und/oder Muten
    "usermanagement_changerank" => 99, //Den Rang innerhalb des Webpanels eines Spielers ändern
    "server_settings" => 99, //Einstellungen des servers ändern (Mord, wartung, slots etc)
    "usermanagement_unbans" => 10, //Entbannungsanträge einsehen/schließen
);

//Gib hier deine MySQL Daten zu deiner BEDWARS (von bote100) Datenbank ein!
const BW_DB_HOST = "127.0.0.1:3306";
const BW_DB_DATABASE = "bedwars";
const BW_DB_USERNAME = "username";
const BW_DB_PASSWORD = "password";


//Gib hier deine MySQL Daten zu deiner SKYWARS (von bote100) Datenbank ein!
const SW_DB_HOST = "127.0.0.1:3306";
const SW_DB_DATABASE = "skywars";
const SW_DB_USERNAME = "username";
const SW_DB_PASSWORD = "password";
