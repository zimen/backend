<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PersonAnci;

class PersonAnciController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = PersonAnci::query();

        if($request->has('searchTerm')) {
            $columnsToSearch = ['name', 'email', 'phone'];
            $search_term = json_decode($request->searchTerm)->searchTerm;
            if(!empty($search_term)) {
                $searchQuery = '%' . $search_term . '%';
                foreach($columnsToSearch as $column) {
                    $query->orWhere($column, 'LIKE', $searchQuery);
                }
            }
        }

        if($request->has('columnFilters')) {

            $filters = get_object_vars(json_decode($request->columnFilters));

            foreach($filters as $key => $value) {
                if(!empty($value)) {
                    $query->orWhere($key, 'like', '%' . $value . '%');
                }
            }
        }

        if($request->has('sort.0')) {
            $sort = json_decode($request->sort[0]);
            $query->orderBy($sort->field, $sort->type);
        }

        if($request->has("perPage")) {
            $rows = $query->paginate($request->perPage);
        }

        return $rows;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'group' => 'required',
            'gid' => 'required',
            'anci' => 'required'
        ]);

        return PersonAnci::create([
            'group' => $request->group,
            'gid' => $request->gid,
            'anci' => $request->anci
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return PersonAnci::find($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'group' => 'required',
            'gid' => 'required',
            'anci' => 'required'
        ]);

        $personanci = PersonAnci::find($id);
        $personanci->group = $request->group;
        $personanci->gid = $request->gid;
        $personanci->anci = $request->anci;
        $personanci->save();
        return $personanci;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $personanci = PersonAnci::find($id);
        if($personanci) {
            $personanci->delete();
            return "true";
        }
        return "false";
    }
}