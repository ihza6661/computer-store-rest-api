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
        // Ensure specifications is properly decoded as array
        // Handle both single-encoded and double-encoded JSON strings
        $specifications = $this->specifications;
        
        if (is_string($specifications)) {
            // First decode attempt
            $decoded = json_decode($specifications, true);
            
            // If result is still a string (double-encoded), decode again
            if (is_string($decoded)) {
                $specifications = json_decode($decoded, true) ?? [];
            } else {
                $specifications = $decoded ?? [];
            }
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'brand' => $this->brand,
            'description' => $this->description,
            'price' => $this->price,
            'sku' => $this->sku,
            'stock' => $this->stock,
            'image_url' => $this->transformImageUrl($this->image_url),
            'image_thumbnail_url' => $this->transformImageUrl($this->image_thumbnail_url),
            'category_id' => $this->category_id,
            'category' => $this->whenLoaded('category'),
            'specifications' => $specifications ?? [],
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

    /**
     * Transform relative image URLs to absolute URLs.
     * Leaves external URLs (Cloudinary, etc.) unchanged.
     */
    private function transformImageUrl(?string $url): ?string
    {
        if (! $url) {
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
