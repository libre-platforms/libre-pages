<?php
  declare(strict_types=1);

  namespace Framework;

  /**
   * @property string $name
   * @property string $type
   * @property string $tmp_name
   * @property int $error
   * @property int $size
   */
  class RequestFile {
    protected $_data;

    function __construct(array $data) {
      $this->_data = $data;
    }

    function __get(string $key) {
      return $this->_data ?? null;
    }

    function save(string $destination) {
      return move_uploaded_file($this->tmp_name, $destination);
    }

    function is_ok() {
      return $this->error === UPLOAD_ERR_OK;
    }
  }