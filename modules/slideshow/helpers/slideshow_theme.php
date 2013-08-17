<?php defined("SYSPATH") or die("No direct script access.");
/**
 * Gallery - a web based photo album viewer and editor
 * Copyright (C) 2000-2013 Bharat Mediratta
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or (at
 * your option) any later version.
 *
 * This program is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street - Fifth Floor, Boston, MA  02110-1301, USA.
 */
class slideshow_theme_Core {
  
  static function page_top($theme){
    //$ss_theme = module::get_var("slideshow", "theme")?module::get_var("slideshow", "theme"):'classic';
    //return '    <link rel="stylesheet" href="'.url::abs_file('modules/slideshow/vendor/themes/' . $ss_theme . '/galleria.' . $ss_theme . '.css').'">'
    //  ."\n";
  }
  
  static function page_bottom($theme) {
    if($theme->item->is_album()){
      $id = $theme->item()->id;
    }else{
      $id = $theme->item()->parent_id;
    }
    $ss_theme = module::get_var("slideshow", "theme")?module::get_var("slideshow", "theme"):'classic';
    return '    <script src="'.url::site("slideshow/js/".$id).'"></script>'
      ."\n"
      .'    <script src="'.url::abs_file("modules/slideshow/vendor/galleria-1.2.9.min.js").'"></script>'
      ."\n"
      .'    <script src="'.url::abs_file('modules/slideshow/vendor/themes/' . $ss_theme . '/galleria.' . $ss_theme . '.min.js').'"></script>'
      ."\n"
      .'    <link rel="stylesheet" href="'.url::abs_file('modules/slideshow/vendor/themes/' . $ss_theme . '/galleria.' . $ss_theme . '.css').'">'
      ."\n";
  }
}
