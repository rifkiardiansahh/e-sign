<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Classes\Collector;
use Symfony\Component\HttpFoundation\Response;

class LecturerController extends Controller
{
    protected $lecturerService;

    public function __construct(Collector $collector)
    {
        $this->lecturerService = $collector->Lecturer();
    }

    /**
     * Get unsigned documents
     * @return \Illuminate\Http\JsonResponse
     */
    public function unsigned()
    {
        $data = $this->lecturerService->unsigned();
        return datatables($data)->toJson();
    }

    /**
     * Sign a document
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function sign(Request $request)
    {
        $data = $this->lecturerService->sign($request);
        return response($data, Response::HTTP_OK);
    }

    /**
     * Delete a signature
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function signDelete(Request $request)
    {
        $data = $this->lecturerService->signDelete($request);
        return response($data, Response::HTTP_OK);
    }

    /**
     * Show signature history page
     * @return \Illuminate\View\View
     */
    public function getHistory()
    {
        return view('lecturer.history');
    }

    /**
     * Show lecturer home page
     * @return \Illuminate\View\View
     */
    public function getHome()
    {
        return view('lecturer.index');
    }

    /**
     * Get signature history data
     * @return \Illuminate\Http\JsonResponse
     */
    public function signatureHistory()
    {
        $data = $this->lecturerService->signatureHistory();
        return datatables($data)->toJson();
    }
}
