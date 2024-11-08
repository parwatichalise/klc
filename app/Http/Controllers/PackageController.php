<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\models\Package;
use Illuminate\Support\Facades\Auth;

class PackageController extends Controller
{
    public function index()
    {
        $packages = Package::paginate(10);
    
        // Directly check the user's role
        if (Auth::user() && Auth::user()->role === 'admin') {
            return view('admin.create.package', compact('packages'));
        } else {
            return view('teacher.create.package', compact('packages'));
        }
    }
    
    public function create()
    {
        $packages = Package::paginate(10); 
    
        if (Auth::user() && Auth::user()->role === 'admin') {
            return view('admin.create.package', compact('packages'));
        } else {
            return view('teacher.create.package', compact('packages'));
        }
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'nullable|numeric',
        ]);
    
        Package::create($request->only('name', 'price'));
    
        return redirect()->route('packages.index')->with('success', 'Package added successfully.');
    }
    
    public function edit($id)
    {
        $packages = Package::paginate(10);        
        $package = Package::findOrFail($id);
    
        if (Auth::user() && Auth::user()->role === 'admin') {
            return view('admin.create.package', compact('packages', 'package'));
        } else {
            return view('teacher.create.package', compact('packages', 'package'));
        }
    }
    
    public function update(Request $request, Package $package)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'nullable|numeric',
        ]);
    
        $package->update($request->only('name', 'price'));
    
        return redirect()->route('packages.index')->with('success', 'Package added successfully.');

    }
    
    public function destroy(Package $package)
    {
        $package->delete();
    
        return redirect()->route('packages.index')->with('success', 'Package added successfully.');

    }
}    