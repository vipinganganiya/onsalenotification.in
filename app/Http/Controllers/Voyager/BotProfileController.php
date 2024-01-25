<?php

namespace App\Http\Controllers\Voyager;

use DB;
use TCG\Voyager\Http\Controllers\VoyagerBaseController;
use Illuminate\Http\Request;
use App\Models\BotProfile;
use App\Models\BotThread;
use TCG\Voyager\Facades\Voyager;

class BotProfileController extends VoyagerBaseController
{
    /**
     * Create BREAD.
     *
     * @param Request $request
     * @param string  $table   Table name.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function createProfile(Request $request, $table)
    {
        $this->authorize('browse_bread');

        $dataType = Voyager::model('DataType')->whereName($table)->first();

        $data = $this->prepopulateBreadInfo($table);
        $data['fieldOptions'] = SchemaManager::describeTable(
            (isset($dataType) && strlen($dataType->model_name) != 0)
            ? DB::getTablePrefix().app($dataType->model_name)->getTable()
            : DB::getTablePrefix().$table
        );

        $view = Voyager::view('voyager::tools.bread.edit-add', $data);

        return response()->json(array('html'=> $view), 200); 
    }

    public function showProfile(Request $request, $id)
    {
        $slug = $this->getSlug($request);

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

        $view = 'voyager::bread.read';

        if (view()->exists("voyager::$slug.read")) {
            $view = "voyager::$slug.read";
        }

        $view =  Voyager::view($view, compact('dataType', 'dataTypeContent', 'isModelTranslatable', 'isSoftDeleted'))
                    ->render();

        return response()->json(array('html'=> $view), 200); 
    }

    /**
     * Edit BREAD.
     *
     * @param string $table
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editProfile($table)
    {
        $this->authorize('browse_bread');

        $dataType = Voyager::model('DataType')->whereName($table)->first();

        $fieldOptions = SchemaManager::describeTable(
            (strlen($dataType->model_name) != 0)
            ? DB::getTablePrefix().app($dataType->model_name)->getTable()
            : DB::getTablePrefix().$dataType->name
        );

        $isModelTranslatable = is_bread_translatable($dataType);
        $tables = SchemaManager::listTableNames();
        $dataTypeRelationships = Voyager::model('DataRow')->where('data_type_id', '=', $dataType->id)->where('type', '=', 'relationship')->get();
        $scopes = [];
        if ($dataType->model_name != '') {
            $scopes = $this->getModelScopes($dataType->model_name);
        }

        return Voyager::view('voyager::tools.bread.edit-add', compact('dataType', 'fieldOptions', 'isModelTranslatable', 'tables', 'dataTypeRelationships', 'scopes'));
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
    public function readProfile(Request $request, $id)
    {

        // GET THE SLUG, ex. 'posts', 'pages', etc.
        $get_route = str_replace('voyager.', '', $request->route()->getName());
        $get_route = str_replace('.filterBySource', '', $get_route);
 
        if ($get_route == 'bot-profiles.read') {
            $slug = 'bot-profiles'; 
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

        $data = BotProfile::with('thread')->find($id); 

        //dd($data, $data->thread);

        return view('/vendor/voyager/bot-profiles/read', compact('dataType', 'dataTypeContent', 'isModelTranslatable', 'isSoftDeleted', 'data'));
                    //->render();

        return response()->json(array('html'=> $view), 200); 
    }
}