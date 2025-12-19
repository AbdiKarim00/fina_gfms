<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CabinetSecretaryController extends Controller
{
    /**
     * Get policy compliance dashboard data
     */
    public function getPolicyCompliance(): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'data' => [
                'compliance_rate' => 85.5,
                'violations' => 12,
                'pending_audits' => 5,
                'department_breakdown' => [
                    ['name' => 'Department of Roads', 'compliance' => 92],
                    ['name' => 'Traffic Management Unit', 'compliance' => 78],
                ]
            ]
        ]);
    }

    /**
     * Get budget oversight dashboard data
     */
    public function getBudgetOversight(): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'data' => [
                'total_budget' => 5000000,
                'executed_budget' => 3200000,
                'utilization_rate' => 64,
                'monthly_expenditure' => [
                    ['month' => 'Jan', 'amount' => 400000],
                    ['month' => 'Feb', 'amount' => 450000],
                    ['month' => 'Mar', 'amount' => 380000],
                ]
            ]
        ]);
    }

    /**
     * Post governance interventions
     */
    public function postInterventions(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'workflow_id' => 'required|string',
            'action' => 'required|string',
            'reason' => 'required|string',
        ]);

        // Logic for governance intervention

        return response()->json([
            'status' => 'success',
            'message' => 'Intervention recorded successfully',
            'intervention' => $validated
        ]);
    }

    /**
     * Get strategic performance dashboard data
     */
    public function getStrategicPerformance(): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'data' => [
                'kpis' => [
                    ['label' => 'Fleet Availability', 'value' => '94%'],
                    ['label' => 'Fuel Efficiency', 'value' => '12.5 km/l'],
                    ['label' => 'Carbon Footprint', 'value' => '450 tons CO2'],
                ]
            ]
        ]);
    }
}
