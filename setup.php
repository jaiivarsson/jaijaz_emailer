<?php
/**
 *                     Jaijaz Emailer module
 *                ===============================
 *
 * Copyright 2010 Jaijaz <info@jaijaz.co.nz>
 *
 * See the enclosed file license.txt for license information (LGPL). If you
 * did not receive this file, see http://www.fsf.org/copyleft/lgpl.html.
 *
 * @author  Jai Ivarsson <jai@jaijaz.co.nz>
 * @link    http://www.jaijaz.co.nz
 */

/* insert the event handler page */
$data = Jojo::selectQuery("SELECT pageid FROM {page} WHERE pg_link='Jojo_Plugin_Jaijaz_emailer_events'");
if (!count($data)) {
    echo "Adding <b>Emailer Event Handler</b> Page to menu<br />";
    Jojo::insertQuery("INSERT INTO {page} SET pg_title='Emailer Event Handler', pg_parent = ?, pg_link='Jojo_Plugin_Jaijaz_emailer_events', pg_url='emailer-events'", array($_NOT_ON_MENU_ID));
}
