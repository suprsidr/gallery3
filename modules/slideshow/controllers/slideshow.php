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
class Slideshow_Controller extends Controller {
  public function js($id=null) {
    if (empty($id)) {
      throw new Kohana_404_Exception();
    }
    $item = ORM::factory("item", $id);
    access::required("view", $item);
    $entities = array();
    foreach ($item->viewable()->descendants(null, null, null) as $child){
      if (!$child->is_album()) {
        $entities[] = $this->_item_data($child);
      }
    }
    $view = new View("slideshow.js");
    $view -> items = json_encode($entities, true);
	$view -> ss_theme = module::get_var("slideshow", "theme")?
	  module::get_var("slideshow", "theme"):'classic';
    header('content-type: application/x-javascript');
    print $view;
  }
  
  public function iframe($id=null) {
    if (empty($id)) {
      throw new Kohana_404_Exception();
    }
    $item = ORM::factory("item", $id);
    access::required("view", $item);
    $view = new View("iframe.html");
	$view -> item = json_encode($this->_item_data($item), true);
    $view -> code = url::abs_file('lib/flowplayer-flash/');
    header('content-type: text/html');
    print $view;
  }
  
  private function _item_data($item, $fields=array()) {
    if ($fields) {
      $data = array();
      foreach ($fields as $field) {
        if (isset($item->object[$field])) {
          $data[$field] = $item->__get($field);
        }
      }
      $fields = array_flip($fields);
    } else {
      $data = $item->as_array();
    }

    // Convert item ids to abs URLs for consistency
    if (empty($fields) || isset($fields["parent"])) {
      if ($tmp = $item->parent()) {
        $data["parent"] = $tmp->abs_url();
      }
      unset($data["parent_id"]);
    }

    if (empty($fields) || isset($fields["web_url"])) {
      $data["web_url"] = $item->abs_url();
    }

    if (!$item->is_album()) {
      if (access::can("view_full", $item)) {
        if (access::user_can(identity::guest(), "view_full", $item)) {
          if (empty($fields) || isset($fields["file_url_public"])) {
            if($item->is_photo()) {
              $data["file_url_public"] = $item->file_url(true);
            }else if($item->is_movie()){
              $data["file_url_public"] = $item->file_url(true);
              $data["iframe_url_public"] = url::site("slideshow/iframe/".$item->id);
            }
            
          }
        }
      }
    }

    if ($item->is_photo()) {
      if (access::user_can(identity::guest(), "view", $item)) {
        if (empty($fields) || isset($fields["resize_url_public"])) {
          $data["resize_url_public"] = $item->resize_url(true);
        }
      }
    }

    if ($item->has_thumb()) {
      if (access::user_can(identity::guest(), "view", $item)) {
        if (empty($fields) || isset($fields["thumb_url_public"])) {
          $data["thumb_url_public"] = $item->thumb_url(true);
        }
      }
    }

    // Elide some internal-only data that is not useful for the slideshow.
    foreach (array("relative_path_cache", "relative_url_cache", "left_ptr", "right_ptr",
                   "thumb_dirty", "resize_dirty", "weight", "level", "album_cover_item_id",
                   "captured", "owner_id", "rand_key", "sort_column", "sort_order", "updated",
                   "view_1", "view_2", "view_count", "slug") as $key) {
      unset($data[$key]);
    }
    return $data;
  }
  
  
}   
 