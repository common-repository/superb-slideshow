<?php

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit();
}

delete_option('sswld_dir1');
delete_option('sswld_dir2');
delete_option('sswld_dir3');
delete_option('sswld_random');
delete_option('sswld_title');
delete_option('sswld_width');
delete_option('sswld_height');
delete_option('sswld_pause');
delete_option('sswld_duration');
delete_option('sswld_cycles');
delete_option('sswld_displaydesc');
 
// for site options in Multisite
delete_site_option('sswld_dir1');
delete_site_option('sswld_dir2');
delete_site_option('sswld_dir3');
delete_site_option('sswld_random');
delete_site_option('sswld_title');
delete_site_option('sswld_width');
delete_site_option('sswld_height');
delete_site_option('sswld_pause');
delete_site_option('sswld_duration');
delete_site_option('sswld_cycles');
delete_site_option('sswld_displaydesc');