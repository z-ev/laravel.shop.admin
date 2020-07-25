<?php

    use Illuminate\Database\Seeder;

    class AttributeProductsSeeder extends Seeder
    {
        /**
         * Run the database seeds.
         *
         * @return void
         */
        public function run()
        {
            $data = [];


            for ($y = 1; $y <= 12; $y+=4){
                for ($i = 1; $i <= 17; $i++) {
                    $data[] = [
                        'product_id' => $i,
                        'attr_id' => $y,
                    ];
                }
            }


            DB::table('attribute_products')->insert($data);
        }
    }
