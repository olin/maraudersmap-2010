<?
// We don't want caching!
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); 
header('Pragma: no-cache'); 

require('../lib_common.php');
require_once('usernames.php');

DBConnect();

$maxNum = 305;
$totalNum = 8630;
$heatmapBind["AC10,in,rm102"] = 11;
$heatmapBind["AC10,in,rm103"] = 1;
$heatmapBind["AC10,in,rm106"] = 1;
$heatmapBind["AC10,in,rm109"] = 45;
$heatmapBind["AC10,in,rm113"] = 21;
$heatmapBind["AC10,in,rm126"] = 121;
$heatmapBind["AC10,in,rm128"] = 17;
$heatmapBind["AC10,out,Machine Shop"] = 3;
$heatmapBind["AC10,out,middle"] = 10;
$heatmapBind["AC10,out,Outside Machine Shop"] = 2;
$heatmapBind["AC10,out,Outside of AC 1st floor North"] = 1;
$heatmapBind["AC10,out,The O"] = 12;
//$heatmapBind["AC20,in,Gill\\'s Lab"] = 1;
//$heatmapBind["AC20,in,Gill\\\\\\'s Lab"] = 1;
$heatmapBind["AC20,in,Gill\'s Lab"] = 2;
$heatmapBind["AC20,in,rm204"] = 150;
$heatmapBind["AC20,in,rm206"] = 148;
$heatmapBind["AC20,in,rm208"] = 4;
$heatmapBind["AC20,in,rm209"] = 140;
$heatmapBind["AC20,in,rm212"] = 6;
$heatmapBind["AC20,in,rm213"] = 95;
$heatmapBind["AC20,in,rm218"] = 60;
$heatmapBind["AC20,in,rm226 SCOPE Lab"] = 10;
$heatmapBind["AC20,in,rm228"] = 6;
$heatmapBind["AC20,out,hallway"] = 3;
$heatmapBind["AC20,out,rm202"] = 3;
$heatmapBind["AC20,out,rm203"] = 1;
$heatmapBind["AC20,out,rm213"] = 9;
$heatmapBind["AC20,out,rm219"] = 1;
$heatmapBind["AC20,out,rm229"] = 1;
$heatmapBind["AC25,in,north"] = 1;
$heatmapBind["AC30,in,08"] = 2;
$heatmapBind["AC30,in,rm302"] = 12;
$heatmapBind["AC30,in,rm304"] = 140;
$heatmapBind["AC30,in,rm306"] = 46;
$heatmapBind["AC30,in,rm308"] = 2;
$heatmapBind["AC30,in,rm309"] = 79;
$heatmapBind["AC30,in,rm313"] = 6;
$heatmapBind["AC30,in,rm314 (printer)"] = 4;
$heatmapBind["AC30,in,rm318"] = 36;
$heatmapBind["AC30,in,rm326"] = 53;
$heatmapBind["AC30,in,rm328"] = 158;
$heatmapBind["AC30,out, AC 318"] = 6;
$heatmapBind["AC30,out,AC302"] = 2;
$heatmapBind["AC30,out,middle"] = 1;
$heatmapBind["AC30,out,rm302"] = 5;
$heatmapBind["AC30,out,rm308"] = 1;
$heatmapBind["AC30,out,rm309"] = 16;
$heatmapBind["AC30,out,rm325"] = 1;
$heatmapBind["AC30,out,rm328"] = 2;
$heatmapBind["AC30,out,rm329"] = 1;
$heatmapBind["AC40,in,rm404"] = 4;
$heatmapBind["AC40,in,rm406"] = 3;
$heatmapBind["AC40,in,rm407"] = 2;
$heatmapBind["AC40,in,rm407(Joanne\\'soffice)"] = 1;
$heatmapBind["AC40,in,rm409"] = 14;
$heatmapBind["AC40,in,rm413"] = 115;
$heatmapBind["AC40,in,rm417"] = 53;
$heatmapBind["AC40,in,rm428"] = 9;
$heatmapBind["AC40,out,End"] = 7;
$heatmapBind["AC40,out,rm406"] = 1;
$heatmapBind["AC40,out,rm407"] = 3;
$heatmapBind["AC40,out,rm409"] = 6;
$heatmapBind["AC40,out,rm417"] = 6;
$heatmapBind["AC40,out,rm419"] = 9;
$heatmapBind["AC40,out,rm428"] = 13;
$heatmapBind["CC00,in,east_stairway"] = 3;
$heatmapBind["CC00,in,game_room"] = 3;
$heatmapBind["CC00,in,Mail Room"] = 5;
$heatmapBind["CC05,in,wooden_waterfall"] = 3;
$heatmapBind["CC10,in,computer bar"] = 43;
$heatmapBind["CC10,in,cookies"] = 27;
$heatmapBind["CC10,in,dininghall"] = 26;
$heatmapBind["CC10,in,pizza"] = 2;
$heatmapBind["CC10,in,tables"] = 54;
$heatmapBind["CC10,in,wooden_waterfall"] = 3;
$heatmapBind["CC10,out,middle"] = 4;
$heatmapBind["CC10,out,outside"] = 1;
$heatmapBind["CC10,out,The O"] = 1;
$heatmapBind["CC15,in,other_stairway"] = 1;
$heatmapBind["CC20,in,Board Room"] = 7;
$heatmapBind["CC20,in,Cresecentrm"] = 11;
$heatmapBind["CC20,in,Dining Hall Mezzanine"] = 25;
$heatmapBind["CC20,in,other_stairway"] = 1;
$heatmapBind["CC20,in,rm210"] = 21;
$heatmapBind["CC20,in,rm214"] = 4;
$heatmapBind["CC25,in,other_stairway"] = 1;
$heatmapBind["CC30,in,central area"] = 1;
$heatmapBind["CC30,in,central area hall"] = 5;
$heatmapBind["CC30,in,central area north"] = 1;
$heatmapBind["CC30,in,central area registrar"] = 1;
$heatmapBind["CC30,in,central stairway"] = 1;
$heatmapBind["CC30,in,rm310"] = 5;
$heatmapBind["EH10,in,111 Kitchen"] = 16;
$heatmapBind["EH10,in,121 Team"] = 20;
$heatmapBind["EH10,in,antelounge"] = 1;
$heatmapBind["EH10,in,lounge"] = 18;
$heatmapBind["EH10,in,rm101teamrm"] = 8;
$heatmapBind["EH10,in,rm106"] = 5;
$heatmapBind["EH10,in,rm106 Suite Lounge"] = 5;
$heatmapBind["EH10,in,rm106A"] = 2;
$heatmapBind["EH10,in,rm106F"] = 13;
$heatmapBind["EH10,in,rm106G"] = 1;
$heatmapBind["EH10,in,rm111"] = 1;
$heatmapBind["EH10,in,rm115"] = 59;
$heatmapBind["EH10,in,rm116 (Living Room)"] = 46;
$heatmapBind["EH10,in,rm117"] = 137;
$heatmapBind["EH10,in,rm119"] = 9;
$heatmapBind["EH10,in,rm120"] = 40;
$heatmapBind["EH10,in,rm121"] = 9;
$heatmapBind["EH10,in,rm122"] = 1;
$heatmapBind["EH10,in,rm126B"] = 4;
$heatmapBind["EH10,in,rm128 Lounge"] = 12;
$heatmapBind["EH10,in,rm128A"] = 7;
$heatmapBind["EH10,in,rm129 Lounge"] = 5;
$heatmapBind["EH10,in,rm129E"] = 13;
$heatmapBind["EH10,in,rm129F"] = 10;
$heatmapBind["EH10,out,eastwing"] = 3;
$heatmapBind["EH10,out,GreatLawn "] = 3;
$heatmapBind["EH10,out,northwing"] = 1;
$heatmapBind["EH10,out,Outside EH front doors."] = 6;
$heatmapBind["EH20,in,221teamrm"] = 14;
$heatmapBind["EH20,in,antelounge"] = 2;
$heatmapBind["EH20,in,lounge"] = 2;
$heatmapBind["EH20,in,rm201team"] = 5;
$heatmapBind["EH20,in,rm205"] = 10;
$heatmapBind["EH20,in,rm206 Lounge"] = 46;
$heatmapBind["EH20,in,rm206C"] = 2;
$heatmapBind["EH20,in,rm206F"] = 32;
$heatmapBind["EH20,in,rm206G"] = 43;
$heatmapBind["EH20,in,rm207"] = 15;
$heatmapBind["EH20,in,rm209"] = 13;
$heatmapBind["EH20,in,rm215"] = 4;
$heatmapBind["EH20,in,rm217"] = 1;
$heatmapBind["EH20,in,rm219"] = 2;
$heatmapBind["EH20,in,rm220"] = 10;
$heatmapBind["EH20,in,rm228 lounge"] = 4;
$heatmapBind["EH20,in,rm228A"] = 3;
$heatmapBind["EH20,in,rm228B"] = 15;
$heatmapBind["EH20,in,rm228F"] = 78;
$heatmapBind["EH20,out,eastwing"] = 1;
$heatmapBind["EH20,out,westwing"] = 4;
$heatmapBind["EH30,in,306 lounge"] = 4;
$heatmapBind["EH30,in,antelounge"] = 2;
$heatmapBind["EH30,in,lounge"] = 29;
$heatmapBind["EH30,in,rm301"] = 12;
$heatmapBind["EH30,in,rm303"] = 28;
$heatmapBind["EH30,in,rm305"] = 6;
$heatmapBind["EH30,in,rm306B"] = 7;
$heatmapBind["EH30,in,rm306C"] = 16;
$heatmapBind["EH30,in,rm306E"] = 29;
$heatmapBind["EH30,in,rm306F"] = 3;
$heatmapBind["EH30,in,rm306G"] = 2;
$heatmapBind["EH30,in,rm307"] = 53;
$heatmapBind["EH30,in,rm313"] = 12;
$heatmapBind["EH30,in,rm315"] = 36;
$heatmapBind["EH30,in,rm316"] = 39;
$heatmapBind["EH30,in,rm316a"] = 3;
$heatmapBind["EH30,in,rm316c"] = 56;
$heatmapBind["EH30,in,rm317"] = 222;
$heatmapBind["EH30,in,rm321"] = 5;
$heatmapBind["EH30,in,rm328"] = 5;
$heatmapBind["EH30,in,rm329 suite lounge"] = 5;
$heatmapBind["EH30,in,rm329A"] = 30;
$heatmapBind["EH30,in,rm329B"] = 98;
$heatmapBind["EH30,in,rm329E"] = 22;
$heatmapBind["EH30,in,rm3WestTeam"] = 3;
$heatmapBind["EH30,in,teamroom"] = 14;
$heatmapBind["EH30,in,WestTeamrm"] = 10;
$heatmapBind["EH30,out,eastwing"] = 3;
$heatmapBind["EH30,out,northwing"] = 2;
$heatmapBind["EH40,in,antelounge"] = 1;
$heatmapBind["EH40,in,Lounge"] = 5;
$heatmapBind["EH40,in,NorthTeamrm"] = 1;
$heatmapBind["EH40,in,rm401Teamrm"] = 7;
$heatmapBind["EH40,in,rm403"] = 9;
$heatmapBind["EH40,in,rm405"] = 6;
$heatmapBind["EH40,in,rm406A"] = 1;
$heatmapBind["EH40,in,rm406B"] = 4;
$heatmapBind["EH40,in,rm406D"] = 32;
$heatmapBind["EH40,in,rm406F"] = 30;
$heatmapBind["EH40,in,rm409"] = 12;
$heatmapBind["EH40,in,rm413"] = 41;
$heatmapBind["EH40,in,rm415"] = 35;
$heatmapBind["EH40,in,rm416"] = 87;
$heatmapBind["EH40,in,rm416C"] = 1;
$heatmapBind["EH40,in,rm416E"] = 7;
$heatmapBind["EH40,in,rm416F"] = 5;
$heatmapBind["EH40,in,rm417"] = 49;
$heatmapBind["EH40,in,rm418"] = 2;
$heatmapBind["EH40,in,rm420"] = 13;
$heatmapBind["EH40,in,rm421"] = 8;
$heatmapBind["EH40,in,rm422"] = 4;
$heatmapBind["EH40,in,rm428"] = 5;
$heatmapBind["EH40,in,rm428B"] = 2;
$heatmapBind["EH40,in,rm429 Suite"] = 4;
$heatmapBind["EH40,in,rm429F"] = 1;
$heatmapBind["EH40,in,rm4N Suite Lounge"] = 5;
$heatmapBind["EH40,in,teamroom"] = 7;
$heatmapBind["EH40,out,northwing"] = 1;
$heatmapBind["EH40,out,westwing"] = 1;
$heatmapBind["OC00,in,auditorium"] = 36;
$heatmapBind["OC00,in,auditorium_stage"] = 6;
$heatmapBind["OC00,in,Computer Lab"] = 27;
$heatmapBind["OC00,in,Library"] = 71;
$heatmapBind["OC00,in,Library Workroom"] = 6;
$heatmapBind["OC00,in,north_it"] = 4;
$heatmapBind["OC00,in,rm10 (IT Helpdesk)"] = 35;
$heatmapBind["OC00,out,north_it"] = 5;
$heatmapBind["OC10,in,Auditorium"] = 4;
$heatmapBind["OC10,in,library"] = 112;
$heatmapBind["OC10,in,rm110"] = 4;
$heatmapBind["OC10,out,admissions"] = 1;
$heatmapBind["OC10,out,library"] = 1;
$heatmapBind["OC10,out,Main stairway between OC"] = 1;
$heatmapBind["OC20,in,Faculty Lobby"] = 3;
$heatmapBind["OC20,in,Mezzanine"] = 5;
$heatmapBind["OC20,in,rm200"] = 1;
$heatmapBind["OC20,in,rm273"] = 2;
$heatmapBind["OC20,out,rm227"] = 1;
$heatmapBind["OC30,in,Faculty Lobby"] = 6;
$heatmapBind["OC30,in,rm300"] = 1;
$heatmapBind["OC30,in,rm321"] = 1;
$heatmapBind["OC30,in,rm325"] = 21;
$heatmapBind["OC30,in,rm332"] = 4;
$heatmapBind["OC30,in,rm335"] = 5;
$heatmapBind["OC30,in,rm350"] = 1;
$heatmapBind["OC30,in,rm357"] = 1;
$heatmapBind["OC30,in,rm373"] = 3;
$heatmapBind["OC30,out,rm366"] = 1;
$heatmapBind["OC30,out,rm370"] = 2;
$heatmapBind["WH10,in,Antelounge"] = 1;
$heatmapBind["WH10,in,Kitchen"] = 9;
$heatmapBind["WH10,in,Lawn"] = 2;
$heatmapBind["WH10,in,lounge"] = 39;
$heatmapBind["WH10,in,North Nook"] = 11;
$heatmapBind["WH10,in,outside"] = 1;
$heatmapBind["WH10,in,rm105"] = 11;
$heatmapBind["WH10,in,rm107"] = 13;
$heatmapBind["WH10,in,rm118"] = 2;
$heatmapBind["WH10,out,eastwing"] = 1;
$heatmapBind["WH10,out,northwing"] = 2;
$heatmapBind["WH10,out,outside"] = 2;
$heatmapBind["WH10,out,rm124"] = 1;
$heatmapBind["WH10,out,Sukkah"] = 3;
$heatmapBind["WH10,out,westwing"] = 3;
$heatmapBind["WH20,in,antelounge"] = 86;
$heatmapBind["WH20,in,lounge"] = 252;
$heatmapBind["WH20,in,Nook"] = 81;
$heatmapBind["WH20,in,north wing"] = 21;
$heatmapBind["WH20,in,North Wing Nook"] = 26;
$heatmapBind["WH20,in,rm14"] = 3;
$heatmapBind["WH20,in,rm201"] = 12;
$heatmapBind["WH20,in,rm202"] = 24;
$heatmapBind["WH20,in,rm203"] = 10;
$heatmapBind["WH20,in,rm204"] = 40;
$heatmapBind["WH20,in,rm205"] = 75;
$heatmapBind["WH20,in,rm206"] = 9;
$heatmapBind["WH20,in,rm207"] = 93;
$heatmapBind["WH20,in,rm209"] = 42;
$heatmapBind["WH20,in,rm210"] = 33;
$heatmapBind["WH20,in,rm211"] = 2;
$heatmapBind["WH20,in,rm212"] = 59;
$heatmapBind["WH20,in,rm213"] = 104;
$heatmapBind["WH20,in,rm214"] = 18;
$heatmapBind["WH20,in,rm215"] = 44;
$heatmapBind["WH20,in,rm218"] = 30;
$heatmapBind["WH20,in,rm223"] = 33;
$heatmapBind["WH20,in,rm224"] = 10;
$heatmapBind["WH20,in,rm225"] = 60;
$heatmapBind["WH20,in,rm226"] = 17;
$heatmapBind["WH20,in,rm227"] = 146;
$heatmapBind["WH20,in,rm228"] = 13;
$heatmapBind["WH20,in,rm229"] = 40;
$heatmapBind["WH20,in,rm230"] = 7;
$heatmapBind["WH20,in,rm231"] = 18;
$heatmapBind["WH20,in,rm28"] = 3;
$heatmapBind["WH20,in,rm31"] = 1;
$heatmapBind["WH20,out,North Wing Nook"] = 4;
$heatmapBind["WH20,out,rm215"] = 11;
$heatmapBind["WH20,out,westwing"] = 1;
$heatmapBind["WH30,in,antelounge"] = 119;
$heatmapBind["WH30,in,lounge"] = 305;
$heatmapBind["WH30,in,North Wing"] = 7;
$heatmapBind["WH30,in,northwing nook"] = 35;
$heatmapBind["WH30,in,rm301"] = 65;
$heatmapBind["WH30,in,rm301 Bath"] = 3;
$heatmapBind["WH30,in,rm301Bathrm"] = 1;
$heatmapBind["WH30,in,rm302"] = 41;
$heatmapBind["WH30,in,rm303"] = 220;
$heatmapBind["WH30,in,rm304"] = 71;
$heatmapBind["WH30,in,rm305"] = 4;
$heatmapBind["WH30,in,rm306"] = 59;
$heatmapBind["WH30,in,rm307"] = 17;
$heatmapBind["WH30,in,rm309"] = 141;
$heatmapBind["WH30,in,rm310"] = 1;
$heatmapBind["WH30,in,rm311"] = 9;
$heatmapBind["WH30,in,rm312"] = 64;
$heatmapBind["WH30,in,rm313"] = 138;
$heatmapBind["WH30,in,rm314"] = 15;
$heatmapBind["WH30,in,rm315"] = 68;
$heatmapBind["WH30,in,rm318"] = 12;
$heatmapBind["WH30,in,rm320"] = 1;
$heatmapBind["WH30,in,rm323"] = 28;
$heatmapBind["WH30,in,rm324"] = 31;
$heatmapBind["WH30,in,rm325"] = 44;
$heatmapBind["WH30,in,rm326"] = 4;
$heatmapBind["WH30,in,rm327"] = 23;
$heatmapBind["WH30,in,rm328"] = 3;
$heatmapBind["WH30,in,rm329"] = 69;
$heatmapBind["WH30,in,rm330"] = 82;
$heatmapBind["WH30,in,rm331"] = 29;
$heatmapBind["WH30,in,TEAM ROOM"] = 6;
$heatmapBind["WH30,in,West Wing Windowseat"] = 4;
$heatmapBind["WH30,out,northwing"] = 16;
$heatmapBind["WH30,out,rm309"] = 7;
$heatmapBind["WH30,out,westwing"] = 6;
$heatmapBind["WH40,in,antelounge"] = 47;
$heatmapBind["WH40,in,lounge"] = 87;
$heatmapBind["WH40,in,northwing stairwell"] = 1;
$heatmapBind["WH40,in,rm02"] = 3;
$heatmapBind["WH40,in,rm401"] = 9;
$heatmapBind["WH40,in,rm402"] = 43;
$heatmapBind["WH40,in,rm403"] = 24;
$heatmapBind["WH40,in,rm404"] = 56;
$heatmapBind["WH40,in,rm405"] = 13;
$heatmapBind["WH40,in,rm406"] = 74;
$heatmapBind["WH40,in,rm407"] = 39;
$heatmapBind["WH40,in,rm409"] = 28;
$heatmapBind["WH40,in,rm410"] = 5;
$heatmapBind["WH40,in,rm410 (The Zebra Lounge)"] = 24;
$heatmapBind["WH40,in,rm411"] = 44;
$heatmapBind["WH40,in,rm412"] = 19;
$heatmapBind["WH40,in,rm413"] = 12;
$heatmapBind["WH40,in,rm414"] = 172;
$heatmapBind["WH40,in,rm415"] = 107;
$heatmapBind["WH40,in,rm418"] = 77;
$heatmapBind["WH40,in,rm420"] = 2;
$heatmapBind["WH40,in,rm423"] = 80;
$heatmapBind["WH40,in,rm424"] = 3;
$heatmapBind["WH40,in,rm425"] = 43;
$heatmapBind["WH40,in,rm427"] = 13;
$heatmapBind["WH40,in,rm429"] = 29;
$heatmapBind["WH40,in,rm431"] = 46;
$heatmapBind["WH40,out,Antelounge"] = 1;
$heatmapBind["WH40,out,eastwing"] = 6;
$heatmapBind["WH40,out,northwing"] = 5;
$heatmapBind["WH40,out,westwing"] = 3;


// This is data
$mapw = (int) $_GET['mapw'];
if (! $mapw) {echo 'no map'; exit;}


$debugShowHistory = false;
$historyInterval = 604800; //in seconds -- this is a week

if ($debugShowHistory)
$q = 'select usercal.username, pointcal.mapx, pointcal.mapy, pointcal.placename from usercal inner join pointcal on usercal.placename=pointcal.placename where (unix_timestamp(NOW()) - unix_timestamp(usercal.lastupdated)<'.$historyInterval.') and pointcal.mapw='.$mapw;
else
$q = 'select usercal.username, pointcal.mapx, pointcal.mapy, pointcal.placename, usercal.lastupdated, usercal.icon from usercal inner join pointcal on usercal.placename=pointcal.placename where pointcal.mapw='.$mapw . ' and (unix_timestamp(NOW()) - unix_timestamp(usercal.lastupdated)<'.$historyInterval.') order by usercal.lastupdated DESC';

$q = 'SELECT DISTINCT(placename),mapx,mapy FROM pointcal WHERE mapw='.$mapw;
//~ $q = 'select users.username, point.mapx, point.mapy, point.placename from users inner join point on users.placename=point.placename where point.mapw='.$mapw;
list($res, $n) = DB($q);

$astr=array();
$usernames = array(); //Hash table containing usernames that have been used.
while ($arr = mysql_fetch_array($res))
{
	// $res is now a list of all points in the database
	
	// correlate these points to the data we have
	$thisPlace = ($arr['placename']);
	$thisNum = $heatmapBind[$thisPlace];
	if (!$thisNum || $thisNum <= 0 || $thisNum == "")
	{
		$thisNum = 0;	
	}
	//echo $thisPlace . " > " . $thisNum . "<br>";

	// create an image that corresponds to this number of hits
	$heatSize = round(50 * $thisNum/$maxNum);
	if ($heatSize <= 0)
	{
		$heatSize = 5;
	}

//	$im = imagecreate($heatSize, $heatSize);
	$im = imagecreate(50, 50);
	$bgColor = imagecolorallocatealpha($im, 255, 255, 255, 127);
	$circleColor = imagecolorallocatealpha($im, 255, 0, 0, 40);
	imagefilledellipse($im, 25, 25, $heatSize, $heatSize, $circleColor);
	$icon = "heatmap/img" . $thisPlace . ".png";
	imagepng($im, $icon);
	$icon = "../" . $icon;
/*
	
	if (!$debugShowHistory)
	{
		if ($usernames[$arr['username']]) continue; //We have already recorded a point from that user. No duplicates.
		$usernames[$arr['username']] = 1;
	}
	$temp = ParseLocation($arr['placename']);
	$location = $temp[0];
	$floor = $temp[1];
	$usernameValue = $usernameArray[$arr['username']];
	if ($usernameValue == "")
	{
		$usernameValue = $arr['username'];
	}
	$icon = $arr['icon'];
	
	if ($icon == "")
	{
		//default icon
		$icon = "p.gif";
	} else
	{
		$icon = "../../" . $icon;
	}
	*/
	$usernameValue = $thisNum . " hits (" . sprintf("%.2f",$thisNum / $totalNum * 100) . "%)";
	$temp = ParseLocation($arr['placename']);
	$location = $temp[0];
	$floor = $temp[1];


	array_push($astr, $arr['mapx'].'|'.$arr['mapy'].'|'.$usernameValue.'|'.$location.'|'.date("Y-m-d H:i:00").'|'.$floor.'|'.$icon);

$str = implode(';',$astr);
}
echo 'success:' . $str;
//echo "success:74|411|Gray Thomas|Inside MH (LL) Library|2009-02-23 04:30:28|0|../../usericons/gthomas.gif;74|411|Matthew Alvarado|Inside MH (LL) Library|2009-02-23 04:08:26|0|p.gif;550|30|Raphael Cherney|Inside AC309|2009-02-23 03:32:08|3|p.gif;853|497|onionuser|Inside CCCresecent|2009-02-23 00:41:05|2|p.gif;586|44|Lindsay Kaye|Inside AC209|2009-02-22 14:52:31|2|p.gif;121|584|Zachary Newell|Inside MH325|2009-02-20 15:50:38|3|p.gif;650|60|Jayesh Gorasia|Inside AC413|2009-02-20 13:08:26|4|p.gif;884|194|Kevin Sihlanick|Inside AC128|2009-02-18 15:14:53|1|../../usericons/ksihlanick.gif;568|50|Laura Firstenberg|Inside AC409|2009-02-18 00:09:43|4|p.gif";

//echo "success:666|583|heatmap|Outside of EH 1st floor northwing|2009-02-18 05:09:43|1|../heatmap/imgeh10,out,northwing.png;526|601|heatmap|Inside EH 1st floor lounge|2009-02-18 00:09:43|1|../heatmap/imgeh10,in,lounge.png;455|655|heatmap|Outside of EH 1st floor eastwing|2009-02-18 00:09:43|1|../heatmap/imgeh10,out,eastwing.png";


function ParseLocation($location)
{
	
	// location strings look like WH,in,rm309
	$location = str_replace("OC", "MH", $location);
	$tempArray = explode(",", $location);
	$building = $tempArray[0];
	$inside = $tempArray[1];
	$description = $tempArray[2];
	
	$floorNum = substr($building, 2, 1);
	$building = substr($building, 0, 2);
	
	if ($inside == "in")
	{
		$inside = "inside";
	} elseif ($inside == "out")
	{
		$inside = "outside of";
	}

	if ($floorNum == "1")
	{
		$floor = "1st";
	} elseif ($floorNum == "2")
	{
		$floor = "2nd";
	} elseif ($floorNum == "3")
	{
		$floor = "3rd";
	} elseif ($floorNum == "4")
	{
		$floor = "4th";
	} elseif ($floorNum == "0")
	{
		$floor = "LL";
	}

	if (stripos($description, "rm") !== false || stripos($description, "room") !== false)
	{
		// this has a room
		$description = str_ireplace("rm", "", $description);
		$description = str_ireplace("room", "", $description);

		$location = $inside . " " . $building . $description;
	} else
	{
		if ($floor != "LL")
		{
			$location = $inside . " " . $building . " " . $floor . " floor " . $description;
		} else
		{
			$location = $inside . " " . $building . " (" . $floor . ") " . $description;
		}
	}
	
	$ret[0] = ucfirst($location);
	$ret[1] = $floorNum;
	return $ret;
}

function ParseUsername($username, $usernameArray)
{
	//change a username like "abarry" to "Andy Barry"
	
}



?>
