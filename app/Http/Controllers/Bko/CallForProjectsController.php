<?php

namespace App\Http\Controllers\Bko;

use App\CallForProjects;
use App\Http\Controllers\Controller;
use App\Thematic;
use Illuminate\Http\Request;

class CallForProjectsController extends Controller
{
    /**
     * Display a listing of the calls for projects opened.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $callsForProjects = CallForProjects::with([
            'thematic',
            'projectHolders',
            'perimeters',
            'beneficiaries'
        ])->opened()->get();
        $callsOfTheWeek = CallForProjects::filterCallsOfTheWeek($callsForProjects)->pluck('id');

//		$primary_thematics = Thematic::primary()->orderBy('name', 'asc')->get();
//		$subthematics = Thematic::sub()->orderBy('name', 'asc')->get()->groupBy('parent_id');

        $primary_thematics = $callsForProjects->map(function ($item) {
            return $item->thematic;
        })->unique()->values()->sortBy('name');

        $perimeters = $callsForProjects->pluck('perimeters')->flatten()->unique('name')->sortBy('name');
        $project_holders = $callsForProjects->pluck('projectHolders')->flatten()->unique('name')->sortBy('name');

        $subthematics = CallForProjects::getRelationshipData(Thematic::class, $callsForProjects, 'subthematic_id');
        if (!empty($subthematics)) {
            $subthematics = $subthematics->sortBy('name')->groupBy('parent_id');
        }

        $title = "Liste des dispositifs ouverts";

        $closed = false;

        return view(
            'bko.callForProjects.index',
            compact('callsForProjects', 'primary_thematics', 'subthematics', 'project_holders', 'perimeters', 'title', 'callsOfTheWeek', 'closed')
        );
    }

    /**
     * Display a listing of the calls for projects closed.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexClosed()
    {
        $callsForProjects = CallForProjects::with([
            'thematic',
            'projectHolders',
            'perimeters',
            'beneficiaries'
        ])->closed()->get();
        $callsOfTheWeek = CallForProjects::filterCallsOfTheWeek($callsForProjects)->pluck('id');
//		$primary_thematics = Thematic::primary()->orderBy('name', 'asc')->get();
//		$subthematics = Thematic::sub()->orderBy('name', 'asc')->get()->groupBy('parent_id');

        $primary_thematics = $callsForProjects->map(function ($item) {
            return $item->thematic;
        })->unique()->values();

        $perimeters = $callsForProjects->pluck('perimeters')->flatten()->unique('name')->sortBy('name');
        $project_holders = $callsForProjects->pluck('projectHolders')->flatten()->unique('name')->sortBy('name');

        $subthematics = CallForProjects::getRelationshipData(Thematic::class, $callsForProjects, 'subthematic_id');
        if (!empty($subthematics)) {
            $subthematics = $subthematics->groupBy('parent_id');
        }

        $title = "Liste des dispositifs clôturés";

        $closed = true;

        return view(
            'bko.callForProjects.index',
            compact('callsForProjects', 'primary_thematics', 'subthematics', 'project_holders', 'perimeters', 'title', 'callsOfTheWeek', 'closed')
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $callForProjects = new CallForProjects();
        $primary_thematics = Thematic::primary()->orderBy('name', 'asc')->get();
        $subthematics = Thematic::sub()->orderBy('name', 'asc')->get()->groupBy('parent_id');

        return view('bko.callForProjects.create', compact('callForProjects', 'primary_thematics', 'subthematics'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $callForProjects = new CallForProjects();

        $validatedData = $request->validate($callForProjects->rules());

        $callForProjects->fill(array_except($validatedData, ['perimeters', 'project_holders', 'beneficiaries']));
        $callForProjects->save();

        return redirect(route('bko.call.edit', $callForProjects))
            ->with('success', "Le dispositif a bien été ajouté.");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\CallForProjects $callForProjects
     *
     * @return \Illuminate\Http\Response
     */
    public function show(CallForProjects $callForProjects)
    {
        return view('bko.callForProjects.show', compact('callForProjects'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\CallForProjects $callForProjects
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(CallForProjects $callForProjects)
    {
        $primary_thematics = Thematic::primary()->orderBy('name', 'asc')->get();
        $subthematics = Thematic::sub()->orderBy('name', 'asc')->get()->groupBy('parent_id');

        return view('bko.callForProjects.edit', compact('callForProjects', 'primary_thematics', 'subthematics'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request $request
     * @param  \App\CallForProjects $callForProjects
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CallForProjects $callForProjects)
    {
        $validatedData = $request->validate($callForProjects->rules());

        $callForProjects->fill(array_except($validatedData, ['perimeters', 'project_holders', 'beneficiaries']));
        $callForProjects->save();

        return redirect(route('bko.call.edit', $callForProjects))
            ->with('success', "Le dispositif a bien été modifié.");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param  \App\CallForProjects $callForProjects
     *
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Request $request, CallForProjects $callForProjects)
    {
        if (!$request->ajax()) {
            exit;
        }

        $success = $callForProjects->delete();

        if ($success == 1) {
            return response()->json('deleted');
        }

        return response()->json('error', 422);
    }

    /**
     * Duplicate a call for projects
     *
     * @param  \App\CallForProjects $callForProjects
     *
     * @return \Illuminate\Http\Response
     */
    public function duplicate(CallForProjects $callForProjects)
    {
        $callForProjects->load(['projectHolders', 'perimeters', 'beneficiaries']);

        $new = $callForProjects->replicate();

        $new->push();

        foreach ($callForProjects->getRelations() as $relation => $entries) {
            $new->{$relation}()->saveMany($new->{$relation});
        }

        return redirect(route('bko.call.edit', $new))
            ->with('success', "Le dispositif a bien été dupliqué.");
    }
}
