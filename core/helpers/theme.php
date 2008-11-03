<?php defined("SYSPATH") or die("No direct script access.");
/**
 * Gallery - a web based photo album viewer and editor
 * Copyright (C) 2000-2008 Bharat Mediratta
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
class theme_Core {
  public static $debug_backtrace = "debug_backtrace";

  public static function url($path) {
    // Using debug_backtrace() sucks.  But I don't know of another way to get
    // the location of the caller without forcing them to pass it in as an argument, which
    // makes for a crappy API.
    $backtrace = call_user_func(theme::$debug_backtrace);
    $calling_file = $backtrace[1]['file'];
    $theme_name = substr($calling_file, strlen(THEMEPATH));
    $theme_name = substr($theme_name, 0, strcspn($theme_name, "/"));
    return url::base() . "themes/{$theme_name}/$path";
  }
}
