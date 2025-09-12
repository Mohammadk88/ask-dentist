<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DoctorController extends Controller
{
    /**
     * Doctor dashboard
     */
    public function dashboard(): View
    {
        return view('doctor.dashboard');
    }

    /**
     * Treatment Plan Builder
     */
    public function treatmentPlanBuilder(): View
    {
        return view('doctor.treatment-plan-builder');
    }
}
