<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use http\Env\Request;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * Class Stock
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $description
 * @property string|null $code
 * @property int|null $product_category_id
 * @property int|null $manufacturer_id
 * @property float|null $selling_price
 * @property float|null $cost_price
 * @property string|null $barcode
 * @property string|null $image
 * @property string|null $location
 * @property string $type
 * @property bool $expiry
 * @property bool $status
 * @property int|null $user_id
 * @property int|null $last_updated_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property User|null $user
 * @property User|null $last_updated
 * @property Manufacturer|null $manufacturer
 * @property ProductCategory|null $product_category
 * @property Collection|Stockbatch[] $stockbatches
 *
 * @package App\Models
 */
class Stock extends Model
{
    use LogsActivity;

    protected $table = 'stocks';

    protected $casts = [
        'product_category_id' => 'int',
        'manufacturer_id' => 'int',
        'selling_price' => 'float',
        'cost_price' => 'float',
        'vip_selling_price'=>'float',
        'expiry' => 'bool',
        'status' => 'bool',
        'user_id' => 'int',
        'last_updated_by' => 'int'
    ];

    protected $fillable = [
        'name',
        'description',
        'code',
        'product_category_id',
        'manufacturer_id',
        'selling_price',
        'cost_price',
        'vip_selling_price',
        'yard_selling_price',
        'yard_cost_price',
        'barcode',
        'location',
        'image',
        'type',
        'expiry',
        'status',
        'user_id',
        'last_updated_by',
        'vvip_selling_price',
        'executive_selling_price'
    ];

    public static $validation = [
        'name' =>'required',
        'selling_price'=>'required',
        'status'=>'required',
        'type'=>'required'
    ];


    public static $field = [
        'name',
        'description',
        'code',
        'image',
        'product_category_id',
        'manufacturer_id',
        'selling_price',
        'vip_selling_price',
        'cost_price',
        'yard_selling_price',
        'yard_cost_price',
        'barcode',
        'location',
        'type',
        'expiry',
        'status',
        'user_id',
        'last_updated_by',
        'vvip_selling_price',
        'executive_selling_price'
    ];
    protected $appends = ['available_quantity','available_yard_quantity'];

    protected static function boot()
    {
        parent::boot(); // TODO: Change the autogenerated stub

        self::creating(function($stock){

            $stock->user_id = auth()->id();

            $stock->last_updated_by = auth()->id();
        });

        self::saved(function($stock){
            if(\request()->has('departments')) {
                $departments = request()->departments;
                StockDepartmentMapper::where('stock_id', $stock->id)->delete();
                StockDepartmentMapper::create(['stock_id' => $stock->id, 'warehousestore_id' => 1]);
                foreach ($departments as $department) {
                    StockDepartmentMapper::updateOrCreate(['stock_id' => $stock->id, 'warehousestore_id' => $department], ['stock_id' => $stock->id, 'warehousestore_id' => $department]);
                }
            }
        });

        self::updating(function ($stock){
            $stock->last_updated_by =  auth()->id();

        });

    }


    public function getImageAttribute(){

        if(!isset($this->attributes['name']))  return asset('assets/products.jpg');;

        foreach (['JPG','jpg','PNG','png','JPEG','jpeg'] as $extension)
        {
            if (is_file(public_path('product_image/' . $this->attributes['name'] . ".".$extension))) {
                $ext = pathinfo(public_path('product_image/' . $this->attributes['name'] . ".png"), PATHINFO_EXTENSION);
                return asset('product_image/' . $this->attributes['name'] . '.' . $ext);
            }
        }
        if($this->attributes['image'] == NULL)
            return asset('assets/products.jpg');


        if($this->attributes['image'] != NULL)
            return asset('product_image/'.$this->attributes['image']);


        return asset('assets/products.jpg');
    }

    public function stockDepartmentMapper()
    {
        return $this->hasMany(StockDepartmentMapper::class, 'stock_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function last_updated()
    {
        return $this->belongsTo(User::class,'last_updated_by');
    }


    public function manufacturer()
    {
        return $this->belongsTo(Manufacturer::class);
    }

    public function product_category()
    {
        return $this->belongsTo(ProductCategory::class);
    }

    public function stockbatches()
    {
        return $this->hasMany(Stockbatch::class);
    }


    public function invoice_item_batches()
    {
        return $this->hasMany(InvoiceItemBatch::class);
    }

    public function invoice_items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function getCustomPackedStockQuantity($store_id)
    {
        if(!$this->stockBatches()->exists()) return 0;

        $store = Warehousestore::find($store_id);

        return $this->stockBatches()->where($store->packed_column, ">", "0")->sum($store->packed_column);
    }

    public function getCustomYardStockQuantity($store_id)
    {
        if(!$this->stockBatches()->exists()) return 0;

        $store = Warehousestore::find($store_id);

        return $this->stockBatches()->where($store->yard_column, ">", "0")->sum($store->yard_column);
    }

    public function getAllAvailableQuantity(){
        $departments = getStores();
        $qty_store = [];
        foreach ($departments as $department){
            $qty_store[$department->id] = $this->stockBatches()->where($department->packed_column, ">", "0")->sum($department->packed_column);
        }

        return $qty_store;
    }

    public function getAvailableQuantityAttribute(){
        if(!$this->stockBatches()->exists()) return 0;
        $store = getActiveStore();
        return $this->stockBatches()->where($store->packed_column, ">", "0")->sum($store->packed_column);
    }
//available_yard_quantity
    public function getAvailableYardQuantityAttribute(){
        if(!$this->stockBatches()->exists()) return 0;
        $store = getActiveStore();
        return $this->stockBatches()->where($store->yard_column, ">", "0")->sum($store->yard_column);
    }

    public function getRecentBatchesForReturn($from, $quantity)
    {
        $batch_ids = [];
        if(!$this->stockBatches()->exists()) return false;
        $stockbatches = $this->stockBatches()->orderBy("expiry_date", "ASC")->limit(1)->get();
        foreach($stockbatches as $batch) {
            $b = $batch->toArray();
            $b['qty'] = $quantity;
            $b['from'] = $from;
            $batch_ids[$batch->id] =$b;
        }
        return $batch_ids;
    }


    public function pingSaleableBatches($from, $quantity){
        $batch_ids = [];
        if(!$this->stockBatches()->exists()) return false;
        $stockbatches = $this->stockBatches()->where($from, ">", "0")->orderBy("expiry_date", "ASC")->get();

        if ($stockbatches->count() == 0) return false;

        foreach($stockbatches as $batch) {
            if($batch->{$from} - $quantity < 0){
                $quantity = $quantity - $batch->{$from};
                $b = $batch->toArray();
                $b['qty'] = $batch->{$from};
                $b['from'] = $from;
                $batch_ids[$batch->id] =$b;
            }else{
                $batch->{$from} = $batch->{$from} - $quantity;
                $b = $batch->toArray();
                $b['qty'] = $quantity;
                $b['from'] = $from;
                $batch_ids[$batch->id] = $b;
                $quantity = 0;
            }
            if($quantity === 0)  return $batch_ids;
        }

        if($quantity != 0) return false;

        if($quantity == 0) return $batch_ids;

        return false;
    }



    public function getSaleableBatches($from, $quantity){
        $batch_ids = [];
        if(!$this->stockBatches()->exists()) return false;
        $stockbatches = $this->stockBatches()->where($from, ">", "0")->orderBy("expiry_date", "ASC")->get();

        if ($stockbatches->count() == 0) return false;

        foreach($stockbatches as $batch) {
            if($batch->{$from} - $quantity < 0){
                $quantity = $quantity - $batch->{$from};
                $b = $batch->toArray();
                $b['qty'] = $batch->{$from};
                $b['from'] = $from;
                $batch_ids[$batch->id] =$b;
            }else{
                $batch->{$from} = $batch->{$from} - $quantity;
                $b = $batch->toArray();
                $b['qty'] = $quantity;
                $b['from'] = $from;
                $batch_ids[$batch->id] = $b;
                $quantity = 0;
            }
            if($quantity === 0)  return $batch_ids;
        }

        if($quantity != 0) return false;

        if($quantity == 0) return $batch_ids;

        return false;
    }

    public function removeSaleableBatches($batches){
        foreach ($batches as $key=>$batch){
            $stockbatch = Stockbatch::find($key);
            $stockbatch->{$batch['from']} =   $stockbatch->{$batch['from']} - $batch['qty'];
            $stockbatch->update();
        }
    }

    public function addSaleableBatches($batches, $to_where){
        foreach ($batches as $key=>$batch){
            $stockbatch = Stockbatch::find($key);
            $stockbatch->{$to_where} =   $stockbatch->{$to_where} + $batch['qty'];
            $stockbatch->update();
        }
    }


    public static function convertStock($request){

        $in_bundle = $request->tt_bundle;

        $num_qty_to_convert = $request->tt_convert;

        $stock = Stock::find($request->stock_id);

        $stock->yard_selling_price = $request->yard_selling_price;

        $stock->yard_cost_price = $request->yard_cost_price;

        $stock->update();

        $batches = $stock->getSaleableBatches(getActiveStore()->packed_column, $num_qty_to_convert);

        foreach ($batches as $key=>$batch){
            $b = Stockbatch::find($key);
            $b->yard_cost_price = $request->yard_cost_price;
            $b->yard_qty += ($in_bundle * $batch['qty']);
            $b->update();
        }

        $stock->removeSaleableBatches($batches);

        return redirect()->route('stockmanager.convert')->with('success','Stock has been converted to pieces successfully!');
    }



    public static function adjustStockQuantity($request)
    {
        $stock = Stock::findorfail($request->stock_id);

        foreach ($stock->stockBatches()->get() as $batch)
        {
            $batch->{ getActiveStore()->packed_column } = 0;
            $batch->{ getActiveStore()->yard_column } = 0;

            $batch->update();
        }

        $recentBatch = $stock->stockBatches()->orderBy("expiry_date", "DESC")->first();

        $recentBatch->{ getActiveStore()->packed_column } = $request->{ getActiveStore()->packed_column };
        $recentBatch->{ getActiveStore()->yard_column } = $request->yard_qty;
        $recentBatch->update();

        return redirect()->route('stock.quick')->with('success','Stock Quantity has been adjusted successfully!');

    }


}
