<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AppSetting;
use Illuminate\Http\Request;

class EmployeeAttendanceSettingsController extends Controller
{
    public function edit()
    {
        return view('admin.karyawan.settings', ['settings' => AppSetting::getSettings()]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'employee_attendance_enabled' => ['nullable', 'boolean'],
            'employee_wifi_allowed_ips' => ['required', 'string', 'max:4000'],
            'employee_entry_start' => ['required', 'date_format:H:i'],
            'employee_entry_end' => ['required', 'date_format:H:i', 'after:employee_entry_start'],
            'employee_exit_start' => ['required', 'date_format:H:i'],
            'employee_exit_end' => ['required', 'date_format:H:i', 'after:employee_exit_start'],
        ]);

        $ips = collect(preg_split('/[\r\n,]+/', $data['employee_wifi_allowed_ips']))
            ->map(fn ($ip) => trim($ip))->filter()->unique()->values()->implode(',');

        AppSetting::getSettings()->update([
            'employee_attendance_enabled' => $request->boolean('employee_attendance_enabled'),
            'employee_wifi_allowed_ips' => $ips,
            'employee_entry_start' => $data['employee_entry_start'],
            'employee_entry_end' => $data['employee_entry_end'],
            'employee_exit_start' => $data['employee_exit_start'],
            'employee_exit_end' => $data['employee_exit_end'],
        ]);

        return back()->with('success', 'Pengaturan presensi karyawan berhasil disimpan.');
    }
}
