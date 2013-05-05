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
class Recaptcha_Controller_Admin_Recaptcha extends Controller_Admin {
  public function action_index() {
    $form = Formo::form()
      ->attr("id", "g-configure-recaptcha-form")
      ->add("recaptcha", "group");
    $form->recaptcha
      ->set("label", t("Configure reCAPTCHA"))
      ->add("public_key",  "input", Module::get_var("recaptcha", "public_key"))
      ->add("private_key", "input", Module::get_var("recaptcha", "private_key"))
      ->add("submit",      "input|submit", t("Save"));
    $form->recaptcha->public_key
      ->set("label", t("Public Key"))
      ->add_rule("not_empty",             array(":value"), t("You must enter a public key"));
    $form->recaptcha->private_key
      ->set("label", t("Private Key"))
      ->add_rule("not_empty",             array(":value"), t("You must enter a private key"))
      ->add_rule("Recaptcha::verify_key", array(":value"), t("This private key is invalid"));

    if ($form->sent()) {
      // We typically run load() and validate() together, but do it separately here so that,
      // if *both* keys are empty, we can disable reCAPTCHA without throwing errors.
      $form->load();
      $public_key  = $form->recaptcha->public_key->val();
      $private_key = $form->recaptcha->private_key->val();
      if (!$public_key && !$private_key) {
        Module::set_var("recaptcha", "public_key", "");
        Module::set_var("recaptcha", "private_key", "");
        Message::success(t("No keys provided.  reCAPTCHA is disabled!"));
        GalleryLog::success("recaptcha", t("reCAPTCHA public and private keys cleared"));
        $this->redirect("admin/recaptcha");
      } else {
        if ($form->validate()) {
          Module::set_var("recaptcha", "public_key",  $public_key);
          Module::set_var("recaptcha", "private_key", $private_key);
          Message::success(t("reCAPTCHA configured!"));
          GalleryLog::success("recaptcha", t("reCAPTCHA public and private keys set"));
          $this->redirect("admin/recaptcha");
        }
      }
    }

    Recaptcha::check_config();
    $site_domain = urlencode(stripslashes($_SERVER["SERVER_NAME"]));

    $view = new View_Admin("required/admin.html");
    $view->page_title = t("reCAPTCHA");
    $view->content = new View("admin/recaptcha.html");
    $view->content->public_key =  Module::get_var("recaptcha", "public_key");
    $view->content->private_key = Module::get_var("recaptcha", "private_key");
    $view->content->get_key_url =
      "http://www.google.com/recaptcha/admin/create?domains=$site_domain&app=Gallery3";
    $view->content->form = $form;
    $this->response->body($view);
  }
}
