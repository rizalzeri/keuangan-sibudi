<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Midtrans\Snap;
use App\Models\User;
use Midtrans\Config;
use App\Models\Ekuit;
use App\Models\Order;
use App\Models\Profil;
use App\Models\Langganan;
use App\Models\Rekonsiliasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class LanggananController extends Controller
{
    public function index()
    {

        if (auth()->user()->referral) {
            $jenis = 'bumdesa';
        } else {
            $jenis = 'bumdes-bersama';
        }
        $langganans = Langganan::where('jenis', $jenis)->orderBy('jumlah_bulan', 'asc')->get();
        $langganan_pertama = Langganan::where('jenis', $jenis)->orderBy('jumlah_bulan', 'asc')->first();

        return view('langganan.index', ['langganans' => $langganans, 'mulai' => $langganan_pertama->harga]);
    }

    public function createTransaction(Request $request)
    {
        // Konfigurasi Midtrans
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;

        // Ambil durasi langganan dari request
        $duration = $request->input('subscription_duration');
        if (auth()->user()->referral) {
            $jenis = 'bumdesa';
        } else {
            $jenis = 'bumdes-bersama';
        }

        // Cari data langganan sesuai dengan durasi yang dipilih
        $langganan = Langganan::where('jenis', $jenis)
            ->where('jumlah_bulan', $duration)
            ->first();

        if (!isset($langganan->harga)) {
            $langganan_harga = 12900; // Harga default jika tidak ditemukan
            $nama_produk = "Langganan Default";
        } else {
            $langganan_harga = $langganan->harga;
            $nama_produk = "Langganan " . $langganan->jumlah_bulan . " Bulan";
        }

        $orderId = uniqid('order-');
        
        // set domain
        $baseUrl = url('/');
        $finishUrl = $baseUrl . '/langganan/berhasil';
        $unfinishUrl = $baseUrl . '/langganan';
        $errorUrl = $baseUrl . '/langganan';
        // Set data transaksi
        $params = [
            'transaction_details' => [
                'order_id' =>  $orderId,
                'gross_amount' => $langganan_harga + 6500, // Total transaksi
            ],
            'item_details' => [
                [
                    'id' => $langganan->id,
                    'price' => $langganan_harga,
                    'quantity' => 1,
                    'name' => $nama_produk,
                ],
                [
                    'id' => $langganan->id,
                    'price' => 6500,
                    'quantity' => 1,
                    'name' => 'Biaya Admin',
                ],
            ],
            'customer_details' => [
                'first_name' => auth()->user()->name,
                'last_name' => '',
                'email' => auth()->user()->email,
                'phone' => '',
            ],
            'custom_field1' => $duration,
            'callbacks' => [
                'finish' => $finishUrl,
                'unfinish' => $unfinishUrl,
                'error' => $errorUrl,
            ],
        ];

        // Buat transaksi Snap
        $transaction = \Midtrans\Snap::createTransaction($params);

        Order::create([
            'user_id'  => auth()->user()->id,
            'order_id' => $orderId,
            'amount'   => $langganan_harga + 6500,
            'duration' => $duration,
            'status'   => 'pending',
        ]);

        // Kembalikan redirect_url
        return response()->json([
            'redirect_url' => $transaction->redirect_url
        ]);
    }



    public function langgananSuccess(Request $request)
    {
        $user   = auth()->user();
        $orderId = $request->query('order_id');
        $status  = $request->query('transaction_status'); // contoh: settlement, pending, cancel

        // Jika status pending → batalkan transaksi
        if ($status === "pending") {
            return redirect('/langganan')->with('error', 'Pembayaran dibatalkan.');
        }

        // Cari order
        $order = Order::where('order_id', $orderId)->first();
        if (!$order) {
            return redirect('/langganan')->with('error', 'Order tidak ditemukan.');
        }

        // Hanya proses kalau sukses (settlement)
        if ($status === 'settlement') {
            $duration = (int) $order->duration;

            // Hitung langganan baru
            if ($user->tgl_langganan && \Carbon\Carbon::parse($user->tgl_langganan)->isFuture()) {
                $langganan_baru = \Carbon\Carbon::parse($user->tgl_langganan)->addMonths($duration);
            } else {
                $langganan_baru = \Carbon\Carbon::now()->addMonths($duration);
            }

            // Update user
            $user->update([
                'status'        => true,
                'tgl_langganan' => $langganan_baru->format('Y-m-d'),
            ]);

            // Update status order
            $order->update(['status' => 'settlement']);

            // Pastikan Ekuit & Rekonsiliasi ada
            Ekuit::firstOrCreate(['user_id' => $user->id]);
            if (!Rekonsiliasi::where('user_id', $user->id)->exists()) {
                Rekonsiliasi::insert([
                    ['posisi' => 'Kas di tangan', 'user_id' => $user->id, 'created_at' => now(), 'updated_at' => now()],
                    ['posisi' => 'Bank Jateng', 'user_id' => $user->id, 'created_at' => now(), 'updated_at' => now()],
                ]);
            }

            return redirect('/spj')->with(
                'success',
                'Pembayaran berhasil, langganan aktif sampai ' . $langganan_baru->translatedFormat('d F Y')
            );

        }

        // Kalau status lain (expire, cancel, failure)
        $order->update(['status' => $status]);

        return redirect('/langganan')->with('error', 'Pembayaran gagal atau dibatalkan.');
    }
}
