<?php

namespace Database\Seeders;

use App\Models\Contact;
use Illuminate\Database\Seeder;

class ContactSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Sales Inquiry Contacts
        Contact::create([
            'name' => 'John Smith',
            'email' => 'john.smith@company.com',
            'phone' => '+62-812-3456-7890',
            'category' => 'sales_inquiry',
            'message' => 'Hi, I\'m interested in purchasing 5 Dell XPS 13 Plus laptops for our office. Could you provide bulk discount pricing and delivery options to Jakarta? We need them by next month.',
            'status' => 'new',
        ]);

        Contact::create([
            'name' => 'Sarah Johnson',
            'email' => 'sarah.j@creative.studio',
            'phone' => '+62-811-9876-5432',
            'category' => 'sales_inquiry',
            'message' => 'We\'re looking for high-end monitors for our creative studio. Can you tell me more about the LG UltraWide 38" curved monitor? Also interested in knowing about volume discounts for 10+ units.',
            'status' => 'read',
        ]);

        Contact::create([
            'name' => 'Michael Chen',
            'email' => 'michael.chen@startup.io',
            'phone' => null,
            'category' => 'sales_inquiry',
            'message' => 'Hello, I\'m setting up a new startup office with 20 employees. We need complete workstation setups including desks, chairs, monitors, and peripherals. Could someone contact me to discuss requirements?',
            'status' => 'read',
        ]);

        // Tech Support Contacts
        Contact::create([
            'name' => 'David Wilson',
            'email' => 'david.wilson@email.com',
            'phone' => '+62-813-5555-1234',
            'category' => 'tech_support',
            'message' => 'I recently purchased a Corsair K95 mechanical keyboard and the RGB lighting isn\'t working properly. The left side of the keyboard isn\'t lighting up. I\'ve already tried restarting the computer. What should I do?',
            'status' => 'new',
        ]);

        Contact::create([
            'name' => 'Emily Rodriguez',
            'email' => 'emily.r@freelance.com',
            'phone' => '+62-815-7777-8899',
            'category' => 'tech_support',
            'message' => 'I have a Dell U2724D monitor that\'s showing some dead pixels on the screen. Is this covered under warranty? How do I file a warranty claim?',
            'status' => 'new',
        ]);

        Contact::create([
            'name' => 'Alex Thompson',
            'email' => 'alex.thompson@student.edu',
            'phone' => '+62-817-2222-3333',
            'category' => 'tech_support',
            'message' => 'My MacBook Pro 14" M3 Pro is running slowly since I updated to the latest OS. Performance has decreased significantly. Is this a known issue? Any solutions available?',
            'status' => 'read',
            'admin_reply' => 'Thank you for contacting us. This issue is sometimes caused by indexing processes in the background. Try restarting your Mac and check System Preferences > Siri & Spotlight to make sure all necessary items are indexed. If the issue persists, please bring the laptop in for a diagnostic check. We offer free diagnostics at our service centers.',
        ]);

        // General Inquiries
        Contact::create([
            'name' => 'Lisa Anderson',
            'email' => 'lisa.anderson@company.org',
            'phone' => null,
            'category' => 'general',
            'message' => 'I\'m writing a blog post about the best laptops for remote workers. Can I feature some of your products and use product images in my article? I\'d like to include links to your store as well.',
            'status' => 'read',
            'admin_reply' => 'Hi Lisa, thank you for your interest in featuring our products! We\'d be happy to have our laptops featured in your blog. You can use the product images from our website for your article. Please include attribution and links back to our product pages. Feel free to reach out if you need any additional product information or high-resolution images.',
        ]);

        Contact::create([
            'name' => 'Robert Martinez',
            'email' => 'robert.m@consultant.biz',
            'phone' => '+62-812-6666-9999',
            'category' => 'general',
            'message' => 'Do you offer corporate partnerships or reseller programs? We\'re interested in selling your products through our B2B technology consulting firm.',
            'status' => 'new',
        ]);

        Contact::create([
            'name' => 'Jessica Lee',
            'email' => 'jessica.lee@nonprofit.org',
            'phone' => '+62-814-3333-4444',
            'category' => 'general',
            'message' => 'Our non-profit organization works with underprivileged youth and teaches them coding skills. Do you offer any educational discounts or donation programs? We\'re looking to equip our computer labs.',
            'status' => 'read',
            'admin_reply' => 'Hi Jessica, we truly appreciate the important work your organization does. We do offer special pricing for educational institutions and non-profit organizations. Please send us details about your organization and we\'ll work with you on a customized quote. You can also visit our educational partners page for more information.',
        ]);

        Contact::create([
            'name' => 'Marcus Thompson',
            'email' => 'marcus.t@gamer.net',
            'phone' => '+62-816-1111-2222',
            'category' => 'general',
            'message' => 'Hey, I\'m a content creator on YouTube and I review gaming equipment. I\'d like to do unboxing and review videos of your gaming monitors and peripherals. Can we work out a review unit arrangement?',
            'status' => 'new',
        ]);

        Contact::create([
            'name' => 'Sophie Wang',
            'email' => 'sophie.wang@agency.co.id',
            'phone' => null,
            'category' => 'general',
            'message' => 'We\'re redesigning our website and would like to use your products in our photography portfolio. Can we get permission to feature some product photos on our site with proper attribution?',
            'status' => 'read',
            'admin_reply' => 'Hi Sophie, thank you for your interest! We\'d love to see our products featured in your portfolio. You have permission to use product images for portfolio purposes with proper attribution and links to our website. Please feel free to reach out if you need specific high-resolution images or any other assistance.',
        ]);
    }
}
