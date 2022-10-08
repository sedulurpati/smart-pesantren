<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use Inertia\Inertia;
use App\Models\Family;
use App\Models\Student;
use App\Models\Dormitory;
use Illuminate\Http\Request;
use App\Models\MadinPresensi;
use App\Models\MadinEducation;
use App\Models\FormalEducation;
use function PHPSTORM_META\map;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\UpdateStudentRequest;

class SantriController extends Controller
{
    public function index(Request $req)
    {
        $perPage = $req->input('perPage') ?: 5;
        return Inertia::render('Santri/Index', [
            'students' => Student::query()
                ->when($req->input('search'), function ($query, $search) {
                    $query->where('nama', 'like', "%{$search}%");
                })
                ->when($req->input('daerah'), function ($query, $daerah) {
                    $query->where('dormitory_id', $daerah);
                })
                ->when($req->input('madin'), function ($query, $madin) {
                    $query->where('madin_education_id', $madin);
                })
                ->when($req->input('formal'), function ($query, $formal) {
                    $query->where('formal_education_id', $formal);
                })
                ->when($req->input('tahun'), function ($query, $tahun) {
                    $query->whereYear('created_at', '=', $tahun);
                })
                ->paginate($perPage)
                ->withQueryString()
                ->through(function ($item) {
                    return [
                        'id' => $item->id,
                        'nama' => $item->nama,
                        'nis' => $item->nis,
                        'jenis_kelamin' => $item->jenis_kelamin,
                        'usia' => $item->age(),
                        'daerah' => $item->dormitory->name . $item->room,
                        'madin' => $item->madinEducation->name,
                        'formal' => $item->formalEducation->name,
                        'showUrl' => URL::route('admin.santri.show', $item->id),
                        'editUrl' => URL::route('admin.santri.edit', $item->id),
                    ];
                }),
            'filters' => $req->only(
                ['search', 'perPage', 'daerah', 'madin', 'formal', 'tahun'],

            ),
            'perHal' => $perPage,
            'dormitories' => Dormitory::query()
                ->get(['id', 'name', 'gender'])
                ->map(function ($asr) {
                    return [
                        'id' => $asr->id,
                        'name' => $asr->name . ' (' . $asr->gender . ')',
                    ];
                })
                ->toArray(),
            'madin_educations' => MadinEducation::all(),
            'formal_educations' => FormalEducation::all(),
        ]);
    }
    public function show(Student $santri)
    {
        $santri->load('dormitory', 'madinEducation', 'formalEducation');
        $showMode = true;
        $family = $santri->family;
        return Inertia::render('Santri/Edit', compact('santri', 'showMode', 'family'));
    }
    public function edit(Student $santri)
    {
        $daerah = Dormitory::where('gender', $santri->jenis_kelamin)->get();
        $madin = MadinEducation::all();
        $formal = FormalEducation::all();
        $family = $santri->family;
        return inertia('Santri/Edit', compact('santri', 'family', 'madin', 'formal', 'daerah'));
    }
    public function update(Student $santri, UpdateStudentRequest $req)
    {
        $input = $req->except(['family']);
        $santri->update($input);
        return back()->with('message', 'Data Pribadi Berhasil di edit');
    }
    public function updateOrtu(Request $req, int $id)
    {
        $input = $req->except(['created_at', 'updated_at']);
        $family = Family::firstOrNew(['student_id' => request('id')]);
        $family->a_nik = $req->a_nik;
        $family->a_nama = $req->a_nama;
        $family->a_pekerjaan = $req->a_pekerjaan;
        $family->a_pendidikan = $req->a_pendidikan;
        $family->a_phone = $req->a_phone;
        $family->a_penghasilan = $req->a_penghasilan;
        $family->i_nik = $req->i_nik;
        $family->i_nama = $req->i_nama;
        $family->i_pekerjaan = $req->i_pekerjaan;
        $family->i_pendidikan = $req->i_pendidikan;
        $family->i_phone = $req->i_phone;
        $family->w_hubungan_wali = $req->w_hubungan_wali;
        $family->w_nik = $req->w_nik;
        $family->w_nama = $req->w_nama;
        $family->w_pekerjaan = $req->w_pekerjaan;
        $family->w_penghasilan = $req->w_penghasilan;
        $family->save();
        return back()->with('message', 'Data Orang tua Berhasil di edit');
    }
    public function updatePendidikan(Request $req, int $id)
    {
        $input = $req->except(['created_at', 'updated_at']);
        Student::find($id)->update($input);
        return back()->with('message', 'Data Pendidikan/Daerah Berhasil di edit');
    }
    public function updateFoto(Student $student, Request $req)
    {
        $pesan  = 'Foto Berhasil Diperbarui';
        if ($req->hasFile('santri')) {
            $req->validate([
                'santri' => 'required|image|mimes:jpg,png,jpeg,svg|max:2024',
            ]);
            $extension = $req->file('santri')->extension();
            $nama_file_santri = $student->nis . '-' . time() . '.' . $extension;
            $req->file('santri')->storeAs('foto_santri', $nama_file_santri, 'public');
            $santri_saved = $student->update([
                'foto' => $nama_file_santri
            ]);
        }
        if ($req->hasFile('wali')) {
            $req->validate([
                'wali' => 'required|image|mimes:jpg,png,jpeg,svg|max:2024',
            ]);
            $extension = $req->file('wali')->extension();
            $nama_file_wali = $student->nis . '-' . time() . '.' . $extension;
            $req->file('wali')->storeAs('foto_wali', $nama_file_wali, 'public');
            $wali_saved = $student->update([
                'foto_wali' => $nama_file_wali
            ]);
        }
        return Redirect::route('admin.santri.index')->with('message', $pesan);
    }
}
