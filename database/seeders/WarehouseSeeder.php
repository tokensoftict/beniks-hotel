<?php

namespace Database\Seeders;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class WarehouseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('warehousestores')->insert(
            [
                [
                    'name'=>'Store',
                    'packed_column'=>'quantity',
                    'yard_column'=>'yard_quantity',
                    'type'=>'SHOP',
                    'default'=>1,
                    'status'=>1
                ],
                [
                    'name'=>'Restaurant',
                    'packed_column'=>'restaurant',
                    'yard_column'=>'yard_restaurant',
                    'type'=>'SHOP',
                    'default'=>0,
                    'status'=>1
                ],
                [
                    'name'=>'Bar',
                    'packed_column'=>'bar',
                    'yard_column'=>'yard_bar',
                    'type'=>'SHOP',
                    'default'=>0,
                    'status'=>1
                ],
                [
                    'name'=>'Laundry',
                    'packed_column'=>'laundry',
                    'yard_column'=>'yard_laundry',
                    'type'=>'SERVICE',
                    'default'=>0,
                    'status'=>1
                ],
            ]
        );

        getActiveStore(true);

        $columns = [
            [
                'name'=>'Restaurant',
                'packed_column'=>'restaurant',
                'yard_column'=>'yard_restaurant',
                'type'=>'SHOP',
                'default'=>0,
                'status'=>1
            ],
            [
                'name'=>'Bar',
                'packed_column'=>'bar',
                'yard_column'=>'yard_bar',
                'type'=>'SHOP',
                'default'=>0,
                'status'=>1
            ],
            [
                'name'=>'Laundry',
                'packed_column'=>'laundry',
                'yard_column'=>'yard_laundry',
                'type'=>'SHOP',
                'default'=>0,
                'status'=>1
            ]
        ];

        foreach ($columns as $column)
        {
            Schema::table("stockbatches", function (Blueprint $table) use(& $column) {
                $table->bigInteger($column['packed_column'])->default(0)->after("quantity");
                $table->bigInteger($column['yard_column'])->default(0)->after("quantity");
            });
        }

    }
}
