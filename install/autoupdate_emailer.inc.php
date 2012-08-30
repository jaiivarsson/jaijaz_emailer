<?php
/**
 *                    Jojo CMS
 *                ================
 *
 * Copyright 2008 Michael Cochrane <mikec@joojcms.org>
 *
 * See the enclosed file license.txt for license information (LGPL). If you
 * did not receive this file, see http://www.fsf.org/copyleft/lgpl.html.
 *
 * @author  Michael Cochrane <mikec@jojocms.org>
 * @license http://www.fsf.org/copyleft/lgpl.html GNU Lesser General Public License
 * @link    http://www.jojocms.org JojoCMS
 */

$o=0;

$table = 'email_template';

$default_td[$table] = array(
        'td_name' => "email_template",
        'td_displayname' => "Templates",
        'td_primarykey' => "email_templateid",
        'td_displayfield' => "name",
        'td_deleteoption' => "yes",
        'td_orderbyfields' => "name",
        'td_menutype' => "list",
        'td_help' => "Newsletters templates are managed from here.",
        'td_defaultpermissions' => "everyone.show=1\neveryone.view=1\neveryone.edit=1\neveryone.add=1\neveryone.delete=1\nadmin.show=1\nadmin.view=1\nadmin.edit=1\nadmin.add=1\nadmin.delete=1\nnotloggedin.show=0\nnotloggedin.view=0\nnotloggedin.edit=0\nnotloggedin.add=0\nnotloggedin.delete=0\nregistered.show=1\nregistered.view=1\nregistered.edit=1\nregistered.add=1\nregistered.delete=1\nsysinstall.show=1\nsysinstall.view=1\nsysinstall.edit=1\nsysinstall.add=1\nsysinstall.delete=1\n",
    );

$default_fd[$table]['email_templateid'] = array(
        'fd_name' => "ID",
        'fd_type' => "readonly",
        'fd_size' => "0",
        'fd_rows' => "0",
        'fd_cols' => "0",
        'fd_order' => $o++,
        'fd_tabname' => "Details",
    );

$default_fd[$table]['name'] = array(
        'fd_name' => "Name",
        'fd_type' => "text",
        'fd_size' => "40",
        'fd_rows' => "0",
        'fd_cols' => "0",
        'fd_order' => $o++,
        'fd_tabname' => "Details",
    );

$default_fd[$table]['tpl_filename'] = array(
        'fd_name' => "Template filename",
        'fd_type' => "text",
        'fd_size' => "40",
        'fd_rows' => "0",
        'fd_cols' => "0",
        'fd_order' => $o++,
        'fd_tabname' => "Details",
    );
