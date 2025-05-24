<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Variation\Entities\Option;

class OptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::beginTransaction();
        try {
            $items = [
                ['status' => 1, 'option_as_filter' => 1, 'flag' => 'colors', 'translations' => ['title' => [
                    'ar' => 'اللون', 'en' => 'Color']
                ], 'values' => [
                    ['status' => 1, 'color' => '#FFFFFF', 'translations' => ['title' => ['ar' => 'ابيض', 'en' => 'White',]]],
                    ['status' => 1, 'color' => '#ffff00', 'translations' => ['title' => ['ar' => 'اصفر', 'en' => 'Yellow',]]],
                    ['status' => 1, 'color' => '#ff0000', 'translations' => ['title' => ['ar' => 'احمر', 'en' => 'Red',]]],
                    ['status' => 1, 'color' => '#000000', 'translations' => ['title' => ['ar' => 'اسود', 'en' => 'Black',]]],
                ]],
                ['status' => 1, 'option_as_filter' => 1, 'flag' => 'sizes', 'translations' => ['title' => [
                    'ar' => 'الحجم', 'en' => 'Size']
                ], 'values' => [
                    ['status' => 1, 'translations' => ['title' => ['ar' => '4', 'en' => '4',]]],
                    ['status' => 1, 'translations' => ['title' => ['ar' => '5', 'en' => '5',]]],
                    ['status' => 1, 'translations' => ['title' => ['ar' => '6', 'en' => '6',]]],
                ]],
            ];
            foreach ($items as $k => $value) {
                $translations = $value['translations'];
                $option_values = $value['values'];
                unset($value['translations']);
                unset($value['values']);
                $option = Option::create($value);
                $this->translateTable($option, $translations);
                foreach ($option_values as $i => $v) {
                    $v_translations = $v['translations'];
                    unset($v['translations']);
                    $optionValue = $option->values()->create($v);
                    $this->translateTable($optionValue, $v_translations);
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    private function translateTable($model, $translations)
    {
        foreach ($translations['title'] as $locale => $value) {
            $model->translateOrNew($locale)->title = $value;
        }
        $model->save();
    }

}
