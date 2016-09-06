<?php
/**
 * Europa Re
 *
 * @author      Arber Mustafa <arber9@gmail.com>
 * @version     1.0.0
 * @package     App
 */
namespace App\Validation\Upload;

use \Upload\Validation\Base;

class ImageSize extends Base
{
    protected $sizes = array(
        'minWidth'  => null,
        'maxWidth'  => null,
        'minHeight' => null,
        'maxHeight' => null
    );
    protected $width;
    protected $height;
    protected $message = 'Invalid image dimensions. Image must be at least of size %s px in width and %s px in height';

    public function __construct($sizes)
    {
        if (is_array($sizes)) {
            $this->sizes = array_merge($this->sizes, $sizes);
        }
    }

    public function validate(\Upload\File $file)
    {
        $imageDimensions = $file->getDimensions();
        $isValid = true;


        $this->width  = $imageDimensions['width'];
        $this->height = $imageDimensions['height'];

        if ($this->width < $this->sizes['minWidth']) {
            $isValid = false;
        }

        if (($this->sizes['maxWidth'] !== null) && ($this->sizes['maxWidth'] < $this->width)) {
            $isValid = false;
        }

        if ($this->height < $this->sizes['minHeight']) {
            $isValid = false;
        }

        if (($this->sizes['maxHeight'] !== null) && ($this->sizes['maxHeight'] < $this->height)) {
            $isValid = false;
        }

        if (!$isValid) {
            $this->setMessage(sprintf($this->message, $this->sizes['minWidth'], $this->sizes['minHeight']));
        }

        return $isValid;
    }
}
