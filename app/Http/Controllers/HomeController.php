<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        if (Auth::user() !== null) {
            $role = Auth::user()->roles->pluck('name')[0] ?? 'tamu';
            switch ($role) {
                case 'admin':
                    $role = Role::with('users');
                    $jml_santri_baru = $role->where('name', 'santri_baru')->first();
                    $jml_santri_baru = $jml_santri_baru ? $jml_santri_baru->users->count() : 0;
                    $santri = $role->where('name', 'santri')->first();
                    $santri = $santri ? $santri->users->count() : 0;
                    $alumni = $role->where('name', 'alumni')->first();
                    $alumni = $alumni ? $alumni->users->count() : 0;
                    $asatidz = $role->where('name', 'asatidz')->first();
                    $asatidz = $asatidz ? $asatidz->users->count() : 0;
                    $jumlah = [
                        'santri_baru' => $jml_santri_baru,
                        'santri' => $santri,
                        'alumni' => $alumni,
                        'asatidz' => $asatidz
                    ];
                    return inertia('Dashboard/admin', compact('jumlah'));
                    break;
                case 'super_admin':
                    return inertia('Dashboard/super_admin');
                    break;
                case 'tamu':
                    $this->authorize('create', Student::class);
                    return inertia('Dashboard/form_pendaftaran');
                    break;
                case 'santri_baru':
                    return inertia('Dashboard/santri_baru');
                    break;
                case 'santri':
                    return inertia('Dashboard/santri_aktif');
                    break;
                case 'alumni':
                    return inertia('Dashboard/alumni');
                    break;
                case 'admin_madin':
                    return inertia('Dashboard/admin_madin');
                    break;
                case 'hankamtib':
                    return inertia('Dashboard/hankamtib');
                    break;
                case 'bendahara':
                    return inertia('Dashboard/bendahara');
                    break;
                case 'asatidz':
                    return inertia('Dashboard/asatidz');
                    break;
                default:
                    return inertia('Dashboard/form_pendaftaran');
                    break;
            }
        } else {
            return redirect('/');
        }
    }
    public function try(Request $req)
    {
        dd($req->all());
    }
}
