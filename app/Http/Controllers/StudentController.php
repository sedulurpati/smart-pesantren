<?php

namespace App\Http\Controllers;

use App\Models\Family;
use App\Models\Student;
use App\Models\Dormitory;
use Illuminate\Http\File;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\MadinEducation;
use App\Models\FormalEducation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use Haruncpi\LaravelIdGenerator\IdGenerator;


class StudentController extends Controller
{
    public function biodata()
    {
        $student = Student::with('dormitory', 'family', 'madinEducation', 'formalEducation')
            ->where('user_id', Auth::user()->id)->first();
        return inertia('Santri/Biodata', compact('student'));
    }
    public function store(StoreStudentRequest $request)
    {
        $nis = IdGenerator::generate(['table' => 'students', 'field' => 'nis',  'length' => 8, 'prefix' => date('y')]);
        $user = Auth::user();
        if (!$user->student) {
            $student = new Student;
            $student->user_id = $user->id;
            $student->nama = $request->nama;
            $student->nik = $request->nik;
            $student->nis = $request->nis;
            $student->hp = $request->hp;
            $student->tempat_lahir = $request->tempat_lahir;
            $student->tanggal_lahir = $request->tanggal_lahir;
            $student->nis = $nis;
            $student->jenis_kelamin = $request->jenis_kelamin;
            $student->alamat = $request->alamat;
            $student->rtrw = $request->rtrw;
            $student->desa = $request->desa;
            $student->kecamatan = $request->kecamatan;
            $student->kota = $request->kota;
            $student->provinsi = Str::lower($request->provinsi);
            $student->kode_pos = $request->kode_pos;
            $student->agama = $request->agama;
            $student->hobi = $request->hobi;
            $student->cita_cita = $request->cita_cita;
            $student->kewarganegaraan = $request->kewarganegaraan;
            $student->kebutuhan_khusus = $request->kebutuhan_khusus;
            $student->status_rumah = $request->status_rumah;
            $student->status_mukim = $request->status_mukim;
            $student->sekolah_asal = $request->sekolah_asal;
            $student->alamat_sekolah_asal = $request->alamat_sekolah_asal;
            $student->npsn_sekolah_asal = $request->npsn_sekolah_asal;
            $student->nsm_sekolah_asal = $request->nsm_sekolah_asal;
            $student->no_ijazah = $request->no_ijazah;
            $student->no_un = $request->no_un;
            $student->nism = $request->nism;
            $student->kip = $request->kip;
            $student->pkh = $request->pkh;
            $student->kks = $request->kks;
            $student->kk = $request->kk;
            $student->save();

            $family = new Family;
            $family->student_id = $student->id;
            $family->a_nik = $request->a_nik;
            $family->a_nama = $request->a_nama;
            $family->a_pekerjaan = $request->a_pekerjaan;
            $family->a_pendidikan = $request->a_pendidikan;
            $family->a_phone = $request->a_phone;
            $family->a_penghasilan = $request->a_penghasilan;
            $family->i_nik = $request->i_nik;
            $family->i_nama = $request->i_nama;
            $family->i_pekerjaan = $request->i_pekerjaan;
            $family->i_pendidikan = $request->i_pendidikan;
            $family->i_phone = $request->i_phone;
            $family->w_hubungan_wali = $request->w_hubungan_wali;
            $family->w_nik = $request->w_nik;
            $family->w_nama = $request->w_nama;
            $family->w_pekerjaan = $request->w_pekerjaan;
            $family->w_penghasilan = $request->w_penghasilan;
            $family->save();

            $user->syncRoles('santri_baru');
            return Redirect::route('santri.reg-lembaga');
        }
        return back()->with('message', 'Data santri akun ini telah ada');
        dd('here');
    }
    public function regLembaga()
    {
        $formal = FormalEducation::all();
        $madin = MadinEducation::all();
        $jk = Auth::user()->student->jenis_kelamin;
        $dormitories = Dormitory::where('gender', $jk)->get();
        return inertia('Santri/RegistrasiLembaga', compact('formal', 'madin', 'dormitories'));
    }
    public function uploadFoto()
    {
        return inertia('Santri/UploadFoto');
    }
    public function storeFoto(Request $req)
    {
        $u = Auth::user();
        if ($req->hasFile('foto_santri')) {
            $req->validate([
                'foto_santri' => 'required|image|mimes:jpg,png,jpeg,svg|max:2024',
            ]);
            $extension = $req->file('foto_santri')->extension();
            $nama_file_santri = $u->student->nis . '-' . time() . '.' . $extension;
            $save_santri = $req->file('foto_santri')->storeAs('foto_santri', $nama_file_santri, 'public');
        }
        if ($req->hasFile('foto_wali')) {
            $req->validate([
                'foto_wali' => 'required|image|mimes:jpg,png,jpeg,svg|max:2024',
            ]);
            $extension = $req->file('foto_wali')->extension();
            $nama_file_wali = $u->student->nis . '-' . time() . '.' . $extension;
            $save_wali = $req->file('foto_wali')->storeAs('foto_wali', $nama_file_wali, 'public');
        }
        if ($req->hasFile('foto_santri') or $req->hasFile('foto_santri')) {
            $u->student->update([
                'foto' => $nama_file_santri,
                'foto_wali' => $nama_file_wali
            ]);
        }
        return Redirect::route('santri.pilih-metode-pembayaran')->with('message', 'Data Berhasil di kirim');
    }
    public function storeLembaga(Request $req)
    {
        $u = Auth::user();
        if ($u) {
            Student::where('user_id', $u->id)->update(
                [
                    'dormitory_id' => $req->daerah,
                    'room' => $req->asrama,
                    'madin_education_id' => $req->madin,
                    'formal_education_id' => $req->formal,
                ]
            );
        }
        return Redirect::route('santri.upload-foto');
    }
    public function pilihMetodePembayaran()
    {
        return inertia('Payment/PilihMetode');
    }
}
