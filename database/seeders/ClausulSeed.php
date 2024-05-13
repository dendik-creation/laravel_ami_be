<?php

namespace Database\Seeders;

use App\Models\Clausul;
use App\Models\JudulClausul;
use App\Models\SubClausul;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClausulSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1
        JudulClausul::create([
            'kode' => '1',
            'judul_clausul' => 'Ruang Lingkup',
            'iso_id' => 1,
        ]);
        // 2
        JudulClausul::create([
            'kode' => '2',
            'judul_clausul' => 'Referensi Normatif',
            'iso_id' => 1,
        ]);
        // 3
        JudulClausul::create([
            'kode' => '3',
            'judul_clausul' => 'Istilah dan Definisi',
            'iso_id' => 1,
        ]);
        // 4
        $judul4 = JudulClausul::create([
            'kode' => '4',
            'judul_clausul' => 'Konteks Organisasi',
            'iso_id' => 1,
        ]);
        Clausul::create([
            'kode' => '4.1',
            'judul_clausul_id' => $judul4['id'],
            'nama_clausul' => 'Pemahaman organisasi dan konteksnya',
        ]);
        Clausul::create([
            'kode' => '4.2',
            'judul_clausul_id' => $judul4['id'],
            'nama_clausul' => 'Pemahaman kebutuhan dan harapan pihak terkait',
        ]);
        Clausul::create([
            'kode' => '4.3',
            'judul_clausul_id' => $judul4['id'],
            'nama_clausul' => "Menetapkan ruang lingkup sistem
            manajemen mutu",
        ]);
        $clausul4_4 = Clausul::create([
            'kode' => '4.4',
            'judul_clausul_id' => $judul4['id'],
            'nama_clausul' => "Sistem manajemen mutu dan
            prosesnya",
        ]);
        SubClausul::create([
            'judul_clausul_id' => $judul4['id'],
            'clausul_id' => $clausul4_4['id'],
            'kode' => '4.4.1',
            'nama_sub_clausul' => "Organisasi harus menetapkan,
            menerapkan, memelihara dan terus
            meningkatkan SMM",
        ]);
        SubClausul::create([
            'judul_clausul_id' => $judul4['id'],
            'clausul_id' => $clausul4_4['id'],
            'kode' => '4.4.2',
            'nama_sub_clausul' => "Sejauh mana diperlukan, maka
            organisasi harus meenyimpan dan memelihara informasi terdokumentasi",
        ]);
        // 5
        $judul5 = JudulClausul::create([
            'kode' => '5',
            'judul_clausul' => 'Kepemimpinan',
            'iso_id' => 1,
        ]);
        $clausul5_1 = Clausul::create([
            'kode' => '5.1',
            'judul_clausul_id' => $judul5['id'],
            'nama_clausul' => 'Kepemimpinan dan komitmen',
        ]);
        SubClausul::create([
            'judul_clausul_id' => $judul5['id'],
            'clausul_id' => $clausul5_1['id'],
            'kode' => '5.1.1',
            'nama_sub_clausul' => 'Umum',
        ]);
        SubClausul::create([
            'judul_clausul_id' => $judul5['id'],
            'clausul_id' => $clausul5_1['id'],
            'kode' => '5.1.2',
            'nama_sub_clausul' => 'Fokus kepada pelanggan',
        ]);
        $clausul5_2 = Clausul::create([
            'kode' => '5.2',
            'judul_clausul_id' => $judul5['id'],
            'nama_clausul' => 'Kebijakan',
        ]);
        SubClausul::create([
            'judul_clausul_id' => $judul5['id'],
            'clausul_id' => $clausul5_2['id'],
            'kode' => '5.2.1',
            'nama_sub_clausul' => 'Menetapkan kebijakan mutu',
        ]);
        SubClausul::create([
            'judul_clausul_id' => $judul5['id'],
            'clausul_id' => $clausul5_2['id'],
            'kode' => '5.2.2',
            'nama_sub_clausul' => 'Mengkomunikasikan kebijakan mutu',
        ]);
        $clausul5_3 = Clausul::create([
            'kode' => '5.3',
            'judul_clausul_id' => $judul5['id'],
            'nama_clausul' => 'Peran organisasi dan tanggung jawab wewenang',
        ]);
        // 6
        $judul6 = JudulClausul::create([
            'kode' => '6',
            'judul_clausul' => 'Perencanaan',
            'iso_id' => 1,
        ]);
        $clausul6_1 = Clausul::create([
            'kode' => '6.1',
            'judul_clausul_id' => $judul6['id'],
            'nama_clausul' => 'Tindakan untuk mengendalikan resiko dan peluang',
        ]);
        SubClausul::create([
            'judul_clausul_id' => $judul6['id'],
            'clausul_id' => $clausul6_1['id'],
            'kode' => '6.1.1',
            'nama_sub_clausul' => 'Ketika merencanakan untuk SMM, organisasi harus mempertimbangkan isu yang
            dimasukkan di 4.1 dan persyaratan yang dimasukkan di 4.2 dan menentukan resiko dan
            peluang',
        ]);
        SubClausul::create([
            'judul_clausul_id' => $judul6['id'],
            'clausul_id' => $clausul6_1['id'],
            'kode' => '6.1.2',
            'nama_sub_clausul' => 'Organisasi harus merencanakan tindakan untuk mengendalikan resiko dan peluang dengan penerapan proses SMM serta evaluasi efektivitas tindakan',
        ]);
        $clausul6_2 = Clausul::create([
            'kode' => '6.2',
            'judul_clausul_id' => $judul6['id'],
            'nama_clausul' => 'Sasaran mutu dan perencanaan
            untuk pencapaiannya',
        ]);
        SubClausul::create([
            'judul_clausul_id' => $judul6['id'],
            'clausul_id' => $clausul6_2['id'],
            'kode' => '6.2.1',
            'nama_sub_clausul' => 'Organisasi harus menetapkan sasaran
            mutu pada fungsi, tingkat dan proses relevan',
        ]);
        SubClausul::create([
            'judul_clausul_id' => $judul6['id'],
            'clausul_id' => $clausul6_2['id'],
            'kode' => '6.2.2',
            'nama_sub_clausul' => 'Ketika merencanakan bagaimana
            mencapai sasaran mutu',
        ]);
        $clausul6_3 = Clausul::create([
            'kode' => '6.3',
            'judul_clausul_id' => $judul6['id'],
            'nama_clausul' => 'Perencanaan Perubahan',
        ]);
        // 7
        $judul7 = JudulClausul::create([
            'kode' => '7',
            'judul_clausul' => 'Dukungan',
            'iso_id' => 1,
        ]);
        $clausul7_1 = Clausul::create([
            'kode' => '7.1',
            'judul_clausul_id' => $judul7['id'],
            'nama_clausul' => 'Sumber Daya',
        ]);
        SubClausul::create([
            'judul_clausul_id' => $judul7['id'],
            'clausul_id' => $clausul7_1['id'],
            'kode' => '7.1.1',
            'nama_sub_clausul' => 'Umum',
        ]);
        SubClausul::create([
            'judul_clausul_id' => $judul7['id'],
            'clausul_id' => $clausul7_1['id'],
            'kode' => '7.1.2',
            'nama_sub_clausul' => 'Orang',
        ]);
        SubClausul::create([
            'judul_clausul_id' => $judul7['id'],
            'clausul_id' => $clausul7_1['id'],
            'kode' => '7.1.3',
            'nama_sub_clausul' => 'Infrastruktur',
        ]);
        SubClausul::create([
            'judul_clausul_id' => $judul7['id'],
            'clausul_id' => $clausul7_1['id'],
            'kode' => '7.1.4',
            'nama_sub_clausul' => 'Lingkungan untuk operasional proses',
        ]);
        SubClausul::create([
            'judul_clausul_id' => $judul7['id'],
            'clausul_id' => $clausul7_1['id'],
            'kode' => '7.1.5',
            'nama_sub_clausul' => 'Sumber daya pemantauan dan pengukuran',
        ]);
        // Ada Sub Di Sub Lagi Wak => 7.1.5.1 - 7.1.5.2  // Skib
        SubClausul::create([
            'judul_clausul_id' => $judul7['id'],
            'clausul_id' => $clausul7_1['id'],
            'kode' => '7.1.6',
            'nama_sub_clausul' => 'Pengetahuan Organisasi',
        ]);
        $clausul7_2 = Clausul::create([
            'kode' => '7.2',
            'judul_clausul_id' => $judul7['id'],
            'nama_clausul' => 'Kompetensi',
        ]);
        $clausul7_3 = Clausul::create([
            'kode' => '7.3',
            'judul_clausul_id' => $judul7['id'],
            'nama_clausul' => 'Kepedulian',
        ]);
        $clausul7_4 = Clausul::create([
            'kode' => '7.4',
            'judul_clausul_id' => $judul7['id'],
            'nama_clausul' => 'Komunikasi',
        ]);
        $clausul7_5 = Clausul::create([
            'kode' => '7.5',
            'judul_clausul_id' => $judul7['id'],
            'nama_clausul' => 'Informasi Terdokumentasi',
        ]);
        SubClausul::create([
            'judul_clausul_id' => $judul7['id'],
            'clausul_id' => $clausul7_5['id'],
            'kode' => '7.5.1',
            'nama_sub_clausul' => 'Umum',
        ]);
        SubClausul::create([
            'judul_clausul_id' => $judul7['id'],
            'clausul_id' => $clausul7_5['id'],
            'kode' => '7.5.2',
            'nama_sub_clausul' => 'Menghemat dan memperbaharui',
        ]);
        SubClausul::create([
            'judul_clausul_id' => $judul7['id'],
            'clausul_id' => $clausul7_5['id'],
            'kode' => '7.5.3',
            'nama_sub_clausul' => 'Pemantauan informasi terdokumentasi',
        ]);
        // Ada Sub Di Sub Lagi Wak => 7.5.3.1 - 7.5.3.2  // Skib
        // 8
        $judul8 = JudulClausul::create([
            'kode' => '8',
            'judul_clausul' => 'Pengoperasian',
            'iso_id' => 1,
        ]);
        $clausul8_1 = Clausul::create([
            'kode' => '8.1',
            'judul_clausul_id' => $judul8['id'],
            'nama_clausul' => 'Perencanaan pengoperasian dan
            pemantauan',
        ]);
        $clausul8_2 = Clausul::create([
            'kode' => '8.2',
            'judul_clausul_id' => $judul8['id'],
            'nama_clausul' => 'Persayaratan untuk produk dan jasa',
        ]);
        SubClausul::create([
            'judul_clausul_id' => $judul8['id'],
            'clausul_id' => $clausul8_2['id'],
            'kode' => '8.2.1',
            'nama_sub_clausul' => 'Komunikasi dengan pelanggan',
        ]);
        SubClausul::create([
            'judul_clausul_id' => $judul8['id'],
            'clausul_id' => $clausul8_2['id'],
            'kode' => '8.2.2',
            'nama_sub_clausul' => 'Menetapkan persyaratan yang
            berkaitan dengan produk dan jasa',
        ]);
        SubClausul::create([
            'judul_clausul_id' => $judul8['id'],
            'clausul_id' => $clausul8_2['id'],
            'kode' => '8.2.3',
            'nama_sub_clausul' => 'Meninjau persyaratan yang
            berkaitan dengan produk dan jasa',
        ]);
        // Ada Sub Di Sub Lagi Wak => 8.2.3.1 - 8.2.3.2  // Skib
        SubClausul::create([
            'judul_clausul_id' => $judul8['id'],
            'clausul_id' => $clausul8_2['id'],
            'kode' => '8.2.4',
            'nama_sub_clausul' => 'Perubahan persyaratan yang
            berkaitan dengan produk dan jasa',
        ]);
        $clausul8_3 = Clausul::create([
            'kode' => '8.3',
            'judul_clausul_id' => $judul8['id'],
            'nama_clausul' => 'Desain dan pengembangan produk dan jasa',
        ]);
        SubClausul::create([
            'judul_clausul_id' => $judul8['id'],
            'clausul_id' => $clausul8_3['id'],
            'kode' => '8.3.1',
            'nama_sub_clausul' => 'Umum',
        ]);
        SubClausul::create([
            'judul_clausul_id' => $judul8['id'],
            'clausul_id' => $clausul8_3['id'],
            'kode' => '8.3.2',
            'nama_sub_clausul' => 'Perencanaan desain & pengembangan',
        ]);
        SubClausul::create([
            'judul_clausul_id' => $judul8['id'],
            'clausul_id' => $clausul8_3['id'],
            'kode' => '8.3.3',
            'nama_sub_clausul' => 'Masukan desain &
            pengembangan',
        ]);
        SubClausul::create([
            'judul_clausul_id' => $judul8['id'],
            'clausul_id' => $clausul8_3['id'],
            'kode' => '8.3.4',
            'nama_sub_clausul' => 'Pemantauan desain & pengembangan',
        ]);
        SubClausul::create([
            'judul_clausul_id' => $judul8['id'],
            'clausul_id' => $clausul8_3['id'],
            'kode' => '8.3.5',
            'nama_sub_clausul' => 'Keluaran desain & pengembangan',
        ]);
        SubClausul::create([
            'judul_clausul_id' => $judul8['id'],
            'clausul_id' => $clausul8_3['id'],
            'kode' => '8.3.6',
            'nama_sub_clausul' => 'Perubahan desain & pengembangan',
        ]);
        $clausul8_4 = Clausul::create([
            'kode' => '8.4',
            'judul_clausul_id' => $judul8['id'],
            'nama_clausul' => 'Pengendalian proses eksternal,
            produk dan jasa',
        ]);
        SubClausul::create([
            'judul_clausul_id' => $judul8['id'],
            'clausul_id' => $clausul8_4['id'],
            'kode' => '8.4.1',
            'nama_sub_clausul' => 'Umum',
        ]);
        SubClausul::create([
            'judul_clausul_id' => $judul8['id'],
            'clausul_id' => $clausul8_4['id'],
            'kode' => '8.4.2',
            'nama_sub_clausul' => 'Tipe & jenis jangkauan pengendalian',
        ]);
        SubClausul::create([
            'judul_clausul_id' => $judul8['id'],
            'clausul_id' => $clausul8_4['id'],
            'kode' => '8.4.3',
            'nama_sub_clausul' => 'Informasi untuk penyedia
            eksternal',
        ]);
        $clausul8_5 = Clausul::create([
            'kode' => '8.5',
            'judul_clausul_id' => $judul8['id'],
            'nama_clausul' => 'Penyediaan produk dan jasa',
        ]);
        SubClausul::create([
            'judul_clausul_id' => $judul8['id'],
            'clausul_id' => $clausul8_5['id'],
            'kode' => '8.5.1',
            'nama_sub_clausul' => 'Pengendalian penyediaan produk
            dan jasa',
        ]);
        SubClausul::create([
            'judul_clausul_id' => $judul8['id'],
            'clausul_id' => $clausul8_5['id'],
            'kode' => '8.5.2',
            'nama_sub_clausul' => 'Identifikasi dan mampu telusur',
        ]);
        SubClausul::create([
            'judul_clausul_id' => $judul8['id'],
            'clausul_id' => $clausul8_5['id'],
            'kode' => '8.5.3',
            'nama_sub_clausul' => 'Penanganan barang milik pelanggan
            atau penyedia eksternal',
        ]);
        SubClausul::create([
            'judul_clausul_id' => $judul8['id'],
            'clausul_id' => $clausul8_5['id'],
            'kode' => '8.5.4',
            'nama_sub_clausul' => 'Pemeliharaan',
        ]);
        SubClausul::create([
            'judul_clausul_id' => $judul8['id'],
            'clausul_id' => $clausul8_5['id'],
            'kode' => '8.5.5',
            'nama_sub_clausul' => 'Aktivitas setelah pengiriman',
        ]);
        SubClausul::create([
            'judul_clausul_id' => $judul8['id'],
            'clausul_id' => $clausul8_5['id'],
            'kode' => '8.5.6',
            'nama_sub_clausul' => 'Pengendalian Perubahan',
        ]);
        $clausul8_6 = Clausul::create([
            'kode' => '8.6',
            'judul_clausul_id' => $judul8['id'],
            'nama_clausul' => 'Pelepasan produk dan jasa',
        ]);
        $clausul8_7 = Clausul::create([
            'kode' => '8.7',
            'judul_clausul_id' => $judul8['id'],
            'nama_clausul' => 'Pengendalian ketidaksesuaian output',
        ]);
        SubClausul::create([
            'judul_clausul_id' => $judul8['id'],
            'clausul_id' => $clausul8_7['id'],
            'kode' => '8.7.1',
            'nama_sub_clausul' => 'Organisasi harus memastikan bahwa
            output yang tidak sesuai dengan persyaratan
            telah diidentifikasi dan dikendalikan untuk
            mencegah penggunaan yang tidak disengaja
            atau terkirim',
        ]);
        SubClausul::create([
            'judul_clausul_id' => $judul8['id'],
            'clausul_id' => $clausul8_7['id'],
            'kode' => '8.7.2',
            'nama_sub_clausul' => 'Organisasi harus menyimpan informasi
            terdokumentasi yang menjelaskan ketidaksesuaian, tindakan yang dilakukan, konsekuensi yang diperoleh, serta mengidentifikasi otoritas yang memutuskan tindakan terkait ketidaksesuaian',
        ]);
        // 9
        $judul9 = JudulClausul::create([
            'kode' => '9',
            'judul_clausul' => 'Evaluasi Kinerja',
            'iso_id' => 1,
        ]);
        $clausul9_1 = Clausul::create([
            'kode' => '9.1',
            'judul_clausul_id' => $judul9['id'],
            'nama_clausul' => 'Pemantauan, pengukuran,
            analisa dan evaluasi',
        ]);
        SubClausul::create([
            'judul_clausul_id' => $judul9['id'],
            'clausul_id' => $clausul9_1['id'],
            'kode' => '9.1.1',
            'nama_sub_clausul' => 'Umum',
        ]);
        SubClausul::create([
            'judul_clausul_id' => $judul9['id'],
            'clausul_id' => $clausul9_1['id'],
            'kode' => '9.1.2',
            'nama_sub_clausul' => 'Kepuasan Pelanggan',
        ]);
        SubClausul::create([
            'judul_clausul_id' => $judul9['id'],
            'clausul_id' => $clausul9_1['id'],
            'kode' => '9.1.3',
            'nama_sub_clausul' => 'Analisa dan Evaluasi',
        ]);
        $clausul9_2 = Clausul::create([
            'kode' => '9.2',
            'judul_clausul_id' => $judul9['id'],
            'nama_clausul' => 'Audit Internal',
        ]);
        SubClausul::create([
            'judul_clausul_id' => $judul9['id'],
            'clausul_id' => $clausul9_2['id'],
            'kode' => '9.2.1',
            'nama_sub_clausul' => 'Organisasi harus melaksanakan audit
            internal dengan interval waktu terencana',
        ]);
        SubClausul::create([
            'judul_clausul_id' => $judul9['id'],
            'clausul_id' => $clausul9_2['id'],
            'kode' => '9.2.2',
            'nama_sub_clausul' => 'Organisasi harus merencanakan dan menerapkan program audit, menetapkan kriteria audit, memilih auditor dan mengadakan audit, memastikan hasil audit dilaporkan ke manajemen, melakukan koreksi langsung tanpa penundaan, dan informasi terdokumentasi sebagai bukti hasil audit',
        ]);
        $clausul9_3 = Clausul::create([
            'kode' => '9.3',
            'judul_clausul_id' => $judul9['id'],
            'nama_clausul' => 'Tinjauan Manajemen',
        ]);
        SubClausul::create([
            'judul_clausul_id' => $judul9['id'],
            'clausul_id' => $clausul9_3['id'],
            'kode' => '9.3.1',
            'nama_sub_clausul' => 'Umum',
        ]);
        SubClausul::create([
            'judul_clausul_id' => $judul9['id'],
            'clausul_id' => $clausul9_3['id'],
            'kode' => '9.3.2',
            'nama_sub_clausul' => 'Masukan Tinjauan Manajemen',
        ]);
        SubClausul::create([
            'judul_clausul_id' => $judul9['id'],
            'clausul_id' => $clausul9_3['id'],
            'kode' => '9.3.3',
            'nama_sub_clausul' => 'Keluaran Tinjauan Manajemen',
        ]);
        // 10
        $judul10 = JudulClausul::create([
            'kode' => '10',
            'judul_clausul' => 'Perbaikan',
            'iso_id' => 1,
        ]);
        $clausul10_1 = Clausul::create([
            'kode' => '10.1',
            'judul_clausul_id' => $judul10['id'],
            'nama_clausul' => 'Umum',
        ]);
        $clausul10_2 = Clausul::create([
            'kode' => '10.2',
            'judul_clausul_id' => $judul10['id'],
            'nama_clausul' => 'Ketidaksesuaian dan tindakan korektif',
        ]);
        SubClausul::create([
            'judul_clausul_id' => $judul10['id'],
            'clausul_id' => $clausul10_2['id'],
            'kode' => '10.2.1',
            'nama_sub_clausul' => 'Ketika suatu ketidaksesuaian
            muncul, termasuk munculnya ketidaksesuaian
            dari komplain, maka organisasi harus bereaksi terhadap ketidaksesuaian dan evaluasi kebutuhan tindakan untuk meminimalkan penyebab ketidaksesuaian',
        ]);
        SubClausul::create([
            'judul_clausul_id' => $judul10['id'],
            'clausul_id' => $clausul10_2['id'],
            'kode' => '10.2.2',
            'nama_sub_clausul' => 'Organisasi harus menyimpan
            informasi terdokumentasi sebagai bukti dari sifat dasar ketidaksesuaian dan hasil tindakan korektif',
        ]);
        $clausul10_3 = Clausul::create([
            'kode' => '10.3',
            'judul_clausul_id' => $judul10['id'],
            'nama_clausul' => 'Perbaikan Berkelanjutan',
        ]);
    }
}
