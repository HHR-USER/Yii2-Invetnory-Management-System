<?php
/**
 * @package   yii2-filesystem
 * @author    Kartik Visweswaran <kartikv2@gmail.com>
 * @copyright Copyright &copy; Kartik Visweswaran, Krajee.com, 2018 - 2019
 * @version   1.0.0
 */

namespace kartik\filesystem;

use finfo;
use SplFileInfo;
use Yii;

/**
 * The File class provides a convenient object oriented method for reading, writing and appending to files. This is a
 * port of CakePHP FileSystem File class for Yii2.
 */
class File
{
    /**
     * @var Folder the folder object of the file
     */
    public $folder;

    /**
     * @var string the file name
     */
    public $name;

    /**
     * @var array the file information
     */
    public $info = [];

    /**
     * @var resource|null holds the file handler resource if the file is opened
     */
    public $handle;

    /**
     * @var bool|null enable locking for file reading and writing
     */
    public $lock;

    /**
     * @var string|null current file's absolute path
     */
    public $path;

    /**
     * File constructor
     *
     * @param string $path   path to file (can be set using yii path aliases)
     * @param bool   $create create file if it does not exist (if true)
     * @param int    $mode   mode to apply to the folder holding the file
     */
    public function __construct($path, $create = false, $mode = 0755)
    {
        $path = Yii::getAlias($path);
        $splInfo = new SplFileInfo($path);
        $this->folder = new Folder($splInfo->getPath(), $create, $mode);
        if (!is_dir($path)) {
            $this->name = ltrim($splInfo->getFilename(), '/\\');
        }
        $this->pwd();
        $create && !$this->exists() && $this->safe($path) && $this->create();
    }

    /**
     * Prepares an ASCII string for writing. Converts line endings to the correct terminator for the current platform.
     * If Windows, "\r\n" will be used, all other platforms will use "\n".
     *
     * @param string $data         data to prepare for writing.
     * @param bool   $forceWindows whether to force Windows new line string.
     *
     * @return string data with converted line endings.
     */
    public static function prepare($data, $forceWindows = false)
    {
        $lineBreak = "\n";
        if (DIRECTORY_SEPARATOR === '\\' || $forceWindows === true) {
            $lineBreak = "\r\n";
        }
        return strtr($data, ["\r\n" => $lineBreak, "\n" => $lineBreak, "\r" => $lineBreak]);
    }

    /**
     * Returns the file basename. Simulate the php basename() for multibyte (mb_basename).
     *
     * @param string      $path path to file
     * @param string|null $ext  the name of the extension
     *
     * @return string the file basename.
     */
    protected static function basename($path, $ext = null)
    {
        // check for multibyte string and use basename() if not found
        if (mb_strlen($path) === strlen($path)) {
            return ($ext === null) ? basename($path) : basename($path, $ext);
        }
        $splInfo = new SplFileInfo($path);
        $name = ltrim($splInfo->getFilename(), '/\\');
        if ($ext === null || $ext === '') {
            return $name;
        }
        $new = preg_replace('/(' . preg_quote($ext) . ')$/u', '', $name);
        // basename of '/etc/.d' is '.d' not ''
        return ($new === '') ? $name : $new;
    }

    /**
     * Closes the current file if it is opened
     */
    public function __destruct()
    {
        $this->close();
    }

    /**
     * Creates the file.
     *
     * @return bool whether the operation was successful
     */
    public function create()
    {
        $dir = $this->folder->pwd();
        return (is_dir($dir) && is_writable($dir) && !$this->exists() && touch($this->path));
    }

    /**
     * Opens the current file with a given $mode
     *
     * @param string $mode  a valid 'fopen' mode string (r|w|a ...)
     * @param bool   $force if true then the file will be re-opened even if its already opened, otherwise it won't
     *
     * @return bool whether the operation was successful
     */
    public function open($mode = 'r', $force = false)
    {
        if (!$force && is_resource($this->handle)) {
            return true;
        }
        if ($this->exists() === false && $this->create() === false) {
            return false;
        }
        $this->handle = fopen($this->path, $mode);
        return is_resource($this->handle);
    }

    /**
     * Return the contents of this file as a string.
     *
     * @param string|bool $bytes where to start
     * @param string      $mode  a `fread` compatible mode.
     * @param bool        $force whether the file will be re-opened even if its already opened, otherwise it won't
     *
     * @return string|false string on success, false on failure
     */
    public function read($bytes = false, $mode = 'rb', $force = false)
    {
        if ($bytes === false && $this->lock === null) {
            return file_get_contents($this->path);
        }
        if ($this->open($mode, $force) === false) {
            return false;
        }
        if ($this->lock !== null && flock($this->handle, LOCK_SH) === false) {
            return false;
        }
        if (is_int($bytes)) {
            return fread($this->handle, $bytes);
        }
        $data = '';
        while (!feof($this->handle)) {
            $data .= fgets($this->handle, 4096);
        }
        if ($this->lock !== null) {
            flock($this->handle, LOCK_UN);
        }
        if ($bytes === false) {
            $this->close();
        }
        return trim($data);
    }

    /**
     * Sets or gets the offset for the currently opened file.
     *
     * @param int|bool $offset the $offset in bytes to seek. If set to `false`, then the current offset is returned.
     * @param int      $seek   PHP constant SEEK_SET | SEEK_CUR | SEEK_END determining what the $offset is relative to
     *
     * @return int|bool True on success, false on failure (set mode), false on failure or int offset on success (get
     *     mode)
     */
    public function offset($offset = false, $seek = SEEK_SET)
    {
        if ($offset === false) {
            if (is_resource($this->handle)) {
                return ftell($this->handle);
            }
        } elseif ($this->open() === true) {
            return fseek($this->handle, $offset, $seek) === 0;
        }
        return false;
    }

    /**
     * Write given data to this file.
     *
     * @param string $data  Data to write to this File.
     * @param string $mode  Mode of writing. {@link https://secure.php.net/fwrite See fwrite()}.
     * @param bool   $force Force the file to open
     *
     * @return bool whether the operation was successful
     */
    public function write($data, $mode = 'w', $force = false)
    {
        $success = false;
        if ($this->open($mode, $force) === true) {
            if ($this->lock !== null && flock($this->handle, LOCK_EX) === false) {
                return false;
            }
            if (fwrite($this->handle, $data) !== false) {
                $success = true;
            }
            if ($this->lock !== null) {
                flock($this->handle, LOCK_UN);
            }
        }
        return $success;
    }

    /**
     * Append given data string to this file.
     *
     * @param string $data  Data to write
     * @param bool   $force Force the file to open
     *
     * @return bool whether the operation was successful
     */
    public function append($data, $force = false)
    {
        return $this->write($data, 'a', $force);
    }

    /**
     * Closes the current file if it is opened.
     *
     * @return bool whether closing was successful or file was already closed
     */
    public function close()
    {
        if (!is_resource($this->handle)) {
            return true;
        }
        return fclose($this->handle);
    }

    /**
     * Deletes the file.
     *
     * @return bool whether the operation was successful
     */
    public function delete()
    {
        if (is_resource($this->handle)) {
            fclose($this->handle);
            $this->handle = null;
        }
        if ($this->exists()) {
            return unlink($this->path);
        }
        return false;
    }

    /**
     * Returns the file info as an array with the following keys:
     *
     * - `dirname`
     * - `basename`
     * - `extension`
     * - `filename`
     * - `filesize`
     * - `mime`
     *
     * @return array File information.
     */
    public function info()
    {
        if (!$this->info) {
            $this->info = pathinfo($this->path);
        }
        if (!isset($this->info['filename'])) {
            $this->info['filename'] = $this->name();
        }
        if (!isset($this->info['filesize'])) {
            $this->info['filesize'] = $this->size();
        }
        if (!isset($this->info['mime'])) {
            $this->info['mime'] = $this->mime();
        }
        return $this->info;
    }

    /**
     * Returns the file extension.
     *
     * @return string|false the file extension, false if extension cannot be extracted.
     */
    public function ext()
    {
        if (!$this->info) {
            $this->info();
        }
        if (isset($this->info['extension'])) {
            return $this->info['extension'];
        }
        return false;
    }

    /**
     * Returns the file name without extension.
     *
     * @return string|false the file name without extension, false if name cannot be extracted.
     */
    public function name()
    {
        if (!$this->info) {
            $this->info();
        }
        if (isset($this->info['extension'])) {
            return static::basename($this->name, '.' . $this->info['extension']);
        }
        if ($this->name) {
            return $this->name;
        }
        return false;
    }

    /**
     * Makes file name safe for saving
     *
     * @param string|null $name The name of the file to make safe if different from $this->name
     * @param string|null $ext  The name of the extension to make safe if different from $this->ext
     *
     * @return string The extension of the file
     */
    public function safe($name = null, $ext = null)
    {
        if (!$name) {
            $name = $this->name;
        }
        if (!$ext) {
            $ext = $this->ext();
        }
        return preg_replace('/(?:[^\w\.-]+)/', '_', static::basename($name, $ext));
    }

    /**
     * Get md5 Checksum of file with previous check of `filesize`
     *
     * @param int|bool $maxsize in MB or true to force
     *
     * @return string|false md5 Checksum {@link https://secure.php.net/md5_file See md5_file()}, or false in case of an
     *     error
     */
    public function md5($maxsize = 5)
    {
        if ($maxsize === true) {
            return md5_file($this->path);
        }
        $size = $this->size();
        if ($size && $size < ($maxsize * 1024) * 1024) {
            return md5_file($this->path);
        }
        return false;
    }

    /**
     * Returns the full path of the file.
     *
     * @return string Full path to the file
     */
    public function pwd()
    {
        if ($this->path === null) {
            $dir = $this->folder->pwd();
            if (is_dir($dir)) {
                $this->path = $this->folder->slashTerm($dir) . $this->name;
            }
        }
        return $this->path;
    }

    /**
     * Returns true if the file exists.
     *
     * @return bool True if it exists, false otherwise
     */
    public function exists()
    {
        $this->clearStatCache();

        return (file_exists($this->path) && is_file($this->path));
    }

    /**
     * Returns the "chmod" (permissions) of the file.
     *
     * @return string|false Permissions for the file, or false in case of an error
     */
    public function perms()
    {
        if ($this->exists()) {
            return substr(sprintf('%o', fileperms($this->path)), -4);
        }
        return false;
    }

    /**
     * Returns the file size
     *
     * @return int|false Size of the file in bytes, or false in case of an error
     */
    public function size()
    {
        if ($this->exists()) {
            return filesize($this->path);
        }
        return false;
    }

    /**
     * Returns true if the file is writable.
     *
     * @return bool True if it's writable, false otherwise
     */
    public function writable()
    {
        return is_writable($this->path);
    }

    /**
     * Returns true if the File is executable.
     *
     * @return bool True if it's executable, false otherwise
     */
    public function executable()
    {
        return is_executable($this->path);
    }

    /**
     * Returns true if the file is readable.
     *
     * @return bool True if file is readable, false otherwise
     */
    public function readable()
    {
        return is_readable($this->path);
    }

    /**
     * Returns the file's owner.
     *
     * @return int|false The file owner, or false in case of an error
     */
    public function owner()
    {
        if ($this->exists()) {
            return fileowner($this->path);
        }
        return false;
    }

    /**
     * Returns the file's group.
     *
     * @return int|false The file group, or false in case of an error
     */
    public function group()
    {
        if ($this->exists()) {
            return filegroup($this->path);
        }
        return false;
    }

    /**
     * Returns last access time.
     *
     * @return int|false Timestamp of last access time, or false in case of an error
     */
    public function lastAccess()
    {
        if ($this->exists()) {
            return fileatime($this->path);
        }
        return false;
    }

    /**
     * Returns last modified time.
     *
     * @return int|false timestamp of last modification, or false in case of an error
     */
    public function lastChange()
    {
        if ($this->exists()) {
            return filemtime($this->path);
        }
        return false;
    }

    /**
     * Copy the file to `$dest`
     *
     * @param string $dest      destination file path for the copy
     * @param bool   $overwrite overwrite `$dest` if exists
     *
     * @return bool whether the operation was successful
     */
    public function copy($dest, $overwrite = true)
    {
        if (!$this->exists() || is_file($dest) && !$overwrite) {
            return false;
        }
        return copy($this->path, $dest);
    }

    /**
     * Gets the mime type of the file. Uses the `finfo` extension if available, otherwise falls back to
     * `mime_content_type()`.
     *
     * @return string|false the mimetype of the file, or `false` if reading fails.
     */
    public function mime()
    {
        if (!$this->exists()) {
            return false;
        }
        if (class_exists('finfo')) {
            $finfo = new finfo(FILEINFO_MIME);
            $type = $finfo->file($this->pwd());
            if (!$type) {
                return false;
            }
            list($type) = explode(';', $type);

            return $type;
        }
        if (function_exists('mime_content_type')) {
            return mime_content_type($this->pwd());
        }
        return false;
    }

    /**
     * Clear PHP's internal stat cache
     *
     * @param bool $all Clear all cache or not. Passing false will clear the stat cache for the current path only.
     *
     * @return void
     */
    public function clearStatCache($all = false)
    {
        if ($all === false) {
            clearstatcache(true, $this->path);
        }
        clearstatcache();
    }

    /**
     * Searches for a given text and replaces the text if found.
     *
     * @param string|array $search  Text(s) to search for.
     * @param string|array $replace Text(s) to replace with.
     *
     * @return bool whether the operation was successful
     */
    public function replaceText($search, $replace)
    {
        if (!$this->open('r+')) {
            return false;
        }
        if ($this->lock !== null && flock($this->handle, LOCK_EX) === false) {
            return false;
        }
        $replaced = $this->write(str_replace($search, $replace, $this->read()), 'w', true);
        if ($this->lock !== null) {
            flock($this->handle, LOCK_UN);
        }
        $this->close();
        return $replaced;
    }
}
