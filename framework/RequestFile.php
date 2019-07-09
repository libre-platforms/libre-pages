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

  namespace Framework;

  /**
   * Represents a file, which has been uploaded with a request.
   *
   * @property string $name
   * @property string $type
   * @property string $tmp_name
   * @property int $error
   * @property int $size
   */
  class RequestFile {
    protected $_data;
    protected $_is_saved = false;

    function __construct(array $data) {
      $this->_data = $data;
    }

    function __get(string $key) {
      return $this->_data ?? null;
    }

    /**
     * Trys to save an uploaded file to the given location.
     */
    function save(string $destination) {
      if (!$this->_is_saved) {
        $this->_is_saved = move_uploaded_file($this->tmp_name, $destination);
      }
      return $this->_is_saved;
    }

    /**
     * Returns a bool, representing the success of the file upload.
     */
    function is_ok() {
      return $this->error === UPLOAD_ERR_OK;
    }
  }