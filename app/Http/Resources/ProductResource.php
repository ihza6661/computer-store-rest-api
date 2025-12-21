<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'price' => $this->price,
            'sku' => $this->sku,
            'stock' => $this->stock,
            'image_url' => $this->transformImageUrl($this->image_url),
            'image_thumbnail_url' => $this->transformImageUrl($this->image_thumbnail_url),
            'category_id' => $this->category_id,
            'category' => $this->whenLoaded('category'),
            'specifications' => $this->specifications,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

    /**
     * Transform relative image URLs to absolute URLs.
     * Leaves external URLs (Cloudinary, etc.) unchanged.
     *
     * @param string|null $url
     * @return string|null
     */
    private function transformImageUrl(?string $url): ?string
    {
        if (!$url) {
            return null;
        }

        // If URL already starts with http:// or https://, return as-is (e.g., Cloudinary)
        if (str_starts_with($url, 'http://') || str_starts_with($url, 'https://')) {
            return $url;
        }

        // For relative URLs (local storage), prepend APP_URL
        return url($url);
    }
}
