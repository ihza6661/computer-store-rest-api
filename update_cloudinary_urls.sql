-- Update product images to Cloudinary CDN URLs
-- Generated: 2025-12-21
-- This script updates all 7 products with their Cloudinary CDN image URLs

UPDATE products SET image_url = 'https://res.cloudinary.com/drcy0kzdm/image/upload/v1766311990/r-tech-products/zvuoi8p9tulelucbjpi8.jpg', image_thumbnail_url = 'https://res.cloudinary.com/drcy0kzdm/image/upload/v1766311990/r-tech-products/zvuoi8p9tulelucbjpi8.jpg' WHERE id = 1;
UPDATE products SET image_url = 'https://res.cloudinary.com/drcy0kzdm/image/upload/v1766311994/r-tech-products/scb7kobsdwfr0r4es389.jpg', image_thumbnail_url = 'https://res.cloudinary.com/drcy0kzdm/image/upload/v1766311994/r-tech-products/scb7kobsdwfr0r4es389.jpg' WHERE id = 2;
UPDATE products SET image_url = 'https://res.cloudinary.com/drcy0kzdm/image/upload/v1766311997/r-tech-products/ltnhslfg95oeyve5zeot.jpg', image_thumbnail_url = 'https://res.cloudinary.com/drcy0kzdm/image/upload/v1766311997/r-tech-products/ltnhslfg95oeyve5zeot.jpg' WHERE id = 3;
UPDATE products SET image_url = 'https://res.cloudinary.com/drcy0kzdm/image/upload/v1766312004/r-tech-products/jfyludkwm0l7wrko58i9.jpg', image_thumbnail_url = 'https://res.cloudinary.com/drcy0kzdm/image/upload/v1766312004/r-tech-products/jfyludkwm0l7wrko58i9.jpg' WHERE id = 4;
UPDATE products SET image_url = 'https://res.cloudinary.com/drcy0kzdm/image/upload/v1766312013/r-tech-products/vwp1dth0z1ofudnlqgzi.jpg', image_thumbnail_url = 'https://res.cloudinary.com/drcy0kzdm/image/upload/v1766312013/r-tech-products/vwp1dth0z1ofudnlqgzi.jpg' WHERE id = 5;
UPDATE products SET image_url = 'https://res.cloudinary.com/drcy0kzdm/image/upload/v1766312023/r-tech-products/rsmsztfqreldj9czimmi.jpg', image_thumbnail_url = 'https://res.cloudinary.com/drcy0kzdm/image/upload/v1766312023/r-tech-products/rsmsztfqreldj9czimmi.jpg' WHERE id = 6;
UPDATE products SET image_url = 'https://res.cloudinary.com/drcy0kzdm/image/upload/v1766312028/r-tech-products/hgkdkh4ekt24kxektyv1.jpg', image_thumbnail_url = 'https://res.cloudinary.com/drcy0kzdm/image/upload/v1766312028/r-tech-products/hgkdkh4ekt24kxektyv1.jpg' WHERE id = 7;

-- Verify the update
SELECT id, name, image_url FROM products ORDER BY id;
