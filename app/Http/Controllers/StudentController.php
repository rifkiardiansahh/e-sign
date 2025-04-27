<?php

namespace App\Http\Controllers;

use App\Http\Classes\Collector;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class StudentController extends Controller
{
    /**
     * @var \App\Http\Classes\StudentService
     */
    protected $studentService;

    /**
     * Constructor
     *
     * @param Collector $collector
     */
    public function __construct(Collector $collector)
    {
        $this->studentService = $collector->Student();
    }

    /**
     * Handle signature request
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function signatureReq(Request $request)
    {
        $response = $this->studentService->signatureReq($request);
        return response()->json($response, Response::HTTP_OK);
    }

    /**
     * Get lecturer data
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLecturer(Request $request)
    {
        $lecturers = $this->studentService->getLecturer($request);
        return response()->json($lecturers, Response::HTTP_OK);
    }

    /**
     * Show student home page
     *
     * @return \Illuminate\View\View
     */
    public function getHome()
    {
        return view("student.index");
    }

    /**
     * Show application form
     *
     * @return \Illuminate\View\View
     */
    public function getPermohonan()
    {
        return view("student.permohonan");
    }

    /**
     * Get list of applications for DataTables
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getListPermohonan()
    {
        $data = $this->studentService->getListPermohonan();
        return datatables($data)->toJson();
    }
}
