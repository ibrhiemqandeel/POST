<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * الفئات الأربع المستخدمة فعلياً في الموقع (Nav + Routes الحالية):
     * Women / Kids / Beauty / Accessories. الـ slug يطابق أسماء الـ routes
     * الموجودة (women, kids, beauty, accessories) لتسهيل الربط بينها.
     */
    public function run(): void
    {
        $categories = [
            ['slug' => 'women',       'name' => 'Women'],
            ['slug' => 'kids',        'name' => 'Children'],
            ['slug' => 'beauty',      'name' => 'Beauty'],
            ['slug' => 'accessories', 'name' => 'Accessories'],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(['slug' => $category['slug']], $category);
        }
    }
}
