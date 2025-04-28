<?php

namespace App\Http\Classes;

use App\Models\Signature;
use App\Models\SignatureDetail;
use Carbon\Carbon;
use EllipticCurve\PrivateKey;
use EllipticCurve\Ecdsa;
use EllipticCurve\PublicKey;
use EllipticCurve\Signature as EllipticCurveSignature;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Lecturer
{
    public function unsigned()
    {
        $data = Signature::with([
            'signatureDetail' => function ($query) {
                return $query->select('id', 'hash', 'note');
            },
            'student' => function ($query) {
                return $query->select('id', 'fullname', 'userid');
            }
        ])
            ->whereHas('signatureDetail', function ($query) {
                return $query->where('signature', null)->where("deleted_at", null);
            })
            ->where('lecturer_id', Auth::user()->id)
            ->get();
        return $data;
    }

    // public function sign(Request $request)
    // {
    //     // $privateKey = new PrivateKey();
    //     // $publicKey = $privateKey->publicKey();
    //     // dd($privateKey->publicKey()->toString());
    //     // $message = "My test message";

    //     # Generate Signature
    //     // $signature = Ecdsa::sign($message, $privateKey);
    //     // dd($signature->toBase64());

    //     // dd(Ecdsa::verify($message, EllipticCurveSignature::fromBase64($signature->toBase64()), $publicKey));
    //     DB::beginTransaction();

    //     $signatureDetail = SignatureDetail::with('signature')->where('hash', $request->post('hash'))->first();
    //     // dd($signatureDetail->toArray());

    //     $privateKey = PrivateKey::fromString($signatureDetail['private_key']);
    //     $signature = Ecdsa::sign($signatureDetail['note'], $privateKey);
    //     $signatureString = $signature->_toString();
    //     // dd(EllipticCurveSignature::_fromString($signature->_toString()));
    //     $filename = time() . '_' . $request->file('signature')->getClientOriginalName();
    //     $request->file('signature')->storeAs('public/response', $filename);
    //     $store = SignatureDetail::where('hash', $request->post('hash'))
    //         ->update([
    //             'signature_key' => $signatureString,
    //             'signature' => 'response/' . $filename
    //         ]);
    //     DB::commit();
    //     // dd($signatureString);
    //     return $store;
    // }

    public function sign(Request $request)
    {
        $request->validate([
            'hash' => 'required',
            'signature' => 'required|file',
            'nomor_surat' => 'required|string|unique:signature_details,nomor_surat'
        ]);

        DB::beginTransaction();

        try {
            $signatureDetail = SignatureDetail::with('signature')
                ->where('hash', $request->post('hash'))
                ->firstOrFail();

            // Generate digital signature
            $privateKey = PrivateKey::fromString($signatureDetail->private_key);
            $signature = Ecdsa::sign($signatureDetail->note, $privateKey);
            $signatureString = $signature->_toString();

            // Handle file upload
            $filename = time() . '_' . $request->file('signature')->getClientOriginalName();
            $request->file('signature')->storeAs('public/response', $filename);

            // Update signature details dengan nomor surat
            $signatureDetail->update([
                'nomor_surat' => $request->nomor_surat, // Tambahkan nomor surat
                'signature_key' => $signatureString,
                'signature' => 'response/' . $filename
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Dokumen berhasil ditandatangani dan nomor surat ditambahkan'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menandatangani: ' . $e->getMessage()
            ], 500);
        }
    }

    public function signDelete(Request $request)
    {
        $sign = SignatureDetail::where('hash', $request->hash);
        $result = $sign->delete();
        return $result;
    }

    public function signatureHistory()
    {
        $data = Signature::with([
            'signatureDetail' => function ($query) {
                return $query->select('id', 'nomor_surat', 'hash', 'private_key', 'public_key', 'note', 'signature', 'deleted_at');
            },
            'student' => function ($query) {
                return $query->select('id', 'fullname', 'userid');
            }
        ])
            ->whereHas('signatureDetail', function ($query) {
                return $query->where('signature', null)->orWhere('signature', '!=', null);
            })
            ->where('lecturer_id', Auth::user()->id)
            ->get();
        return $data;
    }
}
