<?php declare(strict_types=1);

namespace Tolkam\Layers\Base\Domain\Rule;

use SplFileInfo;
use Tolkam\Rules\FailureInterface;
use Tolkam\Rules\Rule;

class Image extends Rule
{
    /**
     * @var int
     */
    protected int $maxFileSize = (1024 * 1024) * 4;
    
    /**
     * @var array
     */
    protected array $mimeTypes = [
        'image/jpeg',
        'image/png',
        'image/gif',
    ];
    
    /**
     * @param int|null $maxFileSize
     * @param array    $mimeTypes
     */
    public function __construct(int $maxFileSize = null, array $mimeTypes = [])
    {
        if (!empty($maxFileSize)) {
            $this->maxFileSize = $maxFileSize;
        }
        
        if (!empty($mimeTypes)) {
            $this->mimeTypes = $mimeTypes;
        }
    }
    
    /**
     * @inheritDoc
     */
    public function apply($value): ?FailureInterface
    {
        $failure = null;
        
        if (!$value instanceof SplFileInfo) {
            $failure = ['invalid.input', 'Invalid input'];
        }
        
        $maxFileSize = $this->maxFileSize;
        if ($value->getSize() > $maxFileSize) {
            $failure = [
                'tooLarge',
                'File is too large',
                compact('maxFileSize'),
            ];
        }
        
        $mimeType = image_type_to_mime_type(exif_imagetype($value->getRealPath()));
        if (!in_array($mimeType, $this->mimeTypes)) {
            $failure = ['invalid.mimeType', 'Invalid file type', compact('mimeType')];
        }
        
        return $failure !== null ? $this->failure(...$failure) : $failure;
    }
}
