<?php
// Keep default image sizes (remove your old filter entirely, or keep this returning $sizes)
add_filter('intermediate_image_sizes_advanced', function ($sizes) {
    return [];
});

// Make generated sub-sizes WebP too (for safety across WP versions/editors)
add_filter('image_editor_output_format', function ($formats) {
    $formats['image/jpeg'] = 'image/webp';
    $formats['image/png']  = 'image/webp';
    $formats['image/gif']  = 'image/webp';
    return $formats;
});

// Optional: control default image quality (affects GD + Imagick)
add_filter('wp_editor_set_quality', function($quality) {
    return 80; // tweak if you want
});

// Convert the original upload to WebP, then let WP do its normal thing.
add_filter('wp_handle_upload', function ($upload) {
    $file_path = $upload['file'];
    $filetype  = wp_check_filetype($file_path);

    // Only images, and skip if already WebP
    if (strpos((string)$filetype['type'], 'image/') !== 0 || $filetype['ext'] === 'webp') {
        return $upload;
    }

    $dir           = wp_normalize_path(dirname($file_path));
    $name          = pathinfo($file_path, PATHINFO_FILENAME);
    $webp_basename = wp_unique_filename($dir, $name . '.webp');
    $webp_path     = trailingslashit($dir) . $webp_basename;
    $quality       = 80;
    $saved         = false;

    // --- Prefer Imagick if available (better color/profiles/alpha handling)
    if (class_exists('Imagick')) {
        try {
            $im = new Imagick($file_path);
            // Keep orientation/profile as best as possible
            if (method_exists($im, 'autoOrient')) {
                $im->autoOrient();
            }
            $im->setImageFormat('webp');
            $im->setImageCompressionQuality($quality);
            $im->setBackgroundColor(new ImagickPixel('transparent'));
            $im->writeImage($webp_path);
            $im->clear();
            $im->destroy();
            $saved = file_exists($webp_path);
        } catch (Exception $e) {
            $saved = false;
        }
    }

    // --- Fallback to GD
    if (!$saved && function_exists('imagewebp')) {
        switch ($filetype['ext']) {
            case 'jpeg':
            case 'jpg':
                $img = @imagecreatefromjpeg($file_path);
                break;
            case 'png':
                $img = @imagecreatefrompng($file_path);
                if ($img && function_exists('imagepalettetotruecolor')) {
                    imagepalettetotruecolor($img);
                }
                if ($img) {
                    imagealphablending($img, true);
                    imagesavealpha($img, true);
                }
                break;
            case 'gif':
                $img = @imagecreatefromgif($file_path);
                break;
            default:
                $img = false;
        }

        if ($img) {
            $saved = imagewebp($img, $webp_path, $quality);
            imagedestroy($img);
        }
    }

    // If conversion succeeded, replace the original in the upload array
    if ($saved) {
        @unlink($file_path); // delete original ONLY after success

        $upload['file'] = $webp_path;
        $upload['url']  = trailingslashit(dirname($upload['url'])) . $webp_basename;
        $upload['type'] = 'image/webp';
    }

    return $upload;
}, 12);
