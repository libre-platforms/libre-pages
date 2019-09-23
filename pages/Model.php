<?php
//  Copyright (C) 2019 Jörn Neumeyer
//
//  This file is part of LibrePages.
//
//  LibrePages is free software: you can redistribute it and/or modify
//  it under the terms of the GNU Affero General Public License as published by
//  the Free Software Foundation, either version 3 of the License, or
//  (at your option) any later version.
//
//  LibrePages is distributed in the hope that it will be useful,
//  but WITHOUT ANY WARRANTY; without even the implied warranty of
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//  GNU Affero General Public License for more details.
//
//  You should have received a copy of the GNU Affero General Public License
//  along with LibrePages.  If not, see <https://www.gnu.org/licenses/>.

  declare(strict_types=1);
  namespace Pages;

  class Model {
    protected $primary_key = 'id';
    protected $attributes = [];

    public function __get($key) {
      if (method_exists($this, "get_{$key}_attribute")) {
        return ([$this, "get_{$key}_attribute"])($key);
      } else {
        return $attributes[$key] ?? null;
      }
    }

    public function __set($key, $value) {
      if (method_exists($this, "set_{$key}_attribute")) {
        ([$this, "get_{$key}_attribute"])($key, $value);
      } else {
        $attributes[$key] = $value;
      }
    }

    /**
     * Adds a database record if the entity does not already exist.
     * Otherwise the existing record will be updated.
     */
    public function save() {
      // TODO
    }

    /**
     * Deteltes the model, if it exists in the database.
     * @return bool Returns a boolean, indicating whether a deletion occurred or not.
     */
    public function delete(): bool {
      return false;
    }

    /**
     * Retrieves all database records of a defined model, which fit the requested criteria.
     */
    public static function where(string $field, string $condition, string $expected_value) {

    }
  }