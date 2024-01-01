<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ListingController extends Controller
{
    // Show all listings
    public function index()
    {
        return view('listings.index', [
            'listings' => Listing::latest()->filter
            (request(['tag', 'search']))->simplePaginate(10),
        ]);
    }

    // Show single listing
    public function show(Listing $listing)
    {
        return view('listings.show', [
            'listing' => $listing,
        ]);
    }

    // Show create form
    public function create()
    {
        return view('listings.create');
    }

    // Store create form
    public function store(Request $request)
    {

        $formFields = $request->validate([
            'title' => 'required',
            'company' => ['required', Rule::unique('listings', 'company')],
            'location' => 'required',
            'website' => 'required',
            'email' => ['required', 'email'],
            'tags' => 'required',
            // 'logo' => 'required',
            'description' => 'required',
        ]);

        if ($request->hasFile('logo')) {
            $formFields['logo'] = $request->file('logo')->store('logos', 'public');
        }

        $formFields['user_id'] = auth()->id();

        Listing::create($formFields);

        return redirect('/')->with('message', 'Listing created successfully!');

    }

    public function edit(Listing $listing)
    {
        return view('listings.edit', ['listing' => $listing]);
    }

    public function update(Request $request, Listing $listing)
    {
        {

            // Make Sure logged in user owner

            if (auth()->id() != $listing->user_id)
            {
                abort(403, 'Unauthorized Action!');
            }

            $formFields = $request->validate([
                'title' => 'required',
                'company' => 'required',
                'location' => 'required',
                'website' => 'required',
                'email' => ['required', 'email'],
                'tags' => 'required',
                'description' => 'required',
            ]);

            if ($request->hasFile('logo')) {
                $formFields['logo'] = $request->file('logo')->store('logos', 'public');
            }

            $listing->update($formFields);

            return redirect('/')->with('message', 'Listing Edited Successfully!');
        }
    }

    public function destroy(Listing $listing){

        // Make Sure logged in user owner

        if (auth()->id() != $listing->user_id)
        {
            abort(403, 'Unauthorized Action!');
        }

        $listing->delete();
        return redirect('/')->with('message', 'Listing Deleted Successfully!');
    }

    public function manage(){
        $listings = Listing::where('user_id', auth()->id())->simplePaginate(10);
        return view('listings.manage', compact('listings'));
    }

}
