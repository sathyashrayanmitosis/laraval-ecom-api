<?php


namespace Mitosis\Support\Traits;

/**
 * Buyable image interface compatibility for Buyable models without images
 */
trait BuyableNoImage
{
    /**
     * Returns whether the item has an image
     *
     * @return bool
     */
    public function hasImage()
    {
        return false;
    }

    /**
     * Returns the URL of the item's thumbnail image, or null if there's no image
     *
     * @return string|null
     */
    public function getThumbnailUrl()
    {
        return null;
    }

    /**
     * Returns the URL of the item's (main) image, or null if there's no image
     *
     * @return string|null
     */
    public function getImageUrl()
    {
        return null;
    }
}
