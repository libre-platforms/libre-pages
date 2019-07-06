<?php
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