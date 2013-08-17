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
class slideshow_event_Core {

  static function album_menu($menu, $theme) {
    if ($theme->item()->descendants_count(array(array("type", "=", "photo"))) || $theme->item()->descendants_count(array(array("type", "=", "movie")))) {
      $menu->append(Menu::factory("link")
                    ->id("slideshow")
                    ->label(t("View slideshow"))
                    ->url("javascript:g3_slideshow.show(0)")
                    ->css_id("g-slideshow-link"));
    }
  }

  static function photo_menu($menu, $theme) {
    $menu->append(Menu::factory("link")
                  ->id("slideshow")
                  ->label(t("View slideshow"))
                  ->url("javascript:g3_slideshow.show(".$theme->item()->id.")")
                  ->css_id("g-slideshow-link"));
  }
  
  static function movie_menu($menu, $theme) {
    $menu->append(Menu::factory("link")
                  ->id("slideshow")
                  ->label(t("View slideshow"))
                  ->url("javascript:g3_slideshow.show(".$theme->item()->id.")")
                  ->css_id("g-slideshow-link"));
  }

  static function tag_menu($menu, $theme) {
    $menu->append(Menu::factory("link")
                  ->id("slideshow")
                  ->label(t("View slideshow"))
                  ->url("javascript:g3_slideshow.show()")
                  ->css_id("g-slideshow-link"));
  }

}
