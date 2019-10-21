<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Car;
use App\Brand;

class CarsController extends Controller
{
    public function index(Request $request)
    {
        $cars = Car::with('Brand', 'Photos', 'Optionals');

        if ($request->has('brand')) 
        {
            $brand = Brand::where('description',$request->brand)->first();
            $cars->where('brand_id',$brand->id);
        }

        if ($request->has('model')) 
        {
            $cars->where('description', 'like', '%'.$request->model.'%');
        }

        if ($request->has('city')) 
        {
            $cars->where('city', 'like', '%'.$request->model.'%');
        }

        if ($request->has('year_min')) 
        {
            $cars->where('year', '>=', $request->year_min);
        }

        if ($request->has('year_max')) 
        {
            $cars->where('year', '<=', $request->year_max);
        }

        return $cars->get();
    }
 
    public function show($id)
    {
        return Car::with('Brand', 'Photos', 'Optionals')->find($id);
    }
}
