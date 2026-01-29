<?php

namespace Database\Seeders;

use App\Models\District;
use Illuminate\Database\Seeder;

class DistrictSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Seeds all 18 districts of Hong Kong.
     */
    public function run(): void
    {
        $districts = [
            // Hong Kong Island (香港島)
            [
                'name_zh_tw' => '中西區',
                'name_zh_cn' => '中西区',
                'name_en' => 'Central and Western',
                'region' => 'hong_kong_island',
            ],
            [
                'name_zh_tw' => '灣仔區',
                'name_zh_cn' => '湾仔区',
                'name_en' => 'Wan Chai',
                'region' => 'hong_kong_island',
            ],
            [
                'name_zh_tw' => '東區',
                'name_zh_cn' => '东区',
                'name_en' => 'Eastern',
                'region' => 'hong_kong_island',
            ],
            [
                'name_zh_tw' => '南區',
                'name_zh_cn' => '南区',
                'name_en' => 'Southern',
                'region' => 'hong_kong_island',
            ],

            // Kowloon (九龍)
            [
                'name_zh_tw' => '油尖旺區',
                'name_zh_cn' => '油尖旺区',
                'name_en' => 'Yau Tsim Mong',
                'region' => 'kowloon',
            ],
            [
                'name_zh_tw' => '深水埗區',
                'name_zh_cn' => '深水埗区',
                'name_en' => 'Sham Shui Po',
                'region' => 'kowloon',
            ],
            [
                'name_zh_tw' => '九龍城區',
                'name_zh_cn' => '九龙城区',
                'name_en' => 'Kowloon City',
                'region' => 'kowloon',
            ],
            [
                'name_zh_tw' => '黃大仙區',
                'name_zh_cn' => '黄大仙区',
                'name_en' => 'Wong Tai Sin',
                'region' => 'kowloon',
            ],
            [
                'name_zh_tw' => '觀塘區',
                'name_zh_cn' => '观塘区',
                'name_en' => 'Kwun Tong',
                'region' => 'kowloon',
            ],

            // New Territories (新界)
            [
                'name_zh_tw' => '葵青區',
                'name_zh_cn' => '葵青区',
                'name_en' => 'Kwai Tsing',
                'region' => 'new_territories',
            ],
            [
                'name_zh_tw' => '荃灣區',
                'name_zh_cn' => '荃湾区',
                'name_en' => 'Tsuen Wan',
                'region' => 'new_territories',
            ],
            [
                'name_zh_tw' => '屯門區',
                'name_zh_cn' => '屯门区',
                'name_en' => 'Tuen Mun',
                'region' => 'new_territories',
            ],
            [
                'name_zh_tw' => '元朗區',
                'name_zh_cn' => '元朗区',
                'name_en' => 'Yuen Long',
                'region' => 'new_territories',
            ],
            [
                'name_zh_tw' => '北區',
                'name_zh_cn' => '北区',
                'name_en' => 'North',
                'region' => 'new_territories',
            ],
            [
                'name_zh_tw' => '大埔區',
                'name_zh_cn' => '大埔区',
                'name_en' => 'Tai Po',
                'region' => 'new_territories',
            ],
            [
                'name_zh_tw' => '沙田區',
                'name_zh_cn' => '沙田区',
                'name_en' => 'Sha Tin',
                'region' => 'new_territories',
            ],
            [
                'name_zh_tw' => '西貢區',
                'name_zh_cn' => '西贡区',
                'name_en' => 'Sai Kung',
                'region' => 'new_territories',
            ],
            [
                'name_zh_tw' => '離島區',
                'name_zh_cn' => '离岛区',
                'name_en' => 'Islands',
                'region' => 'new_territories',
            ],
        ];

        foreach ($districts as $district) {
            District::create($district);
        }
    }
}
