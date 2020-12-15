<?php declare(strict_types=1);

namespace Tolkam\Base\Domain\Value;

trait JsonableTrait
{
    /**
     * Encodes value to JSON
     *
     * @param     $value
     * @param int $options
     * @param int $depth
     *
     * @return string
     */
    protected static function jsonEncode($value, int $options = 0, int $depth = 512): string
    {
        $encoded = json_encode($value, $options | JSON_UNESCAPED_UNICODE, $depth);
        
        if (json_last_error()) {
            throw new ValueException(sprintf(
                'Failed to encode JSON: %s',
                json_last_error_msg()
            ));
        }
        
        return $encoded;
    }
    
    /**
     * Decodes from JSON string
     *
     * @param string $value
     * @param int    $options
     * @param int    $depth
     *
     * @return mixed
     */
    protected static function jsonDecode(
        string $value,
        int $options = JSON_OBJECT_AS_ARRAY,
        int $depth = 512
    ) {
        $decoded = json_decode($value, null, $depth, $options);
        
        if (json_last_error()) {
            throw new ValueException(sprintf(
                'Failed to decode JSON: %s',
                json_last_error_msg()
            ));
        }
        
        return $decoded;
    }
    
}
