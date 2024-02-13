<?php

namespace App\Http\Controllers\Voyager;

use DB;
use TCG\Voyager\Http\Controllers\VoyagerBaseController;
use TCG\Voyager\Events\BreadDataUpdated;
use TCG\Voyager\Events\BreadDataAdded;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use TCG\Voyager\Facades\Voyager;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\BotMachine;

class BotMachineController extends VoyagerBaseController
{

    //***************************************
    //               ____
    //              |  _ \
    //              | |_) |
    //              |  _ <
    //              | |_) |
    //              |____/
    //
    //      Browse our Data Type (B)READ
    //
    //****************************************
    
    public function index(Request $request) { 

        //GET THE SLUG, ex. 'posts', 'pages', etc.
        $get_route = str_replace('voyager.', '', $request->route()->getName());
        $get_route = str_replace('.filterBySource', '', $get_route);
 
        if ($get_route == 'machines') {
            $slug = 'bot-machines'; 
        } else {
            $slug = $this->getSlug($request);
        } 

        // GET THE DataType based on the slug
        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();

        // Check permission
        $this->authorize('browse', app($dataType->model_name));

        $post_per_page = setting('admin.notification_per_page');
        $machines = BotMachine::orderBy('id', 'desc')->get();
        $view = view('/vendor/voyager/bot-machines/listing', compact('machines','dataType'))->render();
        return response()->json($view, 200); 
    }

    public function create(Request $request) {

        //GET THE SLUG, ex. 'posts', 'pages', etc.
        $get_route = str_replace('voyager.', '', $request->route()->getName());
        $get_route = str_replace('.filterBySource', '', $get_route); 
 
        if ($get_route == 'machines.create') {
            $slug = 'bot-machines'; 
        } else {
            $slug = $this->getSlug($request);
        } 

        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();

        // Check permission
        $this->authorize('add', app($dataType->model_name));

        $dataTypeContent = (strlen($dataType->model_name) != 0)
                            ? new $dataType->model_name()
                            : false;

        foreach ($dataType->addRows as $key => $row) {
            $dataType->addRows[$key]['col_width'] = $row->details->width ?? 100;
        }

        // If a column has a relationship associated with it, we do not want to show that field
        $this->removeRelationshipField($dataType, 'add');

        // Check if BREAD is Translatable
        $isModelTranslatable = is_bread_translatable($dataTypeContent);

        // Eagerload Relations
        $this->eagerLoadRelations($dataTypeContent, $dataType, 'add', $isModelTranslatable); 

        $view = view('/vendor/voyager/bot-machines/edit-add', compact('dataType', 'dataTypeContent', 'isModelTranslatable'))->render();
        return response()->json($view, 200); 
    }

    public function edit(Request $request, $id) {
        //GET THE SLUG, ex. 'posts', 'pages', etc.
        $get_route = str_replace('voyager.', '', $request->route()->getName());
        $get_route = str_replace('.filterBySource', '', $get_route); 
 
        if ($get_route == 'machines.edit') {
            $slug = 'bot-machines'; 
        } else {
            $slug = $this->getSlug($request);
        } 

        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();

        if (strlen($dataType->model_name) != 0) {
            $model = app($dataType->model_name);
            $query = $model->query();

            // Use withTrashed() if model uses SoftDeletes and if toggle is selected
            if ($model && in_array(SoftDeletes::class, class_uses_recursive($model))) {
                $query = $query->withTrashed();
            }
            if ($dataType->scope && $dataType->scope != '' && method_exists($model, 'scope'.ucfirst($dataType->scope))) {
                $query = $query->{$dataType->scope}();
            }
            $dataTypeContent = call_user_func([$query, 'findOrFail'], $id);
        } else {
            // If Model doest exist, get data from table name
            $dataTypeContent = DB::table($dataType->name)->where('id', $id)->first();
        }

        foreach ($dataType->editRows as $key => $row) {
            $dataType->editRows[$key]['col_width'] = isset($row->details->width) ? $row->details->width : 100;
        }

        // If a column has a relationship associated with it, we do not want to show that field
        $this->removeRelationshipField($dataType, 'edit');

        // Check permission
        $this->authorize('edit', $dataTypeContent);

        // Check if BREAD is Translatable
        $isModelTranslatable = is_bread_translatable($dataTypeContent);

        // Eagerload Relations
        $this->eagerLoadRelations($dataTypeContent, $dataType, 'edit', $isModelTranslatable);

        $view = 'voyager::bread.edit-add';

        if (view()->exists("voyager::$slug.edit-add")) {
            $view = "voyager::$slug.edit-add";
        }

        $view = view('/vendor/voyager/bot-machines/edit-add', compact('dataType', 'dataTypeContent', 'isModelTranslatable'))->render();
        return response()->json($view, 200); 
    }

    public function store(Request $request) {

        //GET THE SLUG, ex. 'posts', 'pages', etc.
        $get_route = str_replace('voyager.', '', $request->route()->getName());
        $get_route = str_replace('.filterBySource', '', $get_route); 
 
        if ($get_route == 'machines.store') {
            $slug = 'bot-machines'; 
        } else {
            $slug = $this->getSlug($request);
        } 

        $request['machine_unique_field'] = Str::random(16);

        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();

        // Check permission
        $this->authorize('add', app($dataType->model_name));

        // Validate fields with ajax
        $val = $this->validateBread($request->all(), $dataType->addRows)->validate();
        $data = $this->insertUpdateData($request, $slug, $dataType->addRows, new $dataType->model_name());

        event(new BreadDataAdded($dataType, $data));

        //return redirect()->back()->with('popup', 'bot-machines-listing');
        $redirect = redirect()->back(); 

        return $redirect->with([
            'message'    => __('voyager::generic.successfully_added_new')." {$dataType->getTranslatedAttribute('display_name_singular')}",
            'alert-type' => 'success',
            'popup' => 'bot-machines-listing'
        ]);
    }

    public function update(Request $request, $id)
    {
        //GET THE SLUG, ex. 'posts', 'pages', etc.
        $get_route = str_replace('voyager.', '', $request->route()->getName());
        $get_route = str_replace('.filterBySource', '', $get_route); 
 
        if ($get_route == 'machines.update') {
            $slug = 'bot-machines'; 
        } else {
            $slug = $this->getSlug($request);
        } 

        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();

        // Compatibility with Model binding.
        $id = $id instanceof \Illuminate\Database\Eloquent\Model ? $id->{$id->getKeyName()} : $id;

        $model = app($dataType->model_name);
        $query = $model->query();
        if ($dataType->scope && $dataType->scope != '' && method_exists($model, 'scope'.ucfirst($dataType->scope))) {
            $query = $query->{$dataType->scope}();
        }
        if ($model && in_array(SoftDeletes::class, class_uses_recursive($model))) {
            $query = $query->withTrashed();
        }

        $data = $query->findOrFail($id);

        // Check permission
        $this->authorize('edit', $data);

        // Validate fields with ajax
        $val = $this->validateBread($request->all(), $dataType->editRows, $dataType->name, $id)->validate();

        // Get fields with images to remove before updating and make a copy of $data
        $to_remove = $dataType->editRows->where('type', 'image')
            ->filter(function ($item, $key) use ($request) {
                return $request->hasFile($item->field);
            });
        $original_data = clone($data);

        $this->insertUpdateData($request, $slug, $dataType->editRows, $data);

        // Delete Images
        $this->deleteBreadImages($original_data, $to_remove);

        event(new BreadDataUpdated($dataType, $data));
 
        $redirect = redirect()->back(); 

        return $redirect->with([
            'message'    => __('voyager::generic.successfully_updated')." {$dataType->getTranslatedAttribute('display_name_singular')}",
            'alert-type' => 'success',
            'popup' => 'bot-machines-listing'
        ]);
    }

    //***************************************
    //                _____
    //               |  __ \
    //               | |__) |
    //               |  _  /
    //               | | \ \
    //               |_|  \_\
    //
    //  Read an item of our Data Type B(R)EAD
    //
    //****************************************

    public function show(Request $request, $id)
    {
        //GET THE SLUG, ex. 'posts', 'pages', etc.
        $get_route = str_replace('voyager.', '', $request->route()->getName());
        $get_route = str_replace('.filterBySource', '', $get_route); 
 
        if ($get_route == 'machines.read') {
            $slug = 'bot-machines'; 
        } else {
            $slug = $this->getSlug($request);
        }

        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();

        $isSoftDeleted = false;

        if (strlen($dataType->model_name) != 0) {
            $model = app($dataType->model_name);
            $query = $model->query();

            // Use withTrashed() if model uses SoftDeletes and if toggle is selected
            if ($model && in_array(SoftDeletes::class, class_uses_recursive($model))) {
                $query = $query->withTrashed();
            }
            if ($dataType->scope && $dataType->scope != '' && method_exists($model, 'scope'.ucfirst($dataType->scope))) {
                $query = $query->{$dataType->scope}();
            }
            $dataTypeContent = call_user_func([$query, 'findOrFail'], $id);
            if ($dataTypeContent->deleted_at) {
                $isSoftDeleted = true;
            }
        } else {
            // If Model doest exist, get data from table name
            $dataTypeContent = DB::table($dataType->name)->where('id', $id)->first();
        }

        // Replace relationships' keys for labels and create READ links if a slug is provided.
        $dataTypeContent = $this->resolveRelations($dataTypeContent, $dataType, true);

        // If a column has a relationship associated with it, we do not want to show that field
        $this->removeRelationshipField($dataType, 'read');

        // Check permission
        $this->authorize('read', $dataTypeContent);

        // Check if BREAD is Translatable
        $isModelTranslatable = is_bread_translatable($dataTypeContent);

        // Eagerload Relations
        $this->eagerLoadRelations($dataTypeContent, $dataType, 'read', $isModelTranslatable);

        //return Voyager::view($view, compact('dataType', 'dataTypeContent', 'isModelTranslatable', 'isSoftDeleted'));
        $view = view('/vendor/voyager/bot-machines/read', compact('dataType', 'dataTypeContent', 'isModelTranslatable', 'isSoftDeleted'))->render();
        return response()->json($view, 200); 
    }
}
