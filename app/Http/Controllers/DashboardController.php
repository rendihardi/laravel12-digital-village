<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Interfaces\DashboardRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Spatie\Permission\Middleware\PermissionMiddleware;

class DashboardController extends Controller
{
    private DashboardRepositoryInterface $dashboardRepository;

    public function __construct(DashboardRepositoryInterface $dashboardRepository)
    {
        $this->dashboardRepository = $dashboardRepository;
    }

     public static function middleware()
    {
       return [
           new Middleware(PermissionMiddleware::using(['dashboard-menu|dashboard-list|dashboard-create|dashboard-edit|dashboard-delete']),only: ['index','getAllPaginated' ,'store', 'show', 'update', 'destroy']),
           new Middleware(PermissionMiddleware::using(['dashboard-create']),only: ['store']),
           new Middleware(PermissionMiddleware::using(['dashboard-edit']),only: ['update']),
           new Middleware(PermissionMiddleware::using(['dashboard-delete']),only: ['destroy']),
       ];
    }

    public function getDashboardData()
    {
        try {
            $dashboardData = $this->dashboardRepository->getDashboardData();
            return ResponseHelper::jsonResponse(true, 'success', $dashboardData, 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }
}
