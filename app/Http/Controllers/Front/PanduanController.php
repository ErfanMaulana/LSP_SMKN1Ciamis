<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\PanduanItem;
use Illuminate\Support\Str;

class PanduanController extends Controller
{
    public function overview()
    {
        return $this->render('overview');
    }

    public function asesi()
    {
        return $this->render('asesi');
    }

    public function asesor()
    {
        return $this->render('asesor');
    }

    public function admin()
    {
        return $this->render('admin');
    }

    private function render(string $activeSection)
    {
        $sections = [
            'overview' => [
                'db_section' => 'alur-keseluruhan-sistem',
                'menu' => 'Alur Keseluruhan Sistem',
                'heading' => 'Alur Keseluruhan Sistem',
                'intro' => 'Sistem ini mengelola siklus sertifikasi kompetensi dari pendaftaran Asesi hingga hasil rekomendasi oleh Asesor dengan pengawasan Admin.',
            ],
            'asesi' => [
                'db_section' => 'peran-asesi',
                'menu' => 'Peran Asesi',
                'heading' => 'Panduan Peran Asesi',
                'intro' => 'Asesi adalah peserta sertifikasi. Fokus utama Asesi adalah melengkapi data, mengikuti asesmen, dan memantau hasil.',
            ],
            'asesor' => [
                'db_section' => 'peran-asesor',
                'menu' => 'Peran Asesor',
                'heading' => 'Panduan Peran Asesor',
                'intro' => 'Asesor bertugas melakukan penilaian kompetensi Asesi sesuai unit, elemen, dan kriteria yang telah ditetapkan.',
            ],
            'admin' => [
                'db_section' => 'peran-admin',
                'menu' => 'Peran Admin',
                'heading' => 'Panduan Peran Admin',
                'intro' => 'Admin adalah pengelola penuh sistem: mengatur data master, akses pengguna, verifikasi, serta monitoring keseluruhan proses.',
            ],
        ];

        abort_unless(isset($sections[$activeSection]), 404);

        $dbSection = $sections[$activeSection]['db_section'];
        $items = PanduanItem::query()
            ->bySection($dbSection)
            ->active()
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        $introFromPenjelasan = trim(strip_tags((string) optional($items->first())->penjelasan));
        if ($introFromPenjelasan !== '') {
            $sections[$activeSection]['intro'] = Str::limit($introFromPenjelasan, 220);
        }

        $steps = $items
            ->values()
            ->map(function (PanduanItem $item, int $index) {
                return [
                    'title' => ($index + 1) . '. ' . $item->title,
                    'description' => $item->description,
                    'penjelasan' => $item->penjelasan,
                    'image' => $item->image ? asset('storage/' . $item->image) : null,
                    'image_alt' => $item->title,
                    'image_caption' => $item->title,
                ];
            })->all();

        $sections[$activeSection]['steps'] = $steps;

        return view('front.panduan.show', [
            'activeSection' => $activeSection,
            'sections' => $sections,
            'content' => $sections[$activeSection],
        ]);
    }
}
