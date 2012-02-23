<?php // TZ_iCal_test2.php
/**
 * iCalcreator class v2.6
 * copyright (c) 2007-2008 Kjell-Inge Gustafsson, kigkonsult
 * www.kigkonsult.se/iCalcreator/index.php
 * ical@kigkonsult.se
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 */

require_once '../iCalcreator.class.php';

echo "12345678901234567890123456789012345678901234567890123456789012345678901234567890<br />\n";
echo "         1         2         3         4         5         6         7         8<br />\n";
$tpl = "
     BEGIN:VTIMEZONE
     TZID:US-Eastern
     LAST-MODIFIED:19870101T000000Z
     TZURL:http://zones.stds_r_us.net/tz/US-Eastern
     BEGIN:STANDARD
     DTSTART:19671029T020000
     RRULE:FREQ=YEARLY;BYDAY=-1SU;BYMONTH=10
     TZOFFSETFROM:-0400
     TZOFFSETTO:-0500
     TZNAME:EST
     END:STANDARD
     BEGIN:DAYLIGHT
     DTSTART:19870405T020000
     RRULE:FREQ=YEARLY;BYDAY=1SU;BYMONTH=4
     TZOFFSETFROM:-0500
     TZOFFSETTO:-0400
     TZNAME:EDT
     END:DAYLIGHT
     END:VTIMEZONE
     <br />
";
while( 0 < substr_count( $tpl, '  '))
  $tpl = str_replace('  ', ' ', $tpl );
echo $tpl;

$c = new vcalendar();

$e = new vtimezone();
$e->setProperty( 'Tzid', 'US-Eastern' );
$e->setProperty( 'Last-Modified', '19870101T000000' );
$e->setProperty( 'tzurl', 'http://zones.stds_r_us.net/tz/US-Eastern' );

$s = new vtimezone( 'standard' );
$s->setProperty( 'dtstart', '19671029T020000' );
$s->setProperty( 'rrule'
               , array( 'FREQ'    => "YEARLY"
                      , 'BYMONTH' => 10
                      , 'BYday'   => array( -1, 'DAY' => 'SU' )));
$s->setProperty( 'tzoffsetfrom', '-0400' );
$s->setProperty( 'tzoffsetto', '-0500' );
$s->setProperty( 'tzname', 'EST' );
$e->setComponent( $s );

$d = new vtimezone( 'daylight' );
$d->setProperty( 'Dtstart', '19870405T020000' );
$d->setProperty( 'Rrule'
               , array( 'FREQ'    => "YEARLY"
                      , 'BYMONTH' => 4
                      , 'BYday'   => array( 1, 'DAY' => 'SU' )));
$d->setProperty( 'Tzoffsetfrom', '-0500' );
$d->setProperty( 'Tzoffsetto', '-0400' );
$d->setProperty( 'tzname', 'EDT' );
$e->addSubComponent( $d );

$c->setComponent( $e );

// save calendar in file, create new calendar, parse saved file into new file
$c->setConfig( 'filename', 'test.ics' );
$c->saveCalendar();

$c = new vcalendar();
$c->parse( 'test.ics' );
$c->setConfig( 'filename', 'test2.ics' );
$c->saveCalendar();

$str = $c->createCalendar(); 
echo $str; 

$a=array(); $n=chr(10); exec('diff test.ics test2.ics', $a); echo "$n file compare:$n".implode($n,$a);
?>