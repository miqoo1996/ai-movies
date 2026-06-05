<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class ShowImage extends Model
{
    protected $fillable = ['show_id', 'external_id', 'hashid', 'url', 'local_path', 'thumb', 'local_thumb', 'width', 'height', 'mime_type', 'collection'];

    public function show()
    {
        return $this->belongsTo(Show::class);
    }

    /**
     * Resolve the public URL for this image, handling two historical local_path formats:
     *   - Scraper:       'storage/show-images/{ext_id}/file.jpg'  (has storage/ prefix)
     *   - Admin upload:  'show-images/{show_id}/file.jpg'          (no prefix)
     */
    protected function imageUrl(): Attribute
    {
        return Attribute::get(function () {
            $local = $this->getRawOriginal('local_path');

            if ($local) {
                // Normalise to the disk-relative path (strip leading 'storage/' if present)
                $diskPath = str_starts_with($local, 'storage/')
                    ? substr($local, strlen('storage/'))
                    : $local;

                if (file_exists(storage_path('app/public/' . $diskPath))) {
                    return asset('storage/' . $diskPath);
                }
            }

            return $this->getRawOriginal('url');
        });
    }
}
