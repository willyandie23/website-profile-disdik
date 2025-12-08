<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Field;
use App\Models\Organization;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GreetingController extends Controller
{
    public function index () {
        try {
            $organizations = Organization::with('field')->get();

            $head_of_department = $organizations->filter(function ($org) {
                return preg_match('/\b(plt\.?\s+)?kepala\s+dinas\b/i', $org->position);
            });

            $greeting = Field::where('name', 'Kepala Dinas')->first(); 

            return view('frontend.greeting.index',
                        compact('head_of_department', 'greeting'))
                        ->with(
                            [
                                'submenu' => false,
                                'navbar' => true,
                                'footer' => true
                            ]
                        );

        } catch (\Exception $e) {
            return redirect()->route('/')->with('error', 'Data tidak ditemukan.');
        }
    }
}
