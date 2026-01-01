export interface Auth {
    user: User;
}

export interface SharedData {
    name: string;
    quote: { message: string; author: string };
    auth: Auth;
    [key: string]: unknown;
}

export interface User {
    id: number;
    name: string;
    email: string;
    avatar?: string;
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
    [key: string]: unknown; // This allows for additional properties...
}

export interface ProductSpecifications {
    // Hardware
    processor?: string;
    gpu?: string;
    ram?: string;
    storage?: string;
    display?: string;
    keyboard?: string;
    chipset?: string;
    optical_drive?: string;
    wireless_connectivity?: string;

    // Ports & Expansion
    expansion_slots?: string;
    external_ports?: string;

    // Physical
    dimensions_width?: string;
    dimensions_depth?: string;
    dimensions_height?: string;
    weight?: string;

    // Power
    battery?: string;
    power_supply_type?: string;

    // Multimedia
    webcam?: string;
    audio?: string;

    // Software
    operating_system?: string;
    software_included?: string;

    // Product Info
    product_number?: string;
    warranty?: string;
    condition?: 'new' | 'excellent' | 'good' | 'fair' | 'used-excellent' | 'used-very-good' | 'used-good';
    extras?: string;
    original_price?: string;
    features?: string;
}

export interface ProductImage {
    id: number;
    product_id: number;
    image_url: string;
    image_thumbnail_url?: string;
    is_primary: boolean;
    sort_order: number;
}

export interface Product {
    id: number;
    name: string;
    brand?: string;
    category_id: number;
    price: string | number;
    sku: string;
    stock: number;
    description: string;
    image_url: string;
    image_thumbnail_url?: string;
    images?: ProductImage[];
    specifications?: ProductSpecifications;
    created_at?: string;
    updated_at?: string;
}
